<?php
class ListTicketInvoiceForm extends Form
{
	function ListTicketInvoiceForm()
	{
		Form::Form('ListTicketInvoiceForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
	}

     function on_submit()
     {
        if(Url::get('delete_selected'))
        {
            $selected_ids = Url::get('selected_ids');
            if(!empty($selected_ids))
            {
                $log_mi_ticket = '';
    			foreach($selected_ids as $id)
    			{
                    DB::delete_id( 'ticket_reservation', $id );
                    $log_mi_ticket .= 'Code: '. $id. '<br>';
                    DB::delete('ticket_invoice',' ticket_reservation_id = '.$id);
                    DB::delete('ticket_invoice_detail',' ticket_invoice_id = '.$id);
                    DB::delete('payment','type=\'TICKET\' and bill_id='.$id);
    			} 
                $title = 'Delete ticket reservation';
        		$description = ''
        		.Portal::language('time').':'.date('d/m/Y H:i:s').'<br>  ' 
        		.'<hr>'.$log_mi_ticket.'';
        		System::log('delete',$title,$description,URL::get('id'));  
            }
        }
     }

	function draw()
	{
        require_once 'packages/hotel/packages/ticket/includes/php/ticket.php';
        $this->map = array();
        //$area = get_ticket_area();
        //$this->map['ticket_area_id_list'] = array(''=>Portal::language('All'))+String::get_list($area);
        $ticket= get_ticket();
        $this->map['ticket_id_list'] = array(''=>Portal::language('All'))+String::get_list($ticket);
        $this->map['time_start'] = Url::get('time_start')?Url::get('time_start'):date('1/m/Y');
        $_REQUEST['time_start'] = $this->map['time_start'];
        //dau thang
        $this->map['time_end'] = Url::get('time_end')?Url::get('time_end'):date('d/m/Y');
        $_REQUEST['time_end'] = $this->map['time_end'];
		$cond = '
				ticket_reservation.portal_id=\''.PORTAL_ID.'\' ' 
				.(URL::get('time_start')?' and ticket_reservation.time>=\''.Date_Time::to_time(URL::get('time_start')).'\'':'')
				.(URL::get('time_end')?' and ticket_reservation.time<\''.(Date_Time::to_time(URL::get('time_end'))+86400).'\'':'')
		;
		$item_per_page = 100;
		
        DB::query('
			select count(*) as acount
			from 
                ticket_reservation
				
			where '.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page,10,false,'page_no',array('ticket_area_id','ticket_id','time_start','time_end'));
		$sql = '
			SELECT * FROM
			( select
                                       ticket_reservation.id
                                      ,ticket_reservation.customer_id as customerid
                                      ,ticket_reservation.total as total_all
                                      ,ticket_reservation.deposit
                                      ,ticket_reservation.customer_name
                                      ,ticket_reservation.total_paid
                                      ,ticket_reservation.reservation_room_id
                                      ,ticket_reservation.num_cancel
                                      ,ticket_reservation.payment_status
                                      ,ticket_reservation.member_code
                                      ,room.name as room_name
                                      ,customer.address 
                                      ,ticket_reservation.user_id
                                      ,ticket_reservation.time
                                      ,ticket_reservation.portal_id
                                      ,ROW_NUMBER() OVER (ORDER BY ticket_reservation.id desc ) as rownumber
                                      
                                  from 
                                      ticket_reservation
                                      left JOIN customer ON ticket_reservation.customer_id = customer.id
                                      left join reservation_room on reservation_room.id = ticket_reservation.reservation_room_id
                                      left join room on room.id = reservation_room.room_id
 
                                  where 
                                        '.$cond.' 
                                  order by 
                                     ticket_reservation.id desc
			)
			where 
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber <= '.(page_no()*$item_per_page).'
		';
        
        $items = DB::fetch_all($sql);
        //System::debug($sql);
        //System::debug($items);
		$i = (page_no()-1)*$item_per_page + 1;
		foreach($items as $key=>$value){
			$items[$key]['i'] = $i++;

            $items[$key]['total_all'] = System::display_number($value['total_all']);
		}
		$this->map['items'] = $items;
        
        
		$this->parse_layout('list',
			array(
				'items'=>$items,
				'paging'=>$paging,
			)+$this->map
		);
	}
}
?>