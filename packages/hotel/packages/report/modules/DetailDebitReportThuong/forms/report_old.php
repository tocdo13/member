<?php
class DetailDebitReportForm extends Form
{
	function DetailDebitReportForm()
	{
		Form::Form('DetailDebitReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw()
	{
		if(!isset($_REQUEST['date_from'])){
			$_REQUEST['date_from'] = '01/'.date('m/Y');
		}
		if(!isset($_REQUEST['date_to'])){
			$end_date_of_month = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
			$_REQUEST['date_to'] = $end_date_of_month.'/'.date('m/Y');
		}
		if(URL::get('do_search'))
		{
			$this->line_per_page = 4000;// OR RR1.STATUS = \'BOOKED\'
			$cond = '(RR1.STATUS = \'CHECKOUT\' OR RR1.STATUS = \'CHECKIN\' OR RR1.STATUS = \'BOOKED\')
					'.((URL::get('customer_id'))?' AND reservation.customer_id = '.Url::get('customer_id').'':'').'
					'.((URL::get('booking_code'))?' AND reservation.booking_code = \''.Url::sget('booking_code').'\'':'').'
					'.(Url::get('date_from')?' AND RR1.DEPARTURE_TIME >=\''.Date_Time::to_orc_date(Url::get('date_from')).'\'':'').'
					'.(Url::get('date_to')?' AND RR1.DEPARTURE_TIME <=\''.Date_Time::to_orc_date(Url::get('date_to')).'\'':'').'
			';
			if(User::can_admin(false,ANY_CATEGORY)){
				$cond .= Url::get('portal_id')?' and reservation.portal_id = \''.Url::get('portal_id').'\'':'';
			}else{
				$cond .= ' and reservation.portal_id = \''.PORTAL_ID.'\'';	
			}
			require_once 'packages/core/includes/utils/lib/report.php';
			$report = new Report;
			$sql='
					SELECT 
						CONCAT(RESERVATION.id,CONCAT(RR1.arrival_time,CONCAT(RR1.departure_time,RR1.verify_dayuse))) as id,
						reservation.id as reservation_id,
						RESERVATION.booking_code,
						CONCAT(reservation.booking_code,CONCAT(\' \',tour.name)) AS name,
						RR1.arrival_time, RR1.departure_time,
						CASE WHEN (RR1.verify_dayuse) IS NOT NULL THEN (RR1.departure_time - RR1.arrival_time)+((RR1.verify_dayuse)/10) ELSE (RR1.departure_time - RR1.arrival_time) END night,
						RR1.verify_dayuse/10 AS verify_dayuse,
						SUM(RR1.adult)AS num_people,
						COUNT(RR1.id) AS num_room,
						SUM(DECODE(RESERVATION.PAYMENT,null,0,RESERVATION.PAYMENT)) AS PAIED,
						RR1.arrival_time,RR1.departure_time
					FROM
						reservation
						INNER JOIN RESERVATION_ROOM RR1 ON RR1.RESERVATION_ID = reservation.ID
						INNER JOIN PAYMENT_TYPE ON PAYMENT_TYPE.ID= RR1.PAYMENT_TYPE_ID AND PAYMENT_TYPE.DEF_CODE=\'DEBIT\'
						LEFT OUTER JOIN TOUR ON TOUR.ID = RESERVATION.TOUR_ID	
					WHERE
						'.$cond.'
					GROUP BY
						RR1.arrival_time,RR1.departure_time,RESERVATION.ID,RESERVATION.booking_code,tour.name,reservation.booking_code,RR1.verify_dayuse
					ORDER BY
						reservation.id,reservation.booking_code,tour.name,RR1.arrival_time,RR1.departure_time
						
			';
			$report->items = DB::fetch_all($sql);
			$sql='
					SELECT 
						RR1.id,
						RESERVATION.id as reservation_id,
						RESERVATION.booking_code,
						RESERVATION.tour_id,
						RR1.arrival_time,RR1.departure_time,
						RR1.verify_dayuse/10 as verify_dayuse,
						room.name as room_name
					FROM
						reservation
						INNER JOIN RESERVATION_ROOM RR1 ON RR1.RESERVATION_ID = reservation.ID
						INNER JOIN PAYMENT_TYPE ON PAYMENT_TYPE.ID= RR1.PAYMENT_TYPE_ID AND PAYMENT_TYPE.DEF_CODE=\'DEBIT\'
						LEFT OUTER JOIN room ON room.id = RR1.room_id
					WHERE
						'.$cond.'
						
			';
			$rooms = DB::fetch_all($sql);
			$i = 1;
			foreach($report->items as $key=>$item)
			{
				$total = $this->get_total_room($item['reservation_id'],$item['verify_dayuse'],$item['arrival_time'],$item['departure_time']);
				$total_service_amount = $this->get_total_service($item['reservation_id'],$item['verify_dayuse'],$item['arrival_time'],$item['departure_time']);
				$total = $total + $total_service_amount;
				$rooms_stay = '';
				$c = 0;
				foreach($rooms as $k=>$v){
					if($v['reservation_id'] == $item['reservation_id'] and $v['arrival_time'] == $item['arrival_time'] and $v['departure_time'] == $item['departure_time'] and $v['verify_dayuse'] == $item['verify_dayuse']){
						$rooms_stay .= ($c>0?', ':'').$v['room_name'].($v['verify_dayuse']?'('.round($v['verify_dayuse'],1).')':'');
						$c++;
					}
				}
				$report->items[$key]['rooms_stay'] = $rooms_stay;
				$report->items[$key]['arrival_time'] = Date_Time::convert_orc_date_to_date($item['arrival_time'],'/');
				$report->items[$key]['departure_time'] = Date_Time::convert_orc_date_to_date($item['departure_time'],'/');
				$report->items[$key]['debit'] = System::display_number($total - $item['paied']);
				$report->items[$key]['total'] = System::display_number($total);
				$report->items[$key]['paied'] = System::display_number($item['paied']);
				$report->items[$key]['stt'] = $i++;
			}
			$this->print_all_pages($report);
		}
		else
		{
			$this->map['customer_id_list'] = String::get_list(DB::select_all('customer','GROUP_ID=\'TOURISM\'','name'));
			$this->map['tour_id_list'] = array(''=>Portal::language('All')) + String::get_list(DB::select_all('tour',false,'name'));
			$this->map['portal_id_list'] = array(''=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
			$this->parse_layout('search',$this->map);	
		}			
	}

	function print_all_pages(&$report)
	{
		$count = 8;
		$total_page = 1;
		$pages = array();
		$summary = array(
			'total_amount'=>0,
			'total_paied'=>0,
			'total_debit'=>0,
			'total_minibar'=>0,
			'total_laundry'=>0,
			'total_bar'=>0
		);
		$tour_id = false;
		foreach($report->items as $key=>$item)
		{
			if($tour_id<>$item['id'])
			{
				$tour_id=$item['id'];
				$summary['total_amount'] += str_replace(',','',$item['total']);
				$summary['total_paied'] += str_replace(',','',$item['paied']);
				$summary['total_debit'] += str_replace(',','',$item['debit']);
			}
			if($count>=$this->line_per_page)
			{
				$count = 0;
				$total_page++;
			}
			$pages[$total_page][$key] = $item;
			$line = ceil(strlen($item['name'])/36);
			$count+=($line<2)?2:$line;
		}
		$summary['total_amount'] = System::display_number($summary['total_amount']);
		$summary['total_paied'] = System::display_number($summary['total_paied']);
		$summary['total_debit'] = System::display_number($summary['total_debit']);
		if(sizeof($pages)>0)
		{
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page,$summary);
			}
		}
		else
		{
			$this->map['name'] = Url::get('customer_id')?DB::fetch('SELECT ID,NAME FROM CUSTOMER WHERE ID = '.Url::iget('customer_id').'','name'):'';
			$this->parse_layout('header',$this->map+
				array(
					'page_no'=>1,
					'total_page'=>1
				)
			);
			$this->parse_layout('footer',array(
				'page_no'=>0,
				'total_page'=>0
			));
		}
	}
	function print_page($items, &$report, $page_no,$total_page,$summary)
	{
		$this->map['name'] = Url::get('customer_id')?DB::fetch('SELECT ID,NAME FROM CUSTOMER WHERE ID = '.Url::iget('customer_id').'','name'):Portal::language('All');
		$this->parse_layout('header',$this->map+
			array(
				'page_no'=>$page_no,
				'total_page'=>$total_page
			)
		);		
		$this->parse_layout('report',
			$summary+
			array(
				'items'=>$items,
				'page_no'=>$page_no,
				'total_page'=>$total_page
			)
		);
		
		$this->parse_layout('footer',array(				
			'page_no'=>$page_no,
			'total_page'=>$total_page)
		);
	}
	function get_total_room($reseration_id,$verify_dayuse,$from_date,$end_date){
		$cond = '(rr.STATUS = \'CHECKOUT\' OR rr.STATUS = \'CHECKIN\' OR rr.STATUS = \'BOOKED\')
				AND rr.arrival_time =\''.$from_date.'\' 
				AND rr.departure_time = \''.$end_date.'\'
				AND rr.reservation_id = '.$reseration_id.'
		';
		if($verify_dayuse){
			$cond .= 'AND rr.verify_dayuse = '.($verify_dayuse*10).'';
		}else{
			$cond .= 'AND (rr.verify_dayuse = 0 OR rr.verify_dayuse is null)';
		}
		$sql1 = '
				SELECT 
					rs.id,
					rs.change_price,
					rs.reservation_room_id, 
					rr.tax_rate, rr.service_rate,
					rr.arrival_time,
					rr.departure_time,
					rr.price
				FROM 
					room_status rs 
					INNER JOIN reservation_room rr ON rr.id = rs.reservation_room_id 
					INNER JOIN PAYMENT_TYPE ON PAYMENT_TYPE.ID = rr.PAYMENT_TYPE_ID AND PAYMENT_TYPE.DEF_CODE = \'DEBIT\'
					INNER JOIN reservation r on rr.reservation_id = r.id
				WHERE
					'.$cond.'
		    ';
		$room_status = DB::fetch_all($sql1);
		// tinh tien dich vu phong  
		$services = 0;/*DB::fetch('SELECT 
					     				sum (rrs.amount) as service 
									FROM 
										reservation_room_service rrs 
										INNER JOIN reservation_room rr ON rrs.reservation_room_id = rr.id 
										INNER JOIN room_status rs ON rr.id = rs.reservation_room_id
										INNER JOIN service ON service.id = rrs.service_id
									WHERE '.$cond.' AND  rs.in_date >= \''.date('d/M/Y',$from_date).'\' AND rs.in_date <= \''.date('d/M/Y',$end_date).'\'
									      AND service.type=\'ROOM\'','service');*/
		// tinh tien dat coc theo reservation_room
		$total_rooms = 0;
		$services_other = 0;
		foreach($room_status as $k_room =>$v_room){
			if($v_room['arrival_time'] == $v_room['departure_time']){
				if(!$v_room['change_price']){
					$v_room['change_price'] = $v_room['price'];
				}
			}
			$pay_room=0;
			$tax_room=0;
			$net =0;
			// phi dich vu phong 
			$service_room =($v_room['change_price'] * $v_room['service_rate'])/100;
			// tien phong  + dv 1 phong
			$pay_room  = $v_room['change_price'] + $service_room ;	
			// tinh tien thue cua 1 phong 
			$tax_room = ($pay_room * $v_room['tax_rate'])/100;
			$net = $pay_room + $tax_room ;
			// tien cua tat ca 
			$total_rooms += $net;
		}
		$total_rooms = $total_rooms + $services;
		return $total_rooms;
	}
	function get_total_service($reseration_id,$verify_dayuse,$from_date,$end_date){
		$sub_cond = '(rr.STATUS = \'CHECKOUT\' OR rr.STATUS = \'CHECKIN\' OR rr.STATUS = \'BOOKED\')
				AND rr.arrival_time =\''.$from_date.'\' 
				AND rr.departure_time = \''.$end_date.'\'
				AND rr.reservation_id = '.$reseration_id.'
		';
		if($verify_dayuse){
			$sub_cond .= 'AND rr.verify_dayuse = '.($verify_dayuse*10).'';
		}else{
			$sub_cond .= 'AND (rr.verify_dayuse = 0 OR rr.verify_dayuse is null)';
		}
		$sql = '
			SELECT 
				SUM(reservation_room_service.amount) as total
			from 
				reservation_room_service
				inner join reservation_room rr on rr.id = reservation_room_service.reservation_room_id
				INNER JOIN PAYMENT_TYPE ON PAYMENT_TYPE.ID = rr.PAYMENT_TYPE_ID AND PAYMENT_TYPE.DEF_CODE = \'DEBIT\'
				inner join service on service.id = service_id 
			where 
				'.$sub_cond.'
				AND service.type = \'ROOM\'
		';
		if($total_other_service_with_room = DB::fetch($sql,'total')){
			return $total_other_service_with_room;
		}else{
			return 0;
		}
	}
}
?>