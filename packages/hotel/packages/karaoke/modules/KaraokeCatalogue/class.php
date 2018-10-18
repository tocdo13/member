<?php 
class KaraokeCatalogue extends Module
{
	function KaraokeCatalogue($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY)){
			require_once 'db.php';
			require_once 'forms/list.php';
			$this->add_form(new ListKaraokeCatalogueForm());
		}else{
			Url::access_denied();
		}
	}
}
?>