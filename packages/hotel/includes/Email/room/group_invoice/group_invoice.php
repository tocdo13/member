<?php
function setGroupInvoice($reservation_id,$folio_id)
{
	$itemsSend= array();
	if($folio_id)
    {
        $folios = DB::fetch('select * from traveller_folio where traveller_folio.folio_id='.$folio_id.'');
        $reservation_traveller_id = $folios['reservation_traveller_id'];
		$reservation_id = Url::get('id')?Url::get('id'):$folios['reservation_id'];
		$traveller_folio = DB::fetch('select * from folio where id='.$folio_id.'');
        
// Lấy tên traveller		
        if(empty($traveller_folio['reservation_traveller_id']))
        {
            $itemsSend['traveller_name'] = "";
        }
        else
        {
            $traveller_name = DB::fetch("select traveller.id as id, 
                                            concat(concat(traveller.first_name,' '),traveller.last_name) as full_name 
                                        from traveller 
                                            inner join reservation_traveller on reservation_traveller.traveller_id = traveller.id 
                                        where reservation_traveller.id =".$traveller_folio['reservation_traveller_id']);
            $itemsSend['traveller_name'] = $traveller_name['full_name'];
        }
        
		$items = get_items($reservation_traveller_id,$reservation_id,$folio_id);    
       
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
                            ,payment.bank_acc
                            ,payment.description    
						FROM payment
							inner join reservation on payment.bill_id = reservation.id
							left outer join credit_card ON credit_card.id = payment.credit_card_id
							left outer join folio ON folio.id = payment.folio_id
						WHERE 
							1>0 AND payment.bill_id = '.$reservation_id.' AND payment.folio_id = '.$folio_id.'
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
						ORDER BY payment.payment_type_id ASC
                        
										';
		$payments = DB::fetch_all($sql);				
//system::debug($payments);		
        $itemsSend['payments'] = $payments;
        $account_name = DB::fetch("SELECT account.id as id, party.name_1 as name FROM account inner join party on party.user_id = account.id WHERE account.id='".User::id()."'");
        $itemsSend['account_name'] = $account_name['name'];
        $itemsSend += $items;
       // system::debug($itemsSend);
        return $itemsSend;
        //$this->parse_layout('group_invoice',$itemsSend);
	}
}
function get_items($reservation_traveller_id,$reservation_id,$folio_id)
{
    require_once 'packages/core/includes/utils/time_select.php';
	require_once 'packages/core/includes/utils/currency.php';
   
	$itemsSend['bill_number'] = $folio_id;
	$itemsSend['description'] ='';
    $itemsSend['total_amount'] = 0;
    $itemsSend['total_foc'] = 0;
    $itemsSend['total_deposit'] = 0;
    $itemsSend['total_group_deposit'] = 0;
    $itemsSend['total_payment'] = 0;
	//--------------------------------------lay exchange------------------------------------------------
	if(HOTEL_CURRENCY == 'VND')
    {
		$itemsSend['exchange_currency_id'] = 'USD';
	}
    else
    {  
		$itemsSend['exchange_currency_id'] = 'VND';	    
	}
	$itemsSend['exchange_rate'] = DB::fetch('select id,exchange from currency where id=\''.$itemsSend['exchange_currency_id'].'\'','exchange');
	// lấy ra Exchange_rate và Exchange_currency
    
    $reservation_rooms_1 = get_reservation_room_group_invoice($reservation_id);
// Lấy ra Tên customer, địa chỉ, giờ đến giờ đi    
//    System::debug($reservation_rooms_1);die();
	foreach($reservation_rooms_1 as $k=> $room)
    {
		$itemsSend['customer_name'] = $room['customer_name'];
        $itemsSend['guest'] = $room['customer_name'];
		$itemsSend['address'] = $room['address'];
        $itemsSend['departure_time'] = $room['departure_time'];
        $itemsSend['arrival_time'] = $room['arrival_time'];
	}
    
    $cond ='';
	$cond .= ($folio_id!='')?' AND trf.folio_id='.$folio_id.'':'';
	$cond .= ($reservation_traveller_id!='')?' AND trf.reservation_traveller_id='.$reservation_traveller_id.'':'';
    
    
	$sql =' SELECT 
				   trf.*
                   ,(
                   CASE 
                    WHEN housekeeping_invoice.code is not null THEN housekeeping_invoice.code
                    ELSE extra_service_invoice.code
                   END
                   ) as hk_code
				   ,trf.reservation_room_id as rr_id 
				   ,reservation_room.time_in
				   ,reservation_room.time_out
                   ,reservation_room.arrival_time as arrival_time
                   ,reservation_room.departure_time as departure_time
                   ,reservation_room.service_rate as rr_service_rate
                   ,reservation_room.tax_rate as rr_tax_rate
                   ,extra_service_invoice_detail.note as ex_note
				from 
				   traveller_folio trf 
				   inner join folio ON folio.id = trf.folio_id   
				   left join reservation_room ON (reservation_room.id = trf.reservation_room_id and reservation_room.reservation_id = '.$reservation_id.')
                   left join housekeeping_invoice on housekeeping_invoice.id = trf.invoice_id and (trf.type=\'MINIBAR\' or trf.type=\'LAUNDRY\' or trf.type=\'EQUIP\')
                   left join extra_service_invoice_detail on extra_service_invoice_detail.id = trf.invoice_id and trf.type=\'EXTRA_SERVICE\'
                   left join extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
				 where 1>0 '.$cond.'
				 order by trf.date_use,trf.type ';  
	$traveller_folios = DB::fetch_all($sql);
//System::debug($traveller_folios);die();    
	foreach($traveller_folios as $key => $value)
    {
        //Nếu ko phải Tiền đặt cọc hay Loại Giảm giá thì Service_amount, tax amount, total amount
        if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='DISCOUNT')
        {
            $traveller_folios[$key]['service_amount'] = $value['amount'] * $value['service_rate']/100;
            $traveller_folios[$key]['tax_amount'] = $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
            $traveller_folios[$key]['total_amount'] = $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
        }
        else if($value['type']=='DISCOUNT')
        {
            $traveller_folios[$key]['service_amount'] = $value['amount'] * $value['rr_service_rate']/100;
            $traveller_folios[$key]['tax_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * $value['rr_tax_rate']/100;
            $traveller_folios[$key]['total_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * (1+$value['rr_tax_rate']/100);
        }
        // Nếu là các dịch vụ khác ngoài thuê phí thì sẽ lấy tổng là amount
        else
        {
            $traveller_folios[$key]['total_amount'] = $value['amount'];
        }
        // Lấy tổng Đặt cọc, Giảm giá Và Tổng các dịch vụ
        switch ($value['type'])
        {
            case 'DEPOSIT' : $itemsSend['total_deposit'] += $traveller_folios[$key]['total_amount']; break;
            case 'DEPOSIT_GROUP' : $itemsSend['total_group_deposit'] += $traveller_folios[$key]['total_amount']; break;
            case 'DISCOUNT' : 
                $itemsSend['total_amount'] -= $traveller_folios[$key]['total_amount']; 
                if($value['foc'] or $value['foc_all']) $itemsSend['total_foc'] -= $traveller_folios[$key]['total_amount'];
                break;
            default : 
                $itemsSend['total_amount'] += $traveller_folios[$key]['total_amount']; 
                if(($value['type'] == 'ROOM' and $value['foc']) or $value['foc_all']) $itemsSend['total_foc'] += $traveller_folios[$key]['total_amount'];
                break;                   
        }
    }
    $itemsSend['reservation_id'] = $reservation_id;
    
    // Tống thanh tpán: Total Payment = Tổng trừ đi Đặt cọc
    $itemsSend['total_payment'] = $itemsSend['total_amount'] - $itemsSend['total_group_deposit'] - $itemsSend['total_deposit'] - $itemsSend['total_foc'];
    $itemsSend['items'] = $traveller_folios;
    
	return $itemsSend;  
}
function get_reservation_room_group_invoice($reservation_id)
{
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
		WHERE reservation.id ='.$reservation_id.'
	 ');
}
?>