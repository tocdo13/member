<?php
class TicketRevenueReportForm extends Form
{
	function TicketRevenueReportForm()
	{
		Form::Form('TicketRevenueReportForm');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw()
	{
        require_once 'packages/hotel/packages/ticket/includes/php/ticket.php';
        $this->map = array();
        
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):999;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        
        
        $area = get_ticket_area();
        $this->map['ticket_area_id_list'] = array(''=>Portal::language('All'))+String::get_list($area);
        $ticket= get_ticket();
        $this->map['ticket_id_list'] = array(''=>Portal::language('All'))+String::get_list($ticket);
        $ticket_group= get_ticket_group();
        $this->map['ticket_group_id_list'] = array(''=>Portal::language('All'))+String::get_list($ticket_group);
    	//L?y ra c�c account
		$users = DB::fetch_all('select 
									account.id
									,party.full_name 
								from account 
								INNER JOIN party on party.user_id = account.id AND party.type=\'USER\' WHERE account.type=\'USER\'  ORDER BY account.id');			
		//System::debug($users);
        $this->map['user_id_list'] = array(''=>Portal::language('All'))+String::get_list($users);
        
        
        $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
        $_REQUEST['to_date'] = $this->map['to_date'];
        //dau thang
        $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):date('1/m/Y');
        $_REQUEST['from_date'] = $this->map['from_date'];
        
		$cond = ' 1=1';
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
        if(Url::get('portal_id'))
        {
            $portal_id = Url::get('portal_id');
        }
        else
        {
            $portal_id = PORTAL_ID;
            $_REQUEST['portal_id'] = PORTAL_ID;                       
        }
        
        if($portal_id != 'ALL')
        {
            $cond.=' AND ticket_invoice.portal_id = \''.$portal_id.'\' '; 
        }
        
		$cond .= ' '
				.(URL::get('ticket_area_id')?'and ticket_invoice.ticket_area_id = '.URL::get('ticket_area_id').'':'')
                .(URL::get('ticket_group_id')?'and ticket.ticket_group_id = '.URL::get('ticket_group_id').'':'')  
				.(URL::get('ticket_id')?'and ticket_invoice.ticket_id = '.URL::get('ticket_id').'':'')
				.(URL::get('from_date')?' and ticket_invoice.time>=\''.Date_Time::to_time(URL::get('from_date')).'\'':'')
				.(URL::get('to_date')?' and ticket_invoice.time<\''.(Date_Time::to_time(URL::get('to_date'))+86400).'\'':'')
                .(URL::get('user_id')?' and ticket_invoice.user_id = \''.URL::get('user_id').'\'':'')
		;
        
		$sql = '
			SELECT * FROM
			(
				SELECT 
					ticket_invoice.*,
                    to_char(ticket_invoice.date_used,\'DD/MM/YYYY\') as create_date,
                    ticket.name as ticket_name,
                    ticket_area.name as ticket_area_name,
                    ticket_group.name as ticket_group_name,
					ROW_NUMBER() OVER (ORDER BY ticket_invoice.id ) as rownumber
				FROM 
                    ticket_invoice
    				INNER JOIN ticket ON ticket_invoice.ticket_id = ticket.id
                    INNER JOIN ticket_reservation ON ticket_invoice.ticket_reservation_id = ticket_reservation.id
                    INNER JOIN ticket_area ON ticket_reservation.ticket_area_id = ticket_area.id
                    INNER JOIN ticket_group ON ticket.ticket_group_id = ticket_group.id
				WHERE 
                    '.$cond.'
                ORDER BY
					ticket_invoice.id
			)
			where 
				rownumber > '.(($this->map['start_page']-1)*$this->map['line_per_page']).' AND rownumber<='.($this->map['no_of_page']*$this->map['line_per_page']).'
		';
        
        //System::debug($sql);
  
		$items = DB::fetch_all($sql);
        //System::debug($items);
        $i=1;
		foreach($items as $key=>$value)
		{
			$items[$key]['stt'] = $i++;
            $items[$key]['price'] = System::display_number($value['price']);
            $items[$key]['quantity'] = System::display_number($value['quantity']);
            $items[$key]['total'] = System::display_number($value['total']);
		}
        $this->print_all_pages($items);
	}
    
    function print_all_pages($items)
	{
		$count = 0;
		$total_page = 1;
		$pages = array();
		foreach($items as $key=>$item)
		{
			if($count>=$this->map['line_per_page'])
			{
				$count = 0;
				$total_page++;
			}
			$pages[$total_page][$key] = $item;
			$count++;
		}
        
		if(sizeof($pages)>0)
		{
			$this->group_function_params = array(
				    'total_quantity'=>0,
					'total_amount'=>0,
				);
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $page_no,$total_page);
			}
		}
		else
		{
			$this->parse_layout('report',
				array(
					'page_no'=>0,
					'total_page'=>0,
                    'has_no_data'=>true
				)+$this->map
			);
		}
	}
    function print_page($items, $page_no, $total_page)
	{
        $last_group_function_params = $this->group_function_params;	
		foreach($items as $item)
		{
			if($temp=System::calculate_number($item['quantity']))
			{
				$this->group_function_params['total_quantity'] += $temp;
			}
			if($temp=System::calculate_number($item['total']))
			{
				$this->group_function_params['total_amount'] += $temp;
			}			
		}	
		$this->parse_layout('report',array(
				'items'=>$items,
				'last_group_function_params'=>$last_group_function_params,
				'group_function_params'=>$this->group_function_params,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)+$this->map
		);
	}
}
?>