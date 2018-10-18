<?php
class ReportArrivalList extends Form
{
    function ReportArrivalList()
    {
        Form::Form('ReportArrivalList');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_css('skins/default/report.css');
        $this->link_css('packages/core/skins/default/css/global.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');       
    }
    
    function draw()
    {
        require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';
        $cond ='';
        if(Url::get('date'))
        {
            $this->day = Url::get('date');
        }
        else
        {
            $this->day = date('d/m/Y');
            $_REQUEST['date'] = $this->day;     
        }
        $day_orc = Date_Time::to_orc_date($this->day); 
        $date = Date_Time::to_time(Url::get('date'));
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
        
        if($portal_id != 'ALL')
        {
            $cond.=' AND reservation.portal_id = \''.$portal_id.'\' '; 
        }
        $customers = DB::fetch_all('SELECT ID,NAME FROM CUSTOMER');
		$this->map['customer_id_list'] = array(''=>'---') + String::get_list($customers);
        $cond .=  (Url::get('customer_id')?' AND reservation.customer_id=\''.Url::sget('customer_id').'\'':'').'';
        $this->map['status_list'] = array(''=>Portal::language('all'),'NOT_ASSIGN'=>'NOT_ASSIGN','BOOKED'=>'BOOKED','CHECKIN'=>'CHECKIN','CHECKOUT'=>'CHECKOUT');
        if($status = Url::get('status'))
        {
            if($status == 'NOT_ASSIGN')
                $cond.=' AND reservation_room.room_id is null AND reservation_room.status = \'BOOKED\' ';
            else
                $cond.=' AND reservation_room.status = \''.Url::get('status').'\' ';
        } 
        /** phong den trong ngay: dieu kien lay tat ca cac phong
        th1: lay nhung phong binh thuong co ngay den = ngay xem
        th2: neu la doi phong 
            lay phong chang dau: neu có arrival_time = ngay xem va ngay di != ngay xem
            lay phong chang giua: neu có old_arr_time = ngay xem va ngay di != ngay xem
            lay phong chang cuoi: neu có old_arr_time = ngay xem
        Chu y: phong doi lay phong hien tai khach o tại thoi diem xem bc
        **/
        $cond_new = '
            and reservation_room.time_in >= \''.$date.'\'  and reservation_room.time_in <= \''.($date+86399).'\'
                   and 
                   (
                        --th phong bt
                        (
                            (
                                --th phong binh thuong
                                (
                                    reservation_room.change_room_from_rr is null 
                                    and reservation_room.change_room_to_rr is null 
                                )
                                or
                                --th chang dau
                                (
                                    reservation_room.change_room_from_rr is null 
                                    and reservation_room.change_room_to_rr is not null 
                                    and reservation_room.departure_time!=\''.$day_orc.'\'
                                )
                            )
                        )
                        or
                        -- th doi phong
                        (
                            reservation_room.old_arrival_time >= \''.$date.'\'  and reservation_room.old_arrival_time <= \''.($date+86399).'\'
                            and 
                            (
                                --th chang giua
                                (
                                    reservation_room.change_room_from_rr is not null 
                                    and reservation_room.change_room_to_rr is not null 
                                    and reservation_room.departure_time!=\''.$day_orc.'\'
                                )
                                or
                                --th chang cuoi
                                (
                                    reservation_room.change_room_from_rr is not null 
                                    and reservation_room.change_room_to_rr is null 
                                )
                            )
                        )
                   )
        ';
        /**
            Dieu Kien lay phong dayused
            -phong binh thuong co arrival_time = departure_time va arrival_time = ngay xem
            -doi phong chang cuoi co old_arrival_time = departure_time va old_arrival_time = ngay xem
         **/
        $cond_new_2 = '
            and reservation_room.arrival_time = \''.$day_orc.'\'  
            and 
            (
                --th phong bt
                (
                    reservation_room.arrival_time = reservation_room.departure_time
                    and reservation_room.change_room_from_rr is null 
                    and reservation_room.change_room_to_rr is null 
                )
                or
                -- th doi phong chang cuoi
                (
                    reservation_room.old_arrival_time >= \''.$date.'\'  and reservation_room.old_arrival_time <= \''.($date+86399).'\'
                    and from_unixtime(reservation_room.old_arrival_time) = reservation_room.departure_time
                    and reservation_room.change_room_from_rr is not null 
                    and reservation_room.change_room_to_rr is null 
                )
            )
            ';
        if($room_status = Url::get('room_status'))
        {
            if($room_status == 'DAYUSE')
            {
                $cond.=$cond_new_2;
            }
            else
            {
                if($room_status == 'ACTUAL_CHECKIN')
                {
                    $cond.=' AND (reservation_room.status=\'CHECKIN\' or reservation_room.status=\'CHECKOUT\') ';
                }  
                $cond.=$cond_new;
            }
        }
        else
        {
            $cond.=$cond_new;
        }
        $sql = 'select
                    reservation_room.id || \'_\' || reservation_traveller.id as id,
                    reservation.id as reservation_id,
                    reservation.booking_code,
                    reservation.booker,
                    reservation.payment_type1,
                    reservation.note as reservation_note,
                    room.name as room_name,
                    room.id as room_id,
                    room_level.brief_name as room_level,
                    reservation_room.price,
                    reservation_room.id as rr_id,
                    reservation_room.adult, 
                    NVL(reservation_room.child,0) + NVL(reservation_room.child_5,0) as child, 
                    reservation_room.arrival_time, reservation_room.departure_time, 
                    reservation_room.time_in as time_in_room,
                    reservation_room.time_out as time_out_room,
                    reservation_traveller.arrival_time as time_in, reservation_traveller.departure_time as time_out,
                    reservation_room.departure_time - reservation_room.arrival_time as night,
                    reservation_room.note as note_room,
                    reservation_traveller.id as r_traveller_id,
                    reservation_traveller.flight_code,
                    reservation_traveller.flight_arrival_time,
                    reservation_traveller.car_note_arrival,
                    traveller.first_name || \' \' || traveller.last_name as fullname,
                    country.code_'.Portal::language().' as country_code,
                    DECODE(
                    concat(tour.name, customer.name),\'\',\'\',
                        customer.name, customer.name,
                        tour.name, \'(Tour)\' || \' \' || tour.name,
                        concat(tour.name, customer.name), \'(Tour)\' || \' \' || tour.name || \'-\' || customer.name,
                        \' \'
                    )  as note,
                    guest_type.name as guest_level,
                    traveller.id as traveller_id
                from 
                    reservation_room 
                    inner join reservation on reservation.id = reservation_room.reservation_id
                    left join room_level on reservation_room.room_level_id = room_level.id
                    left join room on reservation_room.room_id = room.id
                    left outer join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                    left outer join traveller on reservation_traveller.traveller_id = traveller.id
                    left outer join guest_type on traveller.traveller_level_id = guest_type.id
                    left outer join country on traveller.nationality_id = country.id
                    left outer join tour on reservation.tour_id = tour.id
                    left outer join customer on reservation.customer_id = customer.id
                where 
                   1=1 
                   '.$cond.'
                   and reservation_room.status != \'CANCEL\' and reservation_room.status != \'NOSHOW\'
                   and room_level.is_virtual=0
                order by 
                    reservation.id, reservation_room.id  ';
        $room_and_guest = DB::fetch_all($sql);
        //System::debug($sql);//exit();
        $items = array();
        $i = 1;
        $r_id = '';
        $this->map['total_room'] = 0;
        $this->map['total_child'] = 0;
        $this->map['total_adult'] = 0;
        $this->map['total_night'] = 0;
        $cond_es_invoid = '';
        $house_status_arr = DB::fetch_all('select room_id as id, house_status from room_status where house_status is not null and in_date=\''.$day_orc.'\'');
        $sql = 'SELECT 
                    reservation_traveller.id as id 
                    ,traveller.first_name || \' \' || traveller.last_name as fullname
                    ,trim(traveller.id) as traveller_id
                FROM
                    traveller inner join reservation_traveller on traveller.id = reservation_traveller.traveller_id 
                    left join reservation_room on reservation_traveller.reservation_room_id = reservation_room.id
                
                WHERE 
                    reservation_traveller.reservation_room_id !=0 and reservation_room.arrival_time <= \''.$day_orc.'\'
                GROUP BY 
                    traveller.id ,traveller.first_name,traveller.last_name,reservation_traveller.id
                ORDER BY 
                    traveller.id';
                    
        $count_reservation = DB::fetch_all($sql); 
        $traveller_id = array();
        foreach($count_reservation as $key => $value)
        {
            $traveller_id[$value['traveller_id']][$key] = $value;
            unset($count_reservation[$key]);
        }  
        foreach($room_and_guest as $k=>$v)
        {
            $night = ($v['night']==0)?1:$v['night'];
            $adult = ($v['adult']==0)?1:$v['adult'];
            $r_id = $v['reservation_id'];
            if($cond_es_invoid=='')
            {
                $cond_es_invoid = $v['rr_id'];
            }
            else
            {
                $cond_es_invoid .= ','.$v['rr_id'];
            }
            $house_status = '';
            if(isset($house_status_arr[$v['room_id']]))
            {
                $house_status = (($house_status_arr[$v['room_id']]['house_status']) != '')?$house_status_arr[$v['room_id']]['house_status']:'READY';
            }
            
            if(!isset($items[$v['reservation_id']])) 
            {
                $this->map['total_room'] += 1;
                $this->map['total_child'] += $v['child'];
                $this->map['total_adult'] += $adult;
                $this->map['total_night'] += $night;
                $items[$v['reservation_id']]['id'] = $v['reservation_id'];
                $items[$v['reservation_id']]['stt'] = ($r_id=='')?$i:$i++;
                $items[$v['reservation_id']]['count'] = 1;
                $items[$v['reservation_id']]['room'][$v['rr_id']] = array();
                $items[$v['reservation_id']]['room'][$v['rr_id']]['id'] = $v['rr_id'];
                $items[$v['reservation_id']]['room'][$v['rr_id']]['reservation_note'] = $v['reservation_note'];
                $items[$v['reservation_id']]['room'][$v['rr_id']]['room_name'] = $v['room_name'];
                $items[$v['reservation_id']]['room'][$v['rr_id']]['room_level'] = $v['room_level'];
                $items[$v['reservation_id']]['room'][$v['rr_id']]['extrabed'] = 0;
                $items[$v['reservation_id']]['room'][$v['rr_id']]['baby_cot'] = 0;
                $items[$v['reservation_id']]['room'][$v['rr_id']]['price'] = $v['price'];
                $items[$v['reservation_id']]['room'][$v['rr_id']]['house_status'] = $house_status;
                if($v['room_id'] != '' && $house_status == '')
                {
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['house_status'] = 'READY';
                }else if($v['room_id'] == '')
                {
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['house_status'] = '';
                }
                $items[$v['reservation_id']]['room'][$v['rr_id']]['note'] = $v['note'];
                $items[$v['reservation_id']]['room'][$v['rr_id']]['adult'] = $adult;
                $items[$v['reservation_id']]['room'][$v['rr_id']]['child'] = $v['child'];
                $items[$v['reservation_id']]['room'][$v['rr_id']]['time_in_room'] = $v['time_in_room'];
                $items[$v['reservation_id']]['room'][$v['rr_id']]['time_out_room'] = $v['time_out_room'];
                $items[$v['reservation_id']]['room'][$v['rr_id']]['night'] = $night;
                $items[$v['reservation_id']]['room'][$v['rr_id']]['note_room'] = $v['note_room'];
                $items[$v['reservation_id']]['room'][$v['rr_id']]['booking_code'] = $v['booking_code'];
                $items[$v['reservation_id']]['room'][$v['rr_id']]['booker'] = $v['booker'];
                $items[$v['reservation_id']]['room'][$v['rr_id']]['payment_type'] = $v['payment_type1'];
                $items[$v['reservation_id']]['room'][$v['rr_id']]['count'] = 1;
                $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'] = array();
                if($v['r_traveller_id']!='')
                {
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['id'] = $v['r_traveller_id'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['guest_level'] = $v['guest_level'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['traveller_id'] = $v['traveller_id'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['fullname'] = $v['fullname'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['country_code'] = $v['country_code'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['time_in'] = $v['time_in'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['time_out'] = $v['time_out'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['flight_code'] = $v['flight_code'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['flight_arrival_time'] = $v['flight_arrival_time'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['car_note_arrival'] = $v['car_note_arrival'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['count_reservation'] = '';
                }
                else
                {
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['id'] ='not_traveller';
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['traveller_id'] = '';
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['fullname'] = '';
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['country_code'] = '';
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['time_in'] = '';
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['time_out'] = '';
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['flight_code'] = '';
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['guest_level'] = '';
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['flight_arrival_time'] = '';
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['car_note_arrival'] = '';
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['count_reservation'] = '';
                }
                if(trim($v['traveller_id']) !='')
                {
                    if(isset($traveller_id[trim($v['traveller_id'])]))
                    {
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['count_reservation'] = '('.count($traveller_id[$v['traveller_id']]).')';
                    }
                }  
            }
            else
            {
                if(!isset($items[$v['reservation_id']]['room'][$v['rr_id']]))
                {
                    $this->map['total_room'] += 1;
                    $this->map['total_child'] += $v['child'];
                    $this->map['total_adult'] += $adult;
                    $this->map['total_night'] += $night;
                    $items[$v['reservation_id']]['count'] += 1;
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['id'] = $v['rr_id'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['reservation_note'] = $v['reservation_note'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['room_name'] = $v['room_name'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['room_level'] = $v['room_level'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['extrabed'] = 0;
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['baby_cot'] = 0;
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['price'] = $v['price'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['house_status'] = $house_status;
                    if($v['room_id'] != '' && $house_status == '')
                    {
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['house_status'] = 'READY';
                    }else if($v['room_id'] == '')
                    {
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['house_status'] = '';
                    }
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['note'] = $v['note'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['adult'] = $adult;
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['child'] = $v['child'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['time_in_room'] = $v['time_in_room'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['time_out_room'] = $v['time_out_room'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['night'] = $night;
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['note_room'] = $v['note_room'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['booking_code'] = $v['booking_code'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['booker'] = $v['booker'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['payment_type'] = $v['payment_type1'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['count'] = 1;
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'] = array();
                    if($v['r_traveller_id']!='')
                    {
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['id'] = $v['r_traveller_id'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['guest_level'] = $v['guest_level'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['traveller_id'] = $v['traveller_id'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['fullname'] = $v['fullname'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['country_code'] = $v['country_code'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['time_in'] = $v['time_in'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['time_out'] = $v['time_out'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['flight_code'] = $v['flight_code'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['flight_arrival_time'] = $v['flight_arrival_time'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['car_note_arrival'] = $v['car_note_arrival'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['count_reservation'] = '';
                    }
                    else
                    {
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['id'] ='not_traveller';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['traveller_id'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['fullname'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['country_code'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['time_in'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['time_out'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['flight_code'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['guest_level'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['flight_arrival_time'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['car_note_arrival'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['count_reservation'] = '';
                    }
                }
                else
                {
                    $items[$v['reservation_id']]['count'] += 1;
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['count'] += 1;
                    if($v['r_traveller_id']!='')
                    {
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['id'] = $v['r_traveller_id'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['guest_level'] = $v['guest_level'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['traveller_id'] = $v['traveller_id'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['fullname'] = $v['fullname'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['country_code'] = $v['country_code'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['time_in'] = $v['time_in'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['time_out'] = $v['time_out'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['flight_code'] = $v['flight_code'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['flight_arrival_time'] = $v['flight_arrival_time'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['car_note_arrival'] = $v['car_note_arrival'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['count_reservation'] = '';
                    }
                    else
                    {
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['id'] ='not_traveller';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['traveller_id'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['guest_level'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['fullname'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['country_code'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['time_in'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['time_out'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['flight_code'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['flight_arrival_time'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['car_note_arrival'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['count_reservation'] = '';
                    }
                }
                if(trim($v['traveller_id']) !='')
                {
                    if(isset($traveller_id[trim($v['traveller_id'])]))
                    {
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['count_reservation'] = '('.count($traveller_id[$v['traveller_id']]).')';
                    }
                }  
            }
        }
        $cond_es = '';
        if($cond_es_invoid!='')
        {
            $cond_es = 'and extra_service_invoice.reservation_room_id in ('.$cond_es_invoid.')';
        }
        $sql = '
            select 
                extra_service_invoice_detail.id|| \'_\' ||extra_service_invoice.reservation_room_id || \'_\' || extra_service.code as id,
                extra_service_invoice.reservation_room_id,
                extra_service_invoice_detail.id as detail_id,
                reservation_room.reservation_id,
                extra_service.code,
                sum(extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0)) as quantity
            from extra_service_invoice_detail
                inner join extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id
                inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                inner join reservation_room on extra_service_invoice.reservation_room_id = reservation_room.id
            where             
            (extra_service.code = \'EXTRA_BED\' or extra_service.code = \'BABY_COT\')
            '.$cond_es.'
            group by 
            extra_service_invoice_detail.id ,
            extra_service_invoice.reservation_room_id,
            reservation_room.reservation_id,
            extra_service.code
        ';
        $es_arr = DB::fetch_all($sql);
        foreach($es_arr as $key=>$value){
            if(isset($items[$value['reservation_id']]['room'][$value['reservation_room_id']]))
            {
                if($value['code'] == 'EXTRA_BED' and $value['quantity']>0)
                {
                    $items[$value['reservation_id']]['room'][$value['reservation_room_id']]['extrabed']+=$value['quantity'];
                }
                if($value['code'] == 'BABY_COT' and $value['quantity']>0)
                {
                    $items[$value['reservation_id']]['room'][$value['reservation_room_id']]['baby_cot']= 1;
                }
            }
        }
        //System::debug($items);
        $this->parse_layout('report',array('items'=>$items,'day'=>$this->day)+$this->map);
    }
}

?>
