<?php 
class ReceptionShift extends Module
{
	function ReceptionShift($row)
	{
		Module::Module($row);
        switch (Url::get('cmd'))
        {
            default:
                if(User::can_edit(false,ANY_CATEGORY))
                {
        			require_once 'forms/add_shift.php';
        			$this->add_form(new ReceptionShiftForm());   
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