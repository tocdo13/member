<?php 
class MiceDepartmentSetup extends Module
{
	function MiceDepartmentSetup($row)
	{
		Module::Module($row);
		require_once 'forms/edit.php';
		$this->add_form(new MiceDepartmentSetupForm());
	}
}
?>