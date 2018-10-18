<?php 
class MiceReportSetup extends Module
{
	function MiceReportSetup($row)
	{
		Module::Module($row);
		require_once 'forms/report.php';
		$this->add_form(new MiceReportSetupForm());
	}
}
?>