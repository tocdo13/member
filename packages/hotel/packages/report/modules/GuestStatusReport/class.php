<?php 
class GuestStatusReport extends Module
{
	function GuestStatusReport($row)
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
				
				require_once 'forms/report.php';
				$this->add_form(new GuestStatusReportForm());
			}
			else
			{
				URL::access_denied();
			}
		}
	}
}
?>