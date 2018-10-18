<?php
class VatRevenueReport extends Module
{
    function VatRevenueReport($row) {
        Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY)) {
			require_once 'forms/report.php';
			$this->add_form(new VatRevenueReportForm());
		}
        else
			Url::access_denied();
    }
}
?>