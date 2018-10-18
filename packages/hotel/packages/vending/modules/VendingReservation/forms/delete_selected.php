<?php
class DeleteSelectedBarReservationNewForm extends Form
{
	function DeleteSelectedBarReservationNewForm()
	{
		Form::Form("DeleteSelectedBarReservationNewForm");
		$this->add('confirm',new TextType(true,false,0, 20));
	}
	function on_submit()
	{
		if(URL::get('confirm'))
		{
			require_once 'delete.php';
			foreach(URL::get('selected_ids') as $id)
			{
				DeleteBarReservationNewForm::delete($id);
			}
			Url::redirect_current();
		}
	}
	function draw()
	{
		DB::query('
			select 
				ve_reservation.id,
                ve_reservation.code,
                ve_reservation.status,
				ve_reservation.arrival_time,
                ve_reservation.agent_name,
                ve_reservation.agent_address,
				ve_reservation.time,
                ve_reservation.agent_phone,
                ve_reservation.note 
			from 
			 	ve_reservation
			where 
                ve_reservation.id in ('.join(URL::get('selected_ids'),',').')
		');
		$items = DB::fetch_all();
		foreach($items as $k=>$itm)
		{
			$items[$k]['arrival_date'] = date('d/m/Y H:i',$itm['arrival_time']);
            $items[$k]['time_length'] = '';
		}
		$this->parse_layout('delete_selected',array('items'=>$items));
	}
}
?>