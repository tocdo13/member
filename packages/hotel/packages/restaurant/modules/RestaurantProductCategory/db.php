<?php
class RestaurantProductCategoryDB
{	
	function GetZone($structure_id='1010000000000000000')
	{
		return DB::fetch_all('
			SELECT
				*
			FROM
				country 
			WHERE
				'.IDStructure::direct_child_cond($structure_id,true).'	
			ORDER BY
				structure_id		
		');	
	}	
	static function check_categories($categories)
	{
		foreach($categories as $id=>$category)
		{
			if(!User::can_view(false,$category['structure_id']))
			{
				unset($categories[$id]);
			}
		}
		return $categories;
	}
	static function get_categories($cond)
	{
		return DB::fetch_all('
			select 
				res_product_category.id 
				,res_product_category.structure_id
				,res_product_category.name
				,res_product_category.code				
			from 
			 	res_product_category
			where
				 '.$cond.'
			order by 
				res_product_category.structure_id
		',false);
	}
}
?>