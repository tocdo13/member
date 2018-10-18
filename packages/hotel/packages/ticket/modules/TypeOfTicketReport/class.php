<?php 
class TypeOfTicketReport extends Module
{
	function TypeOfTicketReport($row)
	{
		Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
            require_once 'forms/report.php';
            $this->add_form(new TypeOfTicketReportForm());                   
        }
        else{
            Url::access_denied();
        }
	}	
}
?>