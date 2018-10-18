<?php
class GuestReportByProvince extends Module
{
    function GuestReportByProvince($row)
    {
        Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
            require_once 'forms/report.php';
            $this->add_form(new GuestReportByProvinceForm());
        }
        else
        {
            Url::access_denied();
        }
    }
    
}

?>