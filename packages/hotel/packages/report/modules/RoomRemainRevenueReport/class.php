<?php 
class RoomRemainRevenueReport extends Module
{
	function RoomRemainRevenueReport($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY) or User::can_view_detail(false,ANY_CATEGORY))
		{
            require_once 'forms/report.php';
            $this->add_form(new RoomRemainRevenueReportForm());
		}
		else
		{
            URL::access_denied();
		}
	}
}
?>