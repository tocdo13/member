<?php 
class DailyRevenueExtraServiceReport extends Module
{
	function DailyRevenueExtraServiceReport($row){
		   Module::Module($row);
			if(User::can_view(false,ANY_CATEGORY)){
				require_once 'forms/report-new.php';
				$this->add_form(new DailyRevenueExtraServiceReportForm());
			}else{
				URL::access_denied();
			}
	}
}
?>