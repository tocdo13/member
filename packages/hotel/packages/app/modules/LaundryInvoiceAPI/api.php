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
    			$room_id = Url::get('room_id');
                
                $hk_invoice = array(
                                'type' => 'LAUNDRY',
                                'reservation_room_id' => Url::get('reservation_room_id'),
                                'minibar_id' => $room_id,
                                'user_id' => Url::get('user_id'), 
                                'last_modifier_id' => Url::get('user_id'),
                                'total' => Url::get('total'),
                                'fee_rate' => Url::get('fee_rate',0),
				                'tax_rate' => Url::get('tax_rate',0),
                                'express_rate' => 0,
                                'total_before_tax' => round(Url::get('total_before_tax'),2),
                                'discount' => 0,
                                'time' => time(),
                                'group_payment' => 0,
                                'portal_id' => Url::get('portal_id'),
                                'net_price' => Url::get('net_price'),
                                'is_express_rate' => 0,
                                'last_time' => time(),
                                'lastest_user_id' => Url::get('user_id')
                ); 
                //$this->response(200, json_encode($hk_invoice));
                $id = DB::insert('housekeeping_invoice', $hk_invoice);
                //$this->response(200, json_encode(array('status' => 'TRUE')));
                $pos = DB::fetch('SELECT max(position) as position FROM housekeeping_invoice WHERE housekeeping_invoice.portal_id=\''.Url::get('portal_id').'\' and type =\'LAUNDRY\'');
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
                            
                            $sql = '
                                    SELECT 
                                        product_price_list.*, 
                                        product.name_1,product.name_2 
                                    FROM 
                                        product_price_list
                                        INNER JOIN product ON product.id=\''.$record['product_id'].'\' and product_price_list.product_id=product.id and product_price_list.department_code = \'LAUNDRY\' and product_price_list.portal_id=\''.Url::get('portal_id').'\'';
                            $result = DB::fetch_all($sql);
        				    
                            foreach($result as $product)
                            {
                                $product_description .= 'Quantity:'.$record['quantity'].' - price:'.$record['price'].' - '
                                                     .' <a href="?page=product&id='.$product['id'].'">'.$product['name_'.Portal::language()].'</a><br>';
                                break;
                            }
    					}
    				}
                    
                    $title = 'Add laundry invoice at room '.DB::fetch('SELECT name FROM room WHERE id=\''.Url::get('room_id').'\'','name');
                    $description = 'Code:<a href="?page=laundry_invoice&cmd=edit&id='.$id.'">MN_'.$position.'</a><br>
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
                    
                    foreach($items as $key => $record)
    				{
    					if(($record['quantity']>0))
    					{
    						$record['price'] = System::calculate_number($record['price']);
    						$record['invoice_id'] = $id;
    						$record['product_id'] = $key;
    						DB::insert('housekeeping_invoice_detail',$record);
                        
                            $sql = '
                                    SELECT 
                                        product_price_list.*, 
                                        product.name_1,product.name_2 
                                    FROM 
                                        product_price_list
                                        INNER JOIN product ON product.id=\''.$record['product_id'].'\' and product_price_list.product_id=product.id and product_price_list.department_code = \'LAUNDRY\' and product_price_list.portal_id=\''.Url::get('portal_id').'\'';
                            $result = DB::fetch_all($sql);
        				    
                            foreach($result as $product)
                            {
                                $product_description .= 'Quantity:'.$record['quantity'].' - price:'.$record['price'].' - '
                                                     .' <a href="?page=product&id='.$product['id'].'">'.$product['name_'.Portal::language()].'</a> - promotion: '.$record['promotion'].'<br>';
                                break;
                            }
    					}
    				}
                    
                    $title = 'Add laundry invoice at room '.DB::fetch('SELECT name FROM room WHERE id=\''.Url::get('room_id').'\'','name');
                    $description = 'Code:<a href="?page=laundry_invoice&cmd=edit&id='.$id.'">MN_'.$position.'</a><br>
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
}   
$api = new api();
?>