<?php 
class TicketCardArea extends Module
{
	function TicketCardArea($row)
	{
		Module::Module($row);
		if(User::can_edit(false,ANY_CATEGORY)){
			require_once 'forms/edit.php';
			$this->add_form(new TicketCardAreaForm());
		}else{
			URL::access_denied();
		}
	}
}
?>