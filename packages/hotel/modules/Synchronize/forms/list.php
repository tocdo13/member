<?php
class SynchronizeForm extends Form
{
	function SynchronizeForm()
	{
		Form::Form('SynchronizeForm');
		$this->link_css(Portal::template('hotel').'/css/setting.css');
	}
	function on_submit()
	{
		
	}
	function draw()
	{
		$this->parse_layout('list',array('tables'=>$tables));
	}
}
?>
