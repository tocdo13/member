<?php
class CheckInBarForm extends Form
{
	function CheckInBarForm()
	{
		Form::Form('CheckInBarForm');
		DB::query('select status from bar_reservation where id=\''.Url::get('id').'\' and status in (\'CHECKIN\',\'CHECKOUT\')');
		if(!DB::fetch())
		{
			Url::redirect_current(array('cmd'=>'edit','id'));
		}
		else
		{
			$this->add('code',new TextType(true,'invalid_code',0,20));  
			$this->add('num_table',new IntType(false,'invalid_num_table','0','100000000000')); 
			$this->add('agent_name',new TextType(false,'invalid_agent_name',0,255)); 
			$this->add('agent_address',new TextType(false,'invalid_agent_address',0,255)); 
			$this->add('agent_phone',new TextType(false,'invalid_agent_phone',0,255)); 
			$this->add('prepaid',new FloatType(false,'invalid_prepaid','0','100000000000'));
			if(Url::get('check_out')) 
			{
				$this->add('payment_result',new TextType(true,'invalid_payment_result',0,255));
			}
			if(Url::get('check_out') and Url::get('payment_result')=='ROOM')
			{
				$this->add('room_id',new IDType(true,'room_id_is_required','reservation_room')); 				
			}
			//$this->add('reservation_id',new IDType(false,'invalid_reservation_id','reservation')); 
			//$this->add('room_id',new IDType(false,'invalid_room_id','room')); 
			$this->link_css('packages/hotel/skins/default/css/suggestion.css');
			$this->link_css('skins/default/restaurant.css');
			$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
			$this->link_js('packages/core/includes/js/multi_items.js');
		}
	}
	function on_submit()
	{
		if(URL::check(array('select_bar'=>0)))
		{
			if($this->check())
			{
				$discount = String::convert_to_vnnumeric(Url::get('discount'));
				$tax = String::convert_to_vnnumeric(Url::get('tax'));
				$total_before_tax = String::convert_to_vnnumeric(Url::get('total_before_tax'));
				$total = String::convert_to_vnnumeric(Url::get('sum_total'));
				$prepaid = String::convert_to_vnnumeric(Url::get('prepaid'));
				$bar_reser = DB::select('bar_reservation',Url::get('id'));
				if($bar_reser['status']=='CHECKOUT')
				{
					DB::update('bar_reservation', array(
						'code', 
						'note', 
						'agent_name', 
						'agent_address', 
						'agent_phone', 
						'num_table',
						'tax_rate',
						'server_id',
						'bar_fee_rate',
						'reservation_room_id'=>Url::get('reservation_id'),
						'room_id',
						'discount'=>$discount,
						'tax'=>$tax,
						'total_before_tax'=>$total_before_tax,
						'total'=>$total,
						'prepaid'=>$prepaid,
						'payment_result'=>Url::get('payment_result',''),
						'payment_type_id',
						),'id='.Url::get('id')
					);
				}
				else
				{
					$data = array();
					if(Url::get('check_out'))
					{
						$data = array('time_out'=>time());
					}
					//echo Url::get('server_id');exit();
					DB::update('bar_reservation',$data+ array(
						'reservation_room_id'=>Url::get('reservation_id'),
						'room_id',
						'code', 
						'note', 
						'agent_name', 
						'agent_address', 
						'agent_phone', 
						'num_table',
						'tax_rate',
						'bar_fee_rate',
						'receptionist_id'=>Url::get('server_id'), 
						'server_id',
						'discount'=>$discount,
						'tax'=>$tax,
						'total_before_tax'=>$total_before_tax,
						'total'=>$total,
						'prepaid'=>$prepaid,
						'payment_result'=>Url::get('payment_result',''),
						'payment_type_id',
						'status'=>Url::get('check_out')?'CHECKOUT':'CHECKIN'
						),'id='.Url::get('id')
					);
				}
				if(Url::get('payment_result')!='CASH')
				{
					DB::delete('pay_by_currency','bill_id=\''.Url::get('id').'\' and type=\'BAR\'');
				}
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
					$list_product['quantity_discount'] = Url::get('product__quantity_discount');
					$list_product['price'] = String::convert_to_vnnumeric(Url::get('product__price'));
					$list_product['discount_rate'] = Url::get('product__discount');
					$list_product['discount'] = $list_product['price'];
					$sample = current($list_product);
					foreach($sample as $row=>$row_data)
					{
						$list_product['discount'][$row] = $list_product['discount'][$row]*$list_product['discount_rate'][$row]/100;
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
							$id = DB::insert('bar_reservation_product',$item);
						}
					}
				}
				$currency = DB::select_all('currency');
				$old_item = DB::select_all('pay_by_currency','bill_id='.Url::get('id').' and type=\'BAR\'');
				if(isset($_REQUEST['currency_selecteds']))
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
				$title = ''
					.substr(URL::get('code'),0,32).',  ' .substr(URL::get('status'),0,32).',  '     .substr(URL::get('time'),0,32).',  ';
				if(isset($row))
				{
				$description = ''
					.((URL::get('code')!=$row['code'])?'Change '.Portal::language('code').' from '.substr($row['code'],0,255).' to '.substr(URL::get('code'),0,255).'<br>  ':'') 
					.((URL::get('status')!=$row['status'])?'Change '.Portal::language('status').' from '.substr($row['status'],0,255).' to '.substr(URL::get('status'),0,255).'<br>  ':'') 
					.((URL::get('num_table')!=$row['num_table'])?'Change '.Portal::language('num_table').' from '.substr($row['num_table'],0,255).' to '.substr(URL::get('num_table'),0,255).'<br>  ':'') 
					.((URL::get('agent_name')!=$row['agent_name'])?'Change '.Portal::language('agent_name').' from '.substr($row['agent_name'],0,255).' to '.substr(URL::get('agent_name'),0,255).'<br>  ':'') 
					.((URL::get('agent_address')!=$row['agent_address'])?'Change '.Portal::language('agent_address').' from '.substr($row['agent_address'],0,255).' to '.substr(URL::get('agent_address'),0,255).'<br>  ':'') 
					.((URL::get('time')!=$row['time'])?'Change '.Portal::language('time').' from '.substr($row['time'],0,255).' to '.substr(URL::get('time'),0,255).'<br>  ':'') 
					.((URL::get('agent_phone')!=$row['agent_phone'])?'Change '.Portal::language('agent_phone').' from '.substr($row['agent_phone'],0,255).' to '.substr(URL::get('agent_phone'),0,255).'<br>  ':'') 
					.((URL::get('total_before_tax')!=$row['total_before_tax'])?'Change '.Portal::language('total_before_tax').' from '.substr($row['total_before_tax'],0,255).' to '.substr(URL::get('total_before_tax'),0,255).'<br>  ':'') 
					.((URL::get('tax_rate')!=$row['tax_rate'])?'Change '.Portal::language('tax_rate').' from '.substr($row['tax_rate'],0,255).' to '.substr(URL::get('tax_rate'),0,255).'<br>  ':'') 
					.((URL::get('tax')!=$row['tax'])?'Change '.Portal::language('tax').' from '.substr($row['tax'],0,255).' to '.substr(URL::get('tax'),0,255).'<br>  ':'') 
					.((URL::get('discount')!=$row['discount'])?'Change '.Portal::language('discount').' from '.substr($row['discount'],0,255).' to '.substr(URL::get('discount'),0,255).'<br>  ':'') 
					.((URL::get('total')!=$row['total'])?'Change '.Portal::language('total').' from '.substr($row['total'],0,255).' to '.substr(URL::get('total'),0,255).'<br>  ':'') 
					.((URL::get('prepaid')!=$row['prepaid'])?'Change '.Portal::language('prepaid').' from '.substr($row['prepaid'],0,255).' to '.substr(URL::get('prepaid'),0,255).'<br>  ':'') 
					.((URL::get('note')!=$row['note'])?'Change '.Portal::language('note').' from '.substr($row['note'],0,255).' to '.substr(URL::get('note'),0,255).'<br>  ':'') 
					.((URL::get('reservation_id')!=$row['reservation_id'])?'Change '.Portal::language('reservation_id').' from <a href="?page=reservation&id='.$row['reservation_id'].'">#'.$row['reservation_id'].'</a> to <a href="?page=reservation&id='.URL::get('reservation_id').'">#'.URL::get('').'</a><br>  ':'')
					.((URL::get('room_id')!=$row['room_id'])?'Change '.Portal::language('room_id').' from <a href="?page=room&id='.$row['room_id'].'">#'.$row['room_id'].'</a> to <a href="?page=room&id='.URL::get('room_id').'">#'.URL::get('').'</a><br>  ':'') 
					.((URL::get('bar_id')!=$row['bar_id'])?'Change '.Portal::language('bar_id').' from <a href="?page=bar&id='.$row['bar_id'].'">#'.$row['bar_id'].'</a> to <a href="?page=bar&id='.URL::get('bar_id').'">#'.URL::get('').'</a><br>  ':'') 
					.((URL::get('receptionist_id')!=$row['receptionist_id'])?'Change '.Portal::language('receptionist_id').' from <a href="?page=employee_profile&id='.$row['receptionist_id'].'">#'.$row['receptionist_id'].'</a> to <a href="?page=employee_profile&id='.URL::get('receptionist_id').'">#'.URL::get('').'</a><br>  ':'') 
					.((URL::get('payment_type_id')!=$row['payment_type_id'])?'Change '.Portal::language('payment_type_id').' from <a href="?page=payment_type&id='.$row['payment_type_id'].'">#'.$row['payment_type_id'].'</a> to <a href="?page=payment_type&id='.URL::get('payment_type_id').'">#'.URL::get('').'</a><br>  ':'') ;
				}
				else
				{
					$description = '';
				}
				System::log('edit',Portal::language('edit').' '.$title,$description,$_REQUEST['id']);
				echo '<div id="progress"><img src="packages/core/skins/default/images/updating.gif" /> Updating table status to server...</div>';
				echo '<script>
				if(window.opener)
				{
					window.opener.history.go(0);
				}
				window.setTimeout("location=\''.Url::build('bar_reservation',array('cmd'=>'detail','id'=>Url::get('id'))).'\'",1000);
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
		$db_items = Table::get_available_table($row['bar_id'],$row['arrival_time'],$row['departure_time']);
		//$db_items = DB::select_all('bar_table');
		$select_table_options = '';
		foreach($db_items as $item)
		{
			$select_table_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}
		
		$tables = DB::select_all('bar_table',false,'id');
		$i=0;
		foreach($tables as $k=>$tbl)
		{
			$tables[$k]['stt']=$i;
			$i++;
		}

		$table_items = BarReservationDB::get_bar_table(Url::get('id'));
		
		$cond_bar = 'bar_id=\''.$row['bar_id'].'\'';
		foreach($table_items as $tkey=>$tbl)
		{
			$table_items[$tkey]['reservation_table_id'] = $tbl['id'];
			$table_id_options = '';
			$db_items = DB::select_all('bar_table',$cond_bar,'name');
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
		$product = BarReservationDB::get_products(' and res_product.status = \'avaiable\'');
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
		//danh sach bar
		//danh sach payment_type
		DB::query('select id, name_'.Portal::language().' as name from payment_type where structure_id<>'.ID_ROOT.' order by id');
		$rows_list = DB::fetch_all();
		$list_payment_type[0]='-------';
		$list_payment_type=$list_payment_type+String::get_list($rows_list,'name');
		//danh sach room

		$rows_list = Hotel::get_reservation_room();
		$list_room[0]='-------';
		$list_room=$list_room+String::get_list($rows_list,'name');
		
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
		
		$product_items = BarReservationDB::get_reservation_product();
		$total_before_tax = 0;
		foreach($product_items as $key=>$value)
		{
			$product_items[$key]['product__id'] = $value['id'];
			$product_items[$key]['product__name'] = $value['name'];
			$product_items[$key]['product__quantity'] = $value['quantity'];
			$product_items[$key]['product__quantity_discount'] = $value['quantity_discount'];
			$product_items[$key]['product__discount'] = $value['discount_rate'];
			$product_items[$key]['product__unit'] = $value['unit_name'];
			$product_items[$key]['product__price'] = System::display_number_report($value['price']);
			
			$ttl = $value['price']*($value['quantity']-$value['quantity_discount']);
			$discnt = $ttl*$value['discount_rate']/100;
			$product_items[$key]['product__total'] = System::display_number_report($ttl-$discnt);
			$total_before_tax += $ttl-$discnt;
		}
		
		$row['payment_type_id'] = Url::check('payment_type_id')?Url::get('payment_type_id'):$row['payment_type_id'];
		$row['time_in_hour']=date('H',$row['time_in']);
		$row['time_in_munite']=date('i',$row['time_in']);
		
		$row['sumary'] = System::display_number_report($total_before_tax);
		$row['bar_fee'] = System::display_number_report($total_before_tax*$row['bar_fee_rate']/100);
		
		$total_before = $total_before_tax+$total_before_tax*$row['bar_fee_rate']/100;
		$row['total_before_tax'] = System::display_number_report($total_before_tax);
		
		$tax = $total_before_tax*$row['tax_rate']/100;
		$row['tax'] = System::display_number_report($tax);
		$row['sum_total'] = System::display_number_report($total_before+$tax);
		$row['remain_prepaid'] = System::display_number_report($total_before+$tax - $row['prepaid']);
		$row['prepaid'] =System::display_number_report($row['prepaid']);
		$row['discount'] = System::display_number_report($row['discount']);
		
		$currency_ids = DB::fetch_all('
			select
				id,name
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
		$bar = Hotel::get_new_bar($row['bar_id']);
		$this->parse_layout('check_in',$row+$hotel+array(
			'select_table_options'=>$select_table_options,
			'bar_name'=>$bar['name'],
			'exchange_rate'=>$rate?$rate:$exchange_rate['exchange'],
			'pay_by_currency'=>$currency_ids,
			'date'=>date('d/m/Y',time()),
			'tables'=>$tables,
			'table_items'=>$table_items,
			'product'=>$product,
			'product_items'=>$product_items,
			'server_id_list'=>$list_server,
			'payment_type_id_list'=>$list_payment_type,
			'currency_id_options'=>$currency_id_options
		));
	}
}
?>