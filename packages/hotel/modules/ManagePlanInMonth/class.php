<?php 
class ManagePlanInMonth extends Module
{
	function ManagePlanInMonth($row)
	{
		Module::Module($row);
        if(User::can_edit(false,ANY_CATEGORY))
        {
			require_once 'forms/edit.php';
			$this->add_form(new ManagePlanInMonthForm());
		}
        else
        {
			URL::access_denied();
		}
	}	
}
?>