<?php 
class PackageRevenueReport extends Module
{
	function PackageRevenueReport($row)
	{
		Module::Module($row);		
		if(User::can_view(false,ANY_CATEGORY))
		{
            
			
			require_once 'forms/report.php';
			$this->add_form(new PackageRevenueReportForm());
		}
		else
		{
			URL::access_denied();
		}
		
	}
}
?>