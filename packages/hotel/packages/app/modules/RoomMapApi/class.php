<?php
 
class RoomMapApi extends Module
{
	function RoomMapApi($row)
	{
        Module::Module($row);
        switch(URL::get('cmd'))
		{ 
            case 'get_house_status':
    			require_once 'packages/core/includes/system/restful_api.php';
                require_once 'packages/hotel/packages/app/modules/RoomMapApi/get_house_status.php';
            break;
            case 'change_house_status':
    			require_once 'packages/core/includes/system/restful_api.php';
                require_once 'packages/hotel/packages/app/modules/RoomMapApi/change_house_status.php';
            break;
		}
		
	}
}
?>