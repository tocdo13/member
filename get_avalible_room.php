<?php
date_default_timezone_set('Asia/Saigon');//Define default time for global system
define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
set_include_path(ROOT_PATH);
require_once 'packages/core/includes/system/config.php';
    
        
        $room_lv = DB::fetch_all('
					SELECT
						room.id as id
						,room.name as room_name
						,room_level.name as room_level_name
						,room_level.price
						,0 AS min_room_quantity
						,room_level.portal_id
						,room_level.id as room_level_id
						,0 as checked
						,room.floor
						,room_level.brief_name
					FROM
						room
						INNER JOIN room_level on room_level.id = room.room_level_id
					WHERE
						room_level.portal_id = \''.PORTAL_ID.'\' 
                        AND (room_level.is_virtual IS NULL OR room_level.is_virtual = 0)
                        and room_level.id = \''.$_REQUEST['room_level_id'].'\'
                    ORDER BY room.floor,room.room_level_id
					');
                    //System::debug($room_lv);
                    //exit();
        $sql_status = '
					SELECT
						r.portal_id,rs.id,rr.status,rs.house_status,rr.time_in,rr.time_out,rr.arrival_time,rr.departure_time,rs.in_date,rr.room_level_id,rr.room_id,room_level.name as room_level_name,room.name as room_name,room_level.brief_name
					FROM
						room_status rs
						INNER JOIN reservation_room rr ON rs.reservation_room_id = rr.id
						INNER JOIN reservation r ON rr.reservation_id = r.id
						INNER JOIN room_level  ON rr.room_level_id = room_level.id
						left JOIN room ON room.id = rr.room_id
					WHERE
						r.portal_id = \''.PORTAL_ID.'\' 
                        AND rs.in_date = \''.Date_Time::to_orc_date(date('d/m/Y',$_REQUEST['in_date'])).'\'
                        AND rs.status <> \'CANCEL\'
					ORDER BY
						rr.room_level_id
					';
				$room_status = DB::fetch_all($sql_status);
                $sql_status2 = '
					SELECT
						r.portal_id,rs.id,rs.house_status,rs.room_id,rr.status
					FROM
						room_status rs
						INNER JOIN room r ON r.id = rs.room_id
                        left JOIN reservation_room rr ON rs.reservation_room_id = rr.id
					WHERE
						r.portal_id = \''.PORTAL_ID.'\' 
                        AND rs.in_date = \''.Date_Time::to_orc_date(date('d/m/Y',$_REQUEST['in_date'])).'\'                         
                        AND rs.status <> \'CANCEL\'
                        AND rs.status <> \'OCCUPIED\' AND rs.house_status = \'REPAIR\'
					';
				$room_status2 = DB::fetch_all($sql_status2);
                //System::debug($room_status2);
				//AND  ('.$cond_level.')
				$floor = array();
                foreach($room_lv as $k=>$room)
                {
					$t=0;
					foreach($room_status as $key=>$status)
                    {
						if( $room['id']==$status['room_id'] && $status['departure_time'] != $status['in_date']){
							$t = 1;
						}
					}
                    foreach($room_status2 as $ke=>$status2)
                    {
						if( $room['id']==$status2['room_id']){
							$t = 1;
						}
					}
					if($t==0){
						$rooms[$k] = $room;
						if(!isset($floor[$room['floor']])){
							$floor[$room['floor']]['name'] = $room['floor'];
							$floor[$room['floor']]['rooms'] = array();
						}
                        //System::debug($rooms[$k]);
					}
				}
        foreach($rooms as $y =>$roo)
        {
			if(isset($floor[$roo['floor']]))
            {
				$floor[$roo['floor']]['rooms'][$y] = $roo;
			}
		}
        foreach($floor as $k => $v)
        {
            foreach($v['rooms'] as $key => $value)
            {
                if(isset($room_id[$key]))
                {
                    unset($floor[$k]['rooms'][$key]);
                }
            }
        }
        $items=array();
        $items['floor'] = $floor;
        $items['in_date'] = date('d/m/Y',$_REQUEST['in_date']);
        echo json_encode($items);
        
?>