<?php
class SettingEmail extends Module
{
	function SettingEmail($row)
	{
		Module::Module($row);
		if(User::can_edit(false,ANY_CATEGORY))
		{
			require_once 'forms/list.php';
			$this->add_form(new SettingEmailForm());
		}
        else
		{
			Url::redirect('sign_in');
		}
	}
}
?>
