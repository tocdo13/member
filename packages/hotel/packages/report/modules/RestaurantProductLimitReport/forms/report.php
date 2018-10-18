<?php
class RestaurantProductLimitReportForm extends Form
{
	function RestaurantProductLimitReportForm()
	{
		Form::Form('RestaurantProductLimitReportForm');
	}
	function draw()
	{
		if(URL::get('do_search'))
		{
			require_once 'packages/core/includes/utils/time_select.php';
			$from_year =1990;
			$to_year =date('Y',time());
			$from_month=1;
			$to_month=1;
			$from_day=1;
			$to_day = Date_Time::day_of_month(date('m'),date('Y'));
			if(URL::get('from_year'))
			{
				$from_year = Url::get('from_year');
			}
			if(URL::get('to_year'))
			{
				$to_year = Url::get('to_year');
			}
			if(Url::get('from_month'))
			{	
				$from_month = Url::get('from_month');
			}
			if(Url::get('to_month'))
			{	
				$to_month = Url::get('to_month');
			}
			if(URL::get('from_day'))
			{
				$from_day = URL::get('from_day');
			}
			if(URL::get('to_day'))
			{
				$to_day = URL::get('to_day');
			}
			if(!checkdate($from_month,$from_day,$from_year))
			{
				$from_day = 1;	
			}
			if(!checkdate($to_month,$to_day,$to_year))
			{
				$to_day = Date_time::day_of_month($to_month,$to_year);	
			}
			$this->line_per_page = URL::get('line_per_page',15);
			$cond = '
					1>0 '
					.(URL::get('bar_id')?'	bar_reservation.and bar_id = \''.URL::get('bar_id').'\'':'') 
					.' and bar_reservation.time>=\''.strtotime($from_month.'/'.$from_day.'/'.$from_year).'\' and bar_reservation.time<\''.(strtotime($to_month.'/'.$to_day.'/'.$to_year)+24*3600).'\''
			;
			DB::query('
			select * from (
				select 
					product_limit.id as id
					,product_limit.material_id
					,product_limit.product_id
					,product.name_'.Portal::language().' as name
					,unit.name_'.Portal::language().' as unit_id
					,product_limit.norm_quantity
					,ROWNUM as rownumber
				from
					product_limit
					inner join product on product.id = product_limit.material_id
					inner join unit on unit.id = product.unit_id
					inner join bar_reservation_product on bar_reservation_product.product_id = product_limit.product_id
					left outer join bar_reservation on bar_reservation.id = bar_reservation_product.bar_reservation_id
				where
					'.$cond.' and bar_reservation.status=\'CHECKOUT\'
				order by
					bar_reservation_product.product_id
			)
			where
				rownumber >0 and rownumber<=100
			');
			require_once 'packages/core/includes/utils/lib/report.php';

			$materials = DB::fetch_all();
			$products = DB::fetch_all('
				select
					product.id
				from
					bar_reservation_product
					inner join product on product.id = bar_reservation_product.product_id
					left outer join bar_reservation on bar_reservation.id = bar_reservation_product.bar_reservation_id
				where
					'.$cond.'
				order by
					bar_reservation_product.product_id
			');
			$report = new Report;
			$i = 1;
			foreach($materials as $key=>$item)
			{
				if($products[$item['product_id']])
				{
					$report->items[$item['material_id']] = $item;
				}
			}
			foreach($materials as $key=>$item)
			{
				if($products[$item['product_id']])
				{
					if(isset($report->items[$item['material_id']]['total']))
					{
						$report->items[$item['material_id']]['total']+= $item['norm_quantity'];
					}
					else
					{
						$report->items[$item['material_id']]['total'] = $item['norm_quantity'];
						$report->items[$item['material_id']]['stt'] = $i++;
					}
				}
			}
			$this->print_all_pages($report);
		}
		else
		{
			$this->parse_layout('search',
				array(
				'bar_id' => URL::get('bar_id',''),
				'bar_id_list' => array(''=>'')+String::get_list(DB::select_all('bar',false)), 
				)
			);	
		}			
	}
	function print_all_pages(&$report)
	{
		$count = 0;
		$total_page = 1;
		$pages = array();
		
		$from_year =1990;
		$to_year =date('Y',time());
		$from_month=1;
		$to_month=1;
		$from_day=1;
		$to_day=31;
		$status="0";
		if(URL::get('from_year'))
		{
			$from_year = Url::get('from_year');
		}
		if(URL::get('to_year'))
		{
			$to_year = Url::get('to_year');
		}
		if(Url::get('from_month'))
		{	
			$from_month = Url::get('from_month');
		}
		if(Url::get('to_month'))
		{	
			$to_month = Url::get('to_month');
		}
		if(URL::get('from_day'))
		{
			$from_day = URL::get('from_day');
		}
		if(URL::get('to_day'))
		{
			$to_day = URL::get('to_day');
		}
		if(!checkdate($from_month,$from_day,$from_year))
		{
			$from_day = 1;	
		}
		if(!checkdate($to_month,$to_day,$to_year))
		{
			$to_day = Date_time::day_of_month($to_month,$to_year);	
		}		
		if(isset($report->items))
		{
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
		}
		if(sizeof($pages)>0)
		{
			$this->group_function_params = array(
				    'deposit_USD'=>0, 'deposit_USD_count'=>0, 
					'deposit_usd'=>0, 'deposit_usd_count'=>0,
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
					'page_no'=>1,
					'total_page'=>1,
					'from_year'=>$from_year,
					'to_year'=>$to_year,
					'from_month'=>$from_month,
					'to_month'=>$to_month,
					'from_day'=>$from_day,
					'to_day'=>$to_day,
				)
			);
			$this->parse_layout('footer',array(
				'page_no'=>1,
				'total_page'=>1
			));
		}
	}
	function print_page($items, &$report, $page_no,$total_page)
	{
		$from_year =1990;
		$to_year =date('Y',time());
		$from_month=1;
		$to_month=1;
		$from_day=1;
		$to_day=31;
		$status="0";
		if(URL::get('from_year'))
		{
			$from_year = Url::get('from_year');
		}
		if(URL::get('to_year'))
		{
			$to_year = Url::get('to_year');
		}
		if(Url::get('from_month'))
		{	
			$from_month = Url::get('from_month');
		}
		if(Url::get('to_month'))
		{	
			$to_month = Url::get('to_month');
		}
		if(URL::get('from_day'))
		{
			$from_day = URL::get('from_day');
		}
		if(URL::get('to_day'))
		{
			$to_day = URL::get('to_day');
		}
		if(!checkdate($from_month,$from_day,$from_year))
		{
			$from_day = 1;	
		}
		if(!checkdate($to_month,$to_day,$to_year))
		{
			$to_day = Date_time::day_of_month($to_month,$to_year);	
		}
		$last_group_function_params = $this->group_function_params;
		$this->parse_layout('header',
			array(
				'page_no'=>$page_no,
				'total_page'=>$total_page,
				'from_year'=>$from_year,
				'to_year'=>$to_year,
				'from_month'=>$from_month,
				'to_month'=>$to_month,
				'from_day'=>$from_day,
				'to_day'=>$to_day,
			)
		);		
		$this->parse_layout('report',
			(($page_no==$total_page)?array():array())
			+array(
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