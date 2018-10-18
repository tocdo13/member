<?php
class KaraokeTouchForm extends Form{
	function KaraokeTouchForm(){
		Form::Form('KaraokeTouchForm');
		$this->link_css(Portal::template('hotel').'/css/room.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_css('packages/core/includes/js/jquery/keyboard/style.css');
		//$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/keyboard/keyboard.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.jcarousel.min.js');
		//
		$this->link_js('packages/hotel/packages/karaoke/includes/js/update_price_new.js');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.cookie.js');
		$this->link_js('packages/core/includes/js/jquery/paging/easypaginate.js');
		$this->link_js('packages/hotel/packages/karaoke/modules/KaraokeTouch/karaoke_reservation.js');
		$this->link_js('packages/core/includes/js/jquery/window/jquery.window.js');
		//Dung cho folio
		$this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
        $this->link_js('packages/hotel/packages/reception/modules/includes/common01.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");	
			  
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		//end
		$this->link_css('packages/hotel/skins/default/css/karaoke.css');
		$this->link_css('packages/hotel/skins/default/css/jcarosel.css');
		
		$this->link_js('cache/data/'.str_replace('#','',PORTAL_ID).'/'.Session::get('dp_code').'_'.str_replace('#','',PORTAL_ID).'.js?v='.time());
		$this->add('arrival_date',new DateType(true,'invalid_arrival_time'));
		$this->full_rate = 0;
		$this->full_charge= 0;		
	}
	function on_submit()
    {
        //System::debug($_REQUEST);exit();
        KaraokeTouch::print_to_kitchen('kitchen');
	    KaraokeTouch::print_to_kitchen('karaoke');
		if(Url::get('act') != '' || Url::get('acction') == 1 || Url::get('cancel'))
        {
		    require_once 'packages/hotel/packages/karaoke/includes/table.php';
			if($this->check())
            {
				if(Url::get('act')=='checkin' and Date_Time::to_time(Url::get('arrival_time'))>Date_Time::to_time(date('d/m/Y')))
				{
					//$this->error('arrival_time','arrival_time_is_small_current');
					//return;
				}
                else
                {
					$merge = false;
					$log_product = '';
					$log_table = '';
					$check_out = false;
					$ids = '';
					$status = (Url::get('act')=='checkin')?'CHECKIN':((Url::get('act')=='booked')?'BOOKED':(Url::get('status')?Url::get('status'):'')); 
					if(Url::get('act')=='submit_invoice'){
						$status = 'CHECKIN';	
					}
					if(Url::get('product_list')){				
						$karaoke_reservation_products = array();
						$karaoke_reservation_products = Url::get('product_list');
						foreach($karaoke_reservation_products as $k => $p){
							$ids .= ($ids=='')?$p['price_id']:(','.$p['price_id']);
						}
					$products_select = KaraokeTouchDB::get_product_select($ids);
					}/*else if($status !='BOOKED' && !Url::get('cancel')){//Url::get('act')!='booked'
						$this->error('items_id','you_did_not_select_food_or_drink_yet');
						return;
					}*/
					if(!Url::get('table_list')){
						$this->error('items_id','you_did_not_select_table_yet');
						return;	
					}
					//System::debug($karaoke_reservation_products); exit();
					$array = array();
					if(Url::get('cancel'))
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
                    $amount_pay_with_room = 0;
                    $amount_part_payment=0;
                    if(Url::get('id'))
                    {
                        $amount_part_payment = DB::fetch('select sum(amount) as amount from payment where bill_id='.Url::get('id').' and type=\'KARAOKE\' ','amount');
                        $total = DB::fetch('select total from karaoke_reservation where id='.Url::get('id'),'total'); 
                        if(Url::get('pay_with_room')) 
                            $amount_pay_with_room = $total - $amount_part_payment;
                    }
                    
					$array += array(	
							'reservation_room_id'=>(Url::get('pay_with_room'))?Url::get('reservation_room_id'):0,
                            'total_payment_traveller'=>(Url::get('total_payment_traveller'))?System::calculate_number(Url::get('total_payment_traveller')):0,	
							'room_id'=>(Url::get('payment_result')=='ROOM')?Url::get('room_id'):0,	
							'reservation_traveller_id'=>(Url::get('reservation_traveller_id'))?Url::get('reservation_traveller_id'):0,					
							'arrival_time'=>(Url::get('act')=='checkin')?time():Date_Time::to_time(Url::get('arrival_date'))+(Url::get('arrival_time_in_hour')*3600)+(Url::get('arrival_time_in_munite')*60),
							'departure_time'=>(Url::get('act')=='checkout')?time():Date_Time::to_time(Url::get('arrival_date'))+(Url::get('arrival_time_out_hour')*3600)+(Url::get('arrival_time_out_munite')*60),
							'status'=>$status, 
							'pay_with_room'=>(Url::get('pay_with_room'))?1:0,
							'full_rate'=>Url::get('input_full_rate')?Url::get('input_full_rate'):0,
							'full_charge'=>Url::get('input_full_charge')?Url::get('input_full_charge'):0,
							'note'=>Url::get('note')?Url::get('note'):'', 
							'num_table'=>Url::get('num_table')?Url::get('num_table'):1,
							'customer_id'=>Url::get('customer_id')?Url::get('customer_id'):'',
							'agent_name'=>Url::get('customer_id')?DB::fetch('select id,name from customer where id = '.Url::get('customer_id').'','name'):Url::get('customer_name'), 
							'receiver_name'=>Url::get('receiver_name'),
							'reservation_traveller_id'=>Url::get('reservation_traveller_id'),
							'tax_rate'=>Url::get('tax_rate')?Url::get('tax_rate'):0, 
							'foc'=>Url::get('foc')?Url::get('foc'):0, 
							'karaoke_fee_rate'=>Url::get('service_charge')?Url::get('service_charge'):0,
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
                            'amount_pay_with_room' =>(Url::get('pay_with_room'))?(System::calculate_number(Url::get('total_payment'))-$amount_part_payment):0
							//'date_used'=>Date_Time::to_orc_date(Url::get('arrival_date'))
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
					$surcharges = DB::fetch_all('select karaoke_charge.karaoke_id_from as id,karaoke_charge.percent from karaoke_charge where karaoke_id = '.Session::get('karaoke_id').' AND portal_id = \''.PORTAL_ID.'\'');
					if($row = $this->check_edit())
                    { 
						if((Url::get('act')!='') || (Url::get('acction')==1) || Url::get('cancel'))
                        {
							$karaoke_reservation_id = $row['id'];
							$code = '';
							$leng = strlen($karaoke_reservation_id);
							for($j=0;$j<6-$leng;$j++)
                            {
								$code .= '0';	
							}
							$code = date('Y').'-'.$code.$karaoke_reservation_id;
							$array += array('code'=>$code);
							$table_id = 0;
							if(Url::get('table_list'))
                            {
								$table_old = DB::fetch_all('SELECT 
                                                            table_id as id,
                                                            karaoke_table.name as table_name 
                                                        FROM 
                                                            karaoke_reservation_table
                                                            INNER JOIN karaoke_table ON karaoke_table.id = karaoke_reservation_table.table_id 
                                                        WHERE 
                                                            karaoke_reservation_id ='.$karaoke_reservation_id);
								$table_list = Url::get('table_list');
                                foreach($table_list as $key_table=>$detail)
                                {
                                    $table_detail = DB::fetch("select * from karaoke_reservation_table where id=".$detail['karaoke_reservation_table_id']);
                                    $table_list[$key_table]['num_people'] = $table_detail['num_people'];
                                    $table_list[$key_table]['order_person'] = $table_detail['order_person'];
                                    $table_list[$key_table]['price'] = $table_detail['price'];
                                    $table_list[$key_table]['sing_start_time'] = $table_detail['sing_start_time'];
                                    $table_list[$key_table]['sing_end_time'] = $table_detail['sing_end_time'];
                                }
								$conflix = Table::check_table_conflict($array['arrival_time'],$array['departure_time'],$table_list);
								//System::debug($table_list);
                                $table_id_list = '0';
								foreach($table_list as $tb => $table)
                                {
									$table_id_list .= ','.$table['table_id'];
									$table_id = $table['table_id'];
								}
                                
								foreach($table_list as $tb => $table)
                                {
									DB::delete('karaoke_reservation_table',' karaoke_reservation_id='.$karaoke_reservation_id.' AND table_id not in ('.$table_id_list.')');
									if(isset($table_old[$table['table_id']]['id']))
                                    {
                                        $table['num_people'] = Url::get('num_people');
                                        $table['order_person'] = Url::get('order_person');
										DB::update('karaoke_reservation',$array,'id = '.$karaoke_reservation_id.'');	
										$log_table .= 'Change Table: '.$table['name'].': SL :'.(isset($table['num_people'])?$table['num_people']:'').': Order: '.(isset($table['order_person'])?$table['order_person']:'').'<br> ';
										unset($table['name']);
                                        if(Url::get('act')=='checkin')
                                        {	
                    						$table['sing_start_time'] = Date_Time::to_time(date('d/m/Y')) + $this->calc_time(date('H:i'));
                    					}
                    					if(Url::get('act')=='checkout')
                                        {
                                            $set_checkout = DB::fetch("select sing_end_time from karaoke_reservation_table where id=".$table['karaoke_reservation_table_id'],'sing_end_time');
                                            if($set_checkout=='')
                    						  $table['sing_end_time'] = Date_Time::to_time(date('d/m/Y')) + $this->calc_time(date('H:i'));
                    					}
                                        //System::debug($_REQUEST['sing_room']);
                                        $table['price'] = System::calculate_number($_REQUEST['sing_room'][$table['karaoke_reservation_table_id']]['price']);
                                        $karaoke_reservation_table_id = $table['karaoke_reservation_table_id'];
                                        unset($table['karaoke_reservation_table_id']);
										//echo $table['price']; exit();
                                        DB::update('karaoke_reservation_table',$table,' id ='.$karaoke_reservation_table_id);
									}
                                    else
                                    {// TH ghép bàn, ghép hóa đơn nếu chuyển đến bàn đang CHECKIN
										$cond_conflix = 'karaoke_reservation_table.table_id=\''.$table['table_id'].'\' AND karaoke_reservation.id<>'.Url::iget('id').' AND (karaoke_reservation.status=\''.$status.'\')';
                                        if($row['time_in']!='' AND $row['time_out']!='')
                                            $cond_conflix .= 'AND ((karaoke_reservation.time_in <'.$row['time_out'].' AND karaoke_reservation.time_out > '.$row['time_in'].'))';
                                        $conflict= KaraokeTouchDB::get_table_conflict($cond_conflix);
										if(!empty($conflict))
                                        {
											$merge = true;				
											DB::delete('karaoke_reservation',' id='.$karaoke_reservation_id.'');
											DB::delete('karaoke_reservation_table',' karaoke_reservation_id='.$karaoke_reservation_id.'');
											DB::delete('karaoke_reservation_product',' karaoke_reservation_id='.$karaoke_reservation_id.'');
											//$log_table .= ' Merge Table: from table:'.$table_old[]['table_name'].' to table: '.$table_new['name'].'';
											$log_table .= ' Merge Bill: from Bill:'.$karaoke_reservation_id.' to Bill: '.$conflict['id'].'';
											$karaoke_reservation_id = $conflict['id']; // Gán id cho bàn mới và bỏ bàn cũ.									
										}
                                        else
                                        {
											if(!isset($table['num_people']) AND !is_numeric($table['num_people']))
                                            {
												$table['num_people'] = 5;
											}
											unset($table['name']);
											$table['karaoke_reservation_id'] = $karaoke_reservation_id;
											DB::update('karaoke_reservation',$array,'id = '.$karaoke_reservation_id.'');
                                            //System::debug($table); exit();
                                            $table['price'] = DB::fetch("SELECT price from karaoke_table where id=".$table['table_id'],'price');
                                            unset($table['karaoke_reservation_table_id']);
											DB::insert('karaoke_reservation_table',$table);	
											$log_table .= ' Change Table: from table:'.$table_old['table_name'].' to table: '.$table_new['name'].'';
										}
									}
								}
                                
							}
                            else
                            {
                                
								$this->error('table_list',Portal::language('do_not_select_table'));	
							}
							$old_product_ids = KaraokeTouchDB::get_old_product($karaoke_reservation_id); // Lay ra nhung san pham cua hoa don ghep
							
                            $j=0;$x=0;
							$arr_price_ids = '(\'0\'';
							foreach($karaoke_reservation_products as $k =>$value)
                            {
								$arr_price_ids .= ','.'\''.(($value['brp_id']!='')?($value['brp_id']):0).'\'';
								$j++;
							}
                            $arr_price_ids .= ')';
							if($merge==false)
                            {
								DB::delete('karaoke_reservation_product','karaoke_reservation_id = '.$karaoke_reservation_id.' AND id not in '.$arr_price_ids.''); 
							}
							foreach($karaoke_reservation_products as $id =>$value)
                            {
								$remaiin = 0;
								$data['karaoke_reservation_id'] = $karaoke_reservation_id;
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
								$data['karaoke_id'] = $value['karaoke_id'];
								$data['add_charge'] = 0;
								if(isset($surcharges[$data['karaoke_id']]) && $data['karaoke_id'] != Session::get('karaoke_id'))
                                {
									$data['add_charge'] = $surcharges[$data['karaoke_id']]['percent']; 	
								}
								if(isset($products_select[$data['price_id']]))
                                {
									$data['product_price'] = $products_select[$data['price_id']]['price'];	
								}
								$prd_id = $value['brp_id'];
								if($merge==true)
                                {// TH ghép bàn, ghép với sp của bàn mới nếu 2 bàn có chung sp	
									if(isset($old_product_ids[$id]))
                                    {
										$data['quantity'] += $old_product_ids[$id]['quantity']; 
										$data['quantity_discount'] += $old_product_ids[$id]['quantity_discount']; 
										$data['printed'] += $old_product_ids[$id]['printed'];
										$data['note'] = $old_product_ids[$id]['note'].''.$data['note']; 
										$data['remain'] = $value['remain'];
										$karaoke_reservation_product_id = DB::update('karaoke_reservation_product',$data,'karaoke_reservation_id = '.$karaoke_reservation_id.' AND id = '.$old_product_ids[$id]['prd_id'].'');
										$log_product .= 'Change Product: '.$data['product_id'].': SL :'.$data['quantity'].': Trả Lại: '.$data['remain'].': Note: '.$data['note'].'<br> ';
									}
                                    else
                                    {	
										$karaoke_reservation_product_id = DB::insert('karaoke_reservation_product',$data);	
										$log_product .= 'Insert Product: '.$data['product_id'].': SL :'.$data['quantity'].': Trả Lại: '.$data['remain'].': Note: '.$data['note'].'<br> ';		
									}
								}
                                else
                                {
									if(isset($old_product_ids[$id]['id']))
                                    {	
										$remaiin = DB::fetch('select remain from karaoke_reservation_product where karaoke_reservation_id = '.$karaoke_reservation_id.' AND id = '.$prd_id.'','remain');
										$data['remain'] = $value['remain']; 
										$karaoke_reservation_product_id = DB::update('karaoke_reservation_product',$data,'karaoke_reservation_id = '.$karaoke_reservation_id.' AND id = '.$prd_id.'');
										$log_product .= 'Change Product: '.$data['product_id'].': SL :'.$data['quantity'].': Trả Lại: '.$data['remain'].': Note: '.$data['note'].'<br> ';
									}
                                    else
                                    {
										$karaoke_reservation_product_id = DB::insert('karaoke_reservation_product',$data);	
										$log_product .= 'Insert Product: '.$data['product_id'].': SL :'.$data['quantity'].': Trả Lại: '.$data['remain'].': Note: '.$data['note'].'<br> ';		
									}
								}   
							}   
							if($merge == true)
                            { // update lai tong tien 
								Table::updateTotalKaraoke($karaoke_reservation_id);
							}
						}
						
						//exit();
						// Print to karaoke + kitchen
						$title = 'Edit karaoke reservation , Code: '.$row['id'].', Status: ' .$status.'';
						$description = ''
						.Portal::language('arrival_time').':'.URL::get('arrival_time').'<br>  ' 
						.Portal::language('departure_time').':'.Url::get('departure_time').'<br>  ' 
						.Portal::language('time').':'.Url::get('time').'<br>  ' 
						.Portal::language('agent_phone').':'.Url::get('agent_phone').'<br>  ' 
						.Portal::language('deposit').':'.Url::get('deposit').'<br>'
						.Portal::language('total').':'.Url::get('total').'<br> ' 
						.Portal::language('note').':'.Url::get('note').'<br>  ' 
						.Portal::language('payment_type_id').':'.URL::get('payment_type_id').'<br>  '
						.'<hr>'.$log_table.'<hr>'.$log_product.'';
						System::log('edit',$title,$description,$row['id']);
						//==================================IN VÀ CHECKOUT==============================
						
						if($status == 'CHECKOUT' || Url::get('act') =='checkout')
                        {// Xuất sản phẩm
							require_once 'packages/hotel/includes/php/product.php';
							$karaoke = DB::fetch('select * from karaoke where karaoke.id=\''.$row['karaoke_id'].'\'');
                            
                            //1 nha hang co 2 kho, lay tu bang portal_department
                            $warehouse = DB::fetch('select warehouse_id, warehouse_id_2 from portal_department where portal_id = \''.PORTAL_ID.'\' and department_code = \''.$karaoke['department_id'].'\' ');
                            //DeliveryOrders::get_delivery_orders(Url::iget('id'),$karaoke['department_id'],$warehouse['warehouse_id'],$warehouse['warehouse_id_2']);	
                            DeliveryOrders::get_delivery_orders(Url::iget('id'),'KARAOKE',$warehouse['warehouse_id'],$warehouse['warehouse_id_2']);	
						}
						if(Url::get('act')=='checkout')
                        {
							//KaraokeTouchDB::checkPayment($karaoke_reservation_id);	
                            $this->check_complete_payment($this->check_edit());
						}
						$group_table = DB::fetch('select LOWER(FN_CONVERT_TO_VN(table_group)) as table_group from karaoke_table where id='.$table_id.'','table_group');
						$group_table = str_replace(' ','_',$group_table);
						if($status = 'CHECKOUT' and Url::get('act') =='checkout')
                        {
						  

                        //exit();
                        
                        echo 
                        '<script>
    						'.('window.open("'.Url::build_current(array('cmd'=>'detail',md5('act')=>md5('print'),'method'=>'print_direct','id')).'");').'
    						if(window.opener)
    						{
    							window.opener.history.go(0);
    							'.($check_out?'window.close();':'').'
    						}
    						'.($check_out?'window.location="'.Url::build('karaoke_table_map',array('karaoke_id'=>Session::get('karaoke_id'),'group'=>$group_table)).'";':'window.setTimeout("location=\''.Url::build('karaoke_touch',array('cmd','id'=>Url::get('id'))).'\'",3000);').'
						</script>';
						exit();	
						}
					}
                    else
                    {
						if(Url::get('table_id'))
                        { // Check conflix
							$karaoke_reservation_id = 0;
							$table_list = Url::get('table_list'); 
							foreach($table_list as $i => $tbl){
								$table_list[$i]['id'] = $i;	
							}
							$conflix = Table::check_table_conflict($array['arrival_time'],$array['departure_time'],$table_list);
							if($conflix[Url::get('table_id')]!=false)
                            {
								$this->error('table_id',Portal::language('table_code').' '.Url::get('table_id').' '.Portal::language('conflict_of_time_to_reservation').' <a target="blank" href="?page=karaoke_touch&cmd=edit&id='.$conflix[Url::get('table_id')].'#'.$conflix[Url::get('table_id')].'">#'.$conflix[Url::get('table_id')].'</a>',false);
								return;
							}
                            else
                            {
								$karaoke_reservation_id = DB::insert('karaoke_reservation',$array+array('time'=>time(),'karaoke_id'=>Session::get('karaoke_id'),'portal_id'=>PORTAL_ID));
								$_REQUEST['id'] = $karaoke_reservation_id;
								$code = $karaoke_reservation_id;
								$code = '';
								$leng = strlen($karaoke_reservation_id);
								for($j=0;$j<6-$leng;$j++)
                                {
									$code .= '0';	
								}
								$code = date('Y').'-'.$code.$karaoke_reservation_id;//Session::get('karaoke_code')
								$table_id = '';
								DB::update('karaoke_reservation',array('code'=>$code),'id='.$karaoke_reservation_id);
									if(Url::get('table_list')){
										$table = Url::get('table_list');
										foreach($table as $key=>$tbl)
                                        {
											$tbl['karaoke_reservation_id'] =$karaoke_reservation_id;
											unset($tbl['name']);    
                                            if(Url::get('act')=='checkin')
                                            {	
                        						$tbl['sing_start_time'] = Date_Time::to_time(date('d/m/Y')) + $this->calc_time(date('H:i'));
                        					}
                                            $tbl['price'] = DB::fetch("SELECT price from karaoke_table where id=".$tbl['table_id'],'price');
											unset($tbl['karaoke_reservation_table_id']);
                                            DB::insert('karaoke_reservation_table',$tbl);		
											$table_id = $tbl['table_id'];
											$log_table .= 'Add Table: '.$tbl['table_id'].'<br>';
										}
									}
									foreach($karaoke_reservation_products as $id =>$value)
                                    {
										$data['karaoke_reservation_id'] = $karaoke_reservation_id;
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
										$data['karaoke_id'] = $value['karaoke_id'];
										$data['add_charge'] = 0;
										if(isset($surcharges[$data['karaoke_id']]) && $data['karaoke_id'] != Session::get('karaoke_id'))
                                        {
											$data['add_charge'] = $surcharges[$data['karaoke_id']]['percent']; 	
										}
										if(isset($products_select[$data['price_id']]))
                                        {
											$data['product_price'] = $products_select[$data['price_id']]['price'];	
										}
										$karaoke_reservation_product_id = DB::insert('karaoke_reservation_product',$data);
										$log_product .= 'Insert Product: '.$data['product_id'].': SL : '.$data['quantity'].'<br> ';
									}
									//exit();
									$title = 'Add karaoke reservation , Code: '.$karaoke_reservation_id.', Status: ' .$status.'';
									$description = ''
									.Portal::language('arrival_time').':'.URL::get('arrival_time').'<br>  ' 
									.Portal::language('departure_time').':'.Url::get('departure_time').'<br>  ' 
									.Portal::language('time').':'.Url::get('time').'<br>  ' 
									.Portal::language('agent_phone').':'.Url::get('agent_phone').'<br>  ' 
									.Portal::language('deposit').':'.Url::get('deposit').'<br>'
									.Portal::language('total').':'.Url::get('total').'<br> ' 
									.Portal::language('note').':'.Url::get('note').'<br>  ' 
									.Portal::language('payment_type_id').':'.URL::get('payment_type_id').'<br>  '
									.'<hr>'.$log_table.'<hr>'.$log_product.'';
									System::log('add',$title,$description,$karaoke_reservation_id);
								}
							}
						}
					}
				}
				
				KaraokeTouch::print_to_kitchen('kitchen');
				KaraokeTouch::print_to_kitchen('karaoke');
				//echo 'thuy'.Session::get('karaoke_id'); exit();
				//exit();
				
				if(Url::get('act')=='payment')
                { // Khi chọn nút thanh toán thì Save món trước khi gọi module thanh toán
					//Url::redirect_current(array('karaoke_id'=>$_SESSION['karaoke_id'],'cmd','arrival_time','id','act'=>'payment','total_amount'=>System::calculate_number(Url::get('total_payment'))));
					//echo '<script src="packages/core/includes/js/jquery/jquery.min.1.7.1.js"></script>';
                    $total = Url::get('total_payment')?System::calculate_number(Url::get('total_payment')):0;
                    $sing_room = DB::fetch_all("select karaoke_reservation_table.*, karaoke_table.name
                            from karaoke_reservation_table 
                                inner join karaoke_table on karaoke_table.id = karaoke_reservation_table.table_id                                
                            where karaoke_reservation_id = ".URL::get('id'));
                    foreach($sing_room as $id1=>$content1)
                    {
                        if($sing_room[$id1]['sing_start_time']!='' AND $sing_room[$id1]['sing_end_time']!='')
                        {    
                            $total += ($content1['price']/3600)*($sing_room[$id1]['sing_end_time']-$sing_room[$id1]['sing_start_time']);
                        }
                    }
                    $member_code = Url::get('member_code')?Url::get('member_code'):'';
					echo '<script src="packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js"></script>';
					echo '<script src="packages/hotel/packages/reception/modules/includes/common01.js"></script>';
					echo '<LINK rel="stylesheet" href="packages/hotel/skins/default/css/jquery.windows-engine.css" type="text/css">';
					$tt = 'form.php?block_id='.BLOCK_PAYMENT.'&member_code='.$member_code.'&cmd=payment&id='.Url::get('id').'&type=KARAOKE&total_amount='.$total;
					//echo '<script>openWindowUrl(\''.$tt.'\',Array(\'payment\',\'payment_for\',80,210,950,500));</script>';
                    echo '<script>window.location.href = \''.$tt.'\'</script>'; 
					
				}
                else if(Url::get('act')=='submit_invoice' && $karaoke_reservation_id>0)
                {
					Url::redirect_current(array('karaoke_id'=>Session::get('karaoke_id'),'cmd'=>'detail',md5('act')=>md5('print'),md5('preview')=>1,'id'=>$karaoke_reservation_id));
				}
                else
                {
					$group_table = '';
					if($table_id !='' && $table_id>0)
                    {
						$group_table = DB::fetch('select LOWER(FN_CONVERT_TO_VN(table_group)) as table_group from karaoke_table where id='.$table_id.'','table_group');
						$group_table = str_replace(' ','_',$group_table);
					}
					if(Url::get('acction') == 0 || $merge==true){
						Url::redirect('karaoke_table_map',array('karaoke_id'=>Session::get('karaoke_id'),'group'=>$group_table));
					}
                    else
                    {
						Url::redirect_current(array('karaoke_id'=>Session::get('karaoke_id'),'cmd','arrival_time','id'));	
					}
				}
				//Url::redirect_current();
		}
	}
	function draw()
    {
		require_once 'packages/hotel/packages/karaoke/includes/table.php';
		require_once 'packages/hotel/includes/php/hotel.php';
		$this->cancel_book_expried();
		$karaoke_id =  Session::get('karaoke_id');		
		$_REQUEST['karaoke_id'] = Session::get('karaoke_id');	
		$this->full_rate=(Session::get('full_rate')?Session::get('full_rate'):0);
		$this->full_charge = Session::get('full_charge')?Session::get('full_charge'):0;
		$this->map = array();
		$categories = KaraokeTouchDB::select_list_other_category(Session::get('karaoke_id'));
		$food_categories = KaraokeTouchDB::select_list_food_category(Session::get('karaoke_id'));
		$table_list = array();
		//--------------------Hien thi theo tung Karaoke------------------------------------//
		//$cond_admin = Table::get_privilege_karaoke();
        //System::debug($categories);
		$karaokes = KaraokeTouchDB::get_total_karaokes();
		//=================================================================================//
		$k=0;
		$param = '';
		$amount = 0;$total_amount = 0;
		$karaoke_reservation = array();
		$tax_rate = Url::get('tax_rate');
		$service_charge = Url::get('service_charge');				
		//============================== currency ================================
		$curr = HOTEL_CURRENCY;
		//$currency = DB::select('currency','name=\''.$curr.'\'');
		//============================== karaoke_table ===============================
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
		if($row = $this->check_edit())
        {
			if($row['karaoke_fee_rate']==''){
				$row['karaoke_fee_rate']=0;
			}
			if($row['tax_rate']==''){
				$row['tax_rate']=0;
			}
			$this->full_rate = ($row['full_rate']=='')?0:$row['full_rate'];	
			$this->full_charge = ($row['full_charge']=='')?0:$row['full_charge'];	
			$table_list = DB::fetch_all('select brt.table_id as id,
												brt.num_people,
												brt.order_person,
                                                brt.sing_start_time,
                                                brt.price,
                                                brt.sing_end_time,
												karaoke_table.name as name,
                                                brt.id as karaoke_reservation_table_id,
                                                0 as total
										from karaoke_reservation_table brt
											inner join karaoke_reservation ON karaoke_reservation.id = brt.karaoke_reservation_id
											inner join karaoke_table ON karaoke_table.id = brt.table_id
										WHERE brt.karaoke_reservation_id = '.$row['id'].'');
			$sql = '
					SELECT 
						brp.id as id,DECODE(brp.name,null,product.name_'.Portal::language().',brp.name) as product_name, 
						unit.name_'.Portal::language().' as unit,brp.quantity, brp.price
						,((brp.price * (brp.quantity - brp.quantity_discount- brp.quantity_cancel - brp.remain)) - (brp.price * (brp.quantity - brp.quantity_discount- brp.quantity_cancel - brp.remain)*brp.discount_category/100) - ((brp.price * (brp.quantity - brp.quantity_discount- brp.quantity_cancel - brp.remain)*brp.discount_category/100)*brp.discount_rate/100)) as amount
						,brp.product_id
						,brp.quantity_discount as promotion, brp.discount_rate as percentage, brp.printed as printed,brp.unit_id
						,brp.remain,brp.status,brp.note,brp.price_id,brp.quantity_cancel,brp.discount_category
						,brp.quantity_discount,brp.discount_rate,brp.product_sign,brp.karaoke_id
					FROM 
						karaoke_reservation_product brp
						LEFT OUTER JOIN karaoke_reservation ON karaoke_reservation.id = brp.karaoke_reservation_id
						LEFT OUTER JOIN product ON product.id = brp.product_id
						LEFT OUTER JOIN product_price_list ON product_price_list.product_id = product.id
						LEFT OUTER JOIN unit ON unit.id = product.unit_id
					WHERE
						brp.karaoke_reservation_id = '.$row['id'].'
					ORDER BY
						brp.id ASC
			';
			$original_reservation_products = DB::fetch_all($sql);
            
            
			//System::debug($original_reservation_products);
			$i = 0;
			$items_ids = '';
			$arr = array();
			$quantity_product = 0;
			$discount_quantity_product = 0;
			
			$count = 0;
			foreach($original_reservation_products as $k =>$valu)
            {
				//if(isset($arr[$valu['price_id']]) && $valu['product_id']!='FOUTSIDE' && $valu['product_id']!='DOUTSIDE' && $valu['product_id']!='SOUTSIDE')
                if(isset($arr[$valu['price_id']]))
                {
					$amount = ($valu['price'] * ($valu['quantity'] - $valu['quantity_discount']));
					$arr[$valu['price_id']]['amount'] += ($amount - $amount*$valu['discount_category']/100 - (($amount - $amount*$valu['discount_category']/100)*$valu['discount_rate']/100));
					$arr[$valu['price_id']]['quantity'] += $valu['quantity'];
					$arr[$valu['price_id']]['promotion'] += $valu['promotion'];
					$arr[$valu['price_id']]['remain'] += $valu['remain'];
					$arr[$valu['price_id']]['quantity_cancel'] += $valu['quantity_cancel'];
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
						$price = $valu['price'];
						$amount = ($valu['price'] * ($valu['quantity'] - $valu['quantity_discount']));
						$arr[$valu['price_id'].'_'.$valu['id']]['amount'] = ($amount - $amount*$valu['discount_category']/100 - (($amount - $amount*$valu['discount_category']/100)*$valu['discount_rate']/100));
					}
                    else
                    {
						$arr[$valu['price_id']]['id'] = $valu['price_id'];
						$arr[$valu['price_id']]['name'] = $valu['product_name'];
						$arr[$valu['price_id']] += $valu;
						$arr[$valu['price_id']]['price'] = $valu['price'];
						$arr[$valu['price_id']]['brp_id'] = $valu['id'];
						$price = $valu['price'];
						$amount = ($valu['price'] * ($valu['quantity'] - $valu['quantity_discount']));
						$arr[$valu['price_id']]['amount'] = ($amount - $amount*$valu['discount_category']/100 - (($amount - $amount*$valu['discount_category']/100)*$valu['discount_rate']/100));
					}
					unset($valu['id']);
				}
			}
			if($row['customer_id'] != 0 && $row['customer_id'] != ''){
				$row['customer_name'] = DB::fetch('select name from customer where id = '.$row['customer_id'].'','name');
			}
			$row['total_amount'] = System::display_number($total_amount);
			$row['deposit'] = System::display_number($row['deposit']);
			$row['total_payment'] = System::display_number($row['total']);
			$row['total_amount'] = System::display_number($row['total_before_tax']);
			$row['service_charge'] = $row['karaoke_fee_rate'];
			if($row['reservation_room_id'] != ''){
				$rr_info = KaraokeTouchDB::get_rr_id($row['reservation_room_id']);
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
			if(!isset($row['vip_code'])){
				$row['vip_code'] = '';	
			}
			$row['discount_after_tax'] = ($row['discount_after_tax']=='')?0:$row['discount_after_tax']; 
			$b_r_table = DB::fetch_all('select karaoke_reservation_table.*,karaoke_table.name from karaoke_reservation_table  inner join karaoke_table on karaoke_table.id=karaoke_reservation_table.table_id where karaoke_reservation_id = '.$row['id'].'');
			$row['table_name'] = '';
			foreach($b_r_table as $br =>$table){
				$row['order_person'] = $table['order_person'];
				$row['num_people'] = $table['num_people'];
				$row['table_name'] .= ($row['table_name']=='')?$table['name']:(','.$table['name']);
			}
		}
        else
        {
			$rate = 0;$row['status'] = '';$row['remain'] = '';$row['total_payment'] = '';$row['total_amount'] = '';$row['deposit_date'] = '';$row['discount_percent'] = '';$row['discount'] = '';$row['deposit']=0;
			$row['vip_code'] = '';$row['num_table'] = '';$row['customer_name'] = '';$row['customer_id'] = '';$row['note'] = '';$row['receiver_name'] = '';
			$row['payment_result'] = '';$row['banquet_order_type'] = '';
            $row['tax_rate'] = (KARAOKE_TAX_RATE?KARAOKE_TAX_RATE:0); 
            $row['service_charge'] = (KARAOKE_SERVICE_CHARGE?KARAOKE_SERVICE_CHARGE:0);
			$row['order_person'] = '';$row['num_people'] = '';$row['room_id'] = '';$row['reservation_id'] = '';$row['room_name'] = '';	$row['reservation_name'] = '';$row['discount_after_tax'] = 0;$row['full_rate'] = 0;$row['full_charge'] = 0;
			$row['pay_with_room'] = '';$row['foc'] = '';
			$table = DB::fetch_all('select karaoke_table.*,\'\' as order_person from karaoke_table where id='.Url::iget('table_id'));
			foreach($table as $t => $tabl){
				$row['table_name'] = $tabl['name'];
                $table[$t]['karaoke_reservation_table_id'] = '';
			}
			$table_list = $table;
		}
        //System::debug($table_list);
        
		$this->map += $row;
		//------------------------------------------------------------------------------------------------------------------------------------
		$discount_percent = array();
		//---------------------Gi?m giá % dùng select_box----------------------------
		$param = '';
		$amount = 0;
		$list_products = KaraokeTouchDB::get_all_product();
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
		$table_map = KaraokeTouchDB::get_table_map();
		$this->map['karaokes'] = $karaokes;
		$categories_discount = KaraokeTouchDB::get_list_category_discount();
		$this->map['full_charge'] = $this->full_charge;
		$this->map['full_rate'] = $this->full_rate;
		$this->map['product_array'] = String::array2js($list_products);
		$this->map['products'] = $products;
		$this->map['categories'] = $categories;
		$this->map['food_categories'] = $food_categories;
		$this->map['items'] = $param;
		$this->map['floors'] = $table_map;
		$this->map['categories_discount_js'] = String::array2js($categories_discount);
		$this->map['arrival_date'] = isset($row['arrival_time'])?date('d/m/Y',$row['arrival_time']):date('d/m/Y',time());
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
				$current_room = KaraokeTouchDB::get_room_guest($row['reservation_room_id']);
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
		$this->map['karaoke_name'] = DB::fetch('select name from karaoke where id='.Session::get('karaoke_id'),'name');
		if($row = $this->check_edit())
        {         
            $this->map['sing_room'] = DB::fetch_all("select karaoke_reservation_table.*, karaoke_table.name
                from karaoke_reservation_table 
                    inner join karaoke_table on karaoke_table.id = karaoke_reservation_table.table_id                                
                where karaoke_reservation_id = ".$row['id']);
            
            foreach($this->map['sing_room'] as $id1=>$content1)
            {
                $this->map['sing_room'][$id1]['sing_start_time'] = $content1['sing_start_time']!=''?date('H:i',$content1['sing_start_time']):'';
                $this->map['sing_room'][$id1]['sing_end_time'] = $content1['sing_end_time']!=''?date('H:i',$content1['sing_end_time']):'';
                $this->map['sing_room'][$id1]['total'] = '';
                $this->map['sing_room'][$id1]['total_time'] = 0;
                if($this->map['sing_room'][$id1]['sing_start_time']!='' AND $this->map['sing_room'][$id1]['sing_end_time']!='')
                {    
                    $this->map['sing_room'][$id1]['total'] = System::display_number(round(($content1['price']/3600)*($content1['sing_end_time']-$content1['sing_start_time']),0));
                    $this->map['sing_room'][$id1]['total_time'] = ($content1['sing_end_time']-$content1['sing_start_time']);
                }
                $this->map['sing_room'][$id1]['price'] = System::display_number($content1['price']);
            }
        }
        else
        {
            $this->map['sing_room'] = array();
                    
        }
        $this->map['sing_room_js'] = String::array2js($this->map['sing_room']);   
  
        //System::Debug($this->map);
		if(defined('IMENU') and IMENU)
		{
			$this->parse_layout('list_imenu',$this->map);  
		}
		else
		{  
			$this->parse_layout('list_denhat',$this->map);  
		}
	}	
	/*function get_item($items){
		$items_k = array();
		$item_detail = explode('{',Url::get('itemss_'.$items));
		preg_match_all("/'([^']*)':'([^']*)'/",Url::get('itemss_'.$items),$item);
		$items_k = $item[2];
		return $items_k;
	}*/
	function check_edit(){
		if(Url::get('cmd')=='edit' and Url::get('id') and $karaoke_reseration = DB::select('karaoke_reservation','id = '.Url::iget('id').'')){
			return $karaoke_reseration;
		}else{
			return false;
		}
	}
    
	function cancel_book_expried(){    
		DB::update('karaoke_reservation',array('status'=>'CANCEL','cancel_time'=>time()),' karaoke_reservation.departure_time < '.(time()-86400).' AND karaoke_reservation.status=\'BOOKED\'');	
	}
    
    function check_complete_payment($karaoke_reservation)
    {
        if($karaoke_reservation && $karaoke_reservation['total']>0)
        {
            $temp_row = '';
            $total_must_pay  = $karaoke_reservation['total'];
            $sing_room = array();
            $sing_room = DB::fetch_all("select karaoke_reservation_table.*, karaoke_table.name
                    from karaoke_reservation_table 
                        inner join karaoke_table on karaoke_table.id = karaoke_reservation_table.table_id                                
                    where karaoke_reservation_id = ".$karaoke_reservation['id']);
            foreach($sing_room as $id1=>$content1)
            {
                if($sing_room[$id1]['sing_start_time']!='' AND $sing_room[$id1]['sing_end_time']!='')
                {    
                    $total_must_pay += ($content1['price']/3600)*($sing_room[$id1]['sing_end_time']-$sing_room[$id1]['sing_start_time']);
                }
            }
            $total_paid  = 0;
            $payment_paid  = DB::fetch_all('Select * from payment where bill_id = '.$karaoke_reservation['id'].' and type = \'karaoke\' order by id desc');
            //System::debug($payment_paid);
            if($payment_paid)
            {
                //echo 'vao day roi';
                foreach($payment_paid as $k=>$v)
                {
                    if(!$temp_row)
                        $temp_row = $payment_paid[$k];
                    $total_paid += $v['amount'];
                }
                //echo $total_must_pay.'vao day roi<br />';
                //echo $total_paid.'vao day roi<br />';
                if($total_must_pay>$total_paid and $karaoke_reservation['pay_with_room']!=1)
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
            if($karaoke_reservation['pay_with_room']!=1)
            {
                $temp_row = array();
                $temp_row['bill_id'] = $karaoke_reservation['id'];
                $temp_row['type'] = 'KARAOKE';
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
    function calc_time($string)
    {
        $arr = explode(':',$string);
        //System::debug($arr);
        return $arr[0]*3600 + $arr[1]*60;
    }
}
?>