<?php
class DeleteSelectedShopForm extends Form
{
	function DeleteSelectedShopForm()
	{
		Form::Form("DeleteSelectedShopForm");
		$this->add('confirm',new TextType(true,false,0, 20));
	}
	function on_submit()
	{
		require_once 'delete.php';
		foreach(URL::get('selected_ids') as $id)
		{
			DeleteShopForm::delete($id);
		}
		Url::redirect_current(array(
	'shop_code', 'shop_name', 
	));
	}
	function draw()
	{
		DB::query('
			select 
				shop.id
				,shop.code ,shop.name 
			from 
			 	shop
			where shop.id in ('.join(URL::get('selected_ids'),',').')
		');
		$items = DB::fetch_all();
		$this->parse_layout('delete_selected',
			array(
				'items'=>$items
			)
		);
	}
}
?>