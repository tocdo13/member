<?php 
class BarTable extends Module
{
	function BarTable($row)
	{
		Module::Module($row);
		if(User::can_edit(false,ANY_CATEGORY))
		{
			require_once 'forms/edit.php';
			$this->add_form(new BarTableForm());
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>