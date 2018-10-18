<?php 
class TelephoneNumber extends Module
{
	function TelephoneNumber($row)
	{
		Module::Module($row);

		if(User::can_view(false,ANY_CATEGORY))
		{
			if(URL::check(array('delete_selected','selected_ids')) and is_array(URL::get('selected_ids')) and sizeof(URL::get('selected_ids'))>0 and User::can_delete(false,ANY_CATEGORY))
			{
				require_once 'forms/list.php';
				$this->add_form(new ListTelephoneNumberForm());
				if(sizeof(URL::get('selected_ids'))>1)
				{
					require_once 'forms/delete_selected.php';
					$this->add_form(new DeleteSelectedTelephoneNumberForm());
				}
				else
				{
					$ids = URL::get('selected_ids');
					$_REQUEST['id'] = $ids[0];
					require_once 'forms/delete.php';
					$this->add_form(new DeleteTelephoneNumberForm());
				}
			}
			else
			if(URL::check(array('edit_selected','selected_ids')) and User::can_edit(false,ANY_CATEGORY))
			{
				require_once 'forms/list.php';
				$this->add_form(new ListTelephoneNumberForm());
				require_once 'forms/edit.php';
				$this->add_form(new EditTelephoneNumberForm());
			}
			else
			if(
				(((URL::check(array('cmd'=>'delete')) and User::can_delete(false,ANY_CATEGORY))
					and Url::check('id') and DB::exists_id('telephone_number',$_REQUEST['id'])))
				or
				(URL::check(array('cmd'=>'add')) and User::can_add(false,ANY_CATEGORY))
				or !URL::check('cmd')
			)
			{
				require_once 'forms/list.php';
				$this->add_form(new ListTelephoneNumberForm());
				
				switch(URL::get('cmd'))
				{
				case 'delete':
					require_once 'forms/delete.php';
					$this->add_form(new DeleteTelephoneNumberForm());break;
				case 'edit':
					require_once 'forms/edit.php';
					$this->add_form(new EditTelephoneNumberForm());break;
				case 'add':
					require_once 'forms/add.php';
					$this->add_form(new AddTelephoneNumberForm());break;
				default: 
					if(URL::check('id') and DB::exists_id('telephone_number',$_REQUEST['id']))
					{
						require_once 'forms/detail.php';
						$this->add_form(new TelephoneNumberForm());
					}
					else
					{
						require_once 'forms/add.php';
						$this->add_form(new AddTelephoneNumberForm());
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