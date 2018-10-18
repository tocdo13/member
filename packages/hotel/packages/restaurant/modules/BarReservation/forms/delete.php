<?php
class DeleteBarReservationForm extends Form
{
	function DeleteBarReservationForm()
	{
		Form::Form("DeleteBarReservationForm");
		$this->add('id',new IDType(true,'object_not_exists','bar_reservation'));
	}
	function on_submit()
	{
		if($this->check() and URL::get('confirm') and $product = DB::select('bar_reservation','id=\''.$_REQUEST['id'].'\''))
		{
			//DB::update_id('bar_reservation',array('cancel_note'),Url::get('id'));
			$this->delete($_REQUEST['id']);
			Url::redirect_current(array( 'bar_reservation_bar_id', 'bar_reservation_receptionist_id'));
		}
	}
	function draw()
	{
		DB::query('
			select 
				bar_reservation.id
				,bar_reservation.code 
				,bar_reservation.status 
				,bar_reservation.num_table 
				,bar_reservation.arrival_time
				,bar_reservation.agent_name 
				,bar_reservation.agent_address 
				,bar_reservation.time
				,bar_reservation.agent_phone 
				,bar_reservation.departure_time
				,bar_reservation.note 
			 from 
			 	bar_reservation
				left outer join reservation_room on reservation_room.id=bar_reservation.reservation_room_id 
				left outer join traveller on traveller.id=reservation_room.traveller_id
			where
				bar_reservation.id = '.URL::get('id'));
		if($row = DB::fetch())
		{
			$this->parse_layout('delete',$row);
		}
	}
	function delete($id)
	{
		DB::delete_id('bar_reservation', $id);
		DB::delete('bar_reservation_table', 'bar_reservation_id='.$id);
		DB::delete('bar_reservation_product', 'bar_reservation_id='.$id);
		DB::delete('pay_by_currency', 'bill_id='.$id.' and type=\'BAR\'');
	}
}
?>