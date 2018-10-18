<?php
	date_default_timezone_set('Asia/Saigon');//Define default time for global system
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    require_once ROOT_PATH.'packages/core/includes/system/install.php';
	check_run_install();// khoi tao he thong
    require_once ROOT_PATH.'packages/core/includes/system/config.php';	
	
	define('DEVELOPING',false);
	define('VERSION','5.18');	
	define('REWRITE',false);//bat tat che do rewrite
	define('BEGINNING_YEAR',2017);
	//checkValidMachine(false);
	check_compatible_brower(false); 
	Portal::run();
?>
