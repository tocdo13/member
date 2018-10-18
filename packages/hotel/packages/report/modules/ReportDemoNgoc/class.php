<?php 
class ReportDemoNgoc extends Module
{
	function ReportDemoNgoc ($row){
		   Module::Module($row);
			if(User::can_view(false,ANY_CATEGORY)){
				require_once 'forms/report.php';
				$this->add_form(new ReportDemoNgocForm());
			}else{
				URL::access_denied();
			}
	}
}
?>