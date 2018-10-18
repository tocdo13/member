<?php 
class VendingCustomer extends Module
{
	public static $item = array();
	function VendingCustomer($row)
	{
		Module::Module($row);
		switch (Url::get('cmd')){
			case 'add':
				if(User::can_add(false,ANY_CATEGORY)){//false,ANY_CATEGORY
					require_once 'forms/edit.php';
					$this->add_form(new EditCustomerForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'edit':
				if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and VendingCustomer::$item = DB::select('vending_CUSTOMER','ID = '.Url::iget('id'))){
					require_once 'forms/edit.php';
					$this->add_form(new EditCustomerForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'delete':
				if(User::can_delete(false,ANY_CATEGORY)){
					if(Url::get('id') and DB::exists('SELECT ID FROM vending_CUSTOMER WHERE ID = '.Url::iget('id').'')){
						DB::delete('vending_CUSTOMER','ID = '.Url::iget('id'));
						//Url::redirect('notice',array('cmd'=>'delete','href'=>'?page='.Url::get('page')));
						Url::redirect_current(array('group_id'));
					}
					if(Url::get('item_check_box')){
						$arr = Url::get('item_check_box');	
						for($i=0;$i<sizeof($arr);$i++){
							DB::delete('vending_CUSTOMER','ID = '.$arr[$i]);
						}
						//Url::redirect('notice',array('cmd'=>'delete','href'=>'?page='.Url::get('page')));
						Url::redirect_current(array('group_id'));
					}else{
						Url::redirect_current(array('group_id'));
					}
				}else{
					Url::access_denied();
				}
				break;
			default:
				if(User::can_view(false,ANY_CATEGORY)){
					require_once 'forms/list.php';
					$this->add_form(new ListCustomerForm());
				}else{
					Url::access_denied();
				}
				break;
		}
	}	
}
?>