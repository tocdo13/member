<?php
class TelephoneLib
{
	function update_telephone($file_name)
	{
		$path = 'telephone/';
		if($file_name)
		{
			$local_file = $file_name;
		}
		$remote_file = $local_file;
		$local_file = $path.$local_file;
		//TelephoneLib::save_file($file_name);
		TelephoneLib::update_telephone_daily(ROOT_PATH.$local_file);
	}
	function save_file($file_name)
	{
		copy("\\\\192.168.25.93\\datacdr\\".$file_name,ROOT_PATH."telephone\\".$file_name);
	}
	static function ftp($remote_file='',$local_file,$server_name= 'pcreception1',$user_name='tongdai',$password='123456')
	{
		$conn_id = @ftp_connect($server_name);
		$login_result = @ftp_login($conn_id,$user_name,$password);
		if ((!$conn_id) || (!$login_result))
		{
			die('<div class="notice">Could not connect to '.$server_name.'</div>');
		}
		else
		{
        	//echo "Connected to ".$server_name.", for user ".$user_name ." password :".$password;
   		}
		$handle = @fopen($local_file, 'w');
		if (ftp_fget($conn_id, $handle, $remote_file, FTP_ASCII, 0))
		{
			 //echo "successfully written to $local_file\n";
		}
		@ftp_close($conn_id);
		@fclose($handle);
	}
	//convert date thang/ngay/nam
	function to_telephone_time($date)
	{
		$time = 0;
		if($date)
		{
			$a = explode('/',$date);
			$day = $a[0];
			$month = $a[1];
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
			if((($check_phone_number == 3 or $check_phone_number == 6 or $check_phone_number == 7 or $check_phone_number == 2) && strlen($phone_number) == 7) or (strpos($phone_number,'064',0)!==false and (strpos($phone_number,'064',0)===0) && strlen($phone_number)==10))
			{
				$phone_price_default = ($duration<=$block_second)?($phones['NH']['start_fee']):(($phones['NH']['start_fee']*ceil($duration/$block_second))+$phones['NH']['fee']);
			}
			else if(strlen($phone_number)>7)
			{
				if((strlen($phone_number)==10 and $check_phone_number_1=='09') or (strlen($phone_number)==11 and $check_phone_number_1=='01'))
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
		if($rows and count($rows)>=1)
		{
			$duranton = 0;
			$duration_temp = $rows['10'];
			$duration_temp = str_replace('\'',':',$duration_temp);
			if(preg_match('/([0-9]+):([0-9]+):([0-9]+)/',$duration_temp,$matches))
			{
				$duranton = ($matches[1]*360) + ($matches[2]*60) + $matches[3];
			}
			$price = TelephoneLib::make_price($rows[8],$duranton,$phones);
			$type = TelephoneLib::make_type($rows[8],$phones);
			$hdate = TelephoneLib::to_telephone_time($rows[1]) + TelephoneLib::get_time_call($rows[2],$duranton);
			return array(
				'phone_number_id'=>$rows[6],
				'trungke'=>$rows[7],
				'dial_number'=>$rows[8],
				'description'=>$rows[12],
				'hdate'=>$hdate,
				'price'=>$price,
				'price_vnd'=>$price,
				'ring_durantion'=>$duranton,
				'type'=>$type
			);
		}
		return false;
	}
	function get_time_call($start_time,$duration){
		$start_time = str_replace(array(';','Ã ï¿½Ã Â´'),array('','0'),$start_time);
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
			$duration_temp = str_replace('\'',':',$start_time);
			$hour = substr($start_time,0,2);
			$minute = substr($start_time,2,2);
		}
		$total_second = $hour*3600 + $minute*60 + $duration;
		return $total_second;
	}
	static function update_telephone_daily($file_name){
    	if(file_exists($file_name))
		{
			require_once ROOT_PATH."packages/core/includes/utils/phpxbase/Column.class.php";
			require_once ROOT_PATH."packages/core/includes/utils/phpxbase/Record.class.php";
			require_once ROOT_PATH."packages/core/includes/utils/phpxbase/Table.class.php";
			/* create a table object and open it */
			$table = new XBaseTable($file_name);
			$table->open();
			while ($record=$table->nextRecord()) {
				$arr = array();
				$cond = '';
				foreach ($table->getColumns() as $i=>$c) {
					switch($c->getName())
					{
						case 'NOBILL':
							$arr['bill_id'] = $record->getObject($c);
							break;
						case 'DATECALL':
							$cond.='hdate='.$record->getObject($c);
							$arr['date_call'] = date('d/m/Y',$record->getObject($c));
							break;
						case 'TIMECALL':
							$arr['time_call'] = $record->getObject($c);
							break;
						case 'CALLING':
							$arr['phone_number_id'] = $record->getObject($c);
							break;
						case 'CALLED':
							$arr['dial_number'] = $record->getObject($c);
							break;
						case 'DUR_CALL':
							$arr['duration_call'] = $record->getObject($c);
							break;
						case 'TOTAL_V':
							$arr['price'] = $record->getObject($c);
							$arr['price_vnd'] = $record->getObject($c);
							break;
						case 'POST':
							$arr['post'] = $record->getObject($c);break;
						case 'TAX':
							$arr['tax'] = $record->getObject($c);break;
						case 'IDTK':
							$arr['trungke'] = $record->getObject($c);
							break;
					}
				}
				if($arr)
				{
					$arr['ring_durantion'] = $arr['duration_call'];
					$arr['hdate'] = TelephoneLib::to_telephone_time($arr['date_call']) + TelephoneLib::get_time_call($arr['time_call'],$arr['duration_call']);
					$arr['total_before_tax'] = $arr['post']+$arr['tax'];
					$arr['portal_id'] = PORTAL_ID;
					unset($arr['post']);
					unset($arr['tax']);
					unset($arr['date_call']);
					unset($arr['time_call']);
					unset($arr['duration_call']);
					if(!DB::select('telephone_report_daily','bill_id='.$arr['bill_id']))
					{
						DB::insert('telephone_report_daily',$arr);
					}
				}
			}
			$table->close();
			/*if($telephone_data)
			{
				$phones = DB::fetch_all('select prefix as id,start_fee,fee from telephone_fee');
				foreach($matches as $key=>$value)
				{
					$row = TelephoneLib::parse_row($value,$phones);
					$cond  = ' phone_number_id = \''.$row['phone_number_id'].'\'';
					$cond .= ' and trungke = \''.$row['trungke'].'\'';
					$cond .= ' and dial_number = \''.$row['dial_number'].'\'';
					$cond .= ' and description = \''.$row['description'].'\'';
					$cond .= ' and hdate = \''.$row['hdate'].'\'';
					$cond .= ' and price = \''.$row['price'].'\'';
					$cond .= ' and price_vnd = \''.$row['price_vnd'].'\'';
					$cond .= ' and ring_durantion = \''.$row['ring_durantion'].'\'';
					if(!DB::fetch('select id from telephone_report_daily where '.$cond))
					{
						DB::insert('telephone_report_daily',$row);
					}
				}
			}*/
		}
	}
	function set_telephone_command($telephone_number,$command,$param1='',$param2='',$param3='')
	{
		require_once "packages/core/includes/utils/phpxbase/Column.class.php";
		require_once "packages/core/includes/utils/phpxbase/Record.class.php";
		require_once "packages/core/includes/utils/phpxbase/Table.class.php";
		require_once "packages/core/includes/utils/phpxbase/WritableTable.class.php";
		$tb = new XBaseWritableTable('\\\\192.168.25.252\\d$\\TS\\Tourist_2012\\Tourist\\first\\front\\PN\\DATA\\PMSSEND.DBF');
		$table = $tb->openWrite('\\\\192.168.25.252\\d$\\TS\\Tourist_2012\\Tourist\\first\\front\\PN\\DATA\\PMSSEND.DBF',false);
		$r = $tb->appendRecord();
		$r->setObjectByName("POST","T");
		$r->setObjectByName("TELEPHONE",$telephone_number);
		$r->setObjectByName("LENH",$command);
		$r->setObjectByName("PARA1",$param1);
		$tb->writeRecord();
		$tb->close();
		$tb1 = new XBaseWritableTable('\\\\192.168.25.252\\d$\\TS\\Tourist_2012\\Tourist\\first\\front\\PN\\DATA\\PMSSEND1.DBF');
		$table = $tb1->openWrite('\\\\192.168.25.252\\d$\\TS\\Tourist_2012\\Tourist\\first\\front\\PN\\DATA\\PMSSEND1.DBF',false);
		$r = $tb1->appendRecord();
		$r->setObjectByName("POST","T");
		$r->setObjectByName("TELEPHONE",$telephone_number);
		$r->setObjectByName("LENH",$command);
		$r->setObjectByName("PARA1",$param1);
		$r->setObjectByName("NGAY",time());
		$r->setObjectByName("GIO",date('H:i:s'));
		$tb1->writeRecord();
		$tb1->close();
	}
}
?>