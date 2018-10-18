<?php 
class MinibarImport extends Module
{
	function MinibarImport($row)
	{
		Module::Module($row);

		if(User::can_edit(false,ANY_CATEGORY))
		{
			require_once 'forms/edit.php';
			$this->add_form(new EditMinibarImportForm());
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>