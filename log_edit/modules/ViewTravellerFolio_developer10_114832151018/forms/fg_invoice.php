<?php
class FGViewGroupInvoiceForm  extends Form
{
	function FGViewGroupInvoiceForm(){
		Form::Form('FGViewGroupInvoiceForm');
	}
	function get_items($customer_id,$id,$folio_id){
		$bill_id ='';
		for($i=0;$i<6-strlen($customer_id);$i++){
			$bill_id .= '0';
		}
		$this->map['bill_number'] = $folio_id;
		$this->map['description']='';
		require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/currency.php';
		//--------------------------------------lay exchange------------------------------------------------
		if(HOTEL_CURRENCY == 'VND'){
			$this->map['exchange_currency_id'] = 'USD';
		}else{  
			$this->map['exchange_currency_id'] = 'VND';	    
		}
		$this->map['exchange_rate'] = DB::fetch('select id,exchange from currency where id=\''.$this->map['exchange_currency_id'].'\'','exchange');
		$cond ='';
		$cond .= ($folio_id!='')?' AND trf.folio_id='.$folio_id.'':'';
		$cond .= ($customer_id!='')?' AND trf.reservation_traveller_id='.$customer_id.'':'';
		$sql =' SELECT 
					   trf.*
					   ,trf.reservation_room_id as rr_id 
					   ,reservation_room.time_in
					   ,reservation_room.time_out 
					from 
					   traveller_folio trf 
					   inner join folio ON folio.id = trf.folio_id   
					   INNER JOIN reservation_room ON reservation_room.id = trf.reservation_room_id
					 where 1>0 '.$cond.'
					 order by trf.type ';  
		$traveller_folios = DB::fetch_all($sql);  
		$reservation_room = array();
		$t=0; $rr_id = 0;
		$reservation_rooms = $this->get_reservation_room($id);
		foreach($reservation_rooms as $k=> $room){
			$total_room_this[$k] = 0;
			$total_discount_this[$k] = 0;
			$total_deposit_this[$k] = 0;
			$total_other_this[$k] = 0;
			$reservation_rooms[$k]['total'] = 0;
			$reservation_rooms[$k]['service_total'] = 0;
			$reservation_rooms[$k]['tax_total'] = 0;
			$this->map['customer_name'] = $room['customer_name'];
			$this->map['tour_name'] = $room['tour_name'];
			$this->map['address'] = $room['address'];
		}
		$total_amount = 0;
		$service_amount = 0;
		$tax_amount = 0;
		$sub_total = 0;
		foreach($traveller_folios as $id=>$folio){
			if($folio['add_payment']==2){
				$rr_id = $folio['rr_id']; 
				if($folio['type'] != 'DEPOSIT_GROUP'){
					if($folio['type']=='ROOM'){
						$total_room_this[$folio['rr_id']] += $folio['amount'];
						if(isset($reservation_rooms[$folio['rr_id']][strtoupper($folio['type'])])){
							$reservation_rooms[$folio['rr_id']][strtoupper($folio['type'])] += $folio['amount'];
						}else{
							$reservation_rooms[$folio['rr_id']][strtoupper($folio['type'])] = $folio['amount'];
						}	
					}else if($folio['type']=='DEPOSIT'){
						$reservation_rooms[$folio['rr_id']][strtoupper($folio['type'])] = $folio['amount'];
						$total_deposit_this[$folio['rr_id']] += $folio['amount'];
					}else if($folio['type']=='DISCOUNT'){
						$reservation_rooms[$folio['rr_id']][strtoupper($folio['type'])] = $folio['amount'];
						$total_discount_this[$folio['rr_id']] += $folio['amount'];
					}else{
						if(isset($reservation_rooms[$folio['rr_id']][strtoupper($folio['type'])])){
							$reservation_rooms[$folio['rr_id']][strtoupper($folio['type'])] += $folio['amount'];
						}else{
							$reservation_rooms[$folio['rr_id']][strtoupper($folio['type'])] = $folio['amount'];
						}
						$reservation_rooms[$folio['rr_id']]['service_total'] += $folio['amount']*$folio['service_rate']/100;
						$reservation_rooms[$folio['rr_id']]['tax_total'] += ($folio['amount'] + ($folio['amount']*$folio['service_rate']/100))*$folio['tax_rate']/100;
						$total_other_this[$rr_id] += $folio['amount'];
					}
				}else{
					$this->map['deposit_groups'] = $folio['amount'];
				}
			}
		}
		foreach($reservation_rooms as $rr_id => $rr){
			if($rr['foc']!=''){
				$total_room_this[$rr_id] = 0;
			}		
			//$reservation += $reservation_rooms[$rr_id];
			$total_room[$rr_id] = $total_room_this[$rr_id] - $total_discount_this[$rr_id];
			$service_room[$rr_id] = $total_room[$rr_id] * $reservation_rooms[$rr_id]['service_rate']/100;
			$tax_room[$rr_id] = ($total_room[$rr_id] + $service_room[$rr_id])*$reservation_rooms[$rr_id]['tax_rate']/100;
			$reservation_rooms[$rr_id]['total_before_tax'] = $total_room[$rr_id] + $total_other_this[$rr_id];
			$reservation_rooms[$rr_id]['total_deposit'] = $total_deposit_this[$rr_id]; 
			$reservation_rooms[$rr_id]['service_total'] +=$service_room[$rr_id];
			$reservation_rooms[$rr_id]['tax_total'] +=  $tax_room[$rr_id];
			$reservation_rooms[$rr_id]['total'] = $total_room[$rr_id] + $reservation_rooms[$rr_id]['tax_total'] + $reservation_rooms[$rr_id]['service_total'] + $total_other_this[$rr_id] - $total_deposit_this[$rr_id];
			if($rr['foc_all']==1){
				$reservation_rooms[$rr_id]['total_before_tax'] = 0;
				$reservation_rooms[$rr_id]['total'] = 0;
			}
			$sub_total += $reservation_rooms[$rr_id]['total'];
		}
		$this->map['reservation_rooms'] = $reservation_rooms;
		$this->map['sub_total'] = $sub_total;
		//System::Debug($reservation_rooms);
		//$items = array();
		return $this->map;  
	}
	function draw(){ 
		$this->map= array();
		if(Url::get('folio_id')){
			$this->map['deposit'] = 0;
			$this->map['deposit_group'] = 0;
			$folio_id = Url::get('folio_id');
			$customer = DB::fetch('select reservation_traveller_id as customer_id,reservation_id as id from traveller_folio where folio_id = '.$folio_id.' '); 
			$customer_id = url::get('traveller_id')?url::get('traveller_id'):$customer['customer_id'];
			$id = Url::get('id')?Url::get('id'):$customer['id'];
			$folios = DB::fetch('select * from folio where id='.$folio_id.'');
			$items  =	$this->get_items($customer_id,$id,$folio_id);
			$this->map['items'] = $items['reservation_rooms'];
			
			//System::Debug($this->map['items']);
			$this->map['guest'] = $items['customer_name'];
			$this->map['tour_name'] = $items['tour_name'];
			//$this->map['service_amount'] = $folios['service_amount'];
			if(isset($items['deposit_groups']) && $items['deposit_groups']>0){
				$this->map['deposit_group'] = $items['deposit_groups'];
			}
			//$this->map['tax_amount'] = $folios['tax_amount'];
			$this->map['total'] = $items['sub_total'];
			$this->map['reservation_id'] = $id;
			// Ph?n lo?i ti?n thanh to�n:
			$sql = 'SELECT 
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
							FROM payment
								inner join reservation on payment.bill_id = reservation.id
								left outer join credit_card ON credit_card.id = payment.credit_card_id
								left outer join folio ON folio.id = payment.folio_id
							WHERE 
								1>0 AND payment.bill_id = '.$id.' AND payment.folio_id = '.$folio_id.'
								AND payment.type_dps is null
							GROUP BY payment.payment_type_id,payment.currency_id,payment.bill_id
							,payment.folio_id,payment.credit_card_id,credit_card.name
							ORDER BY payment.payment_type_id ASC
											';
			$payments = DB::fetch_all($sql);				
			//System::Debug($payments);
			$this->map['payments'] = $payments;	
		   $this->parse_layout('fg_invoice',$this->map);
		}
	}
	function get_reservation_room($id){
		return $reservation_rooms = DB::fetch_all('SELECT
				reservation_room.id,
				reservation_room.arrival_time,
				to_char(reservation_room.arrival_time,\'DD/MM\') as arrival_time, 
				to_char(reservation_room.departure_time,\'DD/MM\') as departure_time,
				(reservation_room.departure_time-reservation_room.arrival_time) as night, 
				reservation_room.time_in, reservation_room.time_out,
				reservation_room.room_id,
                reservation_room.price as room_price,
				room.name as room_name,
				concat(traveller.first_name,concat(\' \', traveller.last_name)) as full_name, 
				customer.address, customer.name as customer_name, 
				reservation_room.foc,
				reservation_room.foc_all,
				reservation_room.service_rate,
				reservation_room.tax_rate,
				tour.name as tour_name,
				customer.address
			FROM reservation_room
				INNER JOIN reservation ON reservation_room.reservation_id = reservation.id
				INNER JOIN room ON room.id = reservation_room.room_id
				left outer join customer ON customer.id = reservation.customer_id
				left outer join tour ON tour.id = reservation.tour_id
				left outer join reservation_traveller ON reservation_traveller.reservation_room_id = reservation_room.id
				left outer join traveller ON reservation_traveller.traveller_id = traveller.id
			WHERE reservation.id ='.$id.'
		 ');
	}
}
?>