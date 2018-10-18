<?php
class DeleteHousekeepingInvoiceForm extends Form
{
	function DeleteHousekeepingInvoiceForm()
	{
		Form::Form("DeleteHousekeepingInvoiceForm");
		$this->add('id',new IDType(true,'object_not_exists','housekeeping_invoice'));
	}
	function on_submit()
	{
		if($this->check())
		{
			$this->delete($_REQUEST['id']);
			Url::redirect_current(array('reservation_room_id', 'minibar_id', 'user_id', 
			'time_start','time_end', 'total_start','total_end', 
	));
		}
	}
	function draw()
	{
		DB::query('
			select 
				housekeeping_invoice.id
				,DECODE(abs(housekeeping_invoice),0,\'\',housekeeping_invoice,FROM_UNIXTIME(housekeeping_invoice.time),\'\') as time, 
				housekeeping_invoice.total ,
				housekeeping_invoice.prepaid, 
				housekeeping_invoice.tax_rate ,
				housekeeping_invoice.discount,
				CONCAT(CONCAT(traveller.first_name,\' \'),traveller.last_name) as customer_name
				,housekeeping_invoice.status
				,room.name as room_id 
				,user.user_name 
			from 
			 	housekeeping_invoice
				inner join minibar on minibar.id=housekeeping_invoice.minibar_id 
				inner join reservation_room on RESERVATION_ROOM.id=housekeeping_invoice.reservation_room_id  and reservation_room.room_id=minibar.room_id
				inner join room on room.id=minibar.room_id 
				left outer join traveller on traveller.id=reservation_room.traveller_id
				left outer join user on user.id=housekeeping_invoice.user_id 
			where
				housekeeping_invoice.id = '.URL::get('id'));
		if($row = DB::fetch())
		{
			if(!$row['exchange_rate'])
			{
				$row['exchange_rate']=1;
			}
			$row['tax_rate'] = System::display_number($row['tax_rate']);
			$row['discount'] = System::display_number($row['discount']);
			$row['remain']=System::display_number(($row['total']-$row['prepaid'])/$row['exchange_rate']).' '.$row['currency_id']; 
			$row['total']=System::display_number($row['total']/$row['exchange_rate']).' '.$row['currency_id']; 
			$row['prepaid']=System::display_number($row['prepaid']/$row['exchange_rate']).' '.$row['currency_id']; 
			$this->parse_layout('delete',$row);
		}
	}
	function permanent_delete($id)
	{
		$product_description = '';
		$items = DB::select_all('housekeeping_invoice_detail','invoice_id='.$id);
		$row = DB::select('housekeeping_invoice',$id);
		foreach($items as $item)
		{
			if($product = DB::select('hk_product',$item['product_id']))
			{
				$product_description .= $item['quantity'].' <a href="?page=product&id='.$product['id'].'">'.$product['name_'.Portal::language()].'</a><br>
';
				if(DB::select('minibar_product','minibar_id=\''.$row['minibar_id'].'\' and product_id=\''.$item['product_id'].'\''))
				{
					DB::query('update minibar_product set quantity=quantity-('.$item['quantity'].') where minibar_id=\''.$row['minibar_id'].'\' and product_id=\''.$item['product_id'].'\'');
				}
				else
				{
					DB::insert('minibar_product',
						array(
							'minibar_id'=>$row['minibar_id'],
							'product_id'=>$item['product_id'],
							'quantity'=>-$item['quantity'],
							'price'=>$item['price'],
							'currency_id'=>$row['currency_id'],
							'exchange_rate'=>$row['exchange_rate']
						)
					);
				}
			}
		}
		$minibar = DB::select('minibar',$row['minibar_id']);
		System::log('delete','Edit housekeeping invoice at minibar '.$minibar['name'],
'Code:<a href="?page=housekeeping_invoice&id='.URL::get('id').'">'.URL::get('id').'</a><br>
Total money:'.$row['total'].$row['currency_id'].'<br>
Reservation code: <a href="?page=reservation&id='.$row['reservation_room_id'].'>'.$row['reservation_room_id'].'</a><br>
<b>Services:</b><br>
'.$product_description);
		DB::delete('housekeeping_invoice_detail', 'invoice_id='.$id); 
		DB::delete_id('housekeeping_invoice', $id);
	}
	function delete($id)
	{
		DeleteHousekeepingInvoiceForm::permanent_delete($id);
	}
}
?>