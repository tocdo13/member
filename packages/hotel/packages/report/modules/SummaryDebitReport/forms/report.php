<?php
class SummaryDebitReportForm extends Form
{
	function SummaryDebitReportForm()
	{
		Form::Form('SummaryDebitReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
	}
	
	function draw()
    {
        $this->map = array();
        $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):'01/'.date('m/Y');
        $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
        $_REQUEST['from_date'] = $this->map['from_date'];
        $_REQUEST['to_date'] = $this->map['to_date'];
        
        $this->map['items'] = array();
        $this->map['debit_last_period_before'] = 0;
        $this->map['debit_in_period'] = 0;
        $this->map['review'] = 0;
        $this->map['debit'] = 0;
        // no cuoi ki truoc
        $this->GetDebitLastPeriod($_REQUEST['from_date']);
        // no trong ki
        $this->GetDebitPeriod($_REQUEST['from_date'],$_REQUEST['to_date']);
        // da giam tru trong ki
        $this->GetReview($_REQUEST['from_date'],$_REQUEST['to_date']);
        
        $stt=0;
        $items_sort = array();
        foreach($this->map['items'] as $key=>$value)
        {
            $stt++;
            $this->map['items'][$key]['stt'] = $stt;
            $this->map['items'][$key]['debit_last_period_before'] = System::display_number($value['debit_last_period_before']);
            $this->map['items'][$key]['debit_in_period'] = System::display_number($value['debit_in_period']);
            $this->map['items'][$key]['review'] = System::display_number($value['review']);
            $this->map['items'][$key]['debit'] = System::display_number($value['debit_last_period_before']+$value['debit_in_period']-$value['review']);
            $items_sort[$value['name'].$key] = $this->map['items'][$key];
            $this->map['debit'] += $value['debit_last_period_before']+$value['debit_in_period']-$value['review'];
        }
        ksort($items_sort);
        $this->map['items'] = $items_sort;
        $this->map['debit_last_period_before'] = System::display_number($this->map['debit_last_period_before']);
        $this->map['debit_in_period'] = System::display_number($this->map['debit_in_period']);
        $this->map['review'] = System::display_number($this->map['review']);
        $this->map['debit'] = System::display_number($this->map['debit']);
        $this->parse_layout('report',$this->map);
	}
    
    function GetReview($from_date,$to_date)
    {
        $cond_review = ' customer_review_debt.date_in>=\''.Date_Time::to_orc_date($from_date).'\' and customer_review_debt.date_in<=\''.Date_Time::to_orc_date($to_date).'\'';
        if(Url::get('customer_id')!=0 AND Url::get('customer_id')!='' AND DB::exists('select id from customer where id='.Url::get('customer_id')))
        {
            $cond_review .= ' and customer.id='.Url::get('customer_id');
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
                GROUP BY
                    customer.id,
                    customer.name
                ORDER BY
                    customer.name
                ';
        $review = DB::fetch_all($sql);
        foreach($review as $key=>$value)
        {
            if(!isset($this->map['items'][$value['id']]))
            {
                $this->map['items'][$value['id']]['id'] = $value['id'];
                $this->map['items'][$value['id']]['name'] = $value['name'];
                $this->map['items'][$value['id']]['debit_last_period_before'] = 0;
                $this->map['items'][$value['id']]['debit_in_period'] = 0;
                $this->map['items'][$value['id']]['review'] = 0;
                $this->map['items'][$value['id']]['debit'] = 0;
            }
            $this->map['items'][$value['id']]['review'] += $value['review'];
            $this->map['review'] += $value['review'];
        }
    }
    
    function GetDebitPeriod($from_date,$to_date)
    {
        $cond_payment = ' payment.time>='.Date_Time::to_time($from_date).' AND payment.time<'.(Date_Time::to_time($to_date)+(24*3600));
        if(Url::get('customer_id')!=0 AND Url::get('customer_id')!='' AND DB::exists('select id from customer where id='.Url::get('customer_id')))
        {
            $cond_payment .= ' and customer.id='.Url::get('customer_id');
        }
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
                GROUP BY
                    customer.id,
                    customer.name
                ORDER BY
                    customer.name
                ';
        $payment = DB::fetch_all($sql);
        foreach($payment as $key=>$value)
        {
            if(!isset($this->map['items'][$value['id']]))
            {
                $this->map['items'][$value['id']]['id'] = $value['id'];
                $this->map['items'][$value['id']]['name'] = $value['name'];
                $this->map['items'][$value['id']]['debit_last_period_before'] = 0;
                $this->map['items'][$value['id']]['debit_in_period'] = 0;
                $this->map['items'][$value['id']]['review'] = 0;
                $this->map['items'][$value['id']]['debit'] = 0;
            }
            $this->map['items'][$value['id']]['debit_in_period'] += $value['debit'];
            $this->map['debit_in_period'] += $value['debit'];
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
                GROUP BY
                    customer.id,
                    customer.name
                ORDER BY
                    customer.name
                ';
        $payment = DB::fetch_all($sql);
        foreach($payment as $key=>$value)
        {
            if(!isset($this->map['items'][$value['id']]))
            {
                $this->map['items'][$value['id']]['id'] = $value['id'];
                $this->map['items'][$value['id']]['name'] = $value['name'];
                $this->map['items'][$value['id']]['debit_last_period_before'] = 0;
                $this->map['items'][$value['id']]['debit_in_period'] = 0;
                $this->map['items'][$value['id']]['review'] = 0;
                $this->map['items'][$value['id']]['debit'] = 0;
            }
            $this->map['items'][$value['id']]['debit_in_period'] += $value['debit'];
            $this->map['debit_in_period'] += $value['debit'];
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
                GROUP BY
                    customer.id,
                    customer.name
                ORDER BY
                    customer.name
                ';
        $payment = DB::fetch_all($sql);
        foreach($payment as $key=>$value)
        {
            if(!isset($this->map['items'][$value['id']]))
            {
                $this->map['items'][$value['id']]['id'] = $value['id'];
                $this->map['items'][$value['id']]['name'] = $value['name'];
                $this->map['items'][$value['id']]['debit_last_period_before'] = 0;
                $this->map['items'][$value['id']]['debit_in_period'] = 0;
                $this->map['items'][$value['id']]['review'] = 0;
                $this->map['items'][$value['id']]['debit'] = 0;
            }
            $this->map['items'][$value['id']]['debit_in_period'] += $value['debit'];
            $this->map['debit_in_period'] += $value['debit'];
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
                GROUP BY
                    customer.id,
                    customer.name
                ORDER BY
                    customer.name
                ';
        $payment = DB::fetch_all($sql);
        foreach($payment as $key=>$value)
        {
            if(!isset($this->map['items'][$value['id']]))
            {
                $this->map['items'][$value['id']]['id'] = $value['id'];
                $this->map['items'][$value['id']]['name'] = $value['name'];
                $this->map['items'][$value['id']]['debit_last_period_before'] = 0;
                $this->map['items'][$value['id']]['debit_in_period'] = 0;
                $this->map['items'][$value['id']]['review'] = 0;
                $this->map['items'][$value['id']]['debit'] = 0;
            }
            $this->map['items'][$value['id']]['debit_in_period'] += $value['debit'];
            $this->map['debit_in_period'] += $value['debit'];
        }
    }
    
    function GetDebitLastPeriod($in_date)
    {
        $cond_payment = ' payment.time<'.Date_Time::to_time($in_date);
        $cond_review = ' customer_review_debt.date_in<\''.Date_Time::to_orc_date($in_date).'\'';
        if(Url::get('customer_id')!=0 AND Url::get('customer_id')!='' AND DB::exists('select id from customer where id='.Url::get('customer_id')))
        {
            $cond_payment .= ' and customer.id='.Url::get('customer_id');
            $cond_review .= ' and customer.id='.Url::get('customer_id');
        }
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
                GROUP BY
                    customer.id,
                    customer.name
                ORDER BY
                    customer.name
                ';
        $payment = DB::fetch_all($sql);
        foreach($payment as $key=>$value)
        {
            if(!isset($this->map['items'][$value['id']]))
            {
                $this->map['items'][$value['id']]['id'] = $value['id'];
                $this->map['items'][$value['id']]['name'] = $value['name'];
                $this->map['items'][$value['id']]['debit_last_period_before'] = 0;
                $this->map['items'][$value['id']]['debit_in_period'] = 0;
                $this->map['items'][$value['id']]['review'] = 0;
                $this->map['items'][$value['id']]['debit'] = 0;
            }
            $this->map['items'][$value['id']]['debit_last_period_before'] += $value['debit'];
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
                GROUP BY
                    customer.id,
                    customer.name
                ORDER BY
                    customer.name
                ';
        $payment = DB::fetch_all($sql);
        foreach($payment as $key=>$value)
        {
            if(!isset($this->map['items'][$value['id']]))
            {
                $this->map['items'][$value['id']]['id'] = $value['id'];
                $this->map['items'][$value['id']]['name'] = $value['name'];
                $this->map['items'][$value['id']]['debit_last_period_before'] = 0;
                $this->map['items'][$value['id']]['debit_in_period'] = 0;
                $this->map['items'][$value['id']]['review'] = 0;
                $this->map['items'][$value['id']]['debit'] = 0;
            }
            $this->map['items'][$value['id']]['debit_last_period_before'] += $value['debit'];
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
                GROUP BY
                    customer.id,
                    customer.name
                ORDER BY
                    customer.name
                ';
        $payment = DB::fetch_all($sql);
        foreach($payment as $key=>$value)
        {
            if(!isset($this->map['items'][$value['id']]))
            {
                $this->map['items'][$value['id']]['id'] = $value['id'];
                $this->map['items'][$value['id']]['name'] = $value['name'];
                $this->map['items'][$value['id']]['debit_last_period_before'] = 0;
                $this->map['items'][$value['id']]['debit_in_period'] = 0;
                $this->map['items'][$value['id']]['review'] = 0;
                $this->map['items'][$value['id']]['debit'] = 0;
            }
            $this->map['items'][$value['id']]['debit_last_period_before'] += $value['debit'];
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
                GROUP BY
                    customer.id,
                    customer.name
                ORDER BY
                    customer.name
                ';
        $payment = DB::fetch_all($sql);
        foreach($payment as $key=>$value)
        {
            if(!isset($this->map['items'][$value['id']]))
            {
                $this->map['items'][$value['id']]['id'] = $value['id'];
                $this->map['items'][$value['id']]['name'] = $value['name'];
                $this->map['items'][$value['id']]['debit_last_period_before'] = 0;
                $this->map['items'][$value['id']]['debit_in_period'] = 0;
                $this->map['items'][$value['id']]['review'] = 0;
                $this->map['items'][$value['id']]['debit'] = 0;
            }
            $this->map['items'][$value['id']]['debit_last_period_before'] += $value['debit'];
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
                GROUP BY
                    customer.id,
                    customer.name
                ORDER BY
                    customer.name
                ';
        $review = DB::fetch_all($sql);
        foreach($review as $key=>$value)
        {
            if(!isset($this->map['items'][$value['id']]))
            {
                $this->map['items'][$value['id']]['id'] = $value['id'];
                $this->map['items'][$value['id']]['name'] = $value['name'];
                $this->map['items'][$value['id']]['debit_last_period_before'] = 0;
                $this->map['items'][$value['id']]['debit_in_period'] = 0;
                $this->map['items'][$value['id']]['review'] = 0;
                $this->map['items'][$value['id']]['debit'] = 0;
            }
            $this->map['items'][$value['id']]['debit_last_period_before'] -= $value['review'];
            $this->map['debit_last_period_before'] -= $value['review'];
        }
        
    }
    
}
?>