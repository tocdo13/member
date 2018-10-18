<?php
 
class ReportApi extends Module
{
	function ReportApi($row)
	{
        Module::Module($row);
		require_once 'packages/core/includes/system/restful_api.php';
        require_once 'packages/hotel/packages/app_android/modules/ReportApi/hotel_manager_report.php';
	}
}
?>