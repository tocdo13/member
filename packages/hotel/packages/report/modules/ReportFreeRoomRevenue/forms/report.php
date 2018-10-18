<?php
class ReportFreeRoomRevenueForm extends Form
{
    function ReportFreeRoomRevenueForm()
    {
        Form::Form('ReportFreeRoomRevenueForm');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
    }
    function draw()
    {
        require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/currency.php';
        $r_id = '';$ctm_id = '';$ctm_group = '';
        isset($_REQUEST['reservation_id'])?$r_id = $_REQUEST['reservation_id']:'';
        isset($_REQUEST['customer_id'])?$ctm_id = $_REQUEST['customer_id']:'';
        if(isset($_REQUEST['customer_group']))
        {
            if($_REQUEST['customer_group'] != 'ALL')
            {
                $ctm_group = $_REQUEST['customer_group'];
            }            
        }
        $this->map['from_date'] = isset($_REQUEST['from_date'])?$_REQUEST['from_date'] = $_REQUEST['from_date']:$_REQUEST['from_date'] = date('01/m/Y', Date_Time::to_time(date('d/m/Y', time())));
        $this->map['to_date'] = isset($_REQUEST['to_date'])?$_REQUEST['to_date'] = $_REQUEST['to_date']:$_REQUEST['to_date'] = date('t/m/Y');
        $from_date = Date_Time::to_time($_REQUEST['from_date']);
        $to_date = Date_Time::to_time($_REQUEST['to_date']);
        $rooms = $this->get_all_room_foc($r_id, $ctm_id, $ctm_group, $from_date, $to_date);
        /** lay ra va tinh tong dich vu cua phong foc all */
        foreach($rooms as $key => $value)
        {
            $rooms[$key]['total_extra_room_rates'] = '';
            $rooms[$key]['total_extra_service_rates'] = '';
            $rooms[$key]['total_bar_pay_with_room'] = '';
            $rooms[$key]['total_minibar_room'] = '';
            $rooms[$key]['total_laundry_room'] = '';
            $rooms[$key]['total_equip_room'] = '';
            $rooms[$key]['total_telephone_room'] ='';
            $rooms[$key]['total_spa_room'] ='';
        }
        foreach($rooms as $key => $value)
        {
            $night = (Date_Time::to_time($value['departure_time']) - Date_Time::to_time($value['arrival_time']))/86400;
            $rooms[$key]['nights'] = $night;
            /** tinh tien phong theo luoc dau gia */
            $room_status = DB::fetch_all('
                                SELECT
                                    room_status.id,
                                    room_status.change_price,
                                    room_status.in_date,
                                    room_status.reservation_room_id as rr_id
                                FROM
                                    room_status
                                    inner join reservation_room on reservation_room.id=room_status.reservation_room_id
                                    inner join reservation on reservation_room.reservation_id=reservation.id
                                WHERE
                                    room_status.status <> \'CANCEL\'
                                    AND reservation.id = \''.$value['r_id'].'\'
                                    AND reservation_room.id = \''.$value['id'].'\'
                                ORDER BY 
                                    room_status.id DESC
            ');
            foreach($room_status as $k => $v)
            {
                if(!isset($rooms[$key]['total']))
                {
                    if($value['net_price'] == 1)
                    {
                        $rooms[$key]['total'] = $v['change_price'];
                    }else
                    {
                        $rooms[$key]['total'] = ($v['change_price']/100*(100+$value['service_rate']))/100*(100+$value['tax_rate']);                        
                    }
                }else
                {
                    if($value['net_price'] == 1)
                    {
                        $rooms[$key]['total'] += $v['change_price'];
                    }else
                    {
                        $rooms[$key]['total'] += ($v['change_price']/100*(100+$value['service_rate']))/100*(100+$value['tax_rate']);                        
                    }
                }
            }
            //System::debug($room_status);
            /** tinh tien phong theo luoc dau gia */
            if($value['foc_all'] == 1)
            {
                //$r_id = $value['r_id'];
                $rr_id = $value['id'];
                $extra = $this->get_service_room_foc_all($rr_id);
                $bar = $this->get_bar_room_foc_all($rr_id);
                $minibar_laundry_equip = $this->get_minibar_laundry_equip_foc_all($rr_id);
                $telephone = $this->get_telephone_foc_all($rr_id);
                $spa = $this->get_spa_foc_all($rr_id);
                /** phu troi tien phong */
                foreach($extra as $ek => $ev)
                {
                    $extra[$ek]['quantity'] = $ev['quantity'] + $ev['change_quantity'];
                    if($ev['net_price'] == '0')
                    {
                        $extra[$ek]['price'] = ($ev['price']/100*(100+$ev['service_rate']))/100*(100+$ev['tax_rate']);                     
                    }else
                    {
                        $extra[$ek]['price'] = $ev['price'];
                    }
                }
                foreach($extra as $k => $v)
                {
                    if($v['type']=='ROOM')
                    {
                        if($rooms[$key]['id'] == $v['rr_id'])
                        {
                            if(!isset($rooms[$key]['total_extra_room_rates']))
                            {
                                $rooms[$key]['total_extra_room_rates'] = $v['price']*$v['quantity']; 
                            }else
                            {
                                $rooms[$key]['total_extra_room_rates'] += $v['price']*$v['quantity'];                           
                            } 
                        }
                    }
                    if($v['type']=='SERVICE')
                    {
                        if($rooms[$key]['id'] == $v['rr_id'])
                        {
                            if(!isset($rooms[$key]['total_extra_service_rates']))
                            {
                                $rooms[$key]['total_extra_service_rates'] = $v['price']*$v['quantity'];
                            }else
                            {
                                $rooms[$key]['total_extra_service_rates'] += $v['price']*$v['quantity'];                          
                            } 
                        }
                    }
                }
                /** phu troi tien phong */
                /** bar*/
                foreach($bar as $k => $v)
                {
                    if($rooms[$key]['id'] == $v['rr_id'])
                    {
                        if(!isset($rooms[$key]['total_bar_pay_with_room']))
                        {
                            $rooms[$key]['total_bar_pay_with_room'] = $v['total_payment_room'];
                        }else
                        {
                            $rooms[$key]['total_bar_pay_with_room'] += $v['total_payment_room'];                           
                        } 
                    }                    
                }
                /** bar*/
                /** minibar, giat la, den bu*/
                foreach($minibar_laundry_equip as $k => $v)
                {
                    if($rooms[$key]['id'] == $v['rr_id'])
                    {
                        if($v['type'] == 'MINIBAR')
                        {
                            $remain = ($v['total'] - $v['prepaid']);
                            if(!isset($rooms[$key]['total_minibar_room']))
                            {
                                $rooms[$key]['total_minibar_room'] = $remain;
                            }else
                            {
                                $rooms[$key]['total_minibar_room'] += $remain;                           
                            }                             
                        }
                        if($v['type'] == 'LAUNDRY')
                        {
                            $remain = ($v['total'] - $v['prepaid']);
                            if(!isset($rooms[$key]['total_laundry_room']))
                            {
                                $rooms[$key]['total_laundry_room'] = $remain;
                            }else
                            {
                                $rooms[$key]['total_laundry_room'] += $remain;                           
                            }                             
                        }
                        if($v['type'] == 'EQUIP')
                        {
                            $remain = ($v['total'] - $v['prepaid']);
                            if(!isset($rooms[$key]['total_equip_room']))
                            {
                                $rooms[$key]['total_equip_room'] = $remain;
                            }else
                            {
                                $rooms[$key]['total_equip_room'] += $remain;                           
                            }                             
                        }
                    }                  
                }
                /** minibar, giat la, den bu*/
                /** telephone*/
                foreach($telephone as $k => $v)
                {
                    if($rooms[$key]['id'] == $v['rr_id'])
                    {
                        if(!isset($rooms[$key]['total_telephone_room']))
                        {
                            $rooms[$key]['total_telephone_room'] = $v['total_before_tax'];
                        }else
                        {
                            $rooms[$key]['total_telephone_room'] += $v['total_before_tax'];                           
                        } 
                    }                    
                }
                /** telephone*/
                /** spa */
                foreach($spa as $k => $v)
                {
                    if($rooms[$key]['id'] == $v['rr_id'])
                    {
                        if(!isset($rooms[$key]['total_spa_room']))
                        {
                            $rooms[$key]['total_spa_room'] = $v['amount_pay_with_room'];
                        }else
                        {
                            $rooms[$key]['total_spa_room'] += $v['amount_pay_with_room'];                           
                        } 
                    } 
                }
                /** spa */
            }
        }
        //System::debug($customer_group_arr);
        /** lay ra cac dich vu cua tung phong */
        /** chuyen du lieu sang layouts*/
        $this->map['total'] = 0;
        $this->map['total_houseuse'] =0;
        $this->map['total_amount'] = 0;
        $this->map['total_extra_room_rates'] = 0;
        $this->map['total_extra_service_rates'] = 0;
        $this->map['total_telephone_room'] = 0;
        $this->map['total_bar_pay_with_room'] = 0;
        $this->map['total_minibar_room'] = 0;
        $this->map['total_laundry_room'] = 0;
        $this->map['total_equip_room'] = 0;
        $this->map['total_spa_room'] = 0;
        $customer_group_arr = array();
        foreach($rooms as $key => $value)
        {
            $key_ctm_group = $value['ctm_group_name'];
            $total_amount = $value['total'] + $value['total_extra_room_rates'] + $value['total_extra_service_rates'] + $value['total_bar_pay_with_room'] + $value['total_minibar_room'] + $value['total_laundry_room'] + $value['total_equip_room'] + $value['total_telephone_room'] + $value['total_spa_room'];
            if(!isset($customer_group_arr[$key_ctm_group]['id']))
            {
                $customer_group_arr[$key_ctm_group]['id'] = $value['ctm_group_id'];
                $customer_group_arr[$key_ctm_group]['name'] = $key_ctm_group;
                $customer_group_arr[$key_ctm_group]['quantity'] = 1;
                $customer_group_arr[$key_ctm_group]['total_amount'] = $total_amount;
            }else
            {
                $customer_group_arr[$key_ctm_group]['quantity']++;
                $customer_group_arr[$key_ctm_group]['total_amount'] += $total_amount;
            }            
        }
        foreach($rooms as $key => $value)
        {
            $total_amount = $value['total'] + $value['total_extra_room_rates'] + $value['total_extra_service_rates'] + $value['total_bar_pay_with_room'] + $value['total_minibar_room'] + $value['total_laundry_room'] + $value['total_equip_room'] + $value['total_telephone_room'] + $value['total_spa_room'];
            $rooms[$key]['total_amount'] = $total_amount; 
            $this->map['total'] += $value['total'];
            $this->map['total_amount'] += $total_amount;
            $this->map['total_extra_room_rates'] += $value['total_extra_room_rates'];
            $this->map['total_extra_service_rates'] += $value['total_extra_service_rates']; 
            $this->map['total_telephone_room'] += $value['total_telephone_room']; 
            $this->map['total_bar_pay_with_room'] += $value['total_bar_pay_with_room']; 
            $this->map['total_minibar_room'] += $value['total_minibar_room']; 
            $this->map['total_laundry_room'] += $value['total_laundry_room']; 
            $this->map['total_equip_room'] += $value['total_equip_room'];   
            $this->map['total_spa_room'] += $value['total_spa_room'];               
        }
        $customer = DB::fetch_all('SELECT id, name FROM customer ORDER BY name ASC');
        $customer_option = '';
        foreach($customer as $k => $v)
        {
            $customer_option .= '<option value=\''.$v['name'].'\' id=\''.$v['id'].'\'>' .$v['id']. '</option>';            
        }
        $customer_group = DB::fetch_all('SELECT id, name FROM customer_group WHERE id <> \'ROOT\' ORDER BY name');
        $this->map['customer_option'] = $customer_option;
        $this->map['customer_group_list'] = array('ALL'=>Portal::language('all')) + String::get_list($customer_group);
        $this->map['items'] = $rooms;
        $this->map['table_small'] = $customer_group_arr;
        //System::debug($this->map['table_small']);
        $this->parse_layout('report', $this->map);
        /** chuyen du lieu sang layouts*/    
    }
    
    /** lay ra tat ca cac phong foc va foc all */
    function get_all_room_foc($r_id, $ctm_id, $ctm_group, $from_date, $to_date)
    {
        $cond = '1=1';
        $r_id?$cond .= ' AND reservation_room.reservation_id = \''.$r_id.'\'':'';
        $ctm_id?$cond .= ' AND reservation.customer_id = \''.$ctm_id.'\'':'';
        $ctm_group?$cond .= ' AND customer.group_id = \''.$ctm_group.'\'':'';
        $from_date?$cond .= ' AND reservation_room.departure_time >=\''.Date_Time::convert_time_to_ora_date($from_date).'\'':'';
        $to_date?$cond .= ' AND reservation_room.arrival_time <=\''.Date_Time::convert_time_to_ora_date($to_date).'\'':'';
        $rooms = DB::fetch_all('
                            SELECT
                                reservation_room.id,
                                reservation.id as r_id,
                                customer.name as ctm_name,
                                customer_group.id as ctm_group_id,
                                customer_group.name as ctm_group_name,
                                room.id as room_id,
                                room.name as room_name,
                                TO_CHAR(reservation_room.arrival_time, \'DD/MM/YYYY\') as arrival_time,
                                TO_CHAR(reservation_room.departure_time, \'DD/MM/YYYY\') as departure_time,
                                reservation_room.price,
                                reservation_room.usd_price,
                                reservation_room.net_price,
                                reservation_room.tax_rate,
                                reservation_room.service_rate,
                                reservation_room.foc,
                                reservation_room.foc_all
                            FROM
                                reservation_room
                                left join room on reservation_room.room_id = room.id
                                inner join reservation on reservation_room.reservation_id = reservation.id
                                inner join customer on reservation.customer_id = customer.id
                                inner join customer_group on customer_group.id = customer.group_id
                            WHERE
                                '.$cond.'
                                AND reservation_room.status <>\'CANCEL\'
                                AND (reservation_room.foc is not null OR reservation_room.foc_all <>\'0\')
                                --AND reservation_room.change_room_to_rr is null
                            ORDER BY
                                reservation.id, reservation_room.id ASC 
        ');
        return $rooms;   
    }
    /** lay ra tat ca cac phong foc va foc all */
    /** lay ra dich vu cua tung phong */
    function get_service_room_foc_all($rr_id)
    {
        $extra = DB::fetch_all('
                                 SELECT
                                    extra_service_invoice_detail.id,
                                    extra_service_invoice_detail.price,
                                    extra_service_invoice.net_price,
                                    extra_service_invoice_detail.in_date,
                                    reservation_room.tax_rate,
                                    reservation_room.service_rate,
                                    extra_service_invoice_detail.quantity,
                                    NVL(extra_service_invoice_detail.change_quantity,0) as change_quantity,
                                    extra_service_invoice.reservation_room_id as rr_id,
                                    extra_service.type
                                 FROM
                                    extra_service_invoice
                                    inner join extra_service_invoice_table on extra_service_invoice_table.invoice_id = extra_service_invoice.id
                                    inner join extra_service_invoice_detail on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
                                    inner join extra_service on extra_service.id = extra_service_invoice_detail.service_id
                                    inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                                    inner join reservation on reservation.id = reservation_room.reservation_id
                                 WHERE
                                    reservation_room.id='.$rr_id.'
                                 ORDER BY
                                    extra_service_invoice_detail.invoice_id ASC,
                                    extra_service_invoice_detail.in_date ASC                            
                                    
        ');
        return $extra;        
    }
    /** lay ra dich vu cua tung phong */
    /** lay ra hoa don nha hang tra ve phong */
    function get_bar_room_foc_all($rr_id)
    {
        $bar = DB::fetch_all('
                                SELECT
                                    bar_reservation.id,
                                    bar_reservation.reservation_room_id as rr_id,
                                    bar_reservation.total_payment_room
                                FROM
                                    bar_reservation
                                    inner join reservation_room on reservation_room.id = bar_reservation.reservation_room_id
                                    inner join reservation on reservation.id = reservation_room.reservation_id
                                WHERE
                                    reservation_room.id = \''.$rr_id.'\'
                                    
        ');
        return $bar;
    }
    /** lay ra bar cua tung phong */
    /** lay ra minibar cua tung phong*/
    function get_minibar_laundry_equip_foc_all($room_id)
    {
        $minibar_laundry_equip = DB::fetch_all('
                                    SELECT
                                        housekeeping_invoice.id,
                                        housekeeping_invoice.reservation_room_id as rr_id,
                                        housekeeping_invoice.prepaid,
                                        housekeeping_invoice.total,
                                        housekeeping_invoice.type
                                   	FROM 
                    					housekeeping_invoice
                    					left outer join minibar on minibar.id=housekeeping_invoice.minibar_id 
                    					left outer join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id and reservation_room.room_id=minibar.room_id					
                    				WHERE
                                        housekeeping_invoice.reservation_room_id = \''.$room_id.'\'
        ');
        return $minibar_laundry_equip;
    }
    /** lay ra minibar cua tung phong*/
    /** lay ra telephone cua tung phong */
    function get_telephone_foc_all($rr_id)
    {
        $telephone = DB::fetch_all('
                                SELECT
                                    telephone_report_daily.id,
                                    telephone_report_daily.reservation_room_id as rr_id,
                                    telephone_report_daily.total_before_tax
                                FROM
                                    telephone_report_daily
                                    inner join reservation_room on reservation_room.id = telephone_report_daily.reservation_room_id
                                WHERE
                                    reservation_room.id = \''.$rr_id.'\'
        ');
        return $telephone;
    }
    /** lay ra telephone cua tung phong */
    /** lay ra spa cua tung phong */
    function get_spa_foc_all($rr_id)
    {
        $spa = DB::fetch_all('
                                SELECT
                                    massage_reservation_room.id,
                                    massage_reservation_room.hotel_reservation_room_id as rr_id,
                                    massage_reservation_room.amount_pay_with_room
                                FROM
                                    massage_reservation_room
                                    inner join reservation_room on massage_reservation_room.hotel_reservation_room_id = reservation_room.id
                                    inner join reservation on reservation.id = reservation_room.reservation_id
                                WHERE
                                    reservation_room.id = \''.$rr_id.'\'
        ');
        return $spa;
    }
    /** lay ra spa cua tung phong */
}
?>