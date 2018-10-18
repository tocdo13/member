<?php
class EditRestaurantProductForm extends Form
{
	function EditRestaurantProductForm()
	{
		Form::Form('EditRestaurantProductForm');
		$this->add('res_product.id',new TextType(false,'invalid_code',0,20)); 
		if(URL::get('type')!='MATERIAL' and URL::get('type')!='EQUIPMENT' and URL::get('type')!='TOOL')
		{
			$this->add('res_product.price',new FloatType(true,'invalid_price_usd','0','100000000000'));
		}
		$this->add('res_product.type',new SelectType(false,'invalid_type',array('GOODS'=>'GOODS','PRODUCT'=>'PRODUCT','MATERIAL'=>'MATERIAL','EQUIPMENT'=>'EQUIPMENT','SERVICE'=>'SERVICE','TOOL'=>'TOOL'))); 
		$this->add('res_product.category_id',new IDType(true,'invalid_category_id','product_category')); 
		$this->add('res_product.unit_id',new IDType(true,'invalid_unit_id','unit')); 

		$languages = DB::select_all('language');
		foreach($languages as $language)
		{
			$this->add('res_product.name_'.$language['id'],new TextType(true,'invalid_name',0,2000)); 
		}
		$this->link_css('skins/default/restaurant.css');		
	}
	function on_submit()
	{
		if($this->check() and !is_array(URL::get('selected_ids')))
		{
			if(URL::get('selected_ids'))
			{
				if(strpos(URL::get('selected_ids'),','))
				{
					$old_items = DB::select_all('res_product','id in (\''.str_replace(',','\',\'',URL::get('selected_ids')).'\')');
				}
				else
				{
					$old_item = DB::select('res_product','id=\''.URL::get('selected_ids').'\'');
					$old_items = array($old_item['id']=>$old_item);
				}
			}
			else
			{
				$old_items = array();
			}
			if(isset($_REQUEST['mi_product']))
			{
				foreach($_REQUEST['mi_product'] as $key=>$record)
				{
					if(isset($record['price']))
					{
						$record['price'] = System::calculate_number($record['price']);
					}
					$record['portal_id'] = PORTAL_ID;
					if($record['id'] and isset($old_items[$record['id']]) and $row = DB::select('res_product','id=\''.$record['id'].'\''))
					{
						DB::update('res_product',$record,'id=\''.$record['id'].'\'');
						if(isset($old_items[$record['id']]))
						{
							$old_items[$record['id']]['not_delete'] = true;
						}
					}
					else
					{
						$ids[] = DB::insert('res_product',$record);  
					}
				}
				if (isset($ids) and sizeof($ids))
				{
					$_REQUEST['selected_ids'].=','.join(',',$ids);
				}
			}
			foreach($old_items as $item)
			{
				if(!isset($item['not_delete']))
				{
					DB::delete('res_product','id=\''.$item['id'].'\'');
				}
			}
			Url::redirect_current(array('type','just_edited_ids'=>URL::get('selected_ids')));
		}
	}	
	function draw()
	{
		require_once 'packages/hotel/includes/php/hotel.php';	
		$languages = DB::select_all('language');
		if(URL::get('selected_ids'))
		{
			if(is_string(URL::get('selected_ids')))
			{
				$selected_ids = explode(',',URL::get('selected_ids'));
			}
			else
			{
				$selected_ids = URL::get('selected_ids');
			}
			require_once 'list.php';
			if(sizeof($selected_ids)>0)
			{
				if(!isset($_REQUEST['mi_product']))
				{
					DB::query('
						select 
							*
						from 
							res_product
						where
							'.((sizeof($selected_ids)>1)?'id in (\''.join($selected_ids,'\',\'').'\')':'id=\''.reset($selected_ids).'\'').'
					');
					$mi_product = DB::fetch_all();
					foreach($mi_product as $key=>$value)
					{
						if($value['type']!='EQUIPMENT' and $value['type']!='MATERIAL')
						{
							$mi_product[$key]['price'] = System::display_number_report($value['price']); 
						}
						if(ListRestaurantProductForm::check_product_delete($key))
						{
							$mi_product[$key]['can_delete'] = false;
						}
						else
						{
							$mi_product[$key]['can_delete'] = true;
						}
					}
					$_REQUEST['mi_product'] = $mi_product;
					
					$_REQUEST['selected_ids'] = join($selected_ids,',');
				}
				$row = reset($_REQUEST['mi_product']);
			}
		}
		$db_items = RestaurantProductDB::get_categories('1>0');
		$category_id_options = '';
		foreach($db_items as $item)
		{
			
			$category_id_options .= '<option value="'.$item['id'].'">'.(@str_repeat('--',IDStructure::level($item['structure_id']))).$item['name'].'</option>';
		}
		
		DB::query('select id, name_'.Portal::language().' as name from unit order by name');
		$db_items = DB::fetch_all();
		$unit_id_options = '';
		foreach($db_items as $item)
		{
			$unit_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}
		foreach($_REQUEST['mi_product'] as $key=>$value)
		{
			$_REQUEST['mi_product'][$key]['change_status'] = ($value['status']=='NO_USE')?'packages/cms/skins/default/images/admin/404/icon.png':'packages/cms/skins/default/images/avaiable.jpg';
		}
		if(!isset($_REQUEST['category_id']))
		{
			$_REQUEST['category_id']=(sizeof($_REQUEST['mi_product'])>0 and $item=reset($_REQUEST['mi_product'])and isset($item['category_id']))?$item['category_id']:'';	
		}
		$this->map['exchange_rate'] = DB::fetch('SELECT id,exchange FROM currency WHERE id=\'VND\'','exchange');
		$this->map['languages'] = $languages;
		$this->map['category_id_options'] = $category_id_options;
		$this->map['unit_id_options'] = $unit_id_options; 
		$this->parse_layout('edit',$this->map);
	}
}
?>