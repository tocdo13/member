<?php
class PrintInvoiceForm extends Form
{
	function PrintInvoiceForm()
	{
		Form::Form('PrintInvoiceForm');
		
	}
	function draw()
	{
	    //System::debug($_REQUEST);
        if(!Url::get('invoice_id'))
        {
            echo '<h1 style = "color: red;">'.Portal::language('is_not_saved').'</h1>';
            exit();
        }
        if(DB::fetch('select last_code from ticket_invoice where id = '.Url::get('invoice_id'),'last_code') != '')
        {
            echo '<h1 style = "color: red;">'.Portal::language('ticket_is_printed').'</h1>';
            exit();
        }
        //lay thong tin invoice vi chi co luu roi moi in duoc
        $invoice_info = DB::fetch('select * from ticket_invoice where id = '.Url::get('invoice_id'));
        //System::debug($invoice_info);
        //Kaitoukid đã cm dòng 25. có gì bỏ sau.
        $last_code = $this->get_last_code(Url::get('invoice_id'),$invoice_info['ticket_id']);
        //lay thong tin ve
        $sql = 'select * 
                from 
                    ticket
                where
                    id = '.$invoice_info['ticket_id'].'
                ';
        $ticket_info = DB::fetch($sql);
        //System::debug($ticket_info);
        $ticket_name = $ticket_info['name'];
        $ticket_form = $ticket_info['form'];
        $ticket_name_2 = $ticket_info['name_2'];
        $ticket_denoted = $ticket_info['denoted'];
        $quantity = $invoice_info['quantity'];
        
        $ticket_group_name = DB::fetch('select ticket_group.name from ticket inner join ticket_group on ticket.ticket_group_id = ticket_group.id where ticket.id = '.Url::get('ticket_id'),'name');
        $ticket_prefix = Url::sget('ticket_prefix');
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
                                    ticket_service_grant.ticket_id = '.$invoice_info['ticket_id'].' order by ticket_service.id ');
        //System::debug($service);
        $log_print = '';
        //$last_code = 0;
        for($i=1; $i<=$quantity; $i++)
        {
            $last_code++;
            //echo $last_code;
            $code_print = $this->get_ticket_code($last_code);
            $this->print_page($ticket_name,$ticket_group_name,$ticket_prefix,$ticket_price,$code_print,$service,$ticket_form,$ticket_denoted,$ticket_name_2,$i,$quantity);
            $log_print .= Portal::language('ticket_name').': '.$ticket_name.' Serie number: '.$code_print.'<br>';
            if($i==$quantity)
                DB::update_id( 'ticket_invoice',array( 'last_code'=> $code_print, ), Url::iget('invoice_id') );
            if($i==1)
                DB::update_id( 'ticket_invoice',array( 'start_code'=> $code_print, 'ticket_date' => Date_Time::convert_time_to_ora_date(time())), Url::iget('invoice_id') );    
        }
        $title = 'Print ticket , Code: '.$invoice_info['ticket_reservation_id'];
		$description = ''
		.Portal::language('print_time').':'.date('d/m/Y H:i:s').'<br>  ' 
		.'<hr>'.$log_print.'';
		System::log('print_ticket',$title,$description,$invoice_info['ticket_reservation_id']); 
		//$this->parse_layout('print');
	}
    function print_page($ticket_name,$ticket_group_name,$ticket_prefix,$ticket_price,$code_print,$service,$ticket_form,$ticket_denoted,$ticket_name_2,$i,$quantity)
	{
		$this->parse_layout('print',array(
				'ticket_name'=>$ticket_name,
                'ticket_name_2'=>$ticket_name_2,
                'ticket_group_name'=>$ticket_group_name,
                'ticket_prefix'=>$ticket_prefix,
                'form'=>$ticket_form,
                'denoted'=>$ticket_denoted,
                'ticket_price'=>$ticket_price,
                'code_print'=>$code_print,
                'service'=>$service,
                'quantity'=>$quantity,
                'index'=>$i,
                'date'=>date('d/m/Y'),
                'is_entrance'=>(count($service)==1?1:0)
			)
		);   
	}
    //lay ma cuoi cung duoc in
    function get_last_code($id,$ticket_id)
	{
        //if($lastest_item = DB::fetch('SELECT id,last_code, to_char(date_used,\'YYYY\') as year FROM ticket_invoice where id != '.$id.' and ticket_id = '.$ticket_id.' and last_code is not null and portal_id = \''.PORTAL_ID.'\' ORDER BY id DESC, last_code DESC'))
//        {
//            //System::debug($lastest_item);
//            if($lastest_item['last_code'])
//            {
//                if($lastest_item['year']==date('Y'))
//                    $code = $lastest_item['last_code'];
//                else
//                    $code = 0;
//            }
//            else
//                $code = 0;
//        }
//        else
//            $code = 0;
//        return $code;
        $code = 0;
        $lastest_item = DB::fetch_all(' select *
                                        from 
                                        (
                                            SELECT 
                                                id,last_code, 
                                                to_char(date_used,\'YYYY\') as year 
                                            FROM ticket_invoice 
                                            where id != '.$id.' 
                                                and ticket_id = '.$ticket_id.' 
                                                and last_code is not null 
                                                and portal_id = \''.PORTAL_ID.'\' 
                                            ORDER BY id DESC ,last_code
                                        ) 
                                        where rownum <= 50');
        if(isset($lastest_item))
        {
            foreach($lastest_item as $key=>$value)
            {
               if($value['year']==date('Y') && $code < $value['last_code'])
                    $code = $value['last_code'];
            }
        }
        else
            $code = 0;
        return $code;
	}
    //tao so serie
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
}
?>