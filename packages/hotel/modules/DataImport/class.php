<?php 
class DataImport extends Module{
	function DataImport($row){
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY)){
			require_once 'forms/list.php';
			$this->add_form(new DataImportForm());
		}else{
			URL::access_denied();
		}
	}
}
?>