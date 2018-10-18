<?php 
class Deliver extends Module
{
	function Deliver($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			
			require_once 'forms/edit.php';
			$this->add_form(new EditDeliverForm());
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>