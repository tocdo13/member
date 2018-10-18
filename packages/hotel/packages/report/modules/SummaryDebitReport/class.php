<?php 
class SummaryDebitReport extends Module
{
	function SummaryDebitReport($row)
	{
		Module::Module($row);
        
        switch (Url::get('cmd')){
            case 'detail':
                if(User::can_view(false,ANY_CATEGORY))
                {
                    require_once 'forms/report_detail.php';
				    $this->add_form(new SummaryDebitReportDetailForm());
                }
                else
                    URL::access_denied();
                break;
            default:
                if(User::can_view(false,ANY_CATEGORY))
                {
                    require_once 'forms/report.php';
				    $this->add_form(new SummaryDebitReportForm());
                }
                else
                    URL::access_denied();
                break;
        }		
	}
}
?>