<?php
class CheckInBarForm extends Form
{
	function CheckInBarForm()
	{
		Form::Form('CheckInBarForm');
		$this->add('table.table_id',new IDType(true,'miss_table','bar_table'));
		if($br = DB::fetch('select status from bar_reservation where id=\''.Url::iget('id').'\' and status in (\'CHECKIN\',\'CHECKOUT\')'))
		{
			if(($br['status']=='CHECKOUT' and !User::can_admin(false,ANY_CATEGORY))){
				Url::redirect_current(array('cmd'=>'detail','id',md5('act')=>md5('print'),md5('preview')=>1));
			}
			$this->add('customer_name',new TextType(false,'invalid_customer_name',0,255));
			$this->add('customer_id',new TextType(false,'invalid_customer_id',0,255));
			$this->add('receiver_name',new TextType(false,'invalid_receiver_name',0,255));
			$this->add('num_table',new IntType(false,'invalid_num_table','0','100000000000')); 
			$this->add('prepaid',new FloatType(false,'invalid_prepaid','0','100000000000'));
			if(Url::get('check_out')) 
			{
				$this->add('payment_result',new TextType(true,'invalid_payment_result',0,255));
			}	
			if(Url::get('check_out') and Url::get('payment_result')=='ROOM')
			{
				$this->add('reservation_id',new IDType(true,'room_id_is_required','reservation_room')); 				
			}
			$this->add('product.product_id',new IDType(true,'miss_product','res_product','code'));
			$this->add('product.name',new TextType(false,'miss_product_name',0,255)); 
			$this->add('product.unit',new TextType(false,'invalid_unit',0,255)); 
			$this->add('product.quantity',new FloatType(true,'invalid_quantity',0,10000));
			$this->add('product.quantity_discount',new FloatType(false,'invalid_quantity_discount',0,10000));
			$this->add('product.discount',new FloatType(false,'invalid_discount',0,10000000));
			$this->add('product.price',new FloatType(true,'invalid_price',0,10000000));
			$this->add('product.total',new FloatType(true,'invalid_total',0,1000000000));
			//$this->add('reservation_id',new IDType(false,'invalid_reservation_id','reservation')); 
			//$this->add('room_id',new IDType(false,'invalid_room_id','room')); 
			$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
			$this->link_css('skins/default/restaurant.css');
			$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
			$this->link_js('packages/core/includes/js/multi_items.js');
			$this->link_js('packages/hotel/packages/restaurant/includes/js/update_price_new.js');
		}else{
			Url::redirect_current(array('cmd'=>'edit','id'));
		}
	}
	function on_submit()
	{
		if($this->check())
		{
			$error = false;
			if(!User::is_admin() and Url::get('status')=='CHECKOUT'){
				if(Url::get('password') and User::can_admin(false,ANY_CATEGORY)){
					$user_id = Session::get('user_id');
					if(!$row=DB::fetch('select account.*,party.email,party.name_'.Portal::language().' as full_name from account inner join party on party.user_id=account.id where account.id=\''.$user_id.'\' and party.type=\'USER\' and password=\''.User::encode_password($_REQUEST['password']).'\' and account.is_active=1'))
					{
						$this->error('password','invalid_password');
						$error = true;
					}
				}else{
					$this->error('password','invalid_password');
					$error = true;
				}
			}
			if(!isset($_REQUEST['mi_product']) and !Url::get('note')){
				$this->error('note','there_no_product_selected_please_input_note_for_the_reason');
				return;
			}
			{
				$discount = String::convert_to_vnnumeric(Url::get('discount'));
				$tax = String::convert_to_vnnumeric(Url::get('tax'));
				$total_before_tax = String::convert_to_vnnumeric(Url::get('total_before_tax'));
				$total = String::convert_to_vnnumeric(Url::get('sum_total'));
				$prepaid = String::convert_to_vnnumeric(Url::get('prepaid'));
				$bar_reser = DB::select('bar_reservation',Url::get('id'));
				$data = array();
				$check_out = false;
				if(Url::get('check_out'))
				{
					$check_out = true;
					$data = array(
						'status'=>'CHECKOUT'
					);
					if($row = DB::select('bar_reservation','id = '.Url::iget('id').'')){
						if(HOTEL_CURRENCY == 'VND'){
							$exchange_currency_id = 'USD';
						}else{
							$exchange_currency_id = 'VND';	
						}
						if(!$row['time_out']){
							$data += array(
									'time_out'=>time(),
									'exchange_rate'=>DB::fetch('select exchange from currency where id = \''.$exchange_currency_id.'\'','exchange')
								);
						}
					}
				}else{
					if(Url::get('status')=='CHECKOUT'){
						$check_out = true;
					}
				}
				if(Url::get('old_status')=='CHECKOUT'){
					
				}
				$group_payment = (Url::check('group_payment')?1:0);
				DB::update('bar_reservation', $data+array(
					'note',
					'customer_id'=>Url::get('customer_id')?Url::get('customer_id'):0,
					'receiver_name',
					'num_table',
					'tax_rate',
					'bar_fee_rate',
					'reservation_room_id'=>(Url::get('payment_result')=='ROOM')?Url::get('reservation_id'):0,
					'discount'=>$discount,
					'tax'=>$tax,
					'total_before_tax'=>$total_before_tax,
					'total'=>$total,
					'prepaid'=>$prepaid,
					'payment_result'=>Url::get('payment_result',''),
					'group_payment'=>(Url::get('payment_result')=='ROOM')?$group_payment:0,
					'payment_type_id',
					'reservation_type',
					'lastest_edited_time'=>time(),
					'lastest_edited_user_id'=>Session::get('user_id')
					),'id='.Url::get('id')
				);
				if(Url::get('payment_result')!='CASH')
				{
					DB::delete('pay_by_currency','bill_id=\''.Url::get('id').'\' and type=\'BAR\'');
				}
				//xoa cac ban ghi hien co trong bang bar_reservation_table va bar_reservation_product
				//them vao bang bar_reservation_table
				$table_old_items = array();
				if(URL::get('table_deleted_ids'))
				{
					if(strpos(URL::get('table_deleted_ids'),','))
					{
						$table_old_items = DB::select_all('bar_reservation_table','id in (\''.str_replace(',','\',\'',URL::get('table_deleted_ids')).'\')');
					}
					else
					{
						$table_old_item = DB::select('bar_reservation_table','id=\''.URL::get('table_deleted_ids').'\'');
						$table_old_items = array($table_old_item['id']=>$table_old_item);
					}
				}
				else
				{
					$table_old_items = array();
				}			
				$list_tables = '';
				if(isset($_REQUEST['mi_table']))
				{
					foreach($_REQUEST['mi_table'] as $row=>$row_data)
					{
						$list_tables .= 'Table: '.DB::fetch('select name from bar_table where id = '.$row_data['table_id'].'','name').'<br>';
						$item['bar_reservation_id'] = Url::get('id');
						$item['table_id'] = $row_data['table_id'];
						$item['num_people'] = $row_data['num_people'];
						$item['order_person'] = $row_data['order_person'];
						if($row_data['id'] and DB::select('bar_reservation_table','id='.$row_data['id']))
						{
							DB::update('bar_reservation_table',$item,'id='.$row_data['id']);
						}
						else
						{
							DB::insert('bar_reservation_table',$item);
						}
					}
				}
				//Xoa danh sach ban
				if($table_old_items)
				{
					foreach($table_old_items as $table_item)
					{
						DB::delete('bar_reservation_table','id='.$table_item['id']);	
					}
				}
				//--het xoa ban
				$product_old_items = array();
				if(URL::get('product_deleted_ids'))
				{
					if(strpos(URL::get('product_deleted_ids'),','))
					{
						$product_old_items = DB::select_all('bar_reservation_product','id in (\''.str_replace(',','\',\'',URL::get('product_deleted_ids')).'\')');
					}
					else
					{
						$product_old_item = DB::select('bar_reservation_product','id=\''.URL::get('product_deleted_ids').'\'');
						$product_old_items = array($product_old_item['id']=>$product_old_item);
					}
				}
				else
				{
					$product_old_items = array();
				}
				$list_product = '';
				if(isset($_REQUEST['mi_product']))
				{
					foreach($_REQUEST['mi_product'] as $row=>$row_data)
					{
						$product['discount'] = $row_data['discount'];
						$product['price'] = String::convert_to_vnnumeric($row_data['price']);
						$product['bar_reservation_id'] = Url::get('id');
						$product['product_id'] = $row_data['product_id'];
						$product['quantity'] = $row_data['quantity'];
						$product['quantity_discount'] = $row_data['quantity_discount'];
						$product['discount_rate'] = $row_data['discount'];
						$product['printed'] = isset($row_data['printed'])?1:0;
						$list_product .= 'Product: '.$product['product_id'].', Quantity: '.$product['quantity'].', Price: '.$product['price'].'<br>';
						if($row_data['id'] and DB::select('bar_reservation_product','id='.$row_data['id']))
						{
							DB::update('bar_reservation_product',$product,'id='.$row_data['id']);
						}
						else
						{
							unset($row_data['id']);	
							$id = DB::insert('bar_reservation_product',$product);
						}
					}
				}
				$description  = '';
				//--- Xoa sp ----
				if($product_old_items)
				{
					foreach($product_old_items as $product_item)
					{
						DB::delete('bar_reservation_product','id='.$product_item['id']);
						$description .= '<br>**************<br>Delete bar reservation product: '.$product_item['id'].', Quantity: '.$product_item['quantity'].', Price: '.$product_item['price'].'<br>**************<br>';
					}
				}
				//--- Het xoa sp ---
				$currency = DB::select_all('currency');
				$old_item = DB::select_all('pay_by_currency','bill_id='.Url::get('id').' and type=\'BAR\'');
				if(Url::get('payment_result') == 'CASH' and isset($_REQUEST['currency_selecteds']))
				{
					$data = array();
					foreach($_REQUEST['currency_selecteds'] as $record)
					{
						$data['bill_id'] = URL::get('id');
						if(isset($_REQUEST['value_'.$record]))
						{
							$data['amount'] = String::convert_to_vnnumeric($_REQUEST['value_'.$record]);
						}
						else
						{
							$data['amount'] = 0;
						}
						$data['type'] = 'BAR';
						$data['currency_id'] = $record;
						if($currency[$record])
						{
							$data['exchange_rate'] = $currency[$record]['exchange'];
						}
						else
						{
							$data['exchange_rate'] = 1;
						}
						$bar_row = DB::select('pay_by_currency','bill_id='.$data['bill_id'].' and type=\'BAR\' and currency_id=\''.$record.'\'');
						if($record and isset($old_item[$record]) and $bar_row)
						{
							DB::update('pay_by_currency',$data,'id='.$bar_row['id']);
							if(isset($old_item[$record]))
							{
								$old_items[$record]['not_delete'] = true;
							}							
						}
						else
						{
							DB::insert('pay_by_currency',$data);
						}
					}
				}
				foreach($old_item as $item)
				{
					if(!isset($item['not_delete']))
					{
						DB::delete('pay_by_currency','id=\''.$item['id'].'\'');
					}
				}				
				$title = 'Edit bar reservation , Code: '.Url::get('id').', Status: ' .Url::get('status').'';
				if(isset($row))
				{
				$description .= ''
							.Portal::language('arrival_time').': '.URL::get('arrival_time').'<br>  ' 
							.Portal::language('departure_time').': '.Url::get('departure_time').'<br>  ' 						
							.Portal::language('agent_name').': '.Url::get('agent_name').'<br>  '
							.Portal::language('agent_address').': '.Url::get('agent_address').'<br>  ' 
							.Portal::language('time').': '.Url::get('time').'<br>  ' 
							.Portal::language('agent_phone').': '.Url::get('agent_phone').'<br>  ' 
							.Portal::language('deposit').': '.$prepaid.'<br>'
							.Portal::language('total').': '.$total.'<br> ' 
							.Portal::language('note').': '.Url::get('note').'<br>  ' 
							.Portal::language('reservation_id').': '.URL::get('reservation_id').'<br>  ' 
							.Portal::language('room_id').': '.URL::get('room_id').'<br>  ' 
							.Portal::language('payment_type_id').': '.URL::get('payment_type_id').'<br>  '
							.'<hr>'
							.$list_tables
							.'<hr>'
							.$list_product.'
							';
				}
				else
				{
					$description = '';
				}
				if($error==false){
					System::log('edit',$title,$description,Url::iget('id'));
					echo '<div id="progress"><img src="packages/core/skins/default/images/updating.gif" /> Updating table status to server...</div>';
					echo '<script>
					'.($check_out?'window.open("'.Url::build_current(array('cmd'=>'detail',md5('act')=>md5('print'),'id')).'");':'').'
					if(window.opener)
					{
						window.opener.history.go(0);
						'.($check_out?'window.close();':'').'
					}
					'.($check_out?'window.location="'.Url::build('table_map').'";':'window.setTimeout("location=\''.Url::build('bar_reservation',array('cmd','id'=>Url::get('id'))).'\'",100);').'
					</script>';
					exit();
				}
			}
		}
	}	
	function draw()
	{
		require_once 'packages/hotel/packages/restaurant/includes/table.php';
		require_once 'packages/hotel/includes/php/hotel.php';		
		$row = DB::select('bar_reservation','id='.Url::iget('id').' and portal_id=\''.PORTAL_ID.'\'');
		$row['customer_name'] = $row['customer_id']?DB::fetch('select id,name from customer where id = '.$row['customer_id'].'','name'):'';
		$row['date'] = date('d/m/Y',$row['time']);
		foreach($row as $key=>$value){
			if(!isset($_REQUEST[$key])){
				$_REQUEST[$key] = $value;
			}
		}
		//============================== bar_table ===============================
		$db_items = Table::get_available_table($row['arrival_time'],$row['departure_time']);
		//$db_items = DB::select_all('bar_table');
		$select_table_options = '';
		foreach($db_items as $item)
		{
			$select_table_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}
		
		$tables = DB::select_all('bar_table','portal_id=\''.PORTAL_ID.'\'','id');
		$i=0;
		foreach($tables as $k=>$tbl)
		{
			$tables[$k]['stt']=$i;
			$i++;
		}
		$table_items = BarReservationNewDB::get_bar_table(Url::get('id'));
		$_REQUEST['mi_table'] = $table_items;
		$db_items = DB::select_all('bar_table','portal_id=\''.PORTAL_ID.'\'','name');
		$table_id_options = '';
		if($table_items)
		{
			foreach($table_items as $tkey=>$tbl)
			{
				$table_id_options = '';
				$_REQUEST['mi_table'][$tkey]['table_id'] = $tbl['table_id'];
				$table_items[$tkey]['reservation_table_id'] = $tbl['id'];
				if(isset($tables[$tkey]))
				{
					$tables[$tkey]['num_people'] = $tbl['num_people'];
				}
				foreach($db_items as $item)
				{
					if($item['id']==$tbl['table_id'])
					{
						$table_id_options .= '<option value="'.$item['id'].'" selected="selected">'.$item['name'].'</option>';
					}
					else
					{
						$table_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
					}
				}
			}
		}
		//============================== product ===============================
		$product = BarReservationNewDB::get_products(' and res_product.status = \'avaiable\' and portal_id=\''.PORTAL_ID.'\'');
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
		//danh sach payment_type
		DB::query('select id, name_'.Portal::language().' as name from payment_type where structure_id<>'.ID_ROOT.' order by id');
		$rows_list = DB::fetch_all();
		$list_payment_type[0]='-------';
		$list_payment_type=$list_payment_type+String::get_list($rows_list,'name');
		//danh sach room

		$rows_list = Hotel::get_reservation_room();
		$guest_list = Hotel::get_reservation_guest();
		if($row['reservation_room_id']){
			$current_room = DB::fetch('
				SELECT
					reservation_room.id,reservation_room.room_id,CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as agent_name,
					room.name as name
				FROM
					reservation_room
					LEFT OUTER JOIN room ON room.id = reservation_room.room_id
					LEFT OUTER JOIN traveller ON traveller.id = reservation_room.traveller_id
				WHERE
					reservation_room.id = '.$row['reservation_room_id'].'
			');
			$guest_list[$current_room['id']]['id'] = $current_room['id'];
			$guest_list[$current_room['id']]['name'] = $current_room['agent_name'];
			if(!isset($rows_list[$current_room['id']]))
			{
				$rows_list[$current_room['id']]['id'] = $current_room['room_id'];
				$rows_list[$current_room['id']]['name'] = $current_room['name'];
				$rows_list[$current_room['id']]['agent_name'] = $current_room['agent_name'];
			}
		}
		$list_room[0]='-------';
		$list_room = $list_room+String::get_list($rows_list,'name');
		//danh sach reservation
		$rows_list = $guest_list;
		$list_reservation[0]='-------';
		$list_reservation=$list_reservation+String::get_list($rows_list,'name');
		//danh sach phong - ten khach

		$reservation_room_list = Hotel::get_reservation_room_guest();
		$hotel = array(
			'reservation_room_list'=>$reservation_room_list,
			'room_id_list'=>$list_room,
			'reservation_id_list'=>$list_reservation,
			'reservation_id'=>Url::get('reservation_id',$row['reservation_room_id']),
			'room_id'=>Url::get('room_id',$row['reservation_room_id']),
		);
		
		//danh sach nguoi phuc vu
		DB::query('
			select 
				account.id, party.full_name as name 
			from 
				account 
				left outer join party on party.user_id = account.id
				inner join account_privilege on account_privilege.account_id = account.id
			where 
				account.type=\'USER\' and (account_privilege.privilege_id=5 or account.id=\'admin\')
			order by name');
		$rows_list=DB::fetch_all();
		$list_server[0]='-------';
		$list_server=$list_server+String::get_list($rows_list,'name');
		
		$product_items = BarReservationNewDB::get_reservation_product();
		$category_items = BarReservationNewDB::get_reservation_category();
		$total_before_tax = 0;
		$_REQUEST['mi_product'] = $product_items;
		$temp = 0;
		$b = false;
		foreach($product_items as $key=>$value)
		{
			$_REQUEST['mi_product'][$key]['product_id'] = $value['product_id'];
			$_REQUEST['mi_product'][$key]['printed'] = $value['printed'];
			$_REQUEST['mi_product'][$key]['unit'] = $value['unit_name'];
			$_REQUEST['mi_product'][$key]['quantity_discount'] = $value['quantity_discount'];
			$_REQUEST['mi_product'][$key]['discount'] = $value['discount_rate'];
			$_REQUEST['mi_product'][$key]['price'] = System::display_number_report($value['price']);
			$ttl = $value['price']*($value['quantity']-$value['quantity_discount']);
			$discnt = $ttl*$value['discount_rate']/100;
			$_REQUEST['mi_product'][$key]['total'] = System::display_number_report($ttl-$discnt);
			$total_before_tax += $ttl-$discnt;
			$_REQUEST['mi_product'][$key]['quantity'] = round($value['quantity'],2);
			if($category_items[$value['category_id']])
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
		$row['payment_type_id'] = Url::check('payment_type_id')?Url::get('payment_type_id'):$row['payment_type_id'];
		$row['time_in_hour']=$row['time_in']?date('H',$row['time_in']):date('H',time());
		$row['time_in_munite']=$row['time_in']?date('i',$row['time_in']):date('i',time());
		if($row['time_out'])
		{
			$row['time_out_hour']=date('H',$row['time_out']);
			$row['time_out_munite']=date('i',$row['time_out']);
		}
		
		$row['summary'] = System::display_number_report($total_before_tax + $row['discount']);
		$bar_fee = $total_before_tax*$row['bar_fee_rate']/100;
		$row['bar_fee'] = System::display_number_report($bar_fee);
		
		$total_before = $total_before_tax+round($total_before_tax*$row['bar_fee_rate']/100,2);
		$row['total_before_tax'] = System::display_number_report($total_before_tax+$bar_fee);
		$tax = ($total_before_tax + $bar_fee)*$row['tax_rate']/100;
		$row['tax'] = System::display_number_report($tax);
		$row['sum_total'] = System::display_number_report($total_before+$tax);
		$row['remain_prepaid'] = System::display_number_report($total_before+$tax - $row['prepaid']);
		$row['prepaid'] =System::display_number_report($row['prepaid']);
		$row['discount'] = System::display_number_report($row['discount']);
		$currency_ids = DB::fetch_all('
			select
				id,name,exchange
			from
				currency
			where
				allow_payment=1
			order by
				id desc
		');
		$currency_id_options = '';
		foreach($currency_ids as $key=>$value)
		{
			if($key=='USD')
			{
				$value['name'] = Portal::language('credit_card');
			}
			$currency_id_options.='<option value="'.$key.'">'.$value['name'].'</option>';
		}
		$payment_detail = DB::fetch_all('
			select
				pay_by_currency.*
			from
				pay_by_currency
			where
				bill_id = '.Url::get('id').' and type=\'BAR\'
		');
		$exchange_rate = DB::fetch('
			select
				id,exchange
			from
				currency
			where
				allow_payment=1 and id=\'VND\'
		');
		$rate = 0;
		foreach($payment_detail as $key=>$value)
		{
			if($value['currency_id']=='VND')
			{
				$rate = $value['exchange_rate'];
			}
			if(isset($currency_ids[$value['currency_id']]))
			{
				$currency_ids[$value['currency_id']]['value'] = $value['amount'];
			}
			else
			{
				$currency_ids[$value['currency_id']]['value'] = 0;
			}
		}
		$row['reservation_type_list'] = String::get_list(DB::fetch_all('select id,name from reservation_type order by position'));
		$bar = Hotel::get_new_bar($row['bar_id']);
		$this->parse_layout('check_in',$row+$hotel+array(
			'select_table_options'=>$table_id_options?$table_id_options:$select_table_options,
			'bar_name'=>$bar['name'],
			'exchange_rate'=>$rate?$rate:$exchange_rate['exchange'],
			'pay_by_currency'=>$currency_ids,
			'tables'=>$tables,
			'product'=>$product,
			'product_items'=>$product_items,
			'category_items'=>$category_items,
			'server_id_list'=>$list_server,
			'payment_type_id_list'=>$list_payment_type,
			'currency_id_options'=>$currency_id_options
		));
	}
}
?>