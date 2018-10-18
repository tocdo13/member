<?php
class TennisEditForm extends Form
{
	function TennisEditForm()
	{
		Form::Form('TennisEditForm');
		$this->link_css('packages/hotel/'.Portal::template('swimming_pool').'/css/style.css');
		$this->link_css(Portal::template('core').'/css/jquery/autocomplete.css');		
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->add('price',new FloatType(true,'miss_price'));
		$this->add('time_in_hour',new TextType(true,'miss_time_in',0,5));
		$this->add('time_out_hour',new TextType(true,'miss_time_out',0,5));
		$this->add('people_number',new TextType(true,'miss_people_number',0,50));
		$this->add('swimming_pool_staff.staff_id',new TextType(false,'miss_staff_id',0,50));
		$this->add('swimming_pool_staff.code',new TextType(false,'miss_code',0,50));
		$this->add('swimming_pool_staff.full_name',new TextType(false,'invalid_full_name',0,255));
	}
	function on_submit(){
		if($this->check()){
			$this->check_conflick($this);
			if($this->is_error()){
				return;
			}
			$time_in = 0;
			$time_out = 0;
			if(Url::get('time_in_hour') and Url::get('time_out_hour'))
			{
				$arr = explode(':',Url::get('time_in_hour'));
				$time_in = Date_Time::to_time(Url::get('time_in_date'))+ intval($arr[0])*3600+intval($arr[1])*60;
				$arr = explode(':',Url::get('time_out_hour'));
				$time_out = Date_Time::to_time(Url::get('time_out_date'))+ intval($arr[0])*3600+intval($arr[1])*60;
			}
			$guest_id = 0;
			if(!Url::get('guest_id')){
				if(Url::get('code')){
					$guest_id =  DB::insert('swimming_pool_guest',array('code'=>Url::get('code'),'full_name'));
				}
			}else{
				$guest_id = Url::iget('guest_id');
			}
			$array = array(
				'hotel_reservation_room_id',
				'swimming_pool_id'=>Url::get('swimming_pool_id'),
				'price'=>str_replace(',','',Url::get('price')),
				'time_in'=>$time_in,
				'time_out'=>$time_out,
				'guest_id'=>$guest_id,
				'people_number',
				'status',
				'note',
				'tip_amount'=>str_replace(',','',Url::get('tip_amount')),
				'discount',
				'tax',
				'total_amount'=>str_replace(',','',Url::get('total_amount')),
				'full_name'
			);
			if(Url::get('cmd')=='edit'){
				$id = Url::iget('id');
				DB::update('swimming_pool_reservation_pool',$array,'id='.Url::iget('id'));
			}else{
				$id = DB::insert('swimming_pool_reservation_pool',$array);
			}
			//----------------------------------------------
			if(URl::get('staff_deleted_ids'))
			{
				$group_deleted_ids = explode(',',URl::get('staff_deleted_ids'));
				foreach($group_deleted_ids as $delete_id)
				{
					DB::delete_id('swimming_pool_staff_pool',$delete_id);
				}
			}	
			if(isset($_REQUEST['mi_staff_group']))
			{	
				foreach($_REQUEST['mi_staff_group'] as $key=>$record)
				{
					unset($record['full_name']);
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
						$record['reservation_pool_id'] = $id;
						if($record['id'])
						{
							DB::update('swimming_pool_staff_pool',$record,'id=\''.$record['id'].'\'');
						}
						else
						{
							unset($record['id']);
							if(DB::exists('SELECT id FROM swimming_pool_staff WHERE id=\''.$record['staff_id'].'\'')){
								DB::insert('swimming_pool_staff_pool',$record);
							}
						}
					}
				}
			}
			//----------------------------------------------
			if(URl::get('product_deleted_ids'))
			{
				$group_deleted_ids = explode(',',URl::get('product_deleted_ids'));
				foreach($group_deleted_ids as $delete_id)
				{
					DB::delete_id('swimming_pool_product_consumed',$delete_id);
				}
			}	
			if(isset($_REQUEST['mi_product_group']))
			{
				foreach($_REQUEST['mi_product_group'] as $key=>$record)
				{
					unset($record['name']);
					unset($record['code']);
					unset($record['amount']);
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
						$record['reservation_pool_id'] = $id;
						if($record['id'])
						{
							DB::update('swimming_pool_product_consumed',$record,'id=\''.$record['id'].'\'');
						}
						else
						{
							unset($record['id']);
							if(DB::exists('SELECT id FROM swimming_pool_product WHERE id=\''.$record['product_id'].'\'')){
								DB::insert('swimming_pool_product_consumed',$record);
							}
						}
					}
				}
			}
			//----------------------------------------------
			if(URl::get('hired_product_deleted_ids'))
			{
				$group_deleted_ids = explode(',',URl::get('hired_product_deleted_ids'));
				foreach($group_deleted_ids as $delete_id)
				{
					DB::delete_id('swimming_pool_product_hired',$delete_id);
				}
			}	
			if(isset($_REQUEST['mi_hired_product_group']))
			{
				foreach($_REQUEST['mi_hired_product_group'] as $key=>$record)
				{
					unset($record['name']);
					unset($record['code']);
					unset($record['amount']);
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
						$record['reservation_pool_id'] = $id;
						if($record['id'])
						{
							DB::update('swimming_pool_product_hired',$record,'id=\''.$record['id'].'\'');
						}
						else
						{
							unset($record['id']);
							if(DB::exists('SELECT id FROM swimming_pool_product WHERE id=\''.$record['product_id'].'\'')){
								DB::insert('swimming_pool_product_hired',$record);
							}
						}
					}
				}
			}
			//----------------------------------------------			
			/*else{
				$this->error('product_code','miss_product_code');
				return;
			}*/
			echo '<script>if(window.opener){window.opener.history.go(0);} window.close();</script> ';
			exit();
			//Url::redirect_current();
		}
	}
	function draw()
	{
		$this->map = array(); 
		$this->map['swimming_pool_number'] = DB::fetch('SELECT NAME FROM swimming_pool WHERE ID = '.Url::iget('swimming_pool_id').'','name');
		$this->map['status_list'] = array(
			'BOOKED'=>'BOOKED',
			'CHECKIN'=>'CHECKIN',
			'CHECKOUT'=>'CHECKOUT'
		);
		$item = SwimmingPoolDailySummary::$item;
		if($item){
			foreach($item as $key=>$value){
				if($key=='time_in'){
					$_REQUEST['time_in_date'] = date('d/m/Y',$value);
					$_REQUEST['time_in_hour'] = date('H:i',$value);
				}
				if($key=='time_out'){
					$_REQUEST['time_out_date'] = date('d/m/Y',$value);
					$_REQUEST['time_out_hour'] = date('H:i',$value);
				}
				if(!isset($_REQUEST[$key])){
					$_REQUEST[$key] = $value;
				}
			}
			if(!isset($_REQUEST['mi_staff_group']))
			{
				$sql = '
					SELECT
						swimming_pool_staff_pool.*,
						swimming_pool_staff.full_name
					FROM
						swimming_pool_staff_pool
						INNER JOIN swimming_pool_staff ON swimming_pool_staff.id = staff_id
					WHERE
						swimming_pool_staff_pool.reservation_pool_id=\''.$item['id'].'\'
				';
				$mi_staff_group = DB::fetch_all($sql);
				$_REQUEST['mi_staff_group'] = $mi_staff_group;
			} 
			if(!isset($_REQUEST['mi_product_group']))
			{
				$sql = '
					SELECT
						swimming_pool_product_consumed.*,(swimming_pool_product_consumed.price*swimming_pool_product_consumed.quantity) as amount,
						swimming_pool_product.name,swimming_pool_product.code
					FROM
						swimming_pool_product_consumed
						INNER JOIN swimming_pool_product ON swimming_pool_product.id = product_id
					WHERE
						swimming_pool_product_consumed.reservation_pool_id=\''.$item['id'].'\'
				';
				$mi_product_group = DB::fetch_all($sql);
				$_REQUEST['mi_product_group'] = $mi_product_group;
			} 
			if(!isset($_REQUEST['mi_hired_product_group']))
			{
				$sql = '
					SELECT
						swimming_pool_product_hired.*,(swimming_pool_product_hired.price*swimming_pool_product_hired.quantity) as amount,
						swimming_pool_product.name,swimming_pool_product.code
					FROM
						swimming_pool_product_hired
						INNER JOIN swimming_pool_product ON swimming_pool_product.id = product_id
					WHERE
						swimming_pool_product_hired.reservation_pool_id=\''.$item['id'].'\'
				';
				$mi_hired_product_group = DB::fetch_all($sql);
				$_REQUEST['mi_hired_product_group'] = $mi_hired_product_group;
			} 
		}else{
			if(!Url::get('date')){
				if(!isset($_REQUEST['time_in_date'])){
					$_REQUEST['time_in_date'] = date('d/m/Y',time());
				}
				if(!isset($_REQUEST['time_out_date'])){
					$_REQUEST['time_out_date'] = date('d/m/Y',time());
				}
			}else{
				$_REQUEST['time_in_date'] = $_REQUEST['time_out_date'] = Url::get('date');
			}
			if(!isset($_REQUEST['tax'])){
				$_REQUEST['tax'] = '5';
			}
			if(!isset($_REQUEST['time_in_hour'])){
				$_REQUEST['time_in_hour'] = date('H:i',time());
			}
			if(!isset($_REQUEST['time_out_hour'])){
				$_REQUEST['time_out_hour'] = date('H:i',time()+3600);
			}
		}
		$sql = '
			select
				reservation_room.id
				,concat(CONCAT(traveller.first_name,\' \'),traveller.last_name) as agent_name
				,room.name
			from 
				reservation_room 
				inner join room on room.id=reservation_room.room_id
				inner join room_status on room_status.RESERVATION_ID  =  RESERVATION_ROOM.RESERVATION_ID 
				left outer join traveller on traveller.id=reservation_room.traveller_id 
			where 
				reservation_room.status=\'CHECKIN\'
				and room_status.status = \'OCCUPIED\'
				and room_status.IN_DATE = \''.Date_time::to_orc_date(date('d/m/Y')).'\'
			order by 
				room.name
			';
		$reservations = DB::fetch_all($sql);
		//$reservations += $this->swimming_pool_overdue();
		$this->map['hotel_reservation_room_id_list'] = array(''=>'---') + String::get_list($reservations);
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('reservation'):Portal::language('edit_reservation');
		$this->map['staffs'] = DB::fetch_all('SELECT * from swimming_pool_staff order by full_name');
		$this->map['products'] = DB::fetch_all('SELECT code as id,name,price, id as product_id from swimming_pool_product order by code');
		$this->parse_layout('edit',$this->map);
	}
	function swimming_pool_overdue($cond = '')
	{
		$sql = '
			select 
				reservation_room.id
				,concat(CONCAT(traveller.first_name,\' \'),traveller.last_name) as agent_name
				,swimming_pool.name
			from 
				swimming_pool_reservation_pool
				inner join swimming_pool on swimming_pool.id=swimming_pool_reservation_pool.swimming_pool_id
				inner join swimming_pool_status on swimming_pool_status.RESERVATION_ID  =  swimming_pool_reservation_pool.RESERVATION_ID 
				left outer join traveller on traveller.id=reservation_room.traveller_id 
			where
				reservation_room.status=\'CHECKIN\' and FROM_UNIXTIME(reservation_room.time_out)<=\''.Date_Time::to_orc_date(date('d/m/Y',time())).'\'
				'.$cond.'
			order by
				swimming_pool.name
		';
		return DB::fetch_all($sql);
	}
	function check_conflick($form){
		// confick time
		if(Url::get('time_in_hour') and Url::get('time_out_hour'))
		{
			$arr = explode(':',Url::get('time_in_hour'));
			$time_in = Date_Time::to_time(Url::get('time_in_date'))+ intval($arr[0])*3600+intval($arr[1])*60;
			$arr = explode(':',Url::get('time_out_hour'));
			$time_out = Date_Time::to_time(Url::get('time_out_date'))+ intval($arr[0])*3600+intval($arr[1])*60;
			if($time_out <= $time_in){
				$form->error('a','time_out_has_to_more_than_time_in');
			}
			$sql = '
				SELECT 
					swimming_pool_reservation_pool.*
				FROM
					swimming_pool_reservation_pool
				WHERE
					swimming_pool_reservation_pool.swimming_pool_id = '.Url::get('swimming_pool_id').'
					AND ((time_in<\''.$time_in.'\' and time_out>\''.$time_in.'\') or (time_in>\''.$time_in.'\' and time_out<\''.$time_out.'\'))
			';
			$items = DB::fetch_all($sql);
			if(!empty($items)){
				foreach($items as $value){
					//$form->error('conflick',Portal::language('conflict_to').' <a target="_blank" href="'.Url::build_current(array('cmd'=>'edit','id'=>$value['id'],'swimming_pool_id'=>$value['swimming_pool_id'])).'">#'.$value['id'].'</a>');
				}
			}
		}
	}
}
?>