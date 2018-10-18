<?php 
class RoomProduct extends Module
{
	function RoomProduct($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			if(URL::check(array('cmd'=>'remove_all')))
			{
				DB::query('delete from room_product'.(URL::get('room_id')?' where room_id=\''.URL::get('room_id').'\'':''));
				URL::redirect_current(array('room_id'));
			}
			else
			{
				require_once 'forms/edit.php';
				$this->add_form(new EditRoomProductForm());
			}
		}
		else
		{
			URL::access_denied();
		}
	}
	function get_js_variables_data()
	{
		$sql = 'select
					hk_product.id,name_'.Portal::language().' as name
					,price 
				from
					hk_product
					inner join hk_product_category on hk_product_category.id = hk_product.category_id
				where
					(type=\'GOODS\' or type=\'PRODUCT\')
					and '.IDStructure::child_cond(DB::fetch('select id, structure_id from hk_product_category where code = \'ROOM\'','structure_id')).'
				';					
		$items = DB::fetch_all($sql);
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