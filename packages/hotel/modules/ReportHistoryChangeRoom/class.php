<?php 
class ReportHistoryChangeRoom extends Module
{
	function ReportHistoryChangeRoom($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
        {
			require_once 'forms/report.php';
			$this->add_form(new ReportHistoryChangeRoomForm());
		}
        else
        {
			URL::access_denied();
		}
	}
}
?>