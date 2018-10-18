<?php
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once 'packages/core/includes/system/config.php';
	if(Url::get("q")){
		$items = DB::fetch_all('
			SELECT 
				code_1 as id, name_'.Portal::language().' AS name
			FROM 
				country
			WHERE 
				UPPER(name_'.Portal::language().') LIKE \'%'.strtoupper(Url::sget('q')).'%\'
				OR UPPER(code_1) LIKE \'%'.strtoupper(Url::sget('q')).'%\'   
			ORDER BY 
				name_1
		');
		$items = String::get_list($items);
		foreach($items as $key=>$value){
			echo "$key|$value\n";
		}
		DB::close();
	}
?>