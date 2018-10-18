<?php 
class AmenitiesUsedReport extends Module
{
	function AmenitiesUsedReport($row)
	{
		Module::Module($row);
		if(URL::get('reset'))
		{
			URL::redirect_current();
		}
		else
		{
			if(User::can_view(false,ANY_CATEGORY) or User::can_view_detail(false,ANY_CATEGORY))
			{
				
				require_once 'forms/report.php';
				$this->add_form(new AmenitiesUsedReportForm());
			}
			else
			{
				URL::access_denied();
			}
		}
	}
}
?>