<?php
class DeleteSelectedEquipmentInvoiceForm extends Form
{
	function DeleteSelectedEquipmentInvoiceForm()
	{
		Form::Form("DeleteSelectedEquipmentInvoiceForm");
		$this->add('confirm',new TextType(true,false,0, 20));
	}
	function on_submit()
	{
		if(Url::get('confirm'))
		{
			require_once 'delete.php';
            $check = false;
            foreach(URL::get('selected_ids') as $id)
			{
			    if(DB::exists('
                            SELECT
                                mice_invoice_detail.invoice_id as id,
                                housekeeping_invoice.position as hk_id,
                                mice_invoice_detail.mice_invoice_id
                            FROM
                                mice_invoice_detail
                                INNER JOIN housekeeping_invoice on housekeeping_invoice.id = mice_invoice_detail.invoice_id
                            WHERE
                                housekeeping_invoice.id =\''.$id.'\' and mice_invoice_detail.type = \'EQUIPMENT\'
                                
                '))
                {
                    $check = true;              
                }
                if(DB::exists('
                                    SELECT
                                        traveller_folio.invoice_id as id,
                                        housekeeping_invoice.position as hk_id,
                                        traveller_folio.folio_id
                                    FROM
                                        traveller_folio
                                        INNER JOIN housekeeping_invoice on housekeeping_invoice.id = traveller_folio.invoice_id
                                    WHERE
                                        housekeeping_invoice.id =\''.$id.'\' and traveller_folio.type = \'EQUIPMENT\'
                                        
                '))
                {
                    $check = true;           
                }
            }
            if($check == true)
            {
                $_SESSION['errors'] = 'errors';
                Url::redirect_current(array('equipment_invoice'));
                return false;                    
            }
			foreach(URL::get('selected_ids') as $id)
			{
				DeleteEquipmentInvoiceForm::delete($id);
			}
			Url::redirect_current(array('housekeeping_invoice_reservation_id', 'housekeeping_invoice_minibar_id', 'housekeeping_invoice_employee_id', 
			'housekeeping_invoice_time_start','housekeeping_invoice_time_end', 'housekeeping_invoice_total_start','housekeeping_invoice_total_end',
			));
		}
	}
	function draw()
	{
		$sql = '
			select 
				housekeeping_invoice.id
				,to_char(FROM_UNIXTIME(housekeeping_invoice.time),\'DD/MM/YYYY\') as time ,
				housekeeping_invoice.total ,
				housekeeping_invoice.prepaid ,
				housekeeping_invoice.tax_rate ,
				housekeeping_invoice.discount
				,concat(concat(traveller.first_name,\' \'),traveller.last_name) as reservation_room_id 
				,room.name as minibar_id
			from 
			 	housekeeping_invoice
				left outer join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id 
				left outer join traveller on traveller.id=reservation_room.traveller_id 
				left outer join minibar on minibar.id=housekeeping_invoice.minibar_id
                inner  join room on room.id=housekeeping_invoice.minibar_id 
			where housekeeping_invoice.id in ('.join(URL::get('selected_ids'),',').')
		';
		$items = DB::fetch_all($sql);
		foreach($items as $key=>$item)
		{
			$items[$key]['remain']=System::display_number($item['total']-$item['prepaid']);
			$items[$key]['total']=System::display_number($item['total']);
			$items[$key]['prepaid']=System::display_number($item['prepaid']); 
		}
		$this->parse_layout('delete_selected',
			array(
				'items'=>$items
			)
		);
	}
}
?>