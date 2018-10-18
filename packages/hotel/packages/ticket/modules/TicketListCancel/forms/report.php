<?php
class TicketListCancelForm extends Form
{
	function TicketListCancelForm()
	{
		Form::Form('TicketListCancelForm');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
	}
	function draw()
	{
        require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';
        require_once 'packages/hotel/packages/ticket/includes/php/ticket.php';
        $this->map = array();
        
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):999;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        
        
       
        if(Portal::language() == 1)
        {
            $ticket_type = DB::fetch_all('select ticket.id,ticket.name from ticket ORDER BY ticket.id');
        }
        else
        {
            $ticket_type = DB::fetch_all('select ticket.id,ticket.name_2 from ticket ORDER BY ticket.id');
        }
        $this->map['ticket_id_list'] = array(''=>Portal::language('All'))+String::get_list($ticket_type);
        $this->day = date('d/m/Y');
        $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
        $_REQUEST['from_date'] = $this->map['from_date'];
        $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
        $_REQUEST['to_date'] = $this->map['to_date'];
        
        $day_orc = Date_Time::to_orc_date(date('d/m/Y'));
        
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list());
       	$users = DB::fetch_all('select 
									account.id
									,party.full_name 
								from account 
								INNER JOIN party on party.user_id = account.id AND party.type=\'USER\' WHERE account.type=\'USER\'  ORDER BY account.id');			
		//System::debug($users);
        $this->map['user_id_list'] = array(''=>Portal::language('All'))+String::get_list($users);
      // thêm số 0 vào đâu serie
       /* $serie= DB::fetch_all('select ticket_cancelation.id,
                                        ticket_cancelation.ticket_serie
                                        from ticket_cancelation');
            
           
            foreach($serie as $k=>$v)
               {
              $serie[$k]['ticket_serie']+=10000000;
              
            $serie[$k]['ticket_serie'] =  substr($serie[$k]['ticket_serie'],1,7);
              
              }
            //System::debug($serie);
            //exit(); */
               
                
                
                 
      
       if(Url::get('from_time'))
        {
            $from_time = $this->calc_time(Url::get('from_time'));
            $this->map['from_time'] = Url::get('from_time');
        }
        else
        {
            $this->map['from_time'] = '00:00';            
            $from_time = $this->calc_time($this->map['from_time']);
            $_REQUEST['from_time'] = $this->map['from_time'];
        }
        if(Url::get('to_time'))
        {
            $to_time = $this->calc_time(Url::get('to_time'));
            $this->map['to_time'] = Url::get('to_time');
        }
        else
        {
            $this->map['to_time'] = '23:59';            
            $to_time = $this->calc_time($this->map['to_time']);
            $_REQUEST['to_time'] = $this->map['to_time'];
        }
        $from_time_view = Date_Time::to_time($this->map['from_date']) + $from_time;                                
        $to_time_view = Date_Time::to_time($this->map['to_date']) + $to_time;
        $cond =' 1 = 1 ';
        
        	$cond .= ' '
			    .(URL::get('ticket_id')?'and ticket_cancelation.ticket_id = '.URL::get('ticket_id').'':'')
                .(URL::get('user_id')?' and ticket_cancelation.user_id = \''.URL::get('user_id').'\'':'')
		;
        //Tìm ki?m trong ngày hnay
       
        $cond .= ' 
					AND ticket_cancelation.time >= \''.$from_time_view.'\'
                    AND ticket_cancelation.time <= \''.$to_time_view.'\'  
				';
       
        $serie= DB::fetch_all('select ticket_cancelation.id,
                                        ticket_cancelation.ticket_serie
                                        from ticket_cancelation');
       
        $count_ticket_cancelation = DB::fetch_all('
			SELECT 
                ticket_cancelation.ticket_reservation_id as id
                ,ticket_cancelation.ticket_reservation_id 
				,count(ticket_cancelation.id) as num
			FROM
				ticket_cancelation
				inner join ticket on ticket_cancelation.ticket_id = ticket.id
            WHERE
				'.$cond.'              
			GROUP BY ticket_cancelation.ticket_reservation_id
			');
        if(Portal::language() == 1)
        {
            $lt = '';
        }
        else
        {
            $lt = '_2';
        }    
		$sql = '
			SELECT * FROM
			(
			SELECT
                    ROW_NUMBER() OVER (ORDER BY ticket_cancelation.ticket_reservation_id ) as id,
                    ticket_cancelation.id as ticket_cancelation_id, 
                    ticket_cancelation.ticket_reservation_id as ticket_reservation,
                    ticket.name'.$lt.' as ticket_name,
                    ticket_cancelation.ticket_serie,
                    ticket_cancelation.time as time,
                    ticket_cancelation.user_id ,
                    
                    ticket_cancelation.note as note,
                    ROW_NUMBER() OVER (ORDER BY ticket_cancelation.id ) as rownumber
                FROM 
                        ticket_cancelation
                        inner join ticket on ticket_cancelation.ticket_id = ticket.id
                        
				WHERE 
                    '.$cond.'
                ORDER BY 
                    ticket_cancelation.ticket_reservation_id
					
			)
			where 
				rownumber > '.(($this->map['start_page']-1)*$this->map['line_per_page']).' AND rownumber<='.($this->map['no_of_page']*$this->map['line_per_page']).'
		';
        
        //System::debug($sql);
  
		$items = DB::fetch_all($sql,$count_ticket_cancelation,$serie);
        //System::debug($items);
        $res_id = false;
        $i=1;
		
        foreach($items as $key=>$value)
		{
			if($items[$key]['ticket_reservation'] != $res_id)
            {
               $res_id = $items[$key]['ticket_reservation'];
            } 
            $items[$key]['stt'] = $i++;
            $items[$key]['quantity'] =1;
            $items[$key]['ticket_serie']+=10000000;
            $items[$key]['ticket_serie']=substr($items[$key]['ticket_serie'],1,7);
            
		}
        
        //System::debug($items);
        //System::debug($count_ticket_cancelation);
       //exit();
        $this->print_all_pages($items,$count_ticket_cancelation);
	}
    
    
    function print_all_pages($items,$count_ticket_cancelation)
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
				$this->print_page($page, $page_no,$total_page,$count_ticket_cancelation);
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
    function print_page($items, $page_no, $total_page,$count_ticket_cancelation)
	{
        $last_group_function_params = $this->group_function_params;	
		foreach($items as $item)
		{
            if($temp=System::calculate_number($item['quantity']))
    			{
    				$this->group_function_params['total_quantity'] += $temp;
    			}
                }		
		$this->parse_layout('report',array(
				'items'=>$items,
				'last_group_function_params'=>$last_group_function_params,
				'group_function_params'=>$this->group_function_params,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
                'count_ticket_cancelation'=>$count_ticket_cancelation,
                
			)+$this->map
		);
	}
    function calc_time($string)
    {
        $arr = explode(':',$string);
        //System::debug($arr);
        return $arr[0]*3600 + $arr[1]*60;
    }
}
 
?>