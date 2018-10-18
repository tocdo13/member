<?php
    define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    set_include_path(ROOT_PATH);
    require_once 'packages/core/includes/system/config.php';
    
    function check_booking($room_id)
    {
        $room_level_id = DB::fetch('SELECT room_level_id FROM room WHERE id='.$room_id,'room_level_id');
        $reservation = DB::fetch_all("
                                        SELECT
                                            reservation_room.reservation_id as id
                                        FROM
                                            reservation_room
                                            inner join room on reservation_room.room_id=room.id
                                        WHERE 
                                            reservation_room.status!='CLOSE'
                                            AND reservation_room.room_id=".$room_id."
                                        ");
        if(sizeof($reservation)>0)
        {
            return $data = array('status'=>'F','room_level_id'=>$room_level_id,'reservation'=>$reservation);
        }
        else
        {
            return $data = array('status'=>'T','room_level_id'=>$room_level_id,'reservation'=>$reservation);
        }
    }
    function check_close_room($room_id,$in_date)
    {
        $reservation = DB::fetch_all("
                                        SELECT
                                            reservation_room.reservation_id as id
                                        FROM
                                            reservation_room
                                            inner join room on reservation_room.room_id=room.id
                                        WHERE
                                            ( ( reservation_room.status!='CHECKOUT' AND reservation_room.departure_time!='".Date_Time::to_orc_date($in_date)."' ) OR ( reservation_room.status='CHECKOUT' AND reservation_room.departure_time='".Date_Time::to_orc_date($in_date)."' ) ) 
                                            AND reservation_room.status!='CANCEL' 
                                            AND reservation_room.status!='CLOSE'
                                            AND reservation_room.room_id=".$room_id."
                                        ");
        
        if(sizeof($reservation)>0)
        {
            return $data = array('status'=>'F','reservation'=>$reservation);
        }
        else
        {
            return $data = array('status'=>'T','reservation'=>$reservation);
        }
    }
    switch($_REQUEST['data'])
    {
        case "check_booking":
        {
            echo json_encode(check_booking($_REQUEST['room_id'])); break;
        }
        case "check_close_room":
        {
            echo json_encode(check_close_room($_REQUEST['room_id'],$_REQUEST['in_date'])); break;
        }
        default: echo '';break;
    }
?>