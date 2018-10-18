<?php
class ReportNoteShiftReport extends Form
{
    function ReportNoteShiftReport()
    {
        Form::Form('ReportNoteShiftReport');
    	$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
    	$this->link_js('packages/core/includes/js/jquery/datepicker.js');       
    }
    
    function draw()
    {
        require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';	
        $cond ='';
        $cond2 ='';
        if(Url::get('date')){
            $this->day = Url::get('date');
        }else{
            $this->day = date('d/m/Y');     
        }
		$_REQUEST['date'] = $this->day;
        $this->map = array(); 
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):99;
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
		$this->map['date'] = $this->day;
        $day_orc = Date_Time::to_orc_date($this->day);
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
            $cond.=' AND reservation.portal_id = \''.$portal_id.'\' ';
            $cond2 .=' AND reservation_note.portal_id = \''.$portal_id.'\' ';  
        }  
        //System::debug($count_traveller);                       
        $sql = '
			SELECT * 
			FROM (select
					reservation_room.id
				   ,(CASE 
                        WHEN reservation_room.note is not null 
                        THEN reservation_room.note 
                    END ||      
                        CASE
                            WHEN room_status.note is not null
                            THEN (\' HK note: \' || room_status.note)
                        END) 
                    as note 
				   ,room.name
				   ,ROW_NUMBER() OVER (ORDER BY room.name) as rownumber
                from 
					reservation_room
                    inner join reservation on reservation.id = reservation_room.reservation_id
                    inner join room on reservation_room.room_id = room.id
                    inner join room_status on reservation_room.id = room_status.reservation_room_id
                where 
					(reservation_room.note is not null  OR room_status.note is not null)
                    AND (reservation_room.status = \'CHECKIN\' OR reservation_room.status = \'BOOKED\')
					AND reservation_room.departure_time>= \''.$day_orc.'\'
                    '.$cond.'
				)	
			WHERE rownumber > '.(($this->map['start_page']-1)*$this->map['line_per_page']).' AND rownumber<='.($this->map['no_of_page']*$this->map['line_per_page']).'';
			
        $items = DB::fetch_all($sql);
		
		$sql_note = '
			SELECT * 
			FROM (select
					(reservation_note.id || reservation_note.note) as id
				   ,reservation_note.note
				   ,\' \' as name
				   ,ROW_NUMBER() OVER (ORDER BY reservation_note.id) as rownumber
                from 
					reservation_note
                where  
					reservation_note.confirm is null
                    '.$cond2.'
					
				)	
			WHERE rownumber > '.(($this->map['start_page']-1)*$this->map['line_per_page']).' AND rownumber<='.($this->map['no_of_page']*$this->map['line_per_page']).'';
//AND reservation_note.CREATE_DATE = \''.Date_Time::to_orc_date(date('d/m/y')).'\'
		$notes = DB::fetch_all($sql_note);
		//System::Debug($notes);
        $i = 1;
		foreach($notes as $k => $note){
			$items[$k] = $note;	
		}
		foreach($items as $key=>$item){
           $items[$key]['stt'] = $i; 
		   $i++;
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
            $this->group_function_params['total']++;
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