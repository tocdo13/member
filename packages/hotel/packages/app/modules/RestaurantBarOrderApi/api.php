<?php
class api extends restful_api
{
    function __construct(){
		parent::__construct();
	}
    
    function add_order()
    {
        if($this->method == 'POST')
        {
    		if(Url::get('secretkey') and Url::get('secretkey') == '9a8fa234b2520e9bb4f59d8178545a62')
            {
                /** Set default value insert to Bar_reservation */
                $array = array(					
							'arrival_time' => time(),
							'departure_time' => time()+7200,
							'status'=> Url::get('status'), 
							'full_rate' => Url::get('full_rate'),
							'full_charge' => Url::get('full_charge'),
                            'discount_after_tax' => Url::get('discount_after_tax'),
							'tax_rate' => Url::get('tax_rate'), 
							'bar_fee_rate' => Url::get('bar_fee_rate'),
							'total' => Url::get('total'),
							'user_id' => Url::get('user_id'),	
							'lastest_edited_user_id' => Url::get('lastest_edited_user_id'),	
							'lastest_edited_time' => time(),	
							'total_before_tax' => Url::get('total_before_tax'),
							'discount_percent' => Url::get('discount_percent'),
							'discount' => Url::get('discount'),
                            'last_time' => time()
                            	
                );
                
                if(Url::get('status')=='CHECKIN')
                {	
					$array += array(
                            'time_in'=> time(),
                            'arrival_time'=> time(),
                            'time_out' => time()+7200,
                            'checked_in_user_id' => Url::get('user_id')
                    );
				}
                
                if(HOTEL_CURRENCY == 'VND')
                {
					$exchange_currency_id = 'USD';
				}else
                {
					$exchange_currency_id = 'VND';	
				}
				$array += array('exchange_rate'=>DB::fetch('SELECT exchange FROM currency WHERE id=\''.$exchange_currency_id.'\'','exchange'));
                $array += array(
                    'time' => time(),
                    'bar_id' => Url::get('bar_id'),
                    'portal_id' => Url::get('portal_id'),
                    'check_edit' => '1'
                );
                
                $table_list = array(
                    Url::get('table_id') => array(
                                                'id' => Url::get('table_id'),
                                                'table_id' => Url::get('table_id'),
                                                'name' => Url::get('table_name')
                    )
                );
                
                $order_list = Url::get('order_list');
                $arr = json_decode($order_list, true);
                //System::debug($arr);
                $items = array();
                $bar_set_menu = array();
                $i = 0;
                $stt = 0;
                foreach($arr as $key => $value)
                {
                    foreach($value as $k => $v)
                    {
                        $key_arr = $v['price_id'];
                        if(!isset($items[$key_arr]))
                        {
                            $items[$key_arr]['name'] = $arr[$key][$i]['name'];
                            $items[$key_arr]['quantity'] = $arr[$key][$i]['quantity'];
                            $items[$key_arr]['promotion'] = $arr[$key][$i]['promotion'];
                            $items[$key_arr]['percentage'] = $arr[$key][$i]['percentage'];
                            $items[$key_arr]['discount_category'] = $arr[$key][$i]['discount_category'];
                            $items[$key_arr]['price'] = $arr[$key][$i]['price'];
                            $items[$key_arr]['amount'] = $arr[$key][$i]['quantity']*$arr[$key][$i]['price'];
                            $items[$key_arr]['printed'] = $arr[$key][$i]['printed'];
                            $items[$key_arr]['note'] = $arr[$key][$i]['note'];
                            $items[$key_arr]['unit'] = $arr[$key][$i]['unit'];
                            $items[$key_arr]['unit_id'] = $arr[$key][$i]['unit_id'];
                            $items[$key_arr]['price_id'] = $arr[$key][$i]['price_id'];
                            $items[$key_arr]['remain'] = $arr[$key][$i]['remain'];
                            $items[$key_arr]['product_id'] = $arr[$key][$i]['product_id'];
                            $items[$key_arr]['quantity_cancel'] = $arr[$key][$i]['quantity_cancel'];
                            $items[$key_arr]['brp_id'] = $arr[$key][$i]['brp_id'];
                            $items[$key_arr]['bar_id'] = $arr[$key][$i]['bar_id'];
                            $items[$key_arr]['complete'] = $arr[$key][$i]['complete'];
                            $stt++;
                            $items[$key_arr]['stt'] = $stt;
                            $items[$key_arr]['bar_set_menu_id'] = $arr[$key][$i]['bar_set_menu_id'];
                        }
                        if($arr[$key][$i]['unit'] == 'set')
                        {
                            $arr_product = array();
                            $price_list_set_id = $arr[$key][$i]['price_id'];
                            $sql = "
                                SELECT 
                                    bar_set_menu_product.id || '_' || product_price_list.id as id,
                                    product_price_list.id as price_id,
                                    product_price_list.price as price,
                                    product.name_".Portal::language()." as product_name,
                                    product.id as product_id,
                                    bar_set_menu.id as bar_set_menu_id,
                                    bar_set_menu_product.quantity as bar_set_menu_product_quantity
                                FROM 
                                    bar_set_menu_product 
                                    INNER JOIN bar_set_menu ON bar_set_menu_product.bar_set_menu_id = bar_set_menu.id
                                    INNER JOIN product ON product.id = bar_set_menu_product.product_id
                                    INNER JOIN product_price_list ON product_price_list.product_id = bar_set_menu.code
                                WHERE 
                                    product_price_list.id=".$price_list_set_id;
                            $bar_set_menu_product = DB::fetch_all($sql);
                            foreach($bar_set_menu_product as $prd_set_k => $prd_set_val)
                            {                                     
                                $arr_product[$prd_set_k]['name'] = $prd_set_val['product_name'];
                                $arr_product[$prd_set_k]['quantity'] = $prd_set_val['bar_set_menu_product_quantity'];
                                $arr_product[$prd_set_k]['promotion'] = $arr[$key][$i]['promotion']*$prd_set_val['bar_set_menu_product_quantity'];
                                $arr_product[$prd_set_k]['percentage'] = $arr[$key][$i]['percentage'];
                                $arr_product[$prd_set_k]['discount_category'] = $arr[$key][$i]['discount_category'];
                                $arr_product[$prd_set_k]['price'] = 0;
                                $arr_product[$prd_set_k]['amount'] = number_format(($arr[$key][$i]['quantity']-$arr[$key][$i]['remain'])*$prd_set_val['price']*(100-$arr[$key][$i]['percentage'])/100,0,'.',',');
                                $arr_product[$prd_set_k]['printed'] = $arr[$key][$i]['printed'];
                                $arr_product[$prd_set_k]['note'] = $arr[$key][$i]['note'];
                                $arr_product[$prd_set_k]['unit'] = $arr[$key][$i]['unit'];
                                $arr_product[$prd_set_k]['unit_id'] = $arr[$key][$i]['unit_id'];
                                $arr_product[$prd_set_k]['price_id'] = $prd_set_k;
                                $arr_product[$prd_set_k]['remain'] = $arr[$key][$i]['remain']*$prd_set_val['bar_set_menu_product_quantity'];
                                $arr_product[$prd_set_k]['product_id'] = $prd_set_val['product_id'];
                                $arr_product[$prd_set_k]['quantity_cancel'] = $arr[$key][$i]['quantity_cancel']*$prd_set_val['bar_set_menu_product_quantity'];
                                $arr_product[$prd_set_k]['brsp_id'] = '';
                                $arr_product[$prd_set_k]['bar_id'] = $arr[$key][$i]['bar_id'];
                                $arr_product[$prd_set_k]['bar_set_menu_id'] = $prd_set_val['bar_set_menu_id'];
                                $arr_product[$prd_set_k]['stt'] = '';
                            } 
                            $items += $arr_product;
                            $bar_set_menu += $arr_product;   
                        }
                        $i++;                       
                    }                
                }
                $array['total_before_tax'] = $array['total']/(1+$array['tax_rate']/100)/(1+$array['bar_fee_rate']/100);
                
                
                require_once 'packages/hotel/packages/restaurant/includes/table.php';
                $conflix = Table::check_table_conflict($array['arrival_time'],$array['departure_time'],$table_list);
                if($conflix[Url::get('table_id')]!=false)
                {
					$this->response(200, "Conflit bàn, vui lòng kiểm tra lại, xin cảm ơn!");
				}else
                {
                    $bar_reservation_id = DB::insert('bar_reservation',$array);
                    $sql = 'SELECT max(TO_NUMBER(REPLACE(bar_reservation.code,\'-\',\'\'))) as location FROM bar_reservation WHERE bar_reservation.portal_id=\''.Url::get('portal_id').'\'';
					$location = DB::fetch($sql);
            		$location['location'] = (int)(substr($location['location'],4)) + 1;
                    $location['location'] = str_pad($location['location'], 7, "0", STR_PAD_LEFT);
                    $code = date('Y').'-'.($location['location']);
					DB::update('bar_reservation',array('code'=>$code),'id='.$bar_reservation_id);
                    
                    $table = array(
                                'bar_reservation_id' => $bar_reservation_id,
                                'table_id' => Url::get('table_id')
                    );
					DB::insert('bar_reservation_table',$table);
                    
                    $log_product = '';
                    if(!empty($items))
                    {
						foreach($items as $id =>$value)
                        {
							$data['bar_reservation_id'] = $bar_reservation_id;
							$data['product_id'] = $value['product_id'];
							$data['quantity'] = $value['quantity'];
							$data['quantity_discount'] = $value['promotion'];
							$data['discount_rate'] = $value['percentage'];
							$data['printed'] = $value['printed'];
							$data['price'] =  $value['price'];
							$data['price_id'] = $value['price_id'];
							$data['unit_id'] = $value['unit_id'];
							$data['remain'] = 0;
							$data['note'] = $value['note'];
							$data['name'] = $value['name'];
							$data['quantity_cancel'] = $value['quantity_cancel']; 
							$data['discount_category'] = $value['discount_category']; 
							$data['bar_id'] = $value['bar_id'];
                            $data['stt'] = "";
                            $data['bar_set_menu_id'] = $value['bar_set_menu_id']; 
							$data['product_price'] = $value['price'];
                            $data['add_charge'] = 0;
                            if(isset($value['stt']))
                            {
                                $data['stt'] = $value['stt'];
                            }
                            
							$bar_reservation_product_id = DB::insert('bar_reservation_product',$data);
						}
                        
                        if(!empty($bar_set_menu))
                        {
                            foreach($bar_set_menu as $id =>$value)
                            {
                                $data['bar_reservation_id'] = $bar_reservation_id;
                                $data['product_id'] = $value['product_id'];
                                $data['quantity'] = System::calculate_number($value['quantity']);
                                $data['quantity_discount'] = $value['promotion'];
                                $data['discount_rate'] = $value['percentage'];
                                $data['printed'] = $value['printed'];
                                $data['price'] =  System::calculate_number($value['price']);
                                $data['price_id'] = $value['price_id'];
                                $data['unit_id'] = $value['unit_id'];
                                $data['name'] = $value['name'];
                                $data['note'] = $value['note'];
                                $data['remain'] =($value['remain']=='')?0:$value['remain'];   
                                $data['quantity_cancel'] = $value['quantity_cancel']; 
                                $data['discount_category'] = $value['discount_category']; 
                                $data['bar_id'] = $value['bar_id'];
                                $data['stt'] = $value['stt'];
                                $data['add_charge'] = 0;
                                $data['bar_set_menu_id'] = $value['bar_set_menu_id'];
                                //$prd_id = $bar_reservation_product_id;
                                
                                DB::insert('bar_reservation_set_product',$data);	
                            } 
						}
                    }
                    
                    $this->response(200, "TRUE");
                }
            }else
            {
                $this->response(500, "FAILED"); // AUTH
            } 
        }
    }
    
    function edit_order()
    {
        if($this->method == 'POST')
        {
    		if(Url::get('secretkey') and Url::get('secretkey') == '9a8fa234b2520e9bb4f59d8178545a62' and Url::get('id'))
            {
                if(DB::exists('SELECT * FROM bar_reservation WHERE id=\''.Url::get('id').'\' '))
                {
                    /** Set default value update to Bar_reservation */
                    if(Url::get('type') == 'UPDATE_CHECKIN')
                    {
                        
                        $array = array(					 
        							'total' => Url::get('total'),
        							'lastest_edited_user_id' => Url::get('lastest_edited_user_id'),	
        							'lastest_edited_time' => time(),	
        							'total_before_tax' => Url::get('total_before_tax'),
                                    'last_time' => time()
                                    	
                        );
                    }else
                    {
                        $array = array(					
        							'status'=> Url::get('status'), 
        							'total' => Url::get('total'),
        							'lastest_edited_user_id' => Url::get('lastest_edited_user_id'),	
        							'lastest_edited_time' => time(),	
        							'total_before_tax' => Url::get('total_before_tax'),
                                    'last_time' => time()
                                    	
                        );
                        if(Url::get('status')=='CHECKIN')
                        {	
        					$array += array(
                                    'time_in'=> time(),
                                    'arrival_time'=> time(),
                                    'time_out' => time()+7200,
                                    'checked_in_user_id' => Url::get('user_id')
                            );
        				}
                    }
                    
                    $order_list = Url::get('order_list');
                    $arr = json_decode($order_list, true);
                    //System::debug($arr);
                    $items = array();
                    $bar_set_menu = array();
                    $i = 0;
                    $stt = 0;
                    foreach($arr as $key => $value)
                    {
                        foreach($value as $k => $v)
                        {
                            $key_arr = $v['price_id'];
                            if(!isset($items[$key_arr]))
                            {
                                $items[$key_arr]['id'] = $arr[$key][$i]['id'];
                                $items[$key_arr]['name'] = $arr[$key][$i]['name'];
                                $items[$key_arr]['quantity'] = $arr[$key][$i]['quantity'];
                                $items[$key_arr]['promotion'] = $arr[$key][$i]['promotion'];
                                $items[$key_arr]['percentage'] = $arr[$key][$i]['percentage'];
                                $items[$key_arr]['discount_category'] = $arr[$key][$i]['discount_category'];
                                $items[$key_arr]['price'] = $arr[$key][$i]['price'];
                                $items[$key_arr]['amount'] = $arr[$key][$i]['quantity']*$arr[$key][$i]['price'];
                                $items[$key_arr]['printed'] = $arr[$key][$i]['printed'];
                                $items[$key_arr]['note'] = $arr[$key][$i]['note'];
                                $items[$key_arr]['unit'] = $arr[$key][$i]['unit'];
                                $items[$key_arr]['unit_id'] = $arr[$key][$i]['unit_id'];
                                $items[$key_arr]['price_id'] = $arr[$key][$i]['price_id'];
                                $items[$key_arr]['remain'] = $arr[$key][$i]['remain'];
                                $items[$key_arr]['product_id'] = $arr[$key][$i]['product_id'];
                                $items[$key_arr]['quantity_cancel'] = $arr[$key][$i]['quantity_cancel'];
                                $items[$key_arr]['brp_id'] = $arr[$key][$i]['brp_id'];
                                $items[$key_arr]['bar_id'] = $arr[$key][$i]['bar_id'];
                                $items[$key_arr]['complete'] = $arr[$key][$i]['complete'];
                                $items[$key_arr]['stt'] = $arr[$key][$i]['stt'];
                                $items[$key_arr]['bar_set_menu_id'] = $arr[$key][$i]['bar_set_menu_id'];
                            }
                            if($arr[$key][$i]['unit'] == 'set' && $arr[$key][$i]['brp_id'] == '')
                            {
                                $arr_product = array();
                                $price_list_set_id = $arr[$key][$i]['price_id'];
                                $sql = "
                                    SELECT 
                                        bar_set_menu_product.id || '_' || product_price_list.id as id,
                                        product_price_list.id as price_id,
                                        product_price_list.price as price,
                                        product.name_".Portal::language()." as product_name,
                                        product.id as product_id,
                                        bar_set_menu.id as bar_set_menu_id,
                                        bar_set_menu_product.quantity as bar_set_menu_product_quantity
                                    FROM 
                                        bar_set_menu_product 
                                        INNER JOIN bar_set_menu ON bar_set_menu_product.bar_set_menu_id = bar_set_menu.id
                                        INNER JOIN product ON product.id = bar_set_menu_product.product_id
                                        INNER JOIN product_price_list ON product_price_list.product_id = bar_set_menu.code
                                    WHERE 
                                        product_price_list.id=".$price_list_set_id;
                                $bar_set_menu_product = DB::fetch_all($sql);
                                foreach($bar_set_menu_product as $prd_set_k => $prd_set_val)
                                {                                     
                                    $arr_product[$prd_set_k]['name'] = $prd_set_val['product_name'];
                                    $arr_product[$prd_set_k]['quantity'] = $prd_set_val['bar_set_menu_product_quantity'];
                                    $arr_product[$prd_set_k]['promotion'] = $arr[$key][$i]['promotion']*$prd_set_val['bar_set_menu_product_quantity'];
                                    $arr_product[$prd_set_k]['percentage'] = $arr[$key][$i]['percentage'];
                                    $arr_product[$prd_set_k]['discount_category'] = $arr[$key][$i]['discount_category'];
                                    $arr_product[$prd_set_k]['price'] = 0;
                                    $arr_product[$prd_set_k]['amount'] = number_format(($arr[$key][$i]['quantity']-$arr[$key][$i]['remain'])*$prd_set_val['price']*(100-$arr[$key][$i]['percentage'])/100,0,'.',',');
                                    $arr_product[$prd_set_k]['printed'] = $arr[$key][$i]['printed'];
                                    $arr_product[$prd_set_k]['note'] = $arr[$key][$i]['note'];
                                    $arr_product[$prd_set_k]['unit'] = $arr[$key][$i]['unit'];
                                    $arr_product[$prd_set_k]['unit_id'] = $arr[$key][$i]['unit_id'];
                                    $arr_product[$prd_set_k]['price_id'] = $prd_set_k;
                                    $arr_product[$prd_set_k]['remain'] = $arr[$key][$i]['remain']*$prd_set_val['bar_set_menu_product_quantity'];
                                    $arr_product[$prd_set_k]['product_id'] = $prd_set_val['product_id'];
                                    $arr_product[$prd_set_k]['quantity_cancel'] = $arr[$key][$i]['quantity_cancel']*$prd_set_val['bar_set_menu_product_quantity'];
                                    $arr_product[$prd_set_k]['brp_id'] = '';
                                    $arr_product[$prd_set_k]['brsp_id'] = '';
                                    $arr_product[$prd_set_k]['bar_id'] = $arr[$key][$i]['bar_id'];
                                    $arr_product[$prd_set_k]['bar_set_menu_id'] = $prd_set_val['bar_set_menu_id'];
                                    $arr_product[$prd_set_k]['stt'] = '';
                                } 
                                $items += $arr_product;
                                $bar_set_menu += $arr_product;   
                            }
                            $i++;                    
                        }                
                    }
                    $array['total_before_tax'] = Url::get('total')/(1+Url::get('tax_rate')/100)/(1+Url::get('bar_fee_rate')/100);
                    
                    $table_list = array(
                        Url::get('table_id_new') => array(
                                                    'id' => Url::get('table_id_new'),
                                                    'table_id' => Url::get('table_id_new'),
                                                    'name' => Url::get('table_name_new')
                        )
                    );
                    require_once 'packages/hotel/packages/restaurant/includes/table.php';
                    if(Url::get('table_id_new') != '')
                    {
                        $conflix = Table::check_table_conflict(Url::get('arrival_time'),Url::get('departure_time'),$table_list);
                        if($conflix[Url::get('table_id_new')] !=false)
                        {
        					$this->response(200, "Conflit bàn, vui lòng kiểm tra lại, xin cảm ơn!");
        				}else
                        {
                            DB::update('bar_reservation',$array, 'id='.Url::get('id'));
                            //System::debug($items);
                            if(!empty($items))
                            {
                                DB::update('bar_reservation_table', array('table_id'=>Url::get('table_id_new')), 'bar_reservation_id='.Url::get('id'));
            					foreach($items as $id =>$value)
                                {
            						$data['bar_reservation_id'] = Url::get('id');
            						$data['product_id'] = $value['product_id'];
            						$data['quantity'] = $value['quantity'];
            						$data['quantity_discount'] = $value['promotion'];
            						$data['discount_rate'] = $value['percentage'];
            						$data['printed'] = $value['printed'];
            						$data['price'] =  $value['price'];
            						$data['price_id'] = $value['price_id'];
            						$data['unit_id'] = $value['unit_id'];
            						$data['remain'] = $value['remain'];
            						$data['note'] = $value['note'];
            						$data['name'] = $value['name'];
            						$data['quantity_cancel'] = $value['quantity_cancel']; 
            						$data['discount_category'] = $value['discount_category']; 
            						$data['bar_id'] = $value['bar_id'];
                                    $data['stt'] = "";
                                    $data['bar_set_menu_id'] = $value['bar_set_menu_id'];
            						$data['product_price'] = $value['price'];
                                    $data['add_charge'] = 0;
                                    if(isset($value['stt']))
                                    {
                                        $data['stt'] = $value['stt'];
                                    }
                                    if($value['brp_id'] != '')
            						{
                                        $bar_reservation_product_id = DB::update('bar_reservation_product',$data, 'bar_reservation_id = '.Url::get('id').' AND id = '.$value['brp_id'].' ');    						  
            						}else
                                    {
                                        $bar_reservation_product_id = DB::insert('bar_reservation_product',$data);
                                    }
            					}
                                if(!empty($bar_set_menu))
                                {
                                    foreach($bar_set_menu as $id =>$value)
                                    {
                                        $data['bar_reservation_id'] = Url::get('id');
                                        $data['product_id'] = $value['product_id'];
                                        $data['quantity'] = System::calculate_number($value['quantity']);
                                        $data['quantity_discount'] = $value['promotion'];
                                        $data['discount_rate'] = $value['percentage'];
                                        $data['printed'] = $value['printed'];
                                        $data['price'] =  System::calculate_number($value['price']);
                                        $data['price_id'] = $value['price_id'];
                                        $data['unit_id'] = $value['unit_id'];
                                        $data['name'] = $value['name'];
                                        $data['note'] = $value['note'];
                                        $data['remain'] =($value['remain']=='')?0:$value['remain'];   
                                        $data['quantity_cancel'] = $value['quantity_cancel']; 
                                        $data['discount_category'] = $value['discount_category']; 
                                        $data['bar_id'] = $value['bar_id'];
                                        $data['stt'] = $value['stt'];
                                        $data['add_charge'] = 0;
                                        $data['bar_set_menu_id'] = $value['bar_set_menu_id'];
                                        
                                        DB::insert('bar_reservation_set_product',$data);	
                                    } 
        						}
                            }
                            
                            $this->response(200, "TRUE");                             
                        }
                    }else
                    {
                        DB::update('bar_reservation',$array, 'id='.Url::get('id'));
                        //System::debug($items);
                        if(!empty($items))
                        {
        					foreach($items as $id =>$value)
                            {
        						$data['bar_reservation_id'] = Url::get('id');
        						$data['product_id'] = $value['product_id'];
        						$data['quantity'] = $value['quantity'];
        						$data['quantity_discount'] = $value['promotion'];
        						$data['discount_rate'] = $value['percentage'];
        						$data['printed'] = $value['printed'];
        						$data['price'] =  $value['price'];
        						$data['price_id'] = $value['price_id'];
        						$data['unit_id'] = $value['unit_id'];
        						$data['remain'] = $value['remain'];
        						$data['note'] = $value['note'];
        						$data['name'] = $value['name'];
        						$data['quantity_cancel'] = $value['quantity_cancel']; 
        						$data['discount_category'] = $value['discount_category']; 
        						$data['bar_id'] = $value['bar_id'];
                                $data['stt'] = "";
                                $data['bar_set_menu_id'] = $value['bar_set_menu_id'];
        						$data['product_price'] = $value['price'];
                                $data['add_charge'] = 0;
                                if(isset($value['stt']))
                                {
                                    $data['stt'] = $value['stt'];
                                }
                                if($value['brp_id'] != '')
        						{
                                    $bar_reservation_product_id = DB::update('bar_reservation_product',$data, 'bar_reservation_id = '.Url::get('id').' AND id = '.$value['brp_id'].' ');    						  
        						}else
                                {
                                    $bar_reservation_product_id = DB::insert('bar_reservation_product',$data);
                                }
        					}
                            if(!empty($bar_set_menu))
                            {
                                foreach($bar_set_menu as $id =>$value)
                                {
                                    $data['bar_reservation_id'] = Url::get('id');
                                    $data['product_id'] = $value['product_id'];
                                    $data['quantity'] = System::calculate_number($value['quantity']);
                                    $data['quantity_discount'] = $value['promotion'];
                                    $data['discount_rate'] = $value['percentage'];
                                    $data['printed'] = $value['printed'];
                                    $data['price'] =  System::calculate_number($value['price']);
                                    $data['price_id'] = $value['price_id'];
                                    $data['unit_id'] = $value['unit_id'];
                                    $data['name'] = $value['name'];
                                    $data['note'] = $value['note'];
                                    $data['remain'] =($value['remain']=='')?0:$value['remain'];   
                                    $data['quantity_cancel'] = $value['quantity_cancel']; 
                                    $data['discount_category'] = $value['discount_category']; 
                                    $data['bar_id'] = $value['bar_id'];
                                    $data['stt'] = $value['stt'];
                                    $data['add_charge'] = 0;
                                    $data['bar_set_menu_id'] = $value['bar_set_menu_id'];
                                    
                                    DB::insert('bar_reservation_set_product',$data);	
                                } 
    						}
                        }
                        
                        $this->response(200, "TRUE");                        
                    }
                    
                }else
                {
                    $this->response(500, "FAILED");
                }
            }else
            {
                $this->response(500, "FAILED"); // AUTH
            } 
        }
    }
}   
$api = new api();
?>