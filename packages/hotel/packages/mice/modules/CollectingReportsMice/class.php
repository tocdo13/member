<?php 
class CollectingReportsMice extends Module
{
	function CollectingReportsMice($row)
	{
	   Module::Module($row);
	   if(User::can_view(false,ANY_CATEGORY))
       {
    		require_once 'forms/report.php';
    		$this->add_form(new CollectingReportsMiceForm());
       }
       else
       {
            Url::access_denied();
       }
	}
}
?>