<?php 
class VendingStartTermDebit extends Module
{
	function VendingStartTermDebit($row)
	{
		Module::Module($row);
        if(User::can_edit(false,ANY_CATEGORY))
        {
			require_once 'forms/edit.php';
			$this->add_form(new WhStartTermDebitForm());
		}
        else
        {
			URL::access_denied();
		}
	}	
}
?>