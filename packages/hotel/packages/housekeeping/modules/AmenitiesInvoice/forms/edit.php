<?php
class EditAmenitiesInvoiceForm extends Form
{
	function EditAmenitiesInvoiceForm()
	{
		Form::Form('EditAmenitiesInvoiceForm');
		$this->add('total',new FloatType(true,'invalid_total','0','100000000000')); 
		$this->add('room_id',new IDType(false,'invalid_room_id','room'));
		$this->link_js('packages/core/includes/js/calendar.js');
	}
	function on_submit()
	{
        require_once 'packages/hotel/includes/php/product.php';
		if($this->check())
		{
			DB::update('housekeeping_invoice', 
				array(
					'code'=>Url::get('code')?Url::get('code'):'',
					'note'=>Url::get('note')?Url::get('note'):'',   
					'minibar_id'=>Url::get('room_id'),
					'last_modifier_id'=>Session::get('user_id'),
					'lastest_edited_time'=>time(),
					'group_payment'=>Url::check('group_payment')?1:0,
					'total'=>System::calculate_number(URL::get('total')),
					'total_before_tax'=>(System::calculate_number(URL::get('total_before_tax')) - System::calculate_number(URL::get('total_discount'))),
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
						//Cập nhật lại quantity trong minibar product
                        if(DB::select('room_amenities','room_id='.URL::get('room_id').' and product_id=\''.$key.'\''))
						{
							DB::query('update room_amenities set quantity=quantity+('.($old['quantity']-$record['quantity']).') where room_id='.URL::get('room_id').' and product_id=\''.$key.'\'');
						}
						else
						{
							DB::insert('room_amenities',
								array(
									'room_id',
									'product_id'=>$key,
									'quantity'=>-$record['quantity'],
									'price'=>System::calculate_number($record['price']),
                                    'in_date'=>Date_Time::to_orc_date(date('d/m/Y')),
                                    'portal_id'=>PORTAL_ID
								)
							);
						}
					}
					else
					{
						DB::insert('housekeeping_invoice_detail',$record);
						if(DB::select('room_amenities','product_id=\''.$key.'\''))
						{
							DB::query('update room_amenities set quantity=quantity-'.$record['quantity'].' where room_id= '.URL::get('room_id').' and product_id=\''.$key.'\'');
						}
						else
						{
							DB::insert('room_amenities',
								array(
									'room_id',
									'product_id'=>$key,
									'quantity'=>-$record['quantity'],
									'price'=>System::calculate_number($record['price']),
                                    'in_date'=>Date_Time::to_orc_date(date('d/m/Y')),
                                    'portal_id'=>PORTAL_ID
								)
							);
						}
					}
				}
			}
/**
  *             $warehouse_id = DB::fetch('Select * from portal_department where department_code = \'MINIBAR\' and portal_id = \''.PORTAL_ID.'\' ','warehouse_id');
  *             DeliveryOrders::get_delivery_orders(Url::iget('id'),'MINIBAR',$warehouse_id); 
  */ 
			URL::redirect_current();
		}
	}
	function draw()
	{
		$this->map['unlimited'] = MINIBAR_IMPORT_UNLIMIT;
		if(!URL::get('room_id'))
		{
			$room_id = DB::fetch('select id from room ','id');
		}
		else
		{
			$room_id = URL::get('room_id');
		}
		$row = DB::fetch('
			select
				id,code,note,
				minibar_id as room_id,
				time,lastest_edited_time,
				tax_rate, fee_rate,reservation_room_id as reservation_room_id,discount
			from
				housekeeping_invoice
			where 
				id=\''.URL::get('id').'\''
		);
		$row['time'] = date('H:i\' d/m/Y',$row['time']);
		if($row['lastest_edited_time']){
			$row['lastest_edited_time'] = date('H:i\' d/m/Y',$row['lastest_edited_time']);
		}
		$_REQUEST['code'] = $row['code']?$row['code']:'';
		$_REQUEST['note'] = $row['note']?$row['note']:'';
		$row['total_discount'] = 0;
		$row['total_discount'] = System::display_number($row['total_discount']);
		$row['room_name'] = DB::fetch('select room.name from room inner join reservation_room on reservation_room.room_id = room.id where reservation_room.id = '.$row['reservation_room_id'].'','name');
		$sql = '
			select
				room_amenities.product_id as id,
				norm_quantity,
				housekeeping_invoice_detail.quantity as quantity,
				product.name_'.Portal::language().' as name,
				CASE 
                    WHEN housekeeping_invoice_detail.price >0 
                    THEN housekeeping_invoice_detail.price 
                    ELSE room_amenities.price 
                END AS price 
			from
				room_amenities
				INNER JOIN product on room_amenities.product_id = product.id
				LEFT OUTER JOIN housekeeping_invoice_detail on housekeeping_invoice_detail.product_id = room_amenities.product_id  and housekeeping_invoice_detail.invoice_id = '.URL::get('id').'
				LEFT OUTER JOIN housekeeping_invoice on housekeeping_invoice.id = housekeeping_invoice_detail.invoice_id
			where
				room_amenities.room_id = '.$row['room_id'].'
			order by room_amenities.position';
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
        //System::debug($this->map);
		$this->parse_layout('edit',$this->map+$row);
	}
}
?>