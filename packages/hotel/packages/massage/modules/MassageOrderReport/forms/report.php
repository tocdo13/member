<?php
class MassageRevenueReportForm extends Form
{
	function MassageRevenueReportForm()
	{
		Form::Form('MassageRevenueReportForm');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw()
	{
		if(URL::get('do_search'))
		{
			require_once 'packages/core/includes/utils/lib/report.php';
			require_once 'packages/core/includes/utils/time_select.php';
			$cond = ' 1 = 1 ';
			if(Url::get('date_from'))
			{
				$cond .= ' AND massage_reservation_room.time>='.Date_Time::to_time(Url::get('date_from'));
			}
			if(Url::get('date_to'))
			{
				$cond .= ' AND massage_reservation_room.time < '.(Date_Time::to_time(Url::get('date_to'))+(24*3600));
			}
			$this->line_per_page = URL::get('line_per_page',15);		
			$sql = '
			SELECT  * FROM
			(
				SELECT 
					 massage_reservation_room.*,
					 ROWNUM as rownumber
				  FROM 
					massage_reservation_room
				  WHERE 
				   '.$cond.'	
				  ORDER BY 
					  massage_reservation_room.time
			)	
			WHERE
			 rownumber > '.((URL::get('start_page')-1)*$this->line_per_page).' and rownumber<='.(URL::get('no_of_page')*$this->line_per_page);	
			$report = new Report;
			$report->items = DB::fetch_all($sql);
			$i = 1;
			foreach($report->items as $key=>$item)
			{
				$report->items[$key]['stt'] = $i++;
				$report->items[$key]['time'] = date('d/m/Y',$item['time']);
				$credit_card = 0;
				$currency = DB::fetch('
					SELECT
						pay_by_currency.id,pay_by_currency.amount
					FROM
						pay_by_currency
					WHERE
						pay_by_currency.currency_id=\'USD\' AND type=\'MASSAGE\' AND bill_id = '.$item['id'].'
				');
				$credit_card = $currency['amount'];
				$report->items[$key]['credit_card'] = System::display_number_report($credit_card);
				$report->items[$key]['tip_amount']=System::display_number_report($item['tip_amount']);
				$total_amount_vnd = 0;
				$currency = DB::fetch('
					SELECT
						pay_by_currency.id,pay_by_currency.amount
					FROM
						pay_by_currency
					WHERE
						pay_by_currency.currency_id=\'VND\' AND type=\'MASSAGE\' AND bill_id = '.$item['id'].'
				');
				$total_amount_vnd = $currency['amount'];
				$report->items[$key]['total_amount_vnd'] = System::display_number($total_amount_vnd);
				$report->items[$key]['total_amount']=System::display_number(round($item['total_amount']) - $credit_card - round($total_amount_vnd/$item['exchange_rate']));
				$report->items[$key]['debit']=0;
				$report->items[$key]['debit_vnd']=0;
			}
			$this->print_all_pages($report);
		}
		else
		{
			$status = DB::fetch_all('select status as id,status as name from massage_reservation_room');
			$rooms = DB::fetch_all('select id,name from massage_room order by id');
			$this->parse_layout('search',array(
				'status_list'=>array('All'=>'T&#7845;t c&#7843;')+String::get_list($status),
				'room_id_list'=>array('All'=>'T&#7845;t c&#7843;')+String::get_list($rooms),
				'status'=>'CHECKOUT'
			));	
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
				    'tip_amount'=>0,
					'total_amount'=>0,
					'total_amount_vnd'=>0,
					'total_debit'=>0,
					'total_credit_card'=>0
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
		foreach($items as $item)
		{
			if($temp=System::calculate_number($item['tip_amount']))
			{
				$this->group_function_params['tip_amount'] += $temp;
			}
			if($temp=System::calculate_number($item['total_amount']))
			{
				$this->group_function_params['total_amount'] += $temp;
			}
			if($temp=System::calculate_number($item['total_amount_vnd']))
			{
				$this->group_function_params['total_amount_vnd'] += $temp;
			}
			if($temp=System::calculate_number($item['debit']))
			{
				$this->group_function_params['total_debit'] += $temp;
			}
			if($temp=System::calculate_number($item['credit_card']))
			{
				$this->group_function_params['total_credit_card'] += $temp;
			}			
		}
		$last_group_function_params = $this->group_function_params;
		$this->map['date_from'] = Url::get('date_from');
		$this->map['date_to'] = Url::get('date_to');
		$this->parse_layout('header',$this->map+
			array(
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)
		);		
		$this->parse_layout('report',			
			array(
				'items'=>$items,
				'last_group_function_params'=>$last_group_function_params,
				'group_function_params'=>$this->group_function_params,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)
		);
		$this->parse_layout('footer',array(				
			'page_no'=>$page_no,
			'total_page'=>$total_page,
		));
	}
}
?>