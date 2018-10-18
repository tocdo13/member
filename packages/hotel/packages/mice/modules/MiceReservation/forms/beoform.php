<?php
class BeoFormMiceReservationForm extends Form
{
	function BeoFormMiceReservationForm()
    {
		Form::Form('BeoFormMiceReservationForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_css('packages/hotel/packages/mice/skins/css/font-awesome-4.5.0/css/font-awesome.min.css');
        $this->link_css('packages/core/includes/js/custom_content_scroller/jquery.mCustomScrollbar.css');
        $this->link_js('packages/core/includes/js/custom_content_scroller/jquery.mCustomScrollbar.js');
        $this->link_js('packages/hotel/packages/mice/skins/smart-zoom.js');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
	}
    function on_submit()
    {
        //System::debug($_REQUEST); die;
        if(Url::get('act')=='save')
        {
            $log_description = '';
            $array = array(
                            'contact_name'=>Url::get('contact_name'),
                            'contact_phone'=>Url::get('contact_phone'),
                            'sales'=>Url::get('sales'),
                            'event_name'=>Url::get('event_name'),
                            'confirmed_number_of_guest'=>Url::get('confirmed_number_of_guest'),
                            'expect_number_of_guest'=>Url::get('expect_number_of_guest'),
                            'event_date'=>Url::get('event_date'),
                            'time'=>Url::get('time'),
                            'deposit_note'=>Url::get('deposit_note'),
                            'payment_method_note'=>Url::get('payment_method_note'),
                            'note'=>Url::get('note')
                            );
            DB::update('mice_reservation',$array,'id='.Url::get('id'));
            $log_description .= '
                                <h3>'.Portal::language('update').' BEO in MICE '.Url::get('id').'<h3><hr/>
                                <b>'.Portal::language('contact_name').': </b> '.Url::get('contact_name').'<br/>
                                <b>'.Portal::language('contact_phone').': </b> '.Url::get('contact_phone').'<br/>
                                <b>'.Portal::language('sales').': </b> '.Url::get('sales').'<br/>
                                <b>'.Portal::language('event_name').': </b> '.Url::get('event_name').'<br/>
                                <b>'.Portal::language('confirmed_number_of_guest').': </b> '.Url::get('confirmed_number_of_guest').'<br/>
                                <b>'.Portal::language('expect_number_of_guest').': </b> '.Url::get('expect_number_of_guest').'<br/>
                                <b>'.Portal::language('event_date').': </b> '.Url::get('event_date').'<br/>
                                <b>'.Portal::language('time').': </b> '.Url::get('time').'<br/>
                                <b>'.Portal::language('deposit_note').': </b> '.Url::get('deposit_note').'<br/>
                                <b>'.Portal::language('payment_method_note').': </b> '.Url::get('payment_method_note').'<br/>
                                <b>'.Portal::language('note').': </b> '.Url::get('note').'<br/>
                                ';
            $list_setup_id = '';
            if(isset($_REQUEST['setup']))
            {
                $log_description .= '<h3>'.Portal::language('detail').' SETUP</h3>';
                foreach($_REQUEST['setup'] as $key=>$value)
                {
                    $setup = array(
                                    'input_count'=>$value['input_count'],
                                    'mice_reservation_id'=>Url::get('id'),
                                    'mice_location_id'=>isset($value['mice_location_id'])?$value['mice_location_id']:'',
                                    'mice_department_id'=>isset($value['mice_department_id'])?$value['mice_department_id']:'',
                                    'last_editer'=>User::id(),
                                    'last_edit_time'=>time(),
                                    'description'=>$value['description'],
                                    'time'=>Date_Time::to_time($value['in_date'])+$this->calc_time($value['hour'])
                                    );
                    if($value['id']!='')
                    {
                        $setup_id = $value['id'];
                        DB::update('mice_setup_beo',$setup,'id='.$value['id']);
                        $log_description .= '<b>+'.Portal::language('update').' SETUP</b> <br/>';
                    }
                    else
                    {
                        $setup_id = DB::insert('mice_setup_beo',$setup);
                        $log_description .= '<b>+'.Portal::language('add').' SETUP</b> <br/>';
                    }
                    $log_description.= '-'.Portal::language('location').':'.((isset($value['mice_location_id']) and $value['mice_location_id']!='')?DB::fetch('select name from mice_location_setup where id='.$value['mice_location_id'],'name'):'').'<br/>
                                        -'.Portal::language('department').':'.((isset($value['mice_department_id']) and $value['mice_department_id']!='')?DB::fetch('select name from mice_department_setup where id='.$value['mice_department_id'],'name'):'').'<br/>
                                        -'.Portal::language('time').':'.$value['hour'].' '.$value['in_date'].'<br/>
                                        -'.Portal::language('description').':'.$value['description'].'<br/>
                                        ';
                    if($list_setup_id=='')
                        $list_setup_id = $setup_id;
                    else
                        $list_setup_id .= ','.$setup_id;
                }
            }
            if($list_setup_id!='')
                DB::delete('mice_setup_beo','id not in ('.$list_setup_id.') AND mice_reservation_id='.Url::get('id'));
            else
                DB::delete('mice_setup_beo','mice_reservation_id='.Url::get('id'));
            
            System::log('Edit','Update Setup Beo in Mice'.Url::get('id'),$log_description,Url::get('id'));
        }
        Url::redirect('mice_reservation',array('cmd'=>Url::get('cmd'),'id'=>Url::get('id')));
    }
	function draw()
    {
        $this->map = array();
        $this->map['list_beo'] = array();
        $stt = 0;
        $this->map['log_beo_stt'] = '';
        $this->map['log_beo_time'] = '';
        $mice = DB::fetch('
                        SELECT
                            mice_reservation.*
                            ,customer.name as customer_name
                            ,traveller.first_name || \' \' || traveller.last_name as traveller_name
                        FROM
                            mice_reservation
                            left join customer on customer.id=mice_reservation.customer_id
                            left join traveller on traveller.id=mice_reservation.traveller_id
                        WHERE
                            mice_reservation.id='.Url::get('id').'
                        ');
        $this->map += $mice;
        $this->map['mice_total'] = 0;
        $this->map['mice_service'] = 0;
        $this->map['mice_tax'] = 0;
        $this->map['mice_grand_total'] = 0;
        
        // lay tien phi viet chenh trong hoa don
        //$this->map['extra_vat'] = DB::fetch('select sum(extra_vat) as extra from mice_invoice where mice_invoice.mice_reservation_id='.Url::get('id').'','extra');
        //$this->map['mice_grand_total'] += $this->map['extra_vat'];
        
        // end phi viet chenh
        $this->map['portal_id'] = isset($this->map['portal_id'])?$this->map['portal_id']:PORTAL_ID;
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
                                        ');
        $active_department['EXS'] = array('id'=>'EXS','name'=>Portal::language('extra_service'));
        $this->map['items'] = array();
        $this->map['venues'] = array();
        $key_items = 0;
        $key_venues = 0;
        foreach($active_department as $key=>$value)
        {
            if($value['id']=='REC')
            {
                if(!isset($this->map['items'][$value['id']]))
                {
                    $this->map['items'][$value['id']]['id'] = $value['id'];
                    $this->map['items'][$value['id']]['name'] = $value['name'];
                    $this->map['items'][$value['id']]['child'] = array();
                }
                
                if($mice['status']==1) // MICE da xac nhan thi lay du lieu tu cac module
                {
                    $cond_rec = ' reservation.mice_reservation_id='.Url::get('id');
                    $get_reservation = DB::fetch_all("SELECT
                                                            reservation_room.*,
                                                            room_level.name as room_level_name
                                                        FROM
                                                            reservation_room
                                                            inner join reservation on reservation.id=reservation_room.reservation_id
                                                            inner join room_level on room_level.id=reservation_room.room_level_id
                                                        WHERE
                                                            ".$cond_rec."
                                                            AND reservation_room.status!='CANCEL'
                                                     ");
                    
                    foreach($get_reservation as $key_rec=>$value_rec)
                    {
                        $folio = MiceReservationDB::get_total_room($value_rec['id']);
                        $price = 0;
                        foreach($folio as $f_id=>$f_value)
                        {
                            $this->map['mice_total'] += $f_value['amount'];
                            $price += $f_value['amount'];
                            $service_total = $f_value['amount']*$f_value['service_rate']/100;
                            $this->map['mice_service'] += $service_total;
                            $tax_total = ($f_value['amount']+$service_total)*$f_value['tax_rate']/100;
                            $this->map['mice_tax'] += $tax_total;
                            $this->map['mice_grand_total'] += $f_value['amount']+$service_total+$tax_total;
                        }
                        $key_venues++;
                        $this->map['venues'][$value_rec['room_level_name'].$value_rec['time_in'].$value_rec['time_out']]['id'] = $key_venues;
                        $this->map['venues'][$value_rec['room_level_name'].$value_rec['time_in'].$value_rec['time_out']]['name'] = $value_rec['room_level_name'];
                        $this->map['venues'][$value_rec['room_level_name'].$value_rec['time_in'].$value_rec['time_out']]['service'] = Portal::language('home_stays');
                        $this->map['venues'][$value_rec['room_level_name'].$value_rec['time_in'].$value_rec['time_out']]['time'] = date('H:i d/m/Y',$value_rec['time_in']).' - '.date('H:i d/m/Y',$value_rec['time_out']);
                        
                        if(!isset($this->map['items'][$value['id']]['child'][$value_rec['room_level_name'].$value_rec['time_in'].$price]))
                        {
                            $key_items++;
                            $this->map['items'][$value['id']]['child'][$value_rec['room_level_name'].$value_rec['time_in'].$price]['id'] = $key_items;
                            $this->map['items'][$value['id']]['child'][$value_rec['room_level_name'].$value_rec['time_in'].$price]['stt'] = $key_items;
                            $this->map['items'][$value['id']]['child'][$value_rec['room_level_name'].$value_rec['time_in'].$price]['name'] = $value_rec['room_level_name'].' '.date('d/m/Y',$value_rec['time_in']);
                            $this->map['items'][$value['id']]['child'][$value_rec['room_level_name'].$value_rec['time_in'].$price]['quantity'] = 1;
                            $this->map['items'][$value['id']]['child'][$value_rec['room_level_name'].$value_rec['time_in'].$price]['unit'] = Portal::language('apartment');
                            $this->map['items'][$value['id']]['child'][$value_rec['room_level_name'].$value_rec['time_in'].$price]['price'] = $price;
                            $this->map['items'][$value['id']]['child'][$value_rec['room_level_name'].$value_rec['time_in'].$price]['discount'] = 0;
                            $this->map['items'][$value['id']]['child'][$value_rec['room_level_name'].$value_rec['time_in'].$price]['amount'] = $price;
                            $this->map['items'][$value['id']]['child'][$value_rec['room_level_name'].$value_rec['time_in'].$price]['total'] = $price;
                        }
                        else
                        {
                            $this->map['items'][$value['id']]['child'][$value_rec['room_level_name'].$value_rec['time_in'].$price]['quantity'] += 1;
                            $this->map['items'][$value['id']]['child'][$value_rec['room_level_name'].$value_rec['time_in'].$price]['amount'] += $price;
                            $this->map['items'][$value['id']]['child'][$value_rec['room_level_name'].$value_rec['time_in'].$price]['total'] += $price;
                        }
                        
                    }
                }
                else
                {
                    $mi_booking = DB::fetch_all("
                                                SELECT
                                                    mice_booking.*,
                                                    room_level.name as room_level_name
                                                FROM
                                                    mice_booking
                                                    inner join room_level on room_level.id=mice_booking.room_level_id
                                                WHERE
                                                    mice_booking.mice_reservation_id=".Url::get('id')."
                                                ");
                    
                    foreach($mi_booking as $k=>$v)
                    {
                        $count_day = $this->count_day(date('d/m/Y',$v['time_in']),date('d/m/Y',$v['time_out']));
                        if(date('d/m/Y',$v['time_in'])==date('d/m/Y',$v['time_out']))
                        {
                            $count_day = 1;
                        }
                        if($v['net_price']==1)
                        {
                            $v['price'] = $v['price'] / ( (1+$v['service_rate']/100)*(1+$v['tax_rate']/100) );
                        }
                        
                        $v['price'] = $v['price'] * $count_day;
                        
                        $this->map['mice_total'] += $v['price']*$v['quantity'];
                        $service_total = ($v['price']*$v['quantity'])*($v['service_rate']/100);
                        $this->map['mice_service'] += $service_total;
                        $tax_total = (($v['price']*$v['quantity'])+$service_total) *($v['tax_rate']/100);
                        $this->map['mice_tax'] += $tax_total;
                        $this->map['mice_grand_total'] += ($v['price']*$v['quantity'])+$service_total+$tax_total;
                        
                        $key_venues++;
                        $this->map['venues'][$v['room_level_name'].$v['time_out']]['id'] = $key_venues;
                        $this->map['venues'][$v['room_level_name'].$v['time_out']]['name'] = $v['room_level_name'];
                        $this->map['venues'][$v['room_level_name'].$v['time_out']]['service'] = $v['service_name']==''?Portal::language('home_stays'):$v['service_name'];
                        $this->map['venues'][$v['room_level_name'].$v['time_out']]['time'] = date('H:i d/m/Y',$v['time_in']).' - '.date('H:i d/m/Y',$v['time_out']);
                        
                        if(!isset($this->map['items'][$value['id']]['child'][$v['room_level_name'].$v['time_in'].$v['time_out'].$v['price']]))
                        {
                            $key_items++;
                            $this->map['items'][$value['id']]['child'][$v['room_level_name'].$v['time_in'].$v['time_out'].$v['price']]['id'] = $key_items;
                            $this->map['items'][$value['id']]['child'][$v['room_level_name'].$v['time_in'].$v['time_out'].$v['price']]['stt'] = $key_items;
                            $this->map['items'][$value['id']]['child'][$v['room_level_name'].$v['time_in'].$v['time_out'].$v['price']]['name'] = $v['room_level_name'].' '.date('H:i d/m/Y',$v['time_in']).' - '.date('H:i d/m/Y',$v['time_out']);
                            $this->map['items'][$value['id']]['child'][$v['room_level_name'].$v['time_in'].$v['time_out'].$v['price']]['quantity'] = $v['quantity'];
                            $this->map['items'][$value['id']]['child'][$v['room_level_name'].$v['time_in'].$v['time_out'].$v['price']]['unit'] = Portal::language('apartment');
                            $this->map['items'][$value['id']]['child'][$v['room_level_name'].$v['time_in'].$v['time_out'].$v['price']]['price'] = $v['price'];
                            $this->map['items'][$value['id']]['child'][$v['room_level_name'].$v['time_in'].$v['time_out'].$v['price']]['discount'] = 0;
                            $this->map['items'][$value['id']]['child'][$v['room_level_name'].$v['time_in'].$v['time_out'].$v['price']]['amount'] = $v['price'];
                            $this->map['items'][$value['id']]['child'][$v['room_level_name'].$v['time_in'].$v['time_out'].$v['price']]['total'] = $v['price']*$v['quantity'];
                        }
                        else
                        {
                            $this->map['items'][$value['id']]['child'][$v['room_level_name'].$v['time_in'].$v['time_out'].$v['price']]['quantity'] += $v['quantity'];
                            $this->map['items'][$value['id']]['child'][$v['room_level_name'].$v['time_in'].$v['time_out'].$v['price']]['amount'] += $v['price'];
                            $this->map['items'][$value['id']]['child'][$v['room_level_name'].$v['time_in'].$v['time_out'].$v['price']]['total'] += $v['price']*$v['quantity'];
                        }
                    }
                    /** lay tu cac module **/
                    $cond_rec = ' reservation.mice_action_module='.Url::get('id');
                    $get_reservation = DB::fetch_all("SELECT
                                                            reservation_room.*,
                                                            room_level.name as room_level_name
                                                        FROM
                                                            reservation_room
                                                            inner join reservation on reservation.id=reservation_room.reservation_id
                                                            inner join room_level on room_level.id=reservation_room.room_level_id
                                                        WHERE
                                                            ".$cond_rec."
                                                            AND reservation_room.status!='CANCEL'
                                                     ");
                    
                    foreach($get_reservation as $key_rec=>$value_rec)
                    {
                        $folio = MiceReservationDB::get_total_room($value_rec['id']);
                        $price = 0;
                        foreach($folio as $f_id=>$f_value)
                        {
                            $this->map['mice_total'] += $f_value['amount'];
                            $price += $f_value['amount'];
                            $service_total = $f_value['amount']*$f_value['service_rate']/100;
                            $this->map['mice_service'] += $service_total;
                            $tax_total = ($f_value['amount']+$service_total)*$f_value['tax_rate']/100;
                            $this->map['mice_tax'] += $tax_total;
                            $this->map['mice_grand_total'] += $f_value['amount']+$service_total+$tax_total;
                        }
                        $key_venues++;
                        $this->map['venues'][$value_rec['room_level_name'].$value_rec['time_in'].$value_rec['time_out']]['id'] = $key_venues;
                        $this->map['venues'][$value_rec['room_level_name'].$value_rec['time_in'].$value_rec['time_out']]['name'] = $value_rec['room_level_name'];
                        $this->map['venues'][$value_rec['room_level_name'].$value_rec['time_in'].$value_rec['time_out']]['service'] = Portal::language('home_stays');
                        $this->map['venues'][$value_rec['room_level_name'].$value_rec['time_in'].$value_rec['time_out']]['time'] = date('H:i d/m/Y',$value_rec['time_in']).' - '.date('H:i d/m/Y',$value_rec['time_out']);
                        
                        if(!isset($this->map['items'][$value['id']]['child'][$value_rec['room_level_name'].$value_rec['time_in'].$price]))
                        {
                            $key_items++;
                            $this->map['items'][$value['id']]['child'][$value_rec['room_level_name'].$value_rec['time_in'].$price]['id'] = $key_items;
                            $this->map['items'][$value['id']]['child'][$value_rec['room_level_name'].$value_rec['time_in'].$price]['stt'] = $key_items;
                            $this->map['items'][$value['id']]['child'][$value_rec['room_level_name'].$value_rec['time_in'].$price]['name'] = $value_rec['room_level_name'].' '.date('d/m/Y',$value_rec['time_in']);
                            $this->map['items'][$value['id']]['child'][$value_rec['room_level_name'].$value_rec['time_in'].$price]['quantity'] = 1;
                            $this->map['items'][$value['id']]['child'][$value_rec['room_level_name'].$value_rec['time_in'].$price]['unit'] = Portal::language('apartment');
                            $this->map['items'][$value['id']]['child'][$value_rec['room_level_name'].$value_rec['time_in'].$price]['price'] = $price;
                            $this->map['items'][$value['id']]['child'][$value_rec['room_level_name'].$value_rec['time_in'].$price]['discount'] = 0;
                            $this->map['items'][$value['id']]['child'][$value_rec['room_level_name'].$value_rec['time_in'].$price]['amount'] = $price;
                            $this->map['items'][$value['id']]['child'][$value_rec['room_level_name'].$value_rec['time_in'].$price]['total'] = $price;
                        }
                        else
                        {
                            $this->map['items'][$value['id']]['child'][$value_rec['room_level_name'].$value_rec['time_in'].$price]['quantity'] += 1;
                            $this->map['items'][$value['id']]['child'][$value_rec['room_level_name'].$value_rec['time_in'].$price]['amount'] += $price;
                            $this->map['items'][$value['id']]['child'][$value_rec['room_level_name'].$value_rec['time_in'].$price]['total'] += $price;
                            
                        }
                        
                    }
                }
            }
            
            /** ticket **/
            if($value['id']=='TICKET')
            {
                if(!isset($this->map['items'][$value['id']]))
                {
                    $this->map['items'][$value['id']]['id'] = $value['id'];
                    $this->map['items'][$value['id']]['name'] = $value['name'];
                    $this->map['items'][$value['id']]['child'] = array();
                }
                if($mice['status']==1) // MICE da xac nhan thi lay du lieu tu cac module
                {
                    /** lay tu cac module **/
                    $cond_ticket = ' ticket_reservation.mice_reservation_id='.Url::get('id');
                    $get_ticket_reservation = DB::fetch_all(" SELECT
                                                                ticket_invoice.*
                                                                ,TO_CHAR(ticket_invoice.date_used,'DD/MM/YYYY') as in_date
                                                                ,ticket_reservation.discount_rate as discount_rate_group
                                                                ,ticket_reservation.id as ticket_reservation_id
                                                                ,ticket_reservation.mice_reservation_id
                                                                ,ticket_reservation.mice_action_module
                                                                ,ticket.name as ticket_name
                                                            FROM 
                                                                ticket_invoice 
                                                                inner join ticket_reservation on ticket_reservation.id=ticket_invoice.ticket_reservation_id
                                                                inner join ticket on ticket.id=ticket_invoice.ticket_id
                                                            WHERE 
                                                                ".$cond_ticket."");
                    
                    foreach($get_ticket_reservation as $key_ticket=>$value_ticket)
                    {
                        $key_items++;
                        $this->map['items'][$value['id']]['child'][$key_items]['id'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['stt'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['name'] = $value_ticket['ticket_name'].' '.$value_ticket['in_date'];
                        $this->map['items'][$value['id']]['child'][$key_items]['quantity'] = $value_ticket['quantity'];
                        $this->map['items'][$value['id']]['child'][$key_items]['unit'] = Portal::language('apartment');
                        $this->map['items'][$value['id']]['child'][$key_items]['price'] = $value_ticket['price'];
                        $this->map['items'][$value['id']]['child'][$key_items]['discount'] = $value_ticket['discount_rate'] + $value_ticket['discount_rate_group'];
                        $this->map['items'][$value['id']]['child'][$key_items]['amount'] = $value_ticket['price'];
                        $this->map['items'][$value['id']]['child'][$key_items]['total'] = ($value_ticket['price'] - ($value_ticket['price']*(($value_ticket['discount_rate']+$value_ticket['discount_rate_group'])/100)))*($value_ticket['quantity']-$value_ticket['discount_quantity']);
                        $this->map['mice_total'] += ($value_ticket['price'] - ($value_ticket['price']*(($value_ticket['discount_rate']+$value_ticket['discount_rate_group'])/100)))*($value_ticket['quantity']-$value_ticket['discount_quantity']);
                        $this->map['mice_grand_total'] += ($value_ticket['price'] - ($value_ticket['price']*(($value_ticket['discount_rate']+$value_ticket['discount_rate_group'])/100)))*($value_ticket['quantity']-$value_ticket['discount_quantity']);
                    }
                }
                else
                {
                    $mi_ticket = DB::fetch_all("
                                                SELECT
                                                    mice_ticket_reservation.*,
                                                    ticket.name as ticket_name,
                                                    ticket.name as service_name
                                                FROM
                                                    mice_ticket_reservation
                                                    inner join ticket on ticket.id=mice_ticket_reservation.ticket_id
                                                WHERE
                                                    mice_ticket_reservation.mice_reservation_id=".Url::get('id')."
                                                ");
                    
                    foreach($mi_ticket as $k=>$v)
                    {
                        $key_items++;
                        $this->map['items'][$value['id']]['child'][$key_items]['id'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['stt'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['name'] = $v['ticket_name'].' '.date('H:i d/m/Y',$v['time']);
                        $this->map['items'][$value['id']]['child'][$key_items]['quantity'] = $v['quantity'];
                        $this->map['items'][$value['id']]['child'][$key_items]['unit'] = Portal::language('apartment');
                        $this->map['items'][$value['id']]['child'][$key_items]['price'] = $v['price'];
                        $this->map['items'][$value['id']]['child'][$key_items]['discount'] = $v['discount_rate'];
                        $this->map['items'][$value['id']]['child'][$key_items]['amount'] = $v['price'];
                        $this->map['items'][$value['id']]['child'][$key_items]['total'] = ($v['price'] - ($v['price']*($v['discount_rate']/100)))*($v['quantity']-$v['discount_quantity']);
                        $this->map['mice_total'] += ($v['price'] - ($v['price']*($v['discount_rate']/100)))*($v['quantity']-$v['discount_quantity']);
                        $this->map['mice_grand_total'] += ($v['price'] - ($v['price']*($v['discount_rate']/100)))*($v['quantity']-$v['discount_quantity']);
                    }
                    /** lay tu cac module **/
                    $cond_ticket = ' ticket_reservation.mice_action_module='.Url::get('id');
                    $get_ticket_reservation = DB::fetch_all(" SELECT
                                                                ticket_invoice.*
                                                                ,TO_CHAR(ticket_invoice.date_used,'DD/MM/YYYY') as in_date
                                                                ,ticket_reservation.discount_rate as discount_rate_group
                                                                ,ticket_reservation.id as ticket_reservation_id
                                                                ,ticket_reservation.mice_reservation_id
                                                                ,ticket_reservation.mice_action_module
                                                                ,ticket.name as ticket_name
                                                            FROM 
                                                                ticket_invoice 
                                                                inner join ticket_reservation on ticket_reservation.id=ticket_invoice.ticket_reservation_id
                                                                inner join ticket on ticket.id=ticket_invoice.ticket_id
                                                            WHERE 
                                                                ".$cond_ticket."");
                    
                    foreach($get_ticket_reservation as $key_ticket=>$value_ticket)
                    {
                        $key_items++;
                        $this->map['items'][$value['id']]['child'][$key_items]['id'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['stt'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['name'] = $value_ticket['ticket_name'].' '.$value_ticket['in_date'];
                        $this->map['items'][$value['id']]['child'][$key_items]['quantity'] = $value_ticket['quantity'];
                        $this->map['items'][$value['id']]['child'][$key_items]['unit'] = Portal::language('apartment');
                        $this->map['items'][$value['id']]['child'][$key_items]['price'] = $value_ticket['price'];
                        $this->map['items'][$value['id']]['child'][$key_items]['discount'] = $value_ticket['discount_rate'] + $value_ticket['discount_rate_group'];
                        $this->map['items'][$value['id']]['child'][$key_items]['amount'] = $value_ticket['price'];
                        $this->map['items'][$value['id']]['child'][$key_items]['total'] = ($value_ticket['price'] - ($value_ticket['price']*(($value_ticket['discount_rate']+$value_ticket['discount_rate_group'])/100)))*($value_ticket['quantity']-$value_ticket['discount_quantity']);
                        $this->map['mice_total'] += ($value_ticket['price'] - ($value_ticket['price']*(($value_ticket['discount_rate']+$value_ticket['discount_rate_group'])/100)))*($value_ticket['quantity']-$value_ticket['discount_quantity']);
                        $this->map['mice_grand_total'] += ($value_ticket['price'] - ($value_ticket['price']*(($value_ticket['discount_rate']+$value_ticket['discount_rate_group'])/100)))*($value_ticket['quantity']-$value_ticket['discount_quantity']);
                    }
                }
            }
            
            /** extra service invoice **/
            if($value['id']=='EXS')
            {
                if(!isset($this->map['items'][$value['id']]))
                {
                    $this->map['items'][$value['id']]['id'] = $value['id'];
                    $this->map['items'][$value['id']]['name'] = $value['name'];
                    $this->map['items'][$value['id']]['child'] = array();
                }
                if($mice['status']==1) // MICE da xac nhan thi lay du lieu tu cac module
                {
                    $cond_exs = ' extra_service_invoice.mice_reservation_id='.Url::get('id');
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
                        if($value_exs['net_price']==1)
                        {
                            $value_exs['price'] = $value_exs['price'] / ( (1+$value_exs['service_rate']/100)*(1+$value_exs['tax_rate']/100) );
                        }
                        $amount = $value_exs['price'];
                        $quantity = $value_exs['quantity'];
                        $amount = $amount * $quantity;
                        $amount = $amount - ($amount*($value_exs['percentage_discount']/100));
                        $amount = $amount - $value_exs['amount_discount'];
                        $this->map['mice_total'] += $amount;
                        $service_total = $amount*($value_exs['service_rate']/100);
                        $this->map['mice_service'] += $service_total;
                        $tax_total = ($amount+$service_total) *($value_exs['tax_rate']/100);
                        $this->map['mice_tax'] += $tax_total;
                        $this->map['mice_grand_total'] += $amount+$service_total+$tax_total;
                        
                        $key_items++;
                        $this->map['items'][$value['id']]['child'][$key_items]['id'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['stt'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['name'] = $value_exs['name'].' '.date('d/m/Y',$value_exs['time']);
                        $this->map['items'][$value['id']]['child'][$key_items]['quantity'] = $value_exs['quantity'];
                        $this->map['items'][$value['id']]['child'][$key_items]['unit'] = Portal::language('service');
                        $this->map['items'][$value['id']]['child'][$key_items]['price'] = $value_exs['price'];
                        $this->map['items'][$value['id']]['child'][$key_items]['discount'] = 0;
                        $this->map['items'][$value['id']]['child'][$key_items]['amount'] = $value_exs['price'];
                        $this->map['items'][$value['id']]['child'][$key_items]['total'] = $amount;
                        
                    }
                }
                else
                {
                    if(Url::get('id') AND !isset($_REQUEST['extra']))
                    {
                        $_REQUEST['extra'] = array();
                        $mi_extra_service = DB::select_all("mice_extra_service","mice_reservation_id=".Url::get('id'));
                        foreach($mi_extra_service as $k=>$v)
                        {
                            if($v['net_price']==1)
                            {
                                $v['price'] = $v['price'] / ( (1+$v['service_rate']/100)*(1+$v['tax_rate']/100) );
                            }
                            $this->map['mice_total'] += $v['total_before_tax'];
                            $service_total = ($v['total_before_tax'])*($v['service_rate']/100);
                            $this->map['mice_service'] += $service_total;
                            $tax_total = (($v['total_before_tax'])+$service_total) *($v['tax_rate']/100);
                            $this->map['mice_tax'] += $tax_total;
                            $this->map['mice_grand_total'] += ($v['total_before_tax'])+$service_total+$tax_total;
                            
                            $key_items++;
                            $this->map['items'][$value['id']]['child'][$key_items]['id'] = $key_items;
                            $this->map['items'][$value['id']]['child'][$key_items]['stt'] = $key_items;
                            $this->map['items'][$value['id']]['child'][$key_items]['name'] = $v['name'].' '.date('d/m/Y',$v['time']);
                            $this->map['items'][$value['id']]['child'][$key_items]['quantity'] = $v['quantity'];
                            $this->map['items'][$value['id']]['child'][$key_items]['unit'] = Portal::language('service');
                            $this->map['items'][$value['id']]['child'][$key_items]['price'] = $v['price'];
                            $this->map['items'][$value['id']]['child'][$key_items]['discount'] = 0;
                            $this->map['items'][$value['id']]['child'][$key_items]['amount'] = $v['price'];
                            $this->map['items'][$value['id']]['child'][$key_items]['total'] = $v['total_before_tax'];
                        }
                    }
                    $cond_exs = ' extra_service_invoice.mice_action_module='.Url::get('id');
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
                        if($value_exs['net_price']==1)
                        {
                            $value_exs['price'] = $value_exs['price'] / ( (1+$value_exs['service_rate']/100)*(1+$value_exs['tax_rate']/100) );
                        }
                        $amount = $value_exs['price'];
                        $quantity = $value_exs['quantity'];
                        $amount = $amount * $quantity;
                        $amount = $amount - ($amount*($value_exs['percentage_discount']/100));
                        $amount = $amount - $value_exs['amount_discount'];
                        $this->map['mice_total'] += $amount;
                        $service_total = $amount*($value_exs['service_rate']/100);
                        $this->map['mice_service'] += $service_total;
                        $tax_total = ($amount+$service_total) *($value_exs['tax_rate']/100);
                        $this->map['mice_tax'] += $tax_total;
                        $this->map['mice_grand_total'] += $amount+$service_total+$tax_total;
                        
                        $key_items++;
                        $this->map['items'][$value['id']]['child'][$key_items]['id'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['stt'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['name'] = $value_exs['name'].' '.date('d/m/Y',$value_exs['time']);
                        $this->map['items'][$value['id']]['child'][$key_items]['quantity'] = $value_exs['quantity'];
                        $this->map['items'][$value['id']]['child'][$key_items]['unit'] = Portal::language('service');
                        $this->map['items'][$value['id']]['child'][$key_items]['price'] = $value_exs['price'];
                        $this->map['items'][$value['id']]['child'][$key_items]['discount'] = 0;
                        $this->map['items'][$value['id']]['child'][$key_items]['amount'] = $value_exs['price'];
                        $this->map['items'][$value['id']]['child'][$key_items]['total'] = $amount;
                        
                    }
                }
            }
            
            /** restaurant  **/
            if($value['id']=='RES')
            {
                if(!isset($this->map['items'][$value['id']]))
                {
                    $this->map['items'][$value['id']]['id'] = $value['id'];
                    $this->map['items'][$value['id']]['name'] = $value['name'];
                    $this->map['items'][$value['id']]['child'] = array();
                }
                if($mice['status']==1) // MICE da xac nhan thi lay du lieu tu cac module
                {
                    $cond_res = ' bar_reservation.mice_reservation_id='.Url::get('id').' AND bar_reservation.status!=\'CANCEL\'';
                    $get_bar_reservation = DB::fetch_all("SELECT 
                                                                bar_reservation.*
                                                                --,bar.name as bar_name
                                                                ,bar_area.name as bar_name
                                                            FROM 
                                                                bar_reservation 
                                                                inner join bar on bar.id=bar_reservation.bar_id 
                                                                inner join bar_reservation_table on bar_reservation.id=bar_reservation_table.bar_reservation_id
                                                                inner join bar_table on bar_reservation_table.table_id=bar_table.id
                                                                inner join bar_area on bar_area.id=bar_table.bar_area_id
                                                            WHERE 
                                                                ".$cond_res);
                    foreach($get_bar_reservation as $bar_k=>$bar_v)
                    {
                        $key_venues++;
                        $this->map['venues'][$key_venues]['id'] = $key_venues;
                        $this->map['venues'][$key_venues]['name'] = $bar_v['bar_name'];
                        $this->map['venues'][$key_venues]['service'] = Portal::language('culinary');
                        $this->map['venues'][$key_venues]['time'] = date('H:i d/m/Y',$bar_v['arrival_time']).' - '.date('H:i d/m/Y',$bar_v['departure_time']);
                        //$this->map['extra_vat'] += $bar_v['extra_vat'];
                        //$this->map['mice_grand_total'] += $bar_v['extra_vat'];
                    }
                    $get_bar_reservation_product = DB::fetch_all("
                                                            SELECT
                                                                bar_reservation_product.*,
                                                                unit.name_".Portal::language()." as unit_name,
                                                                bar_reservation.bar_id,
                                                                bar_reservation.arrival_time,
                                                                bar_reservation.departure_time,
                                                                --bar.name as bar_name,
                                                                bar_area.name as bar_name
                                                            FROM
                                                                bar_reservation_product
                                                                inner join bar_reservation on bar_reservation.id=bar_reservation_product.bar_reservation_id
                                                                inner join bar on bar.id=bar_reservation.bar_id
                                                                inner join bar_reservation_table on bar_reservation.id=bar_reservation_table.bar_reservation_id
                                                                inner join bar_table on bar_reservation_table.table_id=bar_table.id
                                                                inner join bar_area on bar_area.id=bar_table.bar_area_id
                                                                inner join product on product.id=bar_reservation_product.product_id 
                                                                inner join product_category on product.category_id = product_category.id                                                                                                                             
                                                                left join unit on unit.id=bar_reservation_product.unit_id
                                                            WHERE
                                                                ".$cond_res." 
                                                                 and (bar_reservation_product.bar_set_menu_id is null or bar_reservation_product.bar_set_menu_id=0)
                                                            ORDER BY
                                                                product_category.structure_id,
                                                                product.name_".Portal::language().",
                                                                bar_reservation.bar_id,
                                                                bar_reservation.arrival_time,
                                                                bar_reservation.departure_time                            
                                                        ");
                    foreach($get_bar_reservation_product as $key_res=>$value_res)
                    {
                        $key_items++;
                        $quantity = $value_res['quantity']-System::calculate_number($value_res['quantity_discount']);
                        
                        $key_bar = date('d/m/Y',$value_res['arrival_time']).' '.$value_res['bar_name'].' - '.date('H:i',$value_res['arrival_time']).'-'.date('H:i',$value_res['departure_time']);
                        if(!isset($this->map['items'][$value['id']]['child'][$key_bar]))
                        {
                            $this->map['items'][$value['id']]['child'][$key_bar]['id'] = $key_bar;
                            $this->map['items'][$value['id']]['child'][$key_bar]['name'] = $key_bar;
                            $this->map['items'][$value['id']]['child'][$key_bar]['child'] = array();
                        }
                        
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['id'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['stt'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['name'] = $value_res['name'];
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['quantity'] = $quantity;
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['unit'] = $value_res['unit_name'];
                        
                        if($get_bar_reservation[$value_res['bar_reservation_id']]['full_rate']==1)
                        {
                            $value_res['price'] = $value_res['price'] / ( (1+$get_bar_reservation[$value_res['bar_reservation_id']]['bar_fee_rate']/100)*(1+$get_bar_reservation[$value_res['bar_reservation_id']]['tax_rate']/100) );
                        }
                        elseif($get_bar_reservation[$value_res['bar_reservation_id']]['full_charge']==1)
                        {
                            $value_res['price'] = $value_res['price'] / (1+$get_bar_reservation[$value_res['bar_reservation_id']]['bar_fee_rate']/100);
                        }
                        
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['price'] = $value_res['price'];
                        
                        $discount_percent = System::calculate_number($get_bar_reservation[$value_res['bar_reservation_id']]['discount_percent'])+$value_res['discount_rate'];
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['discount'] = $discount_percent;
                        
                        $value_res['price'] = $value_res['price'] - ($value_res['price']*($discount_percent/100));
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['amount'] = $value_res['price'];
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['total'] = $value_res['price']*$quantity;
                        
                        $this->map['mice_total'] += $value_res['price']*$quantity;
                        $service_total = ($value_res['price']*$quantity)*($get_bar_reservation[$value_res['bar_reservation_id']]['bar_fee_rate']/100);
                        $this->map['mice_service'] += $service_total;
                        $tax_total = (($value_res['price']*$quantity)+$service_total) *($get_bar_reservation[$value_res['bar_reservation_id']]['tax_rate']/100);
                        $this->map['mice_tax'] += $tax_total;
                        $this->map['mice_grand_total'] += ($value_res['price']*$quantity)+$service_total+$tax_total;
                    }
                }
                else
                {
                    $mi_bar = DB::fetch_all("
                                            SELECT
                                                mice_restaurant.*,
                                                --bar.name as bar_name,
                                                bar_area.name as bar_name
                                            FROM
                                                mice_restaurant
                                                inner join bar on bar.id=mice_restaurant.bar_id
                                                inner join bar_table on mice_restaurant.table_id=bar_table.id
                                                inner join bar_area on bar_area.id=bar_table.bar_area_id
                                            WHERE
                                                mice_restaurant.mice_reservation_id=".Url::get('id')."
                                            ");
                    foreach($mi_bar as $k=>$v)
                    {
                        $key_venues++;
                        $this->map['venues'][$key_venues]['id'] = $key_venues;
                        $this->map['venues'][$key_venues]['name'] = $v['bar_name'];
                        $this->map['venues'][$key_venues]['service'] = $v['service_name']==''?Portal::language('culinary'):$v['service_name'];
                        $this->map['venues'][$key_venues]['time'] = date('H:i d/m/Y',$v['time_in']).' - '.date('H:i d/m/Y',$v['time_out']);
                    }
                    $mi_bar_product = DB::fetch_all("
                                                    SELECT
                                                        mice_restaurant_product.*,
                                                        product.name_".Portal::language()." as product_name,
                                                        unit.name_".Portal::language()." as unit_name,
                                                        mice_restaurant.bar_id,
                                                        mice_restaurant.time_in,
                                                        mice_restaurant.time_out,
                                                        --bar.name as bar_name,
                                                        bar_area.name as bar_name
                                                    FROM
                                                        mice_restaurant_product
                                                        inner join mice_restaurant on mice_restaurant.id=mice_restaurant_product.mice_restaurant_id
                                                        inner join bar on bar.id=mice_restaurant.bar_id
                                                        inner join bar_table on mice_restaurant.table_id=bar_table.id
                                                        inner join bar_area on bar_area.id=bar_table.bar_area_id
                                                        inner join product on product.id=mice_restaurant_product.product_id
                                                        inner join product_category on product.category_id = product_category.id	                                                     
                                                        left join unit on unit.id=mice_restaurant_product.unit_id
                                                    WHERE
                                                        mice_restaurant.mice_reservation_id=".Url::get('id')."
                                                    ORDER BY
                                                        product_category.structure_id,
                                                        product.name_".Portal::language().",
                                                        mice_restaurant.bar_id,
                                                        mice_restaurant.time_in,
                                                        mice_restaurant.time_out
                                                    ");
                    foreach($mi_bar_product as $k=>$v)
                    {
                        $key_items++;
                        $quantity = $v['quantity']-System::calculate_number($v['quantity_discount']);
                        $key_bar = date('d/m/Y',$v['time_in']).' '.$v['bar_name'].' - '.date('H:i',$v['time_in']).'-'.date('H:i',$v['time_out']);
                        if(!isset($this->map['items'][$value['id']]['child'][$key_bar]))
                        {
                            $this->map['items'][$value['id']]['child'][$key_bar]['id'] = $key_bar;
                            $this->map['items'][$value['id']]['child'][$key_bar]['name'] = $key_bar;
                            $this->map['items'][$value['id']]['child'][$key_bar]['child'] = array();
                        }
                        
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['id'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['stt'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['name'] = $v['product_name'];
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['quantity'] = $quantity;
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['unit'] = $v['unit_name'];
                        
                        if($mi_bar[$v['mice_restaurant_id']]['full_rate']==1)
                        {
                            $v['price'] = $v['price'] / ( (1+$mi_bar[$v['mice_restaurant_id']]['service_rate']/100)*(1+$mi_bar[$v['mice_restaurant_id']]['tax_rate']/100) );
                        }
                        elseif($mi_bar[$v['mice_restaurant_id']]['full_charge']==1)
                        {
                            $v['price'] = $v['price'] / (1+$mi_bar[$v['mice_restaurant_id']]['service_rate']/100);
                        }
                        
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['price'] = $v['price'];
                        
                        $discount_percent = System::calculate_number($mi_bar[$v['mice_restaurant_id']]['discount_percent'])+$v['discount_rate'];
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['discount'] = $discount_percent;
                        
                        $v['price'] = $v['price'] - ($v['price']*($discount_percent/100));
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['amount'] = $v['price'];
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['total'] = $v['price']*$quantity;
                        
                        
                        $this->map['mice_total'] += $v['price']*$quantity;
                        $service_total = ($v['price']*$quantity)*($mi_bar[$v['mice_restaurant_id']]['service_rate']/100);
                        $this->map['mice_service'] += $service_total;
                        $tax_total = (($v['price']*$quantity)+$service_total) *($mi_bar[$v['mice_restaurant_id']]['tax_rate']/100);
                        $this->map['mice_tax'] += $tax_total;
                        $this->map['mice_grand_total'] += ($v['price']*$quantity)+$service_total+$tax_total;
                        
                    }
                    
                    
                    $cond_res = ' bar_reservation.mice_action_module='.Url::get('id').' AND bar_reservation.status!=\'CANCEL\'';
                    $get_bar_reservation = DB::fetch_all("SELECT 
                                                                bar_reservation.*,
                                                                --bar.name as bar_name,
                                                                bar_area.name as bar_name
                                                            FROM 
                                                                bar_reservation 
                                                                inner join bar_reservation_table on bar_reservation.id=bar_reservation_table.bar_reservation_id
                                                                inner join bar_table on bar_reservation_table.table_id=bar_table.id
                                                                inner join bar_area on bar_area.id=bar_table.bar_area_id
                                                                inner join bar on bar.id=bar_reservation.bar_id 
                                                            WHERE ".$cond_res);
                    foreach($get_bar_reservation as $bar_k=>$bar_v)
                    {
                        $key_venues++;
                        $this->map['venues'][$key_venues]['id'] = $key_venues;
                        $this->map['venues'][$key_venues]['name'] = $bar_v['bar_name'];
                        $this->map['venues'][$key_venues]['service'] = Portal::language('culinary');
                        $this->map['venues'][$key_venues]['time'] = date('H:i d/m/Y',$bar_v['arrival_time']).' - '.date('H:i d/m/Y',$bar_v['departure_time']);
                    }
                    $get_bar_reservation_product = DB::fetch_all("
                                                            SELECT
                                                                bar_reservation_product.*,
                                                                unit.name_".Portal::language()." as unit_name,
                                                                bar_reservation.bar_id,
                                                                bar_reservation.arrival_time,
                                                                bar_reservation.departure_time,
                                                                --bar.name as bar_name,
                                                                bar_area.name as bar_name
                                                            FROM
                                                                bar_reservation_product
                                                                inner join bar_reservation on bar_reservation.id=bar_reservation_product.bar_reservation_id
                                                                inner join bar on bar.id=bar_reservation.bar_id
                                                                inner join bar_reservation_table on bar_reservation.id=bar_reservation_table.bar_reservation_id
                                                                inner join bar_table on bar_reservation_table.table_id=bar_table.id
                                                                inner join bar_area on bar_area.id=bar_table.bar_area_id
                                                                inner join product on product.id=bar_reservation_product.product_id                                                              
                                                                left join unit on unit.id=bar_reservation_product.unit_id
                                                            WHERE
                                                                ".$cond_res." 
                                                                 and (bar_reservation_product.bar_set_menu_id is null or bar_reservation_product.bar_set_menu_id=0)
                                                            ORDER BY
                                                                bar_reservation.bar_id,
                                                                bar_reservation.arrival_time,
                                                                bar_reservation.departure_time                            
                                                        ");
                    foreach($get_bar_reservation_product as $key_res=>$value_res)
                    {
                        $key_items++;
                        $quantity = $value_res['quantity']-System::calculate_number($value_res['quantity_discount']);
                        
                        $key_bar = date('d/m/Y',$value_res['arrival_time']).' '.$value_res['bar_name'].' - '.date('H:i',$value_res['arrival_time']).'-'.date('H:i',$value_res['departure_time']);
                        if(!isset($this->map['items'][$value['id']]['child'][$key_bar]))
                        {
                            $this->map['items'][$value['id']]['child'][$key_bar]['id'] = $key_bar;
                            $this->map['items'][$value['id']]['child'][$key_bar]['name'] = $key_bar;
                            $this->map['items'][$value['id']]['child'][$key_bar]['child'] = array();
                        }
                        
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['id'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['stt'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['name'] = $value_res['name'];
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['quantity'] = $quantity;
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['unit'] = $value_res['unit_name'];
                        
                        if($get_bar_reservation[$value_res['bar_reservation_id']]['full_rate']==1)
                        {
                            $value_res['price'] = $value_res['price'] / ( (1+$get_bar_reservation[$value_res['bar_reservation_id']]['bar_fee_rate']/100)*(1+$get_bar_reservation[$value_res['bar_reservation_id']]['tax_rate']/100) );
                        }
                        elseif($get_bar_reservation[$value_res['bar_reservation_id']]['full_charge']==1)
                        {
                            $value_res['price'] = $value_res['price'] / (1+$get_bar_reservation[$value_res['bar_reservation_id']]['bar_fee_rate']/100);
                        }
                        
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['price'] = $value_res['price'];
                        
                        $discount_percent = System::calculate_number($get_bar_reservation[$value_res['bar_reservation_id']]['discount_percent'])+$value_res['discount_rate'];
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['discount'] = $discount_percent;
                        
                        $value_res['price'] = $value_res['price'] - ($value_res['price']*($discount_percent/100));
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['amount'] = $value_res['price'];
                        $this->map['items'][$value['id']]['child'][$key_bar]['child'][$key_items]['total'] = $value_res['price']*$quantity;
                        
                        $this->map['mice_total'] += $value_res['price']*$quantity;
                        $service_total = ($value_res['price']*$quantity)*($get_bar_reservation[$value_res['bar_reservation_id']]['bar_fee_rate']/100);
                        $this->map['mice_service'] += $service_total;
                        $tax_total = (($value_res['price']*$quantity)+$service_total) *($get_bar_reservation[$value_res['bar_reservation_id']]['tax_rate']/100);
                        $this->map['mice_tax'] += $tax_total;
                        $this->map['mice_grand_total'] += ($value_res['price']*$quantity)+$service_total+$tax_total;
                    }
                }
            }
            
            /** restaurant  **/
            if($value['id']=='VENDING')
            {
                if(!isset($this->map['items'][$value['id']]))
                {
                    $this->map['items'][$value['id']]['id'] = $value['id'];
                    $this->map['items'][$value['id']]['name'] = $value['name'];
                    $this->map['items'][$value['id']]['child'] = array();
                }
                if($mice['status']==1) // MICE da xac nhan thi lay du lieu tu cac module
                {
                    $cond_ve = ' ve_reservation.mice_reservation_id='.Url::get('id');
                    $get_ve_reservation = DB::fetch_all("SELECT ve_reservation.*,department.name_".Portal::language()." as department_name FROM ve_reservation inner join department on department.id=ve_reservation.department_id WHERE ".$cond_ve);
                    foreach($get_ve_reservation as $ve_k=>$ve_v)
                    {
                        $key_venues++;
                        $this->map['venues'][$key_venues]['id'] = $key_venues;
                        $this->map['venues'][$key_venues]['name'] = $ve_v['department_name'];
                        $this->map['venues'][$key_venues]['service'] = Portal::language('vending');
                        $this->map['venues'][$key_venues]['time'] = date('H:i d/m/Y',$ve_v['time_in']);
                    }
                    $get_ve_reservation_product = DB::fetch_all("
                                                            SELECT
                                                                ve_reservation_product.*,
                                                                ve_reservation_product.bar_reservation_id as ve_reservation_id,
                                                                unit.name_".Portal::language()." as unit_name
                                                            FROM
                                                                ve_reservation_product
                                                                inner join ve_reservation on ve_reservation.id=ve_reservation_product.bar_reservation_id
                                                                left join unit on unit.id=ve_reservation_product.unit_id
                                                            WHERE
                                                                ".$cond_ve."                              
                                                        ");
                    foreach($get_ve_reservation_product as $key_ve=>$value_ve)
                    {
                        $key_items++;
                        $quantity = $value_ve['quantity']-System::calculate_number($value_ve['quantity_discount']);
                        $this->map['items'][$value['id']]['child'][$key_items]['id'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['stt'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['name'] = $value_ve['name'].' '.Portal::language('day').' '.date('d/m/Y',$get_ve_reservation[$value_ve['ve_reservation_id']]['time_in']).''.Portal::language('in').' '.$get_ve_reservation[$value_ve['ve_reservation_id']]['department_name'];
                        $this->map['items'][$value['id']]['child'][$key_items]['quantity'] = $quantity;
                        $this->map['items'][$value['id']]['child'][$key_items]['unit'] = $value_ve['unit_name'];
                        
                        if($get_ve_reservation[$value_ve['ve_reservation_id']]['full_rate']==1)
                        {
                            $value_ve['price'] = $value_ve['price'] / ( (1+$get_ve_reservation[$value_ve['ve_reservation_id']]['bar_fee_rate']/100)*(1+$get_ve_reservation[$value_ve['ve_reservation_id']]['tax_rate']/100) );
                        }
                        elseif($get_ve_reservation[$value_ve['ve_reservation_id']]['full_charge']==1)
                        {
                            $value_ve['price'] = $value_ve['price'] / (1+$get_ve_reservation[$value_ve['ve_reservation_id']]['bar_fee_rate']/100);
                        }
                        
                        $this->map['items'][$value['id']]['child'][$key_items]['price'] = $value_ve['price'];
                        
                        $discount_percent = System::calculate_number($get_ve_reservation[$value_ve['ve_reservation_id']]['discount_percent'])+$value_ve['discount_rate'];
                        $this->map['items'][$value['id']]['child'][$key_items]['discount'] = $discount_percent;
                        
                        $value_ve['price'] = $value_ve['price'] - ($value_ve['price']*($discount_percent/100));
                        $this->map['items'][$value['id']]['child'][$key_items]['amount'] = $value_ve['price'];
                        $this->map['items'][$value['id']]['child'][$key_items]['total'] = $value_ve['price']*$quantity;
                        
                        $this->map['mice_total'] += $value_ve['price']*$quantity;
                        $service_total = ($value_ve['price']*$quantity)*($get_ve_reservation[$value_ve['ve_reservation_id']]['bar_fee_rate']/100);
                        $this->map['mice_service'] += $service_total;
                        $tax_total = (($value_ve['price']*$quantity)+$service_total) *($get_ve_reservation[$value_ve['ve_reservation_id']]['tax_rate']/100);
                        $this->map['mice_tax'] += $tax_total;
                        $this->map['mice_grand_total'] += ($value_ve['price']*$quantity)+$service_total+$tax_total;
                    }
                }
                else
                {
                    $mi_vending = DB::fetch_all("
                                            SELECT
                                                mice_vending.*,
                                                department.name_".Portal::language()." as department_name
                                            FROM
                                                mice_vending
                                                inner join department on department.id=mice_vending.department_id
                                            WHERE
                                                mice_vending.mice_reservation_id=".Url::get('id')."
                                            ");
                    foreach($mi_vending as $k=>$v)
                    {
                        $key_venues++;
                        $this->map['venues'][$key_venues]['id'] = $key_venues;
                        $this->map['venues'][$key_venues]['name'] = $v['department_name'];
                        $this->map['venues'][$key_venues]['service'] = $v['service_name']==''?Portal::language('vending'):$v['service_name'];
                        $this->map['venues'][$key_venues]['time'] = date('H:i d/m/Y',$v['time_in']);
                    }
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
                        $key_items++;
                        $quantity = $v['quantity']-System::calculate_number($v['quantity_discount']);
                        $this->map['items'][$value['id']]['child'][$key_items]['id'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['stt'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['name'] = $v['product_name'].' '.Portal::language('day').' '.date('d/m/Y',$mi_vending[$v['mice_vending_id']]['time_in']).' '.Portal::language('in').' '.$mi_vending[$v['mice_vending_id']]['department_name'];
                        $this->map['items'][$value['id']]['child'][$key_items]['quantity'] = $quantity;
                        $this->map['items'][$value['id']]['child'][$key_items]['unit'] = $v['unit_name'];
                        
                        if($mi_vending[$v['mice_vending_id']]['full_rate']==1)
                        {
                            $v['price'] = $v['price'] / ( (1+$mi_vending[$v['mice_vending_id']]['service_rate']/100)*(1+$mi_vending[$v['mice_vending_id']]['tax_rate']/100) );
                        }
                        elseif($mi_vending[$v['mice_vending_id']]['full_charge']==1)
                        {
                            $v['price'] = $v['price'] / (1+$mi_vending[$v['mice_vending_id']]['service_rate']/100);
                        }
                        
                        $this->map['items'][$value['id']]['child'][$key_items]['price'] = $v['price'];
                        
                        $discount_percent = System::calculate_number($mi_vending[$v['mice_vending_id']]['discount_percent'])+$v['discount_rate'];
                        $this->map['items'][$value['id']]['child'][$key_items]['discount'] = $discount_percent;
                        
                        $v['price'] = $v['price'] - ($v['price']*($discount_percent/100));
                        $this->map['items'][$value['id']]['child'][$key_items]['amount'] = $v['price'];
                        $this->map['items'][$value['id']]['child'][$key_items]['total'] = $v['price']*$quantity;
                        
                        $this->map['mice_total'] += $v['price']*$quantity;
                        $service_total = ($v['price']*$quantity)*($mi_vending[$v['mice_vending_id']]['service_rate']/100);
                        $this->map['mice_service'] += $service_total;
                        $tax_total = (($v['price']*$quantity)+$service_total) *($mi_vending[$v['mice_vending_id']]['tax_rate']/100);
                        $this->map['mice_tax'] += $tax_total;
                        $this->map['mice_grand_total'] += ($v['price']*$quantity)+$service_total+$tax_total;
                        
                    }
                    
                    $cond_ve = ' ve_reservation.mice_action_module='.Url::get('id');
                    $get_ve_reservation = DB::fetch_all("SELECT ve_reservation.*,department.name_".Portal::language()." as department_name FROM ve_reservation inner join department on department.id=ve_reservation.department_id WHERE ".$cond_ve);
                    foreach($get_ve_reservation as $ve_k=>$ve_v)
                    {
                        $key_venues++;
                        $this->map['venues'][$key_venues]['id'] = $key_venues;
                        $this->map['venues'][$key_venues]['name'] = $ve_v['department_name'];
                        $this->map['venues'][$key_venues]['service'] = Portal::language('vending');
                        $this->map['venues'][$key_venues]['time'] = date('H:i d/m/Y',$ve_v['time_in']);
                    }
                    $get_ve_reservation_product = DB::fetch_all("
                                                            SELECT
                                                                ve_reservation_product.*,
                                                                ve_reservation_product.bar_reservation_id as ve_reservation_id,
                                                                unit.name_".Portal::language()." as unit_name
                                                            FROM
                                                                ve_reservation_product
                                                                inner join ve_reservation on ve_reservation.id=ve_reservation_product.bar_reservation_id
                                                                left join unit on unit.id=ve_reservation_product.unit_id
                                                            WHERE
                                                                ".$cond_ve."                              
                                                        ");
                    foreach($get_ve_reservation_product as $key_ve=>$value_ve)
                    {
                        $key_items++;
                        $quantity = $value_ve['quantity']-System::calculate_number($value_ve['quantity_discount']);
                        $this->map['items'][$value['id']]['child'][$key_items]['id'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['stt'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['name'] = $value_ve['name'].' '.Portal::language('day').' '.date('d/m/Y',$get_ve_reservation[$value_ve['ve_reservation_id']]['time_in']).''.Portal::language('in').' '.$get_ve_reservation[$value_ve['ve_reservation_id']]['department_name'];
                        $this->map['items'][$value['id']]['child'][$key_items]['quantity'] = $quantity;
                        $this->map['items'][$value['id']]['child'][$key_items]['unit'] = $value_ve['unit_name'];
                        
                        if($get_ve_reservation[$value_ve['ve_reservation_id']]['full_rate']==1)
                        {
                            $value_ve['price'] = $value_ve['price'] / ( (1+$get_ve_reservation[$value_ve['ve_reservation_id']]['bar_fee_rate']/100)*(1+$get_ve_reservation[$value_ve['ve_reservation_id']]['tax_rate']/100) );
                        }
                        elseif($get_ve_reservation[$value_ve['ve_reservation_id']]['full_charge']==1)
                        {
                            $value_ve['price'] = $value_ve['price'] / (1+$get_ve_reservation[$value_ve['ve_reservation_id']]['bar_fee_rate']/100);
                        }
                        
                        $this->map['items'][$value['id']]['child'][$key_items]['price'] = $value_ve['price'];
                        
                        $discount_percent = System::calculate_number($get_ve_reservation[$value_ve['ve_reservation_id']]['discount_percent'])+$value_ve['discount_rate'];
                        $this->map['items'][$value['id']]['child'][$key_items]['discount'] = $discount_percent;
                        
                        $value_ve['price'] = $value_ve['price'] - ($value_ve['price']*($discount_percent/100));
                        $this->map['items'][$value['id']]['child'][$key_items]['amount'] = $value_ve['price'];
                        $this->map['items'][$value['id']]['child'][$key_items]['total'] = $value_ve['price']*$quantity;
                        
                        $this->map['mice_total'] += $value_ve['price']*$quantity;
                        $service_total = ($value_ve['price']*$quantity)*($get_ve_reservation[$value_ve['ve_reservation_id']]['bar_fee_rate']/100);
                        $this->map['mice_service'] += $service_total;
                        $tax_total = (($value_ve['price']*$quantity)+$service_total) *($get_ve_reservation[$value_ve['ve_reservation_id']]['tax_rate']/100);
                        $this->map['mice_tax'] += $tax_total;
                        $this->map['mice_grand_total'] += ($value_ve['price']*$quantity)+$service_total+$tax_total;
                    }
                }
            }
            
            /** party **/
            if($value['id']=='BANQUET')
            {
                if(!isset($this->map['items'][$value['id']]))
                {
                    $this->map['items'][$value['id']]['id'] = $value['id'];
                    $this->map['items'][$value['id']]['name'] = $value['name'];
                    $this->map['items'][$value['id']]['child'] = array();
                }
                if($mice['status']==1) // MICE da xac nhan thi lay du lieu tu cac module
                {
                    $cond_party = ' party_reservation.mice_reservation_id='.Url::get('id');
                    $get_party_reservation = DB::fetch_all("SELECT party_reservation.* FROM party_reservation WHERE".$cond_party);
                    $get_party_product = DB::fetch_all("
                                                        SELECT
                                                            party_reservation_detail.*,
                                                            product.name_".Portal::language()." as product_name
                                                        FROM
                                                            party_reservation_detail
                                                            inner join party_reservation on party_reservation.id=party_reservation_detail.party_reservation_id
                                                            inner join product on party_reservation_detail.product_id=product.id
                                                        WHERE
                                                            ".$cond_party."
                                                    ");
                    $get_party_room = DB::fetch_all("
                                                    SELECT
                                                        party_reservation_room.*,
                                                        party_room.name as party_room_name
                                                    FROM
                                                        party_reservation_room
                                                        inner join party_reservation on party_reservation.id=party_reservation_room.party_reservation_id
                                                        inner join party_room on party_room.id=party_reservation_room.party_room_id
                                                        
                                                    WHERE
                                                        ".$cond_party."
                                                    ");
                    foreach($get_party_product as $p_party_id=>$p_party_room)
                    {
                        $key_items++;
                        $this->map['items'][$value['id']]['child'][$key_items]['id'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['stt'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['name'] = $p_party_room['product_name'].' '.date('d/m/Y',$get_party_reservation[$p_party_room['party_reservation_id']]['checkin_time']);
                        $this->map['items'][$value['id']]['child'][$key_items]['quantity'] = $p_party_room['quantity'];
                        $this->map['items'][$value['id']]['child'][$key_items]['unit'] = $p_party_room['product_unit'];
                        $this->map['items'][$value['id']]['child'][$key_items]['price'] = $p_party_room['price'];
                        $this->map['items'][$value['id']]['child'][$key_items]['discount'] = 0;
                        $this->map['items'][$value['id']]['child'][$key_items]['amount'] = $p_party_room['price'];
                        $this->map['items'][$value['id']]['child'][$key_items]['total'] = $p_party_room['price']*$p_party_room['quantity'];
                        
                        $this->map['mice_total'] += $p_party_room['price']*$p_party_room['quantity'];
                        $service_total = ($p_party_room['price']*$p_party_room['quantity'])*($get_party_reservation[$p_party_room['party_reservation_id']]['extra_service_rate']/100);
                        $this->map['mice_service'] += $service_total;
                        $tax_total = (($p_party_room['price']*$p_party_room['quantity'])+$service_total) *($get_party_reservation[$p_party_room['party_reservation_id']]['vat']/100);
                        $this->map['mice_tax'] += $tax_total;
                        $this->map['mice_grand_total'] += ($p_party_room['price']*$p_party_room['quantity'])+$service_total+$tax_total;
                    }
                    foreach($get_party_room as $r_party_id=>$r_party_room)
                    {
                        $key_venues++;
                        $this->map['venues'][$key_venues]['id'] = $key_venues;
                        $this->map['venues'][$key_venues]['name'] = $r_party_room['party_room_name'];
                        $this->map['venues'][$key_venues]['service'] = Portal::language('room');
                        $this->map['venues'][$key_venues]['time'] = date('H:i d/m/Y',$get_party_reservation[$r_party_room['party_reservation_id']]['checkin_time']).' - '.date('H:i d/m/Y',$get_party_reservation[$r_party_room['party_reservation_id']]['checkout_time']);
                        
                        $key_items++;
                        $this->map['items'][$value['id']]['child'][$key_items]['id'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['stt'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['name'] = $r_party_room['party_room_name'].' '.date('d/m/Y',$get_party_reservation[$r_party_room['party_reservation_id']]['checkin_time']);
                        $this->map['items'][$value['id']]['child'][$key_items]['quantity'] = 1;
                        $this->map['items'][$value['id']]['child'][$key_items]['unit'] = Portal::language('room');
                        $this->map['items'][$value['id']]['child'][$key_items]['price'] = $r_party_room['price'];
                        $this->map['items'][$value['id']]['child'][$key_items]['discount'] = 0;
                        $this->map['items'][$value['id']]['child'][$key_items]['amount'] = $r_party_room['price'];
                        $this->map['items'][$value['id']]['child'][$key_items]['total'] = $r_party_room['price'];
                        
                        $this->map['mice_total'] += $r_party_room['price'];
                        $service_total = ($r_party_room['price'])*($get_party_reservation[$r_party_room['party_reservation_id']]['extra_service_rate']/100);
                        $this->map['mice_service'] += $service_total;
                        $tax_total = (($r_party_room['price'])+$service_total) *($get_party_reservation[$r_party_room['party_reservation_id']]['vat']/100);
                        $this->map['mice_tax'] += $tax_total;
                        $this->map['mice_grand_total'] += ($r_party_room['price'])+$service_total+$tax_total;
                    }
                }
                else
                {
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
                                                        mice_party_detail_room.*,
                                                        party_room.name as party_room_name
                                                    FROM
                                                        mice_party_detail_room
                                                        inner join mice_party on mice_party.id=mice_party_detail_room.mice_party_id
                                                        inner join party_room on party_room.id=mice_party_detail_room.party_room_id
                                                    WHERE
                                                        mice_party.mice_reservation_id=".Url::get('id')."
                                                    ");
                    foreach($mi_party_product as $k=>$v)
                    {
                        $key_items++;
                        $this->map['items'][$value['id']]['child'][$key_items]['id'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['stt'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['name'] = $v['product_name'].' '.date('d/m/Y',$mi_party[$v['mice_party_id']]['time_in']);
                        $this->map['items'][$value['id']]['child'][$key_items]['quantity'] = $v['quantity'];
                        $this->map['items'][$value['id']]['child'][$key_items]['unit'] = $v['unit_name'];
                        $this->map['items'][$value['id']]['child'][$key_items]['price'] = $v['price'];
                        $this->map['items'][$value['id']]['child'][$key_items]['discount'] = 0;
                        $this->map['items'][$value['id']]['child'][$key_items]['amount'] = $v['price'];
                        $this->map['items'][$value['id']]['child'][$key_items]['total'] = $v['price']*$v['quantity'];
                        
                        $this->map['mice_total'] += $v['price']*$v['quantity'];
                        $service_total = ($v['price']*$v['quantity'])*($mi_party[$v['mice_party_id']]['service_rate']/100);
                        $this->map['mice_service'] += $service_total;
                        $tax_total = (($v['price']*$v['quantity'])+$service_total) *($mi_party[$v['mice_party_id']]['tax_rate']/100);
                        $this->map['mice_tax'] += $tax_total;
                        $this->map['mice_grand_total'] += ($v['price']*$v['quantity'])+$service_total+$tax_total;
                    }
                    foreach($mi_party_room as $k=>$v)
                    {
                        $key_venues++;
                        $this->map['venues'][$key_venues]['id'] = $key_venues;
                        $this->map['venues'][$key_venues]['name'] = $v['party_room_name'];
                        $this->map['venues'][$key_venues]['service'] = $v['service_name']==''?Portal::language('room'):$v['service_name'];
                        $this->map['venues'][$key_venues]['time'] = date('H:i d/m/Y',$mi_party[$v['mice_party_id']]['time_in']).' - '.date('H:i d/m/Y',$mi_party[$v['mice_party_id']]['time_out']);
                        
                        $key_items++;
                        $this->map['items'][$value['id']]['child'][$key_items]['id'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['stt'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['name'] = $v['party_room_name'].' '.date('d/m/Y',$mi_party[$v['mice_party_id']]['time_in']);
                        $this->map['items'][$value['id']]['child'][$key_items]['quantity'] = 1;
                        $this->map['items'][$value['id']]['child'][$key_items]['unit'] = $v['service_name']==''?Portal::language('room'):$v['service_name'];
                        $this->map['items'][$value['id']]['child'][$key_items]['price'] = $v['price'];
                        $this->map['items'][$value['id']]['child'][$key_items]['discount'] = 0;
                        $this->map['items'][$value['id']]['child'][$key_items]['amount'] = $v['price'];
                        $this->map['items'][$value['id']]['child'][$key_items]['total'] = $v['price'];
                        
                        $this->map['mice_total'] += $v['price'];
                        $service_total = ($v['price'])*($mi_party[$v['mice_party_id']]['service_rate']/100);
                        $this->map['mice_service'] += $service_total;
                        $tax_total = (($v['price'])+$service_total) *($mi_party[$v['mice_party_id']]['tax_rate']/100);
                        $this->map['mice_tax'] += $tax_total;
                        $this->map['mice_grand_total'] += ($v['price'])+$service_total+$tax_total;
                    }
                    
                    $cond_party = ' party_reservation.mice_action_module='.Url::get('id');
                    $get_party_reservation = DB::fetch_all("SELECT party_reservation.* FROM party_reservation WHERE".$cond_party);
                    $get_party_product = DB::fetch_all("
                                                        SELECT
                                                            party_reservation_detail.*,
                                                            product.name_".Portal::language()." as product_name
                                                        FROM
                                                            party_reservation_detail
                                                            inner join party_reservation on party_reservation.id=party_reservation_detail.party_reservation_id
                                                            inner join product on party_reservation_detail.product_id=product.id
                                                        WHERE
                                                            ".$cond_party."
                                                    ");
                    $get_party_room = DB::fetch_all("
                                                    SELECT
                                                        party_reservation_room.*,
                                                        party_room.name as party_room_name
                                                    FROM
                                                        party_reservation_room
                                                        inner join party_reservation on party_reservation.id=party_reservation_room.party_reservation_id
                                                        inner join party_room on party_room.id=party_reservation_room.party_room_id
                                                        
                                                    WHERE
                                                        ".$cond_party."
                                                    ");
                    foreach($get_party_product as $p_party_id=>$p_party_room)
                    {
                        $key_items++;
                        $this->map['items'][$value['id']]['child'][$key_items]['id'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['stt'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['name'] = $p_party_room['product_name'].' '.date('d/m/Y',$get_party_reservation[$p_party_room['party_reservation_id']]['checkin_time']);
                        $this->map['items'][$value['id']]['child'][$key_items]['quantity'] = $p_party_room['quantity'];
                        $this->map['items'][$value['id']]['child'][$key_items]['unit'] = $p_party_room['product_unit'];
                        $this->map['items'][$value['id']]['child'][$key_items]['price'] = $p_party_room['price'];
                        $this->map['items'][$value['id']]['child'][$key_items]['discount'] = 0;
                        $this->map['items'][$value['id']]['child'][$key_items]['amount'] = $p_party_room['price'];
                        $this->map['items'][$value['id']]['child'][$key_items]['total'] = $p_party_room['price']*$p_party_room['quantity'];
                        
                        $this->map['mice_total'] += $p_party_room['price']*$p_party_room['quantity'];
                        $service_total = ($p_party_room['price']*$p_party_room['quantity'])*($get_party_reservation[$p_party_room['party_reservation_id']]['extra_service_rate']/100);
                        $this->map['mice_service'] += $service_total;
                        $tax_total = (($p_party_room['price']*$p_party_room['quantity'])+$service_total) *($get_party_reservation[$p_party_room['party_reservation_id']]['vat']/100);
                        $this->map['mice_tax'] += $tax_total;
                        $this->map['mice_grand_total'] += ($p_party_room['price']*$p_party_room['quantity'])+$service_total+$tax_total;
                    }
                    foreach($get_party_room as $r_party_id=>$r_party_room)
                    {
                        $key_venues++;
                        $this->map['venues'][$key_venues]['id'] = $key_venues;
                        $this->map['venues'][$key_venues]['name'] = $r_party_room['party_room_name'];
                        $this->map['venues'][$key_venues]['service'] = Portal::language('room');
                        $this->map['venues'][$key_venues]['time'] = date('H:i d/m/Y',$get_party_reservation[$r_party_room['party_reservation_id']]['checkin_time']).' - '.date('H:i d/m/Y',$get_party_reservation[$r_party_room['party_reservation_id']]['checkout_time']);
                        
                        $key_items++;
                        $this->map['items'][$value['id']]['child'][$key_items]['id'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['stt'] = $key_items;
                        $this->map['items'][$value['id']]['child'][$key_items]['name'] = $r_party_room['party_room_name'].' '.date('d/m/Y',$get_party_reservation[$r_party_room['party_reservation_id']]['checkin_time']);
                        $this->map['items'][$value['id']]['child'][$key_items]['quantity'] = 1;
                        $this->map['items'][$value['id']]['child'][$key_items]['unit'] = Portal::language('room');
                        $this->map['items'][$value['id']]['child'][$key_items]['price'] = $r_party_room['price'];
                        $this->map['items'][$value['id']]['child'][$key_items]['discount'] = 0;
                        $this->map['items'][$value['id']]['child'][$key_items]['amount'] = $r_party_room['price'];
                        $this->map['items'][$value['id']]['child'][$key_items]['total'] = $r_party_room['price'];
                        
                        $this->map['mice_total'] += $r_party_room['price'];
                        $service_total = ($r_party_room['price'])*($get_party_reservation[$r_party_room['party_reservation_id']]['extra_service_rate']/100);
                        $this->map['mice_service'] += $service_total;
                        $tax_total = (($r_party_room['price'])+$service_total) *($get_party_reservation[$r_party_room['party_reservation_id']]['vat']/100);
                        $this->map['mice_tax'] += $tax_total;
                        $this->map['mice_grand_total'] += ($r_party_room['price'])+$service_total+$tax_total;
                    }
                }
                
            }
        }
        $this->map['user_full_name'] = DB::fetch('select full_name from party where user_id=\''.$this->map['creater'].'\'','full_name');
        $this->map['user_view_full_name'] = 'Prepared by: '.DB::fetch('select full_name from party where user_id=\''.User::id().'\'','full_name');
        
        $list_department_setup = DB::fetch_all("SELECT * FROM mice_department_setup ORDER BY position");
        $this->map['department_setup'] = $list_department_setup;
        $this->map['department_setup_option'] = '';
        foreach($list_department_setup as $k_depart=>$v_depart)
        {
            $this->map['department_setup_option'] .= '<option value="'.$v_depart['id'].'" >'.$v_depart['name'].'</option>';
        }
        
        $list_location_setup = DB::fetch_all("SELECT * FROM mice_location_setup ORDER BY position");
        $this->map['location_setup'] = $list_location_setup;
        $this->map['location_setup_option'] = '';
        foreach($list_location_setup as $k_loct=>$v_loct)
        {
            $this->map['location_setup_option'] .= '<option value="'.$v_loct['id'].'" >'.$v_loct['name'].'</option>';
        }
        
        // Lay setup da duoc luu
        $setup_beo = DB::fetch_all("SELECT 
                                        mice_setup_beo.*,
                                        mice_department_setup.name as department_name,
                                        mice_location_setup.name as localtion_name
                                    FROM 
                                        mice_setup_beo 
                                        inner join mice_department_setup on mice_department_setup.id=mice_setup_beo.mice_department_id
                                        inner join mice_location_setup on mice_location_setup.id=mice_setup_beo.mice_location_id
                                    WHERE 
                                        mice_setup_beo.mice_reservation_id=".Url::get('id')."
                                    ORDER BY
                                        mice_setup_beo.input_count DESC
                                    ");
        
        $this->map['setup'] = array();
        foreach($setup_beo as $beo_key=>$beo_value)
        {
            $this->map['setup'][$beo_key] = $beo_value;
            $this->map['setup'][$beo_key]['in_date'] = date('d/m/Y',$beo_value['time']);
            $this->map['setup'][$beo_key]['hour'] = date('H:i',$beo_value['time']);
        }
        //die;
        $this->parse_layout('beoform',$this->map);
    }
    
    function count_day($from_date,$to_date)
    {
        $count = 0;
        for($i=Date_Time::to_time($from_date);$i<Date_Time::to_time($to_date);$i+=(24*3600))
        {
            $count ++;
        }
        return $count;
    }
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
}
?>
