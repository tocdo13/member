<?php
class AddBarReservationNewForm extends Form
{
	function AddBarReservationNewForm()
	{
		Form::Form('AddBarReservationNewForm');
		$this->add('customer_name',new TextType(false,'invalid_customer_name',0,255));
		$this->add('customer_id',new TextType(false,'invalid_customer_id',0,255));
		$this->add('receiver_name',new TextType(false,'invalid_receiver_name',0,255));
		//$this->add('code',new UniqueType(true,'dupplicated_code','bar_reservation','code',false,PORTAL_ID));  
		$this->add('num_table',new IntType(true,'invalid_num_table','0','100000000000'));
		$this->add('arrival_date',new DateType(true,'invalid_arrival_time')); 
		$this->add('deposit',new FloatType(false,'invalid_deposit','0','100000000000')); 
		//$this->add('reservation_id',new IDType(false,'invalid_reservation_id','reservation')); 
		//$this->add('table.table_id',new TextType(true,'invalid_table_id',0,255));
		$this->add('table.table_id',new IDType(true,'miss_table','bar_table')); 
		//$this->add('room_id',new IDType(false,'invalid_room_id','room'));
		//$this->add('product.product_id',new TextType(true,'invalid_product_id',0,20)); 
		$this->add('product.product_id',new IDType(true,'miss_product_id','res_product','code')); 
		$this->link_css('skins/default/restaurant.css');		 
		//$this->link_css('packages/hotel/skins/default/css/suggestion.css');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_js('packages/core/includes/js/multi_items.js');		
		$this->link_js('packages/hotel/packages/restaurant/includes/js/update_price_new.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');

	}
	function on_submit()
	{
		if(Url::get('save') or Url::get('check_in')){
			if(URL::check(array('select_bar'=>0)))
			{
				if($this->check())
				{ 
					if(isset($_REQUEST['mi_table'])){
						$total = String::convert_to_vnnumeric(Url::get('sum_total'));
						$deposit = String::convert_to_vnnumeric(Url::get('deposit'));
						$total_vnd = String::convert_to_vnnumeric(Url::get('sum_total_vnd'));
						$deposit_vnd = String::convert_to_vnnumeric(Url::get('deposit_vnd'));
						$array = array();
						$status = Url::get('check_in')?'CHECKIN':'RESERVATION';
						if($status == 'CHECKIN'){
							$array = array(
								'time_in'=>time()
							);
						}
						$id = DB::insert('bar_reservation', 
							$array + array(							
							'reservation_room_id'=>Url::get('reservation_id')?Url::get('reservation_id'):0,
							'time'=>time(),
							'time_in'=>Url::get('check_in')?time():0,
							'arrival_time'=>Date_Time::to_time(URL::get('arrival_date'))+(Url::get('arrival_time_in_hour')*3600)+(Url::get('arrival_time_in_munite')*60),
							'departure_time'=>Date_Time::to_time(URL::get('arrival_date'))+(Url::get('arrival_time_out_hour')*3600)+(Url::get('arrival_time_out_munite')*60),
							'status'=>$status, 
							'note', 
							'customer_id'=>Url::get('customer_id')?Url::get('customer_id'):0,
							'receiver_name',
							'num_table',
							'tax_rate', 
							'bar_fee_rate'=>Url::get('service_rate'),
							'deposit'=>$deposit,
							'prepaid'=>$deposit,
							'total'=>$total,
							'user_id'=>Session::get('user_id'),
							'reservation_type',
							'banquet_order_type',
							'deposit_date'=>Date_Time::to_orc_date(Url::get('deposit_date')),
							'payment_info',
							'portal_id'=>PORTAL_ID
						));
						DB::update('bar_reservation',array('code'=>$id),'id='.$id);
						$list_tables = '';
						foreach($_REQUEST['mi_table'] as $row=>$row_data)
						{
							$list_tables .= 'Table: '.DB::fetch('select name from bar_table where id = '.$row_data['table_id'].'','name').'<br>';
							$row_data['bar_reservation_id'] = $id;
							unset($row_data['code']);
							DB::insert('bar_reservation_table',$row_data);
						}
						$list_product = '';
						if(isset($_REQUEST['mi_product']))
						{
							foreach($_REQUEST['mi_product'] as $row=>$row_data)
							{
								$data['bar_reservation_id'] = $id;
								$data['product_id'] = $row_data['product_id'];
								$data['quantity'] = $row_data['quantity'];
								$data['price'] =  String::convert_to_vnnumeric($row_data['price']);
								$list_product .= 'Product: '.$row_data['product_id'].', Quantity: '.$row_data['quantity'].', Price: '.$data['price'].'<br>';								
								DB::insert('bar_reservation_product',$data);
							}
						}
						$title = 'Add bar reservation , Code: '.Url::get('code').', Status: ' .$status.'';
						$description = ''
						.Portal::language('arrival_time').':'.URL::get('arrival_time').'<br>  ' 
						.Portal::language('departure_time').':'.Url::get('departure_time').'<br>  ' 						
						.Portal::language('agent_name').':'.Url::get('agent_name').'<br>  '
						.Portal::language('agent_address').':'.Url::get('agent_address').'<br>  ' 
						.Portal::language('time').':'.Url::get('time').'<br>  ' 
						.Portal::language('agent_phone').':'.Url::get('agent_phone').'<br>  ' 
						.Portal::language('deposit').':'.Url::get('deposit').'<br>'
						.Portal::language('total').':'.Url::get('total').'<br> ' 
						.Portal::language('note').':'.Url::get('note').'<br>  ' 
						.Portal::language('reservation_id').':'.URL::get('reservation_id').'<br>  ' 
						.Portal::language('room_id').':'.URL::get('room_id').'<br>  ' 
						.Portal::language('payment_type_id').':'.URL::get('payment_type_id').'<br>  '
						.'<hr>'
						.$list_tables
						.'<hr>'
						.$list_product.'
						';
						System::log('add',$title,$description,$id);
						echo '<div id="progress"><img src="packages/core/skins/default/images/updating.gif" /> Updating table status to server...</div>';
						echo '<script>
						if(window.opener)
						{
							window.opener.history.go(0);
							window.close();
						}
						window.setTimeout("location=\''.((Url::get('check_in'))?Url::build('bar_reservation',array('cmd'=>'check_in','id'=>$id)):Url::build('table_map')).'\'",1000);
						</script>';
						exit();
					}else{
						$this->error('table_id','you_have_to_select_at_least_one_table');
					}
				}
			}
		}
	}
	function draw()
	{
		require_once 'packages/hotel/includes/php/hotel.php';
		require_once 'packages/hotel/packages/restaurant/includes/table.php';
		DB::query('select code as id from bar_reservation where bar_reservation.portal_id=\''.PORTAL_ID.'\' order by code desc');
		$res = DB::fetch();
		$current_code = $res['id']+1;
		
		//============================== currency ================================
		$curr = HOTEL_CURRENCY;
		$currency=DB::select('currency','name=\''.$curr.'\'');
		
		//============================== bar_table ===============================
		$arrival_time_in_munite = Url::check('arrival_time_in_munite')?Url::get('arrival_time_in_munite'):0;
		$arrival_time_out_munite = Url::check('arrival_time_out_munite')?Url::get('arrival_time_out_munite'):45;

		$from_time = Url::check('arrival_date')?(Date_Time::to_time(URL::get('arrival_date'))+(Url::get('arrival_time_in_hour')*3600)+($arrival_time_in_munite*60)):(Date_Time::to_time(date('d/m/Y',time())));
		$to_time = Url::check('arrival_date')?(Date_Time::to_time(URL::get('arrival_date'))+(Url::get('arrival_time_out_hour')*3600)+($arrival_time_out_munite*60)):(Date_Time::to_time(date('d/m/Y',time()))+3600*24-1);
		$table_id_options = '';
		$db_items = Table::get_available_table($from_time,$to_time);
		foreach($db_items as $item)
		{
			$table_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}
		//echo $table_id_options.'asd';

		$tables = DB::select_all('bar_table','portal_id=\''.PORTAL_ID.'\'','id');
		$i=0;
		foreach($tables as $k=>$tbl)
		{
			$tables[$k]['stt']=$i;
			$i++;
		}
		if(Url::check('table_ids'))
		{
			$table_ids=explode(',',Url::get('table_ids'));
			
			for($c=0;$c<sizeof($table_ids);$c++)
			{
				$tbl=DB::select('bar_table',$table_ids[$c]);
				$row['table_items'][$c]['id'] =$tbl['id'];
				$row['table_items'][$c]['reservation_table_id'] =$tbl['id'];
				$row['table_items'][$c]['name'] =$tbl['name'];
				$row['table_items'][$c]['code'] =$tbl['code']; 
				$row['table_items'][$c]['num_people'] =$tbl['num_people']; 
			}
		}
		else
		{
			$row['table_items']=array();
		}
		//============================== product ===============================
		$product = BarReservationNewDB::get_products(' and res_product.status = \'avaiable\' and portal_id=\''.PORTAL_ID.'\'');
		$i=0;
		$total_product = sizeof($product);
		foreach($product as $k1=>$g)
		{
			$product[$k1]['stt'] = $i++;
			if($i == $total_product)
			{
				$product[$k1]['last'] = 1;
			}
			else
			{
				$product[$k1]['last'] = 0;
			}			
		}
		$product_row['product_items']=array();
		
		
		//============================= ngay dat ===============================
		$reserv_date = Url::check('arrival_time')?date('Y-m-d',Date_Time::to_time(Url::get('arrival_time'))):date('Y-m-d',time());
		
		if(User::can_edit(false,ANY_CATEGORY))
		{
			//danh sach room
			$rows_list = Hotel::get_reservation_room();
			$list_room[0] = '-------';
			$list_room = $list_room + String::get_list($rows_list,'name');
			//danh sach reservation
			$rows_list = Hotel::get_reservation_guest();
			$list_reservation[0]='-------';
			$list_reservation = $list_reservation + String::get_list($rows_list,'name');
	
			//danh sach phong - ten khach

			$reservation_room_list = Hotel::get_reservation_room_guest();
			$hotel = array(
				'room_id_list'=>$list_room,
				'room_id'=>Url::check('room_id')?Url::get('room_id'):0, 
				'reservation_id_list'=>$list_reservation,
				'reservation_id'=>Url::check('reservation_id')?Url::get('reservation_id'):0, 
				'reservation_room_list'=>$reservation_room_list,
			);
		}
		else
		{
			$hotel = array();
		}
		
		$default_time_in_hour = date('H',time());
		if(date('i',time())<5)
		{
			$default_time_in_munite = 5;
		}
		else
		if(date('i',time())<10)
		{
			$default_time_in_munite = 10;
		}
		else
		if(date('i',time())<15)
		{
			$default_time_in_munite = 15;
		}
		else
		if(date('i',time())<20)
		{
			$default_time_in_munite = 20;
		}
		else
		if(date('i',time())<25)
		{
			$default_time_in_munite = 25;
		}
		else
		if(date('i',time())<30)
		{
			$default_time_in_munite = 30;
		}
		else
		if(date('i',time())<35)
		{
			$default_time_in_munite = 35;
		}
		else
		if(date('i',time())<40)
		{
			$default_time_in_munite = 40;
		}
		else
		if(date('i',time())<45)
		{
			$default_time_in_munite = 45;
		}
		else
		if(date('i',time())<50)
		{
			$default_time_in_munite = 50;
		}
		else
		if(date('i',time())<55)
		{
			$default_time_in_munite = 55;
		}
		else
		{
			$default_time_in_munite = 0;
			$default_time_in_hour++;
		}
		
		if($default_time_in_hour>=22)
		{
			$default_time_out_hour = 23;
			$default_time_out_munite = 45;
		}
		else
		{
			$default_time_out_hour = $default_time_in_hour+2;
			$default_time_out_munite = $default_time_in_munite;
		}
		if(Url::get('table_id') and $table = DB::select('bar_table','id='.Url::iget('table_id')) and !isset($_REQUEST['mi_table'])){
			$_REQUEST['mi_table'][$table['id']]['table_id'] = $table['id'];
			$_REQUEST['mi_table'][$table['id']]['code'] = $table['code'];
			$_REQUEST['mi_table'][$table['id']]['num_people'] = $table['num_people'];
		}
		$row['reservation_type_list'] = String::get_list(DB::fetch_all('select id,name from reservation_type order by position'));
		$row['tax_rate_list'] = array(0=>'0%',10=>'10%');
		$row['service_rate_list'] = array(0=>'0%',5=>'05%');
		if(!isset($_REQUEST['tax_rate'])){
			$_REQUEST['tax_rate'] = RES_TAX_RATE;
		}
		if(!isset($_REQUEST['service_rate'])){
			$_REQUEST['service_rate'] = RES_SERVICE_CHARGE;
		}
		$this->parse_layout('add',$row+$product_row+$hotel+
			array(
				'exchange_rate' => DB::fetch('SELECT id,exchange FROM currency WHERE id=\'VND\'','exchange'),
				'curr'=>$curr,
				'current_code'=>$current_code,
				'arrival_date'=>Url::check('arrival_time')?Url::get('arrival_time'):date('d/m/Y',time()),
				'date'=>date('d/m/Y',time()),
				'tables'=>$tables,
				'product'=>$product,
				'table_id_options' => $table_id_options,
				'time_in_hour'=>Url::check('arrival_time_in_hour')?Url::get('arrival_time_in_hour'):$default_time_in_hour,
				'time_in_munite'=>Url::check('arrival_time_in_munite')?Url::get('arrival_time_in_munite'):$default_time_in_munite,
				'time_out_hour'=>Url::check('arrival_time_out_hour')?Url::get('arrival_time_out_hour'):$default_time_out_hour,
				'time_out_munite'=>Url::check('arrival_time_out_munite')?Url::get('arrival_time_out_munite'):$default_time_out_munite,
			)
		);
	}
}
?>