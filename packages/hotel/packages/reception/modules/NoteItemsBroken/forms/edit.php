<?php
class NoteItemsBrokenForm extends Form
{
	function NoteItemsBrokenForm()
	{
		Form::Form('NoteItemsBrokenForm');
	}	
	function draw()
	{
        echo "aaa";
        $this->map = array();
		$this->parse_layout('edit',$this->map);
	}
}
?>