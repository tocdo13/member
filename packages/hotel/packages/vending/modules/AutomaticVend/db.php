<?php //AutomaticVendDB
	class AutomaticVendDB
	{
	static function get_reservation_product($cond = '')
	{
		 $sql = '
			select 
				(2 * ve_reservation_product.id) as id,
                ve_reservation_product.id as ve_reservation_product_id,
				ve_reservation_product.product_id,
				CASE
					WHEN
						ve_reservation_product.name is not null
					THEN
						ve_reservation_product.name
					ELSE
						product.name_'.Portal::language().'
				END name,			
				product.category_id,
				ve_reservation_product.quantity as quantity, 
				ve_reservation_product.quantity_discount,
				ve_reservation_product.price,
                ve_reservation_product.printed,
                ve_reservation_product.promotion,
				ve_reservation_product.discount_rate,
				ve_reservation_product.discount_category,
				unit.name_'.Portal::language().' as unit_name ,
				ve_reservation_product.note,
                product.type
			from 
				ve_reservation_product   
				LEFT JOIN product_price_list on product_price_list.id = ve_reservation_product.price_id
				LEFT JOIN product on product.id = ve_reservation_product.product_id
				LEFT OUTER join unit on unit.id = product.unit_id 
				LEFT OUTER JOIN product_category ON product_category.id = product.category_id
			where 1>0
				'.$cond.' AND ve_reservation_product.bar_reservation_id=\''.Url::iget('id').'\'
			order by
				ve_reservation_product.id
		';
		$reservation_products =  DB::fetch_all($sql);
		return $reservation_products;
	}
    
	static function select_list_other_category($bar_id)
    {
		$categories = AutomaticVendDB::get_list_other_category($bar_id);
		$items= '<ul id="mycarousel" class="jcarousel-skin-tango">';
		foreach($categories as $id => $category)
        {
			$level = IDStructure::level($category['structure_id']);
			if($level == 1 or $level == 2 or $level == 3)
            {
				$items .= '<li><span lang="'.$category['id'].'" id="category_'.$category['id'].'" class="category-list-item-parent">'.ucfirst($category['name']).'</span></li>';	
			}
		}
		$items.= '</ul>';
		return $items;
	}
	static function select_list_food_category($bar_id)
    {
		$categories = AutomaticVendDB::get_list_food_category($bar_id);
		$items= '<ul id="ul_food_category" class="jcarousel-skin-tango">';
		foreach($categories as $id => $category)
        {
			$level = IDStructure::level($category['structure_id']);
			if($level == 1 or $level == 2 or $level == 3)
            {
				$items .= '<li><span lang="'.$category['id'].'" id="category_'.$category['id'].'" class="category-list-item-parent">'.ucfirst($category['name']).'</span></li>';	
			}
		}
		$items.= '</ul>';
		return $items;
	}
    
    
	 static function get_list_other_category($department_id)
     {
	   
        $department = DB::select_id('department',$department_id);
        if(!empty($department))
            $dp_code = $department['code'];
        else
            $dp_code = 'VENDING';
		$sql = 'SELECT
					product_category.id
					,product_category.name
					,product_category.structure_id
				FROM
					product_category
					INNER JOIN product ON product_category.id = product.category_id
					INNER JOIN product_price_list ON product_price_list.product_id = product.id
					INNER JOIN unit ON unit.id = product.unit_id
				WHERE 
					1>0 AND (product.type = \'DRINK\' OR product.type = \'SERVICE\')
					AND product_price_list.portal_id = \''.PORTAL_ID.'\'
					AND product_price_list.department_code=\''.$dp_code.'\'
				ORDER BY product_category.position';	
		$categories = DB::fetch_all($sql);
		return $categories;
	}
	static function get_list_food_category($department_id){
        
        $department = DB::select_id('department',$department_id);
        if(!empty($department))
           $dp_code = $department['code'];
        else
            $dp_code = 'VENDING';
            
		$sql = 'SELECT
					product_category.id
					,product_category.name
					,product_category.structure_id
                    ,product.barcode
				FROM
					product_category
					INNER JOIN product ON product_category.id = product.category_id
					INNER JOIN product_price_list ON product_price_list.product_id = product.id
					INNER JOIN unit ON unit.id = product.unit_id
				WHERE 
					1>0 AND (product.type = \'GOODS\' OR product.type = \'PRODUCT\')
					AND product_price_list.portal_id = \''.PORTAL_ID.'\'
					AND product_price_list.department_code=\''.$dp_code.'\'
				ORDER BY product_category.position';	
		$categories = DB::fetch_all($sql);
		//System::Debug($categories);
		return $categories;
	}
	static function get_list_category_discount($department_id){
        $cond = ' 1=1';	
        $department = DB::select_id('department',$department_id);
        //System::debug($department);   
        if(!empty($department))
            $cond .= ' AND product_price_list.department_code = \''.$department['code'].'\' ';
        else
            $cond .= ' AND product_price_list.department_code = \'VENDING\' ';
            
		$sql = 'SELECT
					product_category.id
					,product_category.name
					,product_category.structure_id
					,product_category.code
                    ,product.barcode
				FROM
					product_category
					INNER JOIN product ON product_category.id = product.category_id
					INNER JOIN product_price_list ON product_price_list.product_id = product.id
					INNER JOIN department ON department.code = product_price_list.department_code
					INNER JOIN unit ON unit.id = product.unit_id
				WHERE 
					'.$cond.' 
                    AND 
                    (
                        (product.type = \'GOODS\' OR product.type = \'PRODUCT\' OR product.type = \'DRINK\' OR product.type = \'SERVICE\')
    					AND 
                        product_category.code <>\'ROOT\'
                    )
					AND product_price_list.portal_id = \''.PORTAL_ID.'\'
				ORDER BY structure_id';	
		$items = DB::fetch_all($sql);
		$items_discount = DB::fetch_all('select 
                                            ve_product_category_discount.code as id, 
                                            ve_product_category_discount.discount_percent 
                                        from 
                                            ve_product_category_discount  
                                        where 
                                            portal_id=\''.PORTAL_ID.'\''
                                        );
		require_once 'packages/core/includes/system/id_structure.php';
		foreach($items as $key => $itm){
			$items[$key]['level'] = IDStructure::level($itm['structure_id']);
			if(isset($items_discount[$itm['code']])){
				$items[$key]['discount_percent'] = $items_discount[$itm['code']]['discount_percent'];
			}else{
				$items[$key]['discount_percent'] = 0;	
			}
		}			
		return $items;
	}
	function get_all_product($department_id)
    {
        $department = DB::select_id('department',$department_id);
        //System::debug($department);   
        if(!empty($department))
            $cond = ' AND product_price_list.department_code = \''.$department['code'].'\' ';
        else
            $cond = ' AND product_price_list.department_code = \'VENDING\' ';
		$sql = '
				SELECT
					product_price_list.id as id,
                    product.id as code,
                    product.id as product_id,
                    product.barcode,--Kimtan them ma vach
                    product.name_'.Portal::language().' as name,
					product.name_2,
					product_price_list.price, unit.name_'.Portal::language().' as unit,
					product_category.structure_id,
                    product_category.name AS category_name,
                    product.category_id,
                    product.unit_id,
                    product.type
				FROM 
					product_price_list
					INNER JOIN product ON product_price_list.product_id = product.id
					INNER JOIN product_category ON product_category.id = product.category_id
					INNER JOIN unit ON unit.id = product.unit_id
				WHERE	
					(product.type = \'GOODS\' OR product.type = \'PRODUCT\' OR product.type = \'DRINK\' OR product.type = \'SERVICE\')
					AND product_price_list.portal_id = \''.PORTAL_ID.'\'
					AND (product_price_list.end_date is null OR product_price_list.end_date>=\''.Date_Time::to_orc_date(date('d/m/y')).'\')
					'.$cond.'
				ORDER BY
					product.name_'.Portal::language().' ';
		$products = DB::fetch_all($sql);
		return $products;			
	}	

	function get_bar_reservation($id)
	{
		return DB::fetch('
			SELECT
				bar_reservation.id,
				bar.print_kitchen_name,
				bar.print_bar_name,
				bar.name as bar_name,
				party.full_name as user_name
			FROM
				bar_reservation
				INNER JOIN bar on bar_reservation.bar_id = bar.id
				LEFT OUTER JOIN party on party.user_id = bar_reservation.checked_out_user_id AND party.type=\'USER\'
			WHERE
				bar_reservation.id='.$id
		);
	}
	function get_product_select($arr_id){
		return DB::fetch_all('
				SELECT
					product_price_list.id as id,
					product_price_list.price
				FROM 
					product_price_list
					INNER JOIN product ON product_price_list.product_id = product.id
					INNER JOIN product_category pc ON pc.id = product.category_id
					INNER JOIN unit ON unit.id = product.unit_id
				WHERE	
					1>0 AND product_price_list.id in ('.$arr_id.')
				ORDER BY
					product.name_'.Portal::language().'
		');	
	}
    function get_rr_id($id){
		$sql ='SELECT 
					reservation_room.id
					,room.name as room_name
                    ,reservation_room.status as reservation_room_status                    
					,room.id as room_id
					,concat(DECODE(traveller.gender,1,\'Mr. \',\'Ms. \'),concat(traveller.first_name,concat(\' \',traveller.last_name))) as traveller_name
					,customer.name as customer_name
				 FROM 
				 	reservation_room 
					inner join reservation on reservation.id = reservation_room.reservation_id
					inner join room on room.id = reservation_room.room_id
					left outer join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
					left outer join traveller on traveller.id = reservation_traveller.TRAVELLER_ID
					left outer join customer on customer.id = reservation.customer_id
				WHERE reservation_room.id = '.$id.' and reservation.PORTAL_ID=\''.PORTAL_ID.'\'
					AND reservation_traveller.status=\'CHECKIN\'';
		$rr_info = DB::fetch($sql); 
		return $rr_info;	
	}
	function get_old_product($bar_reservation_id){
		 $items = DB::fetch_all('
                        SELECT  
                            ve_reservation_product.price_id as id,
                            ve_reservation_product.quantity,
                            ve_reservation_product.quantity_discount,
                            ve_reservation_product.note,
                            ve_reservation_product.id as prd_id,
                            ve_reservation_product.product_id,
            				CASE
            					WHEN
            						ve_reservation_product.name is not null
            					THEN
            						ve_reservation_product.name
            					ELSE
            						product.name_'.Portal::language().'
            				END name,
                            ve_reservation_product.department_id	
						FROM 
            				ve_reservation_product   
            				LEFT JOIN product_price_list on product_price_list.id = ve_reservation_product.price_id
            				LEFT JOIN product on product.id = ve_reservation_product.product_id
            				LEFT OUTER join unit on unit.id = product.unit_id 
            				LEFT OUTER JOIN product_category ON product_category.id = product.category_id
						WHERE 
                            bar_reservation_id ='.$bar_reservation_id);	
			return $items;
	}
    
	function get_total_bars($bar_id = false)
    {
        $cond = '';
        if($bar_id)
        {
            $cond .= ' and department.id != '.$bar_id;
        }
		$bars = DB::fetch_all('
			SELECT
                department.id,
                department.code,
                department.name_'.Portal::language().' as name,
                portal_department.warehouse_id,
                department.id as bar_id_from
			FROM
                department
                inner join portal_department on department.code = portal_department.department_code and portal_department.portal_id = \''.PORTAL_ID.'\'
			WHERE
				1=1 '.$cond.' AND department.parent_id = (select id from department where code = \'VENDING\')');
	   return $bars;
	}
}
?>