<?php
class ViewTicketInvoiceForm extends Form
{
    //Bien nay luu lai ma~ ve dc in, de truyen sang layout => ko phai chon lai ve nua 
    static $ticket_id;
	function ViewTicketInvoiceForm()
	{
		Form::Form('ViewTicketInvoiceForm');
        $this->link_js('packages/core/includes/js/multi_items.js');
        //require_once 'packages/core/includes/utils/printer.php';
        //$printer = new Printer('TCV_PRINTER01',array());
        //Printer::draw_grid();
        //echo date('dmy - H\hi');
		
	}
	function on_submit()
	{
       if(Url::get('save'))
       {
            //System::debug($_REQUEST);
            //exit();
            if( isset($_REQUEST['mi_ticket']) )
            {
                //$printer_name = DB::fetch('Select printer_name from ticket_area where id ='.Url::get('ticket_area_id'),'printer_name');
                //require_once 'packages/core/includes/utils/printer.php';
                
                //System::debug($_REQUEST['mi_ticket']);
                foreach( $_REQUEST['mi_ticket'] as $key => $record )
                {
                    $record['quantity'] = System::calculate_number($record['quantity']);
                    if($record['quantity'] != 0)
                    {
                        $ticket_prefix = $record['prefix'];
                        $ticket_form = $record['form'];
                        $ticket_denoted = $record['denoted'];
                        $ticket_group_name = $record['ticket_group_name'];
                        $ticket_name = $record['name'];
                        $ticket_price = $record['price'];
                        
                        $record['ticket_id'] = $record['id'];
                        
                        unset($record['id']);
                        unset($record['received']);
                        unset($record['return']);
                        unset($record['prefix']);
                        unset($record['ticket_group_name']);
                        unset($record['name']);
                        unset($record['form']);
                        unset($record['denoted']);
                        
                        $record['price'] = System::calculate_number($record['price']);
                        $record['price_before_discount'] = System::calculate_number($record['price_before_discount']);
                        $record['total'] = System::calculate_number($record['total']);
                        $record['total_before_discount'] = System::calculate_number($record['quantity']) * $record['price_before_discount'];
                        $record['ticket_area_id'] = Url::get('ticket_area_id');
                        $record['time'] = time();
                        $record['date_used'] = Date_Time::to_orc_date(date('d/m/Y'));
                        $record['user_id'] = Session::get('user_id');
                        $record['portal_id'] = PORTAL_ID;
                        
                        $ticket_reservation_id = DB::insert('ticket_reservation',array('time'=>time()));
                        $record['ticket_reservation_id'] = $ticket_reservation_id;
                        $id = DB::insert('ticket_invoice',$record);
                        //System::debug($record);
                        AddTicketInvoiceForm::$ticket_id = $record['ticket_id'];
                        $service = DB::fetch_all('Select 
                                                    ticket_service.*,
                                                    ticket_service.price as price_before_discount,
                                                    (ticket_service.price - ticket_service_grant.discount_money) - (ticket_service.price - ticket_service_grant.discount_money) * ticket_service_grant.discount_percent/100 as price,
                                                    ticket_service_grant.discount_money,
                                                    ticket_service_grant.discount_percent 
                                                from 
                                                    ticket_service_grant inner join ticket_service on ticket_service.id = ticket_service_grant.ticket_service_id  
                                                where 
                                                    ticket_service_grant.ticket_id = '.$record['ticket_id'].' order by ticket_service.id ');
                        //System::debug($service);
                        //exit();
                        foreach($service as $key => $value)
                        {
                            $detail = array(
                                        'ticket_invoice_id'=>$id,
                                        'ticket_id'=>$record['ticket_id'],
                                        'ticket_service_id'=>$key,
                                        'ticket_service_name'=>$value['name_1'].'/'.$value['name_2'],
                                        'quantity'=>$record['quantity'],
                                        'price_before_discount'=>$value['price_before_discount'],
                                        'total_before_discount'=>$value['price_before_discount'] * $record['quantity'],
                                        'price'=>$value['price'],
                                        'total'=>$value['price'] * $record['quantity'],
                                        'discount_money'=> $value['discount_money'],
                                        'discount_percent'=> $value['discount_percent'],
                                            );
                            DB::insert('ticket_invoice_detail',$detail);
                        }
                        /*
                        if($printer_name)
                        {
                            $last_code = $this->get_last_code($id);
                            
                            if(extension_loaded ('printer'))
                            {
                                $printer = new Printer($printer_name,array());
                            }
                            for($i=1; $i<=$record['quantity']; $i++)
                            {
                                $last_code++;
                                $code_print = $this->get_ticket_code($last_code);
                                if(extension_loaded ('printer'))
                                {
                                    Printer::write_ticket( 3000, 1200,$ticket_name,$ticket_group_name,$ticket_prefix,$ticket_price,$code_print,$service);
                                }
                                if($i==$record['quantity'])
                                    DB::update_id( 'ticket_invoice',array( 'last_code'=> $code_print ), $id );    
                            } 
                               
 
                        }
                        */
                        $last_code = $this->get_last_code($id,$record['ticket_id']);
                        $_REQUEST['service'] = $service;
                        echo "<script>window.open('".Url::build_current(array("cmd"=>"print","invoice_id"=>$id,"form"=>$ticket_form,"denoted"=>$ticket_denoted,"ticket_id"=>$record['ticket_id'],"quantity"=>$record['quantity'],"ticket_name"=>$ticket_name,"ticket_group_name"=>$ticket_group_name,"ticket_prefix"=>$ticket_prefix,"ticket_price"=>$ticket_price,"last_code"=>$last_code,))."','_blank');</script>";
                        
                        /*
                        for($i=1; $i<=$record['quantity']; $i++)
                        {
                            $last_code++;
                            $code_print = $this->get_ticket_code($last_code);
                            if(extension_loaded ('printer'))
                            {
                                Printer::write_ticket( 3000, 1200,$ticket_name,$ticket_group_name,$ticket_prefix,$ticket_price,$code_print,$service);
                            }
                            if($i==$record['quantity'])
                                DB::update_id( 'ticket_invoice',array( 'last_code'=> $code_print ), $id );    
                        } 
                        */
                    }
                }
            }
       }
	}
	function draw()
	{
        /**
         * B�n v�
         */
        require_once 'packages/hotel/packages/ticket/includes/php/ticket.php';
        $this->map = array();
        $area = get_ticket_area();
        //System::debug($area);
        //N?u kh�ng c� qu?y th� T?o request d? ch?n qu?y s?n
        
        if(!Url::get('ticket_area_id'))
        {
            foreach($area as $k => $v)
            {
                $_REQUEST['ticket_area_id'] = $v['id'];
            }     
        }        
        
        $this->map['ticket_area_id_list'] = String::get_list($area);
        if(Url::get('ticket_area_id'))
        {
            $sql = '
                    Select
                        ticket.*,
                        ticket_group.prefix,
                        ticket_group.name as ticket_group_name
                    From
                        ticket_area_type
                        inner join ticket on ticket.id = ticket_area_type.ticket_id
                        inner join ticket_group on ticket_group.id = ticket.ticket_group_id
                    Where
                        ticket_area_type.ticket_area_id = '.Url::get('ticket_area_id').'
                        and ticket_area_type.portal_id = \''.PORTAL_ID.'\'
                    ';
            $ticket = DB::fetch_all($sql);

            foreach($ticket as $key => $value)
            {
                $ticket[$key]['desc'] = '';
                $ticket[$key]['price'] = 0;
                $ticket[$key]['price_before_discount'] = 0;
                $service = DB::fetch_all('Select 
                                            ticket_service.*,
                                            ticket_service.price as price_before_discount,
                                            (ticket_service.price - ticket_service_grant.discount_money) - (ticket_service.price - ticket_service_grant.discount_money) * ticket_service_grant.discount_percent/100 as price 
                                        from 
                                            ticket_service_grant inner join ticket_service on ticket_service.id = ticket_service_grant.ticket_service_id
                                        where ticket_service_grant.ticket_id = '.$key.' order by ticket_service.id ');
                //System::debug($service);
                foreach($service as $k => $v)
                {
                    $ticket[$key]['desc'] .=  $v['name_1'].' ('.(System::display_number($v['price'])).'), ';
                    $ticket[$key]['price'] += $v['price'];
                    $ticket[$key]['price_before_discount'] += $v['price_before_discount'];
                }
                $ticket[$key]['price'] = System::display_number($ticket[$key]['price']);
                $ticket[$key]['price_before_discount'] = System::display_number($ticket[$key]['price_before_discount']);
            }
            //System::debug($ticket);
            $_REQUEST['mi_ticket'] = $ticket;
            $_REQUEST['ticket_id'] = ViewTicketInvoiceForm::$ticket_id;
            //System::debug($ticket);
        }
        /**
         * Danh s�ch b�n v�
         */
         
        $item_per_page = 20;
		$cond = '1=1 
                    and ticket_invoice.portal_id=\''.PORTAL_ID.'\' 
                    and ticket_invoice.user_id = \''.Session::get('user_id').'\'
                    and ticket_invoice.ticket_area_id = \''.Url::get('ticket_area_id').'\'
                ';
        $sql = '
			SELECT
				count(*) AS acount
			FROM
				ticket_invoice
			WHERE
				'.$cond.'
		';
		require_once 'packages/core/includes/utils/paging.php';
		$this->map['total'] =  DB::fetch($sql,'acount');
		$this->map['paging'] =  paging($this->map['total'],$item_per_page,5,false,'page_no',array('ticket_area_id','cmd'));
		$sql = '
			SELECT * FROM
			(
				SELECT
					ticket_invoice.*,
                    to_char(ticket_invoice.date_used,\'DD/MM/YYYY\') as create_date,
                    ticket.name as ticket_name,
                    ticket_area.name as ticket_area_name,
					ROW_NUMBER() OVER (ORDER BY ticket_invoice.id desc ) as rownumber
				FROM
					ticket_invoice
					INNER JOIN ticket ON ticket_invoice.ticket_id = ticket.id
                    INNER JOIN ticket_area ON ticket_invoice.ticket_area_id = ticket_area.id
				WHERE
					'.$cond.'
				ORDER BY
					ticket_invoice.id desc
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'	
		';
        //System::debug($sql);
		$items = DB::fetch_all($sql);
        //System::debug($items);
		$i = (page_no()-1)*$item_per_page + 1;
		foreach($items as $key=>$value){
			$items[$key]['i'] = $i++;
			//Lam tron 2 chu so sau dau phay
            $items[$key]['quantity'] = System::display_number($value['quantity']);
            $items[$key]['total'] = System::display_number($value['total']);
		}
        $this->map['ticket_area_name'] = DB::fetch('select name from ticket_area where id = '.Url::get('ticket_area_id'), 'name' );
        $this->map['user_id'] = Session::get('user_id');
		$this->map['items'] = $items;
        //System::debug($this->map);
		$this->parse_layout('add',$this->map+
			array(
			)
		);
	}
    
    function get_last_code($id,$ticket_id)
	{
        if($lastest_item = DB::fetch('SELECT id,last_code, to_char(date_used,\'YYYY\') as year FROM ticket_invoice where id != '.$id.' and ticket_id = '.$ticket_id.' and portal_id = \''.PORTAL_ID.'\' ORDER BY id DESC'))
        {
            //System::debug($lastest_item);
            if($lastest_item['last_code'])
            {
                if($lastest_item['year']==date('Y'))
                    $code = $lastest_item['last_code'];
                else
                    $code = 0;
            }
            else
                $code = 0;
        }
        else
            $code = 0;
        return $code;
	}
    function get_ticket_code($ticket_code){
		$code = '';
		$leng = strlen($ticket_code);
		for($j=0;$j<6-$leng;$j++){
			$code .= '0';	
		}
		$code = $code.$ticket_code;
		return $code;
	}
}
?>