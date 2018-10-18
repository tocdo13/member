<?php
class VatRevenueReportNotYet extends Module
{
    function VatRevenueReportNotYet($row) {
        Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY)) {
			require_once 'forms/report.php';
			$this->add_form(new VatRevenueReportNotYetForm());
		}
        else
			Url::access_denied();
    }
}
?>