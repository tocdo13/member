<?php
class PaymentTypeForm extends Form
{
	function PaymentTypeForm()
	{
		Form::Form("PaymentTypeForm");
		$this->add('id',new IDType(true,'object_not_exists','payment_type'));
		$this->link_css(Portal::template('core').'/css/payment_type.css');
	}
	function on_submit()
	{
		if(Url::get('id') and $payment_type=DB::fetch('select id,structure_id from payment_type where id='.intval(Url::get('id'))) and User::can_edit(false,$payment_type['structure_id']))
		{
			$this->delete($this,$_REQUEST['id']);
			Url::redirect_current(Module::$current->redirect_parameters);
		}
		else
		{
			Url::redirect_current(Module::$current->redirect_parameters);
		}
	}
	function draw()
	{
		$this->load_data();
		$languages = DB::select_all('language');
		$this->parse_layout('detail',$this->item_data+array('languages'=>$languages));
	}
	function delete(&$form,$id)
	{
		$this->item_data = DB::select('payment_type',$id);
		 if(file_exists($this->item_data['icon_url']))
		{
			@unlink($this->item_data['icon_url']);
		} 
		DB::delete_id('payment_type', $id);
	}
	function load_data()
	{
		DB::query('
			select 
				`payment_type`.id
				,`payment_type`.structure_id
				,`payment_type`.`is_visible` ,`payment_type`.`icon_url` 
				

				,`payment_type`.name_'.Portal::language().' as name 

				,`payment_type`.description_'.Portal::language().' as description 
				

				,`type`.`id` as type 
			from 
			 	`payment_type`
				

				left outer join `type` on `type`.id=`payment_type`.type 
			where
				`payment_type`.id = "'.URL::sget('id').'"');
		if($this->item_data = DB::fetch())
		{
		}
	}
	function load_multiple_items()
	{
	}
}
?>
