<?php 
class QuickDailyRevenueReport extends Module
{
	function QuickDailyRevenueReport($row){
		   Module::Module($row);
			if(User::can_view(false,ANY_CATEGORY)){
				require_once 'forms/report.php';
				$this->add_form(new QuickDailyRevenueReportForm());
			}else{
				URL::access_denied();
			}
	}
}
?>