<?php
    date_default_timezone_set('Asia/Saigon');//Define default time for global system
    define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    require_once 'packages/core/includes/system/config.php';
          
    $barcode = Url::get('barcode');
    $current_date = date("d/m/Y");
    $current_time = date("H")*3600+date("i")*60+date("s");
    $start_time_breakfast = calc_time(BREAKFAST_FROM_TIME);
    $end_time_breakfast = calc_time(BREAKFAST_TO_TIME);
    
    
    if(Url::get('type'))
    {
        $barcode = trim($barcode,",");
        $bar_code_temp = explode(",",$barcode);
        foreach($bar_code_temp as $key=>$value)
        {
            DB::update("voucher_breakfast",array("status"=>"USED","real_use_date"=>time())," barcode = '".$value."'");
        }
        
    }
    else
    {
        $sql = "SELECT voucher_breakfast.id,
        voucher_breakfast.voucher_id,
        voucher_breakfast.barcode,
        to_char(voucher_breakfast.date_use,'DD/MM/YYYY') as date_use,
        voucher_breakfast.reservation_room_id,
        voucher_breakfast.guest_name,
        voucher_breakfast.is_child,
        reservation_room.reservation_id,
        room.name as room_name,
        voucher_breakfast.status,
        voucher_breakfast.real_use_date,
        0 as status_voucher
        FROM voucher_breakfast 
        INNER JOIN reservation_room ON reservation_room.id = voucher_breakfast.reservation_room_id
        INNER JOIN room ON room.id =  reservation_room.room_id
        WHERE voucher_breakfast.barcode = '".$barcode."'";
        $result = DB::fetch($sql);
        $result['real_use_date'] = date("H:i d/m/Y",$result['real_use_date']);
        
        if($result["date_use"]!=$current_date || ($result["date_use"]==$current_date && $current_time>$end_time_breakfast))
        {
        }
        else
        {
            $result['status_voucher'] = 1;
        }
        
        echo json_encode($result);
    }
    
    
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
    
?>
