<?php
class Setting extends Module
{
	function Setting($row)
	{
		Module::Module($row);
		require_once 'db.php';
		if(User::can_edit(false,ANY_CATEGORY))
		{			
			require_once 'forms/list.php';
			$this->add_form(new SettingForm());
		}else
		{
			Url::redirect('sign_in');
		}
	}
}
?>
