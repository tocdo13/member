<?php
class HousekeepingInvoiceForm extends Form
{
	function HousekeepingInvoiceForm()
	{
		Form::Form("HousekeepingInvoiceForm");
		$this->add('id',new IDType(true,'object_not_exists','housekeeping_invoice'));
	}
	function draw()
	{
		DB::query('
			select 
				housekeeping_invoice.id
				,DECODE(housekeeping_invoice.time,0,FROM_UNIXTIME(housekeeping_invoice.time),\'\') as time, 
				housekeeping_invoice.total ,
				housekeeping_invoice.prepaid, 
				housekeeping_invoice.tax_rate ,
				housekeeping_invoice.exchange_rate ,
				housekeeping_invoice.currency_id ,
				housekeeping_invoice.currency_id as currency,
				housekeeping_invoice.discount,
				concat(CONCAT(traveller.first_name,\' \'),traveller.last_name) as customer_name
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
			$currency = $row['currency'];
			if(!$row['exchange_rate'])
			{
				$row['exchange_rate']=1;
			}
			$row['remain']=System::display_number(($row['total']-$row['prepaid'])/$row['exchange_rate']).' '.$currency; 
			$row['tax']=System::display_number(((($row['total']-$row['discount'])*100/(100-$row['tax_rate']))*$row['tax_rate']/100)/$row['exchange_rate']).' '.$currency; 
			$row['total_before_tax']=System::display_number((($row['total']-$row['discount'])*100/(100+$row['tax_rate']))/$row['exchange_rate']).' '.$currency; 
			$row['total']=System::display_number($row['total']/$row['exchange_rate']).' '.$currency; 
			$row['prepaid']=System::display_number($row['prepaid']/$row['exchange_rate']).' '.$currency; 
			DB::query('
				select
					housekeeping_invoice_detail.id
					,housekeeping_invoice_detail.price/IF(housekeeping_invoice.exchange_rate<>0,housekeeping_invoice.exchange_rate,1) as price ,
					housekeeping_invoice.currency_id,
					housekeeping_invoice.exchange_rate,
					housekeeping_invoice_detail.quantity,  
					housekeeping_invoice_detail.product_id, 
					housekeeping_invoice_detail.detail  
					,hk_product.name_'.Portal::language().' as service_name 
				from
					housekeeping_invoice_detail
					left outer join housekeeping_invoice on invoice_id=housekeeping_invoice.id
					left outer join hk_product on housekeeping_invoice_detail.product_id = hk_product.code 
				where
					housekeeping_invoice_detail.invoice_id='.$_REQUEST['id']
			);
			$row['housekeeping_invoice_detail_items'] = DB::fetch_all();
			foreach($row['housekeeping_invoice_detail_items'] as $key=>$value)
			{
				//$row['housekeeping_invoice_detail_items'][$key]['price'] = System::display_number($value['price']).' '.$value['currency_id']; 
				//$row['housekeeping_invoice_detail_items'][$key]['quantity'] = System::display_number($value['quantity']);  
			} 
			$row['date'] = date('d/m/Y',$row['time']);
			$this->parse_layout('detail',$row);
		}
	}
}
?>