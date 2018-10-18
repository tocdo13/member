<?php 
class HotelManagerReportForm extends Form
{
	function HotelManagerReportForm()
	{
		Form::Form('HotelManagerReportForm');
		$this->link_css('packages/hotel/packages/report/skins/default/css/style.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/chart/highcharts.src.js');
		$this->link_js('packages/core/includes/js/jquery/chart/exporting.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw()
	{
        
		require_once 'packages/core/includes/utils/lib/report.php';
        if(!Url::get('date'))
            $_REQUEST['date'] = date("d/m/Y");
        $this->map = array();
        
         //Start : Luu Nguyen GIap add portal
        if(!isset($_REQUEST['do_search']))
        {
            $_REQUEST['portal_id'] = PORTAL_ID;
        }
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()); 
        //End   :Luu Nguyen GIap add portal
        
        $date = $_REQUEST['date'];
        $day = date('d',Date_Time::to_time($date));
        $month = date('m',Date_Time::to_time($date));
        $year = date('Y',Date_Time::to_time($date));
        
        $date = Date_Time::to_time($date);
        $time6h = $date+(6*3600);
        $max_day_prev = date("t", Date_Time::to_time('1/'.$month.'/'.($year-1)));
        $day_prev = $day<$max_day_prev?$day:$max_day_prev;
        
        $date_prev = Date_Time::to_time($day_prev.'/'.$month.'/'.($year-1));
        
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
            $trh_ytd=" AND ROOM.portal_id='".$portal_id."'";
            $oor_d = " AND reservation.portal_id='".$portal_id."'";
            $oor_d_1 = " AND room.portal_id='".$portal_id."'";
            //$oor_mtd = " AND ";
            $hkrm_d =" AND housekeeping_invoice.portal_id='".$portal_id."'";
            $bar_portal=" AND bar_reservation.portal_id='".$portal_id."'" ;
            $shopr_d = " AND ve_reservation.portal_id='".$portal_id."'" ;
            $cond_spa = "AND massage_reservation_room.portal_id='".$portal_id."'" ;
            $cond_party = "AND party_reservation.portal_id='".$portal_id."'" ;
            $cond_ticket = "AND ticket_invoice.portal_id='".$portal_id."'" ;
            $cond_kara = "AND karaoke_reservation.portal_id='".$portal_id."'" ;
        }
        else
        {
            $trh_ytd="";
            $oor_d="";
            $oor_d_1="";
            $hkrm_d="";
            $bar_portal="";
            $shopr_d="";
            $cond_spa="";
            $cond_party="";
            $cond_ticket="";
            $cond_kara="";
        } 
	    //End Luu Nguyen Giap add portal
        
		//$date = time();
		$indexes = array();
		//tong so phong
        /** manh tinh lai tong so phong theo lich su **/
        //$indexes['trh_d'] = $indexes['trh_lysd'] = DB::fetch('select count(*) as acount from room inner join room_level on room_level.id = room.room_level_id where room_level.is_virtual is null or room_level.is_virtual <> 1'.$trh_ytd,'acount');
        $indexes['trh_d'] = $indexes['trh_lysd'] = 0;
        $portal = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
        if($portal !='ALL')
        {
            if($his_in_date = DB::fetch('select max(in_date) as in_date from room_history where in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\' and portal_id=\''.$portal.'\'','in_date'))
            {
                $indexes['trh_d'] = $indexes['trh_lysd'] = DB::fetch('select 
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
            elseif($his_in_date = DB::fetch('select min(in_date) as in_date from room_history where in_date>\''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\' and portal_id=\''.$portal.'\'','in_date'))
            {
                $indexes['trh_d'] = $indexes['trh_lysd'] = DB::fetch('select 
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
                if($his_in_date = DB::fetch('select max(in_date) as in_date from room_history where in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\' and portal_id=\''.$value['id'].'\'','in_date'))
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
                elseif($his_in_date = DB::fetch('select min(in_date) as in_date from room_history where in_date>\''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\' and portal_id=\''.$value['id'].'\'','in_date'))
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
                $indexes['trh_d'] = $indexes['trh_lysd'] = $total_room_all;
            }
        }
        /** End Manh **/
        //echo ceil(($date-strtotime(date('m/1/Y',$date))+1)/24/3600);
        
		$indexes['trh_mtd'] = $indexes['trh_lymtd'] = $indexes['trh_d']*ceil(($date-strtotime(date('m/1/Y',$date))+1)/24/3600);
        
        //echo ceil(($date-strtotime(date('1/1/Y',$date))+1)/24/3600);
        
		$indexes['trh_ytd'] = $indexes['trh_lyytd'] = $indexes['trh_d']*ceil(($date-strtotime(date('1/1/Y',$date))+1)/24/3600);
        
		
		//phong hong ko don duoc khach
		$indexes['oor_d'] = DB::fetch('select count(*) as acount from room_status join room on room.id = room_id where house_status=\'REPAIR\' and in_date=\''.Date_Time::convert_time_to_ora_date($date).'\''.$oor_d_1,'acount');//old left join --Luu nguyen giap comment sql 
        $indexes['oor_mtd'] = DB::fetch('select count(*) as acount from room_status  join room on room.id = room_id where house_status=\'REPAIR\' and in_date>=\''.Date_Time::to_orc_date(date('1/m/Y',$date)).'\' and in_date<=\''.Date_Time::convert_time_to_ora_date($date).'\''.$oor_d_1,'acount');  //old left join --Luu nguyen giap comment sql
		$indexes['oor_ytd'] = DB::fetch('select count(*) as acount from room_status  join room on room.id = room_id where house_status=\'REPAIR\' and in_date>=\''.Date_Time::to_orc_date(date('1/1/Y',$date)).'\' and in_date<=\''.Date_Time::convert_time_to_ora_date($date).'\''.$oor_d_1,'acount');  //old left join --Luu nguyen giap comment sql`

		//tong so phong su dung duoc 
		$indexes['trooo_d'] = $indexes['trh_d']-$indexes['oor_d'];
		$indexes['trooo_mtd'] = $indexes['trh_mtd']-$indexes['oor_mtd'];
		$indexes['trooo_ytd'] = $indexes['trh_ytd']-$indexes['oor_ytd'];
		
		//Phong co extrabed
		$indexes['osr_d'] = DB::fetch('select count(*) as acount from room_status inner join reservation on reservation.id = reservation_id where   extra_bed=\'1\' and in_date=\''.Date_Time::convert_time_to_ora_date($date).'\''.$oor_d,'acount');
		$indexes['osr_mtd'] = DB::fetch('select count(*) as acount from room_status inner join reservation on reservation.id = reservation_id where   extra_bed=\'1\' and in_date>=\''.Date_Time::to_orc_date(date('1/m/Y',$date)).'\' and in_date<=\''.Date_Time::convert_time_to_ora_date($date).'\''.$oor_d,'acount');
		$indexes['osr_ytd'] = DB::fetch('select count(*) as acount from room_status inner join reservation on reservation.id = reservation_id where   extra_bed=\'1\' and in_date>=\''.Date_Time::to_orc_date(date('1/1/Y',$date)).'\' and in_date<=\''.Date_Time::convert_time_to_ora_date($date).'\''.$oor_d,'acount');
		//phong dang co khach
        $sql = 'select 
                    count(*) as acount
                from 
                    room_status 
                    inner join reservation_room on reservation_room.id = room_status.reservation_room_id
                    inner join reservation on reservation.id = reservation_room.reservation_id 
                    left join room_level on reservation_room.room_level_id = room_level.id 
                where  
                    (room_status.status=\'OCCUPIED\' or room_status.status=\'BOOKED\')
                    and nvl(room_level.is_virtual,0) = 0
                    --KimTan : xu ly doi phong moi
                    and(
                        (reservation_room.time_in < (date_to_unix(in_date)+(6*3600)) and date_to_unix(reservation_room.departure_time) > date_to_unix(in_date) )
                        or
                        (reservation_room.change_room_from_rr is not null and reservation_room.arrival_time = in_date and reservation_room.old_arrival_time < (date_to_unix(in_date)+(6*3600)) and date_to_unix(reservation_room.departure_time) > date_to_unix(in_date) ) --neu la phong duoc doi den trong ngay hoac xem dung ngay doi (co ngay den bang in_date va change_room_from_rr is not null) ma co old_arr_time < in_date < dpt_time
                        )
                    ---
                    and in_date=\''.Date_Time::convert_time_to_ora_date($date).'\''.$oor_d;
        //System::debug($sql);
        $indexes['ro_d'] = DB::fetch($sql,'acount');
                    
		$sql_mtd = 'select count(*) as acount from room_status 
                    inner join reservation_room on reservation_room.id = room_status.reservation_room_id
                    inner join reservation on reservation.id = reservation_room.reservation_id
                    left join room_level on reservation_room.room_level_id = room_level.id  
                    where 
                    (room_status.status=\'OCCUPIED\' or room_status.status=\'BOOKED\')
                    and nvl(room_level.is_virtual,0) = 0
                    and(
                        (reservation_room.time_in < (date_to_unix(in_date)+(6*3600)) and date_to_unix(reservation_room.departure_time) > date_to_unix(in_date) )
                        or
                        (reservation_room.change_room_from_rr is not null and reservation_room.arrival_time = in_date and reservation_room.old_arrival_time < (date_to_unix(in_date)+(6*3600)) and date_to_unix(reservation_room.departure_time) > date_to_unix(in_date) ) --neu la phong duoc doi den trong ngay hoac xem dung ngay doi (co ngay den bang in_date va change_room_from_rr is not null) ma co old_arr_time < in_date < dpt_time
                        )
                    and date_to_unix(in_date)>=\''.Date_Time::to_time(date('1/m/Y',$date)).'\' 
                    and date_to_unix(in_date)<=\''.$date.'\' 
                    '.$oor_d;
        //System::debug($sql_mtd);             
        $indexes['ro_mtd'] = DB::fetch($sql_mtd,'acount');            		
		$sql_ytd='select count(*) as acount from room_status 
                    inner join reservation_room on reservation_room.id = room_status.reservation_room_id
                    inner join reservation on reservation.id = reservation_room.reservation_id 
                    left join room_level on reservation_room.room_level_id = room_level.id 
                    where 
                    (room_status.status=\'OCCUPIED\' or room_status.status=\'BOOKED\')
                    and nvl(room_level.is_virtual,0) = 0 
                    and (
                        (date_to_unix(reservation_room.arrival_time) < date_to_unix(in_date) and date_to_unix(reservation_room.departure_time) > date_to_unix(in_date) )
                        or
                        (reservation_room.change_room_from_rr is not null and reservation_room.arrival_time = in_date and reservation_room.old_arrival_time < date_to_unix(in_date) and date_to_unix(reservation_room.departure_time) > date_to_unix(in_date) )
                    )
                    and date_to_unix(in_date)>=\''.Date_Time::to_time(date('1/1/Y',$date)).'\' 
                    and date_to_unix(in_date)<=\''.$date.'\' 
                    '.$oor_d;
        $indexes['ro_ytd'] = DB::fetch($sql_ytd,'acount');            
		//Phong  mien phi cho khach
		$indexes['cr_d'] = DB::fetch('select count(*) as acount from room_status 
                    inner join reservation_room on reservation_room.id = room_status.reservation_room_id
                    inner join reservation on reservation.id = reservation_room.reservation_id
                    left join room_level on reservation_room.room_level_id = room_level.id 
                    where  
                    room_status.in_date=\''.Date_Time::convert_time_to_ora_date($date).'\'  
                    and nvl(room_level.is_virtual,0) = 0 
                    and reservation_room.status != \'CANCEL\' and reservation_room.status != \'NOSHOW\' 
                    and room_status.in_date != reservation_room.departure_time
                    and (foc is not null or foc_all=1) '.$oor_d,'acount');
		$indexes['cr_mtd'] = DB::fetch('select count(*) as acount from room_status 
                    inner join reservation_room on reservation_room.id = room_status.reservation_room_id
                    inner join reservation on reservation.id = reservation_room.reservation_id left join room_level on reservation_room.room_level_id = room_level.id where  date_to_unix(room_status.in_date)>=\''.Date_Time::to_time(date('1/m/Y',$date)).'\' and date_to_unix(room_status.in_date)<=\''.$date.'\'  and nvl(room_level.is_virtual,0) = 0 and reservation_room.status != \'CANCEL\' and reservation_room.status != \'NOSHOW\' and room_status.in_date != reservation_room.departure_time and (foc is not null or foc_all=1) '.$oor_d,'acount');
		$indexes['cr_ytd'] = DB::fetch('select count(*) as acount from room_status
                    inner join reservation_room on room_status.reservation_room_id=reservation_room.id 
                    inner join reservation on reservation.id = reservation_room.reservation_id left join room_level on reservation_room.room_level_id = room_level.id where  date_to_unix(room_status.in_date)>=\''.Date_Time::to_time(date('1/1/Y',$date)).'\' and date_to_unix(room_status.in_date)<=\''.$date.'\'  and nvl(room_level.is_virtual,0) = 0 and reservation_room.status != \'CANCEL\' and reservation_room.status != \'NOSHOW\' and room_status.in_date != reservation_room.departure_time and (foc is not null or foc_all=1)'.$oor_d,'acount');
		
		//Phong noi bo
		$indexes['hu_d'] = DB::fetch('select count(*) as acount from room_status 
                    inner join reservation_room on reservation_room.id = room_status.reservation_room_id
                    inner join reservation on reservation.id = reservation_room.reservation_id where  (room_status.status=\'OCCUPIED\' or room_status.status=\'BOOKED\') and  room_status.house_status=\'HOUSEUSE\' and in_date=\''.Date_Time::convert_time_to_ora_date($date).'\' '.$oor_d,'acount');
		$indexes['hu_mtd'] = DB::fetch('select count(*) as acount from room_status 
                    inner join reservation_room on reservation_room.id = room_status.reservation_room_id
                    inner join reservation on reservation.id = reservation_room.reservation_id where  (room_status.status=\'OCCUPIED\' or room_status.status=\'BOOKED\') and  room_status.house_status=\'HOUSEUSE\' and in_date>=\''.Date_Time::to_orc_date(date('1/m/Y',$date)).'\' and in_date<=\''.Date_Time::convert_time_to_ora_date($date).'\''.$oor_d,'acount');
		$indexes['hu_ytd'] = DB::fetch('select count(*) as acount from room_status 
                    inner join reservation_room on reservation_room.id = room_status.reservation_room_id
                    inner join reservation on reservation.id = reservation_room.reservation_id where  (room_status.status=\'OCCUPIED\' or room_status.status=\'BOOKED\') and  room_status.house_status=\'HOUSEUSE\' and in_date>=\''.Date_Time::to_orc_date(date('1/1/Y',$date)).'\' and in_date<=\''.Date_Time::convert_time_to_ora_date($date).'\''.$oor_d,'acount');
		
		//phong dang co khach thu duoc tien 
		$indexes['orhc_mtd'] = $indexes['ro_mtd'];
		$indexes['orhc_ytd'] = $indexes['ro_ytd'];
		//phong Ä‘ang cÃ³ khÃ¡ch - phÃ²ng miá»…n phÃ­
		$indexes['roc_d'] = $indexes['ro_d']-$indexes['cr_d'];
		$indexes['roc_mtd'] = $indexes['ro_mtd']-$indexes['cr_mtd'];
		$indexes['roc_ytd'] = $indexes['ro_ytd']-$indexes['cr_ytd'];
		
		//phan tram cua phong dang co khach
		$indexes['pro_d'] = $indexes['trh_d']?number_format($indexes['ro_d']/$indexes['trooo_d']*100,2).'%':'0';
		$indexes['pro_mtd'] = $indexes['trh_mtd']?number_format($indexes['ro_mtd']/$indexes['trh_mtd']*100,2).'%':'0';
		$indexes['pro_ytd'] = $indexes['trh_ytd']?number_format($indexes['ro_ytd']/$indexes['trh_ytd']*100,2).'%':'0';
		
		//phan tram phong dang co khach -  phong ks su dug - phong mien phi
        if(!isset($indexes['ra_d']))
        $indexes['ra_d'] = 0;
        if(!isset($indexes['eci_d']))
        $indexes['eci_d'] = 0;
        if(!isset($indexes['lci_d']))
        $indexes['lci_d'] = 0;
        if(!isset($indexes['lco_d']))
        $indexes['lco_d'] = 0;
		$indexes['pohc_d'] = $indexes['trh_d']?number_format(($indexes['ro_d']+$indexes['ra_d']+$indexes['eci_d']+$indexes['lci_d']+$indexes['lco_d'])/$indexes['trh_d']*100,2).'%':'0';
		        //Start Luu Nguyen Giap add portal
        if($portal_id!="ALL")
        {
            $exs_d = " AND extra_service_invoice.portal_id='".$portal_id."'";
        }
        else
        {
             $exs_d="";
        }
		//doanh thu phong 
		$indexes['rr_d'] = DB::fetch('
                 select sum(case
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
                                end) as rrr
                 from room_status
                 inner join reservation_room on reservation_room.id = room_status.reservation_room_id 
                 inner join reservation on reservation.id = reservation_room.reservation_id 
                 left join room_level on reservation_room.room_level_id = room_level.id
                 where  in_date =\''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\' and room_status.status!=\'CANCEL\' and room_status.status!=\'NOSHOW\' 
                 and (room_status.house_status!=\'HOUSEUSE\' or room_status.house_status is null)
                 and nvl(room_level.is_virtual,0) = 0
                 and foc is null and foc_all=0 
                 and (in_date<departure_time or arrival_time=departure_time)'.$oor_d,'rrr');
        /*
        $indexes['rr_d']+=DB::fetch('select 
                sum(
                    CASE
            			WHEN 
            				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
            			THEN
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
            			ELSE
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)
                   END
                ) as tt
                from EXTRA_SERVICE_INVOICE_DETAIL 
                inner join EXTRA_SERVICE_INVOICE on EXTRA_SERVICE_INVOICE.id = EXTRA_SERVICE_INVOICE_DETAIL.invoice_id
                inner join EXTRA_SERVICE on EXTRA_SERVICE.id = EXTRA_SERVICE_INVOICE_DETAIL.service_id
                left join reservation_room on EXTRA_SERVICE_INVOICE.reservation_room_id = reservation_room.id
                left join room_level on reservation_room.room_level_id = room_level.id
                where 
                    (reservation_room.status=\'CHECKIN\' OR reservation_room.status=\'CHECKOUT\')
                    and EXTRA_SERVICE_INVOICE.payment_type = \'ROOM\'
                    and  ((extra_service.code != \'LATE_CHECKIN\' and extra_service.code != \'LATE_CHECKOUT\' and extra_service.code != \'EARLY_CHECKIN\'))
                    and (room_level.is_virtual=0 or room_level.is_virtual is null)
                    and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)=\''.$date.'\''.$exs_d,'tt');  
        */              
		$indexes['rr_mtd'] = DB::fetch('select sum(case
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
                                end) as rrr
                 from room_status 
                 inner join reservation_room on reservation_room.id=room_status.reservation_room_id 
                 inner join reservation on reservation.id = reservation_room.reservation_id
                 left join room_level on reservation_room.room_level_id = room_level.id
                 where   
                 date_to_unix(in_date)>=\''.Date_Time::to_time(date('01/m/Y',$date)).'\'
                 and date_to_unix(in_date)<=\''.$date.'\'
                 and room_status.status!=\'CANCEL\' and room_status.status!=\'NOSHOW\' and (room_status.house_status!=\'HOUSEUSE\' or room_status.house_status is null)
                 and foc is null and foc_all=0
                 and nvl(room_level.is_virtual,0) = 0
                 and (date_to_unix(in_date) < date_to_unix(DEPARTURE_TIME) or arrival_time=departure_time)'.$oor_d,'rrr');
                 
        /*
        $indexes['rr_mtd']+=DB::fetch('select 
                sum(
                    CASE
            			WHEN 
            				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
            			THEN
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
            			ELSE
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)
                   END
                ) as tt
                from EXTRA_SERVICE_INVOICE_DETAIL 
                inner join EXTRA_SERVICE_INVOICE on EXTRA_SERVICE_INVOICE.id = EXTRA_SERVICE_INVOICE_DETAIL.invoice_id
                inner join EXTRA_SERVICE on EXTRA_SERVICE.id = EXTRA_SERVICE_INVOICE_DETAIL.service_id
                left join reservation_room on EXTRA_SERVICE_INVOICE.reservation_room_id = reservation_room.id
                left join room_level on reservation_room.room_level_id = room_level.id
                where 
                    (reservation_room.status=\'CHECKIN\' OR reservation_room.status=\'CHECKOUT\')
                    and EXTRA_SERVICE_INVOICE.payment_type = \'ROOM\'
                    and  ((extra_service.code != \'LATE_CHECKIN\' and extra_service.code != \'LATE_CHECKOUT\' and extra_service.code != \'EARLY_CHECKIN\'))
                    and (room_level.is_virtual=0 or room_level.is_virtual is null)
                    and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)>=\''.Date_Time::to_time(date('01/m/Y',$date)).'\' and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)<=\''.$date.'\' '.$exs_d,'tt');
        */              
		$indexes['rr_ytd'] = DB::fetch('select sum(case
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
                                end) as rrr
         from room_status inner join reservation_room on reservation_room.id=room_status.reservation_room_id 
         inner join reservation on reservation.id = reservation_room.reservation_id 
         left join room_level on reservation_room.room_level_id = room_level.id
         where   
         date_to_unix(in_date)>=\''.Date_Time::to_time(date('01/01/Y',$date)).'\'
         and date_to_unix(in_date)<=\''.$date.'\'
         and room_status.status!=\'CANCEL\' and room_status.status!=\'NOSHOW\' and (room_status.house_status!=\'HOUSEUSE\' or room_status.house_status is null)
         and nvl(room_level.is_virtual,0) = 0
         and foc is null and foc_all=0
         and (date_to_unix(in_date) < date_to_unix(DEPARTURE_TIME) or arrival_time=departure_time)'.$oor_d,'rrr');
   /*
   $indexes['rr_ytd']+=DB::fetch('select 
                sum(
                    CASE
            			WHEN 
            				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
            			THEN
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
            			ELSE
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)
                   END
                ) as tt
                from EXTRA_SERVICE_INVOICE_DETAIL 
                inner join EXTRA_SERVICE_INVOICE on EXTRA_SERVICE_INVOICE.id = EXTRA_SERVICE_INVOICE_DETAIL.invoice_id
                inner join EXTRA_SERVICE on EXTRA_SERVICE.id = EXTRA_SERVICE_INVOICE_DETAIL.service_id
                left join reservation_room on EXTRA_SERVICE_INVOICE.reservation_room_id = reservation_room.id
                left join room_level on reservation_room.room_level_id = room_level.id
                where 
                    (reservation_room.status=\'CHECKIN\' OR reservation_room.status=\'CHECKOUT\')
                    and EXTRA_SERVICE_INVOICE.payment_type = \'ROOM\'
                    and  ((extra_service.code != \'LATE_CHECKIN\' and extra_service.code != \'LATE_CHECKOUT\' and extra_service.code != \'EARLY_CHECKIN\'))
                    and (room_level.is_virtual=0 or room_level.is_virtual is null)
                    and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)>=\''.Date_Time::to_time(date('01/01/Y',$date)).'\' and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)<=\''.$date.'\' '.$exs_d,'tt');
		//doanh thu dich vu mo rong
        */
        $sql = 'select 
                sum(
                    CASE
            			WHEN 
            				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
            			THEN
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
            			ELSE
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)
                   END
                ) as tt
                from EXTRA_SERVICE_INVOICE_DETAIL 
                inner join EXTRA_SERVICE_INVOICE on EXTRA_SERVICE_INVOICE.id = EXTRA_SERVICE_INVOICE_DETAIL.invoice_id
                inner join EXTRA_SERVICE on EXTRA_SERVICE.id = EXTRA_SERVICE_INVOICE_DETAIL.service_id
                left join reservation_room on EXTRA_SERVICE_INVOICE.reservation_room_id = reservation_room.id
                left join room_level on reservation_room.room_level_id = room_level.id
                where 
                    reservation_room.status!=\'CANCEL\' and reservation_room.status!=\'NOSHOW\' 
                    and  ((substr(extra_service.code,1,4) != \'TOUR\' and  EXTRA_SERVICE.code != \'EXTRA_BED\' and extra_service.code != \'LATE_CHECKIN\' and extra_service.code != \'LATE_CHECKOUT\' and extra_service.code != \'EARLY_CHECKIN\') or EXTRA_SERVICE.code is null)
                    and (room_level.is_virtual=0 or room_level.is_virtual is null) and reservation_room.foc_all = 0
                    and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)=\''.$date.'\''.$exs_d; 
		$indexes['exs_d'] = number_format(DB::fetch($sql,'tt'),0);
        $sql = 'select 
                sum(
                    CASE
            			WHEN 
            				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
            			THEN
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
            			ELSE
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)
                   END
                ) as tt
                from EXTRA_SERVICE_INVOICE_DETAIL 
                inner join EXTRA_SERVICE_INVOICE on EXTRA_SERVICE_INVOICE.id = EXTRA_SERVICE_INVOICE_DETAIL.invoice_id
                inner join EXTRA_SERVICE on EXTRA_SERVICE.id = EXTRA_SERVICE_INVOICE_DETAIL.service_id
                left join reservation_room on EXTRA_SERVICE_INVOICE.reservation_room_id = reservation_room.id
                left join room_level on reservation_room.room_level_id = room_level.id
                where 
                    reservation_room.status!=\'CANCEL\' and reservation_room.status!=\'NOSHOW\' 
                    and  ((substr(extra_service.code,1,4) != \'TOUR\' and  EXTRA_SERVICE.code != \'EXTRA_BED\' and extra_service.code != \'LATE_CHECKIN\' and extra_service.code != \'LATE_CHECKOUT\' and extra_service.code != \'EARLY_CHECKIN\') or EXTRA_SERVICE.code is null)
                    and (room_level.is_virtual=0 or room_level.is_virtual is null)
                    and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)>=\''.Date_Time::to_time(date('01/m/Y',$date)).'\' and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)<=\''.$date.'\' '.$exs_d;
		if(User::id()=='developer06')
        {
            //System::debug($sql);
        }
        $indexes['exs_mtd'] = number_format(DB::fetch($sql,'tt'),0);
        
        $sql ='select 
                sum(
                    CASE
            			WHEN 
            				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
            			THEN
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
            			ELSE
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)
                   END
                ) as tt
                from EXTRA_SERVICE_INVOICE_DETAIL 
                inner join EXTRA_SERVICE_INVOICE on EXTRA_SERVICE_INVOICE.id = EXTRA_SERVICE_INVOICE_DETAIL.invoice_id
                inner join EXTRA_SERVICE on EXTRA_SERVICE.id = EXTRA_SERVICE_INVOICE_DETAIL.service_id
                left join reservation_room on EXTRA_SERVICE_INVOICE.reservation_room_id = reservation_room.id
                left join room_level on reservation_room.room_level_id = room_level.id
                where 
                    reservation_room.status!=\'CANCEL\' and reservation_room.status!=\'NOSHOW\' 
                    and  ((substr(extra_service.code,1,4) != \'TOUR\' and  EXTRA_SERVICE.code != \'EXTRA_BED\' and extra_service.code != \'LATE_CHECKIN\' and extra_service.code != \'LATE_CHECKOUT\' and extra_service.code != \'EARLY_CHECKIN\') or EXTRA_SERVICE.code is null)
                    and (room_level.is_virtual=0 or room_level.is_virtual is null)
                    and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)>=\''.Date_Time::to_time(date('01/01/Y',$date)).'\' and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)<=\''.$date.'\' '.$exs_d;
		$indexes['exs_ytd'] = number_format(DB::fetch($sql,'tt'),0);
		
        if($indexes['exs_d']=='')
            $indexes['exs_d']=0;
        if($indexes['exs_mtd']=='')
            $indexes['exs_mtd']=0;
        if($indexes['exs_ytd']=='')
            $indexes['exs_ytd']=0;
        //doanh thu dich vu mo rong (Extra_bed)
        $sql = 'select 
                sum(
                    CASE
            			WHEN 
            				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
            			THEN
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
            			ELSE
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)
                   END
                ) as tt
                from EXTRA_SERVICE_INVOICE_DETAIL 
                inner join EXTRA_SERVICE_INVOICE on EXTRA_SERVICE_INVOICE.id = EXTRA_SERVICE_INVOICE_DETAIL.invoice_id
                inner join EXTRA_SERVICE on EXTRA_SERVICE.id = EXTRA_SERVICE_INVOICE_DETAIL.service_id
                left join reservation_room on EXTRA_SERVICE_INVOICE.reservation_room_id = reservation_room.id
                left join room_level on reservation_room.room_level_id = room_level.id
                where 
                    reservation_room.status!=\'CANCEL\' and reservation_room.status!=\'NOSHOW\'  
                    and  EXTRA_SERVICE.code = \'EXTRA_BED\'
                    and (room_level.is_virtual=0 or room_level.is_virtual is null)
        and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)=\''.$date.'\''.$exs_d;
           if(User::id()=='developer10'){
            //System::debug($sql);
          }
		$indexes['exs_eb_d'] = number_format(DB::fetch($sql,'tt'),0);
        
        $sql ='select 
                sum(
                    CASE
            			WHEN 
            				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
            			THEN
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
            			ELSE
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)
                   END
                ) as tt
                from EXTRA_SERVICE_INVOICE_DETAIL 
                inner join EXTRA_SERVICE_INVOICE on EXTRA_SERVICE_INVOICE.id = EXTRA_SERVICE_INVOICE_DETAIL.invoice_id
                inner join EXTRA_SERVICE on EXTRA_SERVICE.id = EXTRA_SERVICE_INVOICE_DETAIL.service_id
                inner join reservation_room on EXTRA_SERVICE_INVOICE.reservation_room_id = reservation_room.id
                left join room_level on reservation_room.room_level_id = room_level.id
                where 
                    reservation_room.status!=\'CANCEL\' and reservation_room.status!=\'NOSHOW\' 
                    and EXTRA_SERVICE.code = \'EXTRA_BED\'
                    and (room_level.is_virtual=0 or room_level.is_virtual is null)
                     and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)>=\''.Date_Time::to_time(date('01/m/Y',$date)).'\' and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)<=\''.$date.'\' '.$exs_d;           
		$indexes['exs_eb_mtd'] = number_format(DB::fetch($sql,'tt'),0);
        
        $sql ='select 
                sum(
                    CASE
            			WHEN 
            				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
            			THEN
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
            			ELSE
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)
                   END
                ) as tt
                from EXTRA_SERVICE_INVOICE_DETAIL 
                inner join EXTRA_SERVICE_INVOICE on EXTRA_SERVICE_INVOICE.id = EXTRA_SERVICE_INVOICE_DETAIL.invoice_id
                inner join EXTRA_SERVICE on EXTRA_SERVICE.id = EXTRA_SERVICE_INVOICE_DETAIL.service_id
                left join reservation_room on EXTRA_SERVICE_INVOICE.reservation_room_id = reservation_room.id
                left join room_level on reservation_room.room_level_id = room_level.id
                where 
                    reservation_room.status!=\'CANCEL\' and reservation_room.status!=\'NOSHOW\' 
                    and  EXTRA_SERVICE.code = \'EXTRA_BED\'
                    and (room_level.is_virtual=0 or room_level.is_virtual is null)
                    and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)>=\''.Date_Time::to_time(date('01/01/Y',$date)).'\' and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)<=\''.$date.'\' '.$exs_d;
		$indexes['exs_eb_ytd'] = number_format(DB::fetch($sql,'tt'),0);
        
        
		//doanh thu dich vu mo rong (Early_checkin)
        $sql = 'select sum(
                    CASE
            			WHEN 
            				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
            			THEN
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
            			ELSE
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)
                   END
                ) as tt
        from extra_service_invoice_detail
        inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id  
        inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
        inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
        inner join reservation on reservation_room.reservation_id = reservation.id
        left join room_level on reservation_room.room_level_id = room_level.id  
        where 
        extra_service.code=\'EARLY_CHECKIN\'
        and room_level.is_virtual = 0
        and reservation_room.foc_all = 0  
        and  EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE=\''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\''.$exs_d;
		$indexes['exs_ei_d'] = DB::fetch($sql,'tt');
        
        $sql ='select sum(
                    CASE
            			WHEN 
            				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
            			THEN
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
            			ELSE
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)
                   END
                ) as tt 
        from extra_service_invoice_detail 
        inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id
        inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
        inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
        inner join reservation on reservation_room.reservation_id = reservation.id
        left join room_level on reservation_room.room_level_id = room_level.id     
        where 
        extra_service.code=\'EARLY_CHECKIN\'
        and room_level.is_virtual = 0
        and reservation_room.foc_all = 0 
        and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)>=\''.Date_Time::to_time(date('01/m/Y',$date)).'\' 
        and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)<=\''.$date.'\' '.$exs_d;
		$indexes['exs_ei_mtd'] = number_format(DB::fetch($sql,'tt'),0);
        
        $sql='select sum(
                    CASE
            			WHEN 
            				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
            			THEN
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
            			ELSE
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)
                   END
                ) as tt
        from extra_service_invoice_detail
        inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id
        inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
        inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
        inner join reservation on reservation_room.reservation_id = reservation.id
        left join room_level on reservation_room.room_level_id = room_level.id    
        where 
        extra_service.code=\'EARLY_CHECKIN\'
        and room_level.is_virtual = 0
        and reservation_room.foc_all = 0 
        and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)>=\''.Date_Time::to_time(date('01/01/Y',$date)).'\' 
        and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)<=\''.$date.'\' '.$exs_d;
		$indexes['exs_ei_ytd'] = number_format(DB::fetch($sql,'tt'),0);
		//doanh thu dich vu mo rong (Late_checkout)
        
        $sql ='select sum(
                    CASE
            			WHEN 
            				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
            			THEN
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
            			ELSE
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)
                   END
                ) as tt
        from extra_service_invoice_detail 
        inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id
        inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
        inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
        inner join reservation on reservation_room.reservation_id = reservation.id
        left join room_level on reservation_room.room_level_id = room_level.id      
        where
        extra_service.code=\'LATE_CHECKOUT\'
        and room_level.is_virtual = 0
        and reservation_room.foc_all = 0 
        and  EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE=\''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\''.$exs_d;
		$indexes['exs_lo_d'] = DB::fetch($sql,'tt');
        
        $sql ='select sum(
                    CASE
            			WHEN 
            				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
            			THEN
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
            			ELSE
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)
                   END
                ) as tt 
        from extra_service_invoice_detail 
        inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id
        inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
        inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
        inner join reservation on reservation_room.reservation_id = reservation.id
        left join room_level on reservation_room.room_level_id = room_level.id     
        where 
        extra_service.code=\'LATE_CHECKOUT\'
        and room_level.is_virtual = 0
        and reservation_room.foc_all = 0 
        and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)>=\''.Date_Time::to_time(date('01/m/Y',$date)).'\' 
        and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)<=\''.$date.'\' '.$exs_d;
		$indexes['exs_lo_mtd'] = number_format(DB::fetch($sql,'tt'),0);
        
        $sql='select sum(
                    CASE
            			WHEN 
            				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
            			THEN
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
            			ELSE
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)
                   END
                ) as tt 
        from extra_service_invoice_detail 
        inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id
        inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
        inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
        inner join reservation on reservation_room.reservation_id = reservation.id
        left join room_level on reservation_room.room_level_id = room_level.id    
        where 
        extra_service.code=\'LATE_CHECKOUT\'
        and room_level.is_virtual = 0
        and reservation_room.foc_all = 0 
        and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)>=\''.Date_Time::to_time(date('01/01/Y',$date)).'\' 
        and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)<=\''.$date.'\' '.$exs_d;
		$indexes['exs_lo_ytd'] = number_format(DB::fetch($sql,'tt'),0);
		//doanh thu Late Checkin (late checkin)
        $sql ='select sum(
                    CASE
            			WHEN 
            				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
            			THEN
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
            			ELSE
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)
                   END
                ) as tt
        from 
        extra_service_invoice_detail
        inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id  
        inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
        inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
        inner join reservation on reservation_room.reservation_id = reservation.id
        left join room_level on reservation_room.room_level_id = room_level.id   
        where 
        extra_service.code=\'LATE_CHECKIN\'
        and room_level.is_virtual = 0
        and reservation_room.status != \'CANCEL\' and reservation_room.status!=\'NOSHOW\' 
        and reservation_room.foc_all = 0
        and EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE=\''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\''.$exs_d;
		$indexes['exs_off_d'] = DB::fetch($sql,'tt');
        
        $sql='select sum(
                    CASE
            			WHEN 
            				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
            			THEN
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
            			ELSE
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)
                   END
                ) as tt
        from extra_service_invoice_detail
        inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id 
        inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
        inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
        inner join reservation on reservation_room.reservation_id = reservation.id
        left join room_level on reservation_room.room_level_id = room_level.id     
        where
        extra_service.code=\'LATE_CHECKIN\'
        and reservation_room.status != \'CANCEL\' and reservation_room.status!=\'NOSHOW\' 
        and room_level.is_virtual = 0
        and reservation_room.foc_all = 0
        and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)>=\''.Date_Time::to_time(date('01/m/Y',$date)).'\' 
        and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)<=\''.$date.'\' '.$exs_d;
		$indexes['exs_off_mtd'] = number_format(DB::fetch($sql,'tt'),0);
        
        $sql='select sum(
                    CASE
            			WHEN 
            				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
            			THEN
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
            			ELSE
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)
                   END
                ) as tt
        from extra_service_invoice_detail
        inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id
        inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
        inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
        inner join reservation on reservation_room.reservation_id = reservation.id
        left join room_level on reservation_room.room_level_id = room_level.id        
        where
        extra_service.code=\'LATE_CHECKIN\'
        and reservation_room.status != \'CANCEL\'
        and room_level.is_virtual = 0
        and reservation_room.foc_all = 0
        and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)>=\''.Date_Time::to_time(date('01/01/Y',$date)).'\'
        and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)<=\''.$date.'\' '.$exs_d;
		$indexes['exs_off_ytd'] = number_format(DB::fetch($sql,'tt'),0);
        
        
		//doanh thu dich vu mo rong (Tour)
        $sql='select 
                sum(
                    CASE
            			WHEN 
            				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
            			THEN
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
            			ELSE
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)
                   END
                ) as tt
                from EXTRA_SERVICE_INVOICE_DETAIL 
                inner join EXTRA_SERVICE_INVOICE on EXTRA_SERVICE_INVOICE.id = EXTRA_SERVICE_INVOICE_DETAIL.invoice_id
                inner join EXTRA_SERVICE on EXTRA_SERVICE.id = EXTRA_SERVICE_INVOICE_DETAIL.service_id
                left join reservation_room on EXTRA_SERVICE_INVOICE.reservation_room_id = reservation_room.id
                left join room_level on reservation_room.room_level_id = room_level.id
                where 
                    (reservation_room.status=\'CHECKIN\' OR reservation_room.status=\'CHECKOUT\' OR reservation_room.status=\'BOOKED\')
                    and EXTRA_SERVICE_INVOICE.payment_type = \'SERVICE\'
                    and (room_level.is_virtual=0 or room_level.is_virtual is null)
        and substr(extra_service.code,1,4) = \'TOUR\'
        and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)=\''.$date.'\''.$exs_d;
		$indexes['exs_tour_d'] = number_format(DB::fetch($sql,'tt'),0);
        
        $sql='select 
                sum(
                    CASE
            			WHEN 
            				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
            			THEN
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
            			ELSE
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)
                   END
                ) as tt
                from EXTRA_SERVICE_INVOICE_DETAIL 
                inner join EXTRA_SERVICE_INVOICE on EXTRA_SERVICE_INVOICE.id = EXTRA_SERVICE_INVOICE_DETAIL.invoice_id
                inner join EXTRA_SERVICE on EXTRA_SERVICE.id = EXTRA_SERVICE_INVOICE_DETAIL.service_id
                left join reservation_room on EXTRA_SERVICE_INVOICE.reservation_room_id = reservation_room.id
                left join room_level on reservation_room.room_level_id = room_level.id
                where 
                    (reservation_room.status=\'CHECKIN\' OR reservation_room.status=\'CHECKOUT\' OR reservation_room.status=\'BOOKED\')
                    and EXTRA_SERVICE_INVOICE.payment_type = \'SERVICE\'
                    and (room_level.is_virtual=0 or room_level.is_virtual is null)
        and substr(extra_service.code,1,4) = \'TOUR\'
        and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)>=\''.Date_Time::to_time(date('01/m/Y',$date)).'\' 
        and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)<=\''.$date.'\' '.$exs_d;
		$indexes['exs_tour_mtd'] = number_format(DB::fetch($sql,'tt'),0);
        
        $sql='select 
                sum(
                    CASE
            			WHEN 
            				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
            			THEN
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
            			ELSE
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)
                   END
                ) as tt
                from EXTRA_SERVICE_INVOICE_DETAIL 
                inner join EXTRA_SERVICE_INVOICE on EXTRA_SERVICE_INVOICE.id = EXTRA_SERVICE_INVOICE_DETAIL.invoice_id
                inner join EXTRA_SERVICE on EXTRA_SERVICE.id = EXTRA_SERVICE_INVOICE_DETAIL.service_id
                left join reservation_room on EXTRA_SERVICE_INVOICE.reservation_room_id = reservation_room.id
                left join room_level on reservation_room.room_level_id = room_level.id
                where 
                    (reservation_room.status=\'CHECKIN\' OR reservation_room.status=\'CHECKOUT\' OR reservation_room.status=\'BOOKED\')
                    and EXTRA_SERVICE_INVOICE.payment_type = \'SERVICE\'
                    and (room_level.is_virtual=0 or room_level.is_virtual is null)
        and substr(extra_service.code,1,4) = \'TOUR\'
        and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)>=\''.Date_Time::to_time(date('01/01/Y',$date)).'\' 
        and date_to_unix(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE)<=\''.$date.'\' '.$exs_d;
		$indexes['exs_tour_ytd'] = number_format(DB::fetch($sql,'tt'),0);
		//doanh thu an sang (breakfast)
	    $bfd = 0;$bfmtd=0;$bfytd=0;
        if(BREAKFAST_SPLIT_PRICE ==1)
        {   
            $sql='select room_status.id as id, NVL(reservation_room.adult,0) adult,
            NVL(reservation_room.child,0) child,reservation_room.breakfast
            from room_status
            inner join reservation_room on reservation_room.id = room_status.reservation_room_id 
             inner join reservation on reservation.id = reservation_room.reservation_id 
             left join room_level on reservation_room.room_level_id = room_level.id
             where  in_date =\''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\' and room_status.status!=\'CANCEL\' and room_status.status!=\'NOSHOW\' 
             and (room_status.house_status!=\'HOUSEUSE\' or room_status.house_status is null)
             and (ROOM_STATUS.STATUS = \'OCCUPIED\' OR ROOM_STATUS.STATUS = \'BOOKED\')
             and nvl(room_level.is_virtual,0) = 0
             and ROOM_STATUS.change_price > 0
             and foc is null and foc_all=0 
             and reservation_room.breakfast = 1
             and (in_date < departure_time or arrival_time = departure_time)'.$oor_d;
            $breakfast=DB::fetch_all($sql);
            $bfd=0;
            foreach($breakfast as $key =>$value)
            {  
               $bfd += ($value['adult']*(BREAKFAST_PRICE) + $value['child']*BREAKFAST_CHILD_PRICE);
            }
            $indexes['brf_d'] = number_format($bfd);
            
            $indexes['rr_d'] = $indexes['rr_d'] - $bfd;
            
            $sql='select room_status.id as id, NVL(reservation_room.adult,0) adult,
            NVL(reservation_room.child,0) child,reservation_room.breakfast
            from room_status
            inner join reservation_room on reservation_room.id = room_status.reservation_room_id 
             inner join reservation on reservation.id = reservation_room.reservation_id 
             left join room_level on reservation_room.room_level_id = room_level.id
             where  in_date>=\''.Date_Time::to_orc_date(date('01/m/Y',$date)).'\'
             and in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\'
             and (room_status.house_status!=\'HOUSEUSE\' or room_status.house_status is null)
             and (ROOM_STATUS.STATUS = \'OCCUPIED\' OR ROOM_STATUS.STATUS = \'BOOKED\')
             and ROOM_STATUS.change_price > 0
             and foc is null and foc_all=0
             and nvl(room_level.is_virtual,0) = 0
             and reservation_room.breakfast = 1
             and (in_date<=\''.Date_Time::convert_time_to_ora_date($date).'\' or arrival_time = departure_time)'.$oor_d;
            $breakfast=DB::fetch_all($sql);
            $bfmtd=0;
            foreach($breakfast as $key =>$value)
            {  
               $bfmtd += ($value['adult']*(BREAKFAST_PRICE) + $value['child']*BREAKFAST_CHILD_PRICE);
            }
            $indexes['brf_mtd'] = number_format($bfmtd);
            
            $indexes['rr_mtd'] = $indexes['rr_mtd'] - $bfmtd;
            
            $sql='select room_status.id as id, NVL(reservation_room.adult,0) adult,
            NVL(reservation_room.child,0) child,reservation_room.breakfast
            from room_status
            inner join reservation_room on reservation_room.id = room_status.reservation_room_id 
             inner join reservation on reservation.id = reservation_room.reservation_id 
             left join room_level on reservation_room.room_level_id = room_level.id
             where  in_date>=\''.Date_Time::to_orc_date(date('01/01/Y',$date)).'\' 
             and in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\'
             and (room_status.house_status!=\'HOUSEUSE\' or room_status.house_status is null)
             and (ROOM_STATUS.STATUS = \'OCCUPIED\' OR ROOM_STATUS.STATUS = \'BOOKED\')
             and nvl(room_level.is_virtual,0) = 0
             and ROOM_STATUS.change_price > 0
             and foc is null and foc_all=0
             and reservation_room.breakfast = 1
             and (in_date<=\''.Date_Time::convert_time_to_ora_date($date).'\' or arrival_time = departure_time)'.$oor_d;
            $breakfast=DB::fetch_all($sql);
            $bfytd=0;
            foreach($breakfast as $key =>$value)
            {  
               $bfytd += ($value['adult']*(BREAKFAST_PRICE) + $value['child']*BREAKFAST_CHILD_PRICE);
            }
            $indexes['brf_ytd'] = number_format($bfytd);
            $indexes['rr_ytd'] = $indexes['rr_ytd'] - $bfytd;
        }
		//doanh thu van chuyen (transfer)
        $sql='select sum(extra_service_invoice_detail.PRICE*(extra_service_invoice_detail.QUANTITY+nvl(extra_service_invoice_detail.CHANGE_QUANTITY,0))) as tt 
        from extra_service_invoice_detail 
        inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id       
        inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id 
        where (extra_service.code = \'BUS TICKET\' or extra_service.code = \'SLEEPING BUS\') and  EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE=\''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\''.$exs_d;
		$indexes['exs_tf_d'] = number_format(DB::fetch($sql,'tt'),0);
        
        $sql='select sum(extra_service_invoice_detail.PRICE*(extra_service_invoice_detail.QUANTITY+nvl(extra_service_invoice_detail.CHANGE_QUANTITY,0))) as tt 
        from extra_service_invoice_detail
        inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id        
        inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id 
        where (extra_service.code = \'BUS TICKET\' or extra_service.code = \'SLEEPING BUS\') and  EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE>=\''.Date_Time::to_orc_date(date('01/m/Y',$date)).'\' and EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE<=\''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\' '.$exs_d;
		$indexes['exs_tf_mtd'] = number_format(DB::fetch($sql,'tt'),0);
        
        $sql='select sum(extra_service_invoice_detail.PRICE*(extra_service_invoice_detail.QUANTITY+nvl(extra_service_invoice_detail.CHANGE_QUANTITY,0))) as tt 
        from extra_service_invoice_detail 
        inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id        
        inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id 
        where (extra_service.code = \'BUS TICKET\' or extra_service.code = \'SLEEPING BUS\') and  EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE>=\''.Date_Time::to_orc_date(date('01/01/Y',$date)).'\' and EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE<=\''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\' '.$exs_d;
		$indexes['exs_tf_ytd'] = number_format(DB::fetch($sql,'tt'),0);
		
		//doanh thu an uong 
		$indexes['fbr_d'] = number_format(DB::fetch('select sum(total) as arr from bar_reservation where  status=\'CHECKOUT\' and time_out>=\''.$date.'\' and time_out<\''.($date+86400).'\' '.$bar_portal,'arr'),0);
		$indexes['fbr_mtd'] = number_format(DB::fetch('select sum(total) as arr from bar_reservation where  (status=\'CHECKOUT\') and time_out>=\''.Date_Time::to_time(date('01/m/Y',$date)).'\' and time_out<\''.($date+86400).'\' '.$bar_portal,'arr'),0);
		$indexes['fbr_ytd'] = number_format(DB::fetch('select sum(total) as arr from bar_reservation where  (status=\'CHECKOUT\') and time_out>=\''.Date_Time::to_time(date('01/01/Y',$date)).'\' and time_out<\''.($date+86400).'\' '.$bar_portal,'arr'),0);
		
		//doanh thu buong
		//doanh thu buong (Minibar)
        $sql='select sum(
            case
            when (reservation_room.foc_all != 0 or reservation_room.status = \'CANCEL\' or reservation_room.status = \'NOSHOW\')
            then 0
            else
            housekeeping_invoice.total
            end
            ) as arr 
        from housekeeping_invoice 
            left join reservation_room on HOUSEKEEPING_INVOICE.reservation_room_id = reservation_room.id
            left join room_level on reservation_room.room_level_id = room_level.id
        where 
            housekeeping_invoice.type=\'MINIBAR\' 
            and TO_CHAR(FROM_UNIXTIME(housekeeping_invoice.time), \'DD-MON-YYYY\') = \''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\' '.$hkrm_d;
        $indexes['hkrm_d'] = number_format(DB::fetch($sql,'arr'),0);
        $indexes['hkrm_mtd'] = number_format(DB::fetch('select sum(
                                                            case
                                                            when (reservation_room.foc_all != 0 or reservation_room.status = \'CANCEL\' or reservation_room.status = \'NOSHOW\')
                                                            then 0
                                                            else
                                                            housekeeping_invoice.total
                                                            end
                                                            ) as arr 
                                                            from housekeeping_invoice 
                                                            left join reservation_room on HOUSEKEEPING_INVOICE.reservation_room_id = reservation_room.id
                                                            left join room_level on reservation_room.room_level_id = room_level.id
                                                        where 
                                                            housekeeping_invoice.type=\'MINIBAR\' 
                                                            and FROM_UNIXTIME(housekeeping_invoice.time)>=\''.Date_Time::to_orc_date(date('01/m/Y',$date)).'\' and FROM_UNIXTIME(housekeeping_invoice.time)<=\''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\''.$hkrm_d,'arr'),0);
                                                        
		$indexes['hkrm_ytd'] = number_format(DB::fetch('select sum(
                                                            case
                                                            when (reservation_room.foc_all != 0 or reservation_room.status = \'CANCEL\' or reservation_room.status = \'NOSHOW\')
                                                            then 0
                                                            else
                                                            housekeeping_invoice.total
                                                            end
                                                            ) as arr 
                                                            from housekeeping_invoice 
                                                            left join reservation_room on HOUSEKEEPING_INVOICE.reservation_room_id = reservation_room.id
                                                            left join room_level on reservation_room.room_level_id = room_level.id
                                                        where housekeeping_invoice.type=\'MINIBAR\' 
                                                            and FROM_UNIXTIME(housekeeping_invoice.time)>=\''.Date_Time::to_orc_date(date('01/01/Y',$date)).'\' 
                                                            and FROM_UNIXTIME(housekeeping_invoice.time)<=\''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\''.$hkrm_d,'arr'),0);
		
		//doanh thu buong (Laundry)
        
		$indexes['hkrl_d'] = number_format(DB::fetch('select sum(
                                                            case
                                                            when (reservation_room.foc_all != 0 or reservation_room.status = \'CANCEL\' or reservation_room.status = \'NOSHOW\')
                                                            then 0
                                                            else
                                                            housekeeping_invoice.total
                                                            end
                                                            ) as arr 
                                                            from housekeeping_invoice 
                                                            left join reservation_room on HOUSEKEEPING_INVOICE.reservation_room_id = reservation_room.id
                                                            left join room_level on reservation_room.room_level_id = room_level.id
                                                        where housekeeping_invoice.type=\'LAUNDRY\'
                                                            and TO_CHAR(FROM_UNIXTIME(housekeeping_invoice.time), \'DD-MON-YYYY\')=\''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\''.$hkrm_d,'arr'),0);
		$indexes['hkrl_mtd'] = number_format(DB::fetch('select sum(
                                                            case
                                                            when (reservation_room.foc_all != 0 or reservation_room.status = \'CANCEL\' or reservation_room.status = \'NOSHOW\')
                                                            then 0
                                                            else
                                                            housekeeping_invoice.total
                                                            end
                                                            ) as arr 
                                                            from housekeeping_invoice 
                                                            left join reservation_room on HOUSEKEEPING_INVOICE.reservation_room_id = reservation_room.id
                                                            left join room_level on reservation_room.room_level_id = room_level.id
                                                        where 
                                                        housekeeping_invoice.type=\'LAUNDRY\'
                                                        and FROM_UNIXTIME(housekeeping_invoice.time)>=\''.Date_Time::to_orc_date(date('01/m/Y',$date)).'\' and FROM_UNIXTIME(housekeeping_invoice.time)<=\''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\''.$hkrm_d,'arr'),0);
		$indexes['hkrl_ytd'] = number_format(DB::fetch('select sum(
                                                            case
                                                            when (reservation_room.foc_all != 0 or reservation_room.status = \'CANCEL\' or reservation_room.status = \'NOSHOW\')
                                                            then 0
                                                            else
                                                            housekeeping_invoice.total
                                                            end
                                                            ) as arr 
                                                            from housekeeping_invoice 
                                                            left join reservation_room on HOUSEKEEPING_INVOICE.reservation_room_id = reservation_room.id
                                                            left join room_level on reservation_room.room_level_id = room_level.id
                                                        where 
                                                        housekeeping_invoice.type=\'LAUNDRY\'
                                                        and FROM_UNIXTIME(housekeeping_invoice.time)>=\''.Date_Time::to_orc_date(date('01/01/Y',$date)).'\' and FROM_UNIXTIME(housekeeping_invoice.time)<=\''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\''.$hkrm_d,'arr'),0);
		
		//doanh thu buong (Equipment)
        
		$indexes['hkre_d'] = number_format(DB::fetch('select sum(
                                                            case
                                                            when (reservation_room.foc_all != 0 or reservation_room.status = \'CANCEL\' or reservation_room.status = \'NOSHOW\')
                                                            then 0
                                                            else
                                                            housekeeping_invoice.total
                                                            end
                                                            ) as arr 
                                                            from housekeeping_invoice 
                                                            left join reservation_room on HOUSEKEEPING_INVOICE.reservation_room_id = reservation_room.id
                                                            left join room_level on reservation_room.room_level_id = room_level.id
                                                        where housekeeping_invoice.type=\'EQUIP\' and TO_CHAR(FROM_UNIXTIME(housekeeping_invoice.time), \'DD-MON-YYYY\')=\''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\' '.$hkrm_d,'arr'),0);
		$indexes['hkre_mtd'] = number_format(DB::fetch('select sum(
                                                            case
                                                            when (reservation_room.foc_all != 0 or reservation_room.status = \'CANCEL\' or reservation_room.status = \'NOSHOW\' or reservation_room.status = \'NOSHOW\')
                                                            then 0
                                                            else
                                                            housekeeping_invoice.total
                                                            end
                                                            ) as arr 
                                                            from housekeeping_invoice 
                                                            left join reservation_room on HOUSEKEEPING_INVOICE.reservation_room_id = reservation_room.id
                                                            left join room_level on reservation_room.room_level_id = room_level.id
                                                        where housekeeping_invoice.type=\'EQUIP\' and FROM_UNIXTIME(housekeeping_invoice.time)>=\''.Date_Time::to_orc_date(date('01/m/Y',$date)).'\' and FROM_UNIXTIME(housekeeping_invoice.time)<=\''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\''.$hkrm_d,'arr'),0);
		$indexes['hkre_ytd'] = number_format(DB::fetch('select sum(
                                                            case
                                                            when (reservation_room.foc_all != 0 or reservation_room.status = \'CANCEL\')
                                                            then 0
                                                            else
                                                            housekeeping_invoice.total
                                                            end
                                                            ) as arr 
                                                            from housekeeping_invoice 
                                                            left join reservation_room on HOUSEKEEPING_INVOICE.reservation_room_id = reservation_room.id
                                                            left join room_level on reservation_room.room_level_id = room_level.id
                                                        where housekeeping_invoice.type=\'EQUIP\' and FROM_UNIXTIME(housekeeping_invoice.time)>=\''.Date_Time::to_orc_date(date('01/01/Y',$date)).'\' and FROM_UNIXTIME(housekeeping_invoice.time)<=\''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\''.$hkrm_d,'arr'),0);

		
		//Kimtan them doanh thu spa moi cho chat choi hon
        
        $cond_spa_d = 'AND massage_reservation_room.time<='.(Date_Time::to_time(date('d/m/Y',$date))+86399).' AND massage_reservation_room.time>='.(Date_Time::to_time(date('d/m/Y',$date))).'';
        $cond_spa_m = 'AND massage_reservation_room.time<='.(Date_Time::to_time(date('d/m/Y',$date))+86399).' AND massage_reservation_room.time>='.(Date_Time::to_time(date('1/m/Y',$date))).'';
        $cond_spa_y = 'AND massage_reservation_room.time<='.(Date_Time::to_time(date('d/m/Y',$date))+86399).' AND massage_reservation_room.time>='.(Date_Time::to_time(date('1/1/Y',$date))).'';
        
		$indexes['spa_d'] = $this->get_spa($cond_spa,$cond_spa_d);
        $indexes['spa_mtd'] = $this->get_spa($cond_spa,$cond_spa_m);
        $indexes['spa_ytd'] = $this->get_spa($cond_spa,$cond_spa_y);
        //end Kimtan them doanh thu spa moi cho chat choi hon
        //Kimtan:: them doanh thu dat tiec
        $cond_party_d = ' AND party_reservation.checkin_time<='.(Date_Time::to_time(date('d/m/Y',$date))+86399).' AND party_reservation.checkout_time>='.(Date_Time::to_time(date('d/m/Y',$date))).'';
        $cond_party_m = ' AND party_reservation.checkin_time<='.(Date_Time::to_time(date('d/m/Y',$date))+86399).' AND party_reservation.checkout_time>='.(Date_Time::to_time(date('1/m/Y',$date))).'';
        $cond_party_y = ' AND party_reservation.checkin_time<='.(Date_Time::to_time(date('d/m/Y',$date))+86399).' AND party_reservation.checkout_time>='.(Date_Time::to_time(date('1/1/Y',$date))).'';
        
		$indexes['party_d'] = $this->get_party($cond_party,$cond_party_d);
        $indexes['party_mtd'] = $this->get_party($cond_party,$cond_party_m);
        $indexes['party_ytd'] = $this->get_party($cond_party,$cond_party_y);
        // end Kimtan:: them doanh thu dat tiec
        //Kimtan:: them doanh thu ban ve
        $cond_ticket_d = ' AND ticket_invoice.date_used=\''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\'';
        $cond_ticket_m = ' AND ticket_invoice.date_used>=\''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\' AND ticket_invoice.date_used>=\''.Date_Time::to_orc_date(date('1/m/Y',$date)).'\'';
        $cond_ticket_y = ' AND ticket_invoice.date_used>=\''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\' AND ticket_invoice.date_used>=\''.Date_Time::to_orc_date(date('1/1/Y',$date)).'\'';
        
		$indexes['ticket_d'] = $this->get_ticket($cond_ticket,$cond_ticket_d);
        $indexes['ticket_mtd'] = $this->get_ticket($cond_ticket,$cond_ticket_m);
        $indexes['ticket_ytd'] = $this->get_ticket($cond_ticket,$cond_ticket_y);
        // end Kimtan:: them doanh thu ban ve
        //Kimtan:: them doanh thu karaoke
        $cond_kara_d = ' AND karaoke_reservation.time<='.(Date_Time::to_time(date('d/m/Y',$date))+86399).' AND karaoke_reservation.time>='.(Date_Time::to_time(date('d/m/Y',$date))).'';
        $cond_kara_m = ' AND karaoke_reservation.time<='.(Date_Time::to_time(date('d/m/Y',$date))+86399).' AND karaoke_reservation.time>='.(Date_Time::to_time(date('1/m/Y',$date))).'';
        $cond_kara_y = ' AND karaoke_reservation.time<='.(Date_Time::to_time(date('d/m/Y',$date))+86399).' AND karaoke_reservation.time>='.(Date_Time::to_time(date('1/1/Y',$date))).'';
        
		$indexes['kara_d'] = $this->get_kara($cond_kara,$cond_kara_d);
        $indexes['kara_mtd'] = $this->get_kara($cond_kara,$cond_kara_m);
        $indexes['kara_ytd'] = $this->get_kara($cond_kara,$cond_kara_y);
        // end Kimtan:: them doanh thu karaoke
        
        //doanh thu Shop
		$indexes['shopr_d'] = number_format(DB::fetch('select sum(total) as arr from ve_reservation where TO_CHAR(FROM_UNIXTIME(time), \'DD-MON-YYYY\')=\''.Date_Time::to_orc_date(date('d/m/Y',$date)).'\''.$shopr_d,'arr'),0);
		$indexes['shopr_mtd'] = number_format(DB::fetch('select sum(total) as arr from ve_reservation where time>=\''.Date_Time::to_time(date('01/m/Y',$date)).'\' and time<=\''.($date+86400).'\''.$shopr_d,'arr'),0);
		$indexes['shopr_ytd'] = number_format(DB::fetch('select sum(total) as arr from ve_reservation where time>=\''.Date_Time::to_time(date('01/01/Y',$date)).'\' and time<=\''.($date+86400).'\''.$shopr_d,'arr'),0);
		//Doanh Thu luu tru
		$indexes['rsm_d'] = System::display_number_report(
                                                        str_replace(',','',$indexes['rr_d'])
                                                        +str_replace(',','',$indexes['exs_ei_d'])
                                                        +str_replace(',','',$indexes['exs_lo_d'])
                                                        +str_replace(',','',$indexes['exs_off_d'])
                                                        +str_replace(',','',$indexes['exs_eb_d'])
                                                        +str_replace(',','',$indexes['hkrm_d'])
                                                        +str_replace(',','',$indexes['hkrl_d'])
                                                        +str_replace(',','',$indexes['hkre_d'])
                                                        +str_replace(',','',$indexes['exs_tf_d'])
                                                        +str_replace(',','',$indexes['exs_tour_d'])
                                                        +str_replace(',','',$indexes['exs_d'])
                                                        );
		$indexes['rsm_mtd'] = System::display_number_report(
                                                        str_replace(',','',$indexes['rr_mtd'])
                                                        +str_replace(',','',$indexes['exs_ei_mtd'])
                                                        +str_replace(',','',$indexes['exs_lo_mtd'])
                                                        +str_replace(',','',$indexes['exs_off_mtd'])
                                                        +str_replace(',','',$indexes['exs_eb_mtd'])
                                                        +str_replace(',','',$indexes['hkrm_mtd'])
                                                        +str_replace(',','',$indexes['hkrl_mtd'])
                                                        +str_replace(',','',$indexes['hkre_mtd'])
                                                        +str_replace(',','',$indexes['exs_tf_mtd'])
                                                        +str_replace(',','',$indexes['exs_tour_mtd'])
                                                        +str_replace(',','',$indexes['exs_mtd'])
                                                        );
		$indexes['rsm_ytd'] = System::display_number_report(
                                                        str_replace(',','',$indexes['rr_ytd'])
                                                        +str_replace(',','',$indexes['exs_ei_ytd'])
                                                        +str_replace(',','',$indexes['exs_lo_ytd'])
                                                        +str_replace(',','',$indexes['exs_off_ytd'])
                                                        +str_replace(',','',$indexes['exs_eb_ytd'])
                                                        +str_replace(',','',$indexes['hkrm_ytd'])
                                                        +str_replace(',','',$indexes['hkrl_ytd'])
                                                        +str_replace(',','',$indexes['hkre_ytd'])
                                                        +str_replace(',','',$indexes['exs_tf_ytd'])
                                                        +str_replace(',','',$indexes['exs_tour_ytd'])
                                                        +str_replace(',','',$indexes['exs_ytd'])
                                                        );
		//Doanh thu POS
		$indexes['pos_d'] = System::display_number_report(/*str_replace(',','',$indexes['brf_d'])+*/str_replace(',','',$indexes['fbr_d'])+str_replace(',','',$indexes['shopr_d'])+$indexes['spa_d']+$indexes['party_d']+$indexes['ticket_d']+$indexes['kara_d']);//+str_replace(',','',$indexes['kaspa_d'])
		$indexes['pos_mtd'] = System::display_number_report(/*str_replace(',','',$indexes['brf_mtd'])+*/str_replace(',','',$indexes['fbr_mtd'])+str_replace(',','',$indexes['shopr_mtd'])+$indexes['spa_mtd']+$indexes['party_mtd']+$indexes['ticket_mtd']+$indexes['kara_mtd']);//+str_replace(',','',$indexes['kaspa_mtd'])
		$indexes['pos_ytd'] = System::display_number_report(/*str_replace(',','',$indexes['brf_ytd'])+*/str_replace(',','',$indexes['fbr_ytd'])+str_replace(',','',$indexes['shopr_ytd'])+$indexes['spa_ytd']+$indexes['party_ytd']+$indexes['ticket_ytd']+$indexes['kara_ytd']);//+str_replace(',','',$indexes['kaspa_ytd'])
		//Doanh thu d?ch vu kh�c (c�n l?i)
		$indexes['extra_d'] = System::display_number(str_replace(',','',$indexes['exs_d'])/*-str_replace(',','',$indexes['exs_ei_d'])-str_replace(',','',$indexes['exs_lo_d'])-str_replace(',','',$indexes['exs_off_d'])-str_replace(',','',$indexes['exs_eb_d'])-str_replace(',','',$indexes['exs_tf_d'])-str_replace(',','',$indexes['exs_tour_d'])-str_replace(',','',$indexes['brf_d'])*/);
		$indexes['extra_mtd'] = System::display_number(str_replace(',','',$indexes['exs_mtd'])/*-str_replace(',','',$indexes['exs_ei_mtd'])-str_replace(',','',$indexes['exs_lo_mtd'])-str_replace(',','',$indexes['exs_off_mtd'])-str_replace(',','',$indexes['exs_eb_mtd'])-str_replace(',','',$indexes['exs_tf_mtd'])-str_replace(',','',$indexes['exs_tour_mtd'])-str_replace(',','',$indexes['brf_mtd'])*/);
		$indexes['extra_ytd'] = System::display_number(str_replace(',','',$indexes['exs_ytd'])/*-str_replace(',','',$indexes['exs_ei_ytd'])-str_replace(',','',$indexes['exs_lo_ytd'])-str_replace(',','',$indexes['exs_off_ytd'])-str_replace(',','',$indexes['exs_eb_ytd'])-str_replace(',','',$indexes['exs_tf_ytd'])-str_replace(',','',$indexes['exs_tour_ytd'])-str_replace(',','',$indexes['brf_ytd'])*/);
		//doanh thu khach san
		$indexes['hr_d'] = System::display_number_report(str_replace(',','',$indexes['rsm_d'])+str_replace(',','',$indexes['pos_d']));
		$indexes['hr_mtd'] = System::display_number_report(str_replace(',','',$indexes['rsm_mtd'])+str_replace(',','',$indexes['pos_mtd']));
		$indexes['hr_ytd'] = System::display_number_report(str_replace(',','',$indexes['rsm_ytd'])+str_replace(',','',$indexes['pos_ytd']));
		
		//Guest in house
		$indexes['gih_d'] = $this->count_traveller(date('d/m/Y',$date),date('d/m/Y',$date));
        $indexes['gih_mtd'] = $this->count_traveller(date('1/m/Y',$date),date('d/m/Y',$date));
        $indexes['gih_ytd'] = $this->count_traveller(date('1/1/Y',$date),date('d/m/Y',$date));
		$indexes['gih_lysd'] = DB::fetch('select sum(adult) as arr from room_status  inner join reservation_room on reservation_room.id=room_status.reservation_room_id inner join reservation on reservation.id = reservation_room.reservation_id where  (room_status.status=\'OCCUPIED\' or room_status.status=\'OCCUPIED\') and in_date=\''.Date_Time::convert_time_to_ora_date($date_prev).'\''.$oor_d,'arr');
		$indexes['gih_lymtd'] = DB::fetch('select sum(adult) as arr from room_status  inner join reservation_room on reservation_room.id=room_status.reservation_room_id inner join reservation on reservation.id = reservation_room.reservation_id where  (room_status.status=\'OCCUPIED\' or room_status.status=\'OCCUPIED\') and in_date>=\''.Date_Time::to_orc_date(date('1/m/Y',$date_prev)).'\' and in_date<\''.Date_Time::convert_time_to_ora_date($date_prev).'\''.$oor_d,'arr');
		$indexes['gih_lyytd'] = DB::fetch('select sum(adult) as arr from room_status  inner join reservation_room on reservation_room.id=room_status.reservation_room_id inner join reservation on reservation.id = reservation_room.reservation_id where  (room_status.status=\'OCCUPIED\' or room_status.status=\'OCCUPIED\') and in_date>=\''.Date_Time::to_orc_date(date('1/1/Y',$date_prev)).'\' and in_date<\''.Date_Time::convert_time_to_ora_date($date_prev).'\''.$oor_d,'arr');
        
        if($indexes['gih_d']=='')
            $indexes['gih_d']=0;
        if($indexes['gih_mtd']=='')
            $indexes['gih_mtd']=0;
        if($indexes['gih_ytd']=='')
            $indexes['gih_ytd']=0;
        if($indexes['gih_lysd']=='')
            $indexes['gih_lysd']=0;
        if($indexes['gih_lymtd']=='')
            $indexes['gih_lymtd']=0;
        if($indexes['gih_lyytd']=='')
            $indexes['gih_lyytd']=0;
		//phong khach den
        
		$indexes['ra_d'] = DB::fetch('select count(*) as arr
        from reservation_room 
        inner join reservation on reservation_room.reservation_id = reservation.id
        inner join room_status on room_status.reservation_room_id = reservation_room.id and room_status.in_date = reservation_room.arrival_time
        left join room_level  on reservation_room.room_level_id = room_level.id
        where
         reservation_room.status != \'CANCEL\' 
         and reservation_room.status != \'NOSHOW\'
         and room_level.is_virtual = 0 
         --KimTan: xu ly doi phong moi
         and(
                (change_room_to_rr is null and change_room_from_rr is null and reservation_room.time_in >= (date_to_unix(in_date)+(6*3600)) and reservation_room.arrival_time = in_date)--kimtan:06/02/15 phong bt co ngay den = ngay xem va ko phai phong li   
                or
                (change_room_to_rr is not null and change_room_from_rr is null and reservation_room.time_in >= (date_to_unix(in_date)+(6*3600)))-- phong doi chang d?u co arrival_time = ngay xem va ko phai phong li
            )
        and date_to_unix(reservation_room.arrival_time)=\''.$date.'\' 
         '.$oor_d,'arr');
		$indexes['ra_mtd'] = DB::fetch('select count(*) as arr
        from reservation_room 
        inner join reservation on reservation_room.reservation_id = reservation.id
        inner join room_status on room_status.reservation_room_id = reservation_room.id and room_status.in_date = reservation_room.arrival_time
        left join room_level  on reservation_room.room_level_id = room_level.id 
        where 
        reservation_room.status != \'CANCEL\'
        and reservation_room.status != \'NOSHOW\'
         and room_level.is_virtual = 0 
         
         --KimTan: xu ly doi phong moi
         and(
                (change_room_to_rr is null and change_room_from_rr is null and reservation_room.time_in >= (date_to_unix(in_date)+(6*3600)) and reservation_room.arrival_time = in_date)--kimtan:06/02/15 phong bt co ngay den = ngay xem va ko phai phong li   
                or
                (change_room_to_rr is not null and change_room_from_rr is null and reservation_room.time_in >= (date_to_unix(in_date)+(6*3600)))-- phong doi chang d?u co arrival_time = ngay xem va ko phai phong li
            )
        and date_to_unix(reservation_room.arrival_time)>=\''.Date_Time::to_time(date('1/m/Y',$date)).'\' 
        and date_to_unix(reservation_room.arrival_time)<=\''.$date.'\'
        '.$oor_d,'arr');
		
        
        $indexes['ra_ytd'] = DB::fetch('select count(*) as arr
        from reservation_room 
        inner join reservation on reservation_room.reservation_id = reservation.id
        inner join room_status on room_status.reservation_room_id = reservation_room.id and room_status.in_date = reservation_room.arrival_time
        left join room_level  on reservation_room.room_level_id = room_level.id 
        where 
        reservation_room.status != \'CANCEL\'
        and reservation_room.status != \'NOSHOW\'
         and room_level.is_virtual = 0 
         
         --KimTan: xu ly doi phong moi
         and(
                (change_room_to_rr is null and change_room_from_rr is null and reservation_room.time_in >= (date_to_unix(in_date)+(6*3600)) and reservation_room.arrival_time = in_date)--kimtan:06/02/15 phong bt co ngay den = ngay xem va ko phai phong li   
                or
                (change_room_to_rr is not null and change_room_from_rr is null and reservation_room.time_in >= (date_to_unix(in_date)+(6*3600)))-- phong doi chang d?u co arrival_time = ngay xem va ko phai phong li
            )
        and date_to_unix(reservation_room.arrival_time)>=\''.Date_Time::to_time(date('01/01/Y',$date)).'\' 
        and date_to_unix(reservation_room.arrival_time)<=\''.$date.'\'
        '.$oor_d,'arr');
		$indexes['noshow_td'] = 0;
        $indexes['noshow_mtd'] = 0;
        $indexes['noshow_ytd'] = 0;
        $indexes['noshow_td'] = $this->get_noshow($date,$oor_d,'date');
        $indexes['noshow_mtd'] = $this->get_noshow($date,$oor_d,'month');
        $indexes['noshow_ytd'] = $this->get_noshow($date,$oor_d,'year');
		//phong khach di 
		$sql_rd_d=' select count(*) as arr
                    from reservation_room 
                    inner join reservation on reservation_room.reservation_id = reservation.id
                    left join room_level on reservation_room.room_level_id = room_level.id
                    where (reservation_room.status = \'CHECKOUT\' OR reservation_room.status = \'CHECKIN\') 
                    and reservation_room.DEPARTURE_TIME = \''.Date_Time::convert_time_to_ora_date($date).'\'
                    and room_level.is_virtual = 0
                    and reservation_room.change_room_to_rr is null'
        .$oor_d;
        //System::Debug($sql_rd_d);
        $indexes['rd_d'] = DB::fetch($sql_rd_d,'arr');
		$indexes['rd_mtd'] = DB::fetch('select count(*) as arr
                                        from reservation_room 
                                        inner join reservation on reservation_room.reservation_id = reservation.id
                                        left join room_level on reservation_room.room_level_id = room_level.id
                                        where (reservation_room.status = \'CHECKOUT\' OR reservation_room.status = \'CHECKIN\') 
                                        and date_to_unix(reservation_room.DEPARTURE_TIME)>=\''.Date_Time::to_time(date('01/m/Y',$date)).'\'
                                        and date_to_unix(reservation_room.DEPARTURE_TIME)<=\''.$date.'\'
                                        and room_level.is_virtual = 0
                                        and reservation_room.change_room_to_rr is null'
        .$oor_d,'arr');
		$indexes['rd_ytd'] = DB::fetch('select count(*) as arr
                                        from reservation_room 
                                        inner join reservation on reservation_room.reservation_id = reservation.id
                                        left join room_level on reservation_room.room_level_id = room_level.id
                                        where (reservation_room.status = \'CHECKOUT\' OR reservation_room.status = \'CHECKIN\') 
                                        and date_to_unix(reservation_room.DEPARTURE_TIME)>=\''.Date_Time::to_time(date('01/01/Y',$date)).'\'
                                        and date_to_unix(reservation_room.DEPARTURE_TIME)<=\''.$date.'\'
                                        and room_level.is_virtual = 0
                                        and reservation_room.change_room_to_rr is null'
                                        .$oor_d,'arr');
		//Extra Bed
		$indexes['eb_d'] = DB::fetch('select sum(extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0)) as acount 
                                        from  extra_service_invoice_detail 
                                        inner join extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id  
                                        inner join reservation_room on extra_service_invoice.reservation_room_id = reservation_room.id
                                        inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                                        inner join reservation on reservation_room.reservation_id = reservation.id
                                        left join room_level on reservation_room.room_level_id = room_level.id
                                        where  
                                        extra_service.code = \'EXTRA_BED\'
                                        and room_level.is_virtual = 0 
                                        and reservation_room.foc_all = 0 
                                        and reservation_room.status!=\'CANCEL\' and reservation_room.status != \'NOSHOW\'
                                        and extra_service_invoice_detail.in_date=\''.Date_Time::convert_time_to_ora_date($date).'\''.$exs_d,'acount');
		$indexes['eb_mtd'] = DB::fetch('select sum(extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0)) as acount
                                         from  extra_service_invoice_detail 
                                         inner join extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id
                                         inner join reservation_room on extra_service_invoice.reservation_room_id = reservation_room.id
                                         inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id 
                                         inner join reservation on reservation_room.reservation_id = reservation.id
                                         left join room_level on reservation_room.room_level_id = room_level.id
                                         where  
                                         extra_service.code = \'EXTRA_BED\'
                                         and room_level.is_virtual = 0  
                                         and reservation_room.foc_all = 0
                                         and reservation_room.status!=\'CANCEL\' and reservation_room.status != \'NOSHOW\'
                                         and date_to_unix(extra_service_invoice_detail.in_date)>=\''.Date_Time::to_time(date('1/m/Y',$date)).'\' 
                                         and date_to_unix(extra_service_invoice_detail.in_date)<=\''.$date.'\' '.$exs_d,'acount');
		$indexes['eb_ytd'] = DB::fetch('select sum(extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0)) as acount
                                         from  extra_service_invoice_detail
                                         inner join extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id 
                                         inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                                         inner join reservation_room on extra_service_invoice.reservation_room_id = reservation_room.id
                                         inner join reservation on reservation_room.reservation_id = reservation.id
                                         left join room_level on reservation_room.room_level_id = room_level.id
                                         where  
                                         extra_service.code = \'EXTRA_BED\' 
                                         and room_level.is_virtual = 0
                                         and reservation_room.foc_all = 0 
                                         and reservation_room.status!=\'CANCEL\' and reservation_room.status != \'NOSHOW\'
                                         and date_to_unix(extra_service_invoice_detail.in_date)>=\''.Date_Time::to_time(date('1/1/Y',$date)).'\' 
                                         and date_to_unix(extra_service_invoice_detail.in_date)<=\''.$date.'\''.$exs_d,'acount');
		
        //Early Checked-in
		$indexes['eci_d'] = DB::fetch('select sum(extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0)) as acount 
                                        from  extra_service_invoice_detail 
                                        inner join extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id  
                                        inner join reservation_room on extra_service_invoice.reservation_room_id = reservation_room.id
                                        inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                                        inner join reservation on reservation_room.reservation_id = reservation.id
                                        left join room_level on reservation_room.room_level_id = room_level.id
                                        where  
                                        extra_service.code = \'EARLY_CHECKIN\'
                                        and room_level.is_virtual = 0 
                                        and reservation_room.foc_all = 0 
                                        and reservation_room.status != \'CANCEL\' and reservation_room.status != \'NOSHOW\'
                                        and extra_service_invoice_detail.in_date=\''.Date_Time::convert_time_to_ora_date($date).'\''.$exs_d,'acount');
		$indexes['eci_mtd'] = DB::fetch('select sum(extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0)) as acount
                                         from  extra_service_invoice_detail 
                                         inner join extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id
                                         inner join reservation_room on extra_service_invoice.reservation_room_id = reservation_room.id
                                         inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id 
                                         inner join reservation on reservation_room.reservation_id = reservation.id
                                         left join room_level on reservation_room.room_level_id = room_level.id
                                         where  
                                         extra_service.code = \'EARLY_CHECKIN\'
                                         and room_level.is_virtual = 0  
                                         and reservation_room.foc_all = 0
                                         and reservation_room.status != \'CANCEL\' and reservation_room.status != \'NOSHOW\'
                                         and date_to_unix(extra_service_invoice_detail.in_date)>=\''.Date_Time::to_time(date('1/m/Y',$date)).'\' 
                                         and date_to_unix(extra_service_invoice_detail.in_date)<=\''.$date.'\' '.$exs_d,'acount');
		$indexes['eci_ytd'] = DB::fetch('select sum(extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0)) as acount
                                         from  extra_service_invoice_detail
                                         inner join extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id 
                                         inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                                         inner join reservation_room on extra_service_invoice.reservation_room_id = reservation_room.id
                                         inner join reservation on reservation_room.reservation_id = reservation.id
                                         left join room_level on reservation_room.room_level_id = room_level.id
                                         where  
                                         extra_service.code = \'EARLY_CHECKIN\' 
                                         and room_level.is_virtual = 0
                                         and reservation_room.foc_all = 0 
                                         and reservation_room.status != \'CANCEL\' and reservation_room.status != \'NOSHOW\'
                                         and date_to_unix(extra_service_invoice_detail.in_date)>=\''.Date_Time::to_time(date('1/1/Y',$date)).'\' 
                                         and date_to_unix(extra_service_invoice_detail.in_date)<=\''.$date.'\''.$exs_d,'acount');
		
		//late Checked-in
		$indexes['lci_d'] = DB::fetch('select sum(extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0)) as acount from  
                                      extra_service_invoice_detail 
                                      inner join extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id 
                                      inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                                      inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                                      inner join reservation on reservation_room.reservation_id = reservation.id
                                      left join room_level on reservation_room.room_level_id = room_level.id
                                      where  
                                      extra_service.code = \'LATE_CHECKIN\'
                                      and room_level.is_virtual = 0 
                                      and reservation_room.foc_all = 0 
                                      and reservation_room.status != \'CANCEL\' and reservation_room.status != \'NOSHOW\' 
                                      and extra_service_invoice_detail.in_date=\''.Date_Time::convert_time_to_ora_date($date).'\''.$exs_d,'acount');
		$indexes['lci_mtd'] = DB::fetch('select sum(extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0)) as acount 
                                      from
                                      extra_service_invoice_detail 
                                      inner join extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id 
                                      inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                                      inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                                      inner join reservation on reservation_room.reservation_id = reservation.id
                                      left join room_level on reservation_room.room_level_id = room_level.id
                                      where 
                                      extra_service.code = \'LATE_CHECKIN\' 
                                      and room_level.is_virtual = 0
                                      and reservation_room.foc_all = 0  
                                      and reservation_room.status != \'CANCEL\' and reservation_room.status != \'NOSHOW\'
                                      and date_to_unix(extra_service_invoice_detail.in_date)>=\''.Date_Time::to_time(date('1/m/Y',$date)).'\' 
                                      and date_to_unix(extra_service_invoice_detail.in_date)<=\''.$date.'\' '.$exs_d,'acount');
		$indexes['lci_ytd'] = DB::fetch('select sum(extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0)) as acount 
                                      from  
                                      extra_service_invoice_detail 
                                      inner join extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id
                                      inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                                      inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                                      inner join reservation on reservation_room.reservation_id = reservation.id
                                      left join room_level on reservation_room.room_level_id = room_level.id 
                                      where 
                                       extra_service.code = \'LATE_CHECKIN\'
                                      and room_level.is_virtual = 0
                                      and reservation_room.foc_all = 0  
                                      and reservation_room.status != \'CANCEL\' and reservation_room.status != \'NOSHOW\'
                                      and date_to_unix(extra_service_invoice_detail.in_date)>=\''.Date_Time::to_time(date('1/1/Y',$date)).'\' 
                                      and date_to_unix(extra_service_invoice_detail.in_date)<=\''.$date.'\''.$exs_d,'acount');
		
		//Lake Checked-out
		$indexes['lco_d'] = DB::fetch('select sum(extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0)) as acount
                                       from extra_service_invoice_detail 
                                       inner join extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id 
                                       inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                                       inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                                       inner join reservation on reservation_room.reservation_id = reservation.id
                                       left join room_level on reservation_room.room_level_id = room_level.id 
                                       where   
                                       extra_service.code = \'LATE_CHECKOUT\'
                                       and room_level.is_virtual = 0
                                       and reservation_room.foc_all = 0
                                       and reservation_room.status != \'CANCEL\' and reservation_room.status != \'NOSHOW\'
                                       and extra_service_invoice_detail.in_date=\''.Date_Time::convert_time_to_ora_date($date).'\''.$exs_d,'acount');
		$indexes['lco_mtd'] = DB::fetch('select sum(extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0)) as acount 
                                        from  extra_service_invoice_detail 
                                        inner join extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id 
                                        inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                                        inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                                        inner join reservation on reservation_room.reservation_id = reservation.id
                                        left join room_level on reservation_room.room_level_id = room_level.id
                                        where 
                                        extra_service.code = \'LATE_CHECKOUT\'
                                        and room_level.is_virtual = 0
                                        and reservation_room.foc_all = 0
                                        and reservation_room.status != \'CANCEL\' and reservation_room.status != \'NOSHOW\'
                                        and date_to_unix(extra_service_invoice_detail.in_date)>=\''.Date_Time::to_time(date('1/m/Y',$date)).'\' 
                                        and date_to_unix(extra_service_invoice_detail.in_date)<=\''.$date.'\' '.$exs_d,'acount');
		$indexes['lco_ytd'] = DB::fetch('select sum(extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0)) as acount
                                         from  extra_service_invoice_detail 
                                         inner join extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id 
                                         inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                                         inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                                         inner join reservation on reservation_room.reservation_id = reservation.id
                                         left join room_level on reservation_room.room_level_id = room_level.id
                                         where  
                                         extra_service.code = \'LATE_CHECKOUT\'
                                         and room_level.is_virtual = 0
                                         and reservation_room.foc_all = 0
                                         and reservation_room.status != \'CANCEL\' and reservation_room.status != \'NOSHOW\'
                                         and date_to_unix(extra_service_invoice_detail.in_date)>=\''.Date_Time::to_time(date('1/1/Y',$date)).'\' 
                                         and date_to_unix(extra_service_invoice_detail.in_date)<=\''.$date.'\''.$exs_d,'acount');
		
		//Phong su dung trong ngay 
		$indexes['du_d'] = DB::fetch('select count(*) as acount from room_status 
                    inner join reservation_room on reservation_room.id = room_status.reservation_room_id
                    inner join reservation on reservation.id = reservation_room.reservation_id
                    left join room_level on reservation_room.room_level_id = room_level.id 
                    where
                    room_status.status!=\'CANCEL\' and room_status.status != \'NOSHOW\'
                    and nvl(room_level.is_virtual,0) = 0
                    --KimTan xu ly doi phong moi
                    and (
                        (reservation_room.change_room_to_rr is null and reservation_room.change_room_from_rr is null and reservation_room.arrival_time = reservation_room.departure_time and in_date=reservation_room.departure_time and reservation_room.time_in>(date_to_unix(in_date)+(6*3600)))
                        or
                        (reservation_room.change_room_to_rr is null and reservation_room.change_room_from_rr is not null and from_unixtime(reservation_room.old_arrival_time) = reservation_room.departure_time and in_date=reservation_room.departure_time and reservation_room.old_arrival_time>(date_to_unix(in_date)+(6*3600)))
                        )
                    and in_date=\''.Date_Time::convert_time_to_ora_date($date).'\'
                    '.$oor_d,'acount');
		$indexes['du_mtd'] = DB::fetch('select count(*) as acount from room_status 
                    inner join reservation_room on reservation_room.id = room_status.reservation_room_id
                    inner join reservation on reservation.id = reservation_room.reservation_id 
		    left join room_level on reservation_room.room_level_id = room_level.id where  
		    room_status.status!=\'CANCEL\' and room_status.status != \'NOSHOW\' and nvl(room_level.is_virtual,0) = 0 and arrival_time>=\''.Date_Time::to_orc_date(date('1/m/Y',$date)).'\' and arrival_time<=\''.Date_Time::convert_time_to_ora_date($date).'\' and arrival_time=departure_time '.$oor_d,'acount');
		$indexes['du_ytd'] = DB::fetch('select count(*) as acount from room_status 
                    inner join reservation_room on reservation_room.id = room_status.reservation_room_id
                    inner join reservation on reservation.id = reservation_room.reservation_id left join room_level on reservation_room.room_level_id = room_level.id where room_status.status!=\'CANCEL\' and nvl(room_level.is_virtual,0) = 0 and arrival_time>=\''.Date_Time::to_orc_date(date('1/1/Y',$date)).'\' and arrival_time<=\''.Date_Time::convert_time_to_ora_date($date).'\' and arrival_time=departure_time'.$oor_d,'acount');
		
		//phong dat trong ngay
		$indexes['rmt_d'] = DB::fetch('select count(*) as acount from room_status 
                    inner join reservation_room on reservation_room.id = room_status.reservation_room_id
                    inner join reservation on reservation.id = reservation_room.reservation_id where reservation.portal_id = \''.PORTAL_ID.'\' and reservation_room.status=\'BOOKED\' and arrival_time=\''.Date_Time::convert_time_to_ora_date($date).'\'','acount');
		$indexes['rmt_mtd'] = DB::fetch('select count(*) as acount from reservation_room inner join reservation on reservation.id = reservation_room.reservation_id where reservation.portal_id = \''.PORTAL_ID.'\' and reservation_room.status=\'CHECKOUT\' and arrival_time>=\''.Date_Time::to_orc_date(date('1/m/Y',$date)).'\' and arrival_time<=\''.Date_Time::convert_time_to_ora_date($date).'\'','acount');
		$indexes['rmt_ytd'] = DB::fetch('select count(*) as acount from reservation_room inner join reservation on reservation.id = reservation_room.reservation_id where reservation.portal_id = \''.PORTAL_ID.'\' and reservation_room.status=\'CHECKOUT\' and arrival_time>=\''.Date_Time::to_orc_date(date('1/1/Y',$date)).'\' and arrival_time<=\''.Date_Time::convert_time_to_ora_date($date).'\'','acount');
		
        //ph�ng d? ki?n d?n ng�y mai
		$indexes['at_d'] = DB::fetch('select count(*) as arr
        from reservation_room 
        inner join reservation on reservation_room.reservation_id = reservation.id
        inner join room_status on room_status.reservation_room_id = reservation_room.id and room_status.in_date = reservation_room.arrival_time
        left join room_level  on reservation_room.room_level_id = room_level.id
        where
         reservation_room.status != \'CANCEL\' and reservation_room.status != \'NOSHOW\'
         and room_level.is_virtual = 0
         --KimTan: xu ly doi phong moi
         and(
                (change_room_to_rr is null and change_room_from_rr is null and reservation_room.time_in >= (date_to_unix(in_date)+(6*3600)) and reservation_room.arrival_time = in_date)--kimtan:06/02/15 phong bt co ngay den = ngay xem va ko phai phong li   
                or
                (change_room_to_rr is not null and change_room_from_rr is null and reservation_room.time_in >= (date_to_unix(in_date)+(6*3600)))-- phong doi chang d?u co arrival_time = ngay xem va ko phai phong li
            )
        and date_to_unix(reservation_room.arrival_time)=\''.($date+24*3600).'\' 
         '.$oor_d,'arr');             
		//phòng dự kiến đi ngày mai
		$sql_dt_d=' select count(*) as arr
                    from reservation_room 
                    inner join reservation on reservation_room.reservation_id = reservation.id
                    left join room_level on reservation_room.room_level_id = room_level.id
                    where (reservation_room.status = \'CHECKOUT\' OR reservation_room.status = \'CHECKIN\') 
                    and reservation_room.DEPARTURE_TIME = \''.Date_Time::convert_time_to_ora_date($date+24*3600).'\'
                    and room_level.is_virtual = 0
                    and reservation_room.change_room_to_rr is null'
        .$oor_d;
        //System::Debug($sql_rd_d);
        $indexes['dt_d'] = DB::fetch($sql_dt_d,'arr');            
		//phòng dự kiến ở ngày mai
       $sql = 'select 
                    count(*) as acount
                from 
                    room_status 
                    inner join reservation_room on reservation_room.id = room_status.reservation_room_id
                    inner join reservation on reservation.id = reservation_room.reservation_id 
                    left join room_level on reservation_room.room_level_id = room_level.id 
                where  
                    (room_status.status=\'OCCUPIED\' or room_status.status=\'BOOKED\')
                    and nvl(room_level.is_virtual,0) = 0
                    --KimTan : xu ly doi phong moi
                    and(
                        (reservation_room.time_in < (date_to_unix(in_date)+(6*3600)) and date_to_unix(reservation_room.departure_time) > date_to_unix(in_date) )
                        or
                        (reservation_room.change_room_from_rr is not null and reservation_room.arrival_time = in_date and reservation_room.old_arrival_time < (date_to_unix(in_date)+(6*3600)) and date_to_unix(reservation_room.departure_time) > date_to_unix(in_date) ) --neu la phong duoc doi den trong ngay hoac xem dung ngay doi (co ngay den bang in_date va change_room_from_rr is not null) ma co old_arr_time < in_date < dpt_time
                        )
                    ---
                    and in_date=\''.Date_Time::convert_time_to_ora_date($date+24*3600).'\''.$oor_d;
        //System::debug($sql);
        $indexes['oot_d'] = DB::fetch($sql,'acount');
        //phong hong ngay mai
        $indexes['oor_dd'] = DB::fetch('select count(*) as acount from room_status join room on room.id = room_id where house_status=\'REPAIR\' and in_date=\''.Date_Time::convert_time_to_ora_date($date+24*3600).'\''.$oor_d_1,'acount');//old left join --Luu nguyen giap comment sql
        //% phòng dự kiến ở ngày mai
		$indexes['ot_d'] = System::display_number(($indexes['oot_d']+$indexes['at_d'])*100/($indexes['trh_d']-$indexes['oor_dd'])).'%';
		
		$indexes['ytddo_d'] = (strtotime(date('m/d/Y',$date+24*3600))-strtotime(date('1/1/Y',$date)))/24/3600;
		//Gia Trung Binh
		$indexes['arr_d']=0;
        //echo (System::calculate_number($indexes['rr_mtd'])+System::calculate_number($indexes['exs_ei_mtd'])+System::calculate_number($indexes['exs_lo_mtd'])+System::calculate_number($indexes['exs_off_mtd'])).'---'.($indexes['orhc_mtd']+$indexes['ra_mtd']+$indexes['eci_mtd']+$indexes['lci_mtd']+$indexes['lco_mtd']);
        $indexes['arr_mtd']=($indexes['orhc_mtd']+$indexes['ra_mtd']+$indexes['lci_mtd'])>0?(System::calculate_number($indexes['rr_mtd'])+System::calculate_number($indexes['exs_off_mtd']))/($indexes['orhc_mtd']+$indexes['ra_mtd']+$indexes['lci_mtd']):'0';
		$indexes['arr_ytd']=($indexes['orhc_ytd']+$indexes['ra_ytd']+$indexes['lci_ytd'])>0?(System::calculate_number($indexes['rr_ytd'])+System::calculate_number($indexes['exs_off_ytd']))/($indexes['orhc_ytd']+$indexes['ra_ytd']+$indexes['lci_ytd']):'0';
        
        $this->parse_layout('report',$this->map+
			$indexes +
			array(
				'company_name'=>URL::get('company_name'),
				'create_place'=>URL::get('create_place'),
				'create_date'=>URL::get('create_date'),
				'currency_unit'=>URL::get('currency_unit'),
				'director'=>URL::get('director'),
				'general_accountant'=>URL::get('general_accountant'),
				'creator'=>URL::get('creator')
			));
	}
    function count_traveller($from_date,$to_date) //kimtan them ngay 26/08/15 de lay so khach giong tieu chi bc guest_in_hour_list
    {
        $d_f = Date_Time::to_orc_date($from_date);
        $d_t = Date_Time::to_orc_date($to_date);
        $sql = '
                SELECT 
				count(*) as num
			FROM
				reservation_room
				inner join reservation on reservation.id=reservation_room.reservation_id
                inner join room on reservation_room.room_id = room.id
                inner join room_level on room.room_level_id = room_level.id
				left outer join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
				left outer join traveller on traveller.id=reservation_traveller.traveller_id
                inner join room_status on room_status.reservation_room_id = reservation_room.id
			WHERE
				1=1
                AND 
                (
                    (
                        reservation_traveller.arrival_time <= (date_to_unix(room_status.in_date)+86399) 
                        AND reservation_traveller.departure_time > (date_to_unix(room_status.in_date)+86400)
                        and from_unixtime(reservation_traveller.arrival_time) != from_unixtime(reservation_traveller.departure_time)
                    )
                    or
                    (
                        reservation_traveller.arrival_time <= (date_to_unix(room_status.in_date)+86399)
                        and from_unixtime(reservation_traveller.arrival_time) = from_unixtime(reservation_traveller.departure_time)
                        and reservation_room.change_room_to_rr is null
                    )
                )
                AND (reservation_traveller.status = \'CHECKIN\' OR reservation_traveller.status = \'CHECKOUT\')
                AND traveller.first_name || \' \' || traveller.last_name != \' \'  
                AND 
                (
                    reservation_room.status = \'CHECKIN\'
                    OR
                    reservation_room.status = \'CHECKOUT\'
                )
                AND reservation_traveller.status != \'CHANGE\'    
                and room_status.in_date >= \''.$d_f.'\'
                and room_status.in_date <= \''.$d_t.'\'
            ';
            $count = DB::fetch($sql,'num');
        return $count;
    }
    function get_spa($cond,$cond2)
    {
        $sql = "
                SELECT
                    concat('SPA_',massage_reservation_room.id) as id
                    ,massage_reservation_room.total_amount as price
                    ,NVL(reservation_room.foc_all,0) as foc_all
                FROM
                    massage_reservation_room
                    left join reservation_room on reservation_room.id=massage_reservation_room.hotel_reservation_room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                    left join room on room.id=reservation_room.room_id
                    left join room_level on room.room_level_id=room_level.id
                    inner join party on party.user_id=massage_reservation_room.user_id
                WHERE
                    1=1
                    ".$cond.$cond2."
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    massage_reservation_room.time, massage_reservation_room.id
                ";
        $report = DB::fetch_all($sql);
        $total = 0;
        foreach($report as $key=>$value)
        {
            if($value['foc_all']!=0)
            {
                $report[$key]['price'] = 0;
            }
            $total +=$report[$key]['price'];
        }
        return $total;      
    }
    /** hàm lấy doanh thu đặt tiệc **/
    function get_party($cond,$cond2)
    {
        $sql = "
                SELECT
                    concat('PARTY_',party_reservation.id) as id
                    ,party_reservation.total as price
                    
                FROM
                    party_reservation
                    inner join party_type on party_type.id=party_reservation.party_type
                    inner join party on party.user_id=party_reservation.user_id
                WHERE
                    1=1
                    ".$cond.$cond2."
                    AND party_reservation.status!='CANCEL'
                ORDER BY
                    party_reservation.time, party_reservation.id
                ";
        $report = DB::fetch_all($sql);
        $total = 0;
        foreach($report as $key=>$value)
        {
            $total += $report[$key]['price'];
        }
        return $total;
    }
    /** hàm lấy doanh thu bán vé **/
    function get_ticket($cond,$cond2)
    {
        $sql = "
                SELECT
                    concat('TICKET_',ticket_invoice.id) as id
                    ,ticket_invoice.total as price
                FROM
                    ticket_invoice
                    inner join ticket on ticket.id=ticket_invoice.ticket_id
                    inner join ticket_group on ticket_group.id=ticket_invoice.ticket_area_id
                    inner join party on party.user_id=ticket_invoice.user_id
                WHERE
                1=1
                    ".$cond.$cond2."
                ORDER BY
                    ticket_invoice.date_used, ticket_invoice.id
                ";
        $report = DB::fetch_all($sql);
        $total = 0;
        foreach($report as $key=>$value)
        {
            $total += $report[$key]['price'];
        }
        return $total;
    }
    
    function get_kara($cond,$cond2)
    {
        $sql = "
                SELECT
                    concat('KARAOKE_',karaoke_reservation.id) as id
                    ,karaoke_reservation.total as price
                    ,NVL(reservation_room.foc_all,0) as foc_all
                FROM
                    karaoke_reservation
                    left join reservation_room on reservation_room.id = karaoke_reservation.reservation_room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                    left join room on room.id=reservation_room.room_id
                    left join room_level on room.room_level_id=room_level.id
                    inner join party on party.user_id=karaoke_reservation.user_id
                WHERE
                    1=1
                    ".$cond.$cond2."
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                    AND (karaoke_reservation.status='CHECKIN' OR karaoke_reservation.status='CHECKOUT' OR karaoke_reservation.status='BOOKED' )
                ORDER BY
                    karaoke_reservation.time, reservation_room.id
                ";
        $report = DB::fetch_all($sql);
        $total = 0;
        foreach($report as $key=>$value)
        {
            if($value['foc_all']!=0)
            {
                $report[$key]['price'] = 0;
            }
            $total += $report[$key]['price'];
        }
        return $total;
    }
    function get_noshow($date,$oor_d,$type)
    {
        $sql = 'select count(*) as arr
        from reservation_room 
        inner join reservation on reservation_room.reservation_id = reservation.id
        left join room_level  on reservation_room.room_level_id = room_level.id
        where
        reservation_room.status = \'NOSHOW\'
        and room_level.is_virtual = 0';
        $cond = '';
        if($type=='date')
        {
            $cond = 'and date_to_unix(reservation_room.arrival_time)=\''.$date.'\''.$oor_d;
        }
        elseif($type=='month')
        {
            $cond = 'and date_to_unix(reservation_room.arrival_time)<=\''.$date.'\' and date_to_unix(reservation_room.departure_time)>\''.(Date_Time::to_time(date('1/m/Y',$date))+86400).'\''.$oor_d;
        }
        else
        {
            $cond = 'and date_to_unix(reservation_room.arrival_time)<=\''.$date.'\' and date_to_unix(reservation_room.departure_time)>\''.(Date_Time::to_time(date('1/1/Y',$date))+86400).'\''.$oor_d;
        }
        $total = DB::fetch($sql.$cond,'arr');
        return $total;
    }
}
?>
