<?php
function update_reservation_room(&$form, $id, &$title, &$description, &$customer_name, &$change_status,$old_reservation_room)
{
	$currencies = DB::select_all('currency','allow_payment=1');
	$services = DB::select_all('service');
	$min_arr_date='';
	//System::Debug($old_reservation_room);
	//echo '================================================';
	//System::Debug($_REQUEST['mi_reservation_room']); exit();
	if(isset($_REQUEST['mi_reservation_room']))
	{
		$change_status = array();
		$change_price_arr = array();
		$currency_arr = array();
		foreach($_REQUEST['mi_reservation_room'] as $key=>$record)
		{
			unset($record['reservation_traveller_id']);
			unset($record['deposit']);
			unset($record['deposit_date']);
			if(isset($record['net_price'])){
				$record['net_price'] = 1;
			}else{
				$record['net_price'] = 0;
			}
			if(!isset($record['status']) and isset($record['old_status'])){
				$record['status'] = $record['old_status'];
			}
			if(isset($record['confirm']) or $record['status']=='CHECKIN'){
				$record['confirm'] = 1;
			}else{
				$record['confirm'] = 0;
			}
			if(isset($record['extra_bed'])){
				$record['extra_bed'] = 1;
			}else{
				$record['extra_bed'] = 0;
				$record['extra_bed_from_date'] = '';
				$record['extra_bed_to_date'] = '';
				$record['extra_bed_rate'] = 0;
			}
			if(isset($record['baby_cot'])){
				$record['baby_cot'] = 1;
			}else{
				$record['baby_cot'] = 0;
				$record['baby_cot_from_date'] = '';
				$record['baby_cot_to_date'] = '';
				$record['baby_cot_rate'] = 0;
			}
			if(isset($record['discount_after_tax'])){
				$record['discount_after_tax'] = 1;
			}else{
				$record['discount_after_tax'] = 0;
			}
			if(isset($record['closed']) or $record['status']=='CHECKOUT'){
				$record['closed'] = 1;
			}
			if(isset($record['closed']) or $record['status']=='CHECKOUT'){
				$record['closed'] = 1;
			}else{
				$record['closed'] = 0;
			}
			if(isset($record['early_checkin'])){
				$record['early_checkin'] = 1;
			}else{
				$record['early_checkin'] = 0;
			}
			if(isset($record['total_payment'])){
				unset($record['total_payment']);
			}
			if(isset($record['remain_amount'])){
				unset($record['remain_amount']);
			}
			if(isset($record['paid_amount'])){
				unset($record['paid_amount']);
			}
			foreach($currencies as $c_value){
				if(isset($record['currency_'.$c_value['id']])){
					$currency_arr[$c_value['id']]['id'] = $c_value['id'];
					$currency_arr[$c_value['id']]['value'] = str_replace(',','',$record['currency_'.$c_value['id']]);
					$currency_arr[$c_value['id']]['exchange_rate'] = $c_value['exchange'];
					unset($record['currency_'.$c_value['id']]);
				}
			}
			if(isset($record['change_price_arr']))
			{
				$change_price_arr = System::calculate_number($record['change_price_arr']);
				unset($record['change_price_arr']);
			}
			unset($record['room_level_name']);
			$def_code = '';
			if(isset($record['def_code']) and $record['def_code'] and $payment_type_id = DB::fetch('select id,def_code from payment_type where def_code =\''.$record['def_code'].'\'','id')){
				$def_code = $record['def_code'];
				$record['payment_type_id'] = $payment_type_id;
			}
			unset($record['def_code']);
			/////////////////////////////////Update room log//////////////////////////////////////////
			update_room_log($old_reservation_room,$record,$description);
			$record['total_amount'] = (isset($record['total_amount']) and $record['total_amount'])?str_replace(',','',$record['total_amount']):0;
			$record['price'] =	 str_replace(',','',$record['price']);
			if($record['time_in'])
			{
				$arr = explode(':',$record['time_in']);
				$record['time_in']= Date_Time::to_time($record['arrival_time'])+ intval($arr[0])*3600+intval($arr[1])*60;
			}else{
				$record['time_in']= Date_Time::to_time($record['arrival_time']);
			}
			if($record['time_out'])
			{
				$arr = explode(':',$record['time_out']);
				$record['time_out']= Date_Time::to_time($record['departure_time'])+ intval($arr[0])*3600+intval($arr[1])*60;
			}else{
				$record['time_out'] = Date_Time::to_time($record['departure_time']);
			}
			if(!$record['room_id']){
				$record['temp_room'] = $record['room_name'];
			}else{
				$record['temp_room'] = '';
			}
			unset($record['room_name']);
			unset($record['room_name_old']);
			unset($record['room_id_old']);
			unset($record['departure_time_old']);
			if(isset($record['early_arrival_time']) and $record['early_arrival_time']){
				$record['early_arrival_time'] = Date_Time::to_orc_date($record['early_arrival_time']);
			}
			if(Date_Time::to_time($min_arr_date) > Date_Time::to_time($record['arrival_time']) || $min_arr_date==''){
				$min_arr_date = $record['arrival_time'];
			}
			$record['arrival_time']=Date_Time::to_orc_date($record['arrival_time']);
			$record['departure_time']=Date_Time::to_orc_date($record['departure_time']);
			$record['extra_bed_from_date'] = Date_Time::to_orc_date($record['extra_bed_from_date']);
			$record['extra_bed_to_date'] = Date_Time::to_orc_date($record['extra_bed_to_date']);
			$record['baby_cot_from_date'] = Date_Time::to_orc_date($record['baby_cot_from_date']);
			$record['baby_cot_to_date'] = Date_Time::to_orc_date($record['baby_cot_to_date']);
			$record['extra_bed_rate'] = System::calculate_number($record['extra_bed_rate']);
			$record['baby_cot_rate'] = System::calculate_number($record['baby_cot_rate']);
			$record['reservation_id'] = $id;
			$record['foc_all'] = isset($record['foc_all'])?1:0;
			///////////////////////////////////////////EXCHANGE RATE////////////////////////////////////////////////
			$old_status = $record['old_status'];
			unset($record['old_status']);
			$currency_id = (HOTEL_CURRENCY == 'VND')?'USD':'VND';
			if($old_status!='CHECKOUT'){
				$record['exchange_rate'] = DB::fetch('select id,exchange from currency where id=\''.$currency_id.'\'','exchange');
			}
			///////////////////////////////////////////CHECKED IN USER ID///////////////////////////////////////////
			if(($old_status=='BOOKED' or !$old_status) and $record['status']=='CHECKIN'){
				$record['checked_in_user_id'] = Session::get('user_id');
			}
			///////////////////////////////////////////BOOKED USER ID///////////////////////////////////////////////
			if(!$old_status and $record['status']=='BOOKED'){
				$record['booked_user_id'] = Session::get('user_id');
			}
			////////////////////////////////////////////////////////////////////////////////////////////////////////
			if($def_code == 'DEBIT'){
				$record['related_rr_id'] = ($record['related_rr_id'] and DB::exists('select id from reservation_room where id = \''.$record['related_rr_id'].'\''))?$record['related_rr_id']:0;
			}else{
				$record['related_rr_id'] = 0;
			}
			$old_change_price_arr = array();
			$check = false;
			////////////////////////////////////////Truong hop edit//////////////////////////////////////////////
			if($record['id'] and isset($old_reservation_room[$record['id']]))
			{
				//-----Update phong neu co REPAIR-----------------------//
				if($old_reservation_room[$record['id']]['room_id']!=$record['room_id']){
					$room_status = DB::fetch_all('select * from room_status where reservation_room_id = '.$record['id'].' AND house_status=\'REPAIR\'');
					if(!empty($room_status)){
						foreach($room_status as $rs => $status){
							$status['reservation_room_id'] = 0;
							$status['reservation_id'] = 0;
							$status['change_price'] = 0;
							$status['status'] = 'AVAILABLE';
							unset($status['id']);
							DB::insert('room_status',$status);
						}
						$check = true;
					}
				}
				/////////////////////////////////////Update change price log/////////////////////////////////////////
				$old_change_price_arr = DB::fetch_all('SELECT to_char(in_date,\'DD/MM/YYYY\') as id,in_date,change_price FROM room_status WHERE reservation_room_id = '.$record['id'].'');
				update_change_price_log($record['id'],$old_change_price_arr,$change_price_arr,$description);
				if($record['status']!=$old_reservation_room[$record['id']]['status'] and $record['room_id'])// sua them truong hop room_id co the de trong
				{
					$change_status[$record['room_id']] = true;
				}
				if($old_status!='CHECKOUT' or $old_status!='CANCEL'){
					$record['lastest_edited_user_id'] = Session::get('user_id');
					$record['lastest_edited_time'] = time();
				}
				$record_ = $record;
				foreach($currency_arr as $c_a_key=>$c_a_value){
					if(isset($record_['pay_by_'.strtolower($c_a_key)])){
						unset($record_['pay_by_'.strtolower($c_a_key)]);
					}
				}
				$record_['bill_number'] = 'RE'.$record['id'];
				$record_['reduce_amount'] = System::calculate_number($record['reduce_amount']);
				DB::update('reservation_room',$record_+array('customer_name'=>$customer_name),'id='.$record['id']);
			}
			else ////////////////////////////////Truong hop them moi////////////////////////////////
			{
				unset($record['id']);
				$record['user_id'] = Session::get('user_id');
				$record['time'] = time();
				$record['reduce_amount'] = System::calculate_number($record['reduce_amount']);
				$record['id'] = DB::insert('reservation_room',$record+array('customer_name'=>$customer_name));
				DB::update('reservation_room',array('bill_number'=>'RE'.$record['id']),'id='.$record['id']);
				if($record['room_id']){// sua them truong hop room_id co the de trong
					$change_status[$record['room_id']] = true;
				}
			}
			if(isset($record['extra_bed']))
			{
				update_extra_bed_invoice($record['id']);
			}
			if(isset($record['baby_cot']))
			{
				update_baby_cot_invoice($record['id']);
			}
			//----------------------Update currencies--------------------------------
			/*if($def_code=='CASH'){
				foreach($currency_arr as $c_a_key=>$c_a_value){
					if($c_a_value['value']){
						if($row=DB::fetch('select * from pay_by_currency where bill_id='.$record['id'].' and currency_id=\''.$c_a_value['id'].'\' and type=\'RESERVATION\'')){
							DB::update('pay_by_currency',array('exchange_rate'=>$c_a_value['exchange_rate'],'amount'=>str_replace(',','',$c_a_value['value'])),'id='.$row['id']);
						}else{
							DB::insert('pay_by_currency',array('bill_id'=>$record['id'],'currency_id'=>$c_a_value['id'],'amount'=>str_replace(',','',$c_a_value['value']),'exchange_rate'=>$c_a_value['exchange_rate'],'type'=>'RESERVATION'));
						}
					}
					else
					{
						DB::delete('pay_by_currency','bill_id='.$record['id'].' and currency_id=\''.$c_a_value['id'].'\' and type=\'RESERVATION\'');
					}
				}
			}else{
				DB::delete('pay_by_currency','bill_id='.$record['id']);
			}*/
			//----------------------/Update currencies--------------------------------
			reservation_update_room_map($form, $id, $record,isset($change_status[$record['room_id']])?$change_status[$record['room_id']]:'',$change_price_arr,$old_reservation_room,$check);
		}
	}
	if(Url::get('cut_of_date') && Date_Time::to_time($min_arr_date)>Date_Time::to_time(Url::get('cut_of_date'))){
		$min_arr_date = Url::get('cut_of_date');
	}
	DB::update('reservation',array('cut_of_date'=>''.Date_Time::to_orc_date($min_arr_date).''),' id='.$id);
	/////////////////////////////////Fix room status/////////////////////////////////////////////////
	$items = DB::fetch_all('SELECT room_status.id FROM room_status INNER JOIN reservation_room on reservation_room.id = reservation_room_id WHERE room_status.reservation_id = '.$id.' AND (in_date > departure_time or in_date < arrival_time)');
	foreach($items as $key=>$value){
		DB::delete('room_status','id='.$value['id']);
	}
	/*-------------------------------update total_payment----------------------------
	if(isset($_REQUEST['mi_reservation_room']))
	{
		require_once 'packages/hotel/packages/reception/modules/includes/get_reservation.php';
		foreach($_REQUEST['mi_reservation_room'] as $key=>$record)
		{
			if($record['id'] and isset($old_reservation_room[$record['id']])){
				$reservation_arr = get_reservation($id,$record['id']);
				if(isset($reservation_arr['items'][$record['id']])){
					DB::update('reservation_room',array('total_payment'=>$reservation_arr['items'][$record['id']]['total_amount']),'id='.$record['id']);
				}
			}
		}
	}*/
	//------------------------------/update total_payment----------------------------
	if(Url::get('tour_id')){
		update_tour(Url::get('tour_id'),$id);
	}
}
function update_reservation_traveller(&$form, $id, $old_travellers, &$title, &$description, &$customer_name, &$change_status)
{
	$rt_id = 0;
	if(isset($_REQUEST['mi_traveller']) or $old_travellers)
	{
		$i=0;
		$count_travellers = array();
		if(isset($_REQUEST['mi_traveller']))
		{
			$traveller_id = 0;
			//System::Debug($_REQUEST['mi_traveller']); exit();
			foreach($_REQUEST['mi_traveller'] as $key=>$record)
			{
				$traveller_id = $record['traveller_id_'];
				$reservation_id = $id;
				$special_request = $record['note'];
				unset($record['note']);
				//////////////////////////////////Xac dinh thoi gian den cua khach///////////////////////////////////
				/*if($record['time_in'] and $record['arrival_date'])
				{
					$arr = explode(':',$record['time_in']);
					$time_in = Date_Time::to_time($record['arrival_date'])+ intval($arr[0])*3600+intval($arr[1])*60;
				}else{
					$time_in = '';
				}
				unset($record['arrival_date']);
				unset($record['time_in']);
				*/
				/*if($record['time_out'] and $record['departure_date'])
				{
					$arr = explode(':',$record['time_out']);
					$time_out = Date_Time::to_time($record['departure_date'])+ intval($arr[0])*3600+intval($arr[1])*60;
				}else{
					$time_out = '';
				}
				unset($record['departure_date']);
				unset($record['time_out']);*/
				//////////////////////////////////Xu ly tach xau de lay ra room_id//////////////////////////////////
				unset($record['traveller_id_']);
				$i++;
				if($record['traveller_room_id'])
                {
					$temp_arr = explode('-',$record['traveller_room_id']);
					$room_id = $temp_arr[0];
					$departure_time = $temp_arr[1];
					$temp_room = '';
				}
                else
                {
					//System::Debug($_REQUEST['mi_traveller']); exit();
					$temp_room = $record['mi_traveller_room_name'];
					$room_id = $record['mi_traveller_room_name'];
					$departure_time = '01/01/1970';
				}
				/////////////////////////////////////////////////////////////////////////////////////////////////////
				$record['birth_date']= Date_Time::to_orc_date($record['birth_date']);
                //unset($record['nationality_name']);
				 unset($record['nationality_name']);
				$record['nationality_id'] = DB::fetch('select id from country where code_1 = \''.trim($record['nationality_id']).'\'','id',1);
				$payment = false;
				$visa = $record['visa'];
				$expire_date_of_visa = $record['expire_date_of_visa'];
				$payment = isset($record['traveller_id'])?true:false;
				$pa18 = isset($record['pa18'])?1:0;
				$traveller_arrival_time = 0;
				$traveller_departure_time = 0;
				$flight_arrival_time = 0;
				$flight_departure_time = 0;
				$traveller_arrival_date = '';
				$traveller_departure_date = '';
				$reservation_room = array();
				if($room_id and is_numeric($room_id) and DB::select('room','id='.$room_id))
				{
					$reservation_room = DB::select('reservation_room','reservation_id='.$id.' and room_id='.$room_id.' and departure_time = \''.Date_Time::to_orc_date($departure_time).'\'');
				}
				else if(!is_numeric($room_id) && $room_id!='')
				{
					$reservation_room = DB::select('reservation_room','reservation_id='.$id.' and temp_room=\''.$room_id.'\'');
				}
				if($record['arrival_hour'] or $record['traveller_arrival_date'])
				{
					if(!$record['arrival_hour'])
					{
						$record['arrival_hour'] = '00:00';
					}
					$t_arr = explode(':',$record['arrival_hour']);
					$traveller_arrival_time = ($record['traveller_arrival_date']!='')?(Date_Time::to_time($record['traveller_arrival_date'])+$t_arr[0]*3600+$t_arr[1]*60):(($reservation_room['time_in'])?$reservation_room['time_in']:'');
					$traveller_arrival_date = ($record['traveller_arrival_date']!='')?Date_Time::to_orc_date($record['traveller_arrival_date']):(($reservation_room['arrival_time'])?$reservation_room['arrival_time']:'');
				}
				if($record['departure_hour'] or $record['traveller_departure_date'])
				{
					if(!$record['departure_hour'])
					{
						$record['departure_hour'] = '00:00';
					}
					$t_dep = explode(':',$record['departure_hour']);
					$traveller_departure_time = ($record['traveller_departure_date']!='')?(Date_Time::to_time($record['traveller_departure_date'])+$t_dep[0]*3600+$t_dep[1]*60):(($reservation_room['time_out'])?$reservation_room['time_out']:'');
					$traveller_departure_date = ($record['traveller_departure_date']!='')?Date_Time::to_orc_date($record['traveller_departure_date']):(($reservation_room['departure_time'])?$reservation_room['departure_time']:'');
				}
				if($record['flight_arrival_hour'] or $record['flight_arrival_date'])
				{
					if(!$record['flight_arrival_hour'])
					{
						$record['flight_arrival_hour'] = '00:00';
					}
					$f_arr = explode(':',$record['flight_arrival_hour']);
					$flight_arrival_time = Date_Time::to_time($record['flight_arrival_date'])+$f_arr[0]*3600+$f_arr[1]*60;
				}
				if($record['flight_departure_hour'] or $record['flight_departure_date'])
				{
					if(!$record['flight_departure_hour'])
					{
						$record['flight_departure_hour'] = '00:00';
					}
					$f_dep = explode(':',$record['flight_departure_hour']);
					$flight_departure_time = Date_Time::to_time($record['flight_departure_date'])+$f_dep[0]*3600+$f_dep[1]*60;
				}
				// GÃ�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¿Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¡n ngÃ�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¿Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â y Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¿Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¿Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¡Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�ÂºÃ�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¿n ngÃ�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¿Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â y Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¿Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¿Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â½i cÃ�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¡Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â»Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â§a khÃ�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¿Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¡ch bÃ�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¡Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�ÂºÃ�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â±ng ngÃ�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¿Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â y Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¿Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¿Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¡Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�ÂºÃ�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¿n ngÃ�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¿Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â y Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¿Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¿Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â½i cÃ�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¡Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â»Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â§a phÃ�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¿Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â²ng
				$traveller_arrival_time = ($traveller_arrival_time=='')?(($reservation_room['time_in'])?$reservation_room['time_in']:''):$traveller_arrival_time;
				$traveller_arrival_date = ($traveller_arrival_date=='')?(($reservation_room['arrival_time'])?$reservation_room['arrival_time']:''):$traveller_arrival_date;
				$traveller_departure_time = ($traveller_departure_time=='')?(($reservation_room['time_out'])?$reservation_room['time_out']:''):$traveller_departure_time;
			    $traveller_departure_date = ($traveller_departure_date=='')?(($reservation_room['departure_time'])?$reservation_room['departure_time']:''):$traveller_departure_date;
				//End
				if(isset($record['pickup'])){
					$pickup = 1;
				}else{
					$pickup = 0;
				}
				if(isset($record['see_off'])){
					$see_off = 1;
				}else{
					$see_off = 0;
				}
				if(isset($record['pickup_foc'])){
					$pickup_foc = 1;
				}else{
					$pickup_foc = 0;
				}
				if(isset($record['see_off_foc'])){
					$see_off_foc = 1;
				}else{
					$see_off_foc = 0;
				}
				$flight_code = $record['flight_code'];
				$flight_code_departure = $record['flight_code_departure'];
				$car_note_arrival = $record['car_note_arrival'];
				$car_note_departure = $record['car_note_departure'];
				if(isset($record['transit'])){
					$record['transit'] = 1;
				}else{
					$record['transit'] = 0;
				}
				unset($record['reservation_traveller_id']);
				unset($record['arrival_hour']);
				unset($record['traveller_arrival_date']);
				unset($record['departure_hour']);
				unset($record['traveller_departure_date']);
				unset($record['flight_arrival_hour']);
				unset($record['flight_arrival_date']);
				unset($record['flight_code']);
				unset($record['flight_departure_hour']);
				unset($record['flight_departure_date']);
				unset($record['flight_code_departure']);
				unset($record['car_note_arrival']);
				unset($record['car_note_departure']);
				unset($record['pickup']);
				unset($record['see_off']);
				unset($record['pickup_foc']);
				unset($record['see_off_foc']);
				unset($record['traveller_id']);
				unset($record['traveller_room_id']);
				unset($record['mi_traveller_room_name']);
				if(isset($record['status']) && $record['status']){
					$status_traveller = $record['status'];
				}
				unset($record['status']);
				unset($record['visa']);
				unset($record['expire_date_of_visa']);
				$record['passport'] = $record['passport']?$record['passport']:'?';
				if(!empty($reservation_room))
				{
					$reservation_room_id = $reservation_room['id'];
				}else{
					$reservation_room_id = 0;
				}
				//$status_traveller = ($reservation_room['status']=='CHECKOUT')?'CHECKOUT':'CHECKIN';
				//exit();
				if($record['id'])
				{
					if(isset($old_travellers[$record['id']]))
                    {
						$reservation_traveller_id = $record['id'];
						unset($record['id']);
						unset($record['reservation_room_id']);
						DB::update('traveller',$record,'id='.$traveller_id);
						if($reservation_room_id>0)
                        {
							if(!isset($count_travellers[$reservation_room_id]))
							{
								$count_travellers[$reservation_room_id] = 0;
								$customer_names[$reservation_room_id] = $customer_name;
							}
							$customer_names[$reservation_room_id].= ' '.$record['first_name'].' '.$record['last_name'];
							if($status_traveller == 'CHECKIN')
                            {
								$count_travellers[$reservation_room_id]++;
							}
						}
						DB::update('reservation_traveller',array(
							'reservation_room_id'=>$reservation_room_id,
							'reservation_id'=>$reservation_id,
							'traveller_id'=>$traveller_id,
							'arrival_time'=>$traveller_arrival_time,
							'arrival_date'=>$traveller_arrival_date,
							'departure_time'=>$traveller_departure_time,
							'departure_date'=>$traveller_departure_date,
							'flight_code'=>$flight_code,
							'flight_code_departure'=>$flight_code_departure,
							'flight_arrival_time'=>$flight_arrival_time,
							'flight_departure_time'=>$flight_departure_time,
							'car_note_arrival'=>$car_note_arrival,
							'car_note_departure'=>$car_note_departure,
							'pickup'=>$pickup,
							'see_off'=>$see_off,
							'pickup_foc'=>$pickup_foc,
							'see_off_foc'=>$see_off_foc,
							'pa18'=>$pa18,
							'special_request'=>$special_request,
							'temp_room'=>$temp_room,
							'visa_number'=>$visa,
							'expire_date_of_visa'=>Date_Time::to_orc_date($expire_date_of_visa)
						),'id='.$reservation_traveller_id.'');
						$old_travellers[$reservation_traveller_id]['not_delete'] = true;
						$rt_id = $reservation_traveller_id;
					}
				}
				else
				{
					unset($record['id']);
					unset($record['reservation_room_id']);
					if($record['passport']!='?' and $traveller = DB::select('traveller','passport=\''.$record['passport'].'\''))
					{
						DB::update('traveller',$record,'id=\''.$traveller['id'].'\'');
					}
					else
					{
						$traveller_id = DB::insert('traveller',$record);
					}
					$rt_id = DB::insert('reservation_traveller',array(
						'traveller_id'=>$traveller_id,
						'reservation_room_id'=>$reservation_room_id,
						'reservation_id'=>$reservation_id,
						'special_request'=>$special_request,
						'arrival_time'=>$traveller_arrival_time,
						'arrival_date'=>$traveller_arrival_date,
						'departure_time'=>$traveller_departure_time,
						'departure_date'=>$traveller_departure_date,
						'flight_code'=>$flight_code,
						'flight_arrival_time'=>$flight_arrival_time,
						'flight_code_departure'=>$flight_code_departure,
						'flight_departure_time'=>$flight_departure_time,
						'car_note_arrival'=>$car_note_arrival,
						'car_note_departure'=>$car_note_departure,
						'pickup'=>$pickup,
						'see_off'=>$see_off,
						'pickup_foc'=>$pickup_foc,
						'see_off_foc'=>$see_off_foc,
						'temp_room'=>$temp_room,
						'visa_number'=>$visa,
						'expire_date_of_visa'=>Date_Time::to_orc_date($expire_date_of_visa),
						'status'=>'CHECKIN'
					));
					if($reservation_room_id){
						if(!isset($count_travellers[$reservation_room_id]))
						{
							$customer_names[$reservation_room_id] = $customer_name;
							$count_travellers[$reservation_room_id] = 0;
						}
						$customer_names[$reservation_room_id].= ' '.$record['first_name'].' '.$record['last_name'];
						$count_travellers[$reservation_room_id]++;
					}
				}
				if(isset($change_status[$room_id]))
				{
					$content = $reservation_room['status'].' room '.DB::fetch('select CONCAT(room.name,CONCAT(\' - \',party.name_1)) as name from room inner join party on party.user_id = room.portal_id where room.id=\''.$room_id.'\'','name');
					update_traveller_comment($traveller_id,$content);
				}
				if(isset($count_travellers[$reservation_room_id]))
                {
					if($payment)
                    {
						DB::update('reservation_room',array('traveller_id'=>$traveller_id),'id='.$reservation_room_id);
					}
                    else
                    {
						if($count_travellers[$reservation_room_id]==1)
                        {
							DB::update('reservation_room',array('traveller_id'=>$traveller_id),'id='.$reservation_room_id);
						}
					}
				}
				//-------------------------Thuy-------------------------
				$tour_id = DB::fetch('select tour_id from reservation where id='.$id.'','tour_id');
				if($tour_id)
                {
					update_pa18_template_for_traveller($tour_id,$rt_id);
				}
				//-------------------------END-Thuy-------------------------
			}
		}
		foreach($count_travellers as $r_room_id=>$adult)
        {
			$status = DB::fetch('select id,status from reservation_room where id = '.$r_room_id.'','status');
			if($status == 'CHECKIN' or $status == 'CHECKOUT')
            {
				DB::update('reservation_room',array('adult'=>$adult,'customer_name'=>$customer_names[$r_room_id]),'id=\''.$r_room_id.'\'');
			}
		}
		foreach($old_travellers as $t => $item)
        {
			if(!isset($item['not_delete']))
            {
				if(User::can_delete(false,ANY_CATEGORY))
                {
					DB::delete('reservation_traveller','id='.$item['id']);
                    //DB::delete('folio')
				}
                else
                {
					$form->error('can_not_delete','you_have_no_right_to_delete');
				}
			}
		}
	}
}
function update_pa18_template_for_traveller($tour_id,$rt_id){// Thuylt
	$sql= 'SELECT
				tour.id
				,port_of_entry
				,entry_date
				,back_date
				,entry_target
				,go_to_office
				,come_from
				,is_vn
		   FROM
		   		tour WHERE id='.$tour_id.'';
	$tour_info =  DB::fetch($sql);
	unset($tour_info['id']);
	unset($tour_info['is_vn']);
	$traveller = DB::fetch('select * from reservation_traveller where id = '.$rt_id.'');
	if($traveller['port_of_entry'] != ''){
		unset($tour_info['port_of_entry']);
	}
	if($traveller['entry_date'] != ''){
		unset($tour_info['entry_date']);
	}
	if($traveller['back_date'] != ''){
		unset($tour_info['back_date']);
	}
	if($traveller['entry_target'] != ''){
		unset($tour_info['entry_target']);
	}
	if($traveller['go_to_office'] != ''){
		unset($tour_info['go_to_office']);
	}
	if($traveller['come_from'] != ''){
		unset($tour_info['come_from']);
	}
	if($tour_info){
		DB::update('reservation_traveller',$tour_info,'id='.$rt_id.'');
	}
}
function update_tour($tour_id,$reservation_id){//Thuylt
	$sql='
		SELECT
			min(reservation_room.arrival_time) as arrival_time,
			max(reservation_room.departure_time) as departure_time,
			sum(reservation_room.adult) as adult,
			sum(reservation_room.child) as child,
			count(reservation_room.id) as room_quantity FROM reservation_room INNER JOIN reservation ON reservation.id = reservation_room.reservation_id
		WHERE
			reservation.tour_id = '.$tour_id.' AND reservation.id ='.$reservation_id.' AND reservation_room.status <> \'CANCEL\'';
	$items = DB::fetch($sql);
	$items['num_people'] = $items['adult'];
	unset($items['adult']);
	$items['child'] = $items['child'];
	DB::update('tour',$items,'id = '.$tour_id.'');
}
function update_traveller_comment($traveller_id,$content){
	DB::insert('traveller_comment',
		array(
			'user_id'=>Session::get('user_id'),
			'time'=>time(),
			'traveller_id'=>$traveller_id,
			'content'=>$content)
	);
}
function reservation_check_permission(&$form, $id, &$old_reservation_room)
{
	if(isset($_REQUEST['mi_reservation_room']))
	{
		foreach($_REQUEST['mi_reservation_room'] as $key=>$record)
		{
			if(!isset($record['status']) and isset($record['old_status'])){
				$record['status'] = $record['old_status'];
			}
			if(isset($old_reservation_room[$record['id']]) and $old_reservation_room[$record['id']]['status'] == 'BOOKED' and $record['status']=='CHECKIN')
			{
				if($old_reservation = DB::select('reservation_room','status=\'CHECKIN\' and room_id=\''.$record['room_id'].'\''))
				{
					$form->error('room_name_'.$key,'Room '.DB::fetch('select name from room where id=\''.$record['room_id'].'\'','name').' is currently checked in by <a target=\'_blank\' href=\'?page=reservation&cmd=edit&id='.$old_reservation['reservation_id'].'\'>Reservation #'.$old_reservation['reservation_id'].'</a>. Please check out this first before check in another guest!',false);
					$error = true;
				}
			}
			if($record['id'] and isset($old_reservation_room[$record['id']]))
			{
				$old_reservation_room[$record['id']]['not_delete'] = true;
			}
		}
		if($form->is_error())
		{
			return;
		}
	}
}
function reservation_check_conflict($form)
{
	$valid_room_array=array();
	$room_conflig_arr = array();
	$pre_room_id = 0;
	$i = 1;
	if(isset($_REQUEST['mi_reservation_room']))
	{
		foreach($_REQUEST['mi_reservation_room'] as $key=>$record)
		{
			if($record and $record['room_id'])
			{
				if(!isset($record['status']) and isset($record['old_status'])){
					$record['status'] = $record['old_status'];
				}
				/*if($record['status']!='CANCEL'){
					if($pre_room_id != $record['room_id']){
						$pre_room_id = $record['room_id'];
					}else{
						$form->error('room_id',Portal::language('duplicated_room').' '.$record['room_name']);
					}
				}*/
				$time_in = Date_Time::to_time($record['arrival_time']);
				$time_out=Date_Time::to_time($record['departure_time']);
				$cond = 'room.portal_id = \''.PORTAL_ID.'\' AND R.status<>\'CANCEL\' AND R.status<>\'CHECKOUT\'
						AND R.room_id=\''.$_REQUEST['mi_reservation_room'][$key]['room_id'].'\'
						'.($record['id']?' AND R.id<>\''.$record['id'].'\'':'');
				if(isset($record['time_in']) and $record['time_in'] and isset($record['time_out']) and $record['time_out']){
					$arr = explode(':',$record['time_in']);
					$time_in= $time_in + intval($arr[0])*3600+intval($arr[1])*60;
					$arr = explode(':',$record['time_out']);
					$time_out= $time_out + intval($arr[0])*3600+intval($arr[1])*60;
					if($time_out <= $time_in){
						$form->error('room_id_'.$key,Portal::language('time_out_has_to_be_more_than_time_in'));
					}
					if($record['id'] and !check_all_related_serivce($record['id'],$time_in,$time_out)){
						//$form->error('room_id_'.$key,Portal::language('room').' <strong>'.$record['room_name'].'</strong>: '.Portal::language('Some_added_services_of_this_room_out_of_this_time_duration'),false);
					}
				}else{
					if($time_out < $time_in){
						$form->error('room_id_'.$key,Portal::language('time_out_has_to_be_more_than_time_in'),false);
					}
				}
				if($record['status']=='CHECKOUT'){
					if($time_out>time()){
						$form->error('room_id_'.$key,Portal::language('time_out_has_not_to_be_more_than_current_time'));
					}
				}
				$cond2 = $cond.' AND r.status=\'CHECKIN\'';
				$cond .= ' AND (
						(R.time_in <= '.$time_in.' AND R.time_out >= '.$time_out.')
					OR	(R.time_in >= '.$time_in.' AND R.time_out >= '.$time_out.' AND R.time_in <= '.$time_out.')
					OR	(R.time_in <= '.$time_in.' AND R.time_out >= '.$time_in.' AND R.time_out <= '.$time_out.')
					OR	(R.time_in >= '.$time_in.' AND R.time_out <= '.$time_out.')
					OR	(R.time_out = '.$time_in.')
				)';// OR r.status=\'BOOKED\'
				$sql = '
					SELECT
						R.id,R.reservation_id
					FROM
						reservation_room R
						INNER JOIN room ON room.id = R.room_id
					WHERE
				';
				$room_id = $record['room_id'];
				if($record['status']<>'CANCEL' and $record['status']<>'CHECKOUT' and room_check_conflict($room_conflig_arr,array($room_id,$time_in,$time_out))){
					$form->error('room_id_'.$key,Portal::language('conflict').' '.Portal::language('room').' '.$record['room_name'].' '.Portal::language('in_this_reservation'));
				}
				if($record['status']<>'CANCEL'){
					$room_conflig_arr[$i]['room_id'] = $room_id;
					$room_conflig_arr[$i]['time_in'] = $time_in;
					$room_conflig_arr[$i]['time_out'] = $time_out;
					$i++;
				}
				if($reservation_room = DB::fetch($sql.' '.$cond) and $record['status']<>'CANCEL' and $record['status']<>'CHECKOUT')
				{
					$form->error('room_id_'.$key,Portal::language('room').' '.$record['room_name'].' '.Portal::language('conflict_of_time_to_reservation').' <a target="blank" href="?page=reservation&cmd=edit&id='.$reservation_room['reservation_id'].'&r_r_id='.$reservation_room['id'].'">#'.$reservation_room['reservation_id'].'</a>',false);
				}
				if($record['status']=='CHECKIN')
				{
					if(isset($time_in) and $time_in>time()){
						$form->error('status_'.$key,'Room: '.$record['room_name'].' '.Portal::language('time_in_is_more_than_current_time'));
					}else if($reservation_room = DB::fetch($sql.' '.$cond2)){
						$form->error('room_id_'.$key,Portal::language('conflict').' '.Portal::language('room').' '.$record['room_name'].' '.Portal::language('in_this_reservation').' <a target="blank" href="?page=reservation&cmd=edit&id='.$reservation_room['reservation_id'].'&r_r_id='.$reservation_room['id'].'">#'.$reservation_room['reservation_id'].'</a>',false);
					}
				}
				$valid_room_array[$room_id] = false;
				if($record['status']=='CHECKIN' or $record['status']=='CHECKOUT')
				{
					if(isset($_REQUEST['mi_traveller']))
					{
						foreach($_REQUEST['mi_traveller'] as $k=>$v){
							if($v['traveller_room_id']){
								$temp_arr = explode('-',$v['traveller_room_id']);
								$t_room_id = $temp_arr[0];
							}else{
								$t_room_id = 0;
							}
							if($t_room_id==$room_id){
								$valid_room_array[$room_id] = true;
							}
						}
					}
				}
				else{
					if(isset($_REQUEST['mi_traveller']))
					{
						foreach($_REQUEST['mi_traveller'] as $k=>$v){
							if($v['traveller_room_id']){
								$temp_arr = explode('-',$v['traveller_room_id']);
								$t_room_id = $temp_arr[0];
							}else{
								$t_room_id = 0;
							}
							if(!$valid_room_array[$room_id]){
								if($t_room_id==$room_id){
									$valid_room_array[$room_id] = true;
								}
								else
								{
									$valid_room_array[$room_id] = false;
								}
							}
						}
					}
				}
			}
		}
	}else{
		$form->error('room','miss_room_information');
	}
	//exit();
	return $valid_room_array;
}
function room_check_conflict($arr1,$arr2){
	$return = false;
	foreach($arr1 as $key=>$value){
		if($value['room_id'] and $value['room_id']==$arr2[0]){
			$time_in = $arr2[1];
			$time_out = $arr2[2];
			if(($value['time_in'] <= $time_in and $value['time_out'] >= $time_out)
					or	($value['time_in'] >= $time_in and $value['time_out'] >= $time_out and $value['time_in'] <= $time_out)
					or	($value['time_in'] <= $time_in and $value['time_out'] >= $time_in and $value['time_out'] <= $time_out)
					or	($value['time_in'] >= $time_in and $value['time_out'] <= $time_out)
					or	($value['time_out'] == $time_in)){
					$return = true;
					break;
			}
		}
	}
	return $return;
}
function reservation_update_room_map(&$form, $id, $record,$change_status,$change_price_arr=array(),$old_reservation_room=array(),$check)
{
	if($record['id'] and isset($old_reservation_room[$record['id']])){// Khoand updated in 07/04/2012
		//DB::update('room_status',array('status'=>'AVAILABLE','reservation_id'=>0),'reservation_room_id = '.$record['id'].' AND (in_date < \''.$record['arrival_time'].'\' OR in_date < \''.$record['departure_time'].'\')');
	}
	$from = Date_Time::to_time(Date_Time::convert_orc_date_to_date($record['arrival_time']  ,'/'));
	$to   = Date_Time::to_time(Date_Time::convert_orc_date_to_date($record['departure_time'],'/'));
	$d = $from;
	$status=$record['status'];
	switch($record['status'])
	{
	case 'CHECKIN':
	case 'CHECKOUT':
		$status='OCCUPIED';break;
	}
	$house_status = ($record['status']=='CHECKOUT' and $change_status)?'DIRTY':'';
	while($d>=$from and $d<=$to)
	{
		$change_price = 0;
		if(isset($change_price_arr[date('d/m/Y',$d)])){
			$change_price = $change_price_arr[date('d/m/Y',$d)];
		}
		if($status=='BOOKED'){
			if($record['arrival_time']==$record['departure_time']){
				$change_price = $record['price'];
			}else{
				if($d == $to){
					$change_price = 0;
				}else{
					$change_price = $record['price'];
				}
			}
		}
		$sql = 'select * from room_status where in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$d)).'\' and reservation_room_id='.$record['id'].'';
		if($room_status = DB::fetch($sql))
		{
			DB::update_id('room_status',
				(($record['status']=="CHECKOUT" and $change_status and $d==$to)?array('house_status'=>$house_status):array())+
				(($record['status']=="CHECKOUT" and $record['arrival_time'] == $record['departure_time'])?array('closed_time'=>time()):array())+ // Closed doanh thu ngay khi check out neu khach o trong ngay
				array(
				'room_id'=>$record['room_id'],
				'status'=>$status,
				'reservation_id'=>$id,
				'change_price'=>$change_price,
				'house_status'=>($check)?'':$room_status['house_status'],
				'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
				),$room_status['id']
			);
		}
		else
		{
			DB::insert('room_status',
				(($record['status']=="CHECKOUT" and $change_status and $d==$to)?array('house_status'=>$house_status):array())+
				array(
					'room_id'=>$record['room_id'],
					'status'=>$status,
					'reservation_id'=>$id,
					'change_price'=>$change_price,
					'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
					'reservation_room_id'=>$record['id']
				)
			);
		}
		$d=$d+(3600*24);
	}
}
function put_into_lock_card($room_id,$time_in,$time_out)
{
	require_once 'packages/core/includes/system/access_database.php';
	$db_file = LOCK_DB_FILE;
	$adb = new ADB("Driver={Microsoft Access Driver (*.mdb)};Dbq=".$db_file."",'','yhd');
}
function reservation_check_traveller(&$form){
}
function update_change_price_log($rr_id,$old_change_price_arr,$change_price_arr,&$description){ // Writted by khoand in 06/01/2011
	$description .= '<em>Price by date</em>:';
	foreach($change_price_arr as $key=>$value){
		if(isset($old_change_price_arr[$key])){
			if(System::calculate_number($value) != System::calculate_number($old_change_price_arr[$key]['change_price'])){
				DB::update('room_status',array('lastest_edited_user_id'=>Session::get('user_id'),'lastest_edited_time'=>time(),'price_before_edited'=>$old_change_price_arr[$key]['change_price']),'reservation_room_id='.$rr_id.' and in_date = \''.$old_change_price_arr[$key]['in_date'].'\'');
				$description .= '<br>Update price from <strong>'.System::display_number($old_change_price_arr[$key]['change_price']).'</strong> to <strong>'.System::display_number($value).'</strong> for date '.$key.'';
			}
		}else{
			$description .= '<br>Add price '.$value.' for date '.$key.'';
		}
	}
	$description .= '<br>';
}
function update_room_log($old_reservation_room,$record,&$description){ // Writted by khoand in 06/01/2011
	$description .= '<li>';
	$description .= '<strong>Action with room '.$record['room_name'].':</strong><br>';
	if(isset($old_reservation_room[$record['id']])){
		$tmp_arr = $old_reservation_room[$record['id']];
		if($record['status'] != $tmp_arr['status']){
			$description .= 'Update room status from <strong>'.$tmp_arr['status'].'</strong> to <strong>'.$record['status'].'</strong>, ';
		}
		if(System::calculate_number($record['price']) != System::calculate_number($tmp_arr['price'])){
			$description .= 'Update room price from <strong>'.$tmp_arr['price'].'</strong> to <strong>'.$record['price'].'</strong>, ';
		}
		if($record['time_in'] != date('H:i',$tmp_arr['time_in'])){
			$description .= 'Update time in from <strong>'.date('H:i',$tmp_arr['time_in']).'</strong> to <strong>'.$record['time_in'].'</strong>, ';
		}
		if($record['arrival_time'] != Date_Time::convert_orc_date_to_date($tmp_arr['arrival_time'],'/')){
			$description .= 'Update arrival time from <strong>'.Date_Time::convert_orc_date_to_date($tmp_arr['arrival_time'],'/').'</strong> to <strong>'.$record['arrival_time'].'</strong>, ';
		}
		if($record['time_out'] != date('H:i',$tmp_arr['time_out'])){
			$description .= 'Update time out from <strong>'.date('H:i',$tmp_arr['time_out']).'</strong> to <strong>'.$record['time_out'].'</strong>, ';
		}
		if($record['departure_time'] != Date_Time::convert_orc_date_to_date($tmp_arr['departure_time'],'/')){
			$description .= 'Update departure time from <strong>'.Date_Time::convert_orc_date_to_date($tmp_arr['departure_time'],'/').'</strong> to <strong>'.$record['departure_time'].'</strong>, ';
		}
		if(System::calculate_number($record['reduce_balance']) != System::calculate_number($tmp_arr['reduce_balance'])){
			$description .= 'Update discount by percent from <strong>'.System::calculate_number($tmp_arr['reduce_balance']).'%</strong> to <strong>'.System::calculate_number($record['reduce_balance']).'%</strong>, ';
		}
		if(System::calculate_number($record['reduce_amount']) != System::calculate_number($tmp_arr['reduce_amount'])){
			$description .= 'Update discount by '.HOTEL_CURRENCY.' from <strong>'.$tmp_arr['reduce_amount'].'</strong> to <strong>'.$record['reduce_amount'].'</strong>, ';
		}
		if(System::calculate_number($record['tax_rate']) != System::calculate_number($tmp_arr['tax_rate'])){
			$description .= 'Update tax rate from <strong>'.System::calculate_number($tmp_arr['tax_rate']).'%</strong> to <strong>'.System::calculate_number($record['tax_rate']).'%</strong>, ';
		}
		if(System::calculate_number($record['service_rate']) != System::calculate_number($tmp_arr['service_rate'])){
			$description .= 'Update service rate from <strong>'.System::calculate_number($tmp_arr['service_rate']).'%</strong> to <strong>'.System::calculate_number($record['service_rate']).'%</strong>, ';
		}
		if($record['foc'] != $tmp_arr['foc']){
			$description .= 'Update FOC from <strong>"'.$tmp_arr['foc'].'"</strong> to <strong>"'.$record['foc'].'"</strong>, ';
		}
	}
	$description .= '</li>';
	//$record['arrival_time'].' to '.$record['departure_time']
}
function check_all_related_serivce($reservation_room_id,$time_in,$time_out){
	//check housekeeping invoice
	if(DB::exists('SELECT id FROM housekeeping_invoice hkinv WHERE hkinv.reservation_room_id = '.$reservation_room_id.' AND hkinv.time >= '.$time_in.' AND hkinv.time <= '.$time_out.'')){
		return true;
	}else{
		return false;
	}
}
/*
alter table room_status add(
  lastest_edited_user_id   char(50) NULL,
  lastest_edited_time   number(11) NULL,
price_before_edited number(11,2) NULL
)
*/
// ham xu ly tao extra invoice voi extrabed.
function update_extra_bed_invoice($reservation_room_id)
{
	if($reservation_room = DB::select('reservation_room','id='.$reservation_room_id.' AND reservation_room.status<>\'CANCEL\''))
	{
		if($row = DB::select('extra_service_invoice','reservation_room_id='.$reservation_room_id.' AND use_extra_bed=1') and !$reservation_room['extra_bed'])
		{
			DB::delete('extra_service_invoice_detail','invoice_id='.$row['id']);
			DB::delete('extra_service_invoice','id='.$row['id']);
		}
		elseif($row)
		{
			$data = array(
				'reservation_room_id'=>$reservation_room_id,
				'user_id'=>$reservation_room['checked_in_user_id'],
				'portal_id'=>PORTAL_ID,
				'payment_type'=>'SERVICE',
				'note'=>'Invoice for Extra bed',
				'use_extra_bed'=>1,
				'time'=>$reservation_room['time']
			);
			DB::update('extra_service_invoice',$data,'id='.$row['id']);
			DB::delete('extra_service_invoice_detail','in_date<\''.$reservation_room['extra_bed_from_date'].'\' AND invoice_id='.$row['id']);
			DB::delete('extra_service_invoice_detail','in_date>=\''.$reservation_room['extra_bed_to_date'].'\' AND invoice_id='.$row['id']);
			$from = Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['extra_bed_from_date'],'/'));
			$to   = Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['extra_bed_to_date'],'/'));
			$d = $from;
			$service = DB::fetch('select * from extra_service where code=\'EXTRA_BED\'');
			$total_extra_bed = 0;
			if($from==$to)
			{
				$extra_service_invoice_detail = array(
					'invoice_id'=>$row['id'],
					'service_id'=>$service['id'],
					'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
					'price'=>$reservation_room['extra_bed_rate']?$reservation_room['extra_bed_rate']:$service['price'],
					'time'=>time(),
					'name'=>$service['name'],
					'quantity'=>1,
					'used'=>1
				);
				$total_extra_bed+=$reservation_room['extra_bed_rate']?$reservation_room['extra_bed_rate']:$service['price'];
				if($row_detail = DB::select('extra_service_invoice_detail','in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$d)).'\' AND extra_service_invoice_detail.invoice_id='.$row['id']))
				{
					DB::update('extra_service_invoice_detail',$extra_service_invoice_detail,'id='.$row_detail['id']);
				}
				else
				{
					DB::insert('extra_service_invoice_detail',$extra_service_invoice_detail);
				}
			}
			else
			{
				while($d>=$from and $d<$to)
				{
					$extra_service_invoice_detail = array(
						'invoice_id'=>$row['id'],
						'service_id'=>$service['id'],
						'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
						'price'=>$reservation_room['extra_bed_rate']?$reservation_room['extra_bed_rate']:$service['price'],
						'time'=>time(),
						'name'=>$service['name'],
						'quantity'=>1,
						'used'=>1
					);
					$total_extra_bed+=$reservation_room['extra_bed_rate']?$reservation_room['extra_bed_rate']:$service['price'];
					if($row_detail = DB::select('extra_service_invoice_detail','in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$d)).'\' AND extra_service_invoice_detail.invoice_id='.$row['id']))
					{
						DB::update('extra_service_invoice_detail',$extra_service_invoice_detail,'id='.$row_detail['id']);
					}
					else
					{
						DB::insert('extra_service_invoice_detail',$extra_service_invoice_detail);
					}
					$d=$d+(3600*24);
				}
			}
			$total_before_tax = $total_extra_bed;
			$total = $total_before_tax + ($total_before_tax*0.05) + ($total_before_tax + $total_before_tax*0.05)*0.1;
			DB::update('extra_service_invoice',array('total_amount'=>$total,'tax_rate'=>10,'service_rate'=>5),'id='.$row['id']);
		}
		elseif($reservation_room['extra_bed'])
		{
			$data = array(
				'reservation_room_id'=>$reservation_room_id,
				'user_id'=>$reservation_room['checked_in_user_id'],
				'portal_id'=>PORTAL_ID,
				'payment_type'=>'SERVICE',
				'note'=>'Invoice for Extra bed',
				'use_extra_bed'=>1,
				'time'=>$reservation_room['time']
			);
			$id = DB::insert('extra_service_invoice',$data);
			$from = Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['extra_bed_from_date'],'/'));
			$to   = Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['extra_bed_to_date'],'/'));
			$d = $from;
			$service = DB::fetch('select * from extra_service where code=\'EXTRA_BED\'');
			$total_extra_bed = 0;
			if($from==$to)
			{
				$extra_service_invoice_detail = array(
					'invoice_id'=>$id,
					'service_id'=>$service['id'],
					'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
					'price'=>$reservation_room['extra_bed_rate']?$reservation_room['extra_bed_rate']:$service['price'],
					'time'=>time(),
					'name'=>$service['name'],
					'quantity'=>1,
					'used'=>1
				);
				$total_extra_bed+=$reservation_room['extra_bed_rate']?$reservation_room['extra_bed_rate']:$service['price'];
				DB::insert('extra_service_invoice_detail',$extra_service_invoice_detail);
			}
			else
			{
				while($d>=$from and $d<$to)
				{
					$extra_service_invoice_detail = array(
						'invoice_id'=>$id,
						'service_id'=>$service['id'],
						'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
						'price'=>$reservation_room['extra_bed_rate']?$reservation_room['extra_bed_rate']:$service['price'],
						'time'=>time(),
						'name'=>$service['name'],
						'quantity'=>1,
						'used'=>1
					);
					$total_extra_bed+=$reservation_room['extra_bed_rate'];
					DB::insert('extra_service_invoice_detail',$extra_service_invoice_detail);
					$d=$d+(3600*24);
				}
			}
			$total_before_tax = $total_extra_bed;
			$total = $total_before_tax + ($total_before_tax*0.05) + ($total_before_tax + $total_before_tax*0.05)*0.1;
			DB::update('extra_service_invoice',array('total_amount'=>$total,'tax_rate'=>10,'service_rate'=>5,'bill_number'=>'ES'.$id),'id='.$id);
		}
	}
}
function update_baby_cot_invoice($reservation_room_id,$action='update')
{
	if($reservation_room = DB::select('reservation_room','id='.$reservation_room_id.' AND reservation_room.status<>\'CANCEL\''))
	{
		if($row = DB::select('extra_service_invoice','reservation_room_id='.$reservation_room_id.' AND use_baby_cot=1') and !$reservation_room['baby_cot'])
		{
			DB::delete('extra_service_invoice_detail','invoice_id='.$row['id']);
			DB::delete('extra_service_invoice','id='.$row['id']);
		}
		elseif($row)
		{
			$data = array(
				'reservation_room_id'=>$reservation_room_id,
				'user_id'=>$reservation_room['checked_in_user_id'],
				'portal_id'=>PORTAL_ID,
				'payment_type'=>'SERVICE',
				'note'=>'Invoice for Baby cot',
				'use_baby_cot'=>1,
				'time'=>$reservation_room['time_in']
			);
			DB::update('extra_service_invoice',$data,'id='.$row['id']);
			DB::delete('extra_service_invoice_detail','in_date<\''.$reservation_room['baby_cot_from_date'].'\' AND invoice_id='.$row['id']);
			DB::delete('extra_service_invoice_detail','in_date>=\''.$reservation_room['baby_cot_to_date'].'\' AND invoice_id='.$row['id']);
			$from = Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['baby_cot_from_date'],'/'));
			$to   = Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['baby_cot_to_date'],'/'));
			$d = $from;
			$service = DB::fetch('select * from extra_service where code=\'BABY_COT\'');
			$total_baby_cot = 0;
			if($from==$to)
			{
				$extra_service_invoice_detail = array(
					'invoice_id'=>$row['id'],
					'service_id'=>$service['id'],
					'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
					'price'=>$reservation_room['baby_cot_rate']?$reservation_room['baby_cot_rate']:$service['price'],
					'time'=>time(),
					'name'=>$service['name'],
					'quantity'=>1,
					'used'=>1
				);
				$total_baby_cot+=$reservation_room['baby_cot_rate']?$reservation_room['baby_cot_rate']:$service['price'];
				if($row_detail = DB::select('extra_service_invoice_detail','in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$d)).'\' and extra_service_invoice_detail.invoice_id='.$row['id']))
				{
					DB::update('extra_service_invoice_detail',$extra_service_invoice_detail,'id='.$row_detail['id']);
				}
				else
				{
					DB::insert('extra_service_invoice_detail',$extra_service_invoice_detail);
				}
			}
			else
			{
				while($d>=$from and $d<$to)
				{
					$extra_service_invoice_detail = array(
						'invoice_id'=>$row['id'],
						'service_id'=>$service['id'],
						'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
						'price'=>$reservation_room['baby_cot_rate']?$reservation_room['baby_cot_rate']:$service['price'],
						'time'=>time(),
						'name'=>$service['name'],
						'quantity'=>1,
						'used'=>1
					);
					$total_baby_cot+=$reservation_room['baby_cot_rate']?$reservation_room['baby_cot_rate']:$service['price'];
					if($row_detail = DB::select('extra_service_invoice_detail','in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$d)).'\' and extra_service_invoice_detail.invoice_id='.$row['id']))
					{
						DB::update('extra_service_invoice_detail',$extra_service_invoice_detail,'id='.$row_detail['id']);
					}
					else
					{
						DB::insert('extra_service_invoice_detail',$extra_service_invoice_detail);
					}
					$d=$d+(3600*24);
				}
			}
			$total_before_tax = $total_baby_cot;
			$total = $total_before_tax + ($total_before_tax*0.05) + ($total_before_tax + $total_before_tax*0.05)*0.1;
			DB::update('extra_service_invoice',array('total_amount'=>$total,'tax_rate'=>10,'service_rate'=>5),'id='.$row['id']);
		}
		elseif($reservation_room['baby_cot'])
		{
			$data = array(
				'reservation_room_id'=>$reservation_room_id,
				'user_id'=>$reservation_room['checked_in_user_id'],
				'portal_id'=>PORTAL_ID,
				'payment_type'=>'SERVICE',
				'note'=>'Invoice for Baby cot',
				'use_baby_cot'=>1,
				'time'=>$reservation_room['time_in']
			);
			$id = DB::insert('extra_service_invoice',$data);
			$from = Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['baby_cot_from_date'],'/'));
			$to   = Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['baby_cot_to_date'],'/'));
			$d = $from;
			$service = DB::fetch('select * from extra_service where code=\'BABY_COT\'');
			$total_baby_cot = 0;
			if($from==$to)
			{
				$extra_service_invoice_detail = array(
					'invoice_id'=>$id,
					'service_id'=>$service['id'],
					'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
					'price'=>$reservation_room['baby_cot_rate']?$reservation_room['baby_cot_rate']:$service['price'],
					'time'=>time(),
					'name'=>$service['name'],
					'quantity'=>1,
					'used'=>1
				);
				$total_baby_cot+=$reservation_room['baby_cot_rate']?$reservation_room['baby_cot_rate']:$service['price'];
				DB::insert('extra_service_invoice_detail',$extra_service_invoice_detail);
			}
			else
			{
				while($d>=$from and $d<$to)
				{
					$extra_service_invoice_detail = array(
						'invoice_id'=>$id,
						'service_id'=>$service['id'],
						'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
						'price'=>$reservation_room['baby_cot_rate']?$reservation_room['baby_cot_rate']:$service['price'],
						'time'=>time(),
						'name'=>$service['name'],
						'quantity'=>1,
						'used'=>1
					);
					$total_baby_cot+=$reservation_room['baby_cot_rate']?$reservation_room['baby_cot_rate']:$service['price'];
					DB::insert('extra_service_invoice_detail',$extra_service_invoice_detail);
					$d=$d+(3600*24);
				}
			}
			$total_before_tax = $total_baby_cot;
			$total = $total_before_tax + ($total_before_tax*0.05) + ($total_before_tax + $total_before_tax*0.05)*0.1;
			DB::update('extra_service_invoice',array('total_amount'=>$total,'tax_rate'=>10,'service_rate'=>5,'bill_number'=>'ES'.$id),'id='.$id);
		}
	}}
?>