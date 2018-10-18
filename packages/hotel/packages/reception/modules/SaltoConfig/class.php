<?php 
class SaltoConfig extends Module
{
	function SaltoConfig($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			require_once 'forms/list.php';
			$this->add_form(new SaltoConfigForm());
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>