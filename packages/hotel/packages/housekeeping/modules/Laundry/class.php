<?php 
class Laundry extends Module
{
	function Laundry($row)
	{
		Module::Module($row);
		if(User::can_edit(false,ANY_CATEGORY))
		{
			require_once 'forms/edit.php';
			$this->add_form(new EditLaundryForm());
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>