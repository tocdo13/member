<?php
class DeleteSelectedBarReservationForm extends Form
{
	function DeleteSelectedBarReservationForm()
	{
		Form::Form("DeleteSelectedBarReservationForm");
		$this->add('confirm',new TextType(true,false,0, 20));
	}
	function on_submit()
	{
		if(URL::get('confirm'))
		{
			require_once 'delete.php';
			foreach(URL::get('selected_ids') as $id)
			{
				DeleteBarReservationForm::delete($id);
			}
			Url::redirect_current(array(  'bar_reservation_bar_id', 'bar_reservation_receptionist_id',));
		}
	}
	function draw()
	{
		DB::query('
			select 
				bar_reservation.id,bar_reservation.code,bar_reservation.status ,bar_reservation.num_table,
				bar_reservation.arrival_time,bar_reservation.agent_name ,bar_reservation.agent_address ,
				bar_reservation.time,bar_reservation.agent_phone ,
				bar_reservation.departure_time,bar_reservation.note 
			from 
			 	bar_reservation
				left outer join bar on bar.id=bar_reservation.bar_id 
			where bar_reservation.id in ('.join(URL::get('selected_ids'),',').')
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
				bar
			');
		$bars = DB::fetch_all();
		$this->parse_layout('delete_selected',
			array(
				'items'=>$items
			)
		);
	}
}
?>