<?php 
class ShiftFinishing extends Module
{
	function ShiftFinishing($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			require_once 'forms/list.php';
			$this->add_form(new EditShiftFinishingForm());
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>