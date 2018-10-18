<?php
class OccupancyForecastReportForm extends Form{
	function OccupancyForecastReportForm(){
		Form::Form('OccupancyForecastReportForm');
                $this->link_js('packages/core/includes/js/jquery/datepicker.js'); 
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		
                $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function get_total_room_status($from_date,$to_date,$portal_id,$cond_confirm)
    {
		 
        $portal_id_1 =' AND 0 = 0';
        if(Url::get('portal_id'))
        {
            if(Url::get('portal_id') !='ALL')
                $portal_id_1 .= ' AND room.portal_id=\''.Url::get('portal_id').'\' or room.portal_id is null';
            else
                $portal_id_1 .= ' AND 1 = 1';
        }
        $sql='
			SELECT 
			     	count(rs.room_id) as total, 
                    rs.in_date, 
					rs.status, 
                    rs.house_status,
					concat(rs.in_date,concat(rs.status,rs.house_status))as id
			FROM 
					room_status rs
                    INNER JOIN room ON rs.room_id = room.id
			WHERE 
					DATE_TO_UNIX(rs.in_date) >= DATE_TO_UNIX(\''.$from_date.'\') AND DATE_TO_UNIX(rs.in_date) <=DATE_TO_UNIX(\''.$to_date.'\')
                    and rs.status != \'CANCEL\' and rs.status != \'NOSHOW\'
                    and rs.house_status is not null
                    '.$portal_id_1.'
			GROUP 
					BY rs.house_status,
                    rs.status, 
                    rs.in_date';
		$rooms = DB::fetch_all($sql);
		$room_occupancy = array();
			 // tinh so phong se tra trong khoang thoi gian ,tinh tong occupiec va repair
		foreach($rooms as $key => $value)
        {   //System::debug($value);
			$room_occupancy['id'] = $value['in_date'];
			if($value['house_status'] == 'REPAIR')
            {
				if(!isset($room_occupancy[$room_occupancy['id']]['total_house_status_repair']))
                {
					$room_occupancy[$room_occupancy['id']]['total_house_status_repair'] = $value['total'];
                    
                }
                else
                {
					 $room_occupancy[$room_occupancy['id']]['total_house_status_repair'] += $value['total'];
                    
				}
			}
            if($value['house_status'] == 'HOUSEUSE')
            {
				if(!isset($room_occupancy[$room_occupancy['id']]['total_house_status_houseuse']))
                {
					$room_occupancy[$room_occupancy['id']]['total_house_status_houseuse'] = $value['total'];
				}
                else
                {
					 $room_occupancy[$room_occupancy['id']]['total_house_status_houseuse'] += $value['total'];
				}
			}
		}
        //so phong foc + foc_all.
		// tong so phong se check in va sap check out
		$sql2='
				SELECT 
						count(rr.id) as total,  
						rs.in_date,
                        date_to_unix(rs.in_date)+(6*3600) as time6h_indate,
						rs.status, 
                        rr.arrival_time, 
                        rr.status as reservation_status,
						rr.departure_time,
                        rr.time_in,
                        nvl(room_level.is_virtual,0) as is_virtual,
                        nvl(rr.change_room_from_rr,0) as change_room_from_rr,
                        nvl(rr.change_room_to_rr,0) as change_room_to_rr,
                        from_unixtime(rr.old_arrival_time) as old_arrival_time,
                        rr.old_arrival_time as time_old,
                        reservation.id as r_id,
                        (rs.reservation_room_id || rr.arrival_time || rr.departure_time || rs.status || rs.in_date || rr.change_room_from_rr || rr.change_room_to_rr || rr.old_arrival_time) as id
				FROM 
					room_status rs
                    inner join reservation on rs.reservation_id = reservation.id
					INNER JOIN reservation_room rr on rr.id = rs.reservation_room_id
                    left join room_level on room_level.id = rr.room_level_id
                    left join room on rs.room_id = room.id
				WHERE 
					DATE_TO_UNIX(rs.in_date) >= DATE_TO_UNIX(\''.$from_date.'\') 
                    AND DATE_TO_UNIX(rs.in_date) <=DATE_TO_UNIX(\''.$to_date.'\')
                    AND (room_level.is_virtual is null or room_level.is_virtual = 0 )
                    '.$portal_id.$cond_confirm.'
				  and rr.status != \'CANCEL\' and rr.status != \'NOSHOW\'
                GROUP BY rs.reservation_room_id, rr.arrival_time,rr.departure_time,rs.status,rs.in_date,rr.status,rr.change_room_from_rr,rr.change_room_to_rr,rr.old_arrival_time,rr.time_in,reservation.id,is_virtual';
                
	  $room_in_outs = DB::fetch_all($sql2) ;
       /** kieu fix TH ngay 08-06-2016(báo cáo không khop với báo cáo chiem dung phong)) **/
        $reservation_virtual=DB::fetch_all('select reservation_room.id from reservation_room
                                                inner join room on reservation_room.room_id=room.id
                                                inner join room_level on room_level.id=room.room_level_id
                                            where
                                                room_level.is_virtual=1
                                                and reservation_room.change_room_to_rr is not null
                                            ');
        foreach($room_in_outs as $key=>$value)
		{
		     if(isset($reservation_virtual[$value['change_room_from_rr']])){
		      unset($room_in_outs[$key]);
		     }
        }                                    
      /** END kieu fix TH ngay 08-06-2016(báo cáo không khop với báo cáo chiem dung phong)) **/                                        
	  $room_arr = array();
	  $total_userday=0;
	  foreach($room_in_outs as $key=>$value)
      {
        
		if(!isset($room_occupancy[$value['in_date']]))
        {
            $room_occupancy[$value['in_date']] = array();
        }
        if(1==1)
        {
			if($value['reservation_status'] =='BOOKED')
            {
				if($value['in_date'] != $value['departure_time'] and $value['in_date'] !=$value['arrival_time'] and $value['arrival_time'] != $value['departure_time'])
                {
					if(!isset($room_occupancy[$value['in_date']]['total_status_occupied']))
                    {
						 $room_occupancy[$value['in_date']]['total_status_occupied'] = $value['total'];
					}
                    else
                    {
						$room_occupancy[$value['in_date']]['total_status_occupied'] += $value['total'];
					}
				}
			}
			if($value['reservation_status'] =='CHECKIN')
            {
				if($value['in_date']==$value['arrival_time'])
                {
					if(!isset($room_occupancy[$value['in_date']]['total_userday']))
                    {
						$room_occupancy[$value['in_date']]['total_userday'] = $value['total'];
					}
                    else
                    {
						$room_occupancy[$value['in_date']]['total_userday'] += $value['total'];
					}
				}
			}
			if($value['reservation_status']=='CANCEL')
            {
				if(!isset($room_occupancy[$value['in_date']]['total_cancel']))
                {
				    $room_occupancy[$value['in_date']]['total_cancel']= $value['total'];
				}
                else
                {
				    $room_occupancy[$value['in_date']]['total_cancel'] += $value['total'];
				}
			}
            if($value['in_date'] == $value['departure_time'] && $value['change_room_to_rr']!=0 && $value['time_in']>=$value['time6h_indate'])
            {
               if(!isset($room_occupancy[$value['in_date']]['total_change_room_dept']))
                {
   				 $room_occupancy[$value['in_date']]['total_change_room_dept']= $value['total'];
				}
                else
                {
				    $room_occupancy[$value['in_date']]['total_change_room_dept'] += $value['total'];
				}
            }
         // end KimTan xu ly trương hop doi phong  
             if($value['reservation_status']!='CANCEL' and $value['reservation_status']!='NOSHOW') 
             {
                if($value['in_date']==$value['arrival_time'] && $value['time_in']>=$value['time6h_indate'] && $value['change_room_from_rr']==0 && $value['change_room_to_rr']==0)//phòng den kimtan ngay 07/07/15
                {
					if(!isset($room_occupancy[$value['in_date']]['total_arrival_room']))
                    {
							$room_occupancy[$value['in_date']]['total_arrival_room']= $value['total'];
					}
                    else
                    {
						$room_occupancy[$value['in_date']]['total_arrival_room'] += $value['total'];
					}
				}
                //kimtan:: them ngay 18/1/16
                if($value['change_room_from_rr'] == 0 && $value['change_room_to_rr']!=0 and date('d/m/Y',$value['time_in'])!= date('d/m/Y',$value['time_old']) and $value['time_old'] > $value['time6h_indate'] and Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['in_date'],'/'))<Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['departure_time'],'/')) )
                {
                    if(!isset($room_occupancy[$value['in_date']]['total_arrival_room']))
                    {
							$room_occupancy[$value['in_date']]['total_arrival_room']= $value['total'];
					}
                    else
                    {
						$room_occupancy[$value['in_date']]['total_arrival_room'] += $value['total'];
					}
                }
                //kimtan:: them ngay 18/1/16
                if
                (
                    ( $value['time_in'] < $value['time6h_indate'] and Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['in_date'],'/'))<Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['departure_time'],'/')) )
                    or
                    ($value['change_room_from_rr'] != 0 and $value['time_old'] < $value['time6h_indate'] and Date_Time::convert_orc_date_to_date($value['arrival_time'],'/')==date('d/m/Y',$value['time_in']) and Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['in_date'],'/'))<Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['departure_time'],'/')) )
                )
                {
                   if(!isset($room_occupancy[$value['in_date']]['occ_day']))
                    {
						$room_occupancy[$value['in_date']]['occ_day'] = $value['total'];
					}
                    else
                    {
					   $room_occupancy[$value['in_date']]['occ_day'] += $value['total'];
					} 
                }
                if($value['in_date']==$value['departure_time'] and $value['change_room_to_rr']==0)
                {
					if(!isset($room_occupancy[$value['in_date']]['total_has_checkout_in_day']))
                    {
							$room_occupancy[$value['in_date']]['total_has_checkout_in_day']= $value['total'];
					}
                    else
                    {
							$room_occupancy[$value['in_date']]['total_has_checkout_in_day'] += $value['total'];
					}
				}    
             }  
         }
	  }
      //tính phòng FOC.
      $foc_orc = '
            SELECT
                room_status.in_date as in_date,
                rr.foc as foc,
                rr.foc_all as foc_all,
                concat(room_status.reservation_room_id,room_status.in_date) as id
            FROM
                room_status
                INNER JOIN reservation  on room_status.reservation_id = reservation.id
                inner join reservation_room rr on rr.id = room_status.reservation_room_id
                inner join room_level on room_level.id = rr.room_level_id
                left join room on room_status.room_id = room.id
            WHERE
                DATE_TO_UNIX(room_status.in_date) >=DATE_TO_UNIX(\''.$from_date.'\') 
                AND DATE_TO_UNIX(room_status.in_date) <=DATE_TO_UNIX(\''.$to_date.'\')'.$portal_id.' 
                AND (room_level.is_virtual is null or room_level.is_virtual = 0 ) 
                and rr.status != \'CANCEL\' and rr.status != \'NOSHOW\'
                and room_status.in_date != rr.departure_time
                '.$cond_confirm.'
                --and room_status.change_price != 0   
      ';
      $foc_room = DB::fetch_all($foc_orc);
      
      foreach($foc_room as $key_foc=>$value_foc){
        if(!empty($value_foc['foc'])){
           if(!isset($room_occupancy[$value_foc['in_date']]['foc'])){
            $room_occupancy[$value_foc['in_date']]['foc'] = 1;
           }else{
            $room_occupancy[$value_foc['in_date']]['foc'] += 1;
           }
        }
        if(empty($value_foc['foc']) and $value_foc['foc_all'] != 0){
           if(!isset($room_occupancy[$value_foc['in_date']]['foc_all'])){
            $room_occupancy[$value_foc['in_date']]['foc_all'] = 1;
            //echo 3;
           }else{
            $room_occupancy[$value_foc['in_date']]['foc_all'] += 1;
            //echo 4;
           }
        }
      }    
		 return $room_occupancy;
	}
    //tinh foc
	// tinh tong tien phong theo ngay 
	function get_amout_room($date_from,$date_end,$portal_id,$cond_confirm)
    {
		$sql1 = '
		        SELECT 
						rs.id,
						case
                        when rs.in_date = rr.arrival_time
                        then 
                            (case
                             when rr.net_price = 0
                             then ((CHANGE_PRICE*(1-NVL(rr.REDUCE_BALANCE,0)/100.0)-NVL(rr.REDUCE_AMOUNT,0))*(1+NVL(rr.SERVICE_RATE,0)/100.0))*(1 + NVL(rr.TAX_RATE,0)/100.0)
                             else
                              ((((CHANGE_PRICE/(1+NVL(rr.SERVICE_RATE,0)/100.0))/(1 + NVL(rr.TAX_RATE,0)/100.0))*(1-NVL(rr.REDUCE_BALANCE,0)/100.0)-NVL(rr.REDUCE_AMOUNT,0))*(1+NVL(rr.SERVICE_RATE,0)/100.0))*(1 + NVL(rr.TAX_RATE,0)/100.0)
                            end) 
                        else
                            (case
                             when rr.net_price = 0
                             then (CHANGE_PRICE*(1-NVL(rr.REDUCE_BALANCE,0)/100.0)*(1+NVL(rr.SERVICE_RATE,0)/100.0))*(1 + NVL(rr.TAX_RATE,0)/100.0)
                             else
                              ((((CHANGE_PRICE/(1+NVL(rr.SERVICE_RATE,0)/100.0))/(1 + NVL(rr.TAX_RATE,0)/100.0))*(1-NVL(rr.REDUCE_BALANCE,0)/100.0))*(1+NVL(rr.SERVICE_RATE,0)/100.0))*(1 + NVL(rr.TAX_RATE,0)/100.0)
                            end)
                        end as change_price,
						rr.tax_rate, 
						service_rate,
						rs.in_date,
						rr.arrival_time,
                        rr.net_price,
						rr.departure_time,
						rr.price,
						rs.status,
						rr.id as reservation_room_id
					FROM 
						room_status rs 
						INNER JOIN reservation_room rr ON rr.id = rs.reservation_room_id 
						INNER JOIN reservation on rr.reservation_id = reservation.id
                        inner join room_level on room_level.id = rr.room_level_id
					WHERE 
						(rs.status =\'OCCUPIED\' OR rs.status =\'BOOKED\')
                        AND (room_level.is_virtual is null or room_level.is_virtual = 0 )
                        AND rr.foc is null
                        AND rr.foc_all = 0
                        AND (rs.in_date < rr.DEPARTURE_TIME OR  rr.DEPARTURE_TIME = rr.ARRIVAL_TIME)
						AND rs.in_date >= \''.$date_from.'\' 
						AND rs.in_date <= \''.$date_end.'\'
                       '.$portal_id.$cond_confirm.'';
		$room_totals = DB::fetch_all($sql1);
		// tinh tien phong su dung trong ngay
		$amount_room_days =array();
		foreach($room_totals as $key=>$value)
        {
			// tinh tien phong va phi dich phong thue cho tung phong rui cong thanh tog tien trong ngay
			$amount_room_days['id'] = $value['in_date'];
			$service_room = 0;
			$pay_room = 0;
			$tax_room = 0;
			$deposit_room = 0;
			$net=0;
            $net = $value['change_price'];
            
			$room_totals[$key]['room_total'] = $net;// - $deposit_room;
            
			if(!isset($amount_room_days[$value['in_date']]['amount_total']))
            {
                 $amount_room_days[$value['in_date']]['count'] = 1; 
                 if ($net == 0)
                 {
                    $amount_room_days[$value['in_date']]['count'] = 0;
                 }
				 $amount_room_days[$value['in_date']]['amount_total'] = $room_totals[$key]['room_total'];
			}
            else
            {
                 $amount_room_days[$value['in_date']]['count'] += 1;
                 if ($net == 0)
                 {
                    $amount_room_days[$value['in_date']]['count'] -= 1;
                 }
				 $amount_room_days[$value['in_date']]['amount_total'] += $room_totals[$key]['room_total'];
			}
		}
		return $amount_room_days;
	}
    function get_ei_lo_li($date_from,$date_end,$portal_id,$cond_confirm,$type=false)
    {
        $portal_id_2 =' AND 0 = 0';
        if(Url::get('portal_id'))
        {
            if(Url::get('portal_id') !='ALL')
                $portal_id_2 .= ' AND EXTRA_SERVICE_INVOICE.portal_id=\''.Url::get('portal_id').'\'';
            else
                $portal_id_2 .= ' AND 1 = 1';
        }
        $cond='';
        if($type)
        {
            $cond.=' and  extra_service.code=\'LATE_CHECKIN\'';
        }
        else
        {
            $cond.='and (extra_service.code=\'EARLY_CHECKIN\'  or extra_service.code=\'LATE_CHECKOUT\' or  extra_service.code=\'EXTRA_BED\' or  extra_service.code=\'EXTRAPERSON\')';
        }
        $sql = 'select
                    esid.id,
                    esid.quantity+nvl(esid.change_quantity,0) as quantity,
                    esid.price,
                    esid.in_date,
                    rr.foc, 
                    rr.foc_all, 
                    extra_service_invoice.tax_rate,
                    
                    CASE
            			WHEN 
            				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
            			THEN
            				((esid.quantity+nvl(esid.change_quantity,0))*esid.price) + (((esid.quantity+nvl(esid.change_quantity,0))*esid.price)*extra_service_invoice.service_rate*0.01) + ((((esid.quantity+nvl(esid.change_quantity,0))*esid.price) + (((esid.quantity+nvl(esid.change_quantity,0))*esid.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
            			ELSE
            				((esid.quantity+nvl(esid.change_quantity,0))*esid.price)
                    END as total_amount,
                    extra_service_invoice.service_rate,
                    extra_service.code,
                    EXTRA_SERVICE_INVOICE.payment_type
                from
                    extra_service_invoice_detail esid
                    inner join extra_service on esid.service_id = extra_service.id
                    inner join extra_service_invoice on extra_service_invoice.id = esid.invoice_id
                    inner join reservation_room rr on rr.id = extra_service_invoice.reservation_room_id
                    inner join reservation on rr.reservation_id = reservation.id
                    left join room_level on rr.room_level_id = room_level.id
                where
                    room_level.is_virtual = 0
                    AND rr.status != \'CANCEL\' AND rr.status != \'NOSHOW\'
                    --them ngay 19/11/15 cho dong bo ecolo
                    '.$cond.'
                    --them ngay 19/11/15 cho dong bo ecolo
                    AND esid.in_date >= \''.$date_from.'\' 
				    AND esid.in_date <= \''.$date_end.'\'
                     '.$portal_id_2.$cond_confirm.'';
        //System::debug($sql);
        $items = DB::fetch_all($sql);
        //System::debug($items);
        $ei_lo_li = array();
        if (!empty($items))
        {
            foreach ($items as $key => $value)
            {
                //KimTan21/01/15: neu la ei lo li thi khong can tra ve phong van tinh doanh thu va dem phong ei lo li. neu ko phai thi neu co tra ve phong se tinh doanh thu cho phong nhưng ko dc dem so luong
                if($value['code']=='EARLY_CHECKIN' or $value['code']== 'LATE_CHECKOUT' or $value['code']== 'LATE_CHECKIN' or $value['code']== 'EXTRA_BED')
                {
                    $items[$key]['quantity_ei'] = $value['quantity'];
                    if($items[$key]['foc_all']==0)
                    {
                        $items[$key]['total_amount_ei'] = $value['total_amount'];
                    }
                    else
                    {
                        $items[$key]['total_amount_ei'] = 0;
                    }
                }
                else{
                    $items[$key]['quantity_ei'] = 0;
                    if($value['payment_type']=='ROOM')
                    {
                        $items[$key]['total_amount_ei'] = $value['total_amount'];
                    }
                    else
                    {
                        $items[$key]['total_amount_ei'] = 0;
                    }
                }
                //End KimTan 21/01/15 neu la ei lo li thi
                if (!isset($ei_lo_li[$value['in_date']]))
                {
                    $ei_lo_li[$value['in_date']]['total_amount'] = $items[$key]['total_amount_ei'];
                    $ei_lo_li[$value['in_date']]['quantity'] = $items[$key]['quantity_ei'];
                    
                }
                else
                {
                    $ei_lo_li[$value['in_date']]['total_amount'] += $items[$key]['total_amount_ei'];
                    $ei_lo_li[$value['in_date']]['quantity'] += $items[$key]['quantity_ei'];
                }
            }
        }
        //System::debug($ei_lo_li);
        return $ei_lo_li;
    }
	function draw()
    {
        $from_day =(Url::get('from_day'))?Date_Time::to_orc_date(Url::get('from_day')):date('d/M/Y');
        $end_day = (Url::get('to_day'))?Date_Time::to_orc_date(Url::get('to_day')): date('d/M/Y',(Date_Time::to_time(date('d/m/Y')) + 7*24*3600));
        $portal_id =' AND 0 = 0';
        if(Url::get('portal_id'))
        {
            if(Url::get('portal_id') !='ALL')
                $portal_id .= ' AND reservation.portal_id=\''.Url::get('portal_id').'\'';
            else
                $portal_id .= ' AND 1 = 1';
        }
        $portal_id_1 =' AND 0 = 0';
        if(Url::get('portal_id'))
        {
            if(Url::get('portal_id') !='ALL')
                $portal_id_1 .= ' AND room.portal_id=\''.Url::get('portal_id').'\' or room.portal_id is null';
            else
                $portal_id_1 .= ' AND 1 = 1';
        }
        
        /** kimtan : them 24/5/17 dieu kien phong da xac nhan hay chua **/
        $cond_confirm = ' AND 1=1';
        if(Url::get('status'))
        {
            if(Url::get('status') != 'ALL')
            {
                if(Url::get('status') == 'CF')
                $cond_confirm .= ' and rr.confirm = 1';
                else
                $cond_confirm .= ' and rr.confirm = 0';
            }
        }
        /** kimtan : them 24/5/17 **/
        $rooms= $this->get_total_room_status($from_day,$end_day,$portal_id,$cond_confirm);
        //System::debug($rooms);
        $time_from_day =(Url::get('from_day'))?Date_Time::to_time(Url::get('from_day')):(Date_Time::to_time(date('d/m/Y')));
        $time_to_day =(Url::get('to_day'))?Date_Time::to_time(Url::get('to_day')):(Date_Time::to_time(date('d/m/Y')) + 7*24*3600);
        $total_room = DB::fetch('Select count(room.id) as total from room_level inner join room on room_level.id = room.room_level_id Where  room_level.is_virtual = 0  '.$portal_id_1.'  ','total');
        //System::debug($total_room);
        $amount_room= $this->get_amout_room($from_day,$end_day,$portal_id,$cond_confirm);
        $amount_ei_li_lo= $this->get_ei_lo_li($from_day,$end_day,$portal_id,$cond_confirm,false);
        $amount_li= $this->get_ei_lo_li($from_day,$end_day,$portal_id,$cond_confirm,'LATE_CHECKIN');
    
    
        $items= array();
        $rooms_avrrial =0;
        $rooms_repair =0;
        $rooms_houseuse = 0;
        $total_occ=0;
        $total_foc =0;
        $total_ammount_room=0;
		$total_ammount_ei_lo=0;
        $total_cancel=0;
		$total_arrival = 0;
        $total_ei_li_lo = 0;
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
            $items[date('d/m/Y',$i)]['foc']=0;
            $items[date('d/m/Y',$i)]['foc_all']=0;
            $items[date('d/m/Y',$i)]['arr_room']=0;
            $items[date('d/m/Y',$i)]['dpt']=0;
            $items[date('d/m/Y',$i)]['cp']=0;
            $items[date('d/m/Y',$i)]['hu']=0;
            $items[date('d/m/Y',$i)]['room_rev']=0;
			$items[date('d/m/Y',$i)]['ei_lo_rev']=0;
			$items[date('d/m/Y',$i)]['total_rev']=0;
            $items[date('d/m/Y',$i)]['avg_rm']=0;
            $items[date('d/m/Y',$i)]['rm_rev_par']=0;
            $items[date('d/m/Y',$i)]['cancel']=0;
            $items[date('d/m/Y',$i)]['total_occ']=0;
            $items[date('d/m/Y',$i)]['ei_li_lo']=0;
            $date_to_oracle = Date_Time::to_orc_date(date('d/m/y',$i));
        	if(!isset($amount_room[$date_to_oracle]))
            {
                $amount_room[$date_to_oracle]['amount_total'] = 0;
            }
            if(isset($rooms[$date_to_oracle]))
            {
                if (isset($amount_ei_li_lo[$date_to_oracle]))
                {
                    $items[date('d/m/Y',$i)]['ei_li_lo'] = 0;
                    $items[date('d/m/Y',$i)]['ei_li_lo'] += $amount_ei_li_lo[$date_to_oracle]['quantity'];
                    $total_ei_li_lo += $amount_ei_li_lo[$date_to_oracle]['quantity'];
                }
                else
                {
                    $items[date('d/m/Y',$i)]['ei_li_lo']=0;
                }
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
        		// phong di
				if(isset($rooms[$date_to_oracle]['total_has_checkout_in_day']))
                {
        			$items[date('d/m/Y',$i)]['dpt'] +=$rooms[$date_to_oracle]['total_has_checkout_in_day'];
        		}
                //end phong di
                if(isset($rooms[$date_to_oracle]['foc']))
                {
        			$items[date('d/m/Y',$i)]['foc']=$rooms[$date_to_oracle]['foc'];
        		}
        	   if(isset($rooms[$date_to_oracle]['foc_all']))
                {
        			$items[date('d/m/Y',$i)]['foc_all']=$rooms[$date_to_oracle]['foc_all'];
        		}
				
               //Start KimTan: lay ra phong dang o va phong dang trang thai book nhung se la phong khach o trong tuong lai
               //phong luu
                if(isset($rooms[$date_to_oracle]['occ_day']))
                {
                    $items[date('d/m/Y',$i)]['total_occ'] =$items[date('d/m/Y',$i)]['total_occ'] + $rooms[$date_to_oracle]['occ_day'];
                }
                //end phong luu
        		// phong den trong ngay
                if(isset($rooms[$date_to_oracle]['total_arrival_room']))
                {
        			$items[date('d/m/Y',$i)]['arr_room']+=$rooms[$date_to_oracle]['total_arrival_room'];
        		}
                // end phong den trong ngay
                if($items[date('d/m/Y',$i)]['dpt'])
                {
					$total_out += $items[date('d/m/Y',$i)]['dpt'];	
				}
				if($items[date('d/m/Y',$i)]['arr_room'])
                {
					$total_arrival += $items[date('d/m/Y',$i)]['arr_room'];	
				}
                $total_occ +=$items[date('d/m/Y',$i)]['total_occ'];
                
        		//$items[date('d/m/Y',$i)]['oc']= round((( $items[date('d/m/Y',$i)]['total_occ'] + $items[date('d/m/Y',$i)]['arr_room']) /($total_room - (isset($rooms[$date_to_oracle]['total_house_status_repair'])?$rooms[$date_to_oracle]['total_house_status_repair']:0) - (isset($rooms[$date_to_oracle]['total_house_status_houseuse'])?$rooms[$date_to_oracle]['total_house_status_houseuse']:0)))*100,2);
        		
                if(isset($rooms[$date_to_oracle]['total_cancel']))
                {
        			$items[date('d/m/Y',$i)]['cancel']=$rooms[$date_to_oracle]['total_cancel'];
        			$total_cancel +=$rooms[$date_to_oracle]['total_cancel'];
        		}
				$percent_avg += $items[date('d/m/Y',$i)]['oc'];
        	}
            if (isset($amount_li[$date_to_oracle]))
            {
                $items[date('d/m/Y',$i)]['arr_room']+=$amount_li[$date_to_oracle]['quantity'];
                
            }
            //System::debug($rooms[$date_to_oracle]);
        	// tinh tien phong theo ngay
        	if(isset($amount_room[$date_to_oracle]))
            {
                if (isset($amount_ei_li_lo[$date_to_oracle]))
                {
                    $amount_room[$date_to_oracle]['amount_total'];// += $amount_ei_li_lo[$date_to_oracle]['total_amount'];
					$amount_ei_li_lo[$date_to_oracle]['total_amount'];
                }
                else // manh them dieu kien nay de fix loi undefineindex
                {
                    $amount_ei_li_lo[$date_to_oracle]['total_amount'] = 0;
                    $amount_ei_li_lo[$date_to_oracle]['quantity'] = 0;
                }
				
        	}
            // start KimTan: tinh tien phong va gia phong trung binh trong truong hop chi co ei lo li ma ko co doanh thu phong
            else
            {
                if (isset($amount_ei_li_lo[$date_to_oracle]))
                {
                    //System::debug($amount_ei_li_lo);
                    $total_ei_li_lo += $amount_ei_li_lo[$date_to_oracle]['quantity'];
                    $amount_room[$date_to_oracle]['ei_lo'] = 0;
                    $amount_room[$date_to_oracle]['ei_lo'] += $amount_ei_li_lo[$date_to_oracle]['total_amount'];
                    $items[date('d/m/Y',$i)]['ei_lo_rev'] = System::Display_number(round($amount_room[$date_to_oracle]['ei_lo']));
                    if($amount_room[$date_to_oracle]['ei_lo']!=0)
                    {
                            $amount_room[$date_to_oracle]['quantyti_ei_lo'] = 0;
                            $amount_room[$date_to_oracle]['quantyti_ei_lo'];// += $amount_ei_li_lo[$date_to_oracle]['quantity'];
                            $items[date('d/m/Y',$i)]['ei_lo_rev'] = $amount_room[$date_to_oracle]['quantyti_ei_lo'];
                        //if ($amount_room[$date_to_oracle]['quantyti_ei_lo'] != 0)
                        //{
                        //$items[date('d/m/Y',$i)]['avg_rm'] = 
                        //System::Display_number(
                        //round(
                            //$amount_room[$date_to_oracle]['ei_lo']/ $amount_room[$date_to_oracle]['quantyti_ei_lo']));
                            //}
                    }
                }
                
            }
            if (isset($amount_li[$date_to_oracle]))
            {
                $amount_room[$date_to_oracle]['amount_total'] += $amount_li[$date_to_oracle]['total_amount'];
            }
            $items[date('d/m/Y',$i)]['total_rev'] = System::Display_number(round($amount_ei_li_lo[$date_to_oracle]['total_amount']+$amount_room[$date_to_oracle]['amount_total']));
            $items[date('d/m/Y',$i)]['room_rev'] = System::Display_number(round($amount_room[$date_to_oracle]['amount_total']));
    		$total_ammount_room +=$amount_room[$date_to_oracle]['amount_total'];
			$items[date('d/m/Y',$i)]['ei_lo_rev'] = System::Display_number(round($amount_ei_li_lo[$date_to_oracle]['total_amount']));
			$total_ammount_ei_lo +=$amount_ei_li_lo[$date_to_oracle]['total_amount'];
        // tinh gia phong trung binh
    		if($items[date('d/m/Y',$i)]['total_occ']!=0 || $items[date('d/m/Y',$i)]['arr_room'] != 0)
    		{
    		    $num_of_room = $items[date('d/m/Y',$i)]['total_occ'] + $items[date('d/m/Y',$i)]['arr_room'];
                if (isset($amount_ei_li_lo[$date_to_oracle]))
                {
                    $num_of_room;// += $amount_ei_li_lo[$date_to_oracle]['quantity'];
                }
                if ($num_of_room != 0)
                {
                $items[date('d/m/Y',$i)]['avg_rm'] = 
                    System::Display_number(
                    round(
                        $amount_room[$date_to_oracle]['amount_total']/ $num_of_room));
                        }
    		}
            $items[date('d/m/Y',$i)]['oc']= round((( $items[date('d/m/Y',$i)]['total_occ'] + $items[date('d/m/Y',$i)]['arr_room']) /($total_room- (isset($rooms[$date_to_oracle]['total_house_status_repair'])?$rooms[$date_to_oracle]['total_house_status_repair']:0)))*100,1);
            $total_arrival_occ += $items[date('d/m/Y',$i)]['total_occ'] + $items[date('d/m/Y',$i)]['arr_room'];
            //end KimTan
        }
        $avg_room=0;
        if($total_occ!=0)
        {
            
            $total_arrival_occ = $total_arrival_occ;
			//$total_arrival_occ = $total_arrival_occ + $total_ei_li_lo;
            $avg_room=System::Display_number( round($total_ammount_room/($total_arrival_occ)));
        }
        //$percent_occ = round($total_arrival_occ*100/($rooms_avrrial),1);
		$percent_occ = round($total_arrival_occ*100/($rooms_avrrial-$rooms_repair),1);
		 $deparment = DB::fetch("SELECT description_1 as des from party where user_id='".User::id()."'",'des');
        $check_user=0;
        if($deparment==' Bộ phận Buồng')
        {
            $check_user=1;
        }
       	
        $this->parse_layout('report',array('items'=>$items,
            'rooms_avrrial'=>$rooms_avrrial,
            'rooms_repair'=>$rooms_repair,
            'total_occ'=>$total_occ,
            'percent_occ'=>$percent_occ,
            'total_cancel'=>$total_cancel,
			'total_out'=>$total_out,
			'check_user'=>$check_user,
			'total_arrival_occ'=>$total_arrival_occ,
			'total_arrival'=>$total_arrival,
            'total_ei_li_lo'=>$total_ei_li_lo,     
            'total_ammount_room'=> System::display_number(round($total_ammount_room)),
			'total_ammount_ei_lo'=>System::display_number(round($total_ammount_ei_lo)),
			'total_ammount_total'=>System::display_number(round($total_ammount_ei_lo)+round($total_ammount_room)),
            'avg_room'=>$avg_room,
            'from_date'=>date('d/m/Y',$time_from_day),
            'portal_id_list'=>array('ALL'=>Portal::language('all')) + String::get_list(Portal::get_portal_list()),
            'status_list'=>array('ALL'=>Portal::language('all'),'CF'=>'Confirm','TE'=>'Tentative'),
            'to_date'=>date('d/m/Y',$time_to_day))
            );	
    }
}
?>
