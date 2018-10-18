<?php 
class TicketServiceTotalReport extends Module
{
	function TicketServiceTotalReport($row)
	{
	    require_once 'db.php';
		Module::Module($row);		
		if(URL::get('reset'))
		{
			URL::redirect_current();
		}
		else
		{
			if(User::can_view(false,ANY_CATEGORY))
			{
				
				require_once 'forms/report.php';
				$this->add_form(new TicketServiceTotalReportForm());
			}
			else
			{
				URL::access_denied();
			}
		}
	}
}
?>