<?php 
class Karaoke extends Module
{
	function Karaoke($row)
	{
		Module::Module($row);
        switch (Url::get('cmd'))
        {
            case 'add_shift':
    			if(User::can_edit(false,ANY_CATEGORY))
                {
    				require_once 'forms/add_shift.php';
    				$this->add_form(new AddShiftKaraokeForm());
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
        			$this->add_form(new KaraokeForm());
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