<?php
class DetailServiceReportForm extends Form
{
	function DetailServiceReportForm()
	{
		Form::Form('DetailServiceReportForm');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        //echo date('M-Y');
	}
	function draw()
	{
        require_once 'packages/hotel/packages/ticket/includes/php/ticket.php';
        $this->map = array();
        
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):999;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        
        
        //$area = get_ticket_area();
        //$this->map['ticket_area_id_list'] = array(''=>Portal::language('All'))+String::get_list($area);
        //$ticket= get_ticket();
        //$this->map['ticket_id_list'] = array(''=>Portal::language('All'))+String::get_list($ticket);
        $ticket_service= get_service();
        $this->map['ticket_service_id_list'] = array(''=>Portal::language('All'))+String::get_list($ticket_service);
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
                .(URL::get('ticket_service_id')?'and ticket_invoice_detail.ticket_service_id = '.URL::get('ticket_service_id').'':'')  
				.(URL::get('ticket_id')?'and ticket_invoice.ticket_id = '.URL::get('ticket_id').'':'')
				.(URL::get('from_date')?' and ticket_invoice.time>=\''.Date_Time::to_time(URL::get('from_date')).'\'':'')
				.(URL::get('to_date')?' and ticket_invoice.time<\''.(Date_Time::to_time(URL::get('to_date'))+86400).'\'':'')
                .(URL::get('user_id')?' and ticket_invoice.user_id = \''.URL::get('user_id').'\'':'')
		;
        
		$sql = '
			SELECT * FROM
			(
				SELECT 
					ticket_invoice_detail.*,
                    to_char(ticket_invoice.date_used,\'DD/MM/YYYY\') as create_date,
                    ticket_invoice_detail.ticket_service_name as service_name,
					ROW_NUMBER() OVER (ORDER BY ticket_invoice.id ) as rownumber,
                    DECODE(ticket_invoice.discount_quantity,null,0,ticket_invoice.discount_quantity) as discount_quantity,
                    ticket_invoice.user_id
				FROM 
                    ticket_invoice
    				INNER JOIN ticket_invoice_detail ON ticket_invoice.id = ticket_invoice_detail.ticket_invoice_id
				    left join ticket on ticket_invoice.ticket_id = ticket.id
                WHERE 
                    '.$cond.'
                ORDER BY 
					ticket_invoice.date_used desc
			)
			where 
				rownumber > '.(($this->map['start_page']-1)*$this->map['line_per_page']).' AND rownumber<='.($this->map['no_of_page']*$this->map['line_per_page']).'
		';
        
        //System::debug($sql);
  
		$items = DB::fetch_all($sql);
        
        $i=1;
		foreach($items as $key=>$value)
		{
			$items[$key]['stt'] = $i++;
            $items[$key]['price'] = System::display_number($value['price']);
            $items[$key]['quantity'] = System::display_number($value['quantity'] - $value['discount_quantity']);
            $items[$key]['total'] = System::display_number($value['price']*($value['quantity']- $value['discount_quantity']));
            $items[$key]['foc']= 0;
            $items[$key]['row_span']=0; 
            $items[$key]['price_before_discount'] = System::display_number($value['price_before_discount']);
            $items[$key]['discount_money'] = System::display_number($value['discount_money']);
		    $items[$key]['balance']=System::display_number(($value['price']*$value['quantity'])-$value['discount_money']-((($value['price']*$value['quantity'])*$value['discount_percent'])/100));
            $items[$key]['tax']=System::display_number((($value['price']*$value['quantity'])-$value['discount_money']-((($value['price']*$value['quantity'])*$value['discount_percent'])/100))-(($value['price']*$value['quantity'])-$value['discount_money']-((($value['price']*$value['quantity'])*$value['discount_percent'])/100))/1.1);
            $items[$key]['net_amount']=System::display_number((($value['price']*$value['quantity'])-$value['discount_money']-((($value['price']*$value['quantity'])*$value['discount_percent'])/100))-((($value['price']*$value['quantity'])-$value['discount_money']-((($value['price']*$value['quantity'])*$value['discount_percent'])/100))-(($value['price']*$value['quantity'])-$value['discount_money']-((($value['price']*$value['quantity'])*$value['discount_percent'])/100))/1.1));
                if($items[$key]['discount_quantity'] > 0)
                {
                  $items[$key]['row_span']= 2;
                }
                       
        }
        //System::debug($items);
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
                    'total_balance'=>0,  
                    'total_tax'=>0,
                    'total_net_amount'=>0,
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
            /*
			if($temp=System::calculate_number($item['quantity']))
			{
				$this->group_function_params['total_quantity'] += $temp;
			}
            */
			if($temp=System::calculate_number($item['total']))
			{
				$this->group_function_params['total_amount'] += $temp;
			}
            if($temp=System::calculate_number($item['balance']))
			{
				$this->group_function_params['total_balance'] += $temp;
			}
            if($temp=System::calculate_number($item['tax']))
			{
				$this->group_function_params['total_tax'] += $temp;
			}
             if($temp=System::calculate_number($item['net_amount']))
			{
				$this->group_function_params['total_net_amount'] += $temp;
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