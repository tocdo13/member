<?php 
class RestaurantDiscountReport extends Module{
	function RestaurantDiscountReport($row){
		Module::Module($row);		
		{
			if(User::can_view(false,ANY_CATEGORY)){
				require_once 'forms/report.php';
				$this->add_form(new RestaurantDiscountReportForm());
			}else{
				URL::access_denied();
			}
		}
	}
}
?>