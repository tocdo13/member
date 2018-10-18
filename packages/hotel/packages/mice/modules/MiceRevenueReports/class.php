<?php 
class MiceRevenueReports extends Module
{
	function MiceRevenueReports($row)
	{
Module::Module($row);

	   if(User::can_view(false,ANY_CATEGORY))
       {
                		require_once 'forms/report.php';
    		$this->add_form(new MiceRevenueReportsForm());
       }
       else
       {
            Url::access_denied();
       }
	}
}
?>