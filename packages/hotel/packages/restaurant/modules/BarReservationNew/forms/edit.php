<?php
class EditBarReservationNewForm extends Form
{
	function EditBarReservationNewForm()
	{
		Form::Form('EditBarReservationNewForm');
		if(!Url::check('id') or (Url::get('id') and !DB::exists('select id from bar_reservation where id='.Url::iget('id').'')))
		{
			Url::redirect_current();
		}
		else
		{
			if($br = DB::fetch('select id,status from bar_reservation where id=\''.Url::get('id').'\' and (status=\'CHECKIN\' OR status=\'CHECKOUT\')'))
			{
				if(($br['status']=='CHECKOUT' and !User::can_admin(false,ANY_CATEGORY))){
					Url::redirect_current(array('cmd'=>'detail','id'));
				}else{
					Url::redirect_current(array('cmd'=>'check_in','id'));	
				}
			}
			else
			{
				$this->add('customer_name',new TextType(false,'invalid_customer_name',0,255));
				$this->add('customer_id',new TextType(false,'invalid_customer_id',0,255));
				$this->add('receiver_name',new TextType(false,'invalid_receiver_name',0,255));
				$this->add('bar_reservation_product.product_id',new TextType(true,'product_id_is_required',0,255));
				$this->add('num_table',new IntType(true,'invalid_num_table','0','100000000000'));
				$this->add('arrival_date',new DateType(true,'invalid_arrival_time')); 
				$this->add('deposit',new FloatType(false,'invalid_deposit','0','100000000000')); 
				//$this->add('reservation_id',new IDType(false,'invalid_reservation_id','reservation')); 
				$this->add('bar_reservation_table.table_id',new IDType(true,'miss_table','bar_table')); 
				//$this->add('room_id',new IDType(false,'invalid_room_id','room'));
				$this->add('bar_reservation_product.product_id',new IDType(true,'miss_product_id','res_product')); 
				$this->link_css('skins/default/restaurant.css');		 
				//$this->link_css('packages/hotel/skins/default/css/suggestion.css');
				$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
				$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
				$this->link_js('packages/core/includes/js/multi_items.js');		
				$this->link_js('packages/hotel/packages/restaurant/includes/js/update_price_new.js');
				$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
				$this->link_js('packages/core/includes/js/jquery/datepicker.js');
			}
		}
	}
	function on_submit()
	{
		if(URL::check(array('select_bar'=>0)))
		{
			if(URL::get('delete_table') and URL::get('delete_id'))
			{
				foreach($_REQUEST['table_'.URL::get('delete_table')] as $key => $value)
				{
					unset($_REQUEST['table_'.URL::get('delete_table')][$key][URL::get('delete_id')]);
				}
			}
			else
			if($this->check())
			{
				$total = String::convert_to_vnnumeric(Url::get('sum_total'));
				$deposit = String::convert_to_vnnumeric(Url::get('deposit'));
				$bar_reser = DB::select('bar_reservation',Url::get('id'));
				$array = array();
				$status = Url::get('check_in')?'CHECKIN':'RESERVATION';
				if($status == 'CHECKIN'){
					$array = array(
						'time_in'=>time()
					);
				}
				DB::update('bar_reservation',$array + array(
					'reservation_room_id'=>Url::get('reservation_id')?Url::get('reservation_id'):0,
					'note', 
					'customer_id'=>Url::get('customer_id')?Url::get('customer_id'):0,
					'receiver_name',
					'num_table',
					'arrival_time'=>Date_Time::to_time(URL::get('arrival_date'))+(Url::get('arrival_time_in_hour')*3600)+(Url::get('arrival_time_in_munite')*60),
					'departure_time'=>Date_Time::to_time(URL::get('arrival_date'))+(Url::get('arrival_time_out_hour')*3600)+(Url::get('arrival_time_out_munite')*60),
					'deposit'=>$deposit,
					'prepaid'=>$deposit,
					'tax_rate', 
					'bar_fee_rate'=>Url::get('service_rate'),
					'total'=>$total,
					'lastest_edited_time'=>time(),
					'lastest_edited_user_id'=>Session::get('user_id'),
					'status'=>$status,
					'reservation_type',
					'banquet_order_type',
					'deposit_date'=>Date_Time::to_orc_date(Url::get('deposit_date')),
					'payment_info'
					),'id='.Url::get('id')
				);
				//xoa cac ban ghi hien co trong bang bar_reservation_table va bar_reservation_product
				DB::delete('bar_reservation_table','bar_reservation_id=\''.Url::get('id').'\'');
				DB::delete('bar_reservation_product','bar_reservation_id=\''.Url::get('id').'\'');
				//them vao bang bar_reservation_table
				if(isset($_REQUEST['mi_table']))
				{
					foreach($_REQUEST['mi_table'] as $row=>$row_data)
					{
						$item['bar_reservation_id'] = Url::get('id');
						$item['table_id'] = $row_data['table_id'];
						$item['num_people'] = $row_data['num_people'];
						$item['order_person'] = $row_data['order_person'];
						DB::insert('bar_reservation_table',$item);
					}
				}
				if(isset($_REQUEST['mi_product']))
				{
					foreach($_REQUEST['mi_product'] as $row=>$row_data)
					{
						$data['bar_reservation_id'] = Url::get('id');
						$data['product_id'] = $row_data['product_id'];
						$data['quantity'] = $row_data['quantity'];
						$data['price'] =  String::convert_to_vnnumeric($row_data['price']);
						$id = DB::insert('bar_reservation_product',$data);
					}
				}
				$title = substr(URL::get('code'),0,32).',  ' .substr(URL::get('status'),0,32).',  '     .substr(URL::get('time'),0,32).',  ';
				$description = ''
					.((URL::get('code')!=$row['code'])?'Change '.Portal::language('code').' from '.substr($row['code'],0,255).' to '.substr(URL::get('code'),0,255).'<br>  ':'') 
					.((URL::get('status')!=$row['status'])?'Change '.Portal::language('status').' from '.substr($row['status'],0,255).' to '.substr(URL::get('status'),0,255).'<br>  ':'') 
					.((URL::get('num_table')!=$row['num_table'])?'Change '.Portal::language('num_table').' from '.substr($row['num_table'],0,255).' to '.substr(URL::get('num_table'),0,255).'<br>  ':'') 
					.((URL::get('arrival_time')!=$row['arrival_time'])?'Change '.Portal::language('arrival_time').' from '.substr($row['arrival_time'],0,255).' to '.substr(URL::get('arrival_time'),0,255).'<br>  ':'') 
					.((URL::get('agent_name')!=$row['agent_name'])?'Change '.Portal::language('agent_name').' from '.substr($row['agent_name'],0,255).' to '.substr(URL::get('agent_name'),0,255).'<br>  ':'') 
					.((URL::get('agent_address')!=$row['agent_address'])?'Change '.Portal::language('agent_address').' from '.substr($row['agent_address'],0,255).' to '.substr(URL::get('agent_address'),0,255).'<br>  ':'') 
					.((URL::get('time')!=$row['time'])?'Change '.Portal::language('time').' from '.substr($row['time'],0,255).' to '.substr(URL::get('time'),0,255).'<br>  ':'') 
					.((URL::get('agent_phone')!=$row['agent_phone'])?'Change '.Portal::language('agent_phone').' from '.substr($row['agent_phone'],0,255).' to '.substr(URL::get('agent_phone'),0,255).'<br>  ':'') 
					.((URL::get('departure_time')!=$row['departure_time'])?'Change '.Portal::language('departure_time').' from '.substr($row['departure_time'],0,255).' to '.substr(URL::get('departure_time'),0,255).'<br>  ':'') 
					.((URL::get('deposit')!=$row['deposit'])?'Change '.Portal::language('deposit').' from '.substr($row['deposit'],0,255).' to '.substr(URL::get('deposit'),0,255).'<br>  ':'') 
					.((URL::get('total')!=$row['total'])?'Change '.Portal::language('total').' from '.substr($row['total'],0,255).' to '.substr(URL::get('total'),0,255).'<br>  ':'') 
					.((URL::get('note')!=$row['note'])?'Change '.Portal::language('note').' from '.substr($row['note'],0,255).' to '.substr(URL::get('note'),0,255).'<br>  ':'') 
					.((URL::get('reservation_id')!=$row['reservation_id'])?'Change '.Portal::language('reservation_id').' from <a href="?page=reservation&id='.$row['reservation_id'].'">#'.$row['reservation_id'].'</a> to <a href="?page=reservation&id='.URL::get('reservation_id').'">#'.URL::get('').'</a><br>  ':'')
					.((URL::get('room_id')!=$row['room_id'])?'Change '.Portal::language('room_id').' from <a href="?page=room&id='.$row['room_id'].'">#'.$row['room_id'].'</a> to <a href="?page=room&id='.URL::get('room_id').'">#'.URL::get('').'</a><br>  ':'');					
				System::log('edit',Portal::language('edit').' '.$title,$description,Url::iget('id'));
				echo '<div id="progress"><img src="packages/core/skins/default/images/updating.gif" /> Updating table status to server...</div>';
				echo '<script>
				if(window.opener)
				{
					window.opener.history.go(0);
					window.close();
				}
				window.setTimeout("location=\''.Url::build('table_map').'\'",1000);
				</script>';
				exit();
			}
		}
	}	
	function draw()
	{	
		require_once 'packages/hotel/packages/restaurant/includes/table.php';
		require_once 'packages/hotel/includes/php/hotel.php';		
		$row = DB::select('bar_reservation','id='.Url::iget('id').' and portal_id=\''.PORTAL_ID.'\'');
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
		$table_id_options = '';
		$_REQUEST['mi_table'] = $table_items;
		$db_items = DB::select_all('bar_table','portal_id=\''.PORTAL_ID.'\'','name');
		foreach($table_items as $tkey=>$tbl)
		{
			$_REQUEST['mi_table'][$tkey]['table_id'] = $tbl['id'];
			$table_items[$tkey]['reservation_table_id'] = $tbl['id'];
		}
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
		foreach($product as $k1=>$g){
			$product[$k1]['stt']=$i++;
			if($i == $total_product){
				$product[$k1]['last'] = 1;
			}else{
				$product[$k1]['last'] = 0;
			}
		}
		//danh sach room
		$rows_list = Hotel::get_reservation_room();
		$list_room[0]='-------';
		$list_room = $list_room+String::get_list($rows_list,'name');
		//danh sach reservation
		$rows_list = Hotel::get_reservation_guest();
		$list_reservation[0]='-------';
		$list_reservation=$list_reservation+String::get_list($rows_list,'name');
		//danh sach phong - ten khach
		$reservation_room_list = Hotel::get_reservation_room_guest();
		$hotel = array(
			'reservation_room_list'=>$reservation_room_list,
			'room_id_list'=>$list_room,
			'reservation_id_list'=>$list_reservation,
			'reservation_id'=>Url::get('reservation_id',$row['reservation_room_id']),
		);
		$product_items = BarReservationNewDB::get_reservation_product();
		$_REQUEST['mi_product'] = $product_items;
		$total = 0;
		foreach($product_items as $key=>$value){
			$total_ = $value['price']*$value['quantity'];
			$total += $total_;
			$_REQUEST['mi_product'][$key]['total'] = System::display_number_report($total_);
			$_REQUEST['mi_product'][$key]['price'] = System::display_number_report($value['price']);
			$_REQUEST['mi_product'][$key]['product_id'] = $value['product_id'];
			$_REQUEST['mi_product'][$key]['unit'] = $value['unit_name'];
		}
		$row['time_in_hour']=date('H',$row['arrival_time']);
		$row['time_in_munite']=date('i',$row['arrival_time']);
		$row['time_out_hour']=date('H',$row['departure_time']);
		$row['time_out_munite']=date('i',$row['departure_time']);
		$row['summary'] = System::display_number_report($total);
		$row['sum_total'] = System::display_number_report($total);
		$row['arrival_date'] = $row['arrival_time']?date('d/m/Y',$row['arrival_time']):'';
		$row['deposit_date'] = Date_Time::convert_orc_date_to_date($row['deposit_date'],'/');
		$row['service_rate'] = $row['bar_fee_rate'];
		$row['customer_name'] = $row['customer_id']?DB::fetch('select id,name from customer where id = '.$row['customer_id'].'','name'):'';
		foreach($row as $key=>$value){
			if(!isset($_REQUEST[$key])){
				$_REQUEST[$key] = $value;
			}
		}
		$row['reservation_type_list'] = String::get_list(DB::fetch_all('select id,name from reservation_type order by position'));
		$row['tax_rate_list'] = array(0=>'0%',10=>'10%');
		$row['service_rate_list'] = array(0=>'0%',5=>'05%');
		$this->parse_layout('edit',$hotel+$row+array(
			'exchange_rate' => DB::fetch('SELECT id,exchange FROM currency WHERE id=\'VND\'','exchange'),
			'arrival_date'=>Url::check('arrival_time')?Url::get('arrival_time'):date('d/m/Y',time()),
			'date'=>date('d/m/Y',time()),
			'tables'=>$tables,
			'table_id_options' => $table_id_options,
			'product'=>$product,
			'select_table_options' => $table_id_options,
			'room_id'=>Url::get('room_id',$row['reservation_room_id']),
		));
	}
}
?>