<?php
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once 'packages/core/includes/system/config.php';
	if(Url::get('q')){
		$items = DB::fetch_all('
			SELECT 
				id, name_'.Portal::language().' AS name
			FROM 
				unit
			WHERE 
				UPPER(name_'.Portal::language().') LIKE \'%'.strtoupper(Url::sget('q')).'%\'
			ORDER BY 
				name_'.Portal::language().'
		');
		$items = String::get_list($items);
		foreach($items as $key=>$value){
			echo "$value|$key\n";
		}
		DB::close();
	}
?>