<?php
    define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    set_include_path(ROOT_PATH);
    require_once 'packages/core/includes/system/config.php';
    
    
    function check_dirty()
    {
        $check = 200;
        $cond = ' room_status.room_id='.Url::get('room_id').' AND room_status.in_date= \''.Date_Time::to_orc_date(Url::get('in_date')).'\''; 
        if(Url::get('rr_id')!='')
        { 
            $cond .= ' AND (room_status.reservation_room_id != '.Url::get('rr_id').' OR room_status.reservation_room_id is null )';
        }
        if(DB::exists('SELECT room_status.id FROM room_status WHERE'.$cond.' AND room_status.house_status=\'DIRTY\' '))
        {
            $check = 404;
        }
        return $check;
    }
    
    function check_dirty_all()
    {
        $RE_arr = array();
        $Arr_room = explode("|",Url::get('rooms'));
        foreach($Arr_room as $key=>$value)
        {
            $StrArr = explode(",",$value);
            $RE_arr[$StrArr[0]]['index'] = $StrArr[0];
            $RE_arr[$StrArr[0]]['status'] = 200;
            $cond = ' room_status.room_id='.$StrArr[1].' AND room_status.in_date= \''.Date_Time::to_orc_date($StrArr[2]).'\''; 
            if(Url::get('rr_id')!='')
            {
                $cond .= ' AND room_status.reservation_room_id != '.$StrArr[3].' OR room_status.reservation_room_id is null )';
            };
            if(DB::exists('SELECT room_status.id FROM room_status WHERE'.$cond.'  AND room_status.house_status=\'DIRTY\'  '))
            {
                $RE_arr[$StrArr[0]]['status'] = 404;
            }
        }
        return $RE_arr;
    }
    
    switch($_REQUEST['data'])
    {
        case "check_dirty":
        {
            echo json_encode(check_dirty()); break;
        }
        case "check_dirty_all":
        {
            echo json_encode(check_dirty_all()); break;
        }
        default: echo '';break;
    }
    
?>
