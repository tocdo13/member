<?php
class VatRevenueReportForm extends Form
{
	function VatRevenueReportForm()
	{
		Form::Form('VatRevenueReportForm');
        $this->link_css('packages/hotel/skins/default/css/font-awesome-4.5.0/css/font-awesome.min.css');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw()
	{
	   $this->map = array();
       $this->map['start_date'] = '';
       $this->map['end_date'] = '';
       $cond = '';
       if(Url::get('vat_code')){
            $cond .= ' and vat_bill.vat_code=\''.Url::get('vat_code').'\'';
       }elseif(Url::get('folio_code')){
            $cond .= ' and vat_bill.type=\'FOLIO\'';
       }
       else{
           if(Url::get('start_date') and Url::get('end_date')) {
                $cond .= ' and vat_bill.print_date<=\''.Date_Time::to_time(Url::get('end_date')).'\' and vat_bill.print_date>=\''.Date_Time::to_time(Url::get('start_date')).'\'';
                $this->map['start_date'] = Url::get('start_date');
                $this->map['end_date'] = Url::get('end_date');
            }
            else{
                $cond .= ' and vat_bill.print_date<=\''.Date_Time::to_time(cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y')).'/'.date('m/Y')).'\' and vat_bill.print_date>=\''.Date_Time::to_time('01/'.date('m/Y')).'\'';
                $_REQUEST['start_date'] = '01/'.date('m/Y');
                $_REQUEST['end_date'] = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y')).'/'.date('m/Y');
                $this->map['start_date'] = $_REQUEST['start_date'];
                $this->map['end_date'] = $_REQUEST['end_date'];
            }
            if(Url::get('vat_type') and Url::get('vat_type')=='SAVE_NO_PRINT') {
                $cond .= ' and vat_bill.vat_type=\'SAVE_NO_PRINT\'';
            }else{
                if(Url::get('vat_type')=='PRINT')
                    $cond .= ' and vat_bill.vat_type!=\'SAVE_NO_PRINT\' and vat_bill.vat_type=\'PRINT\'';
                elseif(Url::get('vat_type')=='SAVE_CODE')
                    $cond .= ' and vat_bill.vat_type!=\'SAVE_NO_PRINT\' and vat_bill.vat_type=\'SAVE_CODE\'';
                else
                    $cond .= ' and vat_bill.vat_type!=\'SAVE_NO_PRINT\'';
            }
            if(Url::get('type') and Url::get('type')!='ALL') {
                if(Url::get('type')=='OTHER')
                    $cond .= ' and (vat_bill.type=\'\' or vat_bill.type is null)';
                else
                    $cond .= ' and vat_bill.type=\''.Url::get('type').'\'';
            }
            else {
                $this->map['type'] = 'ALL';
            }
       }
       $this->map['vat_type_list'] = array(''=>Portal::language('all'),'SAVE_CODE'=>Portal::language('save_code'),'PRINT'=>Portal::language('printed'));
       $this->map['type_list'] = array('ALL'=>Portal::language('all'),'BAR'=>Portal::language('restaurant'),'BANQUET'=>Portal::language('party'),'FOLIO'=>Portal::language('reception'),'MICE'=>Portal::language('mice'),'OTHER'=>Portal::language('vat_other'));
       
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
      // System::debug($cond);
       $vat_invoice = DB::fetch_all('
                                    SELECT
                                        vat_invoice.id,
                                        vat_invoice.vat_bill_id,
                                        CASE
                                            WHEN folio.total=0
                                            THEN folio.id
                                            ELSE folio.code 
                                            END folio_code,
                                        folio.total as folio_total,
                                        folio.id as folio_id,
                                        folio.reservation_id,
                                        folio.reservation_room_id,
                                        folio.customer_id,
                                        folio.reservation_traveller_id,
                                        bar_reservation.id as bar_reservation_id,
                                        bar_reservation.code as bar_reservation_code,
                                        bar_reservation.bar_id,
                                        bar_reservation.package_id,
                                        party_reservation.id as party_reservation_id,
                                        party_reservation.party_type,
                                        folio.payment_time as payment_time_folio,
                                        folio.create_time as create_time_folio,
                                        bar_reservation.time_out as payment_time_bar,
                                        party_reservation.checkin_time as payment_time_party,
                                        vat_bill.type,
                                        bar.code as bar_code,
                                        mice_invoice.id as mice_invoice_id,
                                        mice_invoice.mice_reservation_id,
                                        mice_invoice.bill_id,
                                        mice_invoice.payment_time as payment_time_mice,
                                        mice_invoice.create_time as create_time_mice
                                    FROM
                                        vat_invoice
                                        inner join vat_bill on vat_invoice.vat_bill_id=vat_bill.id
                                        left join folio on folio.id=vat_invoice.invoice_id and vat_bill.type=\'FOLIO\'
                                        left join bar_reservation on bar_reservation.id=vat_invoice.invoice_id and vat_bill.type=\'BAR\'
                                        left join party_reservation on party_reservation.id=vat_invoice.invoice_id and vat_bill.type=\'BANQUET\'
                                        left join mice_invoice on mice_invoice.id=vat_invoice.invoice_id and vat_bill.type=\'MICE\'
                                        left join bar on bar_reservation.bar_id=bar.id
                                    WHERE
                                        vat_bill.portal_id=\''.PORTAL_ID.'\' 
                                        '.$cond.'
                                    ');
       $vat_invoice_items = array();
       foreach($vat_invoice as $key=>$value){
            if(!isset($vat_invoice_items[$value['vat_bill_id']])){
                $vat_invoice_items[$value['vat_bill_id']]['booking_number'] = '';
                $vat_invoice_items[$value['vat_bill_id']]['folio_number'] = '';
                $vat_invoice_items[$value['vat_bill_id']]['payment_time'] = '';
                $vat_invoice_items[$value['vat_bill_id']]['folio_arr'] = array();
            }
            
            if($value['type']=='FOLIO')
            {
                $href = '?page=view_traveller_folio';
                
                if($value['reservation_room_id']==''){
                    $vat_invoice_items[$value['vat_bill_id']]['booking_number'] .= '<a target="_blank" href="?page=reservation&cmd=edit&id='.$value['reservation_id'].'">#'.$value['reservation_id'].'</a> ,';
                    $href .= '&cmd=group_invoice&folio_id='.$value['folio_id'].'&customer_id='.$value['customer_id'];
                }else{
                    $vat_invoice_items[$value['vat_bill_id']]['booking_number'] .= '<a target="_blank" href="?page=reservation&cmd=edit&id='.$value['reservation_id'].'&r_r_id='.$value['reservation_room_id'].'">#'.$value['reservation_id'].'</a> ,';
                    $href .= '&cmd=invoice&traveller_id='.$value['reservation_traveller_id'].'&folio_id='.$value['folio_id'];
                }
                
                if($value['folio_total']==0){
                    $vat_invoice_items[$value['vat_bill_id']]['folio_number'] .= '<a target="_blank" href="'.$href.'">'.'Ref'.str_pad($value['folio_code'],6,"0",STR_PAD_LEFT).'</a> ,';
                    $vat_invoice_items[$value['vat_bill_id']]['folio_arr']['Ref'.str_pad($value['folio_code'],6,"0",STR_PAD_LEFT)]['folio_code'] = 'Ref'.str_pad($value['folio_code'],6,"0",STR_PAD_LEFT);
                }else{
                    $vat_invoice_items[$value['vat_bill_id']]['folio_number'] .= '<a target="_blank" href="'.$href.'">'.'No.F'.str_pad($value['folio_code'],6,"0",STR_PAD_LEFT).'</a> ,';
                    $vat_invoice_items[$value['vat_bill_id']]['folio_arr']['No.F'.str_pad($value['folio_code'],6,"0",STR_PAD_LEFT)]['folio_code'] = 'No.F'.str_pad($value['folio_code'],6,"0",STR_PAD_LEFT);
                }
                
                $vat_invoice_items[$value['vat_bill_id']]['payment_time'] = $value['payment_time_folio']!=''?date('d/m/Y',$value['payment_time_folio']):date('d/m/Y',$value['create_time_folio']);
            }
            elseif($value['type']=='BAR')
            {
                $vat_invoice_items[$value['vat_bill_id']]['folio_number'] .= '<a target="_blank" href="?page=touch_bar_restaurant&cmd=detail&316c9c3ed45a83ee318b1f859d9b8b79=d001cd500100d09a0a074a7a7ea55702&5ebeb6065f64f2346dbb00ab789cf001=1&id='.$value['bar_reservation_id'].'&bar_id='.$value['bar_id'].'&package_id='.$value['package_id'].'">'.$value['bar_reservation_code'].'</a> ,';//'?page=touch_bar_restaurant&cmd=detail&316c9c3ed45a83ee318b1f859d9b8b79=d001cd500100d09a0a074a7a7ea55702&5ebeb6065f64f2346dbb00ab789cf001=1&id='.$value['id'].'&bar_id='.$value['bar_id'].'&table_id='.$table_id.'&bar_area_id='.$bar_area_id.'&package_id='.$value['package_id']
                $vat_invoice_items[$value['vat_bill_id']]['payment_time'] = date('d/m/Y',$value['payment_time_bar']);
            }
            elseif($value['type']=='BANQUET')
            {
                $vat_invoice_items[$value['vat_bill_id']]['folio_number'] .= '<a target="_blank" href="?page=banquet_reservation&cmd='.$value['party_type'].'&action=edit&id='.$value['party_reservation_id'].'">'.$value['party_reservation_id'].'</a> ,';
                $vat_invoice_items[$value['vat_bill_id']]['payment_time'] = date('d/m/Y',$value['payment_time_party']);
            }
            elseif($value['type']=='MICE')
            {
                $mice_code = ($value['bill_id'] !='')?'BILL - '.$value['bill_id']:'MICE +'.$value['mice_reservation_id'];
                $vat_invoice_items[$value['vat_bill_id']]['booking_number'] .= '<a target="_blank" href="?page=mice_reservation&cmd=edit&id='.$value['mice_reservation_id'].'">MICE +'.$value['mice_reservation_id'].'</a> ,';
                $vat_invoice_items[$value['vat_bill_id']]['folio_number'] .= '<a target="_blank" href="?page=mice_reservation&cmd=bill_new&invoice_id='.$value['mice_invoice_id'].'">'.$mice_code.'</a> ,';
                $vat_invoice_items[$value['vat_bill_id']]['payment_time'] = $value['payment_time_mice']!=''?date('d/m/Y',$value['payment_time_mice']):date('d/m/Y',$value['create_time_mice']);
            }
       }
       
       $vat_bill_detail = DB::fetch_all('SELECT
                                    vat_bill_detail.id,
                                    vat_bill_detail.vat_bill_id,
                                    vat_bill_detail.type as detail_type,
                                    vat_bill_detail.invoice_detail_id,
                                    vat_bill_detail.invoice_id,
                                    vat_bill_detail.description as detail_desc,
                                    vat_bill_detail.total_before_tax,
                                    vat_bill_detail.service_amount,
                                    vat_bill_detail.tax_amount,
                                    vat_bill_detail.total_amount,
                                    vat_bill_detail.invoice_detail_type,
                                    vat_bill_detail.service_code,
                                    extra_service.type as extra_service_type,
                                    vat_bill.vat_code,
                                    vat_bill.type,
                                    vat_bill.vat_type,
                                    vat_bill.guest_name,
                                    vat_bill.print_date,
                                    vat_bill.note,
                                    customer.name as customer_name,
                                    vat_bill.customer_name as customer_full_name,
                                    vat_bill.customer_tax_code as tax_code,
                                    bar.code as bar_code
                                FROM
                                    vat_bill_detail
                                    inner join vat_bill on vat_bill.id=vat_bill_detail.vat_bill_id
                                    left join customer on customer.id=vat_bill.customer_id
                                    left join extra_service on extra_service.code=vat_bill_detail.service_code and vat_bill_detail.invoice_detail_type=\'EXTRA_SERVICE\'
                                    left join bar_reservation on bar_reservation.id=vat_bill_detail.invoice_detail_id and vat_bill_detail.invoice_detail_type=\'BAR\'
                                    left join bar on bar_reservation.bar_id=bar.id
                                WHERE
                                    vat_bill.portal_id=\''.PORTAL_ID.'\' 
                                    '.$cond.'
                                    and (vat_bill.status is null or vat_bill.status!=\'CANCEL\')
                                order by
                                    vat_bill.print_date');
       $items = array();
       
       foreach($vat_bill_detail as $key=>$value){
            
            $check = false;
            if(Url::get('folio_code')){
                if(isset($vat_invoice_items[$value['vat_bill_id']])){
                    foreach($vat_invoice_items[$value['vat_bill_id']]['folio_arr'] as $k=>$v){
                        if($v['folio_code']==Url::get('folio_code')){
                            $check = true;
                        }
                    }
                }
            }else{
                $check = true;
            }
            
            if($check){
                if(!isset($items[$value['vat_bill_id']])){
                    $items[$value['vat_bill_id']]['id'] = $value['vat_bill_id'];
                    $items[$value['vat_bill_id']]['customer_code'] = $value['customer_name'];
                    $items[$value['vat_bill_id']]['booking_number'] = isset($vat_invoice_items[$value['vat_bill_id']])?$vat_invoice_items[$value['vat_bill_id']]['booking_number']:'';
                    $items[$value['vat_bill_id']]['folio_number'] = isset($vat_invoice_items[$value['vat_bill_id']])?$vat_invoice_items[$value['vat_bill_id']]['folio_number']:'';
                    $items[$value['vat_bill_id']]['vat_code'] = '<a target="_blank" href="?page=vat_bill&cmd=edit&id='.$value['vat_bill_id'].'">'.$value['vat_code'].'</a>';
                    $items[$value['vat_bill_id']]['print_date'] = date('d/m/Y',$value['print_date']);
                    $items[$value['vat_bill_id']]['payment_time'] = isset($vat_invoice_items[$value['vat_bill_id']])?$vat_invoice_items[$value['vat_bill_id']]['payment_time']:'';
                    $items[$value['vat_bill_id']]['buyer'] = $value['customer_full_name'];
                    $items[$value['vat_bill_id']]['tax_code'] = $value['tax_code'];
                    $items[$value['vat_bill_id']]['room_charge'] = 0;
                    $items[$value['vat_bill_id']]['extra_service_charge_room'] = 0;
                    $items[$value['vat_bill_id']]['breakfast'] = 0;
                    $items[$value['vat_bill_id']]['restaurant'] = 0;
                    $items[$value['vat_bill_id']]['banquet'] = 0;
                    $items[$value['vat_bill_id']]['minibar'] = 0;
                    $items[$value['vat_bill_id']]['laundry'] = 0;
                    $items[$value['vat_bill_id']]['equipment'] = 0;
                    $items[$value['vat_bill_id']]['trans'] = 0;
                    $items[$value['vat_bill_id']]['others'] = 0;
                    $items[$value['vat_bill_id']]['service_rate'] = 0;
                    $items[$value['vat_bill_id']]['total_revenue'] = 0;
                    $items[$value['vat_bill_id']]['tax_rate'] = 0;
                    $items[$value['vat_bill_id']]['total_amount'] = 0;
                    $items[$value['vat_bill_id']]['note'] = $value['note'];
                }
                $items[$value['vat_bill_id']]['service_rate'] += $value['service_amount'];
                $items[$value['vat_bill_id']]['total_revenue'] += $value['total_before_tax']+$value['service_amount'];
                $items[$value['vat_bill_id']]['tax_rate'] += $value['tax_amount'];
                $items[$value['vat_bill_id']]['total_amount'] += $value['total_amount'];
                                
                $this->map['service_rate'] += $value['service_amount'];
                $this->map['total_revenue'] += $value['total_before_tax']+$value['service_amount'];
                $this->map['tax_rate'] += $value['tax_amount'];
                $this->map['total_amount'] += $value['total_amount'];
                
                if(($value['detail_type']=='FOLIO' OR $value['detail_type']=='MICE') and ($value['invoice_detail_type']=='ROOM' OR ($value['invoice_detail_type']=='EXTRA_SERVICE' and ($value['service_code']=='LATE_CHECKIN' OR $value['service_code']=='LATE_CHECKOUT' OR $value['service_code']=='EARLY_CHECKIN' OR $value['service_code']=='ROOM'))))
                {
                    $items[$value['vat_bill_id']]['room_charge'] += $value['total_before_tax'];
                    $this->map['room_charge'] += $value['total_before_tax'];
                }
                elseif(($value['detail_type']=='FOLIO' OR $value['detail_type']=='MICE') and $value['invoice_detail_type']=='EXTRA_SERVICE' and $value['extra_service_type']=='ROOM' and $value['service_code']!='LATE_CHECKIN' and $value['service_code']!='LATE_CHECKOUT' and $value['service_code']!='EARLY_CHECKIN' and $value['service_code']!='ROOM' and $value['service_code']!='PTAS' and $value['service_code']!='KID BF' and substr($value['service_code'],0,7)!='TRANFER')
                {
                    $items[$value['vat_bill_id']]['extra_service_charge_room'] += $value['total_before_tax'];
                    $this->map['extra_service_charge_room'] += $value['total_before_tax'];
                }
                elseif(($value['detail_type']=='FOLIO' OR $value['detail_type']=='MICE') and $value['invoice_detail_type']=='EXTRA_SERVICE' and ($value['service_code']=='PTAS' OR $value['service_code']=='KID BF'))
                {
                    $items[$value['vat_bill_id']]['breakfast'] += $value['total_before_tax'];
                    $this->map['breakfast'] += $value['total_before_tax'];
                }
                elseif(($value['detail_type']=='BAR' OR (($value['detail_type']=='FOLIO' OR $value['detail_type']=='MICE') and $value['invoice_detail_type']=='BAR')) and $value['bar_code']!='MROOM' )
                {
                    $items[$value['vat_bill_id']]['restaurant'] += $value['total_before_tax'];
                    $this->map['restaurant'] += $value['total_before_tax'];
                }
                elseif($value['detail_type']=='BANQUET' OR ($value['bar_code']=='MROOM' and $value['invoice_detail_type']=='BAR'))
                {
                    $items[$value['vat_bill_id']]['banquet'] += $value['total_before_tax'];
                    $this->map['banquet'] += $value['total_before_tax'];
                }
                elseif(($value['detail_type']=='FOLIO' OR $value['detail_type']=='MICE') and $value['invoice_detail_type']=='MINIBAR')
                {
                    $items[$value['vat_bill_id']]['minibar'] += $value['total_before_tax'];
                    $this->map['minibar'] += $value['total_before_tax'];
                }
                elseif(($value['detail_type']=='FOLIO' OR $value['detail_type']=='MICE') and $value['invoice_detail_type']=='LAUNDRY')
                {
                    $items[$value['vat_bill_id']]['laundry'] += $value['total_before_tax'];
                    $this->map['laundry'] += $value['total_before_tax'];
                }
                elseif(($value['detail_type']=='FOLIO' OR $value['detail_type']=='MICE') and $value['invoice_detail_type']=='EQUIPMENT')
                {
                    $items[$value['vat_bill_id']]['equipment'] += $value['total_before_tax'];
                    $this->map['equipment'] += $value['total_before_tax'];
                }
                elseif(($value['detail_type']=='FOLIO' OR $value['detail_type']=='MICE') and $value['invoice_detail_type']=='EXTRA_SERVICE' and substr($value['service_code'],0,7)=='TRANFER')
                {
                    $items[$value['vat_bill_id']]['trans'] += $value['total_before_tax'];
                    $this->map['trans'] += $value['total_before_tax'];
                }
                else
                {
                    $items[$value['vat_bill_id']]['others'] += $value['total_before_tax'];
                    $this->map['others'] += $value['total_before_tax'];
                }
            }
            
       }
       
       //System::debug($items);
       $this->map['items'] = $items;
       $this->parse_layout('report',$this->map);
	}
}
?>