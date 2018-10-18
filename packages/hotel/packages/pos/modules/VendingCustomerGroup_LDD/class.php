<?php 
class VendingCustomerGroup extends Module
{
	function VendingCustomerGroup($row)
	{
		Module::Module($row);
        require_once 'db.php';
		if(User::can_view(false,ANY_CATEGORY))
		{
			Module::Module($row);
    		require_once 'db.php';
    		if(User::can_view(false,ANY_CATEGORY))
    		{
    			switch(URL::get('cmd'))
    			{			
    			case 'export_cache':				
    				$this->export_cache();
    				break;
    			case 'delete':				
    				$this->delete_cmd();
    				break;
    			case 'edit':				
    				$this->edit_cmd();
    				break;
    			case 'add':				
    				$this->add_cmd();
    				break;
    			case 'view':
    				$this->view_cmd();
    				break;
    			case 'move_up':
    			case 'move_down':
    				$this->move_cmd();
    				break;
    			default: 
    				$this->list_cmd();
    				break;
    			}
    		}
    		else
    		{
    			URL::access_denied();
    		}
	   }
    }
    function add_cmd()
	{
		if(User::can_add(false,ANY_CATEGORY))
		{
			require_once 'forms/edit.php';
			$this->add_form(new EditCustomerGroupForm());
		}
		else
		{
			Url::redirect_current();
		}
	}
	function delete_cmd()
	{
		if(is_array(URL::get('selected_ids')) and sizeof(URL::get('selected_ids'))>0 and User::can_delete(false,ANY_CATEGORY))
		{
            $selected_ids = URL::get('selected_ids');
            foreach($selected_ids as $v)
            {
                VendingCustomerGroupDB::delete_module('vending_customer_group',$v);
            }
		}
        Url::redirect_current();
	}
	function edit_cmd()
	{
		if(Url::get('id') and $warehouse=DB::fetch('select id,structure_id from vending_customer_group where id='.intval(Url::get('id'))) and User::can_edit(false,$warehouse['structure_id']))
		{
			require_once 'forms/edit.php';
			$this->add_form(new EditCustomerGroupForm());
		}
		else
		{
			Url::redirect_current();
		}
	}
	function list_cmd()
	{
		if(User::can_view(false,ANY_CATEGORY))
		{
			require_once 'forms/list.php';
			$this->add_form(new ListCustomerGroupForm());
		}	
		else
		{
			Url::access_denied();
		}
	}
	function view_cmd()
	{
		if(User::can_view_detail(false,ANY_CATEGORY) and Url::check('id') and DB::exists_id('vending_customer_group',$_REQUEST['id']))
		{
			require_once 'forms/detail.php';
			$this->add_form(new WarehouseForm());
		}
		else
		{
			Url::redirect_current();
		}
	}
	function move_cmd()
	{
		if(User::can_edit(false,ANY_CATEGORY)and Url::check('id')and $warehouse=DB::exists_id('vending_customer_group',$_REQUEST['id']))
		{
			if($warehouse['structure_id']!=ID_ROOT)
			{
				require_once 'packages/core/includes/system/si_database.php';
				si_move_position('vending_customer_group');
			}
			Url::redirect_current();
		}
		else
		{
			Url::redirect_current();
		}
	}
}
?>