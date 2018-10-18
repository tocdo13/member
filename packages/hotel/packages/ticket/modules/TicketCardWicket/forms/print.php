<?php
class PrintTicketForm extends Form
{
    function PrintTicketForm()
    {
        Form::Form('PrintTicketForm');
    }
    
    function on_submit()
    {
        
    }
    function draw()
    {
        $this->map = array();
        $id = Url::get('id');
        $this->map['export'] = 0;
        if(Url::get('type') && Url::get('type')=='export')
        {
           $this->map['export'] = 1;   
        }
        $sql = "SELECT 
                    *
                FROM
                    ticket_card_wicket
                WHERE id = ".$id."         
                ";
        $ticket_card_wicket = DB::fetch($sql);
        $bill_no = $ticket_card_wicket['id'];
        $bill_no = "#".date("Y")."-".str_pad($bill_no,7,"0",STR_PAD_LEFT);
        $this->map['bill_no'] = $bill_no;
        
        $sql = "SELECT id, UPPER(full_name) as name FROM party WHERE user_id='".User::id()."'";
        $user_name = DB::fetch($sql);
        $user_name = $user_name['name'];
        $this->map['user_name'] = $user_name;
        
        $this->map['ticket_card_wicket'] = $ticket_card_wicket;
        $sql = "SELECT 
                    ticket_card_wicket_detail.id,
                    ticket_card_wicket_detail.quantity,
                    ticket_card_wicket_detail.price,
                    ticket_card_wicket_detail.discount_percent,
                    ticket_card_wicket_detail.total,
                    ticket_card_types.name 
                  FROM ticket_card_wicket_detail 
                       INNER JOIN ticket_card_types ON ticket_card_wicket_detail.ticket_card_types_id = ticket_card_types.id
                    WHERE ticket_card_wicket_detail.ticket_card_wicket_id = ".$id;
        $ticket_card_wicket_detail = DB::fetch_all($sql);            
        $this->map['ticket_card_wicket_detail'] = $ticket_card_wicket_detail;
        
        $sql = "SELECT payment.id, payment.amount, payment_type.name_".Portal::language()." as payment_name
                    FROM payment 
                    INNER JOIN payment_type ON payment.payment_type_id = payment_type.def_code
                    WHERE payment.type='TICKET_CARD' AND payment.bill_id='".$id."'";
        $payment_info = DB::fetch_all($sql);
        foreach($payment_info as $key=>$value){
            $payment_info[$key]['amount'] = System::display_number($value['amount']);
        }
        $this->map['link_logo'] = HOTEL_LOGO;

        $this->map['payment_info'] = $payment_info;
        
        $this->parse_layout('print',$this->map);
    }    
}


?>