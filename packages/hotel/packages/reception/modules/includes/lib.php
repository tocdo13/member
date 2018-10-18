<?php
function get_available_room($arrival_time,$departure_time,$time_in,$time_out){
	if($arrival_time and $departure_time){
		$arrival_time=Date_Time::to_time($arrival_time);
		$departure_time=Date_Time::to_time($departure_time);
		$cond = 'reservation_room.status<>\'CANCEL\' AND reservation_room.status<>\'CHECKOUT\'';
		if(isset($time_in) and $time_in and isset($time_out) and $time_out){
			$arr = explode(':',$time_in);
			$arrival_time = $arrival_time + intval($arr[0])*3600+intval($arr[1])*60;
			$arr = explode(':',$time_out);
			$departure_time= $departure_time + intval($arr[0])*3600+intval($arr[1])*60;
		}
		if($departure_time > $arrival_time){
			$cond .= ' AND (
					(reservation_room.time_in <= '.$arrival_time.' AND reservation_room.time_out >= '.$departure_time.')
				OR	(reservation_room.time_in >= '.$arrival_time.' AND reservation_room.time_out >= '.$departure_time.' AND reservation_room.time_in <= '.$departure_time.')
				OR	(reservation_room.time_in <= '.$arrival_time.' AND reservation_room.time_out >= '.$arrival_time.' AND reservation_room.time_out <= '.$departure_time.')
				OR	(reservation_room.time_in >= '.$arrival_time.' AND reservation_room.time_out <= '.$departure_time.')
				OR	(reservation_room.time_out = '.$arrival_time.')
			)';
			$sql = '
				SELECT
					reservation_room.room_id as id,room_level.name
				FROM
					reservation_room
					INNER JOIN room ON room.id = reservation_room.room_id
					INNER JOIN room_level ON room_level.id = room.room_level_id
				WHERE
					'.$cond.'
			';
			return DB::fetch_all($sql);
		}
		else{
			return false;
		}
	}else{
		return false;
	}
}
function get_paid_amount($bill_id,$type){
	require_once('cache/config/payment.php');
	/*define('PAY_FOR_ROOM',1);
	define('PAY_FOR_MINIBAR',2);
	define('PAY_FOR_LAUNDRY',3);
	define('PAY_FOR_FB',4);
	define('PAY_FOR_SPA',5);
	define('CASH',2);
	define('CREDIT_CARD',3);
	define('DIRECT_PAYMENT',1);*/
	$sql = '
		SELECT
			SUM(ROUND((payment.amount/payment.exchange_rate),2)) AS id
		FROM
			payment
		WHERE
			1=1
			'.($bill_id?'AND payment.bill_id = '.$bill_id.'':'').'
			'.($type?'AND payment.type = '.$type.'':'').'
	';
	if($item = DB::fetch($sql)){
		return $item['id'];
	}else{
		return 0;
	}
}
?>