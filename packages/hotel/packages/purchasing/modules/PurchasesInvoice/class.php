<?php 
class PurchasesInvoice extends Module
{
	function PurchasesInvoice($row)
	{
		Module::Module($row);
		switch (Url::get('cmd')){
			case 'group_invoice':
				if(User::can_edit(false,ANY_CATEGORY) AND Url::get('id') AND DB::exists("select * from purchases_group_invoice Where id=".Url::get('id'))){
                    require_once 'forms/group_invoice.php';
					$this->add_form(new GroupInvoicePurchasesInvoiceForm());
				}else{
					Url::access_denied();
				}
				break;
            case 'invoice':
				if(User::can_edit(false,ANY_CATEGORY) AND Url::get('id') AND DB::exists("select * from purchases_invoice Where id=".Url::get('id'))){
                    require_once 'forms/invoice.php';
					$this->add_form(new InvoicePurchasesInvoiceForm());
				}else{
					Url::access_denied();
				}
				break;
            case 'detail':
				if(User::can_view(false,ANY_CATEGORY) AND (Url::get('invoice')=='invoice') AND Url::get('id') AND DB::exists("select * from purchases_invoice Where id=".Url::get('id')." AND status='CONFIRM'")){
                    require_once 'forms/detail_invoice.php';
					$this->add_form(new DetailInvoicePurchasesInvoiceForm());
				}
                elseif(User::can_view(false,ANY_CATEGORY) AND (Url::get('invoice')=='group_invoice') AND Url::get('id') AND DB::exists("select * from purchases_group_invoice Where id=".Url::get('id')." AND status='CONFIRM'"))
                {
                    require_once 'forms/detail_group_invoice.php';
					$this->add_form(new DetailGroupInvoicePurchasesInvoiceForm());
                }
                else{
					Url::access_denied();
				}
				break;
			default:
				if(User::can_view(false,ANY_CATEGORY)){
					require_once 'forms/list.php';
					$this->add_form(new ListPurchasesInvoiceForm());
				}else{
					Url::access_denied();
				}
				break;
		}
	}	
}
?>