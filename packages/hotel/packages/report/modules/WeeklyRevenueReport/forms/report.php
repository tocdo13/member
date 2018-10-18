<?php
class WeeklyRevenueReportForm extends Form{
	function WeeklyRevenueReportForm(){
		Form::Form('WeeklyRevenueReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function room_occupancy($from_date,$end_date){
		$cond = 'rs.status =\'OCCUPIED\' AND rs.in_date >=\''.$from_date.'\' AND rs.in_date <= \''.$end_date.'\'';
		$sql='
			SELECT 
			    count(rs.room_id) as total
			FROM 
				room_status rs
				inner join reservation_room rr on rr.id = rs.reservation_room_id
			WHERE 
				'.$cond.' ';
    	 $rooms= DB::fetch($sql,'total');
		// lay so phong sap check in hoac check out
		$sql2='
			SELECT 
			    rs.id,
			    rs.room_id,
				rs.in_date,
				rs.status, rr.arrival_time, 
				rr.departure_time
			FROM 
				reservation_room rr
				INNER JOIN room_status  rs on rr.id = rs.reservation_room_id
			WHERE 
				rs.in_date >= \''.$from_date.'\' AND rs.in_date <=\''.$end_date.'\' AND rs.status =\'OCCUPIED\' ';
		$room_in_outs = DB::fetch_all($sql2) ;
		$total_check_out = 0; 
		foreach($room_in_outs as $key=>$value){
			if(($value['departure_time']!=$value['arrival_time'])){
				if($value['in_date']==$value['departure_time']){
					$total_check_out += 1;
				}					
			}
		}
		return ($rooms - $total_check_out);
	}
	function get_orther_service($from_date,$end_date){
		if(!isset($_REQUEST['checkout_revenue'])){
			$cond = '(rr.status =\'CHECKOUT\' or rr.status =\'CHECKIN\') AND rr.departure_time >=\''.Date_Time::to_orc_date(date('d/m/Y',$from_date)).'\' AND rr.departure_time <= \''.Date_Time::to_orc_date(date('d/m/Y',$end_date)).'\'';
		}else{
			$cond = 'rr.status =\'CHECKOUT\' AND rr.departure_time >=\''.Date_Time::to_orc_date(date('d/m/Y',$from_date)).'\' AND rr.departure_time <= \''.Date_Time::to_orc_date(date('d/m/Y',$end_date)).'\'';
		}
		$sql='
				SELECT 
						sum (rrs.amount) as service , service.name, service.id as id
				FROM 
						reservation_room_service rrs 
						INNER JOIN reservation_room rr ON rrs.reservation_room_id = rr.id 
						INNER JOIN room_status rs ON rr.id = rs.reservation_room_id
						INNER JOIN service ON service.id = rrs.service_id				
				WHERE 	
						1=1 AND '.$cond.'
				GROUP BY  service.name, service.id ';//service.type !=\'ROOM\'
		$sv = 	DB::fetch_all($sql);
		return $sv;
	}
	function extra_service($from_date,$end_date){
		$dk='';
		if(!isset($_REQUEST['checkout_revenue'])){
			$cond = ' extra_service_invoice_detail.time >=\''.$from_date.'\' AND extra_service_invoice_detail.time <= \''.$end_date.'\'';
		}else{
			$cond = 'rr.status =\'CHECKOUT\' AND rr.departure_time >=\''.Date_Time::to_orc_date(date('d/m/Y',$from_date)).'\' AND rr.departure_time <= \''.Date_Time::to_orc_date(date('d/m/Y',$end_date)).'\'';
		}
		 $extras = DB::fetch('
								SELECT
									sum(extra_service_invoice_detail.quantity*extra_service_invoice_detail.price) as amount
								FROM
									extra_service_invoice_detail
									inner join extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
									inner join reservation_room rr on extra_service_invoice.reservation_room_id = rr.id
								WHERE
									'.$cond.' AND 
									( extra_service_invoice_detail.used = 1 )','amount');
		return $extras;
	}
	function get_phone($from_date,$end_date){
		$dk ='';
		if(!isset($_REQUEST['checkout_revenue'])){
			$cond = 'telephone_report_daily.hdate >=\''.$from_date.'\' AND telephone_report_daily.hdate < \''.$end_date.'\'';
		}else{
			$cond = 'rr.status =\'CHECKOUT\' AND rr.departure_time >=\''.Date_Time::to_orc_date(date('d/m/Y',$from_date)).'\' AND rr.departure_time <= \''.Date_Time::to_orc_date(date('d/m/Y',$end_date)).'\'';
		}
		$sql_p = '
					SELECT
						SUM(telephone_report_daily.price) AS total
					FROM
						telephone_report_daily
						inner join telephone_number on telephone_number.phone_number = telephone_report_daily.phone_number_id
						inner join reservation_room rr On telephone_number.room_id = rr.room_id
					WHERE  '.$cond.'';
		return  DB::fetch($sql_p,'total');
	}
	function get_spa($from_date,$end_date){
		$sql_massage='
					SELECT 
                        sum(massage_reservation_room.total_amount) as total_amount
					FROM 
						massage_reservation_room
					WHERE
					  status=\'CHECKOUT\' and  time >=\''.$from_date .'\' AND  time <\''. $end_date.'\' ';
					  
		DB::fetch($sql_massage,'total_amount');
	}
	function get_bar($from_date,$end_date){
		$sql_b = ' SELECT 
						 SUM(total) as total
					FROM
						bar_reservation 
					WHERE  
						status=\'CHECKOUT\' and  departure_time >=\''.$from_date .'\' AND  departure_time <\''. $end_date.'\' 
				';
		return $bar_service = DB::fetch($sql_b,'total');
	}
	function get_hk($from_date,$end_date){
		if(!isset($_REQUEST['checkout_revenue'])){
			$cond = ' housekeeping_invoice.time >=\''.$from_date.'\' AND housekeeping_invoice.time <= \''.$end_date.'\'';
		}else{
			$cond = 'rr.status =\'CHECKOUT\' AND rr.departure_time >=\''.Date_Time::to_orc_date(date('d/m/Y',$from_date)).'\' AND rr.departure_time < \''.Date_Time::to_orc_date(date('d/m/Y',$end_date)).'\'';
		}
		$sql_m='   
					 SELECT 
						 sum(housekeeping_invoice.total) as total
					FROM 
						housekeeping_invoice
						INNER JOIN reservation_room rr On housekeeping_invoice.reservation_id = rr.id
					WHERE 
						'.$cond.' 
						  AND housekeeping_invoice.type = \'MINIBAR\'
						';
	       return DB::fetch($sql_m,'total');
	}
	function get_laundy($from_date,$end_date){
		if(!isset($_REQUEST['checkout_revenue'])){
			$cond = 'housekeeping_invoice.time >=\''.$from_date.'\' AND housekeeping_invoice.time <= \''.$end_date.'\'';
		}else{
			$cond = 'rr.status =\'CHECKOUT\' AND rr.departure_time >=\''.Date_Time::to_orc_date(date('d/m/Y',$from_date)).'\' AND rr.departure_time <= \''.Date_Time::to_orc_date(date('d/m/Y',$end_date)).'\'';
			$dk='INNER JOIN room_status rs on rr.id = rs.reservation_room_id';
		}
		$sql_laundy='
						SELECT 
								SUM(housekeeping_invoice.total) AS total
						FROM 
								housekeeping_invoice
						INNER JOIN reservation_room rr ON housekeeping_invoice.reservation_id = rr.id
						WHERE 
								'.$cond.'
								AND housekeeping_invoice.type = \'LAUNDRY\'';
		 return DB::fetch($sql_laundy,'total');
	}
	function get_total_room($from_date,$end_date){
		if(!isset($_REQUEST['checkout_revenue'])){
			$cond = 'rs.status =\'OCCUPIED\' AND rs.in_date >=\''.Date_Time::to_orc_date(date('d/m/Y',$from_date)).'\' 
					AND rs.in_date < \''.Date_Time::to_orc_date(date('d/m/Y',$end_date)).'\'';
		}else{
			$cond = 'rr.status =\'CHECKOUT\' AND rr.departure_time >=\''.Date_Time::to_orc_date(date('d/m/Y',$from_date)).'\'
					 AND rr.departure_time <= \''.Date_Time::to_orc_date(date('d/m/Y',$end_date)).'\'';
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
					payment_type_id
				FROM 
					room_status rs 
					INNER JOIN reservation_room rr ON rr.id = rs.reservation_room_id 
					INNER JOIN reservation r on rr.reservation_id = r.id
				WHERE
					'.$cond.'';
		$room_total = DB::fetch_all($sql1);
        //echo $sql1;
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
		$cond_deposit ='';
		$ar_deposit = array();
		foreach($room_total as $k_room =>$v_room){
			if($v_room['arrival_time'] == $v_room['departure_time']){
				if(!$v_room['change_price']){
					$v_room['change_price'] = $v_room['price'];
				}
			}
			$pay_room=0;
			$tax_room=0;
			$net =0;
			if(!isset($ar_deposit[$v_room['reservation_room_id']])){
				$ar_deposit[$v_room['reservation_room_id']] = $v_room['reservation_room_id'];
			}
			$cond_deposit .=$v_room['reservation_room_id'];
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
		$cond = '';
		$i=0;
		$k=0;
		$arr_deposit = array();
		foreach($ar_deposit as $k_v=>$v){
			if($cond!=''){
				$cond .=',';
			}
			$cond .=$v;
			$i++;
			if($i == 998){
			  $arr_deposit[$k] = $cond;
			  $cond ='';
			  $i = 0;
			  $k++;
			}
		}
		if($i <998){
			if(!isset($arr_deposit[$k])){
				if($cond!=''){
					$arr_deposit[$k] = $cond;
				}
			}
			else{
				if($cond!=''){
				  $arr_deposit[$k+1] = $cond;
				}
			}
		}	
		if(isset($_REQUEST['checkout_revenue'])){
			foreach($arr_deposit as $k_d=>$v_d){
				 $depist = DB::fetch('
										SELECT sum(deposit) as total_deposit 
										FROM reservation_room rr 
										WHERE rr.id in ('.$v_d.')
										','total_deposit');
			 $total_rooms = $total_rooms - 	$depist;
			}
		   // tinh deposit trong khoang thoi gian
		   $sql_deposit=DB::fetch('
									SELECT 
										sum(deposit) as deposit 
									FROM 
										reservation_room rr 
									WHERE
										deposit_date >= \''.Date_Time::to_orc_date(date('d/m/Y',$from_date)).'\' 
										AND deposit_date <= \''.Date_Time::to_orc_date(date('d/m/Y',$end_date)).'\'','deposit'
								 );
    		$total_rooms = $total_rooms + $services + $sql_deposit ;	
		}
		return $total_rooms;
	}
	function draw(){
		$total= array();
		$orther_service = array();
		$total['mibibar']=0;
		$total['laundry']=0;
		$total['room_occupice']=0;
		$total['room_ocuupancy']=0;
		$total['bar'] =0;
		$total['spa'] =0;
		$total['extra_service'] =0;
		$total['phone'] =0;
		$total['orther_service'] =0;
		$total['ammount']=0;
		if(!Url::get('from_day')){
			if(date('w',Date_time::to_time(date('d/m/y'))) !=0 ){
				$date = Date_time::to_time(date('d/m/y')) - (date('w',Date_time::to_time(date('d/m/y'))))*24*3600;
			}else{
				$date = date('d/m/Y');
			}
				$from_day =date('d/M/Y',$date);
				$end_day = date('d/M/Y' ,($date + 6 *24*3600));
				$time_from_day =date('d/m/Y',$date);
				$time_end_day = date('d/m/Y' ,($date + 6 *24*3600));
				$_REQUEST['from_day']=date('d/m/Y',$date);
				$_REQUEST['to_day'] = date('d/m/Y' ,($date + 6 *24*3600));
			}else{
				$from_day =date('d/M/Y',Date_time::to_time(Url::get('from_day')));
				$time_from_day =date('d/m/Y',Date_time::to_time(Url::get('from_day')));
			}
			if(Url::get('to_day')){
				$end_day = date('d/M/Y',Date_time::to_time(Url::get('to_day')));
				$time_end_day = date('d/m/Y' ,Date_time::to_time(Url::get('to_day')));
			}
	   		$total['room_occupice'] = $this->room_occupancy($from_day,$end_day);
			$time_from_date =Date_time::to_time($time_from_day);
			$time_end_date = Date_time::to_time ($time_end_day)+24*3600;
			$time =(($time_end_date - $time_from_date)/(24*3600)) ;
		    $total_room = (DB::fetch('select count(room.id) as total from room ','total')*$time);
			// tinh cong suat phong
			$total['room_ocuupancy']=round(($total['room_occupice']/$total_room)*100,1);
			// tinh tien phong + dv + thue cua cac phong
			$total['rooms'] =$this->get_total_room($time_from_date,$time_end_date);
			// tinh tien mi ni bar and giat la
			$total['mibibar']=$this->get_hk($time_from_date,$time_end_date);
			$total['laundry']=$this->get_laundy($time_from_date,$time_end_date);
			// tinh tien nha hang
			$total['bar'] = $this->get_bar($time_from_date,$time_end_date);
			// tien spa
			$total['spa'] =$this->get_spa($time_from_date,$time_end_date);
			// tinh dich vu mo rong
			$total['extra_service'] = $this->extra_service($time_from_date,$time_end_date);
			// tinh tien phone 
			$total['phone'] =$this->get_phone($time_from_date,$time_end_date);
			// tien dich vu khac 
			$total['orther_service'] = $this->get_orther_service($time_from_date,$time_end_date);
			// tong tat cac tien 
			$total['ammount']=0;
			$orther_service =$this->get_orther_service($time_from_date,$time_end_date);
			
			foreach($total['orther_service']  as $k_o =>$v_o){
				$total['ammount']  += $v_o['service'];
			}
			$total['ammount'] +=$total['rooms'] +$total['bar'] +$total['spa']+$total['extra_service']+$total['phone'] +$total['mibibar']+$total['laundry'] ;
			$view_all = true;
			if(!User::can_view(false,ANY_CATEGORY) and User::can_view_detail(false,ANY_CATEGORY)){
				$_REQUEST['from_day'] = date('d');
				$view_all = false;
			}
			$this->parse_layout('report', $total + array(	
															'from_date'	=>(date('d/m/Y',$time_from_date)),
															'to_date'=>(date('d/m/Y',$time_end_date)),		
															'view_all'=>$view_all,
															'orther_service'=>$orther_service));														
	}
}
?>
