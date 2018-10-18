<?php
class DeparturesNotCheckedOutForm extends Form
{
	function DeparturesNotCheckedOutForm()
	{
		Form::Form('DeparturesNotCheckedOutForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css('packages/hotel/skins/default/css/night_audit.css');
	}
	function on_submit()
	{
		
	}
	function draw()
	{
		$this->map = array();
		$cond = '
			reservation_room.status = \'CHECKIN\' AND reservation_room.confirm = 1
			AND reservation.portal_id = \''.PORTAL_ID.'\'
			AND (reservation_room.departure_time <= \''.Date_Time::to_orc_date($_SESSION['night_audit_date']).'\')
		';
		$item_per_page = Portal::get_setting('item_per_page',200);
		DB::query('
			select count(*) as acount
			from 
				reservation_room
				inner join reservation on reservation.id=reservation_room.reservation_id					
				inner join room_status on room_status.reservation_room_id=reservation_room.id					
				left outer join tour on tour.id=reservation.tour_id
				left outer join room on room.id=reservation_room.room_id
				left outer join room_level on room_level.id=room.room_level_id 
				left outer join reservation_traveller on reservation_room.id=reservation_traveller.reservation_room_id
				left outer join traveller on reservation_traveller.traveller_id=traveller.id
				left outer join customer on reservation.customer_id=customer.id
			where '.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page);
		$sql = '
			SELECT * FROM
			(
				select 
					distinct
					reservation_room.id,reservation_room.reservation_id
					,reservation_room.ADULT 
					,reservation_room.price
					,reservation_room.time_in
					,reservation_room.time_out
					,reservation_room.status
					,room.name as room_name
					,room_level.name as room_level_name
					,room_level.name as room_level_id 
					,reservation.customer_id
					,reservation.tour_id
					,reservation.user_id
					,reservation.user_id as user_name
					,reservation.note
					,DECODE(reservation_room.status,\'CHECKIN\',1,\'BOOKED\',2,DECODE(reservation_room.status,\'CHECKOUT\',3,4)) as order_type
					,reservation_room.time
					,reservation_room.lastest_edited_user_id
					,reservation_room.lastest_edited_time
					,customer.name as company_name
					,tour.name as tour_name
					,CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as guest_name
					,ROWNUM as rownumber
				from 
					reservation_room
					inner join reservation on reservation.id=reservation_room.reservation_id
					inner join room_status on room_status.reservation_room_id=reservation_room.id					left outer join tour on tour.id=reservation.tour_id
					left outer join room on room.id=reservation_room.room_id
					left outer join room_level on room_level.id=room.room_level_id 
					left outer join reservation_traveller on reservation_room.id=reservation_traveller.reservation_room_id
					left outer join traveller on reservation_traveller.traveller_id=traveller.id
					left outer join customer on reservation.customer_id=customer.id
				where 
					'.$cond.'
				order by
					room.name
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
		$items = DB::fetch_all($sql);
		foreach($items as $key=>$value){
			$items[$key]['arrival_time'] = date('d/m/Y H:i\'',$value['time_in']);
			$items[$key]['departure_time'] = date('d/m/Y H:i\'',$value['time_out']);			
		}
		$this->map['items'] = $items;
		$this->parse_layout('departures_not_checked_out',$this->map);
	}
}
?>