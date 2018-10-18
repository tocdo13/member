<?php
class Chat extends Module
{
	function Chat($row)
	{
		Module::Module($row);
		require_once 'db.php';
		require_once 'forms/list.php';
		$this->add_form(new ChatForm());
	}
}
?>
