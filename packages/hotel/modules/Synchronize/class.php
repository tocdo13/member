<?php
class Synchronize extends Module
{
	function Synchronize($row)
	{
		Module::Module($row);
		require_once 'db.php';
		if(User::can_admin(false,ANY_CATEGORY))
		{
			require_once 'forms/list.php';
			$this->add_form(new SynchronizeForm());		
		}
		else
		{
			Url::access_denied();
		}	
	}
}
?>
