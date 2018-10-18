<?php
class OptionForm extends Form
{
	function OptionForm()
	{
		Form::Form('OptionForm');
		$this->link_css(Portal::template('hotel').'/css/setting.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css('packages/core/skins/default/css/jquery/datepicker.css');		
	}
	function draw()
	{
	 $this->parse_layout('option');
	}

}
?>