<?php
class EditAmenitiesUsedForm extends Form
{
	function EditAmenitiesUsedForm()
	{
		Form::Form('EditAmenitiesUsedForm');
	}
	function on_submit()
	{
		if(Url::check('update'))
		{
            $sql='SELECT 
                        DISTINCT
    					product.id as id,
                        product.name_'.Portal::language().' AS name
    				FROM 
    					room_amenities 
                        inner join product ON room_amenities.product_id=product.id
                        inner join product_price_list on product.id = product_price_list.product_id
    				WHERE
    					product_price_list.portal_id=\''.PORTAL_ID.'\'
    				ORDER BY
    					product.id
    			';
            
    		$room_amenities=DB::fetch_all($sql);
            //System::debug($room_amenities);
            
            //Lấy các phòng và gán sp vào dó, qua bước này các phòng đều có sản phẩm giống nhau
    		$sql='SELECT 
    					room.*, 
                        room.name as room_name
    				FROM 
                        room
    				WHERE
    					room.portal_id=\''.PORTAL_ID.'\'
    				ORDER BY
    					room.name
    			';	
    		$rooms=DB::fetch_all($sql);
            
            
            foreach($rooms as $room_id=>$value)
            {
                foreach($room_amenities as $product_id=>$v)
                {
                    if(Url::check($room_id.'_'.$product_id))
                    {
                        $quantity = Url::get($room_id.'_'.$product_id);
                        
                        //Số lượng =  thì xóa bản ghi
                        if(System::calculate_number($quantity)==0)
                            DB::delete('amenities_used_detail',' room_id = '.$room_id.' and product_id = \''.$product_id.'\' and amenities_used_id = '.Url::get('id'));
                        else
                        {
                            //Nếu đã tồn tại bản ghi
                            if( $row = DB::fetch('Select * from amenities_used_detail where room_id = '.$room_id.' and product_id = \''.$product_id.'\' and amenities_used_id = '.Url::get('id') ) )
                                DB::update_id('amenities_used_detail',array('quantity'=>System::calculate_number($quantity)), $row['id']);
                            else //Nếu chưa thì thêm mới
                            {
                                $record = array(
                                        'amenities_used_id'=>Url::get('id'),
                                        'portal_id'=>PORTAL_ID,
                                        'room_id'=>$room_id,
                                        'product_id'=>$product_id,
                                        'quantity'=>System::calculate_number(Url::get($room_id.'_'.$product_id))
                                        );
                                DB::insert('amenities_used_detail',$record);
                            }
                        }
                            
                    }
                }   
            }
            
            require_once 'packages/hotel/includes/php/product.php';
            //Vẫn còn tồn tại bản ghi trong detail thì update lại
            if( DB::exists('Select * from amenities_used_detail where amenities_used_id = '.Url::get('id')) )
            {
                DB::update_id( 'amenities_used',array( 'note','last_modify_user_id'=>Session::get('user_id'),'last_modify_time'=>time() ),Url::get('id') );
                //KID SUA DE TU DONG PHIEU XUAT
                $warehouse_id = DB::fetch('Select * from portal_department where department_code = \'AMENITIES\' and portal_id = \''.PORTAL_ID.'\' ','warehouse_id');
                DeliveryOrders::get_delivery_orders(Url::get('id'),'AMENITIES',$warehouse_id);
//het
            }
            else//Nếu không còn detail thì xóa bản ghi chính
            {
                DB::delete_id('amenities_used',Url::get('id'));
                DeliveryOrders::delete_delivery_order(Url::get('id'),'AMENITIES');
            }
                
                
			Url::redirect_current();
		}
	}	
	function draw()
	{		
		//-------------------------------------------------------------------------------
        //Lấy các amenities đã được khai báo trong phòng
		$sql='SELECT 
                    DISTINCT
					product.id as id,
                    product.name_'.Portal::language().' AS name
				FROM 
					room_amenities 
                    inner join product ON room_amenities.product_id=product.id
                    inner join product_price_list on product.id = product_price_list.product_id
				WHERE
					product_price_list.portal_id=\''.PORTAL_ID.'\'
				ORDER BY
					product.id
			';
        
		$room_amenities=DB::fetch_all($sql);
        //System::debug($room_amenities);
        
        //Lấy các phòng và gán sp vào dó, qua bước này các phòng đều có sản phẩm giống nhau
		$sql='SELECT 
					room.*, 
                    room.name as room_name
				FROM 
                    room
				WHERE
					room.portal_id=\''.PORTAL_ID.'\'
                    and room.close_room=1
				ORDER BY
					room.name
			';	
		$rooms=DB::fetch_all($sql);
        //System::debug($rooms);
		foreach($rooms as $key=>$minibar)
		{
			$rooms[$key]['products'] = $room_amenities;
		}
        //System::debug($rooms);
        		
//-------------------------------------------------------------------------------
        //Lấy các sp trong các phòng
		$sql='
				SELECT 
					room_amenities.id, 
                    room_id, 
                    product_id,
                    1 as is_real
				FROM 
					room_amenities
					inner join product on product.id=room_amenities.product_id
					inner join room on room_amenities.room_id = room.id
				WHERE
					room_amenities.portal_id=\''.PORTAL_ID.'\'
                    and room.portal_id = \''.PORTAL_ID.'\'
			';
		$products=DB::fetch_all($sql);
        //System::debug($products);
        //System::debug($minibars);
//-------------------------------------------------------------------------------
        //is_real : phân biệt phòng nào đã đc khai báo, phòng nào chưa
		foreach($products as $product)
		{
			$rooms[$product['room_id']]['products'][$product['product_id']] = $product;
		}
        //System::debug($rooms);
//-------------------------------------------------------------------------------
		$layout='edit';
//-------------------------------------------------------------------------------
        if($id = Url::iget('id'))
        {
            $_REQUEST['note'] = DB::fetch('Select note from amenities_used where id = '.$id,'note');
            $detail = DB::fetch_all('Select * from amenities_used_detail where amenities_used_id = '.$id);
            //System::debug($detail);
            foreach($detail as $key=>$value)
            {
                $_REQUEST[$value['room_id'].'_'.$value['product_id']] = $value['quantity'];
            }
            //System::debug($_REQUEST);   
        }
        //System::debug($minibar_products_sample);
		$this->parse_layout($layout,
			array(
			'room_amenities'=>$room_amenities,
			'rooms'=>$rooms,
			)
		);	
	}
}
?>