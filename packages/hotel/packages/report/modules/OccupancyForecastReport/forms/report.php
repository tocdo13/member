<?php
class OccupancyForecastReportForm extends Form{
	function OccupancyForecastReportForm(){
		Form::Form('OccupancyForecastReportForm');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js'); 
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
    function draw()
    {
        $this->map = array();
        $from_date = (Url::get('from_day'))?Url::get('from_day'):date('d/m/Y');
        $to_date = (Url::get('to_day'))?Url::get('to_day'):date('d/m/Y',(time() + 7*86400));
        $time_from_day =(Url::get('from_day'))?Date_Time::to_time(Url::get('from_day')):(Date_Time::to_time(date('d/m/Y')));
        $time_to_day =(Url::get('to_day'))?Date_Time::to_time(Url::get('to_day')):(Date_Time::to_time(date('d/m/Y')) + 7*86400);
        $this->map['from_date'] = $from_date;
        $this->map['to_date'] = $to_date;
        $from_day = Date_Time::to_orc_date($from_date);
        $end_day = Date_Time::to_orc_date($to_date);
        $portal_id = (Url::get('portal_id'))?Url::get('portal_id'):'ALL';
        $cond_portal = '';
        $cond_portal_1 = '';
        if($portal_id !='ALL')
        {
            $cond_portal .= ' AND reservation.portal_id=\''.Url::get('portal_id').'\'';
            $cond_portal_1 .= ' AND room.portal_id=\''.Url::get('portal_id').'\'';    
        }
        $room_level_id = (Url::get('room_level'))?Url::get('room_level'):'ALL';
        $cond_room_level = '';
        if($room_level_id !='ALL')
        {
            $cond_room_level .= ' AND room_level.id=\''.$room_level_id.'\'';   
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
        $total_room = DB::fetch('Select count(room.id) as total from room inner join room_level on room_level.id = room.room_level_id Where room_level.is_virtual = 0  '.$cond_portal_1.$cond_room_level.'  ','total');
        $rooms= $this->get_total_room_status($from_day,$end_day,$cond_portal,$cond_portal_1,$cond_confirm,$cond_room_level); /** lay trang thai phong **/
        $amount_room= $this->get_amout_room($from_day,$end_day,$cond_portal,$cond_confirm,$cond_room_level); /** lay doanh thu phong **/
	    $amount_li= $this->get_ei_lo_li($from_day,$end_day,$portal_id,$cond_confirm,$cond_room_level,'LATE_CHECKIN');
        $amount_ei_lo= $this->get_ei_lo_li($from_day,$end_day,$portal_id,$cond_confirm,$cond_room_level,false);
        $items= array();
        $this->map['total_avail_room'] = 0;
        $this->map['total_repair_room'] = 0;
        $this->map['total_arr_room'] = 0;
        $this->map['total_occ_room'] = 0;
        $this->map['total_dep_room'] = 0;
        $this->map['total_hu_room'] = 0;
        $this->map['total_dayused_room'] = 0;
        $this->map['total_noshow_room'] = 0;
        $this->map['total_foc'] = 0;
        $this->map['total_adult'] = 0;
        $this->map['total_child'] = 0;
        $this->map['total_child_5'] = 0;
        $this->map['total_room_soild'] = 0;
        $this->map['total_room_revenue'] = 0;
        $this->map['total_other_revenue'] = 0;
        $this->map['total_total_revenue'] = 0;
        $this->map['total_avg_price'] = 0;
        $this->map['total_oc'] = 0;
        $this->map['total_li_room'] = 0;
        $this->map['total_ei_lo_room'] = 0;
        for($i=$time_from_day ; $i<= $time_to_day; $i +=24*3600)
        {
            $k = date('d/m/Y',$i);
            $items[$k]['id'] = date('d/m/Y',$i);
            $items[$k]['in_date'] = date('d/m/Y',$i);
            /** manh tinh lai tong so phong theo lich su **/
            if(Url::get('portal_id') AND Url::get('portal_id') !='ALL')
            {
                if($his_in_date = DB::fetch('select max(in_date) as in_date from room_history where in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$i)).'\' and portal_id=\''.Url::get('portal_id').'\'','in_date'))
                {
                    $total_room = DB::fetch('select 
                                                count(rhd.room_id) as total_room 
                                            from
                                                room_history_detail rhd
                                                inner join room_history rh on rh.id=rhd.room_history_id
                                                inner join room_level on room_level.id = rhd.room_level_id
                                            where
                                                rh.in_date=\''.$his_in_date.'\'
                                                and rh.portal_id=\''.Url::get('portal_id').'\'
                                                and rhd.close_room=1
                                                and room_level.is_virtual = 0
                                                ','total_room');
                }
                elseif($his_in_date = DB::fetch('select min(in_date) as in_date from room_history where in_date>\''.Date_Time::to_orc_date(date('d/m/Y',$i)).'\' and portal_id=\''.Url::get('portal_id').'\'','in_date'))
                {
                    $total_room = DB::fetch('select 
                                                count(rhd.room_id) as total_room 
                                            from
                                                room_history_detail rhd
                                                inner join room_history rh on rh.id=rhd.room_history_id
                                                inner join room_level on room_level.id = rhd.room_level_id
                                            where
                                                rh.in_date=\''.$his_in_date.'\'
                                                and rh.portal_id=\''.Url::get('portal_id').'\'
                                                and rhd.close_room=1
                                                and room_level.is_virtual = 0
                                                ','total_room');
                }
            }
            else
            {
                $list_portal = Portal::get_portal_list();
                $total_room_all = 0;
                foreach($list_portal as $key=>$value)
                {
                    if($his_in_date = DB::fetch('select max(in_date) as in_date from room_history where in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$i)).'\' and portal_id=\''.$value['id'].'\'','in_date'))
                    {
                        $total_room_all += DB::fetch('select 
                                                    count(rhd.room_id) as total_room 
                                                from
                                                    room_history_detail rhd
                                                    inner join room_history rh on rh.id=rhd.room_history_id
                                                    inner join room_level on room_level.id = rhd.room_level_id
                                                where
                                                    rh.in_date=\''.$his_in_date.'\'
                                                    and rh.portal_id=\''.$value['id'].'\'
                                                    and rhd.close_room=1
                                                    and room_level.is_virtual = 0
                                                    ','total_room');
                    }
                    elseif($his_in_date = DB::fetch('select min(in_date) as in_date from room_history where in_date>\''.Date_Time::to_orc_date(date('d/m/Y',$i)).'\' and portal_id=\''.$value['id'].'\'','in_date'))
                    {
                        $total_room_all += DB::fetch('select 
                                                    count(rhd.room_id) as total_room 
                                                from
                                                    room_history_detail rhd
                                                    inner join room_history rh on rh.id=rhd.room_history_id
                                                    inner join room_level on room_level.id = rhd.room_level_id
                                                where
                                                    rh.in_date=\''.$his_in_date.'\'
                                                    and rh.portal_id=\''.$value['id'].'\'
                                                    and rhd.close_room=1
                                                    and room_level.is_virtual = 0
                                                    ','total_room');
                    }
                }
                if($total_room_all!=0)
                {
                    $total_room = $total_room_all;
                }
            }
            /** End Manh **/
            $items[$k]['avail_room'] = $total_room;
            $items[$k]['repair_room'] = 0; 
            $items[$k]['arr_room'] = 0; 
            $items[$k]['occ_room'] = 0; 
            $items[$k]['dep_room'] = 0;
            $items[$k]['hu_room'] = 0;  
            $items[$k]['dayused_room'] = 0;
            $items[$k]['noshow_room'] = 0; 
            $items[$k]['foc'] = 0;
            $items[$k]['adult'] = 0; 
            $items[$k]['child'] = 0; 
            $items[$k]['child_5'] = 0;
            $items[$k]['li_revenue'] = 0;
            $items[$k]['li_room'] = 0;
            $items[$k]['ei_lo_room'] = 0;
            $items[$k]['room_revenue'] = 0;
            $items[$k]['other_revenue'] = 0;
            $items[$k]['total_revenue'] = 0;
            $items[$k]['avg_price'] = 0;
            $items[$k]['room_soild'] = 0;
            $items[$k]['oc'] = 0;
            if(isset($rooms[$k]))
            {
                $items[$k]['repair_room'] += $rooms[$k]['repair_room']; 
                $items[$k]['arr_room'] += $rooms[$k]['arr_room'];
                $items[$k]['occ_room'] += $rooms[$k]['occ_room']; 
                $items[$k]['dep_room'] += $rooms[$k]['dep_room'];
                $items[$k]['dayused_room'] += $rooms[$k]['dayused_room'];
                $items[$k]['foc'] += $rooms[$k]['foc'];
                $items[$k]['adult'] += $rooms[$k]['adult'];
                $items[$k]['child'] += $rooms[$k]['child'];
                $items[$k]['child_5'] += $rooms[$k]['child_5'];
            } 
            if(isset($noshow_arr[$k]))
            {
                $items[$k]['noshow_room'] += $noshow_arr[$k]['count'];
            }
            if(isset($amount_room[$k])) 
            {
                $items[$k]['room_revenue'] += $amount_room[$k]['amount_total'];
                $items[$k]['total_revenue'] += $amount_room[$k]['amount_total'];
            }
            if(isset($amount_li[$k])) 
            {
                $items[$k]['room_revenue'] += $amount_li[$k]['total_amount'];
                $items[$k]['total_revenue'] += $amount_li[$k]['total_amount'];
                $items[$k]['li_room'] += $amount_li[$k]['quantity'];
            }
            if(isset($amount_ei_lo[$k])) 
            {
                $items[$k]['other_revenue'] += $amount_ei_lo[$k]['total_amount'];
                $items[$k]['total_revenue'] += $amount_ei_lo[$k]['total_amount'];
                $items[$k]['ei_lo_room'] += $amount_ei_lo[$k]['quantity'];
            }
            $items[$k]['room_soild'] = $items[$k]['occ_room']+$items[$k]['arr_room']+$items[$k]['li_room']; /** tong so phong ban= phong den+phong o+phong li **/
            $items[$k]['avg_price'] = ($items[$k]['room_soild']>0)?System::Display_number(round($items[$k]['room_revenue']/$items[$k]['room_soild'])):0; /** gia binh quan = doanh thu/phong ban **/
            /** cong xuat phong = phong ban/(phong ks co-phong hong- phong HU)*100 **/
            $items[$k]['oc'] = round($items[$k]['room_soild']/($items[$k]['avail_room']-$items[$k]['repair_room']-$items[$k]['hu_room'])*100,2);
            /** doan nay de tinh tong **/
            $this->map['total_avail_room'] += $items[$k]['avail_room'];
            $this->map['total_repair_room'] += $items[$k]['repair_room'];
            $this->map['total_arr_room'] += $items[$k]['arr_room'];
            $this->map['total_occ_room'] += $items[$k]['occ_room'];
            $this->map['total_dep_room'] += $items[$k]['dep_room'];
            $this->map['total_hu_room'] += $items[$k]['hu_room'];
            $this->map['total_dayused_room'] += $items[$k]['dayused_room'];
            $this->map['total_noshow_room'] += $items[$k]['noshow_room'];
            $this->map['total_foc'] += $items[$k]['foc'];
            $this->map['total_adult'] += $items[$k]['adult'];
            $this->map['total_child'] += $items[$k]['child'];
            $this->map['total_li_room'] += $items[$k]['li_room'];
            $this->map['total_ei_lo_room'] += $items[$k]['ei_lo_room'];
            $this->map['total_child_5'] += $items[$k]['child_5'];
            $this->map['total_room_soild'] += $items[$k]['room_soild'];
            $this->map['total_room_revenue'] += $items[$k]['room_revenue'];
            $this->map['total_other_revenue'] += $items[$k]['other_revenue'];
            $this->map['total_total_revenue'] += $items[$k]['total_revenue'];
        }
        $this->map['total_avg_price'] = ($this->map['total_room_soild']>0)?System::Display_number(round($this->map['total_room_revenue']/$this->map['total_room_soild'])):0;
        $this->map['total_oc'] = round($this->map['total_room_soild']/($this->map['total_avail_room']-$this->map['total_repair_room']-$this->map['total_hu_room'])*100,2);
        $deparment = DB::fetch("SELECT description_1 as des from party where user_id='".User::id()."'",'des');
        $check_user=0;
        if($deparment==' Bộ phận Buồng')
        {
            $check_user=1;
        }
       	$this->map['portal_id_list'] = array('ALL'=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
        $this->parse_layout('report',array(
            'items'=>$items,
            'check_user'=>$check_user,
            'status_list'=>array('ALL'=>Portal::language('all'),'CF'=>'Confirm','TE'=>'Tentative')
            )+$this->map);	
    }
	function get_total_room_status($from_date,$to_date,$cond_portal,$cond_portal_1,$cond_confirm,$cond_room_level)
    {
		$room_status = array();
        $time_from_day =Date_Time::to_time(Date_Time::convert_orc_date_to_date($from_date,'/'));
        $time_to_day =Date_Time::to_time(Date_Time::convert_orc_date_to_date($to_date,'/'));
        for($i=$time_from_day ; $i<= $time_to_day; $i +=24*3600)
        {
            $k = date('d/m/Y',$i);
            $room_status[$k]['id'] = $k;
            $room_status[$k]['in_date'] = date('d/m/Y',$i);
            $room_status[$k]['repair_room'] = 0; /** phong hong **/
            $room_status[$k]['arr_room'] = 0; /** phong den **/
            $room_status[$k]['occ_room'] = 0; /** phong luu **/
            $room_status[$k]['dep_room'] = 0; /** phong di **/
            $room_status[$k]['dayused_room'] = 0; /** phong day_used **/
            $room_status[$k]['foc'] = 0;
            $room_status[$k]['adult'] = 0; 
            $room_status[$k]['child'] = 0; 
            $room_status[$k]['child_5'] = 0;  
        }
        //System::debug($room_status);exit();
        /** Tinh phong Repair **/
        $sql='
			SELECT 
			     	count(rs.room_id) as total, 
                    rs.in_date,
                    rs.house_status,
					concat(rs.in_date,rs.house_status)as id
			FROM 
					room_status rs
                    inner join room on rs.room_id = room.id
                    inner join room_level on room.room_level_id = room_level.id
			WHERE 
					DATE_TO_UNIX(rs.in_date) >= DATE_TO_UNIX(\''.$from_date.'\') AND DATE_TO_UNIX(rs.in_date) <=DATE_TO_UNIX(\''.$to_date.'\')
                    and rs.house_status = \'REPAIR\'
                    and room_level.is_virtual = 0
                    '.$cond_portal_1.$cond_room_level.'
			GROUP 
					BY rs.house_status,
                    rs.in_date
                    ';
        $rooms = DB::fetch_all($sql);
		foreach($rooms as $key => $value)
        {   
			$id = Date_Time::convert_orc_date_to_date($value['in_date'],'/');
			if($value['house_status'] == 'REPAIR')
            {
				$room_status[$id]['repair_room'] += $value['total'];
			}
		}
		/** Tinh phong den,di,luu **/
		$sql2='
				SELECT 
					rs.id as id,  
					rs.in_date,
                    date_to_unix(rs.in_date) as time_indate,
					rs.status, 
                    rr.arrival_time, 
                    rr.status as reservation_status,
					rr.departure_time,
                    rr.time_in,
                    rr.time_out,
                    rr.foc_all,
                    rr.foc,
                    nvl(room_level.is_virtual,0) as is_virtual,
                    nvl(rr.change_room_from_rr,0) as change_room_from_rr,
                    nvl(rr.change_room_to_rr,0) as change_room_to_rr,
                    from_unixtime(rr.old_arrival_time) as old_arrival_time,
                    rr.old_arrival_time as time_old,
                    reservation.id as r_id,
                    rr.room_level_id as room_level_id,
                    nvl(rr.adult,0) as adult,
                    nvl(rr.child,0) as child,
                    nvl(rr.child_5,0) as child_5,
                    room.id as room_id,
                    room.name as room_name
				FROM 
					room_status rs
                    inner JOIN reservation_room rr on rr.id = rs.reservation_room_id
                    inner join reservation on rr.reservation_id = reservation.id
                    left join room on rr.room_id = room.id
                    left join room_level on room_level.id = rr.room_level_id
				WHERE 
					DATE_TO_UNIX(rs.in_date) >= DATE_TO_UNIX(\''.$from_date.'\') 
                    AND DATE_TO_UNIX(rs.in_date) <=DATE_TO_UNIX(\''.$to_date.'\')
                    '.$cond_portal.$cond_confirm.'
				    and rr.status != \'CANCEL\' 
                    and rr.status != \'NOSHOW\'
                    and room_level.is_virtual = 0
                    and rs.reservation_id != 0
                ';
        $room_in_outs = DB::fetch_all($sql2);
        //System::debug($room_in_outs);
        foreach($room_in_outs as $key=>$value)
        {
            $id = Date_Time::convert_orc_date_to_date($value['in_date'],'/');;
        	if($value['change_room_from_rr']==0 and $value['change_room_to_rr']==0) /** th khong lien quan toi doi phong **/
            {
                if($value['in_date']==$value['arrival_time']) /** phong den truong hop binh thuong **/
                {
                    $room_status[$id]['arr_room'] += 1;
                }
                if($value['time_in']<$value['time_indate'] and $value['time_out']>=($value['time_indate']+86400)) /** phong luu truong hop binh thuong **/
                {
                    $room_status[$id]['occ_room'] += 1;
                }
                if($value['in_date']==$value['departure_time']) /** phong di truong hop binh thuong **/
                {
                    $room_status[$id]['dep_room'] += 1;
                }
                if($value['arrival_time']==$value['departure_time'] and $value['arrival_time']==$value['in_date'])
                {
                    $room_status[$id]['dayused_room'] += 1;
                }
            }
            elseif($value['change_room_from_rr']==0 and $value['change_room_to_rr']!=0) /** Truong hop phong chang dau cua doi phong**/
            {
                if($value['in_date']==$value['arrival_time'])
                {
                    $room_status[$id]['arr_room'] += 1;
                }
                if($value['time_in']<$value['time_indate'] and $value['time_out']>=($value['time_indate']+86400))
                {
                    $room_status[$id]['occ_room'] += 1;
                }
            }
            elseif($value['change_room_from_rr']!=0 and $value['change_room_to_rr']==0) /** Truong hop phong chang cuoi cua doi phong**/
            {
                if($value['in_date']==$value['departure_time'])
                {
                    $room_status[$id]['dep_room'] += 1;
                }
                if($value['time_old']<$value['time_indate'] and $value['time_out']>=($value['time_indate']+86400))
                {
                    $room_status[$id]['occ_room'] += 1;
                }
                if($value['old_arrival_time']==$value['departure_time'] and $value['old_arrival_time'] == $value['in_date'])
                {
                    $room_status[$id]['dayused_room'] += 1;
                }
            }
            elseif($value['change_room_from_rr']!=0 and $value['change_room_to_rr']!=0) /** Truong hop phong chang giua cua doi phong**/
            {
                if($value['time_old']<$value['time_indate'] and $value['time_out']>=($value['time_indate']+86400))
                {
                    $room_status[$id]['occ_room'] += 1;
                }
            }
            /** tinh phong mien phi, ng lon, tre e **/
            if($value['in_date']!=$value['departure_time'] || ($value['in_date']==$value['departure_time'] and $value['arrival_time']=$value['departure_time'] and $value['change_room_to_rr']==0))
            {
                if($value['foc_all']==1 || $value['foc']!='')
                {
                    $room_status[$id]['foc'] += 1;
                }
                $room_status[$id]['adult'] += $value['adult'];
                $room_status[$id]['child'] += $value['child'];
                $room_status[$id]['child_5'] += $value['child_5'];
            }    
        }
        return $room_status;
	}
	// tinh tong tien phong theo ngay 
    function get_amout_room($date_from,$date_end,$cond_portal,$cond_confirm,$cond_room_level)
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
						rr.id as reservation_room_id,
                        rr.room_level_id
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
                       '.$cond_portal.$cond_confirm.'
                       ';
        $room_totals = DB::fetch_all($sql1);
		// tinh tien phong su dung trong ngay
		$amount_room_days =array();
		foreach($room_totals as $key=>$value)
        {
			$id = Date_Time::convert_orc_date_to_date($value['in_date'],'/');
            if(!isset($amount_room_days[$id]['amount_total']))
            {
                 $amount_room_days[$id]['amount_total'] = $value['change_price'];
			}
            else
            {
                 $amount_room_days[$id]['amount_total'] += $value['change_price'];
			}
		}
		return $amount_room_days;
	}
    function get_ei_lo_li($date_from,$date_end,$portal_id,$cond_confirm,$cond_room_level,$type=false)
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
            $cond.='and extra_service.code!=\'LATE_CHECKIN\' and extra_service.type=\'ROOM\'';
        }
        $sql = 'select
                    esid.id,
                    esid.quantity+nvl(esid.change_quantity,0) as quantity,
                    esid.price,
                    esid.in_date,
                    rr.room_level_id,
                    rr.foc_all,
                    CASE
            			WHEN 
            				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
            			THEN
            				((esid.quantity+nvl(esid.change_quantity,0))*esid.price) + (((esid.quantity+nvl(esid.change_quantity,0))*esid.price)*extra_service_invoice.service_rate*0.01) + ((((esid.quantity+nvl(esid.change_quantity,0))*esid.price) + (((esid.quantity+nvl(esid.change_quantity,0))*esid.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
            			ELSE
            				((esid.quantity+nvl(esid.change_quantity,0))*esid.price)
                    END as total_amount,
                    extra_service_invoice.service_rate,
                    extra_service.code
                from
                    extra_service_invoice_detail esid
                    inner join extra_service on esid.service_id = extra_service.id
                    inner join extra_service_invoice on extra_service_invoice.id = esid.invoice_id
                    inner join reservation_room rr on rr.id = extra_service_invoice.reservation_room_id
                    inner join reservation on rr.reservation_id = reservation.id
                    left join room_level on rr.room_level_id = room_level.id
                where
                    room_level.is_virtual = 0
                    and rr.foc_all = 0
                    AND rr.status != \'CANCEL\' AND rr.status != \'NOSHOW\'
                    '.$cond.'
                    AND esid.in_date >= \''.$date_from.'\' 
				    AND esid.in_date <= \''.$date_end.'\'
                     '.$portal_id_2.$cond_confirm.'
                     ';
        $items = DB::fetch_all($sql);
        //System::debug($items);
        $ei_lo_li = array();
        if (!empty($items))
        {
            foreach ($items as $key => $value)
            {
                $items[$key]['quantity_ei'] = 0;
                if($value['code']=='EARLY_CHECKIN' or $value['code']== 'LATE_CHECKOUT' or $value['code']== 'LATE_CHECKIN')
                {
                    $items[$key]['quantity_ei'] = $value['quantity'];
                }
                if($items[$key]['foc_all']==0)
                {
                    $items[$key]['total_amount_ei'] = $value['total_amount'];
                }
                else
                {
                    $items[$key]['total_amount_ei'] = 0;
                }
                $id = Date_Time::convert_orc_date_to_date($value['in_date'],'/');
                if (!isset($ei_lo_li[$id]))
                {
                    $ei_lo_li[$id]['total_amount'] = $items[$key]['total_amount_ei'];
                    $ei_lo_li[$id]['quantity'] = $items[$key]['quantity_ei'];
                }
                else
                {
                    $ei_lo_li[$id]['total_amount'] += $items[$key]['total_amount_ei'];
                    $ei_lo_li[$id]['quantity'] += $items[$key]['quantity_ei'];
                }
            }
        }
        return $ei_lo_li;
    }
}
?>
