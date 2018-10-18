<?php
class MenuHelpListDB
{
	function get_help_list($cond ='1 =1',$structure_id = ID_ROOT)
	{
		$sql = 'select 
				help_content.*,
				name_'.Portal::language().' as name
				,description_'.Portal::language().' as description				
			 from 
			 	help_content
			where 
				'.$cond .' and '.IDStructure::child_cond($structure_id).'
			order by 
				structure_id';
		$categories = DB::fetch_all($sql);
		return String::array2tree($categories,'childs');
	}	
}
?>
