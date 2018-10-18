<?php 
class TicketDeclaration extends Module
{
	function TicketDeclaration($row)
	{
		Module::Module($row);
        switch (Url::get('cmd'))
        {
            case 'add_ticket_service':
    			if(User::can_edit(false,ANY_CATEGORY))
                {
    				require_once 'forms/add_ticket_service.php';
    				$this->add_form(new AddTicketServiceForm());
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
        			$this->add_form(new TicketDeclarationForm());
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