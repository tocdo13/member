<?php 
class TicketInvoiceGroup extends Module
{
	function TicketInvoiceGroup($row)
	{
		Module::Module($row);
        switch(Url::get('cmd'))
        {
            case 'delete':
                if(User::can_delete(false,ANY_CATEGORY))
                    $this->delete();
                else
                    Url::access_denied();
                break;
            case 'add':
                if(User::can_add(false,ANY_CATEGORY))
                {
                    require_once 'forms/add.php';
                    $this->add_form(new AddTicketInvoiceGroupForm());break;
                }
                else
                    Url::access_denied();
                break;
            case 'choose_area':
                if(User::can_add(false,ANY_CATEGORY))
                {
                    require_once 'forms/choose_area.php';
                    $this->add_form(new ChooseAreaForm());break;
                }
                else
                    Url::access_denied();
                break;
            case 'print':
                if(User::can_add(false,ANY_CATEGORY))
                {
                    require_once 'forms/print.php';
                    $this->add_form(new PrintInvoiceForm());break;
                }
                else
                    Url::access_denied();
                break;
            case 'edit':
                if(User::can_add(false,ANY_CATEGORY))
                {
                    require_once 'forms/edit.php';
                    $this->add_form(new EditTicketInvoiceGroupForm());break;
                } 
            case 'bill':
                if(User::can_add(false,ANY_CATEGORY))
                {
                    require_once 'forms/bill.php';
                    $this->add_form(new BillTicketInvoiceGroupForm());break;
                }   
            case 'cancel':
                if(User::can_add(false,ANY_CATEGORY))
                {
                    require_once 'forms/cancel.php';
                    $this->add_form(new CancelTicketForm());break;
                } 
            default:
                if(User::can_view(false,ANY_CATEGORY))
                {
                    require_once 'forms/list.php';
                    $this->add_form(new ListTicketInvoiceForm());
                }
                else
                    Url::access_denied();
                break;
        }
	}
    
 
    
    function delete()
    {
        if( $id = Url::iget('id') and $items = DB::exists_id( 'ticket_reservation', $id ) )
        {
            $ticket_invoice_id = DB::fetch_all('select ticket_invoice.id from ticket_invoice where ticket_reservation_id='.$id.'');
            foreach($ticket_invoice_id as $v)
            {
                DB::delete('ticket_invoice_detail',' ticket_invoice_id = '. $v['id']);
            }
            DB::delete_id( 'ticket_reservation', $id );
            DB::delete('ticket_invoice',' ticket_reservation_id = '.$id);
            DB::delete('TICKET_CANCELATION',' ticket_reservation_id = '.$id);
            DB::delete('PAYMENT',' BILL_ID = '.$id.' and type = \'TICKET\'');
            $log_mi_ticket = 'Code: '. $id. '<br>';
            $title = 'Delete ticket reservation';
    		$description = ''
    		.Portal::language('time').':'.date('d/m/Y H:i:s').'<br>  ' 
    		.'<hr>'.$log_mi_ticket.'';
    		System::log('delete',$title,$description,URL::get('id')); 
            //exit();
            Url::redirect_current( array() );
            
        }
    }
}
?>