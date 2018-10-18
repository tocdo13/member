<?php 
class AreaManage extends Module
{
	function AreaManage($row)
	{
		Module::Module($row);
        switch (Url::get('cmd'))
        {
            default:
                if(User::can_edit(false,ANY_CATEGORY))
                {
        			require_once 'forms/edit.php';
        			$this->add_form(new AreaManageForm());
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