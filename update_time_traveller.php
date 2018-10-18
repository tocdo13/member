<?php
    define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    set_include_path(ROOT_PATH);
    require_once 'packages/core/includes/system/config.php';
    
    function update_traveller()
    {
        $r_r_id = $_REQUEST['res_r_id'];
        $arrival_time = Date_Time::to_time($_REQUEST['arrival_time'])+calc_time($_REQUEST['time_in']);
        $arrival_date = Date_Time::to_time($_REQUEST['arrival_time']);
        
        $traveller = DB::fetch_all("SELECT id,arrival_time,arrival_date FROM reservation_traveller WHERE reservation_room_id=".$r_r_id);
        foreach($traveller as $key=>$value)
        {
            $date = Date_Time::to_time($value['arrival_date']);
            if(($date!=$arrival_date) OR ($value['arrival_time']!=$arrival_time))
            {
                DB::update('reservation_traveller',array('arrival_time'=>$arrival_time,'arrival_date'=>Date_Time::to_orc_date($_REQUEST['arrival_time'])),'id='.$value['id']);
            }
        }
    }
    
    /** trả về dữ liệu cho hàm gọi **/
    switch($_REQUEST['data'])
    {
        case "update_traveller":
        {
            echo json_encode(update_traveller()); break;
        }
        default: echo '';break;
    }
    /** hàm dùng chung **/
    function convert_month_to_orcl($month)
    {
        if($month==1){
            $month="JAN"; return $month;
        }elseif($month==2){
            $month="FEB"; return $month;
        }elseif($month==3){
            $month="MAR"; return $month;
        }elseif($month==4){
            $month="APR"; return $month;
        }elseif($month==5){
            $month="MAY"; return $month;
        }elseif($month==6){
            $month="JUN"; return $month;
        }elseif($month==7){
            $month="JUL"; return $month;
        }elseif($month==8){
            $month="AUG"; return $month;
        }elseif($month==9){
            $month="SEP"; return $month;
        }elseif($month==10){
            $month="OCT"; return $month;
        }elseif($month==11){
            $month="NOV"; return $month;
        }else{
            $month="DEC"; return $month;
        }
    }
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
?>