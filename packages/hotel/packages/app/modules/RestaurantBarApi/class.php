<?php
 
class RestaurantBarApi extends Module
{
	function RestaurantBarApi($row)
	{
        Module::Module($row);
		require_once 'packages/core/includes/system/restful_api.php';
        require_once 'packages/hotel/packages/app/modules/RestaurantBarApi/get_restaurant_bar.php';
	}
}
?>