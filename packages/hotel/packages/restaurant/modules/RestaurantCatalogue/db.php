<?php
class RestaurantCatalogueDB
{	
	static function get_categories($cond)
	{
		return DB::fetch_all('
			select 
				product_category.id 
				,product_category.structure_id
				,product_category.name
				,product_category.code				
			from 
			 	product_category
			where
				 '.$cond.'
			order by 
				product_category.structure_id
		',false);
	}
}
?>