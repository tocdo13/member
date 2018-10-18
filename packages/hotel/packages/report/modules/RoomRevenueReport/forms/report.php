<?php
class RoomRevenueReportForm extends Form{
	function RoomRevenueReportForm(){
		Form::Form('RoomRevenueReportForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
$this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');  
	}
    function on_submit()
    {
        //System::debug($_REQUEST);exit();
    }
	function draw()
    {
	   
        $this->map = array();
        if(Url::get('type')==1){
            $this->map['title'] = Portal::language('hotel_revenue_report');
        }
        elseif(Url::get('type')==2)
        {
            $this->map['title'] = Portal::language('room_revenue_report');
        }
        else
        {
            $this->map['title'] = Portal::language('reception_report'); 
        }
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):32;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;   
       
        
		if(URL::get('do_search') )
        {
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';			
            $from_day = Url::get('date_from')?Url::get('date_from'):date('d/m/Y');        
            $to_day = Url::get('date_to')?Url::get('date_to'):'';                         
            
			$cond = '1=1'
					.(URL::get('room_level_id')?' and reservation_room.room_level_id = '.URL::iget('room_level_id').'':'') 
					.(URL::get('customer_id')?' and customer_id=\''.URL::get('customer_id').'\'':'')					
                    .(URL::get('reservation_type_id')?' and customer_group.id = \''.URL::get('reservation_type_id').'\'':''); 	                                								            
            $reservation_cond = ' AND reservation_room.departure_time>=\''.Date_Time::to_orc_date($from_day).'\' AND reservation_room.arrival_time<=\''.Date_Time::to_orc_date($to_day).'\' ';			            
			if(Url::get('revenue')=='ALL'){
				$cond.=' and (reservation_room.status!=\'CANCEL\')';
                $reservation_cond.='';
			}elseif(Url::get('revenue')=='CHECKOUT'){
				$cond.=' and reservation_room.status=\'CHECKOUT\'';	
				$reservation_cond.=' and reservation_room.status=\'CHECKOUT\'';					
			}elseif(Url::get('revenue')=='BOOKED'){
				$cond.=' and reservation_room.status=\'BOOKED\'';	
				$reservation_cond.=' and reservation_room.status=\'BOOKED\'';					
			}            
            $cond .=Url::get('portal_id')? ' and reservation.portal_id = \''.Url::get('portal_id','').'\'':'';                            
            //System::debug($cond);
			$report = new Report;
			$sql = '
				select 
					reservation_room.id,
                    reservation_room.foc,
                    reservation_room.foc_all
				from 
					reservation_room
					inner join reservation on reservation_room.reservation_id=reservation.id
                    inner join customer on reservation.customer_id = customer.id
                    inner join customer_group on customer.group_id = customer_group.id                                            
                    left join room on reservation_room.room_id = room.id
                    left join room_level on reservation_room.room_level_id =  room_level.id
				where 
					'.$cond.$reservation_cond.'
                    and room_level.is_virtual = 0                    
                    and reservation_room.change_room_to_rr is null
                order by room.name
                    ';
                     //and room_level.is_virtual = 0
                    //order by TO_NUMBER(REPLACE(room.name,\'PA\',\'\'))
                    //order by room.name
			//System::debug($sql);
            $total_room = DB::fetch_all($sql);
           
            foreach($total_room as $key => $value)
            {
                if($value['foc']=='')
                {
                    $sql="
                        select sum(
                                case
                                when room_status.in_date = reservation_room.arrival_time
                                then 
                                    (case
                                     when RESERVATION_ROOM.net_price = 0
                                     then ((CHANGE_PRICE*(1-NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0)-NVL(RESERVATION_ROOM.REDUCE_AMOUNT,0))*(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))*(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)
                                     else
                                      ((((CHANGE_PRICE/(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))/(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0))*(1-NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0)-NVL(RESERVATION_ROOM.REDUCE_AMOUNT,0))*(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))*(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)
                                    end) 
                                else
                                    (case
                                     when RESERVATION_ROOM.net_price = 0
                                     then (CHANGE_PRICE*(1-NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0)*(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))*(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)
                                     else
                                      ((((CHANGE_PRICE/(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))/(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0))*(1-NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0))*(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))*(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)
                                    end)
                                end) as total
                        from ROOM_STATUS
                        inner join reservation_room on reservation_room.id = ROOM_STATUS.reservation_room_id
                        where 
                        reservation_room.id=".$key.(Url::get('date_from')?' and ROOM_STATUS.in_date>=\''.(Date_Time::to_orc_date($from_day)).'\' and ROOM_STATUS.in_date<=\''.(Date_Time::to_orc_date($to_day)).'\'':'');
                        $total_room[$key]["room_total"] = DB::fetch($sql,"total");
                        
                        if($value['foc_all']==1) 
                            $total_room[$key]["room_total"] = 0;
                        
                }
                else
                {
                    $total_room[$key]["room_total"]=0;
                }
            }
             
			$telephone_cond  = '1=1';
			$telephone_cond .= ' and telephone_report_daily.hdate>='.Date_Time::to_time($from_day);
			$telephone_cond .= ' and telephone_report_daily.hdate < '.(Date_Time::to_time($to_day)+(24*3600));
			$sql = '
				SELECT 
					reservation_room.id,
					SUM(telephone_report_daily.price) AS total
				  FROM 
					telephone_report_daily
                    inner join telephone_number on telephone_number.phone_number = telephone_report_daily.phone_number_id
                    inner join reservation_room on telephone_number.room_id = reservation_room.room_id
                    inner join reservation on reservation_room.reservation_id=reservation.id
                    inner join customer on reservation.customer_id=customer.id  
                    inner join customer_group on customer.group_id = customer_group.id                  
				  WHERE 
				   '.$cond.' and '.$telephone_cond.'
                   and reservation_room.foc_all =0
                  group by 
					reservation_room.id';
			$telephone_total = DB::fetch_all($sql);
            $telephone_total = array();
            /****restaurant****/
            //with room
			if(Url::get('revenue')=='ALL'){
				$restaurent_cond = ' and (bar_reservation.status = \'CHECKOUT\' or bar_reservation.status = \'CHECKIN\') ';
			}else{
				$restaurent_cond = ' and bar_reservation.status = \''.Url::get('revenue').'\' ';
			}
            //$restaurent_cond .= (URL::get('from_year')?' and bar_reservation.time_in>=\''.Date_Time::to_time($day.'/'.$month.'/'.$year).'\' and bar_reservation.time_out<=\''.(Date_Time::to_time($end_day.'/'.$end_month.'/'.$end_year)+(24*3600)).'\'':'');
			$restaurent_cond .= ' and bar_reservation.time_in>=\''.Date_Time::to_time($from_day).'\' and bar_reservation.time_out<=\''.(Date_Time::to_time($to_day)+(24*3600)).'\' ';
            $sql =	'
					select 
						reservation_room.id
						,sum(bar_reservation.total) as total						
					from 
						bar_reservation
						inner join reservation_room on reservation_room.id=bar_reservation.reservation_room_id
                        inner join reservation on reservation_room.reservation_id=reservation.id
                        inner join customer on reservation.customer_id = customer.id
                        inner join customer_group on customer.group_id = customer_group.id
					where 
						'.$cond.'
						'.$restaurent_cond.'
					group by 
						reservation_room.id	';
			$restaurant_total = DB::fetch_all($sql);
            
			//restaurant total = with hotel + walkin
			$sql = '
				SELECT 
					sum( BAR_RESERVATION.TOTAL) as id	
				  FROM 
					BAR_RESERVATION
				  WHERE 
				   	'.Date_Time::to_time($from_day).'<= TIME_OUT and TIME_OUT <'.(Date_Time::to_time($to_day)+(24*3600)).'
					'.$restaurent_cond.'
			';
			$restaurant_total_with_all = DB::fetch($sql);
            
            /****minibar****/
			$sql = 	'
						select 
							reservation_room.id
							,sum(housekeeping_invoice.total) as minibar_total
						from 
							housekeeping_invoice
							inner join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id
                            inner join reservation on reservation_room.reservation_id=reservation.id
                            inner join customer on reservation.customer_id = customer.id
                            inner join customer_group on customer.group_id = customer_group.id
                            
						where '.$cond.$reservation_cond.' and housekeeping_invoice.type = \'MINIBAR\''
                            .(URL::get('date_from')?' and housekeeping_invoice.time>=\''.Date_Time::to_time($from_day).'\' and housekeeping_invoice.time<=\''.(Date_Time::to_time($to_day)+(24*3600)).'\'':'').'
						group by
							reservation_room.id';
			$minibar = DB::fetch_all($sql);
            
            /****giatla****/
			$sql = 	'
						select 
							reservation_room.id
							,sum(housekeeping_invoice.total) as laundry_total
						from 
							housekeeping_invoice
							inner join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id
                            inner join reservation on reservation_room.reservation_id=reservation.id
                            inner join customer on reservation.customer_id = customer.id
                            inner join customer_group on customer.group_id = customer_group.id
						where 
							'.$cond.$reservation_cond.' and housekeeping_invoice.type = \'LAUNDRY\''
                            .(URL::get('date_from')?' and housekeeping_invoice.time>=\''.Date_Time::to_time($from_day).'\' and housekeeping_invoice.time<=\''.(Date_Time::to_time($to_day)+(24*3600)).'\'':'').'
						group by
							reservation_room.id';
			$laundry = DB::fetch_all($sql);
            
            /****lam hong do trong phong****/
			$sql = 	'
						select 
							reservation_room.id
							,sum(housekeeping_invoice.total) as total
						from 
							housekeeping_invoice
							inner join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id
                            inner join reservation on reservation_room.reservation_id=reservation.id
                            inner join customer on reservation.customer_id = customer.id
                            inner join customer_group on customer.group_id = customer_group.id
						where 
							'.$cond.$reservation_cond.' and housekeeping_invoice.type = \'EQUIP\''
                            .(URL::get('date_from')?' and housekeeping_invoice.time>=\''.Date_Time::to_time($from_day).'\' and housekeeping_invoice.time<=\''.(Date_Time::to_time($to_day)+(24*3600)).'\'':'').'
						    and reservation_room.foc_all =0
						group by
							reservation_room.id';
			$equip = DB::fetch_all($sql);
            
            /****dich vu khac cung****/
			$sql = 	'
						select 
							reservation_room.id
							,sum(RESERVATION_ROOM_SERVICE.AMOUNT) as service_other_total
						from 
							RESERVATION_ROOM_SERVICE
							inner join service  on reservation_room_service.service_id = service.id  and service.type = \'SERVICE\'
							inner join reservation_room on reservation_room.id=RESERVATION_ROOM_SERVICE.RESERVATION_ROOM_ID
                            inner join reservation on reservation_room.reservation_id=reservation.id
                            inner join customer on reservation.customer_id = customer.id
                            inner join customer_group on customer.group_id = customer_group.id
						where 
							'.$cond.$reservation_cond.'
                            and reservation_room.foc_all =0
						group by
							reservation_room.id';
			$service_other = DB::fetch_all($sql); 
            
            /****service room****/
			$sql_service  = '
						select 
							reservation_room.id
							,sum(RESERVATION_ROOM_SERVICE.AMOUNT) as service_other_total
						from 
							RESERVATION_ROOM_SERVICE
							inner join service  on reservation_room_service.service_id = service.id and service.type = \'ROOM\'
							inner join reservation_room on reservation_room.id=RESERVATION_ROOM_SERVICE.RESERVATION_ROOM_ID
                            inner join reservation on reservation_room.reservation_id=reservation.id
                            inner join customer on reservation.customer_id = customer.id
                            inner join customer_group on customer.group_id = customer_group.id
						where 
							'.$cond.$reservation_cond.'
                            and reservation_room.foc_all =0
						group by
							reservation_room.id';
			$room_service = DB::fetch_all($sql_service);
            
            /****dich vu mo rong ****/
            //dich vu mo rong tru late checkin, extra bed, baby cot
            //giap.ln: loc ra tat ca dich vu khong chua liloei va cac dich vu chi chua liloei de cong vao tien phong
        $exception_liloei =" AND extra_service.code != 'LATE_CHECKOUT' AND extra_service.code!='LATE_CHECKIN' AND extra_service.code <>'EARLY_CHECKIN' AND extra_service.code <>'EXTRA_BED' AND extra_service.code <>'BABY_COT' AND extra_service.code <>'extraperson' AND extra_service.code <>'NS'";
            $liloei =" AND ((extra_service.code='LATE_CHECKOUT' OR extra_service.code='EARLY_CHECKIN' OR extra_service.code ='EXTRA_BED' OR extra_service.code ='BABY_COT' OR extra_service.code ='NS' OR extra_service.code ='extraperson') and room_level.is_virtual = 0) ";
            $li =" and extra_service.code='LATE_CHECKIN' ";
            $sql = 'select 
                        extra_service_invoice.reservation_room_id || \'_\' || extra_service_invoice.payment_type as id,
                        extra_service_invoice.reservation_room_id,
                        extra_service_invoice.payment_type,
                        sum( CASE
                                WHEN extra_service_invoice.net_price = 0
                                THEN
                                        (extra_service_invoice_detail.price 
                                        * 
                                        (extra_service_invoice_detail.quantity + nvl(extra_service_invoice_detail.change_quantity,0)) 
                                        * 
                                        (1 + NVL(extra_service_invoice.service_rate,0)/100.0) 
                                        * 
                                        (1 + NVL(extra_service_invoice.tax_rate,0)/100.0))
                                ELSE
                                    (extra_service_invoice_detail.price 
                                        * 
                                        (extra_service_invoice_detail.quantity +nvl(extra_service_invoice_detail.change_quantity,0)))
                                END
                                        
                        ) as total
                        --sum(extra_service_invoice.total_amount) as total
                    from extra_service_invoice
                        inner join extra_service_invoice_detail on extra_service_invoice.id=extra_service_invoice_detail.invoice_id
                        inner join reservation_room on reservation_room.id=extra_service_invoice.reservation_room_id
                        inner join reservation on reservation_room.reservation_id=reservation.id
                        inner join extra_service on extra_service.id=extra_service_invoice_detail.service_id
                        inner join customer on reservation.customer_id = customer.id
                        inner join customer_group on customer.group_id = customer_group.id
                    where 
                        reservation_room.foc_all =0 and 
                        '.$cond.$exception_liloei.'
                        and '.(URL::get('date_from')?' extra_service_invoice_detail.in_date>=\''.(Date_Time::to_orc_date($from_day)).'\' and extra_service_invoice_detail.in_date<=\''.(Date_Time::to_orc_date($to_day)).'\'':'1=1').'
                    group by
						extra_service_invoice.reservation_room_id,
                        extra_service_invoice.payment_type';
            
            $extra_services = DB::fetch_all($sql);
            /** Kimtan viet rieng cai nay vi truong hop cung reservation_room_id ma khac payment_type thi de nhu tren se thieu*/
            $sql_eiloli='select 
                        extra_service_invoice.reservation_room_id as id,
                        extra_service_invoice.reservation_room_id,
                        sum( CASE
                                WHEN extra_service_invoice.net_price = 0
                                THEN
                                        (extra_service_invoice_detail.price 
                                        * 
                                        (extra_service_invoice_detail.quantity + nvl(extra_service_invoice_detail.change_quantity,0))
                                        * 
                                        (1 + NVL(extra_service_invoice.service_rate,0)/100.0) 
                                        * 
                                        (1 + NVL(extra_service_invoice.tax_rate,0)/100.0))
                                ELSE
                                    (extra_service_invoice_detail.price 
                                        * 
                                        (extra_service_invoice_detail.quantity + nvl(extra_service_invoice_detail.change_quantity,0)))
                                END
                                        
                        ) as total
                        --sum(extra_service_invoice.total_amount) as total
                    from extra_service_invoice
                        inner join extra_service_invoice_detail on extra_service_invoice.id=extra_service_invoice_detail.invoice_id
                        inner join reservation_room on reservation_room.id=extra_service_invoice.reservation_room_id
                        inner join reservation on reservation_room.reservation_id=reservation.id
                        inner join extra_service on extra_service.id=extra_service_invoice_detail.service_id
                        inner join customer on reservation.customer_id = customer.id
                        inner join customer_group on customer.group_id = customer_group.id
                        left join room_level on reservation_room.room_level_id =  room_level.id
                    where 
                        reservation_room.foc_all =0 and 
                        '.$cond.$liloei.'
                        and '.(URL::get('date_from')?' extra_service_invoice_detail.in_date>=\''.(Date_Time::to_orc_date($from_day)).'\' and extra_service_invoice_detail.in_date<=\''.(Date_Time::to_orc_date($to_day)).'\'':'1=1').'
                    group by
						extra_service_invoice.reservation_room_id
                        ';
            $extra_services_liloei = DB::fetch_all($sql_eiloli);
            $sql_li='select 
                        extra_service_invoice.reservation_room_id as id,
                        extra_service_invoice.reservation_room_id,
                        sum( CASE
                                WHEN extra_service_invoice.net_price = 0
                                THEN
                                        (extra_service_invoice_detail.price 
                                        * 
                                        (extra_service_invoice_detail.quantity + nvl(extra_service_invoice_detail.change_quantity,0))
                                        * 
                                        (1 + NVL(extra_service_invoice.service_rate,0)/100.0) 
                                        * 
                                        (1 + NVL(extra_service_invoice.tax_rate,0)/100.0))
                                ELSE
                                    (extra_service_invoice_detail.price 
                                        * 
                                        (extra_service_invoice_detail.quantity + nvl(extra_service_invoice_detail.change_quantity,0)))
                                END
                                        
                        ) as total
                        --sum(extra_service_invoice.total_amount) as total
                    from extra_service_invoice
                        inner join extra_service_invoice_detail on extra_service_invoice.id=extra_service_invoice_detail.invoice_id
                        inner join reservation_room on reservation_room.id=extra_service_invoice.reservation_room_id
                        inner join reservation on reservation_room.reservation_id=reservation.id
                        inner join extra_service on extra_service.id=extra_service_invoice_detail.service_id
                        left outer join customer on reservation.customer_id=customer.id
                        left outer join customer_group on customer_group.id=customer.group_id
                        left join room_level on reservation_room.room_level_id =  room_level.id
                    where 
                        reservation_room.foc_all =0 and 
                        '.$cond.$li.'
                        and '.(URL::get('date_from')?' extra_service_invoice_detail.in_date>=\''.(Date_Time::to_orc_date($from_day)).'\' and extra_service_invoice_detail.in_date<=\''.(Date_Time::to_orc_date($to_day)).'\'':'1=1').'
                    group by
						extra_service_invoice.reservation_room_id
                        ';
            $li_arr = DB::fetch_all($sql_li);
            //System::debug($extra_services_liloei);
            /** Kimtan viet rieng cai nay vi truong hop cung reservation_room_id ma khac payment_type thi de nhu tren se thieu*/
            //end giap.ln
            /****massage****/
			$massage_cond  = '1=1';
			if(Url::get('revenue')!='ALL'){
				$massage_cond .= ' and massage_product_consumed.status=\''.Url::get('revenue').'\'';
			}
			$massage_cond .= ' and massage_product_consumed.time_out>='.Date_Time::to_time($from_day);
			$massage_cond .= ' and massage_product_consumed.time_out < '.(Date_Time::to_time($to_day)+(24*3600));
            
            $sql =	'
					select 
						reservation_room.id,
						sum(massage_product_consumed.price*massage_product_consumed.quantity) as total						
					from 
						massage_product_consumed
                        inner join massage_room on massage_product_consumed.room_id = massage_room.id
						inner join reservation_room on reservation_room.id=massage_product_consumed.reservation_room_id
                        inner join reservation on reservation_room.reservation_id=reservation.id
                        inner join customer on reservation.customer_id = customer.id
                        inner join customer_group on customer.group_id = customer_group.id
					where 
						'.$cond.' and '.$massage_cond.'
                        and reservation_room.foc_all =0
					group by 
						reservation_room.id	';		
			$massage = DB::fetch_all($sql);
            
            /****tennis****/
			$tennis_cond  = '1=1';
			if(Url::get('revenue')!='ALL'){
				$tennis_cond .= ' and tennis_reservation_court.status=\''.Url::get('revenue').'\'';
			}	
			
            $tennis_cond .= ' and tennis_reservation_court.time_out>='.Date_Time::to_time($from_day);
			$tennis_cond .= ' and tennis_reservation_court.time_out < '.(Date_Time::to_time($to_day)+(24*3600));
			
            $sql = '
				SELECT 
					reservation_room.id,
					sum(tennis_reservation_court.total_amount) as total
				  FROM 
					tennis_reservation_court
					inner join tennis_court on tennis_reservation_court.court_id = tennis_court.id
					left outer join tennis_reservation on tennis_reservation_court.reservation_id = tennis_reservation.id
					inner join reservation_room on reservation_room.id = tennis_reservation_court.hotel_reservation_room_id
                    inner join reservation on reservation_room.reservation_id=reservation.id
                    inner join customer on reservation.customer_id = customer.id
                    inner join customer_group on customer.group_id = customer_group.id                    
				  WHERE 
				   '.$cond.' and '.$tennis_cond.'
                  group by 
						reservation_room.id';
			$tennis = DB::fetch_all($sql);
            
            /****swimming****/
			$swimming_cond  = '1=1';
			if(Url::get('revenue')!='ALL'){
				$swimming_cond .= ' and swimming_pool_reservation_pool.status=\''.Url::get('revenue').'\'';
			}	
			$swimming_cond .= ' and swimming_pool_reservation_pool.time_out>='.Date_Time::to_time($from_day);
			$swimming_cond .= ' and swimming_pool_reservation_pool.time_out < '.(Date_Time::to_time($to_day)+(24*3600));
			$sql = '
				SELECT 
					 reservation_room.id,
					 sum(swimming_pool_reservation_pool.total_amount) as total
				  FROM 
					swimming_pool_reservation_pool
					inner join swimming_pool on swimming_pool_reservation_pool.swimming_pool_id = swimming_pool.id
					left outer join swimming_pool_reservation on swimming_pool_reservation_pool.reservation_id = swimming_pool_reservation.id
					inner join reservation_room on reservation_room.id = swimming_pool_reservation_pool.hotel_reservation_room_id
                    inner join reservation on reservation_room.reservation_id=reservation.id
                    inner join customer on reservation.customer_id = customer.id
                    inner join customer_group on customer.group_id = customer_group.id
				  WHERE 
				   '.$cond.' and '.$swimming_cond.'
                  group by 
						reservation_room.id';
			$swimming = DB::fetch_all($sql);
            
            /****karaoke****/
			$karaoke_cond  = '1=1';
			if(Url::get('revenue')!='ALL'){
				$karaoke_cond .= ' and karaoke_reservation.status=\''.Url::get('revenue').'\'';
			}	
			$karaoke_cond .= ' and karaoke_reservation.time_out>='.Date_Time::to_time($from_day);
			$karaoke_cond .= ' and karaoke_reservation.time_out < '.(Date_Time::to_time($to_day)+(24*3600));
			$sql = '
				SELECT 
					reservation_room.id,
					sum(karaoke_reservation.amount_pay_with_room) as total
				  FROM 
					karaoke_reservation
					inner join reservation_room on reservation_room.id=karaoke_reservation.reservation_room_id
                    inner join reservation on reservation_room.reservation_id=reservation.id
                    inner join customer on reservation.customer_id = customer.id
                    inner join customer_group on customer.group_id = customer_group.id
				  WHERE 
				   '.$cond.' and '.$karaoke_cond.'
                   and reservation_room.foc_all =0
                  group by 
						reservation_room.id';
            //System::debug($sql);
			$karaoke = DB::fetch_all($sql);
            /****ticket****/
            $ticket_cond  = '1=1';
			$ticket_cond .= ' and ticket_reservation.time>='.Date_Time::to_time($from_day);
			$ticket_cond .= ' and ticket_reservation.time < '.(Date_Time::to_time($to_day)+(24*3600));
			$sql = '
				SELECT 
					reservation_room.id,
					sum(nvl(ticket_reservation.amount_pay_with_room,0)) as total
				  FROM 
					ticket_reservation
					inner join reservation_room on reservation_room.id=ticket_reservation.reservation_room_id
                    inner join reservation on reservation_room.reservation_id=reservation.id
                    inner join customer on reservation.customer_id = customer.id
                    inner join customer_group on customer.group_id = customer_group.id
				  WHERE 
				   '.$cond.' and '.$ticket_cond.'
                   and reservation_room.foc_all =0
                  group by 
						reservation_room.id';
			$ticket = DB::fetch_all($sql);
            /****vending****/
            $ve_cond  = '1=1';
			$ve_cond .= ' and ve_reservation.time>='.Date_Time::to_time($from_day);
			$ve_cond .= ' and ve_reservation.time < '.(Date_Time::to_time($to_day)+(24*3600));
			$sql = '
				SELECT 
					reservation_room.id,
					sum(nvl(ve_reservation.amount_pay_with_room,0)) as total
				  FROM 
					ve_reservation
					inner join reservation_room on reservation_room.id=ve_reservation.reservation_room_id
                    inner join reservation on reservation_room.reservation_id=reservation.id
                    inner join customer on reservation.customer_id = customer.id
                    inner join customer_group on customer.group_id = customer_group.id
				  WHERE 
				   '.$cond.' and '.$ve_cond.'
                   and reservation_room.foc_all =0
                  group by 
						reservation_room.id';
			$vending = DB::fetch_all($sql);
            //System::debug($vending);
            /****for_hotel_5_star not by reservation****/
			$sql = '
				SELECT 
					 sum(massage_product_consumed.price*massage_product_consumed.quantity) as id
				  FROM 
					massage_product_consumed
					inner join massage_room on massage_product_consumed.room_id = massage_room.id
				  WHERE 
				   '.$massage_cond.'
			';
			$extra_massage = DB::fetch($sql);
			
			$sql = '
				SELECT 
					 sum(tennis_reservation_court.total_amount) as id
				  FROM 
					tennis_reservation_court
					inner join tennis_court on tennis_reservation_court.court_id = tennis_court.id
					left outer join tennis_reservation on tennis_reservation_court.reservation_id = tennis_reservation.id
				  WHERE 
				   '.$tennis_cond.'
			';
			$extra_tennis = DB::fetch($sql);
			
			$sql = '
				SELECT 
					 sum(swimming_pool_reservation_pool.total_amount) as id
				  FROM 
					swimming_pool_reservation_pool
					inner join swimming_pool on swimming_pool_reservation_pool.swimming_pool_id = swimming_pool.id
					left outer join swimming_pool_reservation on swimming_pool_reservation_pool.reservation_id = swimming_pool_reservation.id
				  WHERE 
				   '.$swimming_cond.'
			';			
			$extra_swimming = DB::fetch($sql);
			
			$sql = '
				SELECT 
					sum(karaoke_reservation.total) as id
				  FROM 
					karaoke_reservation
				  WHERE 
				   '.$karaoke_cond.'
			';
			$extra_karaoke = DB::fetch($sql);
            
            $sql = '
				SELECT 
					sum(nvl(ticket_reservation.amount_pay_with_room,0)) as id
				  FROM 
					ticket_reservation
					inner join ticket_invoice on ticket_invoice.ticket_reservation_id = ticket_reservation.id
				  WHERE 
				   '.$ticket_cond.'
			';
			$extra_ticket = DB::fetch($sql);
            
            $sql = '
				SELECT 
					sum(nvl(ve_reservation.amount_pay_with_room,0)) as id
				  FROM 
					ve_reservation
					inner join ve_reservation_product on ve_reservation_product.bar_reservation_id = ve_reservation.id
				  WHERE 
				   '.$ve_cond.'
			';
			$extra_vending = DB::fetch($sql);
            
            $total_not_by_reservation = array('massage'=>0,'tennis'=>0,'swimming'=>0,'karaoke'=>0,'restaurant_total_with_all'=>$restaurant_total_with_all);
			isset($extra_massage['id'])?$total_not_by_reservation['massage'] = $extra_massage['id']:'';
			isset($extra_tennis['id'])?$total_not_by_reservation['tennis'] = $extra_tennis['id']:'';
			isset($extra_swimming['id'])?$total_not_by_reservation['swimming'] = $extra_swimming['id']:'';
			isset($extra_karaoke['id'])?$total_not_by_reservation['karaoke'] = $extra_karaoke['id']:'';
            isset($extra_ticket['id'])?$total_not_by_reservation['ticket'] = $extra_ticket['id']:'';
            isset($extra_vending['id'])?$total_not_by_reservation['vending'] = $extra_vending['id']:'';
            
            $sql ='
				select
					currency_id as id
					,type
					,exchange
					,sum(amount) as amount
				from
					pay_by_currency
					inner join reservation_room on pay_by_currency.bill_id = reservation_room.id
					inner join reservation on reservation.id = reservation_room.reservation_id
					inner join currency on currency.id = pay_by_currency.currency_id
                    inner join customer on reservation.customer_id = customer.id
                    inner join customer_group on customer.group_id = customer_group.id
				where
					type=\'RESERVATION\'
					and '.$cond.'
				group by
					currency_id
					,exchange
					,type	
			';
			$pay_by_currency = DB::fetch_all($sql);
            
            $report->items = array();
            //room charge
            foreach($total_room as $key => $value)
            {
                if(!isset($report->items[$key]))
                $report->items[$key] = array();
                $report->items[$key]['room_total'] = isset($value['room_total'])?$value['room_total']:0;
                //system::debug($report->items[$key]['room_total']);
            }
            
            //telephone
            foreach($telephone_total as $key => $value)
            {
                if(!isset($report->items[$key]))
                    $report->items[$key] = array();
                    
                $report->items[$key]['telephone_total'] = isset($value['total'])?$value['total']:0;
            }
            //restaurant
            foreach($restaurant_total as $key => $value)
            {
                if(!isset($report->items[$key]))
                    $report->items[$key] = array();
                    
                $report->items[$key]['restaurant_total'] = isset($value['total'])?$value['total']:0;
            }
            //minibar
            foreach($minibar as $key => $value)
            {
                if(!isset($report->items[$key]))
                    $report->items[$key] = array();
                    
                $report->items[$key]['minibar_total'] = isset($value['minibar_total'])?$value['minibar_total']:0;
            }
            //laundry
            foreach($laundry as $key => $value)
            {
                if(!isset($report->items[$key]))
                    $report->items[$key] = array();
                    
                $report->items[$key]['laundry_total'] = isset($value['laundry_total'])?$value['laundry_total']:0;
            }
            //equip
            foreach($equip as $key => $value)
            {
                if(!isset($report->items[$key]))
                    $report->items[$key] = array();
                    
                $report->items[$key]['equip_total'] = isset($value['total'])?$value['total']:0;
            }
            //service_other
            foreach($service_other as $key => $value)
            {
                if(!isset($report->items[$key]))
                    $report->items[$key] = array();
                    
                $report->items[$key]['service_other_total'] = isset($value['service_other_total'])?$value['service_other_total']:0;
            }
            //room_service
            foreach($room_service as $key => $value)
            {
                if(!isset($report->items[$key]))
                    $report->items[$key] = array();
                    
                $report->items[$key]['room_service_total'] = isset($value['service_other_total'])?$value['service_other_total']:0;
            }
            //extra_service
//            system::debug($extra_services);
            foreach($extra_services as $key => $value)
            {
                if(!isset($report->items[$value['reservation_room_id']]))
                    $report->items[$value['reservation_room_id']] = array();

                if($value['payment_type'] == 'ROOM')
                    $report->items[$value['reservation_room_id']]['extra_service_total_with_room'] = isset($value['total'])?$value['total']:0;
                else
                {
                    $report->items[$value['reservation_room_id']]['extra_service_total_with_service'] = isset($value['total'])?$value['total']:0;
                }
                    
            }
            //giap.ln tin tien phong voi extra services la liloei 
            /** Daund them vao de fix truong hop Undefined index extra_service_total_with_room */
            foreach($report->items as $key => $value)
            {
                if(!isset($report->items[$key]['extra_service_total_with_room']))
                {
                    $report->items[$key]['extra_service_total_with_room'] = 0;                    
                }
            }
            /** Daund them vao de fix truong hop Undefined index extra_service_total_with_room */
            foreach($extra_services_liloei as $key=>$value)
            {
                if(!isset($report->items[$value['reservation_room_id']]))
                    $report->items[$value['reservation_room_id']] = array();
                if(isset($report->items[$value['reservation_room_id']]['extra_service_total_with_room'])){
                 $report->items[$value['reservation_room_id']]['extra_service_total_with_room'] +=isset($value['total'])?$value['total']:0;
                }
            }
            //end giap.ln
            //System::debug($li_arr);
            
            foreach($li_arr as $key=>$value)
            {
                if(!isset($report->items[$value['reservation_room_id']]))
                {
                    $report->items[$value['reservation_room_id']] = array();
                    $report->items[$value['reservation_room_id']]['room_total'] = 0;
                }
                else
                {
                    if(!isset($report->items[$value['reservation_room_id']]['room_total']))
                    {
                        $report->items[$value['reservation_room_id']]['room_total'] = 0;
                    }
                }
                $report->items[$value['reservation_room_id']]['room_total'] +=isset($value['total'])?$value['total']:0;
            }
            //massage
            foreach($massage as $key => $value)
            {
                if(!isset($report->items[$key]))
                    $report->items[$key] = array();
                    
                $report->items[$key]['massage_total'] = isset($value['total'])?$value['total']:0;
            }
            //tennis
            foreach($tennis as $key => $value)
            {
                if(!isset($report->items[$key]))
                    $report->items[$key] = array();
                    
                $report->items[$key]['tennis_total'] = isset($value['total'])?$value['total']:0;
            }
            //swimming
            foreach($swimming as $key => $value)
            {
                if(!isset($report->items[$key]))
                    $report->items[$key] = array();
                    
                $report->items[$key]['swimming_total'] = isset($value['total'])?$value['total']:0;
            }
            //karaoke
            foreach($karaoke as $key => $value)
            {
                if(!isset($report->items[$key]))
                    $report->items[$key] = array();
                    
                $report->items[$key]['karaoke_total'] = isset($value['total'])?$value['total']:0;
            }
            //ticket
            foreach($ticket as $key => $value)
            {
                if(!isset($report->items[$key]))
                    $report->items[$key] = array();
                    
                $report->items[$key]['ticket_total'] = isset($value['total'])?$value['total']:0;
            }
            //vending
            foreach($vending as $key => $value)
            {
                if(!isset($report->items[$key]))
                    $report->items[$key] = array();
                    
                $report->items[$key]['vending_total'] = isset($value['total'])?$value['total']:0;
            }
            //System::debug($report->items);
            $i=1;
            
            foreach($report->items as $key => $value)
            {
               
                $report->items[$key]['stt'] = $i++;
                
                if(!isset($report->items[$key]['room_total']))
                    $report->items[$key]['room_total'] = 0;
                if(!isset($report->items[$key]['restaurant_total']))
                    $report->items[$key]['restaurant_total'] = 0;
                if(!isset($report->items[$key]['minibar_total']))
                    $report->items[$key]['minibar_total'] = 0;
                if(!isset($report->items[$key]['laundry_total']))
                    $report->items[$key]['laundry_total'] = 0;
                if(!isset($report->items[$key]['equip_total']))
                    $report->items[$key]['equip_total'] = 0;
                if(!isset($report->items[$key]['telephone_total']))
                    $report->items[$key]['telephone_total'] = 0;
                if(!isset($report->items[$key]['service_other_total']))
                    $report->items[$key]['service_other_total'] = 0;
                if(!isset($report->items[$key]['room_service_total']))
                    $report->items[$key]['room_service_total'] = 0;
                if(!isset($report->items[$key]['extra_service_total_with_room']))
                    $report->items[$key]['extra_service_total_with_room'] = 0;
                if(!isset($report->items[$key]['extra_service_total_with_service']))
                    $report->items[$key]['extra_service_total_with_service'] = 0;
                if(!isset($report->items[$key]['massage_total']))
                    $report->items[$key]['massage_total'] = 0;
                if(!isset($report->items[$key]['tennis_total']))
                    $report->items[$key]['tennis_total'] = 0;
                if(!isset($report->items[$key]['swimming_total']))
                    $report->items[$key]['swimming_total'] = 0;
                if(!isset($report->items[$key]['karaoke_total']))
                    $report->items[$key]['karaoke_total'] = 0;
                if(!isset($report->items[$key]['ticket_total']))
                    $report->items[$key]['ticket_total'] = 0;
                if(!isset($report->items[$key]['vending_total']))
                    $report->items[$key]['vending_total'] = 0;     
                $report->items[$key]['housekeeping_total'] = $report->items[$key]['minibar_total']
                                                            + $report->items[$key]['laundry_total'] 
                                                            + $report->items[$key]['equip_total'];
                $report->items[$key]['extra_service_total'] = $report->items[$key]['extra_service_total_with_room']
                                                             + $report->items[$key]['extra_service_total_with_service'];
                $report->items[$key]['room_total'] += $report->items[$key]['room_service_total'];
                                                    //+ $report->items[$key]['extra_service_total_with_room'];
                 
                $sql = "
                select 
					reservation_room.id
					,reservation_room.note
					,reservation_room.traveller_id
					,reservation_room.reservation_id
					,reservation_room.arrival_time
					,reservation_room.departure_time
                    ,reservation_room.foc
					,concat(concat(traveller.first_name,'  '),traveller.last_name) as customer_stay
					,customer.name as customer_name
					,room.name as room_name
                    ,room.id as room_id
					,tour.name as tour_name
					,room_level.name as room_level
                                        ,room_level.brief_name as brief_name                                       
				from 
					reservation_room
					inner join reservation on reservation_room.reservation_id=reservation.id
					inner join room_level on reservation_room.room_level_id = room_level.id
					left outer join room on room.id=reservation_room.room_id
					left outer join customer on reservation.customer_id=customer.id
					left outer join traveller on reservation_room.traveller_id=traveller.id
					left outer join tour on reservation.tour_id = tour.id
				where reservation_room.id=".$key;
                
                $detail = DB::fetch_all($sql);
                $report->items[$key] += $detail[$key];
                
                if($report->items[$key]['foc'])
                {
                    //$report->items[$key]['room_total'] = 0;
                    //$report->items[$key]['room_service_total'] = 0;
                    //$report->items[$key]['extra_service_total_with_room'] = 0;
                }
                
                $report->items[$key]['total'] = $report->items[$key]['room_total'] 
                                                + $report->items[$key]['restaurant_total']
                                                + $report->items[$key]['housekeeping_total']
                                                + $report->items[$key]['telephone_total']
				                                + $report->items[$key]['massage_total']
                                                + $report->items[$key]['tennis_total']
				                                + $report->items[$key]['swimming_total']
                                                + $report->items[$key]['karaoke_total']
                                                + $report->items[$key]['ticket_total']
                                                + $report->items[$key]['vending_total']
												+ $report->items[$key]['service_other_total']
												+ $report->items[$key]['extra_service_total_with_service'];
             //System::debug($report->items); 	
            }
			$this->print_all_pages($report,$pay_by_currency,$total_not_by_reservation);
		}
        else
        {
            $_REQUEST['date_from'] = date('d/m/Y');
            $_REQUEST['date_to'] = date('d/m/Y');
			$room_level_cond = '1=1';
			if(Url::get('portal_id')){
				$room_level_cond = ' room_level.portal_id = \''.Url::sget('portal_id').'\'';
			}else{
				$room_level_cond = ' room_level.portal_id = \''.PORTAL_ID.'\'';
			}
            //$potal = String::get_list(Portal::get_portal_list());
            //System::debug($potal);            
		$report_name = Portal::language('hotel_revenue_report');
		if(Url::get('type')==2){
            $report_name = Portal::language('room_revenue_report');
		}elseif(Url::get('type')==3){
            $report_name = Portal::language('reception_report');
		}
            $this->map['room_level_id_list'] = array(''=>Portal::language('all'))+String::get_list(DB::fetch_all('select id, name from room_level where '.$room_level_cond.' order by name'));
		    $this->parse_layout('search',$this->map +
				array(
				'portal_id_list'=>array(''=>Portal::language('all')) + String::get_list(Portal::get_portal_list()),
				'reservation_type_id_list' => array(''=>Portal::language('all'))+String::get_list(DB::fetch_all('select id,name from customer_group order by id')),
				'report_name'=>$report_name
                )
			);	
		}			
	}

	function print_all_pages(&$report,$pay_by_currency,$extra_service){
		$from_day = Url::get('date_from');
        $to_day = Url::get('date_to');
        $count = 0;
		$total_page = 1;
		$pages = array();
		foreach($report->items as $key=>$item){
			if($count>=$this->map['line_per_page']){
				$count = 0;
				$total_page++;
			}
            $item['my_spa'] = 0;
			$pages[$total_page][$key] = $item;
			$count++;
		}		
		if(sizeof($pages)>0){
			$this->group_function_params = array(
				'total'=>0,
				'room_total'=>0,
				'restaurant_total'=>0,
				'minibar_total'=>0,
				'laundry_total'=>0,
				'equip_total'=>0,
				'service_other_total'=>0,
				'room_service_total'=>0,
				'extra_service_total_with_room'=>0,
				'extra_service_total_with_service'=>0,
				'housekeeping_total'=>0,
                'telephone_total'=>0,
				'massage_total'=>0,
				'tennis_total'=>0,
				'swimming_total'=>0,
				'my_spa_total'=>0,
                'karaoke_total'=>0,
                'ticket_total'=>0,
                'vending_total'=>0
			);
            $this->map['real_total_page']=count($pages);
            $this->map['real_page_no'] = 1;  
			foreach($pages as $page_no=>$page)
            {
    			$this->print_page($page, $report, $page_no,$total_page,$pay_by_currency,$extra_service);
                $this->map['real_page_no'] ++;
			}
		}else{					
			$this->parse_layout('header',
			get_time_parameters()+
				array(
                    'to_date'=>$to_day,
                    'from_date'=>$from_day,
					'page_no'=>0,
					'total_page'=>0,
					'hotel_name'=>Url::get('portal_id')?DB::fetch('SELECT id,name_1 FROM party WHERE user_id = \''.PORTAL_ID.'\'','name_1'):HOTEL_NAME
				)+$this->map
			);
			$this->parse_layout('no_record');
			$this->parse_layout('footer',array(
				'page_no'=>0,
				'total_page'=>0
			)+$this->map);
		}
	}
	function print_page($items, &$report, $page_no,$total_page,$pay_by_currency,$extra_service){
	    $from_day = Url::get('date_from');
        $to_day = Url::get('date_to');
		$last_group_function_params = $this->group_function_params;
		foreach($items as $item)
        {
			if($temp=System::calculate_number($item['total'])){
				$this->group_function_params['total'] += $temp;
			}
			if($temp=System::calculate_number($item['room_total'])){
				$this->group_function_params['room_total'] += $temp;
			}
			if($temp=System::calculate_number($item['restaurant_total'])){
				$this->group_function_params['restaurant_total'] += $temp;
			}
			//for 5 star
			if($temp=System::calculate_number($item['massage_total'])){
				$this->group_function_params['massage_total'] += $temp;
			}
			if($temp=System::calculate_number($item['tennis_total'])){
				$this->group_function_params['tennis_total'] += $temp;
			}
			if($temp=System::calculate_number($item['swimming_total'])){
				$this->group_function_params['swimming_total'] += $temp;
			}	
			if($temp=System::calculate_number($item['karaoke_total'])){
				$this->group_function_params['karaoke_total'] += $temp;
			}
            if($temp=System::calculate_number($item['ticket_total'])){
				$this->group_function_params['ticket_total'] += $temp;
			}	
             if($temp=System::calculate_number($item['vending_total'])){
				$this->group_function_params['vending_total'] += $temp;
			}	
			//	
			if($temp=System::calculate_number($item['minibar_total'])){
				$this->group_function_params['minibar_total'] += $temp;
			}
			if($temp=System::calculate_number($item['laundry_total'])){
				$this->group_function_params['laundry_total'] += $temp;
			}
			if($temp=System::calculate_number($item['equip_total'])){
				$this->group_function_params['equip_total'] += $temp;
			}
			if($temp=System::calculate_number($item['housekeeping_total'])){
				$this->group_function_params['housekeeping_total'] += $temp;
			}
			if($temp=System::calculate_number($item['telephone_total'])){
				$this->group_function_params['telephone_total'] += $temp;
			}
			if($temp=System::calculate_number($item['extra_service_total_with_room'])){
				$this->group_function_params['extra_service_total_with_room'] += $temp;
			}
			if($temp=System::calculate_number($item['extra_service_total_with_service'])){
				$this->group_function_params['extra_service_total_with_service'] += $temp;
			}
			if($temp=System::calculate_number($item['room_service_total'])){ 
				$this->group_function_params['room_service_total'] += $temp;
			}			
			if($temp=System::calculate_number($item['service_other_total'])){
				$this->group_function_params['service_other_total'] += $temp;
			}			
		}
		//tat ca cac dich vu them 
		$old_extra_service = $extra_service;		
		$extra_service['massage']  =  ($extra_service['massage'] - $this->group_function_params['massage_total']);
		$extra_service['tennis']  =  ($extra_service['tennis'] - $this->group_function_params['tennis_total']);
		$extra_service['swimming']  =  ($extra_service['swimming'] - $this->group_function_params['swimming_total']);
		$extra_service['karaoke']  =  ($extra_service['karaoke'] - $this->group_function_params['karaoke_total']);
        //$extra_service['ticket']  =  ($extra_service['ticket'] - $this->group_function_params['ticket_total']);	
        //$extra_service['vending']  =  ($extra_service['vending'] - $this->group_function_params['vending_total']);		
		//echo Url::get('portal_id');
        if(Url::get('portal_id') != '')
        {
            $con_portal = 'and ACCOUNT.id = \''.Url::get('portal_id').'\'';
        }
        else
        {
            $con_portal = '';
        }
        $portal_name = DB::fetch_all('
            SELECT 
					ACCOUNT.ID,party.name_1
				FROM
					ACCOUNT
					LEFT OUTER JOIN PARTY ON PARTY.user_id = account.id
					LEFT OUTER JOIN ZONE ON ZONE.id = party.zone_id
				WHERE 
					ACCOUNT.TYPE=\'PORTAL\'
                    '.$con_portal.'
        ');
        foreach($portal_name as $k=>$v)
        {
            $portal_name[$k]['portal_name'] = $v['name_1'];
        }
        
        if($page_no>=$this->map['start_page'])
		{
            $this->parse_layout('header',
    			array(
                    'to_date'=>$to_day,
                    'from_date'=>$from_day,
    				'page_no'=>$page_no,
    				'total_page'=>$total_page,
    				'hotel_name'=>Url::get('portal_id')?DB::fetch('SELECT id,name_1 FROM party WHERE user_id = \''.Url::sget('portal_id').'\'','name_1'):HOTEL_NAME,
                    'portal_name'=>$portal_name
                )+$this->map
    		);	
            
    		$layout = 'report1';
    		if(Url::get('type')==2){
    			$layout = 'report2';
    		}elseif(Url::get('type')==3){
    			$layout = 'report3';
    		}
            $portal_department = '';
        if(Url::get('portal_id') != 'ALL')
        {
            $portal_department=Url::get('portal_id')?"and portal_department.portal_id ='".Url::get('portal_id')."'":'';
        }
        $sql='
                select 
                    department.id,
                    department.code
                from 
                    department 
                    inner join portal_department on department.code = portal_department.department_code 
                where    
                    1=1
                    '.$portal_department.'
                    and department.parent_id = 0 AND department.code != \'WH\'
        ';
        $department = DB::fetch_all($sql);
        //System::debug($sql);
        //System::debug($department);
        $cols = 0;
        $depa[1] = array('res'=>0,'karaoke'=>0,'hk'=>0,'telephone'=>0,'massage'=>0,'ticket'=>0,'res'=>0,'res'=>0);
        foreach($department as $k=>$v)
        {
            if($v['code']=='RES')
            {
                $depa[1]['res'] = 1;
                $cols+=1;
            }
            if($v['code']=='KARAOKE')
            {
                $depa[1]['karaoke'] = 1;
                $cols+=1;
            }
            if($v['code']=='HK')
            {
                $depa[1]['hk'] = 1;
                $cols+=4;
            }
            if($v['code']=='SPA')
            {
                $depa[1]['massage'] = 1;
                $cols+=1;
            }
            if($v['code']=='VENDING')
            {
                $depa[1]['vending'] = 1;
                $cols+=1;
            }
            if($v['code']=='TICKET')
            {
                $depa[1]['ticket'] = 1;
                $cols+=1;
            }
        }
    		$this->parse_layout($layout,
    			array(
    				'items'=>$items,
    				'last_group_function_params'=>$last_group_function_params,
    				'group_function_params'=>$this->group_function_params,
    				'page_no'=>$page_no,
    				'total_page'=>$total_page,
    				'pay_by_currency'=>$pay_by_currency,
    				'extra_service'=>$extra_service,
    				'old_extra_service'=>$old_extra_service,
                    'cols'=>$cols,
                    'department'=>$depa
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
