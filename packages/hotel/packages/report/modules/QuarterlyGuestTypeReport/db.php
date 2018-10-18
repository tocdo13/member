<?php
class QuarterlyGuestTypeReportDB extends Module
{
    // function return data of day to day
    static function get_data_for_days($from_date, $to_date, $cond)
    {
        $items = array();
        for ($i = $from_date; $i <= $to_date ; $i += 86400)
        {
            $day_orc = Date_Time::convert_time_to_ora_date($i);
            $one_day_data = QuarterlyGuestTypeReportDB::get_data_for_one_day($day_orc, $cond);
            foreach($one_day_data as $key => $value)
            {
                if(!isset($items[$key]))
                {
                    $items[$key] = $value;
                }
                else
                {
                    foreach($value as $l => $t)
                    {
                        if($l != 'nationality')
                            $items[$key][$l] += $t;
                    }
                }
            }
        }   
        return $items;
    }
    // function return data of one day
    static function get_data_for_one_day($day_orc, $cond)
    {
        require_once 'packages/core/includes/utils/time_select.php';
	    require_once 'packages/core/includes/utils/lib/report.php';
        //Lay cac loai khach
        $sql = '
                select
                    guest_type.*
                from guest_type
                order by group_name DESC, id
                ';
        
        $guest_type = DB::fetch_all($sql);
        $sql = 'select
                    ROW_NUMBER() OVER (ORDER BY reservation_room.id Desc) as id,
                    reservation_traveller.id as reservation_traveller_id,
                    reservation_room.id as reservation_room_code,
                    reservation.id as reservation_id,
                    traveller.first_name || \' \' || traveller.last_name as fullname,
                    COALESCE(country.id,999999) as nationality_id,
                    COALESCE(country.name_'.Portal::language().',\'Other country\')  as nationality,
                    reservation_room.arrival_time, reservation_room.departure_time, 
                    reservation_room.time_in, reservation_room.time_out,
                    reservation_room.departure_time - reservation_room.arrival_time as night,
                    reservation_room.status,
                    traveller.id as traveller_id,
                    COALESCE(guest_type.id,999999) as guest_type_id,
                    COALESCE(guest_type.name,\'Other\') as guest_type_name,
                    guest_type.group_name,
                    guest_type.is_online
                from 
                    reservation_room 
                    inner join reservation on reservation_room.reservation_id = reservation.id
                    inner join room_level on room_level.id = reservation_room.room_level_id
                    left outer join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                    left outer join traveller on reservation_traveller.traveller_id = traveller.id
                    left outer join country on traveller.nationality_id = country.id
                    left outer join guest_type on traveller.traveller_level_id = guest_type.id
                where
                    (room_level.is_virtual is null or room_level.is_virtual <> 1)
                    AND
                    (
                        (reservation_room.status = \'CHECKIN\' AND reservation_room.departure_time >= \''.$day_orc.'\' AND reservation_room.arrival_time <= \''.$day_orc.'\'
                            and (reservation_traveller.status !=\'CHECKOUT\'  )
                        )
                        OR
                        ( 
                            reservation_room.status =\'CHECKOUT\' and reservation_room.arrival_time = \''.$day_orc.'\' and reservation_room.departure_time = \''.$day_orc.'\'
                        )
                    )     
                    '.$cond.'
                    
                    and (traveller.first_name || \' \' || traveller.last_name) != \' \'
                    and reservation_room.change_room_to_rr is null                   
                order by 
                    country.priority,
                    reservation_room_code ';
        $report = new Report;
        $report->items = DB::fetch_all($sql);
        $nationality = array();
        foreach($report->items as $key=>$item)
		{
            if(!isset($nationality[$item['nationality_id']]))
            {
                $nationality[$item['nationality_id']] = array('nationality'=>$item['nationality']);
                foreach($guest_type as $k=>$v)
        		{
                    $nationality[$item['nationality_id']][$v['name']] = 0;
                    $nationality[$item['nationality_id']][$v['name'].' today'] = 0;
                    $nationality[$item['nationality_id']]['WALK_IN'] = 0;
                    $nationality[$item['nationality_id']]['WALK_IN today'] = 0;
                    $nationality[$item['nationality_id']]['TRAVEL'] = 0;
                    $nationality[$item['nationality_id']]['TRAVEL today'] = 0;
                    $nationality[$item['nationality_id']]['IS_ONLINE'] = 0;
                    $nationality[$item['nationality_id']]['IS_ONLINE today'] = 0;
                    $nationality[$item['nationality_id']]['TOTAL'] = 0;
                    $nationality[$item['nationality_id']]['TOTAL today'] = 0;
                    $nationality[$item['nationality_id']]['TOTAL NIGHT GUEST'] = 0;
                    $nationality[$item['nationality_id']]['total_room'] = 0;
        		}  
            }
            foreach($guest_type as $k=>$v)
    		{
                if($item['guest_type_id']==$k)
                {
                    $nationality[$item['nationality_id']][$v['name']]++;
                    $nationality[$item['nationality_id']]['TOTAL']++;
                    if($item['group_name']=='WALK_IN')
                        $nationality[$item['nationality_id']]['WALK_IN']++;
                    else
                        $nationality[$item['nationality_id']]['TRAVEL']++;
                    
                    if($item['is_online']==1)
                        $nationality[$item['nationality_id']]['IS_ONLINE']++;
                        
                    if(date('d/m/Y',$item['time_in']) == date('d/m/Y',Date_Time::to_time($day_orc)))
                    {
                        $nationality[$item['nationality_id']][$v['name'].' today']++;
                        $nationality[$item['nationality_id']]['TOTAL today']++;
                        if($item['group_name']=='WALK_IN')
                            $nationality[$item['nationality_id']]['WALK_IN today']++;
                        else
                            $nationality[$item['nationality_id']]['TRAVEL today']++;
                            
                        if($item['is_online']==1)
                            $nationality[$item['nationality_id']]['IS_ONLINE today']++;
                        
                    }
                }   
    		}
		}
        foreach($report->items as $key=>$item)
        {
            foreach($nationality as $k=>$v)
            {
                if($item['nationality']==$nationality[$k]['nationality'])
                {
                    $nationality[$k]['TOTAL NIGHT GUEST'] += $item['night'];
                }
            }
        }
        $res_id = false;
        foreach($report->items as $key=>$item)
        {
            if($item['reservation_room_code']!=$res_id)
            {
                if(!isset($nationality[$item['nationality_id']]['total_room']))
                    $nationality[$item['nationality_id']]['total_room'] =0;
                $nationality[$item['nationality_id']]['total_room'] ++;
                $res_id = $item['reservation_room_code'];
            }
                
        }
        return $nationality;
    }
}

?>