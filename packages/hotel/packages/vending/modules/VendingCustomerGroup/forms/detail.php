<?php
class CustomerGroupForm extends Form
{
	function CustomerGroupForm()
	{
		Form::Form('CustomerGroupForm');
		$this->add('id',new IDType(true,'object_not_exists','vending_customer_group'));
	}
	function on_submit()
	{
		if($this->check() and URL::get('confirm'))
		{
			$this->delete($this,$_REQUEST['id']);
			Url::redirect_current(array(
	 'name'=>isset($_GET['name'])?$_GET['name']:'', 
	));
		}
	}
	function draw()
	{
		DB::query('
			select 
				vending_customer_group.id
				,vending_customer_group.structure_id
				,vending_customer_group.name 
			from 
			 	vending_customer_group
			where
				vending_customer_group.id = \''.URL::sget('id').'\'');
		if($row = DB::fetch())
		{
		}
		$this->parse_layout('detail',$row);
	}
	function delete(&$form,$id)
	{
		$row = DB::select('vending_customer_group',$id);
		DB::delete_id('vending_customer_group', $id);
	}
}
?>