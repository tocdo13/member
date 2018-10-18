<?php
 
class RestaurantBarOrderApi extends Module
{
	function RestaurantBarOrderApi($row)
	{
        Module::Module($row);
		require_once 'packages/core/includes/system/restful_api.php';
        require_once 'packages/hotel/packages/app/modules/RestaurantBarOrderApi/api.php';
	}
}
?>