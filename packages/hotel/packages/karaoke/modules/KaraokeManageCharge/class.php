<?php 
class KaraokeManageCharge extends Module
{
	function KaraokeManageCharge($row)
	{
		Module::Module($row);
        switch (Url::get('cmd'))
        {
            case 'add_shift':
    			if(User::can_edit(false,ANY_CATEGORY))
                {
    				require_once 'forms/add_shift.php';
    				$this->add_form(new AddShiftManageKaraokeChargeForm());
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
        			$this->add_form(new ManageKaraokeChargeForm());
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