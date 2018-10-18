<?php
    date_default_timezone_set('Asia/Saigon'); 
	require_once 'packages/core/includes/system/config.php';
    // 1. l?y tên hàm callback
    $cb = $_GET['callback'] ;
    $date_from = Date_Time::to_time($_GET['date_from']);
    $date_to = Date_Time::to_time($_GET['date_to']);
    $max_price = $_GET['max_price'];
    $min_price = $_GET['min_price'];
    
    // Lay ti gia online
    $from   = 'USD' ;
    $to     = 'VND';
    $url = 'http://finance.yahoo.com/d/quotes.csv?e=.csv&f=sl1d1t1&s='. $from . $to .'=X';
    $handle = fopen($url, 'r');
 
    if ($handle) {
        $result = fgets($handle, 4096);
        fclose($handle);
    }
 
    $allData = explode(',',$result); 
    $currencyValue = $allData[1];
    // end  
    
    $sql = "SELECT * FROM room_level WHERE portal_id='#default' AND is_virtual=0 AND price>=".$min_price." AND price<=".$max_price;
    $room_level = DB::fetch_all($sql);
    foreach($room_level as $key=>$value){
        $sql = "SELECT count(id) as count_room FROM room WHERE room_level_id=".$value['id']." AND portal_id='#default'";
        $count_room = DB::fetch($sql);
        
        $sql = "SELECT id, time_in, time_out FROM reservation_room WHERE room_level_id=".$value['id'];
        $result = DB::fetch_all($sql);
        foreach($result as $k=>$v){
            $date_from_current = $v['time_in'];
            $date_to_current = $v['time_out'];
            if(($date_from<=$date_from_current && $date_from_current <=$date_to) || ($date_from<=$date_to_current && $date_to_current<=$date_to) || ($date_from<=$date_from_current && $date_to>=$date_to_current) || ($date_from<=$date_from_current && $date_to>=$date_to_current)){
                $count_room['count_room']--;
            }
        }
        if($count_room['count_room']==0){
            unset($room_level[$key]);
        }
        else{
            $value['currencyValue']  =  $currencyValue; 
            $value['count_room'] = $count_room['count_room'];
            $room_level[$key] = $value;
        }
        
    }
    //$items = DB::fetch_all($sql);
    
    # 2. tr? l?i v?i d? li?u json {'a':1}
    $array = array('a'=>1);
    echo $cb."(".json_encode($room_level).");"
?>