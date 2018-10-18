<?php
/******************************
COPY RIGHT BY NYN PORTAL - TCV
******************************/
class MSSQL_DB 
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
	function MSSQL_DB($sqlserver, $sqluser, $sqlpassword, $dbname)
	{
		MSSQL_DB::$db_connect_id = mssql_connect($sqlserver, $sqluser, $sqlpassword);
		if (isset(MSSQL_DB::$db_connect_id) and MSSQL_DB::$db_connect_id)
		{
			if (!$dbselect = mssql_select_db($dbname))
			{
				@mssql_close(MSSQL_DB::$db_connect_id);
				MSSQL_DB::$db_connect_id = $dbselect;
			}

		}

		if(!MSSQL_DB::$db_connect_id)
		{
			//echo 'Error: Could not connect to the mssql_database';
			return false;
		}

		return MSSQL_DB::$db_connect_id;
	}
	static function register_cache($table, $id_name='id', $order=' order by id asc')
	{
		MSSQL_DB::$db_cache_tables[$table]=array('id_name'=>$id_name, 'order'=>$order);
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
		return MSSQL_DB::fetch('select count(*) as total from '.$table.''.($condition?' where '.$condition:''),'total');
	}
	//Lay ra mot ban ghi trong bang $table thoa man dieu kien $condition
	//Neu bang duoc cache thi lay tu cache, neu khong query tu CSDL
	static function select($table, $condition)
	{
		if($result = MSSQL_DB::select_id($table, $condition))
		{
			return $result;
		}
		else
		{
			return MSSQL_DB::exists('select * from '.$table.' where '.$condition.' limit 0,1');
		}
	}
	static function select_id($table, $condition)
	{
		if($condition and !preg_match('/[^a-zA-Z0-9_#-\.]/',$condition))
		{
			if(isset(MSSQL_DB::$db_cache_tables[$table]))
			{
				$id=$condition;
				$cache_var = 'cache_'.$table;
				global $$cache_var;
				$cached = isset($$cache_var);
				if(!$cached)
				{
					MSSQL_DB::refresh_cache($table);
				}
				$data = &$$cache_var;
				if(isset($data[$id]))
				{
					return $data[$id];
				}
			}
			else 
			{
				return MSSQL_DB::exists_id($table,$condition);
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
		if(isset(MSSQL_DB::$db_select_all_db[$table]) and !$order and !$condition)
		{
			return MSSQL_DB::$db_select_all_db[$table];
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
				$order = ' order by '.$order;
			}
			if($condition)
			{
				$condition = ' where '.$condition;
			}
			MSSQL_DB::query('select * from '.$table.' '.$condition.' '.$order);
			$rows = MSSQL_DB::fetch_all();
			if(sizeof($rows)<10)
			{
				MSSQL_DB::$db_select_all_db[$table] = $rows;
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
		if (isset(MSSQL_DB::$db_connect_id) and MSSQL_DB::$db_connect_id)
		{
			if (isset(MSSQL_DB::$db_result) and MSSQL_DB::$db_result)
			{
				@mssql_free_result(MSSQL_DB::$db_result);
			}

			$result = mssql_close(MSSQL_DB::$db_connect_id);

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
		//echo MSSQL_DB::$db_num_queries.'.'.$query.'<br>';
		// Clear old query result
		MSSQL_DB::$db_result=false;
		if (!empty($query))
		{
			if(!(MSSQL_DB::$db_result = mssql_query($query, MSSQL_DB::$db_connect_id)))
			{
				require_once 'packages/core/includes/utils/error.php';
				if(defined('DEBUG'))
				{
					echo '<p><font face="Courier New,Courier" size=3><b>'.mssql_get_last_message(MSSQL_DB::$db_connect_id).'</b></font><br>';
					echo DBG_GetBacktrace().'</b>';
				}
				else
				{
					MSSQL_DB::insert('log',
						array(
							'module_id'=>1387,
							'user_id'=>Session::get('user_id'),
							'time'=>time(),
							'type'=>'mssql',
							'description'=>DBG_GetBacktrace(),
							'title'=>mssql_get_last_message(MSSQL_DB::$db_connect_id)
						)
					);
				}
			}
			MSSQL_DB::$db_num_queries++;
			if(Url::get('debug')==1)
			{
				MSSQL_DB::$db_query_info[MSSQL_DB::$db_num_queries]['stt'] =  MSSQL_DB::$db_num_queries;
				MSSQL_DB::$db_query_info[MSSQL_DB::$db_num_queries]['query'] =  $query;
				if(class_exists('Module') and isset(Module::$current->data['module']))
				{
					MSSQL_DB::$db_query_info[MSSQL_DB::$db_num_queries]['module_id'] = Module::$current->data['module']['name'];
				}
				MSSQL_DB::$db_query_info[MSSQL_DB::$db_num_queries]['timer']=(class_exists('Portal'))?Portal::$page_gen_time->get_timer():0;
			}
		}	
		return MSSQL_DB::$db_result;
	}
	static function db_queries()
	{
		return MSSQL_DB::$db_query_info;
	}
	//Tra ve ban ghi query tu CSDL bang lenh SQL $query neu co
	//Neu khong co tra ve false
	//$query: cau lenh SQL se thuc hien
	static function exists($query)
	{
		MSSQL_DB::query($query);
		if(MSSQL_DB::num_rows()>=1)
		{
			return MSSQL_DB::fetch();
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
			if(!isset(MSSQL_DB::$db_exists_db[$table][$id]))
			{
				MSSQL_DB::$db_exists_db[$table][$id]=MSSQL_DB::exists('select * from '.$table.' where id="'.$id.'" limit 0,1');
			}
			return MSSQL_DB::$db_exists_db[$table][$id];
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
			$query='insert ';
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
						$query.=''.MSSQL_DB::escape($value).'';
					}else{
						$query.='"'.MSSQL_DB::escape($value).'"';
					}
				}
				$i++;
			}
			$query.=')';
			//echo $query;
			if(MSSQL_DB::query($query))
			{
				$id = MSSQL_DB::insert_id($table);
				if(isset(MSSQL_DB::$db_cache_tables[$table]))
				{
					//MSSQL_DB::refresh_cache($table);
				}
				return $id;
			}
		}
	}
	static function delete($table, $condition)
	{
		$query='delete from '.$table.' where '.$condition;
		//echo $query;
		if(MSSQL_DB::query($query))
		{		
			if(isset(MSSQL_DB::$db_cache_tables[$table]))
			{
				//MSSQL_DB::refresh_cache($table);
			}		
			return true;
		}
	}
	static function delete_id($table, $id)
	{
		return MSSQL_DB::delete($table, 'id="'.addslashes($id).'"');
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
						$query.=''.$key.'=NULL';
					}
					else
					{
						$query.=''.$key.'=\''.MSSQL_DB::escape($value).'\'';
					}
					$i++;
				}
			}
			$query.=' where '.$condition;
			if(MSSQL_DB::query($query))
			{
				if(isset(MSSQL_DB::$db_cache_tables[$table]))
				{
					MSSQL_DB::refresh_cache($table);
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
		return MSSQL_DB::update($table, $values, 'id="'.$id.'"');
	}
	static function num_rows($query_id = 0)
	{
		if (!$query_id)
		{
			$query_id = MSSQL_DB::$db_result;
		}

		if ($query_id)
		{
			$result = @mssql_num_rows($query_id);

			return $result;
		}
		else
		{
			return false;
		}
	}
	static function affected_rows()
	{

		if (isset(MSSQL_DB::$db_connect_id) and MSSQL_DB::$db_connect_id)
		{
			$result = @mssql_rows_affected(MSSQL_DB::$db_connect_id);

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
			MSSQL_DB::query($sql);
		}
		$query_id = MSSQL_DB::$db_result;
		if ($query_id)
		{
			if($result = @mssql_fetch_assoc($query_id))
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
			MSSQL_DB::query($sql);
		}
		$query_id = MSSQL_DB::$db_result;
		
		if ($query_id)
		{
			$result=array();
			while($row = @mssql_fetch_assoc($query_id))
			{
				$result[$row['id']] = $row;
			}

			return $result;
		}
		else
		{
			return false;
		}
	}
	static function fetch_all_array($sql=false)
	{
		if($sql)
		{
			MSSQL_DB::query($sql);
		}
		$query_id = MSSQL_DB::$db_result;

		if ($query_id)
		{
			$result=array();
			while($row = @mssql_fetch_assoc($query_id))
			{
				$result[] = $row;
			}

			return $result;
		}
		else
		{
			return false;
		}
	}
	static function insert_id($table)
	{
		if (MSSQL_DB::$db_connect_id)
		{
			$result = MSSQL_DB::fetch('select id from '.$table.' where id=@@IDENTITY','id');
			return $result;
		}
		else
		{
			return false;
		}
	}
	static function free_result($query_id = 0)
	{
		if (!$query_id)
		{
			$query_id = MSSQL_DB::$db_result;
		}
		if ($query_id)
		{
			mssql_free_result($query_id);
			return true;
		}else{
			return false;
		}
	}
	static function error()
	{
		$result['message'] = mssql_get_last_message(MSSQL_DB::$db_connect_id);
		$result['code'] = '';//mssql_errno(MSSQL_DB::$db_connect_id);
		return $result;
	}
	static function escape($sql)
	{
		return mysql_real_escape_string($sql);
	}
	static function num_queries()
	{
		return MSSQL_DB::$db_num_queries;
	}
	// tra ve structure_id cua $id
	static function structure_id($table,$id)
	{
		$row=MSSQL_DB::select($table,'id="'.$id.'"');
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
$mssql_db = new MSSQL_DB('Ducnm','hotel','123456','demo');

?>