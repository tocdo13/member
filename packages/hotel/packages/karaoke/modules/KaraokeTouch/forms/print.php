<?php
class PrintKaraokeForm extends Form
{
    function PrintKaraokeForm()
    {
        Form::Form('PrintKaraokeForm');
    }
    
    function on_submit()
    {
        
    }
    function draw()
    {
        $this->map = array();
        //echo Url::iget('id');
        $cond =' AND karaoke_reservation.portal_id = \''.PORTAL_ID.'\' and karaoke_reservation.id = '.Url::iget('id').''; 
        $sql = 'select
                    karaoke_reservation.id,
                    karaoke_reservation.total,
                    traveller.first_name || \' \' || traveller.last_name as fullname,
                    customer.name as customer, 
                    karaoke_reservation.receiver_name,
					karaoke.name as karaoke_name,
                    FROM_UNIXTIME(karaoke_reservation.time) as time,
                    karaoke_reservation.tax_rate,
                    karaoke_reservation.karaoke_fee_rate,karaoke.full_charge,karaoke.full_rate,party.full_name as user_name,
					karaoke_reservartion_table.num_people, karaoke_reservation_table.order_person
                from 
                    karaoke_reservation
                    inner join karaoke ON karaoke.id = karaoke_reservation.karaoke_id
                    left outer join reservation_traveller on reservation_traveller.id = karaoke_reservation.reservation_traveller_id
                    left outer join traveller on reservation_traveller.traveller_id = traveller.id
                    left outer join customer on karaoke_reservation.customer_id = customer.id
					left outer join karaoke_reservation on karaoke_reservation.id = karaoke_reservation_table.karaoke_reservation_id
					left outer join party on party.user_id = karaoke_reservation.check_out_user_id
                where 
                    (karaoke_reservation.status = \'CHECKIN\' or karaoke_reservation.status = \'CHECKOUT\') '.$cond.' 
                '
                ;
        
        //echo $sql  ;          
        $this->map['item'] = DB::fetch($sql);
        //System::debug($this->map['item']);exit();
        $this->map['item']['name'] = $this->map['item']['fullname']?$this->map['item']['fullname']:$this->map['item']['customer_name']; 
        $this->map['item']['name'] = $this->map['item']['name']?$this->map['item']['name']:$this->map['item']['receiver_name'];
        
        $this->map['item']['total_before_tax'] = ceil($this->map['item']['total']*(100/(100+$this->map['item']['tax_rate'])));
        $this->map['item']['fee_tax'] = $this->map['item']['total']- $this->map['item']['total_before_tax'];
        $this->map['item']['total_before_rate'] = ceil($this->map['item']['total_before_tax']*(100/(100+$this->map['item']['karaoke_fee_rate'])));
        $this->map['item']['fee_charge'] = $this->map['item']['total_before_tax'] - $this->map['item']['total_before_rate'];
        
        require_once 'packages/core/includes/utils/currency.php';
        $this->map['item']['total_in_words'] = currency_to_text($this->map['item']['total']);
        System::debug($this->map);
        
        $this->parse_layout('print',$this->map);
    }    
}


?>