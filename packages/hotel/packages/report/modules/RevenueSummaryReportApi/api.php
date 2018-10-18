<?php 
class api extends restful_api
{
	function __construct(){
		parent::__construct();
	}
    
    function draw()
    {
        if($this->method == 'GET')
        {
            if(Url::get('secretkey') and Url::get('secretkey') == '9a8fa234b2520e9bb4f59d8178545a62')
            {
                $from_date = (Url::get('from_day'))?Url::get('from_day'):date('d/m/Y');
                //System::debug($from_date);
                $time_from_day =(Url::get('from_day'))?Date_Time::to_time(Url::get('from_day')):(Date_Time::to_time(date('d/m/Y')));
                $this->map['from_date'] = $from_date;
                $from_day = Date_Time::to_orc_date($from_date);
                $portal_id = (Url::get('portal_id'))?Url::get('portal_id'):'ALL';
                $this->map['portal_id_list'] = array('ALL'=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
                /** du lieu phat sinh cua phan mem **/
                $month_current = $this->get_data(date('01/m/Y',$time_from_day),date('d/m/Y',$time_from_day),$portal_id); /** Thuc hien cua thang xem bc **/
                //System::debug($month_current);
                
                //System::debug($month_current_pie);
                $last_year = date('Y',$time_from_day)-1;
                $last_year_current = $this->get_data(date('01/m',$time_from_day).'/'.$last_year,date('d/m',$time_from_day).'/'.$last_year,$portal_id); /** Thuc hien cua cung thang nay nam truoc **/
                $this_month_ytd = $this->get_data(date('01/01/Y',$time_from_day),date('d/m/Y',$time_from_day),$portal_id); /** Thuc hien tu dau nam den thang hien tai **/
                
                $last_year_ytd = $this->get_data(date('01/01',$time_from_day).'/'.$last_year,date('d/m',$time_from_day).'/'.$last_year,$portal_id); /** Thuc hien cua tu dau nam den thang hien tai cua nam truoc **/
                /** du lieu trong phan ke hoach **/
                $budget_current = $this->get_budget(date('m',$time_from_day),date('m',$time_from_day),date('Y',$time_from_day)); /** ke hoach cua thang hien tai **/
                //System::debug($budget_current);
                $budget_ytd = $this->get_budget(1,date('m',$time_from_day),date('Y',$time_from_day),date('Y',$time_from_day)); /** ke hoach tu dau nam den thang hien tai **/

                $this->response(200, json_encode(array(
                                                'month_current'=>$month_current,
                                                'last_year_current'=>$last_year_current,
                                                'this_month_ytd'=>$this_month_ytd,
                                                'last_year_ytd'=>$last_year_ytd,
                                                'budget_current'=>$budget_current,
                                                'budget_ytd'=>$budget_ytd
                )));     
            }
        }else
        {
            $this->response(500, "FAILED"); // METHOD
        }	
    }
    
    function get_budget($from_month,$to_month,$year)
    {
        $budget = array();
        $cond = '1=1';
        $cond.= 'and PLAN_OF_MONTH_DETAIL.month >= '.$from_month;
        $cond.= 'and PLAN_OF_MONTH_DETAIL.month <= '.$to_month;
        $cond.= 'and PLAN_OF_MONTH_DETAIL.year = '.$year;
        $sql = '
            select 
                sum(UNITS_BUILT) as UNITS_BUILT
                ,sum(ROOM_REPAIR) as ROOM_REPAIR
                ,sum(ROOMS_AVAILABLE_FOR_SALE) as ROOMS_AVAILABLE_FOR_SALE
                ,sum(ROOMS_SOLD) as ROOMS_SOLD
                ,sum(COMPLIMENTARY_ROOMS) as COMPLIMENTARY_ROOMS
                ,sum(TOTAL_ROOMS_OCCUPIED) as TOTAL_ROOMS_OCCUPIED
                ,sum(HOUSE_USE_ROOMS) as HOUSE_USE_ROOMS
                ,sum(NO_OF_GUESTS) as NO_OF_GUESTS
                ,sum(ROOM_REVENUE) as ROOM_REVENUE
                ,sum(BAR_REVENUE) as BAR_REVENUE
                ,sum(TELEPHONE_REVENUE) as TELEPHONE_REVENUE
                ,sum(LAUNDRY_REVENUE) as LAUNDRY_REVENUE
                ,sum(MINIBAR_REVENUE) as MINIBAR_REVENUE
                ,sum(TRANSPORT_REVENUE) as TRANSPORT_REVENUE
                ,sum(SPA_REVENUE) as SPA_REVENUE
                ,sum(OTHERS_REVENUE) as OTHERS_REVENUE
                ,sum(VENDING_REVENUE) as VENDING_REVENUE
           from  
                PLAN_OF_MONTH_DETAIL
           where '.$cond.'
        ';
        $budget = DB::fetch($sql);
        $budget['hotel_revenue_total'] = $budget['room_revenue']+$budget['bar_revenue']+$budget['telephone_revenue']+$budget['laundry_revenue']+$budget['minibar_revenue']+$budget['transport_revenue']+$budget['spa_revenue']+$budget['others_revenue']+$budget['vending_revenue'];
        $budget['occupancy_rate'] = ($budget['rooms_available_for_sale']!=0)?round($budget['total_rooms_occupied']/$budget['rooms_available_for_sale']*100):0;
        $budget['average_room_rate'] = ($budget['rooms_sold']!=0)?round($budget['room_revenue']/$budget['rooms_sold']):0;
        $budget['rev_par'] = ($budget['rooms_available_for_sale']!=0)?round($budget['room_revenue']/$budget['rooms_available_for_sale']):0;
        $budget['spend_per_guest'] = ($budget['no_of_guests']!=0)?round($budget['hotel_revenue_total']/$budget['no_of_guests']):0;
        
        $budget['room_revenue_percent'] = ($budget['hotel_revenue_total']!=0)?round($budget['room_revenue']/$budget['hotel_revenue_total']*100,2):0;
        $budget['bar_revenue_percent'] = ($budget['hotel_revenue_total']!=0)?round($budget['bar_revenue']/$budget['hotel_revenue_total']*100,2):0;
        $budget['total_telephone_percent'] = ($budget['hotel_revenue_total']!=0)?round($budget['telephone_revenue']/$budget['hotel_revenue_total']*100,2):0;
        $budget['laundry_percent'] = ($budget['hotel_revenue_total']!=0)?round($budget['laundry_revenue']/$budget['hotel_revenue_total']*100,2):0;
        $budget['minibar_percent'] = ($budget['hotel_revenue_total']!=0)?round($budget['minibar_revenue']/$budget['hotel_revenue_total']*100,2):0;
        $budget['transport_percent'] = ($budget['hotel_revenue_total']!=0)?round($budget['transport_revenue']/$budget['hotel_revenue_total']*100,2):0;
        $budget['spa_percent'] = ($budget['hotel_revenue_total']!=0)?round($budget['spa_revenue']/$budget['hotel_revenue_total']*100,2):0;
        $budget['service_other_percent'] = ($budget['hotel_revenue_total']!=0)?round($budget['others_revenue']/$budget['hotel_revenue_total']*100,2):0;
        $budget['shop_percent'] = ($budget['hotel_revenue_total']!=0)?round($budget['vending_revenue']/$budget['hotel_revenue_total']*100,2):0;
        
        return $budget;
    }
    
    function get_data($from_date,$to_date,$portal_id)
    {
        $data = array();
        $from_day = Date_Time::to_orc_date($from_date);
        $end_day = Date_Time::to_orc_date($to_date);
        $cond_portal_1=$cond_portal = '';
        if($portal_id !='ALL')
        {
            $cond_portal .= ' AND reservation.portal_id=\''.Url::get('portal_id').'\'';
            $cond_portal_1 .= ' AND room.portal_id=\''.Url::get('portal_id').'\'';    
        }
        $rooms = $this->get_total_room_status($from_day,$end_day,$cond_portal,$cond_portal_1); /** lay trang thai phong **/
        $data['total_room'] = $rooms['total_room'];
        $data['repair_room'] = $rooms['repair_room']; 
        $data['room_available_for_sale'] = $data['total_room']-$data['repair_room'];
        $data['total_room_occ'] = $rooms['soild_room'];  
        $data['foc_room'] = $rooms['foc_room'];
        $data['hu_room'] = $rooms['hu_room'];
        $data['adult'] = $rooms['adult'];
        $amount_room= $this->get_amout_room($from_day,$end_day,$cond_portal); /** lay doanh thu phong **/
        $amount_li = $this->get_extra_service($from_day,$end_day,$portal_id,'LATE_CHECKIN'); /** lay so luong, doanh thu li khong foc **/
        //echo $amount_li['total_amount'];
        $data['total_room_occ'] += $amount_li['quantity'];
        $quantity_li_foc = $this->get_extra_service($from_day,$end_day,$portal_id,'LATE_CHECKIN_FOC'); /** lay so luong, doanh thu li khong foc **/
        $data['total_room_occ'] += $quantity_li_foc['quantity'];
        //echo $data['total_room_occ'].'------------';
        $data['foc_room'] += $quantity_li_foc['quantity'];
        $data['room_soild'] = $data['total_room_occ']-$data['foc_room'];
        $data['occupancy_rate'] = round($data['total_room_occ']/$data['room_available_for_sale']*100,2); /** Cong suat phong **/
        $amount_service_room = $this->get_extra_service($from_day,$end_day,$portal_id,'SERVICE_ROOM'); /** lay doanh thu dv tra ve phong **/
        $data['room_revenue'] = 0;
        $data['room_revenue'] += round($amount_room+$amount_li['total_amount']+$amount_service_room['total_amount']);
        $data['average_room_rate'] = ($data['room_soild']!=0)?round($data['room_revenue']/$data['room_soild']):0; /** Gia phong binh quan tren so phong ban dc **/
        $data['rev_par'] = round($data['room_revenue']/$data['room_available_for_sale']); /** Gia phong binh quan tren so phong co san **/
        $transport = $this->get_extra_service($from_day,$end_day,$portal_id,'TRANSPORT'); /** lay doanh thu dv van chuyen **/
        $data['transport'] = $transport['total_amount'];
        $data['service_other'] = 0;
        $extra_service_other = $this->get_extra_service($from_day,$end_day,$portal_id,'SERVICE_OTHER'); /** lay doanh thu dv khac **/
        $data['service_other'] += $extra_service_other['total_amount'];
        $amount_bar =  $this->get_amount_bar($from_date,$to_date,$portal_id); /** lay doanh thu an uong+dv cua nha hang **/
        $data['bar_revenue'] = 0;
        $data['bar_revenue'] += $amount_bar;
        $amount_party =  $this->get_party($from_date,$to_date,$portal_id); /** lay doanh thu an uong cua tiec **/
        $data['bar_revenue'] += $amount_party;
        $data['total_telephone'] =  $this->get_telephone($from_date,$to_date,$portal_id); /** lay doanh thu dien thoai **/
        $data['minibar'] = $this->get_housekeeping($from_date,$to_date,$portal_id,'MINIBAR'); /** lay doanh thu minibar **/
        $data['laundry'] = $this->get_housekeeping($from_date,$to_date,$portal_id,'LAUNDRY'); /** lay doanh thu giat la **/
        $equipment = $this->get_housekeeping($from_date,$to_date,$portal_id,'EQUIP'); /** lay doanh thu den bu **/
        $data['service_other']+=$equipment;
        $data['spa']=$this->get_spa($from_date,$to_date,$portal_id);
        $data['shop']=$this->get_shop($from_date,$to_date,$portal_id);
        $data['service_other'] = round($data['service_other']);
        $data['bar_revenue'] = round($data['bar_revenue']);
        $data['total_telephone'] = round($data['total_telephone']);
        $data['laundry'] = round($data['laundry']);
        $data['minibar'] = round($data['minibar']);
        $data['transport'] = round($data['transport']);
        $data['spa'] = round($data['spa']);
        $data['shop'] = round($data['shop']);
        $data['service_other'] = round($data['service_other']);
        $data['hotel_revenue_total'] = $data['room_revenue']+$data['bar_revenue']+$data['total_telephone']+$data['laundry']+$data['minibar']+$data['transport']+$data['spa']+$data['service_other']+$data['shop'];
        $data['spend_per_guest'] = ($data['adult']!=0)?round($data['hotel_revenue_total']/$data['adult']):$data['hotel_revenue_total'];
        $data['room_revenue_percent'] = ($data['hotel_revenue_total']!=0)?round($data['room_revenue']/$data['hotel_revenue_total']*100,2):0;
        $data['bar_revenue_percent'] = ($data['hotel_revenue_total']!=0)?round($data['bar_revenue']/$data['hotel_revenue_total']*100,2):0;
        $data['total_telephone_percent'] = ($data['hotel_revenue_total']!=0)?round($data['total_telephone']/$data['hotel_revenue_total']*100,2):0;
        $data['laundry_percent'] = ($data['hotel_revenue_total']!=0)?round($data['laundry']/$data['hotel_revenue_total']*100,2):0;
        $data['minibar_percent'] = ($data['hotel_revenue_total']!=0)?round($data['minibar']/$data['hotel_revenue_total']*100,2):0;
        $data['transport_percent'] = ($data['hotel_revenue_total']!=0)?round($data['transport']/$data['hotel_revenue_total']*100,2):0;
        $data['spa_percent'] = ($data['hotel_revenue_total']!=0)?round($data['spa']/$data['hotel_revenue_total']*100,2):0;
        $data['service_other_percent'] = ($data['hotel_revenue_total']!=0)?round($data['service_other']/$data['hotel_revenue_total']*100,2):0;
        $data['shop_percent'] = ($data['hotel_revenue_total']!=0)?round($data['shop']/$data['hotel_revenue_total']*100,2):0;
        return $data;
    }
	function get_total_room_status($from_date,$to_date,$cond_portal,$cond_portal_1)
    {
		$room_status = array();
        $time_from_day =Date_Time::to_time(Date_Time::convert_orc_date_to_date($from_date,'/'));
        $time_to_day =Date_Time::to_time(Date_Time::convert_orc_date_to_date($to_date,'/'));
        $total_room = DB::fetch('Select count(room.id) as total from room inner join room_level on room_level.id = room.room_level_id Where room_level.is_virtual = 0  '.$cond_portal_1.' ','total');
        $room_status['total_room'] = $total_room*ceil(($time_to_day-$time_from_day+1)/24/3600);
        $room_status['repair_room'] = 0; /** phong hong **/
        $room_status['soild_room'] = 0; /** phong ban **/
        $room_status['foc_room'] = 0;
        $room_status['hu_room'] = 0;
        $room_status['adult'] = 0; 
        //System::debug($room_status);exit();
        /** Tinh phong Repair **/
        $sql='
			SELECT 
			     	count(*) as total
			FROM 
					room_status rs
                    inner join room on rs.room_id = room.id
                    inner join room_level on room.room_level_id = room_level.id
			WHERE 
					DATE_TO_UNIX(rs.in_date) >= DATE_TO_UNIX(\''.$from_date.'\') AND DATE_TO_UNIX(rs.in_date) <=DATE_TO_UNIX(\''.$to_date.'\')
                    and rs.house_status = \'REPAIR\'
                    and room_level.is_virtual = 0
                    '.$cond_portal_1.'
                    ';
        $room_status['repair_room'] = DB::fetch($sql,'total')?DB::fetch($sql,'total'):0;
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
                    room.id as room_id,
                    room.name as room_name,
                    customer.name as customer_name
				FROM 
					room_status rs
                    inner JOIN reservation_room rr on rr.id = rs.reservation_room_id
                    inner join reservation on rr.reservation_id = reservation.id
                    left join room on rr.room_id = room.id
                    left join room_level on room_level.id = rr.room_level_id
                    left join customer on reservation.customer_id = customer.id
				WHERE 
					DATE_TO_UNIX(rs.in_date) >= DATE_TO_UNIX(\''.$from_date.'\') 
                    AND DATE_TO_UNIX(rs.in_date) <=DATE_TO_UNIX(\''.$to_date.'\')
                    '.$cond_portal.'
				    and rr.status != \'CANCEL\' 
                    and rr.status != \'NOSHOW\'
                    and room_level.is_virtual = 0
                    and rs.reservation_id != 0
                ';
        //System::debug($sql2);
        $room_in_outs = DB::fetch_all($sql2);
        //System::debug($room_in_outs);
        foreach($room_in_outs as $key=>$value)
        {
            $id = Date_Time::convert_orc_date_to_date($value['in_date'],'/');;
        	if($value['change_room_from_rr']==0 and $value['change_room_to_rr']==0) /** th khong lien quan toi doi phong **/
            {
                if($value['in_date']==$value['arrival_time']) /** phong den truong hop binh thuong **/
                {
                    $room_status['soild_room'] += 1;
                    if($value['foc_all']==1 || $value['foc']!='')
                    {
                        $room_status['foc_room'] += 1;
                    }
                    if($value['customer_name']=='NOIBO')
                    {
                        $room_status['hu_room'] += 1;
                    }
                    $room_status['adult'] += $value['adult'];
                }
                if($value['time_in']<$value['time_indate'] and $value['time_out']>=($value['time_indate']+86400)) /** phong luu truong hop binh thuong **/
                {
                    $room_status['soild_room'] += 1;
                    if($value['foc_all']==1 || $value['foc']!='')
                    {
                        $room_status['foc_room'] += 1;
                    }
                    if($value['customer_name']=='NOIBO')
                    {
                        $room_status['hu_room'] += 1;
                    }
                    $room_status['adult'] += $value['adult'];
                }
            }
            elseif($value['change_room_from_rr']==0 and $value['change_room_to_rr']!=0) /** Truong hop phong chang dau cua doi phong**/
            {
                if($value['in_date']==$value['arrival_time'])
                {
                    $room_status['soild_room'] += 1;
                    if($value['foc_all']==1 || $value['foc']!='')
                    {
                        $room_status['foc_room'] += 1;
                    }
                    if($value['customer_name']=='NOIBO')
                    {
                        $room_status['hu_room'] += 1;
                    }
                    $room_status['adult'] += $value['adult'];
                }
                if($value['time_in']<$value['time_indate'] and $value['time_out']>=($value['time_indate']+86400))
                {
                    $room_status['soild_room'] += 1;
                    if($value['foc_all']==1 || $value['foc']!='')
                    {
                        $room_status['foc_room'] += 1;
                    }
                    if($value['customer_name']=='NOIBO')
                    {
                        $room_status['hu_room'] += 1;
                    }
                    $room_status['adult'] += $value['adult'];
                }
            }
            elseif($value['change_room_from_rr']!=0 and $value['change_room_to_rr']==0) /** Truong hop phong chang cuoi cua doi phong**/
            {
                if($value['time_old']<$value['time_indate'] and $value['time_out']>=($value['time_indate']+86400))
                {
                    $room_status['soild_room'] += 1;
                    if($value['foc_all']==1 || $value['foc']!='')
                    {
                        $room_status['foc_room'] += 1;
                    }
                    if($value['customer_name']=='NOIBO')
                    {
                        $room_status['hu_room'] += 1;
                    }
                    $room_status['adult'] += $value['adult'];
                }
            }
            elseif($value['change_room_from_rr']!=0 and $value['change_room_to_rr']!=0) /** Truong hop phong chang giua cua doi phong**/
            {
                if($value['time_old']<$value['time_indate'] and $value['time_out']>=($value['time_indate']+86400))
                {
                    $room_status['soild_room'] += 1;
                    if($value['foc_all']==1 || $value['foc']!='')
                    {
                        $room_status['foc_room'] += 1;
                    }
                    if($value['customer_name']=='NOIBO')
                    {
                        $room_status['hu_room'] += 1;
                    }
                    $room_status['adult'] += $value['adult'];
                }
            }
            /** tinh phong mien phi, ng lon, tre e **/   
        }
        if(User::id()=='developer06')
        {
            //System::debug($room_status);
        }
        return $room_status;
	}
	// tinh tong tien phong theo ngay 
    function get_amout_room($date_from,$date_end,$cond_portal)
    {
		$sql1 = '
		        SELECT 
						sum(
    						case
                            when rs.in_date = rr.arrival_time
                            then 
                                (case
                                 when rr.net_price = 0
                                 then ((CHANGE_PRICE*(1-NVL(rr.REDUCE_BALANCE,0)/100.0)-NVL(rr.REDUCE_AMOUNT,0))*(1+NVL(rr.SERVICE_RATE,0)/100.0))
                                 else
                                  ((((CHANGE_PRICE/(1+NVL(rr.SERVICE_RATE,0)/100.0))/(1 + NVL(rr.TAX_RATE,0)/100.0))*(1-NVL(rr.REDUCE_BALANCE,0)/100.0)-NVL(rr.REDUCE_AMOUNT,0))*(1+NVL(rr.SERVICE_RATE,0)/100.0))
                                end) 
                            else
                                (case
                                 when rr.net_price = 0
                                 then (CHANGE_PRICE*(1-NVL(rr.REDUCE_BALANCE,0)/100.0)*(1+NVL(rr.SERVICE_RATE,0)/100.0))
                                 else
                                  ((((CHANGE_PRICE/(1+NVL(rr.SERVICE_RATE,0)/100.0))/(1 + NVL(rr.TAX_RATE,0)/100.0))*(1-NVL(rr.REDUCE_BALANCE,0)/100.0))*(1+NVL(rr.SERVICE_RATE,0)/100.0))
                                end)
                            end
                        ) as change_price
					FROM 
						room_status rs 
						INNER JOIN reservation_room rr ON rr.id = rs.reservation_room_id 
						INNER JOIN reservation on rr.reservation_id = reservation.id
                        inner join room_level on room_level.id = rr.room_level_id
                        left join customer on reservation.customer_id = customer.id
					WHERE 
						(rs.status =\'OCCUPIED\' OR rs.status =\'BOOKED\')
                        and customer.name != \'NOIBO\'
                        AND (room_level.is_virtual is null or room_level.is_virtual = 0 )
                        AND rr.foc is null
                        AND rr.foc_all = 0
                        AND (rs.in_date < rr.DEPARTURE_TIME OR  rr.DEPARTURE_TIME = rr.ARRIVAL_TIME)
						AND rs.in_date >= \''.$date_from.'\' 
						AND rs.in_date <= \''.$date_end.'\'
                       '.$cond_portal.'
                       ';
        $amount_room_days = DB::fetch($sql1,'change_price');
		return $amount_room_days;
	}
    function get_extra_service($date_from,$date_end,$portal_id,$type=false)
    {
        $portal_id_2 =' AND 0 = 0';
        if($portal_id)
        {
            if($portal_id !='ALL')
                $portal_id_2 .= ' AND EXTRA_SERVICE_INVOICE.portal_id=\''.$portal_id.'\'';
            else
                $portal_id_2 .= ' AND 1 = 1';
        }
        $cond='';
        if($type=='LATE_CHECKIN')
        {
            $cond.=' and extra_service.code=\'LATE_CHECKIN\'';
        }
        elseif($type=='SERVICE_ROOM')
        {
            $cond.='and extra_service.code!=\'LATE_CHECKIN\' and extra_service.code!=\'TRANSPORT\' and extra_service.type=\'ROOM\'';
        }
        elseif($type=='TRANSPORT')
        {
            $cond.=' and extra_service.code=\'TRANSPORT\'';
        }
        else
        {
            $cond.='and extra_service.type=\'SERVICE\' and extra_service.code!=\'TRANSPORT\'';
        }
        if($type=='LATE_CHECKIN_FOC')
        {
            $cond.='and rr.foc_all != 0';
        }
        else
        {
            $cond.='and rr.foc_all = 0';
        }
        $sql = 'select
                    sum(esid.quantity+nvl(esid.change_quantity,0)) as quantity,
                    sum(
                        CASE
                			WHEN 
                				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
                			THEN
                                ((esid.quantity+nvl(esid.change_quantity,0))*esid.price) + (((esid.quantity+nvl(esid.change_quantity,0))*esid.price)*extra_service_invoice.service_rate*0.01)
                			ELSE
                				((esid.quantity+nvl(esid.change_quantity,0))*esid.price)/(1+(extra_service_invoice.tax_rate/100))
                        END
                    ) as total_amount
                from
                    extra_service_invoice_detail esid
                    inner join extra_service on esid.service_id = extra_service.id
                    inner join extra_service_invoice on extra_service_invoice.id = esid.invoice_id
                    inner join reservation_room rr on rr.id = extra_service_invoice.reservation_room_id
                    inner join reservation on rr.reservation_id = reservation.id
                    left join customer on reservation.customer_id = customer.id
                    left join room_level on rr.room_level_id = room_level.id
                where
                    room_level.is_virtual = 0
                    AND rr.status != \'CANCEL\' AND rr.status != \'NOSHOW\'
                    and customer.name != \'NOIBO\'
                    '.$cond.'
                    AND esid.in_date >= \''.$date_from.'\' 
				    AND esid.in_date <= \''.$date_end.'\'
                     '.$portal_id_2.'
                     ';
        if(User::id()=='developer06')
        {
            //System::debug($sql);
        }
        $items = DB::fetch($sql);
        return $items;
    }
    function get_amount_bar($date_from,$date_end,$portal_id)
    {
        $time_from_day =Date_Time::to_time($date_from);
        $time_to_day =Date_Time::to_time($date_end)+86400;
        $cond = '1=1';
        if($portal_id!='')
        {
            if($portal_id != 'ALL')
            {
                $cond .=" AND bar_reservation.portal_id = '".$portal_id."'"; 
            }
        }
        $cond .= ' and bar_reservation.time_out>='.$time_from_day.' and bar_reservation.time_out<'.($time_to_day).'';
        $sql = 'SELECT
			 sum(bar_reservation.total/(1+nvl(bar_reservation.tax_rate,0)/100)) as total_amount
			FROM 
				bar_reservation
                left join reservation_room on reservation_room.id = bar_reservation.reservation_room_id
                left join reservation on reservation_room.reservation_id=reservation.id
                left join customer on reservation.customer_id = customer.id
			WHERE 
				'.$cond.'
                and (customer.name != \'NOIBO\' or customer.name is null)
                and (reservation_room.foc_all = 0 or reservation_room.foc_all is null)
			';
        $items = DB::fetch($sql,'total_amount');        
        return $items;
    }
    function get_party($date_from,$date_end,$portal_id)
    {
        $time_from_day =Date_Time::to_time($date_from);
        $time_to_day =Date_Time::to_time($date_end)+86400;
        $cond = '1=1';
        if($portal_id!='')
        {
            if($portal_id != 'ALL')
            {
                $cond .=" AND party_reservation.portal_id = '".$portal_id."'"; 
            }
        }
        $cond .= ' and party_reservation.checkin_time>=\''.$time_from_day.'\' and party_reservation.checkin_time<\''.($time_to_day).'\'';
        $party_amount = 0;
        $sql = "
                SELECT
                    sum(party_reservation.total/(1+(nvl(party_reservation.vat,0))/100)) as total_amount
                FROM
                    party_reservation
                    left join party_type on party_type.id=party_reservation.party_type
                    left join party on party.user_id=party_reservation.user_id
                    left join mice_reservation on mice_reservation.id = party_reservation.mice_reservation_id
                    left join customer on mice_reservation.customer_id = customer.id
                    left join customer_group on customer_group.id = customer.group_id
                    left join payment on payment.type='BANQUET' AND payment.bill_id=party_reservation.id                    
                WHERE
                    ".$cond."
                    AND party_reservation.status!='CANCEL'
                ";
        $party_amount = DB::fetch($sql,'total_amount'); 
        return $party_amount;         
    }
    function get_telephone($date_from,$date_end,$portal_id)
    {
        $time_from_day =Date_Time::to_time($date_from);
        $time_to_day =Date_Time::to_time($date_end)+86400;
        $cond = '';
        if($portal_id!='')
        {
            if($portal_id != 'ALL')
            {
                $cond .=" AND telephone_report_daily.portal_id = '".$portal_id."'"; 
            }
        }
        $sql = '
            SELECT
				sum(telephone_report_daily.price_vnd) AS total
			FROM
				telephone_report_daily
				
			WHERE
				telephone_report_daily.hdate >='.($time_from_day).' and telephone_report_daily.hdate <= '.($time_to_day).'
                '.$cond.'
        ';
        $telephone_amount = DB::fetch($sql,'total');
        return $telephone_amount;  
    }
    /** hàm l?y doanh thu bu?ng **/
    function get_housekeeping($date_from,$date_end,$portal_id,$type)
    {
        $time_from_day =Date_Time::to_time($date_from);
        $time_to_day =Date_Time::to_time($date_end)+86400;
        $cond = '';
        if($portal_id!='')
        {
            if($portal_id != 'ALL')
            {
                $cond .=" AND housekeeping_invoice.portal_id = '".$portal_id."'"; 
            }
        }
        $sql = "
                SELECT
                    sum(housekeeping_invoice.total/(1+(nvl(housekeeping_invoice.tax_rate,0))/100)) as total
                FROM
                    housekeeping_invoice
                    inner join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id
                    inner join reservation on reservation_room.reservation_id=reservation.id
                    inner join room on room.id=reservation_room.room_id
                    inner join room_level on room.room_level_id=room_level.id
                    left join customer on reservation.customer_id = customer.id
                WHERE
                    1=1 
                    AND housekeeping_invoice.time >= ".$time_from_day." and housekeeping_invoice.time< ".$time_to_day."
                    AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                    and (customer.name != 'NOIBO' or customer.name is null)
                    AND housekeeping_invoice.type = '".$type."'
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                    AND (reservation_room.foc_all = 0 or reservation_room.foc_all is null)
                    ".$cond."
                ORDER BY
                    housekeeping_invoice.time, reservation_room.id
                ";
        $report = DB::fetch($sql,'total');
        return $report;
    }
    /** Hàm l?y doanh thu spa **/
    function get_spa($date_from,$date_end,$portal_id)
    {
        $time_from_day =Date_Time::to_time($date_from);
        $time_to_day =Date_Time::to_time($date_end)+86400;
        $cond = '';
        if($portal_id!='')
        {
            if($portal_id != 'ALL')
            {
                $cond .=" AND housekeeping_invoice.portal_id = '".$portal_id."'"; 
            }
        }
        $sql = "
                SELECT
                    sum(massage_reservation_room.total_amount/(1+(nvl(massage_reservation_room.tax,0))/100)) as total
                FROM
                    massage_reservation_room
                    left join reservation_room on reservation_room.id=massage_reservation_room.hotel_reservation_room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                    left join room_level on reservation_room.room_level_id=room_level.id
                    left join customer on reservation.customer_id = customer.id
                WHERE
                    1=1
                    ".$cond."
                    AND massage_reservation_room.time >= ".$time_from_day." and massage_reservation_room.time< ".$time_to_day."
                    AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                    AND (reservation_room.foc_all = 0 or reservation_room.foc_all is null)
                    and (customer.name != 'NOIBO' or customer.name is null)
                ORDER BY
                    massage_reservation_room.time, massage_reservation_room.id
                ";
        $report = DB::fetch($sql,'total');
        return $report;      
    }
    function get_shop($date_from,$date_end,$portal_id)
    {
        $time_from_day =Date_Time::to_time($date_from);
        $time_to_day =Date_Time::to_time($date_end)+86400;
        $cond = '';
        if($portal_id!='')
        {
            if($portal_id != 'ALL')
            {
                $cond .=" AND ve_reservation.portal_id = '".$portal_id."'"; 
            }
        }
        $sql = "
                SELECT
                    sum(ve_reservation.total/(1+(nvl(ve_reservation.tax_rate,0))/100)) as total
                FROM
                    ve_reservation
                    LEFT JOIN reservation_room ON ve_reservation.reservation_room_id = reservation_room.id
                    LEFT JOIN reservation ON reservation_room.reservation_id = reservation.id
                    left join customer on reservation.customer_id = customer.id
                WHERE
                    1=1
                    ".$cond."
                    AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                    AND ve_reservation.time >= '".$time_from_day."' and ve_reservation.time< '".$time_to_day."'
                    AND (reservation_room.foc_all = 0 or reservation_room.foc_all is null)
                    and (customer.name != 'NOIBO' or customer.name is null)
                ";
        $report = DB::fetch($sql,'total');
        return $report;   
    }
}
$api = new api();
?>