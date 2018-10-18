<?php
class TennisEditForm extends Form
{
	function TennisEditForm()
	{
		Form::Form('TennisEditForm');
		$this->link_css('packages/hotel/'.Portal::template('tennis').'/css/style.css');
		$this->link_css(Portal::template('core').'/css/jquery/autocomplete.css');		
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->add('price',new FloatType(true,'miss_price'));
		$this->add('time_in_hour',new TextType(true,'miss_time_in',0,5));
		$this->add('time_out_hour',new TextType(true,'miss_time_out',0,5));
		$this->add('people_number',new TextType(true,'miss_people_number',0,50));
		$this->add('tennis_staff.staff_id',new TextType(false,'miss_staff_id',0,50));
		$this->add('tennis_staff.code',new TextType(false,'miss_code',0,50));
		$this->add('tennis_staff.full_name',new TextType(false,'invalid_full_name',0,255));
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
					$guest_id =  DB::insert('tennis_guest',array('code'=>Url::get('code'),'full_name'));
				}
			}else{
				$guest_id = Url::iget('guest_id');
			}
			$array = array(
				'hotel_reservation_room_id',
				'court_id'=>Url::get('court_id'),
				'price',
				'time_in'=>$time_in,
				'time_out'=>$time_out,
				'guest_id'=>$guest_id,
				'people_number',
				'status',
				'note',
				'tip_amount',
				'discount',
				'tax',
				'total_amount',
				'full_name'
			);
			if(Url::get('cmd')=='edit'){
				$id = Url::iget('id');
				DB::update('tennis_reservation_court',$array,'id='.Url::iget('id'));
			}else{
				$id = DB::insert('tennis_reservation_court',$array);
			}
			//----------------------------------------------
			if(URl::get('staff_deleted_ids'))
			{
				$group_deleted_ids = explode(',',URl::get('staff_deleted_ids'));
				foreach($group_deleted_ids as $delete_id)
				{
					DB::delete_id('tennis_staff_court',$delete_id);
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
						$record['reservation_court_id'] = $id;
						if($record['id'])
						{
							DB::update('tennis_staff_court',$record,'id=\''.$record['id'].'\'');
						}
						else
						{
							unset($record['id']);
							if(DB::exists('SELECT id FROM tennis_staff WHERE id=\''.$record['staff_id'].'\'')){
								DB::insert('tennis_staff_court',$record);
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
					DB::delete_id('tennis_product_consumed',$delete_id);
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
						$record['reservation_court_id'] = $id;
						if($record['id'])
						{
							DB::update('tennis_product_consumed',$record,'id=\''.$record['id'].'\'');
						}
						else
						{
							unset($record['id']);
							if(DB::exists('SELECT id FROM tennis_product WHERE id=\''.$record['product_id'].'\'')){
								DB::insert('tennis_product_consumed',$record);
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
					DB::delete_id('tennis_product_hired',$delete_id);
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
						$record['reservation_court_id'] = $id;
						if($record['id'])
						{
							DB::update('tennis_product_hired',$record,'id=\''.$record['id'].'\'');
						}
						else
						{
							unset($record['id']);
							if(DB::exists('SELECT id FROM tennis_product WHERE id=\''.$record['product_id'].'\'')){
								DB::insert('tennis_product_hired',$record);
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
		$this->map['court_number'] = DB::fetch('SELECT NAME FROM tennis_court WHERE ID = '.Url::iget('court_id').'','name');
		$this->map['status_list'] = array(
			'BOOKED'=>'BOOKED',
			'CHECKIN'=>'CHECKIN',
			'CHECKOUT'=>'CHECKOUT'
		);
		$item = TennisDailySummary::$item;
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
						tennis_staff_court.*,
						tennis_staff.full_name
					FROM
						tennis_staff_court
						INNER JOIN tennis_staff ON tennis_staff.id = staff_id
					WHERE
						tennis_staff_court.reservation_court_id=\''.$item['id'].'\'
				';
				$mi_staff_group = DB::fetch_all($sql);
				$_REQUEST['mi_staff_group'] = $mi_staff_group;
			} 
			if(!isset($_REQUEST['mi_product_group']))
			{
				$sql = '
					SELECT
						tennis_product_consumed.*,(tennis_product_consumed.price*tennis_product_consumed.quantity) as amount,
						tennis_product.name,tennis_product.code
					FROM
						tennis_product_consumed
						INNER JOIN tennis_product ON tennis_product.id = product_id
					WHERE
						tennis_product_consumed.reservation_court_id=\''.$item['id'].'\'
				';
				$mi_product_group = DB::fetch_all($sql);
				$_REQUEST['mi_product_group'] = $mi_product_group;
			} 
			if(!isset($_REQUEST['mi_hired_product_group']))
			{
				$sql = '
					SELECT
						tennis_product_hired.*,(tennis_product_hired.price*tennis_product_hired.quantity) as amount,
						tennis_product.name,tennis_product.code
					FROM
						tennis_product_hired
						INNER JOIN tennis_product ON tennis_product.id = product_id
					WHERE
						tennis_product_hired.reservation_court_id=\''.$item['id'].'\'
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
		$reservations += $this->court_overdue();
		$this->map['hotel_reservation_room_id_list'] = array(''=>'---') + String::get_list($reservations);
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('court_reservation'):Portal::language('edit_court');
		$this->map['staffs'] = DB::fetch_all('SELECT * from tennis_staff order by full_name');
		$this->map['products'] = DB::fetch_all('SELECT code as id,name,price, id as product_id from tennis_product order by code');
		$this->parse_layout('edit',$this->map);
	}
	function court_overdue($cond = '')
	{
		$sql = '
			select 
				reservation_room.id
				,concat(CONCAT(traveller.first_name,\' \'),traveller.last_name) as agent_name
				,tennis_court.name
			from 
				tennis_reservation_court 
				inner join tennis_court on tennis_court.id=tennis_reservation_court.court_id
				left outer join traveller on traveller.id=reservation_room.traveller_id 
			where
				reservation_room.status=\'CHECKIN\' and FROM_UNIXTIME(reservation_room.time_out)<=\''.Date_Time::to_orc_date(date('d/m/Y',time())).'\'
				'.$cond.'
			order by
				tennis_court.name
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
					tennis_reservation_room.*
				FROM
					tennis_reservation_court
				WHERE
					tennis_reservation_room.court_id = '.Url::get('court_id').'
					AND ((time_in<\''.$time_in.'\' and time_out>\''.$time_in.'\') or (time_in>\''.$time_in.'\' and time_out<\''.$time_out.'\'))
			';
			$items = DB::fetch_all($sql);
			if(!empty($items)){
				foreach($items as $value){
					$form->error('conflick',Portal::language('conflict_to').' <a target="_blank" href="'.Url::build_current(array('cmd'=>'edit','id'=>$value['id'],'court_id'=>$value['court_id'])).'">#'.$value['id'].'</a>');
				}
			}
		}
	}
}
?>