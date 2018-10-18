<?php
class RestaurantOrderRevenueReportForm extends Form
{
	function RestaurantOrderRevenueReportForm()
	{
		Form::Form('RestaurantOrderRevenueReportForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
        
	}
	function draw()
	{
        $this->map = array();
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):32;
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        $date = getdate();
            if($date['mday']<10){
                $date['mday'] = "0".$date['mday'];
            }
            if($date['mon']<10){
                $date['mon'] = "0".$date['mon'];
            }
            $to_day = $date['mday']."/".$date['mon']."/".$date['year'];
            //exit();
            $from_date = Url::get('from_date')?Url::get('from_date'):$to_day;
            $to_date = Url::get('to_date')?Url::get('to_date'):$to_day;
            $this->map['from_date'] = $from_date;
            $_REQUEST['from_date'] = $from_date;
            $this->map['to_date'] = $to_date; 
            $_REQUEST['to_date'] = $to_date;
		if(URL::get('do_search'))
		{
			$bars = DB::fetch_all('select * from bar');
			$bar_ids = '';
            $bar_name = '';
			foreach($bars as $k => $bar){
				if(Url::get('bar_id_'.$k)){
					$bar_ids .= ($bar_ids=='')?$k:(','.$k);	
                    $bar_name .= ($bar_name=='')?$bar['name']:(', '.$bar['name']);
				}
			}
            $_REQUEST['bar_name'] = $bar_name;
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
           
			$from_code = 0;
			$to_code = 0;$cond_code ='';
			$this->map['check']=false;
			if(Url::get('from_code') && Url::get('to_code')){
				if(strpos(Url::get('from_code'),'-') > -1){
					$from_code = intval(substr(Url::get('from_code'),(strpos(Url::get('from_code'),'-')+1),strlen(Url::get('from_code'))));
				}else{
					$from_code = Url::get('from_code');
				}
				if(strpos(Url::get('to_code'),'-') > -1){
					$to_code = intval(substr(Url::get('to_code'),(strpos(Url::get('to_code'),'-')+1),strlen(Url::get('to_code'))));
				}else{
					$to_code = Url::get('to_code');
				}
			}
			if($from_code>0 && $to_code>0 && $to_code>=$from_code){
				// TH tim kiem theo hoa don VAT.
				$cond_code = $this->get_vat_invoice_cond($from_code,$to_code); // Lay dk cua HD VAT de select ra folio
				//echo $cond_code;
                $this->map['check'] = true;
			}
            
			$this->line_per_page = URL::get('line_per_page',15);
			$shift_lists = DB::fetch_all('select bar_shift.* from bar_shift order by id ASC');
            $cond = '';
            //start: KID them de tim theo ma hoa don
            if(Url::get('from_bill') || Url::get('to_bill'))
            {
				$from_bill = Url::get('from_bill');
				$to_bill = Url::get('to_bill');
            }
            
			//end: KID them de tim theo ma hoa don
			if($cond_code!='')
            { // Neu co dieu kien tim theo code roi thi ko lay dieu kien thoi gian nua
				$cond .= ' 1 = 1 AND ('.$cond_code.')';
			}
            else
            {
				//echo $shift_to.'--'.$shift_from;
    			$cond = $this->cond = ' 1 >0 '
    				.(URL::get('bar_id')?' and bar_reservation.bar_id = '.URL::get('bar_id').'':'') 
    				.(URL::get('user_id')?' and bar_reservation.checked_out_user_id = \''.URL::get('user_id').'\'':'') 		    				
                    //.((URL::get('revenue')=='min')?' and bar_reservation.total<200000':((URL::get('revenue')=='max')?' and bar_reservation.total>=200000':'')).' and bar_reservation.status=\'CHECKOUT\' '
                    .((URL::get('revenue')=='min')?' and bar_reservation.total<200000':((URL::get('revenue')=='max')?' and bar_reservation.total>=200000':'')).''
    				;
				//.' and bar_reservation.time_out>='.($time_from+$shift_from).' and bar_reservation.time_out<'.($time_to+$shift_to).''
    				if(Url::get('search_time')){
					$time_from = Date_Time::to_time($from_date);
					$time_to = (Date_Time::to_time($to_date));//+24*3600
					
					if(Url::get('start_time'))
					{
						$shift_from = $this->calc_time(Url::get('start_time'));
						$this->map['start_shift_time'] = Url::get('start_time');
					}
					else
					{
						$shift_from = $this->calc_time('00:00');
						$this->map['start_shift_time'] = '00:00';
					}
					if(Url::get('end_time'))
					{
						$shift_to = $this->calc_time(Url::get('end_time'));
						$this->map['end_shift_time'] = Url::get('end_time'); 
					}
					else
					{
						$shift_to = $this->calc_time('23:59');
						$this->map['end_shift_time'] = '23:59'; 
					}
					$cond .=' and bar_reservation.time_out>='.($time_from+$shift_from).' and bar_reservation.time_out<='.($time_to+$shift_to);
				}  
				if(Url::get('code')){
    				$cond .= ' AND upper(FN_CONVERT_TO_VN(customer.name)) LIKE \'%'.strtoupper(Url::sget('code')).'%\'';
    			}
                //start: KID them de tim theo ma hoa don
				if(Url::get('search_invoice')){
					if(Url::get('from_bill')!='' && Url::get('to_bill')!='')
					{
						if($from_bill>0 && $to_bill>0 && $to_bill>=$from_bill)
						{
							 $cond .= '  AND SUBSTR(bar_reservation.code, 6 ) >='.$from_bill.' AND SUBSTR(bar_reservation.code, 6 )<='.$to_bill.'';
						}
					}else if(Url::get('from_bill') and !Url::get('to_bill')){
                    $cond .= ' AND SUBSTR(bar_reservation.code, 6 ) >='.$from_bill.'';
					}else if(!Url::get('from_bill') and Url::get('to_bill')){
						$cond .= ' AND SUBSTR(bar_reservation.code, 6 )<='.$to_bill.'';
					}
				}
                //end: KID them de tim theo ma hoa don
			}
			if(User::can_admin(false,ANY_CATEGORY)){
			     if(Url::get('hotel_id') != 'ALL'){
				$cond .= Url::get('hotel_id')?' and bar_reservation.portal_id = \''.Url::get('hotel_id').'\'':'';}
			}else{
				$cond .= ' and bar_reservation.portal_id = \''.PORTAL_ID.'\'';	
			}
          
			$sql = 'SELECT
						bar_reservation.id
                        ,bar_reservation.code
                        ,bar_reservation.departure_time
						,bar_reservation.total as total_2--tam thoi ko dung de la 2
                        ,bar_reservation.payment_result
						,bar_reservation.checked_out_user_id as receptionist_id
						,bar_reservation.exchange_rate
						,bar_reservation.receiver_name
						,bar_reservation.bar_fee_rate
						,bar_reservation.tax_rate
						,bar_reservation.total_before_tax
                        ,bar_reservation.discount_percent
                        ,bar_reservation.discount
                        ,bar_reservation.full_rate
                        ,bar_reservation.full_charge
                        ,bar_reservation.note
						,bar_reservation.time_out
						,bar_reservation.pay_with_room                        
						,customer.name as customer_name
						,bar_reservation.bar_id
						,bar_table.name as table_name
						,CONCAT(traveller.first_name,CONCAT(\' \',traveller.first_name)) as traveller_name
						,room.name as room_name
						,0 as deposit
						,0 as paid
						,0 as foc
                        ,0 as product_discount
						,reservation_room.reservation_id as recode
                        ,reservation_room.id as reservation_room_id
						,ROW_NUMBER() OVER(ORDER BY bar_reservation.id ) as rownumber
                        ,amount_pay_with_room
                        ,reservation_room.reservation_id
					FROM 
						bar_reservation 
						left outer join bar_reservation_table ON bar_reservation_table.bar_reservation_id = bar_reservation.id
						left outer join bar_table ON bar_reservation_table.table_id = bar_table.id
						left outer join customer on customer.id = bar_reservation.customer_id
						left outer join reservation_room on reservation_room.id = bar_reservation.reservation_room_id
						left outer join room on room.id = reservation_room.room_id
						left outer join traveller on traveller.id = reservation_room.traveller_id
                        left join reservation_room on reservation_room.id=bar_reservation.reservation_room_id
					WHERE 
						'.$cond.' AND bar_reservation.bar_id in ('.$bar_ids.')
					ORDER BY
						bar_reservation.id
				';
			$report = new Report;
			$report->items = DB::fetch_all($sql);
			//System::debug($report->items);
            $sql='select bar_reservation.id,
                    --/** START : DAT-1343 **/
                    --sum((bar_reservation_product.price*(bar_reservation_product.quantity-NVL(bar_reservation_product.quantity_discount,0)-NVL(bar_reservation_product.quantity_cancel,0)))*(100-NVL(bar_reservation_product.discount_rate,0))/100) as price,            
                    sum((bar_reservation_product.price*(bar_reservation_product.quantity-NVL(bar_reservation_product.quantity_discount,0)-NVL(bar_reservation_product.quantity_cancel,0)))) as price,
                    sum((bar_reservation_product.price*(bar_reservation_product.quantity-NVL(bar_reservation_product.quantity_discount,0)-NVL(bar_reservation_product.quantity_cancel,0))*NVL(bar_reservation_product.discount_rate,0)/100)) as product_discount
                    --/** END : DAT-1343 **/
                    from bar_reservation_product
                    inner join bar_reservation ON bar_reservation_product.bar_reservation_id = bar_reservation.id
                    left outer join customer on customer.id = bar_reservation.customer_id
                    WHERE 
						'.$cond.' AND bar_reservation.bar_id in ('.$bar_ids.')
					group by
						bar_reservation.id
					';                      
            $record =  DB::fetch_all($sql);                  
			$i = 1;
			$bill_id = 'payment.bill_id = 0';
			foreach($report->items as $key=>$value)
			{			 
				$bill_id .='or payment.bill_id = '.$value['id'];
                    $total_price = 0;
                    $fee_tax = (1+$value['bar_fee_rate']/100)*(1+$value['tax_rate']/100);                    
                    foreach($record as $key2=>$value2)
                    {                           
                        if($value['id']==$value2['id'])
                        {                            
                            $report->items[$key]['total_price'] = $value2['price'];
                            $report->items[$key]['total_price_dp'] = $value2['price'] - $value2['product_discount'];
                            $total_price = $report->items[$key]['total_price'];
                            $total_price_dp = $report->items[$key]['total_price_dp'];                             
                            $report->items[$key]['product_discount'] = $value2['product_discount']/$fee_tax;
                            $product_discount = $report->items[$key]['product_discount'];
                            //System::debug($value['code'].'-------'.$total_price.'-----'.$value2['product_discount']);                            
                        }                                                
                    
                                        
                    }                    
                    if($value['full_rate']==0 and $value['full_charge']==0)//gia chua thue phi lay luon
                    {
                        $total_before_discount = $total_price;
                    }
                    if($value['full_rate']==1)//da co thue phi bo thue phi
                    {
                        //echo $total_price.'<br>';
                        $total_before_discount = ($total_price*100/(100+$value['tax_rate']))*100/(100+$value['bar_fee_rate']);
                        //giá trước thuế phí = tổng* 100/(100+10) 
                    } 
                    if($value['full_charge']==1)//da co phi bo phi di
                    {
                        $total_before_discount = $total_price*100/(100+$value['bar_fee_rate']);
                    }  
                    //echo  $total_before_discount.'<br>';                    
                    //tong truoc giam gia
                    $report->items[$key]['before_discount'] = $total_before_discount;
                    //tông sau giam gia                    
                    $after_discount = ($total_before_discount*(100-$value['discount_percent'])/100)-$value['discount'];                    
                    //System::debug($after_discount);
                    //so tien giam gia                                        
                    $report->items[$key]['total_discount'] = ($total_price_dp*$value['discount_percent']/100 + $value['discount'])/$fee_tax;
                    $total_discount = $report->items[$key]['total_discount'];                                                             
                                        
                    //so tien co phi
                    $after_fee = $total_before_discount-$product_discount-$total_discount;
                    //echo  $value['bar_fee_rate'].'<br>';                                        
                    //phi dich vu
                    $report->items[$key]['fee_rate'] = $after_fee*$value['bar_fee_rate']/100;                    
                    //so tien co thue phi                    
                    $report->items[$key]['total'] = $after_fee*(100+$value['tax_rate'])/100;
                    //echo $report->items[$key]['total'];
                    //thue                                        
                    $report->items[$key]['tax'] = $after_fee*(1+$value['bar_fee_rate']/100)*$value['tax_rate']/100;              	
			}
			//System::debug($report->items);
            $payments = $this->get_payments($bill_id);
            //System::debug($payments);
			$all_payments = DB::fetch_all('select payment_type.*,payment_type.name_'.Portal::language().' as name from payment_type where def_code is not null AND structure_id!=\'1000000000000000000\'');
			$all_currencies = DB::fetch_all('select * from currency where allow_payment=1');
			$credit_card = DB::fetch_all('select * from credit_card');
			foreach($report->items as $key=>$value)
			{
				$total_paid = 0; $total_debit = 0;
				$report->items[$key]['room'] = 0;
                $report->items[$key]['refund'] = 0;
				$report->items[$key]['bank'] = 0;
				$report->items[$key]['note'].= $value['room_name']?'Room name: '.$value['room_name']:'';
                //echo $value['pay_with_room'].'<br />';
				$report->items[$key]['pay_with_room'] = $value['pay_with_room'];
                if($value['pay_with_room']==1)
                {
					//$report->items[$key]['room'] += $value['total'];	
					//$total_paid += $value['total'];	
                    $total_paid += $value['amount_pay_with_room'];
                    $report->items[$key]['room'] = $value['amount_pay_with_room'];
				}
                
				foreach($payments as $k=>$pay)
                {
					if($pay['bill_id'] == $value['id'])
                    {
						if($pay['type_dps']=='BAR')
                        {
							$report->items[$key]['deposit'] += $pay['total_vnd'];
							
                            $total_paid += $pay['total_vnd'];		
						}
                        else
                        {
                            
							if($pay['payment_type_id']=='DEBIT')
                            {
								$total_debit = $pay['total_vnd'];
							}
                            else if($pay['payment_type_id']=='CREDIT_CARD')
                            {
								$report->items[$key][$pay['payment_type_id'].'_'.$pay['credit_card_id'].'_'.$pay['currency_id']] = $pay['total'];
							}
                            else if($pay['payment_type_id']=='BANK')
                            {
							     $report->items[$key]['bank'] += $pay['total_vnd'];
                                 $report->items[$key][$pay['payment_type_id'].'_'.$pay['currency_id']] = $pay['total_vnd'];
							}
                            else if($pay['payment_type_id']=='REFUND')
                            {
							     $report->items[$key]['refund'] += $pay['total_vnd'];
                                 $report->items[$key][$pay['payment_type_id'].'_'.$pay['currency_id']] = $pay['total_vnd'];
							}
                            else
                            {
								$report->items[$key][$pay['payment_type_id'].'_'.$pay['currency_id']] = $pay['total'];	
							}
                            //System::debug($report->items[$key]);
							if($pay['payment_type_id']=='FOC')
                            {
								$report->items[$key]['foc'] = $pay['total_vnd'];
							}
                            if($pay['payment_type_id']=='REFUND')
                            {
                                $total_paid -= $pay['total_vnd']; 
                            }
                            else
                            {
                              $total_paid += $pay['total_vnd'];  
                            }
						}
					}
				}
                    
				$report->items[$key]['debit'] = (int)($total_debit + round($value['total_2'] - $total_paid));
				$report->items[$key]['total'] =  $value['total_2'];
				$report->items[$key]['stt'] = $i++;
			}
			//System::debug($report->items);
			$report->currencies = $all_currencies;
			$report->payment_types = $all_payments;
			$report->credit_card = $credit_card;
            if(User::is_admin())
            {
                //System::debug($report->items);
            }
			$this->print_all_pages($report,$bar_ids);
		}
		else
		{
			$date_arr = array();
			for($i=1;$i<=31;$i++){
				$date_arr[strlen($i)<2?'0'.($i):$i] = strlen($i)<2?'0'.($i):$i;
			}
			$view_all = true;
			if(!User::can_view(false,ANY_CATEGORY) and User::can_view_detail(false,ANY_CATEGORY)){
				$_REQUEST['from_day'] = date('d');
				$view_all = false;
			}
			$bar_lists = DB::fetch_all('select bar.* from bar');
			$shift_lists = array();
			
            
			$shift_lists = DB::fetch_all('select bar_shift.* from bar_shift order by id ASC');
			$shift_list = '<option value="0">---------</option>';
			foreach($shift_lists as $k=>$shift){
				if(isset($bar_lists[$shift['bar_id']]['shifts'])){
					$bar_lists[$shift['bar_id']]['shifts'][$k] = $shift; 
				}else{
					$bar_lists[$shift['bar_id']]['shifts'][$k] = $shift;	
				}
				$shift_list .= '<option value="'.$shift['id'].'">'.$shift['name'].':'.$shift['brief_start_time'].'h - '.$shift['brief_end_time'].'h</option>';	
			} 
            
            if(Url::get('hotel_id'))
             {
                 if(Url::get('hotel_id')!='ALL')
                 {
                     $bars = DB::fetch_all("select id,name FROM bar where portal_id='".Url::get('hotel_id')."'");
                 }
                 else
                 {
                    $bars = DB::select_all('bar',false); 
                 }
             }
             else
             {
                $bars = DB::select_all('bar',false); 
             }
			$users = DB::fetch_all('select account.id,party.full_name, party.description_1 from account INNER JOIN party on party.user_id = account.id AND party.type=\'USER\' WHERE account.type=\'USER\' ORDER BY account.id');		
            
            $portal_department = DB::fetch_all("SELECT portal_department.id FROM portal_department WHERE portal_id='".PORTAL_ID."' AND (portal_department.department_code='RES' OR portal_department.department_code='CASHIER')");
            $user_temp = array();
            foreach($users as $key=>$value){
                foreach($portal_department as $k=>$v){
                    if(($value['description_1']==$v['id']) && !array_key_exists($key,$user_temp)){
                        $user_temp[$key]=$value;
                    } 
                }                                 
            }
            $users=$user_temp;
            //System::debug($this->map);
            $this->parse_layout('search',
				array(			
				'user_id_list'=>array(''=>Portal::language('all_user'))+String::get_list($users),	
				'view_all'=>$view_all,
				'from_date'=>$from_date,
				'to_date'=>$to_date,
				'bar_id' => URL::get('bar_id',''),
				'shift_list' =>$shift_list,
				'bar_lists' =>String::array2js($bar_lists),
				'bars' =>$bars, 
				'hotel_id_list'=>array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list())
				)
			);	
		}			
	}
	function print_all_pages(&$report,$bar_ids)
	{
	       $check_table=0;
        $check_table_out = DB::fetch_all('select bar_reservation.id from bar_reservation inner join bar on bar.id = bar_reservation.bar_id where status=\'CHECKIN\' and departure_time < '.time().' and bar_reservation.bar_id in ('.$bar_ids.')  ');
        if(count($check_table_out)>0){
            $check_table =1;
        }
		$count = 0;
		$total_page = 1;
		$pages = array();
		foreach($report->items as $key=>$item)
		{
			if($count>=$this->map['line_per_page'])
			{
				$count = 0;
				$total_page++;
			}
			$pages[$total_page][$key] = $item;
			$count++;
		}
		if(sizeof($pages)>0)
		{
			$this->group_function_params = array(
					'total'=>0,
					'deposit'=>0,
					'debit'=>0,
                    'refund'=>0,
					'foc'=>0,
					'room'=>0,
					'bank'=>0,
                    'before_discount'=>0,
                    'total_discount'=>0,
                    'fee_rate'=>0,
                    'tax'=>0
				);
			foreach($report->payment_types as $key =>$payment){
				if($payment['def_code']!='FOC' && $payment['def_code']!='DEBIT'){
					if($payment['def_code']=='CREDIT_CARD'){
						foreach($report->credit_card as $key =>$credit_card){
							foreach($report->currencies as $key =>$currency){
								$this->group_function_params += array(''.$payment['def_code'].'_'.$credit_card['id'].'_'.$currency['id'].''=>'');
							}
						}
					}else{
						foreach($report->currencies as $key => $currency){
							$this->group_function_params += array(''.$payment['def_code'].'_'.$currency['id'].''=>'');
						}	
					}
				}
			}
			$this->map['real_total_page']=count($pages);
            $this->map['real_page_no'] = 1;  
            //System::debug($pages);
            foreach($pages as $page_no=>$page)
			{			 
				$this->print_page($page, $report, $page_no,$total_page,$check_table);
                $this->map['real_page_no'] ++;
            }
		}
		else
		{
			if(Url::get('hotel_id')){
				$hotel = DB::fetch('SELECT NAME_1 AS name,ADDRESS FROM PARTY WHERE USER_ID = \''.Url::get('hotel_id').'\'');
				$hotel_name = $hotel['name']?$hotel['name']:HOTEL_NAME;
				$hotel_address = $hotel['address']?$hotel['address']:HOTEL_ADDRESS;
			}else{
				$hotel_name = HOTEL_NAME;
				$hotel_address = HOTEL_ADDRESS;
			}	
			$this->parse_layout('header',
			get_time_parameters()+
				array(
                    'check_table'=>$check_table,
					'hotel_address'=>$hotel_address,
					'hotel_name'=>$hotel_name,
					'page_no'=>0,
					'total_page'=>0,
                    'start_page'=>Url::get('start_page'),
                    'from_bill'=>Url::get('from_bill'),
                    'to_bill'=>Url::get('to_bill')
				)+$this->map
			);
            $this->parse_layout('no_report',array());
			$this->parse_layout('footer',array(
				'page_no'=>0,
				'total_page'=>0
			));
		}
	}
	function print_page($items, &$report, $page_no,$total_page,$check_table)
	{
		$payment = array();
		$credit_card = 0;
		$total_currency = 0;
        //foreach($items as $id=>$item)
//		{
//			$items[$id]['code'] = substr($item['code'],-6);
//		}
		$last_group_function_params = $this->group_function_params;
        //System::debug($this->group_function_params);
		foreach($items as $item)
		{
			foreach($report->payment_types as $key =>$payment){
				if($payment['def_code']!='FOC' && $payment['def_code']!='DEBIT'){
					if($payment['def_code']=='CREDIT_CARD'){
						foreach($report->credit_card as $key =>$credit_card){
							foreach($report->currencies as $key =>$currency){
								if(isset($item[''.$payment['def_code'].'_'.$credit_card['id'].'_'.$currency['id'].'']) && $temp = $item[''.$payment['def_code'].'_'.$credit_card['id'].'_'.$currency['id'].'']){
									$this->group_function_params[''.$payment['def_code'].'_'.$credit_card['id'].'_'.$currency['id'].''] += $temp;
								}
							}	
						}
					}
					foreach($report->currencies as $key => $currency){
						if(isset($item[''.$payment['def_code'].'_'.$currency['id'].'']) && $temp = $item[''.$payment['def_code'].'_'.$currency['id'].'']){
							$this->group_function_params[''.$payment['def_code'].'_'.$currency['id'].''] += $temp;
						}
					}	
				}
			}      
            //System::debug($item['total_discount']);      
            if($temp = $item['before_discount']){
				$this->group_function_params['before_discount'] += $temp;
			}
            if($temp = $item['total_discount']){
				$this->group_function_params['total_discount'] += $temp;
			}
            if($temp = $item['fee_rate']){
				$this->group_function_params['fee_rate'] += $temp;
			}
            if($temp = $item['tax']){
				$this->group_function_params['tax'] += $temp;
			}
			if($temp = $item['total']){
				$this->group_function_params['total'] += $temp;
			}
			if($temp = $item['debit']){
				$this->group_function_params['debit'] += $temp;
			}
			if($temp = $item['foc']){
				$this->group_function_params['foc'] += $temp;
			}
			if($temp = $item['bank']){
				$this->group_function_params['bank'] += $temp;
			}
            if($temp = $item['refund']){
				$this->group_function_params['refund'] += $temp;
			}
			if($temp = $item['room']){
				$this->group_function_params['room'] += $temp;
			}
			if($temp = $item['deposit']){
				$this->group_function_params['deposit'] += $temp;
			}
		}
		//$total = $this->group_function_params['pay_by_cash']+$fee_summary['pay_by_cash_fee']+$fee_summary['pay_by_cash_tax'];		
		if(Url::get('hotel_id')){
				$hotel = DB::fetch('SELECT NAME_1 AS name,ADDRESS FROM PARTY WHERE USER_ID = \''.Url::get('hotel_id').'\'');
				$hotel_name = $hotel['name'];
				$hotel_address = $hotel['address'];
			}else{
				$hotel_name = HOTEL_NAME;
				$hotel_address = HOTEL_ADDRESS;
			}
         if($page_no>=$this->map['start_page']){   	
    		$this->parse_layout('header',
    			array(
                    'check_table'=>$check_table,
    				'hotel_address'=>$hotel_address,
    				'hotel_name'=>$hotel_name,
    				'page_no'=>$page_no,
    				'total_page'=>$total_page,
                    'start_page'=>Url::get('start_page'),
                    'from_bill'=>Url::get('from_bill'),
                    'to_bill'=>Url::get('to_bill')
    			)+$this->map
    		);
    		$layout = 'report';
    		if(Url::get('revenue')=='min'){
    			$layout = 'report_cash';
    			$report->payment_types = DB::fetch_all('select payment_type.*,payment_type.name_'.Portal::language().' as name from payment_type where def_code =\'CASH\'');
    		}
            //System::debug($report->payment_types);
            //System::debug($report->payment_types);
    		$this->parse_layout($layout,array(
    				'payment_types'=>$report->payment_types,
    				'currencies'=>$report->currencies,
    				'credit_card'=>$report->credit_card,
    				'items'=>$items,
    				'last_group_function_params'=>$last_group_function_params,
    				'group_function_params'=>$this->group_function_params,
    				'page_no'=>$page_no,
    				'total_page'=>$total_page,
    			)+$this->map
    		);
    		$this->parse_layout('footer',array(				
    			'payment'=>$payment,
    			'credit_card_total'=>$credit_card,
    			'page_no'=>$page_no,
    			'total_page'=>$total_page,
    		)+$this->map
            );
         }   
        
	}
	function get_payments($bill_id)
    {
		//$hi = DB::fetch_all('select * from payment where payment.time>='.$time_from.' AND payment.time<='.$time_to.'');
		//System::debug($hi);
		return $payments = DB::fetch_all('
						SELECT 
							(payment.payment_type_id || \'_\' || payment.credit_card_id || \'_\' || payment.currency_id || \'_\' || payment.bill_id || \'_\' || payment.type_dps) as id
							,SUM(amount) as total
							,SUM(amount*payment.exchange_rate) as total_vnd
							,CONCAT(payment.payment_type_id,CONCAT(\'_\',payment.currency_id)) as name
							,payment.bill_id
							,payment.payment_type_id
							,payment.credit_card_id
							,payment.currency_id
							,payment.type_dps
						FROM payment
							inner join bar_reservation on payment.bill_id = bar_reservation.id
						WHERE 
							('.$bill_id.') AND payment.type=\'BAR\'
						GROUP BY payment.payment_type_id,payment.currency_id,payment.bill_id
						,payment.currency_id,payment.type_dps,payment.credit_card_id
				');	
	}
	function get_vat_invoice_cond($from_code,$to_code){
		$sql = 'select 
					vat_bill.id as id
					,vat_bill.bar_reservation_id
				FROM 
					vat_bill
				WHERE SUBSTR(vat_bill.code, 6 ) >='.$from_code.' AND SUBSTR(vat_bill.code, 6 )<='.$to_code.'
                    AND vat_bill.department = \'RESTAURANT\'
                    ';
		$vats = DB::fetch_all($sql);	
		$cond = '';
		foreach($vats as $v => $vat){
            if( strpos( $vat['bar_reservation_id'],',' ) )
            {
                $arr = explode(',',$vat['bar_reservation_id']);
                foreach($arr as $b_r_id)
                    $cond .= ($cond=='')?'bar_reservation.id='.$b_r_id:' OR bar_reservation.id='.$b_r_id;
            }
            else
            {
                $cond .= ($cond=='')?'bar_reservation.id='.$vat['bar_reservation_id']:' OR bar_reservation.id='.$vat['bar_reservation_id']; 
            }
		}
		return $cond;
	}
    
    function calc_time($string)
    {
        $arr = explode(':',$string);
        //System::debug($arr);
        return $arr[0]*3600 + $arr[1]*60;
    }
}
?>
