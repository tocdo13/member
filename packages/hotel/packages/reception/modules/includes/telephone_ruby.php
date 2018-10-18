<?php
class TelephoneLib
{
	function update_telephone($month='',$year='')
	{
		$path = 'telephone/';
		$path = $path.substr(PORTAL_ID,1);
		if(!is_dir($path))
		{
			mkdir($path);
		}
		/*if($month and $year)
		{
			$local_file = ((strlen($month)==1)?'':'').$month.'_'.$year.'.dat';
		}
		else*/
		{
			//$local_file = 'Pbxlog_'.date('M').'-'.date('Y').'.txt';
            $local_file = 'Test_tong_dai.txt';
		}
		$remote_file = $local_file;
		$local_file = $path.'/'.$local_file;
		//TelephoneLib::ftp($remote_file,$local_file);
		TelephoneLib::update_telephone_daily($local_file);
	}
	static function ftp($remote_file='',$local_file,$server_name= TELEPHONE_LINK,$user_name = TELEPHONE_FTP_USER,$password = TELEPHONE_FTP_PASSWORD)
	{
		$conn_id = ftp_connect($server_name); 
		$login_result = ftp_login($conn_id,$user_name,$password); 
		if ((!$conn_id) || (!$login_result)) 
		{
			die('<div class="notice">Could not connect to '.$server_name.'</div>');
		} 
		else 
		{
        	//echo "Connected to ".$server_name.", for user ".$user_name ." password :".$password;
   		}
		$handle = @fopen($local_file, 'w');
		if (@ftp_fget($conn_id, $handle, $remote_file, FTP_ASCII, 0)) 
		{
			 //echo "successfully written to $local_file\n";
		}
		@ftp_close($conn_id);
		@fclose($handle);	
	}
    /*
	//convert date thang/ngay/nam
	function to_telephone_time($date)
	{
		$time = 0;
		if($date)
		{
			$date = str_replace(' ','',$date);
			$a = explode('/',$date);
			$day = $a[1];
			$month = $a[0];
			$year = ($a[2]<100)?$a[2]+2000:$a[2];
			if(checkdate($month,$day,$year))
			{
				$time = Date_Time::to_time($day.'/'.$month.'/'.$year);
				return $time;
			}
			return false;
		}
		return false;
	}
    */
    
	//convert date ngay/thang/nam
	function to_telephone_time($date)
	{
        $date  = trim($date);
		$time = 0;
		if($date)
		{
            $a = explode('/',$date);
            $date = date('d/m/Y',strtotime($a[1].'/'.$a[0].'/'.$a[2]));
			$time = Date_Time::to_time($date);
			return $time;
		}
		return false;
	}
	function make_price($phone_number,$durantion,$phones)
	{
		$duration = $durantion;
		$phone_price_default = 0;
		$block_second = 60;
		if($phone_number==0)
		{
			return 0;
		}
		$prefix = substr($phone_number,0,5);	
		if($prefix == 17100 or $prefix == 17800 or $prefix == 17900)
		{
			//QT 171
			$phone_price_default = ($duration<=$block_second)?($phones['QT171']['start_fee']):(($phones['QT171']['start_fee']*ceil($duration/$block_second))+$phones['QT171']['fee']);
		}
		elseif(strpos($phone_number,'00',0)!==false and (strpos($phone_number,'00',0)===0))
		{
			//QT
			$phone_price_default = ($duration<=$block_second)?($phones['QT']['start_fee']):(($phones['QT']['start_fee']*ceil($duration/$block_second))+$phones['QT']['fee']);
		}
		else
		{
			$check_phone_number = substr($phone_number,0,1);
			$check_phone_number_1 = substr($phone_number,0,2);
			//kiem tra noi hat
			if((($check_phone_number == 3 or $check_phone_number == 6 or $check_phone_number == 7 or $check_phone_number == 2) && strlen($phone_number) == 8) or (strpos($phone_number,'04',0)!==false and (strpos($phone_number,'04',0)===0) && strlen($phone_number)==10))
			{
				$phone_price_default = ($duration<=$block_second)?($phones['NH']['start_fee']):(($phones['NH']['start_fee']*ceil($duration/$block_second))+$phones['NH']['fee']);
			}
			else if(strlen($phone_number)>7)
			{
				if((strlen($phone_number)==10 and $check_phone_number_1=='09') or (strlen($phone_number)>=11 and $check_phone_number_1=='01'))
				{
					$phone_price_default = ($duration<=$block_second)?($phones['DD']['start_fee']):(($phones['DD']['start_fee']*ceil($duration/$block_second))+$phones['DD']['fee']);
				}
				else
				{
					$phone_price_default = ($duration<=$block_second)?($phones['NGH']['start_fee']):(($phones['NGH']['start_fee']*ceil($duration/$block_second))+$phones['NGH']['fee']);
				}
			}
		}
		return $phone_price_default;
	}
	function make_type($phone_number,$phones)
	{
		$phone_price_default = 0;
		if($phone_number==0)
		{
			return 'NH';
		}
		$type = 'NH';
		$prefix = substr($phone_number,0,5);
		if($prefix == 17100 or $prefix == 17800 or $prefix == 17900)
		{
			//QT 171
			$type = 'QT';
		}
		elseif(strpos($phone_number,'00',0)!==false and (strpos($phone_number,'00',0)===0))
		{
			//QT
			$type = 'QT';
		}
		else
		{
			$check_phone_number = substr($phone_number,0,1);
			$check_phone_number_1 = substr($phone_number,0,2);
			//kiem tra noi hat
			if((($check_phone_number == 3 or $check_phone_number == 6 or $check_phone_number == 7 or $check_phone_number == 2) && strlen($phone_number) == 7) or (strpos($phone_number,'064',0)!==false and (strpos($phone_number,'064',0)===0) && strlen($phone_number)==10))
			{
				$type = 'NH';
			}
			else if(strlen($phone_number)>7)
			{
				$type = 'NGH';
			}
		}
		return $type;
	}
	function parse_row($rows,$phones)
	{
        //System::debug($rows);
		if($rows and count($rows)>=1)
		{		
			$duranton = 0;
			$duration_temp = $rows['13'];
			$duration_temp = str_replace(array('\'','"',' '),array(':','',''),$duration_temp);
			if(preg_match('/([0-9]+):([0-9]+):([0-9]+)/',$duration_temp,$matches))
			{
				$duranton = ($matches[1]*360) + ($matches[2]*60) + $matches[3];
			}				
			$price = TelephoneLib::make_price($rows[11],$duranton,$phones);
			$type = TelephoneLib::make_type($rows[11],$phones);
			$hdate = TelephoneLib::to_telephone_time($rows[1]) + TelephoneLib::get_time_call($rows[3],$duranton);
			return array(
				'phone_number_id'=>$rows[7],
				'trungke'=>$rows[9],
				'dial_number'=>$rows[11],
				'description'=>$rows[14],
				'hdate'=>$hdate,
				'price'=>$price,
				'price_vnd'=>$price,
				'ring_durantion'=>$duranton,
				'type'=>$type,
				'portal_id'=>PORTAL_ID
			);
		}
		return false;
	}	
	function get_time_call($start_time,$duration){
		$start_time = str_replace(array(';','Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â´'),array('','0'),$start_time);
		if(strpos($start_time,'AM'))
		{
			$start_time = str_replace('AM','',$start_time);
			$time_arr = explode(":",$start_time);
			$hour = intval($time_arr[0]);
			$minute = intval($time_arr[1]);
	
		}
		elseif(strpos($start_time,'PM'))
		{
			$start_time = str_replace('PM','',$start_time);
			$time_arr = explode(":",$start_time);
			$hour = intval($time_arr[0]);
			if($hour!=12)
			{
				$hour = $hour+12;
			}
			$minute = intval($time_arr[1]);
		}
		else
		{
			$time_arr = explode(":",$start_time);
			$hour = intval($time_arr[0]);
			$minute = intval($time_arr[1]);
		}
		$total_second = $hour*3600 + $minute*60 + $duration;
		return $total_second;
	}
	static function update_telephone_daily($file_name)
	{

		if(file_exists($file_name))
		{	
			$content = file_get_contents($file_name);
            //System::debug($content);
            
			// so thuc
			$pattern = '/([0-9\/\s]+)([\s])(([0-9\:]+)PM|([0-9\:]+)AM)([\s]+)([0-9]+)([\s]+)([0-9]+)([\s]+)([0-9\*]+)([\s]+)([0-9\:\'\"]+)(.*)/';	
			if(preg_match_all($pattern,$content,$matches,PREG_SET_ORDER))
			{
				$phones = DB::fetch_all('select prefix as id,start_fee,fee from telephone_fee');
                //System::debug($phones);
				foreach($matches as $key=>$value)
				{
				    //System::debug($value);
					$row = TelephoneLib::parse_row($value,$phones);
                    //System::debug($row);
                    //exit();
					$cond  = ' phone_number_id = \''.$row['phone_number_id'].'\'';
					$cond .= ' and trungke = \''.$row['trungke'].'\'';
					$cond .= ' and dial_number = \''.$row['dial_number'].'\'';
					//$cond .= ' and description = \''.$row['description'].'\'';
					$cond .= ' and hdate = \''.$row['hdate'].'\'';
					$cond .= ' and price = \''.$row['price'].'\'';
					$cond .= ' and price_vnd = \''.$row['price_vnd'].'\'';
					$cond .= ' and portal_id = \''.PORTAL_ID.'\'';
					$cond .= ' and ring_durantion = \''.$row['ring_durantion'].'\'';
					if(!DB::fetch('select id from telephone_report_daily where '.$cond))
					{
                        //System::debug($row);
						DB::insert('telephone_report_daily',$row);
					}
				}
                //exit();
			}
		}	
	}	
}
?>