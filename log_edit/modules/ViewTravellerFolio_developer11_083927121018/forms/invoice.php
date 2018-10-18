<?php
class ViewTravellerFolioForm extends Form{
	function ViewTravellerFolioForm(){
		Form::Form('ViewTravellerFolioForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function get_folio_info($traveller_id, $folio_id)
    {
		$bill_id ='';
		for($i=0;$i<6-strlen($traveller_id);$i++){
			$bill_id .= '0';
		}
		$this->map['bill_number'] = $bill_id.$traveller_id;
		$this->map['description']='';
		require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/currency.php';
		//--------------------------------------lay exchange------------------------------------------------
		if(HOTEL_CURRENCY == 'VND')
        {
			$this->map['exchange_currency_id'] = 'USD';
		}
        else
        {
			$this->map['exchange_currency_id'] = 'VND';	  
		}
		$this->map['exchange_rate'] = DB::fetch('select id,exchange from currency where id=\''.$this->map['exchange_currency_id'].'\'','exchange');
		$cond ='';
		$cond .= (Url::get('folio_id'))?' AND trf.folio_id='.Url::get('folio_id').'':'';
		$cond .= (Url::get('traveller_id'))?' AND trf.reservation_traveller_id='.Url::get('traveller_id').'':'';
		$sql =' SELECT 
					   trf.*
                       ,housekeeping_invoice.code as hk_code
                       ,housekeeping_invoice.position as position
                       ,bar_reservation.code
                       --,extra_service_invoice.code as ex_code
                       ,extra_service_invoice.id as ex_id
                       ,extra_service_invoice.bill_number as ex_bill
                       ,extra_service_invoice_detail.note as ex_note
					   ,trf.reservation_room_id as rr_id 
					   ,reservation_room.time_in
					   ,reservation_room.time_out
                       ,trf.description || \' \' ||MASSAGE_PRODUCT_CONSUMED.time_in as time
                       ,to_char(trf.date_use, \'DD/MM/YYYY\') as date_use
                       --,trf.description || \' \' ||to_char(trf.date_use, \'DD/MM/YYYY\') as description
                       ,trf.description as description
                       -- dong duoi la lay them ca note cua extra_service
                       --,trf.description || \'_<i>\' || extra_service_invoice_detail.note || \'</i> \' ||to_char(trf.date_use, \'DD/MM/YYYY\') as description
                       ,extra_service.code as ex_code
                       ,extra_service.name as ex_name
                       ,room.name as room_name
					from 
					   traveller_folio trf 
					   inner join folio ON folio.id = trf.folio_id   
					   INNER JOIN reservation_room ON reservation_room.id = trf.reservation_room_id
                       left join room on reservation_room.room_id = room.id
                       left join bar_reservation ON bar_reservation.reservation_room_id = reservation_room.id
                       left join housekeeping_invoice on housekeeping_invoice.id = trf.invoice_id and (trf.type=\'MINIBAR\' or trf.type=\'LAUNDRY\' or trf.type=\'EQUIPMENT\')
                       left join extra_service_invoice_detail on extra_service_invoice_detail.id = trf.invoice_id and trf.type=\'EXTRA_SERVICE\'
                       left join MASSAGE_PRODUCT_CONSUMED on trf.RESERVATION_ROOM_ID = MASSAGE_PRODUCT_CONSUMED.RESERVATION_ROOM_ID
                       left join extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id
                       left join extra_service on extra_service_invoice_detail.service_id = extra_service.id
					 where 1>0 '.$cond.'
					 order by trf.date_use,trf.type ';
        //System::debug($sql);  
		$traveller_folios = DB::fetch_all($sql);
        
        //System::debug($traveller_folios);
		$reservation_room = array();
		$arr = '0';
        
		$t=0; $rr_id = 0;
		foreach($traveller_folios as $id=>$folio)
        {
			if(!strpos($arr,$folio['rr_id']))
            {
				$arr .= ','.$folio['rr_id'];	
			}
			if($folio['add_payment']==0)
            {
				$t=1;
			}
		}               
        
        //System::debug($folio['type']);
		$reservation_rooms = $this->get_reservation_room($arr);
		if($t==0)
        {
			$reservation_rooms_this = $this->get_reservation_room_this($traveller_id);
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
            $total_other_before_tax[$k] = 0;
		}
        
		$add_rr = array();
		$reservation = array();
		$reservation['total'] = 0;        
        $reservation['total_before_tax'] = 0;
        $reservation['grand_total_before_tax'] = 0;
        $reservation['total_amount'] = 0;
		$reservation['tax_total'] = 0;
		$reservation['service_total'] = 0;
        $reservation['grand_tax_total'] = 0;
		$reservation['grand_service_total'] = 0;        
		 
		$total_room_this = 0;
		$total_discount_this = 0;
		$total_deposit_this = 0;
		$total_other_this = 0;
		$reservation['add_payment_items'] = array();
		$i = 1;
        foreach($traveller_folios as $id=>$folio)
        {
			if($folio['add_payment']==0)
            {
				$rr_id = $folio['rr_id']; 
				$reservation[strtolower($folio['type']).'s'][$folio['invoice_id']] = $folio;
				if($folio['type']=='ROOM')
                {
					$total_room_this += $folio['amount'];
                    $reservation[strtolower($folio['type']).'s'][$folio['invoice_id']]['service_amount'] = $folio['amount']*$folio['service_rate']/100;
					$reservation[strtolower($folio['type']).'s'][$folio['invoice_id']]['tax_amount'] = ($folio['amount'] + ($folio['amount']*$folio['service_rate']/100))*$folio['tax_rate']/100;	
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
					$reservation[strtolower($folio['type']).'s'][$folio['invoice_id']]['product'] = $this->get_product($folio['invoice_id']);
					$reservation['service_total'] +=  $folio['amount']*$folio['service_rate']/100;
                    $reservation['grand_service_total'] +=  $folio['amount']*$folio['service_rate']/100;
					//echo $folio['amount'].'<br>';
					$reservation['tax_total'] +=($folio['amount'] + ($folio['amount']*$folio['service_rate']/100))*$folio['tax_rate']/100;
                    $reservation['grand_tax_total'] +=($folio['amount'] + ($folio['amount']*$folio['service_rate']/100))*$folio['tax_rate']/100;
                    
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
                    $reservation['add_payment_items'][$folio['rr_id']]['total_before_tax'] = 0;
				}
				if($folio['type'] == 'ROOM'){
				    $reservation['add_payment_items'][$folio['rr_id']]['total_before_tax'] += $folio['amount'];
                    
                    $reservation['add_payment_items'][$folio['rr_id']]['items'][$folio['invoice_id'].'_'.$folio['type']] = $folio;	
					$total_room_add[$folio['rr_id']] += $folio['amount'];
                    $reservation['add_payment_items'][$folio['rr_id']]['items'][$folio['invoice_id'].'_'.$folio['type']]['service_amount'] = $folio['amount']*$folio['service_rate']/100;
                    $reservation['grand_service_total']+=$folio['amount']*$folio['service_rate']/100;
                    $reservation['add_payment_items'][$folio['rr_id']]['items'][$folio['invoice_id'].'_'.$folio['type']]['tax_amount'] = ($folio['amount'] + ($folio['amount']*$folio['service_rate']/100))*$folio['tax_rate']/100;
                    $reservation['grand_tax_total'] += ($folio['amount'] + ($folio['amount']*$folio['service_rate']/100))*$folio['tax_rate']/100;
				}else if($folio['type'] == 'DISCOUNT'){
					$total_discount_add[$folio['rr_id']] += $folio['amount'];	
					$reservation['add_payment_items'][$folio['rr_id']]['items'][$folio['type']] = $folio;
				}else if($folio['type'] == 'DEPOSIT'){
					$total_deposit_add[$folio['rr_id']] += $folio['amount'];	
					$reservation['add_payment_items'][$folio['rr_id']]['items'][$folio['type']] = $folio;
				}else{
					$reservation['add_payment_items'][$folio['rr_id']]['items'][$folio['invoice_id'].'_'.$folio['type']] = $folio;
                    $reservation['add_payment_items'][$folio['rr_id']]['items'][$folio['invoice_id'].'_'.$folio['type']]['service_amount'] = $folio['amount']*$folio['service_rate']/100;
                    $reservation['add_payment_items'][$folio['rr_id']]['items'][$folio['invoice_id'].'_'.$folio['type']]['tax_amount'] = ($folio['amount'] + ($folio['amount']*$folio['service_rate']/100))*$folio['tax_rate']/100;
                    $reservation['add_payment_items'][$folio['rr_id']]['items'][$folio['invoice_id'].'_'.$folio['type']]['product'] = $this->get_product($folio['invoice_id']);
					$service_add = ($folio['amount']*$folio['service_rate']/100);
					$tax_add = ($folio['amount'] + $service_add)*$folio['tax_rate']/100;
					$total_other_add[$folio['rr_id']] += $folio['amount'] + $service_add + $tax_add;
                    $total_other_before_tax[$folio['rr_id']] += $folio['amount'];
					$reservation['add_payment_items'][$folio['rr_id']]['total_before_tax'] += $folio['amount'];
                    $reservation['add_payment_items'][$folio['rr_id']]['service_amount'] += $service_add;
					$reservation['add_payment_items'][$folio['rr_id']]['tax_amount'] += $tax_add;
                    $reservation['grand_service_total']+=$service_add;
                    $reservation['grand_tax_total']+=$tax_add;	
				}
				if(isset($add_rr[$folio['rr_id']]))
                {
					$add_rr[$folio['rr_id']]['total_room'] = $total_room_add[$folio['rr_id']];
					$add_rr[$folio['rr_id']]['total_discount'] = $total_discount_add[$folio['rr_id']];
					$add_rr[$folio['rr_id']]['total_deposit'] = $total_deposit_add[$folio['rr_id']];
					$add_rr[$folio['rr_id']]['total_other'] = $total_other_add[$folio['rr_id']];
                    $add_rr[$folio['rr_id']]['total_other_before_tax'] = $total_other_before_tax[$folio['rr_id']];
                    
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
					$add_rr[$folio['rr_id']]['total_other_before_tax'] = $total_other_before_tax[$folio['rr_id']];
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
			$reservation['grand_total_before_tax'] = $reservation['total_before_tax'];
            $reservation['total_deposit'] = $total_deposit_this; 
			$reservation['service_total'] +=$service_this_aftex_discount;
            $reservation['grand_service_total'] +=$service_this_aftex_discount;
			$reservation['tax_total'] +=  $tax_this_aftex_discount;
            $reservation['grand_tax_total'] +=$tax_this_aftex_discount;
			$reservation['total_amount'] += $total_room_this_aftex_discount + $reservation['tax_total'] + $reservation['service_total'] + $total_other_this ;
            $reservation['total'] = $total_room_this_aftex_discount + $reservation['tax_total'] + $reservation['service_total'] + $total_other_this ;
			if($reservation_rooms[$rr_id]['foc_all']==1){
				$reservation['total_before_tax'] = 0;
				$reservation['total'] = 0;
                $reservation['total_amount'] = 0;
                
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
				$add_rr[$b]['total_before_tax'] = $total_room_aftex_discount + $arr['total_other_before_tax'];
                if($reservation_rooms[$arr['id']]['foc_all']==1){
					$add_rr[$b]['total'] = 0;
                    $add_rr[$b]['total_before_tax'] = 0;
				}	
				$reservation['total'] += $add_rr[$b]['total'];
                $reservation['grand_total_before_tax'] += $add_rr[$b]['total_before_tax'];
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
		//$items = array();
        /** daund start gom lai de in rut gon */
        foreach($traveller_folios as $key => $value)
        {
            if($value['type'] == 'ROOM' || $value['ex_code'] == 'LATE_CHECKIN' || $value['ex_code'] == 'EARLY_CHECKIN' || $value['ex_code'] == 'LATE_CHECKOUT')
            {
                $traveller_folios[$key]['type'] = 'ROOM_CHARGE';
            }
        }
        $room_charge = array();
        $minibar_laundary = array();
        $extra_service = array();
        $i=1;
        /**gom room charge, Minibar, laundary, dvmr, bar*/
        //System::debug($traveller_folios);
        ksort($traveller_folios);
        foreach($traveller_folios as $key => $value)
        {   
            if($value['type'] == 'ROOM_CHARGE')
            {
                $id_room_charge = 'ROOM_CHARGE_' . $value['reservation_room_id'] .'_'.$value['type'];
                if(!isset($room_charge[$id_room_charge]['id']))
                {
                    $room_charge[$id_room_charge]['id'] = $id_room_charge;
                    $room_charge[$id_room_charge]['description'] = $value['room_name'] .' '. Portal::language('room_charge');
                    $room_charge[$id_room_charge]['amount'] = $value['amount']; 
                    if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='DISCOUNT')
                    {
                        $room_charge[$id_room_charge]['service_amount'] = $value['amount'] * $value['service_rate']/100;
                        $room_charge[$id_room_charge]['tax_amount'] = $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                        $room_charge[$id_room_charge]['total_amount'] = $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
                    }
                    else if($value['type']=='DISCOUNT')
                    {
                        $room_charge[$id_room_charge]['service_amount'] = $value['amount'] * $value['rr_service_rate']/100;
                        $room_charge[$id_room_charge]['tax_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * $value['rr_tax_rate']/100;
                        $room_charge[$id_room_charge]['total_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * (1+$value['rr_tax_rate']/100);
                    }
                    else
                    {
                        $room_charge[$id_room_charge]['total_amount'] = $value['amount'];
                    }                  
                }else
                {
                    $room_charge[$id_room_charge]['amount'] += $value['amount'];
                    if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='DISCOUNT')
                    {
                        $room_charge[$id_room_charge]['service_amount'] += $value['amount'] * $value['service_rate']/100;
                        $room_charge[$id_room_charge]['tax_amount'] += $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                        $room_charge[$id_room_charge]['total_amount'] += $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
                    }
                    else if($value['type']=='DISCOUNT')
                    {
                        $room_charge[$id_room_charge]['service_amount'] += $value['amount'] * $value['rr_service_rate']/100;
                        $room_charge[$id_room_charge]['tax_amount'] += $value['amount'] * (1+$value['rr_service_rate']/100) * $value['rr_tax_rate']/100;
                        $room_charge[$id_room_charge]['total_amount'] += $value['amount'] * (1+$value['rr_service_rate']/100) * (1+$value['rr_tax_rate']/100);
                    }
                    else
                    {
                        $room_charge[$id_room_charge]['total_amount'] += $value['amount'];
                    }                      
                }
            }
            if($value['type'] == 'BAR')
            {
                $id_bar = 'BAR_' . $value['reservation_room_id'] .'_'.$value['type'];
                if(!isset($room_charge[$id_bar]['id']))
                {
                    $room_charge[$id_bar]['id'] = $id_bar;
                    $room_charge[$id_bar]['description'] = $value['room_name'] . ' BAR';
                    $room_charge[$id_bar]['amount'] = $value['amount']; 
                    if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='DISCOUNT')
                    {
                        $room_charge[$id_bar]['service_amount'] = $value['amount'] * $value['service_rate']/100;
                        $room_charge[$id_bar]['tax_amount'] = $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                        $room_charge[$id_bar]['total_amount'] = $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
                    }
                    else if($value['type']=='DISCOUNT')
                    {
                        $room_charge[$id_bar]['service_amount'] = $value['amount'] * $value['rr_service_rate']/100;
                        $room_charge[$id_bar]['tax_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * $value['rr_tax_rate']/100;
                        $room_charge[$id_bar]['total_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * (1+$value['rr_tax_rate']/100);
                    }
                    else
                    {
                        $room_charge[$id_bar]['total_amount'] = $value['amount'];
                    }
                }else
                {
                    $room_charge[$id_bar]['amount'] += $value['amount']; 
                    if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='DISCOUNT')
                    {
                        $room_charge[$id_bar]['service_amount'] += $value['amount'] * $value['service_rate']/100;
                        $room_charge[$id_bar]['tax_amount'] += $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                        $room_charge[$id_bar]['total_amount'] += $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
                    }
                    else if($value['type']=='DISCOUNT')
                    {
                        $room_charge[$id_bar]['service_amount'] += $value['amount'] * $value['rr_service_rate']/100;
                        $room_charge[$id_bar]['tax_amount'] += $value['amount'] * (1+$value['rr_service_rate']/100) * $value['rr_tax_rate']/100;
                        $room_charge[$id_bar]['total_amount'] += $value['amount'] * (1+$value['rr_service_rate']/100) * (1+$value['rr_tax_rate']/100);
                    }
                    else
                    {
                        $room_charge[$id_bar]['total_amount'] += $value['amount'];
                    }                    
                }                      
            }
            if($value['type'] == 'MINIBAR')
            {
                $id_minibar = 'MINIBAR_' . $value['reservation_room_id'] .'_'.$value['type'];
                if(!isset($room_charge[$id_minibar]['id']))
                {
                    $room_charge[$id_minibar]['id'] = $id_minibar;
                    $room_charge[$id_minibar]['description'] = $value['room_name'] . ' ' . $value['type'];
                    $room_charge[$id_minibar]['amount'] = $value['amount']; 
                    if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='DISCOUNT')
                    {
                        $room_charge[$id_minibar]['service_amount'] = $value['amount'] * $value['service_rate']/100;
                        $room_charge[$id_minibar]['tax_amount'] = $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                        $room_charge[$id_minibar]['total_amount'] = $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
                    }
                    else if($value['type']=='DISCOUNT')
                    {
                        $room_charge[$id_minibar]['service_amount'] = $value['amount'] * $value['rr_service_rate']/100;
                        $room_charge[$id_minibar]['tax_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * $value['rr_tax_rate']/100;
                        $room_charge[$id_minibar]['total_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * (1+$value['rr_tax_rate']/100);
                    }
                    else
                    {
                        $room_charge[$id_minibar]['total_amount'] = $value['amount'];
                    }           
                }else
                {
                    $room_charge[$id_minibar]['amount'] += $value['amount']; 
                    if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='DISCOUNT')
                    {
                        $room_charge[$id_minibar]['service_amount'] += $value['amount'] * $value['service_rate']/100;
                        $room_charge[$id_minibar]['tax_amount'] += $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                        $room_charge[$id_minibar]['total_amount'] += $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
                    }
                    else if($value['type']=='DISCOUNT')
                    {
                        $room_charge[$id_minibar]['service_amount'] += $value['amount'] * $value['rr_service_rate']/100;
                        $room_charge[$id_minibar]['tax_amount'] += $value['amount'] * (1+$value['rr_service_rate']/100) * $value['rr_tax_rate']/100;
                        $room_charge[$id_minibar]['total_amount'] += $value['amount'] * (1+$value['rr_service_rate']/100) * (1+$value['rr_tax_rate']/100);
                    }
                    else
                    {
                        $room_charge[$id_minibar]['total_amount'] += $value['amount'];
                    }                       
                }             
            }
            if($value['type'] == 'LAUNDRY')
            {
                $id_laundry = 'LAUNDRY_' . $value['reservation_room_id'] .'_'.$value['type'];
                if(!isset($room_charge[$id_laundry]['id']))
                {
                    $room_charge[$id_laundry]['id'] = $id_laundry;
                    $room_charge[$id_laundry]['description'] = $value['room_name'] . ' ' . $value['type'];
                    $room_charge[$id_laundry]['amount'] = $value['amount']; 
                    if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='DISCOUNT')
                    {
                        $room_charge[$id_laundry]['service_amount'] = $value['amount'] * $value['service_rate']/100;
                        $room_charge[$id_laundry]['tax_amount'] = $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                        $room_charge[$id_laundry]['total_amount'] = $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
                    }
                    else if($value['type']=='DISCOUNT')
                    {
                        $room_charge[$id_laundry]['service_amount'] = $value['amount'] * $value['rr_service_rate']/100;
                        $room_charge[$id_laundry]['tax_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * $value['rr_tax_rate']/100;
                        $room_charge[$id_laundry]['total_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * (1+$value['rr_tax_rate']/100);
                    }
                    else
                    {
                        $room_charge[$id_laundry]['total_amount'] = $value['amount'];
                    }           
                }else
                {
                    $room_charge[$id_laundry]['amount'] += $value['amount']; 
                    if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='DISCOUNT')
                    {
                        $room_charge[$id_laundry]['service_amount'] += $value['amount'] * $value['service_rate']/100;
                        $room_charge[$id_laundry]['tax_amount'] += $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                        $room_charge[$id_laundry]['total_amount'] += $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
                    }
                    else if($value['type']=='DISCOUNT')
                    {
                        $room_charge[$id_laundry]['service_amount'] += $value['amount'] * $value['rr_service_rate']/100;
                        $room_charge[$id_laundry]['tax_amount'] += $value['amount'] * (1+$value['rr_service_rate']/100) * $value['rr_tax_rate']/100;
                        $room_charge[$id_laundry]['total_amount'] += $value['amount'] * (1+$value['rr_service_rate']/100) * (1+$value['rr_tax_rate']/100);
                    }
                    else
                    {
                        $room_charge[$id_laundry]['total_amount'] += $value['amount'];
                    }                       
                }             
            }
            if($value['type'] == 'EQUIPMENT')
            {
                $id_equipment = 'EQUIPMENT_' . $value['reservation_room_id'] .'_'.$value['type'];
                if(!isset($room_charge[$id_equipment]['id']))
                {
                    $room_charge[$id_equipment]['id'] = $id_equipment;
                    $room_charge[$id_equipment]['description'] = $value['room_name'] . ' ' . $value['type'];
                    $room_charge[$id_equipment]['amount'] = $value['amount']; 
                    if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='DISCOUNT')
                    {
                        $room_charge[$id_equipment]['service_amount'] = $value['amount'] * $value['service_rate']/100;
                        $room_charge[$id_equipment]['tax_amount'] = $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                        $room_charge[$id_equipment]['total_amount'] = $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
                    }
                    else if($value['type']=='DISCOUNT')
                    {
                        $room_charge[$id_equipment]['service_amount'] = $value['amount'] * $value['rr_service_rate']/100;
                        $room_charge[$id_equipment]['tax_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * $value['rr_tax_rate']/100;
                        $room_charge[$id_equipment]['total_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * (1+$value['rr_tax_rate']/100);
                    }
                    else
                    {
                        $room_charge[$id_equipment]['total_amount'] = $value['amount'];
                    }           
                }else
                {
                    $room_charge[$id_equipment]['amount'] += $value['amount']; 
                    if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='DISCOUNT')
                    {
                        $room_charge[$id_equipment]['service_amount'] += $value['amount'] * $value['service_rate']/100;
                        $room_charge[$id_equipment]['tax_amount'] += $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                        $room_charge[$id_equipment]['total_amount'] += $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
                    }
                    else if($value['type']=='DISCOUNT')
                    {
                        $room_charge[$id_equipment]['service_amount'] += $value['amount'] * $value['rr_service_rate']/100;
                        $room_charge[$id_equipment]['tax_amount'] += $value['amount'] * (1+$value['rr_service_rate']/100) * $value['rr_tax_rate']/100;
                        $room_charge[$id_equipment]['total_amount'] += $value['amount'] * (1+$value['rr_service_rate']/100) * (1+$value['rr_tax_rate']/100);
                    }
                    else
                    {
                        $room_charge[$id_equipment]['total_amount'] += $value['amount'];
                    }                       
                }             
            }
            if($value['type'] == 'EXTRA_SERVICE')
            {
                $id_extra_service = 'EXTRA_SERVICE_' . $value['reservation_room_id'] .'_'.$value['ex_code'];
                if(!isset($room_charge[$id_extra_service]['id']))
                {
                    $room_charge[$id_extra_service]['id'] = $id_extra_service;
                    $room_charge[$id_extra_service]['description'] = $value['room_name'] . ' ' . $value['ex_name'];
                    $room_charge[$id_extra_service]['amount'] = $value['amount']; 
                    if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='DISCOUNT')
                    {
                        $room_charge[$id_extra_service]['service_amount'] = $value['amount'] * $value['service_rate']/100;
                        $room_charge[$id_extra_service]['tax_amount'] = $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                        $room_charge[$id_extra_service]['total_amount'] = $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
                    }
                    else if($value['type']=='DISCOUNT')
                    {
                        $room_charge[$id_extra_service]['service_amount'] = $value['amount'] * $value['rr_service_rate']/100;
                        $room_charge[$id_extra_service]['tax_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * $value['rr_tax_rate']/100;
                        $room_charge[$id_extra_service]['total_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * (1+$value['rr_tax_rate']/100);
                    }
                    else
                    {
                        $room_charge[$id_extra_service]['total_amount'] = $value['amount'];
                    }           
                }else
                {
                    $room_charge[$id_extra_service]['amount'] += $value['amount']; 
                    if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='DISCOUNT')
                    {
                        $room_charge[$id_extra_service]['service_amount'] += $value['amount'] * $value['service_rate']/100;
                        $room_charge[$id_extra_service]['tax_amount'] += $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                        $room_charge[$id_extra_service]['total_amount'] += $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
                    }
                    else if($value['type']=='DISCOUNT')
                    {
                        $room_charge[$id_extra_service]['service_amount'] += $value['amount'] * $value['rr_service_rate']/100;
                        $room_charge[$id_extra_service]['tax_amount'] += $value['amount'] * (1+$value['rr_service_rate']/100) * $value['rr_tax_rate']/100;
                        $room_charge[$id_extra_service]['total_amount'] += $value['amount'] * (1+$value['rr_service_rate']/100) * (1+$value['rr_tax_rate']/100);
                    }
                    else
                    {
                        $room_charge[$id_extra_service]['total_amount'] += $value['amount'];
                    }                       
                }             
            }
            
        }
        $this->map['items_short'] = $room_charge;
        /**gom room charge: bao gồm tiền phong, EI,LO, LI*/
        /** daund end gom lai de in rut gon */
		return $reservation;  
	}
	function draw()
    {                
		$this->map = array();
		if(is_numeric(Url::get('traveller_id')))
        {
			$traveller_id = Url::get('traveller_id');
			$folio_id = Url::get('folio_id');
			$items = $this->get_folio_info($traveller_id,$folio_id);            
            
			$folios = DB::fetch('select folio.*,member_level.def_name as member_level,CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as full_name,
                                    reservation.booking_code,reservation_room.arrival_time as arrival_time,
                                    reservation_room.departure_time as departure_time,reservation_room.price as room_rate,
                                    reservation_room.reservation_id as reservation_id ,
                                    folio.code as folio_code
                                from folio 
            						left join member_level on member_level.id=folio.member_level_id
                                    inner join reservation_traveller on reservation_traveller.id=folio.reservation_traveller_id
            						inner join traveller on reservation_traveller.traveller_id = traveller.id
            						inner join reservation_room ON reservation_room.id = reservation_traveller.reservation_room_id
            						inner join reservation ON reservation_room.reservation_id = reservation.id
            					 where folio.id = '.$folio_id.'');
            $this->map['member_code'] = $folios['member_code'];
            $this->map['member_level'] = $folios['member_level'];
			$this->map['tax_total'] = $items['tax_total'];
			$this->map['booking_code'] = $folios['booking_code'];
            $this->map['reservation_id'] = $folios['reservation_id'];
			$this->map['full_name'] = $folios['full_name'];
			$this->map['service_total']=$items['service_total'];
			$this->map['total'] = $folios['total'];
			$this->map['arrival_time'] = $items['arrival_time'];
            $this->map['departure_time'] = $items['departure_time'];
            $this->map['room_rate'] = System::display_number($folios['room_rate']);
            $this->map['total_befor_tax'] = $folios['total'] - $items['tax_total'] - $items['service_total'];
		    $create_folio_user = DB::fetch('select name_1 as name from party where user_id=\''.$folios['user_id'].'\'');
            $this->map['create_folio_user'] = $create_folio_user['name'];
            $this->map['create_folio_time'] = date('H:i d/m/Y',$folios['create_time']);
            $this->map['id'] = $folios['id'];
            $this->map['folio_code'] = $folios['folio_code'];
            //System::debug($this->map['id']);
            $this->map += $items;
			$payments = array();
            
			$payments = DB::fetch_all('SELECT 
								(payment.payment_type_id || \'_\' || payment.credit_card_id || \'_\' || payment.currency_id || \'_\' || payment.folio_id || \'_\' || payment.description ) as id
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
                                ,payment.payment_point    
							FROM payment
								inner join reservation_room on payment.bill_id = reservation_room.id
								left outer join credit_card ON credit_card.id = payment.credit_card_id
								left outer join folio ON folio.id = payment.folio_id
							WHERE 
								1>0 AND payment.folio_id = '.Url::get('folio_id').'
								AND payment.type_dps is null
							GROUP BY 
                                payment.payment_type_id,
                                payment.currency_id,
                                payment.bill_id,
                                payment.folio_id,
                                payment.credit_card_id,
                                credit_card.name,
                                payment.bank_acc,
                                payment.description,
                                payment.payment_point  
							ORDER BY 
                                payment.payment_type_id  ASC
											');
			//System::Debug($payments);
            if($folios['member_code']!=''){
                require_once 'packages/hotel/includes/member.php';
                $this->map['point'] = 0;
                $this->map['point_user'] = 0;
                foreach($payments as $id=>$value)
                {
                    if(isset($value['payment_point']) AND ($value['payment_point']=='on'))
                    {
                        $payment_point = 1;
                    }else
                    {
                        $payment_point = 0;
                    }
                    $arr_point = point($value['payment_type_id'],$value['total'],$payment_point);
                    $this->map['point'] += $arr_point['point'];
                    $this->map['point_user'] += $arr_point['point_user'];   
                }
            }
			$this->map['payments'] = $payments;		
			
		}
        
        $total_payment = DB::fetch('select sum(amount) as amount,SUM(amount*payment.exchange_rate) as total_vnd from payment where folio_id ='.$folio_id);
        $this->map['total_payment1'] =  $total_payment['amount'];
        $this->map['total_payment_vnd'] =  $total_payment['total_vnd'];
		
        $account_name = DB::fetch("SELECT account.id as id, party.name_1 as name FROM account inner join party on party.user_id = account.id WHERE account.id='".User::id()."'");
        $this->map['account_name'] = $account_name['name'];
        
        //giap.ln: lay ra ten phong chang sau cung neu co truong hop doi phong 
        //$this->map['room_name]
        $sql='SELECT reservation_room.id,room.name as room_name 
        	FROM reservation_room
			INNER JOIN room ON room.id=reservation_room.room_id
			WHERE reservation_room.reservation_id='.$this->map['reservation_id'].' AND reservation_room.change_room_to_rr is null AND reservation_room.change_room_from_rr is not null';
        $reservation_room_tail = DB::fetch($sql);
        if(isset( $reservation_room_tail['room_name']))
       	    $this->map['room_name'] = $reservation_room_tail['room_name'];
        //end giap.ln
        
        /** check hoa don thanh toan de ghi tieu de **/
        $this->map['items'] = $items;
        $this->map['check_payment'] = 0;
        if($folios['total']<=0 OR DB::exists('select id from payment where folio_id='.$folio_id.' and type_dps is null')){
            $this->map['check_payment'] = 1;
        }                
        $this->parse_layout('invoice',$this->map);//,'currencies'=>$currencies        
	}
	function get_reservation_room($cond){
		return $reservation_rooms = DB::fetch_all('SELECT
				reservation_room.id,
				reservation_room.reservation_id,
				reservation_room.arrival_time,
				to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') as arrival_time, 
				to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as departure_time,
				(reservation_room.departure_time-reservation_room.arrival_time) as nights, 
				reservation_room.time_in, reservation_room.time_out,
				reservation_room.room_id,
				room.name as room_name,
				concat(traveller.first_name,concat(\' \', traveller.last_name)) as full_name, 
				customer.address, customer.name as customer_name,customer.def_name as customer_def_name,  
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
				customer.address, customer.name as customer_name,customer.def_name as customer_def_name, 
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
    function get_product($invoice_id)
    {
    	$reservation_rooms = DB::fetch_all('
                SELECT
    				housekeeping_invoice_detail.id,product.name_'.Portal::language().' as name
                    ,housekeeping_invoice_detail.price
                    ,housekeeping_invoice_detail.quantity
                    ,housekeeping_invoice_detail.price*housekeeping_invoice_detail.quantity as total_product
    			FROM 
                    housekeeping_invoice_detail
                     left join product on housekeeping_invoice_detail.product_id = product.id
    			WHERE housekeeping_invoice_detail.invoice_id ='.$invoice_id.'
    		 ');
       foreach($reservation_rooms as $key => $value)
        {
            if($value['quantity'] == 0)
            {
                unset($reservation_rooms[$key]);
            }
        }
        return $reservation_rooms;
	}
}
?>