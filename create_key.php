<?php 
   
    require_once("packages/core/includes/system/config.php");
    require_once 'packages/hotel/packages/reception/modules/ManagerKey/db.php';
    $str = $_REQUEST['str_room'];
    $i = $_REQUEST['input'];
    $str1 = explode(":",$str);
    
    $ip_port = DB::fetch("select * from manage_ipsever where id=".$str1[2]);
    //xac dinh cardNo here
    $cardNo = DB::fetch('select max(id) as max from manage_key');
    $cardNo = $cardNo['max'] + 1;
    $str ='1-'.$cardNo.'-'.(substr($str1[0],2));

    $receive  = process_client($str,$ip_port['ip'],$ip_port['port']);
    if($receive==-1)
    {
        echo $i.'_-1_0';
    }
    else
    {
        $s = explode("-",$str1[0]);//1-000201-1409260800-1409261800-2
        $reservation_room_id = $str1[1];
        $beginTime = $s[2];//1409260800
        $year = substr($s[2],0,2);
        $month  = substr($s[2],2,2);
        $day = substr($s[2],4,2);
        $hour = substr($s[2],6,2);
        $minute = substr($s[2],8,2);
        $year = '20'.$year;
        //mktime(hour,minute,second,month,day,year,is_dst);
        $beginTime =mktime($hour,$minute,0,$month,$day,$year);
        
        $endTime = $s[3];
        $year = substr($s[3],0,2);
        $month  = substr($s[3],2,2);
        $day = substr($s[3],4,2);
        $hour = substr($s[3],6,2);
        $minute = substr($s[3],8,2);
        $year = '20'.$year;
        $endTime = mktime($hour,$minute,0,$month,$day,$year);
        
        $num_card = $s[4];
        
        $row = array('reservation_room_id'=>$reservation_room_id,'begin_time'=>$beginTime,'end_time'=>$endTime,'create_user'=>User::id(),'guest_index'=>1);
        $row = $row + array('create_time'=>mktime(date('H'),date('i'),0,date('m'),date('d'),date('Y')));
        
        $flag = explode("_",$receive);
        $row = $row + array('guestsn'=>(int)$flag[1]);
        $round = (int)$flag[0]==0?$num_card:(int)$flag[0];
        
        for($j=1;$j<=$round;$j++)
        {
            $row['guest_index'] = $j;
            DB::insert('manage_key',$row);
        }
        
        if(intval($flag[0])==0) 
            echo $i.'_1'; 
        else
        {
            echo $i.'_2_'.intval($flag[0]);
        }
    }
        
?>
