<?php 
class StatisticsReport extends Module
{
	function StatisticsReport($row){
		   Module::Module($row);
			if(User::can_view(false,ANY_CATEGORY)){
				require_once 'forms/report.php';
				$this->add_form(new StatisticsReportForm());
			}else{
				URL::access_denied();
			}
	}
}
?>