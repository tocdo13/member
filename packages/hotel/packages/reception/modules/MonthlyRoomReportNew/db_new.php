<?php
 class MonthlyRoomReportDBNew 
 {
    static function get_floor() {
        return DB::fetch_all('select room.floor as id,room.floor as name from room order by room.id,room.floor,room.position');
    }
    
    static function get_room_level()
    {
        return DB::fetch_all('select * from room_level WHERE room_level.portal_id = \''.PORTAL_ID.'\'');
    }
    
    static function get_room_status($start_date,$end_date)
    {
        $start_date_orc = Date_Time::to_orc_date($start_date);
        $end_date_orc = Date_Time::to_orc_date($end_date);
        
        $sql = '
                SELECT
                    room_status.id,
                    room_status.room_id,
                    TO_CHAR(room_status.in_date,\'DD/MM/YYYY\') as in_date,
                    room_status.house_status,
                    room_status.note,
                    TO_CHAR(room_status.start_date,\'DD/MM/YYYY\') as start_date,
                    room_level.is_virtual,
                    TO_CHAR(room_status.end_date,\'DD/MM/YYYY\') as end_date
                FROM
                    room_status
                    inner join room on room.id=room_status.room_id
                    inner join room_level on room_level.id=room.room_level_id
                WHERE
                    room_status.in_date>=\''.$start_date_orc.'\'
                    and room_status.in_date<=\''.$end_date_orc.'\'
                    and room_status.house_status is not null
                    and room.portal_id=\''.PORTAL_ID.'\'
                    and (room_status.house_status=\'REPAIR\' OR room_status.house_status=\'HOUSEUSE\')
                ORDER BY
                    room_status.room_id,room_status.house_status,room_status.note,room_status.in_date DESC
                ';
        $room_status = DB::fetch_all($sql);
        //System::debug($room_status);
        $items_group = array(); $key_repair = 1; $key_houseuse = 1; $key_close = 1;
        foreach($room_status as $key=>$value)
        {
            $id = $value['room_id'].'_'.$value['house_status'].'_'.Date_Time::to_time($value['start_date']).'_'.Date_Time::to_time($value['end_date']);
            if(!isset($items_group[$id]))
            {
                $items_group[$id]['room_id'] = $value['room_id'];
                $items_group[$id]['is_virtual'] = $value['is_virtual'];
                $items_group[$id]['house_status'] = $value['house_status'];
                $items_group[$id]['note'] = $value['note'];
                $items_group[$id]['start_date'] = $value['start_date'];
                $items_group[$id]['end_date'] = $value['end_date'];
                $items_group[$id]['from_date'] = $value['start_date'];
                $items_group[$id]['to_date'] = $value['end_date'];
            }
        }
        $room_status_close_sale = DB::fetch_all('
                                                SELECT
                                                    reservation_room.id,
                                                    reservation_room.room_id,
                                                    room_level.is_virtual,
                                                    reservation_room.status as house_status,
                                                    reservation_room.note,
                                                    reservation_room.time_in as start_date,
                                                    reservation_room.time_out as end_date,
                                                    TO_CHAR(reservation_room.arrival_time,\'DD/MM/YYYY\') as from_date,
                                                    TO_CHAR(reservation_room.departure_time,\'DD/MM/YYYY\') as to_date
                                                FROM
                                                    reservation_room
                                                    inner join room on reservation_room.room_id=room.id
                                                    inner join room_level on room_level.id=room.room_level_id
                                                WHERE
                                                    reservation_room.status=\'CLOSE\'
                                                    and reservation_room.arrival_time<=\''.$end_date_orc.'\' 
                                                    and reservation_room.departure_time>=\''.$start_date_orc.'\' 
                                                    and room_level.portal_id=\''.PORTAL_ID.'\'
                                                ');
        //System::debug($items_group);
        return $items_group+$room_status_close_sale;
    }
    
    static function get_booking_room($start_date,$end_date)
    {
        $start_date_orc = Date_Time::to_orc_date($start_date);
        $end_date_orc = Date_Time::to_orc_date($end_date);
        
        $sql = '
                SELECT
                    reservation_room.id,
                    TO_CHAR(reservation_room.arrival_time,\'DD/MM/YYYY\') as arrival_time,
                    TO_CHAR(reservation_room.departure_time,\'DD/MM/YYYY\') as departure_time,
                    reservation_room.time_in,
                    reservation_room.time_out,
                    reservation_room.room_level_id,
                    reservation_room.room_id,
                    reservation_room.price,
                    reservation_room.note,
                    reservation_room.do_not_move,
                    reservation_room.user_do_not_move,
                    reservation_room.note_do_not_move,
                    reservation_room.status,
                    UPPER(customer.name) as customer_name,
                    DECODE(traveller.gender,1,\' Mr.\',DECODE(traveller.gender,0,\' Ms.\',\'\'))|| traveller.last_name as traveller_name,
                    UPPER(reservation.booker) as booker, 
                    reservation.note as group_note,
                    reservation.id as reservation_id,
                    room_level.is_virtual,
                    case 
                        when reservation_room.arrival_time=reservation_room.departure_time
                        then 1
                        else reservation_room.departure_time-reservation_room.arrival_time
                    end as night
                FROM
                    reservation_room
                    inner join reservation on reservation.id=reservation_room.reservation_id
                    inner join customer on customer.id=reservation.customer_id
                    inner join room_level on room_level.id=reservation_room.room_level_id
                    left join reservation_traveller on reservation_traveller.reservation_room_id=reservation_room.id
                    left join traveller on traveller.id=reservation_traveller.traveller_id
                WHERE
                    reservation_room.arrival_time<=\''.$end_date_orc.'\' 
                    and reservation_room.departure_time>=\''.$start_date_orc.'\' 
                    --and (reservation_room.arrival_time!=reservation_room.departure_time)
                    and reservation_room.status!=\'CANCEL\'
                    and reservation_room.status!=\'NOSHOW\'  
                    and (reservation_room.room_id is not null and reservation_room.room_id!=0) 
                    and reservation.portal_id=\''.PORTAL_ID.'\'
                ORDER BY
                    night, reservation_room.room_level_id
                ';
        return DB::fetch_all($sql);
        
    }
    static function get_all_room($date_from,$date_to,$cond_order,$cond)
    {
        $date_from = Date_Time::to_orc_date($date_from);
        $date_to = Date_Time::to_orc_date($date_to);
        /**
         * Manh them de lay lich su phong trong khoang thoi gian
         *  + cach thuc hien:
         *      - lay ngay duoc luu trong lich su gan nhat voi from_date, uu tien ngay nho hon from_date
         *      - lay ngay duoc luu trong lich su gan nhat voi to_date, uu tien ngay nho hon to_date
         *      - lay lich su trong khoang 2 ngay vua lay duoc neu co
         *      - neu khong co thi lay phong nhu ban dau.
        **/
        $conds_history = '1=1';
        $history_min_date = '';
        $history_max_date = '';
        // lay ngay lich su gan voi from_date nhat lam min
        if( $history_in_date = DB::fetch("SELECT max(in_date) as in_date FROM room_history WHERE in_date<='".$date_from."' and portal_id='".PORTAL_ID."'","in_date") )
        {
            $history_min_date=$history_in_date;
        }
        elseif( $history_in_date = DB::fetch("SELECT min(in_date) as in_date FROM room_history WHERE in_date>'".$date_from."' and portal_id='".PORTAL_ID."'","in_date") )
        {
            $history_min_date=$history_in_date;
        }
        // lay ngay lic su gan nhat voi to_date lam max
        if( $history_in_date = DB::fetch("SELECT max(in_date) as in_date FROM room_history WHERE in_date<='".$date_to."' and portal_id='".PORTAL_ID."'","in_date") )
        {
            $history_max_date=$history_in_date;
        }
        elseif( $history_in_date = DB::fetch("SELECT min(in_date) as in_date FROM room_history WHERE in_date>'".$date_to."' and portal_id='".PORTAL_ID."'","in_date") )
        {
            $history_max_date=$history_in_date;
        }
        if($history_min_date!='' AND $history_max_date!='')
        {
            $conds_history = 'room_history.in_date>=\''.$history_min_date.'\' AND room_history.in_date<=\''.$history_max_date.'\'';
        }
        if($conds_history!='')
        {
            $history = DB::fetch_all('
                                    SELECT
                                        room_history_detail.id,
                                        room_history_detail.room_id,
                                        room_history_detail.name,
                                        room_history_detail.room_type_id,
                                        room_type.name as room_type_name,
                                        room_history_detail.room_level_id,
                                        room_level.brief_name as room_level_name,
                                        room_level.color,
                                        room_level.is_virtual
                                    FROM
                                        room_history_detail
                                        inner join room on room_history_detail.room_id=room.id
                                        inner join room_history on room_history_detail.room_history_id=room_history.id
                                        inner join room_type on room_history_detail.room_type_id=room_type.id 
                                        inner join room_level on room_history_detail.room_level_id=room_level.id
                                    WHERE
                                        '.$cond.' and '.$conds_history.'
                                        ANd room_history.portal_id = \''.PORTAL_ID.'\'
                                        AND room_level.portal_id = \''.PORTAL_ID.'\'
                                        AND room_history_detail.close_room=1
                                    ORDER BY
                                    '.$cond_order.'
                                    ');
            $rooms = array();
            foreach($history as $key=>$value)
            {
                $room_id = $value['room_id'];
                unset($value['id']); 
                unset($value['room_id']);
                $rooms[$room_id] = $value;
                $rooms[$room_id]['id'] = $room_id;
            }
        }
        else
        {
            $rooms = DB::fetch_all('
                                    select 
                                        room.id,
                                        room.name, 
                                        room.room_type_id,
                                        room_type.name as room_type_name,
                                        room.room_level_id,
                                        room_level.brief_name as room_level_name,
                                        room_level.color,
                                        room_level.is_virtual
                                    from 
                                        room 
                                        inner join room_type on room_type_id=room_type.id 
                                        inner join room_level on room_level_id=room_level.id 
                                    WHERE 
                                        '.$cond.' and room_level.portal_id = \''.PORTAL_ID.'\'
                                    ORDER BY
                                    '.$cond_order.'
                                ');
        }
        return $rooms;
        /** END MANH **/
    }
    
    static function get_booked_not_asign($from_date,$to_date)
    {
        $sql = '
                SELECT
                    reservation_room.id,
                    TO_CHAR(reservation_room.arrival_time,\'DD/MM/YYYY\') as arrival_time,
                    TO_CHAR(reservation_room.departure_time,\'DD/MM/YYYY\') as departure_time,
                    reservation_room.time_in,
                    reservation_room.time_out,
                    reservation_room.room_level_id,
                    reservation_room.price,
                    reservation_room.note,
                    reservation_room.do_not_move,
                    reservation_room.user_do_not_move,
                    reservation_room.note_do_not_move,                    
                    UPPER(customer.name) as customer_name,
                    DECODE(traveller.gender,1,\' Mr.\',DECODE(traveller.gender,0,\' Ms.\',\'\'))|| traveller.last_name as traveller_name,
                    UPPER(reservation.booker) as booker, 
                    reservation.note as group_note,
                    reservation.id as reservation_id,
                    room_level.is_virtual,
                    case 
                        when reservation_room.arrival_time=reservation_room.departure_time
                        then 1
                        else reservation_room.departure_time-reservation_room.arrival_time
                    end as night
                FROM
                    reservation_room
                    inner join reservation on reservation.id=reservation_room.reservation_id
                    inner join customer on customer.id=reservation.customer_id
                    inner join room_level on room_level.id=reservation_room.room_level_id
                    left join reservation_traveller on reservation_traveller.reservation_room_id=reservation_room.id
                    left join traveller on traveller.id=reservation_traveller.traveller_id
                WHERE
                    reservation_room.arrival_time<=\''.Date_Time::to_orc_date($to_date).'\' 
                    and reservation_room.departure_time>=\''.Date_Time::to_orc_date($from_date).'\' 
                    --and (reservation_room.arrival_time!=reservation_room.departure_time)
                    and reservation_room.status=\'BOOKED\' 
                    and (reservation_room.room_id is null or reservation_room.room_id=0) 
                    and reservation.portal_id=\''.PORTAL_ID.'\'
                ORDER BY
                    night DESC, reservation_room.room_level_id
                ';
       return DB::fetch_all($sql);
       
    }
    
    static function get_booked_not_asign_min_date($from_date,$to_date)
    {
        return DB::fetch('
                SELECT
                    TO_CHAR(MIN(reservation_room.arrival_time),\'DD/MM/YYYY\') as arrival_time
                FROM
                    reservation_room
                    inner join reservation on reservation.id=reservation_room.reservation_id
                    inner join customer on customer.id=reservation.customer_id
                    inner join room_level on room_level.id=reservation_room.room_level_id
                    left join reservation_traveller on reservation_traveller.reservation_room_id=reservation_room.id
                    left join traveller on traveller.id=reservation_traveller.traveller_id
                WHERE
                    reservation_room.arrival_time<=\''.Date_Time::to_orc_date($to_date).'\'
                    and reservation_room.departure_time>=\''.Date_Time::to_orc_date($from_date).'\'
                    and reservation_room.status=\'BOOKED\'
                    and (reservation_room.room_id is null or reservation_room.room_id=0)
                    and reservation.portal_id=\''.PORTAL_ID.'\'
                ','arrival_time');
    }
    static function get_booked_not_asign_max_date($from_date,$to_date)
    {
        return DB::fetch('
                SELECT
                    TO_CHAR(MAX(reservation_room.departure_time),\'DD/MM/YYYY\') as departure_time
                FROM
                    reservation_room
                    inner join reservation on reservation.id=reservation_room.reservation_id
                    inner join customer on customer.id=reservation.customer_id
                    inner join room_level on room_level.id=reservation_room.room_level_id
                    left join reservation_traveller on reservation_traveller.reservation_room_id=reservation_room.id
                    left join traveller on traveller.id=reservation_traveller.traveller_id
                WHERE
                    reservation_room.arrival_time<=\''.Date_Time::to_orc_date($to_date).'\'
                    and reservation_room.departure_time>=\''.Date_Time::to_orc_date($from_date).'\'
                    and reservation_room.status=\'BOOKED\'
                    and (reservation_room.room_id is null or reservation_room.room_id=0)
                    and reservation.portal_id=\''.PORTAL_ID.'\'
                ','departure_time');
    }
    
 }
?>