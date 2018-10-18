<?php
define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
set_include_path(ROOT_PATH);
require_once 'packages/core/includes/system/config.php';
    $cond = $invoice_cond = '1=1';
    $warehouse_cond = '';
    $warehouse_cond .= ' and wh_invoice_detail.product_id = \''.$_REQUEST['product_id']. '\'';
    $cond .= ' and wh_start_term_remain.product_id = \''.$_REQUEST['product_id']. '\'';
    $cond.=' and wh_start_term_remain.warehouse_id='.$_REQUEST['warehouse_id'];
    $warehouse_cond.=' and wh_invoice_detail.warehouse_id='.$_REQUEST['warehouse_id'].' ';
    $products = array();
    $sql_invoice = '
        SELECT 
    		wh_invoice_detail.id,
            wh_invoice_detail.to_warehouse_id,
            wh_invoice_detail.num,
            wh_invoice.id as invoice_id,
            (CASE
                WHEN money_add is null and (wh_invoice_detail.payment_price is not null and wh_invoice_detail.payment_price != 0) THEN wh_invoice_detail.payment_price
                WHEN money_add is null and (wh_invoice_detail.payment_price is null or wh_invoice_detail.payment_price = 0) THEN wh_invoice_detail.price * wh_invoice_detail.num
                ELSE wh_invoice_detail.money_add
             END
            ) as payment_price,
            wh_invoice_detail.product_id,
            wh_invoice.type
    	FROM
    		wh_invoice_detail
    		INNER JOIN wh_invoice on wh_invoice.id = wh_invoice_detail.invoice_id
    		INNER JOIN product on product.id = wh_invoice_detail.product_id
            INNER JOIN unit on unit.id = product.unit_id 
            INNER JOIN product_category ON product_category.id = product.category_id
    	WHERE
    		'.$invoice_cond.$warehouse_cond.'
    ';
    $product_invoice = DB::fetch_all($sql_invoice);
    $items = $product_invoice;
    $old_items = array();
	if(is_array($items))
	{
		foreach($items as $key=>$value)
        {
			$product_id = $value['product_id'];
			if(isset($old_items[$product_id]))
            {
				if($value['type']=='IMPORT' or $value['to_warehouse_id'] == $_REQUEST['warehouse_id'])
                {
					$old_items[$product_id]['remain_invoice'] =  System::calculate_number(round($old_items[$product_id]['remain_invoice'], 8)) + System::calculate_number(round($value['num'],8));
                    $old_items[$product_id]['remain_money'] =  System::calculate_number($old_items[$product_id]['remain_money']) + System::calculate_number($value['payment_price']);
                }
				else
                {
                    if($value['type']=='EXPORT' and $value['to_warehouse_id'] != $_REQUEST['warehouse_id'])
                    {
						$old_items[$product_id]['remain_invoice'] =  System::calculate_number(round($old_items[$product_id]['remain_invoice'], 8)) - System::calculate_number(round($value['num'],8));
                        $old_items[$product_id]['remain_money'] =  System::calculate_number($old_items[$product_id]['remain_money']) - System::calculate_number($value['payment_price']);
                    }
                }
			}
            else
            {
                $old_items[$product_id]['id'] = $value['product_id'];
                $old_items[$product_id]['remain_invoice'] = 0;
                $old_items[$product_id]['remain_money'] = 0;
				if($value['type']=='IMPORT' or $value['to_warehouse_id'] == $_REQUEST['warehouse_id'])
                {
					$old_items[$product_id]['remain_invoice'] =  System::calculate_number(round($old_items[$product_id]['remain_invoice'], 8)) + System::calculate_number(round($value['num'],8));
                    $old_items[$product_id]['remain_money'] =  System::calculate_number($old_items[$product_id]['remain_money']) + System::calculate_number($value['payment_price']);
                }
                //PX ma kho xuat den khong phai la kho can tinh(tranh th tao PX ma tu kho A den kho A)
				if($value['type']=='EXPORT' and $value['to_warehouse_id'] != $_REQUEST['warehouse_id'])
                {
					$old_items[$product_id]['remain_invoice'] =  System::calculate_number(round($old_items[$product_id]['remain_invoice'], 8)) - System::calculate_number(round($value['num'],8));
                    $old_items[$product_id]['remain_money'] =  System::calculate_number($old_items[$product_id]['remain_money']) - System::calculate_number($value['payment_price']);
                }
			}
		}
	}
    $product_invoice = $old_items;
    /**
     * END 
    **/
    
    /**
     * BEGIN
     * doan nay tra ve so ton tien va luowng
     * qua so du dau ki
    **/
    //tinh so ton dau ki cua sp
    $sql_start_term_remain = '
        SELECT
            wh_start_term_remain.product_id as id,
            product.name_'.Portal::language().' as product_name,
            unit.name_'.Portal::language().' as unit_name,
            wh_start_term_remain.warehouse_id,
            SUM(
                CASE 
                    WHEN wh_start_term_remain.quantity >0 THEN wh_start_term_remain.quantity
                    ELSE 0 
                END
            ) as remain_number,
            wh_start_term_remain.total_start_term_price as remain_money,
            wh_start_term_remain.start_term_price
        FROM
            wh_start_term_remain
            INNER JOIN product on product.id = wh_start_term_remain.product_id
            INNER JOIN unit on unit.id = product.unit_id
            INNER JOIN product_category ON product_category.id = product.category_id
        WHERE
            '.$cond.'
        GROUP BY
            wh_start_term_remain.product_id,
            wh_start_term_remain.total_start_term_price,
            product.name_'.Portal::language().',
            unit.name_'.Portal::language().',
            wh_start_term_remain.warehouse_id,
            wh_start_term_remain.start_term_price
            
    ';
    $start_term_remain = DB::fetch_all($sql_start_term_remain);
    $prices = DB::fetch_all("
                    SELECT dtail.product_id as id,
                        dtail.price,
                        dtail.average_price,
                        dtail.time_calculation
                    FROM wh_invoice_detail dtail
                    where dtail.id in (
                        SELECT
                            max(wh_invoice_detail.id)
                        FROM
                            wh_invoice_detail
                            INNER JOIN wh_invoice on wh_invoice.id = wh_invoice_detail.invoice_id
                        WHERE
                            ".$invoice_cond." 
                            AND wh_invoice.type='IMPORT'
                            ".$warehouse_cond."
                        GROUP BY wh_invoice_detail.product_id
                    )");
    
    foreach($start_term_remain as $key=>$value)
    {
        if(isset($product_invoice[$key]))
        {
            $price = array('id'=>0,'price'=>0,'average_price'=>0);
            if(isset($prices[$key]))
            {
                $price = $prices[$key];
            }
            if(Url::get('type')=='IMPORT')
            {
                $lastest_imported_price = $price['price'];
            }
            else
            {
                $lastest_imported_price = 0;
            }
//            else//Xuat thi lay gia TB
//            {
                $price = $price['average_price'];
            //}
            
            //So ton thuc su
            $start_term_remain[$key]['remain_number'] = round(($value['remain_number']?$value['remain_number']:0)+($product_invoice[$key]['remain_invoice']?$product_invoice[$key]['remain_invoice']:0),8);
            $start_term_remain[$key]['remain_money'] = round(($value['remain_money']?$value['remain_money']:0)+($product_invoice[$key]['remain_money']?$product_invoice[$key]['remain_money']:0),8);
            $start_term_remain[$key]['lastest_imported_price'] = $lastest_imported_price?$lastest_imported_price:0;
            //===Gia TB se tinh theo cach sau, khong lay o PN gan nhat nua====/
            
            $start_term_remain[$key]['price'] = $start_term_remain[$key]['remain_number'] != 0? $start_term_remain[$key]['remain_money']/$start_term_remain[$key]['remain_number'] : 0;
            
        }
    }
        
        if($_REQUEST['type']=='IMPORT')//phieu nhap => lay moi sp da khai bao
        {
            $products = DB::fetch_all('
    				SELECT
                         product.id,
                         product.id as code,
                         product.name_'.Portal::language().' as name,
                         unit.id as unit_id,
                         unit.name_'.Portal::language().' as unit,
                         product_category.id as category_id,
                         product_category.name as category,
                         product.type
    				FROM
    					product
    					INNER JOIN unit ON unit.id = product.unit_id
                        INNER JOIN product_category ON product_category.id = product.category_id
    				WHERE
    					product.type in (\'GOODS\',\'TOOL\',\'EQUIPMENT\',\'MATERIAL\',\'DRINK\',\'PRODUCT\')
                        and product.status = \'avaiable\'
                        and product.id = \''.$_REQUEST['product_id'].'\'
    				ORDER BY
    					product.id
    		');
        }
        else //phieu xuat, chi lay san pham da nhap kho (va` ton` dau` ki`)
        {
            $products = DB::fetch_all('
                    				SELECT
                                        DISTINCT wh_invoice_detail.product_id as id,
                                        wh_invoice_detail.product_id as code,
                                        product.name_'.Portal::language().' as name,
                                        unit.id as unit_id,
                                        unit.name_'.Portal::language().' as unit,
                                        product_category.id as category_id,
                                        product_category.name as category,
                                        product.type
                    				FROM
                    					wh_invoice_detail
                                        inner join wh_invoice on wh_invoice.id = wh_invoice_detail.invoice_id
                                        inner join product on wh_invoice_detail.product_id = product.id
                                        inner join unit on product.unit_id = unit.id
                                        inner join product_category on product.category_id = product_category.id
                    				WHERE
                                        product.status = \'avaiable\'
                                        AND product.type != \'SERVICE\'
                                        --AND product.type != \'PRODUCT\'
                                        AND wh_invoice.type = \'IMPORT\'
                                        AND product.id = \''.$_REQUEST['product_id'].'\'
                                        AND wh_invoice.warehouse_id = '.$_REQUEST['warehouse_id'].'
                                        AND wh_invoice.portal_id = \''.PORTAL_ID.'\'
                                    UNION
                                    SELECT
                                        wh_start_term_remain.product_id as id,
                                        wh_start_term_remain.product_id as code,
                                        product.name_'.Portal::language().' as name,
                                        unit.id as unit_id,
                                        unit.name_'.Portal::language().' as unit,
                                        product_category.id as category_id,
                                        product_category.name as category,
                                        product.type
                    				FROM
                    					wh_start_term_remain
                                        inner join product on wh_start_term_remain.product_id = product.id
                                        inner join unit on product.unit_id = unit.id
                                        inner join product_category on product.category_id = product_category.id
                    				WHERE
                                        product.status = \'avaiable\'
                                        AND product.type != \'SERVICE\'
                                        --AND product.type != \'PRODUCT\'
                                        AND product.id = \''.$_REQUEST['product_id'].'\'
                                        AND wh_start_term_remain.warehouse_id = '.$_REQUEST['warehouse_id'].'
                                        AND wh_start_term_remain.portal_id = \''.PORTAL_ID.'\'
    		                          ');
        }
        $product_remain = array();
		$product_remain = $start_term_remain;
        foreach($products as $id=>$product)
		{
			if(isset($product_remain[$product['code']]) and $product['code']==$product_remain[$product['code']]['id'])
			{
				/**
                 * BEGIN edit
                 * L?y ra s? lu?ng t?n 
                 * s? ti?n t?n 
                **/
				$products[$id]['remain_num'] = $product_remain[$product['code']]['remain_number'];
                $products[$id]['remain_money'] = $product_remain[$product['code']]['remain_money'];
                /**
                 * END edit
                 * L?y ra s? lu?ng t?n 
                 * s? ti?n t?n 
                **/
                //Ton tai gia (tuc la da co phieu nhap, tinh dc gia TB roi`)
                if(isset($product_remain[$product['code']]['price']))
				{
					$products[$id]['old_price'] = $product_remain[$product['code']]['price'];
				}
				else
				{
				    //neu khong thi lay gia la gia ton dau ki
    				if(isset($product_remain[$product['code']]['start_term_price']))
    				    $products[$id]['old_price'] = $product_remain[$product['code']]['start_term_price'];
                    else
                        $products[$id]['old_price'] = 0;
				}
                //$products[$id]['lastest_imported_price'] = 0; 
                if(Url::get('type') == 'IMPORT')
                {
                    $products[$id]['lastest_imported_price'] = 0;  
                    if(isset($product_remain[$product['code']]['lastest_imported_price']) and $product_remain[$product['code']]['lastest_imported_price'] > 0)
    				    $products[$id]['lastest_imported_price'] = $product_remain[$product['code']]['lastest_imported_price'];
                    else
                        $products[$id]['lastest_imported_price'] = 0;  
                }
			}
			else
			{
				$products[$id]['remain_num'] = 0;
                //them o day nua nha
                $products[$id]['remain_money'] = 0;
				$products[$id]['old_price'] = 0;
                $products[$id]['lastest_imported_price'] = 0;  
			}
		}
echo json_encode($products);
?>