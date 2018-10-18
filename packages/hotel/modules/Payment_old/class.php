<?php 
class Payment extends Module{
	function Payment($row){
		Module::Module($row);
		if(Url::get('id') and Url::get('type')){
			require_once('cache/config/payment.php');
			if(User::can_view(false,ANY_CATEGORY)){
				require_once 'forms/edit.php';
				$this->add_form(new EditPaymentForm());
			}else{
				URL::redirect('room_map');
			}
		}else{
			URL::redirect('room_map');
		}
	}
}
?>