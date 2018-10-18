<?php
    define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    set_include_path(ROOT_PATH);
    require_once 'packages/core/includes/system/config.php';
    require_once 'packages/core/includes/utils/vn_code.php';
    if(Url::get('department'))
    {
        $department_id = Url::get('department_id');
        $cond = " 1=1 ";
        if($department_id!=0)
            $cond .=" AND pc_department_product.portal_department_id=".$department_id;
        if(Url::get('name_product')=='1')
        {
            
            $items_product = DB::fetch_all('
                                        select 
                                            pc_department_product.product_id as name,
                                            product.name_'.Portal::language().' as id,
                                            pc_department_product.id as department_product_id,
                                            unit.name_'.Portal::language().' as unit_name,
                                            rownum
                                        from
                                            pc_department_product
                                            INNER JOIN product ON product.id=pc_department_product.product_id
                                            INNER JOIN unit ON unit.id = product.unit_id
                                        where
                                        '.$cond.'
                                        AND
                                        (
                                                LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' 
                                                OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\'
                                        ) 
                                        AND (rownum > 0 AND rownum <= 1000)
                                        order by
                                            pc_department_product.product_id
                                    ');
                                    
        }
        else
        {
            
            $items_product = DB::fetch_all('
                                        select 
                                            pc_department_product.product_id as id,
                                            product.name_'.Portal::language().' as name,
                                            pc_department_product.id as department_product_id,
                                            unit.name_'.Portal::language().' as unit_name,
                                            rownum
                                        from
                                            pc_department_product
                                            INNER JOIN product ON product.id=pc_department_product.product_id
                                            INNER JOIN unit ON unit.id = product.unit_id
                                        where
                                        '.$cond.'
                                        AND 
                                        (
                                                UPPER(product.id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
                                        )
                                        AND (rownum > 0 AND rownum <= 1000)
                                        order by
                                            pc_department_product.product_id
                                    ');
        }
        foreach($items_product as $key => $value)
        {
            echo $value['id'].'|'.$value['name'].'|'.$value['unit_name'] ."\n";
        }
    }
    DB::close();
?>