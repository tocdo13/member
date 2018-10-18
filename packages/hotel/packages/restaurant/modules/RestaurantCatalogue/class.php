<?php 
class RestaurantCatalogue extends Module
{
	function RestaurantCatalogue($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY)){
			require_once 'db.php';
			require_once 'forms/list.php';
			$this->add_form(new ListRestaurantCatalogueForm());
		}else{
			Url::access_denied();
		}
	}
}
?>