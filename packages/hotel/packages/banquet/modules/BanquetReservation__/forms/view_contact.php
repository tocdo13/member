<?php
class ViewContact1 extends Form
{
	function ViewContact1()
	{
		Form::Form('ViewContact1');
	}	
	function draw()
	{
	   if(Url::get('id') and $row = DB::fetch('select party_reservation.* from party_reservation where party_reservation.id = '.Url::get('id')))
       
       {
            $reservation_room = DB::fetch_all('select 
                    party_reservation_room.id,
                    party_reservation_room.type,
                    party_room.name,
                    party_room.group_name,
                    party_reservation_room.address,
                    party_reservation_room.price
                    from 
                    party_reservation_room inner join party_room 
                    on 
                    party_reservation_room.party_room_id = party_room.id 
                    where 
                    party_reservation_room.party_reservation_id = '.Url::get('id'));
                //System::debug($reservation_room);
                //System::debug($row);
                $row['meeting_room_name'] = false;
                $row['meeting_room_address'] = false;
                $row['meeting_room_price'] = false;
                $row['meeting_room_group_name'] = false;
                $row['banquet_room_name'] = false;
                $row['banquet_room_address'] = false;
                $row['banquet_room_price'] = false;
                $row['banquet_room_group_name'] = false;
                foreach($reservation_room as $key => $value)
                {   
                    
                    if($value['type'] == 1)
                    {
                        $row['meeting_room_name'] = $value['name'];
                        $row['meeting_room_address'] = $value['address'];
                        $row['meeting_room_price'] = $value['price'];
                        $row['meeting_room_group_name'] = $value['group_name'];
                    }
                    if($value['type'] == 2)
                    {
                        $row['banquet_room_name'] = $value['name'];
                        $row['banquet_room_address'] = $value['address'];
                        $row['banquet_room_price'] = $value['price'];
                        $row['banquet_room_group_name'] = $value['group_name'];
                    }
                }
              $promo = DB::fetch('select promotions from party_reservation where id = '.Url::get('id'),'promotions');
                if($promo != '')
                {
                    $promo_arr = explode(' ',$promo);
                    //System::debug($promo_arr);
                    foreach($promo_arr as $value)
                    {
                        $value = System::calculate_number($value);
                        if($value != '' and $pro = DB::fetch('select * from party_promotions where id = '.$value))
                        {
                            $row['promotions_list'][$value] = array();
                            $row['promotions_list'][$value] = $pro;
                            //System::debug($pro);
                        }
                    }
                }
                //System::debug($promo_arr);
                $reservation_detail = DB::fetch_all('
                    select
                    party_reservation_detail.id,
                    party_reservation_detail.type,
                    party_reservation_detail.price as product_price,
                    CASE
                        WHEN
                                party_reservation_detail.product_name is not NULL    
                        THEN
                            	party_reservation_detail.product_name 
                        ELSE
                                product.name_'.Portal::language().'          
                    END name
                    from
                    party_reservation_detail inner join product
                    on
                    product.id = party_reservation_detail.product_id
                    where
                    party_reservation_detail.party_reservation_id = '.Url::get('id').' order by party_reservation_detail.id asc');
                    
                //System::debug($reservation_detail);
                
                $i = 1;
                $j = 1;
                $k = 1;
                $h = 1;
                $row['giaan'] =false;
                $row['num_eating']=false;
                $row['num_service']=false;
                $row['num_drinking'] =false;
                $row['num_vegetarian'] =false;
                foreach($reservation_detail as $key => $value)
                {
                    
                   
                    if($value['name'] != '' and $value['type'] == 1)
                    {   
                        $row['drinking'][$i] = $value['name'];
                        $row['drink_price'][$i] = $value['product_price'];
                         $i += 1;
                        $row['num_drinking']=$i;
                        
                     }
                     if($value['name'] != '' and $value['type'] == 2)
                    {
                        $row['eating'][$j] = $value['name'];
                        $row['eating_price'][$j] = $value['product_price'];
                        if(!isset($row['giaan'])) $row['giaan']=0;
                        $row['giaan']+=$row['eating_price'][$j];
                        $j += 1;
                        $row['num_eating']=$j;
                    }
                    if($value['name'] != '' and $value['type'] == 3)
                    {
                        $row['service'][$k] = $value['name'];
                        $row['service_price'][$k] = $value['product_price'];
                        $k += 1;
                        $row['num_service']=$k;
                    }
                    if($value['name']!= '' and $value['type'] == 4)
                    {
                        $row['vegetarian'][$h] = $value['name'];
                        $row['vegetarian_price'][$h] = $value['product_price'];
                        
                        $h += 1;
                        $row['num_vegetarian']=$h;
                    }
                }
            if ($row['party_type'] == 4)
            {
                //System::debug($row);
                $this ->parse_layout('view_contact',$row);   
            }
            if ($row['party_type'] == 5)
            {
                //System::debug($row);
                $this ->parse_layout('company_contact',$row);   
            }
            if ($row['party_type'] == 1)
            {
                //System::debug($row);
                $this ->parse_layout('wedding_contact',$row);   
            }
            if ($row['party_type'] == 2)
            {
                //System::debug($row);
                $this ->parse_layout('birthday_contact',$row);   
            }
            if ($row['party_type'] == 3)
            {
                //System::debug($row);
                $this ->parse_layout('meeting_contact',$row);   
            }
       }	
    }
}
?>