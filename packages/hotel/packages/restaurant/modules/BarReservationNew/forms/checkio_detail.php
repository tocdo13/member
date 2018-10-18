<?php
class DetailBarForm extends Form
{
	function DetailBarForm()
	{
		Form::Form("DetailBarForm");
		DB::query('select status from bar_reservation where id=\''.Url::get('id').'\' and status in (\'CHECKIN\',\'CHECKOUT\',\'RESERVATION\')');
		if(!DB::fetch())
		{
			Url::redirect_current(array('id','cmd'=>'detail'));
		}
		else
		{
			$this->add('id',new IDType(true,'object_not_exists','bar_reservation'));
		}
		$this->link_css('skins/default/restaurant.css');		
		$this->link_js('packages/core/includes/js/jquery/disable.text.select.js');
	}
	function draw()
	{
		require_once 'packages/core/includes/utils/currency.php';
		$row = DB::select('bar_reservation',Url::get('id'));
		$row['customer_name'] = $row['customer_id']?DB::fetch('select id,name from customer where id = '.$row['customer_id'].'','name'):'';
		$row['exchange_rate'] = $row['exchange_rate']?$row['exchange_rate']:DB::fetch('select id,exchange from currency where id=\'VND\'','exchange');
		$order_id = '';
		for($i=0;$i<6-strlen($row['id']);$i++)
		{
			$order_id .= '0';
		}
		$order_id .= $row['id'];
		//============================== bar_table ===============================

		$table_items = BarReservationNewDB::get_bar_table(Url::get('id'));
		$tables_num=count($table_items);
		
		$tables_name = '';
		$row['num_people'] = 0;
		foreach($table_items as $tkey1=>$tbl)
		{
			$row['num_people'] += $tbl['num_people'];
			$tables_name .= ','.($tbl['name'].' (Order: '.$tbl['order_person']).')<br>';
		}
		$tables_name = substr($tables_name,1);
		
		//============================== bar_product ===============================
		$product_items = BarReservationNewDB::get_reservation_product();
		$product_num = count($product_items);
		
		$total_discount = 0;
		$total_price = 0;
		$product_tmp = array();
		foreach($product_items as $key=>$value)
		{
			if(isset($product_tmp[$value['product_id']])){
				$product_tmp[$value['product_id']]['quantity'] += $value['quantity'];
				$product_tmp[$value['product_id']]['quantity_discount'] += $value['quantity_discount'];
			}else{
				$product_tmp[$value['product_id']]['id'] = $value['product_id'];			
				$product_tmp[$value['product_id']]['product_id'] = $value['product_id'];
				$product_tmp[$value['product_id']]['name'] = $value['name'];
				$product_tmp[$value['product_id']]['quantity'] = $value['quantity'];
				$product_tmp[$value['product_id']]['quantity_discount'] = $value['quantity_discount'];
				$product_tmp[$value['product_id']]['unit_name'] = $value['unit_name'];
				$product_tmp[$value['product_id']]['price'] = $value['price'];// tu tien $ de hien thi ra tien vnd
				$product_tmp[$value['product_id']]['discount_rate'] = $value['discount_rate'];
				$product_tmp[$value['product_id']]['printed'] = $value['printed'];
			}
			if(Url::get('act')=='print_kitchen'){
				DB::update('bar_reservation_product',array('printed'=>1),'id = '.$key.'');
			}
		}
		$product_items = $product_tmp;
		foreach($product_items as $key=>$value)
		{
			$product_items[$key]['product__id'] = $value['product_id'];
			$product_items[$key]['product__name'] = $value['name'];
			$product_items[$key]['product__quantity'] = $value['quantity'];
			$product_items[$key]['product__remain_quantity'] = $value['quantity']-$value['quantity_discount'];
			$product_items[$key]['product__quantity_discount'] = $value['quantity_discount'];
			$product_items[$key]['product__discount'] = $value['discount_rate']?$value['discount_rate'].'%':'';
			$product_items[$key]['product__unit'] = $value['unit_name'];
			$product_items[$key]['product__price'] = System::display_number($value['price']);
			$ttl = $value['price']*($value['quantity']- $value['quantity_discount']);
			$discnt = ($ttl*$value['discount_rate']/100);
			$total_discount += $discnt;
			$total_price += $ttl-$discnt;
			$product_items[$key]['product__total'] = System::display_number($ttl-$discnt);
		}
		$row['time_begin'] = date('d/m/Y H:i',$row['time_in']);
		$row['time_end'] = $row['time_out']!=0?date('d/m/Y H:i',$row['time_out']):date('d/m/Y H:i',time());
		$row['time_in_hour']=date('H',$row['time_in']);
		$row['time_in_munite']=date('i',$row['time_in']);
		if($row['time_out']!=0)
		{
			$row['time_out_hour'] = date('H',$row['time_out']);
			$row['time_out_munite'] = date('i',$row['time_out']);
		}
		else
		{
			$row['time_out_hour']=date('H',time());
			$row['time_out_munite']=date('i',time());
		}
		$row['deposit_date'] = Date_Time::convert_orc_date_to_date($row['deposit_date'],'/');
		$row['bar_fee'] = (($total_price*$row['bar_fee_rate']/100));
		$row['total_before_tax'] = ($total_price+$total_price*$row['bar_fee_rate']/100);
		$row['tax'] = ($row['total_before_tax']*$row['tax_rate']/100);
		$row['sum_total'] = $row['total_before_tax'] + $row['tax'];//String::vnd_round(
		$row['remain_prepaid'] = $row['sum_total'] - $row['prepaid'];
		
		$row['discount'] = System::display_number($row['discount']);
		$row['tax'] = System::display_number($row['tax']);
		$row['bar_fee'] = System::display_number($row['bar_fee']);
		$row['prepaid'] = System::display_number($row['prepaid']);
		$row['remain_prepaid_usd'] = System::display_number(round($row['sum_total']/$row['exchange_rate'],2));
		$row['remain_prepaid'] = System::display_number($row['remain_prepaid']);
		$row['total_before_tax'] = System::display_number($row['total_before_tax']);
		$row['sum_total_usd'] = System::display_number(round($row['sum_total']/$row['exchange_rate'],2));
		$row['sum_total'] = System::display_number($row['sum_total']);
		//ten payment_type
		if($row['payment_type_id'] and $payment_type = DB::select('payment_type',$row['payment_type_id']))
		{
			$payment_name = $payment_type['name_'.Portal::language()];
		}
		else
		{
			$payment_name = Portal::language('none');
		}
		
		if(User::can_view(false,ANY_CATEGORY))
		{
			//ten reservation
			DB::query('
				select
					concat(CONCAT(traveller.first_name,\' \'),traveller.last_name) as name,
					room.name as room_name
				from
					reservation_room
					left outer join traveller on traveller.id=reservation_room.traveller_id
					left outer join room on room.id = reservation_room.room_id
				where
					reservation_room.id='.$row['reservation_room_id'].'
			');
			$reservation=DB::fetch();
			$hotel = array(
				'room_name'=>$reservation['room_name'],
				'reservation_name'=>$reservation['name'],
			);
		}
		else
		{
			$hotel = array();
		}
		//ten kieu thanh toan
		if($row['payment_result']!='')
		{
			$pkind = 1;
			if($row['payment_result']=='CASH')
			{
				$payment_kind = Portal::language('pay_now');
			}
			else
			if($row['payment_result']=='ROOM')
			{
				$payment_kind = Portal::language('pay_by_room');
			}
			else
			if($row['payment_result']=='DEBT')
			{
				$payment_kind = Portal::language('pay_by_debt');
			}
			else
			if($row['payment_result']=='BREAKFAST')
			{
				$payment_kind = Portal::language('pay_by_breakfast');
			}
			else
			if($row['payment_result']=='FREE')
			{
				$payment_kind = Portal::language('pay_by_free');
			}
			else
			{
				$payment_kind = Portal::language('pay_by_tour');
			}
		}
		else
		{
			$pkind = 0;
			$payment_kind = Portal::language('No_payment_type');
		}
		$currency_ids = DB::fetch_all('
			select
				id,name
			from
				currency
			where
				allow_payment=1
			order by
				name
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
		$total_currency = 0;
		$credit_card = 0;
		foreach($payment_detail as $key=>$value)
		{
			if($value['currency_id']=='USD')
			{
				$credit_card = $credit_card + $value['amount'];				
			}
			else
			{
				if(isset($payment[$value['currency_id']]))
				{
					$payment[$value['currency_id']] = $payment[$value['currency_id']] + $value['amount'];
				}
				else
				{
					$payment[$value['currency_id']] = $value['amount'];
				}
			}
			$total_currency = $total_currency + $value['amount']/$value['exchange_rate'];			
		}
		$payment['USD'] = round($row['total'] - $total_currency,2);		
		$row['exchange'] = System::display_number($row['exchange_rate']);
		if(Url::get(md5('act')) == md5('print'))
		{
			$this->parse_layout('print',$row+$hotel+array(
				'preview'=>Url::get(md5('preview'))?Portal::language('preview_order').' '.Portal::language('not_for_payment'):'',
				'order_id'=>$order_id,
				'payment_detail'=>$payment_detail,
				'pay_by_usd'=>$payment['USD'],
				'new_receptionist_id'=>$row['receptionist_id'],
				'new_server_id'=>$row['server_id'],
				'total_discount'=>System::display_number($total_discount),
				'amount'=>System::display_number($total_price + $total_discount),
				'payment_kind'=>$payment_kind,
				'payment_type_name'=>$payment_name,
				'invoice_date'=>date('d/m/Y',time()),
				'invoice_hour'=>date('H:i',time()),
				'tables_name'=>$tables_name,
				'product_items'=>$product_items,
			));
		}else if(Url::check(array('act'=>'print_kitchen')))
		{
			$this->parse_layout('print_kitchen',$row+$hotel+array(
				'order_id'=>$order_id,
				'payment_detail'=>$payment_detail,
				'pay_by_usd'=>$payment['USD'],
				'new_receptionist_id'=>$row['receptionist_id'],
				'new_server_id'=>$row['server_id'],
				'total_discount'=>System::display_number($total_discount),
				'amount'=>System::display_number($total_price),
				'payment_kind'=>$payment_kind,
				'payment_type_name'=>$payment_name,
				'invoice_date'=>date('d/m/Y',time()),
				'invoice_hour'=>date('H:i',time()),
				'tables_name'=>$tables_name,
				'product_items'=>$product_items,
			));
		}else if(Url::check(array('act'=>'print_b_e_order'))){
			$this->parse_layout('print_b_e_order',$row+$hotel+array(
				'order_id'=>$order_id,
				'payment_detail'=>$payment_detail,
				'pay_by_usd'=>$payment['USD'],
				'new_receptionist_id'=>$row['receptionist_id'],
				'new_server_id'=>$row['server_id'],
				'total_discount'=>System::display_number($total_discount),
				'amount'=>System::display_number($total_price),
				'payment_kind'=>$payment_kind,
				'payment_type_name'=>$payment_name,
				'invoice_date'=>date('d/m/Y',time()),
				'invoice_hour'=>date('H:i',time()),
				'tables_name'=>$tables_name,
				'product_items'=>$product_items,
			));	
		}else{
			$this->parse_layout('checkio_detail',$row+array(
				'order_id'=>$order_id,
				'total_discount'=>System::display_number($total_discount),
				'amount'=>System::display_number($total_price),
				'payment_kind'=>$payment_kind,
				'pkind'=>$pkind,
				'room_name'=>isset($room_name)?$room_name:'',
				'payment_type_name'=>$payment_name,
				'reservation_name'=>$reservation['name'],
				'date'=>date('d/m/Y',time()),
				'table_items'=>$table_items,
				'tables_num'=>$tables_num,
				'product_items'=>$product_items,
				'product_num'=>$product_num,
			));
		}
	}
}
?>