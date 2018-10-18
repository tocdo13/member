<?php
class CancelMiceReservationForm extends Form
{
	function CancelMiceReservationForm()
    {
		Form::Form('CancelMiceReservationForm');
        $this->link_css('packages/hotel/packages/mice/skins/css/font-awesome-4.5.0/css/font-awesome.min.css');
	}
    function on_submit()
    {
        
    }
	function draw()
    {
		$this->map = array();
        $this->parse_layout('cancel',$this->map);
    }
}
?>
