<?php
/****************************
	Writtend by KHOAND
	Connect to  ACCESS	
*****************************/
class ADB 
{
	static $db_connect_id=false;				// connection id of this database
	static $db_result=false;				// current result of an query
	static $db_cache_tables = array();
	static $db_exists_db = array();
	static $db_select_all_db = array();
	static $db_num_queries = 0;
	static $db_query_info = array();
	// Debug
	var $num_queries = 0;		// number of queries was done
	public  function ADB($sqlserver, $sqluser, $sqlpassword)
	{
		ADB::$db_connect_id = odbc_connect($sqlserver, $sqluser, $sqlpassword);
		if (isset(ADB::$db_connect_id) and ADB::$db_connect_id)
		{
		}
		if(!ADB::$db_connect_id)
		{
			echo 'Error: connect SQL Server fail ';//.odbc_errormsg(ADB::$db_connect_id).' (Code:'.odbc_error(ADB::$db_connect_id).')';
			return false;
		}
		return ADB::$db_connect_id;
	}
	public static function register_cache($table, $id_name='id', $order=' order by id asc')
	{
		ADB::$db_cache_tables[$table]=array('id_name'=>$id_name, 'order'=>$order);
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
	public static function count($table, $condition=false)
	{
		return ADB::fetch('select count(*) as total from '.$table.''.($condition?' where '.$condition:''),'total');
	}
	//Lay ra mot ban ghi trong bang $table thoa man dieu kien $condition
	//Neu bang duoc cache thi lay tu cache, neu khong query tu CSDL
	public static function select($table, $condition)
	{
		if($result = ADB::select_id($table, $condition)){
			return $result;
		}else{
			return ADB::exists('select top 1 * from '.$table.' where '.$condition.'');
		}
	}
	public static function select_id($table, $condition)
	{
		if($condition and !preg_match('/[^a-zA-Z0-9_#-\.]/',$condition))
		{
			if(isset(ADB::$db_cache_tables[$table]))
			{
				$id=$condition;
				$cache_var = 'cache_'.$table;
				global $$cache_var;
				$cached = isset($$cache_var);
				if(!$cached)
				{
					ADB::refresh_cache($table);
				}
				$data = &$$cache_var;
				if(isset($data[$id]))
				{
					return $data[$id];
				}
			}
			else 
			{
				return ADB::exists_id($table,$condition);
			}
		}
		else
		{
			return false;
		}
	}
	//Lay ra tat ca cac ban ghi trong bang $table thoa man dieu kien $condition sap xep theo thu tu $order
	//Neu bang duoc cache thi lay tu cache, neu khong query tu CSDL
	public static function select_all($table, $condition=false, $order = false)
	{
		if(isset(ADB::$db_select_all_db[$table]) and !$order and !$condition)
		{
			return ADB::$db_select_all_db[$table];
		}elseif(isset($GLOBALS['cache_'.$table]) and !$order and !$condition)
		{
			return $GLOBALS['cache_'.$table];
		}else{
			if($order)
			{
				$order = ' order by '.$order;
			}
			if($condition)
			{
				$condition = ' where '.$condition;
			}
			ADB::query('select * from '.$table.' '.$condition.' '.$order);
			$rows = ADB::fetch_all();
			if(sizeof($rows)<10)
			{
				ADB::$db_select_all_db[$table] = $rows;
			}
			return $rows;
		}
	}
	// function close
	// Close SQL connection
	// should be called at very end of all scripts
	// ------------------------------------------------------------------------------------------

	public static function close()
	{
		if (isset(ADB::$db_connect_id) and ADB::$db_connect_id)
		{
			if (isset(ADB::$db_result) and ADB::$db_result)
			{
				@ODBC_free_result(ADB::$db_result);
			}

			$result = ODBC_close(ADB::$db_connect_id);

			return $result;
		}
		else
		{
			return false;
		}

	}
	// function query
	// Run an sql command
	// Parameters:
	//		$query:		the command to run
	// ------------------------------------------------------------------------------------------

	public static function query($query)
	{
		//echo ADB::$db_num_queries.'.'.$query.'<br>';
		// Clear old query result
		ADB::$db_result=false;
		if (!empty($query))
		{
			if(!(ADB::$db_result = @odbc_exec(ADB::$db_connect_id,$query)))
			{
				require_once 'packages/core/includes/utils/error.php';
				if(defined('DEBUG'))
				{
					echo '<p><font face="Courier New,Courier" size=3><b>'.odbc_errormsg(ADB::$db_connect_id).'</b></font><br>';
					echo DBG_GetBacktrace().'</b>';
				}
				else
				{
					DB::insert('log',
						array(
							'module_id'=>1387,
							'user_id'=>Session::get('user_id'),
							'time'=>time(),
							'type'=>'mssql',
							'description'=>DBG_GetBacktrace(),
							'title'=>@odbc_errormsg(ADB::$db_connect_id)
						)
					);
				}
			}
			ADB::$db_num_queries++;
			if(Url::get('debug')==1)
			{
				ADB::$db_query_info[ADB::$db_num_queries]['stt'] =  ADB::$db_num_queries;
				ADB::$db_query_info[ADB::$db_num_queries]['query'] =  $query;
				if(class_exists('Module') and isset(Module::$current->data['module']))
				{
					ADB::$db_query_info[ADB::$db_num_queries]['module_id'] = Module::$current->data['module']['name'];
				}
				ADB::$db_query_info[ADB::$db_num_queries]['timer']=(class_exists('Portal'))?Portal::$page_gen_time->get_timer():0;
			}
		}	
		return ADB::$db_result;
	}
	public static function db_queries()
	{
		return ADB::$db_query_info;
	}
	//Tra ve ban ghi query tu CSDL bang lenh SQL $query neu co
	//Neu khong co tra ve false
	//$query: cau lenh SQL se thuc hien
	public static function exists($query)
	{
		ADB::query($query);
		if($result = odbc_fetch_array(ADB::$db_result))
		{
			return $result;
		}
		return false;
	}
	//Tra ve ban ghi trong bang $table co id la $id
	//Neu khong co tra ve false
	//$table: bang can truy van
	//$id: ma so ban ghi can lay
	public static function exists_id($table,$id)
	{
		if($id)
		{
			if(!isset(ADB::$db_exists_db[$table][$id]))
			{
				ADB::$db_exists_db[$table][$id]=ADB::exists('select top 1 * from '.$table.' where id="'.$id.'"');
			}
			return ADB::$db_exists_db[$table][$id];
		}
	}
	public static function insert($table, $values, $replace=false)
	{
		if($replace)
		{
			$query='replace';
		}
		else
		{
			$query='insert into';
		}
		$query.=' '.$table.'(';
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
					$query.=''.$key.'';
					$i++;
				}
			}
			$query.=') VALUES (';
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
					if(is_numeric($value))
					{
						$query.=''.ADB::escape($value).'';
					}else{
						$query.='\''.ADB::escape($value).'\'';
					}
				}
				$i++;
			}
			$query.=')';
			if(ADB::query($query))
			{
				$id = ADB::insert_id($table);
				if(isset(ADB::$db_cache_tables[$table]))
				{
					//ADB::refresh_cache($table);
				}
				return $id;
			}
		}
	}
	public static function delete($table, $condition)
	{
		$query='delete from '.$table.' where '.$condition;
		if(ADB::query($query))
		{		
			if(isset(ADB::$db_cache_tables[$table]))
			{
				//ADB::refresh_cache($table);
			}		
			return true;
		}
		return false;
	}
	public static function delete_id($table, $id)
	{
		return ADB::delete($table, 'id="'.addslashes($id).'"');
	}
	public static function update($table, $values, $condition)
	{
		$query='update '.$table.' set ';
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
						$query.=''.$key.'=NULL';
					}
					else
					{
						$query.=''.$key.'=\''.ADB::escape($value).'\'';
					}
					$i++;
				}
			}
			$query.=' where '.$condition;
			if(ADB::query($query))
			{
				if(isset(ADB::$db_cache_tables[$table]))
				{
					ADB::refresh_cache($table);
				}
				return true;
			}
		}
	}
	public function refresh_cache()
	{
	
	}
	public static function update_id($table, $values, $id)
	{
		return ADB::update($table, $values, 'id='.$id.'');
	}
	public static function num_rows($query_id = false)
	{
		if(!$query_id)
		{
			$query_id = ADB::$db_result;
		}
		if ($query_id)
		{
			$result = @odbc_result_all($query_id);
			return $result;
		}
		else
		{
			return false;
		}
	}
	public static function affected_rows()
	{

		if (isset(ADB::$db_connect_id) and ADB::$db_connect_id)
		{
			$result = @odbc_num_rows(ADB::$db_connect_id);
			return $result;
		}
		else
		{
			return false;
		}
	}
	public static function fetch($sql = false, $field = false, $default = false)
	{
		if($sql)
		{
			ADB::query($sql);
		}
		$query_id = ADB::$db_result;
		if($query_id)
		{
			if($result = @odbc_fetch_array($query_id))
			{
				if($field)
				{
					return $result[$field];
				}
				return $result;
			}
			return $default;
		}else{
			return false;
		}
	}
	public static function fetch_all($sql=false)
	{
		if($sql)
		{
			ADB::query($sql);
		}
		$query_id = ADB::$db_result;
		
		if ($query_id)
		{
			$result=array();
			while($row = @odbc_fetch_array($query_id))
			{
				$result[$row['id']] = $row;
			}
			return $result;
		}else{
			return false;
		}
	}
	public static function fetch_all_array($sql=false)
	{
		if($sql)
		{
			ADB::query($sql);
		}
		$query_id = ADB::$db_result;
		if ($query_id)
		{
			$result=array();
			while($row = @odbc_fetch_row($query_id))
			{
				$result[] = $row;
			}
			return $result;
		}else{
			return false;
		}
	}
	public static function insert_id($table)
	{
		if (ADB::$db_connect_id)
		{
			if($result =  @odbc_exec(ADB::$db_connect_id,'select @@IDENTITY as id'))
			{
				return odbc_result($result,'id');
			}
			return false;
			/*if($result = @odbc_fetch_array($result))
			{
				return $result['id'];
			}*/
		}
		return false;
	}
	public static function free_result($query_id = 0)
	{
		if (!$query_id)
		{
			$query_id = ADB::$db_result;
		}
		if ($query_id)
		{
			odbc_free_result($query_id);
			return true;
		}else{
			return false;
		}
	}
	public static function error()
	{
		$result['message'] = odbc_errormsg(ADB::$db_connect_id);
		$result['code'] = odbc_error(ADB::$db_connect_id);
		return $result;
	}
	public static function escape($sql)
	{
		return stripslashes($sql);
	}
	public static function num_queries()
	{
		return ADB::$db_num_queries;
	}
	// tra ve structure_id cua $id
	public static function structure_id($table,$id)
	{
		$row=ADB::select($table,'id='.$id);
		return $row['structure_id'];
	}	
	public static function search_cond($table, $field)
	{
		$cond_st = '';
		if(URL::get('search_by_'.$field))
		{
			$conds = explode('&',URL::get('search_by_'.$field));
			foreach($conds as $cond)
			{
				if(preg_match('/[><=]/',URL::get('search_by_'.$field)))
				{	
					$cond_st .= ' and '.$table.'.'.$field.' '.$cond;
				}
				else
				{
					$cond_st .= ' and '.$table.'.'.$field.' LIKE "%'.$cond.'%"';
				}
			}
		}
		return $cond_st;
	}
	public static function get_record_title($item)
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
	public function xxx(){
		echo 1;
	}
}
//$adb = new ADB("Driver={Microsoft Access Driver (*.mdb)};Dbq=C:\att2000.mdb",'','');
//$fdb = new ADB("Driver={Microsoft Access Driver (*.DBF)};Dbq=C:\Users\Minh Duc\Desktop\PA18\32LOSU_NN_201008292037.DBF",'','');
?>