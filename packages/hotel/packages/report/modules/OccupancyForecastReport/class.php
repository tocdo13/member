<?php 
class OccupancyForecastReport extends Module
{
	function OccupancyForecastReport($row){
		   Module::Module($row);
			if(User::can_view(false,ANY_CATEGORY)){
				require_once 'forms/report.php';
				$this->add_form(new OccupancyForecastReportForm());
			}else{
				URL::access_denied();
			}
	}
}
?>