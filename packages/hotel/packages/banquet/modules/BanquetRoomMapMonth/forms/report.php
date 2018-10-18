<?php
class BanquetRoomMapMonthReportForm extends Form
{
	function BanquetRoomMapMonthReportForm()
	{
		Form::Form('BanquetRoomMapMonthReportForm');
		$this->link_css('packages/hotel/skins/default/css/banquet.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw()
	{
		$this->map = array();
		$day_in_month = Url::get('month')?Date_Time::day_of_month(Url::get('month'),Url::get('year')):Date_Time::day_of_month(date('m'),date('Y'));
		$start_time = Url::get('month')?Date_Time::to_time('01/'.Url::get('month').'/'.Url::get('year')):Date_Time::to_time('01'.date('/m/Y'));
		$end_time = Url::get('month')?Date_Time::to_time($day_in_month.'/'.Url::get('month').'/'.Url::get('year')):Date_Time::to_time($day_in_month.'/'.date('m/Y'));
        //$end_time = Url::get('month')?Date_Time::to_time('08/'.Url::get('month').'/'.Url::get('year')):Date_Time::to_time('08/'.date('m/Y'));
        $n_time = Url::get('month')?Date_Time::to_time(Url::get('day').'/'.Url::get('month').'/'.Url::get('year')):Date_Time::to_time(date('d/m/Y'));
		$rooms = DB::select_all('party_room',false,'num,group_name,name');
        //System::debug($rooms);
		$room_status = array();
        $day_status = array();
		$last = false;
		$d = $start_time;
		$day = array();
		while($d<=$end_time+24*3600)
        {
			$day_status[date('d',$d)]['time'] = Date_Time::convert_time_to_ora_date($d);
            $day_status[date('d',$d)]['time2'] = date('D',$d);
            if(date('H',$d)=='00')
			{
				$day[date('d',$d)]['id'] = date('d',$d);             
				$id = 'morning_'.date('d',$d);
			}
			else
			{
				$id = 'afternoon_'.date('d',$d);
			}
			$room_status[date('H_d',$d)]['id'] = $id;
			$room_status[date('H_d',$d)]['time'] = $d;
			$room_status[date('H_d',$d)]['day'] = date('d',$d);
            $room_status[date('H_d',$d)]['D'] = date('D',$d);
			
			foreach($rooms as $key=>$value)
			{
				if($last!=$value['group_name'])
				{
					$last = $value['group_name'];
					$floors[$value['group_name']]=
						array(
							'name'=>$value['group_name'],
							'rooms'=>array()
						);
				}
				$sql = '
					SELECT 
						party_reservation_room.*,
						party_reservation.checkin_time as time_in,
						party_reservation.checkout_time as time_out,
						party_reservation.status,
						party_reservation.id as party_reservation_id,
						party_reservation.full_name,
                        party_reservation.time_type,
						party_room.group_name,
						party_room.name as room_name,
						party_type.name as party_name
					FROM
						party_reservation_room
						INNER JOIN party_reservation ON party_reservation.id = party_reservation_room.party_reservation_id
						INNER JOIN party_room on party_room.id = party_reservation_room.party_room_id
						LEFT OUTER JOIN party_type on party_type.id = party_reservation.party_type
					WHERE
						party_reservation_room.party_room_id = '.$value['id'].'
						AND ((party_reservation.checkin_time >= '.$d.'
						AND party_reservation.checkin_time < '.($d+12*3600).')) AND party_reservation.status<>\'CANCEL\'
					ORDER BY
						party_reservation.checkin_time,party_room.group_name
				';
				$reservation_room[date('H_d',$d)] = DB::fetch($sql);		
                $tooltip = '';
				//$room_status[date('H_d',$d)]['rooms'][$key]['id'] = $key;
				if($reservation_room[date('H_d',$d)])
				{
					if($reservation_room[date('H_d',$d)]['status']=='BOOKED')
					{
						$reservation_room[date('H_d',$d)]['brief_status'] = 'B';
					}
					elseif($reservation_room[date('H_d',$d)]['status']=='CHECKIN')
					{
						$reservation_room[date('H_d',$d)]['brief_status'] = 'IN';
					}
					elseif($reservation_room[date('H_d',$d)]['status']=='CHECKOUT')
					{
						$reservation_room[date('H_d',$d)]['brief_status'] = 'O';
					}
					else
					{
						$reservation_room[date('H_d',$d)]['brief_status'] = 'C';
					}
					$room_status[date('H_d',$d)]['rooms'][$key] = $reservation_room[date('H_d',$d)];
                    if($reservation_room[date('H_d',$d)]['time_type'] =='DAY')
                    {
                        $reservation_room[date('H_d',($d+12*3600))] = $reservation_room[date('H_d',$d)];
                        $room_status[date('H_d',($d+12*3600))]['rooms'][$key.'_'] = $reservation_room[date('H_d',($d+12*3600))];
                    }
                    
				}
				else
				{
					$room_status[date('H_d',$d)]['rooms'][$key] = ' ';
				}
				$rooms[$key]['reservation_room'] = $reservation_room;
				$floors[$last]['rooms'][$key] = $value;
               // System::debug($floors);
			}
            $d+= 12*3600;
		}
        //System::debug($reservation_room);
        //System::debug($room_status);
        //System::debug($room_status);
		$begin_hour = 6;
		$end_hour = 24;
		$this->map['room_status'] = $room_status;
        $this->map['day_status'] = $day_status;
		$this->map['days'] = $day;
		$this->map['floors'] = $floors;
		$this->map['aggregate_duration'] = $aggregate_duration = ($end_hour - $begin_hour) * 60;
		$month = array();
		for($i=1;$i<=12;$i++)
		{
			$month[$i] = $i;
		}
		$this->map['month_list'] = $month;
		$this->map['month'] = Url::get('month')?Url::get('month'):date('m');

		$year_arr = array();
		for($year=date('Y')+5;$year>=1900;$year--)
		{
			$year_arr[$year] = $year;
		}
		$this->map['year_list'] = $year_arr;
		$this->map['year'] =  Url::get('year')?Url::get('year'):date('Y');
        //System::debug($this->map);
		$this->parse_layout('report',$this->map);
	}
}
?>