<?php 
class PaymentCashier extends Module
{
	function PaymentCashier($row){
		   Module::Module($row);
			if(User::can_view(false,ANY_CATEGORY)){
				require_once 'forms/list.php';
				$this->add_form(new PaymentCashierForm());
			}else{
				URL::access_denied();
			}
	}
}
?>