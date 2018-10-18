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
COPY RIGHT BY NYN PORTAL - TCV
WRITTEN BY vuonggialong
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
	function send_mail($from,$to,$subject,$content)
	{
		if(!class_exists('PHPMailer'))
		{
			require(ROOT_PATH.'packages/core/includes/utils/mailer/class.phpmailer.php');
		}	
		$mail = new PHPMailer();		
		$mail->IsSMTP();
		$mail->SetLanguage("vn", "");
		$mail->Host     = "webmail.trangvangdulich.com";
		$mail->SMTPAuth = true;
		
		////////////////////////////////////////////////
		// Ban hay sua cac thong tin sau cho phu hop
		
		$mail->Username = "ngocnv1784@gmail.com";				// SMTP username
		$mail->Password = "chienthang"; 				// SMTP password
		
		$mail->From     = "admin@vesnahotel.com.vn";				// Email duoc gui tu???
		$mail->FromName = "Admin";					// Ten hom email duoc gui
		$mail->AddAddress($to,"");	 	// Dia chi email va ten nhan
		$mail->AddReplyTo($from,"Information");		// Dia chi email va ten gui lai
		
		$mail->IsHTML(true);						// Gui theo dang HTML
		
		$mail->Subject  =  $subject;				// Chu de email
		$mail->Body     =  $content;		// Noi dung html
		if(!$mail->Send())
		{
		   echo "Email chua duoc gui di! <p>";
		   echo "Loi: " . $mail->ErrorInfo;
		   echo '<br><a href="'.URL::build('lost_password').'">Back</a>';
		   exit;
		}
		else
		{
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
		DB::insert('LOG', array('TYPE'=>$type, 'MODULE_ID'=>is_object(Module::$current)?Module::block_id():0,
			'TITLE'=>$title, 'DESCRIPTION'=>$description, 'PARAMETER'=>$parameter, 'NOTE'=>$note, 'TIME'=>time(),'USER_ID'=>$user_id?$user_id:is_object(User::$current)?User::id():0));
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
		if($num==round($num))
		{
			return number_format($num,0,'','.');
		}
		else
		{
			return number_format($num,2,'.',',');
		}
	}
	function display_number_report($num)
	{
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
			$st .= $name.'_'.$language['ID'];
		}
		return $st;
	}
	function display_sort_title($str,$word_number)
	{
		$c = str_word_count($str);
		$array1=array($c);
		$new_str='';
		if($c/2>$word_number)
		{
			$array1 = explode(" ",$str);
			$i=0;
			while($i<sizeof($array1))
			{
				if($i<$word_number)
				{
					$new_str.=$array1[$i].' ';
				}
				$i++;
			}
			return $new_str.'...';
		}
		else
		{
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
	function array2tree(&$items,$items_name)
	{
		//$structure_ids = array(ID_ROOT=>1);
		$show_items = array();
		$min = -1;
		foreach($items as $item)
		{
			if($min==-1)
			{
				$min = IDStructure::level($item['STRUCTURE_ID']);
			}
			$structure_ids[number_format($item['STRUCTURE_ID'],0,'','')] = $item['id'];
			//echo number_format($item['structure_id'],0,'','').'<br>';
			if(IDStructure::level($item['STRUCTURE_ID'])<=$min)
			{
				$show_items[$item['ID']] = $item+(isset($item['CHILDS'])?array():array($items_name=>array()));
			}
			else
			{
				$st = '';
				$parent = $item['STRUCTURE_ID'];
				
				while(($level=IDStructure::level($parent = IDStructure::parent($parent)))>=$min and $parent and isset($structure_ids[number_format($parent,0,'','')]))
				{
					
					$st = '['.$structure_ids[number_format($parent,0,'','')].'][\''.$items_name.'\']'.$st;
					
				}
				//echo number_format($parent,0,'','').' '.$st.'<br>';
				if($level<$min or $level==0)
				{
					//echo '$show_items'.$st.'['.$item['id'].']<br>';
					eval('$show_items'.$st.'['.$item['id'].'] = $item+array($items_name=>array());');
				}
			}
		}
		return $show_items;
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
	}
	function get_list($items, $field_name=false)
	{
		
		$item_list = array();
		foreach($items as $id=>$item)
		{
			if(isset($item['STRUCTURE_ID']) and !User::can_view(MODULE_MANAGECONTENT,$item['STRUCTURE_ID']))
			{
				unset($items[$id]);
			}
		}
		foreach($items as $item)
		{	
			if(!$field_name)
			{
				$field_name=isset($item['NAME'])?'NAME':(isset($item['TITLE'])?'TITLE':(isset($item['NAME_'.Portal::language()])?'NAME_'.Portal::language():(isset($item['TITLE_'.Portal::language()])?'TITLE_'.Portal::language():'ID')));
			}
			if(isset($item['STRUCTURE_ID']))
			{
				$level = IDStructure::level($item['STRUCTURE_ID']);
				for($i=0;$i<$level;$i++)
				{
					$item[$field_name] = ' --- '.$item[$field_name];
				}
			}
			$item_list[$item['ID']]=isset($item[$field_name])?$item[$field_name]:'';
		}
		return $item_list;
	}
}
class Date_Time
{
	function to_sql_date($date)
	{
		$a = explode('/',$date);
		if(sizeof($a)==3 and is_numeric($a[1]) and is_numeric($a[2]) and is_numeric($a[0]) and checkdate($a[1],$a[0],$a[2]))
		{
			return ($a[2].'-'.$a[1].'-'.$a[0]);
		}
		else
		{
			return false;
		}
	}
	function to_common_date($date)
	{
		$a = explode('-',$date);
		
		if(sizeof($a)==3 and $a[0]!='0000')
		{
			return ($a[2].'/'.$a[1].'/'.$a[0]);
		}
		else
		{
			return false;
		}	
	}
	// format 01/01/2006
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
		$time=date('d/m/Y',$time);
		return $time;
	}
	function daily($time)
	{
		$daily=(getdate($time));
		return $daily['weekday'];
	}
}
?>
<?php
/****************************
	writtend by THANHPT
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
		DB::$db_connect_id =  @oci_connect($user_name,$password,$db);
		if(isset(DB::$db_connect_id) and DB::$db_connect_id)
		{
			return DB::$db_connect_id;		
		}	
		else
		{
			DB::db_error();
			return false;	
		}
	}
	function db_error($db_connect_id = false)
	{
		$error = $db_connect_id?oci_error($db_connect_id):oci_error();
		if(isset($error['message'])) 
		{
			return $error['message'];
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
		return DB::fetch('SELECT  count(*) as TOTAL FROM '.strtoupper($table).' '.($condition?' WHERE '.$condition:''),'TOTAL');
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
			if (!$result) 
			{
				DB::db_error($result);
			}
			if(!oci_commit(DB::$db_connect_id))
			{
				DB::db_error(DB::$db_connect_id);
			}
			DB::$db_num_queries++;
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
				DB::$db_exists_db[$table][$id]=DB::exists('SELECT * FROM '.strtoupper($table).' WHERE ID = '.$id.'  AND rownum = 1');
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
		$item = DB::fetch('SELECT max(ID) AS ID FROM '.strtoupper($table));
		$id = intval($item['ID'])+1;
		$values = array('ID'=>$id)+$values;
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
			if(DB::query($query))
			{			
				return $id;
			}
		}
	}
	static function delete($table, $condition)
	{
		$query='DELETE FROM '.strtoupper($table).' WHERE '.$condition;
		if(DB::query($query))
		{		
			return true;
		}
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
					if($value=='NULL')
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
				$rows[$row['ID']] = $row;
			}
			return $rows;
		}
	}
	static function escape($sql)
	{
		return stripslashes($sql);
	}
	static function num_queries()
	{
		return DB::$db_num_queries;
	}
	static function structure_id($table,$id)
	{
		$row=DB::select($table,'ID = \''.$id.'\'');
		return $row['STRUCTURE_ID'];
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
		if(isset($item['NAME']))
		{
			return 'NAME';
		}
		else
		if(isset($item['TITLE']))
		{
			return 'TITLE';
		}
		else
		if(isset($item['NAME_'.Portal::language()]))
		{
			return 'NAME_'.Portal::language();
		}
		else
		if(isset($item['TITLE_'.Portal::language()]))
		{
			return 'TITLE_'.Portal::language();
		}
	}
	function get_fields($table)
	{
		if(!empty($table))
		{
			DB::query('SELECT * FROM '.strtoupper($table).' WHERE rownum = 1');
		}
		$query_id = DB::$db_result;
		$fields = array();
		if($query_id)
		{
			$number_field = oci_num_fields($query_id);	
			for($i=1;$i<=$number_field;$i++)
			{
				$fields[$i] = oci_field_name($query_id,$i);
			}
		}
		return $fields;
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
			DB::update_id($table,array('HIT_COUNT'=>$item['HIT_COUNT']+1),intval($id));
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
$db = new DB('thanhpt','123456');
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
				ID=\''.addslashes(Session::$name).'\'
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
			if($session=DB::select('SESSION','ID=\''.addslashes(Session::$name).'\''))
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
		DB::delete('SESSION','ID=\''.addslashes(Session::$name).'\'');
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
******************************/

class Url
{
	var  $root = false;
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
		return URL::build(Portal::$page['NAME'],$params,$smart,Url::get('portal'),$anchor);
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
		URL::redirect(Portal::$page['NAME'],$params+array('portal'),$anchor);
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
		header('Location:'.str_replace('&','&','http://'.$_SERVER['SERVER_NAME'].'/'.$url));
		System::halt();
	}
	function access_denied()
	{
		if(Portal::$page['NAME']!='home')
		{
			Url::redirect('home');
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
	function iget($name){
		return intval(Url::sget($name));
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
		return '(FIND_IN_SET(STRUCTURE_ID,"0,'.$path.'")>0)';
	}
	//Tra ve bieu thuc dieu kien truy van tat ca con cua $id
	//$structure_id: can tinh dieu kien
	//$except_me: co loai tru chinh $structure_id nay khong
	
	function child_cond($structure_id, $except_me = false,$extra = '')
	{	
		if($except_me)
		{
			return '('.$extra.'STRUCTURE_ID > '.$structure_id.' and '.$extra.'STRUCTURE_ID < '.IDStructure::next($structure_id).')';
		}
		else
		{
			return '('.$extra.'STRUCTURE_ID >= '.$structure_id.' and '.$extra.'STRUCTURE_ID < '.IDStructure::next($structure_id).')';
		}
	}
	//Tra ve bieu thuc dieu kien truy van tat ca con truc tiep cua $id (truc tiep nghia la co level = level ($structure_id)-1)
	//$structure_id: can tinh dieu kien
	function direct_child_cond($structure_id, $child_level=1)
	{	
		$level = IDStructure::level($structure_id);
		$child_offset = number_format(pow(ID_BASE, ID_MAX_LEVEL-($level+$child_level)),0,'','');
		return '('.IDStructure::child_cond($structure_id, true).' and (STRUCTURE_ID % '.$child_offset.'=0)) ';
	}
}
?>
<?php
/******************************
COPY RIGHT BY NYN PORTAL - TCV
WRITTEN BY vuonggialong
******************************/

class Type
{
	var $required = false;
	var $error = false;
	var $message = false;
	var $name = false;
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
	function TextType($required=false, $messages=false, $min_len, $max_len)
	{
		Type::Type($required, $messages);
		$this->min_len = $min_len;
		$this->max_len = $max_len;
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
			
			if(!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,6}$",$value) )
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
	function FloatType($required=false, $messages=false, $min=0, $max=1000000000)
	{
		
		Type::Type($required, $messages);
		$this->min = $min;
		$this->max = $max;
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
	function IntType($required=false, $messages=false, $min=0, $max=9999)
	{
		FloatType::FloatType($required, $messages, $min, $max);
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
	function IDType($required=false, $messages=false, $table='')
	{
		echo 
		NameType::NameType($required, $messages,1,50);
		$this->table=$table;
	}
	function to_js_data($name, $word)
	{
		return '{"name":"'.$name.'","message":"'.addslashes($word).'","type":"name","require":'.($this->required?1:0).',"min":'.$this->min_len.',"max":'.$this->max_len.'}';
	}
	function check($value)
	{
		$value=str_replace(',','',$value);
		if($value!='')
		{
			if(!DB::select($this->table,'ID=\''.$value.'\''))
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
	function UniqueType($messages=false, $table, $field)
	{
		Type::Type(true, $messages, 0, 999999);
		$this->table=$table;
		$this->field=$field;
	}
	function check($value)
	{
		if(Type::check($value) && $value!='')
		{
			$cond = '';
			if (isset($_REQUEST['id']) && $_REQUEST['id'])
			{
				$cond = 'ID != \''.$_REQUEST['id'].'\' and ';
			}
			if(DB::exists('SELECT ID FROM '.$this->table.' WHERE '.$cond.' '.$this->field.'=\''.DB::escape($value).'\''))
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
	function link_css($file_name)
	{
		if(strpos(Portal::$extra_header,'<LINK rel="stylesheet" href="'.$file_name.'" type="text/css">')===false)
		{
			Portal::$extra_header .= '
<LINK rel="stylesheet" href="'.$file_name.'" type="text/css">';
		}
	}
	function link_js($file_name)
	{
		if(strpos(Portal::$extra_header,'<script type="text/javascript" src="'.$file_name.'"></script>')===false)
		{
			Portal::$extra_header .= '
<script type="text/javascript" src="'.$file_name.'"></script>';
		}
	}
	function auto_refresh($time, $url)
	{
		Portal::$extra_header .= '<META HTTP-EQUIV="Refresh" CONTENT="'.$time.'; URL='.$url.'">';
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
	function check($exclude=array())
	{	
		if($this->is_submit())
		{
			$this->errors = false;
			if($this->inputs)
			{
				foreach ($this->inputs as $name=>$types)
				{
					foreach($types as $type)
					{
						if(!in_array($name,$exclude))
						{
							if(!strpos($name,'.'))
							{
								if(!$type->check($_REQUEST[$name]))
								{
									$this->errors[$name][] = $type;
								}
							}
							else
							{
								$names = explode('.',$name);
								$table = 'mi_'.$names[0];
								$field = $names[1];
								if(isset($_REQUEST[$table]))
								{
									if(is_array($_REQUEST[$table]))
									{
										foreach($_REQUEST[$table] as $key=>$record)
										{
											if(isset($record[$field]))
											{
												if(!$type->check($record[$field]))
												{
													$this->errors[$table.'['.$key.']['.$field.']'][] = $type;
												}
											}
											else
											{
												if(!$type->check(''))
												{
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
	function error($name, $message)
	{
		$this->error_messages[$name][]=Portal::language($message);
	}
	function parse_layout($name, $params=array())
	{
		$dir = ROOT_PATH.'cache/modules/'.Module::$current->data[(Module::$current->data['MODULE']['TYPE']!='WRAPPER')?'MODULE':'WRAPPER']['NAME'];
		$cache_file_name = $dir.'/'.$name.'.php';
		$file_name = Module::$current->data[(Module::$current->data['MODULE']['TYPE']!='WRAPPER')?'MODULE':'WRAPPER']['PATH'].'layouts/'.$name.'.php';

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
			$notify = 'Th&#244;ng b&#225;o l&#7895;i';
		}
		else
		{
			$notify = 'Errors';
		}
		$txt = '<fieldset id="error_messages_'.$this->count.'"'.$show.'><table style="margin-top:5px;" bgcolor="#FFFFF2"><tr valign="top">';
		$txt .= '<td nowrap><div style="font-weight:bold;color:#FF0000">'.$notify.'</div><div align="center"><img src="packages/core/skins/default/images/icon/warning.gif"></div></td>';
		$txt.='<td style=""font-family:Arial, Helvetica, sans-serif;color:#000000;"" width="100%" id="error_messages_content'.$this->count.'" >';
		if($this->error_messages)
		{
			foreach ($this->error_messages as $name=>$error_messages)
			{
				foreach($error_messages as $error_message)
				{
					if(trim($this->name))
					{
						$txt .= '<li><a javascript:void(0) style="color:#3B3B3B" onclick = "javascript:if(typeof(document.forms.'.$this->name.')!=\'undefined\'){document.forms.'.$this->name.'.namedItem(\''.$name.'\').focus();document.forms.'.$this->name.'.namedItem(\''.$name.'\').style.backgroundColor=\'#FFFFF2\';}" title="&#7844;n v&#224;o &#273;&#226;y &#273;&#7875; xem v&#7883; tr&#237; x&#7843;y ra l&#7895;i">'.$error_message.'</a>';
					}
					else
					{
						$txt .= '<li>'.$error_message;
					}
					$txt .= '<br>';
				}
			}
		}
		$txt .= '</td></tr></table></fieldset>';
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
}
Form::$current=&System::$false;
?>
<?php
/******************************
COPY RIGHT BY NYN PORTAL - TCV
WRITTEN BY vuonggialong
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
				if($this->data['CACHE_PRIVILEGE']=='')
				{
					require_once 'packages/core/includes/system/make_user_privilege_cache.php';
					eval(make_user_privilege_cache(Session::get('user_id')));
				}
				else
				{
					eval($this->data['CACHE_PRIVILEGE']);
				}
				if(!$this->data['CACHE_SETTING'])
				{
					require_once 'packages/core/includes/system/make_account_setting_cache.php';
					$code = make_account_setting_cache(Session::get('user_id'));
					eval('$this->settings='.$code);
				}
				else
				{
					eval('$this->settings='.$this->data['CACHE_SETTING']);
				}
			}
		}
	}
	function is_login()
	{
		return Session::is_set('user_id') and Session::get('user_id')!='guest' and DB::exists_id('ACCOUNT',Session::get('user_id'));
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
		return md5($password.'vuonggialong');
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
			if($group['home_page']=='')
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
		if(!$module_id)
		{
			if(isset(Module::$current->data))
			{
				$module_id = Module::$current->data['MODULE']['ID'];
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
	function get_status_by_user_id()
	{
		if(User::is_admin() or User::can_admin(MODULE_MANAGECONTENT,ANY_CATEGORY))
		{
			return 'SHOW';
		}
		else if($account_privilege = DB::select('ACCOUNT_PRIVILEGE',' ACCOUNT_ID = \''.Session::get('user_id').'\''))
		{
			$status = DB::select('PRIVILEGE','ID='.$account_privilege['PRIVILEGE_ID']);
			return $status['STATUS'];
		}	
		return '';
	}
	function get_privilege()
	{
		if(User::is_admin() or User::can_admin(MODULE_MANAGECONTENT,ANY_CATEGORY))
		{
			return 'Administrator';
		}
		else if($account_privilege = DB::select('ACCOUNT_PRIVILEGE',' ACCOUNT_ID = \''.Session::get('user_id').'\''))
		{
			$status = DB::select('PRIVILEGE','ID='.$account_privilege['PRIVILEGE_ID']);
			return $status['TITLE_'.Portal::language()];
		}	
		return 'guest';
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
	//-----------------Cac ham` cua estore-----------------------
	//neu co portal_id Ham tra lai portal_id neu ko tra lai user_id
	function get_estore_id()
	{
		if(Url::get('portal'))
		{
			$user_id='#'.addslashes(Url::get('portal'));
		}
		else 
		if(Session::is_set('portal','id'))
		{
			$user_id=Session::get('portal','id');
		}
		else
		{
			return false;
		}
		return $user_id;
	}
	function  estore_can_edit($user_id)
	{
		if(Url::get('portal'))
		{
			return false;
		}
		else
		{
			if(USER::can_admin())
			{
				return true;
			}
			else
			{
				return false;
			}
		}
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
class Module
{
	var $forms = array();
	var $data = false;
	static $current = false;
	static $blocks = array();
	function Module($row)
	{
		Module::$current=&$this;
		$this->data = $row;
		Module::invoke_event('ONLOAD',$this,System::$false);
	}
	static function block_id()
	{
		return Module::$current->data['ID'];
	}
	function add_form($form)
	{
		$this->forms[]=$form;
	}
	function submit()
	{
		Module::invoke_event('ONSUBMIT',$this,System::$false);
		Module::$current=&$this;
		$submit=$this->on_submit();
		Module::invoke_event('ONENDSUBMIT',$this,System::$false);
		Module::$current=&System::$false;
	}
	function on_submit()
	{
		if($this->forms)
		{
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
		Module::invoke_event('ONDRAW',$this,System::$false);
		Module::$current=&$this;
		
		echo '<div id="module_'.$this->data['ID'].'">';
		if(User::can_admin(MODULE_MODULEADMIN) and !$this->data['MODULE']['USE_DBLCLICK']and !defined('XPATH'))
		{
			echo '<script type="text/javascript">make_module_title('.$this->data['MODULE']['ID'].',"'.$this->data['MODULE']['NAME'].'","'.$this->data['MODULE']['TYPE'].'",'.$this->data['ID'].',"'.$this->data['REGION'].'","'.$this->data['PAGE_ID'].'","'.Portal::$page_gen_time->get_timer().'","'.$this->data['CONTAINER_ID'].'");</script>';
		}
		$this->draw();
		if(User::can_admin())
		{
			echo '<script type="text/javascript">echo("<"+"/div>");</script>';
		}
		echo '</div>';
		Module::invoke_event('ONENDDRAW',$this,System::$false);
		Module::$current=&System::$false;
		
	}
	function get_setting($name,$default=false, $block_id = false)
	{
		if($block_id)
		{
			if($block_id<1000)
			{
				if($block=DB::select('BLOCK','ID=\''.intval(URL::sget('block_id')).'\''))
				{
					return DB::fetch('SELECT VALUE FROM BLOCK_SETTING WHERE BLOCK_ID=\''.intval(URL::sget('block_id')).'\' and SETTING_ID=\''.$block['MODULE_ID'].'_'.$name.'\'','VALUE',$default);
				}
			}
			else
			{
				if($block=DB::select('BLOCK','ID=\''.intval($block_id).'\''))
				{
					return DB::fetch('SELECT VALUE FROM BLOCK_SETTING WHERE BLOCK_ID=\''.intval($block_id).'\' and SETTING_ID=\''.$block['MODULE_ID'].'_'.$name.'\'','VALUE',$default);
				}
			}
		}
		return isset(Module::$current->data['SETTINGS'][Module::$current->data['MODULE_ID'].'_'.$name])?Module::$current->data['SETTINGS'][Module::$current->data['MODULE_ID'].'_'.$name]:$default;
	}
	function set_setting($setting_id,$value)
	{
		if(isset($this) and isset($this->data['ID']))
		{
			$block_id = $this->data['ID'];
			$module_id = $this->data['MODULE_ID'];
			$page_id = $this->data['PAGE_ID'];
		}
		else
		{
			$block_id = Module::block_id();
			$module_id = Module::$current->data['MODULE_ID'];
			$page_id = Module::$current->data['PAGE_ID'];
		}
		if($setting = DB::select('BLOCK_SETTING','BLOCK_ID=\''.$block_id.'\' and SETTING_ID=\''.$module_id.'_'.$setting_id.'\''))
		{
			DB::update('BLOCK_SETTING',array('VALUE'=>$value),'ID=\''.$setting['ID'].'\'');
		}
		else
		{
			DB::insert('BLOCK_SETTING',array('SETTING_ID'=>$module_id.'_'.$setting_id,'VALUE'=>$value,'BLOCK_ID'=>$block_id));
		}
		require_once 'packages/core/includes/portal/update_page.php';
		update_page($page_id);
	}
	function get_help_topic_id()
	{
		if(isset(Module::$current->data['HELP_TOPICS'][URL::get('cmd')]))
		{
			return Module::$current->data['HELP_TOPICS'][URL::get('cmd')];
		}
		else
		if(isset(Module::$current->data['HELP_TOPICS']['']))
		{
			return Module::$current->data['HELP_TOPICS'][''];
		}
		else
		{
			return 1;
		}
	}
	static function get_sub_regions($region)
	{
		$last_module = &Module::$current;
		$block_id = Module::block_id();
		global $blocks;
		foreach($blocks as $id => &$block)
		{
			if($block['CONTAINER_ID'] == $block_id and $block['REGION'] == $region)
			{
				if($block['MODULE']['TYPE'] == 'HTML')
				{
					Module::generate_module_html($block);
				}
				else
				if($block['MODULE']['TYPE'] == 'CONTENT')
				{
					Module::generate_module_content($block);
				}
				else
				{
					$block['OBJECT']->on_draw();
				}
			}
		}
		Module::$current = &$last_module;
	}
	static function generate_module_html(&$block)
	{
		$block_id = $block['ID'];
		Module::$blocks[$block_id]['OBJECT'] = new Module(Module::$blocks[$block_id]);
		$last = &Module::$current;
		Module::$current=&Module::$blocks[$block_id]['OBJECT'];
		Module::invoke_event('ONDRAW',$this);
		if(User::can_admin(MODULE_MODULEADMIN) and !Module::$blocks[$block_id]['OBJECT']->data['MODULE']['USE_DBLCLICK']and !defined('XPATH'))
		{
			echo '<script type="text/javascript">make_module_title('.Module::$blocks[$block_id]['OBJECT']->data['MODULE']['ID'].',"'.Module::$blocks[$block_id]['OBJECT']->data['MODULE']['NAME'].'","'.Module::$blocks[$block_id]['OBJECT']->data['MODULE']['TYPE'].'",'.Module::$blocks[$block_id]['OBJECT']->data['ID'].',"'.Module::$blocks[$block_id]['OBJECT']->data['REGION'].'","'.Module::$blocks[$block_id]['OBJECT']->data['PAGE_ID'].'","'.Portal::$page_gen_time->get_timer().'","'.Module::$blocks[$block_id]['OBJECT']->data['CONTAINER_ID'].'");</script>';
		}
		
		Module::convert_language($block['MODULE']['LAYOUT']);
		
		if(User::can_admin(MODULE_MODULEADMIN) and !Module::$blocks[$block_id]['OBJECT']->data['MODULE']['USE_DBLCLICK']and !defined('XPATH'))
		{
			echo '<script type="text/javascript">echo("<"+"/div>");</script>';
		}
		Module::invoke_event('ONENDDRAW',$this);
		Module::$current=&$last;

	}
	
	static function generate_module_content(&$block)
	{
		$block_id = $block['ID'];
		Module::$blocks[$block_id]['OBJECT'] = new Module(Module::$blocks[$block_id]);
		$last = Module::$current;
		Module::$current = &Module::$blocks[$block_id]['OBJECT'];
		Module::invoke_event('ONDRAW',$this,System::$false);
		if(User::can_admin(MODULE_MODULEADMIN) and !Module::$blocks[$block_id]['OBJECT']->data['MODULE']['USE_DBLCLICK']and !defined('XPATH'))
		{
			echo '<script type="text/javascript">make_module_title('.Module::$blocks[$block_id]['OBJECT']->data['MODULE']['ID'].',"'.Module::$blocks[$block_id]['OBJECT']->data['MODULE']['NAME'].'","'.Module::$blocks[$block_id]['OBJECT']->data['MODULE']['TYPE'].'",'.Module::$blocks[$block_id]['OBJECT']->data['ID'].',"'.Module::$blocks[$block_id]['OBJECT']->data['REGION'].'","'.Module::$blocks[$block_id]['OBJECT']->data['PAGE_ID'].'","'.Portal::$page_gen_time->get_timer().'","'.Module::$blocks[$block_id]['OBJECT']->data['CONTAINER_ID'].'");</script>';
		}
		require_once 'packages/core/includes/portal/generate_layout.php';
		$generate_layout = new GenerateLayout($block['MODULE']['LAYOUT']);
		$layout = str_replace('$this->map','$map',$generate_layout->generate_text($generate_layout->synchronize())); 
		//if(!$row['is_cached'])
		{
			$map = array('CONTENT_NAME'=>''.$module_data['NAME'].'');
			$ok=true;
			eval($block['MODULE']['CODE']);
			if($ok){
				Module::convert_language($layout);
			}
		}

		if(User::can_admin(MODULE_MODULEADMIN) and !Module::$blocks[$block_id]['OBJECT']->data['MODULE']['USE_DBLCLICK']and !defined('XPATH'))
		{
			echo '<script type="text/javascript">echo("<"+"/div>");</script>';
		}
		Module::invoke_event('ONENDDRAW',$this,System::$false);
		Module::$current=&$last;
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
			if($block['REGION'] == $region and $block['CONTAINER_ID'] == $container_id)
			{
				if(!$first)
				{
					$st .= ',';
				}
				$first = false;
				$st .= '{
					'.($block['NAME']?'title: \''.($block['NAME']?$block['NAME']:$block['MODULE']['NAME']).'\',
					tools: tools,
					':'header:false,border:false,bodyBorder:false,frame:false,footer:false,').'
					
					'.($baseCls?'baseCls:\'simple-panel\',':'').'
					contentEl :\'module_'.$block['ID'].'\'
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
				if($plugin['ACTION'] == $event and (($module === System::$false) or ($module->data['MODULE_ID'] == $plugin['ACTION_MODULE_ID'])))
				{
					if(!class_exists($plugin['NAME']))
					{
						require_once $plugin['PATH'].'class.php';
						eval($plugin['NAME'].'::init($module,$params);');
					}
					eval($plugin['NAME'].'::run($module,$params);');
				}
			}
			if($event == 'ONUNLOAD' and $module === System::$false)
			{
				if(class_exists($plugin['NAME']))
				{
					eval($plugin['NAME'].'::finish($module,$params);');
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
COPY RIGHT BY NYN PORTAL - TCV
WRITTEN BY vuonggialong
******************************/

class Portal
{
	static $current = false;
	static $extra_header = '';
	static $page_gen_time = 0;
	static $page = false;
	static $meta_keywords = 'esn, mang xa hoi doanh nghiep,tcv,tiachopviet,doanh nghiep,san pham';
	static $meta_description = 'ESN.VN - H&#7879; th&#7889;ng m&#7841;ng x&atilde; h&#7897;i doanh nghi&#7879;p &ndash; s&#7843;n ph&#7849;m  c&#7911;a C&ocirc;ng ty c&#7893; ph&#7847;n gi&#7843;i ph&aacute;p c&ocirc;ng ngh&#7879; cao TCV . ESN mang &#273;&#7871;n cho b&#7841;n c&#417; h&#7897;i l&#7899;n nh&#7845;t &#273;&#7875; k&#7871;t n&#7889;i v&#7899;i c&#7897;ng &#273;&#7891;ng c&aacute;c doanh nghi&#7879;p';
	static $document_title = '';
	function Portal()
	{
	}
	function register_module($row_or_id, &$module)
	{
		if(is_numeric($row_or_id))
		{
			$id=$row_or_id;
		}
		else
		if(isset($row_or_id['id']))
		{
			$id = $row_or_id['id'];
		}
		else
		{
			System::halt();
		}
		if(is_numeric($row_or_id))
		{
			DB::query('
				SELECT
					ID, NAME, PACKAGE_ID
				FROM
					MODULE
				where
					ID = '.$row_or_id);
			$row = DB::fetch();
			if(!$row)
			{
				System::halt();
			}
		}
		else
		{
			$row = $row_or_id;
		}
		require_once 'packages/core/includes/portal/package.php';
		$class_fn = get_package_path($row['PACKAGE_ID']).'module_'.$row['NAME'].'/class.php';

		require_once $class_fn;
		$module = new $row['NAME']($row);
		$module->package = &$GLOBALS['packages'][$row['PACKAGE_ID']];
	}
	//Chay portal
	function run()
	{
		if(isset($_REQUEST['portal']) 
			and ((!Session::is_set('portal') 
				or !Session::is_set('portal','id') 
				or ('#'.$_REQUEST['portal'])!=Session::get('portal','id')))
					and $portal=DB::select_id('ACCOUNT','\'#'.addslashes($_REQUEST['portal']).'\''))
		{
			Session::set('portal', $portal);
		}
		else
		if(!Session::is_set('portal') 
			or !Session::get('portal') 
			or (!isset($_REQUEST['portal']) and Session::get('portal','id') != '#default'))
		{
			URL::redirect_url('?portal=default');
		}
		if(Session::is_set('portal') and Session::get('portal'))
		{
			if(!Session::get('portal','CACHE_SETTING'))
			{
				require_once 'packages/core/includes/system/make_account_setting_cache.php';
				make_account_setting_cache(Session::get('portal','ID'));
				Session::set('portal', DB::select('ACCOUNT','ID=\''.Session::get('portal','ID').'\''));				
			}
			eval('Portal::$current->settings='.Session::get('portal','CACHE_SETTING'));
			define('PORTAL_PREFIX',str_replace('#','p_',Session::get('portal','ID')).'_');
			define('PORTAL_ID',Session::get('portal','ID'));
			if($services = Portal::get_setting('services'))
			{
				Portal::$current->services = array_flip(explode(',',$services));
			}
			else
			{
				Portal::$current->services = array();
			}
			if(isset($_REQUEST['page']))
			{
				$page_name = strtolower($_REQUEST['page']);
			}
			else
			{
				header('Location:home.html');
				exit();
			}
			$pages = DB::select_all('PAGE','NAME=\''.addslashes($page_name).'\'','PARAMS DESC');
			if(sizeof($pages)==1)
			{
				Portal::run_page(array_pop($pages),$page_name, false);
			}
			else
			if(sizeof($pages)==0)
			{
				header('Location:home.html');
				exit();
			}
			else
			{
				foreach($pages as $page)
				{
					if($page['PARAMS']=='')
					{
						Portal::run_page($page,$page_name, false);
						break;
					}
					else
					{
						$params = explode('&',$page['PARAMS']);
						$ok = true;
						foreach($params as $param)
						{
							if($param = explode('=',$param))
							{
								if(sizeof($param)==1)
								{
									if(!URL::check($param[0]))
									{
										$ok = false;
										break;
									}
								}
								else
								if($param[0]=='group')
								{
									if(!isset(User::$current->groups[$param[1]]))
									{
										$ok = false;
										break;
									}
								}
								else
								{
									if($param[0]=='portal')
									{
										if('#'.$param[1] != Session::get('portal','ID'))
										{
											$ok = false;

											break;
										}
									}
									else
									if(URL::get($param[0])!=$param[1])
									{
										$ok = false;
										break;
									}
									else
									{
										if($param[0]=='portal')
										{
											$portal = $param[1];
										}
									}
								}
							}
						}
						if($ok)
						{
							Portal::run_page($page,$page_name, $page['PARAMS']);
							break;
						}
					}
				}
			}
		}
		Session::end();
		DB::close();
	}
	function run_page($row, $page_name, $params=false)
	{
		$postfix = $params?'.'.$params:'';
		$page_file = ROOT_PATH.'cache/page_layouts/'.$page_name.$postfix.'.cache.php';
		if(file_exists($page_file) and false)
		{
			require_once $page_file;								
		}
		else
		{
			require_once 'packages/core/includes/portal/generate_page.php';
			$generate_page = new GeneratePage($row);
			$generate_page->generate();
			$page_name=$row['NAME'];
		}
		Portal::update_portal_hit_count($page_name,PORTAL_ID);		
	}
	static function update_portal_hit_count($page_name,$portal_id)
	{
		if(Session::is_set('portal_visited'))
		{
			$items=array_flip(explode(',',Session::get('portal_visited')));
		}
		else
		{
			$items=array();
		}			
		if(!isset($items[$portal_id]) and $item=DB::fetch('SELECT ID,HIT_COUNT  FROM PARTY WHERE TYPE=\'PORTAL\' AND PORTAL_ID=\''.$portal_id.'\''))
		{
			DB::update_id('PARTY',array('HIT_COUNT'=>$item['HIT_COUNT']+1),intval($item['ID']));
			$items[$portal_id]=$portal_id;			
			Session::set('portal_visited', implode(',',array_keys($items)));
		}		
	}
	static function template($package_name)
	{
		return 'packages/'.$package_name.'/skins/'.Portal::get_setting($package_name.'_template','default');
	}
	static function template_tcv($template_name=false)
	{
		if($template_name)
		{
			return 'packages/tcv/skins/'.$template_name.'/';
		}	
		return 'packages/tcv/skins/'.substr(PORTAL_ID,1).'/';
	}
	static function service($service_name)
	{
		$services = Portal::get_setting('registered_services');
		return isset($services[$service_name]);
	}
	static function language($name=false)
	{
		if($name)
		{
			if(isset($GLOBALS['all_words']['[[.'.$name.'.]]']))
			{
				return $GLOBALS['all_words']['[[.'.$name.'.]]'];
			}
			else
			{
				$languages = DB::select_all('LANGUAGE');
				$row = array();
				foreach($languages as $language)
				{
					$row['VALUE_'.$language['ID']] = ucfirst(str_replace('_',' ',$name));
				}
				DB::insert('WORD',$row + array(
					'ID'=>$name,
					'PACKAGE_ID'=>Module::$current->data['MODULE']['PACKAGE_ID']
				),1);
				Portal::make_word_cache();
				return $name;
			}
		}
		if(Session::is_set('LANGUAGE_ID'))
		{
			return Session::get('LANGUAGE_ID');
		}
		else
		/*if(User::is_login())
		{
			return User::$current->data['language_id'];
		}
		else*/
		{
			Session::set('LANGUAGE_ID', Portal::get_setting('LANGUAGE_ID',1));
			return Session::get('LANGUAGE_ID');
		}
	}
	static function get_setting($name, $default=false, $user_id = false)
	{
		if(!$user_id)
		{
			if(isset(User::$current->settings[$name]))
			{
				if(User::$current->settings[$name] == '@VERY_LARGE_INFORMATION')
				{
					if($setting = DB::select('ACCOUNT_SETTING','SETTING_ID=\''.DB::escape($name).'\' and ACCOUNT_ID=\''.User::id().'\''))
					{
						return $setting['VALUE'];
					}
				}
				else
				{
					return User::$current->settings[$name];
				}
			}
			else
			if(isset(Portal::$current->settings[$name]))
			{
				if(Portal::$current->settings[$name] == '@VERY_LARGE_INFORMATION')
				{
					if($setting = DB::select('ACCOUNT_SETTING','SETTING_ID=\''.DB::escape($name).'\' and ACCOUNT_ID=\''.PORTAL_ID.'\''))
					{
						return $setting['VALUE'];
					}
				}
				else
				{
					return Portal::$current->settings[$name];
				}
			}
			
		}
		else
		{
			
			/*if(!isset($GLOBALS['user_settings']))
			{
				$GLOBALS['user_settings'] = array();
			}
			if(!isset($GLOBALS['user_settings'][$user_id]))
			{
				if($cache_setting = DB::fetch('select cache_setting from account where id="'.mysql_escape_string($user_id).'"','cache_setting'))
				{
					eval('$setting = '.$cache_setting.';');
				}
				else
				{
					$setting = array();
				}
				$GLOBALS['user_settings'][$user_id] = $setting;
				
			}
			if(isset($GLOBALS['user_settings'][$user_id][$name]))
			{
				return $GLOBALS['user_settings'][$user_id][$name];
			}
			*/
			if($setting = DB::select('ACCOUNT_SETTING','SETTING_ID=\''.DB::escape($name).'\' and ACCOUNT_ID=\''.DB::escape($user_id).'\''))
			{
				return $setting['VALUE'];
			}
			return $default;
		}
		if(isset($GLOBALS['default_settings'][$name]))
		{
			if($GLOBALS['default_settings'][$name] == '@VERY_LARGE_INFORMATION')
			{
				if($setting = DB::select('ACCOUNT_SETTING','SETTING_ID=\''.DB::escape($name).'\' and ACCOUNT_ID=\''.PORTAL_ID.'\''))
				{
					return $setting['VALUE'];
				}
			}
			else
			{
				return $GLOBALS['default_settings'][$name];
			}
		}
		return $default;
	}
	function use_service($name)
	{
		return isset(Portal::$current->services[$name]);
	}
	function set_setting($name, $value,$user_id=false)
	{
		if($setting = DB::select('SETTING','ID=\''.$name.'\''))
		{
			if($user_id==false)
			{
				if($setting['ACCOUNT_TYPE']=='USER')
				{
					$account_id = Session::get('USER_ID');
				}
				else
				{
					$account_id = Session::get('portal','ID');
				}
			}
			else
			{
				$account_id = $user_id;
			}
			if(DB::select('ACCOUNT_SETTING','ACCOUNT_ID=\''.addslashes($account_id).'\' and SETTING_ID=\''.addslashes($name).'\''))
			{
				DB::update('ACCOUNT_SETTING',
					array(
						'VALUE'=>$value
					),
					'ACCOUNT_ID=\''.addslashes($account_id).'\' and SETTING_ID=\''.addslashes($name).'\''
				);
			}
			else
			{
				DB::insert('ACCOUNT_SETTING',
					array(
						'ACCOUNT_ID'=>$account_id,
						'SETTING_ID'=>$name,
						'VALUE'=>$value
					)
				);
			}
			DB::update('ACCOUNT',array('CACHE_SETTING'=>''),'ID=\''.$account_id.'\'');
			if($setting['ACCOUNT_TYPE']=='PORTAL' and $account_id==PORTAL_ID)
			{
				if(isset($_REQUEST['portal']) and $portal=DB::select_id('ACCOUNT',addslashes($_REQUEST['portal'])))
				{
					Session::set('portal', $portal);
				}
				else
				{
					Session::set('portal', DB::select_id('ACCOUNT','#default'));
				}
			}
		}
	}
	function make_word_cache()
	{
		$languages = DB::select_all('LANGUAGE');
		foreach($languages as $language_id=>$row)
		{
			$all_words = DB::fetch_all('
					SELECT 
						ID, VALUE_'.$language_id.' as VALUE 
					FROM
						WORD 
				');
			$language_convert = array();
			foreach($all_words as $language)
			{
				$language_convert = $language_convert + 
					array('[[.'.$language['ID'].'.]]'=>$language['VALUE']);
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