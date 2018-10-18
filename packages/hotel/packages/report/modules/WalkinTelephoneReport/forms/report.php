<?php
class WalkinTelephoneReportForm extends Form
{
	function WalkinTelephoneReportForm()
	{
		Form::Form('WalkinTelephoneReportForm');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw()
	{
	   
        $this->map = array();
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):99;
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        $this->map['customer_code_list'] = array(''=>Portal::language('All'))+String::get_list( DB::fetch_all('Select code as id, name from customer where code = \'TL\' or code = \'WI\'') ); 
        $this->map['date'] = Url::get('date')?Url::get('date'):date('d/m/Y');
        $_REQUEST['date'] = $this->map['date'];
		$cond = ' 1=1';
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
        if(Url::get('portal_id'))
            $portal_id = Url::get('portal_id');
        else
            $portal_id = PORTAL_ID;
            $_REQUEST['portal_id'] = PORTAL_ID;                       
        
        if($portal_id != 'ALL')
            $cond.=' AND reservation.portal_id = \''.$portal_id.'\' ';
        if(Url::get('customer_code'))
            $cond.=' AND customer.code = \''.Url::get('customer_code').'\' ';
        else
            $cond.=' AND ( customer.code = \'TL\' OR customer.code = \'WI\' )';
    	$sql = '
		SELECT  * FROM
		(
            SELECT 
                reservation_room.id,
                reservation_room.id as reservation_room_id,
                reservation.id as reservation_id,
                customer.name as customer_name,
                room.name as room_name,
                to_char(reservation_room.arrival_time, \'DD/MM/YYYY\') as arrival_time,
                to_char(reservation_room.departure_time, \'DD/MM/YYYY\') as departure_time,
                ROW_NUMBER() OVER (ORDER BY reservation_room.id ) as rownumber
            FROM 
                reservation_room 
                inner join reservation on reservation.id = reservation_room.reservation_id
                left join room on reservation_room.room_id = room.id
                inner join customer on reservation.customer_id = customer.id
                inner join room_status on room_status.reservation_room_id = reservation_room.id and room_status.in_date = \''.Date_Time::to_orc_date($this->map['date']).'\'
            WHERE 
                '.$cond.'
                and reservation_room.status != \'CANCEL\'
                and reservation_room.departure_time > \''.Date_Time::to_orc_date($this->map['date']).'\'	
            ORDER BY 
                reservation_room.id
		)	
		WHERE
            rownumber > '.(($this->map['start_page']-1)*$this->map['line_per_page']).' AND rownumber<='.($this->map['no_of_page']*$this->map['line_per_page']).'
        ';
		$items = DB::fetch_all($sql);
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
				    'total'=>0,
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
		  $this->group_function_params['total'] ++;		
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