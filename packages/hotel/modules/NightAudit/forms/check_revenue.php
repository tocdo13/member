<?php
class CheckRevenueForm extends Form
{
	function CheckRevenueForm()
	{
		Form::Form('CheckRevenueForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css('packages/hotel/skins/default/css/night_audit.css');
		$this->cond = '
			(room_status.status = \'OCCUPIED\')
			AND reservation.portal_id = \''.PORTAL_ID.'\'
			AND (room_status.closed_time = 0 OR room_status.closed_time is null)
			AND (room_status.in_date = \''.Date_Time::to_orc_date($_SESSION['night_audit_date']).'\')
		';
		$this->close_revenue();
	}
	function close_revenue(){
		$tmp_arr = explode(':',NIGHT_AUDIT_TIME);
		$closed_time = Date_Time::to_time($_SESSION['night_audit_date']) + intval($tmp_arr[0])*3600 + 	intval($tmp_arr[1])*60;
		if(Url::get('close_all')){
			$sql = '
					select 
						room_status.id
					from 
						reservation_room
						INNER JOIN reservation ON reservation.id = reservation_room.reservation_id
						inner join room_status on room_status.reservation_room_id=reservation_room.id					
					where 
						'.$this->cond.'
			';
			$items = DB::fetch_all($sql);
			foreach($items as $key=>$value){
				DB::update('room_status',array('closed_time'=>$closed_time),'id = '.$value['id']);	
			}
			Url::redirect_current(array('cmd'));
		}
		if(Url::get('room_status_id') and $room_status = DB::select('room_status','id = '.Url::iget('room_status_id'))){
			DB::update('room_status',array('closed_time'=>$closed_time),'id = '.$room_status['id']);
			Url::redirect_current(array('cmd'));
		}
	
	}
	function on_submit()
	{
		
	}
	function draw()
	{
		$this->map = array();
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
			where '.$this->cond.'
		');
		$count = DB::fetch();
        System::debug($_SESSION['night_audit_date']);
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
					,room_status.change_price
					,reservation_room.time_in
					,reservation_room.time_out
					,reservation_room.status
					,room.name as room_name
					,room_level.name as room_level_name
					,reservation_room.arrival_time
					,reservation_room.departure_time
					,to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') as brief_arrival_time
					,to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as brief_departure_time
					,room_status.in_date
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
					,room_status.id as room_status_id
					,reservation_room.verify_dayuse
					,ROWNUM as rownumber
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
				where 
					'.$this->cond.'
				order by
					room.name
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
		$items = DB::fetch_all($sql);
		$total = 0;
		foreach($items as $key=>$value){
			$check = true;
				if($value['departure_time'] == $value['in_date']){
					if($value['arrival_time'] != $value['departure_time'] and !$value['verify_dayuse']){
						unset($items[$key]);
						$check = false;
					}
				}
			if($check==true){
				$items[$key]['arrival_time'] = date('d/m/Y H:i\'',$value['time_in']);
				$items[$key]['departure_time'] = date('d/m/Y H:i\'',$value['time_out']);
				if($value['verify_dayuse'] and $value['in_date'] == $value['departure_time']){
					$items[$key]['change_price'] = $value['change_price'] = DB::fetch('SELECT amount FROM reservation_room_service WHERE service_id =  7','amount'); //service_id = 7: checkout lately
				}
				$price = $value['change_price'];
				if($value['arrival_time'] != $value['departure_time'] and $value['departure_time'] != $value['in_date'] and $value['change_price']==0){
					DB::update('room_status',array('change_price'=>$value['price']),'id = '.$value['room_status_id'].'');
					$price = $value['price'];
				}
				$total += $price;
			}
		}
		$this->map['total'] = System::display_number($total);
		$this->map['items'] = $items;
		$this->parse_layout('check_revenue',$this->map);
	}
}
?>