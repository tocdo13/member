<?php
class DeleteEquipmentInvoiceForm extends Form
{
	function DeleteEquipmentInvoiceForm()
	{
		Form::Form("DeleteEquipmentInvoiceForm");
		$this->add('id',new IDType(true,'object_not_exists','housekeeping_invoice'));
	}
	function on_submit()
	{
		if($this->check() and Url::get('cmd')=='delete')
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
                                    housekeeping_invoice.id =\''.Url::get('id').'\' and mice_invoice_detail.type = \'EQUIPMENT\'
                                    
            '))
            {
                $this->error('','Hóa đơn đền bù #'.Url::get('id').' đã được tạo Bill không được xóa!');
                return false;                
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
                                    housekeeping_invoice.id =\''.Url::get('id').'\' and traveller_folio.type = \'EQUIPMENT\'
                                    
            '))
            {
                $this->error('','Hóa đơn đền bù #'.Url::get('id').' đã được tạo folio không được xóa!');
                return false;            
            }
			$this->delete($_REQUEST['id']);
			Url::redirect_current(array('reservation_room_id', 'room_id', 'employee_id', 'time_start','time_end', 'total_start','total_end'));
		}
	}
	function draw()
	{
		$sql = '
			select 
				housekeeping_invoice.id,
				DECODE(housekeeping_invoice.time,\'0\',\'\',to_char(FROM_UNIXTIME(housekeeping_invoice.time), \'DD/MM/YYYY\')) as time ,
				housekeeping_invoice.total ,
				housekeeping_invoice.tax_rate ,
				housekeeping_invoice.discount ,
				housekeeping_invoice.prepaid
				,concat(concat(traveller.first_name,\' \'),traveller.last_name) as reservation_room_id 
				,room.name as minibar_id 
				,party.name_'.Portal::language().' as employee_id 
			from 
			 	housekeeping_invoice
				inner  join room on room.id=housekeeping_invoice.minibar_id 
				inner  join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id and reservation_room.room_id=room.id
				left outer join traveller on reservation_room.traveller_id=traveller.id
				left outer join party on party.user_id=housekeeping_invoice.user_id 
			where
				housekeeping_invoice.id = '.URL::get('id');
		if($row = DB::fetch($sql))
		{
			$row['tax_rate'] = System::display_number($row['tax_rate']);
			$row['discount'] = System::display_number($row['discount']);
			$row['remain']=System::display_number($row['total']-$row['prepaid']); 
			$row['total']=System::display_number($row['total']); 
			$row['prepaid']=System::display_number($row['prepaid']); 
			$this->parse_layout('delete',$row);
		}
	}
	function permanent_delete($id)
	{
		$row = DB::select('housekeeping_invoice',$id);
		if($row)
		{
			$items = DB::select_all('housekeeping_invoice_detail','invoice_id='.$id);
			// Cap nhat lai so luong truoc khi ghi hoa don
			if($items){
				foreach($items as $item){
					$sql = '
						update 
							housekeeping_equipment
						set
							damaged_quantity = damaged_quantity - '.$item['quantity'].'
						where
							housekeeping_equipment.room_id = \''.$row['minibar_id'].'\'
							and product_id = \''.$item['product_id'].'\'
					';
					DB::query($sql);				
				}			
			}
			DB::delete('housekeeping_invoice_detail','invoice_id='.$id); 
			DB::delete('housekeeping_equipment_damaged','housekeeping_invoice_id='.$id);
			DB::delete_id('housekeeping_invoice',$id);
            $log_id = System::log('DELETE','Delete Equipment Invocie'.$id,'Delete Equipment Invocie'.$id,$id);
            $reservation = DB::fetch('select reservation_id from reservation_room where id='.$row['reservation_room_id'],'reservation_id');
            System::history_log('RECODE',$reservation,$log_id);
            System::history_log('EQUIPMENT',$id,$log_id);
		}
	}
	function delete($id)
	{
		$row = DB::select('housekeeping_invoice',$id);
		DeleteEquipmentInvoiceForm::permanent_delete($id);
	}
}
?>