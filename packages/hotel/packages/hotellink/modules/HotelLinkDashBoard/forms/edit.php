<?php
class HotelLinkDashBoardForm extends Form
{
	function HotelLinkDashBoardForm()
	{
		Form::Form('HotelLinkDashBoardForm');
		$this->link_js('packages/core/includes/js/multi_items.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.mask.min.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');        
	}	
	function draw(){
		$this->parse_layout('edit',array());
	}
}
?>