<?php
class BookingConfirmForm extends Form
{
	function BookingConfirmForm()
	{
		Form::Form('BookingConfirmForm');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
    function on_submit()
	{
        $data = array();
        $data['RESERVATION_ID'] = Url::get('id');                        
        $data['BOOK_CONFIRM_CREATE_DATE'] = time();
        $data['SPECIAL_REQUEST'] = $_REQUEST['special_request'];
        $data['RESERVATION_DEPARTMENT'] = Url::get('reservation_department');
        $data['TEL_BOOKING_CF'] = Url::get('tel_dn');
        $data['DUE_DATE_DP'] = Url::get('due_date_dp_ip');
        $data['DUE_DATE_DPR'] = Url::get('due_date_dpr_ip');
        $data['DUE_DATE_BL'] = Url::get('due_date_bl_ip');
        $data['TOTAL_ADULT'] = Url::get('total_adult_ip');
        $data['TOTAL_CHILD'] = Url::get('total_children_ip');
        $data['CANCEL_LINE_1'] = Url::get('cancel_line_1');
        $data['CANCEL_LINE_2'] = Url::get('cancel_line_2');
        $data['CANCEL_LINE_3'] = Url::get('cancel_line_3');
        $data['CANCEL_LINE_4'] = Url::get('cancel_line_4');       
	    if(DB::exists('select reservation_id from booking_confirm where reservation_id='.Url::get('id')))
        {                   
            DB::update('booking_confirm', $data, 'reservation_id ='.Url::get('id'));            
        }
        else
        {                  
            DB::insert('booking_confirm', $data);            
        }  
    }
	function draw()
	{	   
        $this->map = array();
        $user_data = Session::get('user_data');
        // lay thong tin chung cua dat phong
        $reservation = DB::fetch('
                                SELECT 
                                    reservation.id as recode,
                                    reservation.user_id,
                                    party.full_name as user_name,
                                    reservation.booker,
                                    reservation.phone_booker,
                                    reservation.email_booker,
                                    reservation.time as create_booking,
                                    NVL(reservation.deposit,0) as deposit_group,
                                    reservation.cut_of_date,
                                    customer.name as customer_name,
                                    customer.address as customer_address,
                                    booking_confirm.special_request,
                                    booking_confirm.reservation_department,
                                    booking_confirm.tel_booking_cf, 
                                    booking_confirm.due_date_dp,
                                    booking_confirm.due_date_dpr,
                                    booking_confirm.due_date_bl,
                                    booking_confirm.total_adult as adult_bc,
                                    booking_confirm.total_child as child_bc,
                                    booking_confirm.cancel_line_1,
                                    booking_confirm.cancel_line_2,
                                    booking_confirm.cancel_line_3,
                                    booking_confirm.cancel_line_4                                                                       
                                FROM
                                    reservation
                                    inner join customer on customer.id=reservation.customer_id
                                    inner join party on party.user_id=reservation.user_id
                                    left join booking_confirm on booking_confirm.reservation_id = reservation.id                                     
                                WHERE
                                    reservation.id='.Url::get('id').'
                                ');
        $this->map = $reservation;
        //System::debug($reservation);
        // lay thong tin cua tung phong
        $this->map += DB::fetch("select count(reservation_room.id) as total_room from reservation_room inner join reservation on reservation_room.reservation_id = reservation.id where reservation.id =".Url::get('id')." AND reservation_room.status != 'CANCEL' AND reservation_room.change_room_to_rr is null");
        $room_status = DB::fetch_all("
                            SELECT
                                room_status.id,
                                room_status.change_price,
                                TO_CHAR(room_status.in_date,'DD/MM/YYYY') as in_date,
                                room_status.reservation_id,
                                reservation_room.id as reservation_room_id,
                                TO_CHAR(reservation_room.arrival_time,'DD/MM/YYYY') as arrival_time,
                                TO_CHAR(reservation_room.departure_time,'DD/MM/YYYY') as departure_time,
                                room_level.name as room_level_name,
                                room_level.id as room_level_id,
                                NVL(reservation_room.adult,0) as adult,
                                NVL(reservation_room.child,0) +NVL(reservation_room.child_5,0) as child,-- trung +them thang chil_5 
                                reservation_room.foc,
                                NVL(reservation_room.deposit,0) as deposit
                            FROM
                                room_status
                                inner join reservation_room on reservation_room.id=room_status.reservation_room_id
                                inner join reservation on reservation_room.reservation_id=reservation.id
                                inner join room_level on reservation_room.room_level_id=room_level.id
                            WHERE
                                reservation.id=".Url::get('id')."
                                and reservation_room.status!='CANCEL'
                                and reservation_room.status!='NOSHOW'
                                and ( (room_status.in_date != reservation_room.departure_time and reservation_room.arrival_time != reservation_room.departure_time) OR (room_status.in_date = reservation_room.departure_time and reservation_room.arrival_time = reservation_room.departure_time) )
                            ORDER BY
                                reservation_room.id,room_status.in_date
                            ");
        //System::debug($room_status);
        // lay ra dat coc cua tung phong
        $deposit = DB::fetch_all("
            SELECT
                reservation_room.id,
                NVL(reservation_room.deposit,0) as deposit
            FROM
                reservation_room
                inner join reservation on reservation_room.reservation_id=reservation.id
            WHERE
                reservation.id=".Url::get('id')."
                and reservation_room.status!='CANCEL'
                and reservation_room.status!='NOSHOW'
            ORDER BY
                reservation_room.id
        ");
        $stt = 0; 
        $key_change = 0;
        $this->map['total'] = 0;
        $this->map['total_deopsit'] = 0;
        $this->map['balance'] = 0;
        $this->map['total_adult'] = 0;
        $this->map['total_child'] = 0;
        // tinh tong tien dat coc cua recode
        foreach($deposit as $key => $value)
        {
            $this->map['total_deopsit'] += $value['deposit'];
        }
        // gom theo luoc do gia cua tung phong truoc
        $room_status_date_price = array();
        foreach($room_status as $key=>$value)
        {
            if($value['foc'] != '')
            {
                if($key_change!=$value['reservation_room_id'].'_'.$value['change_price'])
                {
                    $key_change = $value['reservation_room_id'].'_'.$value['change_price'];
                    $stt++;
                    $room_status_date_price[$stt]['id'] = $stt;
                    $room_status_date_price[$stt]['reservation_room_id'] = $value['reservation_room_id'];
                    $room_status_date_price[$stt]['change_price'] = $value['change_price'];
                    $room_status_date_price[$stt]['room_level_id'] = $value['room_level_id'];
                    $room_status_date_price[$stt]['room_level_name'] = $value['room_level_name'];
                    $room_status_date_price[$stt]['from_date'] = $value['in_date'];
                    $room_status_date_price[$stt]['to_date'] = $value['in_date'];
                    $room_status_date_price[$stt]['count_date'] = 0;
                    $room_status_date_price[$stt]['adult'] = $value['adult'];
                    $room_status_date_price[$stt]['child'] = $value['child'];
                    $room_status_date_price[$stt]['nights'] = 0;
                    $room_status_date_price[$stt]['foc'] = $value['foc'];
                    $room_status_date_price[$stt]['check_room'] = 'Z_FOC';
                }
                if($value['arrival_time']!=$value['departure_time'])
                {
                    $room_status_date_price[$stt]['to_date'] = date('d/m/Y',(Date_Time::to_time($value['in_date'])+(24*3600)));
                }
                $room_status_date_price[$stt]['count_date']++;
                $room_status_date_price[$stt]['nights']++;
            }else
            {
                if($key_change!=$value['reservation_room_id'].'_'.$value['change_price'])
                {
                    $key_change = $value['reservation_room_id'].'_'.$value['change_price'];
                    $stt++;
                    $room_status_date_price[$stt]['id'] = $stt;
                    $room_status_date_price[$stt]['reservation_room_id'] = $value['reservation_room_id'];
                    $room_status_date_price[$stt]['change_price'] = $value['change_price'];
                    $room_status_date_price[$stt]['room_level_id'] = $value['room_level_id'];
                    $room_status_date_price[$stt]['room_level_name'] = $value['room_level_name'];
                    $room_status_date_price[$stt]['from_date'] = $value['in_date'];
                    $room_status_date_price[$stt]['to_date'] = $value['in_date'];
                    $room_status_date_price[$stt]['count_date'] = 0;
                    $room_status_date_price[$stt]['adult'] = $value['adult'];
                    $room_status_date_price[$stt]['child'] = $value['child'];
                    $room_status_date_price[$stt]['nights'] = 0;
                    $room_status_date_price[$stt]['foc'] = $value['foc'];
                    $room_status_date_price[$stt]['check_room'] = 'NOT_FOC';
                }
                if($value['arrival_time']!=$value['departure_time'])
                {
                    $room_status_date_price[$stt]['to_date'] = date('d/m/Y',(Date_Time::to_time($value['in_date'])+(24*3600)));
                }
                $room_status_date_price[$stt]['count_date']++;
                $room_status_date_price[$stt]['nights']++;
            }
        }
        // gom theo hang phong giong nhau
        //System::debug($room_status_date_price);
        $room_level = array();
        $check_rr_id = 0;
        foreach($room_status_date_price as $key=>$value)
        {
            $key_change = 'ROOM_'.$value['check_room'].'_'.$value['room_level_id'].'_'.$value['from_date'].'_'.$value['to_date'].'_'.$value['change_price'];
            if(!isset($room_level[$key_change]))
            {
                $room_level[$key_change]['id'] = $key_change;
                if($value['check_room'] == 'NOT_FOC')
                {
                    $room_level[$key_change]['name'] = $value['room_level_name']; 
                    $room_level[$key_change]['total'] = $value['count_date']*$value['change_price'];                   
                }else
                {
                    if(Url::get('type'))
                    {
                        $room_level[$key_change]['name'] = $value['room_level_name'].' (Miễn phí)';                        
                    }else
                    {
                        $room_level[$key_change]['name'] = $value['room_level_name'].' (FOC)';
                    }
                    $room_level[$key_change]['total'] = 0;
                }
                $room_level[$key_change]['from_date'] = $value['from_date'];
                $room_level[$key_change]['to_date'] = $value['to_date'];
                $room_level[$key_change]['price'] = $value['change_price'];
                $room_level[$key_change]['adult'] = $value['adult'];
                $room_level[$key_change]['child'] = $value['child'];
                $room_level[$key_change]['nights'] = $value['nights'];
                $room_level[$key_change]['quantity'] = 1;
                $room_level[$key_change]['type'] = 'ROOM';
                $room_level[$key_change]['foc'] = $value['foc'];                 
            }
            else
            {
            
                $room_level[$key_change]['adult'] += $value['adult'];
                $room_level[$key_change]['child'] += $value['child'];
                if($value['check_room'] == 'NOT_FOC')
                {
                    $room_level[$key_change]['total'] += $value['count_date']*$value['change_price'];                  
                }else
                {
                    $room_level[$key_change]['total'] += 0;
                }
                $room_level[$key_change]['quantity'] += 1;
            }
            if($check_rr_id != $value['reservation_room_id'])
            {
                $this->map['total_adult'] += $value['adult'];
                $this->map['total_child'] += $value['child'];
            }
            //$this->map['total_deopsit'] += $value['deposit'];
            if($value['check_room'] == 'NOT_FOC')
            {
                $this->map['total'] += $value['count_date']*$value['change_price'];                 
            }
            $check_rr_id = $value['reservation_room_id'];
            // Bỏ room đổi phòng trong ngày.
            if($value['from_date'] == $value['to_date'] && $value['count_date']*$value['change_price'] ==0)
            {
                unset($room_level[$key_change]);
            }
        }
        //System::debug($room_level);        
        // lay extra service
        $extra_service = DB::fetch_all('
                                 SELECT
                                    extra_service_invoice_table.id,
                                    extra_service_invoice_detail.invoice_id,
                                    extra_service_invoice_detail.price,
                                    TO_CHAR(extra_service_invoice_detail.in_date,\'DD/MM/YYYY\') as in_date,
                                    extra_service_invoice_detail.quantity,
                                    NVL(extra_service_invoice_detail.change_quantity,0) as change_quantity,
                                    extra_service_invoice.total_amount,
                                    extra_service_invoice.reservation_room_id as rr_id,
                                    extra_service_invoice.net_price,
                                    NVL(extra_service_invoice.use_extra_bed,0) as use_extra_bed,
                                    NVL(extra_service_invoice.use_baby_cot,0) as use_baby_cot,
                                    extra_service.id as extra_service_id,
                                    extra_service.code as extra_service_code,
                                    extra_service.name as extra_service_name,
                                    reservation_room.tax_rate,
                                    reservation_room.service_rate,
                                    TO_CHAR(extra_service_invoice_table.from_date,\'DD/MM/YYYY\') as from_date,
                                    TO_CHAR(extra_service_invoice_table.to_date,\'DD/MM/YYYY\') as to_date
                                 FROM
                                    extra_service_invoice_table
                                    inner join extra_service_invoice_detail on extra_service_invoice_detail.table_id = extra_service_invoice_table.id
                                    inner join extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id
                                    inner join extra_service on extra_service.id = extra_service_invoice_detail.service_id
                                    inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                                    inner join reservation on reservation.id = reservation_room.reservation_id
                                 WHERE
                                    reservation.id='.Url::get('id').'
                                    and reservation_room.status <>\'CANCEL\'
                                    and reservation_room.status <>\'NOSHOW\'
                                 ORDER BY
                                    extra_service_invoice_detail.invoice_id ASC,
                                    extra_service_invoice_detail.in_date ASC                            
                                    
        ');
        //System::debug($extra_service);
        // gom dvmr theo loai, gia, thoi gian su dung
        $extra_service_group = array();
        foreach($extra_service as $key => $value)
        {
            $id_service = 'SERVICE_' . '_' . $value['extra_service_code'] . '_' . $value['price'] . '_' . $value['from_date'] . '_' . $value['to_date'];
            if(!isset($extra_service_group[$id_service]))
            {
                $extra_service_group[$id_service]['id'] = $id_service;
                $extra_service_group[$id_service]['extra_service_id'] = $value['extra_service_id'];
                $extra_service_group[$id_service]['extra_service_code'] = $value['extra_service_code'];
                $extra_service_group[$id_service]['name'] = $value['extra_service_name'];
                $extra_service_group[$id_service]['quantity'] = $value['quantity'] + $value['change_quantity'];
                $extra_service_group[$id_service]['from_date'] = $value['from_date'];
                $extra_service_group[$id_service]['to_date'] = $value['to_date'];
                if($value['net_price'] == '0')
                {
                    $extra_service_group[$id_service]['price'] = ($value['price']/100*(100+$value['service_rate']))/100*(100+$value['tax_rate']);                     
                }else
                {
                    $extra_service_group[$id_service]['price'] = $value['price'];
                }
                $extra_service_group[$id_service]['total'] = 0; 
                $extra_service_group[$id_service]['nights'] = ((Date_Time::to_time($value['to_date']) + 86400) - Date_Time::to_time($value['from_date'])) / 86400;
                $extra_service_group[$id_service]['tax_rate'] = $value['tax_rate'];
                $extra_service_group[$id_service]['service_rate'] = $value['service_rate']; 
                $extra_service_group[$id_service]['type'] = 'EXTRA';
            }else
            {
                $extra_service_group[$id_service]['quantity'] += ($value['quantity'] + $value['change_quantity']);           
            }            
        }
        foreach($extra_service_group as $key => $value)
        {
            $extra_service_group[$key]['total'] = $value['price']*$value['quantity']*$value['nights'];
            if($value['quantity'] == 0)
            {
                unset($extra_service_group[$key]);
            }
            $this->map['total'] +=$value['price']*$value['quantity']*$value['nights'];           
        }
        //System::debug($extra_service_group);
        $this->map['balance'] = $this->map['total'] - $this->map['deposit_group'] - $this->map['total_deopsit'];
        $reservation_department = '';
        $reservation_department = DB::fetch('
                                            SELECT
                                                department.id,
                                                department.name_'.Portal::language().' as name
                                            FROM
                                                department
                                                inner join portal_department on department.code = portal_department.department_code
                                                inner join account on account.portal_department_id = portal_department.id
                                                inner join party on party.user_id = account.id
                                            WHERE
                                                account.id =\''.Session::get('user_id').'\'
        ','name');
        $this->map['department'] = (isset($reservation['reservation_department']) and $reservation['reservation_department']!='')?$reservation['reservation_department']:$reservation_department;
        ksort($room_level);//ham ksort: sap sep key mang
        $items = $room_level+$extra_service_group;
        $this->map['items'] = $items;
        //if(User::id()=='developer14')
        //{
            //System::debug($this->map['items']);            
        //}
        //System::debug($this->map['items']);
        $this->map['person_send_book_confirm'] = isset($user_data['full_name'])?$user_data['full_name']:Session::get('user_id');
        if(Url::get('cmd') == 'booking_confirm')
        {
            if(Url::get('type'))
            {
                $this->parse_layout('book_confirm_vn', $this->map);                
            }else
            {
                $this->parse_layout('book_confirm', $this->map);
            }
        }
    }
}
?>