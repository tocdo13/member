<?php 
class ManagePlan extends Module
{
	function ManagePlan($row)
	{
		Module::Module($row);
        if(User::can_edit(false,ANY_CATEGORY))
        {
			require_once 'forms/edit.php';
			$this->add_form(new ManagePlanForm());
		}
        else
        {
			URL::access_denied();
		}
	}	
}
?>