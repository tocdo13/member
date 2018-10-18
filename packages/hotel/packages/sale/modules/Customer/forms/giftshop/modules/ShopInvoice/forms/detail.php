<?php
class ShopInvoiceForm extends Form
{
	function ShopInvoiceForm()
	{
		Form::Form("ShopInvoiceForm");
		$this->add('id',new IDType(true,'object_not_exists','bar_reservation'));
	}
	function draw()
	{
		$row = DB::select('shop_invoice',Url::get('id'));
		$order_id = '';
		for($i=0;$i<6-strlen($row['id']);$i++)
		{
			$order_id .= '0';
		}
		$order_id .= $row['id'];
		
		//============================== product ================================
		
		DB::query('
			select 
				shop_product.name_'.Portal::language().' as name, 
				shop_invoice_detail.product_id as id,
				shop_invoice_detail.quantity as quantity, 
				shop_invoice_detail.quantity_discount, 
				shop_invoice_detail.discount_rate,
				shop_invoice_detail.price, 
				unit.name_'.Portal::language().' as unit_name 
			from 
				shop_invoice_detail
				inner join shop_product on shop_product.id = shop_invoice_detail.product_id 
				inner join unit on unit.id = shop_product.unit_id 
			where 
				shop_invoice_detail.shop_invoice_id=\''.Url::get('id').'\''
		);
		$product_items = DB::fetch_all();
		$total = 0;
		$discount = 0;
		foreach($product_items as $key=>$value)
		{
			$product_items[$key]['product__id'] = $value['id'];
			$product_items[$key]['product__name'] = $value['name'];
			$product_items[$key]['product__quantity_remain'] = $value['quantity'] - $value['quantity_discount'];
			$product_items[$key]['product__unit'] = $value['unit_name'];
			$product_items[$key]['product__quantity_discount'] = $value['quantity_discount'];
			$total += $value['price']*$product_items[$key]['product__quantity_remain'];
			$discount+= $value['price']*$product_items[$key]['product__quantity_remain']*$value['discount_rate']/100;
			$product_items[$key]['product__total'] = $value['price']*$product_items[$key]['product__quantity_remain'];
		}
		$row['sumary'] = System::display_number_report($total);
		$row['bar_fee'] = System::display_number_report($total*5/100);
		$row['sum_total'] = System::display_number_report($row['total']);
		$row['date'] = date('H:i d/m/Y',$row['time']);
		$row['tax'] = System::display_number_report($row['tax']);
		$row['total_discount'] = System::display_number_report($discount);
		$bar = DB::select('shop',$row['shop_id']);
		//System::debug($product_items);
		$this->parse_layout('print',$row+array(
			'order_id'=>$order_id,
			'bar_name'=>$bar['name'],
			'product_items'=>$product_items,
		));
	}
}
?>