<?php 
class WeeklyViewFolio extends Module
{
	function WeeklyViewFolio($row){
		   Module::Module($row);
			if(User::can_view(false,ANY_CATEGORY)){
				require_once 'forms/list.php';
				$this->add_form(new WeeklyViewFolioForm());
			}else{
				URL::access_denied();
			}
	}
}
?>