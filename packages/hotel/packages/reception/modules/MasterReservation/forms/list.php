<?php
class MasterReservationForm extends Form
{
	function MasterReservationForm()
	{
		Form::Form('MasterReservationForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_css('packages/hotel/packages/reception/skins/js/jquery.windows-engine.css');
        $this->link_css('packages/hotel/packages/reception/modules/MonthlyRoomReportNew/font-awesome/css/font-awesome.min.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_css('packages/hotel/skins/default/css/w3c.css');
        $this->link_css('packages/hotel/skins/default/css/font-awesome-4.7.0/css/font-awesome.min.css');
        $this->link_js('packages/hotel/packages/reception/skins/js/jquery.windows-engine.js');
    }
	function draw()
	{
	   if(Url::get('reservation_room_id')){
	       $_REQUEST['room_name'] = DB::fetch('select room.name from reservation_room inner join room on reservation_room.room_id=room.id where reservation_room.id='.Url::get('reservation_room_id'),'name');
	   }
       $this->parse_layout('list',array());
	}
}
?>
