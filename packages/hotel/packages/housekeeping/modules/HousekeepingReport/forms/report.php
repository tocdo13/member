<?php
class HousekeepingReportForm extends Form
{
    function HousekeepingReportForm()
    {
        Form::Form('HousekeepingReportForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');      
    }
    
    function draw()
    {
		//require_once 'packages/core/includes/utils/lib/report.php';	
        
        $this->map = array();
        
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):32;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        
        
        //$this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
        //$_REQUEST['to_date'] = $this->map['to_date'];
        
        $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):(date('d/m/Y'));
        $_REQUEST['from_date'] = $this->map['from_date'];
        
        $this->map['from_time'] = Url::get('from_time')?Url::get('from_time'):date("H:i");
        $_REQUEST['from_time'] = $this->map['from_time'];
        
        //$this->map['to_time'] = Url::get('to_time')?Url::get('to_time'):'23:59';
        //$_REQUEST['to_time'] = $this->map['to_time'];
         
        $from_time_view = Date_Time::to_time($this->map['from_date']) + $this->calc_time($this->map['from_time']) +59;                                
        //$to_time_view = Date_Time::to_time($this->map['to_date']) + $this->calc_time($this->map['to_time']);
        
        $cond =' 1=1 ';
        
        $from_date = Date_Time::to_orc_date(Date('d/m/y',$from_time_view));
        //$to_date = Date_Time::to_orc_date($this->map['to_date']);
         //$cond .= ' AND room_status.in_date=\''.$from_date.'\'';
         $cond_room_status = ' AND room_status.in_date=\''.$from_date.'\'';
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list());
        
        $portal_id = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
        $_REQUEST['portal_id'] = $portal_id;                       
        
        if($portal_id != 'ALL')
        {
            $cond.=' AND room.portal_id = \''.$portal_id.'\' '; 
        }
        $floor = DB::fetch_all('select distinct room.floor as id from room ');
        $this->map['floor_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list($floor);
        
        if(Url::get('floor_id')!='ALL' && Url::get('floor_id')!='')
        {
            $cond.=' AND room.floor = \''.Url::get('floor_id').'\' '; 
        }
        
        $room = DB::fetch_all('select room.id, room.name,
                                CASE 
                                    WHEN REGEXP_REPLACE(room.name,\'([0-9])\',\'\') is null
                                    THEN to_number(room.name)
                                END  number_room_name from room order by number_room_name');
        $this->map['room_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list($room);
        
        if(Url::get('room_id')!='ALL'&& Url::get('room_id')!='')
        {
            $cond.=' AND room.id = \''.Url::get('room_id').'\' '; 
        }
        
        $this->map['house_status_list'] = array('ALL'=>Portal::language('all'),'DIRTY'=>'DIRTY','READY'=>'READY','REPAIR'=>'REPAIR');
        
        if(Url::get('house_status')!='ALL'&& Url::get('house_status')!='')
        {
            if(Url::get('house_status')!='READY')
            {
                $cond.=' AND room_status.house_status = \''.Url::get('house_status').'\' ';
            }
            else
            {
                $cond.=' AND (room_status.house_status is null )';
            }
        }
        
        $this->map['fo_status_list'] = array('ALL'=>Portal::language('all'),'ARRIVAL'=>'ARRIVAL','DEPARTURE'=>'DEPARTURE','DUEOUT'=>'DUEOUT','OCCUPIED'=>'OCCUPIED','NOSHOW'=>'NOSHOW');
        
        if(Url::get('fo_status')!='ALL' && Url::get('fo_status')!='')
        {
            if(Url::get('fo_status')=='ARRIVAL')
            {
                $cond.=' AND (reservation_room.status = \'BOOKED\' and reservation_room.arrival_time = \''.$from_date.'\' and reservation_room.time_in > \''.$from_time_view.'\')';
            }
            else if(Url::get('fo_status')=='DEPARTURE')
            {
                $cond.=' AND (reservation_room.status = \'CHECKOUT\' and reservation_room.departure_time = \''.$from_date.'\')';
            }
            else if(Url::get('fo_status')=='DUEOUT')
            {
                $cond.=' AND (reservation_room.status = \'CHECKIN\' and reservation_room.departure_time = \''.$from_date.'\')';
            }
            else if(Url::get('fo_status')=='OCCUPIED')
            {
                $cond.=' AND (reservation_room.status = \'CHECKIN\' and reservation_room.departure_time > \''.$from_date.'\')';
            }
            else if(Url::get('fo_status')=='NOSHOW')
            {
                $cond.=' AND (reservation_room.status = \'BOOKED\' and reservation_room.time_in < \''.$from_time_view.'\')';
            }
        }      
        /*$count_traveller =DB::fetch_all('
			SELECT 
				room.id
				,count(reservation_traveller.id) as num
			FROM
				room
				left join reservation_room on reservation_room.room_id = room.id
                left join reservation on reservation_room.reservation_id = reservation.id
                left join room_status on room_status.reservation_room_id = reservation_room.id'.$cond_room_status.'
                left join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
            WHERE
				'.$cond.'             
			GROUP BY room.id
			');
            System::debug($count_traveller);
        /*System::debug('SELECT 
				room.id
				,count(reservation_traveller.id) as num
			FROM
				room
				left join reservation_room on reservation_room.room_id = room.id
                left join room_status on room_status.reservation_room_id = reservation_room.id
                left join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
            WHERE
				'.$cond.'             
			GROUP BY room.id
			'); */           
        $sql = 'SELECT
    				ROW_NUMBER() OVER ( ORDER BY room.id) as id,
                    room.id as code,
                    room.name as room_name,
                    traveller.first_name ||\' \'|| traveller.last_name as guest_name,
                    country.name_2 as nationality,
                    CASE
                        WHEN room_status.house_status is not null
                        THEN room_status.house_status
                    ELSE \'READY\'
                    END hk_status,
                    CASE
                        WHEN reservation_room.status is null
                        THEN \'\'
                    ELSE 
                        CASE
                            WHEN (reservation_room.status = \'BOOKED\' and reservation_room.arrival_time = \''.$from_date.'\' and reservation_room.time_in > \''.$from_time_view.'\')
                            THEN \'ARRIVAL\'
                        ELSE 
                            CASE
                             WHEN (reservation_room.status = \'CHECKOUT\' and reservation_room.departure_time = \''.$from_date.'\')
                             THEN \'DEPARTURE\'
                            ELSE
                               CASE
                                 WHEN (reservation_room.status = \'CHECKIN\' and reservation_room.departure_time = \''.$from_date.'\')
                                 THEN \'DUEOUT\'
                               ELSE
                                   CASE
                                     WHEN (reservation_room.status = \'CHECKIN\' and reservation_room.departure_time > \''.$from_date.'\')
                                     THEN \'OCCUPIED\'
                                   ELSE
                                        CASE
                                         WHEN (reservation_room.status = \'BOOKED\' and reservation_room.time_in < \''.$from_time_view.'\')
                                         THEN \'NOSHOW\'
                                        ELSE \'\'     
                                        END     
                                   END  
                               END 
                            END    
                        END 
                    END fo_status,
                    CASE
                        WHEN reservation_room.time_in is null
                        THEN 0
                    ELSE reservation_room.time_in
                    END time_in,
                    CASE
                        WHEN reservation_room.time_out is null
                        THEN 0
                    ELSE reservation_room.time_out
                    END time_out,
                    CASE
                        WHEN reservation.note is not null
                        THEN reservation.note
                    ELSE \'\'
                    END special_request,
                    CASE 
                        WHEN REGEXP_REPLACE(room.name,\'([0-9])\',\'\') is null
                        THEN to_number(room.name)
                    END  number_room_name,
                    room_status.id as room_status_id
    			FROM 
                    room
                    inner join room_level on room_level.id=room.room_level_id
                    left join room_status ON room_status.room_id=room.id '.$cond_room_status.'
                    left join reservation_room on reservation_room.room_id = room.id and reservation_room.departure_time >= \''.$from_date.'\' and reservation_room.arrival_time <= \''.$from_date.'\'
                    left join reservation on reservation_room.reservation_id = reservation.id
                    left join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                    left join traveller on traveller.id = reservation_traveller.traveller_id
                    left join country on traveller.nationality_id = country.id
    			WHERE 
    				'.$cond.' and room_level.is_virtual=0
    			ORDER BY
    				number_room_name asc,time_in asc,room_status_id desc';
        
        
        $items = DB::fetch_all($sql);
        
        $count_traveller = array();
        $i = 0;$j =0;
        $code=false;
        $room_name = false;
        $guest_name =false;
        
        foreach($items as $key => $value)
        {
            if($room_name!=$value['room_name'])
            {
                $room_name = $value['room_name'];
                $guest_name = $value['guest_name'];
                if($code != $value['code'])
                {
                    $code = $value['code'];
                    $i=0;
                    $items[$key]['stt'] = $j++;
                    
                }
                if($code == $value['code'])
                {
                    $i++;
                    $count_traveller[$value['code']]['num'] = $i;
                }
            }
            else
            {
                if($guest_name!=$value['guest_name'])
                {
                    $guest_name = $value['guest_name'];
                    if($code != $value['code'])
                    {
                        $code = $value['code'];
                        $i=0;
                        $items[$key]['stt'] = $j++;
                        
                    }
                    if($code == $value['code'])
                    {
                        $i++;
                        $count_traveller[$value['code']]['num'] = $i;
                    }
                }
                else
                    unset($items[$key]);
            }
        }
		$this->print_all_pages($items,$count_traveller);
    }
    
    
    
    function print_all_pages($items,$count_traveller)
	{
		$count = 0;
		$total_page = 1;
		$pages = array();
		foreach($items as $key=>$item)
		{
		    if(isset($items[$key]['stt']))
            {
                $count+= 1;
            }
            //count >= so dong tren 1 trang thi reset ve 0 va tang so trang len 1
            if($count>$this->map['line_per_page'])
        	{
        	   
        		$count = 1;
        		$total_page++;
        	}  
			$pages[$total_page][$key] = $item;  
			
		}
        
		if(sizeof($pages)>0)
		{
			/*$this->group_function_params = array(
					'total'=>0,
					'total_before_tax'=>0,
				);*/
            $this->map['real_total_page']=count($pages);
            $this->map['real_page_no'] = 1;
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $page_no,$total_page,$count_traveller);
                $this->map['real_page_no'] ++;
			}
		}
		else
		{
			$this->map['real_total_page'] = 0;
            $this->map['real_page_no'] = 0;
			$this->parse_layout('report',$this->map+
				array(
					'page_no'=>0,
					'total_page'=>0
				)
			);
		}
	}
    

	
    function print_page($items, $page_no, $total_page,$count_traveller)
	{
		/*$last_group_function_params = $this->group_function_params;
		foreach($items as $item)
		{
            //System::debug($item);
            $this->group_function_params['total']+=$item['total'];
            $this->group_function_params['total_before_tax']+=$item['total_before_tax'];
		}*/
        if($page_no>=$this->map['start_page'])
		{		
    		$this->parse_layout('report',array(
    				'items'=>$items,
                    'count_traveller'=>$count_traveller,
    				'page_no'=>$page_no,
    				'total_page'=>$total_page,
    			)+$this->map
    		);
        }
        //'last_group_function_params'=>$last_group_function_params,
		//'group_function_params'=>$this->group_function_params,
	}
    function calc_time($string)
    {
        $arr = explode(':',$string);
        //System::debug($arr);
        return $arr[0]*3600 + $arr[1]*60;
    }
}

?>