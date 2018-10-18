<?php
class OccupancyHoldingReportForm extends Form
{
	function OccupancyHoldingReportForm()
	{
		Form::Form('OccupancyHoldingReportForm');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css('packages/core/skins/default/css/jquery/datepicker.css');
	}
	function draw()
	{
		require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';
		$start_date = Url::get('date')?Url::get('date'):date('d/m/Y');
		$sql2= 'SELECT  party.id as party_id, party.name_1,party.user_id as id from party WHERE party.type=\'PORTAL\' AND user_id <>\'#default\'';
		$item_party = DB::fetch_all($sql2);
		$date = @explode('/',$start_date);
		if(!checkdate($date[1],$date[0],$date[2])){
			$start_date = date('d/m/Y');
			$end_date =  date('d/m/Y',(time()+90*24*60*60));
		}else{
			$end_date =  date('d/m/Y',mktime(0,0,0,$date[1],$date[0],$date[2])+90*24*60*60);
		}	
		$day = $start_date;
		$items = array();
		$start_date = Date_time::to_orc_date($start_date);
		$end_date = Date_time::to_orc_date($end_date);
		$items = array();
		$total_rooms = $this->get_total_room();		
		for($i=Date_Time::to_time($day);$i<(Date_Time::to_time($day)+90*24*3600);$i+=24*3600){
			 $items[$i]['id'] = $i;
			$time_date = Date_Time::to_orc_date(date('d/m/y',$i));
			$room_quantity = $this->get_total_quantity($time_date);
			foreach($room_quantity as $id => $room){
					if($room['status'] == 'BOOKED' && $item_party[$room['portal_id']]['id'] == $room['portal_id']){
						$items[$i][$room['portal_id'].'BOOKED']=  $room['total_quantity'];
						$items[$i][$room['portal_id'].'_percent_book'] = round(($room['total_quantity']/$total_rooms[$room['portal_id']]['total_room'])*100,1);  
					}elseif($room['status'] == 'OCCUPIED' && $item_party[$room['portal_id']]['id'] == $room['portal_id']){ 
						$items[$i][$room['portal_id'].'OCCUPIED'] = $room['total_quantity'];
						$items[$i][$room['portal_id'].'_percent_occ'] = round(($room['total_quantity']/$total_rooms[$room['portal_id']]['total_room'])*100,1);
					}
			}
		}
		$this->parse_layout('report',array(
				'item_party'=>$item_party,
				'day'=>$day,
				'items'=>$items
			)
		);
	}
	function get_total_quantity($time_date){
		$sql = 'SELECT count(*) as total_quantity,reservation.portal_id,rs.status, concat(reservation.portal_id,rs.status) as id
				FROM room_status rs
					INNER JOIN reservation_room rr ON rr.id = rs.reservation_room_id 
							INNER JOIN reservation ON reservation.id = rs.reservation_id
							INNER JOIN party ON party.user_id = reservation.portal_id 
				WHERE 
							rs.in_date = \''.$time_date.'\'
							AND (rs.status =\'BOOKED\' OR rs.status = \'OCCUPIED\')
							AND reservation.portal_id <>\'#default\'
							AND rs.in_date >= rr.arrival_time AND rs.in_date < rr.departure_time
			    GROUP BY rs.in_date,reservation.portal_id ,rs.status';	
		$total_room_quantity = DB::fetch_all($sql);
		return $total_room_quantity;
	}
	function get_total_room(){
		$sql = 'SELECT count(*) as total_room, room.portal_id as id 
				FROM room 
					INNER JOIN room_level ON room_level.id = room.room_level_id 
				WHERE room.portal_id <>\'#default\' AND (room_level.is_virtual = 0 OR room_level.is_virtual is null) 
				GROUP BY room.portal_id';
		$total_rooms = DB::fetch_all($sql);
		return $total_rooms;
	}
}
?>