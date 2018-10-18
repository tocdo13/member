<?php
class CashRevenueReportForm extends Form
{
	function CashRevenueReportForm()
	{
		Form::Form('CashRevenueReportForm');
	}
	function draw()
	{
		if(URL::get('do_search') )
		{
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
			$name_month = array("01"=>"JAN","02"=>"FEB","03"=>"MAR","04"=>"APR","05"=>"MAY","06"=>"JUN","07"=>"JUL","08"=>"AUG","09"=>"SEP","10"=>"OCT","11"=>"NOV","12"=>"DEC");
			$year = URL::get('from_year');
			$day=1;
			if(URL::get('from_day'))
			{
				$day = URL::get('from_day');
			}
			$month = URL::get('from_month');
			if(!checkdate($month,$day,$year))
			{
				$day = 1;
			}
			$this->line_per_page = URL::get('line_per_page',24);
			$cond = '1>0'
					.(URL::get('payment_type')?' and payment_type.def_code = \''.URL::get('payment_type').'\'':'') 
					.(URL::get('room_type_id')?' and room.room_type_id = \''.URL::get('room_type_id').'\'':'') 
					.(URL::get('customer_id')?' and customer_id=\''.URL::get('customer_id').'\'':'') 
					.(URL::get('reservation_type_id')?' and reservation_room.reservation_type_id = \''.URL::get('reservation_type_id').'\'':'') 
			;
			$reservation_cond = (URL::get('from_year')?' and reservation_room.departure_time>=\''.($day.'-'.$name_month[$month].'-'.substr($year,2,2)).'\' and reservation_room.departure_time<=\''.($day.'-'.$name_month[$month].'-'.substr($year,2,2)).'\'':'');
			$cond .=$reservation_cond;
			if(Url::get('revenue')!='ALL')
			{
				$cond.=' and reservation_room.status=\'CHECKOUT\'';	
				$reservation_cond.=' and reservation_room.status=\'CHECKOUT\'';	
			}
			else
			{
				$cond.=' and (reservation_room.status=\'CHECKOUT\' or reservation_room.status=\'CHECKIN\' or reservation_room.status=\'BOOKED\')';
			}
			if(Url::get('customer'))
			{
				$cond.= ' and reservation_room.tax_rate >0';	
			}
			$report = new Report;
			$sql = '
			SELECT * FROM
			(
				select 
					reservation_room.id
					,reservation_room.note
					,reservation_room.traveller_id
					,reservation_room.reservation_id
					,reservation_room.total_amount as room_total
					,reservation_room.arrival_time
					,reservation_room.departure_time
					,concat(concat(traveller.first_name,\'  \'),traveller.last_name) as customer_stay
					,customer.name as customer_name
					,customer.id as customer_id
					,customer.tax_code
					,room.name as room_name
					,tour.name as tour_name
					,ROWNUM as rownumber
					,room_type.name as room_type
				from 
					reservation_room
					inner join reservation on reservation_room.reservation_id=reservation.id
					left outer join customer on reservation.customer_id=customer.id
					left outer join reservation_traveller on reservation_traveller.reservation_room_id=reservation_room.id
					left outer join traveller on reservation_room.traveller_id=traveller.id and reservation_traveller.reservation_room_id=reservation_room.id
					left outer join tour on reservation.tour_id = tour.id
					inner join room on room.id=reservation_room.room_id
					inner join room_type on room.room_type_id = room_type.id
					inner join payment_type on payment_type.id = reservation_room.payment_type_id
				where 
					'.$cond.'
				order by 
					departure_time DESC
			)
			WHERE
			 rownumber > '.((URL::get('start_page')-1)*$this->line_per_page).' and rownumber<='.(URL::get('no_of_page')*$this->line_per_page);
			$report->items = DB::fetch_all($sql);
			$sql ='
				select
					currency_id as id
					,type
					,exchange
					,sum(amount) as amount
				from
					pay_by_currency
					inner join reservation_room on pay_by_currency.bill_id = reservation_room.id
					inner join currency on currency.id = pay_by_currency.currency_id
					inner join payment_type on payment_type.id = reservation_room.payment_type_id
				where
					type=\'RESERVATION\'
					and '.$cond.'
				group by
					currency_id
					,exchange
					,type	
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
						inner join payment_type on payment_type.id = reservation_room.payment_type_id
					where 
						'.$cond.'
						'.$restaurent_cond.'
					group by 
						reservation_room.id												
					order by
						reservation_room.id	
			 	) re
			 )
			 WHERE
			 rownums > '.((URL::get('start_page')-1)*$this->line_per_page).' and rownums<='.(URL::get('no_of_page')*$this->line_per_page);
			$restaurant_total = DB::fetch_all($sql);
			//restaurant total = with hotel + walkin
			$sql = '
				SELECT 
					sum( BAR_RESERVATION.TOTAL) as id	
				  FROM 
					BAR_RESERVATION
				  WHERE 
				   	'.Date_Time::to_time($day.'/'.$month.'/'.$year).'<= TIME_OUT and TIME_OUT <'.(Date_Time::to_time($day.'/'.$month.'/'.$year)+(24*3600)).'
					'.$restaurent_cond.'
				 	
			';
			$restaurant_total_with_all = DB::fetch($sql);
			//echo $sql;
			//minibar
			$sql = 	'
				SELECT 	* FROM
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
							inner join reservation_room on reservation_room.id=housekeeping_invoice.reservation_id and reservation_room.room_id=minibar.room_id
							inner join reservation on reservation.id=reservation_room.reservation_id
							inner join payment_type on payment_type.id = reservation_room.payment_type_id
						where '.$cond.' and housekeeping_invoice.type = \'MINIBAR\'
						group by
							reservation_room.id
						) house
			  )  
			 WHERE
			 rownums > '.((URL::get('start_page')-1)*$this->line_per_page).' and rownums <='.(URL::get('no_of_page')*$this->line_per_page)
			;
			$minibar = DB::fetch_all($sql);
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
							inner join reservation_room on reservation_room.id=housekeeping_invoice.reservation_id
							inner join reservation on reservation.id=reservation_room.reservation_id
							inner join payment_type on payment_type.id = reservation_room.payment_type_id
						where 
							'.$cond.' and housekeeping_invoice.type = \'LAUNDRY\'
						group by
							reservation_room.id
						) house
			  )  
			 WHERE
			 rownums > '.((URL::get('start_page')-1)*$this->line_per_page).' and rownums <='.(URL::get('no_of_page')*$this->line_per_page)
			;
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
							inner join reservation_room on reservation_room.id=housekeeping_invoice.reservation_id
							inner join reservation on reservation.id=reservation_room.reservation_id
							inner join payment_type on payment_type.id = reservation_room.payment_type_id
						where 
							'.$cond.' and housekeeping_invoice.type = \'EQUIP\'
						group by
							reservation_room.id
						) house
			  )  
			 WHERE
			 rownums > '.((URL::get('start_page')-1)*$this->line_per_page).' and rownums <='.(URL::get('no_of_page')*$this->line_per_page)
			;
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
							inner join reservation on reservation.id=reservation_room.reservation_id
							inner join payment_type on payment_type.id = reservation_room.payment_type_id
						where 
							'.$cond.'
						group by
							reservation_room.id
						) house
			  ) ' 
			;
			$service_other = DB::fetch_all($sql);
			//dich vu mo rong 
			$sql = 	'	select 
							extra_service_invoice.reservation_room_id as id
							,sum(extra_service_invoice_detail.quantity*extra_service_invoice_detail.price) as total_amount
						from 
							extra_service_invoice_detail
							inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id
							inner join reservation_room on reservation_room.id=extra_service_invoice.reservation_room_id
							inner join reservation on reservation.id=reservation_room.reservation_id
							inner join payment_type on payment_type.id = reservation_room.payment_type_id
						where 
							'.$cond.'
							AND extra_service_invoice_detail.used = 1
						group by
							extra_service_invoice.reservation_room_id'
			;
			$extra_services = DB::fetch_all($sql);
			/*For hotel 5 star*/
			// massage
			$massage_cond  = '1=1';
			if(Url::get('revenue')!='ALL')
			{
				$massage_cond .= ' and massage_product_consumed.status=\''.Url::get('revenue').'\'';
			}
			$massage_cond .= ' and massage_product_consumed.time_out>='.Date_Time::to_time($day.'/'.$month.'/'.$year);
			$massage_cond .= ' and massage_product_consumed.time_out < '.(Date_Time::to_time($day.'/'.$month.'/'.$year)+(24*3600));
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
			//tennis
			$tennis_cond  = '1=1';
			if(Url::get('revenue')!='ALL')
			{
				$tennis_cond .= ' and tennis_reservation_court.status=\''.Url::get('revenue').'\'';
			}	
			$tennis_cond .= ' and tennis_reservation_court.time_out>='.Date_Time::to_time($day.'/'.$month.'/'.$year);
			$tennis_cond .= ' and tennis_reservation_court.time_out < '.(Date_Time::to_time($day.'/'.$month.'/'.$year)+(24*3600));
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
			//swimming
			$swimming_cond  = '1=1';
			if(Url::get('revenue')!='ALL')
			{
				$swimming_cond .= ' and swimming_pool_reservation_pool.status=\''.Url::get('revenue').'\'';
			}	
			$swimming_cond .= ' and swimming_pool_reservation_pool.time_out>='.Date_Time::to_time($day.'/'.$month.'/'.$year);
			$swimming_cond .= ' and swimming_pool_reservation_pool.time_out < '.(Date_Time::to_time($day.'/'.$month.'/'.$year)+(24*3600));
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
			//karaoke
			$karaoke_cond  = '1=1';
			if(Url::get('revenue')!='ALL')
			{
				$karaoke_cond .= ' and ka_reservation.status=\''.Url::get('revenue').'\'';
			}	
			$karaoke_cond .= ' and ka_reservation.time_out>='.Date_Time::to_time($day.'/'.$month.'/'.$year);
			$karaoke_cond .= ' and ka_reservation.time_out < '.(Date_Time::to_time($day.'/'.$month.'/'.$year)+(24*3600));
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
			//shop
			$shop_cond  = '1=1';
			$shop_cond .= ' and shop_invoice.time>='.Date_Time::to_time($day.'/'.$month.'/'.$year);
			$shop_cond .= ' and shop_invoice.time < '.(Date_Time::to_time($day.'/'.$month.'/'.$year)+(24*3600));
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
			//for_hotel_5_star not by reservation
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
					sum(ka_reservation.total) as id
				  FROM 
					ka_reservation
					inner join ka_reservation_room on ka_reservation_room.ka_reservation_id = ka_reservation.id
				  WHERE 
				   '.$karaoke_cond.'
			';
			$extra_karaoke = DB::fetch($sql);
			
			$sql = '
				SELECT 
					sum(shop_invoice.total) as id
				  FROM 
					shop_invoice
				  WHERE 
				   '.$shop_cond.'
			';
			$extra_shop = DB::fetch($sql);
			
			$total_not_by_reservation = array('massage'=>0,'tennis'=>0,'swimming'=>0,'karaoke'=>0,'shop'=>0,'restaurant_total_with_all'=>$restaurant_total_with_all);
			isset($extra_massage['id'])?$total_not_by_reservation['massage'] = $extra_massage['id']:'';
			isset($extra_tennis['id'])?$total_not_by_reservation['tennis'] = $extra_tennis['id']:'';
			isset($extra_swimming['id'])?$total_not_by_reservation['swimming'] = $extra_swimming['id']:'';
			isset($extra_karaoke['id'])?$total_not_by_reservation['karaoke'] = $extra_karaoke['id']:'';
			isset($extra_shop['id'])?$total_not_by_reservation['shop'] = $extra_shop['id']:'';
			//room
			foreach($report->items as $key=>$item)
			{
				$report->items[$key]['stt'] = $i++;
				$report->items[$key]['minibar_product'] = DB::fetch_all('
					SELECT 
						housekeeping_invoice_detail.product_id as id
						,sum(housekeeping_invoice_detail.quantity) as quantity
						,hk_product.name_'.Portal::language().' as name
					FROM
						housekeeping_invoice_detail
						INNER JOIN housekeeping_invoice on housekeeping_invoice.id = housekeeping_invoice_detail.invoice_id
						LEFT OUTER JOIN hk_product on hk_product.id = housekeeping_invoice_detail.product_id
					WHERE
						housekeeping_invoice.reservation_id='.$key.' and housekeeping_invoice.type=\'MINIBAR\'
					GROUP BY
						housekeeping_invoice_detail.product_id,hk_product.name_'.Portal::language().'
					
				');
				$report->items[$key]['laundry_product'] = DB::fetch_all('
					SELECT 
						housekeeping_invoice_detail.product_id as id
						,sum(housekeeping_invoice_detail.quantity) as quantity
						,hk_product.name_'.Portal::language().' as name
					FROM
						housekeeping_invoice_detail
						INNER JOIN housekeeping_invoice on housekeeping_invoice.id = housekeeping_invoice_detail.invoice_id
						LEFT OUTER JOIN hk_product on hk_product.id = housekeeping_invoice_detail.product_id
					WHERE
						housekeeping_invoice.reservation_id='.$key.' and housekeeping_invoice.type=\'LAUNDRY\'
					GROUP BY
						housekeeping_invoice_detail.product_id,hk_product.name_'.Portal::language().'
					
				');		

				$room_amount = $report->items[$key]['room_total'];
				//nha hang
				$report->items[$key]['restaurant_total'] = isset($restaurant_total[$key]['total'])?$restaurant_total[$key]['total']:0;
				//minibar + giat la
				$report->items[$key]['minibar_total'] = isset($minibar[$key]['minibar_total'])?$minibar[$key]['minibar_total']:0;
				$report->items[$key]['laundry_total'] = isset($laundry[$key]['laundry_total'])?$laundry[$key]['laundry_total']:0;
				$report->items[$key]['equip_total'] = isset($equip[$key]['total'])?$equip[$key]['total']:0;
				$report->items[$key]['housekeeping_total'] = $report->items[$key]['minibar_total']+$report->items[$key]['laundry_total'] + $report->items[$key]['equip_total'];
				//dich vu khac 
				$report->items[$key]['service_other_total'] = isset($service_other[$key]['service_other_total'])?$service_other[$key]['service_other_total']:0;
				$report->items[$key]['extra_service_total'] = isset($extra_services[$key]['total_amount'])?$extra_services[$key]['total_amount']:0;
				//for 5 star
				$report->items[$key]['massage_total'] = isset($massage[$key]['total_amount'])?$massage[$key]['total_amount']:0;
				$report->items[$key]['tennis_total'] = isset($tennis[$key]['total_amount'])?$tennis[$key]['total_amount']:0;
				$report->items[$key]['swimming_total'] = isset($swimming[$key]['total_amount'])?$swimming[$key]['total_amount']:0;
				$report->items[$key]['karaoke_total'] = isset($karaoke[$key]['total'])?$karaoke[$key]['total']:0;
				$report->items[$key]['shop_total'] = isset($shop[$key]['total'])?$shop[$key]['total']:0;
				
				//tong nha hang + minibar + giat la 
				$report->items[$key]['total'] = $room_amount+$report->items[$key]['restaurant_total']+$report->items[$key]['housekeeping_total'];
				// for 5 star :  massage + tennis + karaoke + swimming
				$report->items[$key]['total'] += $report->items[$key]['massage_total']+$report->items[$key]['tennis_total'];
				$report->items[$key]['total'] += $report->items[$key]['swimming_total']+$report->items[$key]['karaoke_total'];
				$report->items[$key]['total'] += $report->items[$key]['shop_total'];
				$report->items[$key]['total'] += str_replace(',','',$report->items[$key]['service_other_total']) +  str_replace(',','',$report->items[$key]['extra_service_total']);
				
				//$report->items[$key]['room_total']=$report->items[$key]['room_total']?System::display_number($item['room_total']):'';
				$report->items[$key]['restaurant_total']=$report->items[$key]['restaurant_total']?System::display_number($report->items[$key]['restaurant_total']):'';
				$report->items[$key]['minibar_total']=$report->items[$key]['minibar_total']?System::display_number($report->items[$key]['minibar_total']):'';
				$report->items[$key]['laundry_total']=$report->items[$key]['laundry_total']?System::display_number($report->items[$key]['laundry_total']):'';
				$report->items[$key]['equip_total']=$report->items[$key]['equip_total']?System::display_number($report->items[$key]['equip_total']):'';
				$report->items[$key]['housekeeping_total']=$report->items[$key]['housekeeping_total']?System::display_number($report->items[$key]['housekeeping_total']):'';

				//for 5 star
				$report->items[$key]['massage_total']=$report->items[$key]['massage_total']?System::display_number($report->items[$key]['massage_total']):'';
				$report->items[$key]['tennis_total']=$report->items[$key]['tennis_total']?System::display_number($report->items[$key]['tennis_total']):'';
				$report->items[$key]['swimming_total']=$report->items[$key]['swimming_total']?System::display_number($report->items[$key]['swimming_total']):'';
				$report->items[$key]['karaoke_total']=$report->items[$key]['karaoke_total']?System::display_number($report->items[$key]['karaoke_total']):'';
				$report->items[$key]['shop_total']=$report->items[$key]['shop_total']?System::display_number($report->items[$key]['shop_total']):'';
				//dich vu khac
				//$report->items[$key]['service_other_total']=$report->items[$key]['service_other_total']?$report->items[$key]['service_other_total']:'';
				
				//$report->items[$key]['total']=$report->items[$key]['total']?System::display_number($report->items[$key]['total']):'';
			}
			if(Url::get('customer'))
			{
				$items = array();
				foreach($report->items as $key=>$item)
				{
					
					if($item['customer_id'])
					{
						if(!isset($items[$item['customer_id']]))
						{
							$items[$item['customer_id']]['id'] = $item['customer_id'];
							$items[$item['customer_id']]['tax_code'] = $item['tax_code'];
							$items[$item['customer_id']]['customer_name'] = $item['customer_name'];							
							$items[$item['customer_id']]['items'][$item['id']] = $item;
							$items[$item['customer_id']]['total'] = 0;
							$items[$item['customer_id']]['room_total'] = 0;
							$items[$item['customer_id']]['restaurant_total'] = 0;
							$items[$item['customer_id']]['massage_total'] = 0;
							$items[$item['customer_id']]['tennis_total'] = 0;
							$items[$item['customer_id']]['swimming_total'] = 0;
							$items[$item['customer_id']]['karaoke_total'] = 0;
							$items[$item['customer_id']]['shop_total'] = 0;
							$items[$item['customer_id']]['minibar_total'] = 0;
							$items[$item['customer_id']]['laundry_total'] = 0;
							$items[$item['customer_id']]['equip_total'] = 0;
							$items[$item['customer_id']]['housekeeping_total'] = 0;
							$items[$item['customer_id']]['extra_service_total'] = 0;
							$items[$item['customer_id']]['service_other_total'] = 0;
						}
						else
						{
							$items[$item['customer_id']]['items'][$item['id']] = $item;
						}
						if($temp=System::calculate_number($item['total']))
						{
							$items[$item['customer_id']]['total'] += $temp;
						}
						if($temp=System::calculate_number($item['room_total']))
						{
							$items[$item['customer_id']]['room_total'] += $temp;
						}
						if($temp=System::calculate_number($item['restaurant_total']))
						{
							$items[$item['customer_id']]['restaurant_total'] += $temp;
						}
						//for 5 star
						if($temp=System::calculate_number($item['massage_total']))
						{
							$items[$item['customer_id']]['massage_total'] += $temp;
						}
						if($temp=System::calculate_number($item['tennis_total']))
						{
							$items[$item['customer_id']]['tennis_total'] += $temp;
						}
						if($temp=System::calculate_number($item['swimming_total']))
						{
							$items[$item['customer_id']]['swimming_total'] += $temp;
						}	
						if($temp=System::calculate_number($item['karaoke_total']))
						{
							$items[$item['customer_id']]['karaoke_total'] += $temp;
						}	
						if($temp=System::calculate_number($item['shop_total']))
						{
							$items[$item['customer_id']]['shop_total'] += $temp;
						}			
						//	
						if($temp=System::calculate_number($item['minibar_total']))
						{
							$items[$item['customer_id']]['minibar_total'] += $temp;
						}
						if($temp=System::calculate_number($item['laundry_total']))
						{
							$items[$item['customer_id']]['laundry_total'] += $temp;
						}
						if($temp=System::calculate_number($item['equip_total']))
						{
							$items[$item['customer_id']]['equip_total'] += $temp;
						}
						if($temp=System::calculate_number($item['housekeeping_total']))
						{
							$items[$item['customer_id']]['housekeeping_total'] += $temp;
						}
						if($temp=System::calculate_number($item['extra_service_total']))
						{
							$items[$item['customer_id']]['extra_service_total'] += $temp;
						}			
						if($temp=System::calculate_number($item['service_other_total']))
						{
							$items[$item['customer_id']]['service_other_total'] += $temp;
						}			
						
					}
				}
				$this->parse_layout('report2',array('customer'=>$items));
			}
			else
			{
				$this->print_all_pages($report,$pay_by_currency,$total_not_by_reservation);
			}
		}
		else
		{
			$this->parse_layout('search',
				array(
				'reservation_type_id_list' => array(''=>'')+String::get_list(DB::fetch_all('select id,name from reservation_type order by position')),	
				'room_type_id_list' => array(''=>'')+String::get_list(DB::fetch_all('select id, name from room_type order by name')),
				'room_type_id' => URL::get('room_type_id','')
				)
			);	
		}			
	}

	function print_all_pages(&$report,$pay_by_currency,$extra_service)
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
				'shop_total'=>0
			);
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page,$pay_by_currency,$extra_service);
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
			$this->parse_layout('no_record');
			$this->parse_layout('footer',array(
				'page_no'=>0,
				'total_page'=>0
			));
		}
	}
	function print_page($items, &$report, $page_no,$total_page,$pay_by_currency,$extra_service)
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
			if($temp=System::calculate_number($item['tennis_total']))
			{
				$this->group_function_params['tennis_total'] += $temp;
			}
			if($temp=System::calculate_number($item['swimming_total']))
			{
				$this->group_function_params['swimming_total'] += $temp;
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
			if($temp=System::calculate_number($item['extra_service_total']))
			{
				$this->group_function_params['extra_service_total'] += $temp;
			}			
			if($temp=System::calculate_number($item['service_other_total']))
			{
				$this->group_function_params['service_other_total'] += $temp;
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
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)
		);		
		$layout = 'report1';
		if(Url::get('type')==2)
		{
			$layout = 'report2';
		}elseif(Url::get('type')==3)
		{
			$layout = 'report3';
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
				'old_extra_service'=>$old_extra_service
			)
		);
		$this->parse_layout('footer',array(				
			'page_no'=>$page_no,
			'total_page'=>$total_page,
		));
	}
}
?>