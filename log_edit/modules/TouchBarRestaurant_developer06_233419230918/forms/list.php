<?php
class TouchBarRestaurantForm extends Form{
	function TouchBarRestaurantForm(){
		Form::Form('TouchBarRestaurantForm');
		$this->link_css(Portal::template('hotel').'/css/room.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_css('packages/core/includes/js/jquery/keyboard/style.css');
		//$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/keyboard/keyboard.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.jcarousel.min.js');
		$this->link_js('packages/hotel/packages/restaurant/includes/js/update_price_new.js');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.cookie.js');
		$this->link_js('packages/core/includes/js/jquery/paging/easypaginate.js');
		$this->link_js('packages/hotel/packages/restaurant/modules/TouchBarRestaurant/bar_reservation.js');
		$this->link_js('packages/hotel/packages/restaurant/modules/TouchBarRestaurant/jquery_format_price_manh.js');
        $this->link_js('packages/core/includes/js/jquery/window/jquery.window.js');
		//Dung cho folio
		$this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
        $this->link_js('packages/hotel/packages/reception/modules/includes/common01.js');                
        $this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");	
			  
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		//Dung cho MICE
        $this->link_js('packages/hotel/packages/mice/includes/js/mice_function.js');
        //End MICE
		$this->link_js('cache/data/'.str_replace('#','',PORTAL_ID).'/'.Session::get('dp_code').'_'.str_replace('#','',PORTAL_ID).'.js?v='.time());
		$this->add('arrival_date',new DateType(true,'invalid_arrival_time'));
		$this->full_rate = 0;
		$this->full_charge= 0;		
	}
	function on_submit()
    {

        //System::debug($_REQUEST); exit();
/*-----------send mail---------------*/
        if(file_exists('cache/portal/default/config/config_email.php'))
        {
            require_once ('cache/portal/default/config/config_email.php');
        }        
        if(Url::get('cmd')=='edit' && BAR_INVOICE==1)
        {
            $array_bar_before = DB::fetch('SELECT id,code,total_before_tax,total FROM bar_reservation WHERE id='.Url::get('id'));
            $array_bar_product_before = DB::fetch_all('SELECT id,product_id,price,price_id,quantity,product_price,bar_id FROM bar_reservation_product WHERE bar_reservation_id='.Url::get('id'));    
        }
/*-----------send mail---------------*/        
       // TouchBarRestaurant::print_to_kitchen('kitchen');
	   // TouchBarRestaurant::print_to_kitchen('bar');
		if(Url::get('act') != '' || Url::get('acction') == 1 || Url::get('cancel'))
        {
		    require_once 'packages/hotel/packages/restaurant/includes/table.php';
            /** Check phong da CO khong cho chuyen ve **/
            if(Url::get('reservation_room_id') and DB::exists('select id from bar_reservation where reservation_room_id!='.Url::get('reservation_room_id')) and DB::exists('select id from reservation_room where status=\'CHECKOUT\' and id='.Url::get('reservation_room_id'))){
                $this->error('items_id','Phòng đã CO, không được phép trả về phòng');
		        return;	
            }
            /** end check phong CO **/
			if($this->check())
            {
                
				if(Url::get('act')=='checkin' and Date_Time::to_time(Url::get('arrival_time'))>Date_Time::to_time(date('d/m/Y')))
				{
				}
                else
                {
                    /** Thanh s?a ph?n chuy?n v? ph�ng, t?o bill r?i th� kh�ng du?c chuy?n v? ph�ng kh�c **/
                    $amount_pay_with_room = 0;
                    $amount_part_payment=0;
                    $rr_id = Url::get('reservation_room_id') ? Url::get('reservation_room_id') : 0;
                    
                    if(Url::get('id'))
                    {
                        $amount_part_payment = DB::fetch('select sum(amount) as amount from payment where bill_id='.Url::get('id').' and type=\'BAR\' ','amount');
                        //$total = DB::fetch('select total from bar_reservation where id='.Url::get('id'),'total');    
                        
                        $total = Url::get('total_payment')?System::calculate_number(Url::get('total_payment')):0;
                        if(Url::get('pay_with_room')) 
                            $amount_pay_with_room = $total - $amount_part_payment;
                            
                        
                        $old_rr_id = DB::fetch('SELECT id,reservation_room_id, reservation_traveller_id FROM bar_reservation WHERE id='.Url::get('id'));
                        
                        if($rr_id!=$old_rr_id['reservation_room_id'] && $old_rr_id['reservation_room_id']!=0)
                        {
                            $result = DB::fetch("SELECT id FROM traveller_folio WHERE type = 'BAR' and invoice_id=".Url::get('id'));
                            if(!empty($result))
                            {
                                $this->error('has_folio','bill_has_folio');
                                $_REQUEST['reservation_room_id'] = $old_rr_id['reservation_room_id'];
                                $_REQUEST['reservation_traveller_id'] = $old_rr_id['reservation_traveller_id'];
    							return;
                            }
                        }
                    }
                                    
                   
                    
                      
                    /** End - Thanh s?a ph?n chuy?n v? ph�ng, t?o bill r?i th� kh�ng du?c chuy?n v? ph�ng kh�c **/
                    
                    $merge = false;
					$log_product = '';
					$log_table = '';
					$check_out = false;
					$ids = "0";
					$status = (Url::get('act')=='checkin')?'CHECKIN':((Url::get('act')=='booked')?'BOOKED':(Url::get('status')?Url::get('status'):'')); 
					if(Url::get('act')=='submit_invoice')
                    {
						$status = 'CHECKIN';	
					}
					if(Url::get('product_list'))
                    {
                        //System::debug(Url::get('product_list'));exit(11);
						$bar_reservation_products = array();
                        $bar_set_menu = array();
						$bar_reservation_products = Url::get('product_list');
                        $set_menu_quantity_list = Url::get('set_menu_list');                   
                        $stt= 0;
                        $sql = "SELECT * FROM bar WHERE id=".Url::get('bar_id');
                        $result = DB::fetch($sql);
                        $cond2 = ' AND product_price_list.department_code = \''.$result['department_id'].'\'';
                        $bar_reservation_id = isset($_GET['id'])?$_GET['id']:'';

                        foreach($bar_reservation_products as $k => $p)
                        {                        
                            if(isset($p['brsp_id']))
                            {
                                $temp_arr = explode('-',$p['brsp_id']);
                                $bar_reservation_products[$k]['brsp_id'] = $temp_arr[0];
                                if(strpos("DOUTSIDE|FOUTSIDE|SOUTSIDE",$p['product_id'])!==FALSE){
                                    $extra = explode('-',$k);
                                    if(count($extra)>=2){
                                    unset($bar_reservation_products[$k]);
                                    $bar_reservation_products+=array($extra[0]=>$p);
                                    $ids .= ($ids=='')?$p['price_id']:(','.$p['price_id']);
                                    continue;
                                    }
                                }
                            }
                            if(isset($p['brp_id']))
                            {
                                $temp_arr = explode('-',$p['brp_id']);
                                $bar_reservation_products[$k]['brp_id'] = $temp_arr[0];
                                if(strpos("DOUTSIDE|FOUTSIDE|SOUTSIDE",$p['product_id'])!==FALSE){
                                    $extra = explode('-',$k);
                                    if(count($extra)>=2){
                                    unset($bar_reservation_products[$k]);
                                    $bar_reservation_products+=array($extra[0]=>$p);
                                    $ids .= ($ids=='')?$p['price_id']:(','.$p['price_id']);
                                    continue;
                                    }
                                }
                            }
						    if($p['unit']=='set')
                            {						      
						      $arr_product = array();
						      $arr_temp = explode('-',$k);
						      $bar_set_menu_id = $arr_temp[0];
                              
                                $sql = "SELECT bar_set_menu.id,
                                bar_set_menu.code,
                                bar_set_menu.department_code,
                                product_price_list.id as set_price_id 
                                FROM bar_set_menu INNER JOIN product_price_list ON bar_set_menu.code = product_price_list.product_id WHERE product_price_list.id = ".$bar_set_menu_id;
                                
                                $rs = DB::fetch($sql);
                              
                               //$sql = "SELECT 
//                                bar_set_menu_product.id as id,
//                                product_price_list.id as price_id,
//                                product_price_list.price as price,
//                                product.name_".Portal::language()." as product_name,
//                                product.id as product_id,
//                                bar_set_menu.id as bar_set_menu_id,
//                                bar_set_menu_product.quantity as bar_set_menu_product_quantity
//                                FROM 
//                                bar_set_menu_product 
//                                INNER JOIN bar_set_menu ON bar_set_menu_product.bar_set_menu_id = bar_set_menu.id
//                                INNER JOIN product ON product.id = bar_set_menu_product.product_id
//                                INNER JOIN product_price_list ON product_price_list.product_id = bar_set_menu.code
//                                WHERE 
//                                product_price_list.id=".$bar_set_menu_id;
                                $sql = "SELECT DISTINCT product_price_list.id || '_' || bar_set_menu.id as id,
                                                   product_price_list.id as product_price_id,
                                                   bar_set_menu_product.product_id,
                                                   product_price_list.price,
                                                   bar_set_menu.id as bar_set_menu_id,
                                                   product.name_1 as product_name,
                                                   --bar_reservation_set_product.quantity,
                                                   bar_set_menu_product.quantity as bar_set_menu_product_quantity,      
                                                   bar_set_menu_product.quantity as original_quantity,                                               
                                                   product.name_1 as product_name 
                                            FROM 
                                                bar_set_menu_product
                                                --INNER JOIN bar_reservation_set_product ON bar_reservation_set_product.product_id = bar_set_menu_product.product_id AND bar_reservation_set_product.bar_set_menu_id = bar_set_menu_product.bar_set_menu_id
                                                INNER JOIN product ON bar_set_menu_product.product_id = product.id
                                                INNER JOIN bar_set_menu ON bar_set_menu_product.bar_set_menu_id = bar_set_menu.id
                                                INNER JOIN product_price_list ON ( product_price_list.product_id = bar_set_menu_product.product_id AND product_price_list.department_code = bar_set_menu.department_code)
                                            WHERE bar_set_menu.id =".$rs['id'];
                                $bar_set_menu_product = DB::fetch_all($sql);        
                                foreach($bar_set_menu_product as $prd_set_k => $prd_set_val)
                                {                                     
                                    $arr_product[$prd_set_k]['name'] = $prd_set_val['product_name'];
                                    $arr_product[$prd_set_k]['quantity'] = $set_menu_quantity_list[$rs['set_price_id']][$prd_set_val['product_price_id']]['quantity'];
                                    $arr_product[$prd_set_k]['promotion'] = $p['promotion']*$prd_set_val['bar_set_menu_product_quantity'];
                                    $arr_product[$prd_set_k]['percentage'] = $p['percentage'];
                                    $arr_product[$prd_set_k]['discount_category'] = $p['discount_category'];
                                    $arr_product[$prd_set_k]['price'] = 0;
                                    $arr_product[$prd_set_k]['amount'] = number_format(($p['quantity']-$p['remain'])*$prd_set_val['price']*(100-$p['percentage'])/100,0,'.',',');
                                    $arr_product[$prd_set_k]['printed'] = $p['printed'];
                                    $arr_product[$prd_set_k]['note'] = $p['note'];
                                    $arr_product[$prd_set_k]['unit'] = $p['unit'];
                                    $arr_product[$prd_set_k]['unit_id'] = $p['unit_id'];
                                    $arr_product[$prd_set_k]['price_id'] = $prd_set_val['product_price_id'];
                                    $arr_product[$prd_set_k]['remain'] = $p['remain']*$prd_set_val['bar_set_menu_product_quantity'];
                                    $arr_product[$prd_set_k]['product_id'] = $prd_set_val['product_id'];
                                    $arr_product[$prd_set_k]['quantity_cancel'] = $p['quantity_cancel']*$prd_set_val['bar_set_menu_product_quantity'];
                                    //$arr_product[$prd_set_k]['chair_number']=$p['chair_number'];// trung add: lay mang chair_number luu vao database
                                    if(!empty($bar_reservation_id)){
                                        $sql = "
                                        SELECT bar_reservation_set_product.product_id as id, bar_reservation_set_product.id as bar_reservation_set_product_id 
                                        FROM 
                                        bar_reservation_set_product
                                        INNER JOIN bar_set_menu ON bar_reservation_set_product.bar_set_menu_id = bar_set_menu.id
                                        INNER JOIN product_price_list ON product_price_list.product_id =  bar_set_menu.code
                                        WHERE bar_reservation_set_product.bar_reservation_id=".$bar_reservation_id." AND product_price_list.id=".$bar_set_menu_id;
                                        
                                        $result = DB::fetch_all($sql);
                                        if(isset($result[$prd_set_val['product_id']])){
                                            $arr_product[$prd_set_k]['brsp_id'] = $result[$prd_set_val['product_id']]['bar_reservation_set_product_id'];
                                        }
                                        else
                                        $arr_product[$prd_set_k]['brsp_id'] = ''; 
                                    }
                                    else{
                                       $arr_product[$prd_set_k]['brsp_id'] = ''; 
                                    }
                                    $arr_product[$prd_set_k]['bar_id'] = $p['bar_id'];
                                    $arr_product[$prd_set_k]['bar_set_menu_id'] = $prd_set_val['bar_set_menu_id'];
                                    $stt++;
                                    $arr_product[$prd_set_k]['stt'] = $stt;
                                    $ids .= ($ids=='')?$prd_set_val['product_price_id']:(','.$prd_set_val['product_price_id']);
                                }
                                $bar_reservation_products+=$arr_product;
                                $bar_set_menu+=$arr_product;
                                //unset($bar_reservation_products[$k]);     
                                //continue;                          
						    }
                            else
                            {
                                $stt++;
                                $bar_reservation_products[$k]['stt'] = $stt;
                                $bar_reservation_products[$k]['bar_set_menu_id'] = '';
					            $ids .= ($ids=='')?$p['price_id']:(','.$p['price_id']);
                            }
						}
                        //exit();
					   $products_select = TouchBarRestaurantDB::get_product_select($ids);
					}/*else if($status !='BOOKED' && !Url::get('cancel')){//Url::get('act')!='booked'
						$this->error('items_id','you_did_not_select_food_or_drink_yet');
						return;
					}
                    */
                    
					if(!Url::get('table_list'))
                    {
						$this->error('items_id','you_did_not_select_table_yet');
						return;	
					}
					//System::debug($bar_reservation_products); exit();
					$array = array();
					if(Url::get('act')=='cancel')
                    {
						$status = 'CANCEL';	
					}
					if(Url::get('act')=='checkout')
                    {
						$status = 'CHECKOUT';
						$check_out = true;	
					}
					if($status=='CHECKOUT' and Url::get('act')=='save')
                    { //!User::is_admin() and
                        	
						if(Url::get('password') and User::can_admin(false,ANY_CATEGORY)){
							$user_id = Session::get('user_id');
							if(!$row=DB::fetch('select account.*,party.email,party.name_'.Portal::language().' as full_name from account inner join party on party.user_id=account.id where account.id=\''.$user_id.'\' and party.type=\'USER\' and password=\''.User::encode_password($_REQUEST['password']).'\' and account.is_active=1'))
							{
								$this->error('password','invalid_password');
								return;
							}
						}
                        else
                        {
							$this->error('password','invalid_password');
							return;
						}
					}
					$exchange_rate = 0;
					//echo Url::get('act'); exit();
					//echo Url::get('arrival_date'); exit();
                    $description = '';
                    if(Url::get('reservation_room_id'))
                    {
                        $reservation = DB::fetch('SELECT reservation_id From reservation_room Where id='.Url::get('reservation_room_id'));
                        $description.= 'reservation code: '.$reservation['reservation_id']."</ br>";
                        DB::update('reservation',array('last_time'=>time()),'id='.$reservation['reservation_id']);
                    }
                    //end giap.ln 
                    
					$array += array(	
							'reservation_room_id'=>$rr_id,
                            'total_payment_traveller'=>(Url::get('total_payment_traveller'))?System::calculate_number(Url::get('total_payment_traveller')):0,
							'room_id'=>(Url::get('payment_result')=='ROOM')?Url::get('room_id'):0,	
							'reservation_traveller_id'=>(Url::get('reservation_traveller_id'))?Url::get('reservation_traveller_id'):0,					
							'arrival_time'=>(Url::get('act')=='checkin')?time():Date_Time::to_time(Url::get('arrival_date'))+(Url::get('arrival_time_in_hour')*3600)+(Url::get('arrival_time_in_munite')*60),
							'departure_time'=>(Url::get('act')=='checkout')?time():Date_Time::to_time(Url::get('arrival_date'))+(Url::get('arrival_time_out_hour')*3600)+(Url::get('arrival_time_out_munite')*60),
							'status'=>$status, 
							'pay_with_room'=>(Url::get('pay_with_room') || Url::get('package_id'))?1:0,
							'full_rate'=>Url::get('input_full_rate')?Url::get('input_full_rate'):0,
							'full_charge'=>Url::get('input_full_charge')?Url::get('input_full_charge'):0,
                            'discount_after_tax'=>Url::get('input_discount_after_tax')?Url::get('input_discount_after_tax'):0,
							'note'=>Url::get('note')?Url::get('note'):'', 
							'num_table'=>Url::get('num_table')?Url::get('num_table'):1,
							'customer_id'=>Url::get('customer_id')?Url::get('customer_id'):'',
							'agent_name'=>Url::get('customer_id')?DB::fetch('select id,name from customer where id = '.Url::get('customer_id').'','name'):Url::get('customer_name'), 
							'receiver_name'=>Url::get('receiver_name'),
							'tax_rate'=>Url::get('tax_rate')?Url::get('tax_rate'):0, 
							'foc'=>Url::get('foc')?Url::get('foc'):0, 
							'bar_fee_rate'=>Url::get('service_charge')?Url::get('service_charge'):0,
							'deposit'=>Url::get('deposit')?System::calculate_number(Url::get('deposit')):0,
							'total'=>Url::get('total_payment')?System::calculate_number(Url::get('total_payment')):0,
							'user_id'=>Session::get('user_id'),	
							'lastest_edited_user_id'=>Session::get('user_id'),	
							'lastest_edited_time'=>time(),	
							'total_before_tax'=>Url::get('total_amount')?System::calculate_number(Url::get('total_amount')):0,
							'deposit_date'=>Url::get('deposit_date')?Date_Time::to_orc_date(Url::get('deposit_date')):(Url::get('deposit')?date('d/M/Y'):''),
							'payment_result'=>(Url::get('pay_with_room'))?'ROOM':'',
							'discount_percent'=>Url::get('discount_percent')?Url::get('discount_percent'):0,
							'discount'=>Url::get('discount')?System::calculate_number(Url::get('discount')):0,
							'banquet_order_type'=>Url::get('banquet_order_type')?Url::get('banquet_order_type'):'',
                            'member_code'=>Url::get('member_code')?Url::get('member_code'):'',
                            'member_level_id'=>Url::get('member_level_id')?Url::get('member_level_id'):'',
                            'create_member_date'=>Url::get('create_member_date')?Url::get('create_member_date'):'',
							'amount_pay_with_room' =>$amount_pay_with_room,
                            'package_id'=>Url::get('package_id')?Url::get('package_id'):'',
                            'total_payment_room'=>isset($_REQUEST['total_payment_room'])?System::calculate_number(Url::get('total_payment_room')):0,
                            'last_time'=>time(),
                            'reason_discount' => Url::get('reason_discount'),
                            'receiver_phone' => Url::get('receiver_phone')
                            	
      	             );	
                    
					if(Url::get('vip_code') != '')
                    {
						$array += array('vip_code'=>Url::get('vip_code'));	 
					}
					if(Url::get('cancel'))
                    {
						$array += array('cancel_time'=>time());
					}
					if(Url::get('act')=='checkin')
                    {	
						$array += array('time_in'=>time(),'arrival_time'=>time(),'time_out'=>Date_Time::to_time(Url::get('arrival_date'))+(Url::get('arrival_time_out_hour')*3600)+(Url::get('arrival_time_out_munite')*60),'checked_in_user_id'=>Session::get('user_id'));
					}
					if(Url::get('act')=='checkout')
                    {
						$array += array('time_out'=>time(),'checked_out_user_id'=>Session::get('user_id'));
					}
					$row = array();
					if(HOTEL_CURRENCY == 'VND')
                    {
						$exchange_currency_id = 'USD';
					}
                    else
                    {
						$exchange_currency_id = 'VND';	
					}
					$array += array('exchange_rate'=>DB::fetch('SELECT exchange FROM currency WHERE id=\''.$exchange_currency_id.'\'','exchange'));
					// Lấy bảng phụ thu nhà hàng
					$surcharges = DB::fetch_all('select bar_charge.bar_id_from as id,bar_charge.percent from bar_charge where bar_id = '.Session::get('bar_id').' AND portal_id = \''.PORTAL_ID.'\'');
					
                    if($row = $this->check_edit())
                    {
                        /** $bar_reservation_products - list sản phẩm lấy được **/
                        /** $row - Thông tin bàn **/
                        /** Mạnh thêm phần log edit món ăn **/
                        
                        if(!$_REQUEST['product_list']){
                            $bar_reservation_products=array();
                        }
                        
                            
                            $check_log_edit_product = false;
                            $log_edit_product ='';
                            foreach($bar_reservation_products as $id_log=>$value_log)
                            {
                                if(isset($value_log['brp_id']) AND $value_log['brp_id']!='')
                                {
                                    $old_price = DB::fetch("SELECT id,price FROM bar_reservation_product WHERE id=".$value_log['brp_id']);
                                    $value_log['price'] = System::calculate_number($value_log['price']);
                                    if($old_price['price']!=$value_log['price'])
                                    {
                                        $check_log_edit_product = true;
                                        $log_edit_product .= "Product Name: ".$value_log['name']." - Product Id: ".$value_log['product_id']." - Old Price: ".$old_price['price']." - New Price:".$value_log['price']."<br />"; 
                                    }
                                }
                            }
                            if($check_log_edit_product==true)
                            {
                                $title_log = 'Change Price Product - bar reservation , Code: '.$row['id'].', Status: ' .$row['status'].'';
                                $description_log = "
                                    User id: ".User::id()."<br />"."
                                    Date Time: ".date('d/m/Y H:i:s')."<br />"." 
                                ".$log_edit_product."";
                                System::log('edit_product',$title_log,$description_log,$row['id']);
                            }
                        /** End manh log **/
						if((Url::get('act')!='') || (Url::get('acction')==1) || Url::get('cancel'))
                        {
							$bar_reservation_id = $row['id'];
							
                            $table_id = 0;
							if(Url::get('selectgrouptable'))
                            {
							     
								$table_old = DB::fetch_all('SELECT table_id as id,bar_table.name as table_name FROM bar_reservation_table
							INNER JOIN bar_table ON bar_table.id = bar_reservation_table.table_id WHERE bar_reservation_id ='.$bar_reservation_id);
								$table_list = array();
                                foreach(Url::get('selectgrouptable') as $k1=>$v1)
                                {
                                    $table_list[$k1]['id'] = $k1;
                                    $table_list[$k1]['name'] = $k1;
                                    $table_list[$k1]['table_id'] = $k1;
                                }
                                
								$conflix = Table::check_table_conflict($array['arrival_time'],$array['departure_time'],$table_list,$bar_reservation_id);
                                $check_conflix = true;
                                foreach($table_list as $id_confix=>$value_confix)
                                {
                                    if($conflix[$value_confix['id']]!=false)
                                    {
                                        $check_conflix = false;
                                        $this->error('table_id_'.$value_confix['id'],Portal::language('table_code').' '.$value_confix['id'].' '.Portal::language('conflict_of_time_to_reservation').' <a target="blank" href="?page=touch_bar_restaurant&cmd=edit&id='.$conflix[$value_confix['id']].'#'.$conflix[$value_confix['id']].'">#'.$conflix[$value_confix['id']].'</a>',false);
                                    }
                                }
                                if($check_conflix==false)
                                    return;
								$table_id_list = '0';
								foreach($table_list as $tb => $table)
                                {
									$table_id_list .= ','.$table['table_id'];
									$table_id = $table['table_id'];
                                    unset($table_list[$tb]['id']);
								}
								foreach($table_list as $tb => $table)
                                {
                                    
									DB::delete('bar_reservation_table',' bar_reservation_id='.$bar_reservation_id.' AND table_id not in ('.$table_id_list.')');
									if(isset($table_old[$table['table_id']]['id']))
                                    {
                                        $table['num_people'] = Url::get('num_people');
                                        $table['order_person'] = Url::get('order_person');
										DB::update('bar_reservation',$array,'id = '.$bar_reservation_id.'');	
										$log_table .= 'Change Table: '.$table['name'].': SL :'.(isset($table['num_people'])?$table['num_people']:'').': Order: '.(isset($table['order_person'])?$table['order_person']:'').'<br> ';
										unset($table['name']);
                                        unset($table['table_id']);
										DB::update('bar_reservation_table',$table,' bar_reservation_id ='.$bar_reservation_id);
									}
                                    else
                                    {// TH ghép bàn, ghép hóa đơn nếu chuyển đến bàn đang CHECKIN
										$cond_conflix = 'bar_reservation_table.table_id=\''.$table['table_id'].'\' AND ((bar_reservation.time_in <'.$row['time_out'].' AND bar_reservation.time_out > '.$row['time_in'].')) AND bar_reservation.id<>'.Url::iget('id').' AND (bar_reservation.status=\''.$status.'\')';
										$conflict= TouchBarRestaurantDB::get_table_conflict($cond_conflix);
										if(!empty($conflict))
                                        {
											$merge = true;				
											DB::delete('bar_reservation',' id='.$bar_reservation_id.'');
											DB::delete('bar_reservation_table',' bar_reservation_id='.$bar_reservation_id.'');
											DB::delete('bar_reservation_product',' bar_reservation_id='.$bar_reservation_id.'');
											//$log_table .= ' Merge Table: from table:'.$table_old[]['table_name'].' to table: '.$table_new['name'].'';
											$log_table .= ' Merge Bill: from Bill:'.$bar_reservation_id.' to Bill: '.$conflict['id'].'';
											$bar_reservation_id = $conflict['id']; // Gán id cho bàn mới và bỏ bàn cũ.									
										}
                                        else
                                        {
											if(!is_numeric($table['num_people']))
                                            {
												//$table['num_people'] = 5;
											}
                                            $table['num_people'] = Url::get('num_people');
                                            $table['order_person'] = Url::get('order_person');
											unset($table['name']);
											$table['bar_reservation_id'] = $bar_reservation_id;
											DB::update('bar_reservation',$array,'id = '.$bar_reservation_id.'');
											DB::insert('bar_reservation_table',$table);
                                            $table_name = DB::fetch("SELECT name FROM BAR_TABLE WHERE id=".$tb);
											$log_table .= ' Change Table: from table:'.$table_name['name'].' to table: '.$table_list[$tb]['name'].'';
										}
									}
								}
							}
                            else
                            {
                                
								$this->error('table_list',Portal::language('do_not_select_table'));	
							}
							$old_product_ids = TouchBarRestaurantDB::get_old_product($bar_reservation_id); // Lay ra nhung san pham cua hoa don ghep
                            
     	                    $old_set_menu_product_ids = TouchBarRestaurantDB::get_old_set_menu_product($bar_reservation_id); 
                            //System::debug($old_product_ids);
                            //System::debug($old_set_menu_product_ids); exit();

                            /**
                            $j=0;$x=0;
							$arr_price_ids = '(\'0\'';
                            $arr_price_set_ids = '(\'0\'';
                            $arr_product_id = '(\'0\'';
                            //System::debug($bar_reservation_products); exit();
							foreach($bar_reservation_products as $k =>$value)
                            {
								if(!empty($value['brp_id'])){
                                    $arr_price_ids .= ','.'\''.(($value['brp_id']!='')?($value['brp_id']):0).'\'';
                                    if(strpos($arr_product_id,$value['product_id'])===FALSE){
                                        $arr_product_id .= ','.'\''.(($value['product_id']!='')?($value['product_id']):0).'\'';
    								}
                                    $j++;
                                }
                                if(!empty($value['bar_set_menu_id'])){
                                    if(strpos($arr_price_set_ids,$value['bar_set_menu_id'])===FALSE){
                                        $arr_price_set_ids .= ','.'\''.(($value['bar_set_menu_id']!='')?($value['bar_set_menu_id']):0).'\'';
        								$x++;
                                    }
                                }
							}
                            
                            $arr_price_ids .= ')';
                            $arr_price_set_ids.=')';
                            $arr_product_id.=')';
                            $sql = "SELECT 
                            bar_set_menu.id,
                            bar_reservation_product.id as bar_reservation_product_id 
                            FROM 
                            bar_set_menu 
                            INNER JOIN bar_reservation_product ON bar_set_menu.code = bar_reservation_product.product_id
                            WHERE bar_reservation_id = ".$bar_reservation_id." AND code NOT IN ".$arr_product_id;
                            $set_menu_temp = DB::fetch_all($sql);
                            $set_menu_id_list = '(\'0\'';
                            $set_product_id = '(\'0\'';
                            foreach($set_menu_temp as $k=>$value){
                                if(!empty($set_menu_temp)){
                                    if(strpos($set_menu_id_list,$value['id'])===FALSE){
                                         $set_menu_id_list .= ','.'\''.(($value['id']!='')?($value['id']):0).'\'';
                                    }
                                }
                                if(!empty($set_menu_temp)){
                                    if(strpos($set_product_id,$value['bar_reservation_product_id'])===FALSE){
                                         $set_product_id .= ','.'\''.(($value['bar_reservation_product_id']!='')?($value['bar_reservation_product_id']):0).'\'';
                                    }
                                }
                            }
                            $set_menu_id_list.=')';
                            $set_product_id.=')';
                            
                            
                            
                            if($merge==false)
                            {
                                if(strlen($set_menu_id_list)<=5){
                                    echo 1;
                                    //----DB::delete('bar_reservation_product','bar_reservation_id = '.$bar_reservation_id.' AND id not in '.$arr_price_ids.' AND bar_set_menu_id IS NULL'); 
                                }
                                else{
                                    echo 2;
                                    //----DB::delete('bar_reservation_product','bar_reservation_id = '.$bar_reservation_id.' AND id not in '.$arr_price_ids);
                                    
                                    //----DB::delete('bar_reservation_product','bar_reservation_id = '.$bar_reservation_id.' AND bar_set_menu_id in '.$set_product_id);
                                }
                                if(strlen($set_menu_id_list)>5){
                                    echo 3;
                                    //----DB::delete('bar_reservation_set_product','bar_reservation_id = '.$bar_reservation_id.' AND bar_set_menu_id  in '.$set_menu_id_list.''); 
                                }
                                
							}
                            **/
                            
							foreach($bar_reservation_products as $id =>$value)
                            {
								$remaiin = 0;
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
                                $data['bar_set_menu_id'] = "";
                                //$data['chair_number']=$value['chair_number'];//luu truong so ghe khi sua
                                if(isset($value['stt'])){
                                    $data['stt'] = $value['stt'];
                                }
								$data['add_charge'] = 0;
                                if(isset($value['bar_set_menu_id'])){
				                    $data['bar_set_menu_id'] = $value['bar_set_menu_id'];	
                                }	
								if(isset($surcharges[$data['bar_id']]) && $data['bar_id'] != Session::get('bar_id'))
                                {
									$data['add_charge'] = $surcharges[$data['bar_id']]['percent']; 	
								}
								if(isset($products_select[$data['price_id']]))
                                {
									$data['product_price'] = $products_select[$data['price_id']]['price'];	
								}
                                if(isset($value['brp_id']) and $value['brp_id']!=''){
								    $prd_id = $value['brp_id'];
                                }
                                else{
                                    $prd_id = $id;
                                }
								if($merge==true)
                                {// TH ghép bàn, ghép với sp của bàn mới nếu 2 bàn có chung sp	
									if(isset($old_product_ids[$id]))
                                    {
										$data['quantity'] += $old_product_ids[$id]['quantity']; 
                                        $data['complete'] += $old_product_ids[$id]['complete'];
										$data['quantity_discount'] += $old_product_ids[$id]['quantity_discount']; 
										$data['printed'] += $old_product_ids[$id]['printed'];
										$data['note'] = $old_product_ids[$id]['note'].''.$data['note']; 
                                        //$data['chair_number'] = $old_product_ids[$id]['chair_number'];// trung luu so ghe nhap vao database khi edit
										$data['remain'] = $value['remain'];
										$bar_reservation_product_id = DB::update('bar_reservation_product',$data,'bar_reservation_id = '.$bar_reservation_id.' AND id = '.$old_product_ids[$id]['prd_id'].'');
										$log_product .= 'Change Product: '.$data['product_id'].' : Product Name: '.$data['name'].': SL :'.$data['quantity'].': Trả Lại: '.$data['remain'].': Note: '.$data['note'].'<br> ';
									}
                                    else
                                    {	
										$bar_reservation_product_id = DB::insert('bar_reservation_product',$data);	
										$log_product .= 'Insert Product: '.$data['product_id'].' : Product Name: '.$data['name'].': SL :'.$data['quantity'].': Trả Lại: '.$data['remain'].': Note: '.$data['note'].'<br> ';	
                                        
                                    }
								}
                                else
                                {
                                    if(isset($old_product_ids[$id]['id']))
                                    {	
                                        
                                        if(strpos($old_product_ids[$id]['id'],'_')!==FALSE && strpos("DOUTSIDE|FOUTSIDE|SOUTSIDE",$old_product_ids[$id]['product_id'])===FALSE)
                                        {
                                            $arr_temp = explode("_",$old_product_ids[$id]['id']);
                                            $remaiin = DB::fetch('select remain from bar_reservation_product where bar_reservation_id = '.$bar_reservation_id.' AND price_id = '.$arr_temp[0].' AND bar_set_menu_id='.$arr_temp[1],'remain');
                                            $data['remain'] = $value['remain']; 
                                            $bar_reservation_product_id = DB::update('bar_reservation_product',$data,'bar_reservation_id = '.$bar_reservation_id.' AND price_id = '.$arr_temp[0].' AND bar_set_menu_id='.$arr_temp[1]);

                                        }
                                        elseif(strpos("DOUTSIDE|FOUTSIDE|SOUTSIDE",$old_product_ids[$id]['product_id'])===FALSE)
                                        {
                                            $remaiin = DB::fetch('select remain from bar_reservation_product where bar_reservation_id = '.$bar_reservation_id.' AND id = '.$prd_id.'','remain');
                                            $data['remain'] = $value['remain']; 
		                                    $bar_reservation_product_id = DB::update('bar_reservation_product',$data,'bar_reservation_id = '.$bar_reservation_id.' AND id = '.$prd_id.'');
                                        }
										elseif(strpos("DOUTSIDE|FOUTSIDE|SOUTSIDE",$old_product_ids[$id]['product_id'])!==FALSE)
                                        {
                                            $arr_temp = explode("_",$old_product_ids[$id]['id']);
                                            $remaiin = DB::fetch('select remain from bar_reservation_product where bar_reservation_id = '.$bar_reservation_id.' AND id = '.$arr_temp[1],'remain');
                                            $data['remain'] = $value['remain']; 
                                            $bar_reservation_product_id = DB::update('bar_reservation_product',$data,'bar_reservation_id = '.$bar_reservation_id.' AND id = '.$arr_temp[1]);
                                        }
										
										$log_product .= 'Change Product: '.$data['product_id'].' : Product Name: '.$data['name'].': SL :'.$data['quantity'].': Trả Lại: '.$data['remain'].': Note: '.$data['note'].'<br> ';
									}
                                    else
                                    {
										$bar_reservation_product_id = DB::insert('bar_reservation_product',$data);	
	   								    $log_product .= 'Insert Product: '.$data['product_id'].' : Product Name: '.$data['name'].': SL :'.$data['quantity'].': Trả Lại: '.$data['remain'].': Note: '.$data['note'].'<br> ';                                   		
									}
								}   
							}
                            
                            if(!empty($bar_set_menu)){
                               foreach($bar_set_menu as $id =>$value)
                                {
                                    $remaiin = 0;
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
                                    //$data['chair_number'] = $value['chair_number'];//neu co set menu 
    								if(isset($surcharges[$data['bar_id']]) && $data['bar_id'] != Session::get('bar_id'))
                                    {
    									$data['add_charge'] = $surcharges[$data['bar_id']]['percent']; 	
    								}
    								if(isset($products_select[$data['price_id']]))
                                    {
    									$data['product_price'] = $products_select[$data['price_id']]['price'];	
    								}
    								$prd_id = $value['brsp_id'];
    								if($merge==true)
                                    {// TH gh�p b�n, gh�p v?i sp c?a b�n m?i n?u 2 b�n c� chung sp	
    									if(isset($old_set_menu_product_ids[$id]))
                                        {
    										$data['quantity'] += $old_set_menu_product_ids[$id]['quantity']; 
    										$data['quantity_discount'] += $old_set_menu_product_ids[$id]['quantity_discount']; 
    										$data['printed'] += $old_set_menu_product_ids[$id]['printed'];
    										$data['note'] = $old_set_menu_product_ids[$id]['note'].''.$data['note']; 
    										$data['remain'] = $value['remain'];
                                            //$data['chair_number']=$old_set_menu_product_ids['chair_number'];
                                            
    										$bar_reservation_product_id = DB::update('bar_reservation_set_product',$data,'bar_reservation_id = '.$bar_reservation_id.' AND id = '.$old_set_menu_product_ids[$id]['prsd_id'].'');
    										$log_product .= 'Change Product: '.$data['product_id'].' : Product Name: '.$data['name'].': SL :'.$data['quantity'].': Trả Lại: '.$data['remain'].': Note: '.$data['note'].'<br> ';
    									}
                                        else
                                        {	
    										$bar_reservation_product_id = DB::insert('bar_reservation_set_product',$data);	
    										$log_product .= 'Insert Product: '.$data['product_id'].' : Product Name: '.$data['name'].': SL :'.$data['quantity'].': Trả Lại: '.$data['remain'].': Note: '.$data['note'].'<br> ';		
    									}
    								}
                                    else
                                    {
                                        if(isset($old_set_menu_product_ids[$id]['id']))
                                        {	
                                            if(strpos($old_set_menu_product_ids[$id]['id'],'_')!==FALSE)
                                            {
                                                $arr_temp = explode("_",$old_set_menu_product_ids[$id]['id']);
                                                $remaiin = DB::fetch('select remain from bar_reservation_set_product where bar_reservation_id = '.$bar_reservation_id.' AND price_id = '.$arr_temp[0].' AND bar_set_menu_id='.$arr_temp[1],'remain');
                                                $data['remain'] = $value['remain']; 
                                                
        										$bar_reservation_product_id = DB::update('bar_reservation_set_product',$data,'bar_reservation_id = '.$bar_reservation_id.' AND price_id = '.$arr_temp[0].' AND bar_set_menu_id='.$arr_temp[1]);
                                            }
                                            else
                                            {
                                                $remaiin = DB::fetch('select remain from bar_reservation_set_product where bar_reservation_id = '.$bar_reservation_id.' AND id = '.$prd_id.'','remain');
        										$data['remain'] = $value['remain']; 
                                                
        										$bar_reservation_product_id = DB::update('bar_reservation_set_product',$data,'bar_reservation_id = '.$bar_reservation_id.' AND id = '.$prd_id.'');
                                            }
                                                										
    										$log_product .= 'Change Product: '.$data['product_id'].' : Product Name: '.$data['name'].': SL :'.$data['quantity'].': Trả Lại: '.$data['remain'].': Note: '.$data['note'].'<br> ';
    									}
                                        else
                                        {                                            
    										$bar_reservation_product_id = DB::insert('bar_reservation_set_product',$data);	
    	   								    $log_product .= 'Insert Product: '.$data['product_id'].' : Product Name: '.$data['name'].': SL :'.$data['quantity'].': Trả Lại: '.$data['remain'].': Note: '.$data['note'].'<br> ';                                   		
    									}
    								}   
    							} 
                            }
							if($merge == true)
                            { // update lai tong tien 
								Table::updateTotalBar($bar_reservation_id);
							}
						}

						// Print to bar + kitchen
						$title = 'Edit bar reservation , Code: '.$row['id'].', Status: ' .$status.'';
						$description .= ''
						.Portal::language('arrival_time').':'.URL::get('arrival_time').'<br>  ' 
						.Portal::language('departure_time').':'.Url::get('departure_time').'<br>  ' 
						.Portal::language('time').':'.Url::get('time').'<br>  ' 
						.Portal::language('agent_phone').':'.Url::get('agent_phone').'<br>  ' 
						.Portal::language('deposit').':'.Url::get('deposit').'<br>'
						.Portal::language('total').':'.Url::get('total').'<br> ' 
						.Portal::language('note').':'.Url::get('note').'<br>  ' 
						.Portal::language('payment_type_id').':'.URL::get('payment_type_id').'<br>  '
						.'<hr>'.$log_table.'<hr>'.$log_product.'';
						$log_id = System::log('edit',$title,$description,$row['id']);
                        if($reservation_room = DB::fetch('select * from reservation_room where id='.$rr_id)){
                            System::history_log('RECODE',$reservation_room['reservation_id'],$log_id);
                        }
                        System::history_log('BAR',$row['id'],$log_id);
                        
						//==================================IN VÀ CHECKOUT==============================
						
						if(Url::get('act') =='checkout')//if($status == 'CHECKOUT' || Url::get('act') =='checkout')
                        {// Xuất sản phẩm
							require_once 'packages/hotel/includes/php/product.php';
							$bar = DB::fetch('select * from bar where bar.id=\''.$row['bar_id'].'\'');
                            //1 nha hang co 2 kho, lay tu bang portal_department
                            $warehouse = DB::fetch('select warehouse_id, warehouse_id_2 
                                                        from portal_department 
                                                        inner join warehouse w1 on portal_department.warehouse_id = w1.id
                                                        inner join warehouse w2 on portal_department.warehouse_id_2 = w2.id
                                                        where portal_id = \''.PORTAL_ID.'\' and department_code = \''.$bar['department_id'].'\' '); 
                            if(isset($warehouse['warehouse_id']) or isset($warehouse['warehouse_id_2']))
                            {
                                DeliveryOrders::get_delivery_orders(Url::iget('id'),$bar['department_id'],$warehouse['warehouse_id'],$warehouse['warehouse_id_2']);	
                            }
						}
						if(Url::get('act')=='checkout' AND DB::exists("SELECT id FROM bar_reservation WHERE (mice_reservation_id is null OR mice_reservation_id='') AND id=".$bar_reservation_id))
                        {
							TouchBarRestaurantDB::checkPayment($bar_reservation_id);	
                            $this->check_complete_payment($this->check_edit());
						}
						$group_table = DB::fetch('select bar_table.bar_area_id as table_group from bar_table inner join bar_area on bar_table.bar_area_id = bar_area.id where bar_table.id='.$table_id.'','table_group');
						//$group_table = str_replace(' ','_',$group_table);
						if($status == 'CHECKOUT' and Url::get('act') =='checkout')
                        {
                            if(Url::get('print_automatic_bill'))
                            {
                                echo 
                                '<script>
            						'.('window.open("'.Url::build_current(array('cmd'=>'detail',md5('act')=>md5('print'),'print_automatic_bill'=>'1','bar_id'=>Session::get('bar_id'),'bar_area_id'=>Url::get('bar_area_id'),'table_id'=>$table_id,'method'=>'print_direct','id')).'");').'
            						if(window.opener)
            						{
            							window.opener.history.go(0);
            							'.($check_out?'window.close();':'').'
            						}
            						'.($check_out?'window.location="'.Url::build('table_map',array('bar_id'=>Session::get('bar_id'),'group'=>$group_table)).'";':'window.setTimeout("location=\''.Url::build('touch_bar_restaurant',array('cmd','id'=>Url::get('id'),'table_id'=>Url::get('table_id'),'bar_area_id'=>Url::get('bar_area_id'))).'\'",3000);').'
        						</script>';
        						exit();
                            }
                            else
                            {
                                echo 
                                '<script>
            						'.('window.open("'.Url::build_current(array('cmd'=>'detail',md5('act')=>md5('print'),'print_automatic_bill'=>'0','bar_id'=>Session::get('bar_id'),'bar_area_id'=>Url::get('bar_area_id'),'table_id'=>$table_id,'method'=>'print_direct','id')).'");').'
            						if(window.opener)
            						{
            							window.opener.history.go(0);
            							'.($check_out?'window.close();':'').'
            						}
            						'.($check_out?'window.location="'.Url::build('table_map',array('bar_id'=>Session::get('bar_id'),'group'=>$group_table)).'";':'window.setTimeout("location=\''.Url::build('touch_bar_restaurant',array('cmd','id'=>Url::get('id'),'table_id'=>Url::get('table_id'),'bar_area_id'=>Url::get('bar_area_id'))).'\'",3000);').'
        						</script>';
        						exit();
                            }
						}
					}
                    else
                    {
                     
						if(Url::get('selectgrouptable'))
                        { // Check conflix
							$bar_reservation_id = 0;
							$table_list = Url::get('selectgrouptable'); 
							foreach($table_list as $i => $tbl){
								$table_list[$i]['id'] = $i;	
							}
							$conflix = Table::check_table_conflict($array['arrival_time'],$array['departure_time'],$table_list);
                            $check_conflix = true;
                            foreach($table_list as $id_confix=>$value_confix)
                            {
                                if($conflix[$value_confix['id']]!=false)
                                {
                                    $check_conflix = false;
                                    $this->error('table_id_'.$value_confix['id'],Portal::language('table_code').' '.$value_confix['id'].' '.Portal::language('conflict_of_time_to_reservation').' <a target="blank" href="?page=touch_bar_restaurant&cmd=edit&id='.$conflix[$value_confix['id']].'#'.$conflix[$value_confix['id']].'">#'.$conflix[$value_confix['id']].'</a>',false);
                                }
                            }
							if($check_conflix==false)
                            {
								return;
							}
                            else
                            {
								$bar_reservation_id = DB::insert('bar_reservation',$array+array('time'=>time(),'bar_id'=>Session::get('bar_id'),'portal_id'=>PORTAL_ID,'check_edit'=>'1'));
								if(Url::get('package_id'))
                                    DB::query("Update package_sale_detail set quantity_used = quantity_used + 1 Where id=".Url::get('package_id'));
                                $_REQUEST['id'] = $bar_reservation_id;
								/*
                                $code = $bar_reservation_id;
								$code = '';
								$leng = strlen($bar_reservation_id);
								for($j=0;$j<6-$leng;$j++)
                                {
									$code .= '0';	
								}
								$code = date('Y').'-'.$code.$bar_reservation_id;//Session::get('bar_code')
								*/
                                //start: KID sua de ma hoa don tang dan theo portal
                                $sql = 'SELECT max(TO_NUMBER(REPLACE(bar_reservation.code,\'-\',\'\'))) as location FROM bar_reservation WHERE bar_reservation.portal_id=\''.PORTAL_ID.'\'';
								$location = DB::fetch($sql);
                                //System::debug($location);
                        		$location['location'] = (int)(substr($location['location'],4)) + 1;
                                $location['location'] = str_pad($location['location'], 7, "0", STR_PAD_LEFT);
                                $code = date('Y').'-'.($location['location']);
                                //System::debug($code);exit();
                                //end: KID sua de ma hoa don tang dan theo portal
								$table_id = '';
								DB::update('bar_reservation',array('code'=>$code),'id='.$bar_reservation_id);
									if(Url::get('table_list')){
										$table = Url::get('table_list');
										foreach($table as $key=>$tbl)
                                        {
											$tbl['bar_reservation_id'] =$bar_reservation_id;
											unset($tbl['name']);
                                            $tbl['num_people'] = Url::get('num_people');
                                            $tbl['order_person'] = Url::get('order_person'); 
											DB::insert('bar_reservation_table',$tbl);		
											$table_id = $tbl['table_id'];
											$log_table .= 'Add Table: '.$tbl['table_id'].'<br>';
										}
									}
                                    /**
                                     * Manh them chon ban cho nhom doan 
                                     **/
                                     if(isset($_REQUEST['selectgrouptable']))
                                     {
                                        $bar_reservation_table_ids = '0';
                                        foreach(Url::get('selectgrouptable') as $table_ids=>$reservation_table)
                                        {
                                            if($reservation_table['id']!='')
                                                $bar_reservation_table_ids .= ','.$reservation_table['id'];
                                        }
                                        DB::delete('bar_reservation_table','id not in ('.$bar_reservation_table_ids.') AND bar_reservation_id='.$bar_reservation_id);
                                        foreach(Url::get('selectgrouptable') as $table_ids1=>$reservation_table1)
                                        {
                                            if($reservation_table1['id']!='')
                                            {
                                                DB::update('bar_reservation_table',array('bar_reservation_id'=>$bar_reservation_id,'table_id'=>$table_ids1),'id='.$reservation_table1['id']);
                                            }
                                            else
                                            {
                                                DB::insert('bar_reservation_table',array('bar_reservation_id'=>$bar_reservation_id,'table_id'=>$table_ids1));
                                            }
                                        }
                                     }
                                     /**
                                      * End manh 
                                      **/
                                    if(!empty($bar_reservation_products)){
    									foreach($bar_reservation_products as $id =>$value)
                                        {
    										$data['bar_reservation_id'] = $bar_reservation_id;
    										$data['product_id'] = $value['product_id'];
    										$data['quantity'] = $value['quantity'];
    										$data['quantity_discount'] = $value['promotion'];
    										$data['discount_rate'] = $value['percentage'];
    										$data['printed'] = $value['printed'];
    										$data['price'] =  String::convert_to_vnnumeric($value['price']);
    										$data['price_id'] = $value['price_id'];
    										$data['unit_id'] = $value['unit_id'];
    										$data['remain'] =($value['remain']=='')?0:$value['remain'];
    										$data['note'] = $value['note'];
    										$data['name'] = $value['name'];
    										$data['quantity_cancel'] = $value['quantity_cancel']; 
    										$data['discount_category'] = $value['discount_category']; 
    										$data['bar_id'] = $value['bar_id'];
                                            $data['stt'] = "";
                                            $data['bar_set_menu_id'] = "";
                                            //$data['chair_number']=$value['chair_number'];
                                            if(isset($value['stt'])){
                                                $data['stt'] = $value['stt'];
                                            }
            								$data['add_charge'] = 0;
                                            if(isset($value['bar_set_menu_id'])){
            				                    $data['bar_set_menu_id'] = $value['bar_set_menu_id'];	
                                            }	
    										if(isset($surcharges[$data['bar_id']]) && $data['bar_id'] != Session::get('bar_id'))
                                            {
    											$data['add_charge'] = $surcharges[$data['bar_id']]['percent']; 	
    										}
    										if(isset($products_select[$data['price_id']]))
                                            {
    											$data['product_price'] = $products_select[$data['price_id']]['price'];	
    										}
                                            
    										$bar_reservation_product_id = DB::insert('bar_reservation_product',$data);
    										$log_product .= 'Insert Product: '.$data['product_id'].' : Product Name: '.$data['name'].': SL : '.$data['quantity'].'<br> ';
    									}
                                    }
                                    if(!empty($bar_set_menu)){
    									foreach($bar_set_menu as $id =>$value)
                                        {
    										$data['bar_reservation_id'] = $bar_reservation_id;
    										$data['product_id'] = $value['product_id'];
    										$data['quantity'] = $value['quantity'];
    										$data['quantity_discount'] = $value['promotion'];
    										$data['discount_rate'] = $value['percentage'];
    										$data['printed'] = $value['printed'];
    										$data['price'] =  String::convert_to_vnnumeric($value['price']);
    										$data['price_id'] = $value['price_id'];
    										$data['unit_id'] = $value['unit_id'];
    										$data['remain'] =($value['remain']=='')?0:$value['remain'];
    										$data['note'] = $value['note'];
    										$data['name'] = $value['name'];
    										$data['quantity_cancel'] = $value['quantity_cancel']; 
    										$data['discount_category'] = $value['discount_category']; 
    										$data['bar_id'] = $value['bar_id'];
                                            $data['stt'] = $value['stt'];
    										$data['add_charge'] = 0;
                                            $data['bar_set_menu_id'] = $value['bar_set_menu_id'];
                                            $data['chair_number']=$value['chair_number'];
    										if(isset($surcharges[$data['bar_id']]) && $data['bar_id'] != Session::get('bar_id'))
                                            {
    											$data['add_charge'] = $surcharges[$data['bar_id']]['percent']; 	
    										}
    										if(isset($products_select[$data['price_id']]))
                                            {
    											$data['product_price'] = $products_select[$data['price_id']]['price'];	
    										}
    										$bar_reservation_product_id = DB::insert('bar_reservation_set_product',$data);
    										$log_product .= 'Insert Product: '.$data['product_id'].' : Product Name: '.$data['name'].': SL : '.$data['quantity'].'<br> ';
    									}
                                    }
									//exit();
									$title = 'Add bar reservation , Code: '.$code.', Status: ' .$status.'';
									$description .= ''
									.Portal::language('arrival_time').':'.URL::get('arrival_time').'<br>  ' 
									.Portal::language('departure_time').':'.Url::get('departure_time').'<br>  ' 
									.Portal::language('time').':'.Url::get('time').'<br>  ' 
									.Portal::language('agent_phone').':'.Url::get('agent_phone').'<br>  ' 
									.Portal::language('deposit').':'.Url::get('deposit').'<br>'
									.Portal::language('total').':'.Url::get('total').'<br> ' 
									.Portal::language('note').':'.Url::get('note').'<br>  ' 
									.Portal::language('payment_type_id').':'.URL::get('payment_type_id').'<br>  '
									.'<hr>'.$log_table.'<hr>'.$log_product.'';
									$log_id = System::log('add',$title,$description,$bar_reservation_id);
                                    if($reservation_room = DB::fetch('select * from reservation_room where id='.$rr_id)){
                                        System::history_log('RECODE',$reservation_room['reservation_id'],$log_id);
                                    }
                                    System::history_log('BAR',$bar_reservation_id,$log_id);
								}
							}
                            else
                            {
                                $this->error('table_list',Portal::language('do_not_select_table'));	
                            }
						}
					}
				}
                //tieubinh check ,if table booker then no print bill,
                $result_print = '';
                if(Url::get('act') =='checkin' || (Url::get('status') =='CHECKIN' || Url::get('act')=='checkout' && Url::get('act')!='payment')){
                     // Thanh edit phần in món ăn
					TouchBarRestaurant::print_to_kitchen('kitchen');
					TouchBarRestaurant::print_to_kitchen('bar');
                }
				
                
/*-----------------------------send mail-----------------------------*/      
				if(Url::get('cmd')=='edit'  && BAR_INVOICE==1)
                {
                    $array_bar_after = DB::fetch('SELECT id,code,total_before_tax,total FROM bar_reservation WHERE id='.Url::get('id'));
                    $array_bar_product_after = DB::fetch_all('SELECT id,product_id,price,price_id,quantity,product_price,bar_id FROM bar_reservation_product WHERE bar_reservation_id='.Url::get('id'));
                    
                    if($array_bar_after != $array_bar_before || $array_bar_product_after != $array_bar_product_before)
                    {
                        DB::update('bar_reservation',array('check_edit'=>'1'),'id='.Url::get('id'));
                    }
                }
/*-----------------------------send mail-----------------------------*/                
				if(Url::get('act')=='payment')
                { // Khi chọn nút thanh toán thì Save món trước khi gọi module thanh toán
                    $get_member_code = Url::get('member_code')?Url::get('member_code'):"";
                    $total = Url::get('total_payment')?System::calculate_number(Url::get('total_payment')):0;
                    $total_payment_room = Url::get('total_payment_room')?System::calculate_number(Url::get('total_payment_room')):0;
                    $total = $total - $total_payment_room;
                    echo '<script src="packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js"></script>';
					echo '<script src="packages/hotel/packages/reception/modules/includes/common01.js"></script>';
					echo '<LINK rel="stylesheet" href="packages/hotel/skins/default/css/jquery.windows-engine.css" type="text/css">';
					$tt = 'form.php?block_id='.BLOCK_PAYMENT.'&member_code='.$get_member_code.'&cmd=payment&id='.Url::get('id').'&table_id='.Url::get('table_id').'&bar_area_id='.Url::get('bar_area_id').'&bar_id='.Url::get('bar_id').'&type=BAR&total_amount='.$total.'';
                    if(Url::get('customer_id'))
                        $tt .= '&customer_id='.Url::get('customer_id');
                    echo '<script>window.location.href = \''.$tt.'\'</script>'; 
                    exit();
					
				}
                else if(Url::get('act')=='submit_invoice' && $bar_reservation_id>0)
                {
					Url::redirect_current(array('bar_id'=>Session::get('bar_id'),'cmd'=>'detail',md5('act')=>md5('print'),md5('preview')=>1,'id'=>$bar_reservation_id));
				}
                else
                {
					$group_table = '';
					if($table_id !='' && $table_id>0)
                    {
						$group_table = DB::fetch('select bar_table.bar_area_id as table_group from bar_table inner join bar_area on bar_table.bar_area_id = bar_area.id where bar_table.id='.$table_id.'','table_group');
					}
					if(Url::get('acction') == 0 || $merge==true)
                    {
					   if(USE_DISPLAY && USE_DISPLAY==1)
                       {
					       if(isset($_GET['target']) && $_GET['target']=='split')
                           {
					          Url::redirect('table_map',array('bar_id'=>Session::get('bar_id'),'group'=>$group_table,'bar_reservation_id'=>$bar_reservation_id,'target'=>'split'));
					       }
                           else
					       {
					           Url::redirect('table_map',array('bar_id'=>Session::get('bar_id'),'group'=>$group_table,'bar_reservation_id'=>$bar_reservation_id));
					       }
					   }
                       else
					   {
					       Url::redirect('table_map',array('bar_id'=>Session::get('bar_id'),'group'=>$group_table));
					   }
					}
                    else
                    {
                        if(Url::get("package_id"))
                        {
                            Url::redirect_current(array('bar_id'=>Session::get('bar_id'),'cmd','arrival_time','id','table_id'=>$table_id,'bar_area_id'=>$group_table,'package_id'=>Url::get('package_id')));
                        }
                        else
                        {
                            Url::redirect_current(array('bar_id'=>Session::get('bar_id'),'cmd','arrival_time','id','table_id'=>$table_id,'bar_area_id'=>$group_table,'status'=>'edit'));
                        }	
                    }
				}
		}
	}
	function draw()
    {
		require_once 'packages/hotel/packages/restaurant/includes/table.php';
		require_once 'packages/hotel/includes/php/hotel.php';
        $this->cancel_book_expried();
		$bar_id =  Session::get('bar_id');		
		$_REQUEST['bar_id'] = Session::get('bar_id');	
		$this->full_rate=(Session::get('full_rate')?Session::get('full_rate'):0);
		$this->full_charge = Session::get('full_charge')?Session::get('full_charge'):0;
        $this->discount_after_tax = DB::fetch('select discount_after_tax from bar where id ='.$_REQUEST['bar_id'],'discount_after_tax');
		$this->map = array();
		$categories = TouchBarRestaurantDB::select_list_other_category(Session::get('bar_id'));
		$food_categories = TouchBarRestaurantDB::select_list_food_category(Session::get('bar_id'));
		$table_list = array();
		//--------------------Hien thi theo tung Bar------------------------------------//
		//$cond_admin = Table::get_privilege_bar();
        //System::debug($categories);
		$bars = TouchBarRestaurantDB::get_total_bars();
		//=================================================================================//
		$k=0;
		$param = '';
		$amount = 0;$total_amount = 0;
		$bar_reservation = array();
		$tax_rate = Url::get('tax_rate');
		$service_charge = Url::get('service_charge');				
		//============================== currency ================================
		$curr = HOTEL_CURRENCY;
		//$currency = DB::select('currency','name=\''.$curr.'\'');
		//============================== bar_table ===============================
		if(Url::get('cmd') == 'add'){
			$arrival_time_in_munite = Url::check('arrival_time_in_munite')?Url::get('arrival_time_in_munite'):0;
			$arrival_time_out_munite = Url::check('arrival_time_out_munite')?Url::get('arrival_time_out_munite'):45;
	
			$from_time = Url::get('arrival_date')?(Date_Time::to_time(URL::get('arrival_date'))+(Url::get('arrival_time_in_hour')*3600)+($arrival_time_in_munite*60)):(Date_Time::to_time(date('d/m/Y',time())));
			$to_time = Url::get('arrival_date')?(Date_Time::to_time(URL::get('arrival_date'))+(Url::get('arrival_time_out_hour')*3600)+($arrival_time_out_munite*60)):(Date_Time::to_time(date('d/m/Y',time()))+3600*24-1);
			
			//-----------------------------------------------Thoi gian---------------------------------------------------//
			$default_time_in_hour = date('H',time());
			$default_time_in_munite = date('i',time());
			if($default_time_in_hour>=22){
				$default_time_out_hour = 23;
				$default_time_out_munite = 45;
			}else{
				$default_time_out_hour = $default_time_in_hour+2;
				$default_time_out_munite = $default_time_in_munite;
			}
		}else if($old_item = $this->check_edit()){
			$from_time = $old_item['arrival_time'];
			$to_time = 	$old_item['departure_time'];
			$default_time_in_hour = date('H',$old_item['arrival_time']);
			$default_time_out_hour = date('H',$old_item['departure_time']);
			$default_time_in_munite =date('i',$old_item['arrival_time']);
			$default_time_out_munite = date('i',$old_item['departure_time']);
		}
		//-----------------------------------------------------------------------------------------------------------//
		$vip_cards = DB::fetch_all('
			select id,code,CARD_HOLDER as name,DISCOUNT_PERCENT as discount_percent,discount_amount
			from vip_card WHERE JOIN_DATE<= \''.Date_Time::to_orc_date(date('d/m/Y')).'\' AND EXPIRED_DATE>= \''.Date_Time::to_orc_date(date('d/m/Y')).'\'
		');
		//--------------------------------------------------------Xy ly truong hop Edit--------------------------------------------------
		$arr = array();
		if($row = $this->check_edit()){
			if($row['bar_fee_rate']==''){
				$row['bar_fee_rate']=0;
			}
			if($row['tax_rate']==''){
				$row['tax_rate']=0;
			}
			$this->full_rate = ($row['full_rate']=='')?0:$row['full_rate'];	
			$this->full_charge = ($row['full_charge']=='')?0:$row['full_charge'];
            $this->discount_after_tax = ($row['discount_after_tax']=='')?0:$row['discount_after_tax'];
			$table_list = DB::fetch_all('select brt.table_id as id,
												brt.num_people,
												brt.order_person,
												bar_table.name as name
										from bar_reservation_table brt
											inner join bar_reservation ON bar_reservation.id = brt.bar_reservation_id
											inner join bar_table ON bar_table.id = brt.table_id
										WHERE brt.bar_reservation_id = '.$row['id'].'');
			
                  //$cond = '';
//		if($bar_id !=Session::get('bar_id')){
//			$surcharges = DB::fetch('select * from bar where id = '.Session::get('bar_id').' AND portal_id = \''.PORTAL_ID.'\'');
//			$cond = ' AND product_price_list.department_code = \''.$surcharges['department_id'].'\'';
//		}else{
//			$cond = ' AND product_price_list.department_code = \''.Session::get('dp_code').'\'';	
//           } 
            $sql = '
					SELECT 
						brp.id || \'-\' || brp.bar_set_menu_id as id,
                        DECODE(brp.name,null,product.name_'.Portal::language().',brp.name) as product_name, 
						unit.name_'.Portal::language().' as unit,brp.quantity, brp.price
						,((brp.price * (brp.quantity - brp.quantity_discount- brp.quantity_cancel - brp.remain)) - (brp.price * (brp.quantity - brp.quantity_discount- brp.quantity_cancel - brp.remain)*brp.discount_category/100) - ((brp.price * (brp.quantity - brp.quantity_discount- brp.quantity_cancel - brp.remain)*brp.discount_category/100)*brp.discount_rate/100)) as amount
						,brp.product_id
						,brp.quantity_discount as promotion, brp.discount_rate as percentage, brp.printed as printed,brp.unit_id
						,brp.remain,brp.status,brp.note,brp.price_id,brp.quantity_cancel,brp.discount_category
						,brp.quantity_discount,brp.discount_rate,brp.product_sign,brp.bar_id
                        ,brp.stt,brp.stt_order
                        ,brp.complete
                        --,brp.chair_number --trung lay ra cot chair_number
					FROM 
						bar_reservation_product brp
						LEFT OUTER JOIN bar_reservation ON bar_reservation.id = brp.bar_reservation_id
						LEFT OUTER JOIN product ON product.id = brp.product_id
						LEFT OUTER JOIN product_price_list ON product_price_list.product_id = product.id
						LEFT OUTER JOIN unit ON unit.id = product.unit_id
					WHERE
						brp.bar_reservation_id = '.$row['id'].' AND brp.bar_set_menu_id IS NULL
					ORDER BY
						brp.stt ASC
			';
			$original_reservation_products = DB::fetch_all($sql);
            //System::debug($original_reservation_products); exit();
            $sql = '
					SELECT 
						brp.id || \'-\' || brp.bar_set_menu_id as id,
                        DECODE(brp.name,null,product.name_'.Portal::language().',brp.name) as product_name, 
						unit.name_'.Portal::language().' as unit,brp.quantity, brp.price
						,((brp.price * (brp.quantity - brp.quantity_discount- brp.quantity_cancel - brp.remain)) - (brp.price * (brp.quantity - brp.quantity_discount- brp.quantity_cancel - brp.remain)*brp.discount_category/100) - ((brp.price * (brp.quantity - brp.quantity_discount- brp.quantity_cancel - brp.remain)*brp.discount_category/100)*brp.discount_rate/100)) as amount
						,brp.product_id
						,brp.quantity_discount as promotion, 
                        brp.discount_rate as percentage, brp.printed as printed,brp.unit_id
						,brp.remain,brp.status,brp.note,brp.price_id,brp.quantity_cancel,brp.discount_category
						,brp.quantity_discount,brp.discount_rate,brp.product_sign,brp.bar_id
                        ,brp.stt
                        ,brp.bar_set_menu_id
                        ,bar_set_menu_product.quantity as bar_set_menu_product_quantity
					FROM 
						bar_reservation_set_product brp
                        INNER JOIN bar_set_menu_product ON brp.bar_set_menu_id = bar_set_menu_product.bar_set_menu_id AND brp.product_id = bar_set_menu_product.product_id
						LEFT OUTER JOIN bar_reservation ON bar_reservation.id = brp.bar_reservation_id
						LEFT OUTER JOIN product ON product.id = brp.product_id
						LEFT OUTER JOIN product_price_list ON product_price_list.product_id = product.id
						LEFT OUTER JOIN unit ON unit.id = product.unit_id
					WHERE
						brp.bar_reservation_id = '.$row['id'].'
					ORDER BY
						brp.stt ASC
			';
            $bar_reservation_set_product = DB::fetch_all($sql);
            //System::debug($bar_reservation_set_product); //exit();
            //$original_reservation_products+=$bar_reservation_set_product;
			//System::debug($original_reservation_products);// exit();
			$i = 0;
			$items_ids = '';
			$arr = array();
			$quantity_product = 0;
			$discount_quantity_product = 0;
			$bar_set_menu_id = 0;
            $bar_set_menu_id_temp = 0;
			$count = 0;
            //System::debug($original_reservation_products);
          
			foreach($original_reservation_products as $k =>$valu)
            {
				$bar_set_menu_product_quantity = 1;
                if(isset($valu['bar_set_menu_product_quantity'])){
                    $valu['price'] *= $valu['bar_set_menu_product_quantity'];
                    $valu['quantity'] = $valu['quantity']/$valu['bar_set_menu_product_quantity'];
                    $valu['promotion'] = $valu['promotion']/$valu['bar_set_menu_product_quantity'];
                    $valu['remain'] = $valu['remain']/$valu['bar_set_menu_product_quantity'];
                    $valu['quantity_cancel'] = $valu['quantity_cancel']/$valu['bar_set_menu_product_quantity'];
                }
                //if(isset($arr[$valu['price_id']]) && $valu['product_id']!='FOUTSIDE' && $valu['product_id']!='DOUTSIDE' && $valu['product_id']!='SOUTSIDE')
                if(isset($arr[$valu['price_id']]) || (!empty($valu['bar_set_menu_id']) && $valu['bar_set_menu_id']==$bar_set_menu_id_temp))
                {
					if(!empty($valu['bar_set_menu_id']) && $valu['bar_set_menu_id']==$bar_set_menu_id_temp){
                            $arr[$bar_set_menu_id]['price'] += $valu['price'];
                            $i++;
                            $arr[$bar_set_menu_id]['stt'] = $i;
    						$price = $valu['price'];
    						$amount = ($valu['price'] * ($valu['quantity'] - $valu['quantity_discount']));
    						$arr[$bar_set_menu_id]['amount'] += ($amount - $amount*$valu['discount_category']/100 - (($amount - $amount*$valu['discount_category']/100)*$valu['discount_rate']/100));
					}
                    else if(isset($arr[$valu['price_id']])){
                        $i++;
                        $arr[$bar_set_menu_id]['stt'] = $i;
                        $amount = ($valu['price'] * ($valu['quantity'] - $valu['quantity_discount']));
    					$arr[$valu['price_id']]['amount'] += ($amount - $amount*$valu['discount_category']/100 - (($amount - $amount*$valu['discount_category']/100)*$valu['discount_rate']/100));
    					$arr[$valu['price_id']]['quantity'] += $valu['quantity'];
    					$arr[$valu['price_id']]['promotion'] += $valu['promotion'];
    					$arr[$valu['price_id']]['remain'] += $valu['remain'];
    					$arr[$valu['price_id']]['quantity_cancel'] += $valu['quantity_cancel'];
				    }    
                }
                else
                {
					if($valu['product_id']=='FOUTSIDE' || $valu['product_id']=='DOUTSIDE' || $valu['product_id']=='SOUTSIDE')
                    {
						$arr[$valu['price_id'].'_'.$valu['id']]['id'] = $valu['price_id'].'_'.$valu['id'];
						$arr[$valu['price_id'].'_'.$valu['id']]['name'] = $valu['product_name'];
						$arr[$valu['price_id'].'_'.$valu['id']] += $valu;
						$arr[$valu['price_id'].'_'.$valu['id']]['price'] = $valu['price'];
						$arr[$valu['price_id'].'_'.$valu['id']]['brp_id'] = $valu['id'];
                        $i++;
                        $arr[$valu['price_id'].'_'.$valu['id']]['stt'] = $i;
						$price = $valu['price'];
						$amount = ($valu['price'] * ($valu['quantity'] - $valu['quantity_discount']));
						$arr[$valu['price_id'].'_'.$valu['id']]['amount'] = ($amount - $amount*$valu['discount_category']/100 - (($amount - $amount*$valu['discount_category']/100)*$valu['discount_rate']/100));
					}
                    else
                    {
						if(!empty($valu['bar_set_menu_id'])){
						    if($valu['bar_set_menu_id']!=$bar_set_menu_id_temp){
						        $bar_set_menu_id_temp = $valu['bar_set_menu_id'];
                                $sql = "SELECT * FROM bar_set_menu WHERE id = ".$valu['bar_set_menu_id'];
                                $result = DB::fetch($sql);
                                //System::debug($result);
                                $bar_set_menu_id = $result['id']."-".$result['code'];
                                $arr[$bar_set_menu_id] = $valu;
                                $i++;
                                $arr[$bar_set_menu_id]['stt'] = $i;
                                $arr[$bar_set_menu_id]['id'] = $bar_set_menu_id;
                                $arr[$bar_set_menu_id]['product_id'] = $bar_set_menu_id;
                                $arr[$bar_set_menu_id]['name'] = $result['name'];
                                $arr[$bar_set_menu_id]['unit'] = 'set';
        						$arr[$bar_set_menu_id]['product_name'] = $result['name'];   						
        						$arr[$bar_set_menu_id]['price'] = $valu['price'];
        						$arr[$bar_set_menu_id]['brp_id'] = $valu['id'];
        						$price = $valu['price'];
        						$amount = ($valu['price'] * ($valu['quantity'] - $valu['quantity_discount']));
        						$arr[$bar_set_menu_id]['amount'] = ($amount - $amount*$valu['discount_category']/100 - (($amount - $amount*$valu['discount_category']/100)*$valu['discount_rate']/100));
						    }
                        }
                        else{
                            $arr[$valu['price_id']]['id'] = $valu['price_id'];
    						$arr[$valu['price_id']]['name'] = $valu['product_name'];
    						$arr[$valu['price_id']] += $valu;
                            $i++;
                            $arr[$valu['price_id']]['stt'] = $i;
    						$arr[$valu['price_id']]['price'] = $valu['price'];
                            $arr[$valu['price_id']]['unit'] = $valu['unit'];
    						$arr[$valu['price_id']]['brp_id'] = $valu['id'];
    						$price = $valu['price'];
    						$amount = ($valu['price'] * ($valu['quantity'] - $valu['quantity_discount']));
    						$arr[$valu['price_id']]['amount'] = ($amount - $amount*$valu['discount_category']/100 - (($amount - $amount*$valu['discount_category']/100)*$valu['discount_rate']/100));
					    }
                    }
					unset($valu['id']);
				}
			}
            //System::debug($arr); //exit();
			if($row['customer_id'] != 0 && $row['customer_id'] != ''){
				$row['customer_name'] = DB::fetch('select name from customer where id = '.$row['customer_id'].'','name');
			}
			$row['total_amount'] = System::display_number(round($total_amount));
			$row['deposit'] = System::display_number(round($row['deposit']));
			$row['total_payment'] = System::display_number(round($row['total']));
			$row['total_amount'] = System::display_number(round($row['total_before_tax']));
			$row['service_charge'] = System::display_number(round($row['bar_fee_rate']));
			if($row['reservation_room_id'] != ''){
				$rr_info = TouchBarRestaurantDB::get_rr_id($row['reservation_room_id']);
				$row['room_id'] = $rr_info['room_id'];
				$row['room_name'] = $rr_info['room_name'];
				$row['reservation_id'] = $rr_info['id'];
				$row['reservation_name'] = ($rr_info['traveller_name']=='')?$rr_info['customer_name']:$rr_info['traveller_name'];
			}else{
				$row['room_id'] = '';
				$row['room_name'] = '';
				$row['reservation_id'] = '';
				$row['reservation_name'] = '';	
			}		
			
			$row['deposit_date']= $row['deposit_date']?Date_Time::convert_orc_date_to_date($row['deposit_date'],'/'):'';
			$row['customer_name'] = $row['customer_id']?DB::fetch('select id,name from customer where id = '.$row['customer_id'].'','name'):($row['agent_name']?$row['agent_name']:'');
			$row['reservation_id'] = $row['reservation_room_id']?$row['reservation_room_id']:'';
			$row['remain'] = System::display_number(System::calculate_number($row['total_payment']) - System::calculate_number($row['deposit']));
			$row['discount_percent'] = $row['discount_percent']?$row['discount_percent']:'';
			$row['discount'] = $row['discount']?System::display_number($row['discount']):'';
            $row['reason_discount'] = $row['reason_discount']?$row['reason_discount']:'';
            $row['receiver_phone'] = $row['receiver_phone']?$row['receiver_phone']:'';
			if(!isset($row['vip_code'])){
				$row['vip_code'] = '';	
			}
			$row['discount_after_tax'] = ($row['discount_after_tax']=='')?0:$row['discount_after_tax']; 
			$b_r_table = DB::fetch_all('select bar_reservation_table.*,bar_table.name from bar_reservation_table  inner join bar_table on bar_table.id=bar_reservation_table.table_id where bar_reservation_id = '.$row['id'].'');
			$row['table_name'] = '';
			foreach($b_r_table as $br =>$table){
				$row['order_person'] = $table['order_person'];
				$row['num_people'] = $table['num_people'];
				$row['table_name'] .= ($row['table_name']=='')?$table['name']:(','.$table['name']);
			}
		}else{
			$rate = 0;$row['status'] = '';$row['remain'] = '';$row['total_payment'] = '';$row['total_amount'] = '';$row['deposit_date'] = '';$row['discount_percent'] = '';$row['discount'] = '';$row['deposit']=0;
			$row['vip_code'] = '';$row['num_table'] = '';$row['customer_name'] = '';$row['customer_id'] = '';$row['note'] = '';$row['receiver_name'] = '';$row['receiver_phone'] = '';$row['reason_discount'] = '';
			$row['payment_result'] = '';$row['banquet_order_type'] = '';$row['tax_rate'] = (RES_TAX_RATE?RES_TAX_RATE:0); $row['service_charge'] = (RES_SERVICE_CHARGE?RES_SERVICE_CHARGE:0);
			$row['order_person'] = '';$row['num_people'] = '';$row['room_id'] = '';$row['reservation_id'] = '';$row['room_name'] = '';	$row['reservation_name'] = '';$row['discount_after_tax'] = 1;$row['full_rate'] = 0;$row['full_charge'] = 0;
			$row['pay_with_room'] = '';$row['foc'] = '';
			$table = DB::fetch_all('select bar_table.*,\'\' as order_person from bar_table where id='.Url::iget('table_id'));
			foreach($table as $t => $tabl){
				$row['table_name'] = $tabl['name'];
			}
			$table_list = $table;
		}
		$this->map += $row;
        //System::debug($this->map);
		//------------------------------------------------------------------------------------------------------------------------------------
		$discount_percent = array();
		//---------------------Gi?m giá % dùng select_box----------------------------
		$param = '';
		$amount = 0;
		$list_products = TouchBarRestaurantDB::get_all_product();
		$full_rate = 0; $full_charge = 0;
		foreach($list_products as $k => $product){
			$list_products[$k]['price'] = $product['price'];
		}
		
		if(defined('IMENU') and IMENU)
		{
			$products = '<ul id="bound_product_list" style="display:none;">';
			foreach($list_products as $id => $prd){
					$products .= '<li id="product_'.$prd['id'].'" class="product-list-imenu" title="'.ucfirst($prd['name']).'" onclick="SelectedItems(\''.$prd['id'].'\',0);">
						'.$prd['code'].' - '.System::display_number($prd['price']).'<br><img src="'.$prd['image_url'].'"><br>'.ucfirst($prd['name']).'<br>'.ucfirst($prd['name_2']).'<input name="items" type="hidden" id="items_'.$prd['id'].'" /></li>';
			}
			$products .= '</ul>';
		}
		else
		{
			$products = '<ul id="bound_product_list" style="display:none;">';
			foreach($list_products as $id => $prd){
					$products .= '<li id="product_'.$prd['id'].'" class="product-list" title="'.ucfirst($prd['name']).'" onclick="SelectedItems(\''.$prd['id'].'\',0);"><span class="product-name">'.ucfirst($prd['name']).'</span><br>'.System::display_number($prd['price']).'<input name="items" type="hidden" id="items_'.$prd['id'].'" /></li>';
			}
			$products .= '</ul>';			
		}
        //System::debug($list_products); 
		$table_map = TouchBarRestaurantDB::get_table_map();
		$this->map['bars'] = $bars;
        
		$categories_discount = TouchBarRestaurantDB::get_list_category_discount();
		$this->map['full_charge'] = $this->full_charge;
		$this->map['full_rate'] = $this->full_rate;
        
        $this->map['discount_after_tax'] = $this->discount_after_tax;
        
		$this->map['product_array'] = String::array2js($list_products);
		$this->map['products'] = $products;
		$this->map['categories'] = $categories;
		$this->map['food_categories'] = $food_categories;
		$this->map['items'] = $param;
		$this->map['floors'] = $table_map;
		$this->map['categories_discount_js'] = String::array2js($categories_discount);
		$this->map['arrival_date'] = isset($row['arrival_time'])?date('d/m/Y',$row['arrival_time']):Url::get('arrival_time');
		$this->map['departure_date'] = isset($row['departure_time'])?date('d/m/Y',$row['departure_time']):Url::get('arrival_time');
		$this->map['units']=String::array2js(DB::fetch_all('select id as name, name_'.Portal::language().' as id from unit where 1=1 order by name_'.Portal::language().''));
		//$this->map['discount_percent_list'] = $discount_percent;
        
		$this->map['product_list_js'] = String::array2js($arr);
		$this->map['table_list_js'] = String::array2js($table_list);
		// Danh sacsh phong 
		$rows_list = Hotel::get_reservation_room();
		$guest_list = Hotel::get_reservation_traveller_guest();
		$list_room[0]='-------';
		$list_room = $list_room+String::get_list($rows_list,'name');
		//danh sach reservation
		$rows_list = $guest_list;
		$list_reservation[0]='-------';
		$list_reservation_traveller = $list_reservation+String::get_list($rows_list,'name');
		//danh sach phong - ten khach
		$reservation_room_list = Hotel::get_reservation_room_guest();
		if(Url::get('cmd')=='edit')
        {
			$this->map['arrival_time_in_hour'] = date('H',$row['arrival_time']);
			$this->map['arrival_time_in_munite'] = date('i',$row['arrival_time']);
			$this->map['arrival_time_out_hour'] = date('H',$row['departure_time']);
			$this->map['arrival_time_out_munite'] = date('i',$row['departure_time']);
			$row['arrival_time'] = date('d/m/Y',$row['arrival_time']);
			//Get reservvation travller
			if($row['reservation_room_id'])
            {
				$current_room = TouchBarRestaurantDB::get_room_guest($row['reservation_room_id']);
				$guest_list[$current_room['id']]['id'] = $current_room['id'];
				$guest_list[$current_room['id']]['name'] = $current_room['name'];
				$rows_list[$current_room['reservation_room_id']]['id'] = $current_room['reservation_room_id'];
				$rows_list[$current_room['reservation_room_id']]['name'] = $current_room['room_name'];
				$rows_list[$current_room['reservation_room_id']]['agent_name'] = $current_room['name'];
			}
			$this->map['reservation_traveller_id']=Url::get('reservation_traveller_id',$row['reservation_traveller_id']);
		}else{
			$this->map['arrival_time_in_hour'] = Url::check('arrival_time_in_hour')?Url::get('arrival_time_in_hour'):$default_time_in_hour;
			$this->map['arrival_time_in_munite'] = Url::check('arrival_time_in_munite')?Url::get('arrival_time_in_munite'):$default_time_in_munite;
			$this->map['arrival_time_out_hour'] = Url::check('arrival_time_out_hour')?Url::get('arrival_time_out_hour'):$default_time_out_hour;
			$this->map['arrival_time_out_munite'] = Url::check('arrival_time_out_munite')?Url::get('arrival_time_out_munite'):$default_time_out_munite;		
		}
        $_REQUEST['arrival_time_in_hour'] = $this->map['arrival_time_in_hour'];
        $_REQUEST['arrival_time_in_munite'] = $this->map['arrival_time_in_munite'];   
        $_REQUEST['arrival_time_out_hour'] = $this->map['arrival_time_out_hour'];  
        $_REQUEST['arrival_time_out_munite'] = $this->map['arrival_time_out_munite'];       
		$this->map['date'] = date('d/m/Y',time());
		$this->map['vip_cards'] = $vip_cards;
		$this->map+= array(
			'reservation_traveller_list'=>$guest_list,
			'reservation_room_id_list'=>$list_room,
			'reservation_traveller_id_list'=>$list_reservation_traveller
		);
		$this->map['bar_name'] = DB::fetch('select name from bar where id='.Session::get('bar_id'),'name');
        $this->map['bar_area_id'] = Url::get('bar_area_id')?Url::get('bar_area_id'):'';
        if(Url::get('bar_area_id'))
        $this->map['print_automatic_bill'] = DB::fetch('select print_automatic_bill from bar_area where id='.Url::get('bar_area_id').' AND bar_id='.Session::get('bar_id').'','print_automatic_bill');
		else
        $this->map['print_automatic_bill'] = '';
        
        if(isset($this->map['reservation_room_id']) AND $this->map['reservation_room_id']!='')
        {
           $this->map['reservation_room_status'] = DB::fetch("SELECT status from reservation_room where id=".$this->map['reservation_room_id'],'status');
           $this->map['room_name'] = DB::fetch("SELECT room.name as name from room inner join reservation_room on reservation_room.room_id=room.id where reservation_room.id=".$this->map['reservation_room_id'],'name');
           $this->map['reservation_name'] = DB::fetch("SELECT concat(concat(traveller.first_name,' '),traveller.last_name) as traveller from traveller inner join reservation_traveller on traveller.id=reservation_traveller.traveller_id where reservation_traveller.reservation_room_id=".$this->map['reservation_room_id'],'traveller');
        }
        else
        {
            $this->map['reservation_room_status'] = '';
        }
        //System::debug($this->map);
        $this->map['amount_package'] = 0;
        if(Url::get('package_id'))
        {
            $this->map['amount_package'] = DB::fetch("SELECT price FROM package_sale_detail WHERE id=".Url::get('package_id'),"price");
        }
        
	/** MICE **/
        $this->map['close_mice'] = 0;
        if(Url::get('cmd')=='edit' AND $this->map['mice_reservation_id']!='' AND DB::exists('SELECT id FROM mice_reservation WHERE close=1 AND id='.$this->map['mice_reservation_id']))
        {
            $this->map['close_mice'] = 1;
        }
        /** end MICE **/
	
        // Thanh add phan hien thi mon an ra man hinh Socket
        if(USE_DISPLAY && USE_DISPLAY==1 && isset($_GET['status']) && $_GET['status']=='edit'){
                $bar_reservation_id = $_GET['id'];
                $sql = "SELECT 
                 bar_reservation_product.id as id,
                 bar_reservation_product.quantity,
                 bar_reservation_product.complete,
                 bar_reservation_product.name,
                 bar_table.name as table_name,
                 product_category.code as code
                FROM bar_reservation_product 
                    INNER JOIN bar_reservation ON bar_reservation.id = bar_reservation_product.bar_reservation_id
                    INNER JOIN bar_reservation_table ON bar_reservation_table.bar_reservation_id =  bar_reservation.id
                    INNER JOIN bar_table ON bar_table.id = bar_reservation_table.table_id
                    INNER JOIN product ON product.id =  bar_reservation_product.product_id
                    INNER JOIN product_category ON product_category.id = product.category_id              
                WHERE bar_reservation_product.bar_reservation_id=$bar_reservation_id ORDER BY bar_reservation.time_in, bar_reservation_product.id DESC";
                $arr_product = DB::fetch_all($sql);     
                
                $food_categories = $this->get_list_food_category();
                //System::debug($food_categories);
                foreach($arr_product as $key=>$value){
                    foreach($food_categories as $k=>$v){
                        if($value['code'] == $v['code']){
                            $arr_product[$key]['type'] = 'cooking';
                            break;
                        }
                        else{
                            $arr_product[$key]['type'] = 'bar';
                        }
                    }
                } 
                         
                $arr_product_js = String::array2js($arr_product);
                
                
                //System::debug($arr_product_js); exit();
                
            $this->map['arr_product_js'] = $arr_product_js;  
        }
        // end
        
        $this->map['last_time'] = time();
        if(Url::get('id'))
            $table_map_full = TouchBarRestaurantDB::get_table_map_full(Url::get('id'));
        else
            $table_map_full = TouchBarRestaurantDB::get_table_map_full('');
        
        $this->map['floor_full'] = $table_map_full; 
		if(defined('IMENU') and IMENU)
		{
			$this->parse_layout('list_imenu',$this->map);  
		}
		else
		{
		  //System::debug($this->map);
            $this->parse_layout('list_denhat',$this->map);
		}
	}	

	function check_edit(){
		if(Url::get('cmd')=='edit' and Url::get('id') and $bar_reseration = DB::select('bar_reservation','id = '.Url::iget('id').'')){
			return $bar_reseration;
		}else{
			return false;
		}
	}
	function cancel_book_expried(){    
		DB::update('bar_reservation',array('status'=>'CANCEL','cancel_time'=>time()),' bar_reservation.departure_time < '.(time()-86400).' AND bar_reservation.status=\'BOOKED\'');	
	}
    
    function check_complete_payment($bar_reservation)
    {
        if($bar_reservation && $bar_reservation['total']>0)
        {
            $temp_row = '';
            $total_must_pay  = $bar_reservation['total'];
            $total_paid  = 0;
            $payment_paid  = DB::fetch_all('Select * from payment where bill_id = '.$bar_reservation['id'].' and type = \'BAR\' order by id desc');
            //System::debug($payment_paid);
            if($payment_paid)
            {
                foreach($payment_paid as $k=>$v)
                {
                    if(!$temp_row)
                        $temp_row = $payment_paid[$k];
                    $total_paid += $v['amount']*$v['exchange_rate'];
                }
                
                if(abs($total_must_pay-$total_paid>100) and $bar_reservation['pay_with_room']!=1)
                {
                    unset($temp_row['id']);
                    $temp_row['amount'] = $total_must_pay - $total_paid;
                    $temp_row['time'] = time();
                    $temp_row['user_id'] = Session::get('user_id');
                    $temp_row['payment_type_id'] = 'CASH';
                    unset($temp_row['card_number']);
                    unset($temp_row['credit_card']);
                    $temp_row['currency_id'] = 'VND';
                    unset($temp_row['count']);
                    unset($temp_row['description']);
                    $temp_row['exchange_rate'] = 1;
                    $temp_row['portal_id'] = PORTAL_ID;
                    unset($temp_row['bank_acc']);
                    unset($temp_row['folio_id']);
                    unset($temp_row['type_dps']);
                    $temp_row['bank_fee'] = 0;
                    unset($temp_row['date_used']);
                    DB::insert('payment',$temp_row);
   
                }
            }
            else
            if($bar_reservation['pay_with_room']!=1)
            {
                $temp_row = array();
                $temp_row['bill_id'] = $bar_reservation['id'];
                $temp_row['type'] = 'BAR';
                $temp_row['amount'] = $total_must_pay;
                $temp_row['time'] = time();
                $temp_row['user_id'] = Session::get('user_id');
                $temp_row['payment_type_id'] = 'CASH';
                $temp_row['currency_id'] = 'VND';
                $temp_row['exchange_rate'] = 1;
                $temp_row['portal_id'] = PORTAL_ID;
                $temp_row['bank_fee'] = 0;
                DB::insert('payment',$temp_row);
                
            }
        }
        
    }
    static function get_list_food_category(){
		$dp_code = DB::fetch('select department_id as code from bar where portal_id=\''.PORTAL_ID.'\'','code');	
		$structure_id_food = DB::fetch('select structure_id from product_category where code=\'DA\'','structure_id');
		$sql = 'SELECT
					product_category.id
					,product_category.name
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
  }
?>
