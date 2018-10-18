<?php 
class RevenueSummaryByDayReport extends Module
{
	function RevenueSummaryByDayReport($row){
		   Module::Module($row);
			if(User::can_view(false,ANY_CATEGORY)){
				require_once 'forms/report.php';
				$this->add_form(new RevenueSummaryByDayReportForm());
			}else{
				URL::access_denied();
			}
	}
}
?>