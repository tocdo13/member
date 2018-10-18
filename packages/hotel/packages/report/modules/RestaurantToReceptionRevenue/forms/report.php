<?php
class RestaurantToReceptionRevenueForm extends Form
{
	function RestaurantToReceptionRevenueForm()
	{
		Form::Form('RestaurantToReceptionRevenueForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw()
	{
		if(URL::get('do_search'))
		{
			
		}
		else
		{
			
		}			
	}
}
?>