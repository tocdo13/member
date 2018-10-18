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
                
                $sql = '
    				SELECT
    					room.*, 
                        reservation_room.id as reservation_room_id, 
                        reservation_room.reservation_id as reservation_id
    				FROM
    					room
    					inner join reservation_room on reservation_room.room_id = room.id
    					inner join room_status on room_status.reservation_room_id  =  RESERVATION_ROOM.id
    				WHERE
    					reservation_room.status=\'CHECKIN\'
    					AND room_status.status = \'OCCUPIED\'
    					AND room_status.in_date = \''.Date_time::to_orc_date(date('d/m/Y')).'\'
    					AND room.id=\''.Url::get('room_id').'\'
    			';
    			if(!($reservation = DB::fetch($sql)))
    			{
    				$this->response(200, "Không có đặt phòng.");
    			}	
                $row = array(
					'type'=>'EQUIP',
					'reservation_room_id' => Url::get('reservation_room_id'), 
					'minibar_id' => Url::get('room_id'), 
					'user_id' => Url::get('user_id'), 
					'last_modifier_id' => Url::get('user_id'), 
					'total' => System::calculate_number(URL::get('total')),
					'fee_rate' => 0,
					'tax_rate' => Url::get('tax_rate',0),
					'total_before_tax' => round(Url::get('total_before_tax'),2),
					'time'=>time(),
					'group_payment' => 0,
					'portal_id' => Url::get('portal_id'),
                    'last_time' => time(),
                    'lastest_user_id' => Url::get('user_id'), 
				);
                $invoice_id = DB::insert('housekeeping_invoice',$row);
                $pos = DB::fetch('SELECT max(position) as position FROM housekeeping_invoice WHERE housekeeping_invoice.portal_id=\''.PORTAL_ID.'\' and type =\'EQUIP\'');
                if(($pos['position']!=''))
                {
                    $position = $pos['position'] + 1;
                }else
                {
                    $position = 1 ;
                }
    			DB::update('housekeeping_invoice',array('position'=>$position),'id='.$invoice_id);
                
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
                                            'invoice_id' => $invoice_id,
                            ); 
    						DB::insert('housekeeping_invoice_detail',$data_arr);
                            
                            //Update lai so luong trong thiet bi phong
                            DB::query('
    							UPDATE 
                                    housekeeping_equipment 
                                SET 
                                    damaged_quantity = damaged_quantity+'.intval($record['quantity']).'
    							WHERE 
    								room_id=\''.Url::get('room_id').'\' and
    								product_id=\''.$record['product_id'].'\'
    						');
                            
    						$id = DB::insert('housekeeping_equipment_damaged',array(
    							'room_id',
    							'product_id' => $record['product_id'],
    							'quantity' => $record['quantity'],
    							'note' =>Portal::language('from_equipment_invoice'),
    							'type' => 'DAMAGED',
    							'time' => time(),
    							'housekeeping_invoice_id' => $invoice_id,
                                'portal_id' => Url::get('portal_id')
    						));
                            
    						$product_description .= '
    							product_id: '.$record['product_id'].', 
    							quantity: '.$record['quantity'].', 
    							type: DAMAGED,
    							housekeeping_invoice_id: '.$invoice_id.'<br>
    						';
    					}
    				}
                    
                    $title = 'Add compensation invoice #EQ_'.$position.' at room '.DB::fetch('select name from room where id=\''.Url::get('room_id').'\'','name');
                    $description = 'Code: <a href="?page=equipment_invoice&cmd=edit&id='.$id.'">'.$id.'</a><br>
            						Total money:'.Url::get('total').HOTEL_CURRENCY.'<br>
                                    Reservation code: <a href="?page=reservation&cmd=edit&id='.Url::get('reservation_id').'&r_r_id='.Url::get('reservation_room_id').'">'.Url::get('reservation_id').'</a><br>
            						<b>Services: </b><br>
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
    						$record['invoice_id'] = $invoice_id;
    						$record['product_id'] = $key;
    						DB::insert('housekeeping_invoice_detail',$record);
                            
                            //Update lai so luong trong thiet bi phong
                            DB::query('
    							UPDATE 
                                    housekeeping_equipment 
                                SET 
                                    damaged_quantity = damaged_quantity+'.intval($record['quantity']).'
    							WHERE 
    								room_id=\''.Url::get('room_id').'\' and
    								product_id=\''.$record['product_id'].'\'
    						');
                            
    						$id = DB::insert('housekeeping_equipment_damaged',array(
    							'room_id',
    							'product_id' => $record['product_id'],
    							'quantity' => $record['quantity'],
    							'note' =>Portal::language('from_equipment_invoice'),
    							'type' => 'DAMAGED',
    							'time' => time(),
    							'housekeeping_invoice_id' => $invoice_id,
                                'portal_id' => Url::get('portal_id')
    						));
                            
    						$product_description .= '
    							product_id: '.$record['product_id'].', 
    							quantity: '.$record['quantity'].', 
    							type: DAMAGED,
    							housekeeping_invoice_id: '.$invoice_id.'<br>
    						';
    					}
    				}
                    
                    $title = 'Add compensation invoice #EQ_'.$position.' at room '.DB::fetch('select name from room where id=\''.Url::get('room_id').'\'','name');
                    $description = 'Code: <a href="?page=equipment_invoice&cmd=edit&id='.$id.'">'.$id.'</a><br>
            						Total money:'.Url::get('total').HOTEL_CURRENCY.'<br>
                                    Reservation code: <a href="?page=reservation&cmd=edit&id='.Url::get('reservation_id').'&r_r_id='.Url::get('reservation_room_id').'">'.Url::get('reservation_id').'</a><br>
            						<b>Services: </b><br>
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