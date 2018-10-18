<?php 
class InHouseGuestList extends Module
{
	function InHouseGuestList($row)
	{
		Module::Module($row);

		if(User::can_view(false,ANY_CATEGORY))
		{
			
			require_once 'forms/report.php';
			$this->add_form(new InHouseGuestListForm());
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>