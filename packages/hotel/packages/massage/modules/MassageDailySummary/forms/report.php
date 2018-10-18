<?php
class MassageDailySummaryReportForm extends Form
{
	function MassageDailySummaryReportForm()
	{
		Form::Form('MassageDailySummaryReportForm');
		$this->link_css('packages/hotel/'.Portal::template('massage').'/css/style.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw()
	{
	   //System::debug($_REQUEST);
		$this->map = array();
		$current_time = Url::get('date')?Date_Time::to_time(Url::get('date')):Date_Time::to_time(date('d/m/Y',time()));
		if(!isset($_REQUEST['date'])){
			$_REQUEST['date'] = date('d/m/Y');
		}
		$this->map['current_time'] = $current_time;
		$rooms = DB::select_all('massage_room',' portal_id = \''.PORTAL_ID.'\'','position,name');
		$sql = 'SELECT massage_staff.* FROM massage_staff';
		$staffs = DB::fetch_all($sql);
		$this->map['staff_id_list'] = array(''=>Portal::language('all'))+String::get_list($staffs,'full_name');
		foreach($rooms as $key=>$value){
			$sql = '
				SELECT 
					massage_product_consumed.*,
                    massage_guest.full_name as guest_name,
                    massage_reservation_room.note,
                    massage_product_consumed.reservation_room_id,
                    massage_reservation_room.hotel_reservation_room_id as ht_reservation_room_id,
                    massage_reservation_room.package_id 
				FROM
					massage_product_consumed
					INNER JOIN massage_reservation_room ON massage_reservation_room.id = massage_product_consumed.reservation_room_id
					LEFT OUTER JOIN massage_guest ON massage_guest.id = massage_reservation_room.guest_id
					LEFT OUTER JOIN massage_staff_room ON massage_staff_room.reservation_room_id = massage_reservation_room.id
				WHERE
					massage_product_consumed.room_id = '.$value['id'].' AND massage_reservation_room.portal_id=\''.PORTAL_ID.'\'
					AND massage_product_consumed.time_in >= '.Date_Time::to_time($_REQUEST['date']).'
					AND massage_product_consumed.time_out <= '.(Date_Time::to_time($_REQUEST['date'])+24*3600).'
					'.(Url::get('staff_id')?' AND massage_staff_room.staff_id = '.Url::iget('staff_id').'':'').'
			';
			$reservation_room = DB::fetch_all($sql);
			foreach($reservation_room as $k=>$v){
				$tooltip = $v['guest_name']?Portal::language('guest_name').': '.$v['guest_name']."\n":'';
				$tooltip .= Portal::language('time').': '.date('H:i',$v['time_in']).' - '.date('H:i',$v['time_out'])."\n";
				$tooltip .= '------------------------------'."\n".Portal::language('serve_staff').': '."\n";
				$staffs = DB::fetch_all('SELECT massage_staff.*,massage_staff_room.reservation_room_id FROM massage_staff LEFT OUTER JOIN massage_staff_room ON massage_staff_room.staff_id = massage_staff.id where massage_staff_room.reservation_room_id = '.$v['reservation_room_id'].'');
				foreach($staffs as $v1){
						$tooltip .=' - '.$v1['full_name']."\n";
				}
				if($v['note']){
					$tooltip .= "------------------------------\n".$v['note']."\n";
				}
				$reservation_room[$k]['tooltip'] = $tooltip;
				$reservation_room[$k]['brief_status'] = ($v['status']=='BOOKED')?'B':(($v['status']=='CHECKIN')?'IN':'OUT');
			}
			$rooms[$key]['reservation_room'] = $reservation_room;
		}
            //System::debug($sql);
		$this->map['rooms'] = $rooms;
		$begin_hour = 0;
		$end_hour = 24;
		$this->map['begin_time'] = $current_time + 0*3600;
		$this->map['aggregate_duration'] = $aggregate_duration = ($end_hour - $begin_hour) * 60;
		$this->parse_layout('report',$this->map);
	}
}
?>