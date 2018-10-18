<?php 
class GolfPitchPriceManager extends Module
{
	function GolfPitchPriceManager($row)
	{
		Module::Module($row);
		switch(Url::get('cmd')){
	         case "add":
	         case "edit":
               if(User::can_edit(false,ANY_CATEGORY)){
                    require_once 'forms/edit.php';
	               $this->add_form(new GolfPitchPriceManagerEditForm());
               }
               else{
                Url::access_denied();
               } 
               break; 
             default:
				if(User::can_view(false,ANY_CATEGORY)){
					require_once 'forms/list.php';
					$this->add_form(new GolfPitchPriceManagerForm());
				}
                else{
					Url::access_denied();
				}
				break;    
	     }
	}
}
?>