<?php 
class GeneralReservationReport extends Module
{
	function GeneralReservationReport($row){
		   Module::Module($row);
			if(User::can_view(false,ANY_CATEGORY)){
				require_once 'forms/report.php';
				$this->add_form(new GeneralReservationReportForm());
			}else{
				URL::access_denied();
			}
	}
}
?>