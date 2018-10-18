<?php 
class RestaurantProductCategory extends Module
{
	function RestaurantProductCategory($row)
	{
		Module::Module($row);
		if(Url::get('bar_id'))
		{
			Session::set('bar_id',intval(Url::get('bar_id')));
		}
		else if(!Session::is_set('bar_id'))
		{
			require_once 'packages/hotel/includes/php/hotel.php';
			$bar = Hotel::get_new_bar();
			if($bar)
			{
				Session::set('bar_id',$bar['id']);
			}
			else
			{
				Session::set('bar_id','');
			}
		}
		$_REQUEST['bar_id'] = Session::get('bar_id');		
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

	function add_cmd()
	{
		if(User::can_add(false,ANY_CATEGORY))
		{
			require_once 'forms/edit.php';
			$this->add_form(new EditRestaurantProductCategoryForm());
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
				$this->add_form(new ListRestaurantProductCategoryForm());
			}
			else
			{
				$ids = URL::get('selected_ids');
				$_REQUEST['id'] = $ids[0];
				require_once 'forms/detail.php';
				$this->add_form(new RestaurantProductCategoryForm());
			}
		}
		else
		if(User::can_delete(false,ANY_CATEGORY) and Url::check('id') and DB::exists_id('res_product_category',Url::get('id')))
		{
			require_once 'forms/detail.php';
			$this->add_form(new RestaurantProductCategoryForm());
		}
		else
		{
			Url::redirect_current();
		}
	}
	function edit_cmd()
	{
		if(Url::get('id') and $category=DB::fetch('select id,structure_id from res_product_category where id='.intval(Url::get('id'))) and User::can_edit(false,ANY_CATEGORY))
		{
			require_once 'forms/edit.php';
			$this->add_form(new EditRestaurantProductCategoryForm());
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
			$this->add_form(new ListRestaurantProductCategoryForm());
		}	
		else
		{
			Url::access_denied();
		}
	}
	function view_cmd()
	{
		if(User::can_view_detail(false,ANY_CATEGORY) and Url::check('id') and DB::exists_id('res_product_category',$_REQUEST['id']))
		{
			require_once 'forms/detail.php';
			$this->add_form(new RestaurantProductCategoryForm());
		}
		else
		{
			Url::redirect_current();
		}
	}
	function move_cmd()
	{
		if(User::can_edit(false,ANY_CATEGORY)and Url::check('id')and $category=DB::exists_id('res_product_category',$_REQUEST['id']))
		{
			if($category['structure_id']!=ID_ROOT)
			{
				require_once 'packages/core/includes/system/si_database.php';
				si_move_position('res_product_category','');
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