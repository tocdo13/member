<?php 
class ManagerKeyAdel extends Module
{
	function ManagerKeyAdel($row)
	{
		Module::Module($row);
        if(User::can_edit(false,ANY_CATEGORY)){
            if(Url::get('cmd'))
            {
                switch (Url::get('cmd'))
                {
                    case 'create_group':
                    {
                        require_once 'forms/create_group_adel.php';
                        $this->add_form(new CreateGroupKeyForm());
                        break;
                    }
                    case 'create':
                    {
						require_once 'forms/create_adel.php';
                        $this->add_form(new CreateKeyForm());
                        break;
                    }
                    case 'delete':
                    {
                        require_once 'forms/delete_adel.php';
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
                    case 'checkout':
                    {
                        require_once 'forms/checkout.php';
                        $this->add_form(new CheckOutForm());
                        break;
                    }
                    case 'report_key':
                    {
                        require_once 'forms/report_key.php';
                        $this->add_form(new ReportKeyForm());
                        break;    
                    }
                    default : 
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