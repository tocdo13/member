<?php 
class Privilege extends Module
{
	function Privilege($row)
	{
		Module::Module($row);
		require_once 'db.php';		
		if(User::can_view(false,ANY_CATEGORY))
		{
			if(URL::check(array('cmd'=>'make_cache'))and User::can_edit(false,ANY_CATEGORY))
			{
				require_once 'packages/core/includes/system/update_privilege.php';
				make_privilege_cache();
				URL::redirect_current();
			}
			else
			if(URL::get('cmd')=='delete' and is_array(URL::get('selected_ids')) and sizeof(URL::get('selected_ids'))>0 and User::can_delete(false,ANY_CATEGORY))
			{
				if(sizeof(URL::get('selected_ids'))>1)
				{
					require_once 'forms/list.php';
					$this->add_form(new ListPrivilegeForm());
				}
				else
				{
					$ids = URL::get('selected_ids');
					$_REQUEST['id'] = $ids[0];
					require_once 'forms/detail.php';
					$this->add_form(new PrivilegeForm());
				}
			}
			else
			if(
				(((URL::check(array('cmd'=>'delete'))and User::can_delete(false,ANY_CATEGORY))
					or (URL::check(array('cmd'=>'edit')) and User::can_edit(false,ANY_CATEGORY))
					or (URL::check(array('cmd'=>'grant')) and User::can_edit(false,ANY_CATEGORY))
					or (URL::check(array('cmd'=>'view')) and User::can_view_detail()))
					and Url::check('id') and $record=DB::exists_id('privilege_group',$_REQUEST['id'])and (User::can_admin(false,ANY_CATEGORY) or $record['portal_id']==PORTAL_ID))
				or
				(URL::check(array('cmd'=>'add')) and User::can_add(false,ANY_CATEGORY))
				or !URL::check('cmd')
			)
			{
				Module::Module($row);
		require_once 'db.php';
				switch(URL::get('cmd'))
				{
				case 'delete':
					require_once 'forms/detail.php';
					$this->add_form(new PrivilegeForm());break;
				case 'edit':
				case 'add':
					require_once 'forms/edit.php';
					$this->add_form(new EditPrivilegeForm());break;
				case 'grant':
					require_once 'forms/grant.php';
					$this->add_form(new GrantPrivilegeForm());break;
				case 'view':
					require_once 'forms/detail.php';
					$this->add_form(new PrivilegeForm());break;
				default: 
					require_once 'forms/list.php';
					$this->add_form(new ListPrivilegeForm());
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
