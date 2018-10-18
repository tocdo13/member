<?php
class DeleteSelectedHousekeepingInvoiceForm extends Form
{
	function DeleteSelectedHousekeepingInvoiceForm()
	{
		Form::Form("DeleteSelectedHousekeepingInvoiceForm");
		$this->add('confirm',new TextType(true,false,0, 100));
	}
	function on_submit()
	{
		if(URL::get('confirm'))
		{
			require_once 'delete.php';
			foreach(URL::get('selected_ids') as $id)
			{
				DeleteHousekeepingInvoiceForm::delete($id);
			}
			Url::redirect_current(array('reservation_room_id', 'minibar_id', 'employee_id', 
				'time_start','time_end', 'total_start','total_end',      
		}
	));
	}
	function draw()
	{
		DB::query('
			select 
				housekeeping_invoice.id
				,FROM_UNIXTIME(housekeeping_invoice.time) as time ,
				housekeeping_invoice.total ,
				housekeeping_invoice.prepaid ,
				housekeeping_invoice.tax_rate ,
				housekeeping_invoice.discount,
				housekeeping_invoice.currency_id
				,concat(concat(traveller.first_name,\' \'),traveller.last_name) as reservation_room_id 
				,minibar.name as minibar_id  
				,currency.name as currency
				,housekeeping_invoice.exchange_rate
			from 
			 	housekeeping_invoice
				left outer join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id 
				left outer join traveller on traveller.id=reservation_room.traveller_id 
				left outer join minibar on minibar.id=housekeeping_invoice.minibar_id 
				left outer join employee_profile on employee_profile.id=housekeeping_invoice.employee_id 
				left outer join currency on currency_id=currency.id
			where housekeeping_invoice.id in ('.join(URL::get('selected_ids'),',').')
		');
		$items = DB::fetch_all();
		foreach($items as $key=>$item)
		{
			$items[$key]['remain']=System::display_number(($item['total']-$item['prepaid'])/$item['exchange_rate']).' '.$item['currency']; 
			$items[$key]['total']=System::display_number($item['total']/$item['exchange_rate']).' '.$item['currency']; 
			$items[$key]['prepaid']=System::display_number($item['prepaid']/$item['exchange_rate']).' '.$item['currency']; 
		}
		$this->parse_layout('delete_selected',
			array(
				'items'=>$items
			)
		);
	}
}
?>