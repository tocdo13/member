<?php
class TennisDailySummaryReportForm extends Form
{
	function TennisDailySummaryReportForm()
	{
		Form::Form('TennisDailySummaryReportForm');
		$this->link_css('packages/hotel/'.Portal::template('tennis').'/css/style.css');
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
		$courts = DB::select_all('tennis_court',false,'position');
		$sql = 'SELECT tennis_staff.* FROM tennis_staff';
		$staffs = DB::fetch_all($sql);
		$this->map['staff_id_list'] = array(''=>Portal::language('all'))+String::get_list($staffs,'full_name');
		foreach($courts as $key=>$value){
			$sql = '
				SELECT 
					tennis_reservation_court.*,tennis_guest.full_name as guest_name
				FROM
					tennis_reservation_court
					LEFT OUTER JOIN tennis_guest ON tennis_guest.id = tennis_reservation_court.guest_id
					LEFT OUTER JOIN tennis_staff_court ON tennis_staff_court.reservation_court_id = tennis_reservation_court.id					
				WHERE
					tennis_reservation_court.court_id = '.$value['id'].'
					AND tennis_reservation_court.time_in >= '.Date_Time::to_time($_REQUEST['date']).'
					AND tennis_reservation_court.time_in <= '.(Date_Time::to_time($_REQUEST['date'])+24*3600).'
					'.(Url::get('staff_id')?' AND tennis_staff_court.staff_id = '.Url::iget('staff_id').'':'').'
			';
			$reservation_court = DB::fetch_all($sql);
			foreach($reservation_court as $k=>$v){
				$tooltip = $v['guest_name']?Portal::language('guest_name').': '.$v['guest_name']."\n":'';
				$tooltip .= Portal::language('time').': '.date('H:i',$v['time_in']).' - '.date('H:i',$v['time_out'])."\n";
				$tooltip .= '------------------------------'."\n".Portal::language('serve_staff').': '."\n";
				$staffs = DB::fetch_all('SELECT tennis_staff.*,tennis_staff_court.reservation_court_id FROM tennis_staff LEFT OUTER JOIN tennis_staff_court ON tennis_staff_court.staff_id = tennis_staff.id where tennis_staff_court.reservation_court_id = '.$v['id'].'');
				foreach($staffs as $v1){
						$tooltip .=' - '.$v1['full_name']."\n";
				}
				if($v['note']){
					$tooltip .= "------------------------------\n".$v['note']."\n";
				}
				$reservation_court[$k]['tooltip'] = $tooltip;
				$reservation_court[$k]['brief_status'] = ($v['status']=='BOOKED')?'B':(($v['status']=='CHECKIN')?'IN':'OUT');
			}
			$courts[$key]['reservation_court'] = $reservation_court;
		}
		$this->map['courts'] = $courts;
		$begin_hour = 8;
		$end_hour = 23;
		$this->map['begin_time'] = $current_time + 8*3600;
		$this->map['aggregate_duration'] = $aggregate_duration = ($end_hour - $begin_hour) * 60;
		$this->parse_layout('report',$this->map);
	}
}
?>