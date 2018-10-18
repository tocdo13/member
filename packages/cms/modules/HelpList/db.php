<?php
class HelpListDB
{	
	function GetZone($structure_id='1010000000000000000')
	{
		return DB::fetch_all('
			SELECT
				*
			FROM
				zone 
			WHERE
				'.IDStructure::direct_child_cond($structure_id,true).'	
			ORDER BY
				structure_id		
		');	
	}	
	static function check_categories($categories)
	{
		foreach($categories as $id=>$help_list)
		{
			if(!User::can_view(false,$help_list['structure_id']))
			{
				unset($categories[$id]);
			}
		}
		return $categories;
	}
	static function get_categories()
	{
		return DB::fetch_all('select help_list.* from help_list where structure_id <>'.ID_ROOT.' order by structure_id');
	}
}
?>