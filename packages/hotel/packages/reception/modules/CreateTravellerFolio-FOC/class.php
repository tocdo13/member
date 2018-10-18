<?php 
class CreateTravellerFolio extends Module
{
	function CreateTravellerFolio($row)
	{
		Module::Module($row);
		switch(URL::get('cmd'))
				{
			case 'create_folio':
				if(Url::get('rr_id'))
				{
					require_once 'forms/split.php';
					$this->add_form(new CreateTravellerFolioForm());break;	
				}
			case 'add_payment':
				if(Url::get('add_payment') && Url::get('traveller_id')){
					require_once 'forms/split.php';
					$this->add_form(new CreateTravellerFolioForm());break;
				}else{
					Url::redirect();
				}
			case 'group_folio':
				if(Url::get('id')){
					require_once 'forms/split_group.php';
					$this->add_form(new CreateGroupFolioForm());break;	
				}else{
					echo 'ko co id';	
					exit();
				}
		}
	}
}
?>