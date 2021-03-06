<?php
class EditTicketInvoiceGroupForm extends Form
{
    //Bien nay luu lai ma~ ve dc in, de truyen sang layout => ko phai chon lai ve nua 
    static $ticket_id2;
	function EditTicketInvoiceGroupForm()
	{
		Form::Form('EditTicketInvoiceGroupForm');
        $this->link_js('packages/core/includes/js/multi_items.js');
        $this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
	}
    
	function on_submit()
	{
       if(Url::get('save'))
       {
            if(URL::get('deleted_ids'))
            {
				$ids = explode(',',URL::get('deleted_ids'));
				foreach($ids as $id)
                {
					DB::delete('ticket_invoice','id='.$id.'');
                    DB::delete('ticket_invoice_detail','ticket_invoice_id='.$id.'');
				}
			}
            DB::update('ticket_reservation',
                                        array
                                        (
                                        'time'=>time()
                                        ,'customer_id'=>Url::get('customer_id')
                                        ,'customer_address'=>Url::get('customer_address')
                                        ,'customer_name'=>Url::get('customer_name')
                                        ,'note'=>Url::get('note')
                                        ,'total'=>System::calculate_number(Url::get('total_all'))
                                        ,'total_before_tax'=>System::calculate_number(Url::get('total_all'))*10/11
                                        ,'discount_rate'=>URL::get('discount_rate')
                                        ,'reservation_room_id'=>URL::get('reservation_room_id')
                                        ,'reservation_traveller_id'=>URL::get('reservation_traveller_id')
                                        ,'pay_with_room'=>URL::get('pay_with_room')
                                        ,'user_id'=>Session::get('user_id'),'portal_id'=>PORTAL_ID)
                                        ,'id= '.URL::get('id')
                                        );
            
            
            if( isset($_REQUEST['mi_ticket']) )
            {
                foreach( $_REQUEST['mi_ticket'] as $key => $record )
                {
                    $record['quantity'] = System::calculate_number($record['quantity']);
                    if(1==1)
                    {
                        $ticket_name = $record['ticket_id'];
                        $ticket_invoice_id = $record['id'];
                        
                        unset($record['ticket_invoice_id']);
                        unset($record['received']);
                        unset($record['return']);
                        unset($record['prefix']);
                        unset($record['ticket_group_name']);
                        unset($record['id']);
                        unset($record['form']);
                        unset($record['denoted']);
                        //$record['id'] = $ticket_id;
                        $record['price'] = System::calculate_number($record['price']);
                        $record['price_before_discount'] = System::calculate_number($record['price_before_discount']);
                        $record['total'] = System::calculate_number($record['total']);
                        $record['total_before_discount'] = System::calculate_number($record['quantity']) * $record['price_before_discount'];
                        //$record['ticket_area_id'] = Url::get('ticket_area_id');
                        $record['time'] = time();
                        $record['date_used'] = Date_Time::to_orc_date(date('d/m/Y'));
                        $record['user_id'] = Session::get('user_id');
                        $record['portal_id'] = PORTAL_ID;
                        if($ticket_invoice_id and DB::fetch('select * from ticket_invoice where ticket_reservation_id = '.URL::get('id').' and ticket_id = '.$ticket_name))
                        {
                            DB::update('ticket_invoice',$record,'id='.$ticket_invoice_id);   
                        }
                        else 
                        {
                            $record['ticket_reservation_id'] = Url::get('id');
                            DB::insert('ticket_invoice',$record);
                        }
                    }
                }
            }
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
        $sql2 = '
                select
                     ticket_reservation.id
                     ,ticket_reservation.discount_rate
                     ,ticket_reservation.pay_with_room
                     ,ticket_reservation.total_paid
                     ,ticket_reservation.reservation_room_id
                     ,ticket_reservation.reservation_traveller_id
                     ,ticket_reservation.deposit
                     ,ticket_reservation.customer_address
                     ,ticket_reservation.customer_name
                     ,ticket_reservation.note
                     ,customer.id as customer_id
                    ,customer.name
                    ,customer.address
                    ,ticket_reservation.total_before_tax
                    ,ticket_reservation.total as total_all                   
                from
                    ticket_reservation
                    left JOIN customer ON ticket_reservation.customer_id = customer.id
                    left JOIN ticket_invoice ON ticket_reservation.id = ticket_invoice.ticket_reservation_id 
                where
                    ticket_reservation.id = \''.URL::get('id').'\' 
                order by
                    customer.name
        ';
        $items2 = DB::fetch_all($sql2);
        if(User::is_admin())
        {
            //System::debug($items2);
        }
        foreach($items2 as $key=>$value)
        {
            $items2[$key]['total_all'] = System::display_number($value['total_all']);
            $_REQUEST['reservation_room_id'] = $value['reservation_room_id'];
            $_REQUEST['reservation_traveller_id'] = $value['reservation_traveller_id'];
		}
        $sql3 = '
            select
                ticket_invoice.*
            from 
                ticket_invoice
                inner join ticket on ticket.id = ticket_invoice.ticket_id
                inner join ticket_group on ticket_group.id = ticket.ticket_group_id
            where 
                ticket_invoice.ticket_reservation_id = \''.URL::get('id').'\'
        ';
        $items3 = DB::fetch_all($sql3);
        $_REQUEST['mi_ticket'] = $items3;
        //System::debug($_REQUEST['mi_ticket']);
        //list ticket
        $ticket_area_id = DB::fetch('select ticket_area_id from ticket_reservation where id = '.Url::get('id'),'ticket_area_id');
        $sql = 'select 
                    ticket.id,
                    ticket.name
                from 
                    ticket
                    inner join ticket_area_type on ticket.id = ticket_area_type.ticket_id
                where 
                    ticket.portal_id = \''.PORTAL_ID.'\'
                    and ticket_area_type.ticket_area_id = '.$ticket_area_id.'
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
        $this->parse_layout('edit', $this->map + 
			array(
            'items2'=>$items2,
            'ticket'=>$ticket,
            'ticket_options'=>$ticket_options,
            'items3'=>$items3,
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
    function get_ticket_code($ticket_code)
    {
		$code = '';
		$leng = strlen($ticket_code);
		for($j=0;$j<6-$leng;$j++)
        {
			$code .= '0';	
		}
		$code = $code.$ticket_code;
		return $code;
	}
}
?>