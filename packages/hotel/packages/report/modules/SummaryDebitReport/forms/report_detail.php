<?php
class SummaryDebitReportDetailForm extends Form
{
	function SummaryDebitReportDetailForm()
	{
		Form::Form('SummaryDebitReportDetailForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
    
    function on_submit() {
        //System::debug($_REQUEST); exit();
        if(Url::get('act')=='DEDUCTIBLE') {
            $customer = DB::fetch('select * from customer where id='.Url::get('customer_id'));
            if(isset($_REQUEST['up'])) {
                foreach($_REQUEST['up'] as $key=>$value) {
                    if(isset($value['id'])) {
                        $record = array(
                                        'customer_id'=>Url::get('customer_id'),
                                        'price'=>System::calculate_number($value['price']),
                                        'date_in'=>Date_time::to_orc_date(date('d/m/Y')),
                                        'user_id'=>User::id(),
                                        'portal_id'=>PORTAL_ID,
                                        'folio_id'=>$value['folio_id'],
                                        'bar_reservation_id'=>$value['bar_reservation_id'],
                                        've_reservation_id'=>$value['ve_reservation_id'],
                                        'mice_invoice_id'=>$value['mice_invoice_id'],
                                        'payment_type_id'=>$value['payment_type_id'],
                                        'description'=>$value['description'],
                                        'invoice_date'=>Date_time::to_orc_date($value['invoice_date'])
                                        );
                        DB::insert('customer_review_debt',$record);
                        $description = '
                                        <b>'.Portal::language('customer_name').': '.$customer['name'].'</b><br/>
                                        <b>'.Portal::language('description').': '.$value['description'].'</b><br/>
                                        <b>'.Portal::language('price').': '.$value['price'].'</b><br/>
                                        <b>'.Portal::language('date_in').': '.date('d/m/Y').'</b><br/>
                                        <b>'.Portal::language('folio').': '.$value['folio_id'].'</b><br/>
                                        <b>'.Portal::language('bar_code').': '.$value['bar_reservation_id'].'</b><br/>
                                        <b>'.Portal::language('ve_code').': '.$value['ve_reservation_id'].'</b><br/>
                                        <b>'.Portal::language('payment_type').': '.$value['payment_type_id'].'</b><br/>
                                        ';
                        System::log('ADD',Portal::language('deductible_for_customer').' '.$customer['name'].' code: '.$customer['code'],$description,'',false);
                    }
                }
            }
            if(isset($_REQUEST['down'])) {
                foreach($_REQUEST['down'] as $key=>$value) {
                    if(isset($value['id'])) {
                        if(isset($value['delete'])){
                            DB::delete('customer_review_debt','id='.$value['id']);
                            $description = '
                                        <b>'.Portal::language('customer_name').': '.$customer['name'].'</b><br/>
                                        <b>'.Portal::language('description').': '.$value['description'].'</b><br/>
                                        <b>'.Portal::language('price').': '.$value['price'].'</b><br/>
                                        <b>'.Portal::language('date_in').': '.date('d/m/Y').'</b><br/>
                                        <b>'.Portal::language('folio').': '.$value['folio_id'].'</b><br/>
                                        <b>'.Portal::language('bar_code').': '.$value['bar_reservation_id'].'</b><br/>
                                        <b>'.Portal::language('ve_code').': '.$value['ve_reservation_id'].'</b><br/>
                                        <b>'.Portal::language('payment_type').': '.$value['payment_type_id'].'</b><br/>
                                        ';
                            System::log('DELETE',Portal::language('deductible_for_customer').' '.$customer['name'].' code: '.$customer['code'],$description,'',false);
                        }else{
                            $record = array(
                                            'price'=>System::calculate_number($value['price']),
                                            'date_in'=>Date_time::to_orc_date(date('d/m/Y')),
                                            'user_id'=>User::id(),
                                            'portal_id'=>PORTAL_ID,
                                            'description'=>$value['description'],
                                            'payment_type_id'=>$value['payment_type_id']
                                            );
                            DB::update('customer_review_debt',$record,'id='.$value['id']);
                            $description = '
                                        <b>'.Portal::language('customer_name').': '.$customer['name'].'</b><br/>
                                        <b>'.Portal::language('description').': '.$value['description'].'</b><br/>
                                        <b>'.Portal::language('price').': '.$value['price'].'</b><br/>
                                        <b>'.Portal::language('date_in').': '.date('d/m/Y').'</b><br/>
                                        <b>'.Portal::language('folio').': '.$value['folio_id'].'</b><br/>
                                        <b>'.Portal::language('bar_code').': '.$value['bar_reservation_id'].'</b><br/>
                                        <b>'.Portal::language('ve_code').': '.$value['ve_reservation_id'].'</b><br/>
                                        <b>'.Portal::language('payment_type').': '.$value['payment_type_id'].'</b><br/>
                                        ';
                            System::log('UPDATE',Portal::language('deductible_for_customer').' '.$customer['name'].' code: '.$customer['code'],$description,'',false);
                        }
                    }
                }
            }
            Url::redirect('summary_debit_report',array('cmd'=>'detail','customer_id'=>Url::get('customer_id'),'from_date'=>Url::get('from_date'),'to_date'=>Url::get('to_date')));
        }
    }
	
	function draw()
    {
        $this->map = array();
        $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):'01/'.date('m/Y');
        $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
        $_REQUEST['from_date'] = $this->map['from_date'];
        $_REQUEST['to_date'] = $this->map['to_date'];
        $this->map['customer_id'] = Url::get('customer_id')?Url::get('customer_id'):'';
        $this->map['customer_name'] = '';
        $this->map['customer_id_list'] = array(''=>Portal::language('all'))+String::get_list(DB::fetch_all('select id,name from customer order by name'));
        $this->map['find_list'] = array(''=>Portal::language('all'),'PST'=>Portal::language('ps_up'),'PSG'=>Portal::language('ps_down'));
        $payment_type_list = DB::fetch_all('select def_code as id,name_'.Portal::language().' as name from payment_type where def_code is not null and apply=\'ALL\' and def_code!=\'FOC\' and def_code!=\'REFUND\' and def_code!=\'DEBIT\'');
        $this->map['payment_type_option'] = '<option value=""></option>';
        foreach($payment_type_list as $key=>$value) {
            $this->map['payment_type_option'] .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';
        }
        
        $this->map['debit_last_period_before'] = 0;
        $this->map['items'] = array();
        if($this->map['customer_id']!='')
        {
            $this->map['customer_name'] = DB::fetch('select name from customer where id='.$this->map['customer_id'].'','name');
            // so du no dau ki
            $this->GetDebitLastPeriod($_REQUEST['from_date'],$this->map['customer_id']);
            
            // chi tiet du no trong ki
            $this->GetDebitPeriod($_REQUEST['from_date'],$_REQUEST['to_date'],$this->map['customer_id']);
            $this->GetReview($_REQUEST['from_date'],$_REQUEST['to_date'],$this->map['customer_id']);
            
        }
        $this->parse_layout('report_detail',$this->map);
    }
    
    function GetReview($from_date,$to_date,$customer_id)
    {
        $cond_review = ' customer_review_debt.date_in>=\''.Date_Time::to_orc_date($from_date).'\' and customer_review_debt.date_in<=\''.Date_Time::to_orc_date($to_date).'\'';
        $sql = '
                SELECT
                    customer_review_debt.id,
                    TO_CHAR(customer_review_debt.date_in,\'DD/MM/YYYY\') as in_date,
                    customer_review_debt.price,
                    folio.reservation_id as recode,
                    folio.reservation_room_id,
                    folio.reservation_traveller_id,
                    folio.total as folio_total,
                    folio.customer_id as folio_customer_id,
                    folio.code as folio_code,
                    customer_review_debt.folio_id as folio_number,
                    customer_review_debt.description,
                    customer_review_debt.bar_reservation_id,
                    customer_review_debt.ve_reservation_id,
                    ve_reservation.department_id,
                    ve_reservation.department_code,
                    ve_reservation.code  as ve_reservation_code,
                    bar_reservation.code  as bar_reservation_code,
                    bar_reservation.bar_id,
                    bar_reservation.package_id,
                    bar.area_id as bar_area_id,
                    customer_review_debt.mice_invoice_id,
                    mice_invoice.bill_id as mice_invoice_code,
                    mice_invoice.mice_reservation_id,
                    customer_review_debt.user_id,
                    customer_review_debt.payment_type_id
                FROM
                    customer_review_debt
                    inner join customer on customer.id=customer_review_debt.customer_id
                    left join folio on customer_review_debt.folio_id = folio.id and customer_review_debt.folio_id is not null
                    left join bar_reservation on customer_review_debt.bar_reservation_id=bar_reservation.id and customer_review_debt.bar_reservation_id is not null
                    left join ve_reservation on customer_review_debt.ve_reservation_id=ve_reservation.id and customer_review_debt.ve_reservation_id is not null
                    left join bar on bar_reservation.bar_id=bar.id
                    left join mice_invoice on customer_review_debt.mice_invoice_id=mice_invoice.id and customer_review_debt.mice_invoice_id is not null
                WHERE
                    '.$cond_review.'
                    and customer_review_debt.portal_id=\''.PORTAL_ID.'\'
                    AND customer.id='.$customer_id.'
                ORDER BY
                    customer_review_debt.date_in
                ';
        $review = DB::fetch_all($sql);
        foreach($review as $key=>$value)
        {
            $in_date = $value['in_date'];
            $time = Date_Time::to_time($in_date);
            if(!isset($this->map['items'][$time.'_'.$value['id'].'_B']))
            {
                $folio = '';
                $link_folio = '';
                if($value['folio_number']!=''){
                    $folio = $value['folio_code']==''?'Ref'.str_pad($value['folio_number'],6,"0",STR_PAD_LEFT):'No.F'.str_pad($value['folio_code'],6,"0",STR_PAD_LEFT);
                    if($value['reservation_room_id']!=''){
                        $link_folio = '?page=view_traveller_folio&cmd=invoice&traveller_id='.$value['reservation_traveller_id'].'&folio_id='.$value['folio_number'];
                    }else{
                        $link_folio = '?page=view_traveller_folio&cmd=group_invoice&customer_id='.$value['folio_customer_id'].'&id='.$value['recode'].'&folio_id='.$value['folio_number'];
                    }
                }
                
                $link_bar = '';
                if($value['bar_reservation_id']!=''){
                    $link_bar = '?page=touch_bar_restaurant&cmd=detail&'.md5('act').'='.md5('print_bill').'&'.md5('preview').'=1&id='.$value['bar_reservation_id'].'&bar_id='.$value['bar_id'].'&bar_area_id='.$value['bar_area_id'].'&package_id='.$value['package_id'];
                }
                $link_vend = '';
                if($value['ve_reservation_id']!=''){
                    $link_vend = '?page=automatic_vend&cmd=detail&'.md5('act').'='.md5('print').'&'.md5('preview').'=1&id='.$value['ve_reservation_id'];
                }
                
                $link_mice = '';
                if($value['mice_invoice_id']!=''){
                    $link_mice = '?page=mice_reservation&cmd=bill_new&invoice_id='.$value['mice_invoice_id'];
                }
                
                $this->map['items'][$time.'_'.$value['id'].'_B']['id'] = $value['id'];
                $this->map['items'][$time.'_'.$value['id'].'_B']['in_date'] = $in_date;
                $this->map['items'][$time.'_'.$value['id'].'_B']['description'] = $value['description'];
                $this->map['items'][$time.'_'.$value['id'].'_B']['reservation_id'] = $value['recode'];
                $this->map['items'][$time.'_'.$value['id'].'_B']['folio_id'] = $value['folio_number'];
                $this->map['items'][$time.'_'.$value['id'].'_B']['folio_number'] = $folio;
                $this->map['items'][$time.'_'.$value['id'].'_B']['bar_reservation_id'] = $value['bar_reservation_id'];
                $this->map['items'][$time.'_'.$value['id'].'_B']['bar_reservation_code'] = $value['bar_reservation_code'];
                $this->map['items'][$time.'_'.$value['id'].'_B']['ve_reservation_id'] = $value['ve_reservation_id'];
                $this->map['items'][$time.'_'.$value['id'].'_B']['ve_reservation_code'] = $value['ve_reservation_code'];
                $this->map['items'][$time.'_'.$value['id'].'_B']['mice_invoice_id'] = $value['mice_invoice_id'];
                $this->map['items'][$time.'_'.$value['id'].'_B']['mice_invoice_code'] = ($value['mice_invoice_code']=='' and $value['mice_invoice_id']!='')?'#'.$value['mice_invoice_id']:($value['mice_invoice_id']!=''?'BILL-'.$value['mice_invoice_code']:'');
                $this->map['items'][$time.'_'.$value['id'].'_B']['mice_reservation_id'] = $value['mice_reservation_id'];
                $this->map['items'][$time.'_'.$value['id'].'_B']['link_folio'] = $link_folio;
                $this->map['items'][$time.'_'.$value['id'].'_B']['link_bar'] = $link_bar;
                $this->map['items'][$time.'_'.$value['id'].'_B']['link_vend'] = $link_vend;
                $this->map['items'][$time.'_'.$value['id'].'_B']['link_mice'] = $link_mice;
                $this->map['items'][$time.'_'.$value['id'].'_B']['up'] = 0;
                $this->map['items'][$time.'_'.$value['id'].'_B']['up_balance'] = 0;
                $this->map['items'][$time.'_'.$value['id'].'_B']['down'] = 0;
                $this->map['items'][$time.'_'.$value['id'].'_B']['edit'] = 1;
                $this->map['items'][$time.'_'.$value['id'].'_B']['user_id'] = $value['user_id'];
                $this->map['items'][$time.'_'.$value['id'].'_B']['payment_type_id'] = $value['payment_type_id'];
            }
            $this->map['items'][$time.'_'.$value['id'].'_B']['down'] += $value['price'];
        }
        //System::debug($this->map['items']);
    }
    
    function GetDebitPeriod($from_date,$to_date,$customer_id)
    {
        $cond_payment = ' payment.time>='.Date_Time::to_time($from_date).' AND payment.time<'.(Date_Time::to_time($to_date)+(24*3600));
        $sql = '
                SELECT
                    payment.id,
                    payment.amount,
                    payment.time,
                    folio.id as folio_id,
                    folio.reservation_id as recode,
                    folio.reservation_room_id,
                    folio.reservation_traveller_id,
                    folio.total as folio_total,
                    folio.customer_id as folio_customer_id,
                    folio.code as folio_code,
                    reservation.id as reservation_id
                FROM
                    payment
                    inner join folio on folio.id=payment.folio_id
                    inner join reservation on reservation.id=payment.reservation_id
                    inner join customer on customer.id=reservation.customer_id
                WHERE
                    '.$cond_payment.'
                    AND payment.payment_type_id=\'DEBIT\' and payment.portal_id=\''.PORTAL_ID.'\'
                    AND payment.type=\'RESERVATION\'
                    AND customer.id='.$customer_id.'
                ORDER BY
                    payment.time,
                    reservation.id,
                    folio.id
                ';
        $payment = DB::fetch_all($sql);
        $folio_ids = 0;
        foreach($payment as $key=>$value)
        {
            $in_date = date('d/m/Y',$value['time']);
            $time = Date_Time::to_time($in_date);
            if(!isset($this->map['items'][$time.'_'.$value['folio_id'].'_A']))
            {
                $folio = $value['folio_code']==''?'Ref'.str_pad($value['folio_id'],6,"0",STR_PAD_LEFT):'No.F'.str_pad($value['folio_code'],6,"0",STR_PAD_LEFT);
                
                if($value['reservation_room_id']!=''){
                    $link_folio = '?page=view_traveller_folio&cmd=invoice&traveller_id='.$value['reservation_traveller_id'].'&folio_id='.$value['folio_id'];
                }else{
                    $link_folio = '?page=view_traveller_folio&cmd=group_invoice&customer_id='.$value['folio_customer_id'].'&id='.$value['recode'].'&folio_id='.$value['folio_id'];
                }
                $this->map['items'][$time.'_'.$value['folio_id'].'_A']['id'] = $time.'_'.$value['folio_id'].'_A';
                $this->map['items'][$time.'_'.$value['folio_id'].'_A']['in_date'] = $in_date;
                $this->map['items'][$time.'_'.$value['folio_id'].'_A']['description'] = Portal::language('room_charge');
                $this->map['items'][$time.'_'.$value['folio_id'].'_A']['reservation_id'] = $value['reservation_id'];
                $this->map['items'][$time.'_'.$value['folio_id'].'_A']['folio_id'] = $value['folio_id'];
                $this->map['items'][$time.'_'.$value['folio_id'].'_A']['folio_number'] = $folio;
                $this->map['items'][$time.'_'.$value['folio_id'].'_A']['bar_reservation_id'] = '';
                $this->map['items'][$time.'_'.$value['folio_id'].'_A']['bar_reservation_code'] = '';
                $this->map['items'][$time.'_'.$value['folio_id'].'_A']['ve_reservation_id'] = '';
                $this->map['items'][$time.'_'.$value['folio_id'].'_A']['ve_reservation_code'] = '';
                $this->map['items'][$time.'_'.$value['folio_id'].'_A']['mice_invoice_id'] = '';
                $this->map['items'][$time.'_'.$value['folio_id'].'_A']['mice_invoice_code'] = '';
                $this->map['items'][$time.'_'.$value['folio_id'].'_A']['mice_reservation_id'] = '';
                $this->map['items'][$time.'_'.$value['folio_id'].'_A']['link_folio'] = $link_folio;
                $this->map['items'][$time.'_'.$value['folio_id'].'_A']['link_bar'] = '';
                $this->map['items'][$time.'_'.$value['folio_id'].'_A']['link_vend'] = '';
                $this->map['items'][$time.'_'.$value['folio_id'].'_A']['link_mice'] = '';
                $this->map['items'][$time.'_'.$value['folio_id'].'_A']['up'] = 0;
                $this->map['items'][$time.'_'.$value['folio_id'].'_A']['up_balance'] = 0;
                $this->map['items'][$time.'_'.$value['folio_id'].'_A']['down'] = 0;
                $this->map['items'][$time.'_'.$value['folio_id'].'_A']['edit'] = 1;
                $this->map['items'][$time.'_'.$value['folio_id'].'_A']['user_id'] = '';
                $this->map['items'][$time.'_'.$value['folio_id'].'_A']['payment_type_id'] = '';
                $folio_ids .= ','.$value['folio_id'];
            }
            $this->map['items'][$time.'_'.$value['folio_id'].'_A']['up'] += $value['amount'];
            $this->map['items'][$time.'_'.$value['folio_id'].'_A']['up_balance'] += $value['amount'];
        }
        $remain = DB::fetch_all('
                                SELECT
                                    sum(price) as amount,
                                    folio_id as id,
                                    TO_CHAR(invoice_date,\'DD/MM/YYYY\') as invoice_date
                                FROM
                                    customer_review_debt
                                WHERE
                                    folio_id in ('.$folio_ids.')
                                GROUP BY
                                    folio_id,invoice_date
                                ');
        foreach($remain as $key=>$value) {
            $time = Date_Time::to_time($value['invoice_date']);
            if(isset($this->map['items'][$time.'_'.$value['id'].'_A'])) {
                $this->map['items'][$time.'_'.$value['id'].'_A']['up_balance'] -= $value['amount'];
                if($this->map['items'][$time.'_'.$value['id'].'_A']['up_balance']<=0) {
                    $this->map['items'][$time.'_'.$value['id'].'_A']['up_balance']=0;
                    $this->map['items'][$time.'_'.$value['id'].'_A']['edit'] = 0;
                }
            }
        }
        
        
        $sql = '
                SELECT
                    payment.id,
                    payment.amount,
                    payment.time,
                    bar_reservation.id as bar_reservation_id,
                    bar_reservation.code as bar_reservation_code,
                    bar_reservation.bar_id,
                    bar_reservation.package_id,
                    bar.area_id as bar_area_id
                FROM
                    payment
                    inner join bar_reservation on bar_reservation.id=payment.bill_id and payment.type=\'BAR\'
                    inner join bar on bar_reservation.bar_id=bar.id
                    inner join customer on customer.id=bar_reservation.customer_id
                WHERE
                    '.$cond_payment.'
                    AND payment.payment_type_id=\'DEBIT\' and payment.portal_id=\''.PORTAL_ID.'\'
                    AND payment.type=\'BAR\'
                    AND customer.id='.$customer_id.'
                ORDER BY
                    payment.time,
                    bar_reservation.id
                ';
        $payment = DB::fetch_all($sql);
        $bar_reservation_ids = 0;
        foreach($payment as $key=>$value)
        {
            $in_date = date('d/m/Y',$value['time']);
            $time = Date_Time::to_time($in_date);
            if(!isset($this->map['items'][$time.'_'.$value['bar_reservation_id'].'_A1']))
            {
                $link_bar = '?page=touch_bar_restaurant&cmd=detail&'.md5('act').'='.md5('print_bill').'&'.md5('preview').'=1&id='.$value['bar_reservation_id'].'&bar_id='.$value['bar_id'].'&bar_area_id='.$value['bar_area_id'].'&package_id='.$value['package_id'];
                
                $this->map['items'][$time.'_'.$value['bar_reservation_id'].'_A1']['id'] = $time.'_'.$value['bar_reservation_id'].'_A1';
                $this->map['items'][$time.'_'.$value['bar_reservation_id'].'_A1']['in_date'] = $in_date;
                $this->map['items'][$time.'_'.$value['bar_reservation_id'].'_A1']['description'] = Portal::language('restaurant');
                $this->map['items'][$time.'_'.$value['bar_reservation_id'].'_A1']['reservation_id'] = '';
                $this->map['items'][$time.'_'.$value['bar_reservation_id'].'_A1']['folio_id'] = '';
                $this->map['items'][$time.'_'.$value['bar_reservation_id'].'_A1']['folio_number'] = '';
                $this->map['items'][$time.'_'.$value['bar_reservation_id'].'_A1']['bar_reservation_id'] = $value['bar_reservation_id'];
                $this->map['items'][$time.'_'.$value['bar_reservation_id'].'_A1']['bar_reservation_code'] = $value['bar_reservation_code'];
                $this->map['items'][$time.'_'.$value['bar_reservation_id'].'_A1']['ve_reservation_id'] = '';
                $this->map['items'][$time.'_'.$value['bar_reservation_id'].'_A1']['ve_reservation_code'] = '';
                $this->map['items'][$time.'_'.$value['bar_reservation_id'].'_A1']['mice_invoice_id'] = '';
                $this->map['items'][$time.'_'.$value['bar_reservation_id'].'_A1']['mice_invoice_code'] = '';
                $this->map['items'][$time.'_'.$value['bar_reservation_id'].'_A1']['mice_reservation_id'] = '';
                $this->map['items'][$time.'_'.$value['bar_reservation_id'].'_A1']['link_folio'] = '';
                $this->map['items'][$time.'_'.$value['bar_reservation_id'].'_A1']['link_bar'] = $link_bar;
                $this->map['items'][$time.'_'.$value['bar_reservation_id'].'_A1']['link_vend'] = '';
                $this->map['items'][$time.'_'.$value['bar_reservation_id'].'_A1']['link_mice'] = '';
                $this->map['items'][$time.'_'.$value['bar_reservation_id'].'_A1']['up'] = 0;
                $this->map['items'][$time.'_'.$value['bar_reservation_id'].'_A1']['up_balance'] = 0;
                $this->map['items'][$time.'_'.$value['bar_reservation_id'].'_A1']['down'] = 0;
                $this->map['items'][$time.'_'.$value['bar_reservation_id'].'_A1']['edit'] = 1;
                $this->map['items'][$time.'_'.$value['bar_reservation_id'].'_A1']['user_id'] = '';
                $this->map['items'][$time.'_'.$value['bar_reservation_id'].'_A1']['payment_type_id'] = '';
                $bar_reservation_ids .= ','.$value['bar_reservation_id'];
            }
            
            $this->map['items'][$time.'_'.$value['bar_reservation_id'].'_A1']['up'] += $value['amount'];
            $this->map['items'][$time.'_'.$value['bar_reservation_id'].'_A1']['up_balance'] += $value['amount'];
        }
        $remain = DB::fetch_all('
                                SELECT
                                    sum(price) as amount,
                                    bar_reservation_id as id,
                                    TO_CHAR(invoice_date,\'DD/MM/YYYY\') as invoice_date
                                FROM
                                    customer_review_debt
                                WHERE
                                    bar_reservation_id in ('.$bar_reservation_ids.')
                                GROUP BY
                                    bar_reservation_id,invoice_date
                                ');
        foreach($remain as $key=>$value) {
            $time = Date_Time::to_time($value['invoice_date']);
            if(isset($this->map['items'][$time.'_'.$value['id'].'_A1'])) {
                $this->map['items'][$time.'_'.$value['id'].'_A1']['up_balance'] -= $value['amount'];
                if($this->map['items'][$time.'_'.$value['id'].'_A1']['up_balance']<=0) {
                    $this->map['items'][$time.'_'.$value['id'].'_A1']['up_balance'] =0;
                    $this->map['items'][$time.'_'.$value['id'].'_A1']['edit'] = 0;
                }
            }
        }
        
        $sql = '
                SELECT
                    payment.id,
                    payment.amount,
                    payment.time,
                    ve_reservation.id as ve_reservation_id,
                    ve_reservation.code as ve_reservation_code,
                    ve_reservation.department_id,
                    ve_reservation.department_code
                FROM
                    payment
                    inner join ve_reservation on ve_reservation.id=payment.bill_id and payment.type=\'VEND\'
                    inner join customer on customer.id=ve_reservation.customer_id
                WHERE
                    '.$cond_payment.'
                    AND payment.payment_type_id=\'DEBIT\' and payment.portal_id=\''.PORTAL_ID.'\'
                    AND payment.type=\'VEND\'
                    AND customer.id='.$customer_id.'
                ORDER BY
                    payment.time,
                    ve_reservation.id
                ';
        $payment = DB::fetch_all($sql);
        $ve_reservation_ids = 0;
        foreach($payment as $key=>$value)
        {
            $in_date = date('d/m/Y',$value['time']);
            $time = Date_Time::to_time($in_date);
            if(!isset($this->map['items'][$time.'_'.$value['ve_reservation_id'].'_A3']))
            {
                $link_vend = '?page=automatic_vend&cmd=detail&'.md5('act').'='.md5('print').'&'.md5('preview').'=1&id='.$value['ve_reservation_id'];
                
                $this->map['items'][$time.'_'.$value['ve_reservation_id'].'_A3']['id'] = $time.'_'.$value['ve_reservation_id'].'_A3';
                $this->map['items'][$time.'_'.$value['ve_reservation_id'].'_A3']['in_date'] = $in_date;
                $this->map['items'][$time.'_'.$value['ve_reservation_id'].'_A3']['description'] = Portal::language('vend');
                $this->map['items'][$time.'_'.$value['ve_reservation_id'].'_A3']['reservation_id'] = '';
                $this->map['items'][$time.'_'.$value['ve_reservation_id'].'_A3']['folio_id'] = '';
                $this->map['items'][$time.'_'.$value['ve_reservation_id'].'_A3']['folio_number'] = '';
                $this->map['items'][$time.'_'.$value['ve_reservation_id'].'_A3']['bar_reservation_id'] = '';
                $this->map['items'][$time.'_'.$value['ve_reservation_id'].'_A3']['bar_reservation_code'] = '';
                $this->map['items'][$time.'_'.$value['ve_reservation_id'].'_A3']['ve_reservation_id'] = $value['ve_reservation_id'];
                $this->map['items'][$time.'_'.$value['ve_reservation_id'].'_A3']['ve_reservation_code'] = $value['ve_reservation_code'];
                $this->map['items'][$time.'_'.$value['ve_reservation_id'].'_A3']['mice_invoice_id'] = '';
                $this->map['items'][$time.'_'.$value['ve_reservation_id'].'_A3']['mice_invoice_code'] = '';
                $this->map['items'][$time.'_'.$value['ve_reservation_id'].'_A3']['mice_reservation_id'] = '';
                $this->map['items'][$time.'_'.$value['ve_reservation_id'].'_A3']['link_folio'] = '';
                $this->map['items'][$time.'_'.$value['ve_reservation_id'].'_A3']['link_bar'] = '';
                $this->map['items'][$time.'_'.$value['ve_reservation_id'].'_A3']['link_vend'] = $link_vend;
                $this->map['items'][$time.'_'.$value['ve_reservation_id'].'_A3']['link_mice'] = '';
                $this->map['items'][$time.'_'.$value['ve_reservation_id'].'_A3']['up'] = 0;
                $this->map['items'][$time.'_'.$value['ve_reservation_id'].'_A3']['up_balance'] = 0;
                $this->map['items'][$time.'_'.$value['ve_reservation_id'].'_A3']['down'] = 0;
                $this->map['items'][$time.'_'.$value['ve_reservation_id'].'_A3']['edit'] = 1;
                $this->map['items'][$time.'_'.$value['ve_reservation_id'].'_A3']['user_id'] = '';
                $this->map['items'][$time.'_'.$value['ve_reservation_id'].'_A3']['payment_type_id'] = '';
                $ve_reservation_ids .= ','.$value['ve_reservation_id'];
            }
            
            $this->map['items'][$time.'_'.$value['ve_reservation_id'].'_A3']['up'] += $value['amount'];
            $this->map['items'][$time.'_'.$value['ve_reservation_id'].'_A3']['up_balance'] += $value['amount'];
        }
        $remain = DB::fetch_all('
                                SELECT
                                    sum(price) as amount,
                                    ve_reservation_id as id,
                                    TO_CHAR(invoice_date,\'DD/MM/YYYY\') as invoice_date
                                FROM
                                    customer_review_debt
                                WHERE
                                    ve_reservation_id in ('.$ve_reservation_ids.')
                                GROUP BY
                                    ve_reservation_id,invoice_date
                                ');
        foreach($remain as $key=>$value) {
            $time = Date_Time::to_time($value['invoice_date']);
            if(isset($this->map['items'][$time.'_'.$value['id'].'_A3'])) {
                $this->map['items'][$time.'_'.$value['id'].'_A3']['up_balance'] -= $value['amount'];
                if($this->map['items'][$time.'_'.$value['id'].'_A3']['up_balance']<=0) {
                    $this->map['items'][$time.'_'.$value['id'].'_A3']['up_balance'] =0;
                    $this->map['items'][$time.'_'.$value['id'].'_A3']['edit'] = 0;
                }
            }
        }
        
        
        $sql = '
                SELECT
                    payment.id,
                    payment.amount,
                    payment.time,
                    mice_invoice.id as mice_invoice_id,
                    mice_invoice.bill_id as mice_invoice_code,
                    mice_reservation.id as mice_reservation_id
                FROM
                    payment
                    inner join mice_invoice on mice_invoice.id=payment.bill_id and payment.type=\'BILL_MICE\'
                    inner join mice_reservation on mice_reservation.id=mice_invoice.mice_reservation_id
                    inner join customer on customer.id=mice_reservation.customer_id
                WHERE
                    '.$cond_payment.'
                    AND payment.payment_type_id=\'DEBIT\' and payment.portal_id=\''.PORTAL_ID.'\'
                    AND payment.type=\'BILL_MICE\'
                    AND customer.id='.$customer_id.'
                ORDER BY
                    payment.time,
                    mice_invoice.id
                ';
        $payment = DB::fetch_all($sql);
        $mice_invoice_ids = 0;
        foreach($payment as $key=>$value)
        {
            $in_date = date('d/m/Y',$value['time']);
            $time = Date_Time::to_time($in_date);
            if(!isset($this->map['items'][$time.'_'.$value['mice_invoice_id'].'_A2']))
            {
                $link_mice = '?page=mice_reservation&cmd=bill_new&invoice_id='.$value['mice_invoice_id'];
                
                $this->map['items'][$time.'_'.$value['mice_invoice_id'].'_A2']['id'] = $time.'_'.$value['mice_invoice_id'].'_A2';
                $this->map['items'][$time.'_'.$value['mice_invoice_id'].'_A2']['in_date'] = $in_date;
                $this->map['items'][$time.'_'.$value['mice_invoice_id'].'_A2']['description'] = Portal::language('mice');
                $this->map['items'][$time.'_'.$value['mice_invoice_id'].'_A2']['reservation_id'] = '';
                $this->map['items'][$time.'_'.$value['mice_invoice_id'].'_A2']['folio_id'] = '';
                $this->map['items'][$time.'_'.$value['mice_invoice_id'].'_A2']['folio_number'] = '';
                $this->map['items'][$time.'_'.$value['mice_invoice_id'].'_A2']['bar_reservation_id'] = '';
                $this->map['items'][$time.'_'.$value['mice_invoice_id'].'_A2']['bar_reservation_code'] = '';
                $this->map['items'][$time.'_'.$value['mice_invoice_id'].'_A2']['ve_reservation_id'] = '';
                $this->map['items'][$time.'_'.$value['mice_invoice_id'].'_A2']['ve_reservation_code'] = '';
                $this->map['items'][$time.'_'.$value['mice_invoice_id'].'_A2']['mice_invoice_id'] = $value['mice_invoice_id'];
                $this->map['items'][$time.'_'.$value['mice_invoice_id'].'_A2']['mice_invoice_code'] = $value['mice_invoice_code'];
                $this->map['items'][$time.'_'.$value['mice_invoice_id'].'_A2']['mice_reservation_id'] = $value['mice_reservation_id'];
                $this->map['items'][$time.'_'.$value['mice_invoice_id'].'_A2']['link_folio'] = '';
                $this->map['items'][$time.'_'.$value['mice_invoice_id'].'_A2']['link_bar'] = '';
                $this->map['items'][$time.'_'.$value['mice_invoice_id'].'_A2']['link_vend'] = '';
                $this->map['items'][$time.'_'.$value['mice_invoice_id'].'_A2']['link_mice'] = $link_mice;
                $this->map['items'][$time.'_'.$value['mice_invoice_id'].'_A2']['up'] = 0;
                $this->map['items'][$time.'_'.$value['mice_invoice_id'].'_A2']['up_balance'] = 0;
                $this->map['items'][$time.'_'.$value['mice_invoice_id'].'_A2']['down'] = 0;
                $this->map['items'][$time.'_'.$value['mice_invoice_id'].'_A2']['edit'] = 1;
                $this->map['items'][$time.'_'.$value['mice_invoice_id'].'_A2']['user_id'] = '';
                $this->map['items'][$time.'_'.$value['mice_invoice_id'].'_A2']['payment_type_id'] = '';
                $mice_invoice_ids .= ','.$value['mice_invoice_id'];
            }
            
            $this->map['items'][$time.'_'.$value['mice_invoice_id'].'_A2']['up'] += $value['amount'];
            $this->map['items'][$time.'_'.$value['mice_invoice_id'].'_A2']['up_balance'] += $value['amount'];
        }
        $remain = DB::fetch_all('
                                SELECT
                                    sum(price) as amount,
                                    mice_invoice_id as id,
                                    TO_CHAR(invoice_date,\'DD/MM/YYYY\') as invoice_date
                                FROM
                                    customer_review_debt
                                WHERE
                                    mice_invoice_id in ('.$mice_invoice_ids.')
                                GROUP BY
                                    mice_invoice_id,invoice_date
                                ');
        foreach($remain as $key=>$value) {
            $time = Date_Time::to_time($value['invoice_date']);
            if(isset($this->map['items'][$time.'_'.$value['id'].'_A2'])) {
                $this->map['items'][$time.'_'.$value['id'].'_A2']['up_balance'] -= $value['amount'];
                if($this->map['items'][$time.'_'.$value['id'].'_A2']['up_balance']<=0) {
                    $this->map['items'][$time.'_'.$value['id'].'_A2']['up_balance']=0;
                    $this->map['items'][$time.'_'.$value['id'].'_A2']['edit'] = 0;
                }
            }
        }
        
    }
    
    function GetDebitLastPeriod($in_date,$customer_id)
    {
        $cond_payment = ' payment.time<'.Date_Time::to_time($in_date);
        $cond_review = ' customer_review_debt.date_in<\''.Date_Time::to_orc_date($in_date).'\'';
        
        $sql = '
                SELECT
                    customer.id,
                    customer.name,
                    sum(payment.amount) as debit
                FROM
                    payment
                    inner join reservation on reservation.id=payment.reservation_id
                    inner join customer on customer.id=reservation.customer_id
                WHERE
                    '.$cond_payment.'
                    AND payment.payment_type_id=\'DEBIT\' and payment.portal_id=\''.PORTAL_ID.'\'
                    AND payment.type=\'RESERVATION\'
                    AND customer.id='.$customer_id.'
                GROUP BY
                    customer.id,
                    customer.name
                ';
        
        $payment = DB::fetch_all($sql);
        //System::debug($payment);
        foreach($payment as $key=>$value)
        {
            $this->map['debit_last_period_before'] += $value['debit'];
        }
        
        $sql = '
                SELECT
                    customer.id,
                    customer.name,
                    sum(payment.amount) as debit
                FROM
                    payment
                    inner join bar_reservation on bar_reservation.id=payment.bill_id and payment.type=\'BAR\'
                    inner join customer on customer.id=bar_reservation.customer_id
                WHERE
                    '.$cond_payment.'
                    AND payment.payment_type_id=\'DEBIT\' and payment.portal_id=\''.PORTAL_ID.'\'
                    AND payment.type=\'BAR\'
                    AND customer.id='.$customer_id.'
                GROUP BY
                    customer.id,
                    customer.name
                ';
        $payment = DB::fetch_all($sql);
        foreach($payment as $key=>$value)
        {
            $this->map['debit_last_period_before'] += $value['debit'];
        }
        
        $sql = '
                SELECT
                    customer.id,
                    customer.name,
                    sum(payment.amount) as debit
                FROM
                    payment
                    inner join ve_reservation on ve_reservation.id=payment.bill_id and payment.type=\'VEND\'
                    inner join customer on customer.id=ve_reservation.customer_id
                WHERE
                    '.$cond_payment.'
                    AND payment.payment_type_id=\'DEBIT\' and payment.portal_id=\''.PORTAL_ID.'\'
                    AND payment.type=\'VEND\'
                    AND customer.id='.$customer_id.'
                GROUP BY
                    customer.id,
                    customer.name
                ';
        $payment = DB::fetch_all($sql);
        foreach($payment as $key=>$value)
        {
            $this->map['debit_last_period_before'] += $value['debit'];
        }
        
        $sql = '
                SELECT
                    customer.id,
                    customer.name,
                    sum(payment.amount) as debit
                FROM
                    payment
                    inner join mice_invoice on mice_invoice.id=payment.bill_id and payment.type=\'BILL_MICE\'
                    inner join mice_reservation on mice_reservation.id=mice_invoice.mice_reservation_id
                    inner join customer on customer.id=mice_reservation.customer_id
                WHERE
                    '.$cond_payment.'
                    AND payment.payment_type_id=\'DEBIT\' and payment.portal_id=\''.PORTAL_ID.'\'
                    AND payment.type=\'BILL_MICE\'
                    AND customer.id='.$customer_id.'
                GROUP BY
                    customer.id,
                    customer.name
                ';
        $payment = DB::fetch_all($sql);
        foreach($payment as $key=>$value)
        {
            $this->map['debit_last_period_before'] += $value['debit'];
        }
        
        $sql = '
                SELECT
                    customer.id,
                    customer.name,
                    sum(customer_review_debt.price) as review
                FROM
                    customer_review_debt
                    inner join customer on customer.id=customer_review_debt.customer_id
                WHERE
                    '.$cond_review.'
                    and customer_review_debt.portal_id=\''.PORTAL_ID.'\'
                    AND customer.id='.$customer_id.'
                GROUP BY
                    customer.id,
                    customer.name
                ';
        $review = DB::fetch_all($sql);
        foreach($review as $key=>$value)
        {
            $this->map['debit_last_period_before'] -= $value['review'];
        }
        
    }
}
?>