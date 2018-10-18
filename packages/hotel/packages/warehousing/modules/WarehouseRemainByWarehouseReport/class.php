<?php 
class WarehouseRemainByWarehouseReport extends Module
{
	public static $item = array();
	function WarehouseRemainByWarehouseReport($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY)){
			require_once 'forms/options.php';
			$this->add_form(new WarehouseRemainByWarehouseReportForm());
		}else{
			Url::access_denied();
		}
	}	
}
?>