<?php 
class FocRestaurant extends Module
{
	function FocRestaurant($row)
	{
		Module::Module($row);		
		if(User::can_view(false,ANY_CATEGORY))
		{
			require_once 'forms/report.php';
			$this->add_form(new FocRestaurantForm());
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>