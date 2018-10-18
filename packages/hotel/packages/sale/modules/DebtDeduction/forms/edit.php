<?php
class DebtDeductionForm extends Form
{
    function DebtDeductionForm()
    {
        Form::Form();
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css'); 
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css'); 
    }
    function on_submit()
    {
        $recode = array(
                        'customer_id'=>Url::get('customer_id'),
                        'price'=>System::calculate_number(Url::get('price')),
                        'description'=>Url::get('description'),
                        'recode'=>Url::get('recode'),
                        'bar_reservation_ids'=>Url::get('bar_reservation_id'),
                        'bar_reservation_code'=>Url::get('bar_reservation_code'),
                        'folio_number'=>Url::get('folio_number'),
                        'date_in'=>Date_Time::to_orc_date(Url::get('date_in')),
                        'portal_id'=>PORTAL_ID,
                        'user_id'=>User::id(),
                        'payment_type_id'=>Url::get('payment_type_id')
                        );
        if(Url::get('id'))
        {
            $id = Url::get('id');
            DB::update('customer_review_debt',$recode,'id='.Url::get('id'));
        }
        else
        {
            $id = DB::insert('customer_review_debt',$recode);
        }
        Url::redirect('debit_deduction');
    }
    function draw()
    {
        // du lieu nap vao ban dau
        $this->map['recode'] = Url::get('recode')?Url::get('recode'):'';
        $this->map['folio_number'] = Url::get('folio_number')?Url::get('folio_number'):'';
        $this->map['bar_reservation_ids'] = Url::get('bar_reservation_id')?Url::get('bar_reservation_id'):'';
        $this->map['bar_reservation_code'] = Url::get('bar_reservation_code')?Url::get('bar_reservation_code'):'';
        $this->map['customer_id'] = Url::get('customer_id')?Url::get('customer_id'):'';
        $this->map['customer_name'] = '';
        $this->map['date_in'] = date('d/m/Y');
        $this->map['payment_type_id'] = '';
        $this->map['payment_type_id_list'] = array(''=>Portal::language('all'))+String::get_list(DB::fetch_all('select def_code as id,name_'.Portal::language().' as name from payment_type where def_code is not null and apply=\'ALL\' and def_code!=\'FOC\' and def_code!=\'REFUND\' and def_code!=\'DEBIT\''));
        $this->map['total_invoice'] = 0;
        $this->map['price'] = 0;
        $this->map['description'] = '';
        $this->map['count_folio'] = 1;
        if(Url::get('id'))
        {// truong hop edit
            $review = DB::fetch('select customer_review_debt.*,to_char(customer_review_debt.date_in,\'DD/MM/YYYY\') as date_in from customer_review_debt where id='.Url::get('id'));
            $this->map['recode'] = $review['recode'];
            $this->map['folio_number'] = $review['folio_number'];
            $this->map['bar_reservation_ids'] = $review['bar_reservation_ids'];
            $this->map['bar_reservation_code'] = $review['bar_reservation_code'];
            $this->map['customer_id'] = $review['customer_id'];
            $this->map['date_in'] = $review['date_in'];
            $this->map['payment_type_id'] = $review['payment_type_id'];
            $this->map['price'] = System::display_number($review['price']);
            $this->map['description'] = $review['description'];
        }
        // lay ten nguon khac
        if($this->map['customer_id']!='')
            $this->map['customer_name'] = DB::fetch('select name from customer where id='.$this->map['customer_id'].'','name');
        // lay tong tien hoa don folio - debit
        if($this->map['folio_number']!='')
        {
            $folio_arr = explode(',',$this->map['folio_number']);
            $this->map['folio_number'] = '';
            $this->map['count_folio'] = sizeof($folio_arr);
            for($i=0;$i<sizeof($folio_arr);$i++)
            {
                if($this->map['folio_number']=='')
                    $this->map['folio_number'] = trim($folio_arr[$i]);
                else
                    $this->map['folio_number'] .= ','.trim($folio_arr[$i]);
            }
            
            $total_debit = DB::fetch_all('select 
                                            payment.folio_id as id, 
                                            sum(payment.amount) as amount 
                                        from 
                                            payment 
                                        where 
                                            payment.folio_id in ('.$this->map['folio_number'].') 
                                            and payment.payment_type_id=\'DEBIT\'
                                        group by payment.folio_id');
            foreach($total_debit as $key=>$value)
            {
                $this->map['total_invoice'] += $value['amount'];
            }
        }
        if($this->map['bar_reservation_ids']!='')
        {
            $bar_arr = explode(',',$this->map['bar_reservation_ids']);
            $this->map['bar_reservation_ids'] = '';
            $this->map['count_folio'] = sizeof($bar_arr);
            for($i=0;$i<sizeof($bar_arr);$i++)
            {
                if($this->map['bar_reservation_ids']=='')
                    $this->map['bar_reservation_ids'] = trim($bar_arr[$i]);
                else
                    $this->map['bar_reservation_ids'] .= ','.trim($bar_arr[$i]);
            }
            
            $total_debit = DB::fetch_all('select 
                                            payment.bill_id as id, 
                                            sum(payment.amount) as amount 
                                        from 
                                            payment 
                                        where 
                                            payment.bill_id in ('.$this->map['bar_reservation_ids'].') 
                                            and payment.type=\'BAR\'
                                            and payment.payment_type_id=\'DEBIT\'
                                        group by payment.bill_id');
            foreach($total_debit as $key=>$value)
            {
                $this->map['total_invoice'] += $value['amount'];
            }
        }
        $this->map['total_invoice'] = System::display_number($this->map['total_invoice']);
        $_REQUEST += $this->map;
        $this->parse_layout('edit',$this->map);
    }
    
}
?>