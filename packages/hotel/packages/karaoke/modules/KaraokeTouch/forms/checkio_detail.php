<?php
class DetailKaraokeForm extends Form
{
	function DetailKaraokeForm()
	{
		Form::Form("DetailKaraokeForm");
		DB::query('select status from karaoke_reservation where id=\''.Url::get('id').'\' and status in (\'CHECKIN\',\'CHECKOUT\',\'BOOKED\',\'RESERVATION\')');
		if(!DB::fetch())
		{
			Url::redirect_current(array('id','cmd'=>'detail','print'=>1));
		}
		else
		{
			$this->add('id',new IDType(true,'object_not_exists','karaoke_reservation'));
		}
		$this->link_js('packages/core/includes/js/jquery/disable.text.select.js');
	}
	function draw()
	{
		require_once 'packages/core/includes/utils/currency.php';
		$row = DB::fetch('select karaoke_reservation.*, karaoke.name as karaoke_name,
							customer.name as customer_name,
							CASE WHEN room.name is null
								THEN \'\'
								ELSE
							(traveller.first_name || \' \' || traveller.last_name || \' '.Portal::language('room').' \' || room.name)
							END  as reservation_room_name, FROM_UNIXTIME(karaoke_reservation.time) as time	
						FROM karaoke_reservation
							inner join karaoke on karaoke.id = karaoke_reservation.karaoke_id
							left outer join customer ON customer.id = karaoke_reservation.customer_id
							left outer join reservation_traveller ON reservation_traveller.id = karaoke_reservation.reservation_traveller_id
							left outer join reservation_room ON karaoke_reservation.reservation_room_id = reservation_room.id
							left outer join room ON reservation_room.room_id = room.id 
							left outer join traveller ON reservation_traveller.traveller_id = traveller.id
						WHERE karaoke_reservation.id = '.Url::get('id').'');
		$this->full_tax = $row['full_rate'];
		$this->full_charge =  $row['full_charge'];
		//System::debug($row);
		//$row['customer_name'] = $row['customer_id']?DB::fetch('select id,name from customer where id = '.$row['customer_id'].'','name'):'';
		$row['exchange_rate'] = $row['exchange_rate']?$row['exchange_rate']:DB::fetch('select id,exchange from currency where id=\'VND\'','exchange');
		$cond = '';
		$order_id = '';
		for($i=0;$i<6-strlen($row['id']);$i++)
		{
			$order_id .= '0';
		}
		$order_id .= $row['id'];
		//============================== karaoke_table ===============================

		$table_items = KaraokeTouchDB::get_karaoke_table(Url::get('id'));
		$tables_num=count($table_items);
		
		$tables_name = '';
		$row['num_people'] = 0;
		foreach($table_items as $tkey1=>$tbl)
		{
			$row['num_people'] += $tbl['num_people'];
			$tables_name .= ','.($tbl['name'].' (Order: '.$tbl['order_person']).')<br>';
		}
		$tables_name = substr($tables_name,1);
		//============================== karaoke_product ===============================
		$nh = DB::fetch('select structure_id from product_category where code=\'NH\'','structure_id');
		$cond_nh = ' AND product_category.structure_id > '.$nh.' and product_category.structure_id <'.IDStructure::next($nh).'';
		if(Url::get('act') == 'print_kitchen' and Url::get('type') == 'food'){
			$food = DB::fetch('select structure_id from product_category where code=\'DA\' '.$cond_nh.'','structure_id');
			$cond = ' AND product_category.structure_id > '.$food.' and product_category.structure_id <'.IDStructure::next($food).'';	
		}else if(Url::get('act') == 'print_kitchen' and Url::get('type') == 'drink'){
			$drink = DB::fetch('select structure_id from product_category where code=\'DU\' '.$cond_nh.'','structure_id');
			$cond = ' AND product_category.structure_id > '.$drink.' and product_category.structure_id <'.IDStructure::next($drink).'';	
		}
		//$cond = '';
		$product_items = KaraokeTouchDB::get_reservation_product($cond);
		$karaoke_name = KaraokeTouchDB::get_karaoke_reservation(Url::get('id'));
		//System::Debug($product_items); //exit();
		$product_num = count($product_items);
		$total_discount = 0;
		$total_price = 0;
        $order_discount = 0;
		$product_tmp = array();
		
		$row['total_discount'] = 0;
        $row['order_discount'] = 0;//GG tren sp
        
		foreach($product_items as $key=>$value)
		{
			if(isset($product_tmp[$key]))
            {
				$product_tmp[$key]['quantity'] += $value['quantity'];
				$product_tmp[$key]['quantity_discount'] += $value['quantity_discount'];
			}
            else
            {
				if(Url::get('act')== 'print_kitchen'){
					if(($value['quantity'] - $value['printed'])>0 ){
						
						$product_tmp[$key]['id'] = $key;			
						$product_tmp[$key]['product_id'] = $value['product_id'];
						$product_tmp[$key]['name'] = $value['name'];
						$product_tmp[$key]['quantity'] = $value['quantity'];
						$product_tmp[$key]['quantity_discount'] = $value['quantity_discount'];
						$product_tmp[$key]['quantity_cancel'] = $value['quantity_cancel'];
						$product_tmp[$key]['unit_name'] = $value['unit_name'];
						$product_tmp[$key]['price'] = $value['price'];
						$product_tmp[$key]['discount_rate'] = $value['discount_rate'];
						$product_tmp[$key]['discount_category'] = $value['discount_category'];
						$product_tmp[$key]['printed'] = $value['quantity'] - $value['printed'];
						$product_tmp[$key]['remain'] = $value['remain'];
						$product_tmp[$key]['note'] = $value['note'];
						if(Url::get('act')=='print_kitchen' && !$karaoke_name['print_karaoke_name'])
                        {
							DB::query('Update karaoke_reservation_product set karaoke_reservation_product.printed = '.($value['printed'] + $product_tmp[$key]['printed']).' where id='.$key.'');	
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
					$product_tmp[$key]['discount_rate'] = $value['discount_rate'];
					$product_tmp[$key]['discount_category'] = $value['discount_category'];
					$product_tmp[$key]['printed'] = $value['quantity'] - $value['printed'];
					$product_tmp[$key]['remain'] = $value['remain'];
					$product_tmp[$key]['note'] = $value['note'];
					if(Url::get('act')=='print_kitchen' && !$karaoke_name['print_karaoke_name'])
                    {
						DB::query('Update karaoke_reservation_product set karaoke_reservation_product.printed = '.($value['printed'] + $product_tmp[$key]['printed']).' where id='.$key.'');	
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
			if($row['discount_after_tax']==1)
            {
				$ttl = $value['price']*($value['quantity'] - $value['quantity_discount']);
				$discnt = ($ttl*$value['discount_category']/100) + (($ttl*(100-$value['discount_category'])/100)*$value['discount_rate']/100);
				$order_discount += $discnt;
				if($ttl<0)
                {
					$ttl = 0;
				}
				$total_price += $ttl-$discnt;
			}
            else
            {
				$ttl = $value['price']*($value['quantity'] - $value['quantity_discount']);
				$discnt = ($ttl*$value['discount_category']/100) + (($ttl*(100-$value['discount_category'])/100)*$value['discount_rate']/100); 
				$order_discount += $discnt;
				if($ttl<0)
                {
					$ttl = 0;
				}
				$total_price += $ttl-$discnt;	
			}
			$product_items[$key]['product__total'] = System::display_number($ttl-$discnt);
			//if($value['quantity'] == ($value['quantity_cancel'] + $value['remain'])){
				//$product_items[$key]['cancel_all'] = 1;
			//}else{
				$product_items[$key]['cancel_all'] = 0;	
			//}
		}
		$row['time_begin'] = date('d/m/Y H:i',$row['time_in']);
		$row['time_end'] = $row['time_out']!=0?date('d/m/Y H:i',$row['time_out']):date('d/m/Y H:i',time());
		$row['time_in_hour']=date('H',$row['time_in']);
		$row['time_in_munite']=date('i',$row['time_in']);
		$user_data = Session::get('user_data');
		$row['user_name'] = ($karaoke_name['status']=='CHECKOUT')?$karaoke_name['user_name']:$user_data['full_name'];
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
		$row['order_discount'] += $order_discount; // GG tren SP
        
		$row['guest_name'] = ($row['receiver_name']!='')?$row['receiver_name']:(($row['reservation_room_name']!='')?$row['reservation_room_name']:(($row['customer_name']!='')?$row['customer_name']:''));
		$total_before_tax = $row['total_before_tax'];
		if($row['full_rate'] ==1){
			$row['total_amount'] = $total_price + $total_discount;
			$row['total_discount'] += $row['discount'] + $total_price*$row['discount_percent']*0.01;
			$row['service_amount'] = 0;	
			$row['tax_amount'] = 0;			
		}else if($row['full_charge'] ==1){
			$row['total_amount'] = $total_price + $total_discount;
			$row['total_discount'] += $row['discount'] + $total_price*$row['discount_percent']*0.01;
			$row['service_amount'] = 0;	
			$row['tax_amount'] = round($row['total'] - (($row['total_amount'] - $row['total_discount']) + $row['service_amount']));
  }else{
			$row['total_amount'] = $total_price + $total_discount;
			$row['total_discount'] += $row['discount'] + $total_price*$row['discount_percent']*0.01;
			$row['service_amount'] = $total_before_tax * $row['karaoke_fee_rate'] * 0.01;	
			$row['tax_amount'] = ($total_before_tax + $row['service_amount']) * $row['tax_rate'] * 0.01;		
		}
		$row['total_after_discount'] =  System::display_number($row['total_amount']-$row['total_discount']);
		$row['total_amount'] =  System::display_number($row['total_amount']);
		$row['total_before_deposit'] = System::display_number($row['total']);
		$row['sum_total'] = System::display_number($row['total'] - $row['deposit']);
		$row['deposit'] = System::display_number($row['deposit']);
		$row['discount'] = System::display_number($row['discount']);
		$row['discount_percent'] = $row['discount_percent'];
        if($row['discount'] != 0)
        {
            $row['total_discount'] = $row['discount'];            
        }
        
		/*
		$row['order_discount'] = $row['discount'];
		$row['karaoke_fee'] = $row['total_before_tax'] * $row['karaoke_fee_rate'] * 0.01;
		$row['tax'] = ($row['total_before_tax'] + $row['karaoke_fee']) * $row['tax_rate'] *0.01;
		$row['sum_total'] = $total_price + $row['karaoke_fee'] + $row['tax'] - $row['order_discount'];
		$row['prepaid'] = $row['prepaid'];
		$row['remain_prepaid'] = $row['sum_total'] - $row['order_discount'] - $row['prepaid'];
		$row['discount'] = System::display_number($row['discount']);
		$row['tax'] = System::display_number($row['tax']);
		$row['karaoke_fee'] = System::display_number($row['karaoke_fee']);
		$row['prepaid'] = System::display_number($row['prepaid']);
		$row['remain_prepaid_usd'] = System::display_number(round($row['sum_total']/$row['exchange_rate'],2));
		$row['remain_prepaid'] = System::display_number($row['remain_prepaid']);
		$row['total_before_tax'] = System::display_number($row['total_before_tax']);
		$row['sum_total_usd'] = System::display_number(round(($row['sum_total'] - 
$row['deposit'])/$row['exchange_rate'],2));
		$row['sum_total'] = System::display_number(round($row['sum_total'] - $row['deposit'],0));
		$row['deposit'] = System::display_number($row['deposit']);*/
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
				bill_id = '.Url::get('id').' and type=\'karaoke\'
		');
		$total_currency = 0;
		$credit_card = 0;
		foreach($payment_detail as $key=>$value){
			if($value['currency_id']=='USD'){
				$credit_card = $credit_card + $value['amount'];				
			}else{
				if(isset($payment[$value['currency_id']])){
					$payment[$value['currency_id']] = $payment[$value['currency_id']] + $value['amount'];
				}else{
					$payment[$value['currency_id']] = $value['amount'];
				}
			}
			$total_currency = $total_currency + $value['amount']/$value['exchange_rate'];			
		}
		$payment['USD'] = round($row['total'] - $total_currency,2);		
		$row['exchange'] = System::display_number($row['exchange_rate']);
		//System::Debug($product_items);
		//System::Debug($row);
        $payment_list = array();
       
        $sing_room = array();
        $sing_room = DB::fetch_all("select karaoke_reservation_table.*, karaoke_table.name
                from karaoke_reservation_table 
                    inner join karaoke_table on karaoke_table.id = karaoke_reservation_table.table_id                                
                where karaoke_reservation_id = ".$row['id']." AND sing_end_time is not null");
        $amount_sing  =0;
        foreach($sing_room as $id1=>$content1)
        {
            $sing_room[$id1]['total'] = '';
            $sing_room[$id1]['total_time'] = 0;
            if($sing_room[$id1]['sing_start_time']!='' AND $sing_room[$id1]['sing_end_time']!='')
            {    
                $sing_room[$id1]['total'] = System::display_number(round(($content1['price']/3600)*($sing_room[$id1]['sing_end_time']-$sing_room[$id1]['sing_start_time']),0));
                $sing_room[$id1]['total_time'] = ($sing_room[$id1]['sing_end_time']-$sing_room[$id1]['sing_start_time']);
                $arr = explode(".",($sing_room[$id1]['total_time']/3600));
                $h = $arr[0];
                
                $sing_room[$id1]['total_time'] -= (3600 * $h);
                $arr = explode(".",($sing_room[$id1]['total_time']/60));
                if(isset($arr[1]))
                    $p = $arr[0]+1;
                else
                    $p = $arr[0];
                if($h<10)
                    $h = "0".$h;
                if($p<10)
                    $p="0".$p;
                $sing_room[$id1]['time'] = $h."h".$p."'";
                $amount_sing += System::calculate_number($sing_room[$id1]['total']);
                //$row['sum_total'] += System::calculate_number($sing_room[$id1]['total']);
            }
            $sing_room[$id1]['price'] = System::display_number($content1['price']);
        }
         if(Url::get('id'))
        {
            $payment_list = DB::fetch_all('SELECT payment.id, payment_type.name_'.Portal::language().' as payment_type_name, payment.amount, payment.currency_id FROM payment inner join payment_type on payment_type.def_code=payment.payment_type_id WHERE payment.bill_id=\''.Url::get('id').'\' AND payment.type=\'KARAOKE\'');
            $pay_with_room = DB::fetch('select pay_with_room,amount_pay_with_room,payment.currency_id from karaoke_reservation left join payment ON payment.bill_id =karaoke_reservation.id  where karaoke_reservation.id='.Url::get('id'));
            if($pay_with_room['pay_with_room']==1)
                array_push($payment_list,array('payment_type_name'=>Portal::language('pay_with_room'),
                                               'amount'=>($pay_with_room['amount_pay_with_room']+$amount_sing),
                                               'currency_id'=>$pay_with_room['currency_id']
                                               )
                           );
            //system::debug($payment_list);                                   
        }
        //System::debug($amount_sing);
		if(Url::get(md5('act')) == md5('print'))
		{
			//'.Portal::language('not_for_payment')
			$this->parse_layout('print_order',$row+$hotel+array(
				'preview'=>Url::get(md5('preview'))?Portal::language('preview_order'):'',  
				'order_id'=>$order_id,
				'payment_detail'=>$payment_detail,
				'pay_by_usd'=>$payment['USD'],
				'new_receptionist_id'=>$row['receptionist_id'],
				'new_server_id'=>$row['server_id'],
				'total_discount'=>System::display_number($total_discount),
				'amount'=>System::display_number(round($total_price + $total_discount+ $amount_sing,2)),
				'payment_kind'=>$payment_kind,
				'payment_type_name'=>$payment_name,
				'invoice_date'=>date('d/m/Y',time()),
				'invoice_hour'=>date('H:i',time()),
				'tables_name'=>$tables_name,
				'product_items'=>$product_items,
                'payment_list'=>$payment_list,
                'sing_room'=>$sing_room
			));
		}
        else if(Url::check(array('act'=>'print_kitchen')))
		{
			//System::Debug($product_items);
			$this->parse_layout('print_kitchen',$row+$hotel+array(
				'order_id'=>$order_id,
				'payment_detail'=>$payment_detail,
				'pay_by_usd'=>$payment['USD'],
				'new_receptionist_id'=>$row['receptionist_id'],
				'new_server_id'=>$row['server_id'],
				'total_discount'=>System::display_number($total_discount),
				'amount'=>System::display_number($total_price+$amount_sing),
				'payment_kind'=>$payment_kind,
				'payment_type_name'=>$payment_name,
				'invoice_date'=>date('d/m/Y',time()),
				'invoice_hour'=>date('H:i',time()),
				'tables_name'=>$tables_name,
				'product_items'=>$product_items,
                'payment_list'=>$payment_list,
                'sing_room'=>$sing_room
			));
		}else if(Url::check(array('act'=>'print_b_e_order'))){
			$this->parse_layout('print_b_e_order',$row+$hotel+array(
				'order_id'=>$order_id,
				'payment_detail'=>$payment_detail,
				'pay_by_usd'=>$payment['USD'],
				'new_receptionist_id'=>$row['receptionist_id'],
				'new_server_id'=>$row['server_id'],
				'total_discount'=>System::display_number($total_discount),
				'amount'=>System::display_number($total_price+$amount_sing),
				'payment_kind'=>$payment_kind,
				'payment_type_name'=>$payment_name,
				'invoice_date'=>date('d/m/Y',time()),
				'invoice_hour'=>date('H:i',time()),
				'tables_name'=>$tables_name,
				'product_items'=>$product_items,
                'payment_list'=>$payment_list,
                'sing_room'=>$sing_room
			));	
		}else{
			$this->parse_layout('checkio_detail',$row+array(
				'order_id'=>$order_id,
				'total_discount'=>System::display_number($total_discount),
				'amount'=>System::display_number($total_price+$amount_sing),
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
                'payment_list'=>$payment_list,
                'sing_room'=>$sing_room
			));
		}
	}
}
?>