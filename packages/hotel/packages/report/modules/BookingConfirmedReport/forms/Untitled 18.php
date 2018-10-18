<?php 
	class TouchBarRestaurantDB
	{
	static function get_reservation_product($cond = '')
	{
		 $sql = '
			select 
				bar_reservation_product.id,
				bar_reservation_product.product_id,
				CASE
					WHEN
						product.id =\'DOUTSIDE\' or  product.id =\'FOUTSIDE\' or product.id =\'SOUTSIDE\' 
					THEN
						lower(bar_reservation_product.name)
					ELSE
						lower(product.name_'.Portal::language().')
				END name,			
				product.category_id,
				bar_reservation_product.quantity as quantity, 
				bar_reservation_product.quantity_discount as quantity_discount,
				bar_reservation_product.quantity_cancel as quantity_cancel, 
				bar_reservation_product.price, 
				bar_reservation_product.discount_rate as discount_rate,
				bar_reservation_product.discount_category,
                bar_reservation_product.bar_set_menu_id,
                \'\' as bar_set_menu_code,
				unit.name_'.Portal::language().' as unit_name ,
				NVL(bar_reservation_product.printed,0) as printed,
                bar_reservation_product.remain,
				NVL(bar_reservation_product.printed_cancel,0) as printed_cancel,
				bar_reservation_product.note,
				product.type,
                nvl(bar_reservation.tax_rate,0) as tax_rate,
                nvl(bar_reservation.bar_fee_rate,0) as bar_fee_rate,
                bar_reservation_product.stt_order
			from 
				bar_reservation_product
                inner join bar_reservation on bar_reservation.id = bar_reservation_product.bar_reservation_id  
				INNER JOIN product_price_list on product_price_list.id = bar_reservation_product.price_id
				INNER JOIN product on product.id = bar_reservation_product.product_id
				LEFT OUTER join unit on unit.id = product.unit_id 
				LEFT OUTER JOIN product_category ON product_category.id = product.category_id
			where 1>0
				'.$cond.' AND bar_reservation_product.bar_reservation_id=\''.Url::iget('id').'\' AND bar_reservation_product.bar_set_menu_id IS NULL
			order by
				bar_reservation_product.id
		';
		$reservation_products =  DB::fetch_all($sql);
		return $reservation_products;
	}
    static function get_reservation_set_product($cond = '')
	{
		 $sql = '
			select 
				bar_reservation_set_product.id || \'-\' || bar_reservation_set_product.product_id as id,
				bar_reservation_set_product.product_id,
				CASE
					WHEN
						product.id =\'DOUTSIDE\' or  product.id =\'FOUTSIDE\' or product.id =\'SOUTSIDE\' 
					THEN
						lower(bar_reservation_set_product.name)
					ELSE
						lower(product.name_'.Portal::language().')
				END name,			
				product.category_id,
                bar_set_menu.code as bar_set_menu_code,
                bar_reservation.id as bar_reservation_id,
				bar_reservation_set_product.quantity as quantity, 
				bar_reservation_set_product.quantity_discount as quantity_discount,
				bar_reservation_set_product.quantity_cancel as quantity_cancel, 
				0 as price, 
				bar_reservation_set_product.discount_rate as discount_rate,
				bar_reservation_set_product.discount_category,
                bar_reservation_set_product.bar_set_menu_id,
				unit.name_'.Portal::language().' as unit_name ,
				DECODE(bar_reservation_set_product.printed,null,0,bar_reservation_set_product.printed) as printed
                ,bar_reservation_set_product.remain
				,DECODE(bar_reservation_set_product.printed_cancel,null,0,bar_reservation_set_product.printed_cancel) as printed_cancel
				,bar_reservation_set_product.note
				,product.type
                ,nvl(bar_reservation.tax_rate,0) as tax_rate 
                ,nvl(bar_reservation.bar_fee_rate,0) as bar_fee_rate
                ,bar_reservation_set_product.bar_set_menu_id
                ,bar_set_menu.name as bar_set_menu_name
			from 
				bar_reservation_set_product
                inner join bar_reservation on bar_reservation.id = bar_reservation_set_product.bar_reservation_id  
                INNER JOIN bar_set_menu ON bar_reservation_set_product.bar_set_menu_id = bar_set_menu.id
				LEFT JOIN product_price_list on product_price_list.id = bar_reservation_set_product.price_id
				LEFT JOIN product on product.id = bar_reservation_set_product.product_id
				LEFT OUTER join unit on unit.id = product.unit_id 
				LEFT OUTER JOIN product_category ON product_category.id = product.category_id
			where 1>0
				'.$cond.' AND bar_reservation_set_product.bar_reservation_id=\''.Url::iget('id').'\'
			order by
				bar_reservation_set_product.bar_set_menu_id
		';
		$reservation_set_products =  DB::fetch_all($sql);
		return $reservation_set_products;
	}
	static function select_list_other_category($bar_id){
		$categories = TouchBarRestaurantDB::get_list_other_category($bar_id);
        $items= '<ul id="mycarousel" class="jcarousel-skin-tango" style="margin-left:25px important" >';
		foreach($categories as $id => $category){
			$level = IDStructure::level($category['structure_id']);
			if($level == 1 or $level == 2 or $level == 3){
				$items .= '<li><span lang="'.$category['id'].'" id="category_'.$category['id'].'" class="category-list-item-parent">'.ucfirst($category['name']).'</span></li>';	
			}
		}
		$items.= '</ul>';
		return $items;
	}
	static function select_list_food_category($bar_id){
		$categories = TouchBarRestaurantDB::get_list_food_category($bar_id);
		$items= '<ul id="ul_food_category" class="jcarousel-skin-tango">';
		/** - THANH fix set menu len dau tien **/
        
        $items .= '<li><span class="category-set-menu">'.Portal::language('set_menu').'</span></li>';
        /** - END **/
        foreach($categories as $id => $category){
			$level = IDStructure::level($category['structure_id']);
			if($level == 1 or $level == 2 or $level == 3){
				$items .= '<li><span lang="'.$category['id'].'" id="category_'.$category['id'].'" class="category-list-item-parent">'.ucfirst($category['name']).'</span></li>';	
			}
		}
		$items.= '</ul>';
		return $items;
	}
	 static function get_list_other_category($bar_id){
	   $language ="";
       if(Portal::language()==2){
	       $language = "_en";
	   }
		$dp_code = DB::fetch('select department_id as code from bar where id='.$bar_id.' and portal_id=\''.PORTAL_ID.'\'','code');	
		$structure_id_drink = DB::fetch('select structure_id from product_category where code=\'DU\'','structure_id');
		$structure_id_service = DB::fetch('select structure_id from product_category where code=\'DVNH\'','structure_id');
        //$structure_other_id_service = DB::fetch('select structure_id from product_category where code=\'OT\'','structure_id');
		$sql = 'SELECT
					product_category.id
					,product_category.name'.$language.' as name
					,product_category.structure_id
				FROM
					product_category
					INNER JOIN product ON product_category.id = product.category_id
					INNER JOIN product_price_list ON product_price_list.product_id = product.id
					INNER JOIN unit ON unit.id = product.unit_id
				WHERE 
					1>0 
					AND ((product_category.structure_id > '.$structure_id_drink.' AND  product_category.structure_id < '.IDStructure::next($structure_id_drink).')
					OR (product_category.structure_id > '.$structure_id_service.' AND  product_category.structure_id < '.IDStructure::next($structure_id_service).'))
					AND product_price_list.portal_id = \''.PORTAL_ID.'\'
					AND product_price_list.department_code=\''.$dp_code.'\' 
                    AND (product_price_list.end_date is null OR product_price_list.end_date>=\''.Date_Time::to_orc_date(date('d/m/y')).'\')
				ORDER BY product_category.position';
        //System::debug($sql);	
		$categories = DB::fetch_all($sql);
        //System::debug($categories);
		return $categories;
	}
	static function get_list_food_category($bar_id){
	   $language ="";
       if(Portal::language()==2){
	       $language = "_en";
	   }
		$dp_code = DB::fetch('select department_id as code from bar where id='.$bar_id.' and portal_id=\''.PORTAL_ID.'\'','code');	
		$structure_id_food = DB::fetch('select structure_id from product_category where code=\'DA\'','structure_id');
		$sql = 'SELECT
					product_category.id
					,product_category.name'.$language.' as name
					,product_category.structure_id
                    ,product_category.code
				FROM
					product_category
					INNER JOIN product ON product_category.id = product.category_id
					INNER JOIN product_price_list ON product_price_list.product_id = product.id
					INNER JOIN unit ON unit.id = product.unit_id
				WHERE 
					1>0 
					AND (product_category.structure_id > '.$structure_id_food.' AND  product_category.structure_id < '.IDStructure::next($structure_id_food).')
					AND product_price_list.portal_id = \''.PORTAL_ID.'\'
					AND product_price_list.department_code=\''.$dp_code.'\' 
                    AND (product_price_list.end_date is null OR product_price_list.end_date>=\''.Date_Time::to_orc_date(date('d/m/y')).'\')
                    AND product_category.code!=\'SETMENU\'
				ORDER BY product_category.position';	
		$categories = DB::fetch_all($sql);
		//System::Debug($categories);
		return $categories;
	}
	static function get_list_category_discount(){
	   $language ="";
       if(Portal::language()==2){
	       $language = "_en";
	   }
         $cond = ' 1=1';
		if(Session::get('bar_id')){
			$dp_code = DB::fetch('select department_id as code from bar where id='.Session::get('bar_id').' and portal_id=\''.PORTAL_ID.'\'','code');	
			$cond .= ' AND product_price_list.department_code=\''.$dp_code.'\'';
		}
		$sql = 'SELECT
					product_category.id
					,product_category.name'.$language.' as name
					,product_category.structure_id
					,product_category.code
				FROM
					product_category
					INNER JOIN product ON product_category.id = product.category_id
					INNER JOIN product_price_list ON product_price_list.product_id = product.id
					INNER JOIN department ON department.code = product_price_list.department_code
					INNER JOIN unit ON unit.id = product.unit_id
				WHERE 
					'.$cond.' AND ((product.type = \'DRINK\' OR product.type = \'SERVICE\')
					AND product_category.code <>\'ROOT\') or (product_category.code=\'DA\' or product_category.code =\'DU\')
					AND product_price_list.portal_id = \''.PORTAL_ID.'\'
				ORDER BY structure_id';	
		$items = DB::fetch_all($sql);
		$items_discount = DB::fetch_all('select pcd.code as id, 
												pcd.bar_code,pcd.discount_percent from product_category_discount pcd  where bar_code=\''.Session::get('bar_code').'\'');
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
	static function get_bar_table($id){
		$sql = '
			select 
				bar_table.id as table_id,
				bar_reservation_table.id,
				bar_table.code as code,
				bar_reservation_table.num_people as num_people,
				bar_reservation_table.order_person,
				bar_table.name as name
			from 
				bar_reservation_table
				inner join bar_table on bar_table.id = bar_reservation_table.table_id 
			where 
				bar_reservation_table.bar_reservation_id=\''.$id.'\'
			order by
				bar_reservation_table.id
		';
		$mi_table = DB::fetch_all($sql);
		return $mi_table;
	}
	static function get_all_product()
    {
        $language ="";
       if(Portal::language()==2){
	       $language = "_en";
	   }
		$sql = '
				SELECT
					product_price_list.id as id,product.id as code,lower(product.name_'.Portal::language().') as name,
					lower(product.name_'.Portal::language().') as name_2,
					product_price_list.price, unit.name_'.Portal::language().' as unit,
					pc.structure_id,pc.name'.$language.' AS pc_name,product.category_id,product.unit_id
				FROM 
					product_price_list
					INNER JOIN product ON product_price_list.product_id = product.id
					INNER JOIN product_category pc ON pc.id = product.category_id
					INNER JOIN unit ON unit.id = product.unit_id
				WHERE	
					(product.type = \'GOODS\' OR product.type = \'PRODUCT\' OR product.type = \'DRINK\' OR product.type = \'SERVICE\')
					AND product_price_list.portal_id = \''.PORTAL_ID.'\'
					AND (product_price_list.end_date is null OR product_price_list.end_date>=\''.Date_Time::to_orc_date(date('d/m/y')).'\')
					AND product_price_list.department_code = \''.Session::get('dp_code').'\'
                    AND UPPER(unit.name_1)!=\'SET\'
				ORDER BY
					product.name_'.Portal::language().' ';
                   
                 
		$products = DB::fetch_all($sql);
        $sql = "SELECT 
             product_price_list.id,
             bar_set_menu.id as bar_set_menu_id,
             bar_set_menu.code as code,
             bar_set_menu.name as name,
             bar_set_menu.total as price,
             'set' as unit
            FROM 
            bar_set_menu
            INNER JOIN product_price_list ON bar_set_menu.code = product_price_list.product_id 
            WHERE (bar_set_menu.department_code = '".Session::get('dp_code')."' OR bar_set_menu.department_code='RES')";
            $result = DB::fetch_all($sql);
         //$result = 
           $products+=$result;  
          //System::debug($result); exit(); 
          
         
		return $products;			
	}
	static function get_table_map(){
		//$_REQUEST['in_date'] =  $in_date?$in_date:date('d/m/Y',time());
		$in_date = date('d/m/Y',time());
		$sql = '
			SELECT
				bar_table.*,
				bar.name as bar_name,
				\'AVAILABLE\' AS status
			FROM
				bar_table
				inner join bar on bar.id=bar_table.bar_id
			WHERE
				bar_table.portal_id=\''.PORTAL_ID.'\'
				AND bar_table.bar_id = \''.Session::get('bar_id').'\'
			ORDER BY
				bar.name,bar_table.name
		';
		$bar_tables = DB::fetch_all($sql);			
		$floors = array();
		$last_floor = false;
		$i = 1;
		$status_tables_others = DB::fetch_all('
			SELECT
				bar_reservation.id,bar_table.id table_id,bar_reservation.status
				,bar_reservation.arrival_time
			FROM
				bar_reservation
				inner join bar_reservation_table on bar_reservation_table.bar_reservation_id = bar_reservation.id
				inner join bar_table on bar_table.id = bar_reservation_table.table_id
				inner join bar on bar.id=bar_table.bar_id
			WHERE
				bar_reservation.portal_id=\''.PORTAL_ID.'\'
				AND (bar_reservation.status = \'CHECKOUT\' OR bar_reservation.status = \'BOOKED\')
				AND bar_reservation.arrival_time >='.Date_Time::to_time($in_date).' AND bar_reservation.arrival_time <'.(Date_Time::to_time($in_date)+1*3600).'
			ORDER BY
				ABS(bar_reservation.arrival_time - '.(Date_Time::to_time($in_date)+(intval(date('H'))*3600+intval(date('i'))*60)).')	
		');
		$sql = '
			SELECT
				bar_table.id,   
				bar_table.num_people,
				bar_reservation.id as bar_reservation_id,bar_reservation.agent_name,
				bar_reservation.time_in,bar_reservation.time_out,
				bar_reservation.arrival_time,bar_reservation.departure_time,
				bar_table.name as bar_table_name,
				bar.name as bar_name,bar_table.table_group,
				bar_reservation_table.num_people,
				DECODE(bar_reservation.status,\'CHECKIN\',\'OCCUPIED\',DECODE(bar_reservation.status,\'RESERVATION\',\'BOOKED\',bar_reservation.status)) AS status
			FROM
				bar_reservation
				inner join bar_reservation_table on bar_reservation_table.bar_reservation_id = bar_reservation.id
				inner join bar_table on bar_table.id = bar_reservation_table.table_id
				inner join bar on bar.id=bar_table.bar_id
			WHERE
				bar_reservation.status <> \'CHECKOUT\' AND bar_reservation.status <> \'CANCEL\' and bar_reservation.portal_id=\''.PORTAL_ID.'\'
				AND bar_reservation.arrival_time >='.Date_Time::to_time($in_date).' AND bar_reservation.arrival_time <'.(Date_Time::to_time($in_date)+24*3600).'
			ORDER BY
				ABS(bar_reservation.arrival_time - '.(Date_Time::to_time($in_date)+(intval(date('H'))*3600+intval(date('i'))*60)).')
		';
		$busy_tables = DB::fetch_all($sql);
		foreach($bar_tables as $key=>$value)
		{
			$floors[$value['table_group']]['id']= $value['bar_id'];
			$floors[$value['table_group']]['name']= $value['table_group'];
			$value['class'] = 'AVAILABLE';
			$value['agent_name'] = '';
			//$value['href'] = URL::build('touch_bar_restaurant',array('cmd'=>'add','bar_id'=>$value['bar_id'],'table_id'=>$value['id'],'arrival_time'=>$_REQUEST['in_date']));
			$value['arrival_time'] = '';
			$value['departure_time'] = '';
			$value['num_people'] = '';
			if(isset($busy_tables[$key])){
				$value['arrival_time'] = $busy_tables[$key]['time_in']?date('H:i',$busy_tables[$key]['time_in']):date('H:i',$busy_tables[$key]['arrival_time']);
				$value['departure_time'] = $busy_tables[$key]['time_out']?date('H:i',$busy_tables[$key]['time_out']):date('H:i',$busy_tables[$key]['departure_time']);
				$value['status'] = $busy_tables[$key]['status'];
				$value['agent_name'] = $busy_tables[$key]['agent_name'];
				if($busy_tables[$key]['status']=='BOOKED')
				{
					//$value['href'] = URL::build('touch_bar_restaurant',array('cmd'=>'edit','id'=>$busy_tables[$key]['bar_reservation_id']));
				}
				else
				{
					//$value['href'] = URL::build('touch_bar_restaurant',array('cmd'=>'edit','bar_id'=>$value['bar_id'],'id'=>$busy_tables[$key]['bar_reservation_id']));
				}
				$value['num_people'] = $busy_tables[$key]['num_people'].' '.Portal::language('people');
			}
			if($value['status']){
				$value['class'] = $value['status'];	
			}
			$value['status_tables_others'] = array();
			foreach($status_tables_others as $k=>$v){
				$table_out_list = DB::fetch_all('
					select
						bar_table.id
					from
						bar_table
						inner join bar_reservation_table on bar_reservation_table.table_id = bar_table.id
					where bar_reservation_table.bar_reservation_id = '.$k);
				foreach($table_out_list as $tbl_id=>$tbl)
				{
					if($tbl_id==$key){
						$value['status_tables_others'][$k]['id'] = $k;
						$value['status_tables_others'][$k]['status'] = $v['status'];
					}
				}
			}
			$floors[$value['table_group']]['bar_tables'][$i]= $value;
			$i++;
		}
		return $floors;
	}	
	function get_table_conflict($cond=' 1>0'){
		$conflix=DB::fetch('Select 
								bar_reservation.* 
							from bar_reservation 
								INNER JOIN bar_reservation_table ON bar_reservation.id = bar_reservation_table.bar_reservation_id 
							where  '.$cond.' AND bar_reservation.portal_id=\''.PORTAL_ID.'\'');
		return $conflix;
	}
	
	function get_room_guest($rr_id){
		return $current_room = DB::fetch('
					select 
						traveller.id, reservation_room.id as reservation_room_id,
						CONCAT(room.name,CONCAT(\' - \',CONCAT(CONCAT(traveller.first_name,\' \'),traveller.last_name))) as name,
						room.id as room_id,room.name as room_name
					from 
						reservation_traveller
						INNER JOIN traveller on traveller.id = reservation_traveller.traveller_id 
						INNER JOIN reservation_room on reservation_traveller.reservation_room_id = reservation_room.id
						INNER JOIN reservation on reservation_room.reservation_id = reservation.id
						LEFT OUTER JOIN room ON room.id = reservation_room.room_id
						inner join room_status on room_status.RESERVATION_ID  =  RESERVATION_ROOM.RESERVATION_ID 
					where
						reservation_room.id = '.$rr_id.' and reservation.PORTAL_ID=\''.PORTAL_ID.'\'
						AND reservation_traveller.status=\'CHECKIN\'
				');	
	}
	static function get_rr_id($id){
		$sql ='SELECT 
					reservation_room.id
					,room.name as room_name
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
	function get_total_bars_select($bar_id = false){
		$bars = DB::fetch_all('SELECT * FROM bar order by id ASC');
		$items = '<select name="bar_id" onchange="ChangeBar();" id="bar_id">';
		$items .='<option value="">--Select Bar--</option>';	
		foreach($bars as $id=>$bar){
			if($bar['id'] == $bar_id){
				$items .= '<option value="'.$bar['id'].'" selected="selected">'.$bar['name'].'</option>';	
			}else $items .= '<option value="'.$bar['id'].'">'.$bar['name'].'</option>';	
		}
		$items .='</select>';
		return $items;
	}
	static function get_total_bars($bar_id = false){
		//-------- Phan quyen Bar-------------//
		require_once 'packages/hotel/packages/restaurant/includes/table.php';
		$cond_admin = Table::get_privilege_bar();
		$bars = array();
		if(User::is_admin() || $cond_admin){
			$bars = DB::fetch_all('
				SELECT
					bar_charge.id,
					bar_charge.bar_id_from,
					bar_charge.percent,
					bar.name
				FROM
					bar_charge
					INNER JOIN bar on bar.id = bar_charge.bar_id_from
				WHERE
					1=1 '.$cond_admin.' AND bar_charge.bar_id = '.Session::get('bar_id').' and bar_charge.portal_id = \''.PORTAL_ID.'\' ORDER BY bar.ID ASC');
		}
		return $bars;
	}
	static function get_bar_reservation($id)
	{
		return DB::fetch('
			SELECT
				bar_reservation.id,
                bar_reservation.code,
				bar.print_kitchen_name,
                bar.kitchen_ip_address,
				bar.print_bar_name,
                bar.bar_ip_address,
                bar.code as bar_code,
				bar.name as bar_name,
				party.full_name as user_name,
				bar_reservation.status,bar_reservation.departure_time
			FROM
				bar_reservation
				INNER JOIN bar on bar_reservation.bar_id = bar.id
				LEFT OUTER JOIN party on party.user_id = bar_reservation.checked_out_user_id AND party.type=\'USER\'
			WHERE
				bar_reservation.id='.$id
		);
	}
	static function get_product_select($arr_id){
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
					AND product_price_list.department_code = \''.Session::get('dp_code').'\'
				ORDER BY
					product.name_'.Portal::language().'
		');	
	}
	static function get_old_product($bar_reservation_id){
		 $items = DB::fetch_all('SELECT 
							CASE
								WHEN (bar_reservation_product.product_id=\'FOUTSIDE\' OR bar_reservation_product.product_id=\'DOUTSIDE\' OR bar_reservation_product.product_id=\'SOUTSIDE\')
								THEN 
									(price_id || \'_\' || bar_reservation_product.id)
								ELSE
									price_id
							END as id,
							 bar_reservation_product.quantity,
							 bar_reservation_product.quantity_discount,
							 bar_reservation_product.printed,
							 bar_reservation_product.note,
							 bar_reservation_product.id as prd_id,
                             bar_reservation_product.complete
						FROM 
							bar_reservation_product 
						WHERE bar_reservation_id ='.$bar_reservation_id);	
			return $items;
	}
    function get_old_set_menu_product($bar_reservation_id){
        $items = DB::fetch_all('SELECT 
							CASE
								WHEN (bar_reservation_set_product.product_id=\'FOUTSIDE\' OR bar_reservation_set_product.product_id=\'DOUTSIDE\' OR bar_reservation_set_product.product_id=\'SOUTSIDE\')
								THEN 
									(price_id || \'_\' || bar_reservation_set_product.id)
								ELSE
									price_id
							END as id,
							 bar_reservation_set_product.quantity,
							 bar_reservation_set_product.quantity_discount,
							 bar_reservation_set_product.printed,
							 bar_reservation_set_product.note,
							 bar_reservation_set_product.id as prd_id
						FROM 
							bar_reservation_set_product 
						WHERE bar_reservation_id ='.$bar_reservation_id);	
			return $items;
	}
	function checkPayment($id){
		$row = DB::fetch('select id,total as amount,checked_out_user_id as user_id,pay_with_room from bar_reservation where id='.$id.'');
		if(!empty($row)){
			$payment = DB::fetch_all('select * from payment where type=\'BAR\' AND bill_id='.$id.'');
			if($row['pay_with_room'] != 1){
				if(empty($payment)){
					DB::insert('payment',array('type'=>'BAR','bill_id'=>$id,'payment_type_id'=>'CASH','currency_id'=>'VND','exchange_rate'=>1,'portal_id'=>PORTAL_ID,'amount'=>$row['amount'],'time'=>time(),'user_id'=>$row['user_id']));	
				}
			}else{
				if(!empty($payment)){
					foreach($payment as $p =>$pay){
						//DB::query('delete from payment WHERE id = '.$p.' AND type=\'BAR\'');	
					}
				}
			}
		}
	}	
}
?>
