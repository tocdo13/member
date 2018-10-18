<?php
class BillTicketInvoiceGroupForm extends Form
{
	function BillTicketInvoiceGroupForm()
	{
		Form::Form('BillTicketInvoiceGroupForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
 
	function draw()
	{
        require_once 'packages/hotel/packages/ticket/includes/php/ticket.php';
        $this->map = array();
		$sql = '
                select 
                    ticket_invoice.id
                    ,ticket_invoice.ticket_id
                    ,ticket.name as ticket_name
                    ,ticket_invoice.quantity
                    ,ticket_invoice.price
                    ,ticket_invoice.total
                    ,(ticket_invoice.price * ticket_invoice.quantity) as total_price
                    ,ticket_invoice.ticket_reservation_id
                    ,ticket_invoice.discount_quantity
                    ,ticket_invoice.discount_rate
                    ,ticket_invoice.discount_cash
                    ,(ticket_invoice.price * ticket_invoice.discount_quantity) as total_discount_quantity
                    ,(ticket_invoice.price * (ticket_invoice.quantity - ticket_invoice.discount_quantity) * ticket_invoice.discount_rate/100) as total_discount_rate
                from ticket_invoice
                    INNER JOIN ticket ON ticket.id = ticket_invoice.ticket_id
                    INNER JOIN ticket_reservation ON ticket_reservation.id = ticket_invoice.ticket_reservation_id
                    
                where
                    ticket_invoice.ticket_reservation_id = \''.URL::get('id').'\'
                    and ticket_invoice.quantity is not null
		      ';
        
        $items = DB::fetch_all($sql);
        if(User::is_admin())
        {
            //System::debug($items);
        }
        //System::debug($items);
        foreach($items as $key=>$value)
        {
            if($value['discount_cash'] == '')
            {
                $value['discount_cash'] = 0;
            }
            if($value['discount_rate'] == '')
            {
                $value['discount_rate'] = 0;
            }
            $real_quantity = $value['quantity'] - $value['discount_quantity'];
            $total = $real_quantity*$value['price'];
            $discount_quantity = $value['discount_quantity']*$value['price'];
            $discount_cash = $real_quantity*($value['discount_cash']);
            $discount_rate = ($total - $discount_cash)*$value['discount_rate']/100;
            $items[$key]['total'] = System::display_number($value['total']);
            $items[$key]['price'] = System::display_number($value['price']);
            $items[$key]['total_discount_rate'] = System::display_number($discount_rate);
            $items[$key]['total_discount_quantity'] = System::display_number($discount_quantity);
            $items[$key]['total_price'] = System::display_number($value['total_price']);
            $items[$key]['discount_cash'] = System::display_number($discount_cash);
		}
        //System::debug($sql);
        //System::debug($items);
        if(User::is_admin())
        {
            //System::debug($items);
        }
        $this->map['items'] = $items;
        $sql2 = '
            select
                ticket_reservation.id
                ,ticket_reservation.total as total_all
                ,ticket_reservation.deposit
                ,ticket_reservation.discount_rate
                ,ticket_reservation.customer_name
                ,((ticket_reservation.total*ticket_reservation.discount_rate)/(100-ticket_reservation.discount_rate)) as total_all_discount_rate
            from ticket_reservation
            where
                ticket_reservation.id = '.URL::get('id').'
        ';
        $items2 = DB::fetch_all($sql2);
        //System::debug($items2);
        foreach($items2 as $key=>$value){
            $items2[$key]['total_all'] = System::display_number($value['total_all']);
            $items2[$key]['total_all_discount_rate'] = System::display_number($value['total_all_discount_rate']);
            $items2[$key]['deposit'] = System::display_number($value['deposit']);
		}
        
        $payment_list = DB::fetch_all('SELECT payment.id, payment_type.name_'.Portal::language().' as payment_type_name, payment.amount, payment.currency_id FROM payment inner join payment_type on payment_type.def_code=payment.payment_type_id WHERE payment.bill_id=\''.Url::get('id').'\' AND payment.type=\'TICKET\'');
        $pay_with_room = DB::fetch('select pay_with_room,amount_pay_with_room,payment.currency_id from ticket_reservation left join payment ON payment.bill_id =ticket_reservation.id  where ticket_reservation.id='.Url::get('id'));
        if($pay_with_room['pay_with_room']==1)
            array_push($payment_list,array('payment_type_name'=>Portal::language('pay_with_room'),
                                           'amount'=>$pay_with_room['amount_pay_with_room'],
                                           'currency_id'=>$pay_with_room['currency_id']
                                           )
                       );
        $this->map['payment_list'] = $payment_list;
        
        $this->map['items2'] = $items2;
		$this->parse_layout('bill',
			array(
				'items'=>$items,
                'items2'=>$items2,
			)+$this->map
		);
	}
}
?>