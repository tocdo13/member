<?php 
class ArrivalReportDB {
    function get_service(){
        $query = 'SELECT
                        id,name
                  FROM
                    service
                  ORDER BY
                    id';
        return DB::fetch_all($query);       
    }
    function get_report($cond){
        $query = 'SELECT 
                    reservation_room.id,
                    reservation_room.status,
                    reservation_room.arrival_time,
                    reservation_room.time_in,
                    reservation_room.time_out,
                    reservation_room.departure_time,
                    reservation_room.price,
                    DECODE(reservation.note , NULL , \' \' , CONCAT( \'+ \',reservation.note )) as group_note,
                    DECODE(reservation_room.note , NULL , \' \' , CONCAT( \'+ \',reservation_room.note )) as room_note,
                    room.name as room_id,
                    room_type.name as room_type,
                    customer.name as company_name,
                    reservation_room_service.service_id
                  FROM
                    reservation_room
                    INNER JOIN reservation on reservation.id = reservation_room.reservation_id
                    INNER JOIN room on room.id = reservation_room.room_id
                    INNER JOIN room_type on room_type.id = room.room_type_id
                    LEFT OUTER JOIN reservation_room_service on reservation_room.id = reservation_room_service.reservation_room_id
                    LEFT OUTER JOIN customer on customer.id = reservation.customer_id
                  WHERE
                    '.$cond.' AND reservation_room.status != \'CANCEL\'
                  ORDER BY
                    room.id';
        $items =  DB::fetch_all($query);
        foreach($items as $key => $item){
            $items[$key]['guest'] = DB::fetch_all('SELECT 
                                                     traveller.id,
                                                     CONCAT(traveller.first_name ,traveller.last_name) as name 
                                                   FROM 
                                                     reservation_room 
                                                     INNER JOIN reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
                                                     INNER JOIN traveller on traveller.id = reservation_traveller.traveller_id
                                                   WHERE
                                                    reservation_room.id = \''.$item['id'].'\'');
            foreach($items[$key]['guest'] as $k => $return){
                $items[$key]['guest'][$k]['return'] = DB::fetch('SELECT DECODE(count(1), 1 , 0 , count(1)) as return FROM reservation_traveller WHERE traveller_id = \''.$return['id'].'\'','return');
            }
        }
        return $items;
    }
}
?>