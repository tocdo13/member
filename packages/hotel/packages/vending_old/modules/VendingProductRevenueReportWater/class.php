<?php 
class VendingProductRevenueReportWater extends Module
{
	function VendingProductRevenueReportWater($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
    		require_once 'forms/report.php';
            $this->add_form(new RestaurantRevenueReportForm());
		}
		else
		{
		  URL::access_denied();
		}
	}
}
?>