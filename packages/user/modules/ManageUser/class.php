<?php 
class ManageUser extends Module
{
	function ManageUser($row)
	{
		Module::Module($row);
		require_once 'db.php';
		if(User::can_edit(false,ANY_CATEGORY))
		{
			if(URL::get('cmd')=='delete_privilege_cache' and User::can_edit(false,ANY_CATEGORY))
			{
				Url::redirect_current(array('join_date_start','join_date_end',  'active'=>isset($_GET['active'])?$_GET['active']:'', 'block'=>isset($_GET['block'])?$_GET['block']:'',  'user_id'=>isset($_GET['user_id'])?$_GET['user_id']:''));
			}
			else
			if(URL::get('cmd')=='delete' and is_array(URL::get('selected_ids')) and sizeof(URL::get('selected_ids'))>0 and User::can_delete(false,ANY_CATEGORY))
			{
				if(sizeof(URL::get('selected_ids'))>1)
				{
					require_once 'forms/list.php';
					$this->add_form(new ListManageUserForm());
				}
				else
				{
					$ids = URL::get('selected_ids');
					$_REQUEST['id'] = $ids[0];
					require_once 'forms/detail.php';
					$this->add_form(new ManageUserForm());
				}
			}
			else
			if(
				(((URL::check(array('cmd'=>'delete'))and User::can_delete(false,ANY_CATEGORY))
					or (URL::check(array('cmd'=>'edit')) and User::is_login())
                    or (URL::check(array('cmd'=>'')) and User::can_view(false,ANY_CATEGORY))
					or (URL::check(array('cmd'=>'view')) and User::can_view_detail(false,ANY_CATEGORY)))
					)
				or
				(URL::check(array('cmd'=>'add')) and User::can_add(false,ANY_CATEGORY))
				or !URL::check('cmd')
			)
			{
				switch(URL::get('cmd'))
				{
				case 'delete':
					require_once 'forms/detail.php';
					$this->add_form(new ManageUserForm());break;
				case 'edit':
				case 'add':
					require_once 'forms/edit.php';
					$this->add_form(new EditManageUserForm());break;
				case 'view':
					require_once 'forms/detail.php';
					$this->add_form(new ManageUserForm());break;
				default:
					require_once 'forms/list.php';
					$this->add_form(new ListManageUserForm());
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
			//URL::access_denied();
		}
	}
}
?>
