<?php
class BanquetRoomMapReportForm extends Form
{
	function BanquetRoomMapReportForm()
	{
		Form::Form('BanquetRoomMapReportForm');
		$this->link_css('packages/hotel/skins/default/css/banquet.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw()
	{
		$this->map = array();
		$current_time = Url::get('date')?Date_Time::to_time(Url::get('date')):Date_Time::to_time(date('d/m/Y',time()));
		if(!isset($_REQUEST['date'])){
			$_REQUEST['date'] = date('d/m/Y');
		}
		$this->map['current_time'] = $current_time;
		$rooms = DB::fetch_all('select ROW_NUMBER() OVER (ORDER BY party_room.id ) as id,
                                party_room.id as party_id,
                                party_room.name,
                                party_room.group_name,
                                party_room.price,
                                party_room.price_half_day  
                                from party_room order by group_name');
		//System::debug($rooms);
        $last = false;
		$i = 1;		
		foreach($rooms as $key=>$value)
        {
			$i++;
			if($last!=$value['group_name'])
			{
				$last = $value['group_name'];
				$floors[$value['group_name']]=
					array(
						'name'=>$value['group_name'],
						'rooms'=>array()
					);
                    //System::debug($floors);
			}
            //System::debug($floors);
			$sql = '
				SELECT 
					party_reservation_room.*,
					party_reservation.checkin_time as time_in,
					party_reservation.checkout_time as time_out,
					party_reservation.status,
					party_reservation.id as party_reservation_id,
					party_reservation.full_name,
					party_room.group_name,
					party_type.name as party_name,
					party_type.id as party_type_id
				FROM
					party_reservation_room
					INNER JOIN party_reservation ON party_reservation.id = party_reservation_room.party_reservation_id
					INNER JOIN party_room on party_room.id = party_reservation_room.party_room_id
					LEFT OUTER JOIN party_type on party_type.id = party_reservation.party_type
				WHERE
					party_reservation_room.party_room_id = '.$value['party_id'].'
					AND party_reservation.checkin_time >= '.Date_Time::to_time($_REQUEST['date']).'
					AND party_reservation.checkin_time <= '.(Date_Time::to_time($_REQUEST['date'])+24*3600).' and party_reservation.status!=\'CANCEL\'
				ORDER BY
					party_room.group_name
			';
			$reservation_room = DB::fetch_all($sql);
            //System::debug($reservation_room);
			$tooltip = '';
			foreach($reservation_room as $k=>$v){
				$tooltip .= $v['party_name']." - ".$v['full_name']."\n";
				$tooltip .= Portal::language('time').': '.date('H:i',$v['time_in']).' - '.date('H:i',$v['time_out'])."\n";
				if($v['note']){
					$tooltip .= "------------------------------\n".$v['party_name']." - ".$v['full_name']."\n";
					$tooltip .= "------------------------------\n".$v['note']."\n";
				}
				$reservation_room[$k]['tooltip'] = $tooltip;
                $tooltip = "";
				$reservation_room[$k]['brief_status'] = ($v['status']=='BOOKED')?'B':(($v['status']=='CHECKIN')?'IN':'OUT');
			}
			$rooms[$key]['reservation_room'] = $reservation_room;
			$floors[$last]['rooms'][$i] = $value;
			$floors[$last]['rooms'][$i]['reservation_room'] = $reservation_room;
		}
		$this->map['floors'] = $floors;
		$begin_hour = 7;
		$end_hour = 23;
		$this->map['begin_time'] = $current_time + 7*3600;
		$this->map['aggregate_duration'] = $aggregate_duration = ($end_hour - $begin_hour) * 60;
		$this->parse_layout('report',$this->map);
	}
}
?>