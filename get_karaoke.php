<?php
define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
set_include_path(ROOT_PATH);
require_once 'packages/core/includes/system/config.php';

function stop_sing()
   	{
        if(isset($_REQUEST['id']) AND DB::exists("select id from karaoke_reservation_table where id=".$_REQUEST['id']))
        {
            $sing_room = array();
            DB::update('karaoke_reservation_table',array('sing_end_time'=>time()),'id='.$_REQUEST['id']);
            $sing_room = DB::fetch("select karaoke_reservation_table.* from karaoke_reservation_table where id = ".$_REQUEST['id']);
                
            $sing_start_time = $sing_room['sing_start_time'];
            $sing_end_time = $sing_room['sing_end_time'];
            $sing_room['sing_start_time'] = $sing_room['sing_start_time']!=''?date('H:i',$sing_room['sing_start_time']):'';
            $sing_room['sing_end_time'] = $sing_room['sing_end_time']!=''?date('H:i',$sing_room['sing_end_time']):'';
            $sing_room['total'] = '';
            $sing_room['total_time'] = 0;
            if($sing_start_time!='' AND $sing_end_time!='')
            {    
                $sing_room['total'] = System::display_number(($sing_room['price']/3600)*($sing_end_time-$sing_start_time));
                $sing_room['total_time'] = ($sing_end_time-$sing_start_time);
            }
            $sing_room['price'] = System::display_number($sing_room['price']);
            
            return $sing_room;
        }
        else
        {
            return '';
        }
   	}
    switch($_REQUEST['data'])
    {
        case "stop_sing":
        {
            echo json_encode(stop_sing()); break;
        }
        default: echo '';break;
    }
?>
