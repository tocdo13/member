<?php
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	error_reporting(E_ALL|E_NOTICE);
	//define('ENCODED',1);
	//require_once 'package_system/cache/library.php';
	require_once ROOT_PATH.'packages/core/includes/system/config.php';
	if(Url::check('payment') and Url::get('reservation_id') and DB::exists('SELECT ID FROM RESERVATION WHERE ID = '.Url::iget('reservation_id').'')){
		DB::update('reservation',
			array(
				'payment'=>Url::get('payment')?str_replace(',','',Url::get('payment')):0
			)
			,'id='.Url::iget('reservation_id')
		);
		exit();
	}
?>