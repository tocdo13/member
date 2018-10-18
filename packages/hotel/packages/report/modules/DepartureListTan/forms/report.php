<?php
class ReportDepartureList extends Form
{
    function ReportDepartureList()
    {
        Form::Form('ReportDepartureList');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
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
        /** phong đi trong ngay: 
         * th phong binh thuong co ngay di bang ngay xem bc
         * th doi phong chang c
        **/
        if($room_status = Url::get('room_status'))
        {
            if($room_status == 'ACTUAL_CHECKOUT')
            {
                $cond.=' AND reservation_room.status=\'CHECKOUT\' ';
            } 
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
                    reservation_room.status,
                    reservation_room.id as rr_id,
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
                    reservation_traveller.flight_code_departure,
                    reservation_traveller.flight_departure_time,
                    reservation_traveller.car_note_departure,
                    traveller.first_name || \' \' || traveller.last_name as fullname,
                    country.code_'.Portal::language().' as country_code,
                    DECODE(
                    concat(tour.name, customer.name),\'\',\'\',
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
                where 
                   1=1 
                   '.$cond.'
                   and reservation_room.change_room_to_rr is null
                   and reservation_room.departure_time=\''.$day_orc.'\'
                   and reservation_room.status != \'CANCEL\' and reservation_room.status != \'NOSHOW\'
                   and room_level.is_virtual=0
                order by 
                    reservation.id, reservation_room.id  ';
        $room_and_guest = DB::fetch_all($sql);
        //System::debug($room_and_guest);//exit();
        $items = array();
        $i = 1;
        $r_id = '';
        $this->map['total_room'] = 0;
        $this->map['total_night'] = 0;
        
        foreach($room_and_guest as $k=>$v)
        {
            $night = ($v['night']==0)?1:$v['night'];
            $r_id = $v['reservation_id'];
            if(!isset($items[$v['reservation_id']])) 
            {
                $this->map['total_room'] += 1;
                $this->map['total_night'] += $night;
                $items[$v['reservation_id']]['id'] = $v['reservation_id'];
                $items[$v['reservation_id']]['reservation_note'] = $v['reservation_note'];
                $items[$v['reservation_id']]['note'] = $v['note'];
                $items[$v['reservation_id']]['stt'] = ($r_id=='')?$i:$i++;
                $items[$v['reservation_id']]['count'] = 1;
                $items[$v['reservation_id']]['room'][$v['rr_id']] = array();
                $items[$v['reservation_id']]['room'][$v['rr_id']]['id'] = $v['rr_id'];
                $items[$v['reservation_id']]['room'][$v['rr_id']]['room_name'] = $v['room_name'];
                $items[$v['reservation_id']]['room'][$v['rr_id']]['room_level'] = $v['room_level'];
                $items[$v['reservation_id']]['room'][$v['rr_id']]['status'] = $v['status'];
                $items[$v['reservation_id']]['room'][$v['rr_id']]['time_in_room'] = $v['time_in_room'];
                $items[$v['reservation_id']]['room'][$v['rr_id']]['time_out_room'] = $v['time_out_room'];
                $items[$v['reservation_id']]['room'][$v['rr_id']]['night'] = $night;
                $items[$v['reservation_id']]['room'][$v['rr_id']]['note_room'] = $v['note_room'];
                $items[$v['reservation_id']]['room'][$v['rr_id']]['count'] = 1;
                $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'] = array();
                if($v['r_traveller_id']!='')
                {
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['id'] = $v['r_traveller_id'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['traveller_id'] = $v['traveller_id'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['fullname'] = $v['fullname'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['country_code'] = $v['country_code'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['time_in'] = $v['time_in'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['time_out'] = $v['time_out'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['flight_code_departure'] = $v['flight_code_departure'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['flight_departure_time'] = $v['flight_departure_time'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['car_note_departure'] = $v['car_note_departure'];
                }
                else
                {
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['id'] ='not_traveller';
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['traveller_id'] = '';
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['fullname'] = '';
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['country_code'] = '';
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['time_in'] = '';
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['time_out'] = '';
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['flight_code_departure'] = '';
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['flight_departure_time'] = '';
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['car_note_departure'] = '';
                }
                
            }
            else
            {
                if(!isset($items[$v['reservation_id']]['room'][$v['rr_id']]))
                {
                    $this->map['total_room'] += 1;
                    $this->map['total_night'] += $night;
                    $items[$v['reservation_id']]['count'] += 1;
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['id'] = $v['rr_id'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['room_name'] = $v['room_name'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['room_level'] = $v['room_level'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['status'] = $v['status'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['time_in_room'] = $v['time_in_room'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['time_out_room'] = $v['time_out_room'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['night'] = $night;
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['note_room'] = $v['note_room'];
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['count'] = 1;
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'] = array();
                    if($v['r_traveller_id']!='')
                    {
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['id'] = $v['r_traveller_id'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['traveller_id'] = $v['traveller_id'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['fullname'] = $v['fullname'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['country_code'] = $v['country_code'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['time_in'] = $v['time_in'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['time_out'] = $v['time_out'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['flight_code_departure'] = $v['flight_code_departure'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['flight_departure_time'] = $v['flight_departure_time'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['car_note_departure'] = $v['car_note_departure'];
                    }
                    else
                    {
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['id'] ='not_traveller';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['traveller_id'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['fullname'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['country_code'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['time_in'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['time_out'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['flight_code_departure'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['flight_departure_time'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['car_note_departure'] = '';
                    }
                }
                else
                {
                    $items[$v['reservation_id']]['count'] += 1;
                    $items[$v['reservation_id']]['room'][$v['rr_id']]['count'] += 1;
                    if($v['r_traveller_id']!='')
                    {
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['id'] = $v['r_traveller_id'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['traveller_id'] = $v['traveller_id'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['fullname'] = $v['fullname'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['country_code'] = $v['country_code'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['time_in'] = $v['time_in'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['time_out'] = $v['time_out'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['flight_code_departure'] = $v['flight_code_departure'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['flight_departure_time'] = $v['flight_departure_time'];
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller'][$v['r_traveller_id']]['car_note_departure'] = $v['car_note_departure'];
                    }
                    else
                    {
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['id'] ='not_traveller';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['traveller_id'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['fullname'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['country_code'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['time_in'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['time_out'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['flight_code_departure'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['flight_departure_time'] = '';
                        $items[$v['reservation_id']]['room'][$v['rr_id']]['traveller']['not_traveller']['car_note_departure'] = '';
                    }
                }
            }
        }
        //System::debug($items);
        $this->parse_layout('report',array('items'=>$items,'day'=>$this->day)+$this->map);
    }
}
?>