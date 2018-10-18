<?php
class TourInvoiceReservationForm extends Form
{
	function TourInvoiceReservationForm()
	{
		Form::Form('TourInvoiceReservationForm');
		$this->link_css("packages/hotel/skins/default/css/invoice.css");
	}
	function draw()
	{	
		require_once 'packages/hotel/packages/reception/modules/includes/get_reservation.php';
		$this->map = array();
		$this->map = get_reservation(Url::iget('id'));
		$this->parse_layout('tour_invoice',$this->map);	
	}
}
?>