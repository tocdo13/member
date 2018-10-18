<?php
class TestMenu extends Module
{
	function TestMenu($row)
	{
		Module::Module($row);
		require_once 'forms/list.php';
		require_once 'db.php';
		$this->add_form(new TestMenuForm());
	}
}
?>
