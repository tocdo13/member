<?php
class ViewGroupInvoiceForm  extends Form
{
	function ViewGroupInvoiceForm(){
		Form::Form('ViewGroupInvoiceForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function get_items($reservation_traveller_id,$reservation_id,$folio_id){
        require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/currency.php';
       
		$this->map['bill_number'] = $folio_id;
		$this->map['description'] ='';
        $this->map['total_amount'] = 0;
        $this->map['total_foc'] = 0;
        $this->map['total_deposit'] = 0;
        $this->map['total_group_deposit'] = 0;
        $this->map['total_payment'] = 0;
        $items = array();
		//--------------------------------------lay exchange------------------------------------------------
		if(HOTEL_CURRENCY == 'VND'){
			$this->map['exchange_currency_id'] = 'USD';
		}else{  
			$this->map['exchange_currency_id'] = 'VND';	    
		}
		$this->map['exchange_rate'] = DB::fetch('select id,exchange from currency where id=\''.$this->map['exchange_currency_id'].'\'','exchange');
		
        /*$reservation_rooms_1 = $this->get_reservation_room($reservation_id);
		foreach($reservation_rooms_1 as $k=> $room){
			$this->map['customer_name'] = $room['customer_name'];
            $this->map['guest'] =$room['customer_def_name']?$room['customer_name'].' - '.$room['customer_def_name']:$room['customer_name'];
			$this->map['tour_name'] = $room['tour_name'];
			$this->map['address'] = $room['address'];
            $this->map['departure_time'] = $room['departure_time'];
            $this->map['arrival_time'] = $room['arrival_time'];
		}*/
        /** Daund thêm 03/07/2018 do time io ko dúng */
		$cond_dn ='1=1';
		$cond_dn .= ($folio_id!='')?' AND trf.folio_id='.$folio_id.'':'';
        $get_reservation_room = DB::fetch_all('
                                            SELECT 
                                                trf.*,
                                                room_status.reservation_room_id as rr_id 
                                            FROM
                                                traveller_folio trf 
                                                inner join folio ON folio.id = trf.folio_id
                                                left join room_status ON (room_status.id = trf.invoice_id and trf.type=\'ROOM\')
                                            WHERE
                                                '.$cond_dn.'
        ');
        //System::debug($get_reservation_room);
        $check_rr_id = '';
        $r_r_id = '';
        $check_deposit = 1;
        foreach($get_reservation_room as $key => $value)
        {
            if($value['type'] != 'DEPOSIT_GROUP')
            {
                if($value['rr_id'] == '')
                {
                    if($check_rr_id != $value['reservation_room_id'])
                    {
                        if($r_r_id == '')
                        {
                            $r_r_id = $value['reservation_room_id'];
                        }else
                        {
                            $r_r_id .= ','.$value['reservation_room_id'];                        
                        }                    
                    }
                    $check_rr_id = $value['reservation_room_id'];
                }else
                {
                    if($check_rr_id != $value['rr_id'])
                    {
                        if($r_r_id == '')
                        {
                            $r_r_id = $value['rr_id'];
                        }else
                        {
                            $r_r_id .= ','.$value['rr_id'];                        
                        }                    
                    }
                    $check_rr_id = $value['rr_id'];
                }
                $check_deposit = 0;
            }
        }
        if(User::id()=='developer14')
        {
            //System::debug($r_r_id);
        }
        $reservation_rooms_1 = $this->get_reservation_room($reservation_id,$r_r_id);
        //System::debug($reservation_rooms_1);
        $check_arrival_time ='';
        $check_departure_time ='';
		foreach($reservation_rooms_1 as $k=> $room)
        {
			$this->map['customer_name'] = $room['customer_name'];
            $this->map['guest'] = $room['customer_def_name']?$room['customer_name'].' - '.$room['customer_def_name']:$room['customer_name'];
			$this->map['tour_name'] = $room['tour_name'];
			$this->map['address'] = $room['address'];
            if($check_departure_time == '' || $check_departure_time < Date_Time::to_time($room['departure_time']))
            {
                $this->map['departure_time'] = $room['departure_time'];                
            }
            if($check_arrival_time == '' || Date_Time::to_time($room['arrival_time']) < $check_arrival_time)
            {
                $this->map['arrival_time'] = $room['arrival_time'];
            }
            $check_arrival_time = Date_Time::to_time($room['arrival_time']);
            $check_departure_time = Date_Time::to_time($room['departure_time']);
		}
        if($check_deposit == 1)
        {
            $cond_n = ($folio_id!='')?'id='.$folio_id.'':'1>0';
            $check_folio = DB::fetch('select * from folio where '.$cond_n.' ');
            if(empty($check_folio['reservation_traveller_id']))
            {
                $this->map['arrival_time'] = '';
                $this->map['departure_time'] = '';
            }else
            {
                $r_room = DB::fetch('
                                SELECT 
                                    reservation_room.id,
                                    TO_CHAR(reservation_room.arrival_time, \'DD/MM/YYYY\') as arrival_time,
                                    TO_CHAR(reservation_room.departure_time, \'DD/MM/YYYY\') as departure_time
                                FROM
                                    reservation_room 
                                    inner join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id 
                                WHERE
                                    reservation_traveller.id =\''.$check_folio['reservation_traveller_id'].'\'
                ');
                $this->map['arrival_time'] = $r_room['arrival_time'];
                $this->map['departure_time'] = $r_room['departure_time'];
            }
        }
        /** Daund thêm 06/06/2018 do time io ko dúng */
        $cond ='';
		$cond .= ($folio_id!='')?' AND trf.folio_id='.$folio_id.'':'';
		$cond .= ($reservation_traveller_id!='')?' AND trf.reservation_traveller_id='.$reservation_traveller_id.'':'';
		$sql =' SELECT 
					   trf.*
                       ,(
                       CASE 
                        WHEN housekeeping_invoice.code is not null THEN housekeeping_invoice.code
                        ELSE extra_service_invoice.code
                       END
                       ) as hk_code
                       ,housekeeping_invoice.position
                       ,bar_reservation.code
					   ,trf.reservation_room_id as rr_id 
					   ,reservation_room.time_in
					   ,reservation_room.time_out
                       ,reservation_room.arrival_time as arrival_time
                       ,reservation_room.departure_time as departure_time
                       ,reservation_room.service_rate as rr_service_rate
                       ,reservation_room.tax_rate as rr_tax_rate
                       ,extra_service_invoice_detail.note as ex_note
                       ,extra_service_invoice_detail.invoice_id as ex_id
                       ,extra_service_invoice.bill_number as ex_bill
                       ,extra_service.code as ex_code
                       ,extra_service.name as ex_name
                       ,room.name as room_name                       
					from 
					   traveller_folio trf 
					   inner join folio ON folio.id = trf.folio_id                           
					   left join reservation_room ON (reservation_room.id = trf.reservation_room_id and reservation_room.reservation_id = '.$reservation_id.')
                       left join room on reservation_room.room_id = room.id
                       left join bar_reservation ON bar_reservation.id = trf.invoice_id and trf.type=\'BAR\'
                       left join housekeeping_invoice on housekeeping_invoice.id = trf.invoice_id and (trf.type=\'MINIBAR\' or trf.type=\'LAUNDRY\' or trf.type=\'EQUIPMENT\')
                       left join extra_service_invoice_detail on extra_service_invoice_detail.id = trf.invoice_id and trf.type=\'EXTRA_SERVICE\'
                       left join extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
                       left join extra_service on extra_service_invoice_detail.service_id = extra_service.id
 					where 1>0 '.$cond.'
					 order by trf.date_use,trf.type '; 
                      
		$traveller_folios = DB::fetch_all($sql); 
                              
		foreach($traveller_folios as $key => $value)
        {   
            // $value['type'] == 'LAUNDRY'?$traveller_folios[$key]['description_'] = $value['room_name'] .' '. Portal::language('laundry'):'';
            // $value['type'] == 'ROOM'?$traveller_folios[$key]['description_'] = $value['room_name'] .' '. Portal::language('room_charge'):'';
            // $value['type'] == 'EQUIPMENT'?$traveller_folios[$key]['description_'] = $value['room_name'] .' '. Portal::language('equipment'):'';
            // $value['type'] == 'EXTRA_SERVICE'?$traveller_folios[$key]['description_'] = $value['room_name'] .' '. Portal::language('extra_service'):'';
            // $value['type'] == 'MINIBAR'?$traveller_folios[$key]['description_'] = $value['room_name'] .' '. 'Minibar':'';
            // $value['type'] == 'TELEPHONE'?$traveller_folios[$key]['description_'] = $value['room_name'] .' '. 'Telephone':'';
            // $value['type'] == 'BAR'?$traveller_folios[$key]['description_'] = $value['room_name'] .' '. 'Bar':'';
            
            if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='DISCOUNT')
            {
                $traveller_folios[$key]['product'] = $this->get_product($value['invoice_id']);
                $traveller_folios[$key]['service_amount'] = $value['amount'] * $value['service_rate']/100;
                $traveller_folios[$key]['tax_amount'] = $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                $traveller_folios[$key]['total_amount'] = $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
            }
            else if($value['type']=='DISCOUNT')
            {
                $traveller_folios[$key]['service_amount'] = $value['amount'] * $value['rr_service_rate']/100;
                $traveller_folios[$key]['tax_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * $value['rr_tax_rate']/100;
                $traveller_folios[$key]['total_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * (1+$value['rr_tax_rate']/100);
            }
            else
            {
                $traveller_folios[$key]['total_amount'] = $value['amount'];
            }
            switch ($value['type'])
            {                
                case 'DEPOSIT' : $this->map['total_deposit'] += $traveller_folios[$key]['total_amount']; break;
                case 'DEPOSIT_GROUP' : $this->map['total_group_deposit'] += $traveller_folios[$key]['total_amount']; break;
                case 'DISCOUNT' : 
                    $this->map['total_amount'] -= $traveller_folios[$key]['total_amount']; 
                    //if($value['foc'] or $value['foc_all']) $this->map['total_foc'] -= $traveller_folios[$key]['total_amount'];
                    break;
                default : 
                    $this->map['total_amount'] += $traveller_folios[$key]['total_amount']; 
                    //if(($value['type'] == 'ROOM' and $value['foc']) or $value['foc_all']) $this->map['total_foc'] += $traveller_folios[$key]['total_amount'];
                    break;                   
            }                              
        }
        $this->map['reservation_id'] = $reservation_id;
        $this->map['total_payment'] = $this->map['total_amount'] - $this->map['total_group_deposit'] - $this->map['total_deposit'] - $this->map['total_foc'];
        $this->map['items'] = $traveller_folios;
        //System::debug($traveller_folios);
        /** daund start gom lai de in rut gon */
        foreach($traveller_folios as $key => $value)
        {
            if($value['type'] == 'ROOM' || $value['ex_code'] == 'LATE_CHECKIN' || $value['ex_code'] == 'EARLY_CHECKIN' || $value['ex_code'] == 'LATE_CHECKOUT')
            {
                $traveller_folios[$key]['type'] = 'ROOM_CHARGE';
            }
        }
        $room_charge = array();
        $minibar_laundary = array();
        $extra_service = array();
        $i=1;
        /**gom room charge, Minibar, laundary, dvmr, bar*/
        //System::debug($traveller_folios);
        ksort($traveller_folios);
        foreach($traveller_folios as $key => $value)
        {               
            if($value['type'] == 'ROOM_CHARGE')
            {
                $id_room_charge = 'ROOM_CHARGE_' . $value['reservation_room_id'] .'_'.$value['type'];
                if(!isset($room_charge[$id_room_charge]['id']))
                {
                    $room_charge[$id_room_charge]['id'] = $id_room_charge;
                    $room_charge[$id_room_charge]['description'] = $value['room_name'] .' '.Portal::language('room_charge');
                    $room_charge[$id_room_charge]['amount'] = $value['amount']; 
                    if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='DISCOUNT')
                    {
                        $room_charge[$id_room_charge]['service_amount'] = $value['amount'] * $value['service_rate']/100;
                        $room_charge[$id_room_charge]['tax_amount'] = $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                        $room_charge[$id_room_charge]['total_amount'] = $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
                    }
                    else if($value['type']=='DISCOUNT')
                    {
                        $room_charge[$id_room_charge]['service_amount'] = $value['amount'] * $value['rr_service_rate']/100;
                        $room_charge[$id_room_charge]['tax_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * $value['rr_tax_rate']/100;
                        $room_charge[$id_room_charge]['total_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * (1+$value['rr_tax_rate']/100);
                    }
                    else
                    {
                        $room_charge[$id_room_charge]['total_amount'] = $value['amount'];
                    }                  
                }else
                {                    
                    $room_charge[$id_room_charge]['amount'] += $value['amount'];
                    if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='DISCOUNT')
                    {
                        $room_charge[$id_room_charge]['service_amount'] += $value['amount'] * $value['service_rate']/100;
                        $room_charge[$id_room_charge]['tax_amount'] += $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                        $room_charge[$id_room_charge]['total_amount'] += $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
                    }
                    else if($value['type']=='DISCOUNT')
                    {
                        $room_charge[$id_room_charge]['service_amount'] += $value['amount'] * $value['rr_service_rate']/100;
                        $room_charge[$id_room_charge]['tax_amount'] += $value['amount'] * (1+$value['rr_service_rate']/100) * $value['rr_tax_rate']/100;
                        $room_charge[$id_room_charge]['total_amount'] += $value['amount'] * (1+$value['rr_service_rate']/100) * (1+$value['rr_tax_rate']/100);
                    }
                    else
                    {
                        $room_charge[$id_room_charge]['total_amount'] += $value['amount'];
                    }                      
                }
            }
            if($value['type'] == 'BAR')
            {
                $id_bar = 'BAR_' . $value['reservation_room_id'] .'_'.$value['type'];
                if(!isset($room_charge[$id_bar]['id']))
                {
                    $room_charge[$id_bar]['id'] = $id_bar;
                    $room_charge[$id_bar]['description'] = $value['room_name'] . ' ' . $value['type'];
                    $room_charge[$id_bar]['amount'] = $value['amount']; 
                    if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='DISCOUNT')
                    {
                        $room_charge[$id_bar]['service_amount'] = $value['amount'] * $value['service_rate']/100;
                        $room_charge[$id_bar]['tax_amount'] = $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                        $room_charge[$id_bar]['total_amount'] = $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
                    }
                    else if($value['type']=='DISCOUNT')
                    {
                        $room_charge[$id_bar]['service_amount'] = $value['amount'] * $value['rr_service_rate']/100;
                        $room_charge[$id_bar]['tax_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * $value['rr_tax_rate']/100;
                        $room_charge[$id_bar]['total_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * (1+$value['rr_tax_rate']/100);
                    }
                    else
                    {
                        $room_charge[$id_bar]['total_amount'] = $value['amount'];
                    }
                }else
                {
                    $room_charge[$id_bar]['amount'] += $value['amount']; 
                    if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='DISCOUNT')
                    {
                        $room_charge[$id_bar]['service_amount'] += $value['amount'] * $value['service_rate']/100;
                        $room_charge[$id_bar]['tax_amount'] += $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                        $room_charge[$id_bar]['total_amount'] += $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
                    }
                    else if($value['type']=='DISCOUNT')
                    {
                        $room_charge[$id_bar]['service_amount'] += $value['amount'] * $value['rr_service_rate']/100;
                        $room_charge[$id_bar]['tax_amount'] += $value['amount'] * (1+$value['rr_service_rate']/100) * $value['rr_tax_rate']/100;
                        $room_charge[$id_bar]['total_amount'] += $value['amount'] * (1+$value['rr_service_rate']/100) * (1+$value['rr_tax_rate']/100);
                    }
                    else
                    {
                        $room_charge[$id_bar]['total_amount'] += $value['amount'];
                    }                    
                }                      
            }
            if($value['type'] == 'MINIBAR')
            {
                $id_minibar = 'MINIBAR_' . $value['reservation_room_id'] .'_'.$value['type'];
                if(!isset($room_charge[$id_minibar]['id']))
                {
                    $room_charge[$id_minibar]['id'] = $id_minibar;
                    $room_charge[$id_minibar]['description'] = $value['room_name'] . ' ' . $value['type'];
                    $room_charge[$id_minibar]['amount'] = $value['amount']; 
                    if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='DISCOUNT')
                    {
                        $room_charge[$id_minibar]['service_amount'] = $value['amount'] * $value['service_rate']/100;
                        $room_charge[$id_minibar]['tax_amount'] = $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                        $room_charge[$id_minibar]['total_amount'] = $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
                    }
                    else if($value['type']=='DISCOUNT')
                    {
                        $room_charge[$id_minibar]['service_amount'] = $value['amount'] * $value['rr_service_rate']/100;
                        $room_charge[$id_minibar]['tax_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * $value['rr_tax_rate']/100;
                        $room_charge[$id_minibar]['total_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * (1+$value['rr_tax_rate']/100);
                    }
                    else
                    {
                        $room_charge[$id_minibar]['total_amount'] = $value['amount'];
                    }           
                }else
                {
                    $room_charge[$id_minibar]['amount'] += $value['amount']; 
                    if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='DISCOUNT')
                    {
                        $room_charge[$id_minibar]['service_amount'] += $value['amount'] * $value['service_rate']/100;
                        $room_charge[$id_minibar]['tax_amount'] += $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                        $room_charge[$id_minibar]['total_amount'] += $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
                    }
                    else if($value['type']=='DISCOUNT')
                    {
                        $room_charge[$id_minibar]['service_amount'] += $value['amount'] * $value['rr_service_rate']/100;
                        $room_charge[$id_minibar]['tax_amount'] += $value['amount'] * (1+$value['rr_service_rate']/100) * $value['rr_tax_rate']/100;
                        $room_charge[$id_minibar]['total_amount'] += $value['amount'] * (1+$value['rr_service_rate']/100) * (1+$value['rr_tax_rate']/100);
                    }
                    else
                    {
                        $room_charge[$id_minibar]['total_amount'] += $value['amount'];
                    }                       
                }             
            }
            if($value['type'] == 'LAUNDRY')
            {
                $id_laundry = 'LAUNDRY_' . $value['reservation_room_id'] .'_'.$value['type'];
                if(!isset($room_charge[$id_laundry]['id']))
                {
                    $room_charge[$id_laundry]['id'] = $id_laundry;
                    $room_charge[$id_laundry]['description'] = $value['room_name'] . ' ' . Portal::language('laundry');
                    $room_charge[$id_laundry]['amount'] = $value['amount']; 
                    if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='DISCOUNT')
                    {
                        $room_charge[$id_laundry]['service_amount'] = $value['amount'] * $value['service_rate']/100;
                        $room_charge[$id_laundry]['tax_amount'] = $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                        $room_charge[$id_laundry]['total_amount'] = $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
                    }
                    else if($value['type']=='DISCOUNT')
                    {
                        $room_charge[$id_laundry]['service_amount'] = $value['amount'] * $value['rr_service_rate']/100;
                        $room_charge[$id_laundry]['tax_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * $value['rr_tax_rate']/100;
                        $room_charge[$id_laundry]['total_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * (1+$value['rr_tax_rate']/100);
                    }
                    else
                    {
                        $room_charge[$id_laundry]['total_amount'] = $value['amount'];
                    }           
                }else
                {
                    $room_charge[$id_laundry]['amount'] += $value['amount']; 
                    if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='DISCOUNT')
                    {
                        $room_charge[$id_laundry]['service_amount'] += $value['amount'] * $value['service_rate']/100;
                        $room_charge[$id_laundry]['tax_amount'] += $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                        $room_charge[$id_laundry]['total_amount'] += $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
                    }
                    else if($value['type']=='DISCOUNT')
                    {
                        $room_charge[$id_laundry]['service_amount'] += $value['amount'] * $value['rr_service_rate']/100;
                        $room_charge[$id_laundry]['tax_amount'] += $value['amount'] * (1+$value['rr_service_rate']/100) * $value['rr_tax_rate']/100;
                        $room_charge[$id_laundry]['total_amount'] += $value['amount'] * (1+$value['rr_service_rate']/100) * (1+$value['rr_tax_rate']/100);
                    }
                    else
                    {
                        $room_charge[$id_laundry]['total_amount'] += $value['amount'];
                    }                       
                }             
            }
            if($value['type'] == 'EQUIPMENT')
            {
                $id_equipment = 'EQUIPMENT_' . $value['reservation_room_id'] .'_'.$value['type'];
                if(!isset($room_charge[$id_equipment]['id']))
                {
                    $room_charge[$id_equipment]['id'] = $id_equipment;
                    $room_charge[$id_equipment]['description'] = $value['room_name'] . ' ' . Portal::language('equipment');
                    $room_charge[$id_equipment]['amount'] = $value['amount']; 
                    if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='DISCOUNT')
                    {
                        $room_charge[$id_equipment]['service_amount'] = $value['amount'] * $value['service_rate']/100;
                        $room_charge[$id_equipment]['tax_amount'] = $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                        $room_charge[$id_equipment]['total_amount'] = $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
                    }
                    else if($value['type']=='DISCOUNT')
                    {
                        $room_charge[$id_equipment]['service_amount'] = $value['amount'] * $value['rr_service_rate']/100;
                        $room_charge[$id_equipment]['tax_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * $value['rr_tax_rate']/100;
                        $room_charge[$id_equipment]['total_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * (1+$value['rr_tax_rate']/100);
                    }
                    else
                    {
                        $room_charge[$id_equipment]['total_amount'] = $value['amount'];
                    }           
                }else
                {
                    $room_charge[$id_equipment]['amount'] += $value['amount']; 
                    if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='DISCOUNT')
                    {
                        $room_charge[$id_equipment]['service_amount'] += $value['amount'] * $value['service_rate']/100;
                        $room_charge[$id_equipment]['tax_amount'] += $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                        $room_charge[$id_equipment]['total_amount'] += $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
                    }
                    else if($value['type']=='DISCOUNT')
                    {
                        $room_charge[$id_equipment]['service_amount'] += $value['amount'] * $value['rr_service_rate']/100;
                        $room_charge[$id_equipment]['tax_amount'] += $value['amount'] * (1+$value['rr_service_rate']/100) * $value['rr_tax_rate']/100;
                        $room_charge[$id_equipment]['total_amount'] += $value['amount'] * (1+$value['rr_service_rate']/100) * (1+$value['rr_tax_rate']/100);
                    }
                    else
                    {
                        $room_charge[$id_equipment]['total_amount'] += $value['amount'];
                    }                       
                }             
            }
            if($value['type'] == 'EXTRA_SERVICE')
            {
                $id_extra_service = 'EXTRA_SERVICE_' . $value['reservation_room_id'] .'_'.$value['ex_code'];
                if(!isset($room_charge[$id_extra_service]['id']))
                {
                    $room_charge[$id_extra_service]['id'] = $id_extra_service;
                    $room_charge[$id_extra_service]['description'] = $value['room_name'] . ' ' . $value['ex_name'];
                    $room_charge[$id_extra_service]['amount'] = $value['amount']; 
                    if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='DISCOUNT')
                    {
                        $room_charge[$id_extra_service]['service_amount'] = $value['amount'] * $value['service_rate']/100;
                        $room_charge[$id_extra_service]['tax_amount'] = $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                        $room_charge[$id_extra_service]['total_amount'] = $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
                    }
                    else if($value['type']=='DISCOUNT')
                    {
                        $room_charge[$id_extra_service]['service_amount'] = $value['amount'] * $value['rr_service_rate']/100;
                        $room_charge[$id_extra_service]['tax_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * $value['rr_tax_rate']/100;
                        $room_charge[$id_extra_service]['total_amount'] = $value['amount'] * (1+$value['rr_service_rate']/100) * (1+$value['rr_tax_rate']/100);
                    }
                    else
                    {
                        $room_charge[$id_extra_service]['total_amount'] = $value['amount'];
                    }           
                }else
                {
                    $room_charge[$id_extra_service]['amount'] += $value['amount']; 
                    if($value['type']!='DEPOSIT' and $value['type']!='DEPOSIT_GROUP' and $value['type']!='DISCOUNT')
                    {
                        $room_charge[$id_extra_service]['service_amount'] += $value['amount'] * $value['service_rate']/100;
                        $room_charge[$id_extra_service]['tax_amount'] += $value['amount'] * (1+$value['service_rate']/100) * $value['tax_rate']/100;
                        $room_charge[$id_extra_service]['total_amount'] += $value['amount'] * (1+$value['service_rate']/100) * (1+$value['tax_rate']/100);
                    }
                    else if($value['type']=='DISCOUNT')
                    {
                        $room_charge[$id_extra_service]['service_amount'] += $value['amount'] * $value['rr_service_rate']/100;
                        $room_charge[$id_extra_service]['tax_amount'] += $value['amount'] * (1+$value['rr_service_rate']/100) * $value['rr_tax_rate']/100;
                        $room_charge[$id_extra_service]['total_amount'] += $value['amount'] * (1+$value['rr_service_rate']/100) * (1+$value['rr_tax_rate']/100);
                    }
                    else
                    {
                        $room_charge[$id_extra_service]['total_amount'] += $value['amount'];
                    }                       
                }             
            }
            
        }
        $this->map['items_short'] = $room_charge;
        /**gom room charge: bao gồm tiền phong, EI,LO, LI*/
        /** daund end gom lai de in rut gon */
		return $this->map;  
	}
	function draw()
    {   
		$this->map= array();
		if(Url::get('folio_id')){
			$folio_id = Url::get('folio_id');
            $folios = DB::fetch('select traveller_folio.* , folio.code as folio_code
                                 from traveller_folio 
                                      inner join folio on folio.id = traveller_folio.folio_id
                                 where traveller_folio.folio_id='.$folio_id.'');
            if(User::id()=='developer12')
            {
               // System::debug($folios);
            }
           
            
           $this->map['folio_code']  = $folios['folio_code'];
           
            //System::debug($value['folio_code']);
            $reservation_traveller_id = $folios['reservation_traveller_id'];
			$reservation_id = Url::get('id')?Url::get('id'):$folios['reservation_id'];
			$traveller_folio = DB::fetch('select * from folio where id='.$folio_id.'');
            $create_folio_user = DB::fetch('select name_1 as name from party where user_id=\''.$traveller_folio['user_id'].'\'');
            $this->map['create_folio_user'] = $create_folio_user['name'];
            $this->map['create_folio_time'] = date('H:i d/m/Y',$traveller_folio['create_time']);
            if(empty($traveller_folio['reservation_traveller_id'])){
                $this->map['traveller_name'] = "";
            }else{
                $traveller_name = DB::fetch("select traveller.id as id, 
                                                concat(concat(traveller.first_name,' '),traveller.last_name) as full_name 
                                            from traveller 
                                                inner join reservation_traveller on reservation_traveller.traveller_id = traveller.id 
                                            where reservation_traveller.id =".$traveller_folio['reservation_traveller_id']);
                $this->map['traveller_name'] = $traveller_name['full_name'];
            }
            
			$this->get_items($reservation_traveller_id,$reservation_id,$folio_id);
            
            //start: KID them 2 dong duoi tinh so tien da thanh toan
            $total_payment = DB::fetch('select sum(amount) as amount,SUM(amount*payment.exchange_rate) as total_vnd from payment where folio_id ='.$folio_id);
            $this->map['total_payment_vnd'] =  $total_payment['total_vnd'];
            $this->map['total_payment1'] =  $total_payment['amount'];
			//end : KID
            
            // Ph?n lo?i ti?n thanh to?n:
			$sql = 'SELECT 
								(payment.payment_type_id || \'_\' || payment.credit_card_id || \'_\' || payment.currency_id || \'_\' || payment.folio_id || \'_\' || payment.description ) as id
								,SUM(amount) as total
								,SUM(payment.bank_fee) as bank_fee
								,SUM(amount*payment.exchange_rate) as total_vnd
								,CONCAT(payment.payment_type_id,CONCAT(\'_\',payment.currency_id)) as name
								,payment.bill_id
								,payment.folio_id
								,payment.payment_type_id
								,payment.credit_card_id
								,payment.currency_id 
								,credit_card.name as credit_card_name
                                ,payment.bank_acc
                                ,payment.description
                                ,payment.payment_point    
							FROM payment
								inner join reservation on payment.bill_id = reservation.id
								left outer join credit_card ON credit_card.id = payment.credit_card_id
								left outer join folio ON folio.id = payment.folio_id
							WHERE 
								1>0 AND payment.bill_id = '.$reservation_id.' AND payment.folio_id = '.$folio_id.'
								AND payment.type_dps is null
							GROUP BY
                                 payment.payment_type_id,
                                 payment.currency_id,
                                 payment.bill_id,
                                 payment.folio_id,
                                 payment.credit_card_id,
                                 credit_card.name,
                                 payment.bank_acc,
                                payment.description,
                                payment.payment_point
							ORDER BY payment.payment_type_id ASC
											';
			$payments = DB::fetch_all($sql);	
            $this->map['payments'] = $payments;	
            //if(User::id() == 'developer04'){
                //System::debug($this->map);	
                //exit();
            //}
            $account_name = DB::fetch("SELECT account.id as id, party.name_1 as name FROM account inner join party on party.user_id = account.id WHERE account.id='".User::id()."'");
            $this->map['account_name'] = $account_name['name'];            
            $member_code = DB::fetch("SELECT folio.member_code,member_level.def_name as member_level FROM folio left join member_level on member_level.id=folio.member_level_id WHERE folio.id=".Url::get('folio_id'));
            if(sizeof($member_code)>0)
            {
                require_once 'packages/hotel/includes/member.php';
                $this->map['member_code'] = $member_code['member_code'];
                $this->map['member_level'] = $member_code['member_level'];
                $this->map['point'] = 0;
                $this->map['point_user'] = 0;
                foreach($payments as $id=>$value)
                {
                    if(isset($value['payment_point']) AND ($value['payment_point']=='on'))
                    {
                        $payment_point = 1;
                    }else
                    {
                        $payment_point = 0;
                    }
                    $arr_point = point($value['payment_type_id'],$value['total'],$payment_point);
                    $this->map['point'] += $arr_point['point'];
                    $this->map['point_user'] += $arr_point['point_user'];   
                }
            }else
            {
                $this->map['member_code'] = '';
                $this->map['member_level'] = '';
            }                                    
            /** check hoa don thanh toan de ghi tieu de **/
            $this->map['check_payment'] = 0;
            if($traveller_folio['total']<=0 OR DB::exists('select id from payment where folio_id='.$folio_id.' and type_dps is null')){
                $this->map['check_payment'] = 1;
            }
            $this->parse_layout('group_invoice',$this->map);
		}
	}
	function get_reservation_room($reservation_id,$r_r_id){
	    $cond = ' AND 1=1';
        if($r_r_id != '')
        {
            $cond .= ' AND reservation_room.id in('.$r_r_id.')';
        }
		return $reservation_rooms = DB::fetch_all('SELECT
				reservation_room.id,
				reservation_room.arrival_time,
				to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') as arrival_time, 
				to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as departure_time,
				(reservation_room.departure_time-reservation_room.arrival_time) as night, 
				reservation_room.time_in, reservation_room.time_out,
				reservation_room.room_id,
                reservation_room.price as room_price,
				room.name as room_name,
				concat(traveller.first_name,concat(\' \', traveller.last_name)) as full_name, 
				customer.address, customer.name as customer_name,customer.def_name as customer_def_name, 
				reservation_room.foc,
				reservation_room.foc_all,
				reservation_room.service_rate,
				reservation_room.tax_rate,
				tour.name as tour_name,
				customer.address
			FROM reservation_room
				INNER JOIN reservation ON reservation_room.reservation_id = reservation.id
				INNER JOIN room ON room.id = reservation_room.room_id
				left outer join customer ON customer.id = reservation.customer_id
				left outer join tour ON tour.id = reservation.tour_id
				left outer join reservation_traveller ON reservation_traveller.reservation_room_id = reservation_room.id
				left outer join traveller ON reservation_traveller.traveller_id = traveller.id
			WHERE 
                reservation.id ='.$reservation_id.'
                '.$cond.'
		 ');
	}
    function get_product($invoice_id)
    {
        $reservation_rooms = DB::fetch_all('
                    SELECT
                        housekeeping_invoice_detail.id
                        ,product.name_'.Portal::language().' as name
                        ,housekeeping_invoice_detail.price
                        ,housekeeping_invoice_detail.quantity
                        ,housekeeping_invoice_detail.price*housekeeping_invoice_detail.quantity as total_product
                    FROM 
                        housekeeping_invoice_detail
                        left join product on housekeeping_invoice_detail.product_id = product.id
                    WHERE 
                        housekeeping_invoice_detail.invoice_id ='.$invoice_id.'
        ');        
        foreach($reservation_rooms as $key => $value)
        {
            if($value['quantity'] == 0)
            {
                unset($reservation_rooms[$key]);
            }
        }
        return $reservation_rooms;
	}
}
?>