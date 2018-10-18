<?php 
class RepostReservationThuho extends Module
{
	function RepostReservationThuho($row){
		   Module::Module($row);
			if(User::can_view(false,ANY_CATEGORY)){
				require_once 'forms/report.php';
				$this->add_form(new RepostReservationThuhoForm());
			}else{
				URL::access_denied();
			}
	}
}
?>