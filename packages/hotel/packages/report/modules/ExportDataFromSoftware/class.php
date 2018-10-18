<?php 
class ExportDataFromSoftware extends Module
{
	function ExportDataFromSoftware($row)
    {
        Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY))
        {
            
            /*if(User::id()=='developer14')
            {
                if(
    				(URL::check(array('cmd'=>md5('ExportDataFromSoftwareNewway@2017'))))
    				or !URL::check('cmd')
    			)
                {
                    switch(URL::get('cmd'))
                    { 
                        case md5('ExportDataFromSoftwareNewway@2017'):
            				require_once 'forms/report_old.php';
                            $this->add_form(new ExportDataFromSoftwareForm());
                        break;
                        default: 
    						require_once 'forms/report_old.php';
    						$this->add_form(new ExportDataFromSoftwareForm());
                        break;
                    }
                }
            }else*/if(
				(URL::check(array('cmd'=>md5('ExportDataFromSoftwareNewway@'.date('Y')).md5('n2d'))))
				or !URL::check('cmd')
			)
            {
                switch(URL::get('cmd'))
    			{ 
                    case md5('ExportDataFromSoftwareNewway'.date('Y')).md5('n2d'):
        				require_once 'forms/report.php';
                        $this->add_form(new ExportDataFromSoftwareForm());
                    break;
                    default: 
						require_once 'forms/report.php';
						$this->add_form(new ExportDataFromSoftwareForm());
                    break;
                }
            }
        }
        else
        {
            URL::access_denied();
        }
	}
}
?>