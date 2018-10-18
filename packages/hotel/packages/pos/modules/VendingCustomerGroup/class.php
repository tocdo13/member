<?php 
class VendingCustomerGroup extends Module
{
	function VendingCustomerGroup($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			switch(URL::get('cmd'))
			{
			case 'delete':
				if(is_array(URL::get('selected_ids')) and sizeof(URL::get('selected_ids'))>0)
				{
					if(sizeof(URL::get('selected_ids'))>1)
					{
						require_once 'forms/list.php';
						$this->add_form(new ListCustomerGroupForm());
					}
					else
					{
						$ids = URL::get('selected_ids');
						$_REQUEST['id'] = $ids[0];
						require_once 'forms/detail.php';
						$this->add_form(new CustomerGroupForm());
					}
				}
				else
				if(Url::check('id') and DB::exists_id('vending_customer_group',$_REQUEST['id']))
				{
					require_once 'forms/detail.php';
					$this->add_form(new CustomerGroupForm());
				}
				else
				{
					Url::redirect_current();
				}
				break;
			case 'edit':
				if(Url::check('id') and DB::exists_id('vending_customer_group',$_REQUEST['id']) and User::can_edit(false,ANY_CATEGORY))
				{
					require_once 'forms/edit.php';
					$this->add_form(new EditCustomerGroupForm());
				}
				else
				{
					Url::redirect_current();
				}
				break;
			case 'add':
				require_once 'forms/edit.php';
				$this->add_form(new EditCustomerGroupForm());
				break;
			case 'view':
				if(Url::check('id') and DB::exists_id('vending_customer_group',$_REQUEST['id']) and User::can_delete(false,ANY_CATEGORY))
				{
					require_once 'forms/detail.php';
					$this->add_form(new CustomerGroupForm());
				}
				else
				{
					Url::redirect_current();
				}
				break;
			case 'move_up':
			case 'move_down':
				if(Url::get('id') and $category=DB::select('vending_customer_group','id=\''.$_REQUEST['id'].'\'') and User::can_edit(false,ANY_CATEGORY))
				{
					if($category['structure_id']!=ID_ROOT)
					{
						require_once 'packages/core/includes/system/si_database.php';
						si_move_position('customer_group');
					}
					Url::redirect_current();
				}
				else
				{
					Url::redirect_current();
				}
				break;
			default: 
				require_once 'forms/list.php';
				$this->add_form(new ListCustomerGroupForm());
				break;
			}
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>