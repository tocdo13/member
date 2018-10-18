<?php
class ChatForm extends Form
{
	function ChatForm()
	{
		Form::Form('ChatForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
	}
	function draw()
	{
		$this->parse_layout('list');
	}
}
?>
