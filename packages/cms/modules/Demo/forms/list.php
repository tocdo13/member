<?php
class DemoForm extends Form
{
	function DemoForm()
	{
		Form::Form('DemoForm');
		//$this->link_css(Portal::template('cms').'/css/menu.css');
	}
	function draw()
	{
		$this->parse_layout('list',array());
	}
}
?>