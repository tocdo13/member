<?php 
class Minibar extends Module
{
	function Minibar($row)
	{
		Module::Module($row);
		if(User::can_edit(false,ANY_CATEGORY))
		{
			require_once 'forms/edit.php';
			$this->add_form(new EditMinibarForm());
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>