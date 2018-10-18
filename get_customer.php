<?php
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once 'packages/core/includes/system/config.php';
	require_once 'packages/hotel/includes/php/product.php';	
	require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';		
	if(Url::get('q'))
    {
		if(Url::get('supplier'))
        {
            
			$items_product = DB::fetch_all('
                            				select 
                            					supplier.code as id
                            					,supplier.name
                            					,rownum
                            				from
                            					supplier
                            				where
                            					UPPER(supplier.code) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
                            					AND (rownum > 0 AND rownum <= 1000)
                            				order by
                            					supplier.code
                            			');
            foreach($items_product as $key=>$value)
            {
                echo $value['id'].'|'.$value['name']."\n";
            }
            //System::debug($items_product);
            /*
			$items = DB::fetch_all('
				select 
					customer.code as id
					,customer.name
					,rownum
				from
					customer
				where
					UPPER(customer.code) LIKE \'%'.strtoupper(Url::sget('q')).'%\' and group_id=\'SUPPLIER\'
					AND (rownum > 0 AND rownum <= 1000)
				order by
					customer.code
			');*/
		}
        /*
		$items = String::get_list($items);
		foreach($items as $key=>$value){
			echo "$key|$value\n";
		}
        */
		DB::close();
	}
?>