<?php
    require_once("packages/core/includes/system/config.php");
    require_once 'packages/hotel/packages/reception/modules/ManagerKeyOrbita/db.php';
    date_default_timezone_set('Asia/Saigon');//Define default time for global system
    
    $str = Url::get('str_room');

    $s = explode("_",$str);
    $cmd = $s[0];
    $input = Url::get('input');
    switch($cmd)
    {
        case 1://truong hop tao the 
        {
            $reception_id = Url::get('reception');
            $ip_port = DB::fetch("select * from manage_ipsever where id=".$reception_id);
            $receive = process_client($str,$ip_port['ip'],$ip_port['port']);
            $arr_receive = explode("_",$receive);
            
            if($arr_receive[0]==0)
            {
                $cardNo = $arr_receive[1];
                $row = get_object_fox($str,$cardNo);
                
                $id = DB::insert('MANAGE_KEY_FOX',$row);
                DB::update_id("reservation_room", array("checkin_time_card"=>$row['begin_time']),$row['reservation_room_id']);
            }
            echo $input.'_'.$arr_receive[0];
            break;
        }
        case 3: //truong hop check out the 
        {
            $reception_id = Url::get('reception');
            $ip_port = DB::fetch("select * from manage_ipsever where id=".$reception_id);
            $receive = process_client($str,$ip_port['ip'],$ip_port['port']);
            $arr_receive = explode("_",$receive);
            update_card_after_checkout($arr_receive[1]);
            echo $input.'_'.$arr_receive[0];
            break;
        }
        default:
            break;
    }
    
    function update_card_after_checkout($cardNo)
    {
        $values = array();
        $values['checkout_user'] = User::id();
        $values['checkout_time'] = mktime(date('H'),date('i'),0,date('m'),date('d'),date('Y'));
        
        $condition =" card_no='".$cardNo."' AND checkout_user is null";
        DB::update('MANAGE_KEY_FOX',$values,$condition);
    }
    function get_object_fox($str,$cardNo)
    {
        $row = array();
        $row['reservation_room_id'] = Url::get('reservation_room_id');
        
        $row['create_user'] = User::id();
        $row['create_time'] = mktime(date('H'),date('i'),0,date('m'),date('d'),date('Y'));
        $row['card_no'] = $cardNo;
        //msg = "1_201_2015-07-06 08:00:00_2015-07-07 12:00:00_1";
        $s = explode("_",$str);
        
        $row['commdoor'] = $s[3];
        
        $str_begin = $s[4];
        //System::debug($str_begin);
        $str_begin_str = explode(" ",$str_begin);
        $str_begin_date = explode("-",$str_begin_str[0]);
        $str_begin_time = explode(":",$str_begin_str[1]);
        $row['begin_time'] = mktime($str_begin_time[0],$str_begin_time[1],0,$str_begin_date[1],$str_begin_date[2],$str_begin_date[0]);
        //$row['begin_time'] +=3600;
        
        $str_end = $s[5];
        $str_end_str = explode(" ",$str_end);
        $str_end_date = explode("-",$str_end_str[0]);
        $str_end_time = explode(":",$str_end_str[1]);
        $row['end_time'] = mktime($str_end_time[0],$str_end_time[1],$str_end_time[2],$str_end_date[1],$str_end_date[2],$str_end_date[0]);
        //$row['end_time'] +=3600;
        return $row ;
    }
        
?>