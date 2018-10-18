<?php
class SynExcel extends Module
{
	function SynExcel($row)
	{
		Module::Module($row);
		require_once 'db.php';
		if(User::can_admin(false,ANY_CATEGORY))
		{
			require_once 'forms/list.php';
			$this->add_form(new SynExcelForm());		
		}
		else
		{
			Url::access_denied();
		}	
	}
}
?>
