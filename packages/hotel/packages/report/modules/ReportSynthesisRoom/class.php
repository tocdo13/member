<?php 
class ReportSynthesisRoom extends Module
{
	function ReportSynthesisRoom($row)
    {
		    require_once('db.php');
            Module::Module($row);
			if(User::can_view(false,ANY_CATEGORY))
            {
				require_once 'forms/report.php';
				$this->add_form(new ReportSynthesisRoomForm());
			}
            else
            {
				URL::access_denied();
			}
	}
}
?>