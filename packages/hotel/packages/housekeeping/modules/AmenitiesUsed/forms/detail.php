<?php
class DetailAmenitiesUsedForm extends Form
{
	function DetailAmenitiesUsedForm()
	{
		Form::Form('DetailAmenitiesUsedForm');
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
            
            //L?y c�c ph�ng v� g�n sp v�o d�, qua bu?c n�y c�c ph�ng d?u c� s?n ph?m gi?ng nhau
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
                        //S? lu?ng =  th� x�a b?n ghi
                        if(System::calculate_number($quantity)==0)
                            DB::delete('amenities_used_detail',' room_id = '.$room_id.' and product_id = \''.$product_id.'\' and amenities_used_id = '.Url::get('id'));
                        else
                            DB::update('amenities_used_detail',array('quantity'=>System::calculate_number($quantity)),' room_id = '.$room_id.' and product_id = \''.$product_id.'\' and amenities_used_id = '.Url::get('id'));
                    }
                }   
            }
            //V?n c�n t?n t?i b?n ghi trong detail th� update l?i
            if( DB::exists('Select * from amenities_used_detail where amenities_used_id = '.Url::get('id')) )
                DB::update_id( 'amenities_used',array( 'last_modify_user_id'=>Session::get('user_id'),'last_modify_time'=>time() ),Url::get('id') );
            else//N?u kh�ng c�n detail th� x�a b?n ghi ch�nh
                DB::delete_id('amenities_used',Url::get('id'));
                
			Url::redirect_current();
		}
	}	
	function draw()
	{		
		//-------------------------------------------------------------------------------
        //L?y c�c amenities d� du?c khai b�o trong ph�ng
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
        
        //L?y c�c ph�ng v� g�n sp v�o d�, qua bu?c n�y c�c ph�ng d?u c� s?n ph?m gi?ng nhau
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
        //System::debug($rooms);
		foreach($rooms as $key=>$minibar)
		{
			$rooms[$key]['products'] = $room_amenities;
		}
        //System::debug($rooms);
        		
//-------------------------------------------------------------------------------
        //L?y c�c sp trong c�c ph�ng
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
			';
		$products=DB::fetch_all($sql);
        //System::debug($products);
        //System::debug($minibars);
//-------------------------------------------------------------------------------
        //is_real : ph�n bi?t ph�ng n�o d� dc khai b�o, ph�ng n�o chua
		foreach($products as $product)
		{
			$rooms[$product['room_id']]['products'][$product['product_id']] = $product;
		}
        //System::debug($rooms);
//-------------------------------------------------------------------------------
		$layout='detail';
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