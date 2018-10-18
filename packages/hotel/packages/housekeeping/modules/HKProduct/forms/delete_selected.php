<?php
class DeleteSelectedHKProductForm extends Form
{
	function DeleteSelectedHKProductForm()
	{
		Form::Form("DeleteSelectedHKProductForm");
		$this->add('confirm',new TextType(true,false,0, 20));
	}
	function on_submit()
	{
		if(URL::get('confirm'))
		{
			require_once 'delete.php';
			foreach(URL::get('selected_ids') as $id)
			{
				if($product=DB::select('hk_product','id=\''.$id.'\''))
				{
					if(DB::select('store_product','product_id=\''.$id.'\''))
					{
						$this->error('confirm','product_in_use');
					}
					else
					{
						DeleteHKProductForm::delete($id);
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
				hk_product.id
				,hk_product.code 
				,hk_product.price 
				,hk_product.type 
				,hk_product.name_'.Portal::language().' as name 
				,product_category.name as category_id 
				,unit.name_'.Portal::language().' as unit_id 
			from 
			 	hk_product
				left outer join product_category on product_category.id=hk_product.category_id 
				left outer join unit on unit.id=hk_product.unit_id 
			where hk_product.id in (\''.join(URL::get('selected_ids'),'\',\'').'\') and portal_id=\''.PORTAL_ID.'\'
		');
		$items = DB::fetch_all();
		foreach($items as $key=>$item)
		{
			$items[$key]['price']=System::display_number($item['price']); 
		}
		$this->parse_layout('delete_selected',
			array(
				'items'=>$items
			)
		);
	}
}
?>