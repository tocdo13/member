<?php 
/******************************
COPY RIGHT BY NYN PORTAL - TCV
WRITTEN BY thedeath
EDITED BY KHOAND
******************************/
class CombineTemporarilyAbsent extends Module
{
	function CombineTemporarilyAbsent($row)
	{
		Module::Module($row);
		if(1==1)
		{
			require_once 'db.php';			
			require_once 'forms/list.php';
			$this->add_form(new CombineTemporarilyAbsentForm());			
		}
		else
		{
			Url::access_denied();
		}	
	}
}
?>