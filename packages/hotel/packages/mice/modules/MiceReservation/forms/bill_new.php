<?php
class BillNewMiceReservationForm extends Form
{
	function BillNewMiceReservationForm()
    {
		Form::Form('BillNewMiceReservationForm');
	}
	function draw()
    {
        $this->map = array();
        $invoice = DB::fetch('
                    SELECT
                        mice_invoice.*,
                        NVL(mice_invoice.extra_vat,0) as extra_vat,
                        to_char(mice_reservation.start_date, \'DD/MM/YYYY\') as start_date,
                        to_char(mice_reservation.end_date, \'DD/MM/YYYY\') as end_date,
                        traveller.first_name || \' \' || traveller.last_name as traveller_name,
                        customer.name as customer_name,
                        customer.address as customer_address
                    FROM
                        mice_invoice
                        left join reservation_traveller on reservation_traveller.id=mice_invoice.reservation_traveller_id
                        left join traveller on traveller.id=reservation_traveller.traveller_id
                        inner join mice_reservation on mice_reservation.id=mice_invoice.mice_reservation_id
                        left join customer on customer.id=mice_reservation.customer_id
                    WHERE
                        mice_invoice.id=\''.Url::get('invoice_id').'\'
        ');
        $this->map +=$invoice;
        $invoice_detail = DB::fetch_all("
                                SELECT
                                    mice_invoice_detail.*,
                                    mice_invoice_detail.type || '_' || mice_invoice_detail.invoice_id as id,
                                    mice_invoice_detail.id as mice_invoice_detail_id,
                                    TO_CHAR(mice_invoice_detail.date_use,'DD/MM/YYYY') as date_use,
                                    mice_invoice.id as mice_invoice_id,
                                    mice_invoice.payment_time,
                                    mice_invoice.bill_id,
                                    mice_invoice.extra_vat,
                                    mice_invoice.extra_amount,
                                    mice_invoice.reservation_traveller_id,
                                    mice_invoice_detail.type,
                                    reservation.id as r_id,
                                    room_status.reservation_room_id as rr_id,
                                    extra_service_invoice.reservation_room_id as ex_rr_id,
                                    extra_service_invoice_detail.quantity as ex_quantity,
                                    extra_service_invoice_detail.change_quantity as ex_change_quantity,
                                    housekeeping_invoice.reservation_room_id as hs_rr_id,
                                    room.name as room_name,
                                    extra_service.code as ex_code,
                                    extra_service.name as ex_name,
                                    extra_service.unit as unit_name
                                FROM
                                    mice_invoice_detail
                                    inner join mice_invoice on mice_invoice.id=mice_invoice_detail.mice_invoice_id
                                    inner join mice_reservation on mice_reservation.id = mice_invoice.mice_reservation_id
                                    left join room_status on room_status.id = mice_invoice_detail.invoice_id and mice_invoice_detail.type ='ROOM'
                                    left join reservation_room on reservation_room.id = room_status.reservation_room_id
                                    left join reservation on reservation_room.reservation_id = reservation.id
                                    left join room on reservation_room.room_id = room.id 
                                    left join extra_service_invoice_detail on extra_service_invoice_detail.id = mice_invoice_detail.invoice_id and mice_invoice_detail.type='EXTRA_SERVICE'
                                    left join extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
                                    left join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                                    left join housekeeping_invoice on housekeeping_invoice.id = mice_invoice_detail.invoice_id and (mice_invoice_detail.type ='MINIBAR' or mice_invoice_detail.type ='LAUNDRY' or mice_invoice_detail.type ='EQUIPMENT')
                                WHERE
                                    mice_invoice.id=".Url::get('invoice_id')."
                                ORDER BY
                                    room.name ASC
        ");
        $items = array();
        $this->map['service_charge'] = 0;
        $this->map['vat_charge'] = 0;
        $this->map['sub_total'] = 0;
        $this->map['discount'] = 0;
        $this->map['deposit'] = 0;
        foreach($invoice_detail as $key => $value)
        {
            /** Tạo mảng phát sinh từ phòng và nhà hàng. */
            if(($value['key'] == 'REC' or $value['key'] == 'EXS') && $value['type'] != 'DEPOSIT')
            {
                /** Tiền phòng gom theo từng phòng, sắp xếp theo số phòng từ nhỏ đến lớn. */
                if($value['type'] == 'ROOM')
                {
                    $id = $value['r_id'].'_'.$value['rr_id'].'_'.$value['amount'];
                    if(!isset($items[$id]['id']))
                    {
                        $items[$id]['id'] = $id;
                        $items[$id]['description'] = $value['room_name'] . ' Room charge';
                        $items[$id]['unit'] = Portal::language('night_room');
                        $items[$id]['quantity'] = 1;
                        $items[$id]['price_before_tax'] = $value['amount'];
                        $items[$id]['tax_amount'] = $value['tax_amount'];
                        $items[$id]['service_amount'] = $value['service_amount'];
                        $items[$id]['total_before_tax'] = $value['total_amount'];
                    }else
                    {
                        $items[$id]['quantity']++;
                        $items[$id]['total_before_tax'] += $value['total_amount'];
                        $items[$id]['tax_amount'] += $value['tax_amount'];
                        $items[$id]['service_amount'] += $value['service_amount'];                
                    }
                }
                /** Gom DVMR. */
                if($value['type'] == 'EXTRA_SERVICE')
                {
                    $id = $value['ex_code'];
                    if(!isset($items[$id]['id']))
                    {
                        $items[$id]['id'] = $id;
                        $items[$id]['description'] = $value['ex_name'];
                        $items[$id]['unit'] = $value['unit_name'];
                        $items[$id]['quantity'] = 1;
                        $items[$id]['price_before_tax'] = $value['amount'];
                        $items[$id]['tax_amount'] = $value['tax_amount'];
                        $items[$id]['service_amount'] = $value['service_amount'];
                        $items[$id]['total_before_tax'] = $value['total_amount'];                        
                    }else
                    {
                        $items[$id]['quantity'] ++;
                        $items[$id]['total_before_tax'] += $value['total_amount'];
                        $items[$id]['tax_amount'] += $value['tax_amount'];
                        $items[$id]['service_amount'] += $value['service_amount'];
                    }
                }
                /** Gom Minibar, Giặt là, Đền bù. */
                if($value['type'] == 'MINIBAR' || $value['type'] == 'LAUNDRY' || $value['type'] == 'EQUIPMENT')
                {
                    $room_name = DB::fetch('SELECT room.name as room_name FROM room inner join reservation_room on reservation_room.room_id = room.id WHERE reservation_room.id =\''.$value['hs_rr_id'].'\'');
                    $id = $value['hs_rr_id'].'_'.$room_name['room_name'].'_'.$value['type'];
                    if(!isset($items[$id]['id']))
                    {
                        $items[$id]['id'] = $id;
                        $items[$id]['description'] = $room_name['room_name']. ' '.$value['type'];
                        $items[$id]['unit'] = '';
                        $items[$id]['quantity'] = '';
                        $items[$id]['price_before_tax'] = $value['amount'];
                        $items[$id]['tax_amount'] = $value['tax_amount'];
                        $items[$id]['service_amount'] = $value['service_amount'];
                        $items[$id]['total_before_tax'] = $value['total_amount'];                        
                    }else
                    {
                        $items[$id]['total_before_tax'] += $value['total_amount'];
                        $items[$id]['tax_amount'] += $value['tax_amount'];
                        $items[$id]['service_amount'] += $value['service_amount'];
                    }
                }
                /** Gom Bar. */
                if($value['type'] == 'BAR')
                {
                    $id = $value['id'].'_'.$value['type'];
                    if(!isset($items[$id]['id']))
                    {
                        $items[$id]['id'] = $id;
                        $items[$id]['description'] = $value['description'];
                        $items[$id]['unit'] = '';
                        $items[$id]['quantity'] = '';
                        $items[$id]['price_before_tax'] = $value['amount'];
                        $items[$id]['tax_amount'] = $value['tax_amount'];
                        $items[$id]['service_amount'] = $value['service_amount'];
                        $items[$id]['total_before_tax'] = $value['total_amount'];                        
                    }else
                    {
                        $items[$id]['total_before_tax'] += $value['total_amount'];
                        $items[$id]['tax_amount'] += $value['tax_amount'];
                        $items[$id]['service_amount'] += $value['service_amount'];
                    }
                }
            }else
            {
                if($value['type'] != 'DEPOSIT_MICE')
                {
                    $id = $value['id'];
                    if(!isset($items[$id]['id']))
                    {
                        $items[$id]['id'] = $id;
                        $items[$id]['description'] = $value['description'];
                        $items[$id]['unit'] = '';
                        $items[$id]['quantity'] = '';
                        $items[$id]['price_before_tax'] = $value['amount'];
                        $items[$id]['tax_amount'] = $value['tax_amount'];
                        $items[$id]['service_amount'] = $value['service_amount'];
                        $items[$id]['total_before_tax'] = $value['total_amount'];                        
                    }else
                    {
                        $items[$id]['total_before_tax'] += $value['total_amount'];
                        $items[$id]['tax_amount'] += $value['tax_amount'];
                        $items[$id]['service_amount'] += $value['service_amount'];
                    } 
                }               
            }
            if($value['type']=='DEPOSIT_MICE')
            {
                $this->map['deposit'] += $value['amount'];
            }else
            {
                if($value['type']=='DISCOUNT')
                {
                    $this->map['sub_total'] -= $value['total_amount'];
                    $this->map['discount'] += ($value['amount']/(1+($value['tax_rate']/100))/(1+($value['service_rate']/100)));
                    $this->map['service_charge'] -= $value['amount']/(1+($value['tax_rate']/100))/(1+($value['service_rate']/100))*($value['service_rate']/100);
                    $this->map['vat_charge'] -= ($value['amount']/(1+($value['tax_rate']/100))/(1+($value['service_rate']/100)) + $value['amount']/(1+($value['tax_rate']/100))/(1+($value['service_rate']/100))*($value['service_rate']/100))*($value['tax_rate']/100); 
                }else
                {
                    $this->map['service_charge'] += $value['service_amount'];
                    $this->map['vat_charge'] += $value['tax_amount'];
                }
                if($value['type'] != 'DEPOSIT')
                {
                    $this->map['sub_total'] += $value['total_amount'];
                }else
                {
                    $this->map['sub_total'] -= $value['total_amount'];                  
                } 
            }
        }
        
        if(User::id()=='developer14')
        {
            //System::debug($items);
        }
        $this->map['total'] = ($this->map['sub_total'] + $this->map['extra_vat']) - $this->map['discount'];
        $this->map['have_to_pay'] = $this->map['total'] - $this->map['deposit'];
        
        $this->map['items'] = $items;
        // Phân loại tiền thanh toán
		$sql = 'SELECT 
							(payment.payment_type_id || \'_\' || payment.credit_card_id || \'_\' || payment.currency_id || \'_\' || payment.bill_id || \'_\' || payment.description ) as id
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
						FROM 
                            payment
							inner join mice_invoice on payment.bill_id = mice_invoice.id
							left outer join credit_card ON credit_card.id = payment.credit_card_id
						WHERE 
							1>0 AND payment.bill_id = '.Url::get('invoice_id').'
							AND payment.type_dps is null
                            AND payment.type =\'BILL_MICE\'
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
        //System::debug($payments);
        
        $mice_invoice_id = Url::get('invoice_id');
        $mice_invoice = DB::fetch('select * from mice_invoice where id='.$mice_invoice_id.'');
        $create_mice_user = DB::fetch('select full_name as name from party where user_id=\''.$mice_invoice['user_id'].'\'');
        $this->map['create_mice_user'] = $create_mice_user['name'];
        
        $print_person = DB::fetch("SELECT account.id as id, party.full_name as name FROM account inner join party on party.user_id = account.id WHERE account.id='".User::id()."'");
        $this->map['print_person'] = $print_person['name'];
        
        $this->parse_layout('bill_new',$this->map);
    }
}
?>
