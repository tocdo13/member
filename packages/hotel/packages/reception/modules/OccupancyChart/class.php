<?php 
class OccupancyChart extends Module{
	function OccupancyChart($row){
		Module::Module($row);		
		{
			if(User::can_view(false,ANY_CATEGORY)){
				require_once 'forms/report.php';
				$this->add_form(new OccupancyChartForm());
			}else{
				URL::access_denied();
			}
		}
	}
}
?>