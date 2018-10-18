<?php
class SwimmingPoolRevenueReportForm extends Form
{
	function SwimmingPoolRevenueReportForm()
	{
		Form::Form('SwimmingPoolRevenueReportForm');
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
			if(Url::get('status')!='All')
			{
				$cond .=' and swimming_pool_reservation_pool.status=\''.Url::get('status').'\'';
			}
			if(Url::get('swimming_pool_id')!='All')
			{
				$cond .=' and swimming_pool.id=\''.Url::get('swimming_pool_id').'\'';
			}
			if(Url::get('date_from'))
			{
				$cond .= ' and swimming_pool_reservation_pool.time_out>='.Date_Time::to_time(Url::get('date_from'));
			}
			if(Url::get('date_to'))
			{
				$cond .= ' and swimming_pool_reservation_pool.time_out < '.(Date_Time::to_time(Url::get('date_to'))+(24*3600));
			}
			$inner = '
					inner join swimming_pool on swimming_pool_reservation_pool.swimming_pool_id = swimming_pool.id
					left outer join swimming_pool_reservation on swimming_pool_reservation_pool.reservation_id = swimming_pool_reservation.id
					left outer join swimming_pool_guest on swimming_pool_guest.id = swimming_pool_reservation_pool.guest_id
					inner join swimming_pool_staff_pool on swimming_pool_staff_pool.reservation_pool_id = swimming_pool_reservation_pool.id
					inner join swimming_pool_staff on swimming_pool_staff_pool.staff_id = swimming_pool_staff.id
			';
			$this->line_per_page = URL::get('line_per_page',15);		
			$sql = '
			SELECT  * FROM
			(
				SELECT 
					 swimming_pool_reservation_pool.id,
					 swimming_pool_guest.full_name as guest_name,
					 swimming_pool.name,
					 swimming_pool.category,
					 swimming_pool_reservation_pool.people_number, 					
 					 swimming_pool_reservation_pool.time_in,
					 swimming_pool_reservation_pool.time_out,
					 swimming_pool_reservation_pool.tip_amount,
					 swimming_pool_reservation_pool.discount,
					 swimming_pool_reservation_pool.tax,
					 swimming_pool_reservation_pool.total_amount,
					 swimming_pool_reservation_pool.status,
					 swimming_pool_reservation_pool.price,
					 swimming_pool_staff.full_name as staff_name,
					 ROWNUM as rownumber
				  FROM 
					swimming_pool_reservation_pool
					'.$inner.'
				  WHERE 
				   '.$cond.'	
				  ORDER BY 
					  swimming_pool_reservation_pool.time_out DESC
			)	
			WHERE
			 rownumber > '.((URL::get('start_page')-1)*$this->line_per_page).' and rownumber<='.(URL::get('no_of_page')*$this->line_per_page);	
			$report = new Report;
			$report->items = DB::fetch_all($sql);
			$i = 1;
			foreach($report->items as $key=>$item)
			{
				$report->items[$key]['stt'] = $i++;
				$report->items[$key]['time_in']=date('h\h i\' d/m/Y',intval($item['time_in']));
				$report->items[$key]['time_out']=date('h\h i\' d/m/Y',intval($item['time_out']));
				if($item['tip_amount']==0)
				{
					$report->items[$key]['tip_amount']='';
				}
				else
				{
					$report->items[$key]['tip_amount']=System::display_number_report($item['tip_amount']);
				} 	
				if($item['discount']==0)
				{
					$report->items[$key]['discount']='';
				}
				else
				{
					$report->items[$key]['discount']=System::display_number_report($item['discount']);
				} 
				if($item['tax']==0)
				{
					$report->items[$key]['tax']='';
				}
				else
				{
					$report->items[$key]['tax']=System::display_number_report($item['tax']);
				} 	
				if($item['total_amount']==0)
				{
					$report->items[$key]['total_amount']='';
				}
				else
				{
					$report->items[$key]['total_amount']=System::display_number_report($item['total_amount']);
				} 				
			}
			$this->print_all_pages($report);
		}
		else
		{
			$status = DB::fetch_all('select status as id,status as name from swimming_pool_reservation_pool');
			$swimming_pools = DB::fetch_all('select id,name from swimming_pool order by id');
			$this->parse_layout('search',array(
				'status_list'=>array('All'=>'T&#7845;t c&#7843;')+String::get_list($status),
				'swimming_pool_id_list'=>array('All'=>'T&#7845;t c&#7843;')+String::get_list($swimming_pools),
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
					'discount'=>0, 
					'tax'=>0,
					'total_amount'=>0
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
		$last_group_function_params = $this->group_function_params;
		foreach($items as $item)
		{
			if($temp=System::calculate_number($item['tip_amount']))
			{
				$this->group_function_params['tip_amount'] += $temp;
			}
			if($temp=System::calculate_number($item['discount']))
			{
				$this->group_function_params['discount'] += $temp;
			}
			if($temp=System::calculate_number($item['tax']))
			{
				$this->group_function_params['tax'] += $temp;
			}
			if($temp=System::calculate_number($item['total_amount']))
			{
				$this->group_function_params['total_amount'] += $temp;
			}			
		}
		$this->parse_layout('header',
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