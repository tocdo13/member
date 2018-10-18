<?php
class GroupInvoiceReservationForm extends Form
{
	function GroupInvoiceReservationForm()
	{
		Form::Form('GroupInvoiceReservationForm');
		$this->link_css("packages/hotel/skins/default/css/invoice.css");
	}
	function draw()
	{
		require_once 'packages/hotel/packages/reception/modules/includes/get_reservation.php';
		$this->map = array();
		$this->map = get_reservation(Url::iget('id'));
		if(Url::get(md5('print')) == md5('1'))
        {
			$this->map['preview'] = '';
		}
        else
        {
			$this->map['preview'] = Portal::language('preview_order').' '.Portal::language('not_for_payment');
		}
		$this->parse_layout('group_invoice',$this->map);
	}
}
?>