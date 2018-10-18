<?php 
class TicketInvoiceRevenueReport extends Module
{
	function TicketInvoiceRevenueReport($row)
	{
		Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
            require_once 'forms/report.php';
            $this->add_form(new TicketInvoiceRevenueReportForm());                   
        }
        else{
            Url::access_denied();
        }
	}	
}
?>