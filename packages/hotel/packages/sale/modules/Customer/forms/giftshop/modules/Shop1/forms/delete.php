<?php
class DeleteShopForm extends Form
{
	function DeleteShopForm()
	{
		Form::Form("DeleteShopForm");
		$this->add('id',new IDType(true,'object_not_exists','shop'));
	}
	function on_submit()
	{
		if($this->check())
		{
			$this->delete($_REQUEST['id']);
			Url::redirect_current(array(
	'shop_code', 'shop_name', 
	));
		}
	}
	function draw()
	{
		DB::query('
			select 
				shop.id
				,shop.code ,shop.name 
			from 
			 	shop
			where
				shop.id = '.URL::get('id'));
		if($row = DB::fetch())
		{
		}
		$this->parse_layout('delete',$row);
	}
	function permanent_delete($id)
	{
		$row = DB::select('shop',$id);
		DB::delete_id('shop', $id);
	}
	function delete($id)
	{
		DeleteShopForm::permanent_delete($id);
	}
}
?>