<?php
class HouseStatusReportForm extends Form
{
	function HouseStatusReportForm()
	{
		Form::Form('HouseStatusReportForm');
		$this->link_js("packages/core/includes/js/jquery/datepicker.js");
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_css("skins/default/report.css");
	}
	function draw()
	{
		require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';
		$start_date = '01/'.date('m/Y',time());
		$start_time = Date_Time::to_time($start_date);
		if(!isset($_REQUEST['from_date'])){
			$_REQUEST['from_date'] = $start_date;
		}else{
			$start_date = Url::get('from_date');
			$start_time = Date_Time::to_time($start_date);
		}
		$end_time = $start_time + 7*24*3600;
		$end_date = date('d/m/Y',$end_time);
		if(!isset($_REQUEST['to_date'])){
			$_REQUEST['to_date'] = $end_date;
		}else{
			$end_date = Url::get('to_date');
			$end_time = Date_Time::to_time($end_date);
		}
		$portal_cond = ' AND account.id = \''.PORTAL_ID.'\'';
		if(Url::get('portal_id')){
			$portal_cond = ' AND (';
			$arr = Url::get('portal_id');
			$i=0;
			foreach($arr as $value){
				if($i>0){
					$portal_cond .= ' OR ';	
				}
				$i++;
				$portal_cond .= 'account.id = \''.$value.'\'';
			}
			$portal_cond .= ')';
		}
		$selected_portals = Portal::get_all_portal($portal_cond);
		$room_levels = DB::fetch_all('
			select 
				room_level.id,room_level.brief_name as name,room_level.portal_id
			from 
				room_level 
			where 
				(room_level.is_virtual is null or room_level.is_virtual <> 1)
			order by
				room_level.brief_name
		');
		$portal_cond = 'reservation.portal_id = \''.PORTAL_ID.'\'';
		if(Url::get('portal_id')){
			$portal_cond = '(';
			$arr = Url::get('portal_id');
			$i=0;
			foreach($arr as $value){
				if($i>0){
					$portal_cond .= ' OR ';	
				}
				$i++;
				$portal_cond .= 'reservation.portal_id = \''.$value.'\'';
			}
			$portal_cond .= ')';
		}
		$sql = 'select 
					CONCAT(to_char(room_status.in_date,\'dd/mm/yyyy\'),CONCAT(\'_\',reservation_room.room_level_id)) as id,reservation_room.room_level_id,
					count(*) as acount
				from 
					room_status 
					inner join reservation_room on reservation_room.id = room_status.reservation_room_id
					inner join reservation on reservation.id = reservation_room.reservation_id
					inner join room_level on room_level.id = reservation_room.room_level_id
				where 
					1=1
					and (room_level.is_virtual is null or room_level.is_virtual <> 1)
					and reservation_room.status<>\'CANCEL\'
					and room_status.in_date >= \''.Date_time::to_orc_date(Url::get('from_date')).'\'
					and room_status.in_date <= \''.Date_time::to_orc_date(Url::get('to_date')).'\' 
					and (in_date>=arrival_time and in_date<departure_time)
				GROUP BY
				
					reservation.portal_id,room_status.in_date,reservation_room.room_level_id
				ORDER BY
					reservation_room.room_level_id';//and ((in_date>=arrival_time and in_date<departure_time))
		$items = DB::fetch_all($sql);
		foreach($selected_portals as $kk=>$vv){
			$dates = array();
			$dates[32]['id'] = '32';
			$dates[32]['name'] = '<strong>'.Portal::language('date').'</strong>';
			$dates[32]['color'] = '#FFFFFF';
			$dates[32]['total'] = '<strong>'.Portal::language('total').'</strong>';
			foreach($room_levels as $key=>$value){
				if($value['portal_id'] == $kk){
					$dates[32]['room_levels'][$key]['id'] = $key;
					$dates[32]['room_levels'][$key]['value'] = '<strong>'.$value['name'].'</strong>';
				}
			}
			for($d = $start_time;$d <= $end_time;$d = $d + (24*3600)){
				$dates[$d]['id'] = $d;
				$dates[$d]['name'] = date('d/m',$d);
				if(date('w',$d)==0){
					$dates[$d]['color'] = '#DDDDDD';
				}elseif(date('w',$d)==6){
					$dates[$d]['color'] = '#EFEFEF';
				}else{
					$dates[$d]['color'] = '#FFFFFF';
				}
				$dates[$d]['total'] = 0;
				foreach($room_levels as $k=>$v){
					if($v['portal_id'] == $kk){
						$total_room_quantity = DB::fetch('select count(*) as acount from room inner join room_level on room_level.id = room_level_id where room_level.id = '.$v['id'].' and room_level.portal_id = \''.$kk.'\'','acount');	
						if(isset($items[date('d/m/Y',$d).'_'.$k])){
							$dates[$d]['room_levels'][$k]['id'] = $k;
							$dates[$d]['room_levels'][$k]['value'] = $total_room_quantity - $items[date('d/m/Y',$d).'_'.$k]['acount'];	
							$dates[$d]['total'] += $total_room_quantity - $items[date('d/m/Y',$d).'_'.$k]['acount'];
						}else{
							$dates[$d]['room_levels'][$k]['id'] = $k;
							$dates[$d]['room_levels'][$k]['value'] = $total_room_quantity;
							$dates[$d]['total'] += $total_room_quantity;
						}
					}
				}
			}
			$selected_portals[$kk]['dates'] = $dates;
		}
		$this->map['selected_portals'] = $selected_portals;
		$portals = Portal::get_portal_list();
		$this->map['portal_options'] = '';
		foreach($portals as $key=>$value){
			$this->map['portal_options'] .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';	
		}
		$this->parse_layout('report',$this->map);
	}
}
?>