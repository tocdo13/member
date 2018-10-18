<?php
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once 'packages/core/includes/system/config.php';
                
	if(URL::get('q')){
       $list_spr = array();
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
                                        pc_recommend_detail.order_id
                                    FROM
                                        pc_recommend_detail
                                        INNER JOIN pc_recommendation on pc_recommend_detail.recommend_id=pc_recommendation.id
                                        INNER JOIN product on pc_recommend_detail.product_id=product.id
                                        INNER JOIN unit on product.unit_id=unit.id
                                        INNER JOIN portal_department on portal_department.id=pc_recommendation.portal_department_id
                                        INNER JOIN department on department.code=portal_department.department_code
                                    WHERE
                                        pc_recommendation.confirm is not null
                                        AND pc_recommendation.status is null
                                    ORDER BY
                                        pc_recommend_detail.id
                                    ");
       $list_product = array();
       foreach($list_product_full as $key=>$value)
       {
            if($value['order_id']!='')
            {
                $quantity_use = DB::fetch('select sum(quantity) as total from pc_order_detail where id in ('.$value['order_id'].')','total');
                if(($value['quantity']-$quantity_use)>0)
                {
                    $list_product_full[$key]['quantity'] = $value['quantity']-$quantity_use;
                }
                else
                {
                    unset($list_product_full[$key]);
                }
            }
            if(isset($list_product_full[$key]))
            {
                $list_product_full[$key]['pc_recommend_detail_id_list'] = $value['id'];
                $list_product_full[$key]['tax_amount'] = 0;
                if( $value['warehouse_pc_id']!='')
                {
                    
                    $list_product_full[$key]['wh_remain'] = isset($product_remain[$value['product_id']])?$product_remain[$value['product_id']]['remain_number']:0;
                }
                else
                    $list_product_full[$key]['wh_remain']  = 0;
            }
       }
       
       $list_product = $list_product_full;
       if(sizeof($list_product)>0) /** neu co san pham de xuat **/
       {

            //danh sach nha cung cap 
            $list_supplier = DB::fetch_all("SELECT * FROM supplier");
            //lay ra danh sach bao gia nha cung cap voi moi san pham
            $list_sup_price = DB::fetch_all("
                                            SELECT 
                                                CONCAT(concat(pc_sup_price.product_id,'_'),pc_sup_price.supplier_id) as id,
                                                pc_sup_price.product_id,
                                                pc_sup_price.supplier_id,
                                                pc_sup_price.price,
                                                product.tax_percent
                                            FROM
                                                pc_sup_price
                                            INNER JOIN product ON product.id=pc_sup_price.product_id
                                            WHERE
                                                (pc_sup_price.starting_date is null)
                                                OR (pc_sup_price.starting_date is not null ANd pc_sup_price.starting_date<='".Date_Time::to_orc_date(date('d/m/Y'))."' AND pc_sup_price.ending_date is null)
                                                OR (pc_sup_price.starting_date is not null AND pc_sup_price.ending_date is not null AND pc_sup_price.starting_date<='".Date_Time::to_orc_date(date('d/m/Y'))."' AND pc_sup_price.ending_date>='".Date_Time::to_orc_date(date('d/m/Y'))."')
                                            ORDER BY
                                                pc_sup_price.supplier_id
                                            ");
                                                                                   
            
            $list_total_amount_sup = select_supplier_auto($list_supplier,$list_product,$list_sup_price);
            
            $tas = DB::fetch_all('select supplier.id, supplier.name from supplier where UPPER(name) LIKE \'%'.strtoupper(Url::sget('q')).'%\'');
            foreach($list_total_amount_sup as $key=>$value)
            {
                echo  $value['name'].'|'.$value['id'].'-'.$value['total_amount_product']."\n";
            } 
    		DB::close();                                    
       }
       else
       {
            $list_spr['no_data'] = portal::language('no_record');
       }	
	}
      function select_supplier_auto($sup_list,$product_list,$sup_price)
        {
            foreach($sup_list as $key=>$value)
            {
                $check = true;
                $sup_list[$key]['total_amount_product'] = 0;
                $sup_list[$key]['full_product'] = 1;
                foreach($product_list as $id=>$content)
                {
                    
                    if(isset($sup_price[$content['product_id']."_".$value['id']]))
                    {
                        $sup_list[$key]['total_amount_product'] += $sup_price[$content['product_id']."_".$value['id']]['price']*$content['quantity'];
                    }
                    else
                    {
                        $sup_list[$key]['full_product'] = 0;
                        $sup_list[$key]['total_amount_product'] += 0;
                    }
                }   
            }
            return $sup_list;
        }             
?>
