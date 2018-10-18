<?php
class MiceRevenueReportsForm extends Form
{
	function MiceRevenueReportsForm()
	{
		Form::Form('MiceRevenueReportsForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_css('packages/hotel/packages/mice/skins/css/font-awesome-4.5.0/css/font-awesome.min.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
	{
	   require_once 'packages/hotel/packages/mice/modules/MiceReservation/db.php';
	   $this->map = array();
       $cond = '1>0';
       $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
       $_REQUEST['from_date'] = $this->map['from_date'];
       $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
       $_REQUEST['to_date'] = $this->map['to_date'];
       
       $from_date = Date_Time::to_time($this->map['from_date']);
       $to_date = Date_Time::to_time($this->map['to_date'])+86400;
       
       $cond .= ' AND ( 
                        (mice_reservation.create_time>='.$from_date.' and mice_reservation.create_time<'.$to_date.')
                    OR 
                        ( mice_reservation.start_date<=\''.Date_Time::to_orc_date($this->map['from_date']).'\' and mice_reservation.end_date>=\''.Date_Time::to_orc_date($this->map['to_date']).'\' )
                      )
                ';  
       
       $cond .= ' AND mice_reservation.status=1';
       
       // lay cac khoan thanh toan trong MICE
       $payment_mice = DB::fetch_all('
                                    SELECT
                                        payment.id,
                                        payment.amount,
                                        NVL(payment.bank_fee,0) as bank_fee,
                                        payment.exchange_rate,
                                        payment.payment_type_id as payment_type,
                                        mice_invoice.id as mice_invoice_id,
                                        mice_reservation.id as mice_reservation_id
                                    FROM
                                        payment
                                        inner join mice_invoice on TO_CHAR(mice_invoice.id)=payment.bill_id and payment.type=\'BILL_MICE\'
                                        inner join mice_reservation on mice_reservation.id=mice_invoice.mice_reservation_id
                                    WHERE
                                        '.$cond.'
                                    ');
       $revenue_payment_mice = array();
       foreach($payment_mice as $k_pay=>$v_pay)
       {
            if(!isset($revenue_payment_mice[$v_pay['mice_reservation_id']]))
            {
                $revenue_payment_mice[$v_pay['mice_reservation_id']]['total']=0;
            }
            if($v_pay['payment_type']=='REFUND')
            {
                $revenue_payment_mice[$v_pay['mice_reservation_id']]['total'] -= ($v_pay['amount']+$v_pay['bank_fee']) * $v_pay['exchange_rate'];
            }
            else
            {
                $revenue_payment_mice[$v_pay['mice_reservation_id']]['total'] += ($v_pay['amount']+$v_pay['bank_fee']) * $v_pay['exchange_rate'];
            }
       }
       
       // lay nhung khoan thanh toan folio trong phong
       $payment_folio_room = DB::fetch_all("
                                            SELECT
                                                payment.id,
                                                payment.amount,
                                                NVL(payment.bank_fee,0) as bank_fee,
                                                payment.exchange_rate,
                                                payment.payment_type_id as payment_type,
                                                reservation.mice_reservation_id
                                            FROM
                                                payment
                                                inner join reservation on reservation.id=payment.reservation_id and payment.type='RESERVATION' and payment.folio_id is not null
                                                inner join mice_reservation on mice_reservation.id=reservation.mice_reservation_id
                                            WHERE
                                                ".$cond."
                                            ");
       //System::debug($payment_folio_room);
       foreach($payment_folio_room as $k_pay_folio=>$v_pay_folio)
       {
            if(!isset($revenue_payment_mice[$v_pay_folio['mice_reservation_id']]))
            {
                $revenue_payment_mice[$v_pay_folio['mice_reservation_id']]['total']=0;
            }
            if($v_pay_folio['payment_type']=='REFUND')
            {
                $revenue_payment_mice[$v_pay_folio['mice_reservation_id']]['total'] -= ($v_pay_folio['amount']+$v_pay_folio['bank_fee']) * $v_pay_folio['exchange_rate'];
            }
            else
            {
                $revenue_payment_mice[$v_pay_folio['mice_reservation_id']]['total'] += ($v_pay_folio['amount']+$v_pay_folio['bank_fee']) * $v_pay_folio['exchange_rate'];
            }
       }
       
       // lay cac khoan thanh toan trong ban ve
       $payment_ticket = DB::fetch_all("
                                        SELECT
                                            payment.id,
                                            payment.amount,
                                            NVL(payment.bank_fee,0) as bank_fee,
                                            payment.exchange_rate,
                                            payment.payment_type_id as payment_type,
                                            ticket_reservation.mice_reservation_id
                                        FROM
                                            payment
                                            inner join ticket_reservation on TO_CHAR(ticket_reservation.id)=payment.bill_id AND payment.type='TICKET'
                                            inner join mice_reservation on mice_reservation.id=ticket_reservation.mice_reservation_id
                                        WHERE
                                            ".$cond."
                                        ");
       foreach($payment_ticket as $k_pay_ticket=>$v_pay_ticket)
       {
            if(!isset($revenue_payment_mice[$v_pay_ticket['mice_reservation_id']]))
            {
                $revenue_payment_mice[$v_pay_ticket['mice_reservation_id']]['total']=0;
            }
            if($v_pay_ticket['payment_type']=='REFUND')
            {
                $revenue_payment_mice[$v_pay_ticket['mice_reservation_id']]['total'] -= ($v_pay_ticket['amount']+$v_pay_ticket['bank_fee']) * $v_pay_ticket['exchange_rate'];
            }
            else
            {
                $revenue_payment_mice[$v_pay_ticket['mice_reservation_id']]['total'] += ($v_pay_ticket['amount']+$v_pay_ticket['bank_fee']) * $v_pay_ticket['exchange_rate'];
            }
       }
       
       // lay cac khoan thanh toan tien nha hang
       $payment_res = DB::fetch_all("
                                        SELECT
                                            payment.id,
                                            payment.amount,
                                            NVL(payment.bank_fee,0) as bank_fee,
                                            payment.exchange_rate,
                                            payment.payment_type_id as payment_type,
                                            bar_reservation.mice_reservation_id
                                        FROM
                                            payment
                                            inner join bar_reservation on TO_CHAR(bar_reservation.id)=payment.bill_id AND payment.type='BAR'
                                            inner join mice_reservation on mice_reservation.id=bar_reservation.mice_reservation_id
                                        WHERE
                                            ".$cond."
                                        ");
       foreach($payment_res as $k_pay_res=>$v_pay_res)
       {
            if(!isset($revenue_payment_mice[$v_pay_res['mice_reservation_id']]))
            {
                $revenue_payment_mice[$v_pay_res['mice_reservation_id']]['total']=0;
            }
            if($v_pay_res['payment_type']=='REFUND')
            {
                $revenue_payment_mice[$v_pay_res['mice_reservation_id']]['total'] -= ($v_pay_res['amount']+$v_pay_res['bank_fee']) * $v_pay_res['exchange_rate'];
            }
            else
            {
                $revenue_payment_mice[$v_pay_res['mice_reservation_id']]['total'] += ($v_pay_res['amount']+$v_pay_res['bank_fee']) * $v_pay_res['exchange_rate'];
            }
       }
       
       
       // lay cac khoan dat coc
       $depo_mice = DB::fetch_all("SELECT 
                                    mice_reservation.id,
                                    sum(payment.amount) as total
                                FROM 
                                    payment 
                                    inner join mice_reservation on TO_CHAR(mice_reservation.id)=payment.bill_id AND payment.type='MICE'
                                WHERE 
                                    ".$cond."
                                GROUP BY
                                    mice_reservation.id
                                    ");
       
       $mice_reservation = DB::fetch_all("SELECT 
                                                mice_reservation.id,
                                                0 as room_charge,
                                                0 as service_charge_room,
                                                0 as bar,
                                                0 as hill_coffee,
                                                0 as karaoke,
                                                0 as event,
                                                0 as ticket,
                                                0 as service_other,
                                                0 as extra_amount,
                                                0 as vat,
                                                0 as extra_vat,
                                                0 as total,
                                                0 as deposit,
                                                0 as total_payment,
                                                0 as payment,
                                                0 as debit,
                                                0 as remain_traveller
                                        FROM 
                                            mice_reservation 
                                        where 
                                            ".$cond." 
                                        ORDER BY mice_reservation.id ");
       
       $this->map['items'] = array();
       foreach($mice_reservation as $key=>$value)
       {
            // lay phi viet check trong cac hoa don
            $mice_reservation[$key]['extra_amount'] = DB::fetch('select sum(extra_amount) as extra from mice_invoice where mice_invoice.mice_reservation_id='.$value['id'].'','extra');
            $mice_reservation[$key]['extra_vat'] = DB::fetch('select sum(extra_vat) as extra from mice_invoice where mice_invoice.mice_reservation_id='.$value['id'].'','extra');
            $mice_reservation[$key]['total'] = DB::fetch('select sum(extra_vat) as extra from mice_invoice where mice_invoice.mice_reservation_id='.$value['id'].'','extra');
            // bien xac sinh xem co dich vu su dung trong mice hay khong
            $value['view_report'] = 0;
            
            // tinh tien phong
            $ReservationRoomList = DB::fetch_all(" SELECT 
                                                        reservation_room.id
                                                    FROM 
                                                        reservation_room 
                                                        INNER JOIN reservation ON reservation_room.reservation_id=reservation.id
                                                    WHERE 
                                                        reservation.mice_reservation_id=".$value['id']." 
                                                        AND reservation_room.status!='CANCEL' 
                                                        --AND reservation_room.status!='BOOKED' 
                                                    ");
            
            foreach($ReservationRoomList as $K_ResRoom=>$V_ResRoom)
            {
                $get_invoice = MiceReservationDB::get_total_room($V_ResRoom['id']);
                //System::debug($get_invoice);
                foreach($get_invoice as $F_key=>$F_value)
                {
                    //if(Date_Time::to_time($F_value['date'])>=$from_date AND Date_Time::to_time($F_value['date'])<$to_date)
                    {
                        $value['view_report'] = 1;
                        
                        if($F_value['type']=='EXTRA_SERVICE')
                        {
                            $service_code = DB::fetch('
                                                        select extra_service.code 
                                                        from 
                                                             extra_service
                                                             inner join extra_service_invoice_detail on extra_service.id=extra_service_invoice_detail.service_id
                                                        where
                                                            extra_service_invoice_detail.id='.$F_value['id'].'
                                                        ','code');
                            if($service_code=='EXTRA_BED' OR $service_code=='BABY_COT' OR $service_code=='VFD' OR $service_code=='EARLY_CHECKIN' OR $service_code=='LATE_CHECKOUT' OR $service_code=='LATE_CHECKIN' OR $service_code=='EXTRA_PERSON')
                            {
                                $mice_reservation[$key]['room_charge'] += round(($F_value['amount'] * (1+$F_value['service_rate']/100)),0);
                                $mice_reservation[$key]['vat'] += ((round(($F_value['amount'] * (1+$F_value['service_rate']/100)),0))*$F_value['tax_rate'])/100;
                            }
                            else
                            {
                                $mice_reservation[$key]['service_other'] += round(($F_value['amount'] * (1+$F_value['service_rate']/100)),0);
                                $mice_reservation[$key]['vat'] += ((round(($F_value['amount'] * (1+$F_value['service_rate']/100)),0))*$F_value['tax_rate'])/100;
                            }
                            $mice_reservation[$key]['total'] += round(($F_value['amount'] * ( (1+$F_value['service_rate']/100)*(1+$F_value['tax_rate']/100) )),0);
                        }
                        elseif($F_value['type']=='MINIBAR' OR $F_value['type']=='LAUNDRY' OR $F_value['type']=='EQUIPMENT')
                        {
                            $mice_reservation[$key]['service_charge_room'] += round(($F_value['amount'] * (1+$F_value['service_rate']/100)),0);
                            $mice_reservation[$key]['vat'] += ((round(($F_value['amount'] * (1+$F_value['service_rate']/100)),0))*$F_value['tax_rate'])/100;
                            $mice_reservation[$key]['total'] += round(($F_value['amount'] * ( (1+$F_value['service_rate']/100)*(1+$F_value['tax_rate']/100) )),0);
                        }
                        elseif($F_value['type']=='TICKET')
                        {
                            $mice_reservation[$key]['ticket'] += round(($F_value['amount'] * (1+$F_value['service_rate']/100)),0);
                            $mice_reservation[$key]['vat'] += ((round(($F_value['amount'] * (1+$F_value['service_rate']/100)),0))*$F_value['tax_rate'])/100;
                            $mice_reservation[$key]['total'] += round(($F_value['amount'] * ( (1+$F_value['service_rate']/100)*(1+$F_value['tax_rate']/100) )),0);
                        }
                        elseif($F_value['type']=='BAR')
                        {
                            $bar_code = DB::fetch('select bar.code from bar inner join bar_reservation on bar_reservation.bar_id=bar.id where bar_reservation.id='.$F_value['id'],'code');
                            if($bar_code=='SUKIEN')
                            {
                                $mice_reservation[$key]['event'] += round(($F_value['amount'] * (1+$F_value['service_rate']/100)),0);
                            }
                            elseif($bar_code=='KARAOKE')
                            {
                                $mice_reservation[$key]['karaoke'] += round(($F_value['amount'] * (1+$F_value['service_rate']/100)),0);
                            }
                            elseif($bar_code=='HILL')
                            {
                                $mice_reservation[$key]['hill_coffee'] += round(($F_value['amount'] * (1+$F_value['service_rate']/100)),0);
                            }
                            else
                            {
                                $mice_reservation[$key]['bar'] += round(($F_value['amount'] * (1+$F_value['service_rate']/100)),0);
                            }
                            $mice_reservation[$key]['vat'] += ((round(($F_value['amount'] * (1+$F_value['service_rate']/100)),0))*$F_value['tax_rate'])/100;
                            $mice_reservation[$key]['total'] += round(($F_value['amount'] * ( (1+$F_value['service_rate']/100)*(1+$F_value['tax_rate']/100) )),0);
                        }
                        elseif($F_value['type']=='DISCOUNT')
                        {
                            $mice_reservation[$key]['room_charge'] -= round(($F_value['amount'] * (1+$F_value['service_rate']/100)),0);
                            $mice_reservation[$key]['vat'] -= ((round(($F_value['amount'] * (1+$F_value['service_rate']/100)),0))*$F_value['tax_rate'])/100;
                            $mice_reservation[$key]['total'] -= round(($F_value['amount'] * ( (1+$F_value['service_rate']/100)*(1+$F_value['tax_rate']/100) )),0);
                        }
                        elseif($F_value['type']=='DEPOSIT')
                        {
                            $mice_reservation[$key]['deposit'] += round(($F_value['amount'] * ( (1+$F_value['service_rate']/100)*(1+$F_value['tax_rate']/100) )),0);
                        }
                        else
                        {
                            $mice_reservation[$key]['room_charge'] += round(($F_value['amount'] * (1+$F_value['service_rate']/100)),0);
                            $mice_reservation[$key]['vat'] += ((round(($F_value['amount'] * (1+$F_value['service_rate']/100)),0))*$F_value['tax_rate'])/100;
                            $mice_reservation[$key]['total'] += round(($F_value['amount'] * ( (1+$F_value['service_rate']/100)*(1+$F_value['tax_rate']/100) )),0);
                        }
                    }
                }
            }
            
            // dich vu mo rong
            $ExtraServiceList = DB::fetch_all('
                                				select 
                                					extra_service_invoice_detail.id,
                                					(extra_service_invoice_detail.quantity*extra_service_invoice_detail.price) as amount,
                                                    extra_service_invoice.net_price,
                                					DECODE(extra_service_invoice.tax_rate,\'\',0,extra_service_invoice.tax_rate) as tax_rate,
                                					DECODE(extra_service_invoice.service_rate,\'\',0,extra_service_invoice.service_rate) as service_rate,
                                					to_char(extra_service_invoice_detail.in_date,\'DD/MM/YYYY\') as in_date,
                                                    extra_service.code
                                				from 
                                					extra_service_invoice_detail
                                                    inner join extra_service on extra_service.id=extra_service_invoice_detail.service_id
                                					inner join extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
                                				where
                                                    extra_service_invoice.mice_reservation_id='.$value['id'].'
                                			');
            foreach($ExtraServiceList as $s_key=>$s_value)
            {
                //if(Date_Time::to_time($s_value['in_date'])>=$from_date AND Date_Time::to_time($s_value['in_date'])<$to_date)
                {
                    $value['view_report'] = 1;
                    
                    if($s_value['net_price'] != 1)
                    {
                        $s_value['amount'] = $s_value['amount']* (1+$s_value['service_rate']/100);
                        $s_value['vat'] = ($s_value['amount']*$s_value['tax_rate'])/100;
                    }
                    else
                    {
                        $s_value['amount'] = $s_value['amount']/( 1+$s_value['tax_rate']/100 );
                        $s_value['vat'] = ($s_value['amount']*$s_value['tax_rate'])/100;
                    }
                    $s_value['amount'] = round($s_value['amount'],0);
                    
                    $service_code = $s_value['code'];
                    if($service_code=='EXTRA_BED' OR $service_code=='BABY_COT' OR $service_code=='VFD' OR $service_code=='EARLY_CHECKIN' OR $service_code=='LATE_CHECKOUT' OR $service_code=='LATE_CHECKIN' OR $service_code=='EXTRA_PERSON')
                    {
                        $mice_reservation[$key]['room_charge'] += $s_value['amount'];
                    }
                    else
                    {
                        $mice_reservation[$key]['service_other'] += $s_value['amount'];
                    }
                    $mice_reservation[$key]['vat'] += $s_value['vat'];
                    $mice_reservation[$key]['total'] += $s_value['amount']+$s_value['vat'];
                }
            }
            
            // tinh tien ban ve
            
            $ticket_invoice = DB::fetch_all(" SELECT
                                                    ticket_invoice.*
                                                    ,TO_CHAR(ticket_invoice.date_used,'DD/MM/YYYY') as in_date
                                                    ,ticket_reservation.discount_rate as discount_rate_group
                                                FROM 
                                                    ticket_invoice 
                                                    inner join ticket_reservation on ticket_reservation.id=ticket_invoice.ticket_reservation_id
                                                    inner join ticket on ticket.id=ticket_invoice.ticket_id
                                                WHERE 
                                                    ticket_reservation.mice_reservation_id=".$value['id']."");
            
            foreach($ticket_invoice as $K_Ticket=>$V_Ticket)
            {
                //if($V_Ticket['export_ticket']==1) //AND Date_Time::to_time($V_Ticket['in_date'])>=$from_date AND Date_Time::to_time($V_Ticket['in_date'])<$to_date)
                {
                    $value['view_report'] = 1;
                    
                    $quantity_ticket = $V_Ticket['quantity'] - System::calculate_number($V_Ticket['discount_quantity']);
                    $amount_ticket = $V_Ticket['price'] - ($V_Ticket['price'] * ( (System::calculate_number($V_Ticket['discount_rate'])+System::calculate_number($V_Ticket['discount_rate_group']))/100 ));
                    
                    $mice_reservation[$key]['ticket'] += $quantity_ticket*$amount_ticket;
                    $mice_reservation[$key]['total'] += $quantity_ticket*$amount_ticket;
                }
            }
            
            // tinh tien nha hang
            $bar_resrs = DB::fetch_all('
                        				select 
                        					bar_reservation.id,
                                            bar_reservation.total as total_amount,
                                            bar.code as bar_code,
                                            bar_reservation.time_out,
                                            bar_reservation.extra_vat,
                                            bar_reservation.tax_rate
                        				from 
                        					bar_reservation 
                                            inner join bar ON bar_reservation.bar_id = bar.id
                        				where 
                        					 bar_reservation.status!=\'CANCEL\'
                                             --and bar_reservation.status!=\'BOOKED\'
                                             and bar_reservation.mice_reservation_id = '.$value['id'].'
                        			     ');             
			foreach($bar_resrs as $bk=>$reser)
            {
                //if($reser['time_out']>=$from_date AND $reser['time_out']<$to_date)
                {
                    $value['view_report'] = 1;
                    $bar_code = $reser['bar_code'];
                    $reser['total_amount'] = $reser['total_amount']/(1+$reser['tax_rate']/100);
                    $reser['vat'] = ($reser['total_amount']*$reser['tax_rate'])/100;
                    if($bar_code=='SUKIEN')
                    {
                        $mice_reservation[$key]['event'] += $reser['total_amount']+$reser['extra_vat'];
                    }
                    elseif($bar_code=='KARAOKE')
                    {
                        $mice_reservation[$key]['karaoke'] += $reser['total_amount']+$reser['extra_vat'];
                    }
                    elseif($bar_code=='HILL')
                    {
                        $mice_reservation[$key]['hill_coffee'] += $reser['total_amount']+$reser['extra_vat'];
                    }
                    else
                    {
                        $mice_reservation[$key]['bar'] += $reser['total_amount']+$reser['extra_vat'];
                    }
                    $mice_reservation[$key]['vat'] += $reser['vat'];
                    $mice_reservation[$key]['total'] += $reser['total_amount']+$reser['vat']+$reser['extra_vat'];
                }
			}
            
            // neu bo view_report
            if($value['view_report']==0)
            {
                unset($mice_reservation[$key]);
            }
            else
            {
                if(isset($depo_mice[$value['id']]))
                {
                    $mice_reservation[$key]['deposit'] += $depo_mice[$value['id']]['total'];
                }
                
                $total_payment = $mice_reservation[$key]['total']-$mice_reservation[$key]['deposit'];
                $mice_reservation[$key]['total_payment'] += $total_payment;
                $total_remain = $total_payment;
                if(isset($revenue_payment_mice[$value['id']]))
                {
                    $mice_reservation[$key]['payment'] += $revenue_payment_mice[$value['id']]['total'];
                    $total_remain -= $revenue_payment_mice[$value['id']]['total'];
                }
                //echo $value['id'].'-'.$total_remain;
                if($total_remain<0)
                {
                    $mice_reservation[$key]['remain_traveller']+= $total_remain*(-1);
                }
                else
                {
                    $mice_reservation[$key]['debit']+= $total_remain;
                }
            }
       }
       
       $this->map['grand_total'] = array(
                                        'room_charge'=>0,
                                        'service_charge_room'=>0,
                                        'bar'=>0,
                                        'hill_coffee'=>0,
                                        'karaoke'=>0,
                                        'event'=>0,
                                        'ticket'=>0,
                                        'service_other'=>0,
                                        'vat'=>0,
                                        'extra_amount'=>0,
                                        'extra_vat'=>0,
                                        'total'=>0,
                                        'deposit'=>0,
                                        'total_payment'=>0,
                                        'payment'=>0,
                                        'debit'=>0,
                                        'remain_traveller'=>0
                                        );
       foreach($mice_reservation as $key_mice=>$value_mice)
       {
            $this->map['grand_total']['room_charge'] += ($value_mice['room_charge']*1)>0?($value_mice['room_charge']*1):(($value_mice['room_charge']*-1)>0?($value_mice['room_charge']*-1):0);
            $this->map['grand_total']['service_charge_room'] += ($value_mice['service_charge_room']*1)>0?($value_mice['service_charge_room']*1):(($value_mice['service_charge_room']*-1)>0?($value_mice['service_charge_room']*-1):0);
            $this->map['grand_total']['bar'] += ($value_mice['bar']*1)>0?($value_mice['bar']*1):(($value_mice['bar']*-1)>0?($value_mice['bar']*-1):0);
            $this->map['grand_total']['hill_coffee'] += ($value_mice['hill_coffee']*1)>0?($value_mice['hill_coffee']*1):(($value_mice['hill_coffee']*-1)>0?($value_mice['hill_coffee']*-1):0);
            $this->map['grand_total']['karaoke'] += ($value_mice['karaoke']*1)>0?($value_mice['karaoke']*1):(($value_mice['karaoke']*-1)>0?($value_mice['karaoke']*-1):0);
            $this->map['grand_total']['event'] += ($value_mice['event']*1)>0?($value_mice['event']*1):(($value_mice['event']*-1)>0?($value_mice['event']*-1):0);
            $this->map['grand_total']['ticket'] += ($value_mice['ticket']*1)>0?($value_mice['ticket']*1):(($value_mice['ticket']*-1)>0?($value_mice['ticket']*-1):0);
            $this->map['grand_total']['service_other'] += ($value_mice['service_other']*1)>0?($value_mice['service_other']*1):(($value_mice['service_other']*-1)>0?($value_mice['service_other']*-1):0);
            $this->map['grand_total']['vat'] += ($value_mice['vat']*1)>0?($value_mice['vat']*1):(($value_mice['vat']*-1)>0?($value_mice['vat']*-1):0);
            $this->map['grand_total']['extra_amount'] += ($value_mice['extra_amount']*1)>0?($value_mice['extra_amount']*1):(($value_mice['extra_amount']*-1)>0?($value_mice['extra_amount']*-1):0);
            $this->map['grand_total']['extra_vat'] += ($value_mice['extra_vat']*1)>0?($value_mice['extra_vat']*1):(($value_mice['extra_vat']*-1)>0?($value_mice['extra_vat']*-1):0);
            $this->map['grand_total']['total'] += ($value_mice['total']*1)>0?($value_mice['total']*1):(($value_mice['total']*-1)>0?($value_mice['total']*-1):0);
            $this->map['grand_total']['deposit'] += ($value_mice['deposit']*1)>0?($value_mice['deposit']*1):(($value_mice['deposit']*-1)>0?($value_mice['deposit']*-1):0);
            $this->map['grand_total']['total_payment'] += ($value_mice['total_payment']*1)>0?($value_mice['total_payment']*1):(($value_mice['total_payment']*-1)>0?($value_mice['total_payment']*-1):0);
            $this->map['grand_total']['payment'] += ($value_mice['payment']*1)>0?($value_mice['payment']*1):(($value_mice['payment']*-1)>0?($value_mice['payment']*-1):0);
            $this->map['grand_total']['debit'] += ($value_mice['debit']*1)>0?($value_mice['debit']*1):(($value_mice['debit']*-1)>0?($value_mice['debit']*-1):0);
            $this->map['grand_total']['remain_traveller'] += ($value_mice['remain_traveller']*1)>0?($value_mice['remain_traveller']*1):(($value_mice['remain_traveller']*-1)>0?($value_mice['remain_traveller']*-1):0);
       }
       $this->map['items'] = $mice_reservation;
       //System::debug($mice_reservation);
       $this->parse_layout('report',$this->map);
	}
}
?>