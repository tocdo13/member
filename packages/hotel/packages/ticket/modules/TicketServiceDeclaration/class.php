<?php 
class TicketServiceDeclaration extends Module
{
	function TicketServiceDeclaration($row)
	{
		Module::Module($row);
        switch (Url::get('cmd'))
        {
            default:
                if(User::can_edit(false,ANY_CATEGORY))
                {
        			require_once 'forms/edit.php';
        			$this->add_form(new TicketServiceDeclarationForm());
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