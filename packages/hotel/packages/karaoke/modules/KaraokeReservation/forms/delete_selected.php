<?php
class DeleteSelectedKaraokeReservationNewForm extends Form
{
	function DeleteSelectedKaraokeReservationNewForm()
	{
		Form::Form("DeleteSelectedKaraokeReservationNewForm");
		$this->add('confirm',new TextType(true,false,0, 20));
	}
	function on_submit()
	{
		if(URL::get('confirm'))
		{
			require_once 'delete.php';
			foreach(URL::get('selected_ids') as $id)
			{
				DeleteKaraokeReservationNewForm::delete($id);
			}
			Url::redirect_current(array('karaoke_reservation_receptionist_id',));
		}
	}
	function draw()
	{
		DB::query('
			select 
				karaoke_reservation.id,karaoke_reservation.code,karaoke_reservation.status ,karaoke_reservation.num_table,
				karaoke_reservation.arrival_time,karaoke_reservation.agent_name ,karaoke_reservation.agent_address ,
				karaoke_reservation.time,karaoke_reservation.agent_phone ,
				karaoke_reservation.departure_time,karaoke_reservation.note 
			from 
			 	karaoke_reservation
				left outer join karaoke on karaoke.id=karaoke_reservation.karaoke_id 
			where karaoke_reservation.id in ('.join(URL::get('selected_ids'),',').')
		');
		$items = DB::fetch_all();
		foreach($items as $k=>$itm)
		{
			$items[$k]['arrival_date'] = date('d/m/Y H:i',$itm['arrival_time']);
			if($itm['departure_time']>$itm['arrival_time'])
			{
				$items[$k]['time_length'] = date('H\h i',strtotime('1/1/2000')+$itm['departure_time']-$itm['arrival_time']);
			}
			else
			{
				$items[$k]['time_length'] = '';
			}
		}
		DB::query('
			select
				id,name
			from
				karaoke
			');
		$karaokes = DB::fetch_all();
		$this->parse_layout('delete_selected',
			array(
				'items'=>$items
			)
		);
	}
}
?>