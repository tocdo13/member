<?php 
class RoomStatusReport extends Module
{
	function RoomStatusReport($row){
		   Module::Module($row);
			if(User::can_view(false,ANY_CATEGORY)){
				require_once 'forms/report.php';
				$this->add_form(new RoomStatusReportForm());
			}else{
				URL::access_denied();
			}
	}
}
?>