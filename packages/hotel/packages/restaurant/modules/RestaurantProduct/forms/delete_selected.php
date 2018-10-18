<?php
class DeleteSelectedRestaurantProductForm extends Form
{
	function DeleteSelectedRestaurantProductForm()
	{
		Form::Form("DeleteSelectedRestaurantProductForm");
		$this->add('confirm',new TextType(true,false,0, 20));
	}
	function on_submit()
	{
		if(URL::get('confirm'))
		{
			require_once 'delete.php';
			foreach(URL::get('selected_ids') as $id)
			{
				if($product=DB::select('res_product','id=\''.$id.'\''))
				{
					if(DB::select('store_product','product_id=\''.$id.'\''))
					{
						$this->error('confirm','product_in_use');
					}
					else
					{
						DeleteRestaurantProductForm::delete($id);
					}
				}
			}
			Url::redirect_current();
		}
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
				,product_category.name as category_id 
				,unit.name_'.Portal::language().' as unit_id 
			from 
			 	res_product
				left outer join product_category on product_category.id = res_product.category_id 
				left outer join unit on unit.id = res_product.unit_id 
			where res_product.id in (\''.join(URL::get('selected_ids'),'\',\'').'\')
		');
		$items = DB::fetch_all();
		foreach($items as $key=>$item)
		{
			$items[$key]['price']=System::display_number($item['price']); 
			$defintition = array(
				'GOODS'=>'GOODS',
				'PRODUCT'=>'PRODUCT',
				'MATERIAL'=>'MATERIAL',
				'EQUIPMENT'=>'EQUIPMENT',
				'TOOL'=>'TOOL'
			);
			if(isset($defintition[$items[$key]['type']]))
			{
				$items[$key]['type'] = $defintition[$items[$key]['type']];
			}
			else
			{
				$items[$key]['type'] = '';
			} 
		}
		$this->parse_layout('delete_selected',
			array(
				'items'=>$items
			)
		);
	}
}
?>