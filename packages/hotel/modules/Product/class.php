<?php 
class Product extends Module
{
	function Product($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			if(URL::check(array('cmd','selected_ids')) and Url::get('cmd')=='delete_selected' and is_array(URL::get('selected_ids')) and sizeof(URL::get('selected_ids'))>0)
			{
				if(sizeof(URL::get('selected_ids'))>1)
				{
					require_once 'forms/delete_selected.php';
					$this->add_form(new DeleteSelectedProductForm());
				}
				else
				{
					$ids = URL::get('selected_ids');
					$_REQUEST['id'] = $ids[0];
					require_once 'forms/delete.php';
					$this->add_form(new DeleteProductForm());
				}
			}
			else
			if(URL::check(array('cmd','selected_ids')) and Url::get('cmd')=='edit_selected' and User::can_edit(false,ANY_CATEGORY))
			{
				require_once 'forms/edit.php';
				$this->add_form(new EditProductForm());
			}
			else
			if(
				(((URL::check(array('cmd'=>'delete')) and User::can_delete(false,ANY_CATEGORY)) 
					or ((URL::check(array('cmd'=>'edit'))and User::can_edit(false,ANY_CATEGORY)))
					and Url::check('id') and $product = DB::select('product','id=\''.$_REQUEST['id'].'\'')))
				or
				(URL::check(array('cmd'=>'add')) and User::can_add(false,ANY_CATEGORY))
				or
				(URL::check(array('cmd'=>'update')) and User::can_add(false,ANY_CATEGORY))
				or
				(URL::check(array('cmd'=>'import')) and User::can_add(false,ANY_CATEGORY))				
				or
				(URL::check(array('cmd'=>'delete_product')) and User::can_add(false,ANY_CATEGORY))
				or
				(URL::check(array('cmd'=>'syn')) and User::can_add(false,ANY_CATEGORY))
				or (!URL::get('cmd'))
			)
			{
				switch(URL::get('cmd'))
				{
				case 'delete':
					require_once 'forms/delete.php';
					$this->add_form(new DeleteProductForm());break;
				case 'edit':
					require_once 'forms/edit.php';
					$this->add_form(new EditProductForm());break;
				case 'syn':
					require_once 'forms/synchronize.php';
					$this->add_form(new SynProductForm());break;					
				case 'add':
					require_once 'forms/add.php';
					$this->add_form(new AddProductForm());break;
				case 'import':
					require_once 'forms/import.php';
					$this->add_form(new ImportProductForm());break;
				case 'update':
					$this->update();
					break;
				case 'delete_product':
					$this->delete_all_product();
					break;					
				default: 
					if(URL::check('id') and DB::exists('select id from product where id=\''.$_REQUEST['id'].'\''))
					{
						require_once 'forms/detail.php';
						$this->add_form(new ProductForm());
					}
					else
					{
						require_once 'forms/list.php';
						$this->add_form(new ListProductForm());
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
	function update()
	{
		if(User::can_edit(false,ANY_CATEGORY))
		{
			if(Url::get('id'))	
			{
				if(strpos(Url::get('id'),','))
				{
					$ids = split(',',Url::get('id'));
					foreach($ids as $id)
					{
						$this->insert_product($id);
					}
				}
				else
				{
					$this->insert_product(Url::get('id'));
				}
				exit();
			}
		}
	}
	function insert_product($id)
	{
		if($row = DB::fetch('select product.id as code,product.* from product where id=\''.$id.'\''))
		{
			unset($row['id']);
			if($table = Url::get('func') and !DB::exists('select id from '.$table.' where code=\''.$id.'\' and portal_id=\''.PORTAL_ID.'\''))
			{
				DB::insert($table,$row+array('portal_id'=>PORTAL_ID));	
			}
			else
			{
				echo Portal::language('data_not_insert');	
			}
		}
	}
	function delete_all_product()
	{
		if(User::can_edit(false,ANY_CATEGORY))
		{
			if(Url::get('id'))	
			{
				if(strpos(Url::get('id'),','))
				{
					$ids = split(',',Url::get('id'));
					foreach($ids as $key=>$id)
					{
						$this->delete_product($id);
					}
				}
				else
				{
					$this->delete_product(Url::get('id'));
				}
				exit();
			}
		}
	}	
	function delete_product($id)
	{
		if($table = Url::get('func') and DB::exists('select id from '.$table.' where code=\''.$id.'\' and portal_id=\''.PORTAL_ID.'\''))
		{
			DB::delete($table,'code=\''.$id.'\' and portal_id=\''.PORTAL_ID.'\'');	
		}
	}
}
?>