<?php 
class MiceLocationSetup extends Module
{
	function MiceLocationSetup($row)
	{
		Module::Module($row);
		require_once 'forms/edit.php';
		$this->add_form(new MiceLocationSetupForm());
	}
}
?>