<?php 
class ProductCategory extends Module
{
	function ProductCategory($row)
	{
		Module::Module($row);
		require_once 'db.php';
		$this->redirect_parameters = array('type',);
		switch(Url::get('page'))
		{
			case 'warehouse_product_category':
				if(!defined('PRODUCT_CATEGORY'))
				{
					define('PRODUCT','wh_product');
					define('PRODUCT_CATEGORY','wh_product_category');
				}
			case 'housekeeping_product_category':
				if(!defined('PRODUCT_CATEGORY'))
				{
					define('PRODUCT','hk_product');
					define('PRODUCT_CATEGORY','hk_product_category');
				}
			case 'restaurant_product_category':
				if(!defined('PRODUCT_CATEGORY'))
				{
					define('PRODUCT','product');
					define('PRODUCT_CATEGORY','product_category');
				}
			case 'karaoke_product_category':
				if(!defined('PRODUCT_CATEGORY'))
				{
					define('PRODUCT','ka_product');
					define('PRODUCT_CATEGORY','ka_product_category');
				}
			case 'shop_product_category':
				if(!defined('PRODUCT') and !defined('PRODUCT_CATEGORY'))
				{				
					define('PRODUCT','shop_product');
					define('PRODUCT_CATEGORY','shop_product_category');
				}				
			default:
				if(!defined('PRODUCT_CATEGORY'))
				{
					define('PRODUCT','product');
					define('PRODUCT_CATEGORY','product_category');
				}				
		}		
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
			$this->add_form(new EditProductCategoryForm());
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
				$this->add_form(new ListProductCategoryForm());
			}
			else
			{
				$ids = URL::get('selected_ids');
				$_REQUEST['id'] = $ids[0];
				require_once 'forms/detail.php';
				$this->add_form(new ProductCategoryForm());
			}
		}
		else
		if(User::can_delete(false,ANY_CATEGORY) and Url::check('id') and DB::exists_id(PRODUCT_CATEGORY,$_REQUEST['id']))
		{
			require_once 'forms/detail.php';
			$this->add_form(new ProductCategoryForm());
		}
		else
		{
			Url::redirect_current();
		}
	}
	function edit_cmd()
	{
		if(Url::get('id') and $category=DB::fetch('select id,structure_id from '.PRODUCT_CATEGORY.' where id='.intval(Url::get('id'))))
		{//and User::can_edit(false,$category['structure_id'])
			require_once 'forms/edit.php';
			$this->add_form(new EditProductCategoryForm());
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
			$this->add_form(new ListProductCategoryForm());
		}	
		else
		{
			Url::access_denied();
		}
	}
	function view_cmd()
	{
		if(User::can_view_detail(false,ANY_CATEGORY) and Url::check('id') and DB::exists_id('product_category',$_REQUEST['id']))
		{
			require_once 'forms/detail.php';
			$this->add_form(new ProductCategoryForm());
		}
		else
		{
			Url::redirect_current();
		}
	}
	function move_cmd()
	{
		if(User::can_edit(false,ANY_CATEGORY)and Url::check('id')and $category=DB::exists_id('product_category',$_REQUEST['id']))
		{
			if($category['structure_id']!=ID_ROOT)
			{
				require_once 'packages/core/includes/system/si_database.php';
				si_move_position('product_category');
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