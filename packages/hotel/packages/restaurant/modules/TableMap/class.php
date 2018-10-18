<?php 
class TableMap extends Module
{
	function TableMap($row)
	{
		Module::Module($row);
		if(Url::get('bar_id'))
		{
			Session::set('bar_id',intval(Url::get('bar_id')));
			$sql_group='SELECT 
                        REPLACE(LOWER(FN_CONVERT_TO_VN(bar_table.table_group)),\' \',\'_\') as id
                        ,bar_table.table_group as name 
                    FROM bar_table 
                        INNER JOIN bar on bar.id=bar_table.bar_id 
                    WHERE bar_table.bar_id = '.Session::get('bar_id').' GROUP BY bar_table.table_group ORDER BY bar_table.table_group';		
			$groups = DB::fetch_all($sql_group);
			$name_group = '';
			foreach($groups as $k => $gr){
				$name_group = $gr['id'];
				break;
			}
			Session::set('group',$name_group);
		}else if(!Session::is_set('bar_id')){
			$bar = DB::fetch('select min(id) as id from bar where 1>0 and portal_id=\''.PORTAL_ID.'\'','id');	
			$bar_id = $bar;	
            $bar_code = DB::fetch('select bar.* from bar where id='.$bar_id.' and portal_id=\''.PORTAL_ID.'\'');	
			if($bar_id){
				Session::set('bar_id',$bar['id']);
			}else{
				Session::set('bar_id','');
			}  
		}
		$_REQUEST['bar_id'] = Session::get('bar_id');
		if(User::can_view(false,ANY_CATEGORY)){
			require_once 'forms/list.php';
			$this->add_form(new EditTableMapForm());
		}else{
			URL::access_denied();
		}
	}
}
?>