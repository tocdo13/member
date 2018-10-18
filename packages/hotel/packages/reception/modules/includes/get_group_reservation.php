<?php
	function get_reservation($reservation_id,$reservation_room_id=false){
		$map = array();
		$map['items'] = array();
		$item_counter = 1;
		$map['total_with_bank_fee'] = 0;
		$map['total_bank_fee'] = 0;
		$map['bank_fee_percen'] = BANK_FEE_PERCEN;
		$id=$reservation_id;
		$map['checkout_id'] = str_pad($id,6,"0",STR_PAD_LEFT);
		$map['reservation_id'] = $id;
		$include_booked = false;
		if(Url::get('include_booked')){
			$include_booked = true;
		}
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
				INNER JOIN tour on tour.id=reservation.tour_id
				INNER JOIN customer on reservation.customer_id = customer.id
			where
				reservation.id='.$id.'';
		if(!($row = DB::fetch($sql))){
			echo '<script>alert("'.Portal::language('invalid_tour').'");window.close();</script>';
		}else{
	//--------------------------------------lay exchange------------------------------------------------
			$exchange_rate = 1;
			$map += $row;
			$currency = DB::fetch('select * from currency where id=\'USD\'');
			$exchange_rate = $currency['exchange'];
			$map['exchange_rate'] = System::display_number($exchange_rate);
	//////////////////////////////////////////////////////////////////////////////////////////////////////
			$cond = 'rr.reservation_id = '.$id.'
					'.($reservation_room_id?' AND rr.id='.$reservation_room_id.'':'').'
			';
			//'.($include_booked?'':' AND (rr.status = \'CHECKIN\' OR rr.status = \'CHECKOUT\')').'
	//----------------------------------------Tien phong`----------------------------------------------------
			$map['total'] = 0;
			$sql = '
				SELECT
					rr.room_type_id as id,count(rr.room_type_id) as quantity, rr.price, room_type.name as room_type_name
				FROM
					reservation_room rr
					INNER JOIN room_type ON room_type.id = rr.room_type_id
				WHERE
					'.$cond.'
				GROUP BY
					rr.room_type_id,room_type.name,rr.price
			';
			if(Url::get('room_invoice')){
				$reservation_rooms = DB::fetch_all($sql);
				foreach($reservation_rooms as $key=>$value){
					$map['items'][$item_counter]['id'] = $item_counter;
					$map['items'][$item_counter]['description'] = $value['room_type_name'];
					$map['items'][$item_counter]['amount'] = System::display_number($value['price']*$value['quantity']);
					$map['items'][$item_counter]['price'] = System::display_number($value['price']);
					$map['items'][$item_counter]['quantity'] = System::display_number($value['quantity']);
					$map['total'] += $value['price']*$value['quantity'];
					$item_counter++;
				}
			}
			if(Url::get('hk_invoice')){
				$sql_m='
					SELECT
						SUM(housekeeping_invoice.total) AS total
					FROM
						housekeeping_invoice
						INNER JOIN reservation_room On reservation_room.id = housekeeping_invoice.reservation_room_id
						INNER JOIN minibar On minibar.id = housekeeping_invoice.minibar_id and minibar.room_id = reservation_room.room_id
					WHERE
						reservation_room.reservation_id='.$id.' AND
						housekeeping_invoice.type = \'MINIBAR\'
					GROUP BY
						reservation_room.reservation_id
				';
				if($minibar = DB::fetch($sql_m)){
					$map['items'][$item_counter]['id'] = $item_counter;
					$map['items'][$item_counter]['description'] = Portal::language('minibar');
					$map['items'][$item_counter]['amount'] = System::display_number($minibar['total']);
					$map['items'][$item_counter]['price'] = '';
					$map['items'][$item_counter]['quantity'] = '';
					$map['total'] += $minibar['total'];
					$item_counter++;
				}
				$sql_l='
					SELECT
						SUM(housekeeping_invoice.total) AS total
					FROM
						housekeeping_invoice
						INNER JOIN reservation_room On reservation_room.id = housekeeping_invoice.reservation_room_id
					WHERE
						reservation_room.reservation_id='.$id.' AND
						housekeeping_invoice.type = \'LAUNDRY\'
					GROUP BY
						reservation_room.reservation_id
				';
				if($laundry = DB::fetch($sql_l)){
					$map['items'][$item_counter]['id'] = $item_counter;
					$map['items'][$item_counter]['description'] = Portal::language('laundry');
					$map['items'][$item_counter]['amount'] = System::display_number($laundry['total']);
					$map['items'][$item_counter]['price'] = '';
					$map['items'][$item_counter]['quantity'] = '';
					$map['total'] += $laundry['total'];
					$item_counter++;
				}
				$sql_e='
					SELECT
						SUM(housekeeping_invoice.total) AS total
					FROM
						housekeeping_invoice
						INNER JOIN reservation_room On reservation_room.id = housekeeping_invoice.reservation_room_id
						INNER JOIN minibar On minibar.id = housekeeping_invoice.minibar_id and minibar.room_id = reservation_room.room_id
					WHERE
						reservation_room.reservation_id='.$id.' AND
						housekeeping_invoice.type = \'EQUIP\'
					GROUP BY
						reservation_room.reservation_id
				';
				if($equipment = DB::fetch($sql_e)){
					$map['items'][$item_counter]['id'] = $item_counter;
					$map['items'][$item_counter]['description'] = Portal::language('compansation');
					$map['items'][$item_counter]['amount'] = System::display_number($equipment['total']);
					$map['items'][$item_counter]['price'] = '';
					$map['items'][$item_counter]['quantity'] = '';
					$map['total'] += $equipment['total'];
					$item_counter++;
				}
			}
			if(Url::get('bar_invoice')){
				$sql_b = '
					SELECT
						SUM(bar_reservation.total) as total
					FROM
						bar_reservation
						INNER JOIN reservation_room On reservation_room.id = bar_reservation.reservation_room_id
					WHERE
						reservation_room.reservation_id='.$id.'
						AND (bar_reservation.status=\'CHECKOUT\' OR bar_reservation.status=\'CHECKIN\')
					GROUP BY
						reservation_room.reservation_id
				';
				if($bar_service = DB::fetch($sql_b)){
					$map['items'][$item_counter]['id'] = $item_counter;
					$map['items'][$item_counter]['description'] = Portal::language('restaurant');
					$map['items'][$item_counter]['amount'] = System::display_number($bar_service['total']);
					$map['items'][$item_counter]['price'] = '';
					$map['items'][$item_counter]['quantity'] = '';
					$map['total'] += $bar_service['total'];
					$item_counter++;
				}
			}
			if(Url::get('massage_invoice')){
				$sql_massage='
					SELECT
						sum(massage_reservation_room.total_amount) as total
					FROM
						massage_reservation_room
						INNER JOIN reservation_room ON reservation_room.id = massage_reservation_room.hotel_reservation_room_id
					WHERE
						reservation_room.reservation_id='.$id.'
					GROUP BY
						reservation_room.reservation_id
				';
				if($smassage = DB::fetch($sql_massage)){
					$map['items'][$item_counter]['id'] = $item_counter;
					$map['items'][$item_counter]['description'] = Portal::language('massage').'/'.Portal::language('SPA');
					$map['items'][$item_counter]['amount'] = System::display_number($smassage['total']);
					$map['items'][$item_counter]['price'] = '';
					$map['items'][$item_counter]['quantity'] = '';
					$map['total'] += $smassage['total'];
					$item_counter++;
				}
			}
			if(Url::get('tennis_invoice')){
				$sql_tennis='
					SELECT
						sum(tennis_reservation_court.total_amount) as total
					FROM
						tennis_reservation_court
						INNER JOIN reservation_room ON reservation_room.id = tennis_reservation_court.hotel_reservation_room_id
					WHERE
						reservation_room.reservation_id='.$id.'
					GROUP BY
						reservation_room.reservation_id
				';
				if($tennis = DB::fetch($sql_tennis)){
					$map['items'][$item_counter]['id'] = $item_counter;
					$map['items'][$item_counter]['description'] = Portal::language('tennis');
					$map['items'][$item_counter]['amount'] = System::display_number($tennis['total']);
					$map['items'][$item_counter]['price'] = '';
					$map['items'][$item_counter]['quantity'] = '';
					$map['total'] += $tennis['total'];
					$item_counter++;
				}
			}
			if(Url::get('swimming_pool_invoice')){
				$sql_swimming_pool='
					SELECT
						sum(swimming_pool_reservation_pool.total_amount) as total
					FROM
						swimming_pool_reservation_pool
						INNER JOIN reservation_room ON reservation_room.id = swimming_pool_reservation_pool.hotel_reservation_room_id
					WHERE
						reservation_room.reservation_id='.$id.'
					GROUP BY
						reservation_room.reservation_id
				';
				if($swimming_pool = DB::fetch($sql_swimming_pool)){
					$map['items'][$item_counter]['id'] = $item_counter;
					$map['items'][$item_counter]['description'] = Portal::language('swimming_pool');
					$map['items'][$item_counter]['amount'] = System::display_number($swimming_pool['total']);
					$map['items'][$item_counter]['price'] = '';
					$map['items'][$item_counter]['quantity'] = '';
					$map['total'] += $swimming_pool['total'];
					$item_counter++;
				}
			}
			if(Url::get('karaoke_invoice')){
				$sql_karaoke='
					SELECT
						sum(ka_reservation.total) as total
					FROM
						ka_reservation
						INNER JOIN reservation_room ON reservation_room.id = ka_reservation.reservation_room_id
					WHERE
						reservation_room.reservation_id='.$id.'
					GROUP BY
						reservation_room.reservation_id
				';
				if($karaoke = DB::fetch($sql_karaoke)){
					$map['items'][$item_counter]['id'] = $item_counter;
					$map['items'][$item_counter]['description'] = Portal::language('karaoke');
					$map['items'][$item_counter]['amount'] = System::display_number($karaoke['total']);
					$map['items'][$item_counter]['price'] = '';
					$map['items'][$item_counter]['quantity'] = '';
					$map['total'] += $karaoke['total'];
					$item_counter++;
				}
			}
			if(Url::get('shop_invoice')){
				$sql_shop='
					SELECT
						sum(shop_invoice.total) as total
					FROM
						shop_invoice
						INNER JOIN reservation_room ON reservation_room.id = shop_invoice.reservation_room_id
					WHERE
						reservation_room.reservation_id='.$id.'
					GROUP BY
						reservation_room.reservation_id
				';
				if($shop = DB::fetch($sql_shop)){
					$map['items'][$item_counter]['id'] = $item_counter;
					$map['items'][$item_counter]['description'] = Portal::language('shop');
					$map['items'][$item_counter]['amount'] = System::display_number($shop['total']);
					$map['items'][$item_counter]['price'] = '';
					$map['items'][$item_counter]['quantity'] = '';
					$map['total'] += $shop['total'];
					$item_counter++;
				}
			}
			/*
				$net = 0;
				$room_status = DB::fetch_all(
					'
						SELECT
							room_status.id,reservation_id,change_price,room_status.in_date
						FROM
							room_status
						WHERE
							room_status.reservation_room_id = '.$value['id'].'
					'
				);
				foreach($room_status as $v){
					if($v['in_date']!=$value['departure_time']){
						$net += $v['change_price'];
					}
				}
				if($net==0){
					$net = $value['price'];
					if(date('w',$value['time_in'])==5 and EXTRA_CHARGE_ON_SUNDAY>0){
						$net += EXTRA_CHARGE_ON_SUNDAY;
					}
					if(date('w',$value['time_in'])==6 and EXTRA_CHARGE_ON_SATURDAY>0){
						$net += EXTRA_CHARGE_ON_SATURDAY;
					}
				}
				$reservation_rooms[$key]['room_amount'] = $net;
				if($value['reduce_balance']>0){
					$discount = round($net*($value['reduce_balance']/100),ROUND_PRECISION);
					$reservation_rooms[$key]['discount'] = $discount;
				}else{
					$reservation_rooms[$key]['discount'] = 0;
				}
				$reservation_rooms[$key]['service_rate_amount'] = 0;
				if($value['service_rate']){
					$reservation_rooms[$key]['service_rate_amount'] = $net*($value['service_rate']/100);
				}
				$reservation_rooms[$key]['tax_rate_amount'] = 0;
				if($value['tax_rate']){
					$reservation_rooms[$key]['tax_rate_amount'] = ($net+$reservation_rooms[$key]['service_rate_amount'])*($value['tax_rate']/100);
				}
				$reservation_rooms[$key]['total_amount'] = $net - $reservation_rooms[$key]['discount'];
				$reservation_rooms[$key]['services'] = array();
				$reservation_rooms[$key]['services'] = DB::fetch_all('select service_id as id,reservation_room_service.amount,reservation_room_id,service.name,service.type from reservation_room_service inner join service on service.id = service_id where reservation_room_id='.$value['id'].'');
				$total_service = 0;
				if(Url::get('service')){
					foreach($reservation_rooms[$key]['services'] as $s_value){
						if($s_value['type'] == 'SERVICE'){
							$total_service += $s_value['amount'];
							$reservation_rooms[$key]['total_amount'] += $s_value['amount'];
						}
					}
				}elseif(Url::get('room')){
					foreach($reservation_rooms[$key]['services'] as $s_value){
						if($s_value['type'] == 'ROOM'){
							$reservation_rooms[$key]['total_amount'] += $s_value['amount'];
						}
					}
				}else{
					foreach($reservation_rooms[$key]['services'] as $s_value){
						$reservation_rooms[$key]['total_amount'] += $s_value['amount'];
					}
				}
				///////////////////////Xy ly thong can hien thi tren hoa don///////////////////////////////
				if(!Url::get('hk_invoice')){
					$minibar['total'] = $laundry['total'] = $equipment['total'] = 0;
				}
				if(!Url::get('bar_invoice')){
					$bar_service['total'] = 0;
				}
				if(!Url::get('phone_invoice')){
					$phone['total'] = 0;
				}
				if(!Url::get('karaoke_invoice')){
					$reservation_rooms[$key]['total_karaoke_amount'] = 0;
				}
				if(!Url::get('massage_invoice')){
					$reservation_rooms[$key]['total_massage_amount'] = 0;
				}
				if(!Url::get('tennis_invoice')){
					$reservation_rooms[$key]['total_tennis_amount'] = 0;
				}
				if(!Url::get('swimming_pool_invoice')){
					$reservation_rooms[$key]['total_swimming_pool_amount'] = 0;
				}
				////////////////////////////////////////////////////////////////////////////////////////////
				$reservation_rooms[$key]['total_amount'] += ($minibar['total'] + $laundry['total'] + $equipment['total'] + $bar_service['total']);
				$reservation_rooms[$key]['bar_service'] = $bar_service['total'];
				$reservation_rooms[$key]['total_amount'] += $phone['total'];
				$reservation_rooms[$key]['phone'] = $phone['total'];
				$reservation_rooms[$key]['total_amount'] += $reservation_rooms[$key]['total_karaoke_amount']
															+ $reservation_rooms[$key]['total_massage_amount']
															+ $reservation_rooms[$key]['total_tennis_amount']
															+ $reservation_rooms[$key]['total_swimming_pool_amount'];
				if(!Url::get('extra_invoice')){
					$extra_services = DB::fetch_all('
					select
						extra_service_invoice_detail.service_id as id,extra_service_invoice_detail.name,SUM(extra_service_invoice_detail.quantity*extra_service_invoice_detail.price) as amount
					from
						extra_service_invoice_detail
						inner join extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
					where
						extra_service_invoice.reservation_room_id='.$value['id'].'
						AND extra_service_invoice_detail.used = 1
					group by
						extra_service_invoice_detail.service_id,extra_service_invoice_detail.name
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
				if(Url::get('room_invoice')){
					$reservation_rooms[$key]['total_amount'] += $reservation_rooms[$key]['service_rate_amount']+$reservation_rooms[$key]['tax_rate_amount'];
					$reservation_rooms[$key]['total_amount'] -= $value['reduce_amount'];
				}
				$map['total'] += $reservation_rooms[$key]['total_amount'];
				//////////////////////////////////////////////////////////////
				//////////////////////////////////////////////////////////////
				///////////////////////Phi giao dich///////////////////////////
				$total = $reservation_rooms[$key]['total_amount'];
				$reservation_rooms[$key]['total_bank_fee'] = 0;
				$bank_fee_percen = BANK_FEE_PERCEN; // % phi giao dich
				if($value['payment_type_id'] == 3){ // thanh quan qua the, chuyen khoan
					$reservation_rooms[$key]['total_bank_fee'] = round($total*$bank_fee_percen/100,ROUND_PRECISION);
					$reservation_rooms[$key]['total_with_bank_fee'] = $total + $reservation_rooms[$key]['total_bank_fee'];
				}else{
					$reservation_rooms[$key]['total_with_bank_fee'] = $total;
				}
				$reservation_rooms[$key]['bank_fee_percen'] = $bank_fee_percen;
				$map['total_with_bank_fee'] += $reservation_rooms[$key]['total_with_bank_fee'];
				$map['total_bank_fee'] += $reservation_rooms[$key]['total_bank_fee'];
				///////////////////////Phi giao dich///////////////////////////
			}*/
		}
		return $map;
	}
?>