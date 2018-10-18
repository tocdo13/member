<?php
class DetailMinibarInvoiceForm extends Form
{
	function DetailMinibarInvoiceForm()
	{
		Form::Form('DetailMinibarInvoiceForm');
		$this->add('total',new FloatType(true,'invalid_total','0','100000000000'));
		$this->add('minibar_id',new IDType(false,'invalid_minibar_id','minibar'));
		$this->link_js('packages/core/includes/js/calendar.js');
	}
	function on_submit()
	{
		if($this->check())
		{
			$id = DB::update('housekeeping_invoice', 
				array(
					'minibar_id', 
					'total',
					'time'=>time()
				),'id=\''.URL::get('id').'\''
			);
			if(isset($_REQUEST['items']))
			{
				foreach($_REQUEST['items'] as $key=>$record)
				{
					if($record['quantity']>0)
					{
						$record['price']*=$rate;
						$record['invoice_id'] = $id;
						$record['product_id'] = $key;
						if(DB::select('housekeeping_invoice_detail','invoice_id=\''.URL::get('id').'\' and product_id=\''.$key.'\''))
						{
							DB::update('housekeeping_invoice_detail',$record,'invoice_id=\''.URL::get('id').'\' and product_id=\''.$key.'\'');
						}
						else
						{
							DB::insert('housekeeping_invoice_detail',$record);
						}
					}
				}
			} 
			URL::redirect_current();
		}
	}
	function draw()
	{
		if(!URL::get('minibar_id'))
		{
			$minibar_id = DB::fetch('select id from minibar','id');
		}
		else
		{
			$minibar_id = URL::get('minibar_id');
		}
		/*$sql = '
			select
				hk_product.code,
				norm_quantity,
				housekeeping_invoice_detail.quantity as quantity,
				hk_product.name_'.Portal::language().' as name,
				housekeeping_invoice_detail.price
			from
				minibar_product
				inner join hk_product on minibar_product.product_id=hk_product.code
				left outer join housekeeping_invoice_detail on invoice_id=\''.URL::get('id').'\'
				and housekeeping_invoice_detail.product_id=minibar_product.product_id
				left outer join housekeeping_invoice on invoice_id=housekeeping_invoice.id
			where
				minibar_product.minibar_id=\''.$minibar_id.'\'
			order by name';*/
		$sql = '
			select
				hk_product.id,
				norm_quantity,
				housekeeping_invoice_detail.quantity as quantity,
				hk_product.name_'.Portal::language().' as name,
				housekeeping_invoice_detail.price
			from
				housekeeping_invoice_detail
				inner join housekeeping_invoice on housekeeping_invoice.id = housekeeping_invoice_detail.invoice_id
				inner join minibar_product on  housekeeping_invoice_detail.product_id=minibar_product.product_id
				inner join hk_product on minibar_product.product_id=hk_product.code
			where				
				housekeeping_invoice.id = '.URL::get('id').' and housekeeping_invoice_detail.quantity>0
			order by minibar_product.position';
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
				id,
				minibar_id,
				time,
				tax_rate, fee_rate,discount
			from
				housekeeping_invoice
			where 
				id=\''.URL::get('id').'\'')){
			$row['time'] = date('d/m/Y',$row['time']);
		}else{
			$row = array();
		}
		$this->parse_layout('detail',
			$row+
			array(
			'room_id'=>DB::fetch('select room.name from minibar inner join room on room.id=room_id where minibar.id=\''.$row['minibar_id'].'\'','name'),
			'num_product'=>$i-1,
			'items'=>$items
			)
		);
	}
}
?>