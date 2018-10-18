<?php 
class TelephoneConfig extends Module
{
	function TelephoneConfig($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			require_once 'forms/list.php';
			$this->add_form(new ListTelephoneConfigForm());
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>