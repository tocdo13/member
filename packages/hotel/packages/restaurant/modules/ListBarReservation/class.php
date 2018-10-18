<?php 
class ListBarReservation extends Module{
	function ListBarReservation($row){
		Module::Module($row);
		require_once 'db.php';
		switch(URL::get('cmd'))
		{
            case 'list_less':
    			if(User::can_view(false,ANY_CATEGORY))
    			{
    				require_once 'forms/list_less.php';
    				$this->add_form(new ListLessBarReservationNewForm());
    			}
    			else
                    Url::access_denied();
    			break;            
    		default: 
    			if(User::can_view(false,ANY_CATEGORY))
    			{
    				require_once 'forms/list.php';
    				$this->add_form(new ListBarReservationNewForm());
    			}
    			else
                    Url::access_denied();
    			break;
		}
	}
}
?>