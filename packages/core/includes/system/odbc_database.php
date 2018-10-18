<?php
/******************************
COPY RIGHT BY NYN PORTAL - TCV
******************************/
class ODBC_DB 
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
	function ODBC_DB($sqlserver, $sqluser, $sqlpassword)
	{
		ODBC_DB::$db_connect_id = @odbc_connect($sqlserver, $sqluser, $sqlpassword);
		if (isset(ODBC_DB::$db_connect_id) and ODBC_DB::$db_connect_id)
		{
		}
		if(!ODBC_DB::$db_connect_id)
		{
			die('Error: connect SQL Server fail ');//.odbc_errormsg(ODBC_DB::$db_connect_id).' (Code:'.odbc_error(ODBC_DB::$db_connect_id).')';
			return false;
		}
		return ODBC_DB::$db_connect_id;
	}
	static function register_cache($table, $id_name='id', $order=' order by id asc')
	{
		ODBC_DB::$db_cache_tables[$table]=array('id_name'=>$id_name, 'order'=>$order);
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
		return ODBC_DB::fetch('select count(*) as total from '.$table.''.($condition?' where '.$condition:''),'total');
	}
	//Lay ra mot ban ghi trong bang $table thoa man dieu kien $condition
	//Neu bang duoc cache thi lay tu cache, neu khong query tu CSDL
	static function select($table, $condition)
	{
		if($result = ODBC_DB::select_id($table, $condition)){
			return $result;
		}else{
			return ODBC_DB::exists('select top 1 * from '.$table.' where '.$condition.'');
		}
	}
	static function select_id($table, $condition)
	{
		if($condition and !preg_match('/[^a-zA-Z0-9_#-\.]/',$condition))
		{
			if(isset(ODBC_DB::$db_cache_tables[$table]))
			{
				$id=$condition;
				$cache_var = 'cache_'.$table;
				global $$cache_var;
				$cached = isset($$cache_var);
				if(!$cached)
				{
					ODBC_DB::refresh_cache($table);
				}
				$data = &$$cache_var;
				if(isset($data[$id]))
				{
					return $data[$id];
				}
			}
			else 
			{
				return ODBC_DB::exists_id($table,$condition);
			}
		}
		else
		{
			return false;
		}
	}
	//Lay ra tat ca cac ban ghi trong bang $table thoa man dieu kien $condition sap xep theo thu tu $order
	//Neu bang duoc cache thi lay tu cache, neu khong query tu CSDL
	static function select_all($table, $condition=false, $order = false)
	{
		if(isset(ODBC_DB::$db_select_all_db[$table]) and !$order and !$condition)
		{
			return ODBC_DB::$db_select_all_db[$table];
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
			ODBC_DB::query('select * from '.$table.' '.$condition.' '.$order);
			$rows = ODBC_DB::fetch_all();
			if(sizeof($rows)<10)
			{
				ODBC_DB::$db_select_all_db[$table] = $rows;
			}
			return $rows;
		}
	}
	// function close
	// Close SQL connection
	// should be called at very end of all scripts
	// ------------------------------------------------------------------------------------------

	static function close()
	{
		if (isset(ODBC_DB::$db_connect_id) and ODBC_DB::$db_connect_id)
		{
			odbc_commit(ODBC_DB::$db_connect_id);
			if (isset(ODBC_DB::$db_result) and ODBC_DB::$db_result)
			{
				odbc_free_result(ODBC_DB::$db_result);
			}
			$result = odbc_close(ODBC_DB::$db_connect_id);

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

	static function query($query)
	{
		//echo ODBC_DB::$db_num_queries.'.'.$query.'<br>';
		// Clear old query result
		ODBC_DB::$db_result=false;
		if (!empty($query))
		{
			if(!(ODBC_DB::$db_result = @odbc_exec(ODBC_DB::$db_connect_id,$query)))
			{
				require_once 'packages/core/includes/utils/error.php';
				if(defined('DEBUG'))
				{
					echo '<p><font face="Courier New,Courier" size=3><b>'.odbc_errormsg(ODBC_DB::$db_connect_id).'</b></font><br>';
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
							'title'=>@odbc_errormsg(ODBC_DB::$db_connect_id)
						)
					);
				}
			}
			ODBC_DB::$db_num_queries++;
			if(Url::get('debug')==1)
			{
				ODBC_DB::$db_query_info[ODBC_DB::$db_num_queries]['stt'] =  ODBC_DB::$db_num_queries;
				ODBC_DB::$db_query_info[ODBC_DB::$db_num_queries]['query'] =  $query;
				if(class_exists('Module') and isset(Module::$current->data['module']))
				{
					ODBC_DB::$db_query_info[ODBC_DB::$db_num_queries]['module_id'] = Module::$current->data['module']['name'];
				}
				ODBC_DB::$db_query_info[ODBC_DB::$db_num_queries]['timer']=(class_exists('Portal'))?Portal::$page_gen_time->get_timer():0;
			}
		}	
		return ODBC_DB::$db_result;
	}
	static function db_queries()
	{
		return ODBC_DB::$db_query_info;
	}
	//Tra ve ban ghi query tu CSDL bang lenh SQL $query neu co
	//Neu khong co tra ve false
	//$query: cau lenh SQL se thuc hien
	static function exists($query)
	{
		ODBC_DB::query($query);
		if($result = odbc_fetch_array(ODBC_DB::$db_result))
		{
			return $result;
		}
		return false;
	}
	//Tra ve ban ghi trong bang $table co id la $id
	//Neu khong co tra ve false
	//$table: bang can truy van
	//$id: ma so ban ghi can lay
	static function exists_id($table,$id)
	{
		if($id)
		{
			if(!isset(ODBC_DB::$db_exists_db[$table][$id]))
			{
				ODBC_DB::$db_exists_db[$table][$id]=ODBC_DB::exists('select top 1 * from '.$table.' where id="'.$id.'"');
			}
			return ODBC_DB::$db_exists_db[$table][$id];
		}
	}
	static function insert($table, $values, $replace=false)
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
				if($value=='NULL')
				{
					$query.='0';
				}
				elseif($value=='')
				{
					$query.='0';
				}
				else
				{
					if(is_numeric($value))
					{
						$query.=''.ODBC_DB::escape($value).'';
					}else{
						$query.='\''.ODBC_DB::escape($value).'\'';
					}
				}
				$i++;
			}
			$query.=')';
			if(ODBC_DB::query($query))
			{
				$id = ODBC_DB::insert_id($table);
				if(isset(ODBC_DB::$db_cache_tables[$table]))
				{
					//ODBC_DB::refresh_cache($table);
				}
				return $id;
			}
		}
	}
	static function delete($table, $condition)
	{
		$query='delete from '.$table.' where '.$condition;
		if(ODBC_DB::query($query))
		{
			if(isset(ODBC_DB::$db_cache_tables[$table]))
			{
				//ODBC_DB::refresh_cache($table);
			}		
			return true;
		}
		return false;
	}
	static function delete_id($table, $id)
	{
		return ODBC_DB::delete($table, 'id="'.addslashes($id).'"');
	}
	static function update($table, $values, $condition)
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
						$query.=''.$key.'=0';
					}
					elseif($value=='')
					{
						$query.=''.$key.'=0';
					}
					else
					{
						$query.=''.$key.'=\''.ODBC_DB::escape($value).'\'';
					}
					$i++;
				}
			}
			$query.=' where '.$condition;
			if(ODBC_DB::query($query))
			{
				if(isset(ODBC_DB::$db_cache_tables[$table]))
				{
					ODBC_DB::refresh_cache($table);
				}
				return true;
			}
		}
	}
	function refresh_cache()
	{
	
	}
	static function update_id($table, $values, $id)
	{
		return ODBC_DB::update($table, $values, 'id='.$id.'');
	}
	static function num_rows($query_id = false)
	{
		if(!$query_id)
		{
			$query_id = ODBC_DB::$db_result;
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
	static function affected_rows()
	{

		if (isset(ODBC_DB::$db_connect_id) and ODBC_DB::$db_connect_id)
		{
			$result = @odbc_num_rows(ODBC_DB::$db_connect_id);
			return $result;
		}
		else
		{
			return false;
		}
	}
	static function fetch($sql = false, $field = false, $default = false)
	{
		if($sql)
		{
			ODBC_DB::query($sql);
		}
		$query_id = ODBC_DB::$db_result;
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
	static function fetch_all($sql=false)
	{
		if($sql)
		{
			ODBC_DB::query($sql);
		}
		$query_id = ODBC_DB::$db_result;
		
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
	static function fetch_all_array($sql=false)
	{
		if($sql)
		{
			ODBC_DB::query($sql);
		}
		$query_id = ODBC_DB::$db_result;
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
	static function insert_id($table)
	{
		if (ODBC_DB::$db_connect_id)
		{
			if($result =  @odbc_exec(ODBC_DB::$db_connect_id,'select @@IDENTITY as id'))
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
	static function free_result($query_id = 0)
	{
		if (!$query_id)
		{
			$query_id = ODBC_DB::$db_result;
		}
		if ($query_id)
		{
			odbc_free_result($query_id);
			return true;
		}else{
			return false;
		}
	}
	static function error()
	{
		$result['message'] = odbc_errormsg(ODBC_DB::$db_connect_id);
		$result['code'] = odbc_error(ODBC_DB::$db_connect_id);
		return $result;
	}
	static function escape($sql)
	{
		return stripslashes($sql);
	}
	static function num_queries()
	{
		return ODBC_DB::$db_num_queries;
	}
	// tra ve structure_id cua $id
	static function structure_id($table,$id)
	{
		$row=ODBC_DB::select($table,'id='.$id);
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
}
$ODBC_db = new ODBC_DB('hotel','sa','123456');
?>