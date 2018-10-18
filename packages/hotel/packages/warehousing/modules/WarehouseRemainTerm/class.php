<?php 
class WarehouseRemainTerm extends Module
{
	public static $item = array();
	function WarehouseRemainTerm($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY)){
			require_once 'forms/options.php';
			$this->add_form(new WarehouseRemainTermForm());
		}else{
			Url::access_denied();
		}
	}	
}
?>