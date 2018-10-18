<?php
class RestaurantProductForm extends Form
{
	function RestaurantProductForm()
	{
		Form::Form("RestaurantProductForm");
		$this->add('id',new IDType(true,'object_not_exists','res_product'));
	}
	function draw()
	{
		$languages = DB::select_all('language');
		DB::query('
			select 
				res_product.id
				,res_product.price 
				,res_product.type 
				,res_product.name_'.Portal::language().' as name 
				,'.PRODUC_CATEGORY.'.name as category_id
				,unit.name_'.Portal::language().' as unit_id
			from 
			 	res_product
				left outer join product_category on product_category.id=res_product.category_id 
				left outer join unit on unit.id=res_product.unit_id 
			where
				res_product.id=\''.URL::get('id').'\''
		);
		if($row = DB::fetch())
		{
			$row['price'] = System::display_number($row['price']); 
			$defintition = array('GOODS'=>'GOODS',''res_product''=>''res_product'','MATERIAL'=>'MATERIAL','EQUIPMENT'=>'EQUIPMENT','SERVICE'=>'SERVICE','TOOL'=>'TOOL');
			if(isset($defintition[$row['type']]))
			{
				$row['type'] = $defintition[$row['type']];
			}
			else
			{
				$row['type'] = '';
			} 
		}
		$languages = DB::select_all('language');
		DB::query('
			select
				store_res_product.*,
				store.name
			from
				store_product
				inner join store on store_id=store.id
			where 
				product_id=\''.URL::get('id').'\''
		);
		$stores = DB::fetch_all();
		$this->parse_layout('detail',$row+array('languages'=>$languages,'stores'=>$stores));
	}
}
?>