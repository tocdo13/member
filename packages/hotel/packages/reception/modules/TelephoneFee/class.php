<?php 
class TelephoneFee extends Module
{
	function TelephoneFee($row)
	{
		Module::Module($row);

		if(User::can_view(false,ANY_CATEGORY))
		{
			if(URL::check(array('delete_selected','selected_ids')) and is_array(URL::get('selected_ids')) and sizeof(URL::get('selected_ids'))>0 and User::can_delete(false,ANY_CATEGORY))
			{
				
				require_once 'forms/list.php';
				$this->add_form(new ListTelephoneFeeForm());
				if(sizeof(URL::get('selected_ids'))>1)
				{
					require_once 'forms/delete_selected.php';
					$this->add_form(new DeleteSelectedTelephoneFeeForm());
				}
				else
				{
					$ids = URL::get('selected_ids');
					$_REQUEST['id'] = $ids[0];
					require_once 'forms/delete.php';
					$this->add_form(new DeleteTelephoneFeeForm());
				}
			}
			else
			if(URL::check(array('edit_selected','selected_ids')) and User::can_edit(false,ANY_CATEGORY ))
			{
				
				require_once 'forms/list.php';
				$this->add_form(new ListTelephoneFeeForm());
				require_once 'forms/edit.php';
				$this->add_form(new EditTelephoneFeeForm());
			}
			else
			if(
				(((URL::check(array('cmd'=>'delete'))and User::can_delete(false,ANY_CATEGORY))
					and Url::check('id') and DB::exists_id('telephone_fee',$_REQUEST['id'])))
				or
				(URL::check(array('cmd'=>'add')) and User::can_add(false,ANY_CATEGORY))
				or !URL::check('cmd')
			)
			{
				require_once 'forms/list.php';
				$this->add_form(new ListTelephoneFeeForm());				
				switch(URL::get('cmd'))
				{
				case 'delete':
					require_once 'forms/delete.php';
					$this->add_form(new DeleteTelephoneFeeForm());break;
				case 'edit':
					require_once 'forms/edit.php';
					$this->add_form(new EditTelephoneFeeForm());break;
				case 'add':
					require_once 'forms/add.php';
					$this->add_form(new AddTelephoneFeeForm());break;
				default: 
					if(URL::check('id') and DB::exists_id('telephone_fee',$_REQUEST['id']))
					{
						require_once 'forms/detail.php';
						$this->add_form(new TelephoneFeeForm());
					}
					else
					{
						require_once 'forms/add.php';
						$this->add_form(new AddTelephoneFeeForm());
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