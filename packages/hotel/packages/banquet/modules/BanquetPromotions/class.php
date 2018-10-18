<?php 
class BanquetPromotions extends Module
{
	function BanquetPromotions($row)
	{
		Module::Module($row);
        if(User::can_edit(false,ANY_CATEGORY)){
			require_once 'forms/list.php';
			$this->add_form(new BanquetPromotionsList());
		}else{
			URL::access_denied();
		}
	}	
}
?>