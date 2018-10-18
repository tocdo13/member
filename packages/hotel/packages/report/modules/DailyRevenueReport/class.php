<?php 
class DailyRevenueReport extends Module
{
	function DailyRevenueReport($row){
		   Module::Module($row);
			if(User::can_view(false,ANY_CATEGORY)){
				require_once 'forms/report.php';
				$this->add_form(new DailyRevenueReportForm());
			}else{
				URL::access_denied();
			}
	}
}
?>