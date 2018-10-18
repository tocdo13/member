<?php
class UpdateVersion extends Module
{
	function UpdateVersion($row)
	{
		Module::Module($row);
		require_once 'db.php';
		if(User::id()=='developer05')
		{
			if(Url::get('cmd') and Url::get('cmd')=='sync_module'){
			     require_once 'forms/sync_module.php';
			     $this->add_form(new UpdateVersionSyncModuleForm());
			}elseif(Url::get('cmd') and Url::get('cmd')=='sync_system'){
			     require_once 'forms/sync_system.php';
			     $this->add_form(new UpdateVersionSyncSystemForm());
			}else{
			     require_once 'forms/backup.php';
			     $this->add_form(new UpdateVersionForm());
			}		
		}
		else
		{
			Url::access_denied();
		}	
	}
}
?>
