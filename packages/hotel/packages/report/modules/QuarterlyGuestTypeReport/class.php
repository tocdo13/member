<?php
class QuarterlyGuestTypeReport extends Module
{
    function QuarterlyGuestTypeReport($row)
    {
        require_once 'db.php';
        Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
            require_once 'forms/report.php';
            $this->add_form(new QuarterlyGuestTypeReportForm());
        }
        else
        {
            Url::access_denied();
        }
    }
    
}

?>