<?php 
class WarehouseProduct extends Module
{
	function WarehouseProduct($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			if(URL::check(array('cmd','selected_ids')) and Url::get('cmd')=='delete_selected' and is_array(URL::get('selected_ids')) and sizeof(URL::get('selected_ids'))>0)
			{
				if(sizeof(URL::get('selected_ids'))>1)
				{
					require_once 'forms/delete_selected.php';
					$this->add_form(new DeleteSelectedWarehouseProductForm());
				}
				else
				{
					$ids = URL::get('selected_ids');
					$_REQUEST['id'] = $ids[0];
					require_once 'forms/delete.php';
					$this->add_form(new DeleteWarehouseProductForm());
				}
			}
			else
			if(URL::check(array('cmd','selected_ids')) and Url::get('cmd')=='edit_selected' and User::can_edit(false,ANY_CATEGORY))
			{
				require_once 'forms/edit.php';
				$this->add_form(new EditWarehouseProductForm());
			}
			else
			if(
				(((URL::check(array('cmd'=>'delete')) and User::can_delete(false,ANY_CATEGORY)) 
					or ((URL::check(array('cmd'=>'edit'))and User::can_edit(false,ANY_CATEGORY)))
					and Url::check('id') and $product = DB::select(PRODUCT,'id=\''.$_REQUEST['id'].'\'')))
				or !URL::check('cmd')
			)
			{
				switch(URL::get('cmd'))
				{
				case 'delete':
					require_once 'forms/delete.php';
					$this->add_form(new DeleteWarehouseProductForm());break;
				case 'edit':
					require_once 'forms/edit.php';
					$this->add_form(new EditWarehouseProductForm());break;
				default: 
					if(URL::check('id') and DB::exists('select id from wh_product where id=\''.$_REQUEST['id'].'\' and portal_id=\''.PORTAL_ID.'\''))
					{
						require_once 'forms/detail.php';
						$this->add_form(new WarehouseProductForm());
					}
					else
					{
						require_once 'forms/list.php';
						$this->add_form(new ListWarehouseProductForm());
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