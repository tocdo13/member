<?php 
class VoucherBreakfast extends Module
{
	function VoucherBreakfast($row)
	{
		Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
			require_once 'forms/breakfast.php';
			$this->add_form(new VoucherBreakfastForm());
		}
        else
        {
			URL::access_denied();
		}
	}	
}
?>