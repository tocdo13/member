<?php 
class HotelLinkDashBoard extends Module
{
	function HotelLinkDashBoard($row)
	{
		Module::Module($row);
        if(User::is_admin()){
      		require_once 'forms/edit.php';
		    $this->add_form(new HotelLinkDashBoardForm());
        }
	}
}
?>