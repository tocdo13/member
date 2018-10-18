<?php
class ReportEmailMarketing extends Module
{
    function ReportEmailMarketing($row)
    {
        Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
            
            require_once('forms/report.php');
            $this -> add_form(new ReportEmailMarketingForm());
        }
            
    }
}
?>