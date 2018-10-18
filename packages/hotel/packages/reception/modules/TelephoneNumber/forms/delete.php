<?php
class DeleteTelephoneNumberForm extends Form
{
	function DeleteTelephoneNumberForm()
	{
		Form::Form("DeleteTelephoneNumberForm");
		$this->add('id',new IDType(true,'object_not_exists','telephone_number'));
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
				telephone_number.id
				,telephone_number.phone_number
				,room.name as room_id 
			from 
			 	telephone_number
				

				left outer join room on room.id=telephone_number.room_id 
			where
				telephone_number.id = '.URL::get('id'));
		if($row = DB::fetch())
		{
		}
		$this->parse_layout('delete',$row);
	}
	function permanent_delete($id)
	{
		$row = DB::select('telephone_number',$id);
		DB::delete_id('telephone_number', $id);
	}
	function delete($id)
	{
		$row = DB::select('telephone_number',$id);
			DeleteTelephoneNumberForm::permanent_delete($id);
	}
}
?>