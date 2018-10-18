<?php
class ConsolidatedInvoiceForm extends Form
{
	function ConsolidatedInvoiceForm()
    {
		Form::Form('ConsolidatedInvoiceForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_css('skins/default/bootstrap/css/bootstrap.min.css');
        $this->link_js('skins/default/bootstrap/js/bootstrap.js');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
    }
	function draw()
    {
        if(isset($_GET['id'])){
        require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/currency.php';
       
		
		//--------------------------------------lay exchange------------------------------------------------
		if(HOTEL_CURRENCY == 'VND'){
			$this->map['exchange_currency_id'] = 'USD';
		}else{  
			$this->map['exchange_currency_id'] = 'VND';	    
		}
		$this->map['exchange_rate'] = DB::fetch('select id,exchange from currency where id=\''.$this->map['exchange_currency_id'].'\'','exchange');
        
                
        
        $id = $_GET['id'];
        require_once 'packages/hotel/packages/reception/modules/CreateTravellerFolio/get_reservation_room.php';
          
          
        $sql = "SELECT 
        d.id as id, 
        b.first_name, 
        b.last_name,
        c.name,
        a.time_in,
        a.time_out
        FROM reservation_room a 
        LEFT JOIN reservation_traveller d ON d.reservation_room_id = a.id
        LEFT JOIN traveller b ON d.traveller_id=b.id
        LEFT JOIN room c ON a.room_id=c.id WHERE a.reservation_id=$id";
        $traveller = DB::fetch_all($sql);
        $this->map['traveller'] = $traveller;
        $max = 0;
        $reservation_id = 0;
        foreach($traveller as $key=> $value){
            if($value['time_out']>$max){
               $max =  $value['time_out'];
               $reservation_id = $value['id'];
            }
        }
        if($reservation_id!=0){
           $sql = "SELECT reservation_room.time_in, reservation_room.time_out FROM reservation_room WHERE id=$reservation_id"; 
        }
        else{
           $sql = "SELECT *
                    FROM (SELECT reservation_room.time_in, reservation_room.time_out FROM reservation_room WHERE reservation_room.reservation_id=$id) reservation_id
                    WHERE rownum <= 1
                    ORDER BY rownum";  
        }
        
        $room_longest = DB::fetch($sql);
        $this->map['time_in_longest'] = $room_longest['time_in'];
        $this->map['time_out_longest'] = $room_longest['time_out'];
        $sql = "SELECT * FROM reservation a INNER JOIN customer b ON a.customer_id=b.id WHERE a.id=$id";
        $customer_info = DB::fetch_all($sql);
        $this->map['customer_info'] = $customer_info;
        
        
        $this->map['total_amount'] = 0;
        $this->map['total_foc'] = 0;
        $this->map['total_deposit'] = 0; $this->map['total_advance_payment'] = 0;
        $this->map['total_group_deposit'] = 0; $this->map['total_group_advance_payment'] = 0;
        $this->map['total_payment'] = 0;
        
        
        $sql = "
        SELECT 
        reservation_room.id as id,
        reservation.deposit as group_deposit, reservation.advance_payment as group_advance_payment,
        reservation_room.change_room_from_rr,
        reservation_room.change_room_to_rr 
        FROM
            reservation 
        INNER JOIN reservation_room ON  reservation_room.reservation_id = reservation.id
        WHERE reservation.id = $id AND reservation_room.status!='CANCEL'
        ";      
        $reservation_room = DB::fetch_all($sql);
        $reservation_room_detail = array();
        foreach($reservation_room as $key=>$value){
           $reservation_room_detail += get_reservation_room_detail($key,array()); 
           if($value['group_deposit']>0)
            {
				$percent = 100;$status = 0;
				$amount =$value['group_deposit'];
				$items['DEPOSIT_GROUP_'.$id]['net_amount'] = System::display_number($amount);         
				$items['DEPOSIT_GROUP_'.$id]['id'] = $id;
				$items['DEPOSIT_GROUP_'.$id]['type'] = 'DEPOSIT_GROUP';
				$items['DEPOSIT_GROUP_'.$id]['service_rate'] = 0;
				$items['DEPOSIT_GROUP_'.$id]['tax_rate'] = 0;
				$items['DEPOSIT_GROUP_'.$id]['date'] = '';
				$items['DEPOSIT_GROUP_'.$id]['rr_id'] = $id;
				$items['DEPOSIT_GROUP_'.$id]['percent'] = $percent;
				$items['DEPOSIT_GROUP_'.$id]['status'] = $status;
				$items['DEPOSIT_GROUP_'.$id]['amount'] = $amount;
				$items['DEPOSIT_GROUP_'.$id]['description'] = Portal::language('deposit_for_group');
				//$reservation_rooms['DEPOSIT'][''] = 0;
				$reservation_room_detail += $items;
             }
             if($value['group_advance_payment']>0)
            {
				$percent = 100;$status = 0;
				$amount =$value['group_advance_payment'];
				$items['ADVANCE_PAYMENT_GROUP_'.$id]['net_amount'] = System::display_number($amount);         
				$items['ADVANCE_PAYMENT_GROUP_'.$id]['id'] = $id;
				$items['ADVANCE_PAYMENT_GROUP_'.$id]['type'] = 'ADVANCE_PAYMENT_GROUP';
				$items['ADVANCE_PAYMENT_GROUP_'.$id]['service_rate'] = 0;
				$items['ADVANCE_PAYMENT_GROUP_'.$id]['tax_rate'] = 0;
				$items['ADVANCE_PAYMENT_GROUP_'.$id]['date'] = '';
				$items['ADVANCE_PAYMENT_GROUP_'.$id]['rr_id'] = $id;
				$items['ADVANCE_PAYMENT_GROUP_'.$id]['percent'] = $percent;
				$items['ADVANCE_PAYMENT_GROUP_'.$id]['status'] = $status;
				$items['ADVANCE_PAYMENT_GROUP_'.$id]['amount'] = $amount;
				$items['ADVANCE_PAYMENT_GROUP_'.$id]['description'] = Portal::language('advance_payment_for_group');
				//$reservation_rooms['DEPOSIT'][''] = 0;
				$reservation_room_detail += $items;
             }   
        }
        
        //krsort($reservation_room_detail);
        
        foreach($reservation_room_detail as $key=>$value){
             if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='ADVANCE_PAYMENT' and $value['type']!='ADVANCE_PAYMENT_GROUP' and $value['type']!='DISCOUNT')
            {
                if($value['type']!='ROOM'){
                   $reservation_room_detail[$key]['product'] = $this->get_product($value['id'],$value['type']);
                }
                $reservation_room_detail[$key]['service_amount'] = $value['amount'] * $value['service_rate']/100;   
                $reservation_room_detail[$key]['tax_amount'] = $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                $reservation_room_detail[$key]['total_amount'] = $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
            }
            else if($value['type']=='DISCOUNT')
            {
                $reservation_room_detail[$key]['service_amount'] = $value['amount'] * $value['service_rate']/100;
                $reservation_room_detail[$key]['tax_amount'] = $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                $reservation_room_detail[$key]['total_amount'] = $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
            }
            else
            {
                $reservation_room_detail[$key]['total_amount'] = $value['amount'];
            }
            switch ($value['type'])
            {
                case 'DEPOSIT' : 
                                 
                                $this->map['total_deposit'] += $reservation_room_detail[$key]['total_amount']; 
                                break;
                case 'DEPOSIT_GROUP' : 
                            
                            $this->map['total_group_deposit'] += $reservation_room_detail[$key]['total_amount'];
                             break;
                case 'ADVANCE_PAYMENT' : 
                                 
                                $this->map['total_advance_payment'] += $reservation_room_detail[$key]['total_amount']; 
                                break;
                case 'ADVANCE_PAYMENT_GROUP' : 
                            
                            $this->map['total_group_advance_payment'] += $reservation_room_detail[$key]['total_amount'];
                             break;
                case 'DISCOUNT' : 
                     
                    $this->map['total_amount'] -= $reservation_room_detail[$key]['total_amount']; 
                    break;
                default :               
                   $this->map['total_amount'] += $reservation_room_detail[$key]['total_amount'];
                    break;                   
            }
        }
        
        //$this->map['total_payment'] += $reservation_room_detail['total_payment'];
        $this->getGroupItem($reservation_room_detail);
        $this->getPriceGroup($reservation_room_detail);      
        $this->map['folio'] = $reservation_room_detail;
        $this->parse_layout('consolidated_invoice',$this->map);
        }
    } 
    function get_product($invoice_id,$type){
        if($type=='MINIBAR' || $type=='LAUNDRY' || $type=='EQUIPMENT'){
            $housekeeping_invoice = DB::fetch_all('SELECT
    				housekeeping_invoice_detail.id,product.name_1 as name
                    ,housekeeping_invoice_detail.price
                    ,housekeeping_invoice_detail.quantity
                    ,0 as total_product
                    ,housekeeping_invoice.tax_rate
                    ,housekeeping_invoice.fee_rate
                    ,housekeeping_invoice.net_price
                    ,housekeeping_invoice.express_rate
                    ,housekeeping_invoice.discount
                    ,housekeeping_invoice_detail.promotion
                    ,housekeeping_invoice_detail.change_quantity
    			FROM 
                    housekeeping_invoice_detail
                    INNER JOIN housekeeping_invoice ON housekeeping_invoice_detail.invoice_id = housekeeping_invoice.id
                     left join product on housekeeping_invoice_detail.product_id = product.id
    			WHERE housekeeping_invoice_detail.invoice_id ='.$invoice_id);
                foreach($housekeeping_invoice as $key=>$value)
                {
                    $subtotal = 0;
                    if($value['net_price']==1)
                    {
                        $subtotal = ($value['quantity']-$value['promotion'])*$value['price'];
                        
                        $total_before_tax = $subtotal/((1+$value['fee_rate']/100)*(1+$value['tax_rate']/100));
                        $subtotal_after_tax = ($total_before_tax*(1-$value['discount']/100)*(1+$value['express_rate']/100))*((1+$value['fee_rate']/100)*(1+$value['tax_rate']/100)); 
                        $housekeeping_invoice[$key]['total_product'] = $subtotal_after_tax;
                    }
                    else{
                        $subtotal = ($value['quantity']-$value['promotion'])*$value['price'];
                        $subtotal_after_tax = $subtotal*(1-$value['discount']/100)*(1+$value['express_rate']/100);
                        $total_before_tax = $subtotal_after_tax*(1+$value['fee_rate']/100)*(1+$value['tax_rate']/100);
                        $housekeeping_invoice[$key]['total_product'] = $total_before_tax;
                    }
                    
                }
                return $housekeeping_invoice;
        }
        else if($type=='BAR'){
            
            $table = DB::fetch_all('SELECT
    				bar_reservation_product.id,product.name_1 as name
                    ,bar_reservation_product.price
                    ,bar_reservation_product.quantity
                    ,bar_reservation.full_rate
                    ,bar_reservation.full_charge
                    ,bar_reservation.tax_rate
                    ,bar_reservation.bar_fee_rate
                    ,bar_reservation.discount_percent
                    ,bar_reservation.discount
                    ,bar_reservation_product.discount_rate
                    ,bar_reservation_product.quantity_discount
                    ,bar_reservation_product.price
    			FROM bar_reservation_product
                     inner join bar_reservation on bar_reservation_product.bar_reservation_id = bar_reservation.id
                     left join product on bar_reservation_product.product_id = product.id
    			WHERE bar_reservation.id ='.$invoice_id.'
    		 ');
             foreach($table as $key=>$table_info){
                      $total_amount = 0;
                      $total_amount = ($table_info['quantity']-$table_info['quantity_discount'])*(100-$table_info['discount_rate'])*$table_info['price']/100; 
                      $total_amount = $total_amount*(100-$table_info['discount_percent'])/100 - $table_info['discount']; 
                      $param = 0;
                        if($table_info['full_rate']==1)
                        {
                            $param =100+$table_info['tax_rate'] + $table_info['bar_fee_rate'];
                            $total_payment = $total_amount;
                            $total_amount=$total_amount*(100)/$param;                       
                        }
                        else if($table_info['full_charge']==1){
                            $param =  (100+$table_info['bar_fee_rate'])/100;
                            $total_payment = $total_amount*(1+$table_info['tax_rate']/100);
                            $total_amount = $total_amount/$param;
                        }
                        else{
                            $total_service = $total_amount*$table_info['bar_fee_rate']/100;
                            $total_tax = ($total_amount+$total_service)*$table_info['tax_rate']/100;
                            $total_payment = $total_amount + $total_service + $total_tax;
                        }
                        $table[$key]['total_product'] = $total_payment;
             }
             return $table;
        }    
	    else if($type=='MASSAGE'){
	       $spa = DB::fetch_all('SELECT
    				massage_product_consumed.id,product.name_1 as name
                    ,massage_product_consumed.price
                    ,massage_product_consumed.quantity
                    ,massage_product_consumed.price*massage_product_consumed.quantity as total_product
                    ,massage_reservation_room.net_price
                    ,massage_reservation_room.discount
                    ,massage_reservation_room.tax
                    ,massage_reservation_room.service_rate
                    ,massage_reservation_room.net_price
    			FROM massage_product_consumed
                     inner join massage_reservation_room on massage_reservation_room.id = massage_product_consumed.reservation_room_id
                     left join product on massage_product_consumed.product_id = product.id
    			WHERE massage_reservation_room.id ='.$invoice_id.'
    		 ');
             
             foreach($spa as $key=>$value){
                $total_amount = $value['total_product'];
                if($value['net_price']==1){
                    if(DISCOUNT_BEFORE_TAX==1){
                        $total_before_tax = $total_amount/((1+$value['service_rate']/100)*(1+$value['tax']/100));
                        $discount_amount = $total_before_tax*($value['discount']/100);
                        $total_after_tax = ($total_before_tax-$discount_amount)*((1+$value['service_rate']/100)*(1+$value['tax']/100));
                        $spa[$key]['total_product'] = $total_after_tax;
                    }
                    else{
                        $discount_amount = $total_amount*($value['discount']/100);
                        $spa[$key]['total_product'] = ($total_amount-$discount_amount);
                    }
                }
                else{
                    if(DISCOUNT_BEFORE_TAX==1){
                        $discount_amount = $total_amount*($value['discount']/100);
                        $total_after_tax = ($total_amount-$discount_amount)*((1+$value['service_rate']/100)*(1+$value['tax']/100));
                        $spa[$key]['total_product'] = $total_after_tax;
                    }
                    else{
                        $total_after_tax = ($total_amount)*((1+$value['service_rate']/100)*(1+$value['tax']/100));
                        $discount_amount = $total_after_tax*($value['discount']/100);
                        $spa[$key]['total_product'] = $total_after_tax-$discount_amount;
                    }
                }              
             }
             return $spa;
	    }
        else if($type=='VE'){
            $vend = DB::fetch_all('SELECT
    				ve_reservation_product.id,product.name_1 as name
                    ,ve_reservation_product.price
                    ,ve_reservation_product.quantity
                    ,ve_reservation.full_rate
                    ,ve_reservation.full_charge
                    ,ve_reservation.tax_rate
                    ,ve_reservation.bar_fee_rate
                    ,ve_reservation.discount_percent
                    ,ve_reservation.discount
                    ,ve_reservation_product.discount_rate
                    ,ve_reservation_product.quantity_discount
                    ,ve_reservation_product.price
    			FROM ve_reservation_product
                     inner join ve_reservation on ve_reservation_product.bar_reservation_id = ve_reservation.id
                     left join product on ve_reservation_product.product_id = product.id
    			WHERE ve_reservation.id ='.$invoice_id.'
    		 ');
             foreach($vend as $key=>$vend_info){
                      $total_amount = 0;
                      $total_amount = ($vend_info['quantity']-$vend_info['quantity_discount'])*(100-$vend_info['discount_rate'])*$vend_info['price']/100; 
                      $total_amount = $total_amount*(100-$vend_info['discount_percent'])/100 - $vend_info['discount']; 
                      $param = 0;
                        if($vend_info['full_rate']==1)
                        {
                            $param =100+$vend_info['tax_rate'] + $vend_info['bar_fee_rate'];
                            $total_payment = $total_amount;
                            $total_amount=$total_amount*(100)/$param;                       
                        }
                        else if($vend_info['full_charge']==1){
                            $param =  (100+$vend_info['bar_fee_rate'])/100;
                            $total_payment = $total_amount*(1+$vend_info['tax_rate']/100);
                            $total_amount = $total_amount/$param;
                        }
                        else{
                            $total_service = $total_amount*$vend_info['bar_fee_rate']/100;
                            $total_tax = ($total_amount+$total_service)*$vend_info['tax_rate']/100;
                            $total_payment = $total_amount + $total_service + $total_tax;
                        }
                        $vend[$key]['total_product'] = $total_payment;
             }
             return $vend;
        }
	}
    
    function getGroupItem(&$arr){
            reset($arr);
            $j = 1;
              $key = array_keys($arr);
              $total = 0;              
              for($i=0 ; $i< count($arr); $i++){
                  $current = current($arr);
                  $next = next($arr);
                  //echo $current[$field]."/".$next[$field];
                  if($current['type']=='ROOM'){
                      if($current['description'] == $next['description']){
                         $total += $next['total_amount'];                        
                          $j++;
                      }
                      else{
                          $arr[$key[$i-$j+1]]['count'] = $j;
                          $total += $arr[$key[$i-$j+1]]['total_amount'];
                          $arr[$key[$i-$j+1]]['total'] = $total;
                          $total = 0;                          
                          $j = 1;
                      }
                  }
              }
        }
    function getPriceGroup(&$arr){
        reset($arr);
            $j = 1;
            $key = array_keys($arr);
             $total = 0;
              for($i=0 ; $i< count($arr); $i++){
                    $current = current($arr);
                  $next = next($arr);
                  if($current['type'] == $next['type']){
                    $total += $next['total_amount'];
                      $j++;
                  }
                  else{
                      $total += $arr[$key[$i-$j+1]]['total_amount'];
                      $arr[$key[$i]]['total_amount_print'] = $total;
                      $total = 0;
                      $j = 1;
                  }
              }
    }
}
?>    