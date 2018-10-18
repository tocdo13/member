<?php
class MonthlyBanquetRevenueReportForm extends Form
{
	function MonthlyBanquetRevenueReportForm()
	{
		Form::Form('MonthlyBanquetRevenueReportForm');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw()
	{
        require_once 'packages/hotel/packages/banquet/includes/php/banquet.php';
        $this->map = array();
        
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):999;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        
        
        $party_room = get_banquet_room();
        $this->map['party_room_id_list'] = array(''=>Portal::language('All'))+String::get_list($party_room);
        $party_type= get_party_type();
        $this->map['party_type_id_list'] = array(''=>Portal::language('All'))+String::get_list($party_type);
        $this->map['status_list'] = array(''=>Portal::language('All'))+get_banquet_status();
        //System::debug($this->map);
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
            $cond.=' AND party_reservation.portal_id = \''.$portal_id.'\' '; 
        }
        
		$cond .= ' '
				.(URL::get('party_type_id')?'and party_reservation.party_type = '.URL::get('party_type_id').'':'') 
				.(URL::get('from_date')?' and party_reservation.checkin_time>=\''.Date_Time::to_time(URL::get('from_date')).'\'':'')
				.(URL::get('to_date')?' and party_reservation.checkin_time<\''.(Date_Time::to_time(URL::get('to_date'))+86400).'\'':'')
                .(URL::get('status')?' and party_reservation.status = \''.URL::get('status').'\'':'')
		;
        $rooms = DB::select_all('party_room');
        $party_reservation_rooms = DB::select_all('party_reservation_room');
        $reservation_detail_row = null;
        foreach($rooms as $key => $value)
        {
            $rooms[$key]['num_party_table'] = 0;
            $rooms[$key]['meeting_num_people'] = 0;
            $rooms[$key]['num_people'] = 0;
            $rooms[$key]['eating'] = 0;
            $rooms[$key]['drinking'] = 0;
            $rooms[$key]['service'] = 0;
            $rooms[$key]['meeting_eating'] = 0;
            $rooms[$key]['meeting_drinking'] = 0;
            $rooms[$key]['meeting_service'] = 0;
            $rooms[$key]['num_meeting'] = 0;
            $rooms[$key]['num_party'] = 0;
            foreach($party_reservation_rooms as $k => $v)
            {
                if ($v['party_room_id'] == $value['id'])
                {
                    //$rooms[$key]['party_reservation_id'][$k] = $v['party_reservation_id'];
                    $reservation_row = DB::fetch('select * from party_reservation where id = '.$v['party_reservation_id'] .'and'. $cond);
                    //System::debug($reservation_row);
                    if($reservation_row['id'] && $reservation_row['status'] != 'CANCEL')
                    {
                        $rooms[$key]['service'] += $v['price'];
                        if ($v['type'] == 1) 
                            $rooms[$key]['num_meeting'] += 1;
                        if ($v['type'] == 2)
                            $rooms[$key]['num_party'] +=1;
                        $reservation_detail_row = DB::fetch_all('select * from party_reservation_detail where party_reservation_id = '.$reservation_row['id']);
                        $rooms[$key]['num_party_table'] += $reservation_row['num_table'];
                        $rooms[$key]['meeting_num_people'] += $reservation_row['meeting_num_people'];
                        $rooms[$key]['num_people'] += $reservation_row['num_people'];
                        $rooms[$key]['meeting_drinking'] += $reservation_row['break_coffee']*$reservation_row['coffee_price'] + 
                        $reservation_row['water']*$reservation_row['water_price'];
                        if($reservation_detail_row != null)    
                        foreach($reservation_detail_row as $k2 => $v2)
                        {
                            if ($v2['type'] == 1)
                            {
                                $rooms[$key]['drinking'] += $v2['price']*$v2['quantity']; 
                            }
                            if($v2['type'] == 2)
                            {
                                $rooms[$key]['eating'] += $v2['price']*$v2['quantity']; 
                            }
                            if($v2['type'] == 3)
                            {
                                $rooms[$key]['service'] += $v2['price']*$v2['quantity']; 
                            }
                        }
                    }
                    if($reservation_row['id'] && $reservation_row['status'] == 'CANCEL')
                    {
                        $rooms[$key]['service'] += $reservation_row['deposit_1'] + $reservation_row['deposit_2'] +$reservation_row['deposit_3'] + $reservation_row['deposit_4'];
                    }
                }
            }   
        }
        //System::debug($rooms);
        $items = $rooms;
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
                    'total_num_meeting'=>0,
                    'total_num_party'=>0,
                    'total_num_party_table'=>0,
                    'total_num_meeting_table'=>0,
                    'total_meeting_num_people'=>0,
                    'total_num_people'=>0,
                    'total_eating'=>0,
                    'total_drinking'=>0,
                    'total_meeting_service'=>0,
                    'total_meeting_eating'=>0,
                    'total_meeting_drinking'=>0,
                    'total_service'=>0,
                    'total_meeting'=>0,
                    'total_party'=>0,
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
			if($temp=System::calculate_number($item['num_meeting']))
			{
				$this->group_function_params['total_num_meeting'] += $temp;
			}
            if($temp=System::calculate_number($item['num_party']))
			{
				$this->group_function_params['total_num_party'] += $temp;
			}
            if($temp=System::calculate_number($item['num_party_table']))
			{
				$this->group_function_params['total_num_party_table'] += $temp;
			}
            if($temp=System::calculate_number($item['meeting_num_people']))
			{
				$this->group_function_params['total_meeting_num_people'] += $temp;
			}
            if($temp=System::calculate_number($item['num_people']))
			{
				$this->group_function_params['total_num_people'] += $temp;
			}
            if($temp=System::calculate_number($item['eating']))
			{
				$this->group_function_params['total_eating'] += $temp;
			}
            if($temp=System::calculate_number($item['drinking']))
			{
				$this->group_function_params['total_drinking'] += $temp;
			}
            if($temp=System::calculate_number($item['service']))
			{
				$this->group_function_params['total_service'] += $temp;
			}
            if($temp=System::calculate_number($item['meeting_eating']))
			{
				$this->group_function_params['total_meeting_eating'] += $temp;
			}
            if($temp=System::calculate_number($item['meeting_drinking']))
			{
				$this->group_function_params['total_meeting_drinking'] += $temp;
			}
            if($temp=System::calculate_number($item['meeting_service']))
			{
				$this->group_function_params['total_meeting_service'] += $temp;
			}
			$this->group_function_params['total_meeting'] += System::calculate_number($item['meeting_service'] + $item['meeting_drinking'] + $item['meeting_eating']);
            $this->group_function_params['total_party'] += System::calculate_number($item['service'] + $item['drinking'] + $item['eating']);			
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