<?php 
class ManageBanquetType extends Module
{
	function ManageBanquetType($row)
	{
		Module::Module($row);
        if(User::can_edit(false,ANY_CATEGORY)){
			require_once 'forms/list.php';
			$this->add_form(new ManageBanquetTypeForm());
		}else{
			URL::access_denied();
		}
	}	
}
?>