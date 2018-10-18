<?php 
class Unit extends Module
{
	function Unit($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			
			require_once 'forms/edit.php';
			$this->add_form(new EditUnitForm());
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>