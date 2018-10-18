<?php 
class YearlyRevenueReport extends Module
{
	function YearlyRevenueReport($row){
		   Module::Module($row);
			if(User::can_view(false,ANY_CATEGORY)){
				require_once 'forms/report.php';
				$this->add_form(new YearlyRevenueReportForm());
			}else{
				URL::access_denied();
			}
	}
}
?>