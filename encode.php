<?php 
//Written by Khoand
//Date: 30/04/2012
//Encode all system
//*******************************************************************
define('DEVELOPING',false);
define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
require_once ROOT_PATH.'packages/core/includes/system/config.php';
class Encode{
	function encode(){
		echo 1;
	}
}
if(User::is_admin()){
	$ec = new Encode;
}
?>