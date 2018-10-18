<?php
class AddNumberMemberLevelForm extends Form
{
	function AddNumberMemberLevelForm()
	{
		Form::Form('AddNumberMemberLevelForm');
	}
	function on_submit()
	{
	   
	}	
	function draw()
	{
	   $this->parse_layout('add_number',$this->map);
	}
}
?>