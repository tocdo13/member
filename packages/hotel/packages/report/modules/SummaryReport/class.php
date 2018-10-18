<?php 
// Process night audit
// Written by: khoand
// Date: 29/11/2010
class SummaryReport extends Module
{
	function SummaryReport($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{			
			require_once 'forms/summary_report.php';
			$this->add_form(new SummaryReportForm());
		}
		else
		{
			URL::access_denied();
		}
	}	
}
?>