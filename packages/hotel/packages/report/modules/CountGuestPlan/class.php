<?php
class CountGuestPlan extends Module
{
    function CountGuestPlan($row)
    {
        Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
            require_once 'forms/report.php';
            $this->add_form(new CountGuestPlanForm());
        }
        else
        {
            Url::access_denied();
        }
    }
    
}

?>