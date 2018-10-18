<?php 
class SplitTable extends Module
{
	function SplitTable($row)
	{
		Module::Module($row);
		require_once 'db.php';
		if(User::can_view(false,ANY_CATEGORY))
		{
			if(!Session::get('bar_id')){
					Session::set('bar_id',1);
				}
			switch(Url::get('type'))
			{
				case 'split':
					require_once 'forms/split.php';
					$this->add_form(new SplitTableForm());
					break;
				default:
					require_once 'forms/list.php';
					$this->add_form(new SplitTableForm());
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