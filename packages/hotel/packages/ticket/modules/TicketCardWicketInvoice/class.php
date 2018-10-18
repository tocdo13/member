<?php 
class TicketCardWicketInvoice extends Module
{
	function TicketCardWicketInvoice($row)
	{
		Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
                switch(Url::get('cmd')){
                        case "view":
                                    if(Url::get('id')){
                                        require_once 'forms/view.php';
                                        $this->add_form(new TicketCardWicketInvoiceViewForm());         
                                    }
                                    else{
                                        Url::redirect_current();
                                    }
                                    break;
                        case "delete":
                                   if(Url::get('id')){
                                        $ticket_card_wicket_invoice = Url::get('id');
                                        DB::delete("payment"," bill_id=".$ticket_card_wicket_invoice." AND type='TICKET_CARD'");
                                        DB::delete("ticket_card_wicket_detail"," ticket_card_wicket_id=".$ticket_card_wicket_invoice);
                                        DB::delete("ticket_card_wicket"," id=".$ticket_card_wicket_invoice);         
                                    }
                                    Url::redirect_current();
                                    break;             
                        default:
                                    require_once 'forms/list.php';
                                    $this->add_form(new TicketCardWicketInvoiceForm());         
                                    break;
                 }              
        }
	}	
}
?>