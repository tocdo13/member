<?php
class DeleteSelectedMinibarInvoiceForm extends Form
{
	function DeleteSelectedMinibarInvoiceForm()
	{
		Form::Form("DeleteSelectedMinibarInvoiceForm");
		$this->add('confirm',new TextType(true,false,0, 20));
        require_once 'packages/hotel/includes/php/product.php';
	}
	function on_submit()
	{
		if(Url::get('confirm'))
		{
			require_once 'delete.php';
			foreach(URL::get('selected_ids') as $id)
			{
				DeleteMinibarInvoiceForm::delete($id);
			}
			Url::redirect_current(array('housekeeping_invoice_reservation_id', 'housekeeping_invoice_minibar_id', 'housekeeping_invoice_employee_id', 'housekeeping_invoice_time_start','housekeeping_invoice_time_end', 'housekeeping_invoice_total_start','housekeeping_invoice_total_end',));
		}
	}
	function draw()
	{
		$sql = '
			select 
				housekeeping_invoice.id,
                FROM_UNIXTIME(housekeeping_invoice.time) as time,
				housekeeping_invoice.total,
				housekeeping_invoice.prepaid,
				housekeeping_invoice.tax_rate,
				housekeeping_invoice.discount,
                concat(concat(traveller.first_name,\' \'),traveller.last_name) as reservation_room_id ,
                minibar.name as minibar_id
			from 
			 	housekeeping_invoice
				left outer join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id 
				left outer join traveller on traveller.id=reservation_room.traveller_id 
				left outer join minibar on minibar.id=housekeeping_invoice.minibar_id
			where housekeeping_invoice.id in ('.join(URL::get('selected_ids'),',').')
		';
		$items = DB::fetch_all($sql);
		foreach($items as $key=>$item)
		{
			$items[$key]['remain']=System::display_number($item['total']-$item['prepaid']);
			$items[$key]['total']=System::display_number($item['total']);
			$items[$key]['prepaid']=System::display_number($item['prepaid']); 
		}
        //System::debug($items);
		$this->parse_layout('delete_selected',
			array(
				'items'=>$items
			)
		);
	}
}
?>