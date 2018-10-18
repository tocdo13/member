<?php
class MenuHomeForm extends Form
{
	function MenuHomeForm()
	{
		Form::Form('MenuHomeForm');
		$this->link_css(Portal::template('hotel').'/css/home_menu.css');
	}
	function draw()
	{
		$this->map = array();
		$this->map['hotels'] = Portal::get_portal_list();
		$this->parse_layout('list',$this->map);
	}
}
?>
