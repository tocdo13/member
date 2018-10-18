<?php
class AddTicketInvoiceGroupForm extends Form
{
    //Bien nay luu lai ma~ ve dc in, de truyen sang layout => ko phai chon lai ve nua 
    static $ticket_id;
	function AddTicketInvoiceGroupForm()
	{
		Form::Form('AddTicketInvoiceGroupForm');
        $this->add('ticket_invoice.quantity',new TextType(true,'quantity',0,255));
		$this->link_js('packages/core/includes/js/multi_items.js');
	}
    
	function on_submit()
	{
       if(Url::get('save'))
       {
            //System::debug($_REQUEST['mi_ticket']);
            //exit();
            $ticket_reservation_id = DB::insert('ticket_reservation',
                                                array(
                                                        'time'=>time()
                                                        ,'customer_id'=>Url::get('customer_id')
                                                        ,'ticket_area_id'=>Url::get('ticket_area_id')
                                                        ,'customer_address'=>Url::get('customer_address')
                                                        ,'customer_name'=>Url::get('customer_name')
                                                        ,'note'=>Url::get('note')
                                                        ,'total'=>System::calculate_number(Url::get('total_all'))
                                                        ,'total_before_tax'=>System::calculate_number(Url::get('total_all'))*10/11
                                                        ,'discount_rate'=>URL::get('discount_rate')
                                                        ,'reservation_room_id'=>URL::get('reservation_room_id')
                                                        ,'reservation_traveller_id'=>URL::get('reservation_traveller_id')
                                                        ,'pay_with_room'=>URL::get('pay_with_room')
                                                        ,'member_code'=>Url::get('member_code')
                                                        ,'member_level_id'=>Url::get('member_level_id')
                                                        ,'create_member_date'=>Url::get('create_member_date')
                                                        ,'user_id'=>Session::get('user_id'),'portal_id'=>PORTAL_ID));
                                                        
            $log_mi_ticket = '';
            if( isset($_REQUEST['mi_ticket']) )
            {
                foreach( $_REQUEST['mi_ticket'] as $key => $record )
                {
                    $record['quantity'] = System::calculate_number($record['quantity']);
                    if(1==1)
                    {
                        $ticket_prefix = $record['prefix'];
                        $ticket_form = $record['form'];
                        $ticket_denoted = $record['denoted'];
                        $ticket_group_name = $record['ticket_group_name'];
                        $ticket_id = $record['ticket_id'];
                        $ticket_name = DB::fetch('select id,name from ticket where id = '.$ticket_id, 'name');
                        $ticket_price = $record['price'];
                        
                        $record['ticket_id'] = $record['ticket_id'];
                        
                        unset($record['id']);
                        unset($record['received']);
                        unset($record['return']);
                        unset($record['prefix']);
                        unset($record['ticket_group_name']);
                        unset($record['name']);
                        unset($record['form']);
                        unset($record['denoted']);
                        
                        
                        $record['price'] = System::calculate_number($record['price']);
                        $record['discount_cash'] = System::calculate_number($record['discount_cash']);
                        $record['price_before_discount'] = System::calculate_number($record['price_before_discount']);
                        $record['total'] = System::calculate_number($record['total']);
                        $record['total_before_discount'] = System::calculate_number($record['quantity']) * $record['price_before_discount'];
                        $record['ticket_area_id'] = Url::get('ticket_area_id');
                        $record['time'] = time();
                        $record['date_used'] = Date_Time::to_orc_date(date('d/m/Y'));
                        $record['user_id'] = Session::get('user_id');
                        $record['portal_id'] = PORTAL_ID;
                        $record['ticket_reservation_id'] = $ticket_reservation_id;
                        
                        $id = DB::insert('ticket_invoice',$record);
                        $log_mi_ticket .= Portal::language('add'). ':'.$ticket_name.' SL: '.$record['quantity']. ' '.Portal::language('promotion').' '.$record['discount_quantity'].'<br>';
                        AddTicketInvoiceGroupForm::$ticket_id = $record['ticket_id'];
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
                    }
                }  
            }
            $title = 'Add ticket reservation , Code: '.$ticket_reservation_id;
    		$description = ''
    		.Portal::language('time').':'.date('d/m/Y H:i:s').'<br>  ' 
    		.'<hr>'.$log_mi_ticket.'';
    		System::log('add',$title,$description,$ticket_reservation_id); 
            Url::redirect_current(array('cmd'=>'edit','ticket_area_id'=>Url::get('ticket_area_id'),'id'=>$ticket_reservation_id));
       }     
	}
	function draw()
	{
        require_once 'packages/hotel/includes/php/hotel.php';
	    // Danh sacsh phong 
		$rows_list = Hotel::get_reservation_room();
		$guest_list = Hotel::get_reservation_traveller_guest();
		$list_room[0]='-------';
		$list_room = $list_room+String::get_list($rows_list,'name');
		//danh sach reservation
		$rows_list = $guest_list;
		$list_reservation[0]='-------';
		$list_reservation_traveller = $list_reservation+String::get_list($rows_list,'name');
        $this->map= array(
			'reservation_traveller_list'=>$guest_list,
			'reservation_room_id_list'=>$list_room,
			'reservation_traveller_id_list'=>$list_reservation_traveller
		);
        
        require_once 'packages/hotel/packages/ticket/includes/php/ticket.php';
        //list ticket
        $sql = 'select 
                    ticket.id,
                    ticket.name
                from 
                    ticket
                    inner join ticket_area_type on ticket.id = ticket_area_type.ticket_id
                where 
                    ticket.portal_id = \''.PORTAL_ID.'\'
                    and ticket_area_type.ticket_area_id = '.Url::get('ticket_area_id').'
                ';
        $ticket = DB::fetch_all($sql);
        $ticket_options = '<option value="">'.Portal::language('choose_ticket').'</option>';
		foreach($ticket as $key=>$value)
		{
			$ticket_options.='<option value="'.$value['id'].'">'.$value['name'].'</option>';
		}
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
        //System::debug($this->map);
		$this->parse_layout('add', $this->map +
			array(
                'ticket_options'=>$ticket_options,
                'ticket'=>$ticket
			         )
		);
	}
}
?>