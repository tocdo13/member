<?php
class AddAmenitiesUsedForm extends Form
{
	function AddAmenitiesUsedForm()
	{
		Form::Form('AddAmenitiesUsedForm');
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
            
            $row = array(
                            'portal_id'=>PORTAL_ID,
                            'user_id'=>Session::get('user_id'),
                            'time'=>time(),
                            'create_date'=>Date_Time::to_orc_date(date('d/m/Y')) ,
                            'note'=>Url::get('note')
                        );
            $amen_used_id = DB::insert('amenities_used',$row);
            
            foreach($rooms as $room_id=>$value)
            {
                foreach($room_amenities as $product_id=>$v)
                {
                    if(Url::check($room_id.'_'.$product_id))
                    {
                        if( $quantity = System::calculate_number(Url::get($room_id.'_'.$product_id)) and $quantity>0 )
                        {
                            $record = array(
                                        'amenities_used_id'=>$amen_used_id,
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
            
            //Nếu không còn detail thì xóa bản ghi chính
            if( !DB::exists('Select * from amenities_used_detail where amenities_used_id = '.$amen_used_id ) )
                DB::delete_id('amenities_used',$amen_used_id);
            else//Neu ton tai thi tao phieu xuat
            {
                //start: KID SUA DE TU DONG PHIEU XUAT
                require_once 'packages/hotel/includes/php/product.php';
                $warehouse_id = DB::fetch('Select * from portal_department where department_code = \'AMENITIES\' and portal_id = \''.PORTAL_ID.'\' ','warehouse_id');
                if(isset($warehouse_id) and $warehouse_id!='')
                {
                    DeliveryOrders::get_delivery_orders($amen_used_id,'AMENITIES',$warehouse_id);
                }
                else
                {
                    echo '<script>
                        alert("chưa chọn kho cho bộ phận AMENITIES!");
                    </script>';
                    return false;
                }
                
                //end
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
		$layout='add';
//-------------------------------------------------------------------------------
		$this->parse_layout($layout,
			array(
			'room_amenities'=>$room_amenities,
			'rooms'=>$rooms,
			)
		);	
	}
}
?>