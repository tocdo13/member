<?php 
class VendingReservation extends Module
{
	function VendingReservation($row)
    {
		Module::Module($row);

        if(Url::check('selected_ids') and is_array(Url::get('selected_ids')) and sizeof(Url::get('selected_ids'))>0  and User::can_delete(false,ANY_CATEGORY))
        {
            if(sizeof(Url::get('selected_ids'))>1){
            	require_once 'forms/delete_selected.php';
            	$this->add_form(new DeleteSelectedBarReservationNewForm());
            }
            else
            {
            	$ids = Url::get('selected_ids');
            	$_REQUEST['id'] = $ids[0];
            	require_once 'forms/delete.php';
            	$this->add_form(new DeleteBarReservationNewForm());
            }				
		}
        else 
        {
            switch(Url::get('cmd'))
            {
                case 'delete':
                    if(User::can_delete(false,ANY_CATEGORY))
                    {
    					require_once 'forms/delete.php';
    					$this->add_form(new DeleteBarReservationNewForm());
                        break;
                    }
                    else
                    {
                        Url::access_denied();
                    }
                default:
                    if(User::can_view(false,ANY_CATEGORY))
                    {
                        require_once 'forms/list.php';
            			$this->add_form(new ListBarReservationNewForm());
                        break;
                    }
                    else
                    {
                        Url::access_denied();
                    }
                    
            }

        } 
	}
}
?>