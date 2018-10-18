<?php
class PickupSeeoff extends Module
{
    function PickupSeeoff($row)
    {
        Module::Module($row);
    	if(User::can_view(false,ANY_CATEGORY))
		{
		  require_once('forms/report.php');
          $this->add_form(new ReportPickupSeeoff);
		}
		else
		{
			URL::access_denied();
		}
    }
}
?>