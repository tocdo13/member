<?php
class CommissionReportForm extends Form
{
	function CommissionReportForm()
	{
		Form::Form('CommissionReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw()
	{
	    $this->map = array();
        
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):32;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;   
		if(!isset($_REQUEST['date_from'])){
			$_REQUEST['date_from'] = '01/'.date('m/Y');
		}
		if(!isset($_REQUEST['date_to'])){
			$end_date_of_month = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
			$_REQUEST['date_to'] = $end_date_of_month.'/'.date('m/Y');
		}
		if(URL::get('do_search'))
		{
			$this->line_per_page = 4000;// OR RR1.STATUS = \'BOOKED\'
			$cond = '1=1'.((URL::get('status'))?' AND RR1.status =\''.Url::get('status').'\'':'AND(RR1.STATUS = \'CHECKOUT\' OR RR1.STATUS = \'CHECKIN\' OR RR1.STATUS = \'BOOKED\')').'
					'.((URL::get('customer_id'))?' AND reservation.customer_id = '.Url::get('customer_id').'':'').'
					'.((URL::get('booking_code'))?' AND reservation.booking_code = \''.Url::sget('booking_code').'\'':'').'
					'.(Url::get('date_from')?' AND RR1.DEPARTURE_TIME >=\''.Date_Time::to_orc_date(Url::get('date_from')).'\'':'').'
					'.(Url::get('date_to')?' AND RR1.ARRIVAL_TIME <=\''.Date_Time::to_orc_date(Url::get('date_to')).'\'':'').'
			';
			if(User::can_admin(false,ANY_CATEGORY)){
				$cond .= Url::get('portal_id')?' and reservation.portal_id = \''.Url::get('portal_id').'\'':'';
			}else{
				$cond .= ' and reservation.portal_id = \''.PORTAL_ID.'\'';	
			}
            
			require_once 'packages/core/includes/utils/lib/report.php';
			$report = new Report;
			$sql='SELECT 
						RR1.id,
						reservation.id as reservation_id,
                        customer.name as customer_name,
                        room.name as room_name,
                        RR1.commission_rate,
                        RR1.price as price,
						(COUNT(room_status.id)-1) AS night
					FROM
						reservation_room RR1
						INNER JOIN RESERVATION ON RR1.RESERVATION_ID = reservation.ID
                        inner join room_status on room_status.reservation_room_id = RR1.id
                        inner join customer on reservation.customer_id = customer.id
                        left join room on RR1.room_id = room.id	
					WHERE
						'.$cond.'
					GROUP BY 
						reservation.id,customer.name,room.name,RR1.id,RR1.commission_rate,RR1.price
					ORDER BY
						reservation.id,customer.name,room.name,RR1.id,RR1.commission_rate,RR1.price
						
			';
			$reservations = DB::fetch_all($sql);
			foreach($reservations as $k=>$reservation)
            {
                
                //kimtan lay ra so luong vs so tien cua cac dv theo tung rr_id
                $sql = '
                        SELECT
                            extra_service_invoice.reservation_room_id as id
                            ,sum(
                            CASE
                            WHEN 
                              extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
                            THEN
                              ROUND((extra_service_invoice_detail.quantity*extra_service_invoice_detail.price) + ((extra_service_invoice_detail.quantity*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + (((extra_service_invoice_detail.quantity*extra_service_invoice_detail.price) + ((extra_service_invoice_detail.quantity*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01),2)
                            ELSE
                              ROUND((extra_service_invoice_detail.quantity*extra_service_invoice_detail.price),2)
                              END
                              ) amount_service
                            ,sum(extra_service_invoice_detail.quantity) as quantity_service
                        FROM
                            extra_service_invoice_detail
							inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id
							inner join reservation_room on reservation_room.id=extra_service_invoice.reservation_room_id
                            inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                        WHERE 
                            (extra_service.code = \'LATE_CHECKIN\')
                            and reservation_room.id = '.$reservation['id'].'
                        GROUP BY 
                            extra_service_invoice.reservation_room_id
                ';
                //end kimtan lay ra so luong vs so tien cua cac dv theo tung rr_id
                
                if($reservation['commission_rate']!=0)
                {
        			$report->items[$k] = $reservation;
                    if($reservation['night']==0)
                    {
                        $report->items[$k]['total_price'] = $reservation['price'];
                    }
                    else
                    {
                        $report->items[$k]['total_price'] = $reservation['price']*$reservation['night'];
                    }
                    //kimtan lay them cac dv
                    $report->items[$k]['night'] += DB::fetch($sql,'quantity_service');
                    $report->items[$k]['total_price'] += DB::fetch($sql,'amount_service');   
        			//end lay them
                    $report->items[$k]['amount'] =System::display_number_report(($report->items[$k]['total_price']*$reservation['commission_rate'])/100);
                    
	            }
            }
			$this->print_all_pages($report);
            
		}
		else
		{
            if(!Url::get('portal_id'))
            {
            	$_REQUEST['portal_id'] = PORTAL_ID;
            }
             $status = array(
			''=>Portal::language('All_status'),
			'CHECKIN'=>'CHECKIN',
			'CHECKOUT'=>'CHECKOUT',
			'BOOKED'=>'BOOKED'
			);
			$this->map['customer_id_list'] =array(''=>Portal::language('All')) +String::get_list(DB::select_all('customer','1=1','name'));
			$this->map['tour_id_list'] = array(''=>Portal::language('All')) + String::get_list(DB::select_all('tour',false,'name'));
			$this->map['portal_id_list'] = array(''=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
			$this->parse_layout('search',$this->map+array('status_list'=>$status));	
		}			
	}

	function print_all_pages(&$report)
	{
		$count = 0;
		$total_page = 1;
		$pages = array();
		$this->group_function_params = array(
			'total_amount'=>0,
		);
		if(!empty($report->items)){
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
		}
		if(sizeof($pages)>0)
		{
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
            
			$this->map['name'] = Url::get('customer_id')?DB::fetch('SELECT ID,NAME FROM CUSTOMER WHERE ID = '.Url::iget('customer_id').'','name'):'';
			$this->parse_layout('header',$this->map+
				array(
					'page_no'=>0,
					'total_page'=>0
				)
			);
			$this->parse_layout('footer',array(
				'page_no'=>0,
				'total_page'=>0,
                'real_page_no' =>0,
                'real_total_page'=>0
			)+$this->map);
		}
	}
	function print_page($items, &$report, $page_no,$total_page)
	{
	    //start: KID them doan nay de tinh tong trang truoc chuyen sang trang sau neu so trang khac 1   
		$last_group_function_params = $this->group_function_params;
        $tour_id = false;
		foreach($items as $k => $item)
		{
		    
		  $this->group_function_params['total_amount'] += str_replace(',','',$item['amount']);

		}
        //end: KID   
		$this->map['name'] = Url::get('customer_id')?DB::fetch('SELECT ID,NAME FROM CUSTOMER WHERE ID = '.Url::iget('customer_id').'','name'):Portal::language('All');
		if($page_no>=$this->map['start_page'])
		{
		    $this->map['page_no'] = $page_no; 
            $this->parse_layout('header',$this->map+
    			array(
    				
    				'total_page'=>$total_page
    			)
    		);		
    		$this->parse_layout('report',
    			array(
    				'items'=>$items,
    				'total_page'=>$total_page,
                    'last_group_function_params'=>$last_group_function_params,
    				'group_function_params'=>$this->group_function_params
    			)+$this->map
    		);
    		
    		$this->parse_layout('footer',array(				
    			'total_page'=>$total_page)+$this->map
    		);
        }
	}
}
?>
