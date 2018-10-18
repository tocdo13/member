<?php
class ReceiptByEmployeeForm extends Form
{
	function ReceiptByEmployeeForm()
	{
		Form::Form('ReceiptByEmployeeForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js'); 
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
	}
	function draw()
	{
        $this->map = array();
		if(Url::get('do_search'))
		{
			if(Url::get('karaoke_id')){
				Session::set('karaoke_id',intval(Url::get('karaoke_id')));
			}else{
				$karaoke_id = Session::get('karaoke_id');	
			}
			$_REQUEST['karaoke_id'] = Session::get('karaoke_id');
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
            
			$from_day = Url::get('date_from');
            $to_day = Url::get('date_to');
			
            $this->line_per_page = Url::get('line_per_page',999999999);
			
            
            $shift_lists = DB::fetch_all('select karaoke_shift.* from karaoke_shift order by id ASC');
			$time_from = Date_Time::to_time($from_day);
			$time_to = (Date_Time::to_time($to_day)+24*3600);
			/*
            if(Url::get('shift_id')){
				$shift_from = $shift_lists[Url::get('shift_id')]['start_time'];
                $this->map['start_shift_time'] = $shift_lists[Url::get('shift_id')]['brief_start_time'];
				$shift_to = $shift_lists[Url::get('shift_id')]['end_time'];
                $this->map['end_shift_time'] = $shift_lists[Url::get('shift_id')]['brief_end_time'];
			}
            */
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
            
            
            
			$cond = $this->cond = ' 1 >0 '
				.(Url::get('karaoke_id')?' and karaoke_reservation.karaoke_id = '.Url::get('karaoke_id').'':'') 
				.(Url::get('user_id')?' and karaoke_reservation.checked_out_user_id = \''.Url::get('user_id').'\'':'') 
				.' and karaoke_reservation.departure_time>='.$time_from.' and karaoke_reservation.departure_time<'.$time_to.'
                    and karaoke_reservation.status=\'CHECKOUT\' '
				;
				//AND karaoke_reservation.payment_result IS NOT NULL' and karaoke_reservation.status=\'CHECKOUT\' '
			if(User::can_admin(false,ANY_CATEGORY)){
				$cond .= Url::get('hotel_id')?' and karaoke_reservation.portal_id = \''.Url::get('hotel_id').'\'':'';
			}else{
				$cond .= ' and karaoke_reservation.portal_id = \''.PORTAL_ID.'\'';	
			}
			$sql = 'SELECT
						karaoke_reservation.id,karaoke_reservation.code,karaoke_reservation.departure_time
						,karaoke_reservation.total,karaoke_reservation.payment_result
						,karaoke_reservation.checked_out_user_id as receptionist_id
						,karaoke_reservation.exchange_rate
						,karaoke_reservation.receiver_name
						,karaoke_reservation.karaoke_fee_rate
						,karaoke_reservation.tax_rate
						,karaoke_reservation.time_out
						,customer.name as customer_name
						,karaoke_table.name as table_name
						,CONCAT(traveller.first_name,CONCAT(\' \',traveller.first_name)) as traveller_name
						,0 as deposit
						,0 as paid
						,0 as foc
						,party.full_name
					FROM 
						karaoke_reservation 
						left outer join karaoke_reservation_table ON karaoke_reservation_table.karaoke_reservation_id = karaoke_reservation.id
						left outer join karaoke_table ON karaoke_reservation_table.table_id = karaoke_table.id
						left outer join customer on customer.id = customer_id
						left outer join reservation_room on reservation_room.id = karaoke_reservation.reservation_room_id
						left outer join room on room.id = reservation_room.room_id
						left outer join traveller on traveller.id = reservation_room.traveller_id
						left JOIN party on party.user_id = karaoke_reservation.checked_out_user_id
					WHERE 
						'.$cond.'
					ORDER BY
						karaoke_reservation.checked_out_user_id
				';
			$report = new Report;
			$report->items = array();
			$items = DB::fetch_all($sql);
            //System::debug($sql);
			
            //System::debug($items);
			$count = 0;
			if(!isset($shift_from) && !isset($shift_to)){
				$shift_from = 0;
				$shift_to = 86400;	
			}
			for($i=$time_from; $i<=$time_to;$i=$i+86400){
				foreach($items as $key=>$value){
					if($value['departure_time']>=($i+$shift_from) && ($value['departure_time']<=($i+$shift_to))){
						$count ++;  
						if($count >((Url::get('start_page',1)-1)*$this->line_per_page) && $count<=(Url::get('no_of_page',999999999)*$this->line_per_page)){
							$report->items[$count] = $value;	
						}
					}
				}	
			}
			$i = 1;
			$bill_id = '0';
			foreach($report->items as $key=>$value)
			{
				$bill_id .=','.$value['id'];	
			}
			$payments = $this->get_payments($bill_id);
			$all_payments = DB::fetch_all('select payment_type.*,payment_type.name_'.Portal::language().' as name from payment_type where def_code is not null');
			$all_currencies = DB::fetch_all('select * from currency where allow_payment=1');
			$credit_card = DB::fetch_all('select * from credit_card');
			//System::Debug($payments);
			foreach($report->items as $key=>$value)
			{
				$total_paid = 0; $total_debit = 0;
				foreach($payments as $k=>$pay){
					if($pay['bill_id'] == $value['id']){
						if($pay['type_dps']=='karaoke'){
							$report->items[$key]['deposit'] += $pay['total_vnd'];
							$total_paid += $pay['total_vnd'];		
						}else{
							if($pay['payment_type_id']=='DEBIT'){
								$total_debit = $pay['total_vnd'];
							}else if($pay['payment_type_id']=='CREDIT_CARD'){
								$report->items[$key][$pay['payment_type_id'].'_'.$pay['credit_card_id'].'_'.$pay['currency_id']] = $pay['total'];
							}else{
								$report->items[$key][$pay['payment_type_id'].'_'.$pay['currency_id']] = $pay['total'];	
							}
							if($pay['payment_type_id']=='FOC'){
								$report->items[$key]['foc'] = $pay['total_vnd'];
							}
							$total_paid += $pay['total_vnd'];	
						}
					}
				}
				$report->items[$key]['debit'] = (int)($total_debit + round($value['total'] - $total_paid));
				$report->items[$key]['total'] =  $value['total'];
				$report->items[$key]['stt'] = $i++;
			}
			$report->currencies = $all_currencies;
			$report->payment_types = $all_payments;
			$report->credit_card = $credit_card;
            //System::debug($report->items);
			$this->print_all_pages($report);
		}
		else
		{
            $_REQUEST['start_time'] = '00:00';
            $_REQUEST['end_time'] = '23:59'; 
			$_REQUEST['date_from'] = date('d/m/Y');
            $_REQUEST['date_to'] = date('d/m/Y');
			$view_all = true;
			if(!User::can_view(false,ANY_CATEGORY) and User::can_view_detail(false,ANY_CATEGORY)){
				$_REQUEST['from_day'] = date('d');
				$view_all = false;
			}
			$karaoke_lists = DB::fetch_all('select karaoke.* from karaoke');
			$shift_lists = array();
			if(Session::get('karaoke_id')){
				$karaoke_id = Session::get('karaoke_id');		
			}else{
				$karaoke_id = DB::fetch('select min(id) as id from karaoke where 1>0 and portal_id=\''.PORTAL_ID.'\'','id');
			}
			if(Url::get('karaoke_id')){
				$karaoke_id = Url::get('karaoke_id');	
			}
			// Lấy danh sách các ca làm vc
			$shift_lists = DB::fetch_all('select karaoke_shift.* from karaoke_shift order by id ASC');
			$shift_list = '<option value="0">---------</option>';
			foreach($shift_lists as $k=>$shift){
				if(isset($karaoke_lists[$shift['karaoke_id']]['shifts'])){
					$karaoke_lists[$shift['karaoke_id']]['shifts'][$k] = $shift; 
				}else{
					$karaoke_lists[$shift['karaoke_id']]['shifts'][$k] = $shift;	
				}
				$shift_list .= '<option value="'.$shift['id'].'">'.$shift['name'].':'.$shift['brief_start_time'].'h - '.$shift['brief_end_time'].'h</option>';	
			}
			//Lấy ra các account
			$users = DB::fetch_all('select 
										account.id
										,party.full_name 
									from account 
									INNER JOIN party on party.user_id = account.id AND party.type=\'USER\' WHERE account.type=\'USER\' AND LOWER(FN_CONVERT_TO_VN(party.description_1))=\'nha hang\' ORDER BY account.id');			
			//System::debug($users);
            
            $this->parse_layout('search',
				array(			
				'user_id_list'=>array(''=>Portal::language('all_user'))+String::get_list($users),	
				'view_all'=>$view_all,
				'karaoke_id' => Url::get('karaoke_id')?(Url::get('karaoke_id')):(Session::get('karaoke_id')?Session::get('karaoke_id'):1),
				'shift_list' =>$shift_list,
				'karaoke_lists' =>String::array2js($karaoke_lists),
				'karaoke_id_list' =>String::get_list(DB::select_all('karaoke',false)), 
				'hotel_id_list'=>array(''=>Portal::language('all'))+String::get_list(Portal::get_portal_list())
				)
			);	
		}			
	}
	function print_all_pages(&$report)
	{
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
					'deposit'=>0,
					'debit'=>0,
					'foc'=>0
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
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page);
			}
		}
		else
		{
		    $from_day = Url::get('date_from');
            $to_day = Url::get('date_to');
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
                    'to_date'=>$to_day,
                    'from_date'=>$from_day,
					'hotel_name'=>$hotel_name,
					'page_no'=>0,
					'total_page'=>0
				)+$this->map
			);
			$this->parse_layout('footer',array(
				'page_no'=>0,
				'total_page'=>0
			)+$this->map);
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
			if($temp = $item['total']){
				$this->group_function_params['total'] += $temp;
			}
			if($temp = $item['debit']){
				$this->group_function_params['debit'] += $temp;
			}
			if($temp = $item['foc']){
				$this->group_function_params['foc'] += $temp;
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
		$this->parse_layout('header',
			array(
				'to_date'=>$to_day,
                'from_date'=>$from_day,
                'hotel_address'=>$hotel_address,
				'hotel_name'=>$hotel_name,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)+$this->map
		);
		$users = DB::fetch_all('select 
										account.id
										,party.full_name 
									from account 
									INNER JOIN party on party.user_id = account.id AND party.type=\'USER\' WHERE account.type=\'USER\'  ORDER BY account.id');			
		//AND LOWER(FN_CONVERT_TO_VN(party.description_1))=\'nha hang\'
        //System::debug($items);
        //System::debug($users);
        $this->parse_layout('report',array(
				'users'=>$users,
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
		)+$this->map);
	}
	function get_payments($bill_id){
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
							payment.bill_id in ('.$bill_id.') AND payment.type=\'karaoke\'
						GROUP BY payment.payment_type_id,payment.currency_id,payment.bill_id
						,payment.currency_id,payment.type_dps,payment.credit_card_id
				');	
	}
    
    function calc_time($string)
    {
        $arr = explode(':',$string);
        //System::debug($arr);
        return $arr[0]*3600 + $arr[1]*60;
    }
}
?>