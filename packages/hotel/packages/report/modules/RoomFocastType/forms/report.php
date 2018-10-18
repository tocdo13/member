<?php
class RoomFocastTypeFormByMonth extends Form
{
	function RoomFocastTypeFormByMonth()
	{
		Form::Form('RoomFocastTypeFormByMonth');
		$this->link_js("packages/core/includes/js/jquery/datepicker.js");
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_css("skins/default/report.css");
	}
	function draw()
	{	   	   
        if(1==1)
        {
            require_once 'packages/core/includes/utils/time_select.php';
    		require_once 'packages/core/includes/utils/lib/report.php';
            $this->map = array();
            $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
            $_REQUEST['from_date'] = $this->map['from_date'];
            $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):(date('d/m/Y',Date_Time::to_time($this->map['from_date'])+518400));
            $_REQUEST['to_date'] = $this->map['to_date'];
            
            
    		$start_time = Date_Time::to_time($this->map['from_date']);
    		$start_date = Date_time::to_orc_date($this->map['from_date']);
            
    		$end_time = Date_Time::to_time($this->map['to_date']);
    		$end_date = Date_time::to_orc_date($this->map['to_date']);
            
            $days = array();
            for($i=$start_time; $i<=$end_time; $i+=(24*3600))
            {
                $days[date('d/m/Y',$i)]['id'] = date('d/m',$i);
                $days[date('d/m/Y',$i)]['day'] =  date('d/m/Y',$i);
                $days[date('d/m/Y',$i)]['total'] = 0;
                $days[date('d/m/Y',$i)]['total_repair'] = 0;
                $days[date('d/m/Y',$i)]['total_room'] = 0;
            }
            
            $sql = '
    			select 
    				count(*) as acount
    				,room_level.id as id 
    				,room_level.name as name
    			from 
    				room_level 
    				left join room on room_level.id = room.room_level_id
    			where 
    				(room_level.is_virtual is null or room_level.is_virtual <> 1)
                    and room_level.portal_id = \''.PORTAL_ID.'\'
                    and room.close_room=1
    			group by 
    				room_level.id
    				,room_level.name
                order by 
                    room_level.name';
            $room_level_count = DB::fetch_all($sql);
            $this->map['total'] = 0;
            foreach($room_level_count as $key=>$value)
            {
                //$this->map['total'] += $value['acount'];
                $key_count = 0;
                for($i=$start_time; $i<=$end_time; $i+=(24*3600))
                {
                    /** Manh them de lay so luong phong thuc su trong ngay dc xet **/
                    if($his_in_date = DB::fetch('select max(in_date) as in_date from room_history where in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$i)).'\' and portal_id=\''.PORTAL_ID.'\'','in_date'))
                    {
                        $total_room = DB::fetch('select 
                                                    count(rhd.room_id) as total_room 
                                                from
                                                    room_history_detail rhd
                                                    inner join room_history rh on rh.id=rhd.room_history_id
                                                    inner join room_level on room_level.id = rhd.room_level_id
                                                where
                                                    rh.in_date=\''.$his_in_date.'\'
                                                    and rh.portal_id=\''.PORTAL_ID.'\'
                                                    and rhd.close_room=1
                                                    and room_level.is_virtual = 0
                                                    and room_level.id='.$value['id'].'
                                                    ','total_room');
                        if($key_count==0){
                            $value['acount'] = $total_room;
                        }else{
                            if($total_room>$value['acount'])
                                $value['acount'] = $total_room;
                        }
                        
                    }
                    elseif($his_in_date = DB::fetch('select min(in_date) as in_date from room_history where in_date>\''.Date_Time::to_orc_date(date('d/m/Y',$i)).'\' and portal_id=\''.PORTAL_ID.'\'','in_date'))
                    {
                        $total_room = DB::fetch('select 
                                                    count(rhd.room_id) as total_room 
                                                from
                                                    room_history_detail rhd
                                                    inner join room_history rh on rh.id=rhd.room_history_id
                                                    inner join room_level on room_level.id = rhd.room_level_id
                                                where
                                                    rh.in_date=\''.$his_in_date.'\'
                                                    and rh.portal_id=\''.PORTAL_ID.'\'
                                                    and rhd.close_room=1
                                                    and room_level.is_virtual = 0
                                                    and room_level.id='.$value['id'].'
                                                    ','total_room');
                        if($key_count==0){
                            $value['acount'] = $total_room;
                        }else{
                            if($total_room>$value['acount'])
                                $value['acount'] = $total_room;
                        }
                    }
                    else
                    {
                        $total_room = $value['acount'];
                    }
                    
                    /** end manh **/
                    $room_level_count[$key]['child'][date('d/m/Y',$i)]['total'] = 0;
                    $room_level_count[$key]['child'][date('d/m/Y',$i)]['total_repair'] = 0;
                    $room_level_count[$key]['child'][date('d/m/Y',$i)]['total_room'] = 0;
                    $room_level_count[$key]['child'][date('d/m/Y',$i)]['acount'] = $total_room;
                    $key_count++;
                }
                $this->map['total'] += $value['acount'];
                $room_level_count[$key]['acount'] = $value['acount'];
            }
            //System::debug($room_level_count);
    		$sql = 'select 
    					count(room_status.id) as acount
    					,to_char(in_date,\'DD/MM/YYYY\') as in_date
                        ,reservation_room.room_level_id
    					,reservation_room.room_level_id || \'_\' || to_char(room_status.in_date,\'DD/MM/YYYY\') as id
    				from
                        room_status
    					inner join reservation_room on reservation_room.id = room_status.reservation_room_id
                        inner join reservation on reservation_room.reservation_id = reservation.id
                        inner join room_level on room_level.id = reservation_room.room_level_id
    					left join room on room.id = reservation_room.room_id					
    				where
                        (room_level.is_virtual is null or room_level.is_virtual <> 1)
                        and 
        			    (reservation_room.status !=\'CANCEL\')
     					and room_status.in_date>=\''.$start_date.'\'
    					and room_status.in_date<=\''.$end_date.'\'
                        and reservation.portal_id = \''.PORTAL_ID.'\'
                        and (reservation_room.departure_time > room_status.in_date or (reservation_room.departure_time = reservation_room.arrival_time and reservation_room.time_in>=(date_to_unix(room_status.in_date)+(6*3600))))
                        and (
                          reservation_room.change_room_to_rr is null 
                          or (reservation_room.change_room_to_rr is not null and from_unixtime(reservation_room.old_arrival_time)!=in_date)
                        )
    				group by
    					room_status.in_date
    					,reservation_room.room_level_id
    				ORDER BY
    					reservation_room.room_level_id
                        ';	
            $items = DB::fetch_all($sql);
            if(User::id()=='developer06')
            {
                //System::debug($sql);
                //System::debug($items);
            }
            foreach($items as $key_1=>$value_1)
            {
                $days[$value_1['in_date']]['total'] += $value_1['acount'];
                $room_level_count[$value_1['room_level_id']]['child'][$value_1['in_date']]['total'] += $value_1['acount'];
                $days[$value_1['in_date']]['total_room'] += $value_1['acount'];
                $room_level_count[$value_1['room_level_id']]['child'][$value_1['in_date']]['total_room'] += $value_1['acount'];
            }
            $sql_vd = '
                        Select
                            (room_level.id || \'_\' || to_char(extra_service_invoice_detail.in_date,\'DD/MM/YYYY\')) as id,
                            room_level.id as room_level_id,
                            room_level.brief_name, 
                            to_char( extra_service_invoice_detail.in_date, \'DD/MM/YYYY\' ) as in_date,
                            Sum(extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0)) as acount 
                        from
                            reservation_room
                            inner join extra_service_invoice on extra_service_invoice.reservation_room_id = reservation_room.id
                            inner join extra_service_invoice_detail on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
                            inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                            inner join room_level on room_level.id = reservation_room.room_level_id
                        where
                            extra_service_invoice.portal_id = \''.PORTAL_ID.'\'
                            and (extra_service.code=\'LATE_CHECKIN\')
                            and extra_service_invoice_detail.in_date >= \''.$start_date.'\'
                            and extra_service_invoice_detail.in_date <= \''.$end_date.'\'
                            AND (room_level.is_virtual is null or room_level.is_virtual <> 1)
                        GROUP BY
                            room_level.id,
                            room_level.brief_name, 
                            to_char( extra_service_invoice_detail.in_date, \'DD/MM/YYYY\' )
                        ORDER BY
                            to_char( extra_service_invoice_detail.in_date, \'DD/MM/YYYY\' )
                        ';
            $ex_items = DB::fetch_all($sql_vd);
           	//System::debug($sql_vd);
            foreach($ex_items as $key_2=>$value_2)
            {
                $days[$value_2['in_date']]['total'] += $value_2['acount'];
                $room_level_count[$value_2['room_level_id']]['child'][$value_2['in_date']]['total'] += $value_2['acount'];
            }
            //System::debug($room_level_count);
            //Kimtan: tính phong repair de tru di
            $sql = 'select 
    					count(room_status.id) as acount,
                        to_char(room_status.in_date,\'DD/MM/YYYY\') as in_date,
                        room_level.id as room_level_id,
                        room_level.id || \'_\' || to_char(room_status.in_date,\'DD/MM/YYYY\') as id
                    from room_status
                        inner join room on room_status.room_id = room.id
                        inner join room_level on room.room_level_id = room_level.id
                    where 
                        room_status.house_status = \'REPAIR\'
                        and room_status.in_date>=\''.$start_date.'\'
    					and room_status.in_date<=\''.$end_date.'\'
                        and room.portal_id = \''.PORTAL_ID.'\'
                    group by
    					room_status.in_date
    					,room_level.id
    				ORDER BY
    					room_level.id
                   ';
             $total_repair = DB::fetch_all($sql);
             foreach($total_repair as $key_3=>$value_3)
            {
                if(isset($days[$value_3['in_date']]))
                $days[$value_3['in_date']]['total_repair'] += $value_3['acount'];
                if(isset($room_level_count[$value_3['room_level_id']]['child'][$value_3['in_date']]))
                $room_level_count[$value_3['room_level_id']]['child'][$value_3['in_date']]['total_repair'] += $value_3['acount'];
            }      
            //end Kimtan: tính phong repair de tru di
    		//System::debug($room_level_count);
            $this->parse_layout('report',
    			array(
                        'days'=>$days,
                        'items'=>$room_level_count
    			     ) + $this->map
    		);
        }
        //xem theo nam
        	   
	}
}
?>