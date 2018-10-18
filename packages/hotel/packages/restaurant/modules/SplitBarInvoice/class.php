<?php 
class SplitBarInvoice extends Module
{
	function SplitBarInvoice($row)
	{
		Module::Module($row);
		if(Url::get('bar_id') && Url::get('bar_id') !=0){
			Session::set('bar_id',intval(Url::get('bar_id')));	
		}
		switch(URL::get('cmd'))
				{
			case 'create_folio':
				if(Url::get('rr_id'))
				{
					require_once 'forms/split.php';
					$this->add_form(new CreateTravellerFolioForm());break;	
				}
			default:
				require_once 'forms/split.php';
				$this->add_form(new SplitBarInvoiceForm());break;	
		}
	}
}
?>