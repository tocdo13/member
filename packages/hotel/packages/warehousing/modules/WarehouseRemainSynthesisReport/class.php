<?php 
class WarehouseRemainSynthesisReport extends Module
{
	public static $item = array();
	function WarehouseRemainSynthesisReport($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY)){
			require_once 'forms/options.php';
			$this->add_form(new WarehouseRemainSynthesisReportForm());
		}else{
			Url::access_denied();
		}
	}	
}
?>