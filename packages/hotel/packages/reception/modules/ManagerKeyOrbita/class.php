<?php 
class ManagerKeyOrbita extends Module
{
	function ManagerKeyOrbita($row)
	{
		Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY)){
            if(Url::get('cmd'))
            {
                switch (Url::get('cmd'))
                {
                    case 'create':
                    {
						require_once 'forms/create_fox.php';
                        $this->add_form(new CreateKeyForm());
                        break;
                    }
                    case 'create_group':
                    {
                        require_once 'forms/create_group_fox.php';
                        $this->add_form(new CreateGroupKeyForm());
                        break;
                    }
                    case 'read':
                    {
                        require_once 'forms/read.php';
                        $this->add_form(new ReadKeyForm());
                        break;
                    }
                    case 'checkout':
                    {
                        require_once 'forms/checkout.php';
                        $this->add_form(new CheckoutForm());
                        break;
                    }
                    case 'ip_server':
                    {
                        require_once 'forms/ip_server.php';
                        $this->add_form(new IpServerForm());
                        break;
                    }
                    case 'manage_door';
                    {
                        require_once 'forms/manage_door.php';
                        $this->add_form(new ManageDoorForm());
                        break;
                    }
                    case 'commdoor';
                    {
                        require_once 'forms/commdoor.php';
                        $this->add_form(new CommdoorForm());
                        break;
                    }
                    case 'report':
                    {
                        require_once 'forms/report.php';
                        $this->add_form(new ReportForm());
                        break;   
                    }
                    default : 
                    {
                        URL::access_denied();
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