<?php 
class OccupancyForecastReportNew extends Module
{
	function OccupancyForecastReportNew($row){
		   Module::Module($row);
			if(User::can_view(false,ANY_CATEGORY)){
				require_once 'forms/report.php';
				$this->add_form(new OccupancyForecastReportNewForm());
			}else{
				URL::access_denied();
			}
	}
}
?>