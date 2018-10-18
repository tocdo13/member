<?php 
class FolioCreated extends Module
{
	function FolioCreated($row){
		   Module::Module($row);
			if(User::can_view(false,ANY_CATEGORY)){
				require_once 'forms/list.php';
				$this->add_form(new FolioCreatedForm());
			}else{
				URL::access_denied();
			}
	}
}
?>