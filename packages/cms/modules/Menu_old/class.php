<?php
class Menu extends Module
{
	function Menu($row)
	{
		Module::Module($row);
		require_once 'forms/list.php';
		require_once 'db.php';
		$this->add_form(new MenuForm());
		
		/*if(URL::get('reset'))
		{
			URL::redirect_current();
		}
		else
		{
			if(User::can_view())
			{
				require_once 'forms/list.php';
				require_once 'db.php';		
				$this->add_form(new MenuForm());
			}else
			{
				URL::access_denied();
			}
		}*/
	}
}
?>
