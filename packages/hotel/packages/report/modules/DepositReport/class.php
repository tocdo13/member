<?php 
class DepositReport extends Module
{
	function DepositReport($row)
	{
		Module::Module($row);		
		if(URL::get('reset'))
		{
			URL::redirect_current();
		}
		else
		{
			if(User::can_view(false,ANY_CATEGORY))
			{
				
				require_once 'forms/report_new.php';
				$this->add_form(new DepositReportForm());
			}
			else
			{
				URL::access_denied();
			}
		}
	}
}
?>