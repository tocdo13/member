<?php
class PrintBarForm extends Form
{
    function PrintBarForm()
    {
        Form::Form('PrintBarForm');
    }
    
    function on_submit()
    {
        
    }
    function draw()
    {
        $this->map = array();
        //echo Url::iget('id');
        $cond =' AND bar_reservation.portal_id = \''.PORTAL_ID.'\' and bar_reservation.id = '.Url::iget('id').''; 
        $sql = 'select
                    bar_reservation.id,
                    bar_reservation.total,
                    traveller.first_name || \' \' || traveller.last_name as fullname,
                    customer.name, 
                    bar_reservation.receiver_name,
                    bar_reservation.tax_rate,
                    bar_reservation.bar_fee_rate,bar.full_charge,bar.full_rate,party.full_name as user_name
                from 
                    bar_reservation
                    inner join bar ON bar.id = bar_reservation.bar_id
                    left outer join reservation_traveller on reservation_traveller.id = bar_reservation.reservation_traveller_id
                    left outer join traveller on reservation_traveller.traveller_id = traveller.id
                    left outer join customer on bar_reservation.customer_id = customer.id
					left outer join party on party.user_id = bar_reservation.check_out_user_id
                where 
                    (bar_reservation.status = \'CHECKIN\' or bar_reservation.status = \'CHECKOUT\') '.$cond.' 
                '
                ;
        
        //echo $sql  ;          
        $this->map['item'] = DB::fetch($sql);
        $this->map['item']['name'] = $this->map['item']['fullname']?$this->map['item']['fullname']:$this->map['item']['customer_name']; 
        $this->map['item']['name'] = $this->map['item']['name']?$this->map['item']['name']:$this->map['item']['receiver_name'];
        
        $this->map['item']['total_before_tax'] = ceil($this->map['item']['total']*(100/(100+$this->map['item']['tax_rate'])));
        $this->map['item']['fee_tax'] = $this->map['item']['total']- $this->map['item']['total_before_tax'];
        $this->map['item']['total_before_rate'] = ceil($this->map['item']['total_before_tax']*(100/(100+$this->map['item']['bar_fee_rate'])));
        $this->map['item']['fee_charge'] = $this->map['item']['total_before_tax'] - $this->map['item']['total_before_rate'];
        
        require_once 'packages/core/includes/utils/currency.php';
        $this->map['item']['total_in_words'] = currency_to_text($this->map['item']['total']);
        //System::debug($this->map['item']);
        
        $this->parse_layout('print',$this->map);
    }    
}


?>