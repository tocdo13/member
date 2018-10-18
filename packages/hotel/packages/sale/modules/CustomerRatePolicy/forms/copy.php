<?php
class CopyCustomerRatePolicyForm extends Form
{
	function CopyCustomerRatePolicyForm()
	{
		Form::Form('CopyCustomerRatePolicyForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->add('customer_id',new IDType(true,'miss_customer','customer'));
	}
	function on_submit(){
		if($this->check()){
			if(Url::get('copy_customer_id') and Url::get('item_check_box')){
				$customers = Url::get('copy_customer_id');
				$rate_policies =  Url::get('item_check_box');
				if(!empty($customers) and !empty($rate_policies)){
					foreach($rate_policies as $value){
						if($rate = DB::select('customer_rate_policy','id='.$value)){
							foreach($customers as $v){
								$array = array(
									'customer_id'=>$v,
									'portal_id'=>$rate['portal_id'],
									'start_date'=>$rate['start_date'],
									'end_date'=>$rate['end_date'],
									'room_level_id'=>$rate['room_level_id'],
									'rate_1_adult'=>$rate['rate_1_adult'],
									'rate_2_adults'=>$rate['rate_1_adult'],
									'rate_3_adults'=>$rate['rate_3_adults'],
									'rate_4_adults'=>$rate['rate_4_adults'],
									'rate_extra_adults'=>$rate['rate_extra_adults'],
									'rate_1_child'=>$rate['rate_1_child'],
									'rate_2_children'=>$rate['rate_2_children'],
									'rate_3_children'=>$rate['rate_3_children'],
									'rate_4_children'=>$rate['rate_4_children'],
									'rate_extra_children'=>$rate['rate_extra_children'],
									'commission'=>$rate['commission'],
									'name'=>$rate['name']   
								);
								//if(!DB::exists('SELECT id FROM customer_rate_policy WHERE customer_id = '.$v.' and room_level_id = '.$rate['room_level_id'].' AND start_date=\''.$rate['start_date'].'\' AND end_date=\''.$rate['end_date'].'\' AND portal_id = \''.$rate['portal_id'].'\' and name = \''.$rate['name'].'\''))
								{
									DB::insert('customer_rate_policy',$array);
								}
							}
						}
					}
					Url::redirect('customer',array('group_id'=>'TOURISM'));
				}
			}
		}
	}
	function draw()
	{
		$this->map = array();
		$item_per_page = 1000;
		$cond = 'crp.portal_id = \''.PORTAL_ID.'\'
			'.(Url::get('customer_id')?' AND crp.customer_id='.Url::iget('customer_id').'':'').'
			';
		$this->map['title'] = Portal::language('customer_rate_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				customer_rate_policy crp
				INNER JOIN customer ON customer.id = crp.customer_id
			WHERE
				'.$cond.'
		';
		require_once 'packages/core/includes/utils/paging.php';
		$this->map['total'] =  DB::fetch($sql,'acount');
		$this->map['paging'] =  paging($this->map['total'],$item_per_page,10,$smart=false,$page_name='page_no',array('action'));
		$sql = '
			SELECT * FROM
			(
				SELECT
					crp.*,customer.name as customer_name,room_level.name as room_level_name,
					row_number() OVER(ORDER BY customer.name,room_level.name,ABS(crp.start_date - Sysdate)) AS rownumber
				FROM
					customer_rate_policy crp
					INNER JOIN customer on customer.id = crp.customer_id
					INNER JOIN room_level ON room_level.id = crp.room_level_id 
				WHERE	
					'.$cond.'
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
		$items = DB::fetch_all($sql);
		$i = 1;
		foreach($items as $key=>$value){
			$items[$key]['start_date'] = Date_Time::convert_orc_date_to_date($value['start_date'],'/');
			$items[$key]['end_date'] = Date_Time::convert_orc_date_to_date($value['end_date'],'/');
			$items[$key]['i'] = $i++;
			$items[$key]['number'] = 0;
		}
		$this->map['items'] = $items;
		$sql = 'SELECT
					CUSTOMER.*,CUSTOMER_GROUP.NAME AS group_name
				FROM
					CUSTOMER 
					LEFT OUTER JOIN CUSTOMER_GROUP ON CUSTOMER_GROUP.ID = CUSTOMER.GROUP_ID
				WHERE	
					customer.group_id = \'TOURISM\'
				ORDER BY 
					CUSTOMER.name	
				';
		$customers = DB::fetch_all($sql);
		$customer_options = '';
		foreach($customers as $key=>$value){
			$customer_options .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';
		}
		$this->map['customer_options'] = $customer_options;
		$this->map['customer_name'] = DB::fetch('SELECT id,name FROM customer WHERE id = '.Url::iget('customer_id').'','name');
		$this->parse_layout('copy',$this->map);
	}	
}
?>