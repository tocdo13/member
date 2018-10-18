<?php 
class Traveller extends Module
{
	function Traveller($row)
	{
		Module::Module($row);
		require_once('db.php');
		if(User::can_view(false,ANY_CATEGORY))
		{
			if(URL::check(array('delete_selected','selected_ids')) and is_array(URL::get('selected_ids')) and sizeof(URL::get('selected_ids'))>0 and User::can_delete(false,ANY_CATEGORY))
			{
				if(sizeof(URL::get('selected_ids'))>1)
				{
					require_once 'forms/delete_selected.php';
					$this->add_form(new DeleteSelectedTravellerForm());
				}
				else
				{
					$ids = URL::get('selected_ids');
					$_REQUEST['id'] = $ids[0];
					require_once 'forms/delete.php';
					$this->add_form(new DeleteTravellerForm());
				}
			}
			else
			if(
				(((URL::check(array('cmd'=>'delete'))and User::can_delete(false,ANY_CATEGORY))
					or (URL::check(array('cmd'=>'edit')) and User::can_edit(false,ANY_CATEGORY))
					and Url::check('id') and DB::exists_id('traveller',$_REQUEST['id'])))
				or
				(URL::check(array('cmd'=>'add')) and User::can_add(false,ANY_CATEGORY))
				or !URL::check('cmd')
			)
			{
				
				switch(URL::get('cmd'))
				{
				case 'delete':
					require_once 'forms/delete.php';
					$this->add_form(new DeleteTravellerForm());break;
				case 'edit':
					require_once 'forms/edit.php';
					$this->add_form(new EditTravellerForm());break;
				case 'add':
					require_once 'forms/add.php';
					$this->add_form(new AddTravellerForm());break;
				default: 
					if(URL::check('id') and DB::exists_id('traveller',$_REQUEST['id']))
					{
						require_once 'forms/detail.php';
						$this->add_form(new TravellerForm());
					}
					else
					{
						require_once 'forms/list.php';
						$this->add_form(new ListTravellerForm());
					}
					break;
				}
			}
            elseif((URL::get('cmd')) AND (URL::get('cmd')=='list_member')){
                require_once 'forms/list_member.php';
			     $this->add_form(new ListMemberTravellerForm());
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