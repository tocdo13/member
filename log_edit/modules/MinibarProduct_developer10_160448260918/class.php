<?php 
class MinibarProduct extends Module
{
	function MinibarProduct($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			if(Url::check(array('cmd'=>'remove_all')))
			{
                $cond = ' and 1=1 ';
                if(Url::get('minibar_id'))
                    $cond .= ' and minibar_id=\''.Url::get('minibar_id').'\'';
                    
				DB::query( 'delete from minibar_product where portal_id=\''.PORTAL_ID.'\''.$cond );
				Url::redirect_current(array('minibar_id'));
			}
			else
			{
				require_once 'forms/edit.php';
				$this->add_form(new EditMinibarProductForm());
			}
		}
		else
		{
			Url::access_denied();
		}
	}
	
    //Khong dung nua, dung file cache
    function get_js_variables_data()
	{
		$sql = 'select
					hk_product.code as id,name_'.Portal::language().' as name
					,price ,portal_id
				from
					hk_product
					inner join product_category on product_category.id = hk_product.category_id
				where
					(type=\'GOODS\' or type=\'PRODUCT\') and hk_product.portal_id=\''.PORTAL_ID.'\'
					and '.IDStructure::child_cond(DB::fetch('select id, structure_id from product_category where code = \'MB\'','structure_id')).'
				';					
		$items = DB::fetch_all($sql);
		foreach($items as $key=>$record)
		{
			$items[$key]['name'] = String::string2js($record['name']);
			$items[$key]['price'] = System::display_number_report($record['price']);
			foreach($record as $name=>$value)
			{
				if(strpos($name,'date')!==false and strpos($value,'-')!==false)
				{
					$params = explode('-',$value);
					if(sizeof($params)==3)
					{
						$items[$key][$name] = $params[2].'/'.$params[1].'/'.$params[0];
					}
				}
				else
				if((strpos($name,'time')!==false or strpos($name,'time')!==false) and ctype_digit($value))
				{
					$items[$key][$name] = date('d/m/Y',$value);
				}
			}
		}
		$GLOBALS['js_variables']['products'] = $items;
        //System::debug($items);
        //System::debug(String::array2js($GLOBALS['js_variables']['products']));
        //System::debug(String::array2suggest($GLOBALS['js_variables']['products']));
	}
    
    //Khong dung nua, dung file cache
	function create_js_variables()
	{
		echo '<script>';
		echo 'products = '.String::array2js($GLOBALS['js_variables']['products']).';'; 
		echo '</script>';
	}
}
?>