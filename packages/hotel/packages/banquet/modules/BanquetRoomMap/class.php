<?php 
class BanquetRoomMap extends Module
{
	public static $item = array();
	function BanquetRoomMap($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			require_once 'forms/report.php';
			$this->add_form(new BanquetRoomMapReportForm());
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>