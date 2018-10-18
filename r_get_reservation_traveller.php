<?php
	date_default_timezone_set('Asia/Saigon');//Define default time for global system
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once 'packages/core/includes/system/config.php';
	header("content-type: application/x-javascript");
	$sql_r_r = '
		select
			reservation_room.id
			,reservation_room.price
			,reservation_room.vip_card_code
			,reservation_room.status as old_status
			,reservation_room.status
			,reservation_room.adult
			,reservation_room.child 					
			,reservation_room.time_in
			,reservation_room.time_out
			,reservation_room.arrival_time 
			,reservation_room.departure_time
			,reservation_room.note
			,reservation_room.total_amount 
			,reservation_room.reduce_balance 
			,reservation_room.reduce_amount
			,reservation_room.deposit
			,reservation_room.deposit_type
			,reservation_room.deposit_date
			,reservation_room.tax_rate ,reservation_room.service_rate 
			,reservation_room.room_level_id 
			,room_level.brief_name as room_level_name
			,reservation_room.room_id
			,reservation_room.room_id AS room_id_old 
			,CASE WHEN room.name is null THEN reservation_room.temp_room ELSE room.name END room_name
			,CASE WHEN room.name is null THEN reservation_room.temp_room ELSE room.name END room_name_old
			,payment_type.def_code
			,DECODE(reservation_room.arrival_time,reservation_room.departure_time,1,0) AS in_day
			,reservation_room.traveller_id
			,reservation_room.reservation_id 
			,reservation_room.foc 
			,reservation_room.foc_all
			,reservation_room.reservation_type_id
			,reservation_room.related_rr_id
			,reservation_room.confirm
			,reservation_room.closed
			,reservation_room.early_checkin
			,reservation_room.deposit_invoice_number
			,reservation_room.early_arrival_time
			,reservation_room.verify_dayuse
			,reservation_room.net_price
			,reservation_room.extra_bed
			,to_char(reservation_room.extra_bed_from_date,\'DD/MM/YYYY\') as extra_bed_from_date
			,to_char(reservation_room.extra_bed_to_date,\'DD/MM/YYYY\') as extra_bed_to_date
			,reservation_room.extra_bed_rate
			,reservation_room.baby_cot
			,to_char(reservation_room.baby_cot_from_date,\'DD/MM/YYYY\') as baby_cot_from_date
			,to_char(reservation_room.baby_cot_to_date,\'DD/MM/YYYY\') as baby_cot_to_date
			,reservation_room.baby_cot_rate
			,reservation_room.net_price
		from
			reservation_room
			left outer join room on room.id=reservation_room.room_id
			left outer join room_level on room_level.id=reservation_room.room_level_id
			left outer join room_status on room_status.reservation_room_id=reservation_room.id
			left outer join payment_type on payment_type.id=reservation_room.payment_type_id
		where
			reservation_room.reservation_id='.Url::iget('id').'
			'.(URL::get('reservation_room_id')?' and reservation_room.id=\''.URL::get('reservation_room_id').'\'':'').'
		order by
			reservation_room.time_in asc';		
	$mi_reservation_room = DB::fetch_all($sql_r_r);		
	$sql = '
		select
			reservation_traveller.id
			,reservation_traveller.id as reservation_traveller_id
			,traveller.first_name ,traveller.last_name ,traveller.gender ,
			to_char(traveller.birth_date,\'DD/MM/YYYY\') as birth_date,
			traveller.passport ,traveller.visa ,reservation_traveller.special_request as note ,
			traveller.phone ,traveller.fax ,traveller.address ,traveller.email 
			,country.code_1 as nationality_id
			,country.name_'.Portal::language().' as nationality_name
			,reservation_room.reservation_id
			,reservation_traveller.reservation_room_id
			,reservation_traveller.arrival_time
			,reservation_traveller.departure_time
			,reservation_traveller.visa_number as visa
			,to_char(reservation_traveller.expire_date_of_visa,\'DD/MM/YYYY\') as expire_date_of_visa
			,reservation_traveller.flight_code
			,reservation_traveller.flight_arrival_time
			,reservation_traveller.flight_code_departure
			,reservation_traveller.flight_departure_time
			,reservation_traveller.car_note_arrival
			,reservation_traveller.car_note_departure															
			,to_char(reservation_traveller.arrival_date,\'DD/MM/YYYY\') as traveller_arrival_date
			,to_char(reservation_traveller.departure_date,\'DD/MM/YYYY\') as traveller_departure_date
			,CASE WHEN reservation_room.room_id is not null THEN room.name ELSE reservation_room.temp_room END as mi_traveller_room_name
			,CASE WHEN reservation_room.room_id is not null THEN concat(room.id,concat(\'-\',to_char(reservation_room.departure_time,\'DD/MM/YYYY\'))) ELSE concat(reservation_room.temp_room,concat(\'-\',to_char(reservation_room.departure_time,\'DD/MM/YYYY\'))) END as traveller_room_id
			,DECODE(reservation_room.traveller_id,reservation_traveller.traveller_id,1,0) as traveller_id
			,traveller.id as traveller_id_
			,reservation_traveller.time_out
			,reservation_traveller.time_in
			,traveller.traveller_level_id
			,reservation_traveller.status
			,reservation_traveller.pickup
			,reservation_traveller.see_off
			,reservation_traveller.pickup_foc
			,reservation_traveller.see_off_foc
		from
			reservation_traveller
			inner join traveller on traveller.id=reservation_traveller.traveller_id
			left outer join reservation_room on reservation_room.id=reservation_traveller.reservation_room_id
			left outer join room on reservation_room.room_id=room.id
			left outer join country on traveller.nationality_id=country.id
		where
			reservation_room.reservation_id='.Url::iget('id')
			.(URL::get('reservation_room_id')?' and reservation_room.id=\''.URL::get('reservation_room_id').'\'':'').'
		order by
				reservation_traveller.id DESC
			';
	$mi_travellers = DB::fetch_all($sql);
	foreach($mi_travellers as $k=>$mi_traveller)	
	{	
		$mi_travellers[$k]['old_reservation_room_id'] = $mi_traveller['reservation_room_id'];
		$mi_travellers[$k]['check_out'] = 0;
		if($mi_traveller['arrival_time'])
		{
			$mi_travellers[$k]['arrival_hour'] = date('H:i',$mi_traveller['arrival_time']);
		}
		if($mi_traveller['departure_time'])
		{
			$mi_travellers[$k]['departure_hour'] = date('H:i',$mi_traveller['departure_time']);				
		}
		if($mi_traveller['flight_arrival_time'])
		{
			$mi_travellers[$k]['flight_arrival_hour'] = date('H:i',$mi_traveller['flight_arrival_time']);	
			$mi_travellers[$k]['flight_arrival_date'] = date('d/m/Y',$mi_traveller['flight_arrival_time']);	
		}
		if($mi_traveller['flight_departure_time'])
		{
			$mi_travellers[$k]['flight_departure_hour'] = date('H:i',$mi_traveller['flight_departure_time']);	
			$mi_travellers[$k]['flight_departure_date'] = date('d/m/Y',$mi_traveller['flight_departure_time']);	
		}
		// Check xem khach nao da chuyeern phong
		foreach($mi_reservation_room as $mi => $arr){  
			if($mi_traveller['status'] =='CHECKOUT' || $mi_traveller['status'] =='CHANGE'){// khach da out
				$mi_travellers[$k]['check_out'] = 1;	
			}
		}
	}
	echo 'var mi_traveller_arr = '.String::array2js($mi_travellers).';';
	DB::close();
?>