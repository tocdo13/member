<?php
class MassageTipReportForm extends Form
{
	function MassageTipReportForm()
	{
		Form::Form('MassageTipReportForm');
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
        $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
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
			$cond .= ' and massage_product_consumed.time_out < '.(Date_Time::to_time($this->map['to_date']) + 86400);
		}
        
        $cond .= ' and massage_product_consumed.status = \'CHECKOUT\' ';
        /* Kimtan cmt
        $sql = 
		'
        Select * FROM
        (
            SELECT id,full_name,total, ROWNUM as rownumber FROM
    		(
                SELECT 
                    massage_staff_room.staff_id as id,
                    massage_staff.full_name,
                    sum(massage_staff_room.tip) as total
                FROM 
                    massage_staff_room
                    INNER JOIN massage_staff ON massage_staff.id =  massage_staff_room.staff_id
                    INNER JOIN massage_reservation_room ON massage_reservation_room.id = massage_staff_room.reservation_room_id
                    INNER JOIN massage_product_consumed ON massage_product_consumed.reservation_room_id = massage_reservation_room.id AND massage_product_consumed.room_id = massage_staff_room.room_id
                WHERE 
                    '.$cond.'
                GROUP BY
                    massage_staff_room.staff_id,
                    massage_staff.full_name
                ORDER BY 
                    massage_staff.full_name
    		)
            WHERE total !=0       
        )
		WHERE
			 rownumber > '.(($this->map['start_page']-1)*$this->map['line_per_page']).' AND rownumber<='.($this->map['no_of_page']*$this->map['line_per_page']).'
		';*/
        //Kimtan: viet lai cau sql nay de tinh tien tip ko bi nhan 2 trong tr hop cung phong cung nv 2 dvu
        $sql = 
		'
        Select * FROM
        (
            SELECT id,full_name,total, ROWNUM as rownumber FROM
    		(
                SELECT staff_id as id,full_name,sum(total) as total FROM
                (
                    SELECT DISTINCT
                        massage_staff_room.reservation_room_id || massage_staff_room.room_id || massage_staff_room.staff_id as id,
                        massage_staff_room.staff_id,
                        massage_staff.full_name,
                        massage_staff_room.tip as total
                    FROM 
                        massage_staff_room
                        INNER JOIN massage_staff ON massage_staff.id =  massage_staff_room.staff_id
                        INNER JOIN massage_reservation_room ON massage_reservation_room.id = massage_staff_room.reservation_room_id
                        INNER JOIN massage_product_consumed ON massage_product_consumed.reservation_room_id = massage_reservation_room.id AND massage_product_consumed.room_id = massage_staff_room.room_id
                    WHERE 
                        '.$cond.'
                    ORDER BY 
                        massage_staff.full_name
                )
                GROUP BY  staff_id,full_name
            )
            WHERE total !=0       
        )
		WHERE
			 rownumber > '.(($this->map['start_page']-1)*$this->map['line_per_page']).' AND rownumber<='.($this->map['no_of_page']*$this->map['line_per_page']).'
		';
        // end Kimtan: viet lai cau sql nay de tinh tien tip ko bi nhan 2 trong tr hop cung phong cung nv 2 dvu
		$items = DB::fetch_all($sql);
        $full_name=array();
     
        if(User::id()=='developer06')
        {
            //System::debug($sql);
            //System::debug($items);
            
        }
        $i=1;
    	foreach($items as $key=>$item)
		{ 
			$items[$key]['stt'] = $i++;
			$items[$key]['total_amount']=System::display_number($item['total']);
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
            $this->group_function_params['total_amount']+=$item['total'];
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