<?php 
class PurchasesDetail extends Module
{
	function PurchasesDetail($row)
	{
		Module::Module($row);
		switch (Url::get('cmd')){
			case 'edit':
				if(User::can_edit(false,ANY_CATEGORY) AND Url::get('id') AND DB::exists('SELECT id from purchases_proposed where id='.Url::get('id'))){
                    require_once 'forms/edit.php';
					$this->add_form(new EditPurchasesDetailForm());
				}else{
					Url::access_denied();
				}
				break;
            case 'invoice':
				if(User::can_add(false,ANY_CATEGORY)){
                    require_once 'forms/invoice.php';
					$this->add_form(new InvoicePurchasesDetailForm());
				}else{
					Url::access_denied();
				}
				break;
			default:
				if(User::can_view(false,ANY_CATEGORY)){
					require_once 'forms/list.php';
					$this->add_form(new ListPurchasesDetailForm());
				}else{
					Url::access_denied();
				}
				break;
		}
	}	
}
?>