<?php
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once 'packages/core/includes/system/config.php';
	if(Url::get("q")){
		$items = DB::fetch_all('
			SELECT 
				id, note
			FROM 
				bar_note
			WHERE 
				UPPER(note) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
			ORDER BY 
				note
		');
		foreach($items as $key=>$value){
			echo $value['note'].'|'.$key."\n";  
		}
		DB::close();
	}
?>