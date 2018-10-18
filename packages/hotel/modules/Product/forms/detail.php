<?php
class ProductForm extends Form
{
	function ProductForm()
	{
		Form::Form("ProductForm");
		$this->add('id',new IDType(true,'object_not_exists',PRODUCT));
	}
	function draw()
	{
		$languages = DB::select_all('language');
		DB::query('
			select 
				product.id
				,product.price 
				,product.type 
				,product.name_'.Portal::language().' as name 
				,'.PRODUC_CATEGORY.'.name as category_id
				,unit.name_'.Portal::language().' as unit_id
			from 
			 	product
				left outer join product_category on product_category.id=product.category_id 
				left outer join unit on unit.id=product.unit_id 
			where
				product.id=\''.URL::get('id').'\''
		);
		if($row = DB::fetch())
		{
			$row['price'] = System::display_number($row['price']);
            require_once 'cache/config/type.php';
            $defintition = unserialize(LIST_TYPE); 
			//$defintition = array('GOODS'=>'GOODS','PRODUCT'=>'PRODUCT','MATERIAL'=>'MATERIAL','EQUIPMENT'=>'EQUIPMENT','SERVICE'=>'SERVICE','TOOL'=>'TOOL');
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
				store_product.*,
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