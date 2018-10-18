<?php
class BackupByTime extends Module
{
	function BackupByTime($row)
	{
		Module::Module($row);
		require_once 'db.php';
        switch(Url::get('cmd'))
        {
            case 'restore':
                if(User::can_admin(false,ANY_CATEGORY))
                {
					require_once 'forms/restore.php';
					$this->add_form(new RestoreForm());
				}
                else
					Url::access_denied();
				break;
            default:
        		if(User::can_admin(false,ANY_CATEGORY))
        		{
        			require_once 'forms/backup.php';
        			$this->add_form(new BackupByTimeForm());		
        		}
        		else
        		{
        			Url::access_denied();
        		}	
        }	
	}
}
?>
