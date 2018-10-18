<?php
class PrintInvoiceForm extends Form
{
	function PrintInvoiceForm()
	{
		Form::Form('PrintInvoiceForm');
		
	}
	function draw()
	{
	    if(User::is_admin())
        {
            //System::debug($_REQUEST);
            //echo Url::sget('form').'aaaaa'.Url::sget('denoted');
        }
        $ticket_name = Url::sget('ticket_name');
        $ticket_name_2 = Url::sget('ticket_name_2');
        $quantity = Url::iget('quantity');
        $ticket_group_name = Url::sget('ticket_group_name');
        $ticket_prefix = Url::sget('ticket_prefix');
        $ticket_price = Url::sget('ticket_price');
        $last_code = Url::get('last_code');
        $ticket_denoted = Url::sget('denoted');
        $ticket_form = Url::sget('form');
        $ticket_price = Url::sget('ticket_price');
        
        $service = DB::fetch_all('Select 
                                    ticket_service.*,
                                    ticket_service.price as price_before_discount,
                                    (ticket_service.price - ticket_service_grant.discount_money) - (ticket_service.price - ticket_service_grant.discount_money) * ticket_service_grant.discount_percent/100 as price,
                                    ticket_service_grant.discount_money,
                                    ticket_service_grant.discount_percent 
                                from 
                                    ticket_service_grant inner join ticket_service on ticket_service.id = ticket_service_grant.ticket_service_id  
                                where
                                    ticket_service_grant.ticket_id = '.Url::get('ticket_id').' order by ticket_service.id ');
        // and ticket_service.id != 5 
        //System::debug($service);
        
        for($i=1; $i<=$quantity; $i++)
        {
            $last_code++;
            $code_print = $this->get_ticket_code($last_code);
            $this->print_page($ticket_name,$ticket_group_name,$ticket_prefix,$ticket_price,$code_print,$service,$ticket_form,$ticket_denoted,$ticket_name_2,$i,$quantity);
            if($i==$quantity)
                DB::update_id( 'ticket_invoice',array( 'last_code'=> $code_print ), Url::iget('invoice_id') );    
        } 
		//$this->parse_layout('print');
	}
    
    function get_ticket_code($ticket_code)
    {
		$code = '';
		$leng = strlen($ticket_code);
		for($j=0;$j<7-$leng;$j++)
        {
			$code .= '0';	
		}
		$code = $code.$ticket_code;
		return $code;
	}
    
    function print_page($ticket_name,$ticket_group_name,$ticket_prefix,$ticket_price,$code_print,$service,$ticket_form,$ticket_denoted,$ticket_name_2,$i,$quantity)
	{
	    //echo $ticket_form;
		$this->parse_layout('print',array(
				'ticket_name'=>$ticket_name,
                'ticket_name_2'=>$ticket_name_2,
                'ticket_group_name'=>$ticket_group_name,
                'ticket_prefix'=>$ticket_prefix,
                'form'=>$ticket_form,
                'denoted'=>$ticket_denoted,
                'ticket_price'=>$ticket_price,
                'quantity'=>$quantity,
                'index'=>$i,
                'code_print'=>$code_print,
                'service'=>$service,
                'date'=>date('d/m/Y'),
                'is_entrance'=>(count($service)==1?1:0)
			)
		);
        
	}
}
?>