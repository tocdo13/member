<?php 
class GuestHistory extends Module
{
	function GuestHistory($row)
	{
		Module::Module($row);
		require_once('db.php');
		if(User::can_view(false,ANY_CATEGORY))
		{
			require_once 'forms/list.php';
			$this->add_form(new ListGuestHistoryForm());
	
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>