<?php 
class RestaurantProduct extends Module
{
	function RestaurantProduct($row)
	{
		Module::Module($row);
		require_once 'db.php';
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
		if(User::can_view(false,ANY_CATEGORY))
		{
			if(URL::check(array('cmd','selected_ids')) and Url::get('cmd')=='delete_selected' and is_array(URL::get('selected_ids')) and sizeof(URL::get('selected_ids'))>0)
			{
				if(sizeof(URL::get('selected_ids'))>1)
				{
					require_once 'forms/delete_selected.php';
					$this->add_form(new DeleteSelectedRestaurantProductForm());
				}
				else
				{
					$ids = URL::get('selected_ids');
					$_REQUEST['id'] = $ids[0];
					require_once 'forms/delete.php';
					$this->add_form(new DeleteRestaurantProductForm());
				}
			}
			else
			if(URL::check(array('cmd','selected_ids')) and Url::get('cmd')=='edit_selected' and User::can_edit(false,ANY_CATEGORY))
			{
				require_once 'forms/edit.php';
				$this->add_form(new EditRestaurantProductForm());
			}
			else
			if(
				((((URL::check(array('cmd'=>'delete')) and User::can_delete(false,ANY_CATEGORY) ) 
					or ((URL::check(array('cmd'=>'edit')) and User::can_edit(false,ANY_CATEGORY)))
					and Url::check('id') and $product = DB::select('res_product','id=\''.$_REQUEST['id'].'\''))) or (URL::check(array('cmd'=>'export_product')) and User::can_edit(false,ANY_CATEGORY)))

				or !URL::check('cmd')
			)
			{
				switch(URL::get('cmd'))
				{
				case 'delete':
					require_once 'forms/delete.php';
					$this->add_form(new DeleteRestaurantProductForm());break;
				case 'edit':
					require_once 'forms/edit.php';
					$this->add_form(new EditRestaurantProductForm());break;
				default: 
					if(URL::check('id') and DB::exists('select id from res_product where id=\''.$_REQUEST['id'].'\''))
					{
						require_once 'forms/detail.php';
						$this->add_form(new RestaurantProductForm());
					}
					else
					{
						if(Url::get('cmd')=='export_product' and User::can_edit(false,ANY_CATEGORY)){
							$this->export_product();
						}
						require_once 'forms/list.php';
						$this->add_form(new ListRestaurantProductForm());
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
	function export_product(){
		$dir_string = 'cache/data/'.str_replace('#','',PORTAL_ID).'';
		if(!is_dir($dir_string)){
			mkdir($dir_string);	
		}
		$sql = 'select 
					res_product.id
					,res_product.code
					,res_product.price
					,res_product.status
					,res_product.type 
					,res_product.name_'.Portal::language().' as name 
					,product_category.name as category_id 
					,unit.name_'.Portal::language().' as unit_name
					,unit.id as unit_id
					,row_number() over (order by upper(product_category.name),upper(res_product.code)) as rownumber
				from 
					res_product
					left outer join product_category on res_product.category_id = product_category.id
					left outer join unit on res_product.unit_id = unit.id
				where 
					1>0 and res_product.portal_id=\''.PORTAL_ID.'\'';	
		$products = DB::fetch_all($sql);
		$str = " var product_array=";
		$str.= String::array2js($products);
		$str.= '';
		$f = fopen($dir_string.'/product_'.str_replace('#','',PORTAL_ID).'.js','w+');
		fwrite($f,$str);
		fclose($f);
	}
}
?>