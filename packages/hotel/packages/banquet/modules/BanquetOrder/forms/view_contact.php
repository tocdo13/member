<?php
class ViewContact extends Form
{
	function ViewContact()
	{
		Form::Form('ViewContact');
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
                $reservation_detail = DB::fetch_all('
                    select
                    party_reservation_detail.id,
                    party_reservation_detail.type,
                    product.name_1 as name
                    from
                    party_reservation_detail inner join product
                    on
                    product.id = party_reservation_detail.product_id
                    where
                    party_reservation_detail.party_reservation_id = '.Url::get('id'));
                //System::debug($reservation_detail);
                $i = 1;
                $j = 1;
                $k = 1;
                foreach($reservation_detail as $key => $value)
                {
                    if($value['name'] != '' and $value['type'] == 1)
                    {
                        $row['drinking_'.$i] = $value['name'];
                        $i += 1;
                    }
                    if($value['name'] != '' and $value['type'] == 2)
                    {
                        $row['eating_'.$j] = $value['name'];
                        $j += 1;
                    }
                    if($value['name'] != '' and $value['type'] == 2)
                    {
                        $row['service_'.$k] = $value['name'];
                        $k += 1;
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