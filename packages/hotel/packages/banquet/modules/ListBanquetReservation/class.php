<?php 
class ListBanquetReservation extends Module{
	function ListBanquetReservation($row){
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			require_once 'forms/list.php';
			$this->add_form(new ListBanquetReservationForm());
		}
		else
            Url::access_denied();
	}
}
?>