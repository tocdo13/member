<?php 
class Holiday extends Module
{
	function Holiday($row)
	{
		Module::Module($row);
		if(User::can_edit(false,ANY_CATEGORY))
		{
			require_once 'forms/edit.php';
			$this->add_form(new HolidayForm());
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>