<?php
class DeleteSelectedReservationForm extends Form
{
	function DeleteSelectedReservationForm()
	{
		Form::Form("DeleteSelectedReservationForm");
		$this->add('confirm',new TextType(true,false,0, 20));
	}
	function on_submit()
	{
		if(URL::get('confirm'))
		{
			require_once 'delete.php';
			foreach(URL::get('selected_ids') as $id)
			{
				DeleteReservationForm::delete($id);
			}
			Url::redirect_current(array('reservation_customer_id',
	));
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
			where reservation.id in ('.join(URL::get('selected_ids'),',').')
		');
		$items = DB::fetch_all();
		DB::query('
			select
				id,customer.name customer_name
			from
				customer
			');
		$customers = DB::fetch_all();
		$this->parse_layout('delete_selected',
			array(
				'items'=>$items
			)
		);
	}
}
?>