<?php
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once 'packages/core/includes/system/config.php';
    require_once 'packages/core/includes/utils/vn_code.php';

	if(Url::get('q'))
    {
		if(Url::get('supplier'))
        {
			$items_supplier = DB::fetch_all('
                            				select 
                            					supplier.code as name
                            					,supplier.name as id
                            					,rownum
                            				from
                            					supplier
                            				where
                            					(
                                                LOWER(FN_CONVERT_TO_VN(supplier.name)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' 
                                                OR LOWER(FN_CONVERT_TO_VN(supplier.code)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\'                                                                                                                                               
                                                )
                            					--AND (rownum > 0 AND rownum <= 1000)
                            				order by
                            					supplier.code
                            			');
            foreach($items_supplier as $key=>$value)
            {
                echo $value['id'].'|'.$value['name']."\n";
            }
        }
        if(Url::get('get_back_supplier'))
        {
			$items_supplier = DB::fetch_all('
                            				select 
                            					supplier.id as name
                            					,supplier.name as id
                            					,rownum
                            				from
                            					supplier
                            				where
                            					(
                                                LOWER(FN_CONVERT_TO_VN(supplier.name)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' 
                                                OR LOWER(FN_CONVERT_TO_VN(supplier.code)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\'                                                                                                                                               
                                                )
                            					--AND (rownum > 0 AND rownum <= 1000)
                            				order by
                            					supplier.id
                            			');
            foreach($items_supplier as $key=>$value)
            {
                echo $value['id'].'|'.$value['name']."\n";
            }
        }
		DB::close();
	}
?>