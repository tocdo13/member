<?php
class WeeklyViewFolioForm extends Form{
	function WeeklyViewFolioForm(){
		Form::Form('WeeklyViewFolioForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
	}
	function draw() {
        $cond = ' 1 = 1  and folio.portal_id = \''.PORTAL_ID.'\'';
        $payment_cond = $cond;
        if(Url::get('recode_id')){
            $cond.=' and folio.reservation_id ='.Url::get('recode_id').'';
        }
        if(Url::get('customer_name')) {
            $cond.= ' and customer.name = \''.Url::get('customer_name').'\'';
        }
        if(Url::get('folio_id')) {
            trim(Url::get('folio_id'));
            $str3 = substr(Url::get('folio_id'),0,3);
            $str4 = substr(Url::get('folio_id'),0,4);
            if($str4=='No.F')
            {
                $folio_id = (int) substr(Url::get('folio_id'),4);
                
                $cond.= ' and folio.code = '.$folio_id;
                $payment_cond.' and folio.code = '.$folio_id;
            }
            elseif($str3=='Ref')
            {
                $folio_id = (int) substr(Url::get('folio_id'),3);
                
                $cond.= ' and folio.id = '.$folio_id;
                $payment_cond.' and folio.id = '.$folio_id;
            }
        }
        if(Url::get('from_date') and Url::get('to_date')) {
            $cond .= 'and ( (folio.total=0 and folio.create_time>='.Date_Time::to_time(Url::get('from_date')).' and folio.create_time<='.(Date_Time::to_time(Url::get('to_date'))+86399).') OR (folio.total!=0 and folio.payment_time>='.Date_Time::to_time(Url::get('from_date')).' and folio.payment_time<='.(Date_Time::to_time(Url::get('to_date'))+86399).') )';
            $payment_cond .= 'and ( (folio.total=0 and folio.create_time>='.Date_Time::to_time(Url::get('from_date')).' and folio.create_time<='.(Date_Time::to_time(Url::get('to_date'))+86399).') OR (folio.total!=0 and folio.payment_time>='.Date_Time::to_time(Url::get('from_date')).' and folio.payment_time<='.(Date_Time::to_time(Url::get('to_date'))+86399).') )';
        }
        $this->map = array();
        
        $this->map['order_by_list'] = array(
            'ASC' => Portal::language('tang_dan'),
            'DESC' => Portal::language('giam_dan')
        );
        $this->map['status_list'] = array(
            'ALL' => Portal::language('all'),
            'CO' => Portal::language('checkout'),
            'NOT_CO' => Portal::language('not_checkout')
        );
        $order_by='folio.reservation_id ASC,';
        if(Url::get('order_by'))
        {
            $order_by = 'folio.reservation_id '.Url::get('order_by').',';
        }
        $sql_folio_foc = '
            select id from folio
            where id in (
                    select folio.id as id
                    from payment
                    inner join folio on folio.id = payment.folio_id 
                    where 
                    '.$payment_cond.'
                    and payment.payment_type_id = \'FOC\'
                    )
        ';
        $folio_foc = DB::fetch_all($sql_folio_foc);
        /** L?c nh?ng hóa don c?a booking dã checkout h?t t?t c? các phòng **/
        $cond_booking = '';
        $not_in = '';
        $in = '';
        $_REQUEST['status']=(Url::get('status'))?Url::get('status'):'CO';
        if(Url::get('status'))
        {
            if(Url::get('status')!='ALL')
            {
                $sql_rr = '
                    select reservation_room.*
                    from reservation_room
                    inner join reservation on reservation_room.reservation_id = reservation.id
                    where reservation.id in (
                            select folio.reservation_id as id
                            from folio
                            where 
                            '.$payment_cond.'
                            )
                    and reservation_room.status != \'CANCEL\'
                ';
                $rr_array = DB::fetch_all($sql_rr);
                $reservation_co = array();
                foreach($rr_array as $k=>$v)
                {
                    $reservation_co[$v['reservation_id']][$k]['id'] = $v['id'];
                    $reservation_co[$v['reservation_id']][$k]['status'] = $v['status'];
                }
                $id_co='(0';
                foreach($reservation_co as $key=>$value)
                {
                    $check = true;
                    foreach($value as $k=>$v)
                    {
                        if($v['status']!='CHECKOUT')
                        {
                            $check = false;
                        }
                    }
                    if($check==true)
                    {
                        $id_co.=','.$key;
                        $not_in .= $not_in==''?'reservation.id!='.$key:' and reservation.id!='.$key;
                        $in .= $in==''?'reservation.id='.$key:' or reservation.id='.$key;
                    }
                }
                $id_co.=')';
                if(Url::get('status')!='CO')
                {
                    if($not_in!='')
                        $cond_booking.= ' and ('.$not_in.')';
                }
                else
                {
                    if($in!='')
                        $cond_booking.= ' and ('.$in.')';
                } 
            }
        }
        /** end L?c nh?ng hóa don c?a booking dã checkout h?t t?t c? các phòng **/
        $traveller_folio = DB::fetch_all('
                                        SELECT
                                            traveller_folio.folio_id || \'_\' || traveller_folio.invoice_id || \'_\' || traveller_folio.type as id,
                                            traveller_folio.total_amount,
                                            traveller_folio.type,
                                            traveller_folio.invoice_id,
                                            folio.id as folio_id,
                                            folio.reservation_id,
                                            folio.total,
                                            folio.code,
                                            folio.reservation_room_id,
                                            folio.reservation_traveller_id,
                                            folio.customer_id,
                                            room.name as room_name,
                                            customer.name as customer_name,
                                            traveller.first_name || \' \' || traveller.last_name as traveller_name,
                                            CASE
                                                WHEN folio.total=0
                                                THEN folio.create_time
                                                ELSE folio.payment_time
                                            END time
                                        FROM
                                            traveller_folio
                                            inner join folio on folio.id=traveller_folio.folio_id
                                            inner join reservation on reservation.id=folio.reservation_id
                                            left join customer on customer.id=folio.customer_id
                                            left join reservation_room on folio.reservation_room_id=reservation_room.id
                                            left join room on reservation_room.room_id=room.id
                                            left join reservation_traveller on reservation_traveller.id=folio.reservation_traveller_id
                                            left join traveller on traveller.id=reservation_traveller.traveller_id
                                        WHERE
                                            '.$cond.$cond_booking.'
                                            and traveller_folio.type!=\'DISCOUNT\' and traveller_folio.type!=\'DEPOSIT\' and traveller_folio.type!=\'DEPOSIT_GROUP\'
                                            and (folio.payment_time is not null or folio.total=0)
                                        ORDER by
                                            '.$order_by.' folio.create_time  DESC
                                        ');
        $folio = DB::fetch_all('
                                        SELECT
                                            folio.id,
                                            sum(traveller_folio.total_amount) as total_amount
                                        FROM
                                            traveller_folio
                                            inner join folio on folio.id=traveller_folio.folio_id
                                            inner join reservation on reservation.id=folio.reservation_id
                                            left join customer on customer.id=folio.customer_id
                                            left join reservation_room on folio.reservation_room_id=reservation_room.id
                                            left join room on reservation_room.room_id=room.id
                                            left join reservation_traveller on reservation_traveller.id=folio.reservation_traveller_id
                                            left join traveller on traveller.id=reservation_traveller.traveller_id
                                        WHERE
                                            '.$cond.$cond_booking.'
                                            and traveller_folio.type!=\'DISCOUNT\' and traveller_folio.type!=\'DEPOSIT\' and traveller_folio.type!=\'DEPOSIT_GROUP\'
                                            and (folio.payment_time is not null or folio.total=0)
                                        group by
                                            folio.id
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
        $this->map['items'] = array();
        $stt = 0;
        //System::debug($vat_bill);
        $this->map['total']=0;
        $this->map['total_remain']=0;
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
            if(isset($folio_foc[$value['folio_id']]))
            {
                $check=false;
                unset($traveller_folio[$key]);
            }
            if($check and !isset($this->map['items'][$value['folio_id']])) {
                $stt++;
                $href = '?page=view_traveller_folio&cmd=group_invoice&folio_id='.$value['folio_id'];
                if($value['reservation_room_id']!='') {
                    $href .= '&traveller_id='.$value['reservation_traveller_id'];
                } else {
                    $href .= '&customer_id='.$value['customer_id'];
                }
                $this->map['items'][$value['folio_id']]['stt'] = $stt;
                $this->map['items'][$value['folio_id']]['id'] = $value['folio_id'];
                $this->map['items'][$value['folio_id']]['code'] = $value['total']==0?'Ref'.str_pad($value['folio_id'],6,"0",STR_PAD_LEFT):'No.F'.str_pad($value['code'],6,"0",STR_PAD_LEFT);
                $this->map['items'][$value['folio_id']]['href'] = $href;
                $this->map['items'][$value['folio_id']]['recode'] = $value['reservation_id'];
                $this->map['items'][$value['folio_id']]['room_name'] = $value['room_name'];
                $this->map['items'][$value['folio_id']]['customer_name'] = $value['customer_name'];
                $this->map['items'][$value['folio_id']]['traveller_name'] = $value['traveller_name'];
                $this->map['items'][$value['folio_id']]['time'] = date('H:i d/m/Y',$value['time']);
                $this->map['items'][$value['folio_id']]['total'] = System::display_number($value['total_amount']);
                $this->map['total'] += $value['total_amount'];
                $this->map['items'][$value['folio_id']]['total_remain'] = System::display_number($value['total_amount']-$total_print_vat);
                $this->map['total_remain'] += $value['total_amount']-$total_print_vat;
            } 
            elseif($check and isset($this->map['items'][$value['folio_id']])) {
                $this->map['items'][$value['folio_id']]['total'] = System::display_number(System::calculate_number($this->map['items'][$value['folio_id']]['total'])+$value['total_amount']);
                $this->map['items'][$value['folio_id']]['total_remain'] = System::display_number(System::calculate_number($this->map['items'][$value['folio_id']]['total_remain'])+($value['total_amount']-$total_print_vat));
                $this->map['total_remain'] += System::calculate_number($value['total_amount']-$total_print_vat);
                $this->map['total'] += $value['total_amount'];
            }
        }
        $this->map['total'] = System::display_number($this->map['total']);
        $this->map['total_remain'] = System::display_number($this->map['total_remain']);
		$this->parse_layout('list',$this->map);		
	}
}
?>