<?php 
class GeneralStatement extends Module
{
	function GeneralStatement($row){
		   Module::Module($row);
			if(Url::get('id')){
				require_once 'forms/list.php';
				$this->add_form(new GeneralStatementForm());
			}else{
				URL::access_denied();
			}
	}
}
?>