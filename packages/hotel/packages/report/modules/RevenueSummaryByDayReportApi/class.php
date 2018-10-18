<?php 
class RevenueSummaryByDayReportApi extends Module
{
	function RevenueSummaryByDayReportApi ($row)
    {
        Module::Module($row);
        require_once 'packages/core/includes/system/restful_api.php';
        require_once 'packages/hotel/packages/report/modules/RevenueSummaryByDayReportApi/api.php';
	}
}
?>