<?php 
class CheckVoucher extends Module
{
	function CheckVoucher($row)
	{
		Module::Module($row);
		if(User::can_admin(false,ANY_CATEGORY)){
			require_once 'forms/edit.php';
			$this->add_form(new CheckVoucherForm());
		}else{
			URL::access_denied();
		}
	}
}
?>