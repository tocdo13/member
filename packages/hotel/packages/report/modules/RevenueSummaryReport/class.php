<?php 
class RevenueSummaryReport extends Module
{
	function RevenueSummaryReport($row){
		   Module::Module($row);
			if(User::can_view(false,ANY_CATEGORY)){
				require_once 'forms/report.php';
				$this->add_form(new RevenueSummaryReportForm());
			}else{
				URL::access_denied();
			}
	}
}
?>