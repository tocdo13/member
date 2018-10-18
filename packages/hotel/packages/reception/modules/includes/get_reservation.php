<?php
	function get_reservation($reservation_id,$reservation_room_id=false,$view_all=false){
		$map = array();
		$map['total_with_bank_fee'] = 0;
		$map['total_bank_fee'] = 0;
		$map['bank_fee_percen'] = BANK_FEE_PERCEN;
		$id=$reservation_id;
		$map['reservation_id'] = $id;
		$checkout_id = '';
		$include_booked = false;
		if(Url::get('include_booked')){
			$include_booked = true;
		}
		for($i=0;$i<6-strlen($id);$i++)
		{
			$checkout_id .= '0';
		}
		$checkout_id .= $id;
		$map['checkout_id'] = $checkout_id;
//--------------------------------- thong tin ve` reservation------------------------------------------
		$sql='select
				reservation.*,
				tour.name AS tour_name,
				customer.name AS customer_name,
				customer.address,
				to_char(tour.arrival_time,\'DD/MM/YYYY\') as arrival_time,
				to_char(tour.departure_time,\'DD/MM/YYYY\') as departure_time
			from
				reservation
				left outer join tour on tour.id=reservation.tour_id
				left outer join customer on reservation.customer_id = customer.id
			where
				reservation.id='.$id.'';
		if($row = DB::fetch($sql))
		{
	//--------------------------------------lay exchange------------------------------------------------
			$exchange_rate = 1;
			$map += $row;
			$currency = DB::fetch('select * from currency where id=\'USD\'');
			$exchange_rate = $currency['exchange'];
			$map['exchange_rate'] = System::display_number($exchange_rate);
			$cond = 'reservation_room.reservation_id = '.$id.'
					'.($reservation_room_id?' AND reservation_room.id='.$reservation_room_id.'':'').'
					'.($include_booked?' AND reservation_room.status <> \'CANCEL\'':' AND (reservation_room.status = \'CHECKIN\' OR reservation_room.status = \'CHECKOUT\' OR reservation_room.status = \'BOOKED\')').'
			';
	//----------------------------------------Tien phong`----------------------------------------------------
			$sql = '
				SELECT
					reservation_room.*,
					CONCAT(CONCAT(traveller.first_name,\' \'),traveller.last_name) as traveller_name,
					room_level.name as room_level_name,
					CASE WHEN room.name is not null THEN room.name ELSE room_level.name END  as room_number,
					(reservation_room.departure_time - reservation_room.arrival_time) as nts
				FROM
					reservation_room
					inner join reservation on reservation.id = reservation_room.reservation_id
					inner join room_level on room_level.id = reservation_room.room_level_id
					left outer join room on room.id = reservation_room.room_id
					left outer join reservation_traveller on reservation_traveller.reservation_room_id=reservation_room.id
					left outer join traveller on traveller.id = reservation_room.traveller_id and reservation_traveller.reservation_room_id=reservation_room.id
				WHERE
					'.$cond.'
				ORDER BY
					reservation_room.arrival_time
			';
			$map['total'] = 0;
			$map['total_deposit'] = 0;
			$reservation_rooms = DB::fetch_all($sql);
			require_once 'packages/hotel/packages/reception/modules/CreateTravellerFolio/get_reservation_room.php';
			foreach($reservation_rooms as $key=>$value)
            {
				
				$folios = DB::fetch_all('SELECT
											(traveller_folio.type || \'_\' || traveller_folio.invoice_id) as id
											,traveller_folio.type
											,traveller_folio.invoice_id
											,sum(traveller_folio.amount) as amount
                                            ,sum(traveller_folio.total_amount) as total_amount
											,sum(traveller_folio.percent) as percent
										FROM traveller_folio
											inner join folio ON folio.id = traveller_folio.folio_id
										WHERE 1>0 AND traveller_folio.reservation_room_id = '.$key.'
										GROUP BY
											traveller_folio.invoice_id
											,traveller_folio.type');
				foreach($folios as $f =>$folio)
                {
					$folios[$f]['id'] = $folio['invoice_id'];
				}
				$items = get_reservation_room_detail($key,$folios);
                
				$reservation_rooms[$key]['check_payment'] = 1;
				foreach($items as $k => $itm)
                {
					if($itm['status'] == 0)
                    {
						$reservation_rooms[$key]['check_payment'] = 0;
					}
				}
				$reservation_rooms[$key]['total_amount'] = 0;
				$exchange_rate = $value['exchange_rate']?$value['exchange_rate']:$exchange_rate;
				$reservation_rooms[$key]['exchange_rate'] = $exchange_rate;
				$reservation_rooms[$key]['arrival_time'] = $value['time_in']?date('d/m',$value['time_in']):Date_Time::convert_orc_date_to_date($value['arrival_time'],'/');
				$reservation_rooms[$key]['departure_time'] = $value['time_out']?date('d/m',$value['time_out']):Date_Time::convert_orc_date_to_date($value['departure_time'],'/');
				$sql_massage='
					SELECT
						massage_reservation_room.hotel_reservation_room_id,
                        sum(massage_reservation_room.amount_pay_with_room) as total_amount
					FROM
						massage_reservation_room
					WHERE
						massage_reservation_room.hotel_reservation_room_id='.$value['id'].'
					GROUP BY
						massage_reservation_room.hotel_reservation_room_id
				';
				if($reservation_rooms[$key]['total_massage_amount'] = DB::fetch($sql_massage,'total_amount')){
				}else{
					$reservation_rooms[$key]['total_massage_amount'] = 0;
				}
				$sql_tennis = '
					SELECT
						tennis_reservation_court.hotel_reservation_room_id,sum(tennis_reservation_court.total_amount) as total_amount
					FROM
						tennis_reservation_court
					WHERE
						tennis_reservation_court.hotel_reservation_room_id='.$value['id'].'
					GROUP BY
						tennis_reservation_court.hotel_reservation_room_id
				';
				if($reservation_rooms[$key]['total_tennis_amount'] = DB::fetch($sql_tennis,'total_amount') and HAVE_TENNIS){
				}else{
					$reservation_rooms[$key]['total_tennis_amount'] = 0;
				}
				$sql_swimming_pool = '
					SELECT
						swimming_pool_reservation_pool.hotel_reservation_room_id,sum(swimming_pool_reservation_pool.total_amount) as total_amount
					FROM
						swimming_pool_reservation_pool
					WHERE
						swimming_pool_reservation_pool.hotel_reservation_room_id='.$value['id'].'
					GROUP BY
						swimming_pool_reservation_pool.hotel_reservation_room_id
				';
				if($reservation_rooms[$key]['total_swimming_pool_amount'] = DB::fetch($sql_swimming_pool,'total_amount') and HAVE_SWIMMING){
				}else{
					$reservation_rooms[$key]['total_swimming_pool_amount'] = 0;
				}
				$sql_m='
					SELECT
						housekeeping_invoice.reservation_room_id,SUM(housekeeping_invoice.total) AS total
					FROM
						housekeeping_invoice
						INNER JOIN reservation_room On reservation_room.id = housekeeping_invoice.reservation_room_id
						INNER JOIN minibar On minibar.id = housekeeping_invoice.minibar_id and minibar.room_id = reservation_room.room_id
					WHERE
						housekeeping_invoice.reservation_room_id='.$value['id'].' AND
						housekeeping_invoice.type = \'MINIBAR\'
					GROUP BY
						housekeeping_invoice.reservation_room_id
				';
				$minibar = DB::fetch($sql_m);
				$sql_l='
					SELECT
						housekeeping_invoice.reservation_room_id,SUM(housekeeping_invoice.total) AS total
					FROM
						housekeeping_invoice
						INNER JOIN reservation_room On reservation_room.id = housekeeping_invoice.reservation_room_id
					WHERE
						housekeeping_invoice.reservation_room_id='.$value['id'].' AND
						housekeeping_invoice.minibar_id=\''.($value['room_id']?$value['room_id']:'0').'\' AND
						housekeeping_invoice.type = \'LAUNDRY\'
					GROUP BY
						housekeeping_invoice.reservation_room_id
				';
				$laundry = DB::fetch($sql_l); // GIAT LA CHU Y MA MINIBAR_ID
				$sql_e='
					SELECT
						housekeeping_invoice.reservation_room_id,SUM(housekeeping_invoice.total) AS total
					FROM
						housekeeping_invoice
						INNER JOIN reservation_room On reservation_room.id = housekeeping_invoice.reservation_room_id
					WHERE
						housekeeping_invoice.reservation_room_id='.$value['id'].' AND
						housekeeping_invoice.type = \'EQUIP\'
					GROUP BY
						housekeeping_invoice.reservation_room_id
				';
				$equipment = DB::fetch($sql_e);
				$sql_b = '
					select
						reservation_room_id,
                        SUM(amount_pay_with_room) as total
					from
						bar_reservation
					where
						reservation_room_id=\''.$value['id'].'\' and (status=\'CHECKOUT\' OR status=\'CHECKIN\')
					GROUP BY
						reservation_room_id
				';
				$bar_service = DB::fetch($sql_b);
				$bar_service['total'] = round($bar_service['total'],ROUND_PRECISION);
                
                $sql_k = '
					select
						reservation_room_id,
                        SUM(amount_pay_with_room) as total
					from
						karaoke_reservation
					where
						reservation_room_id=\''.$value['id'].'\' and (status=\'CHECKOUT\' OR status=\'CHECKIN\')
					GROUP BY
						reservation_room_id
				';
				$karaoke_service = DB::fetch($sql_k);
               
				$karaoke_service['total'] = round($karaoke_service['total'],ROUND_PRECISION);
                
                $sql_v = '
					select
						reservation_room_id,
                        SUM(amount_pay_with_room) as total
					from
						ve_reservation
					where
						reservation_room_id=\''.$value['id'].'\' and (status=\'CHECKOUT\' OR status=\'CHECKIN\')
					GROUP BY
						reservation_room_id
				';
				$ve_service = DB::fetch($sql_v);
               
				$ve_service['total'] = round($ve_service['total'],ROUND_PRECISION);
                
                //ticket
                $sql_ticket = '
					select
						reservation_room_id,
                        SUM(amount_pay_with_room) as total
					from
						ticket_reservation
					where
						reservation_room_id=\''.$value['id'].'\'
					GROUP BY
						reservation_room_id
				';
				$ticket_service = DB::fetch($sql_ticket);
				$ticket_service['total'] = round($ticket_service['total'],ROUND_PRECISION);
                
				$sql_p = '
					SELECT
						SUM(telephone_report_daily.price) AS total
					FROM
						telephone_report_daily
						inner join telephone_number on telephone_number.phone_number = telephone_report_daily.phone_number_id
					WHERE
						telephone_report_daily.hdate >= '.$value['time_in'].' AND telephone_report_daily.hdate <= '.$value['time_out'].'
						AND telephone_number.room_id = '.($value['room_id']?$value['room_id']:'0').'  AND telephone_report_daily.portal_id = \''.$row['portal_id'].'\'
					GROUP BY
						telephone_number.room_id
				';
				$phone = DB::fetch($sql_p);
				
                
				$reservation_rooms[$key]['total_karaoke_amount'] = 0;
				$reservation_rooms[$key]['total_shop_amount'] = 0;
				$net = 0;$net_price=0;
				$room_status = DB::fetch_all('
											SELECT
												room_status.id,reservation_id,change_price,room_status.in_date
											FROM
												room_status
											WHERE
												room_status.reservation_room_id = '.$value['id'].'
												and reservation_id <> 0');
				if(!$value['tax_rate']){
					$value['tax_rate'] = 0;
				}
				if(!$value['service_rate']){
					$value['service_rate'] = 0;
				}
				if($value['net_price'] == 1){
					$value['price'] = round($value['price']/(1+($value['tax_rate']*0.01) + ($value['service_rate']*0.01) + (($value['tax_rate']*0.01)*($value['service_rate']*0.01))),2);
					$value['net_amount'] = $value['price'];
				}
				foreach($room_status as $v){
					$v['net_change_price'] = $v['change_price'];
					if($value['net_price'] == 1){
						$v['change_price'] = round($v['change_price']/(1+($value['tax_rate']*0.01) + ($value['service_rate']*0.01) + (($value['tax_rate']*0.01)*($value['service_rate']*0.01))),2);
					}
					//if($v['in_date']!=$value['departure_time']){
						$net += $v['change_price'];
						$net_price += $v['net_change_price'];
					//}
				}
				if($net==0){
					//$net = $value['price'];
					$net_price = $value['total_amount'];
					if(date('w',$value['time_in'])==5 and EXTRA_CHARGE_ON_SATURDAY>0){
						$net += EXTRA_CHARGE_ON_SATURDAY;//round($net*EXTRA_CHARGE_ON_SUNDAY/100,ROUND_PRECISION);
					}
					if(date('w',$value['time_in'])==6 and EXTRA_CHARGE_ON_SUNDAY>0){
						$net += EXTRA_CHARGE_ON_SUNDAY;//round($net*EXTRA_CHARGE_ON_SATURDAY/100,ROUND_PRECISION);
					}
					$holidays = DB::fetch_all('select id,name,charge,in_date from holiday where in_date >= \''.$value['arrival_time'].'\' AND  in_date <= \''.$value['departure_time'].'\'');
					foreach($holidays as $k=>$v){
						$net += $v['charge'];
					}
				}
				if(!Url::get('room_invoice') and $view_all == false){
					$net = 0;
					$net_price=0;
				}
				$reservation_rooms[$key]['services'] = array();
				$reservation_rooms[$key]['services'] = DB::fetch_all('select service_id as id,reservation_room_service.amount,reservation_room_id,service.name,service.type from reservation_room_service inner join service on service.id = service_id where reservation_room_id='.$value['id'].'');
				if(Url::get('room_invoice') or $view_all == true){
					foreach($reservation_rooms[$key]['services'] as $s_value){
						if($s_value['type'] == 'ROOM'){
							$reservation_rooms[$key]['total_amount'] += $s_value['amount'];
						}
					}
				}
				if(Url::get('other_invoice')){
					foreach($reservation_rooms[$key]['services'] as $s_value){
						if($s_value['type'] == 'SERVICE'){
							$reservation_rooms[$key]['total_amount'] += $s_value['amount'];
						}
					}
				}
				///////////////////////Xy ly thong can hien thi tren hoa don///////////////////////////////
				if(!Url::get('hk_invoice')  and $view_all == false)
                {
					$minibar['total'] = $laundry['total'] = $equipment['total'] = 0;
				}
				if(!Url::get('bar_invoice') and $view_all == false){
					$bar_service['total'] = 0;
				}
                if(!Url::get('karaoke_invoice') and $view_all == false){
					$karaoke_service['total'] = 0;
				}
                if(!Url::get('vend_invoice') and $view_all == false){
					$ve_service['total'] = 0;
				}
                
                if(!Url::get('ticket_invoice') and $view_all == false){
					$ticket_service['total'] = 0;
				}
				if(!Url::get('phone_invoice') and $view_all == false){
					$phone['total'] = 0;
				}
				
				//if(!Url::get('massage_invoice') and $view_all == false){
//					$reservation_rooms[$key]['total_massage_amount'] = 0;
//				}
				if(!Url::get('tennis_invoice') and $view_all == false){
					$reservation_rooms[$key]['total_tennis_amount'] = 0;
				}
				if(!Url::get('swimming_pool_invoice')){
					$reservation_rooms[$key]['total_swimming_pool_amount'] = 0;
				}
				if(!Url::get('shop_invoice') and $view_all == false){
					$reservation_rooms[$key]['total_shop_amount'] = 0;
				}
				////////////////////////////////////////////////////////////////////////////////////////////
				$reservation_rooms[$key]['total_amount'] += ($minibar['total'] + $laundry['total'] + $equipment['total'] + $bar_service['total'] + $karaoke_service['total']+ $ve_service['total'] + $ticket_service['total']);
				$reservation_rooms[$key]['bar_service'] = $bar_service['total'];
                $reservation_rooms[$key]['karaoke_service'] = $karaoke_service['total'];
                $reservation_rooms[$key]['vend_service'] = $ve_service['total'];
                $reservation_rooms[$key]['ticket_service'] = $ticket_service['total'];
				$reservation_rooms[$key]['total_amount'] += $phone['total'];
				$reservation_rooms[$key]['phone'] = $phone['total'];
				$reservation_rooms[$key]['total_amount'] += $reservation_rooms[$key]['total_karaoke_amount']
															+ $reservation_rooms[$key]['total_massage_amount']
															+ $reservation_rooms[$key]['total_tennis_amount']
															+ $reservation_rooms[$key]['total_swimming_pool_amount']
															+ $reservation_rooms[$key]['total_shop_amount'];
				if(Url::get('extra_service_invoice') || $view_all == true){//
					$extra_services = DB::fetch_all('
					select
						(extra_service_invoice_detail.service_id || \'\' || extra_service_invoice.id || \'\' ||  extra_service_invoice_detail.in_date) as id
						,extra_service_invoice_detail.name||\'_<i>\'||extra_service_invoice_detail.note||\'</i>\' as name,
                        CASE
							WHEN 
								extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
							THEN
								ROUND(SUM((extra_service_invoice_detail.quantity*extra_service_invoice_detail.price) + ((extra_service_invoice_detail.quantity*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + (((extra_service_invoice_detail.quantity*extra_service_invoice_detail.price) + ((extra_service_invoice_detail.quantity*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)),2)
							ELSE
								ROUND(SUM((extra_service_invoice_detail.quantity*extra_service_invoice_detail.price)),2)
						END amount
						,TO_CHAR(extra_service_invoice_detail.in_date,\'DD/MM/YYYY\') as date_in
					from
						extra_service_invoice_detail
						inner join extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
					where
						extra_service_invoice.reservation_room_id='.$value['id'].'
						AND extra_service_invoice_detail.used = 1
					group by
						extra_service_invoice_detail.service_id,extra_service_invoice_detail.name
						,extra_service_invoice_detail.in_date, extra_service_invoice.net_price, extra_service_invoice_detail.note,extra_service_invoice.id
					');
					foreach($extra_services as $s_key=>$s_value){
						 $reservation_rooms[$key]['total_amount'] += $s_value['amount'];
					}
				}else{
					$extra_services = array();
				}
				$reservation_rooms[$key]['extra_services'] = $extra_services;
				$reservation_rooms[$key]['minibar'] = $minibar['total'];
				$reservation_rooms[$key]['laundry'] = $laundry['total'];
				$reservation_rooms[$key]['equipment'] = $equipment['total'];
				if(Url::get('room_invoice') or $view_all == true){
					////////////////////////////////////////////////////////////////////////////////////////////
					if($value['foc']!='' && !$value['foc_all']){
						$net = 0;
						$net_price = 0;	$value['reduce_amount'] = 0;
						$reservation_rooms[$key]['reduce_amount'] = 0;
					}
					if($value['discount_after_tax']==1){
						$reservation_rooms[$key]['room_amount'] = $net;
						$reservation_rooms[$key]['net_room_amount'] = $net_price;
						$reservation_rooms[$key]['service_rate_amount'] = 0;
						if($value['service_rate']){
							$reservation_rooms[$key]['service_rate_amount'] = $net*($value['service_rate']/100);
						}
						$reservation_rooms[$key]['tax_rate_amount'] = 0;
						if($value['tax_rate']){
							$reservation_rooms[$key]['tax_rate_amount'] = ($net+$reservation_rooms[$key]['service_rate_amount'])*($value['tax_rate']/100);
						}
						$room_amount = $reservation_rooms[$key]['room_amount'] + $reservation_rooms[$key]['service_rate_amount'] + $reservation_rooms[$key]['tax_rate_amount'];
						$net_room_amount = $reservation_rooms[$key]['net_room_amount'] + $reservation_rooms[$key]['service_rate_amount'] + $reservation_rooms[$key]['tax_rate_amount'];
						if($value['reduce_balance']>0){
							$discount = round(($room_amount)*($value['reduce_balance']/100),ROUND_PRECISION);
							$reservation_rooms[$key]['discount'] = $discount;
							$reservation_rooms[$key]['discount_net'] = round(($net_room_amount)*($value['reduce_balance']/100),ROUND_PRECISION);
						}else{
							$reservation_rooms[$key]['discount'] = 0;
						}
						$reservation_rooms[$key]['total_amount']+=$room_amount- $reservation_rooms[$key]['discount'] - $value['reduce_amount'];
						$total_amount = $room_amount- $reservation_rooms[$key]['discount'] - $value['reduce_amount'];
					}else{// GG 
						if($value['reduce_balance']>0){
							$discount = round(($net)*($value['reduce_balance']/100),ROUND_PRECISION);
							$reservation_rooms[$key]['discount'] = $discount;
						}else{
							$reservation_rooms[$key]['discount'] = 0;
						}
						$net = $net - $reservation_rooms[$key]['discount'] - $value['reduce_amount'];
                        $reservation_rooms[$key]['service_rate_amount'] = 0;
						if($value['service_rate']){
							$reservation_rooms[$key]['service_rate_amount'] = $net*($value['service_rate']/100);
						}
						$reservation_rooms[$key]['tax_rate_amount'] = 0;
						if($value['tax_rate']){
							$reservation_rooms[$key]['tax_rate_amount'] = ($net+$reservation_rooms[$key]['service_rate_amount'])*($value['tax_rate']/100);
						}
						$reservation_rooms[$key]['total_amount'] =  $reservation_rooms[$key]['total_amount']+round($net + $reservation_rooms[$key]['service_rate_amount'] + $reservation_rooms[$key]['tax_rate_amount']);
						$reservation_rooms[$key]['room_amount'] = $net;
						$total_amount = round($net + $reservation_rooms[$key]['service_rate_amount'] + $reservation_rooms[$key]['tax_rate_amount']);
					}
					DB::update('reservation_room',array('total_amount'=>$total_amount),' id='.$value['id'].'');
					////////////////////////////////////////////////////////////////////////////////////////////
				}
				if($value['foc_all']){
					$reservation_rooms[$key]['total_amount'] = 0;
				}
				$map['total'] += $reservation_rooms[$key]['total_amount'];
				//DB::update('reservation_room',array('total_amount'=>$reservation_rooms[$key]['room_amount']),' id='.$value['id'].'');
				/*if($value['foc']!='' && !$value['foc_all']){
					$reservation_rooms[$key]['room_amount'] = 'FOC';
				}
				if($value['foc_all']){
					$reservation_rooms[$key]['total_amount'] = 'FOC';
				}*/
				if(Url::get('service')  and $view_all == false){
					$value['deposit'] = 0;
				}
				//////////////////////////////////////////////////////////////
				if(Url::get('included_deposit')){
					$reservation_rooms[$key]['total_amount'] -= $value['deposit'];
					$map['total_deposit'] += $value['deposit'];
				}else{
					$reservation_rooms[$key]['deposit'] = 0;
				}
				///////////////////////Phi giao dich///////////////////////////
				$total = $reservation_rooms[$key]['total_amount'];
				$reservation_rooms[$key]['total_bank_fee'] = 0;
				$bank_fee_percen = BANK_FEE_PERCEN; // % phi giao dich
				if($value['payment_type_id'] == 3){ // thanh quan qua the, chuyen khoan
					$reservation_rooms[$key]['total_bank_fee'] = 0;//round($total*$bank_fee_percen/100,ROUND_PRECISION);
					$reservation_rooms[$key]['total_with_bank_fee'] = $total;//+ $reservation_rooms[$key]['total_bank_fee'];
				}else{
					$reservation_rooms[$key]['total_with_bank_fee'] = $total;
				}
				$reservation_rooms[$key]['bank_fee_percen'] = $bank_fee_percen;
				$map['total_with_bank_fee'] += $reservation_rooms[$key]['total_with_bank_fee'];
				$map['total_bank_fee'] += $reservation_rooms[$key]['total_bank_fee'];
				///////////////////////Phi giao dich///////////////////////////
				if($reservation_rooms[$key]['total_amount'] == 0){
					//unset($reservation_rooms[$key]);
				}
			}
			$map['items'] = $reservation_rooms;
		}
//System::Debug($map);
		return $map;
	}
	function checkPayment(){
	}
?>