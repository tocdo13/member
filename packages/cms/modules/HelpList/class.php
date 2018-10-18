<?php 
class HelpList extends Module
{
	function HelpList($row)
	{
		Module::Module($row);
		require_once 'db.php';
		$this->redirect_parameters = array('type',);
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
	function export_cache()
	{
		if(User::can_view(false,ANY_CATEGORY))
		{
			//$this->export();
			Url::redirect_current();
		}
	}
	// tao cache file voi help_list va zone
	function add_cmd()
	{
		if(User::can_add(false,ANY_CATEGORY))
		{
			require_once 'forms/edit.php';
			$this->add_form(new EditHelpListForm());
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
			if(sizeof(URL::get('selected_ids'))>1)
			{
				require_once 'forms/list.php';
				$this->add_form(new ListHelpListForm());
			}
			else
			{
				$ids = URL::get('selected_ids');
				$_REQUEST['id'] = $ids[0];
				require_once 'forms/detail.php';
				$this->add_form(new HelpListForm());
			}
		}
		else
		if(User::can_delete(false,ANY_CATEGORY) and Url::check('id') and DB::exists_id('help_list',$_REQUEST['id']))
		{
			require_once 'forms/detail.php';
			$this->add_form(new HelpListForm());
		}
		else
		{
			Url::redirect_current();
		}
	}
	function edit_cmd()
	{
		if(Url::get('id') and $help_list=DB::fetch('select id,structure_id from help_list where id='.intval(Url::get('id'))) and User::can_edit(false,$help_list['structure_id']))
		{
			require_once 'forms/edit.php';
			$this->add_form(new EditHelpListForm());
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
			$this->add_form(new ListHelpListForm());
		}	
		else
		{
			Url::access_denied();
		}
	}
	function view_cmd()
	{
		if(User::can_view_detail(false,ANY_CATEGORY) and Url::check('id') and DB::exists_id('help_list',$_REQUEST['id']))
		{
			require_once 'forms/detail.php';
			$this->add_form(new HelpListForm());
		}
		else
		{
			Url::redirect_current();
		}
	}
	function move_cmd()
	{
		if(User::can_edit(false,ANY_CATEGORY)and Url::check('id')and $help_list=DB::exists_id('help_list',$_REQUEST['id']))
		{
			if($help_list['structure_id']!=ID_ROOT)
			{
				require_once 'packages/core/includes/system/si_database.php';
				si_move_position('help_list',' and portal_id=\''.PORTAL_ID.'\'');
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