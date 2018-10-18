<?php
class AddAmenitiesInvoiceForm extends Form
{
	function AddAmenitiesInvoiceForm()
	{
		Form::Form('AddAmenitiesInvoiceForm');
		$this->add('total',new FloatType(true,'null_invoice','0','100000000000'));
		$this->add('room_id',new IDType(false,'invalid_room_id','room'));
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		
	}
	function on_submit()
	{
        require_once 'packages/hotel/includes/php/product.php';
		if($this->check())
		{
			$sql = '
				select
					room.*, reservation_room.id as reservation_room_id
				from
					room
					inner join reservation_room on reservation_room.room_id = room.id
					inner join reservation on reservation.id = reservation_room.reservation_id
					inner join room_status on room_status.reservation_room_id  =  RESERVATION_ROOM.id
				where
					reservation.portal_id = \''.PORTAL_ID.'\'
					and reservation_room.status=\'CHECKIN\'
					and room_status.status = \'OCCUPIED\'
					and room_status.in_date = \''.Date_time::to_orc_date(date('d/m/Y')).'\'
					and room.id='.Url::get('room_id').'
			';
            //Neu khong co reservation
			if(!($reservation = DB::fetch($sql)))
			{
				if(!($reservation = $this->one_minibar_overdue('and room.id= '.Url::get('room_id').' ')))
				{
					$this->error('room_id','no_reservation');
					return;
				}
			}
			$id = DB::insert('housekeeping_invoice', 
				((Url::get('id')=='(auto)')?array():array('id'))+
				array(
					'code'=>Url::get('code')?Url::get('code'):'',
					'note'=>Url::get('note')?Url::get('note'):'',
					'type'=>'AMENITIES',
					'reservation_room_id'=>$reservation['reservation_room_id'], 
					'minibar_id'=>Url::get('room_id'), 
					'user_id'=>Session::get('user_id'),
					'last_modifier_id'=>Session::get('user_id'),
					'total'=>System::calculate_number(Url::get('total')),
					'fee_rate'=>System::calculate_number(Url::get('fee_rate',0)),
					'tax_rate'=>System::calculate_number(Url::get('tax_rate',0)),
					'total_before_tax'=>(System::calculate_number(Url::get('total_before_tax',0)) - System::calculate_number(Url::get('total_discount',0))),
					'discount'=>System::calculate_number(Url::get('discount')),
					'time'=>time(),
					'group_payment'=>Url::check('group_payment')?1:0,
					'portal_id'=>PORTAL_ID
				)
			);
            
			$content = 'Use services of amenities <a href="?page=amenities_invoice&id='.$id.'">#'.$id.'</a> total='.Url::get('total').'$';
			$product_description = '';
			//Chi tiết hóa don
			if(isset($_REQUEST['items']) and is_array($_REQUEST['items']))
			{
				foreach($_REQUEST['items'] as $key=>$record)
				{
					if($record['quantity']>0)
					{
						$content.='<br>'.$record['quantity'].' '.$key.' '.$record['price'].'$';
						$record['price'] = System::calculate_number($record['price']);
						$record['invoice_id'] = $id;
						$record['product_id'] = $key;
						DB::insert('housekeeping_invoice_detail',$record);                      
						if(DB::select('room_amenities','product_id=\''.$key.'\' and room_id= '.Url::get('room_id').' '))
						{
							//Cập nhật lại quantity trong minibar_product, ở đây quantity là số lượng dược sử dụng
							DB::query('update room_amenities set quantity=quantity-'.$record['quantity'].' where room_id = '.Url::get('room_id').' and product_id=\''.$key.'\'');
						}
						else
						{
							//Dung quy trinh truong hop nay khong bao gio xay ra
							//Chi co the ban san pham da khai báo dịnh mức trong minibar
							$id = DB::insert('room_amenities',
								array(
									'room_id',
									'product_id'=>$key,
									'quantity'=>-$record['quantity'],
									'price'=>System::calculate_number($record['price']),
                                    'in_date'=>Date_Time::to_orc_date(date('d/m/Y')),
                                    'portal_id'=>PORTAL_ID
								)
							);
						}
						if($product=DB::select('product_price_list','product_id=\''.$record['product_id'].'\' and department_code = \'HK\' and portal_id=\''.PORTAL_ID.'\''))
						{
                            
							$product_description .= $record['quantity'].' '.$product['product_id'].' <br>';
						}
                        //System::debug($product_description);
                        //exit();
					}
				}
			}
            
/**
 *             $warehouse_id = DB::fetch('Select * from portal_department where department_code = \'MINIBAR\' and portal_id = \''.PORTAL_ID.'\' ','warehouse_id');
 *             DeliveryOrders::get_delivery_orders($id,'MINIBAR',$warehouse_id); 
 */
            
            
			//$travellers = DB::select_all('reservation_traveller','reservation_room_id=\''.$reservation['id'].'\'');
			$travellers = DB::fetch_all("select * from reservation_traveller where reservation_room_id = '".$reservation['RESERVATION_ROOM_ID']."'");
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
			System::log('add','Add amenities invoice at room '.DB::fetch('select name from room where id=\''.$reservation['room_id'].'\'','name'),
'Code:<a href="?page=amenities_invoice&id='.$id.'">'.$id.'</a><br>
Total money:'.Url::get('total').HOTEL_CURRENCY.'<br>
Reservation code: <a href="?page=reservation&id='.$reservation['reservation_room_id'].'">'.$reservation['reservation_room_id'].'</a><br>
<b>Services:</b><br>
'.$product_description);
			Url::redirect_current();
		}
	}
	function draw()
	{
		$this->map['unlimited'] = MINIBAR_IMPORT_UNLIMIT;
		if($this->map['unlimited'])
		{
			$field = 'norm_quantity';
		}else{
			$field = 'quantity';
		}
		$this->map['service_charge'] = 0;
		$this->map['tax_rate'] = 0;
		$this->map['discount'] = 0;
        //Lay danh sách các minibar cua các phòng còn dang checkin
		$rooms = DB::fetch_all('
			select
				room.*,RESERVATION_ROOM.id as RESERVATION_ROOM_ID
			from
				room
				inner join reservation_room on reservation_room.room_id = room.id
				inner join reservation on reservation.id = reservation_room.reservation_id
				inner join room_status on room_status.reservation_room_id  =  RESERVATION_ROOM.id
			where
				reservation.portal_id = \''.PORTAL_ID.'\'
				and reservation_room.status = \'CHECKIN\'
				and (reservation_room.closed is null or reservation_room.closed = 0)
				and room_status.status = \'OCCUPIED\'
				and room_status.in_date = \''.Date_time::to_orc_date(date('d/m/Y')).'\'
			order by
				room.name
		');
		//$minibars += $this->minibar_overdue();
		sort($rooms);
        //System::debug($minibars);
        //Neu link sang tu room_map
		if(Url::get('reservation_room_id')){
			$room_id = $this->get_room_id(Url::iget('reservation_room_id'));
			$_REQUEST['room_id'] = $room_id;
		}
		if(!Url::get('room_id'))
		{
			$room_id = 0;
		}
		else
		{
			$room_id = Url::get('room_id');
		}
        if(
            $items = DB::fetch_all('
                        			select
                        				room_amenities.product_id as id,
                        				'.$field.' as norm_quantity,
                        				0 as quantity,
                        				product.name_'.Portal::language().' as name,
                        				product_price_list.price
                        			from
                        				room_amenities
                        				inner join product on room_amenities.product_id = product.id
                        				inner join product_price_list on product_price_list.product_id=product.id and product_price_list.department_code=\'HK\'
                        			where
                        				room_amenities.room_id = '.$room_id.' and room_amenities.portal_id = \''.PORTAL_ID.'\'
                        			order by 
                        				room_amenities.position'
                        		)
        )
        {
        } 
        else 
            $items = array();
        //System::debug($items);
        $i=1;
		foreach($items as $id=>$item)
		{
			$items[$id]['no'] = $i++;
			$items[$id]['price'] = System::display_number($items[$id]['price']);
		}
		$this->parse_layout('add',$this->map+
			array(
			'num_product'=>$i-1,
			'items'=>$items,
			'time'=>date('H:i\' d/m/Y',time()),
			'room_id_list'=>array('unavaiable'=>Portal::language('select_room'))+String::get_list($rooms),
			'room_id'=>1
			)
		);
	}
	function minibar_overdue($cond = '')
	{
		$sql = '
			select 
				room.*,reservation_room.id as reservation_room_id
			from 
				room
				inner join reservation_room on reservation_room.room_id = room.id 
			where
				reservation_room.status=\'CHECKIN\' and departure_time<=\''.Date_Time::to_orc_date(date('d/m/Y',time())).'\'
				'.$cond.'
		';
		return DB::fetch_all($sql);
	}
	function one_minibar_overdue($cond = '')
	{
		$sql = '
			select 
				room.*,reservation_room.id as reservation_room_id
			from 
				room
				inner join reservation_room on reservation_room.room_id = room.id 
			where
				reservation_room.status=\'CHECKIN\' and departure_time<=\''.Date_Time::to_orc_date(date('d/m/Y',time())).'\'
				'.$cond.'
		';
		return DB::fetch($sql);
	}
	function get_room_id($r_r_id){
		return DB::fetch('select room.id from room inner join reservation_room on reservation_room.room_id = room.id where reservation_room.id = '.$r_r_id.'','id');
	}
}
?>