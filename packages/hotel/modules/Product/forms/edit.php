<?php
class EditProductForm extends Form
{
	function EditProductForm()
	{
		Form::Form('EditProductForm');
		//$this->add('product.id',new TextType(false,'invalid_code',0,100)); 
		$this->add('product.type',new SelectType(false,'invalid_type',array('GOODS'=>'GOODS','DRINK'=>'DRINK','PRODUCT'=>'PRODUCT','MATERIAL'=>'MATERIAL','EQUIPMENT'=>'EQUIPMENT','SERVICE'=>'SERVICE','TOOL'=>'TOOL'))); 
		$this->add('product.category_id',new IDType(true,'invalid_category_id','product_category')); 
		$this->add('product.unit_id',new IDType(true,'invalid_unit_id','unit')); 

		$languages = DB::select_all('language');
		foreach($languages as $language)
		{
			$this->add('product.name_'.$language['id'],new TextType(true,'invalid_name',0,2000)); 
		}
	}
	function on_submit()
	{
		if($this->check() and !is_array(URL::get('selected_ids')))
		{
            require_once 'packages/core/includes/utils/upload_file.php';
			if(URL::get('selected_ids'))
			{
				if(strpos(URL::get('selected_ids'),','))
				{
					$old_items = DB::select_all('product','id in (\''.str_replace(',','\',\'',URL::get('selected_ids')).'\')');
				}
				else
				{
					$old_item = DB::select('product','id=\''.URL::get('selected_ids').'\'');
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
				    //anh moi, neu co
				    $new_img = update_mi_upload_image('product',$key,'new_image_url','resources/default/products',true,'id',true,120,120);
                    $record['image_url'] = $new_img?$new_img:$record['image_url'];
                    unset($record['new_image_url']);
                    unset($record['new_image_url']);
                    unset($record['image_name']);
                    unset($record['check']);
					if(isset($record['price']))
					{
						$record['price'] = System::calculate_number($record['price']);
					}
					$row = DB::select('product','id=\''.$record['id'].'\'');
					if($record['id'] and isset($old_items[$record['id']]) and $row )
					{
					   
						DB::update('product',$record,'id=\''.$record['id'].'\'');
						if(isset($old_items[$record['id']]))
						{
							$old_items[$record['id']]['not_delete'] = true;
						}
					}
					else
					{
						$ids[] = DB::insert('product',$record);  
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
					DB::delete('product','id=\''.$item['id'].'\'');
				}
			}
			Url::redirect_current(array('type','just_edited_ids'=>URL::get('selected_ids'),'code','name','category_id','type'));
		}
	}	
	function draw()
	{	
	   //System::debug($_REQUEST); exit();
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
							product.*
						from 
							product
						where
							'.((sizeof($selected_ids)>1)?'id in (\''.join($selected_ids,'\',\'').'\')':'product.id=\''.reset($selected_ids).'\'').'
					');
					$mi_product = DB::fetch_all();
                    $bar_reservation_product = DB::fetch_all('select product_id as id from bar_reservation_product where '.((sizeof($selected_ids)>1)?'product_id in (\''.join($selected_ids,'\',\'').'\')':'product_id=\''.reset($selected_ids).'\'').'');
                    $housekeeping_invoice_detail = DB::fetch_all('select product_id as id from housekeeping_invoice_detail where '.((sizeof($selected_ids)>1)?'product_id in (\''.join($selected_ids,'\',\'').'\')':'product_id=\''.reset($selected_ids).'\'').'');
                    $wh_invoice_detail = DB::fetch_all('select product_id as id from wh_invoice_detail where '.((sizeof($selected_ids)>1)?'product_id in (\''.join($selected_ids,'\',\'').'\')':'product_id=\''.reset($selected_ids).'\'').'');
                    $massage_product_consumed = DB::fetch_all('select product_id as id from massage_product_consumed where '.((sizeof($selected_ids)>1)?'product_id in (\''.join($selected_ids,'\',\'').'\')':'product_id=\''.reset($selected_ids).'\'').'');
                    $product_price_list = DB::fetch_all('select product_id as id from product_price_list where '.((sizeof($selected_ids)>1)?'product_id in (\''.join($selected_ids,'\',\'').'\')':'product_id=\''.reset($selected_ids).'\'').'');
                    $product_material = DB::fetch_all('select product_id as id from product_material where '.((sizeof($selected_ids)>1)?'product_id in (\''.join($selected_ids,'\',\'').'\')':'product_id=\''.reset($selected_ids).'\'').'');
                    foreach ($mi_product as $key=>$value)
            		{
            			$mi_product[$key]['check'] = 0;
                        if(isset($bar_reservation_product[$value['id']]) || isset($housekeeping_invoice_detail[$value['id']]) || isset($wh_invoice_detail[$value['id']]) || isset($massage_product_consumed[$value['id']]) || isset($product_price_list[$value['id']]) || isset($product_material[$value['id']]))
                        {
                            $mi_product[$key]['check'] = 1;
                        }
            		}
					$_REQUEST['mi_product'] = $mi_product;
					
					$_REQUEST['selected_ids'] = join($selected_ids,',');
				}
				$row = reset($_REQUEST['mi_product']);
			}
		}
		DB::query('select
			id, product_category.name as name,structure_id
			from product_category
			order by structure_id
			'
		);
		$db_items = DB::fetch_all();
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
            //Lay ten anh de show ra 
            $_REQUEST['mi_product'][$key]['image_name'] = substr($_REQUEST['mi_product'][$key]['image_url'],strrpos($_REQUEST['mi_product'][$key]['image_url'],'/')+1)  ;
		}
		if(!isset($_REQUEST['category_id']))
		{
			$_REQUEST['category_id']=(sizeof($_REQUEST['mi_product'])>0 and $item=reset($_REQUEST['mi_product'])and isset($item['category_id']))?$item['category_id']:'';	
		}
		$this->parse_layout('edit',
			array(
			'exchange_rate' => DB::fetch('SELECT id,exchange FROM currency WHERE id=\'VND\'','exchange'),	
			'languages'=>$languages,
			'category_id_options'=>$category_id_options, 
			'unit_id_options' => $unit_id_options, 
			'portal_id_list'=>String::get_list(Portal::get_portal_list())
			)
		);
	}
}
?>