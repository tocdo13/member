<?php
class DeleteReservationForm extends Form
{
	function DeleteReservationForm()
	{
		Form::Form('DeleteReservationForm');
		$this->add('id',new IDType(true,'object_not_exists','reservation'));
	}
	function on_submit()
	{
		if($this->check())
		{
		echo '</script><script src="packages/hotel/includes/js/ajax.js"></script>';
			require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
			$this->delete($_REQUEST['id']);
			if(!$this->is_error())
			{
				echo '<div id="progress">Updating room status to server...</div><script>window.setTimeout("location=\''.Url::build_current(array('customer_id')).'\'",1000);</script>';
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
		$this->parse_layout('delete',$row);
	}
	function permanent_delete($id)
	{
		$row = DB::select('reservation',$id);
		$item_rows = DB::select_all('reservation_room','reservation_id='.$id.(URL::get('reservation_room_id')?' and id=\''.URL::get('reservation_room_id').'\'':''));
		foreach($item_rows as $item_row)
		{
			DB::delete('reservation_traveller','reservation_room_id='.$item_row['id']);
		}
		DB::update('room_status',array('reservation_id'=>0,'status'=>'AVAILABLE'),'reservation_id='.$id);
		DB::delete('reservation_room', 'reservation_id='.$id.(URL::get('reservation_room_id')?' and id=\''.URL::get('reservation_room_id').'\'':'')); 
		if(!URL::get('reservation_room_id'))
		{
			DB::delete_id('reservation', $id);
		}
	}
	function delete($id)
	{
		if($row = DB::fetch('
			select
				reservation.id,
				reservation.customer_id,
				customer.name as customer_name,
				reservation.note
			from
				reservation
				left outer join customer on customer.id=reservation.customer_id 
			where reservation.id=\''.$id.'\''
		))
		{
			if(!URL::get('reservation_room_id'))
			{
				$title = 'Delete reservation #'.$id;
				$description = ''
				.'Code:'.substr($id,0,255).'<br>  ' 
				.'Note:'.substr($row['note'],0,255).'<br>  ' 
				.($row['customer_id']?'Customer name:'.$row['customer_name'].'<br>  ':'');
				$reservation_rooms = DB::fetch_all('
					select
						reservation_room.id,
						reservation_room.status,
						arrival_time,
						departure_time,
						price,
						room.name as room_name
					from
						reservation_room
						inner join room on room_id=room.id
					where reservation_room.reservation_id='.$id
				);
				$description .= '<u>Rooms:</u>';
				foreach($reservation_rooms as $reservation_room)
				{
					if(($reservation_room['status']=='CHECKIN' or $reservation_room['status']=='CHECKOUT') and !USER::can_admin(false,ANY_CATEGORY))
					{
						$this->error('','Cannot delete reservation because it has been checkin or checkout');
					}
					$description .= '<li>Room:'.$reservation_room['room_name'].' '.$reservation_room['price'].' from '.$reservation_room['arrival_time'].' to '.$reservation_room['departure_time'].'</li>';
				}
				if(!$this->is_error())
				{
					DeleteReservationForm::permanent_delete($id);
					System::log('delete',$title,$description,$id);
				}
				else
				{
					System::log('delete',$title.'. But dont have permission!',$description,$id);
				}
			}
			else
			{
				$this->delete_reservation_room($id,URL::get('reservation_room_id'));
			}
		}
	}
	function delete_reservation_room($reservation_id, $reservation_room_id)
	{
		if($reservation_room = DB::fetch('
			select
				reservation_room.id,
				reservation_room.status,
				arrival_time,
				departure_time,
				reservation_room.price,
				room.name as room_name
			from
				reservation_room
				inner join room on room_id=room.id
			where reservation_room.id='.$reservation_room_id
		))
		{
			$title = 'Delete room '.$reservation_room['room_name'].' from reservation <a href=\'?page=reservation&cmd=edit&id='.$reservation_id.'\'>#'.$reservation_id.'</a>';
			$description = 'Room:'.$reservation_room['room_name'].' '.$reservation_room['price'].' from '.$reservation_room['arrival_time'].' to '.$reservation_room['departure_time'];
			if(($reservation_room['status']=='CHECKIN' or $reservation_room['status']=='CHECKOUT') and !USER::can_admin(false,ANY_CATEGORY))
			{
				$this->error('','Cannot delete reservation because it has been checkin or checkout');
				System::log('delete',$title.'. But dont have permission!',$description,$reservation_id);
			}
			else
			{
				DB::update('room_status',array('status'=>'AVAILABLE','reservation_id'=>0),'reservation_room_id='.$reservation_room['id']);
				DB::delete('reservation_traveller','reservation_room_id='.$reservation_room['id']);
				DB::delete_id('reservation_room',$reservation_room['id']);
				DB::delete('by_by_currency','bill_id='.$reservation_room['id'].' and type=\'RESERVATION\'');
				DB::delete('reservation_room_service','reservation_room_id='.$reservation_room['id'].'');
				System::log('delete',$title,$description,$reservation_id);
			}
		}
	}
}
?>