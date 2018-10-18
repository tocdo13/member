<?php 
class MassageReservation extends Module
{
	public static $item = array();
	function MassageReservation($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			require_once 'forms/report.php';
			$this->add_form(new MassageReservationReportForm());
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>