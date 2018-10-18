<?php
class EditCustomerRateCommissionForm extends Form
{
	function EditCustomerRateCommissionForm()
	{
		Form::Form();
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');		
		$this->add('customer_rate_commission.name',new TextType(true,'miss_name',0,255));
		$this->add('customer_rate_commission.start_date',new DateType(true,'invalid_start_date'));
		$this->add('customer_rate_commission.end_date',new DateType(true,'invalid_end_date'));
		$this->add('customer_rate_commission.commission_rate',new DateType(true,'miss_rate_with_1_adult'));
	}
	function on_submit(){
		if($this->check()){
			$description = '';
			if(Url::get('cmd')=='edit'){
				$log_action = 'edit';
				$customer_id = Url::iget('customer_id');
			}else{
				$log_action = 'add';	
				$customer_id = Url::iget('customer_id');
			}
			if(URl::get('group_deleted_ids')){
				$group_deleted_ids = explode(',',URl::get('group_deleted_ids'));
				$description .= '<hr>';
				foreach($group_deleted_ids as $delete_id)
				{
					$description .= 'Delete commission id: '.$delete_id.'<br>';
					DB::delete_id('customer_rate_commission',$delete_id);
				}
			}
			if(isset($_REQUEST['mi_commission_group']))
			{	
				$description .= '<hr>';
				foreach($_REQUEST['mi_commission_group'] as $key=>$record)
				{
					$empty = true;
					foreach($record as $record_value)
					{
						if($record_value)
						{
							$empty = false;
						}
					}
					if(!$empty)
					{
						$record['start_date'] = Date_Time::to_orc_date($record['start_date']);
						$record['end_date'] = Date_Time::to_orc_date($record['end_date']);
						$record['customer_id'] = $customer_id;
						$record['portal_id'] = PORTAL_ID;
						$record['commission_rate'] = System::calculate_number($record['commission_rate']);
						$record_id = false;
						if($record['id']){
							$record_id = $record['id'];	
						}
						$commission = $this->check_mi_commission_group($record_id,$record['start_date'],$record['end_date'],$customer_id);
						if(!$commission){
							if($record['id'])
							{
								$id = $record['id'];
								unset($record['id']);
								$description .= 'Edit [Rate commission: '.$record['commission_rate'].']<br>';
								DB::update('customer_rate_commission',$record,'id=\''.$id.'\'');
							}
							else
							{
								if(isset($record['id'])){
									unset($record['id']);
								}
								$description .= 'Edit [Rate commission: '.$record['commission_rate'].']<br>';
								DB::insert('customer_rate_commission',$record);
							}
						}else{
							$this->error('['.$commission.']','xung đột dữ liệu');
							return;
						}
					}
				}
			} 
			$log_title = 'CustomerRateCommission: #'.$id.'';
			System::log($log_action,$log_title,$description,$id);// Edited in 09/03/2011
			Url::redirect_current(array('group_id','customer_id'));
		}
	}
	function draw()
	{
		$cond = '';
		if(Url::get('commission_id')){
			$commission_id = Url::get('commission_id');	
			$commission = explode(',',$commission_id);
			$cond .= 'AND (';
			for($i=0;$i<count($commission);$i++){
				if($i==0){
					$cond .= ' crp.id = '.$commission[$i].'';	
				}else{
					$cond .= ' or crp.id = '.$commission[$i].'';		
				}
			}
			$cond .= ')';
		}
		if(Url::get('id')){
			$cond .= ' and crc.id = '.Url::get('id').'';	
		}
		$this->map = array();
		$item = CustomerRateCommission::$item;
		if($item){
			if(!isset($_REQUEST['mi_commission_group']))
			{
				$sql = '
					SELECT
						crc.*
					FROM
						customer_rate_commission crc
					WHERE 1>0
						'.$cond.'
						AND crc.customer_id=\''.$item['id'].'\' 
						AND crc.portal_id = \''.PORTAL_ID.'\'
					ORDER BY
						crc.id
				';
				$mi_commssion_group = DB::fetch_all($sql);
				foreach($mi_commssion_group as $k=>$v){
					$mi_commssion_group[$k]['commission_rate'] = System::display_number($v['commission_rate']);
					$mi_commssion_group[$k]['start_date'] = Date_Time::convert_orc_date_to_date($v['start_date'],'/');
					$mi_commssion_group[$k]['end_date'] = Date_Time::convert_orc_date_to_date($v['end_date'],'/');
				}
				$_REQUEST['mi_commission_group'] = $mi_commssion_group;
			} 
		}
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_customer_rate'):Portal::language('edit_customer_rate');
		$this->map['customer_name'] = DB::fetch('SELECT id,name FROM customer where id = '.Url::iget('customer_id').'','name');
		$this->parse_layout('edit',$this->map);
	}
	function check_mi_commission_group($id=false,$start_date,$end_date,$customer_id){
		$cond = '1=1';
		if($id){
			$cond .= ' and id <> '.$id.'';
		}
		$sql = 'SELECT * 
				FROM  customer_rate_commission 
				WHERE '.$cond.'
					AND ((START_DATE>= \''.$start_date.'\' AND START_DATE<= \''.$end_date.'\') OR (END_DATE>=\''.$start_date.'\' AND END_DATE<=\''.$end_date.'\'))
					AND customer_id=\''.$customer_id.'\' 
					AND portal_id = \''.PORTAL_ID.'\'';
		$commission = DB::fetch_all($sql);
		if($commission){
			return $id;	
		}else{
			return false;	
		}
	}
}
?>