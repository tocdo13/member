<?php
class AddBarReservationForm extends Form
{
	function AddBarReservationForm()
	{
		Form::Form('AddBarReservationForm');
		$this->add('agent_name',new TextType(false,'invalid_agent_name',0,255));
		$this->add('code',new UniqueType(true,'invalid_code','bar_reservation','code'));  
		$this->add('num_table',new IntType(true,'invalid_num_table','0','100000000000')); 
		$this->add('arrival_date',new DateType(true,'invalid_arrival_time')); 
		$this->add('agent_address',new TextType(false,'invalid_agent_address',0,255)); 
		$this->add('agent_phone',new TextType(false,'invalid_agent_phone',0,255)); 
		$this->add('deposit',new FloatType(false,'invalid_deposit','0','100000000000')); 
		//$this->add('reservation_id',new IDType(false,'invalid_reservation_id','reservation')); 
		$this->add('bar_id',new IDType(true,'invalid_bar_id','bar')); 
		//$this->add('room_id',new IDType(false,'invalid_room_id','room')); 
		$this->link_css('packages/hotel/skins/default/css/suggestion.css');
		$this->link_css('skins/default/restaurant.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_js('packages/hotel/packages/restaurant/includes/js/update_price.js');
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
					$total = String::convert_to_vnnumeric(Url::get('sum_total'));
					$deposit = String::convert_to_vnnumeric(Url::get('deposit'));
					$id = DB::insert('bar_reservation', 
						array(
						'reservation_room_id'=>Url::get('reservation_id')?Url::get('reservation_id'):0,
						'room_id',
						'receptionist_id'=>Session::get('user_id'),
						'time'=>time(),
						'code',
						'tax_rate'=>'0', 
						'arrival_time'=>Date_Time::to_time(URL::get('arrival_date'))+(Url::get('arrival_time_in_hour')*3600)+(Url::get('arrival_time_in_munite')*60),
						'departure_time'=>Date_Time::to_time(URL::get('arrival_date'))+(Url::get('arrival_time_out_hour')*3600)+(Url::get('arrival_time_out_munite')*60),
						'status'=>Url::get('check_in')?'CHECKIN':'RESERVATION', 
						'note', 
						'agent_name', 
						'agent_address', 
						'agent_phone',
						'agent_fax',
						'receiver_name', 
						'bar_id',
						'num_table',
						'bar_fee_rate'=>'0',
						'deposit'=>$deposit,
						'prepaid'=>$deposit,
						'total'=>$total
					));
					$list_tables = array();
					if(Url::check('table__reservation_table_id')) 
					{
						$list_tables['table_id'] = Url::get('table__reservation_table_id');
						$sample = current($list_tables);
						foreach($sample as $row=>$row_data)
						{
							$blank1 = true;
							$item = array('bar_reservation_id'=>$id);
							foreach($list_tables as $column=>$column_data)
							{
								if($list_tables[$column][$row])
								{
									$blank1 = false;
								}
								$item[$column] = $list_tables[$column][$row];
							}
							if(!$blank1)
							{
								DB::insert('bar_reservation_table',$item);
							}
						}
					}
					$list_product = array();
					if(Url::check('product__id') and Url::get('product__id')!='')
					{
						$list_product['product_id'] = Url::get('product__id');
						$list_product['quantity'] = Url::get('product__quantity');
						$list_product['price'] = String::convert_to_vnnumeric(Url::get('product__price'));
						
						$sample = current($list_product);
						foreach($sample as $row=>$row_data)
						{
							$blank = true;
							$item = array('bar_reservation_id'=>$id);
							foreach($list_product as $column=>$column_data)
							{
								if($list_product[$column][$row])
								{
									$blank = false;
								}
								$item[$column] = $list_product[$column][$row];
							}
							if(!$blank)
							{
								DB::insert('bar_reservation_product',$item);
							}
						}
					}
					$title = ''.substr(URL::get('code'),0,32).',  ' .substr(URL::get('status'),0,32).',  '     .substr(URL::get('time'),0,32).',  ';
					$description = ''
					.Portal::language('code').':'.substr(URL::get('code'),0,255).'<br>  ' 
					.Portal::language('status').':'.substr(URL::get('status'),0,255).'<br>  ' 
					.Portal::language('num_table').':'.substr(URL::get('num_table'),0,255).'<br>  ' 
					.Portal::language('arrival_time').':'.substr(URL::get('arrival_time'),0,255).'<br>  ' 
					.Portal::language('agent_name').':'.substr(URL::get('agent_name'),0,255).'<br>  ' 
					.Portal::language('agent_address').':'.substr(URL::get('agent_address'),0,255).'<br>  ' 
					.Portal::language('time').':'.substr(URL::get('time'),0,255).'<br>  ' 
					.Portal::language('agent_phone').':'.substr(URL::get('agent_phone'),0,255).'<br>  ' 
					.Portal::language('departure_time').':'.substr(URL::get('departure_time'),0,255).'<br>  ' 
					.Portal::language('deposit').':'.substr(URL::get('deposit'),0,255).'<br>  ' 
					.Portal::language('note').':'.substr(URL::get('note'),0,255).'<br>  ' 
					.Portal::language('reservation_id').':'.URL::get('reservation_id').'<br>  ' 
					.Portal::language('room_id').':'.URL::get('room_id').'<br>  ' 
					.Portal::language('bar_id').':'.URL::get('bar_id').'<br>  ' 
					.Portal::language('receptionist_id').':'.URL::get('receptionist_id').'<br>  ' 
					.Portal::language('payment_type_id').':'.URL::get('payment_type_id').'<br>  ' ;
					System::log('add',Portal::language('add').' '.$title,$description,$id);
					echo '<div id="progress"><img src="packages/core/skins/default/images/updating.gif" /> Updating table status to server...</div>';
					echo '<script>
					if(window.opener)
					{
						window.opener.history.go(0);
					}
					window.setTimeout("location=\''.((Url::get('check_in'))?Url::build('bar_reservation',array('cmd'=>'check_in','id'=>$id)):Url::build('bar_reservation',array('id'=>$id))).'\'",1000);
					</script>';
					exit();
				}
			}
		}
	}
	function draw()
	{
		require_once 'packages/hotel/includes/php/hotel.php';
		require_once 'packages/hotel/packages/restaurant/includes/table.php';
		DB::query('select id from bar_reservation order by id desc');
		$res = DB::fetch();
		$current_code = $res['id']+1;
		
		//============================== currency ================================
		$curr = HOTEL_CURRENCY;
		$currency=DB::select('currency','name=\''.$curr.'\'');
		
		//============================== bar_table ===============================
		$bar_id = Url::check('bar_id')?Url::get('bar_id'):'0';
		$arrival_time_in_munite = Url::check('arrival_time_in_munite')?Url::get('arrival_time_in_munite'):0;
		$arrival_time_out_munite = Url::check('arrival_time_out_munite')?Url::get('arrival_time_out_munite'):45;

		$from_time = Url::check('arrival_date')?(Date_Time::to_time(URL::get('arrival_date'))+(Url::get('arrival_time_in_hour')*3600)+($arrival_time_in_munite*60)):(Date_Time::to_time(date('d/m/Y',time())));
		$to_time = Url::check('arrival_date')?(Date_Time::to_time(URL::get('arrival_date'))+(Url::get('arrival_time_out_hour')*3600)+($arrival_time_out_munite*60)):(Date_Time::to_time(date('d/m/Y',time()))+3600*24-1);

		$cond_bar = 'bar_id="'.$bar_id.'"';
		
		$table_id_options = '';
		$db_items = Table::get_available_table($bar_id,$from_time,$to_time);
		foreach($db_items as $item)
		{
			$table_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}
		//echo $table_id_options.'asd';

		$tables = DB::select_all('bar_table',false,'id');
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
		$product = BarReservationDB::get_products(' and res_product.status = \'avaiable\'');
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
		
		//danh sach bar
		$rows_list = Hotel::get_bar();
		$list_bar[0] = '-------';
		$list_bar = $list_bar+String::get_list($rows_list,'name');
		//
		if(Url::get('bar_id'))
		{
			$bar = Hotel::get_new_bar(intval(Url::get('bar_id')));
		}
		
		//============================= ngay dat ===============================
		$reserv_date = Url::check('arrival_time')?date('Y-m-d',Date_Time::to_time(Url::get('arrival_time'))):date('Y-m-d',time());
		
		if(User::can_admin(false,ANY_CATEGORY))
		{
			//danh sach room
			$rows_list = Hotel::get_reservation_room();
			$list_room[0] = '-------';
			$list_room = $list_room + String::get_list($rows_list,'name');
			//danh sach reservation
			$rows_list = Hotel::get_reservation_guest();
			$list_reservation[0]='-------';
			$list_reservation=$list_reservation + String::get_list($rows_list,'name');
	
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
		
		$this->parse_layout('add',$row+$product_row+$hotel+
			array(
				'curr'=>$curr,
				'current_code'=>$current_code,
				'arrival_date'=>Url::check('arrival_time')?Url::get('arrival_time'):date('d/m/Y',time()),
				'date'=>date('d/m/Y',time()),
				'tables'=>$tables,
				'product'=>$product,
				'bar_id_list'=>$list_bar,
				'bar_name'=>isset($bar['name'])?$bar['name']:'',
				'bar_id'=>0, 
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