<?php 
class MiceReservation extends Module
{
	function MiceReservation($row)
	{
		Module::Module($row);
        require_once 'db.php';
		if(User::can_view(false,ANY_CATEGORY))
        {
            switch (Url::get('cmd'))
            {
    			case 'add':
    				if(User::can_add(false,ANY_CATEGORY))
                    {
    					require_once 'forms/edit.php';
    					$this->add_form(new EditMiceReservationForm());
    				}
                    else
                    {
    					Url::access_denied();
    				}
    				break;
    			case 'edit':
    				if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and DB::exists('SELECT id from mice_reservation where id='.Url::get('id')))
                    {
                        $status = DB::fetch('SELECT status from mice_reservation where id='.Url::get('id'),'status');
                        if($status==1)
                        {
                            require_once 'forms/confirm.php';
    					    $this->add_form(new ConfirmMiceReservationForm());
                        }
                        else
                        {
                            require_once 'forms/edit.php';
    					    $this->add_form(new EditMiceReservationForm());
                        }
                        
    				}
                    else
                    {
    					Url::access_denied();
    				}
    				break;
                case 'beoform':
    				if(User::can_view(false,ANY_CATEGORY) and Url::get('id') and DB::exists('SELECT id from mice_reservation where id='.Url::get('id')))
                    {
                        require_once 'forms/beoform.php';
    					$this->add_form(new BeoFormMiceReservationForm());
    				}
                    else
                    {
    					Url::access_denied();
    				}
    				break;
                case 'cancel':
    				if(User::can_delete(false,ANY_CATEGORY) and Url::get('id') and DB::exists('SELECT id from mice_reservation where id='.Url::get('id')))
                    {
                        require_once 'forms/cancel.php';
    					$this->add_form(new CancelMiceReservationForm());
    				}
                    else
                    {
    					Url::access_denied();
    				}
    				break;
                case 'invoice':
    				if(User::can_view(false,ANY_CATEGORY) and Url::get('id') and DB::exists('SELECT id from mice_reservation where id='.Url::get('id')))
                    {
                        require_once 'forms/invoice.php';
    					$this->add_form(new InvoiceMiceReservationForm());
    				}
                    else
                    {
    					Url::access_denied();
    				}
    				break;
                case 'bill':
    				if(User::can_view(false,ANY_CATEGORY) and Url::get('invoice_id') and DB::exists('SELECT id from mice_invoice where id='.Url::get('invoice_id')))
                    {
                        require_once 'forms/bill.php';
    					$this->add_form(new BillMiceReservationForm());
    				}
                    else
                    {
    					Url::access_denied();
    				}
    				break;
                case 'bill_new':
    				if(User::can_view(false,ANY_CATEGORY) and Url::get('invoice_id') and DB::exists('SELECT id from mice_invoice where id='.Url::get('invoice_id')))
                    {
                        require_once 'forms/bill_new.php';
    					$this->add_form(new BillNewMiceReservationForm());
    				}
                    else
                    {
    					Url::access_denied();
    				}
    				break;
                case 'proforma_invoice':
    				if(User::can_view(false,ANY_CATEGORY) and Url::get('id') and DB::exists('SELECT id from mice_reservation where id='.Url::get('id')))
                    {
                        require_once 'forms/proforma_invoice.php';
    					$this->add_form(new ProformaInvoiceMiceReservationForm());
    				}
                    else
                    {
    					Url::access_denied();
    				}
    				break;
    			default:
    				if(User::can_view(false,ANY_CATEGORY))
                    {
    					require_once 'forms/list.php';
    					$this->add_form(new ListMiceReservationForm());
    				}
                    else
                    {
    					Url::access_denied();
    				}
    				break;
    		}
        }
        else
        {
            Url::access_denied();
        }
	}	
}
?>
