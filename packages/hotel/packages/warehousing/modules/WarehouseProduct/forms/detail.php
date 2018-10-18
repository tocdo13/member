<?php
class WarehouseProductForm extends Form
{
	function WarehouseProductForm()
	{
		Form::Form("WarehouseProductForm");
		$this->add('id',new IDType(true,'object_not_exists',PRODUCT));
	}
	function draw()
	{
		$languages = DB::select_all('language');
		DB::query('
			select 
				wh_product.id
				,wh_product.price 
				,wh_product.type 
				,wh_product.name_'.Portal::language().' as name 
				,product_category.name as category_id
				,unit.name_'.Portal::language().' as unit_id
			from 
			 	wh_product
				left outer join product_category on product_category.id=wh_product.category_id 
				left outer join unit on unit.id=wh_product.unit_id 
			where
				wh_product.id=\''.URL::get('id').'\''
		);
		if($row = DB::fetch())
		{
			$row['price'] = System::display_number($row['price']); 
			$defintition = array('GOODS'=>'GOODS','PRODUCT'=>'PRODUCT','MATERIAL'=>'MATERIAL','EQUIPMENT'=>'EQUIPMENT','SERVICE'=>'SERVICE','TOOL'=>'TOOL');
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
				store_wh_product.*,
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