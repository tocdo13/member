<?php
/******************************
COPY RIGHT BY NYN PORTAL - TCV
WRITTEN BY LuanAD
EDITED BY DAIBT
******************************/
class HKInhouseGuestList extends Module
{
    function HKInhouseGuestList($row)
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