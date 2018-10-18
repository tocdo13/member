<?php
/******************************
COPY RIGHT BY NYN PORTAL - TCV
WRITTEN BY LuanAD
EDITED BY DAIBT
******************************/
class RoomChangeList extends Module
{
    function RoomChangeList($row)
    {
        Module::Module($row);
    	if(User::can_view(false,ANY_CATEGORY))
		{
		  require_once('forms/report.php');
          $this->add_form(new ReportRoomChangeList);
		}
		else
		{
			URL::access_denied();
		}
    }
}
?>