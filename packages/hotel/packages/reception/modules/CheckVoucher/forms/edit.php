<?php
class CheckVoucherForm extends Form
{
	function CheckVoucherForm()
	{
		Form::Form('CheckVoucherForm');
        $this->link_css('skins/default/bootstrap/css/bootstrap.min.css');
        $this->link_js('skins/default/bootstrap/js/bootstrap.js');
	}	
	function draw()
	{
		$this->parse_layout('edit',array());
	}
}
?>