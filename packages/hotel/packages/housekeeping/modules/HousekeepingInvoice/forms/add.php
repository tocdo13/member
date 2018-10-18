<?php
class AddHousekeepingInvoiceForm extends Form
{
	function AddHousekeepingInvoiceForm()
	{
		Form::Form('AddHousekeepingInvoiceForm');
		$this->add('total',new FloatType(true,'invalid_total','0','100000000000')); 
		$this->add('discount',new FloatType(false,'invalid_discount','0','100000000000'));
		$this->add('prepaid',new FloatType(false,'invalid_prepaid','0','100000000000')); 
		$this->add('reservation_room_id',new IDType(false,'invalid_reservation_id','reservation_room')); 
		$this->add('housekeeping_invoice_detail.price',new FloatType(true,'invalid_price','0','100000000000')); 
		$this->add('housekeeping_invoice_detail.quantity',new FloatType(true,'invalid_quantity','0','100000000000')); 
		$this->add('housekeeping_invoice_detail.product_id',new IDType(true,'invalid_product_id','product')); 
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/calendar.js');

	}
	function on_submit()
	{
		if($this->check())
		{
			$reservation_room = DB::select('reservation_room',URL::get('reservation_room_id'));
			$minibar = DB::select('minibar','room_id=\''.$reservation_room['room_id'].'\'');
			$id = DB::insert('housekeeping_invoice', 
				array(
					'reservation_room_id'=>$reservation_room['reservation_room_id'], 
					'minibar_id'=>$minibar['id'], 
					'user_id'=>Session::get('user_id'),
					'last_modifier_id'=>Session::get('user_id'),
					'type'=>'SERVICE',
					'tax_rate','fee_rate',
					'total'=>System::calculate_number(URL::get('total')),
					'total_before_tax'=>System::calculate_number(URL::get('total_before_tax')),
					'prepaid'=>System::calculate_number(URL::get('prepaid')),
					'discount'=>System::calculate_number(URL::get('discount')),
					'time'=>time()
				)
			);
			$content = 'Use housekeeping service <a href="?page=housekeeping_invoice&id='.$id.'">#'.$id.'</a> total='.URL::get('total').'$';
			$product_description = '';
			if(isset($_REQUEST['mi_housekeeping_invoice_detail']))
			{
				foreach($_REQUEST['mi_housekeeping_invoice_detail'] as $key=>$record)
				{
					$content.='<br>'.$record['quantity'].' '.$key.' '.$record['price'].'';
					$record['price'] = System::calculate_number($record['price']);
					$record['price']*=$rate;
					unset($record['total']); 
					unset($record['id']);
					$record['invoice_id'] = $id;
					DB::insert('housekeeping_invoice_detail',$record);
					if($product=DB::select('hk_product',$record['product_id']))
					{
						$product_description .= $record['quantity'].' <a href="?page=product&id='.$product['id'].'">'.$product['name_'.Portal::language()].'</a><br>
';
						if(($product['type']=='PRODUCT' or $product['type']=='GOODS'))
						{
							if(DB::select('store_product','store_id=\'7\' and product_id=\''.$record['product_id'].'\''))
							{
								DB::query('update store_product set quantity=quantity-('.($record['quantity']).') where store_id=\'7\' and product_id=\''.$record['product_id'].'\'');
							}
							else
							{
								DB::insert('store_product',
									array(
										'store_id'=>7,
										'product_id'=>$record['product_id'],
										'quantity'=>$record['quantity']
									)
								);
							}
						} 
					}
				}
			} 
			$travellers = DB::select_all('reservation_traveller','reservation_room_id=\''.$reservation_room['id'].'\'');
			foreach($travellers as $traveller)
			{
				DB::insert('traveller_comment',
					array(
						'user_id'=>Session::get('user_id'),
						'traveller_id'=>$traveller['traveller_id'],
						'time'=>time(),
						'content'=>$content
					)
				);
			}
			System::log('add','Add housekeeping invoice at minibar '.$minibar['name'],
'Code:<a href="?page=housekeeping_invoice&id='.$id.'">'.$id.'</a><br>
Total money:'.URL::get('total').HOTEL_CURRENCY.'<br>
Reservation code: <a href="?page=reservation&id='.$reservation_room['reservation_room_id'].'">'.$reservation_room['reservation_room_id'].'</a><br>
<b>Services:</b><br>
'.$product_description);
			URL::redirect_current(array('id'=>$id));
		}
	}
	function draw()
	{
		require_once 'packages/hotel/includes/php/hotel.php';
		$services = DB::fetch_all('select id, name_'.Portal::language().' as name from hk_product where type=\'SERVICE\'  and category_id<>\''.DB::fetch('select id from hk_product_category where code=\'GL\'','id').'\' order by name');
		$product_id_options = '';
		foreach($services as $item)
		{
			$product_id_options .= '<option value="'.$item['id'].'">'.$item['id'].'</option>';
		}
		DB::query('
			select
				reservation_room.id, 
				concat(concat(traveller.first_name,\' \'),traveller.last_name) as agent_name, 
				room.name
			from 
				minibar 
				inner join room on room.id = minibar.room_id 
				inner join reservation_room on reservation_room.room_id = room.id 
				inner join traveller on traveller.id=reservation_room.traveller_id
			where 
				reservation_room.status=\'CHECKIN\'
				and room_status.in_date = \''.Date_time::to_orc_date(date('d/m/Y')).'\'
				status=\'CHECKIN\' 
			order by name
			'
		);
		$reservation_rooms = DB::fetch_all(); 

		
		HousekeepingInvoice::get_js_variables_data();
		
		$this->parse_layout('add',
			array(
			'date'=>date('d/m/Y',time()),
			'reservation_room_id_list'=>array(0=>'')+String::get_list($reservation_rooms),
			'reservation_room_id'=>0, 
			'rooms'=>$reservation_rooms,
			'product_id_options' => $product_id_options, 
			'currency_id_list'=>String::get_list(DB::select_all('currency')),
			'currency_id'=>URL::get('currency_id','USD'),
			'currencies'=>DB::select_all('currency')
			)
		);
	}
}
?>