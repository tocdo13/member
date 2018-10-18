<?php
class DeleteKaraokeReservationNewForm extends Form
{
	function DeleteKaraokeReservationNewForm()
	{
		Form::Form("DeleteKaraokeReservationNewForm");
		$this->add('id',new IDType(true,'object_not_exists','karaoke_reservation'));
	}
	function on_submit()
	{
		if($this->check() and URL::get('confirm') and $product = DB::select('karaoke_reservation','id=\''.Url::iget('id').'\''))
		{
			$this->delete(Url::iget('id'));
			Url::redirect_current(array('karaoke_reservation_receptionist_id'));
		}
	}
	function draw()
	{
		DB::query('
			select 
				karaoke_reservation.id
				,karaoke_reservation.code 
				,karaoke_reservation.status 
				,karaoke_reservation.num_table 
				,karaoke_reservation.arrival_time
				,karaoke_reservation.agent_name 
				,karaoke_reservation.agent_address 
				,karaoke_reservation.time
				,karaoke_reservation.agent_phone 
				,karaoke_reservation.departure_time
				,karaoke_reservation.note 
			 from 
			 	karaoke_reservation
				left outer join reservation_room on reservation_room.id=karaoke_reservation.reservation_room_id 
				left outer join traveller on traveller.id=reservation_room.traveller_id
			where
				karaoke_reservation.id = '.URL::get('id'));
		if($row = DB::fetch())
		{
			$this->parse_layout('delete',$row);
		}
	}
	function delete($id)
	{
		if(User::can_delete(false,ANY_CATEGORY)){
			$can_delete = false;
			if($row = DB::fetch('select * from karaoke_reservation where id = '.$id.'')){
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
						Agent name: '.$row['agent_name'].'<br>
						Agent address: '.$row['agent_address'].'<br>
						Agent phone: '.$row['agent_phone'].'<br>
						Agent fax: '.$row['agent_fax'].'<br>
						Arrival time: '.date('H:i d/m/Y',$row['arrival_time']).'<br>
						Departure time:  '.date('H:i d/m/Y',$row['departure_time']).'<br>
						Note: '.$row['note'].'
					';
					DB::delete_id('karaoke_reservation', $id);
					DB::delete('karaoke_reservation_table', 'karaoke_reservation_id = '.$id);
					DB::delete('karaoke_reservation_product', 'karaoke_reservation_id = '.$id);
					DB::delete('payment', 'bill_id='.$id.' and type = \'KARAOKE\'');
					require_once 'packages/hotel/includes/php/product.php';
					if($karaokes =DB::fetch('select karaoke.* from karaoke where id='.$row['karaoke_id'].' and portal_id=\''.PORTAL_ID.'\'')){
						DeliveryOrders::delete_delivery_order($id,$karaokes['code']);
					}
					System::log('delete','Delete from karaoke reservation with id: \''.$id.'\'',$log_description,$id);
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