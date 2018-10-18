<?php
class BanquetOrderRevenueReportForm extends Form
{
	function BanquetOrderRevenueReportForm()
	{
		Form::Form('BanquetOrderRevenueReportForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        if(User::is_admin(false,ANY_CATEGORY))
        {
            $hi = DB::fetch_all('select * from party_reservation where id = 1083');
            //System::Debug($hi);
        }
	}
	function draw()
	{
        $this->map = array();
		if(URL::get('do_search'))
		{
			$bars = DB::fetch_all('select * from party_room');
			$bar_ids = '';
            $bar_name = '';
			foreach($bars as $k => $bar){
				if(Url::get('bar_id_'.$k)){
					$bar_ids .= ($bar_ids=='')?$k:(','.$k);	
                    $bar_name .= ($bar_name=='')?$bar['name']:(', '.$bar['name']);
				}
			}
            //System::debug($bar_ids);
            $_REQUEST['bar_name'] = $bar_name;
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
			$shift_lists = DB::fetch_all('select bar_shift.* from bar_shift order by id ASC');
			$time_from = Date_Time::to_time($from_day);
			$time_to = (Date_Time::to_time($to_day));//+24*3600
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
    				.(URL::get('bar_id')?' and party_reservation.party_room_id = '.URL::get('bar_id').'':'') 
    				.(URL::get('user_id')?' and party_reservation.lastest_edited_user_id = \''.URL::get('user_id').'\'':'') 
    				.' and party_reservation.checkout_time>='.($time_from+$shift_from).' and party_reservation.checkout_time<'.($time_to+$shift_to).''
   				     //.((URL::get('revenue')=='min')?' and party_reservation.total<200000':((URL::get('revenue')=='max')?' and party_reservation.total>=200000':'')).' and party_reservation.status=\'CHECKOUT\' '
                    .((URL::get('revenue')=='min')?' and party_reservation.total<200000':((URL::get('revenue')=='max')?' and party_reservation.total>=200000':'')).''
    				;
    			if(Url::get('customer_name')){
    				$cond .= ' AND party_reservation.full_name LIKE \'%'.Url::get('customer_name').'%\'';
    			}
			}
			if(User::can_admin(false,ANY_CATEGORY)){
				$cond .= Url::get('hotel_id')?' and party_reservation.portal_id = \''.Url::get('hotel_id').'\'':'';
			}else{
				$cond .= ' and party_reservation.portal_id = \''.PORTAL_ID.'\'';	
			}
            //System::debug($cond);
			//echo $cond;
			$sql = '
				SELECT * FROM (
					SELECT
						party_reservation.id
						,party_reservation.total
                        ,party_reservation.party_type
						,party_reservation.lastest_edited_user_id as receptionist_id
						,party_reservation.full_name as customer_name 
						,party_reservation.EXTRA_SERVICE_RATE
                        ,party_reservation.vat
                        ,party_reservation.checkout_time as time_out
						,party_reservation.note
						,party_reservation_room.party_room_id
                        ,party_room.name as table_name
						,(deposit_1 + deposit_2 + deposit_3 + deposit_4) as deposit
						,0 as paid
						,0 as foc
						
						,ROW_NUMBER() OVER(ORDER BY party_reservation.id ) as rownumber
					FROM 
						party_reservation 
                        inner join party_reservation_room on party_reservation_room.party_reservation_id = party_reservation.id
                        inner join party_room on party_room.id = party_reservation_room.party_room_id
					WHERE 
						'.$cond.' AND party_reservation_room.party_room_id in ('.$bar_ids.')
					ORDER BY
						party_reservation.id
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
			//System::Debug($report->items);
			foreach($report->items as $key=>$value)
			{
				$total_paid = 0; $total_debit = 0;
				$report->items[$key]['room'] = 0;
				$report->items[$key]['bank'] = 0;
                $report->items[$key]['refund'] = 0;
				//$report->items[$key]['note'].= $value['room_name']?'Room name: '.$value['room_name']:'';
                //echo $value['pay_with_room'].'<br />';
				//if($value['pay_with_room']==1){
//					$report->items[$key]['room'] += $value['total'];	
//					$total_paid += $value['total'];	
//				}
				foreach($payments as $k=>$pay){
					if($pay['bill_id'] == $value['id']){
						if($pay['type_dps']=='BANQUET'){
							//$report->items[$key]['deposit'] += $pay['total_vnd'];
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
			//System::Debug($report->items);
			$report->currencies = $all_currencies;
			$report->payment_types = $all_payments;
			$report->credit_card = $credit_card;
            if(User::is_admin())
            {
                //System::debug($report);
            }
			$this->print_all_pages($report);
		}
		else
		{
		    $_REQUEST['date_from'] = date('d/m/Y');
            $_REQUEST['date_to'] = date('d/m/Y');
			$date_arr = array();
			for($i=1;$i<=31;$i++){
				$date_arr[strlen($i)<2?'0'.($i):$i] = strlen($i)<2?'0'.($i):$i;
			}
			$view_all = true;
			if(!User::can_view(false,ANY_CATEGORY) and User::can_view_detail(false,ANY_CATEGORY)){
				$_REQUEST['from_day'] = date('d');
				$view_all = false;
			}
			$bar_lists = DB::fetch_all('select party_room.* from party_room');
			$shift_lists = array();
			/*if(Session::get('bar_id')){
				$bar_id = Session::get('bar_id');		
			}else{
				$bar_id = DB::fetch('select min(id) as id from bar where 1>0 and portal_id=\''.PORTAL_ID.'\'','id');
			}
			if(Url::get('bar_id')){
				$bar_id = Url::get('bar_id');	
			}*/
			// Lấy danh sách các ca làm vc
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
			//Lấy ra các account
            //System::debug($bar_lists);
			/** 7211 */
			$user_privigele=DB::fetch('select group_privilege_id from account_privilege_group where account_id=\''.User::id().'\'');
            if(!$user_privigele or $user_privigele==3 or $user_privigele==4){
                
                $users = DB::fetch_all('
				SELECT
					account.id,account.id as name,account.is_active
				FROM
					account
                    INNER JOIN party on party.user_id=account.id
				WHERE
					party.type=\'USER\'
			');
            }else{
                $users = DB::fetch_all('
				SELECT
					account.id,account.id as name,account.is_active
				FROM
					account
                    INNER JOIN party on party.user_id=account.id
                    INNER JOIN account_privilege_group ON account_privilege_group.account_id=account.id
				WHERE
					party.type=\'USER\'
					AND account_privilege_group.group_privilege_id is not null and account_privilege_group.group_privilege_id !=3 and account_privilege_group.group_privilege_id !=4
			');
            }
            /** 7211 end*/			
			$this->parse_layout('search',
				array(			
				'user_id_list'=>array(''=>Portal::language('all_user'))+String::get_list($users),	
				'view_all'=>$view_all,
				'users' =>$users,
				'bar_id' => URL::get('bar_id',''),
				'shift_list' =>$shift_list,
				'bar_lists' =>String::array2js($bar_lists),
				'bars' =>DB::select_all('party_room',false), 
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
                    'to_date'=>$to_day,
                    'from_date'=>$from_day,
					'hotel_address'=>$hotel_address,
					'hotel_name'=>$hotel_name,
					'page_no'=>1,
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
							payment.bill_id in ('.$bill_id.') AND payment.type=\'BANQUET\'
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