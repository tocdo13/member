<?php
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once 'packages/core/includes/system/config.php';
	require_once 'packages/hotel/includes/php/product.php';	
	require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';		
	if(Url::get('q'))
    {
		if(Url::get('customer'))
        {
            if(Url::get('customer')=='review_debit_customer')
                $name = ',customer.id as name';
            else
                $name = ',customer.name';
			$items = DB::fetch_all('
				select 
					FN_CONVERT_TO_VN(customer.name) as id
					'.$name.'
					,rownum
				from
					customer
				where
					UPPER(FN_CONVERT_TO_VN(customer.name)) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
					AND (rownum > 0 AND rownum <= 1000)
				order by
					customer.name
			');
			foreach($items as $key=>$value)
			{
				echo $value['id'].'|'.$value['name']."\n";
			}
        }
		else if(Url::get('restaurant'))
        {
            $sql ='
    			select 
    				customer.id,
    				customer.name
    			from
    				customer
                    where
    			 UPPER(customer.name) LIKE \'%'.strtoupper(Url::get('q')).'%\'
    			order by
    				customer.name
    		';
            $items = DB::fetch_all($sql);
    		foreach($items as $key=>$value)
            {
    			echo $value['name'].'|'.$value['id']."\n";
    		}
    		
        }
		DB::close();
	}
     if(Url::sget('password')){
            if (preg_match('/([0-9]+[a-z])|([a-z]+[0-9])|[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', Url::sget('password')) )
            {
                echo 3;
            }else{
                echo 2;
            }
   }
    if(Url::sget('change_password')){
        if(DB::update('account',array('password'=>User::encode_password(Url::sget('pass'))),'id=\''.Url::sget('change_password').'\'')){
            echo 1;
        }else{
            echo 0;
        }
   }  
?>