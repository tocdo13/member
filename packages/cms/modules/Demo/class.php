<?php
class Demo extends Module
{
	function Demo($row)
	{
		Module::Module($row);
		require_once 'forms/list.php';
		require_once 'db.php';
		$this->add_form(new DemoForm());
	}
}
?>
