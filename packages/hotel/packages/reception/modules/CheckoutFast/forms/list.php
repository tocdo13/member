<?php
class CheckoutFastForm extends Form
{
    function CheckoutFastForm()
    {
        Form::Form('CheckoutFastForm');
        $this->link_css('packages/hotel/packages/reception/modules/MonthlyRoomReportNew/font-awesome/css/font-awesome.min.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_css('packages/hotel/packages/reception/skins/js/jquery.windows-engine.css');
        $this->link_js('packages/hotel/packages/reception/skins/js/jquery.windows-engine.js');
    }
    
    function draw()
    {
        $this->map = array();
        $cond = '1=1';
        if(Url::get('reservation_id'))
        {
            $cond .= ' AND reservation.id = \''.Url::get('reservation_id').'\'';
        }
        if(Url::get('reservation_room_id'))
        {
            $cond .= ' AND reservation_room.id = \''.Url::get('reservation_room_id').'\'';
        }
        if(Url::get('reservation_traveller_id'))
        {
            $cond .= ' AND reservation_traveller.id = \''.Url::get('reservation_traveller_id').'\'';
        }
        if(Url::get('passport_code'))
        {
            $cond .= ' AND traveller.passport = \''.Url::get('passport_code').'\'';
        }
        $this->map['checkin_list'] = DB::fetch_all('
                                        SELECT
                                            reservation_room.id
                                            ,reservation_room.time_in
                                            ,reservation_room.time_out
                                            ,reservation_room.confirm
                                            ,reservation_room.reservation_id
                                            ,reservation_room.foc
                                            ,reservation_room.foc_all
                                            ,reservation_room.room_id
                                            ,room.name as room_name
                                            ,room_level.name as room_level_name
                                            ,customer.name as customer_name
                                            ,customer.id as customer_id
                                        FROM
                                            reservation_room
                                            inner join reservation on reservation_room.reservation_id=reservation.id
                                            inner join customer on customer.id=reservation.customer_id
                                            inner join room_level on reservation_room.room_level_id=room_level.id
                                            inner join room on room.id=reservation_room.room_id
                                            left join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
                                            left join traveller on reservation_traveller.traveller_id = traveller.id
                                        WHERE
                                            '.$cond.'
                                            AND reservation_room.status=\'CHECKIN\'
                                        ORDER BY
                                            reservation_room.reservation_id,reservation_room.time_in,room.name,room_level.name,customer.name
        ');
        $this->parse_layout('list',$this->map);
    }
}
?>
