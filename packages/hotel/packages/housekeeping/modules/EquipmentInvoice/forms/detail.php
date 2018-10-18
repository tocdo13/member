<?php
class DetailEquipmentInvoiceForm extends Form
{
	function DetailEquipmentInvoiceForm()
	{
		Form::Form('DetailEquipmentInvoiceForm');
		//$this->link_js('packages/core/includes/js/calendar.js');
		//$this->link_js('packages/core/includes/js/multi_items.js');
		//$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		//$this->link_css(Portal::template('core').'/css/autocomplete.css');
                $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw()
	{
		$row = DB::fetch('
			select
				housekeeping_invoice.*,
				concat(concat(TRAVELLER.first_name,\' \'),TRAVELLER.last_name) as customer_name,
				room.name as room_name
			from
				housekeeping_invoice
				inner join room on room.id = housekeeping_invoice.minibar_id
				inner join reservation_room on reservation_room.id = housekeeping_invoice.reservation_room_id
				left outer join traveller on TRAVELLER.id = reservation_room.TRAVELLER_id
			where 
				housekeeping_invoice.id=\''.URL::get('id').'\''
		);
		
		$rooms = DB::fetch_all('
			select
				room.*,RESERVATION_ROOM.id as RESERVATION_ROOM_ID
			from
				room
				inner join reservation_room on reservation_room.room_id = room.id
				inner join room_status on room_status.reservation_room_id  =  RESERVATION_ROOM.id
			where
				reservation_room.status = \'CHECKIN\'
				and room_status.status = \'OCCUPIED\'
				and room_status.in_date = \''.Date_time::to_orc_date(date('d/m/Y')).'\'
			order by
				room.name
		');
		
		$this->map['date'] = date('d/m/Y',$row['time']);
		$invoice_id = URL::get('id');
		$room_id = DB::fetch('select minibar_id from housekeeping_invoice where housekeeping_invoice.id = '.$invoice_id,'minibar_id');
		$sql = '
			select 
				housekeeping_invoice_detail.id,
				housekeeping_invoice_detail.quantity,
				housekeeping_invoice_detail.price,
				housekeeping_invoice_detail.quantity*housekeeping_invoice_detail.price as amount,
				housekeeping_invoice_detail.product_id,
				product.name_'.Portal::language().' as name,
				product.id as code,
				housekeeping_invoice.id as housekeeping_invoice_id
			from
				housekeeping_invoice_detail
				inner join housekeeping_invoice on housekeeping_invoice.id = housekeeping_invoice_detail.invoice_id
				inner join product on product.id = housekeeping_invoice_detail.product_id
			where
				housekeeping_invoice.id = '.$invoice_id.'';
		$total_before_tax = 0;
		if($items = DB::fetch_all($sql)){
			foreach($items as $key=>$item){
				$items[$key]['price'] = System::display_number($item['price']);
				$items[$key]['amount'] = System::display_number($item['amount']);
				$total_before_tax += $item['amount'];
			}
			$this->map['items'] = $items;
			$this->map['tax_rate'] = $row['tax_rate'];
			
			$this->map['total_before_tax'] = System::display_number($total_before_tax);
			
			$this->map['tax'] = System::display_number($total_before_tax*$row['tax_rate']/100);
			
			$this->map['total'] = System::display_number($total_before_tax+$total_before_tax*$row['tax_rate']/100);
		}else{
			$this->map['items'] = array();
		}
        //System::debug($row);
		$this->parse_layout('detail',$this->map+$row);
	}
}
?>