<?php
    define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    require_once 'packages/core/includes/system/config.php';
    require_once 'packages/core/includes/utils/vn_code.php';        
    if(Url::get('q'))
    {
        if(Url::get('product_purchasing'))
        {
            if(Url::get('name_product')=='1')
            {
                $items_product = DB::fetch_all('
                                            select 
                                                product.id as name
                                                ,product.name_1 as id
                                                ,rownum
                                            from
                                                product
                                            where
                                                (
                                                    LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' 
                                                    OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\'
                                                )
                                                AND (rownum > 0 AND rownum <= 1000)
                                                AND product.type!=\'DRINK\' and product.type!=\'PRODUCT\'
                                            order by
                                                product.id
                                        ');
                foreach($items_product as $key=>$value)
                {
                    echo $value['id'].'|'.$value['name']."\n";
                }
            }
            else
            {
                $items_product = DB::fetch_all('
                                            select 
                                                product.id as id
                                                ,product.name_1 as name
                                                ,rownum
                                            from
                                                product
                                            where
                                                (
                                                    UPPER(product.id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
                                                )
                                                AND (rownum > 0 AND rownum <= 1000)
                                                AND product.type!=\'DRINK\' and product.type!=\'PRODUCT\'
                                            order by
                                                product.id
                                        ');
                foreach($items_product as $key=>$value)
                {
                    echo $value['id'].'|'.$value['name']."\n";
                }
            }
            
        }
        else if(Url::get('department_product'))
        {
            if(Url::get('name_product')=='1')
            {
                $items_product = DB::fetch_all('
                                            select
                                                product.id as name
                                                ,product.name_'.Portal::language().' as id
                                                ,rownum
                                            from
                                                product
                                            where
                                                (
                                                    LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' 
                                                    OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\'
                                                )
                                                AND (rownum > 0 AND rownum <= 1000)
                                                AND product.type!=\'DRINK\' and product.type!=\'PRODUCT\'
                                            order by
                                                product.name_'.Portal::language().'
                                        ');
                foreach($items_product as $key=>$value)
                {
                    echo $value['id']."|".$value['name']."\n";
                }
            }
            else
            {
                $items_product = DB::fetch_all('
                                            select 
                                                product.id
                                                ,product.name_'.Portal::language().' as name
                                                ,rownum
                                            from
                                                product
                                            where
                                                (
                                                    UPPER(product.id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
                                                )
                                                AND (rownum > 0 AND rownum <= 1000)
                                                AND product.type!=\'DRINK\' and product.type!=\'PRODUCT\'
                                            order by
                                                product.name_'.Portal::language().'
                                        ');
                foreach($items_product as $key=>$value)
                {
                    echo $value['id']."|".$value['name']."\n";
                }
            }
        }
        else if(Url::get('department'))
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
                                                rownum
                                            from
                                                pc_department_product
                                                INNER JOIN product ON product.id=pc_department_product.product_id
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
                                                rownum
                                            from
                                                pc_department_product
                                                INNER JOIN product ON product.id=pc_department_product.product_id
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
            
            foreach($items_product as $key=>$value)
            {
                echo $value['id']."|".$value['name']."|".$value['department_product_id']."\n";
            }
        }
        
        DB::close();
    }
    else
    {
        if(Url::get('remain_product'))
        {
            $product_id = Url::get('product_id');
            $department_id = Url::get('department_id');
            require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
            //Tu department_id lay ra warehouse_id
            $sql = "SELECT * FROM portal_department WHERE id=".$department_id;
            $row = DB::fetch($sql);
            $warehouse_id = $row['warehouse_pc_id'];
            if($product_id!='')
            {
                //lay ra so luong ton cho san pham do o kho hien tai
                $product_remain_warehouse = get_remain_products($warehouse_id,false,$product_id,false);
                //lay ra ton kho tong cong cua san pham do 
                $product_remain_total = get_remain_products($warehouse_id,false,$product_id,false);
                if(empty($product_remain_warehouse))
                {
                    echo $product_id.'_'.$warehouse_id.'_0:';
                }
                else
                {
                    foreach($product_remain_warehouse as $k=>$v)
                    {
                        echo $k.'_'.$warehouse_id.'_'.$v['remain_number'].':';
                        break;
                    }
                }
                if(empty($product_remain_total))
                {
                    echo $product_id.'_ALL_0';
                }
                else
                {
                    foreach($product_remain_total as $k=>$v)
                    {
                        echo $product_id.'_ALL_'.$v['remain_number'];
                        break;
                    }
                }
            }
            else
                echo '0_'.$warehouse_id.'_0:'.'0_'.$warehouse_id.'_0';
            
            DB::close();
        }
        else if(Url::get('recommendation_product'))
        {
            require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
            $flag = Url::get('flag');
            /** Daund: thêm điều kiện tìm kiếm theo nhóm hàng hóa & bộ phận yêu cầu */
            $cond = '1=1';
            if(Url::get('category_id') && Url::get('category_id') !=1)
                $cond .= ' AND product.category_id in ('.Url::get('category_id').')';
            if(Url::get('department_code'))
                $cond .= ' AND portal_department.id = \''.Url::get('department_code').'\' ';
            //echo json_encode($cond);
            /** Daund: thêm điều kiện tìm kiếm theo nhóm hàng hóa & bộ phận yêu cầu */        
            $list_product_full = DB::fetch_all("
                                        SELECT
                                            pc_recommend_detail.id as id,
                                            product.id as product_id,
                                            product.name_".portal::language()." as product_name,
                                            pc_recommend_detail.id as pc_recommend_detail_id,
                                            pc_recommend_detail.quantity,
                                            unit.id as unit_id,
                                            unit.name_".portal::language()." as unit_name,
                                            pc_recommendation.portal_department_id,
                                            department.id as department_id,
                                            department.name_".portal::language()." as department_name,
                                            portal_department.warehouse_pc_id,
                                            pc_recommend_detail.delivery_date,
                                            pc_recommend_detail.note,
                                            product.tax_percent,
                                            product.category_id,
                                            product_category.name as category_name,
                                            pc_recommend_detail.order_id,
                                            pc_recommend_detail.id as pc_recommend_detail_id_list
                                        FROM
                                            pc_recommend_detail
                                            INNER JOIN pc_recommendation on pc_recommend_detail.recommend_id=pc_recommendation.id
                                            INNER JOIN product on pc_recommend_detail.product_id=product.id
                                            INNER JOIN product_category on product_category.id=product.category_id
                                            INNER JOIN unit on product.unit_id=unit.id
                                            INNER JOIN portal_department on portal_department.id=pc_recommendation.portal_department_id
                                            INNER JOIN department on department.code=portal_department.department_code
                                        WHERE
                                            ".$cond."
                                            AND pc_recommendation.confirm is not null
                                            AND pc_recommendation.status is null
                                        ORDER BY
                                            pc_recommend_detail.id
            ");
            //System::debug($list_product_full);
            $list_product = array();
            $quantity_use = DB::fetch_all('
                        SELECT
                            pc_recommend_detail_order.id as id,
                            pc_recommend_detail.id as detail_id,
                            pc_recommend_detail.product_id,
                            pc_recommend_detail.quantity as detail_quantity,
                            pc_order_detail.quantity,
                            pc_order_detail.id as order_id
                        FROM
                           pc_recommend_detail_order
                           INNER JOIN pc_order_detail on pc_recommend_detail_order.order_id = pc_order_detail.id
                           INNER JOIN pc_order on pc_order_detail.pc_order_id = pc_order.id
                           INNER JOIN pc_recommend_detail on pc_recommend_detail_order.pc_recommend_detail_id = pc_recommend_detail.id
                        ORDER BY
                            pc_recommend_detail.id DESC 
       ');
           /**
           //System::debug($quantity_use);
           $items_arr =array();
           
           foreach($quantity_use as $key => $value)
           {
                $key_arr = $value['order_id'];
                if(!isset($items_arr[$key_arr]))
                {
                    $items_arr[$key_arr]['id'] = $key_arr;
                    $items_arr[$key_arr]['order_id'] = $value['order_id'];
                    $items_arr[$key_arr]['pc_recommend_detail_id'] = $value['detail_id'];
                    $items_arr[$key_arr]['product_id'] = $value['product_id'];
                    $items_arr[$key_arr]['quantity'] = $value['quantity'];
                    $items_arr[$key_arr]['remain_quantity'] = $value['detail_quantity'] - $value['quantity'];
                }else
                {
                    if($items_arr[$key_arr]['product_id'] != $value['product_id'])
                    {
                        $items_arr[$key_arr]['id'] = $key_arr;
                        $items_arr[$key_arr]['order_id'] = $value['order_id'];
                        $items_arr[$key_arr]['pc_recommend_detail_id'] = $value['detail_id'];
                        $items_arr[$key_arr]['product_id'] = $value['product_id'];
                        $items_arr[$key_arr]['quantity'] = $value['quantity'];
                        $items_arr[$key_arr]['remain_quantity'] = $value['detail_quantity'] - $value['quantity'];
                    }else
                    {
                        if($items_arr[$key_arr]['remain_quantity'] > 0)
                        {
                            $key_arr_new = $value['detail_id'];
                            $items_arr[$key_arr_new]['order_id'] = $value['order_id'];
                            $items_arr[$key_arr_new]['pc_recommend_detail_id'] = $value['detail_id'];
                            $items_arr[$key_arr_new]['product_id'] = $value['product_id'];
                            $items_arr[$key_arr_new]['quantity'] = $value['detail_quantity'] - $items_arr[$key_arr]['remain_quantity'];
                            $items_arr[$key_arr_new]['remain_quantity'] = $value['detail_quantity'] - $items_arr[$key_arr_new]['quantity'];
                        }else
                        {
                            $key_arr_new_new = $value['detail_id'];
                            $items_arr[$key_arr_new_new]['order_id'] = $value['order_id'];
                            $items_arr[$key_arr_new_new]['pc_recommend_detail_id'] = $value['detail_id'];
                            $items_arr[$key_arr_new_new]['product_id'] = $value['product_id'];
                            $items_arr[$key_arr_new_new]['quantity'] = $value['detail_quantity'] - $items_arr[$key_arr]['remain_quantity'];
                            $items_arr[$key_arr_new_new]['remain_quantity'] = $value['detail_quantity'] - $items_arr[$key_arr_new_new]['quantity'];
                        }
                    }
                }        
           }
           //System::debug($items_arr);
           foreach($items_arr as $key => $value)
           {
                if(isset($list_product_full[$value['pc_recommend_detail_id']]))
                {
                    if(($list_product_full[$value['pc_recommend_detail_id']]['quantity'] - $value['quantity']) > 0)
                    {
                        $list_product_full[$value['pc_recommend_detail_id']]['quantity'] = $list_product_full[$value['pc_recommend_detail_id']]['quantity'] - $value['quantity'];                        
                    }else
                    {
                        unset($list_product_full[$value['pc_recommend_detail_id']]);
                    }
                }
           }
           **/
           $quantity_use_group = array();
           foreach($quantity_use as $key=>$value){
                if(!isset($quantity_use_group[$value['order_id']])){
                    $quantity_use_group[$value['order_id']]['id'] = $value['order_id'];
                    $quantity_use_group[$value['order_id']]['quantity_use'] = $value['quantity'];
                    $quantity_use_group[$value['order_id']]['quantity'] = 0;
                    $quantity_use_group[$value['order_id']]['child'] = array();
                }
                $quantity_use_group[$value['order_id']]['quantity'] += $value['detail_quantity'];
                $quantity_use_group[$value['order_id']]['child'][$value['detail_id']]['id'] = $value['detail_id'];
                $quantity_use_group[$value['order_id']]['child'][$value['detail_id']]['quantity'] = $value['detail_quantity'];
           }
           //System::debug($quantity_use_group); die;
           foreach($quantity_use_group as $key=>$value){
                // san pham da duoc tao don hang het
                if($value['quantity_use']==$value['quantity']){
                    foreach($value['child'] as $k=>$v){
                        if(isset($list_product_full[$v['id']]))
                            unset($list_product_full[$v['id']]);
                    }
                }else{
                    // san pham chi tao don hang 1 phan
                    foreach($value['child'] as $k=>$v){
                        if(isset($list_product_full[$v['id']])){
                            if($value['quantity_use']>=$list_product_full[$v['id']]['quantity']){
                                $value['quantity_use'] -= $list_product_full[$v['id']]['quantity'];
                                unset($list_product_full[$v['id']]);
                            }else{
                                $list_product_full[$v['id']]['quantity'] -= $value['quantity_use'];
                                $value['quantity_use'] = 0;
                            }
                        }
                    }
                }
           }
            /**
            Mac dinh Khong thuc hien gom nhom so luong san pham cung bo phan 
            **/
            foreach($list_product_full as $key=>$value)
            {
                $id_key = $key;
                if(isset($list_product_full[$key]))
                {
                    //----------------
                    if($flag!='')
                    {
                        $delivery_date = date('d.m.Y',$value['delivery_date']);
                        $id_key = $value['product_id']."_".$value['portal_department_id']."_".$delivery_date."_".$value['note'];
                        if(isset($list_product[$id_key]))
                        {
                            $list_product[$id_key]['quantity'] += $list_product_full[$key]['quantity'];
                            $list_product[$id_key]['pc_recommend_detail_id_list'] .= ','.$value['id'];
                        }
                        else
                        {
                            $list_product_full[$key]['pc_recommend_detail_id_list'] = $value['id'];
                            $list_product_full[$key]['tax_amount'] = 0;
                            if( $value['warehouse_pc_id']!='')
                            {
                                $product_remain = get_remain_products($value['warehouse_pc_id'],false,$value['product_id']);
                                $list_product_full[$key]['wh_remain'] = isset($product_remain[$value['product_id']])?$product_remain[$value['product_id']]['remain_number']:0;
                            }
                            else
                                $list_product_full[$key]['wh_remain']  = 0;
                            $list_product_full[$key]['delivery_date_conver'] = date('d/m/Y',$value['delivery_date']);
                            $list_product[$id_key] = $list_product_full[$key];
                        }
                    }
                    else
                    {
                        $list_product_full[$key]['pc_recommend_detail_id_list'] = $value['id'];
                        $list_product_full[$key]['tax_amount'] = 0;
                        if( $value['warehouse_pc_id']!='')
                        {
                            $product_remain = get_remain_products($value['warehouse_pc_id'],false,$value['product_id']);
                            $list_product_full[$key]['wh_remain'] = isset($product_remain[$value['product_id']])?$product_remain[$value['product_id']]['remain_number']:0;
                        }
                        else
                            $list_product_full[$key]['wh_remain']  = 0;
                        $list_product_full[$key]['delivery_date_conver'] = date('d/m/Y',$value['delivery_date']);
                        $list_product[$key] = $list_product_full[$key];
                    }
                }
                
            }
            echo json_encode($list_product);
            DB::close();
        }
    }
?>