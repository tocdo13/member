<?php
class TelephoneLib
{
    function strToDec($string)
    {
        $hex='';
        for ($i=0; $i < strlen($string); $i++)
        {
            $hex .= ord($string[$i]);
        }
        return $hex;
    }
    
	function parse_row($rows)
	{
		if($rows and count($rows)>=1)
		{
            $index = TelephoneLib::strToDec($rows[1]);
            $time_y = substr($rows[2],0,4);
            $time_m = substr($rows[2],4,2);
            $time_d = substr($rows[2],6,2);
            $time_h = substr($rows[2],8,2);
            $time_mi = substr($rows[2],10,2);
            $time_s = substr($rows[2],12,2);
            $phone_id = substr($rows[2],14,strlen($rows[2])-14);
            $dial_phone = $rows[4];
            $dur_h = substr($rows[5],0,2);
            $dur_mi = substr($rows[5],2,2);
            $dur_s = substr($rows[5],4,2);
            $descrip = $rows[6];
            $total = $rows[8];
            $tk = $rows[17];
            
			return array(
				'phone_number_id'=>$phone_id,
				'trungke'=>$tk,
				'dial_number'=>$dial_phone,
				'description'=>$descrip,
				'hdate'=>strtotime($time_m.'/'.$time_d.'/'.$time_y)+$time_h*3600+$time_mi*60+$time_s,
				'price'=>$total,
				'price_vnd'=>$total,
                'total_before_tax'=>$total,
				'ring_durantion'=>$dur_h*3600+$dur_mi*60+$dur_s,
                'bill_id'=>$index,
				'portal_id'=>"#default"
			);
		}
		return false;
	}
    
    function update_telephone_auto()
	{
        if(date('G')==0 and date('i')<5)
        {
            $time = strtotime(date("m/d/y"))-86400;
            TelephoneLib::update_telephone_daily_auto(date('d',$time),date('m',$time),date('Y',$time));
        }
        TelephoneLib::update_telephone_daily_auto(date('d'),date('m'),date('Y'));
    }
    
    static function update_telephone_daily_auto($date, $month, $year)
	{
        $file = 'C:/Users/Administrator/Desktop/result.txt';
        file_put_contents($file, "hello");
        $str_day = $date;
        $str_mon = $month;
        $str_year = substr($year,2,2);
        
        $path = 'C:/CDR/datacdr/'.$year.$str_mon;
   		if(!is_dir($path))
   		{
  			mkdir($path); 
   		}
        
        //connect
        $conn = oci_connect('paradise', 'hotel2013', 'localhost/XE');
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        //max id
        $stid = oci_parse($conn, "select max(id) as max from TELEPHONE_REPORT_DAILY where portal_id='#default'");
        oci_execute($stid);
        $ID = 0;
        while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
            $ID = $row['MAX'];
            $ID++;
            break;
        }
        
        if(file_exists($path.'/'.$str_day.$str_mon.$str_year.".dbf"))
        {
            $content = file_get_contents($path.'/'.$str_day.$str_mon.$str_year.".dbf");
            //$content = str_replace(chr(hexdec("00")),"-",$content);
            $pattern = '/(.{6})([0-9]+)([\s]+)(.{20})([0-9]{6})([A-z0-9\-_\s]+)([\s]+)([0-9\\\.]+)([\s]+)([0-9\\\.]+)([\s]+)([0-9\\\.]+)([\s]+)([0-9\\\.]+)([\s]+)([0-9\\\.]+)V([0-9]+)/s';
    							
            if(preg_match_all($pattern,$content,$matches,PREG_SET_ORDER))
            {
 				foreach($matches as $key=>$value)
 				{
					$row = TelephoneLib::parse_row($value);
                    //System::debug($row);
                        
                    //check exit bill
                    $stid = oci_parse($conn, "select * from telephone_report_daily where portal_id='#default' and bill_id = ".$row['bill_id']);
                    oci_execute($stid);
                    $bol = 1;
                    while ($re = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
                        if($re)
                        {
                            $bol = 0;
                            break;
                        }
                    }
                            
					if($bol)
					{
                        $stid = oci_parse($conn, "select reservation_room.id as total 
                                                            from reservation_room 
                                                                inner join room on room.id = reservation_room.room_id
                                                                inner join telephone_number on telephone_number.room_id = reservation_room.room_id
                                                            where room.portal_id='#default' 
                                                                and reservation_room.status = 'CHECKOUT' 
                                                                and telephone_number.phone_number ='".$row['phone_number_id']."' 
                                                                and reservation_room.time_in <=".$row['hdate']." 
                                                                and reservation_room.time_out >=".$row['hdate']);
                        oci_execute($stid);
                        $res=0;
                        while ($temp = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
                            $res = $temp['TOTAL'];
                            break;
                        }
                            
                        if(!$res)
                        {
                            $stid = oci_parse($conn, "select reservation_room.id as total 
                                                                from reservation_room 
                                                                    inner join room on room.id = reservation_room.room_id
                                                                    inner join telephone_number on telephone_number.room_id = reservation_room.room_id
                                                                where room.portal_id='#default' 
                                                                    and reservation_room.status = 'CHECKIN' 
                                                                    and telephone_number.phone_number ='".$row['phone_number_id']."' 
                                                                    and reservation_room.time_in <=".$row['hdate']." 
                                                                    and reservation_room.time_out >=".$row['hdate']);
                            oci_execute($stid);
                            $res = 0;
                            while ($temp = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
                                $res = $temp['TOTAL'];
                                break;
                            }
                        }
                                
                        $strSql = "insert into telephone_report_daily (ID,";
                        $bol = 0;
                        foreach($row as $k=>$v)
                        {
                            if($bol == 0)
                            {
                                $strSql.=strtoupper($k);
                                $bol = 1;
                            }
                            else
                            {
                                $strSql.=",".strtoupper($k);
                            }
                        }
                        $strSql.= ") VALUES ('".($ID++)."',";
                        $bol = 0;
                        foreach($row as $k=>$v)
                        {
                            if($bol == 0)
                            {
                                $strSql.="'".$v."'";
                                $bol = 1;
                            }
                            else
                            {
                                $strSql.=",'".$v."'";
                            }
                        }
                        $strSql.= ")";
                        $stmt = oci_parse($conn, $strSql);
                        oci_execute($stmt, OCI_DEFAULT);
					}
 				}
            }
        }
        oci_commit($conn);
    }
    
	static function update_telephone_daily($month,$year)
	{
        if($month < 1 or $month > 12 or $year > date('Y') or strlen($year) < 4 or (($year == date('Y')) and ($month > date('n'))))
            return;
        $str_mon = $month >= 10? $month : '0'.$month;
        $str_year = substr($year,2,2);
        
        //check su ton tai thu muc
        $path = 'C:/CDR/datacdr/'.$year.$str_mon;
   		if(!is_dir($path))
   		{
  			mkdir($path); 
   		}
        $day = 1;
        
        while(checkdate($month,$day,$year))
        {
            $str_day = $day >= 10? $day : '0'.$day;
            
            if(file_exists($path.'/'.$str_day.$str_mon.$str_year.".dbf"))
    		{
                $content = file_get_contents($path.'/'.$str_day.$str_mon.$str_year.".dbf");
                //echo $content;exit();
                //$content = str_replace(chr(hexdec("00")),"-",$content);
                /*
                for($i = 0; $i<strlen($content); $i++)
                {
                    if(strlen(dechex(ord($content[$i])))<2)
                        echo "0".dechex(ord($content[$i]));
                    else
                        echo dechex(ord($content[$i]));
                } 
                exit();
                */
                //$pattern = '/(.)(.)---([0-9]+)([\s]+)([0-9]+)([\s]+)([0-9]+)([A-z\s]+)([\s]+)([0-9\\\.]+)([\s]+)([0-9\\\.]+)([\s]+)([0-9\\\.]+)([\s]+)([0-9\\\.]+)([\s]+)([0-9\\\.]+)V([0-9]+)/s';
    			//$pattern = '/(.)(.)---([0-9]+)([\s]+)/s';
                //$content = " ".chr(hexdec("0a"))."---123";
                //(them index)$pattern = '/(.)(.)---([0-9]+)([\s]+)([0-9\s]{20})([0-9]{6})([A-z0-9\-_\s]+)([\s]+)([0-9\\\.]+)([\s]+)([0-9\\\.]+)([\s]+)([0-9\\\.]+)([\s]+)([0-9\\\.]+)([\s]+)([0-9\\\.]+)V([0-9]+)/s';
    			$pattern = '/(.{6})([0-9]+)([\s]+)(.{20})([0-9]{6})([A-z0-9\-_\s]+)([\s]+)([0-9\\\.]+)([\s]+)([0-9\\\.]+)([\s]+)([0-9\\\.]+)([\s]+)([0-9\\\.]+)([\s]+)([0-9\\\.]+)V([0-9]+)/s';
    			
                if(preg_match_all($pattern,$content,$matches,PREG_SET_ORDER))
    			{
    			     //System::debug($matches);exit();
    				foreach($matches as $key=>$value)
    				{
    					$row = TelephoneLib::parse_row($value);
                        
    					if(!DB::exists("select * from telephone_report_daily where portal_id='#default' and bill_id = ".$row['bill_id']))
    					{
                            $res = DB::fetch("select reservation_room.id as total 
                                                            from reservation_room 
                                                                inner join room on room.id = reservation_room.room_id
                                                                inner join telephone_number on telephone_number.room_id = reservation_room.room_id
                                                            where room.portal_id='#default' 
                                                                and reservation_room.status = 'CHECKOUT' 
                                                                and telephone_number.phone_number ='".$row['phone_number_id']."' 
                                                                and reservation_room.time_in <=".$row['hdate']." 
                                                                and reservation_room.time_out >=".$row['hdate'],"total");
                            if(!$res)
                                $res = DB::fetch("select reservation_room.id as total 
                                                            from reservation_room 
                                                                inner join room on room.id = reservation_room.room_id
                                                                inner join telephone_number on telephone_number.room_id = reservation_room.room_id
                                                            where room.portal_id='#default' 
                                                                and reservation_room.status = 'CHECKIN' 
                                                                and telephone_number.phone_number ='".$row['phone_number_id']."' 
                                                                and reservation_room.time_in <=".$row['hdate']." 
                                                                and reservation_room.time_out >=".$row['hdate'],"total");
                            $row['reservation_room_id'] = $res;
                            
    						DB::insert('telephone_report_daily',$row);
    					}
    				}
    			}
    		}
            $day++;
        }
	}	
}
?>