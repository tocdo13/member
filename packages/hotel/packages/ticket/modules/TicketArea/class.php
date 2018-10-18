<?php 
class TicketArea extends Module
{
	function TicketArea($row)
	{
		Module::Module($row);
        switch (Url::get('cmd'))
        {
            case 'add_ticket_type':
    			if(User::can_edit(false,ANY_CATEGORY))
                {
    				require_once 'forms/add_ticket_type.php';
    				$this->add_form(new AddTicketTypeForm());
    			}
                else
                {
    				Url::access_denied();
    			}
    			break;
            default:
                if(User::can_edit(false,ANY_CATEGORY))
                {
        			require_once 'forms/edit.php';
        			$this->add_form(new AreaForm());
        		}
                else
                {
        			URL::access_denied();
        		}
                break;
        }
	}	
}
?>