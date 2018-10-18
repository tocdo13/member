<?php
class ExpectRoomRevenueReport extends Module
{
    function ExpectRoomRevenueReport($row)
    {
        Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
            require_once 'forms/report.php';
            $this->add_form(new ExpectRoomRevenueReportForm());
        }
        else
        {
            Url::access_denied();
        }
    }
    
}

?>