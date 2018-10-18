<?php 
class RestaurantWarehouseReport extends Module
{
	public static $item = array();
	function RestaurantWarehouseReport($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY)){
			require_once 'forms/options.php';
			$this->add_form(new RestaurantWarehouseReportOptionsForm());
		}else{
			Url::access_denied();
		}
	}	
}
?>