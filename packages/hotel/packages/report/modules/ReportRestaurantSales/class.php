<?php 
class ReportRestaurantSales extends Module
{
	function ReportRestaurantSales($row){
		   Module::Module($row);
			if(User::can_view(false,ANY_CATEGORY)){
				require_once 'forms/report.php';
				$this->add_form(new ReportRestaurantSalesForm());
			}else{
				URL::access_denied();
			}
	}
}
?>