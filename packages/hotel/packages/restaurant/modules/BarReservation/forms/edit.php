<?php
class EditBarReservationForm extends Form
{
	function EditBarReservationForm()
	{
		Form::Form('EditBarReservationForm');
		if(!Url::check('id') or (Url::get('id') and !DB::exists('select id from bar_reservation where id=\''.Url::get('id').'\'')))
		{
			Url::redirect_current();
		}
		else
		{
			DB::query('select id,status from bar_reservation where id=\''.Url::get('id').'\' and status=\'CHECKIN\'');
			if(DB::fetch())
			{
				Url::redirect_current(array('cmd'=>'check_in','id'));
			}
			else
			{
				$this->add('code',new TextType(true,'invalid_code',0,20));  
				$this->add('num_table',new IntType(false,'invalid_num_table','0','100000000000')); 
				$this->add('arrival_date',new DateType(true,'invalid_arrival_time')); 
				$this->add('agent_name',new TextType(false,'invalid_agent_name',0,255)); 
				$this->add('agent_address',new TextType(false,'invalid_agent_address',0,255)); 
				$this->add('agent_phone',new TextType(false,'invalid_agent_phone',0,255)); 
				$this->add('deposit',new FloatType(false,'invalid_deposit','0','100000000000')); 
				//$this->add('reservation_id',new IDType(false,'invalid_reservation_id','reservation')); 
				$this->add('bar_id',new IDType(true,'invalid_bar_id','bar')); 
				//$this->add('room_id',new IDType(false,'invalid_room_id','room')); 
				$this->link_css('packages/hotel/skins/default/css/suggestion.css');
				$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');				
			}
		}
		$this->link_js('packages/hotel/packages/restaurant/includes/js/update_price.js');
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
				$deposit = String::convert_to_vnnumeric(Url::get('deposit'));
				$total = String::convert_to_vnnumeric(Url::get('sum_total'));
				DB::update('bar_reservation', array(
					'reservation_room_id'=>Url::get('reservation_id'),
					'room_id',
					'code', 
					'arrival_time'=>Date_Time::to_time(URL::get('arrival_date'))+(Url::get('arrival_time_in_hour')*3600)+(Url::get('arrival_time_in_munite')*60),
					'departure_time'=>Date_Time::to_time(URL::get('arrival_date'))+(Url::get('arrival_time_out_hour')*3600)+(Url::get('arrival_time_out_munite')*60),
					'note', 
					'agent_name', 
					'agent_address', 
					'agent_phone', 
					'agent_fax', 
					'receiver_name', 
					'bar_id',
					'num_table',
					'deposit'=>$deposit,
					'prepaid'=>$deposit,
					'total'=>$total,
					),'id='.Url::get('id')
				);
				
				//xoa cac ban ghi hien co trong bang bar_reservation_table va bar_reservation_product
				DB::delete('bar_reservation_table','bar_reservation_id=\''.Url::get('id').'\'');
				DB::delete('bar_reservation_product','bar_reservation_id=\''.Url::get('id').'\'');
				
				//them vao bang bar_reservation_table
				$list_tables = array();
				if(Url::check('table__reservation_table_id')) 
				{
					$list_tables['table_id'] = Url::get('table__reservation_table_id');
					$sample = current($list_tables);
					foreach($sample as $row=>$row_data)
					{
						$blank1 = true;
						$item = array('bar_reservation_id'=>Url::get('id'));
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
							DB::insert('bar_reservation_table',$item
								+array(
								)
							);
						}
					}
				}
				
				$list_product = array();
				if(Url::check('product__id') and Url::get('product__id')!='')
				{
					$list_product['product_id'] = Url::get('product__id');
					$list_product['quantity'] = Url::get('product__quantity');
					$list_product['price'] = Url::get('product__price');
					$sample = current($list_product);
					foreach($sample as $row=>$row_data)
					{
						$list_product['price'][$row] = String::convert_to_vnnumeric($list_product['price'][$row]);
						$blank = true;
						$item = array('bar_reservation_id'=>Url::get('id'));
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
				$title = ''
					.substr(URL::get('code'),0,32).',  ' .substr(URL::get('status'),0,32).',  '     .substr(URL::get('time'),0,32).',  ';
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
					.((URL::get('note')!=$row['note'])?'Change '.Portal::language('note').' from '.substr($row['note'],0,255).' to '.substr(URL::get('note'),0,255).'<br>  ':'') 
					.((URL::get('reservation_id')!=$row['reservation_id'])?'Change '.Portal::language('reservation_id').' from <a href="?page=reservation&id='.$row['reservation_id'].'">#'.$row['reservation_id'].'</a> to <a href="?page=reservation&id='.URL::get('reservation_id').'">#'.URL::get('').'</a><br>  ':'')
					.((URL::get('room_id')!=$row['room_id'])?'Change '.Portal::language('room_id').' from <a href="?page=room&id='.$row['room_id'].'">#'.$row['room_id'].'</a> to <a href="?page=room&id='.URL::get('room_id').'">#'.URL::get('').'</a><br>  ':'') 
					.((URL::get('bar_id')!=$row['bar_id'])?'Change '.Portal::language('bar_id').' from <a href="?page=bar&id='.$row['bar_id'].'">#'.$row['bar_id'].'</a> to <a href="?page=bar&id='.URL::get('bar_id').'">#'.URL::get('').'</a><br>  ':'') 
					.((URL::get('receptionist_id')!=$row['receptionist_id'])?'Change '.Portal::language('receptionist_id').' from <a href="?page=employee_profile&id='.$row['receptionist_id'].'">#'.$row['receptionist_id'].'</a> to <a href="?page=employee_profile&id='.URL::get('receptionist_id').'">#'.URL::get('').'</a><br>  ':'') 
					.((URL::get('payment_type_id')!=$row['payment_type_id'])?'Change '.Portal::language('payment_type_id').' from <a href="?page=payment_type&id='.$row['payment_type_id'].'">#'.$row['payment_type_id'].'</a> to <a href="?page=payment_type&id='.URL::get('payment_type_id').'">#'.URL::get('').'</a><br>  ':'') ;
				System::log('edit',Portal::language('edit').' '.$title,$description,$_REQUEST['id']);
				echo '<div id="progress"><img src="packages/core/skins/default/images/updating.gif" /> Updating table status to server...</div>';
				echo '<script>
				if(window.opener)
				{
					window.opener.history.go(0);
				}
				window.setTimeout("location=\''.Url::build('bar_reservation',array('id'));.'\'",1000);
				</script>';
				exit();
			}
		}
	}	
	function draw()
	{	
		require_once 'packages/hotel/packages/restaurant/includes/table.php';
		require_once 'packages/hotel/includes/php/hotel.php';		
		$row = DB::select('bar_reservation',Url::get('id'));
		//============================== bar_table ===============================
		$bar_id = Url::check('bar_id')?Url::get('bar_id'):$row['bar_id'];
		$from_time = Url::check('arrival_time')?(Date_Time::to_time(URL::get('arrival_time'))+(Url::get('arrival_time_in_hour')*3600)+(Url::get('arrival_time_in_munite')*60)):$row['arrival_time'];
		$to_time = Url::check('arrival_time')?(Date_Time::to_time(URL::get('arrival_time'))+(Url::get('arrival_time_out_hour')*3600)+(Url::get('arrival_time_out_munite')*60)):$row['departure_time'];
		$db_items = Table::get_available_table($bar_id,$from_time,$to_time);//DB::select_all('bar_table','bar_id="'.$bar_id.'"');
		$select_table_options = '';
		foreach($db_items as $item)
		{
			$select_table_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}
		
		$tables = DB::select_all('bar_table',false,'id');
		$i=0;
		foreach($tables as $k=>$tb)
		{
			$tables[$k]['stt']=$i;
			$i++;
		}
		
		$table_items = BarReservationDB::get_bar_table(Url::get('id'));
		$str_tables = '';
		foreach($table_items as $k0=>$tb0)
		{
			$str_tables .= ','.$tb0['id'];
		}
		$str_tables = substr($str_tables,1);
		
		$cond_bar = 'bar_id=\''.$bar_id.'\'';
		foreach($table_items as $tkey=>$tbl)
		{
			$table_items[$tkey]['reservation_table_id'] = $tbl['id'];
			$table_id_options = '';
			$db_items = Table::get_available_table($bar_id,$from_time,$to_time,$str_tables);//DB::select_all('bar_table',false,'id');
			foreach($db_items as $item)
			{
				if($item['id']==$tbl['id'])
				{
					$table_id_options .= '<option value="'.$item['id'].'" selected>'.$item['name'].'</option>';
				}
				else
				{
					$table_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
				}
			}
			$table_items[$tkey]['table_id_options'] = $table_id_options;
		}
		
		//============================== product ===============================
		$product = BarReservationDB::get_products();
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
		
		//danh sach bar
		DB::query('select id, name from bar order by name');
		$rows_list=DB::fetch_all();
		$list_bar[0]='-------';
		$list_bar=$list_bar+String::get_list($rows_list,'name');
		
		//danh sach room

		$rows_list=Hotel::get_reservation_room();
		$list_room[0]='-------';
		$list_room=$list_room+String::get_list($rows_list,'name');
		
		//danh sach reservation
		$rows_list = Hotel::get_reservation_guest();
		$list_reservation[0]='-------';
		$list_reservation=$list_reservation+String::get_list($rows_list,'name');
		//danh sach phong - ten khach
		$reservation_room_list = Hotel::get_reservation_room_guest();
		
		$hotel = array(
			'room_id_list'=>$list_room,
			'reservation_id_list'=>$list_reservation,
			'reservation_id'=>Url::get('reservation_id',$row['reservation_room_id']),
			'reservation_room_list'=>$reservation_room_list,
		);

		$product_items = BarReservationDB::get_reservation_product();
		$sumary = 0;
		foreach($product_items as $key=>$value)
		{
			$product_items[$key]['product__id'] = $value['id'];
			$product_items[$key]['product__name'] = $value['name'];
			$product_items[$key]['product__quantity'] = $value['quantity'];
			$product_items[$key]['product__price'] = System::display_number_report($value['price']);
			$product_items[$key]['product__total'] = System::display_number_report(($value['price']*$value['quantity']));
			$product_items[$key]['product__unit'] = $value['unit_name'];
			$sumary += $value['quantity']*$value['price'];
		}
		if($row['arrival_time']>0 and $row['arrival_time']!='')
		{
			$row['arrival_date']=date('d/m/Y',$row['arrival_time']);
			$row['time_in_hour']=date('H',$row['arrival_time']);
			$row['time_in_munite']=date('i',$row['arrival_time']);
		}
		else
		{
			$row['arrival_time']='';
			$row['time_in_hour']=0;
			$row['time_in_munite']=0;
		}
		if($row['departure_time']>0 and $row['departure_time']!='')
		{
			$row['time_out_hour']=date('H',$row['departure_time']);
			$row['time_out_munite']=date('i',$row['departure_time']);
		}
		else
		{
			$row['time_out_hour']=0;
			$row['time_out_munite']=0;
		}
		
		$row['sumary'] = System::display_number_report($sumary);
		$row['bar_fee'] = System::display_number_report($sumary*5/100);
		$row['sum_total'] = System::display_number_report($row['total']);
		$row['deposit'] = System::display_number_report($row['deposit']);
		
		$this->parse_layout('edit',$row+$hotel+array(
			'table_id_options'=>$select_table_options,
			'date'=>date('d/m/Y',time()),
			'tables'=>$tables,
			'table_items'=>$table_items,
			'product'=>$product,
			'product_items'=>$product_items,
			'bar_id_list'=>$list_bar,
			'arrival_date1'=>Url::check('arrival_time')?Url::get('arrival_time'):$row['arrival_date'],
			'time_in_hour1'=>Url::check('arrival_time_in_hour')?Url::get('arrival_time_in_hour'):$row['time_in_hour'],
			'time_in_munite1'=>Url::check('arrival_time_in_munite')?Url::get('arrival_time_in_munite'):$row['time_in_munite'],
			'time_out_hour1'=>Url::check('arrival_time_out_hour')?Url::get('arrival_time_out_hour'):$row['time_out_hour'],
			'time_out_munite1'=>Url::check('arrival_time_out_munite')?Url::get('arrival_time_out_munite'):$row['time_out_munite'],
		));
	}
}
?>