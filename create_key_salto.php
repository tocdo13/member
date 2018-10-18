<?php 
    require_once("packages/core/includes/system/config.php");
    require_once 'packages/hotel/packages/reception/modules/ManagerKeySalto/db.php';
    
    //dau vao
    $str_room = Url::get('str_room');
    $input =  Url::get('input');
    $reservation_room_id = Url::get('rr_r_id');
    $encoder = Url::get('encoder');
    $arr = array();
    $str = explode("_",$str_room);
    for($i=0;$i<16;$i++)
    {
        if($str[$i]!=' ')
        {
            $arr[$i] =$str[$i];
        }
        else
        {
            $arr[$i] ='';
        }
    }
    $in = generate_card($arr);
    //echo $in;
    $ip_port = DB::fetch("select * from manage_ipsever where id=".$encoder);
    $data ='';
    $num_result = proccess_cmd($in,$ip_port['ip'],$ip_port['port'],$data);
    if($num_result !=0)
    {
        echo $input.'_-1';
    }
    else
    {
        $str_result = proccess_data($data);
        if($str_result=='Create card success')
        {
            //thuc hien insert database 
            $arr_result = explode('³',$in);
            $guest_sn = explode('³',$data);
            $serial = $guest_sn[3];

            $row = get_object_salto($serial,$arr_result,$input,$reservation_room_id);
            DB::insert('manage_key_salto',$row);
            echo $input.'_1';
        }
        else
        {
            echo $input.'_-1';
        }
    }
    function get_object_salto($serial,$arr_result,$input,$reservation_room_id)
    {
        $row = array();
        $s_date_start = $arr_result[10];
        $s_date_end = $arr_result[11];
        
        $year = '20'.substr($s_date_start,8,2);
        $year =intval($year);
        
        $year_end='20'.substr($s_date_end,8,2);
        $year_end = intval($year_end);
        
        $number_keys = substr($arr_result[1],2,1);
        $number_keys = intval($number_keys);
        
        $row['begin_time'] = mktime(substr($s_date_start,0,2),substr($s_date_start,2,2),0,substr($s_date_start,4,2),substr($s_date_start,6,2),$year);
        $row['end_time'] = mktime(substr($s_date_end,0,2),substr($s_date_end,2,2),0,substr($s_date_end,4,2),substr($s_date_end,6,2),$year_end);
        $row['number_keys'] = $number_keys;
        
        $can_open_rooms ='';
        if(isset($arr_result[5]) && $arr_result[5]!='')
            $can_open_rooms .=$arr_result[5].' ';
        if(isset($arr_result[6]) &&  $arr_result[6]!='')
            $can_open_rooms .=$arr_result[6].' ';
        if(isset($arr_result[7]) && $arr_result[7]!='')
            $can_open_rooms .=$arr_result[7].' ';
            
        $row['can_open_rooms'] = $can_open_rooms;
        
        $row['type'] ='CN';
        $row['create_user']=User::id();
        $row['create_time'] = mktime(date('H'),date('i'),0,date('m'),date('d'),date('Y'));
        $row['reservation_room_id'] = $reservation_room_id;
        $row['portal_id'] = PORTAL_ID;
        $row['guestsn'] = $serial;
        
        return $row;
    } 
?>
