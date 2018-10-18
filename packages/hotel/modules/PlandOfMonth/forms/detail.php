<?php
class DetailMinibarInvoiceForm extends Form
{
	function DetailMinibarInvoiceForm()
	{
		Form::Form('DetailMinibarInvoiceForm');
		$this->add('total',new FloatType(true,'invalid_total','0','100000000000'));
		$this->add('minibar_id',new IDType(false,'invalid_minibar_id','minibar'));
		$this->link_js('packages/core/includes/js/calendar.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
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
		$sql = '
			select
                housekeeping_invoice_detail.id,
				norm_quantity,
				housekeeping_invoice_detail.quantity as quantity,
				product.name_'.Portal::language().' as name,
				housekeeping_invoice_detail.price,
                housekeeping_invoice.net_price
			from
				housekeeping_invoice_detail
				inner join housekeeping_invoice on housekeeping_invoice.id = housekeeping_invoice_detail.invoice_id
				inner join minibar_product on housekeeping_invoice_detail.product_id = minibar_product.product_id
				inner join product on minibar_product.product_id = product.id				
                inner join product_price_list on product_price_list.product_id = product.id
			where				
				housekeeping_invoice_detail.invoice_id = '.Url::get('id').' and housekeeping_invoice_detail.quantity>0 
                --and product_price_list.portal_id=\''.PORTAL_ID.'\'
                and product_price_list.department_code = \'MINIBAR\'
			order by minibar_product.position';
		if(!($items = DB::fetch_all($sql))) $items = array();
		$i=1;
        //System::debug($items);
		$this->map['net_price_minibar'] = 0;
        foreach($items as $id=>$item)
		{
			if(isset($_REQUEST['items'][$id]))
			{
				$items[$id]['quantity'] = $_REQUEST['items'][$id]['quantity'];
			}
			$items[$id]['no'] = $i++;
            $this->map['net_price_minibar'] = $item['net_price'];
		}
		if($row = DB::fetch('
			select
				id
				,code
				,note
				,minibar_id
				,time
				,tax_rate 
				,fee_rate,discount,position
			from
				housekeeping_invoice
			where 
				id=\''.URL::get('id').'\'')){
			$row['time'] = date('d/m/Y',$row['time']);
		}else{
			$row = array();
		}
        $row['total_discount'] = 0;
		$row['total_discount'] = System::display_number($row['total_discount']);
        //System::debug($row);
        //System::debug($items);
		$this->parse_layout('detail',
            $this->map+
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
