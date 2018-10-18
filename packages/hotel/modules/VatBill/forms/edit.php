<?php
/**
 * VatBillEditForm
 * 
 * @package vpqh8086
 * @author tocdo
 * @copyright 2017
 * @version $Id$
 * @access public
 */
class VatBillEditForm extends Form
{
	function VatBillEditForm()
	{
		Form::Form('VatBillEditForm');
        $this->link_css('packages/hotel/skins/default/css/font-awesome-4.5.0/css/font-awesome.min.css');
        $this->link_css('packages/hotel/modules/VatBill/vat_bill.css');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/hotel/modules/VatBill/vat_bill.js');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
    function on_submit()
    {
        //System::debug($_REQUEST['detail']); die;
        /** check config vat code **/
        if( (Url::get('cmd')=='add' and Url::get('vat_code')!='' and $vat_code=DB::fetch('select id from vat_bill where (status is null or status!=\'CANCEL\') and vat_code=\''.Url::get('vat_code').'\'','id')) OR (Url::get('cmd')=='edit' and Url::get('vat_code')!='' and $vat_code=DB::fetch('select id from vat_bill where (status is null or status!=\'CANCEL\') and vat_code=\''.Url::get('vat_code').'\' and id!='.Url::get('id'),'id')) ) {
            $this->error('vat_code_conflict',Portal::language('vat_code_conflict_with_vat_bill').'<a target="_blank" href="?page=vat_bill&cmd=edit&id='.$vat_code.'">'.Url::get('vat_code').'</a>',false);
            return false;
        }
        /** update customer **/
        if(Url::get('customer_id') and Url::get('customer_id')!='' and DB::exists('select id from customer where id='.Url::get('customer_id'))) {
            $customer_id = Url::get('customer_id');
            
        } elseif(Url::get('customer_name') and Url::get('customer_name')!='') {
            $customer_id = DB::insert('customer',array(
                                                    'name'=>Url::get('customer_name')
                                                    ,'address'=>Url::get('customer_address')
                                                    ,'tax_code'=>Url::get('customer_tax_code')
                                                    ,'bank_code'=>Url::get('customer_bank_code')
                                                    ));
        } else {
            $customer_id = '';
        }
        
        /** update vat bill info **/
        $recode = array(
                        'vat_type'=>Url::get('act'),
                        'start_date'=>Url::get('start_date')!=''?Date_Time::to_orc_date(Url::get('start_date')):'',
                        'end_date'=>Url::get('end_date')!=''?Date_Time::to_orc_date(Url::get('end_date')):'',
                        'guest_name'=>Url::get('guest_name'),
                        'customer_id'=>$customer_id,
                        'customer_name'=>Url::get('customer_name'),
                        'customer_address'=>Url::get('customer_address'),
                        'customer_tax_code'=>Url::get('customer_tax_code'),
                        'customer_bank_code'=>Url::get('customer_bank_code'),
                        'payment_method'=>Url::get('payment_method'),
                        'description_room'=>Url::get('description_room'),
                        'description_bar'=>Url::get('description_bar'),
                        'description_banquet'=>Url::get('description_banquet'),
                        'description_service'=>Url::get('description_service'),
                        'note'=>Url::get('note'),
                        'total_before_tax'=>System::calculate_number(Url::get('total_before_tax')),
                        'service_amount'=>System::calculate_number(Url::get('service_amount')),
                        'tax_amount'=>System::calculate_number(Url::get('tax_amount')),
                        'total_amount'=>System::calculate_number(Url::get('total_amount')),
                        'last_editer'=>User::id(),
                        'last_edit_time'=>time(),
                        'print_date'=>Date_Time::to_time($_REQUEST['print_date']),
                        'portal_id'=>PORTAL_ID
                        );
        
        if(Url::get('cmd')=='add') {
            $recode['type'] = Url::get('type');
            $recode['vat_code'] = Url::get('vat_code');
            $recode['creater'] = User::id();
            $recode['create_time'] = time();
            if(Url::get('act')=='PRINT') {
                $recode['count_print'] = 1;
            }
            $vat_bill_id = DB::insert('vat_bill',$recode);
        }
        else {
            $vat_bill_id = Url::get('id');
            $recode['vat_code'] = Url::get('vat_code');
            $count_print = DB::fetch('select NVL(vat_bill.count_print,0) as count_print from vat_bill where vat_bill.id='.$vat_bill_id,'count_print');
            if(Url::get('act')=='PRINT') {
                $count_print+=1;
                $recode['count_print'] = $count_print;
            }
            DB::update('vat_bill',$recode,'id='.$vat_bill_id);
        }
        
        /** history print **/
        if(Url::get('act')=='PRINT') {
            DB::insert('vat_history_print',array('vat_bill_id'=>$vat_bill_id,'user_print'=>User::id(),'time_print'=>time()));
        }
        
        /** update vat bill detail **/
        $detail_ids = 0;
        $invoice_ids = 0;
        if(isset($_REQUEST['detail'])) {
            $invoice_bill = array();
            foreach($_REQUEST['detail'] as $key=>$value) {
                if($value['invoice_detail_type'] == 'ROOM' || ($value['invoice_detail_type'] == 'EXTRA_SERVICE' && ($value['service_code'] == 'LATE_CHECKIN' || $value['service_code'] == 'LATE_CHECKOUT' || $value['service_code'] == 'EARLY_CHECKIN')))
                {
                    $value['service_code'] = 'ROOM';                    
                }
                $array = array(
                                'vat_bill_id'=>$vat_bill_id,
                                'reservation_id'=>$value['reservation_id'],
                                'invoice_detail_id'=>$value['invoice_detail_id'],
                                'invoice_detail_type'=>$value['invoice_detail_type'],
                                'invoice_id'=>$value['invoice_id'],
                                'service_code'=>$value['service_code'],
                                'description'=>$value['description'],
                                'date_use'=>$value['date_use']!=''?Date_Time::to_orc_date($value['date_use']):'',
                                'total_before_tax'=>System::calculate_number($value['total_before_tax']),
                                'service_rate'=>System::calculate_number($value['service_rate']),
                                'service_amount'=>System::calculate_number($value['service_amount']),
                                'tax_rate'=>System::calculate_number($value['tax_rate']),
                                'tax_amount'=>System::calculate_number($value['tax_amount']),
                                'total_amount'=>System::calculate_number($value['total_amount'])
                                );
                if($value['invoice_id']!='') {
                    $invoice_bill[$value['invoice_id']]['id'] = $value['invoice_id'];
                }
                if($value['id']=='') {
                    $array['type'] = Url::get('type');
                    $detail_id = DB::insert('vat_bill_detail',$array);
                }
                else {
                    $detail_id = $value['id'];
                    DB::update('vat_bill_detail',$array,'id='.$detail_id);
                }
                if($detail_ids==0)
                    $detail_ids = $detail_id;
                else
                    $detail_ids .= ','.$detail_id;
            }
            
            /** update vat invoice ***/
            foreach($invoice_bill as $key=>$value) {
                if( $invoice_id = DB::fetch('select id from vat_invoice where vat_bill_id='.$vat_bill_id.' and invoice_id='.$value['id'],'id')) {
                    
                } else {
                    $invoice_id = DB::insert('vat_invoice',array('vat_bill_id'=>$vat_bill_id,'invoice_id'=>$value['id']));
                }
                if($invoice_ids==0)
                    $invoice_ids = $invoice_id;
                else
                    $invoice_ids .= ','.$invoice_id;
            }
        }
        /** delete detail, vat invoice **/
        DB::delete('vat_bill_detail','id not in ('.$detail_ids.') and vat_bill_id='.$vat_bill_id);
        DB::delete('vat_invoice','id not in ('.$invoice_ids.') and vat_bill_id='.$vat_bill_id);
        if(Url::get('act')=='PRINT')
            Url::redirect('vat_bill',array('cmd'=>'view','id'=>$vat_bill_id,'print_type'=>Url::get('print_type')));
        else
            Url::redirect('vat_bill',array('type'=>Url::get('type')));
    }
	function draw()
	{
	   $this->map = array();
       if(!isset($_REQUEST['print_date']))
       {
            $_REQUEST['print_date'] = date('d/m/Y', time());
       }
       $this->map['history'] = array();
       $this->map['count_print'] = 0;
       $this->map['last_print'] = '';
       $this->map['type'] = Url::get('type')?Url::get('type'):'';
       if(!isset($_REQUEST['detail'])) {
            $this->map['items'] = array();
            /** check dieu kien nhap moi hay edit **/
            if(Url::get('cmd')=='add') {
                $this->map['total_before_tax'] = 0;
                $this->map['service_amount'] = 0;
                $this->map['tax_amount'] = 0;
                $this->map['total_amount'] = 0;
                $this->map['start_date'] = '';
                $this->map['end_date'] = '';
                $this->map['invoice_ids'] = Url::get('invoice_id');
                $_REQUEST['description_room']= Portal::language('room_charge');
                $_REQUEST['description_bar']= Portal::language('foods_and_drinks');;
                $_REQUEST['description_service']=Portal::language('service_other');;
                $_REQUEST['description_banquet']=Portal::language('banquet');
                /** check dieu kien hoa don nha hang hay hoa don le tan **/
                if(Url::get('type')=='BAR') {
                    $this->map['type'] = 'BAR';
                    /** lay tat ca dat ban co trong invoice_id **/
                    $bar_reservation_group = DB::fetch_all('
                                                    SELECT
                                                        bar_reservation.id,
                                                        bar_reservation.id as bar_reservation_id,
                                                        NVL(bar_reservation.tax_rate,0) as tax_rate,
                                                        NVL(bar_reservation.bar_fee_rate,0) as bar_fee_rate,
                                                        NVL(bar_reservation.total,0) as total_amount,
                                                        bar_reservation.arrival_time,
                                                        bar_reservation.departure_time,
                                                        bar_reservation.receiver_name,
                                                        bar_reservation.customer_id,
                                                        customer.name as customer_code,
                                                        customer.def_name as customer_name,
                                                        customer.tax_code as customer_tax_code,
                                                        customer.address as customer_address,
                                                        customer.bank_code as customer_bank_code
                                                    FROM
                                                        bar_reservation_product
                                                        inner join bar_reservation on bar_reservation.id=bar_reservation_product.bar_reservation_id
                                                        left join customer on customer.id=bar_reservation.customer_id
                                                    WHERE
                                                        bar_reservation.id in ('.Url::get('invoice_id').')
                                                    ');
                    $vat_bill_detail = DB::fetch_all('
                                                    SELECT
                                                        vat_bill_detail.*
                                                    FROM
                                                        vat_bill_detail
                                                        inner join vat_bill on vat_bill_detail.vat_bill_id=vat_bill.id
                                                    WHERE
                                                        vat_bill_detail.invoice_id in ('.Url::get('invoice_id').')
                                                        and vat_bill_detail.type=\'BAR\' 
                                                        and (vat_bill.status is null or vat_bill.status!=\'CANCEL\')
                                                    ');
                    $bar_reservation = array();
                    foreach($bar_reservation_group as $key=>$value) {
                        //$bar_reservation[$value['bar_reservation_id']]['id'] = $value['bar_reservation_id'];
                        $bar_reservation[$value['bar_reservation_id']]['invoice_detail_id'] = $value['bar_reservation_id'];
                        $bar_reservation[$value['bar_reservation_id']]['invoice_id'] = $value['bar_reservation_id'];
                        $bar_reservation[$value['bar_reservation_id']]['date_use'] = date('d/m/Y',$value['departure_time']);
                        $bar_reservation[$value['bar_reservation_id']]['description'] = Portal::language('restaurant');
                        $bar_reservation[$value['bar_reservation_id']]['type'] = 'BAR';
                        $bar_reservation[$value['bar_reservation_id']]['invoice_detail_type'] = 'BAR';
                        $bar_reservation[$value['bar_reservation_id']]['extra_service_code_type'] = 'BAR';
                        $bar_reservation[$value['bar_reservation_id']]['total_before_tax'] = 0;
                        $bar_reservation[$value['bar_reservation_id']]['service_rate'] = $value['bar_fee_rate'];
                        $bar_reservation[$value['bar_reservation_id']]['service_amount'] = 0;
                        $bar_reservation[$value['bar_reservation_id']]['tax_rate'] = $value['tax_rate'];
                        $bar_reservation[$value['bar_reservation_id']]['tax_amount'] = 0;
                        $bar_reservation[$value['bar_reservation_id']]['total_amount'] = $value['total_amount'];
                        
                        $this->map['guest_name'] = $value['receiver_name'];
                        $this->map['customer_id'] = $value['customer_id']!=''?$value['customer_id']:'';
                        $this->map['customer_code'] = $value['customer_id']!=''?$value['customer_code']:'';
                        $this->map['customer_name'] = $value['customer_id']!=''?$value['customer_name']:'';
                        $this->map['customer_tax_code'] = $value['customer_id']!=''?$value['customer_tax_code']:'';
                        $this->map['customer_address'] = $value['customer_id']!=''?$value['customer_address']:'';
                        $this->map['customer_bank_code'] = $value['customer_id']!=''?$value['customer_bank_code']:'';
                        
                        $this->map['start_date'] = ($this->map['start_date']=='')?date('d/m/Y',$value['arrival_time']):( (Date_Time::to_time($this->map['start_date'])<$value['arrival_time'])?$this->map['start_date']:date('d/m/Y',$value['arrival_time']) );
                        $this->map['end_date'] = ($this->map['end_date']=='')?date('d/m/Y',$value['departure_time']):( (Date_Time::to_time($this->map['end_date'])>$value['departure_time'])?$this->map['end_date']:date('d/m/Y',$value['departure_time']) );
                    }
                    /** lay cac san pham trong invoice_id da tao hoa don vat **/
                    foreach($vat_bill_detail as $key=>$value) {
                        if(isset($bar_reservation[$value['invoice_id']])) {
                            $bar_reservation[$value['invoice_id']]['total_amount'] -= $value['total_amount'];
                            if($bar_reservation[$value['invoice_id']]['total_amount']<=0) {
                                unset($bar_reservation[$value['invoice_id']]);
                            }
                        }
                    }
                    foreach($bar_reservation as $key=>$value) {
                        $bar_reservation[$key]['total_before_tax'] = round($value['total_amount']/( (1+$value['service_rate']/100) * (1+$value['tax_rate']/100) ),2);
                        $bar_reservation[$key]['service_amount'] = round($bar_reservation[$key]['total_before_tax']*($value['service_rate']/100),2);
                        $bar_reservation[$key]['tax_amount'] = $value['total_amount'] - $bar_reservation[$key]['total_before_tax'] - $bar_reservation[$key]['service_amount'];
                        
                        $this->map['total_before_tax'] += $bar_reservation[$key]['total_before_tax'];
                        $this->map['service_amount'] += $bar_reservation[$key]['service_amount'];
                        $this->map['tax_amount'] += $bar_reservation[$key]['tax_amount'];
                        $this->map['total_amount'] += $value['total_amount'];
                    }
                    $_REQUEST += $this->map;
                    $this->map['items'] = $bar_reservation;
                    //System::debug($bar_reservation);
                }
                if(Url::get('type')=='BANQUET') {
                    $this->map['type'] = 'BANQUET';
                    /** lay tat ca dat ban co trong invoice_id **/
                    $party_reservation_group = DB::fetch_all('
                                                    SELECT
                                                        party_reservation.id,
                                                        party_reservation.id as party_reservation_id,
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
                                                        \'\' as customer_id,
                                                        \'\' as customer_code,
                                                        \'\' as customer_name,
                                                        \'\' as customer_tax_code,
                                                        \'\' as customer_address,
                                                        \'\' as customer_bank_code
                                                    FROM
                                                        party_reservation
                                                    WHERE
                                                        party_reservation.id in ('.Url::get('invoice_id').')
                                                    ');
                    $vat_bill_detail = DB::fetch_all('
                                                    SELECT
                                                        vat_bill_detail.*
                                                    FROM
                                                        vat_bill_detail
                                                        inner join vat_bill on vat_bill_detail.vat_bill_id=vat_bill.id
                                                    WHERE
                                                        vat_bill_detail.invoice_id in ('.Url::get('invoice_id').')
                                                        and vat_bill_detail.type=\'BANQUET\' 
                                                        and (vat_bill.status is null or vat_bill.status!=\'CANCEL\')
                                                    ');
                    $party_reservation = array();
                    foreach($party_reservation_group as $key=>$value) {
                        $party_reservation[$value['party_reservation_id']]['invoice_detail_id'] = $value['party_reservation_id'];
                        $party_reservation[$value['party_reservation_id']]['invoice_id'] = $value['party_reservation_id'];
                        $party_reservation[$value['party_reservation_id']]['date_use'] = date('d/m/Y',$value['checkout_time']);
                        $party_reservation[$value['party_reservation_id']]['description'] = Portal::language('party');
                        $party_reservation[$value['party_reservation_id']]['type'] = 'BANQUET';
                        $party_reservation[$value['party_reservation_id']]['invoice_detail_type'] = 'BANQUET';
                        $party_reservation[$value['party_reservation_id']]['extra_service_code_type'] = 'BANQUET';
                        $party_reservation[$value['party_reservation_id']]['total_before_tax'] = 0;
                        $party_reservation[$value['party_reservation_id']]['service_rate'] = $value['service_rate'];
                        $party_reservation[$value['party_reservation_id']]['service_amount'] = 0;
                        $party_reservation[$value['party_reservation_id']]['tax_rate'] = $value['tax_rate'];
                        $party_reservation[$value['party_reservation_id']]['tax_amount'] = 0;
                        $party_reservation[$value['party_reservation_id']]['total_amount'] = $value['total_amount']+$value['deposit_1']+$value['deposit_2']+$value['deposit_3']+$value['deposit_4'];
                        
                        $this->map['guest_name'] = $value['full_name'];
                        $this->map['customer_id'] = $value['customer_id']!=''?$value['customer_id']:'';
                        $this->map['customer_code'] = $value['customer_id']!=''?$value['customer_code']:'';
                        $this->map['customer_name'] = $value['customer_id']!=''?$value['customer_name']:'';
                        $this->map['customer_tax_code'] = $value['customer_id']!=''?$value['customer_tax_code']:'';
                        $this->map['customer_address'] = $value['customer_id']!=''?$value['customer_address']:'';
                        $this->map['customer_bank_code'] = $value['customer_id']!=''?$value['customer_bank_code']:'';
                        
                        $this->map['start_date'] = ($this->map['start_date']=='')?date('d/m/Y',$value['checkin_time']):( (Date_Time::to_time($this->map['start_date'])<$value['checkin_time'])?$this->map['start_date']:date('d/m/Y',$value['checkin_time']) );
                        $this->map['end_date'] = ($this->map['end_date']=='')?date('d/m/Y',$value['checkout_time']):( (Date_Time::to_time($this->map['end_date'])>$value['checkout_time'])?$this->map['end_date']:date('d/m/Y',$value['checkout_time']) );
                    }
                    /** lay cac san pham trong invoice_id da tao hoa don vat **/
                    foreach($vat_bill_detail as $key=>$value) {
                        if(isset($party_reservation[$value['invoice_id']])) {
                            $party_reservation[$value['invoice_id']]['total_amount'] -= $value['total_amount'];
                            if($party_reservation[$value['invoice_id']]['total_amount']<=0) {
                                unset($party_reservation[$value['invoice_id']]);
                            }
                        }
                    }
                    foreach($party_reservation as $key=>$value) {
                        $party_reservation[$key]['total_before_tax'] = round($value['total_amount']/( (1+$value['service_rate']/100) * (1+$value['tax_rate']/100) ),2);
                        $party_reservation[$key]['service_amount'] = round($party_reservation[$key]['total_before_tax']*($value['service_rate']/100),2);
                        $party_reservation[$key]['tax_amount'] = $value['total_amount'] - $party_reservation[$key]['total_before_tax'] - $party_reservation[$key]['service_amount'];
                        
                        $this->map['total_before_tax'] += $party_reservation[$key]['total_before_tax'];
                        $this->map['service_amount'] += $party_reservation[$key]['service_amount'];
                        $this->map['tax_amount'] += $party_reservation[$key]['tax_amount'];
                        $this->map['total_amount'] += $value['total_amount'];
                    }
                    $_REQUEST += $this->map;
                    $this->map['items'] = $party_reservation;
                    //System::debug($bar_reservation);
                }
                elseif(Url::get('type')=='FOLIO') {
                    $this->map['type'] = 'FOLIO';
                    /** lay chi tiet cua folio co trong invoice_id **/
                    $traveller_folio = DB::fetch_all('
                                                    SELECT 
                                                        traveller_folio.folio_id || \'_\' || traveller_folio.type || \'_\' || traveller_folio.invoice_id as id,
                                                        traveller_folio.type,
                                                        traveller_folio.invoice_id,
                                                        traveller_folio.folio_id,
                                                        NVL(traveller_folio.total_amount,0) as total_amount,
                                                        NVL(traveller_folio.service_rate,0) as service_rate,
                                                        NVL(traveller_folio.tax_rate,0) as tax_rate,
                                                        TO_CHAR(traveller_folio.date_use,\'DD/MM/YYYY\') as date_use,
                                                        traveller_folio.description,
                                                        traveller_folio.reservation_id,
                                                        traveller.first_name || \' \' || traveller.last_name as traveller_name,
                                                        customer.id as customer_id,
                                                        customer.name as customer_code,
                                                        customer.def_name as customer_name,
                                                        customer.tax_code as customer_tax_code,
                                                        customer.address as customer_address,
                                                        customer.bank_code as customer_bank_code,
                                                        extra_service.code as service_code
                                                    FROM
                                                        traveller_folio
                                                        inner join folio on folio.id=traveller_folio.folio_id
                                                        left join reservation_traveller on reservation_traveller.id=folio.reservation_traveller_id
                                                        left join traveller on reservation_traveller.traveller_id=traveller.id
                                                        inner join reservation_room on reservation_room.id=traveller_folio.reservation_room_id
                                                        inner join reservation on reservation.id=reservation_room.reservation_id
                                                        left join customer on customer.id=reservation.customer_id
                                                        left join extra_service_invoice_detail on extra_service_invoice_detail.id = traveller_folio.invoice_id
                                                        left join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                                                    WHERE
                                                        folio.id in ('.Url::get('invoice_id').')
                                                        and traveller_folio.type!=\'DISCOUNT\' and traveller_folio.type!=\'DEPOSIT\' and traveller_folio.type!=\'DEPOSIT_GROUP\'
                                                    ');
                    $folio = array();
                    foreach($traveller_folio as $key=>$value){
                        $id = $value['invoice_id'].'_'.$value['type'];
                        if(!isset($folio[$id])) {
                            //$folio[$id]['id'] = $id;
                            $folio[$id]['reservation_id'] = $value['reservation_id'];
                            $folio[$id]['service_code'] = $value['service_code'];
                            $folio[$id]['invoice_detail_id'] = $value['invoice_id'];
                            $folio[$id]['invoice_detail_type'] = $value['type'];
                            $folio[$id]['type'] = 'FOLIO';
                            $folio[$id]['invoice_id'] = $value['folio_id'];
                            $folio[$id]['date_use'] = $value['date_use'];
                            $folio[$id]['description'] = $value['description'];
                            $folio[$id]['total_before_tax'] = 0;
                            $folio[$id]['service_rate'] = $value['service_rate'];
                            $folio[$id]['service_amount'] = 0;
                            $folio[$id]['tax_rate'] = $value['tax_rate'];
                            $folio[$id]['tax_amount'] = 0;
                            $folio[$id]['total_amount'] = 0;
                        }
                        $folio[$id]['total_amount'] += $value['total_amount'];
                        
                        $this->map['guest_name'] = $value['traveller_name'];
                        $this->map['customer_id'] = $value['customer_id']!=''?$value['customer_id']:'';
                        $this->map['customer_code'] = $value['customer_id']!=''?$value['customer_code']:'';
                        $this->map['customer_name'] = $value['customer_id']!=''?$value['customer_name']:'';
                        $this->map['customer_tax_code'] = $value['customer_id']!=''?$value['customer_tax_code']:'';
                        $this->map['customer_address'] = $value['customer_id']!=''?$value['customer_address']:'';
                        $this->map['customer_bank_code'] = $value['customer_id']!=''?$value['customer_bank_code']:'';
                        
                        $this->map['start_date'] = ($this->map['start_date']=='')?$value['date_use']:( (Date_Time::to_time($this->map['start_date'])<Date_Time::to_time($value['date_use']))?$this->map['start_date']:$value['date_use'] );
                        $this->map['end_date'] = ($this->map['end_date']=='')?$value['date_use']:( (Date_Time::to_time($this->map['end_date'])>Date_Time::to_time($value['date_use']))?$this->map['end_date']:$value['date_use'] );
                    }
                    $vat_bill_detail = DB::fetch_all('
                                                    SELECT
                                                        vat_bill_detail.*
                                                    FROM
                                                        vat_bill_detail
                                                        inner join vat_bill on vat_bill_detail.vat_bill_id=vat_bill.id
                                                    WHERE
                                                        vat_bill_detail.invoice_id in ('.Url::get('invoice_id').')
                                                        and vat_bill_detail.type=\'FOLIO\' 
                                                        and (vat_bill.status is null or vat_bill.status!=\'CANCEL\')
                                                    ');
                    foreach($vat_bill_detail as $key=>$value) {
                        $id = $value['invoice_detail_id'].'_'.$value['invoice_detail_type'];
                        if(isset($folio[$id])) {
                            $folio[$id]['total_amount'] -= $value['total_amount'];
                        }
                        if(isset($folio[$id]) and $folio[$id]['total_amount']<=0) {
                            unset($folio[$id]);
                        }
                    }
                    
                    foreach($folio as $key=>$value) {
                        $folio[$key]['total_before_tax'] = round($value['total_amount']/( (1+$value['service_rate']/100) * (1+$value['tax_rate']/100) ),2);
                        $folio[$key]['service_amount'] = round($folio[$key]['total_before_tax']*($value['service_rate']/100),2);
                        //$folio[$key]['tax_amount'] = $value['total_amount'] - $folio[$key]['total_before_tax'] - $folio[$key]['service_amount'];
                        $folio[$key]['tax_amount'] = $value['total_amount'] - $folio[$key]['total_before_tax'] - $folio[$key]['service_amount'];
                        //$folio[$key]['service_amount'] = $value['total_amount'] - $folio[$key]['total_before_tax'] - $folio[$key]['tax_amount'];
                        
                        $this->map['total_before_tax'] += $folio[$key]['total_before_tax'];
                        $this->map['service_amount'] += $folio[$key]['service_amount'];
                        $this->map['tax_amount'] += $folio[$key]['tax_amount'];
                        $this->map['total_amount'] += $value['total_amount'];
                    }
                    $_REQUEST += $this->map;
                    $this->map['items'] = $folio;
                    //System::debug($folio);
                }else if(Url::get('type')=='MICE') 
                {
                    $this->map['type'] = 'MICE';
                    /** lay chi tiet cua mice co trong invoice_id **/
                    $mice_invoice_detail = DB::fetch_all('
                                                SELECT 
                                                    mice_invoice_detail.mice_invoice_id || \'_\' || mice_invoice_detail.invoice_id || \'_\' || mice_invoice_detail.type as id,
                                                    mice_invoice_detail.type,
                                                    mice_invoice_detail.invoice_id,
                                                    mice_invoice.id as mice_invoice_id,
                                                    NVL(mice_invoice_detail.total_amount,0) as total_amount,
                                                    NVL(mice_invoice_detail.service_rate,0) as service_rate,
                                                    NVL(mice_invoice_detail.tax_rate,0) as tax_rate,
                                                    TO_CHAR(mice_invoice_detail.date_use,\'DD/MM/YYYY\') as date_use,
                                                    mice_invoice_detail.description,
                                                    mice_reservation.contact_name as traveller_name,
                                                    customer.id as customer_id,
                                                    customer.name as customer_code,
                                                    customer.def_name as customer_name,
                                                    customer.tax_code as customer_tax_code,
                                                    customer.address as customer_address,
                                                    customer.bank_code as customer_bank_code,
                                                    extra_service.code as service_code
                                                FROM
                                                    mice_invoice_detail
                                                    INNER JOIN mice_invoice ON mice_invoice.id = mice_invoice_detail.mice_invoice_id
                                                    INNER JOIN mice_reservation ON mice_reservation.id = mice_invoice.mice_reservation_id
                                                    INNER JOIN customer ON customer.id = mice_reservation.customer_id
                                                    left join extra_service_invoice_detail on extra_service_invoice_detail.id = mice_invoice_detail.invoice_id AND mice_invoice_detail.type=\'EXTRA_SERVICE\'
                                                    left join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                                                WHERE
                                                    mice_invoice.id in ('.Url::get('invoice_id').')
                                                    and mice_invoice_detail.type !=\'DISCOUNT\' and mice_invoice_detail.type !=\'DEPOSIT\' and mice_invoice_detail.type !=\'DEPOSIT_MICE\'
                                                ORDER BY
                                                    mice_invoice_detail.id ASC
                                                ');
                    //System::debug($mice_invoice_detail);
                    $check_tax_rate = '';
                    $check_service_rate = '';
                    $mice_invoice = array();
                    foreach($mice_invoice_detail as $key=>$value)
                    {
                        $id = $value['invoice_id'].'_'.$value['type'];
                        if(!isset($mice_invoice[$id])) 
                        {
                            //$folio[$id]['id'] = $id;
                            $mice_invoice[$id]['service_code'] = $value['service_code'];
                            $mice_invoice[$id]['invoice_detail_id'] = $value['invoice_id'];
                            $mice_invoice[$id]['invoice_detail_type'] = $value['type'];
                            $mice_invoice[$id]['type'] = 'MICE';
                            $mice_invoice[$id]['invoice_id'] = $value['mice_invoice_id'];
                            $mice_invoice[$id]['date_use'] = $value['date_use'];
                            $mice_invoice[$id]['description'] = $value['description'];
                            $mice_invoice[$id]['total_before_tax'] = 0;
                            $mice_invoice[$id]['service_rate'] = $value['service_rate'];
                            $mice_invoice[$id]['service_amount'] = 0;
                            $mice_invoice[$id]['tax_rate'] = $value['tax_rate'];
                            $mice_invoice[$id]['tax_amount'] = 0;
                            $mice_invoice[$id]['total_amount'] = 0;
                        }
                        $mice_invoice[$id]['total_amount'] += $value['total_amount'];
                        
                        $this->map['guest_name'] = $value['traveller_name'];
                        $this->map['customer_id'] = $value['customer_id']!=''?$value['customer_id']:'';
                        $this->map['check_table'] = $value['customer_id']!=''?0:'';
                        $this->map['customer_code'] = $value['customer_id']!=''?$value['customer_code']:'';
                        $this->map['customer_name'] = $value['customer_id']!=''?$value['customer_name']:'';
                        $this->map['customer_tax_code'] = $value['customer_id']!=''?$value['customer_tax_code']:'';
                        $this->map['customer_address'] = $value['customer_id']!=''?$value['customer_address']:'';
                        $this->map['customer_bank_code'] = $value['customer_id']!=''?$value['customer_bank_code']:'';
                        
                        $this->map['start_date'] = ($this->map['start_date']=='')?$value['date_use']:( (Date_Time::to_time($this->map['start_date'])<Date_Time::to_time($value['date_use']))?$this->map['start_date']:$value['date_use'] );
                        $this->map['end_date'] = ($this->map['end_date']=='')?$value['date_use']:( (Date_Time::to_time($this->map['end_date'])>Date_Time::to_time($value['date_use']))?$this->map['end_date']:$value['date_use'] );
                        
                        $this->map['add_tax_rate'] = $value['tax_rate'];
                        $this->map['add_service_rate'] = $value['service_rate'];
                    }
                    $vat_bill_detail = DB::fetch_all('
                                                    SELECT
                                                        vat_bill_detail.*
                                                    FROM
                                                        vat_bill_detail
                                                        inner join vat_bill on vat_bill_detail.vat_bill_id=vat_bill.id
                                                    WHERE
                                                        vat_bill_detail.invoice_id in ('.Url::get('invoice_id').')
                                                        and vat_bill_detail.type=\'MICE\' 
                                                        and (vat_bill.status is null or vat_bill.status!=\'CANCEL\')
                    ');
                    foreach($vat_bill_detail as $key=>$value) 
                    {
                        $id = $value['invoice_detail_id'].'_'.$value['invoice_detail_type'];
                        if(isset($mice_invoice[$id])) 
                        {
                            $mice_invoice[$id]['total_amount'] -= $value['total_amount'];
                        }
                        if(isset($mice_invoice[$id]) and $mice_invoice[$id]['total_amount']<=0) 
                        {
                            unset($mice_invoice[$id]);
                        }
                    }
                    foreach($mice_invoice as $key=>$value) 
                    {
                        //echo $value['total_amount']/( (1+$value['service_rate']/100) * (1+$value['tax_rate']/100));
                        $mice_invoice[$key]['total_amount'] = round($mice_invoice[$key]['total_amount']);
                        $mice_invoice[$key]['total_before_tax'] = System::calculate_number(number_format(round($value['total_amount']/( (1+$value['service_rate']/100) * (1+$value['tax_rate']/100) ),2)));
                        $mice_invoice[$key]['service_amount'] = round($mice_invoice[$key]['total_before_tax']*($value['service_rate']/100),2);
                        $mice_invoice[$key]['tax_amount'] = $value['total_amount'] - $mice_invoice[$key]['total_before_tax'] - $mice_invoice[$key]['service_amount'];
                        
                        $this->map['total_before_tax'] += $mice_invoice[$key]['total_before_tax'];
                        $this->map['service_amount'] += $mice_invoice[$key]['service_amount'];
                        $this->map['tax_amount'] += $mice_invoice[$key]['tax_amount'];
                        $this->map['total_amount'] += $value['total_amount'];
                    }
                    //System::debug($mice_invoice);
                    $_REQUEST += $this->map;
                    $this->map['items'] = $mice_invoice;
                }
            }
            else {
                $vat_bill_detail = DB::fetch_all('
                                                    SELECT
                                                        vat_bill_detail.*,
                                                        TO_CHAR(vat_bill_detail.date_use,\'DD/MM/YYYY\') as date_use
                                                    FROM
                                                        vat_bill_detail
                                                    WHERE
                                                        vat_bill_detail.vat_bill_id='.Url::get('id').'
                                                    ');
                $row = DB::fetch('
                                SELECT
                                    vat_bill.*,
                                    TO_CHAR(vat_bill.start_date,\'DD/MM/YYYY\') as start_date,
                                    TO_CHAR(vat_bill.end_date,\'DD/MM/YYYY\') as end_date,
                                    customer.name as customer_code
                                FROM
                                    vat_bill
                                    left join customer on customer.id=vat_bill.customer_id
                                WHERE
                                    vat_bill.id='.Url::get('id').'
                                ');
                $invoice_ids = DB::fetch_all('select * from vat_invoice where vat_bill_id='.Url::get('id').'');
                $this->map['invoice_ids'] = '';
                foreach($invoice_ids as $key=>$value) {
                    if($this->map['invoice_ids']=='')
                        $this->map['invoice_ids'] = $value['invoice_id'];
                    else
                        $this->map['invoice_ids'] .= ','.$value['invoice_id'];
                }
                $this->map = $row;
                $_REQUEST += $this->map;
                $row['description_room']?$_REQUEST['description_room']=$row['description_room']:$_REQUEST['description_room']='Tiền phòng';
                $row['description_bar']?$_REQUEST['description_bar']=$row['description_bar']:$_REQUEST['description_bar']='Dịch vụ ăn uống';
                $row['description_service']?$_REQUEST['description_service']=$row['description_service']:$_REQUEST['description_service']='Dịch vụ khác';
                $row['description_banquet']?$_REQUEST['description_banquet']=$row['description_banquet']:$_REQUEST['description_banquet']='Đặt tiệc, hội nghị';
                $this->map['items'] = $vat_bill_detail;
                $history = DB::fetch_all('select * from vat_history_print where vat_bill_id='.Url::get('id').' order by time_print');
                $count_print = 0;
                $stt=0;
                foreach($history as $key=>$value) {
                    $count_print++;
                    $stt++;
                    $history[$key]['stt'] = $stt;
                    $history[$key]['time_print'] = date('H:i d/m/Y',$value['time_print']);
                    $this->map['last_print'] = $value['user_print'].' '.date('H:i d/m/Y',$value['time_print']);
                }
                $this->map['history'] = $history;
                $this->map['count_print'] = $count_print;
            }
            $_REQUEST['detail'] = $this->map['items'];
       }
       $this->map['print_type_list'] = array('FULL'=>Portal::language('print_full'),'SHORT'=>Portal::language('print_short'));
       $this->map['payment_method_list'] = array('TM'=>'TM','CK/TM'=>'CK/TM','CK'=>'CK');
       //System::debug($this->map);
       $this->parse_layout('edit',$this->map);
	}
}
?>
