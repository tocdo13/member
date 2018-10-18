<?php 
class VendingCategoryDiscount extends Module
{
	function VendingCategoryDiscount($row){
        Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY)){
        	require_once 'forms/list.php';
        	$this->add_form(new VendingCategoryDiscountForm());
        }else{
        	URL::access_denied();
        }
	}
}
?>