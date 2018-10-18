<?php 
class RoomMapSmart extends Module
{
	function RoomMapSmart($row)
	{
		Module::Module($row);
		if(User::is_login())
        {
			require_once 'forms/room_map.php';
			$this->add_form(new RoomMapSmartForm());
		}
        else
        {
			Url::redirect('sign_in');
		}
	}
}
?>