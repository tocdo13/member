<?php
class DeleteSelectedLaundryInvoiceForm extends Form
{
	function DeleteSelectedLaundryInvoiceForm()
	{
		Form::Form("DeleteSelectedLaundryInvoiceForm");
		$this->add('confirm1',new TextType(true,false,0, 20));
	}
	function on_submit()
	{
		if($this->check())
		{
			if(URL::get('confirm1'))
			{
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
                                    housekeeping_invoice.id =\''.$id.'\' and mice_invoice_detail.type = \'LAUNDRY\'
                                    
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
                                            housekeeping_invoice.id =\''.$id.'\' and traveller_folio.type = \'LAUNDRY\'
                                            
                    '))
                    {
                        $check = true;           
                    }
                }
                if($check == true)
                {
                    $_SESSION['errors'] = 'errors';
                    Url::redirect_current(array('laundry_invoice'));
                    return false;                    
                }
				foreach(URL::get('selected_ids') as $id)
				{
					$row = DB::select('housekeeping_invoice',$id);
					if(!DB::exists('SELECT ID FROM RESERVATION_ROOM WHERE ID = '.$row['reservation_room_id'].' AND STATUS=\'CHECKOUT\'')){
						DB::delete('housekeeping_invoice','id=\''.$id.'\'');
						DB::delete('housekeeping_invoice_detail','invoice_id=\''.$id.'\'');
                        
                        $log_id = System::log('DELETE','Delete Laundry Invocie LD_'.$row['position'],'Delete Laundry Invocie LD_'.$row['position'],$id);
                        $reservation = DB::fetch('select reservation_id from reservation_room where id='.$row['reservation_room_id'],'reservation_id');
                        System::history_log('RECODE',$reservation,$log_id);
                        System::history_log('LAUNDRY',$id,$log_id);
					}
				}
				Url::redirect_current(array('reservation_room_id'));
			}
		}
	}
	function draw()
	{
		$sql = '
			select 
				housekeeping_invoice.id
				,concat(CONCAT(traveller.first_name,\' \'),traveller.last_name) as name
				,room.name as room_name
				,housekeeping_invoice.total 
			from 
			 	housekeeping_invoice
				left outer join reservation_room on reservation_room.id = housekeeping_invoice.reservation_room_id
				left outer join room on room.id=housekeeping_invoice.minibar_id 
				left outer join traveller on reservation_room.traveller_id=traveller.id
			where housekeeping_invoice.id in ('.join(URL::get('selected_ids'),',').')
		';
		$items = DB::fetch_all($sql);
		foreach($items as $key=>$item)
		{
			 $items[$key]['total']=System::display_number($item['total']); 
		}
		$this->parse_layout('delete_selected',array('items'=>$items));
	}
}
?>