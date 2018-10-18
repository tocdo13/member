<?php 
class Shop extends Module
{
	function Shop($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			if(URL::check(array('delete_selected','selected_ids')) and is_array(URL::get('selected_ids')) and sizeof(URL::get('selected_ids'))>0)
			{
				
				if(sizeof(URL::get('selected_ids'))>1)
				{
					require_once 'forms/delete_selected.php';
					$this->add_form(new DeleteSelectedShopForm());
				}
				else
				{
					$ids = URL::get('selected_ids');
					$_REQUEST['id'] = $ids[0];
					require_once 'forms/delete.php';
					$this->add_form(new DeleteShopForm());
				}
			}
			else
			if(URL::check(array('edit_selected','selected_ids')) and User::can_edit(false,ANY_CATEGORY))
			{
				require_once 'forms/edit.php';
				$this->add_form(new EditShopForm());
			}
			else
			if(
				(((URL::check(array('cmd'=>'delete'))and User::can_delete(false,ANY_CATEGORY))
					and Url::check('id') and DB::exists_id('shop',$_REQUEST['id'])))
				or
				(URL::check(array('cmd'=>'add')) and User::can_add(false,ANY_CATEGORY))
				or !URL::check('cmd')
			)
			{
				switch(URL::get('cmd'))
				{
				case 'delete':
					require_once 'forms/delete.php';
					$this->add_form(new DeleteShopForm());break;
				case 'edit':
					require_once 'forms/edit.php';
					$this->add_form(new EditShopForm());break;
				case 'add':
					require_once 'forms/add.php';
					$this->add_form(new AddShopForm());break;
				default: 
					if(URL::check('id') and DB::exists_id('shop',$_REQUEST['id']))
					{
						require_once 'forms/detail.php';
						$this->add_form(new ShopForm());
					}
					else
					{
						require_once 'forms/list.php';
						$this->add_form(new ListShopForm());
					}
					break;
				}
			}
			else
			{
				Url::redirect_current();
			}
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>