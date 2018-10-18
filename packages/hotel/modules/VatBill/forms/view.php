<?php
class VatBillViewForm extends Form
{
	function VatBillViewForm()
	{
		Form::Form('VatBillViewForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw()
	{
	   require_once 'packages/core/includes/utils/currency.php';
	   $this->map = array();
       $row = DB::fetch('
                        SELECT
                            vat_bill.*,
                            TO_CHAR(vat_bill.start_date,\'DD/MM/YYYY\') as start_date,
                            TO_CHAR(vat_bill.end_date,\'DD/MM/YYYY\') as end_date
                        FROM
                            vat_bill
                            left join customer on customer.id=vat_bill.customer_id
                        WHERE
                            vat_bill.id='.Url::get('id').'
                        ');
       $this->map += $row;
       $this->map['total_before_tax'] = $row['total_before_tax'];
       $this->map['tax_amount'] = $row['tax_amount'];
       $this->map['service_ammount'] = $row['service_amount'];
       $this->map['total_amount'] = $row['total_amount'];
       $this->map['recode'] = '';
       $recode_arr = DB::fetch_all('
                                        SELECT
                                            vat_bill_detail.reservation_id as id
                                        FROM
                                            vat_bill_detail
                                        WHERE
                                            vat_bill_detail.vat_bill_id='.Url::get('id').'
                                            and vat_bill_detail.reservation_id is not null
                                        ORDER BY id
                                        ');
       foreach($recode_arr as $key=>$value){
            $this->map['recode'] .= $this->map['recode']==''?$value['id']:($value['id']<$this->map['recode']?$value['id']:$this->map['recode']);
       }
       $vat_bill_detail = DB::fetch_all('
                                        SELECT
                                            vat_bill_detail.*,
                                            TO_CHAR(vat_bill_detail.date_use,\'DD/MM/YYYY\') as date_use,
                                            extra_service.code as extra_service_code,
                                            (extra_service_invoice_detail.quantity + NVL(extra_service_invoice_detail.change_quantity,0)) as extra_service_quantity,
                                            bar.code as bar_code 
                                        FROM
                                            vat_bill_detail
                                            left join extra_service_invoice_detail on extra_service_invoice_detail.id = vat_bill_detail.invoice_detail_id and vat_bill_detail.invoice_detail_type=\'EXTRA_SERVICE\'
                                            left join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                                            left join bar_reservation on bar_reservation.id=vat_bill_detail.invoice_detail_id and vat_bill_detail.invoice_detail_type=\'BAR\'
                                            left join bar on bar_reservation.bar_id=bar.id
                                        WHERE
                                            vat_bill_detail.vat_bill_id='.Url::get('id').'
                                        ORDER BY vat_bill_detail.id
                                        ');
       
       $vat_bill_detail_short = array();
       if($row['type']==''){
            $this->map['items'] = $vat_bill_detail;
       }else{
            if(Url::get('print_type')!='FULL')
           {
                /**
                 * tieu chi in hoa don thue:
                 * ++: tien phong: tien phong + EI,LO,LI
                 * ++: tien nha hang: tien module nha hang
                 * ++: tien dat tiec: tien module tiec
                 * ++: dich vu khac: cac khoan tien con lai.
                **/
                foreach($vat_bill_detail as $k => $v)
                {
                    if(($v['type']=='FOLIO' OR $v['type']=='MICE') and ($v['invoice_detail_type']=='ROOM' OR ($v['invoice_detail_type']=='EXTRA_SERVICE' and ($v['service_code']=='LATE_CHECKIN' OR $v['service_code']=='LATE_CHECKOUT' OR $v['service_code']=='EARLY_CHECKIN' OR $v['service_code']=='ROOM')))){
                        $id_pay = 'PAYMENT_ROOM';
                        $description = $row['description_room'];
                    }
                    elseif($v['type']=='BAR' OR (($v['type']=='FOLIO' OR $v['type']=='MICE') and $v['invoice_detail_type']=='BAR')){
                        $id_pay = 'PAYMENT_BAR';
                        $description = $row['description_bar'];
                    }
                    elseif($v['type']=='BANQUET'){
                        $id_pay = 'PAYMENT_PARTY';
                        $description = $row['description_banquet'];
                    }
                    else{
                        $id_pay = 'PAYMENT_EXTRA_SERVICE';
                        $description = $row['description_service'];
                    }
                    
                    if(!isset($vat_bill_detail_short[$id_pay])){
                        $vat_bill_detail_short[$id_pay]['description'] = $description;
                        $vat_bill_detail_short[$id_pay]['total_before_tax'] = 0;
                        $vat_bill_detail_short[$id_pay]['tax_amount'] = 0;
                        $vat_bill_detail_short[$id_pay]['service_amount'] = 0;
                        $vat_bill_detail_short[$id_pay]['total_amount'] = 0;
                    }
                    $vat_bill_detail_short[$id_pay]['total_before_tax'] += $v['total_before_tax'];
                    $vat_bill_detail_short[$id_pay]['tax_amount'] += $v['tax_amount'];
                    $vat_bill_detail_short[$id_pay]['service_amount'] += $v['service_amount'];
                    $vat_bill_detail_short[$id_pay]['total_amount'] += $v['total_amount'];
                }
                $this->map['items'] = $vat_bill_detail_short;
           }
           else
           {
                foreach($vat_bill_detail as $k => $v)
                {
                    $quantity = 0;
                    if(($v['type']=='FOLIO' OR $v['type']=='MICE') and ($v['invoice_detail_type']=='ROOM' OR ($v['invoice_detail_type']=='EXTRA_SERVICE' and $v['extra_service_code']=='LATE_CHECKIN'))){
                        $id_pay = 'PAYMENT_ROOM';
                        $description = Portal::language('room_charge');
                    }
                    elseif(($v['type']=='FOLIO' OR $v['type']=='MICE') and (($v['invoice_detail_type']=='EXTRA_SERVICE' and ($v['extra_service_code']=='EARLY_CHECKIN' or $v['extra_service_code']=='LATE_CHECKOUT')))){
                        $id_pay = 'PAYMENT_EI_LO';
                        $description = Portal::language('early_checkin').','.Portal::language('late_checkout');
                    }
                    elseif(($v['type']=='FOLIO' OR $v['type']=='MICE') and (($v['invoice_detail_type']=='EXTRA_SERVICE' and $v['extra_service_code']=='EXTRA_BED'))){
                        $id_pay = 'PAYMENT_EXTRA_BED';
                        $description = Portal::language('extra_bed');
                        $quantity += $v['extra_service_quantity'];
                    }
                    elseif(($v['type']=='FOLIO' OR $v['type']=='MICE') and (($v['invoice_detail_type']=='EXTRA_SERVICE' and ($v['extra_service_code']=='PTAS' or $v['extra_service_code']=='KID BF')))){
                        $id_pay = 'PAYMENT_BF';
                        $description = Portal::language('breakfast');
                    }
                    elseif(($v['type']=='BAR' OR (($v['type']=='FOLIO' OR $v['type']=='MICE') and $v['invoice_detail_type']=='BAR')) and $v['bar_code']!='MROOM'){
                        $id_pay = 'PAYMENT_BAR';
                        $description = Portal::language('foods_and_drinks');
                    }
                    elseif($v['type']=='BANQUET' OR (($v['type']=='BAR' OR (($v['type']=='FOLIO' OR $v['type']=='MICE') and $v['invoice_detail_type']=='BAR')) and $v['bar_code']=='MROOM')){
                        $id_pay = 'PAYMENT_PARTY';
                        $description = Portal::language('banquet');
                    }
                    elseif(($v['type']=='FOLIO' OR $v['type']=='MICE') and $v['invoice_detail_type']=='MINIBAR'){
                        $id_pay = 'PAYMENT_MNIBAR';
                        $description = Portal::language('minibar');
                    }
                    elseif(($v['type']=='FOLIO' OR $v['type']=='MICE') and $v['invoice_detail_type']=='LAUNDRY'){
                        $id_pay = 'PAYMENT_LAUNDRY';
                        $description = Portal::language('laundry');
                    }
                    elseif(($v['type']=='FOLIO' OR $v['type']=='MICE') and $v['invoice_detail_type']=='EQUIPMENT'){
                        $id_pay = 'PAYMENT_EQUIPMENT';
                        $description = Portal::language('equipment');
                    }
                    elseif(($v['type']=='FOLIO' OR $v['type']=='MICE') and $v['invoice_detail_type']=='EXTRA_SERVICE' and substr($v['extra_service_code'],0,7)=='TRANFER'){
                        $id_pay = 'PAYMENT_TRANFER';
                        $description = Portal::language('tranfer');
                    }
                    else{
                        $id_pay = 'PAYMENT_EXTRA_SERVICE';
                        $description = Portal::language('service_other');
                    }
                    
                    if(!isset($vat_bill_detail_short[$id_pay])){
                        $vat_bill_detail_short[$id_pay]['description'] = $description;
                        $vat_bill_detail_short[$id_pay]['total_before_tax'] = 0;
                        $vat_bill_detail_short[$id_pay]['tax_amount'] = 0;
                        $vat_bill_detail_short[$id_pay]['service_amount'] = 0;
                        $vat_bill_detail_short[$id_pay]['total_amount'] = 0;
                        $vat_bill_detail_short[$id_pay]['quantity'] = 0;
                    }
                    $vat_bill_detail_short[$id_pay]['total_before_tax'] += $v['total_before_tax'];
                    $vat_bill_detail_short[$id_pay]['tax_amount'] += $v['tax_amount'];
                    $vat_bill_detail_short[$id_pay]['service_amount'] += $v['service_amount'];
                    $vat_bill_detail_short[$id_pay]['total_amount'] += $v['total_amount'];
                    $vat_bill_detail_short[$id_pay]['quantity'] += $quantity;
                }
                $this->map['items'] = $vat_bill_detail_short;
           }
       }
           
       $this->map['total_amount_in_word'] = currency_to_text(round($row['total_amount'],0));
       $user_data = Session::get('user_data');
       $this->map['user_name'] = isset($user_data['full_name'])?$user_data['full_name']:Session::get('user_id');
       
       if(Url::get('print_type')!='FULL')
            $this->parse_layout('view',$this->map);
       else
            $this->parse_layout('view_detail',$this->map);
    }
}
?>
