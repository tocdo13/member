<?php
class BookingConfirmForm extends Form
{
	function BookingConfirmForm()
	{
		Form::Form('BookingConfirmForm');
        $this->link_js('packages/core/includes/js/common.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
    	$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        
	}
    function on_submit()
	{
	    /*------------------------------------ send Mail -------------------------------*/	    
        if(Url::get('content_1'))
        {
            $content_email = Url::get('content_mail').'<br ><br >'.Url::get('content_1');
            require_once 'packages/hotel/includes/email/booking_cf/booking_confim.php';
            $to_email = $_REQUEST['to_email_address'];
            $subject_email = $_REQUEST['title_mail'];
            $reservation_id = Url::get('id');
            require_once 'packages/hotel/includes/email/class_mail/class.verifyEmail.php';
            $vmail = new verifyEmail();
            send_mail_bk($reservation_id,$to_email,$subject_email,$content_email);
            return ;
        }
        // UPDATE RESERCATION 
        $reservation_id = Url::get('id');
        $bcf_status = 0;
        $bcf_payment = "";
        $note = "";
        $need_deposit = 0;
        $cut_of_date = '';
        if(isset($_REQUEST['bcf_status']))
            $bcf_status = $_REQUEST['bcf_status'];
        
        $bol = 0;
        for($i = 1;$i <= 6;$i++)
        {
            if(isset($_REQUEST['bcf_payment_'.$i]))
            {
                if($bol == 0)
                {
                    $bcf_payment = $_REQUEST['bcf_payment_'.$i];
                    $bol = 1;
                }
                else
                    $bcf_payment .= ",".$_REQUEST['bcf_payment_'.$i];
            } 
        }
        
        if(isset($_REQUEST['note']))
            $note = $_REQUEST['note'];
            
        if(isset($_REQUEST['need_deposit']))
            $need_deposit = $_REQUEST['need_deposit'];
            
        if(isset($_REQUEST['cut_of_date']))
            $cut_of_date = Date_Time::to_orc_date($_REQUEST['cut_of_date']);
        
        $array_update = array();
        $array_update['BCF_STATUS'] = $bcf_status;
        $array_update['BCF_PAYMENT'] = $bcf_payment;
        $array_update['NEED_DEPOSIT'] = System::calculate_number($need_deposit);
        $array_update['CUT_OF_DATE'] = $cut_of_date;
        $cond = "ID = ".$reservation_id;
        DB::update("RESERVATION",$array_update,$cond);
        // UPDATE+INSERT BOOKING_CONFIRM
            // Date_time
        
        if( $_REQUEST['flight_arrival_time'] != ""){
        $arr = explode(':',$_REQUEST['flight_arrival_time']);
        $_REQUEST['flight_arrival_time'] = ($arr[0]*3600)+($arr[1]*60);
        }
        if($_REQUEST['flight_departure_time'] != ""){
        $arr_1 = explode(':',$_REQUEST['flight_departure_time']);
        $_REQUEST['flight_departure_time'] = ($arr_1[0]*3600)+($arr_1[1]*60);
        }
        $array_booking = array();
        $array_booking['RESERVATION_ID'] = Url::get('id');
        $array_booking['ATTENTION'] = $_REQUEST['attention'];
        $array_booking['GUESTS_NAME'] = $_REQUEST['guests_name'];
        $array_booking['PICK_UP'] = $_REQUEST['pick_up'];
        $array_booking['SEE_OFF'] = $_REQUEST['see_off'];
        $array_booking['FLIGHT_ARRIVAL_TIME'] = $_REQUEST['flight_arrival_time'];
        $array_booking['FLIGHT_DEPARTURE_TIME'] = $_REQUEST['flight_departure_time'];
        $array_booking['COST_FLIGHT_ARRIVAL_TIME'] = $_REQUEST['flight_arr_price'];
        $array_booking['COST_FLIGHT_DEPARTURE_TIME'] = $_REQUEST['flight_dep_price'];
        $array_booking['NOTE'] = $_REQUEST['note_cf'];
        $array_booking['VAT_INFORMATION_COMPANY'] = $_REQUEST['vat_company'];
        $array_booking['VAT_INFORMATION_ADDRESS'] = $_REQUEST['vat_address'];
        $array_booking['VAT_INFORMATION_TAX_CODE'] = $_REQUEST['vat_tax_code'];
        $array_booking['ROOM_TYPE_UNASIGN'] = $_REQUEST['room_type_unasign'];
        $test = DB::fetch_all("SELECT id,reservation_id FROM booking_confirm");
        $case = false;
        foreach($test as $k=>$v){
            if($reservation_id == $v['reservation_id']){
                $case  = true;
            }
        }
        if($case==true){
            $cond_book = "RESERVATION_ID = ".$reservation_id;
            DB::update("BOOKING_CONFIRM",$array_booking,$cond_book);
        }else{
            DB::insert("BOOKING_CONFIRM",$array_booking);
        }
        
      Url::redirect('reservation',array('cmd','id'));
    }
	function draw()
	{
	    $_REQUEST['total_vnd'] = 0;   
		$this->map = array();
        $revertion_id = $_REQUEST['id'];
        $infor_comon = DB::fetch("
            select 
                reservation.CUT_OF_DATE as dealine_deposit,
                reservation.NEED_DEPOSIT as need_deposit,
                reservation.deposit as deposit,
                reservation.NOTE, 
                BCF_STATUS, 
                BCF_PAYMENT,
                customer.NAME as ctm_name, 
                customer.ADDRESS as ctm_add, 
                customer.PHONE as ctm_phone, 
                customer.FAX as ctm_fax,
                customer.EMAIL as ctm_email, 
                customer.TAX_CODE as ctm_tax_code,
                tour.TOUR_LEADER, 
                tour.name_set,
                PARTY.FULL_NAME user_name,
                PARTY.PHONE user_phone
            from 
                reservation
                left join customer on customer.id = reservation.customer_id
                left join tour on tour.id = reservation.tour_id
                left JOIN PARTY ON  PARTY.USER_ID = RESERVATION.USER_ID
                inner join reservation_room on reservation_room.reservation_id = reservation.id
                left join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
            where 
                reservation.id = ".$revertion_id."
                AND reservation_room.status != 'CANCEL'
                ");
                
        if(isset($infor_comon['dealine_deposit'])){
            $infor_comon['dealine_deposit'] = Date_Time::convert_orc_date_to_date($infor_comon['dealine_deposit'],"/");    
        }
        if(!$infor_comon){
            $infor_comon = array(
                'dealine_deposit'=>'',
                'need_deposit'=>'',
                'deposit'=>'',
                'note'=>'',
                'bcf_status'=>'1',
                'bcf_payment'=>'',
                'ctm_name'=>'',
                'ctm_add'=>'',
                'ctm_phone'=>'',
                'ctm_fax'=>'',
                'ctm_email'=>'',
                'ctm_tax_code'=>'',
                'tour_leader'=>'',
                'name_set'=>'',
                'user_name'=>'',
                'user_phone'=>'',
                'booking_code'=>''
            );
        }
        $infor_comon['booking_code'] = $revertion_id;
        //System::debug($infor_comon); //exit();
        // L?y Guest name
        
        $_REQUEST['guests_name'] = $infor_comon['ctm_name'];
        
        // lay thong tin phong
        $orcl = DB::fetch_all("
            SELECT
                reservation_room.id as id,
                reservation_room.arrival_time as c_in,
                reservation_room.departure_time as c_out,
                reservation_room.price as rate,
                reservation_room.foc,
                room_level.name,
                room_type.brief_name,
                1 as num_room,
                CONCAT(concat(traveller.first_name,' '),traveller.last_name) as g_name
            FROM
                reservation_room
                left join room on reservation_room.room_id = room.id
                left join room_level on reservation_room.room_level_id = room_level.id
                left join room_type on reservation_room.room_type_id = room_type.id
                inner join reservation on reservation_room.reservation_id = reservation.id
                left join traveller on reservation_room.traveller_id = traveller.id
            Where
                reservation_room.reservation_id = ".$revertion_id."
                AND reservation_room.status != 'CANCEL' AND room_level.is_virtual!=1
        ");
        $this->map['customer'] = $infor_comon;
        $this->map['room_type_name'] = DB::fetch_all("SELECT room_type.id, room_type.brief_name, room_type.name FROM room_type");
        //$_REQUEST['arr_type'] = $this->map['room_type_name'];
        $room_type_name = String::array2js($this->map['room_type_name']);
        foreach($orcl as $key=>$content){
            $orcl[$key]['c_in'] = Date_Time::convert_orc_date_to_date($content['c_in'],'/');
            $orcl[$key]['c_out'] = Date_Time::convert_orc_date_to_date($content['c_out'],'/');
            $date_f = Date_Time::to_time($orcl[$key]['c_in']);
            $date_t = Date_Time::to_time($orcl[$key]['c_out']);
            $orcl[$key]['num_date'] = ($date_t - $date_f)/(24*3600);
            $_REQUEST['total_vnd'] += ($orcl[$key]['num_date']*$orcl[$key]['rate']);  
        }
        $extrabed = DB::fetch_all("
            SELECT 
                concat(reservation_room.id,extra_service_invoice.id) as id,
                reservation_room.extra_bed_from_date as c_in,
                reservation_room.extra_bed_to_date as c_out,
                reservation_room.extra_bed_rate as rate,
                '' as foc,
                'extra_bed' as name,
                room_type.brief_name,
                '' as num_room,
                (extra_service_invoice.total_amount/reservation_room.extra_bed_rate) as num_date,
                CONCAT(concat(traveller.first_name,' '),traveller.last_name) as g_name
            FROM
                extra_service_invoice
                inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                left join room on reservation_room.room_id = room.id
                left join room_type on reservation_room.room_type_id = room_type.id
                left join traveller on reservation_room.traveller_id = traveller.id
            WHERE
                reservation_room.reservation_id = ".$revertion_id."
                AND reservation_room.status != 'CANCEL' and reservation_room.extra_bed_rate != 0
                and extra_service_invoice.use_extra_bed = 1
                 
        ");
        //
        foreach($extrabed as $ex=>$bed){
            $extrabed[$ex]['c_in'] = Date_Time::convert_orc_date_to_date($bed['c_in'],'/');
            $extrabed[$ex]['c_out'] = Date_Time::convert_orc_date_to_date($bed['c_out'],'/');
            $_REQUEST['total_vnd'] += ($bed['rate']*$bed['num_date']);
        }
        //System::debug($extrabed);
        $this->map['reservation_room_type'] = $orcl+$extrabed;
        //$_REQUEST['reservation_id_arr'] = array();
        //$arr = 0;
        //foreach($this->map['reservation_room_type'] as $id1=>$arr1){
            //$_REQUEST['reservation_id_arr'][$arr] = $id1;
            //$arr ++;
        //}
        //System::debug($_REQUEST['reservation_id_arr']);
        $revertion_room_type = String::array2js($this->map['reservation_room_type']);
        $flight_code = DB::fetch_all("SELECT
                                        id,attention, guests_name, pick_up, see_off, flight_arrival_time, flight_departure_time, cost_flight_arrival_time as flight_arr_price, cost_flight_departure_time as flight_dep_price, vat_information_company as vat_company, vat_information_address as vat_address, vat_information_tax_code as vat_tax_code, note as note, room_type_unasign 
                                    FROM 
                                        BOOKING_CONFIRM 
                                    WHERE RESERVATION_ID = ".$revertion_id);
        $check_room_type = '';
        foreach($flight_code as $key1=>$content1){
            if($content1['flight_arrival_time'] != ""){
            $h = intval($content1['flight_arrival_time']/3600);
            $so_du = $content1['flight_arrival_time']%3600;
            $i = $so_du/60; if($i<10){$i = '0'.$i;}
            $content1['flight_arrival_time'] = $h.":".$i;
            }
            if($content1['flight_departure_time'] != ""){
            $h_2 = intval($content1['flight_departure_time']/3600);
            $so_du_2 = $content1['flight_departure_time']%3600;
            $i_2 = $so_du_2/60; if($i_2<10){$i_2 = '0'.$i_2;}
            $content1['flight_departure_time'] = $h_2.":".$i_2;
            }
            $_REQUEST['note_id'] = $content1['id'];
            $_REQUEST['attention'] = $content1['attention'];
            $_REQUEST['pick_up'] = $content1['pick_up'];
            $_REQUEST['see_off'] = $content1['see_off'];
            $_REQUEST['flight_arrival_time'] = $content1['flight_arrival_time'];
            $_REQUEST['flight_departure_time'] = $content1['flight_departure_time'];
            $_REQUEST['flight_arr_price'] = $content1['flight_arr_price'];
            $_REQUEST['total_vnd'] += $content1['flight_arr_price'];
            $_REQUEST['flight_dep_price'] = $content1['flight_dep_price'];
            $_REQUEST['total_vnd'] += $content1['flight_dep_price'];
            $_REQUEST['vat_company'] = $content1['vat_company'];
            $_REQUEST['vat_address'] = $content1['vat_address'];
            $_REQUEST['vat_tax_code'] = $content1['vat_tax_code'];
            $_REQUEST['note_cf'] = $content1['note'];
            $_REQUEST['room_type_unasign'] = $content1['room_type_unasign'];
            $check_room_type = $content1['room_type_unasign']; 
        }
        //System::debug($this->map);
        if($check_room_type != ''){
            foreach($this->map['reservation_room_type'] as $id_room=>$key_room){
                if(empty($key_room['brief_name'])){
                    $arr_room = explode(",",$check_room_type);
                    unset($arr_room[0]);
                    foreach($arr_room as $id_arr=>$key_arr){
                        $check = explode("_",$key_arr);
                        if($check[1]==$key_room['id']){
                            $this->map['reservation_room_type'][$id_room]['brief_name'] = $check[2];
                        }
                    }
                }
            }
        }
        if(!isset($_REQUEST['note_cf'])){
            $_REQUEST['note_cf'] = $infor_comon['note'];
        }
        $_REQUEST['total_vnd'] = System::display_number($_REQUEST['total_vnd']);
        $detail_user = DB::fetch("
            SELECT
                account.id as id,
                privilege_group.name_1 as position,
                party.phone as phone
            FROM
                account
                left join account_privilege_group on account_privilege_group.account_id = account.id
                left join privilege_group on account_privilege_group.group_privilege_id = privilege_group.id
                left join party on party.user_id = account.id
            WHERE
                account.id = '".User::id()."'
        ");
        $_REQUEST['user'] = $detail_user['id'];
        $_REQUEST['position'] = $detail_user['position'];
        $_REQUEST['phone'] = $detail_user['phone'];
        //System::debug($detail_user);
         $deposit='select
                    payment.id,
                    payment.amount,
                    payment.time,
                    payment.description,
                    customer.name,
                    payment_type.name_'.Portal::language().' as payment_type
                    from payment
                    inner join payment_type on payment.payment_type_id=payment_type.def_code
                    inner join customer on payment.customer_id=customer.id
                    where payment.reservation_id='.$revertion_id.'
                    ';
         $deposit=DB::fetch_all($deposit);
        foreach($deposit as $k=>$v){
            $deposit[$k]['time']=date('d/m/Y',$v['time']);
            $deposit[$k]['amount']=System::display_number($v['amount']);
        }
        $this->map['deposit']=$deposit;
        $this->parse_layout("book_confirm",$this->map+array('reservation_room_type_js'=>$revertion_room_type,'room_type_name_js'=>$room_type_name));
        
	}
}
?>