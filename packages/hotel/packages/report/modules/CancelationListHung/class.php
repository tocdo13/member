<?php 
class CancelationListHung extends Module
{
	function CancelationListHung($row)
	{
		Module::Module($row);	
		if(User::can_view(false,ANY_CATEGORY))
		{	
		    require_once 'forms/report.php';
			$this->add_form(new CancelationListHungForm());
		}
		else
		{ 
		    URL::access_denied();
		}
	}
}
?>