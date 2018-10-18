<?php
class EditHousekeepingInvoiceForm extends Form
{
	function EditHousekeepingInvoiceForm()
	{
		Form::Form('EditHousekeepingInvoiceForm');
		$this->add('id',new IDType(true,'object_not_exists','housekeeping_invoice'));
		$this->add('prepaid',new FloatType(false,'invalid_prepaid','0','100000000000')); 
		$this->add('total',new FloatType(false,'invalid_total','0','100000000000')); 
		$this->add('discount',new FloatType(false,'invalid_discount','0','100000000000')); 
		$this->add('reservation_room_id',new IDType(false,'invalid_reservation_id','reservation_room')); 
		$this->add('housekeeping_invoice_detail.price',new FloatType(true,'invalid_price','0','100000000000')); 
		$this->add('housekeeping_invoice_detail.quantity',new FloatType(true,'invalid_quantity','0','100000000000')); 
		$this->add('housekeeping_invoice_detail.product_id',new IDType(true,'invalid_product_id','product')); 
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/calendar.js');
	}
	function on_submit()
	{
		$row = DB::select('housekeeping_invoice',$_REQUEST['id']);
		if($this->check())
		{
			$rate = DB::fetch('select exchange from currency where name=\''.URL::get('currency_id').'\'','exchange');
			$reservation_room = DB::select('reservation_room',URL::get('reservation_room_id'));
			$minibar = DB::select('minibar','room_id=\''.$reservation_room['room_id'].'\'');
			DB::update_id('housekeeping_invoice', 
				array(
					'total'=>System::calculate_number(URL::get('total'))*$rate,
					'total_before_tax'=>System::calculate_number(URL::get('total_before_tax'))*$rate,
					'prepaid'=>System::calculate_number(URL::get('prepaid'))*$rate,
					'discount'=>System::calculate_number(URL::get('discount'))*$rate,
					'last_modifier_id'=>Session::get('user_id'),
					'reservation_room_id', 
					'minibar_id'=>$minibar['id'], 
					'reservation_room_id'=>$reservation_room['reservation_room_id'],
					'tax_rate',
					'currency_id',
					'exchange_rate'=>$rate
				),
				$_REQUEST['id']
			);
			$old_items = DB::select_all('housekeeping_invoice_detail','invoice_id='.$_REQUEST['id']);
			$product_description = '';
			if(isset($_REQUEST['mi_housekeeping_invoice_detail']))
			{
				foreach($_REQUEST['mi_housekeeping_invoice_detail'] as $key=>$record)
				{
					unset($record['total']); 
					$record['invoice_id'] = $_REQUEST['id'];
					$record['price'] = System::calculate_number($record['price']);
					$record['price'] = $record['price']*$rate;
					if($record['id'] and isset($old_items[$record['id']]))
					{
						$row = DB::select('housekeeping_invoice_detail','id='.$record['id']);
						$inc_quantity = $record['quantity']-$row['quantity'];
						DB::update('housekeeping_invoice_detail',$record,'id='.$record['id']);
						$old_items[$record['id']]['not_delete'] = true;
					}
					else
					{
						unset($record['id']);
						DB::insert('housekeeping_invoice_detail',$record);
						$inc_quantity = $record['quantity'];
					}
					if($product = DB::select('hk_product',$record['product_id']))
					{
						$product_description .= $record['quantity'].' <a href="?page=product&id='.$product['id'].'">'.$product['name_'.Portal::language()].'</a><br>
';
						if(($product['type']=='PRODUCT' or $product['type']=='GOODS'))
						{
							if(DB::select('store_product','store_id=\'7\' and product_id=\''.$record['product_id'].'\''))
							{
								DB::query('update store_product set quantity=quantity-('.($inc_quantity).') where store_id=\'7\' and product_id=\''.$record['product_id'].'\'');
							}
							else
							{
								DB::insert('store_product',
									array(
										'store_id'=>7,
										'product_id'=>$record['product_id'],
										'quantity'=>$inc_quantity
									)
								);
							}
						} 
					}
				}
			}
			foreach($old_items as $item)
			{
				if(!isset($item['not_delete']))
				{
					DB::delete_id('housekeeping_invoice_detail',$item['id']);
				}
			} 
			System::log('edit','Edit housekeeping invoice at minibar '.$minibar['name'],
				'
Code:<a href="?page=housekeeping_invoice&id='.URL::get('id').'">'.URL::get('id').'</a><br>
Total money:'.URL::get('total').URL::get('currency_id').'<br>
Reservation code: <a href="?page=reservation&id='.$reservation_room['reservation_room_id'].'>'.$reservation_room['reservation_room_id'].'</a><br>
<b>Services:</b><br>
'.$product_description);
			URL::redirect_current(array('id'));
		}
	}	
	function draw()
	{	
		DB::query('
			select 
				*,
				total,
				prepaid,
				(total+discount)*100/(100-tax_rate) as total_before_tax
			from 
			 	housekeeping_invoice
			where
				id = '.$_REQUEST['id'].'
		');
		$row = DB::fetch();
		
		$db_items = DB::fetch_all('select id, name_'.Portal::language().' as name from hk_product where type=\'SERVICE\'  and category_id<>'.DB::fetch('select id from hk_product_category where code=\'GL\'','id').' order by name');
		$db_items = $db_items+DB::fetch_all('
			select 
				hk_product.id, 
				hk_product.name_'.Portal::language().' as name 
			from 
				hk_product 
				inner join minibar_product on product_id=hk_product.code
			where
				hk_product.status<>\'DISABLED\'
			order by name');
		
		$product_id_options = '';
		foreach($db_items as $item)
		{
			$product_id_options .= '<option value="'.$item['id'].'">'.$item['id'].'</option>';
		}

		if(!isset($_REQUEST['mi_housekeeping_invoice_detail']))
		{
			DB::query('
				select
					housekeeping_invoice_detail.id
					,housekeeping_invoice_detail.price/'.($row['exchange_rate']?$row['exchange_rate']:1).' as price
					,housekeeping_invoice_detail.detail  
					,housekeeping_invoice_detail.quantity  
					,housekeeping_invoice_detail.product_id 
				from
					housekeeping_invoice_detail
				where
					housekeeping_invoice_detail.invoice_id='.$_REQUEST['id']
			);
			$mi_housekeeping_invoice_detail = DB::fetch_all();
			foreach($mi_housekeeping_invoice_detail as $key=>$value)
			{
				$mi_housekeeping_invoice_detail[$key]['price'] = System::display_number($value['price']); 
				$mi_housekeeping_invoice_detail[$key]['quantity'] = System::display_number($value['quantity']);  
			}
			$_REQUEST['mi_housekeeping_invoice_detail'] = $mi_housekeeping_invoice_detail;
			
		} 
		DB::query('
			select
				reservation_room.id, 
				concat(concat(traveller.first_name,\' \'),traveller.last_name) as agent_name, 
				room.name
			from 
				minibar 
				inner join room on room.id = minibar.room_id 
				left outer join reservation_room on reservation_room.room_id = room.id 
				left outer join traveller on traveller.id=reservation_room.traveller_id
			where 
				status=\'CHECKIN\' 
			order by
				name
			'
		);
		$reservation_rooms = DB::fetch_all(); 
		
		foreach($row as $key=>$value)
		{
			if(is_string($value) and !isset($_REQUEST[$key]))
			{
				$_REQUEST[$key] = $value;
			}
		}
		
		$row['date'] = date('d/m/Y',$row['time']);
		$row['type'] = URL::get('type',$row['type']);
		
		HousekeepingInvoice::get_js_variables_data();
		
		$this->parse_layout('edit',
			$row+
			array(
				'reservation_room_id_list'=>String::get_list($reservation_rooms),
				'reservation_room_id'=>URL::get('reservation_room_id',DB::fetch('select id from reservation_room where reservation_room_id=\''.$row['reservation_room_id'].'\' and room_id=\''.DB::fetch('select room_id from minibar where id=\''.$row['minibar_id'].'\'','room_id').'\'','id')),
				'product_id_options'=>$product_id_options,
				'rooms'=>$reservation_rooms,
				'currency_id_list'=>String::get_list(DB::select_all('currency')),
				'currency_id'=>URL::get('currency_id',HOTEL_CURRENCY)
			)
		);
	}
}
?>