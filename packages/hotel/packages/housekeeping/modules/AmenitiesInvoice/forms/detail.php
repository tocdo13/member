<?php
class DetailAmenitiesInvoiceForm extends Form
{
	function DetailAmenitiesInvoiceForm()
	{
		Form::Form('DetailAmenitiesInvoiceForm');
	}
	function draw()
	{
		if(!URL::get('room_id'))
		{
			$minibar_id = DB::fetch('select id from room','id');
		}
		else
		{
			$minibar_id = URL::get('room_id');
		}
		$sql = '
			select
                housekeeping_invoice_detail.id,
				norm_quantity,
				housekeeping_invoice_detail.quantity as quantity,
				product.name_'.Portal::language().' as name,
				housekeeping_invoice_detail.price
			from
				housekeeping_invoice_detail
				inner join housekeeping_invoice on housekeeping_invoice.id = housekeeping_invoice_detail.invoice_id
				inner join room_amenities on housekeeping_invoice_detail.product_id = room_amenities.product_id
				inner join product on room_amenities.product_id = product.id				
                inner join product_price_list on product_price_list.product_id = product.id
			where				
				housekeeping_invoice.id = '.Url::get('id').' 
                and housekeeping_invoice_detail.quantity>0 
                and product_price_list.portal_id=\''.PORTAL_ID.'\'
                and product_price_list.department_code = \'HK\'
			order by 
                room_amenities.position';
		if(!($items = DB::fetch_all($sql))) $items = array();
		$i=1;
		foreach($items as $id=>$item)
		{
			if(isset($_REQUEST['items'][$id]))
			{
				$items[$id]['quantity'] = $_REQUEST['items'][$id]['quantity'];
			}
			$items[$id]['no'] = $i++;
		}
		if($row = DB::fetch('
			select
				id
				,code
				,note
				,minibar_id
				,time
				,tax_rate 
				,fee_rate,discount
			from
				housekeeping_invoice
			where 
				id=\''.URL::get('id').'\'')){
			$row['time'] = date('d/m/Y',$row['time']);
		}else{
			$row = array();
		}
        //System::debug($items);
		$this->parse_layout('detail',
			$row+
			array(
			'room_id'=>DB::fetch('select room.name from room where room.id=\''.$row['minibar_id'].'\'','name'),
			'num_product'=>$i-1,
			'items'=>$items
			)
		);
	}
}
?>