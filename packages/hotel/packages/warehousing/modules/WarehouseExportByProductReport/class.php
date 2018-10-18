<?php 
class WarehouseExportByProductReport extends Module
{
	public static $item = array();
	function WarehouseExportByProductReport($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY)){
			require_once 'forms/options.php';
			$this->add_form(new WarehouseExportByProductReportForm());
		}else{
			Url::access_denied();
		}
	}	
}
?>