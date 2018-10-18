<?php
class api extends restful_api
{

	function __construct(){
		parent::__construct();
	}
    
    function general()
    {
        if ($this->method == 'GET')
        {
    		if(Url::get('secretkey') and Url::get('secretkey') == '9a8fa234b2520e9bb4f59d8178545a62')
            {
                $in_date = Url::get('in_date')?Url::get('in_date'):date('d/m/Y', time());
                $revenue_arr = $this->revenue($in_date, 'GET');
                //System::debug($revenue_arr);
                $payment_arr = $this->payment($in_date, 'GET');
                //System::debug($payment_arr);
                $arr = $revenue_arr+$payment_arr; 
                //System::debug($arr);
                $items = array();
                foreach($arr as $key => $value)
                {
                    array_push($items, $value);
                }
                //System::debug($items);
                
                $this->response(200, json_encode($items));
            }else
            {
                $this->response(500, "FAILED"); // AUTH
            }
        }else
        {
            $this->response(500, "FAILED"); // METHOD
        }
	}
    
    function revenue($in_date = false, $type = false)
    {
        if ($this->method == 'GET')
        {
    		if(Url::get('secretkey') and Url::get('secretkey') == '9a8fa234b2520e9bb4f59d8178545a62')
            {
                if(Url::get('in_date'))
                {
                    $in_date = Url::get('in_date');
                }
                $cond_r = 'room_status.in_date = \''.Date_Time::to_orc_date($in_date).'\' ';
                /** Lấy ra doanh thu phòng */
                $sql = "
                        SELECT 
                            room_status.id as id
                            ,room_status.in_date
                            ,room_status.change_price as price
                            ,reservation_room.net_price
                            ,reservation_room.tax_rate
                            ,reservation_room.service_rate
                            ,reservation_room.reduce_balance
                            ,reservation_room.reduce_amount
                            ,reservation_room.arrival_time
                            ,reservation_room.departure_time
                            ,reservation_room.status as reservation_room_status
                            ,NVL(reservation_room.change_room_from_rr,0) as change_from
                            ,NVL(reservation_room.change_room_to_rr,0) as change_to
                            ,reservation_room.foc
                            ,NVL(reservation_room.foc_all,0) as foc_all
                        FROM
                            room_status
                            inner join reservation_room on room_status.reservation_room_id=reservation_room.id
                            left join party on reservation_room.user_id=party.user_id
                            left join room_level on reservation_room.room_level_id=room_level.id
                        WHERE
                            ".$cond_r." AND reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW'
                            AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                            AND room_status.reservation_id != 0
                        ORDER BY
                            room_status.in_date,reservation_room.id
                        ";                        
                $revenue_r = DB::fetch_all($sql);
                //System::debug($revenue_r);
                $items = array(
                            'REVENUE' => array(
                                            'revenue_room' => 0,
                                            'revenue_minibar' => 0,
                                            'revenue_laundry' => 0,
                                            'revenue_bar' => 0,
                                            'revenue_banquet' => 0,
                                            'revenue_spa' => 0,
                                            'revenue_shop' => 0,
                                            'revenue_other' => 0,
                                            'revenue_total' => 0
                            )
                );
                $total = 0;
                foreach($revenue_r as $key => $value)
                {
                    /** tinh gia **/
                    if(($value['arrival_time']==$value['departure_time'] AND $value['change_to']!=0) OR ($value['arrival_time']!=$value['departure_time'] AND $value['in_date']==$value['departure_time']))
                    {
                        /** loai bo th doi phong dayuse **/
                        unset($revenue_r[$key]);
                    }
                    else
                    {
                        if($value['foc'] !='' OR $value['foc_all'] !=0)
                        {
                            $value['price'] = 0;
                        }
                        if($value['reservation_room_status']=='CANCEL')
                        {
                            $value['price'] = 0;
                        }
                        if($value['net_price']==1 AND $value['reduce_balance']==0 AND $value['reduce_amount']==0)
                        {
                            /** gia da co thue phi va khong co giam gia **/
                            $items['REVENUE']['revenue_room'] += $value['price']/(1+$value['tax_rate']/100);
                            $total += $value['price']/(1+$value['tax_rate']/100);
                        }
                        else
                        {
                            if($value['net_price']==1)
                            {
                                $value['price'] = $value['price']/((1+$value['tax_rate']/100)*(1+$value['service_rate']/100));
                            }
                            $value['price'] = $value['price'] - ($value['price']*$value['reduce_balance']/100);
                            if($value['in_date']==$value['arrival_time'])
                            {
                                $value['price'] = $value['price'] - $value['reduce_amount'];
                            }
                            $items['REVENUE']['revenue_room'] += $value['price']*(1+$value['service_rate']/100);
                            $total += $value['price']*(1+$value['service_rate']/100);
                        }
                    }
                }
                //System::debug($items);
                /** Lấy doanh thu dịch vụ */
                $cond_e = 'extra_service_invoice_detail.in_date = \''.Date_Time::to_orc_date($in_date).'\' ';
                $sql = "
                        SELECT
                            concat(concat(extra_service.name,'_'),extra_service_invoice_detail.id) as id
                            ,extra_service.code as extra_service_code
                            ,extra_service_invoice_detail.price
                            ,extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0) as quantity
                            ,extra_service_invoice_detail.in_date
                            ,NVL(reservation_room.foc_all,0) as foc_all
                            ,extra_service_invoice.net_price
                            ,extra_service_invoice.tax_rate
                            ,extra_service_invoice.service_rate
                        FROM
                            extra_service_invoice_detail
                            inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id
                            inner join extra_service on extra_service.id = extra_service_invoice_detail.service_id
                            left join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                            left join room_level on reservation_room.room_level_id=room_level.id
                        WHERE
                            ".$cond_e."
                            AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                            AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                        ORDER BY
                            extra_service.name, extra_service_invoice_detail.id
                        ";
                $revenue_e = DB::fetch_all($sql);
                //System::debug($revenue_e);
                foreach($revenue_e as $key => $value)
                {
                    if($value['net_price']==1)
                    {
                        $value['price'] = $value['price']/((1+$value['tax_rate']/100)*(1+$value['service_rate']/100));
                    }
                    if($value['foc_all'] !=0)
                    {
                        $value['price'] = 0;
                    }
                    if($value['extra_service_code'] == 'EARLY_CHECKIN' || $value['extra_service_code'] == 'LATE_CHECKOUT' || $value['extra_service_code'] == 'LATE_CHECKIN' || $value['extra_service_code'] == 'EXTRA_BED' || $value['extra_service_code'] == 'BABY_COT' || $value['extra_service_code'] == 'ROOM')
                    {
                        $items['REVENUE']['revenue_room'] += $value['price']*$value['quantity']*(1+$value['service_rate']/100);
                    }else
                    {
                        $items['REVENUE']['revenue_other'] += $value['price']*$value['quantity']*(1+$value['service_rate']/100);
                    }
                    $total += $value['price']*$value['quantity']*(1+$value['service_rate']/100);
                }
                /** Doanh thu Minibar & laundry */
                $cond_hk = 'housekeeping_invoice.time >= \''.Date_Time::to_time($in_date).'\' AND housekeeping_invoice.time <= \''.(Date_Time::to_time($in_date)+86399).'\' ';
                $sql = "
                        SELECT
                            housekeeping_invoice.id
                            ,housekeeping_invoice.id as housekeeping_invoice_id
                            ,housekeeping_invoice.position
                            ,housekeeping_invoice.time as in_date
                            ,housekeeping_invoice.total as price
                            ,housekeeping_invoice.tax_rate
                            ,housekeeping_invoice.type
                            ,NVL(reservation_room.foc_all,0) as foc_all
                            ,reservation_room.status as reservation_room_status
                        FROM
                            housekeeping_invoice
                            inner join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id
                            inner join room_level on reservation_room.room_level_id=room_level.id
                        WHERE
                            ".$cond_hk."
                            AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                            AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                        ORDER BY
                            housekeeping_invoice.time, reservation_room.id
                        ";
                $revenue_hk = DB::fetch_all($sql);
                foreach($revenue_hk as $key => $value)
                {
                    if($value['foc_all'] !=0 || $value['reservation_room_status']=='CANCEL')
                    {
                        $value['price'] = 0;
                    }
                    if($value['type'] =='MINIBAR')
                    {
                        $items['REVENUE']['revenue_minibar'] += $value['price']/(1+$value['tax_rate']/100);
                    }else if($value['type'] =='LAUNDRY')
                    {
                        $items['REVENUE']['revenue_laundry'] += $value['price']/(1+$value['tax_rate']/100);
                    }else
                    {
                        $items['REVENUE']['revenue_other'] += $value['price']/(1+$value['tax_rate']/100);
                    }
                    $total += $value['price']/(1+$value['tax_rate']/100);
                }
                
                /** Doanh thu nhà hàng */
                $cond_b = 'bar_reservation.time_out >= \''.Date_Time::to_time($in_date).'\' AND bar_reservation.time_out <= \''.(Date_Time::to_time($in_date)+86399).'\' ';
                $sql = "
                    SELECT
                        concat('BAR_',bar_reservation.id) as id
                        ,bar_reservation.code
                        ,bar_reservation.arrival_time as in_date
                        ,bar_reservation.total as price
                        ,bar_reservation.tax_rate
                        ,bar_reservation.id as bar_reservation_id
                        ,bar_reservation.bar_id as bar_id
                        ,NVL(reservation_room.foc_all,0) as foc_all
                    FROM
                        bar_reservation
                        inner join bar on bar_reservation.bar_id = bar.id
                        left join reservation_room on reservation_room.id = bar_reservation.reservation_room_id
                        left join room_level on reservation_room.room_level_id=room_level.id
                    WHERE
                        ".$cond_b."
                        AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                        AND (room_level.is_virtual =0 OR room_level.is_virtual is null)
                        AND bar_reservation.status = 'CHECKOUT'
                    ORDER BY
                        bar_reservation.time, reservation_room.id
                ";
                $revenue_b = DB::fetch_all($sql);
                //System::debug($revenue_b);
                foreach($revenue_b as $key => $value)
                {
                    if($value['foc_all']!=0)
                    {
                        $value['price'] = 0;
                    }
                    $items['REVENUE']['revenue_bar'] += $value['price']/(1+$value['tax_rate']/100);
                    $total += $value['price']/(1+$value['tax_rate']/100);
                }
                //System::debug($items);
                /** Doanh thu tiệc */
                $cond_p = 'party_reservation.checkout_time >= \''.Date_Time::to_time($in_date).'\' AND party_reservation.checkout_time <= \''.(Date_Time::to_time($in_date)+86399).'\' ';
                $sql = "
                    SELECT
                        concat('PARTY_',party_reservation.id) as id
                        ,party_reservation.checkin_time as in_date
                        ,party_reservation.total as price
                        ,party_reservation.vat as tax_rate
                    FROM
                        party_reservation                
                    WHERE
                        ".$cond_p."
                        AND party_reservation.status ='CHECKOUT'
                    ORDER BY
                        party_reservation.time, party_reservation.id
                ";
                $revenue_p = DB::fetch_all($sql);
                //System::debug($revenue_p);
                foreach($revenue_p as $key=>$value)
                {
                    $items['REVENUE']['revenue_banquet'] += $value['price']/(1+$value['tax_rate']/100);
                    $total += $value['price']/(1+$value['tax_rate']/100);
                }
                //System::debug($items);
                /** Doanh thu spa */
                $cond_s = 'massage_reservation_room.time >= '.Date_Time::to_time($in_date).' AND massage_reservation_room.time <= '.(Date_Time::to_time($in_date)+86399).'';
                $sql = "
                        SELECT
                            concat('SPA_',massage_reservation_room.id) as id
                            ,massage_reservation_room.time as in_date
                            ,massage_reservation_room.total_amount as price
                            ,massage_reservation_room.tax as tax_rate
                            ,NVL(reservation_room.foc_all,0) as foc_all
                        FROM
                            massage_reservation_room
                            left join reservation_room on reservation_room.id=massage_reservation_room.hotel_reservation_room_id
                            left join room_level on reservation_room.room_level_id=room_level.id
                        WHERE
                            ".$cond_s."
                            AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                            AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                        ORDER BY
                            massage_reservation_room.time, massage_reservation_room.id
                        ";
                $revenue_s = DB::fetch_all($sql);
                //System::debug($cond_s);
                foreach($revenue_s as $key=>$value)
                {
                    if($value['foc_all'] !=0)
                    {
                        $value['price'] = 0;
                    }
                    $items['REVENUE']['revenue_spa'] += $value['price']/(1+$value['tax_rate']/100);
                    $total += $value['price']/(1+$value['tax_rate']/100);
                }
                
                /** Doanh thu shop (bán hàng)*/
                $cond_ve = 've_reservation.time >= \''.Date_Time::to_time($in_date).'\' AND ve_reservation.time <= \''.(Date_Time::to_time($in_date)+86399).'\' ';
                $sql = "
                        SELECT
                            concat('VEND_',ve_reservation.id) as id
                            ,NVL(reservation_room.foc_all,0) as foc_all
                            ,ve_reservation.time as in_date
                            ,ve_reservation.total as price
                            ,ve_reservation.tax_rate
                        FROM
                            ve_reservation
                            LEFT JOIN reservation_room ON ve_reservation.reservation_room_id = reservation_room.id
                            LEFT JOIN room_level ON reservation_room.room_level_id = room_level.id
                        WHERE
                            ".$cond_ve."
                            AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                            AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                        ORDER BY
                            ve_reservation.time, ve_reservation.id
                        ";
                $revenue_ve = DB::fetch_all($sql);
                
                foreach($revenue_ve as $key=>$value)
                {
                    if($value['foc_all'] !=0)
                    {
                        $value['price'] = 0;
                    }
                    $items['REVENUE']['revenue_shop'] += $value['price']/(1+$value['tax_rate']/100);
                    $total += $value['price']/(1+$value['tax_rate']/100);
                }
                $arr = array();
                foreach($items as $key => $value)
                {
                    $items['REVENUE']['revenue_room'] = $value['revenue_room'] = System::display_number(round($value['revenue_room']));
                    $items['REVENUE']['revenue_minibar'] = $value['revenue_minibar'] = System::display_number(round($value['revenue_minibar']));
                    $items['REVENUE']['revenue_laundry'] = $value['revenue_laundry'] = System::display_number(round($value['revenue_laundry']));
                    $items['REVENUE']['revenue_bar'] = $value['revenue_bar'] = System::display_number(round($value['revenue_bar']));
                    $items['REVENUE']['revenue_banquet'] = $value['revenue_banquet'] = System::display_number(round($value['revenue_banquet']));
                    $items['REVENUE']['revenue_spa'] = $value['revenue_spa'] = System::display_number(round($value['revenue_spa']));
                    $items['REVENUE']['revenue_shop'] = $value['revenue_shop'] = System::display_number(round($value['revenue_shop']));
                    $items['REVENUE']['revenue_other'] = $value['revenue_other'] = System::display_number(round($value['revenue_other']));
                    $items['REVENUE']['revenue_total'] = $value['revenue_total'] = System::display_number(round($total));
                    array_push($arr, $value);
                }
                if($type == 'GET')
                {
                    return $items;
                }else
                {
                    $this->response(200, json_encode($arr));
                }
            }else
            {
                $this->response(500, "FAILED"); // AUTH
            }
        }else
        {
            $this->response(500, "FAILED"); // METHOD
        }
	}
    
    function payment($in_date = false, $type = false)
    {
        if ($this->method == 'GET')
        {
    		if(Url::get('secretkey') and Url::get('secretkey') == '9a8fa234b2520e9bb4f59d8178545a62')
            {
                if(Url::get('in_date'))
                {
                    $in_date = Url::get('in_date');
                }
                $cond = 'payment.time >= \''.Date_Time::to_time($in_date).'\' AND payment.time <= \''.(Date_Time::to_time($in_date)+86399).'\' ';
                $payment = DB::fetch_all('
                                    SELECT
                                        payment.*,
                                        payment.amount * payment.exchange_rate as amount_vnd
                                    FROM
                                        payment
                                    WHERE
                                        '.$cond.'
                ');
                //System::debug($payment);
                $payment_type = DB::fetch_all("SELECT payment_type.def_code as id, payment_type.name_1 as name FROM payment_type WHERE apply = 'ALL' and structure_id != 1000000000000000000");
                //System::debug($payment_type);
                $items = array(
                            'PAYMENT' => array(),
                            'DEPOSIT' => array()
                );
                foreach($payment_type as $key => $value)
                {
                    $items['PAYMENT'][$value['id']] = 0;
                    $items['DEPOSIT'][$value['id']] = 0;
                }
                $items['PAYMENT']['total'] = 0;
                $items['DEPOSIT']['total'] = 0;
                
                $total_payment = 0;
                $total_deposit = 0;
                foreach($payment as $key => $value)
                {
                    if($value['type_dps'] == '')
                    {
                        $items['PAYMENT'][$value['payment_type_id']] += $value['amount_vnd'];
                        $total_payment += $value['amount_vnd'];
                        if($value['payment_type_id'] == 'REFUND')
                        {
                            $total_payment -= $value['amount_vnd'];
                        }
                    }else
                    {
                        $items['DEPOSIT'][$value['payment_type_id']] += $value['amount_vnd'];
                        $total_deposit += $value['amount_vnd'];
                    }
                }
                $items['PAYMENT']['total'] = System::display_number($total_payment);
                $items['DEPOSIT']['total'] = System::display_number($total_deposit);
                //System::debug($items);
                $arr = array();
                foreach($payment_type as $key => $value)
                {
                    $items['PAYMENT'][$value['id']] = System::display_number($items['PAYMENT'][$value['id']]);
                    $items['DEPOSIT'][$value['id']] = System::display_number($items['DEPOSIT'][$value['id']]);
                }
                
                foreach($items as $key => $value)
                {
                    array_push($arr, $value);
                }
                //System::debug($arr);
                if($type == 'GET')
                {
                    return $items;
                }else
                {
                    $this->response(200, json_encode($arr));
                }
            }else
            {
                $this->response(500, "FAILED"); // AUTH
            }
        }else
        {
            $this->response(500, "FAILED"); // METHOD
        }
	}
    
    function occupancy_forecast($in_date = false, $type = false)
    {
        if ($this->method == 'GET')
        {
    		if(Url::get('secretkey') and Url::get('secretkey') == '9a8fa234b2520e9bb4f59d8178545a62')
            {
                if(Url::get('in_date'))
                {
                    $in_date = Url::get('in_date');
                }
                
                /** Tạo dữ liệu mặc định cho mảng công suất phòng */
                $items = array(
                            'OCCUPANCY_FORECAST' => array(
                                                        'oc' => 0, // Công suất phòng
                                                        'total_room' => 0, //Tổng số phòng
                                                        'total_room_repair' => 0, // repair
                                                        'total_room_available' => 0, // phòng có sẵn
                                                        'total_room_occ' => 0, // phòng khách ở lưu
                                                        'total_room_checkin' => 0, // phòng đã checkin
                                                        'total_dayused_room' => 0, // phòng dayused
                                                        'total_room_arr' => 0, // phòng dự kiến đến
                                                        'total_room_in_date' => 0, //phòng ở hôm nay
                                                        'total_room_dep' => 0, // phòng dự kiến đi
                                                        'total_room_checkout' => 0, // phòng đã checkout
                                                        'total_adult' => 0, // người lớn
                                                        'total_child' => 0, // trẻ em
                                                        'total_avg_room' => 0, // giá bình quân
                                                        'total_next_room_arr' => 0, // phòng đến ngày mai
                                                        'total_next_room_dep' => 0, // phòng đi ngày mai
                                                        'total_next_room' => 0, // phòng đi ngày mai
                            )
                );
                
                $total_room = DB::fetch('
                                        SELECT 
                                            count(room.id) as total 
                                        FROM 
                                            room 
                                            inner join room_level on room_level.id = room.room_level_id 
                                        WHERE 
                                            room_level.is_virtual = 0 
                ','total');
                //System::debug($total_room);
                $items['OCCUPANCY_FORECAST']['total_room'] = $total_room;
                /** lay trang thai phong **/
                $from_day = Date_Time::to_orc_date($in_date);
                $end_day = Date_Time::to_orc_date($in_date);
                $next_day = Date_Time::to_time($in_date) + 86400;
                $next_day = date('d/m/Y', $next_day);
                $from_next_day = Date_Time::to_orc_date($next_day);
                $end_next_day = Date_Time::to_orc_date($next_day);
                $rooms = $this->get_total_room_status($from_day,$end_day); 
                $amount_room = $this->get_amout_room($from_day,$end_day);
                $rooms_next = $this->get_total_room_status($from_next_day,$end_next_day); 
                //System::debug($amount_room);
                if(isset($rooms[$in_date]))
                {
                    $items['OCCUPANCY_FORECAST']['total_room_repair'] = $rooms[$in_date]['repair_room'];
                    $items['OCCUPANCY_FORECAST']['total_room_available'] = $total_room - $rooms[$in_date]['repair_room']; 
                    $items['OCCUPANCY_FORECAST']['total_room_occ'] = $rooms[$in_date]['occ_room'];  
                    $items['OCCUPANCY_FORECAST']['total_room_checkin'] = $rooms[$in_date]['checkin_room'] + $rooms[$in_date]['dayused_co']; 
                    $items['OCCUPANCY_FORECAST']['total_dayused_room'] = $rooms[$in_date]['dayused_room']; 
                    $items['OCCUPANCY_FORECAST']['total_room_arr'] = $rooms[$in_date]['arr_room']; 
                    $items['OCCUPANCY_FORECAST']['total_room_in_date'] = $rooms[$in_date]['occ_room'] + $rooms[$in_date]['checkin_room'] + $rooms[$in_date]['dayused_co'] + $rooms[$in_date]['arr_room']; 
                    $items['OCCUPANCY_FORECAST']['total_room_dep'] = $rooms[$in_date]['dep_room']; 
                    $items['OCCUPANCY_FORECAST']['total_room_checkout'] = $rooms[$in_date]['checkout_room']; 
                    $items['OCCUPANCY_FORECAST']['total_adult'] = $rooms[$in_date]['adult']; 
                    $items['OCCUPANCY_FORECAST']['total_child'] = $rooms[$in_date]['child']; 
                } 
                $items['OCCUPANCY_FORECAST']['oc'] = round(($items['OCCUPANCY_FORECAST']['total_room_in_date']/$items['OCCUPANCY_FORECAST']['total_room_available'])*100,2);
                if($amount_room)
                {
                    $items['OCCUPANCY_FORECAST']['total_avg_room'] = System::display_number(round($amount_room[$in_date]['amount_total']/$items['OCCUPANCY_FORECAST']['total_room_in_date']));
                }
                if(isset($rooms_next[$next_day]))
                {
                    $items['OCCUPANCY_FORECAST']['total_next_room_arr'] = $rooms_next[$next_day]['arr_room']; 
                    $items['OCCUPANCY_FORECAST']['total_next_room_dep'] = $rooms_next[$next_day]['dep_room']; 
                    $items['OCCUPANCY_FORECAST']['total_next_room'] = $rooms_next[$next_day]['occ_room'] + $rooms_next[$next_day]['checkin_room'] + $rooms_next[$next_day]['dayused_co'] + $rooms_next[$next_day]['arr_room']; 
                }
                //System::debug($items);
                $arr = array();
                foreach($items as $key => $value)
                {
                    array_push($arr, $value);
                }
                //System::debug($arr);
                
                $this->response(200, json_encode($arr));
            }else
            {
                $this->response(500, "FAILED"); // AUTH
            }
        }else
        {
            $this->response(500, "FAILED"); // METHOD
        }
	}
    
    function plan($in_date = false, $type = false)
    {
        if ($this->method == 'GET')
        {
    		if(Url::get('secretkey') and Url::get('secretkey') == '9a8fa234b2520e9bb4f59d8178545a62')
            {
                
                $this->response(200, "TRUE");
            }else
            {
                $this->response(500, "FAILED"); // AUTH
            }
        }else
        {
            $this->response(500, "FAILED"); // METHOD
        }
	}
    
    function get_total_room_status($from_date,$to_date)
    {
		$room_status = array();
        $time_from_day =Date_Time::to_time(Date_Time::convert_orc_date_to_date($from_date,'/'));
        $time_to_day =Date_Time::to_time(Date_Time::convert_orc_date_to_date($to_date,'/'));
        for($i=$time_from_day ; $i<= $time_to_day; $i +=24*3600)
        {
            $k = date('d/m/Y',$i);
            $room_status[$k]['id'] = $k;
            $room_status[$k]['in_date'] = date('d/m/Y',$i);
            $room_status[$k]['repair_room'] = 0; /** phong hong **/
            $room_status[$k]['arr_room'] = 0; /** phong den **/
            $room_status[$k]['occ_room'] = 0; /** phong luu **/
            $room_status[$k]['dep_room'] = 0; /** phong di **/
            $room_status[$k]['dayused_room'] = 0; /** phong day_used **/
            $room_status[$k]['dayused_co'] = 0; /** phong day_used **/
            $room_status[$k]['checkin_room'] = 0; /** phong checkin trong ngay **/
            $room_status[$k]['checkout_room'] = 0; /** phong checkout trong ngay **/
            $room_status[$k]['foc'] = 0;
            $room_status[$k]['adult'] = 0; 
            $room_status[$k]['child'] = 0; 
            $room_status[$k]['child_5'] = 0;  
        }
        //System::debug($room_status);exit();
        /** Tinh phong Repair **/
        $sql='
			SELECT 
			     	count(rs.room_id) as total, 
                    rs.in_date,
                    rs.house_status,
					concat(rs.in_date,rs.house_status)as id
			FROM 
					room_status rs
                    inner join room on rs.room_id = room.id
                    inner join room_level on room.room_level_id = room_level.id
			WHERE 
					DATE_TO_UNIX(rs.in_date) >= DATE_TO_UNIX(\''.$from_date.'\') 
                    AND DATE_TO_UNIX(rs.in_date) <=DATE_TO_UNIX(\''.$to_date.'\')
                    and rs.house_status = \'REPAIR\'
                    and room_level.is_virtual = 0
			GROUP 
					BY rs.house_status,
                    rs.in_date
                    ';
        $rooms = DB::fetch_all($sql);
		foreach($rooms as $key => $value)
        {   
			$id = Date_Time::convert_orc_date_to_date($value['in_date'],'/');
			if($value['house_status'] == 'REPAIR')
            {
				$room_status[$id]['repair_room'] += $value['total'];
			}
		}
		/** Tinh phong den,di,luu **/
		$sql2='
				SELECT 
					rs.id as id,  
					rs.in_date,
                    date_to_unix(rs.in_date) as time_indate,
					rs.status, 
                    rr.arrival_time, 
                    rr.status as reservation_status,
					rr.departure_time,
                    rr.time_in,
                    rr.time_out,
                    rr.foc_all,
                    rr.foc,
                    nvl(room_level.is_virtual,0) as is_virtual,
                    nvl(rr.change_room_from_rr,0) as change_room_from_rr,
                    nvl(rr.change_room_to_rr,0) as change_room_to_rr,
                    from_unixtime(rr.old_arrival_time) as old_arrival_time,
                    rr.old_arrival_time as time_old,
                    reservation.id as r_id,
                    rr.room_level_id as room_level_id,
                    nvl(rr.adult,0) as adult,
                    nvl(rr.child,0) as child,
                    nvl(rr.child_5,0) as child_5,
                    room.id as room_id,
                    room.name as room_name
				FROM 
					room_status rs
                    inner JOIN reservation_room rr on rr.id = rs.reservation_room_id
                    inner join reservation on rr.reservation_id = reservation.id
                    left join room on rr.room_id = room.id
                    left join room_level on room_level.id = rr.room_level_id
				WHERE 
					DATE_TO_UNIX(rs.in_date) >= DATE_TO_UNIX(\''.$from_date.'\') 
                    AND DATE_TO_UNIX(rs.in_date) <=DATE_TO_UNIX(\''.$to_date.'\')
				    AND rr.status != \'CANCEL\' 
                    AND rr.status != \'NOSHOW\'
                    AND room_level.is_virtual = 0
                    AND rs.reservation_id != 0
                    AND rr.foc is null
                    AND rr.foc_all = 0
        ';
        $room_in_outs = DB::fetch_all($sql2);
        //System::debug($room_in_outs);
        foreach($room_in_outs as $key=>$value)
        {
            $id = Date_Time::convert_orc_date_to_date($value['in_date'],'/');;
        	if($value['change_room_from_rr']==0 and $value['change_room_to_rr']==0) /** th khong lien quan toi doi phong **/
            {
                if($value['in_date']==$value['arrival_time'] && $value['reservation_status'] == 'BOOKED') /** phong den truong hop binh thuong **/
                {
                    $room_status[$id]['arr_room'] += 1;
                }else if($value['in_date']==$value['arrival_time'] && $value['reservation_status'] == 'CHECKIN')
                {
                    $room_status[$id]['checkin_room'] += 1;
                }
                if($value['time_in']<$value['time_indate'] and $value['time_out']>=($value['time_indate']+86400)) /** phong luu truong hop binh thuong **/
                {
                    $room_status[$id]['occ_room'] += 1;
                }
                if($value['in_date']==$value['departure_time'] && $value['reservation_status'] == 'CHECKIN') /** phong di truong hop binh thuong **/
                {
                    $room_status[$id]['dep_room'] += 1;
                }else if($value['in_date']==$value['departure_time'] && $value['reservation_status'] == 'CHECKOUT')
                {
                    $room_status[$id]['checkout_room'] += 1;
                }
                if($value['arrival_time']==$value['departure_time'] and $value['arrival_time']==$value['in_date'])
                {
                    $room_status[$id]['dayused_room'] += 1;
                    $room_status[$id]['adult'] -= $value['adult'];
                    $room_status[$id]['child'] -= $value['child'];
                    if($value['reservation_status'] == 'CHECKOUT'){
                        $room_status[$id]['dayused_co'] += 1;
                    }
                }
            }
            elseif($value['change_room_from_rr']==0 and $value['change_room_to_rr']!=0) /** Truong hop phong chang dau cua doi phong**/
            {
                if($value['in_date']==$value['arrival_time'] && $value['reservation_status'] == 'BOOKED')
                {
                    $room_status[$id]['arr_room'] += 1;
                }else if($value['in_date']==$value['arrival_time'] && $value['reservation_status'] == 'CHECKIN')
                {
                    $room_status[$id]['checkin_room'] += 1;
                }
                if($value['time_in']<$value['time_indate'] and $value['time_out']>=($value['time_indate']+86400))
                {
                    $room_status[$id]['occ_room'] += 1;
                }
            }
            elseif($value['change_room_from_rr']!=0 and $value['change_room_to_rr']==0) /** Truong hop phong chang cuoi cua doi phong**/
            {
                if($value['in_date']==$value['departure_time'] && $value['reservation_status'] == 'CHECKIN')
                {
                    $room_status[$id]['dep_room'] += 1;
                }else if($value['in_date']==$value['departure_time'] && $value['reservation_status'] == 'CHECKOUT')
                {
                    $room_status[$id]['checkout_room'] += 1;
                }
                if($value['time_old']<$value['time_indate'] and $value['time_out']>=($value['time_indate']+86400))
                {
                    $room_status[$id]['occ_room'] += 1;
                }
                if($value['old_arrival_time']==$value['departure_time'] and $value['old_arrival_time'] == $value['in_date'])
                {
                    $room_status[$id]['dayused_room'] += 1;
                    $room_status[$id]['adult'] -= $value['adult'];
                    $room_status[$id]['child'] -= $value['child'];
                    if($value['reservation_status'] == 'CHECKOUT'){
                        $room_status[$id]['dayused_co'] += 1;
                    }
                }
            }
            elseif($value['change_room_from_rr']!=0 and $value['change_room_to_rr']!=0) /** Truong hop phong chang giua cua doi phong**/
            {
                if($value['time_old']<$value['time_indate'] and $value['time_out']>=($value['time_indate']+86400))
                {
                    $room_status[$id]['occ_room'] += 1;
                }
            }
            /** tinh phong mien phi, ng lon, tre e **/
            if($value['in_date'] !=$value['departure_time'] || ($value['in_date']==$value['departure_time'] and $value['arrival_time']=$value['departure_time'] and $value['change_room_to_rr']==0))
            {
                if($value['foc_all']==1 || $value['foc']!='')
                {
                    $room_status[$id]['foc'] += 1;
                }
                $room_status[$id]['adult'] += $value['adult'];
                $room_status[$id]['child'] += $value['child'];
                $room_status[$id]['child_5'] += $value['child_5'];
            }    
        }
        return $room_status;
	}
    
    function get_amout_room($date_from,$date_end)
    {
		$sql1 = '
		        SELECT 
						rs.id,
						case
                        when rs.in_date = rr.arrival_time
                        then 
                            (case
                             when rr.net_price = 0
                             then ((CHANGE_PRICE*(1-NVL(rr.REDUCE_BALANCE,0)/100.0)-NVL(rr.REDUCE_AMOUNT,0))*(1+NVL(rr.SERVICE_RATE,0)/100.0))*(1 + NVL(rr.TAX_RATE,0)/100.0)
                             else
                              ((((CHANGE_PRICE/(1+NVL(rr.SERVICE_RATE,0)/100.0))/(1 + NVL(rr.TAX_RATE,0)/100.0))*(1-NVL(rr.REDUCE_BALANCE,0)/100.0)-NVL(rr.REDUCE_AMOUNT,0))*(1+NVL(rr.SERVICE_RATE,0)/100.0))*(1 + NVL(rr.TAX_RATE,0)/100.0)
                            end) 
                        else
                            (case
                             when rr.net_price = 0
                             then (CHANGE_PRICE*(1-NVL(rr.REDUCE_BALANCE,0)/100.0)*(1+NVL(rr.SERVICE_RATE,0)/100.0))*(1 + NVL(rr.TAX_RATE,0)/100.0)
                             else
                              ((((CHANGE_PRICE/(1+NVL(rr.SERVICE_RATE,0)/100.0))/(1 + NVL(rr.TAX_RATE,0)/100.0))*(1-NVL(rr.REDUCE_BALANCE,0)/100.0))*(1+NVL(rr.SERVICE_RATE,0)/100.0))*(1 + NVL(rr.TAX_RATE,0)/100.0)
                            end)
                        end as change_price,
						rr.tax_rate, 
						service_rate,
						rs.in_date,
						rr.arrival_time,
                        rr.net_price,
						rr.departure_time,
						rr.price,
						rs.status,
						rr.id as reservation_room_id,
                        rr.room_level_id
					FROM 
						room_status rs 
						INNER JOIN reservation_room rr ON rr.id = rs.reservation_room_id 
						INNER JOIN reservation on rr.reservation_id = reservation.id
                        inner join room_level on room_level.id = rr.room_level_id
					WHERE 
						(rs.status =\'OCCUPIED\' OR rs.status =\'BOOKED\')
                        AND (room_level.is_virtual is null or room_level.is_virtual = 0 )
                        AND rr.foc is null
                        AND rr.foc_all = 0
                        AND (rs.in_date < rr.DEPARTURE_TIME OR  rr.DEPARTURE_TIME = rr.ARRIVAL_TIME)
						AND rs.in_date >= \''.$date_from.'\' 
						AND rs.in_date <= \''.$date_end.'\'
                       ';
        $room_totals = DB::fetch_all($sql1);
		// tinh tien phong su dung trong ngay
		$amount_room_days =array();
		foreach($room_totals as $key=>$value)
        {
			$id = Date_Time::convert_orc_date_to_date($value['in_date'],'/');
            if(!isset($amount_room_days[$id]['amount_total']))
            {
                 $amount_room_days[$id]['amount_total'] = $value['change_price'];
			}
            else
            {
                 $amount_room_days[$id]['amount_total'] += $value['change_price'];
			}
		}
		return $amount_room_days;
	}
}   
$api = new api();
?>