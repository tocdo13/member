<?php 
class SiteminderApi extends Module
{
	function SiteminderApi($row)
	{
		Module::Module($row);
        require_once 'packages/core/includes/system/restful_api.php';
        require_once 'packages/hotel/packages/siteminder/modules/SiteminderApi/api.php';
	}
}
?>