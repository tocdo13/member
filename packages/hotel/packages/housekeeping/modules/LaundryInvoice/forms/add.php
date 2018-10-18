<?php
class AddLaundryInvoiceForm extends Form
{
	function AddLaundryInvoiceForm()
	{
		Form::Form('AddLaundryInvoiceForm');
		//$this->add('reservation_room_id',new IDType(true,'invalid_reservation_id','reservation_room'));
        $this->add('grant_total',new FloatType(true,'please_add_product_or_quantity',0,999999999));
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function on_submit()
	{
		if($this->check())
		{
			$reservation_room = DB::select('reservation_room',Url::get('reservation_room_id'));
			$room = DB::select('room','id=\''.$reservation_room['room_id'].'\'');
			$room_id = $room['id'];
			if(isset($_REQUEST['instruction']))
			{
				$special_note = implode(',',$_REQUEST['instruction']);
			}
			else
			{
				$special_note = '';
			}
			//them hoa don GIAT LA moi vao bang housekeeping_invoice
			$id = DB::insert('housekeeping_invoice',array(
				'reservation_room_id'=>$reservation_room['id'],
				'user_id'=>Session::get('user_id'),
				'last_modifier_id'=>Session::get('user_id'),
				'minibar_id'=>$room_id,
				'time'=>time(),
				'discount'=>Url::get('discount',0),
				'tax_rate'=>Url::get('tax_rate',0),
				'fee_rate'=>Url::get('service_rate',0),
				'express_rate'=>Url::get('express_rate',0),
				'total'=>System::calculate_number(Url::get('grant_total')),
				'total_before_tax'=>(System::calculate_number(Url::get('subtotal'))-System::calculate_number(Url::get('total_discount')) +System::calculate_number(Url::get('express'))),
				'type'=>'LAUNDRY',
				'note',
				'code'=>Url::get('code')?Url::get('code'):'',
				'special_note'=>$special_note,
				'group_payment'=>Url::check('group_payment')?1:0,
				'portal_id'=>PORTAL_ID,
                'net_price'=>NET_PRICE_LAUNDRY,
                'is_express_rate'=>(Url::get('is_express_rate')?1:0),
                'last_time'=>time(),
                'lastest_user_id'=>User::id()
			));
			 //start:KID them cai nay de lay ma theo thu tu tang dan
            $pos = DB::fetch('SELECT max(position) as position FROM housekeeping_invoice WHERE housekeeping_invoice.portal_id=\''.PORTAL_ID.'\' and type =\'LAUNDRY\'');
            if(($pos['position']!=''))
            {
                $position = $pos['position'] + 1;
            }
			else
            {
                $position = 1 ;
            }
			DB::update('housekeeping_invoice',array('position'=>$position),'id='.$id);
            //end
			$content = 'Use laundry service <a href="?page=laundry_invoice&id='.$id.'">#'.$id.'</a> total='.Url::get('total').'$';
			//cap nhat cac product_id vao bang housekeeping_invoice_detail
			$product_description = '';
			
            //cha hieu cho nay de lam j`
            //update status product
			//LaundryInvoiceDB::UpdateProductStatus($time);
			//end
			if(isset($_REQUEST['services']))
			foreach($_REQUEST['services'] as $key=>$record)
			{
				if(isset($record['quantity']) and $record['quantity']!='' and $record['quantity']!=0)
				{
					$content.='<br>'.$record['quantity'].' '.$key.' '.$record['price'].'$';
					$record['invoice_id'] = $id;
					$record['quantity'] = System::calculate_number($record['quantity']);
					$record['promotion'] = System::calculate_number($record['promotion']);
					$record['product_id'] = $key;
					$record['price'] = System::calculate_number($record['price']);
					DB::insert('housekeeping_invoice_detail',$record);
                    
                    $sql = 'SELECT product_price_list.*, product.name_1,product.name_2 FROM product_price_list
                            INNER JOIN product ON product.id=\''.$record['product_id'].'\' and product_price_list.product_id=product.id 
                            and product_price_list.department_code = \'LAUNDRY\' and product_price_list.portal_id=\''.PORTAL_ID.'\'';
                    $result = DB::fetch_all($sql);
				    
                    foreach($result as $product)
                    {
                        $product_description .= 'Quantity:'.$record['quantity'].' - price:'.$record['price'].' - '
                    .' <a href="?page=product&id='.$product['id'].'">'.$product['name_'.Portal::language()].'</a> - promotion: '.$record['promotion'].'<br>';
                        break;
                    
                    }
                
				}
			}
			$travellers = DB::select_all('reservation_traveller','reservation_room_id=\''.$reservation_room['id'].'\'');
			foreach($travellers as $traveller)
			{
				DB::insert('traveller_comment',
					array(
						'user_id'=>Session::get('user_id'),
						'traveller_id'=>$traveller['traveller_id'],
						'time'=>time(),
						'content'=>$content
					)
				);
			}
			$log_id = System::log('add','Add laundry invoice at room '.DB::fetch('select name from room where id=\''.$reservation_room['room_id'].'\'','name'),
			'Code:<a href="?page=laundry_invoice&cmd=edit&id='.$id.'">LD_'.$position.'</a><br>
			Total money:'.Url::get('grant_total').HOTEL_CURRENCY.'<br>
			Reservation code: <a href="?page=reservation&cmd=edit&id='.$reservation_room['reservation_id'].'&r_r_id='.$reservation_room['id'].'">'.$reservation_room['reservation_id'].'</a><br><b>Services:</b><br>'.$product_description);
            
            System::history_log('RECODE',$reservation_room['reservation_id'],$log_id);
            System::history_log('LAUNDRY',$id,$log_id);
            
           if(Url::get('fast')){
                echo Portal::language('add').' '.Portal::language('laundry').' '.Portal::language('successful'); die();
            }else{
               echo '<script type="text/javascript">
                           if(window.opener)
                           {
                                window.close();
                           }
                           else
                           {
                                window.location.href=\'?page=laundry_invoice\';
                           }
                </script>';
            }
            
		}
	}
	function draw()
	{
		//danh sach reservation
		require_once 'packages/hotel/includes/php/hotel.php';
		$reservations = Hotel::get_reservation_room();
		//$reservations += $this->room_overdue();
		
		$reservation_id_list = array('0'=>'----')+String::get_list($reservations);
		//mang gio phut
		for($i=0;$i<24;$i++)
		{
			$hour_list[] = $i;
		}
		for($i=0;$i<60;$i++)
		{
			$minute_list[] = $i;
		}
		
		//danh sach nhan vien
		DB::query('
			select 
				employee_profile.id, CONCAT(CONCAT(employee_profile.first_name,\' \'),employee_profile.last_name) as name 
			from 
				employee_profile 
			order by name'
		);
		$employee_id_list = array(''=>'----') + String::get_list(DB::fetch_all()); 
		
		if(!($category = DB::select('product_category','code=\'GL\'')))
		{
			$category = array();
		}
        
		$sql = '
				select 
					code, 
                    code as id, 
                    name,
                    name_en, 
                    id as category, 
                    structure_id
				from
					product_category
				where
					'.IDStructure::direct_child_cond(DB::fetch('select id, structure_id from product_category where code = \'GL\'','structure_id')).'
				order by
					structure_id
				';
		if(!($categories = DB::fetch_all($sql)))
		{
			$categories = array();
		}
        //System::debug($categories);
        
        $sql = '
			select 
				product_price_list.product_id as id, 
                product.name_'.Portal::language().' as name, 
                product_price_list.price,
                product_category.code, 
                product_price_list.status
			from
				product_price_list
                inner join product on product_price_list.product_id = product.id
				inner join product_category on product.category_id = product_category.id
			where
				product.type=\'SERVICE\' 
                and '.IDStructure::child_cond($category['structure_id']).'
                and product_price_list.department_code = \'LAUNDRY\'
                and product_price_list.status = \'avaiable\'
                and product_price_list.portal_id=\''.PORTAL_ID.'\'
                --giap.ln hien thi ra nhung product trong khoang start_date, end_date
                AND (product_price_list.start_date is null OR (product_price_list.start_date is not null AND product_price_list.start_date<=\''.Date_Time::convert_time_to_ora_date(time()).'\'))
                AND (product_price_list.end_date is null OR (product_price_list.end_date is not null AND product_price_list.end_date>=\''.Date_Time::convert_time_to_ora_date(time()).'\'))
                --end giap.ln
			order by
				product_price_list.order_number,
                UPPER(FN_CONVERT_TO_VN(product.name_'.Portal::language().')),
                product_price_list.id asc
                
		';
		if($products = DB::fetch_all($sql)){
		  if(User::id('developer06'))
        {
            //System::debug($sql);
            //System::debug($products);
        }
		} else $products = array();
		$items = array();
		foreach($products as $key=>$value)
		{
			$newkey = substr($key,0,strlen($key)-1);
			$items[$newkey]['product_key'] = $newkey;
			$items[$newkey]['product_name'] = $value['name'];
			$items[$newkey]['child'][$value['code']]['product'] = $key;
			$items[$newkey]['child'][$value['code']]['price'] = System::display_number($value['price']);
			$items[$newkey]['child'][$value['code']]['quantity'] = '';
		}
		foreach($categories as $c=>$k)
		{
			foreach($items as $key=>$value)
			{
				if(!isset($value['child'][$k['code']]))
				{
					$items[$key]['child'][$k['code']] = array();
				}
                else
				{
					unset($items[$key]['child'][$k['code']]);
					$items[$key]['child'][$k['code']] = $value['child'][$k['code']];
				}
			}
		}
		//System::debug($items);
		$this->parse_layout('add',array(
			'reservations'=>$reservations,
			'reservation_room_id_list'=>$reservation_id_list,
			'reservation_room_id'=>0, 
			'employee_id_list'=>$employee_id_list,
            'employee_id'=>0,
			'items'=>$items,
			'current_time'=>date('H:i d/m/Y',time()),
			'hour_list'=>$hour_list,
			'hour'=>Url::get('hour',date('G',time())),
			'minute_list'=>$minute_list,
			'minute'=>Url::get('minute',date('i',time())),
			'discount'=>0,
			'tax_rate'=>LAUNDRY_TAX_RATE,
			'service_rate'=>LAUNDRY_SERVICE_CHARGE,
			'express_rate'=>LAUNDRY_EXPRESS_RATE,
			'categories'=>$categories
		));
	}
    
	/**
	 * Bo 2 ham nay
	 */
    
    function room_overdue($cond = '')
	{
		$sql = '
			select 
				reservation_room.id
				,concat(CONCAT(traveller.first_name,\' \'),traveller.last_name) as agent_name
				,room.name
			from 
				reservation_room 
				inner join room on room.id=reservation_room.room_id
				inner join room_status on room_status.reservation_room_id  =  RESERVATION_ROOM.id 
				left outer join traveller on traveller.id=reservation_room.traveller_id 
			where
				reservation_room.status=\'CHECKIN\' and departure_time<=\''.Date_Time::to_orc_date(date('d/m/Y',time())).'\'
				'.$cond.'
			order by
				room.name
		';
		return DB::fetch_all($sql);
	}
    
	function one_room_overdue($cond = '')
	{
		$sql = '
			select 
				reservation_room.id
				,concat(CONCAT(traveller.first_name,\' \'),traveller.last_name) as agent_name
				,room.name
			from 
				reservation_room 
				inner join room on room.id=reservation_room.room_id
				inner join room_status on room_status.reservation_room_id  =  RESERVATION_ROOM.id 
				left outer join traveller on traveller.id=reservation_room.traveller_id 
			where
				reservation_room.status=\'CHECKIN\' and departure_time<=\''.Date_Time::to_orc_date(date('d/m/Y',time())).'\'
				'.$cond.'
			order by
				room.name
		';
		return DB::fetch($sql);
	}
}
?>
