<?php
 
class HouseKeepingAPI extends Module
{
	function HouseKeepingAPI($row)
	{
        Module::Module($row);
        switch(Url::get('cmd'))
		{ 
            case 'laundry':
    			require_once 'packages/core/includes/system/restful_api.php';
                require_once 'packages/hotel/packages/app/modules/HouseKeepingAPI/laundry.php';
            break;
            case 'minibar':
    			require_once 'packages/core/includes/system/restful_api.php';
                require_once 'packages/hotel/packages/app/modules/HouseKeepingAPI/minibar.php';
            break;
            case 'equipment':
    			require_once 'packages/core/includes/system/restful_api.php';
                require_once 'packages/hotel/packages/app/modules/HouseKeepingAPI/equipment.php';
            break;
		}
		
	}
}
?>