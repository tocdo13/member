<?php 
class GolfHole extends Module
{
	function GolfHole($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
        {
			require_once 'forms/edit.php';
			$this->add_form(new GolfHoleForm());
		}
        else
        {
			URL::access_denied();
		}
	}
}
?>