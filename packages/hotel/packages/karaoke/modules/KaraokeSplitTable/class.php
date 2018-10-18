<?php 
class KaraokeSplitTable extends Module
{
	function KaraokeSplitTable($row)
	{
		Module::Module($row);
		require_once 'db.php';
		if(User::can_view(false,ANY_CATEGORY))
		{
			if(!Session::get('karaoke_id')){
					Session::set('karaoke_id',1);
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