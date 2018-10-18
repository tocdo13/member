<?php
/******************************
COPY RIGHT BY TCV PORTAL
WRITTEN BY vuonggialong
EDITED BY KHOAND
******************************/

//Lop he thong
//Cac ham dung chung thong dung cho vao day
class Timer
{
	var $starttime = 0;
    function start_timer()
    {
        $mtime = microtime();
        $mtime = explode (' ', $mtime);
        $mtime = $mtime[1] + $mtime[0];
		$this->starttime = $mtime;
    }
	function get_timer()
	{
		$mtime = microtime();
		$mtime = explode (' ', $mtime);
		$mtime = $mtime[1] + $mtime[0];
		return number_format($mtime-$this->starttime,4);
	}
}

class System
{
	static $false = false;
	function send_mail($from,$to,$subject,$content,$attachment=array(),$from_user='admin@tiachopviet.net',$from_password='123456')
	{
		if(!class_exists('PHPMailer')){
			require(ROOT_PATH.'packages/core/includes/utils/mailer/class.phpmailer.php');
		}
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SetLanguage("vn", "");
		$mail->Host     = "";
		$mail->SMTPAuth = true;
		//attachment
		if(is_array($attachment) and count($attachment)>0){
			foreach($attachment as $value){
				$mail->AddAttachment("upload/booking/file/".$value);
			}
		}
		////////////////////////////////////////////////
		// Ban hay sua cac thong tin sau cho phu hop
		$mail->Username = $from_user;				// SMTP username
		$mail->Password = $from_password; 				// SMTP password
		$mail->From     = $from;				// Email duoc gui tu???
		$mail->FromName = "CHECKIN VIETNAM";					// Ten hom email duoc gui
		$mail->AddAddress($to,"");	 	// Dia chi email va ten nhan
		$mail->AddReplyTo($from,"checkinvietnam.com");		// Dia chi email va ten gui lai
		$mail->IsHTML(true);//default : true (ducnm sua)				// Gui theo dang HTML
		$mail->Subject  =  $subject;				// Chu de email
		$mail->Body     =  $content;		// Noi dung html
		if(!$mail->Send()){
		   echo "Email chua duoc gui di! <p>";
		   echo "Loi: " . $mail->ErrorInfo;
		   echo '<br><a href="'.URL::build('lost_password').'">Back</a><br>';
		   exit;
		}else{
			return true;
		}
	}
	function halt()
	{
		Session::end();
		DB::close();
		exit();
	}
	function log($type, $title='', $description = '', $parameter = '', $note = '', $user_id = false)
	{
		if(strlen($description)<4000){
			$id_new = DB::insert('LOG', array('TYPE'=>$type, 'MODULE_ID'=>is_object(Module::$current)?Module::block_id():0,
				'TITLE'=>$title, 'DESCRIPTION'=>$description, 'PARAMETER'=>$parameter, 'NOTE'=>$note, 'TIME'=>time(),'USER_ID'=>$user_id?$user_id:is_object(User::$current)?User::id():0));
		}else{
		    $id_new = DB::insert('LOG', array('TYPE'=>$type, 'MODULE_ID'=>is_object(Module::$current)?Module::block_id():0,
				'TITLE'=>$title, 'DESCRIPTION'=>'', 'PARAMETER'=>$parameter, 'NOTE'=>$note, 'TIME'=>time(),'USER_ID'=>$user_id?$user_id:is_object(User::$current)?User::id():0));
            file_put_contents('packages/user/modules/Log/file/title_'.$id_new.'.txt',$title);
            file_put_contents('packages/user/modules/Log/file/description_'.$id_new.'.txt',$description);  
		}
        return $id_new;
	}
    function history_log($type,$invoice_id,$log_id)
    {
        //type: kieu hoa don, nodule
        //invoice_id: id cua hoa don
        //log_id: log thao tac cua hoa don
        DB::insert('HISTORY_LOG',array('TYPE'=>$type,'INVOICE_ID'=>$invoice_id,'LOG_ID'=>$log_id));
    }
	function set_page_title($title)
	{
		echo '<script type="text/javascript">document.title=\''.str_replace('\'','&quot;',$title).'\';</script>';
	}
	function set_page_description($description)
	{
		echo '<script type="text/javascript">document.description=\''.str_replace('\'','&quot;',$description).'\';</script>';
	}
	function add_meta_tag($tags)
	{
		global $meta_tags;
		if(isset($meta_tags))
		{
	 		$meta_tags.=$tags;
		}
		else
		{
			$meta_tags=$tags;
		}
	}
	function display_number($num)
	{
		$num = $num?$num:0;
		if($num==round($num))
		{
			return number_format($num,0,'.',',');
		}
		else
		{
			return number_format($num,2,'.',',');
		}
	}
	function display_number_report($num)
	{
		$num = $num?$num:0;
		return number_format($num,2,'.',',');
	}
	function calculate_number($num)
	{
		return str_replace(',','',$num);
	}
	function debug($array)
	{
		echo '<pre>';
		print_r($array);
		echo '</pre>';
	}
}
class String
{
	function str_multi_language($vn,$en=false)
	{
		if(Portal::language()==1)
		{
			return $vn;
		}
		else
		if(Portal::language()==2)
		{
			return ($en!=false)?$en:$vn;
		}
		else
		if(Portal::language()==3)
		{
			return ($en!=false)?$en:$vn;
		}
		else
		if(Portal::language()==4)
		{
			return ($en!=false)?$en:$vn;
		}
		else
		{
			return ($en!=false)?$en:$vn;
		}
	}
	function language_field_list($name)
	{
		$languages = DB::select_all('LANGUAGE');
		$st = '';
		foreach($languages as $language)
		{
			if($st)
			{
				$st .= ',';
			}
			$st .= $name.'_'.$language['id'];
		}
		return $st;
	}
	function display_sort_title($str, $n, $delim='...') { 
	   $len = strlen($str); 
	   if ($len > $n) { 
		   preg_match('/(.{' . $n . '}.*?)\b/', $str, $matches); 
		   return rtrim($matches[1]) . $delim; 
	   } 
	   else { 
		   return $str; 
	   } 
	} 
	function html_normalize($st)
	{
		return str_replace(array('"','<'),array('&quot;','&lt;'),$st);
	}
	function string2js($st)
	{
		return strtr($st, array('\''=>'\\\'','\\'=>'\\\\','\n'=>'',chr(10)=>'\\
',chr(13)=>''));
	}
	function array2js($array)
	{
		$st = '{';
		foreach($array as $key=>$value)
		{
			if($st!='{')
			{
				$st.='
,';
			}
			$st.='\''.String::string2js($key).'\':';
			if(is_array($value))
			{
				$st .= String::array2js($value);
			}
			else
			{
				$st .= '\''.String::string2js($value).'\'';
			}
		}
		return $st.'
}';
	}
	/*-----------------------------------------*/
	function array2suggest($array)
	{
		$st = '[';
		$i = 0;
		$size_of_array = sizeof($array);
		foreach($array as $key=>$value)
		{
			$st.='{';
			if(isset($value['name']))
			{
				$st.='name:"'.String::string2js($value['name']).'",to:"'.$key.'", id:"'.$key.'"';
			}
			else
			{
				$st.='name:"'.$key.'",to:"'.$key.'", id:"'.$key.'"';
			}
			$i++;
			if($i==$size_of_array)
			{
				$st.='}';
			}
			else
			{
				$st.='},
';
			}
		}
		$st.= ']';
		return $st;
	}
	/*-------------------/----------------------*/
	function array2tree(&$items,$items_name)
	{
		//$structure_ids = array(ID_ROOT=>1);
		$show_items = array();
		$min = -1;
		foreach($items as $item)
		{
			if($min==-1)
			{
				$min = IDStructure::level($item['structure_id']);
			}
			$structure_ids[number_format($item['structure_id'],0,'','')] = $item['id'];
			//echo number_format($item['structure_id'],0,'','').'<br>';
			if(IDStructure::level($item['structure_id'])<=$min)
			{
				$show_items[$item['id']] = $item+(isset($item['childs'])?array():array($items_name=>array()));
			}
			else
			{
				$st = '';
				$parent = $item['structure_id'];
				
				while(($level=IDStructure::level($parent = IDStructure::parent($parent)))>=$min and $parent and isset($structure_ids[number_format($parent,0,'','')]))
				{
					
					$st = '['.$structure_ids[number_format($parent,0,'','')].'][\''.$items_name.'\']'.$st;
					
				}
				//echo number_format($parent,0,'','').' '.$st.'<br>';
				if($level<$min or $level==0)
				{
					eval('$show_items'.$st.'['.$item['id'].'] = $item+array($items_name=>array());');
				}
			}
		}
		return $show_items;
	}
	function array2autosuggest($array)
	{
		$st = '[';
		$i = 0;
		$size_of_array = sizeof($array);
		foreach($array as $key=>$value)
		{
			$st.='{';
			$f = true;
			foreach($value as $k=>$v){
				if($f){ $f = false; }else{ $st .= ',';}
				$st .= $k.':"'.$v.'"';
			}
			$i++;
			if($i==$size_of_array)
			{
				$st.='}';
			}else{
				$st.='},
';
			}
		}
		$st.= ']';
		return $st;
	}
//convert to vnnumeric
	function convert_to_vnnumeric($st)
	{
		//$temp = str_replace('.','',$st);
		return str_replace(',','',$st);
	}
//convert string to number	
	function to_number($st,$count=0)
	{
		$temp = substr($st,$count);
		$n = 0;
		for($i=0;$i<strlen($temp);$i++)
		{
			$n = $n*10 + $temp[$i]; 
		}
		return $n;
	}//2,400,000
	function vnd_round($number,$precision=500){
		$number = intval($number,0);
		$pre_len = strlen($precision);
		$result = 0;
		if($number<=$precision){
			return 0;
		}else{
			$new_number = (substr($number,0,-$pre_len));
			$tail = intval(substr($number,-$pre_len,strlen($number)-1));
			$tail_pad = intval(str_pad(1,$pre_len+1,0));
			if($tail >= $precision){
				$new_number = $new_number + 1;	
			}
			$rerult = $new_number*$tail_pad;
			return $rerult;
		}
	}
	function get_list($items, $field_name=false)
	{
		
		$item_list = array();
		foreach($items as $id=>$item)
		{
			if(isset($item['structure_id']) and !User::can_view(false,ANY_CATEGORY))
			{
				unset($items[$id]);
			}
		}
		foreach($items as $item)
		{	
			if(!$field_name)
			{
				$field_name=isset($item['name'])?'name':(isset($item['title'])?'title':(isset($item['name_'.Portal::language()])?'name_'.Portal::language():(isset($item['title_'.Portal::language()])?'title_'.Portal::language():'id')));
			}
			if(isset($item['structure_id']))
			{
				$level = IDStructure::level($item['structure_id']);
				for($i=0;$i<$level;$i++)
				{
					$item[$field_name] = ' --- '.$item[$field_name];
				}
			}
			$item_list[$item['id']]=isset($item[$field_name])?$item[$field_name]:'';
		}
		return $item_list;
	}
		/**
	 * Remove HTML tags, including invisible text such as style and
	 * script code, and embedded objects.  Add line breaks around
	 * block-level tags to prevent word joining after tag removal.
	 */
	function strip_html_tags( $text )
	{
		$text = preg_replace(
			array(
			  // Remove invisible content
				'@<head[^>]*?>.*?</head>@siu',
				'@<style[^>]*?>.*?</style>@siu',
				'@<script[^>]*?.*?</script>@siu',
				'@<object[^>]*?.*?</object>@siu',
				'@<embed[^>]*?.*?</embed>@siu',
				'@<applet[^>]*?.*?</applet>@siu',
				'@<noframes[^>]*?.*?</noframes>@siu',
				'@<noscript[^>]*?.*?</noscript>@siu',
				'@<noembed[^>]*?.*?</noembed>@siu',
			  // Add line breaks before and after blocks
				'@</?((address)|(blockquote)|(center)|(del))@iu',
				'@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
				'@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
				'@</?((table)|(th)|(td)|(caption))@iu',
				'@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
				'@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
				'@</?((frameset)|(frame)|(iframe))@iu',
			),
			array(
				' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
				"\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
				"\n\$0", "\n\$0",
			),
			$text );
		return strip_tags( $text );
	}
	function vn_str_filter ($str){
        $unicode = array(
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd'=>'đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i'=>'í|ì|ỉ|ĩ|ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
			'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D'=>'Đ',
            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );
        
       foreach($unicode as $nonUnicode=>$uni){
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
       }
		return $str;
    }
	function vn_str_check($str){
        $unicode = array(
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd'=>'đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i'=>'í|ì|ỉ|ĩ|ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
			'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D'=>'Đ',
            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );
        $k=0;
       foreach($unicode as $nonUnicode=>$uni){
            $str = preg_match("/($uni)/i",$str);
			if($str!=0){
				$k=1;	
			}
       }
		return $k;
    }
}
class Date_Time
{
	function udate($time)
	{
		$pos = strpos($time,'.');
		if($pos){
			$microsecond = substr($time,$pos+1,6);
		}else{
			$microsecond = 000000;
		}
		$time = Date_Time::to_orc_date(date('d/m/Y',$time)).' '.date('h.i.s',$time).'.'.$microsecond.' '.date('A',$time);
		return $time;
	}
	/*-------Convert ngay(09)/thang(02)/nam(2009)---------*/
	function to_orc_date($date)
	{
		if($date){
			$month = array(1=>"JAN",2=>"FEB",3=>"MAR",4=>"APR",5=>"MAY",6=>"JUN",7=>"JUL",8=>"AUG",9=>"SEP",10=>"OCT",11=>"NOV",12=>"DEC");
			$a = explode('/',$date);		
			if(is_numeric($a[1]) and is_numeric($a[2]) and is_numeric($a[0]) and checkdate($a[1],$a[0],$a[2]))
			{
				if(intval($a[0])<10)
				{
					$a[0] = "0".intval($a[0]);
				}
				//duc sua 08/09/2009
				return $a[0].'-'.$month[intval($a[1])].'-'.$a[2];
				// ban cu return $a[0].'-'.$month[intval($a[1])].'-'.substr($a[2],2,2);
			}
			else
			{
				return false;
			}
		}else{
			return false;
		}
	}
	function convert_time_to_ora_date($time=0)
	{
		if($time)
		{
			return Date_Time::to_orc_date(date('d/m/Y',$time));
		}
		else
		{
			return Date_Time::to_orc_date(date('d/m/Y'));
		}
	}
	/*-------Convert 09-FEB-09 thanh ngay(09)/thang(02)/nam(2009)---------*/
	function convert_orc_date_to_date($date,$spe = "-")
	{
		if($date){
			$month = array("JAN"=>"01","FEB"=>"02","MAR"=>"03","APR"=>"04","MAY"=>"05","JUN"=>"06","JUL"=>"07","AUG"=>"08","SEP"=>"09","OCT"=>"10","NOV"=>"11","DEC"=>"12");
			$a = explode("-",$date);
			if(is_array($a) and isset($month[$a[1]]))
			{
				if(intval($a[0])<10)
				{
					$a[0] = "0".intval($a[0]);
				}
				return $a[0].$spe.$month[$a[1]].$spe.((strlen($a[2])<4)?'20'.$a[2]:$a[2]);
			}			
		}
		return false;
	}
	function to_sql_date($date)
	{
		$a = explode('/',$date);
		if(sizeof($a)==3 and is_numeric($a[1]) and is_numeric($a[2]) and is_numeric($a[0]) and checkdate($a[1],$a[0],$a[2]))
		{
			return ($a[0].'-'.$a[1].'-'.$a[2]);
		}
		else
		{
			return false;
		}
	}
	function to_common_date($date)
	{
		$a = explode('-',$date);
		
		if(sizeof($a)==3 and $a[2]!='0000')
		{
			return ($a[0].'/'.$a[1].'/'.$a[2]);
		}
		else
		{
			return false;
		}	
	}
	// format(d/m/Y) 01/01/2006
	function to_time($date)
	{
		if(preg_match('/(\d+)\/(\d+)\/(\d+)\s*(\d+)\:(\d+)/',$date,$patterns))
		{
			return strtotime($patterns[2].'/'.$patterns[1].'/'.$patterns[3])+$patterns[4]*3600+$patterns[5]*60;
		}
		else
		{
			$a = explode('/',$date);
			if(sizeof($a)==3 and is_numeric($a[1]) and is_numeric($a[2]) and is_numeric($a[0]) and checkdate($a[1],$a[0],$a[2]))
			{
				return strtotime($a[1].'/'.$a[0].'/'.$a[2]);
			}
			else
			{
				return false;
			}		
		}
	}
	//Tra ve ngay lon nhat trong thang (29, 30 hay 31)
	function display_date($time)
	{
		return date('d/m/Y',$time);
	}
	function daily($time)
	{
		$daily=(getdate($time));
		return $daily['weekday'];
	}
	// Tra ve so ngay cua thang
	function day_of_month($month,$year)
	{
		return cal_days_in_month(CAL_GREGORIAN,$month,$year); 
	}
	function count_hour($from,$to){
		if($from>=$to){
			return '';
		}else{
			$sub = $to - $from;
			$duration = 0;
			if($sub>=3600){
				$duration = floor($sub/3600).'h ';
				if(($sub%3600)>=60){
					$duration .= ':'.floor(($sub%3600)/60).'\'';
				}
			}else{
				$duration = '0h:'.floor($sub%60).'\'';
			}
			return $duration;
		}
	}
	function to_dbase_date($date)
	{
		if($date)
		{
			$arr = explode('/',$date);
			return $arr[2].$arr[1].$arr[0];
		}
		else
		{
			return false;	
		}
	}	
}
?>