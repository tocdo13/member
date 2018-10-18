<?php 
class WeeklyRevenueReport extends Module{
	function WeeklyRevenueReport($row){
		Module::Module($row);		
		{
			if(User::can_view(false,ANY_CATEGORY)){
				require_once 'forms/report.php';
				$this->add_form(new WeeklyRevenueReportForm());
			}else{
				URL::access_denied();
			}
		}
	}
}
?>