
<?php
    define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    set_include_path(ROOT_PATH);
    require_once 'packages/core/includes/system/config.php';
    /** ****************************************** **/
    /** hàm kiểm tra số **/
    function is_numberic_php($a){
        $count = strlen($a);
        $check = true;
        for($i=0;$i<$count;$i++)
        {
            $str = substr($a,$i,1);
            if(preg_match("/[0-9]+/",$str))
            {
                
            }
            else
            {
                $check = false;
            }
        }
        return $check;
    }
    
    /** api.php?cmd=validation_member&account=xxx&password=xxx **/
    /** Hàm xác thực  **/
    function validation_member()
   	{
        $data['refund']['login'] = false;
        $data['refund']['title'] = '';
        $data['refund']['detail'] = array();
        if(!(isset($_REQUEST['account']) AND $_REQUEST['account'] AND is_numberic_php($_REQUEST['account'])) OR !(isset($_REQUEST['password']) AND $_REQUEST['password']))
        {
            $data['refund']['title'] = 'is not';
            if(!(isset($_REQUEST['account']) AND $_REQUEST['account'] AND is_numberic_php($_REQUEST['account'])))
            {
                $data['refund']['title'] .= ' - input username';
            }
            if(!(isset($_REQUEST['password']) AND $_REQUEST['password']))
            {
                $data['refund']['title'] .= ' - input password';
            }
            
        }
        else
        {
            $record = DB::fetch("SELECT 
                                    traveller.*,
                                    country.name_2 as country_name,
                                    member_level.name as name_level,
                                    guest_type.name as traveller_level_name
                                FROM 
                                    traveller 
                                    left join country on country.id=traveller.nationality_id
                                    inner join member_level on member_level.id=traveller.member_level_id
                                    left join guest_type on guest_type.id=traveller.traveller_level_id
                                WHERE 
                                traveller.member_code=".$_REQUEST['account']);
            if(sizeof($record)<=1)
            {
                $data['refund']['title'] = 'is not account';
            }
            else
            {
                if($record['gender']==0){$record['gender']='nữ';}else{$record['gender']=='nam';}
                if(isset($record['password']) AND ($record['password']==$_REQUEST['password']))
                {
                    /** laays chuong trinhf giamr gias **/
                    $member_level_id = $record['member_level_id'];
                    $to_day = Date_Time::to_orc_date(date('d/m/Y'));
                    $member_discount = DB::fetch_all("
                                                SELECT
                                                    member_level_discount.*,
                                                    ROW_NUMBER() OVER (ORDER BY member_level_discount.id desc ) as id,
                                                    member_level.name,
                                                    member_discount.title,
                                                    member_discount.description 
                                                FROM 
                                                    member_level_discount
                                                    inner join member_level on member_level.id = member_level_discount.member_level_id
                                                    inner join member_discount on member_discount.code = member_level_discount.member_discount_code
                                                WHERE
                                                    member_level.id=$member_level_id AND ((member_level_discount.start_date<='$to_day' AND member_level_discount.end_date>='$to_day'))
                                                ORDER BY 
                                                    member_level_discount.id DESC
                                                ");
                    /** end giam gia **/
                    $data['refund']['login'] = true;
                    $data['refund']['title'] = 'login is success';
                    $data['refund']['detail'] = $record;
                    $data['refund']['detail']['member_discount'] = $member_discount;
                }
                else
                {
                    $data['refund']['title'] = 'is not password';
                }
            }
        }
        return $data;
   	}
    /** end function validation_member **/
/** *********************************************************************** **/
    
    /** api.php?cmd=change_password&account=xxx&password=xxx&password_new=xxx **/
    /** Hàm đổi password **/
    function change_password()
    {
        $data = array();
        $data = validation_member();
        unset($data['refund']['detail']);
        $data['refund']['change'] = false;
        if($data['refund']['login'] == true)
        {
            if(!(isset($_REQUEST['password_new']) AND $_REQUEST['password_new']))
            {
                $data['refund']['title'] = 'is not password new';
            }
            else
            {
                $check = DB::update('traveller',array('password'=>$_REQUEST['password_new']),'MEMBER_CODE='.$_REQUEST['account']);
                if($check!=1)
                {
                    $data['refund']['title'] = 'is not change password';
                }else
                {
                    $data['refund']['change'] = true;
                    $data['refund']['title'] = 'change password is success';
                }
            }
        }
        //System::debug($data);
        return $data;
    }
    /** end function change_password **/
/** *********************************************************************** **/
    
    /** api.php?cmd=forgot_password&account=xxx  **/
    /** Hàm gửi mail quên mật khẩu **/
    function forgot_password()
   	{
   	    require_once 'packages/hotel/includes/member.php';
        $data = array();
        $data['refund']['forgot'] = false;
        if(isset($_REQUEST['account']) AND ($_REQUEST['account']) AND is_numberic_php($_REQUEST['account']))
        {
            $array = DB::fetch('SELECT 
                                    traveller.password,
                                    traveller.email,
                                    traveller.first_name,
                                    traveller.last_name
                                FROM 
                                    traveller
                                WHERE 
                                traveller.member_code='.$_REQUEST['account']);
            if($array>1)
            {
                $content = "<h1> Xin chào, ".$array['first_name']." ".$array['last_name']."</h1><br />";
                $content .= "<p> Mật khẩu đăng nhập của bạn là: ".$array['password']."</p>";
                if(!filter_var($array['email'], FILTER_VALIDATE_EMAIL)){
                    $data['refund']['forgot'] = 'is not email';
                }else{
                    sent_mail_to($array['email'],$content);
                    $data['refund']['forgot'] = true;
                }
                
            }
        }
        return $data['refund']['forgot'];
   	}
    /** end function forgot_password **/
/** *********************************************************************** **/
    
    /** api.php?cmd=history_booking&account=xxx&password=xxx&arrival=xxx&departure=xxx **/
    /** Hàm xem lịch sử **/
    function history_booking()
   	{
        $data = array();
        $check_date = 0;
        if(!isset($_REQUEST['arrival']) OR !isset($_REQUEST['departure']) OR ($_REQUEST['arrival']=='') OR ($_REQUEST['departure']=='')){
            $cond=' AND (1>0)';
        }else{
            $arrival = Date_Time::to_orc_date($_REQUEST['arrival']);
            $departure = Date_Time::to_orc_date($_REQUEST['departure']);
            $check_date=1;
        }
        $data = validation_member();
        if($data['refund']['login'] == true)
        {
            $traveller_id = $data['refund']['detail']['id'];
            unset($data['refund']['detail']);
                // Tiền phòng
                if($check_date==1){
                    $cond = " AND ( room_status.in_date>='".$arrival."' AND room_status.in_date<='".$departure."' )";
                }
                $room = DB::fetch_all("SELECT
                                CONCAT(CONCAT(concat(DATE_TO_UNIX(room_status.in_date),'-'),reservation_room.id),'-ROOM') as id, 
                                reservation_room.id as reservation_room_id,
                                reservation_room.reservation_id,
                                room.name as room_name,
                                TO_CHAR(reservation_room.arrival_time,'DD/MM/YYYY') as arrival_time,
                                TO_CHAR(reservation_room.departure_time,'DD/MM/YYYY') as departure_time,
                                reservation_room.net_price,
                                reservation_room.tax_rate,
                                reservation_room.service_rate,
                                TO_CHAR(room_status.in_date,'DD/MM/YYYY') as in_date,
                                room_status.change_price,
                                NVL(reservation_room.reduce_balance,0) as reduce_balance,
                                NVL(reservation_room.reduce_amount,0) as reduce_amount,
                                NVL(reservation_room.deposit,0) as deposit,
                                TO_CHAR(reservation_room.deposit_date,'DD/MM/YYYY') as deposit_date
                                FROM
                                    reservation_room
                                    inner join room on reservation_room.room_id = room.id 
                                    inner join room_status on reservation_room.id = room_status.reservation_room_id
                                    inner join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
                                    inner join traveller on reservation_traveller.traveller_id = traveller.id
                                    inner join  room_level on room_level.id = room.room_level_id
                                WHERE
                                    traveller.id = ".$traveller_id." ".$cond."
                                    AND room_status.status ='OCCUPIED'
                                    AND (reservation_room.foc is null)
                                    AND (reservation_room.foc_all = 0)
                					AND (room_level.is_virtual is null or room_level.is_virtual = 0)
                                ORDER BY 
                                    reservation_room.id DESC
                                ");
                /////////////////////////////////////////
                //System::debug($room);
                $array_reservation_room = array();
                $reservation_room_check = '';
                foreach($room as $id=>$value){
                    if($reservation_room_check!=$value['reservation_room_id']){
                        $reservation_room_check = $value['reservation_room_id'];
                        $array_reservation_room[$value['reservation_room_id']]['reservation_room_id'] = $reservation_room_check;
                        $array_reservation_room[$value['reservation_room_id']]['type'] = 'ROOM';
                        $array_reservation_room[$value['reservation_room_id']]['name'] = $value['room_name'];
                        $array_reservation_room[$value['reservation_room_id']]['arrival_time'] = $value['arrival_time'];
                        $array_reservation_room[$value['reservation_room_id']]['departure_time'] = $value['departure_time'];
                        $array_reservation_room[$value['reservation_room_id']]['in_date'] = $value['arrival_time']." - ".$value['departure_time'];
                        $array_reservation_room[$value['reservation_room_id']]['total'] = 0;
                        $array_reservation_room[$value['reservation_room_id']]['detail'] = array();
                        $array_reservation_room[$value['reservation_room_id']]['detail']['deposit'] = 0;
                        if($value['reduce_amount']>0){
                            $array_reservation_room[$value['reservation_room_id']]['detail']['discount'] = $value['reduce_amount'];
                        }
                        if($value['deposit']>0){
                            $array_reservation_room[$value['reservation_room_id']]['detail']['deposit'] += $value['deposit'];
                        }
                    }
                    //check net price
                    if($value['net_price'])
                        $value['change_price'] = $value['change_price']/(1+$value['service_rate']/100)/(1+$value['tax_rate']/100);
                    //GIAM GIA %
                    $value['change_price'] *= (1-$value['reduce_balance']/100);
                    //Giảm giá số tiền
                    if($value['in_date']==$value['arrival_time']){
                        $value['change_price'] = $value['change_price'] - $value['reduce_amount'];
                    }
                    $room[$id]['change_price'] = $value['change_price']*(1+$value['service_rate']/100)*(1+$value['tax_rate']/100);
                    $array_reservation_room[$value['reservation_room_id']]['total'] += $room[$id]['change_price'];
                    if($value['reduce_balance']>0){
                        $discount = " ( Discounted ".$value['reduce_balance']." % ) ";
                    }else{
                        $discount = "";
                    }
                    $array_reservation_room[$value['reservation_room_id']]['detail'][$value['in_date']]['ROOM'] = $value['in_date']." ".Portal::language("room_price").$discount." : ".$room[$id]['change_price'];
                }
                //System::debug($room);
                //System::debug($array_reservation_room);
                /// end tien phong //
                
                
                ////////// TIEN DICH VU LỄ TÂN ///////////
                if($check_date==1){
                    $cond = " AND ( extra_service_invoice_detail.in_date>='".$arrival."' AND extra_service_invoice_detail.in_date<='".$departure."' )";
                }
                $extra_service = DB::fetch_all("
                                    SELECT
                                        extra_service_invoice_detail.id as id,
                                        extra_service_invoice_detail.name as name,
                                        TO_CHAR(extra_service_invoice_detail.in_date,'DD/MM/YYYY') as in_date,
                                        extra_service_invoice_detail.price,
                                        extra_service_invoice_detail.quantity,
                                        extra_service_invoice.net_price,
                                        extra_service_invoice.tax_rate,
                                        extra_service_invoice.service_rate,
                                        extra_service_invoice.reservation_room_id
                                    FROM
                                        extra_service_invoice_detail
                                        inner join extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
                                        inner join reservation_room on reservation_room.id=extra_service_invoice.reservation_room_id
                                        inner join room on reservation_room.room_id = room.id
                                        inner join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
                                        inner join traveller on reservation_traveller.traveller_id = traveller.id
                                        inner join  room_level on room_level.id = room.room_level_id
                                    WHERE
                                        traveller.id = ".$traveller_id." ".$cond."
                                       
                                        AND (reservation_room.foc is null)
                                        AND (reservation_room.foc_all = 0)
                    					AND (room_level.is_virtual is null or room_level.is_virtual = 0)
                                    ORDER BY
                                        extra_service_invoice_detail.id DESC
                                ");
                ////////////////////////////////////////////////////////////
                //System::debug($extra_service);
                foreach($extra_service as $extra=>$service){
                    if($service['net_price']==0){
                        $total_extra_service = $service['price']*(1+$service['service_rate']/100)*(1+$service['tax_rate']/100);
                    }else{
                        $total_extra_service = $service['price'];
                    }
                    $total_extra_service = $total_extra_service * $service['quantity'];
                    $array_reservation_room[$service['reservation_room_id']]['total'] += $total_extra_service;
                    $array_reservation_room[$service['reservation_room_id']]['detail'][$service['in_date']][$service['name']] = $service['in_date']." ".$service['name'].": ".$total_extra_service;
                }
                //System::debug($array_reservation_room);
                ////// END DỊCH VỤ LỄ TÂN ////////////////////////////
                
                ////// TIỀN DỊCH VỤ BUỒNG ////////////////////////////
                if($check_date==1){
                    $cond = " AND ( housekeeping_invoice.time>='".Date_Time::to_time(Date_Time::convert_orc_date_to_date($arrival,"/"))."' AND housekeeping_invoice.time<='".Date_Time::to_time(Date_Time::convert_orc_date_to_date($departure,"/"))."' )";
                }
                $housekeeping_invoice = DB::fetch_all("
                                            SELECT
                                                housekeeping_invoice.id,
                                                housekeeping_invoice.reservation_room_id,
                                                housekeeping_invoice.time,
                                                housekeeping_invoice.total,
                                                housekeeping_invoice.type
                                            FROM
                                                housekeeping_invoice
                                                inner join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id
                                                inner join room on reservation_room.room_id = room.id
                                                inner join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
                                                inner join traveller on reservation_traveller.traveller_id = traveller.id
                                                inner join  room_level on room_level.id = room.room_level_id
                                            WHERE
                                                traveller.id = ".$traveller_id." ".$cond."
                                                AND (reservation_room.foc is null)
                                                AND (reservation_room.foc_all = 0)
                            					AND (room_level.is_virtual is null or room_level.is_virtual = 0)
                                            ORDER BY
                                                housekeeping_invoice.id DESC
                                        ");
                                        
                foreach($housekeeping_invoice as $house=>$keeping){
                    $housekeeping_invoice[$house]['time'] = date('d/m/Y',$keeping['time']);
                    $array_reservation_room[$keeping['reservation_room_id']]['total'] += $keeping['total'];
                    $array_reservation_room[$keeping['reservation_room_id']]['detail'][$housekeeping_invoice[$house]['time']][$keeping['type']] = $housekeeping_invoice[$house]['time']." ".$keeping['type'].": ".$keeping['total'];
                }
                ////// end tien buong //////////
                
                /////////// TIEN NHA HANG ////////////////
                
                /** trả về phòng **/
                if($check_date==1){
                    $cond = " AND ( bar_reservation.arrival_time>='".Date_Time::to_time(Date_Time::convert_orc_date_to_date($arrival,"/"))."' OR bar_reservation.departure_time<='".Date_Time::to_time(Date_Time::convert_orc_date_to_date($departure,"/"))."' )";
                }
                $restaurant = DB::fetch_all("
                                    SELECT
                                        bar_reservation.id,
                                        bar_reservation.reservation_room_id,
                                        bar_reservation.arrival_time,
                                        bar_reservation.departure_time,
                                        bar_reservation.total,
                                        NVL(bar_reservation.deposit,0) as deposit,
                                        to_CHAR(bar_reservation.deposit_date,'DD/MM/YYYY') as deposit_date,
                                        bar.name
                                     FROM
                                        bar_reservation
                                        inner join bar on bar_reservation.bar_id=bar.id
                                        inner join reservation_room on reservation_room.id=bar_reservation.reservation_room_id
                                        inner join room on reservation_room.room_id = room.id
                                        inner join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
                                        inner join traveller on reservation_traveller.traveller_id = traveller.id
                                        inner join  room_level on room_level.id = room.room_level_id 
                                     WHERE
                                        traveller.id = ".$traveller_id." ".$cond."
                                        AND bar_reservation.pay_with_room=1
                                        AND (reservation_room.foc is null)
                                        AND (reservation_room.foc_all = 0)
                    					AND (room_level.is_virtual is null or room_level.is_virtual = 0)
                                ");
                //System::debug($restaurant);
                foreach($restaurant as $res=>$taurant){
                    $restaurant[$res]['arrival_time'] = date('d/m/Y',$taurant['arrival_time']);
                    $restaurant[$res]['departure_time'] = date('d/m/Y',$taurant['departure_time']);
                    $array_reservation_room[$taurant['reservation_room_id']]['total'] += $taurant['total'];
                    if($taurant['deposit']>0)
                            $array_reservation_room[$taurant['reservation_room_id']]['detail']['deposit'] += $taurant['deposit'];
                    if(isset($array_reservation_room[$taurant['reservation_room_id']]['detail'][$restaurant[$res]['arrival_time']]))
                    $array_reservation_room[$taurant['reservation_room_id']]['detail'][$restaurant[$res]['arrival_time']]['BAR_'.$taurant['id']] = $restaurant[$res]['arrival_time']." BAR ".$taurant['name'].": ".$taurant['total'];
                    else
                    $array_reservation_room[$taurant['reservation_room_id']]['detail'][$restaurant[$res]['departure_time']]['BAR_'.$taurant['id']] = $restaurant[$res]['departure_time']." BAR ".$taurant['name'].": ".$taurant['total'];
                }
                //System::debug($array_reservation_room);
                /** không trả về phòng lấy theo member_coed **/
                $restaurant_notbar = DB::fetch_all("
                                    SELECT
                                        bar_reservation.id,
                                        bar_reservation.arrival_time,
                                        bar_reservation.departure_time,
                                        bar_reservation.total,
                                        NVL(bar_reservation.deposit,0) as deposit,
                                        bar.name
                                     FROM
                                        bar_reservation
                                        inner join bar on bar_reservation.bar_id=bar.id
                                        inner join traveller on bar_reservation.member_code = traveller.member_code
                                     WHERE
                                        traveller.member_code = ".$_REQUEST['account']." ".$cond."
                                        AND bar_reservation.pay_with_room!=1
                                ");
                //System::debug($restaurant_notbar);
                if(sizeof($restaurant_notbar)>0){
                    foreach($restaurant_notbar as $id_res=>$value_res){
                        $array_reservation_room['BAR_'.$value_res['id']]['reservation_room_id']='';
                        $array_reservation_room['BAR_'.$value_res['id']]['type']='BAR';
                        $array_reservation_room['BAR_'.$value_res['id']]['name'] = $value_res['name'];
                        $array_reservation_room['BAR_'.$value_res['id']]['arrival_time'] = date('d/m/Y',$value_res['arrival_time']);
                        $array_reservation_room['BAR_'.$value_res['id']]['departure_time'] = date('d/m/Y',$value_res['departure_time']);
                        $array_reservation_room['BAR_'.$value_res['id']]['in_date'] = date('d/m/Y',$value_res['arrival_time'])." - ".date('d/m/Y',$value_res['departure_time']);
                        $array_reservation_room['BAR_'.$value_res['id']]['total'] = $value_res['total']-$value_res['deposit'] ;
                        $array_reservation_room['BAR_'.$value_res['id']]['detail'] = array();
                    }
                }
                //System::debug($array_reservation_room);
            /////////// end nhà hàng /////////////////
            
            
            //////////////// TIỀN SPA ////////////////
            if($check_date==1){
                    $cond = " AND ( massage_reservation_room.time>='".Date_Time::to_time(Date_Time::convert_orc_date_to_date($arrival,"/"))."' AND massage_reservation_room.time<='".Date_Time::to_time(Date_Time::convert_orc_date_to_date($departure,"/"))."' )";
                }
            $spa = DB::fetch_all("
                            SELECT
                                massage_reservation_room.id,
                                massage_reservation_room.hotel_reservation_room_id as reservation_room_id,
                                massage_reservation_room.time,
                                massage_reservation_room.total_amount as total,
                                massage_room.name
                            FROM
                                massage_reservation_room
                                left join massage_staff_room on massage_staff_room.reservation_room_id = massage_reservation_room.id
                                left join massage_room on massage_room.id = massage_staff_room.room_id
                                inner join reservation_room on reservation_room.id=massage_reservation_room.hotel_reservation_room_id
                                inner join room on reservation_room.room_id = room.id
                                inner join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
                                inner join traveller on reservation_traveller.traveller_id = traveller.id
                                inner join  room_level on room_level.id = room.room_level_id
                            WHERE
                                traveller.id = ".$traveller_id." ".$cond."
                                AND massage_reservation_room.hotel_reservation_room_id is not null
                                AND (reservation_room.foc is null)
                                AND (reservation_room.foc_all = 0)
            					AND (room_level.is_virtual is null or room_level.is_virtual = 0)
                            ");
             //System::debug($spa);
             foreach($spa as $id_spa=>$value_spa){
                $spa[$id_spa]['time'] = Date('d/m/Y',$value_spa['time']);
                $array_reservation_room[$value_spa['reservation_room_id']]['total'] += $value_spa['total'];
                $array_reservation_room[$value_spa['reservation_room_id']]['detail'][$spa[$id_spa]['time']]['SPA_'.$value_spa['id']] = $spa[$id_spa]['time']." SPA_".$value_spa['name'].": ".$value_spa['total'];
             }
             //System::debug($array_reservation_room);
             /** không trả về phòng, lấy theo member_code **/
             $spa_notroom = DB::fetch_all("
                                SELECT 
                                    massage_reservation_room.id,
                                    massage_reservation_room.time,
                                    massage_reservation_room.total_amount as total,
                                    massage_room.name
                                FROM
                                    massage_reservation_room
                                    left join massage_staff_room on massage_staff_room.reservation_room_id = massage_reservation_room.id
                                    left join massage_room on massage_room.id = massage_staff_room.room_id
                                    inner join traveller on traveller.member_code = massage_reservation_room.member_code
                                WHERE
                                    traveller.member_code = ".$_REQUEST['account']." ".$cond."
                                    AND massage_reservation_room.hotel_reservation_room_id is null
                            ");
             //System::debug($spa_notroom);
             if(sizeof($spa_notroom)>0){
                foreach($spa_notroom as $key_spa=>$content_spa){
                    $array_reservation_room['SPA_'.$content_spa['id']]['reservation_room_id']='';
                    $array_reservation_room['SPA_'.$content_spa['id']]['type']='SPA';
                    $array_reservation_room['SPA_'.$content_spa['id']]['name'] = $content_spa['name'];
                    $array_reservation_room['SPA_'.$content_spa['id']]['in_date'] = date('d/m/Y',$content_spa['time']);
                    $array_reservation_room['SPA_'.$content_spa['id']]['total'] = $content_spa['total'];
                    $array_reservation_room['SPA_'.$content_spa['id']]['detail'] = array();
                }
             }
             
             foreach($array_reservation_room as $name=>$detail){
                if($detail['total']==0){
                    unset($array_reservation_room[$name]);
                }
                if(isset($detail['detail']['deposit']) AND $detail['detail']['deposit']==0){
                    unset($array_reservation_room[$name]['detail']['deposit']);
                }elseif(isset($detail['detail']['deposit']) AND $detail['detail']['deposit']!=0){
                    $array_reservation_room[$name]['total'] = $detail['total'] - $detail['detail']['deposit'];
                }
             }
             $data['refund']['detail'] = $array_reservation_room;
        }else{
            $data['refund']['detail'] = array();
        }
        return $data['refund']['detail'];
   	}
    /** end function history_booking **/
    /** *********************************************************************** **/

    /** api.php?cmd=registe_member&first_name=xxx&last_name=xxx&password=xxx&passport=xxx&email=xxx&gender=xxx&birth_date=xxx&address=xxx&phone=xxx&fax=xxx&nationality_id=xxx&note=xxx&is_vn=xxx&traveller_level_id=xxx&province_id=xxx **/
    /** Hàm đăng kí thành viên **/
        function registe_member()
        {
            require_once 'packages/hotel/includes/member.php';
            $data = array('status'=>false,'title'=>'','detail'=>array());
            if(!(isset($_REQUEST['first_name']) AND $_REQUEST['first_name']) OR !(isset($_REQUEST['password']) AND $_REQUEST['password']) OR !(isset($_REQUEST['last_name']) AND $_REQUEST['last_name']) OR !(isset($_REQUEST['passport']) AND $_REQUEST['passport']) OR !(isset($_REQUEST['email']) AND $_REQUEST['email']) OR !(isset($_REQUEST['traveller_level_id']) AND $_REQUEST['traveller_level_id']))
            {
                $data['title'] = 'not input ';
                if(!(isset($_REQUEST['first_name']) AND $_REQUEST['first_name']))
                {
                    $data['title'] .= '- first name';
                }
                if(!(isset($_REQUEST['password']) AND $_REQUEST['password']))
                {
                    $data['title'] .= '- password';
                }
                if(!(isset($_REQUEST['last_name']) AND $_REQUEST['last_name']))
                {
                    $data['title'] .= '- last name';
                }
                if(!(isset($_REQUEST['passport']) AND $_REQUEST['passport']))
                {
                    $data['title'] .= '- passport';
                }
                if(!(isset($_REQUEST['email']) AND $_REQUEST['email']))
                {
                    $data['title'] .= '- email';
                }
                if(!(isset($_REQUEST['traveller_level_id']) AND $_REQUEST['traveller_level_id']))
                {
                    $data['title'] .= '- traveller level id';
                }
            }
            else
            {
                if(DB::exists("SELECT traveller.passport FROM traveller WHERE traveller.passport = '".$_REQUEST['passport']."'"))
                {
                    $data['title'] = 'is conflict passport';
                }
                else
                {
                    $record = array('first_name'=>$_REQUEST['first_name'],'last_name'=>$_REQUEST['last_name'],'passport'=>$_REQUEST['passport'],'email'=>$_REQUEST['email'],'traveller_level_id'=>$_REQUEST['traveller_level_id']);
                    if(isset($_REQUEST['gender']) AND ($_REQUEST['gender']==1 OR $_REQUEST['gender']==0)) $record['gender'] = $_REQUEST['gender']; else $record['gender'] = 1;
                    if(isset($_REQUEST['birth_date']) AND $_REQUEST['birth_date']) $record['birth_date'] = Date_Time::to_orc_date($_REQUEST['birth_date']); else $record['birth_date'] = '';
                    if(isset($_REQUEST['nationality_id']) AND $_REQUEST['nationality_id']) $record['nationality_id'] = $_REQUEST['nationality_id']; else $record['nationality_id'] = 1;
                    if(isset($_REQUEST['address']) AND $_REQUEST['address']) $record['address'] = $_REQUEST['address']; else $record['address']='';
                    if(isset($_REQUEST['phone']) AND $_REQUEST['phone']) $record['phone'] = $_REQUEST['phone']; else $record['phone'] = '';
                    if(isset($_REQUEST['fax']) AND $_REQUEST['fax']) $record['fax'] = $_REQUEST['fax']; else $record['fax'] = '';
                    if(isset($_REQUEST['note']) AND $_REQUEST['note']) $record['note'] = $_REQUEST['note']; else $record['note'] = '';
                    if(isset($_REQUEST['is_vn']) AND ($_REQUEST['is_vn']==0 OR $_REQUEST['is_vn']==1 OR $_REQUEST['is_vn']==2)) $record['is_vn'] = $_REQUEST['is_vn']; else $record['is_vn'] = 0;
                    if(isset($_REQUEST['province_id']) AND $_REQUEST['province_id']) $record['province_id'] = $_REQUEST['province_id']; else $record['province_id'] = '';
                    $member_code = create_member_code();
                    $member_level = DB::fetch("SELECT id FROM member_level WHERE min_point=0");
                    $password = $_REQUEST['password'];
                    $record += array( 'MEMBER_CODE'=>$member_code,'password'=>$password,'point'=>0,'point_user'=>0,'member_level_id'=>$member_level['id'],'member_create_date'=>Date_Time::to_orc_date(date('d/m/Y')) );
                    DB::insert('traveller',$record);
//                    $full_name = Url::get('first_name')." ".Url::get('last_name');
//                    $content = "<h1>"."Xin chào ".$full_name."</h1><br />";
//                    $content .= "<h4>Thông tin đăng nhập của bạn:</h4><br />";
//                    $content .= "<p>Username:</p>".$member_code."<br />";
//                    $content .= "<p>Password:</p>".$password."<br />";
//                    $mail_member = $_REQUEST['email'];
//                    sent_mail_to($mail_member,$content);
                    $data['status'] = true;
                    $data['title'] = 'register is success';
                    $data['detail'] = array('full_name'=>$_REQUEST['first_name']." ".$_REQUEST['last_name'],'account'=>$member_code,'password'=>$password);
                }
            }
            return $data;   
        }
    /** end đăng kí thành viên **/
    
    /** api.php?cmd=get_list&list=XXX **/
    /** Hàm lấy list quốc gia,hạng khách, is_vn, province **/
        function get_list()
        {
            $data = array('status'=>false,'title'=>'','detail'=>array());
            if(!(isset($_REQUEST['list']) AND $_REQUEST['list']))
            {
                $data['title'] = 'not input list';
            }
            else
            {
                $list = $_REQUEST['list'];
                if($list=='nationality_id')
                {
                    $country = DB::fetch_all("SELECT id,name_2 as name FROM country ORDER BY name_2");
                    $data = array('status'=>true,'title'=>'List nationality id','detail'=>$country);
                }
                elseif($list=='traveller_level_id')
                {
                    $traveller_level = DB::fetch_all("SELECT id,name FROM guest_type ORDER BY id");
                    $data = array('status'=>true,'title'=>'List traveller lelvel id','detail'=>$traveller_level);
                }
                elseif($list=='is_vn')
                {
                    $is_vn = array(
                                    1=>array('id'=>0,'name'=>'Alien'),
                                    2=>array('id'=>1,'name'=>'Overseas Vietnamese'),
                                    3=>array('id'=>2,'name'=>'Vietnamese')
                                    );
                    $data = array('status'=>true,'title'=>'List is vn','detail'=>$is_vn);
                }
                elseif($list=='province_id')
                {
                    $province = DB::fetch_all("SELECT id,concat(concat(code,'-'),name) as name FROM province ORDER BY id");
                    $data = array('status'=>true,'title'=>'List province','detail'=>$province);
                }
                else
                {
                    $data['title'] = 'not get list';
                }
            }
            return $data;
        }
    /** end ham  quốc gia,hạng khách, is_vn, province **/
    
    /** api.php?cmd=payment_point&account=XXX&total_amount=xxx&note=xxx **/
    /** Hàm thanh toán bằng điểm sửa dụng **/
        function payment_point()
        {
            require_once 'packages/hotel/includes/member.php';
            $data = array('status'=>false,'title'=>'');
            if(!(isset($_REQUEST['account']) AND ($_REQUEST['account']) AND is_numberic_php($_REQUEST['account'])) OR !(isset($_REQUEST['total_amount']) AND ($_REQUEST['total_amount']) AND is_numberic_php($_REQUEST['total_amount'])) OR !(isset($_REQUEST['note']) AND $_REQUEST['note']))
            {
                $data['title'] = 'is not ';
                if(!(isset($_REQUEST['account']) AND $_REQUEST['account']))
                {
                    $data['title'] .= '- account ';
                }
                if(!(isset($_REQUEST['total_amount']) AND $_REQUEST['total_amount']))
                {
                    $data['title'] .= '- total amount ';
                }
                if(!(isset($_REQUEST['note']) AND $_REQUEST['note']))
                {
                    $data['title'] .= '- note ';
                }
            }
            else
            {
                $member = DB::fetch("SELECT point_user,id FROM traveller WHERE member_code=".$_REQUEST['account']);
                $point_user = $member['point_user'];
                $traveller_id = $member['id'];
                $data = array('status'=>false,'title'=>'');
                $point = point('FOC',$_REQUEST['total_amount'],1);
                $point_user = $point_user + $point['point_user'];
                if($point_user>=0)
                {
                    DB::update('traveller',array('point_user'=>$point_user),'id='.$traveller_id);
                    $data = array('status'=>true,'title'=>'payment success!');
                    $note = $_REQUEST['note'];
                    $max_bill = DB::fetch("SELECT max(bill_id) as bill_id FROM history_member WHERE type='WEBSITE'");
                    if(sizeof($max_bill)==0){
                        $bill_id = 1;
                    }else{
                        $bill_id = $max_bill['bill_id'] + 1;
                    }
                    $history = array('type'=>'WEBSITE','bill_id'=>$bill_id,'folio_id'=>'','create_time'=>Date_Time::to_time(date('d/m/Y')),'user_id'=>'','member_code'=>$_REQUEST['account'],'traveller_id'=>$traveller_id,'note'=>$note);
                    $id_history = DB::insert('history_member',$history);
                    $history_detail = array('payment_id'=>'','payment_type_id'=>'FOC','price'=>$_REQUEST['total_amount'],'change_price'=>CHANGE_POINT_TO_PRICE,'type_point'=>'POINT_USER','payment_type_point'=>'SUB','history_member_id'=>$id_history);
                    DB::insert('history_member_detail',$history_detail);
                }
                else
                {
                    $data = array('status'=>false,'title'=>' is not payment: point < total amount');
                }
            }
            return $data;
        }
    /** Hàm thanh toán bằng điểm sửa dụng **/
	function giaptestapi()
	{
		//thuc hien lay ra name va insert giaptestapi 
		
		$row  = array('name'=>$_REQUEST['name']);
		$id = DB::insert('giaptestapi',$row);
		if($id && $id>0)
			return "0";
		else 	
			return "1";
	}
    /** trả về dữ liệu cho hàm gọi **/
    if(isset($_REQUEST['cmd']))
    {
        switch($_REQUEST['cmd'])
        {
            case "payment_point":
            {
                echo json_encode(payment_point()); break;
            }
            case "get_list":
            {
                echo json_encode(get_list()); break;
            }
            case "registe_member":
            {
                echo json_encode(registe_member()); break;
            }
            case "validation_member":
            {
                echo json_encode(validation_member()); break;
            }
            case "change_password":
            {
                echo json_encode(change_password()); break;
            }
            case "history_booking":
            {
                echo json_encode(history_booking()); break;
            }
            case "forgot_password":
            {
                echo json_encode(forgot_password()); break;
            }
			case "giaptestapi":
			{
				echo json_encode(giaptestapi());
				break;
			}
            default: echo '';break;
        }
    }
    else
    {
        echo "";
    }
?>