<?php 
	class KaraokeTouchDB
	{
	static function get_reservation_product($cond = '')
	{
		 $sql = '
			select 
				karaoke_reservation_product.id,
				karaoke_reservation_product.product_id,
				CASE
					WHEN
						karaoke_reservation_product.name is not null
					THEN
						karaoke_reservation_product.name
					ELSE
						product.name_'.Portal::language().'
				END name,			
				product.category_id,
				karaoke_reservation_product.quantity as quantity, 
				karaoke_reservation_product.quantity_discount as quantity_discount,
				karaoke_reservation_product.quantity_cancel as quantity_cancel, 
				karaoke_reservation_product.price, 
				karaoke_reservation_product.discount_rate as discount_rate,
				karaoke_reservation_product.discount_category,
				unit.name_'.Portal::language().' as unit_name ,
				DECODE(karaoke_reservation_product.printed,null,0,karaoke_reservation_product.printed) as printed
                ,karaoke_reservation_product.remain
				,DECODE(karaoke_reservation_product.printed_cancel,null,0,karaoke_reservation_product.printed_cancel) as printed_cancel
				,karaoke_reservation_product.note
				,product.type
			from 
				karaoke_reservation_product   
				INNER JOIN product_price_list on product_price_list.id = karaoke_reservation_product.price_id
				INNER JOIN product on product.id = karaoke_reservation_product.product_id
				LEFT OUTER join unit on unit.id = product.unit_id 
				LEFT OUTER JOIN product_category ON product_category.id = product.category_id
			where 1>0
				'.$cond.' AND karaoke_reservation_product.karaoke_reservation_id=\''.Url::iget('id').'\'
			order by
				karaoke_reservation_product.id
		';
		$reservation_products =  DB::fetch_all($sql);
		return $reservation_products;
	}
	static function select_list_other_category($karaoke_id){
		$categories = KaraokeTouchDB::get_list_other_category($karaoke_id);
        //System::debug($categories);
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
	static function select_list_food_category($karaoke_id){
		$categories = KaraokeTouchDB::get_list_food_category($karaoke_id);
		$items= '<ul id="ul_food_category" class="jcarousel-skin-tango">';
		foreach($categories as $id => $category){
			$level = IDStructure::level($category['structure_id']);
			if($level == 1 or $level == 2 or $level == 3){
				$items .= '<li><span lang="'.$category['id'].'" id="category_'.$category['id'].'" class="category-list-item-parent">'.ucfirst($category['name']).'</span></li>';	
			}
		}
		$items.= '</ul>';
		return $items;
	}
	 static function get_list_other_category($karaoke_id){
		$dp_code = DB::fetch('select department_id as code from karaoke where id='.$karaoke_id.' and portal_id=\''.PORTAL_ID.'\'','code');	
		
        $structure_id_food = DB::fetch('select structure_id from product_category where code=\'DA\'','structure_id');
        $ROOT_ID = "1000000000000000000";
        /*
        $structure_id_drink = DB::fetch('select structure_id from product_category where code=\'DU\'','structure_id');
		$structure_id_service = DB::fetch('select structure_id from product_category where code=\'DVNH\'','structure_id');
        //$structure_other_id_service = DB::fetch('select structure_id from product_category where code=\'OT\'','structure_id');
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
					1>0 
					AND ((product_category.structure_id > '.$structure_id_drink.' AND  product_category.structure_id < '.IDStructure::next($structure_id_drink).')
					OR (product_category.structure_id > '.$structure_id_service.' AND  product_category.structure_id < '.IDStructure::next($structure_id_service).'))
					AND product_price_list.portal_id = \''.PORTAL_ID.'\'
					AND product_price_list.department_code=\''.$dp_code.'\'
				ORDER BY product_category.position';
        */	
        $sql = 'SELECT
					product_category.id
					,product_category.name
					,product_category.structure_id
				FROM
					product_category
					INNER JOIN product ON 
                    (
                        product_category.id = product.category_id
                        AND 
                        (product.type = \'GOODS\' OR product.type = \'PRODUCT\' OR product.type = \'DRINK\'  OR product.type = \'SERVICE\')
                    )
					INNER JOIN product_price_list ON product_price_list.product_id = product.id
					INNER JOIN unit ON unit.id = product.unit_id
				WHERE 
					1>0 
					AND ('.IDStructure::child_cond($ROOT_ID,1,"product_category.").') 
                    AND product_category.structure_id != '.$structure_id_food.'
					AND product_price_list.portal_id = \''.PORTAL_ID.'\'
					AND product_price_list.department_code=\''.$dp_code.'\'
				ORDER BY product_category.position';
		$categories = DB::fetch_all($sql);
        //System::debug($categories);
		return $categories;
	}
	static function get_list_food_category($karaoke_id){
		$dp_code = DB::fetch('select department_id as code from karaoke where id='.$karaoke_id.' and portal_id=\''.PORTAL_ID.'\'','code');	
		$structure_id_food = DB::fetch('select structure_id from product_category where code=\'DA\'','structure_id');
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
					1>0 
					AND (product_category.structure_id > '.$structure_id_food.' AND  product_category.structure_id < '.IDStructure::next($structure_id_food).')
					AND product_price_list.portal_id = \''.PORTAL_ID.'\'
					AND product_price_list.department_code=\''.$dp_code.'\'
				ORDER BY product_category.position';	
		$categories = DB::fetch_all($sql);
		//System::Debug($categories);
		return $categories;
	}
	static function get_list_category_discount(){
         $cond = ' 1=1';
		if(Session::get('karaoke_id')){
			$dp_code = DB::fetch('select department_id as code from karaoke where id='.Session::get('karaoke_id').' and portal_id=\''.PORTAL_ID.'\'','code');	
			$cond .= ' AND product_price_list.department_code=\''.$dp_code.'\'';
		}
		$sql = 'SELECT
					product_category.id
					,product_category.name
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
												pcd.bar_code,pcd.discount_percent from product_category_discount pcd  where bar_code=\''.Session::get('karaoke_code').'\'');
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
	static function get_karaoke_table($id){
		$sql = '
			select 
				karaoke_table.id as table_id,
				karaoke_reservation_table.id,
				karaoke_table.code as code,
				karaoke_reservation_table.num_people as num_people,
				karaoke_reservation_table.order_person as order_person,
				karaoke_table.name as name
			from 
				karaoke_reservation_table
				inner join karaoke_table on karaoke_table.id = karaoke_reservation_table.table_id 
			where 
				karaoke_reservation_table.karaoke_reservation_id=\''.$id.'\'
			order by
				karaoke_reservation_table.id
		';
		$mi_table = DB::fetch_all($sql);
		return $mi_table;
	}
	function get_all_product()
    {
		$sql = '
				SELECT
					product_price_list.id as id,product.id as code,product.name_'.Portal::language().' as name,
					product.name_2,
					product_price_list.price, unit.name_'.Portal::language().' as unit,
					pc.structure_id,pc.name AS pc_name,product.category_id,product.unit_id
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
				ORDER BY
					product.name_'.Portal::language().' ';
		$products = DB::fetch_all($sql);
		return $products;			
	}
	function get_table_map(){
		//$_REQUEST['in_date'] =  $in_date?$in_date:date('d/m/Y',time());
		$in_date = date('d/m/Y',time());
		$sql = '
			SELECT
				karaoke_table.*,
				karaoke.name as karaoke_name,
				\'AVAILABLE\' AS status
			FROM
				karaoke_table
				inner join karaoke on karaoke.id=karaoke_table.karaoke_id
			WHERE
				karaoke_table.portal_id=\''.PORTAL_ID.'\'
				AND karaoke_table.karaoke_id = \''.Session::get('karaoke_id').'\'
			ORDER BY
				karaoke.name,karaoke_table.name
		';
		$karaoke_tables = DB::fetch_all($sql);			
		$floors = array();
		$last_floor = false;
		$i = 1;
		$status_tables_others = DB::fetch_all('
			SELECT
				karaoke_reservation.id,karaoke_table.id table_id,karaoke_reservation.status
				,karaoke_reservation.arrival_time
			FROM
				karaoke_reservation
				inner join karaoke_reservation_table on karaoke_reservation_table.karaoke_reservation_id = karaoke_reservation.id
				inner join karaoke_table on karaoke_table.id = karaoke_reservation_table.table_id
				inner join karaoke on karaoke.id=karaoke_table.karaoke_id
			WHERE
				karaoke_reservation.portal_id=\''.PORTAL_ID.'\'
				AND (karaoke_reservation.status = \'CHECKOUT\' OR karaoke_reservation.status = \'BOOKED\')
				AND karaoke_reservation.arrival_time >='.Date_Time::to_time($in_date).' AND karaoke_reservation.arrival_time <'.(Date_Time::to_time($in_date)+1*3600).'
			ORDER BY
				ABS(karaoke_reservation.arrival_time - '.(Date_Time::to_time($in_date)+(intval(date('H'))*3600+intval(date('i'))*60)).')	
		');
		$sql = '
			SELECT
				karaoke_table.id,   
				karaoke_table.num_people,
				karaoke_reservation.id as karaoke_reservation_id,karaoke_reservation.agent_name,
				karaoke_reservation.time_in,karaoke_reservation.time_out,
				karaoke_reservation.arrival_time,karaoke_reservation.departure_time,
				karaoke_table.name as karaoke_table_name,
				karaoke.name as karaoke_name,karaoke_table.table_group,
				karaoke_reservation_table.num_people,
				karaoke_reservation_table.order_person as order_person,
				DECODE(karaoke_reservation.status,\'CHECKIN\',\'OCCUPIED\',DECODE(karaoke_reservation.status,\'RESERVATION\',\'BOOKED\',karaoke_reservation.status)) AS status
			FROM
				karaoke_reservation
				inner join karaoke_reservation_table on karaoke_reservation_table.karaoke_reservation_id = karaoke_reservation.id
				inner join karaoke_table on karaoke_table.id = karaoke_reservation_table.table_id
				inner join karaoke on karaoke.id=karaoke_table.karaoke_id
			WHERE
				karaoke_reservation.status <> \'CHECKOUT\' AND karaoke_reservation.status <> \'CANCEL\' and karaoke_reservation.portal_id=\''.PORTAL_ID.'\'
				AND karaoke_reservation.arrival_time >='.Date_Time::to_time($in_date).' AND karaoke_reservation.arrival_time <'.(Date_Time::to_time($in_date)+24*3600).'
			ORDER BY
				ABS(karaoke_reservation.arrival_time - '.(Date_Time::to_time($in_date)+(intval(date('H'))*3600+intval(date('i'))*60)).')
		';
		$busy_tables = DB::fetch_all($sql);
		foreach($karaoke_tables as $key=>$value)
		{
			$floors[$value['table_group']]['id']= $value['karaoke_id'];
			$floors[$value['table_group']]['name']= $value['table_group'];
			$value['class'] = 'AVAILABLE';
			$value['agent_name'] = '';
			//$value['href'] = URL::build('karaoke_touch',array('cmd'=>'add','karaoke_id'=>$value['karaoke_id'],'table_id'=>$value['id'],'arrival_time'=>$_REQUEST['in_date']));
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
					//$value['href'] = URL::build('karaoke_touch',array('cmd'=>'edit','id'=>$busy_tables[$key]['karaoke_reservation_id']));
				}
				else
				{
					//$value['href'] = URL::build('karaoke_touch',array('cmd'=>'edit','karaoke_id'=>$value['karaoke_id'],'id'=>$busy_tables[$key]['karaoke_reservation_id']));
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
						karaoke_table.id
					from
						karaoke_table
						inner join karaoke_reservation_table on karaoke_reservation_table.table_id = karaoke_table.id
					where karaoke_reservation_table.karaoke_reservation_id = '.$k);
				foreach($table_out_list as $tbl_id=>$tbl)
				{
					if($tbl_id==$key){
						$value['status_tables_others'][$k]['id'] = $k;
						$value['status_tables_others'][$k]['status'] = $v['status'];
					}
				}
			}
			$floors[$value['table_group']]['karaoke_tables'][$i]= $value;
			$i++;
		}
		return $floors;
	}	
	function get_table_conflict($cond=' 1>0'){
		$conflix=DB::fetch('Select 
								karaoke_reservation.* 
							from karaoke_reservation 
								INNER JOIN karaoke_reservation_table ON karaoke_reservation.id = karaoke_reservation_table.karaoke_reservation_id 
							where  '.$cond.' AND karaoke_reservation.portal_id=\''.PORTAL_ID.'\'');
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
	function get_rr_id($id){
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
	function get_total_karaokes_select($karaoke_id = false){
		$karaokes = DB::fetch_all('SELECT * FROM karaoke order by id ASC');
		$items = '<select name="karaoke_id" onchange="ChangeKaraoke();" id="karaoke_id">';
		$items .='<option value="">--Select Karaoke--</option>';	
		foreach($karaokes as $id=>$karaoke){
			if($karaoke['id'] == $karaoke_id){
				$items .= '<option value="'.$karaoke['id'].'" selected="selected">'.$karaoke['name'].'</option>';	
			}else $items .= '<option value="'.$karaoke['id'].'">'.$karaoke['name'].'</option>';	
		}
		$items .='</select>';
		return $items;
	}
	function get_total_karaokes($karaoke_id = false){
		//-------- Phan quyen Karaoke-------------//
		require_once 'packages/hotel/packages/karaoke/includes/table.php';
		$cond_admin = Table::get_privilege_karaoke();
		$karaokes = array();
		if(User::is_admin() || $cond_admin){
			$karaokes = DB::fetch_all('
				SELECT
					karaoke_charge.id,
					karaoke_charge.karaoke_id_from,
					karaoke_charge.percent,
					karaoke.name
				FROM
					karaoke_charge
					INNER JOIN karaoke on karaoke.id = karaoke_charge.karaoke_id_from
				WHERE
					1=1 '.$cond_admin.' AND karaoke_charge.karaoke_id = '.Session::get('karaoke_id').' and karaoke_charge.portal_id = \''.PORTAL_ID.'\' ORDER BY karaoke.ID ASC');
		}
		return $karaokes;
	}
	function get_karaoke_reservation($id)
	{
		return DB::fetch('
			SELECT
				karaoke_reservation.id,
				karaoke.print_kitchen_name,
				karaoke.print_karaoke_name,
				karaoke.name as karaoke_name,
				party.full_name as user_name,
				karaoke_reservation.status
			FROM
				karaoke_reservation
				INNER JOIN karaoke on karaoke_reservation.karaoke_id = karaoke.id
				LEFT OUTER JOIN party on party.user_id = karaoke_reservation.checked_out_user_id AND party.type=\'USER\'
			WHERE
				karaoke_reservation.id='.$id
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
					AND product_price_list.department_code = \''.Session::get('dp_code').'\'
				ORDER BY
					product.name_'.Portal::language().'
		');	
	}
	function get_old_product($karaoke_reservation_id){
		 $items = DB::fetch_all('SELECT 
							CASE
								WHEN (karaoke_reservation_product.product_id=\'FOUTSIDE\' OR karaoke_reservation_product.product_id=\'DOUTSIDE\' OR karaoke_reservation_product.product_id=\'SOUTSIDE\')
								THEN 
									(price_id || \'_\' || karaoke_reservation_product.id)
								ELSE
									price_id
							END as id,
							 karaoke_reservation_product.quantity,
							 karaoke_reservation_product.quantity_discount,
							 karaoke_reservation_product.printed,
							 karaoke_reservation_product.note,
							 karaoke_reservation_product.id as prd_id
						FROM 
							karaoke_reservation_product 
						WHERE karaoke_reservation_id ='.$karaoke_reservation_id);	
			return $items;
	}
	function checkPayment($id){
		$row = DB::fetch('select id,total as amount,checked_out_user_id as user_id,pay_with_room from karaoke_reservation where id='.$id.'');
		if(!empty($row)){
			$payment = DB::fetch_all('select * from payment where type=\'karaoke\' AND bill_id='.$id.'');
			if($row['pay_with_room'] != 1){
				if(empty($payment)){
					DB::insert('payment',array('type'=>'karaoke','bill_id'=>$id,'payment_type_id'=>'CASH','currency_id'=>'VND','exchange_rate'=>1,'portal_id'=>PORTAL_ID,'amount'=>$row['amount'],'time'=>time(),'user_id'=>$row['user_id']));	
				}
			}else{
				if(!empty($payment)){
					foreach($payment as $p =>$pay){
						DB::query('delete from payment WHERE id = '.$p.' AND type=\'karaoke\'');	
					}
				}
			}
		}
	}	
}
?>