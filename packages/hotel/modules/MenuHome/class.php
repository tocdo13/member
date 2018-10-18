<?php
class MenuHome extends Module
{
	function MenuHome($row)
	{
		Module::Module($row);
		if(User::is_login()){
			require_once 'forms/list.php';
			require_once 'db.php';
			$this->add_form(new MenuHomeForm());
		}else{
			Url::redirect('sign_in');
		}
	}
}
?>
