<?php
class EditCustomerRatePolicyForm extends Form
{
	function EditCustomerRatePolicyForm()
	{
		Form::Form();
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');		
		$this->add('customer_rate_policy.name',new TextType(true,'miss_name',0,255));
		$this->add('customer_rate_policy.start_date',new DateType(true,'invalid_start_date'));
		$this->add('customer_rate_policy.end_date',new DateType(true,'invalid_end_date'));
		$this->add('customer_rate_policy.rate_1_adult',new DateType(true,'miss_rate_with_1_adult'));
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
					$description .= 'Delete policy id: '.$delete_id.'<br>';
					DB::delete_id('customer_rate_policy',$delete_id);
				}
			}
			if(isset($_REQUEST['mi_policy_group']))
			{	
				$description .= '<hr>';
				foreach($_REQUEST['mi_policy_group'] as $key=>$record)
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
						$record['rate_1_adult'] = System::calculate_number($record['rate_1_adult']);
						$record['rate_2_adults'] = System::calculate_number($record['rate_2_adults']);
						$record['rate_3_adults'] = System::calculate_number($record['rate_3_adults']);
						$record['rate_extra_adults'] = System::calculate_number($record['rate_extra_adults']);
						$record['rate_1_child'] = System::calculate_number($record['rate_1_child']);
						$record['rate_2_children'] = System::calculate_number($record['rate_2_children']);
						$record['rate_3_children'] = System::calculate_number($record['rate_3_children']);
						$record['rate_extra_children'] = System::calculate_number($record['rate_extra_children']);
						$record_id = false;
						if($record['id']){
							$record_id = $record['id'];	
						}
						$policys = $this->check_mi_policy_group($record_id,$record['name'],$record['start_date'],$record['end_date'],$record['room_level_id'],$customer_id);
						if(!$policys){
							if($record['id'])
							{
								$id = $record['id'];
								unset($record['id']);
								$description .= 'Edit [Policy name: '.$record['name'].', 1 adult: '.$record['rate_1_adult'].', 2 adult: '.$record['rate_2_adults'].']<br>';
								DB::update('customer_rate_policy',$record,'id=\''.$id.'\'');
							}
							else
							{
								if(isset($record['id'])){
									unset($record['id']);
								}
								$description .= 'Edit [Policy name: '.$record['name'].', 1 adult: '.$record['rate_1_adult'].', 2 adult: '.$record['rate_2_adults'].']<br>';
								DB::insert('customer_rate_policy',$record);
							}
						}else{
							$this->error('['.$policys.']','xung đột dữ liệu');
							return;
						}
					}
				}
			} 
			$log_title = 'CustomerRatePolicy: #'.$id.'';
			System::log($log_action,$log_title,$description,$id);// Edited in 09/03/2011
			Url::redirect_current(array('group_id','customer_id'));
		}
	}
	function draw()
	{
		$cond = '';
		if(Url::get('policy_id')){
			$policy_id = Url::get('policy_id');	
			$policy = explode(',',$policy_id);
			$cond .= 'AND (';
			for($i=0;$i<count($policy);$i++){
				if($i==0){
					$cond .= ' crp.id = '.$policy[$i].'';	
				}else{
					$cond .= ' or crp.id = '.$policy[$i].'';		
				}
			}
			$cond .= ')';
		}
		if(Url::get('id')){
			$cond .= ' and crp.id = '.Url::get('id').'';	
		}
		$this->map = array();
		$item = CustomerRatePolicy::$item;
		if($item){
			if(!isset($_REQUEST['mi_policy_group']))
			{
				$sql = '
					SELECT
						crp.*
					FROM
						customer_rate_policy crp
					WHERE 1>0
						'.$cond.'
						AND crp.customer_id=\''.$item['id'].'\' 
						AND crp.portal_id = \''.PORTAL_ID.'\'
					ORDER BY
						crp.name
				';
				$mi_policy_group = DB::fetch_all($sql);
				foreach($mi_policy_group as $k=>$v){
					$mi_policy_group[$k]['rate_1_adult'] = System::display_number_report($v['rate_1_adult']);
					$mi_policy_group[$k]['rate_2_adults'] = System::display_number_report($v['rate_2_adults']);
					$mi_policy_group[$k]['rate_3_adults'] = System::display_number_report($v['rate_3_adults']);
					$mi_policy_group[$k]['rate_extra_adults'] = System::display_number_report($v['rate_extra_adults']);
					$mi_policy_group[$k]['rate_1_child'] = System::display_number_report($v['rate_1_child']);
					$mi_policy_group[$k]['rate_2_children'] = System::display_number_report($v['rate_2_children']);
					$mi_policy_group[$k]['rate_3_children'] = System::display_number_report($v['rate_3_children']);
					$mi_policy_group[$k]['rate_extra_children'] = System::display_number_report($v['rate_extra_children']);
					$mi_policy_group[$k]['start_date'] = Date_Time::convert_orc_date_to_date($v['start_date'],'/');
					$mi_policy_group[$k]['end_date'] = Date_Time::convert_orc_date_to_date($v['end_date'],'/');
				}
				$_REQUEST['mi_policy_group'] = $mi_policy_group;
			} 
		}
		$room_level_options = '';
		$room_levels = DB::fetch_all('SELECT id,name FROM room_level WHERE portal_id=\''.PORTAL_ID.'\'');
		foreach($room_levels as $key=>$value){
			$room_level_options .= '<option value="'.$key.'">'.$value['name'].'</option>';
		}
		$this->map['room_level_options'] = $room_level_options;
		$this->map['type_options'] = '<option value="ROOM_QUANTITY">'.Portal::language('room_quantity').'</option>';
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_customer_rate'):Portal::language('edit_customer_rate');
		$this->map['customer_name'] = DB::fetch('SELECT id,name FROM customer where id = '.Url::iget('customer_id').'','name');
		$this->parse_layout('edit',$this->map);
	}
	function check_mi_policy_group($id=false,$name,$start_date,$end_date, $room_level,$customer_id){
		$cond = '1=1';
		if($id){
			$cond .= ' and id <> '.$id.'';
		}
		$sql = 'SELECT * 
				FROM  customer_rate_policy 
				WHERE '.$cond.'
					AND name = \''.$name.'\' 
					AND room_level_id = '.$room_level.' 
					AND ((START_DATE>= \''.$start_date.'\' AND START_DATE<= \''.$end_date.'\') OR (END_DATE>=\''.$start_date.'\' AND END_DATE<=\''.$end_date.'\'))
					AND customer_id=\''.$customer_id.'\' 
					AND portal_id = \''.PORTAL_ID.'\'';
		$policy = DB::fetch_all($sql);
		if($policy){
			return $name;	
		}else{
			return false;	
		}
	}
}
?>