<?php
class WarehouseForm extends Form
{
	function WarehouseForm()
	{
		Form::Form("WarehouseForm");
		$this->add('id',new IDType(true,'object_not_exists','warehouse'));
	}
	function draw()
	{
		$this->load_data();
		$this->parse_layout('detail',$this->item_data);
	}
	function load_data()
	{
		DB::query('
			select 
				*
			from 
			 	warehouse
			where
				warehouse.id = '.URL::iget('id').'');
		$this->item_data = DB::fetch();
	}
}
?>