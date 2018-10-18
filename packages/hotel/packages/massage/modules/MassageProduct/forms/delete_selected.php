<?php
class DeleteSelectedMassageProductForm extends Form
{
	function DeleteSelectedMassageProductForm()
	{
		Form::Form("DeleteSelectedMassageProductForm");
		$this->add('confirm',new TextType(true,false,0, 20));
	}
	function on_submit()
	{
		if(URL::get('confirm'))
		{
			require_once 'delete.php';
			foreach(URL::get('selected_ids') as $id)
			{
				if($product=DB::select(PRODUCT,'id=\''.$id.'\''))
				{
					if(DB::select('store_product','product_id=\''.$id.'\''))
					{
						$this->error('confirm','product_in_use');
					}
					else
					{
						DeleteMassageProductForm::delete($id);
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
				massage_product.id
				,massage_product.code 
				,massage_product.price 
				,massage_product.type 
				,massage_product.name_'.Portal::language().' as name 
				,product_category.name as category_id 
				,unit.name_'.Portal::language().' as unit_id 
			from 
			 	massage_product
				left outer join product_category on product_category.id=massage_product.category_id 
				left outer join unit on unit.id=massage_product.unit_id 
			where massage_product.id in (\''.join(URL::get('selected_ids'),'\',\'').'\') and portal_id=\''.PORTAL_ID.'\'
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