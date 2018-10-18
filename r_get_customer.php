<?php
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once 'packages/core/includes/system/config.php';
	if(Url::get('q')){
		if(Url::get('name')){
			$items = DB::fetch_all('
					select 
						customer.code as id,customer.name,rownum
					from
						customer
					where
						UPPER(customer.name) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
						AND (rownum > 0 AND rownum <= 1000)
					order by
						customer.name
				');
			$items = String::get_list($items);
			foreach($items as $key=>$value){
				echo "$value|$key\n";
			}
		}
		if(Url::get('code')){
			$items = DB::fetch_all('
					select 
						customer.code as id,customer.name,rownum
					from
						customer
					where
						UPPER(customer.name) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
						AND (rownum > 0 AND rownum <= 1000)
					order by
						customer.name
				');
			$items = String::get_list($items);
			foreach($items as $key=>$value){
				echo "$key|$value\n";
			}
		}
		DB::close();
	}
?>