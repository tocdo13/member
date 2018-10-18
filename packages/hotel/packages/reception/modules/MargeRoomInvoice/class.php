<?php 
class MargeRoomInvoice extends Module
{
	function MargeRoomInvoice($row)
	{
		Module::Module($row);
		if(User::can_edit(false,ANY_CATEGORY))
		{
			if(!Url::get('act')){
				Url::redirect_current(array('act'=>'marge_invoice'));
			}
			require_once 'forms/edit.php';
			$this->add_form(new MargeRoomInvoiceForm());
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>