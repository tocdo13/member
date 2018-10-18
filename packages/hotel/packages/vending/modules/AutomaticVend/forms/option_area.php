<?php
class OptionAreaBarForm extends Form
{
	function OptionAreaBarForm()
	{
		Form::Form('OptionAreaBarForm');
	}
    
	function draw()
	{
        require_once 'packages/hotel/packages/vending/includes/php/vending.php';
        $area = get_area_vending();
        
		$this->parse_layout('option_area',array('area'=>$area));
	}
}
?>