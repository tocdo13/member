<?php
    define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    set_include_path(ROOT_PATH);
    require_once 'packages/core/includes/system/config.php';
    
    function get_discount_bar()
    {
        $result = 0;
        $discount = DB::fetch_all("SELECT id,nvl(discount_percent,0) as discount FROM bar_reservation WHERE id=".$_REQUEST['from_code']." OR id=".$_REQUEST['to_code']);
        foreach($discount as $key=>$value)
        {
            if($value['discount']>0)
            {
                $result = 1;
            }
        }
        $discount_product = DB::fetch_all("SELECT id,NVL(discount_rate,0) as discount FROM bar_reservation_product WHERE bar_reservation_id=".$_REQUEST['from_code']." OR bar_reservation_id=".$_REQUEST['to_code']);
        foreach($discount_product as $id=>$content)
        {
            if($content['discount']>0)
            {
                $result = 1;
            }
        }
        return $result;
    }
    switch($_REQUEST['data'])
    {
        case "get_discount_bar":
        {
            echo json_encode(get_discount_bar()); break;
        }
        default: echo '';break;
    }
?>