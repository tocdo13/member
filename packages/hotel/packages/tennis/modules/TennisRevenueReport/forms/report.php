<?php
class TennisRevenueReportForm extends Form
{
	function TennisRevenueReportForm()
	{
		Form::Form('TennisRevenueReportForm');
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
				$cond .=' and tennis_reservation_court.status=\''.Url::get('status').'\'';
			}
			if(Url::get('court_id') and Url::get('court_id')!='All')
			{
				$cond .=' and tennis_court.id=\''.Url::get('court_id').'\'';
			}
			if(Url::get('date_from'))
			{
				$cond .= ' and tennis_reservation_court.time_out>='.Date_Time::to_time(Url::get('date_from'));
			}
			if(Url::get('date_to'))
			{
				$cond .= ' and tennis_reservation_court.time_out < '.(Date_Time::to_time(Url::get('date_to'))+(24*3600));
			}
			$inner = '
					inner join tennis_court on tennis_reservation_court.court_id = tennis_court.id
					left outer join tennis_reservation on tennis_reservation_court.reservation_id = tennis_reservation.id
					left outer join room on room.id = tennis_reservation_court.hotel_reservation_room_id
					left outer join tennis_guest on tennis_guest.id = tennis_reservation_court.guest_id
					inner join tennis_staff_court on tennis_staff_court.reservation_court_id = tennis_reservation_court.id
					inner join tennis_staff on tennis_staff_court.staff_id = tennis_staff.id
			';
			$this->line_per_page = URL::get('line_per_page',15);		
			$sql = '
			SELECT  * FROM
			(
				SELECT 
					 tennis_reservation_court.id,
					 tennis_guest.full_name as guest_name,
					 tennis_court.name,
					 tennis_court.category,
					 tennis_reservation_court.people_number, 					
 					 tennis_reservation_court.time_in,
					 tennis_reservation_court.time_out,
					 tennis_reservation_court.tip_amount,
					 tennis_reservation_court.discount,
					 tennis_reservation_court.tax,
					 tennis_reservation_court.total_amount,
					 tennis_reservation_court.status,
					 tennis_reservation_court.price,
					 tennis_staff.full_name as staff_name,
					 ROWNUM as rownumber
				  FROM 
					tennis_reservation_court
					'.$inner.'
				  WHERE 
				   '.$cond.'	
				  ORDER BY 
					  tennis_reservation_court.time_out DESC
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
			$status = DB::fetch_all('select status as id,status as name from tennis_reservation_court');
			$courts = DB::fetch_all('select id,name from tennis_court order by id');
			$this->parse_layout('search',array(
				'status_list'=>array('All'=>'T&#7845;t c&#7843;')+String::get_list($status),
				'court_id_list'=>array('All'=>'T&#7845;t c&#7843;')+String::get_list($courts),
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