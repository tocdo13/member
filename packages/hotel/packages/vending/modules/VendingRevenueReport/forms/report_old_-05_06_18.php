<?php
class TicketRevenueReportForm extends Form
{
	function TicketRevenueReportForm()
	{
		Form::Form('TicketRevenueReportForm');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw()
	{
        
        $this->map = array();
        
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):999;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        
    	//Lay ra cac account
		$users = DB::fetch_all('select 
									    account.id
									    ,party.full_name 
								from    account 
								        INNER JOIN party on party.user_id = account.id AND party.type=\'USER\' 
                                WHERE 
                                        (account.portal_department_id <> \'1001\' AND account.portal_department_id <> \'1002\' )
                                        AND account.type=\'USER\'  ORDER BY account.id');			
        $this->map['user_id_list'] = array(''=>Portal::language('All'))+String::get_list($users);
        require_once 'packages/hotel/packages/vending/includes/php/vending.php';
        $area = get_area_vending();
        $area_option = '';            
        foreach($area as $key => $value)
        {
            $area_option .= '<li><input type="checkbox" onclick="get_ids()" value='.$value['id'].' />'.$value['name'].'</li>';
        }
        $this->map['area_option'] = $area_option;  
        //System::debug($area_option);
        $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
        $_REQUEST['to_date'] = $this->map['to_date'];
        //dau thang
        $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
        $_REQUEST['from_date'] = $this->map['from_date'];
        
		$cond = ' 1=1';
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
            $cond.=' AND ve_reservation.portal_id = \''.$portal_id.'\' '; 
        }
        
		$cond .= ' '
				.(URL::get('from_date')?' and ve_reservation.time>=\''.Date_Time::to_time(URL::get('from_date')).'\'':'')
				.(URL::get('to_date')?' and ve_reservation.time<\''.(Date_Time::to_time(URL::get('to_date'))+86400).'\'':'')
                .(URL::get('user_id')?' and payment.user_id = \''.URL::get('user_id').'\'':'')
		;
        $cond_area = ' AND 1=1';
        if(isset($_REQUEST['area_id']) && $_REQUEST['area_id'] !='')
        {
            $area_id = explode(',',$_REQUEST['area_id']);
            foreach($area_id as $key => $value)
            {
                if($cond_area == ' AND 1=1')
                {
                    $cond_area .= ' AND (ve_reservation.department_id = \''.$value.'\'';
                }else
                {
                    $cond_area .= ' OR ve_reservation.department_id = \''.$value.'\'';                    
                }                
            }
            $cond_area .= ')';
        }
        if(Url::get('from_bill') && Url::get('to_bill'))
        {
            $cond.= " and SUBSTR(ve_reservation.code, 6 ) >=".Url::get('from_bill')." and SUBSTR(ve_reservation.code, 6 )<=".Url::get('to_bill');
        }
        else
        {
            if(Url::get('from_bill'))
            {
                $cond.= " and SUBSTR(ve_reservation.code, 6 ) >=".Url::get('from_bill');
            }
            else if(Url::get('to_bill'))
            {
                $cond.= " and SUBSTR(ve_reservation.code, 6 ) <=".Url::get('to_bill');
            }
        }
        $cond .=' and ve_reservation.department_id in (';    
        foreach($area as $k=>$v)
        {
            $cond.=$k.',';
        }
        $cond = trim($cond,',');
        $cond.= ')'; 
        
        
		$sql = '
			SELECT * FROM
			(
				SELECT 
					ve_reservation.id,
                    ve_reservation.code,
                    --ve_reservation.total,
                    ve_reservation.user_id,
                    ve_reservation.note,
                    to_char(FROM_UNIXTIME(ve_reservation.time),\'DD/MM/YYYY\') as create_date,
                    DECODE(
                        ve_reservation.agent_name, \'\',ve_reservation.receiver_name,
                                                    ve_reservation.agent_name 
                    ) as agent_name,
					ROW_NUMBER() OVER (ORDER BY ve_reservation.id desc ) as rownumber,
                    ve_reservation.department_id,
                    department.name_1 as area_name,
                    ve_reservation.full_rate,
                    ve_reservation.full_charge,
                    ve_reservation.tax_rate,
                    ve_reservation.bar_fee_rate,
                    ve_reservation.discount,
                    ve_reservation.discount_percent,
                    ve_reservation.amount_pay_with_room,
                    ve_reservation.pay_with_room
				FROM 
                    ve_reservation
                    inner join department on department.id = ve_reservation.department_id
                    left join payment on payment.bill_id=ve_reservation.id and payment.type=\'VEND\'
				WHERE 
                    '.$cond.' '.$cond_area.'
                ORDER BY
					ve_reservation.id asc
			)
			where 
				rownumber > '.(($this->map['start_page']-1)*$this->map['line_per_page']).' AND rownumber<='.($this->map['no_of_page']*$this->map['line_per_page']).'
		';
        $items = DB::fetch_all($sql);
        
        $sql='select ve_reservation.id,
                    sum((ve_reservation_product.price*(ve_reservation_product.quantity-ve_reservation_product.quantity_discount))) as price,
                    sum((ve_reservation_product.price*(ve_reservation_product.quantity-ve_reservation_product.quantity_discount))*NVL(ve_reservation_product.discount_rate,0)/100) as product_discount
                    from ve_reservation_product
                    inner join ve_reservation ON ve_reservation_product.bar_reservation_id = ve_reservation.id
                    left join payment on payment.bill_id=ve_reservation.id and payment.type=\'VEND\'
                    WHERE 
						'.$cond.' '.$cond_area.'
					group by
						ve_reservation.id
					';
        $record =  DB::fetch_all($sql);
        
        $i=1;
        $bill_id = 'payment.bill_id = 0';
		foreach($items as $key=>$value)
		{
            $bill_id .=' or payment.bill_id = '.$value['id'];
			$items[$key]['stt'] = $i++;
            $total_price = 0;
            foreach($record as $key2=>$value2)
            {
                if($value['id']==$value2['id'])
                {
                    if($value['id']==$value2['id'])
                    {
                        $items[$key]['total_price'] = $value2['price'];
                        $total_price = $items[$key]['total_price'];
                        $items[$key]['product_discount'] = $value2['product_discount'];
                    }
                    else
                    {
                        $items[$key]['product_discount'] = 0;
                    }
                }
            }
            if($value['full_rate']==0 and $value['full_charge']==0)//gia chua thue phi lay luon
            {
                $total_before_discount = $total_price;
            }
            if($value['full_charge']==1)//da co phi bo phi di
            {
                $total_before_discount = $total_price/(1+$value['bar_fee_rate']/100);
            }   
            if($value['full_rate']==1)//da co thue phi bo thue phi
            {
                $total_before_discount = $total_price/(1+$value['tax_rate']/100)/(1+$value['bar_fee_rate']/100);
            } 
            
            //tong truoc giam gia
            $items[$key]['before_discount'] = $total_before_discount;
            //tông sau giam gia
            $after_discount = ($total_before_discount*(100-$value['discount_percent'])/100)-$value['discount'];
            //so tien giam gia
            $items[$key]['total_discount'] = $total_before_discount - $after_discount;
            //so tien co phi
            $after_fee = $after_discount*(1+$value['bar_fee_rate']/100);
            //phi dich vu
            $items[$key]['fee_rate'] =($after_discount*(1+$value['bar_fee_rate']/100))-$after_discount ;
            //so tien co thue phi
            $items[$key]['total'] = $after_fee*(1+$value['tax_rate']/100);
            //thue
            $items[$key]['tax'] = $items[$key]['total'] - $after_fee;
            
		}
        
        $payments = $this->get_payments($bill_id);
		$all_payments = DB::fetch_all('select payment_type.*,payment_type.name_'.Portal::language().' as name from payment_type where def_code is not null and def_code !=\'THE\' ');
		$all_currencies = DB::fetch_all('select * from currency where allow_payment=1');
		$credit_card = DB::fetch_all('select * from credit_card');
        foreach($items as $key=>$value)
		{
			$total_paid = 0; $total_debit = 0;
            
            //$items[$key]['user_id']='';
            $items[$key]['deposit'] = 0;
            $items[$key]['foc'] = 0;
            $items[$key]['refund'] = 0;
			$items[$key]['bank'] = 0;
            $items[$key]['room'] = 0;
			if($value['pay_with_room']==1)
            {
				$items[$key]['room'] += $value['amount_pay_with_room'];		
                $total_paid += $value['amount_pay_with_room'];
			}
            foreach($payments as $k=>$pay)
            {
				if($pay['bill_id'] == $value['id'])
                {
                    //$items[$key]['user_id'] = $pay['user_id'];
					
                    if($pay['type_dps']=='VEND')
                    {
						$items[$key]['deposit'] += $pay['total_vnd'];
						
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
							$items[$key][$pay['payment_type_id'].'_'.$pay['credit_card_id'].'_'.$pay['currency_id']] = $pay['total'];
						}
                        else if($pay['payment_type_id']=='BANK')
                        {
						     $items[$key]['bank'] += $pay['total_vnd'];
                             $items[$key][$pay['payment_type_id'].'_'.$pay['currency_id']] = $pay['total_vnd'];
						}
                        else if($pay['payment_type_id']=='REFUND')
                        {
						     $items[$key]['refund'] += $pay['total_vnd'];
                             $items[$key][$pay['payment_type_id'].'_'.$pay['currency_id']] = $pay['total_vnd'];
						}
                        else
                        {
							$items[$key][$pay['payment_type_id'].'_'.$pay['currency_id']] = $pay['total'];	
						}
						if($pay['payment_type_id']=='FOC')
                        {
							$items[$key]['foc'] = $pay['total_vnd'];
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
			$items[$key]['debit'] = ($total_debit /*+ ($value['total'] - $total_paid)*/);
			$items[$key]['total'] =  $value['total'];
			$items[$key]['stt'] = $i++;
		}
        $report->items = $items;
        $report->currencies = $all_currencies;
		$report->payment_types = $all_payments;
		$report->credit_card = $credit_card;
        $this->print_all_pages($report);
	}
    
    function print_all_pages(&$report)
	{
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
					'total_amount'=>0,
                    'deposit'=>0,
					'debit'=>0,
                    'refund'=>0,
					'foc'=>0,
					'bank'=>0,
                    'room'=>0
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
            $this->map['real_page_no'] = 1; 
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page);
                $this->map['real_page_no'] ++;
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
        $payment = array();
		$credit_card = 0;
		$total_currency = 0; 
		$last_group_function_params = $this->group_function_params;
        foreach($items as $item)
		{
			foreach($report->payment_types as $key =>$payment)
            {
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
				$this->group_function_params['total_amount'] += $temp;
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
            if($temp = $item['room']){
				$this->group_function_params['room'] += $temp;
			}
            if($temp = $item['refund']){
				$this->group_function_params['refund'] += $temp;
			}
			if($temp = $item['deposit']){
				$this->group_function_params['deposit'] += $temp;
			}
		}
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
	}
    function get_payments($bill_id)
    {
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
                            --,payment.user_id
						FROM payment
							inner join ve_reservation on payment.bill_id = ve_reservation.id
						WHERE 
							('.$bill_id.') AND payment.type=\'VEND\'
						GROUP BY payment.payment_type_id,payment.currency_id,payment.bill_id
						,payment.currency_id,payment.type_dps,payment.credit_card_id
                        --,payment.user_id
				');	
	}
}
?>