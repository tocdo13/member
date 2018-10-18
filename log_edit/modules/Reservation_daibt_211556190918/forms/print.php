<?php
class PrintReservationForm extends Form
{
    function PrintReservationForm()
    {
        Form::Form('PrintReservationForm');
    }
    function on_submit()
    {
    }
    function draw()
    {
        $this->map = array();
        require_once 'packages/hotel/packages/reception/modules/includes/get_reservation.php';
        $info = get_reservation(Url::iget('id'),Url::iget('r_r_id'),true);
        //System::debug($info);
        //System::debug($info['items'][Url::iget('r_r_id')]['total_amount']);
        $this->map['total'] = $info['items'][Url::iget('r_r_id')]['total_amount'];
        require_once 'packages/core/includes/utils/currency.php';
        $this->map['total_in_words'] = currency_to_text($this->map['total']);
        $cond =' AND reservation.portal_id = \''.PORTAL_ID.'\' and reservation.id = '.Url::iget('id').' and reservation_room.id = '.Url::iget('r_r_id').'';
        $sql = 'select
                    reservation_room.id,
                    reservation.id as reservation_id,
                    reservation_room.price,
                    traveller.first_name || \' \' || traveller.last_name as fullname,
                    reservation_room.arrival_time,
                    reservation_room.departure_time,
                    reservation_room.time_in,
                    reservation_room.time_out,
                    reservation_room.departure_time - reservation_room.arrival_time as night,
                    DECODE(
                    concat(tour.name, customer.name),           \'\',\'\',
                                                                customer.name, customer.name,
                                                                tour.name, \'(Tour)\' || \' \' || tour.name,
                                                                concat(tour.name, customer.name), \'(Tour)\' || \' \' || tour.name || \'-\' || customer.name,
                                                                \' \'
                    )  as note,
                    traveller.id as traveller_id,
                    customer.address as customer_address,
                    traveller.address as traveller_address,
                    customer.tax_code
                from
                    reservation_room inner join reservation on reservation.id = reservation_room.reservation_id
                    left outer join traveller on reservation_room.traveller_id = traveller.id
                    left outer join tour on reservation.tour_id = tour.id
                    left outer join customer on reservation.customer_id = customer.id
                where
                    (reservation_room.status = \'CHECKIN\' or reservation_room.status = \'CHECKOUT\') '.$cond.'
                '
                ;
        //echo $sql  ;
        $this->map['item'] = DB::fetch($sql);
        //System::debug($this->map['item']);
        $this->parse_layout('print',$this->map);
    }
}
?>