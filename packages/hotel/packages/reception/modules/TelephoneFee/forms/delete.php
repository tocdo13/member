<?php
class DeleteTelephoneFeeForm extends Form
{
	function DeleteTelephoneFeeForm()
	{
		Form::Form("DeleteTelephoneFeeForm");
		$this->add('id',new IDType(true,'object_not_exists','telephone_fee'));
	}
	function on_submit()
	{
		if($this->check() and URL::get('confirm'))
		{
			$this->delete($_REQUEST['id']);
			Url::redirect_current(array(
	));
		}
	}
	function draw()
	{
		DB::query('
			select 
				telephone_fee.id
				,telephone_fee.name ,telephone_fee.prefix ,telephone_fee.fee 
			from 
			 	telephone_fee
			where
				telephone_fee.id = '.URL::get('id'));
		if($row = DB::fetch())
		{
			  

			$row['fee'] = System::display_number($row['fee']); 
		}
		$this->parse_layout('delete',$row);
	}
	function permanent_delete($id)
	{
		$row = DB::select('telephone_fee',$id);
		DB::delete_id('telephone_fee', $id);
	}
	function delete($id)
	{
		$row = DB::select('telephone_fee',$id);
			DeleteTelephoneFeeForm::permanent_delete($id);
	}
}
?>