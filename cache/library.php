<?php
define ('DEBUG',1);
Portal::$extra_header='';
//config header
header("Content-Type: text/html; charset=utf-8");
ini_set ('zend.ze1_compatibility_mode','off');
// include kernel files
require_once 'cache/modules.php';
?><?php
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
<?php
/****************************
	writtend by TCV developers
	connect to  ORACLE	
*****************************/
class DB
{
	static $db_connect_id=false;				// connection id of this database
	static $db_result=false;				// current result of an query
	static $db_cache_tables = array();
	static $db_exists_db = array();
	static $db_select_all_db = array();
	static $db_num_queries = 0;
	function DB($user_name,$password,$db = '//localhost/XE')
	{
		DB::$db_connect_id = oci_connect($user_name,$password,$db,'AL32UTF8');
		if(isset(DB::$db_connect_id) and DB::$db_connect_id)
		{
			return DB::$db_connect_id;		
		}	
		else
		{
			die(DB::db_error());
			return false;
		}
	}
	function db_error($db_connect_id = false)
	{
		$error = $db_connect_id?oci_error($db_connect_id):oci_error();
		if(isset($error['message'])) 
		{
			return $error['message'].'<br />'.$error['sqltext'];
		}
		return false;	
	} 
	static function register_cache($table, $id_name='id', $order=' order by id asc')
	{
		DB::$db_cache_tables[$table]=array('id_name'=>$id_name, 'order'=>$order);
		if(!file_exists(ROOT_PATH.'cache/tables/'.$table.'.cache.php'))
		{
			require_once 'packages/core/includes/system/make_table_cache.php';
			make_table_cache($table);
		}
		else
		{
			require_once ROOT_PATH.'cache/tables/'.$table.'.cache.php';
		}
	}
	static function count($table, $condition=false)
	{
		return DB::fetch('SELECT  id as total FROM '.$table.' '.($condition?' WHERE '.$condition:''),'total');
	}
	static function select($table, $condition)
	{
		if($result = DB::select_id($table, $condition))
		{
			return $result;
		}
		else
		{
			return DB::exists('SELECT * FROM '.strtoupper($table).' WHERE '.$condition.' and rownum =1');
		}
	}
	static function select_id($table, $condition)
	{
		if($condition and !preg_match('/[^a-zA-Z0-9_#-\.]/',$condition))
		{
			if(isset(DB::$db_cache_tables[$table]))
			{
				$id=$condition;
				$cache_var = 'cache_'.$table;
				global $$cache_var;
				$cached = isset($$cache_var);				
				$data = &$$cache_var;
				if(isset($data[$id]))
				{
					return $data[$id];
				}
			}
			else 
			{
				return DB::exists_id($table,$condition);
			}
		}
		else
		{
			return false;
		}
	}
	static function select_all($table, $condition=false, $order = false)
	{
		if(isset(DB::$db_select_all_db[$table]) and !$order and !$condition)
		{
			return DB::$db_select_all_db[$table];
		}
		else
		if(isset($GLOBALS['cache_'.$table]) and !$order and !$condition)
		{
			return $GLOBALS['cache_'.$table];
		}
		else
		{
			if($order)
			{
				$order = ' ORDER BY '.$order;
			}
			if($condition)
			{
				$condition = ' WHERE '.$condition;
			}
			DB::query('SELECT * FROM '.strtoupper($table).' '.$condition.' '.$order);
			$rows = DB::fetch_all();
			if(sizeof($rows)<10)
			{
				DB::$db_select_all_db[$table] = $rows;
			}
			return $rows;
		}
	}
	static function query($query)
	{
		if(!empty($query))
		{
			DB::$db_result = @oci_parse(DB::$db_connect_id, $query);
			if (!DB::$db_result)
			{
				if(defined('DEBUG'))
				{
					echo '<p><font face="Courier New,Courier" size=3><b>'.DB::db_error(DB::$db_result).'</b></font><br>';
				}
				else
				{
					DB::insert('LOG',
						array(
							'MODULE_ID'=>1387,
							'USER_ID'=>Session::get('user_id'),
							'TIME'=>time(),
							'TYPE'=>'MYSQL',
							'DESCRIPTION'=>DB::db_error(DB::$db_result)
						)
					);
				}
			}
			$result = @oci_execute(DB::$db_result, OCI_DEFAULT);
			if(!$result)
			{
				die(DB::db_error(DB::$db_result));
			}
			if(DB::$db_connect_id and !oci_commit(DB::$db_connect_id))
			{
				die(DB::db_error(DB::$db_connect_id));
			}
			DB::$db_num_queries++;
			if((!class_exists('Module') or isset(Module::$current->data)))
			{
			   if(class_exists('Module'))
			   {
				$module_id = Module::$current->data['id'];
				$GLOBALS['information_query'][$module_id]['name']=Module::$current->data['module']['name'].(Module::$current->data['name']?'('.Module::$current->data['name'].')':'');
			   }
			   else
			   {
				$module_id = 0;
				$GLOBALS['information_query'][$module_id]['name']='';
			   }
			   if(isset($GLOBALS['information_query'][$module_id]['number_queries']))
			   {
				$GLOBALS['information_query'][$module_id]['number_queries']++;  
			   }
			   else
			   {
				$GLOBALS['information_query'][$module_id]['number_queries']=1;  
			   } 
			   $GLOBALS['information_query'][$module_id]['timer']=(class_exists('Portal'))?number_format(Portal::$page_gen_time->get_timer(),4):0;
			   $GLOBALS['information_query'][$module_id]['query'][]=$query; 
			}
			return DB::$db_result;
		}	
		return false;
	}	
	static function exists($query)
	{
		DB::query($query);
		if($item = DB::fetch() and sizeof($item)>=1)
		{
			return $item;
		}
		return false;
	}
	static function exists_id($table,$id)
	{
		if($id)
		{
			if(!isset(DB::$db_exists_db[$table][$id]))
			{
				DB::$db_exists_db[$table][$id]=DB::exists('SELECT * FROM '.strtoupper($table).' WHERE ID = \''.$id.'\'  AND rownum = 1');
			}
			return DB::$db_exists_db[$table][$id];
		}
	}
	static function insert($table, $values, $replace=false)
	{
		if($replace)
		{
			$query='REPLACE';
		}
		else
		{
			$query='INSERT INTO';
		}
		if(!isset($values['id']))
		{		
			if($item = DB::fetch('select * from (select '.$table.'.*,rownum from '.$table.' order by id DESC) where rownum=1') and isset($item['id']))
			{
				$id = $item['id'] + 1;
			}
			else
			{
				$id = 1;	
			}
			$values = array('id'=>$id)+$values;		
		}
		$query.=' '.strtoupper($table).'(';
		$i=0;
		if(is_array($values))
		{
			foreach($values as $key=>$value)
			{
				if(($key===0) or is_numeric($key))
				{
					$key=$value;
				}
				if($key)
				{
					if($i<>0)
					{
						$query.=',';
					}
					$query.=''.strtoupper($key).'';
					$i++;
				}
			}
			$query.=') VALUES(';
			$i=0;
			foreach($values as $key=>$value)
			{
				if(is_numeric($key) or $key===0)
				{
					$value=Url::get($value);
				}
				if($i<>0)
				{
					$query.=',';
				}
				if($value==='NULL')
				{
					$query.='NULL';
				}
				else
				{
					$query.='\''.DB::escape($value).'\'';
				}
				$i++;
			}
			$query.=')';
			//echo $query;exit();
			if(DB::query($query) and isset($id))
			{
				return $id;
			}
		}
	}
	static function delete($table, $condition)
	{
		$query='DELETE FROM '.strtoupper($table).' WHERE '.$condition;
        //System::debug($query);
        //exit();
		if(DB::query($query))
		{
		   // echo '<br>'.'aaaaa';
			return true;
		}
	}
	static function store_temp($table,$id,$temp='temp')
	{
		if($data = DB::exists_id($table,$id))
		{
			$new_data = array(
				'name'=>isset($data['name'])?$data['name']:$data['name_1'],
				'code'=>$data['id'],
				'ftable'=>$table,
				'meta'=>var_export($data,true)
			);
			return DB::insert($temp,$new_data);
		}
		return false;
	}
	static function delete_id($table, $id)
	{
		return DB::delete($table, 'ID=\''.addslashes($id).'\'');
	}
	static function update($table, $values, $condition)
	{
		$query='UPDATE '.strtoupper($table).' SET ';
		$i=0;
		if($values)
		{
		    /** manh them dong bo cns **/
		    if($table=='payment' || $table=='ticket_reservtion' || $table=='supplier' || $table=='customer' || $table=='account' || $table=='massage_guest' || $table=='traveller' || $table=='currency' || $table=='product_category' || $table=='unit' || $table=='warehouse')
            {
                $values['sync_cns'] = 0;
            }
            if($table=='ve_reservation' || $table=='bar_reservation' || $table=='massage_reservation_room' || $table=='folio' || $table=='product' || $table=='wh_invoice' || $table=='mice_invoice')
            {
                $values['sync_cns_vt'] = 0;
                $values['sync_cns_hh'] = 0;
            }
            if($table=='ticket' || $table=='product_service_cns')  
            {
                $values['sync_cns_case'] = 0;
                $values['sync_cns_fee'] = 0;
            }
            /** end manh **/
			foreach($values as $key=>$value)
			{
				if($key===0 or is_numeric($key))
				{
					$key=$value;
					$value=URL::get($value);
				}
				if($i<>0)
				{
					$query.=',';
				}
				if($key)
				{
					if(preg_match("/[id]|[code]|[name]|[title]/i",$key)){//eregi
						$key = String::strip_html_tags($key);
					}
					if($value==='NULL')
					{
						$query.=''.strtoupper($key).'=NULL';
					}
					else
					{
						$query.=''.strtoupper($key).'=\''.DB::escape($value).'\'';
					}
					$i++;
				}
			}
			$query.=' WHERE '.$condition;
			//echo $query.'<br>';
			if(DB::query($query))
			{
				return true;
			}
		}
	}
	static function update_id($table, $values, $id)
	{
		return DB::update($table, $values, 'ID=\''.$id.'\'');
	}	
	static function affected_rows($query_id = 0)
	{
		if (!$query_id)
		{
			$query_id = DB::$db_result;
		}
		if ($query_id)
		{
			$result = @oci_num_rows($query_id);
			return $result;
		}
		return false;
	}
	static function fetch($sql = false, $field = false, $default = false)
	{
		if($sql)
		{
			DB::query($sql);
		}
		$query_id = DB::$db_result;
		if ($query_id)
		{			
			if($result = @oci_fetch_assoc($query_id))
			{
				$result = array_change_key_case($result,CASE_LOWER);
				if($field)
				{
					return $result[$field];
				}
				return $result;
			}
			return $default;
		}
		else
		{
			return false;
		}
	}
	static function fetch_all($sql=false)
	{
		if($sql)
		{
			DB::query($sql);
		}
		$query_id = DB::$db_result;
		if($query_id)
		{
			$rows = array();			
			while($row = @oci_fetch_assoc($query_id))
			{	
				$rows[$row['ID']] = array_change_key_case($row,CASE_LOWER);
			}		
			return $rows;
		}
	}
	static function escape($sql)
	{
		$sql = stripslashes($sql);
		$sql = str_replace("'",'"',$sql);
		return $sql;
	}
	static function num_queries()
	{
		return DB::$db_num_queries;
	}
	static function structure_id($table,$id)
	{
		$row=DB::select($table,'id = \''.$id.'\'');
		return $row['structure_id'];
	}	
	static function search_cond($table, $field)
	{
		$cond_st = '';
		if(URL::get('search_by_'.$field))
		{
			$conds = explode('&',URL::get('search_by_'.$field));
			foreach($conds as $cond)
			{
				if(preg_match('/[><=]/',URL::get('search_by_'.$field)))
				{	
					$cond_st .= ' AND '.$table.'.'.$field.' '.$cond;
				}
				else
				{
					$cond_st .= ' AND '.$table.'.'.$field.' LIKE "%'.$cond.'%"';
				}
			}
		}
		return $cond_st;
	}
	static function get_record_title($item)
	{
		if(isset($item['name']))
		{
			return 'name';
		}
		else
		if(isset($item['title']))
		{
			return 'title';
		}
		else
		if(isset($item['name_'.Portal::language()]))
		{
			return 'name_'.Portal::language();
		}
		else
		if(isset($item['title_'.Portal::language()]))
		{
			return 'title_'.Portal::language();
		}
	}	
	function get_all_tables($field = ',TABS.*')
	{
		return DB::fetch_all('
			SELECT
				TABS.TABLE_NAME ID'.$field.'
			FROM 
				TABS
			ORDER BY
				TABS.TABLE_NAME
			');
	}
	function get_fields($table)
	{
		if($fields = DB::fetch_all('select user_tab_cols.*,user_tab_cols.column_name as id from user_tab_cols where table_name=\''.strtoupper($table).'\' order by column_id ASC'))
		{
			return $fields;
		}
		return false;	
	}
	static function update_hit_count($table,$id)
	{
		if(Session::is_set('item_visited'))
		{
			$items=array_flip(explode(',',Session::get('item_visited')));
		}
		else
		{
			$items=array();
		}			
		if(!isset($items[$id]) and $item=DB::select_id($table,intval($id)))
		{
			DB::update_id($table,array('hit_count'=>$item['hit_count']+1),intval($id));
			$items[$id]=$id;			
			Session::set('item_visited', implode(',',array_keys($items)));
		}		
	}
	function close()
	{
		if (isset(DB::$db_connect_id) and DB::$db_connect_id)
		{
			if (isset(DB::$db_result) and DB::$db_result)
			{
				@oci_free_statement(DB::$db_result);
			}
			return @oci_close(DB::$db_connect_id);
		}
		return false;
	}
}
require_once 'cache/config/db.php';
$db = new DB(USER_NAME,PASSWORD,DB_NAME);
?>
<?php
class Session
{
	static $name;
	static $vars;
	static $init_vars;
	static function start()
	{
		Session::$vars = array();
		Session::$init_vars = var_export(Session::$vars,true);
		if(!Session::$name)
		{
			Session::$name = md5($_SERVER['REMOTE_ADDR']);
		}
		if($vars = DB::fetch('
			SELECT
				VARS
			FROM
				SESSION_USER
			WHERE
				SESSION_ID=\''.addslashes(Session::$name).'\'
		','VARS'))
		{
			Session::$init_vars = $vars;
			eval('Session::$vars = '.$vars.';');
		}
		
	}
	static function end()
	{
		$vars = var_export(Session::$vars,1);
		if(Session::$init_vars != $vars)
		{
			if($session=DB::select('SESSION','id=\''.addslashes(Session::$name).'\''))
			{
				DB::update('SESSION',
					array(
						'VARS' => $vars,
						'LAST_ACTIVE_TIME'=>time()
					)
					,'ID=\''.addslashes(Session::$name).'\''
				);
			}
			else
			{
				DB::insert('SESSION',
					array(
						'ID'=>Session::$name,
						'VARS'=>$vars,
						'TIME'=>time(),
						'LAST_ACTIVE_TIME'=>time()
					)
				);
			}
		}
	}
	static function destroy()
	{
		DB::delete('SESSION','id=\''.addslashes(Session::$name).'\'');
	}
	static function name($name = false)
	{
		if($name)
		{
			Session::$name = $name;
		}
		return Session::$name;
	}
	static function delete($name, $field=false)
	{
		if($field)
		{
			if(isset(Session::$vars[$name][$field]))
			{
				unset(Session::$vars[$name][$field]);
			}
		}
		else
		{
			if(isset(Session::$vars[$name]))
			{
				unset(Session::$vars[$name]);
			}
		}
	}
	static function get($name, $field=false)
	{
		if(isset(Session::$vars[$name]))
		{
			if($field)
			{
				if(isset(Session::$vars[$name][$field]))
				{
					return Session::$vars[$name][$field];
				}
				return false;
			}
			return Session::$vars[$name];
		}
	}
	static function set($name,$value)
	{
		Session::$vars[$name] = $value;
	}
	static function is_set($name, $field=false)
	{
		if($field)
		{
			return isset(Session::$vars[$name]) and isset(Session::$vars[$name][$field]);
		}
		return isset(Session::$vars[$name]);
	}
}
Session::start();
?>
<?php
/******************************
COPY RIGHT BY NYN PORTAL - TCV
WRITTEN BY vuonggialong
EDITED BY KHOAND
******************************/
class Url
{
	static public $root = 'version18/';
	function build_all($except=array(), $addition=false)
	{
		$url=false;
		foreach($_GET as $key=>$value)
		{	
			if(!in_array($key, $except))
			{
				
				if(!$url)
				{
					$url='?'.urlencode($key).'='.urlencode($value);
				}
				else
				{
					$url.='&'.urlencode($key).'='.urlencode($value);
				}
			}
		}
		foreach($_POST as $key=>$value)
		{
			if($key!='form_block_id')
			{
				if(!in_array($key, $except))
				{
					if(is_array($value))
					{
						$value = '';
					}
					if(!$url)
					{
						$url='?'.urlencode($key).'='.urlencode($value);
					}
					else
					{
						$url.='&'.urlencode($key).'='.urlencode($value);
					}
				}
			}
		}
		
		if($addition)
		{
			if($url)
			{
				$url.='&'.$addition;
			}
			else
			{
				$url.='?'.$addition;
			}
		}
		return $url;
	}
	function build_current($params=array(),$smart=false,$anchor='')
	{
		return URL::build(Portal::$page['name'],$params,$smart,Url::get('portal'),$anchor);
	}
	/*-------------------- edit by thanhpt 08/10/2008: add rewrite --------------------------*/
	function build($page,$params=array(),$smart=false,$portal_id=false,$anchor='')
	{
		//require_once 'packages/portal/includes/utils/vn_code.php';
		if($smart)
		{
			$request_string = URL::get('portal').'/'.$page;
			if($portal_id)
			{
				$request_string =$portal_id.'/'.$page;
			}
			if ($params)
			{
				foreach ($params as $param=>$value)
				{
					if(is_numeric($param))
					{
						if(isset($_REQUEST[$value]))
						{
							$request_string .= '/'.urlencode($_REQUEST[$value]);
						}
					}
					else
					{
						if($param=='name')
						{
							$request_string .= '/'.convert_utf8_to_url_rewrite($value);
						}
						else
						{
							if(preg_match('/page_no/',$param,$matches))
							{
								$request_string .= '/trang-'.$value;
							}
							else
							{
								$request_string .= '/'.substr($param,0,1).$value;
							}	
						}
					}
				}
			}
			$request_string.='.html';
		}
		else
		{
			if(!isset($params['portal']))
			{
				$params['portal'] = URL::get('portal');
			}
			$request_string = '?page='.$page;
	
			if ($params)
			{
				foreach ($params as $param=>$value)
				{
					if(is_numeric($param))
					{
						if(isset($_REQUEST[$value]))
						{
							$request_string .= '&'.$value.'='.urlencode($_REQUEST[$value]);
						}
					}
					else
					{
						$request_string .= '&'.$param.'='.urlencode($value);
					}
				}
			}
		}	
		return $request_string.$anchor;
	}
	function build_page($page,$params=array(),$anchor='')
	{
		return URL::build(Portal::get_setting('page_name_'.$page),$params,$anchor);
	}
	function redirect_current($params=array(),$anchor = '')
	{
		URL::redirect(Portal::$page['name'],$params+array('portal'),$anchor);
	}
	function redirect_href($params=false)
	{
		if(Url::check('href'))
		{
			Url::redirect_url(Url::attach($_REQUEST['href'],$params));
			return true;
		}
	}
	function check($params)
	{
		if(!is_array($params))
		{
			$params=array(0=>$params);
		}
		foreach($params as $param=>$value)
		{
			if(is_numeric($param))
			{
				if(!isset($_REQUEST[$value]))
				{
					return false;
				}
			}
			else
			{
				if(!isset($_REQUEST[$param]))
				{
					return false;
				}
				else
				{
					if($_REQUEST[$param]!=$value)
					{
						return false;
					}
				}
			}
		}
		return true;
	}
	function check_link($link)
	{
		if(preg_match('/http:\/\//',$link,$matches))
		{
			return $link;
		}
		else
		{
			return WEB_ROOT.$link;
		}
	}
	//Chuyen sang trang chi ra voi $url
	function redirect($page=false,$params=false,$smart=false,$anchor='')
	{
		if(!$page and !$params)
		{
			Url::redirect_url();
		}
		else
		{
			Url::redirect_url(Url::build($page, $params,$smart,$anchor));
		}
	}
	function redirect_url($url=false)
	{
		if(!$url||$url=='')
		{
			$url='?'.$_SERVER['QUERY_STRING'];
		}
		header('Location:'.str_replace('&','&','http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.$url));
		System::halt();
	}
	function access_denied()
	{
		if(Portal::$page['name']!='home')
		{
			Url::redirect('access_denied');
		}
		else
		{
			System::halt();
		}
	}
	function get_num($name,$default='')
	{
		if (preg_match('/[^0-9.,]/',URL::get($name)))
		{
			return $default;
		}
		else
		{
			return str_replace(',','.',str_replace('.','',$_REQUEST[$name]));
		}
	}
	function get_value($name,$default='')
	{
		if (isset($_REQUEST[$name]))
		{
			return $_REQUEST[$name];
		}
		else
		if (isset($_POST[$name]))
		{
			return $_POST[$name];
		}
		else
		if(isset($_GET[$name]))
		{
			return $_GET[$name];
		}
		else
		{
			return $default;		
		}
	}
	function get($name,$default='')
	{
		/*if(eregi("(^xxx)|(^fuck)",Url::get_value($name,$default=''))){
			$string = 'Warning: has bad word...please change another word';
			return $string;
		}*/
		if(isset($_REQUEST[$name]))
		{
			return Url::get_value($name,$default='');
		}
		else if(isset($_REQUEST[strtoupper($name)]))
		{
			return Url::get_value(strtoupper($name),$default='');
		}
		else if(isset($_REQUEST[substr($name,0,1)]))
		{
			return Url::get_value(substr($name,0,1),$default='');
		}
		else
		{
			return $default;
		}
	}
	function sget($name,$default='')
	{
		return strtr(URL::get($name, $default),array('"'=>'\\"'));
	}
	function iget($name)
    {//Lay theo so nguyen
		if(!is_numeric(Url::sget($name)))
        {
			return 0;
		}else
        {
			return intval(Url::sget($name));
		}
	}
	function fget($name){// lay theo so float
		if(!is_numeric(Url::sget($name))){
			return 0;
		}else{
			return floatval(Url::sget($name));
		}
	}
	function jget($name,$default='')
	{
		return String::string2js(URL::get($name, $default));
	}
}
?>
<?php
/*----------------------------------------------------------------------------------
Lop IDStructure
Tap hop cac ham xu ly voi cac bang co structure_id, la mot so mo ta cap do, vi tri cua ban ghi trong cay phan cap
Author:
Vuong Gia Long
18/4/2005
----------------------------------------------------------------------------------*/
define('ID_BASE', 100.0);//So ban ghi toi da o cung mot cap thuoc mot goc
define('ID_MAX_LEVEL', 9);//So level toi da
define('ID_ROOT', "1000000000000000000");//ID goc

//Tap hop cac ham thao tac voi cac bang co ID co cau truc cay
class IDStructure
{
	function have_child($table,$structure_id, $extra_cond='', $database=false)
	{
		return DB::select($table,IDStructure::child_cond($structure_id, true).$extra_cond);
	}
	//Tra ve structure_id cha cua $structure_id
	//$structure_id: structure_id can tinh
 	function parent($structure_id)
	{
		if($structure_id==ID_ROOT)
		{
			return false;
		}
		else
		{
			$level=IDStructure::level($structure_id);
			$structure_id=number_format($structure_id,0,'','').'';
			if($level!=0)
			{
				$structure_id{$level*2-1}='0';
				$structure_id{$level*2}='0';
			}
			return number_format($structure_id,0,'','');
		}
	}
	//Tra ve level cua $structure_id
	//$structure_id: structure_id can tinh
	function level($structure_id)
	{
		$level = 0;
		if($structure_id>=ID_ROOT)
		{
			$i = 0;
			$st = '_'.number_format($structure_id,0,'','');
			while(substr($st,$level*2,2)!='00')
			{	
				$level++;
			}
			$level--;
		}
		return $level;
	}

	//Tra ve structure_id ke sau cua $structure_id
	//$structure_id: structure_id can tinh
	function next($structure_id)
	{
		return number_format($structure_id+pow(ID_BASE,ID_MAX_LEVEL - IDStructure::level($structure_id)),0,'','');
	}
	//Kiem tra $structure_id co phai la con cua $parent_id khong
	//$structure_id: structure_id con
	//$parent_id: structure_id cha
	 function is_child($structure_id, $parent_id)
	{	
		return $structure_id > $parent_id and $structure_id < IDStructure::next($parent_id);
	}
	//Tra ve dieu kien de truy van ra duong dan cua idstruture, tu con den cha
	function path_cond($structure_id)
	{
		$path = $structure_id;
		while($structure_id=IDStructure::parent($structure_id))
		{
			$path .= ','.$structure_id;
		}
		return '(instr(structure_id,'.$path.')>0)';
	}
	//Tra ve bieu thuc dieu kien truy van tat ca con cua $id
	//$structure_id: can tinh dieu kien
	//$except_me: co loai tru chinh $structure_id nay khong
	
	function child_cond($structure_id, $except_me = false,$extra = '')
	{	
		if($except_me)
		{
			return '('.$extra.'structure_id > '.$structure_id.' AND '.$extra.'structure_id < '.IDStructure::next($structure_id).')';
		}
		else
		{
			return '('.$extra.'structure_id >= '.$structure_id.' AND '.$extra.'structure_id < '.IDStructure::next($structure_id).')';
		}
	}
	//Tra ve bieu thuc dieu kien truy van tat ca con truc tiep cua $id (truc tiep nghia la co level = level ($structure_id)-1)
	//$structure_id: can tinh dieu kien
	function direct_child_cond($structure_id, $child_level=1,$extra = '')
	{	
		$level = IDStructure::level($structure_id);
		$child_offset = number_format(pow(ID_BASE, ID_MAX_LEVEL-($level+$child_level)),0,'','');
		return '('.IDStructure::child_cond($structure_id, true,$extra).' AND remainder('.$extra.'structure_id,'.$child_offset.')=0 ) ';
	}
}
?>
<?php
/******************************
COPY RIGHT BY NYN PORTAL - TCV
WRITTEN BY vuonggialong
EDITED BY KHOAND.
******************************/

class Type
{
	var $required = false;
	var $error = false;
	var $message = false;
	var $name = false;
	var $constrain_column=false;
	var $constrain_value=false;
	function Type($required=false, $message='error')
	{
		$this->required=$required;
		$this->message = $message;
	}
	function check($value)
	{
		$this->error = false;
		if($this->required and $value=='')
		{
			$this->error = $this->message;
		}
		return !$this->error;
	}
	function to_js_data($name, $word)
	{
		return '{"name":"'.$name.'","message":"'.addslashes($word).'","type":"text","require":'.($this->required?1:0).',"min":0,"max":255}';
	}
	//
	function get_message()
	{
		return Portal::language($this->message);
	}
}
//Lop kieu du lieu Text
class TextType extends Type
{
	var $min_len = 0;
	var $max_len = 0;
	function TextType($required=false, $messages=false, $min_len, $max_len,$constrain_column=false,$constrain_value=false)
	{
		Type::Type($required, $messages);
		$this->min_len = $min_len;
		$this->max_len = $max_len;
		$this->constrain_column = $constrain_column;
		$this->constrain_value = $constrain_value;
	}
	function to_js_data($name, $word)
	{
		return '{"name":"'.$name.'","message":"'
		.addslashes($word).'","type":"text","require":'
		.($this->required?1:0).',"min":'
		.$this->min_len.',"max":'
		.$this->max_len.'}';
	}
	function check($value)
	{
		if(Type::check($value) && $value!='' )
		{
			$len=strlen($value);
			if($len<$this->min_len)
			{
				$this->error = $this->message;
			}
			else
			if($len>$this->max_len)
			{
				$this->error = $this->message;
			}
		}
		return !$this->error;
	}
}
class SelectType extends TextType
{
	function SelectType($required=false, $messages=false, $values)
	{
		TextType::TextType($required, $messages,0,1000);
		$this->values = $values;
	}
	function to_js_data($name, $word)
	{
		return '{"name":"'.$name.'","message":"'.addslashes($word).'","type":"text","require":'.($this->required?1:0).',"min":'.$this->min_len.',"max":'.$this->max_len.'}';
	}
	function check($value)
	{
		if(Type::check($value))
		{
			if(!in_array($value,array_keys($this->values)))
			{
				$this->error = $this->message;
			}
		}
		return !$this->error;
	}
}

//Lop kieu ten 
class NameType extends TextType
{
	function NameType($required=true, $messages=false,$min=2, $max=50)
	{
		TextType::TextType($required, $messages, $min, $max);
	}
	function to_js_data($name, $word)
	{
		return '{"name":"'.$name.'","message":"'.addslashes($word).'","type":"name","require":'.($this->required?1:0).',"min":'.$this->min_len.',"max":'.$this->max_len.'}';
	}
	function check($value)
	{
		if(TextType::check($value) && $value!='')
		{
			if(preg_match('/[^A-Za-z0-9_]/',$value))
			{
				$this->error = $this->message;
			}
		}
		return !$this->error;
	}
}
//Lop kieu password
class PasswordType extends TextType
{
	function PasswordType($required=true, $messages=false, $min=0, $max=32)
	{
		TextType::TextType($required, $messages, $min, $max);
	}
}
class RetypePasswordType extends PasswordType
{
	function RetypePasswordType($required=true, $messages=false, $min=0, $max=32)
	{
		PasswordType::PasswordType($required, $messages, $min, $max);
	}
	function check($value)
	{
		if(PasswordType::check($value) && $value!='')
		{
			if(URL::get('password') and URL::get('password')!=$value)
			{
				$this->error = $this->message;
			}
		}
		return !$this->error;
	}
}
//Lop kieu username
class UsernameType extends NameType
{
	function UsernameType($required=true, $messages=false)
	{
		NameType::NameType($required, $messages,2, 64);
	}
	function check($value)
	{
		return NameType::check($value);
	}
}
//Lop kieu email
class EmailType extends TextType
{
	function EmailType($required=true, $messages=false)
	{
		TextType::TextType($required, $messages, 5, 150);
	}
	function to_js_data($name, $word)
	{
		return '{"name":"'.$name.'","message":"'.addslashes($word).'","type":"email","require":'.($this->required?1:0).',"min":'.$this->min_len.',"max":'.$this->max_len.'}';
	}
	function check($value)
	{
		if(TextType::check($value) && $value!='')
		{
			//khai bao mot so mau de kiem tra
			
			if(!preg_match("/^[a-zA-Z0-9._%-]+@[a-zA-Z0-9._%-]+\.[a-zA-Z]{2,6}$/",$value) )
			{
				$this->error = $this->message;
			}
		}
		return !$this->error;
	}
}
//Lop kieu ngay thang
class DateType extends TextType
{
	var $min = '1/1/1900';
	var $max = '1/1/2030';
	function DateType($required=false, $messages=false,$min= '1/1/1900',$max = '1/1/2030')
	{
		TextType::TextType($required, $messages,6,15);
		$this->min = $min;
		$this->max = $max;
	}
	function to_js_data($name, $word)
	{
		return '{"name":"'.$name.'","message":"'.addslashes($word).'","type":"date","require":'.($this->required?1:0).'}';
	}
	function check($value)
	{
		if(TextType::check($value) && $value!='')
		{
			$params = explode('/',$value);
			if(sizeof($params)!=3 or !ctype_digit($params[0])or !ctype_digit($params[1])or !ctype_digit($params[2])
				or $params[0]<1 or $params[1]<1 or $params[2]<1800
				or $params[0]>31 or $params[1]>12 or $params[2]>2800
			)
			{
				$this->error = $this->message;
			}
		}
		return !$this->error;
	}
}
class PhoneType extends TextType
{
	function PhoneType($required=false, $messages=false,$min=6,$max=11)
	{
		TextType::TextType($required, $messages,$min,$max);
	}
	function to_js_data($name, $word)
	{
		return '{"name":"'.$name.'","message":"'.addslashes($word).'","type":"phone","require":'.($this->required?1:0).',"min":6,"max":11}';
	}
	function check($value)
	{
		if(TextType::check($value) && $value!='')
		{
			if(preg_match('/[^0-9_, ]/',$value))
			{
				$this->error = $this->message;
			}
		}
		return !$this->error;
	}
}
//Lop kieu so thuc
class FloatType extends Type
{
	var $min = 0;
	var $max = 0;
	function FloatType($required=false, $messages=false, $min=0, $max=1000000000,$constrain_column=false,$constrain_value=false)
	{
		Type::Type($required, $messages);
		$this->min = $min;
		$this->max = $max;
		$this->constrain_column = $constrain_column;
		$this->constrain_value = $constrain_value;
	}
	function to_js_data($name, $word)
	{
		return '{"name":"'.$name.'","message":"'.addslashes($word).'","type":"float","require":'.($this->required?1:0).',"min":'.$this->min.',"max":'.$this->max.'}';
	}
	function check($value)
	{
		$value=str_replace(',','',$value);
		if(Type::check($value) && $value!='')
		{
			if(!is_numeric($value) or $value<$this->min or $value>$this->max or preg_match('/[^0-9\.-]/',$value))
			{
				$this->error = $this->message;
			}
		}
		return !$this->error;
	}
}
//Lop kieu so nguyen
class IntType extends FloatType
{
	function IntType($required=false, $messages=false, $min=0, $max=9999,$constrain_column=false,$constrain_value=false)
	{
		FloatType::FloatType($required, $messages, $min, $max);
		$this->constrain_column = $constrain_column;
		$this->constrain_value = $constrain_value;
	}
	function to_js_data($name, $word)
	{
		return '{"name":"'.$name.'","message":"'.addslashes($word).'","type":"int","require":'.($this->required?1:0).',"min":'.$this->min.',"max":'.$this->max.'}';
	}
	function check($value)
	{
		$value=str_replace(',','',$value);
		if(FloatType::check($value) && $value!='')
		{
			if(floor($value)<>$value or preg_match('/[^0-9-]/',$value))
			{
				$this->error = $this->message;
			}
		}
		return !$this->error;
	}
}
//Lop kieu id gan voi 1 bang
class IDType extends NameType
{
	var $table = false;
	function IDType($required=false, $messages=false, $table='',$field='ID')
	{
		NameType::NameType($required, $messages,1,50);
		$this->table=$table;
		$this->field=$field;
	}
	function to_js_data($name, $word)
	{
		return '{"name":"'.$name.'","message":"'.addslashes($word).'","type":"name","require":'.($this->required?1:0).',"min":'.$this->min_len.',"max":'.$this->max_len.'}';
	}
	function check($value)
	{
		$value=str_replace(',','',$value);
		if(TextType::check($value) && $value!='')
		{
			if(!DB::exists('SELECT ID FROM '.$this->table.' WHERE '.$this->field.'=\''.$value.'\''))
			{
				$this->error = $this->message;
			}
		}
		return !$this->error;	
	}
}
class UniqueType extends Type
{
	var $table = false;
	var $field = false;
	var $portal_id = false;
	function UniqueType($require,$messages=false, $table, $field,$table_cond='',$portal_id=false)
	{
		Type::Type($require, $messages);
		$this->table = $table;
		$this->field = $field;
		$this->table_cond = $table_cond;
		$this->portal_id = $portal_id;
	}
	function check($value)
	{
		if(Type::check($value) && $value!='')
		{
			$cond = '';
			if (isset($_REQUEST['id']) && $_REQUEST['id'] and strtoupper($this->table)!='ACCOUNT')
			{
				$cond = 'ID<> \''.$_REQUEST['id'].'\' and ';
			}
			if($this->table_cond)
			{
				$cond.= $this->table_cond.' and ';
			}
			if($this->portal_id){
				$cond.= 'portal_id = \''.$this->portal_id.'\' and ';
			}
			$sql = 'SELECT ID FROM '.strtoupper($this->table).' WHERE '.$cond.' '.strtoupper($this->field).'=\''.DB::escape($value).'\'';
			if(DB::exists($sql))
			{
				$this->error = $this->message;
			}
			else
			{
				return !$this->error;
			}
		}
	}
}
?>
<?php
// Edited by khoand
class Form
{
	static $current = false;
	var $name = false;
	var $inputs = array();
	var $errors = false;
	var $error_messages = false;
	var $is_submit = false;
	var $count = 1;
	static $form_count = 1;
	function Form($name=false)
	{
		$this->name=$name;
		if(!defined('VERSION')){
			define('VERSION',3.01);
		}
	}
	function on_submit()   
	{

	}
	function is_submit()
	{
		if(!$this->is_submit)
		{
			$this->is_submit = 1;
			if(isset(Module::$current))
			{
				if(isset($_REQUEST['form_block_id']))
				{
					if($_REQUEST['form_block_id']==Module::block_id())
					{
						if($this->inputs)
						{
							$this->is_submit = 2;
							foreach($this->inputs as $name=>$types)
							{
								if(!strpos($name,'.') and !isset($_REQUEST[$name]))
								{
									$this->is_submit = 1;
									break;
								}
							}
						}
					}
				}
			}
		}
		return $this->is_submit == 2;
	}
	function is_error()
	{
		return $this->errors<>false or $this->error_messages<>false;
	}
	function add($name, $type)
	{
		$this->inputs[$name][] = $type;
	}
	function get_messages()
	{
		$this->error_messages=false;
		if($this->errors)
		{
			foreach($this->errors as $name=>$types)
			{
				foreach($types as $type)
				{
					$this->error_messages[$name][]=$type->get_message();
				}
			}
		}
		return $this->error_messages;
	}
	function check($exclude=array()){
		if($this->is_submit()){
			$this->errors = false;
			if($this->inputs){
				foreach ($this->inputs as $name=>$types){
					foreach($types as $type){
						if(!in_array($name,$exclude)){
							if(!strpos($name,'.')){
								if(!$type->check($_REQUEST[$name])){
									$this->errors[$name][] = $type;
								}
							}else{
								$names = explode('.',$name);
								$table = 'mi_'.$names[0];
								$field = $names[1];
								if(isset($_REQUEST[$table])){
									if(is_array($_REQUEST[$table])){
										foreach($_REQUEST[$table] as $key=>$record){
											if(isset($record[$field])){
												if(!$type->check($record[$field])){
													$this->errors[$table.'['.$key.']['.$field.']'][] = $type;
												}
											}else{
												if(!$type->check('')){
													$this->errors[$table.'['.$key.']['.$field.']'][] = $type;
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
			$this->get_messages();
			if(!$this->errors)
			{
				foreach ($this->inputs as $name=>$types)
				{
					foreach($types as $type)
					{
						if(get_class($type)=='floattype' or get_class($type)=='inttype')
						{
							if(!strpos($name,'.'))
							{
								$_REQUEST[$name] = str_replace(',','',$_REQUEST[$name]);
							}
							else
							{
								$names = explode('.',$name);
								$table = $names[0];
								$field = $names[1];
								if(isset($_REQUEST['mi_'.$table]))
								{
									if(is_array($_REQUEST['mi_'.$table]))
									{
										foreach($_REQUEST['mi_'.$table] as $key=>$record)
										{
											if(isset($record[$field]))
											{
												$_REQUEST['mi_'.$table][$key][$field] = str_replace(',','',$record[$field]);
											}
										}
									}
								}
							}
						}
					}
				}
			}
			return !$this->errors;
		}
		else
		{
			return false;
		}
	}
	function error($name, $message ,$use_language=true)
	{
		$this->error_messages[$name][]=$use_language?Portal::language($message):$message;
	}
	function parse_layout($name, $params=array())
	{
		$dir = ROOT_PATH.'cache/modules/'.Module::$current->data[(Module::$current->data['module']['type']!='WRAPPER')?'module':'wrapper']['name'];
		$cache_file_name = $dir.'/'.$name.'.php';
		$file_name = Module::$current->data[(Module::$current->data['module']['type']!='WRAPPER')?'module':'WRAPPER']['path'].'layouts/'.$name.'.php';
        if(!file_exists($cache_file_name) or (($cache_time=@filemtime($cache_file_name)) and (@filemtime($cache_file_name)<@filemtime($file_name))) or true)
		{
			require_once 'packages/core/includes/portal/generate_layout.php';
			$generate_layout = new GenerateLayout(file_get_contents($file_name));
			$text = $generate_layout->generate_text($generate_layout->synchronize());
			if(!is_dir($dir))
			{
				@mkdir($dir);
			}
			if($file = @fopen($cache_file_name,'w+'))
			{
				fwrite($file,$text);
				fclose($file);
			}
			$this->map = $params;
			$this->map['parse_layout'] = $text;
		}
		else
		{
			$this->map = $params;
			$this->map['parse_layout'] = file_get_contents($cache_file_name);
		}
		Module::invoke_event('ONPARSELAYOUT',Module::$current,$this->map);
		eval('?>'.$this->map['parse_layout'].'<?php ');
	}
	
	//In ra cac thong bao loi neu co
	function error_messages()
	{
		$this->count = Form::$form_count;
		Form::$form_count++;
		if(!$this->error_messages)
		{
			$show = ' style="display:none;"';
		}
		else
		{
			$show = '';
		}
		if (Portal::language()==1)
		{
			$notify = Portal::language('user_error');
		}
		else
		{
			$notify = 'Errors';
		}
		$txt = '<div id="error_messages_'.$this->count.'"'.$show.'><table cellpadding=5><tr valign="top">';
		$txt .= '<td nowrap><div class="error-notice">'.$notify.'</div><div align="center"><img src="packages/core/skins/default/images/buttons/warning.png" width="40" height="40" /></div></td>';
		$txt.='<td id="error_messages_content'.$this->count.'" >';
		if($this->error_messages)
		{
			foreach ($this->error_messages as $name=>$error_messages)
			{
				foreach($error_messages as $error_message)
				{
					if(trim($this->name))
					{
						$txt .= ' + <a class="error-notice link" onclick = "javascript:if(typeof(document.forms.'.$this->name.')!=\'undefined\'){document.forms.'.$this->name.'.namedItem(\''.$name.'\').focus();document.forms.'.$this->name.'.namedItem(\''.$name.'\').style.backgroundColor=\'#FFFFF2\';}">'.$error_message.'</a>';// title="&#7844;n v&#224;o &#273;&#226;y &#273;&#7875; xem v&#7883; tr&#237; x&#7843;y ra l&#7895;i"
					}
					else
					{
						$txt .= $error_message;
					}
					$txt .= '<br>';
				}
			}
		}
		$txt .= '</td></tr></table></div>';
		return $txt;
	}
	//In ra cac thong bao loi neu co
	function ext_error_messages($form_name)
	{
		$this->count = Form::$form_count;
		Form::$form_count++;
		if($this->error_messages)
		{

			foreach ($this->error_messages as $name=>$error_messages)
			{
				foreach($error_messages as $error_message)
				{
					echo $form_name.'.findById(\''.$name.'\').markInvalid(\''.addslashes($error_message).'\');
';
				}
			}

		}
		return $txt;
	}
	function draw()
	{
		
	}
	//Gan lai $current
	//Goi ham draw()
	function on_draw()
	{
		$last_form = &Form::$current;
		Form::$current = &$this;
		$this->draw();
		Form::$current=&$last_form;
	}
	function link_css($file_name)
	{
		if(strpos(Portal::$extra_css,'<LINK rel="stylesheet" href="'.$file_name.'?v='.VERSION.'" type="text/css">')===false)
		{
			Portal::$extra_css .= '<LINK rel="stylesheet" href="'.$file_name.'?v='.VERSION.'" type="text/css">
';
		}
	}
	function link_js($file_name,$version=true)
	{
		if($version)
		{
			if(strpos(Portal::$extra_js,'<script type="text/javascript" src="'.$file_name.'?v='.VERSION.'"></script>')===false)
			{
				Portal::$extra_js .= '<script type="text/javascript" src="'.$file_name.'?v='.VERSION.'"></script>
';
			}
		}
		else
		{
			if(strpos(Portal::$extra_js,'<script type="text/javascript" src="'.$file_name.'"></script>')===false)
			{
				Portal::$extra_js .= '<script type="text/javascript" src="'.$file_name.'"></script>
';
			}
		}
	}
	function add_footer_js_content($content)
	{
		Portal::$footer_js .= $content;
	}
	function auto_refresh($time, $url)
	{
		Portal::$extra_header .= '<META HTTP-EQUIV="Refresh" CONTENT="'.$time.'; URL='.$url.'">';
	}
	public static function get_module_id($name){
		if($row = DB::fetch('SELECT id,name FROM module WHERE name = \''.$name.'\'')){
			return $row['id'];
		}else{
			return false;
		}
	}
}
Form::$current=&System::$false;
?>
<?php
/******************************
COPY RIGHT BY NYN PORTAL - TCV
WRITTEN BY vuonggialong
EDITED BY Khoand
******************************/
define('CURRENT_CATEGORY',1);
define('ANY_CATEGORY',2);
class User
{
	var $groups = array();
	var $privilege = array();
	var $actions = array();
	var $settings = array();
	static $current=false;
	function User($id=false)
	{
		if(!$id)
		{		
			if(!Session::is_set('user_id'))
			{
				Session::set('user_id','guest');
			}
			if($this->data=DB::fetch('SELECT * FROM ACCOUNT WHERE ID=\''.Session::get('user_id').'\''))
			{
				if(!file_exists('cache/portal/'.str_replace('#','',PORTAL_ID).'/user/'.$this->data['id'].'.php'))
				{
					require_once 'packages/core/includes/system/make_user_privilege_cache.php';
					eval(make_user_privilege_cache(Session::get('user_id'),PORTAL_ID));
				}
				else
				{
					$file_path = 'cache/portal/'.str_replace('#','',PORTAL_ID).'/user/'.$this->data['id'].'.php';
					$privilege_user_file_content = file_get_contents($file_path);
					eval($privilege_user_file_content);
				}				
				if(!$this->data['cache_setting'])
				{
					//require_once 'packages/core/includes/system/make_account_setting_cache.php';
					//echo $code = make_account_setting_cache(Session::get('user_id'));
					//eval('$this->settings='.$code);
				}
				else
				{
					//eval('$this->settings='.$this->data['cache_setting']);
				}
			}
		}
	}
	function is_login()
	{
		if((Session::is_set('user_id') and DB::exists_id('ACCOUNT',Session::get('user_id')) and DB::exists('SELECT id FROM session_user WHERE user_id = \''.Session::get('user_id').'\'') and Session::get('user_id')!='guest')){
			return true;
		}else{
			return false;
		}
	}
	
	function is_online($id)
	{
		$row=DB::select('ACCOUNT', 'ID=\''.$id.'\' and LAST_ONLINE_TIME>'.(time()-600));
		if ($row)
		{
			return true;
		}
		return false;
	}
	function encode_password($password)
	{
		return md5($password.'thedeath');
	}
	function is_in_group($user_id,$group_id)
	{
		$row=DB::select('USER_GROUP',' USER_ID=\''.$user_id.'\' and GROUP_ID=\''.$group_id.'\'');
		if ($row or User::is_admin())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function groups()
	{	
		return $this->groups;
	}
	function home_page()
	{
		if(User::$current and User::$current->groups)
		{
			$group = reset(User::$current->groups);
			if(!isset($group['home_page']))
			{
				$group['home_page'] = URL::build('home');
			}
			return $group['home_page'];
		}
		return URL::build('home');
	}
	
	function is_admin_user()
	{
		return isset($this->groups[3]);
	}
	function is_admin()
	{
		if(isset(User::$current))
		{
			return User::$current->is_admin_user();
		}
	}
    function is_deploy_user() {
        return isset($this->groups[4]);
    }
    function is_deploy() {
        if(isset(User::$current))
		{
			return User::$current->is_deploy_user();
		}
    }
	function can_do_action($action,$pos,$module_id=false, $structure_id = 0, $portal_id = false)
	{
		if(!$portal_id)
		{
			$portal_id = PORTAL_ID;
		}
		if(User::is_admin())
		{
			return true;
		}
        if(User::is_deploy())
		{
			return true;
		}
		if(!$module_id)
		{
			if(isset(Module::$current->data))
			{
				$module_id = Module::$current->data['module']['id'];
				//$is_service = Module::$current->data['module']['type']=='SERVICE';
			}
			else
			{
				$module_id=false;
			}			
		}
		if(!$module_id)
		{
			return;
		}
		if($structure_id)
		{
			if($structure_id==CURRENT_CATEGORY)
			{
				$structure_id=0;
				if(URL::sget('category_id'))
				{
					$structure_id=DB::structure_id('CATEGORY',URL::sget('category_id'));
				}
				if(!$structure_id)
				{
					$structure_id = ID_ROOT;
				}
			}
			if(isset(User::$current->actions[$portal_id][$module_id][0]))
			{
				return User::$current->actions[$portal_id][$module_id][0]&(1 << (7-$pos));
			}			
			if($structure_id==ANY_CATEGORY)
			{
				if(isset(User::$current->actions[$portal_id]) and isset(User::$current->actions[$portal_id][$module_id]))
				{
					foreach(User::$current->actions[$portal_id][$module_id] as $category_privilege)
					{	
						if($category_privilege&(1 << (7-$pos)))
						{
							return true;
						}
					}
				}
				return false;
			}
			else
			{
				while(1)
				{
					if(isset(User::$current->actions[$portal_id][$module_id][$structure_id]))
					{
						return User::$current->actions[$portal_id][$module_id][$structure_id]&(1 << (7-$pos));
					}
					else
					if($structure_id <= ID_ROOT)
					{
						break;
					}
					else
					{
						$structure_id = IDStructure::parent($structure_id);
					}
				}
			}
			return false;
		}
		else
		{
			return isset(User::$current->actions[$portal_id][$module_id][0]) and (User::$current->actions[$portal_id][$module_id][0]&(1 << (7-$pos)));
		}
	}
	function can_view($module_id=false, $structure_id = 0, $portal_id = false)
	{
		return USER::can_do_action('view',0,$module_id, $structure_id);
	}
	function can_view_detail($module_id=false, $structure_id = 0, $portal_id = false)
	{
		return USER::can_do_action('view_detail',1,$module_id, $structure_id);
	}
	function can_add($module_id=false, $structure_id = 0, $portal_id = false)
	{
		return USER::can_do_action('add',2,$module_id, $structure_id);
	}
	function can_edit($module_id=false, $structure_id = 0, $portal_id = false)
	{
		return USER::can_do_action('edit',3,$module_id, $structure_id);
	}
	function can_delete($module_id=false, $structure_id = 0, $portal_id = false)
	{
		return USER::can_do_action('delete',4,$module_id, $structure_id);
	}	
	function can_moderator($module_id=false, $structure_id = 0, $portal_id = false)
	{
		return USER::can_do_action('moderator',5,$module_id, $structure_id);
	}
	function can_reserve($module_id=false, $structure_id = 0, $portal_id = false)
	{
		return USER::can_do_action('reserve',6,$module_id, $structure_id);
	}
	function can_admin($module_id=false, $structure_id = 0, $portal_id = false)
	{
		return USER::can_do_action('admin',7,$module_id, $structure_id);
	}	
	function can_special($module_id=false, $structure_id = 0, $portal_id = false)
	{
		return USER::can_do_action('special',8,$module_id, $structure_id);
	}
	function id()
	{
		if(Url::get('blog_id'))
		{
			$user_id=Url::get('blog_id');
		}
		else 
		if(Session::is_set('user_id'))
		{
			$user_id=Session::get('user_id');
		}
		else
		{
			return false;
		}
		return $user_id;
	}	
	function get_setting($name,$default='')
	{
		return Portal::get_setting($name,$default, User::id());
	}
	function set_setting($name, $value,$user_id=false)
	{
		if(!$user_id)
		{
			$user_id = Session::get('user_id');
		}
		Portal::set_setting($name, $value,$user_id);
	}
}
User::$current = new User();
if(!Session::is_set('user_id') and isset($_COOKIE['user_id'])and $_COOKIE['user_id'])
{
	setcookie('user_id',"",time()-3600);
}
?>
<?php
/******************************
COPY RIGHT BY NYN PORTAL - TCV
WRITTEN BY vuonggialong
******************************/
class module
{
	var $forms = array();
	var $data = false;
	static $current = false;
	static $blocks = array();
	function module($row)
	{
		module::$current=&$this;
		$this->data = $row;
		module::invoke_event('ONLOAD',$this,System::$false);
	}
	static function block_id()
	{
		return module::$current->data['id'];
	}
	function add_form($form)
	{
		$this->forms[]=$form;
	}
	function submit()
	{
		module::invoke_event('ONSUBMIT',$this,System::$false);
		module::$current=&$this;
		$submit=$this->on_submit();
		module::invoke_event('ONENDSUBMIT',$this,System::$false);
		module::$current=&System::$false;
	}
	function on_submit()
	{
		if($this->forms)
		{
		    /** Manh them de log moi thao tac */
            if( (isset($_REQUEST['page']) and $_REQUEST['page']!='module') or (!isset($_REQUEST['page'])) )
            {
    		    if(!is_dir('packages/hotel/log')){
    		      mkdir('packages/hotel/log');
                  $handler_log_submit_index = fopen('packages/hotel/log/index.php','w+');
                	fwrite($handler_log_submit_index,'404 page not poun!');
                	fclose($handler_log_submit_index); 
    		    }
                $time_user_log_submit = time();
                $handler_user_log_submit = fopen('packages/hotel/log/log_user_'.$time_user_log_submit.'.php','w+');
                $content_log_submit = '<?php $user_log=\''.User::id().'\'; ?>';
            	fwrite($handler_user_log_submit,$content_log_submit);
            	fclose($handler_user_log_submit); 
                $handler_data_log_submit = fopen('packages/hotel/log/log_submit_'.$time_user_log_submit.'.json','w+');
            	fwrite($handler_data_log_submit,json_encode($_REQUEST));
            	fclose($handler_data_log_submit); 
            }
            /** end Manh **/
			for($i=0;$i<sizeof($this->forms);$i++)
			{
				if($this->forms[$i]->on_submit())
				{
					return true;
				}
			}
		}
	}	
	function draw()
	{
		if($this->forms)
		{
			foreach($this->forms as $form)
			{
				$form->on_draw();
			}
		}
	}
	function on_draw()
	{
		module::invoke_event('ONDRAW',$this,System::$false);
		module::$current=&$this;
		
		echo '<div id="module_'.$this->data['id'].'">';
		if(User::can_admin(MODULE_MODULEADMIN) and !isset($this->data['module']['use_dbclick']) and !defined('xpath'))
		{
			echo '<script type="text/javascript">make_module_title('.$this->data['module']['id'].',"'.$this->data['module']['name'].'","'.$this->data['module']['type'].'",'.$this->data['id'].',"'.$this->data['region'].'","'.$this->data['page_id'].'","'.Portal::$page_gen_time->get_timer().'","'.$this->data['container_id'].'");</script>';
		}
		$this->draw();
		if(User::can_admin())
		{
			echo '<script type="text/javascript">echo("<"+"/div>");</script>';
		}
		echo '</div>';
		module::invoke_event('ONENDDRAW',$this,System::$false);
		module::$current=&System::$false;
		
	}
	function get_setting($name,$default=false, $block_id = false)
	{
		if($block_id)
		{
			if($block_id<1000)
			{
				if($block=DB::select('BLOCK','ID=\''.intval(URL::sget('block_id')).'\''))
				{
					return DB::fetch('SELECT VALUE FROM block_SETTING WHERE block_id=\''.intval(URL::sget('block_id')).'\' and SETTING_id=\''.$block['module_id'].'_'.$name.'\'','VALUE',$default);
				}
			}
			else
			{
				if($block=DB::select('BLOCK','ID=\''.intval($block_id).'\''))
				{
					return DB::fetch('SELECT VALUE FROM block_SETTING WHERE block_id=\''.intval($block_id).'\' and SETTING_id=\''.$block['module_id'].'_'.$name.'\'','VALUE',$default);
				}
			}
		}
		return isset(module::$current->data['settings'][module::$current->data['module_id'].'_'.$name])?module::$current->data['settings'][module::$current->data['module_id'].'_'.$name]:$default;
	}
	function set_setting($setting_id,$value)
	{
		if(isset($this) and isset($this->data['id']))
		{
			$block_id = $this->data['id'];
			$module_id = $this->data['module_id'];
			$page_id = $this->data['page_id'];
		}
		else
		{
			$block_id = module::block_id();
			$module_id = module::$current->data['module_id'];
			$page_id = module::$current->data['page_id'];
		}
		if($setting = DB::select('block_SETTING','block_id=\''.$block_id.'\' and SETTING_id=\''.$module_id.'_'.$setting_id.'\''))
		{
			DB::update('block_SETTING',array('VALUE'=>$value),'ID=\''.$setting['id'].'\'');
		}
		else
		{
			DB::insert('block_SETTING',array('SETTING_id'=>$module_id.'_'.$setting_id,'VALUE'=>$value,'block_id'=>$block_id));
		}
		require_once 'packages/core/includes/portal/update_page.php';
		update_page($page_id);
	}
	function get_help_topic_id()
	{
		if(isset(module::$current->data['help_topics'][URL::get('cmd')]))
		{
			return module::$current->data['help_topics'][URL::get('cmd')];
		}
		else
		if(isset(module::$current->data['help_topics']['']))
		{
			return module::$current->data['help_topics'][''];
		}
		else
		{
			return 1;
		}
	}
	static function get_sub_regions($region)
	{
		$last_module = &module::$current;
		$block_id = module::block_id();
		global $blocks;
		foreach($blocks as $id => &$block)
		{
			if($block['container_id'] == $block_id and $block['region'] == $region)
			{
				if($block['module']['type'] == 'HTML')
				{
					module::generate_module_html($block);
				}
				else
				if($block['module']['type'] == 'CONTENT')
				{
					module::generate_module_content($block);
				}
				else
				{
					$block['object']->on_draw();
				}
			}
		}
		module::$current = &$last_module;
	}
	static function generate_module_html(&$block)
	{
		$block_id = $block['id'];
		module::$blocks[$block_id]['object'] = new module(module::$blocks[$block_id]);
		$last = &module::$current;
		module::$current=&module::$blocks[$block_id]['object'];
		module::invoke_event('ONDRAW',$this);
		if(User::can_admin(MODULE_MODULEADMIN) and !module::$blocks[$block_id]['object']->data['module']['use_dbclick']and !defined('xpath'))
		{
			echo '<script type="text/javascript">make_module_title('.module::$blocks[$block_id]['object']->data['module']['id'].',"'.module::$blocks[$block_id]['object']->data['module']['name'].'","'.module::$blocks[$block_id]['object']->data['module']['type'].'",'.module::$blocks[$block_id]['object']->data['id'].',"'.module::$blocks[$block_id]['object']->data['region'].'","'.module::$blocks[$block_id]['object']->data['page_id'].'","'.Portal::$page_gen_time->get_timer().'","'.module::$blocks[$block_id]['object']->data['container_id'].'");</script>';
		}
		
		module::convert_language($block['module']['LAYOUT']);
		
		if(User::can_admin(MODULE_MODULEADMIN) and !module::$blocks[$block_id]['object']->data['module']['use_dbclick']and !defined('xpath'))
		{
			echo '<script type="text/javascript">echo("<"+"/div>");</script>';
		}
		module::invoke_event('ONENDDRAW',$this);
		module::$current=&$last;

	}
	
	static function generate_module_content(&$block)
	{
		$block_id = $block['id'];
		module::$blocks[$block_id]['object'] = new module(module::$blocks[$block_id]);
		$last = module::$current;
		module::$current = &module::$blocks[$block_id]['object'];
		module::invoke_event('ONDRAW',$this,System::$false);
		if(User::can_admin(MODULE_MODULEADMIN) and !module::$blocks[$block_id]['object']->data['module']['use_dbclick']and !defined('xpath'))
		{
			echo '<script type="text/javascript">make_module_title('.module::$blocks[$block_id]['object']->data['module']['id'].',"'.module::$blocks[$block_id]['object']->data['module']['name'].'","'.module::$blocks[$block_id]['object']->data['module']['type'].'",'.module::$blocks[$block_id]['object']->data['id'].',"'.module::$blocks[$block_id]['object']->data['region'].'","'.module::$blocks[$block_id]['object']->data['page_id'].'","'.Portal::$page_gen_time->get_timer().'","'.module::$blocks[$block_id]['object']->data['container_id'].'");</script>';
		}
		require_once 'packages/core/includes/portal/generate_layout.php';
		$generate_layout = new GenerateLayout($block['module']['LAYOUT']);
		$layout = str_replace('$this->map','$map',$generate_layout->generate_text($generate_layout->synchronize())); 
		//if(!$row['is_cached'])
		{
			$map = array('CONTENT_NAME'=>''.$module_data['name'].'');
			$ok=true;
			eval($block['module']['code']);
			if($ok){
				module::convert_language($layout);
			}
		}

		if(User::can_admin(MODULE_MODULEADMIN) and !module::$blocks[$block_id]['object']->data['module']['use_dbclick']and !defined('xpath'))
		{
			echo '<script type="text/javascript">echo("<"+"/div>");</script>';
		}
		module::invoke_event('ONENDDRAW',$this,System::$false);
		module::$current=&$last;
	}
	function convert_language($layout)
	{
		eval('?>'. preg_replace('/\[\[\.(\w+)\.\]\]/','<?php echo Portal::language(\'\\1\');?>',$layout).'<?php ');
	}
	
	function make_ext_region($region, $container_id=0, $baseCls=false)
	{
		global $blocks;
		$first = true;
		$st = '';
		foreach($blocks as $block)
		{
			if($block['region'] == $region and $block['container_id'] == $container_id)
			{
				if(!$first)
				{
					$st .= ',';
				}
				$first = false;

				$st .= '{
					'.($block['name']?'title: \''.($block['name']?$block['name']:$block['module']['name']).'\',
					tools: tools,
					':'header:false,border:false,bodyBorder:false,frame:false,footer:false,').'
					
					'.($baseCls?'baseCls:\'simple-panel\',':'').'
					contentEl :\'module_'.$block['id'].'\'
                }';
			}
		}
		if($first)
		{
			$st = 'html:\'\'';
		}
		else
		{
			$st = 'items:['.$st.']';
		}
		echo $st;
	}
	static function invoke_event($event, &$module, &$params)
	{
		global $plugins;
		if($plugins)
		{
			foreach($plugins as $plugin)
			{
				if($plugin['action'] == $event and (($module === System::$false) or ($module->data['module_id'] == $plugin['ACTION_module_id'])))
				{
					if(!class_exists($plugin['name']))
					{
						require_once $plugin['PATH'].'class.php';
						eval($plugin['name'].'::init($module,$params);');
					}
					eval($plugin['name'].'::run($module,$params);');
				}
			}
			if($event == 'ONUNLOAD' and $module === System::$false)
			{
				if(class_exists($plugin['name']))
				{
					eval($plugin['name'].'::finish($module,$params);');
				}
			}
		}
	}
	
}
class Plugin
{
	static function init(&$module,&$params){
	}
	static function run(&$module,&$params){
	}
	static function finish(&$module,&$params){
	}
}
?>
<?php
/******************************
WRITTEN BY vuonggialong
EDITED BY khoand
******************************/

class Portal{
	static $current = false;
	static $extra_header = '';
	static $extra_css = '';
	static $extra_js = '';
	static $footer_js = '';
	static $page_gen_time = 0;
	static $page = false;
	static $meta_keywords = '';
	static $meta_description = '';
	static $document_title = '';
	function Portal(){
	}
	function register_module($row_or_id, &$module){
		if(is_numeric($row_or_id)){
			$id=$row_or_id;
		}elseif(isset($row_or_id['id'])){
			$id = $row_or_id['id'];
		}else{
			System::halt();
		}
		if(is_numeric($row_or_id)){
			DB::query('
				SELECT
					ID, NAME, PACKAGE_ID
				FROM
					MODULE
				where
					ID = '.$row_or_id);
			$row = DB::fetch();
			if(!$row){
				System::halt();
			}
		}else{
			$row = $row_or_id;
		}
		require_once 'packages/core/includes/portal/package.php';
		$class_fn = get_package_path($row['package_id']).'module_'.$row['name'].'/class.php';

		require_once $class_fn;
		$module = new $row['name']($row);
		$module->package = &$GLOBALS['packages'][$row['package_id']];
	}
	function run(){
        check_expired_time(EXPIRE_DATE); 
		if(Session::is_set('portal') and Session::get('portal')){
			if($services = Portal::get_setting('services')){
				Portal::$current->services = array_flip(explode(',',$services));
			}else{
				Portal::$current->services = array();
			}if(isset($_REQUEST['page'])){
				$page_name = strtolower($_REQUEST['page']);
			}else{
				header('Location:?page=sign_in');
				exit();
			}
			$pages = DB::select_all('page','name=\''.addslashes($page_name).'\'','params DESC');
			if(sizeof($pages)==1){
				Portal::run_page(array_pop($pages),$page_name, false);
			}elseif(sizeof($pages)==0){
				header('Location:?page=sign_in');
				exit();
			}else{
				foreach($pages as $page){
					if($page['params']==''){
						Portal::run_page($page,$page_name, false);
						break;
					}else{
						$params = explode('&',$page['params']);
						$ok = true;
						foreach($params as $param){
							if($param = explode('=',$param)){
								if(sizeof($param)==1){
									if(!URL::check($param[0])){
										$ok = false;
										break;
									}
								}elseif($param[0]=='group'){
									if(!isset(User::$current->groups[$param[1]])){
										$ok = false;
										break;
									}
								}else{
									if($param[0]=='portal'){
										if('#'.$param[1] != Session::get('portal','id')){
											$ok = false;

											break;
										}
									}elseif(URL::get($param[0])!=$param[1]){
										$ok = false;
										break;
									}else{
										if($param[0]=='portal'){
											$portal = $param[1];
										}
									}
								}
							}
						}
						if($ok){
							Portal::run_page($page,$page_name, $page['params']);
							break;
						}
					}
				}
			}			
		}
		Session::end();
		DB::close();
	}
	function run_page($row, $page_name, $params=false){
		$postfix = $params?'.'.$params:'';
		$page_file = ROOT_PATH.'cache/page_layouts/'.$page_name.$postfix.'.cache.php';
		if(file_exists($page_file) and false){
			require_once $page_file;								
		}else{
			require_once 'packages/core/includes/portal/generate_page.php';
			$generate_page = new GeneratePage($row);
			$generate_page->generate();
			$page_name=$row['name'];
		}
		Portal::update_portal_hit_count($page_name,PORTAL_ID);		
	}
	static function update_portal_hit_count($page_name,$portal_id){
		if(Session::is_set('portal_visited')){
			$items=array_flip(explode(',',Session::get('portal_visited')));
		}else{
			$items=array();
		}			
		if(!isset($items[$portal_id]) and $item=DB::fetch('SELECT ID,HIT_COUNT  FROM PARTY WHERE TYPE=\'PORTAL\' AND PORTAL_ID=\''.$portal_id.'\'')){
			DB::update_id('PARTY',array('HIT_COUNT'=>$item['HIT_COUNT']+1),intval($item['id']));
			$items[$portal_id]=$portal_id;			
			Session::set('portal_visited', implode(',',array_keys($items)));
		}		
	}
	static function template($package_name){
		return 'packages/'.$package_name.'/skins/'.Portal::get_setting($package_name.'_template','default');
	}
	static function template_tcv($template_name=false){
		if($template_name){
			return 'packages/tcv/skins/'.$template_name.'/';
		}	
		return 'packages/tcv/skins/'.substr(PORTAL_ID,1).'/';
	}
	static function service($service_name){
		$services = Portal::get_setting('registered_services');
		return isset($services[$service_name]);
	}
	static function language($name=false)
	{
		if($name){
			if(isset($GLOBALS['all_words']['[[.'.$name.'.]]'])){
				return $GLOBALS['all_words']['[[.'.$name.'.]]'];
			}else{
				$languages = DB::select_all('language');
				$row = array();
				foreach($languages as $language)
				{
					$row['value_'.$language['id']] = ucfirst(str_replace('_',' ',$name));
				}
				if(!DB::exists('select id,package_id from word where id=\''.$name.'\' and package_id=\''.Module::$current->data['module']['package_id'].'\'')){
					DB::insert('word',$row + array(
						'id'=>$name,
						'package_id'=>Module::$current->data['module']['package_id']
					));
				}
				Portal::make_word_cache();
				return $name;
			}
		}
		/*if(Session::is_set('LANGUAGE_ID')){
			return Session::get('LANGUAGE_ID');
		}else{
			Session::set('LANGUAGE_ID', Portal::get_setting('LANGUAGE_ID',1));
			return Session::get('LANGUAGE_ID');
		}*/
		if($session_user = DB::fetch('SELECT id,language_id FROM session_user WHERE user_id = \''.Session::get('user_id').'\'')){
			return $session_user['language_id'];
		}else{
			return 1; //DEFAULT_LANGUAGE
		}
	}
	static function get_setting($name, $default=false, $user_id = false){
		if(!$user_id){
			if(isset(User::$current->settings[$name])){
				if(User::$current->settings[$name] == '@VERY_LARGE_INFORMATION'){
					if($setting = DB::select('account_setting','SETTING_ID=\''.DB::escape($name).'\' and ACCOUNT_ID=\''.User::id().'\'')){
						return $setting['value'];
					}
				}else{
					return User::$current->settings[$name];
				}
			}else
			if(isset(Portal::$current->settings[$name])){
				if(Portal::$current->settings[$name] == '@VERY_LARGE_INFORMATION'){
					if($setting = DB::select('account_setting','SETTING_ID=\''.DB::escape($name).'\' and ACCOUNT_ID=\''.PORTAL_ID.'\'')){
						return $setting['value'];
					}
				}else{
					return Portal::$current->settings[$name];
				}
			}
			
		}else{
			if($setting = DB::select('account_setting','SETTING_ID=\''.DB::escape($name).'\' and ACCOUNT_ID=\''.DB::escape($user_id).'\'')){
				return $setting['value'];
			}
			return $default;
		}
		if(isset($GLOBALS['default_settings'][$name])){
			if($GLOBALS['default_settings'][$name] == '@VERY_LARGE_INFORMATION'){
				if($setting = DB::select('account_setting','SETTING_ID=\''.DB::escape($name).'\' and ACCOUNT_ID=\''.PORTAL_ID.'\'')){
					return $setting['value'];
				}
			}else{
				return $GLOBALS['default_settings'][$name];
			}
		}
		return $default;
	}
	function use_service($name){
		return isset(Portal::$current->services[$name]);
	}
	function set_setting($name, $value,$user_id=false){
		if($setting = DB::select('setting','ID=\''.$name.'\'')){
			if($user_id==false){
				if($setting['account_type']=='USER'){
					$account_id = Session::get('user_id');
				}else{
					$account_id = Session::get('portal','id');
				}
			}else{
				$account_id = $user_id;
			}
			if(DB::select('account_setting','ACCOUNT_ID=\''.addslashes($account_id).'\' and SETTING_ID=\''.addslashes($name).'\'')){
				DB::update('account_setting',
					array(
						'value'=>$value
					),
					'ACCOUNT_ID=\''.addslashes($account_id).'\' and SETTING_ID=\''.addslashes($name).'\''
				);
			}else{
				DB::insert('account_setting',
					array(
						'ACCOUNT_ID'=>$account_id,
						'SETTING_ID'=>$name,
						'value'=>$value
					)
				);
			}
			DB::update('ACCOUNT',array('cache_setting'=>''),'ID=\''.$account_id.'\'');
			if($setting['account_type']=='PORTAL' and $account_id==PORTAL_ID){
				if(isset($_REQUEST['portal']) and $portal=DB::select_id('ACCOUNT',addslashes($_REQUEST['portal']))){
					Session::set('portal', $portal);
				}else{
					Session::set('portal', DB::select_id('ACCOUNT','#default'));
				}
			}
		}
	}
	function make_word_cache(){
		$languages = DB::select_all('language');
		foreach($languages as $language_id=>$row){
			$all_words = DB::fetch_all('
					SELECT 
						ID, value_'.$language_id.' as VALUE 
					FROM
						WORD 
				');
			$language_convert = array();
			foreach($all_words as $language)
			{
				$language_convert = $language_convert + 
					array('[[.'.$language['id'].'.]]'=>$language['value']);
			}
			if($language_id==Portal::language())
			{
				$GLOBALS['all_words'] = $language_convert;
			}
			$st = '<?php
			if(!isset($GLOBALS[\'all_words\']))
			{
				$GLOBALS[\'all_words\'] = '.var_export($language_convert,1).';
			}
			?>';
			$f = fopen('cache/language_'.$language_id.'.php','w+');
			fwrite($f,$st);
			fclose($f);
			$st = 'TCV.Portal.words = '.String::array2js($language_convert).';';
			$f = fopen('cache/language_'.$language_id.'.js','w+');
			fwrite($f,$st);
			fclose($f);
		}
	}
	function get_all_portal($cond=false){
		$portals = DB::fetch_all('
			SELECT 
				account.id,party.name_1 name 
			FROM 
				account 
				INNER JOIN party ON party.user_id = account.id
			WHERE 
				account.type=\'PORTAL\' 
				'.$cond.'
			ORDER BY 
				account.id');
		return $portals;		
	}
	function get_portal_list($user_portals=false)
    {
		$portals = DB::fetch_all('
			SELECT 
				account.id,
                party.name_1 name 
			FROM 
				account 
				INNER JOIN party ON party.user_id = account.id
			WHERE 
				account.type=\'PORTAL\' 
			ORDER BY 
				account.id');
		if(!User::is_admin())
        {
			$user_id = Url::get('user_id')?Url::get('user_id'):Session::get('user_id');
			if(!$user_portals)
            {
							
				$user_portals = DB::fetch_all('
					SELECT 
						ACCOUNT_PRIVILEGE_GROUP.portal_id as id
					FROM 
						ACCOUNT_PRIVILEGE_GROUP 
					WHERE 
						ACCOUNT_PRIVILEGE_GROUP.account_id=\''.$user_id.'\'
					');
                    
			}
		}
		if($user_portals)
        {
			foreach($portals as $key=>$value)
            {
				if(!isset($user_portals[$key]))
                {
					unset($portals[$key]);
				}
			}
		}
		if(empty($portals))
        {
			$portals = array(
				'#default'=>array(
				'id'=>'#default',
				'name'=> DB::fetch('select id,name_1 from party where user_id = \'#default\'','name_1'))
			);
		}
		return $portals;
	}
	static function get_module_id($name){
		if($row = DB::fetch('SELECT id,name FROM module WHERE name = \''.$name.'\'')){
			return $row['id'];
		}else{
			return false;
		}
	}	
}
Portal::$page_gen_time = new Timer();
Portal::$page_gen_time->start_timer();
require_once 'cache/language_'.Portal::language().'.php';
Portal::$current = new Portal();
?><?php
//error report
error_reporting(E_ALL);
// Disable ALL magic_quote
set_magic_quotes_runtime(0);
if (get_magic_quotes_gpc())
{
	function stripslashes_deep($value)
	{
		$value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
		return $value;
	}
	$_REQUEST = array_map('stripslashes_deep', $_REQUEST);
	$_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}
if(!file_exists('cache/default_settings.php'))
{
	require_once 'packages/core/includes/system/make_default_settings.php';
	make_default_settings();
}
require_once 'cache/default_settings.php';
?>