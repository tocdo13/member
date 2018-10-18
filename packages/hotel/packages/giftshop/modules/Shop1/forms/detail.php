<?php
class ShopForm extends Form
{
	function ShopForm()
	{
		Form::Form("ShopForm");
		$this->add('id',new IDType(true,'object_not_exists','shop'));
	}
	function draw()
	{
		require_once 'list.php';
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
		$this->parse_layout('detail',$row);
	}
}
?>