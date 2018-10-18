<?php 
class ChartRevenueByStaff extends Module
{
	function ChartRevenueByStaff($row){
		   Module::Module($row);
			if(User::can_view(false,ANY_CATEGORY)){
				require_once 'forms/report.php';
				$this->add_form(new RevenueSituationReportCommonForm());
			}else{
				URL::access_denied();
			}
	}
}
?>