<?php 
class ViewTravellerFolio extends Module{
	function ViewTravellerFolio($row){
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY)){
		   switch(Url::get('cmd'))
		    {
			   case 'group_invoice':
			     require 'forms/group_invoice.php';  
				 $this->add_form(new ViewGroupInvoiceForm());
				 break;
			   default:
				require_once 'forms/invoice.php';
			    $this->add_form(new ViewTravellerFolioForm());
				break;
			}
		}
	}
}
?>