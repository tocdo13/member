<?php

class ActiveDepartment extends Module
{
	function ActiveDepartment($row)
    {
        //System::debug(DB::select_all('portal_department'));
		Module::Module($row);
		require_once 'db.php';
        switch(Url::get('cmd'))
        {
            case 'edit':  
            case 'add':
            	if(User::can_admin(false,ANY_CATEGORY))
                {
                    require_once 'forms/add.php';
                    $this->add_form(new AddActiveDepartmentForm());
                }
                else
					Url::access_denied();
                break;
            default:
            	if(User::can_admin(false,ANY_CATEGORY))
                {
                    require_once 'forms/list.php';
                    $this->add_form(new ListActiveDepartmentForm());
                }
                else
					Url::access_denied();
                break;				
        }
	}

}

?>