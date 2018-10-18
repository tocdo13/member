<?php
define('DEVELOPING',false);
define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
require_once ROOT_PATH.'packages/core/includes/system/config.php';
if(User::is_admin()){
	$sql = '
		select
			reservation_room.id,reservation_room.arrival_time,reservation_room.departure_time
			,reservation_room.reservation_id
			,room_status.in_date,room_status.change_price,reservation_room.price
			,reservation.portal_id
			,room_status.id as rs_id
		from
			reservation_room
			inner join reservation on reservation.id = reservation_room.reservation_id
			inner join room_status on room_status.reservation_room_id = reservation_room.id
		where
			room_status.change_price <> reservation_room.price
			and room_status.in_date < reservation_room.departure_time
			and reservation_room.arrival_time < reservation_room.departure_time
			and reservation_room.status <> \'CANCEL\'
	';
	$items = DB::fetch_all($sql);
	echo '<h2>Fix change price</h2>';
	$i = 0;
	$size = sizeof($items).'<br>';
	foreach($items as $value){
		DB::update('room_status',array('change_price'=>$value['price']),'id = '.$value['rs_id']);
		echo '<div>('.$value['portal_id'].' - R '.$value['reservation_id'].'): Fixed change_price from '.$value['change_price'].' to '.$value['price'].' Date '.$value['in_date'].'</div>';
		$i++;
	}
	if($size >0 and $size == $i){
		echo  '<br>Fixed '.$i.' items...!';
	}else{
		echo 'Has nothing to fix...!';
	}
	echo '<br>------------------------<br>';
	$sql = '
		select
			room_status.*
		from 
			room_status
			inner join reservation_room on reservation_room.id = room_status.reservation_room_id
		where
			(room_status.in_date > reservation_room.departure_time OR room_status.in_date < reservation_room.arrival_time)
	';
	$items = DB::fetch_all($sql);
}
?>