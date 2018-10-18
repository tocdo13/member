<?php
class CustomerBySaler extends Module
{
    function CustomerBySaler($row)
    {
        Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
            require_once 'forms/report.php';
            $this->add_form(new CustomerSaler());
        }
        else
        {
            Url::access_denied();
        }
    }
    
}

?>