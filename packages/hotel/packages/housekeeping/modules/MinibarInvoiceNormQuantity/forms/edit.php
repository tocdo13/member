<?php
class EditMinibarInvoiceForm extends Form
{
	function EditMinibarInvoiceForm()
	{
		Form::Form('EditMinibarInvoiceForm');
		$this->add('total',new FloatType(true,'invalid_total','0','100000000000')); 
		$this->add('minibar_id',new IDType(false,'invalid_minibar_id','minibar'));
		$this->link_js('packages/core/includes/js/calendar.js');
	}
	function on_submit()
	{
		if($this->check())
		{
			DB::update('housekeeping_invoice', 
				array(
					'minibar_id', 
					'last_modifier_id'=>Session::get('user_id'),
					'total'=>System::calculate_number(URL::get('total')),
					'total_before_tax'=>System::calculate_number(URL::get('total_before_tax')),
					'tax_rate'=>System::calculate_number(URL::get('tax_rate')),
					'fee_rate'=>System::calculate_number(URL::get('fee_rate')),
					'discount'=>System::calculate_number(URL::get('discount'))
				),'id=\''.URL::get('id').'\''
			);
			if(isset($_REQUEST['items']))
			{
				foreach($_REQUEST['items'] as $key=>$record)
				{
					$record['quantity'] = $record['quantity']?$record['quantity']:0;
					$record['price'] = str_replace(',','',($record['price']));
					$record['invoice_id'] = URL::get('id');
					$record['product_id'] = $key;
					if($old = DB::select('housekeeping_invoice_detail','invoice_id=\''.URL::get('id').'\' and product_id=\''.$key.'\''))
					{
						DB::update('housekeeping_invoice_detail',$record,'invoice_id=\''.URL::get('id').'\' and product_id=\''.$key.'\'');
						if(DB::select('minibar_product','minibar_id=\''.URL::get('minibar_id').'\' and product_id=\''.$key.'\''))
						{
							DB::query('update minibar_product set quantity=quantity+('.($old['quantity']-$record['quantity']).') where minibar_id=\''.URL::get('minibar_id').'\' and product_id=\''.$key.'\'');
						}
						else
						{
							DB::insert('minibar_product',
								array(
									'minibar_id',
									'product_id'=>$key,
									'quantity'=>-$record['quantity'],
									'price'=>$record['price']
								)
							);
						}
					}
					else
					{
						DB::insert('housekeeping_invoice_detail',$record);
						if(DB::select('minibar_product','product_id=\''.$key.'\''))
						{
							DB::query('update minibar_product set quantity=quantity-'.$record['quantity'].' where minibar_id=\''.URL::get('minibar_id').'\' and product_id=\''.$key.'\'');
						}
						else
						{
							DB::insert('minibar_product',
								array(
									'minibar_id',
									'product_id'=>$key,
									'quantity'=>-$record['quantity'],
									'price'=>$record['price']
								)
							);
						}
					}
				}
			} 
			URL::redirect_current();
		}
	}
	function draw()
	{
		$this->map['unlimited'] = MINIBAR_IMPORT_UNLIMIT;
		if(!URL::get('minibar_id'))
		{
			$minibar_id = DB::fetch('select id from minibar ','id');
		}
		else
		{
			$minibar_id = URL::get('minibar_id');
		}
		$row = DB::fetch('
			select
				id,
				minibar_id,
				time,
				tax_rate, fee_rate,reservation_room_id as reservation_room_id,discount
			from
				housekeeping_invoice
			where 
				id=\''.URL::get('id').'\''
		);
		$row['time'] = date('d/m/Y',$row['time']);
		$row['total_discount'] = 0;
		$row['total_discount'] = System::display_number($row['total_discount']);
		$row['room_name'] = DB::fetch('select room.name from room inner join reservation_room on reservation_room.room_id = room.id where reservation_room.id = '.$row['reservation_room_id'].'','name');
		$sql = '
			select
				minibar_product.product_id as id,
				norm_quantity,
				housekeeping_invoice_detail.quantity as quantity,
				product.name_'.Portal::language().' as name,
				CASE WHEN housekeeping_invoice_detail.price >0 THEN housekeeping_invoice_detail.price ELSE product_price_list.price END AS price 
			from
				minibar_product
				INNER JOIN product on minibar_product.product_id=product.id
                INNER JOIn product_price_list on product_price_list.product_id = product.id 
				LEFT OUTER JOIN housekeeping_invoice_detail on housekeeping_invoice_detail.product_id = minibar_product.product_id  and housekeeping_invoice_detail.invoice_id = '.URL::get('id').'
				LEFT OUTER JOIN housekeeping_invoice on housekeeping_invoice.id = housekeeping_invoice_detail.invoice_id
			where
				minibar_product.minibar_id = \''.$row['minibar_id'].'\'	
                and product_price_list.department_code = \'MINIBAR\'
			order by minibar_product.position';
		$i=1;
		if(!($items = DB::fetch_all($sql))) $items = array();
		foreach($items as $id=>$item)
		{
			if(isset($_REQUEST['items'][$id]))
			{
				$items[$id]['quantity'] = $_REQUEST['items'][$id]['quantity'];
			}
			$items[$id]['no'] = $i++;
			$items[$id]['price'] = ($items[$id]['price']);
		}
		$this->map['items'] = $items;
		$this->map['num_product'] = $i-1;
		$this->parse_layout('edit',$this->map+$row);
	}
}
?>