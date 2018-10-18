<?php 
class ManagerKey extends Module
{
	function ManagerKey($row)
	{
		Module::Module($row);
        if(User::can_edit(false,ANY_CATEGORY)){
            if(Url::get('cmd'))
            {
                switch (Url::get('cmd'))
                {
                    case 'create_group':
                    {
                        require_once 'forms/create_group.php';
                        $this->add_form(new CreateGroupKeyForm());
                        break;
                    }
                    case 'create':
                    {
                        require_once 'forms/create.php';
                        $this->add_form(new CreateKeyForm());
                        break;
                    }
                    case 'checkout':
                    {
                        require_once 'forms/delete.php';
                        $this->add_form(new DeleteKeyForm());
                        break;
                    }
                    case 'read':
                    {
                        require_once 'forms/read.php';
                        $this->add_form(new ReadKeyForm());
                        break;
                    }
                    case 'ip_sever':
                    {
                        require_once 'forms/ip_sever.php';
                        $this->add_form(new IpSeverForm());
                        break;
                    }
                    
                    case 'manage_door':
                    {
                        require_once 'forms/manage_door.php';
                        $this->add_form(new ManageDoorForm());
                        break;
                    }
                    case 'report':
                    {
                        require_once 'forms/report.php';
                        $this->add_form(new ReportForm());
                        break;   
                    }
                    default: 
                    {
                        require_once 'forms/menu.php';
                        $this->add_form(new MenuFunctionForm());
                    }
                }
            }
            else
            {
                require_once 'forms/menu.php';
                $this->add_form(new MenuFunctionForm());
            }
		}else{
			URL::access_denied();
		}
	}
}
?>