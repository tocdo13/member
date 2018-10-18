<?php 
class GoodsImport extends Module
{
	function GoodsImport($row)
	{
		Module::Module($row);

		if(User::can_edit(false,ANY_CATEGORY))
		{
			require_once 'forms/edit.php';
			$this->add_form(new EditGoodsImportForm());
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>