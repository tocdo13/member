<?php 
class CreditCard extends Module
{
	function CreditCard($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			require_once 'forms/edit.php';
			$this->add_form(new EditCreditCardForm());
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>