<?php
class AddMatchForm extends Form
{
	function AddMatchForm()
	{
		Form::Form('AddMatchForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
	}
	function on_submit(){
       
	}
	function draw()
	{   
	    $this->map=array();
        $this->parse_layout('add_match',$this->map);
	}
    
}
?>