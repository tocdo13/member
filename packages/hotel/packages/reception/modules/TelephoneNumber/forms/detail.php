<?php
class TelephoneNumberForm extends Form
{
	function TelephoneNumberForm()
	{
		Form::Form("TelephoneNumberForm");
		$this->add('id',new IDType(true,'object_not_exists','telephone_number'));
	}
	function draw()
	{
		$sql = '
			select 
				telephone_number.id
				,telephone_number.phone_number 
				,room.name as room_id 
			from 
			 	telephone_number
				left outer join room on room.id=telephone_number.room_id 
			where
				telephone_number.id = '.URL::get('id');
		$row = DB::fetch($sql);
		$this->parse_layout('detail',$row);
	}
}
?>