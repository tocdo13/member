<?php 
class PrintPaymentForm extends Form{
	function PrintPaymentForm(){
		Form::Form('PrintPaymentForm');
	}
	function draw(){
		if(Url::get('index')){
			$ids = Url::get('index');
			$cond = '';
			if(strpos($ids,",")){
				$item = explode(",",$ids);
				for($i=0;$i<count($item);$i++){
					if($item[$i]!=''){
						$cond .= ($cond=='')?' payment.id='.$item[$i].'':' OR payment.id='.$item[$i].'';	
					}
				}
			}else{
				$cond = ' payment.id='.$ids.'';
			}
            
            
            
			$sql='SELECT 
					 payment.id
					,payment.time
					,payment.payment_type_id
					,payment.currency_id
					,payment.amount   
					,payment.description as note
                    ,party.full_name
				FROM payment
                    LEFT JOIN party ON payment.user_id = party.user_id      
				WHERE 1>0 AND ('.$cond.')	  
				';
			$items = DB::fetch_all($sql);
            //System::debug($items);
            
            foreach($items as $key=>$value)
            {
                $sql = "SELECT * FROM currency WHERE id='".$value['currency_id']."'";
                $currency = DB::fetch($sql);
                $items[$key]['amount'] = $currency['exchange'] * $value['amount'];
            }
            
            
            
			$customers = array();
			$bar_reservations = array();  
			if(Url::get('type')=='ROOM' || Url::get('type')=='RESERVATION'){
				if(Url::get('customer_id')){
					$sql= 'SELECT  customer.id,customer.name as full_name
									,\' \' as room_name
							FROM 
								customer
							WHERE id ='.Url::get('customer_id').'
								 ';	
					$customers = DB::fetch_all($sql);
                    $booking_code = DB::fetch('SELECT reservation.id FROM reservation WHERE reservation.id='.Url::get('id'));
                    $this->map['booking_code'] = $booking_code['id'];
				}else if(Url::get('traveller_id') || Url::get('id')){
					if(Url::get('traveller_id')){
						$con = 'AND reservation_traveller.id ='.Url::get('traveller_id').' AND reservation_room.id ='.Url::get('id').'';
					}else{
						$con = ' AND reservation_room.id ='.Url::get('id').'';	
					}
					$sql= 'SELECT reservation_room.id
								 ,room.name as room_name
								 ,CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as full_name
							FROM reservation_room
								INNER JOIN room ON reservation_room.room_id = room.id
								LEFT OUTER JOIN reservation_traveller ON reservation_traveller.reservation_room_id = reservation_room.id
								LEFT OUTER JOIN traveller ON reservation_traveller.traveller_id = traveller.id
							WHERE  1>0 '.$con.'
								 ';	
					$customers = DB::fetch_all($sql);
                    $booking_code = DB::fetch('SELECT reservation.id FROM reservation inner join reservation_room on reservation_room.reservation_id = reservation.id WHERE 1=1 '.$con.'');
                    $this->map['booking_code'] = $booking_code['id'];
				}
			}else{
				$bar_reservations = DB::fetch('select bar_reservation.*,bar_table.name as table_name from bar_reservation
									INNER JOIN bar_reservation_table ON bar_reservation_table.bar_reservation_id = bar_reservation.id
									INNER JOIN bar_table ON bar_table.id = bar_reservation_table.table_id
				 					where bar_reservation.id='.Url::get('id').'');
				if($bar_reservations['reservation_traveller_id'] !=''){
					$customers = DB::fetch_all('SELECT reservation_room.id
								 ,room.name as room_name
								 ,CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as full_name
							FROM reservation_room
								INNER JOIN room ON reservation_room.room_id = room.id
								LEFT OUTER JOIN reservation_traveller ON reservation_traveller.reservation_room_id = reservation_room.id
								LEFT OUTER JOIN traveller ON reservation_traveller.traveller_id = traveller.id
							WHERE  1>0 AND reservation_traveller.id = '.$bar_reservations['reservation_traveller_id'].'');	
				}else if($bar_reservations['reservation_room_id']!=0){
					$customers = DB::fetch_all('SELECT  customer.id,customer.name as full_name
									,room.name as room_name
							FROM 
								reservation_room 
								INNER JOIN reservation ON reservation_room.reservation_id = reservation.id
								INNER JOIN customer ON customer.id = reservation.customer_id
								INNER JOIN room ON reservation_room.room_id = room.id
							WHERE reservation_room.id ='.$bar_reservations['reservation_room_id'].'');	
				}else{
					$customers[1]['id'] = 1;
					$customers[1]['name'] = $bar_reservations['receiver_name'];			
					$customers[1]['room_name'] = $bar_reservations['table_name'];
					$customers[1]['full_name'] = '';
				}
                $this->map['booking_code'] = Url::get('id');
			}
            $user_data = Session::get('user_data');
            $this->map['person_print'] = isset($user_data['full_name'])?$user_data['full_name']:Session::get('user_id');            
			$this->map['orders'] = $bar_reservations;
			$this->map['items'] = $items;
			$this->map['customers'] = $customers;
		
			$this->parse_layout('print',$this->map);
            	
		}
	}
}
?>  