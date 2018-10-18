<?php
class Personal extends Module
{
	function Personal($row)
	{
		Module::Module($row);
		if(User::is_login())
		{
			switch( URL::get('cmd'))
			{
				case 'change_pass':
					require_once 'forms/change_pass.php';
					$this->add_form(new ChangePassForm);
					break;
				default:
					require_once 'forms/information.php';
					$this->add_form(new PersonalInformationForm);
					break;
			}	
		}	
		else
		{
			Url::redirect('sign_in');
		}
	}
}
?>
