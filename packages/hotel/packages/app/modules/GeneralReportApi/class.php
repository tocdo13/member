<?php
 
class GeneralReportApi extends Module
{
	function GeneralReportApi($row)
	{
        Module::Module($row);
		require_once 'packages/core/includes/system/restful_api.php';
        require_once 'packages/hotel/packages/app/modules/GeneralReportApi/api.php';
	}
}
?>