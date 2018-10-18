<?php
class ReportEmail extends Module
{
    function ReportEmail($row)
    {
        Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
            
            if(Url::get('cmd')=="send_mail_invoice")
            {
                require_once 'forms/send_mail_invoice_resent.php';
                send_mail_invoice_resent();
            }
            
            require_once 'forms/report.php';
            $this->add_form(new ReportEmailForm());
            
        }
        else
        {
            URL::access_denied();
        }    
    }
}
?>