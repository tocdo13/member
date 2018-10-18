<?php
function get_remain_products($warehouse_id = '',$for_report = false, $for_product_id = false, $product = false, $wh_invoice_arr = false)
{
    $cond = $invoice_cond = $cond_2 = '1=1';
    $warehouse_cond = '';
    /** 
     * BEGIN
    * neu co $for_product_id tuc la lay so ton cho 1 product_id
    * de lay gia trung binh cho mot ma
    * goi o get_current_average_price()
    **/
    if ($for_product_id)
    {
        $warehouse_cond .= ' and wh_invoice_detail.product_id = \''.$for_product_id. '\'';
        $cond .= ' and wh_start_term_remain.product_id = \''.$for_product_id. '\'';
        $cond_2 .= ' and wh_remain_date_detail.product_id = \''.$for_product_id. '\'';
    }
    /** 
     * END
    * neu co $for_product_id tuc la lay so ton cho 1 product_id
    * de lay gia trung binh cho mot ma
    * goi o get_current_average_price()
    **/
    /** Kimtan truyen bien vao de CO nha hang ko bá»� cham **/
    $con_product_wh_invoice = '';
    $con_product_wh_start = '';
    $con_wh_start = '';
    if($wh_invoice_arr)
    {
        $wh_invoice_id = '';
        foreach($wh_invoice_arr as $k=>$v)
        {
            if($wh_invoice_id=='')
            {
                $wh_invoice_id = $v['id'];
            }
            else
            {
                $wh_invoice_id = ','.$v['id'];
            }
        }
        $cond_wh_invoice = ' AND wh_invoice_detail.invoice_id not in ('.$wh_invoice_id.')';
    }
    if($product)
    {
        foreach($product as $k=>$v)
        {
            if($con_product_wh_invoice=='')
            {
                $con_product_wh_invoice.=' AND ( wh_invoice_detail.product_id = \''.$v['product_id'].'\'';
            }
            else
            {
                $con_product_wh_invoice.=' or wh_invoice_detail.product_id = \''.$v['product_id'].'\'';
            }
            if($con_product_wh_start=='')
            {
                $con_product_wh_start.=' AND ( wh_start_term_remain.product_id = \''.$v['product_id'].'\'';
            }
            else
            {
                $con_product_wh_start.=' or wh_start_term_remain.product_id = \''.$v['product_id'].'\'';
            }
            if($con_wh_start=='')
            {
                $con_wh_start.=' AND ( wh_remain_date_detail.product_id = \''.$v['product_id'].'\'';
            }
            else
            {
                $con_wh_start.=' or wh_remain_date_detail.product_id = \''.$v['product_id'].'\'';
            }
        }
        if($con_product_wh_invoice!='')
        {
            $con_product_wh_invoice.=' )';
        }
        if($con_product_wh_start!='')
        {
            $con_product_wh_start.=' )';
        }
        if($con_wh_start!='')
        {
            $con_wh_start.=' )';
        }
    }
    /** end Kimtan truyen bien vao de CO nha hang ko bá»� cham **/
    //echo $con_product_wh_invoice;
    if($warehouse_id)
    {
    	//$invoice_cond.=' and wh_invoice.warehouse_id='.$warehouse_id.'';
    	$cond.=' and wh_start_term_remain.warehouse_id='.$warehouse_id;
        $cond_2.=' and wh_remain_date_detail.warehouse_id='.$warehouse_id;
        $warehouse_cond.=' and wh_invoice_detail.warehouse_id='.$warehouse_id.' ';
    }
    //dung cho bao cao ton tong hop
    $check_re = 0;
    if($for_report)
    {
        $invoice_cond.=' AND wh_invoice.create_date <=\''.Date_Time::to_orc_date(Url::get('date')).'\'';
        if(Url::get('product_id'))
        {
            $invoice_cond.=' AND upper(wh_invoice_detail.product_id) =\''.strtoupper(Url::sget('product_id')).'\'';
        }
        if(Url::iget('warehouse_id'))
        {
            $wh_remain_date = DB::fetch_all('select * from wh_remain_date where  warehouse_id='.Url::iget('warehouse_id').' and portal_id = \''.PORTAL_ID.'\'');
            
        }
        $cond_term_date = ' ';
        if(isset($wh_remain_date))
        {
            foreach($wh_remain_date as $key=>$value)
            {
                if($value['end_date'] != '' and Date_time::to_time(Url::get('date'))< Date_time::to_time(Date_time::convert_orc_date_to_date($value['end_date'],'/')) and Date_time::to_time(Url::get('date'))>= Date_time::to_time(Date_time::convert_orc_date_to_date($value['term_date'],'/')))
                {
                    $invoice_cond.= ' AND wh_invoice.create_date >=\''.$value['term_date'].'\'';
                    $cond_term_date = ' AND wh_remain_date_detail.term_date =\''.$value['term_date'].'\'';
                    $check_re = 1;
                }
                
                if($value['end_date']=='' and Date_time::to_time(Url::get('date'))>= Date_time::to_time(Date_time::convert_orc_date_to_date($value['term_date'],'/')) )
                {
                    $invoice_cond.= ' AND wh_invoice.create_date >=\''.$value['term_date'].'\'';
                    $cond_term_date = ' AND wh_remain_date_detail.term_date =\''.$value['term_date'].'\'';
                    $check_re = 1;
                }
            }
            $cond_2 .= $cond_term_date; 
        }
    }
    else
    {
        /** Kimtan: them ngay 5/5/17 de gioi han ngay tinh ton cua sp **/
        if($warehouse_id)
        {
            //echo 'select * from wh_remain_date where status = 1 and warehouse_id='.$warehouse_id.' and portal_id = \''.PORTAL_ID.'\'';
            $wh_remain_date = DB::fetch('select * from wh_remain_date where status = 1 and warehouse_id='.$warehouse_id.' and portal_id = \''.PORTAL_ID.'\'');
        }
        $cond_term = '';
        if(isset($wh_remain_date) and $wh_remain_date['term_date']!='')
        {
            $invoice_cond.=' AND wh_invoice.create_date >=\''.$wh_remain_date['term_date'].'\'';
            $cond_term =' and wh_remain_date_detail.term_date=\''.$wh_remain_date['term_date'].'\'';
            $check_re = 1;
        }
        $cond_2 .= $cond_term; 
        /** end Kimtan: them ngay 5/5/17 **/
    }
    /**
     * BEGIN
     * doan nay tra ve so ton tien va luowng
     * qua tat ca cac lan nhap xuat 
    **/
    $start_term = array();
    if($check_re==1)
    {
        $sql_start = '
        SELECT
            wh_remain_date_detail.product_id as id,
            product.name_'.Portal::language().' as product_name,
            unit.name_'.Portal::language().' as unit_name,
            wh_remain_date_detail.warehouse_id,
            wh_remain_date_detail.quantity as remain_number,
            wh_remain_date_detail.total_start_term_price as remain_money,
            wh_remain_date_detail.start_term_price
        FROM
            wh_remain_date_detail
            INNER JOIN product on product.id = wh_remain_date_detail.product_id
            INNER JOIN unit on unit.id = product.unit_id
            INNER JOIN product_category ON product_category.id = product.category_id
        WHERE
            '.$cond_2.$con_wh_start.'
            and wh_remain_date_detail.portal_id = \''.PORTAL_ID.'\'
            
        ';
        $start_term = DB::fetch_all($sql_start);
    }
    if(User::id()=='developer06')
    {
        //System::debug($sql_start);
        //System::debug($start_term);
    }
    $product_invoice = array();
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
            wh_invoice.type,
            wh_invoice_detail.tmp
    	FROM
    		wh_invoice_detail
    		INNER JOIN wh_invoice on wh_invoice.id = wh_invoice_detail.invoice_id
    		INNER JOIN product on product.id = wh_invoice_detail.product_id
            INNER JOIN unit on unit.id = product.unit_id 
            INNER JOIN product_category ON product_category.id = product.category_id
    	WHERE
    		'.$invoice_cond.$warehouse_cond.$con_product_wh_invoice.'
            and wh_invoice.portal_id = \''.PORTAL_ID.'\'
    ';
    $product_invoice = DB::fetch_all($sql_invoice);
    if (User::is_admin())
    {
        //System::debug($sql_invoice);
        //System::debug($invoice_cond);
    }
    $items = $product_invoice;
    $old_items = array();
	if(is_array($items))
	{
		foreach($items as $key=>$value)
        {
			$product_id = $value['product_id'];
			if(isset($old_items[$product_id]))
            {
				if($value['type']=='IMPORT' and (($warehouse_id=='' and $value['to_warehouse_id']=='') or $warehouse_id!=''))
                {
					$old_items[$product_id]['remain_invoice'] =  System::calculate_number(round($old_items[$product_id]['remain_invoice'], 8)) + System::calculate_number(round($value['num'],8));
                    $old_items[$product_id]['remain_invoice_tan'] =  System::calculate_number(round($old_items[$product_id]['remain_invoice_tan'], 8)) + System::calculate_number(round($value['num'],8));
                    $old_items[$product_id]['remain_money'] =  System::calculate_number($old_items[$product_id]['remain_money']) + System::calculate_number($value['payment_price']);
                }
				else
                {
                    if($value['type']=='EXPORT' and ($value['to_warehouse_id'] != $warehouse_id or $warehouse_id==''))
                    {
						$old_items[$product_id]['remain_invoice'] =  System::calculate_number(round($old_items[$product_id]['remain_invoice'], 8)) - System::calculate_number(round($value['num'],8));
                        if($value['tmp']=='')
                        {
                            /** Kimtan them dong nay lay ra so luong khi tinh gia thi ko lay bon xuat am **/
                            $old_items[$product_id]['remain_invoice_tan'] =  System::calculate_number(round($old_items[$product_id]['remain_invoice_tan'], 8)) - System::calculate_number(round($value['num'],8));
                            $old_items[$product_id]['remain_money'] =  System::calculate_number($old_items[$product_id]['remain_money']) - System::calculate_number($value['payment_price']);
                        }
                    }
                }
			}
            else
            {
                $old_items[$product_id]['id'] = $value['product_id'];
                $old_items[$product_id]['remain_invoice'] = 0;
                $old_items[$product_id]['remain_invoice_tan'] = 0;
                $old_items[$product_id]['remain_money'] = 0;
				if($value['type']=='IMPORT' and (($warehouse_id=='' and $value['to_warehouse_id']=='') or $warehouse_id!=''))
                {
					$old_items[$product_id]['remain_invoice'] =  System::calculate_number(round($old_items[$product_id]['remain_invoice'], 8)) + System::calculate_number(round($value['num'],8));
                    $old_items[$product_id]['remain_invoice_tan'] =  System::calculate_number(round($old_items[$product_id]['remain_invoice_tan'], 8)) + System::calculate_number(round($value['num'],8));
                    $old_items[$product_id]['remain_money'] =  System::calculate_number($old_items[$product_id]['remain_money']) + System::calculate_number($value['payment_price']);
                }
                //PX ma kho xuat den khong phai la kho can tinh(tranh th tao PX ma tu kho A den kho A)
				if($value['type']=='EXPORT' and ($value['to_warehouse_id'] != $warehouse_id or $warehouse_id==''))
                {
					$old_items[$product_id]['remain_invoice'] =  System::calculate_number(round($old_items[$product_id]['remain_invoice'], 8)) - System::calculate_number(round($value['num'],8));
                    if($value['tmp']=='')
                    {
                        $old_items[$product_id]['remain_invoice_tan'] =  System::calculate_number(round($old_items[$product_id]['remain_invoice_tan'], 8)) - System::calculate_number(round($value['num'],8));
                        $old_items[$product_id]['remain_money'] =  System::calculate_number($old_items[$product_id]['remain_money']) - System::calculate_number($value['payment_price']);   
                    }
                }
                /*
                if(isset($start_term[$product_id]))
                {
                    $old_items[$product_id]['remain_invoice'] += $start_term[$product_id]['remain_number'];
                    $old_items[$product_id]['remain_money'] += $start_term[$product_id]['remain_money'];
                }
                */
			}
		}
        if(User::id()=='developer06')
        {
            //System::debug($old_items);
        }
	}
    /*
    if(!$for_report)
    {
        if(is_array($items))
    	{
            foreach ($start_term as $k=>$v)
            {
                if(!isset($old_items[$k]))
                {
                    $old_items[$k]['remain_invoice'] = $start_term[$k]['remain_number'];
                    $old_items[$k]['remain_invoice_tan'] = $start_term[$k]['remain_number'];
                    $old_items[$k]['remain_money'] = $start_term[$k]['remain_money'];
                }
            }
        }
    }*/
    $product_invoice = $old_items;
    //product_invoice: la mang co key la product_id, 
    //so luong ton hien tai = tong so lan nhap - tong so lan xuat 
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
            SUM(
                CASE 
                    WHEN wh_start_term_remain.quantity >0 THEN wh_start_term_remain.quantity
                    ELSE 0 
                END
            ) as remain_number_tan,
            wh_start_term_remain.total_start_term_price as remain_money,
            wh_start_term_remain.start_term_price
        FROM
            wh_start_term_remain
            INNER JOIN product on product.id = wh_start_term_remain.product_id
            INNER JOIN unit on unit.id = product.unit_id
            INNER JOIN product_category ON product_category.id = product.category_id
        WHERE
            '.$cond.$con_product_wh_start.'
            and wh_start_term_remain.portal_id = \''.PORTAL_ID.'\'
        GROUP BY
            wh_start_term_remain.product_id,
            wh_start_term_remain.total_start_term_price,
            product.name_'.Portal::language().',
            unit.name_'.Portal::language().',
            wh_start_term_remain.warehouse_id,
            wh_start_term_remain.start_term_price
            
    ';
    $start_term_remain = DB::fetch_all($sql_start_term_remain);
    if(User::id()=='developer06')
    {
        //System::debug($start_term_remain);
    }
    foreach($start_term_remain as $k=>$v)
    {
        if(isset($start_term[$k]))
        {
            $start_term_remain[$k]['remain_number'] += $start_term[$k]['remain_number'];
            $start_term_remain[$k]['remain_number_tan'] += $start_term[$k]['remain_number'];
            $start_term_remain[$k]['remain_money'] += $start_term[$k]['remain_money'];
        }
    }
    if (User::is_admin())
    {
        //System::debug($sql_start_term_remain);
    }
    $con_wh_id_1 = '';
    if($warehouse_id)
    {
       $con_wh_id_1=' AND wh_invoice.warehouse_id = '.$warehouse_id.'';
    }
    $sql = '
        SELECT
            wh_invoice_detail.id,
            wh_invoice_detail.price,
            wh_invoice_detail.average_price,
            wh_invoice_detail.time_calculation
        FROM
            wh_invoice_detail
            INNER JOIN wh_invoice on wh_invoice.id = wh_invoice_detail.invoice_id
    		INNER JOIN product on product.id = wh_invoice_detail.product_id
            INNER JOIN unit on unit.id = product.unit_id 
            INNER JOIN product_category ON product_category.id = product.category_id
        WHERE
            '.$invoice_cond.' 
            AND wh_invoice.type=\'IMPORT\'
            '.$con_wh_id_1.'
        ORDER BY 
            id desc
    ';
    $price = DB::fetch($sql);
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
//          else//Xuat thi lay gia TB
//          {
            $price = $price['average_price'];
            //}
            /** Kimtan cmt dong nay thay bang dong duoi truong hop so nho hon 1 vd 0.6 se hien thi .6 va +- sai. Ã�ï¿½Ã�Â¢u kÃ�ï¿½Ã�Âª
             $start_term_remain[$key]['remain_number'] = ($value['remain_number']?$value['remain_number']:0)+($product_invoice[$key]['remain_invoice']?$product_invoice[$key]['remain_invoice']:0);**/
            $start_term_remain[$key]['remain_number'] = ($value['remain_number']?System::calculate_number(round($value['remain_number'],8)):0)+($product_invoice[$key]['remain_invoice']?System::calculate_number(round($product_invoice[$key]['remain_invoice'],8)):0);
            $start_term_remain[$key]['remain_number_tan'] = ($value['remain_number']?System::calculate_number(round($value['remain_number'],8)):0)+($product_invoice[$key]['remain_invoice_tan']?System::calculate_number(round($product_invoice[$key]['remain_invoice_tan'],8)):0);
            $start_term_remain[$key]['remain_money'] = ($value['remain_money']?$value['remain_money']:0)+($product_invoice[$key]['remain_money']?$product_invoice[$key]['remain_money']:0);
            $start_term_remain[$key]['lastest_imported_price'] = $lastest_imported_price?$lastest_imported_price:0;
            //===Gia TB se tinh theo cach sau, khong lay o PN gan nhat nua====/
            $start_term_remain[$key]['price'] = $start_term_remain[$key]['remain_number'] > 0? $start_term_remain[$key]['remain_money']/$start_term_remain[$key]['remain_number'] : 0;
        }
        else
        {
            /**
            $start_term_remain[$key]['remain_number'] = ($value['remain_number']?$value['remain_number']:0);
            $start_term_remain[$key]['remain_money'] = ($value['remain_money']?$value['remain_money']:0);
            $start_term_remain[$key]['lastest_imported_price'] = 0;
            //===Gia TB se tinh theo cach sau, khong lay o PN gan nhat nua====/
            $start_term_remain[$key]['price'] = $start_term_remain[$key]['remain_number'] != 0? $start_term_remain[$key]['remain_money']/$start_term_remain[$key]['remain_number'] : 0;
            **/
        }
    }
    /**
     * END 
    **/
    if(User::id()=='developer06')
    {
        //System::debug($start_term_remain);
        //exit();
    }
    if (User::is_admin())
    {
        //System::debug($product_invoice);
        //System::debug($start_term_remain);
        //exit();
    }
    
    return $start_term_remain;
}
//----------------------------------------------------------------------------

function get_current_average_price($product,$warehouse_id, $return_array = false)
{ 
    $start_term_remain = get_remain_products($warehouse_id, false, $product);
    if (!empty($start_term_remain))
    {
        if ($return_array)
        {
            $start_term_remain = $start_term_remain[$product];
            return $start_term_remain;
        }      
        else
        {
            return $start_term_remain[$product]['remain_number'] != 0? $start_term_remain[$product]['remain_money']/$start_term_remain[$product]['remain_number'] : 0;
        }
    }
    else
    {
        if ($return_array)
        {
            $array_lt[$product]['remain_number'] = 0;
            $array_lt[$product]['remain_money'] = 0;
            return $array_lt;
        } 
        else
        {
            return 0;
        }
    }  
}
/**
 * Lay so luong san pham xuat di tu 1 kho + so ton trong kho - cac sp da~ ban tren cac kho khac  = so luong san pham that
 */
//
function get_transfer_product($warehouse_id)
{
    $cond =' 1=1 and wh_invoice.warehouse_id='.$warehouse_id.' ';
    if(Url::get('cmd')=='edit' and Url::get('id'))
        $cond .= ' and wh_invoice.id<'.intval(Url::iget('id'));
    $sql_invoice = '
        SELECT 
    		wh_invoice_detail.product_id as id,
    		SUM( wh_invoice_detail.num ) as export_quantity
    	FROM
    		wh_invoice_detail
    		INNER JOIN wh_invoice on wh_invoice.id = wh_invoice_detail.invoice_id
    		INNER JOIN product on product.id = wh_invoice_detail.product_id
            INNER JOIN unit on unit.id = product.unit_id 
            INNER JOIN product_category ON product_category.id = product.category_id
    	WHERE
    		'.$cond.'
            and wh_invoice.portal_id = \''.PORTAL_ID.'\'
            and wh_invoice.type = \'EXPORT\'
            and
            (
                wh_invoice.move_product = 1
                OR wh_invoice.direct_export = 1
            )
    	GROUP BY
    		wh_invoice_detail.product_id
    ';
    //System::debug($sql_invoice);
    $product_invoice = DB::fetch_all($sql_invoice);
    //System::debug($product_invoice);
    /**
     * $wh : luu cac kho dich (cua tat cac cac lan` goi. ham` de quy)
     */
    $wh = array();
    //Dua kho dau tien vao mang (o day thuong la kho tong, de tranh truong hop tra lai hang ve kho tong, se lai tinh lai va tru di cac sp xuat ra ngoai he thong cua kho tong 1 lan nua)
    array_push($wh,$warehouse_id);
    $product_invoice = calc_transfer_product($product_invoice,$warehouse_id,$wh);
    //System::debug($items);
    return $product_invoice;   
}

function calc_transfer_product($product_invoice,$warehouse_id,$wh)
{
    //System::debug($product_invoice);
    $cond =' 1=1 and wh_invoice.warehouse_id='.$warehouse_id.' ';
    if(Url::get('cmd')=='edit' and Url::get('id'))
        $cond .= ' and wh_invoice.id<'.intval(Url::iget('id'));
    //lay cac kho duoc xuat chuyen toi    
    $items = DB::fetch_all('
                            SELECT 
                                DISTINCT wh_invoice_detail.to_warehouse_id as id
                        	FROM
                        		wh_invoice_detail
                        		INNER JOIN wh_invoice on wh_invoice.id = wh_invoice_detail.invoice_id
                        	WHERE
                        		'.$cond.'
                                and wh_invoice.portal_id = \''.PORTAL_ID.'\'
                                and wh_invoice.type = \'EXPORT\'
                                and
                                (
                                    wh_invoice.move_product = 1
                                    OR wh_invoice.direct_export = 1
                                )
                            ');
    /**
     * $wh : luu cac kho dich (cua tat cac cac lan` goi. ham` de quy)
     */
    if(empty($wh))
        $wh = array();
    /**
     * $wh_legal : luu cac kho dich cua tung lan` goi. ham` de quy
     */    
    $wh_legal = array();
    foreach($items as $key=>$value)
    {
        if(!in_array($value['id'], $wh))
        {
            array_push($wh,$value['id']);
            array_push($wh_legal,$value['id']);
            $cond =' 1=1 and wh_invoice.warehouse_id='.$value['id'].' ';
            if(Url::get('cmd')=='edit' and Url::get('id'))
                $cond .= ' and wh_invoice.id<'.intval(Url::iget('id'));
            $sql = '
                SELECT 
            		wh_invoice_detail.product_id as id,
            		SUM( wh_invoice_detail.num ) as export_quantity
            	FROM
            		wh_invoice_detail
            		INNER JOIN wh_invoice on wh_invoice.id = wh_invoice_detail.invoice_id
            		INNER JOIN product on product.id = wh_invoice_detail.product_id
                    INNER JOIN unit on unit.id = product.unit_id 
                    INNER JOIN product_category ON product_category.id = product.category_id
            	WHERE
            		'.$cond.'
                    and wh_invoice.portal_id = \''.PORTAL_ID.'\'
                    and wh_invoice.type = \'EXPORT\'
                    and
                    (
                        (
                            wh_invoice.move_product is null
                            and wh_invoice.direct_export is null
                        )
                        or
                            wh_invoice_detail.to_warehouse_id = '.$wh[0].'
                    )
            	GROUP BY
            		wh_invoice_detail.product_id
            ';
            //lay cac sp dc xuat ra ngoai (khong con tren he thong kho) cua kho dc xuat chuyen
            $product = DB::fetch_all($sql);
            foreach($product as $k=>$v)
            {
                if(isset($product_invoice[$k]))
                    $product_invoice[$k]['export_quantity'] = $product_invoice[$k]['export_quantity'] - $v['export_quantity'];
            }
        }
    }
    
    foreach($wh_legal as $wh_id)
    {
        if(DB::fetch('Select * from wh_invoice_detail where warehouse_id = '.$wh_id.' and to_warehouse_id is not null '))
            $product_invoice = calc_transfer_product($product_invoice,$wh_id,$wh);
    }
    return $product_invoice;
}
function create_invoice($id,$type)
{
	if(Url::get('allow_save_invoice')==1)
	{
		if($liability_customer=DB::select('customer','code="'.Url::get('invoice_customer_code').'"'))
		{
			$liability_customer_id=$liability_customer['id'];
		}
		else
		{
			$liability_customer_id='';
		}
		$arr=array(
			'liability_customer_id'=>$liability_customer_id,
			'customer'=>Url::get('invoice_customer_name'),
			'address'=>Url::get('invoice_address'),
			'tax_code'=>Url::get('invoice_tax_code'),
			'invoce_sign'=>Url::get('invoice_sign'),
			'invoice_code'=>Url::get('invoice_code'),
			'time_invoce'=>Date_Time::to_sql_date(Url::get('time_invoice')),
			'template_id'=>Url::get('template_id'),
			'tax_code'=>Url::get('invoice_tax_code'),
			'tax_balance'=>Url::get('invoice_tax_balance'),								
			'amount_before_tax'=>str_replace(',','',Url::get('amount_before_tax')),
			'payment_method'=>Url::get('payment_type'),
			'amount_tax'=>str_replace(',','',Url::get('amount_tax')),
			'voucher_id'=>$id,
			'warehouse_product'=>Url::get('warehouse_product'),
			'product_quantity'=>Url::get('product_quantity'),
			'type'=>$type								
		);
		if(DB::exists('select id,voucher_id from invoice where type="'.$type.'" and voucher_id='.$id.''))
		{
			DB::update('invoice',$arr,'type="'.$type.'" and voucher_id='.$id);
		}
		else
		{
			DB::insert('invoice',$arr+array('time'=>time()));
		}
	}	
}
function get_privilege()
{ 
	$warehouses = DB::fetch_all('SELECT id,name,module_name FROM warehouse WHERE structure_id != '.ID_ROOT.' AND portal_id = \''.PORTAL_ID.'\' ');
	if(!User::is_admin())
    {
		$cond = ' and (';
		$i = 1;
		foreach($warehouses as $key=>$value)
        {
			if(User::can_edit(Portal::get_module_id($value['module_name']),ANY_CATEGORY))
            {
				if($i == 1)
                {
					$cond .= ' warehouse.id = '.$key.'';
				}
                else
                {
					$cond .= ' OR warehouse.id = '.$key.'';
				}
				$i++;
			}
		}
		$cond .= ')';
		if($i>1)
        {
			return $cond;
		}
        else
        {
			return false;
		}
		return $cond;
	}
    else
    {
		return false;	
	}
}
/**
 * function = false : lay danh sach kho bao gom ca ID ROOT
 * function = true : lay danh sach kho loai bo ID ROOT
 */
function get_warehouse($function = false,$cond='1=1',$privilege=true)
{
	if($privilege)
	{
		$cond.= get_privilege();
	}
    if(!$function)
    {
    	return DB::fetch_all('
    		select 
    			warehouse.*
    		from 
    		 	warehouse
    		where
    			 '.$cond.'
                  and portal_id = \''.PORTAL_ID.'\'
                  or id = 1
    		order by 
    			warehouse.structure_id
    	',false); 
    }
    else
    {
    	return DB::fetch_all('
    			select 
    				warehouse.*
    			from 
    			 	warehouse
    			where
    				 '.$cond.'
                      and portal_id = \''.PORTAL_ID.'\'
                      and structure_id !='.ID_ROOT.'
    			order by 
    				warehouse.structure_id
    		',false);
    }
	
}
?>
