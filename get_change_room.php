<?php
    date_default_timezone_set('Asia/Saigon');
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once 'packages/core/includes/system/config.php';
	require_once 'packages/core/includes/utils/vn_code.php';
    require_once 'packages/hotel/includes/php/hotel.php';
	if(Url::get('q'))
    {
		if(Url::get('from_room'))
        {
            $sql = '
    			select 
    				reservation_room.id
    				,room.name
                    ,room_level.brief_name
                    ,to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') as arrival_time
                    ,to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as departure_time
    			from 
    				reservation_room 
    				inner join room on room.id=reservation_room.room_id
                    inner join room_level on room.room_level_id=room_level.id
    				inner join room_status on room_status.RESERVATION_ID  =  RESERVATION_ROOM.RESERVATION_ID 
    				LEFT OUTER JOIN traveller on traveller.id=reservation_room.traveller_id 
    			where
    				room.portal_id=\''.PORTAL_ID.'\' and reservation_room.status=\'CHECKIN\'
    				and (reservation_room.closed is null or reservation_room.closed = 0)
    				and room_status.status = \'OCCUPIED\'
    				and room_status.in_date = \''.Date_time::to_orc_date(date('d/m/Y')).'\'
                    and reservation_room.departure_time >= \''.Date_time::to_orc_date(date('d/m/Y')).'\'
                    and LOWER(FN_CONVERT_TO_VN(room.name)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\'
    			order by
    				room.name
    		';
            $arr_reservation_rooms = DB::fetch_all($sql);
		    foreach($arr_reservation_rooms as $key=>$value)
            {
                echo $value['name'].'|'.$value['id'].'|'.'('.$value['brief_name'].')'.' Arrival:'.$value['arrival_time'].'-Departure:'.$value['departure_time']."\n";
            }
		}
        elseif(Url::get('to_room'))
        {
            $sql=('
    			SELECT 
                    room.id 
                    ,room.name
                    ,room_level.brief_name
                    ,room_status.house_status
                FROM 
                    room 
                    inner join room_level on room_level.id = room.room_level_id
                    left join room_status on room.id = room_status.room_id and room_status.in_date = \''.Date_Time::to_orc_date(date('d/m/Y')).'\' 
                WHERE 
                    room.portal_id =\''.PORTAL_ID.'\' 
                    and room.close_room=1
                    AND LOWER(FN_CONVERT_TO_VN(room.name)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\'
                    MINUS
                SELECT 
                    room_status.room_id
                    ,room.name
                    ,room_level.brief_name 
                    ,room_status.house_status 
                FROM 
                    room_status 
                    left join room on room.id = room_status.room_id
                    inner join room_level on room_level.id = room.room_level_id
                    left join reservation_room on reservation_room.id = room_status.reservation_room_id  
                WHERE 
                    room_status.in_date =\''.Date_Time::to_orc_date(date('d/m/Y')).'\' 
                    and room.portal_id =\''.PORTAL_ID.'\' 
                    and LOWER(FN_CONVERT_TO_VN(room.name)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\'
                    and (
                        room_status.house_status = \'REPAIR\' 
                        or (
                                room_status.status != \'AVAILABLE\' 
                                and room_status.status != \'OCCUPIED\'  and room_status.status != \'CANCEL\'
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
            $rooms = DB::fetch_all($sql);
            foreach($rooms as $key => $value)
            {
                if($value['house_status']=='DIRTY')
                {
                    echo $value['name'].'|'.$value['id'].'|'.'('.$value['brief_name'].')---Dirty'."\n";
                }
                else
                    echo $value['name'].'|'.$value['id'].'|'.'('.$value['brief_name'].')'."\n";
            }
            
        }
		DB::close();
	}
?>