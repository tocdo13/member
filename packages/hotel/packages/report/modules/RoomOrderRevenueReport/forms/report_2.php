<?php
class RoomOrderRevenueReportForm extends Form
{
	function RoomOrderRevenueReportForm()
	{
		Form::Form('RoomOrderRevenueReportForm');
         $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
         $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
         $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
	}
	function draw()
	{
        $this->map = array();
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):32;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
       
		
        if(URL::get('do_search'))
		{
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
			require_once 'packages/hotel/packages/reception/modules/includes/get_reservation.php';
            $shift_lists = DB::fetch_all('select reception_shift.* from reception_shift order by id ASC');
		
			$from_code = 0;
			$to_code = 0;$cond_code ='';
			$check=false;
			if(Url::get('from_code') && Url::get('to_code'))
            {
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
                $check = true;
			}
            $time_from = Date_Time::to_time(Url::get('date_from'));
		    $time_to = Date_Time::to_time(Url::get('date_to'));
           
			$cond = $this->cond = '1=1'
				.(URL::get('reservation_type_id')?' and reservation_room.reservation_type_id = '.URL::get('reservation_type_id').'':'');
			
			$cond2 = ' ';
            //echo $cond;die(2);
             //check điều kiện search theo ngày thagngs
			if(Url::get('search_time')){
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
					
					$shift_to = $this->calc_time(Url::get('end_time'))+59;
					$this->map['end_shift_time'] = Url::get('end_time'); 
					
				}
				else
				{
					$shift_to = $this->calc_time('23:59')+59;
					$this->map['end_shift_time'] = '23:59'; 
				}
				$cond .=' and reservation_room.time_in>='.($time_from+$shift_from).' and reservation_room.time_in<'.($time_to+$shift_to).'';
			}
			//end  check date
            
			if(User::can_admin(false,ANY_CATEGORY)){
				$cond .= Url::get('portal_id')?' and reservation.portal_id = \''.Url::get('portal_id').'\'':'';
                $cond2 = Url::get('portal_id')?' 1>0 and reservation.portal_id = \''.Url::get('portal_id').'\'':'';
			}else{
				$cond .= ' and reservation.portal_id = \''.PORTAL_ID.'\'';	
                $cond2 =' 1>0 and reservation.portal_id = \''.PORTAL_ID.'\'';	
			}
             //start: KID them de tim theo ma hoa don
           if(Url::get('search_invoice')){
				$from_bill = Url::get('from_bill');
				$to_bill = Url::get('to_bill');
				if(Url::get('from_bill') && Url::get('to_bill'))
				{
					$from_bill = Url::get('from_bill');
					$to_bill = Url::get('to_bill');
					if($from_bill>0 && $to_bill>0 && $to_bill>=$from_bill)
					{
						 $cond2 .= ' AND folio.id >='.$from_bill.' AND folio.id<='.$to_bill.'';
					}
				}else if(Url::get('from_bill') and !Url::get('to_bill')){
					$cond2 .= ' AND folio.id >='.$from_bill.' ';
				}else if(!Url::get('from_bill') and Url::get('to_bill')){
					$cond2 .= ' AND folio.id<='.$to_bill.'';
				}
            }
            
			//end: KID them de tim theo ma hoa don	
           
			$cond3 = '';
			
            $cond3 = (URL::get('user_id')?' and UPPER(reservation_room.checked_out_user_id) = \''.strtoupper(URL::get('user_id')).'\'':'');
				if(Url::get('customer') and Url::get('customer') !=''){
					$cond3 .=' and customer.name like \'%'.Url::get('customer').'%\' ';
				}
			if($cond_code!='')
            { // Neu co dieu kien tim theo code roi thi ko lay dieu kien thoi gian nua
				$cond2 .= ' AND ('.$cond_code.')';
			}
            elseif(Url::get('search_time'))
            {
				$cond2 .= ' AND folio.create_time>='.($time_from+$shift_from).' AND folio.create_time<'.($time_to+$shift_to).'';
			} 
			// and reservation_room.status=\'CHECKOUT\'
			//.(URL::get('user_id')?' and reservation_room.user_id = \''.URL::sget('user_id').'\'':'') 
			
			$all_payments = DB::fetch_all('select payment_type.*,payment_type.name_'.Portal::language().' as name from payment_type where def_code is not null AND def_code<>\'ROOM CHARGE\'');
			
            $all_currencies = DB::fetch_all('select * from currency where allow_payment=1');
			
            $credit_card = DB::fetch_all('select * from credit_card');
			
			$report = new Report;
			
            $sql= ' select reservation_room.* from reservation_room
						INNER JOIN reservation ON reservation.id = reservation_room.reservation_id
					WHERE '.$cond.' AND reservation_room.status !=\'CANCEL\' ';
			
            $reservations = DB::fetch_all($sql);
			
            $folios = $this->get_foios($cond2.$cond3);// Lấy ra các hóa đơn đã tạo
            //System::debug($folios);
			if(empty($folios)){// Neu không có bản ghi nào
				$this->parse_layout('header',
					get_time_parameters()+
					array(
                        'to_date'=>Url::get('date_to'),
                        'from_date'=>Url::get('date_from'),
                        'from_bill'=>Url::get('from_bill'),
                        'to_bill'=>Url::get('to_bill'), 
						'page_no'=>0,
						'total_page'=>0,
						'hotel_name'=>Url::get('portal_id')?DB::fetch('SELECT id,name_1 FROM party WHERE user_id = \''.PORTAL_ID.'\'','name_1'):HOTEL_NAME
					)+$this->map
				);
				$this->parse_layout('no_record');
				
                $this->parse_layout('footer',array(
					'page_no'=>0,
					'total_page'=>0
				)+$this->map);	
			}
            else
            {
				$payments = $this->get_payments($cond2);//$payments = $this->get_payments($time_from,$time_to);//$cond2
                //System::debug($payments);
                $traveller_folio = $this->get_item_folio(); 
                //System::debug($traveller_folio);
                $code_vats = $this->get_vat_invoice($cond2);// Lay code vat de gan vao folio
			   
				$items = array();
				if(!isset($shift_from) && !isset($shift_to)){
					$shift_from = 0;
					$shift_to = 86400;	
				}
                //System::debug($folios);
                
				for($i=$time_from; $i<=$time_to;$i=$i+86400)
                {
					foreach($folios as $key => $folio)
                    {
                        //xet dieu kien search theo ca || Hoac neu chon tim kiem theo id thi khong xet theo ca nua
						if($folio['time']>=($i+$shift_from) || ($folio['time']<=($i+$shift_to)) || $check==true )
                        {
                            
							$total_paid = 0; 
                            $total_foc = 0; 
                            $total_debit = 0;
							
							$items[$key] = $folio;	
							$items[$key]['debit'] = '';
							$items[$key]['deposit'] = 0;
 
                            foreach($traveller_folio as $t=>$traveller)
                            {
								if(($traveller['type'] == 'DEPOSIT' || $traveller['type'] == 'DEPOSIT_GROUP') && $traveller['folio_id']==$key)
                                {
									$items[$key]['deposit'] += $traveller['total_amount'];			
								}
								if($traveller['type'] == 'ROOM' && $traveller['folio_id']==$key && isset($reservations[$folio['rr_id']]) && $reservations[$folio['rr_id']]['foc']!='' && $reservations[$folio['rr_id']]['foc_all']==0){
									$items[$key]['foc'] = $traveller_folio['ROOM_'.$folio['id']]['total_amount'];  								
									$total_foc += $items[$key]['foc'];
								}
							}
                            
                            foreach($payments as $k => $pay)
                            {
                                
								if($pay['folio_id']==$folio['id'])
                                {
									$item_amount = 0;
                                    $items[$key]['currency_id'] = $pay['currency_id'];
									if(HOTEL_CURRENCY == 'USD')
                                    {
										$item_amount = $pay['total'];
									}
                                    else
                                    {
										$item_amount = $pay['total_vnd'];
                                        if($pay['currency_id'] =='USD')
                                            $item_amount = $pay['total'];
                                        else
                                            $item_amount = $pay['total_vnd'];
									}
                                    
									if($pay['payment_type_id']=='DEBIT')
                                    {
										$total_debit = $item_amount;
									}
                                    else 
                                    if($pay['payment_type_id']=='CREDIT_CARD')
                                    {
										$items[$key][$pay['payment_type_id'].'_'.$pay['credit_card_id'].'_'.$pay['currency_id']] = $pay['total'];
									}
                                    else 
                                    if($pay['payment_type_id']=='FOC')
                                    {
										$items[$key][$pay['payment_type_id'].'_'.$pay['currency_id']] = $item_amount;
										$items[$key]['foc']=$item_amount;	
									}
                                    else
                                    {
										$items[$key][$pay['payment_type_id'].'_'.$pay['currency_id']] = $item_amount;
									}
                                    
									if(HOTEL_CURRENCY == 'USD')
                                    {
										$item_amount = $pay['total']/$pay['exchange_rate'];
									}
                                    
                                    if($pay['payment_type_id']=='REFUND')
                                        $total_paid -= $item_amount*$pay['exchange_rate'];
                                    else
                                        $total_paid += $item_amount*$pay['exchange_rate'];
								}
							}
                            
                            $items[$key]['debit'] = ($total_debit + round($folio['total'] - $total_paid,2) );//- $items[$key]['deposit']
    
							$items[$key]['total'] =  $folio['total'];// + $folios[$key]['deposit'] ;// $folios[$key]['foc'];
                            
							if(isset($code_vats[$folio['id']])){
								$items[$key]['vat_code'] = $code_vats[$folio['id']]['code'];
							}else{
								$items[$key]['vat_code'] = '';
							}
							//$paid_room_others = $this->get_add_paid($rt_id,Url::get('folio_id'));
						}
					}
				}
                //System::debug($items);
				ksort($items);	
				$report = new Report;
				$i=1;    
				foreach($items as $f=>$folio)
                {
					$items[$f]['stt'] = $i; $i++;
					$items[$f]['code'] = $f;
					$items[$f]['group']=0;
					if($folio['rr_id']=='')
                    {
						$items[$f]['group']=1;
						$items[$f]['guest_name'] = $folio['customer_name'];	
					}else
                    {
						$items[$f]['guest_name'] = $folio['full_name'];
					}
					$items[$f]['deposit'] = System::display_number($folio['deposit']);
				}

				$report->items = $items;

				$report->currencies = $all_currencies;
                
				$report->payment_types = $all_payments;
				$report->credit_card = $credit_card;
               
               $this->print_all_pages($report);
              
			}
		}
		else
		{
		    $_REQUEST['date_from'] = date('d/m/Y');
            $_REQUEST['date_to'] = date('d/m/Y');
			$view_all = true;
			if(!User::can_view(false,ANY_CATEGORY) and User::can_view_detail(false,ANY_CATEGORY)){
				
				$view_all = false;
			}
			$users = DB::fetch_all('
				SELECT
					party.user_id as id,party.user_id as name
				FROM
					party
					INNER JOIN account ON party.user_id = account.id
				WHERE
					party.type=\'USER\'
					AND account.is_active = 1
				ORDER BY 
					party.user_id ASC
			');
			$shift_lists = DB::fetch_all('select reception_shift.* from reception_shift order by id ASC');
			
            $shift_list = '<option value="0">--------Select shift-------</option>';
			
            $portals = Portal::get_portal_list();
			foreach($shift_lists as $k=>$shift){
				if(isset($portals[$shift['portal_id']]['shifts'])){
					$portals[$shift['portal_id']]['shifts'][$k] = $shift; 
				}else{
					$portals[$shift['portal_id']]['shifts'][$k] = $shift;	
				}
				$shift_list .= '<option value="'.$shift['id'].'">'.$shift['name'].'   : '.$shift['brief_start_time'].'h - '.$shift['brief_end_time'].'h</option>';	
			}
			$this->parse_layout('search',$this->map+
				array(
				'portal_id_list'=>array(''=>Portal::language('all')) + String::get_list($portals),
				'user_id_list'=>array(''=>Portal::language('all'))+String::get_list($users),
				'view_all'=>$view_all,
				'shift_lists'=>$shift_list,
				'reservation_type_id_list'=>array(''=>Portal::language('All'))+String::get_list(DB::select_all('reservation_type')),
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
					'foc'=>0,
                    'CASH_VND'=>0,
                    'REFUND_VND'=>0,
                    'REFUND_USD'=>0,
                    'CREDIT_CARD_1_VND'=>0,
                    'CREDIT_CARD_1_USD'=>0,
                    'BANK_VND'=>0,
                    'CREDIT_CARD_2_VND'=>0,
                    'CASH_USD'=>0,
				);
			$this->map['real_total_page']=count($pages);
            $this->map['real_page_no'] = 1;
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page);
                $this->map['real_page_no'] ++;                
			}
		}
		else
		{
			$this->parse_layout('header',
			get_time_parameters()+
				array(
                    'to_date'=>$to_day,
                    'from_date'=>$from_day,
                    'from_bill'=>Url::get('from_bill'),
                    'to_bill'=>Url::get('to_bill'), 
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
		$last_group_function_params = $this->group_function_params;
		foreach($items as $item)
		{
			foreach($report->payment_types as $key =>$payment){
				if($payment['def_code']!='FOC' && $payment['def_code']!='DEBIT')
                {
					if($payment['def_code']=='CREDIT_CARD')
                    {
						foreach($report->credit_card as $key =>$credit_card)
                        {
                            $this->group_function_params[''.$payment['def_code'].'_'.$credit_card['id'].'_'.$currency['id'].'']=0;							
                            foreach($report->currencies as $key =>$currency)
                            {
								if(isset($item[''.$payment['def_code'].'_'.$credit_card['id'].'_'.$currency['id'].'']))
                                {
                                    if($temp = $item[''.$payment['def_code'].'_'.$credit_card['id'].'_'.$currency['id'].''])
                                    {
									   $this->group_function_params[''.$payment['def_code'].'_'.$credit_card['id'].'_'.$currency['id'].''] += $temp;
								    }
                                }
							}	
						}
					}
					foreach($report->currencies as $key => $currency)
                    {
						if(isset($item[''.$payment['def_code'].'_'.$currency['id'].'']))
                        {
                            if($temp = $item[''.$payment['def_code'].'_'.$currency['id'].''])
							$this->group_function_params[''.$payment['def_code'].'_'.$currency['id'].''] += $temp;
						}
					}	
				}
			}
            
            
			if($temp = $item['total'])
            {
				$this->group_function_params['total'] += $temp;
			}
			if($temp = $item['debit'])
            {
				$this->group_function_params['debit'] += $temp;
			}
			if($temp = $item['foc'])
            {
				$this->group_function_params['foc'] += $temp;
			}
			if($temp = System::calculate_number($item['deposit']))
            {
				$this->group_function_params['deposit'] += $temp;
			}
		}
        if($page_no>=$this->map['start_page'])
		{
    		$this->parse_layout('header',
    			array(
                    'to_date'=>$to_day,
                    'from_date'=>$from_day,
                    'from_bill'=>Url::get('from_bill'),
                    'to_bill'=>Url::get('to_bill'), 
    				'page_no'=>$page_no,
    				'total_page'=>$total_page,
    			)+$this->map
    		);
 
    		$this->parse_layout('report',array(
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
    			'total_page'=>$total_page
    		)+$this->map);
      }
	}
    
    
    
    
    
	function get_payments($cond)
    {
        return $payments = DB::fetch_all('
						SELECT 
							(payment.payment_type_id || \'_\' || payment.credit_card_id || \'_\' || payment.currency_id || \'_\' || payment.bill_id || \'_\' || payment.folio_id) as id
							,SUM(amount) as total
							,SUM(amount*exchange_rate) as total_vnd
							,CONCAT(payment.payment_type_id
                            ,CONCAT(\'_\',payment.currency_id)) as name
							,payment.bill_id
							,payment.folio_id
							,payment.payment_type_id
							,payment.credit_card_id
							,payment.currency_id
							,payment.type_dps
							,payment.exchange_rate
						FROM payment
							inner join folio ON folio.id = payment.folio_id
							INNER JOIN reservation ON reservation.id = folio.reservation_id
						WHERE 
							(payment.type_dps is null or payment.type_dps =\'(auto)\') AND '.$cond.'
						GROUP BY payment.payment_type_id,payment.currency_id,payment.bill_id,payment.folio_id
						,payment.currency_id,payment.type_dps,payment.credit_card_id,payment.exchange_rate
				');	
	}
	function get_foios($cond=' 1>0')
    {
       
		$sql = '  SELECT
						folio.id as id
						,folio.total
						,folio.reservation_id as r_id
						,folio.reservation_room_id as rr_id
						,folio.reservation_traveller_id as rt_id
						,folio.customer_id
						,0 as foc
						,0 as deposit  
						,(customer.code || \' : \'|| customer.name) as customer_name
						,reservation_traveller.id as traveller_id
						,(\'KHLE: \' || traveller.first_name || \' \' || traveller.last_name) as full_name
						,room.name as room_name
						,folio.create_time as time,
						CASE WHEN (reservation_room.checked_out_user_id is null)
							THEN \'Khách chưa out\'
							ELSE reservation_room.checked_out_user_id
						END	as user_id
					FROM
						folio
						INNER JOIN reservation ON reservation.id = folio.reservation_id 
						LEFT OUTER JOIN reservation_room ON reservation_room.id = folio.reservation_room_id
						LEFT OUTER JOIN reservation_traveller ON folio.reservation_traveller_id = reservation_traveller.id
						LEFT OUTER JOIN traveller ON traveller.id = reservation_traveller.traveller_id
						LEFT OUTER JOIN room ON room.id = reservation_room.room_id
						LEFT OUTER JOIN customer ON customer.id = reservation.customer_id
					WHERE 
						'.$cond.' AND reservation_room.status !=\'CANCEL\'
					union all
                    SELECT
						folio.id as id
						,folio.total
						,folio.reservation_id as r_id
						,folio.reservation_room_id as rr_id
						,folio.reservation_traveller_id as rt_id
						,folio.customer_id
						,0 as foc
						,0 as deposit  
						,(customer.code || \' : \'|| customer.name) as customer_name
						,reservation_traveller.id as traveller_id
						,(\'KHLE: \' || traveller.first_name || \' \' || traveller.last_name) as full_name
						,room.name as room_name
						,folio.create_time as time,
						CASE WHEN (reservation_room.checked_out_user_id is null)
							THEN \'Khách chưa out\'
							ELSE reservation_room.checked_out_user_id
						END	as user_id
					FROM
						folio
						INNER JOIN reservation ON reservation.id = folio.reservation_id 
						LEFT OUTER JOIN reservation_room ON reservation_room.reservation_id = reservation.id
						LEFT OUTER JOIN reservation_traveller ON folio.reservation_traveller_id = reservation_traveller.id
						LEFT OUTER JOIN traveller ON traveller.id = reservation_traveller.traveller_id
						LEFT OUTER JOIN room ON room.id = reservation_room.room_id
						LEFT OUTER JOIN customer ON customer.id = reservation.customer_id
					WHERE 
						'.$cond.' AND reservation_room.status !=\'CANCEL\'  and folio.reservation_traveller_id is NULL or
folio.id in (
select folio.id from folio where folio.reservation_room_id is NULL and folio.reservation_traveller_id is not NULL
 AND '.$cond.'
)
                    
			';
            
		return DB::fetch_all($sql);
	}
	function get_item_folio($cond=' 1>0')
    {
		$sql = 'select 
					(rt.type || \'_\' || rt.folio_id ) as id
					,sum(rt.amount + (rt.amount*rt.service_rate*0.01) + ((rt.amount + (rt.amount*rt.service_rate*0.01))*rt.tax_rate*0.01)) as total_amount
					,rt.reservation_traveller_id as rt_id
					,rt.type
					,rt.folio_id
				FROM traveller_folio rt
					left outer join reservation ON rt.reservation_id = reservation.id
					left outer join reservation_room ON reservation_room.id = rt.reservation_room_id
				WHERE '.$cond.' AND rt.add_payment!=1
                GROUP BY
                rt.type
                ,rt.folio_id
                ,rt.reservation_traveller_id
			';
		return DB::fetch_all($sql);
	}
	function get_add_paid($traveller_id,$folio_id)
    {// Lấy ra tổng tiền thanh toán cho phòng khác
		$add_rr = array();
		$adds = DB::fetch_all('SELECT 
						traveller_folio.id
						,traveller_folio.reservation_room_id as rr_id
						,traveller_folio.amount as add_amount
						,room.name as room_name
						,traveller_folio.reservation_traveller_id as rt_id
						,traveller_folio.tax_rate
						,traveller_folio.service_rate
						,traveller_folio.type
						,reservation_room.time_in
						,reservation_room.time_out
					FROM traveller_folio 
						INNER JOIN reservation_room ON reservation_room.id = traveller_folio.reservation_room_id 
						INNER JOIN room ON room.id = reservation_room.room_id 
					WHERE reservation_traveller_id = '.$traveller_id.' AND add_payment=1 AND folio_id='.$folio_id.'');
		if(!empty($adds))
        {
			$total_room = 0; $total_discount = 0; $total_add = 0; $total_deposit=0;
			$add_rr = array();
			foreach($adds as $k => $ad)
            {
				if(!isset($total_room_add[$ad['rr_id']]))
                {
					$total_room_add[$ad['rr_id']] = 0;
					$total_discount_add[$ad['rr_id']]= 0;
					$total_deposit_add[$ad['rr_id']]= 0;
					$total_other_add[$ad['rr_id']] = 0;
				}
			}
			foreach($adds as $a => $add)
            {
				if($add['type'] == 'ROOM'){
					$total_room_add[$add['rr_id']] += $add['add_amount'];
				}
                else if($add['type'] == 'DISCOUNT')
                {
					$total_discount_add[$add['rr_id']] += $add['add_amount'];	
				}
                else if($add['type'] == 'DEPOSIT')
                {
					$total_deposit_add[$add['rr_id']] += $add['add_amount'];	
				}
                else
                {
					$service_add = ($add['add_amount']*$add['service_rate']/100);
					$tax_add = ($add['add_amount'] + $service_add)*$add['tax_rate']/100;
					$total_other_add[$add['rr_id']] += $add['add_amount'] + $service_add + $tax_add; 	
				}
				if(isset($add_rr[$add['rr_id']]))
                {
					$add_rr[$add['rr_id']]['total_room'] = $total_room_add[$add['rr_id']];
					$add_rr[$add['rr_id']]['total_discount'] = $total_discount_add[$add['rr_id']];
					$add_rr[$add['rr_id']]['total_deposit'] = $total_deposit_add[$add['rr_id']];
					$add_rr[$add['rr_id']]['total_other'] = $total_other_add[$add['rr_id']];
				}
                else
                {
					$add_rr[$add['rr_id']] = $add;
					$add_rr[$add['rr_id']]['id'] = $add['rr_id'];
					$add_rr[$add['rr_id']]['total_room'] = $total_room_add[$add['rr_id']];
					$add_rr[$add['rr_id']]['total_discount'] = $total_discount_add[$add['rr_id']];
					$add_rr[$add['rr_id']]['total_deposit'] = $total_deposit_add[$add['rr_id']];
					$add_rr[$add['rr_id']]['total_other'] = $total_other_add[$add['rr_id']];
				}
				$add_rr[$add['rr_id']]['time_in'] = date('d/m',$add['time_in']);
				$add_rr[$add['rr_id']]['time_out'] = date('d/m',$add['time_out']);
			}
			foreach($add_rr as $b => $arr)
            {
					$reservation_this = DB::fetch('SELECT id,foc,foc_all from reservation_room where id='.$arr['rr_id'].'');
					if($reservation_this['foc']!='')
                    {
						$arr['total_room'] = 0;
					}
					$add_rr[$b]['id'] = $arr['rr_id'];
					$add_rr[$b]['decription'] = Portal::language('add_payment_for_room').' '.$arr['room_name'].'('.$arr['time_in'].' - '.$arr['time_out'].')';
					$total_room_aftex_discount = $arr['total_room'] - $arr['total_discount'];
					$service_aftex_discount = $total_room_aftex_discount * $arr['service_rate']/100;
					$tax_aftex_discount = ($total_room_aftex_discount + $service_aftex_discount)*$arr['tax_rate']/100;
					$add_rr[$b]['add_amount'] = System::display_number($total_room_aftex_discount + $service_aftex_discount + $tax_aftex_discount + $arr['total_other'] - $arr['total_deposit']);
					if($reservation_this['foc_all']==1)
                    {
						$add_rr[$b]['add_amount'] = 0;
					}
			}
		}
		return $add_rr;
	}
	function get_vat_invoice($cond)
    {
		$sql = 'select 
					vat_bill.id as vat_id
					,vat_bill.code
					,vat_bill.folio as id
				FROM 
					vat_bill
					inner join folio ON folio.id = vat_bill.folio
					INNER JOIN reservation ON reservation.id = folio.reservation_id 
				WHERE '.$cond.'
                AND vat_bill.department = \'RECEPTION\'
        ';
		$vat_codes = DB::fetch_all($sql);	
		return $vat_codes;
	}
    function get_vat_invoice_cond($from_code,$to_code){
		$sql = 'select 
					vat_bill.id as id
					,vat_bill.folio
				FROM 
					vat_bill
				WHERE SUBSTR(vat_bill.code, 6 ) >='.$from_code.' AND SUBSTR(vat_bill.code, 6 )<='.$to_code.'
                AND vat_bill.department = \'RECEPTION\'
        ';
		$vats = DB::fetch_all($sql);
		$cond = '';
		foreach($vats as $v => $vat)
        {
			if($vat['folio'])
            {
				$cond .= ($cond=='')?'folio.id='.$vat['folio']:' OR folio.id='.$vat['folio'];	
			}
		} 
		return $cond;
	}
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
   
}

?>