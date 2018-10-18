<?php 
class RealityRoomMapDB {
    function get_room_list($date){
        $rooms = DB::fetch_all('SELECT DISTINCT(floor) as id FROM room ORDER BY floor');
        foreach($rooms as $key => $room){
            $query = '  SELECT
                            room.id ,
                            room.name,
                            room.floor,
                            room.left,
                            room.top,
                            room_type.brief_name,
                            room_type.price,
                            room_type.id as room_type_id,
                            minibar.id as minibar_id,
                            room_status.house_status,
                            room_status.note
                        FROM 
                            room
                            INNER JOIN room_type on room_type.id = room.room_type_id
                            LEFT OUTER JOIN minibar on minibar.room_id = room.id AND minibar.status = \'AVAILABLE\'
                            LEFT OUTER JOIN room_status on room_status.room_id = room.id AND room_status.in_date = \''.Date_Time::to_orc_date($date).'\' AND room_status.status = \'AVAILABLE\' 
                        WHERE
                            room.floor = \''.$room['id'].'\'
                        ORDER BY
                            room.name';
            $rooms[$key]['floor_id'] = str_replace(' ' , '' ,$rooms[$key]['id']);
            $rooms[$key]['room'] = DB::fetch_all($query);   
            foreach($rooms[$key]['room'] as $k => $value){
                $rooms[$key]['room'][$k]['floor_id'] = str_replace(' ' , '' ,$rooms[$key]['room'][$k]['floor']);
                $rooms[$key]['room'][$k]['left'] = trim($rooms[$key]['room'][$k]['left']);
                $rooms[$key]['room'][$k]['top'] = trim($rooms[$key]['room'][$k]['top']);
                if($rooms[$key]['room'][$k]['left'] == 'auto'){
                    $rooms[$key]['room'][$k]['left'] = '';
                }
                if($rooms[$key]['room'][$k]['top'] == 'auto'){
                    $rooms[$key]['room'][$k]['top'] = '';
                }
            }
        }
        foreach($rooms as $key => $room){
            foreach($room['room'] as $k => $value){
                $rooms[$key]['room'][$k]['price'] = System::display_number($value['price']);
                $rooms[$key]['room'][$k]['status'] = array();
                $rooms[$key]['room'][$k]['house_status'] = '';
            }
        }
        $this->map['room_information'] = String::array2js($rooms);
            $query = '  SELECT
                            room_status.id,
                            room_status.room_id,
                            room_status.status,
                            room_status.house_status,
                            room.name,
                            reservation_room.id as reservation_room_id,
                            reservation.id as reservation_id,
                            reservation_room.time_out,
                            reservation_room.time_in,
                            reservation_room.arrival_time,
                            reservation_room.departure_time,
                            reservation_room.time_out - reservation_room.time_in as duration,
                            reservation_room.adult,
                            reservation_room.user_id,
                            reservation_room.note as reservation_note,
                            customer.name as company_name,
                            room_type.price,
                            room_type.id as room_type_id,
                            room_type.brief_name,
                            CASE
                                WHEN 
                                    reservation_room.status = \'CHECKIN\'
                                    THEN
                                        CASE
                                            WHEN 
                                                reservation_room.arrival_time = \''.Date_Time::to_orc_date($date).'\' 
                                            THEN
                                                \'CHECKIN_TO_DAY\'
                                            WHEN
                                                reservation_room.time_out < \''.time().'\'
                                            THEN 
                                                \'OVERDUE_CHECKOUT\'
                                            ELSE
                                                \'OCCUPIED\'
                                        END
                                WHEN 
                                    reservation_room.status = \'BOOKED\'
                                    THEN
                                        CASE
                                            WHEN 
                                                reservation_room.time_in <= \''.time().'\'
                                            THEN
                                                \'OVERDUE_BOOKED\'
                                            ELSE
                                                \'BOOKED\'
                                        END
                                WHEN
                                    reservation_room.status = \'CHECKOUT\'
                                    THEN
                                        CASE
                                            WHEN
                                                reservation_room_service.service_id = 7
                                            THEN
                                                \'OVERDUE_CHECKOUT\'
                                            ELSE
                                                \'CHECKOUT_TO_DAY\'
                                        END
                                WHEN
                                    room_status.status = \'AVAILABLE\'
                                    THEN
                                        room_status.house_status
                                END status
                            FROM
                                room_status
                                INNER JOIN room on room.id = room_status.room_id
                                INNER JOIN room_type on room_type.id = room.room_type_id
                                LEFT OUTER JOIN reservation_room on reservation_room.id = room_status.reservation_room_id
                                LEFT OUTER JOIN reservation on reservation_room.reservation_id = reservation.id
                                LEFT OUTER JOIN reservation_room_service on  reservation_room_service.reservation_room_id = reservation_room.id
                                LEFT OUTER JOIN customer on reservation.customer_id = customer.id
                            WHERE
                                room_status.in_date = \''.Date_Time::to_orc_date($date).'\' AND room_status.status != \'CANCEL\'
                            ORDER BY 
                                reservation_room.time_out DESC';
        $rooms_status = DB::fetch_all($query);
        $query = 'SELECT 
                        reservation_traveller.id,
                        reservation_traveller.reservation_room_id,
                        traveller.gender,
                        traveller.id as traveller_id,
                        CONCAT( traveller.first_name , traveller.last_name) as full_name
                  FROM
                        reservation_traveller
                        INNER JOIN reservation_room on reservation_room.id = reservation_traveller.reservation_room_id
                        INNER JOIN traveller on traveller.id = reservation_traveller.traveller_id
                  WHERE
                        reservation_room.arrival_time <= \''.Date_Time::to_orc_date($date).'\' 
                        AND reservation_room.departure_time >= \''.Date_Time::to_orc_date($date).'\'';
        $travellers = DB::fetch_all($query);
        foreach($rooms_status as $key => $room_status){
            $rooms_status[$key]['traveller'] = array();
            foreach($travellers as $k => $traveller){
                if($room_status['reservation_room_id'] == $traveller['reservation_room_id']){
                    $rooms_status[$key]['traveller'][$k] = $traveller;
                }
            }
            $rooms_status[$key]['price'] = System::display_number($room_status['price']);
			if($room_status['status']=='BOOKED'){
				$rooms_status[$key]['duration'] = ($room_status['duration'] < 1000)?$room_status['duration'].' '.Portal::language('night'):Portal::language('in_day');
			}else{
				$duration = 0;//.' '.Portal::language('hour');
				if(round($room_status['duration']/3600,1)<12){
					$duration = round($room_status['duration']/3600,1).' '.Portal::language('hour');
				}if(round($room_status['duration']/3600,1)>=12 and round($room_status['duration']/3600,1)<24){
					$duration = round($room_status['duration']/3600,1).' '.Portal::language('hour');
				}elseif(round($room_status['duration']/3600,1)>=24){
					$duration = round($room_status['duration']/3600/24).' '.Portal::language('night');
				}
				$rooms_status[$key]['duration'] = $duration;	
			}
			$rooms_status[$key]['arrival_time'] = $room_status['arrival_time']?Date_Time::convert_orc_date_to_date($room_status['arrival_time'],'/'):'';
			$rooms_status[$key]['departure_time'] = $room_status['departure_time']?Date_Time::convert_orc_date_to_date($room_status['departure_time'],'/'):'';
            $rooms_status[$key]['time_out'] = date('H:i' , $room_status['time_out']);
            $rooms_status[$key]['time_in'] = date('H:i' , $room_status['time_in']);
            if($room_status['status'] == ''){
                unset($rooms_status[$key]);
            }
        }
        $items['room_list_js'] = $rooms_status;
        $explanation['AVAILABLE']['value'] = 0;
        $explanation['AVAILABLE']['name'] = 'AVAILABLE';
        $explanation['BOOKED']['value'] = 0;
        $explanation['BOOKED']['name'] = 'BOOKED';
        $explanation['OVERDUE_BOOKED']['value'] = 0;
        $explanation['OVERDUE_BOOKED']['name'] = 'OVERDUE_BOOKED';
        $explanation['OCCUPIED']['value'] = 0;
        $explanation['OCCUPIED']['name'] = 'OCCUPIED';
        $explanation['CHECKIN_TO_DAY']['value'] = 0;
        $explanation['CHECKIN_TO_DAY']['name'] = 'CHECKIN_TO_DAY';
        $explanation['OVERDUE_CHECKOUT']['value'] = 0;
        $explanation['OVERDUE_CHECKOUT']['name'] = 'OVERDUE_CHECKOUT';
        $explanation['CHECKOUT_TO_DAY']['value'] = 0;
        $explanation['CHECKOUT_TO_DAY']['name'] = 'CHECKOUT_TO_DAY';
        $explanation['REPAIR']['value'] = 0;
        $explanation['REPAIR']['name'] = 'REPAIR';
        foreach($rooms_status as $key => $room_status){
            foreach($rooms as $k => $room){
                if(isset($rooms[$k]['room'][$room_status['room_id']]) && $room_status['status'] != ''){
                    $rooms[$k]['room'][$room_status['room_id']]['status'][$room_status['id']] = $rooms_status[$key];
                }
            }
            if($room_status['status'] == 'REPAIR'){
                $explanation['REPAIR']['value']++;
            }
            if($room_status['status'] == 'BOOKED'){
                $explanation['BOOKED']['value']++;
            }
            if($room_status['status'] == 'OVERDUE_CHECKOUT'){
                $explanation['OVERDUE_CHECKOUT']['value']++;
            }
            if($room_status['status'] == 'CHECKOUT_TO_DAY'){
                $explanation['CHECKOUT_TO_DAY']['value']++;
            }
            if($room_status['status'] == 'OVERDUE_BOOKED'){
                $explanation['OVERDUE_BOOKED']['value']++;
            }
            if($room_status['status'] == 'OCCUPIED' || $room_status['status'] == 'CHECKIN_TO_DAY'){
                $explanation['OCCUPIED']['value']++;
            }
            if($room_status['status'] == 'CHECKIN_TO_DAY'){
                $explanation['CHECKIN_TO_DAY']['value']++;
            }
        }
        $room_type = DB::fetch_all('SELECT * FROM room_type');
        foreach($room_type as $key => $value){
            $room_type[$key]['total_room'] = 0;
        }
        foreach($rooms as $key => $room){
            foreach($room['room'] as $k => $value){
                if(sizeof($rooms[$key]['room'][$k]['status']) == 0){
                    $rooms[$key]['room'][$k]['status'] = 'AVAILABLE';
                    $rooms[$key]['room'][$k]['room_status'] = 'AVAILABLE';
                    $explanation['AVAILABLE']['value']++;
                    $room_type[$rooms[$key]['room'][$k]['room_type_id']]['total_room'] += 1;
                }else{
                    $i = 0 ;
                    foreach($value['status'] as $t => $v){
                        if($i == 0){
                            $rooms[$key]['room'][$k]['room_status'] = $v['status'];
                            if($v['status'] == 'CHECKOUT_TO_DAY'){   
                                $rooms[$key]['room'][$k]['house_status'] = $v['house_status'];
                            }
                        }
                        $i++;
                    }
                }
            }
        }
        $items['total_room_available'] = $explanation['AVAILABLE']['value'];
        $items['room_type'] = $room_type;
        $items['room'] = $rooms;
        $items['explanation'] = $explanation;
        return $items;
    }
    function get_rooms(){
        $rooms = DB::fetch_all('SELECT DISTINCT(floor) as id FROM room ORDER BY floor');
        foreach($rooms as $key => $room){
            $query = '  SELECT
                            room.id ,
                            room.name,
                            room.floor,
                            room.left,
                            room.top,
                            room_type.brief_name,
                            room_type.id as room_type_id
                        FROM 
                            room
                            INNER JOIN room_type on room_type.id = room.room_type_id 
                        WHERE
                            room.floor = \''.$room['id'].'\'
                        ORDER BY
                            room.name';
            $rooms[$key]['floor_id'] = str_replace(' ' , '' ,$rooms[$key]['id']);
            $rooms[$key]['room'] = DB::fetch_all($query);   
            foreach($rooms[$key]['room'] as $k => $value){
                $rooms[$key]['room'][$k]['floor_id'] = str_replace(' ' , '' ,$rooms[$key]['room'][$k]['floor']);
                $rooms[$key]['room'][$k]['left'] = trim($rooms[$key]['room'][$k]['left']);
                $rooms[$key]['room'][$k]['top'] = trim($rooms[$key]['room'][$k]['top']);
                if($rooms[$key]['room'][$k]['left'] == 'auto'){
                    $rooms[$key]['room'][$k]['left'] = '';
                }
                if($rooms[$key]['room'][$k]['top'] == 'auto'){
                    $rooms[$key]['room'][$k]['top'] = '';
                }
            }
        }
        return $rooms;
    }
    function get_room_type(){
        return DB::fetch_all('SELECT room_type.id,room_type.brief_name , rr.total_room FROM room_type INNER JOIN (SELECT count(id) as total_room,room_type_id FROM room GROUP BY room_type_id ) rr on rr.room_type_id = room_type.id');
    }
    function get_total_room(){
        return DB::fetch('SELECT COUNT(1) as total FROM room ' , 'total');
    }
}
?>