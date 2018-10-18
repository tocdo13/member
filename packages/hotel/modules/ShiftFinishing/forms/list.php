<?php
class EditShiftFinishingForm extends Form
{
	function EditShiftFinishingForm()
	{
		Form::Form('EditShiftFinishingForm');
		$this->link_css(Portal::template('hotel').'/css/room.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');		
	}
	function on_submit(){
		$array = array();
	}
	function draw()
	{
		$this->map['current_time'] = date('H:i\' d/m/Y');
		$this->map['user_id'] = Session::get('user_id');
		$this->map['total'] = 0;
		
		$this->parse_layout('list',$this->map);
	}
}
?>