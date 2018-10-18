<?php
class ListTicketInvoiceForm extends Form
{
	function ListTicketInvoiceForm()
	{
		Form::Form('ListTicketInvoiceForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
    function on_submit()
    {
        if(!Url::get('search'))
        {
            $selected_ids = Url::get('selected_ids');
            if(!empty($selected_ids))
            {
    			foreach($selected_ids as $id)
    			{
                    DB::delete_id( 'ticket_invoice', $id );
                    DB::delete('ticket_invoice_detail',' ticket_invoice_id = '.$id);
    			}  
            }
            
        }
    }
	function draw()
	{
        require_once 'packages/hotel/packages/ticket/includes/php/ticket.php';
        $this->map = array();
        $area = get_ticket_area();
        $this->map['ticket_area_id_list'] = array(''=>Portal::language('All'))+String::get_list($area);
        $ticket= get_ticket();
        $this->map['ticket_id_list'] = array(''=>Portal::language('All'))+String::get_list($ticket);
        
		$cond = '
				ticket_invoice.portal_id=\''.PORTAL_ID.'\' '
				.(URL::get('ticket_area_id')?'and ticket_invoice.ticket_area_id = '.URL::get('ticket_area_id').'':'') 
				.(URL::get('ticket_id')?'and ticket_invoice.ticket_id = '.URL::get('ticket_id').'':'')
				.(URL::get('time_start')?' and ticket_invoice.time>=\''.Date_Time::to_time(URL::get('time_start')).'\'':'')
				.(URL::get('time_end')?' and ticket_invoice.time<\''.(Date_Time::to_time(URL::get('time_end'))+86400).'\'':'')
		;
		$item_per_page = 100;
		
        DB::query('
			select count(*) as acount
			from 
                ticket_invoice
				INNER JOIN ticket ON ticket_invoice.ticket_id = ticket.id
                INNER JOIN ticket_area ON ticket_invoice.ticket_area_id = ticket_area.id
			where '.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page,10,false,'page_no',array('ticket_area_id','ticket_id','time_start','time_end'));
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
			where 
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber <= '.(page_no()*$item_per_page).'
		';
        
        $items = DB::fetch_all($sql);
		$i = (page_no()-1)*$item_per_page + 1;
		foreach($items as $key=>$value){
			$items[$key]['i'] = $i++;
            $items[$key]['price'] = System::display_number($value['price']);
            $items[$key]['quantity'] = System::display_number($value['quantity']);
            $items[$key]['total'] = System::display_number($value['total']);
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