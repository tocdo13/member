<?php 
class DayToDayTravellerReport extends Module
{
	function DayToDayTravellerReport($row)
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
			    /* if(User::id()=='developer05'){
			         require_once 'forms/list.php';
			     }else{
				    require_once 'forms/list.php';
                } */
                require_once 'forms/list.php';
				$this->add_form(new DayToDayTravellerReportForm());
			}
			else
			{
				URL::access_denied();
			}
		}
	}
}
?>