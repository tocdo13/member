<?php 
class WarehouseImportReport extends Module
{
	public static $item = array();
	function WarehouseImportReport($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY)){
			require_once 'forms/options.php';
			$this->add_form(new WarehouseImportReportOptionsForm());
		}else{
			Url::access_denied();
		}
	}	
}
?>