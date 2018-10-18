<?php 
class KaraokeDiscountReport extends Module{
	function KaraokeDiscountReport($row){
		Module::Module($row);		
		{
			if(User::can_view(false,ANY_CATEGORY)){
				require_once 'forms/report.php';
				$this->add_form(new KaraokeDiscountReportForm());
			}else{
				URL::access_denied();
			}
		}
	}
}
?>