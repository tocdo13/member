<?php 
class WarehouseExportByReceiverReport extends Module
{
	public static $item = array();
	function WarehouseExportByReceiverReport($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY)){
			require_once 'forms/options.php';
			$this->add_form(new WarehouseExportByReceiverReportForm());
		}else{
			Url::access_denied();
		}
	}	
}
?>