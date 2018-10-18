<?php 
class KaraokeTable extends Module
{
	function KaraokeTable($row)
	{
		Module::Module($row);
		if(User::can_edit(false,ANY_CATEGORY))
		{
			require_once 'forms/edit.php';
			$this->add_form(new KaraokeTableForm());
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>