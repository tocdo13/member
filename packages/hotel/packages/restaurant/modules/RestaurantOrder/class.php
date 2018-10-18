<?php 
class RestaurantOrder extends Module
{
	function RestaurantOrder($row)
	{
		Module::Module($row);
       
		if(User::can_view(false,ANY_CATEGORY))
		{
			
				switch(URL::get('cmd'))
				{
				default: 
					
					require_once 'forms/order.php';
					$this->add_form(new  RestaurantOrderForm());
					break;
				}
	    }
		else
		{
			URL::access_denied();
		}
	}
}
?>