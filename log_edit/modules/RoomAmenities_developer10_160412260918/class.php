<?php 
class RoomAmenities extends Module
{
	function RoomAmenities($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			if(Url::get('cmd')=='report')
			{
                require_once 'forms/report.php';
				$this->add_form(new RoomAmenitiesReportForm());
			}
			else
			{
				require_once 'forms/edit.php';
				$this->add_form(new EditRoomAmenitiesForm());
			}
		}
		else
		{
			Url::access_denied();
		}
	}
}
?>