<?php
/*
	Class: kiem tra phong trong tu dong
	Written by: khoand
	Date: 20/01/2011
*/
class CheckAvailabilityForm extends Form
{
	function CheckAvailabilityForm()
	{
		Form::Form('CheckAvailabilityForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css('packages/hotel/'.Portal::template('reception').'/css/style.css');
		$this->link_css(Portal::template('hotel').'/css/style.css');
	}
	function on_submit(){
		$room_levels = '';
		$i = 0;
		$current_room_quantity = 0;
		foreach($_REQUEST as $key=>$value){
			if(is_string($key) and preg_match_all("/room_quantity_([0-9]*)/",$key,$match)){
				System::Debug($match);
				$temp = array();
				if(isset($match[1][0])){
					echo 1;
					$room_level_id = $match[1][0];
					if($room_levels){
						$room_levels .= '|'.$room_level_id.','.$value.',0';
					}else{
						$room_levels .= $room_level_id.','.$value.',0';
					}
				}
			}
		}
		if($room_levels){
			$status = 'BOOKED';
			Url::redirect_current(array('cmd'=>'add','room_levels'=>$room_levels,'arrival_time','departure_time','status'=>$status));
		}else{
			$this->error('room_level','has_no_room_level_was_selected');
		}
	}
	function draw()
	{
		$this->map = array();
		require_once 'packages/hotel/packages/reception/modules/includes/lib.php';
		if(Url::get('time_in')){
			$time_in = Url::get('time_in');
		}else{
			$time_in = CHECK_IN_TIME;
		}
		if(Url::get('time_out')){
			$time_out = Url::get('time_out');
		}else{
			$time_out = CHECK_OUT_TIME;
		}
		$arr = explode(':',$time_in);
		$arrival_time = Date_Time::to_time(Url::get('arrival_time')); 
		$departure_time = Date_Time::to_time(Url::get('departure_time'));
		////////////////Get days/////////////////////////////////////////////////////
		$days = array();
		for($i = $arrival_time;$i <= $departure_time;$i = $i + 24*3600){
			$days[$i]['id'] = $i;
			$days[$i]['value'] = date('d/m',$i);
		}
		$this->map['days'] = $days;
		////////////////Cong them khoan gio, phut////////////////////////////////////
		$arrival_time = $arrival_time + intval($arr[0])*3600+intval($arr[1])*60;
		$arr = explode(':',$time_out);
		$departure_time= $departure_time + intval($arr[0])*3600+intval($arr[1])*60;
		/////////////////////////////////////////////////////////////////////////////
		$room_levels = DB::fetch_all('
			SELECT
				rl.id,rl.name,rl.price,0 AS min_room_quantity,
				(SELECT COUNT(*) FROM room WHERE room_level_id = rl.id) room_quantity
			FROM	
				room_level rl
			WHERE
				rl.portal_id = \''.PORTAL_ID.'\' AND rl.is_virtual IS NULL OR rl.is_virtual = 0
				
		');
		$sql = '
			SELECT 
				rs.id,rr.status,rr.time_in,rr.time_out,rr.arrival_time,rr.departure_time,rs.in_date,rr.room_level_id
			FROM
				room_status rs
				INNER JOIN reservation_room rr ON rs.reservation_room_id = rr.id
				INNER JOIN reservation r ON rr.reservation_id = r.id
			WHERE
				r.portal_id = \''.PORTAL_ID.'\' 
			ORDER BY
				rr.room_level_id
		';
		
		$room_status = DB::fetch_all($sql);
		foreach($room_levels as $key=>$value){
			$min = 1000;
			foreach($days as $k=>$v){
				$room_quantity = $value['room_quantity'];
				foreach($room_status as $kk=>$vv){
					if($vv['room_level_id'] == $key and  Date_Time::convert_orc_date_to_date($vv['in_date'],'/') == date('d/m/Y',$k) and $vv['departure_time'] != $vv['in_date']){
						$room_quantity -= 1;
					}
				}
				$room_levels[$key]['day_items'][$k]['id'] = $k;
				$room_levels[$key]['day_items'][$k]['room_quantity'] = $room_quantity;
				if($min > $room_quantity){
					$min = $room_quantity;
				}
			}
			$room_levels[$key]['min_room_quantity'] = $min;
		}
		$this->map['room_levels'] = $room_levels;
		if(Url::get('room_level_id')){
			$this->map['room_level_name'] = DB::fetch('select name from room_level where id = '.Url::iget('room_level_id').'','name');
		}else{
			$this->map['room_level_name'] = '';
		}
		/////////////////////////////////////////////////////////////////////////////
		$this->map['arrival_time'] = date('d/m/Y H:i\'',$arrival_time);
		$this->map['departure_time'] = date('d/m/Y H:i\'',$departure_time);
		/////////////////////////////////////////////////////////////////////////////
		$this->parse_layout('check_availability',$this->map);
	}
}
?>