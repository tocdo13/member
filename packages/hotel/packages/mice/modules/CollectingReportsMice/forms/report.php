<?php
class CollectingReportsMiceForm extends Form
{
	function CollectingReportsMiceForm()
	{
		Form::Form('CollectingReportsMiceForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_css('packages/hotel/packages/mice/skins/css/font-awesome-4.5.0/css/font-awesome.min.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
	{
	   $this->map = array();
       $cond = '1>0';
       $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
       $_REQUEST['from_date'] = $this->map['from_date'];
       $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
       $_REQUEST['to_date'] = $this->map['to_date'];
       
       $from_date = Date_Time::to_time($this->map['from_date']);
       $to_date = Date_Time::to_time($this->map['to_date'])+86400;
       
       $cond .= ' and mice_invoice.payment_time>='.$from_date.' and mice_invoice.payment_time<'.$to_date;
       
       // lay cac khoan thanh toan trong hoa don
       $sql_payment = '
                        SELECT
                            payment.id,
                            payment.amount,
                            NVL(payment.bank_fee,0) as bank_fee,
                            payment.exchange_rate,
                            payment.payment_type_id as payment_type,
                            mice_invoice.id as mice_invoice_id
                        FROM
                            payment
                            inner join mice_invoice on TO_CHAR(mice_invoice.id)=payment.bill_id and payment.type=\'BILL_MICE\'
                        WHERE
                            '.$cond.'
                        ';
       $payment = DB::fetch_all($sql_payment);
       //System::debug($payment);
       $invoice_payment = array();
       foreach($payment as $key_pay=>$value_pay)
       {
            if(!isset($invoice_payment[$value_pay['mice_invoice_id']]))
            {
                $invoice_payment[$value_pay['mice_invoice_id']]['debit'] = 0;
                $invoice_payment[$value_pay['mice_invoice_id']]['cash'] = 0;
                $invoice_payment[$value_pay['mice_invoice_id']]['credit_card'] = 0;
                $invoice_payment[$value_pay['mice_invoice_id']]['bank'] = 0;
                $invoice_payment[$value_pay['mice_invoice_id']]['foc'] = 0;
                $invoice_payment[$value_pay['mice_invoice_id']]['refund'] = 0;
                
                $invoice_payment[$value_pay['mice_invoice_id']]['total_payment'] = 0;
            }
            if($value_pay['payment_type']=='DEBIT')
                $invoice_payment[$value_pay['mice_invoice_id']]['debit']+= ($value_pay['amount']+$value_pay['bank_fee'])*$value_pay['exchange_rate'];
            elseif($value_pay['payment_type']=='REFUND')
                $invoice_payment[$value_pay['mice_invoice_id']]['refund']+= ($value_pay['amount']+$value_pay['bank_fee'])*$value_pay['exchange_rate'];
            elseif($value_pay['payment_type']=='CASH')
                $invoice_payment[$value_pay['mice_invoice_id']]['cash']+= ($value_pay['amount']+$value_pay['bank_fee'])*$value_pay['exchange_rate'];
            elseif($value_pay['payment_type']=='CREDIT_CARD')
                $invoice_payment[$value_pay['mice_invoice_id']]['credit_card']+= ($value_pay['amount']+$value_pay['bank_fee'])*$value_pay['exchange_rate'];
            elseif($value_pay['payment_type']=='BANK')
                $invoice_payment[$value_pay['mice_invoice_id']]['bank']+= ($value_pay['amount']+$value_pay['bank_fee'])*$value_pay['exchange_rate'];
            elseif($value_pay['payment_type']=='FOC')
                $invoice_payment[$value_pay['mice_invoice_id']]['foc']+= ($value_pay['amount']+$value_pay['bank_fee'])*$value_pay['exchange_rate'];
            
            if($value_pay['payment_type']=='REFUND')
                $invoice_payment[$value_pay['mice_invoice_id']]['total_payment']-= ($value_pay['amount']+$value_pay['bank_fee'])*$value_pay['exchange_rate'];
            else
                $invoice_payment[$value_pay['mice_invoice_id']]['total_payment']+= ($value_pay['amount']+$value_pay['bank_fee'])*$value_pay['exchange_rate'];
                
       }
       //System::debug($invoice_payment);
       
       // boc tach nha hang
       $sql_bar = '
                    SELECT
                        bar_reservation.id,
                        bar.code
                    FROM
                        bar_reservation
                        inner join bar on bar.id=bar_reservation.bar_id
                        inner join mice_invoice_detail on mice_invoice_detail.invoice_id=bar_reservation.id and mice_invoice_detail.type=\'BAR\'
                        inner join mice_invoice on mice_invoice.id=mice_invoice_detail.mice_invoice_id
                    WHERE
                        '.$cond.'
                    ';
       $invoice_bar = DB::fetch_all($sql_bar);
       
       $sql = '
                SELECT
                    mice_invoice_detail.*,
                    mice_invoice.mice_reservation_id,
                    mice_invoice.payment_time,
                    mice_invoice.bill_id,
                    mice_invoice.user_id as user_invoice,
                    payment.user_id as user_payment,
                    mice_invoice.total_amount as total_bill,
                    mice_invoice.extra_amount,
                    mice_invoice.extra_vat,
                    extra_service.code as extra_service_code
                FROM
                    mice_invoice_detail
                    inner join mice_invoice on mice_invoice.id=mice_invoice_detail.mice_invoice_id
                    left join extra_service_invoice_detail on extra_service_invoice_detail.id=mice_invoice_detail.invoice_id and mice_invoice_detail.type=\'EXTRA_SERVICE\'
                    left join extra_service on extra_service.id=extra_service_invoice_detail.service_id
                    left join payment on payment.type=\'BILL_MICE\' AND TO_CHAR(mice_invoice.id)=payment.bill_id
                WHERE
                    '.$cond.'
                ORDER BY
                    mice_invoice.bill_id
                ';
       $items = DB::fetch_all($sql);
       //System::debug($items);
       $this->map['items'] = array();
       $this->map['grand_total'] = array(
                                        'room_charge'=>0,
                                        'service_charge_room'=>0,
                                        'bar'=>0,
                                        'hill_coffee'=>0,
                                        'karaoke'=>0,
                                        'event'=>0,
                                        'ticket'=>0,
                                        'service_other'=>0,
                                        'extra_amount'=>0,
                                        'vat'=>0,
                                        'extra_vat'=>0,
                                        'total_amount'=>0,
                                        'deposit'=>0,
                                        'total_payment'=>0,
                                        'debit'=>0,
                                        'cash'=>0,
                                        'credit_card'=>0,
                                        'bank'=>0,
                                        'foc'=>0,
                                        'refund'=>0,
                                        'debit_refund'=>0,
                                        );
       $stt = 1;
       foreach($items as $key=>$value)
       {
            if(!isset($this->map['items'][$value['mice_invoice_id']]))
            {
                $this->map['items'][$value['mice_invoice_id']]['stt'] = $stt++;
                $this->map['items'][$value['mice_invoice_id']]['in_date'] = date('d/m/Y',$value['payment_time']);
                $this->map['items'][$value['mice_invoice_id']]['id'] = $value['bill_id'];
                $this->map['items'][$value['mice_invoice_id']]['mice_invoice_id'] = $value['mice_invoice_id'];
                $this->map['items'][$value['mice_invoice_id']]['mice_reservation_id'] = $value['mice_reservation_id'];
                $this->map['items'][$value['mice_invoice_id']]['sales'] = $value['user_payment']!=''?$value['user_payment']:$value['user_invoice'];
                $this->map['items'][$value['mice_invoice_id']]['room_charge'] = 0;
                $this->map['items'][$value['mice_invoice_id']]['service_charge_room'] = 0;
                $this->map['items'][$value['mice_invoice_id']]['bar'] = 0; // thuoc nha hang
                $this->map['items'][$value['mice_invoice_id']]['hill_coffee'] = 0; // thuoc nha hang
                $this->map['items'][$value['mice_invoice_id']]['karaoke'] = 0; // thuoc nha hang
                $this->map['items'][$value['mice_invoice_id']]['event'] = 0; // thuoc nha hang
                $this->map['items'][$value['mice_invoice_id']]['ticket'] = 0;
                $this->map['items'][$value['mice_invoice_id']]['service_other'] = 0;
                $this->map['items'][$value['mice_invoice_id']]['extra_amount'] = $value['extra_amount'];
                $this->map['items'][$value['mice_invoice_id']]['vat'] = 0;
                $this->map['items'][$value['mice_invoice_id']]['extra_vat'] = $value['extra_vat'];
                $this->map['items'][$value['mice_invoice_id']]['total_amount'] = $value['total_bill'];
                $this->map['grand_total']['total_amount'] += $value['total_bill'];
                $this->map['grand_total']['extra_amount'] += $value['extra_amount'];
                $this->map['grand_total']['extra_vat'] += $value['extra_vat'];
                $this->map['items'][$value['mice_invoice_id']]['deposit'] = 0;
                $this->map['items'][$value['mice_invoice_id']]['total_payment'] = $value['total_bill'];
                
                $total_remain = $value['total_bill'] - (isset($invoice_payment[$value['mice_invoice_id']])?$invoice_payment[$value['mice_invoice_id']]['total_payment']:0);
                
                $this->map['items'][$value['mice_invoice_id']]['debit']=0;
                $this->map['items'][$value['mice_invoice_id']]['refund']=0;
                $this->map['items'][$value['mice_invoice_id']]['debit_refund']=0;
                if($total_remain>0)
                {
                    $this->map['items'][$value['mice_invoice_id']]['debit'] += $total_remain;
                    $this->map['grand_total']['debit']+=$total_remain;
                }
                else
                {
                    $this->map['items'][$value['mice_invoice_id']]['debit_refund'] += $total_remain*(-1);
                    $this->map['grand_total']['debit_refund']+=$total_remain*(-1);
                }
                
                $this->map['items'][$value['mice_invoice_id']]['debit'] += isset($invoice_payment[$value['mice_invoice_id']])?$invoice_payment[$value['mice_invoice_id']]['debit']:0;
                $this->map['grand_total']['debit']+=isset($invoice_payment[$value['mice_invoice_id']])?$invoice_payment[$value['mice_invoice_id']]['debit']:0;
                
                $this->map['items'][$value['mice_invoice_id']]['cash'] = isset($invoice_payment[$value['mice_invoice_id']])?$invoice_payment[$value['mice_invoice_id']]['cash']:0;
                $this->map['grand_total']['cash']+=isset($invoice_payment[$value['mice_invoice_id']])?$invoice_payment[$value['mice_invoice_id']]['cash']:0;
                
                $this->map['items'][$value['mice_invoice_id']]['credit_card'] = isset($invoice_payment[$value['mice_invoice_id']])?$invoice_payment[$value['mice_invoice_id']]['credit_card']:0;
                $this->map['grand_total']['credit_card']+=isset($invoice_payment[$value['mice_invoice_id']])?$invoice_payment[$value['mice_invoice_id']]['credit_card']:0;
                
                $this->map['items'][$value['mice_invoice_id']]['bank'] = isset($invoice_payment[$value['mice_invoice_id']])?$invoice_payment[$value['mice_invoice_id']]['bank']:0;
                $this->map['grand_total']['bank']+=isset($invoice_payment[$value['mice_invoice_id']])?$invoice_payment[$value['mice_invoice_id']]['bank']:0;
                
                $this->map['items'][$value['mice_invoice_id']]['foc'] = isset($invoice_payment[$value['mice_invoice_id']])?$invoice_payment[$value['mice_invoice_id']]['foc']:0;
                $this->map['grand_total']['foc']+=isset($invoice_payment[$value['mice_invoice_id']])?$invoice_payment[$value['mice_invoice_id']]['foc']:0;
                
                $this->map['items'][$value['mice_invoice_id']]['refund'] += isset($invoice_payment[$value['mice_invoice_id']])?$invoice_payment[$value['mice_invoice_id']]['refund']:0;
                $this->map['grand_total']['refund']+=isset($invoice_payment[$value['mice_invoice_id']])?$invoice_payment[$value['mice_invoice_id']]['refund']:0;
                
            }
            if( $value['type']=='ROOM' OR ( $value['type']=='EXTRA_SERVICE' AND ( $value['extra_service_code']=='EXTRA_BED' OR $value['extra_service_code']=='BABY_COT' OR $value['extra_service_code']=='VFD' OR $value['extra_service_code']=='EARLY_CHECKIN' OR $value['extra_service_code']=='LATE_CHECKOUT' OR $value['extra_service_code']=='LATE_CHECKIN' OR $value['extra_service_code']=='EXTRA_PERSON') ) )
            {
                $this->map['items'][$value['mice_invoice_id']]['room_charge'] += round($value['amount']+$value['service_amount']);
                $this->map['grand_total']['room_charge']+=round($value['amount']+$value['service_amount']);
                $this->map['items'][$value['mice_invoice_id']]['vat'] += $value['tax_amount'];
                $this->map['grand_total']['vat'] +=$value['tax_amount'];
            }
            elseif($value['type']=='DISCOUNT')
            {
                $this->map['items'][$value['mice_invoice_id']]['room_charge'] -= round($value['amount']+$value['service_amount']);
                $this->map['grand_total']['room_charge']-=round($value['amount']+$value['service_amount']);
                $this->map['items'][$value['mice_invoice_id']]['vat'] -= $value['tax_amount'];
                $this->map['grand_total']['vat'] -=$value['tax_amount'];
            }
            elseif($value['type']=='MINIBAR' OR $value['type']=='LAUNDRY' OR $value['type']=='EQUIPMENT')
            {
                $this->map['items'][$value['mice_invoice_id']]['service_charge_room'] += round($value['amount']+$value['service_amount']);
                $this->map['grand_total']['service_charge_room']+=round($value['amount']+$value['service_amount']);
                $this->map['items'][$value['mice_invoice_id']]['vat'] += $value['tax_amount'];
                $this->map['grand_total']['vat'] +=$value['tax_amount'];
            }
            elseif($value['type']=='BAR' AND isset($invoice_bar[$value['invoice_id']]) AND $invoice_bar[$value['invoice_id']]['code']=='NH')
            {
                $this->map['items'][$value['mice_invoice_id']]['bar'] += round($value['amount']+$value['service_amount']);
                $this->map['grand_total']['bar']+=round($value['amount']+$value['service_amount']);
                $this->map['items'][$value['mice_invoice_id']]['vat'] += $value['tax_amount'];
                $this->map['grand_total']['vat'] +=$value['tax_amount'];
            }
            elseif($value['type']=='BAR' AND isset($invoice_bar[$value['invoice_id']]) AND $invoice_bar[$value['invoice_id']]['code']=='KARAOKE')
            {
                $this->map['items'][$value['mice_invoice_id']]['karaoke'] += round($value['amount']+$value['service_amount']);
                $this->map['grand_total']['karaoke']+=round($value['amount']+$value['service_amount']);
                $this->map['items'][$value['mice_invoice_id']]['vat'] += $value['tax_amount'];
                $this->map['grand_total']['vat'] +=$value['tax_amount'];
            }
            elseif($value['type']=='BAR' AND isset($invoice_bar[$value['invoice_id']]) AND $invoice_bar[$value['invoice_id']]['code']=='HILL')
            {
                $this->map['items'][$value['mice_invoice_id']]['hill_coffee'] += round($value['amount']+$value['service_amount']);
                $this->map['grand_total']['hill_coffee']+=round($value['amount']+$value['service_amount']);
                $this->map['items'][$value['mice_invoice_id']]['vat'] += $value['tax_amount'];
                $this->map['grand_total']['vat'] +=$value['tax_amount'];
            }
            elseif($value['type']=='BAR' AND isset($invoice_bar[$value['invoice_id']]) AND $invoice_bar[$value['invoice_id']]['code']=='SUKIEN')
            {
                $this->map['items'][$value['mice_invoice_id']]['event'] += round($value['amount']+$value['service_amount']);
                $this->map['grand_total']['event']+=round($value['amount']+$value['service_amount']);
                $this->map['items'][$value['mice_invoice_id']]['vat'] += $value['tax_amount'];
                $this->map['grand_total']['vat'] +=$value['tax_amount'];
            }
            elseif($value['type']=='TICKET')
            {
                $this->map['items'][$value['mice_invoice_id']]['ticket'] += round($value['amount']+$value['service_amount']);
                $this->map['grand_total']['ticket']+=round($value['amount']+$value['service_amount']);
                $this->map['items'][$value['mice_invoice_id']]['vat'] += $value['tax_amount'];
                $this->map['grand_total']['vat'] +=$value['tax_amount'];
            }
            elseif($value['type']=='EXTRA_SERVICE' AND ( $value['extra_service_code']!='EXTRA_BED' AND $value['extra_service_code']!='BABY_COT' AND $value['extra_service_code']!='VFD' AND $value['extra_service_code']!='EARLY_CHECKIN' AND $value['extra_service_code']!='LATE_CHECKOUT' AND $value['extra_service_code']!='LATE_CHECKIN' AND $value['extra_service_code']!='EXTRA_PERSON'))
            {
                $this->map['items'][$value['mice_invoice_id']]['service_other'] += round($value['amount']+$value['service_amount']);
                $this->map['grand_total']['service_other']+=round($value['amount']+$value['service_amount']);
                $this->map['items'][$value['mice_invoice_id']]['vat'] += $value['tax_amount'];
                $this->map['grand_total']['vat'] +=$value['tax_amount'];
            }
            elseif($value['type']=='DEPOSIT_MICE')
            {
                $this->map['items'][$value['mice_invoice_id']]['deposit'] += round($value['amount']+$value['service_amount']);
                $this->map['grand_total']['deposit']+=round($value['amount']+$value['service_amount']);
                $this->map['items'][$value['mice_invoice_id']]['total_amount'] += round($value['amount']+$value['service_amount']);
                $this->map['grand_total']['total_amount']+=round($value['amount']+$value['service_amount']);
                $this->map['items'][$value['mice_invoice_id']]['vat'] += $value['tax_amount'];
                $this->map['grand_total']['vat'] +=$value['tax_amount'];
            }
       }
       //System::debug($this->map);
       $this->parse_layout('report',$this->map);
	}
}
?>