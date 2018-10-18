<?php
class HKProductForm extends Form
{
	function HKProductForm()
	{
		Form::Form("HKProductForm");
		$this->add('id',new IDType(true,'object_not_exists',PRODUCT));
	}
	function draw()
	{
		$languages = DB::select_all('language');
		DB::query('
			select 
				'.PRODUCT.'.id
				,'.PRODUCT.'.price 
				,'.PRODUCT.'.type 
				,'.PRODUCT.'.name_'.Portal::language().' as name 
				,'.PRODUC_CATEGORY.'.name as category_id
				,unit.name_'.Portal::language().' as unit_id
			from 
			 	'.PRODUCT.'
				left outer join '.PRODUCT_CATEGORY.' on '.PRODUCT_CATEGORY.'.id='.PRODUCT.'.category_id 
				left outer join unit on unit.id='.PRODUCT.'.unit_id 
			where
				'.PRODUCT.'.id=\''.URL::get('id').'\''
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
				store_'.PRODUCT.'.*,
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