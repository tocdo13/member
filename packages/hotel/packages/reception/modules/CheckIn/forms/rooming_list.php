<?php
/*
	Class: kiem tra phong trong tu dong
	Written by: khoand
	Date: 20/01/2011
*/
class RoomingListForm extends Form
{
	function RoomingListForm()
	{
		Form::Form('RoomingListForm');
		$this->link_css(Portal::template('hotel').'/css/report.css');
	  	$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css('packages/hotel/'.Portal::template('reception').'/css/rooming_list.css');
	}
	function draw()
	{
		$this->map = array();
		$sql='select 
				reservation.*,
				tour.name AS group_name,
				customer.name AS customer_name,customer.address,customer.phone,customer.email,
				to_char(tour.arrival_time,\'DD/MM/YYYY\') as arrival_time,
				to_char(tour.departure_time,\'DD/MM/YYYY\') as departure_time
			from 
				reservation 
				left outer join tour on tour.id=reservation.tour_id
				left outer join customer on reservation.customer_id = customer.id
			where 
				reservation.id='.Url::iget('id').'';
		if($row = DB::fetch($sql)){
			$this->map += $row;
			$this->map['hotel_name'] = HOTEL_NAME;
			$sql = '
				select 
					distinct
					reservation_room.id,reservation_room.reservation_id
					,reservation_room.adult
					,reservation_room.child 
					,reservation_room.price
					,reservation_room.time_in
					,reservation_room.time_out
					,reservation_room.status
					,reservation_room.temp_room
					,CASE WHEN room.name is null THEN reservation_room.temp_room ELSE room.name END room_name
					,arrival_time
					,departure_time
					,to_char(arrival_time,\'DD/MM/YYYY\') as brief_arrival_time
					,to_char(departure_time,\'DD/MM/YYYY\') as brief_departure_time
					,room_level.brief_name as room_level_name
					,reservation.customer_id
					,reservation.tour_id
					,reservation.user_id
					,reservation.user_id as user_name
					,reservation.note
					,DECODE(reservation_room.status,\'CHECKIN\',1,\'BOOKED\',2,DECODE(reservation_room.status,\'CHECKOUT\',3,4)) as order_type
					,reservation_room.time
					,reservation_room.lastest_edited_user_id
					,reservation_room.lastest_edited_time
					,reservation_room.checked_in_user_id
					,reservation_room.booked_user_id
					,party.name_1 as portal_name
					,reservation.booking_code
					,row_number() over (order by reservation_room.status,reservation_room.id) as rownumber
				from 
					reservation_room
					inner join reservation on reservation.id = reservation_room.reservation_id
					inner join party on party.user_id = reservation.portal_id
					left outer join room on room.id = reservation_room.room_id
					left outer join room_level on room_level.id = reservation_room.room_level_id 
					'.((URL::get('customer_name') or URL::get('nationality_id') or URL::get('company_name') or URL::get('traveller_name')or URL::get('booking_resource'))?'
					left outer join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
					left outer join traveller on reservation_traveller.traveller_id = traveller.id
					left outer join tour on reservation.tour_id = tour.id
					left outer join customer on reservation.customer_id = customer.id':'').'
				where 
					reservation.id = '.Url::iget('id').'
				order by
					room_level.brief_name
			';
			$items = DB::fetch_all($sql);
			foreach($items as $key=>$value){
				$items[$key]['time_in'] = date('d/m/Y H:i\'',$value['time_in']);
				$items[$key]['time_out'] = date('d/m/Y H:i\'',$value['time_out']);
				$sql = '
				select
					reservation_traveller.id,reservation_traveller.time_in,reservation_traveller.time_out,
					CONCAT(traveller.first_name, CONCAT(\' \',traveller.last_name)) full_name,
					CASE WHEN traveller.gender = 1 THEN \''.Portal::language('mr').'\' ELSE \''.Portal::language('mrs/ms').'\' END as gender,
					reservation_traveller.special_request,
					row_number() over(order by reservation_traveller.id DESC) as i
				from
					reservation_traveller
					inner join traveller on traveller.id=reservation_traveller.traveller_id
				where
					reservation_traveller.reservation_id='.$value['reservation_id'].'
					AND (reservation_traveller.reservation_room_id = '.$value['id'].' OR reservation_traveller.temp_room = \''.$value['temp_room'].'\')
					';
				$travellers = DB::fetch_all($sql);
				foreach($travellers as $k=>$v){
					$travellers[$k]['time_in'] = $v['time_in']?date('d/m/Y H:i\'',$v['time_in']):'................';
					$travellers[$k]['time_out'] = $v['time_out']?date('d/m/Y H:i\'',$v['time_out']):'................';	
				}
				$items[$key]['travellers'] = $travellers;	
			}
			$this->map['items'] = $items;
			$this->parse_layout('rooming_list',$this->map);
		}
	}
}
?>