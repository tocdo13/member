<?php 
class GolfCaddie extends Module
{
	function GolfCaddie($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			switch(URL::get('cmd'))
			{
			case 'edit':
                require_once 'forms/add.php';
				$this->add_form(new AddGolfCaddieForm());break;
			case 'add':
				require_once 'forms/add.php';
				$this->add_form(new AddGolfCaddieForm());break;
			default: 
				require_once 'forms/list.php';
				$this->add_form(new ListGolfCaddieForm());
				break;
			}
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>