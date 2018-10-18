<?php 
class KaraokeRevenueReportBy extends Module
{
	function KaraokeRevenueReportBy($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY) or User::can_view_detail(false,ANY_CATEGORY))
		{
            switch(Url::get('type_report'))
            {
                case 'invoice':
                {
                    require_once 'forms/report_invoice.php';
                    $this->add_form(new KaraokeRevenueReportByInvoiceForm());
                    break;
                }
                case 'date':
                {
                    require_once 'forms/report_date.php';
                    $this->add_form(new KaraokeRevenueReportByDateForm());
                    break;
                }
                default :
                {
                    require_once 'forms/report_invoice.php';
                    $this->add_form(new KaraokeRevenueReportByInvoiceForm());
                    break;
                }
            }
		}
		else
		{
            URL::access_denied();
		}
	}
}
?>