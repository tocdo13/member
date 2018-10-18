<?php 
class OverviewReport extends Module{
	function OverviewReport($row){
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY)){			
			require_once 'forms/report.php';
			$this->add_form(new OverviewReportForm());
		}
		else
		{
			URL::access_denied();
		}
	}	
}
?>