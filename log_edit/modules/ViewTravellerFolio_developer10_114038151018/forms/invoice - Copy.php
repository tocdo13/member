<?php
class ViewGroupInvoiceForm  extends Form
{
	function ViewGroupInvoiceForm(){
		Form::Form('ViewGroupInvoiceForm');
	}
	function get_items($reservation_traveller_id,$reservation_id,$folio_id){
        require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/currency.php';
       
		$this->map['bill_number'] = $folio_id;
		$this->map['description'] ='';
        $this->map['total_amount'] = 0;
        $this->map['total_foc'] = 0;
        $this->map['total_deposit'] = 0;
        $this->map['total_group_deposit'] = 0;
        $this->map['total_payment'] = 0;
		//--------------------------------------lay exchange------------------------------------------------
		if(HOTEL_CURRENCY == 'VND'){
			$this->map['exchange_currency_id'] = 'USD';
		}else{  
			$this->map['exchange_currency_id'] = 'VND';	    
		}
		$this->map['exchange_rate'] = DB::fetch('select id,exchange from currency where id=\''.$this->map['exchange_currency_id'].'\'','exchange');
		
        $reservation_rooms_1 = $this->get_reservation_room($reservation_id);
		foreach($reservation_rooms_1 as $k=> $room){
			$this->map['customer_name'] = $room['customer_name'];
            $this->map['guest'] = $room['customer_name'];
			$this->map['tour_name'] = $room['tour_name'];
			$this->map['address'] = $room['address'];
            $this->map['departure_time'] = $room['departure_time'];
            $this->map['arrival_time'] = $room['arrival_time'];
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
                       ,housekeeping_invoice.position
                       ,bar_reservation.code
					   ,trf.reservation_room_id as rr_id 
					   ,reservation_room.time_in
					   ,reservation_room.time_out
                       ,reservation_room.arrival_time as arrival_time
                       ,reservation_room.departure_time as departure_time
                       ,reservation_room.service_rate as rr_service_rate
                       ,reservation_room.tax_rate as rr_tax_rate
					from 
					   traveller_folio trf 
					   inner join folio ON folio.id = trf.folio_id                           
					   left join reservation_room ON (reservation_room.id = trf.reservation_room_id and reservation_room.reservation_id = '.$reservation_id.')
                       left join bar_reservation ON bar_reservation.reservation_room_id = reservation_room.id
                       left join housekeeping_invoice on housekeeping_invoice.id = trf.invoice_id and (trf.type=\'MINIBAR\' or trf.type=\'LAUNDRY\' or trf.type=\'EQUIP\')
                       left join extra_service_invoice_detail on extra_service_invoice_detail.id = trf.invoice_id and trf.type=\'EXTRA_SERVICE\'
        
 					where 1>0 '.$cond.'
					 order by trf.date_use,trf.type ';  
		$traveller_folios = DB::fetch_all($sql);
         
		foreach($traveller_folios as $key => $value)
        {
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
            else
            {
                $traveller_folios[$key]['total_amount'] = $value['amount'];
            }
            switch ($value['type'])
            {
                case 'DEPOSIT' : $this->map['total_deposit'] += $traveller_folios[$key]['total_amount']; break;
                case 'DEPOSIT_GROUP' : $this->map['total_group_deposit'] += $traveller_folios[$key]['total_amount']; break;
                case 'DISCOUNT' : 
                    $this->map['total_amount'] -= $traveller_folios[$key]['total_amount']; 
                    //if($value['foc'] or $value['foc_all']) $this->map['total_foc'] -= $traveller_folios[$key]['total_amount'];
                    break;
                default : 
                    $this->map['total_amount'] += $traveller_folios[$key]['total_amount']; 
                    //if(($value['type'] == 'ROOM' and $value['foc']) or $value['foc_all']) $this->map['total_foc'] += $traveller_folios[$key]['total_amount'];
                    break;                   
            }
        }
        $this->map['reservation_id'] = $reservation_id;
        $this->map['total_payment'] = $this->map['total_amount'] - $this->map['total_group_deposit'] - $this->map['total_deposit'] - $this->map['total_foc'];
        $this->map['items'] = $traveller_folios;
        
		return $this->map;  
	}
	function draw()
    {
		$this->map= array();
		if(Url::get('folio_id')){
			$folio_id = Url::get('folio_id');
            $folios = DB::fetch('select traveller_folio.* , folio.code as folio_code
                                 from traveller_folio 
                                      inner join folio on folio.id = traveller_folio.folio_id
                                 where traveller_folio.folio_id='.$folio_id.'');
            if(User::id()=='developer12')
            {
               // System::debug($folios);
            }
           
            
           $this->map['folio_code']  = $folios['folio_code'];
           
            //System::debug($value['folio_code']);
            $reservation_traveller_id = $folios['reservation_traveller_id'];
			$reservation_id = Url::get('id')?Url::get('id'):$folios['reservation_id'];
			$traveller_folio = DB::fetch('select * from folio where id='.$folio_id.'');
            $create_folio_user = DB::fetch('select name_1 as name from party where user_id=\''.$traveller_folio['user_id'].'\'');
            $this->map['create_folio_user'] = $create_folio_user['name'];
            $this->map['create_folio_time'] = date('H:i d/m/Y',$traveller_folio['create_time']);
            if(empty($traveller_folio['reservation_traveller_id'])){
                $this->map['traveller_name'] = "";
            }else{
                $traveller_name = DB::fetch("select traveller.id as id, 
                                                concat(concat(traveller.first_name,' '),traveller.last_name) as full_name 
                                            from traveller 
                                                inner join reservation_traveller on reservation_traveller.traveller_id = traveller.id 
                                            where reservation_traveller.id =".$traveller_folio['reservation_traveller_id']);
                $this->map['traveller_name'] = $traveller_name['full_name'];
            }
            
			$this->get_items($reservation_traveller_id,$reservation_id,$folio_id);
            
            //start: KID them 2 dong duoi tinh so tien da thanh toan
            $total_payment = DB::fetch('select sum(amount) as amount,SUM(amount*payment.exchange_rate) as total_vnd from payment where folio_id ='.$folio_id);
            $this->map['total_payment_vnd'] =  $total_payment['total_vnd'];
            $this->map['total_payment1'] =  $total_payment['amount'];
			//end : KID
            
            // Ph?n lo?i ti?n thanh to?n:
			$sql = 'SELECT 
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
                                payment.description,
                                payment.payment_point
							ORDER BY payment.payment_type_id ASC
											';
			$payments = DB::fetch_all($sql);	
            $this->map['payments'] = $payments;	
            //if(User::id() == 'developer04'){
                //System::debug($this->map);	
                //exit();
            //}
            $account_name = DB::fetch("SELECT account.id as id, party.name_1 as name FROM account inner join party on party.user_id = account.id WHERE account.id='".User::id()."'");
            $this->map['account_name'] = $account_name['name'];
            //System::debug($this->map);
            $member_code = DB::fetch("SELECT folio.member_code,member_level.def_name as member_level FROM folio left join member_level on member_level.id=folio.member_level_id WHERE folio.id=".Url::get('folio_id'));
            if(sizeof($member_code)>0)
            {
                require_once 'packages/hotel/includes/member.php';
                $this->map['member_code'] = $member_code['member_code'];
                $this->map['member_level'] = $member_code['member_level'];
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
            }else
            {
                $this->map['member_code'] = '';
                $this->map['member_level'] = '';
            }
            //System::debug($this->map);
            $this->parse_layout('group_invoice',$this->map);
		}
	}
	function get_reservation_room($reservation_id){
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
}
?>
