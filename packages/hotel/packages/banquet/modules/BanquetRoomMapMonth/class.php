<?php 
class BanquetRoomMapMonth extends Module
{
	public static $item = array();
	function BanquetRoomMapMonth($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			require_once 'forms/report.php';
			$this->add_form(new BanquetRoomMapMonthReportForm());
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>