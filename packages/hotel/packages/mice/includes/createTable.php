<?php

        function booked_table($act="",$table_info,$product_list,$type='')
        {  
            
            /**
             * $act co 2 gia tri la booked hoac checkin
             * 
             * Tham so $table_info truyen vao duoi dang mang trong do :
             * 2. $table_info['arrival_time'] : ngay den dang timestamp (H:i d/m/Y) 
             * 3. $table_info['departure_time'] : ngay di dang timestamp (H:i d/m/Y)
             * 4. $table_info['full_rate']
             * 5. $table_info['full_charge']
             * 6. $table_info['note'] : ghi chu
             * 7. $table_info['customer_id']  : id cua cong ty
             * 8. $table_info['agent_name'] : ten Cong ty
             * 9. $table_info['receiver_name'] : ten khach
             * 10. $table_info['tax_rate'] : tien thue 
             * 11. $table_info['foc'] : mien phi ( gia tri 1 la co , 0 la khong)
             * 14. $table_info['banquet_order_type'] : loai tiec
             * 18. $table_info['table_id'] : id cua ban dat
             * 19. $table_info['bar_id'] : id cua bar  
             * 20. $table_info['num_people'] : So khach 
             * 21. $table_info['order_person'] : ten nguoi order    
             * 22. $table_info['discount'] : Giam gia theo so tien tren toan hoa don
             * 23. $table_info['discount_percent'] : Giam gia theo % tren toan hoa don
             * 24. $table_info['bar_fee_rate'] : Tien thue
             * 25. $table_info['mice_reservation_id'] : Mice ID
             * --- $product_list la mang chua thong tin cac san pham trong do :
             *          
             * 1.  $product_list[$i]['product_id'] : id cua san pham  -- $i tang dan deu tu 1
             * 2.  $product_list[$i]['quantity'] : so luong
             * 3.  $product_list[$i]['price_id']
             * 3.  $product_list[$i]['quantity_discount'] : so luong giam gia
             * 4.  $product_list[$i]['discount_rate'] : % giam gia
             * 5.  $product_list[$i]['price'] : gia san pham
             * 5.  $product_list[$i]['unit_id'] : id cua dinh luong san pham
             * 6.  $product_list[$i]['note'] : ghi chu
             * 
             * Vi du 
             * [product_list] => Array
                    (
                    [1] => Array
                        (
                            [quantity] => 2
                            [price] => 25,000
                            [note] => 
                            [quantity_discount] => 1
                            [discount_rate] = 10
                            [unit_id] => 104
                            [price_id] => 2354
                            [product_id] => HH1
                            [bar_id] => 1
                        )
        
                    [2] => Array
                        (
                            [quantity] => 2
                            [price] => 25,000
                            [note] => 
                            [quantity_discount] => 0
                            [discount_rate] => 10
                            [unit_id] => 104
                            [price_id] => 2354
                            [product_id] => NH3
                            [bar_id] => 1
                        )
                   ) 
                   
                   
            ---- CODE TEST 
             
                $table_info = array();
                $table_info['reservation_traveller_id'] = 1;
                $table_info['arrival_date'] = "16/04/2016";
                $table_info['arrival_time_in_hour'] = "10";
                $table_info['arrival_time_in_minute'] = "10";
                $table_info['departure_date'] = "17/04/2016";
                $table_info['arrival_time_out_hour'] = "10";
                $table_info['arrival_time_out_minute'] = "20";
                $table_info['note'] = "asdasd";
                $table_info['table_id'] = 148;
                $table_info['bar_id'] = 1;
                $table_info['customer_name'] = "abcd";
                $table_info['num_people'] = 3;
                $table_info['order_person'] = "asdasd";
                
                
                $product_list = array();
                
                $product_list = Array(
                            '1' => Array
                                (
                                    'quantity' => 2,
                                    'price' => '25,000',
                                    'note' => 'note1',
                                    'quantity_discount' => 1,
                                    'discount_rate' => 10,
                                    'unit_id' => 104,
                                    'price_id' => 2354,
                                    'product_id' => 'HH1',
                                    'bar_id' => 1
                                ), '2' => Array
                                (
                                    'quantity' => 2,
                                    'price' => '25,000',
                                    'note' => 'note2',
                                    'quantity_discount' => 0,
                                    'discount_rate' => 10,
                                    'unit_id' => 104,
                                    'price_id' => 2359,
                                    'product_id' => 'BI',
                                    'bar_id' => 1
                                )
                           );
                $this->booked_table("booked",$table_info,$product_list);          
            **/
                 require_once 'packages/hotel/packages/restaurant/includes/table.php';
                    
    			$status = 'BOOKED'; 
    			if(!$table_info['table_id']){ 
    				return false;	
    			}
                $discount_after_tax = DB::fetch('select discount_after_tax from bar where id ='.$table_info['bar_id'],'discount_after_tax');
    			$array = array();
    			$array += array(	
    					'reservation_room_id'=>0,
                        'reservation_traveller_id'=>0,					
    					'arrival_time'=>$table_info['arrival_time'],
    					'departure_time'=>$table_info['departure_time'],
    					'status'=>$status,
    					'full_rate'=>(isset($table_info['full_rate']) and $table_info['full_rate']!='')?$table_info['full_rate']:0,
    					'full_charge'=>(isset($table_info['full_charge']) and $table_info['full_charge']!='')?$table_info['full_charge']:0,
    					'note'=>isset($table_info['note'])?$table_info['note']:'', 
    					'customer_id'=>isset($table_info['customer_id'])?$table_info['customer_id']:'',
    					'agent_name'=>(!empty($table_info['customer_id']))?DB::fetch('select id,name from customer where id = '.$table_info['customer_id'].'','name'):'', 
    					'receiver_name'=>isset($table_info['receiver_name'])?$table_info['receiver_name']:"",
    					'tax_rate'=>isset($table_info['tax_rate'])?$table_info['tax_rate']:0, 
    					'foc'=>isset($table_info['foc'])?$table_info['foc']:0, 
    					'bar_fee_rate'=>isset($table_info['bar_fee_rate'])?$table_info['bar_fee_rate']:0,
    					'user_id'=>Session::get('user_id'),	
    					'lastest_edited_user_id'=>Session::get('user_id'),	
    					'lastest_edited_time'=>time(),	
                        'discount_after_tax'=>$discount_after_tax,	
    					'discount_percent'=>isset($table_info['discount_percent'])?$table_info['discount_percent']:0,
    					'discount'=>isset($table_info['discount'])?System::calculate_number($table_info['discount']):0,
    					'banquet_order_type'=>isset($table_info['banquet_order_type'])?$table_info['banquet_order_type']:'',
    			        'mice_reservation_id'=>isset($table_info['mice_reservation_id'])?$table_info['mice_reservation_id']:''
                );	
                
                if($table_info['table_id'])
                { // Check conflix
                    $bar_reservation_id = 0;
                    //$table_list_temp = explode(",",$table_list);
                    $table_array = array();
                    $table_array[$table_info['table_id']]['table_id'] =  $table_info['table_id'];
                    $conflix = Table::check_table_conflict($array['arrival_time'],$array['departure_time'],$table_array);
                    if($conflix[$table_info['table_id']]!=false)
                    {
                    	return false;
                    }
                    else if($type!='')
                    {
                        $bar_reservation_id = DB::insert('bar_reservation',$array+array('time'=>time(),'bar_id'=>$table_info['bar_id'],'portal_id'=>PORTAL_ID,'check_edit'=>'1'));
                        //start: KID sua de ma hoa don tang dan theo portal
                        $sql = 'SELECT max(TO_NUMBER(REPLACE(bar_reservation.code,\'-\',\'\'))) as location FROM bar_reservation WHERE bar_reservation.portal_id=\''.PORTAL_ID.'\'';
                        $location = DB::fetch($sql);
                        $location['location'] = (int)(substr($location['location'],4)) + 1;
                        $location['location'] = str_pad($location['location'], 7, "0", STR_PAD_LEFT);
                        $code = date('Y').'-'.($location['location']);
                        $table_id = '';
                        DB::update('bar_reservation',array('code'=>$code),'id='.$bar_reservation_id);
                        $table = $table_info['table_id'];
                        foreach($table_array as $key=>$tbl)
                        {
                            $tbl['bar_reservation_id'] =$bar_reservation_id;
                            unset($tbl['name']);
                            $tbl['num_people'] = $table_info['num_people'];
                            $tbl['order_person'] = $table_info['order_person']; 
                            DB::insert('bar_reservation_table',$tbl);		
                            $table_id = $tbl['table_id'];
                        }
                    }
                }
                if(isset($product_list) && $type!='')
                {				
                    /** get list setmenu **/
                    $setmenu_product = DB::fetch_all('select 
                                                    bar_set_menu_product.id as id,
                                                    bar_set_menu.code,
                                                    bar_set_menu_product.product_id,
                                                    bar_set_menu_product.quantity,
                                                    bar_set_menu_product.bar_set_menu_id,
                                                    product.name_'.Portal::language().' as produc_name
                                                from 
                                                    bar_set_menu_product  
                                                    inner join bar_set_menu on bar_set_menu.id=bar_set_menu_product.bar_set_menu_id
                                                    inner join product on product.id=bar_set_menu_product.product_id
                                                where 
                                                    bar_set_menu.portal_id=\''.PORTAL_ID.'\'
                                                ');
                    $setmenu = array();
                    foreach($setmenu_product as $k_set=>$v_set)
                    {
                        if(!isset($setmenu[$v_set['code']]))
                        {
                            $setmenu[$v_set['code']]['code'] = $v_set['code'];
                            $setmenu[$v_set['code']]['bar_set_menu_id'] = $v_set['bar_set_menu_id'];
                            $setmenu[$v_set['code']]['child'] = array();
                        }
                        $setmenu[$v_set['code']]['child'][$v_set['product_id']]['product_id'] = $v_set['product_id'];
                        $setmenu[$v_set['code']]['child'][$v_set['product_id']]['produc_name'] = $v_set['produc_name'];
                    }
                    /** end get list setmenu **/
    				$bar_reservation_products = array();
    				$bar_reservation_products = $product_list;
                    
                    $total_amount = 0;
                    $total_payment = 0;
                    $total_service = 0;
                    $total_tax = 0;
                    $stt= 0;
                    foreach($bar_reservation_products as $id =>$value)
                    {
                        $stt++;
                        $data = array();
                        $data['bar_reservation_id'] = $bar_reservation_id;
                        $data['product_id'] = $value['product_id'];
                        
                        $sql = "SELECT id,name_1 FROM product WHERE id='".$value['product_id']."'";
                        $name = DB::fetch($sql);
                        $data['name'] = $name['name_1'];
                        $data['quantity'] = $value['quantity'];
                        $data['printed'] = 0;
                        $data['quantity_discount'] = $value['quantity_discount'];
                        $data['discount_rate'] = $value['discount_rate'];
                        $data['price'] =  $value['price'];
                        $data['unit_id'] = $value['unit_id'];
                        $data['product_id'] = $value['product_id'];
                        $data['price_id'] = $value['price_id'];
                        $data['note'] = $value['note'];
                        $data['remain'] =0;   
                        $data['quantity_cancel'] = 0; 
                        $data['discount_category'] =0; 
                        $data['bar_id'] = $table_info['bar_id'];
                        $data['stt'] = $stt;
                        $data['add_charge'] = 0;
                        $bar_reservation_product_id = DB::insert('bar_reservation_product',$data);
                        
                        /**
                            set-menu
                            check setmenu_id
                            get list product in set
                            import product in detail.
                        **/
                        if(isset($setmenu[$data['product_id']]))
                        {
                            $data_set = array();
                            $data_set['bar_reservation_id'] = $bar_reservation_id;
                            $data_set['quantity'] = $value['quantity'];
                            $data_set['unit_id'] = $value['unit_id'];
                            $data_set['bar_set_menu_id'] = $setmenu[$data['product_id']]['bar_set_menu_id'];
                            $data_set['price_id'] = $value['price_id'];
                            $data_set['bar_id'] = $table_info['bar_id'];
                            $data['bar_set_menu_id'] = $setmenu[$data['product_id']]['bar_set_menu_id'];
                            foreach($setmenu[$data['product_id']]['child'] as $k=>$v)
                            {
                                $data_set['product_id'] = $v['product_id'];
                                $data_set['name'] = $v['produc_name'];
                                DB::insert('bar_reservation_product',$data_set);
                                
                                $data['product_id'] = $v['product_id'];
                                $data['name'] = $v['produc_name'];
                                $data['price'] = 0;
                                DB::insert('bar_reservation_set_product',$data);
                            }
                            
                        }
                        /** end setmenu **/
                        $total_amount += ($value['quantity']-$value['quantity_discount'])*(100-$value['discount_rate'])*$value['price']/100;
                    }
                    if($discount_after_tax==1)
                    {
                        $param =100+$table_info['tax_rate'] + $table_info['bar_fee_rate'];
                        if($table_info['full_rate']==1)
                        {
                            
                            
                        }
                        else if($table_info['full_charge']==1){
                            
                            $total_amount = $total_amount*(1+$table_info['tax_rate']/100);
                        }
                        else
                        {
                            $total_service = $total_amount*$table_info['bar_fee_rate']/100;
                            $total_tax = ($total_amount+$total_service)*$table_info['tax_rate']/100;
                            $total_amount = $total_amount + $total_service + $total_tax;
                        }
                        $total_amount = $total_amount*(100-$table_info['discount_percent'])/100 - $table_info['discount'];
                        $total_payment = $total_amount;
                        $total_amount=$total_amount*(100)/$param;
                    }
                    else
                    {
                        if($table_info['full_rate']==1)
                        {
                            $total_amount = ($total_amount*100/(100 + $table_info['tax_rate']))*100/(100 + $table_info['bar_fee_rate']);
                            
                        }
                        else if($table_info['full_charge']==1){
                            
                            $total_amount = $total_amount*100/(100 + $table_info['bar_fee_rate']);
                        }
                        else
                        {
                            
                        }
                        $total_amount = $total_amount*(100-$table_info['discount_percent'])/100 - $table_info['discount'];
                        $total_service = $total_amount*$table_info['bar_fee_rate']/100;
                        $total_tax = ($total_amount+$total_service)*$table_info['tax_rate']/100;
                        
                        $total_amount = $total_amount + $total_service + $total_tax;
                        $total_payment = $total_amount;
                        
                    }
                    /*
                    $total_amount = $total_amount*(100-$table_info['discount_percent'])/100 - $table_info['discount'];
                    
                    $param = 0;
                    if($table_info['full_rate']==1)
                    {
                        $param =100+$table_info['tax_rate'] + $table_info['bar_fee_rate'];
                        $total_payment = $total_amount;
                        $total_amount=$total_amount*(100)/$param;
                        
                    }
                    else if($table_info['full_charge']==1){
                        $param =  (100+$table_info['bar_fee_rate'])/100;
                        $total_payment = $total_amount*(1+$table_info['tax_rate']/100);
                        $total_amount = $total_amount/$param;
                    }
                    else{
                        $total_service = $total_amount*$table_info['bar_fee_rate']/100;
                        $total_tax = ($total_amount+$total_service)*$table_info['tax_rate']/100;
                        $total_payment = $total_amount + $total_service + $total_tax;
                    }
                    */
                    DB::update('bar_reservation',array('total_before_tax'=>floor($total_amount),'total'=>floor($total_payment)),'id='.$bar_reservation_id);
                }
                
                return true;
            }

            function get_list_other_category($bar_id){
                $language ="";
                if(Portal::language()==2){
                   $language = "_en";
                }
                $dp_code = DB::fetch('select department_id as code from bar where id='.$bar_id.' and portal_id=\''.PORTAL_ID.'\'','code');	
                $structure_id_drink = DB::fetch('select structure_id from product_category where code=\'DU\'','structure_id');
                $structure_id_service = DB::fetch('select structure_id from product_category where code=\'DVNH\'','structure_id');
                //$structure_other_id_service = DB::fetch('select structure_id from product_category where code=\'OT\'','structure_id');
                $sql = 'SELECT
                			product_category.id
                			,product_category.name'.$language.' as name
                			,product_category.structure_id
                		FROM
                			product_category
                			INNER JOIN product ON product_category.id = product.category_id
                			INNER JOIN product_price_list ON product_price_list.product_id = product.id
                			INNER JOIN unit ON unit.id = product.unit_id
                		WHERE 
                			1>0 
                			AND ((product_category.structure_id > '.$structure_id_drink.' AND  product_category.structure_id < '.IDStructure::next($structure_id_drink).')
                			OR (product_category.structure_id > '.$structure_id_service.' AND  product_category.structure_id < '.IDStructure::next($structure_id_service).'))
                			AND product_price_list.portal_id = \''.PORTAL_ID.'\'
                			AND product_price_list.department_code=\''.$dp_code.'\' 
                            AND (product_price_list.end_date is null OR product_price_list.end_date>=\''.Date_Time::to_orc_date(date('d/m/y')).'\')
                		ORDER BY product_category.position';	
                $categories = DB::fetch_all($sql);
                //System::debug($categories);
                return $categories;
            }
            
            
            function get_list_food_category($bar_id){
                $language ="";
                if(Portal::language()==2){
                   $language = "_en";
                }
                $dp_code = DB::fetch('select department_id as code from bar where id='.$bar_id.' and portal_id=\''.PORTAL_ID.'\'','code');	
                $structure_id_food = DB::fetch('select structure_id from product_category where code=\'DA\'','structure_id');
                $sql = 'SELECT
                			product_category.id
                			,product_category.name'.$language.' as name
                			,product_category.structure_id
                            ,product_category.code
                		FROM
                			product_category
                			INNER JOIN product ON product_category.id = product.category_id
                			INNER JOIN product_price_list ON product_price_list.product_id = product.id
                			INNER JOIN unit ON unit.id = product.unit_id
                		WHERE 
                			1>0 
                			AND (product_category.structure_id > '.$structure_id_food.' AND  product_category.structure_id < '.IDStructure::next($structure_id_food).')
                			AND product_price_list.portal_id = \''.PORTAL_ID.'\'
                			AND product_price_list.department_code=\''.$dp_code.'\' 
                            AND (product_price_list.end_date is null OR product_price_list.end_date>=\''.Date_Time::to_orc_date(date('d/m/y')).'\')
                		ORDER BY product_category.position';	
                $categories = DB::fetch_all($sql);
                //System::Debug($categories);
                return $categories;
            }   
            
?>