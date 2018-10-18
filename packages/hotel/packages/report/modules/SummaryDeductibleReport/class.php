<?php 
class SummaryDeductibleReport extends Module
{
	function SummaryDeductibleReport($row)
	{
		Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
            require_once 'forms/report.php';
		    $this->add_form(new SummaryDeductibleReportForm());
        }
        else
            URL::access_denied();		
	}
}
?>