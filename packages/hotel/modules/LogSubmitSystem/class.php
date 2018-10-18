<?php 
class LogSubmitSystem extends Module
{
	function LogSubmitSystem($row)
	{
		Module::Module($row);
        if(User::is_admin()){
            require_once 'forms/edit.php';
		      $this->add_form(new LogSubmitSystemForm());
        }else{
            Url::access_denied();
        }
	}	
}
?>