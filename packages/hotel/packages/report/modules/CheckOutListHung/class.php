<?php 
class CheckOutListHung extends Module
{
	function CheckOutListHung($row)
	{
		Module::Module($row);	
		if(User::can_view(false,ANY_CATEGORY))
		{	
		    require_once 'forms/report.php';
			$this->add_form(new CheckOutListHungForm());
		}
		else
		{ 
		    URL::access_denied();
		}
	}
}
?>