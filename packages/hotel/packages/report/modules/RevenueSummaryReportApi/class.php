<?php 
class RevenueSummaryReportApi extends Module
{
	function RevenueSummaryReportApi ($row)
    {
        Module::Module($row);
        require_once 'packages/core/includes/system/restful_api.php';
        require_once 'packages/hotel/packages/report/modules/RevenueSummaryReportApi/api.php';
	}
}
?>