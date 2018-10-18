<?php
class BackupForm extends Form
{
	function BackupForm()
	{
		Form::Form('BackupForm');
		$this->link_css(Portal::template('hotel').'/css/setting.css');
	}
	function save_file($file,$content)
	{		
		$path = $this->make_dir('backup/'.strtoupper(substr(Session::get('user_id'),0,1)).substr(Session::get('user_id'),1).'_backup_'.date('h\hi.d-m-y',time()));		
		$hand = fopen($path.'/'.strtolower($file).'.sql','w+');
		fwrite($hand,$content);
		fclose($hand);
	}
	function make_dir($name)
	{
		if(!is_dir($name))
		{
			@mkdir($name);
		}
		return $name;
	}	
	static function make_sql($table, $values)
	{
		$query='INSERT INTO';		
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
		}
		return $query;
	}
	function create_db($fields)
	{
		$table = false;
		$sql = 'CREATE TABLE '; 
		foreach($fields as $key=>$value)
		{
			if(!$table)
			{				
				$sql .='"'.$value['table_name'].'" (';
				$table = true;
			}	
			$sql .=' "'.$value['column_name'].'" '.$value['data_type'];
			switch($value['data_type'])
			{
				case 'NUMBER':
					if($value['data_precision']!='')
					{
						$sql .='('.$value['data_precision'].','.$value['data_scale'].')';
					}
					break;
				case 'TIMESTAMP(6)':
				case 'DATE':
				case 'CLOB':	
				case 'BLOB':
				case 'LONG':	
					break;
				default:
					$sql .='('.$value['data_length'].')';
					break;					
			}			
			if($value['data_default']!='')
			{
				$sql .=' DEFAULT '.$value['data_default'];
			}
			if($value['nullable']=='N')
			{
				$sql .=' NOT NULL ENABLE';
			}
			$sql .=',';
		}
		$sql .='PRIMARY KEY ("ID") ENABLE)\n';
		return $sql;
	}
	function backup_data($table)
	{
		$query = '';
		if($data = DB::fetch_all('select * from '.$table))
		{
			foreach($data as $key=>$value)
			{
				$query .= $this->make_sql($table,$value).'\n';
			}
		}	
		return $query;
	}
	function backup($tables)
	{
		$all_tables = DB::get_all_tables('');
        $sql = '';		
		foreach($tables as $key=>$value)
		{
			
			if(isset($all_tables[$value]))
			{
				$fields = DB::get_fields($value);
				$sql .= $this->create_db($fields).";\n";
				//$sql .= $this->backup_data($value);
			}
			
		}
        $this->save_file('database',$sql);
	}
	function on_submit()
	{
		if(isset($_REQUEST['backup']) and isset($_REQUEST['selected_ids']) and count($_REQUEST['selected_ids'])>0)
		{
			$this->backup($_REQUEST['selected_ids']);		
		}
		Url::redirect_current();
	}
	function draw()
	{
		$tables = DB::get_all_tables();
		$this->parse_layout('backup',array('tables'=>$tables));
	}
}
?>
