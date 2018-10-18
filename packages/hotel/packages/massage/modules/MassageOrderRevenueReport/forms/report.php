<?php
class MassageRevenueReportForm extends Form
{
	function MassageRevenueReportForm()
	{
		Form::Form('MassageRevenueReportForm');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
	}
	function draw()
	{
        $this->map = array();
        require_once 'packages/core/includes/utils/time_select.php';
        $this->line_per_page = Url::iget('line_per_page')?Url::iget('line_per_page'):32;
        $this->map['line_per_page'] = $this->line_per_page;
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        
        $rooms = DB::fetch_all('select id,name from massage_room order by name');
        
        $this->map['room_id_list'] = array(''=>Portal::language('all')) + String::get_list($rooms);
        
        
        $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
        $_REQUEST['to_date'] = $this->map['to_date'];
        //dau thang
        $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
        $_REQUEST['from_date'] = $this->map['from_date'];
        //KimTan: xem theo gio
        $this->map['hour_from'] = Url::get('hour_from')?Url::get('hour_from'):'00:00';
        $_REQUEST['hour_from'] = $this->map['hour_from'];
        $this->map['hour_to'] = Url::get('hour_to')?Url::get('hour_to'):'23:59';
        $_REQUEST['hour_to'] = $this->map['hour_to'];
        $arr_hour_from = explode(":",$this->map['hour_from']);
        $arr_hour_to = explode(":",$this->map['hour_to']);
        $date_from = Date_Time::to_time($this->map['from_date'])+$arr_hour_from[0]*3600+$arr_hour_from[1]*60;
        $date_to = Date_Time::to_time($this->map['to_date'])+$arr_hour_to[0]*3600+$arr_hour_to[1]*60+59;
        //END KimTan: xem theo gio
        $cond_new = '((massage_reservation_room.hotel_reservation_room_id is not null and 	massage_reservation_room.pay_with_room = \'1\') or massage_reservation_room.total_amount = 0 )';
        $cond = ' 1=1';
        $cond_pay = ' 1=1';
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
        if(Url::get('portal_id'))
        {
            $portal_id = Url::get('portal_id');
        }
        else
        {
            $portal_id = PORTAL_ID;
            $_REQUEST['portal_id'] = PORTAL_ID;                       
        }
        
        if($portal_id != 'ALL')
        {
            $cond_pay.=' AND payment.portal_id = \''.$portal_id.'\' ';
            $cond .= ' AND massage_reservation_room.portal_id = \''.$portal_id.'\' ';
        }
        
		if(Url::get('from_date'))
		{
			$cond_pay .= ' and payment.time>='.$date_from;
            $cond.= ' and massage_product_consumed.time_out >='.$date_from;
		}
		if(Url::get('to_date'))
		{
			$cond_pay .= ' and payment.time < '.$date_to;
            $cond.= ' and massage_product_consumed.time_out < '.$date_to;
		}
        if(Url::get('room_id'))
		{
			$cond .= ' and massage_product_consumed.room_id = '.Url::get('room_id');
		}
        //start: KID them de tim theo ma hoa don
        if(Url::get('from_code') && Url::get('to_code'))
        {
			$from_code = Url::get('from_code');
			$to_code = Url::get('to_code');
            if($from_code>0 && $to_code>0 && $to_code>=$from_code)
            {
		         $cond_pay .= ' AND payment.bill_id >='.$from_code.' AND payment.bill_id<='.$to_code.'';
                 $cond .= ' AND massage_product_consumed.reservation_room_id >='.$from_code.' AND massage_product_consumed.reservation_room_id<='.$to_code.'';
            }
        }
        
        /** 15/11/17 **/
         $sql = '
			SELECT 
				(payment.payment_type_id || \'_\' || payment.credit_card_id || \'_\' || payment.currency_id || \'_\' || payment.bill_id || \'_\' || payment.type_dps || \'_\' || massage_reservation_room.id) as id
				,SUM(amount) as total
				,SUM(amount*payment.exchange_rate) as total_vnd
				,CONCAT(payment.payment_type_id,CONCAT(\'_\',payment.currency_id)) as name
				,payment.bill_id
				,payment.payment_type_id
				,payment.credit_card_id
				,payment.currency_id
				,payment.type_dps
                ,massage_reservation_room.id as mrr_id
			FROM payment
				inner join massage_reservation_room on payment.bill_id = massage_reservation_room.id
			WHERE 
				'.$cond_pay.'
                AND payment.type=\'SPA\'
			GROUP BY payment.payment_type_id,payment.currency_id,payment.bill_id
			,payment.currency_id,payment.type_dps,payment.credit_card_id,massage_reservation_room.id
	       ';	
        
        /** 15/11/17 **/
        $payments = DB::fetch_all($sql);
        $bill_id = '0';
		foreach($payments as $key=>$value)
		{
			$bill_id .=','.$value['mrr_id'];	
		}
        
        $count_sql = 'select
                        massage_product_consumed.reservation_room_id as id,
                        count (massage_product_consumed.id) as num
                      from
                        massage_reservation_room
                        INNER JOIN massage_product_consumed ON massage_product_consumed.reservation_room_id = massage_reservation_room.id
                        --LEFT JOIN  traveller_folio on traveller_folio.reservation_room_id=massage_reservation_room.hotel_reservation_room_id
                      where
                        (massage_reservation_room.id in ('.$bill_id.') or '.$cond_new.')
                        and 
                        ('.$cond.')	
                      group by massage_product_consumed.reservation_room_id';
        //System::debug($count_sql);
        $this->count_items = DB::fetch_all($count_sql);
    	$sql = '
		SELECT  * FROM
		(
            SELECT 
                massage_product_consumed.id as id,
                reservation_room.reservation_id as mrr_id,
                massage_product_consumed.reservation_room_id,
                massage_product_consumed.price,
                massage_room.name as room_name,
                massage_product_consumed.quantity,
                massage_reservation_room.total_amount,
                massage_reservation_room.user_id,
                massage_reservation_room.note,
                massage_reservation_room.discount,
                massage_reservation_room.discount_amount,
                massage_reservation_room.tax,
                massage_reservation_room.service_rate,
                massage_reservation_room.net_price,
                massage_product_consumed.quantity * massage_product_consumed.price as amount,
                product.name_'.Portal::language().' as product_name,
                room.name as hotel_room_name,
                0 as deposit,
				0 as paid,
				0 as foc,
                concat(TRAVELLER.FIRST_NAME, concat(\' \',TRAVELLER.LAST_NAME))  as TRAVELLER_name,
                ROWNUM as rownumber,
                CASE
                WHEN massage_reservation_room.pay_with_room = 1
                THEN massage_reservation_room.amount_pay_with_room
                ELSE 0
                END as amount_pay_with_room 
            FROM 
                massage_product_consumed
				LEFT OUTER JOIN massage_reservation_room ON massage_reservation_room.id = massage_product_consumed.reservation_room_id
                LEFT JOIN  traveller_folio on traveller_folio.reservation_room_id=massage_reservation_room.hotel_reservation_room_id
				LEFT OUTER JOIN massage_room ON massage_room.id = massage_product_consumed.room_id
                LEFT OUTER JOIN reservation_room ON massage_reservation_room.hotel_reservation_room_id = reservation_room.id
                left outer join TRAVELLER on reservation_room.TRAVELLER_ID = TRAVELLER.ID
                LEFT OUTER JOIN room ON reservation_room.room_id = room.id
                INNER JOIN product ON product.id = massage_product_consumed.product_id
            WHERE 
                (massage_reservation_room.id in ('.$bill_id.') or '.$cond_new.')
                and 
                ('.$cond.')	
            ORDER BY 
                massage_product_consumed.reservation_room_id
		)	
		WHERE
            rownumber > '.(($this->map['start_page']-1)*$this->line_per_page).' AND rownumber<='.($this->map['no_of_page']*$this->line_per_page).'
        ';
        //system::Debug($sql);
		$report->items = DB::fetch_all($sql);
        //System::debug($report->items);
        $i = 1;
		$all_payments = DB::fetch_all('select payment_type.*,payment_type.name_'.Portal::language().' as name from payment_type where def_code is not null');
		$all_currencies = DB::fetch_all('select * from currency where allow_payment=1');
		$credit_card = DB::fetch_all('select * from credit_card');
        //if(User::is_admin())
        //System::Debug($payments);
		$discount_persent = array();
        foreach($report->items as $key=>$value)
		{
			$total_paid = 0; 
            $total_debit = 0;
			$report->items[$key]['room'] = 0;
			$report->items[$key]['bank'] = 0;
            $report->items[$key]['refund'] = 0;
            if(DISCOUNT_BEFORE_TAX==1)
            {
                if($value['net_price']==0)
                {
                    if(isset($discount_persent[$value['reservation_room_id']]))
                    {
                        $discount_persent[$value['reservation_room_id']]['total_discount_amount_persent']+=($value['amount']*$value['discount']/100);
                    }
                    else
                    {
                        $discount_persent[$value['reservation_room_id']]['total_discount_amount_persent']=($value['amount']*$value['discount']/100);
                    }
                }
                else
                {
                    $total_amount_net = ($value['amount']*100/(100+$value['tax']))*100/(100+$value['service_rate']);
                    $discount_amount = ($total_amount_net*$value['discount']/100);
                    //$report->items[$key]['discount_amount_persent'] = $discount_amount;
                    if(isset($discount_persent[$value['reservation_room_id']]))
                    {
                        $discount_persent[$value['reservation_room_id']]['total_discount_amount_persent']+=$discount_amount;
                    }
                    else
                    {
                        $discount_persent[$value['reservation_room_id']]['total_discount_amount_persent']=$discount_amount;
                    }
                }
            }
            else
            {
                if($value['net_price']==0)
                {
                    
                    if(isset($discount_persent[$value['reservation_room_id']]))
                    {
                        $discount_persent[$value['reservation_room_id']]['total_discount_amount_persent'] += ($value['amount']*100/(100+$value['tax']))*100/(100+$value['service_rate'])*$value['discount']/100;    
                    }
                    else
                    {
                        $discount_persent[$value['reservation_room_id']]['total_discount_amount_persent'] = ($value['amount']*100/(100+$value['tax']))*100/(100+$value['service_rate'])*$value['discount']/100;
                    }
                }
                else
                {
                    
                    if(isset($discount_persent[$value['reservation_room_id']]))
                    {
                        $discount_persent[$value['reservation_room_id']]['total_discount_amount_persent']+=$value['amount']*$value['discount']/100;
                    }
                    else
                    {
                        
                        $discount_persent[$value['reservation_room_id']]['total_discount_amount_persent']=$value['amount']*$value['discount']/100;
                    }
                }
            }
			$report->items[$key]['hotel_room_name'] = $value['hotel_room_name'];
            if($value['hotel_room_name'] != '')
            {
				$report->items[$key]['room'] += $value['amount_pay_with_room'];	
				$total_paid += $value['amount_pay_with_room'];	
			}
            //else
			//{
		    foreach($payments as $k=>$pay)
            {
				if($pay['bill_id'] == $value['reservation_room_id'])
                {
					if($pay['type_dps']=='SPA')
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
						if($pay['payment_type_id']=='FOC')
                        {
							$report->items[$key]['foc'] = $pay['total_vnd'];
						}
						$total_paid += $pay['total_vnd'];	
					}
				}
			}
			//}
			$report->items[$key]['debit'] = (int)($total_debit + round($value['total_amount'] - $total_paid));
			$report->items[$key]['total'] =  $value['total_amount'];
			$report->items[$key]['stt'] = $i++;
		}
        //System::debug($discount_persent);
        foreach($report->items as $key=>$value)
        {
            foreach($discount_persent as $k=>$v)
            {
                if($k==$value['reservation_room_id'])
                {
                    $report->items[$key]['total_discount_amount_persent']=$v['total_discount_amount_persent'];
                }
            }
        }
		$report->currencies = $all_currencies;
		$report->payment_types = $all_payments;
		$report->credit_card = $credit_card;
        //System::debug($report->payment_types);
        //System::debug($report->items);
        $this->print_all_pages($report);
        
	}
    
    function print_all_pages(&$report)
	{
		$count = 0;
		$total_page = 1;
		$pages = array();
        $reservation_room_id = '';
		foreach($report->items as $key=>$item)
		{
            if ($reservation_room_id != $item['reservation_room_id'])
            {
                //echo $reservation_room_id.'_'.$item['reservation_room_id'].'<br>';
                $count ++;
            }
            $reservation_room_id = $item['reservation_room_id'];
            if($count >= $this->line_per_page)
			{
				$count = 0;
				$total_page++;
			}
			$pages[$total_page][$key] = $item;
		}
		if(sizeof($pages)>0)
		{
			$this->group_function_params = array(
					'total'=>0,
                    'quantity'=>0,
                    'discount_amount'=>0,
                    'total_discount_amount_persent'=>0,
                    'amount'=>0,
					'deposit'=>0,
					'debit'=>0,
					'foc'=>0,
					'room'=>0,
					'bank'=>0,
                    'refund'=>0
				);
			foreach($report->payment_types as $key =>$payment)
            {
				if($payment['def_code']!='FOC' && $payment['def_code']!='DEBIT')
                {
					if($payment['def_code']=='CREDIT_CARD'){
						foreach($report->credit_card as $key =>$credit_card)
                        {
							foreach($report->currencies as $key =>$currency)
                            {
								$this->group_function_params += array(''.$payment['def_code'].'_'.$credit_card['id'].'_'.$currency['id'].''=>'');
							}
						}
					}
                    else
                    {
						foreach($report->currencies as $key => $currency)
                        {
							$this->group_function_params += array(''.$payment['def_code'].'_'.$currency['id'].''=>'');
						}	
					}
				}
			}
            
            //System::debug($this->group_function_params);
			foreach($pages as $page_no => $page)
			{
				$this->print_page($page, $report, $page_no,$total_page);
			}
		}
		else
		{
			$this->parse_layout('report',
				array(
					'page_no'=>0,
					'total_page'=>0,
                    'has_no_data'=>true
				)+$this->map
			);
		}
	}
	function print_page($items, &$report, $page_no,$total_page)
	{
	    //System::debug($items);
		$payment = array();
		$credit_card = 0;
		$total_currency = 0;
		$last_group_function_params = $this->group_function_params;
        $reservation_room_id = '';
		foreach($items as $item)
		{
            if($reservation_room_id != $item['reservation_room_id'])
            {
                $reservation_room_id = $item['reservation_room_id'];
                
                foreach($report->payment_types as $key =>$payment)
                {
    				if($payment['def_code']!='FOC' && $payment['def_code']!='DEBIT')
                    {
    					if($payment['def_code']=='CREDIT_CARD')
                        {
    						foreach($report->credit_card as $key =>$credit_card)
                            {
    							foreach($report->currencies as $key =>$currency)
                                {
    								if(isset($item[''.$payment['def_code'].'_'.$credit_card['id'].'_'.$currency['id'].'']) && $temp = $item[''.$payment['def_code'].'_'.$credit_card['id'].'_'.$currency['id'].''])
                                    {
    									$this->group_function_params[''.$payment['def_code'].'_'.$credit_card['id'].'_'.$currency['id'].''] += $temp;
    								}
    							}	
    						}
    					}
    					foreach($report->currencies as $key => $currency)
                        {
    						if(isset($item[''.$payment['def_code'].'_'.$currency['id'].'']) && $temp = $item[''.$payment['def_code'].'_'.$currency['id'].''])
                            {
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
    			if($temp = $item['bank'])
                {
    				$this->group_function_params['bank'] += $temp;
    			}
                if($temp = $item['refund'])
                {
    				$this->group_function_params['refund'] += $temp;
    			}
    			if($temp = $item['room'])
                {
    				$this->group_function_params['room'] += $temp;
    			}
    			if($temp = $item['deposit'])
                {
    				$this->group_function_params['deposit'] += $temp;
    			}
                if($temp = $item['total_discount_amount_persent'])
                {
    				$this->group_function_params['total_discount_amount_persent'] += $temp;
    			}
                if($temp = $item['discount_amount'])
                {
    				$this->group_function_params['discount_amount'] += $temp;
    			}
            }
            
            if($temp = $item['quantity'])
            {
				$this->group_function_params['quantity'] += $temp;
			}
            if($temp = $item['amount'])
            {
				$this->group_function_params['amount'] += $temp;
			}
		}
        
        //System::debug($this->group_function_params);
        //exit();
        if (1 == 1)
        {
            $arr = array(
    			'payment_types'=>$report->payment_types,
    			'currencies'=>$report->currencies,
    			'credit_card'=>$report->credit_card,
    			'items'=>$items,
    			'last_group_function_params'=>$last_group_function_params,
    			'group_function_params'=>$this->group_function_params,
    			'page_no'=>$page_no,
    			'total_page'=>$total_page,
                'line_per_page'=> $this->map['line_per_page'],
                'from_date'=> $this->map['from_date'],
                'to_date'=> $this->map['to_date'],
                'no_of_page'=> $this->map['no_of_page'],
                'portal_id_list'=> $this->map['portal_id_list'],
                'room_id_list'=> $this->map['room_id_list'],
                'count_items' => $this->count_items,
    		);
           
            $this->parse_layout('report', $arr);
        }
        //exit();
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
							inner join massage_reservation_room on payment.bill_id = massage_reservation_room.id
						WHERE 
							payment.bill_id in ('.$bill_id.') AND payment.type=\'SPA\'
						GROUP BY payment.payment_type_id,payment.currency_id,payment.bill_id
						,payment.currency_id,payment.type_dps,payment.credit_card_id
				');	
	}
}
?>
