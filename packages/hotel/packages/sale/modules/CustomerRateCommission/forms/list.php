<?php
class ListCustomerRateCommissionForm extends Form
{
	function ListCustomerRateCommissionForm()
	{
		Form::Form('ListCustomerRateCommissionForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
	}
	function draw()
	{
		$this->map = array();
		$item_per_page = 500;
		$cond = 'crc.portal_id = \''.PORTAL_ID.'\'
			'.(Url::get('customer_id')?' AND crc.customer_id='.Url::iget('customer_id').'':'').'
			';
		$this->map['title'] = Portal::language('customer_rate_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				customer_rate_commission crc
				INNER JOIN customer ON customer.id = crc.customer_id
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
					crc.*,customer.name as customer_name,
                    row_number()  OVER(ORDER BY customer.name) as rownumber
				FROM
					customer_rate_commission crc
					INNER JOIN customer on customer.id = crc.customer_id
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
            //System::debug($value['start_date']);
		}
        
		$this->map['items'] = $items;
		$sql = 'SELECT
					CUSTOMER.*,CUSTOMER_GROUP.NAME AS group_name
				FROM
					CUSTOMER 
					LEFT OUTER JOIN CUSTOMER_GROUP ON CUSTOMER_GROUP.ID = CUSTOMER.GROUP_ID
			
				ORDER BY 
					CUSTOMER.name	
				';
                //	WHERE	
					//customer.group_id = \'TOURISM\'
		$this->map['customer_id_list'] = String::get_list(DB::fetch_all($sql));
		$this->parse_layout('list',$this->map);
	}	
}
?>