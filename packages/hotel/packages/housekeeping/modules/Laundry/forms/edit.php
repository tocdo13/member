<?php
class EditLaundryForm extends Form
{
	function EditLaundryForm()
	{
		Form::Form('EditLaundryForm');
		$this->link_js('packages/core/includes/js/multi_items.js');
	}
	function on_submit()
	{
            //lay category con cua danh muc Giat la GL
            $sql = '
            	select 
            		ROWNUM as id, code, name, id as category
            	from
            		product_category
            	where
            		'.IDStructure::direct_child_cond(DB::fetch('select id, structure_id from product_category where code = \'GL\'','structure_id')).'
            	order by
            		structure_id
            ';
            $categories = DB::fetch_all($sql);
            
            if(Url::get('deleted_ids'))
            {
            	$ids = explode(',',Url::get('deleted_ids'));
            
                foreach($ids as $value)
            	{
                    $k = 1;
                    foreach($categories as $category)
                    {
                        if(DB::exists_id('product',$value.$k))
                            DB::delete_id('product',$value.$k);
                        if(DB::exists('Select * from product_price_list where product_id = \''.$value.$k.'\' and department_code = \'LAUNDRY\' and portal_id = \''.PORTAL_ID.'\' ' ))
                            DB::delete('product_price_list', ' product_id = \''.$value.$k.'\' and department_code = \'LAUNDRY\' and portal_id = \''.PORTAL_ID.'\' ');
                        $k++;
                    }
                }
            }
            
            if(Url::get('delete'))
            {
                if(is_array(Url::get('selected_ids')))
                {
                    $selected_ids = Url::get('selected_ids');
                    foreach($selected_ids as $value)
            		{
                        $k = 1;
                        foreach($categories as $category)
                        {
                            if(DB::exists_id('product',$value.$k))
                                DB::delete_id('product',$value.$k);
                            if(DB::exists('Select * from product_price_list where product_id = \''.$value.$k.'\' and department_code = \'LAUNDRY\' and portal_id = \''.PORTAL_ID.'\' ' ))
                                DB::delete('product_price_list', ' product_id = \''.$value.$k.'\' and department_code = \'LAUNDRY\' and portal_id = \''.PORTAL_ID.'\' ');
                            $k++;
                        }
                    }
                }
            }		
            if(User::can_add(false,ANY_CATEGORY))
            {
            	if($laundries = Url::get('mi_laundry_product'))
            	{
                    //System::debug($laundries);
            		foreach($laundries as $value)
                    {
            			if($value['product_id'])
            			{
                            //du lieu vao bang product
                            $arr_product = array(
                                                    'name_1'=>$value['name_1'],
                                                    'name_2'=>$value['name_2'],
                                                    'unit_id'=>$value['unit_id'],
                                                    'type'=>'SERVICE',
                                                    'status'=>'avaiable',
                                                );
                            //du lieu vao bang product price                                            
                            $arr_product_price = array(
                                                        'department_code'=>'LAUNDRY',
                                                        'portal_id'=>PORTAL_ID,
                                                        );
                            $i = 1;
                            //check trung code sp, dung khi add new
                            $duplicate = $this->check_dupicate_product_code($categories, $value['product_id']);
            				
                            foreach($categories as $category)
            				{
                                $arr_product['category_id'] = $category['category'];
                                $arr_product_price['price'] = $value['price_'.$category['code']]?System::calculate_number($value['price_'.$category['code']]):0;
                                $arr_product_price['original_price'] = $value['original_price_'.$category['code']]?System::calculate_number($value['original_price_'.$category['code']]):0;
                                $arr_product_price['order_number'] = $value['order_number']?$value['order_number']:'';
                                $arr_product_price['status'] = $value['status'];
                                if($value['action']=='edit')//Neu cap nhat
                                {
                                    $arr_product['category_id'] = $category['category'];
                                    if(DB::fetch('select id from product where id=\''.strtoupper($value['product_id'].$i).'\'','id'))
                                    {
                                        DB::update_id('product',$arr_product,strtoupper($value['product_id'].$i));
                                    }
                                    else
                                    {
                                        $arr_product['id'] = strtoupper($value['product_id'].$i);
                                        DB::insert('product',$arr_product);
                                    }
                                    if(DB::fetch('select id from product_price_list where product_id=\''.strtoupper($value['product_id'].$i).'\' and department_code = \'LAUNDRY\' and portal_id = \''.PORTAL_ID.'\' ','id'))
                                    {
                                        DB::update('product_price_list',$arr_product_price,' product_id = \''.strtoupper($value['product_id'].$i).'\' and department_code = \'LAUNDRY\' and portal_id = \''.PORTAL_ID.'\' ');
                                    }
                                    else
                                    {
                                        $arr_product_price['product_id'] = strtoupper($value['product_id'].$i);
                                        DB::insert('product_price_list',$arr_product_price);
                                    }
                                }
                                else//Neu them moi
                                {
                                    //Neu chua ton tai ma san pham trong product
                                    if(!$duplicate)
                                    {
                                        //add moi product
                                        $arr_product['id'] = strtoupper($value['product_id'].$i);
                                        
                                        DB::insert('product',$arr_product);
                                        
                                        //add vao bang gia
                                        $arr_product_price['product_id'] = strtoupper($value['product_id'].$i);
                                        DB::insert('product_price_list',$arr_product_price);
                                    }
                                    else//neu da ton tai ma san pham
                                    {
                                        $this->error('duplicate_code','duplicate_product_code_'.$value['product_id']);
                                        return;
                                    }
                                }
                                $i++;
                            }
            			}
            		}
            	}
            	Url::redirect_current();
            }
	}
    
    function check_dupicate_product_code($object, $product_code)
    {
        $z = 1;
		foreach($object as $category)
        {
            if(DB::exists_id('product',strtoupper($product_code.$z)))
                return true;
            $z++;
        }
        return false;
    }
    	
	function draw()
	{
        //start KimTan: lay ngon ngu cho cai tieu de thoi
        if(Portal::language()==1)
        {
            $name ='name';
            
            
        }
        else
        {
            $name ='name_en';
            
        }
        //end KimTan: lay ngon ngu cho cai tieu de thoi
        //L?y c�c nh�m s?n ph?m con c?a danh m?c GL = gi?t l�
		$sql = '
			select 
				id, code, '.$name.' as name
			from
				product_category
			where
				'.IDStructure::direct_child_cond(DB::fetch('select id, structure_id from product_category where code = \'GL\'','structure_id')).'
			order by
				structure_id
		';	
        //echo $sql;	
		$this->map['categories'] = DB::fetch_all($sql);
        //System::debug($this->map['categories']);
		
        //L?y c�c unit
		DB::query('select id, name_'.Portal::language().' as name from unit order by name');
		$db_items = DB::fetch_all();
        //System::debug($db_items);
        $this->map['unit_id_options'] = '';
        foreach($db_items as $item)
		{
			$this->map['unit_id_options'] .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}
		$items = array();
		foreach($this->map['categories'] as $key=>$value)
		{
			$items += DB::fetch_all('select 
                                        product_price_list.product_id as id, 
                                        product.name_1, 
                                        product.name_2, 
                                        product.status,  
                                        product.unit_id as unit, 
                                        product_price_list.price as price,
                                        product_price_list.original_price as original_price,
                                        \''.$value['code'].'\' as category,
                                        product_price_list.order_number,
                                        product_price_list.status
                                    from 
                                        product_price_list
                                        INNER JOIN product on product.id = product_price_list.product_id
                                    where 
                                        product.category_id = '.$value['id'].' 
                                        and product.type=\'SERVICE\' 
                                        and product_price_list.portal_id=\''.PORTAL_ID.'\' 
                                        and product_price_list.department_code = \'LAUNDRY\'
                                    order by 
                                        id '
                                    );
            
		}
        //System::debug($items);
		$product = array();
		$this->map['hides'] = '';
		foreach($items as $key=>$value)
		{
			$newkey = substr($key,0,strlen($key)-1);
			if(isset($product[$newkey]))
			{
				$product[$newkey]['price_'.$value['category']] = System::display_number($value['price']);
                $product[$newkey]['original_price_'.$value['category']] = System::display_number($value['original_price']);
			}else
			{
				$product[$newkey] = $value;
				//them
				$product[$newkey]['change_status'] = ($value['status']=='NO_USE')?'packages/cms/skins/default/images/admin/404/icon.png':'packages/cms/skins/default/images/avaiable.jpg';
				//end
				$product[$newkey]['id'] = $newkey;
				$product[$newkey]['product_id'] = $newkey;
				$product[$newkey]['price_'.$value['category']] = System::display_number($value['price']);
                $product[$newkey]['original_price_'.$value['category']] = System::display_number($value['original_price']);
				unset($product[$newkey]['price']);
				unset($product[$newkey]['category']);
			}
		}
		$_REQUEST['mi_laundry_product'] = $product;
		$this->parse_layout('edit',$this->map);
	}
}
?>
