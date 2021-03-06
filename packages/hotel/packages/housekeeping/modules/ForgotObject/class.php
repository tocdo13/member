<?php 
class ForgotObject extends Module
{
	function ForgotObject($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			if(URL::check('selected_ids') and is_array(URL::get('selected_ids')) and sizeof(URL::get('selected_ids'))>0 and User::can_delete(false,ANY_CATEGORY))
			{
				
				if(sizeof(URL::get('selected_ids'))>1)
				{
					require_once 'forms/delete_selected.php';
					$this->add_form(new DeleteSelectedForgotObjectForm());
				}
				else
				{
					$ids = URL::get('selected_ids');
					$_REQUEST['id'] = $ids[0];
					require_once 'forms/delete.php';
					$this->add_form(new DeleteForgotObjectForm());
				}
			}
			else
			if(
				(((URL::check(array('cmd'=>'delete'))and User::can_delete(false,ANY_CATEGORY))
					or (URL::check(array('cmd'=>'edit')) and User::can_edit(false,ANY_CATEGORY))
					and Url::check('id') and DB::exists_id('forgot_object',$_REQUEST['id'])))
				or
				(URL::check(array('cmd'=>'add')) and User::can_add(false,ANY_CATEGORY))
				or !URL::check('cmd')
			)
			{
				
				switch(URL::get('cmd'))
				{
				case 'delete':
					require_once 'forms/delete.php';
					$this->add_form(new DeleteForgotObjectForm());break;
				case 'edit':
					require_once 'forms/edit.php';
					$this->add_form(new EditForgotObjectForm());break;
				case 'add':
					require_once 'forms/add.php';
					$this->add_form(new AddForgotObjectForm());break;
				default: 
					if(URL::check('id') and DB::exists_id('forgot_object',$_REQUEST['id']))
					{
						require_once 'forms/detail.php';
						$this->add_form(new ForgotObjectForm());
					}
					else
					{
						require_once 'forms/list.php';
						$this->add_form(new ListForgotObjectForm());
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