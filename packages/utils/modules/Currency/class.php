<?php 
class Currency extends Module
{
	function Currency($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			
			require_once 'forms/edit.php';
			$this->add_form(new EditCurrencyForm());
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>