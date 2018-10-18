<?php
class AgentStatisticReportForm extends Form
{
	function AgentStatisticReportForm()
	{
		Form::Form('AgentStatisticReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function get_items($condition){
		$sql='
		     SELECT 
			  rr.id ,customer.NAME as companyname,
			  r.booking_code,
			  room_type.name as room_type,
			  room.name as room_name,
			  rr.departure_time,
			  rr.arrival_time,
			  rr.status,
			  rr.departure_time - rr.arrival_time AS duration,
			  rr.note as room_note
			  From reservation_room rr
			  INNER JOIN room on rr.room_id = room.id
			  INNER JOIN room_type on room.room_type_id = room_type.id
			  INNER JOIN reservation r ON rr.reservation_id = r.id
			  LEFT OUTER JOIN customer ON customer.ID = r.customer_id
			  INNER JOIN customer_group ON customer_group.id = customer.group_id AND customer_group.id = \'TOURISM\'
			 WHERE (1=1) '.$condition;
			 return DB::fetch_all($sql);
	}
	function draw(){
		$this->map = array();
		 $from_day =(Url::get('from_day'))?Date_Time::to_orc_date(Url::get('from_day')):'1-'.date('M').'-'.date('Y');
		 $end_day = (Url::get('to_day'))?Date_Time::to_orc_date(Url::get('to_day')):date('d/M/Y');
		 $cond = ' AND  
					 r.portal_id = \''.PORTAL_ID.'\' and (rr.status = \'CHECKOUT\' OR rr.status = \'CHECKIN\')
					 AND rr.departure_time >=\''.$from_day.'\'
					 AND rr.departure_time <=\''.$end_day.'\'';
			$this->map['items'] = $this->get_items($cond);	
			$this->map['from_date'] =Url::get('from_day')?Url::get('from_day'):'1/'.date('m').'/'.date('Y');
			$this->map['to_date'] =Url::get('to_day')?Url::get('from_day'):date('d/m/Y');
			$this->parse_layout('report',$this->map);	
	}
}
?>