<?php
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once 'packages/core/includes/system/config.php';
	require_once 'packages/core/includes/utils/vn_code.php';
    date_default_timezone_set('Asia/Saigon');
	if(Url::get('q'))
    {
        $today = Date_Time::to_time(date("d/m/Y"));
		if(Url::get('bar'))//sua ngay 3/10/2012
        {
            $cond = '1=1';
            if(isset($_REQUEST['product_type']))
            {
                $cond = 'product.type = \''.Url::get('product_type').'\'';
            }
			$items_product = DB::fetch_all('
                        				select 
                                            product_price_list.id,
                    						product_price_list.product_id as code,
                    						product.name_'.Portal::language().' as name,
											product_price_list.department_code,
                                            rownum
                        				from
                    						product_price_list
                                            INNER JOIN product on product.id = product_price_list.product_id
                                            INNER JOIN unit on unit.id = product.unit_id
				                            INNER JOIN product_category on product.category_id = product_category.id
                        				where
                                            '.$cond.'
                        					AND (UPPER(product_price_list.product_id) LIKE \'%'.strtoupper(Url::get('q')).'%\' 
													OR (UPPER(product_price_list.product_id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
														OR ((LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\'))))
                                            AND product_price_list.portal_id=\''.PORTAL_ID.'\'
                                            AND product_price_list.department_code = \''.Url::get('warehouse_code').'\' 
                                            AND (product_price_list.end_date is null OR product_price_list.end_date>=\''.Date_Time::to_orc_date(date('d/m/y')).'\')
                        					-- THANH ADD dieu kien lay gia theo khoang thoi gian 
                                            AND ( (DATE_TO_UNIX(product_price_list.start_date)<='.$today.' AND '.$today.'<=DATE_TO_UNIX(product_price_list.end_date)) OR (DATE_TO_UNIX(product_price_list.start_date)<='.$today.' AND product_price_list.end_date IS NULL ) OR ( '.$today.'<=DATE_TO_UNIX(product_price_list.end_date) AND product_price_list.start_date IS NULL ) OR ( product_price_list.start_date IS NULL AND product_price_list.end_date IS NULL ))
                                            -- END
                                            --AND (rownum > 0 AND rownum <= 1000)
                                            AND product.status = \'avaiable\'
                        				order by
                        					product_price_list.product_id
                        			     ');
		    foreach($items_product as $key=>$value)
            {
                if(Url::get('mice'))
                    echo convert_utf8_to_latin(mb_strtolower($value['name'],'utf-8')).'|'.$value['name'].'|'.$value['code']."\n";
                else
                    echo $value['code'].'|'.$value['name']."\n";
            }
		}
        else
        if(Url::get('wh_invoice'))
        {
            //Danh cho phieu nhap kho                        
            if(Url::get('type')=='IMPORT')
            {
                $items_product =  DB::fetch_all('
                                				SELECT 
                                					product.id,
                                                    product.id as code,
                                                    product.name_'.Portal::language().' as name
                                                    
                                				FROM 
                                					product 
                                                    inner join unit on product.unit_id = unit.id
                                                    inner join product_category on product.category_id = product_category.id
                                				WHERE
                                                    product.status = \'avaiable\'
                                                    AND product.type != \'SERVICE\'  
                                                    AND product.type != \'PRODUCT\' 
                                                    AND product.type != \'DRINK\' 
                                                    AND (UPPER(product.id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
														OR ((LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\')))
                                                    AND ROWNUM<1000
                                				ORDER BY
                                					product.id
                                			'); 
            }
            //(Url::get('type')=='EXPORT')
            else//danh cho phieu xuat, chi xuat cac mat hang da duoc nhap kho (va` ton` dau` ki`)
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
													(UPPER(product.id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
														OR ((LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\')))													
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
													(UPPER(product.id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
														OR ((LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\')))													
	                               					AND (rownum > 0 AND rownum <= 1000)
                                                    AND product.status = \'avaiable\'
                                                    AND product.type != \'SERVICE\'  
                                                    AND product.type != \'PRODUCT\'
                                                    AND wh_start_term_remain.warehouse_id = '.Url::get('warehouse_id').'
                                                    AND wh_start_term_remain.portal_id = \''.PORTAL_ID.'\'  
                                                
                                			');
            }
           
                if(count($items_product)==''){
                    echo 3;
                }
                if(Url::get('wh_invoice')==1){
                    foreach($items_product as $key=>$value)
                    {
                        echo $value['code'].'|'.$value['name']."\n";
                    } 
                }else{
                    foreach($items_product as $key=>$value)
                    {
                        echo $value['name'].'|'.$value['code']."\n";
                    } 
                }
		}
        else
        if(Url::get('massage'))//sua ngay 3/10/2012
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
											(UPPER(product_price_list.product_id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
												OR ((LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\')))						
                                            AND product_price_list.portal_id=\''.PORTAL_ID.'\'
                                            AND product_price_list.department_code = \'SPA\' 
                                            -- THANH ADD dieu kien lay gia theo khoang thoi gian 
                                            AND ( (DATE_TO_UNIX(product_price_list.start_date)<='.$today.' AND '.$today.'<=DATE_TO_UNIX(product_price_list.end_date)) OR (DATE_TO_UNIX(product_price_list.start_date)<='.$today.' AND product_price_list.end_date IS NULL ) OR ( '.$today.'<=DATE_TO_UNIX(product_price_list.end_date) AND product_price_list.start_date IS NULL ) OR ( product_price_list.start_date IS NULL AND product_price_list.end_date IS NULL ))
                                            -- END
                                            AND product.status = \'avaiable\'
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
											(UPPER(product_price_list.product_id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
												OR ((LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\')))						
                                            AND product_price_list.portal_id=\''.PORTAL_ID.'\'
                                            AND product_price_list.department_code = \'HK\'
                                            AND product.type = \'EQUIPMENT\'
                                            AND product.status = \'avaiable\'
                                            -- THANH ADD dieu kien lay gia theo khoang thoi gian 
                                            AND ( (DATE_TO_UNIX(product_price_list.start_date)<='.$today.' AND '.$today.'<=DATE_TO_UNIX(product_price_list.end_date)) OR (DATE_TO_UNIX(product_price_list.start_date)<='.$today.' AND product_price_list.end_date IS NULL ) OR ( '.$today.'<=DATE_TO_UNIX(product_price_list.end_date) AND product_price_list.start_date IS NULL ) OR ( product_price_list.start_date IS NULL AND product_price_list.end_date IS NULL ))
                                            -- END
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
										(UPPER(product_price_list.product_id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
											OR ((LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\')))						
                                            AND product_price_list.portal_id=\''.PORTAL_ID.'\'
                                            AND product_price_list.department_code = \'HK\'
                                            AND product.type = \'EQUIPMENT\'
                                            AND product.status = \'avaiable\'
                                            AND ((DATE_TO_UNIX(product_price_list.start_date)<=\''.$today.'\' AND DATE_TO_UNIX(product_price_list.end_date)>=\''.$today.'\') OR ( DATE_TO_UNIX(product_price_list.start_date)<=\''.$today.'\' AND product_price_list.end_date is null) OR ( DATE_TO_UNIX(product_price_list.end_date)>=\''.$today.'\' AND product_price_list.start_date is null) OR (product_price_list.start_date is null AND product_price_list.end_date is null))
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
						(UPPER(product_price_list.product_id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
							OR ((LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\')))						
                        AND product_price_list.portal_id=\''.PORTAL_ID.'\'
                        AND product_price_list.department_code = \'MINIBAR\'
                        AND product.status = \'avaiable\'
                        -- THANH ADD dieu kien lay gia theo khoang thoi gian 
                        AND ( (DATE_TO_UNIX(product_price_list.start_date)<='.$today.' AND '.$today.'<=DATE_TO_UNIX(product_price_list.end_date)) OR (DATE_TO_UNIX(product_price_list.start_date)<='.$today.' AND product_price_list.end_date IS NULL ) OR ( '.$today.'<=DATE_TO_UNIX(product_price_list.end_date) AND product_price_list.start_date IS NULL ) OR ( product_price_list.start_date IS NULL AND product_price_list.end_date IS NULL ))
                        -- END
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
        if(Url::get('amenities'))
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
						(UPPER(product_price_list.product_id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
							OR ((LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\')))						
                        AND product_price_list.portal_id=\''.PORTAL_ID.'\'
                        AND product_price_list.department_code = \'HK\'
                        AND product.status = \'avaiable\'
                        -- THANH ADD dieu kien lay gia theo khoang thoi gian 
                        AND ( (DATE_TO_UNIX(product_price_list.start_date)<='.$today.' AND '.$today.'<=DATE_TO_UNIX(product_price_list.end_date)) OR (DATE_TO_UNIX(product_price_list.start_date)<='.$today.' AND product_price_list.end_date IS NULL ) OR ( '.$today.'<=DATE_TO_UNIX(product_price_list.end_date) AND product_price_list.start_date IS NULL ) OR ( product_price_list.start_date IS NULL AND product_price_list.end_date IS NULL ))
                        -- END
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
            if(Url::get('type')){
                if(isset($_GET['search_type'])){
                    $cond = ' AND UPPER(product_category.code)=\'SETMENU\'';
                }
                else{
                    $cond=" ";
                }
                if(isset($_GET['department'])){
                    $department = $_GET['department'];
                } 
                if(Url::get('searchByCode')){
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
                        inner join product_category on product.category_id = product_category.id
    				WHERE
    					(UPPER(product.id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
						OR LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\')
    					AND (rownum > 0 AND rownum <= 1000)
                        AND product.status = \'avaiable\'
                        AND (product.type = \'PRODUCT\' OR product.type = \'DRINK\')
                        AND (product.id != \'DOUTSIDE\' OR product.id != \'FOUTSIDE\')
    				ORDER BY
    					product.id
    			');
                   if(Url::get('set_menu')){
                        if(isset($_GET['department'])){
                            $department = $_GET['department'];
                        }

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
                                inner join product_category on product.category_id = product_category.id
                                inner join product_price_list ON product.id = product_price_list.product_id
                                LEFT JOIN bar_set_menu ON bar_set_menu.code = product_price_list.product_id
            				WHERE
            					(UPPER(product.id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
        						OR LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\')
            					AND (rownum > 0 AND rownum <= 1000)
                                AND product.status = \'avaiable\'
                                AND (product.type = \'PRODUCT\' OR product.type = \'DRINK\' OR product.type = \'GOODS\')
                                AND (product.id != \'DOUTSIDE\' AND product.id != \'FOUTSIDE\' AND product.id!=\'SOUTSIDE\')
                                AND product_price_list.department_code=\''.$department.'\'
            					AND product_price_list.portal_id = \''.PORTAL_ID.'\'
            					AND (product_price_list.end_date is null OR product_price_list.end_date>=\''.Date_Time::to_orc_date(date('d/m/y')).'\')
                                AND bar_set_menu.id is NULL
            				ORDER BY
            					product.id
            			'); 

                    }  
                }
                else{
                   $items_product =  DB::fetch_all('
    				SELECT 
    					product.id,
                        product.name_'.Portal::language().' as code,
                        product.id as name,
                        product.unit_id,
                        unit.name_'.Portal::language().' as unit,
                        rownum
    				FROM 
    					product inner join unit on product.unit_id = unit.id
                        inner join product_category on product.category_id = product_category.id
    				WHERE
    					(UPPER(product.id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
						OR LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\')
    					AND (rownum > 0 AND rownum <= 1000)
                        AND product.status = \'avaiable\'
                        AND (product.type = \'PRODUCT\' OR product.type = \'DRINK\')
                        AND (product.id != \'DOUTSIDE\' OR product.id != \'FOUTSIDE\')
    				ORDER BY
    					product.id
    			'); 
                    if(Url::get('set_menu')){
                        if(isset($_GET['department'])){
                            $department = $_GET['department'];
                        } 
                         $items_product =  DB::fetch_all('
            				SELECT 
                					product.id,
                                    product.name_'.Portal::language().' as code,
                                    product.id as name,
                                    product.unit_id,
                                    unit.name_'.Portal::language().' as unit,
                                    rownum
            				FROM 
            					product inner join unit on product.unit_id = unit.id
                                inner join product_category on product.category_id = product_category.id
                                inner join product_price_list ON product.id = product_price_list.product_id
                                LEFT JOIN bar_set_menu ON bar_set_menu.code = product_price_list.product_id
            				WHERE
            					(UPPER(product.id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
        						OR LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\')
            					AND (rownum > 0 AND rownum <= 1000)
                                AND product.status = \'avaiable\'
                                AND (product.type = \'PRODUCT\' OR product.type = \'DRINK\'  OR product.type = \'GOODS\')
                                AND (product.id != \'DOUTSIDE\' AND product.id != \'FOUTSIDE\' AND product.id!=\'SOUTSIDE\')
                                AND product_price_list.department_code=\''.$department.'\'
            					AND product_price_list.portal_id = \''.PORTAL_ID.'\'
            					AND (product_price_list.end_date is null OR product_price_list.end_date>=\''.Date_Time::to_orc_date(date('d/m/y')).'\')
                                AND bar_set_menu.id is NULL
            				ORDER BY
            					product.id
            			'); 
                    } 
                    
                }                
            }
            else{
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
                        inner join product_category on product.category_id = product_category.id
    				WHERE
    					(UPPER(product.id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
						OR LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\')
    					AND (rownum > 0 AND rownum <= 1000)
                        AND product.status = \'avaiable\'
    				ORDER BY
    					product.id
    			');
                if(Url::get('set_menu')){
                        if(isset($_GET['department'])){
                            $department = $_GET['department'];
                        }                        
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
                                inner join product_category on product.category_id = product_category.id
                                inner join product_price_list ON product.id = product_price_list.product_id
                                LEFT JOIN bar_set_menu ON bar_set_menu.code = product_price_list.product_id
            				WHERE
            					(UPPER(product.id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
        						OR LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\')
            					AND (rownum > 0 AND rownum <= 1000)
                                AND product.status = \'avaiable\'
                                AND (product.type = \'PRODUCT\' OR product.type = \'DRINK\'  OR product.type = \'GOODS\')
                                AND (product.id != \'DOUTSIDE\' AND product.id != \'FOUTSIDE\' AND product.id!=\'SOUTSIDE\')
                                AND product_price_list.department_code=\''.$department.'\'
            					AND product_price_list.portal_id = \''.PORTAL_ID.'\'
            					AND (product_price_list.end_date is null OR product_price_list.end_date>=\''.Date_Time::to_orc_date(date('d/m/y')).'\')
                                AND bar_set_menu.id is NULL
            				ORDER BY
            					product.id
            			'); 
                    } 
            }    
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
                            					(UPPER(product.id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
												OR ((LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\')))
                            					AND (rownum > 0 AND rownum <= 1000)
                                                AND product.type != \'SERVICE\'  
                                                AND product.type != \'PRODUCT\'
                                                AND product.type != \'DRINK\'
                                                AND product.status = \'avaiable\' 
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
            //edit 19/12/2012 theo yeu cau DeNhat : co them ca good
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
                            					(UPPER(product.id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
												OR ((LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\')))
                            					AND (rownum > 0 AND rownum <= 1000)
                                                AND ( product.type = \'MATERIAL\' OR product.type = \'GOODS\' OR product.type = \'DRINK\' )  
                                                AND product.status = \'avaiable\'
                            				ORDER BY
                            					product.id
                            			');
            foreach($items_product as $key=>$value)
            {
                echo $value['code'].'|'.$value['name']."\n";
            }
        }
        else
        if(Url::get('banquet'))
        {
            $cond = '';
            if(Url::get('product_type')=='SERVICE')
            {
                $cond .= ' AND product.type = \'SERVICE\' '; 
            }
            else
            if(Url::get('product_type')=='PRODUCT')
            {
                $cond .= ' AND (product.type = \'PRODUCT\' OR product.type = \'GOODS\') '; 
            }
            else if(Url::get('product_type')=='DRINK' )
            {
                $cond .= ' AND (product.type = \'DRINK\' OR product.type = \'GOODS\')';
            }
            else
            {
                $cond .= ' AND product.type != \'PRODUCT\' AND product.type != \'SERVICE\' ';
            }
			$items_product = DB::fetch_all('
                        				select 
                                            product_price_list.id,
                    						product_price_list.product_id as code,
                    						product.name_'.Portal::language().' as name
                        				from
                    						product_price_list
                                            INNER JOIN product on product.id = product_price_list.product_id
                                            INNER JOIN unit on unit.id = product.unit_id
				                            INNER JOIN product_category on product.category_id = product_category.id
                        				where
   						 (UPPER(product_price_list.product_id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
											OR ((LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\')))						
                                            AND product_price_list.portal_id=\''.PORTAL_ID.'\'
                                            AND product_price_list.department_code = \'BANQUET\'
                                            '.$cond.'
                                            AND product.status = \'avaiable\'
                                            -- THANH ADD dieu kien lay gia theo khoang thoi gian 
                                            AND ( (DATE_TO_UNIX(product_price_list.start_date)<='.$today.' AND '.$today.'<=DATE_TO_UNIX(product_price_list.end_date)) OR (DATE_TO_UNIX(product_price_list.start_date)<='.$today.' AND product_price_list.end_date IS NULL ) OR ( '.$today.'<=DATE_TO_UNIX(product_price_list.end_date) AND product_price_list.start_date IS NULL ) OR ( product_price_list.start_date IS NULL AND product_price_list.end_date IS NULL ))
                                            -- END
                        				order by
                        					product_price_list.product_id
                        			     ');
            foreach($items_product as $key=>$value)
            {
                if(Url::get('mice'))
                    echo convert_utf8_to_latin(mb_strtolower($value['name'],'utf-8')).'|'.$value['name'].'|'.$value['code']."\n";
                else
                    if(Url::get('name'))
                        echo $value['name'].'|'.$value['code']."\n";
                    else
                        echo $value['code'].'|'.$value['name']."\n";
            }
            
		}
        else
        {
            /** Thanh add phan set menu, up code nho can than **/
            if(Url::get('get_set_menu_child'))
            {
                $get_set_menu_child = Url::get('get_set_menu_child');
                $sql = "SELECT id, product_id, department_code FROM product_price_list 
                WHERE id = ".$get_set_menu_child.' AND product_price_list.portal_id=\''.PORTAL_ID.'\' 
                AND ( (DATE_TO_UNIX(product_price_list.start_date)<='.$today.' AND '.$today.'<=DATE_TO_UNIX(product_price_list.end_date)) OR (DATE_TO_UNIX(product_price_list.start_date)<='.$today.' AND product_price_list.end_date IS NULL ) OR ( '.$today.'<=DATE_TO_UNIX(product_price_list.end_date) AND product_price_list.start_date IS NULL ) OR ( product_price_list.start_date IS NULL AND product_price_list.end_date IS NULL ))';
                $parrent_product = DB::fetch($sql);
                $sql = "
                SELECT 
                product_price_list.id,
                product_price_list.product_id, 
                product_price_list.price as total,
                bar_set_menu.name as name 
                 FROM product_price_list
                  INNER JOIN bar_set_menu ON product_price_list.product_id = bar_set_menu.code
                WHERE (product_price_list.product_id LIKE ('%".$parrent_product['product_id']."-%') OR product_price_list.product_id='".$parrent_product['product_id']."' ) 
                AND product_price_list.portal_id='".PORTAL_ID."'  AND product_price_list.department_code = '".$parrent_product['department_code']."'
                AND ( (DATE_TO_UNIX(product_price_list.start_date)<=".$today." AND ".$today."<=DATE_TO_UNIX(product_price_list.end_date)) OR (DATE_TO_UNIX(product_price_list.start_date)<=".$today." AND product_price_list.end_date IS NULL ) OR ( ".$today."<=DATE_TO_UNIX(product_price_list.end_date) AND product_price_list.start_date IS NULL ) OR ( product_price_list.start_date IS NULL AND product_price_list.end_date IS NULL ))
                ";
                $result = DB::fetch_all($sql);
                echo json_encode($result);
            }
            else if(Url::get('set_menu')){
                if(isset($_POST['id'])){
                    if(!Url::get('bar_reservation_id')){
                        
                        $sql = "SELECT bar_set_menu.id,
                        bar_set_menu.code,
                        bar_set_menu.department_code 
                        FROM bar_set_menu INNER JOIN product_price_list ON bar_set_menu.code = product_price_list.product_id WHERE product_price_list.id = ".$_POST['id'];
                        
                        $rs = DB::fetch($sql);
                        
                        $result = DB::fetch_all("SELECT DISTINCT product_price_list.id as id,
                                                   bar_set_menu_product.product_id,
                                                   --bar_reservation_set_product.quantity,
                                                   bar_set_menu_product.quantity as quantity,      
                                                   bar_set_menu_product.quantity as original_quantity,                                               
                                                   product.name_1 as product_name 
                                            FROM 
                                                bar_set_menu_product
                                                --INNER JOIN bar_reservation_set_product ON bar_reservation_set_product.product_id = bar_set_menu_product.product_id AND bar_reservation_set_product.bar_set_menu_id = bar_set_menu_product.bar_set_menu_id
                                                INNER JOIN product ON bar_set_menu_product.product_id = product.id
                                                INNER JOIN bar_set_menu ON bar_set_menu_product.bar_set_menu_id = bar_set_menu.id
                                                INNER JOIN product_price_list ON ( product_price_list.product_id = bar_set_menu_product.product_id AND product_price_list.department_code = bar_set_menu.department_code)
                                            WHERE bar_set_menu.id = ".$rs['id']);
                    //System::debug($result);
                    }
                    else{
                        
                        $result = DB::fetch_all("SELECT DISTINCT product_price_list.id as id,
                                                   bar_set_menu_product.product_id,
                                                   bar_reservation_set_product.quantity,
                                                   bar_set_menu_product.quantity as original_quantity,                                               
                                                   product.name_1 as product_name 
                                            FROM 
                                                bar_set_menu_product
                                                INNER JOIN bar_reservation_set_product ON bar_reservation_set_product.product_id = bar_set_menu_product.product_id AND bar_reservation_set_product.bar_set_menu_id = bar_set_menu_product.bar_set_menu_id
                                                INNER JOIN product ON bar_set_menu_product.product_id = product.id
                                                INNER JOIN bar_set_menu ON bar_set_menu_product.bar_set_menu_id = bar_set_menu.id
                                                INNER JOIN product_price_list ON ( product_price_list.product_id = bar_set_menu_product.product_id AND product_price_list.department_code = bar_set_menu.department_code)
                                            WHERE bar_reservation_set_product.bar_reservation_id=".$_POST['bar_reservation_id']);
                    }
                    echo json_encode($result);
                }
            }
            /** Thanh add phan set menu, up code nho can than **/
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