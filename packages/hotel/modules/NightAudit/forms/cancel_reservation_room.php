<?php
class CancelReservationRoomForm extends Form
{
	function CancelReservationRoomForm()
	{
		Form::Form('CancelReservationRoomForm');
		$this->add('id',new IDType(true,'object_not_exists','reservation'));
	}
	function on_submit()
	{
		if($this->check())
		{
		echo '</script><script src="packages/hotel/includes/js/ajax.js"></script>';
			require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
			$this->cancel(Url::iget('r_r_id'));
			if(!$this->is_error())
			{
				echo '<div id="progress">Updating room status to server...</div>
					<script>
						if(window.opener && (window.opener.year || window.opener.night_audit))
						{
							window.opener.history.go(0);
							window.close();
						}
						window.setTimeout("location=\''.Url::build('night_audit').'\'",1000);
					</script>';
				exit();
			}
		}
	}
	function draw()
	{
		DB::query('
			select
				reservation.id,reservation.note
				,customer.name as customer_name
				,tour.name as tour_id
			from
			 	reservation
				left outer join customer on customer.id=reservation.customer_id
				left outer join tour on tour.id=reservation.tour_id
			where
				reservation.id = '.URL::get('id'));
		$row = DB::fetch();
		$this->parse_layout('cancel_reservation_room',$row);
	}
	function cancel($id)
	{
		DB::update_id('reservation_room',array('status'=>'CANCEL','lastest_edited_user_id'=>Session::get('user_id')),$id);
	}
}
?>