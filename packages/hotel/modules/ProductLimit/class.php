<?php 
class ProductLimit extends Module
{
	function ProductLimit($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			if(URL::check(array('cmd'=>'remove_all')) and User::can_delete(false,ANY_CATEGORY))
			{
				DB::query('delete from product_material'.(URL::get('product_id')?' where product_id=\''.URL::get('product_id').'\'':''));
				URL::redirect_current(array('product_id'));
			}
			else
			{
                switch(Url::get('cmd'))
                {
                    case 'add':
                    case 'edit':
        				if(User::can_add(false,ANY_CATEGORY))
                        {
        					require_once 'forms/edit.php';
        					$this->add_form(new EditProductLimitForm());
        				}
                        else
        					Url::access_denied();
        				break;
                    
                    default:
        				if(User::can_view(false,ANY_CATEGORY))
                        {
        					require_once 'forms/list.php';
        					$this->add_form(new ListProductLimitForm());
        				}
                        else
        					Url::access_denied();
        				break;
                }
			}
		}
		else
		{
			URL::access_denied();
		}
	}
	function get_js_variables_data()
	{
		DB::query('select product.id,product.name_'.Portal::language().' as name,product_price_list.price,unit.name_'.Portal::language().' as unit_id from product INNER JOIN product_price_list ON product_price_list.product_id = product.id
						INNER JOIN unit ON  product_price_list.unit_id = unit.id where product.type=\'MATERIAL\'');
		$items = DB::fetch_all();
		foreach($items as $key=>$record)
		{
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
	}
	function create_js_variables()
	{
		echo '<script>';
		echo 'products = '.String::array2js($GLOBALS['js_variables']['products']).';'; 
		echo '</script>';
	}
}
?>