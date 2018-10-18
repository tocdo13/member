<?php 
// *****************************************************************
// Writer: Khoand
// Description: Quan ly thong tin nhan vien thong qua may cham cong
// Create Date:06/07/2010
// *****************************************************************
class Employee extends Module
{
	function Employee($row)
	{
		require_once 'packages/core/includes/system/access_database.php';
		//if(User::can_view(false,ANY_CATEGORY))
		{
			if(URL::get('cmd')=='delete' and is_array(URL::get('selected_ids')) and sizeof(URL::get('selected_ids'))>0 and User::can_delete(false,ANY_CATEGORY))
			{
				Module::Module($row);
				if(sizeof(URL::get('selected_ids'))>1)
				{
					require_once 'forms/list.php';
					$this->add_form(new ListEmployeeForm());
				}
				else
				{
					$ids = URL::get('selected_ids');
					$_REQUEST['id'] = $ids[0];
					require_once 'forms/detail.php';
					$this->add_form(new EmployeeForm());
				}
			}
			else
			if(
				(((URL::check(array('cmd'=>'delete'))and User::can_delete(false,ANY_CATEGORY))
					or (URL::check(array('cmd'=>'edit')) and User::is_login())
					or (URL::check(array('cmd'=>'view')) and User::can_view_detail(false,ANY_CATEGORY)))
					and Url::get('id') and $adb->exists('SELECT USERID FROM USERINFO WHERE USERID = '.$_REQUEST['id'].''))
				or
				(URL::check(array('cmd'=>'add')) and User::can_add(false,ANY_CATEGORY))
				or !URL::check('cmd')
			)
			{
				Module::Module($row);
				switch(URL::get('cmd'))
				{
				case 'delete':
					require_once 'forms/detail.php';
					$this->add_form(new EmployeeForm());break;
				case 'edit':
				case 'add':
					require_once 'forms/edit.php';
					$this->add_form(new EditEmployeeForm());break;
				case 'view':
					require_once 'forms/detail.php';
					$this->add_form(new EmployeeForm());break;
				default: 
					require_once 'forms/list.php';
					$this->add_form(new ListEmployeeForm());
					break;
				}
			}
			else
			{
				Url::redirect_current();
			}
		}
		/*else
		{
			URL::access_denied();
		}*/
	}
}
?>
