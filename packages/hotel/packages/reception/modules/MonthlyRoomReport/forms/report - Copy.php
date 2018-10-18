<?php
class MonthlyRoomReportReportForm extends Form
{
	function MonthlyRoomReportReportForm()
	{
		Form::Form('MonthlyRoomReportReportForm');
		$this->link_css(Portal::template('hotel').'/css/room.css');		
		$this->link_css(Portal::template('hotel').'/css/monthly_room_report.css');
	}
	function draw()
	{
		$view_style = array(
			'view_style_list'=>array(
				'name'=>Portal::language('name'),
				'price'=>Portal::language('price')
			)
		);
		$years = array();
		for($i=BEGINNING_YEAR;$i<=(BEGINNING_YEAR+5);$i++){
			$years[$i] = $i;
		}
		$months = array();
		for($i=1;$i<=12;$i++){
			$months[$i] = $i;
		}
		$status = array(
			'CHECKOUT'=>Portal::language('check_out'),
			'CHECKIN'=>Portal::language('check_in'),
			'BOOKED'=>Portal::language('booked'),				
		);
		$date_arr = array('year_list'=>$years,'month_list'=>$months);
		$this->parse_layout('header',$view_style+$date_arr);
		/////////////////////////////////////////////
		$this->map = array();
		$this->map['year'] = $year = URL::get('year', date('Y'));
		$this->map['month'] = $month = URL::get('month', date('m'));
		$this->map['current_day'] = intval(date('d'));
		$this->map['days_in_month'] = cal_days_in_month(CAL_GREGORIAN,$month,$year);
		$this->map['disabled_cell_color'] = '#EFEFEF';
		$exchange_rate = DB::fetch('select exchange from currency where id=\'VND\'','exchange');
		$sql = '
			select
				room_status.id,
				room_status.room_id,
				room.name as room_name,
				room_status.status,
				room_status.house_status,
				room_status.note,
				reservation.note as reservation_note,
				reservation_room.note as reservation_room_note,
				reservation_room.adult,
				room_status.in_date,
				reservation_room.service_rate,
				reservation_room.tax_rate,
				reservation_room.reduce_balance,
				to_char(room_status.in_date,\'DD\') as day,
				room_status.reservation_id,
				reservation_room.status as reservation_status,
				(room_status.change_price/1) as price,
				reservation_room.arrival_time,
				reservation_room.departure_time,
				DATE_TO_UNIX(reservation_room.departure_time) as end_time,
				DATE_TO_UNIX(reservation_room.arrival_time) as start_time,
				DECODE(reservation.customer_id,0,\'\',customer.name) as customer_name,
				to_char(FROM_UNIXTIME(reservation_room.time_in),\'DD\') as start_day,
				to_char(FROM_UNIXTIME(reservation_room.time_out),\'DD\') as end_day,
				reservation_room.time_in,
				reservation_room.time_out,
				tour.name as tour_name,
				reservation.color,
				CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as traveller_name,
				room_level.brief_name as room_level_name,
				room_level.color as room_level_color
			from
				room_status
				inner join room on room.id = room_status.room_id
				inner join room_level on room_level.id = room.room_level_id
				left outer join reservation_room on room_status.reservation_id = reservation_room.reservation_id AND room_status.room_id = reservation_room.room_id
				left outer join traveller on traveller.id = reservation_room.traveller_id
				left outer join reservation on room_status.reservation_id = reservation.id
				left outer join customer on reservation.customer_id = customer.id
				left outer join tour on reservation.tour_id = tour.id
			where
				room.portal_id = \''.PORTAL_ID.'\'
				AND (reservation_room.status<>\'CANCEL\' AND room_status.status<>\'AVAILABLE\' AND room_status.status<>\'CANCEL\') 
				AND room_status.in_date>=\''.Date_Time::to_orc_date('01'.'/'.$month.'/'.$year).'\' 
				AND room_status.in_date<=\''.Date_Time::to_orc_date(cal_days_in_month(CAL_GREGORIAN,$month,$year).'/'.$month.'/'.$year).'\'
			order by
				room_status.room_id,room_status.room_id,reservation_room.arrival_time
		';
		//and (room_status.in_date<>reservation_room.departure_time or reservation_room.departure_time=reservation_room.arrival_time)
		$room_statuses = DB::fetch_all($sql);
	//	System::debug($room_statuses);
		$items = array();
		foreach($room_statuses as $key=>$value){
			$item_key = $value['room_name'];
			if(!isset($items[$item_key])){
				$items[$item_key]['id'] = $item_key;
				$items[$item_key]['room_name'] = $value['room_name'];
				$items[$item_key]['room_id'] = $value['room_id'];
				$items[$item_key]['color'] = $value['color'];
				$items[$item_key]['room_level_name'] = $value['room_level_name'];
				$items[$item_key]['room_level_color'] = $value['room_level_color'];
			}
			for($i=1;$i<=$this->map['days_in_month'];$i++){
				$current_time = str_pad($i,2,'0',STR_PAD_LEFT).'/'. str_pad($this->map['month'],2,'0',STR_PAD_LEFT).'/'.$this->map['year'];
				//if(intval($value['day']) == $i and ((intval($value['end_day']) != $i and $value['start_day'] != $value['end_day']) or $value['start_day'] == $value['end_day'])){
				if($current_time == Date_Time::convert_orc_date_to_date($value['in_date'],'/') and ($value['in_date'] != $value['departure_time'] or $value['arrival_time'] == $value['departure_time'])){
					$title = '';
					$title .= ($value['traveller_name']?'{'.$value['traveller_name'].'} ':'');
					$title .= ($value['customer_name']?'{'.$value['customer_name'].'}':'');
					$title .= ($value['tour_name']?'{'.$value['tour_name'].'}':'');
					$title .= "\n";
					$title .= Portal::language('price').': '.System::display_number($value['price']).' '.HOTEL_CURRENCY."\n";
					$title .= Portal::language('Time_in_out').': '.date('d/m/Y H:i\'',$value['time_in']).' - '.date('d/m/Y H:i\'',$value['time_out']);
					if($value['reservation_status']=='CHECKOUT'){
						$value['status'] = 'CHECKOUT';
					}
					if(Url::get('view_style')=='price'){
						$items[$item_key][$i] = '<a  class="room '.(($value['house_status']=='REPAIR')?$value['house_status']:$value['status']).'" title="'.$title.'" style="background-color:'.$value['color'].'" target="_blank" href="'.Url::build('reservation',array('cmd'=>'edit','id'=>$value['reservation_id'])).'">';
						$items[$item_key][$i] .= System::display_number($value['price']);
						$items[$item_key][$i] .= '</a>';
					}else{
						$items[$item_key][$i] = '<a  class="room '.(($value['house_status']=='REPAIR')?$value['house_status']:$value['status']).'" title="'.$title.'" style="background-color:'.$value['color'].'" target="_blank" href="'.Url::build('reservation',array('cmd'=>'edit','id'=>$value['reservation_id'])).'">';
						$items[$item_key][$i] .= ($value['traveller_name']?'{'.$value['traveller_name'].'} ':'');
						$items[$item_key][$i] .= ($value['customer_name']?'{'.$value['customer_name'].'}':'');
						$items[$item_key][$i] .= ($value['tour_name']?'{'.$value['tour_name'].'}':'');
						$items[$item_key][$i] .= '</a>';
					}
				}
			}
		}
		$rooms = DB::fetch_all('
			select 
				room.*,room_level.brief_name as room_level_name,room_level.color as room_level_color 
			from 
				room inner join room_level on room_level.id = room.room_level_id 
			where 
				room.portal_id = \''.PORTAL_ID.'\' 
			order by 
				room.name DESC');
		foreach($rooms as $key=>$value){
			if(!isset($items[$value['name']])){
				$items[$value['name']]['id'] = $value['name'];
				$items[$value['name']]['room_name'] = $value['name'];
				$items[$value['name']]['room_id'] = $value['id'];
				$items[$value['name']]['reservation_id'] = '';
				$items[$value['name']]['color'] = '';				
				$items[$value['name']]['room_level_name'] = $value['room_level_name'];
				$items[$value['name']]['room_level_color'] = $value['room_level_color'];
				for($i=1;$i<=$this->map['days_in_month'];$i++){
					$items[$value['name']][$i] = '&nbsp;';
				}
			}
		}
		ksort($items);
		$this->map['items'] = $items;
		$this->map['room_levels'] = DB::fetch_all('select id,price,name,color, 0 as remain from room_level where room_level.portal_id = \''.PORTAL_ID.'\' order by name');
		$this->parse_layout('report',$this->map);
	}
}
?>