<?php
class TouchBarRestaurantForm extends Form
{    
	function TouchBarRestaurantForm()
	{
		Form::Form('TouchBarRestaurantForm');
		$this->link_css(Portal::template('hotel').'/css/room.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_css('packages/core/includes/js/jquery/keyboard/style.css');
		//$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/keyboard/keyboard.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.jcarousel.min.js');
		//
		$this->link_js('packages/hotel/packages/vending/includes/js/update_price_new.js');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.cookie.js');
		$this->link_js('packages/core/includes/js/jquery/paging/easypaginate.js');
		$this->link_js('packages/hotel/packages/vending/modules/AutomaticVend/bar_reservation.js');
		$this->link_js('packages/core/includes/js/jquery/window/jquery.window.js');
		//Dung cho folio
		$this->link_js('packages/hotel/packages/vending/includes/js/jquery.windows-engine.js');
        $this->link_js('packages/hotel/packages/vending/includes/js/common01.js');
        
        $this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");	
			  
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		//end
		$this->link_css('packages/hotel/skins/default/css/restaurant.css');
		$this->link_css('packages/hotel/packages/vending/skins/default/css/jcarosel.css');
		$this->add('arrival_date',new DateType(true,'invalid_arrival_time'));
		$this->full_rate = 0;
		$this->full_charge= 0;	
        $this->ve_res_id = 0;	
	}
	function on_submit()
	{
		if(Url::get('act') != '' || Url::get('acction') == 1 || Url::get('cancel'))
        {
    		require_once 'packages/hotel/packages/restaurant/includes/table.php';
    		if($this->check())
            {
    			$merge = false;
    			$log_product = '';
    			$log_table = '';
    			$ids = '';
                if(Url::get('product_list'))
                {				
    				$bar_reservation_products = array();
    				$bar_reservation_products = Url::get('product_list');
    				foreach($bar_reservation_products as $k => $p)
                    {
    					$ids .= ($ids=='')?$p['price_id']:(','.$p['price_id']);
    				}
    				$products_select = AutomaticVendDB::get_product_select($ids);
    			}
    			$array = array();
    			$status = 'CHECKIN'; 
                			
    			$exchange_rate = 0;
                
                $customer = Url::get('customer_id')?DB::fetch('select * from vending_customer where id = '.Url::get('customer_id')):array();
    			$array += array(						
    					'status'=>$status, 
    					'full_rate'=>Url::get('input_full_rate')?Url::get('input_full_rate'):0,
    					'full_charge'=>Url::get('input_full_charge')?Url::get('input_full_charge'):0,
    					'tax_rate'=>Url::get('tax_rate')?Url::get('tax_rate'):0,
    					'bar_fee_rate'=>Url::get('service_charge')?Url::get('service_charge'):0,
                        'receiver_name'=>Url::get('receiver_name'),
    					'customer_id'=>Url::get('customer_id')?Url::get('customer_id'):'',
    					'agent_name'=>(!empty($customer))?$customer['name']:Url::get('customer_name'), 
                        'agent_address'=>(!empty($customer))?$customer['address']:Url::get('customer_address'),
                        'tax_code'=>(!empty($customer))?$customer['tax_code']:Url::get('tax_code'),
                        'note'=>Url::get('note')?Url::get('note'):'', 
    					'deposit'=>Url::get('deposit')?System::calculate_number(Url::get('deposit')):0,
    					'discount_percent'=>Url::get('discount_percent')?Url::get('discount_percent'):0,
    					'discount'=>Url::get('discount')?System::calculate_number(Url::get('discount')):0,
    					'total_before_tax'=>Url::get('total_amount')?System::calculate_number(Url::get('total_amount')):0,
                        'total'=>Url::get('total_payment')?System::calculate_number(Url::get('total_payment')):0,
                        'is_debit'=>Url::get('is_debit')?1:0,
                        'foc'=>Url::get('foc')?1:0,
                        'department_id'=>AutomaticVend::$department_id,
                        'department_code'=>AutomaticVend::$department_id?DB::fetch('select * from department where id = '.AutomaticVend::$department_id,'code'):'',
    			);	
                if( Url::get('cmd') =='add' )
                {
    				$array += array(						
    						'arrival_time'=>time(),
    						'user_id'=>Session::get('user_id'),	
                            'time_in'=>time(),
    					);	
                }
                
                if( Url::get('cmd') =='edit' )
                {
    				$array += array(						
    					'lastest_edited_user_id'=>Session::get('user_id'),	
    					'lastest_edited_time'=>time(),	
    					);	
                }
    			if(HOTEL_CURRENCY == 'VND')
                {
    				$exchange_currency_id = 'USD';
    			}
                else
                {
    				$exchange_currency_id = 'VND';	
    			}
    			$array += array('exchange_rate'=>DB::fetch('SELECT exchange FROM currency WHERE id=\''.$exchange_currency_id.'\'','exchange'));
                
                $row = array();
                if($row = $this->check_edit())
                { 
    				if((Url::get('act')!='') || (Url::get('acction')==1) || Url::get('cancel'))
                    {
                        //check xem da thanh toan het chua
                        //update payment_stautus
                        if($array['total'] > ($row['total_paid'] + $row['deposit']))
                        {
                            $array['payment_status'] = 0;
                        }
    					$bar_reservation_id = $row['id'];
                        $this->ve_res_id = $bar_reservation_id;
    					
                        $code = '';
    					$leng = strlen($bar_reservation_id);
    					for($j=0;$j<6-$leng;$j++)
                        {
    						$code .= '0';	
    					}
    					$code = date('Y').'-'.$code.$bar_reservation_id;
    					$array += array('code'=>$code);
                        DB::update_id('ve_reservation',$array,$row['id']);
    					$old_product_ids = AutomaticVendDB::get_old_product($bar_reservation_id);
                        $j=0;$x=0;
    					$arr_price_ids = '(\'0\'';
    					foreach($bar_reservation_products as $k =>$value)
                        {
    						$arr_price_ids .= ','.'\''.(($value['brp_id']!='')?($value['brp_id']):0).'\'';
    						$j++;
    					}
                        $arr_price_ids .= ')';
    					DB::delete('ve_reservation_product','bar_reservation_id = '.$bar_reservation_id.' AND id not in '.$arr_price_ids.''); 
    					
                        foreach($bar_reservation_products as $id =>$value)
                        {
    						$remaiin = 0;
    						$data['bar_reservation_id'] = $bar_reservation_id;
    						$data['product_id'] = $value['product_id'];
    						$data['quantity'] = System::calculate_number($value['quantity']);
    						$data['discount_rate'] = System::calculate_number($value['percentage']);
                            $data['quantity_discount'] = System::calculate_number($value['quantity_discount']);
                            $data['promotion'] = System::calculate_number($value['promotion']);
    						$data['price'] =  String::convert_to_vnnumeric($value['price']);
    						$data['price_id'] = $value['price_id'];
    						$data['unit_id'] = $value['unit_id'];
    						$data['name'] = $value['name'];
    						$data['note'] = $value['note'];
                            $data['department_id'] = $value['department_id'];
    						$data['discount_category'] = $value['discount_category']; 
    						if(isset($products_select[$data['price_id']]))
                            {
    							$data['product_price'] = $products_select[$data['price_id']]['price'];	
    						}
    						$prd_id = $value['brp_id'];
    						if(isset($old_product_ids[$id]['id']))
                            {	
    							$bar_reservation_product_id = DB::update('ve_reservation_product',$data,'bar_reservation_id = '.$bar_reservation_id.' AND id = '.$prd_id.'');
                                $log_product .= 'Change Product: '.$data['product_id'].' ( '.$data['name'].' ): SL : '.$old_product_ids[$id]['quantity'].' -> '.$data['quantity'].' Department id : '.$data['department_id'].'<br>';
                                unset($old_product_ids[$id]);
    						}
                            else
                            {
    							$bar_reservation_product_id = DB::insert('ve_reservation_product',$data);
                                $log_product .= 'Insert Product: '.$data['product_id'].' ( '.$data['name'].' ): SL : '.$data['quantity'].' Department id : '.$data['department_id'].'<br>';		
    						}  
    					}
                        foreach($old_product_ids as $k => $v)
                        {
                            $log_product .= 'Delete Product: '.$v['product_id'].' ( '.$v['name'].' ): SL : '.$v['quantity'].' Department id : '.$v['department_id'].'<br>';
                        }
    				}
    				$title = 'Edit vending reservation , Code: '.$row['id'];
    				$description = ''
                    .Portal::language('total').':'.System::display_number($row['total']).' -> '.System::display_number($array['total']).'<br>'   
					.Portal::language('note').':'.Url::get('note').'<br>' 
                    .Portal::language('department_id').':'.AutomaticVend::$department_id.' ( '.DB::fetch('select * from department where id = '.AutomaticVend::$department_id,'name_'.Portal::language()).' )<br>' 
                    .'<hr>'.$log_product.'';
    				System::log('edit',$title,$description,$row['id'],'VENDING');  
    			}
                else
                {
    				$bar_reservation_id = DB::insert('ve_reservation',$array+array('time'=>time(),'portal_id'=>PORTAL_ID));
                    $this->ve_res_id = $bar_reservation_id;
    				
                    $_REQUEST['id'] = $bar_reservation_id;
    				$code = '';
    				$leng = strlen($bar_reservation_id);
    				for($j=0;$j<6-$leng;$j++)
                    {
    					$code .= '0';	
    				}
    				$code = date('Y').'-'.$code.$bar_reservation_id;//Session::get('bar_code')
                    
    				DB::update('ve_reservation',array('code'=>$code),'id='.$bar_reservation_id);
                    
    				foreach($bar_reservation_products as $id =>$value)
                    {
    					$data['bar_reservation_id'] = $bar_reservation_id;
    					$data['product_id'] = $value['product_id'];
    					$data['quantity'] = System::calculate_number($value['quantity']);
    					$data['discount_rate'] = System::calculate_number($value['percentage']);
                        $data['quantity_discount'] = System::calculate_number($value['quantity_discount']);
                        $data['promotion'] = System::calculate_number($value['promotion']);
    					$data['price'] =  String::convert_to_vnnumeric($value['price']);
    					$data['price_id'] = $value['price_id'];
    					$data['unit_id'] = $value['unit_id'];
    					$data['note'] = $value['note'];
    					$data['name'] = $value['name'];
                        $data['department_id'] = $value['department_id'];
    					$data['discount_category'] = $value['discount_category']; 
    					if(isset($products_select[$data['price_id']])){
    						$data['product_price'] = $products_select[$data['price_id']]['price'];	
    					}
    					$bar_reservation_product_id = DB::insert('ve_reservation_product',$data);
    					$log_product .= 'Insert Product: '.$data['product_id'].' ( '.$data['name'].' ): SL : '.$data['quantity'].' Department id : '.$data['department_id'].'<br>';
    				}
					$title = 'Add vending reservation , Code: '.$bar_reservation_id;
					$description = ''
					.Portal::language('total').':'.Url::get('total_payment').'<br>' 
					.Portal::language('note').':'.Url::get('note').'<br>'
                    .Portal::language('department_id').':'.AutomaticVend::$department_id.' ( '.DB::fetch('select * from department where id = '.AutomaticVend::$department_id,'name_'.Portal::language()).' )<br>' 
					.'<hr>'.$log_product.'';
					System::log('add',$title,$description,$bar_reservation_id,'VENDING');
    			}
    			require_once 'packages/hotel/includes/php/product.php';
                DeliveryOrders::auto_export_warehouse($this->ve_res_id,'VENDING');
    		}          	
            Url::redirect('automatic_vend',array('cmd' => 'edit','id'=>($this->ve_res_id?$this->ve_res_id:Url::get('id')),'department_id' => AutomaticVend::$department_id));
            //echo "<script>window.open('".Url::build('automatic_vend',array('cmd'=>'detail',md5('act')=>md5('print'),md5('preview')=>1,'id'=>($this->ve_res_id?$this->ve_res_id:Url::get('id'))))."','_blank');</script>";
		}
            //echo "<script>window.open('".Url::build('vending_reservation')."','_self');</script>";
           
	}
	function draw()
    {
		$this->map = array();
        $this->map['department_id'] = AutomaticVend::$department_id;
        $this->map['department_name'] = DB::fetch('select id, name_'.Portal::language().' from department where id = '.$this->map['department_id'],'name_'.Portal::language());
        require_once 'packages/hotel/packages/vending/includes/php/vending.php';
        $bars = get_area_vending('1 = 1',true,$this->map['department_id']);
        $this->map['bars'] = $bars;
		$categories = $this->get_list_other_category($this->map['department_id']);
        $this->map['categories'] = $categories;
		$food_categories = $this->select_list_food_category($this->map['department_id']);
		$this->map['food_categories'] = $food_categories;
        
		$amount = 0;
        $total_amount = 0;
        		
		$curr = HOTEL_CURRENCY;
		if(Url::get('cmd') == 'add')
        {
			//-----------------------------------------------Thoi gian---------------------------------------------------//
			$default_time_in_hour = date('H',time());
			$default_time_in_munite = date('i',time());
    		$tax_rate = VENDING_TAX_RATE ? VENDING_TAX_RATE : 0;
    		$service_charge = VENDING_SERVICE_CHARGE ? VENDING_SERVICE_CHARGE : 0;	
            $this->full_rate= VENDING_FULL_RATE ? VENDING_FULL_RATE : 0;
    		$this->full_charge = VENDING_FULL_CHARGE ? VENDING_FULL_CHARGE : 0;	
		}
        else if($old_item = $this->check_edit()) //truong hop edit
        {
			$default_time_in_hour = date('H',$old_item['arrival_time']);
			$default_time_in_munite =date('i',$old_item['arrival_time']);
    		$tax_rate = $old_item['tax_rate']?$old_item['tax_rate']:0;
    		$service_charge = $old_item['bar_fee_rate']?$old_item['bar_fee_rate']:0;	
            $this->full_rate= $old_item['full_rate']?$old_item['full_rate']:0;
    		$this->full_charge = $old_item['full_charge']?$old_item['full_charge']:0;	
		}
        $this->map['full_charge'] = $this->full_charge;
		$this->map['full_rate'] = $this->full_rate;
        
    	$this->map['arrival_time_in_hour'] = $default_time_in_hour;
		$this->map['arrival_time_in_munite'] = $default_time_in_munite;
        $_REQUEST['arrival_time_in_hour'] = $this->map['arrival_time_in_hour'];
        $_REQUEST['arrival_time_in_munite'] = $this->map['arrival_time_in_munite'];
		$arr = array();
		if($row = $this->check_edit())
        {
            //System::debug($row);
			if($row['bar_fee_rate']=='')
				$row['bar_fee_rate']=0;
			if($row['tax_rate']=='')
				$row['tax_rate']=0;
			$sql = '
					SELECT 
						ve_reservation_product.id,
                        DECODE
                        (
                            ve_reservation_product.name,  null,product.name_'.Portal::language().',
                                                            ve_reservation_product.name
                        ) as product_name, 
						unit.name_'.Portal::language().' as unit,
                        ve_reservation_product.quantity, 
                        ve_reservation_product.price,
                        (
                            (ve_reservation_product.price * ve_reservation_product.quantity) 
                            - (ve_reservation_product.price * ve_reservation_product.quantity * ve_reservation_product.discount_category/100) 
                            - ( ( ve_reservation_product.price * ve_reservation_product.quantity * ve_reservation_product.discount_category/100) * ve_reservation_product.discount_rate/100 )
                        ) as amount,
                        ve_reservation_product.product_id,
                        ve_reservation_product.discount_rate as percentage,
                        ve_reservation_product.promotion,
                        ve_reservation_product.quantity_discount,  
                        ve_reservation_product.unit_id,
                        ve_reservation_product.note,
                        ve_reservation_product.price_id,
                        ve_reservation_product.discount_category,
                        ve_reservation_product.discount_rate,
                        ve_reservation_product.department_id
					FROM 
						ve_reservation_product
						LEFT OUTER JOIN ve_reservation ON ve_reservation.id = ve_reservation_product.bar_reservation_id
						LEFT OUTER JOIN product ON product.id = ve_reservation_product.product_id
						LEFT OUTER JOIN product_price_list ON product_price_list.product_id = product.id
						LEFT OUTER JOIN unit ON unit.id = product.unit_id
					WHERE
						ve_reservation_product.bar_reservation_id = '.$row['id'].'
					ORDER BY
						ve_reservation_product.id ASC
			';
			$original_reservation_products = DB::fetch_all($sql);
			$arr = array();

    		foreach($original_reservation_products as $k =>$valu)
            {
                $arr[$valu['price_id']]['id'] = $valu['price_id'];
				$arr[$valu['price_id']]['name'] = $valu['product_name'];
				$arr[$valu['price_id']] += $valu;
				$arr[$valu['price_id']]['price'] = $valu['price'];
				$arr[$valu['price_id']]['brp_id'] = $valu['id'];
				$price = $valu['price'];
				$amount = ($valu['price'] * ($valu['quantity']));
				$arr[$valu['price_id']]['amount'] = ($amount - $amount*$valu['discount_category']/100 - (($amount - $amount*$valu['discount_category']/100)*$valu['discount_rate']/100));
				unset($valu['id']);
			}
            $row['total_payment'] = System::display_number($row['total']);
            $row['remain'] = System::display_number(System::calculate_number($row['total_payment']) );
            $customer = $row['customer_id']?DB::fetch('select * from vending_customer where id = '.$row['customer_id']):array();
            $row['customer_name'] = (!empty($customer))?$customer['name']:($row['agent_name']?$row['agent_name']:'');
            $row['customer_address'] = (!empty($customer))?$customer['address']:($row['agent_address']?$row['agent_address']:'');
            //Tong tien truoc thue
            $row['total_amount'] = System::display_number($row['total_before_tax']);
            $row['service_charge'] = $row['bar_fee_rate'];
            $row['discount_percent'] = $row['discount_percent']?$row['discount_percent']:'';
            $row['discount'] = $row['discount']?System::display_number($row['discount']):'';
            $row['deposit'] = System::display_number($row['deposit']);
		}
        else
        {
            $row['status'] = '';
            $row['remain'] = '';
            $row['receiver_name'] = '';
            $row['customer_name'] = '';
            $row['customer_id'] = '';
            $row['customer_address'] = '';
            $row['tax_code'] = '';
            $row['note'] = '';
            $row['total_amount'] = '';
            $row['tax_rate'] = (VENDING_TAX_RATE ? VENDING_TAX_RATE : 0); 
            $row['service_charge'] = (VENDING_SERVICE_CHARGE ? VENDING_SERVICE_CHARGE : 0);
            $row['discount_percent'] = '';
            $row['discount'] = '';
            $row['total_payment'] = '';
            $row['deposit']=0;
            $row['is_debit']=0;
            $row['foc']=0;
		}
		$this->map += $row;
		$discount_percent = array();
        
		$amount = 0;
		$list_products = AutomaticVendDB::get_all_product($this->map['department_id']);
        $this->map['product_array'] = String::array2js($list_products);
		$products = '<ul id="bound_product_list" style="display:none;">';
		foreach($list_products as $id => $prd)
        {
			$products .= '<li id="product_'.$prd['id'].'" class="product-list" title="'.ucfirst($prd['name']).'" onclick="SelectedItems(\''.$prd['id'].'\',0);">'.$prd['code'].'<br>'.ucfirst($prd['name']).'<br>'.System::display_number($prd['price']).'<input name="items" type="hidden" id="items_'.$prd['id'].'" /></li>';
		}
		$products .= '</ul>';
        $this->map['products'] = $products;
		$categories_discount = AutomaticVendDB::get_list_category_discount($this->map['department_id']);
        $this->map['categories_discount_js'] = String::array2js($categories_discount);
		$this->map['arrival_date'] = isset($row['arrival_time'])?date('d/m/Y',$row['arrival_time']):date('d/m/Y',time());   
		$this->map['date'] = isset($row['arrival_time'])?date('d/m/Y',$row['arrival_time']):date('d/m/Y',time());
        
        $this->map['units']=String::array2js(DB::fetch_all('select id as name, name_'.Portal::language().' as id from unit where 1=1 order by name_'.Portal::language().''));
        
		$this->map['product_list_js'] = String::array2js($arr);
        if(Url::get('cmd')=='edit')
        {
			$row['arrival_time'] = date('d/m/Y',$row['arrival_time']);
		}
        //Ten khu vuc dang ban
        $this->map['bar_name'] = DB::fetch('select name_'.Portal::language().' as name from department where id='.$this->map['department_id'],'name');
		//System::debug($this->map);
        $this->parse_layout('list',$this->map);
	}
	function get_list_other_category($department_id)
    {
		$categories = AutomaticVendDB::get_list_other_category($department_id);
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
	function select_list_food_category($department_id)
    {
		$categories = AutomaticVendDB::get_list_food_category($department_id);
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
	function check_edit()
    {
		if(Url::get('cmd')=='edit' and Url::get('id') and $ve_reseration = DB::select('ve_reservation','id = '.Url::iget('id').''))
        {
			return $ve_reseration;
		}
        else
        {
			return false;
		}
	}
}
?>