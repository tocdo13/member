<?php
class QuickDailyRevenueReportForm extends Form
{
	function QuickDailyRevenueReportForm()
	{
		Form::Form('DailyRevenueReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
	{
		require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';
        $this->map = array();
        $cond =' 1=1 ';
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list());
        if(Url::get('portal_id'))
            $portal_id = Url::get('portal_id');
        else
        {
            $portal_id = PORTAL_ID;
            $_REQUEST['portal_id'] = PORTAL_ID;                       
        }
        if($portal_id != 'ALL')
        {
            $cond.=' AND reservation.portal_id = \''.$portal_id.'\' ';
        }
            
		$in_date = Url::get('date')?Date_Time::to_orc_date(Url::get('date')):Date_Time::to_orc_date(date('d/m/Y',time()));
        $this->map['in_date'] = Url::get('date')?Url::get('date'):date('d/m/Y',time());
        
        $current_day = true;
        //Ngay xem la ngay hien tai
        if($this->map['in_date'] == date('d/m/Y'))
        {
            
            $cond .= '
                    AND ( 
                            (reservation_room.status = \'CHECKIN\' AND reservation_room.departure_time > room_status.in_date AND  reservation_room.departure_time != reservation_room.arrival_time)
                            OR
                            (reservation_room.status = \'CHECKIN\'  AND  reservation_room.departure_time = reservation_room.arrival_time)
                            OR 
                            (
                                reservation_room.status = \'CHECKOUT\' AND reservation_room.arrival_time = reservation_room.departure_time
                            )
                        )
                        ';
            
        }
        else//qua khu
        {
            $current_day = false;
            $cond .= '
                    AND ( 
                            (reservation_room.departure_time > room_status.in_date AND  reservation_room.departure_time != reservation_room.arrival_time)
                            OR 
                            (
                                reservation_room.arrival_time = reservation_room.departure_time
                            )
                        )
                        ';
        }
        	
		$in_time = Url::get('date')?Date_Time::to_time(Url::get('date')):Date_Time::to_time(date('d/m/y'));
        $this->map['date'] = Url::get('date')?Url::get('date'):date('d/m/Y');
        $_REQUEST['date'] = $this->map['date'];
		$report = new Report;
        //Lay ca check in va out trong ngay
		$sql = '
    			SELECT
                    ROW_NUMBER() OVER (ORDER BY reservation_room.id Desc) as id, 
    				reservation_room.id as reservation_room_id ,
                    reservation.id as reservation_id,
                    reservation_room.net_price,
                    room_status.change_price,
                    reservation_room.service_rate,
                    reservation_room.tax_rate,
                    traveller.first_name || \' \' || traveller.last_name as fullname,
                    reservation_traveller.pickup,
                    reservation_traveller.see_off,
                    reservation_traveller.pickup_foc,
                    reservation_traveller.see_off_foc,
                    reservation_traveller.status as reservation_traveller_status,
                    room_status.in_date,
                    reservation_room.time_in,
                    reservation_room.time_out,
                    reservation_room.departure_time - reservation_room.arrival_time as night,
                    reservation_room.arrival_time,
                    reservation_room.departure_time,
                    reservation_room.foc,
                    reservation_room.foc_all,
                    reservation_room.note_change_room,
                    COALESCE(reservation_room.reduce_balance,0) as reduce_balance,
                    COALESCE(reservation_room.reduce_amount,0) as reduce_amount,
                    room.name as room_name,
                    reservation_room.status as reservation_room_status 
    			FROM 
    				reservation_room
    				INNER JOIN reservation on reservation_room.reservation_id=reservation.id
                    inner join room_level on room_level.id = reservation_room.room_level_id
    				INNER JOIN room_status ON room_status.reservation_room_id = reservation_room.id
                    left  join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                    left  join traveller on reservation_traveller.traveller_id = traveller.id
                    left join room on reservation_room.room_id = room.id
    			WHERE 
    				'.$cond.'
                    AND (room_level.is_virtual is null or room_level.is_virtual <> 1)
                    AND room_status.in_date = \''.$in_date.'\'
                    AND room_status.status = \'OCCUPIED\'
    			ORDER BY 
    				reservation_room.id Desc
                ';
        if(User::is_admin())
        {
            //System::debug($cond);
        }
        //echo $sql;
		$report->items = DB::fetch_all($sql);
        //du lieu co the khac voi guest in house list vi o day co ca room_status.in_date
        // con guest in house list khong co => dem ca khach check out muon
        $summary = array(
                        'no_of_room' =>0,
                        'no_of_pax' =>0,
                        'revenue' =>0,//inc BF, exc chare, tax
                        'no_of_breakfast' =>0,
                        'bf_revenue'=>0,
                        'no_of_pickup'=>0,
                        'no_of_seeoff'=>0,
                        'no_of_trans'=>0,
                        'trans_revenue'=>0,
                        'room_revenue'=>0,
                        'occupancy'=>0,
                        );
        $r_r_id = false;
        //mang nay luu cac $r_r_id dem dc 
        //$r_r = array();
        
        $str_room = "----------------------ROOM-----".$this->map['date']."-----------------------\n";
        $str_guest = "----------------------GUEST----------------------------\n";

		
        foreach($report->items as $key=>$item)
		{
            if( 
                (
                    ($item['reservation_traveller_status']=='CHECKIN' or !$current_day)  
                    && 
                    $item['reservation_traveller_status']!='CHANGE'
                    
                )
                ||//Thêm đk này để lấy dayused, trước chỉ lấy đc khi day used mà đang in, bây h lấy cả khi dayussed đó đã out
                //Chú ý đang bị mập mờ giữa latein nhưng out trong ngày -> giống dayussed
                (
                    $item['reservation_traveller_status']=='CHECKOUT' && (isset($item['time']) and  $item['arrival_time']==$item['_time'])
                ) 
                
            )
            {
                $summary['no_of_pax']++;
                if($item['pickup'] and $item['pickup_foc']==1 and strtotime($item['arrival_time']) == strtotime($in_date))
                {
                    $summary['no_of_pickup']++;
                    $summary['no_of_trans']++;
                }
                if($item['see_off'] and $item['see_off_foc']==1 and strtotime($item['departure_time']) == strtotime($in_date))
                {
                    $summary['no_of_seeoff']++;
                    $summary['no_of_trans']++;
                }
                $str_guest .= $summary['no_of_pax'].") Full name: ".$item['fullname']." - Room: ".$item['room_name']." - Recode: ".$item['reservation_id']." - Guest status: ".$item['reservation_traveller_status']." - Room arrival time: ".date('H\h:i d/m/Y',$item['time_in'])." - Room departure time: ".date('H\h:i d/m/Y',$item['time_out'])."\n";
            }
            if($item['reservation_room_id'] != $r_r_id)
            {
                $summary['no_of_room']++;
                $r_r_id = $item['reservation_room_id'];
                //array_push($r_r,$r_r_id);
                if($item['foc_all']==1 or $item['foc']!='')
                {
                   $report->items[$key]['change_price'] = 0; 
                }
                else
                {
                    if($item['net_price']==1)
                    {
                        $report->items[$key]['change_price'] = $report->items[$key]['change_price'] / (1+$item['tax_rate']/100);
                        $report->items[$key]['change_price'] = $report->items[$key]['change_price'] / (1+$item['service_rate']/100);
                        $report->items[$key]['change_price'] = $report->items[$key]['change_price'] * (1-$item['reduce_balance']/100);
                        $report->items[$key]['change_price'] = round($report->items[$key]['change_price'] - $item['reduce_amount']/($item['night']?$item['night']:1),2);
                    }
                    else//net price = 0
                    {
                        $report->items[$key]['change_price'] = $report->items[$key]['change_price']*(1-$item['reduce_balance']/100);
                        $report->items[$key]['change_price'] = round($report->items[$key]['change_price'] - $item['reduce_amount']/($item['night']?$item['night']:1),2);
                    }
                }
                $summary['revenue'] += $report->items[$key]['change_price'];
                $str_room .= $summary['no_of_room'].") Room: ".$item['room_name']." - Recode: ".$item['reservation_id']." - Status: ".$item['reservation_room_status']." - Change price: ".System::display_number($report->items[$key]['change_price'])." - Arrival time: ".date('H\h:i d/m/Y',$item['time_in'])." - Departure time: ".date('H\h:i d/m/Y',$item['time_out'])." - Link: ".Url::build('reservation',array('cmd'=>'edit','id'=>$item['reservation_id'],'r_r_id'=>$item['reservation_room_id']))."\n";
                //$summary['revenue'] = round($summary['revenue']);
            }   
            if(  ($item['reservation_traveller_status']=='CHECKIN' or !$current_day)  && $item['reservation_traveller_status']!='CHANGE')
            {
                $summary['no_of_breakfast']++;
            }	
		}
        
        //System::debug($summary);
        $sql_ex = '
                    Select 
                        extra_service_invoice.*,
                        extra_service_invoice_detail.quantity,
                        room.name as room_name,
                        reservation_room.reservation_id as reservation_id,
                        reservation_room.id as reservation_room_id,
                        reservation_room.status as reservation_room_status,
                        reservation_room.time_in,
                        reservation_room.time_out,
                        reservation_room.arrival_time,
                        reservation_room.departure_time
                    from
                        reservation_room
                        inner join extra_service_invoice on extra_service_invoice.reservation_room_id = reservation_room.id
                        inner join extra_service_invoice_detail on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
                        left join room on reservation_room.room_id = room.id
                    where
                        extra_service_invoice.portal_id = \''.PORTAL_ID.'\'
                        and extra_service_invoice.payment_type = \'ROOM\'
						AND 
                        (
                            (
                                ( extra_service_invoice.early_checkin=1 OR extra_service_invoice.late_checkout=1 ) 
                                AND extra_service_invoice_detail.in_date = \''.$in_date.'\'
                            )
                            OR 
                            (
                                extra_service_invoice.late_checkin=1 AND extra_service_invoice_detail.in_date = \''.Date_Time::to_orc_date(date('d/m/Y',$in_time-86400)).'\' AND reservation_room.arrival_time=\''.$in_date.'\'
                            )
                        )
                    ';
        //Lay cac ex service cua checkin som, check out muon
        $items = DB::fetch_all($sql_ex);
        /*
        if(User::is_admin())
        {
            System::debug($items);
        }
        */
        //System::debug($items);
        //System::debug($sql_ex);
        //System::debug($items);
        
        $str_extra = "----------------------LATE CHECKIN, LATE OUT, EARLY IN----------------------------\n";           
        $summary['revenue_late_checkin'] = 0;
        $summary['no_of_room_late_checkin'] = 0; 
		$i = 1;
        $type = "";
        
        $str_ignore = "----------------------IGNORE LATE CHECKIN AND DAYUSED----------------------------\n";
        $str_lateout = "----------------------GUEST LATE OUT----------------------------\n";
        foreach($items as $key=>$item)
		{
            if($item['late_checkin']==1)
            {
                $summary['revenue_late_checkin'] += $item['total_before_tax'];
                $summary['no_of_room_late_checkin'] += $item['quantity'];
                $type= "LATE CHECKIN";
                //Phong latein nhưng dayussed thì chỉ cần đếm latein
                if($item['arrival_time']==$item['departure_time'])
                {
                    $summary['no_of_room']--;//khong cần trừ doanh thu vì doanh thu = 0
                    $str_ignore .= " Room: ".$item['room_name']." - Type: ".$type." - Recode: ".$item['reservation_id']." - Status: ".$item['reservation_room_status']." - Arrival time: ".date('H\h:i d/m/Y',$item['time_in'])." - Departure time: ".date('H\h:i d/m/Y',$item['time_out'])." - Link: ".Url::build('extra_service_invoice',array('cmd'=>'edit','id'=>$item['id']))."\n";
                }
            }
            else
            if($item['late_checkout']==1)
            {
                $type= "LATE CHECKOUT";
                //Late out sẽ không có trên dữ liệu đếm phòng ở trên, nhưng vẫn phải đếm khách vào đây
                $count_traveller = DB::fetch('Select count(id) as id from reservation_traveller where reservation_room_id  = '.$item['reservation_room_id'].' and status != \'CHANGE\' ','id' );
                $summary['no_of_pax'] += $count_traveller;
                $str_lateout .= " Room: ".$item['room_name']." - Type: ".$type." - Recode: ".$item['reservation_id']." - Status: ".$item['reservation_room_status']." - Arrival time: ".date('H\h:i d/m/Y',$item['time_in'])." - Departure time: ".date('H\h:i d/m/Y',$item['time_out'])." - SL Khach: ".$count_traveller."\n";
            }
            else
            if($item['early_checkin']==1)
            {
                $type= "EARLY CHECKIN";
            }
            $summary['revenue'] += $item['total_before_tax'];
            $summary['no_of_room']+= $item['quantity'];
            $str_extra .= $i++.") Room: ".$item['room_name']." - Type: ".$type." - Recode: ".$item['reservation_id']." - Status: ".$item['reservation_room_status']." - Arrival time: ".date('H\h:i d/m/Y',$item['time_in'])." - Departure time: ".date('H\h:i d/m/Y',$item['time_out'])." - Link: ".Url::build('extra_service_invoice',array('cmd'=>'edit','id'=>$item['id']))."\n"; 
		}
        
		$f = fopen('packages/hotel/packages/report/modules/QuickDailyRevenueReport/'.date('H\h-ip d-m-Y',time()).'.txt','w+');
        fwrite($f, pack("CCC",0xef,0xbb,0xbf)); 
		fwrite($f,$str_room."\n\n".$str_guest."\n\n".$str_extra."\n\n".$str_ignore."\n\n".$str_lateout);
		fclose($f); 
        
        $summary['revenue'] = round($summary['revenue']);
        $summary['bf_revenue'] = $summary['no_of_breakfast'] * BREAKFAST_PRICE;
        $summary['trans_revenue'] = $summary['no_of_trans'] * PICKUP_PRICE;
        $summary['room_revenue'] = $summary['revenue'] - ($summary['bf_revenue'] + $summary['trans_revenue']);
        $summary['occupancy'] = round($summary['no_of_room']/152,2)*100;
        $_REQUEST['no_of_breakfast'] = $summary['no_of_breakfast']?$summary['no_of_breakfast']:0;
        $_REQUEST['no_of_trans'] = $summary['no_of_trans']?$summary['no_of_trans']:0;
        $_REQUEST['no_of_pickup'] = $summary['no_of_pickup']?$summary['no_of_pickup']:0;
        $_REQUEST['no_of_seeoff'] = $summary['no_of_seeoff']?$summary['no_of_seeoff']:0;
        //System::debug($summary);
        $this->parse_layout('report',$this->map+array('summary'=>$summary));	
	}
}
?>