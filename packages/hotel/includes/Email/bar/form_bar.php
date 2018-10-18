<?php
    function BarInvoiceEmailForm()
    {
        $array_bar = array();
        $bar_items = DB::fetch('
			SELECT
				bar_reservation.id,
				bar.name as bar_name,
				party.full_name as user_name,
                bar_reservation.receiver_name,
				bar_reservation.status,bar_reservation.departure_time,
                bar_reservation.agent_name,
                room.name as room_name
			FROM
				bar_reservation
				INNER JOIN bar on bar_reservation.bar_id = bar.id
				LEFT OUTER JOIN party on party.user_id = bar_reservation.checked_out_user_id AND party.type=\'USER\'
                left join reservation_room ON bar_reservation.reservation_room_id = reservation_room.id
                left join room ON reservation_room.room_id = room.id
                left outer join traveller on traveller.id=reservation_room.traveller_id
				left outer join room on room.id = reservation_room.room_id
			WHERE
				bar_reservation.id='.Url::get('id').''
                );
        $array_bar['table_checkout'] =date('H:i d/m/Y',$bar_items['departure_time']);
        $array_bar['bar_name'] = $bar_items['bar_name'];    
        $array_bar['room_name'] = $bar_items['room_name'];
        $array_bar['receiver_name']=$bar_items['receiver_name'];
        $array_bar['agent_name'] = $bar_items['agent_name'];
        $row = DB::select('bar_reservation',Url::get('id'));
        $array_bar['customer_name'] = $row['reservation_room_id']?DB::fetch('
                                                                    SELECT customer.name AS cname,traveller.first_name || \' \' || traveller.last_name AS fullname
                                                                    FROM bar_reservation
                                                                        left JOIN reservation_traveller ON bar_reservation.reservation_traveller_id = reservation_traveller.id
                                                                        left JOIN reservation_room ON bar_reservation.reservation_room_id = reservation_room.id
                                                                        left JOIN reservation ON reservation_room.reservation_id = reservation.id
                                                                        left JOIN customer ON reservation.customer_id = customer.id
                                                                        left JOIN traveller ON reservation_traveller.traveller_id = traveller.id 
                                                                    WHERE bar_reservation.id='.Url::get('id').' 
                                                                    '):''; 
        $order_id = '';
		for($i=0;$i<6-strlen($row['id']);$i++)
		{
			$order_id .= '0';
		}
		$order_id .= $row['id'];
        $array_bar['order_id'] =$order_id;
        $table_items = DB::fetch_all('
                                    select 
                    				bar_table.id as table_id,
                    				bar_reservation_table.id,
                    				bar_table.code as code,
                    				bar_reservation_table.num_people as num_people,
                    				bar_reservation_table.order_person,
                    				bar_table.name as name
                    			from 
                    				bar_reservation_table
                    				inner join bar_table on bar_table.id = bar_reservation_table.table_id
                                    inner join bar_reservation on bar_reservation.id = bar_reservation_table.bar_reservation_id
                    			where 
                    				bar_reservation_table.bar_reservation_id='.Url::get('id').'
                    			order by
                    				bar_reservation_table.id 
                                ');
		$tables_num=count($table_items);
		$tables_name = '';
		$array_bar['num_people'] = 0;
		foreach($table_items as $tkey1=>$tbl)
		{
			$array_bar['num_people'] += $tbl['num_people'];
			$tables_name .= ','.($tbl['name']);            
		}
		$tables_name = substr($tables_name,1);
        $array_bar['tables_name'] =$tables_name;
        
        $product_items = DB::fetch_all('
                                                    select 
                                        				bar_reservation_product.id,
                                        				bar_reservation_product.product_id,
                                        				CASE
                                        					WHEN
                                        						bar_reservation_product.name is not null
                                        					THEN
                                        						lower(bar_reservation_product.name)
                                        					ELSE
                                        						lower(product.name_'.Portal::language().')
                                        				END name,			
                                        				product.category_id,
                                        				bar_reservation_product.quantity as quantity, 
                                        				bar_reservation_product.quantity_discount as quantity_discount,
                                        				bar_reservation_product.quantity_cancel as quantity_cancel, 
                                        				bar_reservation_product.price, 
                                        				bar_reservation_product.discount_rate as discount_rate,
                                        				bar_reservation_product.discount_category,
                                        				unit.name_'.Portal::language().' as unit_name ,
                                        				DECODE(bar_reservation_product.printed,null,0,bar_reservation_product.printed) as printed
                                                        ,bar_reservation_product.remain
                                        				,DECODE(bar_reservation_product.printed_cancel,null,0,bar_reservation_product.printed_cancel) as printed_cancel
                                        				,bar_reservation_product.note
                                        				,product.type
                                                        ,bar_reservation.tax_rate
                                                        ,bar_reservation.bar_fee_rate
                                        			from 
                                        				bar_reservation_product
                                                        inner join bar_reservation on bar_reservation.id = bar_reservation_product.bar_reservation_id  
                                        				INNER JOIN product_price_list on product_price_list.id = bar_reservation_product.price_id
                                        				INNER JOIN product on product.id = bar_reservation_product.product_id
                                        				LEFT OUTER join unit on unit.id = product.unit_id 
                                        				LEFT OUTER JOIN product_category ON product_category.id = product.category_id
                                        			where
                                        				bar_reservation_product.bar_reservation_id='.Url::iget('id').'
                                        			order by
                                        				bar_reservation_product.id
                                    ');
        $product_num = count($product_items);
		$total_discount = 0;
		$total_price = 0;
		$product_tmp = array();
        
        foreach($product_items as $key=>$value)
		{
			if(isset($product_tmp[$key]))
            {
				$product_tmp[$key]['quantity'] += $value['quantity'];
				$product_tmp[$key]['quantity_discount'] += $value['quantity_discount'];
			}
            else
            {
				if(Url::get('act')== 'print_kitchen')
                {
					if(($value['quantity'] - $value['printed'])>0 )
                    {
						$product_tmp[$key]['id'] = $key;			
						$product_tmp[$key]['product_id'] = $value['product_id'];
						$product_tmp[$key]['name'] = $value['name'];
						$product_tmp[$key]['quantity'] = $value['quantity'];
						$product_tmp[$key]['quantity_discount'] = $value['quantity_discount'];
						$product_tmp[$key]['quantity_cancel'] = $value['quantity_cancel'];
						$product_tmp[$key]['unit_name'] = $value['unit_name'];
						$product_tmp[$key]['price'] = $value['price'];
                        $product_tmp[$key]['tax_rate'] = $value['tax_rate'];
                        $array_bar['tax_rate'] = $value['tax_rate'];
                        $product_tmp[$key]['bar_fee_rate'] = $value['bar_fee_rate'];
						$product_tmp[$key]['discount_rate'] = $value['discount_rate'];
						$product_tmp[$key]['discount_category'] = $value['discount_category'];
						$product_tmp[$key]['printed'] = $value['quantity'] - $value['printed'];
						$product_tmp[$key]['remain'] = $value['remain'];
						$product_tmp[$key]['note'] = $value['note'];
						if(Url::get('act')=='print_kitchen')
                        {
							DB::query('Update bar_reservation_product set bar_reservation_product.printed = '.($value['printed'] + $product_tmp[$value['product_id']]['printed']).' where id='.$key.'');	
						}
					}
				}
                else
                {
					$product_tmp[$key]['id'] = $key;			
					$product_tmp[$key]['product_id'] = $value['product_id'];
					$product_tmp[$key]['name'] = $value['name'];
					$product_tmp[$key]['quantity'] = $value['quantity'];
					$product_tmp[$key]['quantity_discount'] = $value['quantity_discount'];
					$product_tmp[$key]['quantity_cancel'] = $value['quantity_cancel'];
					$product_tmp[$key]['unit_name'] = $value['unit_name'];
					$product_tmp[$key]['price'] = $value['price'];
                    $product_tmp[$key]['tax_rate'] = $value['tax_rate'];
                    $product_tmp[$key]['bar_fee_rate'] = $value['bar_fee_rate'];
                    $array_bar['tax_rate'] = $value['tax_rate'];
                    $array_bar['bar_fee_rate'] = $value['bar_fee_rate'];
					$product_tmp[$key]['discount_rate'] = $value['discount_rate'];
					$product_tmp[$key]['discount_category'] = $value['discount_category'];
					$product_tmp[$key]['printed'] = $value['quantity'] - $value['printed'];
					$product_tmp[$key]['remain'] = $value['remain'];
					$product_tmp[$key]['note'] = $value['note'];
					if(Url::get('act')=='print_kitchen')
                    {
						DB::query('Update bar_reservation_product set bar_reservation_product.printed = '.($value['printed'] + $product_tmp[$value['product_id']]['printed']).' where id='.$key.'');	
					}
				}
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
			$product_items[$key]['product__quantity_cancel'] = $value['quantity_cancel'];
			$product_items[$key]['product__discount'] = $value['discount_rate']?$value['discount_rate'].'%':'';
			$product_items[$key]['product__discount_category'] = $value['discount_category']?$value['discount_category'].'%':'';
			$product_items[$key]['product__unit'] = $value['unit_name'];
			$product_items[$key]['product__printed'] = $value['printed'];
			$product_items[$key]['product__price'] = System::display_number($value['price']);
			$product_items[$key]['product__remain'] = $value['remain'];
			$product_items[$key]['product__note'] = $value['note'];            
			$discount_category = $value['discount_category']?$value['discount_category']:0;
            $discount_rate = $value['discount_rate']?$value['discount_rate']:0;            
            if($row['discount_after_tax']==1)
            {
				$ttl = $value['price']*($value['quantity'] - $value['quantity_discount'] );
				$discnt = ($ttl*$discount_category/100);
                $discnt += ( $ttl - ($ttl*$discount_category/100) ) * $discount_rate/100;
				$total_discount += $discnt;
				if($ttl<0)
                {
					$ttl = 0;
				}
				$total_price += $ttl-$discnt;
			}
            else
            {
				if(Session::get('full_rate')==1)
                {
					$value['price'] = round(($value['price']/(1+$value['tax_rate']/100))/(1+$value['bar_fee_rate']/100),2);	
				}
                else if(Session::get('full_charge')==1)
                {
					$value['price'] = round($value['price']/(1+$value['bar_fee_rate']/100),2);
				}
				$ttl = $value['price']*($value['quantity'] - $value['quantity_discount']);;
				$discnt = ($ttl*$discount_category/100);
                $discnt += ( $ttl - ($ttl*$discount_category/100) ) * $discount_rate/100;;
                $total_discount += $discnt;
				if($ttl<0)
                {
					$ttl = 0;
				}
				$total_price += $ttl-$discnt;	
			}
			$product_items[$key]['product__total'] = System::display_number($ttl-$discnt);
			if($value['quantity'] == 0)//($value['quantity_cancel'] + $value['remain'])) //KimTan: vi trong database dang luu remain la so luong tra lai, con soluong duoc tu dong tru di sau moi lan tra lai.
			{
				$product_items[$key]['cancel_all'] = 1;
			}
            else
            {
				$product_items[$key]['cancel_all'] = 0;	
			}
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
		if($row['discount_after_tax']==1)
        {
			$row['bar_fee'] = round((($total_price)*$row['bar_fee_rate']/100),3);
			$row['total_before_tax'] = (($total_price)+($total_price)*$row['bar_fee_rate']/100);
			$row['tax'] =($row['total_before_tax']*$row['tax_rate']/100);
			$row['order_discount'] = $row['discount_percent'] + round((($row['total_before_tax'] + $row['tax']) - $row['discount']) * $row['discount_percent']/100,2);
		}
        else
        {// Giam gia trc thue
			$row['order_discount'] = $row['discount'] + round(($total_price - $row['discount']) * $row['discount_percent']/100,2);
			$before_tax = $total_price - $row['order_discount'];
			$row['bar_fee'] = round((($before_tax)*$row['bar_fee_rate']/100),3);
			$row['total_before_tax'] = (($before_tax)+($before_tax)*$row['bar_fee_rate']/100);
			$row['tax'] =($row['total_before_tax']*$row['tax_rate']/100);
		}
        
		$row['sum_total'] = $total_price + $row['bar_fee'] + $row['tax'] - $row['order_discount'];
		$row['prepaid'] = $row['prepaid'];
		$row['remain_prepaid'] = $row['sum_total'] - $row['order_discount'] - $row['prepaid'];
		$row['discount'] = System::display_number($row['discount']);
		$row['tax'] = System::display_number($row['tax']);
		$row['bar_fee'] = System::display_number($row['bar_fee']);
		$row['prepaid'] = System::display_number($row['prepaid']);
		$row['remain_prepaid_usd'] = System::display_number(round($row['sum_total']/$row['exchange_rate'],2));
		$row['remain_prepaid'] = System::display_number($row['remain_prepaid']);
		$row['total_before_tax'] = System::display_number($row['total_before_tax']);
		$row['sum_total_usd'] = System::display_number(round(($row['sum_total'] - $row['deposit'])/$row['exchange_rate'],2));
		$row['sum_total'] = round($row['sum_total'] - $row['deposit'],0);
		$row['deposit'] = System::display_number($row['deposit']);
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
        $array_bar['product_items']=$product_items;
        $array_bar['total_discount'] = System::display_number($total_discount);
        $array_bar['amount'] = System::display_number(round($total_price + $total_discount,2));
        $array_bar['total_discount'] = System::display_number($total_discount);
        $array_bar['discount_percent'] = $row['discount_percent'];
        $array_bar['order_discount'] = $row['order_discount'];
        $array_bar['bar_fee'] =$row['bar_fee'];
        $array_bar['tax'] = $row['tax'];
        $array_bar['deposit'] = System::display_number($row['deposit']);
        //$array_bar['total_payment_traveller'] = $row['total_payment_traveller'];
        $array_bar['sum_total'] = round($row['sum_total'] - $row['deposit'],0);
        $array_bar['sum_total'] = System::display_number($array_bar['sum_total']);
        $array_bar['remain_prepaid'] = $row['remain_prepaid'];
        $array_bar['prepaid'] =$row['prepaid'];
        return $array_bar;
    }
?>