<?php
class GuestMassageReportForm extends Form
{
	function GuestMassageReportForm()
	{
		Form::Form('GuestMassageReportForm');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw()
	{
		require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';
        $this->map = array();
        
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):32;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        
        
        $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
        $_REQUEST['to_date'] = $this->map['to_date'];
        //dau thang
        $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):('1/'.date('m/Y'));
        $_REQUEST['from_date'] = $this->map['from_date'];
        
		$cond = ' 1=1';
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list());
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
            $cond.=' AND massage_reservation_room.portal_id = \''.$portal_id.'\' '; 
        }
        
		if(Url::get('from_date'))
		{
			$cond .= ' and massage_product_consumed.time_out>='.Date_Time::to_time($this->map['from_date']);
		}
		if(Url::get('to_date'))
		{
			$cond .= ' and massage_product_consumed.time_out < '.( Date_Time::to_time($this->map['to_date']) + 86400 );
		}
		$inner = '
				inner join massage_product_consumed on massage_product_consumed.reservation_room_id = massage_reservation_room.id
                inner join massage_room on massage_product_consumed.room_id = massage_room.id
                LEFT OUTER JOIN reservation_room ON massage_reservation_room.hotel_reservation_room_id = reservation_room.id
                left outer join TRAVELLER on reservation_room.TRAVELLER_ID = TRAVELLER.ID 
                left outer join massage_reservation on massage_reservation_room.reservation_id = massage_reservation.id
                left outer join room on room.id = massage_reservation_room.hotel_reservation_room_id
                left outer join massage_guest on massage_guest.id = massage_reservation_room.guest_id
				';

		$sql = 
		'SELECT * FROM
		( SELECT 
         massage_reservation_room.id,
            (massage_product_consumed.time_out-massage_product_consumed.time_in)/60 as minutes,
            massage_product_consumed.time_out,
            massage_reservation_room.total_amount,
            massage_product_consumed.status,
            massage_reservation_room.full_name as TRAVELLER_name,--KID THEM DONG NAY DE HIEN THI KHACH
            concat(TRAVELLER.FIRST_NAME, concat(\' \',TRAVELLER.LAST_NAME))  as TRAVELLER_name_1,
            room.name as room_name,
            ROWNUM as rownumber
        FROM 
            massage_reservation_room
            '.$inner.'
        WHERE 
            '.$cond.'	
        ORDER BY 
            massage_product_consumed.time_out DESC
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
            $this->group_function_params['total']+=$item['total_amount'];
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