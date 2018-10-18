<?php
class KaraokeReservationNewForm extends Form
{
	function KaraokeReservationNewForm()
	{
		Form::Form("KaraokeReservationNewForm");
		DB::query('select status from karaoke_reservation where id=\''.Url::get('id').'\' and status in (\'CHECKIN\',\'CHECKOUT\')');
		if(DB::fetch())
		{
			Url::redirect_current(array('cmd'=>'detail','id'));
		}
		else
		{
			$this->add('id',new IDType(true,'object_not_exists','karaoke_reservation'));
		}
	}
	function draw()
	{
		$row = DB::select('karaoke_reservation',Url::get('id'));
		$order_id = '';
		for($i=0;$i<6-strlen($row['id']);$i++)
		{
			$order_id .= '0';
		}
		$order_id .= $row['id'];
		
		//============================== currency ================================
		
		//============================== karaoke_table ===============================
		$tables = DB::select_all('karaoke_table',false,'id');
		$i=0;
		foreach($tables as $k=>$tbl)
		{
			$tables[$k]['stt']=$i;
			$i++;
		}
		

		$table_items = KaraokeReservationNewDB::get_karaoke_table(Url::get('id'));
		$num_tables = DB::affected_rows();
		
		$product_items = KaraokeReservationNewDB::get_reservation_product();
		$total = 0;
		foreach($product_items as $key=>$value)
		{
			$product_items[$key]['product__id'] = $value['product_id'];
			$product_items[$key]['product__name'] = $value['name'];
			$product_items[$key]['product__quantity'] = $value['quantity'];
			$product_items[$key]['product__unit'] = $value['unit_name'];
			$total += $value['price']*$value['quantity'];
			$product_items[$key]['product__total'] = $value['price']*$value['quantity'];
		}
		if($row['arrival_time']>0 and $row['arrival_time']!='')
		{
			$row['arrival_date']=date('d/m/Y',$row['arrival_time']);
			$row['time_in_hour']=date('H',$row['arrival_time']);
			$row['time_in_munite']=date('i',$row['arrival_time']);
		}
		else
		{
			$row['arrival_time']='';
			$row['time_in_hour']=0;
			$row['time_in_munite']=0;
		}
		if($row['departure_time']>0 and $row['departure_time']!='')
		{
			$row['time_out_hour']=date('H',$row['departure_time']);
			$row['time_out_munite']=date('i',$row['departure_time']);
		}
		else
		{
			$row['time_out_hour']=0;
			$row['time_out_munite']=0;
		}
		
		$row['summary'] = System::display_number_report($total);
		$row['karaoke_fee'] = System::display_number_report($total*5/100);
		$row['sum_total'] = System::display_number_report($row['total']);
		$row['deposit'] = System::display_number_report($row['deposit']);
		if(defined('HOTEL_STAFF'))
		{
			$room = DB::select('room',$row['room_id']);
			DB::query('
				select
					reservation_room.id, CONCAT(traveller.first_name,\' \',traveller.last_name) as name
				from
					reservation_room
					left outer join traveller on traveller.id=reservation_room.traveller_id
				where reservation_room.id=\''.$row['reservation_room_id'].'\'');
			$reservation=DB::fetch();
			
			$hotel = array(
				'room_name'=>$room['name'],
				'reservation_name'=>$reservation['name'],
			);
		}
		else
		{
			$hotel = array();
		}		
		$this->parse_layout('detail',$row+$hotel+array(
			'order_id'=>$order_id,
			'num_tables'=>$num_tables,
			'date'=>date('d/m/Y',time()),
			'table_items'=>$table_items,
			'product_items'=>$product_items,
		));
	}
}
?>