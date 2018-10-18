<?php
class InvoiceMiceReservationForm extends Form
{
	function InvoiceMiceReservationForm()
    {
		Form::Form('InvoiceMiceReservationForm');
        $this->link_css('packages/hotel/packages/mice/skins/css/font-awesome-4.5.0/css/font-awesome.min.css');
        $this->link_css('packages/hotel/packages/mice/modules/MiceReservation/invoice.css');
	}
    function on_submit()
    {
        //System::debug($_REQUEST); exit();
        if(Url::get('act')=='save' OR Url::get('act')=='payment')
        {
            $log_description = '';
            $log_title = '';
            $log_type = '';
            if(isset($_REQUEST['invoice']))
            {
                $array = array(
                                'mice_reservation_id'=>Url::get('id'),
                                'total_before_tax'=>System::calculate_number(Url::get('total_before_tax')),
                                'service_amount'=>System::calculate_number(Url::get('service_amount')),
                                'tax_amount'=>System::calculate_number(Url::get('tax_amount')),
                                'extra_amount'=>System::calculate_number(Url::get('extra_amount')),
                                'extra_vat'=>System::calculate_number(Url::get('extra_vat')),
                                'total_amount'=>System::calculate_number(Url::get('total_amount')),
                                'reservation_traveller_id'=>Url::get('reservation_traveller_id')
                                );
                if(System::calculate_number(Url::get('total_amount'))==0){
                    if(!isset($_REQUEST['invoice_id'])){
                        $max_bill = DB::fetch("SELECT max(TO_NUMBER(mice_invoice.bill_id)) as bill from mice_invoice","bill");
                        if(!$max_bill)
                            $max_bill = 0;
                        $max_bill++;
                        $array['bill_id'] = $max_bill;
                        $array['payment_time'] = time();
                    }else{
                        $mice_invoice = DB::fetch('SELECT * From mice_invoice Where id='.$_REQUEST['invoice_id']);
                        if($mice_invoice['payment_time']=='')
                        {
                            $max_bill = DB::fetch("SELECT max(TO_NUMBER(mice_invoice.bill_id)) as bill from mice_invoice","bill");
                            if(!$max_bill)
                                $max_bill = 0;
                            
                            $max_bill++;
                            $array['bill_id'] = $max_bill;
                            $array['payment_time'] = time();
                        }
                    }
                }
                if(isset($_REQUEST['invoice_id']))
                {
                    $mice_invoice_id = $_REQUEST['invoice_id'];
                    DB::update('mice_invoice',$array,'id='.$_REQUEST['invoice_id']);
                    $log_type = 'EDIT';
                    $log_title = 'Update Invoice '.$mice_invoice_id.' in MICE '.Url::get('id');
                }
                else
                {
                    $array['create_time'] = time();
                    $array['user_id'] = User::id();
                    $mice_invoice_id = DB::insert('mice_invoice',$array);
                    $log_type = 'ADD';
                    $log_title = 'Make Invoice '.$mice_invoice_id.' in MICE '.Url::get('id');
                }
                $log_description .= '<h3>INVOICE</h3><br/>
                                    <b>'.Portal::language('code_default').': </b> '.$mice_invoice_id.'<br/>
                                    <b>'.Portal::language('id_traveller').': </b> '.Url::get('reservation_traveller_id').'<br/>
                                    <b>'.Portal::language('total_before_tax').': </b> '.Url::get('total_before_tax').'<br/>
                                    <b>'.Portal::language('service_amount').': </b> '.Url::get('service_amount').'<br/>
                                    <b>'.Portal::language('tax_amount').': </b> '.Url::get('tax_amount').'<br/>
                                    <b>'.Portal::language('extra_amount').': </b> '.Url::get('extra_amount').'<br/>
                                    <b>'.Portal::language('extra_vat').': </b> '.Url::get('extra_vat').'<br/>
                                    <b>'.Portal::language('total_amount').': </b> '.Url::get('total_amount').'<br/>
                                    <h3>'.Portal::language('detail').' INVOICE</h3><hr/>
                                    ';
                $invoice_detail_ids = '';
                foreach($_REQUEST['invoice'] as $key=>$value)
                {
                    unset($value['max_amount']);
                    unset($value['max_percent']);
                    $value['amount'] = System::calculate_number($value['amount']);
                    $value['service_amount'] = System::calculate_number($value['service_amount']);
                    $value['tax_amount'] = System::calculate_number($value['tax_amount']);
                    $value['total_amount'] = System::calculate_number($value['total_amount']);
                    $value['date_use'] = Date_Time::to_orc_date($value['date_use']);
                    
                    $value['mice_invoice_id'] = $mice_invoice_id;
                    if($value['id']=='')
                    {
                        unset($value['id']);
                        $id = DB::insert('mice_invoice_detail',$value);
                        $log_description .= '+'.Portal::language('add').' '.Portal::language('invoice').''.$id.':';
                    }
                    else
                    {
                        $id = $value['id'];
                        unset($value['id']);
                        DB::update('mice_invoice_detail',$value,'id='.$id);
                        $log_description .= '+'.Portal::language('update').' '.Portal::language('invoice').''.$id.':';
                    }
                    $log_description .= ''.Portal::language('type').': '.$value['type'].Portal::language('description').': '.$value['description'].Portal::language('invoice').' ID: '.$value['invoice_id'].Portal::language('amount').': '.$value['amount'].'<br/>';
                    if($invoice_detail_ids=='')
                        $invoice_detail_ids = $id;
                    else
                        $invoice_detail_ids .= ','.$id;
                    
                }
                System::log($log_type,$log_title,$log_description,$mice_invoice_id);
                DB::delete('mice_invoice_detail','id not in ('.$invoice_detail_ids.') AND mice_invoice_id='.$mice_invoice_id);
                if(Url::get('act')=='payment')
                {
                    $tt = 'form.php?block_id=428&cmd=payment&id='.$mice_invoice_id.'&mice_id='.Url::get('id').'&type=BILL_MICE&total_amount='.System::calculate_number(Url::get('total_amount')).'&portal_id='.PORTAL_ID.'';
                    echo '<script>window.location.href = \''.$tt.'\'</script>';
                    exit();
                }
            }
            else
            {
                if(isset($_REQUEST['invoice_id']))
                {
                    
                    DB::delete('mice_invoice_detail','mice_invoice_id='.$_REQUEST['invoice_id']);
                    DB::delete('mice_invoice','id='.$_REQUEST['invoice_id']);
                    DB::delete('payment','bill_id=\''.$_REQUEST['invoice_id'].'\' AND type=\'BILL_MICE\'');
                    System::log('DELETE','Delete Bill code default:'.$_REQUEST['invoice_id'],'Delete Bill code default:'.$_REQUEST['invoice_id'],$_REQUEST['invoice_id']);
                }
            }
            
        }
        Url::redirect('mice_reservation',array('cmd'=>'invoice','id'=>Url::get('id')));
    }
	function draw()
    {
        //require_once 'packages/core/includes/utils/currency.php';
		$this->map = array();
        $this->map['portal_id'] = isset($this->map['portal_id'])?$this->map['portal_id']:PORTAL_ID;
        $this->map['reservation_traveller_id_list'] = array(''=>Portal::language('select_traveller'));
        $this->map['reservation_traveller_id'] = '';
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
                                            ');
        $active_department['EXS'] = array('id'=>'EXS','name'=>Portal::language('extra_service'));
        $active_department['DEPOSIT'] = array('id'=>'DEPOSIT','name'=>Portal::language('deposit'));
        $this->map['payment'] = 0;
        /** get detail invoice **/
        if(Url::get('invoice_id'))
        {
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
                                                    mice_invoice.reservation_traveller_id
                                                FROM
                                                    mice_invoice_detail
                                                    inner join mice_invoice on mice_invoice.id=mice_invoice_detail.mice_invoice_id
                                                WHERE
                                                    mice_invoice.id=".Url::get('invoice_id')."
                                            ");
            $payment_invoice = DB::fetch_all("SELECT id,amount,NVL(bank_fee,0) as bank_fee,exchange_rate,payment_type_id FROM payment WHERE bill_id='".Url::get('invoice_id')."' AND type='BILL_MICE'");
            $this->map['payment'] = 0;
            foreach($payment_invoice as $k_pay=>$v_pay)
            {
                if($v_pay['payment_type_id']=='REFUND')
                {
                    $this->map['payment'] -= ($v_pay['amount']+$v_pay['bank_fee'])*$v_pay['exchange_rate'];
                }
                else
                {
                    $this->map['payment'] += ($v_pay['amount']+$v_pay['bank_fee'])*$v_pay['exchange_rate'];
                }
            }
            
            $this->map['invoice_detail'] = array();
            $input_count = 100;
            $input_count_detail = 100;
            //System::debug($invoice_detail);
            foreach($invoice_detail as $id=>$content)
            {
                $this->map['reservation_traveller_id'] = $content['reservation_traveller_id'];
                $this->map['extra_amount'] = $content['extra_amount'];
                $this->map['extra_vat'] = $content['extra_vat'];
                if(!isset($this->map['invoice_detail'][$content['type']]))
                {
                    $input_count++;
                    $this->map['invoice_detail'][$content['type']]['id'] = $content['type'];
                    $this->map['invoice_detail'][$content['type']]['child'] = array();
                    $this->map['invoice_detail'][$content['type']]['input_count'] = $input_count;
                }
                $input_count_detail++;
                $this->map['invoice_detail'][$content['type']]['child'][$input_count_detail]['amount'] = System::display_number($content['amount']);
                $this->map['invoice_detail'][$content['type']]['child'][$input_count_detail]['date_use'] = $content['date_use'];
                $this->map['invoice_detail'][$content['type']]['child'][$input_count_detail]['description'] = $content['description'];
                $this->map['invoice_detail'][$content['type']]['child'][$input_count_detail]['id'] = $content['mice_invoice_detail_id'];
                $this->map['invoice_detail'][$content['type']]['child'][$input_count_detail]['input_count'] = $input_count_detail;
                $this->map['invoice_detail'][$content['type']]['child'][$input_count_detail]['invoice_id'] = $content['invoice_id'];
                $this->map['invoice_detail'][$content['type']]['child'][$input_count_detail]['max_amount'] = System::display_number($content['amount']);
                $this->map['invoice_detail'][$content['type']]['child'][$input_count_detail]['max_percent'] = $content['percent'];
                $this->map['invoice_detail'][$content['type']]['child'][$input_count_detail]['percent'] = $content['percent'];
                $this->map['invoice_detail'][$content['type']]['child'][$input_count_detail]['service_amount'] = System::display_number($content['service_amount']);
                $this->map['invoice_detail'][$content['type']]['child'][$input_count_detail]['service_rate'] = $content['service_rate'];
                $this->map['invoice_detail'][$content['type']]['child'][$input_count_detail]['tax_amount'] = System::display_number($content['tax_amount']);
                $this->map['invoice_detail'][$content['type']]['child'][$input_count_detail]['tax_rate'] = $content['tax_rate'];
                $this->map['invoice_detail'][$content['type']]['child'][$input_count_detail]['total_amount'] = System::display_number($content['total_amount']);
                $this->map['invoice_detail'][$content['type']]['child'][$input_count_detail]['type'] = $content['type'];
                $this->map['invoice_detail'][$content['type']]['child'][$input_count_detail]['key'] = $content['key'];
            }
            $this->map['input_count'] = $input_count;
            $this->map['input_count_detail'] = $input_count_detail;
        }
        /** end detail invoice **/
        
        /** invoice mice other **/
        $invoice_mice_other_arr = DB::fetch_all("
                                            SELECT
                                                mice_invoice_detail.id,
                                                mice_invoice_detail.type,
                                                mice_invoice_detail.invoice_id,
                                                mice_invoice.id as mice_invoice_id,
                                                mice_invoice.payment_time,
                                                mice_invoice.bill_id,
                                                mice_invoice_detail.percent,
                                                mice_invoice_detail.amount,
                                                mice_invoice_detail.total_amount
                                            FROM
                                                mice_invoice_detail
                                                inner join mice_invoice on mice_invoice.id=mice_invoice_detail.mice_invoice_id
                                            WHERE
                                                mice_invoice.mice_reservation_id=".Url::get('id')."
                                            ORDER BY
                                                mice_invoice.bill_id,mice_invoice.id
                                            ");
        $invoice_mice_other = array();
        $this->map['invoice_mice_other'] = array();
        foreach($invoice_mice_other_arr as $k=>$v)
        {
            $this->map['invoice_mice_other'][$v['mice_invoice_id']]['id'] = $v['mice_invoice_id'];
            $this->map['invoice_mice_other'][$v['mice_invoice_id']]['payment_time'] = $v['payment_time'];
            if($v['bill_id']=='')
                $this->map['invoice_mice_other'][$v['mice_invoice_id']]['bill_id'] = '#'.$v['mice_invoice_id'];
            else
                $this->map['invoice_mice_other'][$v['mice_invoice_id']]['bill_id'] = 'BILL-'.$v['bill_id'];
            
            
            if(!isset($invoice_mice_other[$v['type'].'_'.$v['invoice_id']]))
            {
                $invoice_mice_other[$v['type'].'_'.$v['invoice_id']]['id'] = $v['type'].'_'.$v['invoice_id'];
                if($v['bill_id']=='')
                    $invoice_mice_other[$v['type'].'_'.$v['invoice_id']]['mice_invoice_id'] = '#'.$v['mice_invoice_id'];
                else
                    $invoice_mice_other[$v['type'].'_'.$v['invoice_id']]['mice_invoice_id'] = 'BILL-'.$v['bill_id'];
                
                $invoice_mice_other[$v['type'].'_'.$v['invoice_id']]['payment_time'] = $v['payment_time'];
                $invoice_mice_other[$v['type'].'_'.$v['invoice_id']]['bill_id'] = $v['bill_id'];
                $invoice_mice_other[$v['type'].'_'.$v['invoice_id']]['percent'] = $v['percent'];
                $invoice_mice_other[$v['type'].'_'.$v['invoice_id']]['amount'] = $v['amount'];
                $invoice_mice_other[$v['type'].'_'.$v['invoice_id']]['total_amount'] = $v['total_amount'];
            }
            else
            {
                if($v['bill_id']=='')
                    $invoice_mice_other[$v['type'].'_'.$v['invoice_id']]['mice_invoice_id'] .= ', #'.$v['mice_invoice_id'];
                else
                    $invoice_mice_other[$v['type'].'_'.$v['invoice_id']]['mice_invoice_id'] .= ', BILL-'.$v['bill_id'];
                $invoice_mice_other[$v['type'].'_'.$v['invoice_id']]['percent'] += $v['percent'];
                $invoice_mice_other[$v['type'].'_'.$v['invoice_id']]['amount'] += $v['amount'];
                $invoice_mice_other[$v['type'].'_'.$v['invoice_id']]['total_amount'] += $v['total_amount'];
            }
        }
        //System::debug($invoice_mice_other);
        $deposit = 0; $advance_payment = 0;
        $deposit_reservation = 0;
        /** end invoice mice other **/
        foreach($active_department as $key=>$value)
        {
            $active_department[$key]['item'] = array();
            $active_department[$key]['count_item'] = 0;
            $active_department[$key]['sub_amount'] = 0;
            $active_department[$key]['total_amount'] = 0;
            /** reservation  **/
            if($value['id']=='REC')
            {
                if(Url::get('id'))
                {
                    // lay danh sach khach o phong
                    $reservation_traveller = DB::fetch_all("SELECT
                                                                reservation_traveller.id,
                                                                traveller.first_name || ' ' || traveller.last_name as name
                                                            FROM
                                                                reservation_traveller
                                                                inner join reservation_room on reservation_room.id=reservation_traveller.reservation_room_id
                                                                INNER JOIN reservation ON reservation_room.reservation_id=reservation.id
                                                                inner join traveller on traveller.id=reservation_traveller.traveller_id
                                                            WHERE
                                                                reservation.mice_reservation_id=".Url::get('id')." 
                                                                AND reservation_room.status!='CANCEL'
                                                            ");
                    $this->map['reservation_traveller_id_list'] += String::get_list($reservation_traveller);
                    
                    $ReservationRoomList = DB::fetch_all(" SELECT 
                                                                reservation_room.id
                                                            FROM 
                                                                reservation_room 
                                                                INNER JOIN reservation ON reservation_room.reservation_id=reservation.id
                                                            WHERE 
                                                                reservation.mice_reservation_id=".Url::get('id')." 
                                                                AND reservation_room.status!='CANCEL' 
                                                            ");
                    /** lay folio da thanh toan **/
                    $list_folio_other_arr = DB::fetch_all("
                                                        SELECT
                                                            traveller_folio.id,
                                                            traveller_folio.type,
                                                            traveller_folio.invoice_id,
                                                            folio.payment_time,
                                                            folio.id as folio_id,
                                                            folio.customer_id,
                                                            folio.reservation_traveller_id as traveller_id,
                                                            reservation.id as reservation_id,
                                                            traveller_folio.percent,
                                                            traveller_folio.amount,
                                                            traveller_folio.total_amount
                                                        FROM
                                                            traveller_folio
                                                            inner join folio on folio.id=traveller_folio.folio_id
                                                            INNER JOIN reservation ON traveller_folio.reservation_id=reservation.id
                                                        WHERE
                                                            reservation.mice_reservation_id=".Url::get('id')."
                                                        ");
                    $list_folio_other = array();
                    foreach($list_folio_other_arr as $id_f=>$value_f)
                    {
                        if(!isset($list_folio_other[$value_f['type'].'_'.$value_f['invoice_id']]))
                        {
                            $list_folio_other[$value_f['type'].'_'.$value_f['invoice_id']]['id'] = $value_f['type'].'_'.$value_f['invoice_id'];
                            $list_folio_other[$value_f['type'].'_'.$value_f['invoice_id']]['amount'] = $value_f['amount'];
                            $list_folio_other[$value_f['type'].'_'.$value_f['invoice_id']]['total_amount'] = $value_f['total_amount'];
                            $list_folio_other[$value_f['type'].'_'.$value_f['invoice_id']]['percent'] = $value_f['percent'];
                            if($value_f['customer_id']!='')
                                $list_folio_other[$value_f['type'].'_'.$value_f['invoice_id']]['link'] = '<br/> <b><i><a target="_blank" href="?page=view_traveller_folio&cmd=group_invoice&customer_id='.$value_f['customer_id'].'&id='.$value_f['reservation_id'].'&folio_id='.$value_f['folio_id'].'" > Group Folio: '.$value_f['folio_id'].'- Recode: '.$value_f['reservation_id'].'</a></i></b>';
                            else
                                $list_folio_other[$value_f['type'].'_'.$value_f['invoice_id']]['link'] = '<br/> <b><i><a target="_blank" href="?page=view_traveller_folio&traveller_id='.$value_f['traveller_id'].'&folio_id='.$value_f['folio_id'].'" > Folio: '.$value_f['folio_id'].'- Recode: '.$value_f['reservation_id'].'</a></i></b>';
                        }
                        else
                        {
                            $list_folio_other[$value_f['type'].'_'.$value_f['invoice_id']]['amount'] += $value_f['amount'];
                            $list_folio_other[$value_f['type'].'_'.$value_f['invoice_id']]['total_amount'] += $value_f['total_amount'];
                            $list_folio_other[$value_f['type'].'_'.$value_f['invoice_id']]['percent'] += $value_f['percent'];
                            if($value_f['customer_id']!='')
                                $list_folio_other[$value_f['type'].'_'.$value_f['invoice_id']]['link'] .= '<br/> <b><i><a target="_blank" href="?page=view_traveller_folio&cmd=group_invoice&customer_id='.$value_f['customer_id'].'&id='.$value_f['reservation_id'].'&folio_id='.$value_f['folio_id'].'" > Group Folio: '.$value_f['folio_id'].'- Recode: '.$value_f['reservation_id'].'</a></i></b>';
                            else
                                $list_folio_other[$value_f['type'].'_'.$value_f['invoice_id']]['link'] .= '<br/> <b><i><a target="_blank" href="?page=view_traveller_folio&traveller_id='.$value_f['traveller_id'].'&folio_id='.$value_f['folio_id'].'" > Folio: '.$value_f['folio_id'].'- Recode: '.$value_f['reservation_id'].'</a></i></b>';
                        }
                    }
                    /** end folio **/
                    foreach($ReservationRoomList as $K_ResRoom=>$V_ResRoom)
                    {
                        $get_invoice = MiceReservationDB::get_total_room($V_ResRoom['id']);
                        foreach($get_invoice as $F_key=>$F_value)
                        {
                            //System::debug($F_value);exit();
                            $get_invoice[$F_key]['description_'] = '';
                            //$F_value['amount'] = round($F_value['amount'],2);
                            if(isset($list_folio_other[$F_key]))
                            {
                                $F_value['amount'] = $F_value['amount'] - $list_folio_other[$F_key]['amount'];
                                $F_value['total_amount'] = $F_value['total_amount'] - $list_folio_other[$F_key]['total_amount'];
                                $get_invoice[$F_key]['percent'] -= $list_folio_other[$F_key]['percent'];
                                $get_invoice[$F_key]['description_'] .= $list_folio_other[$F_key]['link'];
                            }
                            
                            if(isset($invoice_mice_other[$F_key]))
                            {
                                $F_value['amount'] = $F_value['amount'] - $invoice_mice_other[$F_key]['amount'];
                                $F_value['total_amount'] = $F_value['total_amount'] - $invoice_mice_other[$F_key]['total_amount'];
                                $get_invoice[$F_key]['percent'] -= $invoice_mice_other[$F_key]['percent'];
                                
                                $get_invoice[$F_key]['description_'] .= '<br/> <b><i><a href="#" > Bill Mice '.$invoice_mice_other[$F_key]['mice_invoice_id'].'</a></i></b>';
                            }
                            if(isset($invoice_detail[$F_key]))
                            {
                                $F_value['amount'] = $F_value['amount'] + $invoice_detail[$F_key]['amount'];
                                $F_value['total_amount'] = $F_value['total_amount'] + $invoice_detail[$F_key]['total_amount'];
                                $get_invoice[$F_key]['percent'] += $invoice_detail[$F_key]['percent'];
                            }
                            //$F_value['amount'] = round($F_value['amount'],2);
                            if(round($F_value['amount'],2)==0 || round($F_value['total_amount'],2)==0)
                            {
                                $F_value['amount'] = 0;
                                $F_value['total_amount'] = 0;
                                $get_invoice[$F_key]['status'] = 1;
                            }
                            $get_invoice[$F_key]['amount'] = $F_value['amount'];
                            $get_invoice[$F_key]['net_amount'] = System::display_number($F_value['amount']);
                            $get_invoice[$F_key]['total_amount'] = System::display_number($F_value['total_amount']);
                            if($F_value['type']=='DISCOUNT' or $F_value['type']=='DEPOSIT')
                            {
                                $active_department[$key]['sub_amount'] -= $F_value['amount'];
                                $active_department[$key]['total_amount'] -= $F_value['total_amount'];
                            }
                            else
                            {
                                $active_department[$key]['sub_amount'] += $F_value['amount'];
                                $active_department[$key]['total_amount'] += $F_value['total_amount'];
                            }
                        }
                        $active_department[$key]['item'] += $get_invoice;
                        $active_department[$key]['count_item'] += sizeof($get_invoice);
                    }
                    $deposit_reservation += DB::fetch(" 
                                                            SELECT 
                                                                sum(reservation.deposit) as deposit
                                                            FROM 
                                                                reservation 
                                                            WHERE 
                                                                reservation.mice_reservation_id=".Url::get('id')." 
                                                            ","deposit");
                }
            }
            //System::debug($active_department);exit();
            /** extra service **/
            if($value['id']=='EXS')
            {
                if(Url::get('id'))
                {
                    $ExtraServiceList = DB::fetch_all('
                                    				select 
                                    					extra_service_invoice_detail.*,
                                                        NVL(extra_service_invoice_detail.package_sale_detail_id,0) as package_sale_detail_id,
                                    					((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) as amount,
                                                        ((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) as total_amount,
                                    					0 as service_charge_amount,
                                    					0 as tax_amount,
                                                        extra_service_invoice.net_price,
                                    					DECODE(extra_service_invoice.tax_rate,\'\',0,extra_service_invoice.tax_rate) as tax_rate,
                                    					DECODE(extra_service_invoice.service_rate,\'\',0,extra_service_invoice.service_rate) as service_rate,
                                    					to_char(extra_service_invoice_detail.in_date,\'DD/MM/YYYY\') as in_date,
                                                        extra_service_invoice.id as ex_id,
                                                        extra_service_invoice.bill_number as ex_bill,
                                                        extra_service_invoice.reservation_room_id,
                                                        extra_service_invoice.type,
                                                        extra_service.name
                                    				from 
                                    					extra_service_invoice_detail
                                    					inner join extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
                                                        inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                                    				where
                                                        extra_service_invoice.mice_reservation_id='.Url::get('id').'
                                    			');
                    //System::debug($ExtraServiceList);
                    if(!empty($ExtraServiceList))
                    {
        				foreach($ExtraServiceList as $s_key=>$s_value)
                        {
                            $percent = 100;
                            $status = 0;
                            if($s_value['net_price'] == 1)
                            {
                                $s_value['amount'] = ($s_value['amount']*100/($s_value['tax_rate'] +100))*100/($s_value['service_rate']+100);
                            }   
                            $active_department[$key]['item']['EXTRA_SERVICE_'.$s_key]['description_'] = ''; 
                            $s_value['amount'] = $s_value['amount'];
                            
                            if(isset($list_folio_other) AND isset($list_folio_other['EXTRA_SERVICE_'.$s_key]))
                            {
                                $s_value['amount'] -= $list_folio_other['EXTRA_SERVICE_'.$s_key]['amount'];
                                $s_value['total_amount'] -= $list_folio_other['EXTRA_SERVICE_'.$s_key]['total_amount'];
                                $percent -= $list_folio_other['EXTRA_SERVICE_'.$s_key]['percent'];
                                $active_department[$key]['item']['EXTRA_SERVICE_'.$s_key]['description_'] .= $list_folio_other['EXTRA_SERVICE_'.$s_key]['link'];
                            }
                            
                            if(isset($invoice_mice_other['EXTRA_SERVICE_'.$s_key]))
                            {
                                $s_value['amount'] -= $invoice_mice_other['EXTRA_SERVICE_'.$s_key]['amount'];
                                $s_value['total_amount'] -= $invoice_mice_other['EXTRA_SERVICE_'.$s_key]['total_amount'];
                                
                                $percent -= $invoice_mice_other['EXTRA_SERVICE_'.$s_key]['percent'];
                                
                                $active_department[$key]['item']['EXTRA_SERVICE_'.$s_key]['description_'] .= '<br/> <b><i><a href="#" > Bill Mice '.$invoice_mice_other['EXTRA_SERVICE_'.$s_key]['mice_invoice_id'].'</a></i></b>';
                            }
                            if(isset($invoice_detail['EXTRA_SERVICE_'.$s_key]))
                            {
                                $s_value['amount'] += $invoice_detail['EXTRA_SERVICE_'.$s_key]['amount'];
                                $s_value['total_amount'] += $invoice_detail['EXTRA_SERVICE_'.$s_key]['total_amount'];
                                $percent += $invoice_detail['EXTRA_SERVICE_'.$s_key]['percent'];
                            }
                            if(round($s_value['amount'],0)<=0 || round($s_value['total_amount'],0)<=0)
                            {
                                $s_value['amount'] = 0;
                                $s_value['total_amount'] = 0;
                                $status=1;
                            }
    						$amount = $s_value['amount'];
                            
    						$active_department[$key]['item']['EXTRA_SERVICE_'.$s_key]['net_amount'] = System::display_number($amount);
                            $active_department[$key]['item']['EXTRA_SERVICE_'.$s_key]['real_amount'] = System::display_number($amount);
    						$active_department[$key]['item']['EXTRA_SERVICE_'.$s_key]['id'] = $s_key;
    						$active_department[$key]['item']['EXTRA_SERVICE_'.$s_key]['type'] = 'EXTRA_SERVICE';
    						$active_department[$key]['item']['EXTRA_SERVICE_'.$s_key]['service_rate'] = $s_value['service_rate'];
    						$active_department[$key]['item']['EXTRA_SERVICE_'.$s_key]['tax_rate'] = $s_value['tax_rate'];
    						$active_department[$key]['item']['EXTRA_SERVICE_'.$s_key]['date'] = $s_value['in_date'];
    						$active_department[$key]['item']['EXTRA_SERVICE_'.$s_key]['percent'] = $percent;
    						$active_department[$key]['item']['EXTRA_SERVICE_'.$s_key]['status'] = $status;
                            $active_department[$key]['item']['EXTRA_SERVICE_'.$s_key]['ex_id'] = $s_value['ex_id'];
                            $active_department[$key]['item']['EXTRA_SERVICE_'.$s_key]['ex_bill'] = $s_value['ex_bill'];
                            $active_department[$key]['item']['EXTRA_SERVICE_'.$s_key]['amount'] = ($amount);
                            $active_department[$key]['item']['EXTRA_SERVICE_'.$s_key]['total_amount'] = System::display_number($s_value['total_amount']);
    						$active_department[$key]['item']['EXTRA_SERVICE_'.$s_key]['description'] = $s_value['name'];
                            $active_department[$key]['item']['EXTRA_SERVICE_'.$s_key]['link'] = '?page=extra_service_invoice&cmd=edit&id='.$s_value['ex_id'].'&type='.$s_value['type'];
                            $active_department[$key]['item']['EXTRA_SERVICE_'.$s_key]['bill_number'] = '#'.$s_value['ex_bill'];
                            
                            $active_department[$key]['sub_amount']+= $amount;
                            $active_department[$key]['total_amount']+=$s_value['total_amount'];
                            $active_department[$key]['count_item']++;
                        }
        			}
                }
            }
            //System::debug($active_department);exit();
            /** ticket **/
            if($value['id']=='TICKET')
            {
                if(Url::get('id'))
                {
                    $TicketInvoiceList = DB::fetch_all(" SELECT
                                                                ticket_reservation.*
                                                            FROM 
                                                                ticket_reservation
                                                            WHERE 
                                                                ticket_reservation.mice_reservation_id=".Url::get('id'));
                    $payment_ticket = DB::fetch_all("
                                                SELECT
                                                    payment.bill_id || '_' || payment.type as id,
                                                    SUM(payment.amount) as amount
                                                FROM
                                                    payment
                                                    inner join ticket_reservation on TO_CHAR(ticket_reservation.id)=payment.bill_id AND payment.type='TICKET'
                                                WHERE
                                                    ticket_reservation.mice_reservation_id = ".Url::get('id')."
                                                GROUP BY
                                                    payment.bill_id,payment.type
                                                "); 
                                                            
        			foreach($TicketInvoiceList as $k_ticket=>$value_ticket)
                    {
    					$percent = 100;
                        $status = 0;
                        $active_department[$key]['item']['TICKET_'.$k_ticket]['description_'] = ''; 
                        $total_amount = $value_ticket['total'];
                        if(isset($payment_ticket[$k_ticket.'_TICKET']))
                        {
                            $total_amount = $total_amount - $payment_ticket[$k_ticket.'_TICKET']['amount'];
                        }
                        if(isset($invoice_mice_other['TICKET_'.$k_ticket]))
                        {
                            $total_amount -= $invoice_mice_other['TICKET_'.$k_ticket]['amount'];
                            
                            $percent -= $invoice_mice_other['TICKET_'.$k_ticket]['percent'];
                            
                            $active_department[$key]['item']['TICKET_'.$k_ticket]['description_'] .= '<br/> <b><i><a href="#" > Bill Mice '.$invoice_mice_other['TICKET_'.$k_ticket]['mice_invoice_id'].'</a></i></b>';
                        }
                        if(isset($invoice_detail['TICKET_'.$k_ticket]))
                        {
                            $total_amount += $invoice_detail['TICKET_'.$k_ticket]['amount'];
                            $percent += $invoice_detail['TICKET_'.$k_ticket]['percent'];
                        }
                        if(round($total_amount,0)==0)
                        {
                            $total_amount = 0;
                            $status=1;
                        }
    					$amount = $total_amount;
                        $active_department[$key]['item']['TICKET_'.$k_ticket]['net_amount'] = System::display_number($amount);
                        $active_department[$key]['item']['TICKET_'.$k_ticket]['real_amount'] = System::display_number($amount);
    					$active_department[$key]['item']['TICKET_'.$k_ticket]['id'] = $k_ticket;
    					$active_department[$key]['item']['TICKET_'.$k_ticket]['type'] = 'TICKET';
    					$active_department[$key]['item']['TICKET_'.$k_ticket]['date'] = date('d/m/Y',$value_ticket['time']);
    					$active_department[$key]['item']['TICKET_'.$k_ticket]['service_rate'] = 0;
    					$active_department[$key]['item']['TICKET_'.$k_ticket]['tax_rate'] = 0;
    					$active_department[$key]['item']['TICKET_'.$k_ticket]['percent'] = $percent;
                        $active_department[$key]['item']['TICKET_'.$k_ticket]['code'] = $k_ticket;
    					$active_department[$key]['item']['TICKET_'.$k_ticket]['status'] = $status;
    					$active_department[$key]['item']['TICKET_'.$k_ticket]['amount'] = ($amount);
    					$active_department[$key]['item']['TICKET_'.$k_ticket]['description'] = Portal::language('ticket');
                        $active_department[$key]['item']['TICKET_'.$k_ticket]['link'] = '?page=ticket_invoice_group&cmd=edit&id='.$k_ticket;
                        $active_department[$key]['item']['TICKET_'.$k_ticket]['bill_number'] = '#'.$k_ticket;
                        $active_department[$key]['sub_amount']+= $amount;
                        $active_department[$key]['count_item']++;
    				}
                }
            }
            
            /** restaurant  **/
            if($value['id']=='RES')
            {
                if(Url::get('id'))
                {
                    $sql = '
        				select 
        					bar_reservation.id,
                            bar_reservation.payment_result,
                            bar_reservation.code, 
        					bar_reservation.departure_time as time_out, 
                            bar_reservation.prepaid,
        					bar_reservation.amount_pay_with_room, 
                            bar_reservation.total_before_tax,
                            bar_reservation.total as total_amount,
                            bar_reservation.tax_rate,
                            bar_reservation.bar_fee_rate,
        					(bar.name || \'_\' || bar_reservation.code) AS bar_name ,
        					bar_reservation.deposit as bar_deposit,
                            --bar_reservation.extra_vat,
                            --bar_reservation.extra_amount,
                            bar_table.name as table_name,
                            bar_table.id as table_id,
                            bar.area_id as bar_area_id,
                            bar.id as bar_id
        				from 
        					bar_reservation 
                            inner join bar_reservation_table on bar_reservation_table.bar_reservation_id=bar_reservation.id
        					inner join bar ON bar_reservation.bar_id = bar.id 
                            inner join bar_table on bar_reservation_table.table_id=bar_table.id
        				where 
        					 (bar_reservation.status!=\'CANCEL\')
                             and bar_reservation.mice_reservation_id = '.Url::get('id').'
        			     ';
                    $payment_bar = DB::fetch_all("
                                                SELECT
                                                    payment.bill_id || '_' || payment.type as id,
                                                    SUM(payment.amount) as amount
                                                FROM
                                                    payment
                                                    inner join bar_reservation on TO_CHAR(bar_reservation.id)=payment.bill_id AND payment.type='BAR'
                                                WHERE
                                                    (bar_reservation.status!='CANCEL')
                                                    and bar_reservation.mice_reservation_id = ".Url::get('id')."
                                                GROUP BY
                                                    payment.bill_id,payment.type
                                                ");  
                    //System::debug($payment_bar);              
        			if($bar_resrs = DB::fetch_all($sql))
                    {
                        //System::debug($bar_resrs); 
        				foreach($bar_resrs as $bk=>$reser)
                        {
        					$percent = 100;
                            $status = 0;
                            $active_department[$key]['item']['BAR_'.$bk]['description_'] = ''; 
                            $total_amount = $reser['total_amount'];
                            if(isset($payment_bar[$bk.'_BAR']))
                            {
                                $total_amount = $reser['total_amount'] - $payment_bar[$bk.'_BAR']['total_amount'];
                            }
                            //$total_amount += $reser['extra_vat'];
                            $reser['total_before_tax'] = $total_amount / ( (1+$reser['bar_fee_rate']/100)*(1+$reser['tax_rate']/100));
                            $reser['total_before_tax'] = $reser['total_before_tax'];
                            if(isset($invoice_mice_other['BAR_'.$bk]))
                            {
                                $reser['total_before_tax'] -= $invoice_mice_other['BAR_'.$bk]['amount'];
                                $reser['total_amount'] -= $invoice_mice_other['BAR_'.$bk]['total_amount'];
                                
                                $percent -= $invoice_mice_other['BAR_'.$bk]['percent'];
                                
                                $active_department[$key]['item']['BAR_'.$bk]['description_'] .= '<br/> <b><i><a href="#" > Bill Mice '.$invoice_mice_other['BAR_'.$bk]['mice_invoice_id'].'</a></i></b>';
                            }
                            if(isset($invoice_detail['BAR_'.$bk]))
                            {
                                $reser['total_before_tax'] += $invoice_detail['BAR_'.$bk]['amount'];
                                $reser['total_amount'] += $invoice_detail['BAR_'.$bk]['total_amount'];
                                $percent += $invoice_detail['BAR_'.$bk]['percent'];
                            }
                            if(round($reser['total_before_tax'],0)==0 || round($reser['total_amount'],0)==0)
                            {
                                $reser['total_before_tax'] = 0;
                                $reser['total_amount'] = 0;
                                $status=1;
                            }
        					$amount = $reser['total_before_tax'];
                            
                            $active_department[$key]['item']['BAR_'.$bk]['net_amount'] = System::display_number($amount);
                            $active_department[$key]['item']['BAR_'.$bk]['real_amount'] = System::display_number($bar_resrs[$bk]['total_before_tax']);
        					$active_department[$key]['item']['BAR_'.$bk]['id'] = $bk;
        					$active_department[$key]['item']['BAR_'.$bk]['type'] = 'BAR';
        					$active_department[$key]['item']['BAR_'.$bk]['date'] = date('d/m/Y',$reser['time_out']);
        					$active_department[$key]['item']['BAR_'.$bk]['service_rate'] = $reser['bar_fee_rate'];
        					$active_department[$key]['item']['BAR_'.$bk]['tax_rate'] = $reser['tax_rate'];
        					$active_department[$key]['item']['BAR_'.$bk]['percent'] = $percent;
                            $active_department[$key]['item']['BAR_'.$bk]['code'] = $reser['code'];
        					$active_department[$key]['item']['BAR_'.$bk]['status'] = $status;
        					$active_department[$key]['item']['BAR_'.$bk]['amount'] = ($amount);
                            $active_department[$key]['item']['BAR_'.$bk]['total_amount'] = System::display_number($reser['total_amount']);
        					$active_department[$key]['item']['BAR_'.$bk]['description'] = $reser['table_name'].'-'.$reser['bar_name'];
                            $active_department[$key]['item']['BAR_'.$bk]['link'] = '?page=touch_bar_restaurant&cmd=edit&id='.$bk.'&table_id='.$reser['table_id'].'&bar_area_id='.$reser['bar_area_id'].'&bar_id='.$reser['bar_id'];
                            $active_department[$key]['item']['BAR_'.$bk]['bill_number'] = '#'.$reser['code'];
                            $active_department[$key]['sub_amount']+= $amount;
                            $active_department[$key]['total_amount']+= $reser['total_amount'];
                            $active_department[$key]['count_item']++;
        				}
        			}
                }
            }
            //System::debug($active_department);
            
            /** vending  **/
            if($value['id']=='VENDING')
            {
                if(Url::get('id'))
                {
                    $sql = '
            				select 
            					ve_reservation.*
            				from 
            					ve_reservation
            				where 
            					mice_reservation_id=\''.Url::get('id').'\' 
                            ';
                    $payment_ve = DB::fetch_all("
                                                SELECT
                                                    payment.bill_id || '_' || payment.type as id,
                                                    SUM(payment.amount) as amount
                                                FROM
                                                    payment
                                                    inner join ve_reservation on TO_CHAR(ve_reservation.id)=payment.bill_id AND payment.type='VEND'
                                                WHERE
                                                    ve_reservation.mice_reservation_id = ".Url::get('id')."
                                                GROUP BY
                                                    payment.bill_id,payment.type
                                                ");  
                    if($VendingReservationList = DB::fetch_all($sql))
                    {
        				foreach($VendingReservationList as $bk=>$reser)
                        {
        					$percent = 100;
                            $status = 0;
                            $active_department[$key]['item']['VE_'.$bk]['description_'] = ''; 
                            $total_amount = $reser['total'];
                            if(isset($payment_ve[$bk.'_VEND']))
                            {
                                $total_amount = $reser['total'] - $payment_ve[$bk.'_VEND']['amount'];
                            }
                            $reser['total_before_tax'] = $total_amount / ( (1+$reser['bar_fee_rate']/100)*(1+$reser['tax_rate']/100));
                            $reser['total_before_tax'] = $reser['total_before_tax'];
                            if(isset($invoice_mice_other['VE_'.$bk]))
                            {
                                $reser['total_before_tax'] -= $invoice_mice_other['VE_'.$bk]['amount'];
                                
                                $percent -= $invoice_mice_other['VE_'.$bk]['percent'];
                                
                                $active_department[$key]['item']['VE_'.$bk]['description_'] .= '<br/> <b><i><a href="#" > Bill Mice '.$invoice_mice_other['VE_'.$bk]['mice_invoice_id'].'</a></i></b>';
                            }
                            if(isset($invoice_detail['VE_'.$bk]))
                            {
                                $reser['total_before_tax'] += $invoice_detail['VE_'.$bk]['amount'];
                                $percent += $invoice_detail['VE_'.$bk]['percent'];
                            }
                            if(round($reser['total_before_tax'],0)==0)
                            {
                                $reser['total_before_tax'] = 0;
                                $status=1;
                            }
        					$amount = $reser['total_before_tax'];
        					$active_department[$key]['item']['VE_'.$bk]['net_amount'] = System::display_number($amount);
                            $active_department[$key]['item']['VE_'.$bk]['real_amount'] = System::display_number($VendingReservationList[$bk]['total_before_tax']);
        					$active_department[$key]['item']['VE_'.$bk]['id'] = $bk;
        					$active_department[$key]['item']['VE_'.$bk]['type'] = 'VE';
        					$active_department[$key]['item']['VE_'.$bk]['date'] = date('d/m/Y',$reser['time']);
        					$active_department[$key]['item']['VE_'.$bk]['service_rate'] = $reser['bar_fee_rate'];
        					$active_department[$key]['item']['VE_'.$bk]['tax_rate'] = $reser['tax_rate'];
        					$active_department[$key]['item']['VE_'.$bk]['percent'] = $percent;
        					$active_department[$key]['item']['VE_'.$bk]['status'] = $status;
        					$active_department[$key]['item']['VE_'.$bk]['amount'] = ($amount);
        					$active_department[$key]['item']['VE_'.$bk]['description'] = $reser['department_code'];
                            $active_department[$key]['item']['VE_'.$bk]['link'] = '?page=automatic_vend&cmd=edit&id='.$bk.'&department_id='.$reser['department_id'].'&department_code='.$reser['department_code'].'';
    					    $active_department[$key]['item']['VE_'.$bk]['bill_number'] = '#'.$reser['code'];
                            $active_department[$key]['sub_amount']+= $amount;
                            $active_department[$key]['count_item']++;
        				}
        			}
                }
            }
            
            /** party **/
            if($value['id']=='BANQUET')
            {
                if(Url::get('id'))
                {
                    $PartyReservationList = DB::fetch_all(" SELECT 
                                                                party_reservation.*,
                                                                party_type.name as party_name
                                                            FROM 
                                                                party_reservation 
                                                                left join party_type on party_type.id=party_reservation.party_type  
                                                            WHERE 
                                                                party_reservation.mice_reservation_id=".Url::get('id')." 
                                                                AND party_reservation.status!='CANCEL' 
                                                            ");
                    $payment_banquet = DB::fetch_all("
                                                SELECT
                                                    payment.bill_id || '_' || payment.type as id,
                                                    SUM(payment.amount) as amount
                                                FROM
                                                    payment
                                                    inner join party_reservation on TO_CHAR(party_reservation.id)=payment.bill_id AND payment.type='BANQUET'
                                                WHERE
                                                    party_reservation.mice_reservation_id = ".Url::get('id')."
                                                GROUP BY
                                                    payment.bill_id,payment.type
                                                ");  
                    foreach($PartyReservationList as $K_Party=>$V_Party)
                    {
                        $percent = 100;
                        $status = 0;
                        $active_department[$key]['item']['BANQUET_'.$K_Party]['description_'] = ''; 
                        $total_amount = $V_Party['total'];
                        if(isset($payment_banquet[$K_Party.'_BANQUET']))
                        {
                            $total_amount = $V_Party['total'] - $payment_banquet[$K_Party.'_BANQUET']['amount'];
                            $V_Party['total_before_tax'] = $total_amount / ( (1+$V_Party['extra_service_rate']/100)*(1+$V_Party['vat']/100));
                        }
                        $V_Party['total_before_tax'] = $total_amount / ( (1+$V_Party['extra_service_rate']/100)*(1+$V_Party['vat']/100));
                        $V_Party['total_before_tax'] = $V_Party['total_before_tax'];
                        
                        if(isset($invoice_mice_other['BANQUET_'.$K_Party]))
                        {
                            $V_Party['total_before_tax'] -= $invoice_mice_other['BANQUET_'.$K_Party]['amount'];
                            
                            $percent -= $invoice_mice_other['BANQUET_'.$K_Party]['percent'];
                            
                            $active_department[$key]['item']['BANQUET_'.$K_Party]['description_'] .= '<br/> <b><i><a href="#" > Bill Mice '.$invoice_mice_other['BANQUET_'.$K_Party]['mice_invoice_id'].'</a></i></b>';
                        }
                        if(isset($invoice_detail['BANQUET_'.$K_Party]))
                        {
                            $V_Party['total_before_tax'] += $invoice_detail['BANQUET_'.$K_Party]['amount'];
                            $percent += $invoice_detail['BANQUET_'.$K_Party]['percent'];
                        }
                        if(round($V_Party['total_before_tax'],0)==0)
                        {
                            $V_Party['total_before_tax'] = 0;
                            $status=1;
                        }
    					$amount = $V_Party['total_before_tax'];
    					$active_department[$key]['item']['BANQUET_'.$K_Party]['net_amount'] = System::display_number($amount);
                        $active_department[$key]['item']['BANQUET_'.$K_Party]['real_amount'] = System::display_number($PartyReservationList[$K_Party]['total_before_tax']);
    					$active_department[$key]['item']['BANQUET_'.$K_Party]['id'] = $K_Party;
    					$active_department[$key]['item']['BANQUET_'.$K_Party]['type'] = 'BANQUET';
    					$active_department[$key]['item']['BANQUET_'.$K_Party]['date'] = date('d/m/Y',$V_Party['checkout_time']);
    					$active_department[$key]['item']['BANQUET_'.$K_Party]['service_rate'] = $V_Party['extra_service_rate'];
    					$active_department[$key]['item']['BANQUET_'.$K_Party]['tax_rate'] = $V_Party['vat'];
    					$active_department[$key]['item']['BANQUET_'.$K_Party]['percent'] = $percent;
    					$active_department[$key]['item']['BANQUET_'.$K_Party]['status'] = $status;
    					$active_department[$key]['item']['BANQUET_'.$K_Party]['amount'] = System::calculate_number($amount);
    					$active_department[$key]['item']['BANQUET_'.$K_Party]['description'] = $V_Party['party_name'];
                        $active_department[$key]['item']['BANQUET_'.$K_Party]['link'] = '?page=banquet_reservation&cmd='.$V_Party['party_type'].'&action=edit&id='.$K_Party;
					    $active_department[$key]['item']['BANQUET_'.$K_Party]['bill_number'] = '#Party-'.$K_Party;
                        $active_department[$key]['sub_amount']+= $amount;
                        $active_department[$key]['count_item']++;
                    }
                }
            }
        }
        $deposit = 0;
        $deposit = DB::fetch("SELECT sum(amount) as total FROM payment WHERE bill_id='".Url::get('id')."' AND type='MICE' AND type_dps is not null ","total");
        $deposit += $deposit_reservation;
        if($deposit>0)
        {
            $deposit_real = $deposit;
            $percent = 100;
            $status = 0;                        
            $active_department['DEPOSIT']['item']['DEPOSIT_MICE_'.Url::get('id')]['description_'] = ''; 
            if(isset($invoice_mice_other['DEPOSIT_MICE_'.Url::get('id')]))
            {
                $deposit -= $invoice_mice_other['DEPOSIT_MICE_'.Url::get('id')]['amount'];
                $percent -= $invoice_mice_other['DEPOSIT_MICE_'.Url::get('id')]['percent'];
                $active_department['DEPOSIT']['item']['DEPOSIT_MICE_'.Url::get('id')]['description_'] .= '<br/> <b><i><a href="#" > Bill Mice '.$invoice_mice_other['DEPOSIT_MICE_'.Url::get('id')]['mice_invoice_id'].'</a></i></b>';
            }
            if(isset($invoice_detail['DEPOSIT_MICE_'.Url::get('id')]))
            {
                $deposit = $deposit + $invoice_detail['DEPOSIT_MICE_'.Url::get('id')]['amount'];
                $percent += $invoice_detail['DEPOSIT_MICE_'.Url::get('id')]['percent'];
            } 
            if(round($deposit,0)==0)
            {
                $deposit = 0;
                $status = 1;       
            }                       
            $active_department['DEPOSIT']['item']['DEPOSIT_MICE_'.Url::get('id')]['net_amount'] = System::display_number($deposit);
            $active_department['DEPOSIT']['item']['DEPOSIT_MICE_'.Url::get('id')]['real_amount'] = System::display_number($deposit_real);
			$active_department['DEPOSIT']['item']['DEPOSIT_MICE_'.Url::get('id')]['id'] = Url::get('id');
			$active_department['DEPOSIT']['item']['DEPOSIT_MICE_'.Url::get('id')]['type'] = 'DEPOSIT_MICE';
			$active_department['DEPOSIT']['item']['DEPOSIT_MICE_'.Url::get('id')]['service_rate'] = 0;
			$active_department['DEPOSIT']['item']['DEPOSIT_MICE_'.Url::get('id')]['tax_rate'] = 0;
			$active_department['DEPOSIT']['item']['DEPOSIT_MICE_'.Url::get('id')]['date'] = '';
			$active_department['DEPOSIT']['item']['DEPOSIT_MICE_'.Url::get('id')]['percent'] = $percent;
			$active_department['DEPOSIT']['item']['DEPOSIT_MICE_'.Url::get('id')]['status'] = $status;
            $active_department['DEPOSIT']['item']['DEPOSIT_MICE_'.Url::get('id')]['amount'] = ($deposit);
            $active_department['DEPOSIT']['item']['DEPOSIT_MICE_'.Url::get('id')]['total_amount'] = System::display_number(($deposit));
			$active_department['DEPOSIT']['item']['DEPOSIT_MICE_'.Url::get('id')]['description'] = Portal::language('deposit').' MICE';
            $active_department['DEPOSIT']['item']['DEPOSIT_MICE_'.Url::get('id')]['link'] = '#';
            $active_department['DEPOSIT']['item']['DEPOSIT_MICE_'.Url::get('id')]['bill_number'] = '';
            $active_department['DEPOSIT']['count_item'] = 1;
            $active_department['DEPOSIT']['sub_amount'] = $deposit;
            $active_department['DEPOSIT']['total_amount'] = $deposit;
        }
        //System::debug($active_department);
        
        $this->map['items'] = $active_department;
        //System::debug($this->map);
        $this->parse_layout('invoice',$this->map);
    }
}
?>
