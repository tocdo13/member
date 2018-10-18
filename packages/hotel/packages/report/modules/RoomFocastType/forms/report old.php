<?php
class RoomFocastTypeForm extends Form
{
	function RoomFocastTypeForm()
	{
		Form::Form('RoomFocastTypeForm');
		$this->link_js("packages/core/includes/js/jquery/datepicker.js");
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_css("skins/default/report.css");
	}
	function draw()
	{
		require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';
		$start_time = time();
        //$start_time =  Date_Time::to_time(date('d/m/Y'));
		$start_date = Date_time::to_orc_date(date('d/m/Y',$start_time));
		if(Url::get('from_date') and Url::get('from_date')!="")
		{
			$start_time = Date_Time::to_time(Url::get('from_date'));
			$start_date = Date_time::to_orc_date(Url::get('from_date'));
		}
		$end_time = (time()+(10*24*3600));
        //$end_time = Date_Time::to_time(date('d/m/Y'))+86400;
		$end_date = Date_time::to_orc_date(date('d/m/Y',$end_time));
		if(Url::get('to_date') and Url::get('to_date')!="")
		{
			$end_time = Date_Time::to_time(Url::get('to_date'));
			$end_date = Date_time::to_orc_date(Url::get('to_date'));
		}
        echo $start_date.'---';
        echo $end_date;
		$room_level_count = DB::fetch_all('
			select 
				count(*) as acount
				,room_level_id as id 
				,room_level.brief_name as name
			from 
				room 
				inner join room_level on room_level.id = room.room_level_id
			where 
				(room_level.is_virtual is null or room_level.is_virtual <> 1)
			group by 
				room_level_id
				,room_level.brief_name
            order by 
                room_level.brief_name
		');		
		$sql = 'select 
					count(room_status.id) as acount
					,to_char(in_date,\'yyyy-mm-dd\') as in_date
					,reservation_room.room_level_id || \'_\' || to_char(room_status.in_date,\'yyyy-mm-dd\') as id
				from
                    room_status
					inner join reservation_room on reservation_room.id = room_status.reservation_room_id
                    inner join room_level on room_level.id = reservation_room.room_level_id
					left join room on room.id = reservation_room.room_id					
				where 
    			    (reservation_room.status=\'BOOKED\' OR reservation_room.status=\'CHECKIN\')
 					and room_status.in_date>=\''.$start_date.'\'
					and room_status.in_date<=\''.$end_date.'\'
                    and reservation_room.departure_time > room_status.in_date
				group by
					room_status.in_date
					,reservation_room.room_level_id
				ORDER BY
					reservation_room.room_level_id
                    ';	
		$items = DB::fetch_all($sql);
        System::debug($items);
		$months = array();
		$days = array();
		$arr = array();
		foreach($room_level_count as $room_level_id=>$room_level_value)
		{
			$months[$room_level_id] = $room_level_value;
			$months[$room_level_id]['days'] = array();
			$begin = $start_time;
			while($begin<=$end_time)
			{
				$day = date('d/m',$begin);
				$days[$day]['day'] = $day;
				if(date('w',$begin)==0)
				{
					$sun_color = '#F3F3F3';
					$days[$day]['bgcolor'] = $sun_color;
	 				$months[$room_level_id]['days'][$day]['bgcolor'] = $sun_color;
				}
				else
				{
					$normal_color = '#fff';
					$days[$day]['bgcolor'] = $normal_color;
	 				$months[$room_level_id]['days'][$day]['bgcolor'] = $normal_color;
				}	
 				$months[$room_level_id]['days'][$day]['day'] = $day;				
				$sub_id = $room_level_id.'_'.date('Y-m-d',$begin);
				if(isset($items[$sub_id]) and $items[$sub_id]['in_date'] == date('Y-m-d',$begin))
				{
					if(isset($days[$day]['total']))
					{		
						$days[$day]['total'] += $items[$sub_id]['acount'];
					}	
					else
					{
						$days[$day]['total'] = $items[$sub_id]['acount'];
					}
					$months[$room_level_id]['days'][$day]['resold'] = $items[$sub_id]['acount'];
				}
				else
				{
					if(!isset($days[$day]['total']))
					{
						$days[$day]['total'] = 0;
					}
					$months[$room_level_id]['days'][$day]['resold'] = 0;
				}
				$months[$room_level_id]['days'][$day]['total'] = $room_level_value['acount'];
				$begin += (24*3600);
			}
		}	
		//System::debug($months);
		$this->parse_layout('report',
			array(
				'months'=>$months,
				'days'=>$days
			)
		);
	}
}
?>