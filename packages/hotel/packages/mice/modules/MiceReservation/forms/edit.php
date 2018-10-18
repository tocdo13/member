<?php
class EditMiceReservationForm extends Form
{
	function EditMiceReservationForm()
    {
		Form::Form('EditMiceReservationForm');
        $this->link_css('packages/hotel/packages/mice/skins/css/font-awesome-4.5.0/css/font-awesome.min.css');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_css('packages/core/includes/js/custom_content_scroller/jquery.mCustomScrollbar.css');
        $this->link_css('packages/hotel/packages/mice/modules/MiceReservation/mice.css');
        $this->link_css('packages/hotel/packages/mice/skins/jquery.windows-engine.css');
        $this->link_js('packages/hotel/packages/mice/skins/jquery.windows-engine.js');
        //$this->link_js('packages/hotel/packages/mice/skins/jspdf.min.js');
        $this->link_js('packages/core/includes/js/custom_content_scroller/jquery.mCustomScrollbar.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.mask.min.js');
        $this->link_js('packages/hotel/packages/mice/modules/MiceReservation/mice_reservation.js');
        $this->link_js('packages/hotel/packages/mice/modules/MiceReservation/get_data.js');
	}
    function on_submit()
    {
        //System::debug($_REQUEST); exit();
        if(Url::get('act')=='SAVE' OR Url::get('act')=='CONFIRM')
        {
            require_once 'packages/hotel/packages/mice/includes/create_banquet.php'; //PARTY
            require_once 'packages/hotel/packages/mice/includes/create_reservation.php'; // BOOKING
            require_once 'packages/hotel/packages/mice/includes/create_spa_booking.php'; // SPA
            require_once 'packages/hotel/packages/mice/includes/createTable.php'; // BAR
            
            if(isset($_REQUEST['spa']))
            {
                $services = array();
                $products = array();
                $info = array();
                foreach($_REQUEST['spa'] as $key=>$value)
                {
                    $info = array(
                                'net_price'         => isset($value['net_price'])?1:0,
                                'discount_percent'  => System::calculate_number($value['discount_percent']),
                                'discount_amount'   => System::calculate_number($value['discount_amount']),
                                'tax_rate'          => System::calculate_number($value['tax_rate']),
                                'service_rate'      => System::calculate_number($value['service_rate']),
                                'total_before_tax'  => System::calculate_number($value['total_before_tax']),
                                'total_amount'      => System::calculate_number($value['total_amount']),
                                'exchange_rate'     => '',
                                'contact_name'      => Url::get('contact_name'),
                                'contact_phone'     => Url::get('contact_phone'),
                                'contact_email'     => Url::get('contact_email'),
                                'portal_id'         => PORTAL_ID
                                );
                    if(isset($value['child_service']))
                    {
                        foreach($value['child_service'] as $k1=>$v1)
                        {
                            $services[$key.$k1] = array(
                                                'price_id'      => $v1['price_id'],
                                                'spa_room_id'   => $v1['spa_room_id'],
                                                'staff_ids'     => $v1['staff_ids'],
                                                'in_date'       => $v1['in_date'],
                                                'time_in_hour'  => $v1['time_in'],
                                                'time_out_hour' => $v1['time_out'],
                                                'quantity'      => System::calculate_number($v1['quantity']),
                                                'edit_price'    => System::calculate_number($v1['price']),
                                                'customer_code' => '',
                                                'customer_name' => '',
                                                'status'        => 'BOOKED'
                                                );
                        }
                    }
                    if(isset($value['child_product']))
                    {
                        foreach($value['child_product'] as $k2=>$v2)
                        {
                            $products[$key.$k2] = array(
                                                'price_id'      => $v2['price_id'],
                                                'in_date'       => $v2['in_date'],
                                                'checkin_time'  => $v2['time_in'],
                                                'checkout_time' => $v2['time_out'],
                                                'quantity'      => System::calculate_number($v2['quantity']),
                                                'edit_price'    => System::calculate_number($v2['price']),
                                                'amount'        => System::calculate_number($v2['amount'])
                                                );
                        }
                    }
                }
                /** truyen vao API de kiem tra **/
                if(sizeof($info)>0)
                {
                    $spa = create_spa_booking('',$info,$services,$products,false);
                }
                else
                    $spa = array();
                
                if(sizeof($spa)>0)
                {
                    foreach($spa as $k3=>$v3)
                    {
                        $this->error('SPA','SPA: '.$spa[$k3]);
                    }
                }
            }
            
            if(isset($_REQUEST['booking']))
            {
                $info = array(
                            'customer_id'   => Url::get('customer_id'),
                            'booker'        => '',
                            'phone_booker'  => ''
                            );
                foreach($_REQUEST['booking'] as $key=>$value)
                {
                    if($value['recode']=='' OR $value['recode']==0)
                    {
                        $room_levels[$key] = array(
                                                'id'            => '', 
                                                'room_level_id' => $value['room_level_id'],
                                                'quantity'      => System::calculate_number($value['quantity']),
                                                'child'         => System::calculate_number($value['child']),
                                                'adult'         => System::calculate_number($value['adult']),
                                                'price'         => System::calculate_number($value['price']),
                                                'usd_price'     => System::calculate_number($value['usd_price']),
                                                'note'          => $value['note'],
                                                'time_in'       => Date_Time::to_time($value['from_date'])+$this->calc_time($value['time_in']),
                                                'time_out'      => Date_Time::to_time($value['to_date'])+$this->calc_time($value['time_out']),
                                                'net_price'     => isset($value['net_price'])?1:0,
                                                'service_rate'  => System::calculate_number($value['service_rate']),
                                                'tax_rate'      => System::calculate_number($value['tax_rate']),
                                                'exchange_rate' => $value['exchange_rate']
                                                );
                    }
                }
                if(isset($room_levels))
                    $booking = credit_reservation($room_levels,$info,false,'');
                //System::debug($booking);
                if( isset($booking) AND sizeof($booking)>0 )
                {
                    foreach($booking as $k3=>$v3)
                    {
                        $this->error('BOOKING','RECEPTION: '.$v3['note']);
                    }
                }
                
            }
            
            if(isset($_REQUEST['bar']))
            {
                foreach($_REQUEST['bar'] as $key=>$value)
                {
                    if($value['bar_reservation_id']=='' OR $value['bar_reservation_id']==0)
                    {
                        $info = array(
                                    'arrival_time'      => Date_Time::to_time($value['in_date'])+$this->calc_time($value['time_in']),
                                    'departure_time'    => Date_Time::to_time($value['in_date'])+$this->calc_time($value['time_out']),
                                    'full_rate'         => isset($value['full_rate'])?1:0,
                                    'full_charge'       => isset($value['full_charge'])?1:0,
                                    'note'              => '',
                                    'customer_id'       => Url::get('customer_id'),
                                    'agent_name'        => Url::get('customer_name'),
                                    'receiver_name'     => Url::get('traveller_name'),
                                    'tax_rate'          => System::calculate_number($value['tax_rate']),
                                    'foc'               => 0,
                                    'banquet_order_type'=> $value['banquet_order_type'],
                                    'table_id'          => $value['table_id'],
                                    'bar_id'            => $value['bar_id'],
                                    'num_people'        => $value['num_people'],
                                    'order_person'      => '',
                                    'mice_reservation_id'=>'',
                                    'discount'          => System::calculate_number($value['discount']),
                                    'discount_percent'  => System::calculate_number($value['discount_percent']),
                                    'bar_fee_rate'      => System::calculate_number($value['service_rate'])
                                    );
                        $product_list = array();
                        if(isset($value['child']))
                        {
                            foreach($value['child'] as $k1=>$v1)
                            {
                                $product_list[$k1] = array(
                                                        'product_id'        => $v1['product_id'],
                                                        'quantity'          => System::calculate_number($v1['quantity']),
                                                        'price_id'          => $v1['price_id'],
                                                        'quantity_discount' => System::calculate_number($v1['quantity_discount']),
                                                        'discount_rate'     => System::calculate_number($v1['discount_rate']),
                                                        'price'             => System::calculate_number($v1['price']),
                                                        'unit_id'           => $v1['unit_id'],
                                                        'note'              => $v1['note']
                                                        );
                            }
                        }
                        
                        $bar = booked_table('booked',$info,$product_list,'');
                        //echo $bar;
                        if($bar==false)
                        {
                            $this->error('RESTAURANT','RESTAURANT: conflict Ban ');
                        }
                    }
                }
            }
            
            if(isset($_REQUEST['party']))
            {
                foreach($_REQUEST['party'] as $key=>$value)
                {
                    if($value['party_reservation_id']=='' OR $value['party_reservation_id']==0)
                    {
                        $info = array(
                                    'full_name'             => Url::get('traveller_name'),
                                    'address'                => '',
                                    'identity_number'       => '',
                                    'email'                 => '',
                                    'party_type'            => $value['party_type'],
                                    'company_name'          => Url::get('company_name'),
                                    'company_address'       => '',
                                    'company_phone'         => '',
                                    'total_before_tax'      => System::calculate_number($value['total_before_tax']),
                                    'total'                 => System::calculate_number($value['total']),
                                    'extra_service_rate'    => System::calculate_number($value['service_rate']),
                                    'vat'                   => System::calculate_number($value['tax_rate']),
                                    'user_id'               => User::id(),
                                    'time'                  => $value['in_date'],
                                    'checkin_time'          => $value['time_in'],
                                    'checkout_time'         => $value['time_out'],
                                    'promotions'            => $value['promotions'],
                                    'portal_id'             => PORTAL_ID
                                    );
                        //$mi_banquet_room,$mi_meeting_room
                        $mi_banquet_room = array();
                        $mi_meeting_room = array();
                        if(isset($value['child_room']))
                        {
                            foreach($value['child_room'] as $k1=>$v1)
                            {
                                $child_room = array(
                                                    'party_room_id' => $v1['party_room_id'],
                                                    'time_type'     => $v1['time_type'],
                                                    'address'       => $v1['address'],
                                                    'price'         => System::calculate_number($v1['price']),
                                                    'note'          => $v1['note'],
                                                    'type'          => $v1['type']
                                                    );
                                if($v1['type']==1)
                                {
                                    $mi_meeting_room[$k1] = $child_room;
                                }
                                else
                                {
                                    $mi_banquet_room[$k1] = $child_room;
                                }
                            }
                        }
                        //$mi_vegetarian,$mi_product,$mi_eating_product,$mi_service
                        $mi_vegetarian = array();
                        $mi_product = array();
                        $mi_eating_product = array();
                        $mi_service = array();
                        if(isset($value['child_product']))
                        {
                            foreach($value['child_product'] as $k2=>$v2)
                            {
                                $child_product = array(
                                                    'product_id'    => $v2['product_id'],
                                                    'name'          => $v2['product_name'],
                                                    'quantity'      => System::calculate_number($v2['quantity']),
                                                    'price'         => System::calculate_number($v2['price']),
                                                    'unit'          => $v2['unit_name'],
                                                    'type'          => $v2['type']
                                                    );
                                if($v2['type']==1)
                                {
                                    $mi_product[$k2] = $child_product;
                                }
                                elseif($v2['type']==2)
                                {
                                    $mi_eating_product[$k2] = $child_product;
                                }
                                elseif($v2['type']==3)
                                {
                                    $mi_service[$k2] = $child_product; 
                                }
                                else
                                {
                                    $mi_vegetarian[$k2] = $child_product;
                                }
                            }
                        }
                        
                        $party = create_banquet_book($info,$mi_banquet_room,$mi_meeting_room,$mi_vegetarian,$mi_product,$mi_eating_product,$mi_service,false,'');
                        //System::debug($party);
                        if(sizeof($party)>0)
                        {
                            foreach($party as $k3=>$v3)
                            {
                                $this->error('PARTY','PARTY: '.$v3['note']);
                            }
                        }
                    }
                }
            }
            
            /** ************** END check  **************** **/
/**
 **********************************************************************************
 * *************************   END CHECK  *****************************************
 * **********************************************************************************
 * ********************************************************************************** 
 **/
            if($this->is_error())
    		{
    		  return;
    		}
            else
            {
                /** log **/
                $log_type = '';
                $log_title = '';
                $log_description = '';
                $log_id = '';
                /** log **/
                $customer_name = '';
                $customer_address = '';
                if(Url::get('customer_id')!='')
                {
                    $customer = DB::fetch("SELECT * FROM customer WHERE id=".Url::get('customer_id'));
                    $customer_name = $customer['name'];
                    $customer_address = $customer['address'];
                }
                
                $mice_reservation = array(
                                        'customer_id'   => Url::get('customer_id'),
                                        'traveller_id'  => Url::get('traveller_id'),
                                        'contact_name'  => Url::get('contact_name'),
                                        'contact_phone' => Url::get('contact_phone'),
                                        'contact_email' => Url::get('contact_email'),
                                        'code_mice'     => Url::get('code_mice'),
                                        'start_date'    => Date_Time::to_orc_date(Url::get('start_date')),
                                        'end_date'      => Date_Time::to_orc_date(Url::get('end_date')),
                                        'cut_of_date'   => Date_Time::to_orc_date(Url::get('cut_of_date')),
                                        'note'          => Url::get('note'),
                                        'total_amount'  => System::calculate_number(Url::get('total_amount')),
                                        'portal_id'     => PORTAL_ID
                                        );
                if(Url::get('act')=='CONFIRM')
                {
                    $mice_reservation['status'] = 1;
                    $log_description .= '<h1>'.Portal::language('confirm_for_guest').'</h1>';
                }
                if(Url::get('id'))
                {
                    $mice_id = Url::get('id');
                    $mice_reservation['last_editer'] = User::id();
                    $mice_reservation['last_edit_time'] = time();
                    DB::update('mice_reservation',$mice_reservation,'id='.$mice_id);
                    $log_type = 'EDIT';
                    $log_title = 'Update mice '.$mice_id;
                }
                else
                {
                    $mice_reservation['creater'] = User::id();
                    $mice_reservation['create_time'] = time();
                    $mice_id = DB::insert('mice_reservation',$mice_reservation);
                    $log_type = 'ADD';
                    $log_title = 'Make mice '.$mice_id;
                }
                
                /** log **/
                $log_description .= '<h3>'.Portal::language('infomation').'</h3><hr/>';
                $log_description .= '<b>+ '.Portal::language('create_date').': </b>'.Url::get('code_mice').'<br/>';
                $log_description .= '<b>+ '.Portal::language('start_date').': </b>'.Url::get('start_date').' '.Portal::language('end_date').': </b>'.Url::get('end_date').'<br/>';
                $log_description .= '<b>+ '.Portal::language('cut_of_date').': </b>'.Url::get('cut_of_date').'<br/>';
                $log_description .= '<b>+ '.Portal::language('customer_name').': </b>'.Url::get('customer_name').'<br/>';
                $log_description .= '<b>+ '.Portal::language('traveller_name').': </b>'.Url::get('traveller_name').'<br/>';
                $log_description .= '<b>+ '.Portal::language('contact_name').': </b>'.Url::get('contact_name').'<br/>';
                $log_description .= '<b>+ '.Portal::language('contact_phone').': </b>'.Url::get('contact_phone').'<br/>';
                $log_description .= '<b>+ '.Portal::language('contact_email').': </b>'.Url::get('contact_email').'<br/>';
                $log_description .= '<b>+ '.Portal::language('total_amount').': </b>'.Url::get('total_amount').'<br/>';
                $log_description .= '<b>+ '.Portal::language('note').': </b>'.Url::get('note').'<br/>';
                $log_description .= '<h3>'.Portal::language('detail').'</h3><br/>';
                /** log **/
                
                if(isset($_REQUEST['spa']))
                {
                    $list_spa_id = '0';
                    $log_description .= '<h3>'.Portal::language('spa').'</h3><br/>'; 
                    foreach($_REQUEST['spa'] as $key=>$value)
                    {
                        $info = array(
                                    'mice_reservation_id'   => $mice_id,
                                    'net_price'             => isset($value['net_price'])?1:0,
                                    'discount_percent'      => System::calculate_number($value['discount_percent']),
                                    'discount_amount'       => System::calculate_number($value['discount_amount']),
                                    'tax_rate'              => System::calculate_number($value['tax_rate']),
                                    'service_rate'          => System::calculate_number($value['service_rate']),
                                    'total_before_tax'      => System::calculate_number($value['total_before_tax']),
                                    'total_amount'          => System::calculate_number($value['total_amount']),
                                    'exchange_rate'         => '',
                                    'discount_before_tax'   => isset($value['discount_before_tax'])?1:0,
                                    'portal_id'             => PORTAL_ID
                                    );
                        if($value['id']!='')
                        {
                            $mice_spa_id = $value['id'];
                            DB::update('mice_spa',$info,'id='.$mice_spa_id);
                            $log_description .= '<b>- '.Portal::language('update').': </b>mice spa '.$mice_spa_id.'<br/>';
                        }
                        else
                        {
                            $mice_spa_id = DB::insert('mice_spa',$info);
                            $log_description .= '<b>- '.Portal::language('make').': </b>mice spa '.$mice_spa_id.'<br/>';
                        }
                        $log_description .= Portal::language('net').':'.(isset($value['net_price'])?1:0).'
                                             | '.Portal::language('discount_percent').': '.$value['discount_percent'].'
                                             | '.Portal::language('discount_amount').': '.$value['discount_amount'].' 
                                             | '.Portal::language('tax_rate').': '.$value['tax_rate'].'
                                             | '.Portal::language('service_rate').': '.$value['service_rate'].'
                                            <br/>';
                        /**  **/
                        $list_spa_id .= ','.$mice_spa_id;
                        /**  **/
                        if(Url::get('act')=='CONFIRM')
                        {
                            $info_confirm = array(
                                'net_price'         => isset($value['net_price'])?1:0,
                                'discount_percent'  => System::calculate_number($value['discount_percent']),
                                'discount_amount'   => System::calculate_number($value['discount_amount']),
                                'tax_rate'          => System::calculate_number($value['tax_rate']),
                                'service_rate'      => System::calculate_number($value['service_rate']),
                                'total_before_tax'  => System::calculate_number($value['total_before_tax']),
                                'total_amount'      => System::calculate_number($value['total_amount']),
                                'exchange_rate'     => '',
                                'contact_name'      => Url::get('contact_name'),
                                'contact_phone'     => Url::get('contact_phone'),
                                'contact_email'     => Url::get('contact_email'),
                                'portal_id'         => PORTAL_ID
                                );
                            $services_confirm = array();
                            $products_confirm = array();
                        }
                        if(isset($value['child_service']))
                        {
                            $list_spa_service_id = '0';
                            foreach($value['child_service'] as $k1=>$v1)
                            {
                                $spa_service = array(
                                                    'mice_spa_id'   => $mice_spa_id,
                                                    'price_id'      => $v1['price_id'],
                                                    'spa_room_id'   => $v1['spa_room_id'],
                                                    'staff_ids'     => $v1['staff_ids'],
                                                    'time_in'       => Date_Time::to_time($v1['in_date'])+$this->calc_time($v1['time_in']),
                                                    'time_out'      => Date_Time::to_time($v1['in_date'])+$this->calc_time($v1['time_out']),
                                                    'quantity'      => System::calculate_number($v1['quantity']),
                                                    'edit_price'    => System::calculate_number($v1['price']),
                                                    'customer_code' => '',
                                                    'customer_name' => '',
                                                    'status'        => 'BOOKED'
                                                    );
                                if(Url::get('act')=='CONFIRM')
                                {
                                    $services_confirm[$k1] = array(
                                                'price_id'      => $v1['price_id'],
                                                'spa_room_id'   => $v1['spa_room_id'],
                                                'staff_ids'     => $v1['staff_ids'],
                                                'in_date'       => $v1['in_date'],
                                                'time_in_hour'  => $v1['time_in'],
                                                'time_out_hour' => $v1['time_out'],
                                                'quantity'      => System::calculate_number($v1['quantity']),
                                                'edit_price'    => System::calculate_number($v1['price']),
                                                'customer_code' => '',
                                                'customer_name' => '',
                                                'status'        => 'BOOKED'
                                                );
                                }
                                if($v1['id']!='')
                                {
                                    $spa_service_id = $v1['id'];
                                    DB::update('mice_spa_service',$spa_service,'id='.$spa_service_id);
                                    $log_description .= Portal::language('update').' '.Portal::language('service');
                                }
                                else
                                {
                                    $spa_service_id = DB::insert('mice_spa_service',$spa_service);
                                    $log_description .= Portal::language('add').' '.Portal::language('service');
                                }
                                $log_description .= $v1['spa_room_name'].' '.$v1['time_in'].' - '.$v1['time_out'].' '.Portal::language('in_date').' '.$v1['in_date'].' '.Portal::language('quantity').' '.$v1['quantity'].' '.Portal::language('price').' '.$v1['price'].'<br/>';
                                $list_spa_service_id .= ','.$spa_service_id;
                            }
                            /** xoa cac dich vu spa khong nam trong mang **/
                            DB::delete('mice_spa_service','id not in ('.$list_spa_service_id.') AND mice_spa_id='.$mice_spa_id);
                        }
                        if(isset($value['child_product']))
                        {
                            
                            $list_spa_product_id = '0';
                            foreach($value['child_product'] as $k2=>$v2)
                            {
                                $spa_products = array(
                                                    'mice_spa_id'   => $mice_spa_id,
                                                    'price_id'      => $v2['price_id'],
                                                    'time_in'       => Date_Time::to_time($v2['in_date'])+$this->calc_time($v2['time_in']),
                                                    'time_out'      => Date_Time::to_time($v2['in_date'])+$this->calc_time($v2['time_out']),
                                                    'quantity'      => System::calculate_number($v2['quantity']),
                                                    'edit_price'    => System::calculate_number($v2['price']),
                                                    'amount'        => System::calculate_number($v2['amount'])
                                                    );
                                if(Url::get('act')=='CONFIRM')
                                {
                                    $products_confirm[$k2] = array(
                                                'price_id'      => $v2['price_id'],
                                                'in_date'       => $v2['in_date'],
                                                'checkin_time'  => $v2['time_in'],
                                                'checkout_time' => $v2['time_out'],
                                                'quantity'      => System::calculate_number($v2['quantity']),
                                                'edit_price'    => System::calculate_number($v2['price']),
                                                'amount'        => System::calculate_number($v2['amount'])
                                                );
                                }
                                if($v2['id']!='')
                                {
                                    $spa_product_id = $v2['id'];
                                    DB::update('mice_spa_product',$spa_products,'id='.$spa_product_id);
                                    $log_description .= Portal::language('update').' '.Portal::language('product');
                                }
                                else
                                {
                                    $spa_product_id = DB::insert('mice_spa_product',$spa_products);
                                    $log_description .= Portal::language('add').' '.Portal::language('product');
                                }
                                $log_description .= $v2['product_id'].' '.$v2['time_in'].' - '.$v2['time_out'].' '.Portal::language('in_date').' '.$v2['in_date'].' '.Portal::language('quantity').' '.$v2['quantity'].' '.Portal::language('price').' '.$v2['price'].'<br/>';
                                $list_spa_product_id .= ','.$spa_product_id;
                            }
                            /** xoa cac san pham khong nam trong mang **/
                            //echo $list_spa_product_id;
                            DB::delete('mice_spa_product','id not in ('.$list_spa_product_id.') AND mice_spa_id='.$mice_spa_id);
                        }
                        if(Url::get('act')=='CONFIRM')
                        {
                            create_spa_booking($mice_id,$info_confirm,$services_confirm,$products_confirm,true);
                        }
                    }
                    //exit();
                    /** xoa cac spa khong nam trong mang **/
                    $list_spa_delete = DB::select_all('mice_spa','id not in ('.$list_spa_id.') AND mice_reservation_id='.$mice_id);
                    foreach($list_spa_delete as $k_de=>$v_de)
                    {
                        DB::delete('mice_spa','id='.$v_de['id']);
                        DB::delete('mice_spa_service','mice_spa_id='.$v_de['id']);
                        DB::delete('mice_spa_product','mice_spa_id='.$v_de['id']);   
                        $log_description .= '<b>- '.Portal::language('delete').': </b>mice spa '.$v_de['id'].'<br/>';
                    }
                }
                else
                {
                    $list_spa_delete = DB::select_all('mice_spa','mice_reservation_id='.$mice_id);
                    foreach($list_spa_delete as $k_de=>$v_de)
                    {
                        DB::delete('mice_spa','id='.$v_de['id']);
                        DB::delete('mice_spa_service','mice_spa_id='.$v_de['id']);
                        DB::delete('mice_spa_product','mice_spa_id='.$v_de['id']); 
                        $log_description .= '<b>- '.Portal::language('delete').': </b>mice spa '.$v_de['id'].'<br/>';  
                    }
                }
                ///////// end SPA ///////////////////
                
                if(isset($_REQUEST['booking']))
                {
                    $list_booking_id = '0';
                    if(Url::get('act')=='CONFIRM')
                    {
                        $info_confirm = array(
                            'customer_id'   => Url::get('customer_id'),
                            'booker'        => '',
                            'phone_booker'  => ''
                            );
                    }
                    foreach($_REQUEST['booking'] as $key=>$value)
                    {
                        if($value['recode']=='' OR $value['recode']==0)
                        {
                            $mice_booking = array(
                                                    'mice_reservation_id'   => $mice_id,
                                                    'room_level_id'         => $value['room_level_id'],
                                                    'quantity'              => System::calculate_number($value['quantity']),
                                                    'child'                 => System::calculate_number($value['child']),
                                                    'adult'                 => System::calculate_number($value['adult']),
                                                    'price'                 => System::calculate_number($value['price']),
                                                    'usd_price'             => System::calculate_number($value['usd_price']),
                                                    'total_amount'          => System::calculate_number($value['total_amount']),
                                                    'note'                  => $value['note'],
                                                    'time_in'               => Date_Time::to_time($value['from_date'])+$this->calc_time($value['time_in']),
                                                    'time_out'              => Date_Time::to_time($value['to_date'])+$this->calc_time($value['time_out']),
                                                    'net_price'             => isset($value['net_price'])?1:0,
                                                    'service_rate'          => System::calculate_number($value['service_rate']),
                                                    'tax_rate'              => System::calculate_number($value['tax_rate']),
                                                    'exchange_rate'         => $value['exchange_rate']
                                                    );
                            if(Url::get('act')=='CONFIRM')
                            {
                                $room_levels_confirm[$key] = array(
                                                'id'            => '', 
                                                'room_level_id' => $value['room_level_id'],
                                                'quantity'      => System::calculate_number($value['quantity']),
                                                'child'         => System::calculate_number($value['child']),
                                                'adult'         => System::calculate_number($value['adult']),
                                                'price'         => System::calculate_number($value['price']),
                                                'usd_price'     => System::calculate_number($value['usd_price']),
                                                'note'          => $value['note'],
                                                'time_in'       => Date_Time::to_time($value['from_date'])+$this->calc_time($value['time_in']),
                                                'time_out'      => Date_Time::to_time($value['to_date'])+$this->calc_time($value['time_out']),
                                                'net_price'     => isset($value['net_price'])?1:0,
                                                'service_rate'  => System::calculate_number($value['service_rate']),
                                                'tax_rate'      => System::calculate_number($value['tax_rate']),
                                                'exchange_rate' => $value['exchange_rate']
                                                );
                            }
                            if($value['id']!='')
                            {
                                $mice_booking_id = $value['id'];
                                DB::update('mice_booking',$mice_booking,'id='.$mice_booking_id);
                                $log_description .= '<b>- '.Portal::language('update').': </b>mice booking '.$mice_booking_id.'<br/>';
                            }
                            else
                            {
                                $mice_booking_id = DB::insert('mice_booking',$mice_booking);
                                $log_description .= '<b>- '.Portal::language('add').': </b>mice booking '.$mice_booking_id.'<br/>';
                            }
                            $log_description .= Portal::language('net').':'.(isset($value['net_price'])?1:0).'
                                             | '.Portal::language('room_level').': '.$value['room_level_id'].'
                                             | '.Portal::language('quantity').': '.$value['quantity'].' 
                                             | '.Portal::language('child').': '.$value['child'].'
                                             | '.Portal::language('adult').': '.$value['adult'].'
                                             | '.Portal::language('price').': '.$value['price'].'
                                             | '.Portal::language('time_in').': '.$value['time_in'].' '.$value['from_date'].'
                                             | '.Portal::language('time_out').': '.$value['time_out'].' '.$value['to_date'].'
                                             | '.Portal::language('service_rate').': '.$value['service_rate'].'
                                             | '.Portal::language('tax_rate').': '.$value['tax_rate'].'
                                             | '.Portal::language('exchange_rate').': '.$value['exchange_rate'].'
                                            <br/>';
                            $list_booking_id .= ','.$mice_booking_id;
                        }
                        else
                        {
                            $arr_rec = array('mice_action_module'=>$mice_id,'mice_reservation_id'=>$mice_id);
                            
                            DB::update('reservation',$arr_rec,'id='.$value['recode']);
                        }
                    }
                    /** xoa nhung booking khong nam trong mang **/
                    DB::delete('mice_booking','id not in ('.$list_booking_id.') AND mice_reservation_id='.$mice_id);
                    if(Url::get('act')=='CONFIRM')
                    {
                        if(isset($room_levels_confirm))
                            credit_reservation($room_levels_confirm,$info_confirm,true,$mice_id);
                    }
                    
                }
                else
                {
                    DB::delete('mice_booking','mice_reservation_id='.$mice_id);
                }
                
                //////////////// end BOOKING //////////////////////////
                
                if(isset($_REQUEST['extra']))
                {
                    //System::debug($_REQUEST['extra']);exit();
                    $list_extra_id = '0';
                    foreach($_REQUEST['extra'] as $key=>$value)
                    {
                        if($value['extra_id']=='' OR $value['extra_id']==0)
                        {
                            $mice_extra = array(
                                                    'mice_reservation_id'   => $mice_id,
                                                    'service_id'            => $value['service_id'],
                                                    'type'                  => $value['type'],
                                                    'name'                  => $value['service_name'],
                                                    'start_date'            => Date_Time::to_orc_date($value['start_date']),
                                                    'end_date'              => Date_Time::to_orc_date($value['end_date']),
                                                    'quantity'              => System::calculate_number($value['quantity']),
                                                    'price'                 => System::calculate_number($value['price']),
                                                    'percentage_discount'   => System::calculate_number($value['percentage_discount']),
                                                    'amount_discount'       => System::calculate_number($value['amount_discount']),
                                                    'total_before_tax'      => System::calculate_number($value['total_before_tax']),
                                                    'service_rate'          => System::calculate_number($value['service_rate']),
                                                    'tax_rate'              => System::calculate_number($value['tax_rate']),
                                                    'total_amount'          => System::calculate_number($value['total_amount']),
                                                    'net_price'             => isset($value['net_price'])?1:0,
                                                    'close'                 => isset($value['close'])?1:0,
                                                    'note'                  => $value['note']
                                                    );
                            if(Url::get('act')=='CONFIRM')
                            {
                                    if(isset($value['net_price']))
                                    {
                                        $extra_price = System::calculate_number($value['price']) / ((1+System::calculate_number($value['service_rate'])/100)*(1+System::calculate_number($value['tax_rate'])/100));
                                    }
                                    else
                                    {
                                        $extra_price = System::calculate_number($value['price']);
                                    }
                                    
                                    $start_time = Date_Time::to_time($value['start_date']);
                                    $end_time = Date_Time::to_time($value['end_date']);
                                    
                                    $count_date = (($end_time-$start_time)/(24*3600))+1;
                                    
                                    $extra_price = $extra_price*$count_date;
                                    
                                    $extra_total_before_tax = $extra_price;
                                    $extra_price = $extra_price - ((System::calculate_number($value['percentage_discount'])*$extra_price)/100);
                                    $extra_price = $extra_price - System::calculate_number($value['amount_discount']);
                                    $extra_total_amount = round(($extra_price * ((1+System::calculate_number($value['service_rate'])/100)*(1+System::calculate_number($value['tax_rate'])/100)))*$value['quantity'],0);
                                    $lastest_item_bill = DB::fetch('SELECT id,bill_number FROM extra_service_invoice ORDER BY id DESC');
                    				$total_bill = intval(str_replace('ES','',$lastest_item_bill['bill_number']))+1;
                    				$total_bill = (strlen($total_bill)<2)?'0'.$total_bill:$total_bill;					
                    				$bill_number = 'ES'.$total_bill;
                                    
                                    $extra_service = array(
                                                            'note'                  => $value['note'],
                                                            'total_amount'          => $extra_total_amount,
                                                            'reservation_room_id'   => '',
                                                            'time'                  => time(),
                                                            'user_id'               => User::id(),
                                                            'bill_number'           => $bill_number,
                                                            'portal_id'             => PORTAL_ID,
                                                            'payment_type'          => 'SERVICE',
                                                            'tax_rate'              => System::calculate_number($value['tax_rate']),
                                                            'total_before_tax'      => $extra_total_before_tax,
                                                            'service_rate'          => System::calculate_number($value['service_rate']),
                                                            'net_price'             => isset($value['net_price'])?1:0,
                                                            'close'                 => isset($value['close'])?1:0,
                                                            'mice_reservation_id'   => $mice_id,
                                                            'type'                  => $value['type']
                                                            );
                                    $id_extra_service = DB::insert('extra_service_invoice',$extra_service);
                                    
                                    $id_extra_service_table = DB::insert('extra_service_invoice_table',array('from_date'=>Date_Time::to_orc_date($value['start_date']),'to_date'=>Date_Time::to_orc_date($value['end_date']),'invoice_id'=>$id_extra_service));
                                    //System::debug($value);
                                    for( $time=$start_time;$time<=$end_time;$time+=24*3600 )
                                    {
                                        $currency_id = (HOTEL_CURRENCY == 'VND')?'USD':'VND';
            	                        $exchange_rate = DB::fetch('select id,exchange from currency where id=\''.$currency_id.'\'','exchange');
                                        $extra_service_invoice = array(
                                                                        'invoice_id'            =>$id_extra_service,
                                                                        'service_id'            => $value['service_id'],
                                                                        'name'                  => $value['service_name'],
                                                                        'quantity'              => $value['quantity'],
                                                                        'in_date'               => Date_Time::convert_time_to_ora_date($time),
                                                                        'percentage_discount'   => System::calculate_number($value['percentage_discount']),
                                                                        'amount_discount'       => System::calculate_number($value['amount_discount']),
                                                                        'note'                  => $value['note'],
                                                                        'time'                  => Date_Time::to_time($time)+$this->calc_time(date('H:i')),
                                                                        'price'                 => System::calculate_number($value['price']),
                                                                        'usd_price'             => System::calculate_number($value['price'])/$exchange_rate,
                                                                        'table_id'              => $id_extra_service_table
                                                                        );
                                        DB::insert('extra_service_invoice_detail',$extra_service_invoice);
                                    }
                                    
                            }
                            if($value['id']!='')
                            {
                                $mice_extra_id = $value['id'];
                                DB::update('mice_extra_service',$mice_extra,'id='.$mice_extra_id);
                            }
                            else
                            {
                                $mice_extra_id = DB::insert('mice_extra_service',$mice_extra);
                            }
                            $list_extra_id .= ','.$mice_extra_id;
                        }
                        else
                        {
                            $arr_exs = array('mice_action_module'=>$mice_id,'mice_reservation_id'=>$mice_id);
                            
                            DB::update('extra_service_invoice',$arr_exs,'id='.$value['extra_id']);
                        }
                    }
                    /** xoa nhung booking khong nam trong mang **/
                    DB::delete('mice_extra_service','id not in ('.$list_extra_id.') AND mice_reservation_id='.$mice_id);
                    
                }
                else
                {
                    DB::delete('mice_extra_service','mice_reservation_id='.$mice_id);
                }
                
                //////////////// end EXTRA //////////////////////////
                
                if(isset($_REQUEST['ticket']))
                {
                    $list_ticket_id = '0';
                    foreach($_REQUEST['ticket'] as $key=>$value)
                    {
                        if($value['ticket_reservation_id']=='' OR $value['ticket_reservation_id']==0)
                        {
                            $quantity_ticket = System::calculate_number($value['quantity']) - System::calculate_number($value['discount_quantity']);
                            $amount_ticket = System::calculate_number($value['price']) - (System::calculate_number($value['price']) * ( System::calculate_number($value['discount_rate'])/100 ));
                            
                            $mice_ticket = array(
                                                    'mice_reservation_id'   => $mice_id,
                                                    'time'                  => Date_Time::to_time($value['date_used']),
                                                    'note'                  => $value['note'],
                                                    'tax_rate'              => '',
                                                    'service_rate'          => '',
                                                    'total_before_tax'      => ($quantity_ticket*$amount_ticket)*10/11,
                                                    'total'                 => $quantity_ticket*$amount_ticket,
                                                    'ticket_id'             => $value['ticket_id'],
                                                    'ticket_area_id'        => $value['ticket_area_id'],
                                                    'quantity'              => System::calculate_number($value['quantity']),
                                                    'price'                 => System::calculate_number($value['price']),
                                                    'discount_quantity'     => System::calculate_number($value['discount_quantity']),
                                                    'discount_rate'         => System::calculate_number($value['discount_rate'])
                                                    );
                            if(Url::get('act')=='CONFIRM')
                            {
                                $ticket_reservation_id = DB::insert('ticket_reservation',
                                                                    array(
                                                                            'time'              => Date_Time::to_time($value['date_used'])
                                                                            ,'customer_id'      => Url::get('customer_id')
                                                                            ,'traveller_id'      => Url::get('traveller_id')
                                                                            ,'ticket_area_id'   => $value['ticket_area_id']
                                                                            ,'customer_address' => $customer_address
                                                                            ,'customer_name'    => $customer_name
                                                                            ,'note'             => $value['name']
                                                                            ,'total'            => $quantity_ticket*$amount_ticket
                                                                            ,'total_before_tax' => ($quantity_ticket*$amount_ticket)*10/11
                                                                            ,'user_id'          => User::id()
                                                                            ,'portal_id'        => PORTAL_ID
                                                                            ,'mice_reservation_id'=> $mice_id,
                                                                        )
                                                                    );
                                
                                $ticket_invoice_id = DB::insert('ticket_invoice',
                                                                        array(
                                                                                'ticket_id'             => $value['ticket_id']
                                                                                ,'ticket_area_id'       => $value['ticket_area_id']
                                                                                ,'quantity'             => System::calculate_number($value['quantity'])
                                                                                ,'price'                => System::calculate_number($value['price'])
                                                                                ,'total'                => $quantity_ticket*$amount_ticket
                                                                                ,'user_id'              => User::id()
                                                                                ,'time'                 => Date_Time::to_time($value['date_used'])
                                                                                ,'date_used'            => Date_Time::to_orc_date($value['date_used'])
                                                                                ,'portal_id'            => PORTAL_ID
                                                                                ,'ticket_reservation_id'=> $ticket_reservation_id
                                                                                ,'discount_quantity'    => System::calculate_number($value['discount_quantity'])
                                                                                ,'discount_rate'        => System::calculate_number($value['discount_rate'])
                                                                            )
                                                                    );
                                $service_ticket = DB::fetch_all('Select 
                                                            ticket_service.*,
                                                            ticket_service.price as price_before_discount,
                                                            (ticket_service.price - ticket_service_grant.discount_money) - (ticket_service.price - ticket_service_grant.discount_money) * ticket_service_grant.discount_percent/100 as price,
                                                            ticket_service_grant.discount_money,
                                                            ticket_service_grant.discount_percent 
                                                        from 
                                                            ticket_service_grant inner join ticket_service on ticket_service.id = ticket_service_grant.ticket_service_id  
                                                        where 
                                                            ticket_service_grant.ticket_id = '.$value['ticket_id'].' order by ticket_service.id ');
                                foreach($service_ticket as $key_ticket => $value_ticket)
                                {
                                    $detail_ticket = array(
                                                'ticket_invoice_id'=>$ticket_invoice_id,
                                                'ticket_id'=>$value['ticket_id'],
                                                'ticket_service_id'=>$key_ticket,
                                                'ticket_service_name'=>$value_ticket['name_1'].'/'.$value_ticket['name_2'],
                                                'quantity'=>System::calculate_number($value['quantity']),
                                                'price_before_discount'=>$value_ticket['price_before_discount'],
                                                'total_before_discount'=>$value_ticket['price_before_discount'] * System::calculate_number($value['quantity']),
                                                'price'=>$value_ticket['price'],
                                                'total'=>$value_ticket['price'] * System::calculate_number($value['quantity']),
                                                'discount_money'=> $value_ticket['discount_money'],
                                                'discount_percent'=> $value_ticket['discount_percent'],
                                                    );
                                    DB::insert('ticket_invoice_detail',$detail_ticket);
                                }
                            }
                            if($value['id']!='')
                            {
                                $mice_ticket_id = $value['id'];
                                DB::update('mice_ticket_reservation',$mice_ticket,'id='.$mice_ticket_id);
                                $log_description .= '<b>- '.Portal::language('update').': </b>mice ticket '.$mice_ticket_id.'<br/>';
                            }
                            else
                            {
                                $mice_ticket_id = DB::insert('mice_ticket_reservation',$mice_ticket);
                                $log_description .= '<b>- '.Portal::language('add').': </b>mice ticket '.$mice_ticket_id.'<br/>';
                            }
                            $log_description .= Portal::language('ticket').':'.($value['ticket_id']!=''?DB::fetch('select name from ticket where id='.$value['ticket_id'],'name'):'').'
                                             | '.Portal::language('ticket_area').': '.($value['ticket_area_id']!=''?DB::fetch('select name from ticket_area where id='.$value['ticket_area_id'],'name'):'').'
                                             | '.Portal::language('quantity').': '.$value['quantity'].'
                                             | '.Portal::language('price').': '.$value['price'].'
                                             | '.Portal::language('in_date').': '.$value['date_used'].'
                                             | '.Portal::language('discount_quantity').': '.$value['discount_quantity'].'
                                             | '.Portal::language('discount_rate').': '.$value['discount_rate'].'
                                             | '.Portal::language('note').': '.$value['note'].'
                                            <br/>';
                            $list_ticket_id .= ','.$mice_ticket_id;
                        }
                        else
                        {
                            $arr_ticket = array('mice_action_module'=>$mice_id,'mice_reservation_id'=>$mice_id);
                            DB::update('ticket_reservation',$arr_ticket,'id='.$value['ticket_reservation_id']);
                        }
                    }
                    /** xoa nhung booking khong nam trong mang **/
                    DB::delete('mice_ticket_reservation','id not in ('.$list_ticket_id.') AND mice_reservation_id='.$mice_id);
                    
                }
                else
                {
                    DB::delete('mice_ticket_reservation','mice_reservation_id='.$mice_id);
                }
                
                //////////////// end TICKET //////////////////////////
                
                if(isset($_REQUEST['bar']))
                {
                    $list_bar_id = '0';
                    foreach($_REQUEST['bar'] as $key=>$value)
                    {
                        if($value['bar_reservation_id']=='' OR $value['bar_reservation_id']==0)
                        {
                            $info = array(
                                        'mice_reservation_id'   => $mice_id,
                                        'time_in'               => Date_Time::to_time($value['in_date'])+$this->calc_time($value['time_in']),
                                        'time_out'              => Date_Time::to_time($value['in_date'])+$this->calc_time($value['time_out']),
                                        'full_rate'             => isset($value['full_rate'])?1:0,
                                        'full_charge'           => isset($value['full_charge'])?1:0,
                                        'tax_rate'              => System::calculate_number($value['tax_rate']),
                                        'foc'                   => 0,
                                        'banquet_order_type'    => $value['banquet_order_type'],
                                        'table_id'              => $value['table_id'],
                                        'bar_id'                => $value['bar_id'],
                                        'num_people'            => $value['num_people'],
                                        'order_person'          => '',
                                        'discount'              => System::calculate_number($value['discount']),
                                        'discount_percent'      => System::calculate_number($value['discount_percent']),
                                        'service_rate'          => System::calculate_number($value['service_rate'])
                                        );
                            if(Url::get('act')=='CONFIRM')
                            {
                                $info_confirm = array(
                                    'arrival_time'      => Date_Time::to_time($value['in_date'])+$this->calc_time($value['time_in']),
                                    'departure_time'    => Date_Time::to_time($value['in_date'])+$this->calc_time($value['time_out']),
                                    'full_rate'         => isset($value['full_rate'])?1:0,
                                    'full_charge'       => isset($value['full_charge'])?1:0,
                                    'note'              => '',
                                    'customer_id'       => Url::get('customer_id'),
                                    'agent_name'        => Url::get('customer_name'),
                                    'receiver_name'     => Url::get('traveller_name'),
                                    'tax_rate'          => System::calculate_number($value['tax_rate']),
                                    'foc'               => 0,
                                    'banquet_order_type'=> $value['banquet_order_type'],
                                    'table_id'          => $value['table_id'],
                                    'bar_id'            => $value['bar_id'],
                                    'num_people'        => $value['num_people'],
                                    'order_person'      => '',
                                    'mice_reservation_id'=>$mice_id,
                                    'discount'          => System::calculate_number($value['discount']),
                                    'discount_percent'  => System::calculate_number($value['discount_percent']),
                                    'bar_fee_rate'      => System::calculate_number($value['service_rate'])
                                    );
                                $product_list_confirm = array();
                            }
                            if($value['id']!='')
                            {
                                $mice_bar_id = $value['id'];
                                DB::update('mice_restaurant',$info,'id='.$mice_bar_id);
                                $log_description .= '<b>- '.Portal::language('update').': </b>mice restaurant '.$mice_bar_id.'<br/>';
                            }
                            else
                            {
                                $mice_bar_id = DB::insert('mice_restaurant',$info);
                                $log_description .= '<b>- '.Portal::language('add').': </b>mice restaurant '.$mice_bar_id.'<br/>';
                            }
                            $log_description .= Portal::language('bar').':'.($value['bar_id']!=''?DB::fetch('select name from bar where id='.$value['bar_id'],'name'):'').'
                                             | '.Portal::language('bar_table').': '.($value['table_id']!=''?DB::fetch('select name from bar_table where id='.$value['table_id'],'name'):'').'
                                             | '.Portal::language('full_rate').': '.(isset($value['full_rate'])?1:0).'
                                             | '.Portal::language('full_charge').': '.(isset($value['full_charge'])?1:0).'
                                             | '.Portal::language('time_in').': '.$value['time_in'].' '.$value['in_date'].'
                                             | '.Portal::language('time_out').': '.$value['time_out'].' '.$value['in_date'].'
                                             | '.Portal::language('bar_fee_rate').': '.$value['service_rate'].'
                                             | '.Portal::language('tax_rate').': '.$value['tax_rate'].'
                                             | '.Portal::language('discount').': '.$value['discount'].'
                                             | '.Portal::language('discount_percent').': '.$value['discount_percent'].'
                                             | '.Portal::language('num_people').': '.$value['num_people'].'
                                             | '.Portal::language('banquet_order_type').': '.$value['banquet_order_type'].'
                                            <br/>';
                            /** **/
                            $list_bar_id .= ','.$mice_bar_id;
                            /** **/
                            if(isset($value['child']))
                            {
                                $list_bar_product_id = '0';
                                foreach($value['child'] as $k1=>$v1)
                                {
                                    $bar_product = array(
                                                            'mice_restaurant_id'    => $mice_bar_id,
                                                            'product_id'            => $v1['product_id'],
                                                            'quantity'              => System::calculate_number($v1['quantity']),
                                                            'price_id'              => $v1['price_id'],
                                                            'quantity_discount'     => System::calculate_number($v1['quantity_discount']),
                                                            'discount_rate'         => System::calculate_number($v1['discount_rate']),
                                                            'price'                 => System::calculate_number($v1['price']),
                                                            'unit_id'               => $v1['unit_id'],
                                                            'note'                  => $v1['note']
                                                            );
                                    if(Url::get('act')=='CONFIRM')
                                    {
                                        $product_list_confirm[$k1] = array(
                                                        'product_id'        => $v1['product_id'],
                                                        'quantity'          => System::calculate_number($v1['quantity']),
                                                        'price_id'          => $v1['price_id'],
                                                        'quantity_discount' => System::calculate_number($v1['quantity_discount']),
                                                        'discount_rate'     => System::calculate_number($v1['discount_rate']),
                                                        'price'             => System::calculate_number($v1['price']),
                                                        'unit_id'           => $v1['unit_id'],
                                                        'note'              => $v1['note']
                                                        );
                                    }
                                    if($v1['id']!='')
                                    {
                                        $bar_product_id = $v1['id'];
                                        DB::update('mice_restaurant_product',$bar_product,'id='.$bar_product_id);
                                        $log_description .= Portal::language('update').' '.Portal::language('product');
                                    }
                                    else
                                    {
                                        $bar_product_id = DB::insert('mice_restaurant_product',$bar_product);
                                        $log_description .= Portal::language('add').' '.Portal::language('product');
                                    }
                                    $log_description .= $v1['product_id'].' '.Portal::language('quantity').' '.$v1['quantity'].' '.Portal::language('price').' '.$v1['price'].' '.Portal::language('quantity_discount').' '.$v1['quantity_discount'].' '.Portal::language('discount_rate').' '.$v1['discount_rate'].'<br/>';
                                    $list_bar_product_id .= ','.$bar_product_id;
                                }
                                DB::delete('mice_restaurant_product','id not in ('.$list_bar_product_id.') AND mice_restaurant_id='.$mice_bar_id);
                            }
                            if(Url::get('act')=='CONFIRM')
                            {
                                booked_table('booked',$info_confirm,$product_list_confirm,'SAVE');
                            }
                        }
                        else
                        {
                            $arr_bar = array('mice_action_module'=>$mice_id,'mice_reservation_id'=>$mice_id);
                            
                            DB::update('bar_reservation',$arr_bar,'id='.$value['bar_reservation_id']);
                        } 
                    }
                    /** xoa nhung dat ban khong nam trong mang **/
                    $bar_delete = DB::select_all('mice_restaurant','id not in ('.$list_bar_id.') AND mice_reservation_id='.$mice_id);
                    foreach($bar_delete as $k_de=>$v_de)
                    {
                        DB::delete('mice_restaurant','id='.$v_de['id']);
                        DB::delete('mice_restaurant_product','mice_restaurant_id='.$v_de['id']);
                    }
                    
                }
                else
                {
                    $bar_delete = DB::select_all('mice_restaurant','mice_reservation_id='.$mice_id);
                    foreach($bar_delete as $k_de=>$v_de)
                    {
                        DB::delete('mice_restaurant','id='.$v_de['id']);
                        DB::delete('mice_restaurant_product','mice_restaurant_id='.$v_de['id']);
                    }
                }
                
                ////////////// end BAR /////////////////////////
                
                if(isset($_REQUEST['vending']))
                {
                    $list_vending_id = '0';
                    foreach($_REQUEST['vending'] as $key=>$value)
                    {
                        if($value['ve_reservation_id']=='' OR $value['ve_reservation_id']==0)
                        {
                            $info = array(
                                        'mice_reservation_id'   => $mice_id,
                                        'time_in'               => Date_Time::to_time($value['in_date'])+$this->calc_time($value['time_in']),
                                        'full_rate'             => isset($value['full_rate'])?1:0,
                                        'full_charge'           => isset($value['full_charge'])?1:0,
                                        'tax_rate'              => System::calculate_number($value['tax_rate']),
                                        'foc'                   => 0,
                                        'department_id'         => $value['department_id'],
                                        'department_code'       => $value['department_code'],
                                        'discount'              => System::calculate_number($value['discount']),
                                        'discount_percent'      => System::calculate_number($value['discount_percent']),
                                        'service_rate'          => System::calculate_number($value['service_rate']),
                                        'exchange_rate'         => $value['exchange_rate']
                                        );
                            if(Url::get('act')=='CONFIRM')
                            {
                                $info_confirm = array(
                                    'status'            => 'CHECKIN',
                                    'arrival_time'      => Date_Time::to_time($value['in_date'])+$this->calc_time($value['time_in']),
                                    'time'              => Date_Time::to_time($value['in_date'])+$this->calc_time($value['time_in']),
                                    'time_in'              => Date_Time::to_time($value['in_date'])+$this->calc_time($value['time_in']),
                                    'full_rate'         => isset($value['full_rate'])?1:0,
                                    'full_charge'       => isset($value['full_charge'])?1:0,
                                    'note'              => '',
                                    'customer_id'       => Url::get('customer_id'),
                                    'receiver_name'     => Url::get('traveller_name'),
                                    'tax_rate'          => System::calculate_number($value['tax_rate']),
                                    'foc'               => 0,
                                    'department_id'     => $value['department_id'],
                                    'department_code'   => $value['department_code'],
                                    'mice_reservation_id'=>$mice_id,
                                    'discount'          => System::calculate_number($value['discount']),
                                    'discount_percent'  => System::calculate_number($value['discount_percent']),
                                    'bar_fee_rate'      => System::calculate_number($value['service_rate']),
                                    'exchange_rate'     => $value['exchange_rate'],
                                    'user_id'           => User::id(),
                                    'lastest_edited_time'=>time(),
                                    'lastest_edited_user_id'=>User::id(),
                                    'portal_id'         => PORTAL_ID
                                    );
                                $vending_id = DB::insert('ve_reservation',$info_confirm);
                                $product_list_confirm = array();
                                $total_before_tax_vending = 0;
                                $total_vending = 0;
                                $code_vending = '';
                                if(strlen($vending_id)<6)
                                {
                                    for($i=1;$i<=(6-strlen($vending_id));$i++)
                                        $code_vending .= '0';
                                }
                                $code_vending = date('Y').'-'.$code_vending.$vending_id;
                            }
                            if($value['id']!='')
                            {
                                $mice_vending_id = $value['id'];
                                DB::update('mice_vending',$info,'id='.$mice_vending_id);
                                $log_description .= '<b>- '.Portal::language('update').': </b>mice vending '.$mice_bar_id.'<br/>';
                            }
                            else
                            {
                                $mice_vending_id = DB::insert('mice_vending',$info);
                                $log_description .= '<b>- '.Portal::language('add').': </b>mice vending '.$mice_bar_id.'<br/>';
                            }
                            $log_description .= Portal::language('department_code').':'.$value['department_code'].'
                                             | '.Portal::language('full_rate').': '.(isset($value['full_rate'])?1:0).'
                                             | '.Portal::language('full_charge').': '.(isset($value['full_charge'])?1:0).'
                                             | '.Portal::language('in_date').': '.$value['time_in'].' '.$value['in_date'].'
                                             | '.Portal::language('bar_fee_rate').': '.$value['service_rate'].'
                                             | '.Portal::language('tax_rate').': '.$value['tax_rate'].'
                                             | '.Portal::language('discount').': '.$value['discount'].'
                                             | '.Portal::language('discount_percent').': '.$value['discount_percent'].'
                                            <br/>';
                            /** **/
                            $list_vending_id .= ','.$mice_vending_id;
                            /** **/
                            if(isset($value['child']))
                            {
                                $list_vending_product_id = '0';
                                foreach($value['child'] as $k1=>$v1)
                                {
                                    $vending_product = array(
                                                            'mice_vending_id'       => $mice_vending_id,
                                                            'product_id'            => $v1['product_id'],
                                                            'quantity'              => System::calculate_number($v1['quantity']),
                                                            'price_id'              => $v1['price_id'],
                                                            'quantity_discount'     => System::calculate_number($v1['quantity_discount']),
                                                            'discount_rate'         => System::calculate_number($v1['discount_rate']),
                                                            'price'                 => System::calculate_number($v1['price']),
                                                            'unit_id'               => $v1['unit_id'],
                                                            'note'                  => $v1['note']
                                                            );
                                    if(Url::get('act')=='CONFIRM')
                                    {
                                        $price = System::calculate_number($v1['price']);
                                        if(isset($value['full_rate']))
                                        {
                                            $price = $price / ( (1+System::calculate_number($value['service_rate'])/100)*(1+System::calculate_number($value['tax_rate'])/100) );
                                        }
                                        elseif(isset($value['full_charge']))
                                        {
                                            $price = $price / (1+System::calculate_number($value['service_rate'])/100);
                                        }
                                        $discount_percent = System::calculate_number($v1['discount_rate']) + System::calculate_number($value['discount_percent']);
                                        $discount = System::calculate_number($value['discount']);
                                        $quantity = System::calculate_number($v1['quantity']) - System::calculate_number($v1['quantity_discount']);
                                        $price = $price * $quantity;
                                        $price = $price - ($price*($discount_percent/100));
                                        $total_before_tax_vending += $price;
                                        
                                        $product_list_confirm = array(
                                                        'bar_reservation_id'=> $vending_id,
                                                        'product_id'        => $v1['product_id'],
                                                        'price'             => System::calculate_number($v1['price']),
                                                        'product_price'     => System::calculate_number($v1['price']),
                                                        'quantity_discount' => System::calculate_number($v1['quantity_discount']),
                                                        'discount_rate'     => System::calculate_number($v1['discount_rate']),
                                                        'quantity'          => System::calculate_number($v1['quantity']),
                                                        'price_id'          => $v1['price_id'],
                                                        'name'              => $v1['product_name'],
                                                        'unit_id'           => $v1['unit_id'],
                                                        'note'              => $v1['note'],
                                                        'department_id'     => $value['department_id']
                                                        );
                                        DB::insert('ve_reservation_product',$product_list_confirm);
                                    }
                                    if($v1['id']!='')
                                    {
                                        $vending_product_id = $v1['id'];
                                        DB::update('mice_vending_product',$vending_product,'id='.$vending_product_id);
                                        $log_description .= Portal::language('update').' '.Portal::language('product');
                                    }
                                    else
                                    {
                                        $vending_product_id = DB::insert('mice_vending_product',$vending_product);
                                        $log_description .= Portal::language('add').' '.Portal::language('product');
                                    }
                                    $log_description .= $v1['product_id'].' '.Portal::language('quantity').' '.$v1['quantity'].' '.Portal::language('price').' '.$v1['price'].' '.Portal::language('quantity_discount').' '.$v1['quantity_discount'].' '.Portal::language('discount_rate').' '.$v1['discount_rate'].'<br/>';
                                    $list_vending_product_id .= ','.$vending_product_id;
                                }
                                DB::delete('mice_vending_product','id not in ('.$list_vending_product_id.') AND mice_vending_id='.$mice_vending_id);
                            }
                            if(Url::get('act')=='CONFIRM')
                            {
                                $total_vending = $total_before_tax_vending * ((1+System::calculate_number($value['service_rate'])/100)*(1+System::calculate_number($value['tax_rate'])/100));
                                DB::update('ve_reservation',array('total_before_tax'=>$total_before_tax_vending,'total'=>$total_vending,'code'=>$code_vending),'id='.$vending_id);
                            }
                        }
                        else
                        {
                            $arr_ve = array('mice_action_module'=>$mice_id,'mice_reservation_id'=>$mice_id);
                            
                            DB::update('ve_reservation',$arr_ve,'id='.$value['ve_reservation_id']);
                        }
                    }
                    /** xoa nhung dat ban khong nam trong mang **/
                    $vending_delete = DB::select_all('mice_vending','id not in ('.$list_vending_id.') AND mice_reservation_id='.$mice_id);
                    foreach($vending_delete as $k_de=>$v_de)
                    {
                        DB::delete('mice_vending','id='.$v_de['id']);
                        DB::delete('mice_vending_product','mice_vending_id='.$v_de['id']);
                    }
                    
                }
                else
                {
                    $vending_delete = DB::select_all('mice_vending','mice_reservation_id='.$mice_id);
                    foreach($vending_delete as $k_de=>$v_de)
                    {
                        DB::delete('mice_vending','id='.$v_de['id']);
                        DB::delete('mice_vending_product','mice_vending_id='.$v_de['id']);
                    }
                }
                
                ////////////// end VENDING /////////////////////////
                
                if(isset($_REQUEST['party']))
                {
                    $list_party_id='0';
                    foreach($_REQUEST['party'] as $key=>$value)
                    {
                        if($value['party_reservation_id']=='' OR $value['party_reservation_id']==0)
                        {
                            $info = array(
                                        'mice_reservation_id'   => $mice_id,
                                        'party_type'            => $value['party_type'],
                                        'total_before_tax'      => System::calculate_number($value['total_before_tax']),
                                        'total'                 => System::calculate_number($value['total']),
                                        'service_rate'          => System::calculate_number($value['service_rate']),
                                        'tax_rate'              => System::calculate_number($value['tax_rate']),
                                        'user_id'               => User::id(),
                                        'time_in'               => Date_Time::to_time($value['in_date'])+$this->calc_time($value['time_in']),
                                        'time_out'              => Date_Time::to_time($value['in_date'])+$this->calc_time($value['time_out']),
                                        'promotions'            => $value['promotions'],
                                        'portal_id'             => PORTAL_ID
                                        );
                            if($value['id']!='')
                            {
                                $mice_party_id = $value['id'];
                                DB::update('mice_party',$info,'id='.$mice_party_id);
                            }
                            else
                            {
                                $mice_party_id = DB::insert('mice_party',$info);
                            }
                            /** **/
                            $list_party_id .= ','.$mice_party_id;
                            /** **/
                            if(Url::get('act')=='CONFIRM')
                            {
                                $info_confirm = array(
                                    'full_name'             => Url::get('traveller_name'),
                                    'address'                => '',
                                    'identity_number'       => '',
                                    'email'                 => '',
                                    'party_type'            => $value['party_type'],
                                    'company_name'          => Url::get('company_name'),
                                    'company_address'       => '',
                                    'company_phone'         => '',
                                    'total_before_tax'      => System::calculate_number($value['total_before_tax']),
                                    'total'                 => System::calculate_number($value['total']),
                                    'extra_service_rate'    => System::calculate_number($value['service_rate']),
                                    'vat'                   => System::calculate_number($value['tax_rate']),
                                    'user_id'               => User::id(),
                                    'time'                  => $value['in_date'],
                                    'checkin_time'          => $value['time_in'],
                                    'checkout_time'         => $value['time_out'],
                                    'promotions'            => $value['promotions'],
                                    'portal_id'             => PORTAL_ID
                                    );
                                $mi_banquet_room_confirm = array();
                                $mi_meeting_room_confirm = array();
                                $mi_vegetarian_confirm = array();
                                $mi_product_confirm = array();
                                $mi_eating_product_confirm = array();
                                $mi_service_confirm = array();
                            }
                            if(isset($value['child_room']))
                            {
                                $list_party_room_id = '0';
                                foreach($value['child_room'] as $k1=>$v1)
                                {
                                    $child_room = array(
                                                        'mice_party_id' => $mice_party_id,
                                                        'party_room_id' => $v1['party_room_id'],
                                                        'time_type'     => $v1['time_type'],
                                                        'address'       => $v1['address'],
                                                        'price'         => System::calculate_number($v1['price']),
                                                        'note'          => $v1['note'],
                                                        'type'          => $v1['type']
                                                        );
                                    if(Url::get('act')=='CONFIRM')
                                    {
                                        $child_room_confirm = array(
                                                    'party_room_id' => $v1['party_room_id'],
                                                    'time_type'     => $v1['time_type'],
                                                    'address'       => $v1['address'],
                                                    'price'         => System::calculate_number($v1['price']),
                                                    'note'          => $v1['note'],
                                                    'type'          => $v1['type']
                                                    );
                                        if($v1['type']==1)
                                        {
                                            $mi_meeting_room_confirm[$k1] = $child_room_confirm;
                                        }
                                        else
                                        {
                                            $mi_banquet_room_confirm[$k1] = $child_room_confirm;
                                        }
                                    }
                                    if($v1['id']!='')
                                    {
                                        $mice_party_room_id = $v1['id'];
                                        DB::update('mice_party_detail_room',$child_room,'id='.$mice_party_room_id);
                                        
                                    }
                                    else
                                    {
                                        $mice_party_room_id = DB::insert('mice_party_detail_room',$child_room);
                                    }
                                    $list_party_room_id .= ','.$mice_party_room_id;
                                }
                                DB::delete('mice_party_detail_room','id not in ('.$list_party_room_id.') AND mice_party_id='.$mice_party_id);
                            }
                            
                            if(isset($value['child_product']))
                            {
                                $list_party_product_id = '0';
                                foreach($value['child_product'] as $k2=>$v2)
                                {
                                    $child_product = array(
                                                        'mice_party_id' => $mice_party_id,
                                                        'product_id'    => $v2['product_id'],
                                                        'product_name'  => $v2['product_name'],
                                                        'quantity'      => System::calculate_number($v2['quantity']),
                                                        'price'         => System::calculate_number($v2['price']),
                                                        'unit_name'     => $v2['unit_name'],
                                                        'type'          => $v2['type']
                                                        );
                                    if(Url::get('act')=='CONFIRM')
                                    {
                                        $child_product_confirm = array(
                                                    'product_id'    => $v2['product_id'],
                                                    'name'          => $v2['product_name'],
                                                    'quantity'      => System::calculate_number($v2['quantity']),
                                                    'price'         => System::calculate_number($v2['price']),
                                                    'unit'          => $v2['unit_name'],
                                                    'type'          => $v2['type']
                                                    );
                                        if($v2['type']==1)
                                        {
                                            $mi_product_confirm[$k2] = $child_product_confirm;
                                        }
                                        elseif($v2['type']==2)
                                        {
                                            $mi_eating_product_confirm[$k2] = $child_product_confirm;
                                        }
                                        elseif($v2['type']==3)
                                        {
                                            $mi_service_confirm[$k2] = $child_product_confirm; 
                                        }
                                        else
                                        {
                                            $mi_vegetarian_confirm[$k2] = $child_product_confirm;
                                        }
                                    }
                                    if($v2['id']!='')
                                    {
                                        $mice_party_product_id = $v2['id'];
                                        DB::update('mice_party_detail_product',$child_product,'id='.$mice_party_product_id);
                                    }
                                    else
                                    {
                                        $mice_party_product_id = DB::insert('mice_party_detail_product',$child_product);
                                    }
                                    $list_party_product_id .= ','.$mice_party_product_id;
                                }
                                DB::delete('mice_party_detail_product','id not in ('.$list_party_product_id.') AND mice_party_id='.$mice_party_id);
                            }
                            
                            if(Url::get('act')=='CONFIRM')
                            {
                                create_banquet_book($info_confirm,$mi_banquet_room_confirm,$mi_meeting_room_confirm,$mi_vegetarian_confirm,$mi_product_confirm,$mi_eating_product_confirm,$mi_service_confirm,true,$mice_id);
                            }
                        }
                        else
                        {
                            $arr_party = array('mice_action_module'=>$mice_id,'mice_reservation_id'=>$mice_id);
                            
                            DB::update('party_reservation',$arr_party,'id='.$value['party_reservation_id']);
                        }
                    }
                    /** xoa nhung ban ghi khong nam trong mang **/
                    $party_delete = DB::select_all('mice_party','id not in ('.$list_party_id.') AND mice_reservation_id='.$mice_id);
                    foreach($party_delete as $k_de=>$v_de)
                    {
                        DB::delete('mice_party','id='.$v_de['id']);
                        DB::delete('mice_party_detail_room','mice_party_id='.$v_de['id']);
                        DB::delete('mice_party_detail_product','mice_party_id='.$v_de['id']);
                    }
                }
                else
                {
                    $party_delete = DB::select_all('mice_party','mice_reservation_id='.$mice_id);
                    foreach($party_delete as $k_de=>$v_de)
                    {
                        DB::delete('mice_party','id='.$v_de['id']);
                        DB::delete('mice_party_detail_room','mice_party_id='.$v_de['id']);
                        DB::delete('mice_party_detail_product','mice_party_id='.$v_de['id']);
                    }
                }
                /** ************* END save data*************** **/
                if(!DB::exists('select id from mice_party where mice_reservation_id='.$mice_id) 
                    AND !DB::exists('select id from party_reservation where mice_reservation_id='.$mice_id) 
                    AND !DB::exists('select id from mice_restaurant where mice_reservation_id='.$mice_id) 
                    AND !DB::exists('select id from bar_reservation where mice_reservation_id='.$mice_id) 
                    AND !DB::exists('select id from mice_vending where mice_reservation_id='.$mice_id) 
                    AND !DB::exists('select id from ve_reservation where mice_reservation_id='.$mice_id)
                    AND !DB::exists('select id from mice_booking where mice_reservation_id='.$mice_id) 
                    AND !DB::exists('select id from reservation where mice_reservation_id='.$mice_id) 
                    AND !DB::exists('select id from mice_ticket_reservation where mice_reservation_id='.$mice_id) 
                    AND !DB::exists('select id from ticket_reservation where mice_reservation_id='.$mice_id)
                    AND !DB::exists('select id from mice_spa where mice_reservation_id='.$mice_id) 
                    AND !DB::exists('select id from mice_extra_service where mice_reservation_id='.$mice_id) 
                    AND !DB::exists('select id from extra_service_invoice where mice_reservation_id='.$mice_id))
                {
                    DB::delete('mice_reservation','id='.$mice_id);
                    System::log('DELETE','Delete Mice '.$mice_id,'Delete Mice Id: '.$mice_id.'',$mice_id);
                    Url::redirect('mice_reservation');
                }
                else
                {
                    System::log($log_type,$log_title,$log_description,$log_id);
                    Url::redirect('mice_reservation',array('cmd'=>'edit','id'=>$mice_id));
                }
            }
        }
        
    }
	function draw()
    {
        $cond = '1=1 ';
		$this->map = array();
        $this->map['deposit']       = 0;
        $this->map['payment']       = 0;
        $this->map['portal_id']     = isset($this->map['portal_id'])?$this->map['portal_id']:PORTAL_ID;
        
        $active_department = DB::fetch_all('Select 
                                                department.code as id,
                                                department.name_'.Portal::language().' as name 
                                            from 
                                                department 
                                                inner join portal_department on department.code = portal_department.department_code 
                                            where
                                                portal_department.portal_id = \''.$this->map['portal_id'].'\'
                                                and department.parent_id = 0 AND department.code != \'WH\'
                                                and department.mice_use=1
                                            ORDER BY
                                                department.stt
                                        ');
        $active_department['EXS'] = array('id'=>'EXS','name'=>Portal::language('extra_service'));
        /** lay tat ca cac khu ban hang **/
        require_once 'packages/hotel/packages/vending/includes/php/vending.php';
        $this->map['list_area_vending'] = get_area_vending();
        $this->map['area_vending_options'] = '<option value="">'.Portal::language('choose_area_vending').'</option>';
        foreach($this->map['list_area_vending'] as $key=>$value)
		{
			$this->map['area_vending_options'].='<option value="'.$value['id'].'">'.$value['name'].'</option>';
		}
        /** end khu ban hang **/
        
        /** lay tat ca san pham da duoc khai bao gia tren he thong **/
        $sql = 'select  
                    product_price_list.id as id,
                    product.id as product_id,
                    product.type as product_type,
                    product.name_'.Portal::language().' as name, 
                    product_price_list.price,
                    product_price_list.id as price_id,
                    unit.id as unit_id,
                    unit.name_'.Portal::language().' as unit_name,
                    department.code as department_code
				from 	
                    product_price_list
					INNER JOIN product ON product_price_list.product_id = product.id
                    INNER JOIN unit ON product.unit_id=unit.id
                    INNER JOIN department ON department.code=product_price_list.department_code
				where
                    product_price_list.portal_id = \''.PORTAL_ID.'\'
                    and product.status=\'avaiable\'
			';
		$this->map['all_products'] = DB::fetch_all($sql);
        /** end all product **/
        
        /** lay tat ca cac loai tiec **/
        $all_party = DB::select_all('party_type');
        $this->map['all_party'] = '<option value="">'.Portal::language('Choose_party_type').'</option>';
        foreach($all_party as $k1=>$v1)
        {
            $this->map['all_party'] .= '<option value="'.$v1['id'].'">'.$v1['name'].'</option>';
        }
        /** end full tiec **/
        
        /** lay tat ca khuyen mai tiec **/
        $this->map['all_party_promotions'] = DB::select_all('party_promotions');
        /** end khuyen mai **/
        
        /** lay tat ca loai san pham tiec **/
        $this->map['all_product_party_type']  = '<option value="1">'.Portal::language('drinking_menu').'</option>';
        $this->map['all_product_party_type'] .= '<option value="2">'.Portal::language('eating_menu').'</option>';
        $this->map['all_product_party_type'] .= '<option value="3">'.Portal::language('service').'</option>';
        $this->map['all_product_party_type'] .= '<option value="4">'.Portal::language('vegetarian').'</option>';
        /** end san pham tiec **/
        
        /** lay tat ca phong tiec **/
        $this->map['banquet_rooms'] = $this->get_banquet_room();
		$this->map['banquet_room_options'] = '<option value="">'.Portal::language('choose_banquet_room').'</option>';
		foreach($this->map['banquet_rooms'] as $key=>$value)
		{
			$this->map['banquet_room_options'].='<option value="'.$value['id'].'">'.$value['name'].'</option>';
		}
        /** end phong tiec **/
        
        /** lay ti gia quy doi **/
        $currency_id = (HOTEL_CURRENCY == 'VND')?'USD':'VND';
		$this->map['exchange_rate'] = DB::fetch('select id,exchange from currency where id=\''.$currency_id.'\'','exchange');
        /** end ti gia **/
        
        /** lay tat ca hang phong **/
        $this->map['room_level'] = DB::fetch_all('SELECT
					room_level.*
				FROM
					room_level
				WHERE
					room_level.portal_id = \''.PORTAL_ID.'\'');
        $this->map['room_level_option'] = '<option value="">'.Portal::language('choose_room_level').'</option>';
        foreach($this->map['room_level'] as $key=>$value)
		{
			$this->map['room_level_option'].='<option value="'.$value['id'].'">'.$value['name'].'</option>';
		}
        /** end hang phong **/
        
        /** lay tat ca nha hang tren portal **/
        $this->map['list_bars'] = DB::select_all('bar','portal_id=\''.PORTAL_ID.'\'','name');
        $this->map['bar_options'] = '<option value="">'.Portal::language('choose_restaurant_bar').'</option>';
        foreach($this->map['list_bars'] as $key=>$value)
		{
			$this->map['bar_options'].='<option value="'.$value['id'].'">'.$value['name'].'</option>';
		}
        /** end lay tat ca nha hang **/
        
        /** lay tat ca cac ban tren portal **/
        $this->map['list_bar_tables'] = DB::select_all('bar_table','portal_id=\''.PORTAL_ID.'\'','name');
        $this->map['bar_table_options'] = '<option value="">'.Portal::language('choose_bar_table').'</option>';
        foreach($this->map['list_bar_tables'] as $key=>$value)
		{
			$this->map['bar_table_options'].='<option value="'.$value['id'].'">'.$value['name'].'</option>';
		}
        /** end lay cac ban **/
        
        /** lay tat ca dich vu mo rong **/
        $this->map['all_extra_service'] = DB::select_all('extra_service','status=\'SHOW\'','name');
        $this->map['extra_service_options'] = '<option value="">'.Portal::language('select_service').'</option>';
        foreach($this->map['all_extra_service'] as $key=>$value)
		{
			$this->map['extra_service_options'].='<option value="'.$value['id'].'">'.$value['name'].'</option>';
		}
        /** end lay dich vu mo rong **/
        
        /** lay tat ca quay ban ve **/
        $this->map['all_ticket_area'] = DB::select_all('ticket_area','portal_id=\''.PORTAL_ID.'\'','name');
        $this->map['ticket_area_options'] = '<option value="">'.Portal::language('select_ticket_area').'</option>';
        foreach($this->map['all_ticket_area'] as $key=>$value)
        {
            $this->map['ticket_area_options'].='<option value="'.$value['id'].'">'.$value['name'].'</option>';
        }
        /** end lay tat ca quay ban ve **/
        
        /** lay tat ca cac loai ve **/
        $this->map['all_ticket'] = DB::fetch_all('select 
                                                        ticket.*,
                                                        ticket_area_type.id as id,
                                                        ticket.id as ticket_id,
                                                        ticket_area_type.ticket_area_id
                                                    from 
                                                        ticket_area_type
                                                        inner join ticket on ticket.id = ticket_area_type.ticket_id
                                                    where 
                                                        ticket.portal_id = \''.PORTAL_ID.'\'
                                                    ');
        $all_ticket = array();
        $this->map['ticket_options'] = '<option value="">'.Portal::language('select_ticket').'</option>';
        foreach($this->map['all_ticket'] as $key=>$value)
        {
            $all_ticket[$value['ticket_id']]['id'] = $value['ticket_id'];
            $all_ticket[$value['ticket_id']]['name'] = $value['name'];
            $this->map['all_ticket'][$key]['desc'] = '';
            $this->map['all_ticket'][$key]['price'] = 0;
            $this->map['all_ticket'][$key]['price_before_discount'] = 0;
            $service = DB::fetch_all('Select 
                                        ticket_service.*,
                                        ticket_service.price as price_before_discount,
                                        (ticket_service.price - ticket_service_grant.discount_money) - (ticket_service.price - ticket_service_grant.discount_money) * ticket_service_grant.discount_percent/100 as price 
                                    from 
                                        ticket_service_grant inner join ticket_service on ticket_service.id = ticket_service_grant.ticket_service_id
                                    where ticket_service_grant.ticket_id = '.$value['ticket_id'].' order by ticket_service.id ');
            foreach($service as $k => $v)
            {
                $this->map['all_ticket'][$key]['desc'] .=  $v['name_1'].' ('.(System::display_number($v['price'])).'), ';
                $this->map['all_ticket'][$key]['price'] += $v['price'];
                $this->map['all_ticket'][$key]['price_before_discount'] += $v['price_before_discount'];
            }
        }
        foreach($all_ticket as $key=>$value)
        {
            $this->map['ticket_options'].='<option value="'.$value['id'].'">'.$value['name'].'</option>';
        }
        //System::debug($this->map['all_ticket']);exit();
        /** end lay tat ca cac loai ve **/
        
        
        if(Url::get('id'))
        {
            $mice = DB::fetch('
                            SELECT
                                mice_reservation.*,
                                TO_CHAR(mice_reservation.start_date,\'DD/MM/YYYY\') as start_date,
                                TO_CHAR(mice_reservation.end_date,\'DD/MM/YYYY\') as end_date,
                                TO_CHAR(mice_reservation.cut_of_date,\'DD/MM/YYYY\') as cut_of_date,
                                customer.name as customer_name,
                                traveller.first_name || \' \' || traveller.last_name as traveller_name
                            FROM
                                mice_reservation
                                left join customer on customer.id=mice_reservation.customer_id
                                left join traveller on traveller.id=mice_reservation.traveller_id
                            WHERE
                                mice_reservation.id='.Url::get('id').'
                            ');
            $cond .= ' AND mice_reservation.id='.Url::get('id');
            $this->map['deposit'] = DB::fetch("SELECT sum(amount) as total FROM payment WHERE bill_id=".Url::get('id')." AND type='MICE' AND type_dps is not null ","total");
            $this->map['payment'] = DB::fetch("SELECT sum(amount) as total FROM payment WHERE bill_id=".Url::get('id')." AND type='MICE' AND type_dps is null ","total");
            
            if($mice['traveller_name']==' ')
                $mice['traveller_name'] = '';
            
            $this->map += $mice;
            $_REQUEST += $mice;
        }
        else
        {
            $this->map['total_amount']  = 0;
            $this->map['code_mice']  = date('d').date('m').date('Y');
            
        }
        
        foreach($active_department as $key=>$value)
        {
            $active_department[$key]['item']        = array();
            $active_department[$key]['count_item']  = 0;
            $active_department[$key]['icon']        = '';
            $active_department[$key]['description'] = '';
            /** reservation  **/
            if($value['id']=='REC')
            {
                $cond_rec = '';
                if(Url::get('id') AND DB::exists("SELECT id from reservation where mice_action_module=".Url::get('id')) AND !isset($_REQUEST['booking']))
                {
                    $cond_rec = ' reservation.mice_action_module='.Url::get('id');
                }
                elseif(Url::get('from_department')==$value['id'] AND !isset($_REQUEST['booking']))
                {
                    $cond_rec = " reservation.id=".Url::get('key_department')."";
                }
                if(Url::get('id') AND !isset($_REQUEST['booking']))
                {
                    $mi_booking = DB::select_all("mice_booking","mice_reservation_id=".Url::get('id'));
                    foreach($mi_booking as $k=>$v)
                    {
                        $_REQUEST['booking'][$k] = $v;
                        $_REQUEST['booking'][$k]['time_in']     = date('H:i',$v['time_in']);
                        $_REQUEST['booking'][$k]['from_date']   = date('d/m/Y',$v['time_in']);
                        $_REQUEST['booking'][$k]['time_out']    = date('H:i',$v['time_out']);
                        $_REQUEST['booking'][$k]['to_date']     = date('d/m/Y',$v['time_out']);
                        $_REQUEST['booking'][$k]['recode']      = '';
                    }
                }
                if($cond_rec!='')
                {
                    $get_reservation = DB::fetch_all("SELECT
                                                            reservation_room.*,
                                                            TO_CHAR(reservation_room.arrival_time,'DD/MM/YYYY') as arrival_time,
                                                            TO_CHAR(reservation_room.departure_time,'DD/MM/YYYY') as departure_time,
                                                            reservation.customer_id,
                                                            customer.name as customer_name,
                                                            traveller.first_name || ' ' || traveller.last_name as traveller_name
                                                        FROM
                                                            reservation_room
                                                            inner join reservation on reservation.id=reservation_room.reservation_id
                                                            inner join customer on reservation.customer_id=customer.id
                                                            left join traveller on traveller.id=reservation_room.traveller_id
                                                        WHERE
                                                            ".$cond_rec."
                                                            AND reservation_room.status!='CANCEL'
                                                     ");
                    
                    foreach($get_reservation as $key_rec=>$value_rec)
                    {
                        $folio = MiceReservationDB::get_total_room($value_rec['id']);
                        $total_amount = 0;
                        foreach($folio as $f_id=>$f_value)
                        {
                            $f_Service_amount = $f_value['amount']*$f_value['service_rate']/100;
                            $f_tax_amount = ($f_value['amount']+$f_Service_amount)*$f_value['tax_rate']/100;
                            
                            if($f_value['type']=='DISCOUNT' or $f_value['type']=='DEPOSIT'){
                                $total_amount -= $f_value['amount']+$f_Service_amount+$f_tax_amount;
                            }else{
                                $total_amount += $f_value['amount']+$f_Service_amount+$f_tax_amount;
                            }
                            
                        }
                        $this->map['customer_id'] = $value_rec['customer_id'];
                        $this->map['customer_name'] = $value_rec['customer_name'];
                        $_REQUEST['customer_id'] = $value_rec['customer_id'];
                        $_REQUEST['customer_name'] = $value_rec['customer_name'];
                        if($value_rec['traveller_id']!='')
                        {
                            $this->map['traveller_id'] = $value_rec['traveller_id'];
                            $this->map['traveller_name'] = $value_rec['traveller_name'];
                            $_REQUEST['traveller_id'] = $value_rec['traveller_id'];
                            $_REQUEST['traveller_name'] = $value_rec['traveller_name'];
                        }
                        $key_mi = $value_rec['room_level_id'].$value_rec['time_in'].$value_rec['time_out'].$value_rec['price'];
                        
                        $_REQUEST['booking'][$key_mi]['id'] = '';
                        $_REQUEST['booking'][$key_mi]['room_level_id'] = $value_rec['room_level_id'];
                        $total_amount = number_format($total_amount,0,'','');
                        if(!isset($_REQUEST['booking'][$key_mi]['quantity']))
                        {
                            $_REQUEST['booking'][$key_mi]['quantity'] = 1;
                            $_REQUEST['booking'][$key_mi]['total_amount'] = $total_amount;
                        }
                        else
                        {
                            $_REQUEST['booking'][$key_mi]['quantity']++;
                            $_REQUEST['booking'][$key_mi]['total_amount'] += $total_amount;
                        }
                        $_REQUEST['booking'][$key_mi]['child']          = $value_rec['child'];
                        $_REQUEST['booking'][$key_mi]['adult']          = $value_rec['adult'];
                        $_REQUEST['booking'][$key_mi]['price']          = $value_rec['price'];
                        $_REQUEST['booking'][$key_mi]['exchange_rate']  = $value_rec['exchange_rate'];
                        $_REQUEST['booking'][$key_mi]['usd_price']      = $value_rec['usd_price'];
                        $_REQUEST['booking'][$key_mi]['net_price']      = $value_rec['net_price'];
                        $_REQUEST['booking'][$key_mi]['service_rate']   = $value_rec['service_rate'];
                        $_REQUEST['booking'][$key_mi]['tax_rate']       = $value_rec['tax_rate'];
                        $_REQUEST['booking'][$key_mi]['note']           = $value_rec['note'];
                        $_REQUEST['booking'][$key_mi]['time_in']        = date('H:i',$value_rec['time_in']);
                        $_REQUEST['booking'][$key_mi]['from_date']      = date('d/m/Y',$value_rec['time_in']);
                        $_REQUEST['booking'][$key_mi]['time_out']       = date('H:i',$value_rec['time_out']);
                        $_REQUEST['booking'][$key_mi]['to_date']        = date('d/m/Y',$value_rec['time_out']);
                        $_REQUEST['booking'][$key_mi]['recode']         = $value_rec['reservation_id'];
                    }
                }
                $active_department[$key]['icon'] = 'packages/hotel/packages/mice/skins/img/icon_sale.png';
                $active_department[$key]['description'] = Portal::language('access_room_and_housekeeing');
            }
            
            /** extra service **/
            if($value['id']=='EXS')
            {
                $cond_exs = '';
                if(Url::get('id') AND DB::exists("SELECT id from extra_service_invoice where mice_action_module=".Url::get('id')) AND !isset($_REQUEST['extra']))
                {
                    $cond_exs = ' extra_service_invoice.mice_action_module='.Url::get('id');
                }
                elseif(Url::get('from_department')==$value['id'] AND !isset($_REQUEST['extra']))
                {
                    $cond_exs = " extra_service_invoice.id=".Url::get('key_department')."";
                }
                if(Url::get('id') AND !isset($_REQUEST['extra']))
                {
                    $_REQUEST['extra'] = array();
                    $mi_booking = DB::select_all("mice_extra_service","mice_reservation_id=".Url::get('id'));
                    
                    foreach($mi_booking as $k=>$v)
                    {
                        $_REQUEST['extra'][$k] = $v;
                        //$_REQUEST['extra'][$k]['in_date'] = date('d/m/Y',$v['time']);
                        $_REQUEST['extra'][$k]['start_date'] = Date_Time::convert_orc_date_to_date($v['start_date'],'/');
                        $_REQUEST['extra'][$k]['end_date'] = Date_Time::convert_orc_date_to_date($v['end_date'],'/');
                        $_REQUEST['extra'][$k]['extra_id'] = '';
                        
                    }
                }
                if($cond_exs!='')
                {
                    $get_extra_service = DB::fetch_all("
                                                        SELECT
                                                            extra_service_invoice_detail.*,
                                                            TO_CHAR(extra_service_invoice_detail.in_date,'DD/MM/YYYY') as in_date,
                                                            NVL(extra_service_invoice.net_price,0) as net_price,
                                                            extra_service_invoice.service_rate,
                                                            extra_service_invoice.tax_rate,
                                                            extra_service_invoice.reservation_room_id,
                                                            extra_service_invoice.close,
                                                            NVL(extra_service_invoice_detail.change_quantity,0) as change_quantity
                                                        FROM
                                                           extra_service_invoice_detail
                                                           inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id
                                                        WHERE
                                                           ".$cond_exs." 
                                                        ");
                    foreach($get_extra_service as $key_exs=>$value_exs)
                    {
                        $amount = $value_exs['price'];
                        if($value_exs['net_price']==1)
                        {
                            $amount = $amount / ( (1+$value_exs['service_rate']/100)*(1+$value_exs['tax_rate']/100) );
                        }
                        $quantity = $value_exs['quantity'];
                        $amount = $amount * $quantity;
                        $amount = $amount - ($amount*($value_exs['percentage_discount']/100));
                        $amount = $amount - $value_exs['amount_discount'];
                        $total_amount = $amount * ( (1+$value_exs['service_rate']/100)*(1+$value_exs['tax_rate']/100) );
                        $_REQUEST['extra'][$key_exs.'_EXS']['id'] = '';
                        $_REQUEST['extra'][$key_exs.'_EXS']['total_amount'] = $total_amount;
                        $_REQUEST['extra'][$key_exs.'_EXS']['reservation_room_id'] = $value_exs['reservation_room_id'];
                        $_REQUEST['extra'][$key_exs.'_EXS']['time'] = $value_exs['time'];
                        $_REQUEST['extra'][$key_exs.'_EXS']['tax_rate'] = $value_exs['tax_rate'];
                        $_REQUEST['extra'][$key_exs.'_EXS']['total_before_tax'] = $amount;
                        $_REQUEST['extra'][$key_exs.'_EXS']['service_rate'] = $value_exs['service_rate'];
                        $_REQUEST['extra'][$key_exs.'_EXS']['net_price'] = $value_exs['net_price'];
                        $_REQUEST['extra'][$key_exs.'_EXS']['close'] = $value_exs['close'];
                        $_REQUEST['extra'][$key_exs.'_EXS']['service_id'] = $value_exs['service_id'];
                        $_REQUEST['extra'][$key_exs.'_EXS']['name'] = $value_exs['name'];
                        $_REQUEST['extra'][$key_exs.'_EXS']['quantity'] = $quantity;
                        $_REQUEST['extra'][$key_exs.'_EXS']['percentage_discount'] = $value_exs['percentage_discount'];
                        $_REQUEST['extra'][$key_exs.'_EXS']['amount_discount'] = $value_exs['amount_discount'];
                        $_REQUEST['extra'][$key_exs.'_EXS']['price'] = $value_exs['price'];
                        $_REQUEST['extra'][$key_exs.'_EXS']['mice_reservation_id'] = '';
                        $_REQUEST['extra'][$key_exs.'_EXS']['note'] = $value_exs['note'];
                        $_REQUEST['extra'][$key_exs.'_EXS']['in_date'] = $value_exs['in_date'];
                        $_REQUEST['extra'][$key_exs.'_EXS']['extra_id'] = $value_exs['invoice_id'];
                    }
                }
                $active_department[$key]['icon'] = 'packages/hotel/packages/mice/skins/img/icon_sale.png';
                $active_department[$key]['description'] = Portal::language('extra_service');
                
            }
            /** restaurant  **/
            if($value['id']=='RES')
            {
                $cond_res = '';
                if(Url::get('id') AND DB::exists("SELECT id from bar_reservation where mice_action_module=".Url::get('id')) AND !isset($_REQUEST['bar']))
                {
                    $cond_res = ' bar_reservation.mice_action_module='.Url::get('id');
                }
                elseif(Url::get('from_department')==$value['id'] AND !isset($_REQUEST['bar']))
                {
                    $cond_res = " bar_reservation.id=".Url::get('key_department')."";
                }
                if(Url::get('id') AND !isset($_REQUEST['bar']))
                {
                    $_REQUEST['bar'] = array();
                    $mi_bar = DB::select_all("mice_restaurant","mice_reservation_id=".Url::get('id'));
                    $mi_bar_product = DB::fetch_all("
                                                    SELECT
                                                        mice_restaurant_product.*,
                                                        product.name_".Portal::language()." as product_name,
                                                        unit.name_".Portal::language()." as unit_name
                                                    FROM
                                                        mice_restaurant_product
                                                        inner join mice_restaurant on mice_restaurant.id=mice_restaurant_product.mice_restaurant_id
                                                        inner join product on product.id=mice_restaurant_product.product_id
                                                        left join unit on unit.id=mice_restaurant_product.unit_id
                                                    WHERE
                                                        mice_restaurant.mice_reservation_id=".Url::get('id')."
                                                    ");
                    foreach($mi_bar_product as $k=>$v)
                    {
                        if(!isset($_REQUEST['bar'][$v['mice_restaurant_id']]))
                        {
                            $_REQUEST['bar'][$v['mice_restaurant_id']]                          = $mi_bar[$v['mice_restaurant_id']];
                            $_REQUEST['bar'][$v['mice_restaurant_id']]['in_date']               = date('d/m/Y',$mi_bar[$v['mice_restaurant_id']]['time_in']);
                            $_REQUEST['bar'][$v['mice_restaurant_id']]['time_in']               = date('H:i',$mi_bar[$v['mice_restaurant_id']]['time_in']);
                            $_REQUEST['bar'][$v['mice_restaurant_id']]['time_out']              = date('H:i',$mi_bar[$v['mice_restaurant_id']]['time_out']);
                            $_REQUEST['bar'][$v['mice_restaurant_id']]['bar_reservation_id']    = '';
                            $_REQUEST['bar'][$v['mice_restaurant_id']]['child']                 = array();
                        }
                        $_REQUEST['bar'][$v['mice_restaurant_id']]['child'][$k]                 = $v;
                    }
                }
                if($cond_res!='')
                {
                    $get_bar_reservation = DB::fetch_all("
                                                            SELECT
                                                                bar_reservation_product.*,
                                                                unit.name_".Portal::language()." as unit_name,
                                                                bar_reservation.id as bar_reservation_id,
                                                                bar_reservation_table.table_id as table_id,
                                                                bar_reservation.bar_id,
                                                                bar_reservation.arrival_time,
                                                                bar_reservation.departure_time,
                                                                bar_reservation.full_rate,
                                                                bar_reservation.full_charge,
                                                                bar_reservation.bar_fee_rate as service_rate,
                                                                bar_reservation.tax_rate as tax_rate,
                                                                bar_reservation.foc,
                                                                bar_reservation.banquet_order_type,
                                                                bar_reservation_table.num_people,
                                                                '' as order_person,
                                                                bar_reservation.discount,
                                                                bar_reservation.discount_percent,
                                                                '' as service_name,
                                                                bar_reservation.time
                                                            FROM
                                                                bar_reservation_product
                                                                inner join bar_reservation on bar_reservation.id=bar_reservation_product.bar_reservation_id
                                                                inner join bar_reservation_table on bar_reservation_table.bar_reservation_id=bar_reservation.id
                                                                left join unit on unit.id=bar_reservation_product.unit_id
                                                            WHERE
                                                                ".$cond_res."   
                                                                AND bar_reservation.status!='CANCEL'                           
                                                        ");
                    foreach($get_bar_reservation as $key_res=>$value_res)
                    {
                        if(!isset($_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']))
                        {
                            $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['id'] = '';
                            $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['mice_reservation_id'] = '';
                            $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['table_id'] = $value_res['table_id'];
                            $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['bar_id'] = $value_res['bar_id'];
                            $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['time_in'] = date('H:i',$value_res['arrival_time']);
                            $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['time_out'] = date('H:i',$value_res['departure_time']);
                            $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['full_charge'] = $value_res['full_charge'];
                            $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['full_rate'] = $value_res['full_rate'];
                            $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['service_rate'] = $value_res['service_rate'];
                            $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['tax_rate'] = $value_res['tax_rate'];
                            $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['foc'] = $value_res['foc'];
                            $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['banquet_order_type'] = $value_res['banquet_order_type'];
                            $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['num_people'] = $value_res['num_people'];
                            $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['order_person'] = $value_res['order_person'];
                            $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['discount'] = $value_res['discount'];
                            $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['discount_percent'] = $value_res['discount_percent'];
                            $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['service_name'] = $value_res['service_name'];
                            $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['in_date'] = date('d/m/Y',$value_res['time']);
                            $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['bar_reservation_id'] = $value_res['bar_reservation_id'];
                        }
                        $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['child'][$value_res['id']]['id'] = $value_res['id'];
                        $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['child'][$value_res['id']]['mice_restaurant_id'] = $value_res['bar_reservation_id'].'_RES';
                        $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['child'][$value_res['id']]['product_id'] = $value_res['product_id'];
                        $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['child'][$value_res['id']]['quantity'] = $value_res['quantity'];
                        $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['child'][$value_res['id']]['price_id'] = $value_res['price_id'];
                        $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['child'][$value_res['id']]['quantity_discount'] = $value_res['quantity_discount'];
                        $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['child'][$value_res['id']]['discount_rate'] = $value_res['discount_rate'];
                        $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['child'][$value_res['id']]['price'] = $value_res['price'];
                        $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['child'][$value_res['id']]['unit_id'] = $value_res['unit_id'];
                        $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['child'][$value_res['id']]['note'] = $value_res['note'];
                        $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['child'][$value_res['id']]['product_name'] = $value_res['name'];
                        $_REQUEST['bar'][$value_res['bar_reservation_id'].'_RES']['child'][$value_res['id']]['unit_name'] = $value_res['unit_name'];
                    }
                }
                $active_department[$key]['icon'] = 'packages/hotel/packages/mice/skins/img/icon_res.png';
                $active_department[$key]['description'] = Portal::language('access_food_and_dirnk');
            }
            
            /** vending  **/
            if($value['id']=='VENDING')
            {
                $cond_ve = '';
                if(Url::get('id') AND DB::exists("SELECT id from ve_reservation where mice_action_module=".Url::get('id')) AND !isset($_REQUEST['vending']))
                {
                    $cond_ve = ' ve_reservation.mice_action_module='.Url::get('id');
                }
                elseif(Url::get('from_department')==$value['id'] AND !isset($_REQUEST['vending']))
                {
                    $cond_ve = " ve_reservation.id=".Url::get('key_department')."";
                }
                if(Url::get('id') AND !isset($_REQUEST['vending']))
                {
                    $_REQUEST['vending'] = array();
                    $mi_vending = DB::select_all("mice_vending","mice_reservation_id=".Url::get('id'));
                    $mi_vending_product = DB::fetch_all("
                                                    SELECT
                                                        mice_vending_product.*,
                                                        product.name_".Portal::language()." as product_name,
                                                        unit.name_".Portal::language()." as unit_name
                                                    FROM
                                                        mice_vending_product
                                                        inner join mice_vending on mice_vending.id=mice_vending_product.mice_vending_id
                                                        inner join product on product.id=mice_vending_product.product_id
                                                        left join unit on unit.id=mice_vending_product.unit_id
                                                    WHERE
                                                        mice_vending.mice_reservation_id=".Url::get('id')."
                                                    ");
                    foreach($mi_vending_product as $k=>$v)
                    {
                        if(!isset($_REQUEST['vending'][$v['mice_vending_id']]))
                        {
                            $_REQUEST['vending'][$v['mice_vending_id']]              = $mi_vending[$v['mice_vending_id']];
                            $_REQUEST['vending'][$v['mice_vending_id']]['in_date']   = date('d/m/Y',$mi_vending[$v['mice_vending_id']]['time_in']);
                            $_REQUEST['vending'][$v['mice_vending_id']]['time_in']   = date('H:i',$mi_vending[$v['mice_vending_id']]['time_in']);
                            $_REQUEST['vending'][$v['mice_vending_id']]['ve_reservation_id'] = '';
                            $_REQUEST['vending'][$v['mice_vending_id']]['child']     = array();
                        }
                        $_REQUEST['vending'][$v['mice_vending_id']]['child'][$k] = $v;
                    }
                }
                if($cond_ve!='')
                {
                    $get_ve_reservation = DB::fetch_all("
                                                            SELECT
                                                                ve_reservation_product.*,
                                                                unit.name_".Portal::language()." as unit_name,
                                                                ve_reservation.id as ve_reservation_id,
                                                                ve_reservation.department_id,
                                                                ve_reservation.department_code,
                                                                ve_reservation.exchange_rate,
                                                                ve_reservation.full_rate,
                                                                ve_reservation.full_charge,
                                                                ve_reservation.bar_fee_rate as service_rate,
                                                                ve_reservation.tax_rate as tax_rate,
                                                                ve_reservation.foc,
                                                                ve_reservation.discount,
                                                                ve_reservation.discount_percent,
                                                                '' as service_name,
                                                                ve_reservation.time
                                                            FROM
                                                                ve_reservation_product
                                                                inner join ve_reservation on ve_reservation.id=ve_reservation_product.bar_reservation_id
                                                                left join unit on unit.id=ve_reservation_product.unit_id
                                                            WHERE
                                                                ".$cond_ve."                              
                                                        ");
                    foreach($get_ve_reservation as $key_ve=>$value_ve)
                    {
                        if(!isset($_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']))
                        {
                            $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['id'] = '';
                            $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['mice_reservation_id'] = '';
                            $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['department_id'] = $value_ve['department_id'];
                            $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['department_code'] = $value_ve['department_code'];
                            $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['time_in'] = date('H:i',$value_ve['time']);
                            $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['exchange_rate'] = $value_ve['exchange_rate'];
                            $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['full_charge'] = $value_ve['full_charge'];
                            $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['full_rate'] = $value_ve['full_rate'];
                            $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['service_rate'] = $value_ve['service_rate'];
                            $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['tax_rate'] = $value_ve['tax_rate'];
                            $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['foc'] = $value_ve['foc'];
                            $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['discount'] = $value_ve['discount'];
                            $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['discount_percent'] = $value_ve['discount_percent'];
                            $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['service_name'] = $value_ve['service_name'];
                            $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['in_date'] = date('d/m/Y',$value_ve['time']);
                            $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['ve_reservation_id'] = $value_ve['ve_reservation_id'];
                        }
                        $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['child'][$value_ve['id']]['id'] = $value_ve['id'];
                        $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['child'][$value_ve['id']]['mice_vending_id'] = $value_ve['bar_reservation_id'].'_RES';
                        $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['child'][$value_ve['id']]['product_id'] = $value_ve['product_id'];
                        $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['child'][$value_ve['id']]['quantity'] = $value_ve['quantity'];
                        $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['child'][$value_ve['id']]['price_id'] = $value_ve['price_id'];
                        $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['child'][$value_ve['id']]['quantity_discount'] = $value_ve['quantity_discount'];
                        $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['child'][$value_ve['id']]['discount_rate'] = $value_ve['discount_rate'];
                        $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['child'][$value_ve['id']]['price'] = $value_ve['price'];
                        $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['child'][$value_ve['id']]['unit_id'] = $value_ve['unit_id'];
                        $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['child'][$value_ve['id']]['note'] = $value_ve['note'];
                        $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['child'][$value_ve['id']]['product_name'] = $value_ve['name'];
                        $_REQUEST['vending'][$value_ve['ve_reservation_id'].'_RES']['child'][$value_ve['id']]['unit_name'] = $value_ve['unit_name'];
                    }
                }
                $active_department[$key]['icon'] = 'packages/hotel/packages/mice/skins/img/icon_res.png';
                $active_department[$key]['description'] = Portal::language('access_food_and_dirnk');
            }
            
            /** party **/
            if($value['id']=='BANQUET')
            {
                $cond_party = '';
                if(Url::get('id') AND DB::exists("SELECT id from party_reservation where mice_action_module=".Url::get('id')) AND !isset($_REQUEST['party']))
                {
                    $cond_party = ' party_reservation.mice_action_module='.Url::get('id');
                }
                elseif(Url::get('from_department')==$value['id'] AND !isset($_REQUEST['party']))
                {
                    $cond_party = " party_reservation.id=".Url::get('key_department')."";
                }
                
                if(Url::get('id') AND !isset($_REQUEST['party']))
                {
                    $_REQUEST['party'] = array();
                    $mi_party = DB::select_all("mice_party","mice_reservation_id=".Url::get('id'));
                    $mi_party_product = DB::fetch_all("
                                                    SELECT
                                                        mice_party_detail_product.*
                                                    FROM
                                                        mice_party_detail_product
                                                        inner join mice_party on mice_party.id=mice_party_detail_product.mice_party_id
                                                    WHERE
                                                        mice_party.mice_reservation_id=".Url::get('id')."
                                                    ");
                    $mi_party_room = DB::fetch_all("
                                                    SELECT
                                                        mice_party_detail_room.*
                                                    FROM
                                                        mice_party_detail_room
                                                        inner join mice_party on mice_party.id=mice_party_detail_room.mice_party_id
                                                    WHERE
                                                        mice_party.mice_reservation_id=".Url::get('id')."
                                                    ");
                    foreach($mi_party_product as $k=>$v)
                    {
                        if(!isset($_REQUEST['party'][$v['mice_party_id']]))
                        {
                            $_REQUEST['party'][$v['mice_party_id']]                 = $mi_party[$v['mice_party_id']];
                            $_REQUEST['party'][$v['mice_party_id']]['in_date']      = date('d/m/Y',$mi_party[$v['mice_party_id']]['time_in']);
                            $_REQUEST['party'][$v['mice_party_id']]['time_in']      = date('H:i',$mi_party[$v['mice_party_id']]['time_in']);
                            $_REQUEST['party'][$v['mice_party_id']]['time_out']     = date('H:i',$mi_party[$v['mice_party_id']]['time_out']);
                            $_REQUEST['party'][$v['mice_party_id']]['party_reservation_id'] = '';
                            $_REQUEST['party'][$v['mice_party_id']]['child_product']= array();
                            $_REQUEST['party'][$v['mice_party_id']]['child_room']   = array();
                        }
                        $_REQUEST['party'][$v['mice_party_id']]['child_product'][$k] = $v;
                    }
                    foreach($mi_party_room as $k=>$v)
                    {
                        if(!isset($_REQUEST['party'][$v['mice_party_id']]))
                        {
                            $_REQUEST['party'][$v['mice_party_id']] = $mi_party[$v['mice_party_id']];
                            $_REQUEST['party'][$v['mice_party_id']]['in_date']      = date('d/m/Y',$mi_party[$v['mice_party_id']]['time_in']);
                            $_REQUEST['party'][$v['mice_party_id']]['time_in']      = date('H:i',$mi_party[$v['mice_party_id']]['time_in']);
                            $_REQUEST['party'][$v['mice_party_id']]['time_out']     = date('H:i',$mi_party[$v['mice_party_id']]['time_out']);
                            $_REQUEST['party'][$v['mice_party_id']]['party_reservation_id'] = '';
                            $_REQUEST['party'][$v['mice_party_id']]['child_product']= array();
                            $_REQUEST['party'][$v['mice_party_id']]['child_room']   = array();
                        }
                        $_REQUEST['party'][$v['mice_party_id']]['child_room'][$k] = $v;
                    }
                }
                
                if($cond_party!='')
                {
                    $get_party_reservation = DB::fetch_all("SELECT party_reservation.* FROM party_reservation WHERE".$cond_party);
                    $get_party_product = DB::fetch_all("
                                                        SELECT
                                                            party_reservation_detail.*,
                                                            party_reservation_detail.product_unit as unit_name
                                                        FROM
                                                            party_reservation_detail
                                                            inner join party_reservation on party_reservation.id=party_reservation_detail.party_reservation_id
                                                        WHERE
                                                            ".$cond_party."
                                                    ");
                    $get_party_room = DB::fetch_all("
                                                    SELECT
                                                        party_reservation_room.*
                                                    FROM
                                                        party_reservation_room
                                                        inner join party_reservation on party_reservation.id=party_reservation_room.party_reservation_id
                                                    WHERE
                                                        ".$cond_party."
                                                    ");
                    foreach($get_party_product as $p_party_id=>$p_party_room)
                    {
                        if(!isset($_REQUEST['party'][$p_party_room['party_reservation_id'].'_PARTY']))
                        {
                            $_REQUEST['party'][$p_party_room['party_reservation_id'].'_PARTY']['id'] = '';
                            $_REQUEST['party'][$p_party_room['party_reservation_id'].'_PARTY']['mice_reservation_id'] = '';
                            $_REQUEST['party'][$p_party_room['party_reservation_id'].'_PARTY']['party_reservation_id'] = $p_party_room['party_reservation_id'];
                            $_REQUEST['party'][$p_party_room['party_reservation_id'].'_PARTY']['party_type'] = $get_party_reservation[$p_party_room['party_reservation_id']]['party_type'];
                            $_REQUEST['party'][$p_party_room['party_reservation_id'].'_PARTY']['total_before_tax'] = $get_party_reservation[$p_party_room['party_reservation_id']]['total_before_tax'];
                            $_REQUEST['party'][$p_party_room['party_reservation_id'].'_PARTY']['total'] = $get_party_reservation[$p_party_room['party_reservation_id']]['total'];
                            $_REQUEST['party'][$p_party_room['party_reservation_id'].'_PARTY']['service_rate'] = $get_party_reservation[$p_party_room['party_reservation_id']]['extra_service_rate'];
                            $_REQUEST['party'][$p_party_room['party_reservation_id'].'_PARTY']['tax_rate'] = $get_party_reservation[$p_party_room['party_reservation_id']]['vat'];
                            $_REQUEST['party'][$p_party_room['party_reservation_id'].'_PARTY']['user_id'] = $get_party_reservation[$p_party_room['party_reservation_id']]['user_id'];
                            $_REQUEST['party'][$p_party_room['party_reservation_id'].'_PARTY']['time_in'] = date('H:i',$get_party_reservation[$p_party_room['party_reservation_id']]['checkin_time']);
                            $_REQUEST['party'][$p_party_room['party_reservation_id'].'_PARTY']['time_out'] = date('H:i',$get_party_reservation[$p_party_room['party_reservation_id']]['checkout_time']);
                            $_REQUEST['party'][$p_party_room['party_reservation_id'].'_PARTY']['portal_id'] = $get_party_reservation[$p_party_room['party_reservation_id']]['portal_id'];
                            $_REQUEST['party'][$p_party_room['party_reservation_id'].'_PARTY']['promotions'] = $get_party_reservation[$p_party_room['party_reservation_id']]['promotions'];
                            $_REQUEST['party'][$p_party_room['party_reservation_id'].'_PARTY']['in_date'] = date('d/m/Y',$get_party_reservation[$p_party_room['party_reservation_id']]['checkin_time']);
                        }
                        $_REQUEST['party'][$p_party_room['party_reservation_id'].'_PARTY']['child_product'][$p_party_id] = $p_party_room;
                        $_REQUEST['party'][$p_party_room['party_reservation_id'].'_PARTY']['child_product'][$p_party_id]['mice_party_id'] = '';
                    }
                    foreach($get_party_room as $r_party_id=>$r_party_room)
                    {
                        if(!isset($_REQUEST['party'][$r_party_room['party_reservation_id'].'_PARTY']))
                        {
                            $_REQUEST['party'][$r_party_room['party_reservation_id'].'_PARTY']['id'] = '';
                            $_REQUEST['party'][$r_party_room['party_reservation_id'].'_PARTY']['mice_reservation_id'] = '';
                            $_REQUEST['party'][$r_party_room['party_reservation_id'].'_PARTY']['party_reservation_id'] = $r_party_room['party_reservation_id'];
                            $_REQUEST['party'][$r_party_room['party_reservation_id'].'_PARTY']['party_type'] = $get_party_reservation[$r_party_room['party_reservation_id']]['party_type'];
                            $_REQUEST['party'][$r_party_room['party_reservation_id'].'_PARTY']['total_before_tax'] = $get_party_reservation[$r_party_room['party_reservation_id']]['total_before_tax'];
                            $_REQUEST['party'][$r_party_room['party_reservation_id'].'_PARTY']['total'] = $get_party_reservation[$r_party_room['party_reservation_id']]['total'];
                            $_REQUEST['party'][$r_party_room['party_reservation_id'].'_PARTY']['service_rate'] = $get_party_reservation[$r_party_room['party_reservation_id']]['extra_service_rate'];
                            $_REQUEST['party'][$r_party_room['party_reservation_id'].'_PARTY']['tax_rate'] = $get_party_reservation[$r_party_room['party_reservation_id']]['vat'];
                            $_REQUEST['party'][$r_party_room['party_reservation_id'].'_PARTY']['user_id'] = $get_party_reservation[$r_party_room['party_reservation_id']]['user_id'];
                            $_REQUEST['party'][$r_party_room['party_reservation_id'].'_PARTY']['time_in'] = date('H:i',$get_party_reservation[$r_party_room['party_reservation_id']]['checkin_time']);
                            $_REQUEST['party'][$r_party_room['party_reservation_id'].'_PARTY']['time_out'] = date('H:i',$get_party_reservation[$r_party_room['party_reservation_id']]['checkout_time']);
                            $_REQUEST['party'][$r_party_room['party_reservation_id'].'_PARTY']['portal_id'] = $get_party_reservation[$r_party_room['party_reservation_id']]['portal_id'];
                            $_REQUEST['party'][$r_party_room['party_reservation_id'].'_PARTY']['promotions'] = $get_party_reservation[$r_party_room['party_reservation_id']]['promotions'];
                            $_REQUEST['party'][$r_party_room['party_reservation_id'].'_PARTY']['in_date'] = date('d/m/Y',$get_party_reservation[$r_party_room['party_reservation_id']]['checkin_time']);
                        }
                        $_REQUEST['party'][$r_party_room['party_reservation_id'].'_PARTY']['child_room'][$r_party_id] = $r_party_room;
                        
                        $_REQUEST['party'][$r_party_room['party_reservation_id'].'_PARTY']['child_room'][$r_party_id]['time_type'] = $get_party_reservation[$r_party_room['party_reservation_id']]['time_type'];
                        $_REQUEST['party'][$r_party_room['party_reservation_id'].'_PARTY']['child_room'][$r_party_id]['mice_party_id'] = '';
                    }
                }
                //System::debug($_REQUEST['party']); exit();
                $active_department[$key]['icon'] = 'packages/hotel/packages/mice/skins/img/icon_party.png';
                $active_department[$key]['description'] = Portal::language('access_party_room');
            }
            
            /** ticket **/
            if($value['id']=='TICKET')
            {
                $cond_ticket = '';
                if(Url::get('id') AND DB::exists("SELECT id from ticket_reservation where mice_action_module=".Url::get('id')) AND !isset($_REQUEST['extra']))
                {
                    $cond_ticket = ' ticket_reservation.mice_action_module='.Url::get('id');
                }
                elseif(Url::get('from_department')==$value['id'] AND !isset($_REQUEST['ticket']))
                {
                    $cond_ticket = " ticket_reservation.id=".Url::get('key_department')."";
                }
                if(Url::get('id') AND !isset($_REQUEST['ticket']))
                {
                    $_REQUEST['ticket'] = array();
                    $mi_ticket_reservation = DB::select_all("mice_ticket_reservation","mice_reservation_id=".Url::get('id'));
                    
                    foreach($mi_ticket_reservation as $k=>$v)
                    {
                        $_REQUEST['ticket'][$k] = $v;
                        $_REQUEST['ticket'][$k]['date_used'] = date('d/m/Y',$v['time']);
                        $_REQUEST['ticket'][$k]['ticket_reservation_id'] = '';
                    }
                }
                if($cond_ticket!='')
                {
                    $get_ticket_reservation = DB::fetch_all("
                                                        SELECT
                                                            ticket_invoice.*,
                                                            TO_CHAR(ticket_invoice.date_used,'DD/MM/YYYY') as date_used,
                                                            ticket_reservation.note,
                                                            ticket_reservation.tax_rate,
                                                            ticket_reservation.service_rate,
                                                            ticket_reservation.id as ticket_reservation_id,
                                                            ticket_reservation.discount_rate as discount_rate_group
                                                        FROM
                                                           ticket_invoice
                                                           inner join ticket_reservation on ticket_reservation.id=ticket_invoice.ticket_reservation_id
                                                        WHERE
                                                           ".$cond_ticket." 
                                                        ");
                    foreach($get_ticket_reservation as $key_ticket=>$value_ticket)
                    {
                        $_REQUEST['ticket'][$key_ticket.'_TICKET']['id'] = '';
                        $_REQUEST['ticket'][$key_ticket.'_TICKET']['date_used'] = $value_ticket['date_used'];
                        $_REQUEST['ticket'][$key_ticket.'_TICKET']['note'] = $value_ticket['note'];
                        $_REQUEST['ticket'][$key_ticket.'_TICKET']['tax_rate'] = $value_ticket['tax_rate'];
                        $_REQUEST['ticket'][$key_ticket.'_TICKET']['service_rate'] = $value_ticket['service_rate'];
                        $_REQUEST['ticket'][$key_ticket.'_TICKET']['ticket_id'] = $value_ticket['ticket_id'];
                        $_REQUEST['ticket'][$key_ticket.'_TICKET']['ticket_area_id'] = $value_ticket['ticket_area_id'];
                        $_REQUEST['ticket'][$key_ticket.'_TICKET']['quantity'] = $value_ticket['quantity'];
                        $_REQUEST['ticket'][$key_ticket.'_TICKET']['price'] = $value_ticket['price'];
                        $_REQUEST['ticket'][$key_ticket.'_TICKET']['discount_quantity'] = $value_ticket['discount_quantity'];
                        $_REQUEST['ticket'][$key_ticket.'_TICKET']['discount_rate'] = System::calculate_number($value_ticket['discount_rate'])+System::calculate_number($value_ticket['discount_rate_group']);
                        $_REQUEST['ticket'][$key_ticket.'_TICKET']['ticket_reservation_id'] = $value_ticket['ticket_reservation_id'];
                    }
                }
                $active_department[$key]['icon'] = 'packages/hotel/packages/mice/skins/img/icon_sale.png';
                $active_department[$key]['description'] = Portal::language('ticket');
            }
            /** end ticket **/
            /** spa **/
            /**
            if($value['id']=='SPA')
            {
                if(Url::get('id') AND !isset($_REQUEST['spa']))
                {
                    $_REQUEST['spa'] = array();
                    $mi_spa = DB::select_all("mice_spa","mice_reservation_id=".Url::get('id'));
                    $mi_spa_product = DB::fetch_all("
                                                    SELECT
                                                        mice_spa_product.*,
                                                        product.id as product_id,
                                                        product.name_".Portal::language()." as product_name
                                                    FROM
                                                        mice_spa_product
                                                        inner join mice_spa on mice_spa.id=mice_spa_product.mice_spa_id
                                                        inner join product_price_list on product_price_list.id=mice_spa_product.price_id
                                                        inner join product on product.id=product_price_list.product_id
                                                    WHERE
                                                        mice_spa.mice_reservation_id=".Url::get('id')."
                                                    ");
                    $mi_spa_service = DB::fetch_all("
                                                    SELECT
                                                        mice_spa_service.*,
                                                        product.id as product_id,
                                                        product.name_".Portal::language()." as product_name,
                                                        spa_room.name as spa_room_name
                                                    FROM
                                                        mice_spa_service
                                                        inner join mice_spa on mice_spa.id=mice_spa_service.mice_spa_id
                                                        inner join product_price_list on product_price_list.id=mice_spa_service.price_id
                                                        inner join product on product.id=product_price_list.product_id
                                                        inner join spa_room on spa_room.id=mice_spa_service.spa_room_id
                                                    WHERE
                                                        mice_spa.mice_reservation_id=".Url::get('id')."
                                                    ");
                    foreach($mi_spa_product as $k=>$v)
                    {
                        if(!isset($_REQUEST['spa'][$v['mice_spa_id']]))
                        {
                            $_REQUEST['spa'][$v['mice_spa_id']] = $mi_spa[$v['mice_spa_id']];
                            $_REQUEST['spa'][$v['mice_spa_id']]['child_product'] = array();
                            $_REQUEST['spa'][$v['mice_spa_id']]['child_service'] = array();
                        }
                        $_REQUEST['spa'][$v['mice_spa_id']]['child_product'][$k] = $v;
                        $_REQUEST['spa'][$v['mice_spa_id']]['child_product'][$k]['in_date'] = date('d/m/Y',$v['time_in']);
                        $_REQUEST['spa'][$v['mice_spa_id']]['child_product'][$k]['time_in'] = date('H:i',$v['time_in']);
                        $_REQUEST['spa'][$v['mice_spa_id']]['child_product'][$k]['time_out']= date('H:i',$v['time_out']);
                    }
                    foreach($mi_spa_service as $k=>$v)
                    {
                        if(!isset($_REQUEST['spa'][$v['mice_spa_id']]))
                        {
                            $_REQUEST['spa'][$v['mice_spa_id']] = $mi_spa[$v['mice_spa_id']];
                            $_REQUEST['spa'][$v['mice_spa_id']]['child_product'] = array();
                            $_REQUEST['spa'][$v['mice_spa_id']]['child_service'] = array();
                        }
                        $_REQUEST['spa'][$v['mice_spa_id']]['child_service'][$k] = $v;
                        $_REQUEST['spa'][$v['mice_spa_id']]['child_service'][$k]['in_date'] = date('d/m/Y',$v['time_in']);
                        $_REQUEST['spa'][$v['mice_spa_id']]['child_service'][$k]['time_in'] = date('H:i',$v['time_in']);
                        $_REQUEST['spa'][$v['mice_spa_id']]['child_service'][$k]['time_out']= date('H:i',$v['time_out']);
                    }
                }
                $active_department[$key]['icon'] = 'packages/hotel/packages/mice/skins/img/icon_spa.png';
                $active_department[$key]['description'] = Portal::language('access_spa');
            }
            **/
        }
        $this->map['items']  = $active_department;
        //$this->map['remain'] = $this->map['total_amount']-($this->map['payment']);
        $this->map['deposit']       = System::display_number($this->map['deposit']);
        $this->map['payment']       = System::display_number($this->map['payment']);
        $_REQUEST += $this->map;
        $this->parse_layout('edit',$this->map);
    }
    
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
    function id_like_array($id,$array)
    {
        if(sizeof($array)>0)
        {
            $check  = false;
            foreach($array as $key=>$value)
            {
                if($id==$key)
                {
                    $check = true;
                }
            }
            return $check;
        }
        else
        {
            return false;
        }
    }
    function get_banquet_room()
    {
		$sql = '
			select 
				party_room.id,
				party_room.name,
				party_room.group_name,
				party_room.price,
                party_room.address,
				party_room.price_half_day
			from 
				party_room
            where
                portal_id = \''.PORTAL_ID.'\'
			order by
				party_room.id
		';
		$banquet_rooms = DB::fetch_all($sql);
		return $banquet_rooms;
	}
}
?>
