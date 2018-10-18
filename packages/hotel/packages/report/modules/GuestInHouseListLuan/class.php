<?php
/******************************
COPY RIGHT BY NYN PORTAL - TCV
WRITTEN BY LuanAD
EDITED BY DAIBT
******************************/
class GuestInHouseListLuan extends Module
{
    function GuestInHouseListLuan($row)
    {
        Module::Module($row);
    	if(User::can_view(false,ANY_CATEGORY))
		{
		  require_once('forms/report.php');
          $this->add_form(new ReportGuestInHouse);
		}
		else
		{
			URL::access_denied();
		}
    }
}
?>