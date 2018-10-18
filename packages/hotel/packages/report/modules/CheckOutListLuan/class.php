<?php
/******************************
COPY RIGHT BY NYN PORTAL - TCV
WRITTEN BY LuanAD
EDITED BY DAIBT
******************************/
class CheckoutListLuan extends Module
{
    function CheckoutListLuan($row)
    {
        Module::Module($row);
    	if(User::can_view(false,ANY_CATEGORY))
		{
		  require_once('forms/report.php');
          $this->add_form(new ReportCheckoutList);
		}
		else
		{
			URL::access_denied();
		}
    }
}
?>