<?php
class HomePanel extends Module
{
	function HomePanel($row)
	{
		Module::Module($row);
		require_once 'forms/list.php';
		require_once 'db.php';
		if(User::is_login())
		{
			if(!Url::get('category_id'))
			{
				$_REQUEST['category_id'] = 21; 
			}
			$this->add_form(new HomePanelForm());
		}else
		{
			Url::redirect('sign_in');
		}
	}
}
?>
