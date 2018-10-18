<?php
class ArrivalInDay extends Form
{
    function ArrivalInDay()
    {
        Form::Form('ArrivalInDay');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');       
    }
    
    function draw()
    {
        require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';	
        
        //1. lay ra thoi gian can tim kiem 
        
        if(Url::get('date'))
        {
            $this->day = Url::get('date');
            $this->to_date = Url::get('to_date');
        }
        else
        {
            $this->day  = date('d/m/Y');
            $this->to_date = date('d/m/Y');
            $_REQUEST['date'] = $this->day;
            $_REQUEST['to_date'] = $this->to_date;     
        }
        
        //2. lay ra mang danh sach late checkin nhung khong phai dayuse theo ngay 
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list());
        if(Url::get('portal_id'))
        {
            $portal_id = Url::get('portal_id');
        }
        else
        {
            $portal_id = PORTAL_ID;
            $_REQUEST['portal_id'] = PORTAL_ID;                       
        }
        //4. danh sach cac trang thai cua phong 
        $this->map['status_list'] = array('ALL'=>Portal::language('all'),'NOT_ASSGIN'=>'NOT_ASSGIN','BOOKED'=>'BOOKED','CHECKIN'=>'CHECKIN','CHECKOUT'=>'CHECKOUT');
        /*if($status = Url::get('status'))
        {
            if($status == 'NOT_ASSGIN')
                $cond.=' AND room.name is null AND reservation_room.status = \'BOOKED\' ';
            else
                $cond.=' AND reservation_room.status = \''.Url::get('status').'\' ';
        } */
        //5. danh sach li lo ei dayuse
        $this->map['early_late_list'] = array('ALL'=>Portal::language('all'),'E.I'=>'E.I','L.O'=>'L.O','L.I'=>'L.I','DAYUSE'=>'DAYUSE');
        /*if($early_late = Url::get('early_late'))
        {
            if($early_late == 'E.I')
                $cond.=' AND reservation_room.early_checkin != 0 ';
            if($early_late == 'L.O')
                $cond.=' AND reservation_room.late_checkout != 0';
        }*/
        
        $li_date = Date_Time::to_time($this->day);
        
        $li_to_date = Date_Time::to_time($this->to_date);
        $report = new Report;
        $report->items =array();
        $i = 1;
        $res_id = false;
        $total_adult = 0;
        $total_child = 0;
        $total_night = 0;
        $total_room = 0;
        $count_traveller = array();
        $traveller_id = array();
        $ids = array();
        $elements  = array();
        
        for(;$li_date<=$li_to_date;)
        {
            $day_orc = Date_Time::to_orc_date(date('d/m/Y',$li_date));
            //$li_date = $li_date + 86400;
            $arrival_date = Date_Time::convert_time_to_ora_date($li_date);
            /*
            $late_checkin_sql = 'select
                                     reservation_room.id,
                                     reservation_room.arrival_time,
                                     reservation_room.departure_time
                                 from
                                     extra_service_invoice 
                                     inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                                     inner join extra_service_invoice_detail on extra_service_invoice_detail.invoice_id = extra_service_invoice.id
                                     inner join extra_service on extra_service.id = extra_service_invoice_detail.service_id
                                 where 
                                     extra_service.code = \'LATE_CHECKIN\'
                                     and extra_service_invoice_detail.in_date = \''.$arrival_date.'\'
                                 ';
                                 //giap comment: and reservation_room.departure_time <> \''.$arrival_date.'\'
            $late_checkin_arr = DB::fetch_all($late_checkin_sql);
            
            /*
            Array
            (
                [41] => Array
                    (
                        [id] => 41
                        [arrival_time] => 05-FEB-15
                        [departure_time] => 12-FEB-15
                    )
            
                [40] => Array
                    (
                        [id] => 40
                        [arrival_time] => 05-FEB-15
                        [departure_time] => 06-FEB-15
                    )
            
            )
            */
            /*
            $late_checkin_cond = '';
            $li_cond ='';
            foreach($late_checkin_arr as $key =>$value)
            {
               $late_checkin_cond .= ' OR reservation_room.id = '.$value['id'].' '; 
               $li_cond .=' AND reservation_room.id = '.$value['id'];
            }
            /*
             OR reservation_room.id = 41  OR reservation_room.id = 40 
            */
            /*
            $late_checkin_cond = rtrim($late_checkin_cond,'||');
            $cond ='';
            if(Url::get('early_late')!='ALL')
            {
                if(Url::get('early_late') == 'E.I')
                    $cond.=' AND reservation_room.early_checkin != 0 ';
                if(Url::get('early_late') == 'L.O')
                    $cond.=' AND reservation_room.late_checkout != 0';
                if(Url::get('early_late') == 'DAYUSE')
                    $cond.=' AND reservation_room.arrival_time = reservation_room.departure_time AND reservation_room.arrival_time = \''.$day_orc.'\''.$late_checkin_cond;
                if(Url::get('early_late') == 'L.I')
                    $cond.=$li_cond!=''?$li_cond:' AND 1=0 ';
            }
            
            $cond .= ' and (
                        (reservation_room.arrival_time = reservation_room.departure_time AND reservation_room.change_room_to_rr is null AND reservation_room.arrival_time = \''.$day_orc.'\') 
                        OR
                        (reservation_room.early_checkin != 0 AND reservation_room.arrival_time = \''.$day_orc.'\')'
                        .$late_checkin_cond.
                        ' OR (reservation_room.departure_time= \''.$day_orc.'\' and reservation_room.late_checkout != 0))';
            
     
            if(Url::get('status') && Url::get('status')!='ALL')
            {
                if(Url::get('status') == 'NOT_ASSGIN')
                    $cond.=' AND room.name is null AND reservation_room.status = \'BOOKED\' ';
                else
                {
                	$cond.=' AND reservation_room.status = \''.Url::get('status').'\' ';
                } 
            }*/ 
            //$late_checkin_cond =substr($late_checkin_cond,3);
            //$cond .= " AND $late_checkin_cond";
            //3. dieu kien lay ra nhung reservation room: dayuse, ei, li, lo
            /*
            and ((reservation_room.arrival_time = '05-FEB-2015' and reservation_room.departure_time = '05-FEB-2015') 
                OR
                (reservation_room.early_checkin != 0 AND reservation_room.arrival_time = '05-FEB-2015') 
                OR 
                reservation_room.id = 41  
                OR 
                reservation_room.id = 40  
                OR 
                (reservation_room.departure_time = '05-FEB-2015' and reservation_room.late_checkout != 0))
            */
            
            /** Start: Daund viết lại điều kiện lấy ra: dayuse, ei, li, lo */
            // Lấy ra tất cả phòng có dịch vụ ei, li, lo theo thời gian tìm kiếm
            $extra_service = DB::fetch_all('
                                    SELECT
                                        reservation_room.id,
                                        extra_service.code,
                                        reservation_room.arrival_time,
                                        reservation_room.departure_time
                                    FROM
                                        extra_service_invoice 
                                        inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                                        inner join extra_service_invoice_detail on extra_service_invoice_detail.invoice_id = extra_service_invoice.id
                                        inner join extra_service on extra_service.id = extra_service_invoice_detail.service_id
                                    WHERE 
                                        (extra_service.code = \'LATE_CHECKIN\' or extra_service.code = \'LATE_CHECKOUT\' or extra_service.code = \'EARLY_CHECKIN\')
                                        and extra_service_invoice_detail.in_date = \''.$arrival_date.'\'
            ');
            //System::debug($extra_service);
            $li_date = $li_date + 86400;
            $arrival_date = Date_Time::convert_time_to_ora_date($li_date);
            $extra_service_cond = '';
            $li_cond ='';
            $ei_cond ='';
            $lo_cond ='';
            $cond ='';
            foreach($extra_service as $key => $value)
            {
                $extra_service_cond .= ' OR reservation_room.id = '.$value['id'].' '; 
                if($value['code']=='LATE_CHECKIN')
                {
                    $li_cond .=' AND reservation_room.id = '.$value['id'];
                }else if($value['code'] =='LATE_CHECKOUT')
                {
                    $lo_cond .=' AND reservation_room.id = '.$value['id'];                    
                }else
                {
                    $ei_cond .=' AND reservation_room.id = '.$value['id'];                    
                }                
            }
            if(Url::get('early_late')!='ALL')
            {
                if(Url::get('early_late') == 'E.I')
                    $cond.=$ei_cond!=''?$ei_cond:' AND 1=0 ';
                if(Url::get('early_late') == 'L.O')
                    $cond.=$lo_cond!=''?$lo_cond:' AND 1=0 ';
                if(Url::get('early_late') == 'DAYUSE')
                    $cond.=' AND reservation_room.arrival_time = reservation_room.departure_time AND reservation_room.arrival_time = \''.$day_orc.'\'';
                if(Url::get('early_late') == 'L.I')
                    $cond.=$li_cond!=''?$li_cond:' AND 1=0 ';
            }
            $cond .= ' and (
                        (reservation_room.arrival_time = reservation_room.departure_time AND reservation_room.change_room_to_rr is null AND reservation_room.arrival_time = \''.$day_orc.'\') 
                        OR
                        (reservation_room.early_checkin != 0 AND reservation_room.arrival_time = \''.$day_orc.'\')'
                        .$extra_service_cond.
                        ' OR (reservation_room.departure_time= \''.$day_orc.'\' and reservation_room.late_checkout != 0))';
            if(Url::get('status') && Url::get('status')!='ALL')
            {
                if(Url::get('status') == 'NOT_ASSGIN')
                    $cond.=' AND room.name is null AND reservation_room.status = \'BOOKED\' ';
                else
                {
                	$cond.=' AND reservation_room.status = \''.Url::get('status').'\' ';
                } 
            }
            /** Start: Daund viết lại điều kiện lấy ra: dayuse, ei, li, lo */
            if($portal_id != 'ALL')
            {
                $cond.=' AND reservation.portal_id = \''.$portal_id.'\' '; 
            }
            //6. voi moi reservation room li lo ei dayuse: dem so luong khach voi id la reservation_room_id_ngaydangxet
        	
            $count_traveller = $count_traveller + DB::fetch_all('
					SELECT
                        reservation.id || \'_\'||\''.$day_orc.'\' as reservation_id, 
						reservation_room.id || \'_\'||\''.$day_orc.'\' as id
						,count(reservation_traveller.id) as num
					FROM
                        reservation_room 
                        inner join reservation on reservation.id = reservation_room.reservation_id
                        left join room_level on reservation_room.room_level_id = room_level.id
                        left join room on reservation_room.room_id = room.id
                        left outer join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                        left outer join traveller on reservation_traveller.traveller_id = traveller.id
                        left outer join country on traveller.nationality_id = country.id
                        left outer join tour on reservation.tour_id = tour.id
                        left outer join customer on reservation.customer_id = customer.id
                        left outer join room_status on room_status.reservation_room_id = reservation_room.id AND room_status.in_date=\''.$day_orc.'\'
					WHERE
						reservation_room.status != \'CANCEL\' '.$cond.' 
					GROUP BY 
                        reservation_room.id,
                        reservation.id
                    ORDER BY
                        reservation.id
					');
        //7. lay ra danh sach reservation room tuong ung voi dieu kien tren 
           
        $sql = 'select
                    ROW_NUMBER() OVER (ORDER BY reservation.id ) || \'_\' || \''.$day_orc.'\' as id,
                    reservation_traveller.id as reservation_traveller_id,
                    reservation_room.id || \'_\' || \''.$day_orc.'\' as reservation_room_code,
                    reservation.id as reservation_id,
                    DECODE(
                    room.name,      \'\',\'\',
                                    room.name
                    )  as room_name,
                    room_level.brief_name as brief_name,
                    reservation_room.price,
                    reservation_room.net_price,
                    traveller.first_name || \' \' || traveller.last_name as fullname,
                    country.code_'.Portal::language().' as country_code,
                    NVL(reservation_room.adult,0)as adult, 
                    NVL(reservation_room.child,0) + NVL(reservation_room.child_5,0)as child, 
                    reservation_room.late_checkout,
                    reservation_room.early_checkin,
                    reservation_room.arrival_time, 
                    reservation_room.departure_time, 
                    reservation_room.time_in, 
                    reservation_room.time_out,
                    reservation_room.departure_time - reservation_room.arrival_time as night,
                    DECODE(
                    reservation_room.status,        \'CHECKOUT\',\'CHECKOUTED\',
                                                    room_status.status
                    )  as status,
                    reservation_traveller.id as folio,
                    reservation_traveller.flight_code,
                    reservation_traveller.flight_arrival_time,
                    reservation_traveller.car_note_arrival,
                    reservation.note as reservation_note,
                    DECODE(
                    concat(tour.name, customer.name),           \'\',\'\',
                                                                customer.name, customer.name,
                                                                tour.name, \'(Tour)\' || \' \' || tour.name,
                                                                concat(tour.name, customer.name), \'(Tour)\' || \' \' || tour.name || \'-\' || customer.name,
                                                                \' \'
                    )  as note,
                    traveller.id as traveller_id
                from 
                    reservation_room 
                    inner join reservation on reservation.id = reservation_room.reservation_id
                    left join room_level on reservation_room.room_level_id = room_level.id
                    left join room on reservation_room.room_id = room.id
                    left outer join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                    left outer join traveller on reservation_traveller.traveller_id = traveller.id
                    left outer join country on traveller.nationality_id = country.id
                    left outer join tour on reservation.tour_id = tour.id
                    left outer join customer on reservation.customer_id = customer.id
                    left outer join room_status on room_status.reservation_room_id = reservation_room.id AND room_status.in_date=\''.$day_orc.'\'
                where 
                    reservation_room.status != \'CANCEL\' '.$cond.' 
                    --and reservation_room.change_room_to_rr is null 
                    
                    and room_level.is_virtual = 0
                order by 
                    room_name';
        	//and (reservation_room.change_room_from_rr is null or (reservation_room.change_room_from_rr is not null and from_unixtime(reservation_room.old_arrival_time)=\''.$day_orc.'\'))
            $arr = DB::fetch_all($sql);
            
            foreach($arr as $key=>$item)
    		{
    		    $arr[$key]['room_num'] = 0;
                if($item['net_price'] != 1)
                {
                    $price_origi = $arr[$key]['price'];
                    $arr[$key]['price'] = ($price_origi*5/100 + $price_origi)*10/100 + $price_origi*5/100 + $price_origi;
                }
                //echo $report->items[$key]['price'].'<br>';
    		    $total_adult += $item['adult'];
                $total_child += $item['child'];
                $total_night += $item['night'];
    		    $arr[$key]['late_checkin'] = '';
                $reservation_room_code = explode('_',$item['reservation_room_code']);
                $reservation_room_code = $reservation_room_code[0];
                //$reservation_room_code = $item['reservation_room_code'];
    		    $sql = '
                        select 
                            extra_service_invoice.id,
                            extra_service.code,
                            --extra_service_invoice.late_checkin,
                            --extra_service_invoice.late_checkout,
                            --extra_service_invoice.early_checkin,
                            extra_service_invoice.tax_rate,
                            extra_service_invoice.service_rate,
                            --extra_service_invoice.total_amount as room_price,
                            extra_service_invoice_detail.quantity + nvl(extra_service_invoice_detail.change_quantity,0) as quantity,
                            case
                                when extra_service_invoice.net_price = 0
                                then
                                    round((((extra_service_invoice_detail.quantity + nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity + nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity + nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity + nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)))    
                                else
                                    (extra_service_invoice_detail.quantity + nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price
                                end as room_price
                        from
                        extra_service_invoice
                        inner join extra_service_invoice_detail on extra_service_invoice_detail.invoice_id = extra_service_invoice.id
                        inner join reservation_room ON reservation_room.id=extra_service_invoice.reservation_room_id
                        inner join extra_service on extra_service.id = extra_service_invoice_detail.service_id
                        where 
                        extra_service_invoice.payment_type = \'ROOM\' and
                        (reservation_room.change_room_to_rr is null or (reservation_room.change_room_to_rr is not null and extra_service_invoice.late_checkout is null)) AND
                        extra_service_invoice.reservation_room_id = '.$reservation_room_code;
                
                $extra_price_arr = DB::fetch_all($sql);
                //System::debug($extra_price_arr);
                $arr[$key]['room_num'] = 0;
                $arr[$key]['price'] = 0;
                
                //giap.ln dem so luong cac dich vu cho 1 reservation room 
                $arr[$key]['day_use'] =0;
                //end giap.ln
                if($item['arrival_time']==$item['departure_time'])
                {
                    $arr[$key]['room_num'] = 1;
                    $arr[$key]['price'] = $item['price'];
                }
                //truong hop phong day use trong ngay hien tai 
                if(strtolower(date('d-M-Y',$item['time_in'])) == strtolower(date('d-M-Y',$item['time_out'])) and  strtolower(date('d-M-Y',$item['time_out']))== strtolower($day_orc))
                {
                    $arr[$key]['day_use'] = 1;
                }
                /*if(!empty($extra_price_arr))
                {
                    //neu co su dung dich vu: li lo ei
                    foreach($extra_price_arr as $k => $v)
                    {
                        //neu la ei trong ngay hien tai 
                        if($v['early_checkin'] == 1 && strtolower(date('d-M-Y',$item['time_in'])) == strtolower($day_orc)) 
                        {
                            if(strtolower(date('d-M-Y',$item['time_in']))!=strtolower(date('d-M-Y',$item['time_out'])))
                                $arr[$key]['price'] = $v['room_price'];
                            $arr[$key]['room_num'] += $v['quantity'];       
                        }  
                        if($v['early_checkin'] == 1 && strtolower(date('d-M-Y',$item['time_in'])) != strtolower($day_orc))
                        {
                            $arr[$key]['early_checkin'] ='';
                        }
                        // L.I vÃƒÂ  out luon khong co L.O --> Chi tinh nguyen L.I (out truoc 12h)
                        if($v['late_checkin'] == 1 and strtolower(date('d-M-Y',$item['time_in'])) == strtolower($arrival_date) and strtolower(date('d-M-Y',$item['time_out'])) == strtolower($arrival_date)) 
                        {
                            $arr[$key]['late_checkin'] = 1;
                            $arr[$key]['price'] = $v['room_price'];
                            $arr[$key]['room_num'] = $v['quantity'];      
                        } 
                        //L.I va out luon nhung co L.O --> Tinh ca hai (out sau 12h)
                        if($v['late_checkin'] == 1 and strtolower(date('d-M-Y',$item['time_in'])) == strtolower($arrival_date) and strtolower(date('d-M-Y',$item['time_out'])) == strtolower($arrival_date)) 
                        {
                            $arr[$key]['late_checkin'] = 1;
                            $arr[$key]['price'] = $v['room_price'];
                            $arr[$key]['room_num'] = $v['quantity'];
                            $arr[$key]['late_checkout'] = '';
                            foreach($extra_price_arr as $l => $t)
                            {
                                if($t['late_checkin'] == 1)
                                {
                                    if(!isset($arr[$item['reservation_room_code'].'_'.$item['traveller_id']]))
                                    {
                                        $arr[$item['reservation_room_code'].'_'.$item['traveller_id']] = $arr[$key];
                                        $arr[$item['reservation_room_code'].'_'.$item['traveller_id']]['late_checkin'] = '';
                                        $arr[$item['reservation_room_code'].'_'.$item['traveller_id']]['late_checkout'] = 1;
                                        $arr[$item['reservation_room_code'].'_'.$item['traveller_id']]['reservation_room_code'] = $arr[$item['reservation_room_code'].'_'.$item['traveller_id']]['reservation_room_code'].'_';
                                        $arr[$item['reservation_room_code'].'_'.$item['traveller_id']]['price'] = $v['room_price'];
                                        $arr[$item['reservation_room_code'].'_'.$item['traveller_id']]['room_num'] = $t['quantity'];  
                                        if($arr[$item['reservation_room_code'].'_'.$item['traveller_id']]['room_num'] < 1)
                                        {
                                            $arr[$item['reservation_room_code'].'_'.$item['traveller_id']]['room_num'] = '0'.$arr[$item['reservation_room_code'].'_'.$item['traveller_id']]['room_num'];
                                        }
                                    }

                                    //$count_traveller[$item['reservation_room_code'].'_'] = $count_traveller[$item['reservation_room_code'].'_'.$day_orc];
                                }  
                            }          
                        }
                        //L.I va out vao ngay hom sau = 1 L.I
                        if($v['late_checkin'] == 1 and strtolower(date('d-M-Y',$item['time_in'])) == strtolower($arrival_date) and strtolower(date('d-M-Y',$item['time_out'])) != strtolower($arrival_date)) 
                        {
                            
                            $arr[$key]['late_checkin'] = 1;
                            $arr[$key]['price'] = $v['room_price'];
                            $arr[$key]['room_num'] = $v['quantity'];         
                        }
                        //L.O va den tu ngay hom truoc
                        if($v['late_checkout'] == 1  and strtolower(date('d-M-Y',$item['time_out'])) == strtolower($day_orc)) 
                        {
                            if(strtolower(date('d-M-Y',$item['time_in'])) != strtolower($day_orc))
                            {
                                $arr[$key]['price'] = $v['room_price'];
                            }
                            $arr[$key]['room_num'] += $v['quantity'];
                            
                        }
                        if($v['late_checkout'] == 1 and strtolower(date('d-M-Y',$item['time_out'])) != strtolower($day_orc))
                        {
                             $arr[$key]['late_checkout'] = '';
                        }
                    }
                }*/
                /** Daund: Sửa lại cách gán dữ liệu */
                if(!empty($extra_price_arr))
                {
                    foreach($extra_price_arr as $k => $v)
                    {
                        if($v['code'] == 'EARLY_CHECKIN') 
                        {
                            $arr[$key]['early_checkin'] = 1;
                            $arr[$key]['price'] += $v['room_price'];
                            $arr[$key]['room_num'] += $v['quantity'];       
                        }else if($v['code'] == 'LATE_CHECKIN') 
                        {
                            $arr[$key]['late_checkin'] = 1;
                            $arr[$key]['price'] += $v['room_price'];
                            $arr[$key]['room_num'] += $v['quantity'];       
                        }else if($v['code'] == 'LATE_CHECKOUT') 
                        {
                            $arr[$key]['late_checkout'] = 1;
                            $arr[$key]['price'] += $v['room_price'];
                            $arr[$key]['room_num'] += $v['quantity'];       
                        }                 
                    }                    
                }
                /** Daund: Sửa lại cách gán dữ liệu */
                $reservation_room_code = explode('_',$item['reservation_room_code']);
                $reservation_room_code = $reservation_room_code[0];
                if($reservation_room_code!=$res_id)
                {
                    $arr[$key]['stt'] = $i++;
                    $res_id = $reservation_room_code; 
                }
    		}
    		
            $elements = $elements + $arr;
        }

        $res_ids = array();
        $k =0;
        foreach($elements as $key=>$rows)
        {
        	
        	if($elements[$key]['room_num']==0)
                 $elements[$key]['room_num'] = $rows['late_checkout']/10 + $rows['early_checkin']/10;
            $k++;
            $reservation_room_code = explode('_',$rows['reservation_room_code']);
            $reservation_room_code = $reservation_room_code[0];
            if(in_array($reservation_room_code,$res_ids)==false)
            {
                $res_ids[] = $reservation_room_code;
                $ids[] = $rows['id'];
                $traveller_id[] = $rows['reservation_traveller_id'];
            }
            else
            {
            	//truong hop phong khong co ten khach 
            	if(isset($rows['reservation_traveller_id'])==false)
            	{
            		$j=0;
                    for(;$j<count($res_ids);$j++)
                    {
                        if($reservation_room_code==$res_ids[$j])
                        {
                            break;
                        }
                    }
                    
                    if(isset($rows['room_num']))
                        $elements[$ids[$j]]['room_num'] +=$rows['room_num'];
                    if(isset($rows['early_checkin']))
                        $elements[$ids[$j]]['early_checkin'] +=$rows['early_checkin'];
                    if(isset($rows['late_checkout']))
                        $elements[$ids[$j]]['late_checkout'] +=$rows['late_checkout'];
                        
                    if(isset($rows['day_use']) && $rows['day_use']==1)
                        $elements[$ids[$j]]['day_use'] = 1;
                    if(isset($rows['late_checkin']) && $rows['late_checkin']==1)
                        $elements[$ids[$j]]['late_checkin'] = 1;
                    
                    unset($elements[$key]);
            	}
            	else {//truong hop co ten khach 
	            	if(in_array($rows['reservation_traveller_id'],$traveller_id)==false)
	                {
	                    $ids[] = $rows['id'];
	                    $traveller_id[] =$rows['reservation_traveller_id'];
	                }
	                else
	                {
	                    $j=0;
	                    
	                    for(;$j<count($traveller_id);$j++)
	                    {
	                        if($rows['reservation_traveller_id']==$traveller_id[$j])
	                        {
	                            break;
	                        }
	                    }
	                    
	                    if(isset($rows['room_num']))
	                        $elements[$ids[$j]]['room_num'] +=$rows['room_num'];
	                    if(isset($rows['early_checkin']))
	                        $elements[$ids[$j]]['early_checkin'] +=$rows['early_checkin'];
	                    if(isset($rows['late_checkout']))
	                        $elements[$ids[$j]]['late_checkout'] +=$rows['late_checkout'];
	                        
	                    if(isset($rows['day_use']) && $rows['day_use']==1)
	                        $elements[$ids[$j]]['day_use'] = 1;
	                    if(isset($rows['late_checkin']) && $rows['late_checkin']==1)
	                        $elements[$ids[$j]]['late_checkin'] = 1;
	                    
	                    unset($elements[$key]);
	                }
            	}
                  
            }
            
        }
		//System::debug($elements);
		
        $report->items = $report->items + $elements;
        $_REQUEST['total_adult'] = $total_adult;
        $_REQUEST['total_child'] = $total_child;
        $_REQUEST['total_night'] = $total_night;
		$this->print_all_pages($report,$count_traveller);
    }
    
    function print_all_pages(&$report,$count_traveller)
	{   
        $summary = array(
        	'room_count'=>0,
            'real_room_count'=>0,
            'real_total_money'=>0,
            'total_money'=>0,
            'real_night'=>0,
            'real_num_people'=>0,
            'real_num_child'=>0,
            'total_page'=>1,
            'real_total_page'=>1,
            'line_per_page' =>999,
            'no_of_page' =>999,
            'start_page' =>1,
        );
        $summary['line_per_page'] = URL::get('line_per_page',999);
        
        $count = 0;
        $pages = array();          
        
        //duyet qua tung ban ghi      
        foreach($report->items as $key=>$item)
        {
            $summary['total_money'] += System::calculate_number($item['price']);
            if(isset($report->items[$key]['stt']))
            {
                //echo $report->items[$key]['stt'];
                //Ä�áº¿m sá»‘ khÃ¡ch
            	$summary['room_count']++;
                $count+= 1;
            }
            //count >= so dong tren 1 trang thi reset ve 0 va tang so trang len 1
            if($count>$summary['line_per_page'])
        	{
        		$count = 1;
        		$summary['total_page']++;
        	}
            //In ra báº¯t Ä‘áº§u tá»« trang sá»‘ 1
            $pages[$summary['total_page']][$key] = $item;	
        }
        
        //Náº¿u cÃ³ dá»¯ liá»‡u tá»« cÃ¢u truy váº¥n
        if(sizeof($pages)>0)
        {
            $summary['total_page'] = sizeof($pages);
            //Neu muon xem tu trang bao nhieu
            if(Url::get('start_page')>1)
            {
                $summary['start_page'] = Url::get('start_page');
                //Xoa bo cac phan tu trong mang ma co key (o day la page < start page)
                //for($i = 1; $i< $summary['start_page']; $i++)
                    //unset($pages[$i]);
            }
            //Neu muon xem toi da bao nhieu trang
            if(Url::get('no_of_page'))
            {
                //muon xem bao nhieu trang ?
                $summary['no_of_page'] = Url::get('no_of_page');
                //lay ra trang bat dau dc in (neu muon xem tu trang 5 thÃ¬ se tra ve 5)
                $arr = array_keys($pages);
                if(!empty($arr))
                {
                    $begin = $arr['0'];
                    //trang cuoi cung dc in
                    $end = $begin + $summary['no_of_page'] - 1;
                    //Xoa bo cac phan tu trong mang ma co key (o day la page < end page)
                    //for($i = $summary['total_page']; $i> $end; $i--)
                        //unset($pages[$i]);      
                }

            }
            //Sau khi lá»�c mÃ  cÃ²n dá»¯ liá»‡u
            if(!empty($pages))
            {
                $summary['real_total_page']=count($pages);
                //Sá»‘ trang thá»±c sá»± sau khi lá»�c qua cÃ¡c yÃªu cáº§u
                $total_page = 0;
            	foreach($pages as $page_no=>$page)
            	{
                    //System::debug($page);
                	foreach($page as $key=>$value)
                	{
                	   if(isset($page[$key]['stt']))
                       {
                            //Sá»‘ khÃ¡ch thá»±c sá»± sau khi lá»�c qua cÃ¡c yÃªu cáº§u
                            $summary['real_room_count']++;
                            $summary['real_total_money'] += $page[$key]['price'];
                            $summary['real_night'] += $page[$key]['night'];
                            //neu co khach thi += theo khach, neu khong thi theo adult trong database
                            $summary['real_num_people'] += $count_traveller[$page[$key]['reservation_room_code']]['num']?$count_traveller[$page[$key]['reservation_room_code']]['num']:$page[$key]['adult'];
                            $summary['real_num_child'] += $page[$key]['child'];
                       }
                	}
                    $total_page+=1;
                    //System::debug($summary); 
            	}
                
                //page that su
                $real_page_no = 1;
                //System::debug($pages);
                $total_sammary = array();
                $total_sammary['total_room'] = 0;
                $total_sammary['total_price'] = 0;
                $total_sammary['total_adult'] = 0;
                $total_sammary['total_child'] = 0;
                $total_sammary['total_night'] = 0;
                foreach($pages as $page_no=>$page)
            	{
            	   /** Manh them tinh tong cac trang **/
                   $last_total_sammary = array();
                   $last_total_sammary = $total_sammary;
                   //System::debug($page);
                   $room_name = '';
                   foreach($page as $id_page=>$value_page){
                        if($room_name!=$value_page['room_name'])
                        {
                            $room_name=$value_page['room_name'];
                            $total_sammary['total_room'] += 1;
                            $total_sammary['total_price'] += $value_page['price'];
                            if(!empty($value_page['adult'])){$total_sammary['total_adult'] += $value_page['adult'];}
                            if(!empty($value_page['child'])){$total_sammary['total_child'] += $value_page['child'];}
                            $total_sammary['total_night'] += $value_page['room_num'];
                        }
                   }
                   /** end  Manh **/
                   //System::debug($total_sammary);
            		$this->print_page($page,$page_no,$real_page_no,$summary,$count_traveller,$total_sammary,$last_total_sammary,$total_page);
                    $real_page_no++;
            	}
            }
            else
            {
                $this->prase_layout_default($summary,$count_traveller);
            }
        }
        else
        {
            $this->prase_layout_default($summary,$count_traveller);
        }
	}
    function print_page($items, $page_no, $real_page_no, $summary,$count_traveller,$total_sammary,$last_total_sammary,$total_page)
	{
	   if(User::is_admin())
       {
            //echo '=================';
            //System::debug($items);
       }
       if($page_no>=Url::get('start_page')){
        $this->parse_layout('report',
        	$summary+
        	array(
        		'items'=>$items,
        		'page_no'=>$page_no,
                'total_page'=>$total_page,
                'real_page_no'=>$real_page_no,
        		'day'=>$this->day,
                'to_date'=>$this->to_date,
                'count_traveller'=>$count_traveller,
                'total_sammary'=>$total_sammary,
                'last_total_sammary'=>$last_total_sammary,
                'start_page'=>Url::get('start_page')
        	)+$this->map
		);
       } 
        
	}
    
    function prase_layout_default($summary,$count_traveller)
    {
    	$this->parse_layout('report',
    	$summary+
    		array(
    			'page_no'=>1,
                'real_page_no'=>1,
    			'day'=>$this->day,
                'count_traveller'=>$count_traveller
    		)+$this->map
    	);
    }
}

?>
