<?php
class api extends restful_api
{
    function __construct(){
		parent::__construct();
	}
    
    function add()
    {
        if($this->method == 'POST')
        {
    		if(Url::get('secretkey') and Url::get('secretkey') == '9a8fa234b2520e9bb4f59d8178545a62')
            {
                require_once 'packages/hotel/includes/php/product.php';
                $sql = '
    				SELECT
    					minibar.*, 
                        reservation_room.id as reservation_room_id,
                        reservation_room.reservation_id as reservation_id
    				FROM
    					minibar
    					inner join reservation_room on reservation_room.room_id = minibar.room_id
    					inner join reservation on reservation.id = reservation_room.reservation_id
    					inner join room_status on room_status.reservation_room_id  =  RESERVATION_ROOM.id
    				WHERE
    					reservation.portal_id = \''.Url::get('portal_id').'\'
    					and reservation_room.status=\'CHECKIN\'
    					and room_status.status = \'OCCUPIED\'
    					and room_status.IN_DATE = \''.Date_time::to_orc_date(date('d/m/Y')).'\'
    					and minibar.id=\''.Url::get('minibar_id').'\'
    					and minibar.status <> \'NO_USE\'
    			';
    			if(!($reservation = DB::fetch($sql)))
    			{
    				if(!($reservation = $this->one_minibar_overdue('and minibar.id=\''.Url::get('minibar_id').'\'')))
    				{
    					$this->response(200, "Không có đặt phòng!");
    				}
    			}
                $hk_invoice = array(
                                'type' => 'MINIBAR',
                                'reservation_room_id' => Url::get('reservation_room_id'),
                                'minibar_id' => Url::get('minibar_id'),
                                'user_id' => Url::get('user_id'), 
                                'last_modifier_id' => Url::get('user_id'),
                                'total' => Url::get('total'),
                                'fee_rate' => Url::get('fee_rate',0),
				                'tax_rate' => Url::get('tax_rate',0),
                                'total_before_tax' => round(Url::get('total_before_tax'),2),
                                'discount' => 0,
                                'time' => time(),
                                'group_payment' => 0,
                                'portal_id' => Url::get('portal_id'),
                                'net_price' => Url::get('net_price'),
                                'last_time' => time(),
                                'lastest_user_id' => Url::get('user_id')
                ); 
                $id = DB::insert('housekeeping_invoice', $hk_invoice);
                $pos = DB::fetch('SELECT max(position) as position FROM housekeeping_invoice WHERE housekeeping_invoice.portal_id=\''.Url::get('portal_id').'\' and type =\'MINIBAR\'');
	            if(($pos['position']!=''))
	            {
	                $position = $pos['position'] + 1;
	            }else
	            {
	                $position = 1 ;
	            }
				DB::update('housekeeping_invoice',array('position'=>$position),'id='.$id);
                $product_description = '';
                $order_list = Url::get('product_arr');
                $arr = json_decode($order_list, true);
                //System::debug($arr);exit();
                $items = array();
                if(Url::get('type') == 'IOS')
                {
                    $items = $arr;
                    foreach($items as $key => $record)
    				{
    					if(($record['quantity']>0))
    					{
                            $data_arr = array(
                                            'product_id' => $record['product_id'],
                                            'price' => System::calculate_number($record['price']),
                                            'quantity' => $record['quantity'],
                                            'invoice_id' => $id,
                            ); 
    						DB::insert('housekeeping_invoice_detail',$data_arr);
                            
                            if(DB::select('minibar_product','product_id=\''.$record['product_id'].'\' and minibar_id=\''.Url::get('minibar_id').'\''))
    						{
    							//Cap nhat lai so luong co trong minibar
    							DB::query('update minibar_product set quantity=quantity-'.$record['quantity'].' where minibar_id=\''.Url::get('minibar_id').'\' and product_id=\''.$record['product_id'].'\'');
    						}else
    						{
    							//Dung quy trinh truong hop nay khong bao gio xay ra
    							//Chi co the ban san pham da co trong minibar
    							$id = DB::insert('minibar_product',
    								array(
    									'minibar_id',
    									'product_id'=>$record['product_id'],
    									'quantity'=>-$record['quantity'],
    									'price'=>System::calculate_number($record['price']),
    								)
    							);
    						}
    						if($product=DB::fetch('select product_price_list.*,product.name_1,product.name_2 from product_price_list INNER JOIN product ON product.id=\''.$record['product_id'].'\' and product_price_list.product_id=product.id where product_price_list.product_id=\''.$record['product_id'].'\' and product_price_list.department_code = \'MINIBAR\' and product_price_list.portal_id=\''.Url::get('portal_id').'\''))
    						{
    							$product_description .= 'Quantity'.$record['quantity'].' -  Price: '.$record['price'].' - '
                                                        .'Product Code: '.$product['product_id'].' Product Name: '.$product['name_'.Portal::language()].'<br>';
    						}
    					}
    				}
                    
                    $title = 'Add minibar invoice at room '.DB::fetch('SELECT name FROM room WHERE id=\''.Url::get('room_id').'\'','name');
                    $description = 'Code:<a href="?page=minibar_invoice&cmd=edit&id='.$id.'">MN_'.$position.'</a><br>
                                    Total money:'.Url::get('total').HOTEL_CURRENCY.'<br>
                                    Reservation code: <a href="?page=reservation&cmd=edit&id='.Url::get('reservation_id').'&r_r_id='.Url::get('reservation_room_id').'">'.Url::get('reservation_id').'</a><br>
                                    <b>Services:</b><br>
                                    '.$product_description;
                    DB::insert('log', array('type'=>"Add", 'module_id'=>is_object(Module::$current)?Module::block_id():0,
                            'title' => $title, 'description' => $description, 'time'=>time(),'user_id'=>Url::get('user_id')));
                            
                    $this->response(200, json_encode(array('status' => 'TRUE')));
                }else
                {
                    $i = 0;
                    foreach($arr as $key => $value)
                    {
                        foreach($value as $k => $v)
                        {
                            $key_arr = $v['id'];
                            if(!isset($items[$key_arr]))
                            {
                                $items[$key_arr]['product_id'] = $arr[$key][$i]['id'];
                                $items[$key_arr]['quantity'] = $arr[$key][$i]['quantity'];
                                $items[$key_arr]['price'] = System::calculate_number($arr[$key][$i]['price']);
                                $items[$key_arr]['promotion'] = 0;
                                $items[$key_arr]['change_quantity'] = 0;
                            }
                            $i++;                       
                        }                
                    }
                    //$this->response(200, json_encode($items));
                    
                    foreach($items as $key => $record)
    				{
    					if(($record['quantity']>0))
    					{
    						$record['price'] = System::calculate_number($record['price']);
    						$record['invoice_id'] = $id;
    						$record['product_id'] = $key;
    						DB::insert('housekeeping_invoice_detail',$record);                        
    						if(DB::select('minibar_product','product_id=\''.$key.'\' and minibar_id=\''.Url::get('minibar_id').'\''))
    						{
    							//Cap nhat lai so luong co trong minibar
    							DB::query('update minibar_product set quantity=quantity-'.$record['quantity'].' where minibar_id=\''.Url::get('minibar_id').'\' and product_id=\''.$key.'\'');
    						}else
    						{
    							//Dung quy trinh truong hop nay khong bao gio xay ra
    							//Chi co the ban san pham da co trong minibar
    							$id = DB::insert('minibar_product',
    								array(
    									'minibar_id',
    									'product_id'=>$key,
    									'quantity'=>-$record['quantity'],
    									'price'=>System::calculate_number($record['price']),
    								)
    							);
    						}
    						if($product=DB::fetch('select product_price_list.*,product.name_1,product.name_2 from product_price_list INNER JOIN product ON product.id=\''.$record['product_id'].'\' and product_price_list.product_id=product.id where product_price_list.product_id=\''.$record['product_id'].'\' and product_price_list.department_code = \'MINIBAR\' and product_price_list.portal_id=\''.Url::get('portal_id').'\''))
    						{
    							$product_description .= 'Quantity'.$record['quantity'].' -  Price: '.$record['price'].' - '
                                                        .'Product Code: '.$product['product_id'].' Product Name: '.$product['name_'.Portal::language()].'<br>';
    						}
    					}
    				}
                    
                    $warehouse_id = DB::fetch('SELECT * FROM portal_department where department_code = \'MINIBAR\' and portal_id = \''.Url::get('portal_id').'\' ','warehouse_id');
               
                    if($warehouse_id)
                    {
                        DeliveryOrders::get_delivery_orders($id,'MINIBAR',$warehouse_id);
                    }
                    $title = 'Add minibar invoice at room '.DB::fetch('SELECT name FROM room WHERE id=\''.Url::get('room_id').'\'','name');
                    $description = 'Code:<a href="?page=minibar_invoice&cmd=edit&id='.$id.'">MN_'.$position.'</a><br>
                                    Total money:'.Url::get('total').HOTEL_CURRENCY.'<br>
                                    Reservation code: <a href="?page=reservation&cmd=edit&id='.Url::get('reservation_id').'&r_r_id='.Url::get('reservation_room_id').'">'.Url::get('reservation_id').'</a><br>
                                    <b>Services:</b><br>
                                    '.$product_description;
                    DB::insert('log', array('type'=>"Add", 'module_id'=>is_object(Module::$current)?Module::block_id():0,
                            'title' => $title, 'description' => $description, 'time'=>time(),'user_id'=>Url::get('user_id')));
                            
                    $this->response(200, "TRUE");
                }
            }else
            {
                $this->response(500, "FAILED"); // AUTH
            } 
        }
    }
    
    function one_minibar_overdue($cond = '')
	{
		$sql = '
			SELECT 
				minibar.*,RESERVATION_ROOM.id as RESERVATION_ROOM_ID
			FROM 
				minibar
				inner join reservation_room on reservation_room.room_id = minibar.room_id 
			WHERE
				reservation_room.status=\'CHECKIN\' and departure_time <= \''.Date_Time::to_orc_date(date('d/m/Y',time())).'\'
				'.$cond.'
		';
        
		return DB::fetch($sql);
	}
}   
$api = new api();
?>