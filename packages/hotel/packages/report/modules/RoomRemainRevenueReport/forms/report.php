<?php
class RoomRemainRevenueReportForm extends Form
{
	function RoomRemainRevenueReportForm()
	{
		Form::Form('RoomRemainRevenueReportForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
                $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
        $this->add('from_date',new TextType(true,'miss_from_date',0,255));
        $this->add('to_date',new TextType(true,'miss_to_date',0,255));
	}
    
    function cond_reservation_room($reservation_rooms,$table)
    {
        $cond_rr_id = '';
        foreach($reservation_rooms as $key => $value)
        {
            $cond_rr_id .= " ".$table.".reservation_room_id = ".$value['reservation_room_id']." or";
        }
        return  "(".($cond_rr_id?substr($cond_rr_id,0,strlen($cond_rr_id)-2):"1=1").")";
    }
    
    function cond_reservation($reservation_rooms,$table)
    {
        $cond_r_id = '';
        foreach($reservation_rooms as $key => $value)
        {
            $cond_r_id .= " ".$table.".reservation_id = ".$value['recode']." or";
        }
        return "(".($cond_r_id?substr($cond_r_id,0,strlen($cond_r_id)-2):"1=1").")";
    }
    
    function get_other($cond_rr_id,$cond_r_id)
	{
         $sql=('SELECT 
										(traveller_folio.type || \'_\' || traveller_folio.invoice_id) as id
										,
                                         case
                                         when traveller_folio.type = \'DISCOUNT\'
                                         then 
                                            SUM(traveller_folio.amount)  
                                         else
                                            SUM(traveller_folio.amount*(1+NVL(traveller_folio.service_rate,0)/100)*(1+NVL(traveller_folio.tax_rate,0)/100)) 
                                         end as amount
										,SUM(traveller_folio.percent) as percent 
									FROM traveller_folio 
										inner join folio ON folio.id = traveller_folio.folio_id
                                        --inner join payment on folio.id = payment.folio_id --(1)
									WHERE 1>0 
                                    AND ('.$cond_rr_id.' OR '.$cond_r_id.')
                                    AND (folio.payment_time is not null or folio.total=0) --(2) khi nao anh manh up cai thanh toan het thi bo cmt (2) và cmt (1)
                                    GROUP BY (traveller_folio.type,traveller_folio.invoice_id)');// AND add_payment=1
		$folio_other = DB::fetch_all($sql);
        return $folio_other;
    }
    
    function get_all($from, $to)
	{
        $items = array();
        //room charge
         //KimTan
        $cond_all = '';
        if(Url::get('portal_id') != 'ALL')
        {
            $cond_all=Url::get('portal_id')?"and room.portal_id ='".Url::get('portal_id')."'":'';
        }
        //end KimTan
        $sql = '
				SELECT 
					room_status.id
					,room_status.change_price
					,room.name as room_name
					,room_status.in_date
					,room_status.id as room_status_id
					,reservation_room.tax_rate
					,reservation_room.service_rate
                    ,reservation_room.reduce_balance
                    ,reservation_room.net_price
					,reservation_room.id as rr_id
					,TO_CHAR(room_status.in_date,\'DD/MM/YYYY\') as convert_date
				FROM 
					room_status
					inner join room on room.id=room_status.room_id
                    inner join room_level on room.room_level_id = room_level.id
					INNER JOIN reservation_room ON reservation_room.id = room_status.reservation_room_id
				WHERE date_to_unix(room_status.in_date)<'.($to+86400).' 
                    AND date_to_unix(room_status.in_date)>='.($from).' 
                    and (reservation_room.status = \'CHECKIN\') 
                    and room_level.is_virtual != 1 
                    '.$cond_all.'
                    AND room_status.change_price > 0
				ORDER BY 
					room.name,room_status.in_date';
        $room_statuses = DB::fetch_all($sql);//'.((USE_NIGHT_AUDIT==1)?'AND (room_status.closed_time > 0 OR reservation_room.arrival_time = reservation_room.departure_time)':'').'
        
        foreach($room_statuses as $k=>$v)
        {
            if($v['net_price']==0 )
            {
                $v['change_price'] = round($v['change_price']*(1+$v['service_rate']*0.01)*(1+$v['tax_rate']*0.01),2);	
            }
            
            $tt = ($v['reduce_balance']?(100 - $v['reduce_balance'])*$v['change_price']/100:$v['change_price']);
            if($v['reduce_balance']>0 && $v['reduce_balance']!='')
            {
                $room_statuses[$k]['note'] = '( Discounted '.$v['reduce_balance'].'%)';
            }
            else
            {
				$room_statuses[$k]['note'] = '';
            }
            $room_statuses[$k]['change_price'] = $tt;
            $percent = 100;
            $status = 0;
            $amount = $room_statuses[$k]['change_price'];
            if($amount==0) continue;
            $items['ROOM_'.$v['room_status_id']]['net_amount'] = System::display_number($amount);
            $items['ROOM_'.$v['room_status_id']]['id'] = $v['room_status_id'];
            $items['ROOM_'.$v['room_status_id']]['type'] = 'ROOM';
            $items['ROOM_'.$v['room_status_id']]['service_rate'] = $v['service_rate'];
            $items['ROOM_'.$v['room_status_id']]['tax_rate'] = $v['tax_rate'];
            $items['ROOM_'.$v['room_status_id']]['rr_id'] = $v['rr_id'];
            $items['ROOM_'.$v['room_status_id']]['status'] = $status;
            $items['ROOM_'.$v['room_status_id']]['date'] = $v['convert_date'];
            $items['ROOM_'.$v['room_status_id']]['percent'] = $percent;
            $items['ROOM_'.$v['room_status_id']]['amount'] = System::display_number($amount);
            $items['ROOM_'.$v['room_status_id']]['description'] =  $v['room_name'].' '.Portal::language('room_charge').' '.$room_statuses[$k]['note'];
        }
        //extra_service
        $extra_services = DB::fetch_all('
					select 
						extra_service_invoice_detail.*,
						(extra_service_invoice_detail.quantity*extra_service_invoice_detail.price) as amount,
						0 as service_charge_amount,
						0 as tax_amount,
                        extra_service_invoice.net_price,
						DECODE(extra_service_invoice.tax_rate,\'\',0,extra_service_invoice.tax_rate) as tax_rate,
						DECODE(extra_service_invoice.service_rate,\'\',0,extra_service_invoice.service_rate) as service_rate,
						to_char(extra_service_invoice_detail.in_date,\'DD/MM\') as in_date,
                        reservation_room.id as reservation_room_id,
                        room.name as room_name,
                        extra_service.code
					from 
						extra_service_invoice_detail
                        inner join extra_service on extra_service.id = extra_service_invoice_detail.service_id
						inner join extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
                        inner join reservation_room on  reservation_room.id = extra_service_invoice.reservation_room_id
                        inner join room on room.id = reservation_room.room_id
                        inner join room_level on room.room_level_id = room_level.id
					where date_to_unix(extra_service_invoice_detail.in_date)<'.($to+86400).' 
                        AND date_to_unix(extra_service_invoice_detail.in_date)>='.($from).' 
                        and (reservation_room.status = \'CHECKIN\') 
                        and room_level.is_virtual != 1 
                        '.$cond_all.'
						AND extra_service_invoice_detail.used = 1
						
				');
        if(!empty($extra_services))
        {	
            foreach($extra_services as $s_key=>$s_value){
                if($s_value['net_price']==0)
                {
				    $s_value['amount'] = round($s_value['amount']*(1+$s_value['service_rate']*0.01)*(1+$s_value['tax_rate']*0.01),2);	
				}
				$percent = 100;$status = 0;
				$amount = $s_value['amount'];
                if($amount==0) continue;
                $items['EXTRA_SERVICE_'.$s_key]['net_amount'] = System::display_number($amount);
				$items['EXTRA_SERVICE_'.$s_key]['id'] = $s_key;
				$items['EXTRA_SERVICE_'.$s_key]['type'] = 'EXTRA_SERVICE';
				$items['EXTRA_SERVICE_'.$s_key]['service_rate'] = $s_value['service_rate'];
				$items['EXTRA_SERVICE_'.$s_key]['tax_rate'] = $s_value['tax_rate'];
				$items['EXTRA_SERVICE_'.$s_key]['rr_id'] = $s_value['reservation_room_id'];
				$items['EXTRA_SERVICE_'.$s_key]['date'] = $s_value['in_date'];
				$items['EXTRA_SERVICE_'.$s_key]['percent'] = $percent;
				$items['EXTRA_SERVICE_'.$s_key]['status'] = $status;
				$items['EXTRA_SERVICE_'.$s_key]['amount'] = System::display_number($amount);
				$items['EXTRA_SERVICE_'.$s_key]['description'] = $s_value['room_name'].' '.$s_value['name'];
                //them de chia EXTRA_SERVICE ra
                $items['EXTRA_SERVICE_'.$s_key]['code'] = $s_value['code'];
                
            }
        }
        
        //housekeeping
        $sql_l = '
						SELECT 
							housekeeping_invoice.*,
                            reservation_room.id as reservation_room_id,
                            room.name as room_name
						FROM 
							housekeeping_invoice
                            inner join reservation_room on  reservation_room.id = housekeeping_invoice.reservation_room_id
                            inner join room on room.id = reservation_room.room_id
                            inner join room_level on room.room_level_id = room_level.id
						WHERE housekeeping_invoice.time < '.($to+86400).' 
                            AND housekeeping_invoice.time >= '.($from).' 
                            and (reservation_room.status = \'CHECKIN\') 
                            and room_level.is_virtual != 1 
                            '.$cond_all.' 
							AND housekeeping_invoice.type=\'LAUNDRY\'
					';
        $sql_m = '
						SELECT 
							housekeeping_invoice.*,
                            reservation_room.id as reservation_room_id,
                            room.name as room_name
						FROM 
							housekeeping_invoice
							inner join minibar on housekeeping_invoice.minibar_id = minibar.id
                            inner join reservation_room on  reservation_room.id = housekeeping_invoice.reservation_room_id
                            inner join room on room.id = reservation_room.room_id
                            inner join room_level on room.room_level_id = room_level.id
						WHERE housekeeping_invoice.time < '.($to+86400).' 
                            AND housekeeping_invoice.time >= '.($from).' 
                            and (reservation_room.status = \'CHECKIN\') 
                            and room_level.is_virtual != 1 
                            '.$cond_all.' 
                            AND type=\'MINIBAR\' 
					'; 
        $sql_compensated_item = '
						SELECT 
							housekeeping_invoice.*,
                            reservation_room.id as reservation_room_id,
                            room.name as room_name
						FROM 
							housekeeping_invoice
                            inner join reservation_room on  reservation_room.id = housekeeping_invoice.reservation_room_id
                            inner join room on room.id = reservation_room.room_id
                            inner join room_level on room.room_level_id = room_level.id
						WHERE housekeeping_invoice.time < '.($to+86400).' 
                            AND housekeeping_invoice.time >= '.($from).' 
                            and (reservation_room.status = \'CHECKIN\') 
                            and room_level.is_virtual != 1 
                            '.$cond_all.' 
                            AND housekeeping_invoice.type=\'EQUIP\' 
					';
        
        //-----------------------------------------minibar------------------------------------------------------------
        if($minibars = DB::fetch_all($sql_m)){
            foreach($minibars as $k=>$minibar){				
                $percent = 100;$status = 0;
				$amount = $minibar['total'];
                if($amount==0) continue;
				$items['MINIBAR_'.$k]['net_amount'] = System::display_number($amount);
				$items['MINIBAR_'.$k]['id'] = $k;
				$items['MINIBAR_'.$k]['type'] = 'MINIBAR';
				$items['MINIBAR_'.$k]['date'] = date('d/m',$minibar['time']);
				$items['MINIBAR_'.$k]['service_rate'] = $minibar['fee_rate'];
				$items['MINIBAR_'.$k]['tax_rate'] = $minibar['tax_rate'];
				$items['MINIBAR_'.$k]['percent'] = $percent;
				$items['MINIBAR_'.$k]['status'] = $status;
				$items['MINIBAR_'.$k]['rr_id'] = $minibar['reservation_room_id'];
				$items['MINIBAR_'.$k]['amount'] = System::display_number($amount);
				$items['MINIBAR_'.$k]['description'] =  $minibar['room_name'].' '.Portal::language('minibar');
            }
        }
        
		//--------------------------------------------laundry--------------------------------------------------------
		if($laundrys = DB::fetch_all($sql_l)){
            foreach($laundrys as $k=>$laundry){	
                $percent = 100;$status = 0;
                $amount = $laundry['total'];
                if($amount==0) continue;
                $items['LAUNDRY_'.$k]['net_amount'] = System::display_number($amount);
                $items['LAUNDRY_'.$k]['id'] = $k;
                $items['LAUNDRY_'.$k]['type'] = 'LAUNDRY';
                $items['LAUNDRY_'.$k]['date'] = date('d/m',$laundry['time']);
                $items['LAUNDRY_'.$k]['service_rate'] = $laundry['fee_rate'];
                $items['LAUNDRY_'.$k]['tax_rate'] = $laundry['tax_rate'];
                $items['LAUNDRY_'.$k]['percent'] = $percent;
                $items['LAUNDRY_'.$k]['status'] = $status;
                $items['LAUNDRY_'.$k]['rr_id'] = $laundry['reservation_room_id'];
                $items['LAUNDRY_'.$k]['amount'] = System::display_number($amount);
                $items['LAUNDRY_'.$k]['description'] = 'Giặt / Laundry';//$row['room_name'].' '.Portal::language('laundry');
            }
        }
        
        //--------------------------------------------/laundry--------------------------------------------------------
        if($compensated_items = DB::fetch_all($sql_compensated_item)){
            foreach($compensated_items as $k=>$compensated_item){
                $percent = 100;$status = 0;
                $amount = $compensated_item['total'];
                if($amount==0) continue;
                $items['EQUIPMENT_'.$k]['net_amount'] = System::display_number($amount);
                $items['EQUIPMENT_'.$k]['id'] = $k;
                $items['EQUIPMENT_'.$k]['type'] = 'EQUIPMENT';
                $items['EQUIPMENT_'.$k]['service_rate'] = $compensated_item['fee_rate'];
                $items['EQUIPMENT_'.$k]['tax_rate'] = $compensated_item['tax_rate'];
                $items['EQUIPMENT_'.$k]['percent'] = $percent;
                $items['EQUIPMENT_'.$k]['status'] = $status;
                $items['EQUIPMENT_'.$k]['rr_id'] = $compensated_item['reservation_room_id'];
                $items['EQUIPMENT_'.$k]['date'] = date('d/m',$compensated_item['time']);
                $items['EQUIPMENT_'.$k]['amount'] = System::display_number($amount);
                $items['EQUIPMENT_'.$k]['description'] = $compensated_item['room_name'].' '.Portal::language('equipment');
            }
		}
        
        //-----------------------ticket--------------------------------------------------
        $sql = '
						select 
							ticket_reservation.id,
                            nvl(ticket_reservation.amount_pay_with_room,0) as total,
                            ticket_reservation.time,
                            ticket_reservation.total_before_tax,
							(\''.Portal::language('ticket').'\' || \'_\' || ticket_reservation.id) AS ticket_name ,
							ticket_reservation.deposit as ticket_deposit,
                            reservation_room.id as reservation_room_id,
                            room.name as room_name
						from 
							ticket_reservation
                            inner join reservation_room on  reservation_room.id = ticket_reservation.reservation_room_id
                            inner join room on room.id = reservation_room.room_id
                            inner join room_level on room.room_level_id = room_level.id
						where ticket_reservation.time < '.($to+86400).' 
                            AND ticket_reservation.time >= '.($from).' 
                            and (reservation_room.status = \'CHECKIN\') 
                            and room_level.is_virtual != 1 
                            '.$cond_all.' 
					';//and (bar_reservation.ARRIVAL_TIME>='.$d.' and bar_reservation.ARRIVAL_TIME<='.($d+24*3600).') 
        if($ticket_resrs = DB::fetch_all($sql))
        {
            foreach($ticket_resrs as $bk=>$reser)
            {
                $percent = 100;
                $status = 0;
                $amount = $reser['total'];
                if($amount==0) continue;
                $items['TICKET_'.$bk]['net_amount'] = System::display_number($amount);
                $items['TICKET_'.$bk]['id'] = $bk;
                $items['TICKET_'.$bk]['type'] = 'TICKET';
                $items['TICKET_'.$bk]['date'] = date('d/m',$reser['time']);
                $items['TICKET_'.$bk]['service_rate'] = 0;
                $items['TICKET_'.$bk]['tax_rate'] = 10;
                $items['TICKET_'.$bk]['percent'] = $percent;
                $items['TICKET_'.$bk]['status'] = $status;
                $items['TICKET_'.$bk]['rr_id'] = $reser['reservation_room_id'];
                $items['TICKET_'.$bk]['amount'] = System::display_number($amount);
                $items['TICKET_'.$bk]['description'] = $reser['room_name'].' TICKET';
                if($reser['ticket_deposit']>0)
                {
                    if(!isset($items['DEPOSIT_'.$reser['reservation_room_id']]))
                    {
                        $items['DEPOSIT_'.$reser['reservation_room_id']]['amount'] = 0;
                        $items['DEPOSIT_'.$reser['reservation_room_id']]['type'] = 'DEPOSIT';
                        $items['DEPOSIT_'.$reser['reservation_room_id']]['id'] = $reser['reservation_room_id'];
                    }
                    $items['DEPOSIT_'.$reser['reservation_room_id']]['amount'] += $reser['ticket_deposit'];
                }
            }
		}
        //-----------------------/ticket------------------------------------------------
        
        //------------------------vending-----------------------------------------------------------------
        $sql='
                        select 
                            ve_reservation.id,
                            nvl(ve_reservation.amount_pay_with_room,0) as total,
                            ve_reservation.time,
                            ve_reservation.total_before_tax,
                            (\''.Portal::language('vending').'\' || \'_\' || ve_reservation.id) AS ticket_name,
                            ve_reservation.deposit as vending_deposit,
                            reservation_room.id as reservation_room_id,
                            room.name as room_name
                         from 
							ve_reservation
                            inner join reservation_room on  reservation_room.id = ve_reservation.reservation_room_id
                            inner join room on room.id = reservation_room.room_id
                            inner join room_level on room.room_level_id = room_level.id 
                         where ve_reservation.time < '.($to+86400).' 
                            AND ve_reservation.time >= '.($from).' 
                            and (reservation_room.status = \'CHECKIN\') 
                            and room_level.is_virtual != 1 
                            '.$cond_all.'      
        ';
        if($ve_resrs = DB::fetch_all($sql))
        {
            foreach($ve_resrs as $bk=>$reser)
            {
                $percent = 100;
                $status = 0;
                $amount = $reser['total'];
                if($amount==0) continue;
                $items['VENDING_'.$bk]['net_amount'] = System::display_number($amount);
                $items['VENDING_'.$bk]['id'] = $bk;
                $items['VENDING_'.$bk]['type'] = 'VENDING';
                $items['VENDING_'.$bk]['date'] = date('d/m',$reser['time']);
                $items['VENDING_'.$bk]['service_rate'] = 0;
                $items['VENDING_'.$bk]['tax_rate'] = 10;
                $items['VENDING_'.$bk]['percent'] = $percent;
                $items['VENDING_'.$bk]['status'] = $status;
                $items['VENDING_'.$bk]['rr_id'] = $reser['reservation_room_id'];
                $items['VENDING_'.$bk]['amount'] = System::display_number($amount);
                $items['VENDING_'.$bk]['description'] = $reser['room_name'].' VENDING';
                if($reser['vending_deposit']>0)
                {
                    if(!isset($items['DEPOSIT_'.$reser['reservation_room_id']]))
                    {
                        $items['DEPOSIT_'.$reser['reservation_room_id']]['amount'] = 0;
                        $items['DEPOSIT_'.$reser['reservation_room_id']]['type'] = 'DEPOSIT';
                        $items['DEPOSIT_'.$reser['reservation_room_id']]['id'] = $reser['reservation_room_id'];
                    }
                    $items['DEPOSIT_'.$reser['reservation_room_id']]['amount'] += $reser['vending_deposit'];
                }
            }
		}
        //------------------------/vending-----------------------------------------------------------------
        
        //-------------------------------------nha hang--------------------------------------------------------			
		$sql = '
						select 
							bar_reservation.id, bar_reservation.payment_result, 
							bar_reservation.time_out, bar_reservation.prepaid,
                            bar_reservation.amount_pay_with_room,
                            bar_reservation.tax_rate,bar_reservation.bar_fee_rate,
							(\''.Portal::language('food').'\' || \'_\' || bar_reservation.id) AS bar_name,
							bar_reservation.deposit as bar_deposit,
                            reservation_room.id as reservation_room_id
						from 
							bar_reservation 
							inner join bar ON bar_reservation.bar_id = bar.id 
                            inner join reservation_room on  reservation_room.id = bar_reservation.reservation_room_id
                            inner join room on room.id = reservation_room.room_id
                            inner join room_level on room.room_level_id = room_level.id
						where bar_reservation.time_out < '.($to+86400).' 
                            AND bar_reservation.time_out >= '.($from).' 
                            and (reservation_room.status = \'CHECKIN\') 
                            and room_level.is_virtual != 1 
                            '.$cond_all.' 
                            and (bar_reservation.status=\'CHECKOUT\' or bar_reservation.status=\'CHECKIN\')
					';//and (bar_reservation.ARRIVAL_TIME>='.$d.' and bar_reservation.ARRIVAL_TIME<='.($d+24*3600).') 
        if($bar_resrs = DB::fetch_all($sql)){
            foreach($bar_resrs as $bk=>$reser){
                $percent = 100;$status = 0;
                $amount = $reser['amount_pay_with_room'];
                if($amount==0) continue;
                $items['BAR_'.$bk]['net_amount'] = System::display_number($amount);
                $items['BAR_'.$bk]['id'] = $bk;
                $items['BAR_'.$bk]['type'] = 'BAR';
                $items['BAR_'.$bk]['date'] = date('d/m',$reser['time_out']);
                $items['BAR_'.$bk]['service_rate'] = $reser['bar_fee_rate'];
                $items['BAR_'.$bk]['tax_rate'] = $reser['tax_rate'];
                $items['BAR_'.$bk]['percent'] = $percent;
                $items['BAR_'.$bk]['status'] = $status;
                $items['BAR_'.$bk]['rr_id'] = $reser['reservation_room_id'];
                $items['BAR_'.$bk]['amount'] = System::display_number($amount);
                $items['BAR_'.$bk]['description'] = 'Restaurant';
                
                if($reser['bar_deposit'])
                {
                    if(!isset($items['DEPOSIT_'.$reser['reservation_room_id']]))
                    {
                        $items['DEPOSIT_'.$reser['reservation_room_id']]['amount'] = 0;
                        $items['DEPOSIT_'.$reser['reservation_room_id']]['type'] = 'DEPOSIT';
                        $items['DEPOSIT_'.$reser['reservation_room_id']]['id'] = $reser['reservation_room_id'];
                    }
                    $items['DEPOSIT_'.$reser['reservation_room_id']]['amount'] += $reser['bar_deposit'];
                }
            }
        }
        
        //-----------------------------------------karaoke---------------------------------------------------------			
		//$cond_rr_id = $this->cond_reservation_room($reservation_rooms,'karaoke_reservation');
        $sql = '
						select 
							karaoke_reservation.id, karaoke_reservation.payment_result, 
							karaoke_reservation.time_out, karaoke_reservation.total, karaoke_reservation.prepaid,
							karaoke_reservation.total_before_tax, karaoke_reservation.total_before_tax, karaoke_reservation.tax_rate,karaoke_reservation.karaoke_fee_rate,
							(\''.Portal::language('karaoke').'\' || \'_\' || karaoke_reservation.id) AS karaoke_name ,
							karaoke_reservation.deposit as karaoke_deposit,
                            reservation_room.id as reservation_room_id
                            ,karaoke_reservation.amount_pay_with_room
						from 
							karaoke_reservation 
							inner join karaoke ON karaoke_reservation.karaoke_id = karaoke.id 
                            inner join reservation_room on  reservation_room.id = karaoke_reservation.reservation_room_id
						    
                        where 
							karaoke_reservation.time_out < '.($to+86400-1).' 
                            AND karaoke_reservation.time_out >= '.($from).' 
                            and (reservation_room.status = \'CHECKIN\') 
                            
                            '.$cond_all.' 
                            and (karaoke_reservation.status=\'CHECKOUT\' or karaoke_reservation.status=\'CHECKIN\')
					';
//system::debug(DB::fetch_all($sql));
        if($karaoke_resrs = DB::fetch_all($sql))
        {
            foreach($karaoke_resrs as $bk=>$reser){
                $percent = 100;$status = 0;
                $amount = $reser['amount_pay_with_room'];
                if($amount==0) continue;
                $items['KARAOKE_'.$bk]['net_amount'] = System::display_number($amount);
                if(isset($folio_other['KARAOKE_'.$bk])){
                    if($folio_other['KARAOKE_'.$bk]['percent']==100 && round($folio_other['KARAOKE_'.$bk]['amount'] - $amount)==0){
                        $status = 1;
                    }else{
                        //$percent = 100 - $folio_other['KARAOKE_'.$bk]['percent'];
                        $percent = ($amount - $folio_other['KARAOKE_'.$bk]['amount'])/$amount*100;
                        $percent = round($percent,2);
                        $amount = $amount - $folio_other['KARAOKE_'.$bk]['amount'];
                    }
                }
                $items['KARAOKE_'.$bk]['id'] = $bk;
                $items['KARAOKE_'.$bk]['type'] = 'KARAOKE';
                $items['KARAOKE_'.$bk]['date'] = date('d/m',$reser['time_out']);
                $items['KARAOKE_'.$bk]['service_rate'] = $reser['karaoke_fee_rate'];
                $items['KARAOKE_'.$bk]['tax_rate'] = $reser['tax_rate'];
                $items['KARAOKE_'.$bk]['percent'] = $percent;
                $items['KARAOKE_'.$bk]['status'] = $status;
                $items['KARAOKE_'.$bk]['rr_id'] = $reser['reservation_room_id'];
                $items['KARAOKE_'.$bk]['amount'] = System::display_number($amount);
                $items['KARAOKE_'.$bk]['description'] = 'Karaoke';
                if($reser['karaoke_deposit'])
                {
                    if(!isset($items['DEPOSIT_'.$reser['reservation_room_id']]))
                    {
                        $items['DEPOSIT_'.$reser['reservation_room_id']]['amount'] = 0;
                        $items['DEPOSIT_'.$reser['reservation_room_id']]['type'] = 'DEPOSIT';
                        $items['DEPOSIT_'.$reser['reservation_room_id']]['id'] = $reser['reservation_room_id'];
                    }
                    $items['DEPOSIT_'.$reser['reservation_room_id']]['amount'] += $reser['karaoke_deposit'];
                }
            }
		}
        
        
        //-------------------------------------------------Other services----------------------------------------
		$all_services = DB::fetch_all('
				select 
					service_id as id,
                    reservation_room_service.amount,
                    reservation_room_id,
                    service.name,service.type,
					0 as service_charge_amount, 0 as tax_amount,
                    reservation_room_service.id as room_service_id,
                    reservation_room.id as reservation_room_id,
                    room.name as room_name
				from 
					reservation_room_service 
					inner join service on service.id = service_id 
                    inner join reservation_room on  reservation_room.id = reservation_room_service.reservation_room_id
                    inner join room on room.id = reservation_room.room_id
                    inner join room_level on room.room_level_id = room_level.id
				where reservation_room.time_in < '.($to+86400).' 
                    AND reservation_room.time_out >= '.($from).' 
                    and (reservation_room.status = \'CHECKIN\') 
                    and room_level.is_virtual != 1 
                    '.$cond_all.' 
			');
		$all_services;
		foreach($all_services as $s_key=>$s_value){
            if($s_value['type']=='SERVICE'){
                $percent = 100;$status = 0;
                $amount = $s_value['amount'];
                if($amount==0) continue;
                $items['SERVICE_'.$s_key]['net_amount'] = System::display_number($amount);
                $items['SERVICE_'.$s_key]['id'] = $s_key;
                $items['SERVICE_'.$s_key]['type'] = 'SERVICE';
                $items['SERVICE_'.$s_key]['date'] = '';
                $items['SERVICE_'.$s_key]['service_rate'] = 0;
                $items['SERVICE_'.$s_key]['tax_rate'] = 0;
                $items['SERVICE_'.$s_key]['percent'] = $percent;
                $items['SERVICE_'.$s_key]['status'] = $status;
                $items['SERVICE_'.$s_key]['rr_id'] = $s_value['reservation_room_id'];
                $items['SERVICE_'.$s_key]['amount'] = System::display_number($amount);
                $items['SERVICE_'.$s_key]['description'] = $s_value['room_name'].' '.Portal::language('service');
            }	
        }
        foreach($all_services as $s_key=>$s_value){
            if($s_value['type']=='ROOM'){
                $percent = 100;$status = 0;
                $amount = $s_value['amount'];
                if($amount==0) continue;
                $items['ROOM_SERVICE_'.$s_key]['net_amount'] = System::display_number($amount);
                $items['ROOM_SERVICE_'.$s_key]['id'] = $s_key;
                $items['ROOM_SERVICE_'.$s_key]['type'] = 'ROOM_SERVICE';
                $items['ROOM_SERVICE_'.$s_key]['date'] = '';
                $items['ROOM_SERVICE_'.$s_key]['service_rate'] = 0;
                $items['ROOM_SERVICE_'.$s_key]['tax_rate'] = 0;
                $items['ROOM_SERVICE_'.$s_key]['rr_id'] = $s_value['reservation_room_id'];
                $items['ROOM_SERVICE_'.$s_key]['percent'] = $percent;
                $items['ROOM_SERVICE_'.$s_key]['status'] = $status;
                $items['ROOM_SERVICE_'.$s_key]['amount'] = System::display_number($amount);
                $items['ROOM_SERVICE_'.$s_key]['description'] = $s_value['room_name'].' '.Portal::language('room_service');
            }	
        }
        //----------------------------------------/Other services------------------------------------------------
        
        //----------------------------------------Spa------------------------------------------------
        $sql_massage='
					SELECT 
						massage_reservation_room.id,
                        massage_product_consumed.room_id, 
                        massage_reservation_room.hotel_reservation_room_id,
                        massage_reservation_room.amount_pay_with_room as total_amount,
                        reservation_room.id as reservation_room_id,
                        room.name as room_name
					FROM 
						massage_reservation_room
                        inner join massage_product_consumed on massage_product_consumed.reservation_room_id = massage_reservation_room.id
                        inner join reservation_room on  reservation_room.id = massage_reservation_room.hotel_reservation_room_id
                        inner join room on room.id = reservation_room.room_id
                        inner join room_level on room.room_level_id = room_level.id
					WHERE massage_reservation_room.time < '.($to+86400).' 
                        AND massage_reservation_room.time >= '.($from).' 
                        and (reservation_room.status = \'CHECKIN\') 
                        and room_level.is_virtual != 1 
                        '.$cond_all.' 
				';
        //System::debug($sql_massage);
                
		if($massages = DB::fetch_all($sql_massage)){
            foreach($massages as $mg=>$mgr)
            {
                $percent = 100;$status = 0;
                $amount = $mgr['total_amount'];
                if($amount==0) continue;
                $items['MASSAGE_'.$mg]['net_amount'] = System::display_number($amount);
                $items['MASSAGE_'.$mg]['id'] = $mg;
                $items['MASSAGE_'.$mg]['type'] = 'MASSAGE';
                $items['MASSAGE_'.$mg]['service_rate'] = 0;
                $items['MASSAGE_'.$mg]['tax_rate'] = 0;
                $items['MASSAGE_'.$mg]['date'] = '';
                $items['MASSAGE_'.$mg]['rr_id'] = $mgr['reservation_room_id'];
                $items['MASSAGE_'.$mg]['percent'] = $percent;
                $items['MASSAGE_'.$mg]['status'] = $status;
                $items['MASSAGE_'.$mg]['amount'] = System::display_number($amount);
                $items['MASSAGE_'.$mg]['description'] = $mgr['room_name'].' '.Portal::language('massage').'_'.$mg;
            }	
		}
        //----------------------------------------/Spa------------------------------------------------
        
        //----------------------------------------/tnnis------------------------------------------------
        if(URL::get('tennis_invoice')){
            $sql_tennis='
					SELECT 
						tennis_reservation_court.hotel_reservation_room_id,
                        sum(tennis_reservation_court.total_amount) as total_amount,
                        reservation_room.id as reservation_room_id,
                        room.name as room_name
					FROM 
						tennis_reservation_court
                        inner join reservation_room on  reservation_room.id = tennis_reservation_court.hotel_reservation_room_id
                        inner join room on room.id = reservation_room.room_id
                        inner join room_level on room.room_level_id = room_level.id
					WHERE tennis_reservation_court.time_out < '.($to+86400).' 
                        AND tennis_reservation_court.time_out >= '.($from).' 
                        and (reservation_room.status = \'CHECKIN\') 
                        and room_level.is_virtual != 1 
                        '.$cond_all.' 
					GROUP BY
						tennis_reservation_court.hotel_reservation_room_id
				';
            if($tennis = DB::fetch_all($sql_tennis) and HAVE_TENNIS){
                foreach($tennis as $s_key=>$s_value){
                    $rr_id = $s_value['reservation_room_id'];
                    $percent = 100;$status = 0;
                    $amount =$s_value['total_amount'];
                    if($amount==0) continue;
                    $items['TENNIS_'.$rr_id]['net_amount'] = System::display_number($amount);
                    $items['TENNIS_'.$rr_id]['id'] = $rr_id;
                    $items['TENNIS_'.$rr_id]['type'] = 'TENNIS';
                    $items['TENNIS_'.$rr_id]['service_rate'] = 0;
                    $items['TENNIS_'.$rr_id]['tax_rate'] = 0;
                    $items['TENNIS_'.$rr_id]['date'] = '';
                    $items['TENNIS_'.$rr_id]['rr_id'] = $rr_id;
                    $items['TENNIS_'.$rr_id]['percent'] = $percent;
                    $items['TENNIS_'.$rr_id]['status'] = $status;
                    $items['TENNIS_'.$rr_id]['amount'] = System::display_number($amount);
                    $items['TENNIS_'.$id]['description'] = $s_value['room_name'].' '.Portal::language('tennis');
                }
            }
        }
        //----------------------------------------tennis------------------------------------------------
		
        //----------------------------------------swimming------------------------------------------------
        if(URL::get('swimming_pool_invoice')){
            $sql_swimming_pool='
					SELECT 
						swimming_pool_reservation_pool.hotel_reservation_room_id,
                        sum(swimming_pool_reservation_pool.total_amount) as total_amount,
                        reservation_room.id as reservation_room_id,
                        room.name as room_name
					FROM 
						swimming_pool_reservation_pool
                        inner join reservation_room on  reservation_room.id = swimming_pool_reservation_pool.hotel_reservation_room_id
                        inner join room on room.id = reservation_room.room_id
                        inner join room_level on room.room_level_id = room_level.id
					WHERE swimming_pool_reservation_pool.time_out < '.($to+86400).' 
                        AND swimming_pool_reservation_pool.time_out >= '.($from).' 
                        and (reservation_room.status = \'CHECKIN\') 
                        and room_level.is_virtual != 1 
                        '.$cond_all.' 
					GROUP BY
						swimming_pool_reservation_pool.hotel_reservation_room_id
				';
            if($swimming_pools = DB::fetch_all($sql_swimming_pool) and HAVE_SWIMMING){
                foreach($swimming_pools as $s_key=>$s_value){
                    $rr_id = $s_value['reservation_room_id'];
                    $percent = 100;$status = 0;
                    $amount =$s_value['total_amount'];
                    if($amount==0) continue;
                    $items['SWIMMING_POOL_'.$rr_id]['net_amount'] = System::display_number($amount);
                    $items['SWIMMING_POOL_'.$rr_id]['id'] = $rr_id;
                    $items['SWIMMING_POOL_'.$rr_id]['type'] = 'SWIMMING_POOL';
                    $items['SWIMMING_POOL_'.$rr_id]['service_rate'] = 0;
                    $items['SWIMMING_POOL_'.$rr_id]['tax_rate'] = 0;
                    $items['SWIMMING_POOL_'.$rr_id]['date'] = '';
                    $items['SWIMMING_POOL_'.$rr_id]['rr_id'] = $rr_id;
                    $items['SWIMMING_POOL_'.$rr_id]['percent'] = $percent;
                    $items['SWIMMING_POOL_'.$rr_id]['status'] = $status;
                    $items['SWIMMING_POOL_'.$rr_id]['amount'] = System::display_number($amount);
                    $items['SWIMMING_POOL_'.$rr_id]['description'] = $s_value['room_name'].' '.Portal::language('swimming_pool');
                }
            }
        }
        //----------------------------------------/swimming------------------------------------------------
		
        //----------------------------------------karaoke------------------------------------------------
        /*
        if(URL::get('karaoke_invoice')){
            $sql_karaoke='
					SELECT 
						KA_RESERVATION.RESERVATION_ROOM_ID,
                        sum(KA_RESERVATION.TOTAL) as total_amount,
                        reservation_room.id as reservation_room_id,
                        room.name as room_name
					FROM 
						KA_RESERVATION
                        inner join reservation_room on  reservation_room.id = swimming_pool_reservation_pool.RESERVATION_ROOM_ID
                        inner join room on room.id = reservation_room.room_id
                        inner join room_level on room.room_level_id = room_level.id
					WHERE KA_RESERVATION.time_out < '.($to+86400).' 
                        AND KA_RESERVATION.time_out >= '.($from).'
                        and (reservation_room.status = \'CHECKIN\') 
                        and room_level.is_virtual != 1 
                        '.$cond_all.' 
					GROUP BY
						KA_RESERVATION.RESERVATION_ROOM_ID
				';
            if($karaokes = DB::fetch_all($sql_karaoke) and HAVE_KARAOKE){
                foreach($karaokes as $s_key=>$s_value){
                    $rr_id = $s_value['reservation_room_id'];
                    $percent = 100;$status = 0;
                    $amount =$s_value['total_amount'];
                    if($amount==0) continue;
                    $items['KARAOKE_'.$rr_id]['net_amount'] = System::display_number($amount);
                    $items['KARAOKE_'.$rr_id]['id'] = $rr_id;
                    $items['KARAOKE_'.$rr_id]['type'] = 'KARAOKE';
                    $items['KARAOKE_'.$rr_id]['service_rate'] = 0;
                    $items['KARAOKE_'.$rr_id]['tax_rate'] = 0;
                    $items['KARAOKE_'.$rr_id]['date'] = '';
                    $items['KARAOKE_'.$rr_id]['rr_id'] = $rr_id;
                    $items['KARAOKE_'.$rr_id]['percent'] = $percent;
                    $items['KARAOKE_'.$rr_id]['status'] = $status;
                    $items['KARAOKE_'.$rr_id]['amount'] = System::display_number($amount);
                    $items['KARAOKE_'.$rr_id]['description'] = $s_value['room_name'].' '.Portal::language('karaoke');
                }
            }
        }
        */
        //----------------------------------------/karaoke------------------------------------------------
			
        //----------------------------------------shop------------------------------------------------
        if(URL::get('shop_invoice')){
            $sql_shop='
					SELECT 
						shop_invoice.RESERVATION_ROOM_ID,
                        sum(shop_invoice.TOTAL) as total_amount,
                        reservation_room.id as reservation_room_id,
                        room.name as room_name
					FROM 
						shop_invoice
                        inner join reservation_room on  reservation_room.id = shop_invoice.RESERVATION_ROOM_ID
                        inner join room on room.id = reservation_room.room_id
                        inner join room_level on room.room_level_id = room_level.id
					WHERE shop_invoice.time < '.($to+86400).' 
                        AND shop_invoice.time >= '.($from).' 
                        and (reservation_room.status = \'CHECKIN\') 
                        and room_level.is_virtual != 1 
                        '.$cond_all.' 
					GROUP BY
						shop_invoice.RESERVATION_ROOM_ID
				';
            if($shops = DB::fetch_all($sql_shop)){
                foreach($shops as $s_key=>$s_value){
                    $rr_id = $s_value['reservation_room_id'];
                    $percent = 100;$status = 0;
                    $amount =$s_value['total_amount'];
                    if($amount==0) continue;
                    $items['SHOP_'.$rr_id]['net_amount'] = System::display_number($amount);
                    $items['SHOP_'.$rr_id]['id'] = $rr_id;
                    $items['SHOP_'.$rr_id]['type'] = 'SHOP';
                    $items['SHOP_'.$rr_id]['service_rate'] = 0;
                    $items['SHOP_'.$rr_id]['tax_rate'] = 0;
                    $items['SHOP_'.$rr_id]['date'] = '';
                    $items['SHOP_'.$rr_id]['rr_id'] = $rr_id;
                    $items['SHOP_'.$rr_id]['percent'] = $percent;
                    $items['SHOP_'.$rr_id]['status'] = $status;
                    $items['SHOP_'.$rr_id]['amount'] = System::display_number($amount);
                    $items['SHOP_'.$rr_id]['description'] = $s_value['room_name'].' '.Portal::language('shop');
                }
            }
        }
        //----------------------------------------/shop------------------------------------------------
        
        //----------------------------------------telephone------------------------------------------------
        $sql_p = '
					SELECT
						telephone_report_daily.total_before_tax AS total,
						telephone_report_daily.bill_id as id,
						telephone_report_daily.dial_number,
						telephone_report_daily.hdate,
                        reservation_room.id as reservation_room_id,
                        room.name as room_name
					FROM
						telephone_report_daily
						inner join telephone_number on telephone_number.phone_number = telephone_report_daily.phone_number_id
                        inner join reservation_room on  reservation_room.id = telephone_report_daily.reservation_room_id
                        inner join room on room.id = reservation_room.room_id
                        inner join room_level on room.room_level_id = room_level.id
					WHERE telephone_report_daily.hdate < '.($to+86400).' 
                        AND telephone_report_daily.hdate >= '.($from).' 
                        and (reservation_room.status = \'CHECKIN\') 
                        and room_level.is_virtual != 1 
                        '.$cond_all.' 
                        and telephone_report_daily.total_before_tax > 0
					ORDER BY
						telephone_report_daily.reservation_room_id
	
				';
				
        if($phones = DB::fetch_all($sql_p)){
            foreach($phones as $t_key=>$t_value){
                $percent = 100;$status = 0;
                $amount =$t_value['total'];
                if($amount==0) continue;
                $items['TELEPHONE_'.$t_key]['net_amount'] = System::display_number($amount);
                $items['TELEPHONE_'.$t_key]['id'] = $t_key;
                $items['TELEPHONE_'.$t_key]['type'] = 'TELEPHONE';
                $items['TELEPHONE_'.$t_key]['service_rate'] = 0;
                $items['TELEPHONE_'.$t_key]['tax_rate'] = (TELEPHONE_TAX_RATE)?TELEPHONE_TAX_RATE:0;
                $items['TELEPHONE_'.$t_key]['date'] = date('d/m',$t_value['hdate']);
                $items['TELEPHONE_'.$t_key]['rr_id'] = $t_value['reservation_room_id'];
                $items['TELEPHONE_'.$t_key]['percent'] = $percent;
                $items['TELEPHONE_'.$t_key]['status'] = $status;
                $items['TELEPHONE_'.$t_key]['amount'] = System::display_number($amount);
                $items['TELEPHONE_'.$t_key]['description'] = $t_value['room_name'].' '.Portal::language('telephone').'_'.$t_value['dial_number'];
            }
        }
        //----------------------------------------/telephone------------------------------------------------
        
        //----------------------------------------discount------------------------------------------------
        $sql = '
				SELECT 
					reservation_room.id
					,reservation_room.reduce_amount
                    ,room.name as room_name
				FROM 
					reservation_room
					inner join room on room.id = reservation_room.room_id
                    inner join room_level on room.room_level_id = room_level.id
				WHERE reservation_room.time_in < '.($to+86400).' 
                    AND reservation_room.time_out >= '.($from).' 
                    and (reservation_room.status = \'CHECKIN\') 
                    and room_level.is_virtual != 1 
                    '.$cond_all.' 
                    AND reservation_room.reduce_amount > 0';
        $reduce_amount = DB::fetch_all($sql);
        foreach($reduce_amount as $key=>$value){
            $rr_id = $value['id'];
            $percent = 100;$status = 0;
            $amount = $value['reduce_amount'];
            if($amount==0) continue;
            $items['DISCOUNT_'.$rr_id]['net_amount'] = System::display_number($amount);
            $items['DISCOUNT_'.$rr_id]['id'] = $rr_id;
            $items['DISCOUNT_'.$rr_id]['type'] = 'DISCOUNT';
            $items['DISCOUNT_'.$rr_id]['service_rate'] = 0;
            $items['DISCOUNT_'.$rr_id]['tax_rate'] = 0;
            $items['DISCOUNT_'.$rr_id]['date'] = '';
            $items['DISCOUNT_'.$rr_id]['rr_id'] = $rr_id;
            $items['DISCOUNT_'.$rr_id]['percent'] = $percent;
            $items['DISCOUNT_'.$rr_id]['status'] = $status;
            $items['DISCOUNT_'.$rr_id]['amount'] = System::display_number($amount);
            $items['DISCOUNT_'.$rr_id]['description'] = $value['room_name'].' '.Portal::language('discount_room');
        }
        //----------------------------------------/discount------------------------------------------------
        
        //----------------------------------------deposit------------------------------------------------
        $sql = '
				SELECT 
					reservation_room.id
					,reservation_room.deposit
                    ,room.name as room_name
				FROM 
					reservation_room
					inner join room on room.id = reservation_room.room_id
                    inner join room_level on room.room_level_id = room_level.id
				WHERE reservation_room.time_in < '.($to+86400).' 
                    AND reservation_room.time_out >= '.($from).' 
                    and (reservation_room.status = \'CHECKIN\') 
                    and room_level.is_virtual != 1 
                    '.$cond_all.' 
                    AND reservation_room.deposit > 0';
        $deposit = DB::fetch_all($sql);
        foreach($deposit as $key=>$value){
            $rr_id = $value['id'];
            $percent = 100;
            $status = 0;
            $amount =$value['deposit']+(isset($items['DEPOSIT_'.$rr_id]['amount'])?$items['DEPOSIT_'.$rr_id]['amouunt']:0);
            if($amount==0) continue;
            $items['DEPOSIT_'.$rr_id]['net_amount'] = System::display_number($amount);
            $items['DEPOSIT_'.$rr_id]['id'] = $rr_id;
            $items['DEPOSIT_'.$rr_id]['type'] = 'DEPOSIT';
            $items['DEPOSIT_'.$rr_id]['service_rate'] = 0;
            $items['DEPOSIT_'.$rr_id]['tax_rate'] = 0;
            $items['DEPOSIT_'.$rr_id]['date'] = '';
            $items['DEPOSIT_'.$rr_id]['rr_id'] = $rr_id;
            $items['DEPOSIT_'.$rr_id]['percent'] = $percent;
            $items['DEPOSIT_'.$rr_id]['status'] = $status;
            $items['DEPOSIT_'.$rr_id]['amount'] = System::display_number($amount);
            $items['DEPOSIT_'.$rr_id]['description'] = $value['room_name'].' '.Portal::language('deposit_room');
        }
        
        //other deposit
        foreach($items as $key=>$value){
            if($value['type'] == 'DEPOSIT' and !isset($value['status']))
            {
                $rr_id = $value['id'];
                $percent = 100;
                $status = 0;
                $amount =$value['amount'];
                if($amount==0) continue;
                $items['DEPOSIT_'.$rr_id]['net_amount'] = System::display_number($amount);
                $items['DEPOSIT_'.$rr_id]['id'] = $rr_id;
                $items['DEPOSIT_'.$rr_id]['type'] = 'DEPOSIT';
                $items['DEPOSIT_'.$rr_id]['service_rate'] = 0;
                $items['DEPOSIT_'.$rr_id]['tax_rate'] = 0;
                $items['DEPOSIT_'.$rr_id]['date'] = '';
                $items['DEPOSIT_'.$rr_id]['rr_id'] = $rr_id;
                $items['DEPOSIT_'.$rr_id]['percent'] = $percent;
                $items['DEPOSIT_'.$rr_id]['status'] = $status;
                $items['DEPOSIT_'.$rr_id]['amount'] = System::display_number($amount);
                $items['DEPOSIT_'.$rr_id]['description'] = 'deposit';
            }
        }
        //----------------------------------------deposit------------------------------------------------
        
        return $items;
        
        
    }
    
    function get_remain($all,$traveller_folios)
	{
        //System::debug($all);
        //System::debug($traveller_folios);
        foreach($all as $key => $value)
        {
            $percent = 100;
            $status = 0;
            $amount = System::calculate_number($value['amount']);
            if(isset($traveller_folios[$key]))
            {
                if($traveller_folios[$key]['percent']==100 && round($traveller_folios[$key]['amount'] - $amount)==0 )
                {
				    $status = 1;
				}
                else
                {
				    $percent = ($amount - $traveller_folios[$key]['amount'])/$amount*100;
                    $percent = round($percent,2);
				    $amount = $amount - $traveller_folios[$key]['amount'];
				}
            }
            
            $all[$key]['percent'] = $percent;
            $all[$key]['status'] = $status;
            $all[$key]['amount'] = System::display_number($amount);
        }
        return $all;
    }
    
	function draw()
	{
        $_REQUEST['from_date'] = Url::get("from_date",date('01/m/Y'));
        $_REQUEST['to_date'] = Url::get("to_date",date('d/m/Y'));
        $_REQUEST['line_per_page'] = Url::get("line_per_page",30);
        $_REQUEST['no_of_page1'] = Url::get("no_of_page1",400);
        $_REQUEST['start_page'] = Url::get("start_page",1);
        
        $this->line_per_page = $_REQUEST['line_per_page'];
        $this->no_of_page = $_REQUEST['no_of_page1'];
        $this->start_page = $_REQUEST['start_page'];
        
        $time_from = Date_Time::to_time($_REQUEST['from_date']);
        $time_to = Date_Time::to_time($_REQUEST['to_date']);
        //Kimtan
        $portal_id = '';
        if(Url::get('portal_id') != 'ALL')
        {
            $portal_id=Url::get('portal_id')?"and RESERVATION.portal_id ='".Url::get('portal_id')."'":'';
        }
        //end KimTan
        $this->map = array();
        
        //lay ra tat ca cac khoan can thanh toan trong khoang thoi gian
        $all = $this->get_all($time_from,$time_to);
        //lay ra cac dat phong co cac khoan tren
        $cond_rr_id = '';
        foreach($all as $key => $value)
        {
            $cond_rr_id .= " reservation_room.id = ".$value['rr_id']." or";
        }
        $cond_rr_id = "(".($cond_rr_id?substr($cond_rr_id,0,strlen($cond_rr_id)-2):"1=1").")";
        $temp = DB::fetch_all("select           reservation_room.id|| '_' ||traveller.id as id,
                                                reservation_room.id as reservation_room_id,
                                                RESERVATION_ROOM.arrival_time,
                                                RESERVATION_ROOM.departure_time,
                                                RESERVATION_ROOM.price,
                                                RESERVATION_ROOM.exchange_rate,
                                                RESERVATION_ROOM.foc,
                                                RESERVATION_ROOM.foc_all,
                                                reservation.deposit as group_deposit,
                                                room.name as room_name,
                                                customer.name as customer_name,
                                                RESERVATION.id as recode,
                                                traveller.first_name||' '||traveller.last_name as guest_name
                                            from reservation_room
                                                inner join RESERVATION on 
                                                (
                                                    RESERVATION.ID = reservation_room.RESERVATION_ID
                                                    and ".$cond_rr_id."".$portal_id."
                                                )
                                                left outer join customer on customer.id = reservation.customer_id
                                                inner join room on room.id = reservation_room.room_id
                                                inner join room_level on room.room_level_id = room_level.id
                                                left outer join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
                                                left outer join traveller on reservation_traveller.traveller_id = traveller.id
                                            where ".$cond_rr_id."
                                            order by RESERVATION.id desc,reservation_room.id asc");
        
        $reservation_rooms = array();
        foreach($temp as $key => $value)
        {
            if(!isset($reservation_rooms[$value['reservation_room_id']]))
            {
                $arrival_time = Date_Time::convert_orc_date_to_date($value['arrival_time'],"/");
                $departure_time = Date_Time::convert_orc_date_to_date($value['departure_time'],"/");
                $dem = (Date_Time::to_time($departure_time)-Date_Time::to_time($arrival_time))/86400;
                $dem = $dem?$dem:1;
                $reservation_rooms[$value['reservation_room_id']] = array('reservation_room_id'=>$value['reservation_room_id'],
                                                                        'recode'=>$value['recode'],
                                                                        'customer_name'=>$value['customer_name'],
                                                                        'room_name'=>$value['room_name'],
                                                                        'guest_name'=>$value['guest_name'],
                                                                        'arrival_time'=>Date_Time::convert_orc_date_to_date($value['arrival_time'],'/'),
                                                                        'departure_time'=>Date_Time::convert_orc_date_to_date($value['departure_time'],'/'),
                                                                        'dem'=>$dem,
                                                                        'price_vn'=>$value['price'],
                                                                        'price_usd'=>round($value['price']/$value['exchange_rate']),
                                                                        'group_deposit'=>$value['group_deposit'],
                                                                        'foc'=>$value['foc'],
                                                                        'foc_all'=>$value['foc_all'],
                                                                        'remain_room'=>0,
                                                                        'remain_bar'=>0,
                                                                        'remain_karaoke'=>0,
                                                                        'remain_minibar'=>0,
                                                                        'remain_laundry'=>0,
                                                                        'remain_equip'=>0,
                                                                        'remain_telephone'=>0,
                                                                        'remain_massage'=>0,
                                                                        'remain_tour'=>0,
                                                                        'remain_vending'=>0,
                                                                        'remain_karaoke'=>0,
                                                                        'remain_ticket'=>0,
                                                                        'remain_discount'=>0,
                                                                        'remain_deposit'=>0,
                                                                        'remain_group_deposit'=>0,
                                                                        'remain_other'=>0,
                                                                        'remain_total'=>0,);
            }
            else
            {
                $reservation_rooms[$value['reservation_room_id']]['guest_name'] .= ",</br>".$value['guest_name'];
            }
        }
        //System::debug($reservation_rooms);
        //cond reservation room
        $cond_rr_id = $this->cond_reservation_room($reservation_rooms,'traveller_folio');
        $cond_r_id = $this->cond_reservation($reservation_rooms,'traveller_folio');
        $traveller_folios = $this->get_other($cond_rr_id,$cond_r_id);
        $remain = $this->get_remain($all,$traveller_folios);
        //System::debug($all);
        //System::debug($traveller_folios);
        //System::debug($remain);
        //cong don cac khoan theo reservation_room
        foreach($remain as $key => $v)
        {
            if($v['status'])
                continue;
            switch($v['type'])
            {
                case 'ROOM': $reservation_rooms[$v['rr_id']]['remain_room'] += System::calculate_number($v['amount']); 
                        break;
                case 'BAR': $reservation_rooms[$v['rr_id']]['remain_bar'] += System::calculate_number($v['amount']); 
                        break;
                case 'KARAOKE': $reservation_rooms[$v['rr_id']]['remain_karaoke'] += System::calculate_number($v['amount']); 
                        break;        
                case 'MINIBAR': $reservation_rooms[$v['rr_id']]['remain_minibar'] += System::calculate_number($v['amount']); 
                        break;
                case 'LAUNDRY': $reservation_rooms[$v['rr_id']]['remain_laundry'] += System::calculate_number($v['amount']); 
                        break;
                case 'EQUIPMENT': $reservation_rooms[$v['rr_id']]['remain_equip'] += System::calculate_number($v['amount']); 
                        break;
                case 'TELEPHONE': $reservation_rooms[$v['rr_id']]['remain_telephone'] += System::calculate_number($v['amount']); 
                        break;
                case 'MASSAGE': $reservation_rooms[$v['rr_id']]['remain_massage'] += System::calculate_number($v['amount']); 
                        break;
                case 'EXTRA_SERVICE':
                {
                    if(strtoupper(substr($v['code'],0,4)) == 'TOUR')
                        $reservation_rooms[$v['rr_id']]['remain_tour'] += System::calculate_number($v['amount']);
                    else if(strtoupper($v['code']) == 'LATE_CHECKOUT' or strtoupper($v['code']) == 'EARLY_CHECKIN' or strtoupper($v['code']) == 'LATE_CHECKIN')
                        $reservation_rooms[$v['rr_id']]['remain_room'] += System::calculate_number($v['amount']); 
                    else
                        $reservation_rooms[$v['rr_id']]['remain_other'] += System::calculate_number($v['amount']);
                    break;
                }
                case 'DISCOUNT': $reservation_rooms[$v['rr_id']]['remain_discount'] += System::calculate_number($v['amount']); 
                        break;
                case 'DEPOSIT': $reservation_rooms[$v['rr_id']]['remain_deposit'] += System::calculate_number($v['amount']); 
                        break;
                case 'KARAOKE': $reservation_rooms[$v['rr_id']]['remain_karaoke'] += System::calculate_number($v['amount']); 
                        break;
                case 'TICKET': $reservation_rooms[$v['rr_id']]['remain_ticket'] += System::calculate_number($v['amount']); 
                        break;
                case 'VENDING': $reservation_rooms[$v['rr_id']]['remain_vending'] += System::calculate_number($v['amount']); 
                        break;                
                default: $reservation_rooms[$v['rr_id']]['remain_other'] += System::calculate_number($v['amount']); 
                        break;
            }
        }
        
        //lay group deposit cuar cac reservation gan vao cac reservation room
        //xet cac reservation room FOC va unset cac reservation room co total amount =0
        foreach($reservation_rooms as $key=>$value){
            if($value['group_deposit']>0)
            {
                $amount = $value['group_deposit'];
                $reservation_id = $value['recode'];
                if(isset($traveller_folios['DEPOSIT_GROUP_'.$reservation_id])){
                    if(($traveller_folios['DEPOSIT_GROUP_'.$reservation_id]['percent']==100 and $traveller_folios['DEPOSIT_GROUP_'.$reservation_id]['amount'] ==$amount))
                    {
                        $amount = $amount - $traveller_folios['DEPOSIT_GROUP_'.$reservation_id]['amount'];
                    }
                } 
                $reservation_rooms[$key]['remain_group_deposit']= $amount;
    		}
            
            $reservation_rooms[$key]['remain_room'] = (!$value['foc'] and !$value['foc_all'])?round($reservation_rooms[$key]['remain_room'],0):0;
            $reservation_rooms[$key]['remain_bar'] = !$value['foc_all']?round($reservation_rooms[$key]['remain_bar'],0):0;
            $reservation_rooms[$key]['remain_karaoke'] = !$value['foc_all']?round($reservation_rooms[$key]['remain_karaoke'],0):0;
            $reservation_rooms[$key]['remain_minibar'] = !$value['foc_all']?round($reservation_rooms[$key]['remain_minibar'],0):0;
            $reservation_rooms[$key]['remain_laundry'] = !$value['foc_all']?round($reservation_rooms[$key]['remain_laundry'],0):0;
            $reservation_rooms[$key]['remain_equip'] = !$value['foc_all']?round($reservation_rooms[$key]['remain_equip'],0):0;
            $reservation_rooms[$key]['remain_telephone'] = !$value['foc_all']?round($reservation_rooms[$key]['remain_telephone'],0):0;
            $reservation_rooms[$key]['remain_massage'] = !$value['foc_all']?round($reservation_rooms[$key]['remain_massage'],0):0;
            $reservation_rooms[$key]['remain_tour'] = !$value['foc_all']?round($reservation_rooms[$key]['remain_tour'],0):0;
            $reservation_rooms[$key]['remain_vending'] = !$value['foc_all']?round($reservation_rooms[$key]['remain_vending'],0):0;
            $reservation_rooms[$key]['remain_karaoke'] = !$value['foc_all']?round($reservation_rooms[$key]['remain_karaoke'],0):0;
            $reservation_rooms[$key]['remain_ticket'] = !$value['foc_all']?round($reservation_rooms[$key]['remain_ticket'],0):0;
            $reservation_rooms[$key]['remain_discount'] = !$value['foc_all']?round($reservation_rooms[$key]['remain_discount'],0):0;
            $reservation_rooms[$key]['remain_deposit'] = round($reservation_rooms[$key]['remain_deposit'],0);
            $reservation_rooms[$key]['remain_group_deposit'] = round($reservation_rooms[$key]['remain_group_deposit'],0);
            $reservation_rooms[$key]['remain_other'] = !$value['foc_all']?round($reservation_rooms[$key]['remain_other'],0):0;
            
            
            
            $reservation_rooms[$key]['remain_total'] = $reservation_rooms[$key]['remain_room']
                                                    +$reservation_rooms[$key]['remain_bar']
                                                    +$reservation_rooms[$key]['remain_karaoke']
                                                    +$reservation_rooms[$key]['remain_minibar']
                                                    +$reservation_rooms[$key]['remain_laundry']
                                                    +$reservation_rooms[$key]['remain_equip']
                                                    +$reservation_rooms[$key]['remain_telephone']
                                                    +$reservation_rooms[$key]['remain_massage']
                                                    +$reservation_rooms[$key]['remain_tour']
                                                    +$reservation_rooms[$key]['remain_vending']
                                                    +$reservation_rooms[$key]['remain_karaoke']
                                                    +$reservation_rooms[$key]['remain_ticket']
                                                    +$reservation_rooms[$key]['remain_other'];
                                                    
            if(!($reservation_rooms[$key]['remain_total']-$reservation_rooms[$key]['remain_discount']) and !$reservation_rooms[$key]['remain_group_deposit'] and !$reservation_rooms[$key]['remain_deposit'])
                unset($reservation_rooms[$key]);
        }
        $stt = 0;
        $reservation_id = '';
        foreach($reservation_rooms as $key => $value)
        {
            if($value['recode']!=$reservation_id)
            {
                $reservation_id = $value['recode'];
                $stt++;
            }
            $reservation_rooms[$key]['stt'] = $stt;
        }
        
        //System::debug($reservation_rooms);
        require_once 'packages/core/includes/utils/lib/report.php';
        $report = new Report;
        $report->items = $reservation_rooms;
        $this->print_all_pages($report);
	}
    
    function print_all_pages(&$report)
	{
		$count = 0;
		$total_page = 1;
		$pages = array();
		foreach($report->items as $key=>$item)
		{
			if($count>=$this->line_per_page)
			{
				$count = 0;
				$total_page++;
			}
			$pages[$total_page][$key] = $item;
			$count++;
		}
        
        if(Url::get('portal_id')){
            $hotel = DB::fetch('SELECT NAME_1 AS name,ADDRESS FROM PARTY WHERE USER_ID = \''.Url::get('portal_id').'\'');
            $hotel_name = $hotel['name']?$hotel['name']:HOTEL_NAME;
            $hotel_address = $hotel['address']?$hotel['address']:HOTEL_ADDRESS;
		}else{
            $hotel_name = HOTEL_NAME;
            $hotel_address = HOTEL_ADDRESS;
		}
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list());	
		$user_data = Session::get('user_data');
        $user_data['full_name'];
        $this->parse_layout('header',
			$this->map + array(
					'hotel_address'=>$hotel_address,
					'hotel_name'=>$hotel_name,
                    'print_user'=>$user_data['full_name'],
                    'print_time'=>date('H:i d/m/Y')
				));
			
		if(sizeof($pages)>0)
		{
			$this->group_function_params =   array('total_remain_room'=>0,
                                                   'total_remain_bar'=>0,
                                                   'total_remain_karaoke'=>0,
                                                   'total_remain_minibar'=>0,
                                                   'total_remain_laundry'=>0,
                                                   'total_remain_equip'=>0,
                                                   'total_remain_telephone'=>0,
                                                   'total_remain_massage'=>0,
                                                   'total_remain_tour'=>0,
                                                   'total_remain_vending'=>0,
                                                   'total_remain_karaoke'=>0,
                                                   'total_remain_ticket'=>0,
                                                   'total_remain_other'=>0,
                                                   'total_remain'=>0,
                                                   'total_remain_discount'=>0,
                                                   'total_remain_deposit'=>0,
                                                   'total_remain_group_deposit'=>0
                                                   );
			
            for($page_no = $this->start_page; $page_no <= $total_page and $page_no <= ($this->no_of_page + $this->start_page); $page_no++)
			{
				$this->print_page($pages[$page_no], $report, $page_no,$total_page);
			}
		}
        $this->parse_layout('footer',array());
	}
	function print_page($items, &$report, $page_no,$total_page)
	{
		$last_group_function_params = $this->group_function_params;
        
        $reservations = array();
        foreach($items as $key => $value)
        {
            if(!isset($reservations[$value['recode']]))
                $reservations[$value['recode']] = array('stt'=>$value['stt'],
                                                        'recode'=>$value['recode'],
                                                        'customer_name'=>$value['customer_name'],
                                                        'remain_group_deposit'=>$value['remain_group_deposit'],
                                                        'reservation_rooms'=>array($key=>$value));
            else
                $reservations[$value['recode']]['reservation_rooms'][$key]=$value;
                
            $this->group_function_params['total_remain_room'] += $value['remain_room'];
            $this->group_function_params['total_remain_bar'] += $value['remain_bar'];
            $this->group_function_params['total_remain_karaoke'] += $value['remain_karaoke'];
            $this->group_function_params['total_remain_minibar'] += $value['remain_minibar'];
            $this->group_function_params['total_remain_laundry'] += $value['remain_laundry'];
            $this->group_function_params['total_remain_equip'] += $value['remain_equip'];
            $this->group_function_params['total_remain_telephone'] += $value['remain_telephone'];
            $this->group_function_params['total_remain_massage'] += $value['remain_massage'];
            $this->group_function_params['total_remain_tour'] += $value['remain_tour'];
            $this->group_function_params['total_remain_vending'] += $value['remain_vending'];
            $this->group_function_params['total_remain_karaoke'] += $value['remain_karaoke'];
            $this->group_function_params['total_remain_ticket'] += $value['remain_ticket'];
            $this->group_function_params['total_remain_other'] += $value['remain_other'];
            $this->group_function_params['total_remain'] += $value['remain_total'];
            $this->group_function_params['total_remain_discount'] += $value['remain_discount'];
            $this->group_function_params['total_remain_deposit'] += $value['remain_deposit'];
            $this->group_function_params['total_remain_group_deposit'] += $value['group_deposit'];
        }
        //System::debug($reservations);exit();
        //--------------doan nay xet nhưng cái được tích trong khai báo bộ phận cho hotel
        $portal_department = '';
        if(Url::get('portal_id') != 'ALL')
        {
            $portal_department=Url::get('portal_id')?"and portal_department.portal_id ='".Url::get('portal_id')."'":'';
        }
        $sql='
                select 
                    department.id,
                    department.code
                from 
                    department 
                    inner join portal_department on department.code = portal_department.department_code 
                where    
                    1=1
                    '.$portal_department.'
                    and department.parent_id = 0 AND department.code != \'WH\'
        ';
        $department = DB::fetch_all($sql);
        //System::debug($sql);
        //System::debug($department);
        $depa[1] = array('res'=>0,'karaoke'=>0,'hk'=>0,'telephone'=>0,'massage'=>0,'ticket'=>0,'res'=>0,'res'=>0);
        foreach($department as $k=>$v)
        {
            if($v['code']=='RES')
            {
                $depa[1]['res'] = 1;
            }
            if($v['code']=='KARAOKE')
            {
                $depa[1]['karaoke'] = 1;
            }
            if($v['code']=='HK')
            {
                $depa[1]['hk'] = 1;
            }
            if($v['code']=='SPA')
            {
                $depa[1]['massage'] = 1;
            }
            if($v['code']=='VENDING')
            {
                $depa[1]['vending'] = 1;
            }
            if($v['code']=='TICKET')
            {
                $depa[1]['ticket'] = 1;
            }
        }
        //System::debug($department);
		$this->parse_layout('report',array(
				'items'=>$reservations,
				'last_group_function_params'=>$last_group_function_params,
				'group_function_params'=>$this->group_function_params,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
                'department'=>$depa,
			)
		);
	}
}
?>
