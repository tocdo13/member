<?php 
class Bank extends Module
{
	function Bank($row)
	{
		Module::Module($row);
		require_once 'packages/hotel/includes/php/hotel.php';
		require_once 'forms/edit.php';
		$this->add_form(new BankForm());
	}
}
?>