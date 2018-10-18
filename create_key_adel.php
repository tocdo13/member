<?php 
   
    $stx='';
    $etx ='';
    $rs ='|';
    $add_client='01';
    $add_source='03';
    $cmd= 'I';
    
    require_once("packages/core/includes/system/config.php");
    require_once 'packages/hotel/packages/reception/modules/ManagerKeyAdel/db.php';
    
    if(Url::get('flag'))//truong hop checkout the 
    {
        if(Url::get('flag')==1)//checkout exist reservation_room
        {
            $str_room = Url::get('str_room');
            $input =  Url::get('input');
            $str1 = explode(":",$str_room);
            $ip_port = DB::fetch("select * from manage_ipsever_adel where id=".$str1[1]);
            $str = $str1[0];
            $reservation_room_id = $str1[2];
            $add_client = sprintf("%02d",$ip_port['add_client']);
            $add_source = sprintf("%02d",$ip_port['add_source']);
            $str_success = $add_source.$add_client.'0';
            $cmd='B';
            $str = $stx.$add_client.$add_source.$cmd.$rs.'R'.$add_client.$str.$etx;
            
            
            //doi 5s moi gui lenh tao the 
            sleep(5);
            $receive =process_client($str,$ip_port['ip'],$ip_port['port']);
            //$receive = $str_success;
            $post = strpos($receive,$str_success);
            if($post===false)
            {
                echo $input.'_-1';  
            }
            else
            {
                //update database 
                update_manage_key($reservation_room_id);
                echo $input.'_1';
            }
            //echo $str_room;
        }
        else//checkout not exist reservation room
        {
            $str_room = Url::get('str_room');
            $input =  Url::get('input');
            $str1 = explode(":",$str_room);
            $ip_port = DB::fetch("select * from manage_ipsever_adel where id=".$str1[1]);
            $str = $str1[0];
            $room_id = $str1[2];
            $add_client = sprintf("%02d",$ip_port['add_client']);
            $add_source = sprintf("%02d",$ip_port['add_source']);
            $str_success = $add_source.$add_client.'0';
            $cmd='B';
            $str = $stx.$add_client.$add_source.$cmd.$rs.'R'.$add_client.$str.$etx;
            
            sleep(5);
            $receive =process_client($str,$ip_port['ip'],$ip_port['port']);
            //$receive = $str_success;
            $post = strpos($receive,$str_success);
            if($post===false)
            {
                echo $input.'_-1';  
            }
            else
            {
                //update database 
                update_manage_key_room($room_id);
                echo $input.'_1';
            }
        }
    }
    else //truong hop create the 
    {
        $str_room = Url::get('str_room');
        $input =  Url::get('input');
        //dau ra 
        $str1 = explode(":",$str_room);
        
        $ip_port = DB::fetch("select * from manage_ipsever_adel where id=".$str1[1]);
        $str = $str1[0];
        $rbt = $str1[3];
        
        $add_client = sprintf("%02d",$ip_port['add_client']);
        $add_source = sprintf("%02d",$ip_port['add_source']);
        
        $str_success = $add_source.$add_client.'0';
        
        $row = get_object($str,$str1[2]);
        
        //'0103I|R010201|NGuest name|D201410300800|O102410301200:reception_id
        $s_str = explode("_",$str);
        if($rbt==1)
            $cmd='I';
        else
            $cmd='G';
        $str = $stx.$add_client.$add_source.$cmd.$rs.'R'.$add_client.$s_str[0].$rs.$s_str[1].$rs.$s_str[2].$etx;
        //doi 5s moi gui lenh tao the 
        sleep(5);
        $receive =process_client($str,$ip_port['ip'],$ip_port['port']);
        //$receive = $str_success;
        $post = strpos($receive,$str_success);
        
        if($post===false)
        {
            echo $input.'_-1';  
        }
        else
        {
            //insert database 
            $row['guest_index'] = $input-100;
            DB::insert('manage_key_adel',$row);
            echo $input.'_1';
        }
    }
    function update_manage_key_room($room_id)
    {
        //lay ra danh sach cac reservation_room theo room_id
        $sql ='select id from reservation_room where room_id='.$room_id;
        $arr_reservation_room = DB::fetch_all($sql);
        $cond ='reservation_room_id IN (';
        foreach($arr_reservation_room as $key=>$value)
        {
            $cond .=$arr_reservation_room[$key]['id'].',';
        }
        $cond .='0)';
        $delete_user = User::id();
        $delete_time =  mktime(date('H'),date('i'),0,date('m'),date('d'),date('Y'));
        $values= array('delete_user'=>$delete_user,'delete_time'=>$delete_time);
        $conditions =$cond.' AND delete_user is null AND delete_time is null';
        DB::update('manage_key_adel',$values,$conditions);
    }
    function update_manage_key($reservation_room_id)
    {
        $delete_user = User::id();
        $delete_time =  mktime(date('H'),date('i'),0,date('m'),date('d'),date('Y'));
        $values= array('delete_user'=>$delete_user,'delete_time'=>$delete_time);
        $conditions =' reservation_room_id='.$reservation_room_id.' AND delete_user is null AND delete_time is null';
        DB::update('manage_key_adel',$values,$conditions);
    }
    
    function update_manage_key_guest($room_id,$guest_name)
    {
        //lay ra nhung dong manage_key can update
        $sql ="SELECT mg.*
            FROM reservation_room rr 
            INNER JOIN manage_key_adel mg ON mg.delete_user is null AND mg.reservation_room_id=rr.id 
            AND rr.room_id=".$room_id." AND mg.guest_name='".trim($guest_name)."'";
        $row = DB::fetch($sql);
        
        $row['delete_user'] = User::id();
        $row['delete_time'] = mktime(date('H'),date('i'),0,date('m'),date('d'),date('Y'));
        $id = $row['id'];
        unset($row['id']);
        DB::update('manage_key_adel',$row,'id='.$id);
    }
    
    function get_object($str,$str1)
    {
        $row = array();
        //$str=0103I|R010201|NGuest name|D201410300800|O102410301200
        $row = array('reservation_room_id'=>$str1);
        $row = $row + array('create_user'=>User::id());
        $row = $row + array('create_time'=>mktime(date('H'),date('i'),0,date('m'),date('d'),date('Y')));
        //$str = substr($str,1,strlen($str)-2);
        $s = explode("_",$str);
        $k=1;
        
        $begin = substr($s[$k],1);
        $end = substr($s[$k+1],1);
        
        $year = substr($begin,0,4);
        $month = substr($begin,4,2);
        $day = substr($begin,6,2);
        $hour = substr($begin,8,2);
        $minute = substr($begin,10,2);
        
        $s_begin = mktime($hour,$minute,0,$month,$day,$year);
        
        $year = substr($end,0,4);
        $month = substr($end,4,2);
        $day = substr($end,6,2);
        $hour = substr($end,8,2);
        $minute = substr($end,10,2);
        $s_end = mktime($hour,$minute,0,$month,$day,$year);
        $row = $row + array('begin_time'=>$s_begin,'end_time'=>$s_end);
        
        return $row;
    }
    
        
?>
