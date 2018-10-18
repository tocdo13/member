<?php
class DeleteSelectedTelephoneNumberForm extends Form
{
	function DeleteSelectedTelephoneNumberForm()
	{
		Form::Form("DeleteSelectedTelephoneNumberForm");
		$this->add('confirm',new TextType(true,false,0, 20));
	}
	function on_submit()
	{
		if(URL::get('confirm'))
		{
			require_once 'delete.php';
			foreach(URL::get('selected_ids') as $id)
			{
				DeleteTelephoneNumberForm::delete($id);
			}
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
			where telephone_number.id in ('.join(URL::get('selected_ids'),',').')
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