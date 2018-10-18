<?php
class BanquetRevenueReportForm extends Form
{
	function BanquetRevenueReportForm()
	{
		Form::Form('BanquetRevenueReportForm');
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
        
		$sql = '
			SELECT * FROM
			(
				SELECT 
					party_reservation.*,
                    party_type.name as party_type_name,
					ROW_NUMBER() OVER (ORDER BY party_reservation.id ) as rownumber
				FROM 
                    party_reservation
                    inner join party_type on party_reservation.party_type = party_type.id
				WHERE 
                    '.$cond.'
                ORDER BY
					party_reservation.id
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
            $items[$key]['checkin_time'] = date('H\h:i  d/m/Y',$value['checkin_time']);
            $items[$key]['checkout_time'] = date('H\h:i  d/m/Y',$value['checkout_time']);
            //$items[$key]['price'] = System::display_number($value['price']);
            //$items[$key]['quantity'] = System::display_number($value['quantity']);
            if($value['status'] != 'CANCEL')
                $items[$key]['total'] = System::display_number($value['deposit_1'] + $value['deposit_2'] + $value['deposit_3'] + $value['deposit_4'] +$value['total']);
            if($value['status'] == 'CANCEL')
                $items[$key]['total'] = System::display_number($value['deposit_1'] + $value['deposit_2'] + $value['deposit_3'] + $value['deposit_4']);
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
			if($temp=System::calculate_number($item['total']))
			{
				$this->group_function_params['total_amount'] += $temp;
			}			
		}	
        //System::debug($items);
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