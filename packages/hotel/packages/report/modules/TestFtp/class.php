<?php 
class TestFtp extends Module
{
	function TestFtp($row)
	{
		Module::Module($row);

		if(User::can_view(false,ANY_CATEGORY))
			{
				
				require_once 'forms/report.php';
				$this->add_form(new AgentStatisticReportForm());
			}
			else
			{
				URL::access_denied();
			}
	}
}
?>