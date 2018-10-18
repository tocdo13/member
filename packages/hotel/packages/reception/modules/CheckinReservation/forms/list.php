<?php
class CheckinReservationForm extends Form
{
	function CheckinReservationForm()
	{
		Form::Form('CheckinReservationForm');
        $this->link_css('packages/hotel/packages/reception/modules/MonthlyRoomReportNew/font-awesome/css/font-awesome.min.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_css('packages/hotel/packages/reception/skins/js/jquery.windows-engine.css');
        $this->link_js('packages/hotel/packages/reception/skins/js/jquery.windows-engine.js');
    }
	function draw()
	{
	   $this->map = array();
       $cond = '';
       if(isset($_REQUEST['checkin']) and $_REQUEST['checkin']==1)
       {
            $this->checkin_room($_REQUEST['r_id'],$_REQUEST['rr_id']);
       }
       if(isset($_REQUEST['reservation_traveller_id']) and $_REQUEST['reservation_traveller_id']!='')
       {
            $cond.= ' and reservation_traveller.id = '.$_REQUEST['reservation_traveller_id'];
       }
       if(isset($_REQUEST['reservation_room_id']) and $_REQUEST['reservation_room_id']!='')
       {
            $cond.= ' and reservation_room.id = '.$_REQUEST['reservation_room_id'];
       }
       if(isset($_REQUEST['reservation_id']) and $_REQUEST['reservation_id']!='')
       {
            $cond.= ' and reservation.id = '.$_REQUEST['reservation_id'];
       }
       $this->map['arrival_list'] = DB::fetch_all('
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
                                                    WHERE
                                                        reservation_room.status=\'BOOKED\'
                                                        --and reservation_room.arrival_time=\''.Date_Time::to_orc_date(date('d/m/Y')).'\'
                                                        '.$cond.'
                                                    ORDER BY
                                                        reservation_room.time_in,room.name,room_level.name,customer.name
                                                    ');
       $this->parse_layout('list',$this->map);
	}
}
?>
