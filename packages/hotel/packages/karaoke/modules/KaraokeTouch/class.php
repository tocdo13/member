<?php 
class KaraokeTouch extends Module
{
	function KaraokeTouch($row)
	{
		Module::Module($row);
		require_once 'db.php';
		define('AFTER_TAX',0);// Mac dinh laf giam gia truoc thue va phi dich vu
		$_REQUEST['after_tax'] = AFTER_TAX;
		if((Url::get('karaoke_id') or Session::get('karaoke_id')) and $row =DB::fetch('select karaoke.* from karaoke where id='.intval(Url::get('karaoke_id')?Url::get('karaoke_id'):Session::get('karaoke_id')).' and portal_id=\''.PORTAL_ID.'\''))
		{//and User::can_admin(false,ANY_CATEGORY)
			Session::set('karaoke_id',intval(Url::get('karaoke_id')));
			Session::set('karaoke_code',$row['code']);
            Session::set('dp_code',$row['department_id']); //
			Session::set('full_rate',$row['full_rate']);
			Session::set('full_charge',$row['full_charge']);
		}
		else if(!Session::is_set('karaoke_id'))
		{
			require_once 'packages/hotel/includes/php/hotel.php';
			$karaoke = DB::fetch('select min(id) as id from karaoke where 1>0 and portal_id=\''.PORTAL_ID.'\'','id');	
			$karaoke_id = $karaoke;	
            $karaoke_code = DB::fetch('select karaoke.* from karaoke where id='.$karaoke_id.' and portal_id=\''.PORTAL_ID.'\'');	
			if($karaoke_id)
			{
				Session::set('karaoke_id',$karaoke_id);
				Session::set('dp_code',$karaoke_code['department_id']);
                Session::set('karaoke_code',$karaoke_code['code']);
				Session::set('full_rate',$karaoke_code['full_rate']);
				Session::set('full_charge',$karaoke_code['full_charge']);
			}
			else
			{
				Session::set('karaoke_id','');
				Session::set('dp_code','');
                Session::set('karaoke_code','');
				Session::set('full_rate','');
				Session::set('full_charge','');
			}
		}
		$_REQUEST['karaoke_id'] = Session::get('karaoke_id');
		if(User::can_edit(false,ANY_CATEGORY)){
			if(Url::get('category_id') and Url::get('cmd')=='draw_products' && Url::get('karaoke_id_other')){
				$this->draw_products(Url::get('type'),Url::iget('category_id'),Url::get('karaoke_id_other'),Url::get('act'));
				exit();
			}else if(Url::get('cmd')=='draw_products' && Url::get('karaoke_id_other')){
				$this->draw_products(Url::get('type'),Url::get('product_name'),Url::get('karaoke_id_other'),Url::get('act'));
				exit();
			}
		}
		if(User::can_view(false,ANY_CATEGORY))
		{
			switch(URL::get('cmd'))
			{	
				case 'detail':
						require_once 'forms/checkio_detail.php';
						$this->add_form(new DetailKaraokeForm());break;	
            	case 'print':
						require_once 'forms/print.php';
						$this->add_form(new PrintKaraokeForm());break;	
				case 'print_kitchen':
					$this->print_to_kitchen(Url::get('act'));exit();break;							
				default: 
					$this->list_cmd();
					break;
			}
		}
		else
		{
			URL::access_denied();
		}
	}
	function draw_products($type,$value,$karaoke_id,$action){
		if($type=='CATEGORY'){
			$category_id = $value;
			$cond = ''.IDStructure::child_cond(DB::structure_id('product_category',$category_id)).'';
		}else{
			$cond = ' ((UPPER(product.name_1) like \'%'.mb_strtoupper($value,'utf-8').'%\' OR UPPER(product.name_2) like \'%'.mb_strtoupper($value,'utf-8').'%\')
			OR (UPPER(product.id) like \'%'.mb_strtoupper($value,'utf-8').'%\'))';
			$check= String::vn_str_check($value);
			if($check==0){
				$value = String::vn_str_filter($value);
				$cond .= ' OR ((LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.mb_strtolower($value,'utf-8').'%\' OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.mb_strtolower($value,'utf-8').'%\'))'; 	
			}
		}
		$cond2 = '';
		if($karaoke_id !=Session::get('karaoke_id')){
			$surcharges = DB::fetch('select karaoke_id_from as id,percent,department_id from karaoke_charge where karaoke_id = '.Session::get('karaoke_id').' AND karaoke_id_from='.$karaoke_id.' AND portal_id = \''.PORTAL_ID.'\'');
			$cond2 = ' AND product_price_list.department_code = \''.$surcharges['department_id'].'\'';
		}else{
			$cond2 = ' AND product_price_list.department_code = \''.Session::get('dp_code').'\'';
		}
		if($karaoke_id == Session::get('karaoke_id')){
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
					('.$cond.')
					'.$cond2.'
					AND (product.type = \'GOODS\' OR product.type = \'PRODUCT\' OR product.type = \'DRINK\'  OR product.type = \'SERVICE\')
					AND product_price_list.portal_id = \''.PORTAL_ID.'\'
					AND (product_price_list.end_date is null OR product_price_list.end_date>=\''.Date_Time::to_orc_date(date('d/m/y')).'\')
				ORDER BY
					product.name_'.Portal::language().' ';
		}else{
			if($surcharges['percent']==''){
				$surcharges['percent'] = 0;	
			}
			$add_charge = $surcharges['percent'] * 0.01;	
			// get product by karaoke_id
			$sql = '
				SELECT
					product_price_list.id as id,product.id as code,product.name_'.Portal::language().' as name,
					product.name_2,
					(product_price_list.price + (product_price_list.price * '.$add_charge.')) as price
					,unit.name_'.Portal::language().' as unit,
					pc.structure_id,pc.name AS pc_name,product.category_id,product.unit_id
				FROM 
					product_price_list
					INNER JOIN product ON product_price_list.product_id = product.id
					INNER JOIN product_category pc ON pc.id = product.category_id
					INNER JOIN unit ON unit.id = product.unit_id
				WHERE 
					('.$cond.')
					'.$cond2.'
					AND (product.type = \'GOODS\' OR product.type = \'PRODUCT\' OR product.type = \'DRINK\'  OR product.type = \'SERVICE\')
					AND product_price_list.portal_id = \''.PORTAL_ID.'\'
					AND (product_price_list.end_date is null OR product_price_list.end_date>=\''.Date_Time::to_orc_date(date('d/m/y')).'\')
				ORDER BY
					product.name_'.Portal::language().' ';	
		}
		$items = DB::fetch_all($sql);
		//echo '<div id="bound_product">';
		if(!empty($items)){  
			$temp = '';
		echo '<script src="packages/core/includes/js/jquery/jquery.cookie.js"></script><script src="packages/core/includes/js/jquery/paging/easypaginate.js"> </script>';
		if(defined('IMENU') and IMENU)
		{
			echo '<script>paging(6);</script>';	
		}
		else
		{
			echo '<script>paging(24);</script>';	
		}
		if($karaoke_id != Session::get('karaoke_id')){
			echo '<script src="cache/data/'.str_replace("#","",PORTAL_ID).'/'.$surcharges['department_id'].'_'.str_replace("#","",PORTAL_ID).'.js?v='.time().'"></script>';
			echo '<script>jQuery(\'#karaoke_other\').css(\'display\',\'block\');jQuery(\'#div_add_charge\').css(\'display\',\'block\');jQuery(\'#add_charge\').html('.$surcharges['percent'].');</script>';	
		}else{
			echo '<script src="cache/data/'.str_replace("#","",PORTAL_ID).'/'.Session::get('dp_code').'_'.str_replace("#","",PORTAL_ID).'.js?v='.time().'"></script>';
		}
		if($action =='this_karaoke' or $action =='karaoke'){ //$karaoke_id != Session::get('karaoke_id') || 
			$food_categories = KaraokeTouchDB::select_list_food_category($karaoke_id);
			$other_categories = KaraokeTouchDB::select_list_other_category($karaoke_id);
			echo '<script>jQuery(\'#div_food_category\').html(\''.$food_categories.'\');jQuery(\'#info_summary\').html(\''.$other_categories.'\');</script>';	
		}
		echo '<ul id="bound_product_list">';
		if(defined('IMENU') and IMENU)
		{
			foreach($items as $id => $itm){
				echo '<li id="product_'.$itm['id'].'" class="product-list-imenu" title="'.ucfirst($itm['name']).'" onclick="SelectedItems(\''.$itm['id'].'\',0);">
						'.$itm['code'].' - '.System::display_number($itm['price']).'<br><img src="'.$itm['image_url'].'"><br>'.ucfirst($itm['name']).'<br>'.ucfirst($itm['name_2']).'<input name="items" type="hidden" id="items_'.$itm['id'].'" /></li>';
			}
		}
		else
		{
			foreach($items as $id => $itm){
				echo '<li id="product_'.$itm['id'].'" class="product-list" title="'.ucfirst($itm['name']).'" onclick=" SelectedItems(\''.$itm['id'].'\',0);"><span class="product-name">'.ucfirst($itm['name']).'</span><br>'.System::display_number($itm['price']).'</li>';
				//echo '<input name="items" type="text" value="var items = '.String::array2js($itm).'" id="items_'.$itm['id'].'" />';	
			}
		}
			echo '</ul>';
		}else echo '<div id="alert" class="notice">'.Portal::language('has_no_item_to_be_found').' !</div>';
		//echo '</div>';
		return $items;
	}
	function list_cmd()
	{
		if(User::can_view(false,ANY_CATEGORY))
		{
			require_once 'forms/list.php';
			$this->add_form(new KaraokeTouchForm());
		}	
		else
		{
			Url::access_denied();
		}
	}
	function print_to_kitchen($act)
	{
		if(Url::get('id') and $row = KaraokeTouchDB::get_karaoke_reservation(Url::get('id')) and $row['print_karaoke_name'])
		{
			if($act=='kitchen')
			{
				$products = KaraokeTouchDB::get_reservation_product(' AND product.type=\'PRODUCT\' AND ((karaoke_reservation_product.quantity-karaoke_reservation_product.printed)>0)');
			}
			else
			{
				$products = KaraokeTouchDB::get_reservation_product(' AND product.type<>\'PRODUCT\' AND product.type<>\'SERVICE\' AND ((karaoke_reservation_product.quantity-karaoke_reservation_product.printed)>0)');
			}
			if($act=='kitchen')
			{			
				$product_remain = KaraokeTouchDB::get_reservation_product(' AND product.type=\'PRODUCT\' AND (karaoke_reservation_product.remain-DECODE(karaoke_reservation_product.printed_cancel,null,0,karaoke_reservation_product.printed_cancel))>0');
			}
			else
			{
				$product_remain = KaraokeTouchDB::get_reservation_product(' AND product.type<>\'PRODUCT\' AND product.type<>\'SERVICE\' AND (karaoke_reservation_product.remain-DECODE(karaoke_reservation_product.printed_cancel,null,0,karaoke_reservation_product.printed_cancel))>0');				
			}
			$product_tmp = array();
			foreach($products as $key=>$value)
			{
				if(isset($product_tmp[$key])){
					$product_tmp[$key]['quantity'] += $value['quantity'];
					$product_tmp[$key]['quantity_discount'] += $value['quantity_discount'];
				}
				else
				{
					if(($value['quantity'] - $value['printed'])>0 ){
						$product_tmp[$key]['id'] = $key;			
						$product_tmp[$key]['product_id'] = $value['product_id'];
						$product_tmp[$key]['name'] = $value['name'];
						$product_tmp[$key]['quantity'] = $value['quantity']-$value['printed'];
						$product_tmp[$key]['quantity_discount'] = $value['quantity_discount'];
						$product_tmp[$key]['quantity_cancel'] = $value['quantity_cancel'];
						$product_tmp[$key]['unit_name'] = $value['unit_name'];
						$product_tmp[$key]['price'] = $value['price'];
						$product_tmp[$key]['discount_rate'] = $value['discount_rate'];
						$product_tmp[$key]['discount_category'] = $value['discount_category'];
						$product_tmp[$key]['printed'] = $value['quantity'] - $value['printed'];
						$product_tmp[$key]['note'] = $value['note'];
						DB::query('Update karaoke_reservation_product set karaoke_reservation_product.printed = '.($value['quantity']).',karaoke_reservation_product.note=\'\' where id='.$key.'');	
					}
				}
			}
			foreach($product_remain as $k=>$v)
			{
				if($v['remain'] - $v['printed_cancel']>0)
				{
					$product_tmp[$k]['id'] = $k;
					$product_tmp[$k]['name'] = $v['name'];
					$product_tmp[$k]['remain'] = $v['remain'] - $v['printed_cancel'];
					DB::query('Update karaoke_reservation_product set karaoke_reservation_product.printed_cancel = '.$v['remain'].',karaoke_reservation_product.printed=karaoke_reservation_product.printed-'.$v['remain'].' where id='.$k.'');
				}
			}
			$products = $product_tmp;
			$tables = KaraokeTouchDB::get_karaoke_table($row['id']);
			$table_name = '';
			$i=1;
			foreach($tables as $key=>$value)
			{
				if($i>1)
				{
					$table_name.= ','.$value['code'];
				}
				else
				{
					$table_name.= $value['code'];
				}
				$i++;
			}
			require_once 'packages/core/includes/utils/printer.php';
			if($products and ($row['print_kitchen_name'] or $row['print_karaoke_name']))
			{
				if($act=='kitchen')
				{
					$printer = new Printer($row['print_kitchen_name'],array());
				}
				else
				{
					$printer = new Printer($row['print_karaoke_name'],array());
				}
				$printer->write_r($products,$row['id'],$table_name,$row['karaoke_name']);
				echo 'Product sent to kitchen success!';
			}
			else
			{
				echo 'No product sent to kitchen!';
			}
		}
	}	
}
?>