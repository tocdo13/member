<?php 
class HousekeepingEquipment extends Module
{
	function HousekeepingEquipment($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			if(URL::check(array('delete_selected','selected_ids')) and is_array(URL::get('selected_ids')) and sizeof(URL::get('selected_ids'))>0 and User::can_delete(false,ANY_CATEGORY))
			{
				if(sizeof(URL::get('selected_ids'))>1)
				{
					require_once 'forms/delete_selected.php';
					$this->add_form(new DeleteSelectedHousekeepingEquipmentForm());
				}else{
					$ids = URL::get('selected_ids');
					$ids = explode(',',$ids[0]);
					if(isset($ids[1]))
					{
						Url::redirect_current(array('cmd'=>'delete','product_id'=>$ids[0],'room_id'=>$ids[1]));
					}else{
						Url::redirect_current();
					}
				}
			}
			else
			if(URL::check(array('cmd'=>'delete_damaged','id'))and User::can_delete(false,ANY_CATEGORY))
			{
                if($row = DB::select_id('housekeeping_equipment_damaged',URL::get('id')))
                {
                    DB::query('
        				update housekeeping_equipment 
        				set damaged_quantity = damaged_quantity -'.$row['quantity'].'
        				where 
        					room_id=\''.$row['room_id'].'\' 
                            and product_id=\''.$row['product_id'].'\'
        			     ');
                    DB::delete('housekeeping_equipment_damaged','id=\''.URL::get('id').'\'');
                }
				Url::redirect_current(array('cmd'=>'list_damaged'));
			}
			else
			if(
                   ( URL::check(array('cmd'=>'delete'))and User::can_delete(false,ANY_CATEGORY) )
				or ( URL::check(array('cmd'=>'damaged')) and User::can_edit(false,ANY_CATEGORY) )
				or ( URL::check(array('cmd'=>'list_damaged')) and User::can_edit(false,ANY_CATEGORY) )
                or ( URL::check(array('cmd'=>'add')) and User::can_add(false,ANY_CATEGORY) )
                /*or (URL::check(array('cmd'=>'edit')) and User::can_edit(false,ANY_CATEGORY))*/
		        or !URL::check('cmd')
			)
			{
				switch(URL::get('cmd'))
				{
    				case 'delete':
    					require_once 'forms/delete.php';
    					$this->add_form(new DeleteHousekeepingEquipmentForm());break;
    				/*case 'edit':
    					require_once 'forms/edit.php';
    					$this->add_form(new EditHousekeepingEquipmentForm());break;*/
    				case 'add':
    					require_once 'forms/add.php';
    					$this->add_form(new AddHousekeepingEquipmentForm());break;
    				case 'damaged':
    					require_once 'forms/damaged.php';
    					$this->add_form(new HousekeepingEquipmentDamagedForm());break;
    				case 'list_damaged':
    					require_once 'forms/list_damaged.php';
    					$this->add_form(new ListHousekeepingEquipmentDamagedForm());break;
    				default: 
    					if(URL::check('id') and DB::exists_id('housekeeping_equipment',$_REQUEST['id']))
    					{
    						require_once 'forms/detail.php';
    						$this->add_form(new HousekeepingEquipmentForm());
    					}
    					else
    					{
    						require_once 'forms/list.php';
    						$this->add_form(new ListHousekeepingEquipmentForm());
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
	//lay cac product thuoc housekeeping
	function get_js_variables_data()
	{
        $sql = '
			select 
				product_price_list.product_id as id,
                product.name_'.Portal::language().' as name,
                5 as in_stock,
                unit.name_'.Portal::language().' as unit_name,
                product_price_list.price
                
			from 
				product_price_list
                inner join product on product_price_list.product_id = product.id
				inner join unit on unit.id = product.unit_id
			where 
				product.type=\'EQUIPMENT\' 
                and product_price_list.portal_id=\''.PORTAL_ID.'\'
                and product_price_list.department_code =\'HK\'
				and product.status = \'avaiable\'
		';
		$items = DB::fetch_all($sql);
		$GLOBALS['js_variables']['product'] = $items; 
	}
	function create_js_variables()
	{
		echo '<script>';
		echo 'product = '.String::array2js($GLOBALS['js_variables']['product']).';'; 
		echo '</script>';
	}
}
?>