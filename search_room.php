<?php 
    /** Ninh code 02/12/2017 **/
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    set_include_path(ROOT_PATH);
	require_once ROOT_PATH.'packages/core/includes/system/config.php';
    require_once ROOT_PATH.'packages/core/includes/system/database.php';
   
    if(Url::get('act') == 'LoadRoom')
    {
        /** lấy ra tên phòng muốn change **/
        $check_room = ' 
			select 
				reservation_room.id
				,concat(CONCAT(traveller.first_name,\' \'),traveller.last_name) as agent_name
				,reservation_room.room_id
                ,to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as departure_time
                ,reservation_room.time_out as time_out
			from 
				reservation_room 
				inner join room on room.id=reservation_room.room_id
                inner join room_level on room.room_level_id=room_level.id
				inner join room_status on room_status.RESERVATION_ID  =  RESERVATION_ROOM.RESERVATION_ID 
				LEFT OUTER JOIN traveller on traveller.id=reservation_room.traveller_id 
			where
				room.portal_id=\''.PORTAL_ID.'\' and reservation_room.status=\'CHECKIN\'
				and reservation_room.time_out > \''.time().'\'
				and (reservation_room.closed is null or reservation_room.closed = 0)
				and room_status.status = \'OCCUPIED\'
				and room_status.in_date = \''.Date_time::to_orc_date(date('d/m/Y')).'\'
                and reservation_room.departure_time >= \''.Date_time::to_orc_date(date('d/m/Y')).'\'
                and reservation_room.id = \''.Url::get('room_id').'\'
			order by
				room.name
		';
        $arr_reservation_rooms = DB::fetch_all($check_room);
        foreach($arr_reservation_rooms as $k=>$v)
        {
            $time_out = $v['time_out'];
            $departure_time = Date_Time::to_time($v['departure_time']);
        }
        $sql=('
			SELECT 
                room.id, 
                room.name || \' (\'|| room_level.brief_name ||\')\'  as name,
                room_status.house_status
            FROM 
                room 
                inner join room_level on room_level.id = room.room_level_id
                left join room_status on room.id = room_status.room_id and room_status.in_date = \''.Date_Time::to_orc_date(date('d/m/Y')).'\' and room_status.house_status is not null 
            ORDER by room.id, 
				room.floor, 
				room.position
		');
        $rooms = DB::fetch_all($sql);
        /** Lấy ra các phòng có trạng thái checkin-booked... **/
        $items = DB::fetch_all('
                                SELECT 
                                    room_status.room_id as id,
                                    room.name,
                                    room_level.brief_name as brief_name,
                                    room_status.house_status,
                                    to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') as arrival_time,
                                    reservation_room.time_in as time_in,
                                    room_status.status as status,
                                    reservation_room.time_out,
                                    reservation_room.status as reservation_room_status
                                FROM 
                                    room_status left join room on room.id = room_status.room_id
                                    inner join room_level on room_level.id = room.room_level_id
                                    left join reservation_room on reservation_room.id = room_status.reservation_room_id  
                                where 
                                    room_status.in_date =\''.Date_Time::to_orc_date(date('d/m/Y')).'\' 
                                    and room.portal_id =\''.PORTAL_ID.'\'
                                     and (
                                        room_status.house_status = \'REPAIR\'  
                                        or (
                                                room_status.status != \'AVAILABLE\' 
                                                and room_status.status != \'OCCUPIED\'  and room_status.status != \'CANCEL\' and room_status.status != \'NOSHOW\' and (room_status.status != \'NOSHOW\')
                                           ) 
                                        or 
                                           (
                                                room_status.status = \'OCCUPIED\' and room_status.in_date != reservation_room.departure_time 
                                           )
                                        or 
                                           (
                                                reservation_room.status = \'CHECKIN\' and room_status.status = \'OCCUPIED\' and room_status.in_date = reservation_room.departure_time
                                           )            
                                        )
                                ');
        /** lấy ra phòng có ngày check in == ngày check out của phòng change,giờ check in > giờ check out của phòng change **/
        foreach($items as $k=>$v)
        {
            if($v['status']=='BOOKED')
            {
                $day = Date_Time::to_time($v['arrival_time']);
                if(($v['time_in'] > $time_out) && ($day == $departure_time))
                {
                    unset($items[$k]);
                }else{
                }
            }
            if($v['reservation_room_status']=='CHECKOUT' and $v['time_out']<time() and isset($items[$k])){
                unset($items[$k]);
            }
        }
        foreach($rooms as $key => $value)
        {
            if(isset($items[$value['id']])){
                unset($rooms[$value['id']]);
            }else{
                if($value['house_status']=='DIRTY')
                {
                    $rooms[$key]['name'] = $value['name'].'---Dirty';
                }
            }
            
        }
        echo json_encode($rooms);      
    }
    /** /Ninh code 02/12/2017 **/
?>