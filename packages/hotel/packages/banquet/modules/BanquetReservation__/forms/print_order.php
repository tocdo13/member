<?php
class PrintOrder extends Form
{
	function PrintOrder()
	{
	   Form::Form("PrintOrder");
	   $this->add('id',new IDType(true,'object_not_exists','party_reservation'));
    }
      function on_submit(){
        if(isset($_REQUEST['bill_code'])){
            DB::update('party_reservation',array('bill_code'),'id='.Url::get('id'));
        }   
    }
   	function draw()
	{ 
	   require_once 'packages/core/includes/utils/currency.php';
		if(Url::get('id') and $row = DB::fetch('Select party_reservation.*, party_type.name as party_type_name from party_reservation inner join party_type on party_reservation.party_type = party_type.id where party_reservation.id =  '.Url::get('id')))
		{
		  $total_amount=0;  
    	     $total_product_price=0;
             $total_room_price=0;
             $id=Url::get('id');
		  $items = DB::fetch_all('SELECT party_reservation_detail.*, 
                                party_reservation.user_id, 
                                product.name_'.Portal::language().' as product_name,
                                unit.name_'.Portal::language().' as unit_name
						FROM party_reservation_detail inner join  party_reservation on  party_reservation_detail. party_reservation_id =  party_reservation.id
                        inner join product on party_reservation_detail.product_id = product.id 	
                        inner join unit on unit.id=product.unit_id
		 				WHERE party_reservation_detail.party_reservation_id = '.Url::get('id').'and product_id is not null');
    	   //System::debug($items);
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
    	     //$total_product=0;  
    	     //$total_amount=0;
                  
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
        $total_amount = $total_product_price+$total_room_price;
       if($vat=DB::fetch_all('SELECT party_reservation.id,party_reservation.vat  
						FROM party_reservation 	
		 				WHERE party_reservation.id = '.Url::get('id').''));
        {
           $total_vat=0;
           foreach($vat as $i=>$n)
           {
                $total_vat= ($total_amount*$n['vat'])/100;
           }
           
        }
        $total = $total_amount+$total_vat;
        $row['sum_total_in_word'] = currency_to_text($row['total']);
        
        $payment_list = DB::fetch_all("
                                        SELECT
                                            payment.id
                                            ,payment.amount
                                            ,payment_type.name_".Portal::language()." as payment_type_name
                                        FROM
                                            payment
                                            inner join payment_type on payment_type.def_code=payment.payment_type_id
                                        WHERE
                                            payment.type='BANQUET' AND payment.bill_id='".Url::get('id')."'
                                        ");
        $this->parse_layout('print_order',$row+array('id'=>$id,
				'items'=>$items,
                'total_product_price'=>$total_product_price,
                'total_room_price'=>$total_room_price,
                'total_amount'=>$total_amount,
                'rooms'=>$rooms,
                'total_vat'=>$total_vat,
                'guest_name'=>$row['full_name'],
                'address'=>$row['address'],
                'contract_code'=>$row['contract_code'],
                'payment_list'=>$payment_list,
                'total'=>$total));
    }
}
?>