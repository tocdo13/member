<?php
class ConfirmMiceReservationForm extends Form
{
	function ConfirmMiceReservationForm()
    {
		Form::Form('ConfirmMiceReservationForm');
        $this->link_css('packages/hotel/packages/mice/skins/css/font-awesome-4.5.0/css/font-awesome.min.css');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_css('packages/hotel/packages/mice/skins/jquery.windows-engine.css');
        $this->link_js('packages/hotel/packages/mice/skins/jquery.windows-engine.js');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.mask.min.js');
	}
    function on_submit()
    {
        if(Url::get('act')=='CLOSE')
        {
            DB::update('mice_reservation',array('close'=>1),'id='.Url::get('id'));
            System::log('EDIT','Close mice '.Url::get('id'),'Close mice '.Url::get('id'),Url::get('id'));
        }
        if(Url::get('act')=='OPEN')
        {
            DB::update('mice_reservation',array('close'=>0),'id='.Url::get('id'));
            System::log('EDIT','Open mice '.Url::get('id'),'Open mice '.Url::get('id'),Url::get('id'));
        }
        if(Url::get('act')=='UN_CONFIRM')
        {
            // thao tac huy xac nhan khach hang xoa tat ca cac ban ghi trong databae CHINH lien quan den MICE
            
            // xoa phong:
            if(DB::exists('SELECT id from reservation where mice_reservation_id='.Url::get('id').''))
            {
                require_once 'packages/hotel/packages/reception/modules/Reservation/forms/delete.php';
                $rec_delete = DB::fetch_all('SELECT id,mice_action_module from reservation where mice_reservation_id='.Url::get('id').'');
                foreach($rec_delete as $k_rec=>$v_rec)
                {
                    if($v_rec['mice_action_module']==0 OR $v_rec['mice_action_module']=='')
                        DeleteReservationForm::delete($v_rec['id']);
                }
            }
            
            // xoa dich vu mo rong:
            if(DB::exists('SELECT id from extra_service_invoice where mice_reservation_id='.Url::get('id').''))
            {
                $extra_delete = DB::fetch_all('SELECT id,mice_action_module from extra_service_invoice where mice_reservation_id='.Url::get('id').'');
                foreach($extra_delete as $k_extra=>$v_extra)
                {
                    if($v_extra['mice_action_module']==0 OR $v_extra['mice_action_module']=='')
                    {
                        DB::delete('extra_service_invoice_detail','invoice_id='.$v_extra['id']);
                        DB::delete('extra_service_invoice','id='.$v_extra['id']);
                    }
                }
            }
            
            // xoa ban
            if(DB::exists('SELECT id from bar_reservation where mice_reservation_id='.Url::get('id').''))
            {
                require_once 'packages/hotel/packages/restaurant/modules/BarReservationNew/forms/delete.php';
                $res_delete = DB::fetch_all('SELECT id,mice_action_module from bar_reservation where mice_reservation_id='.Url::get('id').'');
                foreach($res_delete as $k_res=>$v_res)
                {
                    if($v_res['mice_action_module']==0 OR $v_res['mice_action_module']=='')
                        DeleteBarReservationNewForm::delete($v_res['id']);
                }
            }
            
            // xoa party
            if(DB::exists('SELECT id from party_reservation where mice_reservation_id='.Url::get('id').''))
            {
                require_once 'packages/hotel/packages/banquet/modules/BanquetReservation/forms/delete.php';
                $party_delete = DB::fetch_all('SELECT id,mice_action_module from party_reservation where mice_reservation_id='.Url::get('id').'');
                foreach($party_delete as $k_party=>$v_party)
                {
                    if($v_party['mice_action_module']==0 OR $v_party['mice_action_module']=='')
                        DeleteBanquetReservationForm::delete($v_party['id']);
                }
            }
            
            // xoa vending
            if(DB::exists('SELECT id from ve_reservation where mice_reservation_id='.Url::get('id').''))
            {
                require_once 'packages/hotel/includes/php/product.php';
                $ve_delete = DB::fetch_all('SELECT id,mice_action_module from ve_reservation where mice_reservation_id='.Url::get('id').'');
                foreach($ve_delete as $k_ve=>$v_ve)
                {
                    if($v_party['mice_action_module']==0 OR $v_party['mice_action_module']=='')
                    {
    					DB::delete_id('ve_reservation', $v_ve['id']);
    					DB::delete('ve_reservation_product', 'bar_reservation_id = '.$v_ve['id']);
                        DB::delete('payment','payment.bill_id=\''.$v_ve['id'].'\' AND payment.type=\'VEND\'');
                        DeliveryOrders::delete_delivery_order($v_ve['id'],'VENDING');
                    }
                }
            }
            
            // xoa ticket
            if(DB::exists('SELECT id from ticket_reservation where mice_reservation_id='.Url::get('id').''))
            {
                $ticket_delete = DB::fetch_all('SELECT id,mice_action_module from ticket_reservation where mice_reservation_id='.Url::get('id').'');
                foreach($ticket_delete as $k_ticket=>$v_ticket)
                {
                    if($v_ticket['mice_action_module']==0 OR $v_ticket['mice_action_module']=='')
                    {
    					$ticket_invoice_id = DB::fetch_all('select ticket_invoice.id from ticket_invoice where ticket_reservation_id='.$k_ticket.'');
                        foreach($ticket_invoice_id as $v_invoice_ticket)
                        {
                            DB::delete('ticket_invoice_detail',' ticket_invoice_id = '. $v_invoice_ticket['id']);
                        }
                        DB::delete_id( 'ticket_reservation', $k_ticket );
                        DB::delete('ticket_invoice',' ticket_reservation_id = '.$k_ticket);
                        DB::delete('TICKET_CANCELATION',' ticket_reservation_id = '.$k_ticket);
                        DB::delete('PAYMENT',' BILL_ID = '.$k_ticket.' and type = \'TICKET\'');
                    }
                }
            }
            
            
            // xoa hoa don
            if(DB::exists('SELECT id from mice_invoice where mice_reservation_id='.Url::get('id')))
            {
                $invoice = DB::fetch_all('SELECT id from mice_invoice where mice_reservation_id='.Url::get('id'));
                foreach($invoice as $k_bill=>$v_bill)
                {
                    DB::delete('mice_invoice_detail','mice_invoice_id='.$v_bill['id']);
                    DB::delete('mice_invoice','id='.$v_bill['id']);
                    DB::delete('payment','payment.bill_id=\''.$v_bill['id'].'\' AND payment.type=\'BILL_MICE\'');
                }
            }
            
            DB::delete('payment','payment.bill_id=\''.Url::get('id').'\' AND payment.type=\'MICE\'');
            DB::update('mice_reservation',array('status'=>''),'id='.Url::get('id'));
            
            System::log('EDIT','Un confirm mice '.Url::get('id'),'Un confirm mice '.Url::get('id'),Url::get('id'));
        }
        
        Url::redirect('mice_reservation',array('cmd'=>'edit','id'=>Url::get('id')));
    }
	function draw()
    {
		$this->map = array();
        $cond = '1=1 ';
        $this->map['deposit'] = 0;
        $this->map['payment'] = 0;
        $this->map['total_amount'] = 0;
        $this->map['remain'] = 0;
        if(Url::get('id'))
        {
            $this->map += DB::fetch('SELECT 
                                        mice_reservation.*,
                                        customer.name as customer_name,
                                        traveller.first_name || \' \' || traveller.last_name as traveller_name,
                                        TO_CHAR(mice_reservation.start_date,\'DD/MM/YYYY\') as start_date,
                                        TO_CHAR(mice_reservation.end_date,\'DD/MM/YYYY\') as end_date,
                                        TO_CHAR(mice_reservation.cut_of_date,\'DD/MM/YYYY\') as cut_of_date
                                    FROM 
                                        mice_reservation 
                                        left join customer on customer.id=mice_reservation.customer_id
                                        left join traveller on traveller.id=mice_reservation.traveller_id
                                    WHERE 
                                        mice_reservation.id='.Url::get('id').' 
                                        AND mice_reservation.portal_id=\''.PORTAL_ID.'\'
                                        ');
            $cond .= ' AND mice_reservation.id='.Url::get('id');
            $this->map['deposit'] = DB::fetch("SELECT sum(amount) as total FROM payment WHERE bill_id='".Url::get('id')."' AND type='MICE' AND type_dps is not null ","total");
            $this->map['payment'] = DB::fetch("SELECT sum(amount) as total FROM payment WHERE bill_id='".Url::get('id')."' AND type='MICE' ","total");
        }
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
                                            ORDER BY
                                                department.stt
                                            ');
        $active_department['EXS'] = array('id'=>'EXS','name'=>Portal::language('extra_service'));
        $this->map['check_un_confirm'] = 1; // cho phep huy xac nhan
        // da thanh toan roi thi khong cho huy xac nhan
        if(DB::exists('select id from mice_invoice where mice_reservation_id='.Url::get('id'))){
            $this->map['check_un_confirm'] = 0;
        }
        $this->map['check_close_mice'] = 1; // cho phep dong mice
        $this->map['list_room_option'] = '<option value="">'.Portal::language('select_room').'</option>';
        foreach($active_department as $key=>$value)
        {
            $active_department[$key]['item'] = array();
            $active_department[$key]['count_item'] = 0;
            $active_department[$key]['icon'] = '';
            $active_department[$key]['description'] = '';
            /** reservation  **/
            if($value['id']=='REC')
            {
                if(Url::get('id'))
                {
                    $ReservationRoomList = DB::fetch_all(" SELECT 
                                                                reservation_room.*,
                                                                NVL(reservation.mice_action_module,0) as mice_action_module,
                                                                room.name as room_name
                                                            FROM 
                                                                reservation_room 
                                                                INNER JOIN reservation ON reservation_room.reservation_id=reservation.id
                                                                left join room on room.id=reservation_room.room_id
                                                            WHERE 
                                                                reservation.mice_reservation_id=".Url::get('id')." 
                                                                AND reservation_room.status!='CANCEL' 
                                                            ");
                    foreach($ReservationRoomList as $K_ResRoom=>$V_ResRoom)
                    {
                        $V_ResRoom['total'] = 0;
                        $V_ResRoom['link'] = '?page=reservation&cmd=edit&id='.$V_ResRoom['reservation_id'].'&r_r_id='.$V_ResRoom['id'];
                        if(($V_ResRoom['status']=='CHECKIN' OR $V_ResRoom['status']=='CHECKOUT') AND $V_ResRoom['mice_action_module']==0)
                        {
                            $this->map['check_un_confirm'] = 0; // khong cho phep huy xac nhan
                        }
                        if($V_ResRoom['status']!='CHECKOUT')
                        {
                            $this->map['check_close_mice'] = 0;
                        }
                        $V_ResRoom['folio'] = MiceReservationDB::get_total_room($V_ResRoom['id']);
                        foreach($V_ResRoom['folio'] as $K_folio=>$V_Folio)
                        {
                            $Service_amount = $V_Folio['amount']*$V_Folio['service_rate']/100;
                            $tax_amount = ($V_Folio['amount']+$Service_amount)*$V_Folio['tax_rate']/100;
                            if($V_Folio['type']=='DISCOUNT' OR $V_Folio['type']=='DEPOSIT')
                            {
                                $V_ResRoom['total'] -= $V_Folio['amount']+$Service_amount+$tax_amount;
                                $this->map['total_amount'] -= $V_Folio['amount']+$Service_amount+$tax_amount;
                            }
                            else
                            {
                                $V_ResRoom['total'] += $V_Folio['amount']+$Service_amount+$tax_amount;
                                $this->map['total_amount'] += $V_Folio['amount']+$Service_amount+$tax_amount;
                            }
                            if(DB::exists('SELECT mice_invoice_detail.id from mice_invoice_detail inner join mice_invoice on mice_invoice.id=mice_invoice_detail.mice_invoice_id where mice_invoice_detail.invoice_id='.$V_Folio['id'].' AND mice_invoice_detail.type=\''.$V_Folio['type'].'\' AND mice_invoice.payment_time is not null'))
                            {
                                // da tao hoa don va thanh toan
                            }
                            else
                            {
                                $this->map['check_close_mice'] = 0;
                            }
                        }
                        $active_department[$key]['item'][$K_ResRoom] = $V_ResRoom;
                        
                        /** danh sach phong **/
                        if($V_ResRoom['room_id']!='')
                            $this->map['list_room_option'] .= '<option value="'.$V_ResRoom['id'].'">'.$V_ResRoom['id'].'-'.$V_ResRoom['room_name'].'('.date('H:i d/m/Y',$V_ResRoom['time_in']).'-'.date('H:i d/m/Y',$V_ResRoom['time_out']).')'.'</option>';
                        /** ENd danh sach phong **/
                    }
                    $active_department[$key]['count_item'] = sizeof($ReservationRoomList);
                }
                //
                $active_department[$key]['icon'] = 'packages/hotel/packages/mice/skins/img/icon_sale.png';
                $active_department[$key]['description'] = Portal::language('access_room_and_housekeeing');
                
            }
            
            /** extra service **/
            if($value['id']=='EXS')
            {
                if(Url::get('id'))
                {
                    $ExtraServiceList = DB::fetch_all(" SELECT
                                                                extra_service_invoice.*,
                                                                NVL(extra_service_invoice.mice_action_module,0) as mice_action_module
                                                            FROM 
                                                                extra_service_invoice  
                                                            WHERE 
                                                                extra_service_invoice.mice_reservation_id=".Url::get('id')."");
                    $ExtraServiceDetail = DB::fetch_all(" SELECT
                                                                extra_service_invoice_detail.*
                                                                ,TO_CHAR(extra_service_invoice_detail.in_date,'DD/MM/YYYY') as in_date
                                                            FROM 
                                                                extra_service_invoice_detail  
                                                                inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id
                                                            WHERE 
                                                                extra_service_invoice.mice_reservation_id=".Url::get('id')."");
                    
                    foreach($ExtraServiceList as $K_Exs=>$V_Exs)
                    {
                        $ExtraServiceList[$K_Exs]['total'] = $V_Exs['total_amount'];
                        $ExtraServiceList[$K_Exs]['status'] = '';
                        $ExtraServiceList[$K_Exs]['link'] = '?page=extra_service_invoice&cmd=edit&id='.$V_Exs['id'].'&type='.$V_Exs['type'];;
                        $this->map['total_amount'] += $V_Exs['total_amount'];
                        $ExtraServiceList[$K_Exs]['service_name'] = '';
                        $ExtraServiceList[$K_Exs]['date'] = '';
                        foreach($ExtraServiceDetail as $K_chil=>$V_chil)
                        {
                            if($V_chil['invoice_id']==$V_Exs['id'])
                            {
                                if($ExtraServiceList[$K_Exs]['service_name']=='')
                                    $ExtraServiceList[$K_Exs]['service_name'] = $V_chil['name'];
                                else
                                    $ExtraServiceList[$K_Exs]['service_name'] .= ','.$V_chil['name'];
                                
                                if($ExtraServiceList[$K_Exs]['date']=='')
                                    $ExtraServiceList[$K_Exs]['date'] = $V_chil['in_date'];
                                else
                                    $ExtraServiceList[$K_Exs]['date'] .= ','.$V_chil['in_date'];
                                
                                if(Date_Time::to_time($V_chil['in_date'])<Date_Time::to_time(date('d/m/Y')))
                                {
                                    $ExtraServiceList[$K_Exs]['status'] = 'CHECKOUT';
                                    if($V_Exs['mice_action_module']==0)
                                        $this->map['check_un_confirm'] = 0;
                                }
                                elseif(Date_Time::to_time($V_chil['in_date'])==Date_Time::to_time(date('d/m/Y')))
                                {
                                    $ExtraServiceList[$K_Exs]['status'] = 'CHECKIN';
                                    if($V_Exs['mice_action_module']==0)
                                        $this->map['check_un_confirm'] = 0;
                                    
                                    $this->map['check_close_mice'] = 0;
                                }
                                else
                                {
                                    $ExtraServiceList[$K_Exs]['status'] = 'BOOKED';
                                    $this->map['check_close_mice'] = 0;
                                }
                                
                                if(DB::exists('SELECT mice_invoice_detail.id from mice_invoice_detail inner join mice_invoice on mice_invoice.id=mice_invoice_detail.mice_invoice_id where mice_invoice_detail.invoice_id='.$V_chil['id'].' AND mice_invoice_detail.type=\'EXTRA_SERVICE\' AND mice_invoice.payment_time is not null'))
                                {
                                    // da tao hoa don va thanh toan
                                }
                                else
                                {
                                    $this->map['check_close_mice'] = 0;
                                }
                            }
                        }
                    }
                    $active_department[$key]['item'] = $ExtraServiceList;
                    $active_department[$key]['count_item'] = sizeof($ExtraServiceList);
                }
                $active_department[$key]['icon'] = 'packages/hotel/packages/mice/skins/img/icon_sale.png';
                $active_department[$key]['description'] = Portal::language('extra_service');
            }
            
            /** ticket **/
            if($value['id']=='TICKET')
            {
                if(Url::get('id'))
                {
                    $ticket_invoice = DB::fetch_all(" SELECT
                                                                ticket_invoice.*
                                                                ,TO_CHAR(ticket_invoice.date_used,'DD/MM/YYYY') as in_date
                                                                ,ticket_reservation.discount_rate as discount_rate_group
                                                                ,ticket_reservation.id as ticket_reservation_id
                                                                ,ticket_reservation.mice_reservation_id
                                                                ,ticket_reservation.mice_action_module
                                                                ,ticket.name as ticket_name
                                                                ,ticket_reservation.ticket_area_id
                                                            FROM 
                                                                ticket_invoice 
                                                                inner join ticket_reservation on ticket_reservation.id=ticket_invoice.ticket_reservation_id
                                                                inner join ticket on ticket.id=ticket_invoice.ticket_id
                                                            WHERE 
                                                                ticket_reservation.mice_reservation_id=".Url::get('id')."");
                    
                    foreach($ticket_invoice as $K_Ticket=>$V_Ticket)
                    {
                        $quantity_ticket = $V_Ticket['quantity'] - System::calculate_number($V_Ticket['discount_quantity']);
                        $amount_ticket = $V_Ticket['price'] - ($V_Ticket['price'] * ( (System::calculate_number($V_Ticket['discount_rate'])+System::calculate_number($V_Ticket['discount_rate_group']))/100 ));
                        
                        $ticket_invoice[$K_Ticket]['total'] = $quantity_ticket*$amount_ticket;
                        $ticket_invoice[$K_Ticket]['status'] = '';
                        $ticket_invoice[$K_Ticket]['link'] = '?page=ticket_invoice_group&cmd=edit&id='.$V_Ticket['ticket_reservation_id'].'&ticket_area_id='.$V_Ticket['ticket_area_id'];
                        $this->map['total_amount'] += $quantity_ticket*$amount_ticket;
                        $ticket_invoice[$K_Ticket]['service_name'] = $V_Ticket['ticket_name'];
                        $ticket_invoice[$K_Ticket]['date'] = $V_Ticket['in_date'];
                        
                        if($V_Ticket['export_ticket']==1)
                        {
                            $ticket_invoice[$K_Ticket]['status'] = 'CHECKOUT';
                            if($V_Ticket['mice_action_module']==0)
                                $this->map['check_un_confirm'] = 0;
                        }
                        else
                        {
                            $ticket_invoice[$K_Ticket]['status'] = 'BOOKED';
                            $this->map['check_close_mice'] = 0;
                        }
                        
                        if(DB::exists('SELECT mice_invoice_detail.id from mice_invoice_detail inner join mice_invoice on mice_invoice.id=mice_invoice_detail.mice_invoice_id where mice_invoice_detail.invoice_id='.$V_Ticket['ticket_reservation_id'].' AND mice_invoice_detail.type=\'TICKET\' AND mice_invoice.payment_time is not null'))
                        {
                            // da tao hoa don va thanh toan
                        }
                        else
                        {
                            $this->map['check_close_mice'] = 0;
                        }
                    }
                    $active_department[$key]['item'] = $ticket_invoice;
                    $active_department[$key]['count_item'] = sizeof($ticket_invoice);
                }
                $active_department[$key]['icon'] = 'packages/hotel/packages/mice/skins/img/icon_sale.png';
                $active_department[$key]['description'] = Portal::language('ticket');
            }
            
            /** restaurant  **/
            if($value['id']=='RES')
            {
                if(Url::get('id'))
                {
                    $BarReservationList = DB::fetch_all(" SELECT 
                                                            bar_reservation.*
                                                            ,bar_reservation_table.table_id
                                                            ,bar_table.bar_area_id
                                                            ,bar_table.name as table_name
                                                            ,bar.name as bar_name
                                                            ,NVL(bar_reservation.mice_action_module,0) as mice_action_module
                                                            ,'?page=touch_bar_restaurant&cmd=edit&id='|| bar_reservation.id || '&table_id=' || bar_reservation_table.table_id || '&bar_area_id=' || bar_table.bar_area_id || '&bar_id=' || bar_reservation.bar_id as link 
                                                        FROM 
                                                            bar_reservation 
                                                            inner join bar_reservation_table on bar_reservation_table.bar_reservation_id=bar_reservation.id 
                                                            inner join bar on bar.id=bar_reservation.bar_id
                                                            inner join bar_table on bar_reservation_table.table_id=bar_table.id 
                                                        WHERE 
                                                            bar_reservation.mice_reservation_id=".Url::get('id')." 
                                                            AND bar_reservation.status!='CANCEL'");
                   // System::DEBUG($BarReservationList);                                        
                    foreach($BarReservationList as $K_Bar=>$V_Bar)
                    {
                        if(($V_Bar['status']=='CHECKIN' OR $V_Bar['status']=='CHECKOUT') AND $V_Bar['mice_action_module']==0)
                        {
                            $this->map['check_un_confirm'] = 0; // khong cho phep huy xac nhan
                        }
                        if($V_Bar['status']!='CHECKOUT')
                        {
                            $this->map['check_close_mice'] = 0;
                        }
                        $this->map['total_amount'] += $V_Bar['total'];
                        
                        if(DB::exists('SELECT mice_invoice_detail.id from mice_invoice_detail inner join mice_invoice on mice_invoice.id=mice_invoice_detail.mice_invoice_id where mice_invoice_detail.invoice_id='.$V_Bar['id'].' AND mice_invoice_detail.type=\'BAR\' AND mice_invoice.payment_time is not null'))
                        {
                            // da tao hoa don va thanh toan
                        }
                        else
                        {
                            $this->map['check_close_mice'] = 0;
                        }
                        $BarReservationList[$K_Bar]['total'] = $V_Bar['total'];
                    }
                    $active_department[$key]['item'] = $BarReservationList;
                    $active_department[$key]['count_item'] = sizeof($BarReservationList);
                }
                
                $active_department[$key]['icon'] = 'packages/hotel/packages/mice/skins/img/icon_res.png';
                $active_department[$key]['description'] = Portal::language('access_food_and_dirnk');
            }
            
            /** vending  **/
            if($value['id']=='VENDING')
            {
                if(Url::get('id'))
                {
                    $VendingReservationList = DB::fetch_all(" SELECT ve_reservation.*,NVL(ve_reservation.mice_action_module,0) as mice_action_module,'?page=automatic_vend&cmd=edit&id='|| ve_reservation.id || '&department_id=' || ve_reservation.department_id || '&department_code=' || ve_reservation.department_code as link FROM ve_reservation WHERE ve_reservation.mice_reservation_id=".Url::get('id')."");
                    foreach($VendingReservationList as $K_Ve=>$V_Ve)
                    {
                        if($V_Ve['time']<=time() AND $V_Ve['mice_action_module']==0)
                        {
                            $this->map['check_un_confirm'] = 0; // khong cho phep huy xac nhan
                        }
                        if($V_Ve['time']>time())
                        {
                            $this->map['check_close_mice'] = 0;
                        }
                        $this->map['total_amount'] += $V_Ve['total'];
                        
                        if(DB::exists('SELECT mice_invoice_detail.id from mice_invoice_detail inner join mice_invoice on mice_invoice.id=mice_invoice_detail.mice_invoice_id where mice_invoice_detail.invoice_id='.$V_Ve['id'].' AND mice_invoice_detail.type=\'VE\' AND mice_invoice.payment_time is not null'))
                        {
                            // da tao hoa don va thanh toan
                        }
                        else
                        {
                            $this->map['check_close_mice'] = 0;
                        }
                    }
                    $active_department[$key]['item'] = $VendingReservationList;
                    $active_department[$key]['count_item'] = sizeof($VendingReservationList);
                }
                $active_department[$key]['icon'] = 'packages/hotel/packages/mice/skins/img/icon_res.png';
                $active_department[$key]['description'] = Portal::language('vending');
            }
            
            /** party **/
            if($value['id']=='BANQUET')
            {
                if(Url::get('id'))
                {
                    $PartyReservationList = DB::fetch_all(" SELECT party_reservation.*,NVL(party_reservation.mice_action_module,0) as mice_action_module,party_type.name as party_name,'?page=banquet_reservation&cmd=' || party_reservation.party_type || '&action=edit&id=' || party_reservation.id as link FROM party_reservation left join party_type on party_type.id=party_reservation.party_type  WHERE party_reservation.mice_reservation_id=".Url::get('id')." AND party_reservation.status!='CANCEL' ");
                    //System::debug($PartyReservationList);
                    foreach($PartyReservationList as $K_Party=>$V_Party)
                    {
                        if(($V_Party['status']=='CHECKIN' OR $V_Party['status']=='CHECKOUT') AND $V_Party['mice_action_module']==0)
                        {
                            $this->map['check_un_confirm'] = 0; // khong cho phep huy xac nhan
                        }
                        if($V_Party['status']!='CHECKOUT')
                        {
                            $this->map['check_close_mice'] = 0;
                        }
                        $this->map['total_amount'] += $V_Party['total'];
                        if(DB::exists('SELECT mice_invoice_detail.id from mice_invoice_detail inner join mice_invoice on mice_invoice.id=mice_invoice_detail.mice_invoice_id where mice_invoice_detail.invoice_id='.$V_Party['id'].' AND mice_invoice_detail.type=\'BANQUET\' AND mice_invoice.payment_time is not null'))
                        {
                            // da tao hoa don va thanh toan
                        }
                        else
                        {
                            $this->map['check_close_mice'] = 0;
                        }
                    }
                    $active_department[$key]['item'] = $PartyReservationList;
                    $active_department[$key]['count_item'] = sizeof($PartyReservationList);
                }
                $active_department[$key]['icon'] = 'packages/hotel/packages/mice/skins/img/icon_party.png';
                $active_department[$key]['description'] = Portal::language('access_party_room');
            }
        }
        //System::debug($active_department);
        $this->map['items'] = $active_department;
        
        $this->map['remain'] = $this->map['total_amount']-($this->map['payment']);
        
        if(System::calculate_number($this->map['deposit'])>0)
        {
            $this->map['check_un_confirm'] = 0;
        }
        if(DB::exists('SELECT id from mice_invoice where mice_reservation_id='.Url::get('id').''))
        {
            // da tao hoa don va thanh toan
            $this->map['check_un_confirm'] = 0;
        }
        
        $this->map['deposit'] = System::display_number($this->map['deposit']);
        $this->map['payment'] = System::display_number($this->map['payment']);
        $this->map['total_amount'] = System::display_number($this->map['total_amount']);
        $this->map['remain'] = System::display_number($this->map['remain']);
        $_REQUEST += $this->map;
        $this->parse_layout('confirm',$this->map);
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
}
?>
