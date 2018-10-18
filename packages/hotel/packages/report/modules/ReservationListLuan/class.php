<?php
class ReservationListLuan extends Module
{
    function ReservationListLuan($row)
    {
        Module::Module($row);
    	if(User::can_view(false,ANY_CATEGORY))
		{
		  require_once('forms/report.php');
          $this->add_form(new ReportReservationList);
		}
		else
		{
			URL::access_denied();
		}
    }
}
?>