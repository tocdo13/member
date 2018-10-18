<?php
class  DeleteBanquetReservationForm extends Form
{
	function DeleteBanquetReservationForm()
	{
		Form::Form("DeleteBanquetReservationForm");
		$this->add('id',new IDType(true,'object_not_exists','party_reservation'));
	}
	function on_submit()
	{
		if($this->check() and URL::get('confirm') and $product = DB::select('party_reservation','id=\''.Url::iget('id').'\''))
		{
			//DB::update_id('party_reservation',array('cancel_note'),Url::get('id'));
			$this->delete(Url::iget('id'));
			Url::redirect_current(array('party_reservation_receptionist_id'));
		}
	}
	function draw()
	{
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
				party_reservation.id = '.URL::get('id'));
		if($row = DB::fetch())
		{
			$this->parse_layout('delete',$row);
		}
	}
	function delete($id)
	{
		if(User::can_delete(false,ANY_CATEGORY)){
			$can_delete = false;
			if($row = DB::fetch('select * from party_reservation where id = '.$id.'')){
				if($row['status']=='CHECKOUT'){
					$can_delete = false;
				}else{
					$can_delete = true;
				}
				if(User::can_admin(false,ANY_CATEGORY)){
					$can_delete = true;
				}
				if($can_delete){
					$log_description = '
						Agent name: '.$row['full_name'].'<br>
						Agent address: '.$row['address'].'<br>
						Agent phone: '.$row['home_phone'].'<br>
						Arrival time: '.date('H:i d/m/Y',$row['checkin_time']).'<br>
						Departure time:  '.date('H:i d/m/Y',$row['checkout_time']).'<br>
						Note: '.$row['note'].'
					';
					DB::delete_id('party_reservation', $id);
					DB::delete('party_reservation_detail', 'party_reservation_id = '.$id);
					DB::delete('party_reservation_room', 'party_reservation_id = '.$id);
                    DB::delete('payment','type=\'BANQUET\' and bill_id='.$id.'');					
					System::log('delete','Delete from party reservation with id: \''.$id.'\'',$log_description,$id);
				}
			}
		}else{
			echo  '
				<script>alert("'.Portal::language('you_have_no_right_to_delete').'");window.location="'.Url::build_current(array('status')).'";</script>;
			';
			exit();
		}
	}
}
?>