<?php
class DetailBarForm extends Form
{
	function DetailBarForm()
	{
		Form::Form("DetailBarForm");
		DB::query('select status from bar_reservation where id=\''.Url::get('id').'\' and status in (\'CHECKIN\',\'CHECKOUT\',\'BOOKED\',\'RESERVATION\')');
		if(!DB::fetch())
		{
			//Url::redirect_current(array('id','cmd'=>'detail','print'=>1));
		}
		else
		{
			$this->add('id',new IDType(true,'object_not_exists','bar_reservation'));
		}
		$this->link_js('packages/core/includes/js/jquery/disable.text.select.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw()
	{
        $user_data = Session::get('user_data');
        $user_data['full_name'];
		//$this->full_tax = Session::get('full_rate');
		//$this->full_charge = Session::get('full_charge');
		require_once 'packages/core/includes/utils/currency.php';
		$row = DB::select('bar_reservation',Url::get('id'));
        $this->full_tax = $row['full_rate'];$this->full_charge = $row['full_charge'];
		//ten doan:
        $checkout_user  = array();
        if($row['checked_out_user_id']!='')
        {
            $checkout_user = $row['checked_out_user_id']?DB::fetch('select id,name_1 as name from party where user_id = \''.$row['checked_out_user_id'].'\''):'';
        }
        else
        {
            $checkout_user['name']='';
        }
        //System::debug($checkout_user);
        $row['customer_name'] = $row['reservation_room_id']?DB::fetch('
                                                                    SELECT customer.name AS cname,traveller.first_name || \' \' || traveller.last_name AS fullname
                                                                    FROM bar_reservation
                                                                        left JOIN reservation_traveller ON bar_reservation.reservation_traveller_id = reservation_traveller.id
                                                                        left JOIN reservation_room ON bar_reservation.reservation_room_id = reservation_room.id
                                                                        left JOIN reservation ON reservation_room.reservation_id = reservation.id
                                                                        left JOIN customer ON reservation.customer_id = customer.id
                                                                        left JOIN traveller ON reservation_traveller.traveller_id = traveller.id 
                                                                    WHERE bar_reservation.id='.Url::get('id').'
                                                                    '):'';
        
        
        $row['exchange_rate'] = $row['exchange_rate']?$row['exchange_rate']:DB::fetch('select id,exchange from currency where id=\'VND\'','exchange');
		//System::debug($row);
        $cond = '';
		$order_id = '';
		for($i=0;$i<6-strlen($row['id']);$i++)
		{
			$order_id .= '0';
		}
		$order_id .= $row['id'];
		//============================== bar_table ===============================
        $customor_items = TouchBarRestaurantDB::get_rr_id(Url::get('id'));
        //System::debug($customor_items);
        $bar_items = TouchBarRestaurantDB::get_bar_reservation(Url::get('id'));
        if (User::is_admin())
        {
            //System::debug($bar_items);
        }
        $table_items = TouchBarRestaurantDB::get_bar_table(Url::get('id'));
		$tables_num=count($table_items);
		//System::debug($table_items);
		$tables_name = '';
		$row['num_people'] = 0;
		foreach($table_items as $tkey1=>$tbl)
		{
			$row['num_people'] += $tbl['num_people'];
			$tables_name .= ','.($tbl['name']);
            //$tables_name .= ','.($tbl['name'].' (Order: '.$tbl['order_person']).')<br>';
            
		}
		$tables_name = substr($tables_name,1);
		//============================== bar_product ===============================
		$nh = DB::fetch('select structure_id from product_category where code=\'NH\'','structure_id');
		$cond_nh = ' AND product_category.structure_id > '.$nh.' and product_category.structure_id <'.IDStructure::next($nh).'';
		if(Url::get('act') == 'print_kitchen' and Url::get('type') == 'food')
        {
			$food = DB::fetch('select structure_id from product_category where code=\'DA\' '.$cond_nh.'','structure_id');
			$cond = ' AND product_category.structure_id > '.$food.' and product_category.structure_id <'.IDStructure::next($food).'';	
		}
        else if(Url::get('act') == 'print_kitchen' and Url::get('type') == 'drink')
        {
			$drink = DB::fetch('select structure_id from product_category where code=\'DU\' '.$cond_nh.'','structure_id');
			$cond = ' AND product_category.structure_id > '.$drink.' and product_category.structure_id <'.IDStructure::next($drink).'';	
		}
        //System::debug($package);
		//$cond = '';
        //System::debug($cond);
		$product_items = TouchBarRestaurantDB::get_reservation_product($cond);
        //System::debug($product_items);
		$product_items += TouchBarRestaurantDB::get_reservation_set_product($cond);
        //System::debug($product_items);
        //echo "********************";
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
                        $product_tmp[$key]['bar_fee_rate'] = $value['bar_fee_rate'];
						$product_tmp[$key]['discount_rate'] = $value['discount_rate'];
						$product_tmp[$key]['discount_category'] = $value['discount_category'];
						$product_tmp[$key]['printed'] = $value['quantity'] - $value['printed'];
						$product_tmp[$key]['remain'] = $value['remain'];
						$product_tmp[$key]['note'] = $value['note'];
                        $product_tmp[$key]['set_menu_id'] = $value['bar_set_menu_id'];
                        $product_tmp[$key]['chair_number'] = $value['chair_number'];// trung lay vao mang chair_number
                        $product_tmp[$key]['set_menu_name'] = isset($value['bar_set_menu_name'])?$value['bar_set_menu_name']:"";
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
					$product_tmp[$key]['discount_rate'] = $value['discount_rate'];
					$product_tmp[$key]['discount_category'] = $value['discount_category'];
					$product_tmp[$key]['printed'] = $value['quantity'] - $value['printed'];
					$product_tmp[$key]['remain'] = $value['remain'];
					$product_tmp[$key]['note'] = $value['note'];
                    $product_tmp[$key]['set_menu_id'] = $value['bar_set_menu_id'];
                    $product_tmp[$key]['set_menu_name'] = isset($value['bar_set_menu_name'])?$value['bar_set_menu_name']:"";
					$product_tmp[$key]['chair_number'] = isset($value['chair_number'])?$value['chair_number']:'';// trung lay vao mang chair_number
                    if(Url::get('act')=='print_kitchen')
                    {
						DB::query('Update bar_reservation_product set bar_reservation_product.printed = '.($value['printed'] + $product_tmp[$value['product_id']]['printed']).' where id='.$key.'');	
					}
				}
			}
		}
		$product_items = $product_tmp;
        
          //---------------------kieu_edit chinh sua neu so luong bang 0 thi khong hien thi tren hoa don
        foreach($product_items as $k=>$v){
            if($v['quantity']==0){
                unset($product_items[$k]);   
            }
        }
        //---------------------kieu_edit chinh sua neu so luong bang 0 thi khong hien thi tren hoa don
        
        
        $sql = "SELECT * FROM bar_set_menu WHERE portal_id='".PORTAL_ID."'";
        $bar_set_menu = DB::fetch_all($sql);
        
		foreach($product_items as $key=>$value)
		{
		  $product_items[$key]['bar_set_menu_id'] = "";
		    foreach($bar_set_menu as $k=>$v)
            {
                if($value['product_id']==$v['code'])
                {
                    $product_items[$key]['bar_set_menu_id'] = $v['id'];
                }
            }
			$product_items[$key]['product__id'] = $value['product_id'];
			$product_items[$key]['product__name'] = trim($value['name']);
			$product_items[$key]['product__quantity'] = $value['quantity'];
			$product_items[$key]['product__remain_quantity'] = $value['quantity']-$value['quantity_discount'];
			$product_items[$key]['product__quantity_discount'] = $value['quantity_discount'];
			$product_items[$key]['product__quantity_cancel'] = $value['quantity_cancel'];
			$product_items[$key]['product__discount'] = $value['discount_rate']?$value['discount_rate'].'%':'';
			$product_items[$key]['product__discount_category'] = $value['discount_category']?$value['discount_category'].'%':'';
			$product_items[$key]['product__unit'] = $value['unit_name'];
			$product_items[$key]['product__printed'] = $value['printed'];
			//$product_items[$key]['product__price'] = System::display_number(round(($value['price']/(1+$value['tax_rate']/100))/(1+$value['bar_fee_rate']/100),2));//System::display_number($value['price']);
			$product_items[$key]['product__remain'] = $value['remain'];
			$product_items[$key]['product__note'] = $value['note'];
            $product_items[$key]['set_menu_id'] = $value['set_menu_id'];
            $product_items[$key]['set_menu_name'] = $value['set_menu_name'];
            $product_items[$key]['chair_number'] = $value['chair_number'];// lay cai truong nay de in
			$discount_category = $value['discount_category']?$value['discount_category']:0;
            $discount_rate = $value['discount_rate']?$value['discount_rate']:0;

            if($row['discount_after_tax']==1)
            {
				$product_items[$key]['product__price'] = System::display_number(round($value['price']));
                $ttl = $value['price']*($value['quantity'] - $value['quantity_discount'] );//- $value['quantity_cancel'] - $value['remain']
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
				if($this->full_tax==1)
                {
					$value['price'] = round(($value['price']/(1+$value['tax_rate']/100))/(1+$value['bar_fee_rate']/100),2);	
				}
                else if($this->full_charge==1)
                {
					$value['price'] = round($value['price']/(1+$value['bar_fee_rate']/100),2);
				}
                $product_items[$key]['product__price'] = System::display_number(round($value['price'],0));
                $ttl = $value['price']*($value['quantity'] - $value['quantity_discount']);// - $value['quantity_cancel'] - $value['remain']
				
				$discnt = ($ttl*$discount_category/100);
                $discnt += ( $ttl - ($ttl*$discount_category/100) ) * $discount_rate/100;
				$total_discount += $discnt;
				if($ttl<0)
                {
					$ttl = 0;
				}
				$total_price += $ttl-$discnt;	
			}
			$product_items[$key]['product__total'] = System::display_number(round($ttl-$discnt,0));
			if($value['quantity'] == 0)//($value['quantity_cancel'] + $value['remain'])) //KimTan: vi trong database dang luu remain la so luong tra lai, con soluong duoc tu dong tru di sau moi lan tra lai.
			{
				$product_items[$key]['cancel_all'] = 1;
			}
            else
            {
				$product_items[$key]['cancel_all'] = 0;	
			}
		}
        $bar_set_menu_id_temp = 0;
        $arr_set_menu = array();
        $total = 0;
         
        foreach($product_items as $key=>$value){
            if(!empty($value['set_menu_id'])){
                if($value['set_menu_id']!=$bar_set_menu_id_temp){
                    $bar_set_menu_id_temp = $value['set_menu_id'];
                    $arr_set_menu[$value['set_menu_id']]['id'] = $bar_set_menu_id_temp;
                    $arr_set_menu[$value['set_menu_id']]['name'] = $value['set_menu_name'];
                    foreach($product_items as $k=>$v)
                    {
                        if($v['bar_set_menu_id']==$value['set_menu_id'])
                        {
                            $arr_set_menu[$value['set_menu_id']]['product__remain_quantity'] = $v['product__remain_quantity'];
                            $arr_set_menu[$value['set_menu_id']]['product__price'] = $v['product__price'];
                            $arr_set_menu[$value['set_menu_id']]['product__discount'] = $v['product__discount'];
                            $arr_set_menu[$value['set_menu_id']]['product__total'] = $v['product__total'];
                            unset($product_items[$k]);
                        }
                    }
                    
                    $arr_set_menu[$value['set_menu_id']]['items'][$key]=$value;
                    if(!isset($arr_set_menu[$bar_set_menu_id_temp]['total'])){
                        $arr_set_menu[$bar_set_menu_id_temp]['total'] = 0;
                    }
                    $arr_set_menu[$bar_set_menu_id_temp]['total'] += System::calculate_number($value['product__total']);                  
                }
                else{
                    $arr_set_menu[$bar_set_menu_id_temp]['items'][$key]=$value;
                    $arr_set_menu[$bar_set_menu_id_temp]['total'] += System::calculate_number($value['product__total']); 
                }
                unset($product_items[$key]);
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
            /** Daund viết lại Vì là sau thuế phí nên tất cả đều = 0 */
            $row['bar_fee'] = 0;
			$row['total_before_tax'] = (($total_price)+($total_price)*$row['bar_fee']/100);
			$row['tax'] = 0;
			$row['order_discount'] = $row['total_before_tax']*$row['discount_percent']/100;
            $before_tax = $total_price - $row['order_discount'];
            if($this->full_charge==1)
            {
				$row['tax'] = round(($before_tax*$row['tax_rate']/100),2);
			}
            $row['sum_total'] = $before_tax + $row['bar_fee'] + $row['tax']-$row['discount'];
		}
        else
        {// Giam gia trc thue
			
            $row['order_discount'] =  round(($total_price) * $row['discount_percent']/100,0);
			$before_tax = $total_price - $row['order_discount'] - $row['discount'];
            //echo $before_tax;
			$row['bar_fee'] = (($before_tax)*$row['bar_fee_rate']/100);
			$row['total_before_tax'] = (($before_tax)+($before_tax)*$row['bar_fee_rate']/100);
			$row['tax'] =($row['total_before_tax']*$row['tax_rate']/100);
            $row['sum_total'] = $before_tax + $row['bar_fee'] + $row['tax'];
		}
		
        //echo $row['sum_total'];
        //giap.ln them truong hop package nha hang hien thi tren hoa don
        $package_amount = 0;
        if($row['package_id']!='')
        {
            $total_pacakge = DB::fetch("SELECT id,price as amount
                                            FROM package_sale_detail 
                                            WHERE id=".$row['package_id']);
            if(!empty($total_pacakge))
            {
                $row['package_amount'] =  $total_pacakge['amount'];
                $package_amount = $total_pacakge['amount'];
            }
            if($row['sum_total']<=$row['package_amount'])
                $row['sum_total'] = 0;
            else
                $row['sum_total'] = $row['sum_total']-$row['package_amount'] ;  
                
            
        }
        //end giap.ln 
		$row['prepaid'] = $row['prepaid'];
		$row['remain_prepaid'] = $row['sum_total'] - $row['order_discount'] - $row['prepaid'];
        
		$row['bar_fee'] = System::display_number(round($row['sum_total']+$row['discount']+$row['order_discount'],0)-round($row['tax'])-round($total_price,0) + $package_amount);
        $row['discount'] = System::display_number($row['discount']);
        $row['tax'] = System::display_number(round($row['tax']));
		$row['prepaid'] = System::display_number($row['prepaid']);
		$row['remain_prepaid_usd'] = System::display_number(round($row['sum_total']/$row['exchange_rate'],2));
		$row['remain_prepaid'] = System::display_number($row['remain_prepaid']);
		$row['total_before_tax'] = System::display_number($row['total_before_tax']);
		$row['sum_total_usd'] = System::display_number(round(($row['sum_total'] - $row['deposit'])/$row['exchange_rate'],2));
		$row['sum_total'] = System::display_number(round($row['sum_total'] - $row['deposit'],0));
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
		if($row['discount_after_tax']==1)
        {
            $row['bar_fee'] = 0;
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
            $package = DB::fetch('
                    SELECT 
                        package_sale.name as package_name,
                        room.name as room_package_name
                    FROM 
                        bar_reservation
                        left join package_sale on bar_reservation.package_id=package_sale.id
                        left join reservation_room ON reservation_room.package_sale_id=package_sale.id
                        left join room on reservation_room.room_id=room.id
                    WHERE 
                        bar_reservation.id =\''.Url::get('id').'\'');
			$hotel = array(
				'room_name'=>$reservation['room_name'],
				'reservation_name'=>$reservation['name'],
                'package_name'=>$package['package_name'],
                'room_package_name'=>$package['room_package_name']
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
        $paper['paper'] = 'A5';
        if(Url::get('bar_id'))
        {
            $paper = DB::fetch("SELECT paper FROM bar WHERE id=".Url::get('bar_id'));
        }
        $_REQUEST['paper'] = $paper['paper'];
        $row['check_name_invoice'] = 1; // chua dc thanh toan
        
        $payment_list = array();
        if(Url::get('id'))
        {
            $payment_list = DB::fetch_all('SELECT payment.id,payment.type_dps, payment_type.name_'.Portal::language().' as payment_type_name, payment.amount, payment.currency_id FROM payment inner join payment_type on payment_type.def_code=payment.payment_type_id WHERE payment.bill_id=\''.Url::get('id').'\' AND payment.type=\'BAR\'');
            foreach($payment_list as $k=>$v)
            {
                if($v['type_dps']!='')
                $payment_list[$k]['payment_type_name'] = $v['payment_type_name'].' ('.Portal::language('deposit').')';
                else
                $payment_list[$k]['payment_type_name'] = $v['payment_type_name'];
                
                $row['check_name_invoice'] = 2; // da dc thanh toan
            }
            $pay_with_room = DB::fetch('select pay_with_room,amount_pay_with_room,payment.currency_id from bar_reservation left join payment ON payment.bill_id =bar_reservation.id  where bar_reservation.id='.Url::get('id'));
            if($pay_with_room['pay_with_room']==1){
                array_push($payment_list,array('payment_type_name'=>Portal::language('pay_with_room'),
                                               'amount'=>$pay_with_room['amount_pay_with_room'],
                                               'currency_id'=> 'VND'//$pay_with_room['currency_id']
                                               )
                           );
                $row['check_name_invoice'] = 1; // chua dc thanh toan
            }
            if($row['mice_reservation_id']!='' and $row['mice_reservation_id']!=0){
                $row['check_name_invoice'] = 1; // chua dc thanh toan
            }
            //system::debug($payment_list);                                   
        }
        //System::debug($row['discount']);                
		if(Url::get(md5('act')) == md5('print'))
		{/** in hoa don va checkout **/
		  if(Url::get('print_automatic_bill')==1)
          {/** in hoa don tu dong **/
            $this->map = $row+$hotel+array(
    				'preview'=>Url::get(md5('preview'))?Portal::language('bill_invoice'):'',
    				'order_id'=>$order_id,
    				'payment_detail'=>$payment_detail,
    				'pay_by_usd'=>$payment['USD'],
    				'new_receptionist_id'=>$row['receptionist_id'],
    				'new_server_id'=>$row['server_id'],
    				'total_discount'=>System::display_number($total_discount),
                    'discount_percent_dn'=>$row['discount'], /** daund them giam gia so tien */                     
    				'amount'=>System::display_number(round($total_price + $total_discount,2)),
    				'payment_kind'=>$payment_kind,
                    'bar_name'=>$bar_items['bar_name'],
                    'table_checkout'=>date('H:i d/m/Y',$bar_items['departure_time']),
    				'payment_type_name'=>$payment_name,
    				'invoice_date'=>date('d/m/Y',time()),
    				'invoice_hour'=>date('H:i',time()),
    				'tables_name'=>$tables_name,
    				'product_items'=>$product_items,
                    'set_product_items'=>$arr_set_menu,
                    'checkout_user'=>$checkout_user['name'],
                    'print_user'=>$user_data['full_name'],
                    'payment_list'=>$payment_list
    			);
            require_once 'packages/core/includes/utils/printer.php';
            if(Url::get('bar_id'))
            {
                $bill_auto = DB::fetch("SELECT bar_area.print_automatic_bill FROM bar_area WHERE bar_id=".Url::get('bar_id')." AND id=".Url::get('bar_area_id'));
            }
            if($bill_auto['print_automatic_bill']!='')
            {
                $printer = new Printer($bill_auto['print_automatic_bill'],array());
                $printer->write_bill($this->map);
            }
            echo "<script>";
            echo "window.close();";
            echo "</script>";
          }
          else
          {
              $layout = 'view_order_a5';
              if($_REQUEST['paper']=='K8')
              {
                $layout = 'view_order_k8';
              }
              elseif($_REQUEST['paper']=='A4')
              {
                $layout = 'view_order_a4';
              }
    			$this->parse_layout($layout,$row+$hotel+array(
    				'preview'=>Url::get(md5('preview'))?Portal::language('bill_invoice'):'',
    				'order_id'=>$order_id,
    				'payment_detail'=>$payment_detail,
    				'pay_by_usd'=>$payment['USD'],
    				'new_receptionist_id'=>$row['receptionist_id'],
    				'new_server_id'=>$row['server_id'],
    				'total_discount'=>System::display_number($total_discount),
                    'discount_percent_dn'=>$row['discount'], /** daund them giam gia so tien */                     
    				'amount'=>System::display_number(round($total_price + $total_discount,2)),
    				'payment_kind'=>$payment_kind,
                    'bar_name'=>$bar_items['bar_name'],
                    'table_checkout'=>date('H:i d/m/Y',$bar_items['departure_time']),
    				'payment_type_name'=>$payment_name,
    				'invoice_date'=>date('d/m/Y',time()),
    				'invoice_hour'=>date('H:i',time()),
    				'tables_name'=>$tables_name,
    				'product_items'=>$product_items,
                    'set_product_items'=>$arr_set_menu,
                    'checkout_user'=>$checkout_user['name'],
                    'print_user'=>$user_data['full_name'],
                    'payment_list'=>$payment_list
    			));
            }
           // System::Debug($arr_set_menu);
		}
        else if(Url::check(array('act'=>'print_kitchen')))
		{
			//System::Debug($arr_set_menu);
			$this->parse_layout('print_kitchen',$row+$hotel+array(
				'order_id'=>$order_id,
				'payment_detail'=>$payment_detail,
				'pay_by_usd'=>$payment['USD'],
				'new_receptionist_id'=>$row['receptionist_id'],
				'new_server_id'=>$row['server_id'],
				'total_discount'=>System::display_number(round($total_discount,0)),
                'discount_percent_dn'=>$row['discount'], /** daund them giam gia so tien */                
				'amount'=>System::display_number(round($total_price + $total_discount,2)),
				'payment_kind'=>$payment_kind,
                'bar_name'=>$bar_items['bar_name'],
                'customer_name'=>$customor_items['customer_name'],
				'payment_type_name'=>$payment_name,
				'invoice_date'=>date('d/m/Y',time()),
				'invoice_hour'=>date('H:i',time()),
				'tables_name'=>$tables_name,
				'product_items'=>$product_items,
                'set_product_items'=>$arr_set_menu,
                'checkout_user'=>$checkout_user['name'],
                'print_user'=>$user_data['full_name'],
                'payment_list'=>$payment_list
			));
		}
		else if(Url::check(array('act'=>'print_kitchen')))
		{
			//System::Debug($arr_set_menu);
			$this->parse_layout('print_kitchen',$row+$hotel+array(
				'order_id'=>$order_id,
				'payment_detail'=>$payment_detail,
				'pay_by_usd'=>$payment['USD'],
				'new_receptionist_id'=>$row['receptionist_id'],
				'new_server_id'=>$row['server_id'],
				'total_discount'=>System::display_number(round($total_discount,0)),
                'discount_percent_dn'=>$row['discount'], /** daund them giam gia so tien */                 
				'amount'=>System::display_number(round($total_price + $total_discount,2)),
				'payment_kind'=>$payment_kind,
                'bar_name'=>$bar_items['bar_name'],
                'customer_name'=>$customor_items['customer_name'],
				'payment_type_name'=>$payment_name,
				'invoice_date'=>date('d/m/Y',time()),
				'invoice_hour'=>date('H:i',time()),
				'tables_name'=>$tables_name,
				'product_items'=>$product_items,
                'set_product_items'=>$arr_set_menu,
                'checkout_user'=>$checkout_user['name'],
                'print_user'=>$user_data['full_name'],
                'payment_list'=>$payment_list
			));
			}
			else if(Url::get(md5('act')) == md5('print_bill'))
            {/** xem phieu tam tinh **/
           // System::Debug($arr_set_menu);
                if(Url::get('print_automatic_bill')==1)
                {/** in hoa don tu dong **/
                    $this->map = $row+$hotel+array(
                    				'order_id'=>$order_id,
                    				'payment_detail'=>$payment_detail,
                    				'pay_by_usd'=>$payment['USD'],
                    				'new_receptionist_id'=>$row['receptionist_id'],
                    				'new_server_id'=>$row['server_id'],
                                    'total_discount'=>System::display_number(round($total_discount,0)),
                                    'discount_percent_dn'=>$row['discount'], /** daund them giam gia so tien */                                     
                    				'amount'=>System::display_number(round($total_price + $total_discount,2)),
                    				'payment_kind'=>$payment_kind,
                    				'payment_type_name'=>$payment_name,
                    				'invoice_date'=>date('d/m/Y',time()),
                    				'invoice_hour'=>date('H:i',time()),
                                    'bar_name'=>$bar_items['bar_name'],
                                    'table_checkout'=>date('H:i d/m/Y',$bar_items['departure_time']),
                                    'tables_name'=>$tables_name,
                    				'product_items'=>$product_items,
                                    'set_product_items'=>$arr_set_menu,
                    				'preview'=>Url::get(md5('preview'))?Portal::language('bill_invoice'):'',  
                                    'checkout_user'=>$checkout_user['name'],
                                    'print_user'=>$user_data['full_name'],
                                    'payment_list'=>$payment_list
                                   // 'chair_number'
                    		      	);
                    require_once 'packages/core/includes/utils/printer.php';
                    
                    if(Url::get('bar_id'))
                    {
                        $bill_auto = DB::fetch("SELECT bar_area.print_automatic_bill FROM bar_area WHERE bar_id=".Url::get('bar_id')." AND id=".Url::get('bar_area_id'));
                    }
                    if($bill_auto['print_automatic_bill']!='')
                    {
                        $printer = new Printer($bill_auto['print_automatic_bill'],array());
                        $printer->write_bill($this->map);
                    }
                    echo "<script>";
                    echo "window.close();";
                    echo "</script>";
                }
                else
                {
                    $layout = 'view_order_a5';
                      if($_REQUEST['paper']=='K8')
                      {
                        $layout = 'view_order_k8';
                      }
                      elseif($_REQUEST['paper']=='A4')
                      {
                        $layout = 'view_order_a4';
                      }
    		      $this->parse_layout($layout,$row+$hotel+array(
    				'order_id'=>$order_id,
    				'payment_detail'=>$payment_detail,
    				'pay_by_usd'=>$payment['USD'],
    				'new_receptionist_id'=>$row['receptionist_id'],
    				'new_server_id'=>$row['server_id'],
                    'total_discount'=>System::display_number(round($total_discount,0)),
                    'discount_percent_dn'=>$row['discount'], /** daund them giam gia so tien */                     
    				'amount'=>System::display_number(round($total_price + $total_discount,2)),
    				'payment_kind'=>$payment_kind,
    				'payment_type_name'=>$payment_name,
    				'invoice_date'=>date('d/m/Y',time()),
    				'invoice_hour'=>date('H:i',time()),
                    'bar_name'=>$bar_items['bar_name'],
                    'table_checkout'=>date('H:i d/m/Y',$bar_items['departure_time']),
                    'tables_name'=>$tables_name,
    				'product_items'=>$product_items,
                    'set_product_items'=>$arr_set_menu,
    				'preview'=>Url::get(md5('preview'))?Portal::language('bill_invoice'):'',  
                    'checkout_user'=>$checkout_user['name'],
                    'print_user'=>$user_data['full_name'],
                    'payment_list'=>$payment_list
    		      	));
                }
               // System::debug($product_items);
            
		}
        else
        {
			$this->parse_layout('checkio_detail',$hotel+$row+array(
				'order_id'=>$order_id,
				'total_discount'=>System::display_number(round($total_discount,0)),
                'discount_percent_dn'=>$row['discount'], /** daund them giam gia so tien */               
				'amount'=>System::display_number(round($total_price + $total_discount,2)),//System::display_number(round($total_price,0)),
				'payment_kind'=>$payment_kind,
				'pkind'=>$pkind,
				'room_name'=>isset($room_name)?$room_name:'',
				'payment_type_name'=>$payment_name,
                'customer_name'=>$customor_items['customer_name'],
                'bar_name'=>$bar_items['bar_name'],
				'reservation_name'=>$reservation['name'],
				'date'=>date('d/m/Y',time()),
				'table_items'=>$table_items,
                'table_checkout'=>date('d/m/Y',$bar_items['departure_time']),
				'tables_num'=>$tables_num,
				'product_items'=>$product_items,
                'set_product_items'=>$arr_set_menu,
				'product_num'=>$product_num,
                'checkout_user'=>$checkout_user['name'],
                'print_user'=>$user_data['full_name'],
                'payment_list'=>$payment_list
			));
		}

    }
    
}
?>
