<?php
class GeneralStatementForm extends Form {
	function GeneralStatementForm(){
		Form::Form('GeneralStatementForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw(){
	   
       $this->map = array();
       
       $recode = DB::fetch('
                            SELECT
                                reservation.id
                                ,reservation.booker
                                ,reservation.phone_booker
                                ,reservation.booking_code
                                ,reservation.note
                                ,customer.name as customer_name
                                ,customer.def_name as customer_full_name
                                ,0 as room_charge
                                ,0 as extra_service_room
                                ,0 as breakfast
                                ,0 as tranfer
                                ,0 as service_other
                                ,0 as minibar
                                ,0 as laundry
                                ,0 as equipment
                                ,0 as bar
                                ,0 as banquet
                                ,0 as service_amount
                                ,0 as tax_amount
                                ,0 as amount
                                ,0 as total_amount
                            FROM
                                reservation
                                inner join customer on customer.id=reservation.customer_id
                            WHERE
                                reservation.id='.Url::get('id').'
                            ');
       $reservation_room = DB::fetch_all('
                                        SELECT
                                            reservation_room.id
                                            ,reservation_room.time_in
                                            ,reservation_room.time_out
                                            ,reservation_room.net_price
                                            ,reservation_room.service_rate
                                            ,reservation_room.tax_rate
                                            ,reservation_room.reduce_balance
                                            ,CASE
                                                WHEN reservation_room.arrival_time=reservation_room.departure_time
                                                THEN 1
                                                ELSE reservation_room.departure_time - reservation_room.arrival_time
                                            END night
                                            ,room.name as room_name
                                            ,room_level.name as room_level_name
                                            ,0 as room_charge
                                            ,0 as extra_service_room
                                            ,0 as breakfast
                                            ,0 as tranfer
                                            ,0 as service_other
                                            ,0 as minibar
                                            ,0 as laundry
                                            ,0 as equipment
                                            ,0 as bar
                                            ,0 as banquet
                                            ,0 as service_amount
                                            ,0 as tax_amount
                                            ,0 as amount
                                            ,0 as total_amount
                                            ,1 as count_child
                                        FROM
                                            reservation_room
                                            inner join room on reservation_room.room_id=room.id
                                            inner join room_level on room_level.id=reservation_room.room_level_id
                                        WHERE
                                            reservation_room.reservation_id='.Url::get('id').'
                                            AND reservation_room.status!=\'CANCEL\'
                                        ');
       $count_recode = sizeof($reservation_room);
       
       $reservation_traveller = DB::fetch_all('
                                            SELECT
                                                reservation_traveller.id
                                                ,reservation_traveller.reservation_room_id
                                                ,reservation_traveller.arrival_time as time_in
                                                ,reservation_traveller.departure_time as time_out
                                                ,traveller.first_name || \' \' || traveller.last_name as traveller_name
                                                ,traveller.passport
                                                ,traveller.address
                                                ,traveller.competence
                                                ,CASE
                                                    WHEN reservation_traveller.arrival_date=reservation_traveller.departure_date
                                                    THEN 1
                                                    ELSE reservation_traveller.departure_date - reservation_traveller.arrival_date
                                                END night
                                            FROM
                                                reservation_traveller
                                                inner join traveller on traveller.id=reservation_traveller.traveller_id
                                                inner join reservation_room on reservation_room.id=reservation_traveller.reservation_room_id
                                                inner join reservation on reservation.id=reservation_room.reservation_id
                                            WHERE
                                                reservation.id='.Url::get('id').'
                                                AND reservation_room.status!=\'CANCEL\'
                                            ');
                                            
       $reservation_room_arr = array();
       foreach($reservation_traveller as $key=>$value) {
            if(isset($reservation_room[$value['reservation_room_id']])){
                
                $reservation_room[$value['reservation_room_id']]['child'][$value['id']] = $value;
                $reservation_room[$value['reservation_room_id']]['count_child'] = sizeof($reservation_room[$value['reservation_room_id']]['child']);
                
                
                if($reservation_room[$value['reservation_room_id']]['count_child']!=1)
                    $count_recode++;
            }
       }
       
       /*** tien phong **/
       $sql = '
        		SELECT 
        			room_status.id
        			,room_status.change_price
        			,room_status.in_date
        			,reservation_room.tax_rate
        			,reservation_room.service_rate
        			,reservation_room.id as reservation_room_id
        		FROM 
        			room_status
        			INNER JOIN reservation_room on reservation_room.id = room_status.reservation_room_id
                    INNER JOIN reservation on reservation_room.reservation_id=reservation.id
        		WHERE 
        			reservation.id='.Url::get('id').' AND room_status.change_price > 0
                    AND reservation_room.status!=\'CANCEL\'
                ';
		$room_statuses = DB::fetch_all($sql);
        //System::debug($room_statuses);
        $count_package_room = array();
        $packages = DB::fetch_all("SELECT 
                                        reservation_room.id || '_' || package_sale_detail.id as id
                                        ,package_sale_detail.quantity
                                        ,reservation_room.id as reservation_room_id
                                    FROM 
                                        package_sale_detail 
                                        inner join package_sale on package_sale_detail.package_sale_id=package_sale.id 
                                        inner join package_service on package_sale_detail.service_id=package_service.id 
                                        inner join reservation_room on reservation_room.package_sale_id=package_sale.id
                                        inner join reservation on reservation.id=reservation_room.reservation_id
                                    WHERE 
                                        reservation.id=".Url::get('id')." 
                                        AND package_service.code='ROOM'
                                        AND reservation_room.status!='CANCEL'
                                    ");
        foreach($packages as $key=>$value) 
        {
            if(isset($count_package_room[$value['reservation_room_id']])){
                $count_package_room[$value['reservation_room_id']]['quantity'] = 0;
                $count_package_room[$value['reservation_room_id']]['quantity_use'] = 1;
            }else{
                $count_package_room[$value['reservation_room_id']] += $value['quantity'];
            }
        }
		foreach($room_statuses as $key=>$value)
        {
            if(isset($reservation_room[$value['reservation_room_id']])) 
            {
                if(!isset($count_package_room[$value['reservation_room_id']]) or $count_package_room[$value['reservation_room_id']]['quantity_use']>$count_package_room[$value['reservation_room_id']]['quantity']) {
                    if($reservation_room[$value['reservation_room_id']]['net_price']==1) {
    					$value['change_price'] = ($value['change_price']/( (1+$reservation_room[$value['reservation_room_id']]['service_rate']/100)*(1+$reservation_room[$value['reservation_room_id']]['tax_rate']/100) ));	
    				}
    				$amount = ($reservation_room[$value['reservation_room_id']]['reduce_balance']?(100 - $reservation_room[$value['reservation_room_id']]['reduce_balance'])*$value['change_price']/100:$value['change_price']);
    				$total_amount = round($amount*( (1+$reservation_room[$value['reservation_room_id']]['service_rate']/100)*(1+$reservation_room[$value['reservation_room_id']]['tax_rate']/100) ),0);
                    /*$tax_amount = round($total_amount/( (100/100+$reservation_room[$value['reservation_room_id']]['tax_rate']) ),2);
                    $amount = round($amount,2);
                    $service_amount = round($total_amount - $amount - $tax_amount,2);*/
                    /** Daund điều chỉnh lại công thức phải tính phí trước thuế sau chứ không phải là thuế trước phí sau.*/
                    $amount = round($amount,2);
                    //System::debug(($reservation_room[$value['reservation_room_id']]['service_rate']/100));
                    $service_amount = round($amount*($reservation_room[$value['reservation_room_id']]['service_rate']/100),2);
                    $tax_amount = round(($total_amount - $amount - $service_amount),2);
                    /** END*/
                    $reservation_room[$value['reservation_room_id']]['room_charge'] += $amount;
                    $reservation_room[$value['reservation_room_id']]['amount'] += $amount;
                    $reservation_room[$value['reservation_room_id']]['service_amount'] += $service_amount;
                    $reservation_room[$value['reservation_room_id']]['tax_amount'] += $tax_amount;
                    $reservation_room[$value['reservation_room_id']]['total_amount'] += $total_amount;
                    $recode['room_charge'] += $amount;
                    $recode['amount'] += $amount;
                    $recode['service_amount'] += $service_amount;
                    $recode['tax_amount'] += $tax_amount;
                    $recode['total_amount'] += $total_amount;
                }
                if(isset($count_package_room[$value['reservation_room_id']]))
                    $count_package_room[$value['reservation_room_id']]['quantity_use']++;
            }
		}
        /** DVMR **/
        $extra_services = DB::fetch_all('
                        			SELECT 
                        				extra_service_invoice_detail.*,
                                        NVL(extra_service_invoice_detail.package_sale_detail_id,0) as package_sale_detail_id,
                        				((extra_service_invoice_detail.quantity+NVL(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) as amount,
                        				0 as service_charge_amount,
                        				0 as tax_amount,
                                        extra_service_invoice.net_price,
                        				NVL(extra_service_invoice.tax_rate,0) as tax_rate,
                        				NVL(extra_service_invoice.service_rate,0) as service_rate,
                        				TO_CHAR(extra_service_invoice_detail.in_date,\'DD/MM/YYYY\') as in_date,
                                        extra_service_invoice.id as ex_id,
                                        extra_service_invoice.bill_number as ex_bill,
                                        reservation_room.id as reservation_room_id,
                                        extra_service.code as extra_service_code,
                                        extra_service.type as extra_service_type
                        			FROM 
                        				extra_service_invoice_detail
                                        inner join extra_service on extra_service.id=extra_service_invoice_detail.service_id
                        				inner join extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
                                        inner join reservation_room on reservation_room.id=extra_service_invoice.reservation_room_id
                                        inner join reservation on reservation.id=reservation_id
                        			WHERE 
                        				reservation.id='.Url::get('id').'
                        				AND extra_service_invoice_detail.used = 1
                                        AND reservation_room.status!=\'CANCEL\'
                                        ');
        //System::debug($extra_services);
        foreach($extra_services as $key=>$value)
        {
            if(isset($reservation_room[$value['reservation_room_id']]))
            {
                if($value['net_price'] != 1)
                    $value['amount'] = $value['amount'] * ( (1+$value['service_rate']/100)*(1+$value['tax_rate']/100) );
                
                $total_amount = round($value['amount'],0);
                
                if($value['package_sale_detail_id']==0)
                {
                    $amount = round($total_amount / ( (1+$value['service_rate']/100)*(1+$value['tax_rate']/100) ),2);
                    /*$tax_amount = round($total_amount/( (100/100+$value['tax_rate']) ),2);
                    $service_amount = round($total_amount - $amount - $tax_amount,2);*/
                    /** Daund điều chỉnh lại công thức phải tính phí trước thuế sau chứ không phải là thuế trước phí sau.*/
                    $service_amount = round($amount*($value['service_rate']/100),2);
                    $tax_amount = round($total_amount - $amount - $service_amount,2);
                    /** END */
                }
                else
                {
                    $package_amount = DB::fetch("SELECT (quantity*price) as amount from package_sale_detail Where id=".$s_value['package_sale_detail_id']."","amount");
                    if($total_amount>$package_amount)
                    {
                        $total_amount = $total_amount - $package_amount;
                        $amount = round($total_amount / ( (1+$value['service_rate']/100)*(1+$value['tax_rate']/100) ),2);
                        /*$tax_amount = round($total_amount/( (100/100+$value['tax_rate']) ),2);
                        $service_amount = round($total_amount - $amount - $tax_amount,2);*/
                        /** Daund điều chỉnh lại công thức phải tính phí trước thuế sau chứ không phải là thuế trước phí sau.*/
                        $service_amount = round($amount*($value['service_rate']/100),2);
                        $tax_amount = round($total_amount - $amount - $service_amount,2);
                        /** END */
                    }
                }
                if($value['extra_service_code']=='LATE_CHECKIN'){
                    $reservation_room[$value['reservation_room_id']]['room_charge'] += $amount;
                    $reservation_room[$value['reservation_room_id']]['amount'] += $amount;
                    $reservation_room[$value['reservation_room_id']]['service_amount'] += $service_amount;
                    $reservation_room[$value['reservation_room_id']]['tax_amount'] += $tax_amount;
                    $reservation_room[$value['reservation_room_id']]['total_amount'] += $total_amount;
                    $recode['room_charge'] += $amount;
                    $recode['amount'] += $amount;
                    $recode['service_amount'] += $service_amount;
                    $recode['tax_amount'] += $tax_amount;
                    $recode['total_amount'] += $total_amount;
                }elseif($value['extra_service_type']=='ROOM' and substr($value['extra_service_code'],0,7)!='TRANFER' and $value['extra_service_code']!='LATE_CHECKIN' and $value['extra_service_code']!='PTAS' and $value['extra_service_code']!='KID BF'){
                    $reservation_room[$value['reservation_room_id']]['extra_service_room'] += $amount;
                    $reservation_room[$value['reservation_room_id']]['amount'] += $amount;
                    $reservation_room[$value['reservation_room_id']]['service_amount'] += $service_amount;
                    $reservation_room[$value['reservation_room_id']]['tax_amount'] += $tax_amount;
                    $reservation_room[$value['reservation_room_id']]['total_amount'] += $total_amount;
                    $recode['extra_service_room'] += $amount;
                    $recode['amount'] += $amount;
                    $recode['service_amount'] += $service_amount;
                    $recode['tax_amount'] += $tax_amount;
                    $recode['total_amount'] += $total_amount;
                }elseif($value['extra_service_code']=='PTAS' or $value['extra_service_code']=='KID BF'){
                    $reservation_room[$value['reservation_room_id']]['breakfast'] += $amount;
                    $reservation_room[$value['reservation_room_id']]['amount'] += $amount;
                    $reservation_room[$value['reservation_room_id']]['service_amount'] += $service_amount;
                    $reservation_room[$value['reservation_room_id']]['tax_amount'] += $tax_amount;
                    $reservation_room[$value['reservation_room_id']]['total_amount'] += $total_amount;
                    $recode['breakfast'] += $amount;
                    $recode['amount'] += $amount;
                    $recode['service_amount'] += $service_amount;
                    $recode['tax_amount'] += $tax_amount;
                    $recode['total_amount'] += $total_amount;
                }elseif(substr($value['extra_service_code'],0,7)=='TRANFER'){
                    $reservation_room[$value['reservation_room_id']]['tranfer'] += $amount;
                    $reservation_room[$value['reservation_room_id']]['amount'] += $amount;
                    $reservation_room[$value['reservation_room_id']]['service_amount'] += $service_amount;
                    $reservation_room[$value['reservation_room_id']]['tax_amount'] += $tax_amount;
                    $reservation_room[$value['reservation_room_id']]['total_amount'] += $total_amount;
                    $recode['tranfer'] += $amount;
                    $recode['amount'] += $amount;
                    $recode['service_amount'] += $service_amount;
                    $recode['tax_amount'] += $tax_amount;
                    $recode['total_amount'] += $total_amount;
                }else{
                    $reservation_room[$value['reservation_room_id']]['service_other'] += $amount;
                    $reservation_room[$value['reservation_room_id']]['amount'] += $amount;
                    $reservation_room[$value['reservation_room_id']]['service_amount'] += $service_amount;
                    $reservation_room[$value['reservation_room_id']]['tax_amount'] += $tax_amount;
                    $reservation_room[$value['reservation_room_id']]['total_amount'] += $total_amount;
                    $recode['service_other'] += $amount;
                    $recode['amount'] += $amount;
                    $recode['service_amount'] += $service_amount;
                    $recode['tax_amount'] += $tax_amount;
                    $recode['total_amount'] += $total_amount;
                }
            }
        }
        
        /** MINIBAR,LAUNDRY,EQUIPMENT **/
        $housekeeping = DB::fetch_all('
                                SELECT 
        							housekeeping_invoice.*,
                                    housekeeping_invoice.fee_rate as service_rate
        						FROM 
        							housekeeping_invoice
                                    inner join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id
                                    inner join reservation on reservation.id=reservation_room.reservation_id
        						WHERE 
        							reservation.id='.Url::get('id').'
        							AND (
                                        housekeeping_invoice.type=\'MINIBAR\' 
                                        or housekeeping_invoice.type=\'LAUNDRY\' 
                                        or housekeeping_invoice.type=\'EQUIP\'
                                        )
                                    AND reservation_room.status!=\'CANCEL\'
                                    ');
       //System::debug($housekeeping); 
       $sql_package = "
                        SELECT 
                            reservation_room.id,
                            (package_sale_detail.quantity*package_sale_detail.price) as amount 
                        FROM 
                            package_sale_detail 
                            inner join package_service on package_service.id=package_sale_detail.service_id
                            inner join package_sale on package_sale.id=package_sale_detail.package_sale_id
                            inner join reservation_room on reservation_room.package_sale_id=package_sale.id
                            inner join reservation on reservation.id=reservation_room.reservation_id
                        WHERE
                            reservation.id=".Url::get('id')."
                            AND reservation_room.status!='CANCEL'
                            ";
       $package_amount_minibar = DB::fetch_all($sql_package." AND package_service.code='MINIBAR'","amount");
       $package_amount_laundry = DB::fetch_all($sql_package." AND package_service.code='LAUNDRY'","amount");
       
       foreach($housekeeping as $key=>$value)
       {
            if(isset($reservation_room[$value['reservation_room_id']])) 
            {
                $check = true;
                $package_amount = ($value['type']=='MINIBAR' and isset($package_amount_minibar[$value['reservation_room_id']]))?$package_amount_minibar[$value['reservation_room_id']]:(($value['type']=='LAUNDRY' and isset($package_amount_laundry[$value['reservation_room_id']]))?$package_amount_laundry[$value['reservation_room_id']]:0);
                if($package_amount<=0)
                {
    				$percent = 100;$status = 0;
    				$total_amount = $value['total'];
                    $amount = round($total_amount / ( (1+$value['service_rate']/100)*(1+$value['tax_rate']/100) ),2);
                    /*$tax_amount = round($total_amount/( (100/100+$value['tax_rate']) ),2);
                    $service_amount = round($total_amount - $amount - $tax_amount,2);*/
                    /** Daund điều chỉnh lại công thức phải tính phí trước thuế sau chứ không phải là thuế trước phí sau.*/
                    $service_amount = round($amount*($value['service_rate']/100),2);
                    $tax_amount = round($total_amount - $amount - $service_amount,2);
                    /** END */
                }
                else
                {
                    if($package_amount>=$minibar['total']){
                        if($value['type']=='MINIBAR' and isset($package_amount_minibar[$value['reservation_room_id']]))
                            $package_amount_minibar[$value['reservation_room_id']] -= $minibar['total'];
                        elseif($value['type']=='LAUNDRY' and isset($package_amount_laundry[$value['reservation_room_id']]))
                            $package_amount_laundry[$value['reservation_room_id']] -= $minibar['total'];
                        $check = false;
                    }
                    else
                    {
                        $total_amount -= $package_amount_minibar;
                        $amount = round($total_amount / ( (1+$value['service_rate']/100)*(1+$value['tax_rate']/100) ),2);
                        /*$tax_amount = round($total_amount/( (100/100+$value['tax_rate']) ),2);
                        $service_amount = round($total_amount - $amount - $tax_amount,2);*/
                        /** Daund điều chỉnh lại công thức phải tính phí trước thuế sau chứ không phải là thuế trước phí sau.*/
                        $service_amount = round($amount*($value['service_rate']/100),2);
                        $tax_amount = round($total_amount - $amount - $service_amount,2);
                        /** END */
                        $package_amount_minibar[$value['reservation_room_id']] = 0;
                    }
                }
                if($check){
                    if($value['type']=='MINIBAR'){
                        $reservation_room[$value['reservation_room_id']]['minibar'] += $amount;
                        $recode['minibar'] += $amount;
                    }elseif($value['type']=='LAUNDRY'){
                        $reservation_room[$value['reservation_room_id']]['laundry'] += $amount;
                        $recode['laundry'] += $amount;
                    }else{
                        $reservation_room[$value['reservation_room_id']]['equipment'] += $amount;
                        $recode['equipment'] += $amount;
                    }
                    $reservation_room[$value['reservation_room_id']]['amount'] += $amount;
                    $reservation_room[$value['reservation_room_id']]['service_amount'] += $service_amount;
                    $reservation_room[$value['reservation_room_id']]['tax_amount'] += $tax_amount;
                    $reservation_room[$value['reservation_room_id']]['total_amount'] += $total_amount;
                    $recode['amount'] += $amount;
                    $recode['service_amount'] += $service_amount;
                    $recode['tax_amount'] += $tax_amount;
                    $recode['total_amount'] += $total_amount;
                }
            }
            
       }
       /** ticket **/
       $ticket = DB::fetch_all('
                                	SELECT 
                                		ticket_reservation.id,
                                        ticket_reservation.amount_pay_with_room as total,
                                        ticket_reservation.tax_rate,
                                        ticket_reservation.service_rate
                                	FROM 
                                		ticket_reservation
                                        inner join reservation_room on reservation_room.id=ticket_reservation.reservation_room_id
                                        inner join reservation on reservation.id=reservation_room.reservation_id
                                	WHERE 
                                		reservation.id='.Url::get('id').'
                                        AND reservation_room.status!=\'CANCEL\'
                                        ');
        foreach($ticket as $key=>$value)
        {
            $total_amount = $value['total'];
            $amount = round($total_amount / ( (1+$value['service_rate']/100)*(1+$value['tax_rate']/100) ),2);
            /*$tax_amount = round($total_amount/( (100/100+$value['tax_rate']) ),2);
            $service_amount = round($total_amount - $amount - $tax_amount,2);*/
            /** Daund điều chỉnh lại công thức phải tính phí trước thuế sau chứ không phải là thuế trước phí sau.*/
            $service_amount = round($amount*($value['service_rate']/100),2);
            $tax_amount = round($total_amount - $amount - $service_amount,2);
            /** END */
            
            $reservation_room[$value['reservation_room_id']]['service_other'] += $amount;
            $reservation_room[$value['reservation_room_id']]['amount'] += $amount;
            $reservation_room[$value['reservation_room_id']]['service_amount'] += $service_amount;
            $reservation_room[$value['reservation_room_id']]['tax_amount'] += $tax_amount;
            $reservation_room[$value['reservation_room_id']]['total_amount'] += $total_amount;
            $recode['service_other'] += $amount;
            $recode['amount'] += $amount;
            $recode['service_amount'] += $service_amount;
            $recode['tax_amount'] += $tax_amount;
            $recode['total_amount'] += $total_amount;
        }
        
        /** PACKAGE **/
        $package = DB::fetch_all("
                        SELECT 
                            reservation_room.id,
                            (package_sale_detail.quantity*package_sale_detail.price) as amount,
                            package_service.code,
                            reservation_room.id as reservation_room_id
                        FROM 
                            package_sale_detail 
                            inner join package_service on package_service.id=package_sale_detail.service_id
                            inner join package_sale on package_sale.id=package_sale_detail.package_sale_id
                            inner join reservation_room on reservation_room.package_sale_id=package_sale.id
                            inner join reservation on reservation.id=reservation_room.reservation_id
                        WHERE
                            reservation.id=".Url::get('id')."
                            AND reservation_room.status!='CANCEL'
                            ");
        foreach($package as $key=>$value)
        {
            $total_amount = $value['amount'];
            $service_amount = 0;
            $tax_amount = 0;
            $amount = $value['amount'];
            if($value['code']=='MINIBAR'){
                $reservation_room[$value['reservation_room_id']]['minibar'] += $amount;
                $recode['minibar'] += $amount;
            }elseif($value['code']=='LAUNDRY'){
                $reservation_room[$value['reservation_room_id']]['laundry'] += $amount;
                $recode['laundry'] += $amount;
            }else{
                $reservation_room[$value['reservation_room_id']]['room_charge'] += $amount;
                $recode['room_charge'] += $amount;
            }
            $reservation_room[$value['reservation_room_id']]['amount'] += $amount;
            $reservation_room[$value['reservation_room_id']]['service_amount'] += $service_amount;
            $reservation_room[$value['reservation_room_id']]['tax_amount'] += $tax_amount;
            $reservation_room[$value['reservation_room_id']]['total_amount'] += $total_amount;
            $recode['amount'] += $amount;
            $recode['service_amount'] += $service_amount;
            $recode['tax_amount'] += $tax_amount;
            $recode['total_amount'] += $total_amount;
        }
		
        /** VENDING ***/
        $vending = DB::fetch_all('
                                SELECT
                                    ve_reservation.id,
                                    ve_reservation.amount_pay_with_room as total,
                                    ve_reservation.tax_rate,
                                    ve_reservation.bar_fee_rate as service_rate,
                                    ve_reservation.reservation_room_id
                                FROM
                                    ve_reservation
                                    inner join reservation_room on reservation_room.id=ve_reservation.reservation_room_id
                                    inner join reservation on reservation.id=reservation_room.reservation_id
                                WHERE
                                    reservation.id='.Url::get('id').'
                                    AND reservation_room.status!=\'CANCEL\'
                                ');
        foreach($vending as $key=>$value)
        {
            $total_amount = $value['total'];
            $amount = round($total_amount / ( (1+$value['service_rate']/100)*(1+$value['tax_rate']/100) ),2);
            /*$tax_amount = round($total_amount/( (100/100+$value['tax_rate']) ),2);
            $service_amount = round($total_amount - $amount - $tax_amount,2);*/
            /** Daund điều chỉnh lại công thức phải tính phí trước thuế sau chứ không phải là thuế trước phí sau.*/
            $service_amount = round($amount*($value['service_rate']/100),2);
            $tax_amount = round($total_amount - $amount - $service_amount,2);
            /** END */
            
            $reservation_room[$value['reservation_room_id']]['service_other'] += $amount;
            $reservation_room[$value['reservation_room_id']]['amount'] += $amount;
            $reservation_room[$value['reservation_room_id']]['service_amount'] += $service_amount;
            $reservation_room[$value['reservation_room_id']]['tax_amount'] += $tax_amount;
            $reservation_room[$value['reservation_room_id']]['total_amount'] += $total_amount;
            $recode['service_other'] += $amount;
            $recode['amount'] += $amount;
            $recode['service_amount'] += $service_amount;
            $recode['tax_amount'] += $tax_amount;
            $recode['total_amount'] += $total_amount;
        }
        
        /** BAR **/
        $restaurant = DB::fetch_all('
                                    SELECT 
            							bar_reservation.id,
            							bar_reservation.amount_pay_with_room, 
                                        bar_reservation.tax_rate,
                                        bar_reservation.bar_fee_rate as service_rate,
            							bar.code as bar_code,
                                        bar_reservation.reservation_room_id
            						FROM 
            							bar_reservation 
            							inner join bar on bar_reservation.bar_id = bar.id
                                        inner join reservation_room on reservation_room.id=bar_reservation.reservation_room_id
                                        inner join reservation on reservation.id=reservation_room.reservation_id
            						WHERE 
            							reservation.id='.Url::get('id').'
                                        AND reservation_room.status!=\'CANCEL\'
                                    ');
        foreach($restaurant as $key=>$value)
        {
            $total_amount = $value['amount_pay_with_room'];
            $amount = round($total_amount / ( (1+$value['service_rate']/100)*(1+$value['tax_rate']/100) ),2);
            /*$tax_amount = round($total_amount/( (100/100+$value['tax_rate']) ),2);
            $service_amount = round($total_amount - $amount - $tax_amount,2);*/
            /** Daund điều chỉnh lại công thức phải tính phí trước thuế sau chứ không phải là thuế trước phí sau.*/
            $service_amount = round($amount*($value['service_rate']/100),2);
            $tax_amount = round($total_amount - $amount - $service_amount,2);
            /** END */
            
            if($value['bar_code']=='MROOM'){
                $reservation_room[$value['reservation_room_id']]['banquet'] += $amount;
                $recode['banquet'] += $amount;
            }else{
                $reservation_room[$value['reservation_room_id']]['bar'] += $amount;
                $recode['bar'] += $amount;
            }
            
            $reservation_room[$value['reservation_room_id']]['amount'] += $amount;
            $reservation_room[$value['reservation_room_id']]['service_amount'] += $service_amount;
            $reservation_room[$value['reservation_room_id']]['tax_amount'] += $tax_amount;
            $reservation_room[$value['reservation_room_id']]['total_amount'] += $total_amount;
            $recode['amount'] += $amount;
            $recode['service_amount'] += $service_amount;
            $recode['tax_amount'] += $tax_amount;
            $recode['total_amount'] += $total_amount;
        }
        
        /** telephone **/
		$telephone = DB::fetch_all('
                					SELECT
                						telephone_report_daily.price_vnd AS total,
                						telephone_report_daily.id as id,
                						telephone_report_daily.dial_number,
                						telephone_report_daily.hdate,
                                        reservation_room.id as reservation_room_id
                					FROM
                						telephone_report_daily
                						inner join telephone_number on telephone_number.phone_number = telephone_report_daily.phone_number_id
                                        inner join reservation_room on telephone_number.room_id = reservation_room.room_id and telephone_report_daily.hdate >=reservation_room.time_in and telephone_report_daily.hdate <= reservation_room.time_out
                                        inner join reservation on reservation.id=reservation_room.reservation_id
                					WHERE
                						telephone_report_daily.hdate >=reservation_room.time_in and telephone_report_daily.hdate <= reservation_room.time_out
                						AND telephone_number.room_id = reservation_room.room_id
                                        AND reservation.id='.Url::get('id').'
                                        AND reservation_room.status!=\'CANCEL\'
                				');
        foreach($telephone as $key=>$value)
        {
            $tax_rate = (TELEPHONE_TAX_RATE)?TELEPHONE_TAX_RATE:0;
            $total_amount = $value['total'] * (1+$tax_rate/100);
            $amount = round($total_amount / (1+$tax_rate/100),2);
            /*$tax_amount = round($total_amount/( (100/100+$tax_rate) ),2);
            $service_amount = round($total_amount - $amount - $tax_amount,2);*/
            /** Daund điều chỉnh lại công thức phải tính phí trước thuế sau chứ không phải là thuế trước phí sau.*/
            $service_amount = round($amount*($value['service_rate']/100),2);
            $tax_amount = round($total_amount - $amount - $service_amount,2);
            /** END */
            
            $reservation_room[$value['reservation_room_id']]['service_other'] += $amount;
            $reservation_room[$value['reservation_room_id']]['amount'] += $amount;
            $reservation_room[$value['reservation_room_id']]['service_amount'] += $service_amount;
            $reservation_room[$value['reservation_room_id']]['tax_amount'] += $tax_amount;
            $reservation_room[$value['reservation_room_id']]['total_amount'] += $total_amount;
            $recode['service_other'] += $amount;
            $recode['amount'] += $amount;
            $recode['service_amount'] += $service_amount;
            $recode['tax_amount'] += $tax_amount;
            $recode['total_amount'] += $total_amount;
        }
        
        $this->map += $recode;
        $this->map['items'] = $reservation_room;
        $this->map['count_recode'] = $count_recode;
        
        //System::debug($this->map);
        
        $this->parse_layout('list',$this->map);
	}
}
?>