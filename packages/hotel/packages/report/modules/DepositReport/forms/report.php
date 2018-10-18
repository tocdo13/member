<?php
class DepositReportForm extends Form
{
	function DepositReportForm()
	{
		Form::Form('DepositReportForm');
         $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
          $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
          $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw()
	{
		if(URL::get('do_search'))
		{
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
			require_once 'packages/hotel/packages/reception/modules/includes/get_reservation.php';
		
            
            $from_day= Date_Time::to_time(Url::get('date_from'));
            $to_day =Date_Time::to_time(Url::get('date_to'));
             if(URL::get('hour_from')){
			 $this->map['start_shift_time'] = Url::get('hour_from');
                $hour_from= explode(':',URL::get('hour_from'));
                
              $from_day = $from_day + ((int)$hour_from[0]*60+(int)$hour_from[1])*60;
            }
            if(URL::get('hour_to')){
				$this->map['end_shift_time'] = Url::get('hour_to'); 
                $hour_to= explode(':', URL::get('hour_to'));
                $to_day = $to_day + ((int)$hour_to[0]*60+(int)$hour_to[1])*60;
            }
            
			$this->line_per_page = URL::get('line_per_page',999);
		//	$cond  = '  '
//				.(URL::get('user_id')?' and payment.user_id = \''.URL::sget('user_id').'\'':'') 
//				.' and payment.time>='.Date_Time::to_time($from_day).' and payment.time <= '.( 86399 + Date_Time::to_time($to_day) ).''
//			;
            
             $cond  = '  '
				.(URL::get('user_id')?' and payment.user_id = \''.URL::sget('user_id').'\'':'')
                .(URL::get('portal_id')?' and payment.portal_id = \''.URL::sget('portal_id').'\'':'') 
				.' and payment.time>='.$from_day.' and payment.time <= '.$to_day.'
                 '
			;
            
            //System::debug($cond);
			$sql = 'SELECT 	
                        payment.id, 
						payment.bill_id as order_id,
						to_char(FROM_UNIXTIME(payment.time), \'DD/MM/YYYY\') as deposit_date,
						payment.user_id as deposit_user_id,
						CASE 
							WHEN currency_id=\'VND\'
								THEN 
									payment.amount --/ COALESCE(payment.exchange_rate,1)
							ELSE 	0	
						END	 as deposit_vnd,
                        CASE 
							WHEN currency_id=\'USD\'
								THEN 
									payment.amount --* COALESCE(payment.exchange_rate,1)
							ELSE 	0	
						END	 as deposit_usd,
						payment.currency_id,
						room.name as room_name,
						customer.name as customer_name,
                        payment.type_dps,
                        payment.description as note,
                        payment.reservation_room_id,
                        reservation_room.reservation_id,
                        payment_type.name_'.Portal::language().' as payment_type_name,
                        payment.payment_type_id,
                        0 as deposit_BANK,
                        0 as deposit_CASH,
                        0 as deposit_CREDIT_CARD
					FROM 
                        payment
						inner join reservation_room ON payment.bill_id = reservation_room.id
						inner join reservation ON reservation.id = reservation_room.reservation_id
						left outer join customer on reservation.customer_id = customer.id
						left outer join room ON room.id = reservation_room.room_id
                        left join payment_type on payment.payment_type_id = payment_type.def_code
					WHERE 
                        type_dps = \'ROOM\'
                        '.$cond.'
                    ORDER BY
                        reservation.id
                        ';
                    
			$report->items = DB::fetch_all($sql);
            //System::debug($sql);
            //System::debug($report->items);
			$sql = 'SELECT 
                        payment.id as id,
						payment.bill_id as order_id,
						to_char(FROM_UNIXTIME(payment.time), \'DD/MM/YYYY\') as deposit_date,
						payment.user_id as deposit_user_id,
						CASE 
							WHEN currency_id=\'VND\'
								THEN 
									payment.amount --/ COALESCE(payment.exchange_rate,1)
							ELSE 	0	
						END	 as deposit_vnd,
                        CASE 
							WHEN currency_id=\'USD\'
								THEN 
									payment.amount --* COALESCE(payment.exchange_rate,1)
							ELSE 	0	
						END	 as deposit_usd,
						payment.currency_id,
						\' \' as room_name,
						customer.name as customer_name,
                        payment.type_dps,
                        payment.description as note,
                        payment.reservation_room_id,
                        payment.reservation_id,
                        payment_type.name_'.Portal::language().' as payment_type_name,
                        payment.payment_type_id,
                        0 as deposit_bank,
                        0 as deposit_cash,
                        0 as deposit_credit_card
					FROM 
                        payment
						inner join reservation ON payment.bill_id = reservation.id
						inner join customer on reservation.customer_id = customer.id
                        left join payment_type on payment.payment_type_id = payment_type.def_code
					WHERE 
                        type_dps = \'GROUP\'
                        '.$cond.'
                    ORDER BY
                        reservation.id
                        ';
			$report->items += DB::fetch_all($sql);
            
            
            
            //ksort($report->items);
            //System::debug($report->items);
            require_once 'packages/hotel/includes/php/hotel.php';
            //echo Hotel::get_reservation_room_revenue(267);
            foreach($report->items as $key=>$value)
            {
                if(isset($value['reservation_room_id']) && $value['type_dps'] != 'GROUP')
                {
                    $report->items[$key]['total_used'] = round(Hotel::get_reservation_room_revenue($value['reservation_room_id']));
                }
                else
                {
                    $report->items[$key]['total_used']='';
                }
               
                if($value['payment_type_id']=='BANK'){
                    $report->items[$key]['deposit_bank_vnd'] = $value['deposit_vnd'];
                    $report->items[$key]['deposit_bank_usd'] = $value['deposit_usd'];
                    }
                if($value['payment_type_id']=='CASH'){
                    //exit();
                    $report->items[$key]['deposit_cash_vnd'] = $value['deposit_vnd'];
                    $report->items[$key]['deposit_cash_usd'] = $value['deposit_usd'];
                    }
                if($value['payment_type_id']=='CREDIT_CARD')
                    {
                    $report->items[$key]['deposit_credit_card_vnd'] = $value['deposit_vnd'];
                    $report->items[$key]['deposit_credit_card_usd'] = $value['deposit_usd'];
                    }
            }
			$this->print_all_pages($report,$from_day,$to_day);
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
			$users = DB::fetch_all('
				SELECT
					party.user_id as id,party.user_id as name
				FROM
					party
					INNER JOIN account ON party.user_id = account.id
				WHERE
					party.type=\'USER\'
					AND account.is_active = 1
			');
			$this->parse_layout('search',
				array(				
				'portal_id_list'=>array(''=>Portal::language('all')) + String::get_list(Portal::get_portal_list()),
				'user_id_list'=>array(''=>Portal::language('all'))+String::get_list($users),
				'view_all'=>$view_all,
				'reservation_type_id_list'=>array(''=>Portal::language('All'))+String::get_list(DB::select_all('reservation_type')),
				)
			);	
		}			
	}
	function print_all_pages(&$report,$from_day,$to_day)
	{
		$count = 0;
		$total_page = 1;
		$pages = array();
        $stt = 1;
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
					'deposit'=>0,
                    'BANK'=>0,
                    'BANK_VND'=>0,
                    'BANK_USD'=>0,
                    'CASH'=>0,
                    'CASH_VND'=>0,
                    'CASH_USD'=>0,
                    'CREDIT_CARD'=>0,
                    'deposit_usd'=>0,
                    'CREDIT_CARD_USD'=>0,
                    'CREDIT_CARD_VND'=>0,
                    'deposit_vnd'=>0
                    
				);
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page);
			}
		}
		else
		{
			$this->parse_layout('header',
			get_time_parameters()+
				array(
                     'to_date'=>date('H:i-d/m/Y',$to_day),
                    'from_date'=>date('H:i-d/m/Y',$from_day),
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
		   if($temp = System::calculate_number($item['deposit_vnd']))
			{
				$this->group_function_params['deposit_vnd'] += $temp;
                switch($item['payment_type_id'])
                {
                    case 'BANK':
                        $this->group_function_params['BANK_VND'] += $temp;
                        break;
                    case 'CREDIT_CARD':
                        $this->group_function_params['CREDIT_CARD_VND'] += $temp;
                        break;
                    default:
                        $this->group_function_params['CASH_VND'] += $temp;
                        break;
                }
			}
            if($temp = System::calculate_number($item['deposit_usd']))
			{
				$this->group_function_params['deposit_usd'] += $temp;
                switch($item['payment_type_id'])
                {
                    case 'BANK':
                        $this->group_function_params['BANK_USD'] += $temp;
                        break;
                    case 'CREDIT_CARD':
                        $this->group_function_params['CREDIT_CARD_USD'] += $temp;
                        break;
                    default:
                        $this->group_function_params['CASH_USD'] += $temp;
                        break;
                }
			}
		}
		//$total = $this->group_function_params['pay_by_cash']+$fee_summary['pay_by_cash_fee']+$fee_summary['pay_by_cash_tax'];		
		$this->parse_layout('header',
			array(
				'to_date'=>$to_day,
                'from_date'=>$from_day,
                'page_no'=>$page_no,
				'total_page'=>$total_page,
			)+$this->map
		);
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
}
?>
