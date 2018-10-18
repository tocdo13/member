<?php
class RestaurantRevenueReportForm extends Form
{
	function RestaurantRevenueReportForm()
	{
		Form::Form('RestaurantRevenueReportForm');
	}
	function draw()
	{
		if(URL::get('do_search'))
		{
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
			$year = URL::get('from_year');
			$end_year = URL::get('from_year');
			$day=1;
			$end_day = Date_Time::day_of_month(date('m'),date('Y'));
			if(URL::get('from_day'))
			{
				$day = URL::get('from_day');
				$end_day = URL::get('to_day');
			}
			$month = URL::get('from_month');
			$end_month = URL::get('to_month');
			if(!checkdate($month,$day,$year))
			{
				$day = 1;
			}
			if(!checkdate($end_month,$end_day,$end_year))
			{
				$end_day = Date_time::day_of_month($end_month,$end_year);
			}
			$this->line_per_page = URL::get('line_per_page',15);
			$cond = $this->cond = ' bar_reservation.portal_id = \''.PORTAL_ID.'\''
				.(URL::get('from_year')?' and bar_reservation.time_out>=\''.strtotime($month.'/'.$day.'/'.$year).'\' and bar_reservation.time_out<\''.(strtotime($end_month.'/'.$end_day.'/'.$end_year)+24*3600).'\'':'')
			.' and (bar_reservation.status=\'CHECKOUT\')'
			;
			if(Url::get('customer_name')){
				$cond .= ' AND UPPER(customer.name) LIKE \'%'.strtoupper(Url::sget('customer_name')).'%\'';
			}
			/*$fee_summary = array
							(
								'fee' => 0,
								'pay_by_cash_fee' => 0,
								'debt_fee' => 0,
								'pay_by_room_fee' => 0,
								'free_fee' => 0,
								'breakfast_fee' => 0,
								'tax' => 0,
								'pay_by_cash_tax' => 0,
								'debt_tax' => 0,
								'free_tax' => 0,
								'breakfast_tax' => 0,
								'pay_by_room_tax' => 0
							);*/
			$sql = '
				SELECT 
					sum((total_before_tax*bar_fee_rate/(100))*('.RES_EXCHANGE_RATE.'/bar_reservation.exchange_rate)) as fee,
					sum((CASE WHEN (payment_result=\'CASH\' or payment_result=\'\') THEN total_before_tax*bar_fee_rate/(100) ELSE 0 END )*('.RES_EXCHANGE_RATE.'/bar_reservation.exchange_rate)) as pay_by_cash_fee,
					sum((DECODE(payment_result,\'DEBT\',total_before_tax*bar_fee_rate/(100),0))*('.RES_EXCHANGE_RATE.'/bar_reservation.exchange_rate)) as debt_fee,
					sum((DECODE(payment_result,\'ROOM\',total_before_tax*bar_fee_rate/(100),0))*('.RES_EXCHANGE_RATE.'/bar_reservation.exchange_rate)) as pay_by_room_fee,
					sum((DECODE(payment_result,\'FREE\',total_before_tax*bar_fee_rate/(100),0))*('.RES_EXCHANGE_RATE.'/bar_reservation.exchange_rate)) as free_fee,
					sum((DECODE(payment_result,\'BREAKFAST\',total_before_tax*bar_fee_rate/(100),0))*('.RES_EXCHANGE_RATE.'/bar_reservation.exchange_rate)) as breakfast_fee,
					sum((tax)*('.RES_EXCHANGE_RATE.'/bar_reservation.exchange_rate)) as tax,
					sum((CASE WHEN(payment_result=\'CASH\' or payment_result=\'\') THEN tax ELSE 0 END )*('.RES_EXCHANGE_RATE.'/bar_reservation.exchange_rate)) as pay_by_cash_tax,
					sum((DECODE(payment_result,\'DEBT\',tax,0))*('.RES_EXCHANGE_RATE.'/bar_reservation.exchange_rate)) as debt_tax,
					sum((DECODE(payment_result,\'FREE\',tax,0))*('.RES_EXCHANGE_RATE.'/bar_reservation.exchange_rate)) as free_tax,
					sum((DECODE(payment_result,\'BREAKFAST\',tax,0))*('.RES_EXCHANGE_RATE.'/bar_reservation.exchange_rate)) as breakfast_tax,
					sum((DECODE(payment_result,\'ROOM\',tax,0))*('.RES_EXCHANGE_RATE.'/bar_reservation.exchange_rate)) as pay_by_room_tax
				FROM 
					bar_reservation 
					left outer join customer on customer.id = customer_id
				WHERE '.$cond.'
				';		
			
			$fee_summary = DB::fetch($sql);
			foreach($fee_summary as $key=>$value){
				//$fee_summary[$key] = String::vnd_round($value);
			}				
			$sql = '
				SELECT					
					bar_reservation_product.product_id as id
					,min(bar_reservation.id) as bar_reservation_id
					,sum(bar_reservation_product.quantity) as quantity 
					,(AVG(bar_reservation_product.price)*('.RES_EXCHANGE_RATE.'/(bar_reservation.exchange_rate))) as price
					,sum(bar_reservation_product.quantity_discount) as quantity_discount 
					,sum((bar_reservation_product.quantity-bar_reservation_product.quantity_discount)*((bar_reservation_product.price*'.RES_EXCHANGE_RATE.'/(bar_reservation.exchange_rate)))*bar_reservation_product.discount_rate/100) as discount 
					,sum((bar_reservation_product.quantity-bar_reservation_product.quantity_discount)*(bar_reservation_product.price*'.RES_EXCHANGE_RATE.'/(bar_reservation.exchange_rate))) as amount
					,res_product.name_'.Portal::language().' as product_name
					,sum((bar_reservation_product.quantity-bar_reservation_product.quantity_discount)*(bar_reservation_product.price*'.RES_EXCHANGE_RATE.'/(bar_reservation.exchange_rate))*(100-bar_reservation_product.discount_rate)/100) as total
					,sum(CASE WHEN (payment_result=\'CASH\' or payment_result=\'\') THEN (bar_reservation_product.quantity-bar_reservation_product.quantity_discount)*(bar_reservation_product.price*'.RES_EXCHANGE_RATE.'/(bar_reservation.exchange_rate))*(100-bar_reservation_product.discount_rate)/100 ELSE 0 END)as pay_by_cash
					,sum(DECODE(payment_result,\'ROOM\',(bar_reservation_product.quantity-bar_reservation_product.quantity_discount)*(bar_reservation_product.price*'.RES_EXCHANGE_RATE.'/(bar_reservation.exchange_rate))*(100-bar_reservation_product.discount_rate)/100,0))as pay_by_room
					,sum(DECODE(payment_result,\'DEBT\',(bar_reservation_product.quantity-bar_reservation_product.quantity_discount)*(bar_reservation_product.price*'.RES_EXCHANGE_RATE.'/(bar_reservation.exchange_rate))*(100-bar_reservation_product.discount_rate)/100,0))as debt
					,sum(DECODE(payment_result,\'FREE\',(bar_reservation_product.quantity-bar_reservation_product.quantity_discount)*(bar_reservation_product.price*'.RES_EXCHANGE_RATE.'/(bar_reservation.exchange_rate))*(100-bar_reservation_product.discount_rate)/100,0))as free
					,sum(DECODE(payment_result,\'BREAKFAST\',(bar_reservation_product.quantity-bar_reservation_product.quantity_discount)*(bar_reservation_product.price*'.RES_EXCHANGE_RATE.'/(bar_reservation.exchange_rate))*(100-bar_reservation_product.discount_rate)/100,0))as breakfast
				FROM 
					bar_reservation_product
					inner join bar_reservation on bar_reservation_id = bar_reservation.id
					left outer join res_product on (bar_reservation_product.product_id = res_product.code AND res_product.portal_id = \''.PORTAL_ID.'\')
					left outer join unit on unit_id = unit.id
					left outer join customer on customer.id = customer_id
				WHERE 
					'.$cond.'
				GROUP BY
					bar_reservation_product.product_id,res_product.name_'.Portal::language().',bar_reservation.exchange_rate
			';	
			$report = new Report;
			$report->items = DB::fetch_all($sql);
			$i = 1;
			$bar_reservations = DB::fetch_all('select bar_reservation.id,bar_fee_rate from bar_reservation left outer join customer on customer.id = customer_id where '.$cond.'');
			foreach($report->items as $key=>$item)
			{
				$report->items[$key]['stt'] = $i++;
				if($item['quantity']==0)
				{
					$report->items[$key]['quantity']='';
				}
				else
				{
					$report->items[$key]['quantity']=$item['quantity'];
				} 
				if($item['price']==0)
				{
					$report->items[$key]['price']='';
				}
				else
				{
					$report->items[$key]['price']= round($item['price'],4);
				} 
				if($item['quantity_discount']==0)
				{
					$report->items[$key]['quantity_discount']='';
				}
				else
				{
					$report->items[$key]['quantity_discount']=System::display_number($item['quantity_discount']);
				} 
				if($item['discount']==0)
				{
					$report->items[$key]['discount']='';
				}
				else
				{
					$report->items[$key]['discount']=System::display_number(($item['discount']));
				} 
				if($item['amount']==0)
				{
					$report->items[$key]['amount']='';
				}
				else
				{
					$item['amount'] = $item['amount'] - $item['free'];
					$report->items[$key]['amount']=System::display_number(($item['amount']));
				} 
				if($item['total']==0)
				{
					$report->items[$key]['total']='';
				}
				else
				{
					$item['total'] = $item['total'] - $item['free'];
					$report->items[$key]['total']=System::display_number(($item['total']));
				}
				if($item['total']==0)
				{
					$report->items[$key]['total']='';
				}
				else
				{
					$report->items[$key]['total']=System::display_number(($item['total']));
				}
				if($item['pay_by_cash']==0)
				{
					$report->items[$key]['pay_by_cash']='';
				}
				else
				{
					//$fee_summary['pay_by_cash_fee'] += (($item['pay_by_cash'])*$bar_reservations[$item['bar_reservation_id']]['bar_fee_rate']/100);
					$report->items[$key]['pay_by_cash']=System::display_number(($item['pay_by_cash']));
				}
				if($item['pay_by_room']==0)
				{
					$report->items[$key]['pay_by_room']='';
				}
				else
				{
					//$fee_summary['pay_by_room_fee'] += (($item['pay_by_room'])*$bar_reservations[$item['bar_reservation_id']]['bar_fee_rate']/100);
					$report->items[$key]['pay_by_room']=System::display_number(($item['pay_by_room']));
				}
				if($item['debt']==0)
				{
					$report->items[$key]['debt']='';
				}
				else
				{
					//$fee_summary['debt_fee'] += (($item['debt'])*$bar_reservations[$item['bar_reservation_id']]['bar_fee_rate']/100);
					$report->items[$key]['debt'] = System::display_number(($item['debt']));
				}
				if($item['free']==0)
				{
					$report->items[$key]['free']='';
				}
				else
				{
					$report->items[$key]['free']=System::display_number(($item['free']));
				}
			}
			$this->print_all_pages($report,$fee_summary);
		}
		else
		{
			$this->parse_layout('search',
				array(				
				'bar_id' => URL::get('bar_id',''),
				'bar_id_list' =>String::get_list(DB::select_all('bar',false)), 
				)
			);	
		}			
	}
	function print_all_pages(&$report,$fee_summary)
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
				    'discount'=>0,
					'discount_count'=>0, 
					'amount'=>0,
					'amount_count'=>0, 
					'total'=>0,
					'total_count'=>0, 
					'pay_by_cash'=>0,
					'pay_by_room'=>0,
					'breakfast'=>0,
					'free'=>0,
					'debt'=>0
				);
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page,$fee_summary);
			}
		}
		else
		{
			$this->parse_layout('header',
			get_time_parameters()+
				array(
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
	function print_page($items, &$report, $page_no,$total_page,$fee_summary)
	{
		$last_group_function_params = $this->group_function_params;
		foreach($items as $item)
		{
			if($temp=System::calculate_number($item['discount']))
			{
				$this->group_function_params['discount'] += $temp;
			}
			if($temp=System::calculate_number($item['amount']))
			{
				$this->group_function_params['amount'] += $temp;
			}
			if($temp=System::calculate_number($item['total']))
			{
				$this->group_function_params['total'] += $temp;
			}
			if($temp=System::calculate_number($item['pay_by_cash']))
			{
				$this->group_function_params['pay_by_cash'] += $temp;
			}
			if($temp=System::calculate_number($item['pay_by_room']))
			{
				$this->group_function_params['pay_by_room'] += $temp;
			}
			if($temp=System::calculate_number($item['debt']))
			{
				$this->group_function_params['debt'] += $temp;
			}
			if($temp=System::calculate_number($item['free']))
			{
				$this->group_function_params['free'] += $temp;
			}
		}
		$total = $this->group_function_params['pay_by_cash']+$fee_summary['pay_by_cash_tax'];//$fee_summary['pay_by_cash_fee']+
		// pay by currency
		$sql = '
			SELECT
				pay_by_currency.id,SUM(pay_by_currency.amount) as amount,
				pay_by_currency.currency_id,
				pay_by_currency.exchange_rate
			FROM
				pay_by_currency
				inner join bar_reservation on bar_reservation.id = pay_by_currency.bill_id
			where
				pay_by_currency.type=\'BAR\' and '.$this->cond.'
			group by
				pay_by_currency.currency_id,pay_by_currency.id,pay_by_currency.exchange_rate
			';
		$currency = DB::fetch_all($sql);
		$payment = array();
		$credit_card = 0;
		$total_currency = 0;
		foreach($currency as $key=>$value)
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
		$payment['USD'] = round($total - $total_currency,2);
		$this->parse_layout('header',
			array(
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)
		);
		$this->parse_layout('report',
			(($page_no==$total_page)?$fee_summary:array())
			+array(
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