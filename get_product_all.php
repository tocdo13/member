<?php
     define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
     require_once 'packages/core/includes/system/config.php';	
     require_once 'packages/core/includes/utils/vn_code.php';

    if(Url::get('q'))
    {
        if(Url::get('product_all')==1)
        {
            $items_product =  DB::fetch_all('
                                				SELECT 
                                					product.id as name,
                                                    product.id as code,
                                                    product.name_'.Portal::language().' as id
                                                    
                                				FROM 
                                					product 
                                				WHERE
                                                    product.status = \'avaiable\'
                                                    AND 
                                                    (UPPER(product.id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
						                            OR ((LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\')))
                                                    AND ROWNUM<1000
                                				ORDER BY
                                					product.id
                                			'); 
                                            //System::debug($items_product);
                                            
           foreach($items_product as $key=>$value)
            {
                echo $value['id'].'|'.$value['name']."\n";
            }                                 
                                            
                                            
        }
        DB::close();
    }

?>