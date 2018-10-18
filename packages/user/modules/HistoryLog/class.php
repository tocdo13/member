<?php 
class HistoryLog extends Module
{
	function HistoryLog($row)
	{
		Module::Module($row);
		if(Url::get('recode'))
		{
			require_once 'forms/list.php';
			$this->add_form(new ListHistoryLogForm());
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>