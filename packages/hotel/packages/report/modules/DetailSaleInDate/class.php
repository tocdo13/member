<?php 
class DetailSaleInDate extends Module
{
	function DetailSaleInDate($row)
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
				$this->add_form(new DetailSaleInDateForm());
			}
			else
			{
				URL::access_denied();
			}
		}
	}
}
?>