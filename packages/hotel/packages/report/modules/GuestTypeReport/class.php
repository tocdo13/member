<?php
class GuestTypeReport extends Module
{
    function GuestTypeReport($row)
    {
        Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
            require_once 'forms/report.php';
            $this->add_form(new GuestTypeReportForm());
        }
        else
        {
            Url::access_denied();
        }
    }
    
}

?>