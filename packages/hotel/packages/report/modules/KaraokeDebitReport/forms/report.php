<?php
class KaraokeDebitReportForm extends Form
{
	function KaraokeDebitReportForm()
	{
		Form::Form('KaraokeDebitReportForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css'); 
	}
	function draw()
	{
		if(URL::get('do_search'))
		{
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
			$from_day = Url::get('date_from');
            $to_day = Url::get('date_to');
			$this->line_per_page = URL::get('line_per_page',15);
			$cond = $this->cond = ''
				.(URL::get('karaoke_id')?' and karaoke_reservation.karaoke_id = '.URL::get('karaoke_id').'':'') 
				.' and karaoke_reservation.time_out>='.Date_Time::to_time($from_day).' and karaoke_reservation.time_out<'.(Date_Time::to_time($to_day)+86400).''
				.' '
			;//and karaoke_reservation.status=\'CHECKOUT\'
			if(Url::get('customer_name')){
				$cond .= ' AND customer.name LIKE \'%'.Url::get('customer_name').'%\'';
			}
			if(Url::get('code')){
				$cond .= ' AND upper(customer.code) LIKE \'%'.strtoupper(Url::sget('code')).'%\'';
			}
			if(User::can_admin(false,ANY_CATEGORY)){
				$cond .= Url::get('hotel_id')?' and karaoke_reservation.portal_id = \''.Url::get('hotel_id').'\'':'';
			}else{
				$cond .= ' and karaoke_reservation.portal_id = \''.PORTAL_ID.'\'';	
			}
			$sql = 'SELECT
						karaoke_reservation.id,karaoke_reservation.code
						,karaoke_reservation.total,karaoke_reservation.payment_result
						,karaoke_reservation.user_id as receptionist_id
						,karaoke_reservation.exchange_rate
						,karaoke_reservation.receiver_name
						,karaoke_reservation.karaoke_fee_rate
						,karaoke_reservation.tax_rate
						,karaoke_reservation.arrival_time
						,karaoke_reservation.departure_time
						,karaoke_reservation.time_out
						,customer.name as customer_name
						,room.name as room_name
						,karaoke_reservation.receiver_name
						,CONCAT(traveller.first_name,CONCAT(\' \',traveller.first_name)) as traveller_name
						,0 as deposit
						,0 as paid
						,0 as foc
					FROM 
						karaoke_reservation 
						left outer join customer on customer.id = customer_id
						left outer join reservation_room on reservation_room.id = karaoke_reservation.reservation_room_id
						left outer join room on room.id = reservation_room.room_id
						left outer join reservation_traveller on reservation_traveller.id = karaoke_reservation.reservation_traveller_id
						left outer join traveller ON reservation_traveller.traveller_id = traveller.id
					WHERE 
						1>0 '.$cond.'
					ORDER BY
						karaoke_reservation.id
				';

			$report = new Report;
			$items = DB::fetch_all($sql);
			$bill_id = '0';
			foreach($items as $k=>$value)
			{
				$bill_id .=','.$value['id'];	
			}
			$payments = $this->get_payments($cond);
            //System::debug($payments);
			foreach($items as $key=>$value)
			{
				$total_paid = 0; $total_debit = 0;
				foreach($payments as $k=>$pay){
					if($pay['bill_id'] == $value['id']){
						if($pay['type_dps']=='karaoke'){
							$total_paid += $pay['total_vnd'];	
						}else{
							if($pay['payment_type_id']=='DEBIT'){
								$total_debit = $pay['total_vnd'];
							}
							if($pay['payment_type_id']=='FOC'){
								$items[$key]['foc'] = $pay['total_vnd'];
							}
							$total_paid += $pay['total_vnd'];	
						}
					}
				}
				$items[$key]['debit'] = (int)($total_debit + round($value['total'] - $total_paid));
				$items[$key]['total'] =  $value['total'];
				$items[$key]['paid']= $total_paid - $total_debit;
			}
			$report->items = array();
			$count=0;
			foreach($items as $key=>$value){
				$count ++;  
				if($value['debit']>0 && $count >((URL::get('start_page')-1)*$this->line_per_page) && $count<=(URL::get('no_of_page')*$this->line_per_page)){
					$report->items[$count] = $value;	
					$report->items[$count]['stt'] = $count;
					$report->items[$count]['full_name'] = ($value['customer_name']!='')?$value['customer_name']:(($value['traveller_name']!='')?$value['traveller_name']:(($value['receiver_name']!='')?$value['receiver_name']:''));
				}
			}
            
            foreach($report->items as $key => $vaue)
            {
                if ($vaue['room_name'] != '')
                    unset($report->items[$key]);
            }	
			$this->print_all_pages($report);
            //System::debug($report->items);
		}
		else
		{
			$_REQUEST['date_from'] = date('d/m/Y');
            $_REQUEST['date_to'] = date('d/m/Y');
			$view_all = true;
			if(!User::can_view(false,ANY_CATEGORY) and User::can_view_detail(false,ANY_CATEGORY)){
				$_REQUEST['from_day'] = date('d');
				$view_all = false;
			}
			$this->parse_layout('search',
				array(				
				'view_all'=>$view_all,
				'karaoke_id' => URL::get('karaoke_id',''),
				'karaoke_id_list' =>String::get_list(DB::select_all('karaoke',false)), 
				'hotel_id_list'=>array(''=>Portal::language('all'))+String::get_list(Portal::get_portal_list())
				)
			);	
		}			
	}
	function print_all_pages(&$report)
	{
	    $from_day = Url::get('date_from');
            $to_day = Url::get('date_to');
		$count = 0;
		$total_page = 1;
		$pages = array();
		foreach($report->items as $key=>$item)
		{
			if($count>=$this->line_per_page)
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
					'amount'=>0,					
					'paid'=>0,
					'debit'=>0
				);
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page);
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
					'hotel_address'=>$hotel_address,
					'hotel_name'=>$hotel_name,
                    'to_date'=>$to_day,
                    'from_date'=>$from_day,
					'page_no'=>0,
					'total_page'=>0
				)
			);
			$this->parse_layout('footer',array(
				'page_no'=>0,
				'total_page'=>0
			));
		}
	}
	function print_page($items, &$report, $page_no,$total_page)
	{
	    $from_day = Url::get('date_from');
            $to_day = Url::get('date_to');
		$payment = array();
		$credit_card = 0;
		$total_currency = 0;
		foreach($items as $id=>$item)
		{
			//$item['exchange_rate'] = $item['exchange_rate']?$item['exchange_rate']:DB::fetch('select exchange from currency where id = \'VND\'','exchange');
			//$item['total'] = $item['total'];//*$item['exchange_rate']/RES_EXCHANGE_RATE;
			if(!isset($items[$id]['debit'])){
				$items[$id]['debit'] = 0;
			}
			if($item['foc']){
				$items[$id]['foc'] = $item['foc'];
			}else{
				$items[$id]['free'] = 0;
			}
			$order_id = '';
			for($i=0;$i<6-strlen($item['id']);$i++)
			{
				$order_id .= '0';
			}
			$order_id .= $item['id'];
			$items[$id]['code'] = $order_id;
		}
		$last_group_function_params = $this->group_function_params;
		foreach($items as $item)
		{
			if($temp = System::calculate_number($item['total']))
			{
				$this->group_function_params['total'] += $temp;
			}
			if($temp = System::calculate_number($item['debit']))
			{
				$this->group_function_params['debit'] += $temp;
			}
			if($temp = System::calculate_number($item['paid']))
			{
				$this->group_function_params['paid'] += $temp;
			}
			/*if(isset($item['foc']) && $temp = System::calculate_number($item['foc']))
			{
				$this->group_function_params['foc'] += $temp;
			}*/
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
		$this->parse_layout('header',
			array(
				'hotel_address'=>$hotel_address,
				'hotel_name'=>$hotel_name,
                'to_date'=>$to_day,
                'from_date'=>$from_day,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)
		);
       
        
        // System::debug($items);
		$this->parse_layout('report',array(
				'items'=>$items,
				'last_group_function_params'=>$last_group_function_params,
				'group_function_params'=>$this->group_function_params,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)
		);
		$this->parse_layout('footer',array(				
			'payment'=>$payment,
			'credit_card_total'=>$credit_card,
			'page_no'=>$page_no,
			'total_page'=>$total_page,
		));
	}
	function get_payments($cond){
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
							inner join karaoke_reservation on payment.bill_id = karaoke_reservation.id
						WHERE 
							payment.type=\'karaoke\' AND payment.bill_id in 
								(SELECT
									karaoke_reservation.id
								FROM 
									karaoke_reservation 
									left outer join customer on customer.id = customer_id
									left outer join reservation_room on reservation_room.id = karaoke_reservation.reservation_room_id
									left outer join room on room.id = reservation_room.room_id
									left outer join reservation_traveller on reservation_traveller.id = karaoke_reservation.reservation_traveller_id
									left outer join traveller ON reservation_traveller.traveller_id = traveller.id
								WHERE 
									1>0 '.$cond.'
								)
						GROUP BY payment.payment_type_id,payment.currency_id,payment.bill_id
						,payment.currency_id,payment.type_dps,payment.credit_card_id
				');	
	}
}
?>