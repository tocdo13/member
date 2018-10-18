<?php
class SiteminderApiSecretKeyForm extends Form
{
	function SiteminderApiSecretKeyForm()
	{
		Form::Form('ApiSecretKeyForm');
	}
	function draw()
	{
	    $this->map = array();
		$this->parse_layout('edit',$this->map);
	}
}
?>