<?php
class DeleteMinibarInvoiceForm extends Form
{
	function DeleteMinibarInvoiceForm()
	{
		Form::Form("DeleteMinibarInvoiceForm");
		$this->add('id',new IDType(true,'object_not_exists','housekeeping_invoice'));
	}
	function on_submit()
	{
		if($this->check() and Url::get('cmd')=='delete')
		{
			$this->delete($_REQUEST['id']);
			Url::redirect_current(array('reservation_room_id', 'room_id', 'employee_id', 'time_start','time_end', 'total_start','total_end',      
	));
		}
	}
	function draw()
	{
		$sql = '
			select 
				housekeeping_invoice.id,
				DECODE(housekeeping_invoice.time,\'0\',\'\',FROM_UNIXTIME(housekeeping_invoice.time)) as time ,
				housekeeping_invoice.total ,
				housekeeping_invoice.tax_rate ,
				housekeeping_invoice.discount ,
				housekeeping_invoice.prepaid
				,concat(concat(traveller.first_name,\' \'),traveller.last_name) as reservation_room_id 
				,minibar.name as minibar_id 
				,party.name_'.Portal::language().' as employee_id 
			from 
			 	housekeeping_invoice
				LEFT OUTER JOIN minibar on minibar.id=housekeeping_invoice.minibar_id 
				LEFT OUTER JOIN reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id and reservation_room.room_id=minibar.room_id
				LEFT OUTER JOIN traveller on reservation_room.traveller_id=traveller.id
				LEFT OUTER JOIN  party on party.user_id=housekeeping_invoice.user_id 
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
		if(!DB::exists('SELECT ID FROM RESERVATION_ROOM WHERE ID = '.$row['reservation_room_id'].' AND STATUS=\'CHECKOUT\'') or User::is_admin()){
			$items = DB::select_all('housekeeping_invoice_detail','invoice_id='.$id);
			foreach($items as $item)
			{
				if(DB::select('minibar_product','minibar_id=\''.$row['minibar_id'].'\' and product_id=\''.$item['product_id'].'\''))
				{
					DB::query('update minibar_product set quantity=quantity-('.$item['quantity'].') where minibar_id=\''.$row['minibar_id'].'\' and product_id=\''.$item['product_id'].'\'');
				}
				else
				{
					DB::insert('minibar_product',
						array(
							'minibar_id'=>$row['minibar_id'],
							'product_id'=>$item['product_id'],
							'quantity'=>-$item['quantity'],
							'price'=>$item['price'],
							'in_date'=>Date_Time::to_orc_date(date('d/m/Y'))
						)
					);
				}
			}
			DB::delete('housekeeping_invoice_detail', 'invoice_id='.$id); 
			DB::delete_id('housekeeping_invoice', $id);
		}else{
			echo  '
				<script>alert("'.Portal::language('you_have_no_right_to_delete').'");window.location="'.Url::build_current().'";</script>;
			';
			exit();
		}
	}
	function delete($id)
	{
		DeleteMinibarInvoiceForm::permanent_delete($id);
	}
}
?>