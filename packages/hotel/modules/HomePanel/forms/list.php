<?php
class HomePanelForm extends Form
{
	function HomePanelForm()
	{
		Form::Form('HomePanelForm');
		$this->link_css('packages/hotel/skins/default/css/home_panel.css');
	}
	function draw()
	{
		$this->map = array();
		$this->parse_layout('list',$this->map);
	}
}
?>
