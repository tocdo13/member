<?php
class ReceiptMoney extends Module
{
    function ReceiptMoney($row)
    {
        Module::Module($row);
    	if(User::can_view(false,ANY_CATEGORY))
		{ 
            require_once('forms/report.php');
            $this->add_form(new ReceiptMoneyForm);
		}
		else
		{
			URL::access_denied();
		}
    }
}
?>