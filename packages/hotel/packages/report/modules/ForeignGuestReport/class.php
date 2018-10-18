<?php
class ForeignGuestReport extends Module
{
    function ForeignGuestReport($row)
    {
        Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
            require_once 'forms/report.php';
            $this->add_form(new ForeignGuestReportForm());
        }
        else
        {
            Url::access_denied();
        }
    }
    
}

?>