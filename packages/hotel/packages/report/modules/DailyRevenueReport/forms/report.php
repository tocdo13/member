<?php
class DailyRevenueReportForm extends Form
{
	function DailyRevenueReportForm()
	{
		Form::Form('DailyRevenueReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
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
        //echo $cond_portal;
        //System::debug(get_defined_constants());
		//if(URL::get('do_search'))
		{
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
			$in_date = Url::get('in_date')?Date_Time::to_orc_date(Url::get('in_date')):Date_Time::to_orc_date(date('d/m/Y',time()));	
			$in_time =  Url::get('in_date')?Date_Time::to_time(Url::get('in_date')):Date_Time::to_time(date('d/m/y'));
			$cond = '1>0 '
					.(URL::get('room_level_id')?' and room.room_level_id = \''.URL::get('room_level_id').'\'':'') 
					.(URL::get('customer_id')?' and reservation.customer_id=\''.URL::get('customer_id').'\'':'') 
					.(URL::get('reservation_type_id')?' and reservation_room.reservation_type_id = \''.URL::get('reservation_type_id').'\'':'') 
			;
			$reservation_cond = (URL::get('from_year')?' and reservation_room.departure_time>=\''.$in_date.'\'':'');
			$cond .=$reservation_cond;			
			$cond = '(reservation_room.status=\'CHECKIN\' OR reservation_room.status=\'CHECKOUT\') AND reservation_room.arrival_time<=\''.$in_date.'\' AND reservation_room.departure_time>=\''.$in_date.'\'';
			$report = new Report;
			$no_of_page = 500;
			$line_per_page = 999;
			$this->line_per_page = 999;
			$start_page = 1;
			$sql = 'SELECT * FROM
			(
				select 
					reservation_room.id
					,reservation_room.note
					,reservation_room.traveller_id
					,reservation_room.reservation_id
					,(
                    CASE 
                        WHEN reservation_room.net_price=1 THEN room_status.change_price 
                        ELSE 
                            (
                            room_status.change_price +
                            room_status.change_price * reservation_room.service_rate/100 + 
                            (
                                room_status.change_price +
                                room_status.change_price * reservation_room.service_rate/100
                            )
                            * reservation_room.tax_rate/100 
                            ) 
                    END
                    )  as room_total
                    ,(
                    CASE 
                        WHEN reservation_room.net_price=1 
                        THEN room_status.change_price 
                        ELSE 
                            (
                            room_status.change_price +
                            room_status.change_price * reservation_room.service_rate/100 + 
                            (
                                room_status.change_price +
                                room_status.change_price * reservation_room.service_rate/100
                            )
                            * reservation_room.tax_rate/100 
                            ) 
                    END
                    )  as price
					,reservation_room.arrival_time
					,reservation_room.departure_time
					,concat(concat(traveller.first_name,\'  \'),traveller.last_name) as customer_stay
					,customer.name as customer_name
					,room.name as room_name
					,tour.name as tour_name
					,ROWNUM as rownumber
					
                                        ,room_level.name as room_level
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
					inner join room_level on room_level.id = room.room_level_id
					INNER JOIN room_status ON room_status.reservation_room_id = reservation_room.id
				where 
					'.$cond.$cond_portal.' AND room_status.in_date = \''.$in_date.'\'
				order by 
					departure_time DESC
			)
			WHERE
			 rownumber > '.(($start_page-1)*$line_per_page).' and rownumber<='.($no_of_page*$line_per_page);
			$report->items = DB::fetch_all($sql);
            if (User::is_admin())
            {
                //System::debug($report->items);
            }
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
					inner join room_level on room_level.id = room.room_level_id
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
			// restaurant
			if(Url::get('revenue')=='ALL')
			{
				$restaurent_cond = 'and (bar_reservation.status = \'CHECKOUT\' or bar_reservation.status = \'CHECKIN\')';
			}
			else
			{
				$restaurent_cond = 'and bar_reservation.status = \''.Url::get('revenue').'\' ';
			}
			if(HAVE_RESTAURANT==1)
            {
                $sql =	'
    			SELECT * FROM
    			(
    				select re.*,ROWNUM as rownums from
    				(
    					select 
    						reservation_room.id
    						,sum(bar_reservation.total) as total						
    					from 
    						bar_reservation
    						left outer join reservation_room on reservation_room.id=bar_reservation.reservation_room_id
    						left outer join reservation on reservation_room.reservation_id=reservation.id
    						left outer join room on room.id=reservation_room.room_id
    					where 
    						'.$cond.$cond_portal.'
    						AND bar_reservation.time_in >= '.$in_time.' AND bar_reservation.time_out < '.($in_time+24*3600).'
    					group by 
    						reservation_room.id												
    					order by
    						reservation_room.id	
    				) re
    			 )
    			 WHERE
    			  rownums > '.(($start_page-1)*$line_per_page).' and rownums<='.($no_of_page*$line_per_page);
    			$restaurant_total = DB::fetch_all($sql); 
    			//restaurant total = with hotel + walkin
    			
                $sql = '
    				SELECT 
    					sum( BAR_RESERVATION.TOTAL) as id	
    				  FROM 
    					BAR_RESERVATION
    				  WHERE 
    				   	   	time_out >= '.$in_time.' AND time_out <= '.($in_time+24*3600).'
    					'.$restaurent_cond.'
    				 	
    			';
    			$restaurant_total_with_all = DB::fetch($sql);
    			//echo $sql;
            
            }


			//minibar
			if(HAVE_MINIBAR ==1)
            {
                $sql = 	'SELECT 	* FROM
    					(
    					select house.*,ROWNUM as rownums from 
    					(
    						select 
    							reservation_room.id
    							,sum(housekeeping_invoice.total) as minibar_total
    						from 
    							housekeeping_invoice
    							inner join minibar on minibar.id=housekeeping_invoice.minibar_id
    							inner join room on room.id=minibar.room_id
    							inner join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id and reservation_room.room_id=minibar.room_id
    							inner join reservation on reservation.id=reservation_room.reservation_id
    						where '.$cond.$cond_portal.' and housekeeping_invoice.type = \'MINIBAR\'
    						AND housekeeping_invoice.time >= '.$in_time.' AND housekeeping_invoice.time <= '.($in_time+24*3600).'
    						group by
    							reservation_room.id
    						) house
    			  )  
    			 WHERE rownums > '.(($start_page-1)*$line_per_page).' and rownums<='.($no_of_page*$line_per_page);
    			$minibar = DB::fetch_all($sql);
            }

			//giatla
			$sql = 	'
			SELECT 	* FROM
				(
					select house.*,ROWNUM as rownums from 
					(
						select 
							reservation_room.id
							,sum(housekeeping_invoice.total) as laundry_total
						from 
							housekeeping_invoice
							inner join room on room.id=housekeeping_invoice.minibar_id
							inner join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id
							inner join reservation on reservation.id=reservation_room.reservation_id
						where 
							'.$cond.$cond_portal.' and housekeeping_invoice.type = \'LAUNDRY\'
							AND housekeeping_invoice.time >= '.$in_time.' AND housekeeping_invoice.time <= '.($in_time+24*3600).'
						group by
							reservation_room.id
				) house
			  )  
			 WHERE rownums > '.(($start_page-1)*$line_per_page).' and rownums<='.($no_of_page*$line_per_page);
			$laundry = DB::fetch_all($sql);
			// lam hong do trong phong
			$sql = 	'
			SELECT 	* FROM
				(
					select house.*,ROWNUM as rownums from 
					(
						select 
							reservation_room.id
							,sum(housekeeping_invoice.total) as total
						from 
							housekeeping_invoice
							inner join room on room.id=housekeeping_invoice.minibar_id
							inner join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id
							inner join reservation on reservation.id=reservation_room.reservation_id
						where 
							'.$cond.$cond_portal.' and housekeeping_invoice.type = \'EQUIP\'
							AND housekeeping_invoice.time >= '.$in_time.' AND housekeeping_invoice.time <= '.($in_time+24*3600).'
						group by
							reservation_room.id
					) house
			  )  
			 WHERE rownums > '.(($start_page-1)*$line_per_page).' and rownums<='.($no_of_page*$line_per_page);
			$equip = DB::fetch_all($sql);
			//dich vu khac 
			$sql = 	'
				SELECT 	* FROM
				(
					select house.*,ROWNUM as rownums from 
					(
						select 
							reservation_room.id
							,sum(RESERVATION_ROOM_SERVICE.AMOUNT) as service_other_total
						from 
							RESERVATION_ROOM_SERVICE
							inner join reservation_room on reservation_room.id=RESERVATION_ROOM_SERVICE.RESERVATION_ROOM_ID
							inner join room on room.id=reservation_room.room_id
							inner join reservation on reservation.id=reservation_room.reservation_id
						where 
							'.$cond.$cond_portal.'
						group by
							reservation_room.id 
					) house
			  ) ';
			$service_other = DB::fetch_all($sql);
			//dich vu mo rong 
            //sum(extra_service_invoice_detail.quantity*extra_service_invoice_detail.price) as total_amount
			$sql = 	'	select 
                            concat(extra_service_invoice.reservation_room_id,extra_service_invoice_detail.service_id) as id,
							extra_service_invoice_detail.service_id	,
                            extra_service_invoice.reservation_room_id,
                            extra_service_invoice_detail.name,
                            
                            sum(extra_service_invoice.total_amount) as total_amount
						from 
							extra_service_invoice_detail
							inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id
							inner join reservation_room on reservation_room.id=extra_service_invoice.reservation_room_id
							inner join room on room.id=reservation_room.room_id
							inner join reservation on reservation.id=reservation_room.reservation_id
						where 
							'.$cond.$cond_portal.'
							AND extra_service_invoice_detail.used = 1
							AND 
                            ((extra_service_invoice_detail.in_date = \''.$in_date.'\' and reservation_room.arrival_time <> reservation_room.departure_time) 
                              OR (extra_service_invoice_detail.time  >= '.$in_time.' AND extra_service_invoice_detail.time <= '.($in_time + 24*3600).' and reservation_room.arrival_time = reservation_room.departure_time ))
                            AND extra_service_invoice.payment_type = \'SERVICE\'
						GROUP BY 
							extra_service_invoice_detail.service_id	
							,extra_service_invoice.reservation_room_id
							,extra_service_invoice_detail.name	
						'
			;
			$extra_services = DB::fetch_all($sql);
            
			//dich vu phòng 
            //sum(extra_service_invoice_detail.quantity*extra_service_invoice_detail.price) as total_amount
			$sql = 	'	select 
                            concat(extra_service_invoice.reservation_room_id,extra_service_invoice_detail.service_id) as id,
							extra_service_invoice_detail.service_id	,
                            extra_service_invoice.reservation_room_id,
                            extra_service_invoice_detail.name,
                            sum(extra_service_invoice.total_amount) as total_amount
						from 
							extra_service_invoice_detail
							inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id
							inner join reservation_room on reservation_room.id=extra_service_invoice.reservation_room_id
							inner join room on room.id=reservation_room.room_id
							inner join reservation on reservation.id=reservation_room.reservation_id
						where 
							'.$cond.$cond_portal.'
							AND extra_service_invoice_detail.used = 1
							AND extra_service_invoice_detail.in_date = \''.$in_date.'\' 
                            AND extra_service_invoice.payment_type = \'ROOM\'
                            
						GROUP BY 
							extra_service_invoice_detail.service_id	
							,extra_service_invoice.reservation_room_id
							,extra_service_invoice_detail.name	
						'
			;
			$extra_services_room1 = DB::fetch_all($sql);
            //System::debug($extra_services_room1);
            $sql = 	'	select 
                            concat(extra_service_invoice.reservation_room_id,extra_service_invoice_detail.service_id) as id,
							extra_service_invoice_detail.service_id	,
                            extra_service_invoice.reservation_room_id,
                            extra_service_invoice_detail.name,
                            sum(extra_service_invoice_detail.price + 
                            extra_service_invoice_detail.price*extra_service_invoice.service_rate/100
                            +(extra_service_invoice_detail.price + 
                            extra_service_invoice_detail.price*extra_service_invoice.service_rate/100)*extra_service_invoice.tax_rate/100  ) as total_amount
						from 
							extra_service_invoice_detail
							inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id
							inner join reservation_room on reservation_room.id=extra_service_invoice.reservation_room_id
							inner join room on room.id=reservation_room.room_id
							inner join reservation on reservation.id=reservation_room.reservation_id
						where 
							'.$cond.$cond_portal.'
							AND extra_service_invoice_detail.used = 1
							AND extra_service_invoice_detail.time <= '.$in_time.' AND extra_service_invoice_detail.time >= '.($in_time - 24*3600).' 
                            AND extra_service_invoice.payment_type = \'ROOM\'
                            AND extra_service_invoice.late_checkin =1
						GROUP BY 
							extra_service_invoice_detail.service_id	
							,extra_service_invoice.reservation_room_id
							,extra_service_invoice_detail.name	
						'
			;
			$extra_services_room2 = DB::fetch_all($sql);
			/*
            $sql_service = 'select 
							extra_service_invoice_detail.service_id	as id
							,extra_service_invoice_detail.name
						from 
							extra_service_invoice_detail
							inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id
							inner join reservation_room on reservation_room.id=extra_service_invoice.reservation_room_id
							inner join room on room.id=reservation_room.room_id
							inner join reservation on reservation.id=reservation_room.reservation_id
						where 
							'.$cond.'
							AND extra_service_invoice_detail.used = 1
						Group by extra_service_invoice_detail.service_id,extra_service_invoice_detail.name';
			*/
            $sql_service = 'SELECT id,name FROM extra_service';
			$num_services = DB::fetch_all($sql_service);				
			//System::debug($num_services);
			/*For hotel 5 star*/
			// massage
			$massage_cond  = '1=1';
			if(Url::get('revenue')!='ALL')
			{
				$massage_cond .= ' and massage_product_consumed.status=\''.Url::get('revenue').'\'';
			}
			$massage_cond .= ' and massage_product_consumed.time_out >= '.$in_time;
			$massage_cond .= ' and massage_product_consumed.time_out < '.($in_time+24*3600);
			if(HAVE_MASSAGE ==1)
            {
                $sql = '
    				SELECT 
    					massage_product_consumed.id
    					,(massage_product_consumed.price*massage_product_consumed.quantity) as amount
    				  FROM 
    					massage_product_consumed
    					inner join massage_room on massage_product_consumed.room_id = massage_room.id
    				  WHERE 
    				   '.$massage_cond.'
    			';			
    			$massage = DB::fetch_all($sql);  
            }

			//tennis
			$tennis_cond  = '1=1';
			if(Url::get('revenue')!='ALL')
			{
				$tennis_cond .= ' and tennis_reservation_court.status=\''.Url::get('revenue').'\'';
			}	
			$tennis_cond .= ' and tennis_reservation_court.time_out >= '.$in_time;
			$tennis_cond .= ' and tennis_reservation_court.time_out < '.($in_time+24*3600);
			
            if(HAVE_TENNIS ==1)
            {
                $sql = '
    				SELECT 
    					reservation_room.id,
    					tennis_reservation_court.total_amount
    				  FROM 
    					tennis_reservation_court
    					inner join tennis_court on tennis_reservation_court.court_id = tennis_court.id
    					left outer join tennis_reservation on tennis_reservation_court.reservation_id = tennis_reservation.id
    					inner join reservation_room on reservation_room.id = tennis_reservation_court.hotel_reservation_room_id
    				  WHERE 
    				   '.$tennis_cond.$reservation_cond.'
    			';
    			$tennis = DB::fetch_all($sql); 
            }

			//swimming
			$swimming_cond  = '1=1';
			if(Url::get('revenue')!='ALL')
			{
				$swimming_cond .= ' and swimming_pool_reservation_pool.status=\''.Url::get('revenue').'\'';
			}	
			$swimming_cond .= ' and swimming_pool_reservation_pool.time_out>= '.$in_time;
			$swimming_cond .= ' and swimming_pool_reservation_pool.time_out < '.($in_time+24*3600);
			
            if(HAVE_SWIMMING==1)
            {
                $sql = '
    				SELECT 
    					 reservation_room.id,
    					 swimming_pool_reservation_pool.total_amount
    				  FROM 
    					swimming_pool_reservation_pool
    					inner join swimming_pool on swimming_pool_reservation_pool.swimming_pool_id = swimming_pool.id
    					left outer join swimming_pool_reservation on swimming_pool_reservation_pool.reservation_id = swimming_pool_reservation.id
    					inner join reservation_room on reservation_room.id = swimming_pool_reservation_pool.hotel_reservation_room_id
    				  WHERE 
    				   '.$swimming_cond.$reservation_cond.'
    			';
    			$swimming = DB::fetch_all($sql);  
            }

			//get_phone
			//get_phone
            $phones_cond = '1=1';
            $phones_cond .= ' and telephone_report_daily.hdate>= '.$in_time;
			$phones_cond .= ' and telephone_report_daily.hdate < '.($in_time+24*3600);
			$sql_phone = '
				SELECT DISTINCT
						telephone_report_daily.id as id
						,telephone_report_daily.price as total_amount
						,telephone_number.room_id
						,telephone_report_daily.hdate
					FROM
						telephone_report_daily
						inner join telephone_number on telephone_number.phone_number = telephone_report_daily.phone_number_id
						INNER JOIN room ON room.id = telephone_number.room_id	
				  WHERE
				   '.$phones_cond.'';
			$phones = DB::fetch_all($sql_phone);
			//System::Debug($phones);
			//karaoke
			$phones_cond .= ' and telephone_report_daily.hdate>= '.$in_time;
			$phones_cond .= ' and telephone_report_daily.hdate < '.($in_time+24*3600);
			$karaoke_cond  = '1=1';
			if(Url::get('revenue')!='ALL')
			{
				$karaoke_cond .= ' and ka_reservation.status=\''.Url::get('revenue').'\'';
			}	
			$karaoke_cond .= ' and ka_reservation.time_out>= '.$in_time;
			$karaoke_cond .= ' and ka_reservation.time_out < '.($in_time+24*3600);
			
            if(HAVE_KARAOKE==1)
            {
                $sql = '
    				SELECT 
    					reservation_room.id,
    					ka_reservation.total
    				  FROM 
    					ka_reservation
    					inner join ka_reservation_room on ka_reservation_room.ka_reservation_id = ka_reservation.id
    					inner join reservation_room on reservation_room.id=ka_reservation.reservation_room_id
    				  WHERE 
    				   '.$karaoke_cond.$reservation_cond.'
    			';
    			$karaoke = DB::fetch_all($sql);
            }
            
            /*
			//shop
			$shop_cond  = '1=1';
			$shop_cond .= ' and shop_invoice.time>='.$in_time;
			$shop_cond .= ' and shop_invoice.time< '.($in_time+24*3600);
			
            
            $sql = '
				SELECT 
					reservation_room.id,
					shop_invoice.total
				  FROM 
					shop_invoice
					inner join reservation_room on reservation_room.id=shop_invoice.reservation_room_id
				  WHERE 
				   '.$shop_cond.$reservation_cond.'
			';
			$shop = DB::fetch_all($sql);
			*/
            //for_hotel_5_star not by reservation
            if(HAVE_MASSAGE==1)
            {
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
            }

			if(HAVE_TENNIS==1)
            {
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
            }
            

			if(HAVE_SWIMMING==1)
            {
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
            }

			if(HAVE_KARAOKE==1)
            {
    			$sql = '
    				SELECT 
    					sum(ka_reservation.total) as id
    				  FROM 
    					ka_reservation
    					inner join ka_reservation_room on ka_reservation_room.ka_reservation_id = ka_reservation.id
    				  WHERE 
    				   '.$karaoke_cond.'
    			';
    			$extra_karaoke = DB::fetch($sql);
            }
            
            

			/*
			$sql = '
				SELECT 
					sum(shop_invoice.total) as id
				  FROM 
					shop_invoice
				  WHERE 
				   '.$shop_cond.'
			';
			$extra_shop = DB::fetch($sql);
			*/
            
			$total_not_by_reservation = array('massage'=>0,'tennis'=>0,'swimming'=>0,'karaoke'=>0,'shop'=>0,'restaurant_total_with_all'=>(isset($restaurant_total_with_all)?$restaurant_total_with_all:0));
			isset($extra_massage['id'])?$total_not_by_reservation['massage'] = $extra_massage['id']:'';
			isset($extra_tennis['id'])?$total_not_by_reservation['tennis'] = $extra_tennis['id']:'';
			isset($extra_swimming['id'])?$total_not_by_reservation['swimming'] = $extra_swimming['id']:'';
			isset($extra_karaoke['id'])?$total_not_by_reservation['karaoke'] = $extra_karaoke['id']:'';
			isset($extra_shop['id'])?$total_not_by_reservation['shop'] = $extra_shop['id']:'';
			//room
			$count_service = 0;
			foreach($report->items as $key=>$item)
			{
				if($item['arrival_time'] == $item['departure_time'] && $item['room_total'] == 0 )
                {
					$report->items[$key]['room_total'] = $item['price'];	
				}
                if($item['foc']!='' || $item['foc_all'] == 1)
                {
                    $report->items[$key]['room_total'] = 0;
                }
				if($item['reduce_balance'] > 0 )
                {
					$report->items[$key]['room_total'] = $report->items[$key]['room_total']*(100 - $item['reduce_balance'])*0.01;
				}
				if($item['reduce_amount'] > 0 )
                {
					$report->items[$key]['room_total'] = $report->items[$key]['room_total'] - $item['reduce_amount'];
				}
				$room_amount = $report->items[$key]['room_total'];
                if($item['foc_all']== 1)
                {
                    //nha hang
    				$report->items[$key]['restaurant_total'] = 0;
    				//minibar + giat la
    				$report->items[$key]['minibar_total'] = 0;
    				$report->items[$key]['laundry_total'] = 0;
    				$report->items[$key]['equip_total'] = 0;
    				$report->items[$key]['housekeeping_total'] = 0;
    				//dich vu khac 
    				$report->items[$key]['service_other_total'] = 0;
    				$report->items[$key]['extra_service_total'] = 0;
                    $report->items[$key]['extra_service_room_total1'] = 0;
                    $report->items[$key]['extra_service_room_total2'] = 0;
                    $report->items[$key]['massage_total'] = 0;
    				$report->items[$key]['tennis_total'] = 0;
    				$report->items[$key]['swimming_total'] = 0;
    				$report->items[$key]['phone_total'] = 0;
                    $report->items[$key]['karaoke_total'] = 0;
    				$report->items[$key]['shop_total'] =0;
                }
                else
                {
                    //nha hang
    				$report->items[$key]['restaurant_total'] = isset($restaurant_total[$key]['total'])?$restaurant_total[$key]['total']:0;
    				//minibar + giat la
    				$report->items[$key]['minibar_total'] = isset($minibar[$key]['minibar_total'])?$minibar[$key]['minibar_total']:0;
    				$report->items[$key]['laundry_total'] = isset($laundry[$key]['laundry_total'])?$laundry[$key]['laundry_total']:0;
    				$report->items[$key]['equip_total'] = isset($equip[$key]['total'])?$equip[$key]['total']:0;
    				$report->items[$key]['housekeeping_total'] = $report->items[$key]['minibar_total']+$report->items[$key]['laundry_total'] + $report->items[$key]['equip_total'];
    				//dich vu khac 
    				$report->items[$key]['service_other_total'] = isset($service_other[$key]['service_other_total'])?$service_other[$key]['service_other_total']:0;
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
                    $report->items[$key]['extra_service_room_total1'] = 0;
    				foreach($extra_services_room1 as $id=> $service1)
                    {
    					if($item['id'] ==  $service1['reservation_room_id'])
                        {	
    						$report->items[$key][$service1['name']] = $service1['total_amount'];	
    						$report->items[$key]['extra_service_room_total1'] +=$service1['total_amount'];	
    						//echo $report->items[$key]['extra_service_total'];	
    					}
    				}
                    $report->items[$key]['extra_service_room_total2'] = 0;
    				foreach($extra_services_room2 as $id=> $service2)
                    {
    					if($item['id'] ==  $service2['reservation_room_id'])
                        {	
    						$report->items[$key][$service2['name']] = $service2['total_amount'];	
    						$report->items[$key]['extra_service_room_total2'] +=$service2['total_amount'];	
    						//echo $report->items[$key]['extra_service_total'];	
    					}
    				}
    				//$report->items[$key]['extra_service_total'] = $total_service_reservation;
    				//for 5 star
    				$report->items[$key]['massage_total'] = isset($massage[$key]['total_amount'])?$massage[$key]['total_amount']:0;
    				$report->items[$key]['tennis_total'] = isset($tennis[$key]['total_amount'])?$tennis[$key]['total_amount']:0;
    				$report->items[$key]['swimming_total'] = isset($swimming[$key]['total_amount'])?$swimming[$key]['total_amount']:0;
    				$report->items[$key]['phone_total'] = 0;
    				foreach($phones as $id => $phon){
    					if($phon['room_id'] == $item['room_id'] && $phon['hdate'] >= Date_Time::to_time(Date_Time::convert_orc_date_to_date($item['arrival_time'],'/')) && $phon['hdate'] < Date_Time::to_time(Date_Time::convert_orc_date_to_date($item['departure_time'],'/')) +24 *3600){
    						$report->items[$key]['phone_total'] = $phon['total_amount'];
    					}
    				}
    				$report->items[$key]['karaoke_total'] = isset($karaoke[$key]['total'])?$karaoke[$key]['total']:0;
    				$report->items[$key]['shop_total'] = isset($shop[$key]['total'])?$shop[$key]['total']:0;
                }
				$room_amount = $room_amount + $report->items[$key]['extra_service_room_total1'] + $report->items[$key]['extra_service_room_total2'] ;
                $report->items[$key]['room_total'] = $report->items[$key]['room_total'] + $report->items[$key]['extra_service_room_total1'] + $report->items[$key]['extra_service_room_total2'];
				//tong nha hang + minibar + giat la 
				$report->items[$key]['total'] = $room_amount+$report->items[$key]['restaurant_total']+$report->items[$key]['housekeeping_total'];
				// for 5 star :  massage + tennis + karaoke + swimming + phones
				$report->items[$key]['total'] += $report->items[$key]['massage_total']+$report->items[$key]['tennis_total'];
				$report->items[$key]['total'] += $report->items[$key]['swimming_total']+$report->items[$key]['karaoke_total'] + $report->items[$key]['phone_total'];
				$report->items[$key]['total'] += $report->items[$key]['shop_total'];
				$report->items[$key]['total'] += str_replace(',','',$report->items[$key]['service_other_total']) + str_replace(',','',$report->items[$key]['extra_service_total']);
				//$report->items[$key]['room_total']=$report->items[$key]['room_total']?System::display_number($item['room_total']):'';
				$report->items[$key]['restaurant_total']=$report->items[$key]['restaurant_total']?$report->items[$key]['restaurant_total']:'';
				$report->items[$key]['minibar_total']=$report->items[$key]['minibar_total']?$report->items[$key]['minibar_total']:'';
				$report->items[$key]['laundry_total']=$report->items[$key]['laundry_total']?$report->items[$key]['laundry_total']:'';
				$report->items[$key]['equip_total']=$report->items[$key]['equip_total']?$report->items[$key]['equip_total']:'';
				$report->items[$key]['housekeeping_total']=$report->items[$key]['housekeeping_total']?$report->items[$key]['housekeeping_total']:'';

				//for 5 star
				$report->items[$key]['massage_total']=$report->items[$key]['massage_total']?$report->items[$key]['massage_total']:'';
				$report->items[$key]['tennis_total']=$report->items[$key]['tennis_total']?$report->items[$key]['tennis_total']:'';
				$report->items[$key]['swimming_total']=$report->items[$key]['swimming_total']?$report->items[$key]['swimming_total']:'';
				$report->items[$key]['phone_total']=$report->items[$key]['phone_total']?$report->items[$key]['phone_total']:'';
				$report->items[$key]['karaoke_total']=$report->items[$key]['karaoke_total']?$report->items[$key]['karaoke_total']:'';
				$report->items[$key]['shop_total']=$report->items[$key]['shop_total']?$report->items[$key]['shop_total']:'';
				
                //dich vu khac
				//$report->items[$key]['service_other_total']=$report->items[$key]['service_other_total']?$report->items[$key]['service_other_total']:'';
				
				//$report->items[$key]['total']=$report->items[$key]['total']?System::display_number($report->items[$key]['total']):'';
                //tong bang khong thi xoa ban ghi nay khong cho hien thi
                if($report->items[$key]['total']==0)
                    unset($report->items[$key]);
                else
                    $report->items[$key]['stt'] = $i++;
                    
            }
			$this->print_all_pages($report,$pay_by_currency,$total_not_by_reservation,$num_services);
		}
		/*else{
			$this->map['in_date'] = date('d-m-Y');
			$this->parse_layout('header',$this->map+array(
					'page_no'=>0,
					'total_page'=>0
				));
		}	*/		
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
				'room_total'=>0,
				'restaurant_total'=>0,
				'minibar_total'=>0,
				'laundry_total'=>0,
				'equip_total'=>0,
				'service_other_total'=>0,
				'extra_service_total'=>0,
				'housekeeping_total'=>0,
				'massage_total'=>0,
				'tennis_total'=>0,
				'swimming_total'=>0,
				'karaoke_total'=>0,
				'shop_total'=>0,
				'phone_total'=>0
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
			if($temp=System::calculate_number($item['room_total']))
			{
				$this->group_function_params['room_total'] += $temp;
			}
			if($temp=System::calculate_number($item['restaurant_total']))
			{
				$this->group_function_params['restaurant_total'] += $temp;
			}
			//for 5 star
			if($temp=System::calculate_number($item['massage_total']))
			{
				$this->group_function_params['massage_total'] += $temp;
			}
			if($temp=System::calculate_number($item['extra_service_total']))
			{
				$this->group_function_params['extra_service_total'] += $temp;
			}
			if($temp=System::calculate_number($item['tennis_total']))
			{
				$this->group_function_params['tennis_total'] += $temp;
			}
			if($temp=System::calculate_number($item['swimming_total']))
			{
				$this->group_function_params['swimming_total'] += $temp;
			}	
			if($temp=System::calculate_number($item['phone_total']))
			{
				$this->group_function_params['phone_total'] += $temp;
			}	
			if($temp=System::calculate_number($item['karaoke_total']))
			{
				$this->group_function_params['karaoke_total'] += $temp;
			}	
			if($temp=System::calculate_number($item['shop_total']))
			{
				$this->group_function_params['shop_total'] += $temp;
			}			
			//	
			if($temp=System::calculate_number($item['minibar_total']))
			{
				$this->group_function_params['minibar_total'] += $temp;
			}
			if($temp=System::calculate_number($item['laundry_total']))
			{
				$this->group_function_params['laundry_total'] += $temp;
			}
			if($temp=System::calculate_number($item['equip_total']))
			{
				$this->group_function_params['equip_total'] += $temp;
			}
			if($temp=System::calculate_number($item['housekeeping_total']))
			{
				$this->group_function_params['housekeeping_total'] += $temp;
			}	
			if($temp=System::calculate_number($item['service_other_total']))
			{
				$this->group_function_params['service_other_total'] += $temp;
			}			
			foreach($num_services as $id => $value){
				if(isset($item[$value['name']])){ 
					$this->group_function_params[$value['name']] += System::calculate_number($item[$value['name']]);
				}
			}
		}
		//tat ca cac dich vu them 
		$old_extra_service = $extra_service;		
		$extra_service['massage']  =  ($extra_service['massage'] - $this->group_function_params['massage_total']);
		$extra_service['tennis']  =  ($extra_service['tennis'] - $this->group_function_params['tennis_total']);
		$extra_service['swimming']  =  ($extra_service['swimming'] - $this->group_function_params['swimming_total']);
		$extra_service['karaoke']  =  ($extra_service['karaoke'] - $this->group_function_params['karaoke_total']);	
		$extra_service['shop']  =  ($extra_service['shop'] - $this->group_function_params['shop_total']);	
		$this->parse_layout('header',
			array(
				'in_date'=>Url::get('in_date')?Url::get('in_date'):date('d/m/Y'),
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