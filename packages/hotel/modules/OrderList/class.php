<?php 
class OrderList extends Module{
	function OrderList($row){
		Module::Module($row);		
		if(User::can_view(Form::get_module_id('NightAudit'),ANY_CATEGORY)){
			require_once 'forms/list.php';
			$this->add_form(new OrderListForm());
		}else{
			URL::access_denied();
		}
	}
}
?>