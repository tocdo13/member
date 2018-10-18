<?php
class AccessDeniedForm extends Form
{
	function AccessDeniedForm()
	{
		Form::Form('AccessDeniedForm');
        $this->link_css('packages/hotel/skins/default/css/w3c.css');
        $this->link_css('packages/hotel/skins/default/css/font-awesome-4.7.0/css/font-awesome.min.css');
	}
	function draw()
	{
		$this->parse_layout('list');
	}
}
?>
