<?php 
class TicketCardSales extends Module
{
	function TicketCardSales($row)
	{
		Module::Module($row);
		if(User::can_edit(false,ANY_CATEGORY)){
			require_once 'forms/edit.php';
			$this->add_form(new TicketCardSalesForm());
		}else{
			URL::access_denied();
		}
	}
}
?>