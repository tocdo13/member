<?php
    require_once("packages/core/includes/system/config.php");
    require_once 'packages/hotel/packages/reception/modules/ManagerKeyHune/db.php';
    if(Url::get('flag'))//truong hop checkout the 
    {
        $reception_id = Url::get('str_room');
        $input =  Url::get('input');
        
        $ip_port = DB::fetch("select * from manage_ipsever where id=".$reception_id);
        //doi 5s moi gui lenh tao the 
        $str = '3|0';
        sleep(5);
        $receive = process_client($str,$ip_port['ip'],$ip_port['port']);
        $str_receive = explode("_",$receive);
        //value1_value2: 
        //neu value1=0 la thanh cong nguoc lai la khong thanh cong
        //value2: cardNo tra ve cho phep update infor card checkout
        if($str_receive[0]==0)
        {
            //5_0_01010300100_14-12-26_13:37:00_14-12-28_12:00:00_3_1_1_1
            $cardNo = $str_receive[1];
            update_card_after_checkout($cardNo);
            echo $input.'_1';
        }
        else
        {
            echo $input.'_-1';
        }
        
    }
    else//truong hop tao the 
    {
        $str_room = Url::get('str_room');
        $input =  Url::get('input');
        
        $reception_id = Url::get('reception');
        $str_room_s = explode("|",$str_room);
        $str = $str_room_s[0];
        $room_name = $str_room_s[1];
        $reservation_room_id = $str_room_s[2];
        $terminateOld = $str_room_s[3];
        $room_pass ='00000000';
        if($terminateOld==0) //neu la the copied thi lay ra Room_pass thay the vao chuoi 
        {
            //lay theo reservation_room_id va chua checkout 
            $room_pass = DB::fetch("SELECT room_pass FROM manage_key_hune WHERE reservation_room_id=".$reservation_room_id." ORDER BY create_time desc");
            if(isset($room_pass['room_pass']))
                $room_pass = $room_pass['room_pass'];
            else
                $room_pass ='00000000';
            //thay the vao str 
            $str = str_replace("00000000",$room_pass,$str);
        }
    
        
        $ip_port = DB::fetch("select * from manage_ipsever where id=".$reception_id);
        
        //echo $ip_port['ip'].'--'.$ip_port['port'];
        //doi 5s moi gui lenh tao the 
        $str1 ='1|'.$str;
        sleep(5);
        $receive = process_client($str1,$ip_port['ip'],$ip_port['port']);
        $str_receive = explode("_",$receive);
        if($str_receive[0]==0)
        {
            //5_0_01010300100_14-12-26_13:37:00_14-12-28_12:00:00_3_1_1_1
            /*public int Cardno;
            public string  RoomPass;
            public string  Address;
            public string  SDIn;
            public string STIn;
            public string SDOut;
            public string STOut;
            public int Level_Pass;
            public int PassMode;
            public int AddressQty;
            public int TerminateOld;*/
            $room_pass = $str_receive[1];
            $row = get_object_hune($str,$room_name,$reservation_room_id,$room_pass);
            DB::insert('manage_key_hune',$row);
            echo $input.'_1';
        }
        else
        {
            echo $input.'_-1';
        }
    }
    function update_card_after_checkout($cardNo)
    {
        $values = array();
        $values['checkout_user'] = User::id();
        $values['checkout_time'] = mktime(date('H'),date('i'),0,date('m'),date('d'),date('Y'));
        
        $condition =" card_no=".$cardNo;
        DB::update('manage_key_hune',$values,$condition);
    }
    function get_object_hune($str,$room_name,$reservation_room_id,$room_pass)
    {
        $row = array();
        $row['reservation_room_id'] = $reservation_room_id;
        $row['room_name'] = $room_name;
        $row['create_user']=User::id();
        $row['create_time'] = mktime(date('H'),date('i'),0,date('m'),date('d'),date('Y'));
        //5_0_01010300100_14-12-26_13:37:00_14-12-28_12:00:00_3_1_1_1
        $s = explode("_",$str);
        $row['card_no'] = $s[0];
        $row['room_pass'] = $room_pass;
        $row['address'] = $s[2];
        $s3_str = explode("-",$s[3]);
        $s4_str = explode(":",$s[4]);
        //begin_time, end_time
        $y_b = '20'.$s3_str[0];
        $y_b = intval($y_b);
        $row['begin_time'] = mktime($s4_str[0],$s4_str[1],$s4_str[2],$s3_str[1],$s3_str[2],$y_b);
        
        $s5_str = explode("-",$s[5]);
        $s6_str = explode(":",$s[6]);
        $y_e = '20'.$s5_str[0];
        $y_e = intval($y_e);
        $row['end_time'] = mktime($s6_str[0],$s6_str[1],$s6_str[2],$s5_str[1],$s5_str[2],$y_e);
        
        //level_pass, pass_mode,address_qty,terminate_old
        $row['level_pass'] = $s[7];
        $row['pass_mode'] = $s[8];
        $row['address_qty'] = $s[9];
        $row['terminate_old'] = $s[10];
                
        return $row ;
    }
        
?>