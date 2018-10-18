<?php 
class ListMiceReservation extends Module
{
	function ListMiceReservation($row)
    {
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			require_once 'forms/list.php';
			$this->add_form(new ListMiceReservationForm());
		}
		else
        {
            Url::access_denied();
        }
	}
}
?>