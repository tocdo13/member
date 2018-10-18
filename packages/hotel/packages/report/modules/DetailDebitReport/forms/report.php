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
	    $this->map = array();
        
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):32;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
           
		if(!isset($_REQUEST['date_from'])){
			$_REQUEST['date_from'] = '01/'.date('m/Y');
		}
		if(!isset($_REQUEST['date_to'])){
			$end_date_of_month = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
			$_REQUEST['date_to'] = $end_date_of_month.'/'.date('m/Y');
		}
		if(URL::get('do_search'))
		{
			// OR RR1.STATUS = \'BOOKED\'
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
			$sql='SELECT 
						reservation.id,
						reservation.id as reservation_id,
						reservation.booking_code,
						CONCAT(reservation.booking_code,CONCAT(\' \',tour.name)) AS name,
						SUM(RR1.adult)AS num_people,
						SUM(RR1.child)AS num_child,
						COUNT(RR1.id) AS num_room
					FROM
						reservation
						INNER JOIN RESERVATION_ROOM RR1 ON RR1.RESERVATION_ID = reservation.ID
						LEFT OUTER JOIN TOUR ON TOUR.ID = RESERVATION.TOUR_ID	
					WHERE
						'.$cond.'
					GROUP BY 
						reservation.id,reservation.booking_code,tour.name
					ORDER BY
						reservation.id,reservation.booking_code
						
			';
            if(User::is_admin())
            {
                //System::debug($sql);
            }
			$reservations = DB::fetch_all($sql);
			$sql = 'SELECT 
							folio.reservation_id as id
							,sum(folio.total) as total
						FROM folio
							INNER JOIN customer ON customer.id = folio.customer_id
							INNER JOIN reservation ON folio.reservation_id = reservation.id
						WHERE 1>0
							AND folio.create_time >= '.Date_Time::to_time(Url::get('date_from')).'
							AND folio.create_time <= '.Date_Time::to_time(Url::get('date_to')).'
						 	AND folio.customer_id is not null
							AND folio.customer_id = '.URL::get('customer_id').'
						GROUP BY folio.reservation_id';
			$folios = DB::fetch_all($sql);
		//System::Debug($folios);
			$sql = 'SELECT 
						payment.id 
						,payment.payment_type_id
						,CASE 
							WHEN payment.currency_id=\'VND\'
								THEN 
									payment.amount / COALESCE(payment.exchange_rate,1)
							ELSE 	payment.amount * COALESCE(payment.exchange_rate,1)	
						END as total
						,payment.folio_id
						,payment.reservation_id
						,payment.type_dps
					FROM payment
					WHERE
						payment.customer_id = '.URL::get('customer_id').'
					';
			$payments = DB::fetch_all($sql);
			foreach($folios as $f =>$folio){
				$folios[$f]['paid'] = 0;
				$folios[$f]['debit'] = 0;
				$total_paid = 0;$total_foc = 0;
				foreach($payments as $key => $pay){
					if($pay['reservation_id'] == $f){
						if($pay['payment_type_id'] == 'DEBIT'){
							$folios[$f]['debit'] += $pay['total']; 	 
						}else{
							$folios[$f]['paid'] += $pay['total']; 	
						}
						if($pay['payment_type_id'] != 'FOC'){
							if($pay['type_dps'] == ''){
								$total_paid += $pay['total'];
							}
						}else{
							$total_foc += $pay['total'];	
						}
					}
				}
				$folios[$f]['total'] = $folio['total'] - $total_foc;
				$folios[$f]['debit'] +=  (int)($folio['total'] - $total_foc - $total_paid);	
			}
			//System::Debug($folios);	
			foreach($reservations as $k=>$reservation){
				if(isset($folios[$k]) && $folios[$k]['debit']>0){
					$report->items[$k] = $reservation;
					$report->items[$k]['debit'] = $folios[$k]['debit'];	
					$report->items[$k]['paied'] = $folios[$k]['paid'];
					$report->items[$k]['total'] = $folios[$k]['total'];	
				}

			}
			$sql='
					SELECT 
						RR1.id,
						RESERVATION.id as reservation_id,
						RESERVATION.booking_code,
						RESERVATION.tour_id,
						RR1.arrival_time,
						RR1.departure_time,
						room.name as room_name,
						CASE WHEN (RR1.departure_time - RR1.arrival_time) > 0
							THEN CASE WHEN (RR1.verify_dayuse) IS NOT NULL
									  THEN (RR1.departure_time - RR1.arrival_time)+((RR1.verify_dayuse)/10)
									  ELSE (RR1.departure_time - RR1.arrival_time)
								END
							ELSE CASE WHEN (RR1.verify_dayuse) IS NOT NULL
									  THEN 1+((RR1.verify_dayuse)/10)
									  ELSE 1
								END
						END as night									
					FROM
						reservation
						INNER JOIN RESERVATION_ROOM RR1 ON RR1.RESERVATION_ID = reservation.ID
						LEFT OUTER JOIN room ON room.id = RR1.room_id
					WHERE
						'.$cond.'
						
			';
			$rooms = DB::fetch_all($sql);
            
			$i = 1;
			if(!empty($report->items)){
				foreach($report->items as $key=>$item)
				{
					$rooms_stay = '';
					$c = 0;
					$arrival_time = '';
					$departure_time = '';
					$report->items[$key]['night'] = 0;
					foreach($rooms as $k=>$v){
						if($v['reservation_id'] == $key){
							$rooms_stay .= ($c>0?', ':'').$v['room_name'];
							if($arrival_time=='' || Date_Time::to_time(Date_Time::convert_orc_date_to_date($v['arrival_time'],'/')) < Date_Time::to_time($arrival_time)){
								$arrival_time = Date_Time::convert_orc_date_to_date($v['arrival_time'],'/');
							}
							if($departure_time=='' || Date_Time::to_time(Date_Time::convert_orc_date_to_date($v['departure_time'],'/')) < Date_Time::to_time($departure_time)){
								$departure_time = Date_Time::convert_orc_date_to_date($v['departure_time'],'/');
							}
							$report->items[$key]['night'] = $v['night'];
							$c++;
						}
					}
					$report->items[$key]['rooms_stay'] = $rooms_stay;
					$report->items[$key]['arrival_time'] = $arrival_time;
					$report->items[$key]['departure_time'] = $departure_time;
					$report->items[$key]['stt'] = $i++;
				}
			}
			$this->print_all_pages($report);
		}
		else
		{
if(!Url::get('portal_id')){
			$_REQUEST['portal_id'] = PORTAL_ID;
}
			$this->map['customer_id_list'] = String::get_list(DB::select_all('customer','1=1','name'));
			//System::debug($this->map['customer_id_list']);
            $this->map['tour_id_list'] = array(''=>Portal::language('All')) + String::get_list(DB::select_all('tour',false,'name'));
			$this->map['portal_id_list'] = array(''=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
			$this->parse_layout('search',$this->map);	
		}			
	}

	function print_all_pages(&$report)
	{
		$count = 0;
		$total_page = 1;
		$pages = array();
		$this->group_function_params = array(
			'total_amount'=>0,
			'total_paied'=>0,
			'total_debit'=>0
		);
		
		if(!empty($report->items)){
			foreach($report->items as $key=>$item)
			{
				if($count>=$this->map['line_per_page'])
    			{
    				$count = 0;
    				$total_page++;
    			}
    			$pages[$total_page][$key] = $item;
    			$count++;
			}
		}
		//$summary['total_amount'] = System::display_number($summary['total_amount']);
		//$summary['total_paied'] = System::display_number($summary['total_paied']);
		//$summary['total_debit'] = System::display_number($summary['total_debit']);
        $this->map['real_total_page']=0;
        $this->map['real_page_no'] = 0;
		if(sizeof($pages)>0)
		{
		    $this->map['real_total_page']=count($pages);
            $this->map['real_page_no'] = 1;  
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page);
                $this->map['real_page_no'] ++;
			}
		}
		else
		{
			$this->map['name'] = Url::get('customer_id')?DB::fetch('SELECT ID,NAME FROM CUSTOMER WHERE ID = '.Url::iget('customer_id').'','name'):'';
			//System::debug($this->map['name']);
            $this->parse_layout('header',$this->map+
				array(
					'page_no'=>0,
					'total_page'=>0
				)
			);
			$this->parse_layout('footer',array(
				'page_no'=>0,
				'total_page'=>0
			)+$this->map);
		}
	}
	function print_page($items, &$report, $page_no,$total_page)
	{
        //start: KID them doan nay de tinh phan trang 1   
		$last_group_function_params = $this->group_function_params;
        $tour_id = false;
		foreach($items as $k => $item)
		{
		    if($tour_id<>$item['id'])
			{
				$tour_id=$item['id'];
				$this->group_function_params['total_amount'] += str_replace(',','',$item['total']);
				$this->group_function_params['total_paied'] += str_replace(',','',$item['paied']);
				$this->group_function_params['total_debit'] += str_replace(',','',$item['debit']);
			}  
  
		}
        //end: KID
		$this->map['name'] = Url::get('customer_id')?DB::fetch('SELECT ID,NAME FROM CUSTOMER WHERE ID = '.Url::iget('customer_id').'','name'):Portal::language('All');
		if($page_no>=$this->map['start_page'])
		{
		    $this->map['page_no'] = $page_no;  
            $this->parse_layout('header',$this->map+
    			array(
    				'total_page'=>$total_page
    			)
    		);		
    		$this->parse_layout('report',
    			array(
    				'items'=>$items,
   				
    				'total_page'=>$total_page,
                    'last_group_function_params'=>$last_group_function_params,
    				'group_function_params'=>$this->group_function_params
    			)+$this->map
    		);
    		
    		$this->parse_layout('footer',array(				
    			
    			'total_page'=>$total_page)+$this->map
    		);
        }
	}
	function get_total_room($reseration_id,$verify_dayuse,$from_date,$end_date,$price){
		$cond = '(rr.STATUS = \'CHECKOUT\' OR rr.STATUS = \'CHECKIN\')
				AND rr.arrival_time =\''.$from_date.'\' 
				AND rr.departure_time = \''.$end_date.'\'
				AND rr.reservation_id = '.$reseration_id.'
		';
		if($verify_dayuse){
			$cond .= 'AND rr.verify_dayuse = '.($verify_dayuse*10).'';
		}else{
			$cond .= 'AND (rr.verify_dayuse = 0 OR rr.verify_dayuse is null)';
		}
		if($price){
			$cond .= ' AND rr.price = '.$price.'';	
		}
		$sql1 = '
				SELECT 
					rs.id,
					rs.change_price,
					rs.reservation_room_id, 
					rr.tax_rate, rr.service_rate,
					rr.arrival_time,
					rr.departure_time,
					rr.price,
					DECODE(rr.REDUCE_BALANCE,null,0,rr.REDUCE_BALANCE) as reduce_balance,
					DECODE(rr.REDUCE_AMOUNT,null,0,rr.REDUCE_AMOUNT) as reduce_amount,
					DECODE(rr.deposit,null,0,rr.deposit) as deposit
				FROM 
					room_status rs 
					INNER JOIN reservation_room rr ON rr.id = rs.reservation_room_id 
					INNER JOIN PAYMENT_TYPE ON PAYMENT_TYPE.ID = rr.PAYMENT_TYPE_ID AND PAYMENT_TYPE.DEF_CODE = \'DEBIT\'
					INNER JOIN reservation r on rr.reservation_id = r.id
				WHERE
					'.$cond.'
		    ';
		$room_status = DB::fetch_all($sql1);
		// tinh tien dat coc theo reservation_room
		$total_rooms = 0;
		$ch_dp= array();
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
			$pay_room  = $v_room['change_price'] - ($v_room['change_price']*$v_room['reduce_balance']/100) + $service_room ;	
			// tinh tien thue cua 1 phong 
			$tax_room = ($pay_room * $v_room['tax_rate'])/100;
			$net = $pay_room + $tax_room ;
			// tien cua tat ca 
			$total_rooms += $net;
			
			if($v_room['reduce_amount'] != 0){	
				if(!isset($ch_dp[$v_room['reservation_room_id']]['reduce_amount'])){
					$ch_dp[$v_room['reservation_room_id']]['reduce_amount'] = $v_room['reservation_room_id'];
					$total_rooms =  $total_rooms - $v_room['reduce_amount'];
				}
			}
			if($v_room['deposit'] != 0){	
				if(!isset($ch_dp[$v_room['reservation_room_id']]['deposit'])){
					$ch_dp[$v_room['reservation_room_id']]['deposit'] = $v_room['reservation_room_id'];
					$total_rooms =  $total_rooms - $v_room['deposit'];
				}
			}
		}
		return $total_rooms;
	}
}
?>