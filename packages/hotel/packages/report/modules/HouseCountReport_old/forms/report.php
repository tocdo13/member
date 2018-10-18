<?php
class HouseCountReportForm extends Form
{
	function HouseCountReportForm()
	{
		Form::Form('HouseCountReportForm');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css('packages/core/skins/default/css/jquery/datepicker.css');
	}
	function draw()
	{
		require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';
		$start_date = Url::get('date')?Url::get('date'):date('d/m/Y');
		$room_count = DB::fetch('select count(*) as acount from room inner join room_level on room_level.id = room.room_level_id where (room_level.is_virtual is null or room_level.is_virtual <> 1) AND room.portal_id = \''.PORTAL_ID.'\'','acount');		
		$date = @explode('/',$start_date);
		if(!checkdate($date[1],$date[0],$date[2]))
		{
			$start_date = date('d/m/Y');
			$end_date =  date('d/m/Y',(time()+7*24*60*60));
		}
		else
		{
			$end_date =   date('d/m/Y',mktime(0,0,0,$date[1],$date[0],$date[2])+7*24*60*60);
		}	
		$day = $start_date;
		$start_date = Date_time::to_orc_date($start_date);
		$end_date = Date_time::to_orc_date($end_date);
		$cond = 'reservation.portal_id = \''.PORTAL_ID.'\' and (room_status.in_date>=\''.$start_date.'\' and room_status.in_date<=\''.$end_date.'\')';
		$cond .= 'and ((in_date>=arrival_time and in_date<departure_time) and (room_status.status=\'BOOKED\' or room_status.status=\'OCCUPIED\'))';	
		//echo '<div style="background:#FFFFFF;position:absulate;width:2000px;float:left;height:100px;">'.$cond.'</div>';
		$rooms = array();
		$status = array('OCCUPIED'=>Portal::language('occupied'),'BOOKED'=>Portal::language('booked'));
		foreach($status as $key=>$value){
				$sql = '
					select 
						to_char(room_status.in_date,\'dd/mm/yyyy\') as id,
						room_status.status,
						room_status.room_id
					from 
						room_status 
						inner join reservation_room on reservation_room.id = room_status.reservation_room_id
						inner join reservation on reservation.id = reservation_room.reservation_id
						left outer join room on room.id = reservation_room.room_id
					where 
						'.$cond.' and room_status.status=\''.$key.'\'
					order by 
						room_status.id
				';
			$rooms[$key] = DB::fetch_all($sql);
			foreach($rooms[$key] as $key_=>$value_){	
				if($key=='OCCUPIED'){
					//$extra_cond = ' and room_status.change_price!=0 and room_status.room_id not in (select distinct room_id from room_status where status = \'BOOKED\' and to_char(in_date,\'dd/mm/yyyy\')=\''.$value_['id'].'\')';
					$extra_cond = ' and room_status.change_price!=0';
				}else{
					$extra_cond = '';
				}
				$sql = '
						select 
							count(*) as acount
						from 
							room_status 
							inner join reservation_room on reservation_room.id = room_status.reservation_room_id
							inner join reservation on reservation.id = reservation_room.reservation_id
						where 
							to_char(room_status.in_date,\'dd/mm/yyyy\')=\''.$value_['id'].'\' 
							and ((in_date>=arrival_time and in_date<departure_time) and (room_status.status=\'BOOKED\' or room_status.status=\'OCCUPIED\'))
							AND reservation.portal_id = \''.PORTAL_ID.'\'
							and room_status.status=\''.$key.'\' 
							'.$extra_cond.'
				';
				
				$value_['acount'] = DB::fetch($sql,'acount');
				$rooms[$key][$key_]=$value_;
			}
		}
		$guest = array();
		foreach($status as $key=>$value)
		{
			$arr = array();
			$ext_cond = '';
			if($key=='OCCUPIED')
			{
				$ext_cond =' and room_status.change_price!=0 ';
			}
			for($i=Date_Time::to_time($day);$i<(Date_Time::to_time($day)+7*24*3600);$i+=24*3600)
			{
				$sql = '
					select 
						distinct reservation_room.id 
						,room_status.status 
						,adult 
						,to_char(room_status.in_date,\'dd/mm/yyyy\') as in_date 
					from 
						reservation_room 
						inner join reservation on reservation.id = reservation_room.reservation_id
						inner join room_status on room_status.reservation_room_id = reservation_room.id
					where 
						in_date = \''.date('d-M-Y',$i).'\'
						AND reservation.portal_id = \''.PORTAL_ID.'\'
						and room_status.status=\''.$key.'\' 
						'.$ext_cond.'
					ORDER BY 
						in_date ASC
						';	
				//echo $sql.'<br>';
				$items = DB::fetch_all($sql);	
				foreach($items as $id=>$row)
				{
					if(!isset($arr[$row['in_date']]))
					{				
						$arr[$row['in_date']]['adult'] = intval($row['adult']);
					}
					else
					{
						$arr[$row['in_date']]['adult'] += intval($row['adult']);
					}	
				}	
			}			
			$guest[$key] = $arr;
		}
		$this->parse_layout('report',array(
				'rooms'=>$rooms,
				'day'=>$day,
				'room_count'=>$room_count,
				'guest'=>$guest
			)
		);
	}
}
?>