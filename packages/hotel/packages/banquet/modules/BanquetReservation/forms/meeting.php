<?php 
class Meeting extends Form
{
    function Meeting()
    {

        Form::Form('Meeting');
		$this->add('full_name',new TextType(true,'invalid_agent_name',1,255));
        $this->add('meeting_checkin_hour',new TextType(true,'meeting_checkin_hour',1,5));
        $this->add('meeting_checkout_hour',new TextType(true,'meeting_checkout_hour',1,5)); 
        $this->add('meeting_checkin_date',new TextType(true,'meeting_checkin_date',1,20)); 
		$this->link_css('skins/default/restaurant.css');		 
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/hotel/packages/restaurant/includes/js/update_price_party.js');
        @$this->link_js('cache/data/'.strtolower(str_replace('#','',PORTAL_ID)).'/BANQUET_default.js?v='.time());
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');   
    }
    function on_submit()
	{
		if($this->check())
		{
		    /** manh them de check trung dat phong **/
            $check = true;
            $messenge = '';
            $room_arr = array();
            
            if(isset($_REQUEST['mi_banquet_room']))
            {
                $checkin_time_ar = explode(':',Url::get('meeting_checkin_hour'));
                $checkout_time_ar = explode(':',Url::get('meeting_checkout_hour'));
                $checkin_time = Date_Time::to_time(Url::get('meeting_checkin_date'))+$checkin_time_ar[0]*3600+$checkin_time_ar[1]*60;
                $checkout_time = Date_Time::to_time(Url::get('meeting_checkin_date'))+$checkout_time_ar[0]*3600+$checkout_time_ar[1]*60;
                foreach($_REQUEST['mi_banquet_room'] as $key=>$value)
                {
                    if($value['id']!='' and DB::exists('select id from party_reservation_room where id='.$value['id']))
                    {
                        if(DB::exists('
                                        SELECT
                                            party_reservation_room.id
                                        FROM
                                            party_reservation_room
                                            inner join party_reservation on party_reservation.id=party_reservation_room.party_reservation_id
                                        WHERE
                                            party_reservation_room.party_room_id='.$value['banquet_room_id'].'
                                            and party_reservation.checkin_time<='.$checkout_time.'
                                            and party_reservation.checkout_time>='.$checkin_time.'
                                            and party_reservation_room.id!='.$value['id'].'
                                            and party_reservation.status!=\'CANCEL\'
                                        ') OR isset($room_arr[$value['banquet_room_id']]) )
                        {
                            $check = false;
                            $party_room_name = DB::fetch('select name from party_room where id='.$value['banquet_room_id'],'name');
                            if($messenge == '')
                                $messenge = Portal::language('room').' '.$party_room_name.' '.Portal::language('conflict');
                            else
                                $messenge .= '<br/>'.Portal::language('room').' '.$party_room_name.' '.Portal::language('conflict');
                        }
                    }
                    else
                    {
                        if(DB::exists('
                                        SELECT
                                            party_reservation_room.id
                                        FROM
                                            party_reservation_room
                                            inner join party_reservation on party_reservation.id=party_reservation_room.party_reservation_id
                                        WHERE
                                            party_reservation_room.party_room_id='.$value['banquet_room_id'].'
                                            and party_reservation.checkin_time<='.$checkout_time.'
                                            and party_reservation.checkout_time>='.$checkin_time.'
                                            and party_reservation.status!=\'CANCEL\'
                                        ') OR isset($room_arr[$value['banquet_room_id']]) )
                        {
                            $check = false;
                            $party_room_name = DB::fetch('select name from party_room where id='.$value['banquet_room_id'],'name');
                            if($messenge == '')
                                $messenge = Portal::language('room').' '.$party_room_name.' '.Portal::language('conflict');
                            else
                                $messenge .= '<br/>'.Portal::language('room').' '.$party_room_name.' '.Portal::language('conflict');
                        }
                    }
                    $room_arr[$value['banquet_room_id']] = $value['banquet_room_id'];
                }
            }
            $room_meeting_arr = array();
            if(isset($_REQUEST['mi_meeting_room']))
            {
                $checkin_time_ar = explode(':',Url::get('meeting_checkin_hour'));
                $checkout_time_ar = explode(':',Url::get('meeting_checkout_hour'));
                $checkin_time = Date_Time::to_time(Url::get('meeting_checkin_date'))+$checkin_time_ar[0]*3600+$checkin_time_ar[1]*60;
                $checkout_time = Date_Time::to_time(Url::get('meeting_checkin_date'))+$checkout_time_ar[0]*3600+$checkout_time_ar[1]*60;
                foreach($_REQUEST['mi_meeting_room'] as $key=>$value)
                {
                    if($value['id']!='' and DB::exists('select id from party_reservation_room where id='.$value['id']))
                    {
                        if(DB::exists('
                                        SELECT
                                            party_reservation_room.id
                                        FROM
                                            party_reservation_room
                                            inner join party_reservation on party_reservation.id=party_reservation_room.party_reservation_id
                                        WHERE
                                            party_reservation_room.party_room_id='.$value['meeting_room_id'].'
                                            and party_reservation.checkin_time<='.$checkout_time.'
                                            and party_reservation.checkout_time>='.$checkin_time.'
                                            and party_reservation_room.id!='.$value['id'].'
                                            and party_reservation.status!=\'CANCEL\'
                                        ') OR isset($room_meeting_arr[$value['meeting_room_id']]) )
                        {
                            $check = false;
                            $party_room_name = DB::fetch('select name from party_room where id='.$value['meeting_room_id'],'name');
                            if($messenge == '')
                                $messenge = Portal::language('room').' '.$party_room_name.' '.Portal::language('conflict');
                            else
                                $messenge .= '<br/>'.Portal::language('room').' '.$party_room_name.' '.Portal::language('conflict');
                        }
                    }
                    else
                    {
                        if(DB::exists('
                                        SELECT
                                            party_reservation_room.id
                                        FROM
                                            party_reservation_room
                                            inner join party_reservation on party_reservation.id=party_reservation_room.party_reservation_id
                                        WHERE
                                            party_reservation_room.party_room_id='.$value['meeting_room_id'].'
                                            and party_reservation.checkin_time<='.$checkout_time.'
                                            and party_reservation.checkout_time>='.$checkin_time.'
                                            and party_reservation.status!=\'CANCEL\'
                                        ') OR isset($room_meeting_arr[$value['meeting_room_id']]) )
                        {
                            $check = false;
                            $party_room_name = DB::fetch('select name from party_room where id='.$value['meeting_room_id'],'name');
                            if($messenge == '')
                                $messenge = Portal::language('room').' '.$party_room_name.' '.Portal::language('conflict');
                            else
                                $messenge .= '<br/>'.Portal::language('room').' '.$party_room_name.' '.Portal::language('conflict');
                        }
                    }
                    $room_meeting_arr[$value['meeting_room_id']] = $value['meeting_room_id'];
                }
            }
            if(!$check)
            {
                $this->error('room_conflict',$messenge);
                return false;
            }
            /** end manh **/
			$total = String::convert_to_vnnumeric(Url::get('sum_total'));
            $total_before_tax = System::calculate_number(Url::get('summary'));
			$break_coffee = String::convert_to_vnnumeric(Url::get('break_coffee'));
            $coffee_price = String::convert_to_vnnumeric(Url::get('coffee_price'));
            $water = String::convert_to_vnnumeric(Url::get('water'));
            $water_price = String::convert_to_vnnumeric(Url::get('water_price'));
            $deposit_1 = String::convert_to_vnnumeric(Url::get('deposit_1'));
            $deposit_2 = String::convert_to_vnnumeric(Url::get('deposit_2'));
            $deposit_3 = String::convert_to_vnnumeric(Url::get('deposit_3'));
            $deposit_4 = String::convert_to_vnnumeric(Url::get('deposit_4'));
            $deposit = String::convert_to_vnnumeric(Url::get('deposit'));
            $cashier_1 = Url::get('cashier_1');
            $cashier_2 = Url::get('cashier_2');
            $cashier_3 = Url::get('cashier_3');
            $cashier_4 = Url::get('cashier_4');
           	if (isset($_FILES['party_contact']) &&$_FILES['party_contact']['name'] != '')
            {
                require_once 'packages/core/includes/utils/upload_file.php';
                $contact_url = substr(update_upload_doc('party_contact','party_contact',array('pdf','xls','xlsx','doc','docx')),24);
                if(!$contact_url)
                {
                    $this->error('invalid_document_type','invalid_document_type');
                    return false;
                }
            }
            else $contact_url = Url::get_value('contact_url');
			$checkin_time_ar = explode(':',Url::get('meeting_checkin_hour'));
			$checkout_time_ar = explode(':',Url::get('meeting_checkout_hour'));
			$checkin_time = Date_Time::to_time(Url::get('meeting_checkin_date'))+$checkin_time_ar[0]*3600+$checkin_time_ar[1]*60;
			$checkout_time = Date_Time::to_time(Url::get('meeting_checkin_date'))+$checkout_time_ar[0]*3600+$checkout_time_ar[1]*60;			
			$meeting_checkin_time_ar = explode(':',Url::get('meeting_checkin_hour'));
			$meeting_checkout_time_ar = explode(':',Url::get('meeting_checkout_hour'));
			$meeting_checkin_time = Date_Time::to_time(Url::get('meeting_checkin_date'))+$meeting_checkin_time_ar[0]*3600+$meeting_checkin_time_ar[1]*60;
			$meeting_checkout_time = Date_Time::to_time(Url::get('meeting_checkin_date'))+$meeting_checkout_time_ar[0]*3600+$meeting_checkout_time_ar[1]*60;
		
            $party_info = array(
					'note', 
					'full_name',
					'address'=>Url::get('address'),
					'identity_number',
					'email',
                    'contract_code',
                    'representative_name',
                    'representative_phone',
                    'representative_address'=>Url::get('representative_address'),
                    'company_name',
                    'company_address'=>Url::get('company_address'),
                    'company_phone',
                    'contact_url'=>$contact_url,
                    'company_tax_code',
                    'position',
                    'representative_hotel',
                    'position_hotel',
                    'break_coffee'=>$break_coffee,
                    'coffee_price'=>$coffee_price,
                    'style_chairs',
                    'water'=>$water,
                    'water_price'=>$water_price,
                    'deposit_1'=>$deposit_1,
                    'deposit_2'=>$deposit_2,
                    'deposit_3'=>$deposit_3,
                    'deposit_4'=>$deposit_4,
                    'cashier_1'=>$cashier_1,
                    'cashier_2'=>$cashier_2,
                    'cashier_3'=>$cashier_3,
                    'cashier_4'=>$cashier_4,
                    'deposit_1_date'=>Date_Time::to_orc_date(Url::get('deposit_1_date')),
					'deposit_2_date'=>Date_Time::to_orc_date(Url::get('deposit_2_date')),
                    'deposit_3_date'=>Date_Time::to_orc_date(Url::get('deposit_3_date')),
                    'deposit_4_date'=>Date_Time::to_orc_date(Url::get('deposit_4_date')),
                    'table_reserve',
					'party_category',
					'home_phone',
					'party_type'=>3,
					'num_table',
					'num_people',
					'num_room',
					'room_name',
					'checkin_time'=>$checkin_time,
					'checkout_time'=>$checkout_time,
                    'meeting_checkin_hour'=>$meeting_checkin_time,
                    'meeting_checkout_hour'=>$meeting_checkout_time,
                    'meeting_num_people',
					'deposit'=>$deposit,
					'deposit_date'=>Date_Time::to_orc_date(Url::get('deposit_date')),
					'total'=>$total,
                    'total_before_tax'=>$total_before_tax,
					'lastest_edited_time'=>time(),
					'status',
					'vat',
					'extra_service_rate'=>Url::get('service_rate'),
					'price_per_people'=>System::calculate_number(Url::get('price_per_people')),
					'time_type',
					'bride_name',
					'groom_name',
                    'payment_info',
                    'portal_id'=>PORTAL_ID
				);
			if(Url::get('action')=='edit' and $row = DB::select_id('party_reservation',Url::iget('id')))
			{
			    if (Url::get_value('check_list'))
                {
                    //System::debug(Url::get_value('check_list'));exit();
                    $str_promotions = '';
                    foreach(Url::get_value('check_list') as $key=>$check)
                    {
                        $str_promotions .=$check.' ';
                    }
                    $party_info['promotions'] = $str_promotions;
                } 
				$party_id = $row['id'];
				DB::update('party_reservation',$party_info + array('lastest_edited_user_id'=>Session::get('user_id')),'id='.Url::iget('id'));
                if(Url::get('meeting_room_deleted_ids'))
				{
					if(strpos(URL::get('meeting_room_deleted_ids'),','))
					{
						DB::delete('party_reservation_room','id in ('.Url::get('meeting_room_deleted_ids').')');
					}
					else
					{
						DB::delete('party_reservation_room','id=\''.Url::get('meeting_room_deleted_ids').'\'');
					}
				}
				if (Url::get_value('check_list'))
                {
                    //System::debug(Url::get_value('check_list'));exit();
                    $str_promotions = '';
                    foreach(Url::get_value('check_list') as $key=>$check)
                    {
                        $str_promotions .=$check.' ';
                    }
                    $party_info['promotions'] = $str_promotions;
                } 
				$party_id = $row['id'];
				DB::update('party_reservation',$party_info + array('lastest_edited_user_id'=>Session::get('user_id')),'id='.Url::iget('id'));
				if(Url::get('meeting_room_deleted_ids'))
				{
					if(strpos(URL::get('meeting_room_deleted_ids'),','))
					{
						DB::delete('party_reservation_room','id in ('.Url::get('meeting_room_deleted_ids').')');
					}
					else
					{
						DB::delete('party_reservation_room','id=\''.Url::get('meeting_room_deleted_ids').'\'');
					}
				}
                if(Url::get('product_deleted_ids'))
				{
					if(strpos(URL::get('product_deleted_ids'),','))
					{
						DB::delete('party_reservation_detail','id in ('.Url::get('product_deleted_ids').')');
					}
					else
					{
						DB::delete('party_reservation_detail','id=\''.Url::get('product_deleted_ids').'\'');
					}
				}
                if(Url::get('eating_product_deleted_ids'))
				{
					if(strpos(URL::get('eating_product_deleted_ids'),','))
					{
						DB::delete('party_reservation_detail','id in ('.Url::get('eating_product_deleted_ids').')');
					}
					else
					{
						DB::delete('party_reservation_detail','id=\''.Url::get('eating_product_deleted_ids').'\'');
					}
				}
                if(Url::get('service_deleted_ids'))
				{
					if(strpos(URL::get('service_deleted_ids'),','))
					{
						DB::delete('party_reservation_detail','id in ('.Url::get('service_deleted_ids').')');
					}
					else
					{
						DB::delete('party_reservation_detail','id=\''.Url::get('service_deleted_ids').'\'');
					}
				}
				$list_product = '';
				if(isset($_REQUEST['mi_product']))
				{
				    //System::debug($_REQUEST['mi_product']); exit();
					foreach($_REQUEST['mi_product'] as $row=>$row_data)
					{
						$bar_product_id = $row_data['id'];
						unset($row_data['id']);
						$product['price'] = System::calculate_number($row_data['price']);
						$product['party_reservation_id'] = $party_id;
						$product['product_id'] = $row_data['product_id'];
                        if($row_data['product_id'] =='FOUTSIDE' || $row_data['product_id'] =='DOUTSIDE' || $row_data['product_id'] =='SOUTSIDE')
                        {
                            $product['product_name'] = $row_data['name'];
                            $product['product_unit'] = $row_data['unit'];
                            //echo 1;
                        }
						$product['quantity'] = System::calculate_number($row_data['quantity']);
                        $product['type'] = 1;
						$list_product .= 'Product: '.$product['product_id'].', Quantity: '.$product['quantity'].', Price: '.$product['price'].'<br>';
						if($bar_product_id and DB::select('party_reservation_detail','id='.$bar_product_id))
						{
							DB::update_id('party_reservation_detail',$product,$bar_product_id);
						}
						else
						{
							$id = DB::insert('party_reservation_detail',$product);
						}
                        unset($product);
					}
				}
                $list_services = '';
				if(isset($_REQUEST['mi_service']))
				{
					foreach($_REQUEST['mi_service'] as $row=>$row_data)
					{
						$bar_product_id = $row_data['id'];
						unset($row_data['id']);
						$product['price'] = System::calculate_number($row_data['price']);
						$product['party_reservation_id'] = $party_id;
						$product['product_id'] = $row_data['product_id'];
                        if($row_data['product_id'] =='FOUTSIDE' || $row_data['product_id'] =='DOUTSIDE' || $row_data['product_id'] =='SOUTSIDE')
                        {
                            $product['product_name'] = $row_data['name'];
                            $product['product_unit'] = $row_data['unit'];
                            //echo 1;
                        }
						$product['quantity'] = System::calculate_number($row_data['quantity']);
                        $product['type'] = 3;
						$list_services .= 'Product: '.$product['product_id'].', Quantity: '.$product['quantity'].', Price: '.$product['price'].'<br>';
						if($bar_product_id and DB::select('party_reservation_detail','id='.$bar_product_id))
						{
							DB::update_id('party_reservation_detail',$product,$bar_product_id);
						}
						else
						{
							$id = DB::insert('party_reservation_detail',$product);
						}
                        unset($product);
					}
				}
                $list_eating_product='';
                if(isset($_REQUEST['mi_eating_product']))
				{
					foreach($_REQUEST['mi_eating_product'] as $row=>$row_data)
					{
						$bar_product_id = $row_data['id'];
						unset($row_data['id']);
						$product['price'] = System::calculate_number($row_data['price']);
						$product['party_reservation_id'] = $party_id;
						$product['product_id'] = $row_data['product_id'];
                        if($row_data['product_id'] =='FOUTSIDE' || $row_data['product_id'] =='DOUTSIDE' || $row_data['product_id'] =='SOUTSIDE')
                        {
                            $product['product_name'] = $row_data['name'];
                            $product['product_unit'] = $row_data['unit'];
                            //echo 1;
                        }
						$product['quantity'] = System::calculate_number($row_data['quantity']);
                        $product['type'] = 2;
						$list_eating_product .= 'Product: '.$product['product_id'].', Quantity: '.$product['quantity'].', Price: '.$product['price'].'<br>';
						if($bar_product_id and DB::select('party_reservation_detail','id='.$bar_product_id))
						{
							DB::update_id('party_reservation_detail',$product,$bar_product_id);
						}
						else
						{
							$id = DB::insert('party_reservation_detail',$product);
						}
                        unset($product);
					}
				}
				if(Url::get('banquet_room_deleted_ids'))
				{
					if(strpos(URL::get('banquet_room_deleted_ids'),','))
					{
						DB::delete('party_reservation_room','id in ('.Url::get('banquet_room_deleted_ids').')');
					}
					else
					{
						DB::delete('party_reservation_room','id=\''.Url::get('banquet_room_deleted_ids').'\'');
					}
				}
				$list_banquet_room = '';
				if(isset($_REQUEST['mi_banquet_room']))
				{
				    //System::debug($_REQUEST['mi_banquet_room']);exit();
					foreach($_REQUEST['mi_banquet_room'] as $row=>$row_data)
					{
						$banquet_reservation_room_id = $row_data['id'];
						unset($row_data['id']);
						$banquet_room['price'] = System::calculate_number($row_data['total']);
						$banquet_room['party_reservation_id'] = $party_id;
						$banquet_room['party_room_id'] = $row_data['banquet_room_id'];
						//$banquet_room['time_type'] = $row_data['time_type'];
						$banquet_room['note'] = $row_data['note'];
                        $banquet_room['type'] = 2;
                        $banquet_room['address'] = $row_data['address'];
						$list_banquet_room .= 'Room: '.$banquet_room['party_room_id'].', Price: '.$banquet_room['price'].'<br>';
						if($banquet_reservation_room_id and DB::select('party_reservation_room','id='.$banquet_reservation_room_id))
						{
							DB::update_id('party_reservation_room',$banquet_room,$banquet_reservation_room_id);
						}
						else
						{
							$id = DB::insert('party_reservation_room',$banquet_room);
						}
					}
				}
				if($_REQUEST['status']=='CHECKOUT')
                {
                    /** Kimtan them tru kho cho dat tiec. **/
                    Url::iget('id');
                    require_once 'packages/hotel/includes/php/product.php';
                    $warehouse = DB::fetch('select warehouse_id, warehouse_id_2 from portal_department where portal_id = \''.PORTAL_ID.'\' and department_code = \'BANQUET\' '); 
                    if(isset($warehouse['warehouse_id']) or isset($warehouse['warehouse_id_2']))
                    {
                        DeliveryOrders::get_delivery_orders(Url::iget('id'),'BANQUET',$warehouse['warehouse_id'],$warehouse['warehouse_id_2']);	
                    }
                }
                $list_meeting_room = '';
				if(isset($_REQUEST['mi_meeting_room']))
				{ 
				    //System::debug($_REQUEST['mi_meeting_room']); exit();
					foreach($_REQUEST['mi_meeting_room'] as $row=>$row_data)
					{
						$banquet_reservation_room_id = $row_data['id'];
						$banquet_room['price'] = System::calculate_number($row_data['total']);
						$banquet_room['party_reservation_id'] = $party_id;
						$banquet_room['party_room_id'] = $row_data['meeting_room_id'];
						$banquet_room['time_type'] = $row_data['time_type'];
						$banquet_room['note'] = $row_data['note'];
                        $banquet_room['type'] = 1;
                        $banquet_room['address'] = $row_data['address'];
						if($banquet_reservation_room_id and DB::select('party_reservation_room','id='.$banquet_reservation_room_id))
						{
							DB::update_id('party_reservation_room',$banquet_room,$banquet_reservation_room_id);
						}
						else
						{
							$id = DB::insert('party_reservation_room',$banquet_room);
						}
					}
				}					
			}
			else
			{
                //System::debug($party_info); exit();
                if (Url::get_value('check_list'))
                {
                    //System::debug(Url::get_value('check_list'));exit();
                    $str_promotions = '';
                    foreach(Url::get_value('check_list') as $key=>$check)
                    {
                        $str_promotions .=$check.' ';
                    }
                    $party_info['promotions'] = $str_promotions;
                }
				$party_id = DB::insert('party_reservation',$party_info + array('time'=>time(),'user_id'=>Session::get('user_id')));
                $list_banquet_room = '';
				if(isset($_REQUEST['mi_banquet_room']))
				{
					foreach($_REQUEST['mi_banquet_room'] as $row=>$row_data)
					{
						$banquet_reservation_room_id = $row_data['id'];
						unset($row_data['id']);
						$banquet_room['price'] = System::calculate_number($row_data['total']);
						$banquet_room['party_reservation_id'] = $party_id;
						$banquet_room['party_room_id'] = $row_data['banquet_room_id'];
						$banquet_room['time_type'] = $row_data['time_type'];
						$banquet_room['note'] = $row_data['note'];
                        $banquet_room['address'] = $row_data['address'];
                        $banquet_room['type'] = 2;
						$list_banquet_room .= 'Product: '.$banquet_room['party_room_id'].', Price: '.$banquet_room['price'].'<br>';
						$id = DB::insert('party_reservation_room',$banquet_room);
					}
				}
               				
				if(isset($_REQUEST['mi_product']))
				{
					foreach($_REQUEST['mi_product'] as $row=>$row_data)
					{
						$bar_product_id = $row_data['id'];
						unset($row_data['id']);
						$product['price'] = System::calculate_number($row_data['price']);
						$product['party_reservation_id'] = $party_id;
						$product['product_id'] = $row_data['product_id'];
						$product['quantity'] = $row_data['quantity'];
                        $product['type'] = 1;
						$list_product .= 'Product: '.$product['product_id'].', Quantity: '.$product['quantity'].', Price: '.$product['price'].'<br>';
						$id = DB::insert('party_reservation_detail',$product);
					}
				}
                if(isset($_REQUEST['mi_eating_product']))
				{
					foreach($_REQUEST['mi_eating_product'] as $row=>$row_data)
					{
						$bar_product_id = $row_data['id'];
						unset($row_data['id']);
						$product['price'] = System::calculate_number($row_data['price']);
						$product['party_reservation_id'] = $party_id;
						$product['product_id'] = $row_data['product_id'];
						$product['quantity'] = $row_data['quantity'];
                        $product['type'] = 2;
						$list_product .= 'Product: '.$product['product_id'].', Quantity: '.$product['quantity'].', Price: '.$product['price'].'<br>';
						$id = DB::insert('party_reservation_detail',$product);
					}
				}
                if(isset($_REQUEST['mi_service']))
				{
					foreach($_REQUEST['mi_service'] as $row=>$row_data)
					{
						$bar_product_id = $row_data['id'];
						unset($row_data['id']);
						$product['price'] = System::calculate_number($row_data['price']);
						$product['party_reservation_id'] = $party_id;
						$product['product_id'] = $row_data['product_id'];
						$product['quantity'] = $row_data['quantity'];
                        $product['type'] = 3;
						$list_product .= 'Product: '.$product['product_id'].', Quantity: '.$product['quantity'].', Price: '.$product['price'].'<br>';
						$id = DB::insert('party_reservation_detail',$product);
					}
				}
                $list_meeting_room = '';
				if(isset($_REQUEST['mi_meeting_room']))
				{
				    //System::debug($_REQUEST['mi_meeting_room']);exit();
					foreach($_REQUEST['mi_meeting_room'] as $row=>$row_data)
					{
						$banquet_room['price'] = System::calculate_number($row_data['total']);
						$banquet_room['party_reservation_id'] = $party_id;
						$banquet_room['party_room_id'] = $row_data['meeting_room_id'];
						$banquet_room['time_type'] = $row_data['time_type'];
						$banquet_room['note'] = $row_data['note'];
                        $banquet_room['address'] = $row_data['address'];
                        $banquet_room['type'] = 1;
						$list_meeting_room .= 'Product: '.$banquet_room['party_room_id'].', Price: '.$banquet_room['price'].'<br>';
						$id = DB::insert('party_reservation_room',$banquet_room);
					}
				}				
				
			}
			$title = ''
				.substr(URL::get('code'),0,32).',  ' .substr(URL::get('status'),0,32).',  '     .substr(URL::get('time'),0,32).',  ';
			echo '<div id="progress"><img src="packages/core/skins/default/images/updating.gif" /> Updating table status to server...</div>';
			echo '<script>
			
			window.setTimeout("location=\''.Url::redirect_current(array('just_edited_id'=>$party_id)).'\'",1000);
			</script>';
			exit();
		}
	}	
	function draw()
	{	
		require_once 'packages/hotel/packages/restaurant/includes/table.php';
		require_once 'packages/hotel/includes/php/hotel.php';	
		if(Url::get('action')=='edit')	
		{
			$row = DB::select('party_reservation',Url::iget('id'));
			//System::Debug($row);
			$row['date'] = date('d/m/Y',$row['time']);
			$from_time = Date_Time::to_time(date('d/m/Y',time()));
			$to_time = Date_Time::to_time(date('d/m/Y',time()))+3600*24-1;
			//============================== product ===============================
  	         $product = BanquetReservationDB::get_reservation_product(1||2||3);
			$i=0;
			$total_product = sizeof($product);
			foreach($product as $k1=>$g)
			{
				$product[$k1]['stt']=$i++;
				if($i == $total_product)
				{
					$product[$k1]['last'] = 1;
				}
				else
				{
					$product[$k1]['last'] = 0;
				}
			}
            $product_items = BanquetReservationDB::get_reservation_product(1);
            $eating_product_items = BanquetReservationDB::get_reservation_product(2);
            $service_items = BanquetreservationDB::get_reservation_product(3);
			$category_items = BanquetreservationDB::get_reservation_category();
            $banquet_room_items = BanquetReservationDB::get_reservation_banquet_room(2);
            $meeting_room_items = BanquetreservationDB::get_reservation_banquet_room(1);
			$total_before_tax = 0;
			
			foreach($category_items as $key=>$value)
			{
				if(!isset($value['discount_rate']))
				{
					$category_items[$key]['discount_rate'] = 0;
				}
			}
            //System::debug($meeting_room_items);
            $_REQUEST['mi_product'] = $product_items;
            $_REQUEST['mi_service'] = $service_items;
            $_REQUEST['mi_eating_product'] = $eating_product_items;
			$temp = 0;
			$b = false;
			foreach($product_items as $key=>$value)
			{
				$_REQUEST['mi_product'][$key]['product_id'] = $value['product_id'];
				$_REQUEST['mi_product'][$key]['unit'] = $value['unit_name'];
				$_REQUEST['mi_product'][$key]['price'] = System::display_number($value['price']);
				$ttl = $value['price']*($value['quantity']);
				$_REQUEST['mi_product'][$key]['total'] = System::display_number($ttl);
				$total_before_tax += $ttl;
				$_REQUEST['mi_product'][$key]['quantity'] = round($value['quantity'],2);
				if(isset($category_items[$value['category_id']]))
				{
					if($temp and $category_items[$value['category_id']]['discount_rate'] == $temp)
					{
						$b = false;
					}
					else
					{
						$b = true;
						$temp = $category_items[$value['category_id']]['discount_rate'];
					}
					if($b)
					{
						$category_items[$value['category_id']]['discount_rate'] = $value['discount_rate'];
					}
				}
			}
            foreach($service_items as $key=>$value)
			{
				$_REQUEST['mi_service'][$key]['product_id'] = $value['product_id'];
				$_REQUEST['mi_service'][$key]['unit'] = $value['unit_name'];
				$_REQUEST['mi_service'][$key]['price'] = System::display_number($value['price']);
				$ttl = $value['price']*($value['quantity']);
				$_REQUEST['mi_service'][$key]['total'] = System::display_number($ttl);
				$total_before_tax += $ttl;
				$_REQUEST['mi_service'][$key]['quantity'] = round($value['quantity'],2);
				if(isset($category_items[$value['category_id']]))
				{
					if($temp and $category_items[$value['category_id']]['discount_rate'] == $temp)
					{
						$b = false;
					}
					else
					{
						$b = true;
						$temp = $category_items[$value['category_id']]['discount_rate'];
					}
					if($b)
					{
						$category_items[$value['category_id']]['discount_rate'] = $value['discount_rate'];
					}
				}
			}
            foreach($eating_product_items as $key=>$value)
			{
				$_REQUEST['mi_eating_product'][$key]['product_id'] = $value['product_id'];
				$_REQUEST['mi_eating_product'][$key]['unit'] = $value['unit_name'];
				$_REQUEST['mi_eating_product'][$key]['price'] = System::display_number($value['price']);
				$ttl = $value['price']*($value['quantity']);
				$_REQUEST['mi_eating_product'][$key]['total'] = System::display_number($ttl);
				$total_before_tax += $ttl;
				$_REQUEST['mi_eating_product'][$key]['quantity'] = round($value['quantity'],2);
				if(isset($category_items[$value['category_id']]))
				{
					if($temp and $value['discount_rate'] == $temp)
					{
						$b = false;
					}
					else
					{
						$b = true;
						$temp = $value['discount_rate'];
					}
					if($b)
					{
						$category_items[$value['category_id']]['discount_rate'] = $value['discount_rate'];
					}
				}
			}
			foreach($category_items as $key=>$value)
			{
				if(!isset($value['discount_rate']))
				{
					$category_items[$key]['discount_rate'] = 0;
				}
			}
			foreach($banquet_room_items as $key=>$value)
			{
				$total_before_tax += $value['total'];
				$banquet_room_items[$key]['total'] = System::display_number($value['total']);
			}
            foreach($meeting_room_items as $key=>$value)
			{
				$total_before_tax += $value['total'];
				$meeting_room_items[$key]['total'] = System::display_number($value['total']);
			}
            foreach($meeting_room_items as $key=>$value)
            {
                $_REQUEST['mi_meeting_room'][$key]['meeting_room_id'] = $value['banquet_room_id'];
                $_REQUEST['mi_meeting_room'][$key]['total'] = $value['total'];
                $_REQUEST['mi_meeting_room'][$key]['note'] = $value['note'];
                $_REQUEST['mi_meeting_room'][$key]['address'] = $value['address'];
                $_REQUEST['mi_meeting_room'][$key]['group_name'] = $value['group_name'];
                $_REQUEST['mi_meeting_room'][$key]['id'] = $value['id'];
            }
			$row['checkin_hour'] = $row['meeting_checkin_hour']?date('H:i',$row['meeting_checkin_hour']):date('H',time());
			$row['checkin_date'] =  date('d/m/Y',$row['meeting_checkin_hour']);
			$row['checkout_hour'] = date('H:i',$row['checkout_time']);
            $row['meeting_checkin_date'] =  date('d/m/Y',$row['meeting_checkin_hour']);
            $row['meeting_checkin_hour'] = $row['meeting_checkin_hour']?date('H:i',$row['meeting_checkin_hour']):date('H',time());
			$row['meeting_checkout_hour'] = date('H:i',$row['meeting_checkout_hour']);
			$deposit_date = '';
			$row['summary'] = System::display_number($total_before_tax);
			$row['service_rate'] = $row['extra_service_rate'];
			$row['service_total'] = System::display_number($total_before_tax*$row['extra_service_rate']/100);
			$total_before = $total_before_tax+$total_before_tax*$row['extra_service_rate']/100;
			$row['total_before_tax'] = System::display_number($total_before);
			
			$tax = $total_before*$row['vat']/100;
			$row['tax_total'] = System::display_number($tax);
			$row['sum_total'] = System::display_number($total_before+$tax);
			$row['deposit_1'] = System::display_number($row['deposit_1']);
            $row['deposit_2'] = System::display_number($row['deposit_2']);
            $row['deposit_3'] = System::display_number($row['deposit_3']);
            $row['deposit_4'] = System::display_number($row['deposit_4']);
            $row['cashier_1']=$row['cashier_1'];
            $row['cashier_2']=$row['cashier_2'];
            $row['cashier_3']=$row['cashier_3'];
            $row['cashier_4']=$row['cashier_4'];
			$row['price_per_people'] = System::display_number($row['price_per_people']);
            $row['deposit_1_date'] = Date_Time::convert_orc_date_to_date($row['deposit_1_date'],'/');
            $row['deposit_2_date'] = Date_Time::convert_orc_date_to_date($row['deposit_2_date'],'/');
            $row['deposit_3_date'] = Date_Time::convert_orc_date_to_date($row['deposit_3_date'],'/');
            $row['deposit_4_date'] = Date_Time::convert_orc_date_to_date($row['deposit_4_date'],'/');
            $coffee_total_money = $row['break_coffee']*$row['coffee_price'];
            $water_total_money = $row['water']*$row['water_price'];
            $row['break_coffee'] = System::display_number($row['break_coffee']);
            $row['coffee_price'] = System::display_number($row['coffee_price']);
            $_REQUEST['coffee_total_money']=System::display_number($coffee_total_money);
            $row['water'] = System::display_number($row['water']);
            $row['water_price'] = System::display_number($row['water_price']);
            $_REQUEST['water_total_money']=System::display_number($water_total_money);
			foreach($row as $key=>$value)
			{
				$_REQUEST[$key] = $value;
			}
			$this->map = $row;
		}
		else
		{
			$this->map = array();
			$this->map['date'] =  date('d/m/Y');
            $this->map['meeting_checkin_date'] = Url::get('meeting_checkin_date')?Url::get('meeting_checkin_date'):date('d/m/Y');
		}
		$banquet_rooms = $this->get_banquet_room();
		$banquet_room_options = '<option value="">'.Portal::language('choose_banquet_room').'</option>';
		foreach($banquet_rooms as $key=>$value)
		{
			$banquet_room_options.='<option value="'.$value['id'].'">'.$value['name'].'</option>';
		}
        $this->map['style_chairs'] = Url::get('style_chairs');
        $this->map['representative_hotel'] = Url::get('representative_hotel');
        $this->map['position_hotel'] = Url::get('position_hotel');
		$this->map['banquet_rooms'] = $banquet_rooms;
		$this->map['banquet_room_options'] = $banquet_room_options;
		$this->map['vat_list'] = array(0=>'0%',10=>'10%');
		$this->map['service_rate_list'] = array(0=>'0%',5=>'05%',10=>'10%',15=>'15%');
		$this->map['party_type_list'] = array(''=>Portal::language('Choose_party_type')) + String::get_list(DB::select_all('party_type','portal_id = \''.PORTAL_ID.'\' '));
		$this->map['status_list'] =  array(
			'BOOKED'=>Portal::language('booked'),
			'CHECKIN'=>Portal::language('checkin'),
			'CHECKOUT'=>Portal::language('checkout')
		
			);
        //''=>Portal::language('Choose_status'),
		$this->map['party_category_list'] = array(
			'ROOM_PRICE'=>Portal::language('room_price'),
			'FULL_PRICE'=>Portal::language('full_price')
		);
		$this->map['time_type_list'] = array(
    			'DAY'=>Portal::language('one_day'),
    			'MORNING'=>Portal::language('Morning'),
                'AFTERNOON'=>Portal::language('Afternoon')
    		);
		$promotions = DB::select_all('party_promotions','party_type_id = 3');
        $this->map['promotions'] = $promotions;
        $this->parse_layout('meeting',$this->map);
	}
	function get_banquet_room(){
		$sql = '
			select 
				party_room.id,
				party_room.name,
				party_room.group_name,
				party_room.price,
                party_room.address,
				party_room.price_half_day
			from 
				party_room
            where
                portal_id = \''.PORTAL_ID.'\'
			order by
				party_room.id
		';
		$banquet_rooms = DB::fetch_all($sql);
		return $banquet_rooms;
	}
}

?>
