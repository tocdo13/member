<?php
class DailyRevenueExtraServiceReportForm extends Form
{
	function DailyRevenueExtraServiceReportForm()
	{
		Form::Form('DailyRevenueExtraServiceReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw()
	{
        $cond_portal ='';
        
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list());
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
            $cond_portal.=' AND reservation.portal_id = \''.$portal_id.'\' '; 
        }
		//if(URL::get('do_search'))
		{
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
			$in_date = Url::get('in_date')?Date_Time::to_orc_date(Url::get('in_date')):Date_Time::to_orc_date(date('d/m/Y',time()));
            $to_date = Url::get('to_date')?Date_Time::to_orc_date(Url::get('to_date')):Date_Time::to_orc_date(date('d/m/Y',time()));
			$in_time =  Url::get('in_date')?Date_Time::to_time(Url::get('in_date')):Date_Time::to_time(date('d/m/y'));
            $to_time =  Url::get('to_date')?Date_Time::to_time(Url::get('to_date')):Date_Time::to_time(date('d/m/y'));
			$cond = '1>0 '
					.(URL::get('room_type_id')?' and room.room_type_id = \''.URL::get('room_type_id').'\'':'') 
					.(URL::get('customer_id')?' and reservation.customer_id=\''.URL::get('customer_id').'\'':'') 
					.(URL::get('reservation_type_id')?' and reservation_room.reservation_type_id = \''.URL::get('reservation_type_id').'\'':'') 
			;
			$reservation_cond = (URL::get('from_year')?' and reservation_room.departure_time>=\''.$in_date.'\' and reservation_room.departure_time<=\''.$to_date.'\'':'');
			$cond .=$reservation_cond;			
			$cond = '(reservation_room.status=\'CHECKIN\' OR reservation_room.status=\'CHECKOUT\') AND reservation_room.arrival_time<=\''.$in_date.'\' AND reservation_room.departure_time>=\''.$to_date.'\'';
			$report = new Report;
			$no_of_page = 500;
			$line_per_page = 24;
			$this->line_per_page = 24;
			$start_page = 1;
			$sql = 'SELECT * FROM
        			(
        				select 
        					reservation_room.id
        					,reservation_room.note
        					,reservation_room.traveller_id
        					,reservation_room.reservation_id
        					,room_status.change_price as room_total
        					,reservation_room.price
        					,reservation_room.arrival_time
        					,reservation_room.departure_time
        					,concat(concat(traveller.first_name,\'  \'),traveller.last_name) as customer_stay
        					,customer.name as customer_name
        					,room.name as room_name
        					,tour.name as tour_name
        					,ROWNUM as rownumber
        					,room_type.name as room_type
        					,reservation_room.room_id
        					,reservation_room.foc
        					,reservation_room.foc_all
        					,DECODE(reservation_room.reduce_balance,\'\',0,reservation_room.reduce_balance) as reduce_balance
        					,DECODE(reservation_room.reduce_amount,\'\',0,reservation_room.reduce_amount) as reduce_amount
        				from 
        					reservation_room
        					inner join reservation on reservation_room.reservation_id=reservation.id
        					left outer join customer on reservation.customer_id=customer.id
        					left outer join traveller on reservation_room.traveller_id=traveller.id
        					left outer join tour on reservation.tour_id = tour.id
        					inner join room on room.id=reservation_room.room_id
        					inner join room_type on room_type.id = room.room_type_id
        					INNER JOIN room_status ON room_status.reservation_room_id = reservation_room.id
        				where 
        					'.$cond.$cond_portal.' AND room_status.in_date >= \''.$in_date.'\' AND room_status.in_date >= \''.$to_date.'\'
        				order by 
        					departure_time DESC
        			)
        			WHERE
                        rownumber > '.(($start_page-1)*$line_per_page).' and rownumber<='.($no_of_page*$line_per_page);
			
            $report->items = DB::fetch_all($sql);
			//System::debug($report->items);
			$sql ='
				select
					pay_by_currency.currency_id as id
					,pay_by_currency.type
					,currency.exchange
					,sum(pay_by_currency.amount) as amount
				from
					pay_by_currency
					inner join reservation_room on pay_by_currency.bill_id = reservation_room.id
					inner join reservation on reservation_room.reservation_id=reservation.id
					inner join room on room.id=reservation_room.room_id
					inner join room_type on room_type.id = room.room_type_id
					inner join currency on currency.id = pay_by_currency.currency_id
				where
					pay_by_currency.type=\'RESERVATION\'
					and '.$cond.$cond_portal.'
				group by
					pay_by_currency.currency_id
					,currency.exchange
					,pay_by_currency.type	
			';
			$pay_by_currency = DB::fetch_all($sql);			
			$i = 1;
			//dich vu mo rong 
            //sum(extra_service_invoice_detail.quantity*extra_service_invoice_detail.price) as total_amount
			$sql = 	'	select 
                            concat(extra_service_invoice.reservation_room_id,extra_service_invoice_detail.service_id) as id,
							extra_service_invoice_detail.service_id	,
                            extra_service_invoice.reservation_room_id,
                            extra_service_invoice_detail.name,
                            CASE
    							WHEN 
    								extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
    							THEN
    								ROUND(SUM(((extra_service_invoice_detail.quantity + nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity + nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity + nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity + nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)),2)
    							ELSE
    								ROUND(SUM(((extra_service_invoice_detail.quantity + nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)),2)
					       END total_amount
						from 
							extra_service_invoice_detail
							inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id
							inner join reservation_room on reservation_room.id=extra_service_invoice.reservation_room_id
							inner join room on room.id=reservation_room.room_id
							inner join reservation on reservation.id=reservation_room.reservation_id
						where 
							'.$cond.$cond_portal.'
							AND extra_service_invoice_detail.used = 1
							AND extra_service_invoice.time >= '.$in_time.' AND extra_service_invoice.time < '.($to_time+24*3600).'
						GROUP BY 
							extra_service_invoice_detail.service_id	
							,extra_service_invoice.reservation_room_id
							,extra_service_invoice_detail.name,extra_service_invoice.net_price	
						'
			;
			$extra_services = DB::fetch_all($sql);
            $sql_service = 'SELECT id,name FROM extra_service';
			$num_services = DB::fetch_all($sql_service);				
			//System::debug($num_services);
			/*For hotel 5 star*/

            
			$total_not_by_reservation = array();
			//room
			$count_service = 0;
			foreach($report->items as $key=>$item)
			{
                $report->items[$key]['stt'] = $i++;
				//nha hang
				$report->items[$key]['extra_service_total'] = 0;
				foreach($extra_services as $id=> $service)
                {
					if($item['id'] ==  $service['reservation_room_id'])
                    {	
						$report->items[$key][$service['name']] = $service['total_amount'];	
						$report->items[$key]['extra_service_total'] +=$service['total_amount'];	
						//echo $report->items[$key]['extra_service_total'];	
					}
				}
				$report->items[$key]['total'] =0;
				$report->items[$key]['total'] += str_replace(',','',$report->items[$key]['extra_service_total']);
                //tong bang khong thi xoa ban ghi nay khong cho hien thi
                if($report->items[$key]['total']==0)
                    unset($report->items[$key]);
			}
		
			//System::Debug($report->items);
			$this->print_all_pages($report,$pay_by_currency,$total_not_by_reservation,$num_services);
		}	
	}

	function print_all_pages(&$report,$pay_by_currency,$extra_service,$num_services)
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
			$count+=ceil(strlen($item['note'])/18)+1;
		}		
		if(sizeof($pages)>0)
		{
			$this->group_function_params = array(
				'total'=>0,
				'extra_service_total'=>0,
			);
			foreach($num_services as $id => $value){
				$this->group_function_params[$value['name']] = 0;
			}
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page,$pay_by_currency, $extra_service ,$num_services);
			}
		}
		else
		{
			$this->parse_layout('header',
			get_time_parameters()+
				array(
                    'in_date'=>Url::get('in_date')?Url::get('in_date'):date('d/m/Y'),
                    'to_date'=>Url::get('to_date')?Url::get('to_date'):date('d/m/Y'),
					'page_no'=>1,
					'total_page'=>1
				)+$this->map
			);
			$this->parse_layout('no_record');
			$this->parse_layout('footer',array(
				'page_no'=>1,
				'total_page'=>1
			));
		}
	}
	function print_page($items, &$report, $page_no,$total_page,$pay_by_currency,$extra_service,$num_services)
	{
		$last_group_function_params = $this->group_function_params;
		foreach($items as $item)
		{
			if($temp=System::calculate_number($item['total']))
			{
				$this->group_function_params['total'] += $temp;
			}
			//for 5 star
			if($temp=System::calculate_number($item['extra_service_total']))
			{
				$this->group_function_params['extra_service_total'] += $temp;
			}									
			foreach($num_services as $id => $value){
				if(isset($item[$value['name']])){ 
					$this->group_function_params[$value['name']] += System::calculate_number($item[$value['name']]);
				}
			}
		}
		//tat ca cac dich vu them 
		$old_extra_service = $extra_service;	
		$this->parse_layout('header',
			array(
				'in_date'=>Url::get('in_date')?Url::get('in_date'):date('d/m/Y'),
                'to_date'=>Url::get('to_date')?Url::get('to_date'):date('d/m/Y'),
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)+$this->map
		);		
		$layout = 'report1';
		if(Url::get('type')==2)
		{
			$layout = 'report2';
		}elseif(Url::get('type')==3)
		{
			$layout = 'report3';
		}
		//System::Debug($items);
		$this->parse_layout('report',
			array(
				'in_date'=>Url::get('in_date')?Url::get('in_date'):date('d-m-Y'),
                'to_date'=>Url::get('to_date')?Url::get('to_date'):date('d/m/Y'),
				'items'=>$items,
				'last_group_function_params'=>$last_group_function_params,
				'group_function_params'=>$this->group_function_params,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
				'pay_by_currency'=>$pay_by_currency,
				'extra_service'=>$extra_service,
				'old_extra_service'=>$old_extra_service,
				'num_services'=>$num_services
			)
		);
		$this->parse_layout('footer',array(				
			'page_no'=>$page_no,
			'total_page'=>$total_page,
		));
	}
}
?>