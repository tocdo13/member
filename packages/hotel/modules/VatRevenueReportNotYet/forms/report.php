<?php
class VatRevenueReportNotYetForm extends Form
{
	function VatRevenueReportNotYetForm()
	{
		Form::Form('VatRevenueReportNotYetForm');
        $this->link_css('packages/hotel/skins/default/css/font-awesome-4.5.0/css/font-awesome.min.css');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw()
	{
	   $this->map = array();
       
       $cond = '';
       
       if(Url::get('start_date') and Url::get('end_date')) {
            $this->map['start_date'] = Url::get('start_date');
            $this->map['end_date'] = Url::get('end_date');
        }
        else {
            $_REQUEST['start_date'] = '01/'.date('m/Y');
            $_REQUEST['end_date'] = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y')).'/'.date('m/Y');
            $this->map['start_date'] = $_REQUEST['start_date'];
            $this->map['end_date'] = $_REQUEST['end_date'];
        }
       $this->map['type_list'] = array('ALL'=>Portal::language('all'),'BAR'=>Portal::language('restaurant'),'BANQUET'=>Portal::language('party'),'FOLIO'=>Portal::language('reception'),'MICE'=>Portal::language('mice'));
       
        $this->map['room_charge'] = 0;
        $this->map['extra_service_charge_room'] = 0;
        $this->map['breakfast'] = 0;
        $this->map['restaurant'] = 0;
        $this->map['banquet'] = 0;
        $this->map['minibar'] = 0;
        $this->map['laundry'] = 0;
        $this->map['equipment'] = 0;
        $this->map['trans'] = 0;
        $this->map['others'] = 0;
        $this->map['service_rate'] = 0;
        $this->map['total_revenue'] = 0;
        $this->map['tax_rate'] = 0;
        $this->map['total_amount'] = 0;
       
       $items = array();
       
       if(!isset($_REQUEST['type']) or Url::get('type')=='ALL' or Url::get('type')=='FOLIO'){
            $cond = ' 1 = 1  and folio.portal_id = \''.PORTAL_ID.'\'';
            $cond .= '
                        and ( (folio.total=0 and folio.create_time>='.Date_Time::to_time($this->map['start_date']).' 
                                and folio.create_time<='.(Date_Time::to_time($this->map['end_date'])+86399).') 
                            OR (folio.total!=0 and folio.payment_time>='.Date_Time::to_time($this->map['start_date']).' 
                                and folio.payment_time<='.(Date_Time::to_time($this->map['end_date'])+86399).') )';
            
            $sql_folio_foc = '
                SELECT 
                    folio.id as id
                FROM 
                    payment
                    inner join folio on folio.id = payment.folio_id 
                WHERE 
                    payment.payment_type_id = \'FOC\'
            ';
            $folio_foc = DB::fetch_all($sql_folio_foc);
            
            $traveller_folio = DB::fetch_all('
                                            SELECT
                                                traveller_folio.id,
                                                traveller_folio.total_amount,
                                                traveller_folio.id,
                                                traveller_folio.type,
                                                traveller_folio.invoice_id,
                                                traveller_folio.folio_id,
                                                NVL(traveller_folio.total_amount,0) as total_amount,
                                                NVL(traveller_folio.service_rate,0) as service_rate,
                                                NVL(traveller_folio.tax_rate,0) as tax_rate,
                                                folio.id as folio_id,
                                                folio.reservation_id,
                                                folio.total as folio_total,
                                                CASE
                                                    WHEN folio.total=0
                                                    THEN folio.id
                                                    ELSE folio.code 
                                                END folio_code,
                                                folio.reservation_room_id,
                                                folio.reservation_traveller_id,
                                                folio.customer_id,
                                                room.name as room_name,
                                                customer.name as customer_name,
                                                customer.def_name as customer_full_name,
                                                customer.tax_code,
                                                traveller.first_name || \' \' || traveller.last_name as traveller_name,
                                                CASE
                                                    WHEN folio.total=0
                                                    THEN folio.create_time
                                                    ELSE folio.payment_time
                                                END time,
                                                extra_service.code as service_code,
                                                extra_service.type as extra_service_type,
                                                bar.code as bar_code
                                            FROM
                                                traveller_folio
                                                inner join folio on folio.id=traveller_folio.folio_id
                                                inner join reservation on reservation.id=folio.reservation_id
                                                left join customer on customer.id=folio.customer_id
                                                left join reservation_room on folio.reservation_room_id=reservation_room.id
                                                left join room on reservation_room.room_id=room.id
                                                left join reservation_traveller on reservation_traveller.id=folio.reservation_traveller_id
                                                left join traveller on traveller.id=reservation_traveller.traveller_id
                                                left join extra_service_invoice_detail on extra_service_invoice_detail.id=traveller_folio.invoice_id and type=\'EXTRA_SERVICE\'
                                                left join extra_service on extra_service_invoice_detail.service_id=extra_service.id
                                                left join bar_reservation on bar_reservation.id=traveller_folio.invoice_id and traveller_folio.type=\'BAR\'
                                                left join bar on bar_reservation.bar_id=bar.id
                                            WHERE
                                                '.$cond.'
                                                and traveller_folio.type!=\'DISCOUNT\' and traveller_folio.type!=\'DEPOSIT\' and traveller_folio.type!=\'DEPOSIT_GROUP\'
                                                and (folio.payment_time is not null or folio.total=0)
                                            ORDER by
                                                folio.create_time,folio.payment_time
                                            ');
            $vat_bill = DB::fetch_all('select 
                                            vat_bill_detail.invoice_id || vat_bill_detail.invoice_detail_id || vat_bill_detail.invoice_detail_type as id, 
                                            vat_bill_detail.invoice_id,
                                            vat_bill_detail.invoice_detail_id,
                                            vat_bill_detail.invoice_detail_type,
                                            sum(vat_bill_detail.total_amount) as total 
                                        from 
                                            vat_bill_detail
                                            inner join vat_bill on vat_bill_detail.vat_bill_id=vat_bill.id
                                        where 
                                            vat_bill_detail.type=\'FOLIO\' and (vat_bill.status is null or vat_bill.status!=\'CANCEL\')
                                        group by 
                                            vat_bill_detail.invoice_id,
                                            vat_bill_detail.invoice_detail_id,
                                            vat_bill_detail.invoice_detail_type ');
            foreach($traveller_folio as $key=>$value) {
                $check = true;
                $total_print_vat = 0;
                if(isset($vat_bill[$value['folio_id'].$value['invoice_id'].$value['type']])) {
                    if($value['total_amount']<=$vat_bill[$value['folio_id'].$value['invoice_id'].$value['type']]['total']) {
                        $check=false;
                        unset($traveller_folio[$key]);
                    } else {
                        $total_print_vat = $vat_bill[$value['folio_id'].$value['invoice_id'].$value['type']]['total'];
                    }
                }
                if(isset($traveller_folio[$key]) and isset($folio_foc[$value['folio_id']])) {
                    $check=false; 
                    unset($traveller_folio[$key]);
                }
                if($check and isset($traveller_folio[$key])){
                    
                    if(!isset($items['Folio_'.$value['folio_id']])){
                        $items['Folio_'.$value['folio_id']]['id'] = $value['folio_id'];
                        $items['Folio_'.$value['folio_id']]['customer_code'] = $value['customer_name'];
                        $items['Folio_'.$value['folio_id']]['booking_number'] = array();
                        $href = '?page=view_traveller_folio';
                        if($value['reservation_room_id']==''){
                            $href .= '&cmd=group_invoice&folio_id='.$value['folio_id'].'&customer_id='.$value['customer_id'];
                        }
                        else{
                            $href .= '&cmd=invoice&traveller_id='.$value['reservation_traveller_id'].'&folio_id='.$value['folio_id'];
                        }
                        if($value['folio_total']==0)
                            $items['Folio_'.$value['folio_id']]['folio_number'] = '<a target="_blank" href="'.$href.'">'.'Ref'.str_pad($value['folio_code'],6,"0",STR_PAD_LEFT).'</a> ';
                        else
                            $items['Folio_'.$value['folio_id']]['folio_number'] = '<a target="_blank" href="'.$href.'">'.'No.F'.str_pad($value['folio_code'],6,"0",STR_PAD_LEFT).'</a> ';
                        
                        $items['Folio_'.$value['folio_id']]['vat_code'] = '';
                        $items['Folio_'.$value['folio_id']]['create_time'] = '';
                        $items['Folio_'.$value['folio_id']]['payment_time'] = date('d/m/Y',$value['time']);
                        $items['Folio_'.$value['folio_id']]['buyer'] = $value['customer_full_name'];
                        $items['Folio_'.$value['folio_id']]['tax_code'] = $value['tax_code'];
                        $items['Folio_'.$value['folio_id']]['room_charge'] = 0;
                        $items['Folio_'.$value['folio_id']]['extra_service_charge_room'] = 0;
                        $items['Folio_'.$value['folio_id']]['breakfast'] = 0;
                        $items['Folio_'.$value['folio_id']]['restaurant'] = 0;
                        $items['Folio_'.$value['folio_id']]['banquet'] = 0;
                        $items['Folio_'.$value['folio_id']]['minibar'] = 0;
                        $items['Folio_'.$value['folio_id']]['laundry'] = 0;
                        $items['Folio_'.$value['folio_id']]['equipment'] = 0;
                        $items['Folio_'.$value['folio_id']]['trans'] = 0;
                        $items['Folio_'.$value['folio_id']]['others'] = 0;
                        $items['Folio_'.$value['folio_id']]['service_rate'] = 0;
                        $items['Folio_'.$value['folio_id']]['total_revenue'] = 0;
                        $items['Folio_'.$value['folio_id']]['tax_rate'] = 0;
                        $items['Folio_'.$value['folio_id']]['total_amount'] = 0;
                        $items['Folio_'.$value['folio_id']]['note'] = '';
                    }
                    if($value['reservation_room_id']=='')
                        $items['Folio_'.$value['folio_id']]['booking_number'][$value['reservation_id']]['recode'] = '<a target="_blank" href="?page=reservation&cmd=edit&id='.$value['reservation_id'].'">#'.$value['reservation_id'].'</a> ';
                    else
                        $items['Folio_'.$value['folio_id']]['booking_number'][$value['reservation_id']]['recode'] = '<a target="_blank" href="?page=reservation&cmd=edit&id='.$value['reservation_id'].'&r_r_id='.$value['reservation_room_id'].'">#'.$value['reservation_id'].'</a> ';
                    
                    $total_amount = $value['total_amount'] - $total_print_vat;
                    $total_before_tax = round($total_amount/( (1+$value['service_rate']/100) * (1+$value['tax_rate']/100) ),2);
                    $service_amount = round($total_before_tax*($value['service_rate']/100),2);
                    $tax_amount = $total_amount - $total_before_tax - $service_amount;
                    
                    
                    $items['Folio_'.$value['folio_id']]['service_rate'] += $service_amount;
                    $items['Folio_'.$value['folio_id']]['total_revenue'] += $total_before_tax+$service_amount;
                    $items['Folio_'.$value['folio_id']]['tax_rate'] += $tax_amount;
                    $items['Folio_'.$value['folio_id']]['total_amount'] += $total_amount;
                                    
                    $this->map['service_rate'] += $service_amount;
                    $this->map['total_revenue'] += $total_before_tax+$service_amount;
                    $this->map['tax_rate'] += $tax_amount;
                    $this->map['total_amount'] += $total_amount;
                    
                    if($value['type']=='ROOM' OR ($value['type']=='EXTRA_SERVICE' and ($value['service_code']=='LATE_CHECKIN' OR $value['service_code']=='LATE_CHECKOUT' OR $value['service_code']=='EARLY_CHECKIN' OR $value['service_code']=='ROOM')))
                    {
                        $items['Folio_'.$value['folio_id']]['room_charge'] += $total_before_tax;
                        $this->map['room_charge'] += $total_before_tax;
                    }
                    elseif($value['type']=='EXTRA_SERVICE' and $value['extra_service_type']=='ROOM' and $value['service_code']!='LATE_CHECKIN' and $value['service_code']!='LATE_CHECKOUT' and $value['service_code']!='EARLY_CHECKIN' and $value['service_code']!='ROOM' and $value['service_code']!='PTAS' and $value['service_code']!='KID BF' and substr($value['service_code'],0,7)!='TRANFER')
                    {
                        $items['Folio_'.$value['folio_id']]['extra_service_charge_room'] += $total_before_tax;
                        $this->map['extra_service_charge_room'] += $total_before_tax;
                    }
                    elseif($value['type']=='EXTRA_SERVICE' and ($value['service_code']=='PTAS' OR $value['service_code']=='KID BF'))
                    {
                        $items['Folio_'.$value['folio_id']]['breakfast'] += $total_before_tax;
                        $this->map['breakfast'] += $total_before_tax;
                    }
                    elseif($value['type']=='BAR' and $value['bar_code']!='MROOM' )
                    {
                        $items['Folio_'.$value['folio_id']]['restaurant'] += $total_before_tax;
                        $this->map['restaurant'] += $total_before_tax;
                    }
                    elseif($value['type']=='BAR' and $value['bar_code']=='MROOM' )
                    {
                        $items['Folio_'.$value['folio_id']]['banquet'] += $total_before_tax;
                        $this->map['banquet'] += $total_before_tax;
                    }
                    elseif($value['type']=='MINIBAR')
                    {
                        $items['Folio_'.$value['folio_id']]['minibar'] += $total_before_tax;
                        $this->map['minibar'] += $total_before_tax;
                    }
                    elseif($value['type']=='LAUNDRY')
                    {
                        $items['Folio_'.$value['folio_id']]['laundry'] += $total_before_tax;
                        $this->map['laundry'] += $total_before_tax;
                    }
                    elseif($value['type']=='EQUIPMENT')
                    {
                        $items['Folio_'.$value['folio_id']]['equipment'] += $total_before_tax;
                        $this->map['equipment'] += $total_before_tax;
                    }
                    elseif($value['type']=='EXTRA_SERVICE' and substr($value['service_code'],0,7)=='TRANFER')
                    {
                        $items['Folio_'.$value['folio_id']]['trans'] += $total_before_tax;
                        $this->map['trans'] += $total_before_tax;
                    }
                    else
                    {
                        $items['Folio_'.$value['folio_id']]['others'] += $total_before_tax;
                        $this->map['others'] += $total_before_tax;
                    }
                }
                // end If
            }
            // End Foreach
       }// End folio
       if(!isset($_REQUEST['type']) or Url::get('type')=='ALL' or Url::get('type')=='BAR'){
            $cond = '1>0 and bar_reservation.portal_id=\''.PORTAL_ID.'\' and bar_reservation.status=\'CHECKOUT\'';
            $cond .= ' and bar_reservation.time_in<='.(Date_Time::to_time($this->map['end_date'])+86399).' and bar_reservation.time_out>='.Date_Time::to_time($this->map['start_date']);
            
            $bar_reservation_group = DB::fetch_all('
                                                    SELECT
                                                        bar_reservation.id,
                                                        bar_reservation.code,
                                                        bar_reservation.id as bar_reservation_id,
                                                        NVL(bar_reservation.tax_rate,0) as tax_rate,
                                                        NVL(bar_reservation.bar_fee_rate,0) as service_rate,
                                                        NVL(bar_reservation.total,0) as total_amount,
                                                        bar_reservation.arrival_time,
                                                        bar_reservation.departure_time,
                                                        bar_reservation.receiver_name,
                                                        bar_reservation.customer_id,
                                                        bar_reservation.package_id,
                                                        bar_reservation.bar_id,
                                                        customer.name as customer_name,
                                                        customer.def_name as customer_full_name,
                                                        customer.tax_code as customer_tax_code,
                                                        bar.code as bar_code
                                                    FROM
                                                        bar_reservation_product
                                                        inner join bar_reservation on bar_reservation.id=bar_reservation_product.bar_reservation_id
                                                        inner join bar on bar_reservation.bar_id=bar.id
                                                        left join customer on customer.id=bar_reservation.customer_id
                                                    WHERE
                                                        '.$cond.' and (bar_reservation.pay_with_room is null or bar_reservation.pay_with_room=0)
                                                    ');
            $vat_bill = DB::fetch_all('select 
                                        vat_bill_detail.invoice_id as id, 
                                        sum(vat_bill_detail.total_amount) as total 
                                    from 
                                        vat_bill_detail 
                                        inner join vat_bill on vat_bill_detail.vat_bill_id=vat_bill.id
                                    where 
                                        vat_bill_detail.type=\'BAR\' and (vat_bill.status is null or vat_bill.status!=\'CANCEL\')
                                    group by 
                                        vat_bill_detail.invoice_id ');
            foreach($bar_reservation_group as $key=>$value){
                $total_print_vat = 0;
                if(isset($vat_bill[$value['id']])){
                    if($vat_bill[$value['id']]['total']==$value['total_amount']){
                        unset($bar_reservation_group[$key]);
                    }else{
                        $total_print_vat = $vat_bill[$value['id']]['total'];
                    }
                }
                
                if(isset($bar_reservation_group[$key])){
                    if(!isset($items['Bar_'.$value['id']])){
                        $items['Bar_'.$value['id']]['id'] = $value['id'];
                        $items['Bar_'.$value['id']]['customer_code'] = $value['customer_name'];
                        $items['Bar_'.$value['id']]['booking_number'] = array();
                        $items['Bar_'.$value['id']]['folio_number'] = '<a target="_blank" href="?page=touch_bar_restaurant&cmd=detail&316c9c3ed45a83ee318b1f859d9b8b79=d001cd500100d09a0a074a7a7ea55702&5ebeb6065f64f2346dbb00ab789cf001=1&id='.$value['id'].'&bar_id='.$value['bar_id'].'&package_id='.$value['package_id'].'">'.$value['code'].'</a> ';
                        $items['Bar_'.$value['id']]['vat_code'] = '';
                        $items['Bar_'.$value['id']]['create_time'] = '';
                        $items['Bar_'.$value['id']]['payment_time'] = date('d/m/Y',$value['departure_time']);
                        $items['Bar_'.$value['id']]['buyer'] = $value['customer_full_name'];
                        $items['Bar_'.$value['id']]['tax_code'] = $value['customer_tax_code'];
                        $items['Bar_'.$value['id']]['room_charge'] = 0;
                        $items['Bar_'.$value['id']]['extra_service_charge_room'] = 0;
                        $items['Bar_'.$value['id']]['breakfast'] = 0;
                        $items['Bar_'.$value['id']]['restaurant'] = 0;
                        $items['Bar_'.$value['id']]['banquet'] = 0;
                        $items['Bar_'.$value['id']]['minibar'] = 0;
                        $items['Bar_'.$value['id']]['laundry'] = 0;
                        $items['Bar_'.$value['id']]['equipment'] = 0;
                        $items['Bar_'.$value['id']]['trans'] = 0;
                        $items['Bar_'.$value['id']]['others'] = 0;
                        $items['Bar_'.$value['id']]['service_rate'] = 0;
                        $items['Bar_'.$value['id']]['total_revenue'] = 0;
                        $items['Bar_'.$value['id']]['tax_rate'] = 0;
                        $items['Bar_'.$value['id']]['total_amount'] = 0;
                        $items['Bar_'.$value['id']]['note'] = '';
                    }
                    $total_amount = $value['total_amount'] - $total_print_vat;
                    $total_before_tax = round($total_amount/( (1+$value['service_rate']/100) * (1+$value['tax_rate']/100) ),2);
                    $service_amount = round($total_before_tax*($value['service_rate']/100),2);
                    $tax_amount = $total_amount - $total_before_tax - $service_amount;
                    
                    $items['Bar_'.$value['id']]['service_rate'] += $service_amount;
                    $items['Bar_'.$value['id']]['total_revenue'] += $total_before_tax+$service_amount;
                    $items['Bar_'.$value['id']]['tax_rate'] += $tax_amount;
                    $items['Bar_'.$value['id']]['total_amount'] += $total_amount;
                                    
                    $this->map['service_rate'] += $service_amount;
                    $this->map['total_revenue'] += $total_before_tax+$service_amount;
                    $this->map['tax_rate'] += $tax_amount;
                    $this->map['total_amount'] += $total_amount;
                    
                    if($value['bar_code']!='MROOM' )
                    {
                        $items['Bar_'.$value['id']]['restaurant'] += $total_before_tax;
                        $this->map['restaurant'] += $total_before_tax;
                    }
                    else
                    {
                        $items['Bar_'.$value['id']]['banquet'] += $total_before_tax;
                        $this->map['banquet'] += $total_before_tax;
                    }
                }
                // End if
            }
            // end Foreach
       }// end Bar
       if(!isset($_REQUEST['type']) or Url::get('type')=='ALL' or Url::get('type')=='BANQUET') {
            $cond = '1>0 and party_reservation.checkin_time>='.Date_Time::to_time($this->map['start_date']).'
                    and party_reservation.checkin_time<'.(Date_Time::to_time($this->map['end_date'])+(3600*24));
            $party_reservation_group = DB::fetch_all('
                                                    SELECT
                                                        party_reservation.id,
                                                        NVL(party_reservation.vat,0) as tax_rate,
                                                        NVL(party_reservation.extra_service_rate,0) as service_rate,
                                                        NVL(party_reservation.total,0) as total_amount,
                                                        party_reservation.deposit_1,
                                                        party_reservation.deposit_2,
                                                        party_reservation.deposit_3,
                                                        party_reservation.deposit_4,
                                                        party_reservation.checkin_time,
                                                        party_reservation.checkout_time,
                                                        party_reservation.full_name,
                                                        party_reservation.party_type,
                                                        \'\' as customer_id,
                                                        \'\' as customer_code,
                                                        \'\' as customer_full_name,
                                                        \'\' as customer_name,
                                                        \'\' as customer_tax_code,
                                                        \'\' as customer_address,
                                                        \'\' as customer_bank_code
                                                    FROM
                                                        party_reservation
                                                    WHERE
                                                        '.$cond.' and party_reservation.status=\'CHECKOUT\' 
                                                    ');
            $vat_bill = DB::fetch_all('select 
                                        vat_bill_detail.invoice_id as id, 
                                        sum(vat_bill_detail.total_amount) as total 
                                    from 
                                        vat_bill_detail 
                                        inner join vat_bill on vat_bill_detail.vat_bill_id=vat_bill.id
                                    where 
                                        vat_bill_detail.type=\'BANQUET\' and (vat_bill.status is null or vat_bill.status!=\'CANCEL\')
                                    group by 
                                        vat_bill_detail.invoice_id ');
            foreach($party_reservation_group as $key=>$value){
                $value['total_amount'] += $value['deposit_1']+$value['deposit_2']+$value['deposit_3']+$value['deposit_4'];
                $total_print_vat = 0;
                if(isset($vat_bill[$value['id']])){
                    if($vat_bill[$value['id']]['total']==$value['total_amount']){
                        unset($party_reservation_group[$key]);
                    }else{
                        $total_print_vat = $vat_bill[$value['id']]['total'];
                    }
                }
                
                if(isset($party_reservation_group[$key])){
                    if(!isset($items['Party_'.$value['id']])){
                        $items['Party_'.$value['id']]['id'] = $value['id'];
                        $items['Party_'.$value['id']]['customer_code'] = $value['customer_name'];
                        $items['Party_'.$value['id']]['booking_number'] = array();
                        $items['Party_'.$value['id']]['folio_number'] = '<a target="_blank" href="?page=banquet_reservation&cmd='.$value['party_type'].'&action=edit&id='.$value['id'].'">'.$value['id'].'</a> ';
                        $items['Party_'.$value['id']]['vat_code'] = '';
                        $items['Party_'.$value['id']]['create_time'] = '';
                        $items['Party_'.$value['id']]['payment_time'] = date('d/m/Y',$value['checkout_time']);
                        $items['Party_'.$value['id']]['buyer'] = $value['customer_full_name'];
                        $items['Party_'.$value['id']]['tax_code'] = $value['customer_tax_code'];
                        $items['Party_'.$value['id']]['room_charge'] = 0;
                        $items['Party_'.$value['id']]['extra_service_charge_room'] = 0;
                        $items['Party_'.$value['id']]['breakfast'] = 0;
                        $items['Party_'.$value['id']]['restaurant'] = 0;
                        $items['Party_'.$value['id']]['banquet'] = 0;
                        $items['Party_'.$value['id']]['minibar'] = 0;
                        $items['Party_'.$value['id']]['laundry'] = 0;
                        $items['Party_'.$value['id']]['equipment'] = 0;
                        $items['Party_'.$value['id']]['trans'] = 0;
                        $items['Party_'.$value['id']]['others'] = 0;
                        $items['Party_'.$value['id']]['service_rate'] = 0;
                        $items['Party_'.$value['id']]['total_revenue'] = 0;
                        $items['Party_'.$value['id']]['tax_rate'] = 0;
                        $items['Party_'.$value['id']]['total_amount'] = 0;
                        $items['Party_'.$value['id']]['note'] = '';
                    }
                    $total_amount = $value['total_amount'] - $total_print_vat;
                    $total_before_tax = round($total_amount/( (1+$value['service_rate']/100) * (1+$value['tax_rate']/100) ),2);
                    $service_amount = round($total_before_tax*($value['service_rate']/100),2);
                    $tax_amount = $total_amount - $total_before_tax - $service_amount;
                    
                    $items['Party_'.$value['id']]['service_rate'] += $service_amount;
                    $items['Party_'.$value['id']]['total_revenue'] += $total_before_tax+$service_amount;
                    $items['Party_'.$value['id']]['tax_rate'] += $tax_amount;
                    $items['Party_'.$value['id']]['total_amount'] += $total_amount;
                                    
                    $this->map['service_rate'] += $service_amount;
                    $this->map['total_revenue'] += $total_before_tax+$service_amount;
                    $this->map['tax_rate'] += $tax_amount;
                    $this->map['total_amount'] += $total_amount;
                    $items['Party_'.$value['id']]['banquet'] += $total_before_tax;
                    $this->map['banquet'] += $total_before_tax;
                }
            }
            
       }
       if(!isset($_REQUEST['type']) or Url::get('type')=='ALL' or Url::get('type')=='MICE'){
            $cond = ' 1 = 1';
            $cond .= '
                        and ( (mice_invoice.total_amount=0 and mice_invoice.create_time>='.Date_Time::to_time($this->map['start_date']).' 
                                and mice_invoice.create_time<='.(Date_Time::to_time($this->map['end_date'])+86399).') 
                            OR (mice_invoice.total_amount !=0 and mice_invoice.payment_time>='.Date_Time::to_time($this->map['start_date']).' 
                                and mice_invoice.payment_time<='.(Date_Time::to_time($this->map['end_date'])+86399).') )';
            
            $sql_mice_invoice_foc = '
                SELECT 
                    mice_invoice.id as id
                FROM 
                    payment
                    inner join mice_invoice on mice_invoice.id = payment.bill_id 
                WHERE 
                    payment.payment_type_id = \'FOC\'
                    AND payment.type =\'MICE\'
            ';
            $mice_foc = DB::fetch_all($sql_mice_invoice_foc);
            
            $mice_invoice = DB::fetch_all('
                                    SELECT
                                        mice_invoice_detail.mice_invoice_id || \'_\' || mice_invoice_detail.invoice_id || \'_\' || mice_invoice_detail.type as id,
                                        mice_invoice_detail.total_amount,
                                        mice_invoice_detail.type,
                                        mice_invoice_detail.invoice_id,
                                        mice_invoice_detail.tax_amount,
                                        mice_invoice_detail.service_amount,
                                        mice_invoice_detail.amount as total_before_tax,
                                        mice_invoice.id as mice_invoice_id,
                                        mice_invoice.mice_reservation_id,
                                        mice_invoice.bill_id,
                                        mice_invoice.total_amount as total,
                                        mice_invoice.user_id,
                                        CASE
                                            WHEN mice_invoice.bill_id is null
                                            THEN mice_invoice.create_time
                                            ELSE mice_invoice.payment_time
                                        END time,
                                        mice_reservation.contact_name,
                                        customer.name as customer_name,
                                        customer.def_name as customer_full_name,
                                        customer.tax_code,
                                        extra_service.code as service_code,
                                        extra_service.type as extra_service_type,
                                        bar.code as bar_code
                                    FROM
                                        mice_invoice_detail
                                        INNER JOIN mice_invoice ON mice_invoice.id = mice_invoice_detail.mice_invoice_id
                                        INNER JOIN mice_reservation ON mice_reservation.id = mice_invoice.mice_reservation_id
                                        INNER JOIN customer ON customer.id = mice_reservation.customer_id
                                        left join extra_service_invoice_detail on extra_service_invoice_detail.id=mice_invoice_detail.invoice_id and type=\'EXTRA_SERVICE\'
                                        left join extra_service on extra_service_invoice_detail.service_id=extra_service.id
                                        left join bar_reservation on bar_reservation.id=mice_invoice_detail.invoice_id and mice_invoice_detail.type=\'BAR\'
                                        left join bar on bar_reservation.bar_id=bar.id
                                    WHERE
                                        '.$cond.'
                                        AND mice_invoice_detail.type !=\'DISCOUNT\' 
                                        AND mice_invoice_detail.type !=\'DEPOSIT\' 
                                        AND mice_invoice_detail.type !=\'DEPOSIT_MICE\'
                                        AND (mice_invoice.payment_time is not null or mice_invoice_detail.total_amount=0)
                                    ORDER BY
                                        mice_invoice.id DESC 
            ');
            //System::debug($mice_invoice);
            
            $vat_bill = DB::fetch_all('
                                    SELECT 
                                        vat_bill_detail.invoice_id || vat_bill_detail.invoice_detail_id || vat_bill_detail.invoice_detail_type as id, 
                                        vat_bill_detail.invoice_id,
                                        vat_bill_detail.invoice_detail_id,
                                        vat_bill_detail.invoice_detail_type,
                                        sum(vat_bill_detail.total_amount) as total 
                                    FROM 
                                        vat_bill_detail
                                        INNER JOIN vat_bill ON vat_bill_detail.vat_bill_id=vat_bill.id
                                    WHERE 
                                        vat_bill_detail.type=\'MICE\' and (vat_bill.status is null or vat_bill.status!=\'CANCEL\')
                                    GROUP BY 
                                        vat_bill_detail.invoice_id,
                                        vat_bill_detail.invoice_detail_id,
                                        vat_bill_detail.invoice_detail_type 
            ');
            //System::debug($vat_bill);
            
            $this->map['items'] = array();
            $stt = 0;
            $this->map['total']=0;
            $this->map['total_remain']=0;
            
            foreach($mice_invoice as $key=>$value)
            {
                $check = true;
                $total_print_vat = 0;
                if(isset($vat_bill[$value['mice_invoice_id'].$value['invoice_id'].$value['type']])) 
                {
                    if(System::display_number(System::calculate_number($value['total_amount'])) <= $vat_bill[$value['mice_invoice_id'].$value['invoice_id'].$value['type']]['total']) 
                    {
                        $check=false;
                        unset($mice_invoice[$key]);
                    } else 
                    {
                        $total_print_vat = $vat_bill[$value['mice_invoice_id'].$value['invoice_id'].$value['type']]['total'];
                    }
                }
                if($check and isset($mice_invoice[$key])){
                    
                    if(!isset($items['Mice_'.$value['mice_invoice_id']])){
                        $items['Mice_'.$value['mice_invoice_id']]['id'] = $value['mice_invoice_id'];
                        $items['Mice_'.$value['mice_invoice_id']]['customer_code'] = $value['customer_name'];
                        $items['Mice_'.$value['mice_invoice_id']]['booking_number'] = array();
                        $mice_code = ($value['bill_id'] !='')?'BILL - '.$value['bill_id']:'MICE +'.$value['mice_reservation_id'];
                        $items['Mice_'.$value['mice_invoice_id']]['folio_number'] = '<a target="_blank" href="?page=mice_reservation&cmd=bill_new&invoice_id='.$value['mice_invoice_id'].'">'.$mice_code.'</a>';
                        $items['Mice_'.$value['mice_invoice_id']]['vat_code'] = '';
                        $items['Mice_'.$value['mice_invoice_id']]['create_time'] = '';
                        $items['Mice_'.$value['mice_invoice_id']]['payment_time'] = date('d/m/Y',$value['time']);
                        $items['Mice_'.$value['mice_invoice_id']]['buyer'] = $value['customer_full_name'];
                        $items['Mice_'.$value['mice_invoice_id']]['tax_code'] = $value['tax_code'];
                        $items['Mice_'.$value['mice_invoice_id']]['room_charge'] = 0;
                        $items['Mice_'.$value['mice_invoice_id']]['extra_service_charge_room'] = 0;
                        $items['Mice_'.$value['mice_invoice_id']]['breakfast'] = 0;
                        $items['Mice_'.$value['mice_invoice_id']]['restaurant'] = 0;
                        $items['Mice_'.$value['mice_invoice_id']]['banquet'] = 0;
                        $items['Mice_'.$value['mice_invoice_id']]['minibar'] = 0;
                        $items['Mice_'.$value['mice_invoice_id']]['laundry'] = 0;
                        $items['Mice_'.$value['mice_invoice_id']]['equipment'] = 0;
                        $items['Mice_'.$value['mice_invoice_id']]['trans'] = 0;
                        $items['Mice_'.$value['mice_invoice_id']]['others'] = 0;
                        $items['Mice_'.$value['mice_invoice_id']]['service_rate'] = 0;
                        $items['Mice_'.$value['mice_invoice_id']]['total_revenue'] = 0;
                        $items['Mice_'.$value['mice_invoice_id']]['tax_rate'] = 0;
                        $items['Mice_'.$value['mice_invoice_id']]['total_amount'] = 0;
                        $items['Mice_'.$value['mice_invoice_id']]['note'] = '';
                    }
                    $items['Mice_'.$value['mice_invoice_id']]['booking_number'][$value['mice_reservation_id']]['recode'] = '<a target="_blank" href="?page=mice_reservation&cmd=edit&id='.$value['mice_reservation_id'].'">MICE+'.$value['mice_reservation_id'].'</a> ';
                    $total_amount = $value['total_amount'] - $total_print_vat;
                    $total_before_tax = round($value['total_before_tax']);
                    $service_amount = round($value['service_amount']);
                    $tax_amount = round($value['tax_amount']);
                    
                    
                    $items['Mice_'.$value['mice_invoice_id']]['service_rate'] += $service_amount;
                    $items['Mice_'.$value['mice_invoice_id']]['total_revenue'] += $total_before_tax+$service_amount;
                    $items['Mice_'.$value['mice_invoice_id']]['tax_rate'] += $tax_amount;
                    $items['Mice_'.$value['mice_invoice_id']]['total_amount'] += $total_amount;
                                    
                    $this->map['service_rate'] += $service_amount;
                    $this->map['total_revenue'] += $total_before_tax+$service_amount;
                    $this->map['tax_rate'] += $tax_amount;
                    $this->map['total_amount'] += $total_amount;
                    
                    if($value['type']=='ROOM' OR ($value['type']=='EXTRA_SERVICE' and ($value['service_code']=='LATE_CHECKIN' OR $value['service_code']=='LATE_CHECKOUT' OR $value['service_code']=='EARLY_CHECKIN' OR $value['service_code']=='ROOM')))
                    {
                        $items['Mice_'.$value['mice_invoice_id']]['room_charge'] += $total_before_tax;
                        $this->map['room_charge'] += $total_before_tax;
                    }
                    elseif($value['type']=='EXTRA_SERVICE' and $value['extra_service_type']=='ROOM' and $value['service_code']!='LATE_CHECKIN' and $value['service_code']!='LATE_CHECKOUT' and $value['service_code']!='EARLY_CHECKIN' and $value['service_code']!='ROOM' and $value['service_code']!='PTAS' and $value['service_code']!='KID BF' and substr($value['service_code'],0,7)!='TRANFER')
                    {
                        $items['Mice_'.$value['mice_invoice_id']]['extra_service_charge_room'] += $total_before_tax;
                        $this->map['extra_service_charge_room'] += $total_before_tax;
                    }
                    elseif($value['type']=='EXTRA_SERVICE' and ($value['service_code']=='PTAS' OR $value['service_code']=='KID BF'))
                    {
                        $items['Mice_'.$value['mice_invoice_id']]['breakfast'] += $total_before_tax;
                        $this->map['breakfast'] += $total_before_tax;
                    }
                    elseif($value['type']=='BAR' and $value['bar_code']!='MROOM' )
                    {
                        $items['Mice_'.$value['mice_invoice_id']]['restaurant'] += $total_before_tax;
                        $this->map['restaurant'] += $total_before_tax;
                    }
                    elseif($value['type']=='BAR' and $value['bar_code']=='MROOM' )
                    {
                        $items['Mice_'.$value['mice_invoice_id']]['banquet'] += $total_before_tax;
                        $this->map['banquet'] += $total_before_tax;
                    }
                    elseif($value['type']=='MINIBAR')
                    {
                        $items['Mice_'.$value['mice_invoice_id']]['minibar'] += $total_before_tax;
                        $this->map['minibar'] += $total_before_tax;
                    }
                    elseif($value['type']=='LAUNDRY')
                    {
                        $items['Mice_'.$value['mice_invoice_id']]['laundry'] += $total_before_tax;
                        $this->map['laundry'] += $total_before_tax;
                    }
                    elseif($value['type']=='EQUIPMENT')
                    {
                        $items['Mice_'.$value['mice_invoice_id']]['equipment'] += $total_before_tax;
                        $this->map['equipment'] += $total_before_tax;
                    }
                    elseif($value['type']=='EXTRA_SERVICE' and substr($value['service_code'],0,7)=='TRANFER')
                    {
                        $items['Mice_'.$value['mice_invoice_id']]['trans'] += $total_before_tax;
                        $this->map['trans'] += $total_before_tax;
                    }
                    else
                    {
                        $items['Mice_'.$value['mice_invoice_id']]['others'] += $total_before_tax;
                        $this->map['others'] += $total_before_tax;
                    }
                }
            }
       }// End mice
       //System::debug($items);
       
       $this->map['items'] = $items;
       
       $this->parse_layout('report',$this->map);
	}
}
?>