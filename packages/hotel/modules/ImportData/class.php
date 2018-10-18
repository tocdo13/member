<?php
class ImportData extends Module
{
	function ImportData($row)
	{
		Module::Module($row);
		if(User::can_admin(false,ANY_CATEGORY))
		{
            switch (Url::get('cmd'))
            {
                case 'wh_import' :
                {
                    require_once 'forms/wh_import.php';
                    $this->add_form(new WhImportForm());
                    break;
                }
                default :
                {
                    require_once 'forms/option.php';
                    $this->add_form(new OptionForm());
                    break;
                }
            }
					
		}
		else
		{
			Url::access_denied();
		}	
	}
}
?>
