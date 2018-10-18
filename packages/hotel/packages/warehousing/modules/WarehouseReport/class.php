<?php 
class WarehouseReport extends Module
{
	public static $item = array();
	function WarehouseReport($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY)){
			require_once 'forms/options.php';
			$this->add_form(new WarehouseReportOptionsForm());
		}else{
			Url::access_denied();
		}
	}	
}
?>