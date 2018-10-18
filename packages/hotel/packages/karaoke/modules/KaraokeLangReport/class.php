<?php 
class KaraokeLangReport extends Module
{
	function KaraokeLangReport($row)
	{
		Module::Module($row);	
        require_once 'db.php';
        if(User::can_view(false,ANY_CATEGORY))
		{
			require_once 'forms/report.php';
			$this->add_form(new RestaurantLangReportForm());
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>