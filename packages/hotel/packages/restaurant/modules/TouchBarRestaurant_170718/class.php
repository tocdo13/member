<?php 

class TouchBarRestaurant extends Module
{
	function TouchBarRestaurant($row)
	{
	    /** manh: check last time **/
        if(Url::get('check_last_time')){
            $data = array('status'=>'','user'=>'','time'=>'');
            $last_time = DB::fetch('select last_time,lastest_edited_user_id as user_id from bar_reservation where id='.Url::get('id'));
            if($last_time['last_time']!=0 and $last_time['last_time']>Url::get('last_time')){
                $data = array('status'=>'error','user'=>$last_time['user_id'],'time'=>date('H:i:s d/m/Y',$last_time['last_time']));
            }
            echo json_encode($data);
            exit();
        }
        /** end manh **/
		Module::Module($row);
		require_once 'db.php';
		define('AFTER_TAX',0);// Mac dinh laf giam gia truoc thue va phi dich vu
		$_REQUEST['after_tax'] = AFTER_TAX;
		if((Url::get('bar_id') or Session::get('bar_id')) and $row =DB::fetch('select bar.* from bar where id='.intval(Url::get('bar_id')?Url::get('bar_id'):Session::get('bar_id')).' and portal_id=\''.PORTAL_ID.'\''))
		{//and User::can_admin(false,ANY_CATEGORY)
			Session::set('bar_id',intval(Url::get('bar_id')));
			Session::set('bar_code',$row['code']);
            Session::set('dp_code',$row['department_id']); //
			Session::set('full_rate',$row['full_rate']);
			Session::set('full_charge',$row['full_charge']);
            Session::set('discount_after_tax',$row['discount_after_tax']);
		}
		else if(!Session::is_set('bar_id'))
		{
			require_once 'packages/hotel/includes/php/hotel.php';
			$bar = DB::fetch('select min(id) as id from bar where 1>0 and portal_id=\''.PORTAL_ID.'\'','id');	
			$bar_id = $bar;	
            $bar_code = DB::fetch('select bar.* from bar where id='.$bar_id.' and portal_id=\''.PORTAL_ID.'\'');	
			if($bar_id)
			{
				Session::set('bar_id',$bar_id);
				Session::set('dp_code',$bar_code['department_id']);
                Session::set('bar_code',$bar_code['code']);
				Session::set('full_rate',$bar_code['full_rate']);
				Session::set('full_charge',$bar_code['full_charge']);
                Session::set('discount_after_tax',$bar_code['discount_after_tax']);
			}
			else
			{
				Session::set('bar_id','');
				Session::set('dp_code','');
                Session::set('bar_code','');
				Session::set('full_rate','');
				Session::set('full_charge','');
                Session::set('discount_after_tax','');
			}
		}
		$_REQUEST['bar_id'] = Session::get('bar_id');
		if(User::can_edit(false,ANY_CATEGORY)){
			if(Url::get('category_id') and Url::get('cmd')=='draw_products' && Url::get('bar_id_other')){
				$this->draw_products(Url::get('type'),Url::iget('category_id'),Url::get('bar_id_other'),Url::get('act'));
				exit();
			}else if(Url::get('cmd')=='draw_products' && Url::get('bar_id_other')){
				$this->draw_products(Url::get('type'),Url::get('product_name'),Url::get('bar_id_other'),Url::get('act'));
				exit();
			}
		}
		if(User::can_view(false,ANY_CATEGORY))
		{
			switch(URL::get('cmd'))
			{	
				case 'detail':
                
						require_once 'forms/checkio_detail.php';
						$this->add_form(new DetailBarForm());break;	
            	case 'print':
                
						require_once 'forms/print.php';
						$this->add_form(new PrintBarForm());break;	
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
	function draw_products($type,$value,$bar_id,$action){
		if($type=='CATEGORY'){
			$category_id = $value;
			$cond = ''.IDStructure::child_cond(DB::structure_id('product_category',$category_id)).'';
		}else{
		      if($value!='')
              {
                    $cond = ' ((UPPER(product.name_1) like \'%'.mb_strtoupper($value,'utf-8').'%\' OR UPPER(product.name_2) like \'%'.mb_strtoupper($value,'utf-8').'%\')
        			OR (UPPER(product.id) like \'%'.mb_strtoupper($value,'utf-8').'%\'))';
        			$check= String::vn_str_check($value);
        			if($check==0){
        				$value = String::vn_str_filter($value);
        				$cond .= ' OR ((LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.mb_strtolower($value,'utf-8').'%\' OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.mb_strtolower($value,'utf-8').'%\'))'; 	
        			}
                    $cond_set = ' LOWER(FN_CONVERT_TO_VN(bar_set_menu.name)) like \'%'.mb_strtolower($value,'utf-8').'%\'';
              }
              else
              {
                $cond = '1=1';
                $cond_set = "1=1";
              }
		}
		$cond2 = '';
		if($bar_id !=Session::get('bar_id')){
			$surcharges = DB::fetch('select bar_id_from as id,percent,department_id from bar_charge where bar_id = '.Session::get('bar_id').' AND bar_id_from='.$bar_id.' AND portal_id = \''.PORTAL_ID.'\'');
			$cond2 = ' AND product_price_list.department_code = \''.$surcharges['department_id'].'\'';
            $cond_set2 = ' AND (bar_set_menu.department_code = \'RES\' OR bar_set_menu.department_code = \''.$surcharges['department_id'].'\')';
		}else{
			$cond2 = ' AND product_price_list.department_code = \''.Session::get('dp_code').'\'';	
            $cond_set2 = ' AND (bar_set_menu.department_code = \'RES\' OR bar_set_menu.department_code = \''.Session::get('dp_code').'\')';	
		}
		if($bar_id == Session::get('bar_id')){
			$sql = '
				SELECT
					product_price_list.id as id,product.id as code,product.name_'.Portal::language().' as name,
					product.name_2,
					product_price_list.price, unit.name_'.Portal::language().' as unit,
					pc.structure_id,pc.name AS pc_name,product.category_id,product.unit_id,
                    pc.code as category_code
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
			// get product by bar_id
			$sql = '
				SELECT
					product_price_list.id as id,product.id as code,product.name_'.Portal::language().' as name,
					product.name_2,
					(product_price_list.price + (product_price_list.price * '.$add_charge.')) as price
					,unit.name_'.Portal::language().' as unit,
					pc.structure_id,pc.name AS pc_name,product.category_id,product.unit_id,
                    pc.code as category_code
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
        //date_default_timezone_set('Asia/Saigon');
        $today = Date_Time::to_time(date("d/m/Y"));
        if($value=='set_menu'){
            $sql = "SELECT 
             product_price_list.id as id,
             bar_set_menu.id as bar_set_menu_id,
             bar_set_menu.code as code,
             bar_set_menu.name as name,
             product_price_list.price as price,
             product_price_list.start_date,
             product_price_list.end_date,
             'set' as unit
            FROM 
            bar_set_menu
            INNER JOIN product_price_list ON bar_set_menu.code = product_price_list.product_id AND product_price_list.department_code = bar_set_menu.department_code
            WHERE ".$cond_set.$cond_set2." AND bar_set_menu.portal_id='".PORTAL_ID.'\' AND ( (DATE_TO_UNIX(product_price_list.start_date)<='.$today.' AND '.$today.'<=DATE_TO_UNIX(product_price_list.end_date)) OR (DATE_TO_UNIX(product_price_list.start_date)<='.$today.' AND product_price_list.end_date IS NULL ) OR ( '.$today.'<=DATE_TO_UNIX(product_price_list.end_date) AND product_price_list.start_date IS NULL ) OR ( product_price_list.start_date IS NULL AND product_price_list.end_date IS NULL ))
            ORDER BY bar_set_menu.code';
            
            $result = DB::fetch_all($sql);
            
            $items = $result;
            
         /** Thanh add phan phan loai set menu theo cha - con ( Vi du : code ABC la cha, ABC-00001 la con) **/   
            $j = 1;
            $key = array_keys($items);
            $total = 0;
            reset($items); 
               for($i=0 ; $i< count($items); $i++){ 
                  $current = current($items);
                  $next = next($items);
                  if(!empty($next)){
                   $arr_temp = explode("-",$next['code']);
                  }
                  if(!empty($next) && strpos($next['code'],$current['code'])>=0 && isset($arr_temp[1]) && strlen($arr_temp[1])==5){
                      $j++;
                  }
                  else{
                      if(isset($key[$i-$j+1])){
                          $items[$key[$i-$j+1]]['count'] = $j;
                          $j = 1;
                      }
                      
                  }
               }
             foreach($items as $key=>$value)
             {
                if(!isset($value['count']))
                {
                    unset($items[$key]);
                }
                else if($value['count']==1)
                {
                    if(empty($value['start_date']))
                    {
                      $value['start_date'] = '01-JUN-1970';  
                    }
                    if(empty($value['end_date']))
                    {
                      $value['end_date'] = '01-JUN-2030';  
                    }
                    $start_time = Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['start_date'],"/"));
                    $end_time = Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['end_date'],"/"));
                    $current_time = Date_Time::to_time(date("d/m/Y"));
                    if(!($start_time<=$current_time && $current_time<=$end_time))
                    {
                        unset($items[$key]);
                    }
                }
             }    
         /** END **/                 
        }
        else if($type=='PRODUCT'){
            $sql = "SELECT 
             bar_set_menu.id || '-' || bar_set_menu.code as id,
             bar_set_menu.id as bar_set_menu_id,
             bar_set_menu.code as code,
             bar_set_menu.name as name,
             product_price_list.price as price,
             'set' as unit
            FROM bar_set_menu
            INNER JOIN product_price_list ON bar_set_menu.code = product_price_list.product_id AND product_price_list.department_code = bar_set_menu.department_code
            WHERE ".$cond_set.$cond_set2." AND bar_set_menu.portal_id='".PORTAL_ID."'";
            $result = DB::fetch_all($sql);
            $items += $result;
        }
        
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
        echo '<script src="cache/data/'.str_replace("#","",PORTAL_ID).'/RES_'.str_replace("#","",PORTAL_ID).'.js?v='.time().'"></script>'; 
        echo '<script>product_array_temp = product_array</script>';
		if($bar_id != Session::get('bar_id')){
		    
			echo '<script src="cache/data/'.str_replace("#","",PORTAL_ID).'/'.$surcharges['department_id'].'_'.str_replace("#","",PORTAL_ID).'.js?v='.time().'"></script>';
            echo '<script>jQuery(\'#restaurant_other\').css(\'display\',\'block\');jQuery(\'#div_add_charge\').css(\'display\',\'block\');jQuery(\'#add_charge\').html('.$surcharges['percent'].');
                  product_array = Object.assign({},product_array, product_array_temp);  
                  </script>';	
		}else{
			echo '<script src="cache/data/'.str_replace("#","",PORTAL_ID).'/'.Session::get('dp_code').'_'.str_replace("#","",PORTAL_ID).'.js?v='.time().'"></script>';
		    echo '<script>
                  product_array = Object.assign({},product_array, product_array_temp);  
                  </script>';  
        }
		if($action =='this_bar' or $action =='bar'){ //$bar_id != Session::get('bar_id') || 
			$food_categories = TouchBarRestaurantDB::select_list_food_category($bar_id);
			$other_categories = TouchBarRestaurantDB::select_list_other_category($bar_id);
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
				echo '<li id="product_'.$itm['id'].'" class="product-list" title="'.ucfirst($itm['name']).'" onclick=" SelectedItems(\''.$itm['id'].'\',0,\''.((isset($itm['count']) && $itm['count']>1)?"set":"").'\');"><span class="product-name">'.ucfirst($itm['name']).'</span><br>'.System::display_number($itm['price']).'</li>';
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
			$this->add_form(new TouchBarRestaurantForm());
		}	
		else
		{
			Url::access_denied();
		}
	}
	function print_to_kitchen($act)
	{
		if(Url::get('id') and $row = TouchBarRestaurantDB::get_bar_reservation(Url::get('id')) and ($row['print_kitchen_name'] or $row['print_bar_name']))
		{
			if($act=='kitchen')
			{
				$products = TouchBarRestaurantDB::get_reservation_product(' AND product.type=\'PRODUCT\' AND ((bar_reservation_product.quantity-bar_reservation_product.printed)>0)');
			}
			else
			{
				$products = TouchBarRestaurantDB::get_reservation_product(' AND product.type<>\'PRODUCT\' AND product.type<>\'SERVICE\' AND ((bar_reservation_product.quantity-bar_reservation_product.printed)>0)');
			}
			if($act=='kitchen')
			{			
				$product_remain = TouchBarRestaurantDB::get_reservation_product(' AND product.type=\'PRODUCT\' AND (bar_reservation_product.remain-DECODE(bar_reservation_product.printed_cancel,null,0,bar_reservation_product.printed_cancel))>0');
			}
			else
			{
				$product_remain = TouchBarRestaurantDB::get_reservation_product(' AND product.type<>\'PRODUCT\' AND product.type<>\'SERVICE\' AND (bar_reservation_product.remain-DECODE(bar_reservation_product.printed_cancel,null,0,bar_reservation_product.printed_cancel))>0');				
			}
            $group_prduct=array();
            foreach($products as $key=>$value)
			{   
    			 if($value['stt_order']==''){
    			      $products[$key]['stt_order']='1'; 
    			 }	 
			}
            foreach($product_remain as $key=>$value)
			{   
    			 if($value['stt_order']==''){
    			      $product_remain[$key]['stt_order']='1'; 
    			 }	 
			}
            foreach($products as $key=>$value){
			 array_push($group_prduct,$products[$key]['stt_order']); 
			 $products[$value['stt_order'].'_'.$key]=$value;
             unset($products[$key]);
			}
            foreach($product_remain as $key=>$value){
			 array_push($group_prduct,$product_remain[$key]['stt_order']); 
			 $product_remain[$value['stt_order'].'_'.$key]=$value;
             unset($product_remain[$key]);
			}
            $group_prduct=array_unique($group_prduct);
            sort($group_prduct);
            ksort($products);
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
                        $product_tmp[$key]['stt_order'] = $value['stt_order'];
						DB::query('Update bar_reservation_product set bar_reservation_product.printed = '.($value['quantity']).',bar_reservation_product.note=\'\' where id='.substr($key,2,strlen($key)-2).'');	
					}
				}
			}
			foreach($product_remain as $k=>$v)
			{
				if($v['remain'] - $v['printed_cancel']>0)
				{
					$product_tmp[$k]['id'] = $k;
					$product_tmp[$k]['name'] = $v['name'];
                    $product_tmp[$k]['stt_order'] = $v['stt_order'];
					$product_tmp[$k]['remain'] = $v['remain'] - $v['printed_cancel'];
					DB::query('Update bar_reservation_product set bar_reservation_product.printed_cancel = '.$v['remain'].',bar_reservation_product.printed=bar_reservation_product.printed-'.$product_tmp[$k]['remain'].' where id='.substr($k,2,strlen($k)-2).'');
				}
			}
			$products = $product_tmp;
			$tables = TouchBarRestaurantDB::get_bar_table($row['id']);
			$table_name = '';
			$i=1;
			foreach($tables as $key=>$value)
			{
				if($i>1)
				{
					$table_name.= ','.$value['name'];
				}
				else
				{
					$table_name.= $value['name'];
				}
				$i++;
			}
			require_once 'packages/core/includes/utils/printer.php';
            $data = array();
			if($products and (($row['print_kitchen_name'] and $act=='kitchen') or ($row['print_bar_name'] and $act=='bar')))
			{
			    $data_array=array();
                    $data_array['bar_id']=Url::get('bar_id');
                    $data_array['table_id']=Url::get('table_id');
                    $data_array['user_id']=User::id();
                    $data_array['order_code']=$row['id'];
                    $data_array['time']=time();
                    $order_detail_id= DB::insert('order_detail',$data_array); 
				if($act=='kitchen')
				{
				    $data['ptinter'] = $row['print_kitchen_name'];
					//$printer = new Printer($row['print_kitchen_name'],array());
				}
				else
				{   
				    $data['ptinter'] = $row['print_bar_name'];
					//$printer = new Printer($row['print_bar_name'],array());
				}
                $data['group_product'] = $group_prduct;
                $data['products'] = $products;
                $data['bar_reservation_id'] = $row['id'];
                //echo  Date_Time::to_time(date('d/m/Y'))+12*3600;exit();
                $data['order_number']=DB::fetch('select max(order_number) as id from bar_reservation where bar_id='.Url::get('bar_id').' and time>='.(Date_Time::to_time(date('d/m/Y'))).'','id');
                if(!$data['order_number']){
                    $data['order_number']=1;
                }else{
                    $data['order_number']++;
                }
                DB::update('bar_reservation',array('order_number'=>$data['order_number']),'id='.$row['id'].'');
                $data['bar_name'] = $row['bar_name'];
                $data['table_name'] = $table_name;
                $user_data = Session::get('user_data');
                $data['user_id'] = isset($user_data['full_name'])?$user_data['full_name']:Session::get('user_id');
                //$arr = explode(":",HOTEL_DDNS);
                //$ip = gethostbyname($arr[0]);
                //$link = $ip.":".$arr[1];
                //$response = http_post_fields("http://".$link."/print_order.php", $data, array());
                if($act=='kitchen')
				{
				    $data['stt'] = 1;
				    //$response = http_post_fields("http://118.70.151.38:8000/print_order.php", $data, array());
				}
				else
				{   
				    $data['stt'] = 1;
				    //$response = http_post_fields("http://118.70.151.38:8000/print_order.php", $data, array());
				}
                //echo 'Product sent to kitchen success!';
			}
			else
			{
				// 'No product sent to kitchen!';
			}
		}
	}
}
?>
