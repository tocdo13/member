<?php 
class HKWarehouseReport extends Module
{
	public static $item = array();
	function HKWarehouseReport($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY)){
			require_once 'forms/options.php';
			$this->add_form(new HKWarehouseReportOptionsForm());
		}else{
			Url::access_denied();
		}
	}	
}
?>