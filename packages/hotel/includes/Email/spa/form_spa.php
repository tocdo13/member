<?php
function MassageInvoiceEmailForm()
{
    $array = array(); 
	$array['room_number'] = DB::fetch('SELECT NAME FROM MASSAGE_ROOM WHERE ID = '.Url::iget('room_id').'','name');
	$currency=DB::select('currency','id=\'VND\'');
	$exchange_rate=$currency['exchange'];
	$array['exchange_rate'] = $exchange_rate;
	$item = DB::fetch('select massage_reservation_room.*,room.name as hotel_room, 
                        concat(TRAVELLER.FIRST_NAME, concat(\' \',concat(TRAVELLER.LAST_NAME,concat(\' \',massage_reservation_room.full_name)))) as guest_name, 
                        massage_guest.code 
                        from massage_reservation_room
                        left join reservation_room on reservation_room.id =  massage_reservation_room.hotel_reservation_room_id
                        left join room on reservation_room.room_id = room.id
                        left join traveller on reservation_room.traveller_id = traveller.id
                        left outer join massage_guest on massage_guest.id = guest_id where massage_reservation_room.id='.Url::iget('id'));  
   
    
	$total_amount_net = 0;
	$array['total_price'] = 0;
	$array['total_tip'] = 0;
	$array['total_quantity'] = 0;
	$array['total_amount_'] = 0;
	if($item)
    {
		$item['time'] = date('d/m/Y',$item['time']);
		$total_amount_net = $item['extra_charge'];
		$item['currencies'] = DB::fetch_all('select currency_id as id,amount,bill_id,exchange_rate,name from pay_by_currency inner join currency on currency.id = currency_id where bill_id='.$item['id'].' and type=\'MASSAGE\'');
		$item['usd_amount'] = $item['total_amount'];
		foreach($item['currencies'] as $k=>$v)
        {
			if($v['id']=='USD'){
				$item['currencies'][$k]['name'] = 'Creadit card';
			}
			$item['usd_amount'] -= round($v['amount']/$v['exchange_rate'],2);
			$item['currencies'][$k]['amount'] = number_format($v['amount']);
		}
		if($item['hotel_reservation_room_id'] and $row = DB::fetch('select reservation_room.id,room.name from reservation_room inner join room on room.id = reservation_room.room_id where reservation_room.id = '.$item['hotel_reservation_room_id'].''))
        {
			$item['hotel_room_number'] = $row['name'];
		}
        else
        {
			$item['hotel_room_number'] = '';
		}
		{
			$sql = '
				SELECT
					massage_staff_room.*,
					massage_staff.full_name
				FROM
					massage_staff_room
					INNER JOIN massage_staff ON massage_staff.id = staff_id
				WHERE
					massage_staff_room.reservation_room_id=\''.$item['id'].'\'
			';
			$mi_staff_group = DB::fetch_all($sql);
			foreach($mi_staff_group as $value)
            {
				$array['total_tip'] += $value['tip'];
			}
			$array['staffs'] = $mi_staff_group;
		} 
		{
			$sql = '
				SELECT
					massage_product_consumed.*,
                    (massage_product_consumed.price * massage_product_consumed.quantity) as amount,
					product.name_'.Portal::language().' as name,
                    product.name_2,
                    product.id as code,
					massage_room.name as room_name
				FROM
					massage_product_consumed
					INNER JOIN massage_room ON massage_room.id = room_id
					INNER JOIN product ON massage_product_consumed.product_id = product.id
				WHERE
					massage_product_consumed.reservation_room_id=\''.$item['id'].'\'
					'.(Url::get('room_id')?'AND massage_product_consumed.room_id = '.Url::iget('room_id').'':'').'
				ORDER BY
					massage_room.name
			';
			$mi_product_group = DB::fetch_all($sql);
			$i=1;
			foreach($mi_product_group as $key=>$value)
            {
				$mi_product_group[$key]['no'] = $i++;
				$mi_product_group[$key]['time_in'] = date('H:i\'',$value['time_in']);
				$mi_product_group[$key]['time_out'] = date('H:i\'',$value['time_out']);
                $time_in = date('H:i\'',$value['time_in']);
                $time_out = date('H:i\'',$value['time_out']);
				$total_amount_net +=$value['amount'];
				$mi_product_group[$key]['price'] = System::display_number($value['price']);
				$mi_product_group[$key]['amount'] = System::display_number($value['amount']);
				$array['total_price'] += $value['price'];
				$array['total_quantity'] += $value['quantity'];
				$array['total_amount_'] += $value['amount'];
			}
			$array['products'] = $mi_product_group;
		} 
        $array['time_in'] = $time_in;
        $array['time_out'] = $time_out;
		$array['total_amount_'] = System::display_number($array['total_amount_']);
		//KID CMT DOAN DUOI VA THAY BANG DOAN TINH THEO SETTING
		//$discount_amount = $total_amount_net*$item['discount']/100;
//			$item['discount_amount'] = System::display_number($discount_amount);
//			$item['tax_amount'] = System::display_number(($total_amount_net-$discount_amount)*$item['tax']/100);
//			$item['total_amount'] = System::display_number($array['total_tip'] + $total_amount_net - $discount_amount + (($total_amount_net-$discount_amount)*$item['tax']/100));
		
        if($item['net_price']==0)
        {
            $discount_amount = $total_amount_net*$item['discount']/100;
			$item['discount_amount'] = System::display_number($discount_amount);
            $item['service_rate_amount'] = (($total_amount_net-$discount_amount)*$item['service_rate']/100);
			$item['tax_amount'] = (($total_amount_net-$discount_amount)+($total_amount_net-$discount_amount)*$item['service_rate']/100)*$item['tax']/100;
			$item['total_amount'] =$array['total_tip'] + $total_amount_net - $discount_amount + $item['service_rate_amount'] + $item['tax_amount'];
        }
        else
        {
            $total_amount_net = ($total_amount_net*100/(100+$item['tax']))*100/(100+$item['service_rate']);
            
            $discount_amount = $total_amount_net*$item['discount']/100;
            
            $item['discount_amount'] = System::display_number($discount_amount);
            $item['service_rate_amount'] = (($total_amount_net-$discount_amount)*$item['service_rate']/100);
			$item['tax_amount'] = ((($total_amount_net-$discount_amount)+($total_amount_net-$discount_amount)*$item['service_rate']/100)*$item['tax']/100);
			
            $item['total_amount'] =$array['total_tip'] + ($total_amount_net - $discount_amount) + $item['service_rate_amount'] + $item['tax_amount'];
        }
        //het sua
		$array['total_tip'] = System::display_number($array['total_tip']);
	}
	return $array += $item;
    //system::debug($array);die();
}
?>