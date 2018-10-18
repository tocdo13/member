<?php 
/******************************
COPY RIGHT BY NYN PORTAL - TCV
WRITTEN BY thedeath
EDITED BY KHOAND
******************************/
class TemporarilyAbsent extends Module
{
	function TemporarilyAbsent($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			require_once 'db.php';			
			require_once 'forms/list.php';
			$this->add_form(new TemporarilyAbsentForm());			
		}
		else
		{
			Url::access_denied();
		}	
	}
}
?>