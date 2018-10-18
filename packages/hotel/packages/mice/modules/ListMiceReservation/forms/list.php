<?php
class ListMiceReservationForm extends Form
{
    function ListMiceReservationForm()
    {
        Form::Form('ListMiceReservationForm');	
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
    }
    function draw()
    {
        $cond = ' 1 = 1  and mice_reservation.portal_id = \''.PORTAL_ID.'\'';
        //System::debug($_REQUEST);
        if(Url::get('customer_id'))
        {
            $cond.= ' and customer.id = \''.Url::get('customer_id').'\'';
        }
        if(Url::get('from_date') and Url::get('to_date')) 
        {
            $cond .= ' and ( (mice_invoice.total_amount=0 and mice_invoice.create_time>='.Date_Time::to_time(Url::get('from_date')).' and mice_invoice.create_time<='.(Date_Time::to_time(Url::get('to_date'))+86399).') OR (mice_invoice.total_amount!=0 and mice_invoice.payment_time>='.Date_Time::to_time(Url::get('from_date')).' and mice_invoice.payment_time<='.(Date_Time::to_time(Url::get('to_date'))+86399).') )';
        }
        if(Url::get('mice_invoice_id'))
        {
            $cond .= ' AND mice_invoice.id like \'%'.Url::get('mice_invoice_id').'%\'';
        }
        $mice_invoice = DB::fetch_all('
                                SELECT
                                    mice_invoice_detail.mice_invoice_id || \'_\' || mice_invoice_detail.invoice_id || \'_\' || mice_invoice_detail.type as id,
                                    mice_invoice_detail.total_amount,
                                    mice_invoice_detail.type,
                                    mice_invoice_detail.invoice_id,
                                    mice_invoice.id as mice_invoice_id,
                                    mice_invoice.bill_id,
                                    mice_invoice.mice_reservation_id,
                                    mice_invoice.total_amount as total,
                                    mice_invoice.user_id,
                                    CASE
                                        WHEN mice_invoice.bill_id is null
                                        THEN mice_invoice.create_time
                                        ELSE mice_invoice.payment_time
                                    END time,
                                    mice_reservation.contact_name,
                                    customer.name as customer_name
                                FROM
                                    mice_invoice_detail
                                    INNER JOIN mice_invoice ON mice_invoice.id = mice_invoice_detail.mice_invoice_id
                                    INNER JOIN mice_reservation ON mice_reservation.id = mice_invoice.mice_reservation_id
                                    INNER JOIN customer ON customer.id = mice_reservation.customer_id
                                WHERE
                                    '.$cond.'
                                    AND mice_invoice_detail.type !=\'DISCOUNT\' 
                                    AND mice_invoice_detail.type !=\'DEPOSIT\' 
                                    AND mice_invoice_detail.type !=\'DEPOSIT_MICE\'
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
            
            if($check and !isset($this->map['items'][$value['mice_invoice_id']])) 
            {
                $stt++;
                $href = '?page=mice_reservation&cmd=bill_new&invoice_id='.$value['mice_invoice_id'];
                $this->map['items'][$value['mice_invoice_id']]['stt'] = $stt;
                $this->map['items'][$value['mice_invoice_id']]['id'] = $value['mice_invoice_id'];
                $this->map['items'][$value['mice_invoice_id']]['mice_reservation_id'] = $value['mice_reservation_id'];
                $this->map['items'][$value['mice_invoice_id']]['code'] = ($value['bill_id'] !='')?'BILL - '.$value['bill_id']:'MICE +'.$value['mice_reservation_id'];
                $this->map['items'][$value['mice_invoice_id']]['href'] = $href;
                $this->map['items'][$value['mice_invoice_id']]['customer_name'] = $value['customer_name'];
                $this->map['items'][$value['mice_invoice_id']]['contact_name'] = $value['contact_name'];
                $this->map['items'][$value['mice_invoice_id']]['time'] = date('H:i d/m/Y',$value['time']);
                $this->map['items'][$value['mice_invoice_id']]['total'] = System::display_number($value['total_amount']);
                $this->map['items'][$value['mice_invoice_id']]['user_id'] =$value['user_id'];
                $this->map['total'] += $value['total_amount'];
                $this->map['items'][$value['mice_invoice_id']]['total_remain'] = System::display_number($value['total_amount']-$total_print_vat);
                $this->map['total_remain'] += $value['total_amount']-$total_print_vat;
            }elseif($check and isset($this->map['items'][$value['mice_invoice_id']])) 
            {
                $this->map['items'][$value['mice_invoice_id']]['total'] = System::display_number(System::calculate_number($this->map['items'][$value['mice_invoice_id']]['total'])+$value['total_amount']);
                $this->map['items'][$value['mice_invoice_id']]['total_remain'] = System::display_number(System::calculate_number($this->map['items'][$value['mice_invoice_id']]['total_remain'])+($value['total_amount']-$total_print_vat));
                $this->map['total_remain'] += System::calculate_number($value['total_amount']-$total_print_vat);
                $this->map['total'] += $value['total_amount'];
            }
        }
        $this->map['total'] = System::display_number(round($this->map['total']));
        $this->map['total_remain'] = System::display_number(round($this->map['total_remain']));
        //System::debug($this->map['items']);
        
        $this->parse_layout('list', $this->map);
    }
}
?>