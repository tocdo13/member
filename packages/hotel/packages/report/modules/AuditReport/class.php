<?php 
// Night audit report
// Written by: khoand
// Date: 29/11/2010
class AuditReport extends Module
{
	function AuditReport($row)
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