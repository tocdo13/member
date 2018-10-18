<?php 
class WarehouseInvoiceReport extends Module
{
	public static $item = array();
	function WarehouseInvoiceReport($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY)){
			require_once 'forms/options.php';
			$this->add_form(new WarehouseInvoiceReportOptionsForm());
		}else{
			Url::access_denied();
		}
	}	
}
?>