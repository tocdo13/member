<?php 
class PurchasesProposed extends Module
{
	function PurchasesProposed($row)
	{
		Module::Module($row);
		switch (Url::get('cmd')){
			case 'add':
				if(User::can_add(false,ANY_CATEGORY)){//false,ANY_CATEGORY
					require_once 'forms/add.php';
					$this->add_form(new AddPurchasesProposedForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'edit':
				if(User::can_edit(false,ANY_CATEGORY)){
                    require_once 'forms/edit.php';
					$this->add_form(new EditPurchasesProposedForm());
				}else{
					Url::access_denied();
				}
				break;
			default:
				if(User::can_view(false,ANY_CATEGORY)){
                    
					require_once 'forms/list.php';
					$this->add_form(new ListPurchasesProposedForm());
				}else{
					Url::access_denied();
				}
				break;
		}
	}	
}
?>