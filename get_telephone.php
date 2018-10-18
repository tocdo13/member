<?php
	//date_default_timezone_set('Asia/Saigon');//Define default time for global system
	//date_default_timezone_set('Asia/Saigon');//Define default time for global system
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	//require_once ROOT_PATH.'packages/core/includes/system/config.php';
    //require_once ROOT_PATH.'packages/core/includes/system/database.php';	
    //$file = 'C:/Users/ngocdatbk/Desktop/result.txt';
    //file_put_contents($file, ROOT_PATH.'packages/hotel/packages/reception/modules/includes/telephone_paradise.php');
    //$file = 'C:/Users/Administrator/Desktop/result.txt';
        //file_put_contents($file, "hello");
    require_once ROOT_PATH.'packages/hotel/packages/reception/modules/includes/telephone_paradise.php';
	TelephoneLib::update_telephone_auto();
?>
