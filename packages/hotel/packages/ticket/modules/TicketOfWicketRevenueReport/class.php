<?php 
class TicketOfWicketRevenueReport extends Module
{
	function TicketOfWicketRevenueReport($row)
	{
		Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
            require_once 'forms/report.php';
            $this->add_form(new TicketOfWicketRevenueReportForm());                   
        }
        else{
            Url::access_denied();
        }
	}	
}
?>