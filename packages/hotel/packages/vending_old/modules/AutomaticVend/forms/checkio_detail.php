<?php
class DetailBarForm extends Form
{
	function DetailBarForm()
	{
		Form::Form("DetailBarForm");
        $this->add('id',new IDType(true,'object_not_exists','bar_reservation'));
		$this->link_js('packages/core/includes/js/jquery/disable.text.select.js');
	}
	function draw()
	{
		require_once 'packages/core/includes/utils/currency.php';
		$row = DB::fetch('select ve_reservation.*,
							customer.name as customer_name
						FROM ve_reservation
							left outer join customer ON customer.id = ve_reservation.customer_id
						WHERE ve_reservation.id = '.Url::get('id').'');
		$this->full_tax = $row['full_rate'];
		$this->full_charge =  $row['full_charge'];
		//$row['customer_name'] = $row['customer_id']?DB::fetch('select id,name from customer where id = '.$row['customer_id'].'','name'):'';
		$row['exchange_rate'] = $row['exchange_rate']?$row['exchange_rate']:DB::fetch('select id,exchange from currency where id=\'VND\'','exchange');
		$cond = '';
		$order_id = '';
		for($i=0;$i<6-strlen($row['id']);$i++)
		{
			$order_id .= '0';
		}
		$order_id .= $row['id'];

		//============================== bar_product ===============================
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
		$product_items = AutomaticVendDB::get_reservation_product($cond);
		$bar_name = AutomaticVendDB::get_bar_reservation(Url::get('id'));
		 //exit();
		$product_num = count($product_items);
		$total_discount = 0;
		$total_price = 0;
		$product_tmp = array();
		//System::Debug($row);
		$row['total_discount'] = 0;
		foreach($product_items as $key=>$value)
		{
			if(isset($product_tmp[$key]))
            {
				$product_tmp[$key]['quantity'] += $value['quantity'];
				$product_tmp[$key]['quantity_discount'] += $value['quantity_discount'];
			}
            else
            {
				$product_tmp[$key]['id'] = $key;			
				$product_tmp[$key]['product_id'] = $value['product_id'];
				$product_tmp[$key]['name'] = $value['name'];
				$product_tmp[$key]['quantity'] = $value['quantity'];
				$product_tmp[$key]['quantity_discount'] = $value['quantity_discount'];
                $product_tmp[$key]['promotion'] = $value['promotion'];
				$product_tmp[$key]['unit_name'] = $value['unit_name'];
				$product_tmp[$key]['price'] = $value['price'];
				$product_tmp[$key]['discount_rate'] = $value['discount_rate'];
				$product_tmp[$key]['discount_category'] = $value['discount_category'];
				$product_tmp[$key]['note'] = $value['note'];
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
            $product_items[$key]['product__promotion'] = $value['promotion'];
			$product_items[$key]['product__discount'] = $value['discount_rate']?$value['discount_rate'].'%':'';
			$product_items[$key]['product__discount_category'] = $value['discount_category']?$value['discount_category'].'%':'';
			$product_items[$key]['product__unit'] = $value['unit_name'];
			$product_items[$key]['product__price'] = System::display_number($value['price']);
			$product_items[$key]['product__note'] = $value['note'];
            $product_items[$key]['lt'] = $product_items[$key]['product__promotion'].' + '.$value['discount_rate'];
			$ttl = $value['price']*($value['quantity'] - $value['quantity_discount']);
			$discnt = ($ttl*$value['discount_category']/100) + (($ttl*(100-$value['discount_category'])/100)*($value['discount_rate'] + $value['promotion'])/100); 
			$total_discount += $discnt;
			if($ttl<0)
            {
				$ttl = 0;
			}
			$total_price += $ttl-$discnt;
                
			$product_items[$key]['product__total'] = System::display_number($ttl);
			$product_items[$key]['cancel_all'] = 0;
		}
        //System::Debug($product_items);
		$row['time_begin'] = date('d/m/Y H:i',$row['time_in']);
		$row['time_in_hour']=date('H',$row['time_in']);
		$row['time_in_munite']=date('i',$row['time_in']);
		$row['user_name'] = $bar_name['user_name'];
        
		$row['total_discount'] += $total_discount; // GG tren SP

		$row['guest_name'] = ($row['receiver_name']!='')?$row['receiver_name']:((($row['customer_name']!='')?$row['customer_name']:''));
		$total_before_tax = $row['total_before_tax'];
		if($row['full_rate'] ==1)
        {
			$row['total_amount'] = $total_price + $total_discount;
			$row['total_discount'] += $row['discount'] + $total_price*$row['discount_percent']*0.01;
			$row['service_amount'] = 0;	
			$row['tax_amount'] = 0;			
		}
        else if($row['full_charge'] ==1)
        {
			$row['total_amount'] = $total_price + $total_discount;
			$row['total_discount'] += $row['discount'] + $total_price*$row['discount_percent']*0.01;
			$row['service_amount'] = 0;	
			$row['tax_amount'] = round($row['total'] - (($row['total_amount'] - $row['total_discount']) + $row['service_amount']));
		}
        else
        {
			$row['total_amount'] = $total_price + $total_discount;
			$row['total_discount'] += $row['discount'] + $total_price*$row['discount_percent']*0.01;
			$row['service_amount'] = $total_before_tax * $row['bar_fee_rate'] * 0.01;	
			$row['tax_amount'] = ($total_before_tax + $row['service_amount']) * $row['tax_rate'] * 0.01;		
		}
		$row['total_amount'] =  System::display_number($row['total_amount']);
		$row['sum_total'] = System::display_number($row['total'] - $row['deposit']);
		$row['deposit'] = System::display_number($row['deposit']);
		$row['discount'] = System::display_number($row['discount']);
		$row['discount_percent'] = $row['discount_percent'];
        //system::debug($row);
        $hotel = array();
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
        if(Url::get('id'))
        {
            $payment_list = DB::fetch_all('SELECT payment.id, payment_type.name_'.Portal::language().' as payment_type_name, payment.amount, payment.currency_id FROM payment inner join payment_type on payment_type.def_code=payment.payment_type_id WHERE payment.bill_id=\''.Url::get('id').'\' AND payment.type=\'VEND\'');
            $pay_with_room = DB::fetch('select pay_with_room,amount_pay_with_room,payment.currency_id from ve_reservation left join payment ON payment.bill_id =ve_reservation.id  where ve_reservation.id='.Url::get('id'));
            if($pay_with_room['pay_with_room']==1)
                array_push($payment_list,array('payment_type_name'=>Portal::language('pay_with_room'),
                                               'amount'=>$pay_with_room['amount_pay_with_room'],
                                               'currency_id'=>$pay_with_room['currency_id']
                                               )
                           );
            //system::debug($payment_list);                                   
        }
		$payment['USD'] = round($row['total'] - $total_currency,2);		
		$row['exchange'] = System::display_number($row['exchange_rate']);
		$this->parse_layout('print_order',$row+$hotel+array(
			'preview'=>Url::get(md5('preview'))?Portal::language('preview_order'):'',  
			'order_id'=>$order_id,
			'payment_detail'=>$payment_detail,
			'pay_by_usd'=>$payment['USD'],
			'total_discount'=>System::display_number($total_discount),
			'amount'=>System::display_number(round($total_price + $total_discount,2)),
			'invoice_date'=>date('d/m/Y',time()),
			'invoice_hour'=>date('H:i',time()),
			'product_items'=>$product_items,
            'payment_list'=>$payment_list
		));
	}
}
?>