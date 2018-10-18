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