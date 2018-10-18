<?php 
class RevenueSituationReport extends Module
{
	function RevenueSituationReport($row){
		   Module::Module($row);
			if(User::can_view(false,ANY_CATEGORY)){
				require_once 'forms/report.php';
				$this->add_form(new RevenueSituationReportForm());
			}else{
				URL::access_denied();
			}
	}
}
?>