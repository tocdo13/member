<?php
class PrintOrder extends Form
{
	function PrintOrder()
	{
	   Form::Form("PrintOrder");
	   $this->add('id',new IDType(true,'object_not_exists','party_reservation'));
    }
   	function draw()
	{ 
       require_once 'packages/core/includes/utils/currency.php';// include den thu vien nay de lay ham currency_to_text
		if(Url::get('id') and $row = DB::fetch('Select 
                                                    party_reservation.*, 
                                                    party_type.name as party_type_name 
                                                from party_reservation 
                                                    inner join party_type on party_reservation.party_type = party_type.id 
                                                where 
                                                    party_reservation.id =  '.Url::get('id')))
		{
		  //System::debug($row);
		  $total_amount=0;  
    	     $total_product_price=0;
             $total_room_price=0;
             $id=Url::get('id');
		  $items = DB::fetch_all('SELECT party_reservation_detail.*, 
                                party_reservation.user_id, 
                                product.name_'.Portal::language().' as product_name
						FROM party_reservation_detail inner join  party_reservation on  party_reservation_detail. party_reservation_id =  party_reservation.id
                        inner join product on party_reservation_detail.product_id = product.id 	
		 				WHERE party_reservation_detail.party_reservation_id = '.Url::get('id').'and product_id is not null');
    	   if($items)
    	   {
             //$total_room_price=0;  
    		foreach($items as $key=>$value)
    		{ 
    			$items[$key]['id'] = $value['party_reservation_id'];
    			$items[$key]['quantity'] = $value['quantity'];
                $items[$key]['product_name'] = $value['product_name'];
                $items[$key]['price'] = $value['price'];
                $items[$key]['total'] = $items[$key]['quantity']*$items[$key]['price'];
		        $items[$key]['user_id'] = $value['user_id'];
                $total_product_price +=$items[$key]['total'];
            }   
            //system::debug($items);
            
            //$user_name = $items[$key]['user_id'];
           }
           $rooms= DB::fetch_all('SELECT party_reservation_room.id,  
                                party_reservation.id as res_id,
                                party_reservation_room.price,
                                party_room.name
						FROM party_reservation inner join  party_reservation_room on  party_reservation.id =  party_reservation_room.party_reservation_id 
                        inner join party_room on party_reservation_room.party_room_id = party_room.id 	
		 				WHERE party_reservation.id = '.Url::get('id').'');
        if($rooms)
    	   {
                  
        		foreach($rooms as $k=>$v)
        		{ 
        			$rooms[$k]['id'] = $v['id'];
                    $rooms[$k]['name'] = $v['name'];
                    $rooms[$k]['price'] = $v['price'];
                    $total_room_price += $rooms[$k]['price'];
                }
            }  
            //system::debug($total_room_price);
        }
        //System::debug($rooms);
        $total_amount = $total_product_price+$total_room_price;
        
        $traveler =DB::fetch_all( '
                SELECT  
                      party_reservation.id ,
                      party_reservation.full_name,
                      party_reservation.address
				FROM party_reservation 
 				WHERE party_reservation.id = '.Url::get('id').'
        ');
        
       if($vat=DB::fetch_all('SELECT
                         party_reservation.id,
                         party_reservation.vat,
                         party_reservation.extra_service_rate,
                         party_reservation.deposit_1,
                         party_reservation.deposit_2,
                         party_reservation.deposit_3,
                         party_reservation.deposit_4  
						FROM party_reservation 	
		 				WHERE party_reservation.id = '.Url::get('id').''));
        {
           $total_vat=0;
           $total_extra=0;
           foreach($vat as $i=>$n)
           {
                
                $total_extra = ($total_amount *$n['extra_service_rate']*0.01);
                $total_vat = (($total_amount+$total_extra)*$n['vat'])/100;
                $total_deposit = $n['deposit_1'] + $n['deposit_2'] +$n['deposit_3'] +$n['deposit_4'] ;
           }
           
        }
        $id_reservation=Url::get('id');
        /* trung lay ra hinh thuc thanh toan cho hoa don dat tiec */
        $type_payment=DB::fetch_all('
                            select 
                                payment.id as id,
                                party_reservation.id as party_rr_id,
                                payment.bill_id,
                                payment.type,
                                payment.payment_type_id,
                                payment.amount,
                                payment_type.def_code,
                                payment_type.name_1
                            from
                                payment
                                    left join party_reservation on party_reservation.id=payment.bill_id   
                                    left join payment_type on payment_type.def_code=payment.payment_type_id
                                where
                                     payment.type=\'BANQUET\' 
                                     and party_reservation.id ='.$id_reservation.'
        ');
        
        /* trung lay ra hinh thuc thanh toan cho hoa don dat tiec */
        $total_amount_1=$total_amount+$total_vat+$total_extra; // tong tien thanh toan 
        $total = $total_amount+$total_vat+$total_extra -$total_deposit; // tong tien thanh toan
        /* trung lay ra hinh thuc thanh toan cho hoa don dat tiec */
        $payment_type_1='';
        foreach( $type_payment as $k=>$v)
        {
            
                $payment_type_1 .= $v['name_1'].':&nbsp;&nbsp;'.System::display_number($v['amount']).'&nbsp;&nbsp;'   ;
            
        }
        
        /* trung lay ra hinh thuc thanh toan cho hoa don dat tiec */
        $user_data = Session::get('user_data');
        $user_name_1 = isset($user_data['full_name'])?$user_data['full_name']:Session::get('user_id');
        //$traveller = DB::fetch_all('select party_reservation.full_name,party_reservation.address from party_reservation ');
        //System::debug($traveller);
        $this->parse_layout('print_order',array('id'=>$id,
				'items'=>$items,
                'total_product_price'=>$total_product_price,
                'total_room_price'=>$total_room_price,
                'total_amount'=>$total_amount,
                'rooms'=>$rooms,
                'total_vat'=>$total_vat,
                'total_extra'=>$total_extra,
                'total_deposit'=>$total_deposit,
                'total'=>$total,
                'total_in_word' => currency_to_text($total),// tong tien thanh toan hien thi bang chu
                'user_name'=>$user_name_1,
                'traveler' =>$traveler,
                'total_amount_1'=>$total_amount_1,//tong tien 
                'payment_type_name'=>$payment_type_1 // lay hinh ra hinh thuc thanh toan cho dat tiec
                //'traveller'=>$traveller
                ));
    }
}
?>