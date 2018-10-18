<?php 
class ManageBarCharge extends Module
{
	function ManageBarCharge($row)
	{
		Module::Module($row);
        switch (Url::get('cmd'))
        {
            case 'add_shift':
    			if(User::can_edit(false,ANY_CATEGORY))
                {
    				require_once 'forms/add_shift.php';
    				$this->add_form(new AddShiftManageBarChargeForm());
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
        			$this->add_form(new ManageBarChargeForm());
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