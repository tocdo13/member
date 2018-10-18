<?php
class SummaryDeductibleReportForm extends Form
{
	function SummaryDeductibleReportForm()
	{
		Form::Form('SummaryDeductibleReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	
	function draw()
    {
        $this->map = array();
        $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):'01/'.date('m/Y');
        $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
        $_REQUEST['from_date'] = $this->map['from_date'];
        $_REQUEST['to_date'] = $this->map['to_date'];
        
        $this->map['customer_id_list'] = array(''=>Portal::language('all'))+String::get_list(DB::fetch_all('select id,name from customer where group_id is not null order by name'));
        $this->map['payment_type_id_list'] = array(''=>Portal::language('all'))+String::get_list(DB::fetch_all('select def_code as id,name_'.Portal::language().' as name from payment_type where def_code is not null and apply=\'ALL\' and def_code!=\'FOC\' and def_code!=\'REFUND\' and def_code!=\'DEBIT\''));
        $this->map['user_id_list'] = array(''=>Portal::language('all'))+String::get_list(DB::fetch_all('select user_id as id,full_name as name from party where type=\'USER\' order by full_name'));
        
        $cond_review = ' customer_review_debt.date_in>=\''.Date_Time::to_orc_date($this->map['from_date']).'\' and customer_review_debt.date_in<=\''.Date_Time::to_orc_date($this->map['to_date']).'\'';
        
        if(Url::get('customer_id'))
            $cond_review .= ' and  customer.id='.Url::get('customer_id');
        
        if(Url::get('payment_type_id'))
            $cond_review .= ' and  customer_review_debt.payment_type_id=\''.Url::get('payment_type_id').'\'';
        
        if(Url::get('user_id'))
            $cond_review .= ' and  customer_review_debt.user_id=\''.Url::get('user_id').'\'';
        
        $this->map['items'] = array();
        $this->map['total'] = 0;
        
        $sql = '
                SELECT
                    customer_review_debt.id,
                    TO_CHAR(customer_review_debt.date_in,\'DD/MM/YYYY\') as in_date,
                    customer_review_debt.price,
                    folio.reservation_id as recode,
                    reservation.booking_code,
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
                    party.full_name as user_name,
                    customer_review_debt.payment_type_id,
                    payment_type.name_'.Portal::language().' as payment_type_name,
                    customer.name as customer_name
                FROM
                    customer_review_debt
                    inner join customer on customer.id=customer_review_debt.customer_id
                    left join payment_type on payment_type.def_code=customer_review_debt.payment_type_id
                    left join party on party.user_id=customer_review_debt.user_id
                    left join folio on customer_review_debt.folio_id = folio.id and customer_review_debt.folio_id is not null
                    left join reservation on reservation.id=folio.reservation_id
                    left join bar_reservation on customer_review_debt.bar_reservation_id=bar_reservation.id and customer_review_debt.bar_reservation_id is not null
                    left join ve_reservation on customer_review_debt.ve_reservation_id=bar_reservation.id and customer_review_debt.ve_reservation_id is not null
                    left join bar on bar_reservation.bar_id=bar.id
                    left join mice_invoice on customer_review_debt.mice_invoice_id=mice_invoice.id and customer_review_debt.mice_invoice_id is not null
                WHERE
                    '.$cond_review.'
                    and customer_review_debt.portal_id=\''.PORTAL_ID.'\'
                ORDER BY
                    customer_review_debt.date_in
                ';
        $review = DB::fetch_all($sql);
        $stt = 1;
        foreach($review as $key=>$value)
        {
            $in_date = $value['in_date'];
            $time = Date_Time::to_time($in_date);
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
            
            $this->map['items'][$key]['stt'] = $stt++;
            $this->map['items'][$key]['in_date'] = $in_date;
            $this->map['items'][$key]['customer_name'] = $value['customer_name'];
            $this->map['items'][$key]['description'] = $value['description'];
            $this->map['items'][$key]['reservation_id'] = $value['recode'];
            $this->map['items'][$key]['booking_code'] = $value['booking_code'];
            $this->map['items'][$key]['folio_id'] = $folio;
            $this->map['items'][$key]['bar_reservation_id'] = $value['bar_reservation_id'];
            $this->map['items'][$key]['bar_reservation_code'] = $value['bar_reservation_code'];
            $this->map['items'][$key]['ve_reservation_id'] = $value['ve_reservation_id'];
            $this->map['items'][$key]['ve_reservation_code'] = $value['ve_reservation_code'];
            $this->map['items'][$key]['mice_invoice_id'] = $value['mice_invoice_id'];
            $this->map['items'][$key]['mice_invoice_code'] = ($value['mice_invoice_code']=='' and $value['mice_invoice_id']!='')?'#'.$value['mice_invoice_id']:($value['mice_invoice_id']!=''?'BILL-'.$value['mice_invoice_code']:'');
            $this->map['items'][$key]['mice_reservation_id'] = $value['mice_reservation_id'];
            $this->map['items'][$key]['link_folio'] = $link_folio;
            $this->map['items'][$key]['link_bar'] = $link_bar;
            $this->map['items'][$key]['link_vend'] = $link_vend;
            $this->map['items'][$key]['link_mice'] = $link_mice;
            $this->map['items'][$key]['price'] = System::display_number($value['price']);
            $this->map['items'][$key]['user_name'] = $value['user_name'];
            $this->map['items'][$key]['payment_type_name'] = $value['payment_type_name'];
            
            $this->map['total'] += $value['price'];
        }
        
        $this->parse_layout('report',$this->map);
	}
}
?>