<?php 
class EmailGroupEvent extends Module
{
	function EmailGroupEvent($row)
	{
		Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
        	switch (Url::get('cmd'))
            {
    		    case 'list_customer':
    			
    				require_once 'forms/choose_customer.php';
    				$this->add_form(new ChooseCustomer());
    			break;
        		default:
        				require_once 'forms/edit.php';
        				$this->add_form(new EditEmailGroupEvent());
        		break;
    		}
	   }
       else
       {
		   Url::access_denied();
	   }
     }  
}
?>