<?php 
class TotalyReport extends Module
{
	function TotalyReport($row){
		   Module::Module($row);
			if(User::can_view(false,ANY_CATEGORY))
            {
                require_once 'db.php';
				require_once 'forms/report.php';
				$this->add_form(new TotalyReportForm());
			}
            else
            {
				URL::access_denied();
			}
	}
}
?>