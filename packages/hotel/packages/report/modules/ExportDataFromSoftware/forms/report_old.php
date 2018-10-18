<?php
class ExportDataFromSoftwareForm extends Form
{
    function ExportDataFromSoftwareForm()
    {
        Form::Form('ExportDataFromSoftwareForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
    }

    function draw()
    {
        require_once 'packages/core/includes/utils/time_select.php';
        $this->map['export_date'] = isset($_REQUEST['export_date'])?$_REQUEST['export_date'] = $_REQUEST['export_date']:$_REQUEST['export_date'] = date('d/m/Y', Date_Time::to_time(date('d/m/Y', time())));
        $this->map = array();
        $this->parse_layout('report_old', $this->map);
        if(Url::get('cmd') == md5('ExportDataFromSoftwareNewway@2017')) 
        {
            $export_date = Url::get('export_date');
            $cond_date = Date_Time::convert_time_to_ora_date(Date_Time::to_time($export_date));
            /** Phát sinh của lễ tân */
            //tạo mảng chứa dữ liệu phát sinh phòng và tách tiền phòng và tiền thuế của phòng trong một mảng
            $rooms = array();
            $i = 1;
            $j = 1;
            $k = 1;
            $l = 1;
            $m = 1;
            $n = 1;
            $q = 1;
            $w = 1;
            $e = 1;
            $r = 1;
            $t = 1;
            $y = 1;
            $u = 1;
            $o = 1;
            //start lấy ra phát sinh phòng
            $reception = DB::fetch_all('
                            SELECT
                                room_status.id,
                                room_status.in_date,
                                room_status.change_price as price,
                                reservation.id as r_id,
                                reservation_room.id as rr_id,
                                to_char(room_status.in_date, \'DD/MM/YYYY\') as certificate_date,
                                room.name as room_name,
                                customer.name as customer_code,
                                customer.def_name as customer_name,
                                customer.mobile as customer_phone,
                                customer.address as customer_address,
                                customer.tax_code as customer_tax_code,
                                traveller.first_name || \' \' || traveller.last_name as traveller_name,
                                traveller.address as traveller_address,
                                reservation_room.net_price,
                                reservation_room.tax_rate,
                                reservation_room.service_rate
                            FROM
                                room_status
                                inner join reservation_room on reservation_room.id=room_status.reservation_room_id
                                inner join reservation on reservation_room.reservation_id=reservation.id
                                inner join room on reservation_room.room_id = room.id
                                inner join room_level on room.room_level_id = room_level.id
                                inner join customer on reservation.customer_id = customer.id
                                left join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                                left join traveller on traveller.id = reservation_traveller.traveller_id
                            WHERE
                                room_status.in_date = \''.$cond_date.'\'
                                and reservation_room.change_room_to_rr is null
                                and (reservation_room.status =\'CHECKIN\' or reservation_room.status =\'CHECKOUT\')
                                and reservation_room.status!=\'CANCEL\'
                                and room_level.name !=\'PA\'
                                and ( (room_status.in_date != reservation_room.departure_time and reservation_room.arrival_time != reservation_room.departure_time) OR (room_status.in_date = reservation_room.departure_time and reservation_room.arrival_time = reservation_room.departure_time) )
                                and room_status.change_price != 0
                            ORDER BY
                                room.id, room.name, room.position ASC
            ');
            //System::debug($reception);
            foreach($reception as $key => $value)
            {
                $revenue_room_id = $value['r_id'] .'_'. $value['room_name'];
                $tax_money_id = $value['r_id'] .'_'. $value['room_name'].'_'.$value['tax_rate'];
                // xây dựng mảng doanh thu phòng
                if(!isset($rooms[$revenue_room_id]['id']))
                {
                    $rooms[$revenue_room_id]['id'] = $revenue_room_id;
                    $rooms[$revenue_room_id]['certificate'] = 'TP';
                    $rooms[$revenue_room_id]['invoice_symbol'] = '';
                    $rooms[$revenue_room_id]['invoice_number'] = '';
                    $rooms[$revenue_room_id]['invoice_date'] = '';
                    $rooms[$revenue_room_id]['certificate_number'] = 'TP'.date('ymd', Date_Time::to_time($value['certificate_date'])).str_pad($i, 4,"0",STR_PAD_LEFT);
                    $rooms[$revenue_room_id]['certificate_date'] = $value['certificate_date'];
                    $rooms[$revenue_room_id]['description'] = 'Doanh thu phòng '.$value['room_name'].' ngày '.$value['certificate_date'].' recode '.$value['r_id'].' ('.$value['customer_code'].')';
                    $rooms[$revenue_room_id]['account_no'] = 132;
                    $rooms[$revenue_room_id]['customer_no'] = '';
                    $rooms[$revenue_room_id]['product_import'] = '';
                    $rooms[$revenue_room_id]['account_co'] = 5113;
                    $rooms[$revenue_room_id]['customer_co'] = '';
                    $rooms[$revenue_room_id]['list_code'] = 'ROOM';
                    $rooms[$revenue_room_id]['list_name'] = 'ROOM';
                    $rooms[$revenue_room_id]['unit'] = 'DEM';
                    $rooms[$revenue_room_id]['quantity'] = 1;
                    if($value['net_price'] == 0)
                    {
                        $rooms[$revenue_room_id]['price_room_before_tax'] = $value['price']*(1+($value['service_rate'] / 100));                       
                    }else
                    {
                        $rooms[$revenue_room_id]['price_room_before_tax'] = $value['price']/(1+($value['tax_rate'] / 100));                        
                    }
                    if($value['net_price'] == 0)
                    {
                        $rooms[$revenue_room_id]['total_before_tax'] = $value['price']*(1+($value['service_rate'] / 100));                       
                    }else
                    {
                        $rooms[$revenue_room_id]['total_before_tax'] = $value['price']/(1+($value['tax_rate'] / 100));                        
                    }
                    $rooms[$revenue_room_id]['pt_ck'] = '';
                    $rooms[$revenue_room_id]['ck'] = '';
                    $rooms[$revenue_room_id]['invoice_vat'] = '';
                    $rooms[$revenue_room_id]['account_tax'] = 33311;
                    $rooms[$revenue_room_id]['ts_gtgt'] = $value['tax_rate'];
                    if($value['net_price'] == 0)
                    {
                        $rooms[$revenue_room_id]['tax_vn'] = ($value['price']*(1+($value['service_rate'] / 100)))*($value['tax_rate'] / 100);                       
                    }else
                    {
                        $rooms[$revenue_room_id]['tax_vn'] = ($value['price']/(1+($value['tax_rate'] / 100)))*($value['tax_rate'] / 100);                        
                    }
                    if($value['net_price'] == 0)
                    {
                        $rooms[$revenue_room_id]['total_vnd_tt'] = $value['price']*(1+($value['service_rate'] / 100)) + ($value['price']*(1+($value['service_rate'] / 100)))*($value['tax_rate'] / 100);                       
                    }else
                    {
                        $rooms[$revenue_room_id]['total_vnd_tt'] = $value['price']/(1+($value['tax_rate'] / 100)) + ($value['price']/(1+($value['tax_rate'] / 100)))*($value['tax_rate'] / 100);                        
                    }
                    $rooms[$revenue_room_id]['customer_code'] = $value['customer_code'];
                    $rooms[$revenue_room_id]['customer_name'] = $value['customer_name'];
                    $rooms[$revenue_room_id]['customer_tax_code'] = $value['customer_tax_code'];
                    $rooms[$revenue_room_id]['customer_address'] = $value['customer_address'];
                    $rooms[$revenue_room_id]['traveller_address'] = $value['traveller_address'];
                    $rooms[$revenue_room_id]['traveller_name'] = $value['traveller_name'] .' phòng '.$value['room_name'].' recode '.$value['r_id'];
                    $rooms[$revenue_room_id]['note'] = '';
                }
                
                //Xây dựng mảng tiền thuế
                if(!isset($rooms[$tax_money_id]['id']))
                {
                    $rooms[$tax_money_id]['id'] = $tax_money_id;
                    $rooms[$tax_money_id]['certificate'] = 'TP';
                    $rooms[$tax_money_id]['invoice_symbol'] = '';
                    $rooms[$tax_money_id]['invoice_number'] = '';
                    $rooms[$tax_money_id]['invoice_date'] = '';
                    $rooms[$tax_money_id]['certificate_number'] = 'TP'.date('ymd', Date_Time::to_time($value['certificate_date'])).str_pad($i, 4,"0",STR_PAD_LEFT);
                    $rooms[$tax_money_id]['certificate_date'] = $value['certificate_date'];
                    $rooms[$tax_money_id]['description'] = 'Thuế GTGT phòng '.$value['room_name'].' ngày '.$value['certificate_date'].' recode '.$value['r_id'].' ('.$value['customer_code'].')';
                    $rooms[$tax_money_id]['account_no'] = 132;
                    $rooms[$tax_money_id]['customer_no'] = '';
                    $rooms[$tax_money_id]['product_import'] = '';
                    $rooms[$tax_money_id]['account_co'] = 33311;
                    $rooms[$tax_money_id]['customer_co'] = '';
                    $rooms[$tax_money_id]['list_code'] = '';
                    $rooms[$tax_money_id]['list_name'] = '';
                    $rooms[$tax_money_id]['unit'] = '';
                    $rooms[$tax_money_id]['quantity'] = '';
                    $rooms[$tax_money_id]['price_room_before_tax'] = '';
                    if($value['net_price'] == 0)
                    {
                        $rooms[$tax_money_id]['total_before_tax'] = $value['price'] -($value['price']*(1+($value['service_rate'] / 100)));                      
                    }else
                    {
                        $rooms[$tax_money_id]['total_before_tax'] = $value['price'] - ($value['price']/(1+($value['tax_rate'] / 100)));                        
                    }
                    $rooms[$tax_money_id]['pt_ck'] = '';
                    $rooms[$tax_money_id]['ck'] = '';
                    $rooms[$tax_money_id]['invoice_vat'] = '';
                    $rooms[$tax_money_id]['account_tax'] = '';
                    $rooms[$tax_money_id]['ts_gtgt'] = '';
                    if($value['net_price'] == 0)
                    {
                        $rooms[$tax_money_id]['tax_vn'] = '';                       
                    }else
                    {
                        $rooms[$tax_money_id]['tax_vn'] = '';                        
                    }
                    if($value['net_price'] == 0)
                    {
                        $rooms[$tax_money_id]['total_vnd_tt'] = ($value['price']*(1+($value['service_rate'] / 100)))*($value['tax_rate'] / 100);                       
                    }else
                    {
                        $rooms[$tax_money_id]['total_vnd_tt'] = ($value['price']/(1+($value['tax_rate'] / 100)))*($value['tax_rate'] / 100);                        
                    }
                    $rooms[$tax_money_id]['customer_code'] = $value['customer_code'];
                    $rooms[$tax_money_id]['customer_name'] = $value['customer_name'];
                    $rooms[$tax_money_id]['customer_tax_code'] = $value['customer_tax_code'];
                    $rooms[$tax_money_id]['customer_address'] = $value['customer_address'];
                    $rooms[$tax_money_id]['traveller_address'] = '';
                    $rooms[$tax_money_id]['traveller_name'] = $value['traveller_name'];
                    $rooms[$tax_money_id]['note'] = '';
                }
                $i++;
            }
            
            //Nhận Thanh toán hộ
            //lấy ra thông của nguồn khách thanh toán hộ
            $folio_arr = array();
            $folio = DB::fetch_all('
                                    SELECT
                                        folio.id,
                                        folio.id as folio_id,
                                        folio.reservation_id as r_id_b,
                                        folio.reservation_room_id as rr_id_b,
                                        folio.create_time,
                                        customer.name as customer_code_b,
                                        customer.def_name as customer_name_b,
                                        customer.mobile as customer_phone_b,
                                        customer.address as customer_address_b,
                                        customer.tax_code as customer_tax_code_b,
                                        traveller.first_name || \' \' || traveller.last_name as traveller_name_b,
                                        room.name as room_name_b
                                    FROM
                                        folio
                                        inner join traveller_folio ON folio.id = traveller_folio.folio_id   
                					    inner join reservation_room on reservation_room.id = traveller_folio.reservation_room_id
                                        left join room on reservation_room.room_id = room.id
                                        inner join reservation on reservation_room.reservation_id = reservation.id
                                        inner join customer on reservation.customer_id = customer.id
                                        left join reservation_traveller on reservation_traveller.id = folio.reservation_traveller_id
                                        left join traveller on traveller.id = reservation_traveller.traveller_id
                                        left join room on room.id = reservation_room.room_id
                                    WHERE
                                        folio.create_time >= \''.Date_Time::to_time($export_date).'\'
                                        and folio.create_time < \''.(Date_Time::to_time($export_date)+86399).'\'
                                    ORDER BY
                                        folio.id ASC
            ');
            //System::debug($folio);
            foreach($folio as $key => $value)
            {
                if($value['folio_id'])
                {
                    $folio_traveller = DB::fetch_all('
                            SELECT
                                traveller_folio.id,
                                traveller_folio.reservation_id as r_id_b,
                                traveller_folio.reservation_room_id as rr_id_b,
                                traveller_folio.folio_id,
                                traveller_folio.add_payment,
                                traveller_folio.total_amount,
                                room.name as room_name_b
                            FROM
                                traveller_folio
                                inner join folio on folio.id = traveller_folio.folio_id
                                left join reservation_traveller on reservation_traveller.id = traveller_folio.reservation_traveller_id
                                left join traveller on traveller.id = reservation_traveller.traveller_id
                                left join reservation_room on reservation_room.id = reservation_traveller.reservation_room_id
                                left join room on room.id = reservation_room.room_id
                            WHERE
                                traveller_folio.folio_id = \''.$value['folio_id'].'\'
                                and traveller_folio.add_payment = 1
                            ORDER BY
                                traveller_folio.id ASC
                    ');
                    if(empty($folio_traveller))
                    {
                        unset($folio[$key]);
                    }
                    //System::debug($folio_traveller);
                    foreach($folio_traveller as $k => $v)
                    {
                        $id = $v['folio_id'].'_'.$v['r_id_b'].'_'.$v['rr_id_b'];
                        if($value['folio_id'] == $v['folio_id'])
                        {
                            if(!isset($folio_arr[$id]['id']))
                            {
                                $folio_arr[$id]['id'] = $id;
                                $folio_arr[$id]['r_id_b'] = $v['r_id_b'];
                                $folio_arr[$id]['rr_id_b'] = $v['rr_id_b'];
                                $folio_arr[$id]['room_name_b'] = $v['room_name_b'];
                                $folio_arr[$id]['certificate_date'] = date('d/m/Y', $value['create_time']);
                                $folio_arr[$id]['customer_code_b'] = $value['customer_code_b'];
                                $folio_arr[$id]['customer_name_b'] = $value['customer_name_b'];
                                $folio_arr[$id]['customer_address_b'] = $value['customer_address_b'];
                                $folio_arr[$id]['customer_tax_code_b'] = $value['customer_tax_code_b'];
                                $folio_arr[$id]['traveller_name_b'] = $value['traveller_name_b'];
                                $folio_arr[$id]['total_amount'] = $v['total_amount'];
                            }else
                            {
                                $folio_arr[$id]['total_amount'] += $v['total_amount'];                                
                            }
                        }
                    }
                }
            }
            foreach($folio_arr as $key => $value)
            {
                $info_customer_a = DB::fetch_all('
                                        SELECT
                                            reservation.id,
                                            reservation.id as r_id_a,
                                            reservation_room.id as rr_id_a,
                                            customer.name as customer_code_a,
                                            customer.def_name as customer_name_a,
                                            customer.mobile as customer_phone_a,
                                            customer.address as customer_address_a,
                                            customer.tax_code as customer_tax_code_a,
                                            traveller.first_name || \' \' || traveller.last_name as traveller_name_a,
                                            room.name as room_name_a
                                        FROM
                                            reservation
                                            inner join reservation_room on reservation.id = reservation_room.reservation_id
                                            left join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
                                            left join traveller on traveller.id = reservation_traveller.traveller_id
                                            left join room on room.id = reservation_room.room_id
                                            inner join customer on reservation.customer_id = customer.id
                                        WHERE
                                            reservation_room.id = \''.$value['rr_id_b'].'\'
                                        ORDER BY
                                            reservation.id ASC
                ');
                foreach($info_customer_a as $k => $v)
                {
                    if($value['rr_id_b'] == $v['rr_id_a'])
                    {
                        $folio_arr[$key]['r_id_a'] = $v['r_id_a'];
                        $folio_arr[$key]['rr_id_a'] = $v['rr_id_a'];
                        $folio_arr[$key]['room_name_a'] = $v['room_name_a'];
                        $folio_arr[$key]['customer_code_a'] = $v['customer_code_a'];
                        $folio_arr[$key]['customer_name_a'] = $v['customer_name_a'];
                        $folio_arr[$key]['customer_phone_a'] = $v['customer_phone_a'];
                        $folio_arr[$key]['customer_address_a'] = $v['customer_address_a'];
                        $folio_arr[$key]['customer_tax_code_a'] = $v['customer_tax_code_a']; 
                        $folio_arr[$key]['traveller_name_a'] = $v['traveller_name_a'];                         
                    }
                }
            }
            foreach($folio_arr as $key =>$value)
            {
                $folio_arr_id_pcn = $value['id'].'_PCN';
                if(!isset($rooms[$folio_arr_id_pcn]['id']))
                {
                    $rooms[$folio_arr_id_pcn]['id'] = $folio_arr_id_pcn;
                    $rooms[$folio_arr_id_pcn]['certificate'] = 'PCN';
                    $rooms[$folio_arr_id_pcn]['invoice_symbol'] = '';
                    $rooms[$folio_arr_id_pcn]['invoice_number'] = '';
                    $rooms[$folio_arr_id_pcn]['invoice_date'] = '';
                    $rooms[$folio_arr_id_pcn]['certificate_number'] = 'PCN'.date('ymd', Date_Time::to_time($value['certificate_date'])).str_pad($j, 4,"0",STR_PAD_LEFT);
                    $rooms[$folio_arr_id_pcn]['certificate_date'] = $value['certificate_date'];
                    $rooms[$folio_arr_id_pcn]['description'] = 'Chuyển công nợ khách hàng từ ('.$value['traveller_name_a'].' ('.$value['customer_code_a'].'), phòng '.$value['room_name_a'].', recode '.$value['r_id_a'].') sang ( '. $value['traveller_name_b'] .' ('.$value['customer_code_b'].'), phòng '.$value['room_name_b'].', recode '.$value['r_id_b'];
                    $rooms[$folio_arr_id_pcn]['account_no'] = 131;
                    $rooms[$folio_arr_id_pcn]['customer_no'] = '';
                    $rooms[$folio_arr_id_pcn]['product_import'] = '';
                    $rooms[$folio_arr_id_pcn]['account_co'] = 132;
                    $rooms[$folio_arr_id_pcn]['customer_co'] = $value['customer_code_a'];
                    $rooms[$folio_arr_id_pcn]['list_code'] = '';
                    $rooms[$folio_arr_id_pcn]['list_name'] = '';
                    $rooms[$folio_arr_id_pcn]['unit'] = '';
                    $rooms[$folio_arr_id_pcn]['quantity'] = '';
                    $rooms[$folio_arr_id_pcn]['price_room_before_tax'] = '';
                    $rooms[$folio_arr_id_pcn]['total_before_tax'] = $value['total_amount'];
                    $rooms[$folio_arr_id_pcn]['pt_ck'] = '';
                    $rooms[$folio_arr_id_pcn]['ck'] = '';
                    $rooms[$folio_arr_id_pcn]['invoice_vat'] = '';
                    $rooms[$folio_arr_id_pcn]['account_tax'] = '';
                    $rooms[$folio_arr_id_pcn]['ts_gtgt'] = '';
                    $rooms[$folio_arr_id_pcn]['tax_vn'] = '';
                    $rooms[$folio_arr_id_pcn]['total_vnd_tt'] = '';
                    $rooms[$folio_arr_id_pcn]['customer_code'] = $value['customer_code_b'];
                    $rooms[$folio_arr_id_pcn]['customer_name'] = $value['customer_name_b'];
                    $rooms[$folio_arr_id_pcn]['customer_tax_code'] = $value['customer_tax_code_b'];
                    $rooms[$folio_arr_id_pcn]['customer_address'] = $value['customer_address_b'];
                    $rooms[$folio_arr_id_pcn]['traveller_address'] = '';
                    $rooms[$folio_arr_id_pcn]['traveller_name'] = $value['traveller_name_b'] .'phòng '.$value['room_name_b'].', recode '.$value['r_id_b'];
                    $rooms[$folio_arr_id_pcn]['note'] = '';
                } 
                $j++;
            }
            //Nhận đặt cọc
            // lấy ra đặt cọc nhóm
            $deposit_group = DB::fetch_all('
                            SELECT
                                payment.id,
                                payment.payment_type_id,
                                payment.time as certificate_date,
                                reservation.id as r_id,
                                reservation_room.id as rr_id,
                                payment.amount,
                                payment.type_dps,
                                customer.name as customer_code,
                                customer.def_name as customer_name,
                                customer.mobile as customer_phone,
                                customer.address as customer_address,
                                customer.tax_code as customer_tax_code,
                                traveller.first_name || \' \' || traveller.last_name as traveller_name
                            FROM
                                payment
                                inner join reservation on reservation.id = payment.reservation_id
                                inner join reservation_room on reservation_room.reservation_id = reservation.id
                                left join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
                                left join traveller on traveller.id = reservation_traveller.traveller_id
                                left join customer on reservation.customer_id = customer.id
                            WHERE
                                payment.time >= \''.Date_Time::to_time($export_date).'\'
                                and payment.time < \''.(Date_Time::to_time($export_date)+86399).'\'
                                and payment.type = \'RESERVATION\'
                                and payment.type_dps = \'GROUP\'
                            ORDER BY
                                payment.id ASC
            ');
            // lấy ra đặt cọc phòng
            $deposit_room = DB::fetch_all('
                            SELECT
                                payment.id,
                                payment.payment_type_id,
                                payment.time as certificate_date,
                                reservation.id as r_id,
                                reservation_room.id as rr_id,
                                payment.amount,
                                payment.type_dps,
                                customer.name as customer_code,
                                customer.def_name as customer_name,
                                customer.mobile as customer_phone,
                                customer.address as customer_address,
                                customer.tax_code as customer_tax_code,
                                traveller.first_name || \' \' || traveller.last_name as traveller_name
                            FROM
                                payment
                                inner join reservation_room on reservation_room.id = payment.reservation_room_id
                                inner join reservation on reservation_room.reservation_id = reservation.id
                                left join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
                                left join traveller on traveller.id = reservation_traveller.traveller_id
                                left join customer on reservation.customer_id = customer.id
                            WHERE
                                payment.time >= \''.Date_Time::to_time($export_date).'\'
                                and payment.time < \''.(Date_Time::to_time($export_date)+86399).'\'
                                and reservation_room.change_room_to_rr is null
                                and payment.type = \'RESERVATION\'
                                and payment.type_dps = \'ROOM\'
                            ORDER BY
                                payment.id ASC
            ');
            $deposit_arr = $deposit_group + $deposit_room;
            foreach($deposit_arr as $key => $value)
            {
                $deposit_id = $value['id'].'_'.$value['r_id'].'_'.$value['rr_id'];
                if(!isset($rooms[$deposit_id]['id']))
                {
                    $rooms[$deposit_id]['id'] = $deposit_id;
                    if($value['payment_type_id'] == 'CASH')
                    {
                        $rooms[$deposit_id]['certificate'] = 'PT';    
                    }else if($value['payment_type_id'] == 'CREDIT_CARD' or $value['payment_type_id'] == 'BANK')
                    {
                        $rooms[$deposit_id]['certificate'] = 'GBC';                        
                    }
                    $rooms[$deposit_id]['invoice_symbol'] = '';
                    $rooms[$deposit_id]['invoice_number'] = '';
                    $rooms[$deposit_id]['invoice_date'] = '';
                    if($value['payment_type_id'] == 'CASH')
                    {
                        $rooms[$deposit_id]['certificate_number'] = 'PT'.date('ymd', $value['certificate_date']).str_pad($k, 4,"0",STR_PAD_LEFT);    
                    }else if($value['payment_type_id'] == 'CREDIT_CARD' or $value['payment_type_id'] == 'BANK')
                    {
                        $rooms[$deposit_id]['certificate_number'] = 'GBC'.date('ymd', $value['certificate_date']).str_pad($k, 4,"0",STR_PAD_LEFT);                        
                    }
                    $rooms[$deposit_id]['certificate_date'] = date('d/m/Y', $value['certificate_date']);
                    $rooms[$deposit_id]['description'] = 'Khách hàng ' . $value['traveller_name'] . ' ( '.$value['customer_code'].' ), chuyển tiền đặt cọc cho recode ' .$value['r_id'];
                    if($value['payment_type_id'] == 'CASH')
                    {
                         $rooms[$deposit_id]['account_no'] = 1111;    
                    }else if($value['payment_type_id'] == 'CREDIT_CARD' or $value['payment_type_id'] == 'BANK')
                    {
                        $rooms[$deposit_id]['account_no'] = 1121;                        
                    }
                    $rooms[$deposit_id]['customer_no'] = '';
                    $rooms[$deposit_id]['product_import'] = '';
                    $rooms[$deposit_id]['account_co'] = 131;
                    $rooms[$deposit_id]['customer_co'] = $value['customer_code'];
                    $rooms[$deposit_id]['list_code'] = '';
                    $rooms[$deposit_id]['list_name'] = '';
                    $rooms[$deposit_id]['unit'] = '';
                    $rooms[$deposit_id]['quantity'] = '';
                    $rooms[$deposit_id]['price_room_before_tax'] = '';
                    $rooms[$deposit_id]['total_before_tax'] = $value['amount'];
                    $rooms[$deposit_id]['pt_ck'] = '';
                    $rooms[$deposit_id]['ck'] = '';
                    $rooms[$deposit_id]['invoice_vat'] = '';
                    $rooms[$deposit_id]['account_tax'] = '';
                    $rooms[$deposit_id]['ts_gtgt'] = '';
                    $rooms[$deposit_id]['tax_vn'] = '';
                    $rooms[$deposit_id]['total_vnd_tt'] = '';
                    $rooms[$deposit_id]['customer_code'] = $value['customer_code'];
                    $rooms[$deposit_id]['customer_name'] = $value['customer_name'];
                    $rooms[$deposit_id]['customer_tax_code'] = $value['customer_tax_code'];
                    $rooms[$deposit_id]['customer_address'] = $value['customer_address'];
                    $rooms[$deposit_id]['traveller_address'] = '';
                    $rooms[$deposit_id]['traveller_name'] = $value['traveller_name'];
                    $rooms[$deposit_id]['note'] = '';                    
                }
                $k++;                
            }
            // Ghi nhận giảm giá
            // lấy ra giảm giá theo %, giảm giá theo % được giảm giá theo từng ngày
            $reduce_balance = DB::fetch_all('
                                    SELECT
                                        room_status.id,
                                        room_status.in_date,
                                        room_status.change_price as price,
                                        reservation.id as r_id,
                                        reservation_room.id as rr_id,
                                        to_char(room_status.in_date, \'DD/MM/YYYY\') as certificate_date,
                                        room.name as room_name,
                                        customer.name as customer_code,
                                        customer.def_name as customer_name,
                                        customer.mobile as customer_phone,
                                        customer.address as customer_address,
                                        customer.tax_code as customer_tax_code,
                                        reservation_room.reduce_balance,
                                        reservation_room.reduce_balance as reduce,
                                        reservation_room.reduce_amount,
                                        reservation_room.net_price,
                                        reservation_room.tax_rate,
                                        reservation_room.service_rate,
                                        traveller.first_name || \' \' || traveller.last_name as traveller_name
                                    FROM
                                        room_status
                                        inner join reservation_room on reservation_room.id=room_status.reservation_room_id
                                        inner join reservation on reservation_room.reservation_id=reservation.id
                                        inner join customer on reservation.customer_id = customer.id
                                        inner join room on reservation_room.room_id = room.id
                                        inner join room_level on room.room_level_id = room_level.id
                                        left join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                                        left join traveller on traveller.id = reservation_traveller.traveller_id
                                    WHERE
                                        room_status.in_date = \''.$cond_date.'\'
                                        and reservation_room.change_room_to_rr is null
                                        and (reservation_room.status =\'CHECKIN\' or reservation_room.status =\'CHECKOUT\')
                                        and reservation_room.status!=\'CANCEL\'
                                        and room_level.name !=\'PA\'
                                        and reservation_room.reduce_balance > 0
                                        and ( (room_status.in_date != reservation_room.departure_time and reservation_room.arrival_time != reservation_room.departure_time) OR (room_status.in_date = reservation_room.departure_time and reservation_room.arrival_time = reservation_room.departure_time) )
                                    ORDER BY
                                        room_status.id ASC
            ');
            // lấy gia giảm giá theo số tiền, giảm giá theo số tiền là giảm giá trên toàn hóa đơn
            $reduce_amount = DB::fetch_all('
                                    SELECT
                                        room_status.id,
                                        room_status.in_date,
                                        room_status.change_price as price,
                                        reservation.id as r_id,
                                        reservation_room.id as rr_id,
                                        to_char(room_status.in_date, \'DD/MM/YYYY\') as certificate_date,
                                        room.name as room_name,
                                        customer.name as customer_code,
                                        customer.def_name as customer_name,
                                        customer.mobile as customer_phone,
                                        customer.address as customer_address,
                                        customer.tax_code as customer_tax_code,
                                        reservation_room.reduce_balance,
                                        reservation_room.reduce_amount,
                                        reservation_room.reduce_amount as reduce,
                                        reservation_room.net_price,
                                        reservation_room.tax_rate,
                                        reservation_room.service_rate,
                                        traveller.first_name || \' \' || traveller.last_name as traveller_name
                                    FROM
                                        room_status
                                        inner join reservation_room on reservation_room.id=room_status.reservation_room_id
                                        inner join reservation on reservation_room.reservation_id=reservation.id
                                        inner join customer on reservation.customer_id = customer.id
                                        inner join room on reservation_room.room_id = room.id
                                        inner join room_level on room.room_level_id = room_level.id
                                        left join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                                        left join traveller on traveller.id = reservation_traveller.traveller_id
                                    WHERE
                                        room_status.in_date = \''.$cond_date.'\'
                                        and reservation_room.change_room_to_rr is null
                                        and reservation_room.status = \'CHECKOUT\'
                                        and reservation_room.reduce_amount > 0
                                        and ( (room_status.in_date != reservation_room.departure_time and reservation_room.arrival_time != reservation_room.departure_time) OR (room_status.in_date = reservation_room.departure_time and reservation_room.arrival_time = reservation_room.departure_time) )
                                    ORDER BY
                                        room_status.id ASC
            ');
            $reduce_arr = $reduce_balance + $reduce_amount;
            foreach($reduce_arr as $key => $value)
            {
                $reduce_id = $value['r_id'] .'_'.$value['rr_id'];
                // tạo mảng giảm giá chưa có thuế
                if(!isset($rooms[$reduce_id]['id']))
                {
                    $rooms[$reduce_id]['id'] = $reduce_id;
                    $rooms[$reduce_id]['certificate'] = 'PGG ';
                    $rooms[$reduce_id]['invoice_symbol'] = '';
                    $rooms[$reduce_id]['invoice_number'] = '';
                    $rooms[$reduce_id]['invoice_date'] = '';
                    $rooms[$reduce_id]['certificate_number'] = 'PGG'.date('ymd', Date_Time::to_time($value['certificate_date'])).str_pad($l, 4,"0",STR_PAD_LEFT);
                    $rooms[$reduce_id]['certificate_date'] = $value['certificate_date'];
                    $rooms[$reduce_id]['description'] = 'Giảm tiền phòng cho khách hàng '.$value['traveller_name'].' ( '.$value['customer_code'].' ), phòng '.$value['room_name'].' recode '.$value['r_id'];
                    $rooms[$reduce_id]['account_no'] = 5213;
                    $rooms[$reduce_id]['customer_no'] = '';
                    $rooms[$reduce_id]['product_import'] = '';
                    $rooms[$reduce_id]['account_co'] = 132;
                    $rooms[$reduce_id]['customer_co'] = $value['customer_code'];
                    $rooms[$reduce_id]['list_code'] = '';
                    $rooms[$reduce_id]['list_name'] = '';
                    $rooms[$reduce_id]['unit'] = '';
                    $rooms[$reduce_id]['quantity'] = '';
                    $rooms[$reduce_id]['price_room_before_tax'] = '';
                    if($value['reduce_balance'] > 0)
                    {
                        if($value['net_price'] == 0)
                        {
                            $rooms[$reduce_id]['total_before_tax'] = $value['price']*(1+($value['service_rate'] / 100))*($value['reduce_balance']/100);
                            $rooms[$reduce_id]['ck'] = $value['price']*($value['reduce_balance']/100)*(1+($value['service_rate'] / 100));
                            $rooms[$reduce_id]['tax_vn'] = $rooms[$reduce_id]['ck']*($value['tax_rate'] / 100);
                            $rooms[$reduce_id]['total_vnd_tt'] = $rooms[$reduce_id]['ck'] + $rooms[$reduce_id]['tax_vn'];                      
                        }else
                        {
                            $rooms[$reduce_id]['total_before_tax'] = ($value['price']/(1+($value['tax_rate'] / 100)))*($value['reduce_balance']/100);
                            $rooms[$reduce_id]['ck'] = ($value['price']/(1+($value['tax_rate'] / 100)))*($value['reduce_balance']/100);
                            $rooms[$reduce_id]['tax_vn'] = $rooms[$reduce_id]['ck']*($value['tax_rate'] / 100);
                            $rooms[$reduce_id]['total_vnd_tt'] = $rooms[$reduce_id]['ck'] + $rooms[$reduce_id]['tax_vn'];                        
                        }
                        $rooms[$reduce_id]['pt_ck'] = $value['reduce_balance'];                    
                    }else
                    {
                        $rooms[$reduce_id]['total_before_tax'] = $value['reduce_amount']; 
                        $rooms[$reduce_id]['ck'] = $value['reduce_amount'];
                        $rooms[$reduce_id]['tax_vn'] = '';   
                        $rooms[$reduce_id]['total_vnd_tt'] = $value['reduce_amount'];
                        $rooms[$reduce_id]['pt_ck'] = '';                
                    }
                    $rooms[$reduce_id]['invoice_vat'] = '';
                    $rooms[$reduce_id]['account_tax'] = 33311;
                    $rooms[$reduce_id]['ts_gtgt'] = $value['tax_rate'];
                    $rooms[$reduce_id]['customer_code'] = $value['customer_code'];
                    $rooms[$reduce_id]['customer_name'] = $value['customer_name'];
                    $rooms[$reduce_id]['customer_tax_code'] = $value['customer_tax_code'];
                    $rooms[$reduce_id]['customer_address'] = $value['customer_address'];
                    $rooms[$reduce_id]['traveller_address'] = '';
                    $rooms[$reduce_id]['traveller_name'] = $value['traveller_name'];
                    $rooms[$reduce_id]['note'] = '';                    
                } 
                
                //tạo mảng thuế
                $reduce_tax_id = $value['r_id'] .'_'.$value['rr_id'] .'_'.$value['tax_rate'];
                if(!isset($rooms[$reduce_tax_id]['id']))
                {
                    $rooms[$reduce_tax_id]['id'] = $reduce_tax_id;
                    $rooms[$reduce_tax_id]['certificate'] = 'PGG ';
                    $rooms[$reduce_tax_id]['invoice_symbol'] = '';
                    $rooms[$reduce_tax_id]['invoice_number'] = '';
                    $rooms[$reduce_tax_id]['invoice_date'] = '';
                    $rooms[$reduce_tax_id]['certificate_number'] = 'PGG'.date('ymd', Date_Time::to_time($value['certificate_date'])).str_pad($l, 4,"0",STR_PAD_LEFT);
                    $rooms[$reduce_tax_id]['certificate_date'] = $value['certificate_date'];
                    $rooms[$reduce_tax_id]['description'] = 'Giảm thuế GTGT cho khách hàng '.$value['traveller_name'].' ( '.$value['customer_code'].' ), phòng '.$value['room_name'].' recode '.$value['r_id'];
                    $rooms[$reduce_tax_id]['account_no'] = 33311;
                    $rooms[$reduce_tax_id]['customer_no'] = '';
                    $rooms[$reduce_tax_id]['product_import'] = '';
                    $rooms[$reduce_tax_id]['account_co'] = 132;
                    $rooms[$reduce_tax_id]['customer_co'] = $value['customer_code'];
                    $rooms[$reduce_tax_id]['list_code'] = '';
                    $rooms[$reduce_tax_id]['list_name'] = '';
                    $rooms[$reduce_tax_id]['unit'] = '';
                    $rooms[$reduce_tax_id]['quantity'] = '';
                    $rooms[$reduce_tax_id]['price_room_before_tax'] = '';
                    if($value['reduce_balance'] > 0)
                    {
                        $rooms[$reduce_tax_id]['total_before_tax'] = $rooms[$reduce_id]['ck']*($value['tax_rate'] / 100);
                        $rooms[$reduce_tax_id]['total_vnd_tt'] = $rooms[$reduce_id]['ck']*($value['tax_rate'] / 100);
                    }else
                    {
                        $rooms[$reduce_tax_id]['total_before_tax'] = '';
                        $rooms[$reduce_tax_id]['total_vnd_tt'] = '';                    
                    }
                    $rooms[$reduce_tax_id]['pt_ck'] = '';
                    $rooms[$reduce_tax_id]['ck'] = '';
                    $rooms[$reduce_tax_id]['invoice_vat'] = '';
                    $rooms[$reduce_tax_id]['account_tax'] = '';
                    $rooms[$reduce_tax_id]['ts_gtgt'] = '';
                    $rooms[$reduce_tax_id]['tax_vn'] = '';
                    $rooms[$reduce_tax_id]['customer_code'] = $value['customer_code'];
                    $rooms[$reduce_tax_id]['customer_name'] = $value['customer_name'];
                    $rooms[$reduce_tax_id]['customer_tax_code'] = $value['customer_tax_code'];
                    $rooms[$reduce_tax_id]['customer_address'] = $value['customer_address'];
                    $rooms[$reduce_tax_id]['traveller_address'] = '';
                    $rooms[$reduce_tax_id]['traveller_name'] = $value['traveller_name'];
                    $rooms[$reduce_tax_id]['note'] = '';                    
                }
                $l++;            
            }
            // Thanh toán hóa đơn
            // lấy ra thanh toán nhóm
            $payments_group = DB::fetch_all('
                            SELECT
                                payment.id,
                                payment.payment_type_id,
                                payment.time as certificate_date,
                                reservation.id as r_id,
                                reservation_room.id as rr_id,
                                payment.amount,
                                payment.type_dps,
                                customer.name as customer_code,
                                customer.def_name as customer_name,
                                customer.mobile as customer_phone,
                                customer.address as customer_address,
                                customer.tax_code as customer_tax_code,
                                traveller.first_name || \' \' || traveller.last_name as traveller_name,
                                traveller.address as traveller_address,
                                folio.code as folio_code,
                                room.name as room_name
                            FROM
                                payment
                                inner join folio on folio.id = payment.folio_id
                                inner join reservation on reservation.id = payment.reservation_id
                                inner join reservation_room on reservation_room.reservation_id = reservation.id
                                inner join room on room.id = reservation_room.room_id
                                left join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
                                left join traveller on traveller.id = reservation_traveller.traveller_id
                                left join customer on reservation.customer_id = customer.id
                            WHERE
                                payment.time >= \''.Date_Time::to_time($export_date).'\'
                                and payment.time < \''.(Date_Time::to_time($export_date)+86399).'\'
                                and payment.type = \'RESERVATION\'
                                and payment.reservation_room_id is null
                                and payment.type_dps is null
                                and payment.payment_type_id != \'REFUND\' 
                            ORDER BY
                                payment.id ASC
            ');
            // lấy ra thanh toán phòng
            $payments_room = DB::fetch_all('
                            SELECT
                                payment.id,
                                payment.payment_type_id,
                                payment.time as certificate_date,
                                reservation.id as r_id,
                                reservation_room.id as rr_id,
                                payment.amount,
                                payment.type_dps,
                                customer.name as customer_code,
                                customer.def_name as customer_name,
                                customer.mobile as customer_phone,
                                customer.address as customer_address,
                                customer.tax_code as customer_tax_code,
                                traveller.first_name || \' \' || traveller.last_name as traveller_name,
                                traveller.address as traveller_address,
                                folio.code as folio_code,
                                room.name as room_name
                            FROM
                                payment
                                inner join folio on folio.id = payment.folio_id
                                inner join reservation_room on reservation_room.id = payment.reservation_room_id
                                inner join reservation on reservation_room.reservation_id = reservation.id
                                inner join room on room.id = reservation_room.room_id
                                left join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
                                left join traveller on traveller.id = reservation_traveller.traveller_id
                                left join customer on reservation.customer_id = customer.id
                            WHERE
                                payment.time >= \''.Date_Time::to_time($export_date).'\'
                                and payment.time < \''.(Date_Time::to_time($export_date)+86399).'\'
                                and payment.type = \'RESERVATION\'
                                and payment.reservation_room_id is not null
                                and payment.type_dps is null
                                and payment.payment_type_id != \'REFUND\' 
                            ORDER BY
                                payment.id ASC
            ');
            $payments_arr = $payments_group + $payments_room;
            foreach($payments_arr as $key => $value)
            {
                $payments_id = $value['id'].'_'.$value['r_id'].'_'.$value['rr_id'];
                if(!isset($rooms[$payments_id]['id']))
                {
                    $rooms[$payments_id]['id'] = $payments_id;
                    if($value['payment_type_id'] == 'CASH')
                    {
                        $rooms[$payments_id]['certificate'] = 'PT'; 
                        $rooms[$payments_id]['certificate_number'] = 'PT'.date('ymd', $value['certificate_date']).str_pad($m, 4,"0",STR_PAD_LEFT);
                        $rooms[$payments_id]['description'] = 'Thu tiền mặt hóa đơn số No.F' .str_pad($value['folio_code'], 6,"0",STR_PAD_LEFT).' của khách hàng '.$value['traveller_name'].', phòng ' . $value['room_name'] . ' Recode ' . $value['r_id'];
                        $rooms[$payments_id]['account_no'] = 1111;   
                    }else if($value['payment_type_id'] == 'CREDIT_CARD' or $value['payment_type_id'] == 'BANK')
                    {
                        $rooms[$payments_id]['certificate'] = 'GBC';  
                        $rooms[$payments_id]['certificate_number'] = 'GBC'.date('ymd', $value['certificate_date']).str_pad($m, 4,"0",STR_PAD_LEFT);
                        $rooms[$payments_id]['description'] = 'Thanh toán thẻ cho hóa đơn số No.F' .str_pad($value['folio_code'], 6,"0",STR_PAD_LEFT).' của khách hàng '.$value['traveller_name'].', phòng ' . $value['room_name'] . ' Recode ' . $value['r_id'];
                        $rooms[$payments_id]['account_no'] = 1121;                      
                    }else if($value['payment_type_id'] == 'FOC')
                    {
                        $rooms[$payments_id]['certificate'] = 'PKT';
                        $rooms[$payments_id]['certificate_number'] = 'PKT'.date('ymd', $value['certificate_date']).str_pad($m, 4,"0",STR_PAD_LEFT);
                        $rooms[$payments_id]['description'] = 'Miễn phí cho hóa đơn số No.F' .str_pad($value['folio_code'], 6,"0",STR_PAD_LEFT).' của khách hàng '.$value['traveller_name'].', phòng ' . $value['room_name'] . ' Recode ' . $value['r_id'];
                        $rooms[$payments_id]['account_no'] = 1388;
                    }else if($value['payment_type_id'] == 'DEBIT')
                    {
                        $rooms[$payments_id]['certificate'] = 'PTN';
                        $rooms[$payments_id]['certificate_number'] = 'PTN'.date('ymd', $value['certificate_date']).str_pad($m, 4,"0",STR_PAD_LEFT);
                        $rooms[$payments_id]['description'] = 'Công nợ cho hóa đơn số No.F' .str_pad($value['folio_code'], 6,"0",STR_PAD_LEFT).' của khách hàng '.$value['traveller_name'].', phòng ' . $value['room_name'] . ' Recode ' . $value['r_id'];
                        $rooms[$payments_id]['account_no'] = 131;                        
                    }
                    $rooms[$payments_id]['certificate_date'] = date('d/m/Y', $value['certificate_date']);
                    $rooms[$payments_id]['invoice_symbol'] = '';
                    $rooms[$payments_id]['invoice_number'] = '';
                    $rooms[$payments_id]['invoice_date'] = '';
                    $rooms[$payments_id]['customer_no'] = '';
                    $rooms[$payments_id]['product_import'] = '';
                    $rooms[$payments_id]['account_co'] = 132;
                    $rooms[$payments_id]['customer_co'] = $value['customer_code'];
                    $rooms[$payments_id]['list_code'] = '';
                    $rooms[$payments_id]['list_name'] = '';
                    $rooms[$payments_id]['unit'] = '';
                    $rooms[$payments_id]['quantity'] = '';
                    $rooms[$payments_id]['price_room_before_tax'] = '';
                    $rooms[$payments_id]['total_before_tax'] = $value['amount'];
                    $rooms[$payments_id]['pt_ck'] = '';
                    $rooms[$payments_id]['ck'] = '';
                    $rooms[$payments_id]['invoice_vat'] = '';
                    $rooms[$payments_id]['account_tax'] = '';
                    $rooms[$payments_id]['ts_gtgt'] = '';
                    $rooms[$payments_id]['tax_vn'] = '';
                    $rooms[$payments_id]['total_vnd_tt'] = '';
                    $rooms[$payments_id]['customer_code'] = $value['customer_code'];
                    $rooms[$payments_id]['customer_name'] = $value['customer_name'];
                    $rooms[$payments_id]['customer_tax_code'] = $value['customer_tax_code'];
                    $rooms[$payments_id]['customer_address'] = $value['customer_address'];
                    $rooms[$payments_id]['traveller_address'] = $value['traveller_address'];
                    $rooms[$payments_id]['traveller_name'] = $value['traveller_name'] . ' phòng số '. $value['room_name'] . ' Recode ' . $value['r_id'];
                    $rooms[$payments_id]['note'] = '';                    
                }
                $m++; 
            }
            // Chuyển dữ liệu dịch vụ mở rộng phát sinh
            $extra_service = DB::fetch_all('
                                 SELECT
                                    extra_service_invoice_table.id,
                                    extra_service_invoice_detail.invoice_id,
                                    extra_service_invoice_detail.price,
                                    TO_CHAR(extra_service_invoice_detail.in_date,\'DD/MM/YYYY\') as certificate_date,
                                    extra_service_invoice_detail.quantity,
                                    NVL(extra_service_invoice_detail.change_quantity,0) as change_quantity,
                                    extra_service_invoice.net_price,
                                    NVL(extra_service_invoice.use_extra_bed,0) as use_extra_bed,
                                    NVL(extra_service_invoice.use_baby_cot,0) as use_baby_cot,
                                    extra_service.id as extra_service_id,
                                    extra_service.code as extra_service_code,
                                    extra_service.name as extra_service_name,
                                    extra_service.unit,
                                    extra_service_invoice.tax_rate,
                                    extra_service_invoice.service_rate,
                                    reservation.id as r_id,
                                    reservation_room.id as rr_id,
                                    customer.name as customer_code,
                                    customer.def_name as customer_name,
                                    customer.mobile as customer_phone,
                                    customer.address as customer_address,
                                    customer.tax_code as customer_tax_code,
                                    traveller.first_name || \' \' || traveller.last_name as traveller_name,
                                    traveller.address as traveller_address,
                                    room.name as room_name
                                 FROM
                                    extra_service_invoice_table
                                    inner join extra_service_invoice_detail on extra_service_invoice_detail.table_id = extra_service_invoice_table.id
                                    inner join extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id
                                    inner join extra_service on extra_service.id = extra_service_invoice_detail.service_id
                                    inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                                    inner join reservation on reservation.id = reservation_room.reservation_id
                                    inner join room on room.id = reservation_room.room_id
                                    left join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
                                    left join traveller on traveller.id = reservation_traveller.traveller_id
                                    left join customer on reservation.customer_id = customer.id
                                 WHERE
                                    extra_service_invoice_detail.in_date = \''.Date_Time::convert_time_to_ora_date(Date_Time::to_time($export_date)).'\'
                                    and reservation_room.status <>\'CANCEL\'
                                    and reservation_room.change_room_to_rr is null
                                 ORDER BY
                                    extra_service_invoice_detail.invoice_id ASC,
                                    extra_service_invoice_detail.in_date ASC                            
                                    
            ');
            //System::debug($extra_service);exit();
            foreach($extra_service as $key => $value)
            {
                $extra_service_id = $value['r_id'] .'_'. $value['room_name'].'_'.$value['extra_service_code'];
                $tax_extra_service_id = $value['r_id'] .'_'. $value['room_name'].'_'.$value['extra_service_code'].'_'.$value['tax_rate'];
                $quantity = $value['quantity'] + $value['change_quantity'];
                // xây dựng mảng doanh thu dvmr
                if(!isset($rooms[$extra_service_id]['id']))
                {
                    $rooms[$extra_service_id]['id'] = $extra_service_id;
                    $rooms[$extra_service_id]['certificate'] = 'DVK';
                    $rooms[$extra_service_id]['invoice_symbol'] = '';
                    $rooms[$extra_service_id]['invoice_number'] = '';
                    $rooms[$extra_service_id]['invoice_date'] = '';
                    $rooms[$extra_service_id]['certificate_number'] = 'DVK'.date('ymd', Date_Time::to_time($value['certificate_date'])).str_pad($n, 4,"0",STR_PAD_LEFT);
                    $rooms[$extra_service_id]['certificate_date'] = $value['certificate_date'];
                    $rooms[$extra_service_id]['description'] = 'Doanh thu dịch vụ khác ngày ('.$value['certificate_date'].') recode '.$value['r_id'].' ('.$value['customer_code'].')';
                    $rooms[$extra_service_id]['account_no'] = 132;
                    $rooms[$extra_service_id]['customer_no'] = '';
                    $rooms[$extra_service_id]['product_import'] = '';
                    $rooms[$extra_service_id]['account_co'] = 5113;
                    $rooms[$extra_service_id]['customer_co'] = '';
                    $rooms[$extra_service_id]['list_code'] = $value['extra_service_code'];
                    $rooms[$extra_service_id]['list_name'] = $value['extra_service_name'];
                    $rooms[$extra_service_id]['unit'] = $value['unit'];
                    $rooms[$extra_service_id]['quantity'] = $value['quantity'] + $value['change_quantity'];
                    if($value['net_price'] == 0)
                    {
                        $rooms[$extra_service_id]['price_room_before_tax'] = ($value['price']*(1+($value['service_rate'] / 100)));
                        $rooms[$extra_service_id]['total_before_tax'] = ($value['price']*(1+($value['service_rate'] / 100)))*$quantity; 
                        $rooms[$extra_service_id]['tax_vn'] = ($value['price']*(1+($value['service_rate'] / 100)))*$quantity*($value['tax_rate'] / 100);  
                        $rooms[$extra_service_id]['total_vnd_tt'] = ($value['price']*(1+($value['service_rate'] / 100)))*$quantity + ($value['price']*(1+($value['service_rate'] / 100)))*$quantity*($value['tax_rate'] / 100);                   
                    }else
                    {
                        $rooms[$extra_service_id]['price_room_before_tax'] = ($value['price']/(1+($value['tax_rate'] / 100)));
                        $rooms[$extra_service_id]['total_before_tax'] = ($value['price']/(1+($value['tax_rate'] / 100)))*$quantity; 
                        $rooms[$extra_service_id]['tax_vn'] = ($value['price']/(1+($value['tax_rate'] / 100)))*$quantity*($value['tax_rate'] / 100); 
                        $rooms[$extra_service_id]['total_vnd_tt'] = ($value['price']/(1+($value['tax_rate'] / 100)))*$quantity + ($value['price']/(1+($value['tax_rate'] / 100)))*$quantity*($value['tax_rate'] / 100);                   
                    }
                    $rooms[$extra_service_id]['pt_ck'] = '';
                    $rooms[$extra_service_id]['ck'] = '';
                    $rooms[$extra_service_id]['invoice_vat'] = '';
                    $rooms[$extra_service_id]['account_tax'] = 33311;
                    $rooms[$extra_service_id]['ts_gtgt'] = $value['tax_rate'];
                    $rooms[$extra_service_id]['customer_code'] = $value['customer_code'];
                    $rooms[$extra_service_id]['customer_name'] = $value['customer_name'];
                    $rooms[$extra_service_id]['customer_tax_code'] = $value['customer_tax_code'];
                    $rooms[$extra_service_id]['customer_address'] = $value['customer_address'];
                    $rooms[$extra_service_id]['traveller_address'] = $value['traveller_address'];
                    $rooms[$extra_service_id]['traveller_name'] = $value['traveller_name'];
                    $rooms[$extra_service_id]['note'] = '';
                }
                
                //Xây dựng mảng tiền thuế
                if(!isset($rooms[$tax_extra_service_id]['id']))
                {
                    $rooms[$tax_extra_service_id]['id'] = $tax_extra_service_id;
                    $rooms[$tax_extra_service_id]['certificate'] = 'DVK';
                    $rooms[$tax_extra_service_id]['invoice_symbol'] = '';
                    $rooms[$tax_extra_service_id]['invoice_number'] = '';
                    $rooms[$tax_extra_service_id]['invoice_date'] = '';
                    $rooms[$tax_extra_service_id]['certificate_number'] = 'DVK'.date('ymd', Date_Time::to_time($value['certificate_date'])).str_pad($n, 4,"0",STR_PAD_LEFT);
                    $rooms[$tax_extra_service_id]['certificate_date'] = $value['certificate_date'];
                    $rooms[$tax_extra_service_id]['description'] = 'Thuế GTGT dịch vụ khác ngày (' .$value['certificate_date']. ') recode ' .$value['r_id'].' ('.$value['customer_code'].')';
                    $rooms[$tax_extra_service_id]['account_no'] = 132;
                    $rooms[$tax_extra_service_id]['customer_no'] = '';
                    $rooms[$tax_extra_service_id]['product_import'] = '';
                    $rooms[$tax_extra_service_id]['account_co'] = 33311;
                    $rooms[$tax_extra_service_id]['customer_co'] = '';
                    $rooms[$tax_extra_service_id]['list_code'] = '';
                    $rooms[$tax_extra_service_id]['list_name'] = '';
                    $rooms[$tax_extra_service_id]['unit'] = '';
                    $rooms[$tax_extra_service_id]['quantity'] = '';
                    $rooms[$tax_extra_service_id]['price_room_before_tax'] = '';
                    if($value['net_price'] == 0)
                    {
                        $rooms[$tax_extra_service_id]['total_before_tax'] = ($value['price']*(1+($value['service_rate'] / 100)))*$quantity*($value['tax_rate'] / 100);
                        $rooms[$tax_extra_service_id]['total_vnd_tt'] = ($value['price']*(1+($value['service_rate'] / 100)))*$quantity*($value['tax_rate'] / 100);                      
                    }else
                    {
                        $rooms[$tax_extra_service_id]['total_before_tax'] = ($value['price']/(1+($value['tax_rate'] / 100)))*$quantity*($value['tax_rate'] / 100);
                        $rooms[$tax_extra_service_id]['total_vnd_tt'] = ($value['price']/(1+($value['tax_rate'] / 100)))*$quantity*($value['tax_rate'] / 100);                    
                    }
                    $rooms[$tax_extra_service_id]['pt_ck'] = '';
                    $rooms[$tax_extra_service_id]['ck'] = '';
                    $rooms[$tax_extra_service_id]['invoice_vat'] = '';
                    $rooms[$tax_extra_service_id]['account_tax'] = '';
                    $rooms[$tax_extra_service_id]['ts_gtgt'] = '';
                    $rooms[$tax_extra_service_id]['tax_vn'] = '';
                    $rooms[$tax_extra_service_id]['customer_code'] = $value['customer_code'];
                    $rooms[$tax_extra_service_id]['customer_name'] = $value['customer_name'];
                    $rooms[$tax_extra_service_id]['customer_tax_code'] = $value['customer_tax_code'];
                    $rooms[$tax_extra_service_id]['customer_address'] = $value['customer_address'];
                    $rooms[$tax_extra_service_id]['traveller_address'] = $value['traveller_address'];
                    $rooms[$tax_extra_service_id]['traveller_name'] = $value['traveller_name'];
                    $rooms[$tax_extra_service_id]['note'] = '';
                }
                if($quantity == 0)
                {
                    unset($rooms[$extra_service_id]);
                    unset($rooms[$tax_extra_service_id]);
                }
                $n++;
            }
            
            // lấy ra phát sinh dịch vụ Minibar, giặt là, đền bù
            $hk_arr = DB::fetch_all('
                                        SELECT
                                            housekeeping_invoice_detail.id,
                                            housekeeping_invoice_detail.product_id,
                                            product.name_'.Portal::language().' as product_name,
                                            unit.name_'.Portal::language().' as unit_name,
                                            housekeeping_invoice_detail.price,
                                            CASE
                                                WHEN housekeeping_invoice.type = \'LAUNDRY\'
                                                THEN housekeeping_invoice_detail.quantity - nvl(housekeeping_invoice_detail.promotion,0)
                                                ELSE housekeeping_invoice_detail.quantity
                                            END as quantity,
                                            housekeeping_invoice.id as invoice_id,
                                            housekeeping_invoice.position,
                                            housekeeping_invoice.time as certificate_date,
                                            housekeeping_invoice.type,
                                            housekeeping_invoice.net_price,
                                            housekeeping_invoice.tax_rate,
                                            housekeeping_invoice.fee_rate as service_rate,
                                            reservation.id as r_id,
                                            reservation_room.id as rr_id,
                                            customer.name as customer_code,
                                            customer.def_name as customer_name,
                                            customer.mobile as customer_phone,
                                            customer.address as customer_address,
                                            customer.tax_code as customer_tax_code,
                                            traveller.first_name || \' \' || traveller.last_name as traveller_name,
                                            room.name as room_name
                                        FROM
                                            housekeeping_invoice_detail
                                            inner join housekeeping_invoice on housekeeping_invoice_detail.invoice_id = housekeeping_invoice.id
                                            inner join product on housekeeping_invoice_detail.product_id = product.id
                                            inner join unit on product.unit_id = unit.id
                                            inner join reservation_room on housekeeping_invoice.reservation_room_id = reservation_room.id
                                            inner join reservation on reservation_room.reservation_id = reservation.id
                                            inner join customer on reservation.customer_id = customer.id
                                            inner join room on reservation_room.room_id = room.id
                                            left join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                                            left join traveller on traveller.id = reservation_traveller.traveller_id
                                        WHERE
                                            housekeeping_invoice.time >= \''.(Date_Time::to_time($export_date)).'\'
                                            and housekeeping_invoice.time <= \''.(Date_Time::to_time($export_date)+86399).'\'
                                            and reservation_room.status <>\'CANCEL\'
                                            and reservation_room.change_room_to_rr is null
                                        ORDER BY
                                            housekeeping_invoice_detail.invoice_id, housekeeping_invoice_detail.id ASC        
            ');
            foreach($hk_arr as $key => $value)
            {
                $hk_id = $value['type'].'_'. $value['r_id'].'_'. $value['rr_id'] .'_'. $value['room_name'].'_'.$value['invoice_id'].'_'.$value['id'];
                $hk_tax_id = $value['type'].'_'. $value['r_id'].'_'. $value['rr_id'] .'_'. $value['room_name'].'_'.$value['invoice_id'].'_'.$value['id'].'_'.$value['tax_rate'];
                // xây dựng mảng doanh thu hk
                if(!isset($rooms[$hk_id]['id']))
                {
                    $rooms[$hk_id]['id'] = $hk_id;
                    $rooms[$hk_id]['certificate'] = 'BP';
                    $rooms[$hk_id]['invoice_symbol'] = '';
                    $rooms[$hk_id]['invoice_number'] = '';
                    $rooms[$hk_id]['invoice_date'] = '';
                    $rooms[$hk_id]['certificate_number'] = 'BP'.date('ymd', $value['certificate_date']).str_pad($q, 4,"0",STR_PAD_LEFT);
                    $rooms[$hk_id]['certificate_date'] = date('d/m/Y', $value['certificate_date']);
                    if($value['type'] == 'LAUNDRY')
                    {
                        $rooms[$hk_id]['description'] = 'Doanh thu Giặt là #LD_'.$value['position'].' ngày ('. date('d/m/Y', $value['certificate_date']) .') recode '. $value['r_id'] .' '.$value['customer_code'];
                        
                        $rooms[$hk_id]['account_co'] = 5113;
                        if($value['net_price'] > 0)
                        {
                            $rooms[$hk_id]['price_room_before_tax'] = $value['price']/(1+($value['tax_rate']/100));
                            $rooms[$hk_id]['total_before_tax'] = ($value['price']*$value['quantity'])/(1+($value['tax_rate'] / 100));
                            $rooms[$hk_id]['tax_vn'] = ((($value['price']*$value['quantity'])/(1+($value['tax_rate']/100)))*($value['tax_rate'] / 100));
                            $rooms[$hk_id]['total_vnd_tt'] = ($value['price']*$value['quantity']);                    
                        }else
                        {
                            $rooms[$hk_id]['price_room_before_tax'] = $value['price']*(1+($value['service_rate']/100));
                            $rooms[$hk_id]['total_before_tax'] = ($value['price']*$value['quantity'])*(1+($value['service_rate']/100)); 
                            $rooms[$hk_id]['tax_vn'] = ((($value['price']*$value['quantity'])*(1+($value['service_rate']/100)))*($value['tax_rate'] / 100));
                            $rooms[$hk_id]['total_vnd_tt'] = ($value['price']*(1+($value['tax_rate']/100))*(1+($value['service_rate']/100))*$value['quantity']);                   
                        }                        
                    }
                    if($value['type'] == 'MINIBAR')
                    {
                        $rooms[$hk_id]['description'] = 'Doanh thu Minibar #MN_'.$value['position'].' ngày ('. date('d/m/Y', $value['certificate_date']) .') recode '. $value['r_id'] .' '.$value['customer_code'];
                        
                        $rooms[$hk_id]['account_co'] = 5111;
                        if($value['net_price'] > 0)
                        {
                            $rooms[$hk_id]['price_room_before_tax'] = $value['price']/(1+($value['tax_rate']/100));
                            $rooms[$hk_id]['total_before_tax'] = ($value['price']*$value['quantity'])/(1+($value['tax_rate'] / 100));
                            $rooms[$hk_id]['tax_vn'] = ((($value['price']*$value['quantity'])/(1+($value['tax_rate']/100)))*($value['tax_rate'] / 100));
                            $rooms[$hk_id]['total_vnd_tt'] = ($value['price']*$value['quantity']);                    
                        }else
                        {
                            $rooms[$hk_id]['price_room_before_tax'] = $value['price']*(1+($value['service_rate']/100));
                            $rooms[$hk_id]['total_before_tax'] = ($value['price']*$value['quantity'])*(1+($value['service_rate']/100));
                            $rooms[$hk_id]['tax_vn'] = ((($value['price']*$value['quantity'])*(1+($value['service_rate']/100)))*($value['tax_rate'] / 100));
                            $rooms[$hk_id]['total_vnd_tt'] = ($value['price']*(1+($value['tax_rate']/100))*(1+($value['service_rate']/100))*$value['quantity']);                   
                        }                        
                    }
                    if($value['type'] == 'EQUIP')
                    {
                        $rooms[$hk_id]['description'] = 'Doanh thu Đền bù #_'.$value['invoice_id'].' ngày ('. date('d/m/Y', $value['certificate_date']) .') recode '. $value['r_id'] .' '.$value['customer_code'];
                        
                        $rooms[$hk_id]['account_co'] = 1381;
                        $rooms[$hk_id]['price_room_before_tax'] = $value['price'];
                        $rooms[$hk_id]['total_before_tax'] = $value['price']*$value['quantity'];
                        $rooms[$hk_id]['tax_vn'] = ($value['price']*$value['quantity'])*($value['tax_rate'] / 100);
                        $rooms[$hk_id]['total_vnd_tt'] = ($value['price']*(1+($value['tax_rate']/100))*$value['quantity']);                       
                    }
                    $rooms[$hk_id]['account_no'] = 132;
                    $rooms[$hk_id]['customer_no'] = $value['customer_code'];
                    $rooms[$hk_id]['product_import'] = '';
                    $rooms[$hk_id]['customer_co'] = '';
                    $rooms[$hk_id]['list_code'] = $value['product_id'];
                    $rooms[$hk_id]['list_name'] = $value['product_name'];
                    $rooms[$hk_id]['unit'] = $value['unit_name'];
                    $rooms[$hk_id]['quantity'] = $value['quantity'];
                    $rooms[$hk_id]['pt_ck'] = '';
                    $rooms[$hk_id]['ck'] = '';
                    $rooms[$hk_id]['invoice_vat'] = '';
                    $rooms[$hk_id]['account_tax'] = 33311;
                    $rooms[$hk_id]['ts_gtgt'] = $value['tax_rate'];
                    $rooms[$hk_id]['customer_code'] = $value['customer_code'];
                    $rooms[$hk_id]['customer_name'] = $value['customer_name'];
                    $rooms[$hk_id]['customer_tax_code'] = $value['customer_tax_code'];
                    $rooms[$hk_id]['customer_address'] = $value['customer_address'];
                    $rooms[$hk_id]['traveller_address'] = '';
                    $rooms[$hk_id]['traveller_name'] = $value['traveller_name'];
                    $rooms[$hk_id]['note'] = '';
                }
                
                //Xây dựng mảng tiền thuế
                if(!isset($rooms[$hk_tax_id]['id']))
                {
                    $rooms[$hk_tax_id]['id'] = $hk_tax_id;
                    $rooms[$hk_tax_id]['certificate'] = 'BP';
                    $rooms[$hk_tax_id]['invoice_symbol'] = '';
                    $rooms[$hk_tax_id]['invoice_number'] = '';
                    $rooms[$hk_tax_id]['invoice_date'] = '';
                    $rooms[$hk_tax_id]['certificate_number'] = 'BP'.date('ymd', $value['certificate_date']).str_pad($q, 4,"0",STR_PAD_LEFT);
                    $rooms[$hk_tax_id]['certificate_date'] = date('d/m/Y', $value['certificate_date']);
                    if($value['type'] == 'LAUNDRY')
                    {
                        $rooms[$hk_tax_id]['description'] = 'Thuế GTGT của Giặt là #LD_'.$value['position'].' ngày ('. date('d/m/Y', $value['certificate_date']) .') recode '. $value['r_id'] .' '.$value['customer_code'];
                        if($value['net_price'] > 0)
                        {
                            $rooms[$hk_tax_id]['total_before_tax'] = ((($value['price']*$value['quantity'])/(1+($value['tax_rate']/100)))*($value['tax_rate'] / 100));
                            $rooms[$hk_tax_id]['total_vnd_tt'] = ((($value['price']*$value['quantity'])/(1+($value['tax_rate']/100)))*($value['tax_rate'] / 100));                    
                        }else
                        { 
                            $rooms[$hk_tax_id]['total_before_tax'] = ((($value['price']*$value['quantity'])*(1+($value['service_rate']/100)))*($value['tax_rate'] / 100));
                            $rooms[$hk_tax_id]['total_vnd_tt'] = ($value['price']*(1+($value['tax_rate']/100))*(1+($value['service_rate']/100))*$value['quantity']);                   
                        }                        
                    }
                    if($value['type'] == 'MINIBAR')
                    {
                        $rooms[$hk_tax_id]['description'] = 'Thuế GTGT của Minibar #MN_'.$value['position'].' ngày ('. date('d/m/Y', $value['certificate_date']) .') recode '. $value['r_id'] .' '.$value['customer_code'];
                        if($value['net_price'] > 0)
                        {
                            $rooms[$hk_tax_id]['total_before_tax'] = ((($value['price']*$value['quantity'])/(1+($value['tax_rate']/100)))*($value['tax_rate'] / 100));
                            $rooms[$hk_tax_id]['total_vnd_tt'] = ((($value['price']*$value['quantity'])/(1+($value['tax_rate']/100)))*($value['tax_rate'] / 100));                   
                        }else
                        { 
                            $rooms[$hk_tax_id]['total_before_tax'] = ((($value['price']*$value['quantity'])*(1+($value['service_rate']/100)))*($value['tax_rate'] / 100));
                            $rooms[$hk_tax_id]['total_vnd_tt'] = ((($value['price']*$value['quantity'])*(1+($value['service_rate']/100)))*($value['tax_rate'] / 100));                   
                        }                          
                    }
                    if($value['type'] == 'EQUIP')
                    {
                        $rooms[$hk_tax_id]['description'] = 'Thuế GTGT của Đền bù #_'.$value['invoice_id'].' ngày ('. date('d/m/Y', $value['certificate_date']) .') recode '. $value['r_id'] .' '.$value['customer_code'];
                        if($value['tax_rate'] != 0)
                        {
                            $rooms[$hk_tax_id]['total_before_tax'] = ($value['price']*$value['quantity'])*($value['tax_rate'] / 100);
                            $rooms[$hk_tax_id]['total_vnd_tt'] = ($value['price']*$value['quantity'])*($value['tax_rate'] / 100);                        
                        }else
                        {
                            $rooms[$hk_tax_id]['total_before_tax'] = $value['price']*$value['quantity'];
                            $rooms[$hk_tax_id]['total_vnd_tt'] = $value['price']*$value['quantity'];
                        }                        
                    }
                    $rooms[$hk_tax_id]['account_no'] = 132;
                    $rooms[$hk_tax_id]['customer_no'] = $value['customer_code'];
                    $rooms[$hk_tax_id]['product_import'] = '';
                    $rooms[$hk_tax_id]['account_co'] = 33311;
                    $rooms[$hk_tax_id]['customer_co'] = '';
                    $rooms[$hk_tax_id]['list_code'] = '';
                    $rooms[$hk_tax_id]['list_name'] = '';
                    $rooms[$hk_tax_id]['unit'] = '';
                    $rooms[$hk_tax_id]['quantity'] = '';
                    $rooms[$hk_tax_id]['price_room_before_tax'] = '';
                    $rooms[$hk_tax_id]['pt_ck'] = '';
                    $rooms[$hk_tax_id]['ck'] = '';
                    $rooms[$hk_tax_id]['invoice_vat'] = '';
                    $rooms[$hk_tax_id]['account_tax'] = '';
                    $rooms[$hk_tax_id]['ts_gtgt'] = '';
                    $rooms[$hk_tax_id]['tax_vn'] = '';
                    $rooms[$hk_tax_id]['customer_code'] = $value['customer_code'];
                    $rooms[$hk_tax_id]['customer_name'] = $value['customer_name'];
                    $rooms[$hk_tax_id]['customer_tax_code'] = $value['customer_tax_code'];
                    $rooms[$hk_tax_id]['customer_address'] = $value['customer_address'];
                    $rooms[$hk_tax_id]['traveller_address'] = '';
                    $rooms[$hk_tax_id]['traveller_name'] = $value['traveller_name'];
                    $rooms[$hk_tax_id]['note'] = '';
                }
                
                if(($value['quantity']) == 0)
                {
                    unset($rooms[$hk_id]);
                    unset($rooms[$hk_tax_id]);
                }
                $q++;
            }
            // lấy ra phí giặt nhanh dịch vụ giặt là
            $laundry_fast = DB::fetch_all('
                                SELECT
                                    housekeeping_invoice.id,
                                    CASE
                                        WHEN housekeeping_invoice.net_price = 0
                                        THEN sum((housekeeping_invoice_detail.price*(1+(housekeeping_invoice.fee_rate/100))*(housekeeping_invoice_detail.quantity - nvl(housekeeping_invoice_detail.promotion,0)))*(housekeeping_invoice.express_rate/100))
                                        ELSE sum((housekeeping_invoice_detail.price/(1+(housekeeping_invoice.tax_rate/100))*(housekeeping_invoice_detail.quantity - nvl(housekeeping_invoice_detail.promotion,0)))*(housekeeping_invoice.express_rate/100))
                                    END as price,
                                    housekeeping_invoice.position,
                                    housekeeping_invoice.time as certificate_date,
                                    housekeeping_invoice.type,
                                    housekeeping_invoice.net_price,
                                    housekeeping_invoice.tax_rate,
                                    housekeeping_invoice.fee_rate as service_rate,
                                    housekeeping_invoice.express_rate,
                                    reservation.id as r_id,
                                    reservation_room.id as rr_id,
                                    customer.name as customer_code,
                                    customer.def_name as customer_name,
                                    customer.mobile as customer_phone,
                                    customer.address as customer_address,
                                    customer.tax_code as customer_tax_code,
                                    traveller.first_name || \' \' || traveller.last_name as traveller_name,
                                    room.name as room_name
                                FROM
                                    housekeeping_invoice_detail
                                    inner join housekeeping_invoice on housekeeping_invoice_detail.invoice_id = housekeeping_invoice.id
                                    inner join product on housekeeping_invoice_detail.product_id = product.id
                                    inner join unit on product.unit_id = unit.id
                                    inner join reservation_room on housekeeping_invoice.reservation_room_id = reservation_room.id
                                    inner join reservation on reservation_room.reservation_id = reservation.id
                                    inner join customer on reservation.customer_id = customer.id
                                    inner join room on reservation_room.room_id = room.id
                                    left join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                                    left join traveller on traveller.id = reservation_traveller.traveller_id
                                WHERE
                                    housekeeping_invoice.time >= \''.(Date_Time::to_time($export_date)).'\'
                                    and housekeeping_invoice.time <= \''.(Date_Time::to_time($export_date)+86399).'\'
                                    and reservation_room.status <>\'CANCEL\'
                                    and reservation_room.change_room_to_rr is null
                                    and housekeeping_invoice.express_rate <> 0
                                    and housekeeping_invoice.type =\'LAUNDRY\'
                                GROUP BY
                                    housekeeping_invoice.id,housekeeping_invoice.position,housekeeping_invoice.time,housekeeping_invoice.type,housekeeping_invoice.net_price,housekeeping_invoice.tax_rate,housekeeping_invoice.fee_rate,housekeeping_invoice.express_rate,reservation.id,reservation_room.id,
                                    customer.name,customer.def_name,customer.mobile,customer.address,customer.tax_code,traveller.first_name,traveller.last_name,room.name
                                ORDER BY
                                    housekeeping_invoice.id,housekeeping_invoice.position,housekeeping_invoice.time,housekeeping_invoice.type,housekeeping_invoice.net_price,housekeeping_invoice.tax_rate,housekeeping_invoice.fee_rate,housekeeping_invoice.express_rate,reservation.id,reservation_room.id,
                                    customer.name,customer.def_name,customer.mobile,customer.address,customer.tax_code,traveller.first_name,traveller.last_name,room.name ASC
            ');
            $s = 0;
            foreach($laundry_fast as $key => $value)
            {
                $laundry_fast_id = $value['type'].'_'. $value['r_id'].'_'. $value['rr_id'] .'_'. $value['room_name'].'_'.$value['id'].'_'.'FAST';
                $laundry_fast_tax_id = $value['type'].'_'. $value['r_id'].'_'. $value['rr_id'] .'_'. $value['room_name'].'_'.$value['id'].'_'.'TAX_FAST';
                // xây dựng mảng doanh thu hk
                if(!isset($rooms[$laundry_fast_id]['id']))
                {
                    $rooms[$laundry_fast_id]['id'] = $laundry_fast_id;
                    $rooms[$laundry_fast_id]['certificate'] = 'BP';
                    $rooms[$laundry_fast_id]['invoice_symbol'] = '';
                    $rooms[$laundry_fast_id]['invoice_number'] = '';
                    $rooms[$laundry_fast_id]['invoice_date'] = '';
                    $rooms[$laundry_fast_id]['certificate_number'] = 'BP'.date('ymd', $value['certificate_date']).str_pad($q, 4,"0",STR_PAD_LEFT);
                    $rooms[$laundry_fast_id]['certificate_date'] = date('d/m/Y', $value['certificate_date']);
                    $rooms[$laundry_fast_id]['description'] = 'Doanh thu Phí giặt nhanh của Giặt là #LD_'.$value['position'].' ngày ('. date('d/m/Y', $value['certificate_date']) .') recode '. $value['r_id'] .' '.$value['customer_code'];
                    $rooms[$laundry_fast_id]['account_co'] = 5113;
                    $rooms[$laundry_fast_id]['price_room_before_tax'] = $value['price'];
                    $rooms[$laundry_fast_id]['total_before_tax'] = $value['price'];
                    $rooms[$laundry_fast_id]['tax_vn'] = $value['price']*($value['tax_rate'] / 100);
                    $rooms[$laundry_fast_id]['total_vnd_tt'] = $value['price']*(1+($value['tax_rate'] / 100));
                    $rooms[$laundry_fast_id]['account_no'] = 132;
                    $rooms[$laundry_fast_id]['customer_no'] = $value['customer_code'];
                    $rooms[$laundry_fast_id]['product_import'] = '';
                    $rooms[$laundry_fast_id]['customer_co'] = '';
                    $rooms[$laundry_fast_id]['list_code'] = 'PGN';
                    $rooms[$laundry_fast_id]['list_name'] = 'Phí giặt nhanh';
                    $rooms[$laundry_fast_id]['unit'] = '';
                    $rooms[$laundry_fast_id]['quantity'] = '';
                    $rooms[$laundry_fast_id]['pt_ck'] = '';
                    $rooms[$laundry_fast_id]['ck'] = '';
                    $rooms[$laundry_fast_id]['invoice_vat'] = '';
                    $rooms[$laundry_fast_id]['account_tax'] = 33311;
                    $rooms[$laundry_fast_id]['ts_gtgt'] = $value['tax_rate'];
                    $rooms[$laundry_fast_id]['customer_code'] = $value['customer_code'];
                    $rooms[$laundry_fast_id]['customer_name'] = $value['customer_name'];
                    $rooms[$laundry_fast_id]['customer_tax_code'] = $value['customer_tax_code'];
                    $rooms[$laundry_fast_id]['customer_address'] = $value['customer_address'];
                    $rooms[$laundry_fast_id]['traveller_address'] = '';
                    $rooms[$laundry_fast_id]['traveller_name'] = $value['traveller_name'];
                    $rooms[$laundry_fast_id]['note'] = '';
                }
                //Xây dựng mảng tiền thuế
                if(!isset($rooms[$laundry_fast_tax_id]['id']))
                {
                    $rooms[$laundry_fast_tax_id]['id'] = $laundry_fast_tax_id;
                    $rooms[$laundry_fast_tax_id]['certificate'] = 'BP';
                    $rooms[$laundry_fast_tax_id]['invoice_symbol'] = '';
                    $rooms[$laundry_fast_tax_id]['invoice_number'] = '';
                    $rooms[$laundry_fast_tax_id]['invoice_date'] = '';
                    $rooms[$laundry_fast_tax_id]['certificate_number'] = 'BP'.date('ymd', $value['certificate_date']).str_pad($q, 4,"0",STR_PAD_LEFT);
                    $rooms[$laundry_fast_tax_id]['certificate_date'] = date('d/m/Y', $value['certificate_date']);
                    $rooms[$laundry_fast_tax_id]['description'] = 'Thuế GTGT Phí giặt nhanh của Giặt là #LD_'.$value['position'].' ngày ('. date('d/m/Y', $value['certificate_date']) .') recode '. $value['r_id'] .' '.$value['customer_code'];
                    $rooms[$laundry_fast_tax_id]['total_before_tax'] = $value['price']*($value['tax_rate'] / 100);
                    $rooms[$laundry_fast_tax_id]['total_vnd_tt'] = $value['price']*($value['tax_rate'] / 100);
                    $rooms[$laundry_fast_tax_id]['account_no'] = 132;
                    $rooms[$laundry_fast_tax_id]['customer_no'] = $value['customer_code'];
                    $rooms[$laundry_fast_tax_id]['product_import'] = '';
                    $rooms[$laundry_fast_tax_id]['account_co'] = 33311;
                    $rooms[$laundry_fast_tax_id]['customer_co'] = '';
                    $rooms[$laundry_fast_tax_id]['list_code'] = '';
                    $rooms[$laundry_fast_tax_id]['list_name'] = '';
                    $rooms[$laundry_fast_tax_id]['unit'] = '';
                    $rooms[$laundry_fast_tax_id]['quantity'] = '';
                    $rooms[$laundry_fast_tax_id]['price_room_before_tax'] = '';
                    $rooms[$laundry_fast_tax_id]['pt_ck'] = '';
                    $rooms[$laundry_fast_tax_id]['ck'] = '';
                    $rooms[$laundry_fast_tax_id]['invoice_vat'] = '';
                    $rooms[$laundry_fast_tax_id]['account_tax'] = '';
                    $rooms[$laundry_fast_tax_id]['ts_gtgt'] = '';
                    $rooms[$laundry_fast_tax_id]['tax_vn'] = '';
                    $rooms[$laundry_fast_tax_id]['customer_code'] = $value['customer_code'];
                    $rooms[$laundry_fast_tax_id]['customer_name'] = $value['customer_name'];
                    $rooms[$laundry_fast_tax_id]['customer_tax_code'] = $value['customer_tax_code'];
                    $rooms[$laundry_fast_tax_id]['customer_address'] = $value['customer_address'];
                    $rooms[$laundry_fast_tax_id]['traveller_address'] = '';
                    $rooms[$laundry_fast_tax_id]['traveller_name'] = $value['traveller_name'];
                    $rooms[$laundry_fast_tax_id]['note'] = '';
                }           
            }
            // lấy ra giảm giá dịch vụ Minibar, giặt là, đền bù
            $hk_discount_arr = DB::fetch_all('
                                        SELECT
                                            housekeeping_invoice_detail.id,
                                            housekeeping_invoice_detail.product_id,
                                            product.name_'.Portal::language().' as product_name,
                                            unit.name_'.Portal::language().' as unit_name,
                                            housekeeping_invoice_detail.price,
                                            CASE
                                                WHEN housekeeping_invoice.type = \'LAUNDRY\'
                                                THEN housekeeping_invoice_detail.quantity - nvl(housekeeping_invoice_detail.promotion,0)
                                                ELSE housekeeping_invoice_detail.quantity
                                            END as quantity,
                                            housekeeping_invoice.id as invoice_id,
                                            housekeeping_invoice.position,
                                            housekeeping_invoice.time as certificate_date,
                                            housekeeping_invoice.type as hk_type,
                                            housekeeping_invoice.net_price,
                                            housekeeping_invoice.tax_rate,
                                            housekeeping_invoice.fee_rate as service_rate,
                                            housekeeping_invoice.discount,
                                            reservation.id as r_id,
                                            reservation_room.id as rr_id,
                                            customer.name as customer_code,
                                            customer.def_name as customer_name,
                                            customer.mobile as customer_phone,
                                            customer.address as customer_address,
                                            customer.tax_code as customer_tax_code,
                                            traveller.first_name || \' \' || traveller.last_name as traveller_name,
                                            room.name as room_name
                                        FROM
                                            housekeeping_invoice_detail
                                            inner join housekeeping_invoice on housekeeping_invoice_detail.invoice_id = housekeeping_invoice.id
                                            inner join product on housekeeping_invoice_detail.product_id = product.id
                                            inner join unit on product.unit_id = unit.id
                                            inner join reservation_room on housekeeping_invoice.reservation_room_id = reservation_room.id
                                            inner join reservation on reservation_room.reservation_id = reservation.id
                                            inner join customer on reservation.customer_id = customer.id
                                            inner join room on reservation_room.room_id = room.id
                                            left join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                                            left join traveller on traveller.id = reservation_traveller.traveller_id
                                        WHERE
                                            housekeeping_invoice.time >= \''.(Date_Time::to_time($export_date)).'\'
                                            and housekeeping_invoice.time <= \''.(Date_Time::to_time($export_date)+86399).'\'
                                            and reservation_room.status <>\'CANCEL\'
                                            and reservation_room.change_room_to_rr is null
                                            and housekeeping_invoice.discount > 0
                                        ORDER BY
                                            housekeeping_invoice_detail.invoice_id, housekeeping_invoice_detail.id ASC        
            ');
            foreach($hk_discount_arr as $key => $value)
            {
                $hk_dc_id = $value['hk_type'].'_'. $value['rr_id'].'_'.$value['room_name'].'_'.$value['invoice_id'].'_'.$value['id'].'_'.$value['discount'];
                $hk_dc_tax_id = $value['hk_type'].'_'. $value['rr_id'].'_'.$value['room_name'].'_'.$value['invoice_id'].'_'.$value['id'].'_'.$value['discount'].'_'.$value['tax_rate'];
                if(!isset($rooms[$hk_dc_id]['id']))
                {
                    $rooms[$hk_dc_id]['id'] = $hk_dc_id;
                    $rooms[$hk_dc_id]['certificate'] = 'PGG';
                    $rooms[$hk_dc_id]['invoice_symbol'] = '';
                    $rooms[$hk_dc_id]['invoice_number'] = '';
                    $rooms[$hk_dc_id]['invoice_date'] = '';
                    $rooms[$hk_dc_id]['certificate_number'] = 'PGG'.date('ymd', $value['certificate_date']).str_pad($w, 4,"0",STR_PAD_LEFT);
                    $rooms[$hk_dc_id]['certificate_date'] = date('d/m/Y', $value['certificate_date']);
                    if($value['hk_type'] == 'LAUNDRY')
                    {
                        $rooms[$hk_dc_id]['description'] = 'Giảm giá Giặt là #LD_'.$value['position'].' ngày ('. date('d/m/Y', $value['certificate_date']) .') recode '. $value['r_id'] .' '.$value['customer_code'];
                        if($value['net_price'] == 0)
                        {
                            $rooms[$hk_dc_id]['total_before_tax'] = (($value['price']*$value['quantity'])*($value['discount']/100)*(1+($value['service_rate'] / 100)));
                            $rooms[$hk_dc_id]['ck'] = (($value['price']*$value['quantity'])*($value['discount']/100)*(1+($value['service_rate'] / 100)));
                            $rooms[$hk_dc_id]['tax_vn'] = ((($value['price']*$value['quantity'])*($value['discount']/100)*(1+($value['service_rate'] / 100)))*($value['tax_rate'] / 100));
                            $rooms[$hk_dc_id]['total_vnd_tt'] = (($value['price']*$value['quantity'])*($value['discount']/100)*(1+($value['service_rate'] / 100)) + ($value['price']/$value['discount']*(1+($value['service_rate'] / 100)))*($value['tax_rate'] / 100));                        
                        }else
                        {
                            $rooms[$hk_dc_id]['total_before_tax'] = (($value['price']*$value['quantity'])*($value['discount']/100)/(1+($value['tax_rate'] / 100)));
                            $rooms[$hk_dc_id]['ck'] = (($value['price']*$value['quantity'])*($value['discount']/100)/(1+($value['tax_rate'] / 100)));
                            $rooms[$hk_dc_id]['tax_vn'] = ((($value['price']*$value['quantity'])*($value['discount']/100)/(1+($value['tax_rate'] / 100)))*($value['tax_rate'] / 100));
                            $rooms[$hk_dc_id]['total_vnd_tt'] = (($value['price']*$value['quantity'])*($value['discount']/100));                        
                        }
                        $rooms[$hk_dc_id]['pt_ck'] = $value['discount'];                     
                    }
                    if($value['hk_type'] == 'MINIBAR')
                    {
                        $rooms[$hk_dc_id]['description'] = 'Giảm giá Minibar #MN_'.$value['position'].' ngày ('. date('d/m/Y', $value['certificate_date']) .') recode '. $value['r_id'] .' '.$value['customer_code'];
                        if($value['net_price'] == 0)
                        {
                            $rooms[$hk_dc_id]['total_before_tax'] = (($value['price']*$value['quantity'])*($value['discount']/100)*(1+($value['service_rate'] / 100)));
                            $rooms[$hk_dc_id]['ck'] = (($value['price']*$value['quantity'])*($value['discount']/100)*(1+($value['service_rate'] / 100)));
                            $rooms[$hk_dc_id]['tax_vn'] = ((($value['price']*$value['quantity'])*($value['discount']/100)*(1+($value['service_rate'] / 100)))*($value['tax_rate'] / 100));
                            $rooms[$hk_dc_id]['total_vnd_tt'] = (($value['price']*$value['quantity'])*($value['discount']/100)*(1+($value['service_rate'] / 100)) + ($value['price']/$value['discount']*(1+($value['service_rate'] / 100)))*($value['tax_rate'] / 100));                        
                        }else
                        {
                            $rooms[$hk_dc_id]['total_before_tax'] = (($value['price']*$value['quantity'])*($value['discount']/100)/(1+($value['tax_rate'] / 100)));
                            $rooms[$hk_dc_id]['ck'] = (($value['price']*$value['quantity'])*($value['discount']/100)/(1+($value['tax_rate'] / 100)));
                            $rooms[$hk_dc_id]['tax_vn'] = ((($value['price']*$value['quantity'])*($value['discount']/100)/(1+($value['tax_rate'] / 100)))*($value['tax_rate'] / 100));
                            $rooms[$hk_dc_id]['total_vnd_tt'] = (($value['price']*$value['quantity'])*($value['discount']/100));                        
                        }
                        $rooms[$hk_dc_id]['pt_ck'] = $value['discount'];                        
                    }
                    if($value['hk_type'] == 'EQUIP')
                    {
                        $rooms[$hk_dc_id]['description'] = 'Giảm giá Đền bù #_'.$value['invoice_id'].' ngày ('. date('d/m/Y', $value['certificate_date']) .') recode '. $value['r_id'] .' '.$value['customer_code'];
                        $rooms[$hk_dc_id]['total_before_tax'] = '';
                        $rooms[$hk_dc_id]['ck'] = '';
                        $rooms[$hk_dc_id]['tax_vn'] = '';
                        $rooms[$hk_dc_id]['total_vnd_tt'] = '';
                        $rooms[$hk_dc_id]['pt_ck'] = '';                        
                    }
                    $rooms[$hk_dc_id]['account_no'] = 5213;
                    $rooms[$hk_dc_id]['customer_no'] = $value['customer_code'];
                    $rooms[$hk_dc_id]['product_import'] = '';
                    $rooms[$hk_dc_id]['account_co'] = 132;
                    $rooms[$hk_dc_id]['customer_co'] = $value['customer_code'];
                    $rooms[$hk_dc_id]['list_code'] = $value['product_id'];
                    $rooms[$hk_dc_id]['list_name'] = $value['product_name'];
                    $rooms[$hk_dc_id]['unit'] = $value['unit_name'];
                    $rooms[$hk_dc_id]['quantity'] = $value['quantity'];
                    $rooms[$hk_dc_id]['price_room_before_tax'] = '';
                    $rooms[$hk_dc_id]['invoice_vat'] = '';
                    $rooms[$hk_dc_id]['account_tax'] = 33311;
                    $rooms[$hk_dc_id]['ts_gtgt'] = $value['tax_rate'];
                    $rooms[$hk_dc_id]['customer_code'] = $value['customer_code'];
                    $rooms[$hk_dc_id]['customer_name'] = $value['customer_name'];
                    $rooms[$hk_dc_id]['customer_tax_code'] = $value['customer_tax_code'];
                    $rooms[$hk_dc_id]['customer_address'] = $value['customer_address'];
                    $rooms[$hk_dc_id]['traveller_address'] = '';
                    $rooms[$hk_dc_id]['traveller_name'] = $value['traveller_name'];
                    $rooms[$hk_dc_id]['note'] = '';  
                }
                //Xây dựng mảng tiền thuế cho giảm giá
                if(!isset($rooms[$hk_dc_tax_id]['id']))
                {
                    $rooms[$hk_dc_tax_id]['id'] = $hk_dc_tax_id;
                    $rooms[$hk_dc_tax_id]['certificate'] = 'PGG ';
                    $rooms[$hk_dc_tax_id]['invoice_symbol'] = '';
                    $rooms[$hk_dc_tax_id]['invoice_number'] = '';
                    $rooms[$hk_dc_tax_id]['invoice_date'] = '';
                    $rooms[$hk_dc_tax_id]['certificate_number'] = 'PGG'.date('ymd', $value['certificate_date']).str_pad($w, 4,"0",STR_PAD_LEFT);
                    $rooms[$hk_dc_tax_id]['certificate_date'] = date('d/m/Y', $value['certificate_date']);
                    if($value['hk_type'] == 'LAUNDRY')
                    {
                        $rooms[$hk_dc_tax_id]['description'] = 'Thuế GTGT của giảm giá Giặt là #LD_'.$value['position'].' ngày ('. date('d/m/Y', $value['certificate_date']) .') recode '. $value['r_id'] .' '.$value['customer_code'];
                        if($value['net_price'] == 0)
                        {
                            $rooms[$hk_dc_tax_id]['total_before_tax'] = ((($value['price']*$value['quantity'])*($value['discount']/100)*(1+($value['service_rate'] / 100)))*($value['tax_rate'] / 100));
                            $rooms[$hk_dc_tax_id]['total_vnd_tt'] = ((($value['price']*$value['quantity'])*($value['discount']/100)*(1+($value['service_rate'] / 100)))*($value['tax_rate'] / 100));                        
                        }else
                        {
                            $rooms[$hk_dc_tax_id]['total_before_tax'] = ((($value['price']*$value['quantity'])*($value['discount']/100)/(1+($value['tax_rate'] / 100)))*($value['tax_rate'] / 100));
                            $rooms[$hk_dc_tax_id]['total_vnd_tt'] = ((($value['price']*$value['quantity'])*($value['discount']/100)/(1+($value['tax_rate'] / 100)))*($value['tax_rate'] / 100));
                        }                        
                    }
                    if($value['hk_type'] == 'MINIBAR')
                    {
                        $rooms[$hk_dc_tax_id]['description'] = 'Thuế GTGT của giảm giá Minibar #MN_'.$value['position'].' ngày ('. date('d/m/Y', $value['certificate_date']) .') recode '. $value['r_id'] .' '.$value['customer_code'];
                        if($value['net_price'] == 0)
                        {
                            $rooms[$hk_dc_tax_id]['total_before_tax'] = ((($value['price']*$value['quantity'])*($value['discount']/100)*(1+($value['service_rate'] / 100)))*($value['tax_rate'] / 100));
                            $rooms[$hk_dc_tax_id]['total_vnd_tt'] = ((($value['price']*$value['quantity'])*($value['discount']/100)*(1+($value['service_rate'] / 100)))*($value['tax_rate'] / 100));                        
                        }else
                        {
                            $rooms[$hk_dc_tax_id]['total_before_tax'] = ((($value['price']*$value['quantity'])*($value['discount']/100)/(1+($value['tax_rate'] / 100)))*($value['tax_rate'] / 100));
                            $rooms[$hk_dc_tax_id]['total_vnd_tt'] = ((($value['price']*$value['quantity'])*($value['discount']/100)/(1+($value['tax_rate'] / 100)))*($value['tax_rate'] / 100));
                        }                          
                    }
                    if($value['hk_type'] == 'EQUIP')
                    {
                        $rooms[$hk_dc_tax_id]['description'] = 'Thuế GTGT của giảm giá Đền bù #_'.$value['invoice_id'].' ngày ('. date('d/m/Y', $value['certificate_date']) .') recode '. $value['r_id'] .' '.$value['customer_code']; 
                        $rooms[$hk_dc_tax_id]['total_before_tax'] = '';
                        $rooms[$hk_dc_tax_id]['total_vnd_tt'] = '';                       
                    }
                    $rooms[$hk_dc_tax_id]['account_no'] = 33311;
                    $rooms[$hk_dc_tax_id]['customer_no'] = $value['customer_code'];
                    $rooms[$hk_dc_tax_id]['product_import'] = '';
                    $rooms[$hk_dc_tax_id]['account_co'] = 132;
                    $rooms[$hk_dc_tax_id]['customer_co'] = $value['customer_code'];
                    $rooms[$hk_dc_tax_id]['list_code'] = '';
                    $rooms[$hk_dc_tax_id]['list_name'] = '';
                    $rooms[$hk_dc_tax_id]['unit'] = '';
                    $rooms[$hk_dc_tax_id]['quantity'] = '';
                    $rooms[$hk_dc_tax_id]['price_room_before_tax'] = '';
                    $rooms[$hk_dc_tax_id]['pt_ck'] = '';
                    $rooms[$hk_dc_tax_id]['ck'] = '';
                    $rooms[$hk_dc_tax_id]['invoice_vat'] = '';
                    $rooms[$hk_dc_tax_id]['account_tax'] = '';
                    $rooms[$hk_dc_tax_id]['ts_gtgt'] = '';
                    $rooms[$hk_dc_tax_id]['tax_vn'] = '';
                    $rooms[$hk_dc_tax_id]['customer_code'] = $value['customer_code'];
                    $rooms[$hk_dc_tax_id]['customer_name'] = $value['customer_name'];
                    $rooms[$hk_dc_tax_id]['customer_tax_code'] = $value['customer_tax_code'];
                    $rooms[$hk_dc_tax_id]['customer_address'] = $value['customer_address'];
                    $rooms[$hk_dc_tax_id]['traveller_address'] = '';
                    $rooms[$hk_dc_tax_id]['traveller_name'] = $value['traveller_name'];
                    $rooms[$hk_dc_tax_id]['note'] = ''; 
                }
                if(($value['quantity']) == 0)
                {
                    unset($rooms[$hk_dc_id]);
                    unset($rooms[$hk_dc_tax_id]);
                }
                $w++;
            }
            //Khách ở phòng sử dụng dịch vụ tại nhà hàng và thanh toán về phòng
            $bar_pay_with_room = DB::fetch_all('
                                        SELECT
                                            bar_reservation_product.id,
                                            bar_reservation_product.product_id,
                                            product.name_'.Portal::language().' as product_name,
                                            product.type as product_type,
                                            unit.name_'.Portal::language().' as unit_name,
                                            bar_reservation_product.price,
                                            CASE
                                                WHEN bar_reservation_product.quantity_discount = 0
                                                THEN bar_reservation_product.quantity
                                                ELSE bar_reservation_product.quantity - bar_reservation_product.quantity_discount
                                            END as quantity,
                                            bar_reservation_product.bar_id,
                                            bar.name as bar_name,
                                            bar_reservation_product.bar_reservation_id,
                                            bar_reservation.time as certificate_date,
                                            bar_reservation.tax_rate,
                                            bar_reservation.bar_fee_rate as service_rate,
                                            bar_reservation.full_rate,
                                            bar_reservation.full_charge,
                                            reservation.id as r_id,
                                            reservation_room.id as rr_id,
                                            customer.name as customer_code,
                                            customer.def_name as customer_name,
                                            customer.mobile as customer_phone,
                                            customer.address as customer_address,
                                            customer.tax_code as customer_tax_code,
                                            traveller.first_name || \' \' || traveller.last_name as traveller_name,
                                            room.name as room_name,
                                            warehouse.code as wh_code,
                                            warehouse.name as wh_name
                                        FROM
                                            bar_reservation_product
                                            inner join bar_reservation on bar_reservation_product.bar_reservation_id = bar_reservation.id
                                            inner join bar on bar_reservation.bar_id = bar.id
                                            inner join warehouse on bar.warehouse_id = warehouse.id
                                            inner join product on bar_reservation_product.product_id = product.id
                                            inner join unit on product.unit_id = unit.id
                                            inner join reservation_room on bar_reservation.reservation_room_id = reservation_room.id
                                            inner join reservation on reservation_room.reservation_id = reservation.id
                                            inner join customer on reservation.customer_id = customer.id
                                            inner join room on reservation_room.room_id = room.id
                                            left join reservation_traveller on bar_reservation.reservation_traveller_id = reservation_traveller.id
                                            left join traveller on traveller.id = reservation_traveller.traveller_id
                                        WHERE
                                            bar_reservation.time >= \''.(Date_Time::to_time($export_date)).'\'
                                            and bar_reservation.time <= \''.(Date_Time::to_time($export_date)+86399).'\'
                                            and reservation_room.status <>\'CANCEL\'
                                            and reservation_room.change_room_to_rr is null
                                            and bar_reservation.pay_with_room = 1
                                        ORDER BY
                                            bar_reservation_product.bar_reservation_id, bar_reservation_product.id ASC
            ');
            //System::debug($bar_pay_with_room);
            foreach($bar_pay_with_room as $key => $value)
            {
                $bar_pay_with_room_id = $value['bar_id'].'_'.$value['bar_reservation_id'].'_'.$value['r_id'].'_'.$value['rr_id'].'_'.$value['id'];
                $bar_pay_with_room_tax_id = $value['bar_id'].'_'.$value['bar_reservation_id'].'_'.$value['r_id'].'_'.$value['rr_id'].'_'.$value['id'].'_'.$value['tax_rate'];
                // Tạo mảng doanh thu nha hàng trả về phòng
                if(!isset($rooms[$bar_pay_with_room_id]['id']))
                {
                    $rooms[$bar_pay_with_room_id]['id'] = $bar_pay_with_room_id;
                    $rooms[$bar_pay_with_room_id]['certificate'] = 'NH ';
                    $rooms[$bar_pay_with_room_id]['invoice_symbol'] = '';
                    $rooms[$bar_pay_with_room_id]['invoice_number'] = '';
                    $rooms[$bar_pay_with_room_id]['invoice_date'] = '';
                    $rooms[$bar_pay_with_room_id]['certificate_number'] = 'NH'.date('ymd', $value['certificate_date']).str_pad($e, 4,"0",STR_PAD_LEFT);
                    $rooms[$bar_pay_with_room_id]['certificate_date'] = date('d/m/Y', $value['certificate_date']);
                    $rooms[$bar_pay_with_room_id]['description'] = 'Doanh thu nhà hang '.$value['bar_name'] .' ngày ('.date('d/m/Y', $value['certificate_date']).') recode '.$value['r_id'].' '.$value['customer_code'];
                    $rooms[$bar_pay_with_room_id]['account_no'] = 132;
                    $rooms[$bar_pay_with_room_id]['customer_no'] = $value['customer_code'];
                    $rooms[$bar_pay_with_room_id]['product_import'] = '';
                    if($value['product_type'] == 'GOODS')
                    {
                        $rooms[$bar_pay_with_room_id]['account_co'] = 5111;                        
                    }elseif($value['product_type'] == 'PRODUCT' or $value['product_type'] == 'DRINK')
                    {
                        $rooms[$bar_pay_with_room_id]['account_co'] = 5112;                        
                    }elseif($value['product_type'] == 'SERVICE')
                    {
                        $rooms[$bar_pay_with_room_id]['account_co'] = 5113;                        
                    }else
                    {
                        $rooms[$bar_pay_id]['account_co'] = '';
                    }
                    $rooms[$bar_pay_with_room_id]['customer_co'] = $value['wh_code'];
                    $rooms[$bar_pay_with_room_id]['list_code'] = $value['product_id'];
                    $rooms[$bar_pay_with_room_id]['list_name'] = $value['product_name'];
                    $rooms[$bar_pay_with_room_id]['unit'] = $value['unit_name'];
                    $rooms[$bar_pay_with_room_id]['quantity'] = $value['quantity'];
                    if($value['full_charge'] == 1)
                    {
                        $rooms[$bar_pay_with_room_id]['price_room_before_tax'] = ($value['price']);
                        $rooms[$bar_pay_with_room_id]['total_before_tax'] = ($value['price']*$value['quantity']);
                        $rooms[$bar_pay_with_room_id]['tax_vn'] = (($value['price']*$value['quantity'])*($value['tax_rate'] / 100));
                        $rooms[$bar_pay_with_room_id]['total_vnd_tt'] = ($value['price']*$value['quantity']*(1+($value['tax_rate']/100)));
                    }else if($value['full_charge'] == 0 && $value['full_rate']==0)
                    {
                        $rooms[$bar_pay_with_room_id]['price_room_before_tax'] = ($value['price']*(1+($value['service_rate']/100)));
                        $rooms[$bar_pay_with_room_id]['total_before_tax'] = ($value['price']*(1+($value['service_rate']/100))*$value['quantity']);
                        $rooms[$bar_pay_with_room_id]['tax_vn'] = (($value['price']*(1+($value['service_rate']/100))*$value['quantity'])*($value['tax_rate'] / 100));
                        $rooms[$bar_pay_with_room_id]['total_vnd_tt'] = ($value['price']*(1+($value['service_rate']/100))*$value['quantity']*(1+($value['tax_rate']/100)));                        
                    }else
                    {
                        $rooms[$bar_pay_with_room_id]['price_room_before_tax'] = (($value['price'])/(1+($value['tax_rate']/100))); 
                        $rooms[$bar_pay_with_room_id]['total_before_tax'] = (($value['price']*$value['quantity'])/(1+($value['tax_rate']/100)));
                        $rooms[$bar_pay_with_room_id]['tax_vn'] = ((($value['price']*$value['quantity'])/(1+($value['tax_rate']/100)))*($value['tax_rate'] / 100));
                        $rooms[$bar_pay_with_room_id]['total_vnd_tt'] = ($value['price']*$value['quantity']);                       
                    }
                    $rooms[$bar_pay_with_room_id]['pt_ck'] = '';
                    $rooms[$bar_pay_with_room_id]['ck'] = '';
                    $rooms[$bar_pay_with_room_id]['invoice_vat'] = '';
                    $rooms[$bar_pay_with_room_id]['account_tax'] = 33311;
                    $rooms[$bar_pay_with_room_id]['ts_gtgt'] = $value['tax_rate'];
                    $rooms[$bar_pay_with_room_id]['customer_code'] = $value['customer_code'];
                    $rooms[$bar_pay_with_room_id]['customer_name'] = $value['customer_name'];
                    $rooms[$bar_pay_with_room_id]['customer_tax_code'] = $value['customer_tax_code'];
                    $rooms[$bar_pay_with_room_id]['customer_address'] = $value['customer_address'];
                    $rooms[$bar_pay_with_room_id]['traveller_address'] = '';
                    $rooms[$bar_pay_with_room_id]['traveller_name'] = $value['traveller_name'];
                    $rooms[$bar_pay_with_room_id]['note'] = '';
                }
                // Tạo mảng thuế trả về phòng
                if(!isset($rooms[$bar_pay_with_room_tax_id]['id']))
                {
                    $rooms[$bar_pay_with_room_tax_id]['id'] = $bar_pay_with_room_tax_id;
                    $rooms[$bar_pay_with_room_tax_id]['certificate'] = 'NH ';
                    $rooms[$bar_pay_with_room_tax_id]['invoice_symbol'] = '';
                    $rooms[$bar_pay_with_room_tax_id]['invoice_number'] = '';
                    $rooms[$bar_pay_with_room_tax_id]['invoice_date'] = '';
                    $rooms[$bar_pay_with_room_tax_id]['certificate_number'] = 'NH'.date('ymd', $value['certificate_date']).str_pad($e, 4,"0",STR_PAD_LEFT);
                    $rooms[$bar_pay_with_room_tax_id]['certificate_date'] = date('d/m/Y', $value['certificate_date']);
                    $rooms[$bar_pay_with_room_tax_id]['description'] = 'Thuế GTGT của hàng hóa '.$value['product_name'] .' ngày ('.date('d/m/Y', $value['certificate_date']).') recode '.$value['r_id'].' '.$value['customer_code'];
                    $rooms[$bar_pay_with_room_tax_id]['account_no'] = 132;
                    $rooms[$bar_pay_with_room_tax_id]['customer_no'] = '';
                    $rooms[$bar_pay_with_room_tax_id]['product_import'] = '';
                    $rooms[$bar_pay_with_room_tax_id]['account_co'] = 33311;
                    $rooms[$bar_pay_with_room_tax_id]['customer_co'] = '';
                    $rooms[$bar_pay_with_room_tax_id]['list_code'] = '';
                    $rooms[$bar_pay_with_room_tax_id]['list_name'] = '';
                    $rooms[$bar_pay_with_room_tax_id]['unit'] = '';
                    $rooms[$bar_pay_with_room_tax_id]['quantity'] = '';
                    $rooms[$bar_pay_with_room_tax_id]['price_room_before_tax'] = '';
                    if($value['full_charge'] == 1)
                    {
                        $rooms[$bar_pay_with_room_tax_id]['total_before_tax'] = (($value['price']*$value['quantity'])*($value['tax_rate']/100));
                        $rooms[$bar_pay_with_room_tax_id]['total_vnd_tt'] = (($value['price']*$value['quantity'])*($value['tax_rate']/100));
                    }else if($value['full_charge'] == 0 && $value['full_rate']==0)
                    {
                        $rooms[$bar_pay_with_room_tax_id]['total_before_tax'] = (($value['price']*(1+($value['service_rate']/100))*$value['quantity'])*($value['tax_rate'] / 100));
                        $rooms[$bar_pay_with_room_tax_id]['total_vnd_tt'] = (($value['price']*(1+($value['service_rate']/100))*$value['quantity'])*($value['tax_rate'] / 100));                        
                    }else
                    {
                        $rooms[$bar_pay_with_room_tax_id]['total_before_tax'] = ((($value['price']*$value['quantity'])/(1+($value['tax_rate']/100)))*($value['tax_rate']/100));
                        $rooms[$bar_pay_with_room_tax_id]['total_vnd_tt'] = ((($value['price']*$value['quantity'])/(1+($value['tax_rate']/100)))*($value['tax_rate']/100));                        
                    }
                    $rooms[$bar_pay_with_room_tax_id]['pt_ck'] = '';
                    $rooms[$bar_pay_with_room_tax_id]['ck'] = '';
                    $rooms[$bar_pay_with_room_tax_id]['invoice_vat'] = '';
                    $rooms[$bar_pay_with_room_tax_id]['account_tax'] = '';
                    $rooms[$bar_pay_with_room_tax_id]['ts_gtgt'] = '';
                    $rooms[$bar_pay_with_room_tax_id]['tax_vn'] = '';
                    $rooms[$bar_pay_with_room_tax_id]['customer_code'] = $value['customer_code'];
                    $rooms[$bar_pay_with_room_tax_id]['customer_name'] = $value['customer_name'];
                    $rooms[$bar_pay_with_room_tax_id]['customer_tax_code'] = $value['customer_tax_code'];
                    $rooms[$bar_pay_with_room_tax_id]['customer_address'] = $value['customer_address'];
                    $rooms[$bar_pay_with_room_tax_id]['traveller_address'] = '';
                    $rooms[$bar_pay_with_room_tax_id]['traveller_name'] = $value['traveller_name'];
                    $rooms[$bar_pay_with_room_tax_id]['note'] = '';
                }
                $e++;
            }
            // Khách vãng lai (khách lẻ) sử dụng dịch vụ tại nhà hàng và thanh toán trực tiếp
            $bar_pay = DB::fetch_all('
                                        SELECT
                                            bar_reservation_product.id,
                                            bar_reservation_product.product_id,
                                            product.name_'.Portal::language().' as product_name,
                                            product.type as product_type,
                                            unit.name_'.Portal::language().' as unit_name,
                                            bar_reservation_product.price,
                                            CASE
                                                WHEN bar_reservation_product.quantity_discount = 0
                                                THEN bar_reservation_product.quantity
                                                ELSE bar_reservation_product.quantity - bar_reservation_product.quantity_discount
                                            END as quantity,
                                            bar_reservation_product.bar_id,
                                            bar.name as bar_name,
                                            bar_reservation.code as bar_code,
                                            bar_reservation_product.bar_reservation_id,
                                            bar_reservation.time as certificate_date,
                                            bar_reservation.tax_rate,
                                            bar_reservation.bar_fee_rate as service_rate,
                                            bar_reservation.full_rate,
                                            bar_reservation.full_charge,
                                            CASE
                                                WHEN bar_reservation.customer_id is not null
                                                THEN  customer.name
                                                ELSE \'KHACHLENH\'
                                            END as customer_code,
                                            CASE
                                                WHEN bar_reservation.customer_id is not null
                                                THEN  customer.def_name
                                                ELSE \'Khách lẻ nhà hàng\'
                                            END as customer_name,
                                            customer.mobile as customer_phone,
                                            customer.address as customer_address,
                                            customer.tax_code as customer_tax_code,
                                            CASE
                                                WHEN bar_reservation.reservation_traveller_id != 0 and bar_reservation.reservation_traveller_id is not null
                                                THEN traveller.first_name || \' \' || traveller.last_name
                                                ELSE \'Khách lẻ nhà hàng\'
                                            END as traveller_name,
                                            warehouse.code as wh_code,
                                            warehouse.name as wh_name
                                        FROM
                                            bar_reservation_product
                                            inner join bar_reservation on bar_reservation_product.bar_reservation_id = bar_reservation.id
                                            inner join bar on bar_reservation.bar_id = bar.id
                                            inner join warehouse on bar.warehouse_id = warehouse.id
                                            inner join product on bar_reservation_product.product_id = product.id
                                            inner join unit on product.unit_id = unit.id
                                            left join customer on customer.id = bar_reservation.customer_id
                                            left join reservation_traveller on bar_reservation.reservation_traveller_id = reservation_traveller.id
                                            left join traveller on traveller.id = reservation_traveller.traveller_id
                                        WHERE
                                            bar_reservation.time >= \''.(Date_Time::to_time($export_date)).'\'
                                            and bar_reservation.time < \''.(Date_Time::to_time($export_date)+86399).'\'
                                            and bar_reservation.pay_with_room <> 1
                                        ORDER BY
                                            bar_reservation_product.bar_reservation_id, bar_reservation_product.id ASC
            ');
            foreach($bar_pay as $key => $value)
            {
                $bar_pay_id = $value['bar_id'].'_'.$value['bar_reservation_id'].'_'.$value['id'].'_'.'KHACHLENH';
                $bar_pay_tax_id = $value['bar_id'].'_'.$value['bar_reservation_id'].'_'.$value['id'].'_'.$value['tax_rate'].'_'.'KHACHLENH';
                if(!isset($rooms[$bar_pay_id]['id']))
                {
                    $rooms[$bar_pay_id]['id'] = $bar_pay_id;
                    $rooms[$bar_pay_id]['certificate'] = 'NH ';
                    $rooms[$bar_pay_id]['invoice_symbol'] = '';
                    $rooms[$bar_pay_id]['invoice_number'] = '';
                    $rooms[$bar_pay_id]['invoice_date'] = '';
                    $rooms[$bar_pay_id]['certificate_number'] = 'NH'.date('ymd', $value['certificate_date']).str_pad($e, 4,"0",STR_PAD_LEFT);
                    $rooms[$bar_pay_id]['certificate_date'] = date('d/m/Y', $value['certificate_date']);
                    $rooms[$bar_pay_id]['description'] = 'Doanh thu nhà hang '.$value['bar_name'] .' ngày ('.date('d/m/Y', $value['certificate_date']).') hóa đơn '.$value['bar_code'].' của '.$value['customer_code'];
                    $rooms[$bar_pay_id]['account_no'] = 132;
                    $rooms[$bar_pay_id]['customer_no'] = $value['customer_code'];
                    $rooms[$bar_pay_id]['product_import'] = '';
                    if($value['product_type'] == 'GOODS')
                    {
                        $rooms[$bar_pay_id]['account_co'] = 5111;                        
                    }elseif($value['product_type'] == 'PRODUCT' or $value['product_type'] == 'DRINK')
                    {
                        $rooms[$bar_pay_id]['account_co'] = 5112;                        
                    }elseif($value['product_type'] == 'SERVICE')
                    {
                        $rooms[$bar_pay_id]['account_co'] = 5113;                        
                    }else
                    {
                        $rooms[$bar_pay_id]['account_co'] = '';    
                    }
                    $rooms[$bar_pay_id]['customer_co'] = $value['wh_code'];
                    $rooms[$bar_pay_id]['list_code'] = $value['product_id'];
                    $rooms[$bar_pay_id]['list_name'] = $value['product_name'];
                    $rooms[$bar_pay_id]['unit'] = $value['unit_name'];
                    $rooms[$bar_pay_id]['quantity'] = $value['quantity'];
                    if($value['full_charge'] == 1)
                    {
                        $rooms[$bar_pay_id]['price_room_before_tax'] = ($value['price']);
                        $rooms[$bar_pay_id]['total_before_tax'] = ($value['price']*$value['quantity']);
                        $rooms[$bar_pay_id]['tax_vn'] = (($value['price']*$value['quantity'])*($value['tax_rate'] / 100));
                        $rooms[$bar_pay_id]['total_vnd_tt'] = ($value['price']*$value['quantity']*(1+($value['tax_rate']/100)));
                    }else if($value['full_charge'] == 0 && $value['full_rate']==0)
                    {
                        $rooms[$bar_pay_id]['price_room_before_tax'] = ($value['price']*(1+($value['service_rate']/100)));
                        $rooms[$bar_pay_id]['total_before_tax'] = ($value['price']*(1+($value['service_rate']/100))*$value['quantity']);
                        $rooms[$bar_pay_id]['tax_vn'] = (($value['price']*(1+($value['service_rate']/100))*$value['quantity'])*($value['tax_rate'] / 100));
                        $rooms[$bar_pay_id]['total_vnd_tt'] = ($value['price']*(1+($value['service_rate']/100))*$value['quantity']*(1+($value['tax_rate']/100)));                        
                    }else
                    {
                        $rooms[$bar_pay_id]['price_room_before_tax'] = (($value['price'])/(1+($value['tax_rate']/100)));
                        $rooms[$bar_pay_id]['total_before_tax'] = (($value['price']*$value['quantity'])/(1+($value['tax_rate']/100)));  
                        $rooms[$bar_pay_id]['tax_vn'] = ((($value['price']*$value['quantity'])/(1+($value['tax_rate']/100)))*($value['tax_rate'] / 100));
                        $rooms[$bar_pay_id]['total_vnd_tt'] = ($value['price']*$value['quantity']);                      
                    }
                    $rooms[$bar_pay_id]['pt_ck'] = '';
                    $rooms[$bar_pay_id]['ck'] = '';
                    $rooms[$bar_pay_id]['invoice_vat'] = '';
                    $rooms[$bar_pay_id]['account_tax'] = 33311;
                    $rooms[$bar_pay_id]['ts_gtgt'] = $value['tax_rate'];
                    $rooms[$bar_pay_id]['customer_code'] = $value['customer_code'];
                    $rooms[$bar_pay_id]['customer_name'] = $value['customer_name'];
                    $rooms[$bar_pay_id]['customer_tax_code'] = '';
                    $rooms[$bar_pay_id]['customer_address'] = '';
                    $rooms[$bar_pay_id]['traveller_address'] = '';
                    $rooms[$bar_pay_id]['traveller_name'] = '';
                    $rooms[$bar_pay_id]['note'] = '';
                }
                //thuế
                if(!isset($rooms[$bar_pay_tax_id]['id']))
                {
                    $rooms[$bar_pay_tax_id]['id'] = $bar_pay_tax_id;
                    $rooms[$bar_pay_tax_id]['certificate'] = 'NH ';
                    $rooms[$bar_pay_tax_id]['invoice_symbol'] = '';
                    $rooms[$bar_pay_tax_id]['invoice_number'] = '';
                    $rooms[$bar_pay_tax_id]['invoice_date'] = '';
                    $rooms[$bar_pay_tax_id]['certificate_number'] = 'NH'.date('ymd', $value['certificate_date']).str_pad($e, 4,"0",STR_PAD_LEFT);
                    $rooms[$bar_pay_tax_id]['certificate_date'] = date('d/m/Y', $value['certificate_date']);
                    $rooms[$bar_pay_tax_id]['description'] = 'Thuế GTGT của hàng hóa '.$value['product_name'] .' ngày ('.date('d/m/Y', $value['certificate_date']).') hóa đơn '.$value['bar_code'].' của '.$value['customer_code'];
                    $rooms[$bar_pay_tax_id]['account_no'] = 132;
                    $rooms[$bar_pay_tax_id]['customer_no'] = $value['customer_code'];
                    $rooms[$bar_pay_tax_id]['product_import'] = '';
                    $rooms[$bar_pay_tax_id]['account_co'] = 33311;
                    $rooms[$bar_pay_tax_id]['customer_co'] = '';
                    $rooms[$bar_pay_tax_id]['list_code'] = '';
                    $rooms[$bar_pay_tax_id]['list_name'] = '';
                    $rooms[$bar_pay_tax_id]['unit'] = '';
                    $rooms[$bar_pay_tax_id]['quantity'] = '';
                    $rooms[$bar_pay_tax_id]['price_room_before_tax'] = '';
                    if($value['full_charge'] == 1)
                    {
                        $rooms[$bar_pay_tax_id]['total_before_tax'] = (($value['price']*$value['quantity'])*($value['tax_rate']/100));
                        $rooms[$bar_pay_tax_id]['total_vnd_tt'] = (($value['price']*$value['quantity'])*($value['tax_rate']/100));
                    }else if($value['full_charge'] == 0 && $value['full_rate']==0)
                    {
                        $rooms[$bar_pay_tax_id]['total_before_tax'] = (($value['price']*(1+($value['service_rate']/100))*$value['quantity'])*($value['tax_rate'] / 100));
                        $rooms[$bar_pay_tax_id]['total_vnd_tt'] = (($value['price']*(1+($value['service_rate']/100))*$value['quantity'])*($value['tax_rate'] / 100));                        
                    }else
                    {
                        $rooms[$bar_pay_tax_id]['total_before_tax'] = ((($value['price']*$value['quantity'])/(1+($value['tax_rate']/100)))*($value['tax_rate']/100));  
                        $rooms[$bar_pay_tax_id]['total_vnd_tt'] = ((($value['price']*$value['quantity'])/(1+($value['tax_rate']/100)))*($value['tax_rate']/100));                      
                    }
                    $rooms[$bar_pay_tax_id]['pt_ck'] = '';
                    $rooms[$bar_pay_tax_id]['ck'] = '';
                    $rooms[$bar_pay_tax_id]['invoice_vat'] = '';
                    $rooms[$bar_pay_tax_id]['account_tax'] = '';
                    $rooms[$bar_pay_tax_id]['ts_gtgt'] = '';
                    $rooms[$bar_pay_tax_id]['tax_vn'] = '';
                    $rooms[$bar_pay_tax_id]['customer_code'] = $value['customer_code'];
                    $rooms[$bar_pay_tax_id]['customer_name'] = $value['customer_name'];
                    $rooms[$bar_pay_tax_id]['customer_tax_code'] = '';
                    $rooms[$bar_pay_tax_id]['customer_address'] = '';
                    $rooms[$bar_pay_tax_id]['traveller_address'] = '';
                    $rooms[$bar_pay_tax_id]['traveller_name'] = '';
                    $rooms[$bar_pay_tax_id]['note'] = '';
                }
                $e++;
            }
            // Giảm giá của nhà hàng
            //Lấy các giảm giá trên toàn hóa đơn 
            $bar_discount = DB::fetch_all('
                        SELECT
                            bar_reservation_product.id,
                            bar_reservation_product.product_id,
                            product.name_'.Portal::language().' as product_name,
                            product.type as product_type,
                            unit.name_'.Portal::language().' as unit_name,
                            bar_reservation_product.price,
                            CASE
                                WHEN bar_reservation_product.quantity_discount = 0
                                THEN bar_reservation_product.quantity
                                ELSE bar_reservation_product.quantity - bar_reservation_product.quantity_discount
                            END as quantity,
                            bar_reservation_product.bar_id,
                            bar_reservation_product.discount_rate,
                            bar_reservation_product.discount,
                            bar.name as bar_name,
                            bar_reservation_product.bar_reservation_id,
                            bar_reservation.code as bar_code,
                            bar_reservation.time as certificate_date,
                            bar_reservation.tax_rate,
                            bar_reservation.bar_fee_rate as service_rate,
                            bar_reservation.full_rate,
                            bar_reservation.full_charge,
                            bar_reservation.discount_percent,
                            bar_reservation.discount as bar_discount,
                            reservation_room.id as rr_id,
                            reservation.id as r_id,
                            room.name as room_name,
                            CASE
                                WHEN bar_reservation.customer_id is not null
                                THEN  customer.name
                                ELSE \'KHACHLENH\'
                            END as customer_code,
                            CASE
                                WHEN bar_reservation.customer_id is not null
                                THEN  customer.def_name
                                ELSE \'Khách lẻ nhà hàng\'
                            END as customer_name,
                            customer.mobile as customer_phone,
                            customer.address as customer_address,
                            customer.tax_code as customer_tax_code,
                            CASE
                                WHEN bar_reservation.reservation_traveller_id != 0 and bar_reservation.reservation_traveller_id is not null
                                THEN traveller.first_name || \' \' || traveller.last_name
                                ELSE \'Khách lẻ nhà hàng\'
                            END as traveller_name
                        FROM
                            bar_reservation_product
                            inner join bar_reservation on bar_reservation_product.bar_reservation_id = bar_reservation.id
                            inner join bar on bar_reservation.bar_id = bar.id
                            inner join warehouse on bar.warehouse_id = warehouse.id
                            inner join product on bar_reservation_product.product_id = product.id
                            inner join unit on product.unit_id = unit.id
                            left join reservation_room on bar_reservation.reservation_room_id = reservation_room.id
                            left join reservation on reservation_room.reservation_id = reservation.id
                            left join room on reservation_room.room_id = room.id
                            left join customer on customer.id = bar_reservation.customer_id
                            left join reservation_traveller on bar_reservation.reservation_traveller_id = reservation_traveller.id
                            left join traveller on traveller.id = reservation_traveller.traveller_id
                        WHERE
                            bar_reservation.time >= \''.(Date_Time::to_time($export_date)).'\'
                            and bar_reservation.time <= \''.(Date_Time::to_time($export_date)+86399).'\'
                            and (bar_reservation.discount_percent > 0 or bar_reservation.discount >0 or bar_reservation_product.discount_rate > 0)
                        ORDER BY
                            bar_reservation_product.bar_reservation_id, bar_reservation_product.id ASC
            ');
            $bar_discount_arr = array();
            /**
             *  Phần giảm giá này khó tính toán quá đấy
             *  Làm thế nào bây giờ đây
             *  Có cách nào tốt nhất để giải quyết không đây huhu
             */
            foreach($bar_discount as $key => $value)
            {
                $id = $value['bar_reservation_id'].'_'.$value['bar_id'].'_'.$value['product_id'];
                /**Hiện tại giảm giá theo % đang đúng, còn giảm giá theo số tiền chưa đúng phải làm như thế nào bây giờ
                *  Khởi tạo 1 mảng khác với id riêng cho giảm giá số tiền, nhưng vẫn phải có giảm giá sản phẩm
                *  Mảng với id cũ sẽ là giảm giá theo %
                */
                // Set nếu giảm giá theo số tiền sẽ chạy vào đây 
                $id_discount = 'DISCOUNT_'.$value['bar_reservation_id'].'_'.$value['bar_id'].'_'.$value['bar_discount'];
                if($value['bar_discount'] >0)
                {
                    if(!isset($bar_discount_arr[$id_discount]['id']))
                    {
                        $bar_discount_arr[$id_discount]['id'] = $id_discount;
                        $bar_discount_arr[$id_discount]['bar_reservation_id'] = $value['bar_reservation_id'];
                        $bar_discount_arr[$id_discount]['certificate_date'] = $value['certificate_date'];
                        $bar_discount_arr[$id_discount]['rr_id'] = $value['rr_id'];
                        $bar_discount_arr[$id_discount]['r_id'] = $value['r_id'];
                        $bar_discount_arr[$id_discount]['bar_code'] = $value['bar_code'];
                        $bar_discount_arr[$id_discount]['product_id'] = '';
                        $bar_discount_arr[$id_discount]['product_name'] = '';
                        $bar_discount_arr[$id_discount]['unit_name'] = '';
                        $bar_discount_arr[$id_discount]['total_discount'] = $value['bar_discount'];
                        $bar_discount_arr[$id_discount]['tax_rate'] = 0;
                        $bar_discount_arr[$id_discount]['service_rate'] = 0;
                        $bar_discount_arr[$id_discount]['customer_code'] = $value['customer_code']?$value['customer_code']:'KHACHLENH';
                        $bar_discount_arr[$id_discount]['customer_name'] = $value['customer_name']?$value['customer_code']:'Khách lẻ nhà hàng';
                        $bar_discount_arr[$id_discount]['customer_phone'] = $value['customer_phone']?$value['customer_phone']:'';
                        $bar_discount_arr[$id_discount]['customer_address'] = $value['customer_address']?$value['customer_address']:'';
                        $bar_discount_arr[$id_discount]['customer_tax_code'] = $value['customer_tax_code']?$value['customer_tax_code']:'';
                        $bar_discount_arr[$id_discount]['traveller_name'] = $value['traveller_name']?$value['traveller_name']:'Khách lẻ nhà hàng';
                        $bar_discount_arr[$id_discount]['room_name'] = $value['room_name'];
                    }
                }
                
                if(!isset($bar_discount_arr[$id]['id']))
                {
                    $bar_discount_arr[$id]['id'] = $id;
                    $bar_discount_arr[$id]['bar_reservation_id'] = $value['bar_reservation_id'];
                    $bar_discount_arr[$id]['certificate_date'] = $value['certificate_date'];
                    $bar_discount_arr[$id]['rr_id'] = $value['rr_id'];
                    $bar_discount_arr[$id]['r_id'] = $value['r_id'];
                    $bar_discount_arr[$id]['bar_code'] = $value['bar_code'];
                    $bar_discount_arr[$id]['product_id'] = $value['product_id'];
                    $bar_discount_arr[$id]['product_name'] = $value['product_name'];
                    $bar_discount_arr[$id]['unit_name'] = $value['unit_name'];
                    if($value['full_charge'] == 1)
                    {
                        $product_before_tax = $value['price']/(1+($value['service_rate']/100));
                        $product_discount_before_tax = $product_before_tax *($value['discount_rate']/100);
                        $price_before_tax = $product_before_tax - $product_discount_before_tax;
                        $price_discount_before_tax = $price_before_tax*$value['quantity'];
                        //Hiện tại giảm giá sp chỉ có % nên không check, sau này có theo số tiền sẽ check sau.
                        //Lấy ra giảm giá của sp
                        $full_price_discount_before_tax = $price_discount_before_tax*($value['discount_percent']/100);
                        $bar_discount_arr[$id]['total_discount'] = $full_price_discount_before_tax + $product_discount_before_tax*$value['quantity'];
                    }else if($value['full_charge'] == 0 && $value['full_rate']==0)
                    {
                        $product_before_tax = $value['price'];
                        $product_discount_before_tax = $product_before_tax *($value['discount_rate']/100);
                        $price_before_tax = $product_before_tax - $product_discount_before_tax;
                        $price_discount_before_tax = $price_before_tax*$value['quantity'];
                        //Hiện tại giảm giá sp chỉ có % nên không check, sau này có theo số tiền sẽ check sau.
                        //Lấy ra giảm giá của sp
                        $full_price_discount_before_tax = $price_discount_before_tax*($value['discount_percent']/100);
                        $bar_discount_arr[$id]['total_discount'] = $full_price_discount_before_tax + $product_discount_before_tax*$value['quantity'];                        
                    }else
                    {
                        //Giá sp trước thuế phí
                        $product_before_tax = $value['price']/(1+($value['tax_rate']/100))/(1+($value['service_rate']/100));
                        $product_discount_before_tax = $product_before_tax *($value['discount_rate']/100);
                        $price_before_tax = $product_before_tax - $product_discount_before_tax;
                        $price_discount_before_tax = $price_before_tax*$value['quantity'];
                        //Hiện tại giảm giá sp chỉ có % nên không check, sau này có theo số tiền sẽ check sau.
                        //Lấy ra giảm giá của sp trước thuế phí
                        $full_price_discount_before_tax = $price_discount_before_tax*($value['discount_percent']/100);
                        $bar_discount_arr[$id]['total_discount'] = $full_price_discount_before_tax + $product_discount_before_tax*$value['quantity'];                       
                    }
                    $bar_discount_arr[$id]['tax_rate'] = $value['tax_rate'];
                    $bar_discount_arr[$id]['service_rate'] = $value['service_rate'];
                    $bar_discount_arr[$id]['customer_code'] = $value['customer_code'];
                    $bar_discount_arr[$id]['customer_name'] = $value['customer_name'];
                    $bar_discount_arr[$id]['customer_phone'] = $value['customer_phone'];
                    $bar_discount_arr[$id]['customer_address'] = $value['customer_address'];
                    $bar_discount_arr[$id]['customer_tax_code'] = $value['customer_tax_code'];
                    $bar_discount_arr[$id]['traveller_name'] = $value['traveller_name'];
                    $bar_discount_arr[$id]['room_name'] = $value['room_name'];
                }else
                {
                    if($value['full_charge'] == 1)
                    {
                        $product_before_tax = $value['price']/(1+($value['service_rate']/100));
                        $product_discount_before_tax = $product_before_tax *($value['discount_rate']/100);
                        $price_before_tax = $product_before_tax - $product_discount_before_tax;
                        $price_discount_before_tax = $price_before_tax*$value['quantity'];
                        //Hiện tại giảm giá sp chỉ có % nên không check, sau này có theo số tiền sẽ check sau.
                        //Lấy ra giảm giá của sp
                        $full_price_discount_before_tax = $price_discount_before_tax*($value['discount_percent']/100);
                        $bar_discount_arr[$id]['total_discount'] += $full_price_discount_before_tax + $product_discount_before_tax*$value['quantity'];
                    }else if($value['full_charge'] == 0 && $value['full_rate']==0)
                    {
                        $product_before_tax = $value['price'];
                        $product_discount_before_tax = $product_before_tax *($value['discount_rate']/100);
                        $price_before_tax = $product_before_tax - $product_discount_before_tax;
                        $price_discount_before_tax = $price_before_tax*$value['quantity'];
                        //Hiện tại giảm giá sp chỉ có % nên không check, sau này có theo số tiền sẽ check sau.
                        //Lấy ra giảm giá của sp
                        $full_price_discount_before_tax = $price_discount_before_tax*($value['discount_percent']/100);
                        $bar_discount_arr[$id]['total_discount'] += $full_price_discount_before_tax + $product_discount_before_tax*$value['quantity'];                        
                    }else
                    {
                        //Giá sp trước thuế phí
                        $product_before_tax = $value['price']/(1+($value['tax_rate']/100))/(1+($value['service_rate']/100));
                        $product_discount_before_tax = $product_before_tax *($value['discount_rate']/100);
                        $price_before_tax = $product_before_tax - $product_discount_before_tax;
                        $price_discount_before_tax = $price_before_tax*$value['quantity'];
                        //Hiện tại giảm giá sp chỉ có % nên không check, sau này có theo số tiền sẽ check sau.
                        //Lấy ra giảm giá của sp trước thuế phí
                        $full_price_discount_before_tax = $price_discount_before_tax*($value['discount_percent']/100);
                        $bar_discount_arr[$id]['total_discount'] += $full_price_discount_before_tax + $product_discount_before_tax*$value['quantity'];                       
                    }                    
                }
            }
            //System::debug($bar_discount_arr);exit();
            foreach($bar_discount_arr as $key => $value)
            {
                $bar_discount_arr_id = 'BAR_PGG_' . $value['id'];
                $bar_discount_arr_tax_id = 'BAR_PGG_' . $value['id'] .'_'.$value['tax_rate'];
                if(!isset($rooms[$bar_discount_arr_id]['id']))
                {
                    $rooms[$bar_discount_arr_id]['id'] = $bar_discount_arr_id;
                    $rooms[$bar_discount_arr_id]['certificate'] = 'PGG ';
                    $rooms[$bar_discount_arr_id]['invoice_symbol'] = '';
                    $rooms[$bar_discount_arr_id]['invoice_number'] = '';
                    $rooms[$bar_discount_arr_id]['invoice_date'] = '';
                    $rooms[$bar_discount_arr_id]['certificate_number'] = 'PGG'.date('ymd', $value['certificate_date']).str_pad($t, 4,"0",STR_PAD_LEFT);
                    $rooms[$bar_discount_arr_id]['certificate_date'] = date('d/m/Y', $value['certificate_date']);
                    $rooms[$bar_discount_arr_id]['description'] = 'Giảm tiền nhà hàng cho khách hàng '.$value['traveller_name'].' ( '.$value['customer_code'].' ), hóa đơn '.$value['bar_code'].'';
                    $rooms[$bar_discount_arr_id]['account_no'] = 5213;
                    $rooms[$bar_discount_arr_id]['customer_no'] = '';
                    $rooms[$bar_discount_arr_id]['product_import'] = '';
                    $rooms[$bar_discount_arr_id]['account_co'] = 132;
                    $rooms[$bar_discount_arr_id]['customer_co'] = $value['customer_code'];
                    $rooms[$bar_discount_arr_id]['list_code'] = $value['product_id'];
                    $rooms[$bar_discount_arr_id]['list_name'] = $value['product_name'];
                    $rooms[$bar_discount_arr_id]['unit'] = $value['unit_name'];
                    $rooms[$bar_discount_arr_id]['quantity'] = '';
                    $rooms[$bar_discount_arr_id]['price_room_before_tax'] = '';
                    $rooms[$bar_discount_arr_id]['total_before_tax'] = $value['total_discount']*(1+($value['service_rate']/100));
                    $rooms[$bar_discount_arr_id]['pt_ck'] = '';
                    $rooms[$bar_discount_arr_id]['ck'] = $value['total_discount']*(1+($value['service_rate']/100));
                    $rooms[$bar_discount_arr_id]['invoice_vat'] = '';
                    $rooms[$bar_discount_arr_id]['account_tax'] = 33311;
                    $rooms[$bar_discount_arr_id]['ts_gtgt'] = $value['tax_rate'];
                    $rooms[$bar_discount_arr_id]['tax_vn'] = (($value['total_discount']*(1+($value['service_rate']/100)))*($value['tax_rate'] / 100));
                    $rooms[$bar_discount_arr_id]['total_vnd_tt'] = ($value['total_discount']*(1+($value['tax_rate']/100))*(1+($value['service_rate']/100)));
                    $rooms[$bar_discount_arr_id]['customer_code'] = $value['customer_code'];
                    $rooms[$bar_discount_arr_id]['customer_name'] = $value['customer_name'];
                    $rooms[$bar_discount_arr_id]['customer_tax_code'] = $value['customer_tax_code'];
                    $rooms[$bar_discount_arr_id]['customer_address'] = $value['customer_address'];
                    $rooms[$bar_discount_arr_id]['traveller_address'] = '';
                    $rooms[$bar_discount_arr_id]['traveller_name'] = $value['traveller_name'];
                    $rooms[$bar_discount_arr_id]['note'] = '';                    
                } 
                
                //tạo mảng thuế
                if(!isset($rooms[$bar_discount_arr_tax_id]['id']))
                {
                    $rooms[$bar_discount_arr_tax_id]['id'] = $bar_discount_arr_tax_id;
                    $rooms[$bar_discount_arr_tax_id]['certificate'] = 'PGG ';
                    $rooms[$bar_discount_arr_tax_id]['invoice_symbol'] = '';
                    $rooms[$bar_discount_arr_tax_id]['invoice_number'] = '';
                    $rooms[$bar_discount_arr_tax_id]['invoice_date'] = '';
                    $rooms[$bar_discount_arr_tax_id]['certificate_number'] = 'PGG'.date('ymd', $value['certificate_date']).str_pad($t, 4,"0",STR_PAD_LEFT);
                    $rooms[$bar_discount_arr_tax_id]['certificate_date'] = date('d/m/Y', $value['certificate_date']);
                    $rooms[$bar_discount_arr_tax_id]['description'] = 'Giảm thuế GTGT nhà hàng cho khách hàng '.$value['traveller_name'].' ( '.$value['customer_code'].' ), hóa đơn '.$value['bar_code'].'';
                    $rooms[$bar_discount_arr_tax_id]['account_no'] = 33311;
                    $rooms[$bar_discount_arr_tax_id]['customer_no'] = '';
                    $rooms[$bar_discount_arr_tax_id]['product_import'] = '';
                    $rooms[$bar_discount_arr_tax_id]['account_co'] = 132;
                    $rooms[$bar_discount_arr_tax_id]['customer_co'] = $value['customer_code'];
                    $rooms[$bar_discount_arr_tax_id]['list_code'] = '';
                    $rooms[$bar_discount_arr_tax_id]['list_name'] = '';
                    $rooms[$bar_discount_arr_tax_id]['unit'] = '';
                    $rooms[$bar_discount_arr_tax_id]['quantity'] = '';
                    $rooms[$bar_discount_arr_tax_id]['price_room_before_tax'] = '';
                    $rooms[$bar_discount_arr_tax_id]['total_before_tax'] = (($value['total_discount']*(1+($value['service_rate']/100)))*($value['tax_rate'] / 100));
                    $rooms[$bar_discount_arr_tax_id]['pt_ck'] = '';
                    $rooms[$bar_discount_arr_tax_id]['ck'] = '';
                    $rooms[$bar_discount_arr_tax_id]['invoice_vat'] = '';
                    $rooms[$bar_discount_arr_tax_id]['account_tax'] = '';
                    $rooms[$bar_discount_arr_tax_id]['ts_gtgt'] = '';
                    $rooms[$bar_discount_arr_tax_id]['tax_vn'] = '';
                    $rooms[$bar_discount_arr_tax_id]['total_vnd_tt'] = (($value['total_discount']*(1+($value['service_rate']/100)))*($value['tax_rate'] / 100));
                    $rooms[$bar_discount_arr_tax_id]['customer_code'] = $value['customer_code'];
                    $rooms[$bar_discount_arr_tax_id]['customer_name'] = $value['customer_name'];
                    $rooms[$bar_discount_arr_tax_id]['customer_tax_code'] = $value['customer_tax_code'];
                    $rooms[$bar_discount_arr_tax_id]['customer_address'] = $value['customer_address'];
                    $rooms[$bar_discount_arr_tax_id]['traveller_address'] = '';
                    $rooms[$bar_discount_arr_tax_id]['traveller_name'] = $value['traveller_name'];
                    $rooms[$bar_discount_arr_tax_id]['note'] = '';                    
                }
                $t++;                
            }
            //System::debug($rooms);exit();
            // Đặt cọc nhà hàng
            $bar_deposit = DB::fetch_all('
                            SELECT
                                payment.id,
                                payment.payment_type_id,
                                payment.time as certificate_date,
                                payment.amount,
                                payment.type_dps,
                                bar_reservation.id as bar_id,
                                bar.code as bar_code,
                                bar.name as bar_name,
                                bar_table.name as table_name,
                                CASE
                                    WHEN bar_reservation.customer_id is not null
                                    THEN  customer.name
                                    ELSE \'KHACHLENH\'
                                END as customer_code,
                                CASE
                                    WHEN bar_reservation.customer_id is not null
                                    THEN  customer.def_name
                                    ELSE \'Khách lẻ nhà hàng\'
                                END as customer_name,
                                customer.mobile as customer_phone,
                                customer.address as customer_address,
                                customer.tax_code as customer_tax_code,
                                CASE
                                    WHEN bar_reservation.reservation_traveller_id != 0 and bar_reservation.reservation_traveller_id is not null
                                    THEN traveller.first_name || \' \' || traveller.last_name
                                    ELSE \'Khách lẻ nhà hàng\'
                                END as traveller_name
                            FROM
                                payment
                                inner join bar_reservation on bar_reservation.id = payment.bill_id
                                inner join bar_reservation_table on bar_reservation_table.bar_reservation_id = bar_reservation.id
    			                inner join bar_table on bar_table.id = bar_reservation_table.table_id
                                inner join bar on bar_reservation.bar_id = bar.id
                                inner join bar_table on bar_table.bar_id = bar.id
                                left join reservation_traveller on bar_reservation.reservation_traveller_id = reservation_traveller.id
                                left join traveller on traveller.id = reservation_traveller.traveller_id
                                left join customer on bar_reservation.customer_id = customer.id
                            WHERE
                                payment.time >= \''.Date_Time::to_time($export_date).'\'
                                and payment.time < \''.(Date_Time::to_time($export_date)+86399).'\'
                                and payment.type = \'BAR\'
                                and payment.type_dps = \'BAR\'
                            ORDER BY
                                payment.id ASC
            ');
            foreach($bar_deposit as $key => $value)
            {
                $bar_deposit_id = 'DPS_'. $value['id'].'_'.$value['bar_id'].'_'.$value['bar_code'];
                $traveller_name =$value['traveller_name']?$value['traveller_name']:'Khách lẻ nhà hàng';
                $customer_code =$value['customer_code']?$value['customer_code']:'KHACHLENH';              
                if(!isset($rooms[$bar_deposit_id]['id']))
                {
                    $rooms[$bar_deposit_id]['id'] = $bar_deposit_id;
                    if($value['payment_type_id'] == 'CASH')
                    {
                        $rooms[$bar_deposit_id]['certificate'] = 'PT'; 
                        $rooms[$bar_deposit_id]['certificate_number'] = 'PT'.date('ymd', $value['certificate_date']).str_pad($y, 4,"0",STR_PAD_LEFT);
                        $rooms[$bar_deposit_id]['account_no'] = 1111;   
                    }else if($value['payment_type_id'] == 'CREDIT_CARD' or $value['payment_type_id'] == 'BANK')
                    {
                        $rooms[$bar_deposit_id]['certificate'] = 'GBC';
                        $rooms[$bar_deposit_id]['certificate_number'] = 'GBC'.date('ymd', $value['certificate_date']).str_pad($y, 4,"0",STR_PAD_LEFT);
                        $rooms[$bar_deposit_id]['account_no'] = 1121;                        
                    }
                    $rooms[$bar_deposit_id]['invoice_symbol'] = '';
                    $rooms[$bar_deposit_id]['invoice_number'] = '';
                    $rooms[$bar_deposit_id]['invoice_date'] = '';
                    $rooms[$bar_deposit_id]['certificate_date'] = date('d/m/Y', $value['certificate_date']);
                    $rooms[$bar_deposit_id]['description'] = 'Khách hàng ' . $value['traveller_name'] . ' ( '.($value['customer_code']).' ), chuyển tiền đặt cọc cho bàn số ' .$value['table_name'].' của nhà hàng '.$value['bar_name'];
                    $rooms[$bar_deposit_id]['customer_no'] = '';
                    $rooms[$bar_deposit_id]['product_import'] = '';
                    $rooms[$bar_deposit_id]['account_co'] = 131;
                    $rooms[$bar_deposit_id]['customer_co'] = $value['customer_code'];
                    $rooms[$bar_deposit_id]['list_code'] = '';
                    $rooms[$bar_deposit_id]['list_name'] = '';
                    $rooms[$bar_deposit_id]['unit'] = '';
                    $rooms[$bar_deposit_id]['quantity'] = '';
                    $rooms[$bar_deposit_id]['price_room_before_tax'] = '';
                    $rooms[$bar_deposit_id]['total_before_tax'] = $value['amount'];
                    $rooms[$bar_deposit_id]['pt_ck'] = '';
                    $rooms[$bar_deposit_id]['ck'] = '';
                    $rooms[$bar_deposit_id]['invoice_vat'] = '';
                    $rooms[$bar_deposit_id]['account_tax'] = '';
                    $rooms[$bar_deposit_id]['ts_gtgt'] = '';
                    $rooms[$bar_deposit_id]['tax_vn'] = '';
                    $rooms[$bar_deposit_id]['total_vnd_tt'] = '';
                    $rooms[$bar_deposit_id]['customer_code'] = $value['customer_code'];
                    $rooms[$bar_deposit_id]['customer_name'] = $value['customer_name'];
                    $rooms[$bar_deposit_id]['customer_tax_code'] = $value['customer_tax_code'];
                    $rooms[$bar_deposit_id]['customer_address'] = $value['customer_address'];
                    $rooms[$bar_deposit_id]['traveller_address'] = '';
                    $rooms[$bar_deposit_id]['traveller_name'] = $value['traveller_name'];
                    $rooms[$bar_deposit_id]['note'] = '';                    
                }
                $y++;                
            }
            // Thanh toán hóa đơn nhà hàng
            $payment_bar = DB::fetch_all('
                            SELECT
                                payment.id,
                                payment.payment_type_id,
                                payment.time as certificate_date,
                                payment.amount,
                                payment.type_dps,
                                bar_reservation.id as bar_reservation_id,
                                bar_reservation.code as bar_code,
                                bar.id as bar_id,
                                bar.name as bar_name,
                                bar_table.name as bar_table_name
                            FROM
                                payment
                                inner join bar_reservation on bar_reservation.id = payment.bill_id
                                inner join bar on bar.id = bar_reservation.bar_id
                                inner join bar_reservation_table on bar_reservation_table.bar_reservation_id = bar_reservation.id
                                inner join bar_table on bar_table.id = bar_reservation_table.table_id
                            WHERE
                                payment.time >= \''.Date_Time::to_time($export_date).'\'
                                and payment.time < \''.(Date_Time::to_time($export_date)+86399).'\'
                                and payment.type = \'BAR\'
                                and payment.type_dps is null
                                and payment.payment_type_id != \'REFUND\' 
                            ORDER BY
                                payment.id ASC
            ');
            //System::debug($payment_bar);
            foreach($payment_bar as $key => $value)
            {
                $payment_bar_id = $value['id'].'_'.$value['bar_reservation_id'].$value['bar_id'];
                if(!isset($rooms[$payment_bar_id]['id']))
                {
                    $rooms[$payment_bar_id]['id'] = $payment_bar_id;
                    if($value['payment_type_id'] == 'CASH')
                    {
                        $rooms[$payment_bar_id]['certificate'] = 'PT'; 
                        $rooms[$payment_bar_id]['certificate_number'] = 'PT'.date('ymd', $value['certificate_date']).str_pad($r, 4,"0",STR_PAD_LEFT);
                        $rooms[$payment_bar_id]['account_no'] = 1111;  
                        $rooms[$payment_bar_id]['description'] = 'Thu tiền mặt hóa đơn số (' .$value['bar_code'].','.$value['bar_table_name'].')'; 
                    }else if($value['payment_type_id'] == 'CREDIT_CARD' or $value['payment_type_id'] == 'BANK')
                    {
                        $rooms[$payment_bar_id]['certificate'] = 'GBC'; 
                        $rooms[$payment_bar_id]['certificate_number'] = 'GBC'.date('ymd', $value['certificate_date']).str_pad($r, 4,"0",STR_PAD_LEFT);
                        $rooms[$payment_bar_id]['account_no'] = 1121;  
                        $rooms[$payment_bar_id]['description'] = 'Thanh toán thẻ cho hóa đơn số (' .$value['bar_code'].','.$value['bar_table_name'].')';                     
                    }else if($value['payment_type_id'] == 'FOC')
                    {
                        $rooms[$payment_bar_id]['certificate'] = 'PKT';
                        $rooms[$payment_bar_id]['certificate_number'] = 'PKT'.date('ymd', $value['certificate_date']).str_pad($r, 4,"0",STR_PAD_LEFT);
                        $rooms[$payment_bar_id]['account_no'] = 1388;
                        $rooms[$payment_bar_id]['description'] = 'Miễn phí cho hóa đơn số (' .$value['bar_code'].','.$value['bar_table_name'].')';
                    }else if($value['payment_type_id'] == 'DEBIT')
                    {
                        $rooms[$payment_bar_id]['certificate'] = 'PTN';
                        $rooms[$payment_bar_id]['certificate_number'] = 'PTN'.date('ymd', $value['certificate_date']).str_pad($r, 4,"0",STR_PAD_LEFT);
                        $rooms[$payment_bar_id]['account_no'] = 131;
                        $rooms[$payment_bar_id]['description'] = 'Công nợ cho hóa đơn số (' .$value['bar_code'].','.$value['bar_table_name'].')';
                    }
                    $rooms[$payment_bar_id]['certificate_date'] = date('d/m/Y', $value['certificate_date']);
                    $rooms[$payment_bar_id]['invoice_symbol'] = '';
                    $rooms[$payment_bar_id]['invoice_number'] = '';
                    $rooms[$payment_bar_id]['invoice_date'] = '';
                    $rooms[$payment_bar_id]['customer_no'] = '';
                    $rooms[$payment_bar_id]['product_import'] = '';
                    $rooms[$payment_bar_id]['account_co'] = 132;
                    $rooms[$payment_bar_id]['customer_co'] = 'KHACHLENH';
                    $rooms[$payment_bar_id]['list_code'] = '';
                    $rooms[$payment_bar_id]['list_name'] = '';
                    $rooms[$payment_bar_id]['unit'] = '';
                    $rooms[$payment_bar_id]['quantity'] = '';
                    $rooms[$payment_bar_id]['price_room_before_tax'] = '';
                    $rooms[$payment_bar_id]['total_before_tax'] = $value['amount'];
                    $rooms[$payment_bar_id]['pt_ck'] = '';
                    $rooms[$payment_bar_id]['ck'] = '';
                    $rooms[$payment_bar_id]['invoice_vat'] = '';
                    $rooms[$payment_bar_id]['account_tax'] = '';
                    $rooms[$payment_bar_id]['ts_gtgt'] = '';
                    $rooms[$payment_bar_id]['tax_vn'] = '';
                    $rooms[$payment_bar_id]['total_vnd_tt'] = '';
                    $rooms[$payment_bar_id]['customer_code'] = 'KHACHLENH';
                    $rooms[$payment_bar_id]['customer_name'] = 'Khách lẻ nhà hàng';
                    $rooms[$payment_bar_id]['customer_tax_code'] = '';
                    $rooms[$payment_bar_id]['customer_address'] = '';
                    $rooms[$payment_bar_id]['traveller_address'] = '';
                    $rooms[$payment_bar_id]['traveller_name'] = '';
                    $rooms[$payment_bar_id]['note'] = '';                     
                }
                $r++;
            }
            /** Bộ phận đặt tiệc */
                /** Khách ở phòng sử dụng dịch vụ tại khu tiệc và thanh toán về phòng. */
                /** Lấy sản phẩm trong tiệc */
                $partys = DB::fetch_all('
                                SELECT
                                    party_reservation_detail.id,
                                    party_reservation_detail.product_id,
                                    party_reservation_detail.product_name,
                                    party_reservation_detail.quantity,
                                    party_reservation_detail.price,
                                    party_reservation_detail.type as product_type,
                                    party_reservation_detail.product_unit as unit_name,
                                    party_reservation.id as party_id,
                                    party_type.name as party_name,
                                    party_reservation.checkin_time as certificate_date,
                                    party_reservation.vat as tax_rate,
                                    party_reservation.extra_service_rate as service_rate,
                                    \'KHACHLEBQ\' as customer_code,
                                    party_reservation.full_name as customer_name
                                FROM
                                    party_reservation_detail
                                    INNER JOIN party_reservation on party_reservation_detail.party_reservation_id = party_reservation.id
                                    INNER JOIN party_type on party_reservation.party_type = party_type.id
                                WHERE
                                    party_reservation.checkin_time >=\''.Date_Time::to_time($export_date).'\'
                                    AND party_reservation.checkin_time <=\''.(Date_Time::to_time($export_date)+ 86399).'\' 
                ');
                /** Lấy sản phẩm trong tiệc */
                /**  Lấy phòng tiệc */
                $partys_room = DB::fetch_all('
                                SELECT
                                    party_reservation.id ||\'_\'|| party_reservation_room.id as id,
                                    party_reservation_room.id as p_rr_id,
                                    party_room.name as product_id,
                                    party_room.group_name as product_name,
                                    1 as quantity,
                                    party_reservation_room.price,
                                    \'\' as product_type,
                                    \'\' as unit_name,
                                    party_reservation.id as party_id,
                                    party_type.name as party_name,
                                    party_reservation.checkin_time as certificate_date,
                                    party_reservation.vat as tax_rate,
                                    party_reservation.extra_service_rate as service_rate,
                                    \'KHACHLEBQ\' as customer_code,
                                    party_reservation.full_name as customer_name
                                FROM
                                    party_reservation_room
                                    INNER JOIN party_reservation on party_reservation_room.party_reservation_id = party_reservation.id
                                    INNER JOIN party_room on party_reservation_room.party_room_id = party_room.id 
                                    INNER JOIN party_type on party_reservation.party_type = party_type.id
                                WHERE
                                    party_reservation.checkin_time >=\''.Date_Time::to_time($export_date).'\'
                                    AND party_reservation.checkin_time <\''.(Date_Time::to_time($export_date)+86399).'\' 
                ');
                /**  Lấy phòng tiệc */
                $warehouse = DB::fetch('select warehouse.code as wh_code, warehouse.name as wh_name from portal_department inner join warehouse on portal_department.warehouse_id = warehouse.id where portal_department.portal_id = \''.PORTAL_ID.'\' and portal_department.department_code = \'BANQUET\' ');
                $partys = $partys_room + $partys;
                foreach($partys as $key => $value)
                {
                    $partys_id = $value['party_id'].'_'.$value['id'];
                    $partys_tax_id = $value['party_id'].'_'.$value['id'].'_'.$value['tax_rate'];
                    if(!isset($rooms[$partys_id]['id']))
                    {
                        $rooms[$partys_id]['id'] = $partys_id;
                        $rooms[$partys_id]['certificate'] = 'BQ';
                        $rooms[$partys_id]['invoice_symbol'] = '';
                        $rooms[$partys_id]['invoice_number'] = '';
                        $rooms[$partys_id]['invoice_date'] = '';
                        $rooms[$partys_id]['certificate_number'] = 'BQ'.date('ymd', $value['certificate_date']).str_pad($u, 4,"0",STR_PAD_LEFT);
                        $rooms[$partys_id]['certificate_date'] = date('d/m/Y', $value['certificate_date']);
                        $rooms[$partys_id]['description'] = 'Doanh thu tiệc '.$value['party_name'] .' ngày ('.date('d/m/Y', $value['certificate_date']).') '.$value['customer_code'];
                        $rooms[$partys_id]['account_no'] = 132;
                        $rooms[$partys_id]['customer_no'] = $value['customer_code'];
                        $rooms[$partys_id]['product_import'] = '';
                        if($value['product_type'] == 2)
                        {
                            $rooms[$partys_id]['account_co'] = 5111;                        
                        }elseif($value['product_type'] == 1)
                        {
                            $rooms[$partys_id]['account_co'] = 5112;                        
                        }elseif($value['product_type'] == 3)
                        {
                            $rooms[$partys_id]['account_co'] = 5113;                        
                        }else
                        {
                            $rooms[$partys_id]['account_co'] = '';                    
                        }
                        $rooms[$partys_id]['customer_co'] = $warehouse['wh_code'];
                        $rooms[$partys_id]['list_code'] = $value['product_id'];
                        $rooms[$partys_id]['list_name'] = $value['product_name'];
                        $rooms[$partys_id]['unit'] = $value['unit_name'];
                        $rooms[$partys_id]['quantity'] = $value['quantity'];
                        $rooms[$partys_id]['price_room_before_tax'] = (($value['price'])*(1+($value['service_rate']/100)));
                        $rooms[$partys_id]['total_before_tax'] = (($value['price'])*(1+($value['service_rate']/100))*$value['quantity']);
                        $rooms[$partys_id]['pt_ck'] = '';
                        $rooms[$partys_id]['ck'] = '';
                        $rooms[$partys_id]['invoice_vat'] = '';
                        $rooms[$partys_id]['account_tax'] = 33311;
                        $rooms[$partys_id]['ts_gtgt'] = $value['tax_rate'];
                        $rooms[$partys_id]['tax_vn'] = (($value['price'])*(1+($value['service_rate']/100))*($value['tax_rate']/100)*$value['quantity']);
                        $rooms[$partys_id]['total_vnd_tt'] = (($value['price'])*(1+($value['tax_rate']/100))*(1+($value['service_rate']/100))*$value['quantity']);
                        $rooms[$partys_id]['customer_code'] = $value['customer_code'];
                        $rooms[$partys_id]['customer_name'] = $value['customer_name'];
                        $rooms[$partys_id]['customer_tax_code'] = '';
                        $rooms[$partys_id]['customer_address'] = '';
                        $rooms[$partys_id]['traveller_address'] = '';
                        $rooms[$partys_id]['traveller_name'] = '';
                        $rooms[$partys_id]['note'] = '';
                    }
                    //thuế
                    if(!isset($rooms[$partys_tax_id]['id']))
                    {
                        $rooms[$partys_tax_id]['id'] = $partys_tax_id;
                        $rooms[$partys_tax_id]['certificate'] = 'BQ';
                        $rooms[$partys_tax_id]['invoice_symbol'] = '';
                        $rooms[$partys_tax_id]['invoice_number'] = '';
                        $rooms[$partys_tax_id]['invoice_date'] = '';
                        $rooms[$partys_tax_id]['certificate_number'] = 'BQ'.date('ymd', $value['certificate_date']).str_pad($u, 4,"0",STR_PAD_LEFT);
                        $rooms[$partys_tax_id]['certificate_date'] = date('d/m/Y', $value['certificate_date']);
                        $rooms[$partys_tax_id]['description'] = 'Thuế GTGT của hàng hóa '.$value['product_name'] .' ngày ('.date('d/m/Y', $value['certificate_date']).') '.$value['customer_code'];
                        $rooms[$partys_tax_id]['account_no'] = 132;
                        $rooms[$partys_tax_id]['customer_no'] = $value['customer_code'];
                        $rooms[$partys_tax_id]['product_import'] = '';
                        $rooms[$partys_tax_id]['account_co'] = 33311;
                        $rooms[$partys_tax_id]['customer_co'] = '';
                        $rooms[$partys_tax_id]['list_code'] = '';
                        $rooms[$partys_tax_id]['list_name'] = '';
                        $rooms[$partys_tax_id]['unit'] = '';
                        $rooms[$partys_tax_id]['quantity'] = '';
                        $rooms[$partys_tax_id]['price_room_before_tax'] = '';
                        $rooms[$partys_tax_id]['total_before_tax'] = (($value['price'])*(1+($value['service_rate']/100))*($value['tax_rate']/100)*$value['quantity']);
                        $rooms[$partys_tax_id]['pt_ck'] = '';
                        $rooms[$partys_tax_id]['ck'] = '';
                        $rooms[$partys_tax_id]['invoice_vat'] = '';
                        $rooms[$partys_tax_id]['account_tax'] = '';
                        $rooms[$partys_tax_id]['ts_gtgt'] = '';
                        $rooms[$partys_tax_id]['tax_vn'] = '';
                        $rooms[$partys_tax_id]['total_vnd_tt'] = (($value['price'])*(1+($value['service_rate']/100))*($value['tax_rate']/100)*$value['quantity']);
                        $rooms[$partys_tax_id]['customer_code'] = $value['customer_code'];
                        $rooms[$partys_tax_id]['customer_name'] = $value['customer_name'];
                        $rooms[$partys_tax_id]['customer_tax_code'] = '';
                        $rooms[$partys_tax_id]['customer_address'] = '';
                        $rooms[$partys_tax_id]['traveller_address'] = '';
                        $rooms[$partys_tax_id]['traveller_name'] = '';
                        $rooms[$partys_tax_id]['note'] = '';
                    }
                    $u++;
                }
                /** Khách ở phòng sử dụng dịch vụ tại khu tiệc và thanh toán về phòng. */
                
                /** Thanh toán hóa đơn tiệc */
                $payment_bq = DB::fetch_all('
                                    SELECT
                                        payment.id,
                                        payment.payment_type_id,
                                        payment.time as certificate_date,
                                        payment.amount,
                                        payment.type_dps,
                                        party_reservation.id as party_id,
                                        \'KHACHLEBQ\' as customer_code,
                                        party_reservation.full_name as customer_name,
                                        party_type.name as party_name
                                    FROM
                                        payment
                                        inner join party_reservation on party_reservation.id = payment.bill_id
                                        INNER JOIN party_type on party_reservation.party_type = party_type.id
                                    WHERE
                                        payment.time >= \''.Date_Time::to_time($export_date).'\'
                                        and payment.time < \''.(Date_Time::to_time($export_date)+86399).'\'
                                        and payment.type = \'BANQUET\'
                                        and payment.type_dps is null
                                        and payment.payment_type_id != \'REFUND\' 
                                    ORDER BY
                                        payment.id ASC
                ');
                //System::debug($payment_bq);exit();
                foreach($payment_bq as $key => $value)
                {
                    $payment_bq_id = $value['id'].'_'.$value['party_id'];
                    if(!isset($rooms[$payment_bq_id]['id']))
                    {
                        $rooms[$payment_bq_id]['id'] = $payment_bq_id;
                        if($value['payment_type_id'] == 'CASH')
                        {
                            $rooms[$payment_bq_id]['certificate'] = 'PT';
                            $rooms[$payment_bq_id]['certificate_number'] = 'PT'.date('ymd', $value['certificate_date']).str_pad($o, 4,"0",STR_PAD_LEFT);
                            $rooms[$payment_bq_id]['description'] = 'Thu tiền mặt hóa đơn số (' .$value['party_id'].','.$value['party_name'].')';
                            $rooms[$payment_bq_id]['account_no'] = 1111;     
                        }else if($value['payment_type_id'] == 'CREDIT_CARD' or $value['payment_type_id'] == 'BANK')
                        {
                            $rooms[$payment_bq_id]['certificate'] = 'GBC'; 
                            $rooms[$payment_bq_id]['certificate_number'] = 'GBC'.date('ymd', $value['certificate_date']).str_pad($o, 4,"0",STR_PAD_LEFT);
                            $rooms[$payment_bq_id]['description'] = 'Thanh toán thẻ cho hóa đơn số (' .$value['party_id'].','.$value['party_name'].')';
                            $rooms[$payment_bq_id]['account_no'] = 1121;                       
                        }else if($value['payment_type_id'] == 'FOC')
                        {
                            $rooms[$payment_bq_id]['certificate'] = 'PKT';
                            $rooms[$payment_bq_id]['certificate_number'] = 'PKT'.date('ymd', $value['certificate_date']).str_pad($o, 4,"0",STR_PAD_LEFT);
                            $rooms[$payment_bq_id]['description'] = 'Miễn phí cho hóa đơn số (' .$value['party_id'].','.$value['party_name'].')';
                            $rooms[$payment_bq_id]['account_no'] = 1388;
                        }else if($value['payment_type_id'] == 'DEBIT')
                        {
                            $rooms[$payment_bq_id]['certificate'] = 'PTN';
                            $rooms[$payment_bq_id]['certificate_number'] = 'PTN'.date('ymd', $value['certificate_date']).str_pad($o, 4,"0",STR_PAD_LEFT);
                            $rooms[$payment_bq_id]['description'] = 'Công nợ cho hóa đơn số (' .$value['party_id'].','.$value['party_name'].')';
                            $rooms[$payment_bq_id]['account_no'] = 131;
                        }
                        $rooms[$payment_bq_id]['invoice_symbol'] = '';
                        $rooms[$payment_bq_id]['invoice_number'] = '';
                        $rooms[$payment_bq_id]['invoice_date'] = '';
                        $rooms[$payment_bq_id]['certificate_date'] = date('d/m/Y', $value['certificate_date']);
                        $rooms[$payment_bq_id]['customer_no'] = '';
                        $rooms[$payment_bq_id]['product_import'] = '';
                        $rooms[$payment_bq_id]['account_co'] = 132;
                        $rooms[$payment_bq_id]['customer_co'] = $value['customer_code'];
                        $rooms[$payment_bq_id]['list_code'] = '';
                        $rooms[$payment_bq_id]['list_name'] = '';
                        $rooms[$payment_bq_id]['unit'] = '';
                        $rooms[$payment_bq_id]['quantity'] = '';
                        $rooms[$payment_bq_id]['price_room_before_tax'] = '';
                        $rooms[$payment_bq_id]['total_before_tax'] = $value['amount'];
                        $rooms[$payment_bq_id]['pt_ck'] = '';
                        $rooms[$payment_bq_id]['ck'] = '';
                        $rooms[$payment_bq_id]['invoice_vat'] = '';
                        $rooms[$payment_bq_id]['account_tax'] = '';
                        $rooms[$payment_bq_id]['ts_gtgt'] = '';
                        $rooms[$payment_bq_id]['tax_vn'] = '';
                        $rooms[$payment_bq_id]['total_vnd_tt'] = '';
                        $rooms[$payment_bq_id]['customer_code'] = $value['customer_code'];
                        $rooms[$payment_bq_id]['customer_name'] = $value['customer_name'];
                        $rooms[$payment_bq_id]['customer_tax_code'] = '';
                        $rooms[$payment_bq_id]['customer_address'] = '';
                        $rooms[$payment_bq_id]['traveller_address'] = '';
                        $rooms[$payment_bq_id]['traveller_name'] = '';
                        $rooms[$payment_bq_id]['note'] = '';                     
                    }
                    $o++;
                }
                /** Thanh toán hóa đơn tiệc */
            /** Bộ phận đặt tiệc */ 
            
            /** Xuất dữ liệu Kho */
                /** Phiếu nhập kho */
                $import_warehouse = DB::fetch_all('
                                    SELECT
                                        wh_invoice_detail.id,
                                        product.id as product_id,
                                        product.name_'.Portal::language().' as product_name,
                                        product.type as product_type,
                                        unit.name_'.Portal::language().' as unit_name,
                                        wh_invoice_detail.num as quantity,
                                        wh_invoice_detail.price,
                                        wh_invoice_detail.payment_price,
                                        wh_invoice.bill_number,
                                        wh_invoice.time as certificate_date,
                                        wh_invoice.note as description,
                                        wh_invoice.supplier_id,
                                        wh_invoice.type as wh_type,
                                        supplier.code as sp_code,
                                        supplier.name as sp_name,
                                        supplier.address as sp_address,
                                        supplier.tax_code,
                                        warehouse.code as wh_code,
                                        warehouse.name as wh_name,
                                        party.full_name
                                    FROM
                                        wh_invoice_detail
                                        INNER JOIN wh_invoice on wh_invoice_detail.invoice_id = wh_invoice.id
                                        INNER JOIN product on wh_invoice_detail.product_id = product.id
                                        INNER JOIN unit on product.unit_id = unit.id
                                        LEFT JOIN supplier on wh_invoice.supplier_id = supplier.id
                                        INNER JOIN warehouse on warehouse.id = wh_invoice.warehouse_id
                                        INNER JOIN party on party.user_id = wh_invoice.user_id
                                    WHERE
                                        wh_invoice.time >= \''.Date_Time::to_time($export_date).'\'
                                        and wh_invoice.time < \''.(Date_Time::to_time($export_date)+86399).'\'
                                        and wh_invoice.type = \'IMPORT\'
                                        and wh_invoice.move_product is null
                ');
                $p = 1;
                //System::debug($import_warehouse);exit();
                $invoice_import = '';
                foreach($import_warehouse as $key => $value)
                {
                    if($invoice_import=='')
                    {
                        $invoice_import = $value['bill_number'];
                    }
                    else
                    {
                        if($value['bill_number'] !=$invoice_import)
                        {
                            $p++;
                            $invoice_import=$value['bill_number'];
                        }
                    }
                    $import_warehouse_id = $value['id'].'_'.$value['bill_number'].'_'.$value['product_id'].'_'.$value['wh_type'];
                    if(!isset($rooms[$import_warehouse_id]['id']))
                    {
                        $rooms[$import_warehouse_id]['id'] = $import_warehouse_id;
                        $rooms[$import_warehouse_id]['certificate'] = 'PN ';
                        $rooms[$import_warehouse_id]['invoice_symbol'] = '';
                        $rooms[$import_warehouse_id]['invoice_number'] = '';
                        $rooms[$import_warehouse_id]['invoice_date'] = '';
                        $rooms[$import_warehouse_id]['certificate_number'] = 'PN'.date('ymd', $value['certificate_date']).str_pad($p, 4,"0",STR_PAD_LEFT);
                        $rooms[$import_warehouse_id]['certificate_date'] = date('d/m/Y', $value['certificate_date']);
                        $rooms[$import_warehouse_id]['description'] = $value['description'];
                        if($value['product_type'] == 'MATERIAL')
                        {
                            $rooms[$import_warehouse_id]['account_no'] = 152;                            
                        }elseif($value['product_type'] == 'TOOLS' or $value['product_type'] == 'EQUIPMENT')
                        {
                            $rooms[$import_warehouse_id]['account_no'] = 153;                            
                        }elseif($value['product_type'] == 'PRODUCT' or $value['product_type'] == 'DRINK')
                        {
                            $rooms[$import_warehouse_id]['account_no'] = 155;                            
                        }elseif($value['product_type'] == 'GOODS')
                        {
                            $rooms[$import_warehouse_id]['account_no'] = 156;                            
                        }
                        $rooms[$import_warehouse_id]['customer_no'] = $value['wh_code'];
                        $rooms[$import_warehouse_id]['product_import'] = $value['product_id'];
                        if($value['supplier_id'] == '')
                        {
                            $rooms[$import_warehouse_id]['account_co'] = 338;
                        }else
                        {
                            $rooms[$import_warehouse_id]['account_co'] = 331;
                        }
                        $rooms[$import_warehouse_id]['customer_co'] = $value['wh_code'];
                        $rooms[$import_warehouse_id]['list_code'] = '';
                        $rooms[$import_warehouse_id]['list_name'] = $value['product_name'];
                        $rooms[$import_warehouse_id]['unit'] = $value['unit_name'];
                        $rooms[$import_warehouse_id]['quantity'] = $value['quantity'];
                        $rooms[$import_warehouse_id]['price_room_before_tax'] = $value['price'];
                        $rooms[$import_warehouse_id]['total_before_tax'] = ($value['price']*$value['quantity']);
                        $rooms[$import_warehouse_id]['pt_ck'] = '';
                        $rooms[$import_warehouse_id]['ck'] = '';
                        $rooms[$import_warehouse_id]['invoice_vat'] = 'X';
                        $rooms[$import_warehouse_id]['account_tax'] = '';
                        $rooms[$import_warehouse_id]['ts_gtgt'] = '';
                        $rooms[$import_warehouse_id]['tax_vn'] = '';
                        $rooms[$import_warehouse_id]['total_vnd_tt'] = $value['payment_price'];
                        $rooms[$import_warehouse_id]['customer_code'] = $value['sp_code'];
                        $rooms[$import_warehouse_id]['customer_name'] = $value['sp_name'];
                        $rooms[$import_warehouse_id]['customer_tax_code'] = $value['tax_code'];
                        $rooms[$import_warehouse_id]['customer_address'] = $value['sp_address'];
                        $rooms[$import_warehouse_id]['traveller_address'] = '';
                        $rooms[$import_warehouse_id]['traveller_name'] = $value['full_name'];
                        $rooms[$import_warehouse_id]['note'] = '';
                    }
                }
                /** Phiếu nhập kho */
                
                /** Phiếu xuất kho */
                $export_warehouse = DB::fetch_all('
                                    SELECT
                                        wh_invoice_detail.id,
                                        product.id as product_id,
                                        product.name_'.Portal::language().' as product_name,
                                        product.type as product_type,
                                        unit.name_'.Portal::language().' as unit_name,
                                        wh_invoice_detail.num as quantity,
                                        wh_invoice_detail.price,
                                        wh_invoice_detail.payment_price,
                                        wh_invoice.id as wh_invoice_id,
                                        wh_invoice.bill_number,
                                        wh_invoice.time as certificate_date,
                                        wh_invoice.note as description,
                                        wh_invoice.supplier_id,
                                        wh_invoice.type as wh_type,
                                        supplier.code as sp_code,
                                        supplier.name as sp_name,
                                        supplier.address as sp_address,
                                        supplier.tax_code,
                                        warehouse.code as wh_code,
                                        warehouse.name as wh_name,
                                        party.full_name
                                    FROM
                                        wh_invoice_detail
                                        INNER JOIN wh_invoice on wh_invoice_detail.invoice_id = wh_invoice.id
                                        INNER JOIN product on wh_invoice_detail.product_id = product.id
                                        INNER JOIN unit on product.unit_id = unit.id
                                        LEFT JOIN supplier on wh_invoice.supplier_id = supplier.id
                                        LEFT JOIN warehouse on warehouse.id = wh_invoice.warehouse_id
                                        INNER JOIN party on party.user_id = wh_invoice.user_id
                                    WHERE
                                        wh_invoice.time >= \''.Date_Time::to_time($export_date).'\'
                                        and wh_invoice.time < \''.(Date_Time::to_time($export_date)+86399).'\'
                                        and wh_invoice.type = \'EXPORT\'
                                        and wh_invoice.move_product is null
                                    order by wh_invoice.id
                ');
                //System::debug($export_warehouse);exit();
                $a = 1;
                $invoice_export = '';
                foreach($export_warehouse as $key => $value)
                {
                    if($invoice_export=='')
                    {
                        $invoice_export = $value['wh_invoice_id'];
                    }
                    else
                    {
                        if($value['wh_invoice_id'] !=$invoice_export)
                        {
                            $a++;
                            $invoice_export=$value['wh_invoice_id'];
                        }
                    }
                    $export_warehouse_id = $value['id'].'_'.$value['bill_number'].'_'.$value['product_id'].'_'.$value['wh_type'];
                    if(!isset($rooms[$export_warehouse_id]['id']))
                    {
                        $rooms[$export_warehouse_id]['id'] = $export_warehouse_id;
                        $rooms[$export_warehouse_id]['certificate'] = 'PX ';
                        $rooms[$export_warehouse_id]['invoice_symbol'] = '';
                        $rooms[$export_warehouse_id]['invoice_number'] = '';
                        $rooms[$export_warehouse_id]['invoice_date'] = '';
                        $rooms[$export_warehouse_id]['certificate_number'] = 'PX'.date('ymd', $value['certificate_date']).str_pad($a, 4,"0",STR_PAD_LEFT);
                        $rooms[$export_warehouse_id]['certificate_date'] = date('d/m/Y', $value['certificate_date']);
                        $rooms[$export_warehouse_id]['description'] = $value['description'];
                        if($value['supplier_id'] == 0)
                        {
                            $rooms[$export_warehouse_id]['account_no'] = 642; 
                            $rooms[$export_warehouse_id]['account_co'] = 512;                         
                        }else
                        {
                            $rooms[$export_warehouse_id]['account_no'] = 331; 
                            if($value['product_type'] == 'MATERIAL')
                            {
                                $rooms[$export_warehouse_id]['account_co'] = 152;                            
                            }elseif($value['product_type'] == 'TOOLS' or $value['product_type'] == 'EQUIPMENT')
                            {
                                $rooms[$export_warehouse_id]['account_co'] = 153;                            
                            }elseif($value['product_type'] == 'PRODUCT' or $value['product_type'] == 'DRINK')
                            {
                                $rooms[$export_warehouse_id]['account_co'] = 155;                            
                            }elseif($value['product_type'] == 'GOODS')
                            {
                                $rooms[$export_warehouse_id]['account_co'] = 156;                            
                            }                          
                        }
                        $rooms[$export_warehouse_id]['customer_no'] = $value['wh_code'];
                        $rooms[$export_warehouse_id]['product_import'] = '';
                        $rooms[$export_warehouse_id]['customer_co'] = $value['wh_code'];
                        $rooms[$export_warehouse_id]['list_code'] = $value['product_id'];
                        $rooms[$export_warehouse_id]['list_name'] = $value['product_name'];
                        $rooms[$export_warehouse_id]['unit'] = $value['unit_name'];
                        $rooms[$export_warehouse_id]['quantity'] = $value['quantity'];
                        $rooms[$export_warehouse_id]['price_room_before_tax'] = $value['price'];
                        $rooms[$export_warehouse_id]['total_before_tax'] = ($value['price']*$value['quantity']);
                        $rooms[$export_warehouse_id]['pt_ck'] = '';
                        $rooms[$export_warehouse_id]['ck'] = '';
                        $rooms[$export_warehouse_id]['invoice_vat'] = 'X';
                        $rooms[$export_warehouse_id]['account_tax'] = '';
                        $rooms[$export_warehouse_id]['ts_gtgt'] = '';
                        $rooms[$export_warehouse_id]['tax_vn'] = '';
                        $rooms[$export_warehouse_id]['total_vnd_tt'] = $value['payment_price'];
                        $rooms[$export_warehouse_id]['customer_code'] = $value['sp_code'];
                        $rooms[$export_warehouse_id]['customer_name'] = $value['sp_name'];
                        $rooms[$export_warehouse_id]['customer_tax_code'] = $value['tax_code'];
                        $rooms[$export_warehouse_id]['customer_address'] = $value['sp_address'];
                        $rooms[$export_warehouse_id]['traveller_address'] = '';
                        $rooms[$export_warehouse_id]['traveller_name'] = $value['full_name'];
                        $rooms[$export_warehouse_id]['note'] = '';
                    }
                }
                /** Phiếu xuất kho */
                
                /** Phiểu chuyển */
                $move_warehouse_to = DB::fetch_all('
                                    SELECT
                                        wh_invoice_detail.id,
                                        product.id as product_id,
                                        product.name_'.Portal::language().' as product_name,
                                        product.type as product_type,
                                        unit.name_'.Portal::language().' as unit_name,
                                        wh_invoice_detail.num as quantity,
                                        wh_invoice_detail.price,
                                        wh_invoice_detail.payment_price,
                                        wh_invoice_detail.to_warehouse_id,
                                        wh_invoice.id as invoice_id,
                                        wh_invoice.bill_number,
                                        wh_invoice.time as certificate_date,
                                        wh_invoice.note as description,
                                        wh_invoice.supplier_id,
                                        wh_invoice.type as wh_type,
                                        wh_invoice.along_receipt,
                                        supplier.code as sp_code,
                                        supplier.name as sp_name,
                                        supplier.address as sp_address,
                                        supplier.tax_code,
                                        warehouse.code as wh_code,
                                        warehouse.name as wh_name,
                                        party.full_name,
                                        \'\' as wh_code_export,
                                        \'\' as wh_name_export,
                                        \'\' as full_name_export
                                    FROM
                                        wh_invoice_detail
                                        INNER JOIN wh_invoice on wh_invoice_detail.invoice_id = wh_invoice.id
                                        INNER JOIN product on wh_invoice_detail.product_id = product.id
                                        INNER JOIN unit on product.unit_id = unit.id
                                        LEFT JOIN supplier on wh_invoice.supplier_id = supplier.id
                                        INNER JOIN warehouse on warehouse.id = wh_invoice.warehouse_id
                                        INNER JOIN party on party.user_id = wh_invoice.user_id
                                    WHERE
                                        wh_invoice.time >= \''.Date_Time::to_time($export_date).'\'
                                        and wh_invoice.time < \''.(Date_Time::to_time($export_date)+86399).'\'
                                        and wh_invoice.type = \'IMPORT\'
                                        and wh_invoice.move_product is not null
                ');
                $export_warehouse_arr = DB::fetch_all('
                                        SELECT
                                            wh_invoice.id,
                                            warehouse.code as wh_code,
                                            warehouse.name as wh_name,
                                            party.full_name
                                        FROM
                                            wh_invoice
                                            INNER JOIN warehouse on warehouse.id = wh_invoice.warehouse_id
                                            INNER JOIN party on party.user_id = wh_invoice.user_id
                                        WHERE
                                            wh_invoice.time >= \''.Date_Time::to_time($export_date).'\'
                                            and wh_invoice.time < \''.(Date_Time::to_time($export_date)+86399).'\'
                                            and wh_invoice.type = \'EXPORT\'
                                            and wh_invoice.move_product is not null
                ');
                foreach($move_warehouse_to as $key => $value)
                {
                    if(isset($export_warehouse_arr[$value['along_receipt']]))
                    {
                        $move_warehouse_to[$key]['wh_code_export'] = $export_warehouse_arr[$value['along_receipt']]['wh_code'];
                        $move_warehouse_to[$key]['wh_name_export'] = $export_warehouse_arr[$value['along_receipt']]['wh_name'];
                        $move_warehouse_to[$key]['full_name_export'] = $export_warehouse_arr[$value['along_receipt']]['full_name'];
                    }
                }
                $b = 1;
                foreach($move_warehouse_to as $key => $value)
                {
                    $move_warehouse_to_id = $value['id'].'_'.$value['invoice_id'].'_'.$value['along_receipt'];
                    if(!isset($rooms[$move_warehouse_to_id]['id']))
                    {
                        $rooms[$move_warehouse_to_id]['id'] = $move_warehouse_to_id;
                        $rooms[$move_warehouse_to_id]['certificate'] = 'PDC';
                        $rooms[$move_warehouse_to_id]['invoice_symbol'] = '';
                        $rooms[$move_warehouse_to_id]['invoice_number'] = '';
                        $rooms[$move_warehouse_to_id]['invoice_date'] = '';
                        $rooms[$move_warehouse_to_id]['certificate_number'] = 'PDC'.date('ymd', $value['certificate_date']).str_pad($b, 4,"0",STR_PAD_LEFT);
                        $rooms[$move_warehouse_to_id]['certificate_date'] = date('d/m/Y', $value['certificate_date']);
                        $rooms[$move_warehouse_to_id]['description'] = $value['description'];
                        if($value['product_type'] == 'MATERIAL')
                        {
                            $rooms[$move_warehouse_to_id]['account_no'] = 152;
                            $rooms[$move_warehouse_to_id]['account_co'] = 152;                            
                        }elseif($value['product_type'] == 'TOOLS' or $value['product_type'] == 'EQUIPMENT')
                        {
                            $rooms[$move_warehouse_to_id]['account_no'] = 153; 
                            $rooms[$move_warehouse_to_id]['account_co'] = 153;                           
                        }elseif($value['product_type'] == 'PRODUCT' or $value['product_type'] == 'DRINK')
                        {
                            $rooms[$move_warehouse_to_id]['account_no'] = 155;
                            $rooms[$move_warehouse_to_id]['account_co'] = 155;                            
                        }elseif($value['product_type'] == 'GOODS')
                        {
                            $rooms[$move_warehouse_to_id]['account_no'] = 156; 
                            $rooms[$move_warehouse_to_id]['account_co'] = 156;                           
                        }
                        $rooms[$move_warehouse_to_id]['customer_no'] = $value['wh_code'];
                        $rooms[$move_warehouse_to_id]['product_import'] = $value['product_id'];
                        $rooms[$move_warehouse_to_id]['customer_co'] = $value['wh_code_export'];
                        $rooms[$move_warehouse_to_id]['list_code'] = $value['product_id'];
                        $rooms[$move_warehouse_to_id]['list_name'] = $value['product_name'];
                        $rooms[$move_warehouse_to_id]['unit'] = $value['unit_name'];
                        $rooms[$move_warehouse_to_id]['quantity'] = $value['quantity'];
                        $rooms[$move_warehouse_to_id]['price_room_before_tax'] = $value['price'];
                        $rooms[$move_warehouse_to_id]['total_before_tax'] = ($value['price']*$value['quantity']);
                        $rooms[$move_warehouse_to_id]['pt_ck'] = '';
                        $rooms[$move_warehouse_to_id]['ck'] = '';
                        $rooms[$move_warehouse_to_id]['invoice_vat'] = 'X';
                        $rooms[$move_warehouse_to_id]['account_tax'] = '';
                        $rooms[$move_warehouse_to_id]['ts_gtgt'] = '';
                        $rooms[$move_warehouse_to_id]['tax_vn'] = '';
                        $rooms[$move_warehouse_to_id]['total_vnd_tt'] = $value['payment_price'];
                        $rooms[$move_warehouse_to_id]['customer_code'] = $value['sp_code'];
                        $rooms[$move_warehouse_to_id]['customer_name'] = $value['sp_name'];
                        $rooms[$move_warehouse_to_id]['customer_tax_code'] = $value['tax_code'];
                        $rooms[$move_warehouse_to_id]['customer_address'] = $value['sp_address'];
                        $rooms[$move_warehouse_to_id]['traveller_address'] = '';
                        $rooms[$move_warehouse_to_id]['traveller_name'] = $value['full_name'];
                        $rooms[$move_warehouse_to_id]['note'] = '';
                    }
                    $b++;
                }
                /** Phiếu chuyển */
            /** Xuất dữ liệu Kho */
            
            /** Phát sinh của lễ tân */
            
            /** Danh mục */
            
            /** Danh mục */
            
            /** Danh mục đối tượng */
            
            /** Danh mục đối tượng */
            
            /** Hàng hóa vật tư */
            $product = DB::fetch_all('
                                SELECT
                                    product.id,
                                    \'\' as tk, 
                                    product.id as code,
                                    product.name_'.Portal::language().' as product_name,
                                    unit.name_'.Portal::language().' as unit_name,
                                    product_category.code as category_code,
                                    product.type
                                FROM
                                    product
                                    inner join product_category on product.category_id = product_category.id
                                    inner join product_price_list on product.id = product_price_list.product_id
                                    inner join unit on product.unit_id = unit.id
                                WHERE
                                    1=1
                                ORDER BY
                                    product.id asc
                                    
                                    
            ');
            foreach($product as $key => $value)
            {
                if($value['type'] == 'GOODS')
                {
                    $product[$key]['tk'] = '156';
                }elseif($value['type'] == 'PRODUCT' or $value['type'] == 'DRINK')
                {
                    $product[$key]['tk'] = '155';
                }elseif($value['type'] == 'MATERIAL')
                {
                    $product[$key]['tk'] = '152';
                }elseif($value['type'] == 'TOOLS' or $value['type'] == 'EQUIPMENT')
                {
                    $product[$key]['tk'] = '153';                    
                }
            }
            /** Hàng hóa vật tư */
            
            /** Danh mục chứng từ */
            $certificate_list = array(
                "PNK" => "PHIẾU NHẬP KHO",
                "PXK" => "PHIẾU XUẤT KHO",
                "PCK" => "PHIẾU ĐIỀU CHUYỂN",
                "PT" => "PHIẾU THU",
                "PC" => "PHIẾU CHI",
                "PTN" => "PHIẾU THU NỢ",
                "PKT" => "PHIẾU KẾ TOÁN KHÁC",
                "HDBR" => "HÓA ĐƠN BÁN HÀNG",
                "HD" => "HÓA ĐƠN DỊCH VỤ",
                "GBN" => "PHIẾU BÁO NỢ",
                "GBC" => "PHIẾU BÁO CÓ",
                "TP" => "TIỀN PHÒNG",
                "PGG" => "PHIẾU GIẢM GIÁ");
            /** Danh mục chứng từ */
            //System::debug($rooms);exit();
            $this->ExportDataFromSoftwareNewway($rooms,$product,$certificate_list,$export_date);
        } 
    }
    function ExportDataFromSoftwareNewway($rooms,$product,$certificate_list,$export_date)
    {
        require_once ROOT_PATH.'packages/core/includes/utils/PHPExcel.php';		
  		require_once ROOT_PATH.'packages/core/includes/utils/PHPExcel/RichText.php';
  		include ROOT_PATH.'packages/core/includes/utils/PHPExcel/IOFactory.php';
        $styleArray = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
                'size'  => 11,
                'name'  => 'Calibri'
        ));
        
        $objPHPExcel = new PHPExcel();
        $i = 1;
        $k = 1;
        $j = 1;
        $h = 1;
        $l = 1;
        /** Xuất ra sheet phát sinh */
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet(0)->setTitle("PHATSINH");
        $objPHPExcel->getActiveSheet(0)->getStyle('A1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('B1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('C1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('D1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('E1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('F1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('G1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('H1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('I1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('J1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('K1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('L1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('M1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('N1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('O1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('P1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('Q1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('R1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('S1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('T1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('U1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('V1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('W1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('X1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('Y1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('Z1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('AA1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('AB1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('AC1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('AD1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('AE1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->getStyle('AF1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(0)->setCellValue('A'.$l, 'LCTG');
        $objPHPExcel->getActiveSheet(0)->setCellValue('B'.$l, 'SR_HD');
        $objPHPExcel->getActiveSheet(0)->setCellValue('C'.$l, 'SO_HD');
        $objPHPExcel->getActiveSheet(0)->setCellValue('D'.$l, 'NGAY_HD');
        $objPHPExcel->getActiveSheet(0)->setCellValue('E'.$l, 'SOCT');
        $objPHPExcel->getActiveSheet(0)->setCellValue('F'.$l, 'NGAYCT');
        $objPHPExcel->getActiveSheet(0)->setCellValue('G'.$l, 'DIENGIAI');
        $objPHPExcel->getActiveSheet(0)->setCellValue('H'.$l, 'TKNO');
        $objPHPExcel->getActiveSheet(0)->setCellValue('I'.$l, 'MADTPNNO');
        $objPHPExcel->getActiveSheet(0)->setCellValue('J'.$l, 'MADMNO');
        $objPHPExcel->getActiveSheet(0)->setCellValue('K'.$l, 'TKCO');
        $objPHPExcel->getActiveSheet(0)->setCellValue('L'.$l, 'MADTPNCO');
        $objPHPExcel->getActiveSheet(0)->setCellValue('M'.$l, 'MADMCO');
        $objPHPExcel->getActiveSheet(0)->setCellValue('N'.$l, 'TENDM');
        $objPHPExcel->getActiveSheet(0)->setCellValue('O'.$l, 'DONVI');
        $objPHPExcel->getActiveSheet(0)->setCellValue('P'.$l, 'LUONG');
        $objPHPExcel->getActiveSheet(0)->setCellValue('Q'.$l, 'DGVND');
        $objPHPExcel->getActiveSheet(0)->setCellValue('R'.$l, 'TTVND');
        $objPHPExcel->getActiveSheet(0)->setCellValue('S'.$l, 'PT_CK');
        $objPHPExcel->getActiveSheet(0)->setCellValue('T'.$l, 'CHIETKHAU');
        $objPHPExcel->getActiveSheet(0)->setCellValue('U'.$l, 'HDVAT');
        $objPHPExcel->getActiveSheet(0)->setCellValue('V'.$l, 'TKTHUE');
        $objPHPExcel->getActiveSheet(0)->setCellValue('W'.$l, 'TS_GTGT');
        $objPHPExcel->getActiveSheet(0)->setCellValue('X'.$l, 'THUEVND');
        $objPHPExcel->getActiveSheet(0)->setCellValue('Y'.$l, 'TTVND_TT');
        $objPHPExcel->getActiveSheet(0)->setCellValue('Z'.$l, 'MAKH');
        $objPHPExcel->getActiveSheet(0)->setCellValue('AA'.$l, 'TENKH');
        $objPHPExcel->getActiveSheet(0)->setCellValue('AB'.$l, 'MS_DN');
        $objPHPExcel->getActiveSheet(0)->setCellValue('AC'.$l, 'DIACHI');
        $objPHPExcel->getActiveSheet(0)->setCellValue('AD'.$l, 'DIACHI_NGD');
        $objPHPExcel->getActiveSheet(0)->setCellValue('AE'.$l, 'KHACHHANG');
        $objPHPExcel->getActiveSheet(0)->setCellValue('AF'.$l, 'GHICHU');
        $l = 2;
        foreach($rooms as $key => $value)
        {
            $objPHPExcel->getActiveSheet(0)->getStyle('Q'.$l)->getNumberFormat()->setFormatCode('#,##0.#0');
            $objPHPExcel->getActiveSheet(0)->getStyle('R'.$l)->getNumberFormat()->setFormatCode('#,##0.#0');
            $objPHPExcel->getActiveSheet(0)->getStyle('T'.$l)->getNumberFormat()->setFormatCode('#,##0.#0');
            $objPHPExcel->getActiveSheet(0)->getStyle('X'.$l)->getNumberFormat()->setFormatCode('#,##0.#0');
            $objPHPExcel->getActiveSheet(0)->getStyle('Y'.$l)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet(0)->setCellValue('A'.$l, $value['certificate']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('B'.$l, $value['invoice_symbol']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('C'.$l, $value['invoice_number']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('D'.$l, $value['invoice_date']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('E'.$l, $value['certificate_number']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('F'.$l, $value['certificate_date']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('G'.$l, $value['description']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('H'.$l, $value['account_no']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('I'.$l, $value['customer_no']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('J'.$l, $value['product_import']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('K'.$l, $value['account_co']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('L'.$l, $value['customer_co']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('M'.$l, $value['list_code']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('N'.$l, $value['list_name']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('O'.$l, $value['unit']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('P'.$l, $value['quantity']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('Q'.$l, $value['price_room_before_tax']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('R'.$l, $value['total_before_tax']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('S'.$l, $value['pt_ck']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('T'.$l, $value['ck']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('U'.$l, $value['invoice_vat']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('V'.$l, $value['account_tax']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('W'.$l, $value['ts_gtgt']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('X'.$l, $value['tax_vn']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('Y'.$l, $value['total_vnd_tt']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('Z'.$l, $value['customer_code']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('AA'.$l, $value['customer_name']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('AB'.$l, $value['customer_tax_code']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('AC'.$l, $value['customer_address']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('AD'.$l, $value['traveller_address']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('AE'.$l, $value['traveller_name']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('AF'.$l, $value['note']);
            $l++;
        }
        /** Xuất ra sheet phát sinh */
        
        /** Xuất ra sheet nhóm danh mục */
        $objPHPExcel->createSheet(1);
        $objPHPExcel->setActiveSheetIndex(1);
        $objPHPExcel->getActiveSheet(1)->setTitle("NHOMDANHMUC");
        $objPHPExcel->getActiveSheet(1)->getStyle('A1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(1)->getStyle('B1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(1)->setCellValue('A'.$h, 'MANHOMKH');
        $objPHPExcel->getActiveSheet(1)->setCellValue('B'.$h, 'TENNHOMKH');
        /** Xuất ra sheet nhóm danh mục */
        
        /** Xuất ra sheet Danh mục đối tượng */
        $objPHPExcel->createSheet(2);
        $objPHPExcel->setActiveSheetIndex(2);
        $objPHPExcel->getActiveSheet(2)->setTitle("DANHMUCDOITUONG");
        $objPHPExcel->getActiveSheet(2)->getStyle('A1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(2)->getStyle('B1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(2)->getStyle('C1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(2)->getStyle('D1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(2)->getStyle('E1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(2)->getStyle('F1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(2)->getStyle('G1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(2)->getStyle('H1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(2)->getStyle('I1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(2)->getStyle('J1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(2)->setCellValue('A'.$k, 'MAKH');
        $objPHPExcel->getActiveSheet(2)->setCellValue('B'.$k, 'TENKH');
        $objPHPExcel->getActiveSheet(2)->setCellValue('C'.$k, 'DIACHI');
        $objPHPExcel->getActiveSheet(2)->setCellValue('D'.$k, 'MASOTHUE');
        $objPHPExcel->getActiveSheet(2)->setCellValue('E'.$k, 'SOTAIKHOAN');
        $objPHPExcel->getActiveSheet(2)->setCellValue('F'.$k, 'NGANHANG');
        $objPHPExcel->getActiveSheet(2)->setCellValue('G'.$k, 'QUOCGIA');
        $objPHPExcel->getActiveSheet(2)->setCellValue('H'.$k, 'DIENTHOAI');
        $objPHPExcel->getActiveSheet(2)->setCellValue('I'.$k, 'EMAIL');
        $objPHPExcel->getActiveSheet(2)->setCellValue('J'.$k, 'MANHOMKH');
        /** Xuất ra sheet Danh mục đối tượng */
        
        /** Xuất ra sheet HHVT */
        $objPHPExcel->createSheet(3);
        $objPHPExcel->setActiveSheetIndex(3);
        $objPHPExcel->getActiveSheet(3)->setTitle("HHVT");
        $objPHPExcel->getActiveSheet(3)->getStyle('A1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(3)->getStyle('B1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(3)->getStyle('C1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(3)->getStyle('D1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(3)->getStyle('E1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(3)->getStyle('F1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(3)->setCellValue('A'.$j, 'TK');
        $objPHPExcel->getActiveSheet(3)->setCellValue('B'.$j, 'MAHHVT');
        $objPHPExcel->getActiveSheet(3)->setCellValue('C'.$j, 'TENHHVT');
        $objPHPExcel->getActiveSheet(3)->setCellValue('D'.$j, 'DVT');
        $objPHPExcel->getActiveSheet(3)->setCellValue('E'.$j, 'NHOMHHVT');
        $objPHPExcel->getActiveSheet(3)->setCellValue('F'.$j, 'LOAISPHH');
        $j = 2;
        foreach($product as $key => $value)
        {
            $objPHPExcel->getActiveSheet(3)->setCellValue('A'.$j, $value['tk']);
            $objPHPExcel->getActiveSheet(3)->setCellValue('B'.$j, $value['code']);
            $objPHPExcel->getActiveSheet(3)->setCellValue('C'.$j, $value['product_name']);
            $objPHPExcel->getActiveSheet(3)->setCellValue('D'.$j, $value['unit_name']);
            $objPHPExcel->getActiveSheet(3)->setCellValue('E'.$j, $value['category_code']);
            $objPHPExcel->getActiveSheet(3)->setCellValue('F'.$j, $value['type']);
            $j++;
        }
        /** Xuất ra sheet HHVT */
        
        /** Xuất ra sheet DMCHUNGTU */
        $objPHPExcel->createSheet(4);
        $objPHPExcel->setActiveSheetIndex(4);
        $objPHPExcel->getActiveSheet(4)->setTitle("DMCHUNGTU");
        $objPHPExcel->getActiveSheet(4)->getStyle('A1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(4)->getStyle('B1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet(4)->setCellValue('A'.$i, 'DMCHUNGTU');
        $objPHPExcel->getActiveSheet(4)->setCellValue('B'.$i, 'DIENGIAI');
        $i =2;
        foreach($certificate_list as $key => $value)
        {
            $objPHPExcel->getActiveSheet(4)->setCellValue('A'.$i, $key);
            $objPHPExcel->getActiveSheet(4)->setCellValue('B'.$i, $value);
            $i++;
        }
        /** Xuất ra sheet DMCHUNGTU */
        $fileName = "EXPORT_DATA_FROM_SOFTWARE_NEWWAY_".(str_replace('/','-',$export_date)).".xls";
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($fileName); 
		if(file_exists($fileName))
        {
			echo '<script>';
			echo 'window.location.href = \''.$fileName.'\';';
			echo ' </script>';
		}           
    }
}
?>