<?php 
class BarTableReport extends Module
{
	function BarTableReport($row)
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
				$this->add_form(new BarTableReportForm());
			}
			else
			{
				URL::access_denied();
			}
		}
	}
}
?>