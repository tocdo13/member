<?php
class OccupancyForecastReportNewForm extends Form{
	function OccupancyForecastReportNewForm(){
		Form::Form('OccupancyForecastReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function get_total_room_status($from_date,$to_date){
		$sql='
			SELECT 
			     	count(rs.room_id) as total, rs.in_date, 
					rs.status, rs.house_status,
					concat(rs.in_date,concat(rs.status,rs.house_status))as id
			FROM 
					room_status rs
					left join reservation_room on rs.reservation_room_id = reservation_room.id
			WHERE 
					rs.in_date >= \''.$from_date.'\' AND rs.in_date <=\''.$to_date.'\' 
                    AND 
                    (
                        rs.reservation_id = 0 
                        OR 
                        (
                            reservation_room.status=\'CHECKIN\'
                        )
                    )
			GROUP 
					BY rs.house_status, rs.status, rs.in_date';		
					//reservation_room.status!=\'CHECKOUT\'  and reservation_room.status!=\'BOOKED\' and reservation_room.status!=\'CANCEL\'
		$rooms = DB::fetch_all($sql);
		//System::Debug($rooms);
		$room_occupancy = array();
			 // tinh so phong se tra trong khoang thoi gian ,tinh tong occupiec va repair
		foreach($rooms as $key => $value){
			$room_occupancy['id'] = $value['in_date'];
			if($value['status'] == 'OCCUPIED'){
				if(!isset($room_occupancy[$room_occupancy['id']]['total_status_occupied'])){
					 $room_occupancy[$room_occupancy['id']]['total_status_occupied'] = $value['total'];
				}else{
					$room_occupancy[$room_occupancy['id']]['total_status_occupied'] += $value['total'];
				}
			}
			if($value['house_status'] == 'REPAIR'){
				if(!isset($room_occupancy[$room_occupancy['id']]['total_house_status_repair'])){
					$room_occupancy[$room_occupancy['id']]['total_house_status_repair'] = $value['total'];
				}else{
					 $room_occupancy[$room_occupancy['id']]['total_house_status_repair'] += $value['total'];
				}
			}
            if($value['house_status'] == 'HOUSEUSE'){
				if(!isset($room_occupancy[$room_occupancy['id']]['total_house_status_houseuse'])){
					$room_occupancy[$room_occupancy['id']]['total_house_status_houseuse'] = $value['total'];
				}else{
					 $room_occupancy[$room_occupancy['id']]['total_house_status_houseuse'] += $value['total'];
				}
			}
		}
		// tong so phong se check in va sap check out
		$sql2='
				SELECT 
						count(rr.id) as total,  
						rs.in_date , 
						rs.status, rr.arrival_time, rr.status as reservation_status,
						rr.departure_time,
						(rs.reservation_room_id || rr.arrival_time || rr.departure_time || rs.status || rs.in_date) as id
				FROM 
					room_status rs
					INNER JOIN reservation_room rr on rr.id = rs.reservation_room_id
				WHERE 
					rs.in_date >= \''.$from_date.'\' AND rs.in_date <=\''.$to_date.'\'
				GROUP BY rs.reservation_room_id, rr.arrival_time,rr.departure_time,rs.status,rs.in_date,rr.status';
	  $room_in_outs = DB::fetch_all($sql2) ;
	  $room_arr = array();
	  foreach($room_in_outs as $k => $room){
			if(isset($room_arr[$room['in_date'].$room['reservation_status'].$room['arrival_time'].$room['departure_time']])){
				$room_arr[$room['in_date'].$room['reservation_status'].$room['arrival_time'].$room['departure_time']]['total']++;
			}else{
				$room_arr[$room['in_date'].$room['reservation_status'].$room['arrival_time'].$room['departure_time']] = array();
				$room_arr[$room['in_date'].$room['reservation_status'].$room['arrival_time'].$room['departure_time']]['id'] =$room['id'];
				$room_arr[$room['in_date'].$room['reservation_status'].$room['arrival_time'].$room['departure_time']]['in_date'] = $room['in_date'];
				//$room_arr[$room['in_date'].$room['reservation_status'].$room['arrival_time'].$room['departure_time']]['reservation_status'] = $room['reservation_status'];
				$room_arr[$room['in_date'].$room['reservation_status'].$room['arrival_time'].$room['departure_time']]['status'] = $room['reservation_status'];
				$room_arr[$room['in_date'].$room['reservation_status'].$room['arrival_time'].$room['departure_time']]['total'] = 1;
				$room_arr[$room['in_date'].$room['reservation_status'].$room['arrival_time'].$room['departure_time']]['arrival_time'] = $room['arrival_time'];
				$room_arr[$room['in_date'].$room['reservation_status'].$room['arrival_time'].$room['departure_time']]['departure_time'] = $room['departure_time'];
				$room_arr[$room['in_date'].$room['reservation_status'].$room['arrival_time'].$room['departure_time']]['reservation_status'] = $room['reservation_status'];
			}
	  }
	  $room_in_outs = array();
	  $room_in_outs = $room_arr;
	//System::Debug($room_in_outs);
	  $total_userday=0;
	  foreach($room_in_outs as $key=>$value){
		if(isset($room_occupancy[$value['in_date']])){
			if($value['status'] =='BOOKED'){
				if($value['in_date']==$value['arrival_time']){
					if(!isset($room_occupancy[$value['in_date']]['total_will_booked'])){
							$room_occupancy[$value['in_date']]['total_will_booked']= $value['total'];
					}else{
						$room_occupancy[$value['in_date']]['total_will_booked'] += $value['total'];
					}
				}
				if($value['in_date']==$value['departure_time']){
					if(!isset($room_occupancy[$value['in_date']]['total_book_will_checkout'])){
						$room_occupancy[$value['in_date']]['total_book_will_checkout']= $value['total'];
					}else{
						$room_occupancy[$value['in_date']]['total_book_will_checkout'] += $value['total'];
					}	
				}
				if($value['in_date'] != $value['departure_time'] && $value['in_date'] !=$value['arrival_time'] && $value['arrival_time'] != $value['departure_time']){
					if(!isset($room_occupancy[$value['in_date']]['total_status_occupied'])){
						 $room_occupancy[$value['in_date']]['total_status_occupied'] = $value['total'];
					}else{
						$room_occupancy[$value['in_date']]['total_status_occupied'] += $value['total'];
					}
					//echo $value['in_date'].'==='.$value['arrival_time'].'-'.$value['departure_time'].'stt'.$room_occupancy[$value['in_date']]['total_status_book_occupied'].'<br>';
					//echo $value['in_date'].'--'.$room_occupancy[$value['in_date']]['total_status_book_occupied'].'<br>';
				}
			}
			if($value['status'] =='CHECKIN'){
				if($value['in_date']==$value['departure_time']){
					//if($value['arrival_time']!=$value['departure_time']){
						if(!isset($room_occupancy[$value['in_date']]['total_will_checkout'])){
							$room_occupancy[$value['in_date']]['total_will_checkout']= $value['total'];
						}else{
							$room_occupancy[$value['in_date']]['total_will_checkout'] += $value['total'];
						}
					//}
				}
				if($value['in_date']==$value['arrival_time']){
					if(!isset($room_occupancy[$value['in_date']]['total_userday'])){
						$room_occupancy[$value['in_date']]['total_userday'] = $value['total'];
					}else{
						$room_occupancy[$value['in_date']]['total_userday'] += $value['total'];
					}
				}	
			}
			if($value['status'] =='CHECKOUT'){
				if($value['in_date']==$value['departure_time']){
					if(!isset($room_occupancy[$value['in_date']]['total_has_checkout_in_day'])){
							$room_occupancy[$value['in_date']]['total_has_checkout_in_day']= $value['total'];
					}else{
							$room_occupancy[$value['in_date']]['total_has_checkout_in_day'] += $value['total'];
					}
				}
				//if(Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['arrival_time'],'/')) < Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['departure_time'],'/')) && Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['arrival_time'],'/')) < Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['in_date'],'/'))){
				if($value['in_date']!=$value['departure_time'] && $value['arrival_time'] != $value['departure_time'] && $value['arrival_time'] != $value['in_date']){
					if(!isset($room_occupancy[$value['in_date']]['total_has_checkout'])){
							$room_occupancy[$value['in_date']]['total_has_checkout']= $value['total'];
					}else{
							$room_occupancy[$value['in_date']]['total_has_checkout'] += $value['total'];
					}	
				}
				if($value['in_date'] == $value['arrival_time']){
					if(!isset($room_occupancy[$value['in_date']]['total_has_checkout_arrival'])){
							$room_occupancy[$value['in_date']]['total_has_checkout_arrival']= $value['total'];
					}else{
							$room_occupancy[$value['in_date']]['total_has_checkout_arrival'] += $value['total'];
					}	
				}
			}
			if($value['status']=='CANCEL'){
				if(!isset($room_occupancy[$value['in_date']]['total_cancel'])){
							$room_occupancy[$value['in_date']]['total_cancel']= $value['total'];
					}else{
							$room_occupancy[$value['in_date']]['total_cancel'] += $value['total'];
					}
			}
		 }
	  }
	 // System::Debug($room_occupancy);
		 return $room_occupancy;
	}
	// tinh tong tien phong theo ngay 
	function get_amout_room($date_from,$date_end){
		$sql1 = '
		        SELECT 
						rs.id,
						rs.change_price,
						rr.tax_rate, 
						service_rate,
						rs.in_date,
						rr.arrival_time,
						rr.departure_time,
						rr.price,
						rs.status,
						rr.id as reservation_room_id
					FROM 
						room_status rs 
						INNER JOIN reservation_room rr ON rr.id = rs.reservation_room_id 
						INNER JOIN reservation r on rr.reservation_id = r.id
					WHERE 
						(rs.status =\'OCCUPIED\' OR rs.status =\'BOOKED\')
						AND rs.in_date >= \''.$date_from.'\' 
						AND rs.in_date <= \''.$date_end.'\' ';
		$room_totals = DB::fetch_all($sql1);
		//echo $sql1;	
		/*
		$deposit= DB::fetch_all('
		 	SELECT 
				sum(deposit) as deposit, rr.id , deposit_date
			FROM 
				reservation_room rr 
			WHERE deposit_date <= \''.$date_end.'\'
			GROUP BY rr.id,deposit_date
			');
			// tinh depsit theo ngay 
			$deposit_date= DB::fetch_all('
		 	SELECT 
				sum(deposit) as deposit,deposit_date as id
			FROM 
				reservation_room rr 
			WHERE 
				deposit_date <= \''.$date_end.'\' AND deposit_date >=\''.$date_from.'\'
			GROUP BY deposit_date
			');*/
			// tinh tien phong su dung trong ngay
			$amount_room_days =array();
		foreach($room_totals as $key=>$value){
			// tinh tien phong va phi dich phong thue cho tung phong rui cong thanh tog tien trong ngay
				$amount_room_days['id'] = $value['in_date'];
				$service_room = 0;
				$pay_room = 0;
				$tax_room = 0;
				$deposit_room = 0;
				$net=0;
				if(($value['arrival_time'] != $value['departure_time'] && $value['in_date'] != $value['departure_time'] && $value['status']=='OCCUPIED') || ($value['arrival_time']==$value['in_date'])){
					$service_room = ($value['change_price'] * $value['service_rate'])/100;
					// tien phong  + dv 1 phong
					$pay_room  = $value['change_price'] + $service_room ;	
					// tinh tien thue cua 1 phong 
					$tax_room = ($pay_room * $value['tax_rate'])/100;	
					$net = $pay_room + $tax_room;
				}
			/*
			foreach($deposit as $k_d=>$v_d){
				if($v_d['id'] == $value['reservation_room_id']){
				$date_deposit =Date_Time::convert_orc_date_to_date($v_d['deposit_date'],'/');
				$time_date_deposit=Date_Time::to_time($date_deposit);
				$date_inday =Date_Time::convert_orc_date_to_date($value['in_date'],'/');
				$time_date_in_day =Date_Time::to_time($date_inday);	
				if($time_date_deposit <= $time_date_in_day){
				   $deposit_room = $v_d['deposit'];
				   unset($deposit[$k_d]);
				}
			 }
			}*/
			$room_totals[$key]['room_total'] = $net;// - $deposit_room;
			//if(Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['arrival_time'],'/')) < Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['departure_time'],'/')) && Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['arrival_time'],'/')) < Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['in_date'],'/'))){
				if(!isset($amount_room_days[$value['in_date']]['amount_total'])){	
					 $amount_room_days[$value['in_date']]['amount_total'] = $room_totals[$key]['room_total'];
				}else{
					 $amount_room_days[$value['in_date']]['amount_total'] += $room_totals[$key]['room_total'];
				}
			//}
			/*
			// cong tien deposit trong ngay
			if(isset($deposit_date[$value['in_date']])){
				$amount_room_days[$value['in_date']]['amount_total'] += $deposit_date[$value['in_date']]['deposit'];
				unset($deposit_date[$value['in_date']]);
			}*/
		}
		return $amount_room_days;
	}
	function draw()
    {
        $from_day =(Url::get('from_day'))?Date_Time::to_orc_date(Url::get('from_day')):date('d/M/Y');
        $end_day = (Url::get('to_day'))?Date_Time::to_orc_date(Url::get('to_day')): date('d/M/Y',(Date_Time::to_time(date('d/m/Y')) + 7*24*3600));
        $rooms= $this->get_total_room_status($from_day,$end_day);
        //System::debug($rooms);
        $time_from_day =(Url::get('from_day'))?Date_Time::to_time(Url::get('from_day')):(Date_Time::to_time(date('d/m/Y')));
        $time_to_day =(Url::get('to_day'))?Date_Time::to_time(Url::get('to_day')):(Date_Time::to_time(date('d/m/Y')) + 7*24*3600);
        $total_room = DB::fetch('Select count(room.id) as total from room_level inner join room on room_level.id = room.room_level_id Where room.portal_id = \''.PORTAL_ID.'\' and room_level.is_virtual = 0  ','total');
        $amount_room= $this->get_amout_room($from_day,$end_day);
        $items= array();
        $rooms_avrrial =0;
        $rooms_repair =0;
        $rooms_houseuse = 0;
        $total_occ=0;
        $total_ammount_room=0;
        $total_cancel=0;
		$total_arrival = 0;
		$total_arrival_occ = 0;
		$total_out = 0;
		$percent_avg = 0; $k=0;
        for($i=$time_from_day ; $i<= $time_to_day; $i +=24*3600)
        {  
			$k++;
            $items[date('d/m/Y',$i)]['id']=date('d/m/Y',$i);
            $items[date('d/m/Y',$i)]['total_avail_room']= $total_room;
            $rooms_avrrial +=$total_room;
            $items[date('d/m/Y',$i)]['ooo']=0;
            $items[date('d/m/Y',$i)]['pax']=0;
            $items[date('d/m/Y',$i)]['oc']=0;
            $items[date('d/m/Y',$i)]['arr_room']=0;
            $items[date('d/m/Y',$i)]['dpt']=0;
            $items[date('d/m/Y',$i)]['cp']=0;
            $items[date('d/m/Y',$i)]['hu']=0;
            $items[date('d/m/Y',$i)]['room_rev']=0;
            $items[date('d/m/Y',$i)]['avg_rm']=0;
            $items[date('d/m/Y',$i)]['rm_rev_par']=0;
            $items[date('d/m/Y',$i)]['cancel']=0;
            $items[date('d/m/Y',$i)]['total_occ']=0;
            $date_to_oracle = Date_Time::to_orc_date(date('d/m/y',$i));
        	if(isset($rooms[$date_to_oracle]))
            {
        		if(isset($rooms[$date_to_oracle]['total_house_status_repair']))
                {
        			$items[date('d/m/Y',$i)]['ooo']=$rooms[$date_to_oracle]['total_house_status_repair'];
        			$rooms_repair +=$rooms[$date_to_oracle]['total_house_status_repair'];
        		}
                if(isset($rooms[$date_to_oracle]['total_house_status_houseuse']))
                {
        			$items[date('d/m/Y',$i)]['hu']=$rooms[$date_to_oracle]['total_house_status_houseuse'];
        			$rooms_houseuse +=$rooms[$date_to_oracle]['total_house_status_houseuse'];
        		}
        		if(isset($rooms[$date_to_oracle]['total_will_checkout']))
                {
        			$items[date('d/m/Y',$i)]['dpt']=$rooms[$date_to_oracle]['total_will_checkout'];
        		}
        		if(isset($rooms[$date_to_oracle]['total_status_occupied']))
                {
        		   $items[date('d/m/Y',$i)]['total_occ']=$rooms[$date_to_oracle]['total_status_occupied'] - $items[date('d/m/Y',$i)]['dpt'];
        	    }
				if(isset($rooms[$date_to_oracle]['total_book_will_checkout']))
                {
        			$items[date('d/m/Y',$i)]['dpt'] +=$rooms[$date_to_oracle]['total_book_will_checkout'];
        		}
				if(isset($rooms[$date_to_oracle]['total_has_checkout']))
                {
        			$items[date('d/m/Y',$i)]['total_occ'] += $rooms[$date_to_oracle]['total_has_checkout'];
        		}
				if(isset($rooms[$date_to_oracle]['total_has_checkout_in_day']))
                {
        			$items[date('d/m/Y',$i)]['dpt'] +=$rooms[$date_to_oracle]['total_has_checkout_in_day'];
        		}
                if(isset($rooms[$date_to_oracle]['total_userday']))
                {
					//echo $date_to_oracle.'-useday-'.$rooms[$date_to_oracle]['total_userday'].'<br>';
					$items[date('d/m/Y',$i)]['arr_room']=$rooms[$date_to_oracle]['total_userday'];
        			$items[date('d/m/Y',$i)]['total_occ'] -=$rooms[$date_to_oracle]['total_userday'];
        		}
        		if(isset($rooms[$date_to_oracle]['total_will_booked']))
                {
        			$items[date('d/m/Y',$i)]['arr_room']+=$rooms[$date_to_oracle]['total_will_booked'];
        		}
                if(isset($rooms[$date_to_oracle]['total_has_checkout_arrival']))
                {
        			$items[date('d/m/Y',$i)]['arr_room']+=$rooms[$date_to_oracle]['total_has_checkout_arrival'];
        		}
				if($items[date('d/m/Y',$i)]['dpt']){
					$total_out += $items[date('d/m/Y',$i)]['dpt'];	
				}
				if($items[date('d/m/Y',$i)]['arr_room']){
					$total_arrival += $items[date('d/m/Y',$i)]['arr_room'];	
				}
				$total_arrival_occ += $items[date('d/m/Y',$i)]['total_occ'] + $items[date('d/m/Y',$i)]['arr_room'];
                $total_occ +=$items[date('d/m/Y',$i)]['total_occ'];
        		$items[date('d/m/Y',$i)]['oc']= round(( ( $items[date('d/m/Y',$i)]['total_occ'] + $items[date('d/m/Y',$i)]['arr_room']  ) /($total_room - (isset($rooms[$date_to_oracle]['total_house_status_repair'])?$rooms[$date_to_oracle]['total_house_status_repair']:0) - (isset($rooms[$date_to_oracle]['total_house_status_houseuse'])?$rooms[$date_to_oracle]['total_house_status_houseuse']:0)))*100,2);
        		if(isset($rooms[$date_to_oracle]['total_cancel']))
                {
        			$items[date('d/m/Y',$i)]['cancel']=$rooms[$date_to_oracle]['total_cancel'];
        			$total_cancel +=$rooms[$date_to_oracle]['total_cancel'];
        		}
				$percent_avg += $items[date('d/m/Y',$i)]['oc'];
        	}
        	// tinh tien phong theo ngay
        	if(isset($amount_room[$date_to_oracle]))
            {
        		$items[date('d/m/Y',$i)]['room_rev'] = System::Display_number($amount_room[$date_to_oracle]['amount_total']);
        		$total_ammount_room +=$amount_room[$date_to_oracle]['amount_total'];
        		// tinh gia phong trung binh
        		if($items[date('d/m/Y',$i)]['total_occ']!=0 || $items[date('d/m/Y',$i)]['arr_room'] != 0)
        		{
                    $items[date('d/m/Y',$i)]['avg_rm'] = System::Display_number(round(($amount_room[$date_to_oracle]['amount_total']/($items[date('d/m/Y',$i)]['total_occ']+$items[date('d/m/Y',$i)]['arr_room'])),1));
        		}
        	}
        }
        //$percent_occ = round(($total_occ/$rooms_avrrial)*100,1);
		$percent_occ = round($percent_avg/$k,2);
        $avg_room=0;
        if($total_occ!=0)
        {
            $avg_room=System::Display_number( round(($total_ammount_room/$total_occ),1));
        }
        $this->parse_layout('report',array('items'=>$items,
            'rooms_avrrial'=>$rooms_avrrial,
            'rooms_repair'=>$rooms_repair,
            'total_occ'=>$total_occ,
            'percent_occ'=>$percent_occ,
            'total_cancel'=>$total_cancel,
			'total_out'=>$total_out,
			'total_arrival_occ'=>$total_arrival_occ,
			'total_arrival'=>$total_arrival,   
            'total_ammount_room'=> System::display_number($total_ammount_room),
            'avg_room'=>$avg_room,
            'from_date'=>date('d/m/Y',$time_from_day),
            'to_date'=>date('d/m/Y',$time_to_day))
            );	
    }
}
?>