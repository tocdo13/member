<?php 
	class MonthlyRoomReportDB{
		static function get_items($date_from,$date_to,$check){
			$time_from = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_from,'/'));
			$time_to = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_to,'/')) + (24*3600);			
			$sql = '
			select
				room_status.id,
				room_status.room_id,
				room_status.status,
				room_status.note,
				reservation.note as reservation_note,
				reservation_room.note as reservation_room_note,
				reservation_room.adult,
				room_status.in_date,
				reservation_room.service_rate,
				reservation_room.tax_rate,
				reservation_room.reduce_balance,
				to_char(room_status.in_date,\'DD\') as day,
				room_status.reservation_id,
				room_status.reservation_room_id,
				reservation_room.status as reservation_status,
				room_status.change_price as price,
				reservation_room.arrival_time,
				reservation_room.departure_time,
				DATE_TO_UNIX(reservation_room.departure_time) as end_time,
				DATE_TO_UNIX(reservation_room.arrival_time) as start_time,
				CASE 
					WHEN 
						reservation.tour_id = 0 or reservation.tour_id is null
					THEN
						CASE
							WHEN 
								reservation.customer_id is null or reservation.customer_id=\'\'
							THEN
								concat(DECODE(traveller.gender,1,\'Mr. \',\'Ms. \'),concat(traveller.first_name,concat(\' \',traveller.last_name)))
							ELSE
								customer.name
						END
					ELSE
						tour.name
				END customer,
				traveller.gender,
				to_char(FROM_UNIXTIME(reservation_room.time_in),\'HH\') as time_in_hour,
				to_char(FROM_UNIXTIME(reservation_room.time_out),\'HH\') as time_out_hour,
				reservation_room.departure_time - reservation_room.arrival_time as nights,
				tour.name as tour_name,
				reservation.color,
				room.id as room_id
			from
				room_status
				inner join reservation_room on room_status.reservation_room_id = reservation_room.id 
				inner join reservation on room_status.reservation_id = reservation.id
				left outer join customer on reservation.customer_id = customer.id
				left outer join tour on reservation.tour_id = tour.id
				left outer join room on room.id = reservation_room.room_id
				left outer join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
				left outer join traveller on traveller.id = reservation_traveller.traveller_id
			where
				room_status.status<>\'AVAILABLE\' AND reservation_room.status<>\'CANCEL\' AND room_status.in_date>=\''.$date_from.'\' and room_status.in_date<=\''.$date_to.'\' AND reservation.portal_id = \''.PORTAL_ID.'\'
			order by
				room_status.reservation_room_id ASC
		';   
		//DECODE(reservation_room.departure_time,reservation_room.arrival_time,reservation_room.extra_charge,0) as extra_charge,
		//DECODE(room_status.in_date,reservation_room.departure_time,reservation_room.extra_charge,0) as extra_charge,
		//and (room_status.in_date<>reservation_room.departure_time or reservation_room.departure_time=reservation_room.arrival_time)
		
		$room_statuses = DB::fetch_all($sql);
		require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';
		//$num_day = cal_days_in_month(CAL_GREGORIAN,$month,$year);
		$rooms = DB::fetch_all('select room.id, 0 as lately_checkout,\'black\' as price_color,1 as can_book,room_type_id,room_level_id, room_type.price as room_price, room.name, 0 as reservation_id, \'\' as price,0 as total,\'\' as note, room_type.name as type,room_level.brief_name as room_level_name from room inner join room_type on room_type_id=room_type.id inner join room_level on room_level_id=room_level.id WHERE room_level.portal_id = \''.PORTAL_ID.'\' order by room_level.id');
		$days = array();
		$room_types = DB::fetch_all('select id,price,name,color, 0 as remain from room_level WHERE room_level.portal_id = \''.PORTAL_ID.'\' order by name');
		//$room_types = DB::fetch_all('select id,price,name,color, 0 as remain from room_type order by name');
		//System::Debug($room_types);
		$i = 0;
		foreach($rooms as $id=>$room)
		{
			for($j = $time_from; $j<$time_to ; $j+=24*3600){
				if(!isset($rooms[$id]['days'])){
					$rooms[$id]['days'][$j] = array();
				}
				$rooms[$id]['days'][$j]['day'] = $j;
			}
			$rooms[$id]['stt'] = $i++;
			$room_types[$room['room_level_id']]['remain'] ++;
		}		
		foreach($room_statuses as $key => $status){
			if(isset($rooms[$status['room_id']])){
				for($i = $time_from; $i<$time_to ; $i+=24*3600){

					if((date('d/m/Y',$i) == Date_Time::convert_orc_date_to_date($status['in_date'],'/')) && ($status['departure_time'] != $status['in_date'] || $status['departure_time'] == $status['arrival_time'])){
							$rooms[$status['room_id']]['days'][$i] = $status;
						if(($time_from > $rooms[$status['room_id']]['days'][$i]['start_time']) && ($time_to < $rooms[$status['room_id']]['days'][$i]['end_time'])){
							$rooms[$status['room_id']]['days'][$i]['nights'] = floor(($time_to - $time_from)/84600);	
						}else if($time_to < $rooms[$status['room_id']]['days'][$i]['end_time']){
							$rooms[$status['room_id']]['days'][$i]['nights'] = 	floor(($time_to - $rooms[$status['room_id']]['days'][$i]['start_time'])/84600);
						}else if($time_from > $rooms[$status['room_id']]['days'][$i]['start_time']){
							$rooms[$status['room_id']]['days'][$i]['nights'] = 	floor(($rooms[$status['room_id']]['days'][$i]['end_time'] - $time_from)/84600);	
						}
						if($rooms[$status['room_id']]['days'][$i]['nights'] ==1){
						 	$rooms[$status['room_id']]['days'][$i]['cus'] = substr($rooms[$status['room_id']]['days'][$i]['customer'],0,10);
						}else{
							$rooms[$status['room_id']]['days'][$i]['cus'] = $rooms[$status['room_id']]['days'][$i]['customer'];
						}
						if($rooms[$status['room_id']]['days'][$i]['cus'] == ''){
							$rooms[$status['room_id']]['days'][$i]['cus'] = ($status['gender']==1)?'Mr':'Ms' ;
							$rooms[$status['room_id']]['days'][$i]['customer'] = $status['customer'];	
						}
						if($rooms[$status['room_id']]['days'][$i]['nights'] ==0){
							//$rooms[$status['room_id']]['days'][$i]['nights'] =1;	
						}
						$rooms[$status['room_id']]['days'][$i]['day'] = $i;
					}else if(!isset($rooms[$status['room_id']]['days'][$i])){
						$rooms[$status['room_id']]['days'][$i] = array();
						$rooms[$status['room_id']]['days'][$i]['day'] = $i;
					}
				}
			}
		}	
	foreach($rooms as $k => $room){
		if(isset($room_types[$room['room_level_id']]) && $room_types[$room['room_level_id']]['id']== $room['room_level_id']){
			$rooms[$k]['color'] = 	$room_types[$room['room_level_id']]['color'];
		}
			foreach($room['days'] as $d=> $day){
				if(isset($day['nights'])){
					if($day['nights'] == 0 && isset($room['days'][$day['day']+86400]) && isset($room['days'][$day['day']+86400]['nights'])){
						if($room['days'][$day['day']+86400]['arrival_time'] == $day['in_date']){
							//$rooms[$k]['days'][$d]['nights'] = 0.5;
							//$rooms[$k]['days'][$day['day']+86400]['nights'] =$rooms[$k]['days'][$day['day']+86400]['nights']-0.55 ;
							//for(var $j = )
							//if(isset())
						}else{
							$rooms[$k]['days'][$d]['nights'] = 1;	
						}
					}else if($day['nights'] == 0){
						$rooms[$k]['days'][$d]['nights'] = 1;		
					}
				}
			}
		}
		//System::Debug($rooms);
		if($check == false){
			$dir_string = 'cache/data/'.str_replace('#','',PORTAL_ID).'';
			if(!is_dir($dir_string)){
				mkdir($dir_string);	
			}
			$str = " var items_js=";
			$str.= String::array2js($rooms);
			$str.= '';
			$f = fopen($dir_string.'/list_items_room.js','w+');
			fwrite($f,$str);
			fclose($f);
			
		}else{
			return $rooms;	
		}
	}
	static function get_rooms($check){
		$rooms = DB::fetch_all('select room.id, 0 as lately_checkout,\'black\' as price_color,1 as can_book,room_type_id,room_level_id, room_type.price as room_price, room.name, 0 as reservation_id, \'\' as price,0 as total,\'\' as note, room_type.name as type,room_level.brief_name as room_level_name from room inner join room_type on room_type_id=room_type.id inner join room_level on room_level_id=room_level.id WHERE room_level.portal_id = \''.PORTAL_ID.'\' order by room_level.id');
		if($check==false){
			$dir_string = 'cache/data/'.str_replace('#','',PORTAL_ID).'';
			if(!is_dir($dir_string)){
				mkdir($dir_string);	
			}
			$str = " var rooms_array=";
			$str.= String::array2js($rooms);
			$str.= '';
			$f = fopen($dir_string.'/list_room_array.js','w+');
			fwrite($f,$str);
			fclose($f);
		}else{
			return $rooms;		

		}
	}	
}
?>