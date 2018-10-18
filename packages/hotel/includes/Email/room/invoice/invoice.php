<?php
function setFolioTraveller($traveller_id,$folio_id)
    {
        $folioTraveller = array();
        $folioTraveller['folio_id'] = $folio_id;
		if(is_numeric($traveller_id))
        {
			;
			$items = get_folio_info($traveller_id,$folio_id);
            if (User::is_admin())
            {
                //System::debug($items);
            }
			$folios = DB::fetch('select folio.*,
                                        CONCAT(traveller.first_name,CONCAT(\'\',traveller.last_name)) as full_name,
                                        reservation.booking_code,reservation_room.arrival_time as arrival_time,
                                        reservation_room.departure_time as departure_time,
                                        reservation_room.price as room_rate,
                                        reservation_room.reservation_id as reservation_id 
                                from folio 
            						inner join reservation_traveller on reservation_traveller.id=folio.reservation_traveller_id
            						inner join traveller on reservation_traveller.traveller_id = traveller.id
            						inner join reservation_room ON reservation_room.id = reservation_traveller.reservation_room_id
            						inner join reservation ON reservation_room.reservation_id = reservation.id
            					 where folio.id = '.$folio_id.'');
			//System::Debug($folios);
            //exit();
			$folioTraveller['tax_total'] = $items['tax_total'];
			$folioTraveller['booking_code'] = $folios['booking_code'];
            $folioTraveller['reservation_id'] = $folios['reservation_id'];
			$folioTraveller['full_name'] = $folios['full_name'];
			$folioTraveller['service_total']=$items['service_total'];
			$folioTraveller['total'] = $folios['total'];
			$folioTraveller['arrival_time'] = $items['arrival_time'];
            $folioTraveller['departure_time'] = $items['departure_time'];
            $folioTraveller['room_rate'] = System::display_number($folios['room_rate']);
            $folioTraveller['total_befor_tax'] = $folios['total'] - $items['tax_total'] - $items['service_total'];
		    $folioTraveller += $items;
			$payments = array();
			$payments = DB::fetch_all('SELECT 
								(payment.payment_type_id || \'_\' || payment.credit_card_id || \'_\' || payment.currency_id || \'_\' || payment.folio_id) as id
								,SUM(amount) as total
								,SUM(payment.bank_fee) as bank_fee
								,SUM(amount*payment.exchange_rate) as total_vnd
								,CONCAT(payment.payment_type_id,CONCAT(\'_\',payment.currency_id)) as name
								,payment.bill_id
								,payment.folio_id
								,payment.payment_type_id
								,payment.credit_card_id
								,payment.currency_id 
								,credit_card.name as credit_card_name
                                ,payment.bank_acc
                                ,payment.description    
							FROM payment
								inner join reservation_room on payment.bill_id = reservation_room.id
								left outer join credit_card ON credit_card.id = payment.credit_card_id
								left outer join folio ON folio.id = payment.folio_id
							WHERE 
								1>0 AND payment.folio_id = '.$folio_id.'
								AND payment.type_dps is null
							GROUP BY 
                                payment.payment_type_id,
                                payment.currency_id,
                                payment.bill_id,
                                payment.folio_id,
                                payment.credit_card_id,
                                credit_card.name,
                                payment.bank_acc,
                                payment.description  
							ORDER BY 
                                payment.payment_type_id  ASC
                                ');
			//System::Debug($payments);
			$folioTraveller['payments'] = $payments;		
			
		}
            $account_name = DB::fetch("SELECT account.id as id, party.name_1 as name FROM account inner join party on party.user_id = account.id WHERE account.id='".User::id()."'");
            $folioTraveller['account_name'] = $account_name['name'];
            //system::debug($folioTraveller);
            return $folioTraveller;
    }
    function get_folio_info($traveller_id, $folio_id)
    {
        
		$reservation=array();
        $bill_id ='';
		for($i=0;$i<6-strlen($traveller_id);$i++){
			$bill_id .= '0';
		}
		$reservation['bill_number'] = $bill_id.$traveller_id;
		$reservation['description']='';
		require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/currency.php';
		//--------------------------------------lay exchange------------------------------------------------
		if(HOTEL_CURRENCY == 'VND')
        {
			$reservation['exchange_currency_id'] = 'USD';
		}
        else
        {
			$reservation['exchange_currency_id'] = 'VND';	  
		}
		$reservation['exchange_rate'] = DB::fetch('select id,exchange from currency where id=\''.$reservation['exchange_currency_id'].'\'','exchange');
        //system::debug($folioTraveller['exchange_rate']);
		$cond ='';
        $cond .= $folio_id?' AND trf.folio_id='.$folio_id.'':'';
		$cond .= $traveller_id?' AND trf.reservation_traveller_id='.$traveller_id.'':'';
		$sql =' SELECT 
					   trf.*
                       ,HOUSEKEEPING_INVOICE.code as hk_code
                       ,extra_service_invoice.code as ex_code
                       ,extra_service_invoice_detail.note as ex_note
					   ,trf.reservation_room_id as rr_id 
					   ,reservation_room.time_in
					   ,reservation_room.time_out
                       ,trf.description || \' \' ||MASSAGE_PRODUCT_CONSUMED.time_in as time
                       ,trf.description || \' \' ||to_char(trf.date_use, \'DD/MM/YYYY\') as description
					from 
					   traveller_folio trf 
					   inner join folio ON folio.id = trf.folio_id   
					   INNER JOIN reservation_room ON reservation_room.id = trf.reservation_room_id
                       left join HOUSEKEEPING_INVOICE on HOUSEKEEPING_INVOICE.id = trf.invoice_id
                       left join extra_service_invoice_detail on extra_service_invoice_detail.id = trf.invoice_id
                       left join MASSAGE_PRODUCT_CONSUMED on trf.RESERVATION_ROOM_ID = MASSAGE_PRODUCT_CONSUMED.RESERVATION_ROOM_ID
                       left join extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id
					 where 1>0 '.$cond.'
					 order by trf.date_use,trf.type ';
        //System::debug($sql);  
		$traveller_folios = DB::fetch_all($sql);
        
		$reservation_room = array();
		$arr = '0';
		$t=0; $rr_id = 0;
		foreach($traveller_folios as $id=>$folio){
			if(!strpos($arr,$folio['rr_id'])){
				$arr .= ','.$folio['rr_id'];	
			}
			if($folio['add_payment']==0){
				$t=1;
			}
		}
		$reservation_rooms = get_reservation_room($arr);
		if($t==0)
        {
			$reservation_rooms_this = get_reservation_room_this($traveller_id);
			$reservation_rooms += $reservation_rooms_this;
			foreach($reservation_rooms_this as $k => $rr){
				$rr_id = $rr['id'];
			}
		}
		foreach($reservation_rooms as $k=> $room){
			$total_room_add[$k] = 0;
			$total_discount_add[$k]= 0;
			$total_deposit_add[$k]= 0;
			$total_other_add[$k] = 0;
		}
        
		$add_rr = array();
		//$reservation = array();
		$reservation['total'] = 0;
		$reservation['tax_total'] = 0;
		$reservation['service_total'] = 0;
		 
		$total_room_this = 0;
		$total_discount_this = 0;
		$total_deposit_this = 0;
		$total_other_this = 0;
		$reservation['add_payment_items'] = array();
		foreach($traveller_folios as $id=>$folio)
        {
			if($folio['add_payment']==0)
            {
				$rr_id = $folio['rr_id']; 
				$reservation[strtolower($folio['type']).'s'][$folio['invoice_id']] = $folio;
				if($folio['type']=='ROOM')
                {
					$total_room_this += $folio['amount'];	
				}
                else if($folio['type']=='DEPOSIT')
                {
					$total_deposit_this += $folio['amount'];
				}
                else if($folio['type']=='DISCOUNT')
                {
					$total_discount_this += $folio['amount'];
				}
                else
                {
					$total_other_this += $folio['amount'];
					$reservation[strtolower($folio['type']).'s'][$folio['invoice_id']]['service_amount'] = $folio['amount']*$folio['service_rate']/100;
					$reservation[strtolower($folio['type']).'s'][$folio['invoice_id']]['tax_amount'] = ($folio['amount'] + ($folio['amount']*$folio['service_rate']/100))*$folio['tax_rate']/100;
					$reservation['service_total'] +=  $folio['amount']*$folio['service_rate']/100;
					//echo $folio['amount'].'<br>';
					$reservation['tax_total'] +=($folio['amount'] + ($folio['amount']*$folio['service_rate']/100))*$folio['tax_rate']/100;
				}
			}
            else if($folio['add_payment']==1)
            {
				if(!isset($reservation['add_payment_items'][$folio['rr_id']]))
                {
					$reservation['add_payment_items'][$folio['rr_id']]['id'] = $folio['rr_id'];
					$reservation['add_payment_items'][$folio['rr_id']]['items'] = array();
					$reservation['add_payment_items'][$folio['rr_id']]['service_amount'] = 0;
					$reservation['add_payment_items'][$folio['rr_id']]['tax_amount'] = 0;
				}
				if($folio['type'] == 'ROOM'){


					$reservation['add_payment_items'][$folio['rr_id']]['items'][$folio['invoice_id'].'_'.$folio['type']] = $folio;	
					$total_room_add[$folio['rr_id']] += $folio['amount'];
				}else if($folio['type'] == 'DISCOUNT'){
					$total_discount_add[$folio['rr_id']] += $folio['amount'];	
					$reservation['add_payment_items'][$folio['rr_id']]['items'][$folio['type']] = $folio;
				}else if($folio['type'] == 'DEPOSIT'){
					$total_deposit_add[$folio['rr_id']] += $folio['amount'];	
					$reservation['add_payment_items'][$folio['rr_id']]['items'][$folio['type']] = $folio;
				}else{
					$reservation['add_payment_items'][$folio['rr_id']]['items'][$folio['invoice_id'].'_'.$folio['type']] = $folio;
					$service_add = ($folio['amount']*$folio['service_rate']/100);
					$tax_add = ($folio['amount'] + $service_add)*$folio['tax_rate']/100;
					$total_other_add[$folio['rr_id']] += $folio['amount'] + $service_add + $tax_add;
					$reservation['add_payment_items'][$folio['rr_id']]['service_amount'] += $service_add;
					$reservation['add_payment_items'][$folio['rr_id']]['tax_amount'] += $tax_add;	
				}
				if(isset($add_rr[$folio['rr_id']]))
                {
					$add_rr[$folio['rr_id']]['total_room'] = $total_room_add[$folio['rr_id']];
					$add_rr[$folio['rr_id']]['total_discount'] = $total_discount_add[$folio['rr_id']];
					$add_rr[$folio['rr_id']]['total_deposit'] = $total_deposit_add[$folio['rr_id']];
					$add_rr[$folio['rr_id']]['total_other'] = $total_other_add[$folio['rr_id']];
					if($folio['type'] == 'ROOM'){
						$add_rr[$folio['rr_id']]['service_rate'] =$folio['service_rate'];
						$add_rr[$folio['rr_id']]['tax_rate'] = $folio['tax_rate'];
					}
				}else{
					$add_rr[$folio['rr_id']]['rr_id'] = $folio['rr_id'];
					$add_rr[$folio['rr_id']]['id'] = $folio['rr_id'];
					$add_rr[$folio['rr_id']]['total_room'] = $total_room_add[$folio['rr_id']];
					$add_rr[$folio['rr_id']]['total_discount'] = $total_discount_add[$folio['rr_id']];
					$add_rr[$folio['rr_id']]['total_deposit'] = $total_deposit_add[$folio['rr_id']];
					$add_rr[$folio['rr_id']]['total_other'] = $total_other_add[$folio['rr_id']];
					$add_rr[$folio['rr_id']]['service_rate'] = 0;
					$add_rr[$folio['rr_id']]['tax_rate'] = 0;
					if($folio['type'] == 'ROOM'){
						$add_rr[$folio['rr_id']]['service_rate'] =$folio['service_rate'];
						$add_rr[$folio['rr_id']]['tax_rate'] = $folio['tax_rate'];
					}
				}
				//$add_rr[$add['rr_id']]['time_in'] = date('d/m',$add['time_in']);
				//$add_rr[$add['rr_id']]['time_out'] = date('d/m',$add['time_out']);
			}else if($folio['add_payment']==2){
				
			}
		}
		if(isset($reservation_rooms[$rr_id]))
        {
			if($reservation_rooms[$rr_id]['foc']!='')
            {
				$total_room_this = 0;
			}
			$reservation += $reservation_rooms[$rr_id];
			$total_room_this_aftex_discount = $total_room_this - $total_discount_this;
			$service_this_aftex_discount = $total_room_this_aftex_discount * $reservation_rooms[$rr_id]['service_rate']/100;
			$tax_this_aftex_discount = ($total_room_this_aftex_discount + $service_this_aftex_discount)*$reservation_rooms[$rr_id]['tax_rate']/100;
			$reservation['total_before_tax'] = $total_room_this_aftex_discount + $total_other_this;
			$reservation['total_deposit'] = $total_deposit_this; 
			$reservation['service_total'] +=$service_this_aftex_discount;
			$reservation['tax_total'] +=  $tax_this_aftex_discount;
			$reservation['total'] = $total_room_this_aftex_discount + $reservation['tax_total'] + $reservation['service_total'] + $total_other_this - $total_deposit_this;
			if($reservation_rooms[$rr_id]['foc_all']==1){
				$reservation['total_before_tax'] = 0;
				$reservation['total'] = 0;
			}
		}
		//System::Debug($add_rr);
		foreach($add_rr as $b => $arr){
			if(isset($reservation_rooms[$arr['id']])){
				if($reservation_rooms[$arr['id']]['foc']!=''){
					$arr['total_room'] = 0;
				}
				$add_rr[$b]['id'] = $arr['rr_id'];
				$add_rr[$b]['description'] = Portal::language('add_payment_for_room').' '.$reservation_rooms[$arr['id']]['room_name'].' ('.date('d/m/Y',$reservation_rooms[$arr['id']]['time_in']).' - '.date('d/m/Y',$reservation_rooms[$arr['id']]['time_out']).')';
				$total_room_aftex_discount = $arr['total_room'] - $arr['total_discount'];
				$service_aftex_discount = $total_room_aftex_discount * $arr['service_rate']/100;
				$tax_aftex_discount = ($total_room_aftex_discount + $service_aftex_discount)*$arr['tax_rate']/100;
				$add_rr[$b]['total'] = $total_room_aftex_discount + $service_aftex_discount + $tax_aftex_discount + $arr['total_other'] - $arr['total_deposit'];
				if($reservation_rooms[$arr['id']]['foc_all']==1){
					$add_rr[$b]['total'] = 0;
				}	
				$reservation['total'] += $add_rr[$b]['total'];
				if(isset($reservation['add_payment_items'][$arr['id']]['service_amount'])){
					$reservation['add_payment_items'][$arr['id']]['service_amount'] += $service_aftex_discount;
				}else{
					$reservation['add_payment_items'][$arr['id']]['service_amount'] = $service_aftex_discount;
				}
				if(isset($reservation['add_payment_items'][$arr['id']]['tax_amount'])){
					$reservation['add_payment_items'][$arr['id']]['tax_amount'] += $tax_aftex_discount;
				}else{
					$reservation['add_payment_items'][$arr['id']]['tax_amount'] = $tax_aftex_discount;
				}
			}
		}
		$reservation['add_payments'] = $add_rr;
//        System::debug($reservation);die();
		return $reservation;  
	}
    function get_reservation_room($cond){
		return $reservation_rooms = DB::fetch_all('SELECT
				reservation_room.id,
				reservation_room.arrival_time,
				to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') as arrival_time, 
				to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as departure_time,
				(reservation_room.departure_time-reservation_room.arrival_time) as nights, 
				reservation_room.time_in, reservation_room.time_out,
				reservation_room.room_id,
				room.name as room_name,
				concat(traveller.first_name,concat(\' \', traveller.last_name)) as full_name, 
				customer.address, customer.name as customer_name, 
				reservation_room.foc,
				reservation_room.foc_all,
				reservation_room.service_rate,
                tour.name as tour_name,
				reservation_room.tax_rate
			FROM reservation_room
				INNER JOIN reservation ON reservation_room.reservation_id = reservation.id
				INNER JOIN room ON room.id = reservation_room.room_id
				left outer join customer ON customer.id = reservation.customer_id
                left outer join tour ON tour.id = reservation.tour_id
				left outer join reservation_traveller ON reservation_traveller.reservation_room_id = reservation_room.id
				left outer join traveller ON reservation_traveller.traveller_id = traveller.id
			WHERE reservation_room.id in ('.$cond.')
		 ');
	}
    function get_reservation_room_this($traveller_id){
	return $reservation_rooms = DB::fetch_all('SELECT
				reservation_room.id,
				reservation_room.arrival_time,
				to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') as arrival_time, 
				to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as departure_time,
				(reservation_room.departure_time-reservation_room.arrival_time) as nights, 
				reservation_room.time_in, reservation_room.time_out,
				reservation_room.room_id,
				room.name as room_name,
				concat(traveller.first_name,concat(\' \', traveller.last_name)) as full_name, 
				customer.address, customer.name as customer_name, 
				reservation_room.foc,
				reservation_room.foc_all,
				reservation_room.service_rate,
				reservation_room.tax_rate
			FROM reservation_room
				INNER JOIN reservation ON reservation_room.reservation_id = reservation.id
				INNER JOIN room ON room.id = reservation_room.room_id
				left outer join customer ON customer.id = reservation.customer_id
				left outer join reservation_traveller ON reservation_traveller.reservation_room_id = reservation_room.id
				left outer join traveller ON reservation_traveller.traveller_id = traveller.id
			WHERE reservation_traveller.id ='.$traveller_id.'
		 ');
	}
?>