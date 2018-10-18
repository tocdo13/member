<?php 
// Night audit report
// Written by: khoand
// Date: 29/11/2010
class TestReadWriteFile extends Module
{
	function TestReadWriteFile($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			require_once 'forms/report.php';
			$this->add_form(new SummaryReportForm());
		}
		else
		{
			URL::access_denied();
		}
	}	
}
?>