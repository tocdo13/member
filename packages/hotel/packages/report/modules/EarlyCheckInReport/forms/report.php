<?php
class EarlyCheckInReportForm extends Form{
	function EarlyCheckInReportForm(){
		Form::Form('EarlyCheckInReportForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw(){
	   
        $this->map = array();
        
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):32;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        
		if(URL::get('do_search')){
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';	
            
			$from_day = Url::get('date_from');
            $to_day = Url::get('date_to');
            
            //Start Luu Nguyen Giap add portal
            if(Url::get('portal_id'))
            {
               $portal_id =  Url::get('portal_id');
            }
            else
            {
                $portal_id =PORTAL_ID;
            }
            if($portal_id!="ALL")
            {
                $cond ="  reservation.portal_id ='".$portal_id."' ";
            }
            else
            {
                $cond=" 1=1 ";
            } 
            //End Luu Nguyen Giap add portal
            
				$cond .= ' 
					AND (reservation_room.status <> \'CANCEL\')
					'.(URL::get('lately_checkout')?' 
					AND (reservation_room.departure_time>=\''.Date_Time::to_orc_date($from_day).'\' 
					AND reservation_room.departure_time<=\''.Date_Time::to_orc_date($to_day).'\')
					':'AND (
						(reservation_room.arrival_time>=\''.Date_Time::to_orc_date($from_day).'\' 
						AND reservation_room.arrival_time<=\''.Date_Time::to_orc_date($to_day).'\'
						AND reservation_room.early_arrival_time IS NULL)
						OR
						(reservation_room.early_arrival_time>=\''.Date_Time::to_orc_date($from_day).'\' 
						AND reservation_room.early_arrival_time<=\''.Date_Time::to_orc_date($to_day).'\')
					)').'
			';
			
            $cond .= (URL::get('lately_checkout'))?" and extra_service.code = 'LATE_CHECKOUT'":" and extra_service.code = 'EARLY_CHECKIN'";
			$report = new Report;
			$sql = '
				select
					reservation_room.id
					,reservation_room.arrival_time
					,reservation_room.departure_time
					,(reservation_room.arrival_time-current_date) as time_segment
					,reservation_room.time_in
					,reservation_room.time_out
					,CONCAT(CONCAT(
						DECODE(reservation.customer_id,0,\'\',CONCAT(customer.name,\'. \')),
						DECODE(reservation.note,\'\',\'\',CONCAT(reservation.note,\'. \'))),
						DECODE(reservation_room.note,\'\',\'\',CONCAT(reservation_room.note,\'. \'))
					) as note
					,reservation_room.reservation_id
					,reservation_room.status
					,0 as colspan
					,DECODE(room.name,NULL,reservation_room.temp_room,room.name) as room_name
					,reservation_room.early_checkin
					,reservation_room.early_arrival_time
					,reservation_room.adult
					,reservation.booking_code
					,reservation.id as reservation_id
                    ,extra_service_invoice.net_price
                    ,extra_service_invoice.tax_rate
                    ,extra_service_invoice.service_rate
                    ,customer.name as customer_name
                    ,CASE
    							WHEN 
    								extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
    							THEN
    								ROUND(((extra_service_invoice_detail.quantity*extra_service_invoice_detail.price) + ((extra_service_invoice_detail.quantity*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + (((extra_service_invoice_detail.quantity*extra_service_invoice_detail.price) + ((extra_service_invoice_detail.quantity*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)),2)
    							ELSE
    								ROUND(((extra_service_invoice_detail.quantity*extra_service_invoice_detail.price)),2)
					       END charge
                    ,ROW_NUMBER() OVER (order by reservation_room.time_out DESC) AS stt
				from
                    extra_service_invoice_detail
                    inner join extra_service on extra_service.id = extra_service_invoice_detail.service_id
				    inner join extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
					inner join  reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
					inner join reservation on reservation.id=reservation_room.reservation_id
					left outer join customer on customer.id=reservation.customer_id
					left outer join room on room.id=reservation_room.room_id
                    
				where  
					'.$cond.'';		
			$report->items = DB::fetch_all($sql);
 
            foreach($report->items as $key=>$item)
			{
                if($item['net_price']==1)
                {
                    $param_e = (1+($item['tax_rate']*0.01) + ($item['service_rate']*0.01) + (($item['tax_rate']*0.01)*($item['service_rate']*0.01)));
                    $report->items[$key]['charge'] = round($item['charge']/$param_e,2);	
                }
				if(!Url::get('lately_checkout'))
                {
					if($item['early_checkin'] == 1){//if(!$item['early_checkin']){//intval(date('H',$item['time_in']))<=6 or 
						if($item['early_arrival_time']){
							$report->items[$key]['time_in'] = (intval(date('H',$item['time_in']))*3600) + (intval(date('i',$item['time_in']))*60) + Date_Time::to_time(Date_Time::convert_orc_date_to_date($item['early_arrival_time'],'/'));
						}
					}
				}
			}
			$this->print_all_pages($report);
		}
		else
		{
		    $_REQUEST['date_from'] = date('d/m/Y');
            $_REQUEST['date_to'] = date('d/m/Y');
			$_REQUEST['no_record'] = 0; 
            $this->map= array();
            //Start : Luu Nguyen GIap add portal
            $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()); 
            //End   :Luu Nguyen GIap add portal
			$this->parse_layout('search',$this->map);	
		}			
	}

	function print_all_pages(&$report)
	{
	    $from_day = URL::get('date_from');
        $to_day = URL::get('date_to');
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
			$this->group_function_params = array
            (
			    'room_count'=>0,
    			'guest_count'=>0,
    			'total_price'=>0,
                'total_charge'=>0 
			);
            $this->map['real_total_page']=count($pages);
            $this->map['real_page_no'] = 1;
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page);
                $this->map['real_page_no'] ++;                
			}
		}
		else
		{
			$this->parse_layout('header',
			get_time_parameters()+
				array(
					'page_no'=>1,
					'total_page'=>1,
                    'from_day'=>$from_day,
    				'to_day'=>$to_day
				)+$this->map
			);
			$this->parse_layout('footer',array(
				'page_no'=>0,
				'total_page'=>0
			)+$this->map);
		}
	}
	function print_page($items, &$report, $page_no,$total_page)
	{
	    $from_day = URL::get('date_from');
        $to_day = URL::get('date_to');
            		
		$last_group_function_params = $this->group_function_params;
        $room_name = false;
		$reservation_id = false;
        foreach($items as $item)
        {
            if($room_name<>$item['room_name'])
        	{
        		$room_name=$item['room_name'];
        		$this->group_function_params['room_count']++;
        	}
        	$this->group_function_params['guest_count'] += $item['adult'];
            $this->group_function_params['total_charge'] += $item['charge'];
        }
        if($page_no>=$this->map['start_page'])
		{
    		$this->parse_layout('header',
    			array(
    				'page_no'=>$page_no,
    				'total_page'=>$total_page,
                    'from_day'=>$from_day,
    				'to_day'=>$to_day
    			)+$this->map
    		);
            
    		$this->parse_layout('report',
    			array(
    				'items'=>$items,
    				'page_no'=>$page_no,
    				'total_page'=>$total_page,
                    'last_group_function_params'=>$last_group_function_params,
    				'group_function_params'=>$this->group_function_params,
    			)+$this->map
    		);
    		$this->parse_layout('footer',array(				
    			'page_no'=>$page_no,
    			'total_page'=>$total_page,
    		)+$this->map);
        }
	}
}
?>