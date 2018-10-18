<?php
  define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    require_once 'packages/core/includes/system/config.php';
    //require_once 'packages/hotel/includes/php/product.php';    
    //require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';        
    $passport_no = Url::get('passport_no');
    $nationality = Url::get('nationality');

    $sql = "SELECT * FROM traveller WHERE passport='".$passport_no."'";
    $traveller = DB::fetch($sql);
    if(empty($traveller))
    {
        $traveller = array();
    }
    $sql = "SELECT code_1 as id, name_1 as country_name FROM country WHERE code_1='".$nationality."'";
    $nationality_id = DB::fetch($sql);
    $array_temp = array();
    
    if(!empty($nationality_id))
    {
        $traveller['nationality_id'] = $nationality;
        $traveller['nationality_name'] = $nationality_id['country_name'];
    }
    else
    {
        $traveller['nationality_id'] = "ZZZ";
        $traveller['nationality_name'] = "Các nước khác";
    }
    echo json_encode($traveller);
?>
