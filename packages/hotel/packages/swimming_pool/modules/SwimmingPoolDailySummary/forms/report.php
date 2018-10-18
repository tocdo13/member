<?php
class SwimmingPoolDailySummaryReportForm extends Form
{
	function SwimmingPoolDailySummaryReportForm()
	{
		Form::Form('SwimmingPoolDailySummaryReportForm');
		$this->link_css('packages/hotel/'.Portal::template('swimming_pool').'/css/style.css');
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
		$swimming_pools = DB::select_all('swimming_pool',false,'position');
		foreach($swimming_pools as $key=>$value){
			$sql = '
				SELECT
					SUM(swimming_pool_reservation_pool.people_number) as people_number
				FROM
					swimming_pool_reservation_pool
				WHERE
					swimming_pool_reservation_pool.SWIMMING_POOL_ID = '.$value['id'].'
					AND swimming_pool_reservation_pool.time_in >= '.Date_Time::to_time($_REQUEST['date']).'
					AND swimming_pool_reservation_pool.time_in <= '.(Date_Time::to_time($_REQUEST['date'])+24*3600).'
				GROUP BY
					swimming_pool_reservation_pool.SWIMMING_POOL_ID
			';
			$swimming_pools[$key]['people_number'] = DB::fetch($sql,'people_number');
		}
		$sql = 'SELECT swimming_pool_staff.* FROM swimming_pool_staff';
		$staffs = DB::fetch_all($sql);
		$this->map['staff_id_list'] = array(''=>Portal::language('all'))+String::get_list($staffs,'full_name');
		foreach($swimming_pools as $key=>$value){
			$sql = '
				SELECT 
					swimming_pool_reservation_pool.*,swimming_pool_guest.full_name as guest_name
				FROM
					swimming_pool_reservation_pool
					LEFT OUTER JOIN swimming_pool_guest ON swimming_pool_guest.id = swimming_pool_reservation_pool.guest_id
					LEFT OUTER JOIN swimming_pool_staff_pool ON swimming_pool_staff_pool.reservation_pool_id = swimming_pool_reservation_pool.id
				WHERE
					swimming_pool_reservation_pool.swimming_pool_id = '.$value['id'].'
					AND swimming_pool_reservation_pool.time_in >= '.Date_Time::to_time($_REQUEST['date']).'
					AND swimming_pool_reservation_pool.time_in <= '.(Date_Time::to_time($_REQUEST['date'])+24*3600).'
					'.(Url::get('staff_id')?' AND swimming_pool_staff_pool.staff_id = '.Url::iget('staff_id').'':'').'
			';
			$reservation_swimming_pool = DB::fetch_all($sql);
			foreach($reservation_swimming_pool as $k=>$v){
				$tooltip = $v['guest_name']?Portal::language('guest_name').': '.$v['guest_name']."\n":'';
				$tooltip .= Portal::language('time').': '.date('H:i',$v['time_in']).' - '.date('H:i',$v['time_out'])."\n";
				$tooltip .= '------------------------------'."\n".Portal::language('serve_staff').': '."\n";
				$staffs = DB::fetch_all('SELECT swimming_pool_staff.*,swimming_pool_staff_pool.reservation_pool_id FROM swimming_pool_staff LEFT OUTER JOIN swimming_pool_staff_pool ON swimming_pool_staff_pool.staff_id = swimming_pool_staff.id where swimming_pool_staff_pool.reservation_pool_id = '.$v['id'].'');
				foreach($staffs as $v1){
						$tooltip .=' - '.$v1['full_name']."\n";
				}
				if($v['note']){
					$tooltip .= "------------------------------\n".$v['note']."\n";
				}
				$reservation_swimming_pool[$k]['tooltip'] = $tooltip;
				$reservation_swimming_pool[$k]['brief_status'] = ($v['status']=='BOOKED')?'B':(($v['status']=='CHECKIN')?'IN':'OUT');
			}
			$swimming_pools[$key]['reservation_swimming_pool'] = $reservation_swimming_pool;
		}
		$this->map['swimming_pools'] = $swimming_pools;
		$begin_hour = 8;
		$end_hour = 23;
		$this->map['begin_time'] = $current_time + 8*3600;
		$this->map['aggregate_duration'] = $aggregate_duration = ($end_hour - $begin_hour) * 60;
		$this->parse_layout('report',$this->map);
	}
}
?>