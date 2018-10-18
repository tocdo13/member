<?php 
class TicketInvoice extends Module
{
	function TicketInvoice($row)
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
                    $this->add_form(new AddTicketInvoiceForm());break;
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
        if( $id = Url::iget('id') and $items = DB::exists_id( 'ticket_invoice', $id ) )
        {
            DB::delete_id( 'ticket_invoice', $id );
            DB::delete('ticket_invoice_detail',' ticket_invoice_id = '.$id);
            Url::redirect_current( array('ticket_area_id','ticket_id','time_start','time_end') );
        }
    }
}
?>