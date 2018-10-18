<?php 
class ManageBanquetRoom extends Module
{
	function ManageBanquetRoom($row)
	{
		Module::Module($row);
        if(User::can_edit(false,ANY_CATEGORY)){
			require_once 'forms/list.php';
			$this->add_form(new ManageBanquetRoomForm());
		}else{
			URL::access_denied();
		}
	}	
}
?>