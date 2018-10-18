<?php
class AgentStatisticReportForm extends Form
{
	function AgentStatisticReportForm()
	{
		Form::Form('AgentStatisticReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw()
	{      
        $this->map = array();
        
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):32;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        
        $this->map['date_from'] = Url::sget('date_from')?Url::sget('date_from'):('01/'.date('m/Y'));
        
        $this->map['date_to'] = Url::sget('date_to')?Url::sget('date_to'):(cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y')).'/'.date('m/Y'));
        
        $_REQUEST['date_from'] = $this->map['date_from'];    

        $_REQUEST['date_to'] = $this->map['date_to']; 
        
        
		if(Url::get('do_search'))
		{
            $cond = '';
            $cond_service ='';
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
                $cond.=' reservation.portal_id = \''.$portal_id.'\' ';
                $cond_service.=' and reservation.portal_id = \''.$portal_id.'\' '; 
            }
		    /** manh comment de lay lai dieu kien ngay gio
			$cond .= ' AND
                    (
                        ( reservation_room.status = \'CHECKOUT\' AND reservation_room.departure_time <=\''.date('d-M-Y',Date_Time::to_time($this->map['date_to'])).'\' AND reservation_room.departure_time >=\''.date('d-M-Y',Date_Time::to_time($this->map['date_from'])).'\'  ) 
                        OR 
                        ( reservation_room.status = \'CHECKIN\' AND reservation_room.arrival_time <=\''.date('d-M-Y',Date_Time::to_time($this->map['date_to'])).'\' AND reservation_room.arrival_time >=\''.date('d-M-Y',Date_Time::to_time($this->map['date_from'])).'\'   ) 
                    )
					'.((URL::get('customer_id'))?' AND reservation.customer_id = '.Url::get('customer_id').'':'').'
					'.((URL::get('booking_code'))?' AND reservation.booking_code = \''.Url::sget('booking_code').'\'':'').'
			';
            **/
            $cond .= ' AND
                    (
                        ( (reservation_room.status = \'CHECKIN\' OR reservation_room.status = \'CHECKOUT\') AND room_status.in_date <=\''.Date_Time::to_orc_date($this->map['date_to']).'\' AND room_status.in_date >=\''.Date_Time::to_orc_date($this->map['date_from']).'\'   ) 
                    )
					'.((URL::get('customer_id'))?' AND reservation.customer_id = '.Url::get('customer_id').'':'').'
					'.((URL::get('booking_code'))?' AND reservation.booking_code = \''.Url::sget('booking_code').'\'':'').'
			';
            $cond_service.=' AND extra_service_invoice_detail.time >=\''.Date_Time::to_time($this->map['date_from']).'\'
			    AND extra_service_invoice_detail.time <=\''.(Date_Time::to_time($this->map['date_to'])+86400).'\'
            ';
			require_once 'packages/core/includes/utils/lib/report.php';
			$report = new Report;
            /** Minh fix khong lay ra phong ao */
			$sql='
					SELECT
                        room_status.id,
                        reservation_room.id as reservation_room_id,
						customer.name as customer_name,
                        customer.id as customer_id,
                        reservation_room.departure_time,
                        reservation_room.arrival_time,
                        1 as night,
                        room_status.change_price as total,
                        room_status.in_date,
                        reservation_room.net_price,
                        reservation_room.tax_rate,
                        reservation_room.service_rate,
                        reservation_room.foc,
						reservation_room.adult,
						NVL(reservation_room.child,0)+NVL(reservation_room.child_5,0) as child,
                        reservation_room.foc_all,                            
                        NVL(reservation_room.reduce_balance,0) as reduce_balance,
                        NVL(reservation_room.reduce_amount,0) as reduce_amount
					FROM
                        reservation_room 
                        inner join reservation on reservation.id = reservation_room.reservation_id
                        left  join room_status on room_status.reservation_room_id = reservation_room.id
						inner join customer ON customer.id = reservation.customer_id
                        inner join room_level on room_level.id = reservation_room.room_level_id
					WHERE
						'.$cond.'                        
                        and customer.group_id != \'ROOT\'
                        and room_status.change_price > 0
                        and room_level.id != 15 
					ORDER BY
						reservation_room.id
						
			';
            /** Minh fix khong lay ra phong ao */
			$report->items = DB::fetch_all($sql);
            //System::debug($report->items);
            //Ti?n d?ch v?
        $sql = '
            SELECT
                extra_service_invoice.id,
                customer.name as customer_name,
                extra_service_invoice_detail.quantity,
                extra_service_invoice_detail.time,
                extra_service_invoice.total_amount as price_service
            FROM
                extra_service_invoice_detail
                inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                inner join extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
                inner join reservation_room on extra_service_invoice.reservation_room_id = reservation_room.id
                inner join reservation on reservation_room.reservation_id = reservation.id
				INNER JOIN  room_level on room_level.id = reservation_room.room_level_id
                inner join customer on customer.ID = reservation.customer_ID
                inner join customer_group on customer_group.id = customer.group_id
            WHERE
                (extra_service.code = \'LATE_CHECKOUT\'
                OR extra_service.code = \'LATE_CHECKIN\'
                OR extra_service.code = \'EARLY_CHECKIN\'
                OR extra_service_invoice.payment_type = \'ROOM\')
                AND (room_level.is_virtual is null or room_level.is_virtual = 0)
                AND reservation_room.foc_all = 0
                '.$cond_service
        ;
        $record = DB::fetch_all($sql);
            $ta = array();
			$i = 1;
            $reservation_room = '';
			foreach($report->items as $key=>$item)
			{
                
                if($item['net_price']==0)
                {
                    $report->items[$key]['total_after'] = $item['total'] * (1-$item['reduce_balance']/100);
                    
                    if($item['in_date']==$item['arrival_time'])
                        $report->items[$key]['total_after'] = $report->items[$key]['total_after'] - $item['reduce_amount'];
                    
                    $report->items[$key]['total_after'] = $report->items[$key]['total_after'] * (1+$item['service_rate']/100);
                    $report->items[$key]['total_after'] = round($report->items[$key]['total_after'] * (1+$item['tax_rate']/100),0);
                }
                else
                {
                    $report->items[$key]['total_after'] = $item['total'] / (1+$item['tax_rate']/100);
                    $report->items[$key]['total_after'] = $report->items[$key]['total_after'] / (1+$item['service_rate']/100);
                    $report->items[$key]['total_after'] = $report->items[$key]['total_after'] * (1-$item['reduce_balance']/100);
                    
                    if($item['in_date']==$item['arrival_time'])
                        $report->items[$key]['total_after'] = $report->items[$key]['total_after'] - $item['reduce_amount'];
                    
                    $report->items[$key]['total_after'] = $report->items[$key]['total_after'] * (1+$item['service_rate']/100);
                    $report->items[$key]['total_after'] = round($report->items[$key]['total_after'] * (1+$item['tax_rate']/100),0);
                /** Minh sua doanh thu foc,foc all */                    
                }                      
                if($item['foc_all'] == 1 || $item['foc'] != null)
                {
                    
                    $report->items[$key]['total_after'] = 0;
                    $report->items[$key]['night'] -= 1;                    
                } 
                /** Minh sua doanh thu foc,foc all */                
                if(!isset($ta[$item['customer_id']]))
                {
                    $ta[$item['customer_id']] = array(
                                                    'stt'=>$i++,
                                                    'customer_id'=>$item['customer_id'],
                                                    'customer_name'=>$item['customer_name'],
                                                    'total_night'=>$report->items[$key]['night'],                                                    
													'total_adult'=>$item['adult'],
													'total_child'=>$item['child'],
                                                    'total_room'=>1,
                                                    'foc_all'=>$item['foc_all'],                                                    
                                                    'foc'=>$item['foc'],
                                                    'total_money'=>$item['total'],
                                                    'total_money_after'=>$report->items[$key]['total_after']                                                    
                                                    );
                    $reservation_room=$item['reservation_room_id'];
                }
                else
                {
                    $ta[$item['customer_id']]['customer_name'] = $item['customer_name'];   
                    $ta[$item['customer_id']]['total_night'] += $report->items[$key]['night'];                                                         
                    if($reservation_room!=$item['reservation_room_id'])
                    {
                        $reservation_room=$item['reservation_room_id'];
                        $ta[$item['customer_id']]['total_room'] ++;
    					$ta[$item['customer_id']]['total_adult'] +=$item['adult'];
    					$ta[$item['customer_id']]['total_child'] +=$item['child'];
                    }
                    $ta[$item['customer_id']]['total_money'] += $item['total'];
                    $ta[$item['customer_id']]['total_money_after'] += $report->items[$key]['total_after'];
                }
            }
            foreach($ta as $key1=>$value1)
            {
                foreach($record as $key2=>$value2)
                {
                    if($value1['customer_name']==$value2['customer_name'])
                    {
                        $ta[$key1]['total_money_after']+=$value2['price_service'];                       
                        $ta[$key1]['total_night']+=$value2['quantity'];
                    }
                }
            }            
            //System::debug($ta);
            $this->print_all_pages($ta);
		}
		else
		{
            //list TA
			$this->map['customer_id_list'] = array(''=>Portal::language('All')) + String::get_list(DB::select_all('customer','GROUP_ID is not null','name'));
			$this->map['tour_id_list'] = array(''=>Portal::language('All')) + String::get_list(DB::select_all('tour',false,'name'));
			$this->map['portal_id_list'] = array(''=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
			$this->parse_layout('search',$this->map);	
		}			
	}

	function print_all_pages(&$ta)
	{
		$count = 0;
		$total_page = 1;
		$pages = array();
       
        foreach($ta as $key=>$item)
		{
			if($count>=$this->map['line_per_page'])
			{
				$count = 0;
				$total_page++;
			}
			$pages[$total_page][$key] = $item;
			$count++;
		}
        $arr = array_keys($pages);
        if(!empty($arr))
        {
            $begin = $arr['0'];
            $end = $begin + $this->map['no_of_page'] - 1;
            for($i = $total_page; $i> $end; $i--)
                unset($pages[$i]);      
        }
        
		if(sizeof($pages)>0)
		{
		  	$this->group_function_params = array(
        			'total_money'=>0,
        			'total_money_after'=>0,
        			'total_night'=>0,
					'total_adult'=>0,
					'total_child'=>0,
        			'total_room'=>0,
                    'total_per_night'=>0
				);
            
            $this->map['real_total_page']=count($pages);
            $this->map['real_page_no'] = 1;
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $page_no,$total_page);
                $this->map['real_page_no'] ++;
			}
		}
		else
		{
            $this->map['real_total_page'] = 0;
            $this->map['real_page_no'] = 0;
			$this->parse_layout('report',$this->map+
				array(
					'page_no'=>0,
					'total_page'=>0
				)
			);
		}
	}
	function print_page($items, $page_no, $total_page)
	{
	    //start: KID thêm do?n này d? tính t?ng c?a trang tru?c chuy?n sang n?u s? trang khác 1   
		$last_group_function_params = $this->group_function_params;
        //end: KID        
		foreach($items as $k => $item)
		{
            $items[$k]['total_per_night'] = round($item['total_money_after']/($item['total_night']?$item['total_night']:1));            
            $this->group_function_params['total_money']+=$item['total_money'];
            $this->group_function_params['total_money_after']+=$item['total_money_after'];
            $this->group_function_params['total_night']+=$item['total_night'];
			$this->group_function_params['total_adult']+=$item['total_adult'];
			$this->group_function_params['total_child']+=$item['total_child'];
            $this->group_function_params['total_room']+=$item['total_room'];
            $this->group_function_params['total_per_night']+=$items[$k]['total_per_night'];
		}
        if($page_no>=$this->map['start_page'])
		{
    		  $this->parse_layout('report',
    			array(
    				'items'=>$items,
    				'page_no'=>$page_no,
    				'total_page'=>$total_page,
    				'last_group_function_params'=>$last_group_function_params,
    				'group_function_params'=>$this->group_function_params,
    			)+$this->map
    		);
        }
	}
}
?>
