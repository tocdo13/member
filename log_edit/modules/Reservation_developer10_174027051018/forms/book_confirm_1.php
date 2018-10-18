<?php
class BookingConfirm1Form extends Form
{
	function BookingConfirm1Form()
	{
		Form::Form('BookingConfirm1Form');
	}
    function on_submit()
	{
        $data = array();
        $data['reservation_id'] = Url::get('id');
        $data['book_confirm_create_date'] = time();
        $data['special_request'] = Url::get('special_request');
        $data['reservation_department'] = Url::get('reservation_department');
        $data['tel_booking_cf'] = Url::get('tel_booking_cf');
	    if(DB::exists('select reservation_id from booking_confirm where reservation_id='.Url::get('id')))
            DB::update('booking_confirm', $data, 'reservation_id ='.Url::get('id'));
        else               
            DB::insert('booking_confirm', $data);
    }
	function draw()
	{
	    // khai bao mang dung cho toan bo function
        $this->map = array();
        
        // lay thong tin chung cua booking CF
        $reservation = DB::fetch('
                                SELECT 
                                    reservation.id as recode,
                                    reservation.user_id,
                                    party.full_name as user_name,
                                    reservation.booker,
                                    reservation.phone_booker,
                                    reservation.email_booker,
                                    reservation.time as create_booking,
                                    NVL(reservation.deposit,0) as deposit,
                                    reservation.cut_of_date,
                                    customer.name as customer_name,
                                    customer.address as customer_address,
                                    booking_confirm.special_request,
                                    booking_confirm.reservation_department,
                                    booking_confirm.tel_booking_cf,
                                    booking_confirm.id as booking_confirm_id,
                                    0 as total
                                FROM
                                    reservation
                                    inner join customer on customer.id=reservation.customer_id
                                    inner join party on party.user_id=reservation.user_id
                                    left join booking_confirm on booking_confirm.reservation_id=reservation.id
                                WHERE
                                    reservation.id='.Url::get('id').'
                                ');
        // gan toan bo thong tin chung vao this->map
        $this->map = $reservation;
        $_REQUEST += $reservation;
        
        // lay thong tin cua tung luoc do gia de gom
        $room_status = DB::fetch_all("
                            SELECT
                                room_status.id,
                                NVL(room_status.change_price,0) as price,
                                TO_CHAR(room_status.in_date,'DD/MM/YYYY') as in_date,
                                reservation_room.id as reservation_room_id,
                                NVL(reservation_room.deposit,0) as deposit,
                                TO_CHAR(reservation_room.arrival_time,'DD/MM/YYYY') as arrival_time,
                                TO_CHAR(reservation_room.departure_time,'DD/MM/YYYY') as departure_time,
                                room_level.name as room_level_name,
                                room_level.id as room_level_id,
                                NVL(reservation_room.adult,0) as adult,
                                NVL(reservation_room.child,0) as child
                            FROM
                                room_status
                                inner join reservation_room on reservation_room.id=room_status.reservation_room_id
                                inner join reservation on reservation_room.reservation_id=reservation.id
                                inner join room_level on reservation_room.room_level_id=room_level.id
                            WHERE
                                reservation.id=".Url::get('id')."
                                and reservation_room.status!='CANCEL'
                                and ( (room_status.in_date != reservation_room.departure_time and reservation_room.arrival_time != reservation_room.departure_time) OR (room_status.in_date = reservation_room.departure_time and reservation_room.arrival_time = reservation_room.departure_time) )
                            ORDER BY
                                reservation_room.id,
                                room_status.in_date ASC
                            ");
        
        // gom theo luoc do gia cua tung phong truoc
        
        // khai bao mang ban dau de gom
        $reservation_room_price = array(); 
        
        // khai bao mang ban dau de tinh deposit theo tung phong
        $reservation_room_deposit = array();
        
        // duyet mang luoc do gia
        foreach($room_status as $key=>$value)
        {
            // gom theo gia cua tung phong
            $key_group = $value['reservation_room_id'].'_'.$value['price'];
            
            // thuat toan gom: kiem tra xem mang $reservation_room_price da co $key_group chua.
            // + neu chua thi tao 1 mang
            // + neu có roi thi gom thanh 1 mang
            // xet ngay: neu dayuse giu nguyen ngay di, nhuoc lai cong ngay di len 1 de dung so dem.
            
            // kiem tra su ton tai cua $reservation_room_price[$key_group]
            if(!isset($reservation_room_price[$key_group]))
            {
                $reservation_room_price[$key_group]['id'] = $key_group;
                $reservation_room_price[$key_group]['room_level_id'] = $value['room_level_name'];
                $reservation_room_price[$key_group]['room_level_name'] = $value['room_level_name'];
                $reservation_room_price[$key_group]['start_date'] = $value['in_date'];
                $reservation_room_price[$key_group]['end_date'] = $value['in_date'];
                $reservation_room_price[$key_group]['adult'] = $value['adult'];
                $reservation_room_price[$key_group]['child'] = $value['child'];
                $reservation_room_price[$key_group]['price'] = $value['price'];
                $reservation_room_price[$key_group]['nite'] = 0;
                $reservation_room_price[$key_group]['total'] = 0;
            }
            // cong don so dem 
            $reservation_room_price[$key_group]['nite']++;
            
            // tinh tong
            $reservation_room_price[$key_group]['total'] += $value['price'];
            
            // xet ngay: neu khong phai dayuse thi cong ngay len 1
            if($value['arrival_time']!=$value['departure_time'])
                $value['in_date'] = date('d/m/Y',(Date_Time::to_time($value['in_date'])+(24*3600)));
            
            // gan lai ngay da tinh duoc cho ngay Departure
            $reservation_room_price[$key_group]['end_date'] = $value['in_date'];
            
            // tinh tong chung: cong theo tung luoc do gia
            $this->map['total'] += $value['price'];
            
            // tinh deposit chung: cong deposit cua tung phong.
            if(!isset($reservation_room_deposit[$value['reservation_room_id']]))
            {
                $reservation_room_deposit[$value['reservation_room_id']] = $value['deposit'];
                $this->map['deposit'] += $value['deposit'];                
            }
        }
        
        // gom theo hang phong - khoang thoi gian - so tien
        // sau khi lay duoc cac khoan thoi gian theo so tien o phong trong chang tren
        // thi den buoc nay gom lai thanh mang cuoi cung.
        
        // khai bao mang de gom
        $room_level_group = array();
        
        foreach($reservation_room_price as $key=>$value)
        {
            // gom theo hang phong - khoang time - gia
            $key_group = 'ROOM_'.$value['room_level_id'].'_'.$value['start_date'].'_'.$value['end_date'].'_'.$value['price'];
            
            // kiem tra su ton tai cua mang
            if(!isset($room_level_group[$key_group]))
            {
                $room_level_group[$key_group]['id'] = $key_group;
                $room_level_group[$key_group]['name'] = $value['room_level_name'];
                $room_level_group[$key_group]['quantity'] = 0;
                $room_level_group[$key_group]['start_date'] = $value['start_date'];
                $room_level_group[$key_group]['end_date'] = $value['end_date'];
                $room_level_group[$key_group]['adult'] = 0;
                $room_level_group[$key_group]['child'] = 0;
                $room_level_group[$key_group]['price'] = $value['price'];
                $room_level_group[$key_group]['nite'] = $value['nite'];
                $room_level_group[$key_group]['total'] = 0;
                $room_level_group[$key_group]['type'] = 'anyday';
                $room_level_group[$key_group]['level'] = 'ROOM';
            }
            
            // cong don theo mang
            $room_level_group[$key_group]['quantity']++;
            $room_level_group[$key_group]['adult'] += $value['adult'];
            $room_level_group[$key_group]['child'] += $value['child'];
            $room_level_group[$key_group]['total'] += $value['total'];
        }
        
        // lay chi tiet cua extra service
        $extra_service = DB::fetch_all('
                                 SELECT
                                    extra_service_invoice_detail.id,
                                    extra_service_invoice_detail.invoice_id,
                                    extra_service_invoice_detail.price,
                                    TO_CHAR(extra_service_invoice_detail.in_date,\'DD/MM/YYYY\') as in_date,
                                    extra_service_invoice_detail.quantity,
                                    extra_service_invoice.reservation_room_id,
                                    extra_service.id as extra_service_id,
                                    extra_service.name as extra_service_name
                                 FROM
                                    extra_service_invoice_detail
                                    inner join extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id
                                    inner join extra_service on extra_service.id = extra_service_invoice_detail.service_id
                                    inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                                    inner join reservation on reservation.id = reservation_room.reservation_id
                                 WHERE
                                    reservation.id='.Url::get('id').'
                                 ORDER BY
                                    extra_service_invoice_detail.invoice_id,
                                    extra_service_invoice_detail.in_date ASC                            
                                    
        ');
        
        // gom theo tung hoa don - lay chang theo gia tien
        
        // khai bao mang de gom
        $extra_service_price = array();
        
        foreach($extra_service as $key=>$value)
        {
            // gom theo hoa don va so tien
            $key_group = $value['invoice_id'].'_'.$value['price'];
            if(!isset($extra_service_price[$key_group]))
            {
                $extra_service_price[$key_group]['id'] = $key_group;
                $extra_service_price[$key_group]['extra_service_id'] = $value['extra_service_id'];
                $extra_service_price[$key_group]['extra_service_name'] = $value['extra_service_name'];
                $extra_service_price[$key_group]['quantity'] = 0;
                $extra_service_price[$key_group]['start_date'] = $value['in_date'];
                $extra_service_price[$key_group]['end_date'] = $value['in_date'];
                $extra_service_price[$key_group]['nite'] = 0;
                $extra_service_price[$key_group]['price'] = $value['price'];
                $extra_service_price[$key_group]['total'] = 0;
                $extra_service_price[$key_group]['type'] = 'oneday';
            }
            else
            {
                $extra_service_price[$key_group]['type'] = 'anyday';
                $extra_service_price[$key_group]['end_date'] = date('d/m/Y',(Date_Time::to_time($value['in_date'])+(24*3600)));
            }
            $extra_service_price[$key_group]['quantity'] += $value['quantity'];
            $extra_service_price[$key_group]['nite'] ++;
            $extra_service_price[$key_group]['total'] += $value['quantity']*$value['price'];
            
            // tinh tong chung
            $this->map['total'] += $value['quantity']*$value['price'];
        }
        
        // gom theo loai dich vu - khoang time - gia
        
        // khai bao mang de gom
        $extra_service_group = array();
        foreach($extra_service_price as $key=>$value)
        {
            $key_group = 'EXTRA_'.$value['extra_service_id'].'_'.$value['start_date'].'_'.$value['end_date'].'_'.$value['price'];
            if(!isset($extra_service_group[$key_group]))
            {
                $extra_service_group[$key_group]['id'] = $key_group;
                $extra_service_group[$key_group]['name'] = $value['extra_service_name'];
                $extra_service_group[$key_group]['quantity'] = 0;
                $extra_service_group[$key_group]['start_date'] = $value['start_date'];
                $extra_service_group[$key_group]['end_date'] = $value['end_date'];
                $extra_service_group[$key_group]['adult'] = '';
                $extra_service_group[$key_group]['child'] = '';
                $extra_service_group[$key_group]['price'] = $value['price'];
                $extra_service_group[$key_group]['nite'] = $value['nite'];
                $extra_service_group[$key_group]['total'] = 0;
                $extra_service_group[$key_group]['type'] = $value['type'];
                $extra_service_group[$key_group]['level'] = 'EXTRA';
            }
            // cong don theo mang
            $extra_service_group[$key_group]['quantity']+= $value['quantity'];
            $extra_service_group[$key_group]['total'] += $value['total'];
        }
        
        
        // gan 2 mang thu duoc vao chung mot mang
        $this->map['items'] = $room_level_group+$extra_service_group;
        
        // thong tin cua user
        $user_data = Session::get('user_data');
        $this->map['creater_name'] = $user_data['full_name'];
        $this->map['department_user'] = DB::fetch('
                                                    SELECT
                                                        department.name_'.Portal::language().' as name
                                                    FROM
                                                        department
                                                        inner join portal_department on portal_department.department_code=department.code
                                                        inner join account on account.portal_department_id=portal_department.id
                                                    WHERE
                                                        account.id=\''.User::id().'\'
                                                    ','name');
        if($this->map['department_user'] and $this->map['booking_confirm_id']!='')
        {
            $this->map['reservation_department'] = $this->map['department_user'];
        }
        // truyen layout
        $this->parse_layout('book_confirm_1',$this->map);
    }
}
?>