<?php 
class TelephoneList extends Module
{
	function TelephoneList($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			require_once 'db.php';
			require_once 'forms/list.php';
			$this->add_form(new ListTelephoneListForm());				
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>