<?php
class RoomFocastForm extends Form
{
	function RoomFocastForm()
	{
		Form::Form('RoomFocastForm');
		$this->link_js("packages/core/includes/js/jquery/datepicker.js");
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_css("skins/default/report.css");
	}
	function draw()
	{
		require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';
		$time = time();
		$start_date = Date_time::to_orc_date(date('d/m/Y',$time));
		if(Url::get('from_date') and Url::get('from_date')!="")
		{
			$start_date = Date_time::to_orc_date(Url::get('from_date'));
		}
		$end_date = Date_time::to_orc_date(date('d/m/Y',strtotime(date('m/d/Y',$time))+31*24*3600));
		if(Url::get('to_date') and Url::get('to_date')!="")
		{
			$end_date = Date_time::to_orc_date(Url::get('to_date'));
		}
		$room_count = DB::fetch('select count(*) as acount from room where room.portal_id = \''.PORTAL_ID.'\'','acount');		
		$repair_rooms = DB::fetch_all('select to_char(in_date,\'yyyy-mm-dd\') as id,count(*) as acount from room_status where status=\'REPAIR\' and in_date>\''.$start_date.'\' and in_date <\''.$end_date.'\' group by in_date');		
		$sql = 'select 
					count(*) as acount
					,to_char(in_date,\'yyyy-mm-dd\') as in_date
					,room_status.id 
					,room_status.status
					,room_status.change_price
				from 
					room_status 
					inner join room on room.id = room_status.room_id
					inner join room_level on room_level.id = room.room_level_id					
					inner join reservation_room on reservation_room.id = room_status.reservation_room_id and reservation_room.room_id = room_status.room_id
				where 
					room.portal_id = \''.PORTAL_ID.'\'
					and (room_level.is_virtual is null or room_level.is_virtual <> 1)
					and reservation_room.status<>\'CANCEL\'
					and in_date>=\''.$start_date.'\'
					and in_date<=\''.$end_date.'\' 
					and ((in_date>=arrival_time and in_date<departure_time) and (room_status.status=\'BOOKED\' or room_status.status=\'OCCUPIED\')) 
				group by
					in_date
					,room_status.id 
					,room_status.status
					,room_status.change_price	
				order by 
					in_date ASC';	
		$items = DB::fetch_all($sql);			
		$rsolds = array();
		foreach($items as $key=>$value)
		{
			if(!isset($rsolds[$value['in_date']]))
			{
				$rsolds[$value['in_date']] = array('id'=>$value['in_date'],'acount'=>0);
			}
			if(!($value['status']=='OCCUPIED' and $value['change_price']==0))
			{			
				$rsolds[$value['in_date']]['acount'] ++;
			}
		}
		$time = strtotime($start_date);
		$month = false;
		while($time<=strtotime($end_date))
		{
			if($month!=date('M-y',$time))
			{
				$month = date('M-y',$time);
				$months[$month] = array(
					'name'=>$month,
					'total'=>0,
					'rsold'=>0,
					'percent'=>0,
					'count'=>0,
					'days'=>array()
				);
			}
			$months[$month]['days'][date('d',$time)] = array(
				'week_day'=>date('D',$time),
				'title_bgcolor'=>(date('D',$time)=='Sun')?'#CCCCCC':'',
				'id'=>date('j',$time),
				'total'=>$room_count-(isset($repair_rooms[date('Y-m-d',$time)])?$repair_rooms[date('Y-m-d',$time)]['acount']:0),
				'rsold'=>isset($rsolds[date('Y-m-d',$time)])?$rsolds[date('Y-m-d',$time)]['acount']:0,
				'percent'=>''
			);
			if($months[$month]['days'][date('d',$time)]['total'])
			{
				$percent = (($months[$month]['days'][date('d',$time)]['rsold']/$months[$month]['days'][date('d',$time)]['total'])*100);
				if($percent>75)
				{
					$months[$month]['days'][date('d',$time)]['bgcolor'] = '#CCCCCC';
				}
				else
				{
					$months[$month]['days'][date('d',$time)]['bgcolor'] = 'white';
				}
				$months[$month]['days'][date('d',$time)]['percent'] = number_format($percent,1);
				$months[$month]['percent'] += $percent;
				$months[$month]['count']++;
				$months[$month]['total'] += $months[$month]['days'][date('d',$time)]['total'];
				$months[$month]['rsold'] += $months[$month]['days'][date('d',$time)]['rsold'];
			}
			$time += 24*3600;
		}
		foreach($months as $id=>$month)
		{
			if($month['count'])
			{
				$months[$id]['percent']=number_format($month['percent']/$month['count'],2);
			}
		}
		$this->parse_layout('report',
			array(
				'months'=>$months
			)
		);
	}
}
?>