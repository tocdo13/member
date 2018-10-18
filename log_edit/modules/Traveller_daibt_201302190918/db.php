<?php
class ListTravellerDB{
	function get_reservation($cond='1=1'){
		return DB::fetch_all('
			select
				reservation.id,CONCAT(reservation.id,CONCAT(\' - \',reservation.booking_code)) as name,tour.name as tour_name,customer.name as customer_name
			from
				reservation
				inner join reservation_room on reservation_room.reservation_id = reservation.id
				left outer join customer on customer.id = reservation.customer_id
				left outer join tour on tour.id = reservation.tour_id
			where
				'.$cond.'
				and reservation.portal_id = \''.PORTAL_ID.'\'
				and reservation_room.arrival_time = \''.Date_time::to_orc_date(Url::sget('arrival_time')).'\'
			order by
				reservation.id DESC
		');
	}
	function get_reservation_room($cond='1=1'){
		$sql = '
			select
				reservation_room.id,room.name,reservation_room.time_in,reservation_room.time_out,reservation_traveller.time_out as traveller_time_out,
				reservation_traveller.entry_target,reservation_traveller.entry_date as entry_date,reservation_traveller.port_of_entry,
				reservation_traveller.back_date,reservation_traveller.go_to_office,reservation_traveller.provisional_residence,
				reservation_traveller.hotel_name,reservation_traveller.distrisct,reservation_traveller.come_from,reservation_traveller.visa_number,
				reservation_traveller.expire_date_of_visa,reservation_traveller.entry_form_number,reservation_traveller.occupation,
				reservation_traveller.input_staff,reservation_traveller.input_date
			from
				reservation_room
				inner join reservation on reservation.id  =  reservation_room.reservation_id
				inner join room_status on room_status.reservation_room_id  =  reservation_room.id
				inner join room on reservation_room.room_id = room.id
				left outer join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
			where
				'.$cond.'
				and reservation.portal_id = \''.PORTAL_ID.'\'	
				and reservation_room.status = \'CHECKIN\'
				and room_status.status = \'OCCUPIED\'
			order by
				room.name
		';
		return DB::fetch_all($sql);//and reservation_room.time_out > '.(Date_Time::to_time(date('d/m/Y'))+24*3600).'
		//and reservation_room.arrival_time = \''.Date_time::to_orc_date(date('d/m/Y')).'\'
	}
}
?>