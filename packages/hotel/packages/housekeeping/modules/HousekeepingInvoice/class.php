<?php 
class HousekeepingInvoice extends Module
{
	function HousekeepingInvoice($row)
	{
		Module::Module($row);

		if(User::can_view(false,ANY_CATEGORY))
		{
			if(URL::check('selected_ids') and is_array(URL::get('selected_ids')) and sizeof(URL::get('selected_ids'))>0 and User::can_delete(false,ANY_CATEGORY))
			{
				
				if(sizeof(URL::get('selected_ids'))>1)
				{
					require_once 'forms/delete_selected.php';
					$this->add_form(new DeleteSelectedHousekeepingInvoiceForm());
				}
				else
				{
					$ids = URL::get('selected_ids');
					$_REQUEST['id'] = $ids[0];
					require_once 'forms/delete.php';
					$this->add_form(new DeleteHousekeepingInvoiceForm());
				}
			}
			else
			if(
				(((URL::check(array('cmd'=>'delete'))and User::can_delete(false,ANY_CATEGORY))
					or (URL::check(array('cmd'=>'edit')) and User::can_edit(false,ANY_CATEGORY))
					and Url::check('id') and DB::exists_id('housekeeping_invoice',$_REQUEST['id'])))
				or (URL::check(array('cmd'=>'add')) and User::can_add(false,ANY_CATEGORY)) 
				or !URL::check('cmd')
			)
			{
				
				switch(URL::get('cmd'))
				{
				case 'delete':
					require_once 'forms/delete.php';
					$this->add_form(new DeleteHousekeepingInvoiceForm());break;
				case 'edit':
					require_once 'forms/edit.php';
					$this->add_form(new EditHousekeepingInvoiceForm());break;
				case 'add':
					require_once 'forms/add.php';
					$this->add_form(new AddHousekeepingInvoiceForm());break;
				default: 
					if(URL::check('id') and DB::exists_id('housekeeping_invoice',$_REQUEST['id']))
					{
						require_once 'forms/detail.php';
						$this->add_form(new HousekeepingInvoiceForm());
					}
					else
					{
						require_once 'forms/list.php';
						$this->add_form(new ListHousekeepingInvoiceForm());
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
	function get_js_variables_data()
	{
		DB::query('select id, price, unit_id,name_'.Portal::language().' as name from hk_product where type=\'SERVICE\' and category_id<>\''.DB::fetch('select id from hk_product_category where code=\'GL\'','id').'\'');
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
		$GLOBALS['js_variables']['services'] = $items; 
	}
	function create_js_variables()
	{
		echo '<script>';
		echo 'services = '.String::array2js($GLOBALS['js_variables']['services']).';'; 
		echo '</script>';
	}
}
?>