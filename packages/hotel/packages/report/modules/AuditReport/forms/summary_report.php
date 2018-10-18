<?php
class SummaryReportForm extends Form
{
	function SummaryReportForm()
	{
		Form::Form('SummaryReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css('packages/hotel/skins/default/css/night_audit.css');
	}
	function draw()
	{
		$this->map = array();
		$this->map['today_ocuppied_rooms'] = 0;
		$this->map['today_check_ins'] = 0;
		$this->map['today_check_outs'] = 0;
		$this->map['today_bookeds'] = 0;
		$this->map['today_no_shows'] = 0;
		$this->map['today_cancellations'] = 0;
		$this->map['checked_out_rooms_marked_dirty'] = 0;
		$this->map['repairing_rooms'] = 0;
		$this->map['occupied_rooms_marked_for_dirty'] = 0;
		if(!isset($_REQUEST['in_date'])){
			$date = date('d/m/Y');
		}else{
			$date = Url::get('in_date');
		}
		$sql = '
				SELECT 
					to_char(in_date,\'DD/MM/YYYY\') as id,to_char(in_date,\'DD/MM/YYYY\') as name
				FROM 
					night_audit 
				WHERE 
					in_date < \''.Date_Time::to_orc_date(date('d/m/Y')).'\'
					AND (time is not null or time > 0)
				ORDER BY
					in_date DESC
		';
		$dates = DB::fetch_all($sql);
		$this->map['in_date_list'] = String::get_list($dates);
		$this->map['date'] = $date;
		$rooms = DB::fetch_all('select room.id from room inner join room_level on room_level.id = room.room_level_id where room_level.is_virtual is null or room_level.is_virtual = 0');
		$this->map['today_available_rooms'] = sizeof($rooms);
		$sql = '
			SELECT
				room_status.id,reservation_room.status,room_status.status as r_status,
				room_status.house_status,
				reservation_room.arrival_time,reservation_room.departure_time,
				reservation_room.confirm,
				reservation_room.lastest_edited_time
			FROM
				room_status
				INNER JOIN reservation_room ON reservation_room.id = room_status.reservation_room_id
			WHERE
				room_status.in_date = \''.Date_Time::to_orc_date(date('d/m/Y')).'\'
		';
		$items = DB::fetch_all($sql);
		foreach($items as $value){
			if($value['status'] == 'CHECKIN' and $value['r_status'] == 'OCCUPIED'){
				$this->map['today_ocuppied_rooms']++;
				$this->map['today_available_rooms']--;
				if($value['house_status']=='DIRTY'){
					$this->map['occupied_rooms_marked_for_dirty']++;
				}
			}
			if($value['status'] == 'CHECKIN' and Date_Time::convert_orc_date_to_date($value['arrival_time'],'/') == $date){
				$this->map['today_check_ins']++;
			}
			if($value['status'] == 'CHECKOUT' and Date_Time::convert_orc_date_to_date($value['departure_time'],'/') == $date){
				$this->map['today_check_outs']++;
				if($value['house_status']=='DIRTY'){
					$this->map['checked_out_rooms_marked_dirty']++;
				}
			}
			if($value['status'] == 'BOOKED' and Date_Time::convert_orc_date_to_date($value['arrival_time'],'/') == $date){
				$this->map['today_bookeds']++;
				$this->map['today_available_rooms']--;
			}
			if($value['status'] == 'BOOKED' and Date_Time::convert_orc_date_to_date($value['arrival_time'],'/') < $date){
				$this->map['today_no_shows']++;
				$this->map['today_available_rooms']--;
			}
			if($value['status'] == 'CANCEL' and date('d/m/Y',$value['lastest_edited_time']) == $date){
				$this->map['today_cancellations']++;
			}			
		}
		$sql = '
			SELECT 
				COUNT(room_status.id) AS id
			FROM 
				room_status 
			WHERE 
				room_status.in_date = \''.Date_Time::to_orc_date($date).'\' AND house_status=\'REPAIR\'';
		if($item = DB::fetch($sql)){
			$this->map['repairing_rooms'] = $item['id'];
		}
		//////////////////////////////////////////revenue/////////////////////////////////////////////////////////////////////////////////////////////
		$this->map['total_revenue'] = 0;
		$this->map['occupied_revenue'] = 0;
		$sql = '
			SELECT
				SUM(room_status.change_price) AS id
			FROM
				room_status
				INNER JOIN reservation_room ON reservation_room.id = room_status.reservation_room_id
			WHERE
				room_status.in_date = \''.Date_Time::to_orc_date($date).'\'
				AND room_status.status = \'OCCUPIED\'
		';
		if($item = DB::fetch($sql)){
			$this->map['total_revenue'] += $item['id'];
			$this->map['occupied_revenue'] = System::display_number($item['id']);
		}
		$this->map['booking_revenue'] = 0;
		$sql = '
			SELECT
				SUM(room_status.change_price) AS id
			FROM
				room_status
				INNER JOIN reservation_room ON reservation_room.id = room_status.reservation_room_id
			WHERE
				room_status.in_date = \''.Date_Time::to_orc_date($date).'\'
				AND reservation_room.status = \'BOOKED\'
				AND reservation_room.arrival_time = \''.Date_Time::to_orc_date($date).'\'
		';
		if($item = DB::fetch($sql)){
			$this->map['total_revenue'] += $item['id'];
			$this->map['booking_revenue'] = System::display_number($item['id']);
		}
		$this->map['no_show_revenue'] = 0;
		$sql = '
			SELECT
				SUM(room_status.change_price) AS id
			FROM
				room_status
				INNER JOIN reservation_room ON reservation_room.id = room_status.reservation_room_id
			WHERE
				room_status.in_date = \''.Date_Time::to_orc_date($date).'\'
				AND reservation_room.status = \'BOOKED\'
				AND reservation_room.arrival_time < \''.Date_Time::to_orc_date($date).'\'
		';
		if($item = DB::fetch($sql)){
			$this->map['total_revenue'] += $item['id'];
			$this->map['no_show_revenue'] = System::display_number($item['id']);
		}
		$this->map['cancellation_revenue'] = 0;
		$sql = '
			SELECT
				SUM(room_status.change_price) AS id
			FROM
				room_status
				INNER JOIN reservation_room ON reservation_room.id = room_status.reservation_room_id
			WHERE
				reservation_room.status = \'CANCEL\'
				AND room_status.in_date = \''.Date_Time::to_orc_date($date).'\'
		';
		if($item = DB::fetch($sql)){
			$this->map['total_revenue'] += $item['id'];
			$this->map['cancellation_revenue'] = System::display_number($item['id']);
		}
		$this->map['minibar_revenue'] = 0;
		$sql = '
			SELECT
				SUM(housekeeping_invoice.total) AS id
			FROM
				housekeeping_invoice
				INNER JOIN reservation_room ON reservation_room.id = housekeeping_invoice.reservation_room_id
			WHERE
				housekeeping_invoice.type = \'MINIBAR\'
				AND FROM_UNIXTIME(housekeeping_invoice.time) = \''.Date_Time::to_orc_date($date).'\'
		';
		if($item = DB::fetch($sql)){
			$this->map['total_revenue'] += $item['id'];
			$this->map['minibar_revenue'] = System::display_number($item['id']);
		}
		$this->map['laundry_revenue'] = 0;
		$sql = '
			SELECT
				SUM(housekeeping_invoice.total) AS id
			FROM
				housekeeping_invoice
				INNER JOIN reservation_room ON reservation_room.id = housekeeping_invoice.reservation_room_id
			WHERE
				housekeeping_invoice.type = \'LAUDRY\'
				AND FROM_UNIXTIME(housekeeping_invoice.time) = \''.Date_Time::to_orc_date($date).'\'
		';
		if($item = DB::fetch($sql)){
			$this->map['total_revenue'] += $item['id'];
			$this->map['laundry_revenue'] = System::display_number($item['id']);
		}
		$this->map['compensation_revenue'] = 0;	
		$sql = '
			SELECT
				SUM(housekeeping_invoice.total) AS id
			FROM
				housekeeping_invoice
				INNER JOIN reservation_room ON reservation_room.id = housekeeping_invoice.reservation_room_id
			WHERE
				housekeeping_invoice.type = \'EQUIP\'
				AND FROM_UNIXTIME(housekeeping_invoice.time) = \''.Date_Time::to_orc_date($date).'\'
		';
		if($item = DB::fetch($sql)){
			$this->map['total_revenue'] += $item['id'];
			$this->map['compensation_revenue'] = System::display_number($item['id']);
		}
		$this->map['restaurant_revenue'] = 0;
		$sql = '
				SELECT 
					SUM( BAR_RESERVATION.TOTAL) as id
				  FROM 
					BAR_RESERVATION
				  WHERE 
				   	'.Date_Time::to_time($date).'<= TIME_OUT and TIME_OUT <'.(Date_Time::to_time($date)+(24*3600)).'
			';
		if($item = DB::fetch($sql)){
			$this->map['total_revenue'] += $item['id'];
			$this->map['restaurant_revenue'] = System::display_number($item['id']);
		}
		$this->map['karaoke_revenue'] = 0;	
		$sql = '
			SELECT 
				sum(ka_reservation.total) as id
			  FROM 
				ka_reservation
				inner join ka_reservation_room on ka_reservation_room.ka_reservation_id = ka_reservation.id
				inner join reservation_room on reservation_room.id=ka_reservation.reservation_room_id
			  WHERE 
			   ka_reservation.time_out>='.Date_Time::to_time($date).'
			   AND ka_reservation.time_out < '.(Date_Time::to_time($date)+(24*3600)).'
		';
		if($item = DB::fetch($sql)){
			$this->map['total_revenue'] += $item['id'];
			$this->map['karaoke_revenue'] = System::display_number($item['id']);
		}
		$this->map['massage_revenue'] = 0;
		$sql = '
			SELECT 
				 sum(massage_product_consumed.price*massage_product_consumed.quantity) as id
			  FROM 
				massage_product_consumed
				inner join massage_room on massage_product_consumed.room_id = massage_room.id
			  WHERE 
			   massage_product_consumed.time_out>='.Date_Time::to_time($date).'
			   AND massage_product_consumed.time_out < '.(Date_Time::to_time($date)+(24*3600)).'
		';
		if($item = DB::fetch($sql)){
			$this->map['total_revenue'] += $item['id'];
			$this->map['massage_revenue'] = System::display_number($item['id']);
		}
		$this->map['tennis_revenue'] = 0;	
		$sql = '
			SELECT 
				 sum(tennis_reservation_court.total_amount) as id
			  FROM 
				tennis_reservation_court
				inner join tennis_court on tennis_reservation_court.court_id = tennis_court.id
				left outer join tennis_reservation on tennis_reservation_court.reservation_id = tennis_reservation.id
			  WHERE 
			   tennis_reservation_court.time_out>='.Date_Time::to_time($date).'
			   AND tennis_reservation_court.time_out < '.(Date_Time::to_time($date)+(24*3600)).'
		';
		if($item = DB::fetch($sql)){
			$this->map['total_revenue'] += $item['id'];
			$this->map['tennis_revenue'] = System::display_number($item['id']);
		}
		$this->map['swimming_pool_revenue'] = 0;
		$sql = '
			SELECT 
				 sum(swimming_pool_reservation_pool.total_amount) as id
			  FROM 
				swimming_pool_reservation_pool
				inner join swimming_pool on swimming_pool_reservation_pool.swimming_pool_id = swimming_pool.id
				left outer join swimming_pool_reservation on swimming_pool_reservation_pool.reservation_id = swimming_pool_reservation.id
			  WHERE 
			 	swimming_pool_reservation_pool.time_out>='.Date_Time::to_time($date).'
			   	AND swimming_pool_reservation_pool.time_out < '.(Date_Time::to_time($date)+(24*3600)).'
		';			
		if($item = DB::fetch($sql)){
			$this->map['total_revenue'] += $item['id'];
			$this->map['swimming_pool_revenue'] = System::display_number($item['id']);
		}
		$sql = 	'	
			select 
					sum(extra_service_invoice_detail.quantity*extra_service_invoice_detail.price) as id
				from 
					extra_service_invoice_detail
				where 
					extra_service_invoice_detail.in_date = \''.Date_Time::to_orc_date($date).'\'
					AND extra_service_invoice_detail.used = 1
		';
		$this->map['extra_service_revenue'] = 0;
		if($item = DB::fetch($sql)){
			$this->map['total_revenue'] += $item['id'];
			$this->map['extra_service_revenue'] = System::display_number($item['id']);
		}
		/*$sql = '
			select 
				sum(RESERVATION_ROOM_SERVICE.AMOUNT) as id
			from 
				RESERVATION_ROOM_SERVICE
				inner join reservation_room on reservation_room.id=RESERVATION_ROOM_SERVICE.RESERVATION_ROOM_ID
				inner join reservation on reservation.id=reservation_room.reservation_id
			where 
		';*/
		$this->map['total_revenue'] = System::display_number($this->map['total_revenue']);
		$this->parse_layout('summary_report',$this->map);
	}
}
?>