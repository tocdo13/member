<?php
function create_banquet_book($party_info,$mi_banquet_room,$mi_meeting_room,$mi_vegetarian,$mi_product,$mi_eating_product,$mi_service,$type,$mice_reservation_id)
{
    /**
     * (*): truong bat buoc nhap
     * $party_info : mang 1 chieu ve thong tin chung dat tiec
            full_name => ten khach (*)
            address => Dia chi khach
            identity_number => CMT
            email =>email
            party_type => loai tiec
            company_name => ten cty dat tiec (*)
            company_address => dia chi cong ty dat tiec
            company_phone => SDT cong ty dat tiec
            total_before_tax => tong truoc thue
            total => tong tien
            extra_service_rate => % dich vu mo rong (select)
            vat => % thue vat (select)
            user_id => nhan vien dat tiec
            time => ngay dat tiec (*)
            checkin_time=> Gio check in (*)
            checkout_time => gio check out (*)
            portal_id => portal
            
            promotions => danh sach khuyen mai
            ....
     * $mi_banquet_room: mang chua thong tin phong tiec
            party_room_id => ID phong tiec
            time_type => Loai thoi gian tiec( sang/chieu/toi)
            address => dia chi phong tiec
            //type = 2 => loai phòng
            price => gia phong tiec
            note => ghi chu phong tiec
        
     * $mi_meeting_room: mang chua thong tin phong tiec
            party_room_id => ID phong tiec
            time_type => Loai thoi gian tiec( sang/chieu/toi)
            address => dia chi phong tiec
            //type = 1  => loai phòng
            price => gia phong tiec
            note => ghi chu phong tiec
            
     * $mi_vegetarian: mang chua thong tin do an chay
            product_id => ID do an chay
            name => ten
            quantity => so luong
            price => gia
            unit => don vi
            type  => loai
        
     * $mi_product: mang chua thong tin do uong
            product_id => ID do uong
            name => ten
            quantity => so luong
            price => Gia
            type  => loai
            unit => don vi
            
     * $mi_eating_product: mmang chua thong tin do an
            product_id => ID do an
            name => ten
            quantity => so luong
            price => gia
            type  => loai
            unit => don vi
            
     * $mi_service: mang chua thong tin dich vu
            product_id => ID dich vu
            name => ten
            quantity => so luong
            price => gia
            type  => loai
            unit => don vi
      
     A. Tiệc cưới (party_type =1)
         1.Đồ ăn: $mi_eating_product
         2.Đồ ăn chay: $mi_vegetarian
         3.Đồ uống: $mi_product
         4.Dịch vụ: $mi_service
     B. Tiệc thôi nôi, sinh nhật,... (party_type = 2)
         1.Đồ ăn: $mi_eating_product
         2.Đồ ăn chay: $mi_vegetarian
         3.Đồ uống: $mi_product
         4.Dịch vụ: $mi_service
     C. Hội nghị (party_type = 3)
         1. ĐỒ uống: $mi_product
     D. Tiệc công ty hội nghị  (party_type = 4)
         1. Đồ ăn: $mi_eating_product
         2. Đồ uống: $mi_product
         3. Dịch vụ: $mi_service
     E. Tiệc công ty (party_type =5)
         1. Đồ ăn: $mi_eating_product
         2. Đồ uống: $mi_product
         3. Dịch vụ: $mi_service      
     * $type = true thi cho luu giu lieu vao o cac  bang , $type = false thi chi cho check du lieu
     * $mice_reservation_id: id cua mice
     **/
   
    $error = array();
    $er = 1;
    if(isset($party_info))
    {
        
        $full_name = $party_info['full_name'];
        $address = $party_info['address'];
        $identity_number = $party_info['identity_number'];
        $email = $party_info['email'];
        $party_type = $party_info['party_type'];
        //tiec meeting -meeting_company
        $company_name = $party_info['company_name'];
        $company_address = $party_info['company_address'];
        $company_phone = $party_info['company_phone'];
        //thanh toan
        $total_before_tax = $party_info['total_before_tax'];
        $total =$party_info['total'];
        $extra_service_rate= $party_info['extra_service_rate'];
        $vat = $party_info['vat'];
        $user_id = $party_info['user_id'];
        $party_info['time'] = Date_Time::to_time($party_info['time']);
        $time = $party_info['time'];
        //$checkin_time = $party_info['checkin_time'];
        //$checkout_time = $party_info['checkout_time'];
        
        $portal_id = $party_info['portal_id'];
        $party_info['mice_reservation_id']=$mice_reservation_id?$mice_reservation_id:'';
        
        $checkin_time_ar = explode(':',$party_info['checkin_time']);
        $checkout_time_ar = explode(':',$party_info['checkout_time']);
        $party_info['checkin_time'] = $time+$checkin_time_ar[0]*3600+$checkin_time_ar[1]*60;
        $party_info['checkout_time'] = $time+$checkout_time_ar[0]*3600+$checkout_time_ar[1]*60;	
         
        if($party_info['party_type'] == 4 || $party_info['party_type']== 5)
        {
            $party_info['meeting_checkin_hour'] = $party_info['checkin_time'];
            $party_info['meeting_checkout_hour'] = $party_info['checkout_time'];
        }
        
        
        //System::debug($party_info);
        if(!$error and $type)
        {
            /** Lay thong tin khuyen mai cua loai tiec **/
            if (Url::get_value('check_list'))
            {
                $str_promotions = '';
                foreach(Url::get_value('check_list') as $key=>$check)
                {
                    $str_promotions .=$check.' ';
                }
                $party_info['promotions'] = $str_promotions;
            } 
        	$party_id = DB::insert('party_reservation',$party_info + array('time'=>time(),'user_id'=>Session::get('user_id'),'status'=>'BOOKED'));
        	
            /** lay thong tin phong tiec **/
            $list_banquet_room = '';
            if(isset($mi_banquet_room) )
            {
                foreach($mi_banquet_room as $row=>$row_data)
                {
    				$banquet_reservation_room_id = $row_data['id'];
				unset($row_data['id']);
				$banquet_room['price'] = System::calculate_number($row_data['price']);
    				$banquet_room['party_room_id'] = $row_data['party_room_id'];
    				$banquet_room['time_type'] = $row_data['time_type'];
    				$banquet_room['note'] = $row_data['note'];
                    $banquet_room['address'] = $row_data['address'];
                    $banquet_room['type'] = 2;
                    $banquet_room['party_reservation_id'] = $party_id;
    				$list_banquet_room .= 'Product: '.$banquet_room['party_room_id'].', Price: '.$banquet_room['price'].'<br>';
    				$id = DB::insert('party_reservation_room',$banquet_room);
                    DB::update('party_reservation',array('time_type'=>$row_data['time_type']),'id='.$party_id);
    			}
            }
            /** lay thong tin phong meeting  **/
            $list_meeting_room = '';
    		if(isset($mi_meeting_room))
    		{
    		    foreach($mi_meeting_room as $row=>$row_data)
    			{
				$banquet_reservation_room_id = $row_data['id'];
				unset($row_data['id']);
    				$banquet_room['price'] = System::calculate_number($row_data['price']);
    				$banquet_room['party_room_id'] = $row_data['party_room_id'];
    				$banquet_room['time_type'] = $row_data['time_type'];
    				$banquet_room['note'] = $row_data['note'];
                    $banquet_room['address'] = $row_data['address'];
                    $banquet_room['type'] = 2;
                    //$banquet_room['type'] = 1;
                    $banquet_room['party_reservation_id'] = $party_id;
    				$list_meeting_room .= 'Product: '.$banquet_room['party_room_id'].', Price: '.$banquet_room['price'].'<br>';
    				$id = DB::insert('party_reservation_room',$banquet_room);
                    DB::update('party_reservation',array('time_type'=>$row_data['time_type']),'id='.$party_id);
    			}
    		}
            
            /** lay thong tin ve do chay **/
            if(isset($mi_vegetarian))
    		{
    			foreach($mi_vegetarian as $row=>$row_data)
    			{
    				$product['price'] = System::calculate_number($row_data['price']);
    				$product['product_name'] = $row_data['name'];
    				$product['product_id'] = $row_data['product_id'];
    				$product['quantity'] = $row_data['quantity'];
                    $product['product_unit'] = $row_data['unit'];
                    $product['type'] = 4;
                    $product['party_reservation_id'] = $party_id;
    				$id = DB::insert('party_reservation_detail',$product);
    			}
    		}
             /** End lay thong tin ve do chay **/
            
            /** Lay thong tin ve do uong **/			
    		if(isset($mi_product))
    		{
    			foreach($mi_product as $row=>$row_data)
    			{
    				$product['price'] = System::calculate_number($row_data['price']);
    				$product['product_id'] = $row_data['product_id'];
                    $product['product_name'] = $row_data['name'];
    				$product['quantity'] = $row_data['quantity'];
                    $product['type'] = 1;
                    $product['product_unit'] = $row_data['unit'];
                    $product['party_reservation_id'] = $party_id;
    				$id = DB::insert('party_reservation_detail',$product);
    			}
    		}
            
            /** lay thong tin ve do an **/
            if(isset($mi_eating_product))
    		{
    			foreach($mi_eating_product as $row=>$row_data)
    			{
    				
    				$product['price'] = System::calculate_number($row_data['price']);
    				$product['product_name'] = $row_data['name'];
    				$product['product_id'] = $row_data['product_id'];
    				$product['quantity'] = $row_data['quantity'];
                    $product['type'] = 2;
                    $product['product_unit'] = $row_data['unit'];
                    $product['party_reservation_id'] = $party_id;
    				$id = DB::insert('party_reservation_detail',$product);
    			}
    		}
            
            /** lay thong tin ve dich vu **/
            if(isset($mi_service))
    		{
    			foreach($mi_service as $row=>$row_data)
    			{
    				$product['price'] = System::calculate_number($row_data['price']);
    				$product['product_name'] = $row_data['name'];
    				$product['product_id'] = $row_data['product_id'];
    				$product['quantity'] = $row_data['quantity'];
                    $product['type'] = 3;
                    $product['product_unit'] = $row_data['unit'];
                    $product['party_reservation_id'] = $party_id;
    				$id = DB::insert('party_reservation_detail',$product);
    			}
    		}
        }
    }
    else
    {
        $error[$er]['note'] = Portal::language('miss_infor');
    }
    return $error;
}

function get_banquet_room(){
		$sql = '
			select 
				party_room.id,
				party_room.name,
				party_room.group_name,
				party_room.price,
                party_room.address,
				party_room.price_half_day
			from 
				party_room
            where
                portal_id = \''.PORTAL_ID.'\'
			order by
				party_room.id
		';
		$banquet_rooms = DB::fetch_all($sql);
		return $banquet_rooms;
}  

function get_product_banquet()
{
    require_once 'packages/core/includes/system/config.php';
	require_once 'packages/core/includes/utils/vn_code.php';
    $cond = '';
    if(Url::get('product_type')=='SERVICE')
    {
        $cond .= ' AND product.type = \'SERVICE\' '; 
    }
    else
    if(Url::get('product_type')=='PRODUCT')
    {
        $cond .= ' AND product.type = \'PRODUCT\' '; 
    }
    else
    {
        $cond .= ' AND product.type != \'PRODUCT\' AND product.type != \'SERVICE\' ';
    }
	$sql = '
			select 
                product_price_list.id,
				product_price_list.product_id as code,
				product.name_'.Portal::language().' as name
			from
				product_price_list
                INNER JOIN product on product.id = product_price_list.product_id
			where
			(UPPER(product_price_list.product_id) LIKE \'%'.strtoupper(Url::sget('q')).'%\'
				OR ((LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\' OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('q'),'utf-8')).'%\')))						
                AND product_price_list.portal_id=\''.PORTAL_ID.'\'
                AND product_price_list.department_code = \'BANQUET\'
                '.$cond.'
                AND product.status = \'avaiable\'
			order by
				product_price_list.product_id
	       ';
    $product_banquet= DB::fetch_all($sql);
    //System::debug($product_banquet);
    foreach($product_banquet as $key=>$value)
    {
        echo $value['code'].'|'.$value['name']."\n";
    }
}

?>