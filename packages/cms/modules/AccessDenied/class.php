<?php
class AccessDenied extends Module
{
	function AccessDenied($row)
	{
		Module::Module($row);
		require_once 'db.php';
		require_once 'forms/list.php';
		$this->add_form(new AccessDeniedForm());
	}
}
?>
