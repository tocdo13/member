<?php
class VatInvoice extends Module
{
    function VatInvoice($row)
    {
        Module::Module($row);
        //$department = Url::get('department');

        switch(Url::get('cmd'))
        {
            case 'entry':
                if(User::can_view(false,ANY_CATEGORY))
                {
					require_once 'forms/entry_reception.php';
					$this->add_form(new VatInvoiceEntryForm());
				}
                else
					Url::access_denied();
				break;
            case 'view':
                if(User::can_view(false,ANY_CATEGORY))
                {
					require_once 'forms/view_reception.php';
					$this->add_form(new VatInvoiceViewForm());
				}
                else
					Url::access_denied();
				break;
            case 'entry_restaurant':
                if(User::can_view(false,ANY_CATEGORY))
                {
					require_once 'forms/entry_restaurant.php';
					$this->add_form(new VatInvoiceEntryForm());
				}
                else
					Url::access_denied();
				break;
            case 'view_restaurant':
                if(User::can_view(false,ANY_CATEGORY))
                {
					require_once 'forms/view_restaurant.php';
					$this->add_form(new VatInvoiceViewForm());
				}
                else
					Url::access_denied();
				break;
            case 'delete':
                if(User::can_delete(false,ANY_CATEGORY))
					$this->delete();
                else
					Url::access_denied();
				break;
            default:
            	if(User::can_view(false,ANY_CATEGORY))
                {
					require_once 'forms/list.php';
					$this->add_form(new VatInvoiceListForm());
				}
                else
					Url::access_denied();
				break;
        }
    }
    
    function delete()
    {
        if( $id = Url::iget('id') and $items = DB::exists_id( 'vat_invoice', $id ) )
        {
            DB::delete_id( 'vat_invoice', $id );
            Url::redirect_current();
        }
    }
}
?>