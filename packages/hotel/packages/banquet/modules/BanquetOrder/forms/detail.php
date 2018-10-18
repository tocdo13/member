<?php
class DetailBanquetForm extends Form
{
	function DetailBanquetForm()
	{
		Form::Form("DetailBanquetForm");
		$this->add('id',new IDType(true,'object_not_exists','bar_reservation'));
	}
	function draw()
	{ 
		if(Url::get('id') and $row = DB::fetch('Select party_reservation.*, party_type.name as party_type_name from party_reservation inner join party_type on party_reservation.party_type = party_type.id where party_reservation.id =  '.Url::get('id')))
		{
            //System::debug($row);
            if(($row['deposit_1'] + $row['deposit_2'] + $row['deposit_3'] + $row['deposit_4']) == 0)
                $row['deposit'] = 0;
            else
                {
                    $row['deposit'] = ($row['deposit_1'] + $row['deposit_2'] + $row['deposit_3'] + $row['deposit_4']);
                    $row['deposit'] = $row['deposit']?System::display_number($row['deposit']):'';
                }
            $row['deposit_date'] = $row['deposit_date']?Portal::language('date').' '.Date_Time::convert_orc_date_to_date($row['deposit_date'],'/'):''; 
			$row['total'] = $row['total']?System::display_number($row['total']):'';
            $row['extra_service_rate'] = $row['extra_service_rate']?$row['extra_service_rate']:5;
            $row['vat'] = $row['vat']?$row['vat']:10;
            $total_before_tax_new = $row['total_before_tax'];
            unset($row['total_before_tax']);
            $order_id = $row['id'];
			$banquet_menu = DB::fetch_all('
				SELECT
					party_reservation_detail.id,
					party_reservation_detail.product_id,
					product.name_'.Portal::language().' as name,
                    party_reservation_detail.price,
                    party_reservation_detail.quantity
				FROM
					party_reservation_detail
					INNER JOIN product ON party_reservation_detail.product_id = product.id
				WHERE
					party_reservation_detail.party_reservation_id = '.$order_id.'
				ORDER BY
					party_reservation_detail.id
			');
            $total_before_tax = 0;
            $total_food = 0;
            foreach($banquet_menu as $key=>$value)
            {
                $total_before_tax+= $value['price'] * $value['quantity'];  
                $total_food += $value['price'] * $value['quantity'];
            }
            //System::debug($banquet_menu);
			$banquet_rooms = DB::fetch_all('
				SELECT
					party_reservation_room.id,
					party_room.group_name,
                    party_room.name,
                    party_reservation_room.note,
                    party_reservation_room.price as room_price,
                    DECODE(
                        party_reservation.time_type, \'DAY\', party_room.price,
                                                              party_room.price_half_day      
                    ) as price
				FROM
					party_reservation_room
					INNER JOIN party_room ON party_room.id = party_reservation_room.party_room_id
                    INNER JOIN party_reservation ON party_reservation.id = party_reservation_room.party_reservation_id
				WHERE
					party_reservation_room.party_reservation_id = '.$order_id.'
			');
			$banquet_room = '';
			$i = 1;
            $total_room = 0;
			foreach($banquet_rooms as $key=>$value)
			{
                $total_before_tax += $value['price'];
                $total_room += $value['room_price'];
				if($i>1)
				{
					$banquet_room.= ', '.$value['group_name'];
				}
				else
				{
					$banquet_room.= $value['group_name'];
				}
				$i++;
			}
            
            $desc = System::display_number($total_before_tax_new - $total_room).' '.Portal::language('total_menu').' & '.System::display_number($total_room).' '.Portal::language('party_room');
            //System::debug($banquet_rooms);
            //System::debug($banquet_room);
            if($row['party_category']=='FULL_PRICE')
            {
                $total_before_tax = $row['num_people'] * $row['price_per_people'];
                $desc = System::display_number($row['num_people']).' '.Portal::language('people').' x '.System::display_number($row['price_per_people']).' '.Portal::language('price_per_people');
            }
            if($row['promotions'] != '')
            {
                $promo_str = $row['promotions'];
                $promotions_arr = explode(' ', $promo_str);
                //System::debug($promotions_arr);
                foreach($promotions_arr as $key => $value)
                {
                    $promotion[$key]['id'] = $value;
                    $pro = DB::select_id('party_promotions',$value);
                    $promotion[$key]['name'] = $pro['name'];
                }
            }
            //System::debug($promotions);
            //System::debug($row);
            if(isset($promotion))
                $this->map['promotion'] = $promotion;
            $this->map['total_before_tax'] = System::display_number($total_before_tax_new);
            $service_fee = $total_before_tax_new * $row['extra_service_rate'] / 100;
            $this->map['service_fee'] = System::display_number($service_fee);
            $this->map['tax_fee'] = System::display_number( ($row['vat'] /100 ) * ( $total_before_tax_new + $service_fee ) );
            $this->map['description'] = $desc;
			$this->parse_layout('detail',$row+array(
				'order_id'=>$order_id,
				'banquet_menu'=>$banquet_menu,
				'banquet_room'=>$banquet_room,
                'banquet_rooms'=>$banquet_rooms,
				'date'=>date('d/m/Y',time()),
			)+$this->map);
		}
	}
}
?>