<?php
class GuestTypeReportForm extends Form
{
    function GuestTypeReportForm()
    {
        Form::Form('GuestTypeReportForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
    }
    function draw()
    {
        require_once 'packages/core/includes/utils/time_select.php';
	require_once 'packages/core/includes/utils/lib/report.php';
        $this->map = array();
        if(Url::get('date'))
        {
            $this->map['date'] = Url::get('date');
        }
        else
        {
            $this->map['date'] = date('d/m/Y');
            $_REQUEST['date'] = $this->map['date'];
        }
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
        $cond ='';
        if($portal_id != 'ALL')
        {
            $cond.=' AND reservation.portal_id = \''.$portal_id.'\' '; 
        }
        
        $day_orc = Date_Time::to_orc_date($this->map['date']);
        //echo $day_orc;
        //Lay cac loai khach
        $sql = '
                select
                    guest_type.*
                from guest_type
                order by group_name DESC, id
                ';
        
        $guest_type = DB::fetch_all($sql);
        $this->map['guest_type'] = $guest_type;
        
        //System::debug($guest_type);
        $group_name = array();
        foreach($guest_type as $k=>$v)
		{
            if(!isset($group_name[$v['group_name']]))
                $group_name[$v['group_name']]=array('group_name'=>$v['group_name'],'colspan'=>1);
            else
                $group_name[$v['group_name']]['colspan']++;
		}
        $this->map['group_name'] = $group_name;
        //System::debug($group_name);
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
                order by 
                    country.priority,
                    reservation_room_code ';// 
        
        //echo $sql;
        $report = new Report;
        $report->items = DB::fetch_all($sql);
        if(User::is_admin())
        {
            //System::debug($report->items);
        }
        //System::debug($report->items);
        $nationality = array();
        //$guest_type = array();
        foreach($report->items as $key=>$item)
		{
            //neu chua co quoc tich nay thi them moi vao mang
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
                    //$nationality[$item['nationality_id']]['TOTAL NIGHT ROOM'] = 0;
                    $nationality[$item['nationality_id']]['total_room'] = 0;
        		}  
            }
            //dem so khach
            foreach($guest_type as $k=>$v)
    		{
                //$nationality[$item['nationality_id']][$v['name']] = 0;
                if($item['guest_type_id']==$k)
                {
                    $nationality[$item['nationality_id']][$v['name']]++;
                    $nationality[$item['nationality_id']]['TOTAL']++;
                    //$nationality[$item['nationality_id']]['TOTAL NIGHT GUEST']+=$item['night'];
                    if($item['group_name']=='WALK_IN')
                        $nationality[$item['nationality_id']]['WALK_IN']++;
                    else
                        $nationality[$item['nationality_id']]['TRAVEL']++;
                    
                    if($item['is_online']==1)
                        $nationality[$item['nationality_id']]['IS_ONLINE']++;
                        
                    if(date('d/m/Y',$item['time_in']) == $this->map['date'])
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
        
        
        //hqua dem trong 1 vong for rat pro nhung hnay k hieu sao doc lai khong hieu j`, danh` 
        // phai lam them 1 vong for nua de dem so khach'
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
        $this->map['nationality'] = $nationality;
        //System::debug($nationality);
        //dem tong
        $total = array();
        foreach($guest_type as $k=>$v)
		{
            $total[$v['name']] = 0;
            $total[$v['name'].' today'] = 0;
		}
        
        $total['WALK_IN'] = 0;
        $total['WALK_IN today'] = 0;
        $total['TRAVEL'] = 0;
        $total['TRAVEL today'] = 0;
        $total['IS_ONLINE'] = 0;
        $total['IS_ONLINE today'] = 0;
        $total['TOTAL'] = 0;
        $total['TOTAL today'] = 0;
        $total['TOTAL NIGHT GUEST'] = 0;
        $total['total_room'] = 0;
        
        foreach($nationality as $k=>$v)
        {
            foreach($total as $k1=>$v1)
            {
                $total[$k1]+= $v[$k1];
            }
        }
        $this->map['total'] = $total;  
        if(User::is_admin())
        {
            //System::debug($nationality);
        }
        $this->parse_layout('report',$this->map);
    }
}

?>