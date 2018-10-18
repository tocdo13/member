<?php 
	date_default_timezone_set('Asia/Saigon');//Define default time for global system
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once ROOT_PATH.'packages/core/includes/system/config.php';	
	if(User::is_admin()){
		$module_id = DB::fetch('select id from module where name = \'Footer\'','id');
		$pages = DB::fetch_all('SELECT id FROM page');
		foreach($pages as $key=>$value){
			$cond = 'module_id = '.$module_id.' AND page_id = '.$key.' AND region = \'footer\'';
			if(!DB::exists('SELECT * FROM block WHERE '.$cond.'')){
				$array = array(
					'module_id'=>$module_id,
					'page_id'=>$key,
					'container_id'=>0,
					'region'=>'footer',
					'position'=>1
				);
				DB::insert('block',$array);
			}
		}
	}
?>