<?php
class PingSiteminderForm extends Form
{
	function PingSiteminderForm()
	{
		Form::Form('PingSiteminderForm');
	}
	function draw()
	{
	    $this->map = array();
		$this->parse_layout('ping_siteminder',$this->map);
	}
}
?>