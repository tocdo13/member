<?php
class DeleteSelectedBanquetReservationForm extends Form
{
	function DeleteSelectedBanquetReservationForm()
	{
		Form::Form("DeleteSelectedBanquetReservationForm");
		$this->add('confirm',new TextType(true,false,0, 20));
	}
	function on_submit()
	{
	   require_once 'delete.php';
		foreach(URL::get('selected_ids') as $id)
		{
            if($party_reservation=DB::select('party_reservation','id=\''.$id.'\'','status != \'CANCEL\' '))
			{
				
					$this->error('confirm','party_reservation_in_use');
			}
			else
			{
					DeleteBanquetReservationForm::delete($id);
			}
			
		}
		Url::redirect_current();
         exit();
	}
	function draw()
	{
		$languages = DB::select_all('language');
		DB::query('
			select 
				party_reservation.id
				,party_reservation.status 
				,party_reservation.num_table 
				,party_reservation.checkin_time
				,party_reservation.checkout_time
				,party_reservation.full_name
				,party_reservation.address 
				,party_reservation.time
				,party_reservation.home_phone 
				,party_reservation.note 
			 from 
			 	party_reservation
			where
				party_reservation.id in (\''.join(URL::get('selected_ids'),'\',\'').'\')
		');
		$items = DB::fetch_all();
		
		$this->parse_layout('delete_selected', array('items'=>$items) );
	}
}
?>