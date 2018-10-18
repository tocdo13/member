<?php 
class PurchaseHistory extends Module
{
    function PurchaseHistory($row)
    {
        Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
            require_once 'forms/report.php';
            $this->add_form(new PurchaseHistoryForm());
        }else
        {
            Url::access_denied();
        }
    }	
}
?>