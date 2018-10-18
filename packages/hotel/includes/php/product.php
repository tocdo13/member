<?php
// Lop dung chung cho cac module
// Write by Khoand
class Product
{
    //chi lay san pham co don vi
	static function get_product()
	{
        $result = DB::fetch_all('
                    			SELECT
                    				product.id,
                                    product.name_'.Portal::language().' as name,
                                    product.unit_id,
                                    product.type,
                                    unit.name_'.Portal::language().' as unit,
                                    product.category_id,
                                    product_category.name as category
                    			FROM
                    				product 
                                    left join unit on product.unit_id = unit.id
                                    left join product_category on product.category_id = product_category.id
                                WHERE
                                    1=1
		                      ');	  
        //System::debug($result); 
		return $result;
	}
	static function get_product_price($portal_id=PORTAL_ID,$department_code,$parent_id='')
	{
		$products = DB::fetch_all('
			SELECT 
				 product_price_list.id
				,product.id as product_id
                ,product_price_list.price
                ,product_price_list.start_date
                ,product_price_list.end_date
                ,product_price_list.department_code
                ,product_price_list.portal_id
                ,product_price_list.id price_id
				,product.name_'.Portal::language().' as name
				,product.category_id
				,product.type
                ,unit.id as unit_id
                ,unit.name_'.Portal::language().' as unit_name
			FROM
                product_price_list
                INNER JOIN product ON product_price_list.product_id = product.id
				INNER JOIN unit on unit.id = product.unit_id
				INNER JOIN product_category on product.category_id = product_category.id			
			WHERE
				product_price_list.department_code = \''.$department_code.'\' and product_price_list.portal_id = \''.$portal_id.'\'
			ORDER BY
				product_price_list.id
		');	
        if($parent_id==1 || $department_code=='RES'){          
        $sql = "SELECT 
             product_price_list.id as id,
             bar_set_menu.id as bar_set_menu_id,
             bar_set_menu.code as code,
             bar_set_menu.name as name,
             '' as barcode,
             bar_set_menu.code as product_id,
             bar_set_menu.total as price,
             product_price_list.id as price_id,
             'set' as unit,
             '' as category_id,
			 'SET' as type,
             '' as unit_id,
             'set' as unit_name,
             '' as start_date,
             '' as end_date,
             '' as department_code,
             '".$portal_id."' as portal_id
            FROM 
            bar_set_menu
            INNER JOIN product_price_list ON product_price_list.product_id = bar_set_menu.code
            WHERE
                (bar_set_menu.department_code = '".$department_code."' OR bar_set_menu.department_code = 'RES') and product_price_list.portal_id = '".$portal_id."'
            ";
            $result = DB::fetch_all($sql);
            $products+=$result;
         }   
		return $products;
	}
    
    /*
	static function get_product_price($warehouse_id='',$cond='')
	{
		$products = DB::fetch_all('
			SELECT 
				product_price_list.id
				,product.id as code
				,product.name_'.Portal::language().' as name
				,product_price_list.price
				,product.category_id
				,product.type
				,unit.name_'.Portal::language().' as unit_name
                ,unit.id as unit_id
			FROM
				product
				INNER JOIN product_price_list ON product_price_list.product_id = product.id
				LEFT OUTER JOIN unit on unit.id = product_price_list.unit_id
				LEFT OUTER JOIN warehouse on warehouse.id = product_price_list.warehouse_id				
			WHERE
				'.($warehouse_id?'product_price_list.warehouse_id='.$warehouse_id:'').' '.$cond.'
			ORDER BY
				product.id
		');	
		return $products;
	}
    */
	static function get_material($warehouse_id='',$cond='')
	{
		$products = DB::fetch_all('
			SELECT 
				product_price_list.id
				,product.id as code
				,product.name_'.Portal::language().' as name
				,product_price_list.price
				,product.category_id
				,product.type
				,unit.name_'.Portal::language().' as unit_name
                ,unit.id as unit_id
			FROM
				product
				LEFT OUTER JOIN product_price_list ON product_price_list.product_id = product.id
				LEFT OUTER JOIN unit on unit.id = product_price_list.unit_id
				LEFT OUTER JOIN warehouse on warehouse.id = product_price_list.warehouse_id				
			WHERE
				'.($warehouse_id?'product_price_list.warehouse_id='.$warehouse_id:'').' '.$cond.'
			ORDER BY
				product.id
		');	
		return $products;
	}	
	function get_minibar_product($minibar_id,$field='')
	{
		return DB::fetch_all('
			select
				product_price_list.id,
                product.id as code,
				'.$field.' as norm_quantity,
				0 as quantity,
				product.name_'.Portal::language().' as name,
				product_price_list.price,
				product_price_list.unit_id,
                unit.name_1
			from
				minibar_product
				inner join product on product_id = product.id
				INNER JOIN product_price_list on product_price_list.id = minibar_product.price_id
                INNER JOIN unit on unit.id = product_price_list.unit_id
			where
				minibar_id = \''.$minibar_id.'\'
			order by 
				minibar_product.position'
		);	
	}
	function get_laundry_service($cond='')
	{ 
		return DB::fetch_all('
			select 
				product.id
				,name_'.Portal::language().' as name
				,product_price_list.price
				,product.id as code
			from
				product
				inner join product_price_list on product_price_list.product_id = product.id
				inner join product_category on product.category_id = product_category.id
			where
				product.type=\'SERVICE\''.$cond.' and '.IDStructure::child_cond(DB::structure_id('product_category',DB::fetch('select id from product_category where product_category.code=\'GL\'','id'))).'
			order by
				product.id
		');	
	}
	function get_unit($cond='',$option=false)
	{
		$items = DB::fetch_all('
			SELECT
				id,name_'.Portal::language().' as name,value,base_unit_id
			FROM
				unit
			WHERE
				1=1 '.$cond.'
			ORDER BY
				unit.name_'.Portal::language().'
		');
		$str = '';
		if($option)
		{
			foreach($items as $key=>$value)
			{
				$str.= 	'<option value="'.$key.'">'.$value['name'].'</option>';
			}
			return $str;
		}
		else
		{
			return $items;	
		}
	}
}

class DeliveryOrders
{
    static function get_new_bill_number($type,$wh_id)
    {
        if($type == 'IMPORT')
            $prefix = 'PN';
        else
            $prefix = 'PX';
            
        $code_wh = DB::fetch('SELECT WAREHOUSE.code as code
                                      FROM WAREHOUSE 
                                      where WAREHOUSE.id = '.$wh_id.' and WAREHOUSE.portal_id = \''.PORTAL_ID.'\'
                                      ','code');
        $max_bill =  DB::fetch("SELECT max(TO_NUMBER(REPLACE(bill_number,'".$prefix."-".$code_wh."'))) as bill
                                      FROM wh_invoice 
                                      where 
                                        wh_invoice.type='".$type."' and wh_invoice.WAREHOUSE_ID = ".$wh_id." and wh_invoice.portal_id = '".PORTAL_ID."'
                                        and wh_invoice.bill_number like '%".$prefix."-".$code_wh."%'
                                      ",'bill');
        
        if(!$max_bill)
            $max_bill = 0;
        $bill = $prefix."-".$code_wh.($max_bill + 1);
        return $bill;
    }
    /*
    static function get_new_bill_number($type)
    {
        if($type == 'IMPORT')
            $prefix = 'PN';
        else
            $prefix = 'PX';
        if($lastest_item = DB::fetch('SELECT id,bill_number FROM wh_invoice where type=\''.$type.'\' and portal_id = \''.PORTAL_ID.'\' ORDER BY to_number(REPLACE(REPLACE(bill_number,\'PX\',\'\'),\'PN\',\'\')) DESC'))
        {
            $total = str_replace($prefix,'',$lastest_item['bill_number'])+1;
        }
        else
        {
            $total = 1;
        }
        //format ve dinh dang: PN01
        $total = (strlen($total)<2)?'0'.$total:$total;
        return $prefix.$total;
    }
    */
    /**
     * Ham nay goi khi xoa hoa don  nha hang => xoa phieu xuat tuong ung, va chi tiet phieu xuat
     * $idinvoice : id hoa don nha hang
     * $invoice_type : loai hoa don (= ma bo phan), de phan biet nha hang, or minibar,..
     */
     
    static function delete_delivery_order($idinvoice,$invoice_type)
    {
        if($ids = DB::fetch_all('select * from wh_invoice where invoice_id ='.$idinvoice.' and invoice_type = \''.$invoice_type.'\' and portal_id = \''.PORTAL_ID.'\' '))
        {
            foreach($ids as $key=>$value)
            {
                DB::delete_id('wh_invoice',$value['id']);
                DB::delete('wh_invoice_detail','invoice_id = '.$value['id']);
            }
        }
    }
    
    
    /**
     * Ham nay goi khi tao hoa don cho nha hang => tao phieu xuat tuong ung, va chi tiet phieu xuat
     * $idinvoice : id hoa don nha hang
     * $invoice_type : loai hoa don (= ma bo phan), de phan biet nha hang, or minibar,..
     * $warehouse_id : kho duoc su dung cho nha hang do
     * $warehouse_id_2 : truong hop dac biet doi voi nha hang, co 2 kho an va uong
     */
	static function get_delivery_orders($idinvoice,$invoice_type,$warehouse_id=false,$warehouse_id_2=false)
    {
        //Lay cac sp dc su dung trong hoa don
        if($invoice_type != 'MINIBAR' and $invoice_type != 'VENDING' and $invoice_type != 'AMENITIES' and $invoice_type != 'SPA' )
        {   
            $product = DeliveryOrders::get_list_product($idinvoice,$invoice_type,$warehouse_id,$warehouse_id_2);
            
        }
        else
        {
            $product = DeliveryOrders::get_list_product($idinvoice,$invoice_type);

        }
        require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
        /** 
         * lay ton kho
        **/
        //product: danh sach cac san pham da su dung dat ban doi voi nha hang 
        if($wh_invoice_arr = DB::fetch_all('select * from wh_invoice where invoice_id ='.$idinvoice.' and invoice_type = \''.$invoice_type.'\' and portal_id = \''.PORTAL_ID.'\' '))
        {
            if ($warehouse_id)
            {
                $warehouse_remain = get_remain_products($warehouse_id,false,false,$product,$wh_invoice_arr);
                //echo $warehouse_remain;exit();
                $item['bill_number'] = DeliveryOrders::get_new_bill_number('EXPORT',$warehouse_id);
                
            }
            //$warehouse_remain: 
            if ($warehouse_id_2)
            {
                $warehouse_remain_2 = get_remain_products($warehouse_id_2,false,false,$product,$wh_invoice_arr);  
            }
        }
        else
        {
            if ($warehouse_id)
            {
                $warehouse_remain = get_remain_products($warehouse_id,false,false,$product,false);
                //echo $warehouse_remain;exit();
                $item['bill_number'] = DeliveryOrders::get_new_bill_number('EXPORT',$warehouse_id);
                
            }
            //$warehouse_remain: 
            if ($warehouse_id_2)
            {
                $warehouse_remain_2 = get_remain_products($warehouse_id_2,false,false,$product,false);   
            }
        }
        //giap.ln 
        $item['type']='EXPORT';
		$item['user_id']= Session::get('user_id');
		$item['create_date'] = Date_Time::to_orc_date(date('d/m/Y'));
		$item['time'] = time();
        $item['portal_id'] = PORTAL_ID;
        $item['invoice_id'] = $idinvoice;
        $item['invoice_type'] = $invoice_type;	
        //neu xuat kho cho minibar
        if($invoice_type=='MINIBAR')
        {
            $info = DB::fetch('Select housekeeping_invoice.id, minibar.id as minibar_id, minibar.name from housekeeping_invoice inner join minibar on minibar.id = housekeeping_invoice.minibar_id where housekeeping_invoice.id ='.$idinvoice);
    		$item['note'] = Portal::language('export').' '.Portal::language('warehouse').' '.Portal::language('for').' '.$info['name'].' (Code: '.$info['minibar_id'].', '.Portal::language('department').': MINIBAR) '.Portal::language('invoice').' '.$idinvoice;
        }
        else
        if($invoice_type=='AMENITIES')
        {
            
            $item['note'] = Portal::language('export').' '.Portal::language('warehouse').' '.Portal::language('amenities').' ('.Portal::language('department').': HK) '.Portal::language('code').' '.$idinvoice;
        }
        else
        if($invoice_type=='VENDING')
        {
            $item['note'] = Portal::language('export').' '.Portal::language('warehouse').' '.Portal::language('vending').' ('.Portal::language('department').': VENDING) '.Portal::language('code').' '.$idinvoice;
        }
        //start : KID them de xuat kho cho spa
        else
        if($invoice_type=='SPA')
        {
            $item['note'] = Portal::language('export').' '.Portal::language('warehouse').' '.Portal::language('spa').' ('.Portal::language('department').': SPA) '.Portal::language('code').' '.$idinvoice;
        }
        //end
        else//karaoke//
        if($invoice_type=='KARAOKE')
        {
            $info = DB::fetch('Select 
                                        karaoke_reservation.id, 
                                        karaoke.code, 
                                        karaoke.name, 
                                        karaoke.department_id 
                               from 
                                        karaoke_reservation inner join karaoke on karaoke_reservation.karaoke_id = karaoke.id 
                               where karaoke_reservation.id ='.$idinvoice);
            $item['note'] = Portal::language('export').' '.Portal::language('warehouse').' '.Portal::language('for').' '.$info['name'].' (Code: '.$info['code'].', '.Portal::language('department').': '.$info['department_id'].') '.Portal::language('invoice').' '.$idinvoice;   
        }
        else//nha hang//
        {
            $info = DB::fetch('Select 
                                        bar_reservation.id, 
                                        bar.code, 
                                        bar.name, 
                                        bar.department_id 
                               from 
                                        bar_reservation inner join bar on bar_reservation.bar_id = bar.id 
                               where bar_reservation.id ='.$idinvoice);
            $item['note'] = Portal::language('export').' '.Portal::language('warehouse').' '.Portal::language('for').' '.$info['name'].' (Code: '.$info['code'].', '.Portal::language('department').': '.$info['department_id'].') '.Portal::language('invoice').' '.$idinvoice;   
        }
        //hoa don xuat kho
        $id ='';
        $id_2='';
        //1 hoa don co the co 2 phieu xuat (truong hop nha hang)
		if($rows = DB::fetch_all('select * from wh_invoice where invoice_id ='.$idinvoice.' and invoice_type = \''.$invoice_type.'\' and portal_id = \''.PORTAL_ID.'\' '))
        {
            foreach($rows as $key => $value)
            {
                //Lay hoa don 1
                if(!$id)
                {
                    if($value['warehouse_id']==$warehouse_id)
                    $id = $rows[$key]['id'];
                }  
                if(!$id_2) //Lay hoa don 2
                {
                    if($value['warehouse_id']==$warehouse_id_2)
                    $id_2 = $rows[$key]['id'];
                }
                DB::update_id('wh_invoice',array('last_modify_user_id'=>Session::get('user_id'),'last_modify_time'=>time()),$rows[$key]['id']);
                DB::delete('wh_invoice_detail','invoice_id = '.$rows[$key]['id']);
            }

        }
        else //Phan them moi phieu nhap dua xuong duoi,tranh truong hop tao phieu rong
        {
            /*
            $id = DB::insert('wh_invoice',$item);
            if($warehouse_id_2 and $warehouse_id_2!='')
            {
                $item['warehouse_id'] =$warehouse_id_2;
        		$item['bill_number'] = DeliveryOrders::get_new_bill_number('EXPORT',$item['warehouse_id']);
        		$item['time'] = time();
                $id_2 = DB::insert('wh_invoice',$item);
            }
            */
        }
        //System::debug($product);exit();
        /** Kimtan them may bien nay de tinh tong cho phieu xuat **/
        $amount_MINIBAR = 0;
        $amount_KARAOKE_drink_goods = 0;
        $amount_KARAOKE_product = 0;
        $amount_RES_drink_goods = 0;
        $amount_RES_product = 0;
        /** end  Kimtan them may bien nay de tinh tong cho phieu xuat **/
    	foreach($product as $key=>$value)
        {
            //Neu la xuat cho minibar chi can dung 1 phieu xuat
            if($invoice_type=='MINIBAR' or $invoice_type=='AMENITIES' or $invoice_type=='VENDING' or $invoice_type=='SPA')
            {
                //Minibar chi can 1 phieu xuat kho, amenities cung the, ban hang va ca massage nua (kid them nhe)
                if(!$id)
                {
                    $item['warehouse_id'] =$warehouse_id;
                    $id = DB::insert('wh_invoice',$item);
                }
                    
                //Add them ton dau ki, de khi edit lay dung gia
                if(!DB::select('wh_start_term_remain','upper(product_id)=\''.$value['product_id'].'\' and warehouse_id='.$warehouse_id.' and portal_id = \''.PORTAL_ID.'\' '))
            	{
            		DB::insert('wh_start_term_remain',array('product_id'=>$value['product_id'],'warehouse_id'=>$warehouse_id,'quantity'=>0,'portal_id'=>PORTAL_ID));
            	}
                $price = !empty($warehouse_remain)?($warehouse_remain[$value['product_id']]['remain_number'] != 0? $warehouse_remain[$value['product_id']]['remain_money']/$warehouse_remain[$value['product_id']]['remain_number'] : 0):0;
                $payment_price = $price*$value['quantity'];
                $detail = array(
                                'invoice_id'=>$id,
                                'product_id'=>$value['product_id'],
                                'num'=>$value['quantity'],
                                'warehouse_id'=>$warehouse_id,
                                'price'=>$price,
                                'payment_price'=>$payment_price              
                                );
                /**
                 * Check xem co xuat am khong
                **/
                if ($value['quantity'] > (isset($warehouse_remain[$value['product_id']])?$warehouse_remain[$value['product_id']]['remain_number']:0))
                {
                    $detail['price'] = 0;
                    $detail['payment_price'] = 0;
                    $detail['tmp'] = 1;
                    // them ban ghi vao wh_tmp
                    $tmp_data = array(
                                        'warehouse_id' => $detail['warehouse_id'],
                                        'product_id' => $detail['product_id'],
                                        'average_price' => $price,
                                        'quantity' => $warehouse_remain[$value['product_id']]['remain_number'],
                                        'total' => $warehouse_remain[$value['product_id']]['remain_number'] * $price,
                                        'time' => time()
                                        );
                    $cond_tmp = 'warehouse_id = '. $detail['warehouse_id'] . ' and product_id = \''. $detail['product_id']. '\'';
                    $average_sql = 'select 
                                    * 
                                    from 
                                        wh_tmp 
                                    where '. $cond_tmp;
                    $isset_tmp_export = DB::fetch($average_sql);
                    if (empty($isset_tmp_export))
                    {
                        DB::insert('wh_tmp', $tmp_data);
                    }
                    unset($tmp_data);
                }
                /**
                 * END:Check 
                **/
                /**
                 * BEGIN: Check neu so luong xuat = sl ton
                 * thi tong tien xuat = tong tien ton 
                **/
                if (abs($value['quantity'] - (isset($warehouse_remain[$value['product_id']])?$warehouse_remain[$value['product_id']]['remain_number']:0)) <= 0.001 and (isset($warehouse_remain[$value['product_id']])?$warehouse_remain[$value['product_id']]['remain_number']:0) > 0)
                {
                    $remain_money = $warehouse_remain[$value['product_id']]['remain_money'];
                    $detail['price'] = $remain_money / $value['quantity'];
                    $detail['payment_price'] = $remain_money;
                }
                /**
                 * END: Check neu so luong xuat = sl ton
                 * thi tong tien xuat = tong tien ton 
                **/
    	    	DB::insert('wh_invoice_detail',$detail);
                /** update cho tong phieu xuat tang dan len **/   
                $amount_MINIBAR += $detail['payment_price'];
                DB::update_id('wh_invoice',array('total_amount'=>$amount_MINIBAR),$id); 
                /** update cho tong phieu xuat tang dan len **/   
            }
            else if($invoice_type=='KARAOKE')//Xuat cho karaoke, dung 2 phieu xuat
            {
                //System::debug($value);
                if($value['type']=='DRINK' || $value['type']=='GOODS')// xuat hang cho kho 2
                {
                    //Tao phieu xuat kho cho kho 2
                    if($warehouse_id_2 and $warehouse_id_2!='' and !$id_2)
                    {
                        $item['warehouse_id'] =$warehouse_id_2;
                        $item['bill_number'] = DeliveryOrders::get_new_bill_number('EXPORT',$item['warehouse_id']);
                		$item['time'] = time();
                        $info = DB::fetch('Select karaoke_reservation.id, karaoke.code, karaoke.name, karaoke.department_id from karaoke_reservation inner join karaoke on karaoke_reservation.karaoke_id = karaoke.id where karaoke_reservation.id ='.$idinvoice);
                        $id_2 = DB::insert('wh_invoice',$item);
                        
                    }
                    if($id_2)
                    {
                        if(!DB::select('wh_start_term_remain','upper(product_id)=\''.$value['product_id'].'\' and warehouse_id='.$warehouse_id_2.' and portal_id = \''.PORTAL_ID.'\' '))
                    	{
                    		DB::insert('wh_start_term_remain',array('product_id'=>$value['product_id'],'warehouse_id'=>$warehouse_id_2,'quantity'=>0,'portal_id'=>PORTAL_ID));
                    	}
                        $price = get_current_average_price($value['product_id'],$warehouse_id_2);
                        $payment_price = $price*$value['quantity'];
                        $detail = array(
                                        'invoice_id'=>$id_2,
                                        'product_id'=>$value['product_id'],
                                        'num'=>$value['quantity'],
                                        'warehouse_id'=>$warehouse_id_2,
                                        'price'=>$price,
                                        'payment_price'=>$payment_price              
                                        );
                        DB::insert('wh_invoice_detail',$detail);
                        $amount_KARAOKE_drink_goods += $payment_price;     
                        DB::update_id('wh_invoice',array('total_amount'=>$amount_KARAOKE_drink_goods),$id_2);
                    }  
                }
                else//Xuat hang cho kho 1
                {
                    //Tao phieu xuat kho cho kho 1
                    if(!$id)
                    {
                        //Phai lam lai buoc nay vi khi xuat cho kho 2 truoc roi moi xuat kho 1, thong tin warehouse_id se bi lay theo kho 2
                        $item['warehouse_id'] =$warehouse_id;
                		$item['bill_number'] = DeliveryOrders::get_new_bill_number('EXPORT',$item['warehouse_id']);
                		$item['time'] = time();
                        $info = DB::fetch('Select karaoke_reservation.id, karaoke.code, karaoke.name, karaoke.department_id from karaoke_reservation inner join karaoke on karaoke_reservation.karaoke_id = karaoke.id where karaoke_reservation.id ='.$idinvoice);
                        $id = DB::insert('wh_invoice',$item);
                    }
                    if(!DB::select('wh_start_term_remain','upper(product_id)=\''.$value['product_id'].'\' and warehouse_id='.$warehouse_id.' and portal_id = \''.PORTAL_ID.'\' '))
                	{
                		DB::insert('wh_start_term_remain',array('product_id'=>$value['product_id'],'warehouse_id'=>$warehouse_id,'quantity'=>0,'portal_id'=>PORTAL_ID));
                	}
                    $price = get_current_average_price($value['product_id'],$warehouse_id);
                    $payment_price = $price*$value['quantity'];
                    $detail = array(
                                    'invoice_id'=>$id,
                                    'product_id'=>$value['product_id'],
                                    'num'=>$value['quantity'],
                                    'warehouse_id'=>$warehouse_id,
                                    'price'=>$price,
                                    'payment_price'=>$payment_price              
                                    );
                    DB::insert('wh_invoice_detail',$detail); 
                    $amount_KARAOKE_product += $payment_price;     
                    DB::update_id('wh_invoice',array('total_amount'=>$amount_KARAOKE_product),$id); 
                }
            }
            else//Xuat cho nha hang, dung 2 phieu xuat
            {
                
                if($value['type']=='DRINK' || $value['type']=='GOODS')// xuat hang cho kho 2
                {
                    //Tao phieu xuat kho cho kho 2
                    if($warehouse_id_2 and $warehouse_id_2!='' and !$id_2)
                    {
                        $item['warehouse_id'] =$warehouse_id_2;
                		$item['bill_number'] = DeliveryOrders::get_new_bill_number('EXPORT',$item['warehouse_id']);
                		$item['time'] = time();
                        $info = DB::fetch('Select bar_reservation.id, bar.code, bar.name, bar.department_id from bar_reservation inner join bar on bar_reservation.bar_id = bar.id where bar_reservation.id ='.$idinvoice);
                        $id_2 = DB::insert('wh_invoice',$item);
                        
                    }
                    if($id_2)
                    {
                        
                        if(!DB::select('wh_start_term_remain','upper(product_id)=\''.$value['product_id'].'\' and warehouse_id='.$warehouse_id_2.' and portal_id = \''.PORTAL_ID.'\' '))
                    	{
                    		DB::insert('wh_start_term_remain',array('product_id'=>$value['product_id'],'warehouse_id'=>$warehouse_id_2,'quantity'=>0,'portal_id'=>PORTAL_ID));
                    	}
                        $price = !empty($warehouse_remain_2)?($warehouse_remain_2[$value['product_id']]['remain_number'] != 0? $warehouse_remain_2[$value['product_id']]['remain_money']/$warehouse_remain_2[$value['product_id']]['remain_number'] : 0):0;
                        $payment_price = $price*$value['quantity'];
                        $detail = array(
                                        'invoice_id'=>$id_2,
                                        'product_id'=>$value['product_id'],
                                        'num'=>$value['quantity'],
                                        'warehouse_id'=>$warehouse_id_2,
                                        'price'=>$price,
                                        'payment_price'=>$payment_price              
                                        );
                        /**
                         * Check xem co xuat am khong
                        **/
                        if ($value['quantity'] > $warehouse_remain_2[$value['product_id']]['remain_number'])
                        {
                            $detail['price'] = 0;
                            $detail['payment_price'] = 0;
                            $detail['tmp'] = 1;
                            // them ban ghi vao wh_tmp
                            $tmp_data = array(
                                                'warehouse_id' => $detail['warehouse_id'],
                                                'product_id' => $detail['product_id'],
                                                'average_price' => $price,
                                                'quantity' => $warehouse_remain_2[$value['product_id']]['remain_number'],
                                                'total' => $warehouse_remain_2[$value['product_id']]['remain_number'] * $price,
                                                'time' => time()
                                                );
                            $cond_tmp = 'warehouse_id = '. $detail['warehouse_id'] . ' and product_id = \''. $detail['product_id']. '\'';
                            $average_sql = 'select 
                                            * 
                                            from 
                                                wh_tmp 
                                            where '. $cond_tmp;
                            $isset_tmp_export = DB::fetch($average_sql);
                            if (empty($isset_tmp_export))
                            {
                                DB::insert('wh_tmp', $tmp_data);
                            }
                            unset($tmp_data);
                        }
                        /**
                         * END:Check 
                        **/
                        /**
                         * BEGIN: Check neu so luong xuat = sl ton
                         * thi tong tien xuat = tong tien ton 
                        **/
                        if (abs($value['quantity'] - $warehouse_remain_2[$value['product_id']]['remain_number']) <= 0.001 and $warehouse_remain_2[$value['product_id']]['remain_number'] > 0)
                        {
                            $remain_money = $warehouse_remain_2[$value['product_id']]['remain_money'];
                            $detail['price'] = $remain_money / $value['quantity'];
                            $detail['payment_price'] = $remain_money;
                        }
                        /**
                         * END: Check neu so luong xuat = sl ton
                         * thi tong tien xuat = tong tien ton 
                        **/
                        //System::debug($detail);exit();
                        DB::insert('wh_invoice_detail',$detail);    
                        $amount_RES_drink_goods += $detail['payment_price'];     
                        DB::update_id('wh_invoice',array('total_amount'=>$amount_RES_drink_goods),$id_2); 
                    }
                     
                }
                else//Xuat hang cho kho 1
                {
                    //Tao phieu xuat kho cho kho 1
                    if(isset($warehouse_id))
                    {
                        if(!$id)
                        {
                            //Phai lam lai buoc nay vi khi xuat cho kho 2 truoc roi moi xuat kho 1, thong tin warehouse_id se bi lay theo kho 2
                            $item['warehouse_id'] =$warehouse_id;
                    		$item['bill_number'] = DeliveryOrders::get_new_bill_number('EXPORT',$item['warehouse_id']);
                    		$item['time'] = time();
                            $info = DB::fetch('Select bar_reservation.id, bar.code, bar.name, bar.department_id from bar_reservation inner join bar on bar_reservation.bar_id = bar.id where bar_reservation.id ='.$idinvoice);
                            $id = DB::insert('wh_invoice',$item);
                        }
                        if(!DB::select('wh_start_term_remain','upper(product_id)=\''.$value['product_id'].'\' and warehouse_id='.$warehouse_id.' and portal_id = \''.PORTAL_ID.'\' '))
                    	{
                    		DB::insert('wh_start_term_remain',array('product_id'=>$value['product_id'],'warehouse_id'=>$warehouse_id,'quantity'=>0,'portal_id'=>PORTAL_ID));
                    	}
                        $price = !empty($warehouse_remain)?($warehouse_remain[$value['product_id']]['remain_number'] != 0? $warehouse_remain[$value['product_id']]['remain_money']/$warehouse_remain[$value['product_id']]['remain_number'] : 0):0;
                        $payment_price = $price*$value['quantity'];
                        $detail = array(
                                        'invoice_id'=>$id,
                                        'product_id'=>$value['product_id'],
                                        'num'=>$value['quantity'],
                                        'warehouse_id'=>$warehouse_id,
                                        'price'=>$price,
                                        'payment_price'=>$payment_price              
                                        );
                        /**
                         * Check xem co xuat am khong
                        **/
                        if ($value['quantity'] > $warehouse_remain[$value['product_id']]['remain_number'])
                        {
                            $detail['price'] = 0;
                            $detail['payment_price'] = 0;
                            $detail['tmp'] = 1;
                            // them ban ghi vao wh_tmp
                            $tmp_data = array(
                                                'warehouse_id' => $detail['warehouse_id'],
                                                'product_id' => $detail['product_id'],
                                                'average_price' => $price,
                                                'quantity' => $warehouse_remain[$value['product_id']]['remain_number'],
                                                'total' => $warehouse_remain[$value['product_id']]['remain_number'] * $price,
                                                'time' => time()
                                                );
                            $cond_tmp = 'warehouse_id = '. $detail['warehouse_id'] . ' and product_id = \''. $detail['product_id']. '\'';
                            $average_sql = 'select 
                                            * 
                                            from 
                                                wh_tmp 
                                            where '. $cond_tmp;
                            $isset_tmp_export = DB::fetch($average_sql);
                            if (empty($isset_tmp_export))
                            {
                                DB::insert('wh_tmp', $tmp_data);
                            }
                            unset($tmp_data);
                        }
                        /**
                         * END:Check 
                        **/
                        /**
                         * BEGIN: Check neu so luong xuat = sl ton
                         * thi tong tien xuat = tong tien ton 
                        **/
                        if (abs($value['quantity'] - $warehouse_remain[$value['product_id']]['remain_number']) <= 0.001 and $warehouse_remain[$value['product_id']]['remain_number'] > 0)
                        {
                            $remain_money = $warehouse_remain[$value['product_id']]['remain_money'];
                            $detail['price'] = $remain_money / $value['quantity'];
                            $detail['payment_price'] = $remain_money;
                        }
                        /**
                         * END: Check neu so luong xuat = sl ton
                         * thi tong tien xuat = tong tien ton 
                        **/
                        DB::insert('wh_invoice_detail',$detail);
                        $amount_RES_product += $detail['payment_price'];     
                        DB::update_id('wh_invoice',array('total_amount'=>$amount_RES_product),$id); 
                        /** Kimtan:: update lai so luong thuc trong wh_total  **/
                    }  
                }
            }
		}
		//tao phieu xuat
        /*
        if($idmax = DB::fetch('select max(id) as idmax from WH_INVOICE','idmax'))
        {
			$id =($idmax+1);
            $item['id']= $id;
            $id = (strlen($id)<2)?'0'.$id:$id;
			$item['bill_number'] ='PX'.$id;
		}
        else
        {
			$id=1;
			$item['id']= $id;
			$item['bill_number'] ='PX01';
		}
        */
		/*
        $sql = "";
		$total_proc = "";
		$sqlid = 'select id From warehouse where code =\''.$warehouse_id.'\'';
		$id = DB::fetch($sqlid,'id');
		$item['total_amount'] = 0;
		$invoice='';
		if($warehouse_id == 'REST')
        {
			$sql='SELECT name FROM warehouse WHERE code =\'REST\'';
			$invoice='REST'.$idinvoice;
			$total_proct = '
						select 	sum (quantity*price) as total from 
							(
								select 
										 (bar_reservation_product.quantity - bar_reservation_product.quantity_discount)as quantity, bar_reservation_product.price 
								from 
										bar_reservation_product 
								inner join 		
										product on product.id = bar_reservation_product.product_id	
								where  bar_reservation_id =\''.$idinvoice.'\'
									AND product.type in (\'PRODUCT\',\'GOODS\',\'TOOL\',\'EQUIPMENT\')
							) ';
			 $item['total_amount'] = DB::fetch($total_proct,'total');
		}
		if($warehouse_id == 'HSKP')
        {
			$invoice='HSKP'.$idinvoice;
			$sql='SELECT name FROM warehouse WHERE code =\'HSKP\'';
			$total_proct = ' select 
									sum(housekeeping_invoice_detail.quantity * housekeeping_invoice_detail.price) as total
							FROM 
									housekeeping_invoice_detail
							inner join 
									product on product.id = housekeeping_invoice_detail.product_id
							where  invoice_id =\''.$idinvoice.'\'
								AND product.type in (\'PRODUCT\',\'GOODS\',\'TOOL\',\'EQUIPMENT\')
							';
		 $item['total_amount'] = DB::fetch($total_proct,'total');
		}
		if($warehouse_id == 'MASSA'){
			//$sql='SELECT name FROM warehouse WHERE code =\'MASSA\'';
			//$sqlporduct = ' select  product_id, num from ';
		}
		if($warehouse_id == 'REPT'){
				//$sql='SELECT name FROM warehouse WHERE code =\'REPT\'';
				//$sqlporduct = ' select product_id, num from ';
		}
        */
        
	}
    
	static function auto_export_warehouse($idinvoice,$invoice_type)
    {
        //Lay cac sp dc su dung trong hoa don
        $product = DeliveryOrders::get_list_product($idinvoice,$invoice_type);
        require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
        
        $item = array();
		$item['type']='EXPORT';
		$item['user_id']= Session::get('user_id');
		$item['create_date'] = Date_Time::to_orc_date(date('d/m/Y'));
		$item['time'] = time();
        $item['portal_id'] = PORTAL_ID;
        $item['invoice_id'] = $idinvoice;
        $item['invoice_type'] = $invoice_type;
		
        //M?ng ch?a 
        $ids_wh_invoice = array();
        
        //M?ng ch?a 
        $wh_ids = array();
        //TH edit
        if($rows = DB::fetch_all('select * from wh_invoice where invoice_id ='.$idinvoice.' and invoice_type = \''.$invoice_type.'\' and portal_id = \''.PORTAL_ID.'\' '))
        {
            foreach($rows as $key => $value)
            {
                
                if(!in_array($value['warehouse_id'],$wh_ids))
                {
                    array_push($wh_ids,$value['warehouse_id']);  
                }
                if($invoice_type = 'VENDING')
                {
                    $ids_wh_invoice['id_'.$value['warehouse_id']] = $value['id'];
                }
                else
                {
                    $ids_wh_invoice['id_'.$value['warehouse_id'].'_'.$value['id']] = $value['id'];
                }
                DB::update_id('wh_invoice',array('last_modify_user_id'=>Session::get('user_id'),'last_modify_time'=>time()),$rows[$key]['id']);
                DB::delete('wh_invoice_detail','invoice_id = '.$rows[$key]['id']);
            }
            
            $wh_ids_null = $wh_ids;
 
            foreach($product as $key=>$va)
            {
                if($va['department_id'] && $va['department_code'] && $va['warehouse_id'])
                {
                    foreach($wh_ids_null as $id => $v)
                    {
                        if($v == $va['warehouse_id'])
                        {
                            unset($wh_ids_null[$id]);
                            break; 
                        }
                        
                    }

                    //N?u kho 
                    if(!in_array($va['warehouse_id'],$wh_ids))
                    {
                        $item['warehouse_id'] = $va['warehouse_id'];
                        $item['bill_number'] = DeliveryOrders::get_new_bill_number('EXPORT',$item['warehouse_id']);
                        $item['note'] = Portal::language('export').' '.Portal::language('warehouse').' '.Portal::language('vending').' ('.Portal::language('department').': '.$va['department_code'].') '.Portal::language('code').' '.$idinvoice;
                        $wh_invoice_id = DB::insert('wh_invoice',$item);
                        
                        $ids_wh_invoice['id_'.$va['warehouse_id']] = $wh_invoice_id;
                         
                        array_push($wh_ids,$va['warehouse_id']);  
                    }
                }
                
                if( isset( $ids_wh_invoice['id_'.$va['warehouse_id']] ) )
                {
                    if(!DB::select('wh_start_term_remain','upper(product_id)=\''.$va['product_id'].'\' and warehouse_id='.$va['warehouse_id'].' and portal_id = \''.PORTAL_ID.'\' '))
                	{
                		DB::insert('wh_start_term_remain',array('product_id'=>$va['product_id'],'warehouse_id'=>$va['warehouse_id'],'quantity'=>0,'portal_id'=>PORTAL_ID));
                	}
                    $price = get_current_average_price($va['product_id'],$va['warehouse_id']);
                    $payment_price = $price*$va['quantity'];
                    $detail = array(
                                    'invoice_id'=>$ids_wh_invoice['id_'.$va['warehouse_id']],
                                    'product_id'=>$va['product_id'],
                                    'num'=>$va['quantity'],
                                    'warehouse_id'=>$va['warehouse_id'],
                                    'price'=>$price,
                                    'payment_price'=>$payment_price              
                                    );
                    DB::insert('wh_invoice_detail',$detail);
                }
            }
            foreach($wh_ids_null as $id => $v)
            {
                foreach($rows as $key => $value)
                {
                    if($value['warehouse_id']==$v)
                    {
                        DB::delete_id('wh_invoice',$rows[$key]['id']);
                        DB::delete('wh_invoice_detail','invoice_id = '.$rows[$key]['id']);
                    }
                    
                } 
            }

        }
        else//TH add new
        {  
            foreach($product as $key=>$value)
            {
                
                if($invoice_type=='VENDING')
                {
                    if($value['department_id'] && $value['department_code'] && $value['warehouse_id'])
                    {
                        //N?u kho chua dc t?o PX
                        if(!in_array($value['warehouse_id'],$wh_ids))
                        {
                            $item['warehouse_id'] = $value['warehouse_id'];
                            $item['bill_number'] = DeliveryOrders::get_new_bill_number('EXPORT',$item['warehouse_id']);
                            $item['note'] = Portal::language('export').' '.Portal::language('warehouse').' '.Portal::language('vending').' ('.Portal::language('department').': '.$value['department_code'].') '.Portal::language('code').' '.$idinvoice;
                            $wh_invoice_id = DB::insert('wh_invoice',$item);
                            echo $wh_invoice_id.'aaaa';
                            $ids_wh_invoice['id_'.$value['warehouse_id']] = $wh_invoice_id;
                            //
                            array_push($wh_ids,$value['warehouse_id']);  
                        }
                        //
                        if( isset( $ids_wh_invoice['id_'.$value['warehouse_id']] ) )
                        {
                            if(!DB::select('wh_start_term_remain','upper(product_id)=\''.$value['product_id'].'\' and warehouse_id='.$value['warehouse_id'].' and portal_id = \''.PORTAL_ID.'\' '))
                        	{
                        		DB::insert('wh_start_term_remain',array('product_id'=>$value['product_id'],'warehouse_id'=>$value['warehouse_id'],'quantity'=>0,'portal_id'=>PORTAL_ID));
                        	}
                            $price = get_current_average_price($value['product_id'],$value['warehouse_id']);
                            $payment_price = $price*$value['quantity'];
                            $detail = array(
                                            'invoice_id'=>$ids_wh_invoice['id_'.$value['warehouse_id']],
                                            'product_id'=>$value['product_id'],
                                            'num'=>$value['quantity'],
                                            'warehouse_id'=>$value['warehouse_id'],
                                            'price'=>$price,
                                            'payment_price'=>$payment_price              
                                            );
                            DB::insert('wh_invoice_detail',$detail);
                            
                        }
                    }
                }
                else//Xuat cho nha hang, dung 2 phieu xuat
                {
                    
                }
    		}
            //exit();
        }
    }
    
	static function get_list_product($idinvoice,$invoice_type,$warehouse_id = false, $warehouse_id_2 = false)
    {
        //mang luu cac san pham(material, good) dc su dung
        require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
        $product_used = array();
        if($invoice_type =='MINIBAR')
        {
            $sqlporduct = ' select  
                        			housekeeping_invoice_detail.id,
                        			housekeeping_invoice_detail.product_id,
                        			housekeeping_invoice_detail.quantity,
                                    product.type,
                                    product.name_'.Portal::language().' as product_name,
                                    unit.name_'.Portal::language().' as unit_name			
                            	from 
                                	housekeeping_invoice 
                                	inner join housekeeping_invoice_detail on housekeeping_invoice.id  = housekeeping_invoice_detail.invoice_id
                                    inner join product on product.id = housekeeping_invoice_detail.product_id
                                    inner join unit on product.unit_id = unit.id
                            	where  
                                    housekeeping_invoice.id =\''.$idinvoice.'\'
                                    and housekeeping_invoice_detail.quantity!=0
                            	';
        }
        else
        if($invoice_type=='AMENITIES')
        {
            
            $sqlporduct = '
                    select
                        amenities_used_detail.product_id as id,
                        sum(quantity) as quantity,
                        amenities_used_detail.product_id,
                        product.type,
                        product.name_'.Portal::language().' as product_name,
                        unit.name_'.Portal::language().' as unit_name
                    from
                        amenities_used_detail 
                        inner join product on product.id = amenities_used_detail.product_id
                        inner join unit on product.unit_id = unit.id
                    where
                        amenities_used_id = \''.$idinvoice.'\'
                        and portal_id = \''.PORTAL_ID.'\'
                    group by 
                        amenities_used_detail.product_id,
                        product.type,
                        product.name_'.Portal::language().',
                        unit.name_'.Portal::language().'
                    ';
            
        }
        else
        if($invoice_type=='VENDING')
        {
            $sqlporduct = ' select  
                    			ve_reservation_product.id,
                    			ve_reservation_product.product_id,
                    			ve_reservation_product.quantity,
                                product.type,
                                product.name_'.Portal::language().' as product_name,
                                unit.name_'.Portal::language().' as unit_name,
                                ve_reservation_product.department_id,
                                portal_department.warehouse_id,
                                portal_department.warehouse_id_2,
                                department.code as department_code			
                        	from 
                            	ve_reservation_product 
                            	inner join ve_reservation on ve_reservation_product.bar_reservation_id  = ve_reservation.id
                                inner join product on product.id = ve_reservation_product.product_id
                                inner join unit on product.unit_id = unit.id
                                left join department on ve_reservation_product.department_id = department.id
                                left join portal_department on department.code = portal_department.department_code and portal_department.portal_id = \''.PORTAL_ID.'\'
                        	where  
                                bar_reservation_id =\''.$idinvoice.'\'
                                and (product.type =\'GOODS\' OR product.type =\'DRINK\' OR product.type =\'PRODUCT\' )
                        	';
            
        }
        //start: KID them de xuat kho cho spa
        else
        if($invoice_type=='SPA')
        {
            $sqlporduct = '
                    select
                        massage_product_consumed.product_id as id,
                        sum(quantity) as quantity,
                        massage_product_consumed.product_id,
                        product.type,
                        product.name_'.Portal::language().' as product_name,
                        unit.name_'.Portal::language().' as unit_name
                    from
                        massage_product_consumed 
                        inner join product on product.id = massage_product_consumed.product_id
                        inner join unit on product.unit_id = unit.id
                        inner join massage_reservation_room on  massage_reservation_room.id = massage_product_consumed.reservation_room_id
                    where
                        reservation_room_id = \''.$idinvoice.'\'
                        and massage_reservation_room.portal_id = \''.PORTAL_ID.'\'
                        and (product.type =\'GOODS\' OR product.type =\'DRINK\' OR product.type =\'PRODUCT\' )
                    group by 
                        massage_product_consumed.product_id,
                        product.type,
                        product.name_'.Portal::language().',
                        unit.name_'.Portal::language().'
                    ';
            
        }
        //end
        else
        if($invoice_type=='KARAOKE')//karaoke
        {
            $sqlporduct = ' select  
                    			karaoke_reservation_product.id,
                    			karaoke_reservation_product.product_id,
                    			karaoke_reservation_product.quantity - karaoke_reservation_product.quantity_cancel as quantity,
                                product.type,
                                product.name_'.Portal::language().' as product_name,
                                unit.name_'.Portal::language().' as unit_name			
                        	from 
                            	karaoke_reservation_product 
                            	inner join karaoke_reservation on karaoke_reservation_product.karaoke_reservation_id  = karaoke_reservation.id
                                inner join product on product.id = karaoke_reservation_product.product_id
                                inner join unit on product.unit_id = unit.id
                        	where  
                                karaoke_reservation_id =\''.$idinvoice.'\' 
                                AND (karaoke_reservation.status =\'CHECKOUT\'
                                OR karaoke_reservation.status =\'CHECKIN\')
                        	';
        }
        else//nha hang
        {
            $sqlporduct = ' select  
                    			bar_reservation_product.id,
                    			bar_reservation_product.product_id,
                    			bar_reservation_product.quantity - bar_reservation_product.quantity_cancel as quantity,
                                product.type,
                                product.name_'.Portal::language().' as product_name,
                                unit.name_'.Portal::language().' as unit_name			
                        	from 
                            	bar_reservation_product 
                            	inner join bar_reservation on bar_reservation_product.bar_reservation_id  = bar_reservation.id
                                inner join product on product.id = bar_reservation_product.product_id
                                inner join unit on product.unit_id = unit.id
                        	where  
                                bar_reservation_id =\''.$idinvoice.'\' 
                                AND (bar_reservation.status =\'CHECKOUT\'
                                OR bar_reservation.status =\'CHECKIN\')
                                and product.type != \'SERVICE\'
                        	';
        }
        
		//cac san pham dc su dung trong hoa don
        $products = DB::fetch_all($sqlporduct);
        
        foreach($products as $key=>$value)
        {
            $bar_reservation_product_id = $value['id'];
            //neu product
            if($value['type']=='PRODUCT'||$value['type']=='DRINK')
            {
                //neu product dc lam tu cac material
                if(
                    $material = DB::fetch_all('Select 
                                                product_material.id, 
                                                product_material.material_id as product_id, 
                                                product_material.quantity, 
                                                product.type, 
                                                product.name_'.Portal::language().' as product_name,
                                                unit.name_'.Portal::language().' as unit_name	    
                                            from 
                                                product_material
                                                inner join product on  product_material.material_id = product.id
                                                inner join unit on product.unit_id = unit.id
                                            where 
                                                product_material.product_id = \''.$value['product_id'].'\' 
                                                and product_material.portal_id = \''.PORTAL_ID.'\'
                                            '))
                {
                    $original_price = 0;
                    foreach($material as $k=>$v)
                    {
                        if(isset($product_used[$v['product_id'].'_'.$value['type']]))
                        {
                            //material dc su dung = dinh. luong * so luong product trong phieu 
                            $product_used[$v['product_id'].'_'.$value['type']]['is_materialed'] = 1;
                            $product_used[$v['product_id'].'_'.$value['type']]['quantity'] +=  $v['quantity']*$value['quantity'];
                        }  
                        else
                        {
                            unset($v['id']);
                            //$v chi co dinh luong cua product, nen se phai * voi so luong duoc su dung
                            if($value['type']=='DRINK')
                                $v['type'] = 'DRINK';
                            $product_used[$v['product_id'].'_'.$value['type']] =  $v;
                            //$product_used[$v['product_id']]['type'] = 
                            $product_used[$v['product_id'].'_'.$value['type']]['quantity'] = $product_used[$v['product_id'].'_'.$value['type']]['quantity']*$value['quantity'];
                            $product_used[$v['product_id'].'_'.$value['type']]['is_materialed'] = 1;
                        }       
                    }
                }
                //Start: KID cmt de loai bo nhung san phan khong cÃ�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¿Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¿Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¿Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â³ dinh muc
                /*
                else
                {
                    if($warehouse_id != '' and $warehouse_id_2 != '')
                    {
                        if($value['type'] == 'PRODUCT')
                        {
                            $material_price = get_current_average_price($value['product_id'],$warehouse_id);
                        }
                        else
                        {
                            $material_price = get_current_average_price($value['product_id'],$warehouse_id_2);
                        }
                        $original_price = $material_price*$value['quantity'];
                    }
                    if(isset($product_used[$value['product_id']]))
                    {
                        $product_used[$value['product_id']]['quantity'] +=  $value['quantity'];
                        $product_used[$value['product_id']]['is_materialed'] = 0;
                    }
                    else
                    {
                        unset($value['id']);
                        $product_used[$value['product_id']] =  $value;
                        $product_used[$value['product_id']]['is_materialed'] = 0;
                    }
                    if($warehouse_id != '' and $warehouse_id_2 != '' and $invoice_type=='KARAOKE')
                    {
                        DB::update('karaoke_reservation_product',array('original_price'=>$original_price,'is_processed'=>0),'id = '.$bar_reservation_product_id);
                    }
                    else
                    if($warehouse_id != '' and $warehouse_id_2 != '')
                    {
                        DB::update('bar_reservation_product',array('original_price'=>$original_price,'is_processed'=>0),'id = '.$bar_reservation_product_id);
                    }
                }
                */
                //end: KID cmt de loai bo nhung san phan khong cÃ�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¿Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¿Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â¿Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã�Â¯Ã�ï¿½Ã�Â¿Ã�ï¿½Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�Â¯Ã�Â¿Ã�Â½Ã�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â³ dinh muc
            }
            else//neu khong se la goods
            {
                if(isset($product_used[$value['product_id'].'_'.$value['type']]))
                    $product_used[$value['product_id'].'_'.$value['type']]['quantity'] +=  $value['quantity'];
                else
                {
                    unset($value['id']);
                    $product_used[$value['product_id'].'_'.$value['type']] =  $value;
                }    
            }
        }
        
        return $product_used;
  
        /**
         * cho nay de viet tiep cho cac bo phan khac, nhung ma chua viet =))
         */
  
        $sqlid = 'select id From warehouse where code = \''.$warehouse_id.'\'';
		$id=  DB::fetch($sqlid,'id');
		$products = array();
		if($warehouse_id == 'REST'){
			 $sqlporduct = ' select  
			 						bar_reservation_product.id,
									bar_reservation_product.product_id,
									bar_reservation_product.price, 
									bar_reservation_product.remain,
									product_price_list.unit_id,
									bar_reservation_product.quantity,
									product.type ,
									product.name_'.Portal::language().' as name	,
									bar_reservation.status					
							from 
									bar_reservation_product 
							inner join bar_reservation on bar_reservation_product.bar_reservation_id  = bar_reservation.id
							inner join 		
									product on product.id = bar_reservation_product.product_id	
							inner join product_price_list on bar_reservation_product.price_id = product_price_list.id
							where  bar_reservation_id =\''.$idinvoice.'\' AND bar_reservation.status =\'CHECKOUT\' AND product.id != \'OUTSIDE\' 
								AND product.type in (\'PRODUCT\',\'GOODS\',\'TOOL\',\'MATERIAL\',\'EQUIPMENT\')
							';
	 $products = DB::fetch_all($sqlporduct);
	 $units = DB::fetch_all('
	 				select 
					u_m.base_unit_id as id, 
					unit.name_1 as name, 
					u_m.id as unit_id,
					u_m.value
					from 
						(
							select unit.id,
							unit.value, unit.base_unit_id
							FROM 
								unit INNER JOIN bar_reservation_product  on unit.id = bar_reservation_product.unit_id
						    where bar_reservation_id =\''.$idinvoice.'\' AND base_unit_id is not null
						) u_m 
					inner join unit on u_m.base_unit_id = unit.id
					where u_m.base_unit_id = unit.id');
		}
		if($warehouse_id == 'HSKP'){
			$sqlporduct = ' select  
									housekeeping_invoice_detail.id,
									housekeeping_invoice_detail.product_id, 
									housekeeping_invoice_detail.quantity,
									housekeeping_invoice_detail.price,
									product_price_list.unit_id,
									product.type, 
									product.name_'.Portal::language().' as name
							FROM 
									housekeeping_invoice_detail
							inner join 
									product on product.id = housekeeping_invoice_detail.product_id
							inner join 
									product_price_list on housekeeping_invoice_detail.price_id = product_price_list.id
							where  invoice_id =\''.$idinvoice.'\'
								AND product.type in (\'PRODUCT\',\'GOODS\',\'TOOL\',\'EQUIPMENT\')
							';
						$products = DB::fetch_all($sqlporduct);	
				$units = DB::fetch_all('
	 				select 
					u_m.base_unit_id as id, 
					unit.name_1 as name, 
					u_m.id as unit_id,
					u_m.value
					from 
						(
							select unit.id,
							unit.value, unit.base_unit_id
							FROM 
								unit INNER JOIN housekeeping_invoice_detail  on unit.id = housekeeping_invoice_detail.unit_id
						    where invoice_id =\''.$idinvoice.'\' AND base_unit_id is not null
						) u_m 
					inner join unit on u_m.base_unit_id = unit.id
					where u_m.base_unit_id = unit.id');
		}
		if($warehouse_id == 'MASSA'){
			//$sql='SELECT name FROM warehouse WHERE code =\'MASSA\'';
			//$sqlporduct = ' select  product_id, num from ';
		}
		if($warehouse_id == 'REPT'){
				//$sql='SELECT name FROM warehouse WHERE code =\'REPT\'';
				//$sqlporduct = ' select product_id, num from ';
		}
		$arr_product = array();
		$old_id = DB::fetch('select * from WH_INVOICE where invoice_id = \''.$idinvoice.$warehouse_id.'\'');
		if($old_id['id'] != ''){
			$invoice_id = $old_id['id'];
		}else{
			$idmax = DB::fetch('select max(id) as idmax from WH_INVOICE','idmax');
			$invoice_id =($idmax+1);
		}
		$pd = array();
		$ar_p = array();
		foreach($products as $key =>$value){
			foreach($units as $k_u=>$v_u){
				if($value['unit_id'] == $v_u['unit_id']){
				$value['quantity'] *=$v_u['value'];
				$value['unit_id'] = $v_u['id'];
			   }	
			}
			if($value['type'] =='MATERIAL'){
				if(isset($arr_product[$value['product_id']])){
					$arr_product[$value['product_id']]['num'] += $value['quantity'];
				}
				else{
					$arr_product[$value['product_id']]['invoice_id'] = $invoice_id;
					$arr_product[$value['product_id']]['product_id'] = $value['product_id'];
					$arr_product[$value['product_id']]['num'] = $value['quantity'];
					$arr_product[$value['product_id']]['price'] = $value['price'];
					$arr_product[$value['product_id']]['unit_id'] = $value['unit_id'];
					$arr_product[$value['product_id']]['warehouse_id'] = $id;
					$arr_product[$value['product_id']]['note'] = '';
				}
			}else{
				if(!isset($ar_p[$value['product_id']])){
					$ar_p[$value['product_id']]['product_id'] = $value['product_id'];
				}
				if(!isset($pd[$value['product_id']])){
						$pd[$value['product_id']]['invoice_id'] = $invoice_id;
						$pd[$value['product_id']]['product_id'] = $value['product_id'];
						$pd[$value['product_id']]['id'] = $value['product_id'];
						$pd[$value['product_id']]['name'] = $value['name'];
						$pd[$value['product_id']]['num'] = $value['quantity'];
						$pd[$value['product_id']]['price'] = $value['price'];
						$pd[$value['product_id']]['unit_id'] = $value['unit_id'];
						$pd[$value['product_id']]['warehouse_id'] = $id;
						$pd[$value['product_id']]['note'] = '';
				}else{
						$pd[$value['product_id']]['num'] += $value['quantity'];
				}
			}
		}
		$prd_material='';
		foreach($ar_p as $k_p=>$v_p){
			if($prd_material){
				$prd_material .=',';
		    }
			$prd_material .='\''.$v_p['product_id'].'\'';
			
		}
			$prd = array();
			$prd = $pd;
		if($prd_material){
			// lay ra san pham nguyen vat lieu
			// doanh nay sai phai don vi vi trong bang product_price_list 1 san pham luu tru nhieu don vi 
			$sql_material ='
							SELECT 
								product_material.id, 
								product_material.product_id, 
								material_id, 
								product.name_'.Portal::language().' as name,
								quantity as quantity, 
								product_price_list.unit_id,
								product_price_list.price
							FROM 
								product_material
							INNER JOIN 
								product on product.id = product_material.material_id
							INNER JOIN
								 product_price_list on product_material.price_id = product_price_list.id
							WHERE 
								product_material.product_id in ('.$prd_material.')';
			$units = DB::fetch_all('
	 				select 
					u_m.base_unit_id as id, 
					unit.name_1 as name, 
					u_m.id as unit_id,
					u_m.value
					from 
						(
							select unit.id,
							unit.value, unit.base_unit_id
							FROM 
								unit INNER JOIN product_price_list  on unit.id = product_price_list.unit_id
							inner join 
							   product on product_price_list.product_id = product.id
						    where product.id in ('.$prd_material.') AND base_unit_id is not null
						) u_m 
					inner join unit on u_m.base_unit_id = unit.id
					where u_m.base_unit_id = unit.id');
			$product_material = DB::fetch_all($sql_material);
			foreach($product_material as $k_m =>$v_m){				
				foreach($units as $k_u=>$v_u){
					if($v_m['unit_id'] == $v_u['unit_id']){
						$v_m['quantity'] *= $v_u['value'];
						$v_m['unit_id'] = $v_u['id'];
				   }	
				 }
				if(isset($pd[$v_m['product_id']])){   
						if(isset($arr_product[$v_m['material_id']])){
							$arr_product[$k_m]['num'] +=($pd[$v_m['product_id']]['num']* $v_m['quantity']);
							$arr_product[$k_m]['note'] ='';
						}else{						    
									$arr_product[$v_m['material_id']]['invoice_id'] = $invoice_id;
									$arr_product[$v_m['material_id']]['product_id'] = $v_m['material_id'];
									$arr_product[$v_m['material_id']]['num'] = ($pd[$v_m['product_id']]['num']* $v_m['quantity']);
									$arr_product[$v_m['material_id']]['price'] = $v_m['price'];
									$arr_product[$v_m['material_id']]['unit_id'] = $v_m['unit_id'];
									if(isset($arr_product[$v_m['material_id']]['note'])){
										$arr_product[$v_m['material_id']]['note'] .=','.$pd[$v_m['product_id']]['name'];
									}else{
										$arr_product[$v_m['material_id']]['note'] = $pd[$v_m['product_id']]['name'];
									}
									$arr_product[$v_m['material_id']]['warehouse_id'] = $id; 
						}
						unset ($prd[$v_m['product_id']]);
				}
			}	
		}	
		foreach($prd as $k=>$v){
			if(!isset($arr_product[$k])){
				$arr_product[$k]['invoice_id'] = $invoice_id;
				 $arr_product[$k]['product_id'] = $v['product_id'];
				$arr_product[$k]['num'] = $v['num'];
				$arr_product[$k]['price'] = $v['price'];
				$arr_product[$k]['unit_id'] = $v['unit_id'];
				$arr_product[$k]['warehouse_id'] = $id;
				$arr_product[$k]['note'] = '';
			}else{
				$arr_product[$k]['num'] += $v['num'];
			}
		}
		return $arr_product;
	}
}
?>
