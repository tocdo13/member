<?php
class BackupByTimeForm extends Form
{
	function BackupByTimeForm()
	{
		Form::Form('BackupByTimeForm');
		$this->link_css(Portal::template('hotel').'/css/setting.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
    //Tạo thư mục
	function make_dir($name)
	{
		if(!is_dir($name))
		{
			@mkdir($name);
		}
		return $name;
	}
	function on_submit()
	{
        if(!isset($_REQUEST['backup']) )
        {
            //do nothing
        }
        else
        {
    		if(isset($_REQUEST['backup']) and isset($_REQUEST['selected_ids']) and count($_REQUEST['selected_ids'])>0)
    		{
                require_once 'cache/config/config_backup.php';
                $all_tables = DB::get_all_tables('');	
                foreach($_REQUEST['selected_ids'] as $table)
                {
                    if( isset( $all_tables[strtoupper($table)] ) )
                    {
                        $this->backup_table($table,$link_table);
                    }
                }
                Url::redirect_current(array('department'));		
    		}
            
        }
		
	}
	function draw()
	{
        $this->map = array();
        $this->map['date_from'] = Url::get('date_from')?Url::get('date_from'):date('1/m/Y');
        $_REQUEST['date_from'] = $this->map['date_from'];
        $this->map['date_to'] = Url::get('date_to')?Url::get('date_to'):date('d/m/Y');
        $_REQUEST['date_to'] = $this->map['date_to'];
        
		$date_from = Date_Time::to_orc_date( $this->map['date_from'] );
		$date_to = Date_Time::to_orc_date( $this->map['date_to'] );
        
        require_once 'cache/config/config_backup.php';
        //System::debug($department);
        $this->map['table'] = array();
        if(Url::get('department'))
        {
            $this->map['table'] = $department[Url::get('department')]['tables'];
        }
        $this->map['department_list'] = array(''=>Portal::language('select_department')) + String::get_list($department);
		$this->parse_layout('backup',array()+$this->map);
	}
    
    /**
     * table name : bảng cần backup
     * link table : mảng cấu hình trong file config
     * 
     */
    function backup_table($table_name,$link_table)
    {
        $cond = '';
        //trường để ss time
        $field = $link_table[$table_name]['compare_filed'];
        if( $link_table[$table_name]['data_type'] =='number' )
        {
            $cond = '  '.$table_name.'.'.$field.' >= \''.Date_Time::to_time(Url::get('date_from')).'\' 
                        AND '.$table_name.'.'.$field.' < \''.( Date_Time::to_time(Url::get('date_to')) + 86400 ).'\'  ';
        }
        else
        {
            $cond = ' FROM_UNIXTIME('.$table_name.'.'.$field.') >= '.Date_Time::to_orc_date( Url::get('date_from') ).' 
                    AND FROM_UNIXTIME('.$table_name.'.'.$field.') <= '.Date_Time::to_orc_date( Url::get('date_to') ).' ';
        }
        $sql = $this->create_sql($table_name,$cond,$link_table);
        if($sql)
            $this->save_file($table_name,$sql);
    }
    
    function create_sql($table_name,$cond,$link_table)
    {
        $query = '';
		if($data = DB::fetch_all('select * from '.$table_name.' where '.$cond))
		{
			foreach($data as $key=>$value)
			{
				$query .= $this->make_sql($table_name,$value).'\n';
			}
		}
        //Nếu tồn tại các bảng con của bảng này
        if(isset($link_table[$table_name]['associate']) and !empty( $link_table[$table_name]['associate'] ))
        {
            //Mảng các id chinh
            if($ids = array_keys($data))
            {
                foreach($link_table[$table_name]['associate'] as $assoc_tbl => $field)
                {
                    $this->save_associate_table($table_name,$assoc_tbl,$field,$ids);
                }
            }       
        }	
		return $query;
        
    }
    function make_sql($table, $values)
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
        //System::debug($query);
		return $query;
	}
	function save_file($file,$content)
	{
        $path = $this->make_dir('backup/backup_by_time');
		$path = $this->make_dir( $path.'/'.strtoupper(substr(Session::get('user_id'),0,1)).substr(Session::get('user_id'),1).'_backup_at_'.date('H\hi.d-m-y',time()).'_from_'.str_replace( '/','-',Url::get('date_from') ).'_to_'.str_replace( '/','-',Url::get('date_to') ).'_'.Url::get('department') );		
		$hand = fopen($path.'/'.strtolower($file).'.sql','w+');
		fwrite($hand,$content);
		fclose($hand);
	}
    
    function save_associate_table($table_name, $assoc_tbl,$field,$ids)
    {
        $cond = '';
        foreach($ids as $id)
        {
            $cond.= $cond? ' OR '.$assoc_tbl.'.'.$field.' = '.$id : '  '.$assoc_tbl.'.'.$field.' = '.$id;
        }
        $sql = $this->create_sql($assoc_tbl,$cond,array());
        if($sql)
            $this->save_file($assoc_tbl,$sql);
        
    }
}
?>
