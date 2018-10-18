<?php
class KaraokeOrderRevenueReportForm extends Form
{
	function KaraokeOrderRevenueReportForm()
	{
		Form::Form('KaraokeOrderRevenueReportForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');        
        
	}
	function draw()
	{
        $this->map = array();
		if(URL::get('do_search'))
		{
			
			$karaokes = DB::fetch_all('select * from karaoke');
			$karaoke_ids = '';
            $karaoke_name = '';
			foreach($karaokes as $k => $karaoke){
				if(Url::get('karaoke_id_'.$k)){
					$karaoke_ids .= ($karaoke_ids=='')?$k:(','.$k);	
                    $karaoke_name .= ($karaoke_name=='')?$karaoke['name']:(', '.$karaoke['name']);
				}
			}
            //System::debug($karaoke_ids);
            $_REQUEST['karaoke_name'] = $karaoke_name;
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
			$from_day = Url::get('date_from');
            $to_day = Url::get('date_to');
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
			$shift_lists = DB::fetch_all('select karaoke_shift.* from karaoke_shift order by id ASC');
			$time_from = Date_Time::to_time($from_day);
			$time_to = (Date_Time::to_time($to_day));//+24*3600
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
            $cond = '';
			if($cond_code!='')
            { // Neu co dieu kien tim theo code roi thi ko lay dieu kien thoi gian nua
				$cond .= ' 1 = 1 AND ('.$cond_code.')';
			}
            else
            {
				//echo $shift_to.'--'.$shift_from;
    			$cond = $this->cond = ' 1 >0 '
    				.(URL::get('karaoke_id')?' and karaoke_reservation.karaoke_id = '.URL::get('karaoke_id').'':'') 
    				.(URL::get('user_id')?' and karaoke_reservation.checked_out_user_id = \''.URL::get('user_id').'\'':'') 
    				.' and karaoke_reservation.time_out>='.($time_from+$shift_from).' and karaoke_reservation.time_out<'.($time_to+$shift_to).''
    				.((URL::get('revenue')=='min')?' and karaoke_reservation.total<200000':((URL::get('revenue')=='max')?' and karaoke_reservation.total>=200000':'')).' and karaoke_reservation.status=\'CHECKOUT\' '
    				;
    			if(Url::get('customer_name')){
    				$cond .= ' AND customer.name LIKE \'%'.Url::get('customer_name').'%\'';
    			}
    			if(Url::get('code')){
    				$cond .= ' AND upper(customer.code) LIKE \'%'.strtoupper(Url::sget('code')).'%\'';
    			}
			}
				//AND karaoke_reservation.payment_result IS NOT NULL' and karaoke_reservation.status=\'CHECKOUT\' '
			if(User::can_admin(false,ANY_CATEGORY)){
				$cond .= Url::get('hotel_id')?' and karaoke_reservation.portal_id = \''.Url::get('hotel_id').'\'':'';
			}else{
				$cond .= ' and karaoke_reservation.portal_id = \''.PORTAL_ID.'\'';	
			}
            //System::debug($cond);
			//echo $cond;
			$sql = '
				SELECT * FROM (
					SELECT
						karaoke_reservation.id
                        ,karaoke_reservation.code
                        ,karaoke_reservation.departure_time
						,karaoke_reservation.total
                        ,karaoke_reservation.payment_result
						,karaoke_reservation.checked_out_user_id as receptionist_id
						,karaoke_reservation.exchange_rate
						,karaoke_reservation.receiver_name
						,karaoke_reservation.karaoke_fee_rate
						,karaoke_reservation.tax_rate
						,karaoke_reservation.note
						,karaoke_reservation.time_out
						,karaoke_reservation.pay_with_room
						,customer.name as customer_name
						,karaoke_reservation.karaoke_id
						,karaoke_table.name as table_name
						,CONCAT(traveller.first_name,CONCAT(\' \',traveller.first_name)) as traveller_name
						,room.name as room_name
						,0 as deposit
						,0 as paid
						,0 as foc
						,karaoke_reservation.amount_pay_with_room
						,ROW_NUMBER() OVER(ORDER BY karaoke_reservation.id ) as rownumber
					FROM 
						karaoke_reservation 
						left outer join karaoke_reservation_table ON karaoke_reservation_table.karaoke_reservation_id = karaoke_reservation.id
						left outer join karaoke_table ON karaoke_reservation_table.table_id = karaoke_table.id
						left outer join customer on customer.id = karaoke_reservation.customer_id
						left outer join reservation_room on reservation_room.id = karaoke_reservation.reservation_room_id
						left outer join room on room.id = reservation_room.room_id
						left outer join traveller on traveller.id = reservation_room.traveller_id
					WHERE 
						'.$cond.' AND karaoke_reservation.karaoke_id in ('.$karaoke_ids.')
					ORDER BY
						karaoke_reservation.id
					)
				WHERE rownumber>'.((URL::get('start_page')-1)*$this->line_per_page).' AND rownumber <= '.(URL::get('no_of_page')*$this->line_per_page).'
				';
			$report = new Report;
			$report->items = array();
			$report->items = DB::fetch_all($sql);
			
			$i = 1;
			$bill_id = '0';
			foreach($report->items as $key=>$value)
			{
				$bill_id .=','.$value['id'];	
			}
			$payments = $this->get_payments($bill_id);
            //System::debug($payments);
			$all_payments = DB::fetch_all('select payment_type.*,payment_type.name_'.Portal::language().' as name from payment_type where def_code is not null');
			$all_currencies = DB::fetch_all('select * from currency where allow_payment=1');
			$credit_card = DB::fetch_all('select * from credit_card');
            //if(User::is_admin())
                //System::Debug($report->items);
			foreach($report->items as $key=>$value)
			{
			     $sing_room = array();
                $sing_room = DB::fetch_all("select karaoke_reservation_table.*, karaoke_table.name
                        from karaoke_reservation_table 
                            inner join karaoke_table on karaoke_table.id = karaoke_reservation_table.table_id                                
                        where karaoke_reservation_id = ".$value['id']);
                foreach($sing_room as $id1=>$content1)
                {
                    if($sing_room[$id1]['sing_start_time']!='' AND $sing_room[$id1]['sing_end_time']!='')
                    {    
                        $value['total'] += ($content1['price']/3600)*($sing_room[$id1]['sing_end_time']-$sing_room[$id1]['sing_start_time']);
                    }
                }
				$total_paid = 0; $total_debit = 0;
				$report->items[$key]['room'] = 0;
				$report->items[$key]['bank'] = 0;
                $report->items[$key]['refund'] = 0;
				$report->items[$key]['note'].= $value['room_name']?'Room name: '.$value['room_name']:'';
                //echo $value['pay_with_room'].'<br />';
				$report->items[$key]['pay_with_room'] = $value['pay_with_room'];
                
                if($value['pay_with_room']==1){
					$report->items[$key]['room'] += $value['amount_pay_with_room'];	
					$total_paid += $value['amount_pay_with_room'];	
				}
                
				foreach($payments as $k=>$pay)
                {
					if($pay['bill_id'] == $value['id'])
                    {
						if($pay['type_dps']=='karaoke')
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
                            else{
								$report->items[$key][$pay['payment_type_id'].'_'.$pay['currency_id']] = $pay['total'];	
							}
                            //System::debug($report->items[$key]);
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
			//if(User::is_admin())
                //System::Debug($report->items);
			$report->currencies = $all_currencies;
			$report->payment_types = $all_payments;
			$report->credit_card = $credit_card;
            if(User::is_admin())
            {
                //System::debug($report->items);
            }
			$this->print_all_pages($report);
		}
		else
		{
		    $_REQUEST['date_from'] = date('d/m/Y');
            $_REQUEST['date_to'] = date('d/m/Y');
			
			$view_all = true;
			if(!User::can_view(false,ANY_CATEGORY) and User::can_view_detail(false,ANY_CATEGORY)){
				$view_all = false;
			}
			$karaoke_lists = DB::fetch_all('select karaoke.* from karaoke');
			$shift_lists = array();
			/*if(Session::get('karaoke_id')){
				$karaoke_id = Session::get('karaoke_id');		
			}else{
				$karaoke_id = DB::fetch('select min(id) as id from karaoke where 1>0 and portal_id=\''.PORTAL_ID.'\'','id');
			}
			if(Url::get('karaoke_id')){
				$karaoke_id = Url::get('karaoke_id');	
			}*/
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
			$users = DB::fetch_all('select account.id,party.full_name from account INNER JOIN party on party.user_id = account.id AND party.type=\'USER\' WHERE account.type=\'USER\' AND party.description_1=\'Nhà hàng\' ORDER BY account.id');			
			$this->parse_layout('search',
				array(			
				'user_id_list'=>array(''=>Portal::language('all_user'))+String::get_list($users),	
				'view_all'=>$view_all,
				'karaoke_id' => URL::get('karaoke_id',''),
				'shift_list' =>$shift_list,
				'karaoke_lists' =>String::array2js($karaoke_lists),
				'karaokes' =>DB::select_all('karaoke',false), 
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
					'foc'=>0,
					'room'=>0,
					'bank'=>0,
                    'refund'=>0
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
                    
					'page_no'=>0,
					'total_page'=>0
				)+$this->map
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
			if($temp = $item['bank']){
				$this->group_function_params['bank'] += $temp;
			}
            if($temp = $item['refund']){
				$this->group_function_params['bank'] += $temp;
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
		$this->parse_layout('header',
			array(
				'hotel_address'=>$hotel_address,
				'hotel_name'=>$hotel_name,
                'to_date'=>$to_day,
                'from_date'=>$from_day,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)+$this->map
		);
		$layout = 'report';
		if(Url::get('revenue')=='min'){
			$layout = 'report_cash';
			$report->payment_types = DB::fetch_all('select payment_type.*,payment_type.name_'.Portal::language().' as name from payment_type where def_code =\'CASH\'');
		}
        
		$this->parse_layout($layout,array(
				'payment_types'=>$report->payment_types,
				'currencies'=>$report->currencies,
				'credit_card'=>$report->credit_card,
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
							payment.bill_id in ('.$bill_id.') 
                            AND payment.type=\'KARAOKE\'
						GROUP BY payment.payment_type_id,payment.currency_id,payment.bill_id
						,payment.currency_id,payment.type_dps,payment.credit_card_id
				');	
                
	}
	function get_vat_invoice_cond($from_code,$to_code){
		$sql = 'select 
					vat_bill.id as id
					,vat_bill.karaoke_reservation_id
				FROM 
					vat_bill
				WHERE SUBSTR(vat_bill.code, 6 ) >='.$from_code.' AND SUBSTR(vat_bill.code, 6 )<='.$to_code.'
                    AND vat_bill.department = \'KARAOKE\'
                    ';
		$vats = DB::fetch_all($sql);	
		$cond = '';
		foreach($vats as $v => $vat){
            if( strpos( $vat['karaoke_reservation_id'],',' ) )
            {
                $arr = explode(',',$vat['karaoke_reservation_id']);
                foreach($arr as $b_r_id)
                    $cond .= ($cond=='')?'karaoke_reservation.id='.$b_r_id:' OR karaoke_reservation.id='.$b_r_id;
            }
            else
            {
                $cond .= ($cond=='')?'karaoke_reservation.id='.$vat['karaoke_reservation_id']:' OR karaoke_reservation.id='.$vat['karaoke_reservation_id']; 
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