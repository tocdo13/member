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
        /*
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list());
        if(Url::get('portal_id'))
        {
            $portal_id = Url::get('portal_id');
        }
        else
        {
            $portal_id = PORTAL_ID;
            $_REQUEST['portal_id'] = PORTAL_ID;                       
        }
        
        if($portal_id != 'ALL')
        {
            $cond.=' AND reservation.portal_id = \''.$portal_id.'\' '; 
        }
        */
        
		if(Url::get('date')){
			$this->cond = '
				room_status.in_date = \''.Date_Time::to_orc_date(Url::sget('date')).'\'
			';
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
			$this->map['total_revenue'] = 0;
			$this->map['occupied_revenue'] = 0;
			$rooms = DB::fetch_all('SELECT room.id FROM room inner join room_level ON room_level.id = room.room_level_id WHERE room_level.portal_id = \''.PORTAL_ID.'\'');
			$this->map['today_available_rooms'] = sizeof($rooms);
			$sql = '
				SELECT
					room_status.id,reservation_room.status,room_status.status as r_status,
					room_status.house_status,
					reservation_room.arrival_time,reservation_room.departure_time,
					reservation_room.confirm,
					reservation_room.lastest_edited_time,
					room_status.in_date
				FROM
					room_status
					INNER JOIN reservation_room ON reservation_room.id = room_status.reservation_room_id
					INNER JOIN room_level ON room_level.id = reservation_room.room_level_id
					INNER JOIN reservation ON reservation.id = reservation_room.reservation_id
				WHERE
					reservation.portal_id = \''.PORTAL_ID.'\'
					AND room_status.in_date = \''.Date_Time::to_orc_date(Url::sget('date')).'\'

			';
			$items = DB::fetch_all($sql);
			foreach($items as $value){
				if($value['r_status'] == 'OCCUPIED'){
					if($value['house_status']=='DIRTY'){
						$this->map['occupied_rooms_marked_for_dirty']++;
					}
				}
				if($value['r_status'] == 'OCCUPIED' and Date_Time::convert_orc_date_to_date($value['arrival_time'],'/') == Url::sget('date')){
					$this->map['today_check_ins']++;
				}
				if($value['status'] == 'CHECKOUT' and Date_Time::convert_orc_date_to_date($value['departure_time'],'/') == Url::sget('date')){
					$this->map['today_check_outs']++;
					if($value['house_status']=='DIRTY'){
						$this->map['checked_out_rooms_marked_dirty']++;
					}
				}
				if($value['status'] == 'BOOKED' and Date_Time::convert_orc_date_to_date($value['arrival_time'],'/') < Url::sget('date')){
					$this->map['today_no_shows']++;
					$this->map['today_available_rooms']--;
				}
			}
			////////////////////////////////////////////BOOKED////////////////////////////////////////////////////
				$sql = '
					SELECT
						count (*) as total
					FROM
						reservation_room
						INNER JOIN room_level ON room_level.id = reservation_room.room_level_id
						INNER JOIN reservation ON reservation.id = reservation_room.reservation_id
					WHERE
						
						reservation.portal_id = \''.PORTAL_ID.'\'
						AND (room_level.is_virtual is null or room_level.is_virtual = 0)
						AND	(reservation_room.status = \'BOOKED\' OR reservation_room.booked_user_id is not null)
						AND (	reservation_room.time>='.Date_Time::to_time(Url::sget('date')).'
								and reservation_room.time<'.(Date_Time::to_time(Url::sget('date'))+24*3600).')
				';
				$this->map['today_bookeds'] = DB::fetch($sql,'total');
				$this->map['today_available_rooms'] -= $this->map['today_bookeds'];
				////////////////////////////////////////////CANCEL////////////////////////////////////////////////////
				$sql = '
					SELECT
						count (*) as total
					FROM
						reservation_room
						INNER JOIN room_level ON room_level.id = reservation_room.room_level_id
						INNER JOIN reservation ON reservation.id = reservation_room.reservation_id
					WHERE
						
						reservation.portal_id = \''.PORTAL_ID.'\'
						AND (room_level.is_virtual is null or room_level.is_virtual = 0)
						AND	(reservation_room.status = \'CANCEL\')
						AND (	reservation_room.lastest_edited_time>='.Date_Time::to_time(Url::sget('date')).'
								and reservation_room.lastest_edited_time<'.(Date_Time::to_time(Url::sget('date'))+24*3600).')
				';
				$this->map['today_cancellations'] = DB::fetch($sql,'total');
			$sql = '
				SELECT 
					COUNT(room_status.id) AS id
				FROM 
					room_status 
					INNER JOIN reservation ON reservation.id = room_status.reservation_id
				WHERE 
					reservation.portal_id = \''.PORTAL_ID.'\' AND room_status.in_date = \''.Date_Time::to_orc_date(Url::sget('date')).'\' AND house_status=\'REPAIR\'';
			if($item = DB::fetch($sql)){
				$this->map['repairing_rooms'] = $item['id'];
			}
			////////////////////////////////////////////////////
			$sql = '
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
					,reservation_room.reduce_balance
					,reservation_room.reduce_amount
					,to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') as brief_arrival_time
					,to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as brief_departure_time
					,to_char(room_status.in_date,\'DD/MM/YYYY\') as indate
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
					,reservation_room.early_checkin
					,reservation_room.early_arrival_time
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
					reservation.portal_id = \''.PORTAL_ID.'\'
					AND room_status.in_date = \''.Date_Time::to_orc_date(Url::sget('date')).'\'
					AND room_status.status = \'OCCUPIED\'
				order by
					room.name
			';
			$items = DB::fetch_all($sql);
			$total = 0;
			foreach($items as $key=>$value){
				$check = true;
				$indate = Date_Time::to_time($value['indate']);
					$arr_time = Date_time::to_time($value['brief_arrival_time']);
					$drr_time = Date_time::to_time($value['brief_departure_time']);
				if($value['departure_time'] == $value['in_date']){
					if($value['arrival_time'] != $value['departure_time']){
						unset($items[$key]);
						$check = false;
					}
				}
				if($value['verify_dayuse']){
					$verify = $value['verify_dayuse']/10;
					if($value['early_checkin']){
						$sql = '
							SELECT 
								SUM(extra_service_invoice_detail.price*extra_service_invoice_detail.quantity) as total_amount
							FROM 
								extra_service_invoice
								INNER JOIN extra_service_invoice_detail ON extra_service_invoice_detail.invoice_id = extra_service_invoice.id
								INNER JOIN extra_service ON extra_service.id = extra_service_invoice_detail.service_id
								INNER JOIN reservation_room rr ON rr.id = extra_service_invoice.reservation_room_id
							WHERE 
								extra_service.code =  \'C.IN.EARLY\'
								AND extra_service_invoice_detail.used = 1
								AND rr.arrival_time = \''.($value['early_arrival_time']?$value['early_arrival_time']:$value['arrival_time']).'\'
								AND rr.id = '.$value['id'].'
							GROUP BY
								rr.id
						';
						if($value['early_arrival_time']){
								$value['early_arrival_time'] = Date_Time::convert_orc_date_to_date($value['early_arrival_time'],'/');
								$time_arriv = Date_Time::to_time($value['early_arrival_time']);
								if($time_arriv == $indate ){
									$items[$key]['change_price'] = $value['change_price'] + DB::fetch($sql,'total_amount');
									$price = $value['change_price'];
									$this->map['today_ocuppied_rooms'] += $verify;
									$this->map['today_check_ins'] += $verify;
									$this->map['today_available_rooms'] -= $verify;						
								}
						}else{
								if($arr_time == $indate ){
									$items[$key]['change_price'] = $value['change_price'] + DB::fetch($sql,'total_amount');
									$price = $value['change_price'];
									$this->map['today_ocuppied_rooms'] += $verify;
									$this->map['today_check_ins'] += $verify;
									$this->map['today_available_rooms'] -= $verify;	
								}
						}
					}else{	
						$sql = '
							SELECT 
								SUM(extra_service_invoice_detail.price*extra_service_invoice_detail.quantity) as total_amount
							FROM 
								extra_service_invoice
								INNER JOIN extra_service_invoice_detail ON extra_service_invoice_detail.invoice_id = extra_service_invoice.id
								INNER JOIN extra_service ON extra_service.id = extra_service_invoice_detail.service_id
								INNER JOIN reservation_room rr ON rr.id = extra_service_invoice.reservation_room_id
							WHERE 
								extra_service.code =  \'C.O.LATE\'
								AND extra_service_invoice_detail.used = 1
								AND rr.departure_time = \''.$value['departure_time'].'\'
								AND rr.id = '.$value['id'].'
							GROUP BY
								rr.id
						';
						if($value['verify_dayuse'] < 0 ){
							  if($arr_time < $drr_time){
								  $time_before = $drr_time - 24*36000;
								  if($time_before == $indate ){
									 $items[$key]['change_price'] = $value['change_price'] + DB::fetch($sql,'total_amount');
									  $price = $value['change_price'];
									$this->map['today_ocuppied_rooms'] += $verify;
									$this->map['today_available_rooms'] -= $verify;	
								  }
							  }else{
								   if($drr_time == $indate){
									  $items[$key]['change_price'] = $value['change_price'] + DB::fetch($sql,'total_amount');
									   $price = $value['change_price'];
									  $this->map['today_ocuppied_rooms'] += $verify;
										$this->map['today_available_rooms'] -= $verify;	
								   }
							  }
						}else{
							 if($drr_time == $indate){
								$items[$key]['change_price'] = $value['change_price'] + DB::fetch($sql,'total_amount');
								 $price = $value['change_price'];
										   $this->map['today_ocuppied_rooms'] += $verify;
										$this->map['today_available_rooms'] -= $verify;	
							  }
						}
					}
				}
				if($check==true){
					$this->map['today_ocuppied_rooms']++;
					$this->map['today_available_rooms']--;
					$items[$key]['arrival_time'] = date('d/m/Y H:i\'',$value['time_in']);
					$items[$key]['departure_time'] = date('d/m/Y H:i\'',$value['time_out']);
				/*	if($value['verify_dayuse'] and $value['in_date'] == $value['departure_time']){
						$items[$key]['change_price'] = $value['change_price'] = DB::fetch('SELECT amount FROM reservation_room_service WHERE service_id =  7','amount'); //service_id = 7: checkout lately
					}*/
					if($value['arrival_time'] != $value['departure_time'] and $value['departure_time'] != $value['in_date'] and !$value['change_price']){
						DB::update('room_status',array('change_price'=>$value['price']),'id = '.$value['room_status_id'].'');
						if(isset($price)){
							$price += $value['price'];
						}else{
							$price = $value['price'];
						}
					}else{
						$price = $value['change_price'];
					}
					$total += $items[$key]['change_price'];
					$items[$key]['change_price'] = System::display_number($items[$key]['change_price']);
				}
			}
			$this->map['total_revenue'] +=  $total;
			$this->map['occupied_revenue'] +=$total;
			$this->map['total'] = System::display_number($total);
			$this->map['items'] = $items;
			//////////////////////////////////////////revenue/////////////////////////////////////////////////////////////////////////////////////////////
			$this->map['booking_revenue'] = 0;
			$sql = '
				SELECT
					SUM(room_status.change_price) AS id
				FROM
					room_status
					INNER JOIN reservation ON reservation.id = room_status.reservation_id
					INNER JOIN reservation_room ON reservation_room.id = room_status.reservation_room_id
					INNER JOIN room_level ON room_level.id = reservation_room.room_level_id
				WHERE
					reservation.portal_id = \''.PORTAL_ID.'\'
					AND room_status.in_date = \''.Date_Time::to_orc_date(Url::sget('date')).'\'
					AND reservation_room.status = \'BOOKED\'
					AND reservation_room.arrival_time = \''.Date_Time::to_orc_date(Url::sget('date')).'\'
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
					INNER JOIN reservation ON reservation.id = room_status.reservation_id
					INNER JOIN reservation_room ON reservation_room.id = room_status.reservation_room_id
					INNER JOIN room_level ON room_level.id = reservation_room.room_level_id
				WHERE
					reservation.portal_id = \''.PORTAL_ID.'\'
					AND room_status.in_date = \''.Date_Time::to_orc_date(Url::sget('date')).'\'
					AND reservation_room.status = \'BOOKED\'
					AND reservation_room.arrival_time < \''.Date_Time::to_orc_date(Url::sget('date')).'\'
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
					INNER JOIN reservation ON reservation.id = room_status.reservation_id
					INNER JOIN reservation_room ON reservation_room.id = room_status.reservation_room_id
					INNER JOIN room_level ON room_level.id = reservation_room.room_level_id
				WHERE
					reservation.portal_id = \''.PORTAL_ID.'\'
					AND reservation_room.status = \'CANCEL\'
					AND room_status.in_date = \''.Date_Time::to_orc_date(Url::sget('date')).'\'
			';
			if($item = DB::fetch($sql)){
				//$this->map['total_revenue'] += $item['id'];
				$this->map['cancellation_revenue'] = System::display_number($item['id']);
			}
			$this->map['minibar_revenue'] = 0;
			$sql = '
				SELECT
					SUM(housekeeping_invoice.total) AS id
				FROM
					housekeeping_invoice
					INNER JOIN reservation_room ON reservation_room.id = housekeeping_invoice.reservation_room_id
					INNER JOIN reservation ON reservation.id = reservation_room.reservation_id
				WHERE
					reservation.portal_id = \''.PORTAL_ID.'\'
					AND housekeeping_invoice.type = \'MINIBAR\'
					AND housekeeping_invoice.time >= '.Date_Time::to_time(Url::sget('date')).'
					AND housekeeping_invoice.time <= '.(Date_Time::to_time(Url::sget('date'))+24*3600).'
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
					INNER JOIN reservation ON reservation.id = reservation_room.reservation_id
				WHERE
					reservation.portal_id = \''.PORTAL_ID.'\'
					AND housekeeping_invoice.type = \'LAUNDRY\'
					AND housekeeping_invoice.time >= '.Date_Time::to_time(Url::sget('date')).'
					AND housekeeping_invoice.time <= '.(Date_Time::to_time(Url::sget('date'))+24*3600).'
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
					INNER JOIN reservation ON reservation.id = reservation_room.reservation_id
				WHERE
					reservation.portal_id = \''.PORTAL_ID.'\'
					AND housekeeping_invoice.type = \'EQUIP\'
					AND housekeeping_invoice.time >= '.Date_Time::to_time(Url::sget('date')).'
					AND housekeeping_invoice.time <= '.(Date_Time::to_time(Url::sget('date'))+24*3600).'
			';
			if($item = DB::fetch($sql)){
				$this->map['total_revenue'] += $item['id'];
				$this->map['compensation_revenue'] = System::display_number($item['id']);
			}
			$this->map['restaurant_revenue'] = 0;
			$sql = '
				SELECT 
					sum(bar_reservation.total) as id
				FROM 
					bar_reservation
				WHERE 
					 ('.Date_Time::to_time(Url::sget('date')).'<= departure_time AND departure_time <'.(Date_Time::to_time(Url::sget('date'))+(24*3600)).')
					 AND bar_reservation.portal_id = \''.PORTAL_ID.'\'
					 AND bar_reservation.status <> \'CANCEL\'
				';
			if($item = DB::fetch($sql)){
				$this->map['total_revenue'] += $item['id'];
				$this->map['restaurant_revenue'] = System::display_number($item['id']);
			}
			$this->map['karaoke_revenue'] = 0;	
			if(HAVE_KARAOKE){
				$sql = '
					SELECT 
						sum(ka_reservation.total) as id
					  FROM 
						ka_reservation
						inner join ka_reservation_room on ka_reservation_room.ka_reservation_id = ka_reservation.id
						left outer join reservation_room on reservation_room.id=ka_reservation.reservation_room_id
						left outer join reservation ON reservation.id = reservation_room.reservation_id
					  WHERE 
						reservation.portal_id = \''.PORTAL_ID.'\'
						AND ka_reservation.time_out>='.Date_Time::to_time(Url::sget('date')).'
						AND ka_reservation.time_out < '.(Date_Time::to_time(Url::sget('date'))+(24*3600)).'
				';
				if($item = DB::fetch($sql)){
					$this->map['total_revenue'] += $item['id'];
					$this->map['karaoke_revenue'] = System::display_number($item['id']);
				}
			}
			$this->map['massage_revenue'] = 0;
			if(HAVE_MASSAGE){
				$sql = '
					SELECT 
						 sum(massage_product_consumed.price*massage_product_consumed.quantity) as id
					  FROM 
						massage_product_consumed
						inner join massage_room on massage_product_consumed.room_id = massage_room.id
						left outer join reservation_room on massage_product_consumed.hotel_reservation_room_id = reservation_room.id
						left outer join reservation ON reservation.id = reservation_room.reservation_id
					  WHERE 
						reservation.portal_id = \''.PORTAL_ID.'\'	
						AND massage_product_consumed.time_out>='.Date_Time::to_time(Url::sget('date')).'
						AND massage_product_consumed.time_out < '.(Date_Time::to_time(Url::sget('date'))+(24*3600)).'
				';
				if($item = DB::fetch($sql)){
					$this->map['total_revenue'] += $item['id'];
					$this->map['massage_revenue'] = System::display_number($item['id']);
				}
			}
			$this->map['tennis_revenue'] = 0;	
			if(HAVE_TENNIS){
				$sql = '
					SELECT 
						 sum(tennis_reservation_court.total_amount) as id
					  FROM 
						tennis_reservation_court
						inner join tennis_court on tennis_reservation_court.court_id = tennis_court.id
						left outer join tennis_reservation on tennis_reservation_court.reservation_id = tennis_reservation.id
						left outer join reservation_room on tennis_reservation_court.hotel_reservation_room_id = reservation_room.id
						left outer join reservation ON reservation.id = reservation_room.reservation_id
					  WHERE 
					   reservation.portal_id = \''.PORTAL_ID.'\'	
					   AND tennis_reservation_court.time_out>='.Date_Time::to_time(Url::sget('date')).'
					   AND tennis_reservation_court.time_out < '.(Date_Time::to_time(Url::sget('date'))+(24*3600)).'
				';
				if($item = DB::fetch($sql)){
					$this->map['total_revenue'] += $item['id'];
					$this->map['tennis_revenue'] = System::display_number($item['id']);
				}
			}
			$this->map['swimming_pool_revenue'] = 0;
			if(HAVE_SWIMMING){
				$sql = '
					SELECT 
						 sum(swimming_pool_reservation_pool.total_amount) as id
					  FROM 
						swimming_pool_reservation_pool
						inner join swimming_pool on swimming_pool_reservation_pool.swimming_pool_id = swimming_pool.id
						left outer join swimming_pool_reservation on swimming_pool_reservation_pool.reservation_id = swimming_pool_reservation.id
						left outer join reservation_room on swimming_pool_reservation_pool.hotel_reservation_room_id = reservation_room.id
						left outer join reservation ON reservation.id = reservation_room.reservation_id
					  WHERE 
						reservation.portal_id = \''.PORTAL_ID.'\'
						AND swimming_pool_reservation_pool.time_out>='.Date_Time::to_time(Url::sget('date')).'
						AND swimming_pool_reservation_pool.time_out < '.(Date_Time::to_time(Url::sget('date'))+(24*3600)).'
				';			
				if($item = DB::fetch($sql)){
					$this->map['total_revenue'] += $item['id'];
					$this->map['swimming_pool_revenue'] = System::display_number($item['id']);
				}
			}
			$sql = 	'	
				select 
						sum(extra_service_invoice_detail.quantity*extra_service_invoice_detail.price) as id
					from 
						extra_service_invoice_detail
						inner join extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
					where 
						extra_service_invoice.portal_id = \''.PORTAL_ID.'\'
						AND extra_service_invoice_detail.in_date = \''.Date_Time::to_orc_date(Url::sget('date')).'\'
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
		}else{
			$dates = DB::fetch_all('SELECT to_char(in_date,\'DD/MM/YYYY\') AS id,to_char(in_date,\'DD/MM/YYYY\') AS name FROM night_audit WHERE status=\'CHECKED\' AND portal_id = \''.PORTAL_ID.'\' ORDER BY IN_DATE');
			$this->map['date_list'] = String::get_list($dates);
			$this->parse_layout('search',$this->map);
		}
	}
}
?>