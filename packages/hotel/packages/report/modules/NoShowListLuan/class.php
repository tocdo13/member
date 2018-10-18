<?php
class NoShowListLuan extends Module
{
    function NoShowListLuan($row)
    {
        Module::Module($row);
    	if(User::can_view(false,ANY_CATEGORY))
		{
		  require_once('forms/report.php');
          $this->add_form(new ReportNoShowList);
		}
		else
		{
			URL::access_denied();
		}
    }
}
?>