<?php
class ViewWarehouseProductForm extends Form
{
	function ViewWarehouseProductForm()
	{
		Form::Form();
		$this->link_css(Portal::template('hotel').'/css/style.css');
	}
	function draw()
	{
		$this->map = array();
		$item = WarehouseProduct::$item;
		if($item){
			$this->map += $item;
		}
		$this->map['title'] = Portal::language('view_product_detail');
		$this->parse_layout('view',$this->map);
	}	
}
?>