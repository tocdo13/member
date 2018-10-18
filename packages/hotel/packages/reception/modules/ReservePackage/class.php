<?php 
class ReservePackage extends Module
{
	function ReservePackage($row)
	{
		Module::Module($row);
		switch (Url::get('cmd'))
        {
			case 'res':
            case 'spa':
            case 'party':
            case 'vend':
            case 'kar':
            {
                if(User::can_view(false,ANY_CATEGORY)){
					require_once 'forms/list.php';
					$this->add_form(new ReservePackageForm());
				}else{
					Url::access_denied();
				}
                break;
            }
			default:
				Url::access_denied();
				break;
		}
	}		
}
?>
