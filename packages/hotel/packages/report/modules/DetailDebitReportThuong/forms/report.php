<?php
class DetailDebitReportThuongForm extends Form
{
	function DetailDebitReportThuongForm()
	{
		Form::Form('DetailDebitReportThuongForm');
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
			$cond = '(RR1.STATUS = \'CHECKOUT\' OR RR1.STATUS = \'CHECKIN\')
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
						CONCAT(RESERVATION.id,CONCAT(RR1.arrival_time,CONCAT(RR1.departure_time,RR1.verify_dayuse))) as old_id,
						reservation.id as id,
						RESERVATION.booking_code,
						CONCAT(reservation.booking_code,CONCAT(\' \',tour.name)) AS name,
						min(RR1.arrival_time) as arrival_time, max(RR1.departure_time) as departure_time,
						CASE WHEN (RR1.verify_dayuse) IS NOT NULL THEN (RR1.departure_time - RR1.arrival_time)+((RR1.verify_dayuse)/10) ELSE (RR1.departure_time - RR1.arrival_time) END night,
						RR1.verify_dayuse/10 AS verify_dayuse,
						SUM(RR1.adult)AS num_people,
						COUNT(RR1.id) AS num_room,
						SUM(DECODE(RESERVATION.PAYMENT,null,0,RESERVATION.PAYMENT)) AS PAIED,
						RR1.arrival_time,RR1.departure_time,
						0 as total
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
			//----------------- Tien phòng-------------------//
			$sql_rr='
					SELECT 
						room_status.id as id
						,room_status.change_price
						,reservation.customer_id 
						,CUSTOMER.NAME
						,DECODE(RR1.service_rate,null,0,RR1.service_rate) as service_rate
						,DECODE(RR1.tax_rate,null,0,RR1.tax_rate) as tax_rate
						,RR1.deposit
						,reservation.id as reservation_id
						,0 as total
					FROM
						room_status
						INNER JOIN RESERVATION_ROOM RR1 ON RR1.id = room_status.reservation_room_id
						INNER JOIN reservation ON reservation.id = RR1.RESERVATION_ID
						INNER JOIN PAYMENT_TYPE ON PAYMENT_TYPE.ID= RR1.PAYMENT_TYPE_ID AND PAYMENT_TYPE.DEF_CODE=\'DEBIT\'	
						INNER JOIN CUSTOMER ON CUSTOMER.ID = RESERVATION.CUSTOMER_ID
					WHERE
						'.$cond.'  AND PAYMENT_TYPE.DEF_CODE=\'DEBIT\'
					';
			$reservation_rooms= DB::fetch_all($sql_rr);
			
			//-------------dich vu phong-------------------//
			$total_services = DB::fetch_all('
			SELECT 
				reservation.id
				,SUM(reservation_room_service.amount) as total
			from 
				reservation_room_service
				inner join reservation_room RR1 on RR1.id = reservation_room_service.reservation_room_id
				INNER JOIN reservation ON reservation.id = RR1.reservation_id
				INNER JOIN PAYMENT_TYPE ON PAYMENT_TYPE.ID = RR1.PAYMENT_TYPE_ID AND PAYMENT_TYPE.DEF_CODE = \'DEBIT\'
				inner join service on service.id = service_id 
			where 
				'.$cond.'
				AND service.type = \'ROOM\'
			GROUP BY reservation.id	
		');
		//--------------dat coc phong-------------------------//
			$deposit_room = DB::fetch_all('
				SELECT
					reservation.id 
					,sum(RR1.deposit) as deposit
				FROM 
					reservation_room RR1
					INNER JOIN reservation ON reservation.id = RR1.reservation_id
					INNER JOIN PAYMENT_TYPE ON PAYMENT_TYPE.ID = RR1.PAYMENT_TYPE_ID AND PAYMENT_TYPE.DEF_CODE = \'DEBIT\'
					INNER JOIN reservation r on RR1.reservation_id = r.id
				WHERE
					'.$cond.'
				GROUP BY reservation.id	
		    ');
			//--------------------------------Tien nha hang---------------------------
			$sql_bar = 'select DISTINCT
					sum((ROUND(((brp.price *'.RES_EXCHANGE_RATE.' *(brp.quantity - brp.quantity_discount)) - ((brp.price *'.RES_EXCHANGE_RATE.' *(brp.quantity - brp.quantity_discount))* brp.discount_rate/100 ) + ROUND((((brp.price *'.RES_EXCHANGE_RATE.' *(brp.quantity - brp.quantity_discount)) - ((brp.price *'.RES_EXCHANGE_RATE.' *(brp.quantity - brp.quantity_discount))* brp.discount_rate/100 )) * bar_reservation.BAR_FEE_RATE/100),2) + ROUND((((brp.price *'.RES_EXCHANGE_RATE.' *(brp.quantity - brp.quantity_discount)) - ((brp.price *'.RES_EXCHANGE_RATE.' *(brp.quantity - brp.quantity_discount))* brp.discount_rate/100 ) + (((brp.price *'.RES_EXCHANGE_RATE.' *(brp.quantity - brp.quantity_discount)) - ((brp.price *'.RES_EXCHANGE_RATE.' *(brp.quantity - brp.quantity_discount))* brp.discount_rate/100 )) * bar_reservation.bar_fee_rate/100))*bar_reservation.tax_rate/100),2))/bar_reservation.exchange_rate,2))) as total_bar
				from 
					bar_reservation
					inner join bar_reservation_product brp on brp.bar_reservation_id=bar_reservation.id
					inner join reservation_room RR1 on RR1.id=bar_reservation.reservation_room_id

					inner join reservation on RR1.reservation_id=reservation.id
					inner join room on room.id=RR1.room_id
					INNER JOIN PAYMENT_TYPE ON PAYMENT_TYPE.ID= RR1.PAYMENT_TYPE_ID AND PAYMENT_TYPE.DEF_CODE=\'DEBIT\'
					LEFT OUTER JOIN TOUR ON TOUR.ID = RESERVATION.TOUR_ID
				where 
					'.$cond.' AND bar_reservation.GROUP_PAYMENT = 1 and bar_reservation.status = \'CHECKOUT\'';
			$total_bars = DB::fetch($sql_bar);
			//----------------------Dat coc nha hang--------------------------------------
			$deposit_bar =  DB::fetch('select
					sum(bar_reservation.prepaid) as deposit
				from 
					bar_reservation
					inner join reservation_room RR1 on RR1.id=bar_reservation.reservation_room_id
					inner join reservation on RR1.reservation_id=reservation.id
					inner join room on room.id=RR1.room_id
					INNER JOIN PAYMENT_TYPE ON PAYMENT_TYPE.ID= RR1.PAYMENT_TYPE_ID AND PAYMENT_TYPE.DEF_CODE=\'DEBIT\'
					LEFT OUTER JOIN TOUR ON TOUR.ID = RESERVATION.TOUR_ID
				where 
					'.$cond.' AND bar_reservation.GROUP_PAYMENT = 1 and bar_reservation.status = \'CHECKOUT\'');
			//-------------------------------Tien minibar------------------------------------
			$sql_minibar = 'select 
						sum(housekeeping_invoice.total) as minibar_total
				from 
					housekeeping_invoice
					inner join minibar on minibar.id=housekeeping_invoice.minibar_id
					inner join room on room.id=minibar.room_id
					inner join reservation_room RR1 on RR1.id=housekeeping_invoice.reservation_room_id and RR1.room_id=minibar.room_id
					inner join reservation on reservation.id=RR1.reservation_id
					INNER JOIN PAYMENT_TYPE ON PAYMENT_TYPE.ID= RR1.PAYMENT_TYPE_ID AND PAYMENT_TYPE.DEF_CODE=\'DEBIT\'
					LEFT OUTER JOIN TOUR ON TOUR.ID = RESERVATION.TOUR_ID
				where '.$cond.' and housekeeping_invoice.type = \'MINIBAR\' and housekeeping_invoice.group_payment=1';
			$total_minibar = DB::fetch($sql_minibar);
			//-----------------------------------Tien giat la----------------------------------
			$sql_laundry = 'select 
					sum(housekeeping_invoice.total) as laundry_total
				from 
					housekeeping_invoice
					inner join room on room.id=housekeeping_invoice.minibar_id
					inner join reservation_room RR1 on RR1.id=housekeeping_invoice.reservation_room_id
					inner join reservation on reservation.id=RR1.reservation_id
					INNER JOIN PAYMENT_TYPE ON PAYMENT_TYPE.ID= RR1.PAYMENT_TYPE_ID AND PAYMENT_TYPE.DEF_CODE=\'DEBIT\'
					LEFT OUTER JOIN TOUR ON TOUR.ID = RESERVATION.TOUR_ID
				where 
					'.$cond.' and housekeeping_invoice.type = \'LAUNDRY\' and housekeeping_invoice.group_payment=1';	
			$total_laundry = DB::fetch($sql_laundry);
			//----------------------------------------Thong tin phong----------------------------------							
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
			$arr = array();
			$arr['total_minibar'] = round($total_minibar['minibar_total'],ROUND_PRECISION);
			$arr['total_bar'] = $total_bars['total_bar'] - $deposit_bar['deposit'];
			$arr['total_laundry'] = round($total_laundry['laundry_total'],ROUND_PRECISION);
			//System::Debug($rooms);	
			foreach($report->items as $id=>$item)
			{   $total_rr = 0;
				foreach($reservation_rooms as $key =>$value){
					$service_rate = $value['change_price'] * $value['service_rate']/100;
					$tax_rate = ($value['change_price'] + $service_rate) * $value['tax_rate']/100;
					$net = $value['change_price'] + $service_rate + $tax_rate;
					if($item['id'] == $value['reservation_id']){
						$report->items[$id]['total'] += $net;	
						$total_rr += $net;	
					}
				}
				if(isset($total_services[$item['id']]['id']) and $item['id'] == $total_services[$item['id']]['id']){
					$report->items[$id]['total'] += $total_services[$item['id']]['total'];
					$total_rr += $total_services[$item['id']]['total'];
				}
				if(isset($deposit_room[$item['id']]['id']) and $item['id'] == $deposit_room[$item['id']]['id']){
					$report->items[$id]['total'] = $report->items[$id]['total'] - $deposit_room[$item['id']]['deposit'];
					$total_rr  = $total_rr - $deposit_room[$item['id']]['deposit'];
				}
				$rooms_stay = '';
				$c = 0;
				foreach($rooms as $k=>$v){
					if($v['reservation_id'] == $item['id']){
						$rooms_stay .= ($c>0?', ':'').$v['room_name'].($v['verify_dayuse']?'('.round($v['verify_dayuse'],1).')':'');
						$c++;
					}
				}
				$report->items[$id]['rooms_stay'] = $rooms_stay;
				$report->items[$id]['arrival_time'] = Date_Time::convert_orc_date_to_date($item['arrival_time'],'/');
				$report->items[$id]['departure_time'] = Date_Time::convert_orc_date_to_date($item['departure_time'],'/');
				$report->items[$id]['debit'] = System::display_number($total_rr - $item['paied']);
				$report->items[$id]['total'] = System::display_number($total_rr);
				$report->items[$id]['paied'] = System::display_number($item['paied']);
				$report->items[$id]['stt'] = $i++;
			}
			//System::Debug($report->items);	
			$this->print_all_pages($report,$arr);
		}
		else
		{
			$this->map['customer_id_list'] = String::get_list(DB::select_all('customer','GROUP_ID=\'TOURISM\'','name'));
			$this->map['tour_id_list'] = array(''=>Portal::language('All')) + String::get_list(DB::select_all('tour',false,'name'));
			$this->map['portal_id_list'] = array(''=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
			$this->parse_layout('search',$this->map);	
		}			
	}

	function print_all_pages(&$report,$arr)
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
		$summary['total_minibar'] = $arr['total_minibar'];
		$summary['total_laundry'] = $arr['total_laundry'];
		$summary['total_bar'] = $arr['total_bar'];
		$summary['total_amount'] = System::display_number($summary['total_amount'] + $arr['total_minibar'] + $arr['total_bar'] + $arr['total_laundry']);
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
}
?>