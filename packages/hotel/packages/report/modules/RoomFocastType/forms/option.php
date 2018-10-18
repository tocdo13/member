<?php
class RoomFocastTypeForm extends Form
{
	function RoomFocastTypeForm()
	{
		Form::Form('RoomFocastTypeForm');
		$this->link_js("packages/core/includes/js/jquery/datepicker.js");
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_css("skins/default/report.css");
	}
	function draw()
	{
	   $this->parse_layout('option');	   
	}
}
?>