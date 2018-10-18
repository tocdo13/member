<?php
class AddHousekeepingEquipmentForm extends Form
{
	function AddHousekeepingEquipmentForm()
	{
		Form::Form('AddHousekeepingEquipmentForm');
		$this->add('housekeeping_equipment_detail.quantity',new FloatType(true,'invalid_quantity','0.00000000001','100000000000')); 
		$this->add('housekeeping_equipment_detail.product_id',new IDType(true,'invalid_product_id','product_price_list','product_id'));
		//$this->add('housekeeping_equipment_detail.name',new TextType(true,'invalid_housekeeping_equipment_name',0,255));
		$this->link_js('packages/core/includes/js/multi_items.js');
		//$this->link_js('packages/core/includes/js/suggest.js');
		//$this->link_js('packages/core/includes/js/calendar.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
	}
	function on_submit()
	{
        if(Url::get('save'))
        {
            //vi khi layout list truyen sang co mi_housekeeping_equipment_detail[1][product_id] tren url nen khi submit xoa cai nay di de ko this check
            unset($_REQUEST['mi_housekeeping_equipment_detail'][1]);
            if($this->check())
    		{
    			if(isset($_REQUEST['mi_housekeeping_equipment_detail']))
    			{
                    $new_recode =array();
    				foreach($_REQUEST['mi_housekeeping_equipment_detail'] as $key=>$record)
    				{
    					if(isset($record['quantity']) and $record['quantity']!=0)
    					{
    						if(DB::select('product_price_list','product_id = \''.$record['product_id'].'\' and department_code = \'HK\' and portal_id=\''.PORTAL_ID.'\''))
    						{
                                //ghi log    						  
                                $product_name = $record['name'];
    							unset($record['name']);
    							unset($record['unit_name']);
    							unset($record['id']);	
                                unset($record['price']);
                                $new_recode['portal_id'] = PORTAL_ID;
                                $new_recode['product_id'] = $record['product_id'];
                                //khong co loai phong, ten phong
                                if(!Url::get('room_id') and !Url::get('room_level_id'))
                                {
                                    $this->error('room_id','invalid_room_id');
									return;
                                }
                                else
                                {
                                    //neu chon loai phong
                                    if($room_level_id = Url::get('room_level_id'))
    								{
    									$cond = ' and room.portal_id=\''.PORTAL_ID.'\'';
    									//ghi log
                                        $room_level_name = 'All';
    									//neu loai phong khac all
                                        if($room_level_id != 'all')
    									{
    										$cond = ' and room.room_level_id = \''.$room_level_id.'\'';
    										if($room_level = DB::fetch('select id,name from room_level where id=\''.$room_level_id.'\''))
    										{
    											$room_level_name = $room_level['name'];
    										}
    									}
    									$room = DB::fetch_all('	select id from room where 1=1 '.$cond);
    									foreach($room as $key=>$value)
                                        {
    										$new_recode['room_id'] = $key;
    										if($row = DB::fetch('select * from housekeeping_equipment where room_id = '.$key.' and product_id=\''.$record['product_id'].'\''))
                                            {
                                                //System::debug($row);
                                                
                                                $row['damaged_quantity'] = $row['damaged_quantity']?$row['damaged_quantity']:0;
                                                $quantity = $row['quantity'] - $row['damaged_quantity']+ $record['quantity'];
                                                $damaged_quantity = $row['damaged_quantity'] - $record['quantity'];
                                                $damaged_quantity = $damaged_quantity>=0 ? $damaged_quantity : 0;
                                                $new_recode['quantity'] = $quantity;
                                                $new_recode['damaged_quantity'] = $damaged_quantity;
                                                //echo 1;
                                                //System::debug($new_recode);die;
                                                //exit();
    											DB::update('housekeeping_equipment',$new_recode,'room_id = '.$key.' and product_id=\''.$record['product_id'].'\'');
    										}
                                            else
                                            {
    											DB::insert('housekeeping_equipment',$record+array('time'=>time(),'room_id'=>$key,'portal_id'=>PORTAL_ID));
    										}
    									}
    									//$product = DB::select('hk_product','code=\''.$record['product_id'].'\' and portal_id=\''.PORTAL_ID.'\'');
    									$product = DB::select('product_price_list','product_id = \''.$record['product_id'].'\' and department_code = \'HK\' and portal_id=\''.PORTAL_ID.'\'');
                                        System::log
                                        (
                                            'add','Add housekeeping equipment',
    										'room_level:'.$room_level_name.'<br>
    										Name:<a href="?page=product&id='.$product['id'].'">'.$product_name.'</a><br>
    										Quantity:'.$record['quantity']
                                        );
    								}
                                    else//neu khong chon loai phong thi xet den phong
                                    {
                                        if($room_id = Url::get('room_id'))
        								{
        									$record['room_id'] = $room_id;
        									//$product = DB::select('hk_product','code=\''.$record['product_id'].'\' and portal_id=\''.PORTAL_ID.'\'');
        									$product = DB::select('product_price_list','product_id = \''.$record['product_id'].'\' and department_code = \'HK\' and portal_id=\''.PORTAL_ID.'\'');
                                            $room = DB::select('room','id='.Url::get('room_id').' and portal_id=\''.PORTAL_ID.'\'');
        									if($he = DB::fetch('select * from housekeeping_equipment where room_id = '.$room_id.' and product_id=\''.$record['product_id'].'\''))
                                            {
    											$id = $he['id'];
                                                
                                                $he['damaged_quantity'] = $he['damaged_quantity']?$he['damaged_quantity']:0;
                                                $quantity = $he['quantity'] - $he['damaged_quantity']+ $record['quantity'];
                                                $damaged_quantity = $he['damaged_quantity'] - $record['quantity'];
                                                $damaged_quantity = $damaged_quantity>=0 ? $damaged_quantity : 0;
                                                $record['quantity'] = $quantity;
                                                $record['damaged_quantity'] = $damaged_quantity;
                                                
    											DB::update('housekeeping_equipment',$record,'room_id = '.$room_id.' and product_id=\''.$record['product_id'].'\'');
    											System::log
                                                (
                                                'edit','Edit housekeeping equipment',
    											'Code: <a href="?page=housekeeping_equipment&id='.$id.'">'.$id.'</a><br>
    											Room: '.$room['name'].'<br>
    											Name: <a href="?page=product&id='.$product['id'].'">'.$product_name.'</a><br>
    											Quantity: '.$record['quantity'].'<br>
    											username: '.Session::get('user_id').'<br>
    											'
                                                );
        									}
                                            else
                                            {
        										$id=DB::insert('housekeeping_equipment',$record+array('time'=>time(),'portal_id'=>PORTAL_ID));
        										System::log('add','Add housekeeping equipment',
        										'Code:<a href="?page=housekeeping_equipment&id='.$id.'">'.$id.'</a><br>
        										Room:'.$room['name'].'<br>
        										Name:<a href="?page=product&id='.$product['id'].'">'.$product_name.'</a><br>
        										Quantity:'.$record['quantity']).'<br>
        										sername: '.Session::get('user_id').'<br>
        										';
        									}
        								}
                                    }
                                }
    						}
    					}
    				}
    			}
    			Url::redirect_current();
    		}
        }	
	}
	function draw()
	{
		DB::query('select
                        id, room.name as name
            		from 
                        room
            		where 
                        portal_id=\''.PORTAL_ID.'\'
                        and close_room=1
            		order by room.name
            	');
		$room_id_list = array('0'=>'----')+String::get_list(DB::fetch_all());
		
		DB::query('select
                        id, room_level.name as name
        			from 
        				room_level
                    where
                        room_level.portal_id = \''.PORTAL_ID.'\'
        			order 
        				by room_level.name
        		');
		$room_level_id_list = array('0'=>'----','all'=>Portal::language('All'))+String::get_list(DB::fetch_all());
		
		HousekeepingEquipment::get_js_variables_data();
		
		$this->parse_layout('add',array(
			'room_id_list'=>$room_id_list,
			'room_id'=>0,
			'room_level_id_list'=>$room_level_id_list,
			'room_level_id'=>0,
			'product_id_list'=>array(''=>'')+String::get_list($GLOBALS['js_variables']['product']),
		));
	}
}
?>