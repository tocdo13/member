<?php
class RevenueSituationReportCommonForm extends Form
{
    
	function RevenueSituationReportCommonForm()
	{
		Form::Form('RevenueSituationReportCommonForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/chart/highcharts.src.js');
		$this->link_js('packages/core/includes/js/jquery/chart/exporting.js');
	}
	function draw()
	{
	    //echo date("d/m/Y H:i:s",1430413200);
        if(!Url::get('date'))
            $_REQUEST['date'] = date("d/m/Y");
        $this->map = array();
        $array_depart = array();
        $array_depart = $this->list_depart();
        
        $date = Date_Time::to_orc_date($_REQUEST['date']);
        $arr_time = explode("/",$_REQUEST['date']);
        $time = Date_Time::to_time($arr_time[1]."/".$arr_time[0]."/".$arr_time[2]);
        
        //echo "_".date('d/m/y',1390669200);
        //Start : Luu Nguyen GIap add portal
        if(!isset($_REQUEST['do_search']))
        {
            $_REQUEST['portal_id'] = PORTAL_ID;
        }
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()); 
        //End   :Luu Nguyen GIap add portal
        
        //$this->map['revention_indate_php'] = $this->cal_revention_bystaff_in_date($date,$array_depart);
        $this->map['revention_indate_php'] = $this->cal_revention_bystaff_in_date_revenue($date,$array_depart);//KimTan: cai này de chuyen mang sang bieu do doanh thu trong ngay
        //System::debug($this->map['revention_indate_php']);
        $this->map['revention_indate_js'] = $this->convertToJsArray($this->map['revention_indate_php']);
        $this->map['revention_dates_in_month_js'] = $this->convertToJsArray($this->cal_revention_bystaff_days_in_month($array_depart));
        $this->map['occupied_room_dates_in_month_js'] = $this->convertToJsArray($this->cal_occupied_room_bystaff_days_in_month());
        //System::debug($this->map['occupied_room_dates_in_month_js']);
        $this->map['revention_inmonth'] = $this->cal_revention_bystaff_total_in_month($array_depart);
        $this->map['real_revention_indate'] = $this->cal_real_revention_in_date($date);
        $this->map['deposit_indate'] = $this->cal_deposit_in_date($date);
        $this->map['static_room'] = $this->cal_report_room($date,$time);
        $this->map['list_part_php'] = $array_depart;
        $this->map['list_part_js'] = $this->convertToJsArray($array_depart);
        //System::debug($this->map['revention_indate_php']);
        $this->parse_layout('report',$this->map);
	}
    
    function list_depart()
    {
        $array_depart['ROOM'] = '1';
        $array_depart['HOUSEKEEPING'] = DB::fetch("select count(*) as total from department where parent_id = 0 and code = 'HK'",'total');
        $array_depart['EXTRASERVICE'] = '1';
        //$array_depart['EI_LO'] = '1';
        $array_depart['SPA'] = DB::fetch("select count(*) as total from department where parent_id = 0 and code = 'SPA'",'total');
        $array_depart['SALE'] = DB::fetch("select count(*) as total from department where parent_id = 0 and code = 'VENDING'",'total');
        $array_depart['TICKET'] = '0';
        $array_depart['BAR'] = DB::fetch("select count(*) as total from department where parent_id = 0 and code = 'RES'",'total');
        return $array_depart;
    }
    
    function convertToJsArray($phpArray)
    {
        $result = array();
        $i=1;
        foreach($phpArray as $key=>$value)
        {
            $result[$i++] = $value;
        }
        return (json_encode($result));
    }
    
    function cal_occupied_room_bystaff_days_in_month()
    {
        /** Kimtan: bieu do du kien phong ban lay them phong ei_lo_li va dayuse ngay 04/02/2015
        Cách tính: 1=tính các phòng bt - cac phong li mà dayuse(vi li se lay theo ngay su dung la ngay hom truoc neu li dayuse se tinh het hom truoc len hom nay ko con nua)
                   2=tính các khoan ei lo li theo ngay su dung(phải tính cái này vì trong room_status ếu có in_date của ngày sử dụng li, cay!)
                   ket qua = 1+2;
        **/  
        $result = array();
        $room = array();
        $total_ei_lo = array();
        $date = $_REQUEST['date'];
        $day = date('d',Date_Time::to_time($date));
        $month = date('m',Date_Time::to_time($date));
        $year = date('Y',Date_Time::to_time($date));
        //Start Luu NGuyen Giap add portal 
        if(Url::get('portal_id'))
        {
           $portal_id = Url::get('portal_id');
        }
        else
        {
            $portal_id= PORTAL_ID;
        }
        $cond_month = '';
        if($portal_id!='ALL')
        {
            $cond_month .= "AND RESERVATION.portal_id='".$portal_id."'";
        }
        
        //End Luu NGuyen Giap add portal
        for($i = 1; checkdate($month,$i,$year); $i++){
            $day_in_month = Date_Time::to_orc_date($i.'/'.$month.'/'.$year);
            $time6h = Date_Time::to_time($i.'/'.$month.'/'.$year)+(6*3600);
            //Kimtan::vi lay late in vao phong den cua ngay hom truoc len loai nhung phong late in ma dayuse cua ngay hom nay
            $sql=(" select  RESERVATION_ROOM.id from RESERVATION_ROOM
                                        inner join room_status on RESERVATION_ROOM.ID = ROOM_STATUS.RESERVATION_ROOM_ID
                                            inner join RESERVATION on RESERVATION.ID = ROOM_STATUS.RESERVATION_ID
                                            left join room_level  on reservation_room.room_level_id = room_level.id
                                        where 
                                        (room_status.status = 'OCCUPIED' or room_status.status = 'BOOKED') 
                                            and (room_status.in_date < reservation_room.departure_time 
                                                or 
                                                (reservation_room.departure_time=reservation_room.arrival_time
                                                   and 
                                                      (
                                                      (reservation_room.time_in >=".$time6h." and reservation_room.change_room_from_rr is null and reservation_room.change_room_to_rr is null)
                                                        or
                                                      (reservation_room.old_arrival_time >=".$time6h." and reservation_room.change_room_from_rr is not null and reservation_room.change_room_to_rr is null)
                                                      )
                                                )
                                                )
                                            AND (ROOM_STATUS.HOUSE_STATUS != 'HOUSEUSE' OR ROOM_STATUS.HOUSE_STATUS is null)
                                            and reservation_room.status != 'CANCEL' and reservation_room.status != 'NOSHOW'
                                            and room_level.is_virtual = 0 
                                            and TO_CHAR(room_status.in_date, 'DD-MON-YYYY') = '".$day_in_month."' ".$cond_month);
             $temp =   DB::fetch_all($sql);
             if(User::id()=='developer06')
             {
                //System::debug($sql);
             }
             $room[$i] = count($temp);
             //System::debug($room);
             $sql_ei_lo=("select extra_service_invoice_detail.in_date as id, sum(extra_service_invoice_detail.quantity + nvl(extra_service_invoice_detail.change_quantity,0)) as quantity from extra_service_invoice_detail
                                       inner join EXTRA_SERVICE_INVOICE on EXTRA_SERVICE_INVOICE.id = EXTRA_SERVICE_INVOICE_DETAIL.invoice_id
                                       inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                                       inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                                       left join room_level on reservation_room.room_level_id = room_level.id
                                       where 
                                       extra_service_invoice_detail.in_date = '".$day_in_month."'
                                       and (extra_service.code = 'LATE_CHECKIN' or extra_service.code = 'LATE_CHECKOUT' or extra_service.code = 'EARLY_CHECKIN')
                                       and NVL(room_level.is_virtual,0) = 0
                                       group by extra_service_invoice_detail.in_date
                                        "); 
            $total_ei_lo[$i] = DB::fetch($sql_ei_lo,'quantity');
            //System::debug($sql_ei_lo);
            if($total_ei_lo[$i]=='')
            {
                $total_ei_lo[$i] = 0;
            }
            $result[$i] = $total_ei_lo[$i]+$room[$i];
        }
        return $result;
    }
    
    function cal_report_room($date,$time)
    {
        $time6h_today = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date,'/'))+(6*3600);
        $time6h_tomorrow = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date,'/'))+86400+(6*3600);
        $result = array();
        $result['ROOM_ARRIVE'] = 0;
        $result['ROOM_DEPART'] = 0;
        $result['ROOM_OCCUTIE'] = 0;
        $result['ROOM_EI_LO'] = 0;
        $result['ROOM_CANCEL'] = 0;
        $result['ROOM_REPAIR'] = 0;
        $result['ROOM_PRICE'] = 0;
        $result['ROOM_CAPACITY'] = 0;
        $result['ROOM_GUEST'] = 0;
        $result['ROOM_GUEST_CHILD'] = 0;
        $result['ROOM_EXTRA_BED'] = 0;
        $result['ROOM_EI_LO'] = 0;
        $result['ROOM_EI_LO_REVENUE']=0;
        $result['ROOM_REVENUE'] = 0;
        $result['ROOM_DAYUSE'] = 0;
        $result['ROOM_BOOKED'] = 0;
        /**
        KimTan: phòng đến
            2014:lay phong den trong ngay co ngay den bang ngay xem bao cao tru phong cancel,phong ao,doi phong(trang thai dang checkin va book)
            04/02/15:thêm nhung phong li thi xet theo ngay su dung chu ko xet theo ngay den bthuong
        */
        $query_room_arrive = "
        select count(*) as total
        from reservation_room 
        inner join reservation on reservation_room.reservation_id = reservation.id
        left join room_level  on reservation_room.room_level_id = room_level.id
        where
         reservation_room.status != 'CANCEL' and reservation_room.status != 'NOSHOW'
         and room_level.is_virtual = 0 
         --KimTan: xu ly doi phong moi
         and(
                (change_room_to_rr is null and change_room_from_rr is null and reservation_room.time_in >= '".$time6h_today."' and reservation_room.arrival_time ='".$date."')--kimtan:06/02/15 phong bt co ngay den = ngay xem va ko phai phong li   
                or
                (change_room_to_rr is null and change_room_from_rr is not null and reservation_room.old_arrival_time >= '".$time6h_today."' and from_unixtime(reservation_room.old_arrival_time) ='".$date."')-- phong doi chang cuoi co old_arrival_time = ngay xem va ko phai phong li
            ) 
         --end KimTan    
         ";
        //KimTan: lay phong booked(gan phong+chua gan phong) trong ngay tru cancel,ph�ng ao,houseuse
        $query_room_booked = "
        select count(*) as total
        from reservation_room 
        inner join reservation on reservation_room.reservation_id = reservation.id
        left join room_level  on reservation_room.room_level_id = room_level.id
        left join room_status on room_status.reservation_room_id = reservation_room.id
        where
        TO_CHAR(room_status.in_date, 'DD-MON-YYYY') = '".$date."'
        and TO_CHAR(reservation_room.departure_time, 'DD-MON-YYYY') != '".$date."'
        and reservation_room.status = 'BOOKED'
        and room_level.is_virtual = 0 
        and (room_status.house_status != 'READLY' or room_status.house_status is null)
         ";
        //KimTan: dem phong di:lay nhung phong co ngay di = ngay xem, co trang thai checkin hoac checkout,ko tinh phong ao,ko tinh truong hop doi phong check in
        $query_room_departure = "
        select count(*) as total
        from reservation_room 
        inner join reservation on reservation_room.reservation_id = reservation.id
        left join room_level on reservation_room.room_level_id = room_level.id
        where (reservation_room.status = 'CHECKOUT' OR reservation_room.status = 'CHECKIN') and TO_CHAR(reservation_room.DEPARTURE_TIME, 'DD-MON-YYYY') = '".$date."'
        and room_level.is_virtual = 0
        and reservation_room.change_room_to_rr is null --KimTan: chi can khong tinh la phong doi vi ngay di phong duoc doi den van nhu cu
        ";
        //KimTan: lay nhung phong khach o co ngay di lon hon ngay xem,ko tinh phong ao va houseuse 
        $query_room_occutie = "
        select count(distinct room_status.ID) as total
        from room_status 
        inner join reservation_room on reservation_room.id = room_status.reservation_room_id
        inner join reservation on reservation_room.reservation_id = reservation.id
        left join room_level  on reservation_room.room_level_id = room_level.id
        where
        room_status.status = 'OCCUPIED'
        and (room_status.house_status != 'HOUSEUSE' or room_status.house_status is null)
        and date_to_unix(room_status.in_date) = '".Date_Time::to_time(Date_Time::convert_orc_date_to_date($date,'/'))."'
        and date_to_unix(DEPARTURE_TIME) > '".Date_Time::to_time(Date_Time::convert_orc_date_to_date($date,'/'))."'
        and NVL(room_level.is_virtual,0) = 0 
         ";
        //KimTan:dem phong dayuse:phong co ngay den bang ngay di ,tru phong ao va ---moi them cai nay ngay 04/04/15: ko phai li(có time_in lớn hơn 6h)---
        $query_room_dayuse = "
         select count(*) as total
         from reservation_room
         inner join reservation on reservation.id = reservation_room.reservation_id 
         left join room_level on reservation_room.room_level_id = room_level.id 
         where 
         TO_CHAR(reservation_room.ARRIVAL_TIME, 'DD-MON-YYYY') = '".$date."'
         and ARRIVAL_TIME = DEPARTURE_TIME
         and NVL(room_level.is_virtual,0) = 0
         --KimTan : tru truong hop doi phong: tru phong doi di va chi tinh phong duoc doi den neu old_arrival_time = ngay xem
         and change_room_to_rr is null
         and ((change_room_from_rr is null and time_in>'".$time6h_today."')or(change_room_from_rr is not null and  from_unixtime(old_arrival_time) = '".$date."' and old_arrival_time>'".$time6h_today."'))
         and reservation_room.status != 'CANCEL' and reservation_room.status != 'NOSHOW'
         ";
         //System::debug($query_room_dayuse);
        $query_room_cancel = "
        select count(*) as total
        from reservation_room 
        inner join reservation on reservation.id =reservation_room.reservation_id  
        where 
        reservation_room.time_cancel >= ".Date_Time::to_time(Date_Time::convert_orc_date_to_date($date,'/'))." 
        and reservation_room.time_cancel <= ".(Date_Time::to_time(Date_Time::convert_orc_date_to_date($date,'/'))+(86399))."
        and reservation_room.status = 'CANCEL'";
        $query_room_repair = "
        select count(distinct ROOM_ID) as total
        from ROOM_STATUS 
        inner join room on ROOM_STATUS.room_id = room.id
        where HOUSE_STATUS = 'REPAIR' and TO_CHAR(IN_DATE, 'DD-MON-YYYY') = '".$date."'";
        //System::debug($query_room_repair);
        $query_room_price = "
         select
         sum(
            case
            when room_status.in_date = reservation_room.arrival_time
            then 
                (case
                 when RESERVATION_ROOM.net_price = 0
                 then ((CHANGE_PRICE*(1-NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0)-NVL(RESERVATION_ROOM.REDUCE_AMOUNT,0))*(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))*(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)
                 else
                  ((((CHANGE_PRICE/(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))/(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0))*(1-NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0)-NVL(RESERVATION_ROOM.REDUCE_AMOUNT,0))*(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))*(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)
                end) 
            else
                (case
                 when RESERVATION_ROOM.net_price = 0
                 then (CHANGE_PRICE*(1-NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0)*(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))*(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)
                 else
                  ((((CHANGE_PRICE/(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))/(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0))*(1-NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0))*(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))*(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)
                end)
            end)  as total
           
        from ROOM_STATUS
        inner join RESERVATION on RESERVATION.ID = ROOM_STATUS.RESERVATION_ID
        inner join RESERVATION_ROOM on RESERVATION_ROOM.ID = ROOM_STATUS.RESERVATION_ROOM_ID
        left join room_level on RESERVATION_ROOM.room_level_id = room_level.id
        WHERE 
        (ROOM_STATUS.STATUS = 'OCCUPIED' OR ROOM_STATUS.STATUS = 'BOOKED')
        AND (ROOM_STATUS.HOUSE_STATUS != 'HOUSEUSE' OR ROOM_STATUS.HOUSE_STATUS is null)
        AND (RESERVATION_ROOM.FOC is null OR RESERVATION_ROOM.FOC_ALL = 0)
        and ROOM_STATUS.change_price > 0
        and NVL(room_level.is_virtual,0) = 0
        and reservation_room.foc is null
        and reservation_room.foc_all = 0
        and TO_CHAR(room_status.IN_DATE, 'DD-MON-YYYY') = '".$date."' AND (IN_DATE < DEPARTURE_TIME OR  TO_CHAR(DEPARTURE_TIME, 'DD-MON-YYYY')=TO_CHAR(ARRIVAL_TIME, 'DD-MON-YYYY'))
        ";
        //System::debug($query_room_price);
        $query_room_houseuse_checkin = "
        select count(*) as total
        from ROOM_STATUS 
        inner join reservation_room on reservation_room.id = room_status.reservation_room_id
        where HOUSE_STATUS = 'HOUSEUSE' and TO_CHAR(IN_DATE, 'DD-MON-YYYY') = '".$date."'
        and reservation_room.status = 'CHECKIN' and reservation_room.time_out > '".$time."' and reservation_room.time_in < '".($time+86400)."'";
        
        
        
        /** manh tinh lai tong so phong theo lich su **/
        /**
            $query_room_total = "
            select count(*) as total
            from ROOM
            inner join room_level on room.room_level_id = room_level.id
            where room_level.is_virtual = 0 ";
        **/
        $total_room = 0;
        if(Url::get('portal_id') !='ALL')
        {
            $portal = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
            //System::debug('select max(in_date) as in_date from room_history where in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$time)).'\' and portal_id=\''.$portal.'\'');
            if($his_in_date = DB::fetch('select max(in_date) as in_date from room_history where in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$time)).'\' and portal_id=\''.$portal.'\'','in_date'))
            {
                $total_room = DB::fetch('select 
                                            count(rhd.room_id) as total_room 
                                        from
                                            room_history_detail rhd
                                            inner join room_history rh on rh.id=rhd.room_history_id
                                            inner join room_level on room_level.id = rhd.room_level_id
                                        where
                                            rh.in_date=\''.$his_in_date.'\'
                                            and rh.portal_id=\''.$portal.'\'
                                            and rhd.close_room=1
                                            and room_level.is_virtual = 0
                                            ','total_room');                       
            }
            elseif($his_in_date = DB::fetch('select min(in_date) as in_date from room_history where in_date>\''.Date_Time::to_orc_date(date('d/m/Y',$time)).'\' and portal_id=\''.$portal.'\'','in_date'))
            {
                $total_room = DB::fetch('select 
                                            count(rhd.room_id) as total_room 
                                        from
                                            room_history_detail rhd
                                            inner join room_history rh on rh.id=rhd.room_history_id
                                            inner join room_level on room_level.id = rhd.room_level_id
                                        where
                                            rh.in_date=\''.$his_in_date.'\'
                                            and rh.portal_id=\''.$portal.'\'
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
                if($his_in_date = DB::fetch('select max(in_date) as in_date from room_history where in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$time)).'\' and portal_id=\''.$value['id'].'\'','in_date'))
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
                elseif($his_in_date = DB::fetch('select min(in_date) as in_date from room_history where in_date>\''.Date_Time::to_orc_date(date('d/m/Y',$time)).'\' and portal_id=\''.$value['id'].'\'','in_date'))
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
        
        //System::debug($query_room_total);
        $query_room_extrabed = "
        select sum(EXTRA_BED) as total
        from reservation_room 
        inner join reservation on reservation.id=reservation_room.reservation_id 
        where reservation_room.status = 'CHECKIN' and reservation_room.extra_bed_from_date <= '".$date."' and reservation_room.extra_bed_to_date >= '".$date."'";
        //KimTan: dem so luong ng lon tre em tren form dat phong cua nhung phong o qua ngay,bo phong ao,
        $query_room_guest = "
        select sum(adult) as total,
        sum(child) as total_child
        from room_status 
        inner join reservation_room on reservation_room.id = room_status.reservation_room_id
        inner join reservation on reservation_room.reservation_id = reservation.id
        left join room_level  on reservation_room.room_level_id = room_level.id
        where 
        room_status.status = 'OCCUPIED'
        and (room_status.house_status != 'HOUSEUSE' or room_status.house_status is null)
        and TO_CHAR(room_status.in_date, 'DD-MON-YYYY') = '".$date."'
        and TO_CHAR(DEPARTURE_TIME, 'DD-MON-YYYY') > '".$date."'
        and NVL(room_level.is_virtual,0) = 0 ";
        $query_room_ei_lo = "
        select 
            case
                when sum(EXTRA_SERVICE_INVOICE_DETAIL.quantity ) < 1
                then concat('0',sum( EXTRA_SERVICE_INVOICE_DETAIL.quantity))
            else concat('',sum( EXTRA_SERVICE_INVOICE_DETAIL.quantity))
            end as total,
            sum(
                EXTRA_SERVICE_INVOICE.total_amount
            ) as total_amount
        from EXTRA_SERVICE_INVOICE 
            inner join EXTRA_SERVICE_INVOICE_DETAIL on EXTRA_SERVICE_INVOICE.id = EXTRA_SERVICE_INVOICE_DETAIL.invoice_id
            inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
            inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
            inner join reservation on reservation_room.reservation_id = reservation.id
            left join room_level on reservation_room.room_level_id = room_level.id
        where TO_CHAR(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE, 'DD-MON-YYYY') = '".$date."'
            and room_level.is_virtual = 0 
            and (extra_service.code = 'LATE_CHECKIN' or extra_service.code = 'LATE_CHECKOUT' or extra_service.code = 'EARLY_CHECKIN')"; 
        //echo $query_room_extrabed;
        //System::debug($query_room_ei_lo);
        //Start Luu Nguyen Giap add portal
         if(Url::get('portal_id'))
        {
           $portal_id =  Url::get('portal_id');
        }
        else
        {
            $portal_id =PORTAL_ID;
        }
        
        if($portal_id!="ALL")
        {
            $query_room_arrive.=" AND reservation.portal_id='".$portal_id."'";
            $query_room_departure.=" AND reservation.portal_id='".$portal_id."'";
            $query_room_dayuse.="AND reservation.portal_id='".$portal_id."'";
            $query_room_booked.="AND reservation.portal_id='".$portal_id."'";
            $query_room_occutie.=" AND reservation.portal_id='".$portal_id."'";
            $query_room_ei_lo.=" AND EXTRA_SERVICE_INVOICE.portal_id='".$portal_id."'";
            $query_room_price.=" AND reservation.portal_id='".$portal_id."'";
            //$query_room_total.=" AND ROOM.portal_id='".$portal_id."'";
            $query_room_repair.=" AND ROOM.portal_id='".$portal_id."'";
            $query_room_guest.=" AND RESERVATION.portal_id='".$portal_id."'";
            $query_room_cancel.=" AND RESERVATION.portal_id='".$portal_id."'";
            $query_room_extrabed .=" AND RESERVATION.portal_id='".$portal_id."'";
        }  
        //End Luu Nguyen Giap add portal
        $result['ROOM_BOOKED'] = DB::fetch($query_room_booked,'total');
        $result['ROOM_ARRIVE'] = DB::fetch($query_room_arrive,'total');
        $result['ROOM_DAYUSE'] = DB::fetch($query_room_dayuse,'total');
        $result['ROOM_DEPART'] = DB::fetch($query_room_departure,'total');
        $result['ROOM_OCCUTIE'] = DB::fetch($query_room_occutie,'total');
        $result['ROOM_CANCEL'] = DB::fetch($query_room_cancel,'total');
        $result['ROOM_REPAIR'] = DB::fetch($query_room_repair,'total');
        //System::debug($result['ROOM_REPAIR']);
        $result['ROOM_EI_LO'] = DB::fetch($query_room_ei_lo,'total');
        $result['ROOM_EI_LO_REVENUE'] = DB::fetch($query_room_ei_lo,'total_amount');
        $result['ROOM_REVENUE'] = DB::fetch($query_room_price,'total');
        //echo $result['ROOM_EI_LO_REVENUE'];
        $room_revenue = $result['ROOM_REVENUE'] + $result['ROOM_EI_LO_REVENUE'];
        $result['ROOM_REVENUE'] = $room_revenue;
        //echo $room_revenue;
        $room_i = $result['ROOM_OCCUTIE']+$result['ROOM_DAYUSE'] + $result['ROOM_EI_LO'] + $result['ROOM_BOOKED'];
        //echo $room_i;
        //echo $room_revenue;
        if($room_i != 0)
        {
            $result['ROOM_PRICE'] = $room_revenue/$room_i;
        }
        else
        {
            $result['ROOM_PRICE'] = 0;
        }
        //echo $room_i.'-'.$total_room.'-'.$result['ROOM_REPAIR'];
        $result['ROOM_CAPACITY'] = ($room_i)/($total_room-$result['ROOM_REPAIR'])*100; //DB::fetch($query_room_total,'total')
        $result['ROOM_GUEST'] = DB::fetch($query_room_guest,'total');
        $result['ROOM_GUEST_CHILD'] = DB::fetch($query_room_guest,'total_child');
        $result['ROOM_EXTRA_BED'] = DB::fetch($query_room_extrabed,'total');
                                  
        return $result;
    }
    
    function cal_real_revention_in_date($date)
    {
        $result = array();
        $result['CASH'] = array('TOTAL_VND'=>0,'TOTAL_USD'=>0,'TOTAL'=>0);
        $result['CREDIT_CARD'] = array('TOTAL_VND'=>0,'TOTAL_USD'=>0,'TOTAL'=>0);
        $result['DEBIT'] = array('TOTAL_VND'=>0,'TOTAL_USD'=>0,'TOTAL'=>0);
        $result['FOC'] = array('TOTAL_VND'=>0,'TOTAL_USD'=>0,'TOTAL'=>0);
        $result['BANK'] = array('TOTAL_VND'=>0,'TOTAL_USD'=>0,'TOTAL'=>0);
        $result['REFUND'] = array('TOTAL_VND'=>0,'TOTAL_USD'=>0,'TOTAL'=>0);
        //Start Luu Nguyen Giap add portal 
        if(Url::get('portal_id'))
        {
           $portal_id =  Url::get('portal_id');
        }
        else
        {
            $portal_id = PORTAL_ID;
        }
        
        if($portal_id!="ALL")
        {
            $cond = " AND PAYMENT.portal_id ='".$portal_id."'";
            $cond_folio = " and folio.portal_id ='".$portal_id."'";  
        
        }
        else
        {
            $cond = "";
            $cond_folio = "";
        }
        //KimTan:thêm cái mới.cái bên trên đang đúng nhhưng vì trường hợp 2 bản ghi của nhà hàng lên phai làm cái này
        $query_real_revenue = "
        select PAYMENT.id,PAYMENT.AMOUNT,PAYMENT.PAYMENT_TYPE_ID,PAYMENT.CURRENCY_ID,PAYMENT.EXCHANGE_RATE,
        NVL(room_level.is_virtual,0) as is_vistual
        from PAYMENT
        left join reservation_room on PAYMENT.reservation_room_id = reservation_room.id
        left join room_level on reservation_room.room_level_id = room_level.id 
        left join bar_reservation on payment.bill_id = bar_reservation.id
        where TO_CHAR(FROM_UNIXTIME(PAYMENT.TIME), 'DD-MON-YYYY') = '".$date."'
         AND TYPE_DPS IS NULL
         and NVL(room_level.is_virtual,0) != 1
         and (PAYMENT.type!='BAR' or (PAYMENT.type='BAR' and nvl(bar_reservation.pay_with_room,0)!=1))
         ".$cond;
        //end KimTan:
        $temp = DB::fetch_all($query_real_revenue);
        //System::debug($query_real_revenue);
        //System::debug($temp);
        foreach($temp as $id => $value)
        {
            switch($value['payment_type_id'])
            {
                case "CASH":
                {
                    if($value['currency_id']=='VND')
                    {
                        $result['CASH']['TOTAL_VND']+=$value['amount'];
                        $result['CASH']['TOTAL']+=$value['amount'];
                    }
                    else
                    {
                        $result['CASH']['TOTAL_USD']+=$value['amount'];
                        $result['CASH']['TOTAL']+=$value['amount']*$value['exchange_rate'];
                    }
                    break;
                }
                
                case "CREDIT_CARD":
                {
                    if($value['currency_id']=='VND')
                    {
                        $result['CREDIT_CARD']['TOTAL_VND']+=$value['amount'];
                        $result['CREDIT_CARD']['TOTAL']+=$value['amount'];
                    }
                    else
                    {
                        $result['CREDIT_CARD']['TOTAL_USD']+=$value['amount'];
                        $result['CREDIT_CARD']['TOTAL']+=$value['amount']*$value['exchange_rate'];
                    }
                    break;
                }
                
                case "REFUND":
                {
                    
                    if($value['currency_id']=='VND')
                    {
                        $result['REFUND']['TOTAL_VND']+=$value['amount'];
                        $result['REFUND']['TOTAL']+=$value['amount'];
                    }
                    else
                    {
                        $result['REFUND']['TOTAL_USD']+=$value['amount'];
                        $result['REFUND']['TOTAL']+=$value['amount']*$value['exchange_rate'];
                    }
                    break;
                }
                
                case "FOC":
                {
                    if($value['currency_id']=='VND')
                    {
                        $result['FOC']['TOTAL_VND']+=$value['amount'];
                        $result['FOC']['TOTAL']+=$value['amount'];
                    }
                    else
                    {
                        $result['FOC']['TOTAL_USD']+=$value['amount'];
                        $result['FOC']['TOTAL']+=$value['amount']*$value['exchange_rate'];
                    }
                    break;
                }
                
                case "BANK":
                {
                    if($value['currency_id']=='VND')
                    {
                        $result['BANK']['TOTAL_VND']+=$value['amount'];
                        $result['BANK']['TOTAL']+=$value['amount'];
                    }
                    else
                    {
                        $result['BANK']['TOTAL_USD']+=$value['amount'];
                        $result['BANK']['TOTAL']+=$value['amount']*$value['exchange_rate'];
                    }
                    break;
                }
                
                case "DEBIT":
                {
                    if($value['currency_id']=='VND')
                    {
                        $result['DEBIT']['TOTAL_VND']+=$value['amount'];
                        $result['DEBIT']['TOTAL']+=$value['amount'];
                    }
                    else
                    {
                        $result['DEBIT']['TOTAL_USD']+=$value['amount'];
                        $result['DEBIT']['TOTAL']+=$value['amount']*$value['exchange_rate'];
                    }
                    break;
                }
            }
        }                   
        return $result;
    }
    
    function cal_deposit_in_date($date)
    //----Tan:dat coc trong ngay
    {
        $result = array();
        $result['CASH'] = array('TOTAL_VND'=>0,'TOTAL_USD'=>0,'TOTAL'=>0);
        $result['CREDIT_CARD'] = array('TOTAL_VND'=>0,'TOTAL_USD'=>0,'TOTAL'=>0);
        $result['BANK'] = array('TOTAL_VND'=>0,'TOTAL_USD'=>0,'TOTAL'=>0);
        //Start Luu Nguyen Giap add portal 
        if(Url::get('portal_id'))
        {
           $portal_id =  Url::get('portal_id');
        }
        else
        {
            $portal_id = PORTAL_ID;
        }
        
        if($portal_id!="ALL")
        {
            $cond = " AND PAYMENT.portal_id ='".$portal_id."'";  
        }
        else
        {
            $cond = "";
        }
        //End Luu Nguyen Giap add portal
        $query_real_revenue = "
        select id,AMOUNT,PAYMENT_TYPE_ID,CURRENCY_ID,EXCHANGE_RATE
        from PAYMENT 
        where TO_CHAR(FROM_UNIXTIME(TIME), 'DD-MON-YYYY') = '".$date."' AND TYPE_DPS IS NOT NULL".$cond;
        
        $temp = DB::fetch_all($query_real_revenue);
        //System::debug($temp);
        //System::debug($query_real_revenue);
        foreach($temp as $id => $value)
        {
            switch($value['payment_type_id'])
            {
                case "CASH":
                {
                    if($value['currency_id']=='VND')
                    {
                        $result['CASH']['TOTAL_VND']+=$value['amount'];
                        $result['CASH']['TOTAL']+=$value['amount'];
                    }
                    else
                    {
                        $result['CASH']['TOTAL_USD']+=$value['amount'];
                        $result['CASH']['TOTAL']+=$value['amount']*$value['exchange_rate'];
                    }
                    break;
                }
                
                case "CREDIT_CARD":
                {
                    if($value['currency_id']=='VND')
                    {
                        $result['CREDIT_CARD']['TOTAL_VND']+=$value['amount'];
                        $result['CREDIT_CARD']['TOTAL']+=$value['amount'];
                    }
                    else
                    {
                        $result['CREDIT_CARD']['TOTAL_USD']+=$value['amount'];
                        $result['CREDIT_CARD']['TOTAL']+=$value['amount']*$value['exchange_rate'];
                    }
                    break;
                }
                
                case "BANK":
                {
                    if($value['currency_id']=='VND')
                    {
                        $result['BANK']['TOTAL_VND']+=$value['amount'];
                        $result['BANK']['TOTAL']+=$value['amount'];
                    }
                    else
                    {
                        $result['BANK']['TOTAL_USD']+=$value['amount'];
                        $result['BANK']['TOTAL']+=$value['amount']*$value['exchange_rate'];
                    }
                    break;
                }
            }
        }
                                  
        return $result;
    }
    //Tan:du kien doanh thu trong thang(se lam giong voi doanh thu trong ngay chi khac ve dieu kien thoi gian)
    function cal_revention_bystaff_total_in_month($array_depart)
    {
        $result = array();
        $result['TOTAL_ROOM_IN_MONTH'] = 0;
        $result['TOTAL_HOUSEKEEPING_IN_MONTH'] = 0;
        $result['TOTAL_EXTRASERVICE_IN_MONTH'] = 0;
        $result['TOTAL_SPA_IN_MONTH'] = 0;
        $result['TOTAL_SALE_IN_MONTH'] = 0;
        $result['TOTAL_TICKET_IN_MONTH'] = 0;
        $result['TOTAL_BAR_IN_MONTH'] = 0;
        
        $date = $_REQUEST['date'];
        
        $month = date('m',Date_Time::to_time($date));
        $year = date('Y',Date_Time::to_time($date));
        if($month == 12)
        {
            $next_month = 1;
            $next_year = $year+1;
        }
        else
        {
            $next_month = $month+1;
            $next_year = $year;
        }
        
        //$date = Date_Time::to_orc_date($date);
        $begin_month = Date_Time::to_orc_date('1/'.$month.'/'.$year);
        $begin_next_month = Date_Time::to_orc_date('1/'.$next_month.'/'.$next_year);
        //tan: lay doanh thu phong da tinh net_price cua nhung phong co luoc do gia trong thang xem bao cao(phong occ+dayuse trong thang xem bao cao)
        //ko tinh nhung phong ao,neu co giam gia theo so tien thi se chi tinh trong ngay dau tien
        $query_total_revenue = "
         select
         room_status.id,
            case
            when room_status.in_date = reservation_room.arrival_time
            then 
                (case
                 when RESERVATION_ROOM.net_price = 0
                 then ((CHANGE_PRICE*(1-NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0)-NVL(RESERVATION_ROOM.REDUCE_AMOUNT,0))*(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))*(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)
                 else
                  ((((CHANGE_PRICE/(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))/(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0))*(1-NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0)-NVL(RESERVATION_ROOM.REDUCE_AMOUNT,0))*(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))*(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)
                end) 
            else
                (case
                 when RESERVATION_ROOM.net_price = 0
                 then (CHANGE_PRICE*(1-NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0)*(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))*(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)
                 else
                  ((((CHANGE_PRICE/(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))/(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0))*(1-NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0))*(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))*(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)
                end)
            end  as total,
        RESERVATION_ROOM.SERVICE_RATE,
        RESERVATION_ROOM.TAX_RATE,
        NVL(reservation_room.adult,0) adult,
        NVL(reservation_room.child,0) child,
        reservation_room.breakfast    
        from ROOM_STATUS
        inner join RESERVATION on RESERVATION.ID = ROOM_STATUS.RESERVATION_ID
        inner join RESERVATION_ROOM on RESERVATION_ROOM.ID = ROOM_STATUS.RESERVATION_ROOM_ID
        left join room_level on RESERVATION_ROOM.room_level_id = room_level.id
        WHERE 
        (ROOM_STATUS.STATUS = 'OCCUPIED' OR ROOM_STATUS.STATUS = 'BOOKED')
        AND (ROOM_STATUS.HOUSE_STATUS != 'HOUSEUSE' OR ROOM_STATUS.HOUSE_STATUS is null)
        AND (RESERVATION_ROOM.FOC is null OR RESERVATION_ROOM.FOC_ALL = 0)
        and ROOM_STATUS.change_price > 0
        and NVL(room_level.is_virtual,0) = 0
        and reservation_room.foc is null
        and reservation_room.foc_all = 0
        and ";
        $query_total_ei_lo = "
        select 
        sum(
            CASE
    			WHEN 
    				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
    			THEN
    				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
    			ELSE
    				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)
           END
        ) as total
        from EXTRA_SERVICE_INVOICE_DETAIL 
        inner join EXTRA_SERVICE_INVOICE on EXTRA_SERVICE_INVOICE.id = EXTRA_SERVICE_INVOICE_DETAIL.invoice_id
        inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
        inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
        left join room_level on reservation_room.room_level_id = room_level.id
        where 
        NVL(room_level.is_virtual,0) = 0
        and (extra_service.code = 'LATE_CHECKIN' or extra_service.code = 'LATE_CHECKOUT' or extra_service.code = 'EARLY_CHECKIN')
        and
        ";
        $query_total_housekeepng = "
        select sum(
                    case
                    when (reservation_room.foc_all != 0 or reservation_room.status = 'CANCEL' or reservation_room.status != 'NOSHOW')
                    then 0
                    else
                    housekeeping_invoice.total
                    end
                    ) as total
        from HOUSEKEEPING_INVOICE
        left join reservation_room on HOUSEKEEPING_INVOICE.reservation_room_id = reservation_room.id
        left join room_level on reservation_room.room_level_id = room_level.id
        where ";
        $query_total_extraservice = "
        select 
        sum(
            CASE
    			WHEN 
    				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
    			THEN
    				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
    			ELSE
    				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)
           END
        ) as total
        from EXTRA_SERVICE_INVOICE_DETAIL 
        inner join EXTRA_SERVICE_INVOICE on EXTRA_SERVICE_INVOICE.id = EXTRA_SERVICE_INVOICE_DETAIL.invoice_id
        inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
        left join reservation_room on EXTRA_SERVICE_INVOICE.reservation_room_id = reservation_room.id
        left join room_level on reservation_room.room_level_id = room_level.id
        where
        (reservation_room.status='CHECKIN' OR reservation_room.status='CHECKOUT'  OR reservation_room.status='BOOKED')
        and extra_service_invoice.payment_type = 'SERVICE'
        and ((extra_service.code != 'LATE_CHECKIN' and extra_service.code != 'LATE_CHECKOUT' and extra_service.code != 'EARLY_CHECKIN') or extra_service.code is null)
         ";
        $query_total_spa = "
        select sum(
        MASSAGE_RESERVATION_ROOM.total_amount
        ) as total
        from MASSAGE_PRODUCT_CONSUMED
        inner join MASSAGE_RESERVATION_ROOM on MASSAGE_PRODUCT_CONSUMED.RESERVATION_ROOM_ID = MASSAGE_RESERVATION_ROOM.ID
        left join room on MASSAGE_PRODUCT_CONSUMED.room_id = room.id
        left join room_level on room.room_level_id = room_level.id
        where MASSAGE_PRODUCT_CONSUMED.STATUS != 'CANCEL' and ";
        $query_total_sale = "
        select sum(TOTAL) as total
        from VE_RESERVATION
        where ";
        $query_total_ticket = "
        select sum(TOTAL) as total
        from TICKET_INVOICE
        where ";
        $query_total_bar = "
        select sum(BAR_RESERVATION.TOTAL) as total
        from BAR_RESERVATION
        left join reservation_room on BAR_RESERVATION.reservation_room_id = reservation_room.id
        left join room_level on reservation_room.room_level_id = room_level.id
        where (BAR_RESERVATION.STATUS='CHECKOUT' OR BAR_RESERVATION.STATUS='BOOKED' OR BAR_RESERVATION.STATUS='CHECKIN')
        and NVL(room_level.is_virtual,0) = 0
        AND ";
        //
        $room_cond = "room_status.IN_DATE < '".$begin_next_month."' and room_status.IN_DATE >= '".$begin_month."' AND (IN_DATE < DEPARTURE_TIME OR  TO_CHAR(DEPARTURE_TIME, 'DD-MON-YYYY')=TO_CHAR(ARRIVAL_TIME, 'DD-MON-YYYY'))";
        $housekeepng_cond = "FROM_UNIXTIME(HOUSEKEEPING_INVOICE.TIME) < '".$begin_next_month."' AND FROM_UNIXTIME(HOUSEKEEPING_INVOICE.TIME) >= '".$begin_month."'";
        $extraservice_cond = "and EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE < '".$begin_next_month."' AND EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE >= '".$begin_month."'";
        $ei_lo_cond = "EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE < '".$begin_next_month."' AND EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE >= '".$begin_month."'";
        $spa_cond = "FROM_UNIXTIME(MASSAGE_PRODUCT_CONSUMED.TIME_OUT) < '".$begin_next_month."' AND FROM_UNIXTIME(MASSAGE_PRODUCT_CONSUMED.TIME_OUT) >= '".$begin_month."'";
        $sale_cond = "FROM_UNIXTIME(VE_RESERVATION.TIME) < '".$begin_next_month."' AND FROM_UNIXTIME(VE_RESERVATION.TIME) >= '".$begin_month."'";
        $ticket_cond = "FROM_UNIXTIME(TICKET_INVOICE.TIME) < '".$begin_next_month."' AND FROM_UNIXTIME(TICKET_INVOICE.TIME0) >= '".$begin_month."'";
        $bar_cond = "FROM_UNIXTIME(BAR_RESERVATION.DEPARTURE_TIME) < '".$begin_next_month."' AND FROM_UNIXTIME(BAR_RESERVATION.DEPARTURE_TIME) >= '".$begin_month."'";
        
         //Start  Luu Nguyen GIap add portal
        if(Url::get('portal_id'))
        {
           $portal_id =  Url::get('portal_id');
        }
        else
        {
            $portal_id=PORTAL_ID;
        }
        
        if($portal_id!="ALL")
        {
            $room_cond .= " AND RESERVATION.portal_id ='".$portal_id."'";
            $housekeepng_cond.=" AND HOUSEKEEPING_INVOICE.portal_id='".$portal_id."'";
            $extraservice_cond.=" AND  EXTRA_SERVICE_INVOICE.portal_id='".$portal_id."'";
            $ei_lo_cond.=" AND  EXTRA_SERVICE_INVOICE.portal_id='".$portal_id."'";
            $spa_cond.=" AND MASSAGE_RESERVATION_ROOM.portal_id='".$portal_id."'";
            $sale_cond .=" AND VE_RESERVATION.portal_id='".$portal_id."'";
            $ticket_cond.="AND TICKET_INVOICE.portal_id='".$portal_id."'";
            $bar_cond.="AND BAR_RESERVATION.portal_id='".$portal_id."'";
        } 
        //End   :Luu Nguyen GIap add portal
        
        if($array_depart['ROOM'])
        {
            $result['TOTAL_ROOM_IN_MONTH'] = 0;
            $result['TOTAL_BREAKFAST_IN_MONTH'] = 0;
            $room_prices = DB::fetch_all($query_total_revenue.$room_cond);
            $break_fast =0;
            foreach($room_prices as $key =>$value)
            {
                 if(BREAKFAST_NET_PRICE == 1)  
                 {
                    if($value['breakfast'] == 1)
                    {
                        $break_fast = $value['adult']*BREAKFAST_PRICE + $value['child']*BREAKFAST_CHILD_PRICE;
                    }
                    else
                    {
                        $break_fast = 0;
                    } 
                    
                    /*
                    if(BREAKFAST_NET_PRICE == 1)
                    {
                        $result['TOTAL_ROOM_IN_MONTH'] += ($value['total']*(1+$value['service_rate']/100)*(1+$value['tax_rate']/100) - $break_fast)/(1+$value['service_rate']/100)/(1+$value['tax_rate']/100);
                    }
                    else
                    {
                        $result['TOTAL_ROOM_IN_MONTH'] += $value['total'] - $break_fast;
                    }*/
                }
                $result['TOTAL_ROOM_IN_MONTH'] += $value['total'];   
                $result['TOTAL_BREAKFAST_IN_MONTH'] +=$break_fast;
                
            }
            $result['TOTAL_EI_LO_IN_MONTH'] = DB::fetch($query_total_ei_lo.$ei_lo_cond,'total');
            $result['TOTAL_ROOM_IN_MONTH'] = $result['TOTAL_ROOM_IN_MONTH'] + $result['TOTAL_EI_LO_IN_MONTH'];
        }
        if($array_depart['HOUSEKEEPING'])
            $result['TOTAL_HOUSEKEEPING_IN_MONTH'] = DB::fetch($query_total_housekeepng.$housekeepng_cond,'total');
        if($array_depart['EXTRASERVICE'])
            $result['TOTAL_EXTRASERVICE_IN_MONTH'] = DB::fetch($query_total_extraservice.$extraservice_cond,'total');
        if($array_depart['SPA'])
            $result['TOTAL_SPA_IN_MONTH'] = DB::fetch($query_total_spa.$spa_cond,'total');
        //System::debug($query_total_spa.$spa_cond);     
        if($array_depart['SALE'])
            $result['TOTAL_SALE_IN_MONTH'] = DB::fetch($query_total_sale.$sale_cond,'total');
        if($array_depart['TICKET'])
            $result['TOTAL_TICKET_IN_MONTH'] = DB::fetch($query_total_ticket.$ticket_cond,'total');
        if($array_depart['BAR'])
            $result['TOTAL_BAR_IN_MONTH'] = DB::fetch($query_total_bar.$bar_cond,'total');
                                  
        return $result;
    }
    
    function cal_revention_bystaff_days_in_month($array_depart)
    {
        $result = array();
        
        $date = $_REQUEST['date'];
        $day = date('d',Date_Time::to_time($date));
        $month = date('m',Date_Time::to_time($date));
        $year = date('Y',Date_Time::to_time($date));
        
        for($i = 1; checkdate($month,$i,$year); $i++){
            $day_in_month = Date_Time::to_orc_date($i.'/'.$month.'/'.$year);
            $temp = $this->cal_revention_bystaff_in_date_revenue($day_in_month,$array_depart);
            $result[$i] =   $temp['TOTAL_ROOM_IN_DATE_REVENUE'] +
                            $temp['TOTAL_HOUSEKEEPING_IN_DATE_REVENUE'] +
                            $temp['TOTAL_EXTRASERVICE_IN_DATE_REVENUE'] +
                            $temp['TOTAL_SPA_IN_DATE_REVENUE'] +
                            $temp['TOTAL_SALE_IN_DATE_REVENUE'] +
                            $temp['TOTAL_TICKET_IN_DATE_REVENUE'] +
                            $temp['TOTAL_BAR_IN_DATE_REVENUE'] ;
        }
        
        return $result;
    }
    //KimTan: cal_revention_bystaff_in_date
    
    //Tan: tinh doanh thu du kien trong ngay
     function cal_revention_bystaff_in_date_revenue($date,$array_depart)
    {
        $result = array();
        $result['TOTAL_ROOM_IN_DATE_REVENUE'] = 0;
        $result['TOTAL_HOUSEKEEPING_IN_DATE_REVENUE'] = 0;
        $result['TOTAL_EXTRASERVICE_IN_DATE_REVENUE'] = 0;
        $result['TOTAL_SPA_IN_DATE_REVENUE'] = 0;
        $result['TOTAL_SALE_IN_DATE_REVENUE'] = 0;
        $result['TOTAL_TICKET_IN_DATE_REVENUE'] = 0;
        $result['TOTAL_BAR_IN_DATE_REVENUE'] = 0;
        
        //Tan: lay doanh thu phong cua nhung phong co luoc do gia trong ngay xem bao cao(phong occ + dayuse)
        //ko tinh nhung phong ao, neu co giam gia theo so tien chi tinh vao ngay dau tien
        $query_total_revenue = "
         select
         room_status.id,
            case
            when room_status.in_date = reservation_room.arrival_time
            then 
                (case
                 when RESERVATION_ROOM.net_price = 0
                 then ((CHANGE_PRICE*(1-NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0)-NVL(RESERVATION_ROOM.REDUCE_AMOUNT,0))*(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))*(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)
                 else
                  ((((CHANGE_PRICE/(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))/(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0))*(1-NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0)-NVL(RESERVATION_ROOM.REDUCE_AMOUNT,0))*(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))*(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)
                end) 
            else
                (case
                 when RESERVATION_ROOM.net_price = 0
                 then (CHANGE_PRICE*(1-NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0)*(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))*(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)
                 else
                  ((((CHANGE_PRICE/(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))/(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0))*(1-NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0))*(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))*(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)
                end)
            end  as total,
        RESERVATION_ROOM.SERVICE_RATE,
        RESERVATION_ROOM.TAX_RATE,
        NVL(reservation_room.adult,0) adult,
        NVL(reservation_room.child,0) child,
        reservation_room.breakfast  
        from ROOM_STATUS
        inner join RESERVATION on RESERVATION.ID = ROOM_STATUS.RESERVATION_ID
        inner join RESERVATION_ROOM on RESERVATION_ROOM.ID = ROOM_STATUS.RESERVATION_ROOM_ID
        left join room_level on RESERVATION_ROOM.room_level_id = room_level.id
        WHERE 
        (ROOM_STATUS.STATUS = 'OCCUPIED' OR ROOM_STATUS.STATUS = 'BOOKED')
        AND (ROOM_STATUS.HOUSE_STATUS != 'HOUSEUSE' OR ROOM_STATUS.HOUSE_STATUS is null)
        AND (RESERVATION_ROOM.FOC is null OR RESERVATION_ROOM.FOC_ALL = 0)
        and ROOM_STATUS.change_price > 0
        and NVL(room_level.is_virtual,0) = 0
        and reservation_room.foc is null
        and reservation_room.foc_all = 0
        and ";
        
        $query_total_housekeepng = "
        select sum(
                case
                when (reservation_room.foc_all != 0 or reservation_room.status = 'CANCEL' or reservation_room.status != 'NOSHOW')
                then 0
                else
                housekeeping_invoice.total
                end
                ) as total
        from HOUSEKEEPING_INVOICE
        left join reservation_room on HOUSEKEEPING_INVOICE.reservation_room_id = reservation_room.id
         left join room_level on reservation_room.room_level_id = room_level.id
        where ";
        $query_total_extraservice = "
        select 
        sum(
            CASE
    			WHEN 
    				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
    			THEN
    				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
    			ELSE
    				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)
           END
        ) as total
        from EXTRA_SERVICE_INVOICE_DETAIL 
        inner join EXTRA_SERVICE_INVOICE on EXTRA_SERVICE_INVOICE.id = EXTRA_SERVICE_INVOICE_DETAIL.invoice_id
        inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
        left join reservation_room on EXTRA_SERVICE_INVOICE.reservation_room_id = reservation_room.id
        left join room_level on reservation_room.room_level_id = room_level.id
        where 
            (reservation_room.status='CHECKIN' OR reservation_room.status='CHECKOUT' OR reservation_room.status='BOOKED')
            and extra_service_invoice.payment_type = 'SERVICE'
            and ((extra_service.code != 'LATE_CHECKIN' and extra_service.code != 'LATE_CHECKOUT' and extra_service.code != 'EARLY_CHECKIN') or extra_service.code is null)
            ";
        //System::debug($query_total_extraservice);
        
        //tan:lay ei_li_lo theo ngay su dung da tinh thue phi,khong tinh phong ao 
        $query_total_ei_lo = "
        select 
        sum(
            CASE
    			WHEN 
    				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
    			THEN
    				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
    			ELSE
    				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)
           END
        ) as total
        from EXTRA_SERVICE_INVOICE_DETAIL 
        inner join EXTRA_SERVICE_INVOICE on EXTRA_SERVICE_INVOICE.id = EXTRA_SERVICE_INVOICE_DETAIL.invoice_id
            inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
            inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
            inner join reservation on reservation_room.reservation_id = reservation.id
            left join room_level on reservation_room.room_level_id = room_level.id
        where 
        NVL(room_level.is_virtual,0) = 0
        and (extra_service.code = 'LATE_CHECKIN' or extra_service.code = 'LATE_CHECKOUT' or extra_service.code = 'EARLY_CHECKIN')
        and
        ";
        $query_total_spa = "
        select sum(
        MASSAGE_RESERVATION_ROOM.total_amount
        ) as total
        from MASSAGE_PRODUCT_CONSUMED
        inner join MASSAGE_RESERVATION_ROOM on MASSAGE_PRODUCT_CONSUMED.RESERVATION_ROOM_ID = MASSAGE_RESERVATION_ROOM.ID
        left join room on MASSAGE_PRODUCT_CONSUMED.room_id = room.id
        left join room_level on room.room_level_id = room_level.id
        where MASSAGE_PRODUCT_CONSUMED.STATUS != 'CANCEL' and ";
        $query_total_sale = "
        select sum(TOTAL) as total
        from VE_RESERVATION
        where ";
        $query_total_ticket = "
        select sum(TOTAL) as total
        from TICKET_INVOICE
        where ";
        $query_total_bar = "
        select sum(BAR_RESERVATION.TOTAL) as total
        from BAR_RESERVATION
        left join reservation_room on BAR_RESERVATION.reservation_room_id = reservation_room.id
        left join room_level on reservation_room.room_level_id = room_level.id
        where (BAR_RESERVATION.STATUS='CHECKOUT' OR BAR_RESERVATION.STATUS='BOOKED' OR BAR_RESERVATION.STATUS='CHECKIN')
        and NVL(room_level.is_virtual,0) = 0
        AND ";
        
        //
        $room_cond = "TO_CHAR(room_status.IN_DATE, 'DD-MON-YYYY') = '".$date."' AND (IN_DATE < DEPARTURE_TIME OR  TO_CHAR(DEPARTURE_TIME, 'DD-MON-YYYY')=TO_CHAR(ARRIVAL_TIME, 'DD-MON-YYYY'))";
        $housekeepng_cond = "TO_CHAR(FROM_UNIXTIME(HOUSEKEEPING_INVOICE.TIME), 'DD-MON-YYYY') = '".$date."' ";
        $extraservice_cond = " AND TO_CHAR(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE, 'DD-MON-YYYY') = '".$date."' and (room_level.is_virtual=0 or room_level.is_virtual is null)";
        $ei_lo_cond = "TO_CHAR(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE, 'DD-MON-YYYY') = '".$date."'";
        $spa_cond = "TO_CHAR(FROM_UNIXTIME(MASSAGE_PRODUCT_CONSUMED.TIME_OUT), 'DD-MON-YYYY') = '".$date."' and NVL(room_level.is_virtual,0) = 0";
        $sale_cond = "TO_CHAR(FROM_UNIXTIME(VE_RESERVATION.TIME), 'DD-MON-YYYY') = '".$date."'";
        $ticket_cond = "TO_CHAR(FROM_UNIXTIME(TICKET_INVOICE.TIME), 'DD-MON-YYYY') = '".$date."'";
        $bar_cond = "TO_CHAR(FROM_UNIXTIME(BAR_RESERVATION.DEPARTURE_TIME), 'DD-MON-YYYY') = '".$date."'";
        
        //Start  Luu Nguyen GIap add portal
        if(Url::get('portal_id'))
        {
           $portal_id =  Url::get('portal_id');
        }
        else
        {
            $portal_id=PORTAL_ID;
        }
        
        if($portal_id!="ALL")
        {
            $room_cond .= " AND RESERVATION.portal_id ='".$portal_id."'";
            $housekeepng_cond.=" AND HOUSEKEEPING_INVOICE.portal_id='".$portal_id."'";
            $extraservice_cond.=" AND  EXTRA_SERVICE_INVOICE.portal_id='".$portal_id."'";
            $spa_cond.=" AND MASSAGE_RESERVATION_ROOM.portal_id='".$portal_id."'";
            $sale_cond .=" AND VE_RESERVATION.portal_id='".$portal_id."'";
            $ticket_cond.="AND TICKET_INVOICE.portal_id='".$portal_id."'";
            $bar_cond.="AND BAR_RESERVATION.portal_id='".$portal_id."'";
            $ei_lo_cond.=" AND EXTRA_SERVICE_INVOICE.portal_id='".$portal_id."'";
        } 
        //End   :Luu Nguyen GIap add portal
        //System::debug($query_total_revenue);
        if($array_depart['ROOM'])
        {
            $room_price = 0;
            $result['TOTAL_BREAKFAST_IN_DATE_REVENUE'] = 0;
            $room_prices = DB::fetch_all($query_total_revenue.$room_cond);
            $break_fast =0;
            foreach($room_prices as $key =>$value)
            {
                if(BREAKFAST_NET_PRICE == 1)
                {
                    if($value['breakfast'] == 1)
                    {
                        $break_fast = $value['adult']*BREAKFAST_PRICE + $value['child']*BREAKFAST_CHILD_PRICE;
                    }
                    else
                    {
                        $break_fast = 0;
                    } 
                    /*
                    if(BREAKFAST_NET_PRICE == 1)
                    {
                        $room_price += ($value['total']*(1+$value['service_rate']/100)*(1+$value['tax_rate']/100) - $break_fast)/(1+$value['service_rate']/100)/(1+$value['tax_rate']/100);
                    }
                    else
                    {
                        $room_price += $value['total'] - $break_fast;
                    }
                    */
                }
                $room_price += $value['total'];  
                $result['TOTAL_BREAKFAST_IN_DATE_REVENUE'] +=$break_fast;
                
            }
            $result['TOTAL_ROOM_IN_DATE_REVENUE'] = $room_price + DB::fetch($query_total_ei_lo.$ei_lo_cond,'total');
            //System::debug(DB::fetch($query_total_ei_lo.$ei_lo_cond,'total'));
            //$result['TOTAL_EI_LO_IN_DATE_REVENUE'] = DB::fetch($query_total_ei_lo.$ei_lo_cond,'total');
            //System::debug($result['TOTAL_EI_LO_IN_DATE_REVENUE']);
            }
        if($array_depart['HOUSEKEEPING'])
            $result['TOTAL_HOUSEKEEPING_IN_DATE_REVENUE'] = DB::fetch($query_total_housekeepng.$housekeepng_cond,'total');
        if($array_depart['EXTRASERVICE'])
            $result['TOTAL_EXTRASERVICE_IN_DATE_REVENUE'] = DB::fetch($query_total_extraservice.$extraservice_cond,'total');
            
        if($array_depart['SPA'])
            $result['TOTAL_SPA_IN_DATE_REVENUE'] = DB::fetch($query_total_spa.$spa_cond,'total');
        if($array_depart['SALE'])
            $result['TOTAL_SALE_IN_DATE_REVENUE'] = DB::fetch($query_total_sale.$sale_cond,'total');
        if($array_depart['TICKET'])
            $result['TOTAL_TICKET_IN_DATE_REVENUE'] = DB::fetch($query_total_ticket.$ticket_cond,'total');
        if($array_depart['BAR'])
            $result['TOTAL_BAR_IN_DATE_REVENUE'] = DB::fetch($query_total_bar.$bar_cond,'total');    
        return $result;
    }
}
?>
