<?php
class CompensationReportForm extends Form
{
    function CompensationReportForm()
    {
        Form::Form('CompensationReportForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js'); 
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');      
    }
    
    function draw()
    {
		//require_once 'packages/core/includes/utils/lib/report.php';	
        
        $this->map = array();
        
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):32;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        
        
        $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
        $_REQUEST['to_date'] = $this->map['to_date'];
        //dau thang
        $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):('1/'.date('m/Y'));
        $_REQUEST['from_date'] = $this->map['from_date'];
        
        $cond =' 1=1 ';
        
        $from_date = Date_Time::to_orc_date($this->map['from_date']);
        $to_date = Date_Time::to_orc_date($this->map['to_date']);
         
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
            $cond.=' AND housekeeping_invoice.portal_id = \''.$portal_id.'\' '; 
        }
        
        $this->map['room_status_list'] = array(''=>Portal::language('all'),'CHECKIN'=>'CHECKIN','CHECKOUT'=>'CHECKOUT');
        if(Url::get('room_status'))
             $cond.=' AND reservation_room.status = \''.Url::get('room_status').'\' ';
        
        $cond .= ' and FROM_UNIXTIME(housekeeping_invoice.time)>= \''.$from_date.'\' and FROM_UNIXTIME(housekeeping_invoice.time)<= \''.$to_date.'\'  and housekeeping_invoice.type=\'EQUIP\'';
                        
        $sql = '
    			SELECT 
    				* 
    			FROM
    				(
                    SELECT
                        ROW_NUMBER() OVER (ORDER BY housekeeping_invoice.id Desc) as row_num,
    					housekeeping_invoice.*,
                        reservation_room.status as reservation_room_status,
                        room.name as room_name
    				FROM 
                        housekeeping_invoice
                        inner join reservation_room on housekeeping_invoice.reservation_room_id = reservation_room.id
                        inner join room on room.id = reservation_room.room_id
    				WHERE 
    					'.$cond.'
    				ORDER BY
    					housekeeping_invoice.id Desc
    				)
    			WHERE
    				row_num > '.(($this->map['start_page']-1)*$this->map['line_per_page']).' AND row_num<='.($this->map['no_of_page']*$this->map['line_per_page']).'
    		';
        
        
        $items = DB::fetch_all($sql);
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
					'total'=>0,
					'total_before_tax'=>0,
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
            //System::debug($item);
            $this->group_function_params['total']+=$item['total'];
            $this->group_function_params['total_before_tax']+=$item['total_before_tax'];
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