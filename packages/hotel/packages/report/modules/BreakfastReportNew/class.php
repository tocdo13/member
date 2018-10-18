<?php 
class BreakfastReportNew extends Module
{
	function BreakfastReportNew($row)
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
				$this->add_form(new BreakfastReportNewForm());
			}
			else
			{
				URL::access_denied();
			}
		}
	}
}
?>