<?php
class OccupancyChartForm extends Form{
	function OccupancyChartForm(){
		Form::Form('OccupancyChartForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/core/includes/js/jquery/chart/highcharts.js');
		$this->link_js('packages/core/includes/js/jquery/chart/excanvas.compiled.js');
	}
	function draw(){
		$this->map = array();
		require_once 'packages/core/includes/utils/lib/report.php';
		$total = array();
		$total_service_others = array();
		$items = array();
		$dautuan = $this->get_beginning_date_of_week();
		$date_from = Url::get('from_date')?Date_Time::to_orc_date(Url::get('from_date')):$this->get_beginning_date_of_week();
		$date_to = Url::get('to_date')?Date_Time::to_orc_date(Url::get('to_date')):$this->get_end_date_of_week();
		$this->map['from_date'] = Date_Time::convert_orc_date_to_date($date_from,'/');
		$this->map['to_date'] = Date_Time::convert_orc_date_to_date($date_to,'/');
		$time_from = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_from,'/'));
		$time_to = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_to,'/')) + (24*3600);
		//--------------------------Cong suat phong---------------------------------------------------//
		$total_rooms = $this->get_total_room();//Tong so phong
		$this->map['total'] = $total_rooms;
		//-----------------------------------------Tính doanh thu phòng--------------------------------//
		$total_occupancy = $this->get_total_quantity($date_from,$date_to);
		$room_amounts = $this->total_amount_room_occ($date_from,$date_to);
		$total_in_day = $this->get_amount_occ_in_day($date_from,$date_to);
		$total_room_in_day = $this->get_total_in_day($date_from,$date_to);
		//System::debug($total_occupancy);
		for($i=$time_from; $i< $time_to;$i+=24*3600){
			$items[$i]['id'] = $i;
			$items[$i]['in_date'] = date('d',$i);
			$items[$i]['room_amount'] = 0;
			$items[$i]['total_BOOKED'] = 0;
			$items[$i]['total_OCCUPIED'] = 0;
		}
		foreach($room_amounts as $id =>$room_amount){
			$in_time = Date_Time::to_time(Date_Time::convert_orc_date_to_date($room_amount['in_date'],'/'));
			if(isset($in_time) && $in_time == $items[$in_time]['id']){
				$items[$in_time]['room_amount'] +=  $room_amount['room_total'];	
			}
		}
		foreach($total_in_day as $id => $in_day){
			$time_dep = Date_Time::to_time(Date_Time::convert_orc_date_to_date($in_day['departure_time'],'/'));
			if(isset($time_dep) && $time_dep == $items[$time_dep]['id']){
				$items[$time_dep]['room_amount'] += $in_day['amount'];	
			}
		}
		foreach($total_occupancy as $id => $occupancy){
			$in_date = Date_Time::to_time(Date_Time::convert_orc_date_to_date($occupancy['in_date'],'/'));
			if(isset($items[$in_date]['id']) && $items[$in_date]['id'] = $in_date){
				if($occupancy['status'] == 'OCCUPIED'){
				  	$items[$in_date]['total_OCCUPIED'] = $occupancy['total_room'];	
				}
				elseif($occupancy['status'] == 'BOOKED'){
				  	$items[$in_date]['total_BOOKED'] = $occupancy['total_room'];	
				}
			}
		}	
		foreach($total_room_in_day as $id => $total_in){
			$time_day = Date_Time::to_time(Date_Time::convert_orc_date_to_date($total_in['departure_time'],'/'));
			if(isset($time_day) && $time_day == $items[$time_day]['id']){
				if($total_in['status'] == 'OCCUPIED'){
				  	$items[$time_day]['total_OCCUPIED'] += $total_in['total_room'];	
				}
				elseif($total_in['status'] == 'BOOKED'){
				  	$items[$time_day]['total_BOOKED'] += $total_in['total_room'];	
				};	
			}
		}
		foreach($items as $id=> $item){
			$items[$id]['room_amount'] = round($item['room_amount']/1000);
			$items[$id]['room_amount'] = System::display_number($items[$id]['room_amount']);
		}	
		$view_all = true;
		$users = DB::fetch_all('
			SELECT
				party.user_id as id,party.user_id as name
			FROM
				party
				INNER JOIN account ON party.user_id = account.id
			WHERE
				party.type=\'USER\'
				AND account.is_active = 1
		');		
		$this->map['view_all'] = $view_all;
		$this->parse_layout('report',$this->map +array('items'=>String::array2js($items)));		
	}
	function get_total_quantity($date_from,$date_to){
		$sql = 'SELECT count(*) as total_room,rs.status,rs.in_date,concat(rs.in_date,rs.status) as id
				FROM room_status rs
					INNER JOIN reservation_room rr ON rr.id = rs.reservation_room_id 
							INNER JOIN reservation ON reservation.id = rs.reservation_id
				WHERE 
							rs.in_date >= \''.$date_from.'\' AND rs.in_date <= \''.$date_to.'\'
							AND rs.in_date < rr.departure_time
							AND (rs.status = \'OCCUPIED\' OR rs.status = \'BOOKED\')
			    GROUP BY rs.in_date,rs.status';	
		$total_room_quantity = DB::fetch_all($sql);
		return $total_room_quantity;
	}
	function get_total_in_day($date_from,$date_to){
		$sql = 'SELECT count(*) as total_room,rs.status,rr.departure_time,concat(rr.departure_time,rs.status) as id
				FROM room_status rs
					INNER JOIN reservation_room rr ON rr.id = rs.reservation_room_id 
							INNER JOIN reservation ON reservation.id = rs.reservation_id
				WHERE 
							rs.in_date >= \''.$date_from.'\' AND rs.in_date <= \''.$date_to.'\'
							AND rr.departure_time = rr.arrival_time
							AND (rs.status =\'BOOKED\' OR rs.status = \'OCCUPIED\')
			    GROUP BY rr.departure_time,rs.status';	
		$total_total_in_day = DB::fetch_all($sql);
		return $total_total_in_day;
	}
	function total_amount_room_occ($date_from,$date_to){
			$sql = 'SELECT 
					sum(rs.change_price) as room_total,rs.in_date,rs.id as id, rs.reservation_room_id as rr_id
				FROM
					room_status rs
					INNER JOIN reservation_room rr ON rr.id = rs.reservation_room_id 
				WHERE 
					rs.status = \'OCCUPIED\'
					AND rs.in_date >= \''.$date_from.'\' AND rs.in_date <=  \''.$date_to.'\'
					AND rs.in_date < rr.departure_time
				GROUP BY 
					rs.in_date,rs.id,rs.reservation_room_id';
			$amount_rooms = DB::fetch_all($sql);
			return $amount_rooms;
	}
	function total_amount_room_booked($date_from,$date_to){
			$sql = 'SELECT 
					sum(rs.change_price) as room_total,rr.time_out,concat(rr.id,rr.time_out) as id
				FROM
					room_status rs
					INNER JOIN reservation_room rr ON rr.id = rs.reservation_room_id 
				WHERE 
					rs.status = \'BOOKED\'
					AND rs.in_date >= \''.$date_from.'\' AND rs.in_date <=  \''.$date_to.'\'
					AND rs.in_date < rr.departure_time
				GROUP BY 
					rs.in_date,rs.id,rs.reservation_room_id';
			$amount_rooms = DB::fetch_all($sql);
			return $amount_rooms;
	}
	function get_amount_occ_in_day($date_from,$date_to){
			$sql='SELECT rr.price as amount,rr.time_out,rr.id,rr.departure_time
					FROM reservation_room rr
						INNER JOIN room_status rs ON rs.reservation_room_id = rr.id
					WHERE
						rr.status =  \'CHECKIN\'
						AND rs.in_date >= \''.$date_from.'\' AND rs.in_date <=  \''.$date_to.'\'
						AND rr.arrival_time = rr.departure_time';
			$total_in_day = DB::fetch_all($sql);
			return $total_in_day;
	}
	function get_beginning_date_of_week(){
		$today = date('d/m/Y');
		$time_today = Date_Time::to_time($today);
		$day_of_week = date('w',$time_today);
		$day_begin_of_week = $time_today  - (24 * 3600 * $day_of_week);
		return (Date_Time::to_orc_date(date('d/m/Y',$day_begin_of_week)));
	}
	function get_end_date_of_week(){
		$today = date('d/m/Y');
		$time_today = Date_Time::to_time($today);
		$day_of_week = date('w',$time_today);
		$end_of_week = $time_today + (24 * 3600 * (6 - $day_of_week));
		return (Date_Time::to_orc_date(date('d/m/Y',$end_of_week)));
	}
	function get_total_room(){
		$sql = 'SELECT count(*) as total_rooms
				FROM room
				';
		$total_rooms = DB::fetch($sql,'total_rooms');
		return $total_rooms;
	}
}
?>