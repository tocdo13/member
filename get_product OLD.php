<?php
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once 'packages/core/includes/system/config.php';
	if(Url::get('q'))
    {
		if(Url::get('bar'))//sua ngay 3/10/2012
        {
            /*
			$items = DB::fetch_all('
				select 
					res_product.code as id,res_product.name_'.Portal::language().' as name,rownum
				from
					res_product
				where
					UPPER(res_product.code) LIKE \'%'.strtoupper(Url::sget('q')).'%\' and portal_id=\''.PORTAL_ID.'\'
					AND (rownum > 0 AND rownum <= 1000)
				order by
					res_product.id
			');
            */
			$items_product = DB::fetch_all('
                        				select 
                                            product_price_list.id,
                    						product_price_list.product_id as code,
                    						product.name_'.Portal::language().' as name,
                                            rownum
                        				from
                    						product_price_list
                                            INNER JOIN product on product.id = product_price_list.product_id
                        				where
                        					UPPER(product_price_list.product_id) LIKE \'%'.strtoupper(Url::get('q')).'%\' 
                                            AND product_price_list.portal_id=\''.PORTAL_ID.'\'
                                            AND product_price_list.department_code = \'RES\'
                        					AND (rownum > 0 AND rownum <= 1000)
                        				order by
                        					product_price_list.product_id
                        			     ');
            foreach($items_product as $key=>$value)
            {
                echo $value['code'].'|'.$value['name']."\n";
            }
		}
        else
        if(Url::get('wh_invoice'))
        {
            //exit();
            //Danh cho phieu nhap kho                        
            if(Url::get('type')=='IMPORT')
            {
                $items_product =  DB::fetch_all('
                                				SELECT 
                                					product.id,
                                                    product.id as code,
                                                    product.name_'.Portal::language().' as name,
                                                    rownum
                                				FROM 
                                					product 
                                                    inner join unit on product.unit_id = unit.id
                                                    inner join product_category on product.category_id = product_category.id
                                				WHERE
                                					UPPER(product.id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'	
                                					AND (rownum > 0 AND rownum <= 1000)
                                                    AND product.status = \'avaiable\'
                                                    AND product.type != \'SERVICE\'  
                                                    AND product.type != \'PRODUCT\' 
                                				ORDER BY
                                					product.id
                                			'); 
            }
            //(Url::get('type')=='EXPORT')
            else//danh cho phieu xuat, va bao cao the kho, bao cao ton tong hop, chi xuat cac mat hang da duoc nhap kho (va` ton` dau` ki`)
            {
                if(Url::get('for_report'))//bc ton tong hop
                {
                    $structure_id = DB::fetch('Select * from warehouse where id = '.Url::get('warehouse_id'),'structure_id');
                    $group_wh = DB::fetch_all('Select * from warehouse where '.IDStructure::child_cond($structure_id).' order by structure_id');
                    $cond ='(';
                    foreach($group_wh as $wh_id=>$value)
                    {
                        $cond.= $wh_id.',';
                    }
                    $cond = trim($cond,',').')';
                    
                    $items_product =  DB::fetch_all('
                                                
                                				SELECT
                                                    DISTINCT wh_invoice_detail.product_id as id,
                                                    wh_invoice_detail.product_id as code,
                                                    product.name_'.Portal::language().' as name,
                                                    rownum
                                				FROM 
                                					wh_invoice_detail
                                                    inner join wh_invoice on wh_invoice.id = wh_invoice_detail.invoice_id 
                                                    inner join product on wh_invoice_detail.product_id = product.id
                                                    inner join unit on product.unit_id = unit.id
                                                    inner join product_category on product.category_id = product_category.id
                                				WHERE
                                					UPPER(product.id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'	
                                					AND (rownum > 0 AND rownum <= 1000)
                                                    AND product.status = \'avaiable\'
                                                    AND product.type != \'SERVICE\'  
                                                    AND product.type != \'PRODUCT\'
                                                    AND wh_invoice.type = \'IMPORT\'
                                                    AND wh_invoice.warehouse_id IN '.$cond.'
                                                    AND wh_invoice.portal_id = \''.PORTAL_ID.'\' 
                                                
                                                    
                                                UNION
                                                
                                                
                                                SELECT
                                                    wh_start_term_remain.product_id as id,
                                                    wh_start_term_remain.product_id as code,
                                                    product.name_'.Portal::language().' as name,
                                                    rownum
                                				FROM 
                                					wh_start_term_remain
                                                    inner join product on wh_start_term_remain.product_id = product.id
                                                    inner join unit on product.unit_id = unit.id
                                                    inner join product_category on product.category_id = product_category.id
                                				WHERE
                                					UPPER(product.id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'	
                                					AND (rownum > 0 AND rownum <= 1000)
                                                    AND product.status = \'avaiable\'
                                                    AND product.type != \'SERVICE\'  
                                                    AND product.type != \'PRODUCT\'
                                                    AND wh_start_term_remain.warehouse_id IN '.$cond.'
                                                    AND wh_start_term_remain.portal_id = \''.PORTAL_ID.'\'  
                                                
                                			');
                }
                else
                {
                    $items_product =  DB::fetch_all('
                                                
                                				SELECT
                                                    DISTINCT wh_invoice_detail.product_id as id,
                                                    wh_invoice_detail.product_id as code,
                                                    product.name_'.Portal::language().' as name,
                                                    rownum
                                				FROM 
                                					wh_invoice_detail
                                                    inner join wh_invoice on wh_invoice.id = wh_invoice_detail.invoice_id 
                                                    inner join product on wh_invoice_detail.product_id = product.id
                                                    inner join unit on product.unit_id = unit.id
                                                    inner join product_category on product.category_id = product_category.id
                                				WHERE
                                					UPPER(product.id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'	
                                					AND (rownum > 0 AND rownum <= 1000)
                                                    AND product.status = \'avaiable\'
                                                    AND product.type != \'SERVICE\'  
                                                    AND product.type != \'PRODUCT\'
                                                    AND wh_invoice.type = \'IMPORT\'
                                                    AND wh_invoice.warehouse_id = '.Url::get('warehouse_id').'
                                                    AND wh_invoice.portal_id = \''.PORTAL_ID.'\' 
                                                
                                                    
                                                UNION
                                                
                                                
                                                SELECT
                                                    wh_start_term_remain.product_id as id,
                                                    wh_start_term_remain.product_id as code,
                                                    product.name_'.Portal::language().' as name,
                                                    rownum
                                				FROM 
                                					wh_start_term_remain
                                                    inner join product on wh_start_term_remain.product_id = product.id
                                                    inner join unit on product.unit_id = unit.id
                                                    inner join product_category on product.category_id = product_category.id
                                				WHERE
                                					UPPER(product.id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'	
                                					AND (rownum > 0 AND rownum <= 1000)
                                                    AND product.status = \'avaiable\'
                                                    AND product.type != \'SERVICE\'  
                                                    AND product.type != \'PRODUCT\'
                                                    AND wh_start_term_remain.warehouse_id = '.Url::get('warehouse_id').'
                                                    AND wh_start_term_remain.portal_id = \''.PORTAL_ID.'\'  
                                                
                                			');
                    
                }
            }
            foreach($items_product as $key=>$value)
            {
                echo $value['code'].'|'.$value['name']."\n";
            } 
		}
        else
        if(Url::get('massage'))//sua ngay 3/10/2012
        {
			/*
            $items =  DB::fetch_all('
				SELECT 
					code as id,name_'.Portal::language().' as name
				FROM 
					massage_product
				WHERE
					UPPER(code) LIKE \'%'.strtoupper(Url::sget('q')).'%\'	
				ORDER BY
					code
			');
            */
            
			$items_product = DB::fetch_all('
                        				select 
                                            product_price_list.id,
                    						product_price_list.product_id as code,
                    						product.name_'.Portal::language().' as name
                        				from
                    						product_price_list
                                            INNER JOIN product on product.id = product_price_list.product_id
                        				where
                        					UPPER(product_price_list.product_id) LIKE \'%'.strtoupper(Url::get('q')).'%\' 
                                            AND product_price_list.portal_id=\''.PORTAL_ID.'\'
                                            AND product_price_list.department_code = \'SPA\'
                        				order by
                        					product_price_list.product_id
                        			     ');
            foreach($items_product as $key=>$value)
            {
                echo $value['code'].'|'.$value['name']."\n";
            }
            
        }
        else
        if(Url::get('equipment'))//Ducnm them //sua ngay 3/10/2012
        { 
			$items_product = DB::fetch_all('
                        				select 
                                            product_price_list.id,
                    						product_price_list.product_id as code,
                    						product.name_'.Portal::language().' as name
                        				from
                    						product_price_list
                                            INNER JOIN product on product.id = product_price_list.product_id
                        				where
                        					UPPER(product_price_list.product_id) LIKE \'%'.strtoupper(Url::get('q')).'%\' 
                                            AND product_price_list.portal_id=\''.PORTAL_ID.'\'
                                            AND product_price_list.department_code = \'HK\'
                                            AND product.type = \'EQUIPMENT\'
                        				order by
                        					product_price_list.product_id
                        			     ');
            foreach($items_product as $key=>$value)
            {
                echo $value['code'].'|'.$value['name']."\n";
            }
            
		}
		else
        if(Url::get('equipment_invoice'))
        {
			$items_product = DB::fetch_all('
                        				select 
                                            product_price_list.id,
                    						product_price_list.product_id as code,
                    						product.name_'.Portal::language().' as name
                        				from
                    						product_price_list
                                            INNER JOIN product on product.id = product_price_list.product_id
                        				where
                        					UPPER(product_price_list.product_id) LIKE \'%'.strtoupper(Url::get('q')).'%\' 
                                            AND product_price_list.portal_id=\''.PORTAL_ID.'\'
                                            AND product_price_list.department_code = \'HK\'
                                            AND product.type = \'EQUIPMENT\'
                        				order by
                        					product_price_list.product_id
                        			     ');
            foreach($items_product as $key=>$value)
            {
                echo $value['code'].'|'.$value['name']."\n";
            }
            
		}
        else 
        if(Url::get('minibar'))//sua ngay 3/10/2012
        { 
			$sql = '
					select
                        product_price_list.id,
						product_price_list.product_id as code,
						product.name_'.Portal::language().' as name
					from
						product_price_list
                        INNER JOIN product on product.id = product_price_list.product_id
					where
						UPPER(product_price_list.product_id) LIKE \'%'.strtoupper(Url::get('q')).'%\' 
                        AND product_price_list.portal_id=\''.PORTAL_ID.'\'
                        AND product_price_list.department_code = \'MINIBAR\'
					order by
						product_price_list.product_id	
			';
			$items_product = DB::fetch_all($sql);
            foreach($items_product as $key=>$value)
            {
                echo $value['code'].'|'.$value['name']."\n";
            }
        }
        else 
        if(Url::get('product'))// lay cac product
        {
            $items_product =  DB::fetch_all('
    				SELECT 
    					product.id,
                        product.id as code,
                        product.name_'.Portal::language().' as name,
                        product.unit_id,
                        unit.name_'.Portal::language().' as unit,
                        rownum
    				FROM 
    					product inner join unit on product.unit_id = unit.id
    				WHERE
    					UPPER(product.id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'	
    					AND (rownum > 0 AND rownum <= 1000)
    				ORDER BY
    					product.id
    			');
            foreach($items_product as $key=>$value)
            {
                echo $value['code'].'|'.$value['name'].' ('.$value['unit'].')'."\n";
            }
        }
        else
        if(Url::get('wh_start_term_remain'))//khai bao ton dau ki cho warehouse
        {
            $items_product =  DB::fetch_all('
                            				SELECT 
                            					product.id,
                                                product.id as code,
                                                product.name_'.Portal::language().' as name,
                                                product.unit_id,
                                                unit.name_'.Portal::language().' as unit,
                                                rownum
                            				FROM 
                            					product inner join unit on product.unit_id = unit.id
                            				WHERE
                            					UPPER(product.id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'	
                            					AND (rownum > 0 AND rownum <= 1000)
                                                AND product.type != \'SERVICE\'  
                                                AND product.type != \'PRODUCT\' 
                            				ORDER BY
                            					product.id
                            			');
            foreach($items_product as $key=>$value)
            {
                echo $value['code'].'|'.$value['name'].' ('.$value['unit'].')'."\n";
            }
        }
        else
        if(Url::get('material'))//lay nguyen vat lieu dung trong dinh muc san pham
        {
            $items_product =  DB::fetch_all('
                            				SELECT 
                            					product.id,
                                                product.id as code,
                                                product.name_'.Portal::language().' as name,
                                                rownum
                            				FROM 
                            					product 
                                                inner join unit on product.unit_id = unit.id
                                                left join product_category on product.category_id = product_category.id
                            				WHERE
                            					UPPER(product.id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'	
                            					AND (rownum > 0 AND rownum <= 1000)
                                                AND product.type = \'MATERIAL\'  
                            				ORDER BY
                            					product.id
                            			');
            foreach($items_product as $key=>$value)
            {
                echo $value['code'].'|'.$value['name']."\n";
            }
        }

		if(isset($items))
        {
            $items = String::get_list($items);
    		echo "'-----'|'---'\n";
    		foreach($items as $key=>$value){
    			$k = trim($key);
    			$v = $value;
    			$k = preg_replace('/[\n\s\t]+/',"",$k);
    			echo $k.'|'.$v."\n";
    		}
        }        

		DB::close();
	}
?>