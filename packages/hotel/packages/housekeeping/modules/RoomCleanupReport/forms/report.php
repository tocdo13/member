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
        //require_once 'packages/hotel/packages/ticket/includes/php/ticket.php';
        $this->map = array();
        
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):999;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        
        
        $rooms= DB::fetch_all('select * from room where room.portal_id = \''.PORTAL_ID.'\'');
        $this->map['room_id_list'] = array(''=>Portal::language('All'))+String::get_list($rooms);
        
    	//L?y ra các account
		$users = DB::fetch_all('select 
									account.id
									,party.full_name 
								from account 
								INNER JOIN party on party.user_id = account.id AND party.type=\'USER\' WHERE account.type=\'USER\'  ORDER BY account.id');			
		//System::debug($users);
        $this->map['user_id_list'] = array(''=>Portal::language('All'))+String::get_list($users);
        
        
        $this->map['date'] = Url::get('date')?Url::get('date'):date('d/m/Y');
        $_REQUEST['date'] = $this->map['date'];
        
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
            $cond.=' AND room_cleanup.portal_id = \''.$portal_id.'\' '; 
        }
        
		$cond .= ' '
				.(URL::get('room_id')?'and room_cleanup.room_id = '.URL::get('room_id').'':'')
				.(URL::get('date')?' and room_cleanup.start_time>=\''.Date_Time::to_time($this->map['date']).'\'':'')
				.(URL::get('date')?' and room_cleanup.start_time<\''.(Date_Time::to_time($this->map['date'])+86400).'\'':'')
                .(URL::get('user_id')?' and room_cleanup.user_id = \''.URL::get('user_id').'\'':'')
		;
        
		$sql = '
				SELECT 
					room_cleanup.*,
                    room.name as room_name
				FROM 
                    room_cleanup
    				INNER JOIN room ON room_cleanup.room_id = room.id
				WHERE 
                    '.$cond.'
                ORDER BY
					room.id
		';
        
        //System::debug($sql);
  
		$items = DB::fetch_all($sql);
        //System::debug($items);
        $final_items = array();
        $i=1;
        $stt = 1;
		$room_id = false;
        foreach($items as $key=>$value)
		{
            if($room_id != $value['room_id'])
            {
                $room_id = $value['room_id'];
                $final_items[$room_id] = array(
                                                'id'=>$room_id,
                                                'stt'=>$stt++,
                                                'room_name'=>$value['room_name'],
                                                'cleanup'=>array($i=>$value,),
                                                'total_clean'=>2,//de = 2 de rowsan cho chuan thuc te la total clean = 1
                                                );
            }
            else
            {
                $final_items[$room_id]['cleanup'][$i] = $value;
                $final_items[$room_id]['total_clean']++;
            }
            $i++;
		}
        //System::debug($final_items);
        $this->print_all_pages($final_items);
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
            /*
			$this->group_function_params = array(
				    'total_quantity'=>0,
					'total_amount'=>0,
				);
            */
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
        //$last_group_function_params = $this->group_function_params;	
		/*
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
        */
		$this->parse_layout('report',array(
				'items'=>$items,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)+$this->map
		);
	}
}
?>